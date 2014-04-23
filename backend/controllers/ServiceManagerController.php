<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

define("LAYOUTS", REGIX_PATH."views/layouts/ServiceManager/");

class ServiceManagerController extends Controller {
	
	public function run() {
		if (!loadClass(
				REGIX_PATH."models/ServiceManagerModel.php",
				"ServiceManagerModel",
				"Model")) {
			return FALSE;
		}
		
		$model = new ServiceManagerModel($this->db, $this->session);
		
		$view_outer = new View(
				REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		
		$view_outer->title = "Services - Regix";
		$view_outer->user_name = $model->get_user_name();
		
		if (!$this->args[0]) {
			
			if ($this->check_permission("list_groups", "")) {
				
				// Service list
				// /
				
				$view_inner = new View(LAYOUTS."service_list_xhtml.phtml");
				
				$view_inner->controller_uri_name =
						$this->get_controller_uri_name();
				$view_inner->services = $model->get_service_array();
				
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}
			
		} else if ($this->args[0] == "edit" && isset($this->args[1])) {
			
			if ($this->check_permission("edit_group", "edit/".$this->args[1])) {
			
				// Service editor
				// /edit/service_uri_name
				
				$service_data = $model->get_service_data(urldecode($this->args[1]));
				
				if ($service_data) {
					
					// Service exists
					
					$view_inner = new View(LAYOUTS."service_editor_xhtml.phtml");
					
					$view_inner->controller_uri_name = 
							$this->get_controller_uri_name();
					$view_inner->service_data = $service_data[0];
					$view_inner->service_groups = $model->get_service_groups(urldecode($this->args[1]));
					
				} else {
					
					// Wrong service name
					
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/failure_generic_xhtml.phtml");
						
					$view_inner->message = "Service does not exist.";
				}
				
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
					
				$view_inner->message = "Action forbidden";
			}
			
		} else {
			
			$view_inner = new View(REGIX_PATH.
					"views/layouts/generic/failure_generic_xhtml.phtml");
				
			$view_inner->message = "Invalid request.";
		}
		
		$view_outer->content = $view_inner->render(FALSE);
		$view_outer->render(TRUE);
		
		return TRUE;
	}
}