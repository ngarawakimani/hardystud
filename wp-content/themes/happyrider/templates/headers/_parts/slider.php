<?php
if (happyrider_get_custom_option('show_slider')=='yes') {
	$slider = happyrider_get_custom_option('slider_engine');
	$slider_alias = $slider_ids = $slider_html = '';

	if ($slider == 'revo' && happyrider_exists_revslider()) {
		$slider_alias = happyrider_get_custom_option('slider_alias');
		if (!empty($slider_alias)) $slider_html = happyrider_do_shortcode('[rev_slider '.esc_attr($slider_alias).']');

	} else if ($slider == 'royal' && happyrider_exists_royalslider()) {
		$slider_alias = get_new_royalslider($slider_alias);
		if (!empty($slider_alias)) $slider_html = happyrider_do_shortcode('[rev_slider '.esc_attr($slider_alias).']');
		happyrider_enqueue_style(  'new-royalslider-core-css', NEW_ROYALSLIDER_PLUGIN_URL . 'lib/royalslider/royalslider.css', array(), null );
		happyrider_enqueue_script( 'new-royalslider-main-js', NEW_ROYALSLIDER_PLUGIN_URL . 'lib/royalslider/jquery.royalslider.min.js', array('jquery'), NEW_ROYALSLIDER_WP_VERSION, true );

	} else if ($slider == 'swiper') {
		$slider_pagination = happyrider_get_custom_option("slider_pagination");
		$slider_alias = happyrider_get_custom_option("slider_category");
		$slider_orderby = happyrider_get_custom_option("slider_orderby");
		$slider_order = happyrider_get_custom_option("slider_order");
		$slider_count = $slider_ids = happyrider_get_custom_option("slider_posts");

		if (happyrider_strpos($slider_ids, ',')!==false) {
			$slider_alias = '';
			$slider_count = 0;
		} else {
			$slider_ids = '';
			if (empty($slider_count)) $slider_count = 3;
		}

		$slider_interval = happyrider_get_custom_option("slider_interval");

		if ($slider_count > 0 || !empty($slider_ids)) {
			$args = array(
				'custom'	=> "no",
				'crop'		=> "no",
				'controls'	=> "no",
				'engine'	=> $slider,
				'height'	=> max(100, happyrider_get_custom_option('slider_height')),
				'titles'	=> happyrider_get_custom_option("slider_infobox")
			);
			if ($slider_interval)	$args['interval'] = $slider_interval;
			if ($slider_alias)		$args['cat'] = $slider_alias;
			if ($slider_ids)		$args['ids'] = $slider_ids;
			if ($slider_count)		$args['count'] = $slider_count;
			if ($slider_orderby)	$args['orderby'] = $slider_orderby;
			if ($slider_order)		$args['order'] = $slider_order;
			if ($slider_pagination)	$args['pagination'] = $slider_pagination;
			
			$slider_html = happyrider_sc_slider($args);
		}
	}

	// if slider selected
	if (!empty($slider_html)) {
		?>
		<section class="slider_wrap slider_<?php echo esc_attr(happyrider_get_custom_option('slider_display')); ?> slider_engine_<?php echo esc_attr($slider); ?> slider_alias_<?php echo esc_attr($slider_alias); ?>">
			<?php echo ($slider_html); ?>
		</section>
		<?php 
	}
}
?>