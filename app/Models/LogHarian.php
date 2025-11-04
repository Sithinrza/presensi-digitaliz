<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogHarian extends Model
{
    use HasFactory;

    // Nama tabel (Opsional, tapi bagus untuk eksplisit)
    protected $table = 'log_harians';

    /**
     * Kolom yang boleh diisi (WAJIB untuk Log::create()).
     */
    protected $fillable = [
        'presensi_karyawan_id',
        'catatan_log',
    ];

    // --- RELASI ---

    /**
     * Relasi Many-to-One ke PresensiKaryawan.
     * Satu log harian dimiliki oleh SATU entri presensi harian.
     */
    public function presensi(): BelongsTo
    {
        // Menggunakan foreign key 'id_presensi' di tabel log_harians
        // yang mereferensi 'id' di tabel presensi_karyawans
        return $this->belongsTo(PresensiKaryawan::class, 'presensi_karyawan_id', 'id');
    }
}
