<?php

class Service_Db {

	public function query() {
		$ret = array(
				'user_list'	=> array(),	
		);
		$user_dao = new Dao_User();
		$user_list = $user_dao->getAll();
		$ret['user_list'] = $user_list;
		
		return $ret;
	}
	
}