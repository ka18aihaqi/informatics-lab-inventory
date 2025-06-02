@extends('layouts.app')
<style>
    .form-group {
        margin-bottom: 10px;
    }

    .form-group label {
        display: block;
        font-weight: 500;
        margin-bottom: 8px;
        color: #003078;
    }

    .form-group input {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        transition: 0.3s;
    }

    .form-group input:focus {
        border-color: #f7d200;
        outline: none;
        box-shadow: 0 0 0 2px rgba(247, 210, 0, 0.3);
    }

    .password-wrapper {
        position: relative;
    }

    .password-wrapper input {
        width: 100%;
        padding-right: 40px;
    }

    .toggle-password {
        position: absolute;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #777;
    }
</style>

@section('content')
    <div class="content-header d-flex">
        <div class="col-12 d-flex justify-content-center">
            <h1 class="title text-uppercase">Profile</h1>
        </div>
    </div>

    <div class="content-body">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-5 m-3">
                    <form action="{{ route('profile.update', auth()->user()->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Method spoofing karena HTML hanya support GET/POST --}}
                        
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username', auth()->user()->username) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                        </div>

                        <div class="mt-5 d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#Modal">
                                Change Password
                            </button>
                            <button type="submit" class="btn btn-outline-success">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="locationForm" role="form" action="{{ route('profile.change-password') }}" method="POST">
                    @csrf
                    <div class="modal-body text-uppercase">
                        <div class="card shadow p-3 mb-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group password-group">
                                        <label for="old_password">Current Password</label>
                                        <div class="password-wrapper">
                                            <input type="password" name="old_password" placeholder="Enter your current password" required>
                                            <i class="fa fa-eye toggle-password" onclick="togglePassword(this)"></i>
                                        </div>
                                    </div>                                           
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group password-group">
                                        <label for="password">New Password</label>
                                        <div class="password-wrapper">
                                            <input type="password" id="password" name="new_password" placeholder="Enter your new password" required>
                                            <i class="fa fa-eye toggle-password" onclick="togglePassword(this)"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group password-group">
                                        <label for="new_password_confirmation">Confirm New Password</label>
                                        <div class="password-wrapper">
                                            <input type="password" name="new_password_confirmation"  placeholder="Enter your new password" required>
                                            <i class="fa fa-eye toggle-password" onclick="togglePassword(this)"></i>
                                        </div>
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

    @include('layouts.error_modal')
    @include('layouts.success_modal')
@endsection
