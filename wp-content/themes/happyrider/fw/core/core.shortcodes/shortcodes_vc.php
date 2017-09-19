<?php
if (is_admin() 
		|| (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true' )
		|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline')
	) {
	require_once( happyrider_get_file_dir('core/core.shortcodes/shortcodes_vc_classes.php') );
}

// Width and height params
if ( !function_exists( 'happyrider_vc_width' ) ) {
	function happyrider_vc_width($w='') {
		return array(
			"param_name" => "width",
			"heading" => __("Width", "happyrider"),
			"description" => __("Width (in pixels or percent) of the current element", "happyrider"),
			"group" => __('Size &amp; Margins', 'happyrider'),
			"value" => $w,
			"type" => "textfield"
		);
	}
}
if ( !function_exists( 'happyrider_vc_height' ) ) {
	function happyrider_vc_height($h='') {
		return array(
			"param_name" => "height",
			"heading" => __("Height", "happyrider"),
			"description" => __("Height (only in pixels) of the current element", "happyrider"),
			"group" => __('Size &amp; Margins', 'happyrider'),
			"value" => $h,
			"type" => "textfield"
		);
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'happyrider_shortcodes_vc_scripts_admin' ) ) {
	//add_action( 'admin_enqueue_scripts', 'happyrider_shortcodes_vc_scripts_admin' );
	function happyrider_shortcodes_vc_scripts_admin() {
		// Include CSS 
		happyrider_enqueue_style ( 'shortcodes_vc-style', happyrider_get_file_url('core/core.shortcodes/shortcodes_vc_admin.css'), array(), null );
		// Include JS
		happyrider_enqueue_script( 'shortcodes_vc-script', happyrider_get_file_url('core/core.shortcodes/shortcodes_vc_admin.js'), array(), null, true );
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'happyrider_shortcodes_vc_scripts_front' ) ) {
	//add_action( 'wp_enqueue_scripts', 'happyrider_shortcodes_vc_scripts_front' );
	function happyrider_shortcodes_vc_scripts_front() {
		if (happyrider_vc_is_frontend()) {
			// Include CSS 
			happyrider_enqueue_style ( 'shortcodes_vc-style', happyrider_get_file_url('core/core.shortcodes/shortcodes_vc_front.css'), array(), null );
			// Include JS
			happyrider_enqueue_script( 'shortcodes_vc-script', happyrider_get_file_url('core/core.shortcodes/shortcodes_vc_front.js'), array(), null, true );
		}
	}
}

// Add init script into shortcodes output in VC frontend editor
if ( !function_exists( 'happyrider_shortcodes_vc_add_init_script' ) ) {
	//add_filter('happyrider_shortcode_output', 'happyrider_shortcodes_vc_add_init_script', 10, 4);
	function happyrider_shortcodes_vc_add_init_script($output, $tag='', $atts=array(), $content='') {
		if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')
				&& ( isset($_POST['shortcodes'][0]['tag']) && $_POST['shortcodes'][0]['tag']==$tag )
		) {
			if (happyrider_strpos($output, 'happyrider_vc_init_shortcodes')===false) {
				$id = "happyrider_vc_init_shortcodes_".str_replace('.', '', mt_rand());
				$output .= '
					<script id="'.esc_attr($id).'">
						try {
							happyrider_init_post_formats();
							happyrider_init_shortcodes(jQuery("body").eq(0));
							happyrider_scroll_actions();
						} catch (e) { };
					</script>
				';
			}
		}
		return $output;
	}
}


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'happyrider_shortcodes_vc_theme_setup' ) ) {
	//if ( happyrider_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'happyrider_action_before_init_theme', 'happyrider_shortcodes_vc_theme_setup', 20 );
	else
		add_action( 'happyrider_action_after_init_theme', 'happyrider_shortcodes_vc_theme_setup' );
	function happyrider_shortcodes_vc_theme_setup() {
	
		// Set dir with theme specific VC shortcodes
		if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
			vc_set_shortcodes_templates_dir( happyrider_get_folder_dir('core/core.shortcodes/vc_shortcodes' ) );
		}
		
		// Add/Remove params in the standard VC shortcodes
		vc_add_param("vc_row", array(
					"param_name" => "scheme",
					"heading" => __("Color scheme", "happyrider"),
					"description" => __("Select color scheme for this block", "happyrider"),
					"group" => __('Color scheme', 'happyrider'),
					"class" => "",
					"value" => array_flip(happyrider_get_list_color_schemes(true)),
					"type" => "dropdown"
		));

		if (happyrider_shortcodes_is_used()) {

			// Set VC as main editor for the theme
			vc_set_as_theme( true );
			
			// Enable VC on follow post types
			vc_set_default_editor_post_types( array('page', 'team') );
			
			// Disable frontend editor
			//vc_disable_frontend();

			// Load scripts and styles for VC support
			add_action( 'wp_enqueue_scripts',		'happyrider_shortcodes_vc_scripts_front');
			add_action( 'admin_enqueue_scripts',	'happyrider_shortcodes_vc_scripts_admin' );

			// Add init script into shortcodes output in VC frontend editor
			add_filter('happyrider_shortcode_output', 'happyrider_shortcodes_vc_add_init_script', 10, 4);

			// Remove standard VC shortcodes
			vc_remove_element("vc_button");
			vc_remove_element("vc_posts_slider");
			vc_remove_element("vc_gmaps");
			vc_remove_element("vc_teaser_grid");
			vc_remove_element("vc_progress_bar");
			vc_remove_element("vc_facebook");
			vc_remove_element("vc_tweetmeme");
			vc_remove_element("vc_googleplus");
			vc_remove_element("vc_facebook");
			vc_remove_element("vc_pinterest");
			vc_remove_element("vc_message");
			vc_remove_element("vc_posts_grid");
			vc_remove_element("vc_carousel");
			vc_remove_element("vc_flickr");
			vc_remove_element("vc_tour");
			vc_remove_element("vc_separator");
			vc_remove_element("vc_single_image");
			vc_remove_element("vc_cta_button");
//			vc_remove_element("vc_accordion");
//			vc_remove_element("vc_accordion_tab");
			vc_remove_element("vc_toggle");
			vc_remove_element("vc_tabs");
			vc_remove_element("vc_tab");
			vc_remove_element("vc_images_carousel");
			
			// Remove standard WP widgets
			vc_remove_element("vc_wp_archives");
			vc_remove_element("vc_wp_calendar");
			vc_remove_element("vc_wp_categories");
			vc_remove_element("vc_wp_custommenu");
			vc_remove_element("vc_wp_links");
			vc_remove_element("vc_wp_meta");
			vc_remove_element("vc_wp_pages");
			vc_remove_element("vc_wp_posts");
			vc_remove_element("vc_wp_recentcomments");
			vc_remove_element("vc_wp_rss");
			vc_remove_element("vc_wp_search");
			vc_remove_element("vc_wp_tagcloud");
			vc_remove_element("vc_wp_text");
			
			global $HAPPYRIDER_GLOBALS;
			
			$HAPPYRIDER_GLOBALS['vc_params'] = array(
				
				// Common arrays and strings
				'category' => __("HappyRider shortcodes", "happyrider"),
			
				// Current element id
				'id' => array(
					"param_name" => "id",
					"heading" => __("Element ID", "happyrider"),
					"description" => __("ID for current element", "happyrider"),
					"group" => __('ID &amp; Class', 'happyrider'),
					"value" => "",
					"type" => "textfield"
				),
			
				// Current element class
				'class' => array(
					"param_name" => "class",
					"heading" => __("Element CSS class", "happyrider"),
					"description" => __("CSS class for current element", "happyrider"),
					"group" => __('ID &amp; Class', 'happyrider'),
					"value" => "",
					"type" => "textfield"
				),

				// Current element animation
				'animation' => array(
					"param_name" => "animation",
					"heading" => __("Animation", "happyrider"),
					"description" => __("Select animation while object enter in the visible area of page", "happyrider"),
					"group" => __('ID &amp; Class', 'happyrider'),
					"class" => "",
					"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['animations']),
					"type" => "dropdown"
				),
			
				// Current element style
				'css' => array(
					"param_name" => "css",
					"heading" => __("CSS styles", "happyrider"),
					"description" => __("Any additional CSS rules (if need)", "happyrider"),
					"group" => __('ID &amp; Class', 'happyrider'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				// Margins params
				'margin_top' => array(
					"param_name" => "top",
					"heading" => __("Top margin", "happyrider"),
					"description" => __("Top margin (in pixels).", "happyrider"),
					"group" => __('Size &amp; Margins', 'happyrider'),
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_bottom' => array(
					"param_name" => "bottom",
					"heading" => __("Bottom margin", "happyrider"),
					"description" => __("Bottom margin (in pixels).", "happyrider"),
					"group" => __('Size &amp; Margins', 'happyrider'),
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_left' => array(
					"param_name" => "left",
					"heading" => __("Left margin", "happyrider"),
					"description" => __("Left margin (in pixels).", "happyrider"),
					"group" => __('Size &amp; Margins', 'happyrider'),
					"value" => "",
					"type" => "textfield"
				),
				
				'margin_right' => array(
					"param_name" => "right",
					"heading" => __("Right margin", "happyrider"),
					"description" => __("Right margin (in pixels).", "happyrider"),
					"group" => __('Size &amp; Margins', 'happyrider'),
					"value" => "",
					"type" => "textfield"
				)
			);
	
	
	
			// Accordion
			//-------------------------------------------------------------------------------------
			vc_map( array(
				"base" => "trx_accordion",
				"name" => __("Accordion", "happyrider"),
				"description" => __("Accordion items", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_accordion',
				"class" => "trx_sc_collection trx_sc_accordion",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_accordion_item'),	// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
				"params" => array(

					array(
						"param_name" => "counter",
						"heading" => __("Counter", "happyrider"),
						"description" => __("Display counter before each accordion title", "happyrider"),
						"class" => "",
						"value" => array("Add item numbers before each element" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "initial",
						"heading" => __("Initially opened item", "happyrider"),
						"description" => __("Number of initially opened item", "happyrider"),
						"class" => "",
						"value" => 1,
						"type" => "textfield"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => __("Icon while closed", "happyrider"),
						"description" => __("Select icon for the closed accordion item from Fontello icons set", "happyrider"),
						"class" => "",
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => __("Icon while opened", "happyrider"),
						"description" => __("Select icon for the opened accordion item from Fontello icons set", "happyrider"),
						"class" => "",
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_accordion_item title="' . __( 'Item 1 title', 'happyrider' ) . '"][/trx_accordion_item]
					[trx_accordion_item title="' . __( 'Item 2 title', 'happyrider' ) . '"][/trx_accordion_item]
				',
				"custom_markup" => '
					<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
						%content%
					</div>
					<div class="tab_controls">
						<button class="add_tab" title="'.__("Add item", "happyrider").'">'.__("Add item", "happyrider").'</button>
					</div>
				',
				'js_view' => 'VcTrxAccordionView'
			) );
			
			
			vc_map( array(
				"base" => "trx_accordion_item",
				"name" => __("Accordion item", "happyrider"),
				"description" => __("Inner accordion item", "happyrider"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_accordion_item',
				"as_child" => array('only' => 'trx_accordion'), 	// Use only|except attributes to limit parent (separate multiple values with comma)
				"as_parent" => array('except' => 'trx_accordion'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Title", "happyrider"),
						"description" => __("Title for current accordion item", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => __("Icon while closed", "happyrider"),
						"description" => __("Select icon for the closed accordion item from Fontello icons set", "happyrider"),
						"class" => "",
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => __("Icon while opened", "happyrider"),
						"description" => __("Select icon for the opened accordion item from Fontello icons set", "happyrider"),
						"class" => "",
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['css']
				),
			  'js_view' => 'VcTrxAccordionTabView'
			) );

			class WPBakeryShortCode_Trx_Accordion extends HAPPYRIDER_VC_ShortCodeAccordion {}
			class WPBakeryShortCode_Trx_Accordion_Item extends HAPPYRIDER_VC_ShortCodeAccordionItem {}
			
			
			
			
			
			
			// Anchor
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_anchor",
				"name" => __("Anchor", "happyrider"),
				"description" => __("Insert anchor for the TOC (table of content)", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_anchor',
				"class" => "trx_sc_single trx_sc_anchor",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "icon",
						"heading" => __("Anchor's icon", "happyrider"),
						"description" => __("Select icon for the anchor from Fontello icons set", "happyrider"),
						"class" => "",
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => __("Short title", "happyrider"),
						"description" => __("Short title of the anchor (for the table of content)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => __("Long description", "happyrider"),
						"description" => __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "url",
						"heading" => __("External URL", "happyrider"),
						"description" => __("External URL for this TOC item", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "separator",
						"heading" => __("Add separator", "happyrider"),
						"description" => __("Add separator under item in the TOC", "happyrider"),
						"class" => "",
						"value" => array("Add separator" => "yes" ),
						"type" => "checkbox"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id']
				),
			) );
			
			class WPBakeryShortCode_Trx_Anchor extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Audio
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_audio",
				"name" => __("Audio", "happyrider"),
				"description" => __("Insert audio player", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_audio',
				"class" => "trx_sc_single trx_sc_audio",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => __("URL for audio file", "happyrider"),
						"description" => __("Put here URL for audio file", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "image",
						"heading" => __("Cover image", "happyrider"),
						"description" => __("Select or upload image or write URL from other site for audio cover", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "happyrider"),
						"description" => __("Title of the audio file", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "author",
						"heading" => __("Author", "happyrider"),
						"description" => __("Author of the audio file", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => __("Controls", "happyrider"),
						"description" => __("Show/hide controls", "happyrider"),
						"class" => "",
						"value" => array("Hide controls" => "hide" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "autoplay",
						"heading" => __("Autoplay", "happyrider"),
						"description" => __("Autoplay audio on page load", "happyrider"),
						"class" => "",
						"value" => array("Autoplay" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Select block alignment", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				),
			) );
			
			class WPBakeryShortCode_Trx_Audio extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_block",
				"name" => __("Block container", "happyrider"),
				"description" => __("Container for any block ([section] analog - to enable nesting)", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_block',
				"class" => "trx_sc_collection trx_sc_block",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "dedicated",
						"heading" => __("Dedicated", "happyrider"),
						"description" => __("Use this block as dedicated content - show it before post title on single page", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Use as dedicated content', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Select block alignment", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns emulation", "happyrider"),
						"description" => __("Select width for columns emulation", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['columns']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "pan",
						"heading" => __("Use pan effect", "happyrider"),
						"description" => __("Use pan effect to show section content", "happyrider"),
						"group" => __('Scroll', 'happyrider'),
						"class" => "",
						"value" => array(__('Content scroller', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll",
						"heading" => __("Use scroller", "happyrider"),
						"description" => __("Use scroller to show section content", "happyrider"),
						"group" => __('Scroll', 'happyrider'),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Content scroller', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll_dir",
						"heading" => __("Scroll direction", "happyrider"),
						"description" => __("Scroll direction (if Use scroller = yes)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"group" => __('Scroll', 'happyrider'),
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['dir']),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll_controls",
						"heading" => __("Scroll controls", "happyrider"),
						"description" => __("Show scroll controls (if Use scroller = yes)", "happyrider"),
						"class" => "",
						"group" => __('Scroll', 'happyrider'),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"value" => array(__('Show scroll controls', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scheme",
						"heading" => __("Color scheme", "happyrider"),
						"description" => __("Select color scheme for this block", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Fore color", "happyrider"),
						"description" => __("Any color for objects in this section", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "happyrider"),
						"description" => __("Any background color for this section", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image URL", "happyrider"),
						"description" => __("Select background image from library for this section", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_tile",
						"heading" => __("Tile background image", "happyrider"),
						"description" => __("Do you want tile background image or image cover whole block?", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						'dependency' => array(
							'element' => 'bg_image',
							'not_empty' => true
						),
						"std" => "no",
						"value" => array(__('Tile background image', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", "happyrider"),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", "happyrider"),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", "happyrider"),
						"description" => __("Font size of the text (default - in pixels, allows any CSS units of measure)", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => __("Font weight", "happyrider"),
						"description" => __("Font weight of the text", "happyrider"),
						"class" => "",
						"value" => array(
							__('Default', 'happyrider') => 'inherit',
							__('Thin (100)', 'happyrider') => '100',
							__('Light (300)', 'happyrider') => '300',
							__('Normal (400)', 'happyrider') => '400',
							__('Bold (700)', 'happyrider') => '700'
						),
						"type" => "dropdown"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Container content", "happyrider"),
						"description" => __("Content for section container", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Block extends HAPPYRIDER_VC_ShortCodeCollection {}
			
			
			
			
			
			
			// Blogger
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_blogger",
				"name" => __("Blogger", "happyrider"),
				"description" => __("Insert posts (pages) in many styles from desired categories or directly from ids", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_blogger',
				"class" => "trx_sc_single trx_sc_blogger",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Output style", "happyrider"),
						"description" => __("Select desired style for posts output", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['blogger_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "filters",
						"heading" => __("Show filters", "happyrider"),
						"description" => __("Use post's tags or categories as filter buttons", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['filters']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "hover",
						"heading" => __("Hover effect", "happyrider"),
						"description" => __("Select hover effect (only if style=Portfolio)", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['hovers']),
						'dependency' => array(
							'element' => 'style',
							'value' => array('portfolio_2','portfolio_3','portfolio_4','grid_2','grid_3','grid_4','square_2','square_3','square_4','short_2','short_3','short_4','colored_2','colored_3','colored_4')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "hover_dir",
						"heading" => __("Hover direction", "happyrider"),
						"description" => __("Select hover direction (only if style=Portfolio and hover=Circle|Square)", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['hovers_dir']),
						'dependency' => array(
							'element' => 'style',
							'value' => array('portfolio_2','portfolio_3','portfolio_4','grid_2','grid_3','grid_4','square_2','square_3','square_4','short_2','short_3','short_4','colored_2','colored_3','colored_4')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "location",
						"heading" => __("Dedicated content location", "happyrider"),
						"description" => __("Select position for dedicated content (only for style=excerpt)", "happyrider"),
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('excerpt')
						),
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['locations']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "dir",
						"heading" => __("Posts direction", "happyrider"),
						"description" => __("Display posts in horizontal or vertical direction", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"std" => "horizontal",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "rating",
						"heading" => __("Show rating stars", "happyrider"),
						"description" => __("Show rating stars under post's header", "happyrider"),
						"group" => __('Details', 'happyrider'),
						"class" => "",
						"value" => array(__('Show rating', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "info",
						"heading" => __("Show post info block", "happyrider"),
						"description" => __("Show post info block (author, date, tags, etc.)", "happyrider"),
						"class" => "",
						"std" => 'yes',
						"value" => array(__('Show info', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "descr",
						"heading" => __("Description length", "happyrider"),
						"description" => __("How many characters are displayed from post excerpt? If 0 - don't show description", "happyrider"),
						"group" => __('Details', 'happyrider'),
						"class" => "",
						"value" => 0,
						"type" => "textfield"
					),
					array(
						"param_name" => "links",
						"heading" => __("Allow links to the post", "happyrider"),
						"description" => __("Allow links to the post from each blogger item", "happyrider"),
						"group" => __('Details', 'happyrider'),
						"class" => "",
						"std" => 'yes',
						"value" => array(__('Allow links', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "readmore",
						"heading" => __("More link text", "happyrider"),
						"description" => __("Read more link text. If empty - show 'More', else - used as link text", "happyrider"),
						"group" => __('Details', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "happyrider"),
						"description" => __("Title for the block", "happyrider"),
						"admin_label" => true,
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => __("Subtitle", "happyrider"),
						"description" => __("Subtitle for the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => __("Description", "happyrider"),
						"description" => __("Description for the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "link",
						"heading" => __("Button URL", "happyrider"),
						"description" => __("Link URL for the button at the bottom of the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => __("Button caption", "happyrider"),
						"description" => __("Caption for the button at the bottom of the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => __("Post type", "happyrider"),
						"description" => __("Select post type to show", "happyrider"),
						"group" => __('Query', 'happyrider'),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['posts_types']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => __("Post IDs list", "happyrider"),
						"description" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "happyrider"),
						"group" => __('Query', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "cat",
						"heading" => __("Categories list", "happyrider"),
						"description" => __("Select category. If empty - show posts from any category or from IDs list", "happyrider"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"group" => __('Query', 'happyrider'),
						"class" => "",
						"value" => array_flip(happyrider_array_merge(array(0 => __('- Select category -', 'happyrider')), $HAPPYRIDER_GLOBALS['sc_params']['categories'])),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => __("Total posts to show", "happyrider"),
						"description" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "happyrider"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"admin_label" => true,
						"group" => __('Query', 'happyrider'),
						"class" => "",
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns number", "happyrider"),
						"description" => __("How many columns used to display posts?", "happyrider"),
						'dependency' => array(
							'element' => 'dir',
							'value' => 'horizontal'
						),
						"group" => __('Query', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => __("Offset before select posts", "happyrider"),
						"description" => __("Skip posts before select next part.", "happyrider"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"group" => __('Query', 'happyrider'),
						"class" => "",
						"value" => 0,
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => __("Post order by", "happyrider"),
						"description" => __("Select desired posts sorting method", "happyrider"),
						"class" => "",
						"group" => __('Query', 'happyrider'),
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => __("Post order", "happyrider"),
						"description" => __("Select desired posts order", "happyrider"),
						"class" => "",
						"group" => __('Query', 'happyrider'),
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "only",
						"heading" => __("Select posts only", "happyrider"),
						"description" => __("Select posts only with reviews, videos, audios, thumbs or galleries", "happyrider"),
						"class" => "",
						"group" => __('Query', 'happyrider'),
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['formats']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll",
						"heading" => __("Use scroller", "happyrider"),
						"description" => __("Use scroller to show all posts", "happyrider"),
						"group" => __('Scroll', 'happyrider'),
						"class" => "",
						"value" => array(__('Use scroller', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "controls",
						"heading" => __("Show slider controls", "happyrider"),
						"description" => __("Show arrows to control scroll slider", "happyrider"),
						"group" => __('Scroll', 'happyrider'),
						"class" => "",
						"value" => array(__('Show controls', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				),
			) );
			
			class WPBakeryShortCode_Trx_Blogger extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Br
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_br",
				"name" => __("Line break", "happyrider"),
				"description" => __("Line break or Clear Floating", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_br',
				"class" => "trx_sc_single trx_sc_br",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "clear",
						"heading" => __("Clear floating", "happyrider"),
						"description" => __("Select clear side (if need)", "happyrider"),
						"class" => "",
						"value" => "",
						"value" => array(
							__('None', 'happyrider') => 'none',
							__('Left', 'happyrider') => 'left',
							__('Right', 'happyrider') => 'right',
							__('Both', 'happyrider') => 'both'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Trx_Br extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Button
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_button",
				"name" => __("Button", "happyrider"),
				"description" => __("Button with link", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_button',
				"class" => "trx_sc_single trx_sc_button",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => __("Caption", "happyrider"),
						"description" => __("Button caption", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "type",
						"heading" => __("Button's shape", "happyrider"),
						"description" => __("Select button's shape", "happyrider"),
						"class" => "",
						"value" => array(
							__('Square', 'happyrider') => 'square',
							__('Round', 'happyrider') => 'round'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => __("Button's style", "happyrider"),
						"description" => __("Select button's style", "happyrider"),
						"class" => "",
						"value" => array(
							__('Filled', 'happyrider') => 'filled',
							__('Border', 'happyrider') => 'border'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "size",
						"heading" => __("Button's size", "happyrider"),
						"description" => __("Select button's size", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Small', 'happyrider') => 'small',
							__('Medium', 'happyrider') => 'medium',
							__('Large', 'happyrider') => 'large'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Button's icon", "happyrider"),
						"description" => __("Select icon for the title from Fontello icons set", "happyrider"),
						"class" => "",
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Button's text color", "happyrider"),
						"description" => __("Any color for button's caption", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Button's backcolor", "happyrider"),
						"description" => __("Any color for button's background", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "align",
						"heading" => __("Button's alignment", "happyrider"),
						"description" => __("Align button to left, center or right", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", "happyrider"),
						"description" => __("URL for the link on button click", "happyrider"),
						"class" => "",
						"group" => __('Link', 'happyrider'),
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "target",
						"heading" => __("Link target", "happyrider"),
						"description" => __("Target for the link on button click", "happyrider"),
						"class" => "",
						"group" => __('Link', 'happyrider'),
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "popup",
						"heading" => __("Open link in popup", "happyrider"),
						"description" => __("Open link target in popup window", "happyrider"),
						"class" => "",
						"group" => __('Link', 'happyrider'),
						"value" => array(__('Open in popup', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "rel",
						"heading" => __("Rel attribute", "happyrider"),
						"description" => __("Rel attribute for the button's link (if need", "happyrider"),
						"class" => "",
						"group" => __('Link', 'happyrider'),
						"value" => "",
						"type" => "textfield"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Button extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Call to Action block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_call_to_action",
				"name" => __("Call to Action", "happyrider"),
				"description" => __("Insert call to action block in your page (post)", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_call_to_action',
				"class" => "trx_sc_collection trx_sc_call_to_action",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Block's style", "happyrider"),
						"description" => __("Select style to display this block", "happyrider"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip(happyrider_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Select block alignment", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "accent",
						"heading" => __("Accent", "happyrider"),
						"description" => __("Fill entire block with Accent1 color from current color scheme", "happyrider"),
						"class" => "",
						"value" => array("Fill with Accent1 color" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom", "happyrider"),
						"description" => __("Allow get featured image or video from inner shortcodes (custom) or get it from shortcode parameters below", "happyrider"),
						"class" => "",
						"value" => array("Custom content" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "image",
						"heading" => __("Image", "happyrider"),
						"description" => __("Image to display inside block", "happyrider"),
				        'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "video",
						"heading" => __("URL for video file", "happyrider"),
						"description" => __("Paste URL for video file to display inside block", "happyrider"),
				        'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "happyrider"),
						"description" => __("Title for the block", "happyrider"),
						"admin_label" => true,
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => __("Subtitle", "happyrider"),
						"description" => __("Subtitle for the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => __("Description", "happyrider"),
						"description" => __("Description for the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "link",
						"heading" => __("Button URL", "happyrider"),
						"description" => __("Link URL for the button at the bottom of the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => __("Button caption", "happyrider"),
						"description" => __("Caption for the button at the bottom of the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link2",
						"heading" => __("Button 2 URL", "happyrider"),
						"description" => __("Link URL for the second button at the bottom of the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link2_caption",
						"heading" => __("Button 2 caption", "happyrider"),
						"description" => __("Caption for the second button at the bottom of the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Call_To_Action extends HAPPYRIDER_VC_ShortCodeCollection {}


			
			
			
			
			// Chat
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_chat",
				"name" => __("Chat", "happyrider"),
				"description" => __("Chat message", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_chat',
				"class" => "trx_sc_container trx_sc_chat",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Item title", "happyrider"),
						"description" => __("Title for current chat item", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => __("Item photo", "happyrider"),
						"description" => __("Select or upload image or write URL from other site for the item photo (avatar)", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", "happyrider"),
						"description" => __("URL for the link on chat title click", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Chat item content", "happyrider"),
						"description" => __("Current chat item content", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextContainerView'
			
			) );
			
			class WPBakeryShortCode_Trx_Chat extends HAPPYRIDER_VC_ShortCodeContainer {}
			
			
			
			
			
			
			// Columns
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_columns",
				"name" => __("Columns", "happyrider"),
				"description" => __("Insert columns with margins", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_columns',
				"class" => "trx_sc_columns",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_column_item'),
				"params" => array(
					array(
						"param_name" => "count",
						"heading" => __("Columns count", "happyrider"),
						"description" => __("Number of the columns in the container.", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "2",
						"type" => "textfield"
					),
					array(
						"param_name" => "fluid",
						"heading" => __("Fluid columns", "happyrider"),
						"description" => __("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", "happyrider"),
						"class" => "",
						"value" => array(__('Fluid columns', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "margins",
						"heading" => __("Margins between columns", "happyrider"),
						"description" => __("Add margins between columns", "happyrider"),
						"class" => "",
						"std" => "yes",
						"value" => array(__('Disable margins between columns', 'happyrider') => 'no'),
						"type" => "checkbox"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_column_item][/trx_column_item]
					[trx_column_item][/trx_column_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_column_item",
				"name" => __("Column", "happyrider"),
				"description" => __("Column item", "happyrider"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_column_item',
				"as_child" => array('only' => 'trx_columns'),
				"as_parent" => array('except' => 'trx_columns'),
				"params" => array(
					array(
						"param_name" => "span",
						"heading" => __("Merge columns", "happyrider"),
						"description" => __("Count merged columns from current", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Alignment text in the column", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Fore color", "happyrider"),
						"description" => __("Any color for objects in this column", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "happyrider"),
						"description" => __("Any background color for this column", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("URL for background image file", "happyrider"),
						"description" => __("Select or upload image or write URL from other site for the background", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_tile",
						"heading" => __("Tile background image", "happyrider"),
						"description" => __("Do you want tile background image or image cover whole column?", "happyrider"),
						"class" => "",
						'dependency' => array(
							'element' => 'bg_image',
							'not_empty' => true
						),
						"std" => "no",
						"value" => array(__('Tile background image', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Column's content", "happyrider"),
						"description" => __("Content of the current column", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
			class WPBakeryShortCode_Trx_Columns extends HAPPYRIDER_VC_ShortCodeColumns {}
			class WPBakeryShortCode_Trx_Column_Item extends HAPPYRIDER_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Contact form
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_contact_form",
				"name" => __("Contact form", "happyrider"),
				"description" => __("Insert contact form", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_contact_form',
				"class" => "trx_sc_collection trx_sc_contact_form",
				"content_element" => true,
				"is_container" => true,
				"as_parent" => array('only' => 'trx_form_item'),
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "custom",
						"heading" => __("Custom", "happyrider"),
						"description" => __("Use custom fields or create standard contact form (ignore info from 'Field' tabs)", "happyrider"),
						"class" => "",
						"value" => array(__('Create custom form', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scheme",
						"heading" => __("Color scheme", "happyrider"),
						"description" => __("Select color scheme for this block", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "action",
						"heading" => __("Action", "happyrider"),
						"description" => __("Contact form action (URL to handle form data). If empty - use internal action", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Select form alignment", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "happyrider"),
						"description" => __("Title for the block", "happyrider"),
						"admin_label" => true,
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => __("Subtitle", "happyrider"),
						"description" => __("Subtitle for the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => __("Description", "happyrider"),
						"description" => __("Description for the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_form_item",
				"name" => __("Form item (custom field)", "happyrider"),
				"description" => __("Custom field for the contact form", "happyrider"),
				"class" => "trx_sc_item trx_sc_form_item",
				'icon' => 'icon_trx_form_item',
				//"allowed_container_element" => 'vc_row',
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				"as_child" => array('only' => 'trx_contact_form'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => __("Type", "happyrider"),
						"description" => __("Select type of the custom field", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['field_types']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "name",
						"heading" => __("Name", "happyrider"),
						"description" => __("Name of the custom field", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "value",
						"heading" => __("Default value", "happyrider"),
						"description" => __("Default value of the custom field", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "options",
						"heading" => __("Options", "happyrider"),
						"description" => __("Field options. For example: big=My daddy|middle=My brother|small=My little sister", "happyrider"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('radio','checkbox','select')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "label",
						"heading" => __("Label", "happyrider"),
						"description" => __("Label for the custom field", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "label_position",
						"heading" => __("Label position", "happyrider"),
						"description" => __("Label position relative to the field", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['label_positions']),
						"type" => "dropdown"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Contact_Form extends HAPPYRIDER_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Form_Item extends HAPPYRIDER_VC_ShortCodeItem {}
			
			
			
			
			
			
			
			// Content block on fullscreen page
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_content",
				"name" => __("Content block", "happyrider"),
				"description" => __("Container for main content block (use it only on fullscreen pages)", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_content',
				"class" => "trx_sc_collection trx_sc_content",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "scheme",
						"heading" => __("Color scheme", "happyrider"),
						"description" => __("Select color scheme for this block", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Container content", "happyrider"),
						"description" => __("Content for section container", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom']
				)
			) );
			
			class WPBakeryShortCode_Trx_Content extends HAPPYRIDER_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Countdown
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_countdown",
				"name" => __("Countdown", "happyrider"),
				"description" => __("Insert countdown object", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_countdown',
				"class" => "trx_sc_single trx_sc_countdown",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "date",
						"heading" => __("Date", "happyrider"),
						"description" => __("Upcoming date (format: yyyy-mm-dd)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "time",
						"heading" => __("Time", "happyrider"),
						"description" => __("Upcoming time (format: HH:mm:ss)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => __("Style", "happyrider"),
						"description" => __("Countdown style", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(happyrider_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Align counter to left, center or right", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Countdown extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Dropcaps
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_dropcaps",
				"name" => __("Dropcaps", "happyrider"),
				"description" => __("Make first letter of the text as dropcaps", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_dropcaps',
				"class" => "trx_sc_single trx_sc_dropcaps",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Style", "happyrider"),
						"description" => __("Dropcaps style", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(happyrider_get_list_styles(1, 4)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => __("Paragraph text", "happyrider"),
						"description" => __("Paragraph with dropcaps content", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			
			) );
			
			class WPBakeryShortCode_Trx_Dropcaps extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Emailer
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_emailer",
				"name" => __("E-mail collector", "happyrider"),
				"description" => __("Collect e-mails into specified group", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_emailer',
				"class" => "trx_sc_single trx_sc_emailer",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "group",
						"heading" => __("Group", "happyrider"),
						"description" => __("The name of group to collect e-mail address", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "open",
						"heading" => __("Opened", "happyrider"),
						"description" => __("Initially open the input field on show object", "happyrider"),
						"class" => "",
						"value" => array(__('Initially opened', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Align field to left, center or right", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Emailer extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Gap
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_gap",
				"name" => __("Gap", "happyrider"),
				"description" => __("Insert gap (fullwidth area) in the post content", "happyrider"),
				"category" => __('Structure', 'js_composer'),
				'icon' => 'icon_trx_gap',
				"class" => "trx_sc_collection trx_sc_gap",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"params" => array(
					/*
					array(
						"param_name" => "content",
						"heading" => __("Gap content", "happyrider"),
						"description" => __("Gap inner content", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					)
					*/
				)
			) );
			
			class WPBakeryShortCode_Trx_Gap extends HAPPYRIDER_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Googlemap
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_googlemap",
				"name" => __("Google map", "happyrider"),
				"description" => __("Insert Google map with desired address or coordinates", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_googlemap',
				"class" => "trx_sc_collection trx_sc_googlemap",
				"content_element" => true,
				"is_container" => true,
				"as_parent" => array('only' => 'trx_googlemap_marker'),
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "zoom",
						"heading" => __("Zoom", "happyrider"),
						"description" => __("Map zoom factor", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "16",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => __("Style", "happyrider"),
						"description" => __("Map custom style", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['googlemap_styles']),
						"type" => "dropdown"
						),
					array(
						"param_name" => "show_info",
						"heading" => __("Hide info", "happyrider"),
						"description" => __("Hide info over gogle map(default is show)", "happyrider"),
						"class" => "",
						"value" => array(__('Hide', 'happyrider') => 'no'),
						"type" => "checkbox"
						),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width('100%'),
					happyrider_vc_height(240),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			vc_map( array(
				"base" => "trx_googlemap_marker",
				"name" => __("Googlemap marker", "happyrider"),
				"description" => __("Insert new marker into Google map", "happyrider"),
				"class" => "trx_sc_collection trx_sc_googlemap_marker",
				'icon' => 'icon_trx_googlemap_marker',
				//"allowed_container_element" => 'vc_row',
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "address",
						"heading" => __("Address", "happyrider"),
						"description" => __("Address of this marker", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "latlng",
						"heading" => __("Latitude and Longtitude", "happyrider"),
						"description" => __("Comma separated marker's coorditanes (instead Address)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "happyrider"),
						"description" => __("Title for this marker", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "point",
						"heading" => __("URL for marker image file", "happyrider"),
						"description" => __("Select or upload image or write URL from other site for this marker. If empty - use default marker", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id']
				)
			) );
			
			class WPBakeryShortCode_Trx_Googlemap extends HAPPYRIDER_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Googlemap_Marker extends HAPPYRIDER_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			
			
			// Highlight
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_highlight",
				"name" => __("Highlight text", "happyrider"),
				"description" => __("Highlight text with selected color, background color and other styles", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_highlight',
				"class" => "trx_sc_single trx_sc_highlight",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => __("Type", "happyrider"),
						"description" => __("Highlight type", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Custom', 'happyrider') => 0,
								__('Type 1', 'happyrider') => 1,
								__('Type 2', 'happyrider') => 2,
								__('Type 3', 'happyrider') => 3,
								__('Type 4', 'happyrider') => 4
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Text color", "happyrider"),
						"description" => __("Color for the highlighted text", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "happyrider"),
						"description" => __("Background color for the highlighted text", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", "happyrider"),
						"description" => __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => __("Highlight text", "happyrider"),
						"description" => __("Content for highlight", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Highlight extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Icon
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_icon",
				"name" => __("Icon", "happyrider"),
				"description" => __("Insert the icon", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_icon',
				"class" => "trx_sc_single trx_sc_icon",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "icon",
						"heading" => __("Icon", "happyrider"),
						"description" => __("Select icon class from Fontello icons set", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Text color", "happyrider"),
						"description" => __("Icon's color", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "happyrider"),
						"description" => __("Background color for the icon", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_shape",
						"heading" => __("Background shape", "happyrider"),
						"description" => __("Shape of the icon background", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('None', 'happyrider') => 'none',
							__('Round', 'happyrider') => 'round',
							__('Square', 'happyrider') => 'square'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", "happyrider"),
						"description" => __("Icon's font size", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => __("Font weight", "happyrider"),
						"description" => __("Icon's font weight", "happyrider"),
						"class" => "",
						"value" => array(
							__('Default', 'happyrider') => 'inherit',
							__('Thin (100)', 'happyrider') => '100',
							__('Light (300)', 'happyrider') => '300',
							__('Normal (400)', 'happyrider') => '400',
							__('Bold (700)', 'happyrider') => '700'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Icon's alignment", "happyrider"),
						"description" => __("Align icon to left, center or right", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", "happyrider"),
						"description" => __("Link URL from this icon (if not empty)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				),
			) );
			
			class WPBakeryShortCode_Trx_Icon extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Image
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_image",
				"name" => __("Image", "happyrider"),
				"description" => __("Insert image", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_image',
				"class" => "trx_sc_single trx_sc_image",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => __("Select image", "happyrider"),
						"description" => __("Select image from library", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "align",
						"heading" => __("Image alignment", "happyrider"),
						"description" => __("Align image to left or right side", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "shape",
						"heading" => __("Image shape", "happyrider"),
						"description" => __("Shape of the image: square or round", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Square', 'happyrider') => 'square',
							__('Round', 'happyrider') => 'round'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "happyrider"),
						"description" => __("Image's title", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Title's icon", "happyrider"),
						"description" => __("Select icon for the title from Fontello icons set", "happyrider"),
						"class" => "",
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link", "happyrider"),
						"description" => __("The link URL from the image", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Image extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Infobox
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_infobox",
				"name" => __("Infobox", "happyrider"),
				"description" => __("Box with info or error message", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_infobox',
				"class" => "trx_sc_container trx_sc_infobox",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Style", "happyrider"),
						"description" => __("Infobox style", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Regular', 'happyrider') => 'regular',
								__('Info', 'happyrider') => 'info',
								__('Success', 'happyrider') => 'success',
								__('Error', 'happyrider') => 'error',
								__('Result', 'happyrider') => 'result',
								__('Warning', 'happyrider') => 'warning'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "closeable",
						"heading" => __("Closeable", "happyrider"),
						"description" => __("Create closeable box (with close button)", "happyrider"),
						"class" => "",
						"value" => array(__('Close button', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Custom icon", "happyrider"),
						"description" => __("Select icon for the infobox from Fontello icons set. If empty - use default icon", "happyrider"),
						"class" => "",
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Text color", "happyrider"),
						"description" => __("Any color for the text and headers", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "happyrider"),
						"description" => __("Any background color for this infobox", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Message text", "happyrider"),
						"description" => __("Message for the infobox", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Infobox extends HAPPYRIDER_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			
			// Line
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_line",
				"name" => __("Line", "happyrider"),
				"description" => __("Insert line (delimiter)", "happyrider"),
				"category" => __('Content', 'js_composer'),
				"class" => "trx_sc_single trx_sc_line",
				'icon' => 'icon_trx_line',
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Style", "happyrider"),
						"description" => __("Line style", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Solid', 'happyrider') => 'solid',
								__('Dashed', 'happyrider') => 'dashed',
								__('Dotted', 'happyrider') => 'dotted',
								__('Double', 'happyrider') => 'double',
								__('Shadow', 'happyrider') => 'shadow'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Line color", "happyrider"),
						"description" => __("Line color", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Line extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// List
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_list",
				"name" => __("List", "happyrider"),
				"description" => __("List items with specific bullets", "happyrider"),
				"category" => __('Content', 'js_composer'),
				"class" => "trx_sc_collection trx_sc_list",
				'icon' => 'icon_trx_list',
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_list_item'),
				"params" => array(
					array(
                        "style" => array(
                            "title" => __("Bullet's style", "happyrider"),
                            "desc" => __("Bullet's style for each list item", "happyrider"),
                            "value" => "ul",
                            "type" => "checklist",
                            "options" => $HAPPYRIDER_GLOBALS['sc_params']['list_styles']
                        ),
						"param_name" => "style",
						"heading" => __("Bullet's style", "happyrider"),
						"description" => __("Bullet's style for each list item", "happyrider"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['list_styles']),
						"type" => "dropdown"
					),
                    array(
                        "param_name" => "underlined",
                        "heading" => __("Underline", "happyrider"),
                        "description" => __("Underline for li", "happyrider"),
                        "type" => "checkbox"
                    ),
					array(
						"param_name" => "color",
						"heading" => __("Color", "happyrider"),
						"description" => __("List items color", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => __("List icon", "happyrider"),
						"description" => __("Select list icon from Fontello icons set (only for style=Iconed)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_color",
						"heading" => __("Icon color", "happyrider"),
						"description" => __("List icons color", "happyrider"),
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => "",
						"type" => "colorpicker"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_list_item]' . __( 'Item 1', 'happyrider' ) . '[/trx_list_item]
					[trx_list_item]' . __( 'Item 2', 'happyrider' ) . '[/trx_list_item]
				'
			) );
			
			
			vc_map( array(
				"base" => "trx_list_item",
				"name" => __("List item", "happyrider"),
				"description" => __("List item with specific bullet", "happyrider"),
				"class" => "trx_sc_single trx_sc_list_item",
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_list_item',
				"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"as_parent" => array('except' => 'trx_list'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("List item title", "happyrider"),
						"description" => __("Title for the current list item (show it as tooltip)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", "happyrider"),
						"description" => __("Link URL for the current list item", "happyrider"),
						"admin_label" => true,
						"group" => __('Link', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "target",
						"heading" => __("Link target", "happyrider"),
						"description" => __("Link target for the current list item", "happyrider"),
						"admin_label" => true,
						"group" => __('Link', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => __("Color", "happyrider"),
						"description" => __("Text color for this item", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => __("List item icon", "happyrider"),
						"description" => __("Select list item icon from Fontello icons set (only for style=Iconed)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_color",
						"heading" => __("Icon color", "happyrider"),
						"description" => __("Icon color for this item", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "content",
						"heading" => __("List item text", "happyrider"),
						"description" => __("Current list item content", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			
			) );
			
			class WPBakeryShortCode_Trx_List extends HAPPYRIDER_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_List_Item extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			
			
			// Number
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_number",
				"name" => __("Number", "happyrider"),
				"description" => __("Insert number or any word as set of separated characters", "happyrider"),
				"category" => __('Content', 'js_composer'),
				"class" => "trx_sc_single trx_sc_number",
				'icon' => 'icon_trx_number',
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "value",
						"heading" => __("Value", "happyrider"),
						"description" => __("Number or any word to separate", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Select block alignment", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Number extends HAPPYRIDER_VC_ShortCodeSingle {}


			
			
			
			
			
			// Parallax
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_parallax",
				"name" => __("Parallax", "happyrider"),
				"description" => __("Create the parallax container (with asinc background image)", "happyrider"),
				"category" => __('Structure', 'js_composer'),
				'icon' => 'icon_trx_parallax',
				"class" => "trx_sc_collection trx_sc_parallax",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "gap",
						"heading" => __("Create gap", "happyrider"),
						"description" => __("Create gap around parallax container (not need in fullscreen pages)", "happyrider"),
						"class" => "",
						"value" => array(__('Create gap', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "dir",
						"heading" => __("Direction", "happyrider"),
						"description" => __("Scroll direction for the parallax background", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Up', 'happyrider') => 'up',
								__('Down', 'happyrider') => 'down'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "speed",
						"heading" => __("Speed", "happyrider"),
						"description" => __("Parallax background motion speed (from 0.0 to 1.0)", "happyrider"),
						"class" => "",
						"value" => "0.3",
						"type" => "textfield"
					),
					array(
						"param_name" => "scheme",
						"heading" => __("Color scheme", "happyrider"),
						"description" => __("Select color scheme for this block", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Text color", "happyrider"),
						"description" => __("Select color for text object inside parallax block", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Backgroud color", "happyrider"),
						"description" => __("Select color for parallax background", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image", "happyrider"),
						"description" => __("Select or upload image or write URL from other site for the parallax background", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_image_x",
						"heading" => __("Image X position", "happyrider"),
						"description" => __("Parallax background X position (in percents)", "happyrider"),
						"class" => "",
						"value" => "50%",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_video",
						"heading" => __("Video background", "happyrider"),
						"description" => __("Paste URL for video file to show it as parallax background", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_video_ratio",
						"heading" => __("Video ratio", "happyrider"),
						"description" => __("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", "happyrider"),
						"class" => "",
						"value" => "16:9",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", "happyrider"),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", "happyrider"),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Content", "happyrider"),
						"description" => __("Content for the parallax container", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Parallax extends HAPPYRIDER_VC_ShortCodeCollection {}
			
			
			
			
			
			
			// Popup
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_popup",
				"name" => __("Popup window", "happyrider"),
				"description" => __("Container for any html-block with desired class and style for popup window", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_popup',
				"class" => "trx_sc_collection trx_sc_popup",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					/*
					array(
						"param_name" => "content",
						"heading" => __("Container content", "happyrider"),
						"description" => __("Content for popup container", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Popup extends HAPPYRIDER_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Price
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_price",
				"name" => __("Price", "happyrider"),
				"description" => __("Insert price with decoration", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_price',
				"class" => "trx_sc_single trx_sc_price",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "money",
						"heading" => __("Money", "happyrider"),
						"description" => __("Money value (dot or comma separated)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => __("Currency symbol", "happyrider"),
						"description" => __("Currency character", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "$",
						"type" => "textfield"
					),
					array(
						"param_name" => "period",
						"heading" => __("Period", "happyrider"),
						"description" => __("Period text (if need). For example: monthly, daily, etc.", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Align price to left or right side", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Price extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Price block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_price_block",
				"name" => __("Price block", "happyrider"),
				"description" => __("Insert price block with title, price and description", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_price_block',
				"class" => "trx_sc_single trx_sc_price_block",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Title", "happyrider"),
						"description" => __("Block title", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", "happyrider"),
						"description" => __("URL for link from button (at bottom of the block)", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_text",
						"heading" => __("Link text", "happyrider"),
						"description" => __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom gb style", "happyrider"),
						"description" => __("Add custom style", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Icon", "happyrider"),
						"description" => __("Select icon from Fontello icons set (placed before/instead price)", "happyrider"),
						"class" => "",
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "money",
						"heading" => __("Money", "happyrider"),
						"description" => __("Money value (dot or comma separated)", "happyrider"),
						"admin_label" => true,
						"group" => __('Money', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => __("Currency symbol", "happyrider"),
						"description" => __("Currency character", "happyrider"),
						"admin_label" => true,
						"group" => __('Money', 'happyrider'),
						"class" => "",
						"value" => "$",
						"type" => "textfield"
					),
					array(
						"param_name" => "period",
						"heading" => __("Period", "happyrider"),
						"description" => __("Period text (if need). For example: monthly, daily, etc.", "happyrider"),
						"admin_label" => true,
						"group" => __('Money', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "scheme",
						"heading" => __("Color scheme", "happyrider"),
						"description" => __("Select color scheme for this block", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Align price to left or right side", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => __("Description", "happyrider"),
						"description" => __("Description for this price block", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_PriceBlock extends HAPPYRIDER_VC_ShortCodeSingle {}

			
			
			
			
			// Quote
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_quote",
				"name" => __("Quote", "happyrider"),
				"description" => __("Quote text", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_quote',
				"class" => "trx_sc_single trx_sc_quote",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
                        "param_name" => "style",
                        "heading" => __("Style", "happyrider"),
                        "description" => __("Quote style", "happyrider"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array_flip(happyrider_get_list_styles(1, 2)),
                        "type" => "dropdown"
                    ),
                    array(
						"param_name" => "bg_image",
						"heading" => __("Background image", "happyrider"),
						"description" => __("Select or upload image or write URL from other site for the quote - style 1 background", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "cite",
						"heading" => __("Quote cite", "happyrider"),
						"description" => __("URL for the quote cite link", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title (author)", "happyrider"),
						"description" => __("Quote title (author name)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					 array(
						"param_name" => "author_photo",
						"heading" => __("Author photo", "happyrider"),
						"description" => __("Select or upload image or write URL for author photo", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "content",
						"heading" => __("Quote content", "happyrider"),
						"description" => __("Quote content", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Quote extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Reviews
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_reviews",
				"name" => __("Reviews", "happyrider"),
				"description" => __("Insert reviews block in the single post", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_reviews',
				"class" => "trx_sc_single trx_sc_reviews",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Align counter to left, center or right", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Reviews extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Search
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_search",
				"name" => __("Search form", "happyrider"),
				"description" => __("Insert search form", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_search',
				"class" => "trx_sc_single trx_sc_search",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Style", "happyrider"),
						"description" => __("Select style to display search field", "happyrider"),
						"class" => "",
						"value" => array(
							__('Regular', 'happyrider') => "regular",
							__('Flat', 'happyrider') => "flat"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "state",
						"heading" => __("State", "happyrider"),
						"description" => __("Select search field initial state", "happyrider"),
						"class" => "",
						"value" => array(
							__('Fixed', 'happyrider')  => "fixed",
							__('Opened', 'happyrider') => "opened",
							__('Closed', 'happyrider') => "closed"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "happyrider"),
						"description" => __("Title (placeholder) for the search field", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => __("Search &hellip;", 'happyrider'),
						"type" => "textfield"
					),
					array(
						"param_name" => "ajax",
						"heading" => __("AJAX", "happyrider"),
						"description" => __("Search via AJAX or reload page", "happyrider"),
						"class" => "",
						"value" => array(__('Use AJAX search', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Search extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Section
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_section",
				"name" => __("Section container", "happyrider"),
				"description" => __("Container for any block ([block] analog - to enable nesting)", "happyrider"),
				"category" => __('Content', 'js_composer'),
				"class" => "trx_sc_collection trx_sc_section",
				'icon' => 'icon_trx_block',
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "dedicated",
						"heading" => __("Dedicated", "happyrider"),
						"description" => __("Use this block as dedicated content - show it before post title on single page", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Use as dedicated content', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Select block alignment", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns emulation", "happyrider"),
						"description" => __("Select width for columns emulation", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['columns']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "pan",
						"heading" => __("Use pan effect", "happyrider"),
						"description" => __("Use pan effect to show section content", "happyrider"),
						"group" => __('Scroll', 'happyrider'),
						"class" => "",
						"value" => array(__('Content scroller', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll",
						"heading" => __("Use scroller", "happyrider"),
						"description" => __("Use scroller to show section content", "happyrider"),
						"group" => __('Scroll', 'happyrider'),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Content scroller', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll_dir",
						"heading" => __("Scroll and Pan direction", "happyrider"),
						"description" => __("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"group" => __('Scroll', 'happyrider'),
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll_controls",
						"heading" => __("Scroll controls", "happyrider"),
						"description" => __("Show scroll controls (if Use scroller = yes)", "happyrider"),
						"class" => "",
						"group" => __('Scroll', 'happyrider'),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"value" => array(__('Show scroll controls', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scheme",
						"heading" => __("Color scheme", "happyrider"),
						"description" => __("Select color scheme for this block", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Fore color", "happyrider"),
						"description" => __("Any color for objects in this section", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "happyrider"),
						"description" => __("Any background color for this section", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image URL", "happyrider"),
						"description" => __("Select background image from library for this section", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_tile",
						"heading" => __("Tile background image", "happyrider"),
						"description" => __("Do you want tile background image or image cover whole block?", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						'dependency' => array(
							'element' => 'bg_image',
							'not_empty' => true
						),
						"std" => "no",
						"value" => array(__('Tile background image', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", "happyrider"),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", "happyrider"),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", "happyrider"),
						"description" => __("Font size of the text (default - in pixels, allows any CSS units of measure)", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => __("Font weight", "happyrider"),
						"description" => __("Font weight of the text", "happyrider"),
						"class" => "",
						"value" => array(
							__('Default', 'happyrider') => 'inherit',
							__('Thin (100)', 'happyrider') => '100',
							__('Light (300)', 'happyrider') => '300',
							__('Normal (400)', 'happyrider') => '400',
							__('Bold (700)', 'happyrider') => '700'
						),
						"type" => "dropdown"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => __("Container content", "happyrider"),
						"description" => __("Content for section container", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Section extends HAPPYRIDER_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Skills
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_skills",
				"name" => __("Skills", "happyrider"),
				"description" => __("Insert skills diagramm", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_skills',
				"class" => "trx_sc_collection trx_sc_skills",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_skills_item'),
				"params" => array(
					array(
						"param_name" => "max_value",
						"heading" => __("Max value", "happyrider"),
						"description" => __("Max value for skills items", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "100",
						"type" => "textfield"
					),
					array(
						"param_name" => "type",
						"heading" => __("Skills type", "happyrider"),
						"description" => __("Select type of skills block", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Bar', 'happyrider') => 'bar',
							__('Pie chart', 'happyrider') => 'pie',
							__('Counter', 'happyrider') => 'counter',
							__('Arc', 'happyrider') => 'arc'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "layout",
						"heading" => __("Skills layout", "happyrider"),
						"description" => __("Select layout of skills block", "happyrider"),
						"admin_label" => true,
						'dependency' => array(
							'element' => 'type',
							'value' => array('counter','bar','pie')
						),
						"class" => "",
						"value" => array(
							__('Rows', 'happyrider') => 'rows',
							__('Columns', 'happyrider') => 'columns'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "dir",
						"heading" => __("Direction", "happyrider"),
						"description" => __("Select direction of skills block", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => __("Counters style", "happyrider"),
						"description" => __("Select style of skills items (only for type=counter)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(happyrider_get_list_styles(1, 4)),
						'dependency' => array(
							'element' => 'type',
							'value' => array('counter')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns count", "happyrider"),
						"description" => __("Skills columns count (required)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => __("Color", "happyrider"),
						"description" => __("Color for all skills items", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "happyrider"),
						"description" => __("Background color for all skills items (only for type=pie)", "happyrider"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "border_color",
						"heading" => __("Border color", "happyrider"),
						"description" => __("Border color for all skills items (only for type=pie)", "happyrider"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Align skills block to left or right side", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "arc_caption",
						"heading" => __("Arc caption", "happyrider"),
						"description" => __("Arc caption - text in the center of the diagram", "happyrider"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('arc')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "pie_compact",
						"heading" => __("Pie compact", "happyrider"),
						"description" => __("Show all skills in one diagram or as separate diagrams", "happyrider"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => array(__('Show all skills in one diagram', 'happyrider') => 'on'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "pie_cutout",
						"heading" => __("Pie cutout", "happyrider"),
						"description" => __("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", "happyrider"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "happyrider"),
						"description" => __("Title for the block", "happyrider"),
						"admin_label" => true,
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => __("Subtitle", "happyrider"),
						"description" => __("Subtitle for the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => __("Description", "happyrider"),
						"description" => __("Description for the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "link",
						"heading" => __("Button URL", "happyrider"),
						"description" => __("Link URL for the button at the bottom of the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => __("Button caption", "happyrider"),
						"description" => __("Caption for the button at the bottom of the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_skills_item",
				"name" => __("Skill", "happyrider"),
				"description" => __("Skills item", "happyrider"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_single trx_sc_skills_item",
				"content_element" => true,
				"is_container" => false,
				"as_child" => array('only' => 'trx_skills'),
				"as_parent" => array('except' => 'trx_skills'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Title", "happyrider"),
						"description" => __("Title for the current skills item", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "value",
						"heading" => __("Value", "happyrider"),
						"description" => __("Value for the current skills item", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => __("Color", "happyrider"),
						"description" => __("Color for current skills item", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "happyrider"),
						"description" => __("Background color for current skills item (only for type=pie)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "border_color",
						"heading" => __("Border color", "happyrider"),
						"description" => __("Border color for current skills item (only for type=pie)", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "style",
						"heading" => __("Counter style", "happyrider"),
						"description" => __("Select style for the current skills item (only for type=counter)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(happyrider_get_list_styles(1, 4)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Counter icon", "happyrider"),
						"description" => __("Select icon from Fontello icons set, placed before counter (only for type=counter)", "happyrider"),
						"class" => "",
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Skills extends HAPPYRIDER_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Skills_Item extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Slider
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_slider",
				"name" => __("Slider", "happyrider"),
				"description" => __("Insert slider", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_slider',
				"class" => "trx_sc_collection trx_sc_slider",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_slider_item'),
				"params" => array_merge(array(
					array(
						"param_name" => "engine",
						"heading" => __("Engine", "happyrider"),
						"description" => __("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['sliders']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Float slider", "happyrider"),
						"description" => __("Float slider to left or right side", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom slides", "happyrider"),
						"description" => __("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", "happyrider"),
						"class" => "",
						"value" => array(__('Custom slides', 'happyrider') => 'yes'),
						"type" => "checkbox"
					)
					),
					happyrider_exists_revslider() ? array(
					array(
						"param_name" => "alias",
						"heading" => __("Revolution slider alias", "happyrider"),
						"description" => __("Select Revolution slider to display", "happyrider"),
						"admin_label" => true,
						"class" => "",
						'dependency' => array(
							'element' => 'engine',
							'value' => array('revo')
						),
						"value" => array_flip(happyrider_array_merge(array('none' => __('- Select slider -', 'happyrider')), $HAPPYRIDER_GLOBALS['sc_params']['revo_sliders'])),
						"type" => "dropdown"
					)) : array(), array(
					array(
						"param_name" => "cat",
						"heading" => __("Categories list", "happyrider"),
						"description" => __("Select category. If empty - show posts from any category or from IDs list", "happyrider"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip(happyrider_array_merge(array(0 => __('- Select category -', 'happyrider')), $HAPPYRIDER_GLOBALS['sc_params']['categories'])),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => __("Swiper: Number of posts", "happyrider"),
						"description" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "happyrider"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => __("Swiper: Offset before select posts", "happyrider"),
						"description" => __("Skip posts before select next part.", "happyrider"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => __("Swiper: Post sorting", "happyrider"),
						"description" => __("Select desired posts sorting method", "happyrider"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => __("Swiper: Post order", "happyrider"),
						"description" => __("Select desired posts order", "happyrider"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => __("Swiper: Post IDs list", "happyrider"),
						"description" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "happyrider"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => __("Swiper: Show slider controls", "happyrider"),
						"description" => __("Show arrows inside slider", "happyrider"),
						"group" => __('Details', 'happyrider'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Show controls', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "pagination",
						"heading" => __("Swiper: Show slider pagination", "happyrider"),
						"description" => __("Show bullets or titles to switch slides", "happyrider"),
						"group" => __('Details', 'happyrider'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"std" => "no",
						"value" => array(
								__('None', 'happyrider') => 'no',
								__('Dots', 'happyrider') => 'yes',
								__('Side Titles', 'happyrider') => 'full',
								__('Over Titles', 'happyrider') => 'over'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "titles",
						"heading" => __("Swiper: Show titles section", "happyrider"),
						"description" => __("Show section with post's title and short post's description", "happyrider"),
						"group" => __('Details', 'happyrider'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(
								__('Not show', 'happyrider') => "no",
								__('Show/Hide info', 'happyrider') => "slide",
								__('Fixed info', 'happyrider') => "fixed"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "descriptions",
						"heading" => __("Swiper: Post descriptions", "happyrider"),
						"description" => __("Show post's excerpt max length (characters)", "happyrider"),
						"group" => __('Details', 'happyrider'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "links",
						"heading" => __("Swiper: Post's title as link", "happyrider"),
						"description" => __("Make links from post's titles", "happyrider"),
						"group" => __('Details', 'happyrider'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Titles as a links', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "crop",
						"heading" => __("Swiper: Crop images", "happyrider"),
						"description" => __("Crop images in each slide or live it unchanged", "happyrider"),
						"group" => __('Details', 'happyrider'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Crop images', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "autoheight",
						"heading" => __("Swiper: Autoheight", "happyrider"),
						"description" => __("Change whole slider's height (make it equal current slide's height)", "happyrider"),
						"group" => __('Details', 'happyrider'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Autoheight', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "slides_per_view",
						"heading" => __("Swiper: Slides per view", "happyrider"),
						"description" => __("Slides per view showed in this slider", "happyrider"),
						"admin_label" => true,
						"group" => __('Details', 'happyrider'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "slides_space",
						"heading" => __("Swiper: Space between slides", "happyrider"),
						"description" => __("Size of space (in px) between slides", "happyrider"),
						"admin_label" => true,
						"group" => __('Details', 'happyrider'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => __("Swiper: Slides change interval", "happyrider"),
						"description" => __("Slides change interval (in milliseconds: 1000ms = 1s)", "happyrider"),
						"group" => __('Details', 'happyrider'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "5000",
						"type" => "textfield"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				))
			) );
			
			
			vc_map( array(
				"base" => "trx_slider_item",
				"name" => __("Slide", "happyrider"),
				"description" => __("Slider item - single slide", "happyrider"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_slider_item',
				"as_child" => array('only' => 'trx_slider'),
				"as_parent" => array('except' => 'trx_slider'),
				"params" => array(
					array(
						"param_name" => "src",
						"heading" => __("URL (source) for image file", "happyrider"),
						"description" => __("Select or upload image or write URL from other site for the current slide", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Slider extends HAPPYRIDER_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Slider_Item extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Socials
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_socials",
				"name" => __("Social icons", "happyrider"),
				"description" => __("Custom social icons", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_socials',
				"class" => "trx_sc_collection trx_sc_socials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_social_item'),
				"params" => array_merge(array(
					array(
						"param_name" => "type",
						"heading" => __("Icon's type", "happyrider"),
						"description" => __("Type of the icons - images or font icons", "happyrider"),
						"class" => "",
						"std" => happyrider_get_theme_setting('socials_type'),
						"value" => array(
							__('Icons', 'happyrider') => 'icons',
							__('Images', 'happyrider') => 'images'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "size",
						"heading" => __("Icon's size", "happyrider"),
						"description" => __("Size of the icons", "happyrider"),
						"class" => "",
						"std" => "small",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['sizes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "shape",
						"heading" => __("Icon's shape", "happyrider"),
						"description" => __("Shape of the icons", "happyrider"),
						"class" => "",
						"std" => "square",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['shapes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "socials",
						"heading" => __("Manual socials list", "happyrider"),
						"description" => __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebooc.com/my_profile. If empty - use socials from Theme options.", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom socials", "happyrider"),
						"description" => __("Make custom icons from inner shortcodes (prepare it on tabs)", "happyrider"),
						"class" => "",
						"value" => array(__('Custom socials', 'happyrider') => 'yes'),
						"type" => "checkbox"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				))
			) );
			
			
			vc_map( array(
				"base" => "trx_social_item",
				"name" => __("Custom social item", "happyrider"),
				"description" => __("Custom social item: name, profile url and icon url", "happyrider"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_social_item',
				"as_child" => array('only' => 'trx_socials'),
				"as_parent" => array('except' => 'trx_socials'),
				"params" => array(
					array(
						"param_name" => "name",
						"heading" => __("Social name", "happyrider"),
						"description" => __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "url",
						"heading" => __("Your profile URL", "happyrider"),
						"description" => __("URL of your profile in specified social network", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => __("URL (source) for icon file", "happyrider"),
						"description" => __("Select or upload image or write URL from other site for the current social icon", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					)
				)
			) );
			
			class WPBakeryShortCode_Trx_Socials extends HAPPYRIDER_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Social_Item extends HAPPYRIDER_VC_ShortCodeSingle {}
			

			
			
			
			
			
			// Table
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_table",
				"name" => __("Table", "happyrider"),
				"description" => __("Insert a table", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_table',
				"class" => "trx_sc_container trx_sc_table",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "align",
						"heading" => __("Cells content alignment", "happyrider"),
						"description" => __("Select alignment for each table cell", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => __("Table content", "happyrider"),
						"description" => __("Content, created with any table-generator", "happyrider"),
						"class" => "",
						"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
						"type" => "textarea_html"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Table extends HAPPYRIDER_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Tabs
			//-------------------------------------------------------------------------------------
			
			$tab_id_1 = 'sc_tab_'.time() . '_1_' . rand( 0, 100 );
			$tab_id_2 = 'sc_tab_'.time() . '_2_' . rand( 0, 100 );
			vc_map( array(
				"base" => "trx_tabs",
				"name" => __("Tabs", "happyrider"),
				"description" => __("Tabs", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_tabs',
				"class" => "trx_sc_collection trx_sc_tabs",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_tab'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Tabs style", "happyrider"),
						"description" => __("Select style of tabs items", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(happyrider_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "initial",
						"heading" => __("Initially opened tab", "happyrider"),
						"description" => __("Number of initially opened tab", "happyrider"),
						"class" => "",
						"value" => 1,
						"type" => "textfield"
					),
					array(
						"param_name" => "scroll",
						"heading" => __("Scroller", "happyrider"),
						"description" => __("Use scroller to show tab content (height parameter required)", "happyrider"),
						"class" => "",
						"value" => array("Use scroller" => "yes" ),
						"type" => "checkbox"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_tab title="' . __( 'Tab 1', 'happyrider' ) . '" tab_id="'.esc_attr($tab_id_1).'"][/trx_tab]
					[trx_tab title="' . __( 'Tab 2', 'happyrider' ) . '" tab_id="'.esc_attr($tab_id_2).'"][/trx_tab]
				',
				"custom_markup" => '
					<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
						<ul class="tabs_controls">
						</ul>
						%content%
					</div>
				',
				'js_view' => 'VcTrxTabsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_tab",
				"name" => __("Tab item", "happyrider"),
				"description" => __("Single tab item", "happyrider"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_tab",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_tab',
				"as_child" => array('only' => 'trx_tabs'),
				"as_parent" => array('except' => 'trx_tabs'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Tab title", "happyrider"),
						"description" => __("Title for current tab", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "tab_id",
						"heading" => __("Tab ID", "happyrider"),
						"description" => __("ID for current tab (required). Please, start it from letter.", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['css']
				),
			  'js_view' => 'VcTrxTabView'
			) );
			class WPBakeryShortCode_Trx_Tabs extends HAPPYRIDER_VC_ShortCodeTabs {}
			class WPBakeryShortCode_Trx_Tab extends HAPPYRIDER_VC_ShortCodeTab {}
			
			
			
			
			
			
			
			// Title
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_title",
				"name" => __("Title", "happyrider"),
				"description" => __("Create header tag (1-6 level) with many styles", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_title',
				"class" => "trx_sc_single trx_sc_title",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => __("Title content", "happyrider"),
						"description" => __("Title content", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					array(
						"param_name" => "type",
						"heading" => __("Title type", "happyrider"),
						"description" => __("Title type (header level)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Header 1', 'happyrider') => '1',
							__('Header 2', 'happyrider') => '2',
							__('Header 3', 'happyrider') => '3',
							__('Header 4', 'happyrider') => '4',
							__('Header 5', 'happyrider') => '5',
							__('Header 6', 'happyrider') => '6'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => __("Title style", "happyrider"),
						"description" => __("Title style: only text (regular) or with icon/image (iconed)", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							
							__('Regular', 'happyrider') => 'regular',
							__('Underline', 'happyrider') => 'underline',
							__('Custom', 'happyrider') => 'custom',
							__('Standart', 'happyrider') => 'standart',
							__('Divider', 'happyrider') => 'divider',
							__('With icon (image)', 'happyrider') => 'iconed'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Title text alignment", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", "happyrider"),
						"description" => __("Custom font size. If empty - use theme default", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => __("Font weight", "happyrider"),
						"description" => __("Custom font weight. If empty or inherit - use theme default", "happyrider"),
						"class" => "",
						"value" => array(
							__('Default', 'happyrider') => 'inherit',
							__('Thin (100)', 'happyrider') => '100',
							__('Light (300)', 'happyrider') => '300',
							__('Normal (400)', 'happyrider') => '400',
							__('Semibold (600)', 'happyrider') => '600',
							__('Bold (700)', 'happyrider') => '700',
							__('Black (900)', 'happyrider') => '900'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Title color", "happyrider"),
						"description" => __("Select color for the title", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Title font icon", "happyrider"),
						"description" => __("Select font icon for the title from Fontello icons set (if style=iconed)", "happyrider"),
						"class" => "",
						"group" => __('Icon &amp; Image', 'happyrider'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => __("or image icon", "happyrider"),
						"description" => __("Select image icon for the title instead icon above (if style=iconed)", "happyrider"),
						"class" => "",
						"group" => __('Icon &amp; Image', 'happyrider'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['images'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "picture",
						"heading" => __("or select uploaded image", "happyrider"),
						"description" => __("Select or upload image or write URL from other site (if style=iconed)", "happyrider"),
						"group" => __('Icon &amp; Image', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "image_size",
						"heading" => __("Image (picture) size", "happyrider"),
						"description" => __("Select image (picture) size (if style=iconed)", "happyrider"),
						"group" => __('Icon &amp; Image', 'happyrider'),
						"class" => "",
						"value" => array(
							__('Small', 'happyrider') => 'small',
							__('Medium', 'happyrider') => 'medium',
							__('Large', 'happyrider') => 'large'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "position",
						"heading" => __("Icon (image) position", "happyrider"),
						"description" => __("Select icon (image) position (if style=iconed)", "happyrider"),
						"group" => __('Icon &amp; Image', 'happyrider'),
						"class" => "",
						"value" => array(
							__('Top', 'happyrider') => 'top',
							__('Left', 'happyrider') => 'left'
						),
						"type" => "dropdown"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Title extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Toggles
			//-------------------------------------------------------------------------------------
				
			vc_map( array(
				"base" => "trx_toggles",
				"name" => __("Toggles", "happyrider"),
				"description" => __("Toggles items", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_toggles',
				"class" => "trx_sc_collection trx_sc_toggles",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_toggles_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Toggles style", "happyrider"),
						"description" => __("Select style for display toggles", "happyrider"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip(happyrider_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "counter",
						"heading" => __("Counter", "happyrider"),
						"description" => __("Display counter before each toggles title", "happyrider"),
						"class" => "",
						"value" => array("Add item numbers before each element" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => __("Icon while closed", "happyrider"),
						"description" => __("Select icon for the closed toggles item from Fontello icons set", "happyrider"),
						"class" => "",
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => __("Icon while opened", "happyrider"),
						"description" => __("Select icon for the opened toggles item from Fontello icons set", "happyrider"),
						"class" => "",
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_toggles_item title="' . __( 'Item 1 title', 'happyrider' ) . '"][/trx_toggles_item]
					[trx_toggles_item title="' . __( 'Item 2 title', 'happyrider' ) . '"][/trx_toggles_item]
				',
				"custom_markup" => '
					<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
						%content%
					</div>
					<div class="tab_controls">
						<button class="add_tab" title="'.__("Add item", "happyrider").'">'.__("Add item", "happyrider").'</button>
					</div>
				',
				'js_view' => 'VcTrxTogglesView'
			) );
			
			
			vc_map( array(
				"base" => "trx_toggles_item",
				"name" => __("Toggles item", "happyrider"),
				"description" => __("Single toggles item", "happyrider"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_toggles_item',
				"as_child" => array('only' => 'trx_toggles'),
				"as_parent" => array('except' => 'trx_toggles'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Title", "happyrider"),
						"description" => __("Title for current toggles item", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "open",
						"heading" => __("Open on show", "happyrider"),
						"description" => __("Open current toggle item on show", "happyrider"),
						"class" => "",
						"value" => array("Opened" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => __("Icon while closed", "happyrider"),
						"description" => __("Select icon for the closed toggles item from Fontello icons set", "happyrider"),
						"class" => "",
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => __("Icon while opened", "happyrider"),
						"description" => __("Select icon for the opened toggles item from Fontello icons set", "happyrider"),
						"class" => "",
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTogglesTabView'
			) );
			class WPBakeryShortCode_Trx_Toggles extends HAPPYRIDER_VC_ShortCodeToggles {}
			class WPBakeryShortCode_Trx_Toggles_Item extends HAPPYRIDER_VC_ShortCodeTogglesItem {}
			
			
			
			
			
			
			// Twitter
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "trx_twitter",
				"name" => __("Twitter", "happyrider"),
				"description" => __("Insert twitter feed into post (page)", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_twitter',
				"class" => "trx_sc_single trx_sc_twitter",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "user",
						"heading" => __("Twitter Username", "happyrider"),
						"description" => __("Your username in the twitter account. If empty - get it from Theme Options.", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_key",
						"heading" => __("Consumer Key", "happyrider"),
						"description" => __("Consumer Key from the twitter account", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_secret",
						"heading" => __("Consumer Secret", "happyrider"),
						"description" => __("Consumer Secret from the twitter account", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_key",
						"heading" => __("Token Key", "happyrider"),
						"description" => __("Token Key from the twitter account", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_secret",
						"heading" => __("Token Secret", "happyrider"),
						"description" => __("Token Secret from the twitter account", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => __("Tweets number", "happyrider"),
						"description" => __("Number tweets to show", "happyrider"),
						"class" => "",
						"divider" => true,
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => __("Show arrows", "happyrider"),
						"description" => __("Show control buttons", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "interval",
						"heading" => __("Tweets change interval", "happyrider"),
						"description" => __("Tweets change interval (in milliseconds: 1000ms = 1s)", "happyrider"),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Alignment of the tweets block", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoheight",
						"heading" => __("Autoheight", "happyrider"),
						"description" => __("Change whole slider's height (make it equal current slide's height)", "happyrider"),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scheme",
						"heading" => __("Color scheme", "happyrider"),
						"description" => __("Select color scheme for this block", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "happyrider"),
						"description" => __("Any background color for this section", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image URL", "happyrider"),
						"description" => __("Select background image from library for this section", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", "happyrider"),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", "happyrider"),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				),
			) );
			
			class WPBakeryShortCode_Trx_Twitter extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Video
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_video",
				"name" => __("Video", "happyrider"),
				"description" => __("Insert video player", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_video',
				"class" => "trx_sc_single trx_sc_video",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => __("URL for video file", "happyrider"),
						"description" => __("Paste URL for video file", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "ratio",
						"heading" => __("Ratio", "happyrider"),
						"description" => __("Select ratio for display video", "happyrider"),
						"class" => "",
						"value" => array(
							__('16:9', 'happyrider') => "16:9",
							__('4:3', 'happyrider') => "4:3"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoplay",
						"heading" => __("Autoplay video", "happyrider"),
						"description" => __("Autoplay video on page load", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array("Autoplay" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Select block alignment", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => __("Cover image", "happyrider"),
						"description" => __("Select or upload image or write URL from other site for video preview", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image", "happyrider"),
						"description" => __("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "happyrider"),
						"group" => __('Background', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_top",
						"heading" => __("Top offset", "happyrider"),
						"description" => __("Top offset (padding) from background image to video block (in percent). For example: 3%", "happyrider"),
						"group" => __('Background', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_bottom",
						"heading" => __("Bottom offset", "happyrider"),
						"description" => __("Bottom offset (padding) from background image to video block (in percent). For example: 3%", "happyrider"),
						"group" => __('Background', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_left",
						"heading" => __("Left offset", "happyrider"),
						"description" => __("Left offset (padding) from background image to video block (in percent). For example: 20%", "happyrider"),
						"group" => __('Background', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_right",
						"heading" => __("Right offset", "happyrider"),
						"description" => __("Right offset (padding) from background image to video block (in percent). For example: 12%", "happyrider"),
						"group" => __('Background', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Video extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Zoom
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_zoom",
				"name" => __("Zoom", "happyrider"),
				"description" => __("Insert the image with zoom/lens effect", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_zoom',
				"class" => "trx_sc_single trx_sc_zoom",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "effect",
						"heading" => __("Effect", "happyrider"),
						"description" => __("Select effect to display overlapping image", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"std" => "zoom",
						"value" => array(
							__('Lens', 'happyrider') => 'lens',
							__('Zoom', 'happyrider') => 'zoom'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "url",
						"heading" => __("Main image", "happyrider"),
						"description" => __("Select or upload main image", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "over",
						"heading" => __("Overlaping image", "happyrider"),
						"description" => __("Select or upload overlaping image", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Float zoom to left or right side", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image", "happyrider"),
						"description" => __("Select or upload image or write URL from other site for zoom background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "happyrider"),
						"group" => __('Background', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_top",
						"heading" => __("Top offset", "happyrider"),
						"description" => __("Top offset (padding) from background image to zoom block (in percent). For example: 3%", "happyrider"),
						"group" => __('Background', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_bottom",
						"heading" => __("Bottom offset", "happyrider"),
						"description" => __("Bottom offset (padding) from background image to zoom block (in percent). For example: 3%", "happyrider"),
						"group" => __('Background', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_left",
						"heading" => __("Left offset", "happyrider"),
						"description" => __("Left offset (padding) from background image to zoom block (in percent). For example: 20%", "happyrider"),
						"group" => __('Background', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_right",
						"heading" => __("Right offset", "happyrider"),
						"description" => __("Right offset (padding) from background image to zoom block (in percent). For example: 12%", "happyrider"),
						"group" => __('Background', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css'],
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Zoom extends HAPPYRIDER_VC_ShortCodeSingle {}
			

			do_action('happyrider_action_shortcodes_list_vc');
			
			
			if (false && happyrider_exists_woocommerce()) {
			
				// WooCommerce - Cart
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_cart",
					"name" => __("Cart", "happyrider"),
					"description" => __("WooCommerce shortcode: show cart page", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_wooc_cart',
					"class" => "trx_sc_alone trx_sc_woocommerce_cart",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array(
						array(
							"param_name" => "dummy",
							"heading" => __("Dummy data", "happyrider"),
							"description" => __("Dummy data - not used in shortcodes", "happyrider"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Woocommerce_Cart extends HAPPYRIDER_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Checkout
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_checkout",
					"name" => __("Checkout", "happyrider"),
					"description" => __("WooCommerce shortcode: show checkout page", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_wooc_checkout',
					"class" => "trx_sc_alone trx_sc_woocommerce_checkout",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array(
						array(
							"param_name" => "dummy",
							"heading" => __("Dummy data", "happyrider"),
							"description" => __("Dummy data - not used in shortcodes", "happyrider"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Woocommerce_Checkout extends HAPPYRIDER_VC_ShortCodeAlone {}
			
			
				// WooCommerce - My Account
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_my_account",
					"name" => __("My Account", "happyrider"),
					"description" => __("WooCommerce shortcode: show my account page", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_wooc_my_account',
					"class" => "trx_sc_alone trx_sc_woocommerce_my_account",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array(
						array(
							"param_name" => "dummy",
							"heading" => __("Dummy data", "happyrider"),
							"description" => __("Dummy data - not used in shortcodes", "happyrider"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Woocommerce_My_Account extends HAPPYRIDER_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Order Tracking
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_order_tracking",
					"name" => __("Order Tracking", "happyrider"),
					"description" => __("WooCommerce shortcode: show order tracking page", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_wooc_order_tracking',
					"class" => "trx_sc_alone trx_sc_woocommerce_order_tracking",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array(
						array(
							"param_name" => "dummy",
							"heading" => __("Dummy data", "happyrider"),
							"description" => __("Dummy data - not used in shortcodes", "happyrider"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Woocommerce_Order_Tracking extends HAPPYRIDER_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Shop Messages
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "shop_messages",
					"name" => __("Shop Messages", "happyrider"),
					"description" => __("WooCommerce shortcode: show shop messages", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_wooc_shop_messages',
					"class" => "trx_sc_alone trx_sc_shop_messages",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array(
						array(
							"param_name" => "dummy",
							"heading" => __("Dummy data", "happyrider"),
							"description" => __("Dummy data - not used in shortcodes", "happyrider"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Shop_Messages extends HAPPYRIDER_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Product Page
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_page",
					"name" => __("Product Page", "happyrider"),
					"description" => __("WooCommerce shortcode: display single product page", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_product_page',
					"class" => "trx_sc_single trx_sc_product_page",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "sku",
							"heading" => __("SKU", "happyrider"),
							"description" => __("SKU code of displayed product", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "id",
							"heading" => __("ID", "happyrider"),
							"description" => __("ID of displayed product", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "posts_per_page",
							"heading" => __("Number", "happyrider"),
							"description" => __("How many products showed", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "post_type",
							"heading" => __("Post type", "happyrider"),
							"description" => __("Post type for the WP query (leave 'product')", "happyrider"),
							"class" => "",
							"value" => "product",
							"type" => "textfield"
						),
						array(
							"param_name" => "post_status",
							"heading" => __("Post status", "happyrider"),
							"description" => __("Display posts only with this status", "happyrider"),
							"class" => "",
							"value" => array(
								__('Publish', 'happyrider') => 'publish',
								__('Protected', 'happyrider') => 'protected',
								__('Private', 'happyrider') => 'private',
								__('Pending', 'happyrider') => 'pending',
								__('Draft', 'happyrider') => 'draft'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Page extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Product
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product",
					"name" => __("Product", "happyrider"),
					"description" => __("WooCommerce shortcode: display one product", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_product',
					"class" => "trx_sc_single trx_sc_product",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "sku",
							"heading" => __("SKU", "happyrider"),
							"description" => __("Product's SKU code", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "id",
							"heading" => __("ID", "happyrider"),
							"description" => __("Product's ID", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Product extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
				// WooCommerce - Best Selling Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "best_selling_products",
					"name" => __("Best Selling Products", "happyrider"),
					"description" => __("WooCommerce shortcode: show best selling products", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_best_selling_products',
					"class" => "trx_sc_single trx_sc_best_selling_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "happyrider"),
							"description" => __("How many products showed", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "happyrider"),
							"description" => __("How many columns per row use for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Best_Selling_Products extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Recent Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "recent_products",
					"name" => __("Recent Products", "happyrider"),
					"description" => __("WooCommerce shortcode: show recent products", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_recent_products',
					"class" => "trx_sc_single trx_sc_recent_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "happyrider"),
							"description" => __("How many products showed", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "happyrider"),
							"description" => __("How many columns per row use for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "happyrider"),
							"description" => __("Sorting order for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'happyrider') => 'date',
								__('Title', 'happyrider') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "happyrider"),
							"description" => __("Sorting order for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Recent_Products extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Related Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "related_products",
					"name" => __("Related Products", "happyrider"),
					"description" => __("WooCommerce shortcode: show related products", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_related_products',
					"class" => "trx_sc_single trx_sc_related_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "posts_per_page",
							"heading" => __("Number", "happyrider"),
							"description" => __("How many products showed", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "happyrider"),
							"description" => __("How many columns per row use for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "happyrider"),
							"description" => __("Sorting order for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'happyrider') => 'date',
								__('Title', 'happyrider') => 'title'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Related_Products extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Featured Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "featured_products",
					"name" => __("Featured Products", "happyrider"),
					"description" => __("WooCommerce shortcode: show featured products", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_featured_products',
					"class" => "trx_sc_single trx_sc_featured_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "happyrider"),
							"description" => __("How many products showed", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "happyrider"),
							"description" => __("How many columns per row use for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "happyrider"),
							"description" => __("Sorting order for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'happyrider') => 'date',
								__('Title', 'happyrider') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "happyrider"),
							"description" => __("Sorting order for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Featured_Products extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Top Rated Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "top_rated_products",
					"name" => __("Top Rated Products", "happyrider"),
					"description" => __("WooCommerce shortcode: show top rated products", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_top_rated_products',
					"class" => "trx_sc_single trx_sc_top_rated_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "happyrider"),
							"description" => __("How many products showed", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "happyrider"),
							"description" => __("How many columns per row use for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "happyrider"),
							"description" => __("Sorting order for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'happyrider') => 'date',
								__('Title', 'happyrider') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "happyrider"),
							"description" => __("Sorting order for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Top_Rated_Products extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Sale Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "sale_products",
					"name" => __("Sale Products", "happyrider"),
					"description" => __("WooCommerce shortcode: list products on sale", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_sale_products',
					"class" => "trx_sc_single trx_sc_sale_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "happyrider"),
							"description" => __("How many products showed", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "happyrider"),
							"description" => __("How many columns per row use for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "happyrider"),
							"description" => __("Sorting order for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'happyrider') => 'date',
								__('Title', 'happyrider') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "happyrider"),
							"description" => __("Sorting order for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Sale_Products extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Product Category
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_category",
					"name" => __("Products from category", "happyrider"),
					"description" => __("WooCommerce shortcode: list products in specified category(-ies)", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_product_category',
					"class" => "trx_sc_single trx_sc_product_category",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "happyrider"),
							"description" => __("How many products showed", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "happyrider"),
							"description" => __("How many columns per row use for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "happyrider"),
							"description" => __("Sorting order for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'happyrider') => 'date',
								__('Title', 'happyrider') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "happyrider"),
							"description" => __("Sorting order for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "category",
							"heading" => __("Categories", "happyrider"),
							"description" => __("Comma separated category slugs", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "operator",
							"heading" => __("Operator", "happyrider"),
							"description" => __("Categories operator", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('IN', 'happyrider') => 'IN',
								__('NOT IN', 'happyrider') => 'NOT IN',
								__('AND', 'happyrider') => 'AND'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Category extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "products",
					"name" => __("Products", "happyrider"),
					"description" => __("WooCommerce shortcode: list all products", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_products',
					"class" => "trx_sc_single trx_sc_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "skus",
							"heading" => __("SKUs", "happyrider"),
							"description" => __("Comma separated SKU codes of products", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "ids",
							"heading" => __("IDs", "happyrider"),
							"description" => __("Comma separated ID of products", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "happyrider"),
							"description" => __("How many columns per row use for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "happyrider"),
							"description" => __("Sorting order for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'happyrider') => 'date',
								__('Title', 'happyrider') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "happyrider"),
							"description" => __("Sorting order for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Products extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
			
				// WooCommerce - Product Attribute
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_attribute",
					"name" => __("Products by Attribute", "happyrider"),
					"description" => __("WooCommerce shortcode: show products with specified attribute", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_product_attribute',
					"class" => "trx_sc_single trx_sc_product_attribute",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "happyrider"),
							"description" => __("How many products showed", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "happyrider"),
							"description" => __("How many columns per row use for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "happyrider"),
							"description" => __("Sorting order for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'happyrider') => 'date',
								__('Title', 'happyrider') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "happyrider"),
							"description" => __("Sorting order for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "attribute",
							"heading" => __("Attribute", "happyrider"),
							"description" => __("Attribute name", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "filter",
							"heading" => __("Filter", "happyrider"),
							"description" => __("Attribute value", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Attribute extends HAPPYRIDER_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Products Categories
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_categories",
					"name" => __("Product Categories", "happyrider"),
					"description" => __("WooCommerce shortcode: show categories with products", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_product_categories',
					"class" => "trx_sc_single trx_sc_product_categories",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "number",
							"heading" => __("Number", "happyrider"),
							"description" => __("How many categories showed", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "happyrider"),
							"description" => __("How many columns per row use for categories output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "happyrider"),
							"description" => __("Sorting order for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'happyrider') => 'date',
								__('Title', 'happyrider') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "happyrider"),
							"description" => __("Sorting order for products output", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "parent",
							"heading" => __("Parent", "happyrider"),
							"description" => __("Parent category slug", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "date",
							"type" => "textfield"
						),
						array(
							"param_name" => "ids",
							"heading" => __("IDs", "happyrider"),
							"description" => __("Comma separated ID of products", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "hide_empty",
							"heading" => __("Hide empty", "happyrider"),
							"description" => __("Hide empty categories", "happyrider"),
							"class" => "",
							"value" => array("Hide empty" => "1" ),
							"type" => "checkbox"
						)
					)
				) );
				
				class WPBakeryShortCode_Products_Categories extends HAPPYRIDER_VC_ShortCodeSingle {}
			
				/*
			
				// WooCommerce - Add to cart
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "add_to_cart",
					"name" => __("Add to cart", "happyrider"),
					"description" => __("WooCommerce shortcode: Display a single product price + cart button", "happyrider"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_add_to_cart',
					"class" => "trx_sc_single trx_sc_add_to_cart",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "id",
							"heading" => __("ID", "happyrider"),
							"description" => __("Product's ID", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "sku",
							"heading" => __("SKU", "happyrider"),
							"description" => __("Product's SKU code", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "quantity",
							"heading" => __("Quantity", "happyrider"),
							"description" => __("How many item add", "happyrider"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "show_price",
							"heading" => __("Show price", "happyrider"),
							"description" => __("Show price near button", "happyrider"),
							"class" => "",
							"value" => array("Show price" => "true" ),
							"type" => "checkbox"
						),
						array(
							"param_name" => "class",
							"heading" => __("Class", "happyrider"),
							"description" => __("CSS class", "happyrider"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "style",
							"heading" => __("CSS style", "happyrider"),
							"description" => __("CSS style for additional decoration", "happyrider"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Add_To_Cart extends HAPPYRIDER_VC_ShortCodeSingle {}
				*/
			}

		}
	}
}
?>