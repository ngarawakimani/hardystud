<?php
include '../common.php';

/********check if there are reservation and if there are slots with same time/date***********/
$result=$bookingSlotsObj->checkEditSlotsReservation();
if($result > 0) {
?>
	<script>
		if(confirm("<?php echo esc_js(__( 'There are reservation for one or more of the selected slots. Modify them anyway?', 'wp-booking-calendar' )); ?>")) {
			window.parent.document.forms["modify_slots"].action="";
			window.parent.document.forms["modify_slots"].target="_self";
			window.parent.document.forms["modify_slots"].submit();
		} else {
			window.parent.document.getElementById('edit_button').disabled=false;
		}
	</script>
<?php
} else if($result == 0) {
?>
	<script>            
		window.parent.document.getElementById('result_modify').innerHTML = "<img src='<?php echo BOOKING_ADMIN_URL;?>images/loading.gif'>";
		setTimeout("submitForm()",3000);
		function submitForm() {
			window.parent.document.forms["modify_slots"].action="";
			window.parent.document.forms["modify_slots"].target="_self";
			window.parent.document.forms["modify_slots"].submit();            
		}
	</script>
<?php
} else if($result == -1) {
?>
<script>            
	alert("<?php echo esc_js(__( 'Duplicate slots. Cannot make these changes', 'wp-booking-calendar' )); ?>");
	window.parent.document.getElementById('edit_button').disabled=false;
</script>
<?php
}


?>
