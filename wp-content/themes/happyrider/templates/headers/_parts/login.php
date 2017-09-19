<div id="popup_login" class="popup_wrap popup_login bg_tint_light">
	<a href="#" class="popup_close"></a>
	<div class="form_wrap">
		<div class="form_left">
			<form action="<?php echo wp_login_url(); ?>" method="post" name="login_form" class="popup_form login_form">
				<input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url('/')); ?>">
				<div class="popup_form_field login_field iconed_field icon-user"><input type="text" id="log" name="log" value="" placeholder="<?php _e('Login or Email', 'happyrider'); ?>"></div>
				<div class="popup_form_field password_field iconed_field icon-lock"><input type="password" id="password" name="pwd" value="" placeholder="<?php _e('Password', 'happyrider'); ?>"></div>
				<div class="popup_form_field remember_field">
					<a href="<?php echo wp_lostpassword_url( get_permalink() ); ?>" class="forgot_password"><?php _e('Forgot password?', 'happyrider'); ?></a>
					<input type="checkbox" value="forever" id="rememberme" name="rememberme">
					<label for="rememberme"><?php _e('Remember me', 'happyrider'); ?></label>
				</div>
				<div class="popup_form_field submit_field"><input type="submit" class="submit_button" value="<?php _e('Login', 'happyrider'); ?>"></div>
			</form>
		</div>
		<div class="form_right">
			<div class="login_socials_title"><?php _e('You can login using your social profile', 'happyrider'); ?></div>
			<div class="login_socials_list">
				<?php echo happyrider_sc_socials(array('size'=>"tiny", 'shape'=>"round", 'socials'=>"facebook=#|twitter=#|gplus=#")); ?>
			</div>
			<div class="login_socials_problem"><a href="#"><?php _e('Problem with login?', 'happyrider'); ?></a></div>
			<div class="result message_block"></div>
		</div>
	</div>	<!-- /.login_wrap -->
</div>		<!-- /.popup_login -->
