<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\LogHarian;
use App\Models\PresensiKaryawan;
use App\Models\StatusPresensi; // Perlu untuk mengambil ID status 'Hadir'
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LogHarianController extends Controller
{
    /**
     * Tampilkan daftar Log Aktivitas Harian (READ) dan form input.
     * Logika utama tetap sama.
     */
    // File: LogHarianController.php (Method index)

    public function index()
    {
        // ... (Kode setup awal tidak berubah) ...
        $karyawan = Auth::user()->karyawan;
        $today = Carbon::today()->toDateString();
        $logs = collect([]);

        // 1. CARI PRESENSI HARI INI
        $presensi = PresensiKaryawan::where('karyawan_id', $karyawan->id)
                                    ->where('tanggal', $today)
                                    ->first();

        // Tentukan apakah presensi valid (sudah CI dan bukan status Tidak Hadir/Lupa CO)
        // Asumsi: Presensi valid jika ada record dan status bukan 4 atau 5.
        $isPresensiValid = ($presensi && $presensi->status_presensi_id != 4 && $presensi->status_presensi_id != 5);

        if ($presensi) {
            // 2. AMBIL LOG HARI INI (Hanya jika record presensi ada)
            $logs = LogHarian::where('presensi_karyawan_id', $presensi->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
        }

        return view('karyawan.log.log', compact('logs', 'today', 'isPresensiValid'));
    }

    /**
     * Menyimpan catatan log harian baru (CREATE).
     * DENGAN LOGIKA BYPASS OTOMATIS MEMBUAT PRESENSI.
     */
    public function store(Request $request)
    {
        $request->validate([
            'catatan_log' => 'required|string',
        ]);

        $today = Carbon::today()->toDateString();
        $karyawan = Auth::user()->karyawan;

        // 1. CARI ATAU BUAT PRESENSI HARI INI
        $presensi = PresensiKaryawan::where('karyawan_id', $karyawan->id)
                                    ->where('tanggal', $today)
                                    ->first();

        if (!$presensi) {
            // --- LOGIKA BYPASS DIMULAI DI SINI ---

            // Cari ID Status Hadir (Asumsi kamu sudah seeder data ini)
            $statusHadir = StatusPresensi::where('name', 'Hadir')->first();

            if (!$statusHadir) {
                return back()->with('error', 'Status "Hadir" belum ada di database master.');
            }

            // BUAT ENTRI PRESENSI OTOMATIS (Waktu dan Lokasi diisi NULL/Dummy)
            $presensi = PresensiKaryawan::create([
                'karyawan_id' => $karyawan->id,
                'status_presensi_id' => $statusHadir->id, // Menggunakan ID status Hadir
                'tanggal' => $today,
                // Semua kolom CI/CO diisi NULL karena ini hanya BYPASS
                'waktu_ci' => Carbon::now()->toDateTimeString(), // Waktu sekarang
            ]);
            // --- LOGIKA BYPASS SELESAI ---
        }

        // 2. Simpan Log Harian seperti biasa
        LogHarian::create([
            'presensi_karyawan_id' => $presensi->id,
            'catatan_log' => $request->catatan_log,
        ]);

        return redirect()->route('karyawan.log.index')->with('success', 'Log aktivitas berhasil dicatat!');
    }
}
