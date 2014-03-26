<?php
require_once REGIX_PATH.'models/Model.php';

class GroupManagerModel extends Model{
	
	public function get_editor_uri($id) {
		$res = $this->db->select(
				"controller",
				array("uri_name"),
				"s",
				array("id" => $id),
				1);
		return $res[0]["uri_name"];
	}
	
	public function get_group_array() {
		return $this->db->select_all_groups();
	}
	
	public function get_group_data($group_name) {
		return $this->db->select_group($group_name);
	}

	public function get_group_users($group_name) {
		return $this->db->select_group_users($group_name);
	}
	
	public function get_group_permissions($group_name) {
		return $this->db->select_group_permissions($group_name);
	}
}