<?php 
include 'common.php';
if(isset($_GET["reservation_id"])) {
	include 'user_reservation_detail.php';
} else {
	/*check if user can confirm/cancel reservations*/
	$confirm_function = 0;
	$cancel_function = 0;
	if($bookingSettingObj->getReservationConfirmationMode() == 2) {
		$confirm_function = 1;
	}
	if($bookingSettingObj->getReservationCancel() == 1) {
		$cancel_function = 1;
	}
	?>
	
	<div class="booking_padding_20 booking_font_14 booking_line_percent">

       
    
            
            
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
             $wbc( "#date_to").datepicker({
                altField: "#second_date",
                altFormat: "yy,mm,dd",
                onClose: function( selectedDate ) {
                    $wbc( "#date_from" ).datepicker( "option", "maxDate", selectedDate );
                }
            });
            $wbc( "#date_from").datepicker({
                altField: "#first_date",
                altFormat: "yy,mm,dd",
                
                onClose: function(selectedDate) { 
                    $wbc( "#date_to" ).datepicker( "option", "minDate", selectedDate );
                    $wbc( "#date_to").datepicker({
                        altField: "#second_date",
                        altFormat: "yy,mm,dd",

                        onClose: function( selectedDate ) {
                            $wbc( "#date_from" ).datepicker( "option", "maxDate", selectedDate );
                        }
                    });
                }
            });
            
            
        });
        
        function orderby(column,type) {
        
            $wbc.ajax({
              url: '<?php echo BOOKING_ADMIN_URL;?>ajax/setUsersReservationOrderby.php?order_by='+column+'&type='+type+"&user_id=<?php echo $reservation_user_id; ?>",
              success: function(data) {
                  $wbc('#table').hide().html(data).fadeIn(2000);						 
                
              }
            });
            
        }
        function filterReservations() {
            if($wbc('#search_date option:selected').val() == 1) {
                if(Trim($wbc('#first_date').val()) != '') {
                    $wbc('#result_search').html('<img src="<?php echo BOOKING_ADMIN_URL;?>images/loading.gif">');
                    $wbc.ajax({
                      url: '<?php echo BOOKING_ADMIN_URL;?>ajax/filterUsersReservations.php?date_from='+$wbc('#first_date').val()+"&calendar_id="+$wbc('#calendar_id').val()+"&user_id=<?php echo $reservation_user_id; ?>",
                      success: function(data) {
                          $wbc('#date_from').val('');
                          $wbc('#first_date').val('');
                          $wbc('#table').hide().html(data).fadeIn(2000);
                          $wbc('#result_search').html('');
                          goToByScroll("results");
                          $wbc('.action_reset').fadeIn(0);
                      }
                    });
                } else {
                    alert("<?php echo esc_js(__( 'Select a date', 'wp-booking-calendar' ));?>");
                }
            } else if($wbc('#search_date option:selected').val() == 2) {
                if(Trim($wbc('#first_date').val()) != '' && Trim($wbc('#second_date').val()) != '') {
                    $wbc('#result_search').html('<img src="<?php echo BOOKING_ADMIN_URL;?>images/loading.gif">');
                    $wbc.ajax({
                      url: '<?php echo BOOKING_ADMIN_URL;?>ajax/filterUsersReservations.php?date_from='+$wbc('#first_date').val()+"&date_to="+$wbc('#second_date').val()+"&calendar_id="+$wbc('#calendar_id').val()+"&user_id=<?php echo $reservation_user_id; ?>",
                      success: function(data) {
                          $wbc('#date_from').val('');
                          $wbc('#date_to').val('');
                          $wbc('#first_date').val('');
                          $wbc('#second_date').val('');
                          $wbc('#table').hide().html(data).fadeIn(2000);
                          $wbc('#result_search').html('');
                          goToByScroll("results");
                          $wbc('.action_reset').fadeIn(0);
                      }
                    });
                } else {
                    alert("<?php echo esc_js(__( 'Select date range', 'wp-booking-calendar' ));?>");
                }
            }
        }
		<?php
		if($cancel_function == 1) {
			?>
			function cancelItem(itemId) {
				/*have to check if paypal is active and this reservation has a price, if it has, warn the admin that it could not be payed*/
				<?php
				if($bookingSettingObj->getPaypal()==1 && $bookingSettingObj->getPaypalAccount() != '' && $bookingSettingObj->getPaypalLocale() != '' && $bookingSettingObj->getPaypalCurrency() != '') {
					?>
					$wbc.ajax({
					  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/checkSlotPrice.php?reservation_id='+itemId,
					  success: function(data) {
						  if(data>0) {
							  /*there's a price*/
							  alert("<?php echo __( 'You cannot cancel this reservation as the payment was successfull. Contact the administrator for its cancellation.', 'wp-booking-calendar' );?>");
						  } else {
							  if(confirm("<?php echo __( 'Are you sure you want to cancel this reservation?', 'wp-booking-calendar' );?>")) {
									$wbc.ajax({
									  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/cancelUsersReservationItem.php?item_id='+itemId+"&user_id=<?php echo $reservation_user_id; ?>",
									  success: function(data) {
										  $wbc('#table').hide().html(data).fadeIn(2000);
										 
										
									  }
									});
								}  
						  }
						
					  }
					});
					<?php
				} else {
					?>
					if(confirm("<?php echo __( 'Are you sure you want to cancel this reservation?', 'wp-booking-calendar' );?>")) {
						$wbc.ajax({
						  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/cancelUserReservationItem.php?item_id='+itemId+"&user_id=<?php echo $reservation_user_id; ?>",
						  success: function(data) {
							  $wbc('#table').hide().html(data).fadeIn(2000);
							 
							
						  }
						});
					} 
					<?php
				}
				?>
			}
			<?php
		}
		?>
		<?php
		if($confirm_function == 1) {
			?>
			function confirmReservation(itemId) {
				/*have to check if paypal is active and this reservation has a price, if it has, warn the admin that it could not be payed*/
				<?php
				if($bookingSettingObj->getPaypal()==1 && $bookingSettingObj->getPaypalAccount() != '' && $bookingSettingObj->getPaypalLocale() != '' && $bookingSettingObj->getPaypalCurrency() != '') {
					?>
					$wbc.ajax({
					  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/checkSlotPrice.php?reservation_id='+itemId,
					  success: function(data) {
						  if(data>0) {
							  /*there's a price*/
							  alert("<?php echo __( 'You can\'t confirm this reservation, only administrator is able to do it as he has to check your payment first', 'wp-booking-calendar' );?>");
						  } else {
							  if(confirm("<?php echo __( 'Are you sure you want to confirm this reservation?', 'wp-booking-calendar' );?>")) {
									$wbc.ajax({
									  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/confirmReservation.php?reservation_id='+itemId,
									  success: function(data) {
										  $wbc('#conferma_'+itemId).html('<img src="<?php echo BOOKING_ADMIN_URL;?>images/icons/published.png" border=0 />');
										
									  }
									});
								} 
						  }
						
					  }
					});
					<?php
				} else {
					?>
					if(confirm("<?php echo __( 'Are you sure you want to confirm this reservation?', 'wp-booking-calendar' );?>")) {
						$wbc.ajax({
							url: '<?php echo BOOKING_ADMIN_URL;?>ajax/confirmReservation.php?reservation_id='+itemId,
							success: function(data) {
								  $wbc('#conferma_'+itemId).html('<a href="javascript:unconfirmReservation('+itemId+');"><img src="<?php echo BOOKING_ADMIN_URL;?>images/icons/published.png" border=0 /></a>');
			
							}
						});
					} 
				<?php
				}
				?>
			}
			<?php
		}
		?>
        
        
        function showForm(value) {
            if(value==1) {
                $wbc('#form_add').slideDown();
                $wbc('#date_to_field').slideUp();
                $wbc('#from_date_label').html('<?php echo __( 'Day', 'wp-booking-calendar' );?>');
            } else if(value==2) {
                $wbc('#form_add').slideDown();
                $wbc('#date_to_field').slideDown();
                $wbc('#from_date_label').html('<?php echo __( 'From', 'wp-booking-calendar' );?>');
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
        
        function resetSearch() {
             $wbc.ajax({
                  url: '<?php echo BOOKING_ADMIN_URL;?>ajax/resetUsersReservationSearch.php?user_id=<?php echo $reservation_user_id; ?>',
                  success: function(data) {
                      $wbc('#table').hide().html(data).fadeIn(2000);
                      goToByScroll("results");
                      $wbc('.action_reset').fadeOut(0);
                  }
                });
        }
        
        function CSVexport() {
             $wbc.ajax({
              url: '<?php echo BOOKING_ADMIN_URL;?>ajax/csvUsersExport.php?user_id=<?php echo $reservation_user_id; ?>',
              success: function(data) {
                  document.location.href = "<?php echo BOOKING_ADMIN_URL;?>ajax/your_reservation.csv";
              }
            });
        }
        
    </script>
    
    
    <a href="javascript:openSection('create_div');"  class="booking_manage_slot_box_title booking_border_1 booking_border_solid booking_border_ccc booking_margin_t_20"><?php echo __( 'Search Reservations', 'wp-booking-calendar' );?></a>
    
    <div class="booking_padding_5 booking_width_60p booking_bg_f6f" id="create_div" style="display:none !important">
        <div class="booking_font_14 booking_margin_t_20"><?php echo __( 'Use the following filters to search reservations', 'wp-booking-calendar' );?></div>
        
        <!-- select create closing days -->
        <div class="booking_margin_t_20 booking_font_14">
            <div class="booking_float_left booking_height_30 booking_line_30"><?php echo __( 'Filter by date:', 'wp-booking-calendar' );?></div>
            <div class="booking_float_left booking_margin_l_10">
                <select name="search_date" id="search_date" onchange="javascript:showForm(this.options[this.selectedIndex].value);">
                    <option value="0"><?php echo __( 'choose', 'wp-booking-calendar' );?></option>
                    <option value="1"><?php echo __( 'One day', 'wp-booking-calendar' );?></option>
                    <option value="2"><?php echo __( 'Period of time', 'wp-booking-calendar' );?></option>
                </select>
            </div>
            <div class="booking_cleardiv"></div>
        </div>
        
        
        <div id="form_add" style="display:none !important">
            <!-- filter by period of time -->
            <div class="booking_float_left booking_height_30 booking_line_30 booking_margin_t_20" id="from_date_label"><?php echo __( 'From', 'wp-booking-calendar' );?></div>
            <div class="booking_float_left booking_margin_l_10 booking_margin_t_20">
                <input type="text" class="booking_width_100 booking_margin_t_5" name="date_from" id="date_from" readonly="readonly" style="background-color: #fff;">
                <input type="hidden" name="first_date" id="first_date">
            </div>
            <div id="date_to_field" style="display:none !important">
                <div class="booking_float_left booking_height_30 booking_line_30 booking_margin_t_20 booking_margin_l_10"><?php echo __( 'To', 'wp-booking-calendar' );?></div>
                <div class="booking_float_left booking_margin_l_10 booking_margin_t_20">
                    <input type="text" class="booking_width_100 booking_margin_t_5" name="date_to" id="date_to"  readonly="readonly" style="background-color: #fff;">
                    <input type="hidden" name="second_date" id="second_date">
                </div>
                <div class="booking_cleardiv"></div>        
            </div>
            
            
            <div class="booking_cleardiv"></div>     
            <!-- search -->
            <div class="booking_margin_t_20">
                <input type="button" name="saveunpublish" value="<?php echo __( 'Search', 'wp-booking-calendar' ); ?>" onclick="javascript:filterReservations();" class="booking_bg_693 booking_admin_button booking_green_button booking_mark_fff booking_float_left">
                <div id="result_search" class="booking_float_left booking_margin_l_20"></div>
                <div class="booking_cleardiv"></div>
            </div>
        
        </div>
            
    </div>


        
    <a id="results" name="results"></a>
    
    <div id="action_bar" class="booking_margin_t_20 booking_bg_f6f booking_border_1 booking_border_solid booking_border_ccc booking_padding_10">
    	 
    	<div class="booking_float_right">          
            <a onclick="javascript:CSVexport();" class="booking_float_left booking_margin_l_20 booking_mark_333 booking_pointer booking_font_bold"><?php echo __( 'CSV Export', 'wp-booking-calendar' );?></a>
            <a style="display:none !important" onclick="javascript:resetSearch();" class="action_reset booking_float_left booking_margin_l_20 booking_mark_333 booking_pointer booking_font_bold"><?php echo __( 'Show today\'s reservations', 'wp-booking-calendar' );?></a>
        </div>
        <div class="booking_cleardiv"></div>
    </div>



    <form name="manage_reservations" action="" method="post">
        <input type="hidden" name="operation" />
        <input type="hidden" name="reservations[]" value=0 />
        
        <div class="booking_margin_t_20">
            <div id="table" class="booking_font_12">
            
                <!-- 
                =======================
                === table header ==
                =======================
                -->
                <div class="booking_border_b_1 booking_border_solid booking_border_ccc booking_bg_f6f booking_height_30 booking_line_30">
                	<!-- number -->
                    <div class="booking_float_left booking_width_3p">#</div>
                    
                    <!-- check -->
                    <div class="booking_float_left booking_width_3p"><input type="checkbox" name="selectAll" onclick="javascript:selectCheckbox('manage_reservations','reservations[]');" /></div>            
                    
                    <!-- date -->
                    <div class="booking_float_left booking_width_10p"><?php echo __( 'Date', 'wp-booking-calendar' );?>&nbsp;<a href="javascript:orderby('date','<?php echo $_SESSION["orderbyUserReservationDate"]; ?>');"><img src="<?php echo BOOKING_ADMIN_URL;?>images/orderby_<?php echo $_SESSION["orderbyUserReservationDate"];?>.gif" border=0 /></a></div>
                    
                    <!-- time -->
                    <div class="booking_float_left booking_width_10p"><?php echo __( 'Time', 'wp-booking-calendar' );?>&nbsp;<a href="javascript:orderby('time','<?php echo $_SESSION["orderbyUserReservationTime"]; ?>');"><img src="<?php echo BOOKING_ADMIN_URL;?>images/orderby_<?php echo $_SESSION["orderbyUserReservationTime"];?>.gif" border=0 /></a></div>
                    
                    <!-- seats -->          
                    <div class="booking_float_left booking_width_5p"><?php echo __( 'Seats', 'wp-booking-calendar' );?></div>
                    
                    <!-- surname -->
                    <div class="booking_float_left booking_width_20p"><?php echo __( 'Surname, name', 'wp-booking-calendar' );?></div>            
                    
                    <!-- email -->
                    <div class="booking_float_left booking_width_20p"><?php echo __( 'Email', 'wp-booking-calendar' );?></div>        
                    
                    <!-- confirmed -->
                    <div class="booking_float_left booking_width_5p"><?php echo __( 'Confirmed', 'wp-booking-calendar' );?></div>
                    
                    <!-- delete -->
                    <div class="booking_float_left booking_width_10p"></div>
                    
                    <!-- detail -->
                    <div class="booking_float_left booking_width_14p"></div>            
                    
                    <div class="booking_cleardiv"></div>
                    
                </div>
                    
                    
                    
                <?php                         
                $arrayReservations = $bookingListObj->getUsersReservationsList($_SESSION["qryUsersReservationsFilterString"],$_SESSION["qryUsersReservationsOrderString"],$reservation_user_id);                        
                $i=1;
                foreach($arrayReservations as $reservationId => $reservation) {		
                    if($reservation["slot_active"] == 0) {
                        $class="booking_table_row_red";
                    } else {													
                        if($i % 2) {
                            $class="booking_alternate_table_row_white";
                        } else {
                            $class="booking_alternate_table_row_grey";
                        }
                    }
                ?>
                
                
                <div id="row_<?php echo $reservationId; ?>" class="booking_border_b_1 booking_border_solid booking_border_ccc">
                
                	<!-- number -->
                    <div class="booking_float_left booking_width_3p booking_height_50 <?php echo $class; ?>">
                        <div class="booking_wh_inherit booking_table_cell booking_vertical_middle"><?php echo $i; ?></div>
                    </div>
                    
                    <!-- check -->
                    <div class="booking_float_left booking_width_3p booking_height_50 <?php echo $class; ?>">
                        <div class="booking_wh_inherit booking_table_cell booking_vertical_middle"><input type="checkbox" name="reservations[]" value="<?php echo $reservationId; ?>" onclick="javascript:disableSelectAll('manage_reservations',this.checked);" /></div>
                    </div> 
                    
                    <!-- date -->                 
                    <div class="booking_float_left booking_width_10p booking_height_50 <?php echo $class; ?>">
                        <div class="booking_wh_inherit booking_table_cell booking_vertical_middle">
                        	<?php
							if($bookingSettingObj->getDateFormat() == "UK") {
								$dateToSend = strftime('%d/%m/%Y',strtotime($reservation["reservation_date"]));
							} else if($bookingSettingObj->getDateFormat() == "EU") {
								$dateToSend = strftime('%Y/%m/%d',strtotime($reservation["reservation_date"]));
							} else {
								$dateToSend = strftime('%m/%d/%Y',strtotime($reservation["reservation_date"]));
							}
							?>
							<?php echo $dateToSend; ?>
                        </div>
                    </div>
                    
                    <!-- time -->
                    <div class="booking_float_left booking_width_10p booking_height_50 <?php echo $class; ?>">
                        <div class="booking_wh_inherit booking_table_cell booking_vertical_middle">
                        	<?php
							if($bookingSettingObj->getTimeFormat() == "12") {
								echo date('h:i a',strtotime(substr($reservation["reservation_time"],0,5)));
								
							} else {
								echo substr($reservation["reservation_time"],0,5);
								
							}
							
							?>
                        </div>
                    </div>
                    
                    <!-- seats -->
                    <div class="booking_float_left booking_width_5p booking_height_50 <?php echo $class; ?>">
                        <div class="booking_wh_inherit booking_table_cell booking_vertical_middle"><?php echo $reservation["reservation_seats"]; ?></div>
                    </div>
                    
                    <!-- surname -->
                    <div class="booking_float_left booking_width_20p booking_height_50 <?php echo $class; ?>">
                        <div class="booking_wh_inherit booking_table_cell booking_vertical_middle"><?php echo $reservation["reservation_surname"].", ".$reservation["reservation_name"]; ?></div>
                    </div>
                    
                    <!-- email -->
                    <div class="booking_float_left booking_width_20p booking_height_50 <?php echo $class; ?>">
                        <div class="booking_wh_inherit booking_table_cell booking_vertical_middle"><?php echo $reservation["reservation_email"]; ?></div>
                    </div>
                    
                    <!-- status -->
                    <div class="booking_float_left booking_width_5p booking_height_50 <?php echo $class; ?>">
                        <div class="booking_wh_inherit booking_table_cell booking_vertical_middle"><span id="conferma_<?php echo $reservationId; ?>"><?php if($reservation["reservation_confirmed"]=='1' && $reservation["reservation_cancelled"] == 0) { ?><img src="<?php echo BOOKING_ADMIN_URL;?>images/icons/published.png" border=0 /><?php } else if($reservation["reservation_cancelled"] == 0 && !$bookingReservationObj->isPassed(sha1($reservationId.$reservation["slot_id"])) && $confirm_function == 1){ ?><a href="javascript:confirmReservation(<?php echo $reservationId; ?>);"><img src="<?php echo BOOKING_ADMIN_URL;?>images/icons/unpublished.png" border=0 /></a><?php } else if($reservation["reservation_cancelled"] == 0) {?><img src="<?php echo BOOKING_ADMIN_URL;?>images/icons/unpublished.png" border=0 /><?php } else { ?><?php echo __( 'Cancelled', 'wp-booking-calendar' ); ?><?php } ?></span></div>
                    </div>
                    
                    <!-- delete -->
                    <div class="booking_float_left booking_width_10p booking_height_50 <?php echo $class; ?>">
                    	<?php
						if(!$bookingReservationObj->isPassed(sha1($reservationId.$reservation["slot_id"])) && $cancel_function == 1) {
							?>
							<div class="booking_wh_inherit booking_table_cell booking_vertical_middle"><a href="javascript:cancelItem(<?php echo $reservationId; ?>,'reservations','reservation_id');"><?php echo __( 'Cancel', 'wp-booking-calendar' ); ?></a></div>
							<?php
						}
						?>
                    </div>    
                    
                    <!-- detail -->                     
                    <div class="booking_float_left booking_width_14p booking_height_50 <?php echo $class; ?>">
                        <div class="booking_wh_inherit booking_table_cell booking_vertical_middle"><a href="?page=wp-booking-calendar-orders&reservation_id=<?php echo $reservationId; ?>"><?php echo __( 'Detail', 'wp-booking-calendar' );?></a></div>
                    </div>
                    
                    
                    <div class="booking_cleardiv"></div>
                </div>
                <?php 
                $i++;
                } ?>
            
            
            </div>
        </div>
        </form>
   <?php
   }
?>  
