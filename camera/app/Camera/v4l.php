<?php
namespace App\Camera;
use App\parameters;

class v4l
{
	public static function get($file)
	{
		$dest = "". __DIR__ ."/../../storage/app/$file";
		//$cmd = "libcamera-still -t 5000 -n -o $dest --autofocus-on-capture -q ".parameters::get('jpeg quality', 97)." --hdr 1";
		$cmd = "ffmpeg -f v4l2 -video_size 4656x3496 -i /dev/video0 -frames 1 $dest";
        shell_exec($cmd);
	}

	public static function listResolution()
	{

	}
}