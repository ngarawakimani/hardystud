<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'happyrider_template_related_theme_setup' ) ) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_template_related_theme_setup', 1 );
	function happyrider_template_related_theme_setup() {
		happyrider_add_template(array(
			'layout' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'need_terms' => true,
			'title'  => __('Related posts /no columns/', 'happyrider'),
			'thumb_title'  => __('Medium square image (crop)', 'happyrider'),
			'w'		 => 390,
			'h'		 => 390
		));
		happyrider_add_template(array(
			'layout' => 'related_2',
			'template' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'need_terms' => true,
			'title'  => __('Related posts /2 columns/', 'happyrider'),
			'thumb_title'  => __('Large square image (crop)', 'happyrider'),
			'w'		 => 870,
			'h'		 => 870
		));
		happyrider_add_template(array(
			'layout' => 'related_3',
			'template' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'need_terms' => true,
			'title'  => __('Related posts /3 columns/', 'happyrider'),
			'thumb_title'  => __('Medium square image (crop)', 'happyrider'),
			'w'		 => 390,
			'h'		 => 390
		));
		happyrider_add_template(array(
			'layout' => 'related_4',
			'template' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'need_terms' => true,
			'title'  => __('Related posts /4 columns/', 'happyrider'),
			'thumb_title'  => __('Small square image (crop)', 'happyrider'),
			'w'		 => 300,
			'h'		 => 300
		));
	}
}

// Template output
if ( !function_exists( 'happyrider_template_related_output' ) ) {
	function happyrider_template_related_output($post_options, $post_data) {
		$show_title = true;	//!in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($post_options['columns_count']) 
									? (empty($parts[1]) ? 1 : (int) $parts[1])
									: $post_options['columns_count']
									));
		$tag = happyrider_in_shortcode_blogger(true) ? 'div' : 'article';
		//require(happyrider_get_file_dir('templates/_parts/reviews-summary.php'));
		if ($columns > 1) {
			?><div class="<?php echo 'column-1_'.esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
		<<?php echo ($tag); ?> class="post_item post_item_<?php echo esc_attr($style); ?> post_item_<?php echo esc_attr($post_options['number']); ?>">

			<div class="post_content">
				<?php if ($post_data['post_video'] || $post_data['post_thumb'] || $post_data['post_gallery']) { ?>
				<div class="post_featured">
					<?php require(happyrider_get_file_dir('templates/_parts/post-featured.php')); ?>
				</div>
				<?php } ?>

				<?php if ($show_title) { ?>
					<div class="post_content_wrap">
						<?php
						if (!isset($post_options['links']) || $post_options['links']) { 
							?><h4 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo ($post_data['post_title']); ?></a></h4><?php
						} else {
							?><h4 class="post_title"><?php echo ($post_data['post_title']); ?></h4><?php
						}
						//echo ($reviews_summary);
						if (!empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
							?><div class="post_info post_info_tags"><?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></div><?php
						}
						?>
					</div>
				<?php } ?>
			</div>	<!-- /.post_content -->
		</<?php echo ($tag); ?>>	<!-- /.post_item -->
		<?php
		if ($columns > 1) {
			?></div><?php
		}
	}
}
?>