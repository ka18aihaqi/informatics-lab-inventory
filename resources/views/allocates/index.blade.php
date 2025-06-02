@extends('layouts.app')

@section('content')
    <div class="content-header d-flex">
        {{-- Download PDF by Location --}}
        <div class="dropdown col-4 d-flex justify-content-start">
            <button class="btn btn-sm btn-outline-download dropdown-toggle" type="button" id="downloadDropdown" data-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-file-download"></i> <span class="download-text">Download PDF</span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="downloadDropdown">
                @foreach($locations as $location)
                    <li>
                        <a class="dropdown-item" href="{{ route('allocates.download.pdf', ['location' => $location->id]) }}">
                            {{ $location->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>        
        
        {{-- Location Filter --}}
        <div class="col-4 d-flex justify-content-center">
            <form method="GET" action="{{ route('allocates.index') }}" class="d-flex align-items-center">
                <select name="location" id="location" class="form-control form-control-sm border-radius-md text-center" onchange="this.form.submit()">
                    <option value="" disabled {{ request('location') ? '' : 'selected' }}>Select Location</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        
        {{-- Add Item --}}
        <div class="col-4 d-flex justify-content-end">
            <button type="button" class="btn btn-sm btn-outline-add" data-toggle="modal" data-target="#Modal">
                <i class="fas fa-plus"></i> <span class="add-text ml-1">Allocate Items</span>
            </button>   
            <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">Allocate New Items</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="allocateForm" role="form" action="{{ route('allocates.store') }}" method="POST">
                            @csrf
                            <div class="modal-body text-uppercase">
                                <!-- Item Type -->
                                <div class="row justify-content-center mb-4">
                                    <div class="col-md-12">
                                        <label class="font-weight-bold text-center d-block">Choose an item type to allocate</label>
                                        <div class="d-flex justify-content-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="item_type" id="computer" value="computer">
                                                <label class="form-check-label" for="computer">Allocate Computer</label>
                                            </div>
                                            <div class="form-check pl-5">
                                                <input class="form-check-input" type="radio" name="item_type" id="other_item" value="other_item">
                                                <label class="form-check-label" for="other_item">Allocate Other Item</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Allocated Computer --}}
                                <div class="computerSection card shadow p-3 mb-4" hidden>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Location</label>
                                                <select name="location_id" id="location_id" class="form-control" data-required="true">
                                                    <option value="" class="text-center" disabled {{ old('location_id') == '' ? 'selected' : '' }}>Select Location</option>
                                                    @foreach ($locations as $location)
                                                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                                            {{ $location->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="desk_number">No. Desk</label>
                                                <select name="desk_number" id="desk_number" class="form-control" data-required="true">
                                                    <option value="" class="text-center" disabled selected>Select Desk Number</option>
                                                    @for ($i = 1; $i <= 50; $i++)
                                                        <option value="{{ $i }}" {{ old('desk_number') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>                                                                             
                                    </div>
                                </div>
                                <div class="computerSection card shadow p-3 mb-4" hidden>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Computer</label>
                                                <select name="computer_id" id="computer_id" class="form-control">
                                                    <option value="" class="text-center" disabled selected>Select Computer</option>
                                                    @foreach ($computers as $computer)
                                                        <option value="{{ $computer->id }}">
                                                            {{ $computer->item_name }} {{ $computer->description }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Processor</label>
                                                <select name="processor_id" id="processor_id" class="form-control">
                                                    <option value="" class="text-center" disabled selected>Select Processor</option>
                                                    @foreach ($processors as $processor)
                                                        <option value="{{ $processor->id }}">
                                                            {{ $processor->item_name }} {{ $processor->description }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Disk Drive</label>
                                                <select name="disk_drive_1_id" id="disk_drive_1_id" class="form-control">
                                                    <option value="" class="text-center" disabled selected>Select Primary Disk</option>
                                                    @foreach ($diskDrives as $diskDrive)
                                                        <option value="{{ $diskDrive->id }}">
                                                            {{ $diskDrive->item_name }} {{ $diskDrive->description }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold"></label>
                                                <select name="disk_drive_2_id" id="disk_drive_2_id" class="form-control">
                                                    <option value="" class="text-center" disabled selected>Select Secondary Disk</option>
                                                    @foreach ($diskDrives as $diskDrive)
                                                        <option value="{{ $diskDrive->id }}">
                                                            {{ $diskDrive->item_name }} {{ $diskDrive->description }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">VGA Card</label>
                                                <select name="vga_card_id" id="vga_card_id" class="form-control">
                                                    <option value="" class="text-center" disabled selected>Select VGA</option>
                                                    @foreach ($vgaCards as $vgaCard)
                                                        <option value="{{ $vgaCard->id }}">
                                                            {{ $vgaCard->item_name }} {{ $vgaCard->description }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">RAM</label>
                                                <select name="ram_id" id="ram_id" class="form-control">
                                                    <option value="" class="text-center" disabled selected>Select RAM</option>
                                                    @foreach ($rams as $ram)
                                                        <option value="{{ $ram->id }}">
                                                            {{ $ram->item_name }} {{ $ram->description }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Monitor</label>
                                                <select name="monitor_id" id="monitor_id" class="form-control">
                                                    <option value="" class="text-center" disabled selected>Select Monitor</option>
                                                    @foreach ($monitors as $monitor)
                                                        <option value="{{ $monitor->id }}">
                                                            {{ $monitor->item_name }} {{ $monitor->description }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>   
                                <div class="computerSection card shadow p-3 mb-4" hidden>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="year_approx">Year (Approx)</label>
                                                <select name="year_approx" id="year_approx" class="form-control">
                                                    <option value="" class="text-center" selected disabled>Select year</option>
                                                    @for ($year = 2000; $year <= date('Y'); $year++)
                                                        <option value="{{ $year }}">{{ $year }}</option>
                                                    @endfor
                                                </select>
                                            </div> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="ups_status">UPS Status</label>
                                                <select name="ups_status" id="ups_status" class="form-control">
                                                    <option value="" selected>-</option>
                                                    <option value="Active">Aktif</option>
                                                    <option value="Inactive">Tidak Aktif</option>
                                                </select>                                             
                                            </div>
                                        </div>
                                    </div>
                                </div>           
                                
                                {{-- Allocated Item --}} 
                                <div class="itemSection card shadow p-3 mb-4" hidden>
                                    <div class="row">  
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Location</label>
                                                <select name="location_id" id="location_id" class="form-control" data-required="true">
                                                    <option value="" class="text-center" disabled selected>Select Location</option>
                                                    @foreach ($locations as $location)
                                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <div class="itemSection card shadow p-3 mb-4" hidden>
                                    <div class="row">  
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Item</label>
                                                <select name="other_item_id" id="other_item_id" class="form-control" data-required="true">
                                                    <option value="" class="text-center" disabled selected>Select Item</option>
                                                    @foreach ($otherItems as $otherItem)
                                                        <option value="{{ $otherItem->id }}">
                                                            {{ in_array($otherItem->item_type, ['computer', 'disk_drive', 'processor', 'vga_card', 'ram', 'monitor']) 
                                                                ? $otherItem->item_name . ' ' . $otherItem->description 
                                                                : $otherItem->item_name }} 
                                                            (Stok: {{ $otherItem->stock }})
                                                        </option>                                                    
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="stock">Stock</label>
                                                <input type="number" name="stock" id="stock" class="form-control" placeholder="Enter stock items" data-required="true">
                                                <span class="text-danger" id="error-stock">{{ $errors->first('stock') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea name="description" id="description" class="form-control" rows="3" placeholder="Type item description"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>                     
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-outline-success">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        @if(request('location'))
            {{-- Allocated Computer --}}
            @include('allocates.components.allocated_computers')


            {{-- Allocated Other Item --}}
            @include('allocates.components.allocated_items')

        @else
            <p class="text-center pt-3 pb-3">Please select a location to view data.</p>
        @endif
    </div>
    
    @include('layouts.error_modal')
    @include('layouts.success_modal')
@endsection


