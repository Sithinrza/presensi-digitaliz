<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKaryawan extends Model
{
    public function jadwalKerja()
    {
        // Relasi: Many-to-One (Banyak Jadwal Karyawan terhubung ke Satu Jenis Jadwal Kerja)
        return $this->belongsTo(JadwalKerja::class, 'id_jadwal_kerja', 'id');
    }
}
