
<script>
	var $wbc = jQuery;
	
	var customopen = 0;
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

		$wbc('input[name=slot_show_different_price]').each(function() {
            var val = $wbc(this).val();
            $wbc(this).bind('click',function() {
               showDiscountedPrice(val);
            });
        });

        $wbc('input[name=slot_perc_or_discount]').each(function() {
            var val = $wbc(this).val();
            $wbc(this).bind('click',function() {
                showPercOrDiscount(val);
            });
        });



		$wbc( "#slot_date_from").datepicker({
			altField: "#first_date",
			altFormat: "yy,mm,dd",
			minDate: new Date(),
			 onClose: function(selectedDate) { 
			 	
				  $wbc( "#slot_date_to").datepicker( "option", "minDate", selectedDate );
				 		
				 		 
			}
			
			

		});
		$wbc( "#slot_date_to").datepicker({
			altField: "#second_date",
			altFormat: "yy,mm,dd",
			onClose: function(selectedDate) {
				$wbc( "#slot_date_from" ).datepicker( "option", "maxDate", selectedDate );
				$wbc('#delete_button').fadeIn();
				$wbc('#weekday_div').fadeIn();
				
			}
		});

		
		
	});
	function showCustom(choice) {
		if(choice == '0') {
			customopen = 1;
			$wbc('#custom_fields').fadeIn("slow");
			
			$wbc('#range_time').fadeOut("slow");
			$wbc('#custom_minutes').fadeOut("slow");
			
		} else if(choice == '1') {
			customopen=0;
			$wbc('#custom_fields').fadeOut("slow");
			$wbc('#range_time').fadeIn("slow");
			$wbc('#custom_minutes').fadeIn("slow");
			
			
		} else {
			customopen=0;
			$wbc('#custom_fields').fadeOut("slow");
			$wbc('#range_time').fadeOut("slow");
			$wbc('#custom_minutes').fadeOut("slow");
			
		}
		
	}
	
	function addTime(num) {
		
		$wbc('#add_time').attr("onClick","javascript:addTime("+(num+1)+");");
		htmlToAdd = '<div id="time_'+num+'" class="booking_font_12"><br><br><?php echo esc_js(__( 'From', 'wp-booking-calendar' )); ?>&nbsp;';
		htmlToAdd+='<select name="slot_interval_custom_from_hour[]">';
		htmlToAdd+='<option value="">hour</option>';
                    <?php
                    if($bookingSettingObj->getTimeFormat() == '24') {
						$start=0;
                        $to = 23;
                        $ampm=0;
                    } else {
						$start=1;
                        $to = 12;
                        $ampm = 1;
                    }
                    for($i=$start;$i<=$to;$i++) {
						
                        ?>
                        htmlToAdd+='<option value="<?php echo $i; ?>"><?php echo $i; ?></option>';
                        <?php
                    }
                    ?>
                htmlToAdd+='</select>';
                htmlToAdd+='<select name="slot_interval_custom_from_minute[]">';
                htmlToAdd+='<option value="">minute</option>';
                    <?php						
                    for($i=0;$i<=59;$i++) {
						$num = $i;
						if(strlen($num) == 1) {
							$num = '0'.$num;
						}
                        ?>
                        htmlToAdd+='<option value="<?php echo $i; ?>"><?php echo $num; ?></option>';
                        <?php
                    }
                    ?>
                htmlToAdd+='</select>';
                <?php
                if($ampm == 1) {
                    ?>
                    htmlToAdd+='<select name="slot_interval_custom_from_ampm[]">';
                    htmlToAdd+='<option value="am">am</option>';
                    htmlToAdd+='<option value="pm">pm</option>';
                    htmlToAdd+='</select>';
                    <?php
                }
                ?>
                htmlToAdd+='&nbsp;';
               htmlToAdd+='&nbsp;';
				htmlToAdd+='<?php echo esc_js(__( 'To', 'wp-booking-calendar' )); ?>&nbsp;';
                htmlToAdd+='<select name="slot_interval_custom_to_hour[]">';
                 htmlToAdd+='<option value="">hour</option>';
                    <?php
                    if($bookingSettingObj->getTimeFormat() == '24') {
						$start=0;
                        $to = 23;
                        $ampm=0;
                    } else {
						$start=1;
                        $to = 12;
                        $ampm = 1;
                    }
                    for($i=$start;$i<=$to;$i++) {
                        ?>
                        htmlToAdd+='<option value="<?php echo $i; ?>"><?php echo $i; ?></option>';
                        <?php
                    }
                    ?>
                htmlToAdd+='</select>';
                htmlToAdd+='<select name="slot_interval_custom_to_minute[]">';
                htmlToAdd+='<option value="">minute</option>';
                    <?php						
                    for($i=0;$i<=59;$i++) {
						$num = $i;
						if(strlen($num) == 1) {
							$num = '0'.$num;
						}
                        ?>
                        htmlToAdd+='<option value="<?php echo $i; ?>"><?php echo $num; ?></option>';
                        <?php
                    }
                    ?>
                htmlToAdd+='</select>';
                <?php
                if($ampm == 1) {
                    ?>
                    htmlToAdd+='<select name="slot_interval_custom_to_ampm[]">';
                    htmlToAdd+='<option value="am">am</option>';
                    htmlToAdd+='<option value="pm">pm</option>';
                    htmlToAdd+='</select>';
                    <?php
                }
                ?>
				htmlToAdd+='&nbsp;<input type="button" id="minus_'+num+'" value="-" onclick="javascript:delTime(\''+num+'\');"></div>';
		$wbc('#custom_fields_add').append(htmlToAdd);
		
		
	}
	function delTime(num) {
		$wbc('#time_'+num).remove();
		var length = document.forms["addslot"]["slot_interval_custom_from_hour[]"].length;
		
	}
	
	function addTimeRange(num) {
		$wbc('#add_time_range').attr("onClick","javascript:addTimeRange("+(num+1)+");");
		htmlToAdd='<div id="time_range_'+num+'" class="booking_font_12"><br><br><div class="booking_float_left booking_margin_t_10"><?php echo esc_js(__( 'From', 'wp-booking-calendar' )); ?></div>';
		htmlToAdd+='<div class="booking_float_left booking_margin_l_10">';
		htmlToAdd+='<select name="slot_time_from_hour[]">';
        htmlToAdd+='<option value="">hour</option>';
                        <?php
						if($bookingSettingObj->getTimeFormat() == '24') {
							$start=0;
							$to = 23;
							$ampm=0;
						} else {
							$start=1;
							$to = 12;
							$ampm = 1;
						}
						for($i=$start;$i<=$to;$i++) {
							?>
                            htmlToAdd+='<option value="<?php echo $i; ?>"><?php echo $i; ?></option>';
                            <?php
						}
						?>
                    htmlToAdd+='</select>';
                    htmlToAdd+='<select name="slot_time_from_minute[]">';
                    htmlToAdd+='<option value="">minute</option>';
                        <?php						
						for($i=0;$i<=59;$i++) {
							$num = $i;
							if(strlen($num) == 1) {
								$num = '0'.$num;
							}
							?>
                            htmlToAdd+='<option value="<?php echo $i; ?>"><?php echo $num; ?></option>';
                            <?php
						}
						?>
                    htmlToAdd+='</select>';
                    <?php
					if($ampm == 1) {
						?>
                        htmlToAdd+='<select name="slot_time_from_ampm[]">';
                        htmlToAdd+='<option value="am">am</option>';
                        htmlToAdd+='<option value="pm">pm</option>';
                        htmlToAdd+='</select>';
                        <?php
					}
					?>
                	
               	htmlToAdd+='</div>';
                
                htmlToAdd+='<div class="booking_float_left booking_margin_t_10 booking_margin_l_10"><?php echo esc_js(__( 'To', 'wp-booking-calendar' )); ?></div>';
                htmlToAdd+='<div class="booking_float_left booking_margin_l_10">';
                htmlToAdd+='<select name="slot_time_to_hour[]">';
                htmlToAdd+='<option value="0">hour</option>';
                        <?php
						if($bookingSettingObj->getTimeFormat() == '24') {
							$start=0;
							$to = 23;
							$ampm=0;
						} else {
							$start=1;
							$to = 12;
							$ampm = 1;
						}
						for($i=$start;$i<=$to;$i++) {
							?>
                            htmlToAdd+='<option value="<?php echo $i; ?>"><?php echo $i; ?></option>';
                            <?php
						}
						?>
                    htmlToAdd+='</select>';
                    htmlToAdd+='<select name="slot_time_to_minute[]">';
                    htmlToAdd+='<option value="">minute</option>';
                        <?php						
						for($i=0;$i<=59;$i++) {
							$num = $i;
							if(strlen($num) == 1) {
								$num = '0'.$num;
							}
							?>
                            htmlToAdd+='<option value="<?php echo $i; ?>"><?php echo $num; ?></option>';
                            <?php
						}
						?>
                    htmlToAdd+='</select>';
                    <?php
					if($ampm == 1) {
						?>
                        htmlToAdd+='<select name="slot_time_to_ampm[]">';
                            htmlToAdd+='<option value="am">am</option>';
                            htmlToAdd+='<option value="pm">pm</option>';
                        htmlToAdd+='</select>';
                        <?php
					}
					?>
                	
                htmlToAdd+='</div>';
                htmlToAdd+='&nbsp;<input type="button" id="minus_range_'+num+'" value="-" onclick="javascript:delTimeRange(\''+num+'\');"></div><div class="booking_cleardiv"></div>';
		$wbc('#time_ranges_add').append(htmlToAdd);
		
	}
	function delTimeRange(num) {
		$wbc('#time_range_'+num).remove();
	}
	
	
	
	function clearDateTo() {
		$wbc('#slot_date_to').val('');
		$wbc('#second_date').val('');
		$wbc('#weekday_div').fadeOut();
		$wbc('#delete_button').fadeOut();
	}
	function checkSlotIntervalCustomTimes() {
		var error = 0;
		
		var len = document.getElementsByName('slot_interval_custom_from_hour[]').length;
		for(i=0;i<len;i++) {
			tempMinuteFrom = document.getElementsByName("slot_interval_custom_from_minute[]").item(i).value;
			if(document.getElementsByName("slot_interval_custom_from_minute[]").item(i).value.length == 1) {
				tempMinuteFrom = '0'+document.getElementsByName("slot_interval_custom_from_minute[]").item(i).value;
			}
			tempMinuteTo = document.getElementsByName("slot_interval_custom_to_minute[]").item(i).value;
			if(document.getElementsByName("slot_interval_custom_to_minute[]").item(i).value.length == 1) {
				tempMinuteTo = '0'+document.getElementsByName("slot_interval_custom_to_minute[]").item(i).value;
				
			}
			
			var from_value =document.getElementsByName("slot_interval_custom_from_hour[]").item(i).value+""+tempMinuteFrom;
			var to_value =document.getElementsByName("slot_interval_custom_to_hour[]").item(i).value+""+tempMinuteTo;
			if(document.getElementsByName("slot_interval_custom_from_ampm[]").item(i) && document.getElementsByName("slot_interval_custom_from_ampm[]").item(i).value=='am') {
				switch(document.getElementsByName("slot_interval_custom_from_hour[]").item(i).value) {
					case '12':
						from_value = '00'+tempMinuteFrom;
						break;
				}
			} else if(document.getElementsByName("slot_interval_custom_from_ampm[]").item(i) && document.getElementsByName("slot_interval_custom_from_ampm[]").item(i).value=='pm') {
				switch(document.getElementsByName("slot_interval_custom_from_hour[]").item(i).value) {
					case '1':
						from_value = '13'+tempMinuteFrom;
						break;
					case '2':
						from_value = '14'+tempMinuteFrom;
						break;
					case '3':
						from_value = '15'+tempMinuteFrom;
						break;
					case '4':
						from_value = '16'+tempMinuteFrom;
						break;
					case '5':
						from_value = '17'+tempMinuteFrom;
						break;
					case '6':
						from_value = '18'+tempMinuteFrom;
						break;
					case '7':
						from_value = '19'+tempMinuteFrom;
						break;
					case '8':
						from_value = '20'+tempMinuteFrom;
						break;
					case '9':
						from_value = '21'+tempMinuteFrom;
						break;
					case '10':
						from_value = '22'+tempMinuteFrom;
						break;
					case '11':
						from_value = '23'+tempMinuteFrom;
						break;
				}
			}
			if(document.getElementsByName("slot_interval_custom_to_ampm[]").item(i) && document.getElementsByName("slot_interval_custom_to_ampm[]").item(i).value=='am') {
				switch(document.getElementsByName("slot_interval_custom_to_hour[]").item(i).value) {
					case '12':
						to_value = '00'+tempMinuteTo;
						break;
				}
			} else if(document.getElementsByName("slot_interval_custom_to_ampm[]").item(i) && document.getElementsByName("slot_interval_custom_to_ampm[]").item(i).value=='pm') {
				switch(document.getElementsByName("slot_interval_custom_to_hour[]").item(i).value) {
					case '1':
						to_value = '13'+tempMinuteTo;
						break;
					case '2':
						to_value = '14'+tempMinuteTo;
						break;
					case '3':
						to_value = '15'+tempMinuteTo;
						break;
					case '4':
						to_value = '16'+tempMinuteTo;
						break;
					case '5':
						to_value = '17'+tempMinuteTo;
						break;
					case '6':
						to_value = '18'+tempMinuteTo;
						break;
					case '7':
						to_value = '19'+tempMinuteTo;
						break;
					case '8':
						to_value = '20'+tempMinuteTo;
						break;
					case '9':
						to_value = '21'+tempMinuteTo;
						break;
					case '10':
						to_value = '22'+tempMinuteTo;
						break;
					case '11':
						to_value = '23'+tempMinuteTo;
						break;
				}
			}
			
			/*if(from_value.substring(0,1) == "0") {
				from_value = parseInt(from_value.substring(1,4));
			}
			if(to_value.substring(0,1) == "0") {
				to_value = parseInt(to_value.substring(1,4));
			}*/
			 
			if(parseInt(to_value)<parseInt(from_value)) {
				error = 1;
			}
		}
		
		return error;
	}
	function checkSlotTimes() {
		var error = 0;
		var len = document.getElementsByName('slot_time_from_hour[]').length;
		for(i=0;i<len;i++) {
			tempMinuteFrom = document.getElementsByName("slot_time_from_minute[]").item(i).value;
			if(document.getElementsByName("slot_time_from_minute[]").item(i).value.length == 1) {
				tempMinuteFrom = '0'+document.getElementsByName("slot_time_from_minute[]").item(i).value;
			}
			tempMinuteTo = document.getElementsByName("slot_time_to_minute[]").item(i).value;
			if(document.getElementsByName("slot_time_to_minute[]").item(i).value.length == 1) {
				tempMinuteTo = '0'+document.getElementsByName("slot_time_to_minute[]").item(i).value;
				
			}
			
			var from_value =document.getElementsByName("slot_time_from_hour[]").item(i).value+""+tempMinuteFrom;
			var to_value =document.getElementsByName("slot_time_to_hour[]").item(i).value+""+tempMinuteTo;
			if(document.getElementsByName("slot_time_from_ampm[]").item(i) && document.getElementsByName("slot_time_from_ampm[]").item(i).value=='am') {
				switch(document.getElementsByName("slot_time_from_hour[]").item(i).value) {
					case '12':
						from_value = '00'+tempMinuteFrom;
						break;
				}
			} else if(document.getElementsByName("slot_time_from_ampm[]").item(i) && document.getElementsByName("slot_time_from_ampm[]").item(i).value=='pm') {
				switch(document.getElementsByName("slot_time_from_hour[]").item(i).value) {
					case '1':
						from_value = '13'+tempMinuteFrom;
						break;
					case '2':
						from_value = '14'+tempMinuteFrom;
						break;
					case '3':
						from_value = '15'+tempMinuteFrom;
						break;
					case '4':
						from_value = '16'+tempMinuteFrom;
						break;
					case '5':
						from_value = '17'+tempMinuteFrom;
						break;
					case '6':
						from_value = '18'+tempMinuteFrom;
						break;
					case '7':
						from_value = '19'+tempMinuteFrom;
						break;
					case '8':
						from_value = '20'+tempMinuteFrom;
						break;
					case '9':
						from_value = '21'+tempMinuteFrom;
						break;
					case '10':
						from_value = '22'+tempMinuteFrom;
						break;
					case '11':
						from_value = '23'+tempMinuteFrom;
						break;
				}
			}
			if(document.getElementsByName("slot_time_to_ampm[]").item(i) && document.getElementsByName("slot_time_to_ampm[]").item(i).value=='am') {
				switch(document.getElementsByName("slot_time_to_hour[]").item(i).value) {
					case '12':
						to_value = '00'+tempMinuteTo;
						break;
				}
			} else if(document.getElementsByName("slot_time_to_ampm[]").item(i) && document.getElementsByName("slot_time_to_ampm[]").item(i).value=='pm') {
				switch(document.getElementsByName("slot_time_to_hour[]").item(i).value) {
					case '1':
						to_value = '13'+tempMinuteTo;
						break;
					case '2':
						to_value = '14'+tempMinuteTo;
						break;
					case '3':
						to_value = '15'+tempMinuteTo;
						break;
					case '4':
						to_value = '16'+tempMinuteTo;
						break;
					case '5':
						to_value = '17'+tempMinuteTo;
						break;
					case '6':
						to_value = '18'+tempMinuteTo;
						break;
					case '7':
						to_value = '19'+tempMinuteTo;
						break;
					case '8':
						to_value = '20'+tempMinuteTo;
						break;
					case '9':
						to_value = '21'+tempMinuteTo;
						break;
					case '10':
						to_value = '22'+tempMinuteTo;
						break;
					case '11':
						to_value = '23'+tempMinuteTo;
						break;
				}
			}
			
			/*if(from_value.substring(0,1) == "0") {
				from_value = parseInt(from_value.substring(1,4));
			}
			if(to_value.substring(0,1) == "0") {
				to_value = parseInt(to_value.substring(1,4));
			}*/
			
			if(parseInt(to_value)<parseInt(from_value)) {
				error = 1;
			}
			
			
		}
		
		return error;
	}
	function checkData(frm) {
		with(frm) {
			if(slot_date_from.value=='') {
				alert("<?php echo esc_js(__( 'Select a date from', 'wp-booking-calendar' )); ?>");
				return false;
			} else if(slot_interval.options[slot_interval.selectedIndex].value == '') {
				alert("<?php echo esc_js(__( 'Choose slot interval', 'wp-booking-calendar' )); ?>");
				return false;
			} else if(slot_interval.options[slot_interval.selectedIndex].value == '1' && (!isNumeric(slot_interval_minutes) || slot_interval_minutes.value>1435)) {
				alert("<?php echo esc_js(__( 'Insert a valid value for slot interval', 'wp-booking-calendar' )); ?>");
				return false;
			} else if(slot_interval.options[slot_interval.selectedIndex].value == '0' && (document.getElementsByName("slot_interval_custom_from_hour[]").item(0).value=='' || document.getElementsByName("slot_interval_custom_to_hour[]").item(0).value=='')) {
				alert("<?php echo esc_js(__( 'Insert at least a custom slot', 'wp-booking-calendar' )); ?>");
				return false;
			} else if(slot_interval.options[slot_interval.selectedIndex].value == '0' && checkSlotIntervalCustomTimes()==1) {				
				alert("<?php echo esc_js(__( 'Slot duration values are not correct', 'wp-booking-calendar' )); ?>");
				return false;
			} else if(slot_pause.value != '0' && slot_pause.value!= '' && (!isNumeric(slot_pause) || slot_pause.value<5 || slot_pause.value>1435)) {
				alert("<?php echo esc_js(__( 'Insert a valid value for slot pause', 'wp-booking-calendar' )); ?>");
				return false;
			} else if(slot_interval.options[slot_interval.selectedIndex].value == '1' && (document.getElementsByName("slot_time_from_hour[]").item(0).value=='' || document.getElementsByName("slot_time_to_hour[]").item(0).value=='')) {
				alert("<?php echo esc_js(__( 'Select at least time from and a time to', 'wp-booking-calendar' )); ?>");
				return false;
			} else if(slot_interval.options[slot_interval.selectedIndex].value == '1' && checkSlotTimes() == 1) {
				alert("<?php echo esc_js(__( 'Time periods for slots are not correct', 'wp-booking-calendar' )); ?>");
				return false;
			} else {
				$wbc('body').prepend($wbc('#hidden_loader').html());
				return true;
				
			}
		}
	}

    function showDiscountedPrice(val) {
        if(val == 1) {
            $wbc('#discounted_price').fadeIn();
        } else {
            $wbc('#discounted_price').fadeOut();
        }
    }

    function showPercOrDiscount(val) {
        if(val == 'perc') {
            $wbc('#perc_price').fadeIn();
            $wbc('#discount_price').fadeOut(0);
        } else {
            $wbc('#discount_price').fadeIn();
            $wbc('#perc_price').fadeOut(0);
        }
        $wbc('#show_price').fadeIn();
    }
