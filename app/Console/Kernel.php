<?php

namespace App\Console;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        // Chạy vào 5h sáng mỗi ngày
        // $schedule->command('scheduled-task')->cron('0 5 * * * ');

        //  $schedule->command('scheduled-cron')->everyMinute();
        // $schedule->command('scheduled-cron')->hourly();

        // time_cron


        $cacheValue = Cache::get('time_cron');
        Log::info('update cron: ' . $cacheValue);
        $schedule->command('scheduled-cron')->dailyAt($cacheValue)->timezone('Asia/Ho_Chi_Minh');
    }
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
