<?php

abstract class Lib_Base_Controller {
	
	private $render;
	private $tpl;
	
	protected $method;
	protected $get;
	protected $post;
	
	function __construct() {
	}
	
	public function execute($acion){
		$this->method = strtolower($_SERVER['REQUEST_METHOD']);
		$this->get = $_GET;
		$this->post = $_POST;
		$this->tpl = Const_Tpl::JSON;
		
		try {
			$ret = $this->$acion();
			$ret += array(
					'errno' => Const_ErrorCode::SUCC,
					'msg' => Const_ErrorCode::$code[Const_ErrorCode::SUCC],
			);
		} catch (Exception $e) {
			$ret = array(
					'errno' => Const_ErrorCode::SYSTEM_ERROR,
					'msg' => Const_ErrorCode::$code[Const_ErrorCode::SYSTEM_ERROR],
			);
		}
		
		if ($this->tpl === Const_Tpl::JSON) {
			$this->assign($ret);
		} else {
			$this->assignPage($ret);
		}
		
		$this->display();
	}
	
	protected function setTpl($tpl) {
		$this->tpl = $tpl;
	}
	
	private function assign($tpl_var) {
		$this->render->assign($tpl_var);
	}
	
	private function assignPage($tpl_var) {
		unset($tpl_var['errno']);
		unset($tpl_var['msg']);
		$this->render->assign($tpl_var);
	}
	
	private function display(){
		if (!isset($this->tpl)) {
			$this->tpl = Const_Tpl::JSON;
		}
		$this->render->display($this->tpl);
	}
	
}