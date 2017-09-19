<?php
/**
 * HappyRider Framework: Testimonial post type settings
 *
 * @package	happyrider
 * @since	happyrider 1.0
 */

// Theme init
if (!function_exists('happyrider_testimonial_theme_setup')) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_testimonial_theme_setup' );
	function happyrider_testimonial_theme_setup() {
	
		// Add item in the admin menu
		add_action('admin_menu',			'happyrider_testimonial_add_meta_box');

		// Save data from meta box
		add_action('save_post',				'happyrider_testimonial_save_data');

		// Add shortcodes [trx_testimonials] and [trx_testimonials_item]
		add_action('happyrider_action_shortcodes_list',		'happyrider_testimonials_reg_shortcodes');
		add_action('happyrider_action_shortcodes_list_vc',	'happyrider_testimonials_reg_shortcodes_vc');

		// Meta box fields
		global $HAPPYRIDER_GLOBALS;
		$HAPPYRIDER_GLOBALS['testimonial_meta_box'] = array(
			'id' => 'testimonial-meta-box',
			'title' => __('Testimonial Details', 'happyrider'),
			'page' => 'testimonial',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"testimonial_author" => array(
					"title" => __('Testimonial author',  'happyrider'),
					"desc" => __("Name of the testimonial's author", 'happyrider'),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_position" => array(
					"title" => __("Author's position",  'happyrider'),
					"desc" => __("Position of the testimonial's author", 'happyrider'),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_email" => array(
					"title" => __("Author's e-mail",  'happyrider'),
					"desc" => __("E-mail of the testimonial's author - need to take Gravatar (if registered)", 'happyrider'),
					"class" => "testimonial_email",
					"std" => "",
					"type" => "text"),
				"testimonial_link" => array(
					"title" => __('Testimonial link',  'happyrider'),
					"desc" => __("URL of the testimonial source or author profile page", 'happyrider'),
					"class" => "testimonial_link",
					"std" => "",
					"type" => "text")
			)
		);
		
		if (function_exists('happyrider_require_data')) {
			// Prepare type "Testimonial"
			happyrider_require_data( 'post_type', 'testimonial', array(
				'label'               => __( 'Testimonial', 'happyrider' ),
				'description'         => __( 'Testimonial Description', 'happyrider' ),
				'labels'              => array(
					'name'                => _x( 'Testimonials', 'Post Type General Name', 'happyrider' ),
					'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name', 'happyrider' ),
					'menu_name'           => __( 'Testimonials', 'happyrider' ),
					'parent_item_colon'   => __( 'Parent Item:', 'happyrider' ),
					'all_items'           => __( 'All Testimonials', 'happyrider' ),
					'view_item'           => __( 'View Item', 'happyrider' ),
					'add_new_item'        => __( 'Add New Testimonial', 'happyrider' ),
					'add_new'             => __( 'Add New', 'happyrider' ),
					'edit_item'           => __( 'Edit Item', 'happyrider' ),
					'update_item'         => __( 'Update Item', 'happyrider' ),
					'search_items'        => __( 'Search Item', 'happyrider' ),
					'not_found'           => __( 'Not found', 'happyrider' ),
					'not_found_in_trash'  => __( 'Not found in Trash', 'happyrider' ),
				),
				'supports'            => array( 'title', 'editor', 'author', 'thumbnail'),
				'hierarchical'        => false,
				'public'              => false,
				'show_ui'             => true,
				'menu_icon'			  => 'dashicons-cloud',
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 28,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'capability_type'     => 'page',
				)
			);
			
			// Prepare taxonomy for testimonial
			happyrider_require_data( 'taxonomy', 'testimonial_group', array(
				'post_type'			=> array( 'testimonial' ),
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => _x( 'Testimonials Group', 'taxonomy general name', 'happyrider' ),
					'singular_name'     => _x( 'Group', 'taxonomy singular name', 'happyrider' ),
					'search_items'      => __( 'Search Groups', 'happyrider' ),
					'all_items'         => __( 'All Groups', 'happyrider' ),
					'parent_item'       => __( 'Parent Group', 'happyrider' ),
					'parent_item_colon' => __( 'Parent Group:', 'happyrider' ),
					'edit_item'         => __( 'Edit Group', 'happyrider' ),
					'update_item'       => __( 'Update Group', 'happyrider' ),
					'add_new_item'      => __( 'Add New Group', 'happyrider' ),
					'new_item_name'     => __( 'New Group Name', 'happyrider' ),
					'menu_name'         => __( 'Testimonial Group', 'happyrider' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'testimonial_group' ),
				)
			);
		}
	}
}


