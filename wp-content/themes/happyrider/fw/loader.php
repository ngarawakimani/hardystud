<?php
/**
 * HappyRider Framework
 *
 * @package happyrider
 * @since happyrider 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'HAPPYRIDER_FW_DIR' ) )		define( 'HAPPYRIDER_FW_DIR', '/fw/' );

// Theme timing
if ( ! defined( 'HAPPYRIDER_START_TIME' ) )	define( 'HAPPYRIDER_START_TIME', microtime());			// Framework start time
if ( ! defined( 'HAPPYRIDER_START_MEMORY' ) )	define( 'HAPPYRIDER_START_MEMORY', memory_get_usage());	// Memory usage before core loading

// Global variables storage
global $HAPPYRIDER_GLOBALS;
$HAPPYRIDER_GLOBALS = array();

/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'happyrider_loader_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'happyrider_loader_theme_setup', 20 );
	function happyrider_loader_theme_setup() {
		// Before init theme
		do_action('happyrider_action_before_init_theme');

		// Load current values for main theme options
		happyrider_load_main_options();

		// Theme core init - only for admin side. In frontend it called from header.php
		if ( is_admin() ) {
			happyrider_core_init_theme();
		}
	}
}


/* Include core parts
------------------------------------------------------------------------ */

// Manual load important libraries before load all rest files
// core.strings must be first - we use happyrider_str...() in the happyrider_get_file_dir()
require_once( (file_exists(get_stylesheet_directory().(HAPPYRIDER_FW_DIR).'core/core.strings.php') ? get_stylesheet_directory() : get_template_directory()).(HAPPYRIDER_FW_DIR).'core/core.strings.php' );
// core.files must be first - we use happyrider_get_file_dir() to include all rest parts
require_once( (file_exists(get_stylesheet_directory().(HAPPYRIDER_FW_DIR).'core/core.files.php') ? get_stylesheet_directory() : get_template_directory()).(HAPPYRIDER_FW_DIR).'core/core.files.php' );

// Include core files
happyrider_autoload_folder( 'core' );

// Include custom theme files
happyrider_autoload_folder( 'includes' );

// Include theme templates
happyrider_autoload_folder( 'templates' );

// Include theme widgets
happyrider_autoload_folder( 'widgets' );
?>