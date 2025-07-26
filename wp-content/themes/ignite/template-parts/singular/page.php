<?php
/**
 * 	Template part for singular page display
 *  DEFAULT view
 *
 *
 */

$override_header = false;

if( get_field( 'override_default_header', get_the_ID() ) ) {
	$override_header = true;
}

if( false === $override_header ) {
	if( has_post_thumbnail() ) :
		$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
		echo '<div class="featured_image">';
		echo '<img class="fade-bottom" src="' . esc_url( $featured_img_url ) . '" />';
		echo '<h1 class="page_title">' . wp_kses_post( get_the_title() ) . '</h1>';
		echo '</div>';

	else :
		echo '<div class="no_featured_image">';
		echo '<h1 class="page_title">' . wp_kses_post( get_the_title() ) . '</h1>';
		echo '</div>';
	endif;
} else {
	echo '<div class="no-header"></div>';
}

?>


<main id="post-<?php the_ID(); ?>" <?php post_class( 'singular_page' ); ?>>
	<?php
		the_content();
		wp_link_pages(
			array('before' => '<div>' . __( 'Pages:', 'ITS' ),	'after'  => '</div>')
		);
	?>
</main>