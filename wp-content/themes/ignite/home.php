<?php
/**
 * The template for default post page (blog)
 *
 * also shows upcoming events in the secondary column.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ITS
 */

get_header();
?>
	<section id="primary">
		<main id="main">
			<?php

			// grab the featured image / header of the posts page
			$page_id_of_posts_page = get_option( 'page_for_posts' );
			$override_header = false;

			if( get_field( 'override_default_header', $page_id_of_posts_page ) ) {
				$override_header = true;
			}

			if( false == $override_header ) {
				if( has_post_thumbnail( $page_id_of_posts_page ) ) :
					$featured_img_url = get_the_post_thumbnail_url( $page_id_of_posts_page,'full');
					echo '<div class="page_featured_image">';
					echo '<img class="fade-bottom" src="' . esc_url( $featured_img_url ) . '" />';
					echo '<h1 class="page_title">' . wp_kses_post( get_the_title( $page_id_of_posts_page ) ) . '</h1>';
					echo '</div>';

				else :
					echo '<div class="no_featured_image">';
					echo '<h1 class="page_title">' . wp_kses_post( get_the_title( $page_id_of_posts_page ) ) . '</h1>';
					echo '</div>';
				endif;
			} else {
				echo '<div class="no_header"></div>';
			}

			wp_reset_postdata();

			ITS_render_news_and_events(); ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
