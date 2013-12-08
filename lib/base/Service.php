<?php

abstract class Lib_Base_Service {
	
	protected $method;
	protected $get;
	protected $post;
	
	public function process() {
		$err = array(
				'errno' => Const_ErrorCode::SUCC,
				'msg' => Const_ErrorCode::$code[Const_ErrorCode::SUCC],
		);
		
		$this->method = strtolower($_SERVER['REQUEST_METHOD']);
		$this->get = $_GET;
		$this->post = $_POST;
		
		try {
			$func = array($this, 'execute');
			$args = func_get_args();
			$data = call_user_func_array($func, $args);
		} catch (Exception $e) {
			echo "Base_PageService error";
// 			$err = array(
// 					'errno' => $e->getCode(),
// 					'msg' => 'error',
// 			);
		}
		return $this->getResult($data, $err);
	}
	
	abstract protected function execute();
	
	protected function getResult($data, $err) {
		if ($this->method === 'post' || (isset($this->get['format']) && $this->get['format'] === 'json')) {
			return array('json_data' => isset($data) ? $data : array()) + $err;
		}
		return (isset($data) ? $data : array()) + $err;
	}
	
}