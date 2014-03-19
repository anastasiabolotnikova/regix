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
	 * @brief Regix configuration.
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
	 * @throws DBConnectionException when connection to the database fails.
	 */
	abstract public function connect();
	
	/**
	 * Close connection with the database.
	 * 
	 * This method should be called *after* all requests to the database are
	 * done.
	 */
	abstract public function close();
	
	/**
	 * Get actual name of the controller file if it is enabled.
	 * 
	 * Returns name and path to the implementation of the controller with
	 * the given uri_name if such controller exists and is enabled in
	 * the database. **Nota that if there are several components with such name,
	 * behaviour is not defined.**
	 * 
	 * @param string $controller_uri_name uri_name of the controller (see
	 * database model for details)
	 * 
	 * @return array|NULL Array should contain the following:
	 * * id - controller id;
	 * * name - controller class name;
	 * * file_path - path to controller implementation.
	 * Returns NULL if such controller was not found.
	 * 
	 * @throws DBRequestException when request cannot be fulfilled.
	 */
	abstract public function get_controller_by_uri($controller_uri_name);
	
	/**
	 * Get user name from DB by user id.
	 * 
	 * @param int $user_id User id in the _User_ table.
	 * @return string User name.
	 */
	//abstract public function get_user_name($user_id);
	
	/**
	 * Get array of user groups from DB by user id.
	 *
	 * @param int $user_id User id in the _User_ table.
	 * @return array List of strings with group names (may be empty).
	 */
	abstract public function get_user_groups($user_id);
	
	//abstract public function get_user_email($user_id);
	
	abstract public function get_profile_data($id);
	
	/**
	 * Get array of information needed to perform local login.
	 * 
	 * @param string $username username value from table LocalLogin
	 * 
	 * @return array|NULL Array contains:
	 * * user_id
	 * * salt
	 * * hash
	 * * email
	 * NULL is returned if user is not found.
	 */
	abstract public function get_local_login_data($username);
	
	abstract public function insert_local_login_data($username, $hashed_pass, $salt, $email);
	
	abstract public function insert_user_data($name);
}