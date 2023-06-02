<?php
namespace App\Camera;
use App\parameters;

class raspistill
{
	public static function get($file)
	{
		$dest = "". __DIR__ ."/../../storage/app/$file";
		$cmd = "libcamera-still -t 5000 -n -o $dest --autofocus-on-capture -q ".parameters::get('jpeg quality', 97)." --hdr 1";
        shell_exec($cmd);
	}

	public static function listResolution()
	{
		
	}
}