<?php
namespace App;
use Storage;

/**
 * 
 */
class linuxExif
{
	
	public static function read($path)
	{
		$cmd = "exiftool ".Storage::path($path)." -json";
		$data = json_decode(shell_exec($cmd));
		return (is_array($data) && count($data) > 0) ? $data[0] : null;
	}

	public static function write($path, $tag, $value)
	{
		$cmd = "exiftool -overwrite_original -$tag=\"$value\" ".Storage::path($path);
		shell_exec($cmd);
		return self::read($path);
	}
}