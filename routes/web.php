<?php

use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengajuanCutiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes([
    'register' => false,
]);

Auth::routes();

// Route::middleware('auth')->group(function () {
//     Route::resource('pegawai', UserController::class);
//     Route::resource('jabatan', JabatanController::class);
//     Route::resource('kehadiran', KehadiranController::class);
//     Route::resource('pengajuan_cuti', PengajuanCutiController::class);
//     Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.exportPdf');
//     Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
//     Route::get('/home', [UserController::class, 'home'])->name('home');
// });

Route::middleware('auth')->group(function () {
    Route::resource('pegawai', UserController::class);
    Route::resource('jabatan', JabatanController::class);
    Route::resource('kehadiran', KehadiranController::class);

    Route::get('/pengajuan_cuti', [PengajuanCutiController::class, 'index'])->name('pengajuan_cuti.index');
    Route::post('/pengajuan_cuti', [PengajuanCutiController::class, 'store'])->name('pengajuan_cuti.store');
    Route::patch('/pengajuan_cuti/{pengajuan_cuti}', [PengajuanCutiController::class, 'update'])
        ->name('pengajuan_cuti.update')
        ->middleware('role:admin');

    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.exportPdf');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    Route::get('/home', [UserController::class, 'home'])->name('home');
});
