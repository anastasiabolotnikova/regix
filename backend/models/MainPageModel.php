<?php
require_once 'Model.php';

class MainPageModel implements Model {
	
	private $session;
	
	public function set_session($session) {
		$this->session = $session;
	}
	
	public function get_title() {
		return "Regix";
	}
	
	public function  get_content() {
		return "Content";
	}
	
	public function  get_user_name() {
		return $this->session->get('user')->get_name();
	}
}