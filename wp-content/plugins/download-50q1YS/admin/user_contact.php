<?php
include 'common.php';

?>
<script>
	var $wbc = jQuery;
	
	function checkContactData(frm) {
		with(frm) {
			if(Trim(admin_contact_subject.value) == '') {
				alert("<?php echo esc_js(__( 'Insert a subject for the message', 'wp-booking-calendar' )); ?>");
				return false;
			} else if(Trim(admin_contact_message.value) == '') {
				alert("<?php echo esc_js(__( 'Write a message for the administrator', 'wp-booking-calendar' )); ?>");
				return false;
			} else {
				return true;
			}
		}
	}
</script>

    

<div class="booking_padding_20 booking_font_20 booking_line_percent">
	<div><?php echo __( 'Please use this form to contact the administrator for any need about your reservations', 'wp-booking-calendar' ); ?></div>
    <form style="display:inline !important" action="<?php echo BOOKING_ADMIN_URL;?>ajax/sendAdminEmail.php" method="post" target="iframe_submit" onsubmit="return checkContactData(this)">
        <div class="booking_bold booking_margin_t_20 booking_font_14"><?php echo __( 'Subject', 'wp-booking-calendar' ); ?></div>
        <div><input type="text" name="admin_contact_subject" /></div>
        <div class="booking_cleardiv"></div>
        <div class="booking_bold booking_margin_t_20 booking_font_14"><?php echo __( 'Message', 'wp-booking-calendar' ); ?></div>
        <div><textarea name="admin_contact_message"></textarea></div>
        <div class="booking_cleardiv"></div>
        <div class="booking_margin_t_20"><input type="submit" value="<?php echo __( 'Send', 'wp-booking-calendar' ); ?>" class="booking_send_btn" /></div>
    </form>
	
	
               
</div>
<iframe name="iframe_submit" id="iframe_submit" style="border:none;display:none;height:0;width:0"></iframe>