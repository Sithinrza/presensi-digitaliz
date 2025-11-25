<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailJadwal extends Model
{
     protected $table = 'detail_jadwals';
    protected $fillable = ['id_jadwal_kerja',
                            'hari',
                            'jam_masuk',
                            'jam_pulang',
                            'hari_kerja'];

    public function jadwalKerja()
    {
        return $this->belongsTo(JadwalKerja::class, 'id_jadwal_kerja');
    }
}
