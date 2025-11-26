<?php

use App\Http\Controllers\Admin\AdAgendaController;
use App\Http\Controllers\Admin\AdDailyReportController;
use App\Http\Controllers\Admin\AdDashController;
use App\Http\Controllers\Admin\AdJadwalController;
use App\Http\Controllers\Admin\AdJadwalPenetapanController;
use App\Http\Controllers\Admin\AdLogHarianController;
use App\Http\Controllers\Admin\AdPresensiController;
use App\Http\Controllers\Admin\AdProfileController;
use App\Http\Controllers\Admin\AdReportController;
use App\Http\Controllers\Admin\KaryawanController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Karyawan\KarAgendaController;
use App\Http\Controllers\Karyawan\KarDailyReportController;
use App\Http\Controllers\Karyawan\KarDashController;
use App\Http\Controllers\Karyawan\KarJadwalController;
use App\Http\Controllers\Karyawan\KarPresensiController;
use App\Http\Controllers\Karyawan\KarProfileController;
use App\Http\Controllers\Karyawan\KarReportController;
use App\Http\Controllers\Karyawan\LogAkController;
use App\Http\Controllers\Karyawan\LogHarianController;
use App\Http\Controllers\WebcamController;
use App\Models\Agenda;
use Illuminate\Support\Facades\Route;


// auth
Route::middleware(['guest'])->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/login-proses', [LoginController::class, 'login'])->name('login.proses');
});
Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('/logoutt', [LoginController::class, 'logoutt'])->name('logoutt')->middleware('auth');

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


        Route::get('/presensi', [KarPresensiController::class, 'index'])->name('presensi.index');

        Route::post('/presensi', [KarPresensiController::class, 'store'])->name('presensi.store');

        Route::get('/presensi/photo/{id}', [KarPresensiController::class, 'photo'])->name('presensi.photo');

        Route::resource('report', KarDailyReportController::class)->except(['create', 'show']);

    });


});
