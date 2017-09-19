<?php
/**
 * HappyRider Framework: return lists
 *
 * @package happyrider
 * @since happyrider 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'happyrider_get_list_styles' ) ) {
	function happyrider_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(__('Style %d', 'happyrider'), $i);
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'happyrider_get_list_animations' ) ) {
	function happyrider_get_list_animations($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_animations']))
			$list = $HAPPYRIDER_GLOBALS['list_animations'];
		else {
			$list = array();
			$list['none']			= __('- None -',	'happyrider');
			$list['bounced']		= __('Bounced',		'happyrider');
			$list['flash']			= __('Flash',		'happyrider');
			$list['flip']			= __('Flip',		'happyrider');
			$list['pulse']			= __('Pulse',		'happyrider');
			$list['rubberBand']		= __('Rubber Band',	'happyrider');
			$list['shake']			= __('Shake',		'happyrider');
			$list['swing']			= __('Swing',		'happyrider');
			$list['tada']			= __('Tada',		'happyrider');
			$list['wobble']			= __('Wobble',		'happyrider');
			$HAPPYRIDER_GLOBALS['list_animations'] = $list = apply_filters('happyrider_filter_list_animations', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'happyrider_get_list_animations_in' ) ) {
	function happyrider_get_list_animations_in($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_animations_in']))
			$list = $HAPPYRIDER_GLOBALS['list_animations_in'];
		else {
			$list = array();
			$list['none']			= __('- None -',	'happyrider');
			$list['bounceIn']		= __('Bounce In',			'happyrider');
			$list['bounceInUp']		= __('Bounce In Up',		'happyrider');
			$list['bounceInDown']	= __('Bounce In Down',		'happyrider');
			$list['bounceInLeft']	= __('Bounce In Left',		'happyrider');
			$list['bounceInRight']	= __('Bounce In Right',		'happyrider');
			$list['fadeIn']			= __('Fade In',				'happyrider');
			$list['fadeInUp']		= __('Fade In Up',			'happyrider');
			$list['fadeInDown']		= __('Fade In Down',		'happyrider');
			$list['fadeInLeft']		= __('Fade In Left',		'happyrider');
			$list['fadeInRight']	= __('Fade In Right',		'happyrider');
			$list['fadeInUpBig']	= __('Fade In Up Big',		'happyrider');
			$list['fadeInDownBig']	= __('Fade In Down Big',	'happyrider');
			$list['fadeInLeftBig']	= __('Fade In Left Big',	'happyrider');
			$list['fadeInRightBig']	= __('Fade In Right Big',	'happyrider');
			$list['flipInX']		= __('Flip In X',			'happyrider');
			$list['flipInY']		= __('Flip In Y',			'happyrider');
			$list['lightSpeedIn']	= __('Light Speed In',		'happyrider');
			$list['rotateIn']		= __('Rotate In',			'happyrider');
			$list['rotateInUpLeft']		= __('Rotate In Down Left',	'happyrider');
			$list['rotateInUpRight']	= __('Rotate In Up Right',	'happyrider');
			$list['rotateInDownLeft']	= __('Rotate In Up Left',	'happyrider');
			$list['rotateInDownRight']	= __('Rotate In Down Right','happyrider');
			$list['rollIn']				= __('Roll In',			'happyrider');
			$list['slideInUp']			= __('Slide In Up',		'happyrider');
			$list['slideInDown']		= __('Slide In Down',	'happyrider');
			$list['slideInLeft']		= __('Slide In Left',	'happyrider');
			$list['slideInRight']		= __('Slide In Right',	'happyrider');
			$list['zoomIn']				= __('Zoom In',			'happyrider');
			$list['zoomInUp']			= __('Zoom In Up',		'happyrider');
			$list['zoomInDown']			= __('Zoom In Down',	'happyrider');
			$list['zoomInLeft']			= __('Zoom In Left',	'happyrider');
			$list['zoomInRight']		= __('Zoom In Right',	'happyrider');
			$HAPPYRIDER_GLOBALS['list_animations_in'] = $list = apply_filters('happyrider_filter_list_animations_in', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'happyrider_get_list_animations_out' ) ) {
	function happyrider_get_list_animations_out($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_animations_out']))
			$list = $HAPPYRIDER_GLOBALS['list_animations_out'];
		else {
			$list = array();
			$list['none']			= __('- None -',	'happyrider');
			$list['bounceOut']		= __('Bounce Out',			'happyrider');
			$list['bounceOutUp']	= __('Bounce Out Up',		'happyrider');
			$list['bounceOutDown']	= __('Bounce Out Down',		'happyrider');
			$list['bounceOutLeft']	= __('Bounce Out Left',		'happyrider');
			$list['bounceOutRight']	= __('Bounce Out Right',	'happyrider');
			$list['fadeOut']		= __('Fade Out',			'happyrider');
			$list['fadeOutUp']		= __('Fade Out Up',			'happyrider');
			$list['fadeOutDown']	= __('Fade Out Down',		'happyrider');
			$list['fadeOutLeft']	= __('Fade Out Left',		'happyrider');
			$list['fadeOutRight']	= __('Fade Out Right',		'happyrider');
			$list['fadeOutUpBig']	= __('Fade Out Up Big',		'happyrider');
			$list['fadeOutDownBig']	= __('Fade Out Down Big',	'happyrider');
			$list['fadeOutLeftBig']	= __('Fade Out Left Big',	'happyrider');
			$list['fadeOutRightBig']= __('Fade Out Right Big',	'happyrider');
			$list['flipOutX']		= __('Flip Out X',			'happyrider');
			$list['flipOutY']		= __('Flip Out Y',			'happyrider');
			$list['hinge']			= __('Hinge Out',			'happyrider');
			$list['lightSpeedOut']	= __('Light Speed Out',		'happyrider');
			$list['rotateOut']		= __('Rotate Out',			'happyrider');
			$list['rotateOutUpLeft']	= __('Rotate Out Down Left',	'happyrider');
			$list['rotateOutUpRight']	= __('Rotate Out Up Right',		'happyrider');
			$list['rotateOutDownLeft']	= __('Rotate Out Up Left',		'happyrider');
			$list['rotateOutDownRight']	= __('Rotate Out Down Right',	'happyrider');
			$list['rollOut']			= __('Roll Out',		'happyrider');
			$list['slideOutUp']			= __('Slide Out Up',		'happyrider');
			$list['slideOutDown']		= __('Slide Out Down',	'happyrider');
			$list['slideOutLeft']		= __('Slide Out Left',	'happyrider');
			$list['slideOutRight']		= __('Slide Out Right',	'happyrider');
			$list['zoomOut']			= __('Zoom Out',			'happyrider');
			$list['zoomOutUp']			= __('Zoom Out Up',		'happyrider');
			$list['zoomOutDown']		= __('Zoom Out Down',	'happyrider');
			$list['zoomOutLeft']		= __('Zoom Out Left',	'happyrider');
			$list['zoomOutRight']		= __('Zoom Out Right',	'happyrider');
			$HAPPYRIDER_GLOBALS['list_animations_out'] = $list = apply_filters('happyrider_filter_list_animations_out', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('happyrider_get_animation_classes')) {
	function happyrider_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return happyrider_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!happyrider_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of categories
if ( !function_exists( 'happyrider_get_list_categories' ) ) {
	function happyrider_get_list_categories($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_categories']))
			$list = $HAPPYRIDER_GLOBALS['list_categories'];
		else {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			$HAPPYRIDER_GLOBALS['list_categories'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'happyrider_get_list_terms' ) ) {
	function happyrider_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_taxonomies_'.($taxonomy)]))
			$list = $HAPPYRIDER_GLOBALS['list_taxonomies_'.($taxonomy)];
		else {
			$list = array();
			$args = array(
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => $taxonomy,
				'pad_counts'               => false );
			$taxonomies = get_terms( $taxonomy, $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
			$HAPPYRIDER_GLOBALS['list_taxonomies_'.($taxonomy)] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'happyrider_get_list_posts_types' ) ) {
	function happyrider_get_list_posts_types($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_posts_types']))
			$list = $HAPPYRIDER_GLOBALS['list_posts_types'];
		else {
			$list = array();
			// Return only theme inheritance supported post types
			$HAPPYRIDER_GLOBALS['list_posts_types'] = $list = apply_filters('happyrider_filter_list_post_types', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'happyrider_get_list_posts' ) ) {
	function happyrider_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		global $HAPPYRIDER_GLOBALS;
		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (isset($HAPPYRIDER_GLOBALS[$hash]))
			$list = $HAPPYRIDER_GLOBALS[$hash];
		else {
			$list = array();
			$list['none'] = __("- Not selected -", 'happyrider');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			$HAPPYRIDER_GLOBALS[$hash] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}


// Return list of registered users
if ( !function_exists( 'happyrider_get_list_users' ) ) {
	function happyrider_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_users']))
			$list = $HAPPYRIDER_GLOBALS['list_users'];
		else {
			$list = array();
			$list['none'] = __("- Not selected -", 'happyrider');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			$HAPPYRIDER_GLOBALS['list_users'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'happyrider_get_list_sliders' ) ) {
	function happyrider_get_list_sliders($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_sliders']))
			$list = $HAPPYRIDER_GLOBALS['list_sliders'];
		else {
			$list = array(
				'swiper' => __("Posts slider (Swiper)", 'happyrider')
			);
			if (happyrider_exists_revslider())
				$list["revo"] = __("Layer slider (Revolution)", 'happyrider');
			if (happyrider_exists_royalslider())
				$list["royal"] = __("Layer slider (Royal)", 'happyrider');
			$HAPPYRIDER_GLOBALS['list_sliders'] = $list = apply_filters('happyrider_filter_list_sliders', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}


// Return Revo Sliders list, prepended inherit (if need)
if ( !function_exists( 'happyrider_get_list_revo_sliders' ) ) {
	function happyrider_get_list_revo_sliders($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_revo_sliders']))
			$list = $HAPPYRIDER_GLOBALS['list_revo_sliders'];
		else {
			$list = array();
			if (happyrider_exists_revslider()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT alias, title FROM " . esc_sql($wpdb->prefix) . "revslider_sliders" );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->alias] = $row->title;
					}
				}
			}
			$HAPPYRIDER_GLOBALS['list_revo_sliders'] = $list = apply_filters('happyrider_filter_list_revo_sliders', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'happyrider_get_list_slider_controls' ) ) {
	function happyrider_get_list_slider_controls($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_slider_controls']))
			$list = $HAPPYRIDER_GLOBALS['list_slider_controls'];
		else {
			$list = array(
				'no' => __('None', 'happyrider'),
				'side' => __('Side', 'happyrider'),
				'bottom' => __('Bottom', 'happyrider'),
				'pagination' => __('Pagination', 'happyrider')
			);
			$HAPPYRIDER_GLOBALS['list_slider_controls'] = $list = apply_filters('happyrider_filter_list_slider_controls', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'happyrider_get_slider_controls_classes' ) ) {
	function happyrider_get_slider_controls_classes($controls) {
		if (happyrider_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')				$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')			$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else										$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'happyrider_get_list_popup_engines' ) ) {
	function happyrider_get_list_popup_engines($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_popup_engines']))
			$list = $HAPPYRIDER_GLOBALS['list_popup_engines'];
		else {
			$list = array();
			$list["pretty"] = __("Pretty photo", 'happyrider');
			$list["magnific"] = __("Magnific popup", 'happyrider');
			$HAPPYRIDER_GLOBALS['list_popup_engines'] = $list = apply_filters('happyrider_filter_list_popup_engines', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'happyrider_get_list_menus' ) ) {
	function happyrider_get_list_menus($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_menus']))
			$list = $HAPPYRIDER_GLOBALS['list_menus'];
		else {
			$list = array();
			$list['default'] = __("Default", 'happyrider');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			$HAPPYRIDER_GLOBALS['list_menus'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'happyrider_get_list_sidebars' ) ) {
	function happyrider_get_list_sidebars($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_sidebars'])) {
			$list = $HAPPYRIDER_GLOBALS['list_sidebars'];
		} else {
			$list = isset($HAPPYRIDER_GLOBALS['registered_sidebars']) ? $HAPPYRIDER_GLOBALS['registered_sidebars'] : array();
			$HAPPYRIDER_GLOBALS['list_sidebars'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'happyrider_get_list_sidebars_positions' ) ) {
	function happyrider_get_list_sidebars_positions($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_sidebars_positions']))
			$list = $HAPPYRIDER_GLOBALS['list_sidebars_positions'];
		else {
			$list = array();
			$list['none']  = __('Hide',  'happyrider');
			$list['left']  = __('Left',  'happyrider');
			$list['right'] = __('Right', 'happyrider');
			$HAPPYRIDER_GLOBALS['list_sidebars_positions'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'happyrider_get_sidebar_class' ) ) {
	function happyrider_get_sidebar_class() {
		$sb_main = happyrider_get_custom_option('show_sidebar_main');
		$sb_outer = happyrider_get_custom_option('show_sidebar_outer');
		return (happyrider_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (happyrider_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'happyrider_get_list_body_styles' ) ) {
	function happyrider_get_list_body_styles($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_body_styles']))
			$list = $HAPPYRIDER_GLOBALS['list_body_styles'];
		else {
			$list = array();
			$list['boxed']		= __('Boxed',		'happyrider');
			$list['wide']		= __('Wide',		'happyrider');
			if (happyrider_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= __('Fullwide',	'happyrider');
				$list['fullscreen']	= __('Fullscreen',	'happyrider');
			}
			$HAPPYRIDER_GLOBALS['list_body_styles'] = $list = apply_filters('happyrider_filter_list_body_styles', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return skins list, prepended inherit
if ( !function_exists( 'happyrider_get_list_skins' ) ) {
	function happyrider_get_list_skins($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_skins']))
			$list = $HAPPYRIDER_GLOBALS['list_skins'];
		else
			$HAPPYRIDER_GLOBALS['list_skins'] = $list = happyrider_get_list_folders("skins");
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return css-themes list
if ( !function_exists( 'happyrider_get_list_themes' ) ) {
	function happyrider_get_list_themes($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_themes']))
			$list = $HAPPYRIDER_GLOBALS['list_themes'];
		else
			$HAPPYRIDER_GLOBALS['list_themes'] = $list = happyrider_get_list_files("css/themes");
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'happyrider_get_list_templates' ) ) {
	function happyrider_get_list_templates($mode='') {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_templates_'.($mode)]))
			$list = $HAPPYRIDER_GLOBALS['list_templates_'.($mode)];
		else {
			$list = array();
			if (is_array($HAPPYRIDER_GLOBALS['registered_templates']) && count($HAPPYRIDER_GLOBALS['registered_templates']) > 0) {
				foreach ($HAPPYRIDER_GLOBALS['registered_templates'] as $k=>$v) {
					if ($mode=='' || happyrider_strpos($v['mode'], $mode)!==false)
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: happyrider_strtoproper($v['layout'])
										);
				}
			}
			$HAPPYRIDER_GLOBALS['list_templates_'.($mode)] = $list;
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'happyrider_get_list_templates_blog' ) ) {
	function happyrider_get_list_templates_blog($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_templates_blog']))
			$list = $HAPPYRIDER_GLOBALS['list_templates_blog'];
		else {
			$list = happyrider_get_list_templates('blog');
			$HAPPYRIDER_GLOBALS['list_templates_blog'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'happyrider_get_list_templates_blogger' ) ) {
	function happyrider_get_list_templates_blogger($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_templates_blogger']))
			$list = $HAPPYRIDER_GLOBALS['list_templates_blogger'];
		else {
			$list = happyrider_array_merge(happyrider_get_list_templates('blogger'), happyrider_get_list_templates('blog'));
			$HAPPYRIDER_GLOBALS['list_templates_blogger'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'happyrider_get_list_templates_single' ) ) {
	function happyrider_get_list_templates_single($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_templates_single']))
			$list = $HAPPYRIDER_GLOBALS['list_templates_single'];
		else {
			$list = happyrider_get_list_templates('single');
			$HAPPYRIDER_GLOBALS['list_templates_single'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'happyrider_get_list_templates_header' ) ) {
	function happyrider_get_list_templates_header($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_templates_header']))
			$list = $HAPPYRIDER_GLOBALS['list_templates_header'];
		else {
			$list = happyrider_get_list_templates('header');
			$HAPPYRIDER_GLOBALS['list_templates_header'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'happyrider_get_list_article_styles' ) ) {
	function happyrider_get_list_article_styles($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_article_styles']))
			$list = $HAPPYRIDER_GLOBALS['list_article_styles'];
		else {
			$list = array();
			$list["boxed"]   = __('Boxed', 'happyrider');
			$list["stretch"] = __('Stretch', 'happyrider');
			$HAPPYRIDER_GLOBALS['list_article_styles'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'happyrider_get_list_post_formats_filters' ) ) {
	function happyrider_get_list_post_formats_filters($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_post_formats_filters']))
			$list = $HAPPYRIDER_GLOBALS['list_post_formats_filters'];
		else {
			$list = array();
			$list["no"]      = __('All posts', 'happyrider');
			$list["thumbs"]  = __('With thumbs', 'happyrider');
			$list["reviews"] = __('With reviews', 'happyrider');
			$list["video"]   = __('With videos', 'happyrider');
			$list["audio"]   = __('With audios', 'happyrider');
			$list["gallery"] = __('With galleries', 'happyrider');
			$HAPPYRIDER_GLOBALS['list_post_formats_filters'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'happyrider_get_list_portfolio_filters' ) ) {
	function happyrider_get_list_portfolio_filters($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_portfolio_filters']))
			$list = $HAPPYRIDER_GLOBALS['list_portfolio_filters'];
		else {
			$list = array();
			$list["hide"] = __('Hide', 'happyrider');
			$list["tags"] = __('Tags', 'happyrider');
			$list["categories"] = __('Categories', 'happyrider');
			$HAPPYRIDER_GLOBALS['list_portfolio_filters'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'happyrider_get_list_hovers' ) ) {
	function happyrider_get_list_hovers($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_hovers']))
			$list = $HAPPYRIDER_GLOBALS['list_hovers'];
		else {
			$list = array();
			$list['circle effect1']  = __('Circle Effect 1',  'happyrider');
			$list['circle effect2']  = __('Circle Effect 2',  'happyrider');
			$list['circle effect3']  = __('Circle Effect 3',  'happyrider');
			$list['circle effect4']  = __('Circle Effect 4',  'happyrider');
			$list['circle effect5']  = __('Circle Effect 5',  'happyrider');
			$list['circle effect6']  = __('Circle Effect 6',  'happyrider');
			$list['circle effect7']  = __('Circle Effect 7',  'happyrider');
			$list['circle effect8']  = __('Circle Effect 8',  'happyrider');
			$list['circle effect9']  = __('Circle Effect 9',  'happyrider');
			$list['circle effect10'] = __('Circle Effect 10',  'happyrider');
			$list['circle effect11'] = __('Circle Effect 11',  'happyrider');
			$list['circle effect12'] = __('Circle Effect 12',  'happyrider');
			$list['circle effect13'] = __('Circle Effect 13',  'happyrider');
			$list['circle effect14'] = __('Circle Effect 14',  'happyrider');
			$list['circle effect15'] = __('Circle Effect 15',  'happyrider');
			$list['circle effect16'] = __('Circle Effect 16',  'happyrider');
			$list['circle effect17'] = __('Circle Effect 17',  'happyrider');
			$list['circle effect18'] = __('Circle Effect 18',  'happyrider');
			$list['circle effect19'] = __('Circle Effect 19',  'happyrider');
			$list['circle effect20'] = __('Circle Effect 20',  'happyrider');
			$list['square effect1']  = __('Square Effect 1',  'happyrider');
			$list['square effect2']  = __('Square Effect 2',  'happyrider');
			$list['square effect3']  = __('Square Effect 3',  'happyrider');
	//		$list['square effect4']  = __('Square Effect 4',  'happyrider');
			$list['square effect5']  = __('Square Effect 5',  'happyrider');
			$list['square effect6']  = __('Square Effect 6',  'happyrider');
			$list['square effect7']  = __('Square Effect 7',  'happyrider');
			$list['square effect8']  = __('Square Effect 8',  'happyrider');
			$list['square effect9']  = __('Square Effect 9',  'happyrider');
			$list['square effect10'] = __('Square Effect 10',  'happyrider');
			$list['square effect11'] = __('Square Effect 11',  'happyrider');
			$list['square effect12'] = __('Square Effect 12',  'happyrider');
			$list['square effect13'] = __('Square Effect 13',  'happyrider');
			$list['square effect14'] = __('Square Effect 14',  'happyrider');
			$list['square effect15'] = __('Square Effect 15',  'happyrider');
			$list['square effect_dir']   = __('Square Effect Dir',   'happyrider');
			$list['square effect_shift'] = __('Square Effect Shift', 'happyrider');
			$list['square effect_book']  = __('Square Effect Book',  'happyrider');
			$list['square effect_more']  = __('Square Effect More',  'happyrider');
			$list['square effect_fade']  = __('Square Effect Fade',  'happyrider');
			$HAPPYRIDER_GLOBALS['list_hovers'] = $list = apply_filters('happyrider_filter_portfolio_hovers', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'happyrider_get_list_hovers_directions' ) ) {
	function happyrider_get_list_hovers_directions($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_hovers_directions']))
			$list = $HAPPYRIDER_GLOBALS['list_hovers_directions'];
		else {
			$list = array();
			$list['left_to_right'] = __('Left to Right',  'happyrider');
			$list['right_to_left'] = __('Right to Left',  'happyrider');
			$list['top_to_bottom'] = __('Top to Bottom',  'happyrider');
			$list['bottom_to_top'] = __('Bottom to Top',  'happyrider');
			$list['scale_up']      = __('Scale Up',  'happyrider');
			$list['scale_down']    = __('Scale Down',  'happyrider');
			$list['scale_down_up'] = __('Scale Down-Up',  'happyrider');
			$list['from_left_and_right'] = __('From Left and Right',  'happyrider');
			$list['from_top_and_bottom'] = __('From Top and Bottom',  'happyrider');
			$HAPPYRIDER_GLOBALS['list_hovers_directions'] = $list = apply_filters('happyrider_filter_portfolio_hovers_directions', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'happyrider_get_list_label_positions' ) ) {
	function happyrider_get_list_label_positions($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_label_positions']))
			$list = $HAPPYRIDER_GLOBALS['list_label_positions'];
		else {
			$list = array();
			$list['top']	= __('Top',		'happyrider');
			$list['bottom']	= __('Bottom',		'happyrider');
			$list['left']	= __('Left',		'happyrider');
			$list['over']	= __('Over',		'happyrider');
			$HAPPYRIDER_GLOBALS['list_label_positions'] = $list = apply_filters('happyrider_filter_label_positions', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'happyrider_get_list_bg_image_positions' ) ) {
	function happyrider_get_list_bg_image_positions($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_bg_image_positions']))
			$list = $HAPPYRIDER_GLOBALS['list_bg_image_positions'];
		else {
			$list = array();
			$list['left top']	  = __('Left Top', 'happyrider');
			$list['center top']   = __("Center Top", 'happyrider');
			$list['right top']    = __("Right Top", 'happyrider');
			$list['left center']  = __("Left Center", 'happyrider');
			$list['center center']= __("Center Center", 'happyrider');
			$list['right center'] = __("Right Center", 'happyrider');
			$list['left bottom']  = __("Left Bottom", 'happyrider');
			$list['center bottom']= __("Center Bottom", 'happyrider');
			$list['right bottom'] = __("Right Bottom", 'happyrider');
			$HAPPYRIDER_GLOBALS['list_bg_image_positions'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'happyrider_get_list_bg_image_repeats' ) ) {
	function happyrider_get_list_bg_image_repeats($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_bg_image_repeats']))
			$list = $HAPPYRIDER_GLOBALS['list_bg_image_repeats'];
		else {
			$list = array();
			$list['repeat']	  = __('Repeat', 'happyrider');
			$list['repeat-x'] = __('Repeat X', 'happyrider');
			$list['repeat-y'] = __('Repeat Y', 'happyrider');
			$list['no-repeat']= __('No Repeat', 'happyrider');
			$HAPPYRIDER_GLOBALS['list_bg_image_repeats'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'happyrider_get_list_bg_image_attachments' ) ) {
	function happyrider_get_list_bg_image_attachments($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_bg_image_attachments']))
			$list = $HAPPYRIDER_GLOBALS['list_bg_image_attachments'];
		else {
			$list = array();
			$list['scroll']	= __('Scroll', 'happyrider');
			$list['fixed']	= __('Fixed', 'happyrider');
			$list['local']	= __('Local', 'happyrider');
			$HAPPYRIDER_GLOBALS['list_bg_image_attachments'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'happyrider_get_list_bg_tints' ) ) {
	function happyrider_get_list_bg_tints($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_bg_tints']))
			$list = $HAPPYRIDER_GLOBALS['list_bg_tints'];
		else {
			$list = array();
			$list['white']	= __('White', 'happyrider');
			$list['light']	= __('Light', 'happyrider');
			$list['dark']	= __('Dark', 'happyrider');
			$HAPPYRIDER_GLOBALS['list_bg_tints'] = $list = apply_filters('happyrider_filter_bg_tints', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'happyrider_get_list_field_types' ) ) {
	function happyrider_get_list_field_types($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_field_types']))
			$list = $HAPPYRIDER_GLOBALS['list_field_types'];
		else {
			$list = array();
			$list['text']     = __('Text',  'happyrider');
			$list['textarea'] = __('Text Area','happyrider');
			$list['password'] = __('Password',  'happyrider');
			$list['radio']    = __('Radio',  'happyrider');
			$list['checkbox'] = __('Checkbox',  'happyrider');
			$list['select']   = __('Select',  'happyrider');
			$list['button']   = __('Button','happyrider');
			$HAPPYRIDER_GLOBALS['list_field_types'] = $list = apply_filters('happyrider_filter_field_types', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'happyrider_get_list_googlemap_styles' ) ) {
	function happyrider_get_list_googlemap_styles($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_googlemap_styles']))
			$list = $HAPPYRIDER_GLOBALS['list_googlemap_styles'];
		else {
			$list = array();
			$list['default'] = __('Default', 'happyrider');
			$list['simple'] = __('Simple', 'happyrider');
			$list['greyscale'] = __('Greyscale', 'happyrider');
			$list['greyscale2'] = __('Greyscale 2', 'happyrider');
			$list['invert'] = __('Invert', 'happyrider');
			$list['dark'] = __('Dark', 'happyrider');
			$list['style1'] = __('Custom style 1', 'happyrider');
			$list['style2'] = __('Custom style 2', 'happyrider');
			$list['style3'] = __('Custom style 3', 'happyrider');
			$HAPPYRIDER_GLOBALS['list_googlemap_styles'] = $list = apply_filters('happyrider_filter_googlemap_styles', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'happyrider_get_list_icons' ) ) {
	function happyrider_get_list_icons($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_icons']))
			$list = $HAPPYRIDER_GLOBALS['list_icons'];
		else
			$HAPPYRIDER_GLOBALS['list_icons'] = $list = happyrider_parse_icons_classes(happyrider_get_file_dir("css/fontello/css/fontello-codes.css"));
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'happyrider_get_list_socials' ) ) {
	function happyrider_get_list_socials($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_socials']))
			$list = $HAPPYRIDER_GLOBALS['list_socials'];
		else
			$HAPPYRIDER_GLOBALS['list_socials'] = $list = happyrider_get_list_files("images/socials", "png");
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'happyrider_get_list_flags' ) ) {
	function happyrider_get_list_flags($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_flags']))
			$list = $HAPPYRIDER_GLOBALS['list_flags'];
		else
			$HAPPYRIDER_GLOBALS['list_flags'] = $list = happyrider_get_list_files("images/flags", "png");
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'happyrider_get_list_yesno' ) ) {
	function happyrider_get_list_yesno($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_yesno']))
			$list = $HAPPYRIDER_GLOBALS['list_yesno'];
		else {
			$list = array();
			$list["yes"] = __("Yes", 'happyrider');
			$list["no"]  = __("No", 'happyrider');
			$HAPPYRIDER_GLOBALS['list_yesno'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'happyrider_get_list_onoff' ) ) {
	function happyrider_get_list_onoff($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_onoff']))
			$list = $HAPPYRIDER_GLOBALS['list_onoff'];
		else {
			$list = array();
			$list["on"] = __("On", 'happyrider');
			$list["off"] = __("Off", 'happyrider');
			$HAPPYRIDER_GLOBALS['list_onoff'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'happyrider_get_list_showhide' ) ) {
	function happyrider_get_list_showhide($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_showhide']))
			$list = $HAPPYRIDER_GLOBALS['list_showhide'];
		else {
			$list = array();
			$list["show"] = __("Show", 'happyrider');
			$list["hide"] = __("Hide", 'happyrider');
			$HAPPYRIDER_GLOBALS['list_showhide'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'happyrider_get_list_orderings' ) ) {
	function happyrider_get_list_orderings($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_orderings']))
			$list = $HAPPYRIDER_GLOBALS['list_orderings'];
		else {
			$list = array();
			$list["asc"] = __("Ascending", 'happyrider');
			$list["desc"] = __("Descending", 'happyrider');
			$HAPPYRIDER_GLOBALS['list_orderings'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'happyrider_get_list_directions' ) ) {
	function happyrider_get_list_directions($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_directions']))
			$list = $HAPPYRIDER_GLOBALS['list_directions'];
		else {
			$list = array();
			$list["horizontal"] = __("Horizontal", 'happyrider');
			$list["vertical"] = __("Vertical", 'happyrider');
			$HAPPYRIDER_GLOBALS['list_directions'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'happyrider_get_list_shapes' ) ) {
	function happyrider_get_list_shapes($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_shapes']))
			$list = $HAPPYRIDER_GLOBALS['list_shapes'];
		else {
			$list = array();
			$list["round"]  = __("Round", 'happyrider');
			$list["square"] = __("Square", 'happyrider');
			$HAPPYRIDER_GLOBALS['list_shapes'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'happyrider_get_list_sizes' ) ) {
	function happyrider_get_list_sizes($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_sizes']))
			$list = $HAPPYRIDER_GLOBALS['list_sizes'];
		else {
			$list = array();
			$list["tiny"]   = __("Tiny", 'happyrider');
			$list["small"]  = __("Small", 'happyrider');
			$list["medium"] = __("Medium", 'happyrider');
			$list["large"]  = __("Large", 'happyrider');
			$HAPPYRIDER_GLOBALS['list_sizes'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'happyrider_get_list_floats' ) ) {
	function happyrider_get_list_floats($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_floats']))
			$list = $HAPPYRIDER_GLOBALS['list_floats'];
		else {
			$list = array();
			$list["none"] = __("None", 'happyrider');
			$list["left"] = __("Float Left", 'happyrider');
			$list["right"] = __("Float Right", 'happyrider');
			$HAPPYRIDER_GLOBALS['list_floats'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'happyrider_get_list_alignments' ) ) {
	function happyrider_get_list_alignments($justify=false, $prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_alignments']))
			$list = $HAPPYRIDER_GLOBALS['list_alignments'];
		else {
			$list = array();
			$list["none"] = __("None", 'happyrider');
			$list["left"] = __("Left", 'happyrider');
			$list["center"] = __("Center", 'happyrider');
			$list["right"] = __("Right", 'happyrider');
			if ($justify) $list["justify"] = __("Justify", 'happyrider');
			$HAPPYRIDER_GLOBALS['list_alignments'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'happyrider_get_list_sortings' ) ) {
	function happyrider_get_list_sortings($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_sortings']))
			$list = $HAPPYRIDER_GLOBALS['list_sortings'];
		else {
			$list = array();
			$list["date"] = __("Date", 'happyrider');
			$list["title"] = __("Alphabetically", 'happyrider');
			$list["views"] = __("Popular (views count)", 'happyrider');
			$list["comments"] = __("Most commented (comments count)", 'happyrider');
			$list["author_rating"] = __("Author rating", 'happyrider');
			$list["users_rating"] = __("Visitors (users) rating", 'happyrider');
			$list["random"] = __("Random", 'happyrider');
			$HAPPYRIDER_GLOBALS['list_sortings'] = $list = apply_filters('happyrider_filter_list_sortings', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'happyrider_get_list_columns' ) ) {
	function happyrider_get_list_columns($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_columns']))
			$list = $HAPPYRIDER_GLOBALS['list_columns'];
		else {
			$list = array();
			$list["none"] = __("None", 'happyrider');
			$list["1_1"] = __("100%", 'happyrider');
			$list["1_2"] = __("1/2", 'happyrider');
			$list["1_3"] = __("1/3", 'happyrider');
			$list["2_3"] = __("2/3", 'happyrider');
			$list["1_4"] = __("1/4", 'happyrider');
			$list["3_4"] = __("3/4", 'happyrider');
			$list["1_5"] = __("1/5", 'happyrider');
			$list["2_5"] = __("2/5", 'happyrider');
			$list["3_5"] = __("3/5", 'happyrider');
			$list["4_5"] = __("4/5", 'happyrider');
			$list["1_6"] = __("1/6", 'happyrider');
			$list["5_6"] = __("5/6", 'happyrider');
			$list["1_7"] = __("1/7", 'happyrider');
			$list["2_7"] = __("2/7", 'happyrider');
			$list["3_7"] = __("3/7", 'happyrider');
			$list["4_7"] = __("4/7", 'happyrider');
			$list["5_7"] = __("5/7", 'happyrider');
			$list["6_7"] = __("6/7", 'happyrider');
			$list["1_8"] = __("1/8", 'happyrider');
			$list["3_8"] = __("3/8", 'happyrider');
			$list["5_8"] = __("5/8", 'happyrider');
			$list["7_8"] = __("7/8", 'happyrider');
			$list["1_9"] = __("1/9", 'happyrider');
			$list["2_9"] = __("2/9", 'happyrider');
			$list["4_9"] = __("4/9", 'happyrider');
			$list["5_9"] = __("5/9", 'happyrider');
			$list["7_9"] = __("7/9", 'happyrider');
			$list["8_9"] = __("8/9", 'happyrider');
			$list["1_10"]= __("1/10", 'happyrider');
			$list["3_10"]= __("3/10", 'happyrider');
			$list["7_10"]= __("7/10", 'happyrider');
			$list["9_10"]= __("9/10", 'happyrider');
			$list["1_11"]= __("1/11", 'happyrider');
			$list["2_11"]= __("2/11", 'happyrider');
			$list["3_11"]= __("3/11", 'happyrider');
			$list["4_11"]= __("4/11", 'happyrider');
			$list["5_11"]= __("5/11", 'happyrider');
			$list["6_11"]= __("6/11", 'happyrider');
			$list["7_11"]= __("7/11", 'happyrider');
			$list["8_11"]= __("8/11", 'happyrider');
			$list["9_11"]= __("9/11", 'happyrider');
			$list["10_11"]= __("10/11", 'happyrider');
			$list["1_12"]= __("1/12", 'happyrider');
			$list["5_12"]= __("5/12", 'happyrider');
			$list["7_12"]= __("7/12", 'happyrider');
			$list["10_12"]= __("10/12", 'happyrider');
			$list["11_12"]= __("11/12", 'happyrider');
			$HAPPYRIDER_GLOBALS['list_columns'] = $list = apply_filters('happyrider_filter_list_columns', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'happyrider_get_list_dedicated_locations' ) ) {
	function happyrider_get_list_dedicated_locations($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_dedicated_locations']))
			$list = $HAPPYRIDER_GLOBALS['list_dedicated_locations'];
		else {
			$list = array();
			$list["default"] = __('As in the post defined', 'happyrider');
			$list["center"]  = __('Above the text of the post', 'happyrider');
			$list["left"]    = __('To the left the text of the post', 'happyrider');
			$list["right"]   = __('To the right the text of the post', 'happyrider');
			$list["alter"]   = __('Alternates for each post', 'happyrider');
			$HAPPYRIDER_GLOBALS['list_dedicated_locations'] = $list = apply_filters('happyrider_filter_list_dedicated_locations', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'happyrider_get_post_format_name' ) ) {
	function happyrider_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? __('gallery', 'happyrider') : __('galleries', 'happyrider');
		else if ($format=='video')	$name = $single ? __('video', 'happyrider') : __('videos', 'happyrider');
		else if ($format=='audio')	$name = $single ? __('audio', 'happyrider') : __('audios', 'happyrider');
		else if ($format=='image')	$name = $single ? __('image', 'happyrider') : __('images', 'happyrider');
		else if ($format=='quote')	$name = $single ? __('quote', 'happyrider') : __('quotes', 'happyrider');
		else if ($format=='link')	$name = $single ? __('link', 'happyrider') : __('links', 'happyrider');
		else if ($format=='status')	$name = $single ? __('status', 'happyrider') : __('statuses', 'happyrider');
		else if ($format=='aside')	$name = $single ? __('aside', 'happyrider') : __('asides', 'happyrider');
		else if ($format=='chat')	$name = $single ? __('chat', 'happyrider') : __('chats', 'happyrider');
		else						$name = $single ? __('standard', 'happyrider') : __('standards', 'happyrider');
		return apply_filters('happyrider_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'happyrider_get_post_format_icon' ) ) {
	function happyrider_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('happyrider_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'happyrider_get_list_fonts_styles' ) ) {
	function happyrider_get_list_fonts_styles($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_fonts_styles']))
			$list = $HAPPYRIDER_GLOBALS['list_fonts_styles'];
		else {
			$list = array();
			$list['i'] = __('I','happyrider');
			$list['u'] = __('U', 'happyrider');
			$HAPPYRIDER_GLOBALS['list_fonts_styles'] = $list;
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'happyrider_get_list_fonts' ) ) {
	function happyrider_get_list_fonts($prepend_inherit=false) {
		global $HAPPYRIDER_GLOBALS;
		if (isset($HAPPYRIDER_GLOBALS['list_fonts']))
			$list = $HAPPYRIDER_GLOBALS['list_fonts'];
		else {
			$list = array();
			$list = happyrider_array_merge($list, happyrider_get_list_font_faces());
			// Google and custom fonts list:
			//$list['Advent Pro'] = array(
			//		'family'=>'sans-serif',																						// (required) font family
			//		'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
			//		'css'=>happyrider_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
			//		);
			$list['Advent Pro'] = array('family'=>'sans-serif');
			$list['Alegreya Sans'] = array('family'=>'sans-serif');
			$list['Arimo'] = array('family'=>'sans-serif');
			$list['Asap'] = array('family'=>'sans-serif');
			$list['Averia Sans Libre'] = array('family'=>'cursive');
			$list['Averia Serif Libre'] = array('family'=>'cursive');
			$list['Bree Serif'] = array('family'=>'serif',);
			$list['Cabin'] = array('family'=>'sans-serif');
			$list['Cabin Condensed'] = array('family'=>'sans-serif');
			$list['Caudex'] = array('family'=>'serif');
			$list['Comfortaa'] = array('family'=>'cursive');
			$list['Cousine'] = array('family'=>'sans-serif');
			$list['Crimson Text'] = array('family'=>'serif');
			$list['Cuprum'] = array('family'=>'sans-serif');
			$list['Dosis'] = array('family'=>'sans-serif');
			$list['Economica'] = array('family'=>'sans-serif');
			$list['Exo'] = array('family'=>'sans-serif');
			$list['Expletus Sans'] = array('family'=>'cursive');
			$list['Karla'] = array('family'=>'sans-serif');
			$list['Lato'] = array('family'=>'sans-serif');
			$list['Lekton'] = array('family'=>'sans-serif');
			$list['Lobster Two'] = array('family'=>'cursive');
			$list['Maven Pro'] = array('family'=>'sans-serif');
			$list['Merriweather'] = array('family'=>'serif');
			$list['Montserrat'] = array('family'=>'sans-serif');
			$list['Neuton'] = array('family'=>'serif');
			$list['Noticia Text'] = array('family'=>'serif');
			$list['Old Standard TT'] = array('family'=>'serif');
			$list['Open Sans'] = array('family'=>'sans-serif');
			$list['Orbitron'] = array('family'=>'sans-serif');
			$list['Oswald'] = array('family'=>'sans-serif');
			$list['Overlock'] = array('family'=>'cursive');
			$list['Oxygen'] = array('family'=>'sans-serif');
			$list['PT Serif'] = array('family'=>'serif');
			$list['Puritan'] = array('family'=>'sans-serif');
			$list['Raleway'] = array('family'=>'sans-serif');
			$list['Roboto'] = array('family'=>'sans-serif');
			$list['Roboto Slab'] = array('family'=>'sans-serif');
			$list['Roboto Condensed'] = array('family'=>'sans-serif');
			$list['Rosario'] = array('family'=>'sans-serif');
			$list['Share'] = array('family'=>'cursive');
			$list['Signika'] = array('family'=>'sans-serif');
			$list['Signika Negative'] = array('family'=>'sans-serif');
			$list['Source Sans Pro'] = array('family'=>'sans-serif');
			$list['Tinos'] = array('family'=>'serif');
			$list['Ubuntu'] = array('family'=>'sans-serif');
			$list['Vollkorn'] = array('family'=>'serif');
			$HAPPYRIDER_GLOBALS['list_fonts'] = $list = apply_filters('happyrider_filter_list_fonts', $list);
		}
		return $prepend_inherit ? happyrider_array_merge(array('inherit' => __("Inherit", 'happyrider')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'happyrider_get_list_font_faces' ) ) {
	function happyrider_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$list = array();
		$dir = happyrider_get_folder_dir("css/font-face");
		if ( is_dir($dir) ) {
			$hdir = @ opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					$pi = pathinfo( ($dir) . '/' . ($file) );
					if ( substr($file, 0, 1) == '.' || ! is_dir( ($dir) . '/' . ($file) ) )
						continue;
					$css = file_exists( ($dir) . '/' . ($file) . '/' . ($file) . '.css' ) 
						? happyrider_get_folder_url("css/font-face/".($file).'/'.($file).'.css')
						: (file_exists( ($dir) . '/' . ($file) . '/stylesheet.css' ) 
							? happyrider_get_folder_url("css/font-face/".($file).'/stylesheet.css')
							: '');
					if ($css != '')
						$list[$file.' ('.__('uploaded font', 'happyrider').')'] = array('css' => $css);
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}
?>