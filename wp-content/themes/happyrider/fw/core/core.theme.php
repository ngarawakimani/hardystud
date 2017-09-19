<?php
/**
 * HappyRider Framework: Theme specific actions
 *
 * @package	happyrider
 * @since	happyrider 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'happyrider_core_theme_setup' ) ) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_core_theme_setup', 11 );
	function happyrider_core_theme_setup() {

		// Add default posts and comments RSS feed links to head 
		add_theme_support( 'automatic-feed-links' );
		
		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		
		// Custom header setup
		add_theme_support( 'custom-header', array('header-text'=>false));
		
		// Custom backgrounds setup
		add_theme_support( 'custom-background');
		
		// Supported posts formats
		add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') ); 
 
 		// Autogenerate title tag
		add_theme_support('title-tag');
 		
		// Add user menu
		add_theme_support('nav-menus');
		
		// WooCommerce Support
		add_theme_support( 'woocommerce' );
		
		// Editor custom stylesheet - for user
		add_editor_style(happyrider_get_file_url('css/editor-style.css'));
		
		// Make theme available for translation
		// Translations can be filed in the /languages/ directory
		load_theme_textdomain( 'happyrider', happyrider_get_folder_dir('languages') );


		/* Front and Admin actions and filters:
		------------------------------------------------------------------------ */

		if ( !is_admin() ) {
			
			/* Front actions and filters:
			------------------------------------------------------------------------ */
	
			// Filters wp_title to print a neat <title> tag based on what is being viewed
			if (floatval(get_bloginfo('version')) < "4.1") {
				add_filter('wp_title',						'happyrider_wp_title', 10, 2);
			}

			// Add main menu classes
			//add_filter('wp_nav_menu_objects', 			'happyrider_add_mainmenu_classes', 10, 2);
	
			// Prepare logo text
			add_filter('happyrider_filter_prepare_logo_text',	'happyrider_prepare_logo_text', 10, 1);
	
			// Add class "widget_number_#' for each widget
			add_filter('dynamic_sidebar_params', 			'happyrider_add_widget_number', 10, 1);

			// Frontend editor: Save post data
			add_action('wp_ajax_frontend_editor_save',		'happyrider_callback_frontend_editor_save');
			add_action('wp_ajax_nopriv_frontend_editor_save', 'happyrider_callback_frontend_editor_save');

			// Frontend editor: Delete post
			add_action('wp_ajax_frontend_editor_delete', 	'happyrider_callback_frontend_editor_delete');
			add_action('wp_ajax_nopriv_frontend_editor_delete', 'happyrider_callback_frontend_editor_delete');
	
			// Enqueue scripts and styles
			add_action('wp_enqueue_scripts', 				'happyrider_core_frontend_scripts');
			add_action('wp_footer',		 					'happyrider_core_frontend_scripts_inline');
			add_action('happyrider_action_add_scripts_inline','happyrider_core_add_scripts_inline');

			// Prepare theme core global variables
			add_action('happyrider_action_prepare_globals',	'happyrider_core_prepare_globals');

		}

		// Register theme specific nav menus
		happyrider_register_theme_menus();

		// Register theme specific sidebars
		happyrider_register_theme_sidebars();
	}
}




/* Theme init
------------------------------------------------------------------------ */

// Init theme template
function happyrider_core_init_theme() {
	global $HAPPYRIDER_GLOBALS;
	if (!empty($HAPPYRIDER_GLOBALS['theme_inited'])) return;
	$HAPPYRIDER_GLOBALS['theme_inited'] = true;

	// Load custom options from GET and post/page/cat options
	if (isset($_GET['set']) && $_GET['set']==1) {
		foreach ($_GET as $k=>$v) {
			if (happyrider_get_theme_option($k, null) !== null) {
				setcookie($k, $v, 0, '/');
				$_COOKIE[$k] = $v;
			}
		}
	}

	// Get custom options from current category / page / post / shop / event
	happyrider_load_custom_options();

	// Load skin
	$skin = happyrider_esc(happyrider_get_custom_option('theme_skin'));
	$HAPPYRIDER_GLOBALS['theme_skin'] = $skin;
	if ( file_exists(happyrider_get_file_dir('skins/'.($skin).'/skin.php')) ) {
		require_once( happyrider_get_file_dir('skins/'.($skin).'/skin.php') );
	}

	// Fire init theme actions (after skin and custom options are loaded)
	do_action('happyrider_action_init_theme');

	// Prepare theme core global variables
	do_action('happyrider_action_prepare_globals');

	// Fire after init theme actions
	do_action('happyrider_action_after_init_theme');
}