// Add meta box
if (!function_exists('happyrider_testimonial_add_meta_box')) {

	function happyrider_testimonial_add_meta_box() {
		global $HAPPYRIDER_GLOBALS;
		$mb = $HAPPYRIDER_GLOBALS['testimonial_meta_box'];
		add_meta_box($mb['id'], $mb['title'], 'happyrider_testimonial_show_meta_box', $mb['page'], $mb['context'], $mb['priority']);
	}
}

// Callback function to show fields in meta box
if (!function_exists('happyrider_testimonial_show_meta_box')) {
	function happyrider_testimonial_show_meta_box() {
		global $post, $HAPPYRIDER_GLOBALS;

		// Use nonce for verification
		echo '<input type="hidden" name="meta_box_testimonial_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		
		$data = get_post_meta($post->ID, 'testimonial_data', true);
	
		$fields = $HAPPYRIDER_GLOBALS['testimonial_meta_box']['fields'];
		?>
		<table class="testimonial_area">
		<?php
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="testimonial_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
					<td><input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
						<br><small><?php echo esc_attr($field['desc']); ?></small></td>
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
if (!function_exists('happyrider_testimonial_save_data')) {

	function happyrider_testimonial_save_data($post_id) {
		// verify nonce
		if (!isset($_POST['meta_box_testimonial_nonce']) || !wp_verify_nonce($_POST['meta_box_testimonial_nonce'], basename(__FILE__))) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='testimonial' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		global $HAPPYRIDER_GLOBALS;

		$data = array();

		$fields = $HAPPYRIDER_GLOBALS['testimonial_meta_box']['fields'];

		// Post type specific data handling
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				if (isset($_POST[$id])) 
					$data[$id] = stripslashes($_POST[$id]);
			}
		}

		update_post_meta($post_id, 'testimonial_data', $data);
	}
}






// ---------------------------------- [trx_testimonials] ---------------------------------------


