<?php
/**
 * This file contains view interface and generic exceptions used by views.
 */

/**
 * All views used by Regix components should implement this interface.
 */
interface View {
	
	public function get_model();
	
	public function show();
}