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

        // ให้ระบบตรวจสอบสต็อกต่ำทุกวันตอน 9 โมงเช้า
        // คุณสามารถเปลี่ยนเวลาได้ตามต้องการ เช่น ->everyMinute() สำหรับการทดสอบ
        $schedule->command('app:check-low-stock')->dailyAt('09:00');
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