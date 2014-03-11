<?php

/**
 * This file contains controller interface.
 */

/**
 * Interface for Regix controllers.
 * 
 * All controllers in the Regix system should implement this interface,
 * otherwise they will not be loaded.
 * 
 * @author Sergei Jakovlev
 *
 */
interface Controller {
	
	/**
	 * Passes database adapter to a controller.
	 * 
	 * This function is guaranteed to be called by the bootloader before
	 * _run()_.
	 * 
	 * @param DB_Adapter $db Fully initialized database adapter object.
	 * 
	 * @see DB_Adapter
	 */
	public function set_db($db);
	
	/**
	 * Passes session handler to a controller.
	 * 
	 * This function is guaranteed to be called by the bootloader before
	 * _run()_.
	 * 
	 * @param Session $session Fully initialized session handler.
	 * 
	 * @see Session
	 */
	public function set_session($session);
	
	/**
	 * Passes arguments received via URL to a controller.
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
	 * @param array|NULL $args Array of strings parsed from request URI.
	 */
	public function set_arguments($args);
	
	/**
	 * Runs an initialized controller.
	 * 
	 * This function is called by the bootloader after a controller is
	 * initialized. Within this function controller must call _show_ methods
	 * of appropriate views.
	 */
	public function run();
}