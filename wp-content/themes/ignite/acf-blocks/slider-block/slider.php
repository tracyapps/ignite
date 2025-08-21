<?php
/**
 * Slider Block template.
 *
 * @param array $block The block settings and attributes.
 */

// Support custom "anchor" values.
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
	$anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_names = '';
if ( ! empty( $block['className'] ) ) {
	$class_names .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$class_names .= ' align' . $block['align'];
}


// slides, repeater
$all_slides  = get_field( 'slides' );


if( $all_slides ) :
// template start

// grab settings
$splide_transition = '"type":"' . get_field( 'transition' ) . '"'; // string: slide, loop, fade
$splide_option_width = get_field( 'full_width_slides' ); // true/false for full width
$splide_option_height = '"height":"' . get_field( 'max_height' ) .'vh"'; // number value
// $splide_option_thumbnails = get_field( 'show_thumbnails' ); // true/false (not yet)


if( true ===$splide_option_width ) {
	$splide_width = '"fixedWidth":"98vw"';
} else {
	$splide_width = '"fixedWidth":"88vw","padding":{"left":"7vw"}';
}

$data_splide = esc_attr( $splide_transition ) . ',' . esc_attr( $splide_width ) . ',' . esc_attr( $splide_option_height );
	/**
	 * data-splide='{"type":"loop","perPage":3}'
	 * In this example, the value is enclosed by single quotes to use double quotes in JSON string,
	 * or you need to escape them with &quot;.
	 */


?>
<section <?php echo esc_attr( $anchor ); ?> class="splide slider_block"
											data-splide='{<?php echo $data_splide; ?>}'
>
	<div class="splide__track">
		<ul class="splide__list">
			<?php

				foreach( $all_slides as $slide ) :
				// start of slide loop and zero out vars
					$slide_title = $slide['slide_title'];
					$class_names = '';
					$inline_styles = '';
					$slide_overlay_image = '';
					$slide_background_image = '';
					$slide_background_video = '';


				// design stuff. similar to the section and content block, but within the slide repeater. huzzah!
				// acf values

				$background_design_type = $slide[ 'slide_background' ]; // color, gradient, pattern, animated, image, video
				$inline_styles = '';

				if( 'color' === $background_design_type ) {
					$background_color = $slide[ 'background_color' ]; // rgba (string)
					$inline_styles = ' style="background-color: ' . esc_attr( $background_color ) . '" ';

					$class_names = ' background_color ';
				}
				if( 'gradient' === $background_design_type ) {
					$background_gradient_array = $slide[ 'gradient' ]; // ARRAY of direction, and color stop (repeater)

					$gradient_direction = $background_gradient_array[ 'direction' ];
					$gradient_stops = $background_gradient_array[ 'color_stops' ];

					$gradient_stops_string = '';

					foreach( $gradient_stops as $stop ) {
						$gradient_stops_string .= ' , ' . esc_attr( $stop[ 'color' ] ) . ' ';
						$gradient_stops_string .=  ( '' != ( $stop[ 'position' ] )) ? esc_attr( $stop[ 'position' ] ) . '% ': '';
					}

					$inline_styles = ' style="background-image: linear-gradient(' . esc_attr( $gradient_direction ) . ' ' . $gradient_stops_string . ')"';
					$class_names = ' background_gradient ';
				}
				if( 'pattern' === $background_design_type ) {
					$background_pattern = $slide[ 'pattern' ]; // class name (string) for patterns [_patterns.scss]

					$class_names = ' pattern ' . esc_attr( $background_pattern );
				}
				if( 'animated' === $background_design_type ) {
					$animated_background = $slide[ 'animated_background' ]; // class name (string) for animated patterns [_patterns.scss]

					$class_names = ' animated ' . esc_attr( $animated_background );
				}
				if( 'image' === $background_design_type ) {
					$image_background = $slide[ 'background_image' ]; // returns string (url)

					$slide_background_image = '<img class="slide_background_image" src="' . esc_url( $image_background ) . '"  />';
				}
				if( 'video' === $background_design_type ) {
					$video_background = $slide[ 'video_details' ]; // video group
					$mimeType = exec('file --mime-type ' . escapeshellarg( $video_background[ 'video_file' ] ) );
					$mimeType = substr($mimeType, strpos($mimeType, ': ') + 2);

					$slide_background_video = sprintf(
							'<video poster="%s" muted loop %s> 
								<source src="%s" type="%s">
							</video>',
						esc_url( $video_background[ 'video_thumbnail' ] ),
						'autoplay',
						esc_url(  $video_background[ 'video_file' ] ),
						$mimeType
					);
				}

				// if has image
				if( get_field( 'add_an_image' ) ){
					$background_image_settings = $slide[ 'background_image_settings' ]; // ARRAY of image url and css properties

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

					$slide_overlay_image =  sprintf(
						'<img src="%s" class="section_background_image" style="%s %s %s %s" />',
						esc_url( $background_image_settings[ 'background_image' ] ),
						'repeat: ' . esc_html( $background_image_settings[ 'repeat' ] ) . '; ',
						esc_html( $background_image_settings[ 'position' ] ) . '; ',
						$filter_list,
						'mix-blend-mode: ' . esc_html( $background_image_settings[ 'blend_mode' ] ) . '; ',
					);
				}
				?>

			<li class="splide__slide">
				<div class="splide__slide__container <?php echo esc_attr( $class_names ); ?>" <?php echo  $inline_styles; ?>>
				<?php // these are blank unless any one is set for this slide above.
				echo $slide_background_image;
				echo $slide_overlay_image;
				echo $slide_background_video;

					$horizontal_position = $slide[ 'horizontal_position' ];
					$vertical_position = $slide[ 'vertical_position' ];
					$alignment_classes = '';
					$inline_padding_styles = '';


					$alignment_classes = esc_attr( $horizontal_position ) . ' ' . esc_attr( $vertical_position );

					if( $slide[ 'additional_padding' ] ) {
						$padding_top = ( $slide[ 'top_padding' ] ) ? 'padding-top: '. esc_attr(  $slide[ 'top_padding' ] ) . '%; ' : '';
						$padding_right = ( $slide[ 'right_padding' ] ) ? 'padding-right: '. esc_attr(  $slide[ 'right_padding' ] ) . '%; ' : '';
						$padding_bottom = ( $slide[ 'bottom_padding' ] ) ? 'padding-bottom: '. esc_attr(  $slide[ 'bottom_padding' ] ) . '%; ' : '';
						$padding_left = ( $slide[ 'left_padding' ] ) ? 'padding-left: '. esc_attr(  $slide[ 'left_padding' ] ) . '%; ' : '';

						$inline_padding_styles = 'style="' . $padding_top . $padding_right . $padding_bottom . $padding_left . '"';
					}
				?>
					<div class="slide_inner_content_container <?php echo $alignment_classes; ?>" <?php echo $inline_padding_styles; ?> >
						<div class="slide_inner_content">
							<?php
							echo ( true === $slide[ 'display_title' ] ) ? '<h2 class="slide_title">' . esc_html( $slide_title ) . '</h2>' : '';
							echo '<div class="slide_content">' . wp_kses_post( $slide[ 'content' ] ) . '</div>';
							?>
						</div><!-- /slide_inner_content , like, content for real for real -->
					</div><!--/slide_content_container (and positioning/padding settings) -->

				</div><!--/splide__slide__container-->
			</li><!--/splide__slide-->

			<?php
			endforeach; // end for each slide (loop)
			?>

		</ul><!--/splide__list-->
	</div><!--/splide__track-->
</section><!--/splide-->
<?php
endif; // end if slides
