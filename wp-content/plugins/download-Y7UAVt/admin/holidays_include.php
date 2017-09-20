<!-- 
=====================================================================
=====================================================================
-->
<script>
	var $wbc = jQuery;
	$wbc(function() {
		
		<?php
		if($bookingSettingObj->getDateFormat() == "UK") {
			?>
			$wbc.datepicker.setDefaults( $wbc.datepicker.regional[ "en-GB" ] );
			<?php
		} else if($bookingSettingObj->getDateFormat() == "EU") {
			?>
			$wbc.datepicker.setDefaults( $wbc.datepicker.regional[ "eu-EU" ] );
			<?php
		} else {
			?>
			$wbc.datepicker.setDefaults( $wbc.datepicker.regional[ "us-US" ] );
			<?php
		}
		?>
		
		arrDateFrom=$wbc('#first_date').val().split(",");
		$wbc( "#add_date_to").datepicker({
			altField: "#second_date",
			altFormat: "yy,mm,dd",
			minDate: new Date(arrDateFrom[0],(arrDateFrom[1]-1),arrDateFrom[2]),
			onClose: function(selectedDate) {
				$wbc( "#add_date_from" ).datepicker( "option", "maxDate", selectedDate );
				
			}
			
		 });
		$wbc( "#add_date_from").datepicker({
			altField: "#first_date",
			altFormat: "yy,mm,dd",
			minDate: new Date(),
			 onClose: function(selectedDate) { 
				  $wbc( "#add_date_to" ).datepicker( "option", "minDate", selectedDate );
				  $wbc( "#add_date_to").datepicker({
					altField: "#second_date",
					altFormat: "yy,mm,dd",

					onClose: function( selectedDate ) {
						$wbc( "#add_date_from" ).datepicker( "option", "maxDate", selectedDate );
					}
		
				 });	
				 
			}

		});
		
	});
	function addDate() {
		if($wbc('#add_dates option:selected').val() == 1) {
			if(Trim($wbc('#add_date_from').val()) != '') {
				/*before I check if there are reservation for this date*/
				$wbc('#result_create').html('<img src="<?php echo BOOKING_ADMIN_URL;?>images/loading.gif">');
				$wbc.ajax({
				  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/checkHolidayDate.php?add_dates=1&date_from='+$wbc('#first_date').val()+"&calendar_id=<?php echo $_GET["calendar_id"]; ?>",
				  success: function(data) {
					 if(data == 0) {
						 $wbc.ajax({
						  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/addHolidayDate.php?add_dates=1&date_from='+$wbc('#first_date').val()+"&calendar_id=<?php echo $_GET["calendar_id"]; ?>",
						  success: function(data) {
							  $wbc(data).hide().appendTo('#table').fadeIn(2000);							 
							  $wbc('#add_date_from').val('');
							  $wbc('#first_date').val('');
							  $wbc('#result_create').html('');
							  openSection('create_div');
							  goToByScroll('goto');
							  $wbc("#add_dates").val(0);
							  showForm(0);

						  }
							  
						  });
					 } else {
						 if(confirm("<?php echo esc_js(__('No items selected','wp-booking-calendar')); ?>")) {
							 $wbc('#result_create').html('<img src="<?php echo BOOKING_ADMIN_URL;?>images/loading.gif">');
							 $wbc.ajax({
							  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/addHolidayDate.php?add_dates=1&date_from='+$wbc('#first_date').val()+"&calendar_id=<?php echo $_GET["calendar_id"]; ?>",
							  success: function(data) {
								  $wbc(data).hide().appendTo('#table').fadeIn(2000);							 
								  $wbc('#add_date_from').val('');
								  $wbc('#first_date').val('');
								  $wbc('#result_create').html('');
								  openSection('create_div');
								  goToByScroll('goto');
								  $wbc("#add_dates").val(0);
								  showForm(0);
							  }
								  
							  });
						 } else {
							 $wbc('#result_create').html('');
						 }
					 }
					  
					 
					}
				});
			} else {
				alert("Select a date");
			}
		} else if($wbc('#add_dates option:selected').val() == 2) {
			if(Trim($wbc('#first_date').val()) != '' && Trim($wbc('#second_date').val()) != '') {
				/*before I check if there are reservation for this date*/
				$wbc('#result_create').html('<img src="<?php echo BOOKING_ADMIN_URL;?>images/loading.gif">');
				$wbc.ajax({
				  url: "<?php echo BOOKING_ADMIN_URL;?>ajax/checkHolidayDate.php?add_dates=2&date_from="+$wbc('#first_date').val()+"&date_to="+$wbc('#second_date').val()+"&calendar_id=<?php echo $_GET["calendar_id"]; ?>",
				  success: function(data) {
					 if(data == 0) {
						 $wbc.ajax({
						  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/addHolidayDate.php?add_dates=2&date_from='+$wbc('#first_date').val()+"&date_to="+$wbc('#second_date').val()+"&calendar_id=<?php echo $_GET["calendar_id"]; ?>",
						  success: function(data) {
							  $wbc(data).hide().appendTo('#table').fadeIn(2000);							 
							  $wbc('#add_date_from').val('');
							  $wbc('#add_date_to').val('');
							  $wbc('#first_date').val('');
							  $wbc('#second_date').val('');
							  $wbc('#result_create').html('');
							  openSection('create_div');
							  goToByScroll('goto');
							  $wbc("#add_dates").val(0);
							  showForm(0);
						  }
							  
						  });
					 } else {
						 if(confirm("<?php echo esc_js(__('There are reservations for one or more dates, are you sure you want to set these days as holidays?','wp-booking-calendar')); ?>")) {
							 $wbc('#result_create').html('<img src="<?php echo BOOKING_ADMIN_URL;?>images/loading.gif">');
							 $wbc.ajax({
							  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/addHolidayDate.php?add_dates=2&date_from='+$wbc('#first_date').val()+"&date_to="+$wbc('#second_date').val()+"&calendar_id=<?php echo $_GET["calendar_id"]; ?>",
							  success: function(data) {
								  $wbc(data).hide().appendTo('#table').fadeIn(2000);							 
								  $wbc('#add_date_from').val('');
								  $wbc('#add_date_to').val('');
								  $wbc('#first_date').val('');
								  $wbc('#second_date').val('');
								  $wbc('#result_create').html('');
								  openSection('create_div');
								  goToByScroll('goto');
								  $wbc("#add_dates").val(0);
								  showForm(0);
							  }
								  
							  });
						 } else {
							 $wbc('#result_create').html('');
						 }
					 }
					  
					 
					}
				});
			} else {
				alert("<?php echo esc_js(__('Select a date','wp-booking-calendar')); ?>");
			}
		}
	}
	function delItem(itemId) {
		if(confirm("<?php echo esc_js(__('Are you sure you want to delete this item?','wp-booking-calendar')); ?>")) {
			$wbc.ajax({
			  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/delHolidayItem.php?item_id='+itemId+"&calendar_id=<?php echo $_GET["calendar_id"]; ?>",
			  success: function(data) {
				  $wbc('#table').hide().html(data).fadeIn(2000);
				 
				
			  }
			});
		}
	}
	function orderby(type) {
		
		$wbc.ajax({
		  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/setHolidayOrderby.php?order_by=date&type='+type+"&calendar_id=<?php echo $_GET["calendar_id"]; ?>",
		  success: function(data) {
			  $wbc('#table').hide().html(data).fadeIn(2000);						 
			
		  }
		});
		
	}
	
	function showForm(value) {
		if(value==1) {
			$wbc('#form_add').slideDown();
			$wbc('#date_to_field').slideUp();
			$wbc('#from_date_label').html('<?php echo esc_js(__('Day','wp-booking-calendar')); ?>');
		} else if(value==2) {
			$wbc('#form_add').slideDown();
			$wbc('#date_to_field').slideDown();
			$wbc('#from_date_label').html('<?php echo esc_js(__('From','wp-booking-calendar')); ?>');
		} else {
			$wbc('#form_add').slideUp();
		}
	}
	
	function openSection(div) {
		if(document.getElementById(div).style.display=="none") {
			$wbc('#'+div).slideDown();
		} else {
			$wbc('#'+div).slideUp();
		}
	}
	function goToByScroll(id){
	      $wbc('html,body').animate({scrollTop: $wbc("#"+id).offset().top},'slow');
	}
