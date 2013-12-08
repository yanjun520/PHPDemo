<?php

abstract class Lib_Base_Controller {
	
	private $_render;
	private $_tpl;
	
	protected $method;
	protected $get;
	protected $post;
	
	function __construct() {
		$this->_render = Common_Init::initSmarty();
	}
	
	public function process($acion){
		$this->method = strtolower($_SERVER['REQUEST_METHOD']);
		$this->get = $_GET;
		$this->post = $_POST;
		
		$this->$acion();
		$this->_display();
	}
	
	protected function setTpl($tpl) {
		$this->_tpl = $tpl;
	}
	
	protected function assign($tpl_var) {
		$this->_render->assign($tpl_var);
	}
	
	protected function assignPageData($tpl_var) {
		unset($tpl_var['errno']);
		unset($tpl_var['msg']);
		$this->_render->assign($tpl_var);
	}
	
	private function _display(){
		if (!isset($this->_tpl)) {
			$this->_tpl = Const_Tpl::JSON;
		}
		$this->_render->display($this->_tpl);
	}
	
}