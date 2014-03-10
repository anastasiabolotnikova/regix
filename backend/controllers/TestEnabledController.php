<?php
require_once 'Controller.php';

class TestEnabledController implements Controller {
	
	private $model;
	private $view;
	private $component_name = "TestEnabled";
	
	public function __construct() {
		$this->model = createModel($this->component_name);
		$this->view = createView($this->component_name);
		$this->view->set_model($this->model);
		$this->view->show();
	}
}