<?php
/* Mega Main Menu support functions
------------------------------------------------------------------------------- */

// Check if MegaMenu installed and activated
if ( !function_exists( 'happyrider_exists_megamenu' ) ) {
	function happyrider_exists_megamenu() {
		return class_exists('mega_main_init');
	}
}
?>