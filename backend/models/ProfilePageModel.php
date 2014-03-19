<?php
require_once REGIX_PATH.'models/Model.php';

class ProfilePageModel extends Model {
	public function get_user_name() {
		return $this->session->user->get_name();
	}
	public function get_user_email() {
		return $this->session->user->get_email();//Add method to user
	}
	public function get_user_group() {
		return $this->session->user->get_group();
	}
}