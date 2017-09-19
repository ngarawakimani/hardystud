<?php
/**
 * HappyRider Framework: shortcodes manipulations
 *
 * @package	happyrider
 * @since	happyrider 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('happyrider_sc_theme_setup')) {
	add_action( 'happyrider_action_init_theme', 'happyrider_sc_theme_setup', 1 );
	function happyrider_sc_theme_setup() {
		// Add sc stylesheets
		add_action('happyrider_action_add_styles', 'happyrider_sc_add_styles', 1);
	}
}

if (!function_exists('happyrider_sc_theme_setup2')) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_sc_theme_setup2' );
	function happyrider_sc_theme_setup2() {

		if ( !is_admin() || isset($_POST['action']) ) {
			// Enable/disable shortcodes in excerpt
			add_filter('the_excerpt', 					'happyrider_sc_excerpt_shortcodes');
	
			// Prepare shortcodes in the content
			if (function_exists('happyrider_sc_prepare_content')) happyrider_sc_prepare_content();
		}

		// Add init script into shortcodes output in VC frontend editor
		add_filter('happyrider_shortcode_output', 'happyrider_sc_add_scripts', 10, 4);

		// AJAX: Send contact form data
		add_action('wp_ajax_send_contact_form',			'happyrider_sc_contact_form_send');
		add_action('wp_ajax_nopriv_send_contact_form',	'happyrider_sc_contact_form_send');

		// Show shortcodes list in admin editor
		add_action('media_buttons',						'happyrider_sc_selector_add_in_toolbar', 11);

	}
}


// Add shortcodes styles
if ( !function_exists( 'happyrider_sc_add_styles' ) ) {
	//add_action('happyrider_action_add_styles', 'happyrider_sc_add_styles', 1);
	function happyrider_sc_add_styles() {
		// Shortcodes
		happyrider_enqueue_style( 'happyrider-shortcodes-style',	happyrider_get_file_url('core/core.shortcodes/shortcodes.css'), array(), null );
	}
}


// Add shortcodes init scripts
if ( !function_exists( 'happyrider_sc_add_scripts' ) ) {
	//add_filter('happyrider_shortcode_output', 'happyrider_sc_add_scripts', 10, 4);
	function happyrider_sc_add_scripts($output, $tag='', $atts=array(), $content='') {

		global $HAPPYRIDER_GLOBALS;
		
		if (empty($HAPPYRIDER_GLOBALS['shortcodes_scripts_added'])) {
			$HAPPYRIDER_GLOBALS['shortcodes_scripts_added'] = true;
			//happyrider_enqueue_style( 'happyrider-shortcodes-style', happyrider_get_file_url('core/core.shortcodes/shortcodes.css'), array(), null );
			happyrider_enqueue_script( 'happyrider-shortcodes-script', happyrider_get_file_url('core/core.shortcodes/shortcodes.js'), array('jquery'), null, true );
		}
		
		return $output;
	}
}


/* Prepare text for shortcodes
-------------------------------------------------------------------------------- */

// Prepare shortcodes in content
if (!function_exists('happyrider_sc_prepare_content')) {
	function happyrider_sc_prepare_content() {
		if (function_exists('happyrider_sc_clear_around')) {
			$filters = array(
				array('happyrider', 'sc', 'clear', 'around'),
				array('widget', 'text'),
				array('the', 'excerpt'),
				array('the', 'content')
			);
			if (happyrider_exists_woocommerce()) {
				$filters[] = array('woocommerce', 'template', 'single', 'excerpt');
				$filters[] = array('woocommerce', 'short', 'description');
			}
			if (is_array($filters) && count($filters) > 0) {
				foreach ($filters as $flt)
					add_filter(join('_', $flt), 'happyrider_sc_clear_around', 1);	// Priority 1 to clear spaces before do_shortcodes()
			}
		}
	}
}