// Prepare theme global variables
if ( !function_exists( 'happyrider_core_prepare_globals' ) ) {
	function happyrider_core_prepare_globals() {
		if (!is_admin()) {
			// AJAX Queries settings
			global $HAPPYRIDER_GLOBALS;
			$HAPPYRIDER_GLOBALS['ajax_nonce'] = wp_create_nonce('ajax_nonce');
			$HAPPYRIDER_GLOBALS['ajax_url'] = admin_url('admin-ajax.php');
		
			// Logo text and slogan
			$HAPPYRIDER_GLOBALS['logo_text'] = apply_filters('happyrider_filter_prepare_logo_text', happyrider_get_custom_option('logo_text'));
			$slogan = happyrider_get_custom_option('logo_slogan');
			if (!$slogan) $slogan = get_bloginfo ( 'description' );
			$HAPPYRIDER_GLOBALS['logo_slogan'] = $slogan;
			
			// Logo image and icons from skin
			$logo_side   = happyrider_get_logo_icon('logo_side');
			$logo_fixed  = happyrider_get_logo_icon('logo_fixed');
			$logo_footer = happyrider_get_logo_icon('logo_footer');
			$HAPPYRIDER_GLOBALS['logo']        = happyrider_get_logo_icon('logo');
			$HAPPYRIDER_GLOBALS['logo_icon']   = happyrider_get_logo_icon('logo_icon');
			$HAPPYRIDER_GLOBALS['logo_side']   = $logo_side   ? $logo_side   : $HAPPYRIDER_GLOBALS['logo'];
			$HAPPYRIDER_GLOBALS['logo_fixed']  = $logo_fixed  ? $logo_fixed  : $HAPPYRIDER_GLOBALS['logo'];
			$HAPPYRIDER_GLOBALS['logo_footer'] = $logo_footer ? $logo_footer : $HAPPYRIDER_GLOBALS['logo'];
	
			$shop_mode = '';
			if (happyrider_get_custom_option('show_mode_buttons')=='yes')
				$shop_mode = happyrider_get_value_gpc('happyrider_shop_mode');
			if (empty($shop_mode))
				$shop_mode = happyrider_get_custom_option('shop_mode', '');
			if (empty($shop_mode) || !is_archive())
				$shop_mode = 'thumbs';
			$HAPPYRIDER_GLOBALS['shop_mode'] = $shop_mode;
		}
	}
}


// Return url for the uploaded logo image or (if not uploaded) - to image from skin folder
if ( !function_exists( 'happyrider_get_logo_icon' ) ) {
	function happyrider_get_logo_icon($slug) {
		$logo_icon = happyrider_get_custom_option($slug);
		return $logo_icon;
	}
}


// Add menu locations
if ( !function_exists( 'happyrider_register_theme_menus' ) ) {
	function happyrider_register_theme_menus() {
		register_nav_menus(apply_filters('happyrider_filter_add_theme_menus', array(
			'menu_main'		=> __('Main Menu', 'happyrider'),
			'menu_user'		=> __('User Menu', 'happyrider'),
			'menu_footer'	=> __('Footer Menu', 'happyrider'),
			'menu_side'		=> __('Side Menu', 'happyrider')
		)));
	}
}


// Register widgetized area
if ( !function_exists( 'happyrider_register_theme_sidebars' ) ) {
	function happyrider_register_theme_sidebars($sidebars=array()) {
		global $HAPPYRIDER_GLOBALS;
		if (!is_array($sidebars)) $sidebars = array();
		// Custom sidebars
		$custom = happyrider_get_theme_option('custom_sidebars');
		if (is_array($custom) && count($custom) > 0) {
			foreach ($custom as $i => $sb) {
				if (trim(chop($sb))=='') continue;
				$sidebars['sidebar_custom_'.($i)]  = $sb;
			}
		}
		$sidebars = apply_filters( 'happyrider_filter_add_theme_sidebars', $sidebars );
		$HAPPYRIDER_GLOBALS['registered_sidebars'] = $sidebars;
		if (is_array($sidebars) && count($sidebars) > 0) {
			foreach ($sidebars as $id=>$name) {
				register_sidebar( array(
					'name'          => $name,
					'id'            => $id,
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h5 class="widget_title">',
					'after_title'   => '</h5>',
				) );
			}
		}
	}
}





