<?php
/**
 * The template for displaying single hero of the month pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package ITS
 */

get_header();


?>

	<section id="primary">
		<main id="main">

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/singular/hero' );


				// End the loop.
			endwhile;

			?>



		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
