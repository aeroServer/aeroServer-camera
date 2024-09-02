<?php

namespace App\Commands;

use App\apiServer;
use App\Camera\Device;
use App\clean;
use App\parameters;
use App\picture;
use App\Watchdog;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
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

        Watchdog::execution(false);
        //Storage::deleteDirectory('tmp');

        if (!is_dir($_SERVER['HOME'] . '/.config/aeroServer-camera')) {
            mkdir($_SERVER['HOME'] . '/.config/aeroServer-camera');
        }

        if (!Storage::exists('tmp')) {
            Storage::makeDirectory('tmp');
        }

        if (!Storage::exists('pictures')) {
            Storage::makeDirectory('pictures');
        }

        $this->info('Clean directory if necessary.');

        clean::deleteOldFile('pictures');

        Watchdog::watch(device::get('tmp/tmp.jpg'), 'Get picture from camera');
        //Watchdog::watch(jpeg::postProccess('tmp/tmp.jpg'), 'Post processing');

        $picture = picture::add('tmp/tmp.jpg');
        Watchdog::watch($picture, 'Save to database');

        if (!is_null(parameters::get('api server url', '')) && parameters::get('api server url', '') !== '') {
            Watchdog::watch(apiServer::sendPicture($picture[1]), 'Send picture to ' . parameters::get('api server url', ''));
        }
        Watchdog::execution(true);
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
