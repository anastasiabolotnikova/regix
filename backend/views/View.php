<?php
/**
 * This file contains abstract view and generic exceptions used by views.
 */

/**
 * All views used by Regix components should extend this class.
 */
abstract class View {
	
	protected $template_file_name;
	protected $data;
	
	function __construct($template_file_name) {
		$this->template_file_name = $template_file_name;
	}
	
	function __get( $name ) {
		if (isset($this->data[$name])) {
			return $this->data[$name];
		}
		return NULL;
	}
	
	function __set( $name, $value ) {
		$this->data[$name] = $value;
	}
	
	function render($print = true) {
		include($this->template_file_name);
		$rendered = ob_get_clean();
		if ($print) {
			echo $rendered;
		}
		return $rendered;
	}
	
	function __toString() {
		return $this->render();
	}
}