<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class PermissionManagerController extends Controller {
	
	public function run() {
		if (!loadClass(
				REGIX_PATH."models/PermissionManagerModel.php",
				"PermissionManagerModel",
				"Model")) {
			return FALSE;
		}
		
		$model = new PermissionManagerModel($this->db, $this->session);
		
		$view_outer = new View(
				REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		
		$view_outer->title = "Permissions - Regix";
		$view_outer->user_name = $model->get_user_name();
		
		if (!$this->args[0]) {
			
			if ($this->session->user->has_permission("list_permissions")) {
				
				// Permission list
				// 
				
				$view_inner = new View(REGIX_PATH.
						"views/layouts/PermissionManager/permission_list_xhtml.phtml");
				
				$view_inner->controller_uri_name =
						$this->get_controller_uri_name();
				$view_inner->permissions = $model->get_permission_array();
				
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}
			
		} else if ($this->args[0] == "edit" && isset($this->args[1]) &&
				isset($_POST["submit"])) {
		
			if ($this->session->user->has_permission("edit_permission")) {
				// Permission editor - save data
				// /edit_category/permission_name (with POST data)
			
				if ($model->update_permission(urldecode(
						$this->args[1]),
						$_POST["name"],
						$_POST["description"],
						$_POST["category_id"])) {
				
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/success_generic_xhtml.phtml");
				
					$view_inner->message = "Permission saved";
				
				} else {
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/failure_generic_xhtml.phtml");
				
					$view_inner->message = "Could not save permission";
				}
			
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}
			
		} else if ($this->args[0] == "edit" && isset($this->args[1])) {
			
			if ($this->session->user->has_permission("edit_permission")) {
		
				// Permission editor
				// /edit_category/permission_name
			
				$view_inner = new View(REGIX_PATH.
						"views/layouts/PermissionManager/permission_editor_xhtml.phtml");
			
				$view_inner->controller_uri_name =
						$this->get_controller_uri_name();
				$view_inner->permission_data =
						$model->get_permission_data(urldecode($this->args[1]));
				$view_inner->categories = $model->get_category_array();
			
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}

		} else if ($this->args[0] == "add" && isset($_POST["submit"])) {
			
			if ($this->session->user->has_permission("add_permission")) {
			
				// Add permission
				// /add (with POST data)
				
				if ($model->add_permission($_POST["name"],$_POST["description"],
						$_POST["category_name"])) {
					
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/success_generic_xhtml.phtml");
					
					$view_inner->message = "Permission added";
					
				} else {
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/failure_generic_xhtml.phtml");
					
					$view_inner->message = "Could not add permission";
				}
			
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}
			
		} else if ($this->args[0] == "add") {
			
			if ($this->session->user->has_permission("add_permission")) {
			
				// Add permission interface
				// /add
					
				$view_inner = new View(REGIX_PATH.
						"views/layouts/PermissionManager/permission_add_xhtml.phtml");
					
				$view_inner->controller_uri_name =
						$this->get_controller_uri_name();
				$view_inner->categories = $model->get_category_array();
			
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}
		
		} else if ($this->args[0] == "delete" && isset($this->args[1])) {
			
			if ($this->session->user->has_permission("delete_permission")) {
				
				// Delete permission
				// /delete/permission_name
				
				if ($model->delete_permission(urldecode($this->args[1]))) {
					
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/success_generic_xhtml.phtml");
					
					$view_inner->message = "Permission deleted";
					
				} else {
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/failure_generic_xhtml.phtml");
					
					$view_inner->message = "Could not delete permission";
				}
			
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}
			
		} else if ($this->args[0] == "categories") {
			
			if ($this->session->user->has_permission("list_permission_categories")) {
		
				// Permission categories
				// /categories
					
				$view_inner = new View(REGIX_PATH.
						"views/layouts/PermissionManager/permission_category_list_xhtml.phtml");
					
				$view_inner->controller_uri_name =
						$this->get_controller_uri_name();
				$view_inner->categories = $model->get_category_array();
			
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}
			
		} else if ($this->args[0] == "edit_category" && isset($this->args[1]) &&
				isset($_POST["submit"])) {
			
			if ($this->session->user->has_permission("edit_permission_category")) {
		
				// Permission category editor (save category)
				// /edit_category/category_id (with POST data)
				
				if ($model->save_permission_category($this->args[1], $_POST["name"])) {
				
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/success_generic_xhtml.phtml");
				
					$view_inner->message = "Category saved";
				
				} else {
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/failure_generic_xhtml.phtml");
				
					$view_inner->message = "Could not save category";
				}
			
				$view_inner->controller_uri_name =
						$this->get_controller_uri_name();
				$view_inner->category_data = $model->get_category_data($this->args[1]);
			
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}
		
		} else if ($this->args[0] == "edit_category" && isset($this->args[1])) {
			
			if ($this->session->user->has_permission("edit_permission_category")) {
		
				// Permission category editor
				// /edit_category/category_id
			
				$view_inner = new View(REGIX_PATH.
						"views/layouts/PermissionManager/permission_category_editor_xhtml.phtml");
			
				$view_inner->controller_uri_name =
				$this->get_controller_uri_name();
				$view_inner->category_data = $model->get_category_data($this->args[1]);
			
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}
		
		} else if ($this->args[0] == "delete_category" && isset($this->args[1])) {
			
			if ($this->session->user->has_permission("delete_permission_category")) {
		
				// Delete category
				// /delete_category/category_id
					
				if ($model->delete_permission_category($this->args[1])) {
			
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/success_generic_xhtml.phtml");
			
					$view_inner->message = "Category deleted";
			
				} else {
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/failure_generic_xhtml.phtml");
			
					$view_inner->message = "Could not delete category";
				}
			
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}
			
		} else if ($this->args[0] == "add_category" &&
				isset($_POST["submit"])) {
			
			if ($this->session->user->has_permission("add_permission_category")) {
		
				// Add permission category
				// /add_category (with POST data)
				
				if ($model->add_permission_category($_POST["name"])) {
				
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/success_generic_xhtml.phtml");
				
					$view_inner->message = "Category added";
				
				} else {
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/failure_generic_xhtml.phtml");
				
					$view_inner->message = "Could not add category";
				}
			
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}
			
		} else if ($this->args[0] == "add_category") {
			
			if ($this->session->user->has_permission("add_permission_category")) {
		
				// Add permission category interface
				// /add_category
			
				$view_inner = new View(REGIX_PATH.
						"views/layouts/PermissionManager/permission_category_add_xhtml.phtml");
			
				$view_inner->controller_uri_name =
						$this->get_controller_uri_name();
				
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}
			
		} else {
			
			$view_inner = new View(REGIX_PATH.
					"views/layouts/generic/failure_generic_xhtml.phtml");
				
			$view_inner->message = "Invalid request";
		}
		
		$view_outer->content = $view_inner->render(FALSE);
		$view_outer->render(TRUE);
		
		return TRUE;
	}
}