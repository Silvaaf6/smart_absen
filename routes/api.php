<?php

use App\Http\Controllers\Api\ApiJabatanController;
use App\Http\Controllers\Api\ApiKehadiranController;
use App\Http\Controllers\Api\ApiPengajuanCutiController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/profile', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
    Route::get('/jabatan', [ApiJabatanController::class, 'index']);
    Route::post('/jabatan', [ApiJabatanController::class, 'store']);
    Route::get('/kehadiran', [ApiKehadiranController::class, 'index']);
    Route::get('/pengajuancuti', [ApiPengajuanCutiController::class, 'index']);
});



