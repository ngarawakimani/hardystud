<?php
/**
 * Single Event Meta (Venue) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/venue.php
 *
 * @package TribeEventsCalendar
 */

if ( ! tribe_get_venue_id() ) {
	return;
}

$phone   = tribe_get_phone();
$website = tribe_get_venue_website_link();

?>

<div class="tribe-events-meta-group tribe-events-meta-group-venue">
	<dl>
		<?php do_action( 'tribe_events_single_meta_venue_section_start' ) ?>
		<?php if ( tribe_address_exists() ) : ?>
			<dd class="location">
				<address class="tribe-events-address">
						<?php echo tribe_get_venue() ?>
					<?php echo tribe_get_full_address(); ?>

				</address>
				<div class="link-to-map">
					<?php if ( tribe_show_google_map_link() ) : ?>
						<?php echo tribe_get_map_link_html(); ?>
					<?php endif; ?>
				</div>
			</dd>
			
		<?php endif; ?>

		<?php if ( ! empty( $phone ) ): ?>
			<dt> <?php esc_html_e( 'Phone:', 'happyrider' ) ?> </dt>
			<dd class="tel"> <?php echo esc_attr($phone) ?> </dd>
		<?php endif ?>

		<?php if ( ! empty( $website ) ): ?>
			<dt> <?php esc_html_e( 'Website:', 'happyrider' ) ?> </dt>
			<dd class="url"> <?php echo esc_attr($website) ?> </dd>
		<?php endif ?>

		<?php do_action( 'tribe_events_single_meta_venue_section_end' ) ?>
	</dl>
</div>
