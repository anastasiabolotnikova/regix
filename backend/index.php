<?php

/**
 * @file index.php
 * 
 * This file contains Regix bootloader.
 */

/**
 * Include path
 * 
 * Must have trailing slash.
 * @var string
 */
define("REGIX_PATH", "backend/");


// Try to include all needed core components.

require_once REGIX_PATH.'core/Config.php';
require_once REGIX_PATH.'core/Session.php';
require_once REGIX_PATH.'core/User.php';


/**
 * Simple class loader
 * 
 * This loader checks if class _$class_ (extending class _$parent_) is available
 * and tries to include file _$file_ if it is not.
 * 
 * Class is considered to be available if **it is visible and extends
 * _$parent_**. Note that if a class with name _$class_ is visible, but
 * does not extend _$parent_, _$file_ **will be included**, which may cause
 * shadowing (if file indeed contains a different definition of _$class_).
 * 
 * **Note that all arguments are strings!**
 * 
 * @param string $path Path to file with class _$class_ definition.
 * @param string $class Class name.
 * @param string $parent Name of a parent the class must extend.
 * @return TRUE|FALSE TRUE if class is visible and correct, FALSE otherwise.
 */
function loadClass($path, $class, $parent) {
	if (class_exists($class)) {
		if(in_array($parent, class_parents($class))) {
			// Requested class is visible and it extends appropriate class.
			return TRUE;
		} else {
			// Requested class is visible, but does not extend $parent.
			// Do not include file $path to avoid class shadowing, return FALSE
			// instead. This situation should be avoided!
			return FALSE;
		}
	} else {
		// Class is not yet visible.
		if (is_readable($path)) {
			// File exists and is readable, include it (once).
			require_once $path;
			if (class_exists($class) &&
					in_array($parent, class_parents($class))) {
				// Now class is visible and correct.
				return TRUE;
			} else {
				// Class is still either invisible or incorrect.
				return FALSE;
			}
		} else {
			// File is not accessible.
			return FALSE;
		}
	}
}

function create_controller($uri_name) {
	
}

define("REGIX", TRUE);

// Configuration
try {
	$config = new Config(REGIX_PATH."config/regix.ini");
} catch (Exception $e) {
	echo $e->getMessage();
	exit("Could not load configuration (BL" . __LINE__. ")");
}

if ($config->debug_php) {
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', 1);
} else {
	// Assume production values.
	error_reporting(0);
	ini_set('display_errors', 0);
}

// Set timezone
date_default_timezone_set($config->timezone);

// DB

try {
	switch ($config->db_adapter) {
		case "mysql":
		default:
			include_once REGIX_PATH."core/adapters/MySQL_Adapter.php";
			$db = new MySQL_Adapter($config);
	}
} catch (DBConnectionException $e) {
	if ($config->debug_php) {
		echo $e->getMessage();
	}
	exit("Connection error (BL" . __LINE__ . ").");
}

$db->connect();

// UAC

$session = new Session();
if (!$session->user) {
	// There is no user stored in the session.
	try {
		//$session->user_id = $config->default_user_id;
		$session->user = new User(1, $db);
	} catch (ConfigException $e) {
		$db->close();
		exit("Configuration error (BL" . __LINE__ . ").");
	}
} else {
	// Reload user data
	try {
		$session->user = new User($session->user->get_id(), $db);
	} catch (Exception $e) {
		// User was removed
		$session->user = new User(1, $db);
	}
}

// Launch

$parts = explode("/", $_GET['uri'], 2);
$controller_uri_name = $parts[0];
$controller_args = isset($parts[1]) ? explode("/", $parts[1]) : NULL;

$controller_data = $db->select(
		"controller",
		array("id", "name", "file_path"),
		"iss",
		array(
				"uri_name" => $controller_uri_name,
				"enabled" => 1,
		), 1);

if ($controller_data) {
	// Controller found in the DB.
	$controller_name = $controller_data[0]["name"];
	$controller_path = REGIX_PATH.$controller_data[0]["file_path"];
	$controller_id = $controller_data[0]["id"];
} else {
	// Load default controller.
	try {
		$controller_name = $config->default_controller_name;
		$controller_path = REGIX_PATH.$config->default_controller_file_path;
		$controller_id = $config->default_controller_id;
	} catch (ConfigException $e) {
		$db->close();
		exit("Configuration error (BL" . __LINE__ . ").");
	}
}

try {
	if (loadClass($controller_path, $controller_name, "Controller")) {
		$controller = new $controller_name($controller_id, $db, $session,
				$controller_args);
		if (!$controller->run()) {
			$db->close();
			exit("Controller failed (BL" . __LINE__ . ").");
		}
	} else {
		$db->close();
		exit("Controller not loaded (BL" . __LINE__ . ").");
	}
} catch (Exception $e) {
	if ($config->debug_db) {
		echo($e->getMessage());
	}
	$db->close();
	exit("Controller error (BL" . __LINE__ . ").");
}

$db->close();
