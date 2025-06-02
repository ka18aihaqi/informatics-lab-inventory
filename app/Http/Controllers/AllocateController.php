<?php

namespace App\Http\Controllers;

use App\Models\Ram;
use App\Models\Item;
use App\Models\VgaCard;
use App\Models\Location;
use App\Models\DiskDrive;
use App\Models\OtherItem;
use App\Models\Processor;
use Illuminate\Http\Request;
use App\Models\AllocatedItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use App\Models\AllocatedComputer;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;

class AllocateController extends Controller
{
    public function index(Request $request)
    {
        $locationId = $request->get('location');
        $locations = Location::all();

        $allocatedItems = AllocatedItem::all();
        $allocatedComputers = AllocatedComputer::all();

        // Filter data berdasarkan lokasi jika ada parameter 'location'
        $allocatedComputers = AllocatedComputer::when($locationId, function ($query) use ($locationId) {
            return $query->where('location_id', $locationId);
        })->get();

        $allocatedItems = AllocatedItem::when($locationId, function ($query) use ($locationId) {
            return $query->where('location_id', $locationId);
        })->get();

        $computers = Item::where('item_type', 'computer')->get();
        $diskDrives = Item::where('item_type', 'disk_drive')->get();
        $processors = Item::where('item_type', 'processor')->get();
        $vgaCards = Item::where('item_type', 'vga_card')->get();
        $rams = Item::where('item_type', 'ram')->get();
        $monitors = Item::where('item_type', 'monitor')->get();
        $otherItems = Item::all();

        $selectedLocation = $locationId ? Location::find($locationId) : null;

        return view('allocates.index', compact(
            'allocatedComputers', 
            'allocatedItems', 
            'locations', 
            'computers',
            'diskDrives', 
            'processors', 
            'vgaCards', 
            'rams',
            'monitors',
            'otherItems',
            'selectedLocation'
        ));
    }

    public function getAvailableDesks($location_id)
    {
        // Ambil desk_number yang sudah dipakai di lokasi itu
        $usedDesks = AllocatedComputer::where('location_id', $location_id)->pluck('desk_number')->toArray();

        // Semua desk dari 1–50
        $allDesks = range(1, 50);

        // Hitung yang belum dipakai
        $availableDesks = array_diff($allDesks, $usedDesks);

        return response()->json(array_values($availableDesks));
    }

    public function create()
    {
        return view('allocates.create');
    }

