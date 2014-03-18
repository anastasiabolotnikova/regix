<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class ProfilePageController extends Controller{
	
	public function run() {
		if (loadClass(
				REGIX_PATH."models/ProfilePageModel.php",
				"ProfilePageModel",
				"Model")) {
			$model = new ProfilePageModel($this->db, $this->session);
		} else {
			return FALSE;
		}
		
		$view = new View(
				REGIX_PATH."views/layouts/content_user_profile.phtml");
		
		$view->user_name = $model->get_user_name();
		$view->user_email = $model->get_user_email();
		$view->user_group = $model->get_user_groups();
		$view->render(TRUE);
		return TRUE;
	}
}