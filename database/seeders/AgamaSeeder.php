<?php

namespace Database\Seeders;

use App\Models\Agama;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AgamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agamas = [
            'Islam',
            'Kristen Protestan',
            'Katolik',
            'Buddha',
            'Hindu',
            'Konghucu'
        ];
        foreach ($agamas as $agama) {
            Agama::create(['name' => $agama]);
        }
    }
}
