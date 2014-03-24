<?php
require_once REGIX_PATH.'models/Model.php';

try {
	require_once REGIX_PATH.'models/LocalLoginModel.php';
} catch (Exception $e) {
	exit("RegistrationPage component requires LocalLogin component!");
}


class RegistrationPageModel extends Model{
	
	protected $local_login_model;
	
	public function plaintextCheck(
			$name,
			$login,
			$password,
			$repassword,
			$email) {
		
		// Check login
		
		if (strlen($login) < 1 || !ctype_alnum($login)) {
			// Not a valid login
			return FALSE;
		}
		
		$login_in_db = $this->db->select(
				"local_login",
				array("user_id"),
				"i",
				array("login" => $login), 1);
		
		if ($login_in_db) {
			return FALSE;
		}
		
		// Check email
		
		$email_in_db = $this->db->select(
				"local_login",
				array("user_id"),
				"i",
				array("email" => $email), 1);
		
		if ($email_in_db) {
			return FALSE;
		}
		
		// Check passwords
		
		if(!LocalLoginModel::is_password_good($password)) {
			//Password is bad
			return FALSE;
		}
		
		if($password != $repassword) {
			//Passwords aren't the same
			return FALSE;
		}
		
		return TRUE;
	}
	
	public function save_data($name, $login, $password, $email) {
		
		$salt = LocalLoginModel::gen_salt("SHA512");
		$hashed_pass = LocalLoginModel::password_hash($password, $salt);
		
		$this->db->insert("user", array("name" => $name));
		$this->db->insert("local_login",
				array(
						"user_id" 	=> $this->db->get_last_id(),
						"login" 	=> $login,
						"salt" 		=> $salt,
						"hash"		=> $hashed_pass,
						"email"		=> $email,
				));
	}
	
}