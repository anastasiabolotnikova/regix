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
	
	protected $name;
	
	protected $permissions;	
	
	public function __construct($name, $db) {
		$this->name = $name;
		$permissions = $db->select_group_permissions($name);
	}
	
	public function get_name() {
		return $this->name;
	}
	
	public function has_permission($permission_name) {
		foreach ($this->permissions as $permission) {
			if ($permission["name"] == $permission_name) {
				return TRUE;
			}
		}
		return FALSE;
	}
}