<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class CalendarController extends Controller{
	
	public function run() {
		if (loadClass(
				REGIX_PATH."models/CalendarModel.php",
				"CalendarModel",
				"Model")) {
			$model = new CalendarModel($this->db, $this->session);
		} else {
			return FALSE;
		}
		
		//Day is selected
		if (count($this->args)==2 or count($this->args)==3){
			//Get here from Calendar date
			if(count($this->args)==2){
				$view_content = new View(
						REGIX_PATH."views/layouts/CalendarOverview/time_selection_form.phtml");
				$title = "Time Selection :: Regix";
				$view_content->cal_uri = $this->get_controller_uri_name();
				$view_content->month = $this->args[0];
				$view_content->day = $this->args[1];
				$view_content->year = $model->get_year();
				$view_content->booked_hours = $model->get_booked_hours(2,$view_content->day);
				
			//Get here from time selection or from Calendar small times
			} else if (count($this->args)==3){
			

				//Registration is correct
				if (isset($_POST['reg_event']) && $_POST['reg_event'] == "Register") {

					$model->save_data(
						$model->get_year(),
						$this->args[0],
						$this->args[1],
						$_POST['event_name'],
						$_POST['comment'],
						$model->get_assigned_user_id($_POST['worker'])[0]['id'],
						$_POST['time']			
						);


					$view_content = new View(
							REGIX_PATH."views/layouts/CalendarOverview/event_registration_success.phtml");
					$title = "Event Registered :: Regix";
				}
				//Registration is not correct
				else if(1==2){
					$view_content = new View(
							REGIX_PATH."views/layouts/CalendarOverview/event_registration_failure.phtml");
					$title = "Registration Error :: Regix";	
				}
				//Begin registration
				else{
					$view_content = new View(
							REGIX_PATH."views/layouts/CalendarOverview/event_registration_form.phtml");
					$title = "Event Registration Form :: Regix";
					
					$view_content->month = $this->args[0];
					$view_content->day = $this->args[1];
					$view_content->from = $this->args[2];
					$view_content->year = $model->get_year();
					$view_content->workers_to_assign = $model->get_workers_to_assign();
					$view_content->booked_hours = $model->get_booked_hours(3,$view_content->day);
					$view_content->cal_uri = $this->get_controller_uri_name();
				}
			}
			
		}
		else{
			$view_content = new View(
						REGIX_PATH."views/layouts/CalendarOverview/calendar_page.phtml");
			$title = "Calendar :: Regix";
			
			$view_content->cal_uri = $this->get_controller_uri_name();
			$view_content->day = $model->get_day();
			$view_content->month = $model->get_month();
			$view_content->year = $model->get_year();
			$view_content->wd = $model->get_wd();
		}

		
		$view_outer = new View(REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		$view_outer->user_name = $model->get_user_name();
		$view_outer->title = $title;
		$view_outer->content = $view_content->render(FALSE);
		
		$view_outer->render(TRUE);
		return TRUE;
	}
}