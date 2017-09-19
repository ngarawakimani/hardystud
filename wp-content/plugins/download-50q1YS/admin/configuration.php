<?php
include 'common.php';
/*if(!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] == 20000) {
	header('Location: login.php');
}*/
if(isset($_POST["reservation_confirmation_mode"])) {	
	$bookingSettingObj->updateSettings();

}

$arrayTimezones = $bookingListObj->getTimezonesList();

?>

<!-- 
=======================
=== js ==
=======================
-->
<script language="javascript" type="text/javascript">
	var $wbc = jQuery;
	$wbc(function() {
		<?php
		if($bookingSettingObj->getReservationConfirmationMode() == "2") {
			?>			
			$wbc('#redirect_path_container').fadeIn();			
			<?php
			if(trim(get_option('wbc_redirect_confirmation_path'))!='') {
				?>
				$wbc('#add_redirect').attr("checked","checked");
				$wbc('#redirect_text').slideDown();
				<?php
			}
		}
		if($bookingSettingObj->getRecaptchaEnabled() == "1") {
			?>
			$wbc('#recaptcha_enabled').attr("checked","true");
			$wbc('#recaptcha_options').slideDown();
			$wbc('#recaptcha_public_key').attr("tmt:required","true");
			$wbc('#recaptcha_private_key').attr("tmt:required","true");
			<?php
		}
		if($bookingSettingObj->getReservationCancel() == "1") {
			?>			
			$wbc('#redirect_cancel_path_container').fadeIn();			
			<?php
			if(trim(get_option('wbc_redirect_cancel_path'))!='') {
				?>
				$wbc('#add_cancel_redirect').attr("checked","checked");
				$wbc('#redirect_cancel_text').slideDown();
				<?php
			}
		}
		?>
		showTermsFields(<?php echo $bookingSettingObj->getShowTerms(); ?>);
		showPaypalFields(<?php echo $bookingSettingObj->getPaypal(); ?>);
		showCustomOptions(<?php echo $bookingSettingObj->getSlotsUnlimited(); ?>);
		showRegistrationField(<?php echo $bookingSettingObj->getWordpressRegistration(); ?>);
	});
	function showRedirect(val) {
		if(val == 2) {
			$wbc('#redirect_path_container').fadeIn();
		} else {
			$wbc('#redirect_path_container').fadeOut();
		}
	}
	function showRecaptchaOptions(el) {
		if(el.checked) {
			$wbc('#recaptcha_options').slideDown();
			$wbc('#recaptcha_public_key').attr("tmt:required","true");
			$wbc('#recaptcha_private_key').attr("tmt:required","true");
		} else {
			$wbc('#recaptcha_options').slideUp();
			$wbc('#recaptcha_public_key').attr("tmt:required","false");
			$wbc('#recaptcha_private_key').attr("tmt:required","false");
		}
	}
	
	function showRedirectPath(el) {
		if(el.checked) {
			$wbc('#redirect_text').slideDown();
		} else {
			$wbc('#redirect_text').slideUp();
			$wbc('#redirect_confirmation_path').val('');
		}
	}
	
	function showCancelRedirect(el) {
		if(el.checked) {
			$wbc('#redirect_cancel_path_container').fadeIn();
		} else {
			$wbc('#redirect_cancel_path_container').fadeOut();
		}
	}
	function showCancelRedirectPath(el) {
		if(el.checked) {
			$wbc('#redirect_cancel_text').slideDown();
		} else {
			$wbc('#redirect_cancel_text').slideUp();
			$wbc('#redirect_cancel_path').val('');
		}
	}
	function showTermsFields(val) {
		if(val==1) {
			$wbc('#terms_fields').slideDown();
		} else {
			$wbc('#terms_fields').slideUp();
			
		}
	}
	function showPaypalFields(val) {
		if(val==1) {
			$wbc('#paypal_fields').slideDown();
		} else {
			$wbc('#paypal_fields').slideUp();
			
		}
	}
	
	function showRegistrationField(val) {
		if(val==1) {
			$wbc('#registration_fields').slideDown();
		} else {
			$wbc('#registration_fields').slideUp();
			
		}
	}
	

	function showCustomOptions(val) {
		if(val==2) {
			$wbc('#custom_options').slideDown();
		} else {
			$wbc('#custom_options').slideUp();
			
		}
	}
	
	function displayError(formNode,validators) {
		for(var i=0;i<validators.length;i++){
			if(validators[i].name == 'reservation_confirmation_mode') {
				$wbc('#reservation_confirmation_mode_label').css('color','#C00');
			}
		  
		 }
		 var error="";
		 for(var i=0;i<validators.length;i++){
		  error += "\r\n"+validators[i].message;
		 }
		 if(Trim(error)!= '') {
		 	alert(error);
		 }
	}
	
</script>

 <!-- 
=======================
=== main content ==
=======================
-->

