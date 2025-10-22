<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendidikanTerakhir extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }
}