/* Front actions and filters:
------------------------------------------------------------------------ */

//  Enqueue scripts and styles
if ( !function_exists( 'happyrider_core_frontend_scripts' ) ) {
	function happyrider_core_frontend_scripts() {
		global $HAPPYRIDER_GLOBALS;
		
		// Modernizr will load in head before other scripts and styles
		//happyrider_enqueue_script( 'happyrider-core-modernizr-script', happyrider_get_file_url('js/modernizr.js'), array(), null, false );
		
		// Enqueue styles
		//-----------------------------------------------------------------------------------------------------
		
		// Prepare custom fonts
		$fonts = happyrider_get_list_fonts(false);
		$theme_fonts = array();
		$custom_fonts = happyrider_get_custom_fonts();
		if (is_array($custom_fonts) && count($custom_fonts) > 0) {
			foreach ($custom_fonts as $s=>$f) {
				if (!empty($f['font-family']) && !happyrider_is_inherit_option($f['font-family'])) $theme_fonts[$f['font-family']] = 1;
			}
		}
		// Prepare current skin fonts
		$theme_fonts = apply_filters('happyrider_filter_used_fonts', $theme_fonts);
		// Link to selected fonts
		if (is_array($theme_fonts) && count($theme_fonts) > 0) {
			foreach ($theme_fonts as $font=>$v) {
				if (isset($fonts[$font])) {
					$font_name = ($pos=happyrider_strpos($font,' ('))!==false ? happyrider_substr($font, 0, $pos) : $font;
					$css = !empty($fonts[$font]['css']) 
						? $fonts[$font]['css'] 
						: happyrider_get_protocol().'://fonts.googleapis.com/css?family='
							.(!empty($fonts[$font]['link']) ? $fonts[$font]['link'] : str_replace(' ', '+', $font_name).':100,100italic,300,300italic,400,400italic,700,700italic')
							.(empty($fonts[$font]['link']) || happyrider_strpos($fonts[$font]['link'], 'subset=')===false ? '&subset=latin,latin-ext,cyrillic,cyrillic-ext' : '');
					happyrider_enqueue_style( 'theme-font-'.str_replace(' ', '-', $font_name), $css, array(), null );
				}
			}
		}
		
		// Fontello styles must be loaded before main stylesheet
		happyrider_enqueue_style( 'happyrider-fontello-style',  happyrider_get_file_url('css/fontello/css/fontello.css'),  array(), null);
		//happyrider_enqueue_style( 'happyrider-fontello-animation-style', happyrider_get_file_url('css/fontello/css/animation.css'), array(), null);

		// Main stylesheet
		happyrider_enqueue_style( 'happyrider-main-style', get_stylesheet_uri(), array(), null );
		
		// Animations
		if (happyrider_get_theme_option('css_animation')=='yes')
			happyrider_enqueue_style( 'happyrider-animation-style',	happyrider_get_file_url('css/core.animation.css'), array(), null );

		// Theme skin stylesheet
		do_action('happyrider_action_add_styles');
		
		// Theme customizer stylesheet and inline styles
		happyrider_enqueue_custom_styles();

		// Responsive
		if (happyrider_get_theme_option('responsive_layouts') == 'yes') {
			$suffix = happyrider_param_is_off(happyrider_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
			happyrider_enqueue_style( 'happyrider-responsive-style', happyrider_get_file_url('css/responsive'.($suffix).'.css'), array(), null );
			do_action('happyrider_action_add_responsive');
			if (happyrider_get_custom_option('theme_skin')!='') {
				$css = apply_filters('happyrider_filter_add_responsive_inline', '');
				if (!empty($css)) wp_add_inline_style( 'happyrider-responsive-style', $css );
			}
		}


		// Enqueue scripts	
		//----------------------------------------------------------------------------------------------------------------------------
		
		// Load separate theme scripts
		happyrider_enqueue_script( 'superfish', happyrider_get_file_url('js/superfish.min.js'), array('jquery'), null, true );
		if (happyrider_get_theme_option('menu_slider')=='yes') {
			happyrider_enqueue_script( 'happyrider-slidemenu-script', happyrider_get_file_url('js/jquery.slidemenu.js'), array('jquery'), null, true );
			//happyrider_enqueue_script( 'happyrider-jquery-easing-script', happyrider_get_file_url('js/jquery.easing.js'), array('jquery'), null, true );
		}

		if ( is_single() && happyrider_get_custom_option('show_reviews')=='yes' ) {
			happyrider_enqueue_script( 'happyrider-core-reviews-script', happyrider_get_file_url('js/core.reviews.js'), array('jquery'), null, true );
		}

		happyrider_enqueue_script( 'happyrider-core-utils-script', happyrider_get_file_url('js/core.utils.js'), array('jquery'), null, true );
		happyrider_enqueue_script( 'happyrider-core-init-script', happyrider_get_file_url('js/core.init.js'), array('jquery'), null, true );

		// Media elements library	
		if (happyrider_get_theme_option('use_mediaelement')=='yes') {
			wp_enqueue_style ( 'mediaelement' );
			wp_enqueue_style ( 'wp-mediaelement' );
			wp_enqueue_script( 'mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		} else {
			global $wp_styles, $wp_scripts;
			$wp_scripts->done[]	= 'mediaelement';
			$wp_scripts->done[]	= 'wp-mediaelement';
			$wp_styles->done[]	= 'mediaelement';
			$wp_styles->done[]	= 'wp-mediaelement';
		}
		
		// Video background
		if (happyrider_get_custom_option('show_video_bg') == 'yes' && happyrider_get_custom_option('video_bg_youtube_code') != '') {
			happyrider_enqueue_script( 'happyrider-video-bg-script', happyrider_get_file_url('js/jquery.tubular.1.0.js'), array('jquery'), null, true );
		}

		// Google map
		if ( happyrider_get_custom_option('show_googlemap')=='yes' ) { 
			$api_key = happyrider_get_theme_option('api_google');
			happyrider_enqueue_script( 'googlemap', happyrider_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
			happyrider_enqueue_script( 'happyrider-googlemap-script', happyrider_get_file_url('js/core.googlemap.js'), array(), null, true );
		}

			
		// Social share buttons
		if (is_singular() && !happyrider_get_global('blog_streampage') && happyrider_get_custom_option('show_share')!='hide') {
			happyrider_enqueue_script( 'happyrider-social-share-script', happyrider_get_file_url('js/social/social-share.js'), array('jquery'), null, true );
		}

		// Comments
		if ( is_singular() && !happyrider_get_global('blog_streampage') && comments_open() && get_option( 'thread_comments' ) ) {
			happyrider_enqueue_script( 'comment-reply', false, array(), null, true );
		}

		// Custom panel
		if (happyrider_get_theme_option('show_theme_customizer') == 'yes') {
			if (file_exists(happyrider_get_file_dir('core/core.customizer/front.customizer.css')))
				happyrider_enqueue_style(  'happyrider-customizer-style',  happyrider_get_file_url('core/core.customizer/front.customizer.css'), array(), null );
			if (file_exists(happyrider_get_file_dir('core/core.customizer/front.customizer.js')))
				happyrider_enqueue_script( 'happyrider-customizer-script', happyrider_get_file_url('core/core.customizer/front.customizer.js'), array(), null, true );
		}
		
		//Debug utils
		if (happyrider_get_theme_option('debug_mode')=='yes') {
			happyrider_enqueue_script( 'happyrider-core-debug-script', happyrider_get_file_url('js/core.debug.js'), array(), null, true );
		}

		// Theme skin script
		do_action('happyrider_action_add_scripts');
	}
}

//  Enqueue Swiper Slider scripts and styles
if ( !function_exists( 'happyrider_enqueue_slider' ) ) {
	function happyrider_enqueue_slider($engine='all') {
		if ($engine=='all' || $engine=='swiper') {
			happyrider_enqueue_style( 'happyrider-swiperslider-style', 				happyrider_get_file_url('js/swiper/swiper.css'), array(), null );
			happyrider_enqueue_script( 'happyrider-swiperslider-script', 			happyrider_get_file_url('js/swiper/swiper.js'), array(), null, true );
		}
	}
}

//  Enqueue Messages scripts and styles
if ( !function_exists( 'happyrider_enqueue_messages' ) ) {
	function happyrider_enqueue_messages() {
		happyrider_enqueue_style( 'happyrider-messages-style',		happyrider_get_file_url('js/core.messages/core.messages.css'), array(), null );
		happyrider_enqueue_script( 'happyrider-messages-script',	happyrider_get_file_url('js/core.messages/core.messages.js'),  array('jquery'), null, true );
	}
}

//  Enqueue Portfolio hover scripts and styles
if ( !function_exists( 'happyrider_enqueue_portfolio' ) ) {
	function happyrider_enqueue_portfolio($hover='') {
		happyrider_enqueue_style( 'happyrider-portfolio-style',  happyrider_get_file_url('css/core.portfolio.css'), array(), null );
		if (happyrider_strpos($hover, 'effect_dir')!==false)
			happyrider_enqueue_script( 'hoverdir', happyrider_get_file_url('js/hover/jquery.hoverdir.js'), array(), null, true );
	}
}

//  Enqueue Charts and Diagrams scripts and styles
if ( !function_exists( 'happyrider_enqueue_diagram' ) ) {
	function happyrider_enqueue_diagram($type='all') {
		if ($type=='all' || $type=='pie') happyrider_enqueue_script( 'happyrider-diagram-chart-script',	happyrider_get_file_url('js/diagram/chart.min.js'), array(), null, true );
		if ($type=='all' || $type=='arc') happyrider_enqueue_script( 'happyrider-diagram-raphael-script',	happyrider_get_file_url('js/diagram/diagram.raphael.min.js'), array(), 'no-compose', true );
	}
}

// Enqueue Theme Popup scripts and styles
// Link must have attribute: data-rel="popup" or data-rel="popup[gallery]"
if ( !function_exists( 'happyrider_enqueue_popup' ) ) {
	function happyrider_enqueue_popup($engine='') {
		if ($engine=='pretty' || (empty($engine) && happyrider_get_theme_option('popup_engine')=='pretty')) {
			happyrider_enqueue_style(  'happyrider-prettyphoto-style',	happyrider_get_file_url('js/prettyphoto/css/prettyPhoto.css'), array(), null );
			happyrider_enqueue_script( 'happyrider-prettyphoto-script',	happyrider_get_file_url('js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
		} else if ($engine=='magnific' || (empty($engine) && happyrider_get_theme_option('popup_engine')=='magnific')) {
			happyrider_enqueue_style(  'happyrider-magnific-style',	happyrider_get_file_url('js/magnific/magnific-popup.css'), array(), null );
			happyrider_enqueue_script( 'happyrider-magnific-script',happyrider_get_file_url('js/magnific/jquery.magnific-popup.min.js'), array('jquery'), '', true );
		} else if ($engine=='internal' || (empty($engine) && happyrider_get_theme_option('popup_engine')=='internal')) {
			happyrider_enqueue_messages();
		}
	}
}

//  Add inline scripts in the footer hook
if ( !function_exists( 'happyrider_core_frontend_scripts_inline' ) ) {
	function happyrider_core_frontend_scripts_inline() {
		do_action('happyrider_action_add_scripts_inline');
	}
}

//  Add inline scripts in the footer
if (!function_exists('happyrider_core_add_scripts_inline')) {
	function happyrider_core_add_scripts_inline() {
		global $HAPPYRIDER_GLOBALS;

		$msg = happyrider_get_system_message(true);
		if (!empty($msg['message'])) happyrider_enqueue_messages();

		echo "<script type=\"text/javascript\">"
			. "jQuery(document).ready(function() {"
			
			// AJAX parameters
			. "HAPPYRIDER_GLOBALS['ajax_url']			= '" . esc_url($HAPPYRIDER_GLOBALS['ajax_url']) . "';"
			. "HAPPYRIDER_GLOBALS['ajax_nonce']		= '" . esc_attr($HAPPYRIDER_GLOBALS['ajax_nonce']) . "';"
			. "HAPPYRIDER_GLOBALS['ajax_nonce_editor'] = '" . esc_attr(wp_create_nonce('happyrider_editor_nonce')) . "';"
			
			// Site base url
			. "HAPPYRIDER_GLOBALS['site_url']			= '" . get_site_url() . "';"
			
			// VC frontend edit mode
			. "HAPPYRIDER_GLOBALS['vc_edit_mode']		= " . (happyrider_vc_is_frontend() ? 'true' : 'false') . ";"
			
			// Theme base font
			. "HAPPYRIDER_GLOBALS['theme_font']		= '" . happyrider_get_custom_font_settings('p', 'font-family') . "';"
			
			// Theme skin
			. "HAPPYRIDER_GLOBALS['theme_skin']			= '" . esc_attr(happyrider_get_custom_option('theme_skin')) . "';"
			. "HAPPYRIDER_GLOBALS['theme_skin_color']		= '" . happyrider_get_scheme_color('text_dark') . "';"
			. "HAPPYRIDER_GLOBALS['theme_skin_bg_color']	= '" . happyrider_get_scheme_color('bg_color') . "';"
			
			// Slider height
			. "HAPPYRIDER_GLOBALS['slider_height']	= " . max(100, happyrider_get_custom_option('slider_height')) . ";"
			
			// System message
			. "HAPPYRIDER_GLOBALS['system_message']	= {"
				. "message: '" . addslashes($msg['message']) . "',"
				. "status: '"  . addslashes($msg['status'])  . "',"
				. "header: '"  . addslashes($msg['header'])  . "'"
				. "};"
			
			// User logged in
			. "HAPPYRIDER_GLOBALS['user_logged_in']	= " . (is_user_logged_in() ? 'true' : 'false') . ";"
			
			// Show table of content for the current page
			. "HAPPYRIDER_GLOBALS['toc_menu']		= '" . esc_attr(happyrider_get_custom_option('menu_toc')) . "';"
			. "HAPPYRIDER_GLOBALS['toc_menu_home']	= " . (happyrider_get_custom_option('menu_toc')!='hide' && happyrider_get_custom_option('menu_toc_home')=='yes' ? 'true' : 'false') . ";"
			. "HAPPYRIDER_GLOBALS['toc_menu_top']	= " . (happyrider_get_custom_option('menu_toc')!='hide' && happyrider_get_custom_option('menu_toc_top')=='yes' ? 'true' : 'false') . ";"
			
			// Fix main menu
			. "HAPPYRIDER_GLOBALS['menu_fixed']		= " . (happyrider_get_theme_option('menu_attachment')=='fixed' ? 'true' : 'false') . ";"
			
			// Use responsive version for main menu
			. "HAPPYRIDER_GLOBALS['menu_relayout']	= " . max(0, (int) happyrider_get_theme_option('menu_relayout')) . ";"
			. "HAPPYRIDER_GLOBALS['menu_responsive']	= " . (happyrider_get_theme_option('responsive_layouts') == 'yes' ? max(0, (int) happyrider_get_theme_option('menu_responsive')) : 0) . ";"
			. "HAPPYRIDER_GLOBALS['menu_slider']     = " . (happyrider_get_theme_option('menu_slider')=='yes' ? 'true' : 'false') . ";"

			// Right panel demo timer
			. "HAPPYRIDER_GLOBALS['demo_time']		= " . (happyrider_get_theme_option('show_theme_customizer')=='yes' ? max(0, (int) happyrider_get_theme_option('customizer_demo')) : 0) . ";"

			// Video and Audio tag wrapper
			. "HAPPYRIDER_GLOBALS['media_elements_enabled'] = " . (happyrider_get_theme_option('use_mediaelement')=='yes' ? 'true' : 'false') . ";"
			
			// Use AJAX search
			. "HAPPYRIDER_GLOBALS['ajax_search_enabled'] 	= " . (happyrider_get_theme_option('use_ajax_search')=='yes' ? 'true' : 'false') . ";"
			. "HAPPYRIDER_GLOBALS['ajax_search_min_length']	= " . min(3, happyrider_get_theme_option('ajax_search_min_length')) . ";"
			. "HAPPYRIDER_GLOBALS['ajax_search_delay']		= " . min(200, max(1000, happyrider_get_theme_option('ajax_search_delay'))) . ";"

			// Use CSS animation
			. "HAPPYRIDER_GLOBALS['css_animation']      = " . (happyrider_get_theme_option('css_animation')=='yes' ? 'true' : 'false') . ";"
			. "HAPPYRIDER_GLOBALS['menu_animation_in']  = '" . esc_attr(happyrider_get_theme_option('menu_animation_in')) . "';"
			. "HAPPYRIDER_GLOBALS['menu_animation_out'] = '" . esc_attr(happyrider_get_theme_option('menu_animation_out')) . "';"

			// Popup windows engine
			. "HAPPYRIDER_GLOBALS['popup_engine']	= '" . esc_attr(happyrider_get_theme_option('popup_engine')) . "';"

			// E-mail mask
			. "HAPPYRIDER_GLOBALS['email_mask']		= '^([a-zA-Z0-9_\\-]+\\.)*[a-zA-Z0-9_\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)*\\.[a-z]{2,6}$';"
			
			// Messages max length
			. "HAPPYRIDER_GLOBALS['contacts_maxlength']	= " . intval(happyrider_get_theme_option('message_maxlength_contacts')) . ";"
			. "HAPPYRIDER_GLOBALS['comments_maxlength']	= " . intval(happyrider_get_theme_option('message_maxlength_comments')) . ";"

			// Remember visitors settings
			. "HAPPYRIDER_GLOBALS['remember_visitors_settings']	= " . (happyrider_get_theme_option('remember_visitors_settings')=='yes' ? 'true' : 'false') . ";"

			// Internal vars - do not change it!
			// Flag for review mechanism
			. "HAPPYRIDER_GLOBALS['admin_mode']			= false;"
			// Max scale factor for the portfolio and other isotope elements before relayout
			. "HAPPYRIDER_GLOBALS['isotope_resize_delta']	= 0.3;"
			// jQuery object for the message box in the form
			. "HAPPYRIDER_GLOBALS['error_message_box']	= null;"
			// Waiting for the viewmore results
			. "HAPPYRIDER_GLOBALS['viewmore_busy']		= false;"
			. "HAPPYRIDER_GLOBALS['video_resize_inited']	= false;"
			. "HAPPYRIDER_GLOBALS['top_panel_height']		= 0;"
			. "});"
			. "</script>";
	}
}


//  Enqueue Custom styles (main Theme options settings)
if ( !function_exists( 'happyrider_enqueue_custom_styles' ) ) {
	function happyrider_enqueue_custom_styles() {
		// Custom stylesheet
		$custom_css = '';	//happyrider_get_custom_option('custom_stylesheet_url');
		happyrider_enqueue_style( 'happyrider-custom-style', $custom_css ? $custom_css : happyrider_get_file_url('css/custom-style.css'), array(), null );
		// Custom inline styles
		wp_add_inline_style( 'happyrider-custom-style', happyrider_prepare_custom_styles() );
	}
}

// Add class "widget_number_#' for each widget
if ( !function_exists( 'happyrider_add_widget_number' ) ) {
	//add_filter('dynamic_sidebar_params', 'happyrider_add_widget_number', 10, 1);
	function happyrider_add_widget_number($prm) {
		global $HAPPYRIDER_GLOBALS;
		if (is_admin()) return $prm;
		static $num=0, $last_sidebar='', $last_sidebar_id='', $last_sidebar_columns=0, $last_sidebar_count=0, $sidebars_widgets=array();
		$cur_sidebar = !empty($HAPPYRIDER_GLOBALS['current_sidebar']) ? $HAPPYRIDER_GLOBALS['current_sidebar'] : 'undefined';
		if (count($sidebars_widgets) == 0)
			$sidebars_widgets = wp_get_sidebars_widgets();
		if ($last_sidebar != $cur_sidebar) {
			$num = 0;
			$last_sidebar = $cur_sidebar;
			$last_sidebar_id = $prm[0]['id'];
			$last_sidebar_columns = max(1, (int) happyrider_get_custom_option('sidebar_'.($cur_sidebar).'_columns'));
			$last_sidebar_count = count($sidebars_widgets[$last_sidebar_id]);
		}
		$num++;
		$prm[0]['before_widget'] = str_replace(' class="', ' class="widget_number_'.esc_attr($num).($last_sidebar_columns > 1 ? ' column-1_'.esc_attr($last_sidebar_columns) : '').' ', $prm[0]['before_widget']);
		return $prm;
	}
}


// Filters wp_title to print a neat <title> tag based on what is being viewed.
if ( !function_exists( 'happyrider_wp_title' ) ) {
	// add_filter( 'wp_title', 'happyrider_wp_title', 10, 2 );
	function happyrider_wp_title( $title, $sep ) {
		global $page, $paged;
		if ( is_feed() ) return $title;
		// Add the blog name
		$title .= get_bloginfo( 'name' );
		// Add the blog description for the home/front page.
		if ( is_home() || is_front_page() ) {
			$site_description = happyrider_get_custom_option('logo_slogan');
			if (empty($site_description)) 
				$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description )
				$title .= " $sep $site_description";
		}
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			$title .= " $sep " . sprintf( esc_html__( 'Page %s', 'happyrider' ), max( $paged, $page ) );
		return $title;
	}
}

// Add main menu classes
if ( !function_exists( 'happyrider_add_mainmenu_classes' ) ) {
	// add_filter('wp_nav_menu_objects', 'happyrider_add_mainmenu_classes', 10, 2);
	function happyrider_add_mainmenu_classes($items, $args) {
		if (is_admin()) return $items;
		if ($args->menu_id == 'mainmenu' && happyrider_get_theme_option('menu_colored')=='yes' && is_array($items) && count($items) > 0) {
			foreach($items as $k=>$item) {
				if ($item->menu_item_parent==0) {
					if ($item->type=='taxonomy' && $item->object=='category') {
						$cur_tint = happyrider_taxonomy_get_inherited_property('category', $item->object_id, 'bg_tint');
						if (!empty($cur_tint) && !happyrider_is_inherit_option($cur_tint))
							$items[$k]->classes[] = 'bg_tint_'.esc_attr($cur_tint);
					}
				}
			}
		}
		return $items;
	}
}


// Save post data from frontend editor
if ( !function_exists( 'happyrider_callback_frontend_editor_save' ) ) {
	function happyrider_callback_frontend_editor_save() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'happyrider_editor_nonce' ) )
			die();

		$response = array('error'=>'');

		parse_str($_REQUEST['data'], $output);
		$post_id = $output['frontend_editor_post_id'];

		if ( happyrider_get_theme_option("allow_editor")=='yes' && (current_user_can('edit_posts', $post_id) || current_user_can('edit_pages', $post_id)) ) {
			if ($post_id > 0) {
				$title   = stripslashes($output['frontend_editor_post_title']);
				$content = stripslashes($output['frontend_editor_post_content']);
				$excerpt = stripslashes($output['frontend_editor_post_excerpt']);
				$rez = wp_update_post(array(
					'ID'           => $post_id,
					'post_content' => $content,
					'post_excerpt' => $excerpt,
					'post_title'   => $title
				));
				if ($rez == 0) 
					$response['error'] = __('Post update error!', 'happyrider');
			} else {
				$response['error'] = __('Post update error!', 'happyrider');
			}
		} else
			$response['error'] = __('Post update denied!', 'happyrider');
		
		echo json_encode($response);
		die();
	}
}

// Delete post from frontend editor
if ( !function_exists( 'happyrider_callback_frontend_editor_delete' ) ) {
	function happyrider_callback_frontend_editor_delete() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'happyrider_editor_nonce' ) )
			die();

		$response = array('error'=>'');
		
		$post_id = $_REQUEST['post_id'];

		if ( happyrider_get_theme_option("allow_editor")=='yes' && (current_user_can('delete_posts', $post_id) || current_user_can('delete_pages', $post_id)) ) {
			if ($post_id > 0) {
				$rez = wp_delete_post($post_id);
				if ($rez === false) 
					$response['error'] = __('Post delete error!', 'happyrider');
			} else {
				$response['error'] = __('Post delete error!', 'happyrider');
			}
		} else
			$response['error'] = __('Post delete denied!', 'happyrider');

		echo json_encode($response);
		die();
	}
}


// Prepare logo text
if ( !function_exists( 'happyrider_prepare_logo_text' ) ) {
	function happyrider_prepare_logo_text($text) {
		$text = str_replace(array('[', ']'), array('<span class="theme_accent">', '</span>'), $text);
		$text = str_replace(array('{', '}'), array('<strong>', '</strong>'), $text);
		return $text;
	}
}
?>