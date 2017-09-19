<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'happyrider_shortcodes_is_used' ) ) {
	function happyrider_shortcodes_is_used() {
		return happyrider_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| happyrider_vc_is_frontend();															// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'happyrider_shortcodes_width' ) ) {
	function happyrider_shortcodes_width($w="") {
		return array(
			"title" => __("Width", "happyrider"),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'happyrider_shortcodes_height' ) ) {
	function happyrider_shortcodes_height($h='') {
		return array(
			"title" => __("Height", "happyrider"),
			"desc" => __("Width (in pixels or percent) and height (only in pixels) of element", "happyrider"),
			"value" => $h,
			"type" => "text"
		);
	}
}

/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'happyrider_shortcodes_settings_theme_setup' ) ) {
//	if ( happyrider_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'happyrider_action_before_init_theme', 'happyrider_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'happyrider_action_after_init_theme', 'happyrider_shortcodes_settings_theme_setup' );
	function happyrider_shortcodes_settings_theme_setup() {
		if (happyrider_shortcodes_is_used()) {
			global $HAPPYRIDER_GLOBALS;

			// Prepare arrays 
			$HAPPYRIDER_GLOBALS['sc_params'] = array(
			
				// Current element id
				'id' => array(
					"title" => __("Element ID", "happyrider"),
					"desc" => __("ID for current element", "happyrider"),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => __("Element CSS class", "happyrider"),
					"desc" => __("CSS class for current element (optional)", "happyrider"),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => __("CSS styles", "happyrider"),
					"desc" => __("Any additional CSS rules (if need)", "happyrider"),
					"value" => "",
					"type" => "text"
				),
			
				// Margins params
				'top' => array(
					"title" => __("Top margin", "happyrider"),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				'bottom' => array(
					"title" => __("Bottom margin", "happyrider"),
					"value" => "",
					"type" => "text"
				),
			
				'left' => array(
					"title" => __("Left margin", "happyrider"),
					"value" => "",
					"type" => "text"
				),
			
				'right' => array(
					"title" => __("Right margin", "happyrider"),
					"desc" => __("Margins around list (in pixels).", "happyrider"),
					"value" => "",
					"type" => "text"
				),
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> __('Unordered', 'happyrider'),
					'ol'	=> __('Ordered', 'happyrider'),
					'iconed'=> __('Iconed', 'happyrider')
				),
				'yes_no'	=> happyrider_get_list_yesno(),
				'on_off'	=> happyrider_get_list_onoff(),
				'dir' 		=> happyrider_get_list_directions(),
				'align'		=> happyrider_get_list_alignments(),
				'float'		=> happyrider_get_list_floats(),
				'show_hide'	=> happyrider_get_list_showhide(),
				'sorting' 	=> happyrider_get_list_sortings(),
				'ordering' 	=> happyrider_get_list_orderings(),
				'shapes'	=> happyrider_get_list_shapes(),
				'sizes'		=> happyrider_get_list_sizes(),
				'sliders'	=> happyrider_get_list_sliders(),
				'revo_sliders' => happyrider_get_list_revo_sliders(),
				'categories'=> happyrider_get_list_categories(),
				'columns'	=> happyrider_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), happyrider_get_list_files("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), happyrider_get_list_icons()),
				'locations'	=> happyrider_get_list_dedicated_locations(),
				'filters'	=> happyrider_get_list_portfolio_filters(),
				'formats'	=> happyrider_get_list_post_formats_filters(),
				'hovers'	=> happyrider_get_list_hovers(true),
				'hovers_dir'=> happyrider_get_list_hovers_directions(true),
				'schemes'	=> happyrider_get_list_color_schemes(true),
				'animations'=> happyrider_get_list_animations_in(),
				'blogger_styles'	=> happyrider_get_list_templates_blogger(),
				'posts_types'		=> happyrider_get_list_posts_types(),
				'googlemap_styles'	=> happyrider_get_list_googlemap_styles(),
				'field_types'		=> happyrider_get_list_field_types(),
				'label_positions'	=> happyrider_get_list_label_positions()
			);

			$HAPPYRIDER_GLOBALS['sc_params']['animation'] = array(
				"title" => __("Animation",  'happyrider'),
				"desc" => __('Select animation while object enter in the visible area of page',  'happyrider'),
				"value" => "none",
				"type" => "select",
				"options" => $HAPPYRIDER_GLOBALS['sc_params']['animations']
			);
	
			// Shortcodes list
			//------------------------------------------------------------------
			$HAPPYRIDER_GLOBALS['shortcodes'] = array(
			
				// Accordion
				"trx_accordion" => array(
					"title" => __("Accordion", "happyrider"),
					"desc" => __("Accordion items", "happyrider"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Accordion style", "happyrider"),
							"desc" => __("Select style for display accordion", "happyrider"),
							"value" => 1,
							"options" => happyrider_get_list_styles(1, 2),
							"type" => "radio"
						),
						"counter" => array(
							"title" => __("Counter", "happyrider"),
							"desc" => __("Display counter before each accordion title", "happyrider"),
							"value" => "off",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['on_off']
						),
						"initial" => array(
							"title" => __("Initially opened item", "happyrider"),
							"desc" => __("Number of initially opened item", "happyrider"),
							"value" => 1,
							"min" => 0,
							"type" => "spinner"
						),
						"icon_closed" => array(
							"title" => __("Icon while closed",  'happyrider'),
							"desc" => __('Select icon for the closed accordion item from Fontello icons set',  'happyrider'),
							"value" => "",
							"type" => "icons",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
						),
						"icon_opened" => array(
							"title" => __("Icon while opened",  'happyrider'),
							"desc" => __('Select icon for the opened accordion item from Fontello icons set',  'happyrider'),
							"value" => "",
							"type" => "icons",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
						),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_accordion_item",
						"title" => __("Item", "happyrider"),
						"desc" => __("Accordion item", "happyrider"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => __("Accordion item title", "happyrider"),
								"desc" => __("Title for current accordion item", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"icon_closed" => array(
								"title" => __("Icon while closed",  'happyrider'),
								"desc" => __('Select icon for the closed accordion item from Fontello icons set',  'happyrider'),
								"value" => "",
								"type" => "icons",
								"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
							),
							"icon_opened" => array(
								"title" => __("Icon while opened",  'happyrider'),
								"desc" => __('Select icon for the opened accordion item from Fontello icons set',  'happyrider'),
								"value" => "",
								"type" => "icons",
								"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
							),
							"_content_" => array(
								"title" => __("Accordion item content", "happyrider"),
								"desc" => __("Current accordion item content", "happyrider"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
							"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
							"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Anchor
				"trx_anchor" => array(
					"title" => __("Anchor", "happyrider"),
					"desc" => __("Insert anchor for the TOC (table of content)", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"icon" => array(
							"title" => __("Anchor's icon",  'happyrider'),
							"desc" => __('Select icon for the anchor from Fontello icons set',  'happyrider'),
							"value" => "",
							"type" => "icons",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
						),
						"title" => array(
							"title" => __("Short title", "happyrider"),
							"desc" => __("Short title of the anchor (for the table of content)", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => __("Long description", "happyrider"),
							"desc" => __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"url" => array(
							"title" => __("External URL", "happyrider"),
							"desc" => __("External URL for this TOC item", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"separator" => array(
							"title" => __("Add separator", "happyrider"),
							"desc" => __("Add separator under item in the TOC", "happyrider"),
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id']
					)
				),
			
			
				// Audio
				"trx_audio" => array(
					"title" => __("Audio", "happyrider"),
					"desc" => __("Insert audio player", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => __("URL for audio file", "happyrider"),
							"desc" => __("URL for audio file", "happyrider"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => __('Choose audio', 'happyrider'),
								'action' => 'media_upload',
								'type' => 'audio',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => __('Choose audio file', 'happyrider'),
									'update' => __('Select audio file', 'happyrider')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"image" => array(
							"title" => __("Cover image", "happyrider"),
							"desc" => __("Select or upload image or write URL from other site for audio cover", "happyrider"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"title" => array(
							"title" => __("Title", "happyrider"),
							"desc" => __("Title of the audio file", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"author" => array(
							"title" => __("Author", "happyrider"),
							"desc" => __("Author of the audio file", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"controls" => array(
							"title" => __("Show controls", "happyrider"),
							"desc" => __("Show controls in audio player", "happyrider"),
							"divider" => true,
							"size" => "medium",
							"value" => "show",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['show_hide']
						),
						"autoplay" => array(
							"title" => __("Autoplay audio", "happyrider"),
							"desc" => __("Autoplay audio on page load", "happyrider"),
							"value" => "off",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['on_off']
						),
						"align" => array(
							"title" => __("Align", "happyrider"),
							"desc" => __("Select block alignment", "happyrider"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Block
				"trx_block" => array(
					"title" => __("Block container", "happyrider"),
					"desc" => __("Container for any block ([section] analog - to enable nesting)", "happyrider"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"dedicated" => array(
							"title" => __("Dedicated", "happyrider"),
							"desc" => __("Use this block as dedicated content - show it before post title on single page", "happyrider"),
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => __("Align", "happyrider"),
							"desc" => __("Select block alignment", "happyrider"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						),
						"columns" => array(
							"title" => __("Columns emulation", "happyrider"),
							"desc" => __("Select width for columns emulation", "happyrider"),
							"value" => "none",
							"type" => "checklist",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['columns']
						), 
						"pan" => array(
							"title" => __("Use pan effect", "happyrider"),
							"desc" => __("Use pan effect to show section content", "happyrider"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"scroll" => array(
							"title" => __("Use scroller", "happyrider"),
							"desc" => __("Use scroller to show section content", "happyrider"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"scroll_dir" => array(
							"title" => __("Scroll direction", "happyrider"),
							"desc" => __("Scroll direction (if Use scroller = yes)", "happyrider"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['dir']
						),
						"scroll_controls" => array(
							"title" => __("Scroll controls", "happyrider"),
							"desc" => __("Show scroll controls (if Use scroller = yes)", "happyrider"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"scheme" => array(
							"title" => __("Color scheme", "happyrider"),
							"desc" => __("Select color scheme for this block", "happyrider"),
							"value" => "",
							"type" => "checklist",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['schemes']
						),
						"color" => array(
							"title" => __("Fore color", "happyrider"),
							"desc" => __("Any color for objects in this section", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => __("Background color", "happyrider"),
							"desc" => __("Any background color for this section", "happyrider"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => __("Background image URL", "happyrider"),
							"desc" => __("Select or upload image or write URL from other site for the background", "happyrider"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_tile" => array(
							"title" => __("Tile background image", "happyrider"),
							"desc" => __("Do you want tile background image or image cover whole block?", "happyrider"),
							"value" => "no",
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"bg_overlay" => array(
							"title" => __("Overlay", "happyrider"),
							"desc" => __("Overlay color opacity (from 0.0 to 1.0)", "happyrider"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => __("Texture", "happyrider"),
							"desc" => __("Predefined texture style from 1 to 11. 0 - without texture.", "happyrider"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"font_size" => array(
							"title" => __("Font size", "happyrider"),
							"desc" => __("Font size of the text (default - in pixels, allows any CSS units of measure)", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => __("Font weight", "happyrider"),
							"desc" => __("Font weight of the text", "happyrider"),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => __('Thin (100)', 'happyrider'),
								'300' => __('Light (300)', 'happyrider'),
								'400' => __('Normal (400)', 'happyrider'),
								'700' => __('Bold (700)', 'happyrider')
							)
						),
						"_content_" => array(
							"title" => __("Container content", "happyrider"),
							"desc" => __("Content for section container", "happyrider"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Blogger
				"trx_blogger" => array(
					"title" => __("Blogger", "happyrider"),
					"desc" => __("Insert posts (pages) in many styles from desired categories or directly from ids", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => __("Title", "happyrider"),
							"desc" => __("Title for the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => __("Subtitle", "happyrider"),
							"desc" => __("Subtitle for the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => __("Description", "happyrider"),
							"desc" => __("Short description for the block", "happyrider"),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => __("Posts output style", "happyrider"),
							"desc" => __("Select desired style for posts output", "happyrider"),
							"value" => "regular",
							"type" => "select",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['blogger_styles']
						),
						"filters" => array(
							"title" => __("Show filters", "happyrider"),
							"desc" => __("Use post's tags or categories as filter buttons", "happyrider"),
							"value" => "no",
							"dir" => "horizontal",
							"type" => "checklist",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['filters']
						),
						"hover" => array(
							"title" => __("Hover effect", "happyrider"),
							"desc" => __("Select hover effect (only if style=Portfolio)", "happyrider"),
							"dependency" => array(
								'style' => array('portfolio','grid','square','short','colored')
							),
							"value" => "",
							"type" => "select",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['hovers']
						),
						"hover_dir" => array(
							"title" => __("Hover direction", "happyrider"),
							"desc" => __("Select hover direction (only if style=Portfolio and hover=Circle|Square)", "happyrider"),
							"dependency" => array(
								'style' => array('portfolio','grid','square','short','colored'),
								'hover' => array('square','circle')
							),
							"value" => "left_to_right",
							"type" => "select",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['hovers_dir']
						),
						"dir" => array(
							"title" => __("Posts direction", "happyrider"),
							"desc" => __("Display posts in horizontal or vertical direction", "happyrider"),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['dir']
						),
						"post_type" => array(
							"title" => __("Post type", "happyrider"),
							"desc" => __("Select post type to show", "happyrider"),
							"value" => "post",
							"type" => "select",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['posts_types']
						),
						"ids" => array(
							"title" => __("Post IDs list", "happyrider"),
							"desc" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"cat" => array(
							"title" => __("Categories list", "happyrider"),
							"desc" => __("Select the desired categories. If not selected - show posts from any category or from IDs list", "happyrider"),
							"dependency" => array(
								'ids' => array('is_empty'),
								'post_type' => array('refresh')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => happyrider_array_merge(array(0 => __('- Select category -', 'happyrider')), $HAPPYRIDER_GLOBALS['sc_params']['categories'])
						),
						"count" => array(
							"title" => __("Total posts to show", "happyrider"),
							"desc" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "happyrider"),
							"dependency" => array(
								'ids' => array('is_empty')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns number", "happyrider"),
							"desc" => __("How many columns used to show posts? If empty or 0 - equal to posts number", "happyrider"),
							"dependency" => array(
								'dir' => array('horizontal')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => __("Offset before select posts", "happyrider"),
							"desc" => __("Skip posts before select next part.", "happyrider"),
							"dependency" => array(
								'ids' => array('is_empty')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Post order by", "happyrider"),
							"desc" => __("Select desired posts sorting method", "happyrider"),
							"value" => "date",
							"type" => "select",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => __("Post order", "happyrider"),
							"desc" => __("Select desired posts order", "happyrider"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['ordering']
						),
						"only" => array(
							"title" => __("Select posts only", "happyrider"),
							"desc" => __("Select posts only with reviews, videos, audios, thumbs or galleries", "happyrider"),
							"value" => "no",
							"type" => "select",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['formats']
						),
						"scroll" => array(
							"title" => __("Use scroller", "happyrider"),
							"desc" => __("Use scroller to show all posts", "happyrider"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"controls" => array(
							"title" => __("Show slider controls", "happyrider"),
							"desc" => __("Show arrows to control scroll slider", "happyrider"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"location" => array(
							"title" => __("Dedicated content location", "happyrider"),
							"desc" => __("Select position for dedicated content (only for style=excerpt)", "happyrider"),
							"divider" => true,
							"dependency" => array(
								'style' => array('excerpt')
							),
							"value" => "default",
							"type" => "select",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['locations']
						),
						"rating" => array(
							"title" => __("Show rating stars", "happyrider"),
							"desc" => __("Show rating stars under post's header", "happyrider"),
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"info" => array(
							"title" => __("Show post info block", "happyrider"),
							"desc" => __("Show post info block (author, date, tags, etc.)", "happyrider"),
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"links" => array(
							"title" => __("Allow links on the post", "happyrider"),
							"desc" => __("Allow links on the post from each blogger item", "happyrider"),
							"value" => "yes",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"descr" => array(
							"title" => __("Description length", "happyrider"),
							"desc" => __("How many characters are displayed from post excerpt? If 0 - don't show description", "happyrider"),
							"value" => 0,
							"min" => 0,
							"step" => 10,
							"type" => "spinner"
						),
						"readmore" => array(
							"title" => __("More link text", "happyrider"),
							"desc" => __("Read more link text. If empty - show 'More', else - used as link text", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => __("Button URL", "happyrider"),
							"desc" => __("Link URL for the button at the bottom of the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => __("Button caption", "happyrider"),
							"desc" => __("Caption for the button at the bottom of the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Br
				"trx_br" => array(
					"title" => __("Break", "happyrider"),
					"desc" => __("Line break with clear floating (if need)", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"clear" => 	array(
							"title" => __("Clear floating", "happyrider"),
							"desc" => __("Clear floating (if need)", "happyrider"),
							"value" => "",
							"type" => "checklist",
							"options" => array(
								'none' => __('None', 'happyrider'),
								'left' => __('Left', 'happyrider'),
								'right' => __('Right', 'happyrider'),
								'both' => __('Both', 'happyrider')
							)
						)
					)
				),
			
			
			
			
				// Button
				"trx_button" => array(
					"title" => __("Button", "happyrider"),
					"desc" => __("Button with link", "happyrider"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => __("Caption", "happyrider"),
							"desc" => __("Button caption", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"type" => array(
							"title" => __("Button's shape", "happyrider"),
							"desc" => __("Select button's shape", "happyrider"),
							"value" => "square",
							"size" => "medium",
							"options" => array(
								'square' => __('Square', 'happyrider'),
								'round' => __('Round', 'happyrider')
							),
							"type" => "switch"
						), 
						"style" => array(
							"title" => __("Button's style", "happyrider"),
							"desc" => __("Select button's style", "happyrider"),
							"value" => "default",
							"dir" => "horizontal",
							"options" => array(
								'filled' => __('Filled', 'happyrider'),
								'border' => __('Border', 'happyrider')
							),
							"type" => "checklist"
						), 
						"size" => array(
							"title" => __("Button's size", "happyrider"),
							"desc" => __("Select button's size", "happyrider"),
							"value" => "small",
							"dir" => "horizontal",
							"options" => array(
								'small' => __('Small', 'happyrider'),
								'medium' => __('Medium', 'happyrider'),
								'large' => __('Large', 'happyrider')
							),
							"type" => "checklist"
						), 
						"icon" => array(
							"title" => __("Button's icon",  'happyrider'),
							"desc" => __('Select icon for the title from Fontello icons set',  'happyrider'),
							"value" => "",
							"type" => "icons",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
						),
						"color" => array(
							"title" => __("Button's text color", "happyrider"),
							"desc" => __("Any color for button's caption", "happyrider"),
							"std" => "",
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => __("Button's backcolor", "happyrider"),
							"desc" => __("Any color for button's background", "happyrider"),
							"value" => "",
							"type" => "color"
						),
						"align" => array(
							"title" => __("Button's alignment", "happyrider"),
							"desc" => __("Align button to left, center or right", "happyrider"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						), 
						"link" => array(
							"title" => __("Link URL", "happyrider"),
							"desc" => __("URL for link on button click", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"target" => array(
							"title" => __("Link target", "happyrider"),
							"desc" => __("Target for link on button click", "happyrider"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"popup" => array(
							"title" => __("Open link in popup", "happyrider"),
							"desc" => __("Open link target in popup window", "happyrider"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						), 
						"rel" => array(
							"title" => __("Rel attribute", "happyrider"),
							"desc" => __("Rel attribute for button's link (if need)", "happyrider"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),




				// Call to Action block
				"trx_call_to_action" => array(
					"title" => __("Call to action", "happyrider"),
					"desc" => __("Insert call to action block in your page (post)", "happyrider"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => __("Title", "happyrider"),
							"desc" => __("Title for the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => __("Subtitle", "happyrider"),
							"desc" => __("Subtitle for the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => __("Description", "happyrider"),
							"desc" => __("Short description for the block", "happyrider"),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => __("Style", "happyrider"),
							"desc" => __("Select style to display block", "happyrider"),
							"value" => "1",
							"type" => "checklist",
							"options" => happyrider_get_list_styles(1, 2)
						),
						"align" => array(
							"title" => __("Alignment", "happyrider"),
							"desc" => __("Alignment elements in the block", "happyrider"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						),
						"accent" => array(
							"title" => __("Accented", "happyrider"),
							"desc" => __("Fill entire block with Accent1 color from current color scheme", "happyrider"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"custom" => array(
							"title" => __("Custom", "happyrider"),
							"desc" => __("Allow get featured image or video from inner shortcodes (custom) or get it from shortcode parameters below", "happyrider"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"image" => array(
							"title" => __("Image", "happyrider"),
							"desc" => __("Select or upload image or write URL from other site to include image into this block", "happyrider"),
							"divider" => true,
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"video" => array(
							"title" => __("URL for video file", "happyrider"),
							"desc" => __("Select video from media library or paste URL for video file from other site to include video into this block", "happyrider"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => __('Choose video', 'happyrider'),
								'action' => 'media_upload',
								'type' => 'video',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => __('Choose video file', 'happyrider'),
									'update' => __('Select video file', 'happyrider')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"link" => array(
							"title" => __("Button URL", "happyrider"),
							"desc" => __("Link URL for the button at the bottom of the block", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => __("Button caption", "happyrider"),
							"desc" => __("Caption for the button at the bottom of the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"link2" => array(
							"title" => __("Button 2 URL", "happyrider"),
							"desc" => __("Link URL for the second button at the bottom of the block", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"link2_caption" => array(
							"title" => __("Button 2 caption", "happyrider"),
							"desc" => __("Caption for the second button at the bottom of the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Chat
				"trx_chat" => array(
					"title" => __("Chat", "happyrider"),
					"desc" => __("Chat message", "happyrider"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => __("Item title", "happyrider"),
							"desc" => __("Chat item title", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"photo" => array(
							"title" => __("Item photo", "happyrider"),
							"desc" => __("Select or upload image or write URL from other site for the item photo (avatar)", "happyrider"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"link" => array(
							"title" => __("Item link", "happyrider"),
							"desc" => __("Chat item link", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => __("Chat item content", "happyrider"),
							"desc" => __("Current chat item content", "happyrider"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Columns
				"trx_columns" => array(
					"title" => __("Columns", "happyrider"),
					"desc" => __("Insert up to 5 columns in your page (post)", "happyrider"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"fluid" => array(
							"title" => __("Fluid columns", "happyrider"),
							"desc" => __("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", "happyrider"),
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						), 
						"margins" => array(
							"title" => __("Margins between columns", "happyrider"),
							"desc" => __("Add margins between columns", "happyrider"),
							"value" => "yes",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						), 
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_column_item",
						"title" => __("Column", "happyrider"),
						"desc" => __("Column item", "happyrider"),
						"container" => true,
						"params" => array(
							"span" => array(
								"title" => __("Merge columns", "happyrider"),
								"desc" => __("Count merged columns from current", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"align" => array(
								"title" => __("Alignment", "happyrider"),
								"desc" => __("Alignment text in the column", "happyrider"),
								"value" => "",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
							),
							"color" => array(
								"title" => __("Fore color", "happyrider"),
								"desc" => __("Any color for objects in this column", "happyrider"),
								"value" => "",
								"type" => "color"
							),
							"bg_color" => array(
								"title" => __("Background color", "happyrider"),
								"desc" => __("Any background color for this column", "happyrider"),
								"value" => "",
								"type" => "color"
							),
							"bg_image" => array(
								"title" => __("URL for background image file", "happyrider"),
								"desc" => __("Select or upload image or write URL from other site for the background", "happyrider"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),
							"bg_tile" => array(
								"title" => __("Tile background image", "happyrider"),
								"desc" => __("Do you want tile background image or image cover whole column?", "happyrider"),
								"value" => "no",
								"dependency" => array(
									'bg_image' => array('not_empty')
								),
								"type" => "switch",
								"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
							),
							"_content_" => array(
								"title" => __("Column item content", "happyrider"),
								"desc" => __("Current column item content", "happyrider"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
							"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
							"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
							"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Contact form
				"trx_contact_form" => array(
					"title" => __("Contact form", "happyrider"),
					"desc" => __("Insert contact form", "happyrider"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => __("Title", "happyrider"),
							"desc" => __("Title for the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => __("Subtitle", "happyrider"),
							"desc" => __("Subtitle for the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => __("Description", "happyrider"),
							"desc" => __("Short description for the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"style" => array(
							"title" => __("Style", "happyrider"),
							"desc" => __("Select style of the contact form", "happyrider"),
							"value" => 1,
							"options" => happyrider_get_list_styles(1, 2),
							"type" => "checklist"
						), 
						"scheme" => array(
							"title" => __("Color scheme", "happyrider"),
							"desc" => __("Select color scheme for this block", "happyrider"),
							"value" => "",
							"type" => "checklist",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['schemes']
						),
						"custom" => array(
							"title" => __("Custom", "happyrider"),
							"desc" => __("Use custom fields or create standard contact form (ignore info from 'Field' tabs)", "happyrider"),
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						), 
						"action" => array(
							"title" => __("Action", "happyrider"),
							"desc" => __("Contact form action (URL to handle form data). If empty - use internal action", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => __("Align", "happyrider"),
							"desc" => __("Select form alignment", "happyrider"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						),
						"width" => happyrider_shortcodes_width(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_form_item",
						"title" => __("Field", "happyrider"),
						"desc" => __("Custom field", "happyrider"),
						"container" => false,
						"params" => array(
							"type" => array(
								"title" => __("Type", "happyrider"),
								"desc" => __("Type of the custom field", "happyrider"),
								"value" => "text",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $HAPPYRIDER_GLOBALS['sc_params']['field_types']
							), 
							"name" => array(
								"title" => __("Name", "happyrider"),
								"desc" => __("Name of the custom field", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"value" => array(
								"title" => __("Default value", "happyrider"),
								"desc" => __("Default value of the custom field", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"options" => array(
								"title" => __("Options", "happyrider"),
								"desc" => __("Field options. For example: big=My daddy|middle=My brother|small=My little sister", "happyrider"),
								"dependency" => array(
									'type' => array('radio', 'checkbox', 'select')
								),
								"value" => "",
								"type" => "text"
							),
							"label" => array(
								"title" => __("Label", "happyrider"),
								"desc" => __("Label for the custom field", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"label_position" => array(
								"title" => __("Label position", "happyrider"),
								"desc" => __("Label position relative to the field", "happyrider"),
								"value" => "top",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $HAPPYRIDER_GLOBALS['sc_params']['label_positions']
							), 
							"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
							"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
							"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
							"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
							"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
							"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
							"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
							"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Content block on fullscreen page
				"trx_content" => array(
					"title" => __("Content block", "happyrider"),
					"desc" => __("Container for main content block with desired class and style (use it only on fullscreen pages)", "happyrider"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"scheme" => array(
							"title" => __("Color scheme", "happyrider"),
							"desc" => __("Select color scheme for this block", "happyrider"),
							"value" => "",
							"type" => "checklist",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['schemes']
						),
						"_content_" => array(
							"title" => __("Container content", "happyrider"),
							"desc" => __("Content for section container", "happyrider"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Countdown
				"trx_countdown" => array(
					"title" => __("Countdown", "happyrider"),
					"desc" => __("Insert countdown object", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"date" => array(
							"title" => __("Date", "happyrider"),
							"desc" => __("Upcoming date (format: yyyy-mm-dd)", "happyrider"),
							"value" => "",
							"format" => "yy-mm-dd",
							"type" => "date"
						),
						"time" => array(
							"title" => __("Time", "happyrider"),
							"desc" => __("Upcoming time (format: HH:mm:ss)", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"style" => array(
							"title" => __("Style", "happyrider"),
							"desc" => __("Countdown style", "happyrider"),
							"value" => "1",
							"type" => "checklist",
							"options" => happyrider_get_list_styles(1, 2)
						),
						"align" => array(
							"title" => __("Alignment", "happyrider"),
							"desc" => __("Align counter to left, center or right", "happyrider"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						), 
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Dropcaps
				"trx_dropcaps" => array(
					"title" => __("Dropcaps", "happyrider"),
					"desc" => __("Make first letter as dropcaps", "happyrider"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"style" => array(
							"title" => __("Style", "happyrider"),
							"desc" => __("Dropcaps style", "happyrider"),
							"value" => "1",
							"type" => "checklist",
							"options" => happyrider_get_list_styles(1, 4)
						),
						"_content_" => array(
							"title" => __("Paragraph content", "happyrider"),
							"desc" => __("Paragraph with dropcaps content", "happyrider"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Emailer
				"trx_emailer" => array(
					"title" => __("E-mail collector", "happyrider"),
					"desc" => __("Collect the e-mail address into specified group", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"group" => array(
							"title" => __("Group", "happyrider"),
							"desc" => __("The name of group to collect e-mail address", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"open" => array(
							"title" => __("Open", "happyrider"),
							"desc" => __("Initially open the input field on show object", "happyrider"),
							"divider" => true,
							"value" => "yes",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => __("Alignment", "happyrider"),
							"desc" => __("Align object to left, center or right", "happyrider"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						), 
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Gap
				"trx_gap" => array(
					"title" => __("Gap", "happyrider"),
					"desc" => __("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", "happyrider"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => __("Gap content", "happyrider"),
							"desc" => __("Gap inner content", "happyrider"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						)
					)
				),
			
			
			
			
			
				// Google map
				"trx_googlemap" => array(
					"title" => __("Google map", "happyrider"),
					"desc" => __("Insert Google map with specified markers", "happyrider"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"zoom" => array(
							"title" => __("Zoom", "happyrider"),
							"desc" => __("Map zoom factor", "happyrider"),
							"divider" => true,
							"value" => 16,
							"min" => 1,
							"max" => 20,
							"type" => "spinner"
						),
						"style" => array(
							"title" => __("Map style", "happyrider"),
							"desc" => __("Select map style", "happyrider"),
							"value" => "default",
							"type" => "checklist",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['googlemap_styles']
							),
						"show_info" => array(
							"title" => __("Hide info", "happyrider"),
							"desc" => __("Hide info over google map(default is show)", "happyrider"),
							"value" => "yes",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
							),
						"width" => happyrider_shortcodes_width('100%'),
						"height" => happyrider_shortcodes_height(240),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_googlemap_marker",
						"title" => __("Google map marker", "happyrider"),
						"desc" => __("Google map marker", "happyrider"),
						"decorate" => false,
						"container" => true,
						"params" => array(
							"address" => array(
								"title" => __("Address", "happyrider"),
								"desc" => __("Address of this marker", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"latlng" => array(
								"title" => __("Latitude and Longtitude", "happyrider"),
								"desc" => __("Comma separated marker's coorditanes (instead Address)", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"point" => array(
								"title" => __("URL for marker image file", "happyrider"),
								"desc" => __("Select or upload image or write URL from other site for this marker. If empty - use default marker", "happyrider"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),
							"title" => array(
								"title" => __("Title", "happyrider"),
								"desc" => __("Title for this marker", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => __("Description", "happyrider"),
								"desc" => __("Description for this marker", "happyrider"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $HAPPYRIDER_GLOBALS['sc_params']['id']
						)
					)
				),
			
			
			
				// Hide or show any block
				"trx_hide" => array(
					"title" => __("Hide/Show any block", "happyrider"),
					"desc" => __("Hide or Show any block with desired CSS-selector", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"selector" => array(
							"title" => __("Selector", "happyrider"),
							"desc" => __("Any block's CSS-selector", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"hide" => array(
							"title" => __("Hide or Show", "happyrider"),
							"desc" => __("New state for the block: hide or show", "happyrider"),
							"value" => "yes",
							"size" => "small",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						)
					)
				),
			
			
			
				// Highlght text
				"trx_highlight" => array(
					"title" => __("Highlight text", "happyrider"),
					"desc" => __("Highlight text with selected color, background color and other styles", "happyrider"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"type" => array(
							"title" => __("Type", "happyrider"),
							"desc" => __("Highlight type", "happyrider"),
							"value" => "1",
							"type" => "checklist",
							"options" => array(
								0 => __('Custom', 'happyrider'),
								1 => __('Type 1', 'happyrider'),
								2 => __('Type 2', 'happyrider'),
								3 => __('Type 3', 'happyrider'),
								4 => __('Type 4', 'happyrider')
							)
						),
						"color" => array(
							"title" => __("Color", "happyrider"),
							"desc" => __("Color for the highlighted text", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => __("Background color", "happyrider"),
							"desc" => __("Background color for the highlighted text", "happyrider"),
							"value" => "",
							"type" => "color"
						),
						"font_size" => array(
							"title" => __("Font size", "happyrider"),
							"desc" => __("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => __("Highlighting content", "happyrider"),
							"desc" => __("Content for highlight", "happyrider"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Icon
				"trx_icon" => array(
					"title" => __("Icon", "happyrider"),
					"desc" => __("Insert icon", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"icon" => array(
							"title" => __('Icon',  'happyrider'),
							"desc" => __('Select font icon from the Fontello icons set',  'happyrider'),
							"value" => "",
							"type" => "icons",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
						),
						"color" => array(
							"title" => __("Icon's color", "happyrider"),
							"desc" => __("Icon's color", "happyrider"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "color"
						),
						"bg_shape" => array(
							"title" => __("Background shape", "happyrider"),
							"desc" => __("Shape of the icon background", "happyrider"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "none",
							"type" => "radio",
							"options" => array(
								'none' => __('None', 'happyrider'),
								'round' => __('Round', 'happyrider'),
								'square' => __('Square', 'happyrider')
							)
						),
						"bg_color" => array(
							"title" => __("Icon's background color", "happyrider"),
							"desc" => __("Icon's background color", "happyrider"),
							"dependency" => array(
								'icon' => array('not_empty'),
								'background' => array('round','square')
							),
							"value" => "",
							"type" => "color"
						),
						"font_size" => array(
							"title" => __("Font size", "happyrider"),
							"desc" => __("Icon's font size", "happyrider"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "spinner",
							"min" => 8,
							"max" => 240
						),
						"font_weight" => array(
							"title" => __("Font weight", "happyrider"),
							"desc" => __("Icon font weight", "happyrider"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => __('Thin (100)', 'happyrider'),
								'300' => __('Light (300)', 'happyrider'),
								'400' => __('Normal (400)', 'happyrider'),
								'700' => __('Bold (700)', 'happyrider')
							)
						),
						"align" => array(
							"title" => __("Alignment", "happyrider"),
							"desc" => __("Icon text alignment", "happyrider"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						), 
						"link" => array(
							"title" => __("Link URL", "happyrider"),
							"desc" => __("Link URL from this icon (if not empty)", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Image
				"trx_image" => array(
					"title" => __("Image", "happyrider"),
					"desc" => __("Insert image into your post (page)", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => __("URL for image file", "happyrider"),
							"desc" => __("Select or upload image or write URL from other site", "happyrider"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'sizes' => true		// If you want allow user select thumb size for image. Otherwise, thumb size is ignored - image fullsize used
							)
						),
						"title" => array(
							"title" => __("Title", "happyrider"),
							"desc" => __("Image title (if need)", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"icon" => array(
							"title" => __("Icon before title",  'happyrider'),
							"desc" => __('Select icon for the title from Fontello icons set',  'happyrider'),
							"value" => "",
							"type" => "icons",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
						),
						"align" => array(
							"title" => __("Float image", "happyrider"),
							"desc" => __("Float image to left or right side", "happyrider"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['float']
						), 
						"shape" => array(
							"title" => __("Image Shape", "happyrider"),
							"desc" => __("Shape of the image: square (rectangle) or round", "happyrider"),
							"value" => "square",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								"square" => __('Square', 'happyrider'),
								"round" => __('Round', 'happyrider')
							)
						), 
						"link" => array(
							"title" => __("Link", "happyrider"),
							"desc" => __("The link URL from the image", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Infobox
				"trx_infobox" => array(
					"title" => __("Infobox", "happyrider"),
					"desc" => __("Insert infobox into your post (page)", "happyrider"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"style" => array(
							"title" => __("Style", "happyrider"),
							"desc" => __("Infobox style", "happyrider"),
							"value" => "regular",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'regular' => __('Regular', 'happyrider'),
								'info' => __('Info', 'happyrider'),
								'success' => __('Success', 'happyrider'),
								'error' => __('Error', 'happyrider'),
								'warning' => __('Warning', 'happyrider')
							)
						),
						"closeable" => array(
							"title" => __("Closeable box", "happyrider"),
							"desc" => __("Create closeable box (with close button)", "happyrider"),
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"icon" => array(
							"title" => __("Custom icon",  'happyrider'),
							"desc" => __('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'happyrider'),
							"value" => "",
							"type" => "icons",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
						),
						"color" => array(
							"title" => __("Text color", "happyrider"),
							"desc" => __("Any color for text and headers", "happyrider"),
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => __("Background color", "happyrider"),
							"desc" => __("Any background color for this infobox", "happyrider"),
							"value" => "",
							"type" => "color"
						),
						"_content_" => array(
							"title" => __("Infobox content", "happyrider"),
							"desc" => __("Content for infobox", "happyrider"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Line
				"trx_line" => array(
					"title" => __("Line", "happyrider"),
					"desc" => __("Insert Line into your post (page)", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Style", "happyrider"),
							"desc" => __("Line style", "happyrider"),
							"value" => "solid",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'solid' => __('Solid', 'happyrider'),
								'dashed' => __('Dashed', 'happyrider'),
								'dotted' => __('Dotted', 'happyrider'),
								'double' => __('Double', 'happyrider')
							)
						),
						"color" => array(
							"title" => __("Color", "happyrider"),
							"desc" => __("Line color", "happyrider"),
							"value" => "",
							"type" => "color"
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// List
				"trx_list" => array(
					"title" => __("List", "happyrider"),
					"desc" => __("List items with specific bullets", "happyrider"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Bullet's style", "happyrider"),
							"desc" => __("Bullet's style for each list item", "happyrider"),
							"value" => "ul",
							"type" => "checklist",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['list_styles']
						),
						"underlined" => array(
                            "title" => __("Line for li", "happyrider"),
                            "desc" => __("Add line for li", "happyrider"),
                            "type" => "checkbox"
                        ), 
						"color" => array(
							"title" => __("Color", "happyrider"),
							"desc" => __("List items color", "happyrider"),
							"value" => "",
							"type" => "color"
						),
						"icon" => array(
							"title" => __('List icon',  'happyrider'),
							"desc" => __("Select list icon from Fontello icons set (only for style=Iconed)",  'happyrider'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "icons",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
						),
						"icon_color" => array(
							"title" => __("Icon color", "happyrider"),
							"desc" => __("List icons color", "happyrider"),
							"value" => "",
							"dependency" => array(
								'style' => array('iconed')
							),
							"type" => "color"
						),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_list_item",
						"title" => __("Item", "happyrider"),
						"desc" => __("List item with specific bullet", "happyrider"),
						"decorate" => false,
						"container" => true,
						"params" => array(
							"_content_" => array(
								"title" => __("List item content", "happyrider"),
								"desc" => __("Current list item content", "happyrider"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"title" => array(
								"title" => __("List item title", "happyrider"),
								"desc" => __("Current list item title (show it as tooltip)", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"color" => array(
								"title" => __("Color", "happyrider"),
								"desc" => __("Text color for this item", "happyrider"),
								"value" => "",
								"type" => "color"
							),
							"icon" => array(
								"title" => __('List icon',  'happyrider'),
								"desc" => __("Select list item icon from Fontello icons set (only for style=Iconed)",  'happyrider'),
								"value" => "",
								"type" => "icons",
								"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
							),
							"icon_color" => array(
								"title" => __("Icon color", "happyrider"),
								"desc" => __("Icon color for this item", "happyrider"),
								"value" => "",
								"type" => "color"
							),
							"link" => array(
								"title" => __("Link URL", "happyrider"),
								"desc" => __("Link URL for the current list item", "happyrider"),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"target" => array(
								"title" => __("Link target", "happyrider"),
								"desc" => __("Link target for the current list item", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
							"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
							"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
				// Number
				"trx_number" => array(
					"title" => __("Number", "happyrider"),
					"desc" => __("Insert number or any word as set separate characters", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"value" => array(
							"title" => __("Value", "happyrider"),
							"desc" => __("Number or any word", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => __("Align", "happyrider"),
							"desc" => __("Select block alignment", "happyrider"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Parallax
				"trx_parallax" => array(
					"title" => __("Parallax", "happyrider"),
					"desc" => __("Create the parallax container (with asinc background image)", "happyrider"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"gap" => array(
							"title" => __("Create gap", "happyrider"),
							"desc" => __("Create gap around parallax container", "happyrider"),
							"value" => "no",
							"size" => "small",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						), 
						"dir" => array(
							"title" => __("Dir", "happyrider"),
							"desc" => __("Scroll direction for the parallax background", "happyrider"),
							"value" => "up",
							"size" => "medium",
							"options" => array(
								'up' => __('Up', 'happyrider'),
								'down' => __('Down', 'happyrider')
							),
							"type" => "switch"
						), 
						"speed" => array(
							"title" => __("Speed", "happyrider"),
							"desc" => __("Image motion speed (from 0.0 to 1.0)", "happyrider"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0.3",
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => __("Color scheme", "happyrider"),
							"desc" => __("Select color scheme for this block", "happyrider"),
							"value" => "",
							"type" => "checklist",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['schemes']
						),
						"color" => array(
							"title" => __("Text color", "happyrider"),
							"desc" => __("Select color for text object inside parallax block", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => __("Background color", "happyrider"),
							"desc" => __("Select color for parallax background", "happyrider"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => __("Background image", "happyrider"),
							"desc" => __("Select or upload image or write URL from other site for the parallax background", "happyrider"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_image_x" => array(
							"title" => __("Image X position", "happyrider"),
							"desc" => __("Image horizontal position (as background of the parallax block) - in percent", "happyrider"),
							"min" => "0",
							"max" => "100",
							"value" => "50",
							"type" => "spinner"
						),
						"bg_video" => array(
							"title" => __("Video background", "happyrider"),
							"desc" => __("Select video from media library or paste URL for video file from other site to show it as parallax background", "happyrider"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => __('Choose video', 'happyrider'),
								'action' => 'media_upload',
								'type' => 'video',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => __('Choose video file', 'happyrider'),
									'update' => __('Select video file', 'happyrider')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"bg_video_ratio" => array(
							"title" => __("Video ratio", "happyrider"),
							"desc" => __("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", "happyrider"),
							"value" => "16:9",
							"type" => "text"
						),
						"bg_overlay" => array(
							"title" => __("Overlay", "happyrider"),
							"desc" => __("Overlay color opacity (from 0.0 to 1.0)", "happyrider"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => __("Texture", "happyrider"),
							"desc" => __("Predefined texture style from 1 to 11. 0 - without texture.", "happyrider"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"_content_" => array(
							"title" => __("Content", "happyrider"),
							"desc" => __("Content for the parallax container", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Popup
				"trx_popup" => array(
					"title" => __("Popup window", "happyrider"),
					"desc" => __("Container for any html-block with desired class and style for popup window", "happyrider"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => __("Container content", "happyrider"),
							"desc" => __("Content for section container", "happyrider"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Price
				"trx_price" => array(
					"title" => __("Price", "happyrider"),
					"desc" => __("Insert price with decoration", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"money" => array(
							"title" => __("Money", "happyrider"),
							"desc" => __("Money value (dot or comma separated)", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"currency" => array(
							"title" => __("Currency", "happyrider"),
							"desc" => __("Currency character", "happyrider"),
							"value" => "$",
							"type" => "text"
						),
						"period" => array(
							"title" => __("Period", "happyrider"),
							"desc" => __("Period text (if need). For example: monthly, daily, etc.", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => __("Alignment", "happyrider"),
							"desc" => __("Align price to left or right side", "happyrider"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['float']
						), 
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Price block
				"trx_price_block" => array(
					"title" => __("Price block", "happyrider"),
					"desc" => __("Insert price block with title, price and description", "happyrider"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => __("Title", "happyrider"),
							"desc" => __("Block title", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => __("Link URL", "happyrider"),
							"desc" => __("URL for link from button (at bottom of the block)", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"link_text" => array(
							"title" => __("Link text", "happyrider"),
							"desc" => __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"custom" => array(
							"title" => __("Custom",  'happyrider'),
							"desc" => __('Add custom bg color',  'happyrider'),
							"value" => "",
							"type" => "colorpicker",
						),
						"icon" => array(
							"title" => __("Icon",  'happyrider'),
							"desc" => __('Select icon from Fontello icons set (placed before/instead price)',  'happyrider'),
							"value" => "",
							"type" => "icons",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
						),
						"money" => array(
							"title" => __("Money", "happyrider"),
							"desc" => __("Money value (dot or comma separated)", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"currency" => array(
							"title" => __("Currency", "happyrider"),
							"desc" => __("Currency character", "happyrider"),
							"value" => "$",
							"type" => "text"
						),
						"period" => array(
							"title" => __("Period", "happyrider"),
							"desc" => __("Period text (if need). For example: monthly, daily, etc.", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"scheme" => array(
							"title" => __("Color scheme", "happyrider"),
							"desc" => __("Select color scheme for this block", "happyrider"),
							"value" => "",
							"type" => "checklist",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['schemes']
						),
						"align" => array(
							"title" => __("Alignment", "happyrider"),
							"desc" => __("Align price to left or right side", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['float']
						), 
						"_content_" => array(
							"title" => __("Description", "happyrider"),
							"desc" => __("Description for this price block", "happyrider"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Quote
				"trx_quote" => array(
					"title" => __("Quote", "happyrider"),
					"desc" => __("Quote text", "happyrider"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"style" => array(
							"title" => __("Style", "happyrider"),
							"desc" => __("Quote style", "happyrider"),
							"admin_label" => true,
                        "class" => "",
							"value" => array_flip(happyrider_get_list_styles(1, 2)),
							"type" => "dropdown"
						), 
						"bg_image" => array(
							"title" => __("Background image", "happyrider"),
							"desc" => __("Select or upload image or write URL from other site for the quote - style 1 background", "happyrider"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"cite" => array(
							"title" => __("Quote cite", "happyrider"),
							"desc" => __("URL for quote cite", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"title" => array(
							"title" => __("Title (author)", "happyrider"),
							"desc" => __("Quote title (author name)", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"author_photo" => array(
							"title" => __("Author photo", "happyrider"),
							"desc" => __("Select or upload image or write URL for author photo", "happyrider"),
							"value" => "",
							"type" => "media"
						),
						"_content_" => array(
							"title" => __("Quote content", "happyrider"),
							"desc" => __("Quote content", "happyrider"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => happyrider_shortcodes_width(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Reviews
				"trx_reviews" => array(
					"title" => __("Reviews", "happyrider"),
					"desc" => __("Insert reviews block in the single post", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"align" => array(
							"title" => __("Alignment", "happyrider"),
							"desc" => __("Align counter to left, center or right", "happyrider"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						), 
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Search
				"trx_search" => array(
					"title" => __("Search", "happyrider"),
					"desc" => __("Show search form", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Style", "happyrider"),
							"desc" => __("Select style to display search field", "happyrider"),
							"value" => "regular",
							"options" => array(
								"regular" => __('Regular', 'happyrider'),
								"rounded" => __('Rounded', 'happyrider')
							),
							"type" => "checklist"
						),
						"state" => array(
							"title" => __("State", "happyrider"),
							"desc" => __("Select search field initial state", "happyrider"),
							"value" => "fixed",
							"options" => array(
								"fixed"  => __('Fixed',  'happyrider'),
								"opened" => __('Opened', 'happyrider'),
								"closed" => __('Closed', 'happyrider')
							),
							"type" => "checklist"
						),
						"title" => array(
							"title" => __("Title", "happyrider"),
							"desc" => __("Title (placeholder) for the search field", "happyrider"),
							"value" => __("Search &hellip;", 'happyrider'),
							"type" => "text"
						),
						"ajax" => array(
							"title" => __("AJAX", "happyrider"),
							"desc" => __("Search via AJAX or reload page", "happyrider"),
							"value" => "yes",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Section
				"trx_section" => array(
					"title" => __("Section container", "happyrider"),
					"desc" => __("Container for any block with desired class and style", "happyrider"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"dedicated" => array(
							"title" => __("Dedicated", "happyrider"),
							"desc" => __("Use this block as dedicated content - show it before post title on single page", "happyrider"),
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => __("Align", "happyrider"),
							"desc" => __("Select block alignment", "happyrider"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						),
						"columns" => array(
							"title" => __("Columns emulation", "happyrider"),
							"desc" => __("Select width for columns emulation", "happyrider"),
							"value" => "none",
							"type" => "checklist",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['columns']
						), 
						"pan" => array(
							"title" => __("Use pan effect", "happyrider"),
							"desc" => __("Use pan effect to show section content", "happyrider"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"scroll" => array(
							"title" => __("Use scroller", "happyrider"),
							"desc" => __("Use scroller to show section content", "happyrider"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"scroll_dir" => array(
							"title" => __("Scroll and Pan direction", "happyrider"),
							"desc" => __("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", "happyrider"),
							"dependency" => array(
								'pan' => array('yes'),
								'scroll' => array('yes')
							),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['dir']
						),
						"scroll_controls" => array(
							"title" => __("Scroll controls", "happyrider"),
							"desc" => __("Show scroll controls (if Use scroller = yes)", "happyrider"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"scheme" => array(
							"title" => __("Color scheme", "happyrider"),
							"desc" => __("Select color scheme for this block", "happyrider"),
							"value" => "",
							"type" => "checklist",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['schemes']
						),
						"color" => array(
							"title" => __("Fore color", "happyrider"),
							"desc" => __("Any color for objects in this section", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => __("Background color", "happyrider"),
							"desc" => __("Any background color for this section", "happyrider"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => __("Background image URL", "happyrider"),
							"desc" => __("Select or upload image or write URL from other site for the background", "happyrider"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_tile" => array(
							"title" => __("Tile background image", "happyrider"),
							"desc" => __("Do you want tile background image or image cover whole block?", "happyrider"),
							"value" => "no",
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"bg_overlay" => array(
							"title" => __("Overlay", "happyrider"),
							"desc" => __("Overlay color opacity (from 0.0 to 1.0)", "happyrider"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => __("Texture", "happyrider"),
							"desc" => __("Predefined texture style from 1 to 11. 0 - without texture.", "happyrider"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"font_size" => array(
							"title" => __("Font size", "happyrider"),
							"desc" => __("Font size of the text (default - in pixels, allows any CSS units of measure)", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => __("Font weight", "happyrider"),
							"desc" => __("Font weight of the text", "happyrider"),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => __('Thin (100)', 'happyrider'),
								'300' => __('Light (300)', 'happyrider'),
								'400' => __('Normal (400)', 'happyrider'),
								'700' => __('Bold (700)', 'happyrider')
							)
						),
						"_content_" => array(
							"title" => __("Container content", "happyrider"),
							"desc" => __("Content for section container", "happyrider"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Skills
				"trx_skills" => array(
					"title" => __("Skills", "happyrider"),
					"desc" => __("Insert skills diagramm in your page (post)", "happyrider"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"max_value" => array(
							"title" => __("Max value", "happyrider"),
							"desc" => __("Max value for skills items", "happyrider"),
							"value" => 100,
							"min" => 1,
							"type" => "spinner"
						),
						"type" => array(
							"title" => __("Skills type", "happyrider"),
							"desc" => __("Select type of skills block", "happyrider"),
							"value" => "bar",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'bar' => __('Bar', 'happyrider'),
								'pie' => __('Pie chart', 'happyrider'),
								'counter' => __('Counter', 'happyrider'),
								'arc' => __('Arc', 'happyrider')
							)
						), 
						"layout" => array(
							"title" => __("Skills layout", "happyrider"),
							"desc" => __("Select layout of skills block", "happyrider"),
							"dependency" => array(
								'type' => array('counter','pie','bar')
							),
							"value" => "rows",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'rows' => __('Rows', 'happyrider'),
								'columns' => __('Columns', 'happyrider')
							)
						),
						"dir" => array(
							"title" => __("Direction", "happyrider"),
							"desc" => __("Select direction of skills block", "happyrider"),
							"dependency" => array(
								'type' => array('counter','pie','bar')
							),
							"value" => "horizontal",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['dir']
						), 
						"style" => array(
							"title" => __("Counters style", "happyrider"),
							"desc" => __("Select style of skills items (only for type=counter)", "happyrider"),
							"dependency" => array(
								'type' => array('counter')
							),
							"value" => 1,
							"options" => happyrider_get_list_styles(1, 4),
							"type" => "checklist"
						), 
						// "columns" - autodetect, not set manual
						"color" => array(
							"title" => __("Skills items color", "happyrider"),
							"desc" => __("Color for all skills items", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => __("Background color", "happyrider"),
							"desc" => __("Background color for all skills items (only for type=pie)", "happyrider"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => "",
							"type" => "color"
						),
						"border_color" => array(
							"title" => __("Border color", "happyrider"),
							"desc" => __("Border color for all skills items (only for type=pie)", "happyrider"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => "",
							"type" => "color"
						),
						"align" => array(
							"title" => __("Align skills block", "happyrider"),
							"desc" => __("Align skills block to left or right side", "happyrider"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['float']
						), 
						"arc_caption" => array(
							"title" => __("Arc Caption", "happyrider"),
							"desc" => __("Arc caption - text in the center of the diagram", "happyrider"),
							"dependency" => array(
								'type' => array('arc')
							),
							"value" => "",
							"type" => "text"
						),
						"pie_compact" => array(
							"title" => __("Pie compact", "happyrider"),
							"desc" => __("Show all skills in one diagram or as separate diagrams", "happyrider"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"pie_cutout" => array(
							"title" => __("Pie cutout", "happyrider"),
							"desc" => __("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", "happyrider"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => 0,
							"min" => 0,
							"max" => 99,
							"type" => "spinner"
						),
						"title" => array(
							"title" => __("Title", "happyrider"),
							"desc" => __("Title for the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => __("Subtitle", "happyrider"),
							"desc" => __("Subtitle for the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => __("Description", "happyrider"),
							"desc" => __("Short description for the block", "happyrider"),
							"value" => "",
							"type" => "textarea"
						),
						"link" => array(
							"title" => __("Button URL", "happyrider"),
							"desc" => __("Link URL for the button at the bottom of the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => __("Button caption", "happyrider"),
							"desc" => __("Caption for the button at the bottom of the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_skills_item",
						"title" => __("Skill", "happyrider"),
						"desc" => __("Skills item", "happyrider"),
						"container" => false,
						"params" => array(
							"title" => array(
								"title" => __("Title", "happyrider"),
								"desc" => __("Current skills item title", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"value" => array(
								"title" => __("Value", "happyrider"),
								"desc" => __("Current skills level", "happyrider"),
								"value" => 50,
								"min" => 0,
								"step" => 1,
								"type" => "spinner"
							),
							"color" => array(
								"title" => __("Color", "happyrider"),
								"desc" => __("Current skills item color", "happyrider"),
								"value" => "",
								"type" => "color"
							),
							"bg_color" => array(
								"title" => __("Background color", "happyrider"),
								"desc" => __("Current skills item background color (only for type=pie)", "happyrider"),
								"value" => "",
								"type" => "color"
							),
							"border_color" => array(
								"title" => __("Border color", "happyrider"),
								"desc" => __("Current skills item border color (only for type=pie)", "happyrider"),
								"value" => "",
								"type" => "color"
							),
							"style" => array(
								"title" => __("Counter style", "happyrider"),
								"desc" => __("Select style for the current skills item (only for type=counter)", "happyrider"),
								"value" => 1,
								"options" => happyrider_get_list_styles(1, 4),
								"type" => "checklist"
							), 
							"icon" => array(
								"title" => __("Counter icon",  'happyrider'),
								"desc" => __('Select icon from Fontello icons set, placed above counter (only for type=counter)',  'happyrider'),
								"value" => "",
								"type" => "icons",
								"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
							),
							"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
							"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
							"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Slider
				"trx_slider" => array(
					"title" => __("Slider", "happyrider"),
					"desc" => __("Insert slider into your post (page)", "happyrider"),
					"decorate" => true,
					"container" => false,
					"params" => array_merge(array(
						"engine" => array(
							"title" => __("Slider engine", "happyrider"),
							"desc" => __("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", "happyrider"),
							"value" => "swiper",
							"type" => "checklist",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['sliders']
						),
						"align" => array(
							"title" => __("Float slider", "happyrider"),
							"desc" => __("Float slider to left or right side", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['float']
						),
						"custom" => array(
							"title" => __("Custom slides", "happyrider"),
							"desc" => __("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", "happyrider"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						)
						),
						happyrider_exists_revslider() ? array(
						"alias" => array(
							"title" => __("Revolution slider alias", "happyrider"),
							"desc" => __("Select Revolution slider to display", "happyrider"),
							"dependency" => array(
								'engine' => array('revo')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['revo_sliders']
						)) : array(), array(
						"cat" => array(
							"title" => __("Swiper: Category list", "happyrider"),
							"desc" => __("Select category to show post's images. If empty - select posts from any category or from IDs list", "happyrider"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => happyrider_array_merge(array(0 => __('- Select category -', 'happyrider')), $HAPPYRIDER_GLOBALS['sc_params']['categories'])
						),
						"count" => array(
							"title" => __("Swiper: Number of posts", "happyrider"),
							"desc" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "happyrider"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => __("Swiper: Offset before select posts", "happyrider"),
							"desc" => __("Skip posts before select next part.", "happyrider"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Swiper: Post order by", "happyrider"),
							"desc" => __("Select desired posts sorting method", "happyrider"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "date",
							"type" => "select",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => __("Swiper: Post order", "happyrider"),
							"desc" => __("Select desired posts order", "happyrider"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => __("Swiper: Post IDs list", "happyrider"),
							"desc" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "happyrider"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "",
							"type" => "text"
						),
						"controls" => array(
							"title" => __("Swiper: Show slider controls", "happyrider"),
							"desc" => __("Show arrows inside slider", "happyrider"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"pagination" => array(
							"title" => __("Swiper: Show slider pagination", "happyrider"),
							"desc" => __("Show bullets for switch slides", "happyrider"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "no",
							"type" => "checklist",
							"options" => array(
								'no'   => __('None', 'happyrider'),
								'yes'  => __('Dots', 'happyrider'),
								'full' => __('Side Titles', 'happyrider'),
								'over' => __('Over Titles', 'happyrider')
							)
						),
						"titles" => array(
							"title" => __("Swiper: Show titles section", "happyrider"),
							"desc" => __("Show section with post's title and short post's description", "happyrider"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "no",
							"type" => "checklist",
							"options" => array(
								"no"    => __('Not show', 'happyrider'),
								"slide" => __('Show/Hide info', 'happyrider'),
								"fixed" => __('Fixed info', 'happyrider')
							)
						),
						"descriptions" => array(
							"title" => __("Swiper: Post descriptions", "happyrider"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"desc" => __("Show post's excerpt max length (characters)", "happyrider"),
							"value" => 0,
							"min" => 0,
							"max" => 1000,
							"step" => 10,
							"type" => "spinner"
						),
						"links" => array(
							"title" => __("Swiper: Post's title as link", "happyrider"),
							"desc" => __("Make links from post's titles", "happyrider"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"crop" => array(
							"title" => __("Swiper: Crop images", "happyrider"),
							"desc" => __("Crop images in each slide or live it unchanged", "happyrider"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"autoheight" => array(
							"title" => __("Swiper: Autoheight", "happyrider"),
							"desc" => __("Change whole slider's height (make it equal current slide's height)", "happyrider"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"slides_per_view" => array(
							"title" => __("Swiper: Slides per view", "happyrider"),
							"desc" => __("Slides per view showed in this slider", "happyrider"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 1,
							"min" => 1,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"slides_space" => array(
							"title" => __("Swiper: Space between slides", "happyrider"),
							"desc" => __("Size of space (in px) between slides", "happyrider"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => __("Swiper: Slides change interval", "happyrider"),
							"desc" => __("Slides change interval (in milliseconds: 1000ms = 1s)", "happyrider"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 5000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)),
					"children" => array(
						"name" => "trx_slider_item",
						"title" => __("Slide", "happyrider"),
						"desc" => __("Slider item", "happyrider"),
						"container" => false,
						"params" => array(
							"src" => array(
								"title" => __("URL (source) for image file", "happyrider"),
								"desc" => __("Select or upload image or write URL from other site for the current slide", "happyrider"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),
							"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
							"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
							"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Socials
				"trx_socials" => array(
					"title" => __("Social icons", "happyrider"),
					"desc" => __("List of social icons (with hovers)", "happyrider"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"type" => array(
							"title" => __("Icon's type", "happyrider"),
							"desc" => __("Type of the icons - images or font icons", "happyrider"),
							"value" => happyrider_get_theme_setting('socials_type'),
							"options" => array(
								'icons' => __('Icons', 'happyrider'),
								'images' => __('Images', 'happyrider')
							),
							"type" => "checklist"
						), 
						"size" => array(
							"title" => __("Icon's size", "happyrider"),
							"desc" => __("Size of the icons", "happyrider"),
							"value" => "small",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['sizes'],
							"type" => "checklist"
						), 
						"shape" => array(
							"title" => __("Icon's shape", "happyrider"),
							"desc" => __("Shape of the icons", "happyrider"),
							"value" => "square",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['shapes'],
							"type" => "checklist"
						), 
						"socials" => array(
							"title" => __("Manual socials list", "happyrider"),
							"desc" => __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebooc.com/my_profile. If empty - use socials from Theme options.", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"custom" => array(
							"title" => __("Custom socials", "happyrider"),
							"desc" => __("Make custom icons from inner shortcodes (prepare it on tabs)", "happyrider"),
							"divider" => true,
							"value" => "no",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_social_item",
						"title" => __("Custom social item", "happyrider"),
						"desc" => __("Custom social item: name, profile url and icon url", "happyrider"),
						"decorate" => false,
						"container" => false,
						"params" => array(
							"name" => array(
								"title" => __("Social name", "happyrider"),
								"desc" => __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"url" => array(
								"title" => __("Your profile URL", "happyrider"),
								"desc" => __("URL of your profile in specified social network", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => __("URL (source) for icon file", "happyrider"),
								"desc" => __("Select or upload image or write URL from other site for the current social icon", "happyrider"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							)
						)
					)
				),
			
			
			
			
				// Table
				"trx_table" => array(
					"title" => __("Table", "happyrider"),
					"desc" => __("Insert a table into post (page). ", "happyrider"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"align" => array(
							"title" => __("Content alignment", "happyrider"),
							"desc" => __("Select alignment for each table cell", "happyrider"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						),
						"_content_" => array(
							"title" => __("Table content", "happyrider"),
							"desc" => __("Content, created with any table-generator", "happyrider"),
							"divider" => true,
							"rows" => 8,
							"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
							"type" => "textarea"
						),
						"width" => happyrider_shortcodes_width(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Tabs
				"trx_tabs" => array(
					"title" => __("Tabs", "happyrider"),
					"desc" => __("Insert tabs in your page (post)", "happyrider"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Tabs style", "happyrider"),
							"desc" => __("Select style for tabs items", "happyrider"),
							"value" => 1,
							"options" => happyrider_get_list_styles(1, 2),
							"type" => "radio"
						),
						"initial" => array(
							"title" => __("Initially opened tab", "happyrider"),
							"desc" => __("Number of initially opened tab", "happyrider"),
							"divider" => true,
							"value" => 1,
							"min" => 0,
							"type" => "spinner"
						),
						"scroll" => array(
							"title" => __("Use scroller", "happyrider"),
							"desc" => __("Use scroller to show tab content (height parameter required)", "happyrider"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_tab",
						"title" => __("Tab", "happyrider"),
						"desc" => __("Tab item", "happyrider"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => __("Tab title", "happyrider"),
								"desc" => __("Current tab title", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => __("Tab content", "happyrider"),
								"desc" => __("Current tab content", "happyrider"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
							"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
							"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
						)
					)
				),
			


				
			
			
				// Title
				"trx_title" => array(
					"title" => __("Title", "happyrider"),
					"desc" => __("Create header tag (1-6 level) with many styles", "happyrider"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => __("Title content", "happyrider"),
							"desc" => __("Title content", "happyrider"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"type" => array(
							"title" => __("Title type", "happyrider"),
							"desc" => __("Title type (header level)", "happyrider"),
							"divider" => true,
							"value" => "1",
							"type" => "select",
							"options" => array(
								'1' => __('Header 1', 'happyrider'),
								'2' => __('Header 2', 'happyrider'),
								'3' => __('Header 3', 'happyrider'),
								'4' => __('Header 4', 'happyrider'),
								'5' => __('Header 5', 'happyrider'),
								'6' => __('Header 6', 'happyrider'),
							)
						),
						"style" => array(
							"title" => __("Title style", "happyrider"),
							"desc" => __("Title style", "happyrider"),
							"value" => "standart",
							"type" => "select",
							"options" => array(
								
								'regular' => __('Regular', 'happyrider'),
								'underline' => __('Underline', 'happyrider'),
								'custom' => __('Custom', 'happyrider'),
								'standart' => __('Standart', 'happyrider'),
								'divider' => __('Divider', 'happyrider'),
								'iconed' => __('With icon (image)', 'happyrider')
							)
						),
						"align" => array(
							"title" => __("Alignment", "happyrider"),
							"desc" => __("Title text alignment", "happyrider"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						), 
						"font_size" => array(
							"title" => __("Font_size", "happyrider"),
							"desc" => __("Custom font size. If empty - use theme default", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => __("Font weight", "happyrider"),
							"desc" => __("Custom font weight. If empty or inherit - use theme default", "happyrider"),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'inherit' => __('Default', 'happyrider'),
								'100' => __('Thin (100)', 'happyrider'),
								'300' => __('Light (300)', 'happyrider'),
								'400' => __('Normal (400)', 'happyrider'),
								'600' => __('Semibold (600)', 'happyrider'),
								'700' => __('Bold (700)', 'happyrider'),
								'900' => __('Black (900)', 'happyrider')
							)
						),
						"color" => array(
							"title" => __("Title color", "happyrider"),
							"desc" => __("Select color for the title", "happyrider"),
							"value" => "",
							"type" => "color"
						),
						"icon" => array(
							"title" => __('Title font icon',  'happyrider'),
							"desc" => __("Select font icon for the title from Fontello icons set (if style=iconed)",  'happyrider'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "icons",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
						),
						"image" => array(
							"title" => __('or image icon',  'happyrider'),
							"desc" => __("Select image icon for the title instead icon above (if style=iconed)",  'happyrider'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "images",
							"size" => "small",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['images']
						),
						"picture" => array(
							"title" => __('or URL for image file', "happyrider"),
							"desc" => __("Select or upload image or write URL from other site (if style=iconed)", "happyrider"),
							"dependency" => array(
								'style' => array('iconed')
							),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"image_size" => array(
							"title" => __('Image (picture) size', "happyrider"),
							"desc" => __("Select image (picture) size (if style='iconed')", "happyrider"),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "small",
							"type" => "checklist",
							"options" => array(
								'small' => __('Small', 'happyrider'),
								'medium' => __('Medium', 'happyrider'),
								'large' => __('Large', 'happyrider')
							)
						),
						"position" => array(
							"title" => __('Icon (image) position', "happyrider"),
							"desc" => __("Select icon (image) position (if style=iconed)", "happyrider"),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "left",
							"type" => "checklist",
							"options" => array(
								'top' => __('Top', 'happyrider'),
								'left' => __('Left', 'happyrider')
							)
						),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Toggles
				"trx_toggles" => array(
					"title" => __("Toggles", "happyrider"),
					"desc" => __("Toggles items", "happyrider"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Toggles style", "happyrider"),
							"desc" => __("Select style for display toggles", "happyrider"),
							"value" => 1,
							"options" => happyrider_get_list_styles(1, 2),
							"type" => "radio"
						),
						"counter" => array(
							"title" => __("Counter", "happyrider"),
							"desc" => __("Display counter before each toggles title", "happyrider"),
							"value" => "off",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['on_off']
						),
						"icon_closed" => array(
							"title" => __("Icon while closed",  'happyrider'),
							"desc" => __('Select icon for the closed toggles item from Fontello icons set',  'happyrider'),
							"value" => "",
							"type" => "icons",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
						),
						"icon_opened" => array(
							"title" => __("Icon while opened",  'happyrider'),
							"desc" => __('Select icon for the opened toggles item from Fontello icons set',  'happyrider'),
							"value" => "",
							"type" => "icons",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
						),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_toggles_item",
						"title" => __("Toggles item", "happyrider"),
						"desc" => __("Toggles item", "happyrider"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => __("Toggles item title", "happyrider"),
								"desc" => __("Title for current toggles item", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"open" => array(
								"title" => __("Open on show", "happyrider"),
								"desc" => __("Open current toggles item on show", "happyrider"),
								"value" => "no",
								"type" => "switch",
								"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
							),
							"icon_closed" => array(
								"title" => __("Icon while closed",  'happyrider'),
								"desc" => __('Select icon for the closed toggles item from Fontello icons set',  'happyrider'),
								"value" => "",
								"type" => "icons",
								"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
							),
							"icon_opened" => array(
								"title" => __("Icon while opened",  'happyrider'),
								"desc" => __('Select icon for the opened toggles item from Fontello icons set',  'happyrider'),
								"value" => "",
								"type" => "icons",
								"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
							),
							"_content_" => array(
								"title" => __("Toggles item content", "happyrider"),
								"desc" => __("Current toggles item content", "happyrider"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
							"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
							"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
			
				// Tooltip
				"trx_tooltip" => array(
					"title" => __("Tooltip", "happyrider"),
					"desc" => __("Create tooltip for selected text", "happyrider"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => __("Title", "happyrider"),
							"desc" => __("Tooltip title (required)", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => __("Tipped content", "happyrider"),
							"desc" => __("Highlighted content with tooltip", "happyrider"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Twitter
				"trx_twitter" => array(
					"title" => __("Twitter", "happyrider"),
					"desc" => __("Insert twitter feed into post (page)", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"user" => array(
							"title" => __("Twitter Username", "happyrider"),
							"desc" => __("Your username in the twitter account. If empty - get it from Theme Options.", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"consumer_key" => array(
							"title" => __("Consumer Key", "happyrider"),
							"desc" => __("Consumer Key from the twitter account", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"consumer_secret" => array(
							"title" => __("Consumer Secret", "happyrider"),
							"desc" => __("Consumer Secret from the twitter account", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"token_key" => array(
							"title" => __("Token Key", "happyrider"),
							"desc" => __("Token Key from the twitter account", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"token_secret" => array(
							"title" => __("Token Secret", "happyrider"),
							"desc" => __("Token Secret from the twitter account", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"count" => array(
							"title" => __("Tweets number", "happyrider"),
							"desc" => __("Tweets number to show", "happyrider"),
							"divider" => true,
							"value" => 3,
							"max" => 20,
							"min" => 1,
							"type" => "spinner"
						),
						"controls" => array(
							"title" => __("Show arrows", "happyrider"),
							"desc" => __("Show control buttons", "happyrider"),
							"value" => "yes",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"interval" => array(
							"title" => __("Tweets change interval", "happyrider"),
							"desc" => __("Tweets change interval (in milliseconds: 1000ms = 1s)", "happyrider"),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"align" => array(
							"title" => __("Alignment", "happyrider"),
							"desc" => __("Alignment of the tweets block", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						),
						"autoheight" => array(
							"title" => __("Autoheight", "happyrider"),
							"desc" => __("Change whole slider's height (make it equal current slide's height)", "happyrider"),
							"value" => "yes",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"scheme" => array(
							"title" => __("Color scheme", "happyrider"),
							"desc" => __("Select color scheme for this block", "happyrider"),
							"value" => "",
							"type" => "checklist",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['schemes']
						),
						"bg_color" => array(
							"title" => __("Background color", "happyrider"),
							"desc" => __("Any background color for this section", "happyrider"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => __("Background image URL", "happyrider"),
							"desc" => __("Select or upload image or write URL from other site for the background", "happyrider"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => __("Overlay", "happyrider"),
							"desc" => __("Overlay color opacity (from 0.0 to 1.0)", "happyrider"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => __("Texture", "happyrider"),
							"desc" => __("Predefined texture style from 1 to 11. 0 - without texture.", "happyrider"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Video
				"trx_video" => array(
					"title" => __("Video", "happyrider"),
					"desc" => __("Insert video player", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => __("URL for video file", "happyrider"),
							"desc" => __("Select video from media library or paste URL for video file from other site", "happyrider"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => __('Choose video', 'happyrider'),
								'action' => 'media_upload',
								'type' => 'video',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => __('Choose video file', 'happyrider'),
									'update' => __('Select video file', 'happyrider')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"ratio" => array(
							"title" => __("Ratio", "happyrider"),
							"desc" => __("Ratio of the video", "happyrider"),
							"value" => "16:9",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								"16:9" => __("16:9", 'happyrider'),
								"4:3" => __("4:3", 'happyrider')
							)
						),
						"autoplay" => array(
							"title" => __("Autoplay video", "happyrider"),
							"desc" => __("Autoplay video on page load", "happyrider"),
							"value" => "off",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['on_off']
						),
						"align" => array(
							"title" => __("Align", "happyrider"),
							"desc" => __("Select block alignment", "happyrider"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						),
						"image" => array(
							"title" => __("Cover image", "happyrider"),
							"desc" => __("Select or upload image or write URL from other site for video preview", "happyrider"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_image" => array(
							"title" => __("Background image", "happyrider"),
							"desc" => __("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "happyrider"),
							"divider" => true,
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_top" => array(
							"title" => __("Top offset", "happyrider"),
							"desc" => __("Top offset (padding) inside background image to video block (in percent). For example: 3%", "happyrider"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_bottom" => array(
							"title" => __("Bottom offset", "happyrider"),
							"desc" => __("Bottom offset (padding) inside background image to video block (in percent). For example: 3%", "happyrider"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_left" => array(
							"title" => __("Left offset", "happyrider"),
							"desc" => __("Left offset (padding) inside background image to video block (in percent). For example: 20%", "happyrider"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_right" => array(
							"title" => __("Right offset", "happyrider"),
							"desc" => __("Right offset (padding) inside background image to video block (in percent). For example: 12%", "happyrider"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Zoom
				"trx_zoom" => array(
					"title" => __("Zoom", "happyrider"),
					"desc" => __("Insert the image with zoom/lens effect", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"effect" => array(
							"title" => __("Effect", "happyrider"),
							"desc" => __("Select effect to display overlapping image", "happyrider"),
							"value" => "lens",
							"size" => "medium",
							"type" => "switch",
							"options" => array(
								"lens" => __('Lens', 'happyrider'),
								"zoom" => __('Zoom', 'happyrider')
							)
						),
						"url" => array(
							"title" => __("Main image", "happyrider"),
							"desc" => __("Select or upload main image", "happyrider"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"over" => array(
							"title" => __("Overlaping image", "happyrider"),
							"desc" => __("Select or upload overlaping image", "happyrider"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"align" => array(
							"title" => __("Float zoom", "happyrider"),
							"desc" => __("Float zoom to left or right side", "happyrider"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['float']
						), 
						"bg_image" => array(
							"title" => __("Background image", "happyrider"),
							"desc" => __("Select or upload image or write URL from other site for zoom block background. Attention! If you use background image - specify paddings below from background margins to zoom block in percents!", "happyrider"),
							"divider" => true,
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_top" => array(
							"title" => __("Top offset", "happyrider"),
							"desc" => __("Top offset (padding) inside background image to zoom block (in percent). For example: 3%", "happyrider"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_bottom" => array(
							"title" => __("Bottom offset", "happyrider"),
							"desc" => __("Bottom offset (padding) inside background image to zoom block (in percent). For example: 3%", "happyrider"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_left" => array(
							"title" => __("Left offset", "happyrider"),
							"desc" => __("Left offset (padding) inside background image to zoom block (in percent). For example: 20%", "happyrider"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_right" => array(
							"title" => __("Right offset", "happyrider"),
							"desc" => __("Right offset (padding) inside background image to zoom block (in percent). For example: 12%", "happyrider"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					)
				)
			);
	
			// Woocommerce Shortcodes list
			//------------------------------------------------------------------
			if (happyrider_exists_woocommerce()) {
				
				// WooCommerce - Cart
				$HAPPYRIDER_GLOBALS['shortcodes']["woocommerce_cart"] = array(
					"title" => __("Woocommerce: Cart", "happyrider"),
					"desc" => __("WooCommerce shortcode: show Cart page", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Checkout
				$HAPPYRIDER_GLOBALS['shortcodes']["woocommerce_checkout"] = array(
					"title" => __("Woocommerce: Checkout", "happyrider"),
					"desc" => __("WooCommerce shortcode: show Checkout page", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - My Account
				$HAPPYRIDER_GLOBALS['shortcodes']["woocommerce_my_account"] = array(
					"title" => __("Woocommerce: My Account", "happyrider"),
					"desc" => __("WooCommerce shortcode: show My Account page", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Order Tracking
				$HAPPYRIDER_GLOBALS['shortcodes']["woocommerce_order_tracking"] = array(
					"title" => __("Woocommerce: Order Tracking", "happyrider"),
					"desc" => __("WooCommerce shortcode: show Order Tracking page", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Shop Messages
				$HAPPYRIDER_GLOBALS['shortcodes']["shop_messages"] = array(
					"title" => __("Woocommerce: Shop Messages", "happyrider"),
					"desc" => __("WooCommerce shortcode: show shop messages", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Product Page
				$HAPPYRIDER_GLOBALS['shortcodes']["product_page"] = array(
					"title" => __("Woocommerce: Product Page", "happyrider"),
					"desc" => __("WooCommerce shortcode: display single product page", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"sku" => array(
							"title" => __("SKU", "happyrider"),
							"desc" => __("SKU code of displayed product", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"id" => array(
							"title" => __("ID", "happyrider"),
							"desc" => __("ID of displayed product", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"posts_per_page" => array(
							"title" => __("Number", "happyrider"),
							"desc" => __("How many products showed", "happyrider"),
							"value" => "1",
							"min" => 1,
							"type" => "spinner"
						),
						"post_type" => array(
							"title" => __("Post type", "happyrider"),
							"desc" => __("Post type for the WP query (leave 'product')", "happyrider"),
							"value" => "product",
							"type" => "text"
						),
						"post_status" => array(
							"title" => __("Post status", "happyrider"),
							"desc" => __("Display posts only with this status", "happyrider"),
							"value" => "publish",
							"type" => "select",
							"options" => array(
								"publish" => __('Publish', 'happyrider'),
								"protected" => __('Protected', 'happyrider'),
								"private" => __('Private', 'happyrider'),
								"pending" => __('Pending', 'happyrider'),
								"draft" => __('Draft', 'happyrider')
							)
						)
					)
				);
				
				// WooCommerce - Product
				$HAPPYRIDER_GLOBALS['shortcodes']["product"] = array(
					"title" => __("Woocommerce: Product", "happyrider"),
					"desc" => __("WooCommerce shortcode: display one product", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"sku" => array(
							"title" => __("SKU", "happyrider"),
							"desc" => __("SKU code of displayed product", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"id" => array(
							"title" => __("ID", "happyrider"),
							"desc" => __("ID of displayed product", "happyrider"),
							"value" => "",
							"type" => "text"
						)
					)
				);
				
				// WooCommerce - Best Selling Products
				$HAPPYRIDER_GLOBALS['shortcodes']["best_selling_products"] = array(
					"title" => __("Woocommerce: Best Selling Products", "happyrider"),
					"desc" => __("WooCommerce shortcode: show best selling products", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "happyrider"),
							"desc" => __("How many products showed", "happyrider"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "happyrider"),
							"desc" => __("How many columns per row use for products output", "happyrider"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						)
					)
				);
				
				// WooCommerce - Recent Products
				$HAPPYRIDER_GLOBALS['shortcodes']["recent_products"] = array(
					"title" => __("Woocommerce: Recent Products", "happyrider"),
					"desc" => __("WooCommerce shortcode: show recent products", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "happyrider"),
							"desc" => __("How many products showed", "happyrider"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "happyrider"),
							"desc" => __("How many columns per row use for products output", "happyrider"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "happyrider"),
							"desc" => __("Sorting order for products output", "happyrider"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'happyrider'),
								"title" => __('Title', 'happyrider')
							)
						),
						"order" => array(
							"title" => __("Order", "happyrider"),
							"desc" => __("Sorting order for products output", "happyrider"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Related Products
				$HAPPYRIDER_GLOBALS['shortcodes']["related_products"] = array(
					"title" => __("Woocommerce: Related Products", "happyrider"),
					"desc" => __("WooCommerce shortcode: show related products", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"posts_per_page" => array(
							"title" => __("Number", "happyrider"),
							"desc" => __("How many products showed", "happyrider"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "happyrider"),
							"desc" => __("How many columns per row use for products output", "happyrider"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "happyrider"),
							"desc" => __("Sorting order for products output", "happyrider"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'happyrider'),
								"title" => __('Title', 'happyrider')
							)
						)
					)
				);
				
				// WooCommerce - Featured Products
				$HAPPYRIDER_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => __("Woocommerce: Featured Products", "happyrider"),
					"desc" => __("WooCommerce shortcode: show featured products", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "happyrider"),
							"desc" => __("How many products showed", "happyrider"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "happyrider"),
							"desc" => __("How many columns per row use for products output", "happyrider"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "happyrider"),
							"desc" => __("Sorting order for products output", "happyrider"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'happyrider'),
								"title" => __('Title', 'happyrider')
							)
						),
						"order" => array(
							"title" => __("Order", "happyrider"),
							"desc" => __("Sorting order for products output", "happyrider"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Top Rated Products
				$HAPPYRIDER_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => __("Woocommerce: Top Rated Products", "happyrider"),
					"desc" => __("WooCommerce shortcode: show top rated products", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "happyrider"),
							"desc" => __("How many products showed", "happyrider"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "happyrider"),
							"desc" => __("How many columns per row use for products output", "happyrider"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "happyrider"),
							"desc" => __("Sorting order for products output", "happyrider"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'happyrider'),
								"title" => __('Title', 'happyrider')
							)
						),
						"order" => array(
							"title" => __("Order", "happyrider"),
							"desc" => __("Sorting order for products output", "happyrider"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Sale Products
				$HAPPYRIDER_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => __("Woocommerce: Sale Products", "happyrider"),
					"desc" => __("WooCommerce shortcode: list products on sale", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "happyrider"),
							"desc" => __("How many products showed", "happyrider"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "happyrider"),
							"desc" => __("How many columns per row use for products output", "happyrider"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "happyrider"),
							"desc" => __("Sorting order for products output", "happyrider"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'happyrider'),
								"title" => __('Title', 'happyrider')
							)
						),
						"order" => array(
							"title" => __("Order", "happyrider"),
							"desc" => __("Sorting order for products output", "happyrider"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Product Category
				$HAPPYRIDER_GLOBALS['shortcodes']["product_category"] = array(
					"title" => __("Woocommerce: Products from category", "happyrider"),
					"desc" => __("WooCommerce shortcode: list products in specified category(-ies)", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "happyrider"),
							"desc" => __("How many products showed", "happyrider"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "happyrider"),
							"desc" => __("How many columns per row use for products output", "happyrider"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "happyrider"),
							"desc" => __("Sorting order for products output", "happyrider"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'happyrider'),
								"title" => __('Title', 'happyrider')
							)
						),
						"order" => array(
							"title" => __("Order", "happyrider"),
							"desc" => __("Sorting order for products output", "happyrider"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['ordering']
						),
						"category" => array(
							"title" => __("Categories", "happyrider"),
							"desc" => __("Comma separated category slugs", "happyrider"),
							"value" => '',
							"type" => "text"
						),
						"operator" => array(
							"title" => __("Operator", "happyrider"),
							"desc" => __("Categories operator", "happyrider"),
							"value" => "IN",
							"type" => "checklist",
							"size" => "medium",
							"options" => array(
								"IN" => __('IN', 'happyrider'),
								"NOT IN" => __('NOT IN', 'happyrider'),
								"AND" => __('AND', 'happyrider')
							)
						)
					)
				);
				
				// WooCommerce - Products
				$HAPPYRIDER_GLOBALS['shortcodes']["products"] = array(
					"title" => __("Woocommerce: Products", "happyrider"),
					"desc" => __("WooCommerce shortcode: list all products", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"skus" => array(
							"title" => __("SKUs", "happyrider"),
							"desc" => __("Comma separated SKU codes of products", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"ids" => array(
							"title" => __("IDs", "happyrider"),
							"desc" => __("Comma separated ID of products", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"columns" => array(
							"title" => __("Columns", "happyrider"),
							"desc" => __("How many columns per row use for products output", "happyrider"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "happyrider"),
							"desc" => __("Sorting order for products output", "happyrider"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'happyrider'),
								"title" => __('Title', 'happyrider')
							)
						),
						"order" => array(
							"title" => __("Order", "happyrider"),
							"desc" => __("Sorting order for products output", "happyrider"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Product attribute
				$HAPPYRIDER_GLOBALS['shortcodes']["product_attribute"] = array(
					"title" => __("Woocommerce: Products by Attribute", "happyrider"),
					"desc" => __("WooCommerce shortcode: show products with specified attribute", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "happyrider"),
							"desc" => __("How many products showed", "happyrider"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "happyrider"),
							"desc" => __("How many columns per row use for products output", "happyrider"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "happyrider"),
							"desc" => __("Sorting order for products output", "happyrider"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'happyrider'),
								"title" => __('Title', 'happyrider')
							)
						),
						"order" => array(
							"title" => __("Order", "happyrider"),
							"desc" => __("Sorting order for products output", "happyrider"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['ordering']
						),
						"attribute" => array(
							"title" => __("Attribute", "happyrider"),
							"desc" => __("Attribute name", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"filter" => array(
							"title" => __("Filter", "happyrider"),
							"desc" => __("Attribute value", "happyrider"),
							"value" => "",
							"type" => "text"
						)
					)
				);
				
				// WooCommerce - Products Categories
				$HAPPYRIDER_GLOBALS['shortcodes']["product_categories"] = array(
					"title" => __("Woocommerce: Product Categories", "happyrider"),
					"desc" => __("WooCommerce shortcode: show categories with products", "happyrider"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"number" => array(
							"title" => __("Number", "happyrider"),
							"desc" => __("How many categories showed", "happyrider"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "happyrider"),
							"desc" => __("How many columns per row use for categories output", "happyrider"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "happyrider"),
							"desc" => __("Sorting order for products output", "happyrider"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'happyrider'),
								"title" => __('Title', 'happyrider')
							)
						),
						"order" => array(
							"title" => __("Order", "happyrider"),
							"desc" => __("Sorting order for products output", "happyrider"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['ordering']
						),
						"parent" => array(
							"title" => __("Parent", "happyrider"),
							"desc" => __("Parent category slug", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"ids" => array(
							"title" => __("IDs", "happyrider"),
							"desc" => __("Comma separated ID of products", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"hide_empty" => array(
							"title" => __("Hide empty", "happyrider"),
							"desc" => __("Hide empty categories", "happyrider"),
							"value" => "yes",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						)
					)
				);

			}
			
			do_action('happyrider_action_shortcodes_list');

		}
	}
}
?>