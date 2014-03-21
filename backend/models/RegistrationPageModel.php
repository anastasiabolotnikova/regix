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
				"LocalLogin",
				array("User_id"),
				"i",
				array("username" => $login), 1);
		
		if ($login_in_db) {
			return FALSE;
		}
		
		// Check email
		
		$email_in_db = $this->db->select(
				"LocalLogin",
				array("User_id"),
				"i",
				array("email" => $email), 1);
		
		if ($email_in_db) {
			return FALSE;
		}
		
		// Check passwords
		
		if(strlen($password) < 1) {
			//Password is empty
			return FALSE;
		}
		
		if($password != $repassword) {
			//Passwords aren't the same
			return FALSE;
		}
		
		return TRUE;
	}
	
	public function save_data($name, $username, $password, $email) {
		
		$salt = LocalLoginModel::gen_salt("SHA512");
		$hashed_pass = LocalLoginModel::password_hash($password, $salt);
		
		$this->db->insert("User", array("name" => $name));
		$this->db->insert("LocalLogin",
				array(
						"user_id" 	=> $this->db->get_last_id(),
						"username" 	=> $username,
						"salt" 		=> $salt,
						"hash"		=> $hashed_pass,
						"email"		=> $email,
				));
	}
	
}