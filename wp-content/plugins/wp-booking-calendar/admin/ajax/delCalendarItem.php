<?php
include '../common.php';

global $wpdb;
global $blog_id;
$blog_prefix=$blog_id."_";
if($blog_id==1) {
	$blog_prefix="";
}
$item_id = $_REQUEST["item_id"];
$bookingCalendarObj->delCalendars($item_id);


include 'wp_booking_calendars.php';
?>

