<?php
require_once REGIX_PATH.'models/Model.php';

class LoginPageModel extends Model{
	
	static private function password_hash($password, $salt) {
		if (CRYPT_SHA512 == 1) {
			return crypt($password, '$6$rounds=5000$' . $salt . '$');
		}
	}
	
	static private function gen_salt($algo) {
		switch ($algo) {
			case "SHA512":
			default:
				$tpl = '$6$rounds=5000$%s$';
		}
		$result = sprintf($tpl, base64_encode(uniqid(mt_rand(), TRUE)));
		return $result;
	}
	
	private function plaintextCheck($login, $password) {
		$ll_data = $this->db->get_local_login_data($login);
		$user_id = $ll_data['User_id'];
		if ($user_id) {
			$salt = $ll_data['salt'];
			$hash_real = $ll_data['hash'];
			$hash_this = LoginPageModel::password_hash($password, $salt);
			if ($hash_real == $hash_this) {
				return $user_id;
			} else {
				// Wrong password.
				return FALSE;
			}
		} else {
			// User not in the database.
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