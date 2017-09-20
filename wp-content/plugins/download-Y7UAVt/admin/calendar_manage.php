<?php 
include 'common.php';

/*manage closing days operations*/
if(isset($_POST["operation"]) && $_POST["operation"] != '' && isset($_POST["holidays"])) {
	$arrHolidays=$_POST["holidays"];
	$qryString = "0";
	for($i=0;$i<count($arrHolidays); $i++) {
		$qryString .= ",".$arrHolidays[$i];
	}
		
	switch($_POST["operation"]) {
		case "delHolidays":
			$bookingHolidayObj->delHolidays($qryString);
			break;
	}    
	?>
	<script>
		document.location.href="?page=wp-booking-calendar&calendar_id=<?php echo $_GET["calendar_id"]; ?>&ref=holidays";
    </script>
    <?php   
}
/*manage time slots operations*/
if(isset($_POST["operation"]) && $_POST["operation"] != '' && isset($_POST["slots"])) {
	
	$arrSlots=$_POST["slots"];
	$qryString = "0";
	for($i=0;$i<count($arrSlots); $i++) {
		$qryString .= ",".$arrSlots[$i];
	}
		
	switch($_POST["operation"]) {
		case "delSlots":
			$bookingSlotsObj->delSlots($qryString);
			
			break;
	}  
	?>
	<script>
		document.location.href="?page=wp-booking-calendar&calendar_id=<?php echo $_GET["calendar_id"]; ?>&ref=slots";
    </script>
    <?php             
	
}
if(isset($_POST["slot_hour_delete"])) {
	/***********first check if there are reservation for the selected time slots and alert*************/
	$_POST["calendar_id"] = $_GET["calendar_id"];
	if($bookingSlotsObj->checkSlotsReservation() == 0) {
		$numslots = $bookingSlotsObj->deleteSlots();
	} else {
		$numslots = $bookingSlotsObj->disableSlots();
	}
	?>
	<script>
        alert('<?php echo $numslots; ?> <?php echo esc_js(__('slots deleted','wp-booking-calendar')); ?>');
		document.location.href="?page=wp-booking-calendar&calendar_id=<?php echo $_GET["calendar_id"]; ?>&ref=slots";
    </script>
    <?php
	
}
if(isset($_POST["slot_hour_edit"])) {
	/***********first check if there are reservation for the selected time slots and alert*************/
	$_POST["calendar_id"] = $_GET["calendar_id"];
	$numslots = $bookingSlotsObj->modifySlots();
	
	?>
	<script>
        alert('<?php echo $numslots; ?> <?php echo esc_js(__('slots modified','wp-booking-calendar')); ?>');
		document.location.href="?page=wp-booking-calendar&calendar_id=<?php echo $_GET["calendar_id"]; ?>&ref=slots";
    </script>
    <?php
	
}
if(isset($_POST["slot_date_from"])) {	
	
	$numslots=$bookingSlotsObj->addSlot();
	?>
	<script>
		<?php
		if($numslots>0) {
			?>
			alert('<?php echo $numslots; ?> <?php echo esc_js(__('slots added','wp-booking-calendar')); ?>');
			<?php
		} else if($numslots == -1) {
			?>
			alert('<?php echo esc_js(__('Days selected are holidays. Cannot make these changes','wp-booking-calendar')); ?>');
			<?php
		} else {
			?>
			alert('<?php echo esc_js(__('Duplicate slots. Cannot make these changes','wp-booking-calendar')); ?>');
			<?php
		}
		?>
        
		document.location.href="?page=wp-booking-calendar&calendar_id=<?php echo $_GET["calendar_id"]; ?>&ref=slots";
    </script>
    <?php

	
}








$bookingCalendarObj->setCalendar($_GET["calendar_id"]);


?>

<!-- 
=====================================================================
=====================================================================
-->

<script>
	
	var $wbc = jQuery;
	$wbc(function() {
		<?php
		if(isset($_GET["ref"])) {
			?>
			showPage('<?php echo $_GET["ref"]; ?>');
			<?php
		}
		?>
	});
	function showPage(pagename) {
		$wbc('#booking_holidays_menu').css({"background-color":"#333"});
		$wbc('#booking_slots_menu').css({"background-color":"#333"});
		$wbc.ajax({
		  url: '<?php echo BOOKING_ADMIN_URL; ?>ajax/showPage.php?pagename='+pagename+"&calendar_id=<?php echo $_GET["calendar_id"]; ?>",
		  success: function(data) {
			  
			  $wbc('#booking_page_container').hide().html(data).slideDown(1000);
			  $wbc('#'+pagename+'_menu').css({"background-color":"#666"});
		  }
		});
	}
	
</script>

<!-- 
=====================================================================
	layout
=====================================================================
-->

<div class="booking_padding_20 booking_font_14 booking_line_percent booking_bg_fff">

      
			
            <!-- breadcrumb -->
            <div class="booking_font_12"><?php echo __('You are in','wp-booking-calendar'); ?>: <a href="?page=wp-booking-calendar"><?php echo  __('Calendars','wp-booking-calendar'); ?></a> > <?php echo $bookingCalendarObj->getCalendarTitle(); ?> > <strong><?php echo __('Manage','wp-booking-calendar'); ?></strong></div>
            
            <?php
			$background = "";
			$status = "";
			if($bookingCalendarObj->getCalendarActive() == 1) {
				$background = "#00B478";
				$status = __('Published','wp-booking-calendar');
			} else {
				$background = "#E05B5B";
				$status = __('Unpublished','wp-booking-calendar');
			}
			?>
            
            <!-- calendar status -->
            <div class="booking_bg_00b booking_mark_fff booking_margin_t_20 booking_padding_10" style="background:<?php echo $background; ?>"><?php echo __('The Actual Status of this calendar is','wp-booking-calendar'); ?>: <span style="text-transform:uppercase; font-weight: bold;"><?php echo $status; ?></span></div>
            
            <!-- menu manage calendar -->
            <div id="booking_menu_container_small" class="booking_margin_t_20">
                <div id="booking_menu">
                    <ul>
                        <li><a href="javascript:showPage('slots');" id="booking_slots_menu"><?php echo __('MANAGE TIME SLOTS','wp-booking-calendar'); ?></a></li>
                        <li><a href="javascript:showPage('holidays');" id="booking_holidays_menu"><?php echo __('CLOSING DAYS','wp-booking-calendar'); ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="booking_cleardiv"></div>
            
            
            <div id="booking_page_container" class="booking_margin_t_20">
            	<!-- contents by js -->
            </div>
            <div class="booking_cleardiv"></div>
       
</div>
