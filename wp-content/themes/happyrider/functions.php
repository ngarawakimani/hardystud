<?php
/**
 * Theme sprecific functions and definitions
 */


/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'happyrider_theme_setup' ) ) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_theme_setup', 1 );
	function happyrider_theme_setup() {

		// Register theme menus
		add_filter( 'happyrider_filter_add_theme_menus',		'happyrider_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'happyrider_filter_add_theme_sidebars',	'happyrider_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'happyrider_filter_importer_options',		'happyrider_set_importer_options' );

	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'happyrider_add_theme_menus' ) ) {
	//add_filter( 'happyrider_filter_add_theme_menus', 'happyrider_add_theme_menus' );
	function happyrider_add_theme_menus($menus) {
		//For example:
		//$menus['menu_footer'] = __('Footer Menu', 'happyrider');
		//if (isset($menus['menu_panel'])) unset($menus['menu_panel']);
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'happyrider_add_theme_sidebars' ) ) {
	//add_filter( 'happyrider_filter_add_theme_sidebars',	'happyrider_add_theme_sidebars' );
	function happyrider_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> __( 'Main Sidebar', 'happyrider' ),
				'sidebar_footer'	=> __( 'Footer Sidebar', 'happyrider' )
			);
			if (happyrider_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = __( 'WooCommerce Cart Sidebar', 'happyrider' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Set theme specific importer options
if ( !function_exists( 'happyrider_set_importer_options' ) ) {
	//add_filter( 'happyrider_filter_importer_options',	'happyrider_set_importer_options' );
	function happyrider_set_importer_options($options=array()) {
		if (is_array($options)) {
			$options['domain_dev'] = esc_url('happyrider.dv.ancorathemes.com');
			$options['domain_demo'] = esc_url('happyrider.ancorathemes.com');
			$options['page_on_front'] = esc_attr('Home page');	// Homepage title
			$options['page_for_posts'] = esc_attr('Blog');		// Blog streampage title
			$options['menus'] = array(						// Menus locations and names
				'menu-main'	  => 'Main menu',
				'menu-user'	  => 'User menu',
				'menu-footer' => 'Footer menu'
			);
		}
		return $options;
	}
}


/* Include framework core files
------------------------------------------------------------------- */
// If now is WP Heartbeat call - skip loading theme core files
if (!isset($_POST['action']) || $_POST['action']!="heartbeat") {
	require_once( get_template_directory().'/fw/loader.php' );
}
?>