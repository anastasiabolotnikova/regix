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
	
	public function plaintextCheck($name, $login, $password, $repassword, $email) {
		$ll_data = $this->db->get_local_login_data($login);
		$user_id = $ll_data['user_id'];
		$username = $ll_data['username'];
		$e_mail = $ll_data['email'];
		if($user_id){
			//If there already is this username
			if($login == $username){
				return False;
			}
		}
		//Passwords aren't same
		if($password != $repassword){
			//here we should control if password is strong enough
			return False;
		}
		//User with this e-mail already exists
		if($email == $e_mail){
			return False;
		}
		return True;
		}
	
	public function save_data($name, $username, $password, $email) {
		$this->db->connect();
		$salt = RegistrationPageModel::gen_salt("");
		$hashed_pass = RegistrationPageModel::password_hash($password, $salt);
		$this->db->insert_local_login_data($username, $hashed_pass, $salt, $email);
		$this->db->insert_user_data($name);
	}
	
}