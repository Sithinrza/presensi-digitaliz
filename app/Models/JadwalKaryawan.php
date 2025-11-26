<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKaryawan extends Model
{

    protected $primaryKey = 'id_karyawan';
    public $incrementing = false;

    protected $fillable = ['id_karyawan', 'id_jadwal_kerja'];

    public function karyawan()
    {
        // PENTING: Menggunakan foreign key dan local key secara eksplisit
        // karena nama kolom Anda adalah 'id_karyawan' (non-konvensi Laravel)
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id');
    }

    public function jadwalKerja()
    {
        // Menggunakan foreign key dan local key secara eksplisit
        return $this->belongsTo(JadwalKerja::class, 'id_jadwal_kerja', 'id');
    }
}
