<?php
require_once REGIX_PATH.'models/Model.php';

class PermissionManagerModel extends Model {
	
	public function get_permission_array() {
		return $this->db->select_all_permissions_with_categories();
	}
	
	public function get_category_array() {
		return $this->db->select_all_categories();
	}
	
	public function add_permission($name, $description, $category_name) {
		return $this->db->insert_permission_with_category_name($name, 
				$description, $category_name);
	}
	
	public function delete_permission($name) {
		return $this->db->delete_permission($name);
	}
	
	public function get_category_data($category_id) {
		$categories = $this->db->select_permission_category($category_id);
		if ($categories) {
			return $categories[0];
		} else {
			return NULL;
		}
	}
	
	public function save_permission_category($category_id, $category_name) {
		return $this->db->update_permission_category($category_id,
				$category_name);
	}
	
	public function delete_permission_category($category_id) {
		return $this->db->delete_permission_category($category_id);
	}
	
	public function add_permission_category($category_name) {
		return $this->db->insert_permission_category($category_name);
	}
	
	public function get_permission_data($permission_name) {
		$permissions =  $this->db->select_permission($permission_name);
		if ($permissions) {
			return $permissions[0];
		} else {
			return NULL;
		}
	}
	
	public function update_permission($name_old, $name_new, $description,
			$category_id) {
		return $this->db->update_permission_with_category_name(
				$name_old,
				$name_new,
				$description,
				$category_id);
	}
}