<?php
namespace App;
use Illuminate\Support\Facades\App;
use Storage;
use App\linuxExif;

/**
 * 
 */
class jpeg
{
    public static function rotate($GD, $degrees)
    {
        return imagerotate($GD, $degrees, 0);
    }

    public static function transferExif2File($srcfile, $destfile) 
    {   
        $srcData = linuxExif::read($srcfile);
        
        $transfertTag = ['Make', 'Model', 'ISO', 'DateTimeOriginal', 'SubjectDistance'];
        foreach ($transfertTag as $key => $tag) {
            if (isset($srcData->$tag)) {
                linuxExif::write($destfile, $tag, $srcData->$tag);
            }
        }
    }

    public static function addDateTimeLoc($GD, $date)
    {
        $GD = self::drawRectangleInfo($GD, 0, 98, 100, 100);
        $GD = self::drawText($GD, 'Date : '.date('d/m/Y H:i:s e', $date).' - '.parameters::get('place', 'generic'), 1, 99.5);
        return $GD;
    }

    public static function addApplicationInfo($GD)
    {
        $GD = self::drawText($GD, config('app.name').' '.config('app.version'), 80, 99.5);
        return $GD;
    }

    public static function drawRectangleInfo($GD, $x1Percent, $y1Percent, $x2Percent, $y2Percent)
    {
        $x = imagesx($GD);
        $y = imagesy($GD);

        $x1 = self::percentToXY($x1Percent, $x);
        $y1 = self::percentToXY($y1Percent, $y);

        $x2 = self::percentToXY($x2Percent, $x);
        $y2 = self::percentToXY($y2Percent, $y);
        imagefilledrectangle(
            $GD,
            $x1,
            $y1,
            $x2,
            $y2,
            imagecolorallocate($GD, 0,0,0)
        );
        return $GD;
    }

    public static function drawText($GD, $text, $xT, $yT)
    {

        \imagefttext(
            $GD,
            32,
            0,
            self::percentToXY($xT, imagesx($GD)),
            self::percentToXY($yT, imagesy($GD)),
            imagecolorallocate($GD, 255,255,255),
            self::getFontsPath(parameters::get('jpeg ttf font', 'JetBrainsMono/JetBrainsMono-Bold.ttf')),
            $text
        );

        return $GD;
    }

    public static function percentToXY($percent, $pixel)
    {
        return intval((($pixel/100)*$percent));
    }

    public static function getFontsPath($font)
    {
        return Storage::disk('fonts')->path($font);
    }
}

// JetBrainsMono