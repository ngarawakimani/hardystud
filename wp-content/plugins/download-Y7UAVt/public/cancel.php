<?php 
include 'common.php';
/*check if reservation is passed*/
if(!$bookingReservationObj->isPassed($_GET["reservations"])) {
	$calendar_id=$bookingReservationObj->cancelReservations($_GET["reservations"]);
	 ?>
	<div class="booking_text_center booking_width_100p booking_margin_t_100">
        <div style="font-size: 30px;"><?php echo __( 'Well done!', 'wp-booking-calendar' ); ?></div>
        <div style="font-size: 20px; margin-top: 20px;"><?php echo __( 'Your reservation is successfully cancelled.', 'wp-booking-calendar' ); ?></div>
        
        <?php
        if($bookingSettingObj->getCancelRedirect() != '') {
            ?>
            <div id="redirect_link_button" style="font-size: 20px; margin-top: 20px;"><a href="<?php echo $bookingSettingObj->getCancelRedirect(); ?>"><?php echo __( 'Ok, thanks.', 'wp-booking-calendar' ); ?></a></div>
            <?php
        } else {
            ?>
            <div id="redirect_link_button" style="font-size: 20px; margin-top: 20px;"><a href="?p=<?php echo $post->ID; ?>"><?php echo __( 'Ok, thanks.', 'wp-booking-calendar' ); ?></a></div>
            <?php
        }
        ?>
    </div>
 	<?php 
	
	
	/*send reservation email to admin*/
	$to = $bookingSettingObj->getEmailReservation();
	$headers  = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=UTF-8\n";
	$headers .= "X-Priority: 5\n";
	$headers .= "X-MSMail-Priority: Low\n";
	$headers .= "X-Mailer: php\n";
	$headers .= "From: ".$bookingSettingObj->getNameFromReservation()." <".$bookingSettingObj->getEmailFromReservation().">\n" . "Reply-To: ".$bookingSettingObj->getEmailFromReservation()."\n";
	$subject = __( 'A user has cancelled his reservation', 'wp-booking-calendar' );
	$message=__( 'A user has cancelled his reservation. Check it in the admin panel: ', 'wp-booking-calendar' ).'<a href="'.BOOKING_ADMIN_URL.'reservations.php">'.__( 'click here', 'wp-booking-calendar' ).'</a><br />';
	/*get reservations details*/
	$resDetailsArr=$bookingReservationObj->getReservationsDetails($_GET["reservations"]);
	
	foreach($resDetailsArr as $reservationId =>$reservation) {
		$bookingCalendarObj->setCalendar($reservation["calendar_id"]);
		if(in_array("reservation_name",$bookingSettingObj->getVisibleFields())) {
			$message.="<strong>".__( 'Name', 'wp-booking-calendar' )."</strong>: ".$reservation["reservation_name"]."<br>";
		}
		if(in_array("reservation_surname",$bookingSettingObj->getVisibleFields())) {
			$message.="<strong>".__( 'Surname', 'wp-booking-calendar' )."</strong>: ".$reservation["reservation_surname"]."<br>";
		}
		if(in_array("reservation_email",$bookingSettingObj->getVisibleFields())) {
			$message.="<strong>".__( 'Email', 'wp-booking-calendar' )."</strong>: ".$reservation["reservation_email"]."<br>";
		}
		if(in_array("reservation_phone",$bookingSettingObj->getVisibleFields())) {
			$message.="<strong>".__( 'Phone', 'wp-booking-calendar' )."</strong>: ".$reservation["reservation_phone"]."<br>";
		}
		
		if(in_array("reservation_message",$bookingSettingObj->getVisibleFields())) {
			$message.="<strong>".__( 'Message', 'wp-booking-calendar' )."</strong>: ".$reservation["reservation_message"]."<br>";
		}	
		if(in_array("reservation_field1",$bookingSettingObj->getVisibleFields())) {
			$message.="<strong>".__( 'Additional field 1', 'wp-booking-calendar' )."</strong>: ".$reservation["reservation_field1"]."<br>";
		}
		if(in_array("reservation_field2",$bookingSettingObj->getVisibleFields())) {
			$message.="<strong>".__( 'Additional field 2', 'wp-booking-calendar' )."</strong>: ".$reservation["reservation_field2"]."<br>";
		}
		if(in_array("reservation_field3",$bookingSettingObj->getVisibleFields())) {
			$message.="<strong>".__( 'Additional field 3', 'wp-booking-calendar' )."</strong>: ".$reservation["reservation_field3"]."<br>";
		}
		if(in_array("reservation_field4",$bookingSettingObj->getVisibleFields())) {
			$message.="<strong>".__( 'Additional field 4', 'wp-booking-calendar' )."</strong>: ".$reservation["reservation_field4"]."<br>";
		}
		$message.="<strong>".__( 'Calendar', 'wp-booking-calendar' )."</strong>: ".$bookingCalendarObj->getCalendarTitle()."<br>";
		$dateToSend = strftime('%B %d %Y',strtotime($reservation["reservation_date"]));
		if($bookingSettingObj->getDateFormat() == "UK") {
			$dateToSend = strftime('%d/%m/%Y',strtotime($reservation["reservation_date"]));
		} else if($bookingSettingObj->getDateFormat() == "EU") {
			$dateToSend = strftime('%Y/%m/%d',strtotime($reservation["reservation_date"]));
		} else {
			$dateToSend = strftime('%m/%d/%Y',strtotime($reservation["reservation_date"]));
		}
		$message.="<strong>".__( 'Date', 'wp-booking-calendar' )."</strong>: ".$dateToSend."<br>";
		if($bookingSettingObj->getTimeFormat() == "12") {
			$message.="<strong>".__( 'Time', 'wp-booking-calendar' )."</strong>: ".$reservation["reservation_time_from_ampm"]."-".$reservation["reservation_time_to_ampm"]."<br>";
		} else {
			$message.="<strong>".__( 'Time', 'wp-booking-calendar' )."</strong>: ".$reservation["reservation_time_from"]."-".$reservation["reservation_time_to"]."<br>";
		}
		if($bookingSettingObj->getSlotsUnlimited() == 2) {
			$message.="<strong>".__( 'Seats', 'wp-booking-calendar' )."</strong>: ".$reservation["reservation_seats"]."<br>";
		}
		if($bookingSettingObj->getPaypalDisplayPrice() == 1) {
			$price= money_format('%!.2n',$reservation["reservation_price"])."&nbsp;".$bookingSettingObj->getPaypalCurrency();			
			$message.="<strong>".__( 'Price', 'wp-booking-calendar' )."</strong>: ".$price."<br>";
		}
	}
	
	wp_mail($to, $subject, $message, $headers);
	
	
} else {
	?>
    <div class="booking_text_center booking_width_100p booking_margin_t_100">
        <div style="font-size: 30px;"><?php echo __( 'Expired link', 'wp-booking-calendar' ); ?></div>
       
        <?php
        if($bookingSettingObj->getCancelRedirect() != '') {
            ?>
            <div id="redirect_link_button" style="font-size: 20px; margin-top: 20px;"><a href="<?php echo $bookingSettingObj->getCancelRedirect(); ?>"><?php echo __( 'Ok, thanks.', 'wp-booking-calendar' ); ?></a></div>
            <?php
        } else {
            ?>
            <div id="redirect_link_button" style="font-size: 20px; margin-top: 20px;"><a href="?p=<?php echo $post->ID; ?>"><?php echo __( 'Ok, thanks.', 'wp-booking-calendar' ); ?></a></div>
            <?php
        }
        ?>
    </div>
    <?php
	
}
?>
