<?php 
include 'common.php';
$reservationsList = urldecode($_POST["custom"]);
$orderResult = 0;


  $req = 'cmd=_notify-validate';

  foreach ($_POST as $key => $value) {
    $value = urlencode(stripslashes($value));
    $req  .= "&$key=$value";
  }

  $header  = "POST /cgi-bin/webscr HTTP/1.1\r\n";
  $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $header .= "Host: www.paypal.com\r\n";
  $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

  $fp = fsockopen('ssl://www.paypal.com', 443, $errno, $errstr, 30);

  fputs($fp, $header . $req);
  
   while (!feof($fp)) {
    $res = fgets($fp, 1024);   
	

    if (strcmp (trim($res), "VERIFIED") == 0) {
		$orderResult = 1;
		if($bookingSettingObj->getPaypal() == 1 && $bookingSettingObj->getReservationAfterPayment() == 1) {
			$reservationsArray = explode(",",$reservationsList);
			$slotsArray = Array();
			for($i=0;$i<count($reservationsArray);$i++) {
				$bookingReservationObj->setReservationByMD5($reservationsArray[$i]);
				$calendar_id = $bookingReservationObj->getReservationCalendarId();
				array_push($slotsArray,$bookingReservationObj->getReservationSlotId());
			}
			/*check if reservations are already unfaked
			send email to administrator to confirm the reservation*/
			$bookingCalendarObj->setCalendar($calendar_id);
			if($bookingCalendarObj->getCalendarEmail() != '') {
				$to = $bookingCalendarObj->getCalendarEmail();
			} else {
				$to = $bookingSettingObj->getEmailReservation();
			}
			
			$headers  = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=UTF-8\n";
			$headers .= "From: ".$bookingSettingObj->getNameFromReservation()." <".$bookingSettingObj->getEmailFromReservation().">\n" . "Reply-To: ".$bookingSettingObj->getEmailFromReservation()."\n";
			$subject = __( 'New reservation', 'wp-booking-calendar' );
			$message=__( 'Reservation data below.', 'wp-booking-calendar' )."<br>";
			
			if(in_array("reservation_name",$bookingSettingObj->getVisibleFields())) {
				$message.="<strong>".__( 'Name', 'wp-booking-calendar' )."</strong>: ".$bookingReservationObj->getReservationName()."<br>";
			}
			if(in_array("reservation_surname",$bookingSettingObj->getVisibleFields())) {
				$message.="<strong>".__( 'Surname', 'wp-booking-calendar' )."</strong>: ".$bookingReservationObj->getReservationSurname()."<br>";
			}
			if(in_array("reservation_email",$bookingSettingObj->getVisibleFields())) {
				$message.="<strong>".__( 'Email', 'wp-booking-calendar' )."</strong>: ".$bookingReservationObj->getReservationEmail()."<br>";
			}
			if(in_array("reservation_phone",$bookingSettingObj->getVisibleFields())) {
				$message.="<strong>".__( 'Phone', 'wp-booking-calendar' )."</strong>: ".$bookingReservationObj->getReservationPhone()."<br>";
			}
			
			if(in_array("reservation_message",$bookingSettingObj->getVisibleFields())) {
				$message.="<strong>".__( 'Message', 'wp-booking-calendar' )."</strong>: ".$bookingReservationObj->getReservationMessage()."<br>";
			}	
			if(in_array("reservation_field1",$bookingSettingObj->getVisibleFields())) {
				$message.="<strong>".__( 'Additional field 1', 'wp-booking-calendar' )."</strong>: ".$bookingReservationObj->getReservationField1()."<br>";
			}
			if(in_array("reservation_field2",$bookingSettingObj->getVisibleFields())) {
				$message.="<strong>".__( 'Additional field 2', 'wp-booking-calendar' )."</strong>: ".$bookingReservationObj->getReservationField2()."<br>";
			}
			if(in_array("reservation_field3",$bookingSettingObj->getVisibleFields())) {
				$message.="<strong>".__( 'Additional field 3', 'wp-booking-calendar' )."</strong>: ".$bookingReservationObj->getReservationField3()."<br>";
			}
			if(in_array("reservation_field4",$bookingSettingObj->getVisibleFields())) {
				$message.="<strong>".__( 'Additional field 4', 'wp-booking-calendar' )."</strong>: ".$bookingReservationObj->getReservationField4()."<br>";
			}
			$message.="<br><strong>".__( 'Slots reserved', 'wp-booking-calendar' )."</strong>:<br>";
			
			$message.="<ul type='disc'>";
			/*loop through slots*/
			
			for($i=0;$i<count($slotsArray);$i++) {
				$bookingSlotsObj->setSlot($slotsArray[$i]);
				$bookingCalendarObj->setCalendar($calendar_id);
				$bookingReservationObj->setReservationByMD5($reservationsArray[$i]);
				
				$message.="<li>";
				$message.="<strong>".__( 'Calendar', 'wp-booking-calendar' )."</strong>: ".$bookingCalendarObj->getCalendarTitle()."<br>";
				$dateToSend = strftime('%B %d %Y',strtotime($bookingSlotsObj->getSlotDate()));
				if($bookingSettingObj->getDateFormat() == "UK") {
					$dateToSend = strftime('%d/%m/%Y',strtotime($bookingSlotsObj->getSlotDate()));
				} else if($bookingSettingObj->getDateFormat() == "EU") {
					$dateToSend = strftime('%Y/%m/%d',strtotime($bookingSlotsObj->getSlotDate()));
				} else {
					$dateToSend = strftime('%m/%d/%Y',strtotime($bookingSlotsObj->getSlotDate()));
				}
				$message.="<strong>".__( 'Date', 'wp-booking-calendar' )."</strong>: ".$dateToSend."<br>";
				if($bookingSettingObj->getTimeFormat() == "12") {
					$message.="<strong>".__( 'Time', 'wp-booking-calendar' )."</strong>: ".$bookingSlotsObj->getSlotTimeFromAMPM()."-".$bookingSlotsObj->getSlotTimeToAMPM()."<br>";
				} else {
					$message.="<strong>".__( 'Time', 'wp-booking-calendar' )."</strong>: ".$bookingSlotsObj->getSlotTimeFrom()."-".$bookingSlotsObj->getSlotTimeTo()."<br>";
				}
				if($bookingSettingObj->getSlotsUnlimited() == 2) {
					$message.="<strong>".__( 'Seats', 'wp-booking-calendar' )."</strong>: ".$bookingReservationObj->getReservationSeats()."<br>";
				}
				if($bookingSettingObj->getPaypalDisplayPrice() == 1) {
					$price= money_format('%!.2n',$bookingSlotsObj->getSlotPrice())."&nbsp;".$bookingSettingObj->getPaypalCurrency();			
					$message.="<strong>".__( 'Price', 'wp-booking-calendar' )."</strong>: ".$price."<br>";
				}
				$message.="</li>";
				
			}
			$message.="</ul>";
			if($bookingSettingObj->getReservationConfirmationMode() == 3) {
				
				$message.=__( 'All reservations must be confirmed in ', 'wp-booking-calendar' ).'<a href="'.site_url('wp-admin/admin.php?page=wp-booking-calendar-reservations').'">'.__( 'admin panel', 'wp-booking-calendar' ).'</a>';
			}

			wp_mail($to, $subject,$message, $headers );
			
			
			/*send reservation email to user*/
			$to = $bookingReservationObj->getReservationEmail();
			$headers  = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=UTF-8\n";
			$headers .= "From: ".$bookingSettingObj->getNameFromReservation()." <".$bookingSettingObj->getEmailFromReservation().">\n" . "Reply-To: ".$bookingSettingObj->getEmailFromReservation()."\n";
			/*WARNING!! static mail record ids, if deleted/changed, must be changed here also*/
			switch($bookingSettingObj->getReservationConfirmationMode()) {
				case "1":
					$bookingMailObj->setMail(1);
					break;
				case "2":
					$bookingMailObj->setMail(2);
					break;
				case "3":
					$bookingMailObj->setMail(3);
					break;
			}
			if($bookingSettingObj->getPaypal()==1 && $bookingSettingObj->getPaypalAccount() != '' && $bookingSettingObj->getPaypalLocale() != '' && $bookingSettingObj->getPaypalCurrency() != '' && $bookingSettingObj->getReservationConfirmationModeOverride() == 1) {
				$bookingMailObj->setMail(1);
			}
			$subject = $bookingMailObj->getMailSubject();
			/*setting username in message*/
			$message=str_replace("[customer-name]",$bookingReservationObj->getReservationName(),$bookingMailObj->getMailText());
			/*check if cancellation is enabled id email is 1*/
			if($bookingMailObj->getMailId() == 1 && $bookingSettingObj->getReservationCancel() == "1") {
				$message.=$bookingMailObj->getMailCancelText();
			}
			/*setting reservation detail in message
			loop through slots*/
			$res_details = "";
			for($i=0;$i<count($slotsArray);$i++) {
				$bookingSlotsObj->setSlot($slotsArray[$i]);
				
				$res_details.="<strong>".__( 'Venue', 'wp-booking-calendar' )."</strong>: ".$bookingCalendarObj->getCalendarTitle()."<br>";
				$dateToSend = strftime('%B %d %Y',strtotime($bookingSlotsObj->getSlotDate()));
				if($bookingSettingObj->getDateFormat() == "UK") {
					$dateToSend = strftime('%d/%m/%Y',strtotime($bookingSlotsObj->getSlotDate()));
				} else if($bookingSettingObj->getDateFormat() == "EU") {
					$dateToSend = strftime('%Y/%m/%d',strtotime($bookingSlotsObj->getSlotDate()));
				} else {
					$dateToSend = strftime('%m/%d/%Y',strtotime($bookingSlotsObj->getSlotDate()));
				}
				$res_details.="<strong>".__( 'Date', 'wp-booking-calendar' )."</strong>: ".$dateToSend."<br>";
				if($bookingSlotsObj->getSlotSpecialMode() == 1) {
					if($bookingSettingObj->getTimeFormat() == "12") {
						$res_details.="<strong>".__( 'Time', 'wp-booking-calendar' )."</strong>: ".$bookingSlotsObj->getSlotTimeFromAMPM()."-".$bookingSlotsObj->getSlotTimeToAMPM();
					} else {
						$res_details.="<strong>".__( 'Time', 'wp-booking-calendar' )."</strong>: ".$bookingSlotsObj->getSlotTimeFrom()."-".$bookingSlotsObj->getSlotTimeTo();
					}
					if($bookingSlotsObj->getSlotSpecialText()!='') {
						$res_details.=" - ".$bookingSlotsObj->getSlotSpecialText();
					}
					$res_details.="<br>";
				} else if($bookingSlotsObj->getSlotSpecialMode() == 0 && $bookingSlotsObj->getSlotSpecialText() != '') {
					$res_details.="<strong>".__( 'Time', 'wp-booking-calendar' )."</strong>:".$bookingSlotsObj->getSlotSpecialText()."<br>";
				} else {
					if($bookingSettingObj->getTimeFormat() == "12") {
						$res_details.="<strong>".__( 'Time', 'wp-booking-calendar' )."</strong>: ".$bookingSlotsObj->getSlotTimeFromAMPM()."-".$bookingSlotsObj->getSlotTimeToAMPM()."<br>";
					} else {
						$res_details.="<strong>".__( 'Time', 'wp-booking-calendar' )."</strong>: ".$bookingSlotsObj->getSlotTimeFrom()."-".$bookingSlotsObj->getSlotTimeTo()."<br>";
					}
				}
				if($bookingSettingObj->getSlotsUnlimited() == 2) {
					$res_details.="<strong>".__( 'Seats', 'wp-booking-calendar' )."</strong>: ".$bookingReservationObj->getReservationSeats()."<br>";
				}
				if($bookingSettingObj->getPaypalDisplayPrice() == 1) {
					$price= money_format('%!.2n',$bookingSlotsObj->getSlotPrice())."&nbsp;".$bookingSettingObj->getPaypalCurrency();			
					$res_details.="<strong>".__( 'Price', 'wp-booking-calendar' )."</strong>: ".$price."<br>";
				}
				$res_details.="<br><br>";
				
			}
			$message=str_replace("[reservation-details]",$res_details,$message);	
			
			
			if($bookingMailObj->getMailId() == 2) {
				/*setting reservation confirmation link in message
				if he must confirm it via mail, I send the link*/
				$message=str_replace("[confirmation-link]","<a href='".site_url('')."/?p=".$bookingReservationObj->getReservationPostId()."&confirm=1&reservations=".$listReservations."'>".__( 'Click here to confirm your reservation', 'wp-booking-calendar' )."</a>",$message);
				$message=str_replace("[confirmation-link-url]",site_url('')."/?p=".$bookingReservationObj->getReservationPostId()."&confirm=1&reservations=".$listReservations,$message);
			}
			
			if($bookingMailObj->getMailId() == 1 && $bookingSettingObj->getReservationCancel() == "1") {
				$message=str_replace("[cancellation-link]","<a href='".site_url('')."/?p=".$bookingReservationObj->getReservationPostId()."&cancel=1&reservations=".$listReservations."'>".__( 'Click here to cancel your reservation', 'wp-booking-calendar' )."</a>",$message);
				$message=str_replace("[cancellation-link-url]",site_url('')."/?p=".$bookingReservationObj->getReservationPostId()."&cancel=1&reservations=".$listReservations,$message);
			}
			$message.="<br><br>".$bookingMailObj->getMailSignature();

			wp_mail($to, $subject,$message, $headers );
		
			$bookingReservationObj->unfakeReservations($reservationsList);
		}
		if($bookingSettingObj->getReservationConfirmationModeOverride() == 1) {
			$bookingReservationObj->confirmReservations($reservationsList);
		}

    } 
    else if (strcmp (trim($res), "INVALID") == 0) { 
		$orderResult = 0;
		$bookingReservationObj->deleteReservations($reservationsList);

    }
  }
 

  
   fclose($fp);



?>