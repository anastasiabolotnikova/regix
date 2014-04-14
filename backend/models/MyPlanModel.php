<?php
require_once REGIX_PATH.'models/Model.php';

class MyPlanModel extends Model{
	public function get_events($year, $month, $day) {
		return $this->db->select_events_by_employee_and_day(
				$this->session->user->get_id(),
				$year, $month, $day);
	}
}