<?php
include 'common.php';


$mail_id=$_GET["mail_id"];

$bookingMailObj->setMail($mail_id);
if(isset($_POST["mail_text"])) {	
	$bookingMailObj->updateMail();	
}
$bookingMailObj->setMail($mail_id);
?>
<script type="text/javascript" src="<?php echo BOOKING_ADMIN_URL.'js/tiny_mce/tiny_mce.js';?>"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "exact",
		elements : "mail_text, mail_signature, mail_cancel_text",
		theme : "advanced",
		plugins:"paste",
		theme_advanced_buttons1 : "pastetext,|,bold,italic,underline,strikethrough,|,bullist,numlist,|,indent,outdent,|,undo,redo,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,charmap",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 :"",
		theme_advanced_disable : "image,anchor,cleanup,help,code,hr,removeformat,sub,sup",
		paste_text_use_dialog : true,
		relative_urls : false,
		remove_script_host : false

	});
	
	function checkData(frm) {
		with(frm) {
			if(mail_subject.value=='') {
				alert("<?php echo esc_js(__( 'Insert mail subject', 'wp-booking-calendar' )); ?>");
				return false;
			} else if(mail_text.value=='') {
				alert("<?php echo esc_js(__( 'Insert mail text', 'wp-booking-calendar' )); ?>");
				return false;
			} else {
				return true;
			}
		}
	}
</script>

<div class="booking_padding_20 booking_font_14 booking_line_percent booking_bg_fff">
        

      <div class="booking_font_bold"><?php echo $bookingMailObj->getMailName(); ?></div>
     
      <form name="editsettings" action="" method="post" onsubmit="return checkData(this);" tmt:validate="true" style="display:inline;">           
      		
            <!-- email subject -->
            <div class="booking_font_bold booking_margin_t_20"><label for="mail_subject"><?php echo __( 'Email subject', 'wp-booking-calendar' ); ?></label></div>
          
            <div class="booking_margin_t_10"><input type="text" class="booking_width_100p" id="mail_subject" name="mail_subject" value="<?php echo $bookingMailObj->getMailSubject(); ?>" tmt:required="true" tmt:message="<?php echo __( 'Insert mail subject', 'wp-booking-calendar' ); ?>"></div>
          
            <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
      		
            
            <!-- email text -->
            <div class="booking_font_bold"><label for="mail_text"><?php echo __( 'Email text', 'wp-booking-calendar' ); ?></label></div>
            <div class="booking_font_12 booking_margin_t_10">
				<?php echo __( 'The commands in square brackets are necessary for dynamically inserting the data. If you modify or delete them, data will not be inserted.', 'wp-booking-calendar' ); ?><br />
                <strong>[customer-name]</strong>:<?php echo __( 'it inserts the user name', 'wp-booking-calendar' ); ?><br />
                <strong>[reservation-details]</strong>:<?php echo __( 'it inserts reservation details', 'wp-booking-calendar' ); ?><br />
                <?php
                if($mail_id==2) {
					?>
                    <strong>[confirmation-link]</strong>:<?php echo __( 'it inserts the confirmation link', 'wp-booking-calendar' ); ?><br />
                    <strong>[confirmation-link-url]</strong>:<?php echo __( 'it inserts the extended link to be copied and pasted into the URL', 'wp-booking-calendar' ); ?>
                    <?php
                   
                }
                ?>
            </div>
      		<div class="booking_margin_t_10"><textarea class="booking_width_100p booking_height_150 booking_bg_f6f" id="mail_text" name="mail_text"><?php echo $bookingMailObj->getMailText(); ?></textarea></div>
      
      		<div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
      
      
      		<!-- email text -->
			<?php
            if($bookingMailObj->getMailId() == 1 || $bookingMailObj->getMailId() == 4) {
            ?>
            
            <div class="booking_font_bold"><label for="mail_text"><?php echo __( 'Email cancellation text.', 'wp-booking-calendar' ); ?></label> <?php echo __( 'Status:', 'wp-booking-calendar' ); ?> <?php if($bookingSettingObj->getReservationCancel() == "1") { echo "<span style='color:#669900'>".__( 'ENABLED', 'wp-booking-calendar' )."</span>"; } else { echo "<span style='color:#990000'>".__( 'DISABLED', 'wp-booking-calendar' )."</span>"; }?></div>
            <div class="booking_font_12 booking_margin_t_10" style="padding: 10px 0; font-size: 13px;">
				<?php echo __( 'The commands in square brackets are necessary for dynamically inserting the data. If you modify or delete them, data will not be inserted.', 'wp-booking-calendar' ) ?><br />
                <strong>[cancellation-link]</strong>:<?php echo __( 'it inserts the cancellation link', 'wp-booking-calendar' ); ?><br />
                <strong>[cancellation-link-url]</strong>:<?php echo __( 'it inserts the extended link to be copied and pasted into the URL', 'wp-booking-calendar' ); ?><br />
            </div>
            <div class="booking_margin_t_10"><textarea class="booking_width_100p booking_height_150 booking_bg_f6f" id="mail_cancel_text" name="mail_cancel_text"><?php echo $bookingMailObj->getMailCancelText(); ?></textarea></div>
            
            <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
            
            
            
			<?php
            } 
            ?>
            
            <!-- email signature -->
      		<div class="booking_font_bold"><label for="mail_signature"><?php echo __( 'Email signature', 'wp-booking-calendar' ); ?></label></div>
      		<div class="booking_margin_t_10"><textarea class="booking_width_100p booking_bg_f6f" id="mail_signature" name="mail_signature"><?php echo $bookingMailObj->getMailSignature(); ?></textarea></div>
    
      
            <!-- 
            =======================
            === control buttons ==
            =======================
            -->
            <div class="booking_bg_333 booking_padding_10 booking_margin_t_20">
                <!-- cancel -->
                <div class="booking_float_left"><a href="javascript:document.location.href='?page=wp-booking-calendar-mails';" class="booking_bg_ccc booking_admin_button booking_grey_button booking_mark_fff"><?php echo __( 'CANCEL', 'wp-booking-calendar' ); ?></a></div>
                <div class="booking_float_left booking_margin_l_20"><input type="submit" name="saveunpublish" value="<?php echo __( 'SAVE', 'wp-booking-calendar' ); ?>" class="booking_bg_693 booking_admin_button booking_green_button booking_mark_fff"></div>
                <div class="booking_cleardiv"></div>
                
            </div>
            
            
         </form>
          
        
        
</div>
