<?php
require_once 'Controller.php';
require_once 'views/View.php';

class MainPageController extends Controller{
	
	public function run() {
		if (loadClass("models/MainPageModel.php", "MainPageModel", "Model")) {
			$model = new MainPageModel($this->db, $this->session);
		} else {
			return FALSE;
		}
		
		$view = new View("views/layouts/layout_placeholder_html.phtml");
		
		$view->user_name = $model->get_user_name();
		$view->render(TRUE);
		return TRUE;
	}
}