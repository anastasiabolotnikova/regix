<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class RegistrationPageController extends Controller{
	
	public function run() {
		if (loadClass(
				REGIX_PATH."models/RegistrationPageModel.php",
				"RegistrationPageModel",
				"Model")) {
			$model = new RegistrationPageModel($this->db, $this->session);
		} else {
			return FALSE;
		}
		
		if (isset($_POST['reg_user']) && $_POST['reg_user'] == "Register") {
			if ($model->auth_plain($_POST['login'], $_POST['password'])) {
				// Registered
				$view_content = new View(
						REGIX_PATH."views/layouts/RegistrationPage/content_registration_success.phtml");
				$title = "Registered :: Regix";
			} else {
				// Registration failed
				$view_content = new View(
						REGIX_PATH."views/layouts/RegistrationPage/content_registration_failure.phtml");
				$title = "Registration failed :: Regix";
			}
		} else {
			// Show form
			$view_content = new View(
					REGIX_PATH."views/layouts/RegistrationPage/content_registration_form.phtml");
			$title = "Registration :: Regix";
		}
		
		$view = new View(REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		$view->title = $title;
		$view->content = $view_content->render(FALSE);
		
		$view->render(TRUE);
		return TRUE;
	}
}