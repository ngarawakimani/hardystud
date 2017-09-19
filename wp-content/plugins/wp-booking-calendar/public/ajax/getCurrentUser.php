<?php
include '../common.php';
global $current_user;
wp_get_current_user();

if($current_user->ID >0) {

	echo $current_user->user_firstname."|".$current_user->user_lastname."|".$current_user->user_email."|".$current_user->ID;
} else {
	echo "error";
}
?>
