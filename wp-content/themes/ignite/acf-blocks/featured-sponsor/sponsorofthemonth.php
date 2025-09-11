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
if ( ! empty( $block['align'] ) ) {
	$class_names .= ' align' . $block['align'];
}

$featured = ITS_get_featured_sponsors_today();
if ($featured) :
	foreach ($featured as $sponsor) :
		echo '<object>' . wp_kses_post( $sponsor->post_content) . '</object>';
	endforeach;
else :
	// nothing to see here
endif;