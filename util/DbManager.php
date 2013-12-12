<?php

class Util_DbManager {
	
	private $_db = null;
	
	const LIST_COM = 0;
    const LIST_AND = 1;
    const LIST_SET = 2;
    
    const FETCH_RAW = 0;    // return raw mysqli_result
    const FETCH_ROW = 1;    // return numeric array
    const FETCH_ASSOC = 2;  // return associate array
    const FETCH_OBJ = 3;    // return Bd_DBResult object
	
	public static function getDb() {
		$db_manager = new Util_DbManager();
		
		$db_conf = Util_Conf::getConf('db');
		
		$db = new mysqli($db_conf['host'], $db_conf['username'], $db_conf['passwd'], $db_conf['dbname'], $db_conf['port']);
		if ($db->connect_errno != 0) {
			echo $db->connect_error;
		}
		$db->set_charset('utf8');
		
		$db_manager->_db = $db;
		
		return $db_manager;
	}
	
	public function select($table, $fields, $conds = NULL, $options = NULL, $appends = NULL) {
        $sql = $this->_getSelect($table, $fields, $conds, $options, $appends);
        if (!$sql) {
        	return false;
        }
        return $this->_query($sql);
    }
    
    public function selectCount($table, $conds = NULL, $options = NULL, $appends = NULL) {
    	$fields = 'COUNT(*)';
    	$sql = $this->_getSelect($table, $fields, $conds, $options, $appends);
    	if (!$sql) {
    		return false;
    	}
    	$res = $this->_query($sql, self::FETCH_ROW);
    	if ($res === false) {
    		return false;
    	}
    	return intval($res[0][0]);
    }
    
    public function insert($table, $row, $options = NULL, $onDup = NULL) {
    	$sql = $this->_getInsert($table, $row, $options, $onDup);
    	if (!$sql || !$this->_query($sql)) {
    		return false;
    	}
    	return $this->_db->affected_rows;
    }
    
    public function update($table, $row, $conds = NULL, $options = NULL, $appends = NULL) {
    	$sql = $this->_getUpdate($table, $row, $conds, $options, $appends);
    	if(!$sql || !$this->_query($sql)) {
    		return false;
    	}
    	return $this->_db->affected_rows;
    }
    
    public function delete($table, $conds = NULL, $options = NULL, $appends = NULL) {
    	$sql = $this->_getDelete($table, $conds, $options, $appends);
    	if(!$sql || !$this->_query($sql)) {
    		return false;
    	}
    	return $this->_db->affected_rows;
    }
    
    private function _query($sql, $fetch_type = self::FETCH_ASSOC) {
		$res = $this->_db->query($sql);
		$ret = false;
		
		if (is_bool($res) || $res === NULL) {
			$ret = ($res == true);
		} else {
			switch ($fetch_type) {
				case self::FETCH_ASSOC:
					while ($row = $res->fetch_assoc()) {
						$ret[] = $row;
					}
					break;
				case self::FETCH_ROW:
					while ($row = $res->fetch_row()) {
						$ret[] = $row;
					}
					break;
				default:
					$ret = $res;
					break;
			}
			$res->free();
		}
		
		return $ret;
    }
    
    private function _getSelect($table, $fields, $conds, $options, $appends) {
    	$sql = 'SELECT ';
    	$fields = $this->_makeList($fields, self::LIST_COM);
    	$sql .= "{$fields} FROM {$table}";
    	
    	if ($conds !== null) {
    		$conds = $this->_makeList($conds, self::LIST_AND);
    		$sql .= " WHERE {$conds}";
    	}
    	
    	if($appends !== NULL) {
    		$appends = $this->_makeList($appends, self::LIST_COM, ' ');
    		$sql .= " $appends";
    	}

    	return $sql;
    }
    
    private function _getInsert($table, $row, $options = NULL, $onDup = NULL) {
    	$sql = "INSERT {$table} SET ";
    	$row = $this->_makeList($row, self::LIST_SET);
    	$sql .= $row;
    
    	if(!empty($onDup)) {
    		$sql .= ' ON DUPLICATE KEY UPDATE ';
    		$onDup = $this->_makeList($onDup, self::LIST_SET);
    		$sql .= $onDup;
    	}
    	
    	return $sql;
    }
    
    private function _getUpdate($table, $row, $conds = NULL, $options = NULL, $appends = NULL) {
    	if(empty($row)) {
    		return NULL;
    	}
    	return $this->_makeUpdateOrDelete($table, $row, $conds, $options, $appends);
    }
    
    private function _getDelete($table, $conds = NULL, $options = NULL, $appends = NULL) {
    	return $this->_makeUpdateOrDelete($table, NULL, $conds, $options, $appends);
    }
    
    private function _makeList($arrList, $type = self::LIST_SET, $cut = ', ') {
    	if (is_string($arrList)) {
    		return $arrList;
    	}
    	
    	$sql = '';
    	
    	if ($type == self::LIST_SET) { // for set in insert and update
    		foreach ($arrList as $name => $value) {
    			if (is_int($name)) {
    				$sql .= "$value, ";
    			} else {
    				if (!is_int($value)) {
    					if ($value === NULL) {
    						$value = 'NULL';
    					} else {
    						$value = '\''.$this->_db->real_escape_string($value).'\'';
    					}
    				}
    				$sql .= "$name=$value, ";
    			}
    		}
    		$sql = substr($sql, 0, strlen($sql) - 2);
    	} else if ($type == self::LIST_AND) { // for where conds
    		foreach ($arrList as $name => $value) {
    			if (is_int($name)) {
    				$sql .= "($value) AND ";
    			} else {
    				if (!is_int($value)) {
    					if ($value === NULL) {
    						$value = 'NULL';
    					} else {
    						$value = '\''.$this->_db->real_escape_string($value).'\'';
    					}
    				}
    				$sql .= "($name $value) AND ";
    			}
    		}
    		$sql = substr($sql, 0, strlen($sql) - 5);
    	} else {
            $sql = implode($cut, $arrList);
        }
    	
    	return $sql;
    }
    
    private function _makeUpdateOrDelete($table, $row, $conds, $options, $appends) {
    	// 1. options
    	if($options !== NULL) {
    		if(is_array($options)) {
    			$options = implode(' ', $options);
    		}
    		$sql = $options;
    	}
    
    	// 2. fields
    	// delete
    	if(empty($row)) {
    		$sql = "DELETE $options FROM $table ";
    	}
    	// update
    	else {
    		$sql = "UPDATE $options $table SET ";
    		$row = $this->_makeList($row, self::LIST_SET);
    		if(!strlen($row)) {
    			$this->sql = NULL;
    			return NULL;
    		}
    		$sql .= "$row ";
    	}
    
    	// 3. conditions
    	if($conds !== NULL) {
    		$conds = $this->_makeList($conds, self::LIST_AND);
    		if(!strlen($conds)) {
    			$this->sql = NULL;
    			return NULL;
    		}
    		$sql .= "WHERE $conds ";
    	}
    
    	// 4. other append
    	if($appends !== NULL) {
    		$appends = $this->_makeList($appends, self::LIST_COM, ' ');
    		if(!strlen($appends)) {
    			$this->sql = NULL;
    			return NULL;
    		}
    		$sql .= $appends;
    	}
    
    	return $sql;
    }
	
}