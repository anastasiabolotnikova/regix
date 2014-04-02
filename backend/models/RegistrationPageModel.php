<?php
/**
 * @file RegistrationPageModel.php
 * 
 * This file contains registration page model class.
 */

/**
 * This file extends Model class.
 */
require_once REGIX_PATH.'models/Model.php';

try {
	require_once REGIX_PATH.'models/LocalLoginModel.php';
} catch (Exception $e) {
	exit("RegistrationPage component requires LocalLogin component!");
}


class RegistrationPageModel extends Model{
	
	/**
	 * Checks validity of the data entered by a client into the registration form.
	 *
	 * @param unknown $name user's name.
	 * @param unknown $login username.
	 * @param unknown $password user's password.
	 * @param unknown $repassword retyped password.
	 * @param unknown $email user's email.
	 *
	 * @return bool TRUE if all the data entered by a client is valid, FALSE otherwise.
	 */
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
		
		$login_in_db = $this->db->select_local_login_by_login($login);
		
		if ($login_in_db) {
			return FALSE;
		}
		
		// Check email
		
		$email_in_db = $this->db->select_local_login_by_email($email);
		
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
	/**
	 * Saves data submited by a client to the database.
	 * Generates salt and hash for the password.
	 *
	 * @param unknown $name user's name.
	 * @param unknown $login username.
	 * @param unknown $password user's password.
	 * @param unknown $email user's email.
	 *
	 */
	public function save_data($name, $login, $password, $email) {
		
		$salt = LocalLoginModel::gen_salt("SHA512");
		$hashed_pass = LocalLoginModel::password_hash($password, $salt);
		
		$this->db->insert_user($name);
		$this->db->insert_local_login(
				$this->db->get_last_id(),
				$login,
				$salt,
				$hashed_pass,
				$email);
	}
	
}