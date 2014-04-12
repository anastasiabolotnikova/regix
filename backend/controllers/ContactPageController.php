<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class ContactPageController extends Controller{
	
	public function run() {
		
				$view_content = new View(
					REGIX_PATH."views/layouts/MainPage/contact_page.phtml");
		$title = "Registration :: Regix";
		
		$view_outer = new View(REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		

		$view_outer->title = $title;
		$view_outer->content = $view_content->render(FALSE);
		
		$view_outer->render(TRUE);
		return TRUE;
	}
}