<?php
/**
 * This file contains abstract model class and generic exceptions.
 */

/**
 * All models used by Regix components should extend this class.
 */
abstract class Model {
	
	protected $db;
	protected $session;
	
	/**
	 * Creates a new model.
	 *
	 * @param DB_Adapter $db	fully initialized database adapter object.
	 * @param Session $session	fully initialized session manager.
	 */
	public function __construct($db, $session) {
		$this->db = $db;
		$this->session = $session;
	}
}