</script>

<!-- 
=====================================================================
	Create closing days
=====================================================================
-->

<a href="javascript:openSection('create_div');"  class="booking_manage_slot_box_title booking_border_1 booking_border_solid booking_border_ccc"><?php echo __('Create Closing Days','wp-booking-calendar'); ?></a>

<div class="booking_padding_5 booking_width_60p booking_bg_f6f" id="create_div" style="display:none !important">

    <div class="booking_margin_t_20 booking_font_14"><?php echo __('These days will not be available for booking. If you created time slots in these days, they will be deleted.','wp-booking-calendar'); ?></div>
   

    <!-- select create closing days -->
    <div class="booking_float_left booking_margin_t_20 booking_height_30 booking_line_30"><?php echo __('How many closing days do you want to set?','wp-booking-calendar'); ?></div>
    <div class="booking_float_left booking_margin_l_10 booking_margin_t_20">
        <select name="add_dates" id="add_dates" class="filter_by_date" onchange="javascript:showForm(this.options[this.selectedIndex].value);">
            <option value="0"><?php echo __('choose','wp-booking-calendar'); ?></option>
            <option value="1"><?php echo __('A single day','wp-booking-calendar'); ?></option>
            <option value="2"><?php echo __('Period of time','wp-booking-calendar'); ?></option>
        </select>
    </div>
    <div class="booking_cleardiv"></div>
    
    <div id="form_add" style="display:none !important">
        <!-- filter by period of time -->
        <div class="booking_float_left booking_margin_t_20 booking_height_30 booking_line_30" id="from_date_label"><?php echo  __('From','wp-booking-calendar'); ?></div>
        <div class="booking_float_left booking_margin_t_20 booking_margin_l_10">
            <input type="text" class="booking_width_100 booking_margin_t_5" name="add_date_from" id="add_date_from" readonly="readonly" style="background-color: #fff;" >
            <input type="hidden" name="first_date" id="first_date">
        </div>
        
        <div id="date_to_field" style="display:none !important">
            <div class="booking_float_left booking_margin_t_20 booking_margin_l_10 booking_height_30 booking_line_30"><?php echo __('To','wp-booking-calendar'); ?></div>
            <div class="booking_float_left booking_margin_t_20 booking_margin_l_10">
                <input type="text" class="booking_width_100 booking_margin_t_5" name="add_date_to" id="add_date_to"  readonly="readonly" style="background-color: #fff;">
                <input type="hidden" name="second_date" id="second_date">
            </div>
        </div>
        <div class="booking_cleardiv"></div>
            
        <!-- search -->
        <div class="booking_margin_t_20">
        	<input type="button"  class="booking_bg_693 booking_admin_button booking_green_button booking_mark_fff" name="saveunpublish" value="<?php echo __('SET','wp-booking-calendar'); ?>" onclick="javascript:addDate();">
        	<div id="result_create" style="float:left;margin-top:35px"></div>
        </div>
        <div class="booking_cleardiv"></div>
        
    </div>
    
