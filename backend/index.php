<?php

//readfile("../frontend/html/index.html");

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

$CONF = parse_ini_file("config/regix.ini");

switch ($CONF['db_adapter']) {
	case "mysql":
	default:
		include_once "core/adapters/MySQL_Adapter.php";
		$db = new MySQL_Adapter($CONF);		
}

$db->connect();

$parts = explode("/", $_GET['uri'], 2);
$controller_uri_name = $parts[0];
$controller_args = isset($parts[1]) ? $parts[1] : null;

$controller_name = $db->get_controller($controller_uri_name);

if (isset($controller_name)) {
	include_once 'controllers/basic/' . $controller_name . '.php';
} else {
	include_once 'controllers/basic/' . $CONF['default_controller'] . '.php';
}

$db->close();
