<?php

namespace Vanguard\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('app:renew-flash-deal')->cron('*/5 * * * *');
        $schedule->command('app:re-sync-flash-deal-product')->cron('*/2 * * * *');
        $schedule->command('app:auto-flashdeal')->cron('*/3 * * * *');
        $schedule->command('app:create_flashdeal')->cron('*/30 * * * *');
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
