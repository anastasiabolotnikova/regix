<?php

/**
 * @file Permission.php
 *
 * This file contains Regix permission object description.
 */

class Permission {
	private $controller_id;
	private $name;
	
	public function __construct($controller_id, $name) {
		$this->controller_id = $controller_id;
		$this->name = $name;
	}
}