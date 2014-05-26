<?php
define('LAYOUTS', REGIX_PATH.'views/layouts/');
define("LAYOUT_FAILURE",
	REGIX_PATH."views/layouts/generic/failure_generic_xhtml.phtml");

require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';
require_once REGIX_PATH.'models/Model.php';


class AdminController extends Controller {
	
	public function run() {
		$model = new Model($this->db, $this->session);
		
		if (TRUE) {
				
			$view_inner = new View(LAYOUTS.'Admin/main_xhtml.phtml');
			
			$perms = array(
				"myplan" => $this->check_permission("myplan_own_plan", ""),
				"services" => $this->check_permission("list_services", ""),
				"events" => $this->check_permission("list_event", ""),
				"users" => $this->check_permission("list_users", ""),
				"groups" => $this->check_permission("list_groups", ""),
				"permissions" => $this->check_permission("list_permissions", ""),
				"controllers" => $this->check_permission("list_controllers", "")
			);
			
			$view_inner->show = array(
				"myplan" => $perms["myplan"],
				"profile" => $this->session->user->get_id() != 1,
				"logout" => $this->session->user->get_id() != 1,
				"personal" => $this->session->user->get_id() != 1,
				
				"services" => $perms["services"],
				"events" => $perms["events"],
				"services_and_events" => ($perms["services"] || $perms["events"]),
				
				"users" => $perms["users"],
				"groups" => $perms["groups"],
				"permissions" => $perms["permissions"],
				"users_and_groups" => $perms["users"] || $perms["groups"] || $perms["permissions"],
				
				"controllers" => $perms["controllers"],
				"system" => $perms["controllers"]
			);
			
		} else {
			$view_inner = new View(LAYOUT_FAILURE);
			$view_inner->message = "Action forbidden";
		}
		
		
		$view_outer = new View(
				REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		$view_outer->title = "Administration console :: Regix";
		$view_outer->user_name = $model->get_user_name();
		$view_outer->content = $view_inner->render(FALSE);
		$view_outer->render(TRUE);
		return TRUE;
	}
}