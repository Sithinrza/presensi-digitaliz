<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// Impor Command yang baru dibuat
use App\Console\Commands\CheckDailyAttendance;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Daftarkan Command Anda di sini
        CheckDailyAttendance::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Penjadwalan Tugas: Menandai Lupa CO (4) dan Tidak Hadir (5)
        $schedule->command('attendance:daily-check')
                 ->dailyAt('23:59')
                 ->timezone('Asia/Makassar'); // Gunakan zona waktu yang sesuai
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
