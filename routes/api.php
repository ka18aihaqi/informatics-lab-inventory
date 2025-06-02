<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PcController;
use App\Http\Controllers\RamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeskController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\VgaCardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DiskDriveController;
use App\Http\Controllers\ProcessorController;
use App\Http\Controllers\TransferLogController;

// Public routes
Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Ganti semua aksi user ke endpoint tanpa parameter ID
    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user', [UserController::class, 'update']);
    Route::delete('/user', [UserController::class, 'destroy']);
    Route::post('/user/change-password', [UserController::class, 'changePassword']);
    Route::post('/user/restore', [UserController::class, 'restore']);
    Route::delete('/user/force-delete', [UserController::class, 'forceDelete']);

    Route::apiResource('locations', LocationController::class);
    Route::apiResource('desks', DeskController::class);
    Route::apiResource('disk-drives', DiskDriveController::class);
    Route::apiResource('processors', ProcessorController::class);
    Route::apiResource('vga-cards', VgaCardController::class);
    Route::apiResource('rams', RamController::class);
    Route::apiResource('pcs', PcController::class);
    Route::apiResource('assets', AssetController::class);
    Route::apiResource('items', ItemController::class);
    Route::get('/transfer-logs/download-pdf/{date}', [TransferLogController::class, 'downloadPDF']);
    Route::apiResource('transfer-logs', TransferLogController::class);

    Route::get('/locations/{id}/download', [LocationController::class, 'downloadPDF'])->name('locations.download');
});
