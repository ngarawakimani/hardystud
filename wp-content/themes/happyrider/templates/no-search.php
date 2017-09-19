<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'happyrider_template_no_search_theme_setup' ) ) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_template_no_search_theme_setup', 1 );
	function happyrider_template_no_search_theme_setup() {
		happyrider_add_template(array(
			'layout' => 'no-search',
			'mode'   => 'internal',
			'title'  => __('No search results found', 'happyrider'),
			'w'		 => null,
			'h'		 => null
		));
	}
}

// Template output
if ( !function_exists( 'happyrider_template_no_search_output' ) ) {
	function happyrider_template_no_search_output($post_options, $post_data) {
		?>
		<article class="post_item">
			<div class="post_content">
				<h2 class="post_title"><?php echo sprintf(__('Search: %s', 'happyrider'), get_search_query()); ?></h2>
				<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'happyrider' ); ?></p>
				<p><?php echo sprintf(__('Go back, or return to <a href="%s">%s</a> home page to choose a new page.', 'happyrider'), esc_url(home_url('/')), get_bloginfo()); ?>
				<br><?php _e('Please report any broken links to our team.', 'happyrider'); ?></p>
				<?php echo happyrider_sc_search(array('state'=>"fixed")); ?>
			</div>	<!-- /.post_content -->
		</article>	<!-- /.post_item -->
		<?php
	}
}
?>