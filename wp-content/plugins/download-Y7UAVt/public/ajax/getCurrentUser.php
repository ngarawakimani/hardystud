<?php
include '../common.php';
global $sitepress;
if(isset($sitepress)) {
    $sitepress->switch_lang($_GET['wpml_lang'], true);
    load_plugin_textdomain( 'wp-booking-calendar' );
    load_plugin_textdomain( 'wp-booking-calendar-slots' );
    load_plugin_textdomain( 'wp-booking-calendar-calendars' );
    load_plugin_textdomain( 'wp-booking-calendar-categories' );
    load_plugin_textdomain( 'wp-booking-calendar-mails' );
}

wp_get_current_user();
global $current_user;


if($current_user->ID >0) {

	echo $current_user->user_firstname."|".$current_user->user_lastname."|".$current_user->user_email."|".$current_user->ID;
} else {
	echo "error";
}
?>
