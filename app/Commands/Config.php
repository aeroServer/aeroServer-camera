<?php

namespace App\Commands;

use App\Camera\Device;
use App\parameters;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

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
    protected $description = 'List, Get or Set parameters ';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->argument('parameters') == 'all') {
            $this->displayAllParameters();

        } elseif ($this->argument('parameters') == 'device') {
            $this->setDevice();
        } else {
            $pa = parameters::getUpdate($this->argument('parameters'), $this->argument('value'));
            if (is_null($pa)) {
                $this->error("Le paramétre : " . $this->argument('parameters') . " n'existe pas ou est protégé.");
            } else {
                $this->info($this->argument('parameters') . '=' . $pa);
            }
        }

        return 0;
    }

    private function displayAllParameters()
    {
        $this->table(['key', 'value'], array_map(fn($x, $y) => ['key' => $x, 'value' => $y], array_keys(parameters::getAll()), array_values(parameters::getAll())));
    }

    private function setDevice()
    {
        $this->info('Current device : ' . parameters::get('device', 'raspistill'));
        $this->table(['id', 'device'], Device::list());
        $newDeviceId = intval($this->ask('New device [0-' . (count(Device::list()) - 1) . ']?'));
        if (isset(Device::list()[$newDeviceId])) {
            parameters::getUpdate('device', Device::list()[$newDeviceId]['device']);
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
