<?php
require_once 'Controller.php';

class MainPageController implements Controller{
	
	private $model;
	private $view;
	private $component_name = "MainPage";
	
	private $session;
	private $args;
	
	public function __construct() {
		$this->model = createModel($this->component_name);
		$this->view = createView($this->component_name);
		$this->view->set_model($this->model);
	}
	
	public function set_db($db) {}
	
	public function set_session($session) {
		$this->session = $session;
	}
	
	public function set_arguments($args) {
		$this->args = $args;
	}
	
	public function run() {
		$this->model->set_session($this->session);
		
		if ($this->args[0] == "reset") {
			$this->session->destroy();
		}
		
		$this->view->show();
	}
}