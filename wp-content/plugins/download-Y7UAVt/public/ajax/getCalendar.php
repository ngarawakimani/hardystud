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

$bookingCalendarObj->setCalendar($_GET["calendar_id"]);
?>
<?php echo $bookingCalendarObj->getFirstFilledMonth($bookingCalendarObj->getCalendarId()); ?>