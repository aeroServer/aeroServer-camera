<?php
namespace App;
use Storage;
use App\picture;

/**
 * 
 */
class clean
{
	protected static $b2Mb = 1048576;

	public static function deleteOldFile($directory)
	{

		if (!self::isFreeSpaceOk($directory) || !self::isMaxSpaceOk($directory)) {
			$cleanSize = self::targetSizeToDelete($directory);
			echo "Delete Size target : ".self::convertToReadableSize(abs($cleanSize))."\n";
			$pictures = picture::where('id', '>', 0)->orderBy('date', 'asc')->get();
			$totalDeleted = 0;


			foreach ($pictures as $picture) {
				if ($totalDeleted < $cleanSize) {
					$totalDeleted += $picture->fileSize;
					echo "Delete picture : ".$picture->id." (".date('Y-m-d H:i:s', $picture->date).")"."\n";
					$picture->delete();
				} else {
					break;
				}
				
			}

		}
	}

	public static function freeSpace($directory)
	{
		$free = disk_free_space(Storage::path($directory)); 
		echo "free space : ".self::convertToReadableSize($free)."\n";
		return $free;
	}

	public static function pictureTotal()
	{
		picture::cleanDb();
		$total = picture::all()->sum('fileSize');
		echo "picture folder size : ".self::convertToReadableSize($total)."\n";
		return $total;
	}

	public static function isFreeSpaceOk($directory)
	{
		$minimumInByte = parameters::get('minimum free space in MB', 100)*self::$b2Mb;
		return (self::freeSpace($directory) > $minimumInByte);
	}

	public static function isMaxSpaceOk($directory)
	{
		$maximumInByte = parameters::get('maximum picture storage size in MB', 10000)*self::$b2Mb;
		return (self::pictureTotal($directory) < $maximumInByte);
	}

	public static function targetSizeToDelete($directory)
	{

		$freeTarget = ((parameters::get('minimum free space in MB', 100)*self::$b2Mb)-intval(disk_free_space(Storage::path($directory))));
		$freeTarget = intval(($freeTarget)*1.2);
		
		$maxSpaceTarget = (intval(picture::all()->sum('fileSize'))-(parameters::get('maximum picture storage size in MB', 10000)*self::$b2Mb));
		$maxSpaceTarget = intval(($maxSpaceTarget)*1.2);

		return ($freeTarget > $maxSpaceTarget) ? $freeTarget : $maxSpaceTarget;		
	}

	public static function convertToReadableSize($size){
	  $base = log($size) / log(1024);
	  $suffix = array("", "KB", "MB", "GB", "TB");
	  $f_base = floor($base);
	  return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
	}
}