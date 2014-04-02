<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class EventManagerController extends Controller {
	
	public function run() {
		if (!loadClass(
				REGIX_PATH."models/EventManagerModel.php",
				"EventManagerModel",
				"Model")) {
			return FALSE;
		}
		
		$model = new EventManagerModel($this->db, $this->session);
		
		$view_outer = new View(
				REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		
		$view_outer->title = "Events - Regix";
		$view_outer->user_name = $model->get_user_name();
		
		if (!$this->args[0]) {
			
			if ($this->session->user->has_permission("list_event")) {
				//show list of events from database
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}