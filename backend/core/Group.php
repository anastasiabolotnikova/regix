<?php

/**
 * @file Group.php
 * 
 * This file contains Group class implementation.
 */


/**
 * Regix core user group.
 * 
 * This class directly corresponds to database _Group_ table.
 */
class Group {
	
	private $name;
	
	public function __construct($name) {
		$this->name = $name;
	}
	
	public function get_name() {
		return $this->name;
	}
}