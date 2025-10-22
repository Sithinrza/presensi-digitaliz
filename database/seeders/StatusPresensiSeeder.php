<?php

namespace Database\Seeders;

use App\Models\StatusPresensi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusPresensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuss= [
            'Tepat Waktu',
            'Terlambat Check-In',
            'Terlambat Check-Out',
            'Lupa Check-Out',
            'Tidak Hadir'
        ];

        foreach($statuss as $status) {
            StatusPresensi::create(['name' => $status]);
        }
    }
}
