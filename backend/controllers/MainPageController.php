<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class MainPageController extends Controller{
	
	public function run() {
		if (loadClass(
				REGIX_PATH."models/MainPageModel.php",
				"MainPageModel",
				"Model")) {
			$model = new MainPageModel($this->db, $this->session);
		} else {
			return FALSE;
		}
		
		$view = new View(
				REGIX_PATH."views/layouts/layout_placeholder_html.phtml");
		
		$view->user_name = $model->get_user_name();
		$view->render(TRUE);
		return TRUE;
	}
}