<?php

class wp_booking_calendar_lang {
	
	private function doLanguageQuery($label) {
		global $wpdb;
		global $blog_id;
		$blog_prefix=$blog_id."_";
		if($blog_id==1) {
			$blog_prefix="";
		}
		$languageQry = $wpdb->get_var($wpdb->prepare("SELECT text_value FROM ".$wpdb->base_prefix.$blog_prefix."booking_texts WHERE text_label=%s",$label));
		return $languageQry;

	}
	
	public function getLabel($label) {
		global $wpdb;
		return wp_booking_calendar_lang::doLanguageQuery($label);
	}
	
	public function updateTexts() {
		global $wpdb;
		global $blog_id;
		$blog_prefix=$blog_id."_";
		if($blog_id==1) {
			$blog_prefix="";
		}
		$arrayLabels=$_POST["text_label"];
		$arrayTexts=$_POST["text_value"];
		for($i=0;$i<count($arrayLabels);$i++) {
			$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->base_prefix.$blog_prefix."booking_texts
						 SET text_value=%s
						 WHERE text_label=%s",$arrayTexts[$i],$arrayLabels[$i]));
		}
	
	}
	
	public function saveTexts() {
		global $wpdb;
		global $blog_id;
		$blog_prefix=$blog_id."_";
		if($blog_id==1) {
			$blog_prefix="";
		}
		$arrayLabels=$_POST["text_label"];
		$arrayTexts=$_POST["text_value"];
		for($i=0;$i<count($arrayLabels);$i++) {
			$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->base_prefix.$blog_prefix."booking_texts
						 SET text_value=%s
						 WHERE text_label=%s",$arrayTexts[$i],$arrayLabels[$i]));
		}
	
	}
	
	public function importLang() {
		global $wpdb;
		global $blog_id;
		$blog_prefix=$blog_id."_";
		if($blog_id==1) {
			$blog_prefix="";
		}
		
		$upload_dir = wp_upload_dir();
		if(isset($_FILES["admin_file"]["tmp_name"]) && $_FILES["admin_file"]["tmp_name"] != '') {
			if(move_uploaded_file($_FILES["admin_file"]["tmp_name"], $upload_dir['basedir'] . "/".str_replace(" ","",$_FILES["admin_file"]["name"]))) {
				/*include the file*/
				$arrlang = Array();
				include $upload_dir['basedir'] . "/".str_replace(" ","",$_FILES["admin_file"]["name"]);
				$arrlang = $lang;
				foreach($arrlang as $key => $val) {
					$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->base_prefix.$blog_prefix."booking_texts SET text_value = %s WHERE text_label = %s",$val,$key)); 
				}
				update_option('wbc_show_text_update_admin','0');
				/*delete file*/
				unlink($upload_dir['basedir'] . "/".str_replace(" ","",$_FILES["admin_file"]["name"]));
			}
			
		}
		if(isset($_FILES["public_file"]["tmp_name"]) && $_FILES["public_file"]["tmp_name"] != '') {
			if(move_uploaded_file($_FILES["public_file"]["tmp_name"], $upload_dir['basedir'] . "/".str_replace(" ","",$_FILES["public_file"]["name"]))) {
				/*include the file*/
				$arrlang = Array();
				include $upload_dir['basedir'] . "/".str_replace(" ","",$_FILES["public_file"]["name"]);
				$arrlang = $lang;
				foreach($arrlang as $key => $val) {
					$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->base_prefix.$blog_prefix."booking_texts SET text_value = %s WHERE text_label = %s"),$val,$key); 
				}
				update_option('wbc_show_text_update_public','0');
				/*delete file*/
				unlink($upload_dir['basedir'] . "/".str_replace(" ","",$_FILES["public_file"]["name"]));
			}
			
		}
	}
	
	
	public function importTextLang() {
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
		
			
			require_once(dirname(__FILE__).'/../libs/Sepia/InterfaceHandler.php');
			require_once(dirname(__FILE__).'/../libs/Sepia/FileHandler.php');	
			require_once(dirname(__FILE__).'/../libs/Sepia/PoParser.php');
			
			require_once(dirname(__FILE__).'/../libs/php-mo.php');
			
			/*get texts from db and insert them into a .po file*/
			$locale = $_POST["locale"];
			if($locale != 'en-US') {
				$filename = "wp-booking-calendar-".str_replace("-","_",$locale).".po";
				copy(dirname(__FILE__).'/../../lang/wp-booking-calendar-en_US.po', dirname(__FILE__).'/../../lang/'.$filename);
				$fileHandler = new Sepia\FileHandler(dirname(__FILE__).'/../../lang/'.$filename);
				$poParser = new Sepia\PoParser($fileHandler);
				$entries  = $poParser->parse();
				
				foreach($arrLang as $key => $val) {
					/*get label from database*/
					$label = $this->getLabel($key);
					$msgid = stripslashes($val);
					$entries[$msgid]['msgstr'] = $label;
					$poParser->setEntry($msgid, $entries[$msgid]);

				}
				$poParser->writeFile(dirname(__FILE__).'/../../lang/'.$filename);
				phpmo_convert( dirname(__FILE__).'/../../lang/'.$filename );
				
					
			} else {
				$filename = "wp-booking-calendar-en_US.po";
				$fileHandler = new Sepia\FileHandler(dirname(__FILE__).'/../../lang/'.$filename);
				$poParser = new Sepia\PoParser($fileHandler);
				$entries  = $poParser->parse();
					
				foreach($arrLang as $key => $val) {
					/*get label from database*/
					$label = $this->getLabel($key);
					$msgid = stripslashes($val);
					$entries[$msgid]['msgstr'] = $label;
					$poParser->setEntry($msgid, $entries[$msgid]);
				}
				$poParser->writeFile(dirname(__FILE__).'/../../lang/'.$filename);
				phpmo_convert( dirname(__FILE__).'/../../lang/'.$filename );
			}
			
    		
	}

}

?>
