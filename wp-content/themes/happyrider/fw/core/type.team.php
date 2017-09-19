<?php
/**
 * HappyRider Framework: Team post type settings
 *
 * @package	happyrider
 * @since	happyrider 1.0
 */

// Theme init
if (!function_exists('happyrider_team_theme_setup')) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_team_theme_setup' );
	function happyrider_team_theme_setup() {

		// Add item in the admin menu
		add_action('admin_menu',							'happyrider_team_add_meta_box');

		// Save data from meta box
		add_action('save_post',								'happyrider_team_save_data');
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('happyrider_filter_get_blog_type',			'happyrider_team_get_blog_type', 9, 2);
		add_filter('happyrider_filter_get_blog_title',		'happyrider_team_get_blog_title', 9, 2);
		add_filter('happyrider_filter_get_current_taxonomy',	'happyrider_team_get_current_taxonomy', 9, 2);
		add_filter('happyrider_filter_is_taxonomy',			'happyrider_team_is_taxonomy', 9, 2);
		add_filter('happyrider_filter_get_stream_page_title',	'happyrider_team_get_stream_page_title', 9, 2);
		add_filter('happyrider_filter_get_stream_page_link',	'happyrider_team_get_stream_page_link', 9, 2);
		add_filter('happyrider_filter_get_stream_page_id',	'happyrider_team_get_stream_page_id', 9, 2);
		add_filter('happyrider_filter_query_add_filters',		'happyrider_team_query_add_filters', 9, 2);
		add_filter('happyrider_filter_detect_inheritance_key','happyrider_team_detect_inheritance_key', 9, 1);

		// Extra column for team members lists
		if (happyrider_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-team_columns',			'happyrider_post_add_options_column', 9);
			add_filter('manage_team_posts_custom_column',	'happyrider_post_fill_options_column', 9, 2);
		}

		// Add shortcodes [trx_team] and [trx_team_item]
		add_action('happyrider_action_shortcodes_list',		'happyrider_team_reg_shortcodes');
		add_action('happyrider_action_shortcodes_list_vc',	'happyrider_team_reg_shortcodes_vc');

		// Meta box fields
		global $HAPPYRIDER_GLOBALS;
		$HAPPYRIDER_GLOBALS['team_meta_box'] = array(
			'id' => 'team-meta-box',
			'title' => __('Team Member Details', 'happyrider'),
			'page' => 'team',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"team_member_position" => array(
					"title" => __('Position',  'happyrider'),
					"desc" => __("Position of the team member", 'happyrider'),
					"class" => "team_member_position",
					"std" => "",
					"type" => "text"),
				"team_member_phone" => array(
					"title" => __('Phone',  'happyrider'),
					"desc" => __("Phone of the team member", 'happyrider'),
					"class" => "team_member_phone",
					"std" => "",
					"type" => "text"),
				"team_member_email" => array(
					"title" => __("E-mail",  'happyrider'),
					"desc" => __("E-mail of the team member - need to take Gravatar (if registered)", 'happyrider'),
					"class" => "team_member_email",
					"std" => "",
					"type" => "text"),
				"team_member_link" => array(
					"title" => __('Link to profile',  'happyrider'),
					"desc" => __("URL of the team member profile page (if not this page)", 'happyrider'),
					"class" => "team_member_link",
					"std" => "",
					"type" => "text"),
				"team_member_socials" => array(
					"title" => __("Social links",  'happyrider'),
					"desc" => __("Links to the social profiles of the team member", 'happyrider'),
					"class" => "team_member_email",
					"std" => "",
					"type" => "social")
			)
		);
		
		if (function_exists('happyrider_require_data')) {
			// Prepare type "Team"
			happyrider_require_data( 'post_type', 'team', array(
				'label'               => __( 'Team member', 'happyrider' ),
				'description'         => __( 'Team Description', 'happyrider' ),
				'labels'              => array(
					'name'                => _x( 'Team', 'Post Type General Name', 'happyrider' ),
					'singular_name'       => _x( 'Team member', 'Post Type Singular Name', 'happyrider' ),
					'menu_name'           => __( 'Team', 'happyrider' ),
					'parent_item_colon'   => __( 'Parent Item:', 'happyrider' ),
					'all_items'           => __( 'All Team', 'happyrider' ),
					'view_item'           => __( 'View Item', 'happyrider' ),
					'add_new_item'        => __( 'Add New Team member', 'happyrider' ),
					'add_new'             => __( 'Add New', 'happyrider' ),
					'edit_item'           => __( 'Edit Item', 'happyrider' ),
					'update_item'         => __( 'Update Item', 'happyrider' ),
					'search_items'        => __( 'Search Item', 'happyrider' ),
					'not_found'           => __( 'Not found', 'happyrider' ),
					'not_found_in_trash'  => __( 'Not found in Trash', 'happyrider' ),
				),
				'supports'            => array( 'title', 'excerpt', 'editor', 'author', 'thumbnail', 'comments', 'custom-fields'),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'menu_icon'			  => 'dashicons-admin-users',
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 25,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'query_var'           => true,
				'capability_type'     => 'page',
				'rewrite'             => true
				)
			);
			
			// Prepare taxonomy for team
			happyrider_require_data( 'taxonomy', 'team_group', array(
				'post_type'			=> array( 'team' ),
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => _x( 'Team Group', 'taxonomy general name', 'happyrider' ),
					'singular_name'     => _x( 'Group', 'taxonomy singular name', 'happyrider' ),
					'search_items'      => __( 'Search Groups', 'happyrider' ),
					'all_items'         => __( 'All Groups', 'happyrider' ),
					'parent_item'       => __( 'Parent Group', 'happyrider' ),
					'parent_item_colon' => __( 'Parent Group:', 'happyrider' ),
					'edit_item'         => __( 'Edit Group', 'happyrider' ),
					'update_item'       => __( 'Update Group', 'happyrider' ),
					'add_new_item'      => __( 'Add New Group', 'happyrider' ),
					'new_item_name'     => __( 'New Group Name', 'happyrider' ),
					'menu_name'         => __( 'Team Group', 'happyrider' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'team_group' ),
				)
			);
		}
	}
}

