<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\parameters;
use App\jpeg;
use Storage;

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

        $dest = "". __DIR__ ."/../../storage/app/tmp/tmp.jpg";
        if (!Storage::exists('tmp')) {
            Storage::makeDirectory('tmp');
        }
        $this->info($dest);
        $cmd = "libcamera-still -t 5000 -n -o $dest --autofocus-on-capture -q ".parameters::get('jpeg quality', 97)." --hdr 1";
        
        shell_exec($cmd);
        jpeg::postProccess('tmp/tmp.jpg');
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