    public function store(Request $request)
    {
        $itemType = $request->input('item_type');

        if ($itemType === 'other_item') {
            $validated = $request->validate([
                'location_id' => 'required|exists:locations,id',
                'other_item_id' => 'required|exists:items,id',
                'description' => 'nullable|string',
                'stock' => 'required|integer|min:1',
            ]);
    
            $otherItem = \App\Models\Item::findOrFail($request['other_item_id']);
    
            if ($otherItem->stock < $request['stock']) {
                return redirect()->back()->with('error', "<strong>Add Failed.</strong><br><strong>$otherItem->name</strong> is out of stock.");
            }
    
            $otherItem->decrement('stock', $request['stock']);
    
            $allocatedItem = AllocatedItem::where('location_id', $validated['location_id'])
                ->where('other_item_id', $validated['other_item_id'])
                ->first();

            if ($allocatedItem) {
                $allocatedItem->increment('stock', $validated['stock']);
                if (!empty($validated['description'])) {
                    $allocatedItem->description = $validated['description'];
                    $allocatedItem->save();
                }
            } else {
                $allocatedItem = AllocatedItem::create($validated);
            }
    
            return redirect()->route('allocates.index', ['location' => $allocatedItem->location_id])->with('success', "<strong>Added Successfully.</strong><br><strong>$otherItem->name</strong> added successfully.");
        } elseif ($itemType === 'computer') {
            // dd($request->all());
            // Validasi input
            $validated = $request->validate([
                'location_id' => 'required|exists:locations,id',
                'desk_number' => [
                    'required',
                    'integer',
                    'min:0',
                    Rule::unique('allocated_computers')->where(fn ($query) =>
                        $query->where('location_id', $request->location_id)
                    ),
                ],
                'computer_id' => 'nullable|exists:items,id',
                'disk_drive_1_id' => 'nullable|exists:items,id',
                'disk_drive_2_id' => 'nullable|exists:items,id',
                'processor_id' => 'nullable|exists:items,id',
                'vga_card_id' => 'nullable|exists:items,id',
                'ram_id' => 'nullable|exists:items,id',
                'monitor_id' => 'nullable|exists:items,id',
                'year_approx' => 'nullable|digits:4|integer|min:2000|max:' . date('Y'),
                'ups_status' => 'nullable|in:Active,Inactive',
            ]);

            try {
                // Cek stok komponen sebelum buat PC
                $components = [
                    'computer_id' => \App\Models\Item::class,
                    'disk_drive_1_id' => \App\Models\Item::class,
                    'disk_drive_2_id' => \App\Models\Item::class,
                    'processor_id' => \App\Models\Item::class,
                    'vga_card_id' => \App\Models\Item::class,
                    'ram_id' => \App\Models\Item::class,
                    'monitor_id' => \App\Models\Item::class,
                ];

                $componentLabels = [
                    'computer_id' => 'Computer',
                    'disk_drive_1_id' => 'Disk Drive 1',
                    'disk_drive_2_id' => 'Disk Drive 2',
                    'processor_id' => 'Processor',
                    'vga_card_id' => 'VGA Card',
                    'ram_id' => 'RAM',
                    'monitor_id' => 'Monitor',
                ]; 

                // Error Message
                $errors = [];

                foreach (array_keys($components) as $field) {
                    if (!empty($validated[$field])) {
                        $model = $components[$field];
                        $item = $model::find($validated[$field]);
                        if ($item && $item->stock <= 0) {
                            $label = $componentLabels[$field] ?? $field;
                            $errors[] = "<strong>$label</strong> is out of stock.";
                        }
                    }
                }
                
                if (!empty($errors)) {
                    $message = implode('<br>', $errors);
                    throw new \Exception($message);
                }          
                // --   

                // Buat data PC
                $allocatedComputer = AllocatedComputer::create($validated);

                // Generate QR code
                $locationSlug = str_replace(' ', '-', strtolower($allocatedComputer->location->name));
                $deskNumber = $validated['desk_number'];
                $qrContent = route('allocates.show', [
                    'location' => $locationSlug,
                    'desk' => $deskNumber
                ]);                

                $qrResult = Builder::create()
                    ->writer(new PngWriter())
                    ->data($qrContent)
                    ->size(200)
                    ->build();

                // Simpan QR Code ke storage
                $qrFileName = "qr_codes/{$locationSlug}_No.{$deskNumber}_" . time() . ".png";

                try {
                    Storage::disk('public')->put($qrFileName, $qrResult->getString());
                    $allocatedComputer->update(['qr_code' => $qrFileName]);
                } catch (\Exception $e) {
                    $allocatedComputer->delete(); // rollback data jika gagal
                    return back()->withErrors(['qr_code' => 'Gagal menyimpan QR Code.']);
                }

                // Kurangi stok komponen
                foreach (array_keys($components) as $field) {
                    if (!empty($validated[$field])) {
                        $model = $components[$field];
                        $model::where('id', $validated[$field])->decrement('stock');
                    }
                }

                return redirect()
                    ->route('allocates.index', ['location' => $allocatedComputer->location_id])
                    ->with('success', "<strong>Added Successfully.</strong><br>Desk number <strong>$deskNumber</strong> in <strong>{$allocatedComputer->location->name}</strong> has been added successfully.");
            }catch (\Throwable $e) {
                $message = nl2br($e->getMessage());
                return redirect()->back()->with('error', '<strong>Add Failed.</strong><br>' . $message);
            }
        }

    }

