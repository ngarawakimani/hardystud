<?php
/**
Template Name: Single post
 */
get_header(); 

global $HAPPYRIDER_GLOBALS;
$single_style = !empty($HAPPYRIDER_GLOBALS['single_style']) ? $HAPPYRIDER_GLOBALS['single_style'] : happyrider_get_custom_option('single_style');

while ( have_posts() ) { the_post();

	// Move happyrider_set_post_views to the javascript - counter will work under cache system
	if (happyrider_get_custom_option('use_ajax_views_counter')=='no') {
		happyrider_set_post_views(get_the_ID());
	}

	happyrider_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !happyrider_param_is_off(happyrider_get_custom_option('show_sidebar_main')),
			'content' => happyrider_get_template_property($single_style, 'need_content'),
			'terms_list' => happyrider_get_template_property($single_style, 'need_terms')
		)
	);

}

get_footer();
?>