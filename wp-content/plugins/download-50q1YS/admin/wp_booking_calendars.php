<?php 

include 'common.php';

if(isset($_GET["file"]) && $_GET["file"] == "new_calendar") {
	include 'new_calendar.php';
	
} else if(isset($_GET["calendar_id"])) {
	include 'calendar_manage.php';
	
} else {
	if(isset($_POST["operation"]) && $_POST["operation"] != '') {
		$arrCalendars=$_POST["calendars"];
		$qryString = "0";
		for($i=0;$i<count($arrCalendars); $i++) {
			$qryString .= ",".$arrCalendars[$i];
		}
			
		switch($_POST["operation"]) {
			case "publishCalendars":
				$bookingCalendarObj->publishCalendars($qryString);
				break;
			case "unpublishCalendars":
				$bookingCalendarObj->unpublishCalendars($qryString);
				break;
			case "delCalendars":
				$bookingCalendarObj->delCalendars($qryString);
				break;
			case "duplicateCalendars":
				$bookingCalendarObj->duplicateCalendars($qryString);
				break;
		}                

	}
	$filter = "";
	
	?>
	
	<!-- 
	=====================================================================
	=====================================================================
	-->
	
	
	<div class="booking_padding_20 booking_font_14 booking_line_percent booking_bg_fff">
	
			
				<?php
				$arrayCalendars = $bookingListObj->getCalendarsList(); 
				
				?>
				<!-- 
				=======================
				=== js - check ==
				=======================
				-->
				<script>
					
					var $wbc = jQuery;
					
					function delItem(itemId) {
						if(confirm("<?php echo esc_js(__( 'Are you sure you want to delete this item? All holidays, slots and reservations will be deleted', 'wp-booking-calendar' )); ?>")) {
							$wbc.ajax({
							  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/delCalendarItem.php?item_id='+itemId,
							  success: function(data) {
								  $wbc('#booking_table').hide().html(data).fadeIn(2000);
								 
								
							  }
							});
						} 
					}
					function publishCalendar(itemId) {
						if(confirm("<?php echo esc_js(__( 'Are you sure you want to publish this calendar?', 'wp-booking-calendar' )); ?>")) {
							$wbc.ajax({
							  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/publishCalendar.php?calendar_id='+itemId,
							  success: function(data) {
								  $wbc('#publish_'+itemId).html('<a href="javascript:unpublishCalendar('+itemId+');"><img src="<?php echo BOOKING_ADMIN_URL;?>images/icons/published.png" border=0 /></a>');
								
							  }
							});
						} 
					}
					function unpublishCalendar(itemId) {
						if(confirm("<?php echo esc_js(__( 'Are you sure you want to unpublish this calendar?', 'wp-booking-calendar' )); ?>")) {
							$wbc.ajax({
							  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/unpublishCalendar.php?calendar_id='+itemId,
							  success: function(data) {
								  $wbc('#publish_'+itemId).html('<a href="javascript:publishCalendar('+itemId+');"><img src="<?php echo BOOKING_ADMIN_URL;?>images/icons/unpublished.png" border=0 /></a>');
								
							  }
							});
						} 
					}
					
					
					function setDefaultCalendar(calendar,category) {
						$wbc.ajax({
						  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/setDefaultCalendar.php?calendar_id='+calendar+"&category_id="+category,
						  success: function(data) {
							document.location.reload();
						  }
						});
					}
					
					$wbc(function() {
						<?php
						if(count($arrayCalendars)>0) {
							?>
							showActionBar();
							<?php
						}
						?>
					});
					
					function showActionBar() {
						$wbc('#action_bar').slideDown();
					}
					function hideActionBar() {
						$wbc('#action_bar').slideUp();
					}
					
					function filterCalendars() {
						category = document.getElementById('category_filter').options[document.getElementById('category_filter').selectedIndex].value;
						 $wbc.ajax({
						  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/filterCalendars.php?category_id='+category,
						  success: function(data) {
							$wbc('#booking_table').hide().html(data).fadeIn(2000);
							
						  }
						});
					}
				</script>
				
			   
			   
				<!-- 
				=======================
				=== create calendar ==
				=======================
				-->
				
				
				<div><a href="?page=wp-booking-calendar&file=new_calendar&calendar_id=0" class="booking_bg_693 booking_admin_button booking_green_button booking_mark_fff" style="width:200px;margin:auto;"><?php echo __( 'Add', 'wp-booking-calendar' ); ?></a></div>
				
			   
				
				 <!-- 
				=======================
				=== action bar ==
				=======================
				-->
				
				<div id="action_bar" style="display:none !important" class="booking_margin_t_20 booking_bg_f6f booking_border_1 booking_border_solid booking_border_ccc booking_padding_10">
					<div class="booking_float_left">
						<select name="category_filter" id="category_filter" onchange="javascript:filterCalendars();">
							<option value="0"><?php echo __( 'choose a category', 'wp-booking-calendar' ); ?></option>
							<?php
							$arrayCategories = $bookingListObj->getCategoriesList();
							foreach($arrayCategories as $categoryId => $category) {
								?>
								<option value="<?php echo $categoryId; ?>"><?php echo $category["category_name"]; ?></option>
								<?php
							}
							?>
						</select>
					</div>
					<div class="booking_float_right">
						<div class="booking_float_left"><?php echo __( 'Selected items', 'wp-booking-calendar' ); ?>:</div>
						<a onclick="javascript:delItems('manage_calendars','calendars[]','publishCalendars','<?php echo __( 'Are you sure to publish the selected items?', 'wp-booking-calendar' ); ?>','<?php echo __( 'No items selected', 'wp-booking-calendar' ); ?>')" class="booking_float_left booking_margin_l_20 booking_mark_333 booking_pointer booking_font_bold"><?php echo __( 'Publish', 'wp-booking-calendar' ); ?></a>
						<a onclick="javascript:delItems('manage_calendars','calendars[]','unpublishCalendars','<?php echo __( 'Are you sure to unpublish the selected items?', 'wp-booking-calendar' ); ?>','<?php echo __( 'No items selected', 'wp-booking-calendar' ); ?>')" class="booking_float_left booking_margin_l_20 booking_mark_333 booking_pointer booking_font_bold"><?php echo __( 'Unpublish', 'wp-booking-calendar' ); ?></a>
						<a onclick="javascript:delItems('manage_calendars','calendars[]','delCalendars','<?php echo __( 'Are you sure to delete the selected items? Slots and reservations of these calendars will be deleted too', 'wp-booking-calendar' ); ?>','<?php echo __( 'No items selected', 'wp-booking-calendar' ); ?>')" class="booking_float_left booking_margin_l_20 booking_mark_333 booking_pointer booking_font_bold"><?php echo __( 'Delete', 'wp-booking-calendar' ); ?></a>
						<a onclick="javascript:delItems('manage_calendars','calendars[]','duplicateCalendars','<?php echo __( 'Are you sure to duplicate the selected items?', 'wp-booking-calendar' ); ?>','<?php echo __( 'No items selected', 'wp-booking-calendar' ); ?>')" class="booking_float_left booking_margin_l_20 booking_mark_333 booking_pointer booking_font_bold"><?php echo __( 'Duplicate', 'wp-booking-calendar' ); ?></a>
						<div class="booking_cleardiv"></div>
					</div>
					<div class="booking_cleardiv"></div>
					
				</div>
			   
				<!-- 
				=======================
				=== table calendars ==
				=======================
				-->
				<form name="manage_calendars" action="" method="post" style="display: inline;">
					<input type="hidden" name="operation" />
					<input type="hidden" name="calendars[]" value=0 />
					
					<div class="booking_margin_t_20">
						<div id="booking_table">
						
							
							
							
							
							<?php include 'ajax/wp_booking_calendars.php'; ?>
						</div>
					</div>
				</form>
				
				
				<div class="booking_cleardiv"></div>
		
	</div>
	<?php
}
?>
