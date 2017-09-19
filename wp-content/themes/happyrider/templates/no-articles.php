<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'happyrider_template_no_articles_theme_setup' ) ) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_template_no_articles_theme_setup', 1 );
	function happyrider_template_no_articles_theme_setup() {
		happyrider_add_template(array(
			'layout' => 'no-articles',
			'mode'   => 'internal',
			'title'  => __('No articles found', 'happyrider'),
			'w'		 => null,
			'h'		 => null
		));
	}
}

// Template output
if ( !function_exists( 'happyrider_template_no_articles_output' ) ) {
	function happyrider_template_no_articles_output($post_options, $post_data) {
		?>
		<article class="post_item">
			<div class="post_content">
				<h2 class="post_title"><?php _e('No posts found', 'happyrider'); ?></h2>
				<p><?php _e( 'Sorry, but nothing matched your search criteria.', 'happyrider' ); ?></p>
				<p><?php echo sprintf(__('Go back, or return to <a href="%s">%s</a> home page to choose a new page.', 'happyrider'), esc_url(home_url('/')), get_bloginfo()); ?>
				<br><?php _e('Please report any broken links to our team.', 'happyrider'); ?></p>
				<?php echo happyrider_sc_search(array('state'=>"fixed")); ?>
			</div>	<!-- /.post_content -->
		</article>	<!-- /.post_item -->
		<?php
	}
}
?>