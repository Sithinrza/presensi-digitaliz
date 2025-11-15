<?php

namespace App\Console\Commands;

use App\Models\PresensiKaryawan;
use App\Models\User; // Asumsi tabel users digunakan untuk daftar karyawan
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckDailyAttendance extends Command
{
    protected $signature = 'attendance:daily-check';
    protected $description = 'Checks attendance for yesterday and marks Lupa CO (4) or Tidak Hadir (5).';

    public function handle()
    {
        $yesterday = Carbon::yesterday()->toDateString();
        // Gunakan locale('id') untuk mencocokkan string hari di database (misal: "Jumat")
        $yesterdayName = Carbon::yesterday()->locale('id')->isoFormat('dddd');

        $this->info("Starting automatic presensi update for: {$yesterdayName}, {$yesterday}");

        // --- 1. PROSES LUPA CHECK-OUT (ID 4) (Logic Anda sudah benar) ---
        $lupaCoCount = PresensiKaryawan::where('tanggal', $yesterday)
            ->whereNotNull('waktu_ci')
            ->whereNull('waktu_co')
            ->update(['status_presensi_id' => 4]);

        $this->info("✅ {$lupaCoCount} karyawan ditandai Lupa Check-Out (ID 4).");

    // --- 2. PROSES TIDAK HADIR (ID 5) (LOGIC BARU) ---

        // A. Ambil ID semua karyawan yang seharusnya Bekerja KEMARIN (berdasarkan jadwal)
        $workingKaryawanIds = DB::table('jadwal_karyawans as jk')
            ->join('detail_jadwals as dj', 'jk.id_jadwal_kerja', '=', 'dj.id_jadwal_kerja')
            ->where('dj.hari', $yesterdayName)
            ->where('dj.hari_kerja', 1)
            ->pluck('jk.id_karyawan')
            ->unique()
            ->toArray(); // Mendapatkan ID karyawan yang wajib kerja

        // B. Ambil ID karyawan yang SUDAH memiliki record presensi (CI/CO) kemarin
        $hadirKaryawanIds = PresensiKaryawan::where('tanggal', $yesterday)
            ->pluck('karyawan_id')
            ->toArray();

        // C. Tentukan siapa yang TIDAK HADIR (ada di daftar kerja tapi tidak ada record presensi)
        $tidakHadirIds = array_diff($workingKaryawanIds, $hadirKaryawanIds);
        $absentCount = 0;

        $this->info("🔄 Menandai " . count($tidakHadirIds) . " karyawan Tidak Hadir (ID 5).");

        foreach ($tidakHadirIds as $karyawanId) {
            // Buat entri baru untuk menandai sebagai TIDAK HADIR (ID 5)
            PresensiKaryawan::create([
                'karyawan_id' => $karyawanId,
                'status_presensi_id' => 5, // ID 5: Tidak Hadir
                'tanggal' => $yesterday,
            ]);
            $absentCount++;
        }

        $this->info("🎉 Proses pengecekan harian selesai. Inserted {$absentCount} records for ID 5.");
        return 0;
    }
}
