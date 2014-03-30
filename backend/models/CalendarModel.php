<?php
require_once REGIX_PATH.'models/Model.php';

try {
	require_once REGIX_PATH.'models/LocalLoginModel.php';
} catch (Exception $e) {
	exit("RegistrationPage component requires LocalLogin component!");
}

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
	public function next_month() {
		return $this->month+1;
	}
	//Previous month
	public function prev_manth() {
		return $this->month+1;
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
}