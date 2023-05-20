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

	private function write(String $name, $default)
	{
		$this->data[$name] = $default;
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


}