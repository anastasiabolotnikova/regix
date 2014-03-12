<?php
require_once 'Controller.php';

class TestEnabledController extends Controller {
	
	public function run() {
		echo ("Test OK");
		return TRUE;
	}
}