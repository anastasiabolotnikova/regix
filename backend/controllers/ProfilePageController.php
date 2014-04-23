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
		
		$view_content = new View(
					REGIX_PATH."views/layouts/UserProfilePage/content_user_profile.phtml");
		$title = "Profile :: Regix";
		
		$view_outer = new View(REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		
		
		$view_content->user_name = $model->get_user_name();
		$view_content->user_login = $model->get_user_login();
		$view_content->user_email = $model->get_user_email();
		$view_content->user_group = $model->get_user_groups();
		
		$view_outer->user_name = $model->get_user_name();
		$view_outer->title = $title;
		$view_outer->content = $view_content->render(FALSE);
		
		$view_outer->render(TRUE);
		return TRUE;
	}
}