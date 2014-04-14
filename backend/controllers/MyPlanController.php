<?php
define('LAYOUTS', REGIX_PATH.'views/layouts/');
define("LAYOUT_FAILURE",
		REGIX_PATH."views/layouts/generic/failure_generic_xhtml.phtml");
require_once REGIX_PATH.'controllers/Controller.php';
require_once REGIX_PATH.'views/View.php';

class MyPlanController extends Controller {
	
	protected $model;
	
	public function __construct($id, $db, $session, $args) {
		parent::__construct($id, $db, $session, $args);
	
		if (!loadClass(
				REGIX_PATH."models/MyPlanModel.php",
				"MyPlanModel",
				"Model")) {
				return FALSE;
		}
	
		$this->model = new MyPlanModel($this->db, $this->session);
	}
	
	public function show_plan_slotted($year, $month, $day) {
		if ($this->check_permission("myplan_own_plan", 
				"myplan/".$year."/".$month."/".$day)) {
			$view_outer = new View(LAYOUTS.'MyPlan/plan_slotted_xhtml.phtml');
			$view_outer->from = gmdate('r', mktime(8, 0, 0, $month, $day, $year));
			$view_outer->to = gmdate('r', mktime(18, 0, 0, $month, $day, $year));
			$view_outer->prev = gmdate('r', mktime(8, 0, 0, $month, $day-1, $year));
			$view_outer->next = gmdate('r', mktime(8, 0, 0, $month, $day+1, $year));
			
		} else {
			$view_outer = new View(
					REGIX_PATH."views/layouts/layout_basic_xhtml.phtml");
			
			$view_outer->title = "My Plan - Regix";
			$view_outer->user_name = $this->model->get_user_name();
			
			$view_inner = new View(LAYOUT_FAILURE);
			$view_inner->message = "Action forbidden";
			
			$view_outer->content = $view_inner->render(FALSE);
		}
		
		$view_outer->render(TRUE);
	}
	
	public function show_events_xml($year, $month, $day) {
		if ($this->session->user->has_permission("myplan_own_plan")) {
			$view_outer = new View(LAYOUTS.'layout_basic_xml.phtml');
			$view_inner = new View(LAYOUTS.'MyPlan/events_xml.phtml');
			
			$view_inner->events = $this->model->get_events($year, $month, $day);
			
			$view_outer->content = $view_inner->render(FALSE);
			$view_outer->render(TRUE);
		} else {
			$view_outer = new View(LAYOUTS.'layout_basic_xml.phtml');
			$view_outer->content = "<error>Forbidden</error>";
			$view_outer->render(TRUE);
		}
	}
	
	public function run() {
		
		if ($this->args[0] == "ajax" &&
			count($this->args) == 4 &&
			checkdate($this->args[2], $this->args[3], $this->args[1])) {
			// Events for the day
			// ajax/year/month/day
			
			$this->show_events_xml(
					$this->args[1],
					$this->args[2],
					$this->args[3]);
			
		} else if (count($this->args) == 3 &&
			checkdate($this->args[1], $this->args[2], $this->args[0])) {
			// Plan view (selected date)
			// year/month/day
			$this->show_plan_slotted(
					$this->args[0], $this->args[1], $this->args[2]);
			
		} else {
			// Plan view (today)
			// default
			$d = getdate();
			$this->show_plan_slotted($d['year'], $d['mon'], $d['mday']);	
		}
		
		return TRUE;
	}
}