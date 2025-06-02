<div class="table-responsive mt-3">
    <table class="table table-hover align-middle custom-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 10%;">No.</th>
                <th style="width: 20%;">Location</th>
                <th style="width: 20%;">Name</th>
                <th style="width: 20%;">Description</th>
                <th style="width: 10%;">Stock</th>
                <th class="text-center" style="width: 10%;">Action</th>                        
            </tr>
        </thead>
        <tbody>
            @forelse($allocatedItems as $index => $allocatedItem)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $allocatedItem->location->name }}</td>                                                           
                    <td>
                        @if (in_array($allocatedItem->otherItem->item_type, ['disk_drive', 'processor', 'vga_card', 'ram', 'monitor']))
                            {{ $allocatedItem->otherItem->item_name }} {{ $allocatedItem->otherItem->description }}
                        @else
                            {{ $allocatedItem->otherItem->item_name }}
                        @endif
                    </td>                    
                    <td>{{ $allocatedItem->description }}</td>
                    <td>{{ $allocatedItem->stock }}</td>
                    <td class="text-center">

                        <!-- Edit Item -->
                        <a href="javascript:;" class="btn btn-sm btn-outline-warning"  data-toggle="modal"  data-target="#editOtherItemModal-{{ $allocatedItem->id }}"> 
                            <i class="fas fa-edit"></i>
                        </a>
                        <div class="modal fade" id="editOtherItemModal-{{ $allocatedItem->id }}" tabindex="-1" role="dialog" aria-labelledby="editOtherItemModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header position-relative">
                                        <h5 class="modal-title w-100 text-center">Edit Item</h5>
                                        <button type="button" class="close position-absolute" style="right: 1rem; top: 0.75rem;" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form role="form" action="{{ route('allocates.update', $allocatedItem->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Hidden item_type field -->
                                        <input type="hidden" name="item_type" value="other_item">
                                        <div class="modal-body text-uppercase">
                                            <div class="card shadow p-3 mb-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="location_id-{{ $allocatedItem->id }}" class="text-left d-block">Item</label>
                                                            <input type="text" class="form-control" 
                                                                value="{{ 
                                                                    in_array($allocatedItem->otherItem->item_type, ['disk_drive', 'processor', 'vga_card', 'ram', 'monitor']) 
                                                                        ? $allocatedItem->otherItem->item_name . ' ' . $allocatedItem->otherItem->description 
                                                                        : $allocatedItem->otherItem->item_name 
                                                                }} (Stok: {{ $allocatedItem->otherItem->stock }})" 
                                                                readonly>
                                                            <input type="hidden" name="other_item_id" value="{{ $allocatedItem->other_item_id }}">
                                                        </div>                                                        
                                                    </div>                                                                
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="stock-{{ $allocatedItem->id }}" class="text-left d-block">Stock <span class="text-danger">*</span></label>
                                                            <input type="number" name="stock" id="stock-{{ $allocatedItem->id }}" class="form-control" value="{{ $allocatedItem->stock }}" min="0" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="description-{{ $allocatedItem->id }}" class="text-left d-block">Description</label>
                                                            <textarea name="description" id="description-{{ $allocatedItem->id }}" class="form-control" rows="3">{{ $allocatedItem->description }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>                                                    
                                            </div>                                                    
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline-success">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Delete Item --}}
                        <form action="{{ route('allocates.destroy', $allocatedItem->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="item_type" value="other_item">
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="text-center">
                    <td colspan="12" class="text-muted">There are no items in this location.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>