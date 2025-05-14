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
        
        // Запуск очистки временных логотипов каждый день в полночь
        $schedule->command('temp:clear-logos')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    
    /**
     * Массив команд, регистрируемых вручную.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\MigrateTemplates::class,
        \App\Console\Commands\SeedTemplateCategories::class,
        \App\Console\Commands\ClearTempLogos::class,
    ];
}
