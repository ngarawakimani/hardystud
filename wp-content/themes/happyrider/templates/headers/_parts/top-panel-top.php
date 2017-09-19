<?php 
if (in_array('contact_info', $top_panel_top_components) && ($contact_info=trim(happyrider_get_custom_option('contact_info')))!='') {
	?>
	<div class="top_panel_top_contact_area">
		<?php echo force_balance_tags($contact_info); ?>
	</div>
	<div class="top_panel_top_phone_area">
	<?php
		$contact_phone = trim(happyrider_get_custom_option('contact_phone'));
        echo force_balance_tags($contact_phone); ?>
	</div>
	
	<?php
}
?>

<?php
if (in_array('open_hours', $top_panel_top_components) && ($open_hours=trim(happyrider_get_custom_option('contact_open_hours')))!='') {
	?>
	<div class="top_panel_top_open_hours icon-clock"><?php echo force_balance_tags($open_hours); ?></div>
	<?php
}
?>

<div class="top_panel_top_user_area">
	<?php
	if (in_array('socials', $top_panel_top_components) && happyrider_get_custom_option('show_socials')=='yes') {
		?>
		<div class="top_panel_top_socials">
			<?php echo happyrider_sc_socials(array('size'=>'tiny')); ?>
		</div>
		<?php
	}

	if (in_array('search', $top_panel_top_components) && happyrider_get_custom_option('show_search')=='yes') {
		?>
		<div class="top_panel_top_search"><?php echo happyrider_sc_search(array('state'=>'fixed')); ?></div>
		<?php
	}

	global $HAPPYRIDER_GLOBALS;
	if (empty($HAPPYRIDER_GLOBALS['menu_user']))
		$HAPPYRIDER_GLOBALS['menu_user'] = happyrider_get_nav_menu('menu_user');
	if (empty($HAPPYRIDER_GLOBALS['menu_user'])) {
		?>
		<ul id="menu_user" class="menu_user_nav">
		<?php
	} else {
		$menu = happyrider_substr($HAPPYRIDER_GLOBALS['menu_user'], 0, happyrider_strlen($HAPPYRIDER_GLOBALS['menu_user'])-5);
		$pos = happyrider_strpos($menu, '<ul');
		if ($pos!==false) $menu = happyrider_substr($menu, 0, $pos+3) . ' class="menu_user_nav"' . happyrider_substr($menu, $pos+3);
		echo str_replace('class=""', '', $menu);
	}
	

	if (in_array('currency', $top_panel_top_components) && happyrider_is_woocommerce_page() && happyrider_get_custom_option('show_currency')=='yes') {
		?>
		<li class="menu_user_currency">
			<a href="#">$</a>
			<ul>
				<li><a href="#"><b>&#36;</b> <?php _e('Dollar', 'happyrider'); ?></a></li>
				<li><a href="#"><b>&euro;</b> <?php _e('Euro', 'happyrider'); ?></a></li>
				<li><a href="#"><b>&pound;</b> <?php _e('Pounds', 'happyrider'); ?></a></li>
			</ul>
		</li>
		<?php
	}

	if (in_array('language', $top_panel_top_components) && happyrider_get_custom_option('show_languages')=='yes' && function_exists('icl_get_languages')) {
		$languages = icl_get_languages('skip_missing=1');
		if (!empty($languages) && is_array($languages)) {
			$lang_list = '';
			$lang_active = '';
			foreach ($languages as $lang) {
				$lang_title = esc_attr($lang['translated_name']);	//esc_attr($lang['native_name']);
				if ($lang['active']) {
					$lang_active = $lang_title;
				}
				$lang_list .= "\n"
					.'<li><a rel="alternate" hreflang="' . esc_attr($lang['language_code']) . '" href="' . esc_url(apply_filters('WPML_filter_link', $lang['url'], $lang)) . '">'
						.'<img src="' . esc_url($lang['country_flag_url']) . '" alt="' . esc_attr($lang_title) . '" title="' . esc_attr($lang_title) . '" />'
						. ($lang_title)
					.'</a></li>';
			}
			?>
			<li class="menu_user_language">
				<a href="#"><span><?php echo ($lang_active); ?></span></a>
				<ul><?php echo ($lang_list); ?></ul>
			</li>
			<?php
		}
	}

	if (in_array('bookmarks', $top_panel_top_components) && happyrider_get_custom_option('show_bookmarks')=='yes') {
		// Load core messages
		happyrider_enqueue_messages();
		?>
		<li class="menu_user_bookmarks"><a href="#" class="bookmarks_show icon-star" title="<?php _e('Show bookmarks', 'happyrider'); ?>"><?php _e('Bookmarks', 'happyrider'); ?></a>
		<?php 
			$list = happyrider_get_value_gpc('happyrider_bookmarks', '');
			if (!empty($list)) $list = json_decode($list, true);
			?>
			<ul class="bookmarks_list">
				<li><a href="#" class="bookmarks_add icon-star-empty" title="<?php _e('Add the current page into bookmarks', 'happyrider'); ?>"><?php _e('Add bookmark', 'happyrider'); ?></a></li>
				<?php 
				if (!empty($list) && is_array($list)) {
					foreach ($list as $bm) {
						echo '<li><a href="'.esc_url($bm['url']).'" class="bookmarks_item">'.($bm['title']).'<span class="bookmarks_delete icon-cancel" title="'.__('Delete this bookmark', 'happyrider').'"></span></a></li>';
					}
				}
				?>
			</ul>
		</li>
		<?php 
	}

	if (in_array('login', $top_panel_top_components) && happyrider_get_custom_option('show_login')=='yes') {
		if ( !is_user_logged_in() ) {
			// Load core messages
			happyrider_enqueue_messages();
			// Load Popup engine
			happyrider_enqueue_popup();
			// Anyone can register ?
			if ( (int) get_option('users_can_register') > 0) {
			?>
			<li class="menu_user_register"><a href="#popup_registration" class="popup_link popup_register_link icon-pencil"><?php _e('Register', 'happyrider'); ?></a><?php
				if (happyrider_get_theme_option('show_login')=='yes') {
					require_once( happyrider_get_file_dir('templates/headers/_parts/register.php') );
				}?></li>
			<?php } ?>
			<li class="menu_user_login"><a href="#popup_login" class="popup_link popup_login_link icon-user"><?php _e('Login', 'happyrider'); ?></a><?php
				if (happyrider_get_theme_option('show_login')=='yes') {
					require_once( happyrider_get_file_dir('templates/headers/_parts/login.php') );
				}?></li>
			<?php 
		} else {
			$current_user = wp_get_current_user();
			?>
			<li class="menu_user_controls">
				<a href="#"><?php
					$user_avatar = '';
					if ($current_user->user_email) $user_avatar = get_avatar($current_user->user_email, 16*min(2, max(1, happyrider_get_theme_option("retina_ready"))));
					if ($user_avatar) {
						?><span class="user_avatar"><?php echo ($user_avatar); ?></span><?php
					}?><span class="user_name"><?php echo ($current_user->display_name); ?></span></a>
				<ul>
					<?php if (current_user_can('publish_posts')) { ?>
					<li><a href="<?php echo esc_url(home_url('/')); ?>/wp-admin/post-new.php?post_type=post" class="icon icon-doc"><?php _e('New post', 'happyrider'); ?></a></li>
					<?php } ?>
					<li><a href="<?php echo get_edit_user_link(); ?>" class="icon icon-cog"><?php _e('Settings', 'happyrider'); ?></a></li>
				</ul>
			</li>
			<li class="menu_user_logout"><a href="<?php echo wp_logout_url(esc_url(home_url('/'))); ?>" class="icon icon-logout"><?php _e('Logout', 'happyrider'); ?></a></li>
			<?php 
		}
	}

	if (in_array('cart', $top_panel_top_components) && happyrider_exists_woocommerce() && (happyrider_is_woocommerce_page() && happyrider_get_custom_option('show_cart')=='shop' || happyrider_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) {
		?>
		<li class="menu_user_cart">
			<?php require_once( happyrider_get_file_dir('templates/headers/_parts/contact-info-cart.php') ); ?>
		</li>
		<?php
	}
	?>

	</ul>

</div>