<?php

namespace App\Console;

use App\Console\Commands\GenerateSitemap;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel
 *               _       _                         __  ___
 *    /\  /\__ _| |_ ___| |__   ___ _ __ _   _  /\ \ \/ _ \
 *   / /_/ / _` | __/ __| '_ \ / _ \ '__| | | |/  \/ / /_\/
 *  / __  / (_| | || (__| | | |  __/ |  | |_| / /\  / /_\\
 *  \/ /_/ \__,_|\__\___|_| |_|\___|_|   \__, \_\ \/\____/
 *             badge.team                |___/
 *
 * @package App\Console
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GenerateSitemap::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     * @codeCoverageIgnore
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sitemap:generate')->hourly();
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
