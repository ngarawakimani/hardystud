<?php
/* Woocommerce support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('happyrider_woocommerce_theme_setup')) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_woocommerce_theme_setup', 1 );
	function happyrider_woocommerce_theme_setup() {

		if (happyrider_exists_woocommerce()) {
			add_action('happyrider_action_add_styles', 				'happyrider_woocommerce_frontend_scripts' );

			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('happyrider_filter_get_blog_type',				'happyrider_woocommerce_get_blog_type', 9, 2);
			add_filter('happyrider_filter_get_blog_title',			'happyrider_woocommerce_get_blog_title', 9, 2);
			add_filter('happyrider_filter_get_current_taxonomy',		'happyrider_woocommerce_get_current_taxonomy', 9, 2);
			add_filter('happyrider_filter_is_taxonomy',				'happyrider_woocommerce_is_taxonomy', 9, 2);
			add_filter('happyrider_filter_get_stream_page_title',		'happyrider_woocommerce_get_stream_page_title', 9, 2);
			add_filter('happyrider_filter_get_stream_page_link',		'happyrider_woocommerce_get_stream_page_link', 9, 2);
			add_filter('happyrider_filter_get_stream_page_id',		'happyrider_woocommerce_get_stream_page_id', 9, 2);
			add_filter('happyrider_filter_detect_inheritance_key',	'happyrider_woocommerce_detect_inheritance_key', 9, 1);
			add_filter('happyrider_filter_detect_template_page_id',	'happyrider_woocommerce_detect_template_page_id', 9, 2);

			add_filter('happyrider_filter_list_post_types', 			'happyrider_woocommerce_list_post_types', 10, 1);
		}
	}
}

if ( !function_exists( 'happyrider_woocommerce_settings_theme_setup2' ) ) {
	add_action( 'happyrider_action_before_init_theme', 'happyrider_woocommerce_settings_theme_setup2', 3 );
	function happyrider_woocommerce_settings_theme_setup2() {
		if (happyrider_exists_woocommerce()) {
			// Add WooCommerce pages in the Theme inheritance system
			happyrider_add_theme_inheritance( array( 'woocommerce' => array(
				'stream_template' => '',
				'single_template' => '',
				'taxonomy' => array('product_cat'),
				'taxonomy_tags' => array('product_tag'),
				'post_type' => array('product'),
				'override' => 'page'
				) )
			);

			// Add WooCommerce specific options in the Theme Options
			global $HAPPYRIDER_GLOBALS;

			happyrider_array_insert_before($HAPPYRIDER_GLOBALS['options'], 'partition_service', array(
				
				"partition_woocommerce" => array(
					"title" => __('WooCommerce', 'happyrider'),
					"icon" => "iconadmin-basket",
					"type" => "partition"),

				"info_wooc_1" => array(
					"title" => __('WooCommerce products list parameters', 'happyrider'),
					"desc" => __("Select WooCommerce products list's style and crop parameters", 'happyrider'),
					"type" => "info"),
		
				"shop_mode" => array(
					"title" => __('Shop list style',  'happyrider'),
					"desc" => __("WooCommerce products list's style: thumbs or list with description", 'happyrider'),
					"std" => "thumbs",
					"divider" => false,
					"options" => array(
						'thumbs' => __('Thumbs', 'happyrider'),
						'list' => __('List', 'happyrider')
					),
					"type" => "checklist"),
		
				"show_mode_buttons" => array(
					"title" => __('Show style buttons',  'happyrider'),
					"desc" => __("Show buttons to allow visitors change list style", 'happyrider'),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

				"shop_loop_columns" => array(
					"title" => __('Shop columns',  'happyrider'),
					"desc" => __("How many columns used to show products on shop page", 'happyrider'),
					"override" => "category,post,page",
					"std" => "3",
					"step" => 1,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),

				"show_currency" => array(
					"title" => __('Show currency selector', 'happyrider'),
					"desc" => __('Show currency selector in the user menu', 'happyrider'),
					"std" => "yes",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
				"show_cart" => array(
					"title" => __('Show cart button', 'happyrider'),
					"desc" => __('Show cart button in the user menu', 'happyrider'),
					"std" => "shop",
					"options" => array(
						'hide'   => __('Hide', 'happyrider'),
						'always' => __('Always', 'happyrider'),
						'shop'   => __('Only on shop pages', 'happyrider')
					),
					"type" => "checklist"),

				"crop_product_thumb" => array(
					"title" => __("Crop product's thumbnail",  'happyrider'),
					"desc" => __("Crop product's thumbnails on search results page or scale it", 'happyrider'),
					"std" => "no",
					"options" => $HAPPYRIDER_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch")
				
				)
			);

		}
	}
}

// WooCommerce hooks
if (!function_exists('happyrider_woocommerce_theme_setup3')) {
	add_action( 'happyrider_action_after_init_theme', 'happyrider_woocommerce_theme_setup3' );
	function happyrider_woocommerce_theme_setup3() {

		if (happyrider_exists_woocommerce()) {

			add_action(    'woocommerce_before_subcategory_title',		'happyrider_woocommerce_open_thumb_wrapper', 9 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'happyrider_woocommerce_open_thumb_wrapper', 9 );

			add_action(    'woocommerce_before_subcategory_title',		'happyrider_woocommerce_open_item_wrapper', 20 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'happyrider_woocommerce_open_item_wrapper', 20 );

			add_action(    'woocommerce_after_subcategory',				'happyrider_woocommerce_close_item_wrapper', 20 );
			add_action(    'woocommerce_after_shop_loop_item',			'happyrider_woocommerce_close_item_wrapper', 20 );

			add_action(    'woocommerce_after_shop_loop_item_title',	'happyrider_woocommerce_after_shop_loop_item_title', 7);

			add_action(    'woocommerce_after_subcategory_title',		'happyrider_woocommerce_after_subcategory_title', 10 );
		}

		if (happyrider_is_woocommerce_page()) {
			
			remove_action( 'woocommerce_sidebar', 						'woocommerce_get_sidebar', 10 );					// Remove WOOC sidebar
			
			remove_action( 'woocommerce_before_main_content',			'woocommerce_output_content_wrapper', 10);
			add_action(    'woocommerce_before_main_content',			'happyrider_woocommerce_wrapper_start', 10);
			
			remove_action( 'woocommerce_after_main_content',			'woocommerce_output_content_wrapper_end', 10);		
			add_action(    'woocommerce_after_main_content',			'happyrider_woocommerce_wrapper_end', 10);

			add_action(    'woocommerce_show_page_title',				'happyrider_woocommerce_show_page_title', 10);

			remove_action( 'woocommerce_single_product_summary',		'woocommerce_template_single_title', 5);		
			add_action(    'woocommerce_single_product_summary',		'happyrider_woocommerce_show_product_title', 5 );

			add_action(    'woocommerce_before_shop_loop', 				'happyrider_woocommerce_before_shop_loop', 10 );

			remove_action( 'woocommerce_after_shop_loop',				'woocommerce_pagination', 10 );
			add_action(    'woocommerce_after_shop_loop',				'happyrider_woocommerce_pagination', 10 );

			add_action(    'woocommerce_product_meta_end',				'happyrider_woocommerce_show_product_id', 10);

			add_filter(    'woocommerce_output_related_products_args',	'happyrider_woocommerce_output_related_products_args' );
			
			add_filter(    'woocommerce_product_thumbnails_columns',	'happyrider_woocommerce_product_thumbnails_columns' );

			add_filter(    'loop_shop_columns',							'happyrider_woocommerce_loop_shop_columns' );

			add_filter(    'get_product_search_form',					'happyrider_woocommerce_get_product_search_form' );

			add_filter(    'post_class',								'happyrider_woocommerce_loop_shop_columns_class' );
			add_action(    'the_title',									'happyrider_woocommerce_the_title');
			
			happyrider_enqueue_popup();
		}
	}
}



// Check if WooCommerce installed and activated
if ( !function_exists( 'happyrider_exists_woocommerce' ) ) {
	function happyrider_exists_woocommerce() {
		return class_exists('Woocommerce');
		//return function_exists('is_woocommerce');
	}
}

// Return true, if current page is any woocommerce page
if ( !function_exists( 'happyrider_is_woocommerce_page' ) ) {
	function happyrider_is_woocommerce_page() {
		return function_exists('is_woocommerce') ? is_woocommerce() || is_shop() || is_product_category() || is_product_tag() || is_product() || is_cart() || is_checkout() || is_account_page() : false;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'happyrider_woocommerce_detect_inheritance_key' ) ) {
	//add_filter('happyrider_filter_detect_inheritance_key',	'happyrider_woocommerce_detect_inheritance_key', 9, 1);
	function happyrider_woocommerce_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return happyrider_is_woocommerce_page() ? 'woocommerce' : '';
	}
}

// Filter to detect current template page id
if ( !function_exists( 'happyrider_woocommerce_detect_template_page_id' ) ) {
	//add_filter('happyrider_filter_detect_template_page_id',	'happyrider_woocommerce_detect_template_page_id', 9, 2);
	function happyrider_woocommerce_detect_template_page_id($id, $key) {
		if (!empty($id)) return $id;
		if ($key == 'woocommerce_cart')				$id = get_option('woocommerce_cart_page_id');
		else if ($key == 'woocommerce_checkout')	$id = get_option('woocommerce_checkout_page_id');
		else if ($key == 'woocommerce_account')		$id = get_option('woocommerce_account_page_id');
		else if ($key == 'woocommerce')				$id = get_option('woocommerce_shop_page_id');
		return $id;
	}
}

// Filter to detect current page type (slug)
if ( !function_exists( 'happyrider_woocommerce_get_blog_type' ) ) {
	//add_filter('happyrider_filter_get_blog_type',	'happyrider_woocommerce_get_blog_type', 9, 2);
	function happyrider_woocommerce_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		
		if (is_shop()) 					$page = 'woocommerce_shop';
		else if ($query && $query->get('product_cat')!='' || is_product_category())	$page = 'woocommerce_category';
		else if ($query && $query->get('product_tag')!='' || is_product_tag())		$page = 'woocommerce_tag';
		else if ($query && $query->get('post_type')=='product' || is_product())		$page = 'woocommerce_product';
		else if (is_cart())				$page = 'woocommerce_cart';
		else if (is_checkout())			$page = 'woocommerce_checkout';
		else if (is_account_page())		$page = 'woocommerce_account';
		else if (is_woocommerce())		$page = 'woocommerce';

		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'happyrider_woocommerce_get_blog_title' ) ) {
	//add_filter('happyrider_filter_get_blog_title',	'happyrider_woocommerce_get_blog_title', 9, 2);
	function happyrider_woocommerce_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		
		if ( happyrider_strpos($page, 'woocommerce')!==false ) {
			if ( $page == 'woocommerce_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_cat' ), 'product_cat', OBJECT);
				$title = $term->name;
			} else if ( $page == 'woocommerce_tag' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_tag' ), 'product_tag', OBJECT);
				$title = __('Tag:', 'happyrider') . ' ' . esc_html($term->name);
			} else if ( $page == 'woocommerce_cart' ) {
				$title = __( 'Your cart', 'happyrider' );
			} else if ( $page == 'woocommerce_checkout' ) {
				$title = __( 'Checkout', 'happyrider' );
			} else if ( $page == 'woocommerce_account' ) {
				$title = __( 'Account', 'happyrider' );
			} else if ( $page == 'woocommerce_product' ) {
				$title = happyrider_get_post_title();
			} else if (($page_id=get_option('woocommerce_shop_page_id')) > 0) {
				$title = happyrider_get_post_title($page_id);
			} else {
				$title = __( 'Shop', 'happyrider' );
			}
		}
		
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'happyrider_woocommerce_get_stream_page_title' ) ) {
	//add_filter('happyrider_filter_get_stream_page_title',	'happyrider_woocommerce_get_stream_page_title', 9, 2);
	function happyrider_woocommerce_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (happyrider_strpos($page, 'woocommerce')!==false) {
			if (($page_id = happyrider_woocommerce_get_stream_page_id(0, $page)) > 0)
				$title = happyrider_get_post_title($page_id);
			else
				$title = __('Shop', 'happyrider');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'happyrider_woocommerce_get_stream_page_id' ) ) {
	//add_filter('happyrider_filter_get_stream_page_id',	'happyrider_woocommerce_get_stream_page_id', 9, 2);
	function happyrider_woocommerce_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (happyrider_strpos($page, 'woocommerce')!==false) {
			$id = get_option('woocommerce_shop_page_id');
		}
		return $id;
	}
}

// Filter to detect stream page link
if ( !function_exists( 'happyrider_woocommerce_get_stream_page_link' ) ) {
	//add_filter('happyrider_filter_get_stream_page_link',	'happyrider_woocommerce_get_stream_page_link', 9, 2);
	function happyrider_woocommerce_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (happyrider_strpos($page, 'woocommerce')!==false) {
			$id = happyrider_woocommerce_get_stream_page_id(0, $page);
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'happyrider_woocommerce_get_current_taxonomy' ) ) {
	//add_filter('happyrider_filter_get_current_taxonomy',	'happyrider_woocommerce_get_current_taxonomy', 9, 2);
	function happyrider_woocommerce_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( happyrider_strpos($page, 'woocommerce')!==false ) {
			$tax = 'product_cat';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'happyrider_woocommerce_is_taxonomy' ) ) {
	//add_filter('happyrider_filter_is_taxonomy',	'happyrider_woocommerce_is_taxonomy', 9, 2);
	function happyrider_woocommerce_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('product_cat')!='' || is_product_category() ? 'product_cat' : '';
	}
}

// Add custom post type into list
if ( !function_exists( 'happyrider_woocommerce_list_post_types' ) ) {
	//add_filter('happyrider_filter_list_post_types', 	'happyrider_woocommerce_list_post_types', 10, 1);
	function happyrider_woocommerce_list_post_types($list) {
		$list['product'] = __('Products', 'happyrider');
		return $list;
	}
}


	
// Enqueue WooCommerce custom styles
if ( !function_exists( 'happyrider_woocommerce_frontend_scripts' ) ) {
	//add_action( 'happyrider_action_add_styles', 'happyrider_woocommerce_frontend_scripts' );
	function happyrider_woocommerce_frontend_scripts() {
		if (happyrider_is_woocommerce_page() || happyrider_get_custom_option('show_cart')=='always')
			happyrider_enqueue_style( 'happyrider-woo-style',  happyrider_get_file_url('css/woo-style.css'), array(), null );
	}
}


// Before main content
if ( !function_exists( 'happyrider_woocommerce_wrapper_start' ) ) {
	//remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	//add_action('woocommerce_before_main_content', 'happyrider_woocommerce_wrapper_start', 10);
	function happyrider_woocommerce_wrapper_start() {
		global $HAPPYRIDER_GLOBALS;
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			<article class="post_item post_item_single post_item_product">
			<?php
		} else {
			?>
			<div class="list_products shop_mode_<?php echo !empty($HAPPYRIDER_GLOBALS['shop_mode']) ? $HAPPYRIDER_GLOBALS['shop_mode'] : 'thumbs'; ?>">
			<?php
		}
	}
}

// After main content
if ( !function_exists( 'happyrider_woocommerce_wrapper_end' ) ) {
	//remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);		
	//add_action('woocommerce_after_main_content', 'happyrider_woocommerce_wrapper_end', 10);
	function happyrider_woocommerce_wrapper_end() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			</article>	<!-- .post_item -->
			<?php
		} else {
			?>
			</div>	<!-- .list_products -->
			<?php
		}
	}
}

// Check to show page title
if ( !function_exists( 'happyrider_woocommerce_show_page_title' ) ) {
	//add_action('woocommerce_show_page_title', 'happyrider_woocommerce_show_page_title', 10);
	function happyrider_woocommerce_show_page_title($defa=true) {
		return happyrider_get_custom_option('show_page_title')=='no';
	}
}

// Check to show product title
if ( !function_exists( 'happyrider_woocommerce_show_product_title' ) ) {
	//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);		
	//add_action( 'woocommerce_single_product_summary', 'happyrider_woocommerce_show_product_title', 5 );
	function happyrider_woocommerce_show_product_title() {
		if (happyrider_get_custom_option('show_post_title')=='yes' || happyrider_get_custom_option('show_page_title')=='no') {
			wc_get_template( 'single-product/title.php' );
		}
	}
}

// Add list mode buttons
if ( !function_exists( 'happyrider_woocommerce_before_shop_loop' ) ) {
	//add_action( 'woocommerce_before_shop_loop', 'happyrider_woocommerce_before_shop_loop', 10 );
	function happyrider_woocommerce_before_shop_loop() {
		global $HAPPYRIDER_GLOBALS;
		if (happyrider_get_custom_option('show_mode_buttons')=='yes') {
			echo '<div class="mode_buttons"><form action="' . esc_url(happyrider_get_protocol().'://' . ($_SERVER["HTTP_HOST"]) . ($_SERVER["REQUEST_URI"])).'" method="post">'
				. '<input type="hidden" name="happyrider_shop_mode" value="'.esc_attr($HAPPYRIDER_GLOBALS['shop_mode']).'" />'
				. '<a href="#" class="woocommerce_thumbs icon-th" title="'.esc_attr(__('Show products as thumbs', 'happyrider')).'"></a>'
				. '<a href="#" class="woocommerce_list icon-th-list" title="'.esc_attr(__('Show products as list', 'happyrider')).'"></a>'
				. '</form></div>';
		}
	}
}


// Open thumbs wrapper for categories and products
if ( !function_exists( 'happyrider_woocommerce_open_thumb_wrapper' ) ) {
	//add_action( 'woocommerce_before_subcategory_title', 'happyrider_woocommerce_open_thumb_wrapper', 9 );
	//add_action( 'woocommerce_before_shop_loop_item_title', 'happyrider_woocommerce_open_thumb_wrapper', 9 );
	function happyrider_woocommerce_open_thumb_wrapper($cat='') {
		happyrider_set_global('in_product_item', true);
		?>
		<div class="post_item_wrap">
			<div class="post_featured">
				<div class="post_thumb">
					<a class="hover_icon hover_icon_link" href="<?php echo get_permalink(); ?>">
		<?php
	}
}

// Open item wrapper for categories and products
if ( !function_exists( 'happyrider_woocommerce_open_item_wrapper' ) ) {
	//add_action( 'woocommerce_before_subcategory_title', 'happyrider_woocommerce_open_item_wrapper', 20 );
	//add_action( 'woocommerce_before_shop_loop_item_title', 'happyrider_woocommerce_open_item_wrapper', 20 );
	function happyrider_woocommerce_open_item_wrapper($cat='') {
		?>
				</a>
			</div>
		</div>
		<div class="post_content">
		<?php
	}
}

// Close item wrapper for categories and products
if ( !function_exists( 'happyrider_woocommerce_close_item_wrapper' ) ) {
	//add_action( 'woocommerce_after_subcategory', 'happyrider_woocommerce_close_item_wrapper', 20 );
	//add_action( 'woocommerce_after_shop_loop_item', 'happyrider_woocommerce_close_item_wrapper', 20 );
	function happyrider_woocommerce_close_item_wrapper($cat='') {
		?>
			</div>
		</div>
		<?php
		happyrider_set_global('in_product_item', false);
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'happyrider_woocommerce_after_shop_loop_item_title' ) ) {
	//add_action( 'woocommerce_after_shop_loop_item_title', 'happyrider_woocommerce_after_shop_loop_item_title', 7);
	function happyrider_woocommerce_after_shop_loop_item_title() {
		global $HAPPYRIDER_GLOBALS;
		if ($HAPPYRIDER_GLOBALS['shop_mode'] == 'list') {
		    $excerpt = apply_filters('the_excerpt', get_the_excerpt());
			echo '<div class="description">'.trim($excerpt).'</div>';
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'happyrider_woocommerce_after_subcategory_title' ) ) {
	//add_action( 'woocommerce_after_subcategory_title', 'happyrider_woocommerce_after_subcategory_title', 10 );
	function happyrider_woocommerce_after_subcategory_title($category) {
		global $HAPPYRIDER_GLOBALS;
		if ($HAPPYRIDER_GLOBALS['shop_mode'] == 'list')
			echo '<div class="description">' . trim($category->description) . '</div>';
	}
}

// Add Product ID for single product
if ( !function_exists( 'happyrider_woocommerce_show_product_id' ) ) {
	//add_action( 'woocommerce_product_meta_end', 'happyrider_woocommerce_show_product_id', 10);
	function happyrider_woocommerce_show_product_id() {
		global $post, $product;
		echo '<span class="product_id">'.__('Product ID: ', 'happyrider') . '<span>' . ($post->ID) . '</span></span>';
	}
}

// Redefine number of related products
if ( !function_exists( 'happyrider_woocommerce_output_related_products_args' ) ) {
	//add_filter( 'woocommerce_output_related_products_args', 'happyrider_woocommerce_output_related_products_args' );
	function happyrider_woocommerce_output_related_products_args($args) {
		$ppp = $ccc = 0;
		if (happyrider_param_is_on(happyrider_get_custom_option('show_post_related'))) {
			$ccc_add = in_array(happyrider_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
			$ccc =  happyrider_get_custom_option('post_related_columns');
			$ccc = $ccc > 0 ? $ccc : (happyrider_param_is_off(happyrider_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
			$ppp = happyrider_get_custom_option('post_related_count');
			$ppp = $ppp > 0 ? $ppp : $ccc;
		}
		$args['posts_per_page'] = $ppp;
		$args['columns'] = $ccc;
		return $args;
	}
}

// Number columns for product thumbnails
if ( !function_exists( 'happyrider_woocommerce_product_thumbnails_columns' ) ) {
	//add_filter( 'woocommerce_product_thumbnails_columns', 'happyrider_woocommerce_product_thumbnails_columns' );
	function happyrider_woocommerce_product_thumbnails_columns($cols) {
		return 5;
	}
}

// Add column class into product item in shop streampage
if ( !function_exists( 'happyrider_woocommerce_loop_shop_columns_class' ) ) {
	//add_filter( 'post_class', 'happyrider_woocommerce_loop_shop_columns_class' );
	function happyrider_woocommerce_loop_shop_columns_class($class) {
		if (!is_product() && !is_cart() && !is_checkout() && !is_account_page()) {
			$ccc_add = in_array(happyrider_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
			$ccc =  happyrider_get_custom_option('shop_loop_columns');
			$ccc = $ccc > 0 ? $ccc : (happyrider_param_is_off(happyrider_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
			$class[] = ' column-1_'.esc_attr($ccc);
		}
		return $class;
	}
}

// Number columns for shop streampage
if ( !function_exists( 'happyrider_woocommerce_loop_shop_columns' ) ) {
	//add_filter( 'loop_shop_columns', 'happyrider_woocommerce_loop_shop_columns' );
	function happyrider_woocommerce_loop_shop_columns($cols) {
		$ccc_add = in_array(happyrider_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
		$ccc =  happyrider_get_custom_option('shop_loop_columns');
		$ccc = $ccc > 0 ? $ccc : (happyrider_param_is_off(happyrider_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
		return $ccc;
	}
}

// Search form
if ( !function_exists( 'happyrider_woocommerce_get_product_search_form' ) ) {
	//add_filter( 'get_product_search_form', 'happyrider_woocommerce_get_product_search_form' );
	function happyrider_woocommerce_get_product_search_form($form) {
		return '
		<form role="search" method="get" class="search_form" action="' . esc_url( home_url( '/'  ) ) . '">
			<input type="text" class="search_field" placeholder="' . __('Search for products &hellip;', 'happyrider') . '" value="' . get_search_query() . '" name="s" title="' . __('Search for products:', 'happyrider') . '" /><button class="search_button icon-search" type="submit"></button>
			<input type="hidden" name="post_type" value="product" />
		</form>
		';
	}
}

// Wrap product title into link
if ( !function_exists( 'happyrider_woocommerce_the_title' ) ) {
	//add_filter( 'the_title', 'happyrider_woocommerce_the_title' );
	function happyrider_woocommerce_the_title($title) {
		if (happyrider_get_global('in_product_item') && get_post_type()=='product') {
			$title = '<a href="'.get_permalink().'">'.($title).'</a>';
		}
		return $title;
	}
}

// Show pagination links
if ( !function_exists( 'happyrider_woocommerce_pagination' ) ) {
	//add_filter( 'woocommerce_after_shop_loop', 'happyrider_woocommerce_pagination', 10 );
	function happyrider_woocommerce_pagination() {
		happyrider_show_pagination(array(
			'class' => 'pagination_wrap pagination_' . esc_attr(happyrider_get_theme_option('blog_pagination_style')),
			'style' => happyrider_get_theme_option('blog_pagination_style'),
			'button_class' => '',
			'first_text'=> '',
			'last_text' => '',
			'prev_text' => '',
			'next_text' => '',
			'pages_in_group' => happyrider_get_theme_option('blog_pagination_style')=='pages' ? 10 : 20
			)
		);
	}
}
?>