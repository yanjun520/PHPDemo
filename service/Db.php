<?php

class Service_Db extends Lib_Base_Service {

	protected function process() {
		$ret = array(
				'user_list'	=> array(),	
		);
		$user_dao = new Dao_User();
		$user_list = $user_dao->getAll();
		$ret['user_list'] = $user_list;
		
		return $ret;
	}
	
}