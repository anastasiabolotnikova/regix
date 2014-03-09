<?php

include_once "core/adapters/DB_Adapter.php";

class MySQL_Adapter implements DB_Adapter {
	
	public function __construct($CONF) {
		$this->CONF = $CONF;
	}
	
	public function connect() {
		
		$this->mysqli = new mysqli(
				$this->CONF['db_host'],
				$this->CONF['db_user'],
				$this->CONF['db_password'],
				$this->CONF['db_name'],
				$this->CONF['db_port']);
		
		if ($this->mysqli->connect_errno) $this->error("Could not connect to database");
		
		$this->prepare_statements();
	}
	
	public function close() {
	
		$this->mysqli->close();
	}
	
	public function get_controller($controller_uri_name) {
		if (!$this->stmt_select_controller) {
			$this->error("get_controller: MySQL statement is not prepared");
		}
		
		if (!$this->stmt_select_controller->bind_param("s", $controller_uri_name)) {
			$this->error("get_controller: could not bing parameters");
		}
		
		if (!$this->stmt_select_controller->execute()) {
			$this->error("get_controller: could not execute statement");
		}
		
		if (!$this->stmt_select_controller->bind_result($controller_name)) {
			$this->error("get_controller: could not bind result");
		}
		
		$this->stmt_select_controller->fetch();
		
		return $controller_name;
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
				"select `name`
				from `Controller`
				where `uri_name` = (?)
				and `enabled` = true
				limit 1;");
	}
	
	/**
	 * Stop script execution and show database error page.
	 * 
	 * @param string $error_message Informational message to be displayed.
	 * Should not contain sensitive information.
	 * 
	 * @todo This method of stopping controller is ugly and dangerous. See
	 * if we can gracefully tell a controller to stop execution. Suggestions:
	 * * throw error when appropriate function is called;
	 * * return predefined value.
	 */
	private function error($error_message) {
		$error_message = "[MySQL_Adapter] " . $error_message;
		include("views/error/db_connection_error.phtml");
		exit();
	}
}
