<?php
/**
 * @file MySQL_Adapter.php
 * 
 * This file contains MySQL implementation of the database adapter interface.
 */

include_once "DB_Adapter.php";

/**
 * MySQL implementation of a database adapter.
 * 
 * This class provides access to MySQL databases using mysqli PHP module.
 * 
 * **This class should use prepared statements for all parameterized SQL
 * queries!** This _significantly_ reduces the risk of SQL injections.
 */
class MySQL_Adapter extends DB_Adapter {
	
	/**
	 * MySQLi object.
	 *
	 * NULL if connection is not established or closed.
	 *
	 * @var mysqli|NULL
	 */
	protected $mysqli;
	
	/**
	 * Convenience method for handling request exceptions.
	 * @param string $msg Error message.
	 * @param string $line Number of line where error is detected.
	 */
	private function request_exception($msg, $line = NULL, $debug_info = "") {
		if ($this->config->debug_db) {
			echo($debug_info . "<br />\n");
		}
		throw new DBRequestException($msg . " (DB_MYSQL" . $line .")");
	}
	
	/**
	 * Make an array of references from an array of values.
	 * 
	 * @author mac_gyver
	 * @see http://forums.phpfreaks.com/topic/281071-mysqli-dynamic-bind-param-parameters/
	 * 
	 * @param array $arr
	 * @return array
	 */
	private static function make_refs($arr)
	{
		$refs = array();
		foreach ($arr as $key => $value)
		{
			$refs[$key] = &$arr[$key];
		}
		return $refs;
	}
	
	/**
	 * Bind mysqli statement result to an array.
	 * 
	 * @author vstm
	 * @see http://stackoverflow.com/questions/7133575/whats-wrong-with-mysqliget-result
	 * 
	 * @param mysqli_stm $stmt mysqli prepared statement.
	 * @param array $row Array for results.
	 */
	private static function bind_array($stmt, &$row) {
		$stmt_metadata = $stmt->result_metadata();
		$params = array();
		while($field = $stmt_metadata->fetch_field()) {
			$params[] = &$row[$field->name];
		}
		return call_user_func_array(array($stmt, 'bind_result'), $params);
	}
	
	private static function dereference_array($arr) {
		$res = array();
		
		foreach ($arr as $key => $val) {
			$res[$key] = $val;
		}
		
		return $res;
	}
	
	public function connect() {
		$this->mysqli = new mysqli(
				$this->config->db_host,
				$this->config->db_user,
				$this->config->db_password,
				$this->config->db_name,
				$this->config->db_port);
		
		if ($this->mysqli->connect_error) {
			throw new DBConnectionException("Could not connect to database");
		}
	}
	
	public function close() {
		if ($this->mysqli) {
			$this->mysqli->close();
			$this->mysqli = NULL;
		}
	}
	
	public function get_last_id(){
		return $this->mysqli->insert_id;
	}
	
