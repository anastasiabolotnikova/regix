<?php
/**
 * @file View.php
 * 
 * This file contains generic view and generic exceptions used by views.
 */

/**
 * This exception is thrown if view is unable render template.
 */
class ViewException extends Exception {
	
}

/**
 * All views used by Regix components should extend this class.
 */
class View {
	
	protected $template_file_name;
	protected $data;
	
	function __construct($template_file_name) {
		$this->template_file_name = $template_file_name;
	}
	
	function __get($name) {
		if (isset($this->data[$name])) {
			return $this->data[$name];
		}
		return NULL;
	}
	
	function __set($name, $value) {
		$this->data[$name] = $value;
	}
	
	function render($print = FALSE) {
		if (is_readable($this->template_file_name)) {
			ob_start();
			include($this->template_file_name);
			$rendered = ob_get_clean();
			if ($print) {
				echo $rendered;
			}
			return $rendered;
		} else {
			throw new ViewException("Template file not found or not readable.");
		}
	}
	
	function __toString() {
		return $this->render(FALSE);
	}
}