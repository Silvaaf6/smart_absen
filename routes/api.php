<?php

use App\Http\Controllers\Api\ApiPegawaiController;
use App\Http\Controllers\Api\ApiJabatanController;
use App\Http\Controllers\Api\ApiKehadiranController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pegawai', [\App\Http\Controllers\UserController::class, 'indexapi']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('profile', ApiPegawaiController::class)->except('create', 'edit');
});

Route::middleware('auth:sanctum')->prefix('jabatan')->group(function () {
    Route::get('/', [ApiJabatanController::class, 'index']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/kehadiran', [ApiKehadiranController::class, 'index']);
    Route::post('/kehadiran', [ApiKehadiranController::class, 'store']);
});


Route::post('/login', [AuthController::class, 'login']);
