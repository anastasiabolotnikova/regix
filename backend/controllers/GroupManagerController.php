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
		
		$view_outer = new View(
				REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		
		$view_outer->title = "Groups and Permissions - Regix";
		$view_outer->user_name = $model->get_user_name();
		
		if (!$this->args[0]) {
			// Group list
			// /
			
			$view_inner = new View(REGIX_PATH.
					"views/layouts/GroupManager/group_list_xhtml.phtml");
			
			$view_inner->controller_uri_name =
					$this->get_controller_uri_name();
			$view_inner->groups = $model->get_group_array();
			
		} else if ($this->args[0] == "edit" && isset($this->args[1])) {
			
			// Group editor
			// /edit/group_name
			
			$group_data = $model->get_group_data(urldecode($this->args[1]));
			
			if ($group_data) {
				
				// Group exists
				
				$group_users = $model->get_group_users(
						urldecode($this->args[1]));
				
				$group_permissions = $model->get_group_permissions(
						urldecode($this->args[1]));
				
				$view_inner = new View(REGIX_PATH.
						"views/layouts/GroupManager/group_editor_xhtml.phtml");
				
				$view_inner->controller_uri_name = 
						$this->get_controller_uri_name();
				$view_inner->group_data = $group_data[0];
				$view_inner->group_users = $group_users;
				$view_inner->group_permissions = $group_permissions;
				
			} else {
				
				// Wrong group name
				
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
					
				$view_inner->message = "Group does not exist.";
			}
			
		} else if ($this->args[0] == "delete" && isset($this->args[1])) {
			
			// Delete group
			// /delete/group_name
			
			if ($model->delete_group($this->args[1])) {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/success_generic_xhtml.phtml");
				
				$view_inner->message = "Group deleted";
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				
				$view_inner->message = "Group does not exist";
			}
			
		} else if ($this->args[0] == "delete_user" &&
				isset($this->args[1]) && isset($this->args[2])) {
				
			// Delete group user
			// /delete_user/group_name/user_id
				
			if ($model->delete_group_user(
					urldecode($this->args[1]),
					$this->args[2])) {
				
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/success_generic_xhtml.phtml");
		
				$view_inner->message = "User removed from group";
				
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
		
				$view_inner->message = "User does not belong to this group";
			}
		
		} else if ($this->args[0] == "add_user" && isset($this->args[1])) {
		
			// Add user interface
			// /add_user/group_name
		
			$view_inner = new View(REGIX_PATH.
					"views/layouts/GroupManager/group_add_user_xhtml.phtml");
				
			$view_inner->controller_uri_name = $this->get_controller_uri_name();
			$view_inner->group_name = urldecode($this->args[1]);
			$view_inner->users_not_in_group = $model->get_users_not_in_group(
						urldecode($this->args[1]));
			
		} else if ($this->args[0] == "add_user_by_id" &&
				isset($this->args[1]) && isset($this->args[2])) {
		
			// Add group user by id
			// /add_user_by_id/group_name/user_id
		
			if ($model->add_group_user_by_id(urldecode($this->args[1]),
					$this->args[2])) {
		
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/success_generic_xhtml.phtml");
		
				$view_inner->message = "User added to group";
		
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
		
				$view_inner->message = "User could not be added";
			}
			
		} else if ($this->args[0] == "add_permission" &&
		isset($this->args[1]) && isset($this->args[2])) {
		
			// Add group permission
			// /add_permission/group_name/prmission_name
		
			if ($model->add_group_permission(urldecode($this->args[1]),
					urldecode($this->args[2]))) {
		
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/success_generic_xhtml.phtml");
		
				$view_inner->message = "Permission granted to group";
		
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
		
				$view_inner->message = "Permission could not be granted";
			}
		
		} else if ($this->args[0] == "add_permission" &&
		isset($this->args[1])) {
		
			// Add group permission interface
			// /add_permission/group_name
		
			$view_inner = new View(REGIX_PATH.
					"views/layouts/GroupManager/group_add_permission_xhtml.phtml");
				
			$view_inner->controller_uri_name = $this->get_controller_uri_name();
			$view_inner->group_name = urldecode($this->args[1]);
			$view_inner->permissions_not_granted =
			$model->get_permissions_not_granted(
					urldecode($this->args[1]));
		
		} else if ($this->args[0] == "delete_permission" &&
		isset($this->args[1]) && isset($this->args[2])) {
		
			// Delete (revoke) group permission
			// /delete_permission/group_name/prmission_name
		
			if ($model->delete_group_permission(urldecode($this->args[1]),
					urldecode($this->args[2]))) {
		
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/success_generic_xhtml.phtml");
		
				$view_inner->message = "Permission revoked from group";
		
			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
		
				$view_inner->message = "Permission could not be revoked";
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