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
        $schedule->command('app:sync-winner --chainId=97 --protocol=0xB95FB889f7B7045336040A06e3e804a55948C7F6')
            ->everyMinute();
//        $schedule->command('app:sync-winner --chainId=97 --protocol=0xB95FB889f7B7045336040A06e3e804a55948C7F6');
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
