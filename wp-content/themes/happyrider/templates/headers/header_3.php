<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'happyrider_template_header_3_theme_setup' ) ) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_template_header_3_theme_setup', 1 );
	function happyrider_template_header_3_theme_setup() {
		happyrider_add_template(array(
			'layout' => 'header_3',
			'mode'   => 'header',
			'title'  => __('Header 3', 'happyrider'),
			'icon'   => happyrider_get_file_url('templates/headers/images/3.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'happyrider_template_header_3_output' ) ) {
	function happyrider_template_header_3_output($post_options, $post_data) {
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

		<header class="top_panel_wrap top_panel_style_3 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_3 top_panel_position_<?php echo esc_attr(happyrider_get_custom_option('top_panel_position')); ?>">
			
			<?php if (happyrider_get_custom_option('show_top_panel_top')=='yes') { ?>
				<div class="top_panel_top">
					<div class="content_wrap clearfix">
						<?php
						$top_panel_top_components = array('contact_info');
						require_once( happyrider_get_file_dir('templates/headers/_parts/top-panel-top.php') );
						?>
					</div>
				</div>
			<?php } ?>

			<div class="top_panel_middle" <?php echo ($header_css); ?>>
				<div class="content_wrap">
					<div class="columns_wrap columns_fluid"><div
						class="column-1_3 contact_logo">
							<?php require_once( happyrider_get_file_dir('templates/headers/_parts/logo.php') ); ?>
						</div><div 
						class="column-2_3 menu_main_wrap">
							<a href="#" class="menu_main_responsive_button icon-menu"></a>
							<?php
							
							
							$top_panel_menu_components = array('socials');
							if (in_array('socials', $top_panel_menu_components) && happyrider_get_custom_option('show_socials')=='yes') {
								?>
								<div class="top_panel_top_socials">
									<?php echo happyrider_sc_socials(array('size'=>'tiny')); ?>
								</div>
								<?php } 
								if (happyrider_get_custom_option('show_search')=='yes')
								echo happyrider_sc_search(array('class'=>"top_panel_icon", 'state'=>"closed"));
						?>
						<nav class="menu_main_nav_area">
								<?php
								if (empty($HAPPYRIDER_GLOBALS['menu_main'])) $HAPPYRIDER_GLOBALS['menu_main'] = happyrider_get_nav_menu('menu_main');
								if (empty($HAPPYRIDER_GLOBALS['menu_main'])) $HAPPYRIDER_GLOBALS['menu_main'] = happyrider_get_nav_menu();
								echo ($HAPPYRIDER_GLOBALS['menu_main']);
								?>
							</nav>


						</div>
					</div>
				</div>
			</div>

			</div>
		</header>

		<?php
	}
}
?>