// Enable/Disable shortcodes in the excerpt
if (!function_exists('happyrider_sc_excerpt_shortcodes')) {
	function happyrider_sc_excerpt_shortcodes($content) {
		if (!empty($content)) {
			$content = do_shortcode($content);
			//$content = strip_shortcodes($content);
		}
		return $content;
	}
}



/*
// Remove spaces and line breaks between close and open shortcode brackets ][:
[trx_columns]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
[/trx_columns]

convert to

[trx_columns][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][/trx_columns]
*/
if (!function_exists('happyrider_sc_clear_around')) {
	function happyrider_sc_clear_around($content) {
		if (!empty($content)) $content = preg_replace("/\](\s|\n|\r)*\[/", "][", $content);
		return $content;
	}
}






/* Shortcodes support utils
---------------------------------------------------------------------- */

// HappyRider shortcodes load scripts
if (!function_exists('happyrider_sc_load_scripts')) {
	function happyrider_sc_load_scripts() {
		happyrider_enqueue_script( 'happyrider-shortcodes-script', happyrider_get_file_url('core/core.shortcodes/shortcodes_admin.js'), array('jquery'), null, true );
		happyrider_enqueue_script( 'happyrider-selection-script',  happyrider_get_file_url('js/jquery.selection.js'), array('jquery'), null, true );
	}
}

// HappyRider shortcodes prepare scripts
if (!function_exists('happyrider_sc_prepare_scripts')) {
	function happyrider_sc_prepare_scripts() {
		global $HAPPYRIDER_GLOBALS;
		if (!isset($HAPPYRIDER_GLOBALS['shortcodes_prepared'])) {
			$HAPPYRIDER_GLOBALS['shortcodes_prepared'] = true;
			$json_parse_func = 'eval';	// 'JSON.parse'
			?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					try {
						HAPPYRIDER_GLOBALS['shortcodes'] = <?php echo trim($json_parse_func); ?>(<?php echo json_encode( happyrider_array_prepare_to_json($HAPPYRIDER_GLOBALS['shortcodes']) ); ?>);
					} catch (e) {}
					HAPPYRIDER_GLOBALS['shortcodes_cp'] = '<?php echo is_admin() ? (!empty($HAPPYRIDER_GLOBALS['to_colorpicker']) ? $HAPPYRIDER_GLOBALS['to_colorpicker'] : 'wp') : 'custom'; ?>';	// wp | tiny | custom
				});
			</script>
			<?php
		}
	}
}

// Show shortcodes list in admin editor
if (!function_exists('happyrider_sc_selector_add_in_toolbar')) {
	//add_action('media_buttons','happyrider_sc_selector_add_in_toolbar', 11);
	function happyrider_sc_selector_add_in_toolbar(){

		if ( !happyrider_options_is_used() ) return;

		happyrider_sc_load_scripts();
		happyrider_sc_prepare_scripts();

		global $HAPPYRIDER_GLOBALS;

		$shortcodes = $HAPPYRIDER_GLOBALS['shortcodes'];
		$shortcodes_list = '<select class="sc_selector"><option value="">&nbsp;'.__('- Select Shortcode -', 'happyrider').'&nbsp;</option>';

		if (is_array($shortcodes) && count($shortcodes) > 0) {
			foreach ($shortcodes as $idx => $sc) {
				$shortcodes_list .= '<option value="'.esc_attr($idx).'" title="'.esc_attr($sc['desc']).'">'.esc_html($sc['title']).'</option>';
			}
		}

		$shortcodes_list .= '</select>';

		echo ($shortcodes_list);
	}
}

// HappyRider shortcodes builder settings
require_once( happyrider_get_file_dir('core/core.shortcodes/shortcodes_settings.php') );

// VC shortcodes settings
if ( class_exists('WPBakeryShortCode') ) {
	require_once( happyrider_get_file_dir('core/core.shortcodes/shortcodes_vc.php') );
}

// HappyRider shortcodes implementation
require_once( happyrider_get_file_dir('core/core.shortcodes/shortcodes.php') );
?>