    public function show($location, $desk)
    {
        $allocatedComputer = AllocatedComputer::with(['location', 'diskDrive1', 'diskDrive2', 'processor', 'vgaCard', 'ram', 'monitor'])
            ->where('desk_number', $desk)
            ->whereHas('location', function ($query) use ($location) {
                $query->whereRaw("REPLACE(LOWER(name), ' ', '-') = ?", [$location]);
            })
            ->first();

        if (!$allocatedComputer) {
            abort(404, 'Allocated computer not found');
        }

        return view('allocates.components.computer_details', compact('allocatedComputer'));
    }

    public function edit($id)
    {
        $allocatedComputer = AllocatedComputer::find($id);
        $allocatedItem = AllocatedItem::find($id);

        if ($allocatedComputer) {
            return view('allocates.edit', ['item' => $allocatedComputer, 'type' => 'computer']);
        } elseif ($allocatedItem) {
            return view('allocates.edit', ['item' => $allocatedItem, 'type' => 'other_item']);
        } else {
            abort(404);
        }
    }  

    public function update(Request $request, $id)
    {
        $itemType = $request->input('item_type');

        if ($itemType === 'other_item') {
            $allocatedItem = AllocatedItem::findOrFail($id);

            $validated = $request->validate([
                'description' => 'nullable|string',
                'stock' => 'required|integer|min:0', // harus required karena pasti diisi
            ]);
            
            $oldStock = $allocatedItem->stock;
            $newStock = $validated['stock'];
            
            $asset = Item::findOrFail($allocatedItem->other_item_id);
            
            if ($newStock != $oldStock) {
                $diff = $newStock - $oldStock;
            
                if ($diff > 0 && $asset->stock < $diff) {
                    return redirect()->back()->with('error', "<strong>Update failed.</strong><br><strong>$asset->name</strong> is out of stock.");
                }
            
                $diff > 0
                    ? $asset->decrement('stock', $diff)
                    : $asset->increment('stock', abs($diff));
            }
            
            $allocatedItem->update([
                'stock' => $newStock,
                'description' => $validated['description'] ?? null,
            ]);
            
            return redirect()
                ->route('allocates.index', ['location' => $allocatedItem->location_id])
                ->with('success', "<strong>Updated successfully.</strong><br><strong>$asset->name</strong> has been successfully updated.");            
        } elseif ($itemType === 'computer') {
            $allocatedComputer = AllocatedComputer::findOrFail($id);

            $validated = $request->validate([
                'computer_id' => 'nullable|exists:items,id',
                'disk_drive_1_id' => 'nullable|exists:items,id',
                'disk_drive_2_id' => 'nullable|exists:items,id',
                'processor_id' => 'nullable|exists:items,id',
                'vga_card_id' => 'nullable|exists:items,id',
                'ram_id' => 'nullable|exists:items,id',
                'monitor_id' => 'nullable|exists:items,id',
                'year_approx' => 'nullable|digits:4|integer|min:2000|max:' . date('Y'),
                'ups_status' => 'nullable|in:Active,Inactive',
            ]);
            
            try {
            $oldComponentIds = [
                'computer_id' => $allocatedComputer->computer_id,
                'disk_drive_1_id' => $allocatedComputer->disk_drive_1_id,
                'disk_drive_2_id' => $allocatedComputer->disk_drive_2_id,
                'processor_id' => $allocatedComputer->processor_id,
                'vga_card_id' => $allocatedComputer->vga_card_id,
                'ram_id' => $allocatedComputer->ram_id,
                'monitor_id' => $allocatedComputer->monitor_id,
            ];
            
            $components = [
                'computer_id' => \App\Models\Item::class,
                'disk_drive_1_id' => \App\Models\Item::class,
                'disk_drive_2_id' => \App\Models\Item::class,
                'processor_id' => \App\Models\Item::class,
                'vga_card_id' => \App\Models\Item::class,
                'ram_id' => \App\Models\Item::class,
                'monitor_id' => \App\Models\Item::class,
            ];

            $componentLabels = [
                'computer_id' => 'Computer',
                'disk_drive_1_id' => 'Disk Drive 1',
                'disk_drive_2_id' => 'Disk Drive 2',
                'processor_id' => 'Processor',
                'vga_card_id' => 'VGA Card',
                'ram_id' => 'RAM',
                'monitor_id' => 'Monitor',
            ];            
            
            $errors = []; // buat array untuk simpan error

            foreach ($components as $field => $model) {
                $oldId = $oldComponentIds[$field] ?? null;
                $newId = $validated[$field] ?? null;
            
                if ($oldId != $newId && $newId) {
                    $component = $model::find($newId);
                    if ($component && $component->stock < 1) {
                        $label = $componentLabels[$field] ?? $field;
                        $errors[] = "<strong>$label</strong> is out of stock.";
                    }
                }
            }
            
            if (!empty($errors)) {
                $message = "<strong>Update Failed.</strong><br>" . implode('<br>', $errors);
                return redirect()->back()->with('error', $message);
            }
            
            // ✅ Lanjut update PC
            $allocatedComputer->update($validated);
            
            // ✅ Update stok (increment/decrement)
            foreach ($components as $field => $model) {
                $oldId = $oldComponentIds[$field] ?? null;
                $newId = $validated[$field] ?? null;
            
                if ($oldId != $newId) {
                    if ($oldId) {
                        $model::where('id', $oldId)->increment('stock');
                    }
                    if ($newId) {
                        $model::where('id', $newId)->decrement('stock');
                    }
                }
            }
            
            return redirect()
                ->route('allocates.index', ['location' => $allocatedComputer->location_id])
                ->with('success', '<strong>Updated successfully.</strong><br><strong>Computer Device</strong> has been successfully updated.');            
            }catch (\Throwable $e) {
                $message = nl2br($e->getMessage());
                return redirect()->back()->with('error', '<strong>Add Failed.</strong><br>' . $message);
            }
        }
    }

