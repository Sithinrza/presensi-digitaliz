<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PresensiKaryawan extends Model
{
    use HasFactory;

    // Nama tabel (Pastikan ini sesuai migrasi)
    protected $table = 'presensi_karyawans';

    /**
     * Kolom yang boleh diisi (Mass Assignable).
     */
    protected $fillable = [
        'karyawan_id',
        'status_id',
        'tanggal',
        'waktu_ci',
        'latitude_ci',
        'longitude_ci',
        'waktu_co',
        'latitude_co',
        'longitude_co',
        // Tambahkan kolom lain jika ada, seperti foto_ci/co
    ];

    // --- RELASI ---

    /**
     * Relasi Many-to-One ke Karyawan.
     * (Satu presensi dimiliki oleh SATU karyawan).
     */
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }

    /**
     * Relasi Many-to-One ke StatusPresensi.
     * (Satu presensi memiliki SATU status).
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(StatusPresensi::class, 'status_id', 'id');
    }

    /**
     * Relasi One-to-Many ke LogHarian.
     * (Satu presensi punya BANYAK catatan log).
     */
    public function logHarians(): HasMany
    {
        // Foreign key di tabel log_harians adalah 'presensi_id'
        return $this->hasMany(LogHarian::class, 'presensi_id', 'id');
    }
}
