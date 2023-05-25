<?php
namespace App;
use Storage;
use App\linuxExif;

/**
 * 
 */
class jpeg
{
    public static function postProccess(String $path)
    {
        $GD = imagecreatefromjpeg(Storage::path($path));
        $postProccess = false;
        
        if (parameters::get('jpeg rotate', 0) !== 0) {
            $GD = self::rotate($GD, parameters::get('jpeg rotate', 0));
            $postProccess = true;
        }      

        if ($postProccess) {
            imagejpeg($GD, Storage::path($path.'-NOEXIF'), parameters::get('jpeg quality', 97));
            self::transferExif2File($path, $path.'-NOEXIF');
            Storage::copy($path.'-NOEXIF', $path);
            
        }
        
    }

    public static function rotate($GD, $degrees)
    {
        return imagerotate($GD, $degrees, 0);
    }

    public static function transferExif2File($srcfile, $destfile) 
    {   
        $srcData = linuxExif::read($srcfile);
        
        $transfertTag = ['Make', 'Model', 'ISO', 'DateTimeOriginal', 'SubjectDistance'];
        foreach ($transfertTag as $key => $tag) {
            linuxExif::write($destfile, $tag, $srcData->$tag);
        }
    }

    public static function addDateTime($GD, $date)
    {
        
        return self::drawRectangleInfo($GD, 0, 90, 100, 100);
    }

    public static function drawRectangleInfo($GD, $x1Percent, $y1Percent, $x2Percent, $y2Percent)
    {
        $x = imagesx($GD);
        $y = imagesy($GD);

        $x1 = self::percentToXY($x1Percent, $x);
        $y1 = self::percentToXY($y1Percent, $y);

        $x2 = self::percentToXY($x2Percent, $x);
        $y2 = self::percentToXY($y2Percent, $y);
        imagerectangle(
            $GD,
            $x1,
            $y1,
            $x2,
            $y2,
            imagecolorallocate($GD, 0,0,0)
        );
        return $GD;
    }

    public static function percentToXY($percent, $pixel)
    {
        return intval((($pixel/100)*$percent));
    }
}