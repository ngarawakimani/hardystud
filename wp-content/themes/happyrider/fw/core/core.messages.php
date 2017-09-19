<?php
/**
 * HappyRider Framework: messages subsystem
 *
 * @package	happyrider
 * @since	happyrider 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('happyrider_messages_theme_setup')) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_messages_theme_setup' );
	function happyrider_messages_theme_setup() {
		// Core messages strings
		add_action('happyrider_action_add_scripts_inline', 'happyrider_messages_add_scripts_inline');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('happyrider_get_error_msg')) {
	function happyrider_get_error_msg() {
		global $HAPPYRIDER_GLOBALS;
		return !empty($HAPPYRIDER_GLOBALS['error_msg']) ? $HAPPYRIDER_GLOBALS['error_msg'] : '';
	}
}

if (!function_exists('happyrider_set_error_msg')) {
	function happyrider_set_error_msg($msg) {
		global $HAPPYRIDER_GLOBALS;
		$msg2 = happyrider_get_error_msg();
		$HAPPYRIDER_GLOBALS['error_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}

if (!function_exists('happyrider_get_success_msg')) {
	function happyrider_get_success_msg() {
		global $HAPPYRIDER_GLOBALS;
		return !empty($HAPPYRIDER_GLOBALS['success_msg']) ? $HAPPYRIDER_GLOBALS['success_msg'] : '';
	}
}

if (!function_exists('happyrider_set_success_msg')) {
	function happyrider_set_success_msg($msg) {
		global $HAPPYRIDER_GLOBALS;
		$msg2 = happyrider_get_success_msg();
		$HAPPYRIDER_GLOBALS['success_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}

if (!function_exists('happyrider_get_notice_msg')) {
	function happyrider_get_notice_msg() {
		global $HAPPYRIDER_GLOBALS;
		return !empty($HAPPYRIDER_GLOBALS['notice_msg']) ? $HAPPYRIDER_GLOBALS['notice_msg'] : '';
	}
}

if (!function_exists('happyrider_set_notice_msg')) {
	function happyrider_set_notice_msg($msg) {
		global $HAPPYRIDER_GLOBALS;
		$msg2 = happyrider_get_notice_msg();
		$HAPPYRIDER_GLOBALS['notice_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('happyrider_set_system_message')) {
	function happyrider_set_system_message($msg, $status='info', $hdr='') {
		update_option('happyrider_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('happyrider_get_system_message')) {
	function happyrider_get_system_message($del=false) {
		$msg = get_option('happyrider_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			happyrider_del_system_message();
		return $msg;
	}
}

if (!function_exists('happyrider_del_system_message')) {
	function happyrider_del_system_message() {
		delete_option('happyrider_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('happyrider_messages_add_scripts_inline')) {
	function happyrider_messages_add_scripts_inline() {
		global $HAPPYRIDER_GLOBALS;
		echo '<script type="text/javascript">'
			. 'jQuery(document).ready(function() {'
			// Strings for translation
			. 'HAPPYRIDER_GLOBALS["strings"] = {'
				. 'bookmark_add: 		"' . addslashes(__('Add the bookmark', 'happyrider')) . '",'
				. 'bookmark_added:		"' . addslashes(__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'happyrider')) . '",'
				. 'bookmark_del: 		"' . addslashes(__('Delete this bookmark', 'happyrider')) . '",'
				. 'bookmark_title:		"' . addslashes(__('Enter bookmark title', 'happyrider')) . '",'
				. 'bookmark_exists:		"' . addslashes(__('Current page already exists in the bookmarks list', 'happyrider')) . '",'
				. 'search_error:		"' . addslashes(__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'happyrider')) . '",'
				. 'email_confirm:		"' . addslashes(__('On the e-mail address <b>%s</b> we sent a confirmation email.<br>Please, open it and click on the link.', 'happyrider')) . '",'
				. 'reviews_vote:		"' . addslashes(__('Thanks for your vote! New average rating is:', 'happyrider')) . '",'
				. 'reviews_error:		"' . addslashes(__('Error saving your vote! Please, try again later.', 'happyrider')) . '",'
				. 'error_like:			"' . addslashes(__('Error saving your like! Please, try again later.', 'happyrider')) . '",'
				. 'error_global:		"' . addslashes(__('Global error text', 'happyrider')) . '",'
				. 'name_empty:			"' . addslashes(__('The name can\'t be empty', 'happyrider')) . '",'
				. 'name_long:			"' . addslashes(__('Too long name', 'happyrider')) . '",'
				. 'email_empty:			"' . addslashes(__('Too short (or empty) email address', 'happyrider')) . '",'
				. 'email_long:			"' . addslashes(__('Too long email address', 'happyrider')) . '",'
				. 'email_not_valid:		"' . addslashes(__('Invalid email address', 'happyrider')) . '",'
				. 'subject_empty:		"' . addslashes(__('The subject can\'t be empty', 'happyrider')) . '",'
				. 'subject_long:		"' . addslashes(__('Too long subject', 'happyrider')) . '",'
				. 'text_empty:			"' . addslashes(__('The message text can\'t be empty', 'happyrider')) . '",'
				. 'text_long:			"' . addslashes(__('Too long message text', 'happyrider')) . '",'
				. 'send_complete:		"' . addslashes(__("Send message complete!", 'happyrider')) . '",'
				. 'send_error:			"' . addslashes(__('Transmit failed!', 'happyrider')) . '",'
				. 'login_empty:			"' . addslashes(__('The Login field can\'t be empty', 'happyrider')) . '",'
				. 'login_long:			"' . addslashes(__('Too long login field', 'happyrider')) . '",'
				. 'login_success:		"' . addslashes(__('Login success! The page will be reloaded in 3 sec.', 'happyrider')) . '",'
				. 'login_failed:		"' . addslashes(__('Login failed!', 'happyrider')) . '",'
				. 'password_empty:		"' . addslashes(__('The password can\'t be empty and shorter then 4 characters', 'happyrider')) . '",'
				. 'password_long:		"' . addslashes(__('Too long password', 'happyrider')) . '",'
				. 'password_not_equal:	"' . addslashes(__('The passwords in both fields are not equal', 'happyrider')) . '",'
				. 'registration_success:"' . addslashes(__('Registration success! Please log in!', 'happyrider')) . '",'
				. 'registration_failed:	"' . addslashes(__('Registration failed!', 'happyrider')) . '",'
				. 'geocode_error:		"' . addslashes(__('Geocode was not successful for the following reason:', 'happyrider')) . '",'
				. 'googlemap_not_avail:	"' . addslashes(__('Google map API not available!', 'happyrider')) . '",'
				. 'editor_save_success:	"' . addslashes(__("Post content saved!", 'happyrider')) . '",'
				. 'editor_save_error:	"' . addslashes(__("Error saving post data!", 'happyrider')) . '",'
				. 'editor_delete_post:	"' . addslashes(__("You really want to delete the current post?", 'happyrider')) . '",'
				. 'editor_delete_post_header:"' . addslashes(__("Delete post", 'happyrider')) . '",'
				. 'editor_delete_success:	"' . addslashes(__("Post deleted!", 'happyrider')) . '",'
				. 'editor_delete_error:		"' . addslashes(__("Error deleting post!", 'happyrider')) . '",'
				. 'editor_caption_cancel:	"' . addslashes(__('Cancel', 'happyrider')) . '",'
				. 'editor_caption_close:	"' . addslashes(__('Close', 'happyrider')) . '"'
				. '};'
			. '});'
			. '</script>';
	}
}
?>