<?php
namespace App\Camera;
use App\parameters;

class v4l
{
	public static function get($file)
	{
		$cmd = "ffmpeg -f v4l2 -video_size 4656x3496 -i /dev/video0 -frames 1 $file";
        shell_exec($cmd);
	}

	public static function listResolution()
	{

	}
}