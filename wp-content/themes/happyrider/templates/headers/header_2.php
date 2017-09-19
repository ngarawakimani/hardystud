<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'happyrider_template_header_2_theme_setup' ) ) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_template_header_2_theme_setup', 1 );
	function happyrider_template_header_2_theme_setup() {
		happyrider_add_template(array(
			'layout' => 'header_2',
			'mode'   => 'header',
			'title'  => __('Header 2', 'happyrider'),
			'icon'   => happyrider_get_file_url('templates/headers/images/2.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'happyrider_template_header_2_output' ) ) {
	function happyrider_template_header_2_output($post_options, $post_data) {
		global $HAPPYRIDER_GLOBALS;

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
				? ' style="background: url('.esc_url($header_image).') repeat center top"' 
				: '';
		}
		?>

		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_2 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_2 top_panel_position_<?php echo esc_attr(happyrider_get_custom_option('top_panel_position')); ?>">
			
			<?php if (happyrider_get_custom_option('show_top_panel_top')=='yes') { ?>
				<div class="top_panel_top">
					<div class="content_wrap clearfix">
						<?php
						$top_panel_top_components = array('contact_info', 'open_hours', 'login', 'socials', 'currency', 'bookmarks');
						require_once( happyrider_get_file_dir('templates/headers/_parts/top-panel-top.php') );
						?>
					</div>
				</div>
			<?php } ?>

			<div class="top_panel_middle" <?php echo ($header_css); ?>>
				<div class="content_wrap">
					<div class="columns_wrap columns_fluid"><?php
						// Phone and email
						$contact_phone=trim(happyrider_get_custom_option('contact_phone'));
						$contact_email=trim(happyrider_get_custom_option('contact_email'));
						if (!empty($contact_phone) || !empty($contact_email)) {
							?><div class="column-1_4 contact_field contact_phone">
								<span class="contact_icon icon-phone"></span>
								<span class="contact_label contact_phone"><?php echo force_balance_tags($contact_phone); ?></span>
								<span class="contact_email"><?php echo force_balance_tags($contact_email); ?></span>
							</div><?php
						}
						?><div class="column-1_2 contact_logo">
							<?php require_once( happyrider_get_file_dir('templates/headers/_parts/logo.php') ); ?>
						</div><?php
						// Woocommerce Cart
						if (happyrider_exists_woocommerce() && (happyrider_is_woocommerce_page() && happyrider_get_custom_option('show_cart')=='shop' || happyrider_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) {
							?><div class="column-1_4 contact_field contact_cart"><?php require_once( happyrider_get_file_dir('templates/headers/_parts/contact-info-cart.php') ); ?></div><?php
						}
						?></div>
				</div>
			</div>

			<div class="top_panel_bottom">
				<div class="content_wrap clearfix">
					<a href="#" class="menu_main_responsive_button icon-down"><?php _e('Select menu item', 'happyrider'); ?></a>
					<nav class="menu_main_nav_area">
						<?php
						if (empty($HAPPYRIDER_GLOBALS['menu_main'])) $HAPPYRIDER_GLOBALS['menu_main'] = happyrider_get_nav_menu('menu_main');
						if (empty($HAPPYRIDER_GLOBALS['menu_main'])) $HAPPYRIDER_GLOBALS['menu_main'] = happyrider_get_nav_menu();
						echo ($HAPPYRIDER_GLOBALS['menu_main']);
						?>
					</nav>
					<?php if (happyrider_get_custom_option('show_search')=='yes') echo happyrider_sc_search(array()); ?>
				</div>
			</div>

			</div>
		</header>

		<?php
	}
}
?>