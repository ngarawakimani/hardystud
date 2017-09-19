<?php

/* Theme setup section
-------------------------------------------------------------------- */


// ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
// Framework settings
$HAPPYRIDER_GLOBALS['settings'] = array(
	
	'less_compiler'		=> 'lessc',								// no|lessc|less - Compiler for the .less
																// lessc - fast & low memory required, but .less-map, shadows & gradients not supprted
																// less  - slow, but support all features
	'less_nested'		=> false,								// Use nested selectors when compiling less - increase .css size, but allow using nested color schemes
	'less_prefix'		=> '',									// any string - Use prefix before each selector when compile less. For example: 'html '
	'less_separator'	=> '', //'/*---LESS_SEPARATOR---*/',	// string - separator inside .less file to split it when compiling to reduce memory usage
																// (compilation speed gets a bit slow)
	'less_map'			=> 'internal',							// no|internal|external - Generate map for .less files. 
																// Warning! You need more then 128Mb for PHP scripts on your server! Supported only if less_compiler=less (see above)
	
	'customizer_demo'	=> true,								// Show color customizer demo (if many color settings) or not (if only accent colors used)

	'allow_fullscreen'	=> false,								// Allow fullscreen and fullwide body styles

	'socials_type'		=> 'icons',								// images|icons - Use this kind of pictograms for all socials: share, social profiles, team members socials, etc.
	'slides_type'		=> 'bg'								// images|bg - Use image as slide's content or as slide's background

);



