<?php
/**
 * Single Event Meta (Map) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/details.php
 *
 * @package TribeEventsCalendar
 */

$map = tribe_get_embedded_map();

if ( empty( $map ) ) {
	return;
}

?>

<div class="tribe-events-venue-map">
<h3 class="tribe-events-single-section-title"> <?php _e( tribe_get_venue_label_singular(), 'happyrider', 'happyrider' ) ?> </h3>
	<?php
	// Display the map.
	do_action( 'tribe_events_single_meta_map_section_start' );
	echo ($map);
	tribe_get_template_part( 'modules/meta/venue' );
	do_action( 'tribe_events_single_meta_map_section_end' );
	?>
</div>
