<div class="table-responsive">
    <h3 class="table-title">Disk Drives</h3>
    <table class="table table-hover align-middle custom-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 10%;">No.</th>
                <th style="width: 20%;">Brand</th>
                <th style="width: 20%;">Model</th>
                <th style="width: 10%;">Stock</th>
                <th class="text-center" style="width: 10%;">Action</th>                        
            </tr>
        </thead>
        <tbody>
            @forelse($computers as $index => $computer)
                <tr>
                    @php
                        [$model] = explode(' - ', $computer->description);
                    @endphp
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $computer->item_name }}</td>                                                         
                    <td>{{ $model }}</td>
                    <td>{{ $computer->stock }}</td>
                    <td class="text-center">

                        <!-- Edit Item -->
                        <a href="javascript:;" class="btn btn-sm btn-outline-warning"  data-toggle="modal"  data-target="#editComputerModal-{{ $computer->id }}"> 
                            <i class="fas fa-edit"></i>
                        </a>
                        <div class="modal fade" id="editComputerModal-{{ $computer->id }}" tabindex="-1" role="dialog" aria-labelledby="editComputerModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header position-relative">
                                        <h5 class="modal-title w-100 text-center">Edit Computer</h5>
                                        <button type="button" class="close position-absolute" style="right: 1rem; top: 0.75rem;" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form role="form" action="{{ route('inventories.update', $computer->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Hidden type_data field -->
                                        <input type="hidden" name="item_type" value="computer">
                                        <div class="modal-body text-uppercase">
                                            <div class="card shadow p-3 mb-3">
                                                <div class="row">
                                                    <!-- Brand -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="brand-{{ $computer->id }}" class="text-left d-block">Brand<span class="text-danger">*</span></label>
                                                            <input type="text" name="brand" id="brand-{{ $computer->id }}" class="form-control" value="{{ $computer->item_name }}" required>
                                                        </div>
                                                    </div>

                                                    <!-- Model -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="model-{{ $computer->id }}" class="text-left d-block">Model<span class="text-danger">*</span></label>
                                                            <input type="text" name="model" id="model-{{ $computer->id }}" class="form-control" value="{{ $model }}" required>
                                                        </div>
                                                    </div>
                                            
                                                    <!-- Stock -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="stock-{{ $computer->id }}" class="text-left d-block">Stock<span class="text-danger">*</span></label>
                                                            <input type="number" name="stock" id="stock-{{ $computer->id }}" class="form-control" value="{{ $computer->stock }}" min="0" required>
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
                        <form action="{{ route('inventories.destroy', $computer->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="type_data" value="computer">
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
            <tr class="text-center">
                    <td colspan="12" class="text-muted">No Computers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>