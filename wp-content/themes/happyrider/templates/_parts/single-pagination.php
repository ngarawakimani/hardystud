<?php
if ($post_data['post_type'] == 'lesson') {
	echo trim(happyrider_get_lessons_links($parent_id, $post_data['post_id'], array(
		'header' => __('Course Content', 'happyrider'),
		'show_prev_next' => true
		)));
} else {
	wp_link_pages( array( 
		'before' => '<nav class="pagination_single"><span class="pager_pages">' . __( 'Pages:', 'happyrider' ) . '</span>',
		'after' => '</nav>',
		'link_before' => '<span class="pager_numbers">',
		'link_after' => '</span>'
		)
	); 
}
?>