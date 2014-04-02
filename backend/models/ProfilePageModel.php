<?php
require_once REGIX_PATH.'models/Model.php';
try {
	require_once REGIX_PATH.'models/LocalLoginModel.php';
} catch (Exception $e) {
	exit("ProfilePage component requires LocalLogin component!");
}


class ProfilePageModel extends Model {

	/**
	 * LocalLogin component.
	 * @var LocalLoginModel
	 */
	protected $local_login_model;
	/**
	 * Creates a new LocalLogin model.
	 * 
	 * @param DB_Adapter $db	fully initialized database adapter object.
	 * @param Session $session	fully initialized session manager.
	 * 
	 */
	public function __construct($db, $session) {
		parent::__construct($db, $session);
		$this->local_login_model = new LocalLoginModel($db, $session);
	}
	
	/**
	 * Calls LocalLogin model's method get_user_login().
	 * 
	 * @return username
	 */	
	public function get_user_login() {
		return $this->local_login_model->get_user_login();
	}

	/**
	 * Calls LocalLogin model's method get_user_email().
	 * 
	 * @return email
	 */	
	public function get_user_email() {
		return $this->local_login_model->get_user_email();
	}
	
	/**
	 * Calls User object's method get_groups().
	 * 
	 * @return array of all user groups
	 */	
	public function get_user_groups() {
		
		$result = array();
		
		foreach ($this->session->user->get_groups() as $group) {
			array_push($result, $group->get_name());
		}
			
		return $result;
	}
}