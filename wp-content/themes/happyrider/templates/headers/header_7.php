<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'happyrider_template_header_7_theme_setup' ) ) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_template_header_7_theme_setup', 1 );
	function happyrider_template_header_7_theme_setup() {
		happyrider_add_template(array(
			'layout' => 'header_7',
			'mode'   => 'header',
			'title'  => __('Header 7', 'happyrider'),
			'icon'   => happyrider_get_file_url('templates/headers/images/7.jpg'),
			'thumb_title'  => __('Fullscreen image', 'happyrider'),
			'w'		 => 1920,
			'h'		 => 1080
			));
	}
}

// Template output
if ( !function_exists( 'happyrider_template_header_7_output' ) ) {
	function happyrider_template_header_7_output($post_options, $post_data) {
		global $HAPPYRIDER_GLOBALS;

		// Post data
		
		// Get custom image (for blog) or featured image (for single)
		$header_css = '';
		if (is_singular()) {
			$post_id = get_the_ID();
			$post_format = get_post_format();
			$post_icon = happyrider_get_custom_option('icon', happyrider_get_post_format_icon($post_format));
			$header_image = wp_get_attachment_url(get_post_thumbnail_id($post_id));
		}
		if (empty($header_image))
			$header_image = happyrider_get_custom_option('top_panel_image');
		if (empty($header_image))
			$header_image = get_header_image();
		if (!empty($header_image)) {
			$thumb_sizes = happyrider_get_thumb_sizes(array(
				'layout' => $post_options['layout']
			));
			$header_image = happyrider_get_resized_image_url($header_image, $thumb_sizes['w'], $thumb_sizes['h'], null, false, false, true);
			$header_css = ' style="background-image: url('.esc_url($header_image).')"';
		}
		?>
		
		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_7 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_7 top_panel_position_<?php echo esc_attr(happyrider_get_custom_option('top_panel_position')); ?>">

			<div class="top_panel_middle">
				<div class="content_wrap">
					<div class="columns_wrap columns_fluid"><div
						class="column-1_3 contact_logo">
							<?php require_once( happyrider_get_file_dir('templates/headers/_parts/logo.php') ); ?>
						</div><div 
						class="column-2_3 menu_main_wrap">
							<a href="#" class="menu_main_responsive_button icon-menu"></a>
							<nav class="menu_main_nav_area">
								<?php
								if (empty($HAPPYRIDER_GLOBALS['menu_main'])) $HAPPYRIDER_GLOBALS['menu_main'] = happyrider_get_nav_menu('menu_main');
								if (empty($HAPPYRIDER_GLOBALS['menu_main'])) $HAPPYRIDER_GLOBALS['menu_main'] = happyrider_get_nav_menu();
								echo ($HAPPYRIDER_GLOBALS['menu_main']);
								?>
							</nav>
							<?php
							if (happyrider_exists_woocommerce() && (happyrider_is_woocommerce_page() && happyrider_get_custom_option('show_cart')=='shop' || happyrider_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) {
								?>
								<div class="menu_main_cart top_panel_icon">
									<?php require_once( happyrider_get_file_dir('templates/headers/_parts/contact-info-cart.php') ); ?>
								</div>
								<?php
							}
							if (happyrider_get_custom_option('show_search')=='yes')
								echo happyrider_sc_search(array('class'=>"top_panel_icon", 'state'=>"closed"));
							?>
						</div>
					</div>
				</div>
			</div>
			

			</div>
		</header>

		<section class="top_panel_image" <?php echo ($header_css); ?>>
			<div class="top_panel_image_hover"></div>
			<div class="top_panel_image_header">
				<?php if (!empty($post_icon)) { ?>
				<div class="top_panel_image_icon <?php echo esc_attr($post_icon); ?>"></div>
				<?php } ?>
				<h1 itemprop="headline" class="top_panel_image_title entry-title"><?php echo strip_tags(happyrider_get_blog_title()); ?></h1>
				<div class="breadcrumbs">
					<?php if (!is_404()) happyrider_show_breadcrumbs(); ?>
				</div>
			</div>
		</section>
		<?php
	}
}
?>