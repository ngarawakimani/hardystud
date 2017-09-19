<?php
// Reviews block
$reviews_markup = '';
if ($avg_author > 0 || $avg_users > 0) {
	$reviews_first_author = happyrider_get_theme_option('reviews_first')=='author';
	$reviews_second_hide = happyrider_get_theme_option('reviews_second')=='hide';
	$use_tabs = !$reviews_second_hide; // && $avg_author > 0 && $avg_users > 0;
	if ($use_tabs) happyrider_enqueue_script('jquery-ui-tabs', false, array('jquery','jquery-ui-core'), null, true);
	$max_level = max(5, (int) happyrider_get_custom_option('reviews_max_level'));
	$allow_user_marks = (!$reviews_first_author || !$reviews_second_hide) && (!isset($_COOKIE['happyrider_votes']) || happyrider_strpos($_COOKIE['happyrider_votes'], ','.($post_data['post_id']).',')===false) && (happyrider_get_theme_option('reviews_can_vote')=='all' || is_user_logged_in());
	$reviews_markup = '<div class="reviews_block'.($use_tabs ? ' sc_tabs sc_tabs_style_2' : '').'">';
	$output = $marks = $users = '';
	if ($use_tabs) {
		$author_tab = '<li class="sc_tabs_title"><a href="#author_marks" class="theme_button">'.__('Author', 'happyrider').'</a></li>';
		$users_tab = '<li class="sc_tabs_title"><a href="#users_marks" class="theme_button">'.__('Users', 'happyrider').'</a></li>';
		$output .= '<ul class="sc_tabs_titles">' . ($reviews_first_author ? ($author_tab) . ($users_tab) : ($users_tab) . ($author_tab)) . '</ul>';
	}
	// Criterias list
	$field = array(
		"options" => happyrider_get_theme_option('reviews_criterias')
	);
	if (!empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms) && is_array($post_data['post_terms'][$post_data['post_taxonomy']]->terms)) {
		foreach ($post_data['post_terms'][$post_data['post_taxonomy']]->terms as $cat) {
			$id = (int) $cat->term_id;
			$prop = happyrider_taxonomy_get_inherited_property($post_data['post_taxonomy'], $id, 'reviews_criterias');
			if (!empty($prop) && !happyrider_is_inherit_option($prop)) {
				$field['options'] = $prop;
				break;
			}
		}
	}
	// Author marks
	if ($reviews_first_author || !$reviews_second_hide) {
		$field["id"] = "reviews_marks_author";
		$field["descr"] = strip_tags($post_data['post_excerpt']);
		$field["accept"] = false;
		$marks = happyrider_reviews_marks_to_display(happyrider_reviews_marks_prepare(happyrider_get_custom_option('reviews_marks'), count($field['options'])));
		$output .= '<div id="author_marks" class="sc_tabs_content">' . trim(happyrider_reviews_get_markup($field, $marks, false, false, $reviews_first_author)) . '</div>';
	}
	// Users marks
	if (!$reviews_first_author || !$reviews_second_hide) {
		$marks = happyrider_reviews_marks_to_display(happyrider_reviews_marks_prepare(get_post_meta($post_data['post_id'], 'reviews_marks2', true), count($field['options'])));
		$users = max(0, get_post_meta($post_data['post_id'], 'reviews_users', true));
		$field["id"] = "reviews_marks_users";
		$field["descr"] = sprintf(__("Summary rating from <b>%s</b> user's marks.", 'happyrider'), $users) . ' '
			.(!isset($_COOKIE['happyrider_votes']) || happyrider_strpos($_COOKIE['happyrider_votes'], ','.($post_data['post_id']).',')===false
				? __('You can set own marks for this article - just click on stars above and press "Accept".', 'happyrider')
				: __('Thanks for your vote!', 'happyrider'));
		$field["accept"] = $allow_user_marks;
		$output .= '<div id="users_marks" class="sc_tabs_content"'.(!$output ? ' style="display: block;"' : '') . '>' . trim(happyrider_reviews_get_markup($field, $marks, $allow_user_marks, false, !$reviews_first_author)) . '</div>';
	}
	$reviews_markup .= $output . '</div>';
	if ($allow_user_marks) {
		happyrider_enqueue_script('jquery-ui-draggable', false, array('jquery', 'jquery-ui-core'), null, true);
		$reviews_markup .= '
			<script type="text/javascript">
				jQuery(document).ready(function() {
					HAPPYRIDER_GLOBALS["reviews_allow_user_marks"] = '.($allow_user_marks ? 'true' : 'false').';
					HAPPYRIDER_GLOBALS["reviews_max_level"] = '.($max_level).';
					HAPPYRIDER_GLOBALS["reviews_levels"] = "'.trim(happyrider_get_theme_option('reviews_criterias_levels')).'";
					HAPPYRIDER_GLOBALS["reviews_vote"] = "'.(isset($_COOKIE['happyrider_votes']) ? $_COOKIE['happyrider_votes'] : '').'";
					HAPPYRIDER_GLOBALS["reviews_marks"] = "'.($marks).'".split(",");
					HAPPYRIDER_GLOBALS["reviews_users"] = '.max(0, $users).';
					HAPPYRIDER_GLOBALS["post_id"] = '.($post_data['post_id']).';
				});
			</script>
		';
	}
	global $HAPPYRIDER_GLOBALS;
	$HAPPYRIDER_GLOBALS['reviews_markup'] = $reviews_markup;
}
?>