	public function get_profile_data($id) {
		$stmt = $this->mysqli->prepare(
				"SELECT name, login, email
				FROM  `user` 
				LEFT JOIN  `local_login` ON user.id = local_login.user_id
				WHERE user.id = (?)");
		
		if (!$stmt) self::request_exception("Statement not prepared", __LINE__);
		
		if (!$stmt->bind_param("s", $id))
			self::request_exception("Parameters not bound", __LINE__);
		
		if (!$stmt->execute())
			self::request_exception("Request execution failed", __LINE__);
		
		if (!$stmt->bind_result($user_name, $user_login, $email))
			self::request_exception("Could not bind result", __LINE__);
		
		if($stmt->fetch()) {
			$res = array(
				"username" => $user_name,
				"login" => $user_login,
				"email" => $email
			);
		} else {
			$res = NULL;
		}
		
		$stmt->close();
		return $res;
	}	
	
	public function select($table, $fields, $types, $filters, $limit = 0) {
		
		// Filter table names (should not be needed, but in case...)
		if (!preg_match('/^[A-Za-z0-9_]+$/',$table)) {
			self::request_exception(
					"Table name contains non-alphanumeric symbols", __LINE__);
		}
		
		// Filter limit
		if (!is_int($limit)) {
			self::request_exception(
					"Limit should be an integer", __LINE__);
		}
		
		$s_fields = "`" . implode("`,`", $fields) . "`";
		
		$s_filter_types = "";
		$s_filters = "";
		
		$is_first = TRUE;
		
		foreach($filters as $filter => $value) {
			
			// Filter column names
			if (!preg_match('/^[A-Za-z0-9_]+$/',$filter)) {
				self::request_exception(
						"Column names contain non-alphanumeric symbols",
						__LINE__);
			}
			
			if (is_int($value)) {
				$s_filter_types .= 'i';
			} else if (is_double($value)) {
				$s_filter_types .= 'd';
			} else if (is_string($value)) {
				$s_filter_types .= 's';
			} else {
				$s_filter_types .= 'b';
			}
				
			if(!$is_first) {
				$s_filters .= ' and ';
			}
				
			$s_filters .= '`' . $filter . '` = (?)';
			$is_first = FALSE;
		}
		
		$stmt = $this->mysqli->prepare(
				"select " . $s_fields . "
				from `" . $table . "`
				where " . $s_filters . "
				limit " . $limit . ";");
		
		if (!$stmt) self::request_exception("Statement not prepared", __LINE__,
					$this->mysqli->error);
		
		$bind_params = array_values($filters);
		array_unshift($bind_params, $s_filter_types);
		
		$bind_res = call_user_func_array(array($stmt, 'bind_param'),
				$this::make_refs($bind_params));
		
		if (!$stmt->execute())
			self::request_exception("Request execution failed", __LINE__,
					$this->mysqli->error);
		
		if (!self::bind_array($stmt, $result_row))
			self::request_exception("Could not bind result", __LINE__,
					$this->mysqli->error);
		
		$result = array();
		while ($stmt->fetch()) {
			array_push($result, self::dereference_array($result_row));
		}
		echo($this->mysqli->error);
		
		$stmt->close();
		return $result;
	}
	
	public function insert($table, $data) {
		
		// Filter table names (should not be needed, but in case...)
		if (!preg_match('/^[A-Za-z0-9_]+$/',$table)) {
			self::request_exception(
					"Table name contains non-alphanumeric symbols", __LINE__);
		}
		
		$s_types = ""; // Types for bind_param
		$s_fields = "";	// Field names
		$s_placeholders = "";
		
		$bind_params = array_values($data);
		
		$is_first = TRUE;
		
		foreach($data as $field => $value) {
			// Filter column names
			if (!preg_match('/^[A-Za-z0-9_]+$/',$field)) {
				self::request_exception(
						"Column names contain non-alphanumeric symbols",
						__LINE__);
			}
			
			if (is_int($value)) {
				$s_types .= 'i';
			} else if (is_double($value)) {
				$s_types .= 'd';
			} else if (is_string($value)) {
				$s_types .= 's';
			} else {
				$s_types .= 'b';
			}
			
			if(!$is_first) {
				$s_fields .= ',';
				$s_placeholders .= ",";
			}
			
			$s_fields .= '`' . $field . '`';
			$s_placeholders .= "?";
			
			$is_first = FALSE;
		}
		
		$stmt = $this->mysqli->prepare(
				"insert into `" . $table . "` (" . $s_fields . ")
				values (" . $s_placeholders . ");");
		
		if (!$stmt) {
			self::request_exception("Statement not prepared", __LINE__,
					$this->mysqli->error);
		}
		
		array_unshift($bind_params, $s_types);
		
		$bind_res = call_user_func_array(array($stmt, 'bind_param'),
				$this::make_refs($bind_params));
		
		if (!$bind_res)
			self::request_exception("Parameters not bound", __LINE__,
					$this->mysqli->error);
		
		if (!$stmt->execute())
			self::request_exception("Request execution failed", __LINE__,
					$this->mysqli->error);
		
		$stmt->close();
	}
	
	public function query($query, $params=NULL, $param_types=NULL) {
		
		$stmt = $this->mysqli->prepare($query);
		
		if (!$stmt) self::request_exception("Statement not prepared", __LINE__);
		
		if ($params) {
			array_unshift($params, $param_types);
			
			$bind_res = call_user_func_array(array($stmt, 'bind_param'),
					$this::make_refs($params));
			
			if (!$bind_res)
				self::request_exception("Parameters not bound", __LINE__);
		}
		
		if (!$stmt->execute())
			self::request_exception("Request execution failed", __LINE__,
					$this->mysqli->error);
		
		if (!self::bind_array($stmt, $result_row))
			self::request_exception("Could not bind result", __LINE__,
					$this->mysqli->error);
		
		$result = array();
		while ($stmt->fetch()) {
			array_push($result, self::dereference_array($result_row));
		}
		echo($this->mysqli->error);
		
		$stmt->close();
		return $result;
	}
	
	
	// User manager
	
	public function select_all_users_with_local_login() {
		$query = "select id, name, login, email
				from  `user`
				left join  `local_login`
				on `user`.`id` = `local_login`.`user_id`";
	
		return $this->query($query);
	}
	
	public function select_all_groups_with_user_mark($user_id) {
		$query = "
				select distinct
					`group`.`name` as `group_name`,
					max(`user_has_group`.`user_id` = ?) as `user_registered`
				from `group`
				join `user_has_group`
				on `group`.`name` = `user_has_group`.`group_name`
				group by `group_name`
				order by `group_name`;
				";
		
		return $this->query($query, array($user_id), "i");
	}
}
