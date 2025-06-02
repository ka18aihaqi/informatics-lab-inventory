<?php

namespace App\Http\Controllers;

use App\Models\Ram;
use App\Models\Item;
use App\Models\VgaCard;
use App\Models\Location;
use App\Models\DiskDrive;
use App\Models\OtherItem;
use App\Models\Processor;
use App\Models\TransferLog;
use Illuminate\Http\Request;
use App\Models\AllocatedItem;
use App\Models\AllocatedComputer;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Umum
        $totalLocations = Location::count();
        $totalAllocatedComputers = AllocatedComputer::count();
        $totalAllocatedItems = AllocatedItem::count();
        $totalTransferLogs = TransferLog::count();

        // Menghitung stok hardware
        $totalRamStock = Item::where('item_type', 'ram')->sum('stock');  // Total stok RAM
        $usedRamStock = AllocatedComputer::sum('ram_id');  // Stok RAM yang digunakan
        $ramUsedPercentage = ($totalRamStock > 0) ? ($usedRamStock / $totalRamStock) * 100 : 0;

        $totalMonitorStock = Item::where('item_type', 'monitor')->sum('stock');  // Total stok Processor
        $usedMonitorStock = AllocatedComputer::sum('monitor_id');  // Stok Processor yang digunakan
        $monitorUsedPercentage = ($totalMonitorStock > 0) ? ($usedMonitorStock / $totalMonitorStock) * 100 : 0;

        $totalComputerStock = Item::where('item_type', 'computer')->sum('stock');  // Total stok Processor
        $usedComputerStock = AllocatedComputer::sum('computer_id');  // Stok Processor yang digunakan
        $computerUsedPercentage = ($totalComputerStock > 0) ? ($usedComputerStock / $totalComputerStock) * 100 : 0;

        $totalProcessorStock = Item::where('item_type', 'processor')->sum('stock');  // Total stok Processor
        $usedProcessorStock = AllocatedComputer::sum('processor_id');  // Stok Processor yang digunakan
        $processorUsedPercentage = ($totalProcessorStock > 0) ? ($usedProcessorStock / $totalProcessorStock) * 100 : 0;

        $totalVgaStock = Item::where('item_type', 'vga_card')->sum('stock');  // Total stok VGA
        $usedVgaStock = AllocatedComputer::sum('vga_card_id');  // Stok VGA yang digunakan
        $vgaUsedPercentage = ($totalVgaStock > 0) ? ($usedVgaStock / $totalVgaStock) * 100 : 0;

        $totalDiskDriveStock = Item::where('item_type', 'disk_drive')->sum('stock');  // Total stok DiskDrive
        $usedDiskDriveStock = AllocatedComputer::sum('disk_drive_1_id') + AllocatedComputer::sum('disk_drive_2_id');  // Stok DiskDrive yang digunakan
        $diskDriveUsedPercentage = ($totalDiskDriveStock > 0) ? ($usedDiskDriveStock / $totalDiskDriveStock) * 100 : 0;

        $totalOtherItemStock = Item::where('item_type', 'other_item')->sum('stock');  // Total stok OtherItem
        $usedOtherItemStock = AllocatedItem::sum('stock');  // Stok OtherItem yang digunakan
        $otherItemUsedPercentage = ($totalOtherItemStock > 0) ? ($usedOtherItemStock / $totalOtherItemStock) * 100 : 0;

        // Menarik 5 data TransferLog terbaru
        $latestTransferLogs = TransferLog::with(['fromLocation', 'toLocation'])  // Asumsi relasi sudah ada
        ->latest()  // Mengurutkan berdasarkan tanggal transfer terbaru
        ->take(5)   // Ambil 5 data terbaru
        ->get();

        // Mengembalikan data untuk ditampilkan di dashboard
        return view('dashboard', compact(
            'totalLocations',
            'totalAllocatedComputers',
            'totalAllocatedItems',
            'totalTransferLogs',
            'computerUsedPercentage',
            'monitorUsedPercentage',
            'ramUsedPercentage', 
            'processorUsedPercentage', 
            'vgaUsedPercentage', 
            'diskDriveUsedPercentage', 
            'otherItemUsedPercentage',
            'latestTransferLogs'
        ));
    }
}
