<a href="#" class="top_panel_cart_button">
	<span class="contact_icon icon-basket"></span>
	<span class="contact_label contact_cart_label"><?php _e('Your cart:', 'happyrider'); ?></span>
	<span class="contact_cart_totals">
		<span class="cart_items"><?php
			$items = WC()->cart->get_cart_contents_count();
			echo esc_html($items) . ' ' . ($items == 1 ? __('Item', 'happyrider') : __('Items', 'happyrider')); ?>
		</span>
		- 
		<span class="cart_summa"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
	</span>
</a>
<ul class="widget_area sidebar_cart sidebar"><li>
	<?php
	do_action( 'before_sidebar' );
	$HAPPYRIDER_GLOBALS['current_sidebar'] = 'cart';
	if ( !dynamic_sidebar( 'sidebar-cart' ) ) { 
		the_widget( 'WC_Widget_Cart', 'title=&hide_if_empty=1' );
	}
	?>
</li></ul>