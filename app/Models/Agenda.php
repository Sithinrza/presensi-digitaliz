<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Agenda extends Model
{
    public function karyawans(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Karyawan::class, 'agenda_karyawan', 'agenda_id', 'karyawan_id');
    }
}
