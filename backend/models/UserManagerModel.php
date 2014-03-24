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
	
	public function set_user_data($id, $data) {
		if (loadClass(
				REGIX_PATH."models/LocalLoginModel.php",
				"LocalLoginModel",
				"Model")) {
				
			$data_old = $this->db->select_local_login($id)[0];
			
			$ll_model = new LocalLoginModel($this->db, $this->session);
			
			if ($data["password"]) {
				if (!LocalLoginModel::is_password_good($data["password"])) {
					throw new Exception(
							"Password is too weak.");
				}
				$new_salt = $ll_model::gen_salt(NULL);
				$new_hash = $ll_model::password_hash($data["password"], $new_salt);
			} else {
				$new_salt = $data_old["salt"];
				$new_hash = $data_old["hash"];
			}
			
			
			$this->db->update_user($id, $data["name"]);
			$this->db->update_local_login($id, $data["login"], $new_salt,
					$new_hash, $data["email"]);
		} else {
			throw new Exception(
					"LocalLoginModel is needed for this functionality.");
		}
	
		return TRUE;
	}
	
	public function delete_user($id) {
		$this->db->delete_local_login($id);
		$this->db->delete_user($id);
	}
}