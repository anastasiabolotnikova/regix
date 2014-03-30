<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class CalendarController extends Controller{
	
	public function run() {
		if (loadClass(
				REGIX_PATH."models/CalendarModel.php",
				"CalendarModel",
				"Model")) {
			$model = new CalendarModel($this->db, $this->session);
		} else {
			return FALSE;
		}
		
		$view_content = new View(
					REGIX_PATH."views/layouts/CalendarOverview/calendar_page.phtml");
		$title = "Calendar :: Regix";
		
		$view_content->day = $model->get_day();
		$view_content->month = $model->get_month();
		$view_content->year = $model->get_year();
		$view_content->wd = $model->get_wd();
		
		//echo $view_content->wd;
		
		$view_outer = new View(REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		$view_outer->user_name = $model->get_user_name();
		$view_outer->title = $title;
		$view_outer->content = $view_content->render(FALSE);
		
		$view_outer->render(TRUE);
		return TRUE;
	}
}