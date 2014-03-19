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
	private function request_exception($msg, $line = NULL) {
		throw new DBRequestException($msg . " (DB_MYSQL" . $line .")");
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
	
	public function get_controller($controller_uri_name) {
		
		$stmt = $this->mysqli->prepare(
				"select `name`, `file_path`
				from `Controller`
				where `uri_name` = (?)
				and `enabled` = true
				limit 1;");
		
		if (!$stmt) self::request_exception("Statement not prepared", __LINE__);
		
		if (!$stmt->bind_param("s", $controller_uri_name))
			self::request_exception("Parameters not bound", __LINE__);
		
		if (!$stmt->execute())
			self::request_exception("Request execution failed", __LINE__);
		
		if (!$stmt->bind_result($name, $file_path))
			self::request_exception("Could not bind result", __LINE__);
		
		if($stmt->fetch()) {
			$res = array(
				'name' => $name,
				'file_path' => $file_path,
			);
		} else {
			$res = NULL;
		}
		
		$stmt->close();
		return $res;
	}
	
	/*public function get_user_name($user_id) {
		
		$stmt = $this->mysqli->prepare(
				"select `name`
				from `User`
				where `id` = (?);");
		
		if (!$stmt) self::request_exception("Statement not prepared", __LINE__);
		
		if (!$stmt->bind_param("i", $user_id))
			self::request_exception("Parameters not bound", __LINE__);
		
		if (!$stmt->execute())
			self::request_exception("Request execution failed", __LINE__);
		
		if (!$stmt->bind_result($user_name))
			self::request_exception("Could not bind result", __LINE__);
		
		$stmt->fetch();
		$stmt->close();
		
		return $user_name;
	}*/
	public function get_last_id(){
		return $this->mysqli->insert_id;
	}
	public function get_user_groups($user_id) {
		
		$stmt = $this->mysqli->prepare(
				"select `Group_name`
				from `User_has_Group`
				where `User_id` = (?);");
		
		if (!$stmt) self::request_exception("Statement not prepared", __LINE__);
		
		if (!$stmt->bind_param("i", $user_id))
			self::request_exception("Parameters not bound", __LINE__);
		
		if (!$stmt->execute())
			self::request_exception("Request execution failed", __LINE__);
		
		if (!$stmt->bind_result($group_name))
			self::request_exception("Could not bind result", __LINE__);
		
		$result = array();
		
		while($stmt->fetch()) {
			array_push($result, $group_name);
		}
		
		$stmt->close();
	
		return $result;
	}
	
	/*public function get_user_email($user_id) {
		
		$stmt = $this->mysqli->prepare(
				"select `email`
				from `locallogin`
				where `user_id` = (?);");
		
		if (!$stmt) self::request_exception("Statement not prepared", __LINE__);
		
		if (!$stmt->bind_param("i", $user_id))
			self::request_exception("Parameters not bound", __LINE__);
		
		if (!$stmt->execute())
			self::request_exception("Request execution failed", __LINE__);
		
		if (!$stmt->bind_result($email))
			self::request_exception("Could not bind result", __LINE__);
		
		$stmt->fetch();
		$stmt->close();
		
		
		return $email;
		
	}*/
	
	public function get_local_login_data($username) {
		$stmt = $this->mysqli->prepare(
				"select `User_id`, `username`, `salt`, `hash`, `email`
				from `LocalLogin`
				where `username` = (?)
				limit 1;");
		
		if (!$stmt) self::request_exception("Statement not prepared", __LINE__);
		
		if (!$stmt->bind_param("s", $username))
			self::request_exception("Parameters not bound", __LINE__);
		
		if (!$stmt->execute())
			self::request_exception("Request execution failed", __LINE__);
		
		if (!$stmt->bind_result($user_id, $user_name, $salt, $hash, $email))
			self::request_exception("Could not bind result", __LINE__);
		
		if($stmt->fetch()) {
			$res = array(
				"user_id" => $user_id,
				"username" => $user_name,
				"salt" => $salt,
				"hash" => $hash,
				"email" => $email
			);
		} else {
			$res = NULL;
		}
		
		$stmt->close();
		return $res;
	}
	public function insert_local_login_data($id, $username, $hashed_pass, $salt, $email) {
		$query = "INSERT INTO `locallogin` (`user_id`,`username`,`salt`,`hash`,`email`)
				VALUES ('".$id."', '".$username."','".$salt."','".$hashed_pass."','".$email."');";
		$result = $this->mysqli->query($query);
		if (!$result) {
			die('Insert local login data: ' . mysql_error());
		}
	}
	public function insert_user_data($name){
		$query = "INSERT INTO `user` (`name`) VALUES ('".$name."');";
		$result = $this->mysqli->query($query);
		if (!$result) {
			die('Insert user data into Users: ' . mysql_error());
		}
	}
	public function get_profile_data($id) {
		$stmt = $this->mysqli->prepare(
				"SELECT name, username, email
				FROM  `user` 
				JOIN  `locallogin` ON user.id = locallogin.user_id
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
}
