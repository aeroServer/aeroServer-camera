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
}