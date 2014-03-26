<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class GroupManagerController extends Controller {
	
	public function run() {
		if (!loadClass(
				REGIX_PATH."models/GroupManagerModel.php",
				"GroupManagerModel",
				"Model")) {
			return FALSE;
		}
		
		$model = new GroupManagerModel($this->db, $this->session);
		
		$editor_uri = $model->get_editor_uri($this->id);
		
		$view_outer = new View(
				REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		
		$view_outer->title = "Groups and Permissions - Regix";
		$view_outer->user_name = $model->get_user_name();
		
		if ($this->args[0] && $this->args[0] == "edit" && isset($this->args[1])) {
			
			// Group editor
			
			$group_data = $model->get_group_data(urldecode($this->args[1]));
			
			if ($group_data) {
				
				// Group exists
				
				$group_users = $model->get_group_users(
						urldecode($this->args[1]));
				
				$group_permissions = $model->get_group_permissions(
						urldecode($this->args[1]));
				
				$view_inner = new View(REGIX_PATH.
						"views/layouts/GroupManager/group_editor_xhtml.phtml");
				
				$view_inner->group_data = $group_data[0];
				$view_inner->group_users = $group_users;
				$view_inner->group_permissions = $group_permissions;
				
				$view_inner->editor_uri = $editor_uri;
				
			} else {
				
				// Wrong group name
				
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
					
				$view_inner->message = "Group does not exist.";
			}
			
		} else if ($this->args[0] && $this->args[0] == "save"
			&& isset($this->args[1]) && isset($_POST["submit"])) {
			/*
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
			}*/
			
		} else if ($this->args[0] && $this->args[0] == "save_new"
				&& isset($_POST["submit"])) {
				
			// Save new controller data
				/*
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
			}*/
			
		} else if ($this->args[0] && $this->args[0] == "delete" && isset($this->args[1])) {
		/*
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
			}*/
			
		} else if ($this->args[0] && $this->args[0] == "add") {
				/*
			// New controller editor
				
			$view_inner = new View(REGIX_PATH.
					"views/layouts/ControllerManager/controller_add_editor_xhtml.phtml");
			
			$view_inner->editor_uri = $editor_uri;*/		
		} else {
			
			// Group list
			
			$view_inner = new View(REGIX_PATH.
					"views/layouts/GroupManager/group_list_xhtml.phtml");
			
			$view_inner->groups = $model->get_group_array();
			$view_inner->editor_uri = $editor_uri;
		}
		
		$view_outer->content = $view_inner->render(FALSE);
		$view_outer->render(TRUE);
		
		return TRUE;
	}
}