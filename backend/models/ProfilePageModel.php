<?php
require_once REGIX_PATH.'models/Model.php';

class ProfilePageModel extends Model {
	public function get_user_name() {
		return $this->session->user->get_name();
	}
	public function get_user_login() {
		return $this->session->user->get_login();
	}
	public function get_user_email() {
		return $this->session->user->get_email();
	}
	public function get_user_groups() {
		
		$result = array();
		
		foreach ($this->session->user->get_groups() as $group) {
			array_push($result, $group->get_name());
		}
			
		return $result;
	}
}