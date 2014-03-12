<?php

/**
 * This file contains Regix initial bootloader.
 */

require_once 'core/Config.php';
require_once 'core/Session.php';
require_once 'core/User.php';

/**
 * Simple class loader
 * 
 * Checks if requested class is visible and requires file _$path_ if it is not.
 * 
 * Class is considered to be correct if it is visible and extends _$parent_.
 * 
 * @param string $path Path to file fith class definition.
 * @param string $class Class name.
 * @param string $parent Name of a parent the class must extend.
 * @return TRUE|FALSE TRUE if class is visible and correct, FALSE otherwise.
 */
function loadClass($path, $class, $parent) {
	if (class_exists($class) && in_array($parent, class_parents($class))) {
		return TRUE;
	}
	
	// Class is not yet loaded.
	if (is_readable($path)) {
		require_once $path;
		if (class_exists($class) && in_array($parent, class_parents($class))) {
			return TRUE;
		} else { return FALSE; }
	} else { return FALSE; }
}

// Configuration
$config = new Config();

try {
	if ($config->get_value("debug_php")) {
		error_reporting(E_ALL | E_STRICT);
		ini_set('display_errors', 1);
	}
} catch (ConfigException $e) {
	// Assume production values.
}

// DB

try {
	switch ($config->get_value("db_adapter")) {
		case "mysql":
		default:
			include_once "core/adapters/MySQL_Adapter.php";
			$db = new MySQL_Adapter($config);
	}
} catch (ConfigException $e) {
	exit("Configuration error (BL" . __LINE__ . ").");
} catch (DBConnectionException $e) {
	exit("Connection error (BL" . __LINE__ . ").");
}

$db->connect();

// UAC

$session = new Session();
if (!$user = $session->get('user')) {
	try {
		$user = new User($config->get_value('default_user_id'), $db);
	} catch (ConfigException $e) {
		$db->close();
		exit("Configuration error (BL" . __LINE__ . ").");
	}
	
	$session->set('user', $user);
}

// Launch

$parts = explode("/", $_GET['uri'], 2);
$controller_uri_name = $parts[0];
$controller_args = isset($parts[1]) ? explode("/", $parts[1]) : NULL;

$controller_data = $db->get_controller($controller_uri_name);

if (isset($controller_data['name'])) {
	// Controller found in the DB.
	$controller_name = $controller_data['name'];
	$controller_path = $controller_data['file_path'];
} else {
	// Load default controller.
	try {
		$controller_name = $config->get_value('default_controller_name');
		$controller_path = $config->get_value('default_controller_file_path');
	} catch (ConfigException $e) {
		$db->close();
		exit("Configuration error (BL" . __LINE__ . ").");
	}
}

try {
	if (loadClass($controller_path, $controller_name, "Controller")) {
		$controller = new $controller_name($db, $session, $controller_args);
		$controller->run();
	} else {
		$db->close();
		exit("Configuration error (BL" . __LINE__ . ").");
	}
} catch (Exception $e) {
	$db->close();
	exit("Configuration error (BL" . __LINE__ . ").");
}

$db->close();
