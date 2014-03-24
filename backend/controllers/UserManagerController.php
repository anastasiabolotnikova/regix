<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class UserManagerController extends Controller {
	
	public function run() {
		if (!loadClass(
				REGIX_PATH."models/UserManagerModel.php",
				"UserManagerModel",
				"Model")) {
			return FALSE;
		}
		
		if (!loadClass(
				REGIX_PATH."models/LocalLoginModel.php",
				"LocalLoginModel",
				"Model")) {
				
				// We do not USE this model, but need to be sure, that it is
				// available!
				return FALSE;
		}
		
		$model = new UserManagerModel($this->db, $this->session);
		
		$view_outer = new View(
				REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		
		$view_outer->title = "User Account Control - Regix";
		
		if ($this->args[0] && $this->args[0] == "edit" && $this->args[1]) {
			
			// User editor
			
			$view_inner = new View(REGIX_PATH.
					"views/layouts/UserManager/user_editor_xhtml.phtml");
			$view_inner->editor_uri = $model->get_editor_uri($this->id);
			$view_inner->edit_user = $model->get_user_data($this->args[1]);
			$view_inner->edit_user_id = $this->args[1];
			
			$view_outer->title = "Edit User - Regix";
			$view_outer->content = $view_inner->render(FALSE);
		
		} else if ($this->args[0] && $this->args[0] == "save"
			&& isset($this->args[1]) && isset($_POST["submit"])) {
			
			// Save user
			
			$user_data_in = array(
					"name" => $_POST["name"],
					"login" => $_POST["login"],
					"email" => $_POST["email"],
					"password" => $_POST["password"],
					"groups" => (isset($_POST["user_groups"])?$_POST["user_groups"]:array()),
					
			);
			
			try {
				$model->set_user_data($this->args[1], $user_data_in);
				
				$view_inner = new View(REGIX_PATH.
						"views/layouts/UserManager/user_manager_success_generic_xhtml.phtml");
				$view_inner->message = "User modified";
				
				$view_outer->content = $view_inner->render(FALSE);
				
			} catch (Exception $e) {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/UserManager/user_manager_failure_generic_xhtml.phtml");
				
				$view_inner->message = "Cannot modify user: " . $e->getMessage();
				
				$view_outer->title = "User Account Control - Regix";
				$view_outer->content = $view_inner->render(FALSE);
			}
			
			
			
		} else if ($this->args[0] && $this->args[0] == "delete"
				&& isset($this->args[1])) {
			
			// Delete user
			
			if ($this->args[1] == 1) {
				// Do not remove guest
				
				$view_inner = new View(REGIX_PATH.
						"views/layouts/UserManager/user_manager_failure_generic_xhtml.phtml");
				$view_inner->message = "Cannot remove default user";
				
				$view_outer->title = "User Deleted - Regix";
				$view_outer->content = $view_inner->render(FALSE);
				
			} else {
			
				$model->delete_user($this->args[1]);
				
				$view_inner = new View(REGIX_PATH.
						"views/layouts/UserManager/user_manager_success_generic_xhtml.phtml");
				$view_inner->message = "User deleted";
				
				$view_outer->title = "User Deleted - Regix";
				$view_outer->content = $view_inner->render(FALSE);
				
			}
		} else if ($this->args[0] && $this->args[0] == "add") {
				
			// Add user
			header("Location: /reg");
		
		} else {
			
			// User list
			
			$view_inner = new View(REGIX_PATH.
					"views/layouts/UserManager/user_list_xhtml.phtml");
			
			$view_inner->users = $model->get_user_array();
			$view_inner->editor_uri = $model->get_editor_uri($this->id);
			
			$view_outer->title = "User Account Control - Regix";
			$view_outer->content = $view_inner->render(FALSE);
		}
		
		$view_outer->user_name = $model->get_user_name();
		$view_outer->render(TRUE);
		
		return TRUE;
	}
}