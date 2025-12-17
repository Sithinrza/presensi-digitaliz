<?php

use App\Http\Controllers\Admin\AdAgendaController;
use App\Http\Controllers\Admin\AdDailyReportController;
use App\Http\Controllers\Admin\AdDashController;
use App\Http\Controllers\Admin\AdJadwalController;
use App\Http\Controllers\Admin\AdJadwalPenetapanController;
use App\Http\Controllers\Admin\AdLogHarianController;
use App\Http\Controllers\Admin\AdPresensiController;
use App\Http\Controllers\Admin\AdProfileController;
use App\Http\Controllers\Admin\KaryawanController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Karyawan\KarAgendaController;
use App\Http\Controllers\Karyawan\KarDailyReportController;
use App\Http\Controllers\Karyawan\KarDashController;
use App\Http\Controllers\Karyawan\KarPresensiController;
use App\Http\Controllers\Karyawan\KarProfileController;
use App\Http\Controllers\Karyawan\LogHarianController;
use Illuminate\Support\Facades\Route;


// auth
Route::middleware(['guest'])->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/login-proses', [LoginController::class, 'login'])->name('login.proses');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

//role
Route::middleware(['auth'])->group(function () {

    //role admin
    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

        Route::get('/dashboard', [AdDashController::class, 'index'])->name('dashboard');
        Route::resource('karyawan', KaryawanController::class);

        Route::get('/presensi', [AdPresensiController::class, 'index'])->name('presensi.index');
        Route::get('/presensi/rekap', [AdPresensiController::class, 'rekap'])->name('presensi.rekap');
        Route::get('/presensi/detail/{id}', [AdPresensiController::class, 'detail'])->name('presensi.detail');
        //Route::resource('presensi', AdPresensiController::class)->except(['show']);

        Route::get('/profile', [AdProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile/update', [AdProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/detail', [AdProfileController::class, 'detail'])->name('profile.detail');

        Route::get('/log-harian', [AdLogHarianController::class, 'index'])->name('log.index');

        Route::resource('agenda', AdAgendaController::class);

        Route::get('/report', [AdDailyReportController::class, 'index'])->name('report.index');

        Route::resource('jadwal', AdJadwalController::class);
        Route::resource('penetapan', AdJadwalPenetapanController::class);
       // Route::put('/penetapan', [AdJadwalPenetapanController::class, 'update'])->name('jadwal.penetapan.edit');
     //   Route::get('admin/penetapan', [AdJadwalController::class, 'index'])->name('admin.penetapan.index');
        Route::put('/profile/foto', [AdProfileController::class, 'updateFoto'])
        ->name('profile.update.foto');

    });

    //role karyawan
    Route::middleware(['role:karyawan'])
     ->prefix('karyawan')
        ->name('karyawan.')
        ->group(function () {

        Route::get('/dashboard', [KarDashController::class, 'index'])->name('dashboard');
        Route::get('/log-harian', [LogHarianController::class, 'index'])->name('log.index');
        Route::post('/log-harian', [LogHarianController::class, 'store'])->name('log.store');

        Route::get('/agenda', [KarAgendaController::class, 'index'])->name('agenda.index');
        Route::get('/agenda/by-date', [KarAgendaController::class, 'getAgendaByDate'])->name('karyawan.agenda.date');

        Route::get('/profile', [KarProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile/update', [KarProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/detail', [KarProfileController::class, 'detail'])->name('profile.detail');
        // [BARU] Update Foto Profil Karyawan
        Route::put('/profile/foto', [KarProfileController::class, 'updateFoto'])->name('profile.update.foto');
        
        // [BARU] Hapus Foto Profil Karyawan
        Route::delete('/profile/foto', [KarProfileController::class, 'deleteFoto'])->name('profile.delete.foto');


        Route::get('/presensi', [KarPresensiController::class, 'index'])->name('presensi.index');

        Route::post('/presensi', [KarPresensiController::class, 'store'])->name('presensi.store');

        Route::get('/presensi/photo/{id}', [KarPresensiController::class, 'photo'])->name('presensi.photo');
        Route::get('presensi/riwayat/{presensi}', [KarPresensiController::class, 'show'])->name('presensi.riwayat');

        Route::resource('report', KarDailyReportController::class)->except(['create', 'show']);
        
        Route::put('/profile/foto', [KarProfileController::class, 'updateFoto'])
        ->name('profile.update.foto'); 
        // Nama otomatis menjadi: 'karyawan.' + 'profile.update.foto' = 'karyawan.profile.update.foto'

    });


});
