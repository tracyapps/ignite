<?php
// Support custom "anchor" values.
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
	$anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_names = 'featured_sponsor';
if ( ! empty( $block['className'] ) ) {
	$class_names .= ' ' . $block['className'];
}

$featured = ITS_get_featured_sponsors_today();
if ($featured) :
	foreach ($featured as $sponsor) :
		$sponsor_id = $sponsor->ID;
		$before_sponsor = '';
		$after_sponsor = '';
		$sponsor_content = $sponsor->post_content;
		if( get_field( 'link', $sponsor_id ) ) {
			$before_sponsor = '<a href="' . esc_url( get_field( 'link', $sponsor_id ) ) . '">';
			$after_sponsor = '</a>';
		}
		echo apply_filters( 'the_content', wp_kses_post( $sponsor_content ) );
	endforeach;
endif;