<div class="table-responsive">
    <h3 class="table-title">RAMs</h3>
    <table class="table table-hover align-middle custom-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 10%;">No.</th>
                <th style="width: 20%;">Size</th>
                <th style="width: 20%;">Type</th>
                <th style="width: 20%;">Stock</th>
                <th class="text-center" style="width: 10%;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rams as $index => $ram)
                <tr>
                    @php
                        [$size] = explode(' - ', $ram->description);
                        $size = str_replace(' GB', '', $size);
                    @endphp
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $size ? $size . ' GB' : 'N/A' }}</td>
                    <td>{{ $ram->item_name }}</td>
                    <td>{{ $ram->stock }}</td>
                    <td class="text-center">
                        <!-- Edit Button -->
                        <a href="javascript:;" class="btn btn-sm btn-outline-warning"  data-toggle="modal"  data-target="#editRAMModal-{{ $ram->id }}"> 
                            <i class="fas fa-edit"></i>
                        </a>
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editRAMModal-{{ $ram->id }}" tabindex="-1" role="dialog" aria-labelledby="editRAMModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header position-relative">
                                        <h5 class="modal-title w-100 text-center">Edit VGA Card</h5>
                                        <button type="button" class="close position-absolute" style="right: 1rem; top: 0.75rem;" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>                                                                                      
                                    <form role="form" action="{{ route('inventories.update', $ram->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Hidden type_data field -->
                                        <input type="hidden" name="item_type" value="ram">
                                        <div class="modal-body text-uppercase">
                                            <div class="card shadow p-3 mb-3">
                                                <div class="row">
                                                    <!-- Size -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="size-{{ $ram->id }}" class="text-left d-block">Size <span class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <input type="number" name="size" id="size-{{ $ram->id }}" class="form-control"
                                                                    placeholder="Enter size" min="0" value="{{ $size }}" required>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">GB</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                    <!-- Type -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="type-{{ $ram->id }}" class="text-left d-block">Type <span class="text-danger">*</span></label>
                                                            <select name="type" id="type-{{ $ram->id }}" class="form-control" required>
                                                                <option value="" disabled style="text-align: center;">-- Select RAM Type --</option>
                                                                <option value="DDR3" {{ $ram->item_name == 'DDR3' ? 'selected' : '' }}>DDR3</option>
                                                                <option value="DDR4" {{ $ram->item_name == 'DDR4' ? 'selected' : '' }}>DDR4</option>
                                                                <option value="DDR5" {{ $ram->item_name == 'DDR5' ? 'selected' : '' }}>DDR5</option>
                                                                <option value="Other" {{ $ram->item_name == 'Other' ? 'selected' : '' }}>Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                
                                                    <!-- Stock -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="stock-{{ $ram->id }}" class="text-left d-block">Stock <span class="text-danger">*</span></label>
                                                            <input type="number" name="stock" id="stock-{{ $ram->id }}" class="form-control"
                                                                placeholder="Enter stock quantity" min="0" value="{{ $ram->stock }}" required>
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
                        <form action="{{ route('inventories.destroy', $ram->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="type_data" value="ram">
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
            <tr class="text-center">
                    <td colspan="12" class="text-muted">No RAM (Random Access Memory) found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>