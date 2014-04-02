<?php
require_once REGIX_PATH.'models/Model.php';

class CalendarModel extends Model{
	
	protected $day;
	protected $month;
	protected $year;
	protected $wd;
	
	//Get date
	public function set_current_day(){
		$this->day = date('j');
	}
		
	public function set_current_month(){
		$this->month = date('n');
	}
		
	public function set_current_year(){
		$this->year = date('o');
	}
	
	public function set_current_wd(){
		$this->wd = date('w', mktime(0,0,0,$this->month,1,$this->year));
	}
	//Get events
	public function get_events() {
		return "Events";
	}
	//Get constraints
	public function get_constraints() {
		return "Constraints";
	}
	
	//Next Month
	public function next_month($curr_mont) {
		return $this->month+1;
	}
	//Previous month
	public function prev_month() {
		return $this->month+1;
	}
	
	public function get_workers_to_assign($service,$day,$month,$year,$time){
		$service_workers = $this->db->select_all_workers_with_service_mark($service);
		//Check if worker is busy at selected time
		$busy_workers = $this->db->select_busy_workers($service,$day,$month,$year,$time);
		
		foreach($service_workers as $swk => $sw){
			foreach($busy_workers as $bw){
				if($sw['id']==$bw['id']){
					unset($service_workers[$swk]);
					break;
				}
			}
		}
		
		return $service_workers;
	}
	
	public function get_booked_hours_for_worker($assigned_user,$day,$month,$year){
		return $this->db->select_hours_booked_with_user_mark($assigned_user, $day,$month,$year);
	}
	
	public function get_booked_hours_for_service($assigned_service,$day,$month,$year){
		//if(count(assigned_users) that belong to service > count(hours) from event) {show available time}
		//if(count(assigned_users) that belong to service <= count(hours) from event) {time is not available}
		$temp = $this->db->select_booked_hours_and_count_them($assigned_service, $day,$month,$year);
		$worker_qty = $this->db->count_workers_from_service($assigned_service);
		
		$booked_time=array();
		foreach ($temp as $booked) {
			if($booked['qty'] >= $worker_qty[0]['qty']){
				array_push($booked_time, $booked['booked_hours']);
			}
		}
		return $booked_time;
	}
	//Create calendar
	
	
	//variables to controller
	public function get_day() {
		CalendarModel::set_current_day();
		return $this->day;
	}
	public function get_month() {
		CalendarModel::set_current_month();
		return $this->month;
	}
	public function get_year() {
		CalendarModel::set_current_year();
		return $this->year;
	}
	public function get_wd() {
		CalendarModel::set_current_wd();
		if($this->wd == 0){
			$this->wd = 7;
		}
		return $this->wd;
	}

	public function save_data($year, $month, $day, $description, $assigned_user, $assigned_service, $time_start) {
		
		$user_id=$this->get_user_id();
		
		$step=1; //may be it's public variable from Event object that's generated using event_constraints table
		
		$timestamp1 = $year."-".$month."-".$day." ".$time_start.":00:00";
		$from = date('Y-m-d H:i:s', strtotime($timestamp1));
		
		$time_end = $time_start+$step;
		$timestamp2 = $year."-".$month."-".$day." ".$time_end.":00:00";
		//echo $timestamp2;
		$to = date('Y-m-d H:i:s', strtotime($timestamp2));
		
		return $this->db->insert_new_event($user_id, $description, $assigned_user, $assigned_service, $from, $to);
	}
	
	public function get_assigned_user_id($name) {
		return $this->db->select_user_id_by_name($name);
	}

	public function get_services() {
		return $this->db->select_all_services();
	}
}