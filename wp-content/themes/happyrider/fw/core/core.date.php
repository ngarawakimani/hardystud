<?php
/**
 * HappyRider Framework: date and time manipulations
 *
 * @package	happyrider
 * @since	happyrider 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Convert date from MySQL format (YYYY-mm-dd) to Date (dd.mm.YYYY)
if (!function_exists('happyrider_sql_to_date')) {
	function happyrider_sql_to_date($str) {
		return (trim($str)=='' || trim($str)=='0000-00-00' ? '' : trim(happyrider_substr($str,8,2).'.'.happyrider_substr($str,5,2).'.'.happyrider_substr($str,0,4).' '.happyrider_substr($str,11)));
	}
}

// Convert date from Date format (dd.mm.YYYY) to MySQL format (YYYY-mm-dd)
if (!function_exists('happyrider_date_to_sql')) {
	function happyrider_date_to_sql($str) {
		if (trim($str)=='') return '';
		$str = strtr(trim($str),'/\-,','....');
		if (trim($str)=='00.00.0000' || trim($str)=='00.00.00') return '';
		$pos = happyrider_strpos($str,'.');
		$d=trim(happyrider_substr($str,0,$pos));
		$str=happyrider_substr($str,$pos+1);
		$pos = happyrider_strpos($str,'.');
		$m=trim(happyrider_substr($str,0,$pos));
		$y=trim(happyrider_substr($str,$pos+1));
		$y=($y<50?$y+2000:($y<1900?$y+1900:$y));
		return ''.($y).'-'.(happyrider_strlen($m)<2?'0':'').($m).'-'.(happyrider_strlen($d)<2?'0':'').($d);
	}
}

// Return difference or date
if (!function_exists('happyrider_get_date_or_difference')) {
	function happyrider_get_date_or_difference($dt1, $dt2=null, $max_days=-1) {
		static $gmt_offset = 999;
		if ($gmt_offset==999) $gmt_offset = (int) get_option('gmt_offset');
		if ($max_days < 0) $max_days = happyrider_get_theme_option('show_date_after', 30);
		if ($dt2 == null) $dt2 = date('Y-m-d H:i:s');
		$dt2n = strtotime($dt2)+$gmt_offset*3600;
		$dt1n = strtotime($dt1);
		$diff = $dt2n - $dt1n;
		$days = floor($diff / (24*3600));
		if (abs($days) < $max_days)
			return sprintf($days >= 0 ? __('%s ago', 'happyrider') : __('after %s', 'happyrider'), happyrider_get_date_difference($days >= 0 ? $dt1 : $dt2, $days >= 0 ? $dt2 : $dt1));
		else
			return happyrider_get_date_translations(date(get_option('date_format'), $dt1n));
	}
}

// Difference between two dates
if (!function_exists('happyrider_get_date_difference')) {
	function happyrider_get_date_difference($dt1, $dt2=null, $short=1, $sec = false) {
		static $gmt_offset = 999;
		if ($gmt_offset==999) $gmt_offset = (int) get_option('gmt_offset');
		if ($dt2 == null) $dt2 = time()+$gmt_offset*3600;
		else $dt2 = strtotime($dt2);
		$dt1 = strtotime($dt1);
		$diff = $dt2 - $dt1;
		$days = floor($diff / (24*3600));
		$months = floor($days / 30);
		$diff -= $days * 24 * 3600;
		$hours = floor($diff / 3600);
		$diff -= $hours * 3600;
		$min = floor($diff / 60);
		$diff -= $min * 60;
		$rez = '';
		if ($months > 0 && $short == 2)
			$rez .= ($rez!='' ? ' ' : '') . sprintf($months > 1 ? __('%s months', 'happyrider') : __('%s month', 'happyrider'), $months);
		if ($days > 0 && ($short < 2 || $rez==''))
			$rez .= ($rez!='' ? ' ' : '') . sprintf($days > 1 ? __('%s days', 'happyrider') : __('%s day', 'happyrider'), $days);
		if ((!$short || $rez=='') && $hours > 0)
			$rez .= ($rez!='' ? ' ' : '') . sprintf($hours > 1 ? __('%s hours', 'happyrider') : __('%s hour', 'happyrider'), $hours);
		if ((!$short || $rez=='') && $min > 0)
			$rez .= ($rez!='' ? ' ' : '') . sprintf($min > 1 ? __('%s minutes', 'happyrider') : __('%s minute', 'happyrider'), $min);
		if ($sec || $rez=='')
			$rez .=  $rez!='' || $sec ? (' ' . sprintf($diff > 1 ? __('%s seconds', 'happyrider') : __('%s second', 'happyrider'), $diff)) : __('less then minute', 'happyrider');
		return $rez;
	}
}

// Prepare month names in date for translation
if (!function_exists('happyrider_get_date_translations')) {
	function happyrider_get_date_translations($dt) {
		return str_replace(
			array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
				  'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
			array(
				__('January', 'happyrider'),
				__('February', 'happyrider'),
				__('March', 'happyrider'),
				__('April', 'happyrider'),
				__('May', 'happyrider'),
				__('June', 'happyrider'),
				__('July', 'happyrider'),
				__('August', 'happyrider'),
				__('September', 'happyrider'),
				__('October', 'happyrider'),
				__('November', 'happyrider'),
				__('December', 'happyrider'),
				__('Jan', 'happyrider'),
				__('Feb', 'happyrider'),
				__('Mar', 'happyrider'),
				__('Apr', 'happyrider'),
				__('May', 'happyrider'),
				__('Jun', 'happyrider'),
				__('Jul', 'happyrider'),
				__('Aug', 'happyrider'),
				__('Sep', 'happyrider'),
				__('Oct', 'happyrider'),
				__('Nov', 'happyrider'),
				__('Dec', 'happyrider'),
			),
			$dt);
	}
}
?>