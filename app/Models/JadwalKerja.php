<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKerja extends Model
{
    public function detailJadwals()
    {
        // Relasi: One-to-Many (Satu Jenis Jadwal Kerja punya banyak Detail Jadwal)
        return $this->hasMany(DetailJadwal::class, 'id_jadwal_kerja', 'id');
    }
}
