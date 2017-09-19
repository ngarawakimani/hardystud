<?php
/**
 * HappyRider Framework: Services post type settings
 *
 * @package	happyrider
 * @since	happyrider 1.0
 */

// Theme init
if (!function_exists('happyrider_services_theme_setup')) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_services_theme_setup' );
	function happyrider_services_theme_setup() {
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('happyrider_filter_get_blog_type',			'happyrider_services_get_blog_type', 9, 2);
		add_filter('happyrider_filter_get_blog_title',		'happyrider_services_get_blog_title', 9, 2);
		add_filter('happyrider_filter_get_current_taxonomy',	'happyrider_services_get_current_taxonomy', 9, 2);
		add_filter('happyrider_filter_is_taxonomy',			'happyrider_services_is_taxonomy', 9, 2);
		add_filter('happyrider_filter_get_stream_page_title',	'happyrider_services_get_stream_page_title', 9, 2);
		add_filter('happyrider_filter_get_stream_page_link',	'happyrider_services_get_stream_page_link', 9, 2);
		add_filter('happyrider_filter_get_stream_page_id',	'happyrider_services_get_stream_page_id', 9, 2);
		add_filter('happyrider_filter_query_add_filters',		'happyrider_services_query_add_filters', 9, 2);
		add_filter('happyrider_filter_detect_inheritance_key','happyrider_services_detect_inheritance_key', 9, 1);

		// Extra column for services lists
		if (happyrider_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-services_columns',			'happyrider_post_add_options_column', 9);
			add_filter('manage_services_posts_custom_column',	'happyrider_post_fill_options_column', 9, 2);
		}

		// Add shortcodes [trx_services] and [trx_services_item]
		add_action('happyrider_action_shortcodes_list',		'happyrider_services_reg_shortcodes');
		add_action('happyrider_action_shortcodes_list_vc',	'happyrider_services_reg_shortcodes_vc');
		
		if (function_exists('happyrider_require_data')) {
			// Prepare type "Team"
			happyrider_require_data( 'post_type', 'services', array(
				'label'               => __( 'Service item', 'happyrider' ),
				'description'         => __( 'Service Description', 'happyrider' ),
				'labels'              => array(
					'name'                => _x( 'Services', 'Post Type General Name', 'happyrider' ),
					'singular_name'       => _x( 'Service item', 'Post Type Singular Name', 'happyrider' ),
					'menu_name'           => __( 'Services', 'happyrider' ),
					'parent_item_colon'   => __( 'Parent Item:', 'happyrider' ),
					'all_items'           => __( 'All Services', 'happyrider' ),
					'view_item'           => __( 'View Item', 'happyrider' ),
					'add_new_item'        => __( 'Add New Service', 'happyrider' ),
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
				'menu_icon'			  => 'dashicons-info',
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 28,
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
			happyrider_require_data( 'taxonomy', 'services_group', array(
				'post_type'			=> array( 'services' ),
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => _x( 'Services Group', 'taxonomy general name', 'happyrider' ),
					'singular_name'     => _x( 'Group', 'taxonomy singular name', 'happyrider' ),
					'search_items'      => __( 'Search Groups', 'happyrider' ),
					'all_items'         => __( 'All Groups', 'happyrider' ),
					'parent_item'       => __( 'Parent Group', 'happyrider' ),
					'parent_item_colon' => __( 'Parent Group:', 'happyrider' ),
					'edit_item'         => __( 'Edit Group', 'happyrider' ),
					'update_item'       => __( 'Update Group', 'happyrider' ),
					'add_new_item'      => __( 'Add New Group', 'happyrider' ),
					'new_item_name'     => __( 'New Group Name', 'happyrider' ),
					'menu_name'         => __( 'Services Group', 'happyrider' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'services_group' ),
				)
			);
		}
	}
}

if ( !function_exists( 'happyrider_services_settings_theme_setup2' ) ) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_services_settings_theme_setup2', 3 );
	function happyrider_services_settings_theme_setup2() {
		// Add post type 'services' and taxonomy 'services_group' into theme inheritance list
		happyrider_add_theme_inheritance( array('services' => array(
			'stream_template' => 'blog-services',
			'single_template' => 'single-services',
			'taxonomy' => array('services_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('services'),
			'override' => 'page'
			) )
		);
	}
}



// Return true, if current page is services page
if ( !function_exists( 'happyrider_is_services_page' ) ) {
	function happyrider_is_services_page() {
		return get_query_var('post_type')=='services' || is_tax('services_group') || (is_page() && happyrider_get_template_page_id('blog-services')==get_the_ID());
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'happyrider_services_detect_inheritance_key' ) ) {
	//add_filter('happyrider_filter_detect_inheritance_key',	'happyrider_services_detect_inheritance_key', 9, 1);
	function happyrider_services_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return happyrider_is_services_page() ? 'services' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'happyrider_services_get_blog_type' ) ) {
	//add_filter('happyrider_filter_get_blog_type',	'happyrider_services_get_blog_type', 9, 2);
	function happyrider_services_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('services_group') || is_tax('services_group'))
			$page = 'services_category';
		else if ($query && $query->get('post_type')=='services' || get_query_var('post_type')=='services')
			$page = $query && $query->is_single() || is_single() ? 'services_item' : 'services';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'happyrider_services_get_blog_title' ) ) {
	//add_filter('happyrider_filter_get_blog_title',	'happyrider_services_get_blog_title', 9, 2);
	function happyrider_services_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( happyrider_strpos($page, 'services')!==false ) {
			if ( $page == 'services_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'services_group' ), 'services_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'services_item' ) {
				$title = happyrider_get_post_title();
			} else {
				$title = __('All services', 'happyrider');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'happyrider_services_get_stream_page_title' ) ) {
	//add_filter('happyrider_filter_get_stream_page_title',	'happyrider_services_get_stream_page_title', 9, 2);
	function happyrider_services_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (happyrider_strpos($page, 'services')!==false) {
			if (($page_id = happyrider_services_get_stream_page_id(0, $page=='services' ? 'blog-services' : $page)) > 0)
				$title = happyrider_get_post_title($page_id);
			else
				$title = __('All services', 'happyrider');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'happyrider_services_get_stream_page_id' ) ) {
	//add_filter('happyrider_filter_get_stream_page_id',	'happyrider_services_get_stream_page_id', 9, 2);
	function happyrider_services_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (happyrider_strpos($page, 'services')!==false) $id = happyrider_get_template_page_id('blog-services');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'happyrider_services_get_stream_page_link' ) ) {
	//add_filter('happyrider_filter_get_stream_page_link',	'happyrider_services_get_stream_page_link', 9, 2);
	function happyrider_services_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (happyrider_strpos($page, 'services')!==false) {
			$id = happyrider_get_template_page_id('blog-services');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'happyrider_services_get_current_taxonomy' ) ) {
	//add_filter('happyrider_filter_get_current_taxonomy',	'happyrider_services_get_current_taxonomy', 9, 2);
	function happyrider_services_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( happyrider_strpos($page, 'services')!==false ) {
			$tax = 'services_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'happyrider_services_is_taxonomy' ) ) {
	//add_filter('happyrider_filter_is_taxonomy',	'happyrider_services_is_taxonomy', 9, 2);
	function happyrider_services_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('services_group')!='' || is_tax('services_group') ? 'services_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'happyrider_services_query_add_filters' ) ) {
	//add_filter('happyrider_filter_query_add_filters',	'happyrider_services_query_add_filters', 9, 2);
	function happyrider_services_query_add_filters($args, $filter) {
		if ($filter == 'services') {
			$args['post_type'] = 'services';
		}
		return $args;
	}
}





// ---------------------------------- [trx_services] ---------------------------------------

/*
[trx_services id="unique_id" columns="4" count="4" style="services-1|services-2|..." title="Block title" subtitle="xxx" description="xxxxxx"]
	[trx_services_item icon="url" title="Item title" description="Item description" link="url" link_caption="Link text"]
	[trx_services_item icon="url" title="Item title" description="Item description" link="url" link_caption="Link text"]
[/trx_services]
*/
if ( !function_exists( 'happyrider_sc_services' ) ) {
	function happyrider_sc_services($atts, $content=null){
		if (happyrider_in_shortcode_blogger()) return '';
		extract(happyrider_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "services-1",
			"columns" => 3,
			"slider" => "no",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"type" => "icons",	// icons | images
			"ids" => "",
			"cat" => "",
			"count" => 3,
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"readmore" => __('more', 'happyrider'),
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => __('more', 'happyrider'),
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
	
		if (empty($id)) $id = "sc_services_".str_replace('.', '', mt_rand());
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
		$HAPPYRIDER_GLOBALS['sc_services_id'] = $id;
		$HAPPYRIDER_GLOBALS['sc_services_style'] = $style;
		$HAPPYRIDER_GLOBALS['sc_services_columns'] = $columns;
		$HAPPYRIDER_GLOBALS['sc_services_counter'] = 0;
		$HAPPYRIDER_GLOBALS['sc_services_slider'] = $slider;
		$HAPPYRIDER_GLOBALS['sc_services_css_wh'] = $ws . $hs;
		$HAPPYRIDER_GLOBALS['sc_services_readmore'] = $readmore;
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '') 
						. ' class="sc_services_wrap'
						. ($scheme && !happyrider_param_is_off($scheme) && !happyrider_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_services'
							. ' sc_services_style_'.esc_attr($style)
							. ' sc_services_type_'.esc_attr($type)
							. ' ' . esc_attr(happyrider_get_template_property($style, 'container_classes'))
							. ' ' . esc_attr(happyrider_get_slider_controls_classes($controls))
							. (happyrider_param_is_on($slider)
								? ' sc_slider_swiper swiper-slider-container'
									. (happyrider_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
									. ($hs ? ' sc_slider_height_fixed' : '')
								: '')
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!empty($width) && happyrider_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
						. (!empty($height) && happyrider_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
						. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
						. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
						. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
						. (!happyrider_param_is_off($animation) ? ' data-animation="'.esc_attr(happyrider_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_services_subtitle sc_item_subtitle">' . trim(happyrider_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_services_title sc_item_title">' . trim(happyrider_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_services_descr sc_item_descr">' . trim(happyrider_strmacros($description)) . '</div>' : '')
					. (happyrider_param_is_on($slider)
						? '<div class="slides swiper-wrapper">' 
						: ($columns > 1 
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
				'post_type' => 'services',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
				'readmore' => $readmore
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = happyrider_query_add_sort_order($args, $orderby, $order);
			$args = happyrider_query_add_posts_and_cats($args, $ids, 'services', $cat, 'services_group');
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
					'readmore' => $readmore,
					'tag_type' => $type,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$output .= happyrider_show_post_layout($args);
			}
			wp_reset_postdata();
		}
	
		if (happyrider_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>';
		} else if ($columns > 1) {
			$output .= '</div>';
		}

		$output .=  (!empty($link) ? '<div class="sc_services_button sc_item_button">'.happyrider_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. '</div><!-- /.sc_services -->'
				. '</div><!-- /.sc_services_wrap -->';
	
		return apply_filters('happyrider_shortcode_output', $output, 'trx_services', $atts, $content);
	}
	if (function_exists('happyrider_require_shortcode')) happyrider_require_shortcode('trx_services', 'happyrider_sc_services');
}


if ( !function_exists( 'happyrider_sc_services_item' ) ) {
	function happyrider_sc_services_item($atts, $content=null) {
		if (happyrider_in_shortcode_blogger()) return '';
		extract(happyrider_html_decode(shortcode_atts( array(
			// Individual params
			"icon" => "",
			"image" => "",
			"title" => "",
			"link" => "",
			"readmore" => "(none)",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));
	
		global $HAPPYRIDER_GLOBALS;
		$HAPPYRIDER_GLOBALS['sc_services_counter']++;

		$id = $id ? $id : ($HAPPYRIDER_GLOBALS['sc_services_id'] ? $HAPPYRIDER_GLOBALS['sc_services_id'] . '_' . $HAPPYRIDER_GLOBALS['sc_services_counter'] : '');

		$descr = trim(chop(do_shortcode($content)));
		$readmore = $readmore=='(none)' ? $HAPPYRIDER_GLOBALS['sc_services_readmore'] : $readmore;
		$type ='';
		if (!empty($icon)) {
			$type = 'icons';
		} else if (!empty($image)) {
			$type = 'images';
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
			$thumb_sizes = happyrider_get_thumb_sizes(array('layout' => $HAPPYRIDER_GLOBALS['sc_services_style']));
			$image = happyrider_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);
		}
	
		$post_data = array(
			'post_title' => $title,
			'post_excerpt' => $descr,
			'post_thumb' => $image,
			'post_icon' => $icon,
			'post_link' => $link,
			'post_protected' => false,
			'post_format' => 'standard'
		);
		$args = array(
			'layout' => $HAPPYRIDER_GLOBALS['sc_services_style'],
			'number' => $HAPPYRIDER_GLOBALS['sc_services_counter'],
			'columns_count' => $HAPPYRIDER_GLOBALS['sc_services_columns'],
			'slider' => $HAPPYRIDER_GLOBALS['sc_services_slider'],
			'show' => false,
			'descr'  => 0,
			'readmore' => $readmore,
			'tag_type' => $type,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => $HAPPYRIDER_GLOBALS['sc_services_css_wh']
		);
		$output = happyrider_show_post_layout($args, $post_data);
		return apply_filters('happyrider_shortcode_output', $output, 'trx_services_item', $atts, $content);
	}
	if (function_exists('happyrider_require_shortcode')) happyrider_require_shortcode('trx_services_item', 'happyrider_sc_services_item');
}
// ---------------------------------- [/trx_services] ---------------------------------------



// Add [trx_services] and [trx_services_item] in the shortcodes list
if (!function_exists('happyrider_services_reg_shortcodes')) {
	//add_filter('happyrider_action_shortcodes_list',	'happyrider_services_reg_shortcodes');
	function happyrider_services_reg_shortcodes() {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['shortcodes'])) {

			$services_groups = happyrider_get_list_terms(false, 'services_group');
			$services_styles = happyrider_get_list_templates('services');
			$controls 		 = happyrider_get_list_slider_controls();

			happyrider_array_insert_after($HAPPYRIDER_GLOBALS['shortcodes'], 'trx_section', array(

				// Services
				"trx_services" => array(
					"title" => __("Services", "happyrider"),
					"desc" => __("Insert services list in your page (post)", "happyrider"),
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
							"title" => __("Services style", "happyrider"),
							"desc" => __("Select style to display services list", "happyrider"),
							"value" => "services-1",
							"type" => "select",
							"options" => $services_styles
						),
						"type" => array(
							"title" => __("Icon's type", "happyrider"),
							"desc" => __("Select type of icons: font icon or image", "happyrider"),
							"value" => "icons",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'icons'  => __('Icons', 'happyrider'),
								'images' => __('Images', 'happyrider')
							)
						),
						"columns" => array(
							"title" => __("Columns", "happyrider"),
							"desc" => __("How many columns use to show services list", "happyrider"),
							"value" => 3,
							"min" => 2,
							"max" => 6,
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
							"desc" => __("Use slider to show services", "happyrider"),
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
							"desc" => __("Alignment of the services block", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						),
						"custom" => array(
							"title" => __("Custom", "happyrider"),
							"desc" => __("Allow get services items from inner shortcodes (custom) or get it from specified group (cat)", "happyrider"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => __("Categories", "happyrider"),
							"desc" => __("Select categories (groups) to show services list. If empty - select services from any category (group) or from IDs list", "happyrider"),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => happyrider_array_merge(array(0 => __('- Select category -', 'happyrider')), $services_groups)
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
						"readmore" => array(
							"title" => __("Read more", "happyrider"),
							"desc" => __("Caption for the Read more link (if empty - link not showed)", "happyrider"),
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
						"name" => "trx_services_item",
						"title" => __("Service item", "happyrider"),
						"desc" => __("Service item", "happyrider"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => __("Title", "happyrider"),
								"desc" => __("Item's title", "happyrider"),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => __("Item's icon",  'happyrider'),
								"desc" => __('Select icon for the item from Fontello icons set',  'happyrider'),
								"value" => "",
								"type" => "icons",
								"options" => $HAPPYRIDER_GLOBALS['sc_params']['icons']
							),
							"image" => array(
								"title" => __("Item's image", "happyrider"),
								"desc" => __("Item's image (if icon not selected)", "happyrider"),
								"dependency" => array(
									'icon' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"link" => array(
								"title" => __("Link", "happyrider"),
								"desc" => __("Link on service's item page", "happyrider"),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"readmore" => array(
								"title" => __("Read more", "happyrider"),
								"desc" => __("Caption for the Read more link (if empty - link not showed)", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => __("Description", "happyrider"),
								"desc" => __("Item's short description", "happyrider"),
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


// Add [trx_services] and [trx_services_item] in the VC shortcodes list
if (!function_exists('happyrider_services_reg_shortcodes_vc')) {
	//add_filter('happyrider_action_shortcodes_list_vc',	'happyrider_services_reg_shortcodes_vc');
	function happyrider_services_reg_shortcodes_vc() {
		global $HAPPYRIDER_GLOBALS;

		$services_groups = happyrider_get_list_terms(false, 'services_group');
		$services_styles = happyrider_get_list_templates('services');
		$controls		 = happyrider_get_list_slider_controls();

		// Services
		vc_map( array(
				"base" => "trx_services",
				"name" => __("Services", "happyrider"),
				"description" => __("Insert services list", "happyrider"),
				"category" => __('Content', 'js_composer'),
				"icon" => 'icon_trx_services',
				"class" => "trx_sc_columns trx_sc_services",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_services_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Services style", "happyrider"),
						"description" => __("Select style to display services list", "happyrider"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($services_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "type",
						"heading" => __("Icon's type", "happyrider"),
						"description" => __("Select type of icons: font icon or image", "happyrider"),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							__('Icons', 'happyrider') => 'icons',
							__('Images', 'happyrider') => 'images'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns", "happyrider"),
						"description" => __("How many columns use to show services list", "happyrider"),
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
						"description" => __("Use slider to show services", "happyrider"),
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
						"description" => __("Alignment of the services block", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom", "happyrider"),
						"description" => __("Allow get services from inner shortcodes (custom) or get it from specified group (cat)", "happyrider"),
						"class" => "",
						"value" => array("Custom services" => "yes" ),
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
						"description" => __("Select category to show services. If empty - select services from any category (group) or from IDs list", "happyrider"),
						"group" => __('Query', 'happyrider'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(happyrider_array_merge(array(0 => __('- Select category -', 'happyrider')), $services_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => __("Number of posts", "happyrider"),
						"description" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "happyrider"),
						"admin_label" => true,
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
						"param_name" => "readmore",
						"heading" => __("Read more", "happyrider"),
						"description" => __("Caption for the Read more link (if empty - link not showed)", "happyrider"),
						"admin_label" => true,
						"group" => __('Captions', 'happyrider'),
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
					[trx_services_item title="' . __( 'Service item 1', 'happyrider' ) . '"][/trx_services_item]
					[trx_services_item title="' . __( 'Service item 2', 'happyrider' ) . '"][/trx_services_item]
					[trx_services_item title="' . __( 'Service item 3', 'happyrider' ) . '"][/trx_services_item]
					[trx_services_item title="' . __( 'Service item 4', 'happyrider' ) . '"][/trx_services_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
		vc_map( array(
				"base" => "trx_services_item",
				"name" => __("Services item", "happyrider"),
				"description" => __("Custom services item - all data pull out from shortcode parameters", "happyrider"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_services_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_services_item',
				"as_child" => array('only' => 'trx_services'),
				"as_parent" => array('except' => 'trx_services'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Title", "happyrider"),
						"description" => __("Item's title", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Icon", "happyrider"),
						"description" => __("Select icon for the item from Fontello icons set", "happyrider"),
						"class" => "",
						"value" => $HAPPYRIDER_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => __("Image", "happyrider"),
						"description" => __("Item's image (if icon is empty)", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link", "happyrider"),
						"description" => __("Link on item's page", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => __("Read more", "happyrider"),
						"description" => __("Caption for the Read more link (if empty - link not showed)", "happyrider"),
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
			
		class WPBakeryShortCode_Trx_Services extends HAPPYRIDER_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Services_Item extends HAPPYRIDER_VC_ShortCodeCollection {}

	}
}
?>