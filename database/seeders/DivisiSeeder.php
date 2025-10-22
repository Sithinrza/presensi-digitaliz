<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Divisi;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $divisis = [
            'Programmer',
            'Graphic Design',
            'Photo & Video',
            'UI/UX Designer',
            'Administrator',
            'Marketing & Partnership'
        ];
        foreach ($divisis as $divisi) {
            Divisi::create(['name' => $divisi]);
        }
    }
}
