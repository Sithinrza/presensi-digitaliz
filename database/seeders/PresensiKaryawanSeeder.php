<?php

namespace Database\Seeders;

use App\Models\PresensiKaryawan;
use App\Models\Karyawan;
use App\Models\StatusPresensi;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PresensiKaryawanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cari Karyawan
        $karyawan = Karyawan::where('nip', 'K-001')->first();

        // 2. Cari Status Hadir
        $statusHadir = StatusPresensi::where('name', 'Tepat Waktu')->first();

        // Cek jika data master belum ada
        if (!$karyawan || !$statusHadir) {
            $this->command->error('Gagal membuat Presensi. Data Karyawan (K-001) atau Status (Hadir) tidak ditemukan.');
            return;
        }

        $now = \Carbon\Carbon::now();

        // 3. Buat Data Presensi
        PresensiKaryawan::create([
            'karyawan_id' => $karyawan->id,
            'status_presensi_id' => $statusHadir->id,

            // Kolom NOT NULL lainnya
           'tanggal' => $now->toDateString(),
            'waktu_ci' => $now->toDateTimeString(),
            'latitude_ci' => '0.00000000',
            'longitude_ci' => '0.00000000',
            // Kolom waktu_co, foto_co, dll. BISA NULL.
        ]);
    }
}
