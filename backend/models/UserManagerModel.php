<?php
require_once REGIX_PATH.'models/Model.php';

class UserManagerModel extends Model{
	
	public function get_editor_uri($id) {
		$res = $this->db->select(
				"controller",
				array("uri_name"),
				"s",
				array("id" => $id),
				1);
		return $res[0]["uri_name"];
	}
	
	public function get_user_array() {
		$users = $this->db->select_all_users_with_local_login();
		
		return $users;
	}
	
	public function get_user_data($id) {
		$user = $this->db->get_profile_data($id);
		$user["group_data"] = $this->db->select_all_groups_with_user_mark($id);
	
		return $user;
	}
}