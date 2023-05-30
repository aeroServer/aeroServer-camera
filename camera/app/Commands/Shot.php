<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
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

        $dest = "". __DIR__ ."/../../storage/app/tmp/tmp.jpg";
        Storage::deleteDirectory('tmp');
        if (!Storage::exists('tmp')) {
            Storage::makeDirectory('tmp');
        }
        $this->info('Clean directory if necessary.');
        //clean::deleteOldFile('pictures', parameters::get('disk free for automatic clean', 1));
        $this->info('Get picture from camera');
        $cmd = "libcamera-still -t 5000 -n -o $dest --autofocus-on-capture -q ".parameters::get('jpeg quality', 97)." --hdr 1";
        shell_exec($cmd);
        $this->info('Post processing');
        jpeg::postProccess('tmp/tmp.jpg');
        $this->info('Save to database');
        $picture = picture::add('tmp/tmp.jpg');
        if (!is_null(parameters::get('api server url', null))) {
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
