<?php
require_once REGIX_PATH.'models/Model.php';
try {
	require_once REGIX_PATH.'models/LocalLoginModel.php';
} catch (Exception $e) {
	exit("ProfilePage component requires LocalLogin component!");
}


class ProfilePageModel extends Model {
	
	protected $local_login_model;
	
	public function __construct($db, $session) {
		parent::__construct($db, $session);
		
		$this->local_login_model = new LocalLoginModel($db, $session);
	}
	
	public function get_user_name() {
		return $this->session->user->get_name();
	}
	public function get_user_login() {
		return $this->local_login_model->get_user_login();
	}
	public function get_user_email() {
		return $this->local_login_model->get_user_email();
	}
	
	public function get_user_groups() {
		
		$result = array();
		
		foreach ($this->session->user->get_groups() as $group) {
			array_push($result, $group->get_name());
		}
			
		return $result;
	}
}