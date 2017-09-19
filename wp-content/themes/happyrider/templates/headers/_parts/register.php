<div id="popup_registration" class="popup_wrap popup_registration bg_tint_light">
	<a href="#" class="popup_close"></a>
	<div class="form_wrap">
		<form name="registration_form" method="post" class="popup_form registration_form">
			<input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url('/')); ?>"/>
			<div class="form_left">
				<div class="popup_form_field login_field iconed_field icon-user"><input type="text" id="registration_username" name="registration_username"  value="" placeholder="<?php _e('User name (login)', 'happyrider'); ?>"></div>
				<div class="popup_form_field email_field iconed_field icon-mail"><input type="text" id="registration_email" name="registration_email" value="" placeholder="<?php _e('E-mail', 'happyrider'); ?>"></div>
				<div class="popup_form_field agree_field">
					<input type="checkbox" value="agree" id="registration_agree" name="registration_agree">
					<label for="registration_agree"><?php _e('I agree with', 'happyrider'); ?></label> <a href="#"><?php _e('Terms &amp; Conditions', 'happyrider'); ?></a>
				</div>
				<div class="popup_form_field submit_field"><input type="submit" class="submit_button" value="<?php _e('Sign Up', 'happyrider'); ?>"></div>
			</div>
			<div class="form_right">
				<div class="popup_form_field password_field iconed_field icon-lock"><input type="password" id="registration_pwd"  name="registration_pwd"  value="" placeholder="<?php _e('Password', 'happyrider'); ?>"></div>
				<div class="popup_form_field password_field iconed_field icon-lock"><input type="password" id="registration_pwd2" name="registration_pwd2" value="" placeholder="<?php _e('Confirm Password', 'happyrider'); ?>"></div>
				<div class="popup_form_field description_field"><?php _e('Minimum 6 characters', 'happyrider'); ?></div>
			</div>
		</form>
		<div class="result message_block"></div>
	</div>	<!-- /.registration_wrap -->
</div>		<!-- /.user-popUp -->
