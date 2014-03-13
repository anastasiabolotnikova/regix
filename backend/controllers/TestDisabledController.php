<?php
require_once REGIX_PATH.'controllers/Controller.php';

class TestDisabledController extends Controller {
	
	public function run() {
		echo ("Test FAIL");
	}
}