if ( !function_exists( 'happyrider_team_settings_theme_setup2' ) ) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_team_settings_theme_setup2', 3 );
	function happyrider_team_settings_theme_setup2() {
		// Add post type 'team' and taxonomy 'team_group' into theme inheritance list
		happyrider_add_theme_inheritance( array('team' => array(
			'stream_template' => 'blog-team',
			'single_template' => 'single-team',
			'taxonomy' => array('team_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('team'),
			'override' => 'page'
			) )
		);
	}
}


// Add meta box
if (!function_exists('happyrider_team_add_meta_box')) {
	//add_action('admin_menu', 'happyrider_team_add_meta_box');
	function happyrider_team_add_meta_box() {
		global $HAPPYRIDER_GLOBALS;
		$mb = $HAPPYRIDER_GLOBALS['team_meta_box'];
		add_meta_box($mb['id'], $mb['title'], 'happyrider_team_show_meta_box', $mb['page'], $mb['context'], $mb['priority']);
	}
}

// Callback function to show fields in meta box
if (!function_exists('happyrider_team_show_meta_box')) {
	function happyrider_team_show_meta_box() {
		global $post, $HAPPYRIDER_GLOBALS;

		// Use nonce for verification
		$data = get_post_meta($post->ID, 'team_data', true);
		$fields = $HAPPYRIDER_GLOBALS['team_meta_box']['fields'];
		?>
		<input type="hidden" name="meta_box_team_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
		<table class="team_area">
		<?php
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="team_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
					<td>
						<?php
						if ($id == 'team_member_socials') {
							$socials_type = happyrider_get_theme_setting('socials_type');
							$social_list = happyrider_get_theme_option('social_icons');
							if (is_array($social_list) && count($social_list) > 0) {
								foreach ($social_list as $soc) {
									if ($socials_type == 'icons') {
										$parts = explode('-', $soc['icon'], 2);
										$sn = isset($parts[1]) ? $parts[1] : $sn;
									} else {
										$sn = basename($soc['icon']);
										$sn = happyrider_substr($sn, 0, happyrider_strrpos($sn, '.'));
										if (($pos=happyrider_strrpos($sn, '_'))!==false)
											$sn = happyrider_substr($sn, 0, $pos);
									}   
									$link = isset($meta[$sn]) ? $meta[$sn] : '';
									?>
									<label for="<?php echo esc_attr(($id).'_'.($sn)); ?>"><?php echo esc_attr(happyrider_strtoproper($sn)); ?></label><br>
									<input type="text" name="<?php echo esc_attr($id); ?>[<?php echo esc_attr($sn); ?>]" id="<?php echo esc_attr(($id).'_'.($sn)); ?>" value="<?php echo esc_attr($link); ?>" size="30" /><br>
									<?php
								}
							}
						} else {
							?>
							<input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
							<?php
						}
						?>
						<br><small><?php echo esc_attr($field['desc']); ?></small>
					</td>
				</tr>
				<?php
			}
		}
		?>
		</table>
		<?php
	}
}


