<?php
require_once 'Model.php';

class MainPageModel implements Model {
	public function get_title() {
		return "Regix";
	}
	
	public function  get_content() {
		return "Content";
	}
}