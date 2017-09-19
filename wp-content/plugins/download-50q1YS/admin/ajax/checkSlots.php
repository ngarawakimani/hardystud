<?php
include '../common.php';

if($bookingSlotsObj->checkSlotsReservation() > 0) {
?>
	<script>
		if(confirm("<?php echo esc_js(__( 'There are reservation for one or more of the selected slots. Modify them anyway?', 'wp-booking-calendar' )); ?>")) {
			window.parent.document.forms["delete_slots"].action="";
			window.parent.document.forms["delete_slots"].target="_self";
			window.parent.document.forms["delete_slots"].submit();
		} else {
			window.parent.document.getElementById('del_button').disabled=false;
		}
	</script>
<?php
} else {
?>
	<script>            
		window.parent.document.getElementById('result_delete').innerHTML = "<img src='<?php echo BOOKING_ADMIN_URL;?>images/loading.gif'>";
		setTimeout("submitForm()",3000);
		function submitForm() {
			window.parent.document.forms["delete_slots"].action="";
			window.parent.document.forms["delete_slots"].target="_self";
			window.parent.document.forms["delete_slots"].submit();            
		}
	</script>
<?php
}


?>