    public function destroy(Request $request, $id)
    {
        $itemType = $request->input('item_type');

        if ($itemType === 'other_item') {
            $allocatedItem = AllocatedItem::findOrFail($id);
        
            // Kembalikan stok ke asset terkait
            $otherItem = Item::findOrFail($allocatedItem->other_item_id);
            $otherItem->increment('stock', $allocatedItem->stock);
        
            $allocatedItem->delete();
        
            return redirect()->route('allocates.index', ['location' => $allocatedItem->location_id])->with('success', '<strong>Deleted successfully.</strong><br><strong>Item</strong> has been successfully deleted.');
        } else {
            $allocatedComputer = AllocatedComputer::findOrFail($id);

            // Hapus file QR Code dari storage (jika ada)
            if ($allocatedComputer->qr_code && Storage::disk('public')->exists($allocatedComputer->qr_code)) {
                Storage::disk('public')->delete($allocatedComputer->qr_code);
            }

            // Kembalikan stok komponen
            $components = [
                'computer_id' => \App\Models\Item::class,
                'disk_drive_1_id' => \App\Models\Item::class,
                'disk_drive_2_id' => \App\Models\Item::class,
                'processor_id' => \App\Models\Item::class,
                'vga_card_id' => \App\Models\Item::class,
                'ram_id' => \App\Models\Item::class,
                'monitor_id' => \App\Models\Item::class,
            ];

            foreach ($components as $field => $model) {
                $componentId = $allocatedComputer->$field;
                if ($componentId) {
                    $model::where('id', $componentId)->increment('stock');
                }
            }

            // Hapus data dari database
            $allocatedComputer->delete();

            return redirect()
                ->route('allocates.index', ['location' => $allocatedComputer->location_id])
                ->with('success', '<strong>Deleted successfully.</strong><br><strong>Computer Device</strong> has been successfully deleted.');
        }
    }
    
    public function downloadPDF($location_id)
    {
        $location = Location::with([
            'allocatedComputer.computer',
            'allocatedComputer.diskDrive1',
            'allocatedComputer.diskDrive2',
            'allocatedComputer.processor',
            'allocatedComputer.vgaCard',
            'allocatedComputer.ram',
            'allocatedItem.otherItem'
        ])->findOrFail($location_id);

        $pdf = Pdf::loadView('pdf.allocated_report', compact('location'));

        return $pdf->download('laporan_inventaris_' . $location->name . '.pdf');
    }
}
