<?php

use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Auth\LoginController;
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

        Route::get('/dashboard', function () {
        return 'Ini Dashboard Admin';
        })->name('dashboard');
        Route::resource('karyawan', KaryawanController::class);
    });

    //role karyawan
    Route::middleware(['role:karyawan'])->group(function () {
        Route::get('/dashboard/karyawan', function () {
            return 'Ini Dashboard Karyawan';
        });
        
    });
});
