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
			$title = "Logged out :: Regix";
			
		} else if (isset($_POST['login_b']) && $_POST['login_b'] == "Log In") {
			// Log in (form)
			if ($model->auth_plain($_POST['login'], $_POST['password'])) {
				// Logged in
				$view_content = new View(
						REGIX_PATH."views/layouts/LoginPage/content_login_success.phtml");
				$title = "Logged in :: Regix";
			} else {
				// Log in failed
				$view_content = new View(
						REGIX_PATH."views/layouts/LoginPage/content_login_failure.phtml");
				$title = "Wrong login or password :: Regix";
			}
		} else {
			// Show form
			$view_content = new View(
					REGIX_PATH."views/layouts/LoginPage/content_login_local.phtml");
			$title = "Login :: Regix";
		}
		
		$view = new View(REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		$view->title = $title;
		$view->content = $view_content->render(FALSE);
		
		$view->render(TRUE);
		return TRUE;
	}
}