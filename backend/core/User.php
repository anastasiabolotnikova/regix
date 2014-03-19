<?php
/**
 * @file User.php
 * 
 * This file contains User class definition.
 */

/**
 * Regix core user.
 * 
 * This class directly corresponds to database _User_ table and provides methods
 * to access basic user information stored in this table.
 * 
 * Note that this class does not provide methods for authentication.
 */
class User {
	
	/**
	 * Database adapter.
	 * 
	 * Used when creating user object.
	 * @var DB_Adapter
	 */
	protected $db;
	
	/**
	 * User id.
	 * @var int
	 */
	protected $id;
	
	/**
	 * User name
	 * 
	 * Note that this is the name from _User_ table, even though other variants
	 * may be available via additional modules.
	 * 
	 * @var unknown
	 */
	protected $name;
	
	/**
	 * Array of user groups.
	 * @var array
	 * 
	 * @todo Create Group objects automatically.
	 */
	protected $groups;
	
	protected $email;
	
	protected $login;
	
	/**
	 * Creates a new user object. Downloads data from the database.
	 * 
	 * @param int $id User id.
	 * @param DB_Adapter $db Initialized and connected database adapter object.
	 * 
	 * @todo Create Group objects automatically.
	 */
	public function __construct($id, $db) {
		$this->db = $db;
		$this->id = $id;
		$prof_data = $this->db->get_profile_data($id);
		$this->name = $prof_data['username'];
		$this->login = $prof_data['login'];
		$this->email = $prof_data['email'];
		$this->groups = $this->db->get_user_groups($id);
	}
	
	/**
	 * Returns user name.
	 * 
	 * Note that this is the name from _User_ table, even though other variants
	 * may be available via additional modules.
	 * 
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}
	public function get_group() {
		return $this->groups;
	}
	public function get_email() {
		return $this->email;
	}
	public function get_login() {
		return $this->login;
	}
}