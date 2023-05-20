<?php
namespace App;
use Storage;

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
            imagejpeg($GD, Storage::path($path), parameters::get('jpeg quality', 97));
        }
        
    }

    public static function rotate($GD, $degrees)
    {
        return imagerotate($GD, $degrees, 0);
    }
}