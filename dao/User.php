<?php

class Dao_User {

	const TABLE = 'user';
	
	private $_db;
	private $_table_fields = false;
	
	function __construct() {
		$this->_db = Common_DbManager::getDb();
		$this->_table_fields = array(
				'user_id',
				'user_name',
		);
	}
	
	public function insert($params){
		return $this->_db->insert(self::TABLE, $params);
	}
	
	public function getAll() {
		$result = $this->_db->select(self::TABLE, $this->_table_fields);
		$ret = empty($result)? array() : $result;
		return $ret;
	}
	
	public function getById($user_id) {
		$conds = array(
				'user_id=' => $user_id,
		);
		$fields = array(
				'user_id',
				'user_name',
		);
		$result = $this->_db->select(self::TABLE, $fields, $conds);
		$ret = empty($result[0])? null : $result[0];
		return $ret;
	}
	
	public function update($user_id, $params) {
		$conds = array(
				'user_id='	=> $user_id,
		);
		$ret = $this->_db->update(self::TABLE, $params, $conds);
		return $ret;
	}
	
	public function del($user_id) {
		$conds = array(
				'user_id='	=> $user_id,
		);
		$ret = $this->_db->delete(self::TABLE, $conds);
		return $ret;
	}
	
}
