@extends('layouts.app')

@section('content')
<div class="content-header d-flex">
    <!-- Location and Desk Section -->
    <div class="col-4 d-flex justify-content-start align-items-center text-uppercase">
        <h1 class="title" style="font-size: 18px !important;">{{ $allocatedComputer->location->name }}</h1>
        <h1 class="title ms-3" style="font-size: 18px !important;">No. Desk: {{ $allocatedComputer->desk_number }}</h1>
    </div>

    <!-- Title Section with custom color -->
    <div class="col-4 d-flex justify-content-center align-items-center" style="padding-top: 10px;">
        <h5 class="card-title mb-4 text-primary" style="color: #007bff;">
            <i class="fas fa-desktop me-2"></i> <span class="add-text ml-1">Detail Komputer</span> 
        </h5>
    </div>

    <!-- Download QR Section with a different button style -->
    <div class="col-4 d-flex justify-content-end">
        <a href="{{ asset('storage/' . $allocatedComputer->qr_code) }}" 
            download="{{ $allocatedComputer->location->name }}_No.{{ $allocatedComputer->desk_number }}.png" 
            class="btn btn-outline-success">
            <i class="fas fa-download"></i> <span class="add-text ml-1">Download QR Code</span> 
        </a>
    </div>
</div>
<div class="content-body">
    <div class="card shadow">
        <div class="row justify-content-center">
            <!-- QR Code Section -->
            <div class="col-md-4">
                <div class="card border-0">
                    <div class="card-body text-center">
                        <img src="{{ asset('storage/' . $allocatedComputer->qr_code) }}" 
                            alt="QR Code" 
                            class="img-fluid mb-3 w-100" 
                            style="max-width: 100%;">
                    </div>
                </div>
            </div>
        
            <!-- Detail Section -->
            <div class="col-md-6 ">
                <div class="card border-0">
                    <div class="card-body text-center">
                        <div class="list-group">
                            <p class="list-group-item d-flex align-items-center">
                                <strong>Brand:</strong>
                                <span class="ms-5">{{ optional($allocatedComputer->computer)->item_name }} {{ optional($allocatedComputer->computer)->description }}</span>
                            </p>                            
                            <p class="list-group-item d-flex align-items-center">
                                <strong>Disk Drive (Primary):</strong>
                                <span class="ms-5">{{ optional($allocatedComputer->diskDrive1)->item_name }} {{ optional($allocatedComputer->diskDrive1)->description }}</span>
                            </p>
                            <p class="list-group-item d-flex align-items-center">
                                <strong>Disk Drive (Secondary):</strong>
                                <span class="ms-5">{{ optional($allocatedComputer->diskDrive2)->item_name }} {{ optional($allocatedComputer->diskDrive2)->description }}</span>
                            </p>
                            <p class="list-group-item d-flex align-items-center">
                                <strong>Processor:</strong>
                                <span class="ms-5">{{ optional($allocatedComputer->processor)->item_name }} {{ optional($allocatedComputer->processor)->description }}</span>
                            </p>
                            <p class="list-group-item d-flex align-items-center">
                                <strong>VGA Card:</strong>
                                <span class="ms-5">{{ optional($allocatedComputer->vgaCard)->item_name }} {{ optional($allocatedComputer->vgaCard)->description }}</span>
                            </p>
                            <p class="list-group-item d-flex align-items-center">
                                <strong>RAM:</strong>
                                <span class="ms-5">{{ optional($allocatedComputer->ram)->item_name }} {{ optional($allocatedComputer->ram)->description }}</span>
                            </p>
                            <p class="list-group-item d-flex align-items-center">
                                <strong>Monitor:</strong>
                                <span class="ms-5">{{ optional($allocatedComputer->monitor)->item_name }} {{ optional($allocatedComputer->monitor)->description }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>                                                  
    </div>
</div>
@endsection
