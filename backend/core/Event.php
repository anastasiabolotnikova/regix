<?php
/**
 * @file Event.php
 * 
 * This file contains Event class definition.
 */

/**
 * Regix core event.
 * 
 * This class directly corresponds to database _Event_ table and provides methods
 * to access basic user information stored in this table.
 * 
 */
class Event {
	
	/**
	 * Database adapter.
	 * 
	 * Used when creating event object.
	 * @var DB_Adapter
	 */
	protected $db;
	
	/**
	 * Event id.
	 * @var int
	 */
	protected $id;
	
	/**
	 * Calendar id.
	 * @var int
	 */
	protected $id_cal;	
	/**
	 * Event name
	 * 
	 * @var unknown
	 */
	protected $event_name;
	
	/**
	 * Event description
	 *
	 * @var unknown
	 */
	protected $desc;
	
	/**
	 * User assigned to event
	 *
	 * @var unknown
	 */
	protected $assigned_user;
	
	/**
	 * User group assigned to event
	 *
	 * @var unknown
	 */
	protected $assigned_group;
	
	/**
	 * Time when event starts
	 *
	 * @var timestamp
	 */
	protected $from;
	
	/**
	 * Time when event ends
	 *
	 * @var timestamp
	 */
	protected $to;
	
	/**
	 * Creates a new event object. Downloads data from the database.
	 * 
	 * @param int $id Event id.
	 * @param DB_Adapter $db Initialized and connected database adapter object.
	 * 
	 */
	public function __construct($id, $db) {
		$this->db = $db;
		$this->id = $id;
		$event_data = $this->db->get_event_data($id);
		
		if (!$prof_data) {
			throw new Exception("Event does not exist");
		}
		
		$this->id_cal = $event_data['id_cal'];
		$this->event_name = $event_data['event_name'];
		$this->desc = $event_data['desc'];
		$this->assigned_user = $event_data['assigned_user'];
		$this->assigned_group = $event_data['assigned_group'];
		$this->from = $event_data['from'];
		$this->to = $event_data['to'];
		
	}
	
	
	public function get_id() {
		return $this->id;
	}
	
	public function get_cal_id() {
		return $this->id_cal;
	}
	
	
	/**
	 * Returns user name.
	 * 
	 * Note that this is the name from _User_ table, even though other variants
	 * may be available via additional modules.
	 * 
	 * @return string
	 */
	public function get_event_name() {
		return $this->event_name;
	}
	
	public function get_desc() {
		return $this->desc;
	}
	
	public function get_assigned_user() {
		return $this->assigned_user;
	}
	
	public function get_assigned_group() {
		return $this->assigned_group;
	}
	
	public function get_start_time() {
		return $this->from;
	}
	
	public function get_end_time() {
		return $this->to;
	}
}