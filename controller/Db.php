<?php

class Controller_Db extends Lib_Base_Controller {
	
	public function query() {
		$this->setTpl('db/query.tpl');
		$service = new Service_Db();
		return $service->query();
	}
	
}