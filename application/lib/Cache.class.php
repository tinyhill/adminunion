<?php

/**
 * 核心接口缓存类
 * http://neo22s.com/cache-class-for-php/
 */
class Cache {

	//path for the cache
	private $cache_path;

	//seconds that the cache expires
	private $cache_expire;

	//cache constructor, optional expiring time and cache path
	public function Cache ($exp_time = 3600, $path = 'cache/') {
		$this -> cache_expire = $exp_time;
		$this -> cache_path = $path;
	}

	//returns the filename for the cache
	private function fileName ($key) {
		return $this -> cache_path . md5($key);
	}

	//creates new cache files with the given data, $key== name of the cache, data the info/values to store
	public function put ($key, $data) {
		$values = serialize($data);
		$filename = $this -> fileName($key);
		$file = fopen($filename, 'w');
		if ($file) {//able to create the file
			fwrite($file, $values);
			fclose($file);
		} else
			return false;
	}

	//returns cache for the given key
	public function get ($key) {
		$filename = $this -> fileName($key);
		if (!file_exists($filename) || !is_readable($filename)) {

			//can't read the cache
			return false;
		}
		if (time() < (filemtime($filename) + $this -> cache_expire)) {

			//cache for the key not expired
			$file = fopen($filename, 'r');

			//read data file
			if ($file) {

				//able to open the file
				$data = fread($file, filesize($filename));
				fclose($file);

				//return the values
				return unserialize($data);
			} else {
				return false;
			}
		} else {

			//was expired you need to create new
			return false;
		}
		
	}

}

?>