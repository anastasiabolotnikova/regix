<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';
require_once REGIX_PATH.'models/Model.php';

class ContactPageController extends Controller{
	
	public function run() {
		$model = new Model($this->db, $this->session);
		
		$view_outer = new View(
				REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		
		$view_inner = new View(
				REGIX_PATH."views/layouts/ContactPage/contact_page.phtml");
		
		$view_outer->title = "Contact";
		$view_outer->user_name = $model->get_user_name();
		$view_outer->content = $view_inner->render(FALSE);
		$view_outer->render(TRUE);
		return TRUE;
	}
}