// Default Theme Options
if ( !function_exists( 'happyrider_options_settings_theme_setup' ) ) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_options_settings_theme_setup', 2 );	// Priority 1 for add happyrider_filter handlers
	function happyrider_options_settings_theme_setup() {
		global $HAPPYRIDER_GLOBALS;
		
		// Clear all saved Theme Options on first theme run
		add_action('after_switch_theme', 'happyrider_options_reset');

		// Settings 
		$socials_type = happyrider_get_theme_setting('socials_type');
				
		// Prepare arrays 
		$HAPPYRIDER_GLOBALS['options_params'] = array(
			'list_fonts'		=> array('$happyrider_get_list_fonts' => ''),
			'list_fonts_styles'	=> array('$happyrider_get_list_fonts_styles' => ''),
			'list_socials' 		=> array('$happyrider_get_list_socials' => ''),
			'list_icons' 		=> array('$happyrider_get_list_icons' => ''),
			'list_posts_types' 	=> array('$happyrider_get_list_posts_types' => ''),
			'list_categories' 	=> array('$happyrider_get_list_categories' => ''),
			'list_menus'		=> array('$happyrider_get_list_menus' => ''),
			'list_sidebars'		=> array('$happyrider_get_list_sidebars' => ''),
			'list_positions' 	=> array('$happyrider_get_list_sidebars_positions' => ''),
			'list_skins'		=> array('$happyrider_get_list_skins' => ''),
			'list_color_schemes'=> array('$happyrider_get_list_color_schemes' => ''),
			'list_bg_tints'		=> array('$happyrider_get_list_bg_tints' => ''),
			'list_body_styles'	=> array('$happyrider_get_list_body_styles' => ''),
			'list_header_styles'=> array('$happyrider_get_list_templates_header' => ''),
			'list_blog_styles'	=> array('$happyrider_get_list_templates_blog' => ''),
			'list_single_styles'=> array('$happyrider_get_list_templates_single' => ''),
			'list_article_styles'=> array('$happyrider_get_list_article_styles' => ''),
			'list_animations_in' => array('$happyrider_get_list_animations_in' => ''),
			'list_animations_out'=> array('$happyrider_get_list_animations_out' => ''),
			'list_filters'		=> array('$happyrider_get_list_portfolio_filters' => ''),
			'list_hovers'		=> array('$happyrider_get_list_hovers' => ''),
			'list_hovers_dir'	=> array('$happyrider_get_list_hovers_directions' => ''),
			'list_sliders' 		=> array('$happyrider_get_list_sliders' => ''),
			'list_revo_sliders'	=> array('$happyrider_get_list_revo_sliders' => ''),
			'list_bg_image_positions' => array('$happyrider_get_list_bg_image_positions' => ''),
			'list_popups' 		=> array('$happyrider_get_list_popup_engines' => ''),
			'list_gmap_styles' 	=> array('$happyrider_get_list_googlemap_styles' => ''),
			'list_yes_no' 		=> array('$happyrider_get_list_yesno' => ''),
			'list_on_off' 		=> array('$happyrider_get_list_onoff' => ''),
			'list_show_hide' 	=> array('$happyrider_get_list_showhide' => ''),
			'list_sorting' 		=> array('$happyrider_get_list_sortings' => ''),
			'list_ordering' 	=> array('$happyrider_get_list_orderings' => ''),
			'list_locations' 	=> array('$happyrider_get_list_dedicated_locations' => '')
			);


		// Theme options array
		$HAPPYRIDER_GLOBALS['options'] = array(

		
		//###############################
		//#### Customization         #### 
		//###############################
		'partition_customization' => array(
					"title" => __('Customization', 'happyrider'),
					"start" => "partitions",
					"override" => "category,services_group,page,post",
					"icon" => "iconadmin-cog-alt",
					"type" => "partition"
					),
		
		
		// Customization -> Body Style
		//-------------------------------------------------
		
		'customization_body' => array(
					"title" => __('Body style', 'happyrider'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-picture',
					"start" => "customization_tabs",
					"type" => "tab"
					),
		
		'info_body_1' => array(
					"title" => __('Body parameters', 'happyrider'),
					"desc" => __('Select body style, skin and color scheme for entire site. You can override this parameters on any page, post or category', 'happyrider'),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'body_style' => array(
					"title" => __('Body style', 'happyrider'),
					"desc" => __('Select body style:', 'happyrider')
								. ' <br>' . __('<b>boxed</b> - if you want use background color and/or image', 'happyrider')
								. ',<br>' . __('<b>wide</b> - page fill whole window with centered content', 'happyrider')
								. (happyrider_get_theme_setting('allow_fullscreen')
									? ',<br>' . __('<b>fullwide</b> - page content stretched on the full width of the window (with few left and right paddings)', 'happyrider')
									: '')
								. (happyrider_get_theme_setting('allow_fullscreen')
									? ',<br>' . __('<b>fullscreen</b> - page content fill whole window without any paddings', 'happyrider')
									: ''),
					"override" => "category,services_group,post,page",
					"std" => "wide",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_body_styles'],
					"dir" => "horizontal",
					"type" => "radio"
					),
		
		'body_paddings' => array(
					"title" => __('Page paddings', 'happyrider'),
					"desc" => __('Add paddings above and below the page content', 'happyrider'),
					"override" => "post,page",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'theme_skin' => array(
					"title" => __('Select theme skin', 'happyrider'),
					"desc" => __('Select skin for the theme decoration', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_skins'],
					"type" => "select"
					),

		"body_scheme" => array(
					"title" => __('Color scheme', 'happyrider'),
					"desc" => __('Select predefined color scheme for the entire page', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		'body_filled' => array(
					"title" => __('Fill body', 'happyrider'),
					"desc" => __('Fill the page background with the solid color or leave it transparend to show background image (or video background)', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		'image_for_404' => array(
					"title" => __('Background 404 page image',  'happyrider'),
					"desc" => __('Select or upload background custom image.',  'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "",
					"type" => "media"
					),

		'info_body_2' => array(
					"title" => __('Background color and image', 'happyrider'),
					"desc" => __('Color and image for the site background', 'happyrider'),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'bg_custom' => array(
					"title" => __('Use custom background',  'happyrider'),
					"desc" => __("Use custom color and/or image as the site background", 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		'bg_color' => array(
					"title" => __('Background color',  'happyrider'),
					"desc" => __('Body background color',  'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "#ffffff",
					"type" => "color"
					),

		'bg_pattern' => array(
					"title" => __('Background predefined pattern',  'happyrider'),
					"desc" => __('Select theme background pattern (first case - without pattern)',  'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"options" => array(
						0 => happyrider_get_file_url('images/spacer.png'),
						1 => happyrider_get_file_url('images/bg/pattern_1.jpg'),
						2 => happyrider_get_file_url('images/bg/pattern_2.jpg'),
						3 => happyrider_get_file_url('images/bg/pattern_3.jpg'),
						4 => happyrider_get_file_url('images/bg/pattern_4.jpg'),
						5 => happyrider_get_file_url('images/bg/pattern_5.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_pattern_custom' => array(
					"title" => __('Background custom pattern',  'happyrider'),
					"desc" => __('Select or upload background custom pattern. If selected - use it instead the theme predefined pattern (selected in the field above)',  'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image' => array(
					"title" => __('Background predefined image',  'happyrider'),
					"desc" => __('Select theme background image (first case - without image)',  'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						0 => happyrider_get_file_url('images/spacer.png'),
						1 => happyrider_get_file_url('images/bg/image_1_thumb.jpg'),
						2 => happyrider_get_file_url('images/bg/image_2_thumb.jpg'),
						3 => happyrider_get_file_url('images/bg/image_3_thumb.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_image_custom' => array(
					"title" => __('Background custom image',  'happyrider'),
					"desc" => __('Select or upload background custom image. If selected - use it instead the theme predefined image (selected in the field above)',  'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image_custom_position' => array( 
					"title" => __('Background custom image position',  'happyrider'),
					"desc" => __('Select custom image position',  'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "left_top",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'left_top' => "Left Top",
						'center_top' => "Center Top",
						'right_top' => "Right Top",
						'left_center' => "Left Center",
						'center_center' => "Center Center",
						'right_center' => "Right Center",
						'left_bottom' => "Left Bottom",
						'center_bottom' => "Center Bottom",
						'right_bottom' => "Right Bottom",
					),
					"type" => "select"
					),
		
		'bg_image_load' => array(
					"title" => __('Load background image', 'happyrider'),
					"desc" => __('Always load background images or only for boxed body style', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "boxed",
					"size" => "medium",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'boxed' => __('Boxed', 'happyrider'),
						'always' => __('Always', 'happyrider')
					),
					"type" => "switch"
					),

		
		'info_body_3' => array(
					"title" => __('Video background', 'happyrider'),
					"desc" => __('Parameters of the video, used as site background', 'happyrider'),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'show_video_bg' => array(
					"title" => __('Show video background',  'happyrider'),
					"desc" => __("Show video as the site background", 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'video_bg_youtube_code' => array(
					"title" => __('Youtube code for video bg',  'happyrider'),
					"desc" => __("Youtube code of video", 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "",
					"type" => "text"
					),

		'video_bg_url' => array(
					"title" => __('Local video for video bg',  'happyrider'),
					"desc" => __("URL to video-file (uploaded on your site)", 'happyrider'),
					"readonly" =>false,
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"before" => array(	'title' => __('Choose video', 'happyrider'),
										'action' => 'media_upload',
										'multiple' => false,
										'linked_field' => '',
										'type' => 'video',
										'captions' => array('choose' => __( 'Choose Video', 'happyrider'),
															'update' => __( 'Select Video', 'happyrider')
														)
								),
					"std" => "",
					"type" => "media"
					),

		'video_bg_overlay' => array(
					"title" => __('Use overlay for video bg', 'happyrider'),
					"desc" => __('Use overlay texture for the video background', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "no",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		
		
		
		
		// Customization -> Header
		//-------------------------------------------------
		
		'customization_header' => array(
					"title" => __("Header", 'happyrider'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		"info_header_1" => array(
					"title" => __('Top panel', 'happyrider'),
					"desc" => __('Top panel settings. It include user menu area (with contact info, cart button, language selector, login/logout menu and user menu) and main menu area (with logo and main menu).', 'happyrider'),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"top_panel_style" => array(
					"title" => __('Top panel style', 'happyrider'),
					"desc" => __('Select desired style of the page header', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "header_1",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_header_styles'],
					"style" => "list",
					"type" => "images"),

		"top_panel_image" => array(
					"title" => __('Top panel image', 'happyrider'),
					"desc" => __('Select default background image of the page header (if not single post or featured image for current post is not specified)', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'top_panel_style' => array('header_7')
					),
					"std" => "",
					"type" => "media"),
		
		"top_panel_position" => array( 
					"title" => __('Top panel position', 'happyrider'),
					"desc" => __('Select position for the top panel with logo and main menu', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "above",
					"options" => array(
						'hide'  => __('Hide', 'happyrider'),
						'above' => __('Above slider', 'happyrider'),
						'below' => __('Below slider', 'happyrider'),
						'over'  => __('Over slider', 'happyrider')
					),
					"type" => "checklist"),

		"top_panel_scheme" => array(
					"title" => __('Top panel color scheme', 'happyrider'),
					"desc" => __('Select predefined color scheme for the top panel', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"show_page_title" => array(
					"title" => __('Show Page title', 'happyrider'),
					"desc" => __('Show post/page/category title', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_breadcrumbs" => array(
					"title" => __('Show Breadcrumbs', 'happyrider'),
					"desc" => __('Show path to current category (post, page)', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"breadcrumbs_max_level" => array(
					"title" => __('Breadcrumbs max nesting', 'happyrider'),
					"desc" => __("Max number of the nested categories in the breadcrumbs (0 - unlimited)", 'happyrider'),
					"dependency" => array(
						'show_breadcrumbs' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 100,
					"step" => 1,
					"type" => "spinner"),

		
		
		
		"info_header_2" => array( 
					"title" => __('Main menu style and position', 'happyrider'),
					"desc" => __('Select the Main menu style and position', 'happyrider'),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"menu_main" => array( 
					"title" => __('Select main menu',  'happyrider'),
					"desc" => __('Select main menu for the current page',  'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"menu_attachment" => array( 
					"title" => __('Main menu attachment', 'happyrider'),
					"desc" => __('Attach main menu to top of window then page scroll down', 'happyrider'),
					"std" => "fixed",
					"options" => array(
						"fixed"=>__("Fix menu position", 'happyrider'),
						"none"=>__("Don't fix menu position", 'happyrider')
					),
					"dir" => "vertical",
					"type" => "radio"),

		"menu_slider" => array( 
					"title" => __('Main menu slider', 'happyrider'),
					"desc" => __('Use slider background for main menu items', 'happyrider'),
					"std" => "yes",
					"type" => "switch",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no']),

		"menu_animation_in" => array( 
					"title" => __('Submenu show animation', 'happyrider'),
					"desc" => __('Select animation to show submenu ', 'happyrider'),
					"std" => "bounceIn",
					"type" => "select",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_animations_in']),

		"menu_animation_out" => array( 
					"title" => __('Submenu hide animation', 'happyrider'),
					"desc" => __('Select animation to hide submenu ', 'happyrider'),
					"std" => "fadeOutDown",
					"type" => "select",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_animations_out']),
		
		"menu_relayout" => array( 
					"title" => __('Main menu relayout', 'happyrider'),
					"desc" => __('Allow relayout main menu if window width less then this value', 'happyrider'),
					"std" => 960,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_responsive" => array( 
					"title" => __('Main menu responsive', 'happyrider'),
					"desc" => __('Allow responsive version for the main menu if window width less then this value', 'happyrider'),
					"std" => 640,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_width" => array( 
					"title" => __('Submenu width', 'happyrider'),
					"desc" => __('Width for dropdown menus in main menu', 'happyrider'),
					"step" => 5,
					"std" => "",
					"min" => 180,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"),
		
		
		
		"info_header_3" => array(
					"title" => __("User's menu area components", 'happyrider'),
					"desc" => __("Select parts for the user's menu area", 'happyrider'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_top_panel_top" => array(
					"title" => __('Show user menu area', 'happyrider'),
					"desc" => __('Show user menu area on top of page', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_user" => array(
					"title" => __('Select user menu',  'happyrider'),
					"desc" => __('Select user menu for the current page',  'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "default",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"show_socials" => array( 
					"title" => __('Show Social icons', 'happyrider'),
					"desc" => __('Show Social icons in the user menu area', 'happyrider'),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		

		
		"info_header_4" => array( 
					"title" => __("Table of Contents (TOC)", 'happyrider'),
					"desc" => __("Table of Contents for the current page. Automatically created if the page contains objects with id starting with 'toc_'", 'happyrider'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"menu_toc" => array( 
					"title" => __('TOC position', 'happyrider'),
					"desc" => __('Show TOC for the current page', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "float",
					"options" => array(
						'hide'  => __('Hide', 'happyrider'),
						'fixed' => __('Fixed', 'happyrider'),
						'float' => __('Float', 'happyrider')
					),
					"type" => "checklist"),
		
		"menu_toc_home" => array(
					"title" => __('Add "Home" into TOC', 'happyrider'),
					"desc" => __('Automatically add "Home" item into table of contents - return to home page of the site', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_toc_top" => array( 
					"title" => __('Add "To Top" into TOC', 'happyrider'),
					"desc" => __('Automatically add "To Top" item into table of contents - scroll to top of the page', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		
		
		
		'info_header_5' => array(
					"title" => __('Main logo', 'happyrider'),
					"desc" => __("Select or upload logos for the site's header and select it position", 'happyrider'),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'favicon' => array(
					"title" => __('Favicon', 'happyrider'),
					"desc" => __("Upload a 16px x 16px image that will represent your website's favicon.<br /><em>To ensure cross-browser compatibility, we recommend converting the favicon into .ico format before uploading. (<a href='http://www.favicon.cc/'>www.favicon.cc</a>)</em>", 'happyrider'),
					"std" => "",
					"type" => "media"
					),

		'logo' => array(
					"title" => __('Logo image', 'happyrider'),
					"desc" => __('Main logo image', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "",
					"type" => "media"
					),

		'logo_fixed' => array(
					"title" => __('Logo image (fixed header)', 'happyrider'),
					"desc" => __('Logo image for the header (if menu is fixed after the page is scrolled)', 'happyrider'),
					"override" => "category,services_group,post,page",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_text' => array(
					"title" => __('Logo text', 'happyrider'),
					"desc" => __('Logo text - display it after logo image', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_slogan' => array(
					"title" => __('Logo slogan', 'happyrider'),
					"desc" => __('Logo slogan - display it under logo image (instead the site tagline)', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_height' => array(
					"title" => __('Logo height', 'happyrider'),
					"desc" => __('Height for the logo in the header area', 'happyrider'),
					"override" => "category,services_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),

		'logo_offset' => array(
					"title" => __('Logo top offset', 'happyrider'),
					"desc" => __('Top offset for the logo in the header area', 'happyrider'),
					"override" => "category,services_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 0,
					"max" => 99,
					"mask" => "?99",
					"type" => "spinner"
					),
		
		
		
		
		
		
		
		// Customization -> Slider
		//-------------------------------------------------
		
		"customization_slider" => array( 
					"title" => __('Slider', 'happyrider'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,page",
					"type" => "tab"),
		
		"info_slider_1" => array(
					"title" => __('Main slider parameters', 'happyrider'),
					"desc" => __('Select parameters for main slider (you can override it in each category and page)', 'happyrider'),
					"override" => "category,services_group,page",
					"type" => "info"),
					
		"show_slider" => array(
					"title" => __('Show Slider', 'happyrider'),
					"desc" => __('Do you want to show slider on each page (post)', 'happyrider'),
					"override" => "category,services_group,page",
					"std" => "no",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_display" => array(
					"title" => __('Slider display', 'happyrider'),
					"desc" => __('How display slider: boxed (fixed width and height), fullwide (fixed height) or fullscreen', 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "fullwide",
					"options" => array(
						"boxed"=>__("Boxed", 'happyrider'),
						"fullwide"=>__("Fullwide", 'happyrider'),
						"fullscreen"=>__("Fullscreen", 'happyrider')
					),
					"type" => "checklist"),
		
		"slider_height" => array(
					"title" => __("Height (in pixels)", 'happyrider'),
					"desc" => __("Slider height (in pixels) - only if slider display with fixed height.", 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => '',
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"slider_engine" => array(
					"title" => __('Slider engine', 'happyrider'),
					"desc" => __('What engine use to show slider?', 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "revo",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_sliders'],
					"type" => "radio"),
		
		"slider_alias" => array(
					"title" => __('Revolution Slider: Select slider',  'happyrider'),
					"desc" => __("Select slider to show (if engine=revo in the field above)", 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('revo')
					),
					"std" => "",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_revo_sliders'],
					"type" => "select"),
		
		"slider_category" => array(
					"title" => __('Posts Slider: Category to show', 'happyrider'),
					"desc" => __('Select category to show in Flexslider (ignored for Revolution and Royal sliders)', 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "",
					"options" => happyrider_array_merge(array(0 => __('- Select category -', 'happyrider')), $HAPPYRIDER_GLOBALS['options_params']['list_categories']),
					"type" => "select",
					"multiple" => true,
					"style" => "list"),
		
		"slider_posts" => array(
					"title" => __('Posts Slider: Number posts or comma separated posts list',  'happyrider'),
					"desc" => __("How many recent posts display in slider or comma separated list of posts ID (in this case selected category ignored)", 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "5",
					"type" => "text"),
		
		"slider_orderby" => array(
					"title" => __("Posts Slider: Posts order by",  'happyrider'),
					"desc" => __("Posts in slider ordered by date (default), comments, views, author rating, users rating, random or alphabetically", 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "date",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"slider_order" => array(
					"title" => __("Posts Slider: Posts order", 'happyrider'),
					"desc" => __('Select the desired ordering method for posts', 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "desc",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
					
		"slider_interval" => array(
					"title" => __("Posts Slider: Slide change interval", 'happyrider'),
					"desc" => __("Interval (in ms) for slides change in slider", 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 7000,
					"min" => 100,
					"step" => 100,
					"type" => "spinner"),
		
		"slider_pagination" => array(
					"title" => __("Posts Slider: Pagination", 'happyrider'),
					"desc" => __("Choose pagination style for the slider", 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "no",
					"options" => array(
						'no'   => __('None', 'happyrider'),
						'yes'  => __('Dots', 'happyrider'),
						'over' => __('Titles', 'happyrider')
					),
					"type" => "checklist"),
		
		"slider_infobox" => array(
					"title" => __("Posts Slider: Show infobox", 'happyrider'),
					"desc" => __("Do you want to show post's title, reviews rating and description on slides in slider", 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "slide",
					"options" => array(
						'no'    => __('None',  'happyrider'),
						'slide' => __('Slide', 'happyrider'),
						'fixed' => __('Fixed', 'happyrider')
					),
					"type" => "checklist"),
					
		"slider_info_category" => array(
					"title" => __("Posts Slider: Show post's category", 'happyrider'),
					"desc" => __("Do you want to show post's category on slides in slider", 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_reviews" => array(
					"title" => __("Posts Slider: Show post's reviews rating", 'happyrider'),
					"desc" => __("Do you want to show post's reviews rating on slides in slider", 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_descriptions" => array(
					"title" => __("Posts Slider: Show post's descriptions", 'happyrider'),
					"desc" => __("How many characters show in the post's description in slider. 0 - no descriptions", 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"),
		
		
		
		
		
		// Customization -> Sidebars
		//-------------------------------------------------
		
		"customization_sidebars" => array( 
					"title" => __('Sidebars', 'happyrider'),
					"icon" => "iconadmin-indent-right",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		"info_sidebars_2" => array(
					"title" => __('Main sidebar', 'happyrider'),
					"desc" => __('Show / Hide and select main sidebar', 'happyrider'),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		'show_sidebar_main' => array( 
					"title" => __('Show main sidebar',  'happyrider'),
					"desc" => __('Select position for the main sidebar or hide it',  'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "right",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_positions'],
					"dir" => "horizontal",
					"type" => "checklist"),

		"sidebar_main_scheme" => array(
					"title" => __("Color scheme", 'happyrider'),
					"desc" => __('Select predefined color scheme for the main sidebar', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"sidebar_main" => array( 
					"title" => __('Select main sidebar',  'happyrider'),
					"desc" => __('Select main sidebar content',  'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "sidebar_main",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		

		"sidebar_outer_scheme" => array(
					"title" => __("Color scheme", 'happyrider'),
					"desc" => __('Select predefined color scheme for the outer sidebar', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"sidebar_outer_show_logo" => array( 
					"title" => __('Show Logo', 'happyrider'),
					"desc" => __('Show Logo in the outer sidebar', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"sidebar_outer_show_socials" => array( 
					"title" => __('Show Social icons', 'happyrider'),
					"desc" => __('Show Social icons in the outer sidebar', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"sidebar_outer_show_menu" => array( 
					"title" => __('Show Menu', 'happyrider'),
					"desc" => __('Show Menu in the outer sidebar', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_side" => array(
					"title" => __('Select menu',  'happyrider'),
					"desc" => __('Select menu for the outer sidebar',  'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right'),
						'sidebar_outer_show_menu' => array('yes')
					),
					"std" => "default",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"sidebar_outer_show_widgets" => array( 
					"title" => __('Show Widgets', 'happyrider'),
					"desc" => __('Show Widgets in the outer sidebar', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"sidebar_outer" => array( 
					"title" => __('Select outer sidebar',  'happyrider'),
					"desc" => __('Select outer sidebar content',  'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'sidebar_outer_show_widgets' => array('yes'),
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "sidebar_outer",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		
		
		
		// Customization -> Footer
		//-------------------------------------------------
		
		'customization_footer' => array(
					"title" => __("Footer", 'happyrider'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		
		"info_footer_1" => array(
					"title" => __("Footer components", 'happyrider'),
					"desc" => __("Select components of the footer, set style and put the content for the user's footer area", 'happyrider'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_sidebar_footer" => array(
					"title" => __('Show footer sidebar', 'happyrider'),
					"desc" => __('Select style for the footer sidebar or hide it', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"sidebar_footer_scheme" => array(
					"title" => __("Color scheme", 'happyrider'),
					"desc" => __('Select predefined color scheme for the footer', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"sidebar_footer" => array( 
					"title" => __('Select footer sidebar',  'happyrider'),
					"desc" => __('Select footer sidebar for the blog page',  'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "sidebar_footer",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		"sidebar_footer_columns" => array( 
					"title" => __('Footer sidebar columns',  'happyrider'),
					"desc" => __('Select columns number for the footer sidebar',  'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => 3,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),
		
		
		"info_footer_2" => array(
					"title" => __('Testimonials in Footer', 'happyrider'),
					"desc" => __('Select parameters for Testimonials in the Footer', 'happyrider'),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_testimonials_in_footer" => array(
					"title" => __('Show Testimonials in footer', 'happyrider'),
					"desc" => __('Show Testimonials slider in footer. For correct operation of the slider (and shortcode testimonials) you must fill out Testimonials posts on the menu "Testimonials"', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"testimonials_scheme" => array(
					"title" => __("Color scheme", 'happyrider'),
					"desc" => __('Select predefined color scheme for the testimonials area', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		"testimonials_count" => array( 
					"title" => __('Testimonials count', 'happyrider'),
					"desc" => __('Number testimonials to show', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		
		"info_footer_3" => array(
					"title" => __('Twitter in Footer', 'happyrider'),
					"desc" => __('Select parameters for Twitter stream in the Footer (you can override it in each category and page)', 'happyrider'),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_twitter_in_footer" => array(
					"title" => __('Show Twitter in footer', 'happyrider'),
					"desc" => __('Show Twitter slider in footer. For correct operation of the slider (and shortcode twitter) you must fill out the Twitter API keys on the menu "Appearance - Theme Options - Socials"', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"twitter_scheme" => array(
					"title" => __("Color scheme", 'happyrider'),
					"desc" => __('Select predefined color scheme for the twitter area', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		"twitter_count" => array( 
					"title" => __('Twitter count', 'happyrider'),
					"desc" => __('Number twitter to show', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),


		"info_footer_4" => array(
					"title" => __('Google map parameters', 'happyrider'),
					"desc" => __('Select parameters for Google map (you can override it in each category and page)', 'happyrider'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
					
		"show_googlemap" => array(
					"title" => __('Show Google Map', 'happyrider'),
					"desc" => __('Do you want to show Google map on each page (post)', 'happyrider'),
					"override" => "category,services_group,page,post",
					"std" => "no",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"googlemap_height" => array(
					"title" => __("Map height", 'happyrider'),
					"desc" => __("Map height (default - in pixels, allows any CSS units of measure)", 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 400,
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"googlemap_address" => array(
					"title" => __('Address to show on map',  'happyrider'),
					"desc" => __("Enter address to show on map center", 'happyrider'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_latlng" => array(
					"title" => __('Latitude and Longitude to show on map',  'happyrider'),
					"desc" => __("Enter coordinates (separated by comma) to show on map center (instead of address)", 'happyrider'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_title" => array(
					"title" => __('Title to show on map',  'happyrider'),
					"desc" => __("Enter title to show on map center", 'happyrider'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_description" => array(
					"title" => __('Description to show on map',  'happyrider'),
					"desc" => __("Enter description to show on map center", 'happyrider'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_zoom" => array(
					"title" => __('Google map initial zoom',  'happyrider'),
					"desc" => __("Enter desired initial zoom for Google map", 'happyrider'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 16,
					"min" => 1,
					"max" => 20,
					"step" => 1,
					"type" => "spinner"),
		
		"googlemap_style" => array(
					"title" => __('Google map style',  'happyrider'),
					"desc" => __("Select style to show Google map", 'happyrider'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 'style1',
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_gmap_styles'],
					"type" => "select"),
		
		"googlemap_marker" => array(
					"title" => __('Google map marker',  'happyrider'),
					"desc" => __("Select or upload png-image with Google map marker", 'happyrider'),
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => '',
					"type" => "media"),

		"show_contact_over" => array(
							"title" => __("Show contact over map", "happyrider"),
							"desc" => __("Show info-contact over google map(default is show)", "happyrider"),
							"std" => "yes",
							"override" => "category,services_group,page,post",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no']
							),
		
		
		
		"info_footer_5" => array(
					"title" => __("Contacts area", 'happyrider'),
					"desc" => __("Show/Hide contacts area in the footer", 'happyrider'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_contacts_in_footer" => array(
					"title" => __('Show Contacts in footer', 'happyrider'),
					"desc" => __('Show contact information area in footer: site logo, contact info and large social icons', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"contacts_scheme" => array(
					"title" => __("Color scheme", 'happyrider'),
					"desc" => __('Select predefined color scheme for the contacts area', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		'logo_footer' => array(
					"title" => __('Logo image for footer', 'happyrider'),
					"desc" => __('Logo image in the footer (in the contacts area)', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'logo_footer_height' => array(
					"title" => __('Logo height', 'happyrider'),
					"desc" => __('Height for the logo in the footer area (in the contacts area)', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"step" => 1,
					"std" => 30,
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),
		
		
		
		"info_footer_6" => array(
					"title" => __("Copyright and footer menu", 'happyrider'),
					"desc" => __("Show/Hide copyright area in the footer", 'happyrider'),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_copyright_in_footer" => array(
					"title" => __('Show Copyright area in footer', 'happyrider'),
					"desc" => __('Show area with copyright information, footer menu and small social icons in footer', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "plain",
					"options" => array(
						'none' => __('Hide', 'happyrider'),
						'text' => __('Text', 'happyrider'),
						'menu' => __('Text and menu', 'happyrider'),
						'socials' => __('Text and Social icons', 'happyrider')
					),
					"type" => "checklist"),

		"copyright_scheme" => array(
					"title" => __("Color scheme", 'happyrider'),
					"desc" => __('Select predefined color scheme for the copyright area', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"menu_footer" => array( 
					"title" => __('Select footer menu',  'happyrider'),
					"desc" => __('Select footer menu for the current page',  'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"dependency" => array(
						'show_copyright_in_footer' => array('menu')
					),
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_menus'],
					"type" => "select"),

		"footer_copyright" => array(
					"title" => __('Footer copyright text',  'happyrider'),
					"desc" => __("Copyright text to show in footer area (bottom of site)", 'happyrider'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"std" => "HappyRider &copy; 2014 All Rights Reserved ",
					"rows" => "10",
					"type" => "editor"),




		// Customization -> Other
		//-------------------------------------------------
		
		'customization_other' => array(
					"title" => __('Other', 'happyrider'),
					"override" => "category,services_group,page,post",
					"icon" => 'iconadmin-cog',
					"type" => "tab"
					),

		'info_other_1' => array(
					"title" => __('Theme customization other parameters', 'happyrider'),
					"desc" => __('Animation parameters and responsive layouts for the small screens', 'happyrider'),
					"type" => "info"
					),

		'show_theme_customizer' => array(
					"title" => __('Show Theme customizer', 'happyrider'),
					"desc" => __('Do you want to show theme customizer in the right panel? Your website visitors will be able to customise it yourself.', 'happyrider'),
					"std" => "no",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		"customizer_demo" => array(
					"title" => __('Theme customizer panel demo time', 'happyrider'),
					"desc" => __('Timer for demo mode for the customizer panel (in milliseconds: 1000ms = 1s). If 0 - no demo.', 'happyrider'),
					"dependency" => array(
						'show_theme_customizer' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 10000,
					"step" => 500,
					"type" => "spinner"),
		
		'css_animation' => array(
					"title" => __('Extended CSS animations', 'happyrider'),
					"desc" => __('Do you want use extended animations effects on your site?', 'happyrider'),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'remember_visitors_settings' => array(
					"title" => __("Remember visitor's settings", 'happyrider'),
					"desc" => __('To remember the settings that were made by the visitor, when navigating to other pages or to limit their effect only within the current page', 'happyrider'),
					"std" => "no",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
					
		'responsive_layouts' => array(
					"title" => __('Responsive Layouts', 'happyrider'),
					"desc" => __('Do you want use responsive layouts on small screen or still use main layout?', 'happyrider'),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		


		'info_other_2' => array(
					"title" => __('Additional CSS and HTML/JS code', 'happyrider'),
					"desc" => __('Put here your custom CSS and JS code', 'happyrider'),
					"override" => "category,services_group,page,post",
					"type" => "info"
					),
					
		'custom_css_html' => array(
					"title" => __('Use custom CSS/HTML/JS', 'happyrider'),
					"desc" => __('Do you want use custom HTML/CSS/JS code in your site? For example: custom styles, Google Analitics code, etc.', 'happyrider'),
					"std" => "no",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		"gtm_code" => array(
					"title" => __('Google tags manager or Google analitics code',  'happyrider'),
					"desc" => __('Put here Google Tags Manager (GTM) code from your account: Google analitics, remarketing, etc. This code will be placed after open body tag.',  'happyrider'),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"),
		
		"gtm_code2" => array(
					"title" => __('Google remarketing code',  'happyrider'),
					"desc" => __('Put here Google Remarketing code from your account. This code will be placed before close body tag.',  'happyrider'),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"),
		
		'custom_code' => array(
					"title" => __('Your custom HTML/JS code',  'happyrider'),
					"desc" => __('Put here your invisible html/js code: Google analitics, counters, etc',  'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		'custom_css' => array(
					"title" => __('Your custom CSS code',  'happyrider'),
					"desc" => __('Put here your css code to correct main theme styles',  'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		
		
		
		
		
		
		
		
		//###############################
		//#### Blog and Single pages #### 
		//###############################
		"partition_blog" => array(
					"title" => __('Blog &amp; Single', 'happyrider'),
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page",
					"type" => "partition"),
		
		
		
		// Blog -> Stream page
		//-------------------------------------------------
		
		'blog_tab_stream' => array(
					"title" => __('Stream page', 'happyrider'),
					"start" => 'blog_tabs',
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		"info_blog_1" => array(
					"title" => __('Blog streampage parameters', 'happyrider'),
					"desc" => __('Select desired blog streampage parameters (you can override it in each category)', 'happyrider'),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"blog_style" => array(
					"title" => __('Blog style', 'happyrider'),
					"desc" => __('Select desired blog style', 'happyrider'),
					"override" => "category,services_group,page",
					"std" => "excerpt",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_blog_styles'],
					"type" => "select"),
		
		"hover_style" => array(
					"title" => __('Hover style', 'happyrider'),
					"desc" => __('Select desired hover style (only for Blog style = Portfolio)', 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "square effect_shift",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_hovers'],
					"type" => "select"),
		
		"hover_dir" => array(
					"title" => __('Hover dir', 'happyrider'),
					"desc" => __('Select hover direction (only for Blog style = Portfolio and Hover style = Circle or Square)', 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored'),
						'hover_style' => array('square','circle')
					),
					"std" => "left_to_right",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_hovers_dir'],
					"type" => "select"),
		
		"article_style" => array(
					"title" => __('Article style', 'happyrider'),
					"desc" => __('Select article display method: boxed or stretch', 'happyrider'),
					"override" => "category,services_group,page",
					"std" => "stretch",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_article_styles'],
					"size" => "medium",
					"type" => "switch"),
		
		"dedicated_location" => array(
					"title" => __('Dedicated location', 'happyrider'),
					"desc" => __('Select location for the dedicated content or featured image in the "excerpt" blog style', 'happyrider'),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"std" => "default",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_locations'],
					"type" => "select"),
		
		"show_filters" => array(
					"title" => __('Show filters', 'happyrider'),
					"desc" => __('What taxonomy use for filter buttons', 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "hide",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_filters'],
					"type" => "checklist"),
		
		"blog_sort" => array(
					"title" => __('Blog posts sorted by', 'happyrider'),
					"desc" => __('Select the desired sorting method for posts', 'happyrider'),
					"override" => "category,services_group,page",
					"std" => "date",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_sorting'],
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_order" => array(
					"title" => __('Blog posts order', 'happyrider'),
					"desc" => __('Select the desired ordering method for posts', 'happyrider'),
					"override" => "category,services_group,page",
					"std" => "desc",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"posts_per_page" => array(
					"title" => __('Blog posts per page',  'happyrider'),
					"desc" => __('How many posts display on blog pages for selected style. If empty or 0 - inherit system wordpress settings',  'happyrider'),
					"override" => "category,services_group,page",
					"std" => "12",
					"mask" => "?99",
					"type" => "text"),
		
		"post_excerpt_maxlength" => array(
					"title" => __('Excerpt maxlength for streampage',  'happyrider'),
					"desc" => __('How many characters from post excerpt are display in blog streampage (only for Blog style = Excerpt). 0 - do not trim excerpt.',  'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('excerpt', 'portfolio', 'grid', 'square', 'related')
					),
					"std" => "250",
					"mask" => "?9999",
					"type" => "text"),
		
		"post_excerpt_maxlength_masonry" => array(
					"title" => __('Excerpt maxlength for classic and masonry',  'happyrider'),
					"desc" => __('How many characters from post excerpt are display in blog streampage (only for Blog style = Classic or Masonry). 0 - do not trim excerpt.',  'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('masonry', 'classic')
					),
					"std" => "150",
					"mask" => "?9999",
					"type" => "text"),
		
		
		
		
		// Blog -> Single page
		//-------------------------------------------------
		
		'blog_tab_single' => array(
					"title" => __('Single page', 'happyrider'),
					"icon" => "iconadmin-doc",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		
		"info_single_1" => array(
					"title" => __('Single (detail) pages parameters', 'happyrider'),
					"desc" => __('Select desired parameters for single (detail) pages (you can override it in each category and single post (page))', 'happyrider'),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"single_style" => array(
					"title" => __('Single page style', 'happyrider'),
					"desc" => __('Select desired style for single page', 'happyrider'),
					"override" => "category,services_group,page,post",
					"std" => "single-standard",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_single_styles'],
					"dir" => "horizontal",
					"type" => "radio"),

		"icon" => array(
					"title" => __('Select post icon', 'happyrider'),
					"desc" => __('Select icon for output before post/category name in some layouts', 'happyrider'),
					"override" => "services_group,page,post",
					"std" => "",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_icons'],
					"style" => "select",
					"type" => "icons"
					),
		
		"show_featured_image" => array(
					"title" => __('Show featured image before post',  'happyrider'),
					"desc" => __("Show featured image (if selected) before post content on single pages", 'happyrider'),
					"override" => "category,services_group,page,post",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title" => array(
					"title" => __('Show post title', 'happyrider'),
					"desc" => __('Show area with post title on single pages', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title_on_quotes" => array(
					"title" => __('Show post title on links, chat, quote, status', 'happyrider'),
					"desc" => __('Show area with post title on single and blog pages in specific post formats: links, chat, quote, status', 'happyrider'),
					"override" => "category,services_group,page",
					"std" => "no",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_info" => array(
					"title" => __('Show post info', 'happyrider'),
					"desc" => __('Show area with post info on single pages', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_text_before_readmore" => array(
					"title" => __('Show text before "Read more" tag', 'happyrider'),
					"desc" => __('Show text before "Read more" tag on single pages', 'happyrider'),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"show_post_author" => array(
					"title" => __('Show post author details',  'happyrider'),
					"desc" => __("Show post author information block on single post page", 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_tags" => array(
					"title" => __('Show post tags',  'happyrider'),
					"desc" => __("Show tags block on single post page", 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_related" => array(
					"title" => __('Show related posts',  'happyrider'),
					"desc" => __("Show related posts block on single post page", 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"post_related_count" => array(
					"title" => __('Related posts number',  'happyrider'),
					"desc" => __("How many related posts showed on single post page", 'happyrider'),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"override" => "category,services_group,post,page",
					"std" => "2",
					"step" => 1,
					"min" => 2,
					"max" => 8,
					"type" => "spinner"),

		"post_related_columns" => array(
					"title" => __('Related posts columns',  'happyrider'),
					"desc" => __("How many columns used to show related posts on single post page. 1 - use scrolling to show all related posts", 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "2",
					"step" => 1,
					"min" => 1,
					"max" => 4,
					"type" => "spinner"),
		
		"post_related_sort" => array(
					"title" => __('Related posts sorted by', 'happyrider'),
					"desc" => __('Select the desired sorting method for related posts', 'happyrider'),
		//			"override" => "category,services_group,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "date",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"post_related_order" => array(
					"title" => __('Related posts order', 'happyrider'),
					"desc" => __('Select the desired ordering method for related posts', 'happyrider'),
		//			"override" => "category,services_group,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "desc",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"show_post_comments" => array(
					"title" => __('Show comments',  'happyrider'),
					"desc" => __("Show comments block on single post page", 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		// Blog -> Other parameters
		//-------------------------------------------------
		
		'blog_tab_other' => array(
					"title" => __('Other parameters', 'happyrider'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,page",
					"type" => "tab"),
		
		"info_blog_other_1" => array(
					"title" => __('Other Blog parameters', 'happyrider'),
					"desc" => __('Select excluded categories, substitute parameters, etc.', 'happyrider'),
					"type" => "info"),
		
		"exclude_cats" => array(
					"title" => __('Exclude categories', 'happyrider'),
					"desc" => __('Select categories, which posts are exclude from blog page', 'happyrider'),
					"std" => "",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_categories'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"blog_pagination" => array(
					"title" => __('Blog pagination', 'happyrider'),
					"desc" => __('Select type of the pagination on blog streampages', 'happyrider'),
					"std" => "pages",
					"override" => "category,services_group,page",
					"options" => array(
						'pages'    => __('Standard page numbers', 'happyrider'),
						'viewmore' => __('"View more" button', 'happyrider'),
						'infinite' => __('Infinite scroll', 'happyrider')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_pagination_style" => array(
					"title" => __('Blog pagination style', 'happyrider'),
					"desc" => __('Select pagination style for standard page numbers', 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_pagination' => array('pages')
					),
					"std" => "pages",
					"options" => array(
						'pages'  => __('Page numbers list', 'happyrider'),
						'slider' => __('Slider with page numbers', 'happyrider')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_counters" => array(
					"title" => __('Blog counters', 'happyrider'),
					"desc" => __('Select counters, displayed near the post title', 'happyrider'),
					"override" => "category,services_group,page",
					"std" => "views",
					"options" => array(
						'views' => __('Views', 'happyrider'),
						'likes' => __('Likes', 'happyrider'),
						'rating' => __('Rating', 'happyrider'),
						'comments' => __('Comments', 'happyrider')
					),
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		"close_category" => array(
					"title" => __("Post's category announce", 'happyrider'),
					"desc" => __('What category display in announce block (over posts thumb) - original or nearest parental', 'happyrider'),
					"override" => "category,services_group,page",
					"std" => "parental",
					"options" => array(
						'parental' => __('Nearest parental category', 'happyrider'),
						'original' => __("Original post's category", 'happyrider')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"show_date_after" => array(
					"title" => __('Show post date after', 'happyrider'),
					"desc" => __('Show post date after N days (before - show post age)', 'happyrider'),
					"override" => "category,services_group,page",
					"std" => "30",
					"mask" => "?99",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Reviews               #### 
		//###############################
		"partition_reviews" => array(
					"title" => __('Reviews', 'happyrider'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,services_group",
					"type" => "partition"),
		
		"info_reviews_1" => array(
					"title" => __('Reviews criterias', 'happyrider'),
					"desc" => __('Set up list of reviews criterias. You can override it in any category.', 'happyrider'),
					"override" => "category,services_group,services_group",
					"type" => "info"),
		
		"show_reviews" => array(
					"title" => __('Show reviews block',  'happyrider'),
					"desc" => __("Show reviews block on single post page and average reviews rating after post's title in stream pages", 'happyrider'),
					"override" => "category,services_group,services_group",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"reviews_max_level" => array(
					"title" => __('Max reviews level',  'happyrider'),
					"desc" => __("Maximum level for reviews marks", 'happyrider'),
					"std" => "5",
					"options" => array(
						'5'=>__('5 stars', 'happyrider'),
						'10'=>__('10 stars', 'happyrider'),
						'100'=>__('100%', 'happyrider')
					),
					"type" => "radio",
					),
		
		"reviews_style" => array(
					"title" => __('Show rating as',  'happyrider'),
					"desc" => __("Show rating marks as text or as stars/progress bars.", 'happyrider'),
					"std" => "stars",
					"options" => array(
						'text' => __('As text (for example: 7.5 / 10)', 'happyrider'),
						'stars' => __('As stars or bars', 'happyrider')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"reviews_criterias_levels" => array(
					"title" => __('Reviews Criterias Levels', 'happyrider'),
					"desc" => __('Words to mark criterials levels. Just write the word and press "Enter". Also you can arrange words.', 'happyrider'),
					"std" => __("bad,poor,normal,good,great", 'happyrider'),
					"type" => "tags"),
		
		"reviews_first" => array(
					"title" => __('Show first reviews',  'happyrider'),
					"desc" => __("What reviews will be displayed first: by author or by visitors. Also this type of reviews will display under post's title.", 'happyrider'),
					"std" => "author",
					"options" => array(
						'author' => __('By author', 'happyrider'),
						'users' => __('By visitors', 'happyrider')
						),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_second" => array(
					"title" => __('Hide second reviews',  'happyrider'),
					"desc" => __("Do you want hide second reviews tab in widgets and single posts?", 'happyrider'),
					"std" => "show",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_show_hide'],
					"size" => "medium",
					"type" => "switch"),
		
		"reviews_can_vote" => array(
					"title" => __('What visitors can vote',  'happyrider'),
					"desc" => __("What visitors can vote: all or only registered", 'happyrider'),
					"std" => "all",
					"options" => array(
						'all'=>__('All visitors', 'happyrider'),
						'registered'=>__('Only registered', 'happyrider')
					),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_criterias" => array(
					"title" => __('Reviews criterias',  'happyrider'),
					"desc" => __('Add default reviews criterias.',  'happyrider'),
					"override" => "category,services_group,services_group",
					"std" => "",
					"cloneable" => true,
					"type" => "text"),

		// Don't remove this parameter - it used in admin for store marks
		"reviews_marks" => array(
					"std" => "",
					"type" => "hidden"),
		





		//###############################
		//#### Media                #### 
		//###############################
		"partition_media" => array(
					"title" => __('Media', 'happyrider'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,post,page",
					"type" => "partition"),
		
		"info_media_1" => array(
					"title" => __('Media settings', 'happyrider'),
					"desc" => __('Set up parameters to show images, galleries, audio and video posts', 'happyrider'),
					"override" => "category,services_group,services_group",
					"type" => "info"),
					
		"retina_ready" => array(
					"title" => __('Image dimensions', 'happyrider'),
					"desc" => __('What dimensions use for uploaded image: Original or "Retina ready" (twice enlarged)', 'happyrider'),
					"std" => "1",
					"size" => "medium",
					"options" => array(
						"1" => __("Original", 'happyrider'),
						"2" => __("Retina", 'happyrider')
					),
					"type" => "switch"),
		
		"substitute_gallery" => array(
					"title" => __('Substitute standard Wordpress gallery', 'happyrider'),
					"desc" => __('Substitute standard Wordpress gallery with our slider on the single pages', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gallery_instead_image" => array(
					"title" => __('Show gallery instead featured image', 'happyrider'),
					"desc" => __('Show slider with gallery instead featured image on blog streampage and in the related posts section for the gallery posts', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gallery_max_slides" => array(
					"title" => __('Max images number in the slider', 'happyrider'),
					"desc" => __('Maximum images number from gallery into slider', 'happyrider'),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'gallery_instead_image' => array('yes')
					),
					"std" => "5",
					"min" => 2,
					"max" => 10,
					"type" => "spinner"),
		
		"popup_engine" => array(
					"title" => __('Popup engine to zoom images', 'happyrider'),
					"desc" => __('Select engine to show popup windows with images and galleries', 'happyrider'),
					"std" => "magnific",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_popups'],
					"type" => "select"),
		
		"substitute_audio" => array(
					"title" => __('Substitute audio tags', 'happyrider'),
					"desc" => __('Substitute audio tag with source from soundcloud to embed player', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"substitute_video" => array(
					"title" => __('Substitute video tags', 'happyrider'),
					"desc" => __('Substitute video tags with embed players or leave video tags unchanged (if you use third party plugins for the video tags)', 'happyrider'),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_mediaelement" => array(
					"title" => __('Use Media Element script for audio and video tags', 'happyrider'),
					"desc" => __('Do you want use the Media Element script for all audio and video tags on your site or leave standard HTML5 behaviour?', 'happyrider'),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		//###############################
		//#### Socials               #### 
		//###############################
		"partition_socials" => array(
					"title" => __('Socials', 'happyrider'),
					"icon" => "iconadmin-users",
					"override" => "category,services_group,page",
					"type" => "partition"),
		
		"info_socials_1" => array(
					"title" => __('Social networks', 'happyrider'),
					"desc" => __("Social networks list for site footer and Social widget", 'happyrider'),
					"type" => "info"),
		
		"social_icons" => array(
					"title" => __('Social networks',  'happyrider'),
					"desc" => __('Select icon and write URL to your profile in desired social networks.',  'happyrider'),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? $HAPPYRIDER_GLOBALS['options_params']['list_socials'] : $HAPPYRIDER_GLOBALS['options_params']['list_icons'],
					"type" => "socials"),
		
		"info_socials_2" => array(
					"title" => __('Share buttons', 'happyrider'),
					"desc" => __("Add button's code for each social share network.<br>
					In share url you can use next macro:<br>
					<b>{url}</b> - share post (page) URL,<br>
					<b>{title}</b> - post title,<br>
					<b>{image}</b> - post image,<br>
					<b>{descr}</b> - post description (if supported)<br>
					For example:<br>
					<b>Facebook</b> share string: <em>http://www.facebook.com/sharer.php?u={link}&amp;t={title}</em><br>
					<b>Delicious</b> share string: <em>http://delicious.com/save?url={link}&amp;title={title}&amp;note={descr}</em>", 'happyrider'),
					"override" => "category,services_group,page",
					"type" => "info"),
		
		"show_share" => array(
					"title" => __('Show social share buttons',  'happyrider'),
					"desc" => __("Show social share buttons block", 'happyrider'),
					"override" => "category,services_group,page",
					"std" => "horizontal",
					"options" => array(
						'hide'		=> __('Hide', 'happyrider'),
						'vertical'	=> __('Vertical', 'happyrider'),
						'horizontal'=> __('Horizontal', 'happyrider')
					),
					"type" => "checklist"),

		"show_share_counters" => array(
					"title" => __('Show share counters',  'happyrider'),
					"desc" => __("Show share counters after social buttons", 'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"share_caption" => array(
					"title" => __('Share block caption',  'happyrider'),
					"desc" => __('Caption for the block with social share buttons',  'happyrider'),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => __('Share:', 'happyrider'),
					"type" => "text"),
		
		"share_buttons" => array(
					"title" => __('Share buttons',  'happyrider'),
					"desc" => __('Select icon and write share URL for desired social networks.<br><b>Important!</b> If you leave text field empty - internal theme link will be used (if present).',  'happyrider'),
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? $HAPPYRIDER_GLOBALS['options_params']['list_socials'] : $HAPPYRIDER_GLOBALS['options_params']['list_icons'],
					"type" => "socials"),
		
		
		"info_socials_3" => array(
					"title" => __('Twitter API keys', 'happyrider'),
					"desc" => __("Put to this section Twitter API 1.1 keys.<br>
					You can take them after registration your application in <strong>https://apps.twitter.com/</strong>", 'happyrider'),
					"type" => "info"),
		
		"twitter_username" => array(
					"title" => __('Twitter username',  'happyrider'),
					"desc" => __('Your login (username) in Twitter',  'happyrider'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_key" => array(
					"title" => __('Consumer Key',  'happyrider'),
					"desc" => __('Twitter API Consumer key',  'happyrider'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_secret" => array(
					"title" => __('Consumer Secret',  'happyrider'),
					"desc" => __('Twitter API Consumer secret',  'happyrider'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_key" => array(
					"title" => __('Token Key',  'happyrider'),
					"desc" => __('Twitter API Token key',  'happyrider'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_secret" => array(
					"title" => __('Token Secret',  'happyrider'),
					"desc" => __('Twitter API Token secret',  'happyrider'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Contact info          #### 
		//###############################
		"partition_contacts" => array(
					"title" => __('Contact info', 'happyrider'),
					"icon" => "iconadmin-mail",
					"type" => "partition"),
		
		"info_contact_1" => array(
					"title" => __('Contact information', 'happyrider'),
					"desc" => __('Company address, phones and e-mail', 'happyrider'),
					"type" => "info"),
		
		"contact_info" => array(
					"title" => __('Contacts in the header', 'happyrider'),
					"desc" => __('String with contact info in the left side of the site header', 'happyrider'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_open_hours" => array(
					"title" => __('Open hours in the header', 'happyrider'),
					"desc" => __('String with open hours in the site header', 'happyrider'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-clock'),
					"type" => "text"),
		
		"contact_email" => array(
					"title" => __('Contact form email', 'happyrider'),
					"desc" => __('E-mail for send contact form and user registration data', 'happyrider'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-mail'),
					"type" => "text"),
		
		"contact_address_1" => array(
					"title" => __('Company address (part 1)', 'happyrider'),
					"desc" => __('Company country, post code and city', 'happyrider'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_address_2" => array(
					"title" => __('Company address (part 2)', 'happyrider'),
					"desc" => __('Street and house number', 'happyrider'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_phone" => array(
					"title" => __('Phone', 'happyrider'),
					"desc" => __('Phone number', 'happyrider'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		
		"contact_fax" => array(
					"title" => __('Fax', 'happyrider'),
					"desc" => __('Fax number', 'happyrider'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		
		"info_contact_2" => array(
					"title" => __('Contact and Comments form', 'happyrider'),
					"desc" => __('Maximum length of the messages in the contact form shortcode and in the comments form', 'happyrider'),
					"type" => "info"),
		
		"message_maxlength_contacts" => array(
					"title" => __('Contact form message', 'happyrider'),
					"desc" => __("Message's maxlength in the contact form shortcode", 'happyrider'),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"message_maxlength_comments" => array(
					"title" => __('Comments form message', 'happyrider'),
					"desc" => __("Message's maxlength in the comments form", 'happyrider'),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"info_contact_3" => array(
					"title" => __('Default mail function', 'happyrider'),
					"desc" => __('What function you want to use for sending mail: the built-in Wordpress wp_mail() or standard PHP mail() function? Attention! Some plugins may not work with one of them and you always have the ability to switch to alternative.', 'happyrider'),
					"type" => "info"),
		
		"mail_function" => array(
					"title" => __("Mail function", 'happyrider'),
					"desc" => __("What function you want to use for sending mail?", 'happyrider'),
					"std" => "wp_mail",
					"size" => "medium",
					"options" => array(
						'wp_mail' => __('WP mail', 'happyrider'),
						'mail' => __('PHP mail', 'happyrider')
					),
					"type" => "switch"),
		
		
		
		
		
		
		
		//###############################
		//#### Search parameters     #### 
		//###############################
		"partition_search" => array(
					"title" => __('Search', 'happyrider'),
					"icon" => "iconadmin-search",
					"type" => "partition"),
		
		"info_search_1" => array(
					"title" => __('Search parameters', 'happyrider'),
					"desc" => __('Enable/disable AJAX search and output settings for it', 'happyrider'),
					"type" => "info"),
		
		"show_search" => array(
					"title" => __('Show search field', 'happyrider'),
					"desc" => __('Show search field in the top area and side menus', 'happyrider'),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_ajax_search" => array(
					"title" => __('Enable AJAX search', 'happyrider'),
					"desc" => __('Use incremental AJAX search for the search field in top of page', 'happyrider'),
					"dependency" => array(
						'show_search' => array('yes')
					),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_min_length" => array(
					"title" => __('Min search string length',  'happyrider'),
					"desc" => __('The minimum length of the search string',  'happyrider'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 4,
					"min" => 3,
					"type" => "spinner"),
		
		"ajax_search_delay" => array(
					"title" => __('Delay before search (in ms)',  'happyrider'),
					"desc" => __('How much time (in milliseconds, 1000 ms = 1 second) must pass after the last character before the start search',  'happyrider'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 500,
					"min" => 300,
					"max" => 1000,
					"step" => 100,
					"type" => "spinner"),
		
		"ajax_search_types" => array(
					"title" => __('Search area', 'happyrider'),
					"desc" => __('Select post types, what will be include in search results. If not selected - use all types.', 'happyrider'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => "",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_posts_types'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"ajax_search_posts_count" => array(
					"title" => __('Posts number in output',  'happyrider'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => __('Number of the posts to show in search results',  'happyrider'),
					"std" => 5,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		"ajax_search_posts_image" => array(
					"title" => __("Show post's image", 'happyrider'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => __("Show post's thumbnail in the search results", 'happyrider'),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_date" => array(
					"title" => __("Show post's date", 'happyrider'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => __("Show post's publish date in the search results", 'happyrider'),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_author" => array(
					"title" => __("Show post's author", 'happyrider'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => __("Show post's author in the search results", 'happyrider'),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_counters" => array(
					"title" => __("Show post's counters", 'happyrider'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => __("Show post's counters (views, comments, likes) in the search results", 'happyrider'),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		
		//###############################
		//#### Service               #### 
		//###############################
		
		"partition_service" => array(
					"title" => __('Service', 'happyrider'),
					"icon" => "iconadmin-wrench",
					"type" => "partition"),
		
		"info_service_1" => array(
					"title" => __('Theme functionality', 'happyrider'),
					"desc" => __('Basic theme functionality settings', 'happyrider'),
					"type" => "info"),
		
		"notify_about_new_registration" => array(
					"title" => __('Notify about new registration', 'happyrider'),
					"desc" => __('Send E-mail with new registration data to the contact email or to site admin e-mail (if contact email is empty)', 'happyrider'),
					"divider" => false,
					"std" => "no",
					"options" => array(
						'no'    => __('No', 'happyrider'),
						'both'  => __('Both', 'happyrider'),
						'admin' => __('Admin', 'happyrider'),
						'user'  => __('User', 'happyrider')
					),
					"dir" => "horizontal",
					"type" => "checklist"),
		
		"use_ajax_views_counter" => array(
					"title" => __('Use AJAX post views counter', 'happyrider'),
					"desc" => __('Use javascript for post views count (if site work under the caching plugin) or increment views count in single page template', 'happyrider'),
					"std" => "no",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"allow_editor" => array(
					"title" => __('Frontend editor',  'happyrider'),
					"desc" => __("Allow authors to edit their posts in frontend area)", 'happyrider'),
					"std" => "no",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_add_filters" => array(
					"title" => __('Additional filters in the admin panel', 'happyrider'),
					"desc" => __('Show additional filters (on post formats, tags and categories) in admin panel page "Posts". <br>Attention! If you have more than 2.000-3.000 posts, enabling this option may cause slow load of the "Posts" page! If you encounter such slow down, simply open Appearance - Theme Options - Service and set "No" for this option.', 'happyrider'),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_taxonomies" => array(
					"title" => __('Show overriden options for taxonomies', 'happyrider'),
					"desc" => __('Show extra column in categories list, where changed (overriden) theme options are displayed.', 'happyrider'),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_posts" => array(
					"title" => __('Show overriden options for posts and pages', 'happyrider'),
					"desc" => __('Show extra column in posts and pages list, where changed (overriden) theme options are displayed.', 'happyrider'),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"admin_dummy_data" => array(
					"title" => __('Enable Dummy Data Installer', 'happyrider'),
					"desc" => __('Show "Install Dummy Data" in the menu "Appearance". <b>Attention!</b> When you install dummy data all content of your site will be replaced!', 'happyrider'),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_dummy_timeout" => array(
					"title" => __('Dummy Data Installer Timeout',  'happyrider'),
					"desc" => __('Web-servers set the time limit for the execution of php-scripts. By default, this is 30 sec. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically! The import process will try to increase this limit to the time, specified in this field.',  'happyrider'),
					"std" => 1200,
					"min" => 30,
					"max" => 1800,
					"type" => "spinner"),
		
		"admin_emailer" => array(
					"title" => __('Enable Emailer in the admin panel', 'happyrider'),
					"desc" => __('Allow to use HappyRider Emailer for mass-volume e-mail distribution and management of mailing lists in "Appearance - Emailer"', 'happyrider'),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_po_composer" => array(
					"title" => __('Enable PO Composer in the admin panel', 'happyrider'),
					"desc" => __('Allow to use "PO Composer" for edit language files in this theme (in the "Appearance - PO Composer")', 'happyrider'),
					"std" => "no",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"debug_mode" => array(
					"title" => __('Debug mode', 'happyrider'),
					"desc" => __('In debug mode we are using unpacked scripts and styles, else - using minified scripts and styles (if present). Attention! If you have modified the source code in the js or css files, regardless of this option will be used latest (modified) version stylesheets and scripts. You can re-create minified versions of files using on-line services or utility', 'happyrider'),
					"std" => "no",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"info_service_3" => array(
			"title" => esc_html__('API Keys', 'happyrider'),
			"desc" => wp_kses_data( __('API Keys for some Web services', 'happyrider') ),
			"type" => "info"),
		
		'api_google' => array(
			"title" => esc_html__('Google API Key', 'happyrider'),
			"desc" => wp_kses_data( __("Insert Google API Key for browsers into the field above to generate Google Maps", 'happyrider') ),
			"std" => "",
			"type" => "text"),

		
		"info_service_2" => array(
					"title" => __('Clear Wordpress cache', 'happyrider'),
					"desc" => __('For example, it recommended after activating the WPML plugin - in the cache are incorrect data about the structure of categories and your site may display "white screen". After clearing the cache usually the performance of the site is restored.', 'happyrider'),
					"type" => "info"),
		
		"clear_cache" => array(
					"title" => __('Clear cache', 'happyrider'),
					"desc" => __('Clear Wordpress cache data', 'happyrider'),
					"divider" => false,
					"icon" => "iconadmin-trash",
					"action" => "clear_cache",
					"type" => "button")
		);

		
		
	}
}


// Update all temporary vars (start with $happyrider_) in the Theme Options with actual lists
if ( !function_exists( 'happyrider_options_settings_theme_setup2' ) ) {
	add_action( 'happyrider_action_after_init_theme', 'happyrider_options_settings_theme_setup2', 1 );
	function happyrider_options_settings_theme_setup2() {
		if (happyrider_options_is_used()) {
			global $HAPPYRIDER_GLOBALS;
			// Replace arrays with actual parameters
			$lists = array();
			if (count($HAPPYRIDER_GLOBALS['options']) > 0) {
				foreach ($HAPPYRIDER_GLOBALS['options'] as $k=>$v) {
					if (isset($v['options']) && is_array($v['options']) && count($v['options']) > 0) {
						foreach ($v['options'] as $k1=>$v1) {
							if (happyrider_substr($k1, 0, 12) == '$happyrider_' || happyrider_substr($v1, 0, 12) == '$happyrider_') {
								$list_func = happyrider_substr(happyrider_substr($k1, 0, 12) == '$happyrider_' ? $k1 : $v1, 1);
								unset($HAPPYRIDER_GLOBALS['options'][$k]['options'][$k1]);
								if (isset($lists[$list_func]))
									$HAPPYRIDER_GLOBALS['options'][$k]['options'] = happyrider_array_merge($HAPPYRIDER_GLOBALS['options'][$k]['options'], $lists[$list_func]);
								else {
									if (function_exists($list_func)) {
										$HAPPYRIDER_GLOBALS['options'][$k]['options'] = $lists[$list_func] = happyrider_array_merge($HAPPYRIDER_GLOBALS['options'][$k]['options'], $list_func == 'happyrider_get_list_menus' ? $list_func(true) : $list_func());
								   	} else
								   		echo sprintf(__('Wrong function name %s in the theme options array', 'happyrider'), $list_func);
								}
							}
						}
					}
				}
			}
		}
	}
}

// Reset old Theme Options while theme first run
if ( !function_exists( 'happyrider_options_reset' ) ) {
	function happyrider_options_reset($clear=true) {
		$theme_data = wp_get_theme();
		$slug = str_replace(' ', '_', trim(happyrider_strtolower((string) $theme_data->get('Name'))));
		$option_name = 'happyrider_'.strip_tags($slug).'_options_reset';
		if ( get_option($option_name, false) === false ) {	// && (string) $theme_data->get('Version') == '1.0'
			if ($clear) {
				// Remove Theme Options from WP Options
				global $wpdb;
				$wpdb->query('delete from '.esc_sql($wpdb->options).' where option_name like "happyrider_%"');
				// Add Templates Options
				if (file_exists(happyrider_get_file_dir('demo/templates_options.txt'))) {
					$theme_options_txt = happyrider_fgc(happyrider_get_file_dir('demo/templates_options.txt'));
					$data = unserialize( base64_decode( $theme_options_txt) );
					// Replace upload url in options
					if (is_array($data) && count($data) > 0) {
						foreach ($data as $k=>$v) {
							if (is_array($v) && count($v) > 0) {
								foreach ($v as $k1=>$v1) {
									$v[$k1] = happyrider_replace_uploads_url(happyrider_replace_uploads_url($v1, 'uploads'), 'imports');
								}
							}
							add_option( $k, $v, '', 'yes' );
						}
					}
				}
			}
			add_option($option_name, 1, '', 'yes');
		}
	}
}
?>