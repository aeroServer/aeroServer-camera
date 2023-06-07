<?php
namespace App\Camera;
use App\parameters;

class ov5647
{
	public static function get($file)
	{
		$cmd = "libcamera-still -t 5000 -n -o $file -q ".parameters::get('jpeg quality', 97);
        shell_exec($cmd);
	}

	public static function listResolution()
	{
		
	}
}
