<?php

namespace App\Http\Controllers;

use App\Models\Ram;
use App\Models\Item;
use App\Models\Monitor;
use App\Models\VgaCard;
use App\Models\DiskDrive;
use App\Models\OtherItem;
use App\Models\Processor;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryController extends Controller
{
    public function index()
    {
        $itemType = request()->item_type;
    
        $items = Item::when($itemType && $itemType !== 'all_items', function ($query) use ($itemType) {
            return $query->where('item_type', $itemType);
        })->get();
    
        $computers = $items->where('item_type', 'computer');
        $diskDrives = $items->where('item_type', 'disk_drive');
        $processors = $items->where('item_type', 'processor');
        $vgaCards = $items->where('item_type', 'vga_card');
        $rams = $items->where('item_type', 'ram');
        $monitors = $items->where('item_type', 'monitor');
        $otherItems = $items->where('item_type', 'other_item');
    
        return view('inventories.index', compact('computers', 'diskDrives', 'processors', 'vgaCards', 'rams', 'monitors', 'otherItems'));
    }      

    public function create()
    {
        return view('inventories.create');
    }

    public function store(Request $request)
    {
        $itemType = $request->input('item_type');
    
        if ($itemType === 'computer') {
            $validated = $request->validate([
                'item_type' => 'required|string',
                'brand' => 'required|string',
                'model' => 'required|string',
                'stock' => 'required|integer|min:0',
            ]);

            $computer = new \App\Models\Item();
            $computer->item_type = $validated['item_type'];
            $computer->item_name = $validated['brand'];
            $computer->description = $validated['model'];
            $computer->stock = $validated['stock'];
            $computer->save();

            return redirect()->route('inventories.index', ['item_type' => $request->item_type])
            ->with('success', "<strong>Added Successfully.</strong><br><strong>$computer->item_name $computer->description</strong> added successfully.");

        } elseif ($itemType === 'disk_drive') {
            $validated = $request->validate([
                'item_type' => 'required|string',
                'type' => 'required|string|in:HDD,SSD,NVMe',
                'size' => 'required|integer|min:1',
                'brand' => 'required|string',
                'stock' => 'required|integer|min:0',
            ]);

            $diskDrive = new \App\Models\Item();
            $diskDrive->item_type = $validated['item_type'];
            $diskDrive->item_name = $validated['type'];
            $diskDrive->description = $validated['size'] . ' GB' . ' - ' . $validated['brand'];
            $diskDrive->stock = $validated['stock'];
            $diskDrive->save();

            return redirect()->route('inventories.index', ['item_type' => $request->item_type])
            ->with('success', "<strong>Added Successfully.</strong><br><strong>$diskDrive->item_name $diskDrive->description</strong> added successfully.");

        } elseif ($itemType === 'processor') {
            $validated = $request->validate([
                'item_type' => 'required|string',
                'type' => 'required|string',
                'model' => 'required|string',
                'frequency' => 'required|regex:/^\d+(\.\d+)?$/',
                'stock' => 'required|integer|min:0',
            ]);

            $processor = new \App\Models\Item();
            $processor->item_type = $validated['item_type'];
            $processor->item_name = $validated['type'];
            $processor->description = $validated['model'] . ' - ' . $validated['frequency'] . ' GHz';
            $processor->stock = $validated['stock'];
            $processor->save();

            return redirect()->route('inventories.index', ['item_type' => $request->item_type])
            ->with('success', "<strong>Added Successfully.</strong><br><strong>$processor->item_name $processor->description</strong> added successfully.");

        } elseif ($itemType === 'vga_card') {
            $validated = $request->validate([
                'item_type' => 'required|string',
                'brand' => 'required|string',
                'size' => 'required|integer|min:1',
                'stock' => 'required|integer|min:0',
            ]);

            $vgaCard = new \App\Models\Item();
            $vgaCard->item_type = $validated['item_type'];
            $vgaCard->item_name = $validated['brand'];
            $vgaCard->description = $validated['size'] . ' GB';
            $vgaCard->stock = $validated['stock'];
            $vgaCard->save();

            return redirect()->route('inventories.index', ['item_type' => $request->item_type])
            ->with('success', "<strong>Added Successfully.</strong><br><strong>$vgaCard->item_name $vgaCard->description</strong> added successfully.");

        } elseif ($itemType === 'ram') {
            $validated = $request->validate([
                'item_type' => 'required|string',
                'size' => 'required|integer|min:1',
                'type' => 'required|string',
                'stock' => 'required|integer|min:0',
            ]);

            $ram = new \App\Models\Item();
            $ram->item_type = $validated['item_type'];
            $ram->item_name = $validated['type'];
            $ram->description = $validated['size'] . ' GB';
            $ram->stock = $validated['stock'];
            $ram->save();

            return redirect()->route('inventories.index', ['item_type' => $request->item_type])
            ->with('success', "<strong>Added Successfully.</strong><br><strong>$ram->item_name $ram->description</strong> added successfully.");

        } elseif ($itemType === 'monitor') {
            $validated = $request->validate([
                'item_type' => 'required|string',
                'brand' => 'required|string',
                'resolution' => 'required|string',
                'inch' => 'required|regex:/^\d+(\.\d+)?$/',
                'stock' => 'required|integer|min:0',
            ]);
            
            $monitor = new \App\Models\Item();
            $monitor->item_type = $validated['item_type'];
            $monitor->item_name = $validated['brand'];
            $monitor->description = $validated['resolution'] . ' - ' . $validated['inch'] . ' inch';
            $monitor->stock = $validated['stock'];
            $monitor->save();

            return redirect()->route('inventories.index', ['item_type' => $request->item_type])
            ->with('success', "<strong>Added Successfully.</strong><br><strong>$monitor->item_name $monitor->description</strong> added successfully.");
            
        } else {
            $validated = $request->validate([
                'item_type' => 'required|string',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'stock' => 'required|integer|min:0',
            ]);
            
            $otherItem = new \App\Models\Item();
            $otherItem->item_type = $validated['item_type'];
            $otherItem->item_name = $validated['name'];
            $otherItem->description = $validated['description'];
            $otherItem->stock = $validated['stock'];
            $otherItem->save();

            return redirect()->route('inventories.index', ['item_type' => $request->item_type])
            ->with('success', "<strong>Added Successfully.</strong><br><strong>$otherItem->item_name $otherItem->description</strong> added successfully.");
        }
    }   

    public function update(Request $request, $id)
    {
        $itemType = $request->input('item_type');
    
        if ($itemType === 'other_item') {
            $otherItem = Item::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'stock' => 'sometimes|required|integer|min:0',
            ]);
            
            $otherItem->item_name = $validated['name'];
            $otherItem->description = $validated['description'] ?? '';
            $otherItem->stock = $validated['stock'];
            $otherItem->save();
            
            return redirect()->route('inventories.index', ['item_type' => $request->item_type])
            ->with('success', "<strong>Updated Successfully.</strong><br><strong>$otherItem->item_name $otherItem->description</strong> Updated successfully.");

        } elseif ($itemType === 'computer') {
            $computer = Item::findOrFail($id);

            $validated = $request->validate([
                'brand' => 'sometimes|required|string',
                'model' => 'sometimes|required|string',
                'stock' => 'sometimes|required|integer|min:0',
            ]);
            
            $computer->item_name = $validated['brand'];
            $computer->description = "{$validated['model']}";
            $computer->stock = $validated['stock'];
            $computer->save();
            
            return redirect()->route('inventories.index', ['item_type' => $request->item_type])
            ->with('success', "<strong>Updated Successfully.</strong><br><strong>$computer->item_name $computer->description</strong> Updated successfully.");
            
        } elseif ($itemType === 'disk_drive') {
            $diskDrive = Item::findOrFail($id);

            $validated = $request->validate([
                'type' => 'sometimes|required|string|in:HDD,SSD,NVMe',
                'size' => 'sometimes|required|integer|min:1',
                'brand' => 'sometimes|required|string',
                'stock' => 'sometimes|required|integer|min:0',
            ]);
            
            $diskDrive->item_name = $validated['type'];
            $diskDrive->description = "{$validated['size']} GB - {$validated['brand']}";
            $diskDrive->stock = $validated['stock'];
            $diskDrive->save();
            
            return redirect()->route('inventories.index', ['item_type' => $request->item_type])
            ->with('success', "<strong>Updated Successfully.</strong><br><strong>$diskDrive->item_name $diskDrive->description</strong> Updated successfully.");

        } elseif ($itemType === 'processor') {
            $processor = Item::findOrFail($id);

            $validated = $request->validate([
                'type' => 'sometimes|required|string',
                'model' => 'sometimes|required|string',
                'frequency' => 'sometimes|required|regex:/^\d+(\.\d+)?$/',
                'stock' => 'sometimes|required|integer|min:0',
            ]);
            
            $processor->item_name = $validated['type'];
            $processor->description = "{$validated['model']} - {$validated['frequency']} GHz";
            $processor->stock = $validated['stock'];
            $processor->save();
            
            return redirect()->route('inventories.index', ['item_type' => $request->item_type])
            ->with('success', "<strong>Updated Successfully.</strong><br><strong>$processor->item_name $processor->description</strong> Updated successfully.");

        } elseif ($itemType === 'vga_card') {
            $vgaCard = Item::findOrFail($id);

            $validated = $request->validate([
                'brand' => 'sometimes|required|string',
                'size' => 'sometimes|required|integer|min:1',
                'stock' => 'sometimes|required|integer|min:0',
            ]);
            
            $vgaCard->item_name = $validated['brand'];
            $vgaCard->description = "{$validated['size']} GB";
            $vgaCard->stock = $validated['stock'];
            $vgaCard->save();
            
            return redirect()->route('inventories.index', ['item_type' => $request->item_type])
            ->with('success', "<strong>Updated Successfully.</strong><br><strong>$vgaCard->item_name $vgaCard->description</strong> Updated successfully.");

        } elseif ($itemType === 'ram') {
            $ram = Item::findOrFail($id);

            $validated = $request->validate([
                'size' => 'sometimes|required|integer|min:1',
                'type' => 'sometimes|required|string',
                'stock' => 'sometimes|required|integer|min:0',
            ]);
            
            $ram->item_name = $validated['type'];
            $ram->description = "{$validated['size']} GB";
            $ram->stock = $validated['stock'];
            $ram->save();
            
            return redirect()->route('inventories.index', ['item_type' => $request->item_type])
            ->with('success', "<strong>Updated Successfully.</strong><br><strong>$ram->item_name $ram->description</strong> Updated successfully.");

        } elseif ($itemType === 'monitor') {
            $monitor = Item::findOrFail($id);

            $validated = $request->validate([
                'brand' => 'sometimes|required|string',
                'resolution' => 'sometimes|required|string',
                'inch' => 'sometimes|required|regex:/^\d+(\.\d+)?$/',
                'stock' => 'sometimes|required|integer|min:0',
            ]);
            
            $monitor->item_name = $validated['brand'];
            $monitor->description = "{$validated['resolution']} - {$validated['inch']} inch";
            $monitor->stock = $validated['stock'];
            $monitor->save();
            
            return redirect()->route('inventories.index', ['item_type' => $request->item_type])
                ->with('success', "<strong>Updated Successfully.</strong><br><strong>$monitor->item_name $monitor->description</strong> Updated successfully.");

        } else {
            abort(404);
        }
    }     

    public function destroy(Request $request, $id)
    {
        // Ambil type_data dari request
        $itemType = $request->input('type_data');
        
        // Tentukan tindakan berdasarkan type_data
        if ($itemType == 'other_item') {
            $otherItem = Item::find($id);
            if ($otherItem) {
                $otherItem->delete();
                return redirect()->route('inventories.index', ['item_type' => $itemType])->with('success', "<strong>Deleted Successfully.</strong><br><strong>$otherItem->item_name $otherItem->description</strong> deleted successfully.");
            }
        } elseif ($itemType == 'computer') {
            $computer = Item::find($id);
            if ($computer) {
                $computer->delete();
                return redirect()->route('inventories.index', ['item_type' => $itemType])->with('success', "<strong>Deleted Successfully.</strong><br><strong>$computer->item_name $computer->description</strong> deleted successfully.");
            }
        } elseif ($itemType == 'disk_drive') {
            $diskDrive = Item::find($id);
            if ($diskDrive) {
                $diskDrive->delete();
                return redirect()->route('inventories.index', ['item_type' => $itemType])->with('success', "<strong>Deleted Successfully.</strong><br><strong>$diskDrive->item_name $diskDrive->description</strong> deleted successfully.");
            }
        } elseif ($itemType == 'processor') {
            $processor = Item::find($id);
            if ($processor) {
                $processor->delete();
                return redirect()->route('inventories.index', ['item_type' => $itemType])->with('success', "<strong>Deleted Successfully.</strong><br><strong>$processor->item_name $processor->description</strong> deleted successfully.");
            }
        } elseif ($itemType == 'vga_card') {
            $vgaCard = Item::find($id);
            if ($vgaCard) {
                $vgaCard->delete();
                return redirect()->route('inventories.index', ['item_type' => $itemType])->with('success', "<strong>Deleted Successfully.</strong><br><strong>$vgaCard->item_name $vgaCard->description</strong> deleted successfully.");
            }
        } elseif ($itemType == 'ram') {
            $ram = Item::find($id);
            if ($ram) {
                $ram->delete();
                return redirect()->route('inventories.index', ['item_type' => $itemType])->with('success', "Deleted Successfully.<br><strong>$ram->item_name $ram->description</strong> deleted successfully.");
            }
        } elseif ($itemType == 'monitor') {
            $monitor = Item::find($id);
            if ($monitor) {
                $monitor->delete();
                return redirect()->route('inventories.index', ['item_type' => $itemType])->with('success', "Deleted Successfully.<br><strong>$monitor->item_name $monitor->description</strong> deleted successfully.");
            }
        }   
        
        // Jika tidak ditemukan, tampilkan 404
        abort(404);
    }
    
    public function downloadPDF(Request $request)
    {
        $itemType = $request->input('item_type');
        $items = null;
        $title = '';
    
        if ($itemType === 'all_items') {
            $items = [
                'Computers' => \App\Models\Item::where('item_type', 'computer')->get(),
                'Disk Drives' => \App\Models\Item::where('item_type', 'disk_drive')->get(),
                'Processors' => \App\Models\Item::where('item_type', 'processor')->get(),
                'VGA Cards' => \App\Models\Item::where('item_type', 'vga_card')->get(),
                'RAM' => \App\Models\Item::where('item_type', 'ram')->get(),
                'Monitor' => \App\Models\Item::where('item_type', 'monitor')->get(),
                'Other Items' => \App\Models\Item::where('item_type', 'other_item')->get()
            ];
            $title = 'All Inventory Items';
        } elseif (in_array($itemType, ['computer', 'disk_drive', 'processor', 'vga_card', 'ram', 'monitor', 'other_item'])) {
            $items = \App\Models\Item::where('item_type', $itemType)->get();
    
            $titles = [
                'computer' => 'Computer',
                'disk_drive' => 'Disk Drive',
                'processor' => 'Processor / CPU (Central Processing Unit)',
                'vga_card' => 'VGA (Video Graphics Adapter)',
                'ram' => 'RAM (Random Access Memory)',
                'monitor' => 'Monitor',
                'other_item' => 'Other Items'
            ];
    
            $title = $titles[$itemType];
        } else {
            return redirect()->back()->with('error', 'Invalid item type.');
        }
    
        $pdf = Pdf::loadView('pdf.inventory_report', compact('items', 'itemType', 'title'));
        return $pdf->download('INVENTORY_REPORT_' . strtoupper($itemType) . '.pdf');
    }
    
}
