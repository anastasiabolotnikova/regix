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
}