<?php
/**
 * The template for displaying the footer.
 */

global $HAPPYRIDER_GLOBALS;

				happyrider_close_wrapper();	// <!-- </.content> -->

				// Show main sidebar
				get_sidebar();

				if (happyrider_get_custom_option('body_style')!='fullscreen') happyrider_close_wrapper();	// <!-- </.content_wrap> -->
				?>
			
			</div>		<!-- </.page_content_wrap> -->
			
			<?php
			// Footer Testimonials stream
			if (happyrider_get_custom_option('show_testimonials_in_footer')=='yes') {
				$count = max(1, happyrider_get_custom_option('testimonials_count'));
				$data = happyrider_sc_testimonials(array('count'=>$count));
				if ($data) {
					?>
					<footer class="testimonials_wrap sc_section scheme_<?php echo esc_attr(happyrider_get_custom_option('testimonials_scheme')); ?>">
						<div class="testimonials_wrap_inner sc_section_inner sc_section_overlay">
							<div class="content_wrap"><?php echo ($data); ?></div>
						</div>
					</footer>
					<?php
				}
			}
			
// Footer contacts
			if (happyrider_get_custom_option('show_contacts_in_footer')=='yes') {
				$address_1 = happyrider_get_theme_option('contact_address_1');
				$address_2 = happyrider_get_theme_option('contact_address_2');
				$phone = happyrider_get_theme_option('contact_phone');
				$fax = happyrider_get_theme_option('contact_fax');
				if (!empty($address_1) || !empty($address_2) || !empty($phone) || !empty($fax)) {
					?>
					<footer class="contacts_wrap scheme_<?php echo esc_attr(happyrider_get_custom_option('contacts_scheme')); ?>">
						<div class="contacts_wrap_inner">
							<div class="content_wrap">
								<?php require( happyrider_get_file_dir('templates/_parts/logo.php') ); ?>
								<div class="contacts_address">
									<address class="address_right">
										<?php if (!empty($phone)) echo __('Phone:', 'happyrider') . ' ' . ($phone) . '<br>'; ?>
										<?php if (!empty($fax)) echo __('Fax:', 'happyrider') . ' ' . ($fax); ?>
									</address>
									<address class="address_left">
										<?php if (!empty($address_2)) echo ($address_2) . '<br>'; ?>
										<?php if (!empty($address_1)) echo ($address_1); ?>
									</address>
								</div>
								<?php echo happyrider_sc_socials(array('size'=>"medium")); ?>
							</div>	<!-- /.content_wrap -->
						</div>	<!-- /.contacts_wrap_inner -->
					</footer>	<!-- /.contacts_wrap -->
					<?php
				}
			}

			// Google map
			if ( happyrider_get_custom_option('show_googlemap')=='yes' ) {
				$map_address = happyrider_get_custom_option('googlemap_address');
				$map_latlng  = happyrider_get_custom_option('googlemap_latlng');
				$map_zoom    = happyrider_get_custom_option('googlemap_zoom');
				$map_style   = happyrider_get_custom_option('googlemap_style');
				$map_height  = happyrider_get_custom_option('googlemap_height');
				$show_contact_over = happyrider_get_custom_option('show_contact_over');
				if (!empty($map_address) || !empty($map_latlng)) {
					$args = array();
					if (!empty($map_style))		$args['style'] = esc_attr($map_style);
					if (!empty($map_zoom))		$args['zoom'] = esc_attr($map_zoom);
					if (!empty($map_height))	$args['height'] = esc_attr($map_height);
					echo happyrider_sc_googlemap($args);
				}
			}

			
			// Footer sidebar
			$footer_show  = happyrider_get_custom_option('show_sidebar_footer');
			$sidebar_name = happyrider_get_custom_option('sidebar_footer');
			if (!happyrider_param_is_off($footer_show) && is_active_sidebar($sidebar_name)) {
				$HAPPYRIDER_GLOBALS['current_sidebar'] = 'footer';
				?>
				<footer class="footer_wrap widget_area scheme_<?php echo esc_attr(happyrider_get_custom_option('sidebar_footer_scheme')); ?>">
					<div class="footer_wrap_inner widget_area_inner">
						<div class="content_wrap">
							<div class="columns_wrap"><?php
							ob_start();
							do_action( 'before_sidebar' );
							if ( !dynamic_sidebar($sidebar_name) ) {
								// Put here html if user no set widgets in sidebar
							}
							do_action( 'after_sidebar' );
							$out = ob_get_contents();
							ob_end_clean();
							echo trim(chop(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out)));
							?></div>	<!-- /.columns_wrap -->
						</div>	<!-- /.content_wrap -->
					</div>	<!-- /.footer_wrap_inner -->
				</footer>	<!-- /.footer_wrap -->
			<?php
			}



			// Footer Twitter stream
			if (happyrider_get_custom_option('show_twitter_in_footer')=='yes') {
				$count = max(1, happyrider_get_custom_option('twitter_count'));
				$data = happyrider_sc_twitter(array('count'=>$count));
				if ($data) {
					?>
					<footer class="twitter_wrap sc_section scheme_<?php echo esc_attr(happyrider_get_custom_option('twitter_scheme')); ?>">
						<div class="twitter_wrap_inner sc_section_inner sc_section_overlay">
							<div class="content_wrap"><?php echo ($data); ?></div>
						</div>
					</footer>
					<?php
				}
			}



			

			// Copyright area
			$copyright_style = happyrider_get_custom_option('show_copyright_in_footer');
			if (!happyrider_param_is_off($copyright_style)) {
			?> 
				<div class="copyright_wrap copyright_style_<?php echo esc_attr($copyright_style); ?>  scheme_<?php echo esc_attr(happyrider_get_custom_option('copyright_scheme')); ?>">
					<div class="copyright_wrap_inner">
						<div class="content_wrap">
							<?php
							if ($copyright_style == 'menu') {
								if (empty($HAPPYRIDER_GLOBALS['menu_footer']))	$HAPPYRIDER_GLOBALS['menu_footer'] = happyrider_get_nav_menu('menu_footer');
								if (!empty($HAPPYRIDER_GLOBALS['menu_footer']))	echo ($HAPPYRIDER_GLOBALS['menu_footer']);
							} else if ($copyright_style == 'socials') {
								echo happyrider_sc_socials(array('size'=>"tiny"));
							}
							?>
							<div class="copyright_text"><?php echo force_balance_tags(happyrider_get_theme_option('footer_copyright')); ?></div>
						</div>
					</div>
				</div>
			<?php } ?>
			
		</div>	<!-- /.page_wrap -->

	</div>		<!-- /.body_wrap -->
	
	<?php if ( !happyrider_param_is_off(happyrider_get_custom_option('show_sidebar_outer')) ) { ?>
	</div>	<!-- /.outer_wrap -->
	<?php } ?>

<?php
if (happyrider_get_custom_option('show_theme_customizer')=='yes') {
	require_once( happyrider_get_file_dir('core/core.customizer/front.customizer.php') );
}
?>

<a href="#" class="scroll_to_top icon-up" title="<?php _e('Scroll to top', 'happyrider'); ?>"></a>

<div class="custom_html_section">
<?php echo force_balance_tags(happyrider_get_custom_option('custom_code')); ?>
</div>

<?php echo force_balance_tags(happyrider_get_custom_option('gtm_code2')); ?>

<?php wp_footer(); ?>

</body>
</html>