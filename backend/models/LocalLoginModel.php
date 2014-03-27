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
			$user_data = $this->db->select_local_login(
					$this->session->user->get_id());
			
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
		return crypt($password, $salt);
	}
	
	static public function is_password_good($password) {
		return strlen($password) > 1;
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