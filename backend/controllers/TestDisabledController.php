<?php
require_once 'Controller.php';

class TestDisabledController extends Controller {
	
	public function run() {
		echo ("Test FAIL");
	}
}