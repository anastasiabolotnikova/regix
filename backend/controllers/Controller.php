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
	public function set_db($db);
	public function set_session($session);
	public function set_arguments($args);
	public function run();
}