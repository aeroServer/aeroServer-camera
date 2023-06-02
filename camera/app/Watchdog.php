<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\parameters;

class Watchdog extends Model
{
    use HasFactory;

    static protected $retryOffset = [
        'execution' => 1
    ];

    public static function watch(bool|array $result, string $text, bool $silent = false, int $timeOffset = 0)
    {
        if (is_array($result)) {
            $result = $result[0];
        }
        


        if (!$silent) {
            echo "$text : ".(($result) ? 'OK' : 'ERROR')."\n";
        }
        
        $wd = new self();
        $wd->date = (time()+$timeOffset);
        $wd->log = $text;
        $wd->state = ($result) ? 1 : 0 ;
        $wd->save();
        self::clean();
        
        if (self::needReboot($text)) {
            self::reboot();
        }
        
        return $result;
    }

    public static function execution($end)
    {
        self::watch($end, 'execution', (!$end));
        if ($end) {
            self::where('log', 'execution')->where('state', 0)->delete();
        }
    }

    public static function needReboot($log)
    {
        
        
        if (self::where('log', $log)->orderBy('date', 'desc')->count() < self::retry($log)) {
            return false;
        }

        if ($log == 'execution') {
            //dd(self::whereIn('log', [$log, 'Reboot'])->orderBy('date', 'desc')->limit(self::retry())->toSql());
        }

        $state = self::whereIn('log', [$log, 'Reboot'])->orderBy('date', 'desc')->limit(self::retry($log))->get()->sum('state');
        
        if ($state == 0) {
            echo self::retry($log, false)." occurence of \"$log\" in error need reboot.\n";
        }

        return ($state == 0);
    }

    public static function reboot()
    {
        echo "Rebooting...\n";
        self::watch(true, 'Reboot', false, 1);
        exit();
    }

    public static function clean()
    {
        self::where('date', '<', (time()-86400))->where('log', '!=', 'Reboot')->where('state', 1)->delete();
    }

    public static function retry($log = '', $wOffset = true)
    {
        if ($wOffset) {
            return (parameters::get('watchdog retry before reboot', 3)+((isset(self::$retryOffset[$log])) ? self::$retryOffset[$log] : 0));
        } else {
            return (parameters::get('watchdog retry before reboot', 3));
        }
        
    }
}
