<?php
/**
 * 	Template part for singular memorial display
 *  DEFAULT view
 *
 *
 */

?>
<div class="no_featured_image memorial_page">
	<h1 class="page_title"><?php the_title(); ?></h1>
</div>


<main id="post-<?php the_ID(); ?>" <?php post_class( 'singular_memorial wide_width' ); ?>>
	<?php
	if( has_post_thumbnail() ) :
		$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
		echo '<img class="memorial_headshot alignright framed" src="' . esc_url( $featured_img_url ) . '" />';
	endif;

		the_content();
		wp_link_pages(
			array('before' => '<div>' . __( 'Pages:', 'ITS' ),	'after'  => '</div>')
		);
	?>
</main>