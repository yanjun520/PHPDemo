<?php

class Lib_Util_Conf {
	
	const SPLIT_CHAR = ':';
	
	public static function getConf($conf_path) {
		$conf_file = $_SERVER['DOCUMENT_ROOT'] . "/conf/{$conf_path}.conf";
		$conf = false;
		$file_handle = @fopen($conf_file, 'r');
		if ($file_handle) {
			$conf = array();
			while (!feof($file_handle)) {
				$line = fgets($file_handle);
				$arr = explode(self::SPLIT_CHAR, $line, 2);
				if (count($arr) === 2) {
					$conf[trim($arr[0])] = trim($arr[1]);
				}
			}
		}
		fclose($file_handle);
		
		return $conf;
	}
	
}
