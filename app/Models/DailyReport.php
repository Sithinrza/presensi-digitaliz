<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'title',
        'description',
        'report_date',
        'status',
    ];

    protected $casts = [
        'report_date' => 'datetime',
    ];

    // Relasi ke Karyawan (User)
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Relasi ke Lampiran
    public function attachments(): HasMany
    {
        return $this->hasMany(DailyReportAttachment::class);
    }
}
