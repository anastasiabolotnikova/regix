<?php
require_once REGIX_PATH.'models/Model.php';

try {
	require_once REGIX_PATH.'models/LocalLoginModel.php';
} catch (Exception $e) {
	exit("LoginPage component requires LocalLogin component!");
}

class LoginPageModel extends Model{
	
	protected $local_login_model;
	
	private function plaintext_check($login, $password) {
		
		$ll_data = $this->db->select_local_login_by_login($login);
		
		if (!$ll_data) {
			// Login not found
			return FALSE;
		}
		
		$salt = $ll_data[0]['salt'];
		$hash_real = $ll_data[0]['hash'];
		$hash_this = LocalLoginModel::password_hash($password, $salt);
		
		if ($hash_real == $hash_this) {
			// Login OK
			return $ll_data[0]['user_id'];
		} else {
			// Wrong password.
			return FALSE;
		}
	}
	
	public function auth_plain($login, $password) {
		if ($user_id = $this->plaintext_check($login, $password)) {
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