<?php
include 'common.php';

?>

<div class="booking_padding_20 booking_font_20 booking_line_percent booking_bg_fff">
	
    <!-- logo -->
	<div><img src="<?php echo BOOKING_ADMIN_URL;?>images/logo_admin.gif"  /></div>
	<!-- welcome text -->
	<div class="booking_padding_20"><p><?php echo __( 'WELCOME TO BOOKING CALENDAR CONTROL PANEL', 'wp-booking-calendar' ); ?><br /><?php echo __( 'Use the menu on the left to manage all configurations and contents', 'wp-booking-calendar' ); ?></p></div>
	<!-- warning text -->
	<?php
	if(($bookingSettingObj->getReservationConfirmationMode() == 0 && $bookingSettingObj->getTimezone() == '') || count($bookingListObj->getCalendarsList()) == 0 ) {
		?>
		<div class="booking_padding_20 booking_bg_f00 booking_mark_fff">
        	<p>
				<?php echo __( 'Hey Admin,', 'wp-booking-calendar' ); ?><br />
                <?php echo __( 'it seems like you did not adjust the settings and  created a calendar yet.', 'wp-booking-calendar' ); ?><br />
                <?php echo __( 'Remember, ', 'wp-booking-calendar' ); ?><strong><?php echo __( 'if you skip these two steps, the Booking Calendar cannot work.', 'wp-booking-calendar' ); ?></strong><br />
                <?php echo __( 'Let\'s go to start now!', 'wp-booking-calendar' ); ?>
            </p>
        </div>
		<?php
	}
	?>
               
</div>