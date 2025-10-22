<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $fillable = [
        'user_id',
        'agama_id',
        'jabatan_id',
        'divisi_id',
        'posisi_id',
        'pendidikan_terakhir_id',
        'nip',
        'nama_lengkap',
        'alamat',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'no_telepon',
        'tanggal_bergabung',
        'status_karyawan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_bergabung' => 'date',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Setiap Karyawan bekerja di satu Divisi.
     */
    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    /**
     * Setiap Karyawan memiliki satu Jabatan.
     */
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    /**
     * Setiap Karyawan memegang satu Posisi/Keahlian.
     */
    public function posisi()
    {
        return $this->belongsTo(Posisi::class);
    }

    /**
     * Setiap Karyawan memiliki satu Agama.
     */
    public function agama()
    {
        return $this->belongsTo(Agama::class);
    }

    /**
     * Setiap Karyawan memiliki satu Pendidikan Terakhir.
     */
    public function pendidikanTerakhir()
    {
        return $this->belongsTo(PendidikanTerakhir::class);
    }

    public function presensiKaryawan()
    {
        return $this->belongsTo(PresensiKaryawan::class);
    }

    public function statusPresensi()
    {
        return $this->belongsTo(StatusPresensi::class);
    }

    public function jadwalKerja()
{
    return $this->belongsToMany(
        JadwalKerja::class,
        'jadwal_pegawai',  
        'id_karyawan',
        'id_jadwal_kerja'
    );
}
}
