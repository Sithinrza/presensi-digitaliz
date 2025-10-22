<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/login-proses', [LoginController::class, 'login'])->name('login.proses');
});

Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');


Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard/admin', function () {
            return 'Ini Dashboard Admin';
        });
    });

    Route::middleware(['role:karyawan'])->group(function () {
        Route::get('/dashboard/karyawan', function () {
            return 'Ini Dashboard Karyawan';
        });
    });
});
