<?php
require_once 'Model.php';

class TestDisabledModel implements Model {
	public function get_title() {
		return "Regix";
	}
	
	public function  get_content() {
		return "Test FAILED";
	}
}