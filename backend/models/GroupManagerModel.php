<?php
require_once REGIX_PATH.'models/Model.php';

class GroupManagerModel extends Model{
	
	public function get_group_array() {
		return $this->db->select_all_groups();
	}
	
	public function get_group_data($group_name) {
		return $this->db->select_group($group_name);
	}

	public function get_group_users($group_name) {
		return $this->db->select_group_users($group_name);
	}
	
	public function get_users_not_in_group($group_name) {
		return $this->db->select_group_non_users($group_name);
	}
	
	public function get_group_permissions($group_name) {
		return $this->db->select_group_permissions($group_name);
	}
	
	public function add_group($group_name) {
		return $this->db->insert_group($group_name);
	}
	
	public function delete_group($group_name) {
		return $this->db->delete_group($group_name);
	}
	
	public function delete_group_user($group_name, $user_id) {
		return $this->db->delete_user_has_group($user_id, $group_name);
	}
	
	public function add_group_user_by_id($group_name, $user_id) {
		return $this->db->insert_user_has_group($user_id, $group_name);
	}
	
	public function add_group_permission($group_name, $permission_name) {
		return $this->db->insert_group_has_permission($group_name,
				$permission_name);
	}
	
	public function delete_group_permission($group_name, $permission_name) {
		return $this->db->delete_group_has_permission($group_name,
				$permission_name);
	}
	
	public function get_permissions_not_granted($group_name) {
		return $this->db->select_group_non_permissions($group_name);
	}
}