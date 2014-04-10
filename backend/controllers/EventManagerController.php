<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class EventManagerController extends Controller {
	
	public function run() {
		if (!loadClass(
				REGIX_PATH."models/EventManagerModel.php",
				"EventManagerModel",
				"Model")) {
			return FALSE;
		}
		
		$model = new EventManagerModel($this->db, $this->session);
		
		$view_outer = new View(
				REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		
		$view_outer->title = "Events - Regix";
		$view_outer->user_name = $model->get_user_name();
		
		if (!$this->args[0]) {
			
			if ($this->check_permission("list_event", "")) {
				//show list of events from database
				
				$view_inner = new View(REGIX_PATH.
						"views/layouts/EventManager/event_manager_list.phtml");
				$view_inner->events = $model->get_events_array();
				$view_inner->editor_uri = $this->get_controller_uri_name();

			} else {
				$view_inner = new View(REGIX_PATH.
						"views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Action forbidden";
			}
		} else if($this->args[0] == "edit" && isset($this->args[1])){
			//args[1]=event.id that we want to edit
			//check permissions if needed
			
			$events_array = $model->get_events_array();
			
			if($events_array){
				$view_inner = new View(REGIX_PATH.
							"views/layouts/EventManager/event_manager_edit.phtml");
				$view_inner->editor_uri =  $this->get_controller_uri_name();
				$view_inner->edit_event = $model->get_event_data($this->args[1]);
				$view_inner->edit_event_id = $this->args[1];
			} else {
				$view_inner = new View(REGIX_PATH."views/layouts/generic/failure_generic_xhtml.phtml");
				$view_inner->message = "Event does not exist.";
				}
				
		} else if (	$this->args[0] &&
					$this->args[0] == "save" &&
					isset($this->args[1]) && 
					isset($_POST["submit"]) &&
					isset($_POST["from"]) &&
					isset($_POST["to"]) &&
					isset($_POST["worker"]) &&
					isset($_POST["description"])&&
					isset($_POST["client"])&&
					isset($_POST["service"])) {
			
				// Save user
				
				$event_data_in = array(
						"from" => $_POST["from"],
						"to" => $_POST["to"],
						"worker" => $_POST["worker"],
						"description" => $_POST["description"],
						"client" => $_POST["client"],
						"service" => $_POST["service"]
						
				);
				
				try {
					$model->update_event($this->args[1], $event_data_in);
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/success_generic_xhtml.phtml");
					$view_inner->message = "Event modified";
					//$view_inner->edit_event_id = $this->args[1];
					
				} catch (Exception $e) {
					$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/failure_generic_xhtml.phtml");
					
					$view_inner->message = "Cannot modify user: " . $e->getMessage();
				}
			} else if($this->args[0] == "delete" && isset($this->args[1])){
			
				$model->delete_event($this->args[1]);
				$view_inner = new View(REGIX_PATH.
							"views/layouts/generic/success_generic_xhtml.phtml");
				$view_inner->message = "Event deleted";
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