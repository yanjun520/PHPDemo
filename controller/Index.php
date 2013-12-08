<?php

class Controller_Index extends Lib_Base_Controller {
	
	public function index() {
		$this->setTpl('index.tpl');
		$msg = isset($this->get['msg']) ? '' : $this->get['msg'];
		$this->assignPage(array('msg' => $msg));
	}
	
}