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
        // 1. Cari User 'Karyawan' yang sudah dibuat oleh UserSeeder
        $adminUser = User::where('email', 'admin@gmail.com')->first();

        // 2. Jika user-nya ada, buat data kepegawaiannya
        if ($adminUser) {
            Karyawan::create([

                // --- Kolom Wajib (Foreign Keys) ---
                'user_id' => $adminUser->id,
                'agama_id' => 1,
                'jabatan_id' => 1,
                'divisi_id' => 1,
                'posisi_id' => 1,
                'pendidikan_terakhir_id' => 1,

                // --- Kolom Wajib Lainnya ---
                'nip' => 'K-001',
                'nama_lengkap' => $adminUser->name, // Ambil dari nama user
                'jenis_kelamin' => 'Laki-Laki',
                'tanggal_bergabung' => '2022-01-01',
                'status_karyawan' => 'Aktif',

                'foto_profil' => null,

                // --- Kolom Opsional (Boleh NULL) ---
                'alamat' => 'Alamat Dummy',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2000-01-01',
                'no_telepon' => '08123456789',
            ]);
        } else {
            // Pesan error jika UserSeeder belum jalan
            $this->command->error('User Admin (admin@gmail.com) tidak ditemukan. Pastikan UserSeeder sudah berjalan.');
        }
    }
}
