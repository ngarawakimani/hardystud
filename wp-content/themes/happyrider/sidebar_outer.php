<?php
/**
 * The Sidebar Outer containing the outer (left or right) widget areas.
 */

$sidebar_show   = happyrider_get_custom_option('show_sidebar_outer');
$sidebar_scheme = happyrider_get_custom_option('sidebar_outer_scheme');
$sidebar_name   = happyrider_get_custom_option('sidebar_outer');

if (!happyrider_param_is_off($sidebar_show) && is_active_sidebar($sidebar_name)) {
	?>
	<div class="sidebar_outer widget_area scheme_<?php echo esc_attr($sidebar_scheme); ?>" role="complementary">
		<div class="sidebar_outer_inner widget_area_inner">
			<div class="sidebar_outer_logo_wrap">
			<?php
			if (happyrider_get_custom_option('sidebar_outer_show_logo')=='yes') {
				?>
				<div class="sidebar_outer_logo">
					<?php require( happyrider_get_file_dir('templates/_parts/logo.php') ); ?>
				</div>
				<?php
			}
			if (happyrider_get_custom_option('sidebar_outer_show_socials')=='yes') {
				?>
				<div class="sidebar_outer_socials">
					<?php echo happyrider_sc_socials(array('size'=>"tiny", 'shape'=>"round")); ?>
				</div>
				<?php
			}
			?>
			</div>
			<?php
			if (happyrider_get_custom_option('sidebar_outer_show_menu')=='yes') {
				if (empty($HAPPYRIDER_GLOBALS['menu_side']))	$HAPPYRIDER_GLOBALS['menu_side'] = happyrider_get_nav_menu('menu_side');
				if (!empty($HAPPYRIDER_GLOBALS['menu_side']))	{
					?>
					<div class="sidebar_outer_menu">
						<?php echo ($HAPPYRIDER_GLOBALS['menu_side']); ?>
						<span class="sidebar_outer_menu_buttons">
						<a href="#" class="sidebar_outer_menu_responsive_button icon-down"><?php _e('Select menu item', 'happyrider'); ?></a>
						<?php if (happyrider_get_custom_option('sidebar_outer_show_widgets')=='yes') { ?>
						<a href="#" class="sidebar_outer_widgets_button icon-book-open"></a>
						<?php } ?>
						</span>
					</div>
					<?php
				}
			}
			if (happyrider_get_custom_option('sidebar_outer_show_widgets')=='yes') {
				?>
				<div class="sidebar_outer_widgets">
					<?php
					ob_start();
					do_action( 'before_sidebar' );
					global $HAPPYRIDER_GLOBALS;
					$HAPPYRIDER_GLOBALS['current_sidebar'] = 'outer';
					if ( !dynamic_sidebar($sidebar_name) ) {
						// Put here html if user no set widgets in sidebar
					}
					do_action( 'after_sidebar' );
					$out = ob_get_contents();
					ob_end_clean();
					echo trim(chop(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out)));
					?>
				</div> <!-- /.sidebar_outer_widgets -->
				<?php
			}
			?>
		</div> <!-- /.sidebar_outer_inner -->
	</div> <!-- /.sidebar_outer -->
	<?php
}
?>