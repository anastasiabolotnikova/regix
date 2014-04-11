<?php
define("REGIX_PATH", "backend/");

require_once REGIX_PATH.'models/CalendarModel.php';

class CalendarModelTest extends PHPUnit_Framework_TestCase{
	
	public function test_get_day() {
		
		$model = new CalendarModel($this->db, $this->session);
		
		$day = date('j');
		echo $day.' *** '.$model->get_day();
		$this->assertEquals($model->get_day(), $day);
	}
}
?>