<?php

namespace Database\Seeders;

use App\Models\PendidikanTerakhir;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PendidikanTerakhirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pendidikans = [
            'SMA/SMK',
            'Diploma 1',
            'Diploma 2',
            'Diploma 3',
            'Diploma 4',
            'Sarjana',
            'Magister',
            'Doktor'
        ];
        foreach ($pendidikans as $pendidikan) {
            PendidikanTerakhir::create(['name' => $pendidikan]);
        }
    }
}
