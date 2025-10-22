<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPresensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function presensiKaryawan()
    {
        return $this->hasMany(PresensiKaryawan::class);
    }
}
