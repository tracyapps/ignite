<?php
/**
 * Section Block template.
 *
 * @param array $block The block settings and attributes.
 */

// Support custom "anchor" values.
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
	$anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_names = 'section_block';
if ( ! empty( $block['className'] ) ) {
	$class_names .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$class_names .= ' align' . $block['align'];
}

// acf values
$background_design_type = get_field( 'background_design' ); // color, gradient, pattern, animated
$inline_styles = '';

if( 'color' === $background_design_type ) {
	$background_color = get_field( 'background_color' ); // rgba (string)
	$inline_styles = ' style="background-color: ' . esc_attr( $background_color ) . '" ';

	$class_names .= ' background_color ';
}
if( 'gradient' === $background_design_type ) {
	$background_gradient_array = get_field( 'gradient' ); // ARRAY of direction, and color stop (repeater)

	$gradient_direction = $background_gradient_array[ 'direction' ];
	$gradient_stops = $background_gradient_array[ 'color_stops' ];

	$gradient_stops_string = '';

	foreach( $gradient_stops as $stop ) {
		$gradient_stops_string .= ' , ' . esc_attr( $stop[ 'color' ] ) . ' ';
		$gradient_stops_string .=  ( '' != ( $stop[ 'position' ] )) ? esc_attr( $stop[ 'position' ] ) . '% ': '';
	}

	$inline_styles = ' style="background-image: linear-gradient(' . esc_attr( $gradient_direction ) . ' ' . $gradient_stops_string . ')"';
	$class_names .= ' background_gradient ';
}
if( 'pattern' === $background_design_type ) {
	$background_pattern = get_field( 'pattern' ); // class name (string) for patterns [_patterns.scss]

	$class_names .= ' pattern ' . esc_attr( $background_pattern );
}
if( 'animated' === $background_design_type ) {
	$animated_background = get_field( 'animated' ); // class name (string) for animated patterns [_patterns.scss]

	$class_names .= ' animated ' . esc_attr( $animated_background );
}

$section_background_image = '';
// if has image
if( get_field( 'add_an_image' ) ){
	$background_image_settings = get_field( 'background_image_settings' ); // ARRAY of image url and css properties

	$filter_list = '';
	if( ! empty( $background_image_settings[ 'filter' ] ) ) {
		$filter_array = $background_image_settings[ 'filter' ];
		$filter_list = 'filter: ';

		foreach( $filter_array as $filter ) {
			$unit = ( 'blur' === $filter[ 'effect' ] ) ? 'px' : '%';
			$filter_list .= esc_html( $filter[ 'effect' ] ) . '(' . esc_attr( $filter[ 'amount' ] )  . $unit . ') ';
		}

		$filter_list .= ';';
	}

	$section_background_image =  sprintf(
			'<img src="%s" class="section_background_image" style="%s %s %s %s" />',
		esc_url( $background_image_settings[ 'background_image' ] ),
		'repeat: ' . esc_html( $background_image_settings[ 'repeat' ] ) . '; ',
		esc_html( $background_image_settings[ 'position' ] ) . '; ',
		$filter_list,
		'mix-blend-mode: ' . esc_html( $background_image_settings[ 'blend_mode' ] ) . '; ',
	);

}

$section_content = get_field( 'section_content' ); // and the content WYSIWYG
$content_text_color = get_field( 'text_color' );
$content_inline_styles = 'color: ' . esc_attr( $content_text_color ) . '; ';
if( '' != get_field( 'content_background' ) ) {
	$content_inline_styles .= 'background-color: ' . esc_attr( get_field( 'content_background' ) ) . ';';
}

?>


<section <?php echo esc_attr( $anchor ); ?> class="<?php echo esc_attr( $class_names ); ?>" <?php echo  $inline_styles; ?>>
	<?php echo $section_background_image; ?>
	<div class="section_inner_content" style="<?php echo $content_inline_styles ?>">
		<?php echo wp_kses_post( $section_content ) ; ?>
	</div>
</section>
