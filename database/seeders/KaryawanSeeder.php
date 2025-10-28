<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- 1. DATA ADMIN (User ID 1) ---
        $adminUser = User::where('email', 'admin@gmail.com')->first();

        if ($adminUser) {
            Karyawan::create([
                // --- Kolom Wajib (Foreign Keys) ---
                'user_id' => $adminUser->id, // User ID Admin
                'agama_id' => 1,
                'jabatan_id' => 1,
                'divisi_id' => 1,
                'posisi_id' => 1,
                'pendidikan_terakhir_id' => 1,

                // --- Kolom Wajib Lainnya ---
                'nip' => 'A-001', // Ganti NIP Admin agar berbeda
                'nama_lengkap' => $adminUser->name,
                'jenis_kelamin' => 'Laki-Laki',
                'tanggal_bergabung' => '2021-01-01',
                'status_karyawan' => 'Aktif',

                // Kolom Opsional
                'alamat' => 'Alamat Admin',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1995-01-01',
                'no_telepon' => '08111111111',
            ]);
        } else {
            $this->command->error('User Admin tidak ditemukan.');
        }


        // --- 2. DATA KARYAWAN (User ID 2) ---
        $karyawanUser = User::where('email', 'karyawan@gmail.com')->first();

        if ($karyawanUser) {
            Karyawan::create([
                // --- Kolom Wajib (Foreign Keys) ---
                'user_id' => $karyawanUser->id, // User ID Karyawan
                'agama_id' => 1,
                'jabatan_id' => 3, // Ganti jabatan untuk karyawan
                'divisi_id' => 2,
                'posisi_id' => 2,
                'pendidikan_terakhir_id' => 1,

                // --- Kolom Wajib Lainnya ---
                'nip' => 'K-001', // NIP Karyawan (ini yang akan dicari di PresensiSeeder)
                'nama_lengkap' => $karyawanUser->name,
                'jenis_kelamin' => 'Perempuan',
                'tanggal_bergabung' => '2022-01-01',
                'status_karyawan' => 'Aktif',

                // Kolom Opsional
                'alamat' => 'Alamat Karyawan Dummy',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2000-01-01',
                'no_telepon' => '08123456789',
            ]);
        } else {
            $this->command->error('User Karyawan tidak ditemukan.');
        }
    }
}
