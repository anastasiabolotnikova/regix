<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class LoginPageController extends Controller{
	
	public function run() {
		if (loadClass(
				REGIX_PATH."models/LoginPageModel.php",
				"LoginPageModel",
				"Model")) {
			$model = new LoginPageModel($this->db, $this->session);
		} else {
			return FALSE;
		}
		
		if ($this->args[0] == 'logout') {
			// Log out
			$model->logout();
			$view_content = new View(
					REGIX_PATH."views/layouts/LoginPage/content_logout.phtml");
			
		} else if (isset($_POST['login'])) {
			// Log in (form)
			if ($model->auth_plain($_POST['login'], $_POST['password'])) {
				// Logged in
				$view_content = new View(
						REGIX_PATH."views/layouts/LoginPage/content_login_success.phtml");
				
				if (isset($_POST["redirect_to"])) {
					header("Location: /".base64_decode($_POST["redirect_to"]));
					return TRUE;
				}
			} else {
				// Log in failed
				$view_content = new View(
						REGIX_PATH."views/layouts/LoginPage/content_login_failure.phtml");
			}
		} else {
			// Show form
			$view_content = new View(
					REGIX_PATH."views/layouts/LoginPage/content_login_local.phtml");
			if ($this->args) {
				$view_content->redirect_to = implode("/", $this->args);
			} else {
				$view_content->redirect_to = NULL;
			}
		}
		
		$view_outer = new View(REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		$view_outer->title = "Sign In - Regix";
		$view_outer->user_name = $model->get_user_name();
		$view_outer->content = $view_content->render(FALSE);
		
		$view_outer->render(TRUE);
		return TRUE;
	}
}