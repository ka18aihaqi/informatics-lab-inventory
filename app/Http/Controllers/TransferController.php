<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Location;
use App\Models\TransferLog;
use Illuminate\Http\Request;
use App\Models\AllocatedItem;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AllocatedComputer;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TransferController extends Controller
{
    public function index()
    {  
        $locations = Location::all();
        $query = \App\Models\TransferLog::with([
            'fromLocation:id,name,description',
            'toLocation:id,name,description',
        ]);
    
        if (request()->has('date') && request('date') !== null) {
            $query->whereDate('transferred_at', request('date'));
        }        
    
        $transferLogs = $query->latest()->get();        
    
        return view('transfers.index', compact('transferLogs', 'locations'));
    }

    public function getDesksByLocation($locationId)
    {
        $desks = \App\Models\AllocatedComputer::where('location_id', $locationId)->get(['id', 'desk_number']);

        return response()->json($desks);
    }

    public function getItemsByLocation($locationId)
    {
        $items = AllocatedItem::with('otherItem')
            ->where('location_id', $locationId)
            ->get();
    
        return response()->json($items);
    }

    public function getDeskComponents($locationId, $deskNumber)
    {
        $pc = \App\Models\AllocatedComputer::with([
            'computer:id,item_name,description',
            'diskDrive1:id,item_name,description',
            'diskDrive2:id,item_name,description',
            'processor:id,item_name,description',
            'vgaCard:id,item_name,description',
            'ram:id,item_name,description',
            'monitor:id,item_name,description',
        ])
        ->where('location_id', $locationId)
        ->where('desk_number', $deskNumber)
        ->first();
    
        return response()->json($pc);
    }

    public function transferAllocatedComputer(Request $request)
    {
    $validated = $request->validate([
        'location_id' => 'required|exists:locations,id',
        'desk_number' => 'required|integer',
        'to_location_id' => 'required|exists:locations,id',
        'to_desk_number' => 'nullable|integer',
        'components' => 'required|array',
        'components.*' => 'in:computer_id,disk_drive_1_id,disk_drive_2_id,processor_id,vga_card_id,ram_id,monitor_id',
    ]);

    DB::beginTransaction();

    try {
        $fromComputer = AllocatedComputer::where('location_id', $validated['location_id'])
            ->where('desk_number', $validated['desk_number'])
            ->firstOrFail();

        $fromLocationId = $fromComputer->location_id;
        $toLocationId = $validated['to_location_id'];
        $toDeskNumber = $validated['to_desk_number'] ?? null;
        $components = $validated['components'];

        // Hanya buat toComputer jika toDeskNumber ada
        $toComputer = null;
        if ($toDeskNumber !== null) {
            $toComputer = AllocatedComputer::firstOrCreate([
                'location_id' => $toLocationId,
                'desk_number' => $toDeskNumber,
            ]);
        }

        $fromLocation = \App\Models\Location::findOrFail($fromLocationId);
        $toLocation = \App\Models\Location::findOrFail($toLocationId);

        $conflictMessages = [];
        $latestTransfer = null;

        foreach ($components as $componentField) {
            $componentId = $fromComputer->$componentField;

            if ($componentId) {
                $component = null;
                $componentName = null;

                switch ($componentField) {
                    case 'computer_id':
                        $component = $fromComputer->computer;
                        break;
                    case 'disk_drive_1_id':
                        $component = $fromComputer->diskDrive1;
                        break;
                    case 'disk_drive_2_id':
                        $component = $fromComputer->diskDrive2;
                        break;
                    case 'processor_id':
                        $component = $fromComputer->processor;
                        break;
                    case 'vga_card_id':
                        $component = $fromComputer->vgaCard;
                        break;
                    case 'ram_id':
                        $component = $fromComputer->ram;
                        break;
                    case 'monitor_id':
                        $component = $fromComputer->monitor;
                        break;
                }

                $componentName = trim(($component->item_name ?? '') . ' ' . ($component->description ?? ''));

                if ($component) {
                    if ($toDeskNumber !== null) {
                        // Transfer ke komputer lain
                        if ($toComputer->$componentField !== null) {
                            $conflictMessages[] = "Desk number <strong>{$toComputer->desk_number}</strong> in <strong>{$toLocation->name}</strong> is already assigned to a <strong>$componentName</strong>.";
                            continue;
                        }

                        $toComputer->$componentField = $componentId;
                    } else {
                        // Simpan ke allocated_items
                        $itemId = $component->id ?? null;

                        if (!$itemId) {
                            throw new \Exception("Component '{$componentName}' does not have item_id.");
                        }
                    
                        $existingAllocatedItem = AllocatedItem::where('location_id', $toLocationId)
                        ->where('other_item_id', $itemId)
                        ->first();
                        
                        if ($existingAllocatedItem) {
                            $existingAllocatedItem->increment('stock');
                        } else {
                            AllocatedItem::create([
                                'location_id' => $toLocationId,
                                'other_item_id' => $itemId,
                                'description' => "Hasil transfer dari {$fromLocation->name} meja {$fromComputer->desk_number}",
                                'stock' => 1,
                            ]);
                        }
                    }

                    // Hapus dari komputer asal
                    $fromComputer->$componentField = null;

                    // Log transfer
                    $latestTransfer = TransferLog::create([
                        'item_id' => $component->id,
                        'item_type' => get_class($component),
                        'item_name' => $componentName,
                        'from_location_id' => $fromLocationId,
                        'to_location_id' => $toLocationId,
                        'quantity' => 1,
                        'note' => "Transfer komponen {$componentName} dari {$fromLocation->name} meja {$fromComputer->desk_number}" .
                            ($toDeskNumber ? " ke {$toLocation->name} meja {$toDeskNumber}" : " ke {$toLocation->name}"),
                    ]);
                }
            }
        }

        if (!empty($conflictMessages)) {
            $errorMessage = implode('<br>', $conflictMessages);
            throw new \Exception($errorMessage);
        }

        $fromComputer->save();
        if ($toComputer) {
            $toComputer->save();
        }

        DB::commit();

        return redirect()
            ->route('transfers.index', ['date' => $latestTransfer?->created_at->format('Y-m-d') ?? now()->format('Y-m-d')])
            ->with('success', "<strong>Transfer Successful.</strong><br>Computer device from <strong>{$fromLocation->name}</strong> (Desk <strong>{$fromComputer->desk_number}</strong>) has been successfully transferred to <strong>{$toLocation->name}</strong>" . ($toDeskNumber ? " (Desk <strong>{$toDeskNumber}</strong>)" : " (Allocated Storage)."));
    } catch (\Throwable $e) {
        DB::rollBack();
        $message = nl2br($e->getMessage());
        return redirect()->back()->with('error', '<strong>Transfer Failed.</strong><br>' . $message);
    }
    }

    public function transferAllocatedItem(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'item_id' => 'required|integer|exists:allocated_items,id',
            'from_location_id' => 'required|exists:locations,id',
            'to_location_id' => 'required|exists:locations,id',
            'quantity' => 'required|integer|min:1',
            'transferred_at' => 'nullable|date',
        ]);
    
        DB::beginTransaction();
    
        try {
            $AllocatedItem = AllocatedItem::findOrFail($request->item_id);
            $quantity = $request->quantity;

            // dd($item->location_id, $request->from_location_id);
    
            // Cek kondisi validasi stock dan location
            if ($AllocatedItem->location_id != $request->from_location_id) {
                return redirect()->back()->with('error', "<strong>Transfer Failed.</strong><br>Location is invalid.");
            }
            
            if ($AllocatedItem->stock < $quantity) {
                return redirect()->back()->with('error', "<strong>Transfer Failed.</strong><br><strong>{$AllocatedItem->otherItem->item_name}</strong> is out of stock.");
            }
            
            
            $fromLocation = Location::findOrFail($request->from_location_id);
            $toLocation = Location::findOrFail($request->to_location_id);   

            // Buat transfer log
            $log = TransferLog::create([
                'item_type' => AllocatedItem::class,
                'item_id' => $AllocatedItem->id,
                'item_name' => in_array($AllocatedItem->otherItem->item_type, ['disk_drive', 'processor', 'vga_card', 'ram', 'monitor'])
                    ? $AllocatedItem->otherItem->item_name . ' ' . $AllocatedItem->otherItem->description
                    : $AllocatedItem->otherItem->item_name,
                'from_location_id' => $request->from_location_id,
                'to_location_id' => $request->to_location_id,
                'quantity' => $quantity,
                'note' => "Transfer {$AllocatedItem->otherItem->item_name} dari {$fromLocation->name} ke {$toLocation->name}",
                'transferred_at' => $request->transferred_at ?? now(),
            ]);            
    
            // Update stok asal
            $AllocatedItem->stock -= $quantity;
            $AllocatedItem->save();
    
            // Tambah ke lokasi tujuan
            $existingItem = AllocatedItem::where('other_item_id', $AllocatedItem->other_item_id)
                ->where('location_id', $request->to_location_id)
                ->first();
    
            if ($existingItem) {
                $existingItem->stock += $quantity;
                $existingItem->save();
            } else {
                AllocatedItem::create([
                    'other_item_id' => $AllocatedItem->other_item_id,
                    'location_id' => $request->to_location_id,
                    'stock' => $quantity,
                ]);
            }
    
            DB::commit();
    
            return redirect()
                ->route('transfers.index', ['date' => $log->created_at->format('Y-m-d')])
                ->with('success', "<strong>Transfer Successful.</strong><br><strong>$log->item_name</strong> was successfully transferred.");

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '<strong>Transfer Failed.</strong> ' . $e->getMessage());
        }
    }

    public function downloadPDF(Request $request)
    {
        $transferredDate = $request->query('transferred_at'); // Ambil tanggal dari query
    
        $query = TransferLog::query();
    
        // Filter berdasarkan tanggal yang dipilih
        if (!empty($transferredDate)) {
            $query->whereDate('transferred_at', $transferredDate);
        }
    
        $transferLogs = $query->orderBy('transferred_at', 'desc')->get(); // Ambil data
    
        // Load view dengan data yang telah dipisah
        $pdf = Pdf::loadView('pdf.transfer_report', compact('transferLogs', 'transferredDate'));
    
        // Format nama file menjadi "TRANSFER-ASSET-REPORT-(Tanggal).pdf"
        $fileName = 'TRANSFER-REPORT-' . ($transferredDate ?? 'All') . '.pdf';
    
        return $pdf->download($fileName);
    }  
}
