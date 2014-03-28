<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class CalendarPageController extends Controller{
	
	public function run() {
		
		if (loadClass(
				REGIX_PATH."models/CalendarPageModel.php",
				"CalendarPageModel",
				"Model")) {
			$model = new CalendarPageModel($this->db, $this->session);
		} else {
			return FALSE;
		}
		
		$view_content = new View(
					REGIX_PATH."views/layouts/CalendarPage/calendar_page.phtml");
		$title = "Registration :: Regix";
		
		$view_outer = new View(REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		
	}
}