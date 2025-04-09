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
    // Pegawai, Jabatan, dan Kehadiran masih menggunakan resource
    Route::resource('pegawai', UserController::class);
    Route::resource('jabatan', JabatanController::class);
    Route::resource('kehadiran', KehadiranController::class);

    // Rute untuk pengajuan cuti tanpa resource
    Route::get('/pengajuan_cuti', [PengajuanCutiController::class, 'index'])->name('pengajuan_cuti.index');
    // Route::get('/pengajuan_cuti/create', [PengajuanCutiController::class, 'create'])->name('pengajuan_cuti.create');
    Route::post('/pengajuan_cuti', [PengajuanCutiController::class, 'store'])->name('pengajuan_cuti.store');
    // Route::get('/pengajuan_cuti/{pengajuan_cuti}', [PengajuanCutiController::class, 'show'])->name('pengajuan_cuti.show');
    // Route::get('/pengajuan_cuti/{pengajuan_cuti}/edit', [PengajuanCutiController::class, 'edit'])->name('pengajuan_cuti.edit');
    Route::patch('/pengajuan_cuti/{pengajuan_cuti}', [PengajuanCutiController::class, 'update'])
        ->name('pengajuan_cuti.update')
        ->middleware('role:admin'); // Hanya admin yang bisa approve/reject
    // Route::delete('/pengajuan_cuti/{pengajuan_cuti}', [PengajuanCutiController::class, 'destroy'])->name('pengajuan_cuti.destroy');

    // Rute untuk laporan
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.exportPdf');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Rute home
    Route::get('/home', [UserController::class, 'home'])->name('home');
});
