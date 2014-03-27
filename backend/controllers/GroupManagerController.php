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
		
		$editor_uri = $this->get_controller_uri_name();
		
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