</script>
<div id="hidden_loader" style="display:none !important">
<div id="sfondo" class="booking_modal_sfondo"><div id="modal_loading" class="booking_modal_loading"><img src="<?php echo BOOKING_ADMIN_URL;?>images/loading.png" border=0 /></div></div>
</div>
<div class="booking_margin_t_30 booking_font_18 booking_bg_fff">
	<div class="booking_font_bold booking_font_20"><label for="slot_date_from"><?php echo __( 'Insert your preferences to create the time slots', 'wp-booking-calendar' ); ?></label></div>
    <div class="booking_font_bold booking_font_14 booking_mark_red booking_margin_t_10 booking_line_20"><?php echo __( 'Remember to limit the time period to a maximum of 3 months at once if you have many slots in a day as there is a limit which prevent to insert more than 2000 slots at once to avoid your WP site to crash or block during slots creation', 'wp-booking-calendar' ); ?></div>
    
<form name="addslot" action="" method="post" onsubmit="return checkData(this);" style="display:inline;">
    <input type="hidden" name="calendar_id" value="<?php echo $_GET["calendar_id"]; ?>" />
    
    <!-- 
    =======================
    === Creation date ==
    =======================
    -->
    <div class="booking_font_bold booking_margin_t_20"><label for="slot_date_from"><?php echo __( 'Date', 'wp-booking-calendar' ); ?></label></div>
   
    <div class="booking_margin_t_10 booking_font_12">
    	<div class="booking_float_left booking_height_30 booking_line_30"><?php echo __( 'From', 'wp-booking-calendar' ); ?></div>
        <div class="booking_float_left booking_margin_l_10">
        	<input type="text" class="booking_width_100 booking_margin_t_5" name="slot_date_from" id="slot_date_from" readonly="readonly">
            <input type="hidden" name="first_date" id="first_date">
        </div>
        
        <div class="booking_float_left booking_height_30 booking_line_30 booking_margin_l_20"><?php echo __( 'To', 'wp-booking-calendar' ); ?></div>
        <div class="booking_float_left booking_margin_l_10">
            <input type="text" class="booking_width_100 booking_margin_t_5" name="slot_date_to" id="slot_date_to"  readonly="readonly">&nbsp;<input type="button" id="delete_button" style="display:none !important" value="<?php echo __( 'Delete', 'wp-booking-calendar' ); ?>" onclick="javascript:clearDateTo();" />
            <input type="hidden" name="second_date" id="second_date">
            <div class="booking_font_12 booking_mark_666"><?php echo __( 'Leave this field empty if you want to set a single day', 'wp-booking-calendar' ); ?></div>
        </div>
        
        <div class="booking_cleardiv"></div>
    
    </div>
    
    <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
    
    <!-- 
    =======================
    ===  ==
    =======================
    -->
    <div id="weekday_div" style="display:none !important">
        <div class="booking_font_bold"><label for="slot_weekday"><?php echo __( 'Days', 'wp-booking-calendar' ); ?></label></div>
        
        <div class="booking_font_12">
            <div class="booking_margin_t_10"><input type="checkbox" name="selectAll" id="slot_weekday" value="0" onclick="javascript:selectCheckbox('addslot','slot_weekday[]');" checked="checked">&nbsp;<?php echo __( 'All', 'wp-booking-calendar' ); ?><br></div>
            <div class="booking_margin_t_10"><input type="checkbox" name="slot_weekday[]" id="slot_weekday" value="1" onclick="javascript:disableSelectAll('addslot',this.checked);" checked="checked">&nbsp;<?php echo __( 'Mondays', 'wp-booking-calendar' ); ?></div>
            <div class="booking_margin_t_10"><input type="checkbox" name="slot_weekday[]" id="slot_weekday" value="2" onclick="javascript:disableSelectAll('addslot',this.checked);" checked="checked">&nbsp;<?php echo __( 'Tuesdays', 'wp-booking-calendar' ); ?></div>
            <div class="booking_margin_t_10"><input type="checkbox" name="slot_weekday[]" id="slot_weekday" value="3" onclick="javascript:disableSelectAll('addslot',this.checked);" checked="checked">&nbsp;<?php echo __( 'Wednesdays', 'wp-booking-calendar' ); ?></div>
            <div class="booking_margin_t_10"><input type="checkbox" name="slot_weekday[]" id="slot_weekday" value="4" onclick="javascript:disableSelectAll('addslot',this.checked);" checked="checked">&nbsp;<?php echo __( 'Thursdays', 'wp-booking-calendar' ); ?></div>
            <div class="booking_margin_t_10"><input type="checkbox" name="slot_weekday[]" id="slot_weekday" value="5" onclick="javascript:disableSelectAll('addslot',this.checked);" checked="checked">&nbsp;<?php echo __( 'Fridays', 'wp-booking-calendar' ); ?></div>
            <div class="booking_margin_t_10"><input type="checkbox" name="slot_weekday[]" id="slot_weekday" value="6" onclick="javascript:disableSelectAll('addslot',this.checked);" checked="checked">&nbsp;<?php echo __( 'Saturdays', 'wp-booking-calendar' ); ?></div>
            <div class="booking_margin_t_10"><input type="checkbox" name="slot_weekday[]" id="slot_weekday" value="7" onclick="javascript:disableSelectAll('addslot',this.checked);" checked="checked">&nbsp;<?php echo __( 'Sundays', 'wp-booking-calendar' ); ?></div>
        </div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
    
    </div>
    
    
    
    <!-- 
    =======================
    === Slot duration  ==
    =======================
    -->
   <div class="booking_margin_t_10">
        <div class="booking_font_bold"><label for="slot_interval"><?php echo __( 'Slot duration', 'wp-booking-calendar' ); ?></label></div>
        <div class="booking_font_12"><?php echo __( 'Select the length each time slot will have', 'wp-booking-calendar' ); ?></div>
    </div>
    
    <div class="booking_font_12 booking_margin_t_10">
        <select name="slot_interval" id="slot_interval" onChange="javascript:showCustom(this.options[this.selectedIndex].value);" >
        	<option value=""><?php echo __( 'choose duration', 'wp-booking-calendar' ); ?></option>
        	<option value="1"><?php echo __( 'in minutes', 'wp-booking-calendar' ); ?></option>
            <option value="0"><?php echo __( 'from, to', 'wp-booking-calendar' ); ?></option>
        </select>
        
         <!-- if from to  -->
        <div id="custom_fields" style="display:none !important;margin-top:20px">
        	<div class="booking_font_12 booking_margin_b_10"><?php echo __( 'Even if you want to set a fixed hour (i.e. 6:00), please remember to select minutes too (00) or you\'ll get the error "Duplicated slots"', 'wp-booking-calendar' ); ?></div>
        	<div id="custom_fields_add">
				<?php echo __( 'From', 'wp-booking-calendar' ); ?>&nbsp;
                <select name="slot_interval_custom_from_hour[]">
                    <option value=""><?php echo __( 'hour', 'wp-booking-calendar' ); ?></option>
                    <?php
                    if($bookingSettingObj->getTimeFormat() == '24') {
						$start=0;
                        $to = 23;
                        $ampm=0;
                    } else {
						$start=1;
                        $to = 12;
                        $ampm = 1;
                    }
                    for($i=$start;$i<=$to;$i++) {
                        ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <select name="slot_interval_custom_from_minute[]">
                    <option value=""><?php echo __( 'minute', 'wp-booking-calendar' ); ?></option>
                    <?php						
                    for($i=0;$i<=59;$i++) {
						$num = $i;
						if(strlen($num) == 1) {
							$num = '0'.$num;
						}
                        ?>
                        <option value="<?php echo $i; ?>"><?php echo $num; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <?php
                if($ampm == 1) {
                    ?>
                    <select name="slot_interval_custom_from_ampm[]">
                        <option value="am">am</option>
                        <option value="pm">pm</option>
                    </select>
                    <?php
                }
                ?>
                &nbsp;
                &nbsp;
				<?php echo __( 'To', 'wp-booking-calendar' ); ?>&nbsp;
                <select name="slot_interval_custom_to_hour[]">
                    <option value=""><?php echo __( 'hour', 'wp-booking-calendar' ); ?></option>
                    <?php
                    if($bookingSettingObj->getTimeFormat() == '24') {
						$start=0;
                        $to = 23;
                        $ampm=0;
                    } else {
						$start=1;
                        $to = 12;
                        $ampm = 1;
                    }
                    for($i=$start;$i<=$to;$i++) {
                        ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <select name="slot_interval_custom_to_minute[]">
                    <option value=""><?php echo __( 'minute', 'wp-booking-calendar' ); ?></option>
                    <?php						
                    for($i=0;$i<=59;$i++) {
						$num = $i;
						if(strlen($num) == 1) {
							$num = '0'.$num;
						}
                        ?>
                        <option value="<?php echo $i; ?>"><?php echo $num; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <?php
                if($ampm == 1) {
                    ?>
                    <select name="slot_interval_custom_to_ampm[]">
                        <option value="am">am</option>
                        <option value="pm">pm</option>
                    </select>
                    <?php
                }
                ?>
                
            </div>
            <br /><input type="button" id="add_time" value="+" onClick="javascript:addTime(1);">
        </div>
        
         
        
        <!-- if in minutes  -->
        <div id="custom_minutes" style="display:none !important;margin-top:20px"><?php echo __( 'Type here the minutes', 'wp-booking-calendar' ); ?>&nbsp;<input type="text" name="slot_interval_minutes" id="slot_interval_minutes" class="booking_width_100 booking_margin_t_5" ></div>
        
    </div>
   
    <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
    
    
    
    
    <!-- 
    =======================
    === Pause management  ==
    =======================
    -->
    <div class="booking_font_bold"><label for="slot_interval"><?php echo __( 'Pause between time slots', 'wp-booking-calendar' ); ?></label></div>
    <div class="booking_font_12"><?php echo __( 'If you like set an interval  between the time slots', 'wp-booking-calendar' ); ?></div>
    
    <div class="booking_font_12 booking_margin_t_10"><input type="text" name="slot_pause" value="0" class="booking_width_100" /></div>
      
    <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
    
    
    
    <!-- 
    =======================
    === Range time ==
    =======================
    -->
    <div id="range_time">
        <div class="booking_font_bold"><label for="slot_date_from"><?php echo __( 'Time', 'wp-booking-calendar' ); ?></label></div>
        <div class="booking_font_12"><?php echo __( 'Set the period of time in which time slots will be available', 'wp-booking-calendar' ); ?><br /><?php echo __( 'Even if you want to set a fixed hour (i.e. 6:00), please remember to select minutes too (00) or you\'ll get the error "Duplicated slots"', 'wp-booking-calendar' ); ?></div>
        
        
        <div class="booking_margin_t_10 booking_font_12" id="time_ranges">
        	<div id="time_ranges_add">
                <div class="booking_float_left booking_margin_t_10"><?php echo __( 'From', 'wp-booking-calendar' ); ?></div>
                <div class="booking_float_left booking_margin_l_10">
                	<select name="slot_time_from_hour[]" class="booking_margin_t_5">
                    	<option value=""><?php echo __( 'hour', 'wp-booking-calendar' ); ?></option>
                        <?php
						if($bookingSettingObj->getTimeFormat() == '24') {
							$start=0;
							$to = 23;
							$ampm=0;
						} else {
							$start=1;
							$to = 12;
							$ampm = 1;
						}
						for($i=$start;$i<=$to;$i++) {
							?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php
						}
						?>
                    </select>
                    <select name="slot_time_from_minute[]" class="booking_margin_t_5">
                    	<option value=""><?php echo __( 'minute', 'wp-booking-calendar' ); ?></option>
                        <?php						
						for($i=0;$i<=59;$i++) {
							$num = $i;
							if(strlen($num) == 1) {
								$num = '0'.$num;
							}
							?>
                            <option value="<?php echo $i; ?>"><?php echo $num; ?></option>
                            <?php
						}
						?>
                    </select>
                    <?php
					if($ampm == 1) {
						?>
                        <select name="slot_time_from_ampm[]" class="booking_margin_t_5">
                            <option value="am">am</option>
                            <option value="pm">pm</option>
                        </select>
                        <?php
					}
					?>
                	<!--<input type="text" name="slot_time_from[]" id="slot_time_from0"  readonly="readonly" class="booking_width_100 booking_margin_t_5">-->
               	</div>
                
                <div class="booking_float_left booking_margin_t_10 booking_margin_l_10"><?php echo __( 'To', 'wp-booking-calendar' ); ?></div>
                <div class="booking_float_left booking_margin_l_10">
                	<select name="slot_time_to_hour[]" class="booking_margin_t_5">
                    	<option value=""><?php echo __( 'hour', 'wp-booking-calendar' ); ?></option>
                        <?php
						if($bookingSettingObj->getTimeFormat() == '24') {
							$start=0;
							$to = 23;
							$ampm=0;
						} else {
							$start=1;
							$to = 12;
							$ampm = 1;
						}
						for($i=$start;$i<=$to;$i++) {
							?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php
						}
						?>
                    </select>
                    <select name="slot_time_to_minute[]" class="booking_margin_t_5">
                    	<option value=""><?php echo __( 'minute', 'wp-booking-calendar' ); ?></option>
                        <?php						
						for($i=0;$i<=59;$i++) {
							$num = $i;
							if(strlen($num) == 1) {
								$num = '0'.$num;
							}
							?>
                            <option value="<?php echo $i; ?>"><?php echo $num; ?></option>
                            <?php
						}
						?>
                    </select>
                    <?php
					if($ampm == 1) {
						?>
                        <select name="slot_time_to_ampm[]" class="booking_margin_t_5">
                            <option value="am">am</option>
                            <option value="pm">pm</option>
                        </select>
                        <?php
					}
					?>
                	
                </div>
                <div class="booking_cleardiv"></div>
            </div>
            <div><input type="button" id="add_time_range" value="+" onClick="javascript:addTimeRange(1);" /></div>
            
        </div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
        
    </div>  
    
    
     <!-- 
    =======================
    === special text management  ==
    =======================
    -->
    
    <div class="booking_font_bold"><label for="special_text"><?php echo __( 'Description text (optional)', 'wp-booking-calendar' ); ?></label></div>
    
    <div class="booking_font_12 booking_margin_t_10">
    	<input type="text" name="special_text" id="special_text" /><br />
        <select name="special_mode" id="special_mode" style="width: 250px;">
        	<option value="1" selected><?php echo __( 'Show both times and special text', 'wp-booking-calendar' ); ?></option>
            <option value="0"><?php echo __( 'Show just special text', 'wp-booking-calendar' ); ?></option>
        </select>
    </div>
     <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
    
    
    <!-- 
    ========================
    === Price management  ==
    ========================
    -->
   
    <div class="booking_font_bold"><label for="slot_price"><?php echo __( 'Price', 'wp-booking-calendar' ); ?></label></div>
    <div class="booking_font_12"><?php echo __( 'Insert price for the slots your are creating', 'wp-booking-calendar' ); ?></div>
    
    <div class="booking_margin_t_10 booking_font_12"><input type="text" name="slot_price" value="0" /><?php echo $bookingSettingObj->getPaypalCurrency(); ?></div>
    


    <?php
    /*if Paypal is active, I need to ask if they want people to pay only a percentage of the price or a discounted price*/
    if($bookingSettingObj->getPaypal()==1 && $bookingSettingObj->getPaypalAccount() != '' && $bookingSettingObj->getPaypalLocale() != '' && $bookingSettingObj->getPaypalCurrency() != '') {
        ?>
        <div class="booking_font_bold booking_margin_t_20"><label for="slot_show_different_price"><?php echo __('Do you want to let people pay a discounted price or only a percentage on the total price?','wp-booking-calendar'); ?></label></div>

        <div class="booking_float_left booking_margin_t_10"><input type="radio" name="slot_show_different_price" value="1" /></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12"><?php echo __('YES','wp-booking-calendar'); ?></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10"><input type="radio" name="slot_show_different_price" value="0" checked/></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12"><?php echo __('NO','wp-booking-calendar'); ?></div>
        <div class="booking_cleardiv"></div>
        <div id="discounted_price" style="display: none">
            <div class="booking_font_bold booking_margin_t_20"><label for="slot_perc_or_discount"><?php echo sprintf(__('Percentage or discounted price in %s?','wp-booking-calendar'),$bookingSettingObj->getPaypalCurrency()); ?></label></div>

            <div class="booking_float_left booking_margin_t_10"><input type="radio" name="slot_perc_or_discount" value="perc" /></div>
            <div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12"><?php echo __('Percentage','wp-booking-calendar'); ?></div>
            <div class="booking_float_left booking_margin_t_10 booking_margin_l_10"><input type="radio" name="slot_perc_or_discount" value="discount"/></div>
            <div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12"><?php echo sprintf(__('Discounted price in %s','wp-booking-calendar'),$bookingSettingObj->getPaypalCurrency()); ?></div>
            <div class="booking_cleardiv"></div>
            <div id="perc_price" style="display: none">
                <div class="booking_margin_t_10 booking_font_12"><input type="text" name="slot_perc_price" value="0" />%</div>
            </div>
            <div id="discount_price" style="display: none">
                <div class="booking_margin_t_10 booking_font_12"><input type="text" name="slot_discount_price" value="0" /><?php echo $bookingSettingObj->getPaypalCurrency(); ?></div>
            </div>
        </div>
        <div id="show_price" style="display: none">
            <div class="booking_font_bold booking_margin_t_20"><label for="slot_show_price"><?php echo __( 'Choose what you want to show to customers', 'wp-booking-calendar' ); ?></label></div>

            <div class="booking_font_12 booking_margin_t_10">
                <select name="slot_show_price" id="slot_show_price" style="width: 250px;">
                    <option value="0"><?php echo __( 'Show just full price', 'wp-booking-calendar' ); ?></option>
                    <option value="1"><?php echo __( 'Show just discounted price', 'wp-booking-calendar' ); ?></option>
                    <option value="2" selected><?php echo __( 'Show both full and discounted prices', 'wp-booking-calendar' ); ?></option>
                </select>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
	
    
    
    
    
    <!-- 
    ========================
    === Seats management  ==
    ========================
    -->
    <?php
	if($bookingSettingObj->getSlotsUnlimited() == 2) {
	?>
    <script>
		function fillAvMax(string) {
			$wbc('#slot_av_max').val(string);
		}
	</script>
    <div class="booking_font_bold"><label for="slot_av"><?php echo __( 'Slot seats number', 'wp-booking-calendar' ); ?></label></div>
    <div class="booking_font_12"><?php echo __( 'choose availability for every slot', 'wp-booking-calendar' ); ?></div>
    
    <div class="booking_margin_t_10 booking_font_12"><input type="text" name="slot_av" value="0" onkeyup="javascript:fillAvMax(this.value);" /></div>
    
    <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
    
    <div class="booking_font_bold"><label for="slot_av_max"><?php echo __( 'Maximum number of bookable seats at once', 'wp-booking-calendar' ); ?></label></div>
    <div class="booking_font_12"><?php echo __( 'Choose the maximum number of bookable seats at once by a customer', 'wp-booking-calendar' ); ?></div>
    
    <div class="booking_margin_t_10 booking_font_12"><input type="text" name="slot_av_max" id="slot_av_max" value="0" /></div>
    
    <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
	<?php
    }
    ?>
    
    
    
    
    
    <!-- bridge buttons -->
    <div class="booking_bg_333 booking_padding_10">
        <!-- cancel -->
        <div class="booking_float_left"><a href="javascript:document.location.href='?page=wp-booking-calendar&calendar_id=<?php echo $_GET["calendar_id"]; ?>&ref=slots';" class="booking_bg_ccc booking_admin_button booking_grey_button booking_mark_fff"><?php echo __( 'CANCEL', 'wp-booking-calendar' ); ?></a></div>
        <div class="booking_float_left booking_margin_l_20"><input type="submit" id="apply_button" value="<?php echo __( 'SAVE', 'wp-booking-calendar' ); ?>" class="booking_bg_693 booking_admin_button booking_green_button booking_mark_fff"></div>
        <div id="loading" style="float:left;margin-top:30px;margin-left:10px"></div>
        <div class="booking_cleardiv"></div>
        
    </div>
    
    
    </form>
    
 </div>

        
      
