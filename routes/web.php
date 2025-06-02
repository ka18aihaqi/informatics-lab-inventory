<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AllocateController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.show');
Route::post('/register', [UserController::class, 'store'])->name('register');

Route::group(['middleware' => 'auth'], function () {
    // Dashboard
    Route::get('/', function () {return redirect('/dashboard');});
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Locations
    Route::resource('locations', LocationController::class);
    
    // Inventories
    Route::resource('inventories', InventoryController::class);
    Route::get('/inventories/download/pdf', [InventoryController::class, 'downloadPDF'])->name('inventories.download.pdf');

    // Allocates
    Route::resource('allocates', AllocateController::class);
    Route::get('/get-available-desks/{location_id}', [AllocateController::class, 'getAvailableDesks']);
    Route::get('/allocates/{location}/{desk}', [AllocateController::class, 'show'])->name('allocates.show');
    Route::get('/allocates/download/pdf/{location}', [AllocateController::class, 'downloadPDF'])->name('allocates.download.pdf');

    // TransferController
    Route::resource('transfers', TransferController::class);
    Route::get('/get-desks-by-location/{locationId}', [TransferController::class, 'getDesksByLocation']);
    Route::get('/get-desk-components/{locationId}/{deskNumber}', [TransferController::class, 'getDeskComponents']);
    Route::get('/get-items-by-location/{locationId}', [TransferController::class, 'getItemsByLocation']);
    Route::post('/allocated-computers/transfer', [TransferController::class, 'transferAllocatedComputer'])->name('allocated-computers.transfer');
    Route::post('/allocated-items/transfer', [TransferController::class, 'transferAllocatedItem'])->name('allocated-items.transfer');
    Route::get('/transfers/download/pdf', [TransferController::class, 'downloadPDF'])->name('transfers.download.pdf');
    
    // Profile
    Route::resource('profile', UserController::class);
    Route::post('/profile/change-password', [UserController::class, 'changePassword'])->name('profile.change-password');

    // Logoute
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
