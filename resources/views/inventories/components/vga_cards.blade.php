<div class="table-responsive">
    <h3 class="table-title">VGA Cards</h3>
    <table class="table table-hover align-middle custom-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 10%;">No.</th>
                <th style="width: 20%;">Brand</th>
                <th style="width: 20%;">Size</th>
                <th style="width: 20%;">Stock</th>
                <th class="text-center" style="width: 10%;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vgaCards as $index => $vgaCard)
                <tr>
                    @php
                        [$size] = explode(' - ', $vgaCard->description);
                        $size = str_replace(' GB', '', $size);
                    @endphp
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $vgaCard->item_name }}</td>
                    <td>{{ $size ? $size . ' GB' : 'N/A' }}</td>
                    <td>{{ $vgaCard->stock }}</td>
                    <td class="text-center">
                        <!-- Edit Button -->
                        <a href="javascript:;" class="btn btn-sm btn-outline-warning"  data-toggle="modal"  data-target="#editVGAModal-{{ $vgaCard->id }}"> 
                            <i class="fas fa-edit"></i>
                        </a>
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editVGAModal-{{ $vgaCard->id }}" tabindex="-1" role="dialog" aria-labelledby="editVGAModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header position-relative">
                                        <h5 class="modal-title w-100 text-center">Edit VGA Card</h5>
                                        <button type="button" class="close position-absolute" style="right: 1rem; top: 0.75rem;" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>                                                                                      
                                    <form role="form" action="{{ route('inventories.update', $vgaCard->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Hidden type_data field -->
                                        <input type="hidden" name="item_type" value="vga_card">
                                        <div class="modal-body text-uppercase">
                                            <div class="card shadow p-3 mb-3">
                                                <div class="row">
                                                    <!-- Brand -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="brand-{{ $vgaCard->id }}" class="text-left d-block">Brand <span class="text-danger">*</span></label>
                                                            <input type="text" name="brand" id="brand-{{ $vgaCard->id }}" class="form-control"
                                                                value="{{ $vgaCard->item_name }}" required>
                                                        </div>
                                                    </div>
                                                
                                                    <!-- Size -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="size-{{ $vgaCard->id }}" class="text-left d-block">Size <span class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <input type="number" name="size" id="size-{{ $vgaCard->id }}" class="form-control"
                                                                    min="0" value="{{ $size }}" required>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">GB</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                    <!-- Stock -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="stock-{{ $vgaCard->id }}" class="text-left d-block">Stock <span class="text-danger">*</span></label>
                                                            <input type="number" name="stock" id="stock-{{ $vgaCard->id }}" class="form-control"
                                                                min="0" value="{{ $vgaCard->stock }}" required>
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
                        <form action="{{ route('inventories.destroy', $vgaCard->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="type_data" value="vga_card">
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="text-center">
                    <td colspan="12" class="text-muted">No VGA Cards found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>