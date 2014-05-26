<?php
require_once REGIX_PATH.'models/Model.php';

class ServiceManagerModel extends Model{
	
	function get_service_array() {
		return $this->db->select_all_services();
	}
	
	function get_service_data($service_uri_name) {
		return $this->db->select_service_data($service_uri_name);
	}
	
	
	function get_service_groups($service_uri_name) {
		return $this->db->select_service_groups($service_uri_name);
	}
	
	function save_service_data(
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
	
	function save_new_service_data(
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
	
	function delete_service($service_uri_name) {
		return $this->db->delete_service($service_uri_name);
	}
}