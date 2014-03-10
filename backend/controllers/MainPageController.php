<?php
require_once 'Controller.php';

class MainPageController implements Controller{
	
	private $model;
	private $view;
	private $component_name = "MainPage";
	
	public function __construct() {
		$this->model = createModel($this->component_name);
		$this->view = createView($this->component_name);
		$this->view->set_model($this->model);
		$this->view->show();
	}
}