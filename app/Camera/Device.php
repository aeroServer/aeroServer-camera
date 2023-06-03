<?php
namespace App\Camera;
use App\parameters;
use Storage;
class Device
{

	protected static $devices = [
		'raspistill' => raspistill::class,
		'v4l' => v4l::class,
	];

	public static function get($file)
	{
		if (Storage::exists($file)) {
			Storage::delete($file);
		}
		
		self::$devices[parameters::get('device', 'raspistill')]::get(Storage::path($file));

		return Storage::exists($file);
	}

	public static function list()
	{
		//dd(array_keys(self::$devices));
		$list = [];
		$id = 0;
		foreach (self::$devices as $device => $value) {
			$list[] = ['id' => $id, 'device' => $device];
			$id++;
		}
		return $list;
	}
}