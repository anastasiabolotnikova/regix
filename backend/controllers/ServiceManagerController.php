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
			
			if ($this->check_permission("list_services", "")) {
				
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
			
			if ($this->check_permission("edit_service", "edit/".$this->args[1])) {
			
				// Service editor
				// /edit/service_uri_name
				
				$service_data = $model->get_service_data(urldecode($this->args[1]));
				
				if ($service_data) {
					
					// Service exists
					
					$view_inner = new View(LAYOUTS."service_editor_xhtml.phtml");
					
					$view_inner->controller_uri_name = 
							$this->get_controller_uri_name();
					$view_inner->save_uri = "save";
					$view_inner->service_data = $service_data[0];
					$view_inner->service_groups = $model->get_service_groups(urldecode($this->args[1]));
					$view_inner->editing_existing = TRUE;
					
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
			
		} else if ($this->args[0] == "save" && isset($this->args[1])) {
				
			if ($this->check_permission("edit_service", "edit/".$this->args[1])) {
					
				// Save service
				// /save/service_uri_name
				
				if ($model->save_service_data(
						urldecode($this->args[1]),
						$_POST["uri_name"],
						$_POST["name"],
						$_POST["on_success_uri"],
						$_POST["on_failure_uri"])) {
					
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/success_generic_xhtml.phtml");
						
					$view_inner->message = "Service modified";
					
				} else {
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/failure_generic_xhtml.phtml");
						
					$view_inner->message = "Service was not changed";
				}
		
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
					
				$view_inner->message = "Action forbidden";
			}
		
		} else if ($this->args[0] == "add") {
		
			if ($this->check_permission("add_service", "add")) {
					
				$view_inner = new View(LAYOUTS."service_editor_xhtml.phtml");
					
				$view_inner->controller_uri_name = $this->get_controller_uri_name();
				$view_inner->save_uri = "save_new";
				$view_inner->service_data = array(
					"name" => "New Service",
					"uri_name" => "",
					"on_success_uri" => "",
					"on_failure_uri" => ""
				);
				$view_inner->service_groups = array();
				$view_inner->editing_existing = FALSE;
		
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
					
				$view_inner->message = "Action forbidden";
			}
			
		} else if ($this->args[0] == "save_new" && isset($this->args[1])) {
		
			if ($this->check_permission("add_service", "add")) {
					
				// Save new service
				// /save_new/service_uri_name
		
				if ($model->save_new_service_data(
						$_POST["uri_name"],
						$_POST["name"],
						$_POST["on_success_uri"],
						$_POST["on_failure_uri"])) {
						
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/success_generic_xhtml.phtml");
		
					$view_inner->message = "Service added";
						
				} else {
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/failure_generic_xhtml.phtml");
		
					$view_inner->message = "Service could not be added";
				}
		
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
					
				$view_inner->message = "Action forbidden";
			}
		
		} else if ($this->args[0] == "delete" && isset($this->args[1])) {
		
			if ($this->check_permission("delete_service", "add")) {
					
				// Delete service
				// /delete/service_uri_name
		
				if ($model->delete_service(urldecode($this->args[1]))) {
		
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/success_generic_xhtml.phtml");
		
					$view_inner->message = "Service deleted";
		
				} else {
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/failure_generic_xhtml.phtml");
		
					$view_inner->message = "Could not delete service";
				}
		
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
					
				$view_inner->message = "Action forbidden";
			}
			
		} else if ($this->args[0] == "delete_group" &&
				isset($this->args[1]) &&
				isset($this->args[2])) {
		
			if ($this->check_permission("edit_service", "edit/".$this->args[1])) {
					
				// Unassign group from service
				// /delete_group/service_uri_name/group_name
		
				if ($model->delete_group_from_service(urldecode($this->args[1]), 
						urldecode($this->args[2]))) {
		
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/success_generic_xhtml.phtml");
		
					$view_inner->message = "Group removed from service";
		
				} else {
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/failure_generic_xhtml.phtml");
		
					$view_inner->message = "Could not remove group from service";
				}
		
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
					
				$view_inner->message = "Action forbidden";
			}
		
		} else if ($this->args[0] == "add_group" &&
		isset($this->args[1]) &&
		!isset($this->args[2])) {
		
			if ($this->check_permission("edit_service", "edit/".$this->args[1])) {
					
				// Assign group to service (interface)
				// /add_group/service_uri_name
		
				$view_inner = new View(LAYOUTS."service_add_group_xhtml.phtml");
					
				$view_inner->controller_uri_name = $this->get_controller_uri_name();
				$view_inner->groups_not_assigned = $model->
						get_groups_not_assigned_to_service(urldecode($this->args[1]));
				
				$service_data = $model->get_service_data(urldecode($this->args[1]));
				
				$view_inner->service_name = $service_data[0]["name"];
				$view_inner->service_uri_name = $this->args[1];
		
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
					
				$view_inner->message = "Action forbidden";
			}
		
		} else if ($this->args[0] == "add_group" &&
		isset($this->args[1]) &&
		isset($this->args[2])) {
		
			if ($this->check_permission("edit_service", "edit/".$this->args[1])) {
					
				// Assign group to service
				// /add_group/service_uri_name/group_name
		
				if ($model->add_group_to_service(urldecode($this->args[1]),
						urldecode($this->args[2]))) {
				
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/success_generic_xhtml.phtml");
				
					$view_inner->message = "Group assigned to service";
				
				} else {
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/failure_generic_xhtml.phtml");
				
					$view_inner->message = "Could not assign group to service";
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