<?php

namespace App\Console;

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
        $schedule->command('app:sync-config')->everyTenMinutes();
        $schedule->command('app:sync-user-balance')->everyTenMinutes();
        $schedule->command('app:sync-history-referrer')->everyTenMinutes();
        $schedule->command('app:sync-history-reward-stake')->everyTenMinutes();
        $schedule->command('app:sync-proposal')->everyTenMinutes();
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
