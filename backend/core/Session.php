<?php
class Session {
	
	public function __construct() {
		session_start();
	}
	
	public function get($name, $default = NULL) {
		if (isset($_SESSION[$name])) {
			return $_SESSION[$name];
		} else {
			return $default;
		}
	}
	
	public function set($name, $value) {
		$_SESSION[$name] = $value;
	}
	
	public function destroy() {
		session_destroy();
	}
}