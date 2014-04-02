<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class LatestController extends Controller {
	
	public function run() {
		if (!loadClass(
				REGIX_PATH."models/LatestModel.php",
				"LatestModel",
				"Model")) {
			return FALSE;
		}
		
		$model = new LatestModel($this->db, $this->session);
		
		$view_outer = new View(
				REGIX_PATH."views/layouts/layout_basic_xml.phtml");
		$view_inner = new View(
				REGIX_PATH."views/layouts/Latest/latest_events_xml.phtml");
		$view_inner->events = $model->get_added_events(10);
		$view_outer->content = $view_inner->render(FALSE);
		$view_outer->render(TRUE);
		
		return TRUE;
	}
}