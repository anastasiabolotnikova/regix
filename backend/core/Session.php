<?php
/**
 * @file Session.php
 * 
 * This file contains implementation of Regix session manager.
 */

/**
 * Regix session manager.
 * 
 * This class provides basic functionality for storage of session information.
 * Session data is loaded during object initialization (if it exists).
 * 
 * @example
 *     // Create or resume session.
 *     $session = new Session();
 *     
 *     // Set parameter
 *     $session->param = value;
 *     
 *     // Get stored parameter (NULL if not set)
 *     $param = $session->param;
 *     
 */
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