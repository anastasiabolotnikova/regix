<?php
/**
 * This file contains generic database adapter interface (DB_Adapter).
 */

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
 * @author Sergei Jakovlev
 *
 */
interface DB_Adapter {
	
	/**
	 * Initializes database adapter.
	 * 
	 * @param array $CONF Should contain at least following values:
	 * * CONF['db_host'] - database server address;
	 * * CONF['db_user'] - database user;
	 * * CONF['db_password'] - password for database user;
	 * * CONF['db_name'] - database (schema) name;
	 * * CONF['db_port'] - port of the database server.
	 */
	public function __construct($CONF);
	
	/**
	 * Creates a new connection to the database.
	 * 
	 * This method should be called *before* any of the others.
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
	 * @param string $controller_uri_name uri_name of the controller (see
	 * database model for details)
	 * 
	 * @return string Name of the main file of the controller with the given
	 * uri_name if such controller exists and is enabled in the database,
	 * NULL otherwise.
	 */
	public function get_controller($controller_uri_name);
}