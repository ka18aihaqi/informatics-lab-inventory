<div class="table-responsive">
    <h3 class="table-title">Processors</h3>
    <table class="table table-hover align-middle custom-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 10%;">No.</th>
                <th style="width: 20%;">Type</th>
                <th style="width: 20%;">Model</th>
                <th style="width: 20%;">Frequnecy</th>
                <th style="width: 10%;">Stock</th>
                <th class="text-center" style="width: 10%;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($processors as $index => $processor)
                <tr>
                    @php
                        [$model, $frequency] = explode(' - ', $processor->description);
                        $frequency = str_replace(' GHz', '', $frequency);
                    @endphp
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $processor->item_name }}</td>
                    <td>{{ $model }}</td>
                    <td>{{ $frequency ? $frequency . ' GHz' : 'N/A' }}</td>
                    <td>{{ $processor->stock }}</td>
                    <td class="text-center">
                        <!-- Edit Button -->
                        <a href="javascript:;" class="btn btn-sm btn-outline-warning"  data-toggle="modal"  data-target="#editProcessorModal-{{ $processor->id }}"> 
                            <i class="fas fa-edit"></i>
                        </a>
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editProcessorModal-{{ $processor->id }}" tabindex="-1" role="dialog" aria-labelledby="editProcessorModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header position-relative">
                                        <h5 class="modal-title w-100 text-center">Edit Processor</h5>
                                        <button type="button" class="close position-absolute" style="right: 1rem; top: 0.75rem;" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>                                                                                      
                                    <form role="form" action="{{ route('inventories.update', $processor->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Hidden type_data field -->
                                        <input type="hidden" name="item_type" value="processor">
                                        <div class="modal-body text-uppercase">
                                            <div class="card shadow p-3 mb-3">
                                                <div class="row">
                                                    <!-- Tipe Processor -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="type-{{ $processor->id }}" class="text-left d-block">Type <span class="text-danger">*</span></label>
                                                            <select name="type" id="type-{{ $processor->id }}" class="form-control" required>
                                                                <option value="" disabled style="text-align: center;">-- Select Type --</option>
                                                                <option value="Intel" {{ $processor->item_name == 'Intel' ? 'selected' : '' }}>Intel</option>
                                                                <option value="AMD" {{ $processor->item_name == 'AMD' ? 'selected' : '' }}>AMD</option>
                                                                <option value="Apple" {{ $processor->item_name == 'Apple' ? 'selected' : '' }}>Apple (M Series)</option>
                                                                <option value="Other" {{ $processor->item_name == 'Other' ? 'selected' : '' }}>Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                            
                                                    <!-- Model Processor -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="model-{{ $processor->id }}" class="text-left d-block">Model <span class="text-danger">*</span></label>
                                                            <input type="text" name="model" id="model-{{ $processor->id }}" class="form-control"
                                                                placeholder="e.g. Core i5-12400F, Ryzen 5 5600" value="{{ $model }}" required>
                                                        </div>
                                                    </div>
                                            
                                                    <!-- Frequency -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="frequency-{{ $processor->id }}" class="text-left d-block">Frequency <span class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <input type="number" name="frequency" id="frequency-{{ $processor->id }}" class="form-control"
                                                                    placeholder="e.g. 3.60" min="0" max="10" step="0.01" value="{{ $frequency }}" required>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">GHz</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            
                                                    <!-- Stock -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="stock-processor-{{ $processor->id }}" class="text-left d-block">Stock <span class="text-danger">*</span></label>
                                                            <input type="number" name="stock" id="stock-processor-{{ $processor->id }}" class="form-control"
                                                                placeholder="Enter stock quantity" min="0" value="{{ $processor->stock }}" required>
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
                        <form action="{{ route('inventories.destroy', $processor->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="type_data" value="processor">
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
            <tr class="text-center">
                    <td colspan="12" class="text-muted">No Processors found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>