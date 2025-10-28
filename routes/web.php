<?php

use App\Http\Controllers\Admin\AdDashController;
use App\Http\Controllers\Admin\AdPresensiController;
use App\Http\Controllers\Admin\AdProfileController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Karyawan\KarDashController;
use App\Http\Controllers\Karyawan\KarJadwalController;
use App\Http\Controllers\Karyawan\KarProfileController;
use App\Http\Controllers\Karyawan\LogAkController;
use App\Http\Controllers\Karyawan\LogHarianController;
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

        Route::get('/presensi/rekap', [AdPresensiController::class, 'rekap'])->name('presensi.rekap');
        Route::get('/presensi/detail', [AdPresensiController::class, 'detail'])->name('presensi.detail');
        Route::resource('presensi', AdPresensiController::class);

        Route::get('/profile', [AdProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/detail', [AdProfileController::class, 'detail'])->name('profile.detail');
    });

    //role karyawan
    Route::middleware(['role:karyawan'])
     ->prefix('karyawan')
        ->name('karyawan.')
        ->group(function () {

        Route::get('/dashboard', [KarDashController::class, 'index'])->name('dashboard');
        Route::get('/log-harian', [LogHarianController::class, 'index'])->name('log.index');
        Route::post('/log-harian', [LogHarianController::class, 'store'])->name('log.store');

        Route::get('/jadwal', [KarJadwalController::class, 'index'])->name('jadwal.index');

        Route::get('/profile', [KarProfileController::class, 'index'])->name('profile.index');


    });


});
