<?php
require_once REGIX_PATH.'models/Model.php';

class RegistrationPageModel extends Model{
	
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
	
	private function plaintextCheck($username, $password, $repassword, $email) {
		//check if username is unique and has more than 3 symbols
		//check if password and repassword are the same
		//check if email is email and there is no such email in database
		}
	}
	
	public function save_data($username, $password, $repassword, $email) {
		//insert data from the form to the database
	}
	
}