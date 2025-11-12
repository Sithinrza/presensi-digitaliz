<?php

namespace App\Console\Commands;

use App\Models\PresensiKaryawan;
use App\Models\User; // Asumsi tabel users digunakan untuk daftar karyawan
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckDailyAttendance extends Command
{
    protected $signature = 'attendance:daily-check';
    protected $description = 'Checks attendance for yesterday and marks Lupa CO (4) or Tidak Hadir (5).';

    public function handle()
    {
        $yesterday = Carbon::yesterday()->toDateString();

        // --- 1. PROSES LUPA CHECK-OUT (ID 4) ---
        // Cari presensi kemarin yang sudah CI (waktu_ci != NULL) tapi belum CO (waktu_co = NULL)
        $lupaCoCount = PresensiKaryawan::where('tanggal', $yesterday)
            ->whereNotNull('waktu_ci')
            ->whereNull('waktu_co')
            ->update(['status_presensi_id' => 4]); // ID 4: Lupa Check-Out

        $this->info("✅ {$lupaCoCount} karyawan ditandai Lupa Check-Out (ID 4) untuk tanggal {$yesterday}.");

        // --- 2. PROSES TIDAK HADIR (ID 5) ---
        // Logika ini lebih kompleks: Membandingkan semua karyawan dengan data presensi

        $allKaryawanIds = User::pluck('id'); // Ambil semua ID Karyawan (Ganti User::class jika tabel karyawan berbeda)

        // Ambil semua ID Karyawan yang sudah presensi kemarin
        $hadirIds = PresensiKaryawan::where('tanggal', $yesterday)->pluck('karyawan_id');

        // Tentukan siapa yang TIDAK HADIR (ID 5)
        $tidakHadirIds = $allKaryawanIds->diff($hadirIds);

        $this->info("🔄 Menandai {$tidakHadirIds->count()} karyawan Tidak Hadir (ID 5).");

        foreach ($tidakHadirIds as $karyawanId) {
             // Buat entri baru untuk menandai sebagai TIDAK HADIR (ID 5)
             PresensiKaryawan::create([
                 'karyawan_id' => $karyawanId,
                 'status_presensi_id' => 5, // ID 5: Tidak Hadir
                 'tanggal' => $yesterday,
                 // Sisanya biarkan NULL karena memang tidak ada data CI/CO
             ]);
        }

        $this->info("🎉 Proses pengecekan harian selesai.");
        return 0;
    }
}
