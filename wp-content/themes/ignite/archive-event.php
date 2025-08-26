<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ITS
 */


get_header();


?>


	<section id="primary">
		<main id="main">
			<div class="no_featured_image">
				<h1 class="page_title">Events</h1>
			</div>

				<?php


				$upcoming = ITS_get_events([
					'posts_per_page' => 99,
					'show_past'      => false,
					'order'          => 'DESC',
				]);
				if ( $upcoming->have_posts() ) {
					echo ' <h2 class="section_title">Upcoming Events:</h2><div class="list_container upcoming-events--list">';
					while ( $upcoming->have_posts() ) {
						$upcoming->the_post();
						ITS_render_event_item( get_the_ID(), [
							'show_excerpt' => true,
							'show_image'   => true,
						]);
					}
					echo '</div>';
					wp_reset_postdata();
				}


				$past = ITS_get_events([
					'posts_per_page' => 99,
					'show_past'      => true,
					'order'          => 'DESC',
				]);
				if ( $past->have_posts() ) {
					echo ' <h2 class="section_title">Past Events:</h2><div class="list_container past-events--list">';
					while ( $past->have_posts() ) {
						$past->the_post();
						ITS_render_event_item( get_the_ID(), [
							'show_excerpt' => false,
							'show_image'   => true,
						]);
					}
					echo '</div>';
					wp_reset_postdata();
				}


				?>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
