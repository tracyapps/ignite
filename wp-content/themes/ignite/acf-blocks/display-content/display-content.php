<?php
/**
 * Display content Block
 */

// Support custom "anchor" values.
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
	$anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_names = 'content_block';
if ( ! empty( $block['className'] ) ) {
	$class_names .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$class_names .= ' align' . $block['align'];
}

// first, start with the design portion (same as section block)

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


// start content settings

$section_title = get_field( 'heading_text' );
$section_layout = get_field( 'layout' );
$content_type = get_field( 'content_type' );
$display_overrides = get_field( 'display_options' );


// template start
?>
<section <?php echo esc_attr( $anchor ); ?> class="<?php echo esc_attr( $class_names ); ?>" <?php echo  $inline_styles; ?>>
	<?php echo $section_background_image; ?>
	<heading class="section_heading"><h2><?php esc_html_e( $section_title ); ?></h2></heading>
	<div class="
		section_inner_content
		content_<?php esc_html_e( $section_layout ); ?>_container
		content_type_<?php esc_html_e( $content_type ); ?>
	">
		<?php
		// --------- START content loop: posts
		if( 'post' === $content_type ) {
			$posts_array = get_field( 'items_posts' );



		// --------- END content loop: posts
		}
		// -------- START content loop: board members
		elseif( 'board' === $content_type ) {
			$board_members_array = get_field( 'items_board' );
			$display_featured_image = $display_overrides['featured_image'];
			$display_bio = $display_overrides['excerpt'];
			$display_email_address = $display_overrides['email_address'];
			$display_page_link = $display_overrides['page_link'];


			foreach( $board_members_array as $member ) {

				echo '<div class="item board_member card">';

				$content_overrides = $member['overrides'];
				$post_object = $member['post_object'];

				$page_link_start = '';
				$page_link_end = '';
				if( $display_page_link ) {
					$page_link_start = '<a href="' . esc_url( get_the_permalink( $post_object->ID ) ) . '">';
					$page_link_end = '</a>';
				}

				if( $display_featured_image ) {
					$featured_img_url = get_the_post_thumbnail_url( $post_object->ID, 'full');
					$headshot_url = in_array( 'override_image', $content_overrides ) ? esc_url( $member['new_image'] ) : esc_url( $featured_img_url ) ;
					echo '<div class="headshot">';
					echo $page_link_start . '<img src="' . $headshot_url . '" class="board_member_headshot>' . $page_link_end;
					echo '</div>';
				}
				echo '<div class="board_member_details">';
					echo $page_link_start . '<h4 class="board_member_name">' . esc_html( $post_object->post_title ) . '</h4>' . $page_link_end;
					$member_position = get_field( 'title', $post_object->ID );
					if( $member_position ) {
						echo '<h6 class="position">' . esc_html( $member_position ) . '</h6>';
					}
					if( $display_email_address ) {
						$member_email = get_field( 'email', $post_object->ID );
						if( $member_email ) {
							echo '<a href="mailto:' . esc_html( $member_email ) . '">' . esc_html( $member_email ) . '</a>';
						}

					}
					if( $display_bio ) {

						$bio = in_array( 'override_excerpt', $content_overrides ) ? wp_kses_post( $member['new_excerpt'] ) : wp_kses_post( $post_object->post_content );
						echo '<div class="bio">' . $bio . '</div>';
					}



					echo '</div><!--/board_member_details-->';
				echo '</div><!--/card-->';
			}


		// -------- END content loop: board members
		}
		// -------- START content loop: memorial
		elseif( 'memorial' === $content_type ) {
			$memorials_array = get_field( 'items_memorial' );



		// -------- END content loop: memorial
		}
		// -------- START content loop: page(s)
		elseif( 'page' === $content_type ) {
			$pages_array = get_field( 'items_page' );



		// -------- END content loop: page(s)
		}

		?>

	</div>
</section>
