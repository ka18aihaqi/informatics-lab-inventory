<div class="table-responsive">
    <h3 class="table-title">Other Items</h3>
    <table class="table table-hover align-middle custom-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 10%;">No.</th>
                <th style="width: 20%;">Name</th>
                <th style="width: 20%;">Description</th>
                <th style="width: 20%;">Stock</th>
                <th class="text-center" style="width: 10%;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($otherItems as $index => $otherItem)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $otherItem->item_name }}</td>
                    <td>{{ $otherItem->description }}</td>
                    <td>{{ $otherItem->stock }}</td>
                    <td class="text-center">
                        <!-- Edit Button -->
                        <a href="javascript:;" class="btn btn-sm btn-outline-warning"  data-toggle="modal"  data-target="#editOtherItemModal-{{ $otherItem->id }}"> 
                            <i class="fas fa-edit"></i>
                        </a>
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editOtherItemModal-{{ $otherItem->id }}" tabindex="-1" role="dialog" aria-labelledby="editOtherItemModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header position-relative">
                                        <h5 class="modal-title w-100 text-center">Edit Items</h5>
                                        <button type="button" class="close position-absolute" style="right: 1rem; top: 0.75rem;" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>                                            
                                    <form role="form" action="{{ route('inventories.update', $otherItem->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Hidden type_data field -->
                                        <input type="hidden" name="item_type" value="other_item">
                                        <div class="modal-body text-uppercase">
                                            <div class="card shadow p-3 mb-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="name-{{ $otherItem->id }}" class="text-left d-block">Name <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" id="name-{{ $otherItem->id }}" class="form-control" value="{{ $otherItem->item_name }}" placeholder="Type item name" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="stock-{{ $otherItem->id }}" class="text-left d-block">Stock <span class="text-danger">*</span></label>
                                                            <input type="number" name="stock" id="stock-{{ $otherItem->id }}" class="form-control" value="{{ $otherItem->stock }}" placeholder="Enter stock quantity" min="0" required>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="description-{{ $otherItem->id }}" class="text-left d-block">Description</label>
                                                            <textarea name="description" id="description-{{ $otherItem->id }}" class="form-control" rows="3" placeholder="Type item description">{{ $otherItem->description }}</textarea>
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
                        <form action="{{ route('inventories.destroy', $otherItem->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="type_data" value="other_item">
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
            <tr class="text-center">
                    <td colspan="12" class="text-muted">No items found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>