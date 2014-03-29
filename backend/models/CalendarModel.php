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
	
	//Get date
	public function set_current_date(){
		$this->day = date('j');
		$this->month = date('n');
		$this->year = date('o');
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
		$this->day;
	}
	public function get_month() {
		$this->month;
	}
	public function get_year() {
		$this->year;
	}
}