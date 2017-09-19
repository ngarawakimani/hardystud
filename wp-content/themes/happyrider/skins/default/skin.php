<?php
/**
 * Skin file for the theme.
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('happyrider_action_skin_theme_setup')) {
	add_action( 'happyrider_action_init_theme', 'happyrider_action_skin_theme_setup', 1 );
	function happyrider_action_skin_theme_setup() {

		// Add skin fonts in the used fonts list
		add_filter('happyrider_filter_used_fonts',			'happyrider_filter_skin_used_fonts');
		// Add skin fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('happyrider_filter_list_fonts',			'happyrider_filter_skin_list_fonts');

		// Add skin stylesheets
		add_action('happyrider_action_add_styles',			'happyrider_action_skin_add_styles');
		// Add skin inline styles
		add_filter('happyrider_filter_add_styles_inline',		'happyrider_filter_skin_add_styles_inline');
		// Add skin responsive styles
		add_action('happyrider_action_add_responsive',		'happyrider_action_skin_add_responsive');
		// Add skin responsive inline styles
		add_filter('happyrider_filter_add_responsive_inline',	'happyrider_filter_skin_add_responsive_inline');

		// Add skin scripts
		add_action('happyrider_action_add_scripts',			'happyrider_action_skin_add_scripts');
		// Add skin scripts inline
		add_action('happyrider_action_add_scripts_inline',	'happyrider_action_skin_add_scripts_inline');

		// Add skin less files into list for compilation
		add_filter('happyrider_filter_compile_less',			'happyrider_filter_skin_compile_less');


		/* Color schemes
		
		// Accenterd colors
		accent1			- theme accented color 1
		accent1_hover	- theme accented color 1 (hover state)
		accent2			- theme accented color 2
		accent2_hover	- theme accented color 2 (hover state)		
		accent3			- theme accented color 3
		accent3_hover	- theme accented color 3 (hover state)		
		
		// Headers, text and links
		text			- main content
		text_light		- post info
		text_dark		- headers
		inverse_text	- text on accented background
		inverse_light	- post info on accented background
		inverse_dark	- headers on accented background
		inverse_link	- links on accented background
		inverse_hover	- hovered links on accented background
		
		// Block's border and background
		bd_color		- border for the entire block
		bg_color		- background color for the entire block
		bg_image, bg_image_position, bg_image_repeat, bg_image_attachment  - first background image for the entire block
		bg_image2,bg_image2_position,bg_image2_repeat,bg_image2_attachment - second background image for the entire block
		
		// Alternative colors - highlight blocks, form fields, etc.
		alter_text		- text on alternative background
		alter_light		- post info on alternative background
		alter_dark		- headers on alternative background
		alter_link		- links on alternative background
		alter_hover		- hovered links on alternative background
		alter_bd_color	- alternative border
		alter_bd_hover	- alternative border for hovered state or active field
		alter_bg_color	- alternative background
		alter_bg_hover	- alternative background for hovered state or active field 
		alter_bg_image, alter_bg_image_position, alter_bg_image_repeat, alter_bg_image_attachment - background image for the alternative block
		
		*/

		// Add color schemes
		happyrider_add_color_scheme('original', array(

			'title'					=> __('Original', 'happyrider'),

			// Accent colors
			'accent1'				=> '#fa6a36',   //
			'accent1_hover'			=> '#ee5f32',   //
//			'accent2'				=> '#ff0000',
//			'accent2_hover'			=> '#aa0000',
//			'accent3'				=> '',
//			'accent3_hover'			=> '',
			
			// Headers, text and links colors
			'text'					=> '#75716f',   // text on site
			'text_light'			=> '#acb4b6',
			'text_dark'				=> '#3a3a3a',   // headers
			'inverse_text'			=> '#ffffff',   //
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
			
			// Whole block border and background
			'bd_color'				=> '#e4e7e8',
			'bg_color'				=> '#f4f4f3',   //
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#8a8a8a',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#292929',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#189799',
			'alter_bd_color'		=> '#dddddd',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#ffffff',   //top
			'alter_bg_hover'		=> '#f0f0f0',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			'transparent' => 'transparent',
			)
		);
