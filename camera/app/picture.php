<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;
use App\linuxExif;

class picture extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function add(String $path)
    {
        if (Storage::exists($path)) {
            $picture = new self();
            Storage::copy($path, $picture->filename);
            $picture->save();
            $picture->writeExif();
            $picture->postProcessJpeg();
            return $picture;
        }
    }

    public function getFilenameAttribute()
    {
        if (is_null($this->date)) {
            $this->date = time();
        }
           
        return '/pictures/'.date('Y', $this->date).'/'.date('m', $this->date).'/OUT_'.date('Y_m_d_His', $this->date).'.jpg';
    }

    public function writeExif()
    {
        linuxExif::write($this->filename, 'ProcessingSoftware', 'aeroServer-camera V0.9');
    }

    public function getCurlFileAttribute()
    {
        return curl_file_create(Storage::path($this->filename), 'image/jpeg', 'picture.jpg');
    }

    public function getGdAttribute()
    {
        return imagecreatefromjpeg(Storage::path($this->filename));
    }

    public function postProcessJpeg()
    {
        $GD = $this->gd;
        $postProcess = false;

        if (parameters::get('jpeg add Date', true)) {
            $GD = jpeg::addDateTime($GD, $this->date);
            $postProcess = true;
        }



        if ($postProccess) {
            imagejpeg($GD, Storage::path($this->filename.'-NOEXIF'), parameters::get('jpeg quality', 97));
            jpeg::transferExif2File($this->filename, $this->filename.'-NOEXIF');
            Storage::copy($this->filename.'-NOEXIF', $this->filename);
            Storage::delete($this->filename.'-NOEXIF');
        }
    }
}
