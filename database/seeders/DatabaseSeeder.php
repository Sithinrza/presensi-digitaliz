<?php

namespace Database\Seeders;

use App\Models\StatusPresensi;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            AgamaSeeder::class,
            PendidikanTerakhirSeeder::class,

            DivisiSeeder::class,
            JabatanSeeder::class,
            PosisiSeeder::class,
            StatusPresensiSeeder::class,


            KaryawanSeeder::class

        ]);
    }
}
