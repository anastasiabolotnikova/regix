<?php
/**
 * @file DB_Adapter.php
 * 
 * Contains abstract database adapter and exceptions used by it.
 */

/**
 * Exception that should be thrown when it is impossible to connect to the DB.
 */
class DBConnectionException extends Exception {}


/**
 * Exception that should be thrown when it is impossible to execute a request.
 */
class DBRequestException extends Exception {}

/**
 * Abstract database adapter that can be used by all components.
 * 
 * Mathods in this abstract class should be used by system core and components
 * instead of directly accessing the database. When a component needs
 * a new method, it *must be added to this interface and all existing
 * subclasses.*
 * 
 * Subclasses may be added into the same directory with this class.
 * Note that you must also add code for loading this new adapter into
 * system bootstrap file (index.php). See MySQL_Adapter for reference
 * implementation.
 *
 */
abstract class DB_Adapter {
	
	/**
	 * @brief Regix configuration object.
	 * @var Config
	 */
	protected $config;
	
	/**
	 * Initialize database adapter.
	 * 
	 * @param Config $config Should contain at least following values:
	 * * db_host - database server address;
	 * * db_user - database user;
	 * * db_password - password for database user;
	 * * db_name - database (schema) name;
	 * * db_port - port of the database server.
	 */
	public function __construct($config) {
		$this->config = $config;
	}
	
	/**
	 * Closes connection and destroys adapter.
	 */
	public function __destruct() {
		// If connection still open, close it.
		$this->close();
	}
	
	/**
	 * Create a new connection to the database.
	 * 
	 * This method should be called *before* any of the others.
	 * 
	 * ** NB! You probably do not need to call this method manually, as this is
	 * done by the bootloader! **
	 * 
	 * @throws DBConnectionException when connection to the database fails.
	 */
	abstract public function connect();
	
	/**
	 * Close connection with the database.
	 * 
	 * This method should be called *after* all requests to the database are
	 * done.
	 * 
	 * ** NB! You probably do not need to call this method manually, as this is
	 * done by the bootloader! **
	 */
	abstract public function close();
	
	/**
	 * Get last error.
	 * 
	 * @return string Last error encountered when executing last query.
	 */
	
	abstract public function error();
	
	abstract public function get_event_data($id);
	
	/**
	 * Returns last ID generated by the database for previous insert operation.
	 */
	abstract public function get_last_id();
	
	abstract public function query($query, $params=NULL, $param_types=NULL,
			$bind_result=TRUE);
	
	// Bootloader
	
	abstract public function select_controller_by_uri_name_if_enabled($uri_name);
	
	// User
	
	abstract public function select_user($user_id);
	
	abstract public function select_user_has_group($user_id);
	
	// Controller
	
	abstract public function select_controller($controller_id);
	
	// LocalLogin
	
	abstract public function select_local_login_by_login($login);
	
	// RegistrationPage
	
	abstract public function select_local_login_by_email($email);
	
	abstract public function insert_user($name);
	
	abstract public function insert_local_login($user_id, $login, $salt, $hash,
			$email);
	
	// UserManager
	
	abstract public function select_all_users_with_local_login();
	
	abstract public function select_all_groups_with_user_mark($user_id);
	
	abstract public function select_local_login($user_id);
	
	abstract public function update_user($id, $name);
	
	abstract public function update_local_login($user_id, $login, $salt, 
			$hash, $email);
	
	abstract public function delete_local_login($user_id);
	
	abstract public function delete_user($id);
	
	abstract public function delete_all_user_groups($id);
	
	abstract public function insert_user_group($user_id, $group_name);
	
	// Controller manager
	
	abstract public function select_all_controllers();
	
	abstract public function update_controller($id, $name, $description,
			$enabled, $uri_name, $file_path);
	
	abstract public function remove_controller($id);
	
	// Permission manager
	
	abstract public function select_all_permissions_with_categories();
	
	abstract public function select_all_categories();
	
	abstract public function insert_permission_with_category_name($name,
			$description, $permission_category_name);
	
	abstract public function delete_permission($name);
	
	abstract public function select_permission_category($id);
	
	abstract public function update_permission_category($id, $name);
	
	abstract public function delete_permission_category($id);
	
	abstract public function insert_permission_category($name);
	
	abstract public function select_permission($name);
	
	abstract public function update_permission_with_category_name(
				$name_old,
				$name_new,
				$description,
				$permission_category_id);
	
	// Group manager
	
	abstract public function select_all_groups();
	
	abstract public function select_group($group_name);
	
	abstract public function select_group_users($group_name);
	
	abstract public function select_group_non_users($group_name);
	
	abstract public function select_group_permissions($group_name);
	
	abstract public function insert_group($group_name);
	
	abstract public function delete_group($group_name);
	
	abstract public function delete_user_has_group($user_id, $group_name);
	
	abstract public function insert_group_has_permission($group_name,
			$permission_name);
	
	abstract public function delete_group_has_permission($group_name,
			$permission_name);
	
	abstract public function select_group_non_permissions($group_name);
	
	// Latest
	
	abstract public function select_last_events($max_event_number);
	
	// MyPlan
	
	abstract public function select_events_by_employee_and_day(
			$employee_id, $year, $month, $day);
	
	// Service manager
	
	abstract public function select_all_services();
	
	abstract public function select_service_data($service_uri_name);
	
	abstract public function select_service_groups($service_uri_name);
}