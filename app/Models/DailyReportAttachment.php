<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyReportAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_report_id',
        'type',
        'url_or_path',
        'filename',
    ];

    // Relasi ke Laporan
    public function report(): BelongsTo
    {
        return $this->belongsTo(DailyReport::class);
    }
}
