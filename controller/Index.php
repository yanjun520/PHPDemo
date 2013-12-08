<?php

class Controller_Index extends Lib_Base_Controller {
	
	public function index() {
		$this->setTpl('index.tpl');
		$msg = isset($this->get['info']) ? '' : $this->get['info'];
		$this->assignPage(array('info' => $info));
	}
	
}