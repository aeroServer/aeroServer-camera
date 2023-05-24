<?php
namespace App;
use Storage;

/**
 * 
 */
class parameters
{
	public $data = null;
	private $updateTime = 60;
	private $filePath = 'parameters.json';
	private $protectedParameters = ['UPDATE'];

	function __construct()
	{
		if (is_null($this->data) || $this->isOutDatedData()) {
			$this->getFile();
		}
	}

	private function getFile()
	{
		$this->data = (Storage::exists($this->filePath)) ? json_decode(Storage::get($this->filePath), true) : [] ;
		$this->data['UPDATE'] = time();
	}

	private function isOutDatedData()
	{
		if (!isset($this->data['update']) || $this->data['update'] < (time()-$this->updateTime)) {
			return true;
		} else {
			return false;
		}
	}

	private function write(String $name, $value)
	{
		$this->data[$name] = $value;
		Storage::put($this->filePath, json_encode($this->data, JSON_PRETTY_PRINT));
	}

	public static function get(String $name, $default)
	{
		$pa = new self();
		if (isset($pa->data[$name])) {
			return $pa->data[$name];
		} else {
			$pa->write($name, $default);
			return $default;
		}
	}

	public static function getUpdate(String $name, $value = null)
	{
		$pa = new self();
		if (isset($pa->data[$name]) && !in_array($name, $pa->protectedParameters)) {
			if (!is_null($value)) {
				$type = gettype($pa->data[$name]);
				if (settype($value, $type)) {
					$pa->write($name, $value);
				} else {
					return null;
				}
			}
			return $pa->data[$name];
		} else {
			return null;
		}
	}

	public static function getAll()
	{
		$pa = new self();
		return $pa->data;
	}
}