<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
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

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

			// first get the regular page content, for intro text, and featured image stuff
				get_template_part( 'template-parts/singular/page' );

				// now the sponsors loop

				$levels = ITS_get_ordered_sponsor_levels();



				if ($levels) :
					foreach ($levels as $level) :

						$sponsors = ITS_get_sponsors_by_level($level->term_id);

						if (!$sponsors) continue;

						get_template_part('template-parts/loop/sponsor', null, [
							'level'    => $level,
							'sponsors' => $sponsors,
						]);
					endforeach;
				endif;


				endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
