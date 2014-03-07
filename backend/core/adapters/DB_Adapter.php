<?php
interface DB_Adapter {
	public function __construct($CONF);
	
	public function connect();
	
	public function close();
	
	public function get_controller($controller_uri_name);
}