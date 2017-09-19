<?php
/**
 * Single Event Meta (Details) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/details.php
 *
 * @package TribeEventsCalendar
 */


$time_format = get_option( 'time_format', Tribe__Events__Date_Utils::TIMEFORMAT );
$time_range_separator = tribe_get_option( 'timeRangeSeparator', ' - ' );

$start_datetime = tribe_get_start_date();
$start_date = tribe_get_start_date( null, false );
$start_time = tribe_get_start_date( null, false, $time_format );
$start_ts = tribe_get_start_date( null, false, Tribe__Events__Date_Utils::DBDATEFORMAT );

$end_datetime = tribe_get_end_date();
$end_date = tribe_get_end_date( null, false );
$end_time = tribe_get_end_date( null, false, $time_format );
$end_ts = tribe_get_end_date( null, false, Tribe__Events__Date_Utils::DBDATEFORMAT );

$cost = tribe_get_formatted_cost();
$website = tribe_get_event_website_link();

$organizer_ids = tribe_get_organizer_ids();
$multiple = count( $organizer_ids ) > 1;

$phone = tribe_get_organizer_phone();
$email = tribe_get_organizer_email();
$website = tribe_get_organizer_website_link();

?>

<div class="tribe-events-meta-group tribe-events-meta-group-details">
	<h3 class="tribe-events-single-section-title"> <?php esc_html_e( 'Details', 'happyrider' ) ?> </h3>
	<dl>

		<?php
		do_action( 'tribe_events_single_meta_details_section_start' );

		// All day (multiday) events
		if ( tribe_event_is_all_day() && tribe_event_is_multiday() ) :
			?>

			<dt> <?php esc_html_e( 'Start:', 'happyrider' ) ?> </dt>
			<dd>
				<abbr class="tribe-events-abbr updated published dtstart" title="<?php esc_attr_e( $start_ts, 'happyrider', 'happyrider' ) ?>"> <?php if (!empty($start_date)) {esc_html_e( $start_date, 'happyrider', 'happyrider');} ?> </abbr>
			</dd>

			<dt> <?php esc_html_e( 'End:', 'happyrider' ) ?> </dt>
			<dd>
				<abbr class="tribe-events-abbr dtend" title="<?php esc_attr_e( $end_ts, 'happyrider', 'happyrider' ) ?>"> <?php if (!empty($end_date)) {esc_html_e( $end_date, 'happyrider', 'happyrider' );} ?> </abbr>
			</dd>

		<?php
		// All day (single day) events
		elseif ( tribe_event_is_all_day() ):
			?>

			<dt> <?php esc_html_e( 'Date:', 'happyrider' ) ?> </dt>
			<dd>
				<abbr class="tribe-events-abbr updated published dtstart" title="<?php esc_attr_e( $start_ts, 'happyrider', 'happyrider' ) ?>"> <?php if (!empty($start_date)) {esc_html_e( $start_date, 'happyrider', 'happyrider' );} ?> </abbr>
			</dd>

		<?php
		// Multiday events
		elseif ( tribe_event_is_multiday() ) :
			?>

			<dt> <?php esc_html_e( 'Start:', 'happyrider' ) ?> </dt>
			<dd>
				<abbr class="tribe-events-abbr updated published dtstart" title="<?php esc_attr_e( $start_ts, 'happyrider', 'happyrider' ) ?>"> <?php if (!empty($start_datetime)) {esc_html_e( $start_datetime, 'happyrider', 'happyrider' );} ?> </abbr>
			</dd>

			<dt> <?php esc_html_e( 'End:', 'happyrider' ) ?> </dt>
			<dd>
				<abbr class="tribe-events-abbr dtend" title="<?php esc_attr_e( $end_ts, 'happyrider', 'happyrider' ) ?>"> <?php if (!empty($end_datetime)) {esc_html_e( $end_datetime, 'happyrider', 'happyrider' );} ?> </abbr>
			</dd>

		<?php
		// Single day events
		else :
			?>

			<dt> <?php esc_html_e( 'Start:', 'happyrider' ) ?> </dt>
			<dd>
				<abbr class="tribe-events-abbr updated published dtstart" title="<?php esc_attr_e( $start_ts, 'happyrider', 'happyrider' ) ?>"> <?php if (!empty($start_datetime)) {esc_html_e( $start_datetime, 'happyrider', 'happyrider' );} ?> </abbr>
			</dd>

			<dt> <?php esc_html_e( 'End:', 'happyrider' ) ?> </dt>
			<dd>
				<abbr class="tribe-events-abbr dtend" title="<?php esc_attr_e( $end_ts, 'happyrider', 'happyrider' ) ?>"> <?php if (!empty($end_datetime)) {esc_html_e( $end_datetime, 'happyrider', 'happyrider' );} ?> </abbr>
			</dd>
		

		<?php endif ?>

		<?php
		// Event Cost
		if ( ! empty( $cost ) ) : ?>

			<dt> <?php esc_html_e( 'Cost:', 'happyrider' ) ?> </dt>
			<dd class="tribe-events-event-cost"> <?php if (!empty($cost)) {esc_html_e( $cost, 'happyrider', 'happyrider' );} ?> </dd>
		<?php endif ?>

		<?php
		echo tribe_get_event_categories(
			get_the_id(), array(
				'before'       => '',
				'sep'          => ', ',
				'after'        => '',
				'label'        => null, // An appropriate plural/singular label will be provided
				'label_before' => '<dt>',
				'label_after'  => '</dt>',
				'wrap_before'  => '<dd class="tribe-events-event-categories">',
				'wrap_after'   => '</dd>',
			)
		);
		?>

		<?php echo tribe_meta_event_tags( sprintf( __( '%s Tags:', 'happyrider' ), tribe_get_event_label_singular() ), ', ', false ) ?>

		<?php
		// Event Website
		if ( ! empty( $website ) ) : ?>

			<dt> <?php esc_html_e( 'Website:', 'happyrider' ) ?> </dt>
			<dd class="tribe-events-event-url"> <?php echo esc_attr($website); ?> </dd>
		<?php endif ?>

		<?php do_action( 'tribe_events_single_meta_details_section_end' ) ?>
	</dl>
	<dt> <?php esc_html_e( 'Organizer:', 'happyrider' ) ?> </dt>
	<dl>
		<?php
		do_action( 'tribe_events_single_meta_organizer_section_start' );
		foreach ( $organizer_ids as $organizer ) {
			if ( ! $organizer ) {
				continue;
			}

			?>
			<dd class="fn org">
				<?php echo tribe_get_organizer( $organizer ) ?>
			</dd>
			<?php
		}

		if ( ! $multiple ) { // only show organizer details if there is one
			if ( ! empty( $phone ) ) {
				?>
				<dt>
					<?php esc_html_e( 'Phone:', 'happyrider' ) ?>
				</dt>
				<dd class="tel">
					<?php echo esc_html( $phone ); ?>
				</dd>
				<?php
			}//end if

			if ( ! empty( $email ) ) {
				?>
				<dt>
					<?php esc_html_e( 'Email:', 'happyrider' ) ?>
				</dt>
				<dd class="email">
					<?php echo esc_html( $email ); ?>
				</dd>
				<?php
			}//end if

			if ( ! empty( $website ) ) {
				?>
				<dt>
					<?php esc_html_e( 'Website:', 'happyrider' ) ?>
				</dt>
				<dd class="url">
					<?php echo esc_attr($website); ?>
				</dd>
				<?php
			}//end if
		}//end if

		do_action( 'tribe_events_single_meta_organizer_section_end' );
		?>
	</dl>
</div>
