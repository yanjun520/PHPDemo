<?php

class Lib_Base_Init {
	
	public static function init() {
		date_default_timezone_set('PRC');
	}
	
	public static function initSmarty() {
		require_once "smarty/Smarty.class.php";
		$tpl = new Smarty();
		$tpl->template_dir = $_SERVER['DOCUMENT_ROOT']."/template/";
		$tpl->compile_dir = "{$_SERVER['HOME']}/temp/smarty_template_c/";
		$tpl->config_dir = "{$_SERVER['HOME']}/temp/smarty_config/";
		$tpl->cache_dir = "{$_SERVER['HOME']}/temp/smarty_cache/";
		$tpl->caching=0;
		$tpl->cache_lifetime=60*60*24;
		$tpl->left_delimiter = '{%';
		$tpl->right_delimiter = '%}';
		return $tpl;
	}
	
	public static function run() {
		$controller = '';
		$action = '';
		$query_str = $_SERVER['REQUEST_URI'];
		
        // to throw exception when controller or action is absent
        $filter_char_list = array('#', '?');
        foreach ($filter_char_list as $filter_char) {
        	$pos = strpos($query_str, $filter_char);
        	if ($pos !== false) {
        		$query_str = substr($query_str, 0, $pos);
        	}
        }
        $tmp = explode('/', $query_str);
        if (count($tmp) === 2) {
        	$controller = 'index';
        	$action = $tmp[1];
        } else if (count($tmp) > 2) {
        	$controller = $tmp[1];
			$action = $tmp[2];
		}
		
		$controller_file = strtoupper($controller[0]) . substr($controller, 1);
		$controller_class = "Controller_{$controller_file}";
		$ref = new ReflectionClass($controller_class);
		$controller_instance = $ref->newInstance();
		$controller_instance->execute($action);
	}
	
}
