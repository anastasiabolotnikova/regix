<?php
require_once REGIX_PATH.'models/Model.php';

class ServiceManagerModel extends Model{
	
	public function get_service_array() {
		return $this->db->select_all_services();
	}
	
	public function get_service_data($service_uri_name) {
		return $this->db->select_service_data($service_uri_name);
	}
	
	
	public function get_service_groups($service_uri_name) {
		return $this->db->select_service_groups($service_uri_name);
	}
	
	public function save_service_data(
			$old_uri_name,
			$uri_name,
			$name,
			$on_success_uri,
			$on_failure_uri) {
		return $this->db->update_service(
				$old_uri_name,
				$uri_name,
				$name,
				$on_success_uri,
				$on_failure_uri);
	}
	
	public function save_new_service_data(
			$uri_name,
			$name,
			$on_success_uri,
			$on_failure_uri) {
		return $this->db->insert_service(
				$uri_name,
				$name,
				$on_success_uri,
				$on_failure_uri);
	}
	
	public function delete_service($service_uri_name) {
		return $this->db->delete_service($service_uri_name);
	}
	
	public function delete_group_from_service($service_uri_name, $group_name) {
		return $this->db->delete_service_has_group($service_uri_name, 
				$group_name);
	}
	
	public function get_groups_not_assigned_to_service($service_uri_name) {
		return $this->db->select_groups_not_assigned_to_service(
				$service_uri_name);
	}
	
	public function add_group_to_service($service_uri_name, $group_name) {
		return $this->db->insert_service_has_group(
				$service_uri_name,
				$group_name);
	}
}