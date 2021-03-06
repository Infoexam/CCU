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
        Commands\Cachet\Working::class,
        Commands\Deploy::class,
        Commands\Firewall::class,
        Commands\Judge::class,
        Commands\JudgeSession::class,
        Commands\SubresourceIntegrity::class,
        Commands\Sync\Account::class,
        Commands\Sync\Certificate::class,
        Commands\Sync\Department::class,
        Commands\Sync\Password::class,
        Commands\Sync\Receipt::class,
        Commands\ImportOldData::class,
        Commands\UpdatePassedStatus::class,
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
        $schedule->command('judge')->dailyAt('02:30');

        if ($this->app->environment('production')) {
            $schedule->command('sync:certificate')->dailyAt('02:20');
            $schedule->command('backup:clean')->dailyAt('03:00');
            $schedule->command('backup:run')->dailyAt('03:30');
            $schedule->command('cachet:working')->everyFiveMinutes();
        }
    }
}
