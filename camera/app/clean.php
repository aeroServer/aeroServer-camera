<?php
namespace App;
use Storage;

/**
 * 
 */
class clean
{
	public static function deleteOldFile($directory, $percentFree = 10)
	{
		$totalFree = self::freeSpace($directory);
	}

	public static function freeSpace($directory)
	{
		$result = disk_free_space(Storage::path($directory));
		
		dd($result);
	}
}