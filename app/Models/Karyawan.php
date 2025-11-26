<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'foto_profil',
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
        return $this->belongsTo(User::class, 'user_id', 'id');
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
        return $this->belongsTo(Jabatan::class, 'jabatan_id', 'id');
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

    public function presensiKaryawans()
    {
        // foreign key di tabel presensi_karyawans adalah 'karyawan_id'
        return $this->hasMany(\App\Models\PresensiKaryawan::class, 'karyawan_id');
    }

    // 🚨 PERBAIKAN: Tambahkan relasi presensiHariIni() yang dibutuhkan Controller Admin
    public function presensiHariIni()
    {
        // Digunakan untuk eager loading data presensi spesifik (sesuai tanggal)
        return $this->hasOne(\App\Models\PresensiKaryawan::class, 'karyawan_id', 'id');
    }


    public function scopeIsKaryawan($query)
    {
        return $query->where('status_karyawan', 'Aktif');
    }

    /* * PERHATIAN: Relasi ini (jadwalKerja) dinonaktifkan sementara karena
     * logika Controller menggunakan jadwalKaryawan() untuk mengambil jadwal hari ini.
    */
    // public function jadwalKerja()
    // {
    //     return $this->belongsToMany(
    //         JadwalKerja::class,
    //         'jadwal_pegawai',
    //         'id_karyawan',
    //         'id_jadwal_kerja'
    //     );
    // }

    // Relasi ke Model perantara JadwalKaryawan
    public function jadwalKaryawan()
    {
        return $this->hasOne(JadwalKaryawan::class, 'id_karyawan', 'id');
    }


    public function agendas(): BelongsToMany
    {
        return $this->belongsToMany(Agenda::class, 'agenda_karyawan', 'karyawan_id', 'agenda_id');
    }
}
