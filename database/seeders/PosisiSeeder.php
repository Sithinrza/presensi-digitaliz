<?php

namespace Database\Seeders;

use App\Models\Posisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PosisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posisis = [
            'Front-End', 'Back-End', 'Wordpress Developer',
            'Project Officer', 'Security Analyst', 'UI/UX for Website',
            'UI/UX for Mobile App', 'Foto', 'Video', 'Streaming'
        ];
        foreach ($posisis as $posisi) {
            Posisi::create(['name' => $posisi]);
        }
    }
}
