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

$category_id=$_GET["category_id"];
$arrayCalendars = $bookingListObj->getCalendarsList('ORDER BY calendar_order',$category_id);
$default = 0;
foreach($arrayCalendars as $calendarId => $calendar) {
	if($calendar["calendar_order"] == 0) {
		$default=$calendarId;
		$bookingCalendarObj->setCalendar($default);
	}
	?>
	<option value="<?php echo $calendarId; ?>"><?php echo $calendar["calendar_title"]; ?></option>
	<?php
}

?>|<?php echo $default; ?>|<?php echo $bookingCalendarObj->getFirstFilledMonth($bookingCalendarObj->getCalendarId()); ?>
