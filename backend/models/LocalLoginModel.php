<?php
require_once REGIX_PATH.'models/Model.php';

class LocalLoginModel extends Model{
	
	protected $initialized = FALSE;
	
	protected $login;
	protected $salt;
	protected $hash;
	protected $email;
	
	protected function init() {
		if (!$this->initialized) {
			$user_data = $this->db->select(
					"local_login",
					array("user_id", "login", "salt", "hash", "email"),
					"issss",
					array("user_id" => $this->session->user->get_id()), 1);
			
			if ($user_data) {
				$this->login = $user_data[0]["login"];
				$this->salt = $user_data[0]["salt"];
				$this->hash = $user_data[0]["hash"];
				$this->email = $user_data[0]["email"];
			}
			$this->initialized = TRUE;
		}
	}
	
	static public function password_hash($password, $salt) {
		if (CRYPT_SHA512 == 1) {
			return crypt($password, '$6$rounds=5000$' . $salt . '$');
		}
	}
	
	static public function gen_salt($algo) {
		switch ($algo) {
			case "SHA512":
			default:
				$tpl = '$6$rounds=5000$%s$';
		}
		$result = sprintf($tpl, base64_encode(uniqid(mt_rand(), TRUE)));
		return $result;
	}
	
	public function get_user_login() {
		$this->init();
		return $this->login;
	}
	
	public function get_user_email() {
		$this->init();
		return $this->email;
	}
}