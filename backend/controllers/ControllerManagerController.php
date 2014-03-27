<?php
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
		
		if ($this->args[0] && $this->args[0] == "edit" && isset($this->args[1])) {
			
			// Controller editor
			
			$controller_data = $model->get_controller_data($this->args[1]);
			
			if ($controller_data) {
				
				// Controller exists
				
				$view_inner = new View(REGIX_PATH.
						"views/layouts/ControllerManager/controller_editor_xhtml.phtml");
				
				$view_inner->controller_data = $controller_data[0];
				$view_inner->editor_uri = $editor_uri;
				
			} else {
				
				// Wrong id
				
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
					
				$view_inner->message = "Controller with given ID does not exist.";
			}
			
		} else if ($this->args[0] && $this->args[0] == "save"
			&& isset($this->args[1]) && isset($_POST["submit"])) {
			
			// Save controller data
			
			$controller_data_in = array(
					"name" => $_POST["name"],
					"description" => $_POST["description"],
					"enabled" => isset($_POST["enabled"]),
					"uri_name" => $_POST["uri_name"],
					"file_path" => $_POST["file_path"]
			);
				
			try {
				$model->set_controller_data($this->args[1], $controller_data_in);
			
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/success_generic_xhtml.phtml");
				$view_inner->message = "Controller modified";
			
			} catch (Exception $e) {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
			
				$view_inner->message = "Cannot modify controller: " . $e->getMessage();
			}
			
		} else if ($this->args[0] && $this->args[0] == "save_new"
				&& isset($_POST["submit"])) {
				
			// Save new controller data
				
			$controller_data_in = array(
					"name" => $_POST["name"],
					"description" => $_POST["description"],
					"enabled" => isset($_POST["enabled"]),
					"uri_name" => $_POST["uri_name"],
					"file_path" => $_POST["file_path"]
			);
		
			try {
				$model->add_controller($controller_data_in);
					
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/success_generic_xhtml.phtml");
				$view_inner->message = "Controller added";
					
			} catch (Exception $e) {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
					
				$view_inner->message = "Cannot add controller<br />(name must be unique)";
			}
			
		} else if ($this->args[0] && $this->args[0] == "delete" && isset($this->args[1])) {
		
			// Delete controller
		
			try {
				$model->delete_controller($this->args[1]);
					
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/success_generic_xhtml.phtml");
				$view_inner->message = "Controller deleted";
					
			} catch (Exception $e) {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
					
				$view_inner->message = "Cannot remove controller";
			}
			
		} else if ($this->args[0] && $this->args[0] == "add") {
				
			// New controller editor
				
			$view_inner = new View(REGIX_PATH.
					"views/layouts/ControllerManager/controller_add_editor_xhtml.phtml");
			
			$view_inner->editor_uri = $editor_uri;		
		} else {
			
			// Controller list
			
			$view_inner = new View(REGIX_PATH.
					"views/layouts/ControllerManager/controller_list_xhtml.phtml");
			
			$view_inner->controllers = $model->get_controller_array();
			$view_inner->editor_uri = $editor_uri;
		}
		
		$view_outer->content = $view_inner->render(FALSE);
		$view_outer->user_name = $model->get_user_name();
		$view_outer->render(TRUE);
		
		return TRUE;
	}
}