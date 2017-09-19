<?php
/**
 * List View Single Event
 * This file contains one event in the list view
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/single-event.php
 *
 * @package TribeEventsCalendar
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$time_format = get_option( 'time_format', Tribe__Events__Date_Utils::TIMEFORMAT );
// Setup an array of venue details for use later in the template
$venue_details = tribe_get_venue_details();

// Venue microformats
$has_venue_address = ( ! empty( $venue_details['address'] ) ) ? ' location' : '';

// Organizer
$organizer = tribe_get_organizer();

?>

<!-- Event Cost -->
<?php if ( tribe_get_cost() ) : ?>
	<div class="tribe-events-event-cost">
		<span><?php echo tribe_get_cost( null, true ); ?></span>
	</div>
<?php endif; ?>

<div class="event-wrapper list-style clearfix">
			<?php echo tribe_event_featured_image( $event_id, 'full', false ); ?>
			<div class="content-event-wrap">
<?php the_title( '<h2 class="tribe-events-single-event-title summary entry-title">', '</h2>' ); ?>

<div class="tribe-events-schedule updated published">
		<h3 class="clearfix"><span class="date-of-event details column-1_3"><?php echo tribe_get_start_date( null, false ); ?></span>
		<span class="time-of-event details column-1_3"><?php echo tribe_get_start_date(null, false, 'l') .',</br>'.tribe_get_start_date(null, false, $time_format) . ' -'. $end_time = tribe_get_end_date( null, false, $time_format );?></span>
		<span class="location-of-event details column-1_3"><?php echo tribe_get_full_address(); ?></span>
		</h3>

		<?php if ( tribe_get_cost() ) : ?>
			<span class="tribe-events-divider">|</span>
			<span class="tribe-events-cost"><?php echo tribe_get_cost( null, true ) ?></span>
		<?php endif; ?>
	
			<!-- Event content -->
			<?php do_action( 'tribe_events_single_event_before_the_content' ) ?>
			<div class="tribe-events-single-event-description tribe-events-content entry-content description entry-summary">
			<?php echo esc_attr($descript = substr(get_the_excerpt(), 0 , 150));?>
			</div>
			<a href="<?php echo esc_url( tribe_get_event_link() ); ?>" class="tribe-events-read-more" rel="bookmark"><?php esc_html_e( 'Details', 'happyrider' ) ?></a>

</div>
</div>
</div>
<?php
do_action( 'tribe_events_after_the_content' );