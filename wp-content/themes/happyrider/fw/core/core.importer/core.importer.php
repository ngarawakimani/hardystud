<?php
// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Theme init
if (!function_exists('happyrider_importer_theme_setup')) {
	add_action( 'happyrider_action_after_init_theme', 'happyrider_importer_theme_setup' );		// Fire this action after load theme options
	function happyrider_importer_theme_setup() {
		if (is_admin() && current_user_can('import') && happyrider_get_theme_option('admin_dummy_data')=='yes') {
			new happyrider_dummy_data_importer();
		}
	}
}

class happyrider_dummy_data_importer {

	// Theme specific settings
	var $options = array(
		'debug'					=> true,						// Enable debug output
		'enable_importer'		=> true,						// Show Importer section
		'enable_exporter'		=> true,						// Show Exporter section
		'data_type'				=> 'vc',						// Default dummy data type
		'file_with_content'		=> array(
			'no_vc'				=> 'demo/dummy_data.xml',		// Name of the file with demo content without VC wrappers
			'vc'				=> 'demo/dummy_data_vc.xml'		// Name of the file with demo content for Visual Composer
			),
		'file_with_options'		=> 'demo/theme_options.txt',	// Name of the file with theme options
		'file_with_postmeta'	=> 'demo/theme_postmeta.txt',	// Name of the file with post meta
		'file_with_widgets'		=> 'demo/widgets.txt',			// Name of the file with widgets data
		'file_with_booking'		=> 'demo/booking.txt',			// Name of the file with Booking Calendar data
		'folder_with_revsliders'=> 'demo/revslider',			// Name of the folder with revolution sliders data
		'folder_with_essgrids'  => 'demo/essgrid',				// Name of the folder with Essential Grids data
		'domain_dev'			=> '_trex2.happyrider.dnw',		// Domain on developer's server
		'domain_demo'			=> 'trex2.dev.happyrider.net',	// Domain on demo-server
		'uploads_folder'		=> 'imports',					// Folder with images on demo server
		'upload_attachments'	=> true,						// Upload attachments images
		'import_posts'			=> true,						// Import posts
		'import_to'				=> true,						// Import Theme Options
		'import_widgets'		=> true,						// Import widgets
		'import_booking'		=> true,						// Import Booking Calendar
		'import_sliders'		=> true,						// Import sliders
		'import_essgrids'		=> true,						// Import Essential Grids
		'overwrite_content'		=> true,						// Overwrite existing content
		'show_on_front'			=> 'page',						// Reading settings
		'page_on_front'			=> 'Home page',					// Homepage title
		'page_for_posts'		=> 'Blog',					// Blog streampage title
		'menus'					=> array(						// Menus locations and names
			'menu-main'	  => 'Main menu',
			'menu-user'	  => 'User menu',
			'menu-footer' => 'Footer menu',
			'menu-outer'  => 'Main menu'
		),
		'taxonomies'			=> array(),						// List of required taxonomies: 'post_type' => 'taxonomy', ...
		'required_plugins'		=> array( 
			'visual_composer',
			'revslider'
		),
		'additional_options'	=> array(						// Additional options slugs (for export plugins settings). Support wildcards, for example 'woocommerce_%'
			// Visual Composer
			'wpb_js_templates',
			// WooCommerce
			'shop_%',
			'woocommerce_%'
		),
		'wooc_pages'			=> array(						// Options slugs and pages titles for WooCommerce pages
			'woocommerce_shop_page_id' 				=> 'Shop',
			'woocommerce_cart_page_id' 				=> 'Cart',
			'woocommerce_checkout_page_id' 			=> 'Checkout',
			'woocommerce_pay_page_id' 				=> 'Checkout &#8594; Pay',
			'woocommerce_thanks_page_id' 			=> 'Order Received',
			'woocommerce_myaccount_page_id' 		=> 'My Account',
			'woocommerce_edit_address_page_id'		=> 'Edit My Address',
			'woocommerce_view_order_page_id'		=> 'View Order',
			'woocommerce_change_password_page_id'	=> 'Change Password',
			'woocommerce_logout_page_id'			=> 'Logout',
			'woocommerce_lost_password_page_id'		=> 'Lost Password'
		)
	);

	var $error    = '';				// Error message
	var $success  = '';				// Success message
	var $result   = 0;				// Import posts percent (if break inside)
	var $last_slider = 0;			// Last imported slider number

	var $nonce    = '';
	var $export_options = '';
	var $export_templates = '';
	var $export_postmeta = '';
	var $export_widgets = '';
	var $export_booking = '';
	var $uploads_url = '';
	var $uploads_dir = '';
	var $import_log = '';
	var $import_last_id = 0;

	//-----------------------------------------------------------------------------------
	// Constuctor
	//-----------------------------------------------------------------------------------
	function __construct() {
	    $this->options = apply_filters('happyrider_filter_importer_options', $this->options);
		$this->nonce = wp_create_nonce(__FILE__);
		$uploads_info = wp_upload_dir();
		$this->uploads_dir = $uploads_info['basedir'];
		$this->uploads_url = $uploads_info['baseurl'];
		if ($this->options['debug']) define('IMPORT_DEBUG', true);
		$this->import_log = happyrider_get_file_dir('core/core.importer/importer.log');
		$log = explode('|', happyrider_fgc($this->import_log));
		$this->import_last_id = (int) $log[0];
		$this->result = empty($log[1]) ? 0 : (int) $log[1];
		$this->last_slider = empty($log[2]) ? '' : $log[2];
		add_action('admin_menu', array($this, 'admin_menu_item'));
	}

