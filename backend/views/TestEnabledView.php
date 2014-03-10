<?php
require_once 'View.php';

class TestEnabledView implements View {

	private $model;

	public function set_model($model) {
		$this->model = $model;
	}

	public function get_model() {
		return $this->model;
	}

	public function show() {
		$title = $this->get_model()->get_title();
		$content = $this->get_model()->get_content();
		include 'views/layouts/layout_basic_xhtml.phtml';
	}
}