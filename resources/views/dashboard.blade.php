@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <!-- Total Locations -->
        <div class="bg-white p-5 rounded-2xl shadow relative">
            <a href="{{ route('locations.index') }}" class="absolute top-3 right-3 text-gray-400 hover:text-blue-600">
                <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
            </a>
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                    <i data-lucide="map-pin" class="w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="text-gray-600 text-sm">Total Locations</h2>
                    <p class="text-2xl font-bold">{{ $totalLocations }}</p>
                </div>
            </div>
        </div>

        <!-- Total Computer Allocations -->
        <div class="bg-white p-5 rounded-2xl shadow relative">
            <a href="{{ route('allocates.index') }}" class="absolute top-3 right-3 text-gray-400 hover:text-green-600">
                <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
            </a>
            <div class="flex items-center gap-4">
                <div class="bg-green-100 text-green-600 p-3 rounded-full">
                    <i data-lucide="monitor" class="w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="text-gray-600 text-sm">Total Computer Allocations</h2>
                    <p class="text-2xl font-bold">{{ $totalAllocatedComputers }}</p>
                </div>
            </div>
        </div>

        <!-- Total Other Item Allocations -->
        <div class="bg-white p-5 rounded-2xl shadow relative">
            <a href="{{ route('allocates.index') }}" class="absolute top-3 right-3 text-gray-400 hover:text-yellow-600">
                <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
            </a>
            <div class="flex items-center gap-4">
                <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full">
                    <i data-lucide="box" class="w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="text-gray-600 text-sm">Total Other Item Allocations</h2>
                    <p class="text-2xl font-bold">{{ $totalAllocatedItems }}</p>
                </div>
            </div>
        </div>

        <!-- Total Transfers -->
        <div class="bg-white p-5 rounded-2xl shadow relative">
            <a href="{{ route('transfers.index') }}" class="absolute top-3 right-3 text-gray-400 hover:text-red-600">
                <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
            </a>
            <div class="flex items-center gap-4">
                <div class="bg-red-100 text-red-600 p-3 rounded-full">
                    <i data-lucide="repeat" class="w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="text-gray-600 text-sm">Total Transfers</h2>
                    <p class="text-2xl font-bold">{{ $totalTransferLogs }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="bg-white p-4 rounded-2xl shadow d-flex flex-column gap-3 h-100">
                <div class="d-flex align-items-center mb-2">
                    <i data-lucide="cpu" class="w-5 h-5 text-muted"></i>
                    <h4 class="mb-0 ml-3">Hardware Stock</h4>
                </div>

                @php
                    $hardwareStocks = [
                        ['title' => 'Monitor', 'percentage' => $monitorUsedPercentage, 'color' => 'info', 'url' => url('/inventories?item_type=monitor')],
                        ['title' => 'Computer', 'percentage' => $computerUsedPercentage, 'color' => 'info', 'url' => url('/inventories?item_type=computer')],
                        ['title' => 'RAM (Random Access Memory)', 'percentage' => $ramUsedPercentage, 'color' => 'info', 'url' => url('/inventories?item_type=ram')],
                        ['title' => 'Processor', 'percentage' => $processorUsedPercentage, 'color' => 'success', 'url' => url('/inventories?item_type=processor')],
                        ['title' => 'VGA (Video Graphics Adapter)', 'percentage' => $vgaUsedPercentage, 'color' => 'warning', 'url' => url('/inventories?item_type=vga')],
                        ['title' => 'Disk Drive', 'percentage' => $diskDriveUsedPercentage, 'color' => 'danger', 'url' => url('/inventories?item_type=disk_drive')],
                    ];
                @endphp

                @foreach ($hardwareStocks as $stock)
                    <div class="bg-light rounded-3 p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-semibold">{{ $stock['title'] }}</span>
                                <a href="{{ $stock['url'] }}" class="text-decoration-none text-muted" title="Lihat detail">
                                    <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                                </a>
                            </div>                    
                            <span class="small text-muted">{{ number_format($stock['percentage'], 2) }}% Used</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 14px;">
                            <div class="progress-bar bg-{{ $stock['color'] }}"
                                role="progressbar"
                                style="width: {{ $stock['percentage'] }}%;"
                                aria-valuenow="{{ number_format($stock['percentage'], 2) }}"
                                aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-lg-7">
            <div class="bg-white p-4 rounded-2xl shadow">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <i data-lucide="repeat" class="w-5 h-5" style="color: black;"></i>
                        <h4 class="mb-0 ml-3">Recent Transfer Activity</h4>
                    </div>
                    <a href="{{ $latestTransferLogs->isNotEmpty() ? route('transfers.index', ['date' => $latestTransferLogs->first()->created_at->format('Y-m-d')]) : '#' }}" class="text-primary d-flex align-items-center gap-1 text-decoration-none">
                        <i data-lucide="arrow-up-right-from-square" class="w-5 h-5" style="color: black;"></i>
                    </a>                                     
                </div>                              
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Name</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Transferred At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($latestTransferLogs as $transferLog)
                                <tr>
                                    <td class="fw-semibold">
                                        <a href="{{ route('transfers.index', ['date' => $transferLog->created_at->format('Y-m-d')]) }}" class="text-decoration-none">
                                            {{ $transferLog->item_name ?? 'N/A' }}
                                        </a>
                                    </td>                                    
                                    <td class="text-muted">{{ $transferLog->fromLocation->name ?? 'N/A' }}</td>
                                    <td class="text-muted">{{ $transferLog->toLocation->name ?? 'N/A' }}</td>
                                    <td class="text-muted">{{ $transferLog->created_at->format('d-m-Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No recent transfer data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
