					<div class="logo">
						<a href="<?php echo esc_url(home_url('/')); ?>"><?php
							echo !empty($HAPPYRIDER_GLOBALS['logo'])
								? '<img src="'.esc_url($HAPPYRIDER_GLOBALS['logo']).'" class="logo_main" alt="">'
								: ''; 
							echo ($HAPPYRIDER_GLOBALS['logo_text']
								? '<div class="logo_text">'.($HAPPYRIDER_GLOBALS['logo_text']).'</div>'
								: '');
							echo ($HAPPYRIDER_GLOBALS['logo_slogan']
								? '<div class="logo_slogan">' . esc_html($HAPPYRIDER_GLOBALS['logo_slogan']) . '</div>'
								: '');
						?></a>
					</div>
