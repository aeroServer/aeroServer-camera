<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Selfupdate extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'selfupdate';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Self Update command';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $baseFolder = dirname(dirname(dirname(__FILE__)));
        $cdCmd = "cd $baseFolder";

        $cmd = "ls";
        $this->localShell($cmd);
        echo $cdCmd."\n";
        echo shell_exec('echo $PWD');
    }


    private function localShell($cmd)
    {
        $baseFolder = dirname(dirname(dirname(__FILE__)));
        $cdCmd = "cd $baseFolder";
        echo shell_exec("$cdCmd && $cmd");
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
