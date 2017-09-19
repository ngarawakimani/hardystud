<?php
/**
 * HappyRider Framework: global variables storage
 *
 * @package	happyrider
 * @since	happyrider 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get global variable
if (!function_exists('happyrider_get_global')) {
	function happyrider_get_global($var_name) {
		global $HAPPYRIDER_GLOBALS;
		return isset($HAPPYRIDER_GLOBALS[$var_name]) ? $HAPPYRIDER_GLOBALS[$var_name] : '';
	}
}

// Set global variable
if (!function_exists('happyrider_set_global')) {
	function happyrider_set_global($var_name, $value) {
		global $HAPPYRIDER_GLOBALS;
		$HAPPYRIDER_GLOBALS[$var_name] = $value;
	}
}

// Inc/Dec global variable with specified value
if (!function_exists('happyrider_inc_global')) {
	function happyrider_inc_global($var_name, $value=1) {
		global $HAPPYRIDER_GLOBALS;
		$HAPPYRIDER_GLOBALS[$var_name] += $value;
	}
}

// Concatenate global variable with specified value
if (!function_exists('happyrider_concat_global')) {
	function happyrider_concat_global($var_name, $value) {
		global $HAPPYRIDER_GLOBALS;
		$HAPPYRIDER_GLOBALS[$var_name] .= $value;
	}
}

// Get global array element
if (!function_exists('happyrider_get_global_array')) {
	function happyrider_get_global_array($var_name, $key) {
		global $HAPPYRIDER_GLOBALS;
		return isset($HAPPYRIDER_GLOBALS[$var_name][$key]) ? $HAPPYRIDER_GLOBALS[$var_name][$key] : '';
	}
}

// Set global array element
if (!function_exists('happyrider_set_global_array')) {
	function happyrider_set_global_array($var_name, $key, $value) {
		global $HAPPYRIDER_GLOBALS;
		if (!isset($HAPPYRIDER_GLOBALS[$var_name])) $HAPPYRIDER_GLOBALS[$var_name] = array();
		$HAPPYRIDER_GLOBALS[$var_name][$key] = $value;
	}
}

// Inc/Dec global array element with specified value
if (!function_exists('happyrider_inc_global_array')) {
	function happyrider_inc_global_array($var_name, $key, $value=1) {
		global $HAPPYRIDER_GLOBALS;
		$HAPPYRIDER_GLOBALS[$var_name][$key] += $value;
	}
}

// Concatenate global array element with specified value
if (!function_exists('happyrider_concat_global_array')) {
	function happyrider_concat_global_array($var_name, $key, $value) {
		global $HAPPYRIDER_GLOBALS;
		$HAPPYRIDER_GLOBALS[$var_name][$key] .= $value;
	}
}
?>