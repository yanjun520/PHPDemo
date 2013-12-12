<?php

class Service_Db {

	protected function query() {
		$ret = array(
				'user_list'	=> array(),	
		);
		$user_dao = new Dao_User();
		$user_list = $user_dao->getAll();
		$ret['user_list'] = $user_list;
		
		return $ret;
	}
	
}