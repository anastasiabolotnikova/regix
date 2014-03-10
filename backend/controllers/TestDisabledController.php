<?php
require_once 'Controller.php';

class TestDisabledController implements Controller {
	
	private $model;
	private $view;
	private $component_name = "TestDisabled";
	
	public function __construct() {
		$this->model = createModel($this->component_name);
		$this->view = createView($this->component_name);
		$this->view->set_model($this->model);
		$this->view->show();
	}
}