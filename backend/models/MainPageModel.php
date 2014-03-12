<?php
require_once 'Model.php';

class MainPageModel extends Model {
	public function get_user_name() {
		return $this->session->user->get_name();
	}
}