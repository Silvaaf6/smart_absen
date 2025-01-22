<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\JabatanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Jabatan;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes([
    'register' => false,
]);

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::resource('pegawai', UserController::class);
    Route::resource('jabatan', JabatanController::class);

    Route::get('/home', [UserController::class, 'home'])->name('home');
});

// Route::get('user/create', [UserController::class, 'create'])->name('user.create');
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::resource('jadwal', JadwalController::class);
// Route::resource('piket', PiketController::class);
