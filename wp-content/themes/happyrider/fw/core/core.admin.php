<?php
/**
 * HappyRider Framework: Admin functions
 *
 * @package	happyrider
 * @since	happyrider 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Admin actions and filters:
------------------------------------------------------------------------ */

if (is_admin()) {

	/* Theme setup section
	-------------------------------------------------------------------- */
	
	if ( !function_exists( 'happyrider_admin_theme_setup' ) ) {
		add_action( 'happyrider_action_before_init_theme', 'happyrider_admin_theme_setup', 11 );
		function happyrider_admin_theme_setup() {
			if ( is_admin() ) {
				add_action("admin_head",			'happyrider_admin_prepare_scripts');
				add_action("admin_enqueue_scripts",	'happyrider_admin_load_scripts');
				add_action('tgmpa_register',		'happyrider_admin_register_plugins');

				// AJAX: Get terms for specified post type
				add_action('wp_ajax_happyrider_admin_change_post_type', 		'happyrider_callback_admin_change_post_type');
				add_action('wp_ajax_nopriv_happyrider_admin_change_post_type','happyrider_callback_admin_change_post_type');
			}
		}
	}
	
	// Load required styles and scripts for admin mode
	if ( !function_exists( 'happyrider_admin_load_scripts' ) ) {
		//add_action("admin_enqueue_scripts", 'happyrider_admin_load_scripts');
		function happyrider_admin_load_scripts() {
			happyrider_enqueue_script( 'happyrider-debug-script', happyrider_get_file_url('js/core.debug.js'), array('jquery'), null, true );
			//if (happyrider_options_is_used()) {
				happyrider_enqueue_style( 'happyrider-admin-style', happyrider_get_file_url('css/core.admin.css'), array(), null );
				happyrider_enqueue_script( 'happyrider-admin-script', happyrider_get_file_url('js/core.admin.js'), array('jquery'), null, true );
			//}
			if (happyrider_strpos($_SERVER['REQUEST_URI'], 'widgets.php')!==false) {
				happyrider_enqueue_style( 'happyrider-fontello-style', happyrider_get_file_url('css/fontello-admin/css/fontello-admin.css'), array(), null );
				happyrider_enqueue_style( 'happyrider-animations-style', happyrider_get_file_url('css/fontello-admin/css/animation.css'), array(), null );
			}
		}
	}
	
	// Prepare required styles and scripts for admin mode
	if ( !function_exists( 'happyrider_admin_prepare_scripts' ) ) {
		//add_action("admin_head", 'happyrider_admin_prepare_scripts');
		function happyrider_admin_prepare_scripts() {
			?>
			<script>
				if (typeof HAPPYRIDER_GLOBALS == 'undefined') var HAPPYRIDER_GLOBALS = {};
				jQuery(document).ready(function() {
					HAPPYRIDER_GLOBALS['admin_mode']	= true;
					HAPPYRIDER_GLOBALS['ajax_nonce'] 	= "<?php echo wp_create_nonce('ajax_nonce'); ?>";
					HAPPYRIDER_GLOBALS['ajax_url']	= "<?php echo admin_url('admin-ajax.php'); ?>";
					HAPPYRIDER_GLOBALS['user_logged_in'] = true;
				});
			</script>
			<?php
		}
	}
	
	// AJAX: Get terms for specified post type
	if ( !function_exists( 'happyrider_callback_admin_change_post_type' ) ) {
		//add_action('wp_ajax_happyrider_admin_change_post_type', 		'happyrider_callback_admin_change_post_type');
		//add_action('wp_ajax_nopriv_happyrider_admin_change_post_type',	'happyrider_callback_admin_change_post_type');
		function happyrider_callback_admin_change_post_type() {
			if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ajax_nonce' ) )
				die();
			$post_type = $_REQUEST['post_type'];
			$terms = happyrider_get_list_terms(false, happyrider_get_taxonomy_categories_by_post_type($post_type));
			$terms = happyrider_array_merge(array(0 => __('- Select category -', 'happyrider')), $terms);
			$response = array(
				'error' => '',
				'data' => array(
					'ids' => array_keys($terms),
					'titles' => array_values($terms)
				)
			);
			echo json_encode($response);
			die();
		}
	}

	// Return current post type in dashboard
	if ( !function_exists( 'happyrider_admin_get_current_post_type' ) ) {
		function happyrider_admin_get_current_post_type() {
			global $post, $typenow, $current_screen;
			if ( $post && $post->post_type )							//we have a post so we can just get the post type from that
				return $post->post_type;
			else if ( $typenow )										//check the global $typenow — set in admin.php
				return $typenow;
			else if ( $current_screen && $current_screen->post_type )	//check the global $current_screen object — set in sceen.php
				return $current_screen->post_type;
			else if ( isset( $_REQUEST['post_type'] ) )					//check the post_type querystring
				return sanitize_key( $_REQUEST['post_type'] );
			else if ( isset( $_REQUEST['post'] ) ) {					//lastly check the post id querystring
				$post = get_post( sanitize_key( $_REQUEST['post'] ) );
				return !empty($post->post_type) ? $post->post_type : '';
			} else														//we do not know the post type!
				return '';
		}
	}
	
	// Register optional plugins
	if ( !function_exists( 'happyrider_admin_register_plugins' ) ) {
		function happyrider_admin_register_plugins() {

			$plugins = apply_filters('happyrider_filter_required_plugins', array(
				array(
					'name' 		=> 'Happy-rider Utilities',
					'slug' 		=> 'happyrider-utils',
					'source'	=> happyrider_get_file_dir('plugins/happyrider-utils.zip'),
					'required' 	=> true
				),
				array(
					'name' 		=> 'Visual Composer',
					'slug' 		=> 'js_composer',
					'source'	=> happyrider_get_file_dir('plugins/js_composer.zip'),
					'required' 	=> false
				),
				array(
					'name' 		=> 'Revolution Slider',
					'slug' 		=> 'revslider',
					'source'	=> happyrider_get_file_dir('plugins/revslider.zip'),
					'required' 	=> false
				),
				array(
					'name' 		=> 'Tribe Events Calendar',
					'slug' 		=> 'the-events-calendar',
					//'source'	=> happyrider_get_file_dir('plugins/the-events-calendar.zip'),
					'required' 	=> false
				),
				array(
					'name' 		=> 'Instagram Widget',
					'slug' 		=> 'wp-instagram-widget',
					//'source'	=> happyrider_get_file_dir('plugins/wp-instagram-widget.zip'),
					'required' 	=> false
				),
				array(
					'name' 		=> 'Esential grid',
					'slug' 		=> 'essential-grid',
					'source'	=> happyrider_get_file_dir('plugins/essential-grid.zip'),
					'required' 	=> false
				),
				array(
					'name' 		=> 'WooCommerce',
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				),
				array(
					'name' 		=> 'Booking Calendar WP Plugin',
					'slug' 		=> 'wp-booking-calendar',
					'source'	=> happyrider_get_file_dir('plugins/wp-booking-calendar.zip'),
					'required' 	=> false
				)
			));
			$config = array(
				'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
				'default_path' => '',                      // Default absolute path to bundled plugins.
				'menu'         => 'tgmpa-install-plugins', // Menu slug.
				'parent_slug'  => 'themes.php',            // Parent menu slug.
				'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
				'has_notices'  => true,                    // Show admin notices or not.
				'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => true,                    // Automatically activate plugins after installation or not.
				'message'      => ''                       // Message to output right before the plugins table.
			);	
			tgmpa( $plugins, $config );
		}
	}

	require_once( happyrider_get_file_dir('lib/tgm/class-tgm-plugin-activation.php') );

	require_once( happyrider_get_file_dir('tools/emailer/emailer.php') );
	require_once( happyrider_get_file_dir('tools/po_composer/po_composer.php') );
}

?>