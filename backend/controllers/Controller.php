<?php

/**
 * This file contains controller abstract class.
 */

/**
 * Abstract class for Regix controllers.
 * 
 * All controllers in the Regix system should extend this class,
 * otherwise they will not be loaded.
 *
 */
abstract class Controller {
	
	/**
	 * Global database adapter.
	 * @var DB_Adapter
	 */
	protected $db;
	
	/**
	 * Global session manager.
	 * @var Session
	 */
	protected $session;
	
	/**
	 * URL arguments as array of strings.
	 * @var array
	 */
	protected $args;
	
	/**
	 * Creates a new controller.
	 * 
	 * @param DB_Adapter $db	fully initialized database adapter object.
	 * @param Session $session	fully initialized session manager.
	 * @param array|NULL $args	URL arguments.
	 * 
	 * As per URI usage policy, URL is considered to have the following format:
	 *     example.com/controller/arg_1/arg_2...
	 * 
	 * If no arguments are parsed, NULL is passed. It is passed if:
	 * 1. URI has form `example.com/controller`;
	 * 2. controller is called as a default controller.
	 * 
	 * Note that for URL with form `example.com/controller/` a single empty
	 * string is passed.
	 * 
	 * Last element of the passed array may be an empty string if the request
	 * URL had a trailing slash.
	 * 
	 * @example 
	 * `example.com/controller/arg_1/arg_2` is parsed into
	 *     array {
	 *         1 => "arg_1",
	 *         2 => "arg_2",
	 *     }
	 * `example.com/controller/arg_1/arg_2/` is parsed into
	 *     array {
	 *         1 => "arg_1",
	 *         2 => "arg_2",
	 *         3 => "",
	 *     }
	 * 
	 */
	public function __construct($db, $session, $args) {
		$this->db = $db;
		$this->session = $session;
		$this->args = $args;
	}
	
	/**
	 * Runs an initialized controller.
	 * 
	 * This function is called by the bootloader after a controller is
	 * initialized.
	 */
	abstract public function run();
}