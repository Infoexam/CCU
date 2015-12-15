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
        \App\Console\Commands\Deploy::class,
        \App\Console\Commands\Sync\Account::class,
        \App\Console\Commands\Sync\Certificate::class,
        \App\Console\Commands\Sync\Department::class,
        \App\Console\Commands\Sync\Receipt::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sync:receipt')->dailyAt('02:00')->withoutOverlapping();
    }
}
