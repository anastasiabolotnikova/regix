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
	
	public function get_workers_to_assign($time){
		$workers_to_assign = $this->db->select_all_users_with_group_mark("Test Group 2");
		//Check if worker is busy at selected time
		return $workers_to_assign;
	}
	
	public function get_booked_hours($assigned_user,$day){
		return $this->db->select_hours_booked_with_user_mark($assigned_user, $day);
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

	public function save_data($year, $month, $day, $event_name, $description, $assigned_user, $time) {
		
		$array = explode("-",$time);
		
		$from_almost = explode(":",$array[0]);
		$timestamp = $year."-".$month."-".$day." ".$from_almost[0].":00:00";
		$from = date('Y-m-d H:i:s', strtotime($timestamp));
		
		$to_almost = explode(":",$array[1]);
		$timestamp = $year."-".$month."-".$day." ".$to_almost[0].":00:00";
		$to = date('Y-m-d H:i:s', strtotime($timestamp));
		
		return $this->db->insert_new_event($event_name, $description, $assigned_user, $from, $to);
	}
	
	public function get_assigned_user_id($name) {
		return $this->db->select_user_id_by_name($name);
	}
}