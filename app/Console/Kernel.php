<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Deploy::class,
        Commands\Firewall::class,
        Commands\Sync\Account::class,
        Commands\Sync\Certificate::class,
        Commands\Sync\Department::class,
        Commands\Sync\Receipt::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sync:department')->dailyAt('02:00');
        $schedule->command('sync:account')->dailyAt('02:05');
        $schedule->command('sync:receipt')->dailyAt('02:15');

        if ($this->app->environment('production')) {
            $schedule->command('sync:certificate')->dailyAt('02:20');
            $schedule->command('backup:clean')->dailyAt('03:00');
            $schedule->command('backup:run')->dailyAt('03:30');
        }
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
