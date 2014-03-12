<?php
require_once 'View.php';

class MainPageView extends View {
	
	private $model;
	
	public function set_model($model) {
		$this->model = $model;
	}
	
	public function get_model() {
		return $this->model;
	}
	
	public function show() {
		//$title = $this->get_model()->get_title();
		//$content = $this->get_model()->get_content();
		//include 'views/layouts/layout_basic_xhtml.phtml';
		$user_name = $this->model->get_user_name();
		include 'views/layouts/layout_placeholder_html.phtml';
	}
}