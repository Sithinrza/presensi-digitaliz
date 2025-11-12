<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $karyawanId = 2;
        $currentTime = Carbon::now();

        // 1. Tambahkan data ke tabel jadwal_kerjas (Tipe Jadwal)
        DB::table('jadwal_kerjas')->insert([
            'id' => 1,
            'name' => 'Shift Reguler (08:30 - 16:30)',
            'created_at' => $currentTime,
            'updated_at' => $currentTime,
        ]);

        // 2. Tambahkan data ke tabel detail_jadwals (Detail Jam Kerja per Hari)
        $detailJadwalData = [
            // Senin sampai Jumat (08:30 - 16:30)
            ['id_jadwal_kerja' => 1, 'hari' => 'Senin', 'jam_masuk' => '08:30:00', 'jam_pulang' => '16:30:00', 'hari_kerja' => 1],
            ['id_jadwal_kerja' => 1, 'hari' => 'Selasa', 'jam_masuk' => '08:30:00', 'jam_pulang' => '16:30:00', 'hari_kerja' => 1],
            ['id_jadwal_kerja' => 1, 'hari' => 'Rabu', 'jam_masuk' => '08:30:00', 'jam_pulang' => '16:30:00', 'hari_kerja' => 1],
            ['id_jadwal_kerja' => 1, 'hari' => 'Kamis', 'jam_masuk' => '08:30:00', 'jam_pulang' => '16:30:00', 'hari_kerja' => 1],
            ['id_jadwal_kerja' => 1, 'hari' => 'Jumat', 'jam_masuk' => '08:30:00', 'jam_pulang' => '16:30:00', 'hari_kerja' => 1],

            // Sabtu dan Minggu (Hari Libur, hari_kerja = 0)
            ['id_jadwal_kerja' => 1, 'hari' => 'Sabtu', 'jam_masuk' => null, 'jam_pulang' => null, 'hari_kerja' => 0],

        ];

        foreach ($detailJadwalData as $data) {
            DB::table('detail_jadwals')->insert(array_merge($data, [
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ]));
        }

        DB::table('jadwal_karyawans')->insert([
            'id_karyawan' => $karyawanId,
            'id_jadwal_kerja' => 1,
            'created_at' => $currentTime,
            'updated_at' => $currentTime,
        ]);
    }
}
