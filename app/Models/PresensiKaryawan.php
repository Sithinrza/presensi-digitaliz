<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiKaryawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }

    public function status()
    {
        return $this->belongsTo(StatusPresensi::class);
    }
}
