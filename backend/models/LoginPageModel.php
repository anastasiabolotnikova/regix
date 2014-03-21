<?php
require_once REGIX_PATH.'models/Model.php';

try {
	require_once REGIX_PATH.'models/LocalLoginModel.php';
} catch (Exception $e) {
	exit("LoginPage component requires LocalLogin component!");
}

class LoginPageModel extends Model{
	
	protected $local_login_model;
	
	private function plaintextCheck($login, $password) {
		
		$ll_data = $this->db->select(
				"LocalLogin",
				array("User_id", "username", "salt", "hash", "email"),
				"issss",
				array("username" => $login), 1)[0];
		
		if (!$ll_data) {
			// Login not found
			return FALSE;
		}
		
		$salt = $ll_data['salt'];
		$hash_real = $ll_data['hash'];
		$hash_this = LocalLoginModel::password_hash($password, $salt);
		
		if ($hash_real == $hash_this) {
			// Login OK
			return $ll_data['User_id'];
		} else {
			// Wrong password.
			return FALSE;
		}
	}
	
	public function auth_plain($login, $password) {
		if ($user_id = $this->plaintextCheck($login, $password)) {
			$this->session->user = new User($user_id, $this->db);
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function logout() {
		$this->session->destroy();
	}
}