<?php
/**
 * This file contains generic database adapter interface (DB_Adapter) and
 * exceptions used by it.
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
 * Generic database interface that can be used by all components.
 * 
 * These methods should be used by system core and components instead of
 * directly accessing the database. When a component needs a new method,
 * it *must be added to this interface and all existing implementations.*
 * 
 * Implementations may be added into the same directory as this interface.
 * Note, that you must also add code for loading this adapter into
 * system bootstrap file (index.php). See MySQL_Adapter for reference
 * implementation.
 *
 */
interface DB_Adapter {
	
	/**
	 * Initializes database adapter.
	 * 
	 * @param Config $config Should contain at least following values:
	 * * db_host - database server address;
	 * * db_user - database user;
	 * * db_password - password for database user;
	 * * db_name - database (schema) name;
	 * * db_port - port of the database server.
	 */
	public function __construct($config);
	
	/**
	 * Creates a new connection to the database.
	 * 
	 * This method should be called *before* any of the others.
	 * 
	 * @throws DBConnectionException when connection to the database fails.
	 * 
	 * @note Should we merge this method with the constructor and avoid
	 * problems related to forgetting to call it before others altogether?
	 * Then we must be careful to create adapted only when it is needed.
	 * Otherwise unnecessary DB connections may be created.
	 */
	public function connect();
	
	/**
	 * Closes connection with the database.
	 * 
	 * This method should be called *after* all requests to the database are
	 * done.
	 * 
	 * @note Should we merge this method with the destructor?
	 */
	public function close();
	
	/**
	 * Get actual name of the controller file if it is enabled.
	 * 
	 * Returns name and path to the implementation of the controller with
	 * the given uri_name if such controller exists and is enabled in
	 * the database.
	 * 
	 * @param string $controller_uri_name uri_name of the controller (see
	 * database model for details)
	 * 
	 * @return array Should contain the following:
	 * * name - controller class name, NULL if this controller does not exist
	 * or is disabled;
	 * * file_path - path to controller implementation, NULL if this controller
	 * does not exist or is disabled.
	 * 
	 * @throws DBRequestException when request cannot be fulfilled.
	 */
	public function get_controller($controller_uri_name);
	
	/**
	 * Get user name from DB by user id.
	 * 
	 * @param int $user_id User id in the User table.
	 * @return string User name.
	 */
	public function get_user_name($user_id);
	
	/**
	 * Get array of user groups from DB by user id.
	 *
	 * @param int $user_id User id in the User table.
	 * @return array List of strings fit group names.
	 */
	public function get_user_groups($user_id);
}