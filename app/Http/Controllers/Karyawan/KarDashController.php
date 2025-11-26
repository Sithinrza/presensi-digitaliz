<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
// Model yang relevan
use App\Models\Agenda;
use App\Models\PresensiKaryawan;
use App\Models\Karyawan;
use App\Models\LogHarian;
use App\Models\DailyReport;

class KarDashController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $todayDate = Carbon::now()->toDateString();
        $isWorkingDay = true; // Asumsi default hari kerja, atau tentukan di logika jadwal

        // 🚨 PENTING: Ambil ID Karyawan yang sesuai dengan user login
        $karyawan = Karyawan::where('user_id', $userId)->first();
        $karyawanId = $karyawan->id ?? null; // ID dari tabel karyawans (Primary Key)

        // --- 1. Ambil Presensi Hari Ini (Dibutuhkan View untuk status CI/CO) ---
        $presensiHariIni = PresensiKaryawan::where('karyawan_id', $karyawanId)
            ->where('tanggal', $todayDate)
            ->first();

        // 🚨 Tentukan $isWorkingDay (Tambahkan logika penentuan hari kerja di sini)
        $isWorkingDay = true; // Ganti dengan logika yang benar dari jadwal karyawan jika ada.
        // Jika Anda memiliki logic jadwal yang lebih kompleks (seperti di KarPresensiController), masukkan di sini.


        // --- 2. Ambil Agenda Hari Ini ---
        $agendaHariIni = collect();

        if ($karyawan) {
             $agendaHariIni = $karyawan->agendas()
                ->whereDate('agendas.tanggal_agenda', $todayDate)
                ->latest()
                ->get();
        }

        // --- 3. Ambil Log Aktivitas Hari Ini ---
        $logAktivitasHariIni = collect();

        if ($presensiHariIni) {
            $logAktivitasHariIni = LogHarian::where('presensi_karyawan_id', $presensiHariIni->id)
                ->latest()
                ->take(5)
                ->get();
        }

        // --- 4. Ambil Daily Report Hari Ini ---
        $dailyReportHariIni = DailyReport::where('employee_id', $karyawanId)
            ->whereDate('report_date', $todayDate)
            ->latest()
            ->first();


        // Kirim semua data ke view
        return view('karyawan.dashboard', compact(
            'agendaHariIni',
            'logAktivitasHariIni',
            'dailyReportHariIni',
            'presensiHariIni', // 🚨 KIRIM VARIABEL INI
            'isWorkingDay',     // 🚨 KIRIM VARIABEL INI
            'todayDate'         // 🚨 KIRIM VARIABEL INI
        ));
    }
}
