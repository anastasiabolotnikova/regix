<?php
define("REGIX_PATH", "backend/");

require_once REGIX_PATH.'core/Config.php';

require_once REGIX_PATH."core/adapters/MySQL_Adapter.php";
require_once REGIX_PATH.'models/CalendarModel.php';
require_once REGIX_PATH.'models/Model.php';


class UserStub {
	public function get_id() {
		return 1;
	}
}

class SessionStub {
	public $user;
	
	public function SessionStub() {
		$this->user = new UserStub();
	}
}

class CalendarModelTest extends PHPUnit_Framework_TestCase{
	
	private $config;
	private $db;
	private $session;
	
	public function __construct() {
		parent::__construct();
		
		$this->config = new Config(REGIX_PATH."config/regix.ini");
		$this->db = new MySQL_Adapter($this->config);
		$this->session = new SessionStub();
		
		$this->db->connect();
	}
	
	public function test_get_current_date() {
		$day = date('j');
		$month = date('n');
		$year = date('o');
		$wd = date('w', mktime(0,0,0,$month,1,$year));		
		$model = new CalendarModel($this->db, $this->session);
		
		if($wd == 0){$wd = 7;}
		
		$this->assertEquals($model->get_day(), $day);
		$this->assertEquals($model->get_month(), $month);
		$this->assertEquals($model->get_year(), $year);
		$this->assertEquals($model->get_wd($month, $year), $wd);
	}
	
	public function test_save_data(){
		
		$day = date('j');
		$month = date('n');
		$year = date('o');
		$wd = date('w', mktime(0,0,0,$month,1,$year));
		$model = new CalendarModel($this->db, $this->session);
		
		$description = 'Description by auto test';
		//$worker_ar = $model->$this->db->select_user(5);
		$worker = 'Test Worker 2';//$worker_ar[0]['name'];
		$assigned_user = 5;
		$time_start = 8;
		$assigned_service = 'tserv1';
		$timestamp1 = $year."-".$month."-".$day." ".$time_start.":00:00";
		$timestamp2 = $year."-".$month."-".$day." ".($time_start+1).":00:00";
		$from =  date('Y-m-d H:i:s', strtotime($timestamp1));
		$to = date('Y-m-d H:i:s', strtotime($timestamp2));
		
		$model->save_data($year, $month, $day, $description, $assigned_user, $assigned_service, $time_start);
		
		$last_id = $this->db->get_last_id();
		$event_data = $this->db->select_event_data($last_id);
		$event = $event_data[0];
		
		$this->assertEquals($event['from'], $from);
		$this->assertEquals($event['to'], $to);
		$this->assertEquals($event['worker'], $worker);
		$this->assertEquals($event['description'], $description);
		$this->assertEquals($event['service'], $assigned_service);
	}
}
?>