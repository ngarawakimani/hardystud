<?php
include '../common.php';
@session_start();
$fp = fopen(ABSPATH . "wp-content/plugins/wp-booking-calendar/admin/ajax/your_reservation.csv", 'w+');
$headerLine = __( 'Date', 'wp-booking-calendar' ).",".__( 'Time', 'wp-booking-calendar' );
	if(in_array("reservation_surname",$bookingSettingObj->getVisibleFields())) { 		
		$headerLine.=",".__( 'Surname', 'wp-booking-calendar' );
	}
	if(in_array("reservation_name",$bookingSettingObj->getVisibleFields())) { 		
		$headerLine.=",".__( 'Name', 'wp-booking-calendar' );
	}
	if(in_array("reservation_email",$bookingSettingObj->getVisibleFields())) { 		
		$headerLine.=",".__( 'Email', 'wp-booking-calendar' );
	}
	if(in_array("reservation_phone",$bookingSettingObj->getVisibleFields())) { 		
		$headerLine.=",".__( 'Phone', 'wp-booking-calendar' );
	}
	if(in_array("reservation_message",$bookingSettingObj->getVisibleFields())) { 		
		$headerLine.=",".__( 'Message', 'wp-booking-calendar' );
	}
	if(in_array("reservation_field1",$bookingSettingObj->getVisibleFields())) { 		
		$headerLine.=",".__( 'Additional field 1', 'wp-booking-calendar' );
	}
	if(in_array("reservation_field2",$bookingSettingObj->getVisibleFields())) { 		
		$headerLine.=",".__( 'Additional field 2', 'wp-booking-calendar' );
	}
	if(in_array("reservation_field3",$bookingSettingObj->getVisibleFields())) { 		
		$headerLine.=",".__( 'Additional field 3', 'wp-booking-calendar' );
	}
	if(in_array("reservation_field4",$bookingSettingObj->getVisibleFields())) { 		
		$headerLine.=",".__( 'Additional field 4', 'wp-booking-calendar' );
	}
	if($bookingSettingObj->getSlotsUnlimited() == 2) {
		$headerLine.=",".__( 'Seats', 'wp-booking-calendar' );
	}
	$headerLine.=",".__( 'Confirmed', 'wp-booking-calendar' ).",".__( 'Cancelled', 'wp-booking-calendar' )."\r\n";
	
	fwrite($fp, $headerLine);

$arrayReservations = $bookingListObj->getUsersReservationsList($_SESSION["qryUsersReservationsFilterString"],$_SESSION["qryUsersReservationsOrderString"],$_GET["user_id"]);

foreach($arrayReservations as $reservationId => $reservation) {
	$confirmed = __( 'NO', 'wp-booking-calendar' );
	if($reservation["reservation_confirmed"] ==1) {
		$confirmed = __( 'YES', 'wp-booking-calendar' );
	}
	$cancelled = __( 'NO', 'wp-booking-calendar' );
	if($reservation["reservation_cancelled"] ==1) {
		$cancelled = __( 'YES', 'wp-booking-calendar' );
	}
$line = $reservation["reservation_date"].",".$reservation["reservation_time"];
		if(in_array("reservation_surname",$bookingSettingObj->getVisibleFields())) { 		
			$line.=",".$reservation["reservation_surname"];
		}
		if(in_array("reservation_name",$bookingSettingObj->getVisibleFields())) { 		
			$line.=",".$reservation["reservation_name"];
		}
		if(in_array("reservation_email",$bookingSettingObj->getVisibleFields())) { 		
			$line.=",".$reservation["reservation_email"];
		}
		if(in_array("reservation_phone",$bookingSettingObj->getVisibleFields())) { 		
			$line.=",".$reservation["reservation_phone"];
		}
		if(in_array("reservation_message",$bookingSettingObj->getVisibleFields())) { 		
			$line.=",".$reservation["reservation_message"];
		}
		if(in_array("reservation_field1",$bookingSettingObj->getVisibleFields())) { 		
			$line.=",".$reservation["reservation_field1"];
		}
		if(in_array("reservation_field2",$bookingSettingObj->getVisibleFields())) { 		
			$line.=",".$reservation["reservation_field2"];
		}
		if(in_array("reservation_field3",$bookingSettingObj->getVisibleFields())) { 		
			$line.=",".$reservation["reservation_field3"];
		}
		if(in_array("reservation_field4",$bookingSettingObj->getVisibleFields())) { 		
			$line.=",".$reservation["reservation_field4"];
		}
		if($bookingSettingObj->getSlotsUnlimited() == 2) {
			$line.=",".$reservation["reservation_seats"];
		}
		$line.=",".$confirmed.",".$cancelled."\r\n";
		fwrite($fp, $line);
	
}
fclose($fp);




?>
