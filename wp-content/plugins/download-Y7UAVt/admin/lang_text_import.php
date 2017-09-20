<?php
if(isset($_POST["lang_import"])) {
	
	$bookingLangObj->importTextLang();
	update_option('wbc_show_text_import_admin','0');
	?>
    <script>
		alert("Successfull import");
		document.location.href="?page=wp-booking-calendar-welcome";
	</script>
    <?php
}

require_once( ABSPATH . 'wp-admin/admin.php' );

/** WordPress Translation Install API */
require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
?>


<div class="booking_margin_t_30 booking_font_18 booking_bg_fff">

<form name="addcalendar" action="" method="post" onsubmit="return checkData(this);" style="display:inline;" enctype="multipart/form-data">
    <input type="hidden" name="lang_import" value="1" />
    
    <!-- 
    =======================
    === ISO lang to import ==
    =======================
    -->
    <table class="form-table">
    	<?php
		$languages = get_available_languages();
		$translations = wp_get_available_translations();
		if ( ! is_multisite() && defined( 'WPLANG' ) && '' !== WPLANG && 'en_US' !== WPLANG && ! in_array( WPLANG, $languages ) ) {
			$languages[] = WPLANG;
		}
		if ( ! empty( $languages ) || ! empty( $translations ) ) {
		?>
        <tr>
            <th scope="row"><label for="locale">Choose your language</label></th>
            <td>
                
                	<?php
					$locale = get_locale();
					if ( ! in_array( $locale, $languages ) ) {
						$locale = '';
					}
		
					wp_dropdown_languages( array(
						'name'         => 'locale',
						'id'           => 'locale',
						'selected'     => $locale,
						'languages'    => $languages,
						'translations' => $translations,
						'show_available_translations' => ( ! is_multisite() || is_super_admin() ) && wp_can_install_language_pack(),
					) );
					?>
                    <?php
					/*
					<select name="locale" id="locale">
					$json_lang = file_get_contents(dirname(__FILE__).'/libs/languages.json');
					$arrLang = json_decode($json_lang);
					for($i = 0;$i<count($arrLang);$i++) {
						if(strlen($arrLang[$i]->lang)>4) {
							?>
							<option value="<?php echo $arrLang[$i]->lang; ?>"><?php echo $arrLang[$i]->lang; ?></option>
							<?php
						}
					}
					</select>*/
					?>
                 
            </td>
        </tr>
        <?php
		}
		?>
    </table>
    <?php
	/*
    <div class="booking_font_bold booking_margin_t_20"><label for="locale">Choose your language</label></div>
    <div class="booking_font_12"></div>
   
    <div class="booking_font_12 booking_margin_t_10">
        <select name="locale">
        	<?php
			$json_lang = file_get_contents(dirname(__FILE__).'/libs/languages.json');
			$arrLang = json_decode($json_lang);
			for($i = 0;$i<count($arrLang);$i++) {
				if(strlen($arrLang[$i]->lang)>4) {
					?>
					<option value="<?php echo $arrLang[$i]->lang; ?>"><?php echo $arrLang[$i]->lang; ?></option>
					<?php
				}
			}
			?>
        </select>
        
        <div class="booking_cleardiv"></div>
    
    </div>
    
    <div class="booking_margin_tb_20 booking_border_dotted booking_border_t_1 booking_border_666 booking_height_1"></div>
    */
	?>
    

	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="IMPORT!"  /></p>
    
    <?php
	/*
    <!-- bridge buttons -->
    <div class="booking_bg_333 booking_padding_10">
        <!-- cancel -->
        <div class="booking_float_left"><a href="javascript:document.location.href='?page=wp-booking-calendar-welcome';" class="booking_bg_ccc booking_admin_button booking_grey_button booking_mark_fff">CANCEL</a></div>
        <div class="booking_float_left booking_margin_l_20"><input type="submit" id="apply_button" value="IMPORT!" class="booking_bg_693 booking_admin_button booking_green_button booking_mark_fff"></div>
        <div id="loading" style="float:left;margin-top:30px;margin-left:10px"></div>
        <div class="booking_cleardiv"></div>
        
    </div>
    */
	?>
    
    </form>
    
 </div>

        
      
