<?php
/**
 * @file Calendar.php
 * 
 * This file contains Calendar class definition.
 */

//require_once REGIX_PATH.'core/Group.php';

/**
 * Regix core user.
 * 
 * This class directly corresponds to database _Calendar_ table and provides methods
 * to access basic calendar information stored in this table.
 * 
 */
class Calendar {
	
	/**
	 * Database adapter.
	 * 
	 * Used when creating calendar object.
	 * @var DB_Adapter
	 */
	protected $db;
	
	/**
	 * Calendar id.
	 * @var int
	 */
	protected $id;
	
	/**
	 * Calendar name
	 * 
	 * @var unknown
	 */
	protected $name;
	
	/**
	 * Creates a new calendar object. Downloads data from the database.
	 * 
	 * @param int $id Calendar id.
	 * @param DB_Adapter $db Initialized and connected database adapter object.
	 *
	 */
	public function __construct($id, $db) {
		$this->db = $db;
		$this->id = $id;
		$cal_data = $this->db->get_cal_data($id);
		
		if (!$cal_data) {
			throw new Exception("Calendar does not exist");
		}
		
		$this->name = $cal_data['cal_name'];
	}
	
	
	public function get_id() {
		return $this->id;
	}
	
	
	/**
	 * Returns user name.
	 * 
	 * Note that this is the name from _User_ table, even though other variants
	 * may be available via additional modules.
	 * 
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}
}