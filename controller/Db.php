<?php

class Controller_Index extends Lib_Base_Controller {
	
	public function query() {
		$this->setTpl('db/query.tpl');
		$service = new Service_Db();
		$ret = $service->execute();
		$this->assignPage($ret);
	}
	
}