	//-----------------------------------------------------------------------------------
	// Admin Interface
	//-----------------------------------------------------------------------------------
	function admin_menu_item() {
		if ( current_user_can( 'manage_options' ) ) {

			add_submenu_page('happyrider_options', __('Install Dummy Data', 'happyrider'), __('Install Dummy Data', 'happyrider'), 'manage_options', 'trx_importer', array($this, 'build_page'));
		}
	}
	
	
	//-----------------------------------------------------------------------------------
	// Build the Main Page
	//-----------------------------------------------------------------------------------
	function build_page() {
		
		$after_importer = false;

		do {
			if ( isset($_POST['importer_action']) ) {
				if ( !isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'], __FILE__ ) ) {
					$this->error = __('Incorrect WP-nonce data! Operation canceled!', 'happyrider');
					break;
				}
				if ($this->checkRequiredPlugins()) {
					$this->options['overwrite_content']	= $_POST['importer_action']=='overwrite';
					$this->options['data_type'] 		= $_POST['data_type']=='vc' ? 'vc' : 'no_vc';
					$this->options['upload_attachments']= isset($_POST['importer_upload']);
					$this->options['import_posts']		= isset($_POST['importer_posts']);
					$this->options['import_to']			= isset($_POST['importer_to']);
					$this->options['import_widgets']	= isset($_POST['importer_widgets']);
					$this->options['import_booking']	= isset($_POST['importer_booking']);
					$this->options['import_sliders']	= isset($_POST['importer_sliders']);
					$this->options['import_essgrids']	= isset($_POST['importer_essgrids']);
					$this->import_last_id = (int) $_POST['last_id'];
					?>
					<div class="trx_importer_log">
						<?php
						$this->importer();
						$after_importer = true;
						?>
						<script type="text/javascript">
							jQuery(document).ready(function() {
								jQuery('.trx_importer_log').remove();
								<?php if ($this->import_last_id > 0 || (!empty($this->last_slider) && $this->options['import_sliders'])) { ?>
								setTimeout(function() {
									jQuery('#trx_importer_continue').trigger('click');
								}, 3000);
								<?php } ?>
							});
						</script>
					</div>
					<?php
				}
			} else if ( isset($_POST['exporter_action']) ) {
				if ( !isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'], __FILE__ ) ) {
					$this->error = __('Incorrect WP-nonce data! Operation canceled!', 'happyrider');
					break;
				}
				$this->exporter();
			}
		} while (false);
		?>
		<div class="trx_importer">
			<div class="trx_importer_result">
				<?php if (!empty($this->error)) { ?>
				<p>&nbsp;</p>
				<div class="error">
					<p><?php echo ($this->error); ?></p>
				</div>
				<p>&nbsp;</p>
				<?php } ?>
				<?php if (!empty($this->success)) { ?>
				<p>&nbsp;</p>
				<div class="updated">
					<p><?php echo ($this->success); ?></p>
				</div>
				<p>&nbsp;</p>
				<?php } ?>
			</div>
	
			<?php if (empty($this->success) && $this->options['enable_importer']) { ?>
				<div class="trx_importer_section"<?php echo ($after_importer ? ' style="display:none;"' : ''); ?>>
					<h2 class="trx_title"><?php _e('HappyRider Importer', 'happyrider'); ?></h2>
					<p><b><?php _e('Attention! Important info:', 'happyrider'); ?></b></p>
					<ol>
						<li><?php _e('Data import can take a long time (sometimes more than 10 minutes) - please wait until the end of the procedure, do not navigate away from the page.', 'happyrider'); ?></li>
						<li><?php _e('Web-servers set the time limit for the execution of php-scripts. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically!', 'happyrider'); ?></li>
						<li><?php _e('We recommend that you select the first option to import (with the replacement of existing content) - so you get a complete copy of our demo site', 'happyrider'); ?></li>
						<li><?php _e('We also encourage you to leave the enabled check box "Upload attachments" - to download the demo version of the images', 'happyrider'); ?></li>
					</ol>
	
					<form id="trx_importer_form" action="#" method="post">
	
						<input type="hidden" value="<?php echo esc_attr($this->nonce); ?>" name="nonce" />
						<input type="hidden" value="0" name="last_id" />
	
						<p>
						<input type="radio" <?php echo ($this->options['overwrite_content'] ? 'checked="checked"' : ''); ?> value="overwrite" name="importer_action" id="importer_action_over" /><label for="importer_action_over"><?php _e('Overwrite existing content', 'happyrider'); ?></label><br>
						<?php _e('In this case <b>all existing content will be erased</b>! But you get full copy of the our demo site <b>(recommended)</b>.', 'happyrider'); ?>
						</p>
	
						<p>
						<input type="radio" <?php echo !$this->options['overwrite_content'] ? 'checked="checked"' : ''; ?> value="append" name="importer_action" id="importer_action_append" /><label for="importer_action_append"><?php _e('Append to existing content', 'happyrider'); ?></label><br>
						<?php _e('In this case demo data append to the existing content! Warning! In many cases you do not have exact copy of the demo site.', 'happyrider'); ?>
						</p>
	
						<p><b><?php _e('Select the data to import:', 'happyrider'); ?></b></p>
						<p>
						<?php
						$checked = 'checked="checked"';
						if (!empty($this->options['file_with_content']['vc']) && file_exists(happyrider_get_file_dir($this->options['file_with_content']['vc']))) {
							?>
							<input type="radio" <?php echo ($this->options['data_type']=='vc' ? $checked : ''); ?> value="vc" name="data_type" id="data_type_vc" /><label for="data_type_vc"><?php _e('Import data for edit in the Visual Composer', 'happyrider'); ?></label><br>
							<?php
							if ($this->options['data_type']=='vc') $checked = '';
						}
						if (!empty($this->options['file_with_content']['no_vc']) && file_exists(happyrider_get_file_dir($this->options['file_with_content']['no_vc']))) {
							?>
							<input type="radio" <?php echo ($this->options['data_type']=='no_vc' || $checked ? $checked : ''); ?> value="no_vc" name="data_type" id="data_type_no_vc" /><label for="data_type_no_vc"><?php _e('Import data without Visual Composer wrappers', 'happyrider'); ?></label>
							<?php
						}
						?>
						</p>
						<p>
						<input type="checkbox" <?php echo ($this->options['import_posts'] ? 'checked="checked"' : ''); ?> value="1" name="importer_posts" id="importer_posts" /> <label for="importer_posts"><?php _e('Import posts', 'happyrider'); ?></label><br>
						<input type="checkbox" <?php echo ($this->options['upload_attachments'] ? 'checked="checked"' : ''); ?> value="1" name="importer_upload" id="importer_upload" /> <label for="importer_upload"><?php _e('Upload attachments', 'happyrider'); ?></label>
						</p>
						<p>
						<input type="checkbox" <?php echo ($this->options['import_to'] ? 'checked="checked"' : ''); ?> value="1" name="importer_to" id="importer_to" /> <label for="importer_to"><?php _e('Import Theme Options', 'happyrider'); ?></label><br>
						<input type="checkbox" <?php echo ($this->options['import_widgets'] ? 'checked="checked"' : ''); ?> value="1" name="importer_widgets" id="importer_widgets" /> <label for="importer_widgets"><?php _e('Import Widgets', 'happyrider'); ?></label><br>
						<?php if (happyrider_exists_booking()) { ?>
						<input type="checkbox" <?php echo ($this->options['import_booking'] ? 'checked="checked"' : ''); ?> value="1" name="importer_booking" id="importer_booking" /> <label for="importer_booking"><?php _e('Import Booking', 'happyrider'); ?></label><br>
						<?php } ?>
						<input type="checkbox" <?php echo ($this->options['import_sliders'] ? 'checked="checked"' : ''); ?> value="1" name="importer_sliders" id="importer_sliders" /> <label for="importer_sliders"><?php _e('Import Sliders', 'happyrider'); ?></label><br>
						<input type="checkbox" <?php echo ($this->options['import_essgrids'] ? 'checked="checked"' : ''); ?> value="1" name="importer_essgrids" id="importer_essgrids" /> <label for="importer_essgrids"><?php _e('Import Ess.Grids', 'happyrider'); ?></label>
						</p>
	
						<div class="trx_buttons">
							<?php if ($this->import_last_id > 0 || (!empty($this->last_slider) && $this->options['import_sliders'])) { ?>
								<h4 class="trx_importer_complete"><?php sprintf(__('Import posts completed by %s', 'happyrider'), $this->result.'%'); ?></h4>
								<input type="submit" value="<?php printf(__('Continue import (from ID=%s)', 'happyrider'), $this->import_last_id); ?>" onClick="this.form.last_id.value='<?php echo esc_attr($this->import_last_id); ?>'" id="trx_importer_continue">
								<input type="submit" value="<?php _e('Start import again', 'happyrider'); ?>">
							<?php } else { ?>
								<input type="submit" value="<?php _e('Start import', 'happyrider'); ?>">
							<?php } ?>
						</div>
					</form>
				</div>
			<?php } ?>

			<?php if (empty($this->success) && $this->options['enable_exporter']) { ?>
				<div class="trx_exporter_section"<?php echo ($after_importer ? ' style="display:none;"' : ''); ?>>
					<h2 class="trx_title"><?php _e('HappyRider Exporter', 'happyrider'); ?></h2>
					<form id="trx_exporter_form" action="#" method="post">
	
						<input type="hidden" value="<?php echo esc_attr($this->nonce); ?>" name="nonce" />
						<input type="hidden" value="all" name="exporter_action" />
	
						<div class="trx_buttons">
							<?php if ($this->export_options!='') { ?>
								<h4><?php _e('Theme Options', 'happyrider'); ?></h4>
								<textarea rows="10" cols="80"><?php echo esc_html($this->export_options); ?></textarea>
								<h4><?php _e('Templates Options', 'happyrider'); ?></h4>
								<textarea rows="10" cols="80"><?php echo esc_html($this->export_templates); ?></textarea>
								<h4><?php _e('Widgets', 'happyrider'); ?></h4>
								<textarea rows="10" cols="80"><?php echo esc_html($this->export_widgets); ?></textarea>
								<?php if (happyrider_exists_booking()) { ?>
								<h4><?php _e('Booking', 'happyrider'); ?></h4>
								<textarea rows="10" cols="80"><?php echo esc_html($this->export_booking); ?></textarea>
								<?php } ?>
							<?php } else { ?>
								<input type="submit" value="<?php _e('Export Theme Options', 'happyrider'); ?>">
							<?php } ?>
						</div>
	
					</form>
				</div>
			<?php } ?>
		</div>
		<?php
	}
	
	
	//-----------------------------------------------------------------------------------
	// Export dummy data
	//-----------------------------------------------------------------------------------
	function exporter() {
		global $wpdb;
		$suppress = $wpdb->suppress_errors();

		// Export theme and categories options and VC templates
		$rows = $wpdb->get_results( "SELECT option_name, option_value FROM " . esc_sql($wpdb->options) . " WHERE option_name LIKE 'happyrider_options%'" );
		$options = array();
		if (is_array($rows) && count($rows) > 0) {
			foreach ($rows as $row) {
				$options[$row->option_name] = $this->prepare_uploads(unserialize($row->option_value));
			}
		}
		// Export additional options
		if (is_array($this->options['additional_options']) && count($this->options['additional_options']) > 0) {
			foreach ($this->options['additional_options'] as $opt) {
				$rows = $wpdb->get_results( "SELECT option_name, option_value FROM " . esc_sql($wpdb->options) . " WHERE option_name LIKE '" . esc_sql($opt) . "'" );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$options[$row->option_name] = maybe_unserialize($row->option_value);
					}
				}
			}
		}
		$this->export_options = base64_encode(serialize($this->prepare_domains($options)));

		// Export templates options
		$rows = $wpdb->get_results( "SELECT option_name, option_value FROM " . esc_sql($wpdb->options) . " WHERE option_name LIKE 'happyrider_options_template_%'" );
		$options = array();
		if (is_array($rows) && count($rows) > 0) {
			foreach ($rows as $row) {
				$options[$row->option_name] = $this->prepare_uploads(unserialize($row->option_value));
			}
		}
		$this->export_templates = base64_encode(serialize($this->prepare_domains($options)));

		// Export widgets
		$rows = $wpdb->get_results( "SELECT option_name, option_value FROM " . esc_sql($wpdb->options) . " WHERE option_name = 'sidebars_widgets' OR option_name LIKE 'widget_%'" );
		$options = array();
		if (is_array($rows) && count($rows) > 0) {
			foreach ($rows as $row) {
				$options[$row->option_name] = $this->prepare_uploads(unserialize($row->option_value));
			}
		}
		$this->export_widgets = base64_encode(serialize($this->prepare_domains($options)));

		// Export Booking Calendar
		if (happyrider_exists_booking()) {
			$options = array();
			$rows = $wpdb->get_results( "SELECT * FROM ".esc_sql($wpdb->prefix)."booking_calendars", ARRAY_A );
			$options['booking_calendars'] = $rows;
			$rows = $wpdb->get_results( "SELECT * FROM ".esc_sql($wpdb->prefix)."booking_categories", ARRAY_A );
			$options['booking_categories'] = $rows;
			$rows = $wpdb->get_results( "SELECT * FROM ".esc_sql($wpdb->prefix)."booking_config", ARRAY_A );
			$options['booking_config'] = $rows;
			$rows = $wpdb->get_results( "SELECT * FROM ".esc_sql($wpdb->prefix)."booking_reservation", ARRAY_A );
			$options['booking_reservation'] = $rows;
			$rows = $wpdb->get_results( "SELECT * FROM ".esc_sql($wpdb->prefix)."booking_slots", ARRAY_A );
			$options['booking_slots'] = $rows;
			$this->export_booking = base64_encode(serialize($options));
		}

		$wpdb->suppress_errors( $suppress );
	}
	
	
	//-----------------------------------------------------------------------------------
	// Import dummy data
	//-----------------------------------------------------------------------------------
	function importer() {
		?>
		<p>&nbsp;</p>
		<div class="error">
			<h4><?php echo __('Import progress:', 'happyrider') . ' <span id="import_progress_value">' . (!empty($this->last_slider) && $this->options['import_sliders'] ? 99 : $this->result) . '</span>%'; ?></h4>
			<p><?php echo __('Status:', 'happyrider'); ?> <span id="import_progress_status"></span></p>
			<p><?php echo __('Data import can take a long time (sometimes more than 10 minutes)!', 'happyrider')
				. '<br>' . __('Please wait until the end of the procedure, do not navigate away from the page!', 'happyrider'); ?></p>
		</div>
		<p>&nbsp;</p>
		<?php
		// Import posts, pages, menu items, etc.
		$result = 100;
		if ($this->options['import_posts'] && (empty($this->last_slider) || !$this->options['import_sliders'])) {
			// Load WP Importer class
			if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true); // we are loading importers
			if ( !class_exists('WP_Import') ) {
				require(happyrider_get_file_dir('core/core.importer/wordpress-importer.php'));
			}
			if ( class_exists( 'WP_Import' ) ) {
				$result = $this->import_posts();
				if ($result >= 100) {
					if (in_array('woocommerce', $this->options['required_plugins']))
						$this->setup_woocommerce_pages();
					$this->setup_menus();
				} else {
					$log = explode('|', happyrider_fgc($this->import_log));
					$this->import_last_id = (int) $log[0];
				}
			}
		}

		// Import Theme Options
		if ($result>=100 && $this->options['import_to'] && (empty($this->last_slider) || !$this->options['import_sliders'])) {
			happyrider_options_reset();
			$this->import_theme_options();
		}

		// Import Widgets
		if ($result>=100 && $this->options['import_widgets'] && (empty($this->last_slider) || !$this->options['import_sliders']))
			$this->import_widgets();

		// Import Booking Calendar
		if ($result>=100 && $this->options['import_booking'] && (empty($this->last_slider) || !$this->options['import_sliders']))
			$this->import_booking();

		// Import Ess.Grids
		if ($result>=100 && $this->options['import_essgrids'] && (empty($this->last_slider) || !$this->options['import_sliders']))
			$this->import_essgrids();

		// Import Sliders
		if ($result>=100 && $this->options['import_sliders'])
			$this->import_sliders();

		// Setup Front page and Blog page
		if ($result>=100 && $this->options['import_posts'] && (empty($this->last_slider) || !$this->options['import_sliders'])) {
			// Set reading options
			$home_page = get_page_by_title( $this->options['page_on_front'] );
			$posts_page = get_page_by_title( $this->options['page_for_posts'] );
			if ($home_page->ID && $posts_page->ID) {
				update_option('show_on_front', $this->options['show_on_front']);
				update_option('page_on_front', $home_page->ID); 	// Front Page
				update_option('page_for_posts', $posts_page->ID);	// Blog Page
			}

			// Flush rules after install
			flush_rewrite_rules();
		}
		// finally redirect to success page
		if ($result >= 100 && (empty($this->last_slider) || !$this->options['import_sliders'])) {
			$this->success = __('Congratulations! Import demo data finished successfull!', 'happyrider');
		} else {
			$this->error = '<h4>' . sprintf(__('Import progress: %s.', 'happyrider'), max(99, $result).'%') . '</h4>'
				. __('Due to the expiration of the time limit for the execution of scripts on your server, the import process is interrupted!', 'happyrider')
				. '<br>' . __('After 3 seconds, the import will continue automatically!', 'happyrider');
			$this->result = $result;
		}
	}
	
	//==========================================================================================
	// Utilities
	//==========================================================================================

	// Check for required plugings
	function checkRequiredPlugins() {
		$not_installed = '';
		if (in_array('visual_composer', $this->options['required_plugins']) && $_POST['data_type']=='vc' && !happyrider_exists_visual_composer() )
			$not_installed .= '<br>Visual Composer';
		if (in_array('woocommerce', $this->options['required_plugins']) && !happyrider_exists_woocommerce() )
			$not_installed .= '<br>WooCommerce';
		if (in_array('revslider', $this->options['required_plugins']) && !happyrider_exists_revslider())
			$not_installed .= '<br>Revolution Slider';
		if (in_array('instagram', $this->options['required_plugins']) && !happyrider_exists_instagram())
			$not_installed .= '<br>Instagram Widget';
		$not_installed = apply_filters('happyrider_filter_importer_required_plugins', $not_installed);
		if ($not_installed) {
			$this->error = '<b>'.__('Attention! For correct installation of the demo data, you must install and activate the following plugins: ', 'happyrider').'</b>'.($not_installed);
			$this->options['enable_importer'] = false;
			$this->options['enable_exporter'] = false;
			return false;
		}
		return true;
	}


	// Import XML file with posts data
	function import_posts() {
		if (empty($this->options['file_with_content'][$this->options['data_type']])) return;
		echo ($this->import_last_id == 0 
			? '<h3>'.__('Start Import', 'happyrider').'</h3>'
			: '<h3>'.sprintf(__('Continue Import from ID=%d', 'happyrider'), $this->import_last_id).'</h3>');
		echo '<b>' . __('Import Posts (pages, menus, attachments, etc) ...', 'happyrider').'</b><br>'; flush();
		$theme_xml = happyrider_get_file_dir($this->options['file_with_content'][$this->options['data_type']]);
		$importer = new WP_Import();
		$importer->fetch_attachments = $this->options['upload_attachments'];
		$importer->overwrite = $this->options['overwrite_content'];
		$importer->debug = $this->options['debug'];
		$importer->uploads_folder = $this->options['uploads_folder'];
		$importer->demo_url = 'http://' . $this->options['domain_demo'] . '/';
		$importer->start_from_id = $this->import_last_id;
		$importer->import_log = $this->import_log;
		if ($this->import_last_id == 0) $this->clear_tables();
		$this->prepare_taxonomies();
		if (!$this->options['debug']) ob_start();
		$result = $importer->import($theme_xml);
		if (!$this->options['debug']) ob_end_clean();
		if ($result>=100) happyrider_fpc($this->import_log, '');
		return $result;
	}
	
	
	// Delete all data from tables
	function clear_tables() {
		global $wpdb;
		if ($this->options['overwrite_content']) {
			echo '<br><b>'.__('Clear tables ...', 'happyrider').'</b><br>'; flush();
			if ($this->options['import_posts']) {
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->comments));
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "comments".', 'happyrider' ) . ' ' . ($res->get_error_message()) . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->commentmeta));
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "commentmeta".', 'happyrider' ) . ' ' . ($res->get_error_message()) . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->postmeta));
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "postmeta".', 'happyrider' ) . ' ' . ($res->get_error_message()) . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->posts));
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "posts".', 'happyrider' ) . ' ' . ($res->get_error_message()) . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->terms));
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "terms".', 'happyrider' ) . ' ' . ($res->get_error_message()) . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->term_relationships));
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "term_relationships".', 'happyrider' ) . ' ' . ($res->get_error_message()) . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->term_taxonomy));
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "term_taxonomy".', 'happyrider' ) . ' ' . ($res->get_error_message()) . '<br />';
			}
			if ($this->options['import_sliders'] && happyrider_exists_revslider()) {
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix) . "revslider_sliders");
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "revslider_sliders".', 'happyrider' ) . ' ' . ($res->get_error_message()) . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix) . "revslider_slides");
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "revslider_slides".', 'happyrider' ) . ' ' . ($res->get_error_message()) . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix) . "revslider_static_slides");
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "revslider_static_slides".', 'happyrider' ) . ' ' . ($res->get_error_message()) . '<br />';
			}
			if ($this->options['import_booking'] && happyrider_exists_booking()) {
				$rows = $wpdb->query( "TRUNCATE TABLE ".esc_sql($wpdb->prefix)."booking_calendars" );
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "booking_calendars".', 'happyrider' ) . ' ' . ($res->get_error_message()) . '<br />';
				$rows = $wpdb->query( "TRUNCATE TABLE ".esc_sql($wpdb->prefix)."booking_categories");
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "booking_categories".', 'happyrider' ) . ' ' . ($res->get_error_message()) . '<br />';
				$rows = $wpdb->query( "TRUNCATE TABLE ".esc_sql($wpdb->prefix)."booking_config");
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "booking_config".', 'happyrider' ) . ' ' . ($res->get_error_message()) . '<br />';
				$rows = $wpdb->query( "TRUNCATE TABLE ".esc_sql($wpdb->prefix)."booking_reservation");
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "booking_reservation".', 'happyrider' ) . ' ' . ($res->get_error_message()) . '<br />';
				$rows = $wpdb->query( "TRUNCATE TABLE ".esc_sql($wpdb->prefix)."booking_slots" );
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "booking_slots".', 'happyrider' ) . ' ' . ($res->get_error_message()) . '<br />';
			}
		}
	}

	
	// Prepare additional taxes
	function prepare_taxonomies() {
		if (!function_exists('happyrider_require_data')) return;
		if (isset($this->options['taxonomies']) && is_array($this->options['taxonomies']) && count($this->options['taxonomies']) > 0) {
			foreach ($this->options['taxonomies'] as $type=>$tax) {
				happyrider_require_data( 'taxonomy', $tax, array(
					'post_type'			=> array( $type ),
					'hierarchical'		=> false,
					'query_var'			=> $tax,
					'rewrite'			=> true,
					'public'			=> false,
					'show_ui'			=> false,
					'show_admin_column'	=> false,
					'_builtin'			=> false
					)
				);
			}
		}
	}
	

	// Set WooCommerce pages
	function setup_woocommerce_pages() {
		if (is_array($this->options['wooc_pages']) && count($this->options['wooc_pages']) > 0) {
			foreach ($this->options['wooc_pages'] as $woo_page_name => $woo_page_title) {
				$woopage = get_page_by_title( $woo_page_title );
				if ($woopage->ID) {
					update_option($woo_page_name, $woopage->ID);
				}
			}
		}
		// We no longer need to install pages
		delete_option( '_wc_needs_pages' );
		delete_transient( '_wc_activation_redirect' );
	}


	// Set imported menus to registered theme locations
	function setup_menus() {
		echo '<script>'
			. 'document.getElementById("import_progress_status").innerHTML = "' . __('Setup menus ...', 'happyrider') .'";'
			. '</script>';
		echo '<br><b>'.__('Setup menus ...', 'happyrider').'</b><br>'; flush();
		$locations = get_theme_mod( 'nav_menu_locations' );
		$menus = wp_get_nav_menus();
		if (is_array($menus) && count($menus) > 0) {
			foreach ($menus as $menu) {
				if (is_array($this->options['menus']) && count($this->options['menus']) > 0) {
					foreach ($this->options['menus'] as $loc=>$name) {
						if ($menu->name == $name)
							$locations[$loc] = $menu->term_id;
					}
				}
			}
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}


	// Import theme options
	function import_theme_options() {
		if (empty($this->options['file_with_options'])) return;
		echo '<script>'
			. 'document.getElementById("import_progress_status").innerHTML = "' . __('Import Theme Options ...', 'happyrider') .'";'
			. '</script>';
		echo '<br><b>'.__('Import Theme Options ...', 'happyrider').'</b><br>'; flush();
		$theme_options_txt = happyrider_fgc(happyrider_get_file_dir($this->options['file_with_options']));
		$data = unserialize( base64_decode( $theme_options_txt) );
		// Replace upload url in options
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				if (is_array($v) && count($v) > 0) {
					foreach ($v as $k1=>$v1) {
						$v[$k1] = $this->replace_uploads($v1);
					}
				} else
					$v = $this->replace_uploads($v);
				if ($k == 'mega_main_menu_options' && isset($v['last_modified']))
					$v['last_modified'] = time()+30;
				update_option( $k, $v );
			}
		}
		happyrider_load_main_options();
	}


	// Import post meta options
	function import_postmeta() {
		if (empty($this->options['file_with_postmeta'])) return;
		$theme_options_txt = happyrider_fgc(happyrider_get_file_dir($this->options['file_with_postmeta']));
		$data = unserialize( base64_decode( $theme_options_txt) );
		// Replace upload url in options
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				if (is_array($v) && count($v) > 0) {
					foreach ($v as $k1=>$v1) {
						$v[$k1] = $this->replace_uploads($v1);
					}
				}
				update_post_meta( $k, $v['key'], $v['value'] );
			}
		}
	}


	// Import widgets
	function import_widgets() {
		if (empty($this->options['file_with_widgets'])) return;
		echo '<script>'
			. 'document.getElementById("import_progress_status").innerHTML = "' . __('Import Widgets ...', 'happyrider') .'";'
			. '</script>';
		echo '<br><b>'.__('Import Widgets ...', 'happyrider').'</b><br>'; flush();
		$widgets_txt = happyrider_fgc(happyrider_get_file_dir($this->options['file_with_widgets']));
		$data = unserialize( base64_decode( $widgets_txt ) );
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				update_option( $k, $this->replace_uploads($v) );
			}
		}
	}


	// Import Booking Calendar
	function import_booking() {
		if (!happyrider_exists_booking()) return;
		if (empty($this->options['file_with_booking'])) return;
		echo '<script>'
			. 'document.getElementById("import_progress_status").innerHTML = "' . __('Import Booking Calendar ...', 'happyrider') .'";'
			. '</script>';
		echo '<br><b>'.__('Import Booking Calendar ...', 'happyrider').'</b><br>'; flush();
		$booking_txt = happyrider_fgc(happyrider_get_file_dir($this->options['file_with_booking']));
		$data = unserialize( base64_decode( $booking_txt ) );
		if (is_array($data) && count($data) > 0) {
			global $wpdb;
			foreach ($data as $table=>$rows) {
				$values = '';
				$fields = '';
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$f = '';
						$v = '';
						if (is_array($row) && count($row) > 0) {
							foreach ($row as $field => $value) {
								$f .= ($f ? ',' : '') . "'" . esc_sql($field) . "'";
								$v .= ($v ? ',' : '') . "'" . esc_sql($value) . "'";
							}
						}
						if ($fields == '') $fields = '(' . $f . ')';
						$values .= ($values ? ',' : '') . '(' . $v . ')';
					}
				}
				// Attention! All items in the variable $values escaped on the loop above - esc_sql($value)
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix . $table));
				$q = "INSERT INTO ".esc_sql($wpdb->prefix . $table)." VALUES {$values}";
				$wpdb->query($q);
			}
		}
	}


	// Import sliders
	function import_sliders() {
		// Revolution Sliders
		if (happyrider_exists_revslider() && file_exists(WP_PLUGIN_DIR.'/revslider/revslider.php')) {
			require_once(WP_PLUGIN_DIR.'/revslider/revslider.php');
			$dir = happyrider_get_folder_dir($this->options['folder_with_revsliders']);
			if ( is_dir($dir) ) {
				$hdir = @opendir( $dir );
				if ( $hdir ) {
					echo '<script>'
						. 'document.getElementById("import_progress_status").innerHTML = "' . __('Import Revolution sliders ...', 'happyrider') .'";'
						. '</script>';
					echo '<br><b>'.__('Import Revolution sliders ...', 'happyrider').'</b><br>'; flush();
					$slider = new RevSlider();
					$counter = 0;
					while (($file = readdir( $hdir ) ) !== false ) {
						$counter++;
						if ($counter <= $this->last_slider) continue;
						$pi = pathinfo( ($dir) . '/' . ($file) );
						if ( substr($file, 0, 1) == '.' || is_dir( ($dir) . '/' . ($file) ) || $pi['extension']!='zip' )
							continue;
if ($this->options['debug']) printf(__('Slider "%s":', 'happyrider'), $file);
						if (!is_array($_FILES)) $_FILES = array();
						$_FILES["import_file"] = array("tmp_name" => ($dir) . '/' . ($file));
						$response = $slider->importSliderFromPost();
						if ($response["success"] == false) { 
if ($this->options['debug']) echo ' '.__('import error:', 'happyrider').'<br>'.happyrider_debug_dump_var($response);
						} else {
if ($this->options['debug']) echo ' '.__('imported', 'happyrider').'<br>';
						}
						flush();
						break;
					}
					@closedir( $hdir );
					// Write last slider into log
					happyrider_fpc($this->import_log, $file ? '0|100|'.intval($counter) : '');
					$this->last_slider = $file ? $counter : 0;
				}
			}
		} else {
			if ($this->options['debug']) { printf(__('Can not locate Revo plugin: %s', 'happyrider'), WP_PLUGIN_DIR.'/revslider/revslider.php<br>'); flush(); }
		}
	}


	// Import Essential Grids
	function import_essgrids() {
		if (happyrider_exists_essgrids()) {
			$dir = happyrider_get_folder_dir($this->options['folder_with_essgrids']);
			if ( is_dir($dir) ) {
				$hdir = @opendir( $dir );
				if ( $hdir ) {
					echo '<br><b>'.__('Import Essential Grids ...', 'happyrider').'</b><br>'; flush();
					while (($file = readdir( $hdir ) ) !== false ) {
						$pi = pathinfo( ($dir) . '/' . ($file) );
						if ( substr($file, 0, 1) == '.' || is_dir( ($dir) . '/' . ($file) ) || $pi['extension']!='json' )
							continue;
if ($this->options['debug']) printf(__('Ess.Grid "%s":', 'happyrider'), $file);
						try{
							$im = new Essential_Grid_Import();
							$data = json_decode(happyrider_fgc(($dir) . '/' . ($file)), true);
							// Prepare arrays with overwrite flags
							$tmp = array();
							if (is_array($data) && count($data) > 0) {
								foreach ($data as $k=>$v) {
									if ($k=='grids') {			$name = 'grids'; $name_1= 'grid'; $name_id='id'; }
									else if ($k=='skins') {		$name = 'skins'; $name_1= 'skin'; $name_id='id'; }
									else if ($k=='elements') {	$name = 'elements'; $name_1= 'element'; $name_id='id'; }
									else if ($k=='navigation-skins') {	$name = 'navigation-skins'; $name1= 'nav-skin'; $name_id='id'; }
									else if ($k=='punch-fonts') {	$name = 'punch-fonts'; $name1= 'punch-fonts'; $name_id='handle'; }
									else if ($k=='custom-meta') {	$name = 'custom-meta'; $name1= 'custom-meta'; $name_id='handle'; }
									if ($k=='global-css') {
										$tmp['import-global-styles'] = "on";
										$tmp['global-styles-overwrite'] = "append";
									} else {
										$tmp['import-'.$name] = "true";
										$tmp['import-'.$name.'-'.$name_id] = array();
										if (is_array($v) && count($v) > 0) {
											foreach ($v as $v1) {
												$tmp['import-'.$name.'-'.$name_id][] = $v1[$name_id];
												$tmp[$name_1.'-overwrite-'.$name_id] = 'append';
											}
										}
									}
								}
							}
							$im->set_overwrite_data($tmp); //set overwrite data global to class
							
							$skins = @$data['skins'];
							if (!empty($skins) && is_array($skins)){
								$skins_ids = @$tmp['import-skins-id'];
								$skins_imported = $im->import_skins($skins, $skins_ids);
							}
							
							$navigation_skins = @$data['navigation-skins'];
							if (!empty($navigation_skins) && is_array($navigation_skins)){
								$navigation_skins_ids = @$tmp['import-navigation-skins-id'];
								$navigation_skins_imported = $im->import_navigation_skins(@$navigation_skins, $navigation_skins_ids);
							}
							
							$grids = @$data['grids'];
							if (!empty($grids) && is_array($grids)){
								$grids_ids = @$tmp['import-grids-id'];
								$grids_imported = $im->import_grids($grids, $grids_ids);
							}
							
							$elements = @$data['elements'];
							if (!empty($elements) && is_array($elements)){
								$elements_ids = @$tmp['import-elements-id'];
								$elements_imported = $im->import_elements(@$elements, $elements_ids);
							}
							
							$custom_metas = @$data['custom-meta'];
							if (!empty($custom_metas) && is_array($custom_metas)){
								$custom_metas_handle = @$tmp['import-custom-meta-handle'];
								$custom_metas_imported = $im->import_custom_meta($custom_metas, $custom_metas_handle);
							}
							
							$custom_fonts = @$data['punch-fonts'];
							if (!empty($custom_fonts) && is_array($custom_fonts)){
								$custom_fonts_handle = @$tmp['import-punch-fonts-handle'];
								$custom_fonts_imported = $im->import_punch_fonts($custom_fonts, $custom_fonts_handle);
							}
							
							if (@$tmp['import-global-styles'] == 'on'){
								$global_css = @$data['global-css'];
								$global_styles_imported = $im->import_global_styles($tglobal_css);
							}

if ($this->options['debug']) echo ' '.__('imported', 'happyrider').'<br>';
							
						} catch (Exception $d) {

if ($this->options['debug']) echo ' '.__('import error:', 'happyrider').'<br>'.ddo($response);

						}
						
						flush();
						break;
					}
					@closedir( $hdir );
				}
			}
		} else {
if ($this->options['debug']) { printf(__('Can not locate Essential Grid plugin: %s', 'happyrider'), EG_PLUGIN_PATH.'/essential-grid.php<br>'); flush(); }
		}
	}

	
	// Replace uploads dir to new url
	function replace_uploads($str) {
		/*
		if (is_array($str) && count($str) > 0) {
			foreach ($str as $k=>$v) {
				$str[$k] = $this->replace_uploads($v);
			}
		} else if (is_string($str)) {
			while (($pos = happyrider_strpos($str, "/{$this->options['uploads_folder']}/"))!==false) {
				$pos0 = $pos;
				while ($pos0) {
					if (happyrider_substr($str, $pos0, 5)=='http:')
						break;
					$pos0--;
				}
				$str = ($pos0 > 0 ? happyrider_substr($str, 0, $pos0) : '') . ($this->uploads_url) . happyrider_substr($str, $pos+happyrider_strlen($this->options['uploads_folder'])+1);
			}
		}
		return $str;
		*/
		return happyrider_replace_uploads_url($str, $this->options['uploads_folder']);
	}

	
	// Replace uploads dir to imports then export data
	function prepare_uploads($str) {
		if ($this->options['uploads_folder']=='uploads') return $str;
		if (is_array($str) && count($str) > 0) {
			foreach ($str as $k=>$v) {
				$str[$k] = $this->prepare_uploads($v);
			}
		} else if (is_string($str)) {
			$str = str_replace('/uploads/', "/{$this->options['uploads_folder']}/", $str);
		}
		return $str;
	}
	
	// Replace dev domain to demo domain then export data
	function prepare_domains($str) {
		if ($this->options['domain_dev']==$this->options['domain_demo']) return $str;
		if (is_array($str) && count($str) > 0) {
			foreach ($str as $k=>$v) {
				$str[$k] = $this->prepare_domains($v);
			}
		} else if (is_string($str)) {
			$str = str_replace($this->options['domain_dev'], $this->options['domain_demo'], $str);
		}
		return $str;
	}
}
?>