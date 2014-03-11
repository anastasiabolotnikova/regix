<?php

include_once "core/adapters/DB_Adapter.php";

class MySQL_Adapter implements DB_Adapter {
	
	private $config;
	private $mysqli;
	
	public function __construct($config) {
		$this->config = $config;
	}
	
	public function connect() {
		try {
			$this->mysqli = new mysqli(
					$this->config->get_value('db_host'),
					$this->config->get_value('db_user'),
					$this->config->get_value('db_password'),
					$this->config->get_value('db_name'),
					$this->config->get_value('db_port'));
		} catch (ConfigException $e) {
			throw new DBConnectionException(
					"Could not get connection configuration");
		}
		
		
		if ($this->mysqli->connect_errno) {
			throw new DBConnectionException("Could not connect to database");
		}
	}
	
	public function close() {
	
		$this->mysqli->close();
	}
	
	public function get_controller($controller_uri_name) {
		
		$stmt_select_controller = $this->mysqli->prepare(
				"select `name`, `file_path`
				from `Controller`
				where `uri_name` = (?)
				and `enabled` = true
				limit 1;");
		
		if (!$stmt_select_controller) {
			throw new DBRequestException("MySQL statement is not prepared");
		}
		
		if (!$stmt_select_controller->bind_param("s",
				$controller_uri_name)) {
			throw new DBRequestException("Could not bind parameters");
		}
		
		if (!$stmt_select_controller->execute()) {
			throw new DBRequestException("Could not execute statement");
		}
		
		if (!$stmt_select_controller->bind_result($name, $file_path)) {
			throw new DBRequestException("Could not bind result");
		}
		
		$stmt_select_controller->fetch();
		$stmt_select_controller->close();
		
		return array(
			'name' => $name,
			'file_path' => $file_path,
		);
	}
	
	public function get_user_name($user_id) {
		
		$stmt_select_user_name = $this->mysqli->prepare(
				"select `name`
				from `User`
				where `id` = (?);");
		
		if (!$stmt_select_user_name) {
			throw new DBRequestException("MySQL statement is not prepared");
		}
		
		if (!$stmt_select_user_name->bind_param("i",
				$user_id)) {
			throw new DBRequestException("Could not bind parameters");
		}
		
		if (!$stmt_select_user_name->execute()) {
			throw new DBRequestException("Could not execute statement");
		}
		
		if (!$stmt_select_user_name->bind_result($user_name)) {
			throw new DBRequestException("Could not bind result");
		}
		
		$stmt_select_user_name->fetch();
		$stmt_select_user_name->close();
		
		return $user_name;
	}
	
	public function get_user_groups($user_id) {
		
		$stmt = $this->mysqli->prepare(
				"select `Group_name`
				from `User_has_Group`
				where `User_id` = (?);");
		
		if (!$stmt) {
			throw new DBRequestException("MySQL statement is not prepared");
		}
	
		if (!$stmt->bind_param("i",
				$user_id)) {
			throw new DBRequestException("Could not bind parameters");
		}
	
		if (!$stmt->execute()) {
			throw new DBRequestException("Could not execute statement");
		}
	
		if (!$stmt->bind_result($group_name)) {
			throw new DBRequestException("Could not bind result");
		}
		
		$result = array();
		
		while($stmt->fetch()) {
			array_push($result, $group_name);
		}
		
		$stmt->close();
	
		return $result;
	}
}
