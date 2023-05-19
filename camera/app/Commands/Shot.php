<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Shot extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'shot';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'take a picture from camera';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cmd = "libcamera-still -t 5000 -n -o tmp.jpg --autofocus-on-capture -q 97 --hdr 1";
        shell_exec($cmd);
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
