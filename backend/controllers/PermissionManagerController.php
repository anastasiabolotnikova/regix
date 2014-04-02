<?php
define("LAYOUT_PATH", REGIX_PATH."views/layouts/PermissionManager/");
define("LAYOUT_SUCCESS",
		REGIX_PATH."views/layouts/generic/success_generic_xhtml.phtml");
define("LAYOUT_FAILURE",
		REGIX_PATH."views/layouts/generic/failure_generic_xhtml.phtml");

require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class PermissionManagerController extends Controller {
	
	protected $model;
	
	
	public function __construct($id, $db, $session, $args) {
		parent::__construct($id, $db, $session, $args);
		
		if (!loadClass(
				REGIX_PATH."models/PermissionManagerModel.php",
				"PermissionManagerModel",
				"Model")) {
				throw new Exception("PermissionManagerModel missing.");
		}
		
		$this->model = new PermissionManagerModel($this->db, $this->session);
	}
	
	
	public function permission_list() {
		if ($this->check_permission("list_permissions", "")) {
			$view_inner = new View(LAYOUT_PATH."permission_list_xhtml.phtml");
			$view_inner->controller_uri_name = $this->get_controller_uri_name();
			$view_inner->permissions = $this->model->get_permission_array();
		} else {
			$view_inner = new View(LAYOUT_FAILURE);
			$view_inner->message = "Action forbidden";
		}
		return $view_inner;
	}
	
	
	public function edit_permission_save_data() {
		if ($this->check_permission("edit_permission", "edit")) {
			if (isset($_POST["name"]) &&
				isset($_POST["description"]) &&
				isset($_POST["category_id"]) &&
				$this->model->update_permission(urldecode(
					$this->args[1]),
					$_POST["name"],
					$_POST["description"],
					$_POST["category_id"])) {
				$view_inner = new View(LAYOUT_SUCCESS);
				$view_inner->message = "Permission saved";
			} else {
				$view_inner = new View(LAYOUT_FAILURE);
				$view_inner->message = "Could not save permission";
			}
		} else {
			$view_inner = new View(LAYOUT_FAILURE);
			$view_inner->message = "Action forbidden";
		}
		return $view_inner;
	}
	
	
	public function edit_permission_editor() {
		if ($this->check_permission(
				"edit_permission",
				("edit/".$this->args[1]))) {
			$view_inner = new View(LAYOUT_PATH."permission_editor_xhtml.phtml");
			$view_inner->controller_uri_name = $this->get_controller_uri_name();
			$view_inner->permission_data = $this->model->get_permission_data(
					urldecode($this->args[1]));
			$view_inner->categories = $this->model->get_category_array();
		} else {
			$view_inner = new View(LAYOUT_FAILURE);
			$view_inner->message = "Action forbidden";
		}
		return $view_inner;
	}
	
	
	public function add_permission_save_data() {
		if ($this->check_permission("add_permission", "add")) {
			if (isset($_POST["name"]) &&
				isset($_POST["description"]) && 
				isset($_POST["category_name"]) &&
				$this->model->add_permission(
						$_POST["name"],
						$_POST["description"],
						$_POST["category_name"])) {
				$view_inner = new View(LAYOUT_SUCCESS);
				$view_inner->message = "Permission added";
			} else {
				$view_inner = new View(LAYOUT_FAILURE);
				$view_inner->message = "Could not add permission";
			}
		} else {
			$view_inner = new View(LAYOUT_FAILURE);
			$view_inner->message = "Action forbidden";
		}
		return $view_inner;
	}
	
	
	public function add_permission_editor() {
		if ($this->check_permission("add_permission", "add")) {
			$view_inner = new View(LAYOUT_PATH."permission_add_xhtml.phtml");
			$view_inner->controller_uri_name = $this->get_controller_uri_name();
			$view_inner->categories = $this->model->get_category_array();
		} else {
			$view_inner = new View(LAYOUT_FAILURE);
			$view_inner->message = "Action forbidden";
		}
		return $view_inner;
	}
	
	
	public function delete_permission() {
		if ($this->check_permission(
				"delete_permission",
				"delete/".$this->args[1])) {
			if ($this->model->delete_permission(urldecode($this->args[1]))) {
				$view_inner = new View(LAYOUT_SUCCESS);
				$view_inner->message = "Permission deleted";
			} else {
				$view_inner = new View(LAYOUT_FAILURE);
				$view_inner->message = "Could not delete permission";
			}
		} else {
			$view_inner = new View(LAYOUT_FAILURE);
			$view_inner->message = "Action forbidden";
		}
		return $view_inner;
	}
	
	
	public function category_list() {
		if ($this->check_permission(
				"list_permission_categories",
				"categories")) {
			$view_inner = new View(LAYOUT_PATH."permission_category_list_xhtml.phtml");
			$view_inner->controller_uri_name = $this->get_controller_uri_name();
			$view_inner->categories = $this->model->get_category_array();
		} else {
			$view_inner = new View(LAYOUT_FAILURE);
			$view_inner->message = "Action forbidden";
		}
		return $view_inner;
	}
	
	
	public function edit_category_save_data() {
		if ($this->check_permission(
				"edit_permission_category",
				"edit_category/".$this->args[1])) {
			if ($this->model->save_permission_category($this->args[1], $_POST["name"])) {
				$view_inner = new View(LAYOUT_SUCCESS);
				$view_inner->message = "Category saved";
			} else {
				$view_inner = new View(LAYOUT_FAILURE);
				$view_inner->message = "Could not save category";
			}
			$view_inner->controller_uri_name = $this->get_controller_uri_name();
			$view_inner->category_data = $this->model->get_category_data($this->args[1]);
		} else {
			$view_inner = new View(LAYOUT_FAILURE);
			$view_inner->message = "Action forbidden";
		}
		return $view_inner;
	}
	
	
	public function edit_category_editor() {
		if ($this->check_permission(
				"edit_permission_category",
				"edit_category/".$this->args[1])) {
			$view_inner = new View(LAYOUT_PATH."permission_category_editor_xhtml.phtml");
			$view_inner->controller_uri_name = $this->get_controller_uri_name();
			$view_inner->category_data = $this->model->get_category_data($this->args[1]);
		} else {
			$view_inner = new View(LAYOUT_FAILURE);
			$view_inner->message = "Action forbidden";
		}
		return $view_inner;
	}
	
	
	public function delete_category() {
		if ($this->session->user->has_permission("delete_permission_category")) {
			if ($this->model->delete_permission_category($this->args[1])) {
				$view_inner = new View(LAYOUT_SUCCESS);
				$view_inner->message = "Category deleted";
			} else {
				$view_inner = new View(LAYOUT_FAILURE);
				$view_inner->message = "Could not delete category";
			}
		} else {
			$view_inner = new View(LAYOUT_FAILURE);
			$view_inner->message = "Action forbidden";
		}
		return $view_inner;
	}
	
	
	public function add_category_save_data() {
		if ($this->check_permission(
				"add_permission_category",
				"add_category")) {
			if ($this->model->add_permission_category($_POST["name"])) {
				$view_inner = new View(LAYOUT_SUCCESS);
				$view_inner->message = "Category added";
			} else {
				$view_inner = new View(LAYOUT_FAILURE);
				$view_inner->message = "Could not add category";
			}
		} else {
			$view_inner = new View(LAYOUT_FAILURE);
			$view_inner->message = "Action forbidden";
		}
		return $view_inner;
	}
	
	
	public function add_category_editor() {
		if ($this->check_permission(
				"add_permission_category",
				"add_category")) {
			$view_inner = new View(LAYOUT_PATH."permission_category_add_xhtml.phtml");
			$view_inner->controller_uri_name = $this->get_controller_uri_name();
		} else {
			$view_inner = new View(LAYOUT_FAILURE);
			$view_inner->message = "Action forbidden";
		}
		return $view_inner;
	}
	
	
	public function run() {
		$view_outer = new View(
				REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		
		$view_outer->title = "Permissions - Regix";
		$view_outer->user_name = $this->model->get_user_name();
		
		if (!$this->args[0]) {
			// Permission list
			// no arguments
			$view_inner = $this->permission_list();
			
		} else if (
				$this->args[0] == "edit" && 
				isset($this->args[1]) &&
				isset($_POST["submit"])
				) {
			
			// Permission editor - save data
			// /edit/permission_name (with POST data)
			
			$view_inner = $this->edit_permission_save_data();
			
		} else if (
				$this->args[0] == "edit" && 
				isset($this->args[1])
				) {
			
			// Permission editor
			// /edit/permission_name
			
			$view_inner = $this->edit_permission_editor();

		} else if (
				$this->args[0] == "add" && 
				isset($_POST["submit"])
				) {
			
			// Add permission
			// /add (with POST data)
			
			$view_inner = $this->add_permission_save_data();
			
		} else if ($this->args[0] == "add") {
			
			// Add permission interface
			// /add
			
			$view_inner = $this->add_permission_editor();
		
		} else if (
				$this->args[0] == "delete" && 
				isset($this->args[1])
				) {
			
			// Delete permission
			// /delete/permission_name
			
			$view_inner = $this->delete_permission();
			
		} else if ($this->args[0] == "categories") {
			
			// Permission categories
			// /categories
			
			$view_inner = $this->category_list();
			
		} else if (
				$this->args[0] == "edit_category" &&
				isset($this->args[1]) &&
				isset($_POST["submit"])
				) {
			
			// Permission category editor (save category)
			// /edit_category/category_id (with POST data)
			
			$view_inner = $this->edit_category_save_data();
		
		} else if (
				$this->args[0] == "edit_category" &&
				isset($this->args[1])
				) {
			
			// Permission category editor
			// /edit_category/category_id
			
			$view_inner = $this->edit_category_editor();
		
		} else if ($this->args[0] == "delete_category" && isset($this->args[1])) {
			// Delete category
			// /delete_category/category_id
			$view_inner = $this->delete_category();
			
		} else if (
				$this->args[0] == "add_category" &&
				isset($_POST["submit"])
				) {
		
			// Add permission category
			// /add_category (with POST data)
			
			$view_inner = $this->add_category_save_data();
			
		} else if ($this->args[0] == "add_category") {
			
			// Add permission category interface
			// /add_category
			
			$view_inner = $this->add_category_editor();
			
		} else {
			$view_inner = new View(LAYOUT_FAILURE);
			$view_inner->message = "Invalid request";
		}
		
		$view_outer->content = $view_inner->render(FALSE);
		$view_outer->render(TRUE);
		
		return TRUE;
	}
}