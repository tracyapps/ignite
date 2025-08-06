<?php
/**
 * Display Heros Block
 */

// Support custom "anchor" values.
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
	$anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_names = 'display_heros_block';
if ( ! empty( $block['className'] ) ) {
	$class_names .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$class_names .= ' align' . $block['align'];
}

$year_id = get_field( 'year_to_display' );
?>

<section <?php echo esc_attr( $anchor ); ?> class="<?php echo esc_attr( $class_names ); ?>" >
	<?php ITS_display_heroes_by_calendar_year(  $year_id  ); ?>
</section>
