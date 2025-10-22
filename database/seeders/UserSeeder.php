<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil role admin yang sudah kita buat sebelumnya
        $adminRole = Role::where('name', 'admin')->first();

        // 2. Buat user admin
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'), // Ganti dengan password yang aman
        ]);

        // 3. Hubungkan user admin dengan role admin
        $adminUser->roles()->attach($adminRole);

        // Jika kamu butuh user karyawan untuk testing, tambahkan di sini
        $karyawanRole = Role::where('name', 'karyawan')->first();

        $karyawanUser = User::create([
            'name' => 'Karyawan',
            'email' => 'karyawan@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        $karyawanUser->roles()->attach($karyawanRole);
    }
}
