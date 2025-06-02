@extends('layouts.app')

@section('content')
    <div class="content-header d-flex">
        
        <div class="col-4 d-flex justify-content-start">
            @if(request('date'))
                <a href="{{ route('transfers.download.pdf', ['transferred_at' => request('date')]) }}" class="btn btn-sm btn-outline-download" role="button">
                    <i class="fas fa-file-download"></i> <span class="download-text">Download PDF</span>
                </a>            
            @endif 
        </div>

        <div class="col-4 d-flex justify-content-center">
            <form method="GET" action="{{ route('transfers.index') }}" class="d-flex align-items-center">
                <input type="date" name="date" id="dateFilter" class="form-control form-control-sm border-radius-md text-center" value="{{ request('date') }}">
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            </form>
        </div>        

        <div class="col-4 d-flex justify-content-end">
            <button type="button" class="btn btn-sm btn-outline-add" data-toggle="modal" data-target="#Modal">
                <i class="fas fa-plus"></i> <span class="add-text ml-1">Transfer Items</span>
            </button>  
            <!-- Modal -->
            <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">Add New Transfer</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        
                        <div class="modal-body text-center text-uppercase">
                            <div class="row justify-content-center">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold">Item Type</label>
                                    <select name="item_type" id="item_type" class="form-control">
                                        <option value="" class="text-center" disabled selected>Select Item Type</option>
                                        <option value="computer_device">Computer Device</option>
                                        <option value="other_items">Other Items</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Computer Device --}}
                        <div id="form-computer-device" style="display: none;">
                            <form role="form" action="{{ route('allocated-computers.transfer') }}" method="POST">
                                @csrf
                                <div class="modal-body text-uppercase">
                                    {{-- From --}}
                                    <div class="card shadow p-3 mb-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">From Location</label>
                                                    <select name="location_id" id="from-location-select" class="form-control" required>
                                                        <option value="" class="text-center" disabled selected>Select Origin Location</option>
                                                        @foreach($locations as $location)
                                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>                                            
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="desk_number">From No. Desk</label>   
                                                    <select name="desk_number" id="from-desk-select" class="form-control" required>
                                                        <option value="" class="text-center" disabled selected>Select Desk Number</option>
                                                    </select>                                                                                       
                                                </div>                                            
                                            </div>
                                        </div>
                                    </div>  
                                    {{-- To --}}
                                    <div class="card shadow p-3 mb-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">To Location</label>
                                                    <select name="to_location_id" id="to-location-select" class="form-control" required>
                                                        <option value="" class="text-center" disabled selected>Select Destination Location</option>
                                                        @foreach($locations as $location)
                                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>                                                                                       
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="to_desk_number">To No. Desk</label>   
                                                    <select name="to_desk_number" id="to-desk-select" class="form-control">
                                                        <option value="" class="text-center" disabled selected>Select Desk Number</option>
                                                    </select>   
                                                </div>                                                                                                                          
                                            </div>
                                        </div>
                                    </div>   
                                    
                                    <div class="card shadow p-3 mb-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <h3>Original Components</h3>
                                                </div>                                                                                       
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <h3>Destination Components</h3>
                                                </div>                                                                                                                          
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group ml-3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" id="checkAllComponents">
                                                        <label class="form-check-label" for="checkAllComponents">Check All Components</label>
                                                    </div>
                                                </div>
                                            </div>                                            

                                            <div class="col-md-6">
                                                <div class="form-group ml-3">
                                                    <label for="computerFromCheck">Computer Brand</label><br>  
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="components[]" value="computer_id" id="computerFromCheck">
                                                        <span id="computerFrom" class="ms-2 text-muted"></span>
                                                    </div> 
                                                </div>                                                                                       
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group ml-3">
                                                    <label for="computerToCheck">Computer Brand</label><br>  
                                                    <div class="form-check form-check-inline">
                                                        <span id="computerTo" class="ms-2 text-muted"></span>
                                                    </div> 
                                                </div>                                                                                                                          
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group ml-3">
                                                    <label for="diskDrive1FromCheck">Disk Drive 1</label><br>  
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="components[]" value="disk_drive_1_id" id="diskDrive1FromCheck">
                                                        <span id="diskDrive1From" class="ms-2 text-muted"></span>
                                                    </div> 
                                                </div>                                                                                       
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group ml-3">
                                                    <label for="diskDrive1ToCheck">Disk Drive 1</label><br>  
                                                    <div class="form-check form-check-inline">
                                                        <span id="diskDrive1To" class="ms-2 text-muted"></span>
                                                    </div> 
                                                </div>                                                                                                                          
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group ml-3">
                                                    <label for="diskDrive2FromCheck">Disk Drive 2</label><br>   
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="components[]" value="disk_drive_2_id" id="diskDrive2FromCheck">
                                                        <span id="diskDrive2From" class="ms-2 text-muted"></span>
                                                    </div> 
                                                </div>                                                                                       
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group ml-3">
                                                    <label for="diskDrive2ToCheck">Disk Drive 2</label><br>   
                                                    <div class="form-check form-check-inline">
                                                        <span id="diskDrive2To" class="ms-2 text-muted"></span>
                                                    </div>   
                                                </div>                                                                                                                          
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group ml-3">
                                                    <label for="processorFromCheck">Processor</label><br>   
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="components[]" value="processor_id" id="processorFromCheck">
                                                        <span id="processorFrom" class="ms-2 text-muted"></span>
                                                    </div> 
                                                </div>                                                                                       
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group ml-3">
                                                    <label for="processorToCheck">Processor</label><br>   
                                                    <div class="form-check form-check-inline">
                                                        <span id="processorTo" class="ms-2 text-muted"></span>
                                                    </div>  
                                                </div>                                                                                                                          
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group ml-3">
                                                    <label for="vgaCardFromCheck">VGA Card</label><br>   
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="components[]" value="vga_card_id" id="vgaCardFromCheck">
                                                        <span id="vgaCardFrom" class="ms-2 text-muted"></span>
                                                    </div> 
                                                </div>                                                                                       
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group ml-3">
                                                    <label for="vgaCardToCheck">VGA Card</label><br>   
                                                    <div class="form-check form-check-inline">
                                                        <span id="vgaCardTo" class="ms-2 text-muted"></span>
                                                    </div>   
                                                </div>                                                                                                                          
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group ml-3">
                                                    <label for="ramFromCheck">RAM</label><br>   
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="components[]" value="ram_id" id="ramFromCheck">
                                                        <span id="ramFrom" class="ms-2 text-muted"></span>
                                                    </div>  
                                                </div>                                                                                       
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group ml-3">
                                                    <label for="ramToCheck">RAM</label><br>   
                                                    <div class="form-check form-check-inline">
                                                        <span id="ramTo" class="ms-2 text-muted"></span>
                                                    </div>  
                                                </div>                                                                                                                          
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group ml-3">
                                                    <label for="monitorFromCheck">Monitor</label><br>   
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="components[]" value="monitor_id" id="monitorFromCheck">
                                                        <span id="monitorFrom" class="ms-2 text-muted"></span>
                                                    </div>  
                                                </div>                                                                                       
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group ml-3">
                                                    <label for="monitorToCheck">Monitor</label><br>   
                                                    <div class="form-check form-check-inline">
                                                        <span id="monitorTo" class="ms-2 text-muted"></span>
                                                    </div>  
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

                        {{-- Other Items --}}
                        <div id="form-other-items" style="display: none;">
                            <form role="form" action="{{ route('allocated-items.transfer') }}" method="POST">
                                @csrf
                                <div class="modal-body text-uppercase">
                                    <div class="card shadow p-3 mb-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">From Location</label>
                                                    <select name="from_location_id" id="from-select" class="form-control" required>
                                                        <option value="" class="text-center" disabled selected>Select Location</option>
                                                        @foreach($locations as $location)
                                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>                                            
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">To Location</label>
                                                    <select name="to_location_id" id="to-select" class="form-control" required>
                                                        <option value="" class="text-center" disabled selected>Select Location</option>
                                                        @foreach($locations as $location)
                                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                        @endforeach
                                                    </select>                                                                                     
                                                </div>                                            
                                            </div>
                                        </div>
                                    </div>  

                                    <div class="card shadow p-3 mb-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="item_id">Name</label>   
                                                    <select name="item_id" id="from-item-select" class="form-control">
                                                        <option value="" class="text-center" disabled selected>Select Item</option>
                                                    </select>  
                                                </div>                                                                                       
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="quantity">Quantity</label>
                                                    <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Enter quantity items" data-required="true">
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
    </div>
    <div class="content-body">
        @if(request('date'))
            <div class="table-responsive">
                <table class="table table-hover align-middle custom-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;">No.</th>
                            <th style="width: 10%;">Name</th>
                            <th style="width: 10%;">From Location</th>
                            <th style="width: 10%;">To Location</th>
                            <th style="width: 10%;">Quantity</th>
                            <th style="width: 10%;">Note</th>
                            <th style="width: 10%;">Transfered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transferLogs as $index => $transferLog)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $transferLog->item_name ?? '-' }}</td>
                                <td>{{ $transferLog->fromLocation->name }}</td>
                                <td>{{ $transferLog->toLocation->name }}</td>
                                <td>{{ $transferLog->quantity }}</td>
                                <td>{{ $transferLog->note }}</td>
                                <td>{{ $transferLog->transferred_at }}</td>
                            </tr>
                        @empty
                            <tr class="text-center">
                                <td colspan="12" class="text-muted">No Transfer logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center pt-3 pb-3">Please select a transfer date to view data.</p>
        @endif 
    </div>    

    @include('layouts.success_modal')
    @include('layouts.error_modal')

@endsection
