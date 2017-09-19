<?php
/**
 * HappyRider Framework: Theme options custom fields
 *
 * @package	happyrider
 * @since	happyrider 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'happyrider_options_custom_theme_setup' ) ) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_options_custom_theme_setup' );
	function happyrider_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'happyrider_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'happyrider_options_custom_load_scripts' ) ) {
	//add_action("admin_enqueue_scripts", 'happyrider_options_custom_load_scripts');
	function happyrider_options_custom_load_scripts() {
		happyrider_enqueue_script( 'happyrider-options-custom-script',	happyrider_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );
	}
}


// Show theme specific fields in Post (and Page) options
function happyrider_show_custom_field($id, $field, $value) {
	$output = '';
	switch ($field['type']) {
		case 'reviews':
			$output .= '<div class="reviews_block">' . trim(happyrider_reviews_get_markup($field, $value, true)) . '</div>';
			break;

		case 'mediamanager':
			wp_enqueue_media( );
			$output .= '<a id="'.esc_attr($id).'" class="button mediamanager"
				data-param="' . esc_attr($id) . '"
				data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? __( 'Choose Images', 'happyrider') : __( 'Choose Image', 'happyrider')).'"
				data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? __( 'Add to Gallery', 'happyrider') : __( 'Choose Image', 'happyrider')).'"
				data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
				data-linked-field="'.esc_attr($field['media_field_id']).'"
				onclick="happyrider_show_media_manager(this); return false;"
				>' . (isset($field['multiple']) && $field['multiple'] ? __( 'Choose Images', 'happyrider') : __( 'Choose Image', 'happyrider')) . '</a>';
			break;
	}
	return apply_filters('happyrider_filter_show_custom_field', $output, $id, $field, $value);
}
?>