<div class="booking_padding_20 booking_font_18 booking_line_percent booking_bg_fff">
        
        <!-- 
        =======================
        === form ==
        =======================
        -->
        
        <form name="editsettings" action="" method="post" tmt:validate="true" enctype="multipart/form-data" tmt:callback="displayError">           
                
              
                
        <!-- 
        =======================
        === Timezone ==
        =======================
        -->
        <div class="booking_font_bold"><label for="timezone"><?php echo esc_html(__('Timezone','wp-booking-calendar')); ?></label></div>
        <div class="booking_font_12"><?php echo esc_html(__('Your timezone to manage the time slots','wp-booking-calendar')); ?></div>
       
        <div class="booking_font_12 booking_margin_t_10">
            <select name="timezone" id="timezone" tmt:invalidvalue="" tmt:required="true" tmt:message="<?php echo esc_html(__('Select your timezone','wp-booking-calendar')); ?>">
                <option value=""><?php echo __('Please select your timezone','wp-booking-calendar'); ?></option>
                <?php
                foreach($arrayTimezones as $timezoneid => $timezone) {
                ?>
                    <option value="<?php echo $timezone["timezone_value"]; ?>" <?php if(trim($bookingSettingObj->getTimezone()) == trim($timezone["timezone_value"])) { echo 'selected="selected"'; }?>><?php echo $timezone["timezone_name"]; ?></option>
                <?php
                }
                ?>
            </select>
          
        </div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
        
        
        
        <!-- 
        =======================
        === Date format ==
        =======================
        -->
        <div class="booking_font_bold"><label for="date_format"><?php echo __('Choose calendar date format.','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('Switch between US, UK and EU date formats','wp-booking-calendar'); ?></div>
        
        <div class="booking_font_12 booking_margin_t_10">
           <select name="date_format">
             <option value="UK" <?php if($bookingSettingObj->getDateFormat()=="UK") { echo "selected"; }?>><?php echo __('UK - dd/mm/yyyy - week starts on Monday','wp-booking-calendar'); ?></option>
             <option value="US" <?php if($bookingSettingObj->getDateFormat()=="US") { echo "selected"; }?>><?php echo __('US - mm/dd/yyyy - week starts on Sunday','wp-booking-calendar'); ?></option>
             <option value="EU" <?php if($bookingSettingObj->getDateFormat()=="EU") { echo "selected"; }?>><?php echo __('EU - yyyy/mm/dd - week starts on Monday','wp-booking-calendar'); ?></option>
           </select>
        </div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
       
        
        
        <!--
        =======================
        === Time format ==
        =======================
        -->
        <div class="booking_font_bold"><label for="time_format"><?php echo __('Choose calendar time format.','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('Switch between 12-hour and 24-hour time formats','wp-booking-calendar'); ?></div>
     
        <div class="booking_font_12 booking_margin_t_10">
           <select name="time_format">
             <option value="12" <?php if($bookingSettingObj->getTimeFormat()=="12") { echo "selected"; }?>><?php echo __('12-hour time format with am/pm','wp-booking-calendar'); ?></option>
             <option value="24" <?php if($bookingSettingObj->getTimeFormat()=="24") { echo "selected"; }?>><?php echo __('24-hour time format','wp-booking-calendar'); ?></option>
           </select>
        </div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
          
          
               
        <!-- 
        =======================
        === Email Admin ==
        =======================
        -->
        <div class="booking_font_bold"><label for="email_reservation"><?php echo __('Admin Email','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('E-mail address where you\'ll receive reservation alerts','wp-booking-calendar'); ?></div>
     
        <div class="booking_font_12 booking_margin_t_10"><input type="text" id="email_reservation" class="booking_width_250 booking_height_30" name="email_reservation" value="<?php echo $bookingSettingObj->getEmailReservation(); ?>" tmt:required="true" tmt:message="<?php echo __('Specify your email address','wp-booking-calendar'); ?>"></div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
         
         
         
         
                
        <!-- 
        =======================
        === Email From ==
        =======================
        -->
        <div class="booking_font_bold"><label for="email_from_reservation"><?php echo __('Email "from"','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('Name and e-mail address shown in the field "From" in every e-mail sent to confirm the reservation to your customer','wp-booking-calendar'); ?></div>
        
        <div class="booking_font_12 booking_margin_t_10"><?php echo __('Sender name','wp-booking-calendar'); ?>&nbsp;<input type="text" class="booking_width_250 booking_height_30" id="name_from_reservation" name="name_from_reservation" value="<?php echo get_option('wbc_name_from_reservation'); ?>"></div>
        
        <div class="booking_font_12 booking_margin_t_10"><?php echo __('E-mail address','wp-booking-calendar'); ?>&nbsp;<input type="text" class="booking_width_250 booking_height_30" id="email_from_reservation" name="email_from_reservation" value="<?php echo $bookingSettingObj->getEmailFromReservation(); ?>" tmt:required="true" tmt:message="<?php echo __('Specify an email address "from"','wp-booking-calendar'); ?>"></div>
        
        
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
                
                
        <!-- 
        ===============================
        === confirmation mode select ==
        ===============================
        -->
        <div class="booking_font_bold"><label for="reservation_confirmation_mode" id="reservation_confirmation_mode_label"><?php echo __('Reservation: confirmation mode','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('Choose how to confirm reservations','wp-booking-calendar'); ?></div>
        
        <div class="booking_font_12 booking_margin_t_10">
            <select name="reservation_confirmation_mode" id="reservation_confirmation_mode" onchange="javascript:showRedirect(this.options[this.selectedIndex].value);" style="width:700px" tmt:invalidvalue="0" tmt:required="true" tmt:message="<?php echo __('Select reservation confirm mode','wp-booking-calendar'); ?>">
                <option value="0"><?php echo __('Select reservation confirmation mode','wp-booking-calendar'); ?></option>
                <option value="1" <?php if($bookingSettingObj->getReservationConfirmationMode() == "1") { echo 'selected="selected"'; }?>><?php echo __('Automatically - When a user book a time slot, it is automatically confirmed','wp-booking-calendar'); ?></option>
                <option value="2" <?php if($bookingSettingObj->getReservationConfirmationMode() == "2") { echo 'selected="selected"'; }?>><?php echo __('By a verification e-mail - When a user book a time slot, he has to confirm the reservation by clicking on a link sent to him by e-mail','wp-booking-calendar'); ?></option>
                <option value="3" <?php if($bookingSettingObj->getReservationConfirmationMode() == "3") { echo 'selected="selected"'; }?>><?php echo __('Admin confirm - You must confirm the reservation in the reservations area','wp-booking-calendar'); ?></option>
            </select>
            
            <div id="redirect_path_container" class="booking_margin_t_20" style="display:none !important">
                <div class="booking_margin_t_10"><?php echo __('Currently in the confirmation page, the user will be pointed to the calendar. If you want to modify the destination page click here:','wp-booking-calendar'); ?> <input type="checkbox" name="add_redirect" id="add_redirect" value="1" onclick="javascript:showRedirectPath(this);" /></div>
                <div class="redirect_text" style="display:none !important" id="redirect_text"><?php echo __('Set the url (starting with: http://):','wp-booking-calendar'); ?>&nbsp;<input type="text" class="short_input_box" name="redirect_confirmation_path" id="redirect_confirmation_path" value="<?php echo get_option('wbc_redirect_confirmation_path'); ?>"/></div>
            </div>
                
        </div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
        
        
	<!-- 
        =======================
        === page after booking setting ==
        =======================
        -->
        <div class="booking_font_bold"><label for="reservation_cancel"><?php echo __('Redirect page after booking','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('(starting with: http://)','wp-booking-calendar'); ?></div>
        
        <div class="booking_font_12 booking_margin_t_10"><input type="text" class="booking_width_250 booking_height_30" id="redirect_booking_path" name="redirect_booking_path" value="<?php echo get_option('wbc_redirect_booking_path'); ?>"></div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>             
        <!-- 
        =================================
        === cancel reservation setting ==
        =================================
        -->
        <div class="booking_font_bold"><label for="reservation_cancel"><?php echo __('Reservation: cancellation','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('Choose if the customer will be able to cancel his reservation by a link in the confirmation email he receives (when you decide to activate this function you can check the email to change the text)','wp-booking-calendar'); ?></div>
        
        <div class="booking_font_12 booking_margin_t_10">
            <input type="checkbox" name="reservation_cancel" id="reservation_cancel" value ="1" <?php if($bookingSettingObj->getReservationCancel() == "1") { echo "checked"; } ?> onclick="javascript:showCancelRedirect(this);"/>&nbsp;<?php echo __('enabled','wp-booking-calendar'); ?>
            <div id="redirect_cancel_path_container" style="display:none !important">
                <div class="booking_margin_t_10"><?php echo __('Currently in the cancellation page, the user will be pointed to the calendar. If you want to modify the destination page click here:','wp-booking-calendar'); ?> <input type="checkbox" name="add_cancel_redirect" id="add_cancel_redirect" value="1" onclick="javascript:showCancelRedirectPath(this);" class="booking_margin_t_10" /></div>
                <div class="booking_margin_t_10" style="display:none !important" id="redirect_cancel_text">
                	<div class="booking_float_left booking_height_30 booking_line_30"><?php echo __('Set the url (starting with: http://):','wp-booking-calendar'); ?></div>
                    <div class="booking_float_left booking_margin_l_10"><input type="text" class="short_input_box" name="redirect_cancel_path" id="redirect_cancel_path" value="<?php echo get_option('wbc_redirect_cancel_path'); ?>"/></div>
                    <div class="booking_cleardiv"></div>
                </div>
            </div>
                
        </div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
        
        
                
        <!-- 
        =======================
        === google recaptcha ==
        =======================
        -->
        <div class="booking_font_bold"><label for="recaptcha_enabled"><?php echo __('Google recaptcha','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('Code verification to avoid spam','wp-booking-calendar'); ?></div>
       
        
        <div class="booking_float_left booking_margin_t_12"><input type="checkbox" name="recaptcha_enabled" id="recaptcha_enabled" value="1" onclick="javascript:showRecaptchaOptions(this);"/></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12"><?php echo __('enabled','wp-booking-calendar'); ?></div>
        
        <div class="booking_cleardiv"></div>
        
        <div id="recaptcha_options" style="display:none !important">
            <div class="booking_font_bold booking_margin_t_20"><label for="recaptcha_version"><?php echo __('Recaptcha version','wp-booking-calendar'); ?></label></div>
            <div class="booking_font_12"><?php echo __('Please select the Recaptcha version for which you have the keys. If you don\'t have keys yet, you will be using the new version (v2)','wp-booking-calendar'); ?> </div>

            <div class="booking_font_12">
                <div class="booking_float_left"><input type="radio" name="recaptcha_version" value="1" <?php if($bookingSettingObj->getRecaptchaVersion() == 1) { echo "checked"; }?> /></div>
                <div class="booking_float_left booking_margin_l_10"><?php echo __('v1 (old one)','wp-booking-calendar'); ?></div>
                <div class="booking_float_left booking_margin_l_10"><input type="radio" name="recaptcha_version" value="2" <?php if($bookingSettingObj->getRecaptchaVersion() == 0) { echo "checked"; }?> /></div>
                <div class="booking_float_left booking_margin_l_10"><?php echo __('v2 (new one)','wp-booking-calendar'); ?></div>
                <div class="booking_cleardiv"></div>
            </div>


            <div class="booking_font_bold booking_margin_t_20"><label for="recaptcha_public_key"><?php echo __('Public Key','wp-booking-calendar'); ?></label></div>
            <div class="booking_font_12"><?php echo __('It must be associated with your site domain, go to Recaptcha site to get it:','wp-booking-calendar'); ?>  <a href="http://www.google.com/recaptcha">http://www.google.com/recaptcha</a></div>
            
            <div class="booking_font_12 booking_margin_t_10"><input type="text" class="booking_width_fluid" id="recaptcha_public_key" name="recaptcha_public_key" value="<?php echo $bookingSettingObj->getRecaptchaPublicKey(); ?>" tmt:required="false" tmt:message="<?php echo __('Insert Google recaptcha public key','wp-booking-calendar'); ?>" onblur="javascript:checkRecaptchaPublic();"></div>
        
            <div class="booking_font_bold booking_margin_t_20"><label for="recaptcha_private_key"><?php echo __('Private key','wp-booking-calendar'); ?></label></div>
            <div class="booking_font_12"><?php echo __('It must be associated with your site domain, go to Recaptcha site to get it:','wp-booking-calendar'); ?>  <a href="http://www.google.com/recaptcha">http://www.google.com/recaptcha</a></div>
            
            <div class="booking_font_12 booking_margin_t_10"><input type="text" class="booking_width_fluid" id="recaptcha_private_key" name="recaptcha_private_key" value="<?php echo $bookingSettingObj->getRecaptchaPrivateKey(); ?>" tmt:required="false" tmt:message="<?php echo __('Insert Google recaptcha private key','wp-booking-calendar'); ?>"></div>
        </div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
                
                
                
        <!-- 
        ====================================
        === add terms and condition check ==
        ====================================
        -->
        <div class="booking_font_bold"><label for="show_terms"><?php echo __('Add terms and condition check','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo  __('Adding this control, the user must check it to be able to book. It\'s mandatory to insert at least a label to enable this option','wp-booking-calendar'); ?></div>
        
        <div class="booking_font_12">
        	<div class="booking_float_left"><input type="radio" name="show_terms" value="1" <?php if($bookingSettingObj->getShowTerms() == 1) { echo "checked"; }?> onclick="javascript:showTermsFields(1);"/></div>
            <div class="booking_float_left booking_margin_l_10"><?php echo __('YES','wp-booking-calendar'); ?></div>
            <div class="booking_float_left booking_margin_l_10"><input type="radio" name="show_terms" value="0" <?php if($bookingSettingObj->getShowTerms() == 0) { echo "checked"; }?> onclick="javascript:showTermsFields(0);"/></div>
            <div class="booking_float_left booking_margin_l_10"><?php echo __('NO','wp-booking-calendar'); ?></div>
            <div class="booking_cleardiv"></div>
            <div id="terms_fields" class="booking_margin_t_20" style="display:none !important">
            	<div><?php echo __('Label to show:','wp-booking-calendar'); ?></div>
                <div class="booking_margin_t_10"><input type="text" class="booking_width_fluid" name="terms_label" id="terms_label" value="<?php echo get_option('wbc_terms_label'); ?>"/></div>
                
                <div class="booking_margin_t_20"><?php echo __('Link to terms and conditions (starting with \'http://\'):','wp-booking-calendar'); ?></div>
                <div class="booking_margin_t_10"><input type="text" class="booking_width_fluid" name="terms_link" id="terms_link" value="<?php echo get_option('wbc_terms_link'); ?>"/></div>
                <div class="booking_cleardiv"></div>
            </div>
        </div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
        
        
        
        
        <!-- 
        =======================
        === slots selection ==
        =======================
        -->
        <div class="booking_font_bold"><label for="slot_selection"><?php echo __('Slot selection','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('(choose if customer can reserve only one or multiple slots at once)','wp-booking-calendar'); ?></div>
        
        <div class="booking_margin_t_10 booking_font_12">
            <select name="slot_selection" id="slot_selection">
                <option value="0" <?php if($bookingSettingObj->getSlotSelection()=="0") { echo 'selected'; }?>><?php echo __('Multiple selection','wp-booking-calendar'); ?></option>
                <option value="1" <?php if($bookingSettingObj->getSlotSelection()=="1") { echo 'selected'; }?>><?php echo __('Only one','wp-booking-calendar'); ?></option>
                
            </select>
        </div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
        
        
        
        
        
        
        
        <!-- 
        =======================
        === slots unlimited ==
        =======================
        -->
        <div class="booking_font_bold"><label for="slots_unlimited"><?php echo __('Unlimited reservations','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('Choose if slots can have unlimited reservations or just one.','wp-booking-calendar'); ?></div>
        
        
        <div class="booking_margin_t_10 booking_font_12">
            <select name="slots_unlimited" id="slots_unlimited" onchange="javascript:showCustomOptions(this.options[this.selectedIndex].value);">
                <option value="0" <?php if($bookingSettingObj->getSlotsUnlimited()=="0") { echo 'selected'; }?>><?php echo __('one reservation per slot','wp-booking-calendar'); ?></option>
    <option value="2" <?php if($bookingSettingObj->getSlotsUnlimited()=="2") { echo 'selected'; }?>><?php echo __('use the number set in slot insertion','wp-booking-calendar'); ?></option>
                <option value="1" <?php if($bookingSettingObj->getSlotsUnlimited()=="1") { echo 'selected'; }?>><?php echo __('unlimited reservations per slot','wp-booking-calendar'); ?></option>
                
            </select>
	<div id="custom_options" class="redirect_text" style="display:none">
                    	<div class="booking_float_left booking_width_400 booking_height_30 booking_line_30 booking_margin_t_10"><?php echo __('Show available slots seats instead of available slots number','wp-booking-calendar'); ?>:</div>
                        <div class="booking_float_left booking_margin_t_10"><input type="radio" name="show_slots_seats" value="1" <?php if($bookingSettingObj->getShowSlotsSeats() == 1) { echo "checked"; }?> />&nbsp;<?php echo __('YES','wp-booking-calendar'); ?>&nbsp;<input type="radio" name="show_slots_seats" value="0" <?php if($bookingSettingObj->getShowSlotsSeats() == 0) { echo "checked"; }?>/>&nbsp;<?php echo __('NO','wp-booking-calendar'); ?></div>
                        
                        <div class="booking_cleardiv"></div>
                    </div>
        </div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
        
        
        
        
        
                
        <!-- 
        ========================
        === show booked slots ==
        ========================
        -->
        <div class="booking_font_bold"><label for="show_booked_slots"><?php echo __('Show booked slots','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('Choose whether to show or not the booked slots. This works only if reservations per slot are not unlimited','wp-booking-calendar'); ?></div>
        
        <div class="booking_float_left booking_margin_t_10"><input type="radio" name="show_booked_slots" value="1" <?php if($bookingSettingObj->getShowBookedSlots() == 1) { echo "checked"; }?>/></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12"><?php echo __('YES','wp-booking-calendar'); ?></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10"><input type="radio" name="show_booked_slots" value="0" <?php if($bookingSettingObj->getShowBookedSlots() == 0) { echo "checked"; }?>/></div>
        <div class="booking_float_left booking_margin_t_10  booking_margin_l_10 booking_font_12"><?php echo __('NO','wp-booking-calendar'); ?></div>
        <div class="booking_cleardiv"></div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
         
         
         
                
        <!-- 
        =======================
        === slots popup ==
        =======================
        -->
        <div class="booking_font_bold"><label for="slots_popup_enabled"><?php echo __('Available time slots preview','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('Show the preview of available time slots on calendar days rollover','wp-booking-calendar'); ?></div>
       
        <div class="booking_float_left booking_margin_t_10"><input type="radio" name="slots_popup_enabled" id="slots_popup_enabled" value="1" <?php if($bookingSettingObj->getSlotsPopupEnabled() == 1) { echo "checked"; } ?>/></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12"><?php echo __('enabled','wp-booking-calendar'); ?></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10"><input type="radio" name="slots_popup_enabled" id="slots_popup_enabled" value="0" <?php if($bookingSettingObj->getSlotsPopupEnabled() == 0) { echo "checked"; } ?>/></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12"><?php echo __('disabled','wp-booking-calendar'); ?></div>
        <div class="booking_cleardiv"></div>
        
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
        
        <!-- 
        ==============================
        === show category selection ==
        ==============================
        -->
        <div class="booking_font_bold"><label for="show_category_selection"><?php echo __('Show category selection','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('Choose whether to show or not the category selection drop down at the top right of the calendar when general shortcode <strong>[wp_booking_calendar]</strong> is used','wp-booking-calendar'); ?></div>
        
        <div class="booking_float_left booking_margin_t_10"><input type="radio" name="show_category_selection" value="1" <?php if($bookingSettingObj->getShowCategorySelection() == 1) { echo "checked"; }?>/></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12"><?php echo __('YES','wp-booking-calendar'); ?></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10"><input type="radio" name="show_category_selection" value="0" <?php if($bookingSettingObj->getShowCategorySelection() == 0) { echo "checked"; }?>/></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12"><?php echo __('NO','wp-booking-calendar'); ?></div>
        <div class="booking_cleardiv"></div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
        
        
        <!-- 
        ==============================
        === show calendar selection ==
        ==============================
        -->
        <div class="booking_font_bold"><label for="show_calendar_selection"><?php echo __('Show calendar selection','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('Choose whether to show or not the calendar selection drop down at the top right of the calendar when general or category shortcode id used','wp-booking-calendar'); ?></div>
        
        <div class="booking_float_left booking_margin_t_10"><input type="radio" name="show_calendar_selection" value="1" <?php if($bookingSettingObj->getShowCalendarSelection() == 1) { echo "checked"; }?>/></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12"><?php echo __('YES','wp-booking-calendar'); ?></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10"><input type="radio" name="show_calendar_selection" value="0" <?php if($bookingSettingObj->getShowCalendarSelection() == 0) { echo "checked"; }?>/></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12"><?php echo __('NO','wp-booking-calendar'); ?></div>
        <div class="booking_cleardiv"></div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
                
 		<!-- 
        ===========================
        === Calendar first month ==
        ===========================
        -->
        <div class="booking_font_bold"><label for="show_first_filled_month"><?php echo __('Show the first not empty month by default','wp-booking-calendar'); ?></label></div>
         <div class="booking_font_12"><?php echo __('Choose whether to start the calendar from the first month which have slots. If Set to "NO" the first visible month will be the current month.','wp-booking-calendar'); ?></div>
        
        <div class="booking_float_left booking_margin_t_10"><input type="radio" name="show_first_filled_month" value="1" <?php if($bookingSettingObj->getShowFirstFilledMonth() == 1) { echo "checked"; }?>/></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12"><?php echo __('YES','wp-booking-calendar'); ?></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10"><input type="radio" name="show_first_filled_month" value="0" <?php if($bookingSettingObj->getShowFirstFilledMonth() == 0) { echo "checked"; }?>/></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12"><?php echo __('NO','wp-booking-calendar'); ?></div>
        <div class="booking_cleardiv"></div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>              
        <!-- 
        ===========================
        === Calendar month limit ==
        ===========================
        -->
        <div class="booking_font_bold"><label for="calendar_month_limit"><?php echo __('Calendar months view','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('Choose if you want to limit the number of past and future months shown in the calendar. Leave -1 if you don\'t want to set this limit','wp-booking-calendar'); ?></div>
        
        <div class="booking_float_left booking_margin_t_10 booking_font_12 booking_width_100"><?php echo __('Past months:','wp-booking-calendar'); ?></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12 booking_width_100"><input type="text" class="booking_width_60" id="calendar_month_limit_past" name="calendar_month_limit_past" value="<?php echo $bookingSettingObj->getCalendarMonthLimitPast(); ?>"></div>
        <div class="booking_cleardiv"></div>
        
        <div class="booking_float_left booking_margin_t_10 booking_font_12 booking_width_100"><?php echo __('Future months:','wp-booking-calendar'); ?></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12 booking_width_100"><input type="text" class="booking_width_60" id="calendar_month_limit_future" name="calendar_month_limit_future" value="<?php echo $bookingSettingObj->getCalendarMonthLimitFuture(); ?>"></div>
        <div class="booking_cleardiv"></div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
           
           
           
                
        <!-- 
        ================
        === book from and to ==
        ================
        -->
        <div class="booking_font_bold"><label for="book_from"><?php echo __('Choose when users are able to book a slot','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('Insert the minimum number of days that a user has to book before the slot date in order to get admitted. Leave 0 if he can book even at the last minute.','wp-booking-calendar'); ?></div>
        
        <div class="booking_margin_t_10"><input type="text" class="long_input_box" id="book_from" name="book_from" value="<?php echo $bookingSettingObj->getBookFrom(); ?>"></div>
        
        <div class="booking_font_12 booking_margin_t_10"><?php echo __('Insert the maximum number of days that a user can book when landing on the calendar. Leave 0 if he can book at any date.','wp-booking-calendar'); ?></div>
        
        <div class="booking_margin_t_10"><input type="text" class="long_input_box" id="book_to" name="book_to" value="<?php echo $bookingSettingObj->getBookTo(); ?>"></div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
        
        <!-- 
        ===========================
        === Show price ==
        ===========================
        -->
        
        <div class="booking_font_bold"><label for="paypal_display_price"><?php echo __('Display prices in booking page','wp-booking-calendar'); ?>:</label></div>
      
        <div class="booking_float_left booking_margin_t_10"><input type="radio" name="paypal_display_price" value="1" <?php if($bookingSettingObj->getPaypalDisplayPrice() == 1) { echo "checked"; }?> /></div>
		<div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12"><?php echo __('YES','wp-booking-calendar'); ?></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10"><input type="radio" name="paypal_display_price" value="0" <?php if($bookingSettingObj->getPaypalDisplayPrice() == 0) { echo "checked"; }?>/></div>
		<div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12"><?php echo __('NO','wp-booking-calendar'); ?></div>
        <div class="booking_cleardiv"></div>
        <div class="booking_float_left booking_margin_t_10 booking_font_12"><?php echo __('Select your currency','wp-booking-calendar'); ?>:</div>
       <div class="booking_float_left booking_margin_t_10 booking_font_12 booking_margin_l_10">
             <select name="paypal_currency">
                <option value=""><?php echo __('choose','wp-booking-calendar'); ?></option>
                <?php
                $arrayCurrencies = $bookingListObj->getPaypalCurrencyList();
                foreach($arrayCurrencies as $currencyId => $currency) {
                    ?>
                    <option value="<?php echo $currency["currency_code"]; ?>" <?php if($bookingSettingObj->getPaypalCurrency() == $currency["currency_code"]) { echo "selected"; }?>><?php echo $currency["currency_name"]; ?></option>
                    <?php
                }
                ?>
            </select> 
        </div>
        <div class="booking_cleardiv"></div>
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
        
        
        <!-- 
        =============
        === paypal ==
        =============
        -->
        <div class="booking_font_bold"><label for="show_terms"><?php echo __('Enable Paypal payment','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('Activate this option if you want people to pay the booking fee with Paypal. You must complete all the fields below to activate this service.','wp-booking-calendar'); ?><br /><?php echo __('Note that if you activate IPN','wp-booking-calendar'); ?> (<a href="http://www.paypalobjects.com/en_US/ebook/PP_OrderManagement_IntegrationGuide/ipn.html#1071087" target="_blank">Instant Payment Notification</a>) <?php echo __('in your Paypal profile the system will automatically confirm reservations after payments even if the user closes the browser before being redirected to the Booking Calendar. In the Notification URL text box just put your WP site address.','wp-booking-calendar'); ?></div>
        
        
        <div class="booking_float_left booking_margin_t_10 booking_font_12"><input type="radio" name="paypal" value="1" <?php if($bookingSettingObj->getPaypal() == 1) { echo "checked"; }?> onclick="javascript:showPaypalFields(1);"/></div>
        <div class="booking_float_left booking_margin_t_10 booking_font_12 booking_margin_l_10"><?php echo __('YES','wp-booking-calendar'); ?></div>
        <div class="booking_float_left booking_margin_t_10 booking_font_12 booking_margin_l_10"><input type="radio" name="paypal" value="0" <?php if($bookingSettingObj->getPaypal() == 0) { echo "checked"; }?> onclick="javascript:showPaypalFields(0);"/></div>
        <div class="booking_float_left booking_margin_t_10 booking_font_12 booking_margin_l_10"><?php echo __('NO','wp-booking-calendar'); ?></div>
        <div class="booking_cleardiv"></div>
        
        <div id="paypal_fields" class="redirect_text" style="display:none !important">
        	<div class="booking_float_left booking_margin_t_10 booking_font_12"><?php echo __('Insert your paypal email address','wp-booking-calendar'); ?></div>
            <div class="booking_float_left booking_margin_t_10 booking_font_12 booking_margin_l_10"><input type="text" class="short_input_box" name="paypal_account" id="paypal_account" value="<?php echo $bookingSettingObj->getPaypalAccount(); ?>"/></div>
            <div class="booking_cleardiv"></div>
            
            <div class="booking_float_left booking_margin_t_10 booking_font_12"><?php echo __('Select your country','wp-booking-calendar'); ?>:</div>
            <div class="booking_float_left booking_margin_t_10 booking_font_12 booking_margin_l_10">
            	<select name="paypal_locale">
                    <option value=""><?php echo __('choose','wp-booking-calendar'); ?></option>
                    <?php
                    $arrayLocales = $bookingListObj->getPaypalLocaleList();
                    foreach($arrayLocales as $localeId => $locale) {
                        ?>
                        <option value="<?php echo $locale["locale_code"]; ?>" <?php if($bookingSettingObj->getPaypalLocale() == $locale["locale_code"]) { echo "selected"; }?>><?php echo $locale["locale_country"]; ?></option>
                        <?php
                    }
                    ?>
                </select>
           </div>
           <div class="booking_cleardiv"></div>
           
           <div class="booking_float_left booking_margin_t_10 booking_font_12"><?php echo __('Do you want the reservations to be automatically confirmed after Paypal payment?','wp-booking-calendar'); ?>:</div>
           <div class="booking_float_left booking_font_12 booking_margin_l_10">
           		<div class="booking_float_left booking_margin_t_10 booking_font_12"><input type="radio" name="reservation_confirmation_mode_override" value="1" <?php if($bookingSettingObj->getReservationConfirmationModeOverride() == 1) { echo "checked"; }?> /></div>
                <div class="booking_float_left booking_margin_t_10 booking_font_12 booking_margin_l_10"><?php echo __('YES','wp-booking-calendar'); ?></div>
                <div class="booking_float_left booking_margin_t_10 booking_font_12 booking_margin_l_10"><input type="radio" name="reservation_confirmation_mode_override" value="0" <?php if($bookingSettingObj->getReservationConfirmationModeOverride() == 0) { echo "checked"; }?>/></div>
                <div class="booking_float_left booking_margin_t_10 booking_font_12 booking_margin_l_10"><?php echo __('NO','wp-booking-calendar'); ?></div>
                <div class="booking_cleardiv"></div>
           </div>
           <div class="booking_cleardiv"></div>
           
           <div class="booking_font_bold booking_margin_t_10"><label for="reservation_after_payment"><?php echo __('Do you want the reservations to be stored in the system only after Paypal Payment?','wp-booking-calendar'); ?></label></div>
            <div class="booking_font_12"><?php echo __('Activating this option, the reservations will be stored into the system ONLY after payment. If Paypal doesn\'t return the payment result, whether the connection is lost, or due to a malfunction, a breakdown, the reservation will be lost and the slot will still be available.','wp-booking-calendar'); ?></div>
            
            
            <div class="booking_float_left booking_margin_t_10 booking_font_12"><input type="radio" name="reservation_after_payment" value="1" <?php if($bookingSettingObj->getReservationAfterPayment() == 1) { echo "checked"; }?>/></div>
            <div class="booking_float_left booking_margin_t_10 booking_font_12 booking_margin_l_10"><?php echo __('YES','wp-booking-calendar'); ?></div>
            <div class="booking_float_left booking_margin_t_10 booking_font_12 booking_margin_l_10"><input type="radio" name="reservation_after_payment" value="0" <?php if($bookingSettingObj->getReservationAfterPayment() == 0) { echo "checked"; }?>/></div>
            <div class="booking_float_left booking_margin_t_10 booking_font_12 booking_margin_l_10"><?php echo __('NO','wp-booking-calendar'); ?></div>
            <div class="booking_cleardiv"></div>
            
            
            
        </div>
        
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
         
        
        
        
        
        <!-- 
        ================
        === form text ==
        ================
        -->
        <div class="booking_font_bold"><label for="form_text"><?php echo __('Additional text for booking page','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('It will be displayed under the date','wp-booking-calendar'); ?></div>
        
        <div class=""><input type="text" class="long_input_box" id="form_text" name="form_text" value="<?php echo get_option('wbc_form_text'); ?>"></div>
        
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
        
        
        <!-- 
        ========================
        === wordpress users ==
        ========================
        -->
        <div class="booking_font_bold"><label for="wordpress_registration"><?php echo __('Allow booking to WP registered users only and allow WP registration through this plugin.','wp-booking-calendar'); ?></label></div>
        <div class="booking_font_12"><?php echo __('Activating this option people will be able to register to your WP site and only WP registered users will be able to book from your Booking Calendar. You can choose the role for these users when registrating through this plugin.','wp-booking-calendar'); ?></div>
        
        <div class="booking_float_left booking_margin_t_10"><input type="radio" name="wordpress_registration" value="1" <?php if($bookingSettingObj->getWordpressRegistration() == 1) { echo "checked"; }?> onclick="javascript:showRegistrationField(1);"/></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10 booking_font_12"><?php echo __('YES','wp-booking-calendar'); ?></div>
        <div class="booking_float_left booking_margin_t_10 booking_margin_l_10"><input type="radio" name="wordpress_registration" value="0" <?php if($bookingSettingObj->getWordpressRegistration() == 0) { echo "checked"; }?> onclick="javascript:showRegistrationField(0);"/></div>
        <div class="booking_float_left booking_margin_t_10  booking_margin_l_10 booking_font_12"><?php echo __('NO','wp-booking-calendar'); ?></div>
        <div class="booking_cleardiv"></div>
        
        <div id="registration_fields" class="redirect_text" style="display:none !important">
        	
            <div class="booking_float_left booking_margin_t_10 booking_font_12"><?php echo __('Select the role for users who register to your site through the plugin','wp-booking-calendar'); ?>:</div>
            <div class="booking_float_left booking_margin_t_10 booking_font_12 booking_margin_l_10">
            	<select name="users_role">
                    <?php
					global $wp_roles;
     				$roles = $wp_roles->get_names();
                    foreach($roles as $role) {
   						if(strtolower($role) != 'administrator') {
							?>
							<option value="<?php echo strtolower($role);?>" <?php if(strtolower($bookingSettingObj->getUsersRole()) == strtolower($role)) { echo "selected"; }?>><?php echo $role;?></option>
							<?php
						}
                    }
                    ?>
                </select>
           </div>
           <div class="booking_cleardiv"></div>
           
           <div class="booking_float_left booking_margin_t_10 booking_font_12"><?php echo __('Choose the text to show in the registration and login form','wp-booking-calendar'); ?>:</div>
            <div class="booking_float_left booking_margin_t_10 booking_font_12 booking_margin_l_10">
            	<input type="text" class="long_input_box" id="registration_text" name="registration_text" value="<?php echo get_option('wbc_registration_text'); ?>">
           </div>
           <div class="booking_cleardiv"></div>
           
           
            
            
                
            
        </div>
        <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
        
         <!-- 
        =======================
        === control buttons ==
        =======================
        -->
        <div class="booking_bg_333 booking_padding_10">
            <!-- cancel -->
            <div class="booking_float_left"><a href="javascript:document.location.href='?page=wp-booking-calendar-welcome';" class="booking_bg_ccc booking_admin_button booking_grey_button booking_mark_fff"><?php echo __('CANCEL','wp-booking-calendar'); ?></a></div>
            <div class="booking_float_left booking_margin_l_20"><input type="submit" name="saveunpublish" value="<?php echo __('SAVE','wp-booking-calendar'); ?>" class="booking_bg_693 booking_admin_button booking_green_button booking_mark_fff"></div>
            <div class="booking_cleardiv"></div>
            
        </div>
            
        </form>
         
        

</div>
