<?php
class User {
	
	private $db;
	
	private $id;
	private $name;
	private $groups;
	
	public function __construct($id, $db) {
		$this->db = $db;
		$this->id = $id;
		$this->name = $this->db->get_user_name($id);
		$this->groups = $this->db->get_user_groups($id);
	}
	
	public function get_name() {
		return $this->name;
	}
}