<?php
include '../common.php';
global $sitepress;
if(isset($sitepress)) {
    $sitepress->switch_lang($_POST['wpml_lang'], true);
    load_plugin_textdomain( 'wp-booking-calendar' );
    load_plugin_textdomain( 'wp-booking-calendar-slots' );
    load_plugin_textdomain( 'wp-booking-calendar-calendars' );
    load_plugin_textdomain( 'wp-booking-calendar-categories' );
    load_plugin_textdomain( 'wp-booking-calendar-mails' );
}

$confirm=0;
$bookingCalendarObj->setCalendar($_POST["calendar_id"]);
/*check if the reservation must be added before or after the payment confirmation*/
$fake = 0;
if(isset($_POST["with_paypal"]) && $bookingSettingObj->getPaypal() == 1 && $bookingSettingObj->getReservationAfterPayment() == 1) {
	$fake = 1;
}
if($bookingSettingObj->getRecaptchaEnabled() == "1") {
    $privatekey = $bookingSettingObj->getRecaptchaPrivateKey();
    if($bookingSettingObj->getRecaptchaVersion() == 1) {
        require_once('../include/recaptchalib.php');

        $resp = recaptcha_check_answer($privatekey,
            $_SERVER["REMOTE_ADDR"],
            $_POST["recaptcha_challenge_field"],
            $_POST["recaptcha_response_field"]);
        if ($resp->is_valid) {
            $resp->success = true;
        }
    } else {
        $response = $_POST["g-recaptcha-response"];
        $resp = $bookingReservationObj->checkRecaptchaResponse($response,$privatekey);
    }
	
	if (!$resp->success) {

		?>
		<script>
			window.parent.showCaptchaError();
		</script>
		<?php
	} else {

		$listReservations=$bookingReservationObj->insertReservation($bookingSettingObj,$fake);
		if($listReservations != '') {
			if($bookingSettingObj->getReservationConfirmationMode() == 1 && !isset($_POST["with_paypal"])) {
				$bookingReservationObj->confirmReservations($listReservations);
			}
			$confirm = 1;
		} else {
			$confirm = 0;
		}
	}
} else {
	$listReservations=$bookingReservationObj->insertReservation($bookingSettingObj,$fake);
	if($listReservations != '') {
		if($bookingSettingObj->getReservationConfirmationMode() == 1 && !isset($_POST["with_paypal"])) {
			$bookingReservationObj->confirmReservations($listReservations);
		}
		$confirm = 1;
	} else {
		$confirm = 0;
	}
}
if($confirm == 1) {
	
	if(isset($_POST["with_paypal"])) {
		/*set session variables if it's from paypal*/
		$_SESSION["reservation_paypal_list"] = $listReservations;
	}
	/*send reservation email to admin
	check if the email must be sent before or after the payment confirmation
	check first if the current calendar has a custom email address*/
	
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
		$message.="<strong>".__( 'Name', 'wp-booking-calendar' )."</strong>: ".$_POST["reservation_name"]."<br>";
	}
	if(in_array("reservation_surname",$bookingSettingObj->getVisibleFields())) {
		$message.="<strong>".__( 'Surname', 'wp-booking-calendar' )."</strong>: ".$_POST["reservation_surname"]."<br>";
	}
	if(in_array("reservation_email",$bookingSettingObj->getVisibleFields())) {
		$message.="<strong>".__( 'Email', 'wp-booking-calendar' )."</strong>: ".$_POST["reservation_email"]."<br>";
	}
	if(in_array("reservation_phone",$bookingSettingObj->getVisibleFields())) {
		$message.="<strong>".__( 'Phone', 'wp-booking-calendar' )."</strong>: ".$_POST["reservation_phone"]."<br>";
	}
	
	if(in_array("reservation_message",$bookingSettingObj->getVisibleFields())) {
		$message.="<strong>".__( 'Message', 'wp-booking-calendar' )."</strong>: ".$_POST["reservation_message"]."<br>";
	}	
	if(in_array("reservation_field1",$bookingSettingObj->getVisibleFields())) {
		$message.="<strong>".__( 'Additional field 1', 'wp-booking-calendar' )."</strong>: ".$_POST["reservation_field1"]."<br>";
	}
	if(in_array("reservation_field2",$bookingSettingObj->getVisibleFields())) {
		$message.="<strong>".__( 'Additional field 2', 'wp-booking-calendar' )."</strong>: ".$_POST["reservation_field2"]."<br>";
	}
	if(in_array("reservation_field3",$bookingSettingObj->getVisibleFields())) {
		$message.="<strong>".__( 'Additional field 3', 'wp-booking-calendar' )."</strong>: ".$_POST["reservation_field3"]."<br>";
	}
	if(in_array("reservation_field4",$bookingSettingObj->getVisibleFields())) {
		$message.="<strong>".__( 'Additional field 4', 'wp-booking-calendar' )."</strong>: ".$_POST["reservation_field4"]."<br>";
	}
	$message.="<br><strong>".__( 'Slots reserved', 'wp-booking-calendar' )."</strong>:<br>";
	$message.="<ul type='disc'>";
	/*loop through slots*/
	$paypalHtml = "";
    $paypalAmount = 0;
	for($i=0;$i<count($_POST["reservation_slot"]);$i++) {
		$bookingSlotsObj->setSlot($_POST["reservation_slot"][$i]);
		$bookingCalendarObj->setCalendar($_POST["calendar_id"]);
		/*PAYPAL*/
		if($bookingSlotsObj->getSlotSpecialMode() == 1) {
			if($bookingSettingObj->getTimeFormat() == "12") {
				$time= date('h:i a',strtotime(substr($bookingSlotsObj->getSlotTimeFrom(),0,5)))." - ".date('h:i a',strtotime(substr($bookingSlotsObj->getSlotTimeTo(),0,5)));
			} else {
				$time= substr($bookingSlotsObj->getSlotTimeFrom(),0,5)." - ".substr($bookingSlotsObj->getSlotTimeTo(),0,5);
			}
			if($bookingSlotsObj->getSlotSpecialText() != '') {
				$time.= " - ".$bookingSlotsObj->getSlotSpecialText(); 
			}
		} else if($bookingSlotsObj->getSlotSpecialMode() == 0 && $bookingSlotsObj->getSlotSpecialText() != '') {
			$time= $bookingSlotsObj->getSlotSpecialText(); 
		} else {
			if($bookingSettingObj->getTimeFormat() == "12") {
				echo date('h:i a',strtotime(substr($bookingSlotsObj->getSlotTimeFrom(),0,5)))." - ".date('h:i a',strtotime(substr($bookingSlotsObj->getSlotTimeTo(),0,5)));
			} else {
				echo substr($bookingSlotsObj->getSlotTimeFrom(),0,5)." - ".substr($bookingSlotsObj->getSlotTimeTo(),0,5);
			}
		}
		if($bookingSettingObj->getDateFormat() == "UK") {
			$dateToSend = strftime('%d/%m/%Y',strtotime($bookingSlotsObj->getSlotDate()));
		} else if($bookingSettingObj->getDateFormat() == "EU") {
			$dateToSend = strftime('%Y/%m/%d',strtotime($bookingSlotsObj->getSlotDate()));
		} else {
			$dateToSend = strftime('%m/%d/%Y',strtotime($bookingSlotsObj->getSlotDate()));
		}
		$info_slot = $dateToSend." ".$time;
		$seats = 1;
		if($bookingSettingObj->getSlotsUnlimited() == 2) {
			$seats=$_POST["reservation_seats_".$_POST["reservation_slot"][$i]];
		}
        $slotPrice = 0;
        if($bookingSlotsObj->getSlotDiscountPrice()>0) {
            $slotPrice = $bookingSlotsObj->getSlotDiscountPrice();
        } else if($bookingSlotsObj->getSlotPercPrice()>0) {
            $slotPrice = $bookingSlotsObj->getSlotPrice()/100*$bookingSlotsObj->getSlotPercPrice();
        } else {
            $slotPrice = $bookingSlotsObj->getSlotPrice();
        }

		$paypalHtml.=trim('<input type="hidden" name="item_name_'.($i+1).'" value="'.$info_slot.'" /><input type="hidden" name="amount_'.($i+1).'" value="'.$slotPrice.'" /><input type="hidden" name="quantity_'.($i+1).'" value="'.$seats.'" />');

        $paypalAmount+=($slotPrice*$seats);
		/*END PAYPAL*/
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
			$message.="<strong>".__( 'Seats', 'wp-booking-calendar' )."</strong>: ".$_POST["reservation_seats_".$_POST["reservation_slot"][$i]]."<br>";
		}
		if($bookingSettingObj->getPaypalDisplayPrice() == 1) {
            if($bookingSlotsObj->getSlotDiscountPrice()>0 || $bookingSlotsObj->getSlotPercPrice()>0) {
                switch($bookingSlotsObj->getSlotShowPrice()) {
                    case 0:
                        $price = money_format('%!.2n',$bookingSlotsObj->getSlotPrice())."&nbsp;".$bookingSettingObj->getPaypalCurrency();
                        break;
                    case 1:
                        if($bookingSlotsObj->getSlotDiscountPrice()>0) {
                            $price = money_format('%!.2n',$bookingSlotsObj->getSlotDiscountPrice())."&nbsp;".$bookingSettingObj->getPaypalCurrency();
                        } else if($bookingSlotsObj->getSlotPercPrice()>0) {
                            $price = money_format('%!.2n',($bookingSlotsObj->getSlotPrice()/100*$bookingSlotsObj->getSlotPercPrice()))."&nbsp;".$bookingSettingObj->getPaypalCurrency();
                        }
                        break;
                    case 2:
                        $price =  '<span style="text-decoration: line-through; color: #900;">'.money_format('%!.2n',$bookingSlotsObj->getSlotPrice())."&nbsp;".$bookingSettingObj->getPaypalCurrency().'</span><span>&nbsp;';
                        if($bookingSlotsObj->getSlotDiscountPrice()>0) {
                            $price.= money_format('%!.2n',$bookingSlotsObj->getSlotDiscountPrice())."&nbsp;".$bookingSettingObj->getPaypalCurrency();
                        } else if($bookingSlotsObj->getSlotPercPrice()>0) {
                            $price.= money_format('%!.2n',($bookingSlotsObj->getSlotPrice()/100*$bookingSlotsObj->getSlotPercPrice()))."&nbsp;".$bookingSettingObj->getPaypalCurrency();
                        }
                        $price.= '</span>';
                        break;
                }
            } else {
                $price= money_format('%!.2n',$bookingSlotsObj->getSlotPrice())."&nbsp;".$bookingSettingObj->getPaypalCurrency();
            }

			$message.="<strong>".__( 'Price', 'wp-booking-calendar' )."</strong>: ".$price."<br>";
		}
		$message.="</li>";
	}
	$message.="</ul>";
	if($bookingSettingObj->getReservationConfirmationMode() == 3) {
		
		$message.=__( 'All reservations must be confirmed in ', 'wp-booking-calendar' ).'<a href="'.site_url('wp-admin/admin.php?page=wp-booking-calendar-reservations').'">'.__( 'admin panel', 'wp-booking-calendar' ).'</a>';
	}
	if(($bookingSettingObj->getPaypal() == 0 && !isset($_POST["with_paypal"])) || ($bookingSettingObj->getPaypal() == 1 && $bookingSettingObj->getReservationAfterPayment() == 0)) {

		wp_mail($to, $subject,$message, $headers );
	}
		
	if(in_array("reservation_email",$bookingSettingObj->getVisibleFields())) {
		/*send reservation email to user*/
		$to = $_POST["reservation_email"];
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
		$message=str_replace("[customer-name]",$_POST["reservation_name"],$bookingMailObj->getMailText());
		/*check if cancellation is enabled id email is 1*/
		if($bookingMailObj->getMailId() == 1 && $bookingSettingObj->getReservationCancel() == "1") {
			$message.=$bookingMailObj->getMailCancelText();
		}
		/*setting reservation detail in message
		loop through slots*/
		$res_details = "";
		for($i=0;$i<count($_POST["reservation_slot"]);$i++) {
			$bookingSlotsObj->setSlot($_POST["reservation_slot"][$i]);
			$bookingCalendarObj->setCalendar($_POST["calendar_id"]);	
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
				$res_details.="<strong>".__( 'Seats', 'wp-booking-calendar' )."</strong>: ".$_POST["reservation_seats_".$_POST["reservation_slot"][$i]]."<br>";
			}
            if($bookingSettingObj->getPaypalDisplayPrice() == 1) {
                if($bookingSlotsObj->getSlotDiscountPrice()>0 || $bookingSlotsObj->getSlotPercPrice()>0) {
                    switch($bookingSlotsObj->getSlotShowPrice()) {
                        case 0:
                            $price = money_format('%!.2n',$bookingSlotsObj->getSlotPrice())."&nbsp;".$bookingSettingObj->getPaypalCurrency();
                            break;
                        case 1:
                            if($bookingSlotsObj->getSlotDiscountPrice()>0) {
                                $price = money_format('%!.2n',$bookingSlotsObj->getSlotDiscountPrice())."&nbsp;".$bookingSettingObj->getPaypalCurrency();
                            } else if($bookingSlotsObj->getSlotPercPrice()>0) {
                                $price = money_format('%!.2n',($bookingSlotsObj->getSlotPrice()/100*$bookingSlotsObj->getSlotPercPrice()))."&nbsp;".$bookingSettingObj->getPaypalCurrency();
                            }
                            break;
                        case 2:
                            $price =  '<span style="text-decoration: line-through; color: #900;">'.money_format('%!.2n',$bookingSlotsObj->getSlotPrice())."&nbsp;".$bookingSettingObj->getPaypalCurrency().'</span><span>&nbsp;';
                            if($bookingSlotsObj->getSlotDiscountPrice()>0) {
                                $price.= money_format('%!.2n',$bookingSlotsObj->getSlotDiscountPrice())."&nbsp;".$bookingSettingObj->getPaypalCurrency();
                            } else if($bookingSlotsObj->getSlotPercPrice()>0) {
                                $price.= money_format('%!.2n',($bookingSlotsObj->getSlotPrice()/100*$bookingSlotsObj->getSlotPercPrice()))."&nbsp;".$bookingSettingObj->getPaypalCurrency();
                            }
                            $price.= '</span>';
                            break;
                    }
                } else {
                    $price= money_format('%!.2n',$bookingSlotsObj->getSlotPrice())."&nbsp;".$bookingSettingObj->getPaypalCurrency();
                }

                $res_details.="<strong>".__( 'Price', 'wp-booking-calendar' )."</strong>: ".$price."<br>";
            }
			$res_details.="<br><br>";
		}
		$message=str_replace("[reservation-details]",$res_details,$message);	
		
		
		if($bookingMailObj->getMailId() == 2) {
			/*setting reservation confirmation link in message
			if he must confirm it via mail, I send the link*/
			
			
			
			$message=str_replace("[confirmation-link]","<a href='".site_url('')."/?p=".$_POST["post_id"]."&confirm=1&reservations=".$listReservations."'>".__( 'Click here to confirm your reservation', 'wp-booking-calendar' )."</a>",$message);
			$message=str_replace("[confirmation-link-url]",site_url('')."/?p=".$_POST["post_id"]."&confirm=1&reservations=".$listReservations,$message);
		}
		
		if($bookingMailObj->getMailId() == 1 && $bookingSettingObj->getReservationCancel() == "1") {
			$message=str_replace("[cancellation-link]","<a href='".site_url('')."/?p=".$_POST["post_id"]."&cancel=1&reservations=".$listReservations."'>".__( 'Click here to cancel your reservation', 'wp-booking-calendar' )."</a>",$message);
			$message=str_replace("[cancellation-link-url]",site_url('')."/?p=".$_POST["post_id"]."&cancel=1&reservations=".$listReservations,$message);
		}
		$message.="<br><br>".$bookingMailObj->getMailSignature();
		

		if(($bookingSettingObj->getPaypal() == 0 && !isset($_POST["with_paypal"])) || ($bookingSettingObj->getPaypal() == 1 && $bookingSettingObj->getReservationAfterPayment() == 0)) {
			wp_mail($to, $subject,$message, $headers );
			
		}
	}
	$arrReservations = explode(",",$listReservations);
	$htmlToAppend = "";
	for($i=0;$i<count($arrReservations);$i++) {
		$htmlToAppend.='<input type="hidden" name="item_number_'.($i+1).'" value="'.$arrReservations[$i].'" />';
	}
	?>
	<script>
		<?php
		if(isset($_POST["with_paypal"]) && $paypalAmount>0) {
			?>
			htmlToAppend = "";
			window.parent.$wbc('#slots_purchased').append('<input type="hidden" name="custom" value="<?php echo $listReservations; ?>" />');
			window.parent.$wbc('#slots_purchased').append('<?php echo addslashes($paypalHtml); ?>');
			window.parent.submitPaypal();
			<?php
		} else {
			
			if($bookingSettingObj->getRedirectBookingPath() == '') {
				?>
				window.parent.showResponse(<?php echo $bookingCalendarObj->getCalendarId(); ?>);
				<?php
			} else {
				?>
				window.parent.document.location = '<?php echo $bookingSettingObj->getRedirectBookingPath(); ?>';
				<?php
			}
			
		}
		?>
	</script>
	<?php
} else {
	$publickey = "";
	if($bookingSettingObj->getRecaptchaEnabled() == "1") {
		$publickey = $bookingSettingObj->getRecaptchaPublicKey();
	}
	?>
	<script>
		window.parent.alert('<?php echo esc_js(__( 'An error occurred. This time slot may be already reserved. Please try again', 'wp-booking-calendar' )); ?>');
		window.parent.hideBookingResponse(<?php echo $bookingCalendarObj->getCalendarId(); ?>,'<?php echo $publickey; ?>');
	</script>
	<?php
}


?>
