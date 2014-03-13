<?php

/**
 * @file Config.php
 * 
 * This file contains Regix configuration parser.
 */

/**
 * This class provides a flexible interface to access Regix configuration.
 * 
 * Note that this class provides read-only access to properly formatted _.ini_
 * files.
 */
class Config {
	
	/**
	 * Contains an array of values parsed from configuration file.
	 * @var array
	 */
	protected $config;
	
	/**
	 * Creates a new configuration object.
	 * 
	 * @param string $config_path Path to configuration .ini file.
	 * _config/regix.ini_ by default.
	 * @throws Exception if configuration file cannot be found.
	 */
	public function __construct($config_path="config/regix.ini") {
		if (is_readable($config_path)){
			if (!$this->config = parse_ini_file($config_path)) {
				throw new Exception("Badly formatted " . $config_path);
			}	
		} else {
			throw new Exception("File " . $config_path . " not found");
		}
	}
	
	/**
	 * Return the value of the requested parameter.
	 * @param string $name Name of the parameter.
	 * @return multitype:bool|int|string|NULL Value of the parameter. NULL if
	 * parameter does not exist.
	 */
	public function __get($name) {
		if (isset($this->config[$name])) {
			return $this->config[$name];
		} else {
			return NULL;
		}
	}
}