<?php

/**
 * This file contains Regix configuration parser, related helper modules
 * and exceptions.
 */

/**
 * Exception thrown when configuration file is incorrect.
 * 
 * This exception should be thrown in two cases:
 * 1. if configuration file does not exist or is not readable;
 * 2. if requested option is not found in configuration file.
 * 
 * @author Sergei Jakovlev
 *
 */
class ConfigException extends Exception {}

/**
 * This class provides a flexible interface to access Regix configuration.
 * 
 * @author Sergei Jakovlev
 *
 */
class Config {
	/**
	 * Contains an array of values parsed from configuration file.
	 * @var array
	 */
	private $config;
	
	/**
	 * Create a new configuration object.
	 * 
	 * @param string $config_path Path to configuration .ini file.
	 * "config/regix.ini" by default.
	 * @throws ConfigException if configuration file cannot be found.
	 */
	public function __construct($config_path="config/regix.ini") {
		if (is_readable($config_path)) {
			$this->config = parse_ini_file($config_path);
		} else {
			throw new ConfigException("Configuration file not found!");
		}
	}
	
	/**
	 * Return the value of the requested parameter.
	 * 
	 * @param string $name Name of the parameter.
	 * @throws ConfigException if there is no such value in the configuration
	 * file.
	 * @return multitype:bool,int,string Value of the parameter.
	 */
	public function get_value($name) {
		if (isset($this->config[$name])) {
			return $this->config[$name];
		} else {
			throw new ConfigException(
					"Parameter with the provided name does not exist.");
		}
	}
}