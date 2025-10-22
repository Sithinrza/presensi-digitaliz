<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Get all of the karyawans for the PendidikanTerakhir
     */
    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }
}
