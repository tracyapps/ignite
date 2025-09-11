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