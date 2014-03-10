<?php

/**
 * This file contains Regix initial bootloader.
 */

require_once 'core/exceptions/LoaderException.php';
require_once 'core/Config.php';

/**
 * Simple factory with interface control.
 * 
 * Creates a new object of class _$class_ from file _$path_ if this class
 * implements interface _$interface_.
 * 
 * @param string $path Path to file fith class definition.
 * @param string $class Class name.
 * @param string $interface Name of interface the class must implement.
 * @throws LoaderException if:
 * 1. file _$path_ is not readable or does not exist;
 * 2. class _$class_ is not loaded and cannot be loaded from file _$path_;
 * 3. class _$class_ does not implement the required interface.
 * @return mixed A new object of class _$class_.
 */
function createClass($path, $class, $interface) {
	if (class_exists($class)) {
		if (in_array($interface, class_implements($class))) {
			// Class implements required interface.
			return new $class;
		} else {
			// Class loaded, but is incorrect.
			throw new LoaderException("Class does not implement the " .
					$interface . " interface.");
		}
	} else {
		// Class is not yet loaded.
		if (is_readable($path)) {
			require_once $path;
			if (class_exists($class)) {
				if (in_array($interface, class_implements($class))) {
					return new $class;
				} else {
					throw new LoaderException("Class does not implement the " . 
							$interface . " interface.");
				}
			} else {
				throw new LoaderException("Class file found, but does not " . 
						"contain the needed class.");
			}
		} else {
			throw new LoaderException("Class file " . $path . 
					" not found or not readable.");
		}
	}
}

/**
 * Simple controller factory.
 * 
 * @param string $component_name Name of controller class.
 * @param string $controller_path Path to file containing implementation.
 * @return Controller A new Controller object. 
 */
function createController($controller_name, $controller_path) {
	$class = $controller_name;
	$path = 'controllers/' . $controller_path;
	return createClass($path, $class, 'Controller');
}


/**
 * Simple model factory.
 * 
 * @param string $component_name Name of the component which controller should
 * be created.
 * @return Model A new Model object. 
 */
function createModel($component_name) {
	$class = $component_name . 'Model';
	$path = 'models/' . $class . '.php';
	return createClass($path, $class, 'Model');
}

/**
 * Simple view factory.
 * 
 * @param string $component_name Name of the component which controller should
 * be created.
 * @return View A new View object. 
 */
function createView($component_name) {
	$class = $component_name . 'View';
	$path = 'views/' . $class . '.php';
	return createClass($path, $class, 'View');
}

$config = new Config();

try {
	if ($config->get_value("debug_php")) {
		error_reporting(E_ALL | E_STRICT);
		ini_set('display_errors', 1);
	}
} catch (ConfigException $e) {
	// Assume production values.
}

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

$parts = explode("/", $_GET['uri'], 2);
$controller_uri_name = $parts[0];
$controller_args = isset($parts[1]) ? $parts[1] : null;

$controller_data = $db->get_controller($controller_uri_name);

if (isset($controller_data['name'])) {
	// Controller found in the DB.
	try {
		createController($controller_data['name'],
		$controller_data['file_path']);
	} catch (LoaderException $e) {
		try {
			if ($config->get_value('debug_php')) {
				echo $e->getMessage();
			}
		} catch (ConfigException $e) {}
	}
} else {
try {
	// Load default controller.
	createController($config->get_value('default_controller_name'),
		$config->get_value('default_controller_file_path'));
	} catch (LoaderException $e) {
		try {
			if ($config->get_value('debug_php')) {
				echo $e->getMessage();
			}
		} catch (ConfigException $e) {}
	}
}

$db->close();
