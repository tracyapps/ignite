<?php
// Support custom "anchor" values.
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
	$anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_names = 'events_block';
if ( ! empty( $block['className'] ) ) {
	$class_names .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$class_names .= ' align' . $block['align'];
}

$layout = get_field( 'layout' );
$class_names .= 'layout-' . esc_attr( $layout );

$events_display = get_field( 'display' );
$num_to_display = null;
$past_events = false;
$event_order = 'ASC';
$manual_event_select = null;

if( 'upcoming-auto' == $events_display) {
	$num_to_display = get_field( 'number_of_events' );
}

if( 'manual-select' == $events_display) {
	$events_array = get_field( 'select_events' );
}

if( 'past-events' == $events_display) {
	$past_events = true;
	$num_to_display = get_field( 'display_perimeters' ) == 'all' ? '99' : get_field( 'number_of_past_events' );
	$event_order = 'DESC';
}


$args = [
	'posts_per_page' => $num_to_display,
	'order'          =>$event_order,
	'show_past'      => $past_events,
	'post__in'       => $manual_event_select,
];



?>

<section <?php echo esc_attr( $anchor ); ?> class="<?php echo esc_attr( $class_names ); ?>">
	<h2 class="events_block_title wide_width"><?php echo esc_html( get_field( 'heading_text' ) ) ?></h2>
	<div class="<?php echo esc_attr( $layout ); ?>_container">
		<?php
		$events = ITS_get_events( $args );

		if ( $events->have_posts() ) {
			while ( $events->have_posts() ) {
				$events->the_post();
				ITS_render_event_item_date_time( get_the_ID() );
			}
			wp_reset_postdata();
		}
		?>
	</div>
</section>
