<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            'Head Of Digitaliz',
            'Chief of Programmer',
            'Chief of Graphic Design',
            'Chief of Photo & Video',
            'Chief of UI/UX Designer',
            'Administrator',
            'Marketing & Partnership',
            'Programmer Team',
            'Photo & Video Team',
            'UI/UX Designer Team'
        ];
        foreach ($jabatans as $jabatan) {
            Jabatan::create(['name' => $jabatan]);
        }
    }
}
