<?php
/**
 * Child-Theme functions and definitions
 */

function hardy_stud_styles() {
    //media queries
    wp_enqueue_style( 'hardystud-media-style', get_stylesheet_directory_uri(). '/media-queries.css' );
	// font-awesome styles
	wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	wp_enqueue_script( 'script', get_stylesheet_directory_uri() . '/custom.js', array ( 'jquery' ), 1.1, true);
}
add_action('wp_enqueue_scripts', 'hardy_stud_styles');

/*Custom logo Hardy stud*/
function hardystud_custom_logo_setup() {
    $defaults = array(
        'height'      => 120,
        'width'       => 430,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array( 'site-title', 'site-description' ),
    );
    add_theme_support( 'custom-logo', $defaults );
}
add_action( 'after_setup_theme', 'hardystud_custom_logo_setup' );


?>