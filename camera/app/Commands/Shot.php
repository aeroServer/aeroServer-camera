<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Camera\Device;
use App\parameters;
use App\jpeg;
use App\picture;
use App\apiServer;
use App\clean;

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

        
        Storage::deleteDirectory('tmp');
        if (!Storage::exists('tmp')) {
            Storage::makeDirectory('tmp');
        }
        $this->info('Clean directory if necessary.');
        
        clean::deleteOldFile('pictures');
        
        $this->info('Get picture from camera');
        device::get('tmp/tmp.jpg');
        
        $this->info('Post processing');
        jpeg::postProccess('tmp/tmp.jpg');
        $this->info('Save to database');
        $picture = picture::add('tmp/tmp.jpg');
        if (!is_null(parameters::get('api server url', '')) && parameters::get('api server url', '') !== '') {
            $this->info('Send picture to '.parameters::get('api server url', ''));
            apiServer::sendPicture($picture);
        }

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
