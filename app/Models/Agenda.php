<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Agenda extends Model
{

    use HasFactory;

    protected $table = 'agendas';

    protected $fillable = [
        'judul',
        'tanggal_agenda',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi_alamat',
        'ruang',
        'catatan',
        // Tambahkan kolom lain jika ada, misal 'id_pembuat'
    ];
    public function karyawans(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Karyawan::class, 'agenda_karyawan', 'agenda_id', 'karyawan_id');
    }
}
