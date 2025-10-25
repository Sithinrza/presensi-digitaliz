<?php

use App\Http\Controllers\Admin\AdDashController;
use App\Http\Controllers\Admin\AdPresensiController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Karyawan\KarDashController;
use Illuminate\Support\Facades\Route;


// auth
Route::middleware(['guest'])->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/login-proses', [LoginController::class, 'login'])->name('login.proses');
});
Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');


//role
Route::middleware(['auth'])->group(function () {

    //role admin
    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

        Route::get('/dashboard', [AdDashController::class, 'index'])->name('dashboard');
        Route::resource('karyawan', KaryawanController::class);
        Route::resource('presensi', AdPresensiController::class);
    });

    //role karyawan
    Route::middleware(['role:karyawan'])
     ->prefix('karyawan')
        ->name('karyawan.')
        ->group(function () {

        Route::get('/dashboard', [KarDashController::class, 'index'])->name('dashboard');

    });

    //buat coba aja
    Route::get('/karyawan/log', function () {
    return view('karyawan.log.log'); // nama file Blade kamu
});

});
