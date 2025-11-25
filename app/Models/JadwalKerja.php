<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKerja extends Model
{
     use HasFactory;

     protected $table = 'jadwal_kerjas';
    // Mass assignment
    protected $fillable = [
        'name',
        // tambahkan field lain yang boleh diisi massal, misal:
        // 'keterangan',
    ];
    public function detailJadwals()
    {
        return $this->hasMany(DetailJadwal::class, 'id_jadwal_kerja', 'id');
    }

}
