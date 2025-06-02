<div class="table-responsive">
    <h3 class="table-title">Monitor</h3>
    <table class="table table-hover align-middle custom-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 10%;">No.</th>
                <th style="width: 20%;">Brand</th>
                <th style="width: 20%;">Resolution</th>
                <th style="width: 20%;">Inch</th>
                <th style="width: 20%;">Stock</th>
                <th class="text-center" style="width: 10%;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($monitors as $index => $monitor)
                <tr>
                    @php
                        [$resolution, $inch] = explode(' - ', $monitor->description);
                        $inch = str_replace(' inch', '', $inch);
                    @endphp
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $monitor->item_name }}</td>
                    <td>{{ $resolution }}</td>
                    <td>{{ $inch }}</td>
                    <td>{{ $monitor->stock }}</td>
                    <td class="text-center">
                        <!-- Edit Button -->
                        <a href="javascript:;" class="btn btn-sm btn-outline-warning"  data-toggle="modal"  data-target="#editMonitorModal-{{ $monitor->id }}"> 
                            <i class="fas fa-edit"></i>
                        </a>
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editMonitorModal-{{ $monitor->id }}" tabindex="-1" role="dialog" aria-labelledby="editMonitorModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header position-relative">
                                        <h5 class="modal-title w-100 text-center">Edit Monitor</h5>
                                        <button type="button" class="close position-absolute" style="right: 1rem; top: 0.75rem;" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>                                                                                      
                                    <form role="form" action="{{ route('inventories.update', $monitor->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Hidden type_data field -->
                                        <input type="hidden" name="item_type" value="monitor">
                                        <div class="modal-body text-uppercase">
                                            <div class="card shadow p-3 mb-3">
                                                <div class="row">
                                                    <!-- Brand -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="brand-{{ $monitor->id }}" class="text-left d-block">Brand<span class="text-danger">*</span></label>
                                                            <input type="text" name="brand" id="brand-{{ $monitor->id }}" class="form-control" value="{{ $monitor->item_name }}" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Resolution -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="resolution-{{ $monitor->id }}" class="text-left d-block">Resolution<span class="text-danger">*</span></label>
                                                            <input type="text" name="resolution" id="resolution-{{ $monitor->id }}" class="form-control" value="{{ $resolution }}" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Inch -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="inch-{{ $monitor->id }}" class="text-left d-block">Inch<span class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <input type="number" name="inch" id="inch-{{ $monitor->id }}" class="form-control" min="0" step="0.01" value="{{ $inch }}" required>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">inch</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                    <!-- Stock -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="stock-{{ $monitor->id }}" class="text-left d-block">Stock <span class="text-danger">*</span></label>
                                                            <input type="number" name="stock" id="stock-{{ $monitor->id }}" class="form-control"
                                                                placeholder="Enter stock quantity" min="0" value="{{ $monitor->stock }}" required>
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
                        <form action="{{ route('inventories.destroy', $monitor->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="type_data" value="monitor">
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
            <tr class="text-center">
                    <td colspan="12" class="text-muted">No Monitors found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>