</div>
    


<!-- 
=====================================================================
	Closing days list
=====================================================================
-->

<a name="goto" id="goto"></a>

<div class="booking_margin_t_20 booking_bg_f6f booking_border_1 booking_border_solid booking_border_ccc"> 
	<a onclick="javascript:delItems('manage_holidays','holidays[]','delHolidays','<?php echo __('Are you sure to delete the selected items?','wp-booking-calendar'); ?>','<?php echo __('No items selected','wp-booking-calendar'); ?>')" class="booking_float_right booking_pointer booking_padding_10"><?php echo __('Delete','wp-booking-calendar'); ?></a>
    <div class="booking_cleardiv"></div>
</div>
<div class="booking_cleardiv"></div>


<form name="manage_holidays" action="" method="post">
	<input type="hidden" name="operation" />
	<input type="hidden" name="holidays[]" value=0 />
    
    <div id="table" class="booking_margin_t_20">
    
    	<div class="booking_border_b_1 booking_border_solid booking_border_ccc booking_bg_f6f booking_height_30 booking_line_30">
        
			<div class="booking_float_left booking_width_5p">#</div>
			
			<div class="booking_float_left booking_width_5p"><input type="checkbox" name="selectAll" onclick="javascript:selectCheckbox('manage_holidays','holidays[]');" /></div>
			
			<div class="booking_float_left booking_width_70p"><?php echo __('Date','wp-booking-calendar'); ?>&nbsp;<a href="javascript:orderby('<?php echo $_SESSION["orderbyHolidayDate"]; ?>');"><img src="<?php echo BOOKING_ADMIN_URL;?>images/orderby_<?php echo $_SESSION["orderbyHolidayDate"];?>.gif" border=0 /></a></div>
			
			
			
			<div class="booking_float_left booking_width_20p"></div>
			
			<div class="booking_cleardiv"></div>
			
		</div>
    
			<?php                         
			$arrayHolidays = $bookingListObj->getHolidaysList("",$_GET["calendar_id"]);                        
			$i=1;
			foreach($arrayHolidays as $holidayId => $holiday) {							
				if($i % 2) {
					$class="booking_alternate_table_row_white";
				} else {
					$class="booking_alternate_table_row_grey";
				}
			?>
			<div id="row_<?php echo $holidayId; ?>">
				<div class="booking_float_left booking_width_5p booking_height_50 <?php echo $class; ?>">
					<div class="booking_wh_inherit booking_table_cell booking_vertical_middle"><?php echo $i; ?></div>
				</div>
				<div class="booking_float_left booking_width_5p booking_height_50 <?php echo $class; ?>">
					<div class="booking_wh_inherit booking_table_cell booking_vertical_middle"><input type="checkbox" name="holidays[]" value="<?php echo $holidayId; ?>" onclick="javascript:disableSelectAll('manage_holidays',this.checked);" /></div>
				</div>                    
				<div class="booking_float_left booking_width_70p booking_height_50 <?php echo $class; ?>">
					<div class="booking_wh_inherit booking_table_cell booking_vertical_middle">
                    	<?php
						if($bookingSettingObj->getDateFormat() == "UK") {
							$dateToSend = strftime('%d/%m/%Y',strtotime($holiday["holiday_date"]));
						} else if($bookingSettingObj->getDateFormat() == "EU") {
							$dateToSend = strftime('%Y/%m/%d',strtotime($holiday["holiday_date"]));
						} else {
							$dateToSend = strftime('%m/%d/%Y',strtotime($holiday["holiday_date"]));
						}
						?>
						<?php echo $dateToSend; ?>
                    </div>
				</div>
				<div class="booking_float_left booking_width_20p booking_height_50 <?php echo $class; ?>">
					<div class="booking_wh_inherit booking_table_cell booking_vertical_middle"><a href="javascript:delItem(<?php echo $holidayId; ?>,'holidays','holiday_id');"><?php echo __('Delete','wp-booking-calendar'); ?></a></div>
				</div>
				<div class="booking_cleardiv"></div>
			</div>
			<?php 
			$i++;
			} ?>
   </div>
   
</form>


<div id="booking_cleardiv"></div>
