<?php
/**
 * The template for displaying all single memorial pages (in memoriam)
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
				get_template_part( 'template-parts/singular/memorial' );


				// End the loop.
			endwhile;
			?>

			<div class="back_link wide_width">
				<a href="/memorial/">&laquo; Back to Memorial Wall</a>
			</div>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
