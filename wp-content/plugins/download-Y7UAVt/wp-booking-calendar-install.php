<?php
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
define('BOOKING_CALENDAR_PLUGIN_PATH', WP_PLUGIN_DIR . '/wp-booking-calendar/');

function booking_calendar_install_db($blog_prefix = '') {
	global $wpdb;
	$sql2="CREATE TABLE ".$wpdb->base_prefix.$blog_prefix."booking_categories (
	category_id int(11) NOT NULL AUTO_INCREMENT ,
	category_name varchar(255) NOT NULL ,
	category_order int(11) NOT NULL ,
	category_active int(11) NOT NULL ,
	PRIMARY KEY  (category_id)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	";
	dbDelta($sql2);
	
	if($wpdb->query("select * from ".$wpdb->base_prefix.$blog_prefix."booking_categories")>0) {
		
		$categoriesQry = $wpdb->get_results("select * from ".$wpdb->base_prefix.$blog_prefix."booking_categories");
		for($i=0;$i<count($categoriesQry);$i++) {
			$package = array(
			 'kind' => 'WP Booking Calendar',
			 'name' => 'categories',
			 'title' => 'WP Booking Calendar - Categories'
			 );
			 do_action( 'wpml_register_string', $categoriesQry[$i]->category_name, "category_".$categoriesQry[$i]->category_id, $package, "Category: ".$categoriesQry[$i]->category_name, 'LINE' );
			
		}
	}
	
	$sql3="CREATE TABLE ".$wpdb->base_prefix.$blog_prefix."booking_calendars (
	  calendar_id int(11) NOT NULL AUTO_INCREMENT,
	  category_id int(11) NOT NULL,
	  calendar_title varchar(255) NOT NULL,
	  calendar_email varchar(700) NOT NULL,
	  calendar_order int(11) NOT NULL,
	  calendar_active int(11) NOT NULL,
	  PRIMARY KEY  (calendar_id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	";
	dbDelta($sql3);
	if($wpdb->query("select * from ".$wpdb->base_prefix.$blog_prefix."booking_calendars")>0) {
		$calendarsQry = $wpdb->get_results("select * from ".$wpdb->base_prefix.$blog_prefix."booking_calendars");
		
		for($i=0;$i<count($calendarsQry);$i++) {
			$package = array(
			 'kind' => 'WP Booking Calendar',
			 'name' => 'calendars',
			 'title' => 'WP Booking Calendar - Calendars'
			 );
			 do_action( 'wpml_register_string', $calendarsQry[$i]->calendar_title, "calendar_".$calendarsQry[$i]->calendar_id, $package, "Calendar: ".$calendarsQry[$i]->calendar_title, 'LINE' );
		}
	}
	
	$sql4="
	CREATE TABLE ".$wpdb->base_prefix.$blog_prefix."booking_config (
	  config_id int(11) NOT NULL AUTO_INCREMENT,
	  config_name varchar(255) NOT NULL,
	  config_value varchar(255) NOT NULL,
	  PRIMARY KEY  (config_id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;
	";
	dbDelta($sql4);
	$sql5="
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'reservation_confirmation_mode', '0') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'reservation_confirmation_mode'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'reservation_confirmation_mode_override', '0') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'reservation_confirmation_mode_override'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'reservation_after_payment', '0') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'reservation_after_payment'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'timezone', 'Europe/Belgrade') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'timezone'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'email_reservation', '') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'email_reservation'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'recaptcha_public_key', '') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'recaptcha_public_key'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'recaptcha_private_key', '') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'recaptcha_private_key'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'mandatory_fields', 'reservation_name,reservation_surname,reservation_email,reservation_phone,reservation_message') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'mandatory_fields'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'email_from_reservation', '') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'email_from_reservation'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'recaptcha_enabled', '0') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'recaptcha_enabled'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'slots_popup_enabled', '1') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'slots_popup_enabled'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'reservation_cancel', '0') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'reservation_cancel'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'slot_selection', '0') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'slot_selection'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'date_format', 'UK') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'date_format'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'time_format', '24') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'time_format'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'slots_unlimited', '0') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'slots_unlimited'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'calendar_month_limit_future', '-1') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'calendar_month_limit_future'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'show_booked_slots', '0') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'show_booked_slots'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'show_calendar_selection', '1') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'show_calendar_selection'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'calendar_month_limit_past', '-1') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'calendar_month_limit_past'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'show_terms', '0') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'show_terms'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'book_from', '0') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'book_from'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'book_to', '0') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'book_to'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'paypal', '0') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'paypal'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'paypal_account', '') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'paypal_account'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'paypal_currency', '') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'paypal_currency'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'paypal_locale', '') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'paypal_locale'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'paypal_display_price', '0') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'paypal_display_price'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'visible_fields', 'reservation_name,reservation_surname,reservation_email,reservation_phone,reservation_message') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'visible_fields'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_grey_bg', '#F6F6F6') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_grey_bg'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_white_bg', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_white_bg'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_white_bg_hover', '#567BD2') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_white_bg_hover'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_white_line1_disabled_color', '#CCCCCC') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_white_line1_disabled_color'
	) LIMIT 1;
	
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_white_line2_disabled_color', '#CCCCCC') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_white_line2_disabled_color'
	) LIMIT 1;

	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_white_line2_disabled_bg', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_white_line2_disabled_bg'
	) LIMIT 1;
	
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_white_line1_color', '#999999') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_white_line1_color'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_white_line1_color_hover', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_white_line1_color_hover'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_white_line2_color', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_white_line2_color'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_white_line2_color_hover', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_white_line2_color_hover'
	) LIMIT 1;

	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_white_line2_bg', '#56c477') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_white_line2_bg'
	) LIMIT 1;

	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_white_line2_bg_hover', '#56c477') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_white_line2_bg_hover'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'form_bg', '#567BD2') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'form_bg'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'form_color', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'form_color'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'recaptcha_style', 'white') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'recaptcha_style'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_red_bg', '#D74E4E') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_red_bg'
	) LIMIT 1;

	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_red_line2_bg', '#D74E4E') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_red_line2_bg'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_red_line1_color', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_red_line1_color'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_red_line2_color', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_red_line2_color'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_white_bg_disabled', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_white_bg_disabled'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'show_category_selection', '0') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'show_category_selection'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'show_first_filled_month', '0') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'show_first_filled_month'
	) LIMIT 1;
	
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'show_slots_seats', '0') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'show_slots_seats'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'book_now_button_bg', '#333333') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'book_now_button_bg'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'book_now_button_color', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'book_now_button_color'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'field_input_bg', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'field_input_bg'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'field_input_color', '#000000') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'field_input_color'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_names_color', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_names_color'
	) LIMIT 1;

	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_names_bg', '#333333') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_names_bg'
	) LIMIT 1;

	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'day_border', 'dashed') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'day_border'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'month_name_color', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'month_name_color'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'year_name_color', '#999999') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'year_name_color'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'month_container_bg', '#333333') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'month_container_bg'
	) LIMIT 1;

	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'book_now_button_bg_hover', '#00CC66') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'book_now_button_bg_hover'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'month_navigation_button_bg', '#333333') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'month_navigation_button_bg'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'month_navigation_button_bg_hover', '#567BD2') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'month_navigation_button_bg_hover'
	) LIMIT 1;

	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'month_navigation_button_color', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'month_navigation_button_color'
	) LIMIT 1;

	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'month_navigation_button_color_hover', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'month_navigation_button_color_hover'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'clear_button_bg', '#999999') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'clear_button_bg'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'clear_button_bg_hover', '#333333') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'clear_button_bg_hover'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'clear_button_color', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'clear_button_color'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'clear_button_color_hover', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'clear_button_color_hover'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'book_now_button_color_hover', '#FFFFFF') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'book_now_button_color_hover'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'form_calendar_name_color', '#567BD2') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'form_calendar_name_color'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'wordpress_registration', '0') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'wordpress_registration'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'users_role', 'subscriber') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'users_role'
	) LIMIT 1;

	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'google_font_css_code', '') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'google_font_css_code'
	) LIMIT 1;

	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'google_font_link_code', '') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'google_font_link_code'
	) LIMIT 1;

	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_config (config_name, config_value)
	SELECT * FROM (SELECT 'recaptcha_version', '1') AS tmp
	WHERE NOT EXISTS (
		SELECT config_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name = 'recaptcha_version'
	) LIMIT 1;
	";
	
	
	dbDelta($sql5);
	
	$sql6="
	
	CREATE TABLE ".$wpdb->base_prefix.$blog_prefix."booking_emails (
	  email_id int(11) NOT NULL AUTO_INCREMENT,
	  email_name varchar(255) NOT NULL,
	  email_subject varchar(700) NOT NULL,
	  email_text text NOT NULL,
	  email_cancel_text text NOT NULL,
	  email_signature text NOT NULL,
	  PRIMARY KEY  (email_id)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	dbDelta($sql6);
	$package = array(
	 'kind' => 'WP Booking Calendar',
	 'name' => 'mails',
	 'title' => 'WP Booking Calendar - Mail'
	 );
	if($wpdb->query("select * from ".$wpdb->base_prefix.$blog_prefix."booking_emails where email_id = 1")==0) {
		$wpdb->insert($wpdb->base_prefix.$blog_prefix."booking_emails",array('email_id'=>1,'email_name'=>'Email sent automatically to customer to confirm reservation','email_subject'=>'Confirmation of Booking','email_text'=>'<p>Hello [customer-name],</p><p>thanks for Booking,<br />your Reservation details:</p><p>[reservation-details]</p><p></p>','email_cancel_text'=>'<p>If you want to cancel your reservation, simply click the link below:<br />[cancellation-link]<br />If the link is not clickable, please copy and paste this URL into your browser\'s address bar: [cancellation-link-url]</p>','email_signature'=>'<p>Thanks,<br />The Team<br /><br /></p>'));
		
		 do_action( 'wpml_register_string', 'Confirmation of Booking', "mail_subject_1", $package, "Mail 'Email sent automatically to customer to confirm reservation' Subject", 'LINE' );
		 do_action( 'wpml_register_string', '<p>Hello [customer-name],</p><p>thanks for Booking,<br />your Reservation details:</p><p>[reservation-details]</p><p></p>', "mail_text_1", $package, "Mail 'Email sent automatically to customer to confirm reservation' text", 'AREA' );
		 do_action( 'wpml_register_string', '<p>If you want to cancel your reservation, simply click the link below:<br />[cancellation-link]<br />If the link is not clickable, please copy and paste this URL into your browser\'s address bar: [cancellation-link-url]</p>', "mail_cancel_text_1", $package, "Mail 'Email sent automatically to customer to confirm reservation' cancellation text", 'AREA' );
		 do_action( 'wpml_register_string', '<p>Thanks,<br />The Team<br /><br /></p>', "mail_signature_1", $package, "Mail 'Email sent automatically to customer to confirm reservation' signature", 'AREA' );
		 do_action( 'wpml_register_string', 'Email sent automatically to customer to confirm reservation', "mail_name_1", $package, "Mail 'Email sent automatically to customer to confirm reservation' name", 'LINE' );
		 
	} else {
		
		$mailQry = $wpdb->get_results("select * from ".$wpdb->base_prefix.$blog_prefix."booking_emails where email_id = 1");
		do_action( 'wpml_register_string', $mailQry[0]->email_subject, "mail_subject_1", $package, "Mail '".$mailQry[0]->email_name."' Subject", 'LINE' );
		do_action( 'wpml_register_string', $mailQry[0]->email_text, "mail_text_1", $package, "Mail '".$mailQry[0]->email_name."' text", 'AREA' );
		do_action( 'wpml_register_string', $mailQry[0]->email_cancel_text, "mail_cancel_text_1", $package, "Mail '".$mailQry[0]->email_name."' cancellation text", 'AREA' );
		do_action( 'wpml_register_string', $mailQry[0]->email_signature, "mail_signature_1", $package, "Mail '".$mailQry[0]->email_name."' signature", 'AREA' );
		do_action( 'wpml_register_string', $mailQry[0]->email_name, "mail_name_1", $package, "Mail '".$mailQry[0]->email_name."' name", 'LINE' );
		
	}
	
	if($wpdb->query("select * from ".$wpdb->base_prefix.$blog_prefix."booking_emails where email_id = 2")==0) {
		$wpdb->insert($wpdb->base_prefix.$blog_prefix."booking_emails",array('email_id'=>2,'email_name'=>'Email sent automatically to customer to make him/her confirm the reservation via link','email_subject'=>'Confirm your Booking','email_text'=>'<p>Hello [customer-name],</p><p>we received your reservation request:</p><p>[reservation-details]</p><p>To confirm your reservation, simply click the link below to verify your email address:</p><p>[confirmation-link]</p><p>If the link is not clickable, please copy and paste this URL into your browser\'s address bar: [confirmation-link-url]</p>','email_cancel_text'=>'','email_signature'=>'<p>Thanks,<br />The Team<br /><br /></p>'));
		
		do_action( 'wpml_register_string', 'Confirm your Booking', "mail_subject_2", $package, "Mail 'Email sent automatically to customer to make him/her confirm the reservation via link' Subject", 'LINE' );
		do_action( 'wpml_register_string', '<p>Hello [customer-name],</p><p>we received your reservation request:</p><p>[reservation-details]</p><p>To confirm your reservation, simply click the link below to verify your email address:</p><p>[confirmation-link]</p><p>If the link is not clickable, please copy and paste this URL into your browser\'s address bar: [confirmation-link-url]</p>', "mail_text_2", $package, "Mail 'Email sent automatically to customer to make him/her confirm the reservation via link' text", 'AREA' );
		do_action( 'wpml_register_string', '', "mail_cancel_text_2", $package, "Mail 'Email sent automatically to customer to make him/her confirm the reservation via link' cancellation text", 'AREA' );
		do_action( 'wpml_register_string', '<p>Thanks,<br />The Team<br /><br /></p>', "mail_signature_2", $package, "Mail 'Email sent automatically to customer to make him/her confirm the reservation via link' signature", 'AREA' );
		do_action( 'wpml_register_string', 'Email sent automatically to customer to make him/her confirm the reservation via link', "mail_name_2", $package, "Mail 'Email sent automatically to customer to make him/her confirm the reservation via link' name", 'LINE' );
		
	} else {
		$mailQry = $wpdb->get_results("select * from ".$wpdb->base_prefix.$blog_prefix."booking_emails where email_id = 2");
		do_action( 'wpml_register_string', $mailQry[0]->email_subject, "mail_subject_2", $package, "Mail '".$mailQry[0]->email_name."' Subject", 'LINE' );
		do_action( 'wpml_register_string', $mailQry[0]->email_text, "mail_text_2", $package, "Mail '".$mailQry[0]->email_name."' text", 'AREA' );
		do_action( 'wpml_register_string', $mailQry[0]->email_cancel_text, "mail_cancel_text_2", $package, "Mail '".$mailQry[0]->email_name."' cancellation text", 'AREA' );
		do_action( 'wpml_register_string', $mailQry[0]->email_signature, "mail_signature_2", $package, "Mail '".$mailQry[0]->email_name."' signature", 'AREA' );
		do_action( 'wpml_register_string', $mailQry[0]->email_name, "mail_name_2", $package, "Mail '".$mailQry[0]->email_name."' name", 'LINE' );
		
	}
	
	if($wpdb->query("select * from ".$wpdb->base_prefix.$blog_prefix."booking_emails where email_id = 3")==0) {
		$wpdb->insert($wpdb->base_prefix.$blog_prefix."booking_emails",array('email_id'=>3,'email_name'=>'Email sent automatically to customer to tell him/her that you have to confirm his/her reservation','email_subject'=>'Confirmation of Booking','email_text'=>'<p>Hello [customer-name],</p><p>thanks for Booking, you\'ll receive a mail when your reservation/s will be confirmed.<br />Your Reservation details:</p><p>[reservation-details]</p>','email_cancel_text'=>'','email_signature'=>'<p>Thanks,<br />The Team<br /><br /></p>'));
		
		do_action( 'wpml_register_string', 'Confirmation of Booking', "mail_subject_3", $package, "Mail 'Email sent automatically to customer to tell him/her that you have to confirm his/her reservation' Subject", 'LINE' );
		do_action( 'wpml_register_string', '<p>Hello [customer-name],</p><p>thanks for Booking, you\'ll receive a mail when your reservation/s will be confirmed.<br />Your Reservation details:</p><p>[reservation-details]</p>', "mail_text_3", $package, "Mail 'Email sent automatically to customer to tell him/her that you have to confirm his/her reservation' text", 'AREA' );
		do_action( 'wpml_register_string', '', "mail_cancel_text_3", $package, "Mail 'Email sent automatically to customer to tell him/her that you have to confirm his/her reservation' cancellation text", 'AREA' );
		do_action( 'wpml_register_string', '<p>Thanks,<br />The Team<br /><br /></p>', "mail_signature_3", $package, "Mail 'Email sent automatically to customer to tell him/her that you have to confirm his/her reservation' signature", 'AREA' );
		do_action( 'wpml_register_string', 'Email sent automatically to customer to tell him/her that you have to confirm his/her reservation', "mail_name_3", $package, "Mail 'Email sent automatically to customer to tell him/her that you have to confirm his/her reservation' name", 'LINE' );
		
	} else {
		$mailQry = $wpdb->get_results("select * from ".$wpdb->base_prefix.$blog_prefix."booking_emails where email_id = 3");
		do_action( 'wpml_register_string', $mailQry[0]->email_subject, "mail_subject_3", $package, "Mail '".$mailQry[0]->email_name."' Subject", 'LINE' );
		do_action( 'wpml_register_string', $mailQry[0]->email_text, "mail_text_3", $package, "Mail '".$mailQry[0]->email_name."' text", 'AREA' );
		do_action( 'wpml_register_string', $mailQry[0]->email_cancel_text, "mail_cancel_text_3", $package, "Mail '".$mailQry[0]->email_name."' cancellation text", 'AREA' );
		do_action( 'wpml_register_string', $mailQry[0]->email_signature, "mail_signature_3", $package, "Mail '".$mailQry[0]->email_name."' signature", 'AREA' );
		do_action( 'wpml_register_string', $mailQry[0]->email_name, "mail_name_3", $package, "Mail '".$mailQry[0]->email_name."' name", 'LINE' );
	}
	
	if($wpdb->query("select * from ".$wpdb->base_prefix.$blog_prefix."booking_emails where email_id = 4")==0) {
		$wpdb->insert($wpdb->base_prefix.$blog_prefix."booking_emails",array('email_id'=>4,'email_name'=>'Email sent to customer when reservation is confirmed manually from admin panel','email_subject'=>'Booking Confirmed','email_text'=>'<p>Hello [customer-name],</p><p>Your reservation has been confirmed.<br />Reservation details:</p><p>[reservation-details]</p>','email_cancel_text'=>'<p>If you want to cancel your reservation, simply click the link below:<br />[cancellation-link]<br />If the link is not clickable, please copy and paste this URL into your browser\'s address bar: [cancellation-link-url]</p>','email_signature'=>'<p>Thanks,<br />The Team<br /><br /></p>'));
		
		do_action( 'wpml_register_string', 'Booking Confirmed', "mail_subject_4", $package, "Mail 'Email sent to customer when reservation is confirmed manually from admin panel' Subject", 'LINE' );
		do_action( 'wpml_register_string', '<p>Hello [customer-name],</p><p>Your reservation has been confirmed.<br />Reservation details:</p><p>[reservation-details]</p>', "mail_text_4", $package, "Mail 'Email sent to customer when reservation is confirmed manually from admin panel' text", 'AREA' );
		do_action( 'wpml_register_string', '<p>If you want to cancel your reservation, simply click the link below:<br />[cancellation-link]<br />If the link is not clickable, please copy and paste this URL into your browser\'s address bar: [cancellation-link-url]</p>', "mail_cancel_text_4", $package, "Mail 'Email sent to customer when reservation is confirmed manually from admin panel' cancellation text", 'AREA' );
		do_action( 'wpml_register_string', '<p>Thanks,<br />The Team<br /><br /></p>', "mail_signature_4", $package, "Mail 'Email sent to customer when reservation is confirmed manually from admin panel' signature", 'AREA' );
		do_action( 'wpml_register_string', 'Email sent to customer when reservation is confirmed manually from admin panel', "mail_name_4", $package, "Mail 'Email sent to customer when reservation is confirmed manually from admin panel' name", 'LINE' );
		
	} else {
		$mailQry = $wpdb->get_results("select * from ".$wpdb->base_prefix.$blog_prefix."booking_emails where email_id = 4");
		do_action( 'wpml_register_string', $mailQry[0]->email_subject, "mail_subject_4", $package, "Mail '".$mailQry[0]->email_name."' Subject", 'LINE' );
		do_action( 'wpml_register_string', $mailQry[0]->email_text, "mail_text_4", $package, "Mail '".$mailQry[0]->email_name."' text", 'AREA' );
		do_action( 'wpml_register_string', $mailQry[0]->email_cancel_text, "mail_cancel_text_4", $package, "Mail '".$mailQry[0]->email_name."' cancellation text", 'AREA' );
		do_action( 'wpml_register_string', $mailQry[0]->email_signature, "mail_signature_4", $package, "Mail '".$mailQry[0]->email_name."' signature", 'AREA' );
		do_action( 'wpml_register_string', $mailQry[0]->email_name, "mail_name_4", $package, "Mail '".$mailQry[0]->email_name."' name", 'LINE' );
		
	}
	
	
	$sql8="
	CREATE TABLE ".$wpdb->base_prefix.$blog_prefix."booking_holidays (
	  holiday_id int(11) NOT NULL AUTO_INCREMENT,
	  holiday_date date NOT NULL,
	  calendar_id int(11) NOT NULL,
	  PRIMARY KEY  (holiday_id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
	";
	dbDelta($sql8);
	
	
	$sql9="
	CREATE TABLE ".$wpdb->base_prefix.$blog_prefix."booking_reservation (
	  reservation_id int(11) NOT NULL AUTO_INCREMENT,
	  slot_id int(11) NOT NULL,
	  reservation_name varchar(255) NOT NULL,
	  reservation_surname varchar(255) NOT NULL,
	  reservation_email varchar(255) NOT NULL,
	  reservation_phone varchar(255) NOT NULL,
	  reservation_message text NOT NULL,
	  reservation_seats int(11) NOT NULL,
	  reservation_field1 text NOT NULL,
	  reservation_field2 text NOT NULL,
	  reservation_field3 text NOT NULL,
	  reservation_field4 text NOT NULL,
	  reservation_confirmed int(11) NOT NULL DEFAULT '0',
	  reservation_cancelled int(11) NOT NULL DEFAULT '0',
	  reservation_fake int(11) NOT NULL DEFAULT '0',
	  calendar_id int(11) NOT NULL,
	  post_id int(11) NOT NULL,
	  admin_confirmed_cancelled int(11) NOT NULL DEFAULT '0',
	  wordpress_user_id int(11) NOT NULL,
	  PRIMARY KEY  (reservation_id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	";
	dbDelta($sql9);
    try {
        $wpdb->suppress_errors(true);
        $wpdb->query("ALTER TABLE ".$wpdb->base_prefix.$blog_prefix."booking_reservation
  	ADD KEY calendar_id (calendar_id),
  	ADD KEY slot_cancelled (reservation_cancelled,slot_id) USING BTREE;");
        /*$sql9bis="ALTER TABLE ".$wpdb->base_prefix.$blog_prefix."booking_reservation
          ADD KEY calendar_id (calendar_id),
          ADD KEY slot_cancelled (reservation_cancelled,slot_id) USING BTREE;";
          dbDelta($sql9bis);*/
    } catch(Exception $e) {

    }
    $wpdb->suppress_errors(false);
	$sql10="
	CREATE TABLE ".$wpdb->base_prefix.$blog_prefix."booking_slots_bundles (
	  bundle_id int(11) NOT NULL AUTO_INCREMENT,
	  bundle_name varchar(700) NOT NULL,
	  bundle_text varchar(700) NOT NULL,
	  PRIMARY KEY  (bundle_id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	";
	dbDelta($sql10);
	$sql10="
	CREATE TABLE ".$wpdb->base_prefix.$blog_prefix."booking_slots (
	  slot_id int(11) NOT NULL AUTO_INCREMENT,
	  slot_special_text varchar(700) NOT NULL,
	  slot_special_mode int(11) NOT NULL,
	  slot_date date NOT NULL,
	  slot_time_from time NOT NULL,
	  slot_time_to time NOT NULL,
	  slot_price double NOT NULL,
	  slot_perc_price double NOT NULL,
	  slot_discount_price double NOT NULL,
	  slot_av INT NOT NULL,
	  slot_av_max INT NOT NULL,
	  slot_active int(11) NOT NULL,
	  calendar_id int(11) NOT NULL,
	  bundle_id int(11) NOT NULL,
	  slot_show_price int(11) NOT NULL,
	  PRIMARY KEY  (slot_id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	";
	dbDelta($sql10);
    try  {
        $wpdb->suppress_errors(true);
        $wpdb->query("ALTER TABLE ".$wpdb->base_prefix.$blog_prefix."booking_slots
  	ADD KEY slot_time_from (slot_time_from),
  	ADD KEY date_active_cal (slot_active,calendar_id,slot_date) USING BTREE;");
        /*$sql10bis="ALTER TABLE ".$wpdb->base_prefix.$blog_prefix."booking_slots
          ADD KEY slot_time_from (slot_time_from),
          ADD KEY date_active_cal (slot_active,calendar_id,slot_date) USING BTREE;";
          dbDelta($sql10bis);*/
    } catch(Exception $e) {

    }
    $wpdb->suppress_errors(false);
	$sql11="
	CREATE TABLE ".$wpdb->base_prefix.$blog_prefix."booking_timezones (
	  timezone_id int(11) NOT NULL AUTO_INCREMENT,
	  timezone_name varchar(255) NOT NULL,
	  timezone_value varchar(255) NOT NULL,
	  PRIMARY KEY  (timezone_id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;
	";
	dbDelta($sql11);
	$sql12="
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Kwajalein GMT -12.00', 'Kwajalein') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Kwajalein GMT -12.00' AND timezone_value = 'Kwajalein'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Pacific/Midway GMT -11.00', 'Pacific/Midway') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Pacific/Midway GMT -11.00' AND timezone_value = 'Pacific/Midway'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Pacific/Honolulu GMT -10.00', 'Pacific/Honolulu') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Pacific/Honolulu GMT -10.00' AND timezone_value = 'Pacific/Honolulu'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'America/Anchorage GMT -9.00', 'America/Anchorage') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'America/Anchorage GMT -9.00' AND timezone_value = 'America/Anchorage'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'America/Los Angeles GMT -8.00', 'America/Los_Angeles') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'America/Los Angeles GMT -8.00' AND timezone_value = 'America/Los_Angeles'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'America/Denver GMT -7.00', 'America/Denver') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'America/Denver GMT -7.00' AND timezone_value = 'America/Denver'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'America/Tegucigalpa GMT -6.00', 'America/Tegucigalpa') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'America/Tegucigalpa GMT -6.00' AND timezone_value = 'America/Tegucigalpa'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'America/New York GMT -5.00', 'America/New_York') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'America/New York GMT -5.00' AND timezone_value = 'America/New_York'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'America/Caracas GMT -4.30', 'America/Caracas') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'America/Caracas GMT -4.30' AND timezone_value = 'America/Caracas'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'America/Halifax GMT -4.00', 'America/Halifax') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'America/Halifax GMT -4.00' AND timezone_value = 'America/Halifax'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'America/St Johns GMT -3.30', 'America/St_Johns') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'America/St Johns GMT -3.30' AND timezone_value = 'America/St_Johns'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'America/Argentina/Buenos Aires GMT -3.00', 'America/Argentina/Buenos_Aires') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'America/Argentina/Buenos Aires GMT -3.00' AND timezone_value = 'America/Argentina/Buenos_Aires'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'America/Sao Paulo GMT -3.00', 'America/Sao_Paulo') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'America/Sao Paulo GMT -3.00' AND timezone_value = 'America/Sao_Paulo'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Atlantic/South_Georgia GMT -2.00', 'Atlantic/South Georgia') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Atlantic/South_Georgia GMT -2.00' AND timezone_value = 'Atlantic/South Georgia'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Atlantic/Azores GMT -1.00', 'Atlantic/Azores') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Atlantic/Azores GMT -1.00' AND timezone_value = 'Atlantic/Azores'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Europe/Dublin GMT 0', 'Europe/Dublin') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Europe/Dublin GMT 0' AND timezone_value = 'Europe/Dublin'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Europe/Belgrade GMT 1.00', 'Europe/Belgrade') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Europe/Belgrade GMT 1.00' AND timezone_value = 'Europe/Belgrade'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Europe/Athens GMT 2.00', 'Europe/Athens') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Europe/Athens GMT 2.00' AND timezone_value = 'Europe/Athens'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Asia/Kuwait GMT 3.00', 'Asia/Kuwait') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Asia/Kuwait GMT 3.00' AND timezone_value = 'Asia/Kuwait'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Asia/Tehran GMT 3.30', 'Asia/Tehran') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Asia/Tehran GMT 3.30' AND timezone_value = 'Asia/Tehran'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Asia/Muscat GMT 4.00', 'Asia/Muscat') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Asia/Muscat GMT 4.00' AND timezone_value = 'Asia/Muscat'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Asia/Yekaterinburg GMT 5.00', 'Asia/Yekaterinburg') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Asia/Yekaterinburg GMT 5.00' AND timezone_value = 'Asia/Yekaterinburg'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Asia/Kolkata GMT 5.30', 'Asia/Kolkata') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Asia/Kolkata GMT 5.30' AND timezone_value = 'Asia/Kolkata'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Asia/Katmandu GMT 5.45', 'Asia/Katmandu') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Asia/Katmandu GMT 5.45' AND timezone_value = 'Asia/Katmandu'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Asia/Dhaka GMT 6.00', 'Asia/Dhaka') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Asia/Dhaka GMT 6.00' AND timezone_value = 'Asia/Dhaka'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Asia/Rangoon GMT 6.30', 'Asia/Rangoon') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Asia/Rangoon GMT 6.30' AND timezone_value = 'Asia/Rangoon'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Asia/Krasnoyarsk GMT 7.00', 'Asia/Krasnoyarsk') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Asia/Krasnoyarsk GMT 7.00' AND timezone_value = 'Asia/Krasnoyarsk'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Asia/Brunei GMT 8.00', 'Asia/Brunei') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Asia/Brunei GMT 8.00' AND timezone_value = 'Asia/Brunei'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Asia/Seoul GMT 9.00', 'Asia/Seoul') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Asia/Seoul GMT 9.00' AND timezone_value = 'Asia/Seoul'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Australia/Darwin GMT 9.30', 'Australia/Darwin') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Australia/Darwin GMT 9.30' AND timezone_value = 'Australia/Darwin'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Australia/Canberra GMT 10.00', 'Australia/Canberra') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Australia/Canberra GMT 10.00' AND timezone_value = 'Australia/Canberra'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Asia/Magadan GMT 11.00', 'Asia/Magadan') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Asia/Magadan GMT 11.00' AND timezone_value = 'Asia/Magadan'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Pacific/Fiji GMT 12.00', 'Pacific/Fiji') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Pacific/Fiji GMT 12.00' AND timezone_value = 'Pacific/Fiji'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_timezones (timezone_name, timezone_value)
	SELECT * FROM (SELECT  'Pacific/Tongatapu GMT 13.00', 'Pacific/Tongatapu') AS tmp
	WHERE NOT EXISTS (
		SELECT timezone_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_timezones WHERE timezone_name = 'Pacific/Tongatapu GMT 13.00' AND timezone_value = 'Pacific/Tongatapu'
	) LIMIT 1;
	
	";
	
	dbDelta($sql12);
	
	$sql13="
	CREATE TABLE ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (
	  currency_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	  currency_name varchar(255) NOT NULL DEFAULT '',
	  currency_code char(3) NOT NULL DEFAULT '',
	  PRIMARY KEY  (currency_id)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	";
	
	dbDelta($sql13);
	$sql14="
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'Australian Dollar','AUD') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'Australian Dollar' AND currency_code = 'AUD'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'Canadian Dollar','CAD') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'Canadian Dollar' AND currency_code = 'CAD'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'Czech Koruna','CZK') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'Czech Koruna' AND currency_code = 'CZK'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'Danish Krone','DKK') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'Danish Krone' AND currency_code = 'DKK'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'Euro','EUR') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'Euro' AND currency_code = 'EUR'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'Hong Kong Dollar','HKD') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'Hong Kong Dollar' AND currency_code = 'HKD'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'Hungarian Forint','HUF') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'Hungarian Forint' AND currency_code = 'HUF'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'Israeli New Sheqel','ILS') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'Israeli New Sheqel' AND currency_code = 'ILS'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'Japanese Yen','JPY') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'Japanese Yen' AND currency_code = 'JPY'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'Mexican Peso','MXN') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'Mexican Peso' AND currency_code = 'MXN'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT 'Norwegian Krone','NOK') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'Norwegian Krone' AND currency_code = 'NOK'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'New Zealand Dollar','NZD') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'New Zealand Dollar' AND currency_code = 'NZD'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'Polish Zloty','PLN') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'Polish Zloty' AND currency_code = 'PLN'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'Pound Sterling','GBP') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'Pound Sterling' AND currency_code = 'GBP'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'Singapore Dollar','SGD') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'Singapore Dollar' AND currency_code = 'SGD'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'Swedish Krona','SEK') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'Swedish Krona' AND currency_code = 'SEK'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'Swiss Franc','CHF') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'Swiss Franc' AND currency_code = 'CHF'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'U.S. Dollar','USD') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'U.S. Dollar' AND currency_code = 'USD'
	) LIMIT 1; 
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency (currency_name, currency_code)
	SELECT * FROM (SELECT  'Brazilian Real', 'BRL') AS tmp
	WHERE NOT EXISTS (
		SELECT currency_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency WHERE currency_name = 'Brazilian Real' AND currency_code = 'BRL'
	) LIMIT 1; 
		
		
		
	";
	dbDelta($sql14);
	
	$sql15="
	CREATE TABLE ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (
	  locale_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	  locale_country varchar(255) NOT NULL DEFAULT '',
	  locale_code char(3) NOT NULL DEFAULT '',
	  PRIMARY KEY  (locale_id)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	";
	dbDelta($sql15);
	
	$sql16="
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'AFGHANISTAN','AF') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'AFGHANISTAN' AND locale_code = 'AF'
	) LIMIT 1; 
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ALAND ISLANDS','AX') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ALAND ISLANDS' AND locale_code = 'AX'
	) LIMIT 1; 
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ALBANIA','AL') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ALBANIA' AND locale_code = 'AL'
	) LIMIT 1; 
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ALGERIA','DZ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ALGERIA' AND locale_code = 'DZ'
	) LIMIT 1; 
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'AMERICAN SAMOA','AS') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'AMERICAN SAMOA' AND locale_code = 'AS'
	) LIMIT 1; 
	
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ANDORRA','AD') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ANDORRA' AND locale_code = 'AD'
	) LIMIT 1; 
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ANGOLA','AO') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ANGOLA' AND locale_code = 'AO'
	) LIMIT 1; 
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ANGUILLA','AI') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ANGUILLA' AND locale_code = 'AI'
	) LIMIT 1; 
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ANTARCTICA','AQ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ANTARCTICA' AND locale_code = 'AQ'
	) LIMIT 1; 
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ANTIGUA AND BARBUDA','AG') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ANTIGUA AND BARBUDA' AND locale_code = 'AG'
	) LIMIT 1; 
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ARGENTINA','AR') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ARGENTINA' AND locale_code = 'AR'
	) LIMIT 1; 
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ARMENIA','AM') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ARMENIA' AND locale_code = 'AM'
	) LIMIT 1; 
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ARUBA','AW') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ARUBA' AND locale_code = 'AW'
	) LIMIT 1; 
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'AUSTRALIA','AU') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'AUSTRALIA' AND locale_code = 'AU'
	) LIMIT 1; 
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'AUSTRIA','AT') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'AUSTRIA' AND locale_code = 'AT'
	) LIMIT 1; 
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'AZERBAIJAN','AZ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'AZERBAIJAN' AND locale_code = 'AZ'
	) LIMIT 1; 
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BAHAMAS','BS') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BAHAMAS' AND locale_code = 'BS'
	) LIMIT 1; 
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BAHRAIN','BH') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BAHRAIN' AND locale_code = 'BH'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BANGLADESH','BD') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BANGLADESH' AND locale_code = 'BD'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BARBADOS','BB') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BARBADOS' AND locale_code = 'BB'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BELARUS','BY') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BELARUS' AND locale_code = 'BY'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BELGIUM','BE') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BELGIUM' AND locale_code = 'BE'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BELIZE','BZ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BELIZE' AND locale_code = 'BZ'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BENIN','BJ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BENIN' AND locale_code = 'BJ'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BERMUDA','BM') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BERMUDA' AND locale_code = 'BM'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BHUTAN','BT') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BHUTAN' AND locale_code = 'BT'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BOLIVIA','BO') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BOLIVIA' AND locale_code = 'BO'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BOSNIA AND HERZEGOVINA','BA') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BOSNIA AND HERZEGOVINA' AND locale_code = 'BA'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BOTSWANA','BW') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BOTSWANA' AND locale_code = 'BW'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BOUVET ISLAND','BV') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BOUVET ISLAND' AND locale_code = 'BV'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BRAZIL','BR') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BRAZIL' AND locale_code = 'BR'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BRITISH INDIAN OCEAN TERRITORY','IO') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BRITISH INDIAN OCEAN TERRITORY' AND locale_code = 'IO'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BRUNEI DARUSSALAM','BN') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BRUNEI DARUSSALAM' AND locale_code = 'BN'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BULGARIA','BG') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BULGARIA' AND locale_code = 'BG'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BURKINA FASO','BF') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BURKINA FASO' AND locale_code = 'BF'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'BURUNDI','BI') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'BURUNDI' AND locale_code = 'BI'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'CAMBODIA','KH') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'CAMBODIA' AND locale_code = 'KH'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'CAMEROON','CM') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'CAMEROON' AND locale_code = 'CM'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'CANADA','CA') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'CANADA' AND locale_code = 'CA'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'CAPE VERDE','CV') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'CAPE VERDE' AND locale_code = 'CV'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'CAYMAN ISLANDS','KY') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'CAYMAN ISLANDS' AND locale_code = 'KY'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'CENTRAL AFRICAN REPUBLIC','CF') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'CENTRAL AFRICAN REPUBLIC' AND locale_code = 'CF'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'CHAD','TD') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'CHAD' AND locale_code = 'TD'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'CHILE','CL') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'CHILE' AND locale_code = 'CL'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'CHINA','CN') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'CHINA' AND locale_code = 'CN'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'CHRISTMAS ISLAND','CX') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'CHRISTMAS ISLAND' AND locale_code = 'CX'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'COCOS (KEELING) ISLANDS','CC') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'COCOS (KEELING) ISLANDS' AND locale_code = 'CC'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'COLOMBIA','CO') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'COLOMBIA' AND locale_code = 'CO'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'COMOROS','KM') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'COMOROS' AND locale_code = 'KM'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'CONGO','CG') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'CONGO' AND locale_code = 'CG'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'CONGO, THE DEMOCRATIC REPUBLIC OF THE','CD') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'CONGO, THE DEMOCRATIC REPUBLIC OF THE' AND locale_code = 'CD'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'COOK ISLANDS','CK') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'COOK ISLANDS' AND locale_code = 'CK'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'COSTA RICA','CR') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'COSTA RICA' AND locale_code = 'CR'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'COTE D\'IVOIRE','CI') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'COTE D\'IVOIRE' AND locale_code = 'CI'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'CROATIA','HR') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'CROATIA' AND locale_code = 'HR'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'CUBA','CU') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'CUBA' AND locale_code = 'CU'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'CYPRUS','CY') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'CYPRUS' AND locale_code = 'CY'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'CZECH REPUBLIC','CZ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'CZECH REPUBLIC' AND locale_code = 'CZ'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'DENMARK','DK') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'DENMARK' AND locale_code = 'DK'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'DJIBOUTI','DJ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'DJIBOUTI' AND locale_code = 'DJ'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'DOMINICA','DM') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'DOMINICA' AND locale_code = 'DM'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'DOMINICAN REPUBLIC','DO') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'DOMINICAN REPUBLIC' AND locale_code = 'DO'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ECUADOR','EC') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ECUADOR' AND locale_code = 'EC'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'EGYPT','EG') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'EGYPT' AND locale_code = 'EG'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'EL SALVADOR','SV') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'EL SALVADOR' AND locale_code = 'SV'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'EQUATORIAL GUINEA','GQ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'EQUATORIAL GUINEA' AND locale_code = 'GQ'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ERITREA','ER') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ERITREA' AND locale_code = 'ER'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ESTONIA','EE') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ESTONIA' AND locale_code = 'EE'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ETHIOPIA','ET') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ETHIOPIA' AND locale_code = 'ET'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'FALKLAND ISLANDS (MALVINAS)','FK') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'FALKLAND ISLANDS (MALVINAS)' AND locale_code = 'FK'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'FAROE ISLANDS','FO') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'FAROE ISLANDS' AND locale_code = 'FO'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'FIJI','FJ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'FIJI' AND locale_code = 'FJ'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'FINLAND','FI') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'FINLAND' AND locale_code = 'FI'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'FRANCE','FR') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'FRANCE' AND locale_code = 'FR'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'FRENCH GUIANA','GF') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'FRENCH GUIANA' AND locale_code = 'GF'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'FRENCH POLYNESIA','PF') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'FRENCH POLYNESIA' AND locale_code = 'PF'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'FRENCH SOUTHERN TERRITORIES','TF') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'FRENCH SOUTHERN TERRITORIES' AND locale_code = 'TF'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'GABON','GA') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'GABON' AND locale_code = 'GA'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'GAMBIA','GM') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'GAMBIA' AND locale_code = 'GM'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'GEORGIA','GE') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'GEORGIA' AND locale_code = 'GE'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'GERMANY','DE') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'GERMANY' AND locale_code = 'DE'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'GHANA','GH') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'GHANA' AND locale_code = 'GH'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'GIBRALTAR','GI') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'GIBRALTAR' AND locale_code = 'GI'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'GREECE','GR') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'GREECE' AND locale_code = 'GR'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'GREENLAND','GL') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'GREENLAND' AND locale_code = 'GL'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'GRENADA','GD') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'GRENADA' AND locale_code = 'GD'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'GUADELOUPE','GP') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'GUADELOUPE' AND locale_code = 'GP'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'GUAM','GU') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'GUAM' AND locale_code = 'GU'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'GUATEMALA','GT') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'GUATEMALA' AND locale_code = 'GT'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'GUERNSEY','GG') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'GUERNSEY' AND locale_code = 'GG'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'GUINEA','GN') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'GUINEA' AND locale_code = 'GN'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'GUINEA-BISSAU','GW') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'GUINEA-BISSAU' AND locale_code = 'GW'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'GUYANA','GY') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'GUYANA' AND locale_code = 'GY'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'HAITI','HT') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'HAITI' AND locale_code = 'HT'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'HEARD ISLAND AND MCDONALD ISLANDS','HM') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'HEARD ISLAND AND MCDONALD ISLANDS' AND locale_code = 'HM'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'HOLY SEE (VATICAN CITY STATE)','VA') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'HOLY SEE (VATICAN CITY STATE)' AND locale_code = 'VA'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'HONDURAS','HN') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'HONDURAS' AND locale_code = 'HN'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'HONG KONG','HK') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'HONG KONG' AND locale_code = 'HK'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'HUNGARY','HU') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'HUNGARY' AND locale_code = 'HU'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ICELAND','IS') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ICELAND' AND locale_code = 'IS'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'INDIA','IN') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'INDIA' AND locale_code = 'IN'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'INDONESIA','ID') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'INDONESIA' AND locale_code = 'ID'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'IRAN, ISLAMIC REPUBLIC OF','IR') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'IRAN, ISLAMIC REPUBLIC OF' AND locale_code = 'IR'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'IRAQ','IQ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'IRAQ' AND locale_code = 'IQ'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'IRELAND','IE') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'IRELAND' AND locale_code = 'IE'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ISLE OF MAN','IM') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ISLE OF MAN' AND locale_code = 'IM'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ISRAEL','IL') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ISRAEL' AND locale_code = 'IL'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ITALY','IT') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ITALY' AND locale_code = 'IT'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'JAMAICA','JM') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'JAMAICA' AND locale_code = 'JM'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'JAPAN','JP') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'JAPAN' AND locale_code = 'JP'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'JERSEY','JE') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'JERSEY' AND locale_code = 'JE'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'JORDAN','JO') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'JORDAN' AND locale_code = 'JO'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'KAZAKHSTAN','KZ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'KAZAKHSTAN' AND locale_code = 'KZ'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'KENYA','KE') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'KENYA' AND locale_code = 'KE'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'KIRIBATI','KI') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'KIRIBATI' AND locale_code = 'KI'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF','KP') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF' AND locale_code = 'KP'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'KOREA, REPUBLIC OF','KR') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'KOREA, REPUBLIC OF' AND locale_code = 'KR'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'KUWAIT','KW') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'KUWAIT' AND locale_code = 'KW'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'KYRGYZSTAN','KG') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'KYRGYZSTAN' AND locale_code = 'KG'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'LAO PEOPLE\'S DEMOCRATIC REPUBLIC','LA') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC' AND locale_code = 'LA'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'LATVIA','LV') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'LATVIA' AND locale_code = 'LV'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'LEBANON','LB') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'LEBANON' AND locale_code = 'LB'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'LESOTHO','LS') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'LESOTHO' AND locale_code = 'LS'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'LIBERIA','LR') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'LIBERIA' AND locale_code = 'LR'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'LIBYAN ARAB JAMAHIRIYA','LY') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'LIBYAN ARAB JAMAHIRIYA' AND locale_code = 'LY'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'LIECHTENSTEIN','LI') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'LIECHTENSTEIN' AND locale_code = 'LI'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'LITHUANIA','LT') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'LITHUANIA' AND locale_code = 'LT'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'LUXEMBOURG','LU') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'LUXEMBOURG' AND locale_code = 'LU'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MACAO','MO') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MACAO' AND locale_code = 'MO'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','MK') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF' AND locale_code = 'MK'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MADAGASCAR','MG') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MADAGASCAR' AND locale_code = 'MG'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MALAWI','MW') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MALAWI' AND locale_code = 'MW'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MALAYSIA','MY') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MALAYSIA' AND locale_code = 'MY'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MALDIVES','MV') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MALDIVES' AND locale_code = 'MV'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MALI','ML') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MALI' AND locale_code = 'ML'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MALTA','MT') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MALTA' AND locale_code = 'MT'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MARSHALL ISLANDS','MH') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MARSHALL ISLANDS' AND locale_code = 'MH'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MARTINIQUE','MQ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MARTINIQUE' AND locale_code = 'MQ'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MAURITANIA','MR') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MAURITANIA' AND locale_code = 'MR'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MAURITIUS','MU') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MAURITIUS' AND locale_code = 'MU'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MAYOTTE','YT') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MAYOTTE' AND locale_code = 'YT'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MEXICO','MX') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MEXICO' AND locale_code = 'MX'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MICRONESIA, FEDERATED STATES OF','FM') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MICRONESIA, FEDERATED STATES OF' AND locale_code = 'FM'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MOLDOVA, REPUBLIC OF','MD') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MOLDOVA, REPUBLIC OF' AND locale_code = 'MD'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MONACO','MC') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MONACO' AND locale_code = 'MC'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MONGOLIA','MN') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MONGOLIA' AND locale_code = 'MN'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MONTSERRAT','MS') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MONTSERRAT' AND locale_code = 'MS'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MOROCCO','MA') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MOROCCO' AND locale_code = 'MA'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MOZAMBIQUE','MZ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MOZAMBIQUE' AND locale_code = 'MZ'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'MYANMAR','MM') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'MYANMAR' AND locale_code = 'MM'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'NAMIBIA','NA') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'NAMIBIA' AND locale_code = 'NA'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'NAURU','NR') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'NAURU' AND locale_code = 'NR'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'NEPAL','NP') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'NEPAL' AND locale_code = 'NP'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'NETHERLANDS','NL') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'NETHERLANDS' AND locale_code = 'NL'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'NETHERLANDS ANTILLES','AN') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'NETHERLANDS ANTILLES' AND locale_code = 'AN'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'NEW CALEDONIA','NC') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'NEW CALEDONIA' AND locale_code = 'NC'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'NEW ZEALAND','NZ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'NEW ZEALAND' AND locale_code = 'NZ'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'NICARAGUA','NI') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'NICARAGUA' AND locale_code = 'NI'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'NIGER','NE') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'NIGER' AND locale_code = 'NE'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'NIGERIA','NG') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'NIGERIA' AND locale_code = 'NG'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'NIUE','NU') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'NIUE' AND locale_code = 'NU'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'NORFOLK ISLAND','NF') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'NORFOLK ISLAND' AND locale_code = 'NF'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'NORTHERN MARIANA ISLANDS','MP') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'NORTHERN MARIANA ISLANDS' AND locale_code = 'MP'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'NORWAY','NO') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'NORWAY' AND locale_code = 'NO'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'OMAN','OM') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'OMAN' AND locale_code = 'OM'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'PAKISTAN','PK') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'PAKISTAN' AND locale_code = 'PK'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'PALAU','PW') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'PALAU' AND locale_code = 'PW'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'PALESTINIAN TERRITORY, OCCUPIED','PS') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'PALESTINIAN TERRITORY, OCCUPIED' AND locale_code = 'PS'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'PANAMA','PA') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'PANAMA' AND locale_code = 'PA'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'PAPUA NEW GUINEA','PG') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'PAPUA NEW GUINEA' AND locale_code = 'PG'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'PARAGUAY','PY') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'PARAGUAY' AND locale_code = 'PY'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'PERU','PE') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'PERU' AND locale_code = 'PE'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'PHILIPPINES','PH') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'PHILIPPINES' AND locale_code = 'PH'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'PITCAIRN','PN') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'PITCAIRN' AND locale_code = 'PN'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'POLAND','PL') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'POLAND' AND locale_code = 'PL'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'PORTUGAL','PT') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'PORTUGAL' AND locale_code = 'PT'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'PUERTO RICO','PR') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'PUERTO RICO' AND locale_code = 'PR'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'QATAR','QA') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'QATAR' AND locale_code = 'QA'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'REUNION','RE') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'REUNION' AND locale_code = 'RE'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ROMANIA','RO') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ROMANIA' AND locale_code = 'RO'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'RUSSIAN FEDERATION','RU') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'RUSSIAN FEDERATION' AND locale_code = 'RU'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'RWANDA','RW') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'RWANDA' AND locale_code = 'RW'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SAINT HELENA','SH') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SAINT HELENA' AND locale_code = 'SH'
	) LIMIT 1;
		
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SAINT KITTS AND NEVIS','KN') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SAINT KITTS AND NEVIS' AND locale_code = 'KN'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SAINT LUCIA','LC') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SAINT LUCIA' AND locale_code = 'LC'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SAINT PIERRE AND MIQUELON','PM') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SAINT PIERRE AND MIQUELON' AND locale_code = 'PM'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SAINT VINCENT AND THE GRENADINES','VC') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SAINT VINCENT AND THE GRENADINES' AND locale_code = 'VC'
	) LIMIT 1;
		
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SAMOA','WS') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SAMOA' AND locale_code = 'WS'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SAN MARINO','SM') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SAN MARINO' AND locale_code = 'SM'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SAO TOME AND PRINCIPE','ST') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SAO TOME AND PRINCIPE' AND locale_code = 'ST'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SAUDI ARABIA','SA') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SAUDI ARABIA' AND locale_code = 'SA'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SENEGAL','SN') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SENEGAL' AND locale_code = 'SN'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SERBIA AND MONTENEGRO','CS') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SERBIA AND MONTENEGRO' AND locale_code = 'CS'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SEYCHELLES','SC') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SEYCHELLES' AND locale_code = 'SC'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SIERRA LEONE','SL') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SIERRA LEONE' AND locale_code = 'SL'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SINGAPORE','SG') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SINGAPORE' AND locale_code = 'SG'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SLOVAKIA','SK') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SLOVAKIA' AND locale_code = 'SK'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SLOVENIA','SI') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SLOVENIA' AND locale_code = 'SI'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SOLOMON ISLANDS','SB') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SOLOMON ISLANDS' AND locale_code = 'SB'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SOMALIA','SO') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SOMALIA' AND locale_code = 'SO'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SOUTH AFRICA','ZA') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SOUTH AFRICA' AND locale_code = 'ZA'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS','GS') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS' AND locale_code = 'GS'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SPAIN','ES') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SPAIN' AND locale_code = 'ES'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SRI LANKA','LK') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SRI LANKA' AND locale_code = 'LK'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SUDAN','SD') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SUDAN' AND locale_code = 'SD'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SURINAME','SR') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SURINAME' AND locale_code = 'SR'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SVALBARD AND JAN MAYEN','SJ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SVALBARD AND JAN MAYEN' AND locale_code = 'SJ'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SWAZILAND','SZ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SWAZILAND' AND locale_code = 'SZ'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SWEDEN','SE') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SWEDEN' AND locale_code = 'SE'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SWITZERLAND','CH') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SWITZERLAND' AND locale_code = 'CH'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'SYRIAN ARAB REPUBLIC','SY') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'SYRIAN ARAB REPUBLIC' AND locale_code = 'SY'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'TAIWAN, PROVINCE OF CHINA','TW') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'TAIWAN, PROVINCE OF CHINA' AND locale_code = 'TW'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'TAJIKISTAN','TJ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'TAJIKISTAN' AND locale_code = 'TJ'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'TANZANIA, UNITED REPUBLIC OF','TZ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'TANZANIA, UNITED REPUBLIC OF' AND locale_code = 'TZ'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'THAILAND','TH') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'THAILAND' AND locale_code = 'TH'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'TIMOR-LESTE','TL') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'TIMOR-LESTE' AND locale_code = 'TL'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'TOGO','TG') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'TOGO' AND locale_code = 'TG'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'TOKELAU','TK') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'TOKELAU' AND locale_code = 'TK'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'TONGA','TO') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'TONGA' AND locale_code = 'TO'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'TRINIDAD AND TOBAGO','TT') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'TRINIDAD AND TOBAGO' AND locale_code = 'TT'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'TUNISIA','TN') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'TUNISIA' AND locale_code = 'TN'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'TURKEY','TR') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'TURKEY' AND locale_code = 'TR'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'TURKMENISTAN','TM') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'TURKMENISTAN' AND locale_code = 'TM'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'TURKS AND CAICOS ISLANDS','TC') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'TURKS AND CAICOS ISLANDS' AND locale_code = 'TC'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'TUVALU','TV') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'TUVALU' AND locale_code = 'TV'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'UGANDA','UG') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'UGANDA' AND locale_code = 'UG'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'UKRAINE','UA') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'UKRAINE' AND locale_code = 'UA'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'UNITED ARAB EMIRATES','AE') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'UNITED ARAB EMIRATES' AND locale_code = 'AE'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'UNITED KINGDOM','GB') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'UNITED KINGDOM' AND locale_code = 'GB'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'UNITED STATES','US') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'UNITED STATES' AND locale_code = 'US'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'UNITED STATES MINOR OUTLYING ISLANDS','UM') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'UNITED STATES MINOR OUTLYING ISLANDS' AND locale_code = 'UM'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'URUGUAY','UY') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'URUGUAY' AND locale_code = 'UY'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'UZBEKISTAN','UZ') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'UZBEKISTAN' AND locale_code = 'UZ'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'VANUATU','VU') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'VANUATU' AND locale_code = 'VU'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'VENEZUELA','VE') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'VENEZUELA' AND locale_code = 'VE'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'VIET NAM','VN') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'VIET NAM' AND locale_code = 'VN'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'VIRGIN ISLANDS, BRITISH','VG') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'VIRGIN ISLANDS, BRITISH' AND locale_code = 'VG'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'VIRGIN ISLANDS, U.S.','VI') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'VIRGIN ISLANDS, U.S.' AND locale_code = 'VI'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'WALLIS AND FUTUNA','WF') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'WALLIS AND FUTUNA' AND locale_code = 'WF'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'WESTERN SAHARA','EH') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'WESTERN SAHARA' AND locale_code = 'EH'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'YEMEN','YE') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'YEMEN' AND locale_code = 'YE'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ZAMBIA','ZM') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ZAMBIA' AND locale_code = 'ZM'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale (locale_country, locale_code)
	SELECT * FROM (SELECT  'ZIMBABWE','ZW') AS tmp
	WHERE NOT EXISTS (
		SELECT locale_country FROM ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale WHERE locale_country = 'ZIMBABWE' AND locale_code = 'ZW'
	) LIMIT 1;
	
	";
	dbDelta($sql16);
	
	$sql17="CREATE TABLE ".$wpdb->base_prefix.$blog_prefix."booking_fields_types (
	  type_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	  reservation_field_name varchar(255) NOT NULL DEFAULT '',
	  reservation_field_type varchar(255) NOT NULL DEFAULT '',
	  PRIMARY KEY  (type_id)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	dbDelta($sql17);
	
	$sql18="
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_fields_types (reservation_field_name, reservation_field_type)
	SELECT * FROM (SELECT 'reservation_name', 'text') AS tmp
	WHERE NOT EXISTS (
		SELECT reservation_field_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_fields_types WHERE reservation_field_name = 'reservation_name'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_fields_types (reservation_field_name, reservation_field_type)
	SELECT * FROM (SELECT 'reservation_surname', 'text') AS tmp
	WHERE NOT EXISTS (
		SELECT reservation_field_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_fields_types WHERE reservation_field_name = 'reservation_surname'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_fields_types (reservation_field_name, reservation_field_type)
	SELECT * FROM (SELECT 'reservation_email', 'text') AS tmp
	WHERE NOT EXISTS (
		SELECT reservation_field_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_fields_types WHERE reservation_field_name = 'reservation_email'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_fields_types (reservation_field_name, reservation_field_type)
	SELECT * FROM (SELECT 'reservation_phone', 'text') AS tmp
	WHERE NOT EXISTS (
		SELECT reservation_field_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_fields_types WHERE reservation_field_name = 'reservation_phone'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_fields_types (reservation_field_name, reservation_field_type)
	SELECT * FROM (SELECT 'reservation_message', 'textarea') AS tmp
	WHERE NOT EXISTS (
		SELECT reservation_field_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_fields_types WHERE reservation_field_name = 'reservation_message'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_fields_types (reservation_field_name, reservation_field_type)
	SELECT * FROM (SELECT 'reservation_field1', 'text') AS tmp
	WHERE NOT EXISTS (
		SELECT reservation_field_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_fields_types WHERE reservation_field_name = 'reservation_field1'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_fields_types (reservation_field_name, reservation_field_type)
	SELECT * FROM (SELECT 'reservation_field2', 'text') AS tmp
	WHERE NOT EXISTS (
		SELECT reservation_field_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_fields_types WHERE reservation_field_name = 'reservation_field2'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_fields_types (reservation_field_name, reservation_field_type)
	SELECT * FROM (SELECT 'reservation_field3', 'text') AS tmp
	WHERE NOT EXISTS (
		SELECT reservation_field_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_fields_types WHERE reservation_field_name = 'reservation_field3'
	) LIMIT 1;
	
	INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_fields_types (reservation_field_name, reservation_field_type)
	SELECT * FROM (SELECT 'reservation_field4', 'text') AS tmp
	WHERE NOT EXISTS (
		SELECT reservation_field_name FROM ".$wpdb->base_prefix.$blog_prefix."booking_fields_types WHERE reservation_field_name = 'reservation_field4'
	) LIMIT 1;

	";
	dbDelta($sql18);
}
/*delete plugin*/

function booking_calendar_on_uninstall()
{
	global $wpdb;
	global $blog_id;
	$current_blog = $blog_id;
    if ( ! current_user_can( 'activate_plugins' ) || ! current_user_can( 'delete_plugins' ) )
        return;


    /* Important: Check if the file is the one
     that was registered during the uninstall hook.
    if ( __FILE__ != WP_UNINSTALL_PLUGIN )*/
		
		if (function_exists('is_multisite') && is_multisite()) {
			$blogsQry =  $wpdb->get_results("SELECT * FROM ".$wpdb->base_prefix."blogs");
			for($i=0;$i<count($blogsQry);$i++) {
				$blog_id = $blogsQry[$i]->blog_id;
				$blog_prefix=$blog_id."_";
				if($blog_id==1) {
					$blog_prefix = '';
				}
				
				$sql1="DROP TABLE IF EXISTS ".$wpdb->base_prefix.$blog_prefix."booking_calendars;";
				$sql2="DROP TABLE IF EXISTS ".$wpdb->base_prefix.$blog_prefix."booking_config;";
				$sql3="DROP TABLE IF EXISTS ".$wpdb->base_prefix.$blog_prefix."booking_emails;";
				$sql4="DROP TABLE IF EXISTS ".$wpdb->base_prefix.$blog_prefix."booking_fields_types;";
				$sql5="DROP TABLE IF EXISTS ".$wpdb->base_prefix.$blog_prefix."booking_holidays;";
				$sql6="DROP TABLE IF EXISTS ".$wpdb->base_prefix.$blog_prefix."booking_paypal_currency;";
				$sql7="DROP TABLE IF EXISTS ".$wpdb->base_prefix.$blog_prefix."booking_paypal_locale;";
				$sql8="DROP TABLE IF EXISTS ".$wpdb->base_prefix.$blog_prefix."booking_reservation;";
				$sql9="DROP TABLE IF EXISTS ".$wpdb->base_prefix.$blog_prefix."booking_slots;";
				$sql12="DROP TABLE IF EXISTS ".$wpdb->base_prefix.$blog_prefix."booking_slots_bundles;";
				$sql10="DROP TABLE IF EXISTS ".$wpdb->base_prefix.$blog_prefix."booking_timezones;";
				$sql11="DROP TABLE IF EXISTS ".$wpdb->base_prefix.$blog_prefix."booking_categories;";
				$sql13="DROP TABLE IF EXISTS ".$wpdb->base_prefix.$blog_prefix."booking_pages;";
				$wpdb->query($sql1);
				$wpdb->query($sql2);
				$wpdb->query($sql3);
				$wpdb->query($sql4);
				$wpdb->query($sql5);
				$wpdb->query($sql6);
				$wpdb->query($sql7);
				$wpdb->query($sql8);
				$wpdb->query($sql9);
				$wpdb->query($sql10);
				$wpdb->query($sql11);
				$wpdb->query($sql12);
				$wpdb->query($sql13);
				switch_to_blog($blog_id);
            	delete_option('wbc_version');
				delete_option('wbc_show_text_update_admin');
				delete_option('wbc_show_text_update_public');
				delete_option('wbc_show_text_import_admin');
				delete_option('wbc_name_from_reservation');				
				delete_option('wbc_redirect_booking_path');
				delete_option('wbc_redirect_cancel_path');
				delete_option('wbc_terms_label');
				delete_option('wbc_terms_link');
				delete_option('wbc_form_text');
				delete_option('wbc_registration_text');
				delete_option('wbc_redirect_confirmation_path');
                if(function_exists('icl_unregister_string')) {
                    icl_unregister_string( 'admin_texts_wbc_name_from_reservation', 'wbc_name_from_reservation');
                    icl_unregister_string( 'admin_texts_wbc_redirect_booking_path', 'wbc_redirect_booking_path');
                    icl_unregister_string( 'admin_texts_wbc_redirect_cancel_path', 'wbc_redirect_cancel_path');
                    icl_unregister_string( 'admin_texts_wbc_terms_label', 'wbc_terms_label');
                    icl_unregister_string( 'admin_texts_wbc_terms_link', 'wbc_terms_link');
                    icl_unregister_string( 'admin_texts_wbc_form_text', 'wbc_form_text');
                    icl_unregister_string( 'admin_texts_wbc_registration_text', 'wbc_registration_text');
                    icl_unregister_string( 'admin_texts_wbc_redirect_confirmation_path', 'wbc_redirect_confirmation_path');
                }
				do_action( 'wpml_delete_package_action', 'categories', 'wp-booking-calendar' );
				do_action( 'wpml_delete_package_action', 'calendars', 'wp-booking-calendar' );
				do_action( 'wpml_delete_package_action', 'slots', 'wp-booking-calendar' );
				do_action( 'wpml_delete_package_action', 'mails', 'wp-booking-calendar' );
			}

			switch_to_blog($current_blog);

		} else {
			$sql1="DROP TABLE IF EXISTS ".$wpdb->base_prefix."booking_calendars;";
			$sql2="DROP TABLE IF EXISTS ".$wpdb->base_prefix."booking_config;";
			$sql3="DROP TABLE IF EXISTS ".$wpdb->base_prefix."booking_emails;";
			$sql4="DROP TABLE IF EXISTS ".$wpdb->base_prefix."booking_fields_types;";
			$sql5="DROP TABLE IF EXISTS ".$wpdb->base_prefix."booking_holidays;";
			$sql6="DROP TABLE IF EXISTS ".$wpdb->base_prefix."booking_paypal_currency;";
			$sql7="DROP TABLE IF EXISTS ".$wpdb->base_prefix."booking_paypal_locale;";
			$sql8="DROP TABLE IF EXISTS ".$wpdb->base_prefix."booking_reservation;";
			$sql9="DROP TABLE IF EXISTS ".$wpdb->base_prefix."booking_slots;";
			$sql12="DROP TABLE IF EXISTS ".$wpdb->base_prefix."booking_slots_bundles;";
			$sql10="DROP TABLE IF EXISTS ".$wpdb->base_prefix."booking_timezones;";
			$sql11="DROP TABLE IF EXISTS ".$wpdb->base_prefix."booking_categories;";
			$sql13="DROP TABLE IF EXISTS ".$wpdb->base_prefix."booking_pages;";
			$wpdb->query($sql1);
			$wpdb->query($sql2);
			$wpdb->query($sql3);
			$wpdb->query($sql4);
			$wpdb->query($sql5);
			$wpdb->query($sql6);
			$wpdb->query($sql7);
			$wpdb->query($sql8);
			$wpdb->query($sql9);
			$wpdb->query($sql10);
			$wpdb->query($sql11);
			$wpdb->query($sql12);
			$wpdb->query($sql13);
			delete_option('wbc_version');
			delete_option('wbc_show_text_update_admin');
			delete_option('wbc_show_text_update_public');
			delete_option('wbc_show_text_import_admin');
			delete_option('wbc_name_from_reservation');
			delete_option('wbc_redirect_booking_path');
			delete_option('wbc_redirect_cancel_path');
			delete_option('wbc_terms_label');
			delete_option('wbc_terms_link');
			delete_option('wbc_form_text');
			delete_option('wbc_registration_text');
			delete_option('wbc_redirect_confirmation_path');
			if(function_exists('icl_unregister_string')) {
			  icl_unregister_string( 'admin_texts_wbc_name_from_reservation', 'wbc_name_from_reservation');
			  icl_unregister_string( 'admin_texts_wbc_redirect_booking_path', 'wbc_redirect_booking_path');
			  icl_unregister_string( 'admin_texts_wbc_redirect_cancel_path', 'wbc_redirect_cancel_path');
			  icl_unregister_string( 'admin_texts_wbc_terms_label', 'wbc_terms_label');
			  icl_unregister_string( 'admin_texts_wbc_terms_link', 'wbc_terms_link');
			  icl_unregister_string( 'admin_texts_wbc_form_text', 'wbc_form_text');
			  icl_unregister_string( 'admin_texts_wbc_registration_text', 'wbc_registration_text');
			  icl_unregister_string( 'admin_texts_wbc_redirect_confirmation_path', 'wbc_redirect_confirmation_path');
			}
			do_action( 'wpml_delete_package_action', 'categories', 'wp-booking-calendar' );
			do_action( 'wpml_delete_package_action', 'calendars', 'wp-booking-calendar' );
			do_action( 'wpml_delete_package_action', 'slots', 'wp-booking-calendar' );
			do_action( 'wpml_delete_package_action', 'mails', 'wp-booking-calendar' );
		}
		delete_option('wbc_version');
		delete_option('wbc_show_text_update_admin');
		delete_option('wbc_show_text_update_public');
		delete_option('wbc_show_text_import_admin');
		delete_option('wbc_name_from_reservation');
		delete_option('wbc_redirect_booking_path');
		delete_option('wbc_redirect_cancel_path');
		delete_option('wbc_terms_label');
		delete_option('wbc_terms_link');
		delete_option('wbc_form_text');
		delete_option('wbc_registration_text');
		delete_option('wbc_redirect_confirmation_path');
		if(function_exists('icl_unregister_string')) {
		  icl_unregister_string( 'admin_texts_wbc_name_from_reservation', 'wbc_name_from_reservation');
		  icl_unregister_string( 'admin_texts_wbc_redirect_booking_path', 'wbc_redirect_booking_path');
		  icl_unregister_string( 'admin_texts_wbc_redirect_cancel_path', 'wbc_redirect_cancel_path');
		  icl_unregister_string( 'admin_texts_wbc_terms_label', 'wbc_terms_label');
		  icl_unregister_string( 'admin_texts_wbc_terms_link', 'wbc_terms_link');
		  icl_unregister_string( 'admin_texts_wbc_form_text', 'wbc_form_text');
		  icl_unregister_string( 'admin_texts_wbc_registration_text', 'wbc_registration_text');
		  icl_unregister_string( 'admin_texts_wbc_redirect_confirmation_path', 'wbc_redirect_confirmation_path');
		}
		do_action( 'wpml_delete_package_action', 'categories', 'wp-booking-calendar' );
		do_action( 'wpml_delete_package_action', 'calendars', 'wp-booking-calendar' );
		do_action( 'wpml_delete_package_action', 'slots', 'wp-booking-calendar' );
		do_action( 'wpml_delete_package_action', 'mails', 'wp-booking-calendar' );
        return;


    # Uncomment the following line to see the function in action
    # exit( var_dump( $_GET ) );
}



function booking_calendar_on_activation($networkwide) {
	global $wpdb;
	global $blog_id;
	$current_blog = $blog_id;




	/*cycle on blogs table*/
	if (function_exists('is_multisite') && is_multisite()) {

		if($networkwide) {
			$blogsQry =  $wpdb->get_results("SELECT * FROM ".$wpdb->base_prefix."blogs");
		} else {
			$blogsQry =  $wpdb->get_results("SELECT * FROM ".$wpdb->base_prefix."blogs WHERE blog_id=".$blog_id);
		}
		for($i=0;$i<count($blogsQry);$i++) {
			$blog_id=$blogsQry[$i]->blog_id;
			$blog_prefix = $blogsQry[$i]->blog_id."_";
			if($blog_id==1) {
				$blog_prefix='';
			}
			switch_to_blog($blog_id);
			$current_version = get_option('wbc_version');
			if($current_version == '') {
				$current_version = "0.0.0";
			}
			$flag = 0;
			if($current_version != '0.0.0' && $current_version != '3.0.0' && $current_version != '3.0.1' && $current_version != '3.0.2' && $current_version != '3.0.3' && $current_version != '3.0.4' && $current_version != '3.0.5' && $current_version != '3.0.6' && $current_version != '3.0.7' && $current_version != '4.0.0' && $current_version != '4.0.1' && $current_version != '4.0.2' && $current_version != '4.0.3' && $current_version != '4.0.4' && $current_version != '4.0.5' && $current_version != '4.0.6' && $current_version != '4.0.7' && $current_version != '4.0.8' && $current_version != '4.0.9' && $current_version != '4.1.0' && $current_version != '4.1.1' && $current_version != '4.1.2' && $current_version != '4.1.3' && $current_version != '4.1.4' && $current_version != '4.1.5' && $current_version != '4.1.6' && $current_version != '4.1.7' && $current_version != '4.1.8' && $current_version != '5.0.0' && $current_version != '5.0.1' && $current_version != '5.0.2' && $current_version != '5.0.3' && $current_version != '6.0.0' && $current_version != '6.0.1' && $current_version != '6.0.2' && $current_version != '6.0.3' && $current_version != '6.0.4' && $current_version != '6.0.5' && $current_version != '6.0.6' && $current_version != '6.0.7') {
			   $flag = 1;
			}


			/*creates record in wp-option table*/
            if($current_version == '0.0.0') {
                add_option('wbc_version','6.0.8');
            } else {
                update_option('wbc_version','6.0.8');
            }

			$wbc_show_text_update_admin = get_option('wbc_show_text_update_admin');
			if($wbc_show_text_update_admin == '') {
				add_option('wbc_show_text_update_admin',$flag);
			} else {
				update_option('wbc_show_text_update_admin',$flag);
			}

			$wbc_show_text_update_public = get_option('wbc_show_text_update_public');
			if($wbc_show_text_update_public == '') {
				add_option('wbc_show_text_update_public',$flag);
			} else {
				update_option('wbc_show_text_update_public',$flag);
			}

			$flag = 0;
			if($current_version != '0.0.0' && $current_version != '5.0.0' && $current_version != '5.0.1' && $current_version != '5.0.2' && $current_version != '5.0.3' && $current_version != '6.0.0' && $current_version != '6.0.1' && $current_version != '6.0.2' && $current_version != '6.0.3' && $current_version != '6.0.4') {
			   $flag = 1;
			}
			$wbc_show_text_import_admin = get_option('wbc_show_text_import_admin');
			if($wbc_show_text_import_admin == '') {
				add_option('wbc_show_text_import_admin',$flag);
			} else {
				update_option('wbc_show_text_import_admin',$flag);
			}
			booking_calendar_install_db($blog_prefix);

			add_option('wbc_name_from_reservation','Booking Calendar');
			add_option('wbc_redirect_booking_path','');
			add_option('wbc_redirect_cancel_path','');
			add_option('wbc_terms_label','');
			add_option('wbc_terms_link','');
			add_option('wbc_form_text','');
			add_option('wbc_registration_text','');
			add_option('wbc_redirect_confirmation_path','');
		}

		if($flag == 1) {
			wp_booking_calendar_update_languages();
		}


		switch_to_blog($current_blog);

	} else {
		$blog_prefix='';
		/*creates record in wp-option table*/
		$flag = 0;
		$current_version = get_option('wbc_version');
		if($current_version == '') {
			$current_version = "0.0.0";
		}
		if($current_version != '0.0.0' && $current_version != '3.0.0' && $current_version != '3.0.1' && $current_version != '3.0.2' && $current_version != '3.0.3' && $current_version != '3.0.4' && $current_version != '3.0.5' && $current_version != '3.0.6' && $current_version != '3.0.7' && $current_version != '4.0.1' && $current_version != '4.0.2' && $current_version != '4.0.3' && $current_version != '4.0.4' && $current_version != '4.0.5' && $current_version != '4.0.6' && $current_version != '4.0.7' && $current_version != '4.0.8' && $current_version != '4.0.9' && $current_version != '4.1.0' && $current_version != '4.1.1' && $current_version != '4.1.2' && $current_version != '4.1.3' && $current_version != '4.1.4' && $current_version != '4.1.5' && $current_version != '4.1.6' && $current_version != '4.1.7' && $current_version != '4.1.8' && $current_version != '5.0.0' && $current_version != '5.0.1' && $current_version != '5.0.2' && $current_version != '5.0.3' && $current_version != '6.0.0' && $current_version != '6.0.1' && $current_version != '6.0.2' && $current_version != '6.0.3' && $current_version != '6.0.4' && $current_version != '6.0.5' && $current_version != '6.0.6' && $current_version != '6.0.7') {
		   $flag = 1;
		}
        if($current_version == '0.0.0') {
            add_option('wbc_version','6.0.8');
        } else {
            update_option('wbc_version','6.0.8');
        }
		$wbc_show_text_update_admin = get_option('wbc_show_text_update_admin');
		if($wbc_show_text_update_admin == '') {
			add_option('wbc_show_text_update_admin',$flag);
		} else {
			update_option('wbc_show_text_update_admin',$flag);
		}

		$wbc_show_text_update_public = get_option('wbc_show_text_update_public');
		if($wbc_show_text_update_public == '') {
			add_option('wbc_show_text_update_public',$flag);
		} else {
			update_option('wbc_show_text_update_public',$flag);
		}

		$flag = 0;
		if($current_version != '0.0.0' && $current_version != '5.0.0' && $current_version != '5.0.1' && $current_version != '5.0.2' && $current_version != '5.0.3' && $current_version != '6.0.0' && $current_version != '6.0.1' && $current_version != '6.0.2' && $current_version != '6.0.3' && $current_version != '6.0.4' && $current_version != '6.0.5' && $current_version != '6.0.6' && $current_version != '6.0.7') {
		   $flag = 1;
		}
		$wbc_show_text_import_admin = get_option('wbc_show_text_import_admin');
		if($wbc_show_text_import_admin == '') {
			add_option('wbc_show_text_import_admin',$flag);
		} else {
			update_option('wbc_show_text_import_admin',$flag);
		}
		booking_calendar_install_db($blog_prefix);

		if($flag == 1) {
			wp_booking_calendar_update_languages();
		}

        add_option('wbc_name_from_reservation','Booking Calendar');
		add_option('wbc_redirect_booking_path', '');
		add_option('wbc_redirect_cancel_path', '');
		add_option('wbc_terms_label','');
		add_option('wbc_terms_link','');
		add_option('wbc_form_text','');
		add_option('wbc_registration_text','');
		add_option('wbc_redirect_confirmation_path','');
	}
    /*copy languages file into "languages" folder*/
    $domain = 'wp-booking-calendar';
    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
    if(!file_exists(trailingslashit( WP_LANG_DIR ) . 'plugins/' . $domain . '-' . $locale . '.mo')) {
        if(!is_dir(trailingslashit( WP_LANG_DIR ))) {
            mkdir(trailingslashit( WP_LANG_DIR ));
        }
        if(!is_dir(trailingslashit( WP_LANG_DIR ) . 'plugins/')) {
            mkdir(trailingslashit( WP_LANG_DIR ) . 'plugins/');
        }
        if(file_exists(dirname( __FILE__ ). '/lang/'. $domain . '-' . $locale . '.mo')) {
            copy(dirname( __FILE__ ). '/lang/'. $domain . '-' . $locale . '.mo',trailingslashit( WP_LANG_DIR ) . 'plugins/' . $domain . '-' . $locale . '.mo');
            copy(dirname( __FILE__ ). '/lang/'. $domain . '-' . $locale . '.po',trailingslashit( WP_LANG_DIR ) . 'plugins/' . $domain . '-' . $locale . '.po');
        } else {
            copy(dirname( __FILE__ ). '/lang/'. $domain . '-en_US.mo',trailingslashit( WP_LANG_DIR ) . 'plugins/' . $domain . '-' . $locale . '.mo');
            copy(dirname( __FILE__ ). '/lang/'. $domain . '-en_US.po',trailingslashit( WP_LANG_DIR ) . 'plugins/' . $domain . '-' . $locale . '.po');
        }

    }


}
add_action('plugins_loaded', 'wp_booking_calendar_process_update',1);

register_uninstall_hook(BOOKING_CALENDAR_PLUGIN_PATH.'wp-booking-calendar.php', 'booking_calendar_on_uninstall' );
register_activation_hook(BOOKING_CALENDAR_PLUGIN_PATH.'wp-booking-calendar.php', 'booking_calendar_on_activation' );

function wp_booking_calendar_process_update(){

	global $wpdb;


	$current_version = get_option('wbc_version');
	if($current_version == '') {
		$current_version = "0.0.0";
	}
	switch($current_version) {
		case '0.0.0':
			/*fresh install*/
			update_option('wbc_show_text_update_admin','0');
			update_option('wbc_show_text_update_public','0');
			update_option('wbc_show_text_import_admin','0');
			wp_booking_multisite_update();

			update_option('wbc_version','6.0.8');

			break;
		case '1.0.0':
			update_option('wbc_show_text_update_admin','1');
			update_option('wbc_show_text_update_public','1');
			wp_booking_multisite_update();

			update_option('wbc_version','6.0.8');

			break;
		case '2.0.0':
			update_option('wbc_show_text_update_admin','1');
			update_option('wbc_show_text_update_public','1');
			wp_booking_multisite_update();
			update_option('wbc_version','6.0.8');

			break;
		case '2.0.1':
			update_option('wbc_show_text_update_admin','1');
			update_option('wbc_show_text_update_public','1');
			wp_booking_multisite_update();
			update_option('wbc_version','6.0.8');

			break;
		case '2.0.2':
			update_option('wbc_show_text_update_admin','1');
			update_option('wbc_show_text_update_public','1');
			wp_booking_multisite_update();
			update_option('wbc_version','6.0.8');

			break;
		case '2.0.3':
			update_option('wbc_show_text_update_admin','1');
			update_option('wbc_show_text_update_public','1');
			wp_booking_multisite_update();
			update_option('wbc_version','6.0.8');

			break;
		case '2.0.4':
			update_option('wbc_show_text_update_admin','1');
			update_option('wbc_show_text_update_public','1');
			wp_booking_multisite_update();
			update_option('wbc_version','6.0.8');

			break;
		case '2.0.5':
			update_option('wbc_show_text_update_admin','1');
			update_option('wbc_show_text_update_public','1');
			wp_booking_multisite_update();
			update_option('wbc_version','6.0.8');

			break;
		case '2.0.6':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '2.0.7':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '3.0.0':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();

			break;
		case '3.0.1':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '3.0.2':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '3.0.3':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '3.0.4':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '3.0.5':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '3.0.6':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '3.0.7':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.0.0':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.0.1':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.0.2':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.0.3':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.0.4':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.0.5':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.0.6':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.0.7':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.0.8':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.0.9':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.1.0':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.1.1':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.1.2':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.1.3':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.1.4':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.1.5':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.1.6':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.1.7':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
		case '4.1.8':
			update_option('wbc_show_text_import_admin','1');
			update_option('wbc_version','6.0.8');
			wp_booking_multisite_update();
			break;
        case '5.0.0':
            update_option('wbc_show_text_import_admin','1');
            update_option('wbc_version','6.0.8');
            wp_booking_multisite_update();
            break;
        case '5.0.1':
            update_option('wbc_show_text_import_admin','1');
            update_option('wbc_version','6.0.8');
            wp_booking_multisite_update();
            break;
        case '5.0.2':
            update_option('wbc_show_text_import_admin','1');
            update_option('wbc_version','6.0.8');
            wp_booking_multisite_update();
            break;
        case '5.0.3':
            update_option('wbc_show_text_import_admin','1');
            update_option('wbc_version','6.0.8');
            wp_booking_multisite_update();
            break;
        case '6.0.0':
            update_option('wbc_show_text_import_admin','1');
            update_option('wbc_version','6.0.8');
            wp_booking_multisite_update();
            break;
        case '6.0.1':
            update_option('wbc_show_text_import_admin','1');
            update_option('wbc_version','6.0.8');
            wp_booking_multisite_update();
            break;
        case '6.0.2':
            update_option('wbc_show_text_import_admin','1');
            update_option('wbc_version','6.0.8');
            wp_booking_multisite_update();
            break;
        case '6.0.3':
            update_option('wbc_show_text_import_admin','1');
            update_option('wbc_version','6.0.8');
            wp_booking_multisite_update();
            break;
        case '6.0.4':
            update_option('wbc_show_text_import_admin','1');
            update_option('wbc_version','6.0.8');
            wp_booking_multisite_update();
            break;
        case '6.0.5':
            update_option('wbc_show_text_import_admin','1');
            update_option('wbc_version','6.0.8');
            wp_booking_multisite_update();
            break;
        case '6.0.6':
            update_option('wbc_show_text_import_admin','1');
            update_option('wbc_version','6.0.8');
            wp_booking_multisite_update();
            break;
        case '6.0.7':
            update_option('wbc_show_text_import_admin','1');
            update_option('wbc_version','6.0.8');
            wp_booking_multisite_update();
            break;





    }

	
	
	
}

function wp_booking_calendar_update_languages() {
    global $wpdb;
	$arrLang = Array();
	$arrLang['WELCOME_TEXT1'] = 'WELCOME TO BOOKING CALENDAR CONTROL PANEL';
	$arrLang['WELCOME_TEXT2'] = 'Use the menu on the left to manage all configurations and contents';
	$arrLang['WELCOME_TEXT3'] = 'Hey Admin,';
	$arrLang['WELCOME_TEXT4'] = 'it seems like you did not adjust the settings and Â created a calendar yet.';
	$arrLang['WELCOME_TEXT5'] = 'Remember,';
	$arrLang['WELCOME_TEXT6'] = 'if you skip these two steps, the Booking Calendar cannot work.';
	$arrLang['WELCOME_TEXT7'] = 'Let\'s go to start now!';
	$arrLang['CONFIGURATION_TIMEZONE_LABEL'] = 'Timezone';
	$arrLang['CONFIGURATION_TIMEZONE_SUBLABEL'] = 'Your timezone to manage the time slots';
	$arrLang['CONFIGURATION_TIMEZONE_ALERT'] = 'Select your timezone';
	$arrLang['CONFIGURATION_TIMEZONE_SELECT'] = 'Select your timezone';
	$arrLang['CONFIGURATION_DATE_FORMAT_LABEL'] = 'Choose calendar date format.';
	$arrLang['CONFIGURATION_DATE_FORMAT_SUBLABEL'] = 'Switch between US, UK and EU date formats';
	$arrLang['CONFIGURATION_DATE_FORMAT_UK'] = 'UK - dd/mm/yyyy - week starts on Monday';
	$arrLang['CONFIGURATION_DATE_FORMAT_US'] = 'US - mm/dd/yyyy - week starts on Sunday';
	$arrLang['CONFIGURATION_DATE_FORMAT_EU'] = 'EU - yyyy/mm/dd - week starts on Monday';
	$arrLang['CONFIGURATION_TIME_FORMAT_LABEL'] = 'Choose calendar time format.';
	$arrLang['CONFIGURATION_TIME_FORMAT_SUBLABEL'] = 'Switch between 12-hour and 24-hour time formats';
	$arrLang['CONFIGURATION_TIME_FORMAT_12'] = '12-hour time format with am/pm';
	$arrLang['CONFIGURATION_TIME_FORMAT_24'] = '24-hour time format';
	$arrLang['CONFIGURATION_EMAIL_RESERVATION_LABEL'] = 'Admin Email';
	$arrLang['CONFIGURATION_EMAIL_RESERVATION_SUBLABEL'] = 'E-mail address where you\'ll receive reservation alerts';
	$arrLang['CONFIGURATION_EMAIL_RESERVATION_ALERT'] = 'Specify your email address';
	$arrLang['CONFIGURATION_EMAIL_FROM_RESERVATION_LABEL'] = 'Email \"from\"';
	$arrLang['CONFIGURATION_EMAIL_FROM_RESERVATION_SUBLABEL'] = 'Name and e-mail address shown in the field \"From\" in every e-mail sent to confirm the reservation to your customer';
	$arrLang['CONFIGURATION_NAME_FROM_RESERVATION_SIDE_LABEL'] = 'Sender name';
	$arrLang['CONFIGURATION_EMAIL_FROM_RESERVATION_SIDE_LABEL'] = 'E-mail address';
	$arrLang['CONFIGURATION_EMAIL_FROM_RESERVATION_ALERT'] = 'Specify an email address \'from\'';
	$arrLang['CONFIGURATION_RESERVATION_CONFIRMATION_MODE_LABEL'] = 'Reservation: confirmation mode';
	$arrLang['CONFIGURATION_RESERVATION_CONFIRMATION_MODE_SUBLABEL'] = 'Choose how to confirm reservations';
	$arrLang['CONFIGURATION_RESERVATION_CONFIRMATION_MODE_ALERT'] = 'Select reservation confirm mode';
	$arrLang['CONFIGURATION_RESERVATION_CONFIRMATION_MODE_SELECT'] = 'Select reservation confirmation mode';
	$arrLang['CONFIGURATION_RESERVATION_CONFIRMATION_MODE_1'] = 'Automatically - When a user book a time slot, it is automatically confirmed';
	$arrLang['CONFIGURATION_RESERVATION_CONFIRMATION_MODE_2'] = 'By a verification e-mail - When a user book a time slot, he has to confirm the reservation by clicking on a link sent to him by e-mail';
	$arrLang['CONFIGURATION_RESERVATION_CONFIRMATION_MODE_3'] = 'Admin confirm - You must confirm the reservation in the reservations area';
	$arrLang['CONFIGURATION_REDIRECT_CONFIRMATION_PATH_LABEL'] = 'Currently in the confirmation page, the user will be pointed to the calendar. If you want to modify the destination page click here:';
	$arrLang['CONFIGURATION_REDIRECT_CONFIRMATION_PATH_SUBLABEL'] = 'Set the url (starting with: http://):';
	$arrLang['CONFIGURATION_REDIRECT_BOOKING_PATH_LABEL'] = 'Redirect page after booking';
	$arrLang['CONFIGURATION_REDIRECT_BOOKING_PATH_SUBLABEL'] = '(starting with: http://)';
	$arrLang['CONFIGURATION_RESERVATION_CANCEL_LABEL'] = 'Reservation: cancellation';
	$arrLang['CONFIGURATION_RESERVATION_CANCEL_SUBLABEL'] = 'Choose if the customer will be able to cancel his reservation by a link in the confirmation email he receives (when you decide to activate this function you can check the email to change the text)';
	$arrLang['CONFIGURATION_RESERVATION_CANCEL_ENABLED'] = 'enabled';
	$arrLang['CONFIGURATION_REDIRECT_CANCEL_PATH_LABEL'] = 'Currently in the cancellation page, the user will be pointed to the calendar. If you want to modify the destination page click here:';
	$arrLang['CONFIGURATION_REDIRECT_CANCEL_PATH_SUBLABEL'] = 'Set the url (starting with: http://):';
	$arrLang['CONFIGURATION_RECAPTCHA_ENABLED_LABEL'] = 'Google recaptcha';
	$arrLang['CONFIGURATION_RECAPTCHA_ENABLED_SUBLABEL'] = 'Code verification to avoid spam';
	$arrLang['CONFIGURATION_RECAPTCHA_ENABLED_ENABLED'] = 'enabled';
	$arrLang['CONFIGURATION_RECAPTCHA_PUBLIC_KEY_LABEL'] = 'Public Key';
	$arrLang['CONFIGURATION_RECAPTCHA_PUBLIC_KEY_SUBLABEL'] = 'It must be associated with your site domain, go to Recaptcha site to get it:';
	$arrLang['CONFIGURATION_RECAPTCHA_PUBLIC_KEY_ALERT'] = 'Insert Google recaptcha public key';
	$arrLang['CONFIGURATION_RECAPTCHA_PRIVATE_KEY_LABEL'] = 'Private key';
	$arrLang['CONFIGURATION_RECAPTCHA_PRIVATE_KEY_SUBLABEL'] = 'It must be associated with your site domain, go to Recaptcha site to get it:';
	$arrLang['CONFIGURATION_RECAPTCHA_PRIVATE_KEY_ALERT'] = 'Insert Google recaptcha private key';
	$arrLang['CONFIGURATION_SHOW_TERMS_LABEL'] = 'Add terms and condition check';
	$arrLang['CONFIGURATION_SHOW_TERMS_SUBLABEL'] = 'Adding this control, the user must check it to be able to book. It\'s mandatory to insert at least a label to enable this option';
	$arrLang['CONFIGURATION_SHOW_TERMS_YES'] = 'YES';
	$arrLang['CONFIGURATION_SHOW_TERMS_NO'] = 'NO';
	$arrLang['CONFIGURATION_TERMS_LABEL_LABEL'] = 'Label to show:';
	$arrLang['CONFIGURATION_TERMS_LINK_LABEL'] = 'Link to terms and conditions (starting with \'http://\'):';
	$arrLang['CONFIGURATION_SLOT_SELECTION_LABEL'] = 'Slot selection';
	$arrLang['CONFIGURATION_SLOT_SELECTION_SUBLABEL'] = '(choose if customer can reserve only one or multiple slots at once)';
	$arrLang['CONFIGURATION_SLOT_SELECTION_MULTIPLE'] = 'Multiple selection';
	$arrLang['CONFIGURATION_SLOT_SELECTION_ONE'] = 'Only one';
	$arrLang['CONFIGURATION_SLOTS_UNLIMITED_LABEL'] = 'Unlimited reservations';
	$arrLang['CONFIGURATION_SLOTS_UNLIMITED_SUBLABEL'] = 'Choose if slots can have unlimited reservations or just one.';
	$arrLang['CONFIGURATION_SLOTS_UNLIMITED_ONE'] = 'one reservation per slot';
	$arrLang['CONFIGURATION_SLOTS_UNLIMITED_UNLIMITED'] = 'unlimited reservations per slot';
	$arrLang['CONFIGURATION_SLOTS_UNLIMITED_CUSTOM'] = 'use the number set in slot insertion';
	$arrLang['CONFIGURATION_SHOW_BOOKED_SLOTS_LABEL'] = 'Show booked slots';
	$arrLang['CONFIGURATION_SHOW_BOOKED_SLOTS_SUBLABEL'] = 'Choose whether to show or not the booked slots. This works only if reservations per slot are not unlimited';
	$arrLang['CONFIGURATION_SHOW_BOOKED_SLOTS_YES'] = 'YES';
	$arrLang['CONFIGURATION_SHOW_BOOKED_SLOTS_NO'] = 'NO';
	$arrLang['CONFIGURATION_SLOTS_POPUP_ENABLED_LABEL'] = 'Available time slots preview';
	$arrLang['CONFIGURATION_SLOTS_POPUP_ENABLED_SUBLABEL'] = 'Show the preview of available time slots on calendar days rollover';
	$arrLang['CONFIGURATION_SLOTS_POPUP_ENABLED_ENABLED'] = 'enabled';
	$arrLang['CONFIGURATION_SLOTS_POPUP_ENABLED_DISABLED'] = 'disabled';
	$arrLang['CONFIGURATION_SHOW_CATEGORY_SELECTION_LABEL'] = 'Show category selection';
	$arrLang['CONFIGURATION_SHOW_CATEGORY_SELECTION_SUBLABEL'] = 'Choose whether to show or not the category selection drop down at the top right of the calendar when general shortcode [wp_booking_calendar] is used';
	$arrLang['CONFIGURATION_SHOW_CATEGORY_SELECTION_YES'] = 'YES';
	$arrLang['CONFIGURATION_SHOW_CATEGORY_SELECTION_NO'] = 'NO';
	$arrLang['CONFIGURATION_SHOW_CALENDAR_SELECTION_LABEL'] = 'Show calendar selection';
	$arrLang['CONFIGURATION_SHOW_CALENDAR_SELECTION_SUBLABEL'] = 'Choose whether to show or not the calendar selection drop down at the top right of the calendar when general or category shortcode id used';
	$arrLang['CONFIGURATION_SHOW_CALENDAR_SELECTION_YES'] = 'YES';
	$arrLang['CONFIGURATION_SHOW_CALENDAR_SELECTION_NO'] = 'NO';
	$arrLang['CONFIGURATION_CALENDAR_MONTH_LIMIT_LABEL'] = 'Calendar months view';
	$arrLang['CONFIGURATION_CALENDAR_MONTH_LIMIT_SUBLABEL'] = 'Choose if you want to limit the number of past and future months shown in the calendar. Leave -1 if you don\'t want to set this limit';
	$arrLang['CONFIGURATION_CALENDAR_MONTH_LIMIT_PAST'] = 'Past months:';
	$arrLang['CONFIGURATION_CALENDAR_MONTH_LIMIT_FUTURE'] = 'Future months:';
	$arrLang['CONFIGURATION_BOOK_FROM_LABEL'] = 'Choose when users are able to book a slot';
	$arrLang['CONFIGURATION_BOOK_FROM_SUBLABEL'] = 'Insert the minimum number of days that a user has to book before the slot date in order to get admitted. Leave 0 if he can book even at the last minute.';
	$arrLang['CONFIGURATION_BOOK_TO_SUBLABEL'] = 'Insert the maximum number of days that a user can book when landing on the calendar. Leave 0 if he can book at any date.';
	$arrLang['CONFIGURATION_PAYPAL_LABEL'] = 'Enable Paypal payment';
	$arrLang['CONFIGURATION_PAYPAL_SUBLABEL1'] = 'Activate this option if you want people to pay the booking fee with Paypal. You must complete all the fields below to activate this service.';
	$arrLang['CONFIGURATION_PAYPAL_SUBLABEL2'] = 'Note that if you activate IPN';
	$arrLang['CONFIGURATION_PAYPAL_SUBLABEL3'] = 'in your Paypal profile the system will automatically confirm reservations after payments even if the user closes the browser before being redirected to the Booking Calendar. In the Notification URL text box just put your WP site address.';
	$arrLang['CONFIGURATION_PAYPAL_YES'] = 'YES';
	$arrLang['CONFIGURATION_PAYPAL_NO'] = 'NO';
	$arrLang['CONFIGURATION_PAYPAL_ACCOUNT'] = 'Insert your paypal email address';
	$arrLang['CONFIGURATION_PAYPAL_CURRENCY'] = 'Select your currency';
	$arrLang['CONFIGURATION_PAYPAL_LOCALE'] = 'Select your country';
	$arrLang['CONFIGURATION_PAYPAL_LOCALE_CHOOSE'] = 'choose';
	$arrLang['CONFIGURATION_PAYPAL_CURRENCY_CHOOSE'] = 'choose';
	$arrLang['CONFIGURATION_PAYPAL_DISPLAY_PRICE'] = 'Display prices in booking page';
	$arrLang['CONFIGURATION_FORM_TEXT_LABEL'] = 'Additional text for booking page';
	$arrLang['CONFIGURATION_FORM_TEXT_SUBLABEL'] = 'It will be displayed under the date';
	$arrLang['CONFIGURATION_CANCEL'] = 'CANCEL';
	$arrLang['CONFIGURATION_SAVE'] = 'SAVE';
	$arrLang['CONFIGURATION_SHOW_SLOTS_SEATS_LABEL'] = 'Show available slots seats instead of available slots number';
	$arrLang['CONFIGURATION_SHOW_SLOTS_SEATS_YES'] = 'YES';
	$arrLang['CONFIGURATION_SHOW_SLOTS_SEATS_NO'] = 'NO';
	$arrLang['CONFIGURATION_SHOW_FIRST_FILLED_MONTH_LABEL'] = 'Show the first not empty month by default';
	$arrLang['CONFIGURATION_SHOW_FIRST_FILLED_MONTH_SUBLABEL'] = 'Choose whether to start the calendar from the first month which have slots. If Set to \"NO\" the first visible month will be the current month.';
	$arrLang['CONFIGURATION_SHOW_FIRST_FILLED_MONTH_YES'] = 'YES';
	$arrLang['CONFIGURATION_SHOW_FIRST_FILLED_MONTH_NO'] = 'NO';
	$arrLang['STYLES_EMPTY_CELLS_TITLE'] = 'Calendar empty cells';
	$arrLang['STYLES_EMPTY_CELLS_BACKGROUND'] = 'Empty cell background (no day):';
	$arrLang['STYLES_AVAILABLE_CELLS_TITLE'] = 'Calendar available cells';
	$arrLang['STYLES_AVAILABLE_CELLS_BACKGROUND'] = 'Available day background:';
	$arrLang['STYLES_AVAILABLE_CELLS_BACKGROUND_OVER'] = 'Available day background on mouse over:';
	$arrLang['STYLES_AVAILABLE_CELLS_LINE_1_COLOR'] = 'Available day first line label color:';
	$arrLang['STYLES_AVAILABLE_CELLS_LINE_1_COLOR_OVER'] = 'Available day first line label color on mouse over:';
	$arrLang['STYLES_AVAILABLE_CELLS_LINE_2_COLOR'] = 'Available day second line label color:';
	$arrLang['STYLES_AVAILABLE_CELLS_LINE_2_COLOR_OVER'] = 'Available day second line label color on mouse over:';
	$arrLang['STYLES_TODAY_CELLS_TITLE'] = 'Calendar today cell';
	$arrLang['STYLES_TODAY_CELLS_BACKGROUND'] = 'Today\'s background:';
	$arrLang['STYLES_TODAY_CELLS_BACKGROUND_OVER'] = 'Today\'s background on mouse over (with available slots):';
	$arrLang['STYLES_TODAY_CELLS_LINE_1_COLOR'] = 'Today\'s first line color:';
	$arrLang['STYLES_TODAY_CELLS_LINE_1_COLOR_OVER'] = 'Today\'s first line color on mouse over:';
	$arrLang['STYLES_TODAY_CELLS_LINE_2_COLOR'] = 'Today\'s second line color:';
	$arrLang['STYLES_TODAY_CELLS_LINE_2_COLOR_OVER'] = 'Today\'s second line color on mouse over:';
	$arrLang['STYLES_SOLDOUT_CELLS_TITLE'] = 'Calendar sold out cells';
	$arrLang['STYLES_SOLDOUT_CELLS_BACKGROUND'] = 'Sold out background:';
	$arrLang['STYLES_SOLDOUT_CELLS_LINE_1_COLOR'] = 'Sold out day first line label color:';
	$arrLang['STYLES_SOLDOUT_CELLS_LINE_2_COLOR'] = 'Sold out day second line label color:';
	$arrLang['STYLES_NOTAVAILABLE_CELLS_TITLE'] = 'Calendar not available cells';
	$arrLang['STYLES_NOTAVAILABLE_CELLS_BACKGROUND'] = 'Not available day background:';
	$arrLang['STYLES_NOTAVAILABLE_CELLS_LINE_1_COLOR'] = 'Not available day first line label color:';
	$arrLang['STYLES_NOTAVAILABLE_CELLS_LINE_2_COLOR'] = 'Not available day second line label color:';
	$arrLang['STYLES_FORM_TITLE'] = 'Booking form style';
	$arrLang['STYLES_FORM_BACKGROUND'] = 'Booking form background:';
	$arrLang['STYLES_FORM_LABELS_COLOR'] = 'Booking form labels color:';
	$arrLang['STYLES_FORM_RECAPTCHA'] = 'Recaptcha style:';
	$arrLang['STYLES_FORM_RECAPTCHA_WHITE'] = 'white';
	$arrLang['STYLES_FORM_RECAPTCHA_RED'] = 'red';
	$arrLang['STYLES_FORM_RECAPTCHA_BLACK'] = 'black';
	$arrLang['STYLES_MONTH_CONTAINER_TITLE'] = 'Month box style';
	$arrLang['STYLES_MONTH_CONTAINER_BACKGROUND'] = 'Month box background:';
	$arrLang['STYLES_MONTH_NAME_COLOR'] = 'Month name label color:';
	$arrLang['STYLES_YEAR_NAME_COLOR'] = 'Year label color:';
	$arrLang['STYLES_DAY_NAMES_TITLE'] = 'Weekdays style';
	$arrLang['STYLES_DAY_NAMES_COLOR'] = 'Weekdays label color:';
	$arrLang['STYLES_FORM_FIELD_INPUT_BACKGROUND'] = 'Fields background:';
	$arrLang['STYLES_FORM_FIELD_INPUT_COLOR'] = 'Fields text color:';
	$arrLang['STYLES_MONTH_NAVIGATION_BUTTONS_TITLE'] = 'Month navigation buttons style';
	$arrLang['STYLES_MONTH_NAVIGATION_BUTTONS_BACKGROUND'] = 'Buttons background:';
	$arrLang['STYLES_MONTH_NAVIGATION_BUTTONS_BACKGROUND_HOVER'] = 'Buttons background on mouse over:';
	$arrLang['STYLES_BOOK_NOW_BUTTON_BACKGROUND'] = '\"Book now\" button background:';
	$arrLang['STYLES_BOOK_NOW_BUTTON_BACKGROUND_HOVER'] = '\"Book now\" background on mouse over:';
	$arrLang['STYLES_BOOK_NOW_BUTTON_COLOR'] = '\"Book now\" button text color:';
	$arrLang['STYLES_BOOK_NOW_BUTTON_COLOR_HOVER'] = '\"Book now\" button text color on mouse over:';
	$arrLang['STYLES_CLEAR_BUTTON_BACKGROUND'] = '\"Clear\" button background:';
	$arrLang['STYLES_CLEAR_BUTTON_BACKGROUND_HOVER'] = '\"Clear\" button background on mouse over:';
	$arrLang['STYLES_CLEAR_BUTTON_COLOR'] = '\"Clear\" button text color:';
	$arrLang['STYLES_CLEAR_BUTTON_COLOR_HOVER'] = '\"Clear\" button text color on mouse over:';
	$arrLang['STYLES_FORM_CALENDAR_NAME_COLOR'] = 'Calendar name color:';
	$arrLang['STYLES_CANCEL'] = 'CANCEL';
	$arrLang['STYLES_SAVE'] = 'SAVE';
	$arrLang['CATEGORY_NAME_LABEL'] = 'Name';
	$arrLang['CATEGORY_SHORTCODE_LABEL'] = 'Shortcode';
	$arrLang['CATEGORY_PUBLISHED_LABEL'] = 'Published';
	$arrLang['CATEGORY_SET_AS_DEFAULT'] = 'Set as default';
	$arrLang['CATEGORY_MODIFY_NAME'] = 'Modify name';
	$arrLang['CATEGORY_DELETE'] = 'Delete';
	$arrLang['CATEGORIES_DELETE_CONFIRM_SINGLE'] = 'Are you sure you want to delete this category? All calendars, slots, holidays and reservations related to it will be deleted too';
	$arrLang['CATEGORIES_PUBLISH_CONFIRM_SINGLE'] = 'Are you sure you want to publish this category?';
	$arrLang['CATEGORIES_UNPUBLISH_CONFIRM_SINGLE'] = 'Are you sure you want to unpublish this category?';
	$arrLang['CATEGORY_NAME_ALERT'] = 'Insert a name for the category';
	$arrLang['CREATE_NEW_CATEGORY'] = 'Create new category';
	$arrLang['TYPE_THE_NAME'] = 'Type the name';
	$arrLang['CATEGORY_ADD'] = 'Add';
	$arrLang['CATEGORY_SELECTED_ITEMS'] = 'Selected items';
	$arrLang['CATEGORIES_PUBLISH_CONFIRM_MULTIPLE'] = 'Are you sure you want to publish the selected items?';
	$arrLang['CATEGORIES_NO_ITEMS_SELECTED'] = 'No items selected';
	$arrLang['CATEGORIES_PUBLISH'] = 'Publish';
	$arrLang['CATEGORIES_UNPUBLISH_CONFIRM_MULTIPLE'] = 'Are you sure you want to unpublish the selected items?';
	$arrLang['CATEGORIES_UNPUBLISH'] = 'Unpublish';
	$arrLang['CATEGORIES_DELETE_CONFIRM_MULTIPLE'] = 'Are you sure you want to delete the selected items? All calendars, slots, holidays and reservations related to them will be deleted too';
	$arrLang['CATEGORIES_DELETE'] = 'Delete';
	$arrLang['CATEGORY_SAVE'] = 'Save';
	$arrLang['CALENDAR_TITLE_LABEL'] = 'Title';
	$arrLang['CALENDAR_CATEGORY_LABEL'] = 'Category';
	$arrLang['CALENDAR_SHORTCODE_LABEL'] = 'Shortcode';
	$arrLang['CALENDAR_PUBLISHED_LABEL'] = 'Published';
	$arrLang['CALENDARS_SET_AS_DEFAULT'] = 'Set as default';
	$arrLang['CALENDARS_MODIFY'] = 'Modify';
	$arrLang['CALENDARS_MANAGE'] = 'Manage';
	$arrLang['CALENDARS_DELETE'] = 'Delete';
	$arrLang['MODIFY_SLOTS_ALERT'] = 'There are reservation for one or more of the selected slots. Modify them anyway?';
	$arrLang['DUPLICATE_SLOTS_ALERT'] = 'Duplicate slots. Cannot make these changes';
	$arrLang['HOLIDAY_DATE_LABEL'] = 'Date';
	$arrLang['HOLIDAY_DELETE'] = 'Delete';
	$arrLang['SLOTS_PAGINATION_FIRST'] = 'First';
	$arrLang['SLOTS_PAGINATION_PREV'] = 'Prev';
	$arrLang['SLOTS_PAGINATION_NEXT'] = 'Next';
	$arrLang['SLOTS_PAGINATION_LAST'] = 'Last';
	$arrLang['SLOTS_DATE_LABEL'] = 'Date';
	$arrLang['SLOTS_TIME_FROM_LABEL'] = 'Time From';
	$arrLang['SLOTS_TIME_TO_LABEL'] = 'Time To';
	$arrLang['SLOTS_SPECIAL_TEXT_LABEL'] = 'Special Text';
	$arrLang['SLOTS_PRICE_LABEL'] = 'Price';
	$arrLang['SLOTS_AV_LABEL'] = 'Seats';
	$arrLang['SLOTS_RESERVATION_LABEL'] = 'Reserved';
	$arrLang['SLOTS_HOUR'] = 'hour';
	$arrLang['SLOTS_MINUTE'] = 'minute';
	$arrLang['SLOTS_MODIFY'] = 'Modify';
	$arrLang['SLOTS_DELETE'] = 'Delete';
	$arrLang['SLOTS_PAGES'] = 'Pages';
	$arrLang['DELETED_SLOTS_ALERT'] = 'slots deleted';
	$arrLang['MODIFIED_SLOTS_ALERT'] = 'slots modified';
	$arrLang['ADDED_SLOTS_ALERT'] = 'slots added';
	$arrLang['SELECTED_DAYS_HOLIDAY_ALERT'] = 'Days selected are holidays. Cannot make these changes';
	$arrLang['CALENDAR_YOU_ARE_IN'] = 'You are in';
	$arrLang['CALENDARS'] = 'Calendars';
	$arrLang['STATUS_PUBLISHED'] = 'Published';
	$arrLang['STATUS_UNPUBLISHED'] = 'Unpublished';
	$arrLang['ACTUAL_CALENDAR_STATUS'] = 'The Actual Status of this calendar is';
	$arrLang['MANAGE_TIME_SLOTS'] = 'MANAGE TIME SLOTS';
	$arrLang['CLOSING_DAYS'] = 'CLOSING DAYS';
	$arrLang['CALENDARS_DELETE_CONFIRM_SINGLE'] = 'Are you sure you want to delete this item? All holidays, slots and reservations will be deleted';
	$arrLang['CALENDARS_PUBLISH_CONFIRM_SINGLE'] = 'Are you sure you want to publish this calendar?';
	$arrLang['CALENDARS_UNPUBLISH_CONFIRM_SINGLE'] = 'Are you sure you want to unpublish this calendar?';
	$arrLang['CALENDARS_ADD'] = 'Add';
	$arrLang['NEW_CALENDAR_CHOOSE_CATEGORY'] = 'choose a category';
	$arrLang['CALENDARS_SELECTED_ITEMS'] = 'Selected items';
	$arrLang['CALENDARS_PUBLISH_CONFIRM_MULTIPLE'] = 'Are you sure to publish the selected items?';
	$arrLang['CALENDARS_NO_ITEMS_SELECTED'] = 'No items selected';
	$arrLang['CALENDARS_PUBLISH'] = 'Publish';
	$arrLang['CALENDARS_UNPUBLISH_CONFIRM_MULTIPLE'] = 'Are you sure to unpublish the selected items?';
	$arrLang['CALENDARS_UNPUBLISH'] = 'Unpublish';
	$arrLang['CALENDARS_DELETE_CONFIRM_MULTIPLE'] = 'Are you sure to delete the selected items? Slots and reservations of these calendars will be deleted too';
	$arrLang['CALENDARS_DUPLICATE_CONFIRM_MULTIPLE'] = 'Are you sure to duplicate the selected items?';
	$arrLang['CALENDARS_DUPLICATE'] = 'Duplicate';
	$arrLang['CREATE_CLOSING_DAY_TITLE'] = 'Create Closing Days';
	$arrLang['CREATE_CLOSING_DAY_SUBTITLE'] = 'These days will not be available for booking. If you created time slots in these days, they will be deleted.';
	$arrLang['CREATE_CLOSING_DAY_HOW_MANY'] = 'How many closing days do you want to set?';
	$arrLang['CREATE_CLOSING_DAY_CHOOSE'] = 'choose';
	$arrLang['CREATE_CLOSING_DAY_SINGLE_DAY'] = 'A single day';
	$arrLang['CREATE_CLOSING_DAY_PERIOD'] = 'Period of time';
	$arrLang['HOLIDAYS_FROM'] = 'From';
	$arrLang['HOLIDAYS_TO'] = 'To';
	$arrLang['HOLIDAYS_SET'] = 'SET';
	$arrLang['HOLIDAYS_DAY'] = 'Day';
	$arrLang['HOLIDAYS_DELETE_MULTIPLE_ALERT'] = 'Are you sure to delete the selected items?';
	$arrLang['HOLIDAYS_NO_ITEMS_SELECTED'] = 'No items selected';
	$arrLang['HOLIDAYS_RESERVATION_SINGLE_ALERT'] = 'No items selected';
	$arrLang['HOLIDAYS_RESERVATION_MULTIPLE_ALERT'] = 'There are reservations for one or more dates, are you sure you want to set these days as holidays?';
	$arrLang['HOLIDAYS_DATE_ALERT'] = 'Select a date';
	$arrLang['HOLIDAYS_DELETE_SINGLE_ALERT'] = 'Are you sure you want to delete this item?';
	$arrLang['NEW_CALENDAR_CATEGORY_ALERT'] = 'Select a category for the calendar';
	$arrLang['NEW_CALENDAR_TITLE_ALERT'] = 'Insert a name for the calendar';
	$arrLang['NEW_CALENDAR_CATEGORY_LABEL'] = 'Select calendar category';
	$arrLang['NEW_CALENDAR_NAME_LABEL'] = 'Calendar name';
	$arrLang['NEW_CALENDAR_EMAIL_LABEL'] = 'Calendar admin email address';
	$arrLang['CALENDAR_CANCEL'] = 'CANCEL';
	$arrLang['CALENDAR_SAVE'] = 'SAVE';
	$arrLang['SLOT_TITLE'] = 'Insert your preferences to create the time slots';
	$arrLang['SLOT_DATE_LABEL'] = 'Date';
	$arrLang['SLOT_FROM'] = 'From';
	$arrLang['SLOT_TO'] = 'To';
	$arrLang['SLOT_DATE_FROM_ALERT'] = 'Select a date from';
	$arrLang['SLOT_INTERVAL_CHOOSE_ALERT'] = 'Choose slot interval';
	$arrLang['SLOT_INTERVAL_ALERT'] = 'Insert a valid value for slot interval';
	$arrLang['SLOT_CUSTOM_SLOT_ALERT'] = 'Insert at least a custom slot';
	$arrLang['SLOT_SLOT_DURATION_ALERT'] = 'Slot duration values are not correct';
	$arrLang['SLOT_SLOT_PAUSE_ALERT'] = 'Insert a valid value for slot pause';
	$arrLang['SLOT_TIME_FROM_TIME_TO_ALERT'] = 'Select at least time from and a time to';
	$arrLang['SLOT_TIME_PERIOD_ALERT'] = 'Time periods for slots are not correct';
	$arrLang['SLOT_DATE_TO_SUBLABEL'] = 'Leave this field empty if you want to set a single day';
	$arrLang['SLOT_DELETE'] = 'Delete';
	$arrLang['SLOT_WEEKDAY_LABEL'] = 'Days';
	$arrLang['SLOT_WEEKDAY_ALL'] = 'All';
	$arrLang['SLOT_WEEKDAY_MON'] = 'Mondays';
	$arrLang['SLOT_WEEKDAY_TUE'] = 'Tuesdays';
	$arrLang['SLOT_WEEKDAY_WED'] = 'Wednesdays';
	$arrLang['SLOT_WEEKDAY_THU'] = 'Thursdays';
	$arrLang['SLOT_WEEKDAY_FRI'] = 'Fridays';
	$arrLang['SLOT_WEEKDAY_SAT'] = 'Saturdays';
	$arrLang['SLOT_WEEKDAY_SUN'] = 'Sundays';
	$arrLang['SLOT_DURATION_LABEL'] = 'Slot duration';
	$arrLang['SLOT_DURATION_SUBLABEL'] = 'Select the length each time slot will have';
	$arrLang['SLOT_DURATION_CHOOSE'] = 'choose duration';
	$arrLang['SLOT_DURATION_MINUTES'] = 'in minutes';
	$arrLang['SLOT_DURATION_FROM_TO'] = 'from, to';
	$arrLang['SLOT_SPECIAL_LABEL'] = 'Description text (optional)';
	$arrLang['SLOT_SPECIAL_MODE_BOTH'] = 'Show both times and special text';
	$arrLang['SLOT_SPECIAL_MODE_TEXT'] = 'Show just special text';
	$arrLang['SLOT_INTERVAL_LABEL'] = 'Type here the minutes';
	$arrLang['SLOT_PAUSE_LABEL'] = 'Pause between time slots';
	$arrLang['SLOT_PAUSE_SUBLABEL'] = 'If you like set an interval between the time slots';
	$arrLang['SLOT_TIME_LABEL'] = 'Time';
	$arrLang['SLOT_TIME_SUBLABEL'] = 'Set the period of time in which time slots will be available';
	$arrLang['SLOT_PRICE_LABEL'] = 'Price';
	$arrLang['SLOT_PRICE_SUBLABEL'] = 'Insert price for the slots your are creating';
	$arrLang['SLOT_AV_LABEL'] = 'Slot seats number';
	$arrLang['SLOT_AV_SUBLABEL'] = 'choose availability for every slot';
	$arrLang['SLOT_AV_MAX_LABEL'] = 'Maximum number of bookable seats at once';
	$arrLang['SLOT_AV_MAX_SUBLABEL'] = 'Choose the maximum number of bookable seats at once by a customer';
	$arrLang['SLOT_MODIFY_NEW_AV_MAX'] = 'New maximum bookable slot seats';
	$arrLang['SLOTS_AV_MAX_LABEL'] = 'Max bookable';
	$arrLang['SLOT_CANCEL'] = 'CANCEL';
	$arrLang['SLOT_SAVE'] = 'SAVE';
	$arrLang['CREATE_TIME_SLOTS'] = 'Create Time Slots';
	$arrLang['SLOTS_SELECT_DATE_ALERT'] = 'Select a date';
	$arrLang['SLOTS_DATE_RANGE_ALERT'] = 'Select date range';
	$arrLang['SLOTS_SELECT_RANGE_ALERT'] = 'Select date range';
	$arrLang['SLOTS_DAY'] = 'Day';
	$arrLang['SLOTS_FROM'] = 'From';
	$arrLang['SLOTS_DELETE_CONFIRM_SINGLE'] = 'Are you sure you want to delete this slot?';
	$arrLang['SLOTS_SAVE'] = 'Save';
	$arrLang['SLOTS_DUPLICATE_SLOT_ALERT'] = 'There\'s another slot for the same date and time. Change values';
	$arrLang['SLOTS_TIME_ALERT'] = 'Slot times values are not correct';
	$arrLang['SLOTS_DATE_FROM_ALERT'] = 'Select a date from';
	$arrLang['SLOTS_DATE_TO_ALERT'] = 'Select a date to';
	$arrLang['SLOTS_NEW_TIME_RANGE_ALERT'] = 'New time range values are not correct';
	$arrLang['SLOT_SEARCH_TIME_SLOTS_LABEL'] = 'Search Time Slots';
	$arrLang['SLOT_SEARCH_TIME_SLOTS_SUBLABEL'] = 'Use the following filters to search created time slots';
	$arrLang['SLOT_SEARCH_FILTER_LABEL'] = 'Filter by date:';
	$arrLang['SLOT_SEARCH_FILTER_CHOOSE'] = 'choose a date';
	$arrLang['SLOT_SEARCH_FILTER_SINGLE'] = 'Single day';
	$arrLang['SLOT_SEARCH_FILTER_PERIOD'] = 'Period of time';
	$arrLang['SLOTS_TO'] = 'To';
	$arrLang['SLOT_TIME_FROM_LABEL'] = 'Time From';
	$arrLang['SLOT_TIME_TO_LABEL'] = 'Time To';
	$arrLang['SLOT_TIME_TO_SUBLABEL'] = 'Leave empty if you don\'t want a range period of time';
	$arrLang['SLOTS_SEARCH'] = 'Search';
	$arrLang['SLOT_MODIFY_SLOTS_LABEL'] = 'Modify Time Slots';
	$arrLang['SLOT_MODIFY_SLOTS_ACTION'] = 'Action:';
	$arrLang['SLOT_MODIFY_SLOTS_CHOOSE'] = 'choose';
	$arrLang['SLOT_MODIFY_SLOTS_MODIFY'] = 'modify';
	$arrLang['SLOT_MODIFY_SLOTS_DELETE'] = 'delete';
	$arrLang['SLOT_MODIFY_SLOTS_SLOT'] = 'Slot:';
	$arrLang['SLOT_MODIFY_SLOTS_WEEKDAYS'] = 'Weekdays:';
	$arrLang['SLOT_MODIFY_SLOTS_NEW_TIME_FROM'] = 'New Time From';
	$arrLang['SLOT_MODIFY_SLOTS_NEW_TIME_TO'] = 'New Time To';
	$arrLang['SLOT_MODIFY_NEW_PRICE'] = 'New Price';
	$arrLang['SLOT_MODIFY_NEW_AV'] = 'New slot seats';
	$arrLang['SLOTS_DELETE_MULTIPLE_ALERT'] = 'Are you sure to delete the selected items?';
	$arrLang['SLOTS_NO_ITEMS_SELECTED'] = 'No items selected';
	$arrLang['SLOT_MODIFY_NEW_TEXT'] = 'New text';
	$arrLang['RESERVATION_DATE_LABEL'] = 'Date';
	$arrLang['RESERVATION_TIME_LABEL'] = 'Time';
	$arrLang['RESERVATION_SEATS_LABEL'] = 'Seats';
	$arrLang['RESERVATION_PRICE_LABEL'] = 'Price';
	$arrLang['RESERVATION_SURNAME_NAME_LABEL'] = 'Surname, name';
	$arrLang['RESERVATION_EMAIL_LABEL'] = 'Email';
	$arrLang['RESERVATION_CONFIRMED_LABEL'] = 'Confirmed';
	$arrLang['RESERVATION_CANCELLED'] = 'Cancelled';
	$arrLang['RESERVATION_DELETE'] = 'Delete';
	$arrLang['RESERVATION_DETAIL'] = 'Detail';
	$arrLang['RESERVATION_YOU_ARE_IN'] = 'You are in';
	$arrLang['RESERVATIONS'] = 'Reservations';
	$arrLang['CALENDAR'] = 'Calendar';
	$arrLang['RESERVATIONS_LIST'] = 'Reservations List';
	$arrLang['RESERVATION_SELECT_DATE_ALERT'] = 'Select a date';
	$arrLang['RESERVATION_SELECT_DATE_RANGE_ALERT'] = 'Select date range';
	$arrLang['RESERVATION_DELETE_SINGLE_ALERT'] = 'Are you sure you want to delete this reservation?';
	$arrLang['RESERVATION_PAYPAL_CONFIRM_SINGLE_ALERT'] = 'Are you sure you want to confirm this reservation? It could not have been paid yet, check your Paypal account before confirmation';
	$arrLang['RESERVATION_CONFIRM_SINGLE_ALERT'] = 'Are you sure you want to confirm this reservation?';
	$arrLang['RESERVATION_UNCONFIRM_SINGLE_ALERT'] = 'Are you sure you want to unconfirm this reservation?';
	$arrLang['RESERVATION_DAY'] = 'Day';
	$arrLang['RESERVATION_FROM'] = 'From';
	$arrLang['RESERVATION_SEARCH_RESERVATION_LABEL'] = 'Search Reservations';
	$arrLang['RESERVATION_SEARCH_RESERVATION_SUBLABEL'] = 'Use the following filters to search reservations';
	$arrLang['RESERVATION_SEARCH_FILTER_DATE_LABEL'] = 'Filter by date:';
	$arrLang['RESERVATION_SEARCH_FILTER_DATE_CHOOSE'] = 'choose';
	$arrLang['RESERVATION_SEARCH_FILTER_DATE_ONE_DAY'] = 'One day';
	$arrLang['RESERVATION_SEARCH_FILTER_DATE_PERIOD'] = 'Period of time';
	$arrLang['RESERVATION_TO'] = 'To';
	$arrLang['RESERVATION_SEARCH'] = 'Search';
	$arrLang['RESERVATION_RED_LABEL'] = 'In red lines you\'ll find those reservations whose slots have been deleted';
	$arrLang['RESERVATION_DELETE_MULTIPLE_ALERT'] = 'Are you sure to delete the selected items?';
	$arrLang['RESERVATION_NO_ITEMS_SELECTED'] = 'No items selected';
	$arrLang['CSV_EXPORT'] = 'CSV Export';
	$arrLang['RESERVATION_RESET_LABEL'] = 'Show today\'s reservations';
	$arrLang['RESERVATION_CLOSE'] = 'Close';
	$arrLang['RESERVATION_PRINT'] = 'Print';
	$arrLang['RESERVATION_SURNAME_LABEL'] = 'Surname';
	$arrLang['RESERVATION_NAME_LABEL'] = 'Name';
	$arrLang['RESERVATION_PHONE_LABEL'] = 'Phone';
	$arrLang['RESERVATION_MESSAGE_LABEL'] = 'Message';
	$arrLang['RESERVATION_ADDITIONAL_FIELD1'] = 'Additional field 1';
	$arrLang['RESERVATION_ADDITIONAL_FIELD2'] = 'Additional field 2';
	$arrLang['RESERVATION_ADDITIONAL_FIELD3'] = 'Additional field 3';
	$arrLang['RESERVATION_ADDITIONAL_FIELD4'] = 'Additional field 4';
	$arrLang['RESERVATION_CONFIRMED_YES'] = 'YES';
	$arrLang['RESERVATION_CONFIRMED_NO'] = 'NO';
	$arrLang['RESERVATIONS_PUBLISHED_TITLE'] = 'Published';
	$arrLang['RESERVATION_LIST'] = 'List';
	$arrLang['MAIL_DESCRIPTION_LABEL'] = 'Description';
	$arrLang['MAIL_MODIFY'] = 'Modify';
	$arrLang['MAIL_SUBJECT_ALERT'] = 'Insert mail subject';
	$arrLang['MAIL_TEXT_ALERT'] = 'Insert mail text';
	$arrLang['MAIL_SUBJECT_LABEL'] = 'Email subject';
	$arrLang['MAIL_TEXT_LABEL'] = 'Email text';
	$arrLang['MAIL_TEXT_SUBLABEL1'] = 'The commands in square brackets are necessary for dynamically inserting the data. If you modify or delete them, data will not be inserted.';
	$arrLang['MAIL_TEXT_SUBLABEL2'] = '[customer-name]';
	$arrLang['MAIL_TEXT_SUBLABEL3'] = 'it inserts the user name';
	$arrLang['MAIL_TEXT_SUBLABEL4'] = '[reservation-details]';
	$arrLang['MAIL_TEXT_SUBLABEL5'] = 'it inserts reservation details';
	$arrLang['MAIL_TEXT_SUBLABEL6'] = '[confirmation-link]';
	$arrLang['MAIL_TEXT_SUBLABEL7'] = 'it inserts the confirmation link';
	$arrLang['MAIL_TEXT_SUBLABEL8'] = '[confirmation-link-url]';
	$arrLang['MAIL_TEXT_SUBLABEL9'] = 'it inserts the extended link to be copied and pasted into the URL';
	$arrLang['MAIL_CANCEL_TEXT_LABEL'] = 'Email cancellation text.';
	$arrLang['MAIL_CANCEL_TEXT_SUBLABEL1'] = 'Status:';
	$arrLang['MAIL_ENABLED'] = 'ENABLED';
	$arrLang['MAIL_DISABLED'] = 'DISABLED';
	$arrLang['MAIL_CANCEL_TEXT_SUBLABEL2'] = 'The commands in square brackets are necessary for dynamically inserting the data. If you modify or delete them, data will not be inserted.';
	$arrLang['MAIL_CANCEL_TEXT_SUBLABEL3'] = '[cancellation-link]';
	$arrLang['MAIL_CANCEL_TEXT_SUBLABEL4'] = 'it inserts the cancellation link';
	$arrLang['MAIL_CANCEL_TEXT_SUBLABEL5'] = '[cancellation-link-url]';
	$arrLang['MAIL_CANCEL_TEXT_SUBLABEL6'] = 'it inserts the extended link to be copied and pasted into the URL';
	$arrLang['MAIL_SIGNATURE_LABEL'] = 'Email signature';
	$arrLang['MAIL_CANCEL'] = 'CANCEL';
	$arrLang['MAIL_SAVE'] = 'SAVE';
	$arrLang['FORM_VISIBLE_FIELDS_LABEL'] = 'Choose the visible fields in the reservation form';
	$arrLang['FORM_VISIBLE_FIELDS_SUBLABEL'] = '(multiple selection)';
	$arrLang['FORM_MANDATORY_FIELDS_LABEL'] = 'Choose the mandatory fields in the reservation form';
	$arrLang['FORM_MANDATORY_FIELDS_SUBLABEL'] = '(multiple selection)';
	$arrLang['FORM_FIELDS_TYPE_LABEL'] = 'Fields type';
	$arrLang['FORM_FIELDS_TYPE_SUBLABEL'] = 'choose the type of every form field';
	$arrLang['FORM_FIELDS_TYPE_TEXT'] = 'Text';
	$arrLang['FORM_FIELDS_TYPE_AREA'] = 'TextArea';
	$arrLang['FORM_CANCEL'] = 'CANCEL';
	$arrLang['FORM_SAVE'] = 'SAVE';
	$arrLang['TEXTS_CANCEL'] = 'CANCEL';
	$arrLang['TEXTS_SAVE'] = 'SAVE';
	$arrLang['LEFT_MENU_SETTINGS'] = 'Settings';
	$arrLang['LEFT_MENU_BG_AND_COLORS'] = 'Bg and Colors';
	$arrLang['LEFT_MENU_MANAGE_CATEGORIES'] = 'Manage Categories';
	$arrLang['LEFT_MENU_MANAGE_CALENDARS'] = 'Manage Calendars';
	$arrLang['LEFT_MENU_RESERVATIONS'] = 'Reservations';
	$arrLang['LEFT_MENU_MANAGE_MAIL'] = 'Manage Mail';
	$arrLang['LEFT_MENU_FORM_MANAGEMENT'] = 'Form management';
	$arrLang['LEFT_MENU_TEXT_MANAGEMENT'] = 'Text management';
	$arrLang['LEFT_MENU_PUBLIC_SECTION'] = 'Public section';
	$arrLang['LEFT_MENU_ADMIN_LEFT_MENU'] = 'Admin left menu';
	$arrLang['SELECT_CALENDAR'] = 'Select the calendar:';
	$arrLang['SELECT_CATEGORY'] = 'Select the category:';
	$arrLang['BACK_TODAY'] = 'Back to today';
	$arrLang['JANUARY'] = 'January';
	$arrLang['FEBRUARY'] = 'February';
	$arrLang['MARCH'] = 'March';
	$arrLang['APRIL'] = 'April';
	$arrLang['MAY'] = 'May';
	$arrLang['JUNE'] = 'June';
	$arrLang['JULY'] = 'July';
	$arrLang['AUGUST'] = 'August';
	$arrLang['SEPTEMBER'] = 'September';
	$arrLang['OCTOBER'] = 'October';
	$arrLang['NOVEMBER'] = 'November';
	$arrLang['DECEMBER'] = 'December';
	$arrLang['SUNDAY'] = 'Sunday';
	$arrLang['MONDAY'] = 'Monday';
	$arrLang['TUESDAY'] = 'Tuesday';
	$arrLang['WEDNESDAY'] = 'Wednesday';
	$arrLang['THURSDAY'] = 'Thursday';
	$arrLang['FRIDAY'] = 'Friday';
	$arrLang['SATURDAY'] = 'Saturday';
	$arrLang['DORESERVATION_MAIL_ADMIN_SUBJECT'] = 'New reservation';
	$arrLang['DORESERVATION_MAIL_ADMIN_MESSAGE1'] = 'Reservation data below.';
	$arrLang['DORESERVATION_MAIL_ADMIN_MESSAGE2'] = 'Name';
	$arrLang['DORESERVATION_MAIL_ADMIN_MESSAGE3'] = 'Surname';
	$arrLang['DORESERVATION_MAIL_ADMIN_MESSAGE4'] = 'Email';
	$arrLang['DORESERVATION_MAIL_ADMIN_MESSAGE5'] = 'Phone';
	$arrLang['DORESERVATION_MAIL_ADMIN_MESSAGE6'] = 'Message';
	$arrLang['DORESERVATION_MAIL_ADMIN_MESSAGE7'] = 'Slots reserved';
	$arrLang['DORESERVATION_MAIL_ADMIN_CALENDAR'] = 'Calendar';
	$arrLang['DORESERVATION_MAIL_ADMIN_DATE'] = 'Date';
	$arrLang['DORESERVATION_MAIL_ADMIN_TIME'] = 'Time';
	$arrLang['DORESERVATION_MAIL_ADMIN_MESSAGE8'] = 'All reservations must be confirmed in';
	$arrLang['DORESERVATION_MAIL_ADMIN_MESSAGE9'] = 'admin panel';
	$arrLang['DORESERVATION_MAIL_USER_MESSAGE2'] = 'Slots reserved';
	$arrLang['DORESERVATION_MAIL_USER_VENUE'] = 'Venue';
	$arrLang['DORESERVATION_MAIL_USER_DATE'] = 'Date';
	$arrLang['DORESERVATION_MAIL_USER_TIME'] = 'Time';
	$arrLang['DORESERVATION_MAIL_USER_MESSAGE3'] = 'Click here to confirm your reservation';
	$arrLang['DORESERVATION_MAIL_USER_MESSAGE4'] = 'Click here to cancel your reservation';
	$arrLang['DORESERVATION_MAIL_ADMIN_SEATS'] = 'Seats';
	$arrLang['DORESERVATION_MAIL_USER_SEATS'] = 'Seats';
	$arrLang['DORESERVATION_MAIL_ADMIN_MESSAGE10'] = 'Additional field 1';
	$arrLang['DORESERVATION_MAIL_ADMIN_MESSAGE11'] = 'Additional field 2';
	$arrLang['DORESERVATION_MAIL_ADMIN_MESSAGE12'] = 'Additional field 3';
	$arrLang['DORESERVATION_MAIL_ADMIN_MESSAGE13'] = 'Additional field 4';
	$arrLang['GETBOOKINGFORM_PREV_DAY'] = 'Prev day';
	$arrLang['GETBOOKINGFORM_NEXT_DAY'] = 'Next day';
	$arrLang['GETBOOKINGFORM_CLOSE'] = 'CLOSE';
	$arrLang['GETBOOKINGFORM_SLOT_ALERT'] = 'Select at least one time slot';
	$arrLang['GETBOOKINGFORM_CAPTCHA_ALERT'] = 'Insert valid verification code';
	$arrLang['FREE'] = 'Free';
	$arrLang['SELECT_SEATS'] = 'Select seats';
	$arrLang['GETMONTHCALENDAR_SLOTS_AVAILABLE'] = 'Available';
	$arrLang['GETMONTHCALENDAR_NO_SLOTS_AVAILABLE'] = 'Not available';
	$arrLang['GETMONTHCALENDAR_BOOK_NOW'] = 'BOOK NOW';
	$arrLang['GETMONTHCALENDAR_SOLDOUT'] = 'SOLDOUT';
	$arrLang['CONFIRM_RESERVATION_CONFIRMED'] = 'Well done!';
	$arrLang['CONFIRM_RESERVATION_CONFIRMED_2'] = 'Your reservation is successfully confirmed.';
	$arrLang['CONFIRM_REDIRECT'] = 'Ok, thanks.';
	$arrLang['CANCEL_RESERVATION_CONFIRMED'] = 'Well done!';
	$arrLang['CANCEL_RESERVATION_CONFIRMED_2'] = 'Your reservation is successfully cancelled.';
	$arrLang['CANCEL_REDIRECT'] = 'Ok, thanks.';
	$arrLang['CANCEL_MAIL_ADMIN_SUBJECT'] = 'A user has cancelled his reservation';
	$arrLang['CANCEL_MAIL_ADMIN_MESSAGE1'] = 'A user has cancelled his reservation. Check it in the admin panel:';
	$arrLang['CANCEL_MAIL_ADMIN_MESSAGE2'] = 'click here';
	$arrLang['INDEX_NAME'] = 'Name';
	$arrLang['INDEX_NAME_ALERT'] = 'Insert your name';
	$arrLang['INDEX_SURNAME'] = 'Surname';
	$arrLang['INDEX_SURNAME_ALERT'] = 'Insert your surname';
	$arrLang['INDEX_EMAIL'] = 'Email';
	$arrLang['INDEX_EMAIL_ALERT'] = 'Insert a valid email address';
	$arrLang['INDEX_PHONE'] = 'Phone';
	$arrLang['INDEX_PHONE_ALERT'] = 'Insert your phone';
	$arrLang['INDEX_MESSAGE'] = 'Message';
	$arrLang['INDEX_MESSAGE_ALERT'] = 'Insert a message';
	$arrLang['INDEX_TERMS_AND_CONDITIONS_ALERT'] = 'You have to accept terms and conditions';
	$arrLang['INDEX_INVALID_CODE'] = 'Invalid code';
	$arrLang['INDEX_BOOK_NOW'] = 'BOOK NOW';
	$arrLang['INDEX_CLEAR'] = 'clear';
	$arrLang['INDEX_CONFIRM1'] = 'Thank you for booking online. You\'ll receive an email confirmation at your email address';
	$arrLang['INDEX_CONFIRM2'] = 'Thank you for booking online. Check your email box to confirm the reservation.';
	$arrLang['INDEX_CONFIRM3'] = 'Thank you for booking online. Your reservation will be confirmed by e-mail.';
	$arrLang['INDEX_RESERVATION_ADDITIONAL_FIELD1'] = 'Additional field 1';
	$arrLang['INDEX_RESERVATION_ADDITIONAL_FIELD1_ALERT'] = 'Insert additional field 1';
	$arrLang['INDEX_RESERVATION_ADDITIONAL_FIELD2'] = 'Additional field 2';
	$arrLang['INDEX_RESERVATION_ADDITIONAL_FIELD2_ALERT'] = 'Insert additional field 2';
	$arrLang['INDEX_RESERVATION_ADDITIONAL_FIELD3'] = 'Additional field 3';
	$arrLang['INDEX_RESERVATION_ADDITIONAL_FIELD3_ALERT'] = 'Insert additional field 3';
	$arrLang['INDEX_RESERVATION_ADDITIONAL_FIELD4'] = 'Additional field 4';
	$arrLang['INDEX_RESERVATION_ADDITIONAL_FIELD4_ALERT'] = 'Insert additional field 4';
	$arrLang['PAYPAL_CONFIRM_CONFIRMED_1'] = 'Well done!';
	$arrLang['PAYPAL_CONFIRM_CONFIRMED_2'] = 'Your reservation is successfully confirmed.';
	$arrLang['PAYPAL_CONFIRM_REDIRECT'] = 'Ok, thanks.';
	$arrLang['EXPIRED_LINK'] = 'Expired link';
	$arrLang['USER_RESERVATION_CANCEL'] = 'Cancel';
	$arrLang['USER_RESERVATION_CANCEL_PAID_ALERT'] = 'You cannot cancel this reservation as the payment was successfull. Contact the administrator for its cancellation.';
	$arrLang['USER_RESERVATION_CANCEL_CONFIRM'] = 'Are you sure you want to cancel this reservation?';
	$arrLang['USER_RESERVATION_CONFIRM_ALERT'] = 'You can\'t confirm this reservation, only administrator is able to do it as he has to check your payment first';
	$arrLang['CONFIGURATION_WORDPRESS_REGISTRATION_LABEL'] = 'Allow booking to WP registered users only and allow WP registration through this plugin.';
	$arrLang['CONFIGURATION_WORDPRESS_REGISTRATION_SUBLABEL'] = 'Activating this option people will be able to register to your WP site and only WP registered users will be able to book from your Booking Calendar. You can choose the role for these users when registrating through this plugin.';
	$arrLang['CONFIGURATION_WORDPRESS_REGISTRATION_NO'] = 'NO';
	$arrLang['CONFIGURATION_WORDPRESS_REGISTRATION_YES'] = 'YES';
	$arrLang['CONFIGURATION_USERS_ROLE_LABEL'] = 'Select the role for users who register to your site through the plugin';
	$arrLang['CONFIGURATION_REGISTRATION_TEXT_LABEL'] = 'Choose the text to show in the registration and login form';
	$arrLang['ADMIN_CONTACT_SUBJECT'] = 'Subject';
	$arrLang['ADMIN_CONTACT_MESSAGE'] = 'Message';
	$arrLang['ADMIN_CONTACT_SEND'] = 'Send';
	$arrLang['ADMIN_CONTACT_SUBJECT_ALERT'] = 'Insert a subject for the message';
	$arrLang['ADMIN_CONTACT_MESSAGE_ALERT'] = 'Write a message for the administrator';
	$arrLang['LEFT_MENU_CONTACT_ADMINISTRATOR'] = 'Contact Administrator';
	$arrLang['LEFT_MENU_USER_RESERVATIONS'] = 'Your reservations';
	$arrLang['INDEX_LOGIN_TAB'] = 'Login';
	$arrLang['INDEX_REGISTER_TAB'] = 'Register';
	$arrLang['INDEX_LOGIN_USERNAME'] = 'Username';
	$arrLang['INDEX_LOGIN_PASSWORD'] = 'Password';
	$arrLang['INDEX_LOGIN_BUTTON'] = 'Login';
	$arrLang['INDEX_REGISTER_MANDATORY'] = 'All fields are mandatory';
	$arrLang['INDEX_REGISTER_USERNAME'] = 'Username';
	$arrLang['INDEX_REGISTER_EMAIL'] = 'Your email';
	$arrLang['INDEX_REGISTER_PASSWORD'] = 'Your password';
	$arrLang['INDEX_REGISTER_CONFIRM_PASSWORD'] = 'Confirm password';
	$arrLang['INDEX_REGISTER_BUTTON'] = 'Sign up';
	$arrLang['INDEX_RESPONSE_OK_BUTTON'] = 'OK';
	$arrLang['INDEX_LOGIN_ERROR_DATA_ALERT'] = 'Invalid username or password';
	$arrLang['INDEX_LOGIN_EMPTY_DATA_ALERT'] = 'Insert a valid username and password';
	$arrLang['INDEX_REGISTER_USERNAME_ALERT'] = 'Insert a username';
	$arrLang['INDEX_REGISTER_EMAIL_ALERT'] = 'Insert a valid email address';
	$arrLang['INDEX_REGISTER_PASSWORD_ALERT'] = 'Insert a password';
	$arrLang['INDEX_REGISTER_CONFIRM_PASSWORD_ALERT'] = 'Confirm password must match password';
	$arrLang['REGISTERUSER_USER_REGISTERED_ALERT'] = 'You\'ve been successfully registered';
	$arrLang['REGISTERUSER_USER_EXISTING_ALERT'] = 'Existing user, please login';
	$arrLang['RESERVATION_USER_CONTACT_SUBJECT'] = 'Subject';
	$arrLang['RESERVATION_USER_CONTACT_MESSAGE'] = 'Message';
	$arrLang['RESERVATION_USER_CONTACT_BUTTON'] = 'Send';
	$arrLang['RESERVATION_USER_CONTACT_SUBJECT_ALERT'] = 'Insert a subject for the message';
	$arrLang['RESERVATION_USER_CONTACT_MESSAGE_ALERT'] = 'Insert a message for the user';
	$arrLang['RESERVATION_WORDPRESS_USER'] = 'Wordpress user';
	$arrLang['RESERVATION_SEARCH_FILTER_WP_USER_ALL'] = 'all';
	$arrLang['PAYPAL_CONFIRM_NOT_CONFIRMED_1'] = 'We\'re sorry';
	$arrLang['PAYPAL_CONFIRM_NOT_CONFIRMED_2'] = 'There\'s been a problem with your payment and your reservation has been cancelled.';
	$arrLang['RESERVATION_SEARCH_FILTER_WP_USER_NOT_REGISTERED'] = 'not registered';
	$arrLang['ADMIN_CONTACT_TEXT'] = 'Please use this form to contact the administrator for any need about your reservations';
	$arrLang['RESERVATION_USER_CONTACT_MODAL_TEXT1'] = 'Message to';
	$arrLang['RESERVATION_USER_CONTACT_MODAL_TEXT2'] = 'Reservation info:';
	$arrLang['RESERVATION_USER_CONTACT_TO'] = 'To (multiple addresses must be separated by comma)';
	$arrLang['RESERVATION_USER_CONTACT_CC'] = 'Cc (multiple addresses must be separated by comma)';
	$arrLang['RESERVATION_USER_CONTACT_BCC'] = 'Bcc (multiple addresses must be separated by comma)';
	$arrLang['RESERVATION_USER_CONTACT_TO_ALERT'] = 'You must add at least an email address to send the email';
	$arrLang['RESERVATION_USER_CONTACT_MESSAGE_SENT'] = 'Message successfully sent!';
	$arrLang['RESERVATION_USER_CONTACT_MESSAGE_ERROR'] = 'An error has occurred. Retry';
	$arrLang['ADMIN_CONTACT_MESSAGE_SENT'] = 'Message successfully sent!';
	$arrLang['ADMIN_CONTACT_MESSAGE_ERROR'] = 'An error has occurred. Retry';
	$arrLang['DORESERVATION_MAIL_USER_PRICE'] = 'Price';
	$arrLang['DORESERVATION_MAIL_ADMIN_PRICE'] = 'Price';
	$arrLang['DORESERVATION_ERROR'] = 'An error occurred. This time slot may be already reserved. Please try again';
	$arrLang['SLOT_SUBTITLE'] = 'Remember to limit the time period to a maximum of 3 months at once if you have many slots in a day as there is a limit which prevent to insert more than 2000 slots at once to avoid your WP site to crash or block during slots creation';
	$arrLang['SLOT_CUSTOM_TIME_LABEL'] = 'Even if you want to set a fixed hour (i.e. 6:00), please remember to select minutes too (00) or you\'ll get the error \"Duplicated slots\"';

    $table_name = $wpdb->prefix.'booking_texts';
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {

        require_once(dirname(__FILE__).'/admin/class/lang.class.php');
        $bookingLangObj = new wp_booking_calendar_lang();

        require_once(dirname(__FILE__).'/admin/libs/Sepia/InterfaceHandler.php');
        require_once(dirname(__FILE__).'/admin/libs/Sepia/FileHandler.php');
        require_once(dirname(__FILE__).'/admin/libs/Sepia/PoParser.php');

        require_once(dirname(__FILE__).'/admin/libs/php-mo.php');

        /*get texts from db and insert them into a .po file*/
        $locale = get_bloginfo( 'language' );
        if($locale != 'en-US') {
            /*create the new .po*/
            $filename = "wp-booking-calendar-".str_replace("-","_",$locale).".po";
            if(!file_exists(dirname(__FILE__).'/lang/'.$filename)) {
                copy(dirname(__FILE__).'/lang/wp-booking-calendar-en_US.po', dirname(__FILE__).'/lang/'.$filename);
                /* Parse a po file*/
                $fileHandler = new Sepia\FileHandler(dirname(__FILE__).'/lang/'.$filename);
                $poParser = new Sepia\PoParser($fileHandler);
                $entries  = $poParser->parse();

                foreach($arrLang as $key => $val) {
                    /*get label from database*/
                    $label = $bookingLangObj->getLabel($key);
                    $msgid = stripslashes($val);
                    $entries[$msgid]['msgstr'] = $label;
                    $poParser->setEntry($msgid, $entries[$msgid]);
                }
                $poParser->writeFile(dirname(__FILE__).'/lang/'.$filename);
                phpmo_convert( dirname(__FILE__).'/lang/'.$filename );
            }


        } else {
            $filename = "wp-booking-calendar-en_US.po";
            /* Parse a po file*/
            $fileHandler = new Sepia\FileHandler(dirname(__FILE__).'/lang/'.$filename);
            $poParser = new Sepia\PoParser($fileHandler);
            $entries  = $poParser->parse();

            foreach($arrLang as $key => $val) {
                /*get label from database*/
                $label = $bookingLangObj->getLabel($key);
                $msgid = stripslashes($val);
                $entries[$msgid]['msgstr'] = $label;
                $poParser->setEntry($msgid, $entries[$msgid]);
            }
            $poParser->writeFile(dirname(__FILE__).'/lang/'.$filename);
            phpmo_convert( dirname(__FILE__).'/lang/'.$filename );
        }
    }



	
    
}

function wp_booking_multisite_update() {
	global $wpdb;
	global $blog_id;
	$current_blog = $blog_id;
	
	require_once(dirname(__FILE__).'/admin/class/settings.class.php');
	$bookingSettingObj = new wp_booking_calendar_setting();
	
	if (function_exists('is_multisite') && is_multisite()) {
		
		$blogsQry =  $wpdb->get_results("SELECT * FROM ".$wpdb->base_prefix."blogs");
		for($i=0;$i<count($blogsQry);$i++) {
			$blog_id = $blogsQry[$i]->blog_id;
			$blog_prefix=$blog_id."_";
			if($blog_id==1) {
				$blog_prefix="";
			}
			switch_to_blog($blog_id);


			booking_calendar_install_db($blog_prefix);
			
			$sql5="INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_categories (category_id,category_name,category_order,category_active)
			SELECT * FROM (SELECT '1','default category','0','1' as category_active) AS tmp
			WHERE NOT EXISTS (
				SELECT category_id,category_name,category_order,category_active FROM ".$wpdb->base_prefix.$blog_prefix."booking_categories WHERE category_id = 1
			) LIMIT 1;";
			dbDelta($sql5);
			$sql6="UPDATE ".$wpdb->base_prefix.$blog_prefix."booking_calendars SET category_id = 1 WHERE category_id = 0";
			dbDelta($sql6);
			
			$sql7="UPDATE ".$wpdb->base_prefix.$blog_prefix."booking_slots SET slot_av_max = slot_av WHERE slot_av > 0 AND slot_av_max = 0";
			dbDelta($sql7);
			
			/*remove styles for day_black*/
			$sql7="DELETE FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name IN('day_black_line2_color_hover','day_black_line2_color','day_black_line1_color_hover','day_black_line1_color','day_black_bg_hover','day_black_bg') ";
			dbDelta($sql7);
			
			
			
			if($bookingSettingObj->getPaypal() == 1) {
				/*update paypal options if necessary*/
				$sql7="UPDATE ".$wpdb->base_prefix.$blog_prefix."booking_config SET config_value = '1' WHERE config_name='reservation_confirmation_mode_override'";
				dbDelta($sql7);
			}

		}
		
		switch_to_blog($current_blog);

	} else {
		$blog_prefix = '';
		booking_calendar_install_db($blog_prefix);
		$sql5="INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_categories (category_id,category_name,category_order,category_active)
		SELECT * FROM (SELECT '1','default category','0','1' as category_active) AS tmp
		WHERE NOT EXISTS (
			SELECT category_id,category_name,category_order,category_active FROM ".$wpdb->base_prefix.$blog_prefix."booking_categories WHERE category_id = 1
		) LIMIT 1;";
		dbDelta($sql5);
		$sql6="UPDATE ".$wpdb->base_prefix.$blog_prefix."booking_calendars SET category_id = 1 WHERE category_id = 0";
		dbDelta($sql6);
		
		$sql7="UPDATE ".$wpdb->base_prefix.$blog_prefix."booking_slots SET slot_av_max = slot_av WHERE slot_av > 0 AND slot_av_max = 0";
			dbDelta($sql7);
		
		/*remove styles for day_black*/
			$sql7="DELETE FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name IN('day_black_line2_color_hover','day_black_line2_color','day_black_line1_color_hover','day_black_line1_color','day_black_bg_hover','day_black_bg') ";
			dbDelta($sql7);
			
			
			
			if($bookingSettingObj->getPaypal() == 1) {
				/*update paypal options if necessary*/
				$sql7="UPDATE ".$wpdb->base_prefix.$blog_prefix."booking_config SET config_value = '1' WHERE config_name='reservation_confirmation_mode_override'";
				dbDelta($sql7);
			}
	}
	
	/*get registered users role from db*/
	if (function_exists('is_multisite') && is_multisite()) {
		$blogsQry =  $wpdb->get_results("SELECT * FROM ".$wpdb->base_prefix."blogs");
		for($i=0;$i<count($blogsQry);$i++) {
			$blog_id = $blogsQry[$i]->blog_id;
			$blog_prefix=$blog_id."_";
			if($blog_id==1) {
				$blog_prefix="";
			}
			switch_to_blog($blog_id);

			$query = "SELECT config_value FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name='users_role'";
			
			if($wpdb->query($query)>0 && $wpdb->get_var($query) != '') {
				$role = get_role(strtolower($wpdb->get_var($query)));
				$role->add_cap('wbc_user_orders');
			}
		}
		
		switch_to_blog($current_blog);
			
	} else {
		$blog_prefix = '';
		$query = "SELECT config_value FROM ".$wpdb->base_prefix.$blog_prefix."booking_config WHERE config_name='users_role'";
		
		if($wpdb->query($query)>0 && $wpdb->get_var($query) != '') {
			$role = get_role(strtolower($wpdb->get_var($query)));
			$role->add_cap('wbc_user_orders');
		}
	}


	

}

$role = get_role( 'administrator' );
$role->add_cap( 'wbc_view_slots' );
$role->add_cap( 'wbc_view_settings' );
$role->add_cap( 'wbc_view_reservations' );
$role->add_cap( 'wbc_add_slots' );
$role->add_cap( 'wbc_approve_reservations' );
$role->add_cap( 'wbc_user_orders' );



?>