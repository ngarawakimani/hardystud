<?php
// Autoload layouts in this folder
$name = pathinfo(__FILE__);
happyrider_autoload_folder( 'templates/'.trim($name['filename']) );
?>