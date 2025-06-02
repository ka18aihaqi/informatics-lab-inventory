@extends('layouts.app')

@section('content')
    <div class="content-header d-flex">
        {{-- Download PDF --}}
        <div class="dropdown col-4 d-flex justify-content-start">
            <button class="btn btn-sm btn-outline-download dropdown-toggle" type="button" id="downloadDropdown" data-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-file-download"></i> <span class="download-text">Download PDF</span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="downloadDropdown">
                <li><a class="dropdown-item" href="{{ route('inventories.download.pdf', ['item_type' => 'all_items']) }}">All Items</a></li>
                <div class="dropdown-divider"></div>
                <li><a class="dropdown-item" href="{{ route('inventories.download.pdf', ['item_type' => 'computer']) }}">Computer</a></li>
                <li><a class="dropdown-item" href="{{ route('inventories.download.pdf', ['item_type' => 'disk_drive']) }}">Disk Drives</a></li>
                <li><a class="dropdown-item" href="{{ route('inventories.download.pdf', ['item_type' => 'processor']) }}">Processors</a></li>
                <li><a class="dropdown-item" href="{{ route('inventories.download.pdf', ['item_type' => 'vga_card']) }}">VGA Cards</a></li>
                <li><a class="dropdown-item" href="{{ route('inventories.download.pdf', ['item_type' => 'ram']) }}">RAM</a></li>
                <li><a class="dropdown-item" href="{{ route('inventories.download.pdf', ['item_type' => 'monitor']) }}">Monitor</a></li>
                <li><a class="dropdown-item" href="{{ route('inventories.download.pdf', ['item_type' => 'other_item']) }}">Other Items</a></li>
            </ul>
        </div>
        
        {{-- Item Filter --}}
        <div class="col-4 d-flex justify-content-center">
            <form method="GET" action="{{ route('inventories.index') }}" class="d-flex align-items-center">
                <select name="item_type" id="item_type" class="form-control form-control-sm border-radius-md text-center" onchange="this.form.submit()">
                    <option value="" disabled {{ request('item_type') ? '' : 'selected' }}>Select Item Type</option>
                    <option value="all_items" {{ request('item_type') == 'all_items' ? 'selected' : '' }}>All Items</option>
                    <option value="computer" {{ request('item_type') == 'computer' ? 'selected' : '' }}>Computers</option>
                    <option value="disk_drive" {{ request('item_type') == 'disk_drive' ? 'selected' : '' }}>Disk Drives</option>
                    <option value="processor" {{ request('item_type') == 'processor' ? 'selected' : '' }}>Processors</option>
                    <option value="vga_card" {{ request('item_type') == 'vga_card' ? 'selected' : '' }}>VGA</option>
                    <option value="ram" {{ request('item_type') == 'ram' ? 'selected' : '' }}>RAM</option>
                    <option value="monitor" {{ request('item_type') == 'monitor' ? 'selected' : '' }}>Monitor</option>
                    <option value="other_item" {{ request('item_type') == 'other_item' ? 'selected' : '' }}>Other Items</option>
                </select>
            </form>
        </div>
        
        <div class="col-4 d-flex justify-content-end">
            {{-- Add Item --}}
            <button type="button" class="btn btn-sm btn-outline-add" data-toggle="modal" data-target="#Modal">
                <i class="fas fa-plus"></i> <span class="add-text ml-1">Add Items</span>
            </button>          
            <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">Add New Item</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form role="form" action="{{ route('inventories.store') }}" method="POST">
                            @csrf
                            <div class="modal-body text-uppercase">
                                {{-- Item Type --}}
                                <div class="row justify-content-center mb-4">
                                    <div class="col-md-6">
                                        <label class="font-weight-bold text-center d-block">Choose an item type to add</label>
                                        <select name="item_type" id="item_type" class="form-control" style="text-align: center;" required>
                                            <option value="" disabled selected>Select Item Type</option>
                                            <option value="computer">Computer Brand</option>
                                            <option value="disk_drive">Disk Drive</option>
                                            <option value="processor">Processor</option>
                                            <option value="vga_card">VGA</option>
                                            <option value="ram">RAM</option>
                                            <option value="monitor">Monitor</option>
                                            <option value="other_item">Other Items</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- Computer --}}
                                <div class="card shadow p-3 mb-4" id="computer_form" style="display: none;">
                                    <div class="row">                              
                                        <!-- Brand -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="brand">Brand<span class="text-danger">*</span></label>
                                                <input type="text" name="brand" id="brand" class="form-control" placeholder="Enter brand" required>
                                            </div>
                                        </div>

                                        <!-- Model -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="model">Model<span class="text-danger">*</span></label>
                                                <input type="text" name="model" id="model" class="form-control" placeholder="Enter model" required>
                                            </div>
                                        </div>
                                
                                        <!-- Stok -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="stock">Stock<span class="text-danger">*</span></label>
                                                <input type="number" name="stock" id="stock" class="form-control" placeholder="Enter stock quantity" min="0" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                                {{-- Disk Drive --}}
                                <div class="card shadow p-3 mb-4" id="disk_drive_form" style="display: none;">
                                    <div class="row">
                                        <!-- Tipe Disk Drive -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="type">Type <span class="text-danger">*</span></label>
                                                <select name="type" id="type" class="form-control" required>
                                                    <option value="" disabled selected style="text-align: center;">Select Type</option>
                                                    <option value="HDD">HDD</option>
                                                    <option value="SSD">SSD</option>
                                                    <option value="NVMe">NVMe</option>
                                                    <!-- Tambahkan sesuai kebutuhan -->
                                                </select>
                                            </div>
                                        </div>
                                
                                        <!-- Ukuran -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="size">Size <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" name="size" id="size" class="form-control" placeholder="Enter size" min="0" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">GB</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                    
                                
                                        <!-- Brand -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="brand">Brand <span class="text-danger">*</span></label>
                                                <input type="text" name="brand" id="brand" class="form-control" placeholder="Enter brand name" required>
                                            </div>
                                        </div>
                                
                                        <!-- Stok -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="stock">Stock <span class="text-danger">*</span></label>
                                                <input type="number" name="stock" id="stock" class="form-control" placeholder="Enter stock quantity" min="0" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                                {{-- Processor --}}
                                <div class="card shadow p-3 mb-4" id="processor_form" style="display: none;">
                                    <div class="row">
                                        <!-- Tipe Processor -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="type">Type <span class="text-danger">*</span></label>
                                                <select name="type" id="type" class="form-control" required>
                                                    <option value="" disabled selected style="text-align: center;">Select Type</option>
                                                    <option value="Intel">Intel</option>
                                                    <option value="AMD">AMD</option>
                                                    <option value="Apple">Apple (M Series)</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                
                                        <!-- Model Processor -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="model">Model <span class="text-danger">*</span></label>
                                                <input type="text" name="model" id="model" class="form-control" placeholder="e.g. Core i5-12400F, Ryzen 5 5600" required>
                                            </div>
                                        </div>
                                
                                        <!-- Frequency -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="frequency">Frequency <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" name="frequency" id="frequency" class="form-control" placeholder="e.g. 3.60" min="0" max="10" step="0.01" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">GHz</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                    
                                
                                        <!-- Stock -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="stock">Stock <span class="text-danger">*</span></label>
                                                <input type="number" name="stock" id="stock" class="form-control" placeholder="Enter stock quantity" min="0" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- VGA --}}
                                <div class="card shadow p-3 mb-4" id="vga_card_form" style="display: none;">
                                    <div class="row">
                                        <!-- Brand -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="brand">Brand <span class="text-danger">*</span></label>
                                                <input type="text" name="brand" id="brand" class="form-control" placeholder="e.g. NVIDIA, AMD, ASUS" required>
                                            </div>
                                        </div>
                                
                                        <!-- Size -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="size">Size <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" name="size" id="size" class="form-control" placeholder="Enter size" min="0" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">GB</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                
                                        <!-- Stock -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="stock">Stock <span class="text-danger">*</span></label>
                                                <input type="number" name="stock" id="stock" class="form-control" placeholder="Enter stock quantity" min="0" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- RAM --}}
                                <div class="card shadow p-3 mb-4" id="ram_form" style="display: none;">
                                    <div class="row">
                                        <!-- Size -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="size">Size <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" name="size" id="size" class="form-control" placeholder="Enter size" min="0" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">GB</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                
                                        <!-- Type -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="type">Type <span class="text-danger">*</span></label>
                                                <select name="type" id="type" class="form-control" required>
                                                    <option value="" disabled selected style="text-align: center;">Select RAM Type</option>
                                                    <option value="DDR3">DDR3</option>
                                                    <option value="DDR4">DDR4</option>
                                                    <option value="DDR5">DDR5</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                
                                        <!-- Stock -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="stock">Stock <span class="text-danger">*</span></label>
                                                <input type="number" name="stock" id="stock" class="form-control" placeholder="Enter stock quantity" min="0" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                                {{-- Monitor --}}
                                <div class="card shadow p-3 mb-4" id="monitor_form" style="display: none;">
                                    <div class="row">
                                        <!-- Brand -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="brand">Brand<span class="text-danger">*</span></label>
                                                <input type="text" name="brand" id="brand" class="form-control" placeholder="e.g. HP V194, Dell E2016H" required>
                                            </div>
                                        </div>
                                        <!-- Resolution -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="resolution">Resolution<span class="text-danger">*</span></label>
                                                <input type="text" name="resolution" id="resolution" class="form-control" placeholder="e.g. 1366x768, 1920x1080" required>
                                            </div>
                                        </div>
                                
                                        <!-- Inch -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="inch">Inch<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" name="inch" id="inch" class="form-control" placeholder="e.g. 18.5" min="0" step="0.01" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">GHz</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                
                                        <!-- Stock -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="stock">Stock <span class="text-danger">*</span></label>
                                                <input type="number" name="stock" id="stock" class="form-control" placeholder="Enter stock quantity" min="0" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                                {{-- Other Item --}}
                                <div class="card shadow p-3 mb-4" id="other_item_form" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" id="name" class="form-control" placeholder="Type item name" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="stock">Stock <span class="text-danger">*</span></label>
                                                <input type="number" name="stock" id="stock" class="form-control" placeholder="Enter stock quantity" min="0" required>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description" class="text-left d-block">Description</label>
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
        @if(!request('item_type'))
            <p class="text-center pt-3 pb-3">Please select an item type to view data.</p>
        @endif

        @if(request('item_type') == 'computer' || request('item_type') == 'all_items')
            @include('inventories.components.computers')
        @endif

        @if(request('item_type') == 'disk_drive' || request('item_type') == 'all_items')
            @include('inventories.components.disk_drives')
        @endif
        
        @if(request('item_type') == 'processor' || request('item_type') == 'all_items')
            @include('inventories.components.processors')
        @endif
        
        @if(request('item_type') == 'vga_card' || request('item_type') == 'all_items')
            @include('inventories.components.vga_cards')
        @endif
        
        @if(request('item_type') == 'ram' || request('item_type') == 'all_items')
            @include('inventories.components.rams')
        @endif

        @if(request('item_type') == 'monitor' || request('item_type') == 'all_items')
            @include('inventories.components.monitors')
        @endif
        
        @if(request('item_type') == 'other_item' || request('item_type') == 'all_items')
            @include('inventories.components.other_items')
        @endif
    </div>   

    @include('layouts.error_modal')
    @include('layouts.success_modal')
@endsection