// Save data from meta box
if (!function_exists('happyrider_team_save_data')) {
	//add_action('save_post', 'happyrider_team_save_data');
	function happyrider_team_save_data($post_id) {
		// verify nonce
		if (!isset($_POST['meta_box_team_nonce']) || !wp_verify_nonce($_POST['meta_box_team_nonce'], basename(__FILE__))) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='team' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		global $HAPPYRIDER_GLOBALS;

		$data = array();

		$fields = $HAPPYRIDER_GLOBALS['team_meta_box']['fields'];

		// Post type specific data handling
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) {
				if (isset($_POST[$id])) {
					if (is_array($_POST[$id]) && count($_POST[$id]) > 0) {
						foreach ($_POST[$id] as $sn=>$link) {
							$_POST[$id][$sn] = stripslashes($link);
						}
						$data[$id] = $_POST[$id];
					} else {
						$data[$id] = stripslashes($_POST[$id]);
					}
				}
			}
		}

		update_post_meta($post_id, 'team_data', $data);
	}
}



// Return true, if current page is team member page
if ( !function_exists( 'happyrider_is_team_page' ) ) {
	function happyrider_is_team_page() {
		return get_query_var('post_type')=='team' || is_tax('team_group') || (is_page() && happyrider_get_template_page_id('blog-team')==get_the_ID());
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'happyrider_team_detect_inheritance_key' ) ) {
	//add_filter('happyrider_filter_detect_inheritance_key',	'happyrider_team_detect_inheritance_key', 9, 1);
	function happyrider_team_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return happyrider_is_team_page() ? 'team' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'happyrider_team_get_blog_type' ) ) {
	//add_filter('happyrider_filter_get_blog_type',	'happyrider_team_get_blog_type', 9, 2);
	function happyrider_team_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('team_group') || is_tax('team_group'))
			$page = 'team_category';
		else if ($query && $query->get('post_type')=='team' || get_query_var('post_type')=='team')
			$page = $query && $query->is_single() || is_single() ? 'team_item' : 'team';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'happyrider_team_get_blog_title' ) ) {
	//add_filter('happyrider_filter_get_blog_title',	'happyrider_team_get_blog_title', 9, 2);
	function happyrider_team_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( happyrider_strpos($page, 'team')!==false ) {
			if ( $page == 'team_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'team_group' ), 'team_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'team_item' ) {
				$title = happyrider_get_post_title();
			} else {
				$title = __('All team', 'happyrider');
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'happyrider_team_get_stream_page_title' ) ) {
	//add_filter('happyrider_filter_get_stream_page_title',	'happyrider_team_get_stream_page_title', 9, 2);
	function happyrider_team_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (happyrider_strpos($page, 'team')!==false) {
			if (($page_id = happyrider_team_get_stream_page_id(0, $page=='team' ? 'blog-team' : $page)) > 0)
				$title = happyrider_get_post_title($page_id);
			else
				$title = __('All team', 'happyrider');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'happyrider_team_get_stream_page_id' ) ) {
	//add_filter('happyrider_filter_get_stream_page_id',	'happyrider_team_get_stream_page_id', 9, 2);
	function happyrider_team_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (happyrider_strpos($page, 'team')!==false) $id = happyrider_get_template_page_id('blog-team');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'happyrider_team_get_stream_page_link' ) ) {
	//add_filter('happyrider_filter_get_stream_page_link',	'happyrider_team_get_stream_page_link', 9, 2);
	function happyrider_team_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (happyrider_strpos($page, 'team')!==false) {
			$id = happyrider_get_template_page_id('blog-team');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'happyrider_team_get_current_taxonomy' ) ) {
	//add_filter('happyrider_filter_get_current_taxonomy',	'happyrider_team_get_current_taxonomy', 9, 2);
	function happyrider_team_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( happyrider_strpos($page, 'team')!==false ) {
			$tax = 'team_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'happyrider_team_is_taxonomy' ) ) {
	//add_filter('happyrider_filter_is_taxonomy',	'happyrider_team_is_taxonomy', 9, 2);
	function happyrider_team_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('team_group')!='' || is_tax('team_group') ? 'team_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'happyrider_team_query_add_filters' ) ) {
	//add_filter('happyrider_filter_query_add_filters',	'happyrider_team_query_add_filters', 9, 2);
	function happyrider_team_query_add_filters($args, $filter) {
		if ($filter == 'team') {
			$args['post_type'] = 'team';
		}
		return $args;
	}
}





// ---------------------------------- [trx_team] ---------------------------------------


if ( !function_exists( 'happyrider_sc_team' ) ) {
	function happyrider_sc_team($atts, $content=null){
		if (happyrider_in_shortcode_blogger()) return '';
		extract(happyrider_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "team-1",
			"slider" => "no",
			"controls" => "no",
			"slides_space" => 0,
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => 3,
			"columns" => 3,
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => __('Learn more', 'happyrider'),
			"link" => '',
			"scheme" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));

		if (empty($id)) $id = "sc_team_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && happyrider_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);

		$ms = happyrider_get_css_position_from_values($top, $right, $bottom, $left);
		$ws = happyrider_get_css_position_from_values('', '', '', '', $width);
		$hs = happyrider_get_css_position_from_values('', '', '', '', '', $height);
		$css .= ($ms) . ($hs) . ($ws);

		$count = max(1, (int) $count);
		$columns = max(1, min(12, (int) $columns));
		if (happyrider_param_is_off($custom) && $count < $columns) $columns = $count;

		global $HAPPYRIDER_GLOBALS;
		$HAPPYRIDER_GLOBALS['sc_team_id'] = $id;
		$HAPPYRIDER_GLOBALS['sc_team_style'] = $style;
		$HAPPYRIDER_GLOBALS['sc_team_columns'] = $columns;
		$HAPPYRIDER_GLOBALS['sc_team_counter'] = 0;
		$HAPPYRIDER_GLOBALS['sc_team_slider'] = $slider;
		$HAPPYRIDER_GLOBALS['sc_team_css_wh'] = $ws . $hs;

		if (happyrider_param_is_on($slider)) happyrider_enqueue_slider('swiper');
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '') 
						. ' class="sc_team_wrap'
						. ($scheme && !happyrider_param_is_off($scheme) && !happyrider_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_team sc_team_style_'.esc_attr($style)
							. ' ' . esc_attr(happyrider_get_template_property($style, 'container_classes'))
							. ' ' . esc_attr(happyrider_get_slider_controls_classes($controls))
							. (happyrider_param_is_on($slider)
								? ' sc_slider_swiper swiper-slider-container'
									. (happyrider_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
									. ($hs ? ' sc_slider_height_fixed' : '')
								: '')
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
						.'"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!empty($width) && happyrider_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
						. (!empty($height) && happyrider_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
						. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
						. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
						. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
						. (!happyrider_param_is_off($animation) ? ' data-animation="'.esc_attr(happyrider_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_team_subtitle sc_item_subtitle">' . trim(happyrider_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_team_title sc_item_title">' . trim(happyrider_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_team_descr sc_item_descr">' . trim(happyrider_strmacros($description)) . '</div>' : '')
					. (happyrider_param_is_on($slider)
						? '<div class="slides swiper-wrapper">' 
						: ($columns > 1 // && happyrider_get_template_property($style, 'need_columns')
							? '<div class="sc_columns columns_wrap">' 
							: '')
						);
	
		$content = do_shortcode($content);
	
		if (happyrider_param_is_on($custom) && $content) {
			$output .= $content;
		} else {
			global $post;
	
			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}
			
			$args = array(
				'post_type' => 'team',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = happyrider_query_add_sort_order($args, $orderby, $order);
			$args = happyrider_query_add_posts_and_cats($args, $ids, 'team', $cat, 'team_group');
			$query = new WP_Query( $args );
	
			$post_number = 0;
				
			while ( $query->have_posts() ) { 
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => happyrider_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					"columns_count" => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$post_data = happyrider_get_post_data($args);
				$post_meta = get_post_meta($post_data['post_id'], 'team_data', true);
				$thumb_sizes = happyrider_get_thumb_sizes(array('layout' => $style));
				$args['position'] = $post_meta['team_member_position'];
				$args['phone'] = $post_meta['team_member_phone'];
				$args['link'] = !empty($post_meta['team_member_link']) ? $post_meta['team_member_link'] : $post_data['post_link'];
				$args['email'] = $post_meta['team_member_email'];
				$args['photo'] = $post_data['post_thumb'];
				if (empty($args['photo']) && !empty($args['email'])) $args['photo'] = get_avatar($args['email'], $thumb_sizes['w']*min(2, max(1, happyrider_get_theme_option("retina_ready"))));
				$args['socials'] = '';
				$soc_list = $post_meta['team_member_socials'];
				if (is_array($soc_list) && count($soc_list)>0) {
					$soc_str = '';
					foreach ($soc_list as $sn=>$sl) {
						if (!empty($sl))
							$soc_str .= (!empty($soc_str) ? '|' : '') . ($sn) . '=' . ($sl);
					}
					if (!empty($soc_str))
						$args['socials'] = happyrider_do_shortcode('[trx_socials size="tiny" shape="round" socials="'.esc_attr($soc_str).'"][/trx_socials]');
				}
	
				$output .= happyrider_show_post_layout($args, $post_data);
			}
			wp_reset_postdata();
		}

		if (happyrider_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>';
		} else if ($columns > 1) {// && happyrider_get_template_property($style, 'need_columns')) {
			$output .= '</div>';
		}

		$output .= (!empty($link) ? '<div class="sc_team_button sc_item_button">'.happyrider_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. '</div><!-- /.sc_team -->'
				. '</div><!-- /.sc_team_wrap -->';
	
		return apply_filters('happyrider_shortcode_output', $output, 'trx_team', $atts, $content);
	}
	if (function_exists('happyrider_require_shortcode')) happyrider_require_shortcode('trx_team', 'happyrider_sc_team');
}


if ( !function_exists( 'happyrider_sc_team_item' ) ) {
	function happyrider_sc_team_item($atts, $content=null) {
		if (happyrider_in_shortcode_blogger()) return '';
		extract(happyrider_html_decode(shortcode_atts( array(
			// Individual params
			"user" => "",
			"member" => "",
			"name" => "",
			"position" => "",
			"phone" => "",
			"photo" => "",
			"email" => "",
			"link" => "",
			"socials" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));
	
		global $HAPPYRIDER_GLOBALS;
		$HAPPYRIDER_GLOBALS['sc_team_counter']++;
	
		$id = $id ? $id : ($HAPPYRIDER_GLOBALS['sc_team_id'] ? $HAPPYRIDER_GLOBALS['sc_team_id'] . '_' . $HAPPYRIDER_GLOBALS['sc_team_counter'] : '');
	
		$descr = trim(chop(do_shortcode($content)));
	
		$thumb_sizes = happyrider_get_thumb_sizes(array('layout' => $HAPPYRIDER_GLOBALS['sc_team_style']));
	
		if (!empty($socials)) $socials = happyrider_do_shortcode('[trx_socials size="tiny" shape="round" socials="'.esc_attr($socials).'"][/trx_socials]');
	
		if (!empty($user) && $user!='none' && ($user_obj = get_user_by('login', $user)) != false) {
			$meta = get_user_meta($user_obj->ID);
			if (empty($email))		$email = $user_obj->data->user_email;
			if (empty($name))		$name = $user_obj->data->display_name;
			if (empty($position))	$position = isset($meta['user_position'][0]) ? $meta['user_position'][0] : '';
			if (empty($descr))		$descr = isset($meta['description'][0]) ? $meta['description'][0] : '';
			if (empty($socials))	$socials = happyrider_show_user_socials(array('author_id'=>$user_obj->ID, 'echo'=>false));
		}
	
		if (!empty($member) && $member!='none' && ($member_obj = (intval($member) > 0 ? get_post($member, OBJECT) : get_page_by_title($member, OBJECT, 'team'))) != null) {
			if (empty($name))		$name = $member_obj->post_title;
			if (empty($descr))		$descr = $member_obj->post_excerpt;
			$post_meta = get_post_meta($member_obj->ID, 'team_data', true);
			if (empty($position))	$position = $post_meta['team_member_position'];
			if (empty($link))		$link = !empty($post_meta['team_member_link']) ? $post_meta['team_member_link'] : get_permalink($member_obj->ID);
			if (empty($email))		$email = $post_meta['team_member_email'];
			if (empty($photo)) 		$photo = wp_get_attachment_url(get_post_thumbnail_id($member_obj->ID));
			if (empty($socials)) {
				$socials = '';
				$soc_list = $post_meta['team_member_socials'];
				if (is_array($soc_list) && count($soc_list)>0) {
					$soc_str = '';
					foreach ($soc_list as $sn=>$sl) {
						if (!empty($sl))
							$soc_str .= (!empty($soc_str) ? '|' : '') . ($sn) . '=' . ($sl);
					}
					if (!empty($soc_str))
						$socials = happyrider_do_shortcode('[trx_socials size="tiny" shape="round" socials="'.esc_attr($soc_str).'"][/trx_socials]');
				}
			}
		}
		if (empty($photo)) {
			if (!empty($email)) $photo = get_avatar($email, $thumb_sizes['w']*min(2, max(1, happyrider_get_theme_option("retina_ready"))));
		} else {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = happyrider_get_resized_image_tag($photo, $thumb_sizes['w'], $thumb_sizes['h']);
		}
		$post_data = array(
			'post_title' => $name,
			'post_excerpt' => $descr
		);
		$args = array(
			'layout' => $HAPPYRIDER_GLOBALS['sc_team_style'],
			'number' => $HAPPYRIDER_GLOBALS['sc_team_counter'],
			'columns_count' => $HAPPYRIDER_GLOBALS['sc_team_columns'],
			'slider' => $HAPPYRIDER_GLOBALS['sc_team_slider'],
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => $HAPPYRIDER_GLOBALS['sc_team_css_wh'],
			'position' => $position,
			'phone' =>'',
			'link' => $link,
			'email' => $email,
			'photo' => $photo,
			'socials' => $socials
		);
		$output = happyrider_show_post_layout($args, $post_data);

		return apply_filters('happyrider_shortcode_output', $output, 'trx_team_item', $atts, $content);
	}
	if (function_exists('happyrider_require_shortcode')) happyrider_require_shortcode('trx_team_item', 'happyrider_sc_team_item');
}
// ---------------------------------- [/trx_team] ---------------------------------------



// Add [trx_team] and [trx_team_item] in the shortcodes list
if (!function_exists('happyrider_team_reg_shortcodes')) {
	//add_filter('happyrider_action_shortcodes_list',	'happyrider_team_reg_shortcodes');
	function happyrider_team_reg_shortcodes() {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['shortcodes'])) {

			$users = happyrider_get_list_users();
			$members = happyrider_get_list_posts(false, array(
				'post_type'=>'team',
				'orderby'=>'title',
				'order'=>'asc',
				'return'=>'title'
				)
			);
			$team_groups = happyrider_get_list_terms(false, 'team_group');
			$team_styles = happyrider_get_list_templates('team');
			$controls	 = happyrider_get_list_slider_controls();

			happyrider_array_insert_after($HAPPYRIDER_GLOBALS['shortcodes'], 'trx_tabs', array(

				// Team
				"trx_team" => array(
					"title" => __("Team", "happyrider"),
					"desc" => __("Insert team in your page (post)", "happyrider"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => __("Title", "happyrider"),
							"desc" => __("Title for the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => __("Subtitle", "happyrider"),
							"desc" => __("Subtitle for the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => __("Description", "happyrider"),
							"desc" => __("Short description for the block", "happyrider"),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => __("Team style", "happyrider"),
							"desc" => __("Select style to display team members", "happyrider"),
							"value" => "1",
							"type" => "select",
							"options" => $team_styles
						),
						"columns" => array(
							"title" => __("Columns", "happyrider"),
							"desc" => __("How many columns use to show team members", "happyrider"),
							"value" => 3,
							"min" => 2,
							"max" => 5,
							"step" => 1,
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => __("Color scheme", "happyrider"),
							"desc" => __("Select color scheme for this block", "happyrider"),
							"value" => "",
							"type" => "checklist",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['schemes']
						),
						"slider" => array(
							"title" => __("Slider", "happyrider"),
							"desc" => __("Use slider to show team members", "happyrider"),
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"controls" => array(
							"title" => __("Controls", "happyrider"),
							"desc" => __("Slider controls style and position", "happyrider"),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => __("Space between slides", "happyrider"),
							"desc" => __("Size of space (in px) between slides", "happyrider"),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => __("Slides change interval", "happyrider"),
							"desc" => __("Slides change interval (in milliseconds: 1000ms = 1s)", "happyrider"),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => __("Autoheight", "happyrider"),
							"desc" => __("Change whole slider's height (make it equal current slide's height)", "happyrider"),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => __("Alignment", "happyrider"),
							"desc" => __("Alignment of the team block", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						),
						"custom" => array(
							"title" => __("Custom", "happyrider"),
							"desc" => __("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", "happyrider"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => __("Categories", "happyrider"),
							"desc" => __("Select categories (groups) to show team members. If empty - select team members from any category (group) or from IDs list", "happyrider"),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => happyrider_array_merge(array(0 => __('- Select category -', 'happyrider')), $team_groups)
						),
						"count" => array(
							"title" => __("Number of posts", "happyrider"),
							"desc" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "happyrider"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => __("Offset before select posts", "happyrider"),
							"desc" => __("Skip posts before select next part.", "happyrider"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Post order by", "happyrider"),
							"desc" => __("Select desired posts sorting method", "happyrider"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "title",
							"type" => "select",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => __("Post order", "happyrider"),
							"desc" => __("Select desired posts order", "happyrider"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => __("Post IDs list", "happyrider"),
							"desc" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "happyrider"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => __("Button URL", "happyrider"),
							"desc" => __("Link URL for the button at the bottom of the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => __("Button caption", "happyrider"),
							"desc" => __("Caption for the button at the bottom of the block", "happyrider"),
							"value" => "",
							"type" => "text"
						),
						"width" => happyrider_shortcodes_width(),
						"height" => happyrider_shortcodes_height(),
						"top" => $HAPPYRIDER_GLOBALS['sc_params']['top'],
						"bottom" => $HAPPYRIDER_GLOBALS['sc_params']['bottom'],
						"left" => $HAPPYRIDER_GLOBALS['sc_params']['left'],
						"right" => $HAPPYRIDER_GLOBALS['sc_params']['right'],
						"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
						"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
						"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
						"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_team_item",
						"title" => __("Member", "happyrider"),
						"desc" => __("Team member", "happyrider"),
						"container" => true,
						"params" => array(
							"user" => array(
								"title" => __("Registerd user", "happyrider"),
								"desc" => __("Select one of registered users (if present) or put name, position, etc. in fields below", "happyrider"),
								"value" => "",
								"type" => "select",
								"options" => $users
							),
							"member" => array(
								"title" => __("Team member", "happyrider"),
								"desc" => __("Select one of team members (if present) or put name, position, etc. in fields below", "happyrider"),
								"value" => "",
								"type" => "select",
								"options" => $members
							),
							"link" => array(
								"title" => __("Link", "happyrider"),
								"desc" => __("Link on team member's personal page", "happyrider"),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"name" => array(
								"title" => __("Name", "happyrider"),
								"desc" => __("Team member's name", "happyrider"),
								"divider" => true,
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"position" => array(
								"title" => __("Position", "happyrider"),
								"desc" => __("Team member's position", "happyrider"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"phone" => array(
								"title" => __("Phone", "happyrider"),
								"desc" => __("Team member's phone", "happyrider"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => __("E-mail", "happyrider"),
								"desc" => __("Team member's e-mail", "happyrider"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => __("Photo", "happyrider"),
								"desc" => __("Team member's photo (avatar)", "happyrider"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"socials" => array(
								"title" => __("Socials", "happyrider"),
								"desc" => __("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", "happyrider"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => __("Description", "happyrider"),
								"desc" => __("Team member's short description", "happyrider"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
							"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
							"animation" => $HAPPYRIDER_GLOBALS['sc_params']['animation'],
							"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
						)
					)
				)

			));
		}
	}
}


// Add [trx_team] and [trx_team_item] in the VC shortcodes list
if (!function_exists('happyrider_team_reg_shortcodes_vc')) {
	//add_filter('happyrider_action_shortcodes_list_vc',	'happyrider_team_reg_shortcodes_vc');
	function happyrider_team_reg_shortcodes_vc() {
		global $HAPPYRIDER_GLOBALS;

		$users = happyrider_get_list_users();
		$members = happyrider_get_list_posts(false, array(
			'post_type'=>'team',
			'orderby'=>'title',
			'order'=>'asc',
			'return'=>'title'
			)
		);
		$team_groups = happyrider_get_list_terms(false, 'team_group');
		$team_styles = happyrider_get_list_templates('team');
		$controls	 = happyrider_get_list_slider_controls();

		// Team
		vc_map( array(
				"base" => "trx_team",
				"name" => __("Team", "happyrider"),
				"description" => __("Insert team members", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_team',
				"class" => "trx_sc_columns trx_sc_team",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_team_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Team style", "happyrider"),
						"description" => __("Select style to display team members", "happyrider"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($team_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns", "happyrider"),
						"description" => __("How many columns use to show team members", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "scheme",
						"heading" => __("Color scheme", "happyrider"),
						"description" => __("Select color scheme for this block", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => __("Slider", "happyrider"),
						"description" => __("Use slider to show team members", "happyrider"),
						"admin_label" => true,
						"group" => __('Slider', 'happyrider'),
						"class" => "",
						"std" => "no",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls",
						"heading" => __("Controls", "happyrider"),
						"description" => __("Slider controls style and position", "happyrider"),
						"admin_label" => true,
						"group" => __('Slider', 'happyrider'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"std" => "no",
						"value" => array_flip($controls),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slides_space",
						"heading" => __("Space between slides", "happyrider"),
						"description" => __("Size of space (in px) between slides", "happyrider"),
						"admin_label" => true,
						"group" => __('Slider', 'happyrider'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => __("Slides change interval", "happyrider"),
						"description" => __("Slides change interval (in milliseconds: 1000ms = 1s)", "happyrider"),
						"group" => __('Slider', 'happyrider'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "autoheight",
						"heading" => __("Autoheight", "happyrider"),
						"description" => __("Change whole slider's height (make it equal current slide's height)", "happyrider"),
						"group" => __('Slider', 'happyrider'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "happyrider"),
						"description" => __("Alignment of the team block", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom", "happyrider"),
						"description" => __("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", "happyrider"),
						"class" => "",
						"value" => array("Custom members" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "happyrider"),
						"description" => __("Title for the block", "happyrider"),
						"admin_label" => true,
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => __("Subtitle", "happyrider"),
						"description" => __("Subtitle for the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => __("Description", "happyrider"),
						"description" => __("Description for the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "cat",
						"heading" => __("Categories", "happyrider"),
						"description" => __("Select category to show team members. If empty - select team members from any category (group) or from IDs list", "happyrider"),
						"group" => __('Query', 'happyrider'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(happyrider_array_merge(array(0 => __('- Select category -', 'happyrider')), $team_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => __("Number of posts", "happyrider"),
						"description" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "happyrider"),
						"group" => __('Query', 'happyrider'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => __("Offset before select posts", "happyrider"),
						"description" => __("Skip posts before select next part.", "happyrider"),
						"group" => __('Query', 'happyrider'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => __("Post sorting", "happyrider"),
						"description" => __("Select desired posts sorting method", "happyrider"),
						"group" => __('Query', 'happyrider'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => __("Post order", "happyrider"),
						"description" => __("Select desired posts order", "happyrider"),
						"group" => __('Query', 'happyrider'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => __("Team member's IDs list", "happyrider"),
						"description" => __("Comma separated list of team members's ID. If set - parameters above (category, count, order, etc.)  are ignored!", "happyrider"),
						"group" => __('Query', 'happyrider'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => __("Button URL", "happyrider"),
						"description" => __("Link URL for the button at the bottom of the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => __("Button caption", "happyrider"),
						"description" => __("Caption for the button at the bottom of the block", "happyrider"),
						"group" => __('Captions', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					happyrider_vc_width(),
					happyrider_vc_height(),
					$HAPPYRIDER_GLOBALS['vc_params']['margin_top'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_bottom'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_left'],
					$HAPPYRIDER_GLOBALS['vc_params']['margin_right'],
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_team_item user="' . __( 'Member 1', 'happyrider' ) . '"][/trx_team_item]
					[trx_team_item user="' . __( 'Member 2', 'happyrider' ) . '"][/trx_team_item]
					[trx_team_item user="' . __( 'Member 4', 'happyrider' ) . '"][/trx_team_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
		vc_map( array(
				"base" => "trx_team_item",
				"name" => __("Team member", "happyrider"),
				"description" => __("Team member - all data pull out from it account on your site", "happyrider"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_team_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_team_item',
				"as_child" => array('only' => 'trx_team'),
				"as_parent" => array('except' => 'trx_team'),
				"params" => array(
					array(
						"param_name" => "user",
						"heading" => __("Registered user", "happyrider"),
						"description" => __("Select one of registered users (if present) or put name, position, etc. in fields below", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($users),
						"type" => "dropdown"
					),
					array(
						"param_name" => "member",
						"heading" => __("Team member", "happyrider"),
						"description" => __("Select one of team members (if present) or put name, position, etc. in fields below", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($members),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link", "happyrider"),
						"description" => __("Link on team member's personal page", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "name",
						"heading" => __("Name", "happyrider"),
						"description" => __("Team member's name", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "position",
						"heading" => __("Position", "happyrider"),
						"description" => __("Team member's position", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "phone",
						"heading" => __("Phone", "happyrider"),
						"description" => __("Team member's phone", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => __("E-mail", "happyrider"),
						"description" => __("Team member's e-mail", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => __("Member's Photo", "happyrider"),
						"description" => __("Team member's photo (avatar)", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "socials",
						"heading" => __("Socials", "happyrider"),
						"description" => __("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['animation'],
					$HAPPYRIDER_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
		class WPBakeryShortCode_Trx_Team extends HAPPYRIDER_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Team_Item extends HAPPYRIDER_VC_ShortCodeCollection {}

	}
}
?>