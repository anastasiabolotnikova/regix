<?php
class Session {
	
	public function __construct() {
		session_start();
	}
	
	public function __get($name) {
		if (isset($_SESSION[$name])) {
			return $_SESSION[$name];
		} else {
			return NULL;
		}
	}
	
	public function __set($name, $value) {
		$_SESSION[$name] = $value;
	}
	
	public function destroy() {
		session_destroy();
	}
}