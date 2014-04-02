<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class UserManagerController extends Controller {
	
	protected $model;
	
	public function __construct($id, $db, $session, $args) {
		parent::__construct($id, $db, $session, $args);
	
		if (!loadClass(
				REGIX_PATH."models/UserManagerModel.php",
				"UserManagerModel",
				"Model")) {
				throw new Exception("UserManagerModel missing.");
		}
	
		$this->model = new UserManagerModel($this->db, $this->session);
	}
	
	public function run() {
		if (!loadClass(
				REGIX_PATH."models/LocalLoginModel.php",
				"LocalLoginModel",
				"Model")) {
				
				// We do not USE this model, but need to be sure, that it is
				// available!
				return FALSE;
		}
		
		$view_outer = new View(
				REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		
		if (!$this->args[0]) { 
			
			if ($this->check_permission("list_users", "")) {
			
				// User list
				//
					
				$view_inner = new View(REGIX_PATH.
						"views/layouts/UserManager/user_list_xhtml.phtml");
					
				$view_inner->users = $this->model->get_user_array();
				$view_inner->editor_uri = $this->get_controller_uri_name();
				
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}
			
		} else if ($this->args[0] && $this->args[0] == "edit" && $this->args[1]) {
			
			if ($this->check_permission("edit_user", "edit/".$this->args[1])) {
				
				// User editor
				
				$view_inner = new View(REGIX_PATH.
						"views/layouts/UserManager/user_editor_xhtml.phtml");
				$view_inner->editor_uri = $this->get_controller_uri_name();
				$view_inner->edit_user = $this->model->get_user_data($this->args[1]);
				$view_inner->edit_user_id = $this->args[1];
				
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}
		
		} else if (	$this->args[0] &&
					$this->args[0] == "save" &&
					isset($this->args[1]) && 
					isset($_POST["submit"]) &&
					isset($_POST["name"]) &&
					isset($_POST["login"]) &&
					isset($_POST["email"]) &&
					isset($_POST["password"])) {
			
			if ($this->check_permission("edit_user", "save/".$this->args[1])) {
			
				// Save user
				
				$user_data_in = array(
						"name" => $_POST["name"],
						"login" => $_POST["login"],
						"email" => $_POST["email"],
						"password" => $_POST["password"],
						"groups" => (isset($_POST["user_groups"])?$_POST["user_groups"]:array()),
						
				);
				
				try {
					$this->model->set_user_data($this->args[1], $user_data_in);
					
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/success_generic_xhtml.phtml");
					$view_inner->message = "User modified";
					
				} catch (Exception $e) {
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/failure_generic_xhtml.phtml");
					
					$view_inner->message = "Cannot modify user: " . $e->getMessage();
				}
				
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}
			
		} else if ($this->args[0] && $this->args[0] == "delete"
				&& isset($this->args[1])) {
			
			if ($this->check_permission(
					"delete_user",
					"delete/".$this->args[1])) {
				
				// Delete user
				
				if ($this->args[1] == 1) {
					// Do not remove guest
					
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/failure_generic_xhtml.phtml");
					$view_inner->message = "Cannot remove default user";
					
				} else {
				
					$this->model->delete_user($this->args[1]);
					
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/success_generic_xhtml.phtml");
					$view_inner->message = "User deleted";
				}
				
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}
			
		} else if ($this->args[0] && $this->args[0] == "add") {
			
			if ($this->check_permission("delete_user", "add")) {
				
				// Add user
				header("Location: /reg");
				
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
		
		$view_outer->title = "Users - Regix";
		$view_outer->content = $view_inner->render(FALSE);
		
		$view_outer->user_name = $this->model->get_user_name();
		$view_outer->render(TRUE);
		
		return TRUE;
	}
}