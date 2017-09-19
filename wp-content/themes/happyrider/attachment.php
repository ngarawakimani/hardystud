<?php
/**
Template Name: Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move happyrider_set_post_views to the javascript - counter will work under cache system
	if (happyrider_get_custom_option('use_ajax_views_counter')=='no') {
		happyrider_set_post_views(get_the_ID());
	}

	happyrider_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !happyrider_param_is_off(happyrider_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>