if (!function_exists('happyrider_sc_testimonials')) {
	function happyrider_sc_testimonials($atts, $content=null){
		if (happyrider_in_shortcode_blogger()) return '';
		extract(happyrider_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "testimonials-1",
			"columns" => 1,
			"slider" => "yes",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => "3",
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
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
	
		if (empty($id)) $id = "sc_testimonials_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && happyrider_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = happyrider_get_scheme_color('bg');
			$rgb = happyrider_hex2rgb($bg_color);
		}
		
		$ms = happyrider_get_css_position_from_values($top, $right, $bottom, $left);
		$ws = happyrider_get_css_position_from_values('', '', '', '', $width);
		$hs = happyrider_get_css_position_from_values('', '', '', '', '', $height);
		$css .= ($ms) . ($hs) . ($ws);

		$count = max(1, (int) $count);
		$columns = max(1, min(12, (int) $columns));
		if (happyrider_param_is_off($custom) && $count < $columns) $columns = $count;
		
		global $HAPPYRIDER_GLOBALS;
		$HAPPYRIDER_GLOBALS['sc_testimonials_id'] = $id;
		$HAPPYRIDER_GLOBALS['sc_testimonials_style'] = $style;
		$HAPPYRIDER_GLOBALS['sc_testimonials_columns'] = $columns;
		$HAPPYRIDER_GLOBALS['sc_testimonials_counter'] = 0;
		$HAPPYRIDER_GLOBALS['sc_testimonials_slider'] = $slider;
		$HAPPYRIDER_GLOBALS['sc_testimonials_css_wh'] = $ws . $hs;

		if (happyrider_param_is_on($slider)) happyrider_enqueue_slider('swiper');
	
		$output = ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || happyrider_strlen($bg_texture)>2 || ($scheme && !happyrider_param_is_off($scheme) && !happyrider_param_is_inherit($scheme))
					? '<div class="sc_testimonials_wrap sc_section'
							. ($scheme && !happyrider_param_is_off($scheme) && !happyrider_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
							. '"'
						.' style="'
							. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
							. ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');' : '')
							. '"'
						. (!happyrider_param_is_off($animation) ? ' data-animation="'.esc_attr(happyrider_get_animation_classes($animation)).'"' : '')
						. '>'
						. '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
								. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
									. (happyrider_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
									. '"'
									. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
									. '>' 
					: '')
				. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_testimonials sc_testimonials_style_'.esc_attr($style)
 					. ' ' . esc_attr(happyrider_get_template_property($style, 'container_classes'))
 					. (happyrider_param_is_on($slider)
						? ' sc_slider_swiper swiper-slider-container'
							. ' ' . esc_attr(happyrider_get_slider_controls_classes($controls))
							. (happyrider_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
							. ($hs ? ' sc_slider_height_fixed' : '')
						: '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
					. '"'
				. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && !happyrider_param_is_off($animation) ? ' data-animation="'.esc_attr(happyrider_get_animation_classes($animation)).'"' : '')
				. (!empty($width) && happyrider_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
				. (!empty($height) && happyrider_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
				. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
				. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
				. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
			. (!empty($subtitle) ? '<h6 class="sc_testimonials_subtitle sc_item_subtitle">' . trim(happyrider_strmacros($subtitle)) . '</h6>' : '')
			. (!empty($title) ? '<h2 class="sc_testimonials_title sc_item_title">' . trim(happyrider_strmacros($title)) . '</h2>' : '')
			. (!empty($description) ? '<div class="sc_testimonials_descr sc_item_descr">' . trim(happyrider_strmacros($description)) . '</div>' : '')
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
				'post_type' => 'testimonial',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = happyrider_query_add_sort_order($args, $orderby, $order);
			$args = happyrider_query_add_posts_and_cats($args, $ids, 'testimonial', $cat, 'testimonial_group');
	
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
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$post_data = happyrider_get_post_data($args);
				$post_data['post_content'] = wpautop($post_data['post_content']);	// Add <p> around text and paragraphs. Need separate call because 'content'=>false (see above)
				$post_meta = get_post_meta($post_data['post_id'], 'testimonial_data', true);
				$thumb_sizes = happyrider_get_thumb_sizes(array('layout' => $style));
				$args['author'] = $post_meta['testimonial_author'];
				$args['position'] = $post_meta['testimonial_position'];
				$args['link'] = !empty($post_meta['testimonial_link']) ? $post_meta['testimonial_link'] : '';	//$post_data['post_link'];
				$args['email'] = $post_meta['testimonial_email'];
				$args['photo'] = $post_data['post_thumb'];
				if (empty($args['photo']) && !empty($args['email'])) $args['photo'] = get_avatar($args['email'], $thumb_sizes['w']*min(2, max(1, happyrider_get_theme_option("retina_ready"))));
				$output .= happyrider_show_post_layout($args, $post_data);
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

		$output .= '</div>'
					. ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || happyrider_strlen($bg_texture)>2
						?  '</div></div>'
						: '');

		return apply_filters('happyrider_shortcode_output', $output, 'trx_testimonials', $atts, $content);
	}
	if (function_exists('happyrider_require_shortcode')) happyrider_require_shortcode('trx_testimonials', 'happyrider_sc_testimonials');
}
	
	
if (!function_exists('happyrider_sc_testimonials_item')) {
	function happyrider_sc_testimonials_item($atts, $content=null){
		if (happyrider_in_shortcode_blogger()) return '';
		extract(happyrider_html_decode(shortcode_atts(array(
			// Individual params
			"author" => "",
			"position" => "",
			"link" => "",
			"photo" => "",
			"email" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
		), $atts)));

		global $HAPPYRIDER_GLOBALS;
		$HAPPYRIDER_GLOBALS['sc_testimonials_counter']++;
	
		$id = $id ? $id : ($HAPPYRIDER_GLOBALS['sc_testimonials_id'] ? $HAPPYRIDER_GLOBALS['sc_testimonials_id'] . '_' . $HAPPYRIDER_GLOBALS['sc_testimonials_counter'] : '');
	
		$thumb_sizes = happyrider_get_thumb_sizes(array('layout' => $HAPPYRIDER_GLOBALS['sc_testimonials_style']));

		if (empty($photo)) {
			if (!empty($email))
				$photo = get_avatar($email, $thumb_sizes['w']*min(2, max(1, happyrider_get_theme_option("retina_ready"))));
		} else {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = happyrider_get_resized_image_tag($photo, $thumb_sizes['w'], $thumb_sizes['h']);
		}

		$post_data = array(
			'post_content' => do_shortcode($content)
		);
		$args = array(
			'layout' => $HAPPYRIDER_GLOBALS['sc_testimonials_style'],
			'number' => $HAPPYRIDER_GLOBALS['sc_testimonials_counter'],
			'columns_count' => $HAPPYRIDER_GLOBALS['sc_testimonials_columns'],
			'slider' => $HAPPYRIDER_GLOBALS['sc_testimonials_slider'],
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => '',
			'tag_css' => $css,
			'tag_css_wh' => $HAPPYRIDER_GLOBALS['sc_testimonials_css_wh'],
			'author' => $author,
			'position' => $position,
			'link' => $link,
			'email' => $email,
			'photo' => $photo
		);
		$output = happyrider_show_post_layout($args, $post_data);

		return apply_filters('happyrider_shortcode_output', $output, 'trx_testimonials_item', $atts, $content);
	}
	if (function_exists('happyrider_require_shortcode')) happyrider_require_shortcode('trx_testimonials_item', 'happyrider_sc_testimonials_item');
}
// ---------------------------------- [/trx_testimonials] ---------------------------------------



// Add [trx_testimonials] and [trx_testimonials_item] in the shortcodes list
if (!function_exists('happyrider_testimonials_reg_shortcodes')) {
	function happyrider_testimonials_reg_shortcodes() {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['shortcodes'])) {

			$testimonials_groups = happyrider_get_list_terms(false, 'testimonial_group');
			$testimonials_styles = happyrider_get_list_templates('testimonials');
			$controls = happyrider_get_list_slider_controls();

			happyrider_array_insert_before($HAPPYRIDER_GLOBALS['shortcodes'], 'trx_title', array(
			
				// Testimonials
				"trx_testimonials" => array(
					"title" => __("Testimonials", "happyrider"),
					"desc" => __("Insert testimonials into post (page)", "happyrider"),
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
							"title" => __("Testimonials style", "happyrider"),
							"desc" => __("Select style to display testimonials", "happyrider"),
							"value" => "testimonials-1",
							"type" => "select",
							"options" => $testimonials_styles
						),
						"columns" => array(
							"title" => __("Columns", "happyrider"),
							"desc" => __("How many columns use to show testimonials", "happyrider"),
							"value" => 1,
							"min" => 1,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"slider" => array(
							"title" => __("Slider", "happyrider"),
							"desc" => __("Use slider to show testimonials", "happyrider"),
							"value" => "yes",
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
							"desc" => __("Alignment of the testimonials block", "happyrider"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['align']
						),
						"custom" => array(
							"title" => __("Custom", "happyrider"),
							"desc" => __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", "happyrider"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => __("Categories", "happyrider"),
							"desc" => __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", "happyrider"),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => happyrider_array_merge(array(0 => __('- Select category -', 'happyrider')), $testimonials_groups)
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
							"value" => "date",
							"type" => "select",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => __("Post order", "happyrider"),
							"desc" => __("Select desired posts order", "happyrider"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "desc",
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
						"scheme" => array(
							"title" => __("Color scheme", "happyrider"),
							"desc" => __("Select color scheme for this block", "happyrider"),
							"value" => "",
							"type" => "checklist",
							"options" => $HAPPYRIDER_GLOBALS['sc_params']['schemes']
						),
						"bg_color" => array(
							"title" => __("Background color", "happyrider"),
							"desc" => __("Any background color for this section", "happyrider"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => __("Background image URL", "happyrider"),
							"desc" => __("Select or upload image or write URL from other site for the background", "happyrider"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => __("Overlay", "happyrider"),
							"desc" => __("Overlay color opacity (from 0.0 to 1.0)", "happyrider"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => __("Texture", "happyrider"),
							"desc" => __("Predefined texture style from 1 to 11. 0 - without texture.", "happyrider"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
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
						"name" => "trx_testimonials_item",
						"title" => __("Item", "happyrider"),
						"desc" => __("Testimonials item (custom parameters)", "happyrider"),
						"container" => true,
						"params" => array(
							"author" => array(
								"title" => __("Author", "happyrider"),
								"desc" => __("Name of the testimonmials author", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"link" => array(
								"title" => __("Link", "happyrider"),
								"desc" => __("Link URL to the testimonmials author page", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => __("E-mail", "happyrider"),
								"desc" => __("E-mail of the testimonmials author (to get gravatar)", "happyrider"),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => __("Photo", "happyrider"),
								"desc" => __("Select or upload photo of testimonmials author or write URL of photo from other site", "happyrider"),
								"value" => "",
								"type" => "media"
							),
							"_content_" => array(
								"title" => __("Testimonials text", "happyrider"),
								"desc" => __("Current testimonials text", "happyrider"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $HAPPYRIDER_GLOBALS['sc_params']['id'],
							"class" => $HAPPYRIDER_GLOBALS['sc_params']['class'],
							"css" => $HAPPYRIDER_GLOBALS['sc_params']['css']
						)
					)
				)

			));
		}
	}
}


// Add [trx_testimonials] and [trx_testimonials_item] in the VC shortcodes list
if (!function_exists('happyrider_testimonials_reg_shortcodes_vc')) {

	function happyrider_testimonials_reg_shortcodes_vc() {
		global $HAPPYRIDER_GLOBALS;

		$testimonials_groups = happyrider_get_list_terms(false, 'testimonial_group');
		$testimonials_styles = happyrider_get_list_templates('testimonials');
		$controls			 = happyrider_get_list_slider_controls();
			
		// Testimonials			
		vc_map( array(
				"base" => "trx_testimonials",
				"name" => __("Testimonials", "happyrider"),
				"description" => __("Insert testimonials slider", "happyrider"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_testimonials',
				"class" => "trx_sc_collection trx_sc_testimonials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_testimonials_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Testimonials style", "happyrider"),
						"description" => __("Select style to display testimonials", "happyrider"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($testimonials_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns", "happyrider"),
						"description" => __("How many columns use to show testimonials", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "slider",
						"heading" => __("Slider", "happyrider"),
						"description" => __("Use slider to show testimonials", "happyrider"),
						"admin_label" => true,
						"group" => __('Slider', 'happyrider'),
						"class" => "",
						"std" => "yes",
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
						"description" => __("Alignment of the testimonials block", "happyrider"),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom", "happyrider"),
						"description" => __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", "happyrider"),
						"class" => "",
						"value" => array("Custom slides" => "yes" ),
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
						"description" => __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", "happyrider"),
						"group" => __('Query', 'happyrider'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(happyrider_array_merge(array(0 => __('- Select category -', 'happyrider')), $testimonials_groups)),
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
						"heading" => __("Post IDs list", "happyrider"),
						"description" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "happyrider"),
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
						"param_name" => "scheme",
						"heading" => __("Color scheme", "happyrider"),
						"description" => __("Select color scheme for this block", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => array_flip($HAPPYRIDER_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "happyrider"),
						"description" => __("Any background color for this section", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image URL", "happyrider"),
						"description" => __("Select background image from library for this section", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", "happyrider"),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", "happyrider"),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", "happyrider"),
						"group" => __('Colors and Images', 'happyrider'),
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
				)
		) );
			
			
		vc_map( array(
				"base" => "trx_testimonials_item",
				"name" => __("Testimonial", "happyrider"),
				"description" => __("Single testimonials item", "happyrider"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_testimonials_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_testimonials_item',
				"as_child" => array('only' => 'trx_testimonials'),
				"as_parent" => array('except' => 'trx_testimonials'),
				"params" => array(
					array(
						"param_name" => "author",
						"heading" => __("Author", "happyrider"),
						"description" => __("Name of the testimonmials author", "happyrider"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link", "happyrider"),
						"description" => __("Link URL to the testimonmials author page", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => __("E-mail", "happyrider"),
						"description" => __("E-mail of the testimonmials author", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => __("Photo", "happyrider"),
						"description" => __("Select or upload photo of testimonmials author or write URL of photo from other site", "happyrider"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),

					$HAPPYRIDER_GLOBALS['vc_params']['id'],
					$HAPPYRIDER_GLOBALS['vc_params']['class'],
					$HAPPYRIDER_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxColumnItemView'
		) );
			
		class WPBakeryShortCode_Trx_Testimonials extends HAPPYRIDER_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Testimonials_Item extends HAPPYRIDER_VC_ShortCodeCollection {}
		
	}
}
?>