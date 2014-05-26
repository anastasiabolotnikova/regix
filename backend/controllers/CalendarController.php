<?php
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class CalendarController extends Controller {
	
	public function run() {
		if (loadClass(
				REGIX_PATH."models/CalendarModel.php",
				"CalendarModel",
				"Model")) {
			$model = new CalendarModel($this->db, $this->session);
		} else {
			return FALSE;
		}
		
		if(count($this->args) == 1){
			//Service is selected, default date
			//Show a calendar
			$view_content = new View(
					REGIX_PATH."views/layouts/CalendarOverview/calendar_page.phtml");
			$title = "Calendar :: Regix";
				
			$view_content->cal_uri = $this->get_controller_uri_name();
			// Current day
			$view_content->day = $model->get_day();
		
			// Selected date values
			$month = $model->get_month();
			$year = $model->get_year();
			$view_content->month = $month;
			$view_content->year = $year;
				
			$view_content->nextmonth = $model->next_month($month,$year);
			$view_content->prevmonth = $model->prev_month($month,$year);
			$view_content->wd = $model->get_wd($month, $year);
			$view_content->service = $this->args[0];
		
			// Current month, year variables
			$view_content->currmonth = $model->get_month();
			$view_content->curryear = $model->get_year();
		
		} else if(count($this->args)==3){
			//Service is selected
			//Show a calendar
			$view_content = new View(
						REGIX_PATH."views/layouts/CalendarOverview/calendar_page.phtml");
			$title = "Calendar :: Regix";
			
			$view_content->cal_uri = $this->get_controller_uri_name();
			// Current day
			$view_content->day = $model->get_day();

			// Selected date values
			$month = $this->args[2];
			$year = $this->args[1];
			$view_content->month = $month;
			$view_content->year = $year;
			
			$view_content->nextmonth = $model->next_month($month,$year);
			$view_content->prevmonth = $model->prev_month($month,$year);
			$view_content->wd = $model->get_wd($month, $year);
			$view_content->service = $this->args[0];

			// Current month, year variables
			$view_content->currmonth = $model->get_month();
			$view_content->curryear = $model->get_year();
		}


		else if(count($this->args)==4){
			//Show a time selection
			$view_content = new View(
					REGIX_PATH."views/layouts/CalendarOverview/time_selection_form.phtml");
			$title = "Time Selection :: Regix";
			$service=$this->args[0];
			$year=$this->args[1];
			$month=$this->args[2];
			$day=$this->args[3];

			$view_content->nextday = $model->next_day($month,$year,$day);
			$view_content->prevday = $model->prev_day($month,$year,$day);
			
			$view_content->cal_uri = $this->get_controller_uri_name();
			$view_content->service = $service;
			$view_content->year = $year;
			$view_content->month = $month;
			$view_content->day = $day;
			$view_content->free_timeslots = $model->get_free_timeslots($service, $year, $month, $day);

			// Current month, year, day variables
			$view_content->currmonth = $model->get_month();
			$view_content->curryear = $model->get_year();
			$view_content->currday = $model->get_day();

		}

		else if(count($this->args)==5){
			//Show registration form
			$view_content = new View(
					REGIX_PATH."views/layouts/CalendarOverview/event_registration_form.phtml");
			$title = "Event Registration Form :: Regix";
			
			$service=$this->args[0];
			$year=$this->args[1];
			$month=$this->args[2];
			$day=$this->args[3];
			
			$view_content->service = $service;
			$view_content->year = $year;
			$view_content->month = $month;
			$view_content->day = $day;
			$view_content->from = $this->args[4];
			$view_content->workers_to_assign = $model->get_workers_to_assign($service,$day,$month,$year,$this->args[4]);
			$view_content->cal_uri = $this->get_controller_uri_name();

			if (isset($_POST['reg_event']) && $_POST['reg_event'] == "Register") {
					$assigned_service = $service;
					$assigned_user = $model->get_assigned_user_id($_POST['worker']);
					$result = $model->save_data(
						$year,
						$month,
						$day,
						$_POST['comment'],
						$assigned_user[0]['id'],
						$assigned_service,
						$this->args[4]			
						);

					if($result) {
						// Booking was successful
						
						$s_uri = $model->get_on_success_uri($service);
						
						if($s_uri) {
							// Redirect to custom success page
							header("Location: " . $s_uri);
							return TRUE;
						} else {
							// Show default success page
							
							$view_content = new View(
									REGIX_PATH."views/layouts/CalendarOverview/event_registration_success.phtml");
							$title = "Event Registered :: Regix";
						}
					} else {
						// Booking failed
						
						$f_uri = $model->get_on_failure_uri($service);
						
						if($f_uri) {
							// Redirect to custom failure page
							header("Location: " . $f_uri);
							return TRUE;
						} else {
							$view_content = new View(
									REGIX_PATH."views/layouts/CalendarOverview/event_registration_failure.phtml");
							$title = "Registration Failed :: Regix";
						}
					}
				}

		}

		//Show services list
		else{
			$view_content = new View(
						REGIX_PATH."views/layouts/service_selection_form.phtml");
			$title = "Calendar :: Regix";
			
			$view_content->services = $model->get_services();
			$view_content->cal_uri = $this->get_controller_uri_name();
			$view_content->day = $model->get_day();
			$view_content->month = $model->get_month();
			$view_content->year = $model->get_year();
		}

		
		$view_outer = new View(REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
		$view_outer->user_name = $model->get_user_name();
		$view_outer->title = $title;
		$view_outer->content = $view_content->render(FALSE);
		
		$view_outer->render(TRUE);
		return TRUE;
	}
}