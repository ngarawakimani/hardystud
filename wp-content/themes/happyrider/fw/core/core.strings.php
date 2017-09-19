<?php
/**
 * HappyRider Framework: strings manipulations
 *
 * @package	happyrider
 * @since	happyrider 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'HAPPYRIDER_MULTIBYTE' ) ) define( 'HAPPYRIDER_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('happyrider_strlen')) {
	function happyrider_strlen($text) {
		return HAPPYRIDER_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('happyrider_strpos')) {
	function happyrider_strpos($text, $char, $from=0) {
		return HAPPYRIDER_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('happyrider_strrpos')) {
	function happyrider_strrpos($text, $char, $from=0) {
		return HAPPYRIDER_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('happyrider_substr')) {
	function happyrider_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = happyrider_strlen($text)-$from;
		}
		return HAPPYRIDER_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('happyrider_strtolower')) {
	function happyrider_strtolower($text) {
		return HAPPYRIDER_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('happyrider_strtoupper')) {
	function happyrider_strtoupper($text) {
		return HAPPYRIDER_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('happyrider_strtoproper')) {
	function happyrider_strtoproper($text) {
		$rez = ''; $last = ' ';
		for ($i=0; $i<happyrider_strlen($text); $i++) {
			$ch = happyrider_substr($text, $i, 1);
			$rez .= happyrider_strpos(' .,:;?!()[]{}+=', $last)!==false ? happyrider_strtoupper($ch) : happyrider_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('happyrider_strrepeat')) {
	function happyrider_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('happyrider_strshort')) {
	function happyrider_strshort($str, $maxlength, $add='...') {
	//	if ($add && happyrider_substr($add, 0, 1) != ' ')
	//		$add .= ' ';
		if ($maxlength < 0) 
			return '';
		if ($maxlength < 1 || $maxlength >= happyrider_strlen($str))
			return strip_tags($str);
		$str = happyrider_substr(strip_tags($str), 0, $maxlength - happyrider_strlen($add));
		$ch = happyrider_substr($str, $maxlength - happyrider_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = happyrider_strlen($str) - 1; $i > 0; $i--)
				if (happyrider_substr($str, $i, 1) == ' ') break;
			$str = trim(happyrider_substr($str, 0, $i));
		}
		if (!empty($str) && happyrider_strpos(',.:;-', happyrider_substr($str, -1))!==false) $str = happyrider_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('happyrider_strclear')) {
	function happyrider_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (happyrider_substr($text, 0, happyrider_strlen($open))==$open) {
					$pos = happyrider_strpos($text, '>');
					if ($pos!==false) $text = happyrider_substr($text, $pos+1);
				}
				if (happyrider_substr($text, -happyrider_strlen($close))==$close) $text = happyrider_substr($text, 0, happyrider_strlen($text) - happyrider_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('happyrider_get_slug')) {
	function happyrider_get_slug($title) {
		return happyrider_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('happyrider_strmacros')) {
	function happyrider_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}
?>