<div class="table-responsive">
    <h3 class="table-title">{{ $selectedLocation ? $selectedLocation->name : 'All' }}</h3>
    <table class="table table-hover align-middle custom-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 9%;">No. Desk</th>
                <th style="width: 9%;">Computer Brand</th>
                <th style="width: 9%;">Disk Drive 1</th>
                <th style="width: 9%;">Disk Drive 2</th>
                <th style="width: 10%;">Processor</th>
                <th style="width: 10%;">VGA Card</th>
                <th style="width: 7%;">RAM</th>
                <th style="width: 7%;">Monitor</th>
                <th style="width: 7%;">Year (Approx)</th>
                <th style="width: 7%;">UPS Status</th>
                <th class="text-center" style="width: 5%;">QR Code</th>
                <th class="text-center" style="width: 9%;">Action</th>                                      
            </tr>
        </thead>
        <tbody>
            @forelse($allocatedComputers as $index => $allocatedComputer)
                <tr>
                    <td class="text-center" onclick="window.open('{{ route('allocates.show', [
                        'location' => str_replace(' ', '-', strtolower($allocatedComputer->location->name)),
                        'desk' => $allocatedComputer->desk_number
                    ]) }}', '_blank')" style="cursor: pointer;">{{ $allocatedComputer->desk_number }}</td>                                                      
                    <td>
                        {{ optional($allocatedComputer->computer)->item_name }} 
                        {{ optional($allocatedComputer->computer)->description }}
                    </td>
                    <td>
                        {{ optional($allocatedComputer->diskDrive1)->item_name }} 
                        {{ optional($allocatedComputer->diskDrive1)->description }}
                    </td>
                    <td>
                        {{ optional($allocatedComputer->diskDrive2)->item_name }}
                        {{ optional($allocatedComputer->diskDrive2)->description }}                                    
                    </td>
                    <td>
                        {{ optional($allocatedComputer->processor)->item_name }} 
                        {{ optional($allocatedComputer->processor)->description }}                              
                    </td>
                    <td>
                        {{ optional($allocatedComputer->vgaCard)->item_name }} 
                        {{ optional($allocatedComputer->vgaCard)->description }} 
                    </td>
                    <td>
                        {{ optional($allocatedComputer->ram)->item_name }} 
                        {{ optional($allocatedComputer->ram)->description }} 
                    </td>
                    <td>
                        {{ optional($allocatedComputer->monitor)->item_name }} 
                        {{ optional($allocatedComputer->monitor)->description }} 
                    </td>
                    <td>{{ optional($allocatedComputer)->year_approx }}</td>
                    <td>{{ optional($allocatedComputer)->ups_status }}</td>
                    <td class="text-center">
                        <a href="{{ route('allocates.show', [
                            'location' => str_replace(' ', '-', strtolower($allocatedComputer->location->name)),
                            'desk' => $allocatedComputer->desk_number
                        ]) }}" target="_blank">
                            <img src="{{ asset('storage/' . $allocatedComputer->qr_code) }}" 
                                 alt="QR Code" 
                                 class="img-fluid mb-3" 
                                 style="max-width: 50px; height: auto;">
                        </a>                                                                                                                                     
                    </td>
                    <td class="text-center">
                        <!-- Edit Item -->
                        <a href="javascript:;" class="btn btn-sm btn-outline-warning"  data-toggle="modal"  data-target="#editComputerModal-{{ $allocatedComputer->id }}"> 
                            <i class="fas fa-edit"></i>
                        </a>
                        <div class="modal fade" id="editComputerModal-{{ $allocatedComputer->id }}" tabindex="-1" role="dialog" aria-labelledby="editComputerModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header position-relative">
                                        <h5 class="modal-title w-100 text-center">Edit Computer</h5>
                                        <button type="button" class="close position-absolute" style="right: 1rem; top: 0.75rem;" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form role="form" action="{{ route('allocates.update', $allocatedComputer->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Hidden item_type field -->
                                        <input type="hidden" name="item_type" value="computer">
                                        <div class="modal-body text-uppercase">
                                            <div class="card shadow p-3 mb-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="location_id-{{ $allocatedComputer->id }}" class="text-left d-block">Location</label>
                                                            <input type="text" class="form-control" value="{{ $allocatedComputer->location->name }}" readonly>
                                                            <input type="hidden" name="location_id" value="{{ $allocatedComputer->location_id }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="desk_number-{{ $allocatedComputer->id }}" class="text-left d-block">No. Desk</label>
                                                            <input type="number" name="desk_number" class="form-control" value="{{ $allocatedComputer->desk_number }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>                                                    
                                            </div>                                                    
                                            <div class="card shadow p-3 mb-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="computer_id-{{ $allocatedComputer->id }}" class="text-left d-block">Computer Brand</label>
                                                            <select name="computer_id" id="computer_id-{{ $allocatedComputer->id }}" class="form-control text-center">
                                                                <option value="" disabled selected>Select Computer</option>
                                                                @foreach ($computers as $computer)
                                                                    <option value="{{ $computer->id }}" {{ $computer->id == $allocatedComputer->computer_id ? 'selected' : '' }}>
                                                                        {{ $computer->item_name }} {{ $computer->description }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>                                                                                                                               
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="processor_id-{{ $allocatedComputer->id }}" class="text-left d-block">Processor</label>
                                                            <select name="processor_id" id="processor_id-{{ $allocatedComputer->id }}" class="form-control text-center">
                                                                <option value="" disabled selected>Select Processor</option>
                                                                @foreach ($processors as $processor)
                                                                    <option value="{{ $processor->id }}" {{ $processor->id == $allocatedComputer->processor_id ? 'selected' : '' }}>
                                                                        {{ $processor->item_name }} {{ $processor->description }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>                                                                                                                               
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="disk_drive_1_id-{{ $allocatedComputer->id }}" class="text-left d-block">Disk Drive (Prymary)</label>
                                                            <select name="disk_drive_1_id" id="disk_drive_1_id-{{ $allocatedComputer->id }}" class="form-control text-center">
                                                                <option value="" disabled selected>Select Disk Drive</option>
                                                                @foreach ($diskDrives as $diskDrive)
                                                                    <option value="{{ $diskDrive->id }}" {{ $diskDrive->id == $allocatedComputer->disk_drive_1_id ? 'selected' : '' }}>
                                                                        {{ $diskDrive->item_name }} {{ $diskDrive->description }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>                                                                                                                               
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="disk_drive_2_id-{{ $allocatedComputer->id }}" class="text-left d-block">Disk Drive (Secondary)</label>
                                                            <select name="disk_drive_2_id" id="disk_drive_2_id-{{ $allocatedComputer->id }}" class="form-control text-center">
                                                                <option value="" disabled selected>Select Disk Drive</option>
                                                                @foreach ($diskDrives as $diskDrive)
                                                                    <option value="{{ $diskDrive->id }}" {{ $diskDrive->id == $allocatedComputer->disk_drive_2_id ? 'selected' : '' }}>
                                                                        {{ $diskDrive->item_name }} {{ $diskDrive->description }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>                                                                                                                               
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="vga_card_id-{{ $allocatedComputer->id }}" class="text-left d-block">VGA Card</label>
                                                            <select name="vga_card_id" id="vga_card_id-{{ $allocatedComputer->id }}" class="form-control text-center">
                                                                <option value="" disabled selected>Select VGA Card</option>
                                                                @foreach ($vgaCards as $vgaCard)
                                                                    <option value="{{ $vgaCard->id }}" {{ $vgaCard->id == $allocatedComputer->vga_card_id ? 'selected' : '' }}>
                                                                        {{ $vgaCard->item_name }} {{ $vgaCard->description }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>                                                                                                                               
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="ram_id-{{ $allocatedComputer->id }}" class="text-left d-block">RAM</label>
                                                            <select name="ram_id" id="ram_id-{{ $allocatedComputer->id }}" class="form-control text-center">
                                                                <option value="" disabled selected>Select RAM</option>
                                                                @foreach ($rams as $ram)
                                                                    <option value="{{ $ram->id }}" {{ $ram->id == $allocatedComputer->ram_id ? 'selected' : '' }}>
                                                                        {{ $ram->item_name }} {{ $ram->description }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>                                                                                                                               
                                                    <div class="col-md-7">
                                                        <div class="form-group">
                                                            <label for="monitor_id-{{ $allocatedComputer->id }}" class="text-left d-block">Monitor</label>
                                                            <select name="monitor_id" id="monitor_id-{{ $allocatedComputer->id }}" class="form-control text-center">
                                                                <option value="" disabled selected>Select Monitor</option>
                                                                @foreach ($monitors as $monitor)
                                                                    <option value="{{ $monitor->id }}" {{ $monitor->id == $allocatedComputer->monitor_id ? 'selected' : '' }}>
                                                                        {{ $monitor->item_name }} {{ $monitor->description }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>                                                                                                                               
                                                </div>                                                  
                                            </div>      
                                            <div class="card shadow p-3 mb-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="year_approx-{{ $allocatedComputer->id }}">Year (Approx)</label>
                                                            <select name="year_approx" id="year_approx-{{ $allocatedComputer->id }}" class="form-control">
                                                                <option value="" disabled {{ empty($allocatedComputer->year_approx) ? 'selected' : '' }}>Pilih Tahun</option>
                                                                @for ($year = 2000; $year <= date('Y'); $year++)
                                                                    <option value="{{ $year }}" {{ $year == $allocatedComputer->year_approx ? 'selected' : '' }}>{{ $year }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>                                                  
                                                    </div>   
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="ups_status-{{ $allocatedComputer->id }}" class="text-sm font-weight-light text-dark">UPS Status</label>
                                                            <select class="form-control" id="ups_status-{{ $allocatedComputer->id }}" name="ups_status">
                                                                <option value="" {{ is_null($allocatedComputer->ups_status) ? 'selected' : '' }}>-</option>
                                                                <option value="Active" {{ $allocatedComputer->ups_status === 'Active' ? 'selected' : '' }}>Active</option>
                                                                <option value="Inactive" {{ $allocatedComputer->ups_status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                            </select>  
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
                        <form action="{{ route('allocates.destroy', $allocatedComputer->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="item_type" value="computer">
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="text-center">
                    <td colspan="12" class="text-muted">There are no computers in this location.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>