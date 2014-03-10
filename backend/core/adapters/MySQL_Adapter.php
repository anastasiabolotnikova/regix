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
		
		$this->prepare_statements();
	}
	
	public function close() {
	
		$this->mysqli->close();
	}
	
	public function get_controller($controller_uri_name) {
		if (!$this->stmt_select_controller) {
			throw new DBRequestException("MySQL statement is not prepared");
		}
		
		if (!$this->stmt_select_controller->bind_param("s",
				$controller_uri_name)) {
			throw new DBRequestException("Could not bing parameters");
		}
		
		if (!$this->stmt_select_controller->execute()) {
			throw new DBRequestException("Could not execute statement");
		}
		
		if (!$this->stmt_select_controller->bind_result($name, $file_path)) {
			throw new DBRequestException("Could not bind result");
		}
		
		$this->stmt_select_controller->fetch();
		
		return array(
			'name' => $name,
			'file_path' => $file_path,
		);
	}
	
	/**
	 * Prepares all statements needed by this adapter.
	 * 
	 * @todo Separate statements and prepare them only if they are actually
	 * needed in the given session.
	 */
	private function prepare_statements() {
		
		// For get_controller
		
		$this->stmt_select_controller = $this->mysqli->prepare(
				"select `name`, `file_path`
				from `Controller`
				where `uri_name` = (?)
				and `enabled` = true
				limit 1;");
	}
}
