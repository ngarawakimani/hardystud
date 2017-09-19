<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'happyrider_template_404_theme_setup' ) ) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_template_404_theme_setup', 1 );
	function happyrider_template_404_theme_setup() {
		happyrider_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			),
			'w'		 => null,
			'h'		 => null
			));
	}
}

// Template output
if ( !function_exists( 'happyrider_template_404_output' ) ) {
	function happyrider_template_404_output() {
		$image = happyrider_get_custom_option('image_for_404');
		?>
		<article class="post_item post_item_404">
			<div class="post_content">
				<h1 class="page_title"><?php _e( 'We are sorry! <span>Error 404!</span>', 'happyrider' ); ?></h1>
				<p class="page_description"><?php echo sprintf( __('Can\'t find what you need? Take a moment and do<br>a search below or start from <a href="%s">our homepage</a>.', 'happyrider'), esc_url(home_url('/'))); ?></p>
				<div class="page_search"><?php echo happyrider_sc_search(array('state'=>'fixed', 'class' => 'page-404', 'title'=>__('To search type and hit enter', 'happyrider'))); ?></div>
			</div>
			<div class="page-404-image-wrap">
				<img src="<?php echo esc_url($image);?>" title="404page" alt="404page">
			</div>
		</article>
		<?php
	}
}

?>