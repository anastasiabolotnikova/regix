<?php

define("LAYOUT_PATH", REGIX_PATH."views/layouts/ControllerManager/");
define("LAYOUT_SUCCESS",
		REGIX_PATH."views/layouts/generic/success_generic_xhtml.phtml");
define("LAYOUT_FAILURE",
		REGIX_PATH."views/layouts/generic/failure_generic_xhtml.phtml");

require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class ControllerManagerController extends Controller {
	
	public function run() {
		if (!loadClass(
				REGIX_PATH."models/ControllerManagerModel.php",
				"ControllerManagerModel",
				"Model")) {
			return FALSE;
		}
		
		$model = new ControllerManagerModel($this->db, $this->session);
		
		$editor_uri = $this->get_controller_uri_name();
		
		$view_outer = new View(
				REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		$view_outer->title = "Controllers - Regix";
		
		if (!isset($this->args[0]) || !$this->args[0]) {
			if ($this->check_permission("list_controllers", "")) {
				// Controller list
				// default
			
				$view_inner = new View(LAYOUT_PATH."controller_list_xhtml.phtml");
			
				$view_inner->controllers = $model->get_controller_array();
				$view_inner->editor_uri = $editor_uri;
			} else {
				$view_inner = new View(LAYOUT_FAILURE);
				$view_inner->message = "Action forbidden";
			}
		} else if ($this->args[0] == "edit" && isset($this->args[1])) {
			
			if ($this->check_permission(
					"edit_controller",
					"edit/".$this->args[1])) {
				
				// Controller editor
				// /edit/controller_id
				
				$controller_data = $model->get_controller_data($this->args[1]);
				
				if ($controller_data) {
					// Controller exists
					$view_inner = new View(LAYOUT_PATH."controller_editor_xhtml.phtml");
					$view_inner->controller_data = $controller_data[0];
					$view_inner->editor_uri = $editor_uri;
				} else {
					// Wrong id
					$view_inner = new View(LAYOUT_FAILURE);
					$view_inner->message = "Controller with given ID does not exist.";
				}
			} else {
				$view_inner = new View(LAYOUT_FAILURE);
				$view_inner->message = "Action forbidden";
			}
			
		} else if (	$this->args[0] == "save" && 
					isset($this->args[1]) && 
					isset($_POST["submit"]) &&
					isset($_POST["name"]) &&
					isset($_POST["description"]) &&
					isset($_POST["uri_name"]) &&
					isset($_POST["file_path"])
					) {
			
			if ($this->check_permission(
					"edit_controller",
					"edit/".$this->args[1])) {
			
				// Save controller data
				// /save/controller_id (with POST data)
				
				$controller_data_in = array(
						"name" => $_POST["name"],
						"description" => $_POST["description"],
						"enabled" => isset($_POST["enabled"]),
						"uri_name" => $_POST["uri_name"],
						"file_path" => $_POST["file_path"]
				);
				
				try {
					$model->set_controller_data($this->args[1], $controller_data_in);
					$view_inner = new View(LAYOUT_SUCCESS);
					$view_inner->message = "Controller modified";
				} catch (Exception $e) {
					$view_inner = new View(LAYOUT_FAILURE);
					$view_inner->message = "Cannot modify controller: " . $e->getMessage();
				}
			} else {
				$view_inner = new View(LAYOUT_FAILURE);
				$view_inner->message = "Action forbidden";
			}
			
		} else if (	$this->args[0] == "save_new" &&
					isset($_POST["submit"]) &&
					isset($_POST["name"]) &&
					isset($_POST["description"]) &&
					isset($_POST["uri_name"]) &&
					isset($_POST["file_path"])) {
			
			if ($this->check_permission("add_controller", "save_new")) {
				
				// Save new controller data
				// /save_new (with POST data)
					
				$controller_data_in = array(
						"name" => $_POST["name"],
						"description" => $_POST["description"],
						"enabled" => isset($_POST["enabled"]),
						"uri_name" => $_POST["uri_name"],
						"file_path" => $_POST["file_path"]
				);
			
				try {
					$model->add_controller($controller_data_in);
					$view_inner = new View(LAYOUT_SUCCESS);
					$view_inner->message = "Controller added";
				} catch (Exception $e) {
					$view_inner = new View(LAYOUT_FAILURE);
					$view_inner->message = "Cannot add controller<br />(name must be unique)";
				}
			} else {
				$view_inner = new View(LAYOUT_FAILURE);
				$view_inner->message = "Action forbidden";
			}
			
		} else if (	$this->args[0] == "delete" &&
					isset($this->args[1])) {
		
			if ($this->check_permission(
					"delete_controller",
					"delete/".$this->args[1])) {
				// Delete controller
				// /delete/controller_id
			
				try {
					$model->delete_controller($this->args[1]);
					$view_inner = new View(LAYOUT_SUCCESS);
					$view_inner->message = "Controller deleted";
				} catch (Exception $e) {
					$view_inner = new View(LAYOUT_FAILURE);
					$view_inner->message = "Cannot remove controller";
				}
			} else {
				$view_inner = new View(LAYOUT_FAILURE);
				$view_inner->message = "Action forbidden";
			}
			
		} else if ($this->args[0] == "add") {
			if ($this->check_permission("add_controller", "add")) {	
				// New controller editor
				// /add
					
				$view_inner = new View(LAYOUT_PATH."controller_add_editor_xhtml.phtml");
				$view_inner->editor_uri = $editor_uri;
			} else {
				$view_inner = new View(LAYOUT_FAILURE);
				$view_inner->message = "Action forbidden";
			}
			
		} else {
			$view_inner = new View(LAYOUT_FAILURE);
			$view_inner->message = "Invalid request";
		}
		
		$view_outer->content = $view_inner->render(FALSE);
		$view_outer->user_name = $model->get_user_name();
		$view_outer->render(TRUE);
		
		return TRUE;
	}
}