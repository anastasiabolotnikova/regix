<?php
require_once REGIX_PATH.'models/Model.php';

class ControllerManagerModel extends Model{
	
	public function get_editor_uri($id) {
		$res = $this->db->select(
				"controller",
				array("uri_name"),
				"s",
				array("id" => $id),
				1);
		return $res[0]["uri_name"];
	}
	
	public function get_controller_array() {
		return $this->db->select_all_controllers();
	}
	
	public function get_controller_data($controller_id) {
		return $this->db->select_controller($controller_id);
	}
	
	public function set_controller_data($id, $data) {
		return $this->db->update_controller(
				$id,
				$data["name"],
				$data["description"],
				$data["enabled"],
				$data["uri_name"],
				$data["file_path"]);
	}
	
	public function add_controller($data) {
		return $this->db->insert_controller($data["name"], $data["description"],
				$data["enabled"], $data["uri_name"], $data["file_path"]);
	}
	
	public function delete_controller($id) {
		return $this->db->remove_controller($id);
	}
}