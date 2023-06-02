<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\parameters;
use App\Camera\Device;

class Config extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'config {parameters} {value?}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->argument('parameters') == 'ALL') {
            foreach (parameters::getAll() as $key => $value) {
                $this->info("$key=$value");
            }
        } elseif ($this->argument('parameters') == 'device') {
            $this->info('Current device : '.parameters::get('device', 'raspistill'));
            $this->table(['id', 'device'], Device::list());
            $newDeviceId = intval($this->ask('New device [0-'.(count(Device::list())-1).']?'));
            if (isset(Device::list()[$newDeviceId])) {
                parameters::getUpdate('device', Device::list()[$newDeviceId]['device']);
            }
        } else {
            $pa = parameters::getUpdate($this->argument('parameters'), $this->argument('value'));
            if (is_null($pa)) {
                $this->error("Le paramétre : ".$this->argument('parameters')." n'existe pas ou est protégé.");
            } else {
                $this->info($this->argument('parameters').'='.$pa);
            }
        }

        return 0;
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
