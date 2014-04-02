<?php
require_once REGIX_PATH.'models/Model.php';

class LatestModel extends Model{
	public function get_added_events($max_event_number) {
		return $this->db->select_last_events($max_event_number);
	}
}