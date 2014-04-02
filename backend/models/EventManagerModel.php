<?php
require_once REGIX_PATH.'models/Model.php';

class EventManagerModel extends Model{
	
	//function that gives us data from event table
	public function get_events_array() {
		return $this->db->select_data_from_events();
	}
	
	public function get_event_data($id) {
		$selected_event = $this->db->select_event_data($id);
		$event = $selected_event[0];
		//echo $event['from'];
		return $event;
	}
	//function that updates event
	public function update_event($id, $event_data_in) {
		//echo $id;
		$user_id = $this->db->select_user_id_by_name($event_data_in['client']);
		$assigned_user = $this->db->select_user_id_by_name($event_data_in['worker']);
		//var_dump($event_data_in);
		return $this->db->set_updated_event(1,$user_id[0]['id'],$event_data_in['description'],$assigned_user[0]['id'],$event_data_in['service'],$event_data_in['from'],$event_data_in['to'],$id);
	}
	//function that deletes event
	public function delete_event($event_id) {
		return $this->db->delete_event($event_id);
	}
}