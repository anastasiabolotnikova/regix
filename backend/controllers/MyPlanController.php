<?php
define('LAYOUTS', REGIX_PATH.'views/layouts/MyPlan/');
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class MyPlanController extends Controller {
	
	public function run() {
		$view = new View(LAYOUTS.'plan_slotted_xhtml.phtml');
		$view->render(TRUE);
		
		return TRUE;
	}
}