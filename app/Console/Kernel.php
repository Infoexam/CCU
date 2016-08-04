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
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('down')->dailyAt('02:00');

        $schedule->command('sync:department')->monthly()->at('02:05')->evenInMaintenanceMode()->withoutOverlapping();
        $schedule->command('sync:account')->dailyAt('02:10')->evenInMaintenanceMode()->withoutOverlapping();
        $schedule->command('sync:receipt')->dailyAt('02:30')->evenInMaintenanceMode()->withoutOverlapping();
        $schedule->command('sync:certificate')->dailyAt('02:40')->evenInMaintenanceMode()->withoutOverlapping();

        $schedule->command('up')->dailyAt('03:00');
    }
}
