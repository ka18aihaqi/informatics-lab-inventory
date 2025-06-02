@extends('layouts.app')

@section('content')
    <div class="content-header d-flex">
        <div class="col-6 d-flex justify-content-start">
            <h1 class="title">Locations</h1>
        </div>
        <div class="col-6 d-flex justify-content-end">
            <button type="button" class="btn btn-sm btn-outline-add" data-toggle="modal" data-target="#Modal">
                <i class="fas fa-plus"></i> <span class="add-text ml-1">Add Locations</span>
            </button>  
            <!-- Modal -->
            <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">Add New Location</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="locationForm" role="form" action="{{ route('locations.store') }}" method="POST">
                            @csrf
                            <div class="modal-body text-uppercase">
                                <div class="card shadow p-3 mb-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" id="name" class="form-control" placeholder="Type location name" required>
                                            </div>                                            
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea name="description" id="description" class="form-control" rows="3" placeholder="Type location description"></textarea>
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
        <div class="table-responsive">
            <table class="table table-hover align-middle custom-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 10%;">No.</th>
                        <th style="width: 20%;">Name</th>
                        <th style="width: 20%;">Description</th>
                        <th class="text-center" style="width: 10%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $index => $location)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $location->name }}</td>
                            <td>{{ $location->description }}</td>
                            <td class="text-center">
                                <!-- Edit Button -->
                                <a href="javascript:;" class="btn btn-sm btn-outline-warning"  data-toggle="modal"  data-target="#editLocationModal-{{ $location->id }}"> 
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Edit Modal -->
                                <div class="modal fade" id="editLocationModal-{{ $location->id }}" tabindex="-1" role="dialog" aria-labelledby="editLocationModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Location</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form role="form" action="{{ route('locations.update', $location->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body text-uppercase">
                                                    <div class="card shadow-sm p-3 mb-4">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="name-{{ $location->id }}" class="text-left d-block">Location</label>
                                                                    <input type="text" name="name" id="name-{{ $location->id }}" class="form-control" value="{{ $location->name }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="description-{{ $location->id }}" class="text-left d-block">Description</label>
                                                                    <textarea name="description" id="description-{{ $location->id }}" class="form-control" rows="3">{{ $location->description }}</textarea>
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
                                {{-- Delete --}}
                                <form action="{{ route('locations.destroy', $location->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted">No locations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>    
    @include('layouts.error_modal')
    @include('layouts.success_modal')
@endsection
