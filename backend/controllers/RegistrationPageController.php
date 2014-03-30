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
			
			if ($model->plaintextCheck(
					$_POST['name'],
					$_POST['username'],
					$_POST['password'],
					$_POST['repassword'],
					$_POST['email'])) {
				
				// Register
				
				$model->save_data(
						$_POST['name'],
						$_POST['username'],
						$_POST['password'],
						$_POST['email']);
				
				$view_content = new View(
						REGIX_PATH."views/layouts/RegistrationPage/content_registration_success.phtml");
				$title = "Registered :: Regix";
				$view_content->user_name = $_POST['name'];
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
		
		$view_outer = new View(REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		$view_outer->title = $title;
		$view_outer->user_name = $model->get_user_name();
		$view_outer->content = $view_content->render(FALSE);
		
		$view_outer->render(TRUE);
		return TRUE;
	}
}