<?php 
include 'common.php';

if($bookingSettingObj->getRecaptchaEnabled() == "1") {
    if($bookingSettingObj->getRecaptchaVersion() == 1) {
	require_once('include/recaptchalib.php');
    }
}
$publickey = $bookingSettingObj->getRecaptchaPublicKey();
  
if((!isset($_GET["calendar_id"]) || $_GET["calendar_id"] == 0) && (!isset($_GET["category_id"]) || $_GET["category_id"] == 0)) {
	/*get default category and default calendar of the category*/
	$bookingCategoryObj->getDefaultCategory();
	$bookingCalendarObj->getDefaultCalendar($bookingCategoryObj->getCategoryId());
	
} else if(isset($_GET["calendar_id"]) && $_GET["calendar_id"] > 0) {
	$bookingCalendarObj->setCalendar($_GET["calendar_id"]);
	$bookingCategoryObj->setCategory($bookingCalendarObj->getCalendarCategoryId());
} else if(isset($_GET["category_id"]) && $_GET["category_id"]>0) {
	$bookingCategoryObj->setCategory($_GET["category_id"]);
	$bookingCalendarObj->getDefaultCalendar($bookingCategoryObj->getCategoryId());
}
 ?>
<div id="wp_booking_calendar_main_container">

    <style>
        #wp_booking_calendar_main_container *:not(.fa) {
            <?php echo str_replace(";","",$bookingSettingObj->getGoogleFontCssCode()); ?> !important;
        }
        .booking_month_navigation_button_custom {
            background-color:<?php echo $bookingSettingObj->getMonthNavigationButtonBg(); ?> !important;
            color: <?php echo $bookingSettingObj->getMonthNavigationButtonColor(); ?> !important;
        }
        .booking_month_navigation_button_custom:hover {
            background-color:<?php echo $bookingSettingObj->getMonthNavigationButtonBgHover(); ?> !important;
            color:<?php echo $bookingSettingObj->getMonthNavigationButtonColorHover(); ?> !important;
        }
        .booking_month_container_custom {
            background-color:<?php echo $bookingSettingObj->getMonthContainerBg(); ?> !important;
        }
        .booking_month_name_custom {
            color: <?php echo $bookingSettingObj->getMonthNameColor(); ?> !important;
        }
        .booking_year_name_custom {
            color: <?php echo $bookingSettingObj->getYearNameColor(); ?> !important;
        }
        .booking_weekdays_container_custom {
            background-color: <?php echo $bookingSettingObj->getDayNamesBg(); ?> !important;
        }
        .booking_weekdays_custom {
            color: <?php echo $bookingSettingObj->getDayNamesColor(); ?> !important;
        }
        .booking_field_input_custom {
            background-color: <?php echo $bookingSettingObj->getFieldInputBg(); ?> !important;
            color: <?php echo $bookingSettingObj->getFieldInputColor(); ?> !important;
        }
        .booking_book_now_custom {
            background-color: <?php echo $bookingSettingObj->getBookNowButtonBg(); ?> !important;
            color: <?php echo $bookingSettingObj->getBookNowButtonColor(); ?> !important;
        }
        .booking_book_now_custom:hover {
            background-color: <?php echo $bookingSettingObj->getBookNowButtonBgHover(); ?> !important;
            color: <?php echo $bookingSettingObj->getBookNowButtonColorHover(); ?> !important;
        }
        .booking_clear_custom {
            background-color: <?php echo $bookingSettingObj->getClearButtonBg(); ?> !important;
            color: <?php echo $bookingSettingObj->getClearButtonColor(); ?> !important;
        }
        .booking_clear_custom:hover {
            background-color: <?php echo $bookingSettingObj->getClearButtonBgHover(); ?> !important;
            color: <?php echo $bookingSettingObj->getClearButtonColorHover(); ?> !important;
        }
        .booking_day_container_custom a {
            border: 1px <?php echo $bookingSettingObj->getDayBorder(); ?> #ccc !important;
        }
    </style><!-- ===============================================================
        js
    ================================================================ -->

    <script language="javascript" type="text/javascript">
        jQuery.noConflict();
        var $wbc = jQuery;
        var currentMonth;
        var currentYear;
        var pageX;
        var pageY;
        var today= new Date();
        var recaptchaVersion = <?php echo $bookingSettingObj->getRecaptchaVersion(); ?>;
        <?php
        if($bookingSettingObj->getShowFirstFilledMonth() == 0) {
            ?>
            var newday= new Date();
            <?php
        } else {
            ?>
            var newday = new Date(<?php echo $bookingCalendarObj->getFirstFilledMonth($bookingCalendarObj->getCalendarId()); ?>);
            <?php
        }
        ?>

        $wbc(function() {
            $wbc('#booking_back_today').css("display","none");
            getBookingMonthCalendar((newday.getMonth()+1),newday.getFullYear(),'<?php echo $bookingCalendarObj->getCalendarId(); ?>','<?php echo $publickey; ?>');
            <?php
            if($bookingSettingObj->getRecaptchaEnabled() == "1" && $bookingSettingObj->getRecaptchaVersion() == 1) {
                ?>
                Recaptcha.create("<?php echo $publickey; ?>",
                    "captcha",
                    {
                      theme: "<?php echo $bookingSettingObj->getRecaptchaStyle();?>",
                      callback: Recaptcha.focus_response_field
                    }
               );
          <?php
            }
            ?>
            $wbc('#reg_button').bind('click',function() {
                if(tmt.validator.validateForm("registration_form")) {
                    $wbc('#booking_container_all').parent().prepend('<div id="booking_sfondo" class="booking_modal_sfondo"></div>');
                    $wbc('#booking_modal_loading').fadeIn();
                }
            });
            /*check all months days*/
            setWeekdays();
            $wbc(window).on('resize', function(){
                setWeekdays();
            });

        });

        function setWeekdays() {
            $wbc('.booking_day_name').each(function() {

                if($wbc(this).width() < 70){
                    $wbc(this).html($wbc(this).data('abbr'));
                } else {
                    $wbc(this).html($wbc(this).data('full'));
                }
            });
        }

        function getMonthName(month,year) {
            var m = new Array();
            m[0] ="<?php echo esc_js(__( 'January', 'wp-booking-calendar' )); ?>";
            m[1] ="<?php echo esc_js(__( 'February', 'wp-booking-calendar' )); ?>";
            m[2] ="<?php echo esc_js(__( 'March', 'wp-booking-calendar' )); ?>";
            m[3] ="<?php echo esc_js(__( 'April', 'wp-booking-calendar' )); ?>";
            m[4] ="<?php echo esc_js(__( 'May', 'wp-booking-calendar' )); ?>";
            m[5] ="<?php echo esc_js(__( 'June', 'wp-booking-calendar' )); ?>";
            m[6] ="<?php echo esc_js(__( 'July', 'wp-booking-calendar' )); ?>";
            m[7] ="<?php echo esc_js(__( 'August', 'wp-booking-calendar' )); ?>";
            m[8] ="<?php echo esc_js(__( 'September', 'wp-booking-calendar' )); ?>";
            m[9] ="<?php echo esc_js(__( 'October', 'wp-booking-calendar' )); ?>";
            m[10] ="<?php echo esc_js(__( 'November', 'wp-booking-calendar' )); ?>";
            m[11] ="<?php echo esc_js(__( 'December', 'wp-booking-calendar' )); ?>";
            $wbc('#month_name').html(m[(month-1)]+'<span class="booking_year_name_custom booking_month_year booking_margin_l_10">'+year+'</span>');
            currentMonth = month;
            currentYear = year;

            if((today.getMonth()+1)!=(month)) {
                $wbc('#booking_back_today').fadeIn();
            } else {
                $wbc('#booking_back_today').css("display","none");
            }
        }


        function showResponse(calendar_id) {
            $wbc('#booking_container_all').parent().prepend('<div id="booking_sfondo" class="booking_modal_sfondo" onclick="hideBookingResponse('+calendar_id+',\'<?php echo $publickey; ?>\')"></div>');
            $wbc('#ok_response').attr("href","javascript:hideBookingResponse("+calendar_id+",'<?php echo $publickey; ?>');");
            $wbc('#booking_modal_response').fadeIn('slow');
            $wbc('#booking_submit_button').removeAttr("disabled");
        }

        function showCaptchaError() {
            $wbc('#booking_captcha_error').fadeIn();
            $wbc('#booking_submit_button').removeAttr("disabled");
        }

        function clearForm() {
            var formObj = document.forms["slot_reservation"];
            <?php
            if(in_array("reservation_name",$bookingSettingObj->getVisibleFields())) {
                ?>
                formObj.reservation_name.value='';
                <?php
            }
            ?>
            <?php
            if(in_array("reservation_surname",$bookingSettingObj->getVisibleFields())) {
                ?>
                formObj.reservation_surname.value='';
                <?php
            }
            ?>
            <?php
            if(in_array("reservation_email",$bookingSettingObj->getVisibleFields())) {
                ?>
                formObj.reservation_email.value='';
                <?php
            }
            ?>
            <?php
            if(in_array("reservation_phone",$bookingSettingObj->getVisibleFields())) {
                ?>
                formObj.reservation_phone.value='';
                <?php
            }
            ?>
            <?php
            if(in_array("reservation_message",$bookingSettingObj->getVisibleFields())) {
                ?>
                formObj.reservation_message.value='';
                <?php
            }
            ?>
            <?php
            if(in_array("reservation_field1",$bookingSettingObj->getVisibleFields())) {
                ?>
                formObj.reservation_field1.value='';
                <?php
            }
            ?>
            <?php
            if(in_array("reservation_field2",$bookingSettingObj->getVisibleFields())) {
                ?>
                formObj.reservation_field2.value='';
                <?php
            }
            ?>
            <?php
            if(in_array("reservation_field3",$bookingSettingObj->getVisibleFields())) {
                ?>
                formObj.reservation_field3.value='';
                <?php
            }
            ?>
            <?php
            if(in_array("reservation_field4",$bookingSettingObj->getVisibleFields())) {
                ?>
                formObj.reservation_field4.value='';
                <?php
            }
            ?>

            $wbc('#booking_captcha_error').fadeOut();
        }

        function updateCalendarSelect(category) {
            $wbc.ajax({
              url: '<?php echo BOOKING_PUBLIC_URL;?>ajax/getCalendarsList.php?category_id='+category+"&wpml_lang="+$wbc('#wpml_lang').val(),
              success: function(data) {
                  arrData = data.split("|");
                  $wbc('#calendar_select_input').html(arrData[0]);
                  $wbc("#calendar_select_input").val($wbc("#calendar_select_input option:first").val());
                <?php
                if($bookingSettingObj->getShowFirstFilledMonth() == 0) {
                    ?>
                    var newday= today;
                    <?php
                } else {
                    ?>
                    monthData = arrData[2].split(",");
                    var newday = new Date(monthData[0],monthData[1],monthData[2]);

                    <?php
                }
                ?>
                 getBookingMonthCalendar((newday.getMonth()+1),newday.getFullYear(),arrData[1],'<?php echo $publickey; ?>');
              }
            });
        }
        function updateCalendar(calendar_id) {
            $wbc.ajax({
              url: '<?php echo BOOKING_PUBLIC_URL;?>ajax/getCalendar.php?calendar_id='+calendar_id+"&wpml_lang="+$wbc('#wpml_lang').val(),
              success: function(data) {

                  <?php
                if($bookingSettingObj->getShowFirstFilledMonth() == 0) {
                    ?>
                    var newday= today;
                    <?php
                } else {
                    ?>

                    monthData = data.split(",");
                    var newday = new Date(monthData[0],monthData[1],monthData[2]);

                    <?php
                }
                ?>
                 getBookingMonthCalendar((newday.getMonth()+1),newday.getFullYear(),calendar_id,'<?php echo $publickey; ?>');
              }
            });

        }	<?php
        if($bookingSettingObj->getPaypal() == 1 && $bookingSettingObj->getPaypalAccount()!='' && $bookingSettingObj->getPaypalCurrency()!='' && $bookingSettingObj->getPaypalLocale() != '') {
            ?>
            $wbc(function() {
                $wbc('#booking_submit_button').bind('click',function() {
                    paypalSubmit();
                });
            });
            function addToPaypalForm() {
                /*
                $wbc('#slots_purchased').html('');
                var new_html = '';
                var i = 1;
                var j = 0;

                $wbc('#booking_slots').find('input').each(function() {


                    if($wbc(this).attr('checked')) {
                        var slot_id = $wbc(this).val();
                        $wbc('#booking_submit_button').attr("disabled","disabled");

                        $wbc.ajax({
                          url: '<?php echo BOOKING_PUBLIC_URL;?>ajax/getSlotInfo.php?slot_id='+$wbc(this).val(),
                          success: function(data) {

                              arrData=data.split("$");
                              if(arrData[1]>0) {
                                  q = 1;
                                  if($wbc('#seats_'+slot_id).val()!=undefined) {
                                      q = $wbc('#seats_'+slot_id).val();
                                  }
                                  new_html += '<input type="hidden" name="item_name_'+i+'" value="'+arrData[0]+'" /><input type="hidden" name="amount_'+i+'" value="'+arrData[1]+'" /><input type="hidden" name="quantity_'+i+'" value="'+q+'" />';
                                  $wbc('#slots_purchased').html(new_html);

                                  if(j == $wbc('#booking_slots').find('input').length) {
                                    $wbc('#booking_submit_button').removeAttr("disabled");
                                  }

                                 i++;
                              }
                          }
                        });

                    }
                    j++;
                });
                */

            }

            function paypalSubmit() {
                if(tmt.validator.validateForm("slot_reservation")) {
                    if(Trim($wbc('#slots_purchased').html())!='') {
                        $wbc('#slot_reservation').submit();
                    } else {
                        /*$wbc('#with_paypal').remove();*/
                        document.forms["slot_reservation"].submit();
                    }
                }
            }
            function submitPaypal() {
                $wbc('#booking_container_all').parent().prepend('<div id="booking_sfondo" class="booking_modal_sfondo"></div>');
                $wbc('#booking_modal_loading').fadeIn();
                document.forms["paypal_form"].submit();
            }
            <?php
        }
        ?>

    </script>

    <!-- ===============================================================
        box preview available time slots
    ================================================================ -->
    <div class="booking_box_preview_container_all booking_font_cuprum" id="booking_box_slots" style="display:none !important">
        <div class="booking_box_preview_title" id="booking_popup_title"><?php echo $bookingCalendarObj->getCalendarTitle(); ?></div>
        <div class="booking_box_preview_slots_container" id="booking_slots_popup">

        </div>
    </div>

    <!-- ===============================================================
        booking calendar begins here
    ================================================================ -->
    <div class="booking_main_container" id="booking_container_all">

        <a name="calendar"></a>
        <!-- =======================================
            header (month + navigation + select)
        ======================================== -->
        <div class="booking_header_container">

            <div class="booking_select_calendar_container">
             <?php
            if($bookingSettingObj->getShowCategorySelection() == 1 && (!isset($_GET["calendar_id"]) || $_GET["calendar_id"] == 0) && (!isset($_GET["category_id"]) || $_GET["category_id"] == 0)) {
                ?>
                <!-- select calendar -->


                    <!-- select message -->
                    <div class="booking_float_right booking_font_13" id="booking_category_select_label"><?php echo __( 'Category:', 'wp-booking-calendar' ); ?></div>
                    <div class="booking_cleardiv"></div>
                    <!-- select -->
                    <div class="booking_float_right booking_margin_b_10" id="booking_category_select">
                        <?php
                        $arrayCategories = $bookingListObj->getCategoriesList('ORDER BY category_order');
                        if(count($arrayCategories) > 0) {
                            ?>
                            <select name="category" onchange="javascript:updateCalendarSelect(this.options[this.selectedIndex].value);">
                                <?php
                                foreach($arrayCategories as $categoryId => $category) {
                                    ?>
                                    <option value="<?php echo $categoryId; ?>" <?php if($categoryId == $bookingCategoryObj->getCategoryId()) { echo 'selected="selected"'; }?>><?php echo $category["category_name"]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <?php
                        }
                        ?>
                    </div>



                    <div class="booking_cleardiv"></div>


                <?php
            }
            ?>

            <?php
            if($bookingSettingObj->getShowCalendarSelection() == 1 && (!isset($_GET["calendar_id"]) || $_GET["calendar_id"] == 0)) {
                ?>
                <!-- select calendar -->


                    <!-- select message -->
                    <div class="booking_float_left booking_font_13 booking_margin_r_10" id="booking_calendar_select_label"><?php echo __( 'Calendar:', 'wp-booking-calendar' ); ?></div>
                    <!-- select -->
                    <div class="booking_float_left" id="booking_calendar_select">
                        <?php

                        $arrayCalendars = $bookingListObj->getCalendarsList('ORDER BY calendar_order',$bookingCategoryObj->getCategoryId());
                        if(count($arrayCalendars) > 0) {
                            ?>
                            <select name="calendar" id="calendar_select_input" onchange="javascript:updateCalendar(this.options[this.selectedIndex].value);">
                                <?php
                                foreach($arrayCalendars as $calendarId => $calendar) {
                                    ?>
                                    <option value="<?php echo $calendarId; ?>" <?php if($calendarId == $bookingCalendarObj->getCalendarId()) { echo "selected"; }?>><?php echo $calendar["calendar_title"]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <?php
                        }
                        ?>
                    </div>






                <?php
            }
            ?>
            </div>

            <div class="booking_cleardiv"></div>

            <!-- month and navigation -->
            <div class="booking_month_container_all booking_month_container_custom">
                <!-- previous month -->
                <div class="booking_mont_nav_button_container_prev" id="booking_month_nav_prev"><a href="javascript:getBookingPreviousMonth(<?php echo $bookingCalendarObj->getCalendarId(); ?>,'<?php echo $publickey; ?>',<?php echo $bookingSettingObj->getCalendarMonthLimitPast(); ?>);" class="booking_month_nav_button booking_month_navigation_button_custom"><</a></div>

                <!-- month -->
                <div class="booking_month_container ">
                    <div class="booking_font_custom booking_month_name booking_month_name_custom" id="month_name"></div>
                    <div class="booking_cleardiv"></div>
                    <div class="booking_back_today" id="booking_back_today"><a href="javascript:getBookingMonthCalendar((today.getMonth()+1),today.getFullYear(),'<?php echo $bookingCalendarObj->getCalendarId(); ?>','<?php echo $publickey; ?>');"><?php echo __( 'Back to today', 'wp-booking-calendar' ); ?></a></div>
                </div>

                <!-- next month -->
                <div class="booking_mont_nav_button_container_next" id="booking_month_nav_next"><a href="javascript:getBookingNextMonth(<?php echo $bookingCalendarObj->getCalendarId(); ?>,'<?php echo $publickey; ?>',<?php echo $bookingSettingObj->getCalendarMonthLimitFuture(); ?>);" class="booking_month_nav_button booking_month_navigation_button_custom">></a></div>

                <!-- navigation -->
                <div class="booking_month_nav_container" id="booking_month_nav">


                </div>
                <div class="booking_cleardiv"></div>

            </div>



        </div>


        <!-- =======================================
            calendar
        ======================================== -->

        <div class="booking_cleardiv"></div>
        <!-- calendar -->
        <div class="booking_calendar_container_all">
            <!-- days name -->
            <div class="booking_name_days_container booking_weekdays_container_custom" id="booking_name_days_container">
                <?php
                if($bookingSettingObj->getDateFormat() == "UK" || $bookingSettingObj->getDateFormat() == "EU") {
                    ?>
                    <div class="booking_font_custom booking_day_name booking_weekdays_custom" data-abbr="<?php echo __( 'Mon', 'wp-booking-calendar' ); ?>" data-full="<?php echo __( 'Monday', 'wp-booking-calendar' ); ?>"><?php echo __( 'Monday', 'wp-booking-calendar' ); ?></div>
                    <div class="booking_font_custom booking_day_name booking_weekdays_custom" data-abbr="<?php echo __( 'Tue', 'wp-booking-calendar' ); ?>" data-full="<?php echo __( 'Tuesday', 'wp-booking-calendar' ); ?>"><?php echo __( 'Tuesday', 'wp-booking-calendar' ); ?></div>
                    <div class="booking_font_custom booking_day_name booking_weekdays_custom" data-abbr="<?php echo __( 'Wed', 'wp-booking-calendar' ); ?>" data-full="<?php echo __( 'Wednesday', 'wp-booking-calendar' ); ?>"><?php echo __( 'Wednesday', 'wp-booking-calendar' ); ?></div>
                    <div class="booking_font_custom booking_day_name booking_weekdays_custom" data-abbr="<?php echo __( 'Thu', 'wp-booking-calendar' ); ?>" data-full="<?php echo __( 'Thursday', 'wp-booking-calendar' ); ?>"><?php echo __( 'Thursday', 'wp-booking-calendar' ); ?></div>
                    <div class="booking_font_custom booking_day_name booking_weekdays_custom" data-abbr="<?php echo __( 'Fri', 'wp-booking-calendar' ); ?>" data-full="<?php echo __( 'Friday', 'wp-booking-calendar' ); ?>"><?php echo __( 'Friday', 'wp-booking-calendar' ); ?></div>
                    <div class="booking_font_custom booking_day_name booking_weekdays_custom" data-abbr="<?php echo __( 'Sat', 'wp-booking-calendar' ); ?>" data-full="<?php echo __( 'Saturday', 'wp-booking-calendar' ); ?>"><?php echo __( 'Saturday', 'wp-booking-calendar' ); ?></div>
                    <div class="booking_font_custom booking_day_name booking_weekdays_custom" data-abbr="<?php echo __( 'Sun', 'wp-booking-calendar' ); ?>" data-full="<?php echo __( 'Sunday', 'wp-booking-calendar' ); ?>" style="margin-right: 0px;"><?php echo __( 'Sunday', 'wp-booking-calendar' ); ?></div>
                    <?php
                } else {
                    ?>
                    <div class="booking_font_custom booking_day_name booking_weekdays_custom" data-abbr="<?php echo __( 'Sun', 'wp-booking-calendar' ); ?>" data-full="<?php echo __( 'Sunday', 'wp-booking-calendar' ); ?>"><?php echo __( 'Sunday', 'wp-booking-calendar' ); ?></div>
                    <div class="booking_font_custom booking_day_name booking_weekdays_custom" data-abbr="<?php echo __( 'Mon', 'wp-booking-calendar' ); ?>" data-full="<?php echo __( 'Monday', 'wp-booking-calendar' ); ?>"><?php echo __( 'Monday', 'wp-booking-calendar' ); ?></div>
                    <div class="booking_font_custom booking_day_name booking_weekdays_custom" data-abbr="<?php echo __( 'Tue', 'wp-booking-calendar' ); ?>" data-full="<?php echo __( 'Tuesday', 'wp-booking-calendar' ); ?>"><?php echo __( 'Tuesday', 'wp-booking-calendar' ); ?></div>
                    <div class="booking_font_custom booking_day_name booking_weekdays_custom" data-abbr="<?php echo __( 'Wed', 'wp-booking-calendar' ); ?>" data-full="<?php echo __( 'Wednesday', 'wp-booking-calendar' ); ?>"><?php echo __( 'Wednesday', 'wp-booking-calendar' ); ?></div>
                    <div class="booking_font_custom booking_day_name booking_weekdays_custom" data-abbr="<?php echo __( 'Thu', 'wp-booking-calendar' ); ?>" data-full="<?php echo __( 'Thursday', 'wp-booking-calendar' ); ?>"><?php echo __( 'Thursday', 'wp-booking-calendar' ); ?></div>
                    <div class="booking_font_custom booking_day_name booking_weekdays_custom" data-abbr="<?php echo __( 'Fri', 'wp-booking-calendar' ); ?>" data-full="<?php echo __( 'Friday', 'wp-booking-calendar' ); ?>"><?php echo __( 'Friday', 'wp-booking-calendar' ); ?></div>
                    <div class="booking_font_custom booking_day_name booking_weekdays_custom" data-abbr="<?php echo __( 'Sat', 'wp-booking-calendar' ); ?>" data-full="<?php echo __( 'Saturday', 'wp-booking-calendar' ); ?>" style="margin-right: 0px;"><?php echo __( 'Saturday', 'wp-booking-calendar' ); ?></div>
                    <?php
                }
                ?>
            </div>

            <!-- days -->
            <div class="days_container_all" id="booking_calendar_container">
                <!-- content by js -->
            </div>
        </div>

        <!-- =======================================
            booking form. It appears once user clicked on a day
        ======================================== -->
        <?php
        $current_user_id = 0;
        if($bookingSettingObj->getWordpressRegistration()  == 1) {
              $display_form = "none";
              $display_login = "block";
              global $current_user;
              wp_get_current_user();

            if($current_user->ID>0) {
                $display_form = "block";
                $display_login = "none";
                $current_user_id = $current_user->ID;
            }

        } else {
            $display_form = "block";
            $display_login = "none";
            global $current_user;
        }
        global $sitepress;

    ?>
    <div id="booking_container" style="display:none !important">
        <form name="slot_reservation" id="slot_reservation" action="<?php echo BOOKING_PUBLIC_URL;?>ajax/doReservation.php" method="post" target="iframe_submit" tmt:validate="true" style="display:inline;">
        <input type="hidden" name="wordpress_user_id" id="wordpress_user_id" value="<?php echo $current_user_id; ?>" />
        <input type="hidden" name="wpml_lang" id="wpml_lang" value="<?php if(isset($sitepress)) { echo $sitepress->get_current_language(); } ?>" />
        <input type="hidden" name="post_id" value="<?php echo $post->ID; ?>" />
        <div class="booking_width_100p booking_margin_t_30">
            <div id="booking_slot_form" class="booking_font_cuprum">

            </div>

            <input type="hidden" name="calendar_id" id="calendar_id" value="<?php echo $bookingCalendarObj->getCalendarId(); ?>" />


            <!-- rightside -->
            <div class="booking_bg_567 booking_mark_fff booking_padding_10 booking_margin_t_20" id="form_container_all" style="background-color:<?php echo $bookingSettingObj->getFormBg(); ?>;color:<?php echo $bookingSettingObj->getFormColor(); ?>;display:<?php echo $display_form; ?> !important">


                <?php
                if(in_array("reservation_name",$bookingSettingObj->getVisibleFields())) {
                    ?>
                    <!-- name -->
                    <?php
                        if($bookingSettingObj->getReservationFieldType('reservation_name') == 'text') {
                            ?>
                            <div class="booking_form_input_container">
                                <div><?php echo __( 'Name', 'wp-booking-calendar' ); ?></div>
                                <input type="text" name="reservation_name" id="reservation_name" class="booking_field_input_custom booking_width_90p booking_border_none" <?php if(in_array("reservation_name",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:message="'.__( 'Insert your name', 'wp-booking-calendar' ).'"'; }?> value=""/>
                            </div>
                            <?php
                        } else if($bookingSettingObj->getReservationFieldType('reservation_name') == 'textarea') {
                            ?>
                            <div class="booking_form_textarea_container">
                                <div><?php echo __( 'Name', 'wp-booking-calendar' ); ?></div>
                                <textarea name="reservation_name" id="reservation_name" class="booking_field_input_custom booking_width_100p height_25 booking_border_none" <?php if(in_array("reservation_name",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:message="'.__( 'Insert your name', 'wp-booking-calendar' ).'"'; }?>></textarea>
                            </div>
                            <?php
                        }
                        ?>
                    <?php
                } else {
                    ?>
                    <input type="hidden" name="reservation_name" value="" />
                    <?php
                }


                if(in_array("reservation_surname",$bookingSettingObj->getVisibleFields())) {
                    ?>
                    <!-- surname -->
                    <?php
                        if($bookingSettingObj->getReservationFieldType('reservation_surname') == 'text') {
                            ?>
                            <div class="booking_form_input_container">
                                <div><?php echo __( 'Surname', 'wp-booking-calendar' ); ?></div>
                                <input type="text" name="reservation_surname" id="reservation_surname" class="booking_field_input_custom booking_width_90p booking_border_none" <?php if(in_array("reservation_surname",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:message="'.__( 'Insert your surname', 'wp-booking-calendar' ).'"'; }?> value=""/>
                            </div>
                            <?php
                        } else if($bookingSettingObj->getReservationFieldType('reservation_surname') == 'textarea') {
                            ?>
                            <div class="booking_float_left booking_margin_r_2p booking_width_98p">
                                <div><?php echo __( 'Surname', 'wp-booking-calendar' ); ?></div>
                                <textarea name="reservation_surname" id="reservation_surname" class="booking_field_input_custom booking_width_100p height_25 booking_border_none" <?php if(in_array("reservation_surname",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:message="'.__( 'Insert your surname', 'wp-booking-calendar' ).'"'; }?>></textarea>
                            </div>
                            <?php
                        }
                        ?>

                    <?php
                } else {
                    ?>
                    <input type="hidden" name="reservation_surname" value="" />
                    <?php
                }



                if(in_array("reservation_email",$bookingSettingObj->getVisibleFields())) {
                    ?>
                    <!-- email -->
                    <?php
                        if($bookingSettingObj->getReservationFieldType('reservation_email') == 'text') {
                            ?>
                            <div class="booking_form_input_container">
                                <div><?php echo __( 'Email', 'wp-booking-calendar' ); ?></div>
                                <input type="text"  name="reservation_email" id="reservation_email" class="booking_field_input_custom booking_width_90p booking_border_none" <?php if(in_array("reservation_email",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:pattern="email" tmt:message="'.__( 'Insert a valid email address', 'wp-booking-calendar' ).'"'; }?> value="<?php echo $current_user->user_email; ?>"/>
                            </div>
                            <?php
                        } else if($bookingSettingObj->getReservationFieldType('reservation_email') == 'textarea') {
                            ?>
                            <div class="booking_float_left booking_margin_r_2p booking_width_98p">
                                <div><?php echo __( 'Email', 'wp-booking-calendar' ); ?></div>
                                <textarea  name="reservation_email" id="reservation_email" class="booking_field_input_custom booking_width_100p height_25 booking_border_none" <?php if(in_array("reservation_email",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:pattern="email" tmt:message="'.__( 'Insert a valid email address', 'wp-booking-calendar' ).'"'; }?>><?php echo $current_user->user_email; ?></textarea>
                            </div>
                            <?php
                        }
                        ?>

                    <?php
                } else {
                    ?>
                    <input type="hidden" name="reservation_email" value="" />
                    <?php
                }



                if(in_array("reservation_phone",$bookingSettingObj->getVisibleFields())) {
                    ?>
                    <!-- phone -->
                    <?php
                    if($bookingSettingObj->getReservationFieldType('reservation_phone') == 'text') {
                        ?>
                        <div class="booking_form_input_container">
                            <div><?php echo __( 'Phone', 'wp-booking-calendar' ); ?></div>
                            <input type="text" name="reservation_phone" class="booking_field_input_custom booking_width_90p booking_border_none" <?php if(in_array("reservation_phone",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:message="'.__( 'Insert your phone', 'wp-booking-calendar' ).'"'; }?>/>
                        </div>
                        <?php
                    } else if($bookingSettingObj->getReservationFieldType('reservation_phone') == 'textarea') {
                        ?>
                        <div class="booking_float_left booking_margin_r_2p booking_width_98p">
                            <div><?php echo __( 'Phone', 'wp-booking-calendar' ); ?></div>
                            <textarea name="reservation_phone" class="booking_field_input_custom booking_width_100p height_25 booking_border_none" <?php if(in_array("reservation_phone",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:message="'.__( 'Insert your phone', 'wp-booking-calendar' ).'"'; }?>></textarea>
                        </div>
                        <?php
                    }
                    ?>

                    <?php
                } else {
                    ?>
                    <input type="hidden" name="reservation_phone" value="" />
                    <?php
                }



                if(in_array("reservation_message",$bookingSettingObj->getVisibleFields())) {
                    ?>
                    <!-- message -->
                    <?php
                    if($bookingSettingObj->getReservationFieldType('reservation_message') == 'text') {
                        ?>
                        <div class="booking_form_input_container">
                            <div><?php echo __( 'Message', 'wp-booking-calendar' ); ?></div>
                            <input type="text" class="booking_field_input_custom booking_width_90p booking_border_none" name="reservation_message" <?php if(in_array("reservation_message",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:message="'.__( 'Insert a message', 'wp-booking-calendar' ).'"'; }?>/>
                        </div>
                        <?php
                    } else if($bookingSettingObj->getReservationFieldType('reservation_message') == 'textarea') {
                        ?>
                        <div class="booking_form_textarea_container">
                            <div><?php echo __( 'Message', 'wp-booking-calendar' ); ?></div>
                            <textarea class="booking_field_input_custom booking_width_100p height_25 booking_border_none" name="reservation_message" <?php if(in_array("reservation_message",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:message="'.__( 'Insert a message', 'wp-booking-calendar' ).'"'; }?>></textarea>
                        </div>
                        <?php
                    }
                    ?>

                    <?php
                } else {
                    ?>
                    <input type="hidden" name="reservation_message" value="" />
                    <?php
                }



                if(in_array("reservation_field1",$bookingSettingObj->getVisibleFields())) {
                    ?>
                    <!-- additional 1 -->
                    <?php
                    if($bookingSettingObj->getReservationFieldType('reservation_field1') == 'text') {
                        ?>
                        <div class="booking_form_input_container">
                            <div><?php echo __( 'Additional field 1', 'wp-booking-calendar' ); ?></div>
                            <input type="text" name="reservation_field1" class="booking_field_input_custom booking_width_90p booking_border_none" <?php if(in_array("reservation_field1",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:message="'.__( 'Insert additional field 1', 'wp-booking-calendar' ).'"'; }?>/>
                        </div>
                        <?php
                    } else if($bookingSettingObj->getReservationFieldType('reservation_field1') == 'textarea') {
                        ?>
                        <div class="booking_form_textarea_container">
                            <div><?php echo __( 'Additional field 1', 'wp-booking-calendar' ); ?></div>
                            <textarea name="reservation_field1" class="booking_field_input_custom booking_width_100p height_25 booking_border_none" <?php if(in_array("reservation_field1",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:message="'.__( 'Insert additional field 1', 'wp-booking-calendar' ).'"'; }?>></textarea>
                        </div>
                        <?php
                    }
                    ?>

                    <?php
                } else {
                    ?>
                    <input type="hidden" name="reservation_field1" value="" />
                    <?php
                }



                if(in_array("reservation_field2",$bookingSettingObj->getVisibleFields())) {
                    ?>
                    <!-- additional 2 -->
                    <?php
                    if($bookingSettingObj->getReservationFieldType('reservation_field2') == 'text') {
                        ?>
                        <div class="booking_form_input_container">
                            <div><?php echo __( 'Additional field 2', 'wp-booking-calendar' ); ?></div>
                            <input type="text" name="reservation_field2" class="booking_field_input_custom booking_width_90p booking_border_none" <?php if(in_array("reservation_field2",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:message="'.__( 'Insert additional field 2', 'wp-booking-calendar' ).'"'; }?>/>
                        </div>
                        <?php
                    } else if($bookingSettingObj->getReservationFieldType('reservation_field2') == 'textarea') {
                        ?>
                        <div class="booking_form_textarea_container">
                            <div><?php echo __( 'Additional field 2', 'wp-booking-calendar' ); ?></div>
                            <textarea name="reservation_field2" class="booking_field_input_custom booking_width_100p height_25 booking_border_none" <?php if(in_array("reservation_field2",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:message="'.__( 'Insert additional field 2', 'wp-booking-calendar' ).'"'; }?>></textarea>
                        </div>
                        <?php
                    }
                    ?>

                    <?php
                } else {
                    ?>
                    <input type="hidden" name="reservation_field2" value="" />
                    <?php
                }



                if(in_array("reservation_field3",$bookingSettingObj->getVisibleFields())) {
                    ?>
                    <!-- additional 3 -->
                    <?php
                    if($bookingSettingObj->getReservationFieldType('reservation_field3') == 'text') {
                        ?>
                        <div class="booking_form_input_container">
                            <div><?php echo __( 'Additional field 3', 'wp-booking-calendar' ); ?></div>
                            <input type="text" name="reservation_field3" class="booking_field_input_custom booking_width_90p booking_border_none" <?php if(in_array("reservation_field3",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:message="'.__( 'Insert additional field 3', 'wp-booking-calendar' ).'"'; }?>/>
                        </div>
                        <?php
                    } else if($bookingSettingObj->getReservationFieldType('reservation_field3') == 'textarea') {
                        ?>
                        <div class="booking_form_textarea_container">
                            <div><?php echo __( 'Additional field 3', 'wp-booking-calendar' ); ?></div>
                            <textarea name="reservation_field3" class="booking_field_input_custom booking_width_100p height_25 booking_border_none" <?php if(in_array("reservation_field3",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:message="'.__( 'Insert additional field 3', 'wp-booking-calendar' ).'"'; }?>></textarea>
                        </div>
                        <?php
                    }
                    ?>

                    <?php
                } else {
                    ?>
                    <input type="hidden" name="reservation_field3" value="" />
                    <?php
                }



                if(in_array("reservation_field4",$bookingSettingObj->getVisibleFields())) {
                    ?>
                    <!-- additional 4 -->
                    <?php
                    if($bookingSettingObj->getReservationFieldType('reservation_field4') == 'text') {
                        ?>
                        <div class="booking_form_input_container">
                            <div><?php echo __( 'Additional field 4', 'wp-booking-calendar' ); ?></div>
                            <input type="text" name="reservation_field4" class="booking_field_input_custom booking_width_90p booking_border_none" <?php if(in_array("reservation_field4",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:message="'.__( 'Insert additional field 4', 'wp-booking-calendar' ).'"'; }?>/>
                        </div>
                        <?php
                    } else if($bookingSettingObj->getReservationFieldType('reservation_field4') == 'textarea') {
                        ?>
                        <div class="booking_form_textarea_container">
                            <div><?php echo __( 'Additional field 4', 'wp-booking-calendar' ); ?></div>
                            <textarea name="reservation_field4" class="booking_field_input_custom booking_width_100p height_25 booking_border_none" <?php if(in_array("reservation_field4",$bookingSettingObj->getMandatoryFields())) { echo 'tmt:required="true" tmt:message="'.__( 'Insert additional field 4', 'wp-booking-calendar' ).'"'; }?>></textarea>
                        </div>
                        <?php
                    }
                    ?>

                    <?php
                } else {
                    ?>
                    <input type="hidden" name="reservation_field4" value="" />
                    <?php
                }
                ?>

                <div class="booking_cleardiv"></div>

                <?php
                if($bookingSettingObj->getShowTerms() == 1 && $bookingSettingObj->getTermsLabel() != '') {
                    ?>
                    <!-- terms -->
                    <div class="booking_cleardiv"></div>
                    <div class="booking_margin_t_10">
                        <div class="booking_float_left"><input type="checkbox" name="reservation_terms" value="checked" tmt:minchecked="1" tmt:message="<?php echo __( 'You have to accept terms and conditions', 'wp-booking-calendar' );?>"/></div>
                        <div class="booking_float_left booking_margin_l_10"><a href="<?php echo $bookingSettingObj->getTermsLink(); ?>" class="booking_mark_fff font_size_12 booking_no_decoration" target="_blank"><?php echo $bookingSettingObj->getTermsLabel(); ?></a></div>
                        <div class="booking_cleardiv"></div>
                        <div class="booking_form_input"></div>
                    </div>
                    <?php
                }
                ?>

                <div class="booking_cleardiv"></div>

                <?php
                if($bookingSettingObj->getWordpressRegistration() == 0) {
                    if($bookingSettingObj->getRecaptchaVersion() == 1) {
                        ?>
                        <!-- google capthca -->
                        <div class="booking_margin_t_10">
                            <div id="booking_captcha_error" style="display:none !important"><?php echo __( 'Invalid code', 'wp-booking-calendar' );?></div>
                            <div id="captcha"></div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="booking_margin_t_10">
                            <div id="booking_captcha_error" style="display:none !important"><?php echo __( 'Invalid code', 'wp-booking-calendar' );?></div>
                            <div class="g-recaptcha" data-sitekey="<?php echo $publickey; ?>"></div>
                        </div>
                        <?php
                    }
                    ?>

                    <div class="booking_cleardiv"></div>
                    <?php
                }
                ?>


                <!-- book now button and clear -->
                <div class="booking_margin_t_20">

                        <!-- book now btn -->
                        <?php
                        if($bookingSettingObj->getPaypal()==1 && $bookingSettingObj->getPaypalAccount() != '' && $bookingSettingObj->getPaypalLocale() != '' && $bookingSettingObj->getPaypalCurrency() != '') {
                            ?>
                            <div class="booking_booknow_btn">
                                <input type="hidden" name="with_paypal" id="with_paypal" value="1" />
                                <input type="button" class="booking_book_now_custom" id="booking_submit_button" value="<?php echo __( 'BOOK NOW', 'wp-booking-calendar' ); ?>" style="cursor:pointer" />
                            </div>
                            <div class="booking_cleardiv"></div>
                            <?php
                        } else {
                            ?>
                            <div class="booking_booknow_btn">
                                <input type="submit" class="booking_book_now_custom" id="booking_submit_button" value="<?php echo __( 'BOOK NOW', 'wp-booking-calendar' ); ?>" style="cursor:pointer" />
                            </div>

                            <?php
                        }
                        ?>

                        <!-- clear btn -->
                    <div class="booking_clear_btn"><a href="javascript:clearForm();" class="booking_clear_custom booking_public_button booking_grey_button"><?php echo __( 'clear', 'wp-booking-calendar' ); ?></a></div>

                    <div class="booking_cleardiv"></div>

                </div>



            </div>

        </div>
        </form>


                <script type="text/javascript" charset="utf-8">
                    $wbc(document).ready(function() {
                        $wbc(".tab_content_login").hide();
                        $wbc("ul.tabs_login li:first").addClass("active_login").show();
                        $wbc(".tab_content_login:first").show();
                        $wbc("ul.tabs_login li").click(function() {
                            $wbc("ul.tabs_login li").removeClass("active_login");
                            $wbc(this).addClass("active_login");
                            $wbc(".tab_content_login").hide();
                            var activeTab = $wbc(this).find("a").attr("href");
                            if ($wbc.browser.msie) {$wbc(activeTab).show();}
                            else {$wbc(activeTab).show();}
                            return false;
                        });
                    });
                </script>


                <div id="login-register-password-container" style="display:<?php echo $display_login; ?> !important">

                    <?php global $user_ID, $user_identity; wp_get_current_user(); if (!$user_ID) { ?>

                    <ul class="tabs_login">
                        <li class="active_login"><a href="#tab1_login"><?php echo __( 'Login', 'wp-booking-calendar' ); ?></a></li>
                        <?php
                        if ( get_option( 'users_can_register' ) ) {
                            ?>
                            <li><a href="#tab2_login"><?php echo __( 'Register', 'wp-booking-calendar' ); ?></a></li>
                            <?php
                        }
                        ?>

                    </ul>
                    <div class="tab_container_login">
                        <div id="tab1_login" class="tab_content_login">



                            <h3><?php echo $bookingSettingObj->getRegistrationText(); ?></h3>



                            <script>
                                function submitLogin() {
                                    if(Trim($wbc('#user_login') .val()) != '' && Trim($wbc('#user_pass').val()) != '') {

                                        $wbc('#booking_container_all').parent().prepend('<div id="booking_sfondo" class="booking_modal_sfondo"></div>');
                                        $wbc('#booking_modal_loading').fadeIn();

                                        $wbc.ajax({
                                            type: "POST",
                                            url: "<?php bloginfo('url') ?>/wp-login.php",
                                            data: { log: $wbc('#user_login').val(), pwd: $wbc('#user_pass').val() }
                                        }).done(function() {
                                            $wbc.ajax({
                                              url: '<?php echo BOOKING_PUBLIC_URL;?>ajax/getCurrentUser.php?wpml_lang='+$wbc('#wpml_lang').val(),
                                              success: function(data) {
                                                  $wbc('#booking_sfondo').remove();
                                                  $wbc('#booking_modal_loading').fadeOut();
                                                  if(data!='error') {
                                                      arrData = data.split("|");
                                                      $wbc('#login-register-password-container').removeAttr('style');
                                                      $wbc('#login-register-password-container').css('display','none !important');
                                                      $wbc('#reservation_name').val(arrData[0]);
                                                      $wbc('#reservation_surname').val(arrData[1]);
                                                      $wbc('#reservation_email').val(arrData[2]);
                                                      $wbc('#wordpress_user_id').val(arrData[3]);
                                                      $wbc('#form_container_all').removeAttr('style');
                                                      $wbc('#form_container_all').css('display','block !important');

                                                  } else {
                                                      alert("<?php echo esc_js(__( 'Invalid username or password', 'wp-booking-calendar' )); ?>");
                                                  }

                                              }
                                            });

                                        });
                                    } else {
                                        alert("<?php echo esc_js(__( 'Insert a valid username and password', 'wp-booking-calendar' )); ?>");
                                    }
                                }
                            </script>
                            <form method="post" action="<?php bloginfo('url') ?>/wp-login.php" class="wp-user-form">
                                <div class="username">
                                    <label for="user_login"><?php echo __( 'Username', 'wp-booking-calendar' ); ?>: </label>
                                    <input type="text" name="log" value="<?php echo esc_attr(stripslashes($user_login)); ?>" size="20" id="user_login" tabindex="11" />
                                </div>
                                <div class="password">
                                    <label for="user_pass"><?php echo __( 'Password', 'wp-booking-calendar' ); ?>: </label>
                                    <input type="password" name="pwd" value="" size="20" id="user_pass" tabindex="12" />
                                </div>
                                <div class="login_fields">

                                    <?php do_action('login_form'); ?>
                                    <input type="button" name="user-submit" value="<?php echo __( 'Login', 'wp-booking-calendar' ); ?>" tabindex="14" class="user-submit" onclick="javascript:submitLogin();" />
                                    <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
                                    <input type="hidden" name="user-cookie" value="1" />
                                </div>
                            </form>
                        </div>
                        <?php
                        global $sitepress;
                        if ( get_option( 'users_can_register' ) ) {
                            ?>
                            <div id="tab2_login" class="tab_content_login" style="display:none;">
                                <h3><?php echo $bookingSettingObj->getRegistrationText(); ?></h3>
                                <div class="booking_font_10 booking_mark_567" style="margin-top:-10px"><?php echo __( 'All fields are mandatory', 'wp-booking-calendar' ); ?></div>
                                <form method="post" action="<?php echo BOOKING_PUBLIC_URL;?>ajax/registerUser.php" class="wp-user-form" target="iframe_submit" tmt:validate="true" name="registration_form">
                                    <input type="hidden" name="wpml_lang" value="<?php if(isset($sitepress)) { echo $sitepress->get_current_language(); } ?>" />
                                    <div class="username">
                                        <label for="user_login"><?php echo __( 'Username', 'wp-booking-calendar' ); ?>: </label>
                                        <input type="text" name="user_login" value="<?php echo esc_attr(stripslashes($user_login)); ?>" size="20" id="user_login" tabindex="101" tmt:required="true" tmt:pattern="alphanumeric" tmt:message="<?php echo __( 'Insert a username', 'wp-booking-calendar' ); ?>" />
                                    </div>
                                    <div class="password">
                                        <label for="user_email"><?php echo __( 'Your email', 'wp-booking-calendar' ); ?>: </label>
                                        <input type="text" name="user_email" value="<?php echo esc_attr(stripslashes($user_email)); ?>" size="25" id="user_email" tabindex="102" tmt:required="true" tmt:pattern="email" tmt:message="<?php echo __( 'Insert a valid email address', 'wp-booking-calendar' ); ?>" />
                                    </div>
                                    <div class="password">
                                        <label for="user_password"><?php echo __( 'Your password', 'wp-booking-calendar' ); ?>: </label>
                                        <input type="password" name="user_password" value="" size="25" id="user_password" tabindex="103" tmt:required="true" tmt:pattern="alphanumeric" tmt:message="<?php echo __( 'Insert a password', 'wp-booking-calendar' ); ?>" />
                                    </div>
                                    <div class="password">
                                        <label for="user_confirm_password"><?php echo __( 'Confirm password', 'wp-booking-calendar' ); ?>: </label>
                                        <input type="password" name="user_confirm_password" value="" size="25" id="user_confirm_password" tabindex="104" tmt:required="true" tmt:pattern="alphanumeric" tmt:equalto="user_password" tmt:message="<?php echo __( 'Confirm password must match password', 'wp-booking-calendar' ); ?>" />
                                    </div>
                                     <!-- google capthca -->
                                    <div class="booking_margin_t_10">
                                        <div id="booking_captcha_error" style="display:none !important"><?php echo __( 'Invalid code', 'wp-booking-calendar' );?></div>
                                        <div id="captcha"></div>
                                    </div>

                                    <div class="booking_cleardiv"></div>
                                    <div class="login_fields">
                                        <?php do_action('register_form'); ?>
                                        <input type="submit" id="reg_button" name="user-submit" value="<?php echo __( 'Sign up', 'wp-booking-calendar' ); ?>" class="user-submit" tabindex="105" />

                                        <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>?register=true" />
                                        <input type="hidden" name="user-cookie" value="1" />
                                    </div>
                                </form>
                            </div>
                            <?php
                        }
                        ?>

                    </div>

                    <?php }  ?>

                </div>


        </div>
        <?php
        if($bookingSettingObj->getPaypal()==1 && $bookingSettingObj->getPaypalAccount() != '' && $bookingSettingObj->getPaypalLocale() != '' && $bookingSettingObj->getPaypalCurrency() != '') {
            ?>
            <!-- paypal form -->
            <form action='https://www.paypal.com/cgi-bin/webscr' METHOD='POST' name="paypal_form" style="display:inline">

                <!-- PayPal Configuration -->
                <input type="hidden" name="business" value="<?php echo $bookingSettingObj->getPaypalAccount(); ?>">
                
                <input type="hidden" name="upload" value="1" />

                <input type="hidden" name="cmd" value="_cart">
                <input type="hidden" name="charset" value="utf-8">


                <!--slots purchased-->
                <div id="slots_purchased">

                </div>

                <input type="hidden" name="notify_url" value="<?php echo site_url('')."/?page_id=".$post->ID."&paypal_ipn_notice=1"; ?>">

                <input type="hidden" name="return" value="<?php echo site_url('')."/?page_id=".$post->ID."&paypal_confirm=1"; ?>">
                <input type="hidden" name="cancel_return" value="<?php echo site_url('')."/?page_id=".$post->ID; ?>&paypal_cancel=1">

                <input type="hidden" name="rm" value="POST">
                <input type="hidden" name="currency_code" value="<?php echo $bookingSettingObj->getPaypalCurrency();?>">
                <input type="hidden" name="lc" value="<?php echo $bookingSettingObj->getPaypalLocale(); ?>">






            </form>
            <?php
        }
        ?>
        <div style="clear:both"></div>

    </div>


    <!-- ===============================================================
        box after booking
    ================================================================ -->
    <div id="booking_modal_response" class="booking_modal" style="display:none !important">
        <?php
        if($bookingSettingObj->getReservationConfirmationMode() == 1) {
            echo __( 'Thank you for booking online. You\'ll receive an email confirmation at your email address', 'wp-booking-calendar' );
        } else if($bookingSettingObj->getReservationConfirmationMode() == 2) {
            echo __( 'Thank you for booking online. Check your email box to confirm the reservation.', 'wp-booking-calendar' );
        } else if($bookingSettingObj->getReservationConfirmationMode() == 3) {
            echo __( 'Thank you for booking online. Your reservation will be confirmed by e-mail.', 'wp-booking-calendar' );
        }
        ?>
        <br /><a href="javascript:hideBookingResponse(<?php echo $bookingCalendarObj->getCalendarId(); ?>,'<?php echo $publickey; ?>');" class="booking_button booking_ok_button" id="ok_response"><?php echo __( 'OK', 'wp-booking-calendar' ); ?></a>
    </div>

    <!-- preloader -->
    <div id="booking_modal_loading" class="booking_modal_loading" style="display:none !important">
        <img src="<?php echo BOOKING_ADMIN_URL;?>images/loading.png" border=0 style="-moz-box-shadow: none; -webkit-box-shadow: none; box-shadow: none;" />
    </div>
    <!-- necessary to submit form without reload the page -->
    <iframe style="border:none;width:0px;height:0px" id="iframe_submit" name="iframe_submit"></iframe>
</div>
