<?php

spl_autoload_register(function($class_name) {
	spl_autoload_register(function($class_name) {
		$dirs=explode('_',$class_name);
		$file_name=array_pop($dirs);
		$dir_path='';
		foreach ($dirs as $dir){
			if($dir){
				$dir_path.=strtolower($dir).DIRECTORY_SEPARATOR;
			}
		}
		$file_path=$dir_path.$file_name.'.php';
		
		require_once $file_path;
	});
});