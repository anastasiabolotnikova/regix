<?php
require_once REGIX_PATH.'controllers/Controller.php';

class TestEnabledController extends Controller {
	
	public function run() {
		echo ("Test OK");
		return TRUE;
	}
}