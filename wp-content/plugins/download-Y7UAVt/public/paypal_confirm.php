<?php 
include 'common.php';

unset($_SESSION["reservation_paypal_list"]);
?>
<style type="text/css">
	
#redirect_link_button a {
	background-color: #333;
	display: block;
	padding:10px 20px;
	color: #fff;
	width: 330px;
	height: 30px;
	line-height: 30px;
	margin: 0 auto;
}

#redirect_link_button a:hover {
	background-color: #666;
}

</style>

	


<div class="booking_text_center booking_width_100p booking_margin_t_100">
    <div style="font-size: 30px;"><?php echo __( 'Well done!', 'wp-booking-calendar' ); ?></div>
    <div style="font-size: 20px; margin-top: 20px;"><?php echo __( 'Your reservation is successfully confirmed.', 'wp-booking-calendar' ); ?></div>
    
    <div id="redirect_link_button" style="font-size: 20px; margin-top: 20px;"><a href="?p=<?php echo $post->ID; ?>"><?php echo __( 'Ok, thanks.', 'wp-booking-calendar' ); ?></a></div>
        
</div>
   
	
	