/*
		// Add color schemes
		happyrider_add_color_scheme('dark', array(

			'title'					=> __('Dark', 'happyrider'),

			// Accent colors
			'accent1'				=> '#20c7ca',
			'accent1_hover'			=> '#189799',
//			'accent2'				=> '#ff0000',
//			'accent2_hover'			=> '#aa0000',
//			'accent3'				=> '',
//			'accent3_hover'			=> '',
			
			// Headers, text and links colors
			'text'					=> '#909090',
			'text_light'			=> '#a0a0a0',
			'text_dark'				=> '#e0e0e0',
			'inverse_text'			=> '#f0f0f0',
			'inverse_light'			=> '#e0e0e0',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#e5e5e5',
			
			// Whole block border and background
			'bd_color'				=> '#000000',
			'bg_color'				=> '#333333',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#999999',
			'alter_light'			=> '#aaaaaa',
			'alter_dark'			=> '#d0d0d0',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#29fbff',
			'alter_bd_color'		=> '#909090',
			'alter_bd_hover'		=> '#888888',
			'alter_bg_color'		=> '#666666',
			'alter_bg_hover'		=> '#505050',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

*/
		/* Font slugs:
		h1 ... h6	- headers
		p			- plain text
		link		- links
		info		- info blocks (Posted 15 May, 2015 by John Doe)
		menu		- main menu
		submenu		- dropdown menus
		logo		- logo text
		button		- button's caption
		input		- input fields
		*/

		// Add Custom fonts
		happyrider_add_custom_font('h1', array(
			'title'			=> __('Heading 1', 'happyrider'),
			'description'	=> '',
			'font-family'	=> 'Merriweather',
			'font-size' 	=> '4.133em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0'
			)
		);
		happyrider_add_custom_font('h2', array(
			'title'			=> __('Heading 2', 'happyrider'),
			'description'	=> '',
			'font-family'	=> 'Merriweather',
			'font-size' 	=> '3.333em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '2.15em',
			'margin-top'	=> '',
			'margin-bottom'	=> '0'
			)
		);
		happyrider_add_custom_font('h3', array(
			'title'			=> __('Heading 3', 'happyrider'),
			'description'	=> '',
			'font-family'	=> 'Merriweather',
			'font-size' 	=> '2.333em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.85em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0'
			)
		);
		happyrider_add_custom_font('h4', array(
			'title'			=> __('Heading 4', 'happyrider'),
			'description'	=> '',
			'font-family'	=> 'Merriweather',
			'font-size' 	=> '1.667em',
			'font-weight'	=> '700',
			'font-style'	=> 'italic',
			'line-height'	=> '3.06em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0'
			)
		);
		happyrider_add_custom_font('h5', array(
			'title'			=> __('Heading 5', 'happyrider'),
			'description'	=> '',
			'font-family'	=> 'Merriweather',
			'font-size' 	=> '1.333em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '3em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0'
			)
		);
		happyrider_add_custom_font('h6', array(
			'title'			=> __('Heading 6', 'happyrider'),
			'description'	=> '',
			'font-family'	=> 'Merriweather',
			'font-size' 	=> '0.933em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '3.4em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0'
			)
		);
		happyrider_add_custom_font('p', array(
			'title'			=> __('Text', 'happyrider'),
			'description'	=> '',
			'font-family'	=> 'Open Sans',
			'font-size' 	=> '15px',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.75em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.5em'
			)
		);
		happyrider_add_custom_font('link', array(
			'title'			=> __('Links', 'happyrider'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		happyrider_add_custom_font('info', array(
			'title'			=> __('Post info', 'happyrider'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.4117em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.5em'
			)
		);
		happyrider_add_custom_font('menu', array(
			'title'			=> __('Main menu items', 'happyrider'),
			'description'	=> '',
			'font-family'	=> 'Open Sans',
			'font-size' 	=> '0.933em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.75em',
			'margin-top'	=> '1.2em',
			'margin-bottom'	=> '1.2em'
			)
		);
		happyrider_add_custom_font('submenu', array(
			'title'			=> __('Dropdown menu items', 'happyrider'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '',
			'margin-bottom'	=> ''
			)
		);
		happyrider_add_custom_font('logo', array(
			'title'			=> __('Logo', 'happyrider'),
			'description'	=> '',
			'font-family'	=> 'Merriweather',
			'font-size' 	=> '1.467em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.35em',
			'margin-top'	=> '2.2em',
			'margin-bottom'	=> '0.2em'
			)
		);
		happyrider_add_custom_font('button', array(
			'title'			=> __('Buttons', 'happyrider'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.3em'
			)


		);
		happyrider_add_custom_font('input', array(
			'title'			=> __('Input fields', 'happyrider'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.3em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Skin's fonts
//------------------------------------------------------------------------------

// Add skin fonts in the used fonts list
if (!function_exists('happyrider_filter_skin_used_fonts')) {
	//add_filter('happyrider_filter_used_fonts', 'happyrider_filter_skin_used_fonts');
	function happyrider_filter_skin_used_fonts($theme_fonts) {
		//$theme_fonts['Roboto'] = 1;
		//$theme_fonts['Love Ya Like A Sister'] = 1;
        $theme_fonts['Open Sans'] = 1;
        $theme_fonts['Merriweather'] = 1;
		return $theme_fonts;
	}
}

// Add skin fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('happyrider_filter_skin_list_fonts')) {
	//add_filter('happyrider_filter_list_fonts', 'happyrider_filter_skin_list_fonts');
	function happyrider_filter_skin_list_fonts($list) {
		// Example:
		// if (!isset($list['Advent Pro'])) {
		//		$list['Advent Pro'] = array(
		//			'family' => 'sans-serif',																						// (required) font family
		//			'link'   => 'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
		//			'css'    => happyrider_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
		//			);
		// }
        if (!isset($list['Open Sans']))	$list['Open Sans'] = array('family'=>'sans-serif', 'link' => 'Open+Sans:400,700,600,600italic,700italic,400italic');
        if (!isset($list['Merriweather']))	$list['Merriweather'] = array('family'=>'serif', 'link' => 'Merriweather:400,300,300italic,400italic,700,700italic)');
		return $list;
	}
}

//------------------------------------------------------------------------------
// Skin's stylesheets
//------------------------------------------------------------------------------
// Add skin stylesheets
if (!function_exists('happyrider_action_skin_add_styles')) {
	//add_action('happyrider_action_add_styles', 'happyrider_action_skin_add_styles');
	function happyrider_action_skin_add_styles() {
		// Add stylesheet files
		happyrider_enqueue_style( 'happyrider-skin-style', happyrider_get_file_url('skin.css'), array(), null );
		if (file_exists(happyrider_get_file_dir('skin.customizer.css')))
			happyrider_enqueue_style( 'happyrider-skin-customizer-style', happyrider_get_file_url('skin.customizer.css'), array(), null );
	}
}

// Add skin inline styles
if (!function_exists('happyrider_filter_skin_add_styles_inline')) {
	//add_filter('happyrider_filter_add_styles_inline', 'happyrider_filter_skin_add_styles_inline');
	function happyrider_filter_skin_add_styles_inline($custom_style) {
		// Todo: add skin specific styles in the $custom_style to override
		//       rules from style.css and shortcodes.css
		// Example:
		//		$scheme = happyrider_get_custom_option('body_scheme');
		//		if (empty($scheme)) $scheme = 'original';
		//		$clr = happyrider_get_scheme_color('accent1');
		//		if (!empty($clr)) {
		// 			$custom_style .= '
		//				a,
		//				.bg_tint_light a,
		//				.top_panel .content .search_wrap.search_style_regular .search_form_wrap .search_submit,
		//				.top_panel .content .search_wrap.search_style_regular .search_icon,
		//				.search_results .post_more,
		//				.search_results .search_results_close {
		//					color:'.esc_attr($clr).';
		//				}
		//			';
		//		}
		return $custom_style;	
	}
}

// Add skin responsive styles
if (!function_exists('happyrider_action_skin_add_responsive')) {
	//add_action('happyrider_action_add_responsive', 'happyrider_action_skin_add_responsive');
	function happyrider_action_skin_add_responsive() {
		$suffix = happyrider_param_is_off(happyrider_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
		if (file_exists(happyrider_get_file_dir('skin.responsive'.($suffix).'.css')))
			happyrider_enqueue_style( 'theme-skin-responsive-style', happyrider_get_file_url('skin.responsive'.($suffix).'.css'), array(), null );
	}
}

// Add skin responsive inline styles
if (!function_exists('happyrider_filter_skin_add_responsive_inline')) {
	//add_filter('happyrider_filter_add_responsive_inline', 'happyrider_filter_skin_add_responsive_inline');
	function happyrider_filter_skin_add_responsive_inline($custom_style) {
		return $custom_style;	
	}
}

// Add skin.less into list files for compilation
if (!function_exists('happyrider_filter_skin_compile_less')) {
	//add_filter('happyrider_filter_compile_less', 'happyrider_filter_skin_compile_less');
	function happyrider_filter_skin_compile_less($files) {
		if (file_exists(happyrider_get_file_dir('skin.less'))) {
		 	$files[] = happyrider_get_file_dir('skin.less');
		}
		return $files;	
	}
}



//------------------------------------------------------------------------------
// Skin's scripts
//------------------------------------------------------------------------------

// Add skin scripts
if (!function_exists('happyrider_action_skin_add_scripts')) {
	//add_action('happyrider_action_add_scripts', 'happyrider_action_skin_add_scripts');
	function happyrider_action_skin_add_scripts() {
		if (file_exists(happyrider_get_file_dir('skin.js')))
			happyrider_enqueue_script( 'theme-skin-script', happyrider_get_file_url('skin.js'), array(), null );
		if (happyrider_get_theme_option('show_theme_customizer') == 'yes' && file_exists(happyrider_get_file_dir('skin.customizer.js')))
			happyrider_enqueue_script( 'theme-skin-customizer-script', happyrider_get_file_url('skin.customizer.js'), array(), null );
	}
}

// Add skin scripts inline
if (!function_exists('happyrider_action_skin_add_scripts_inline')) {
	//add_action('happyrider_action_add_scripts_inline', 'happyrider_action_skin_add_scripts_inline');
	function happyrider_action_skin_add_scripts_inline() {
		// Todo: add skin specific scripts
		// Example:
		// echo '<script type="text/javascript">'
		//	. 'jQuery(document).ready(function() {'
		//	. "if (HAPPYRIDER_GLOBALS['theme_font']=='') HAPPYRIDER_GLOBALS['theme_font'] = '" . happyrider_get_custom_font_settings('p', 'font-family') . "';"
		//	. "HAPPYRIDER_GLOBALS['theme_skin_color'] = '" . happyrider_get_scheme_color('accent1') . "';"
		//	. "});"
		//	. "< /script>";
	}
}
?>