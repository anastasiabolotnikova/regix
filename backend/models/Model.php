<?php
/**
 * @file Model.php
 * 
 * This file contains generic model class and generic exceptions.
 */

/**
 * All models used by Regix components should extend this class.
 */
class Model {
	
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
	
	public function get_user_name() {
		return $this->session->user->get_name();
	}

	public function get_user_id() {
		return $this->session->user->get_id();
	}
}