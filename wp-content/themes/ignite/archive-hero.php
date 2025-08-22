<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ITS
 */


get_header();

add_action('pre_get_posts', function($query) {
	if (!is_admin() && $query->is_main_query() && is_post_type_archive('hero')) {
		$query->set('posts_per_page', 300);
	}
});

?>

	<section id="primary">
		<main id="main" class="hom_archive">
			<header class="hom_header post_header">
				<h1 class="page_title">Heroes of the Month</h1>
			</header>

			<?php

			// Get calendar-year terms in DESC order
			$years = get_terms( [
				'taxonomy'   => 'calendar-year',
				'orderby'    => 'name',
				'order'      => 'DESC',
				'hide_empty' => false,
			] );

			foreach ($years as $year) :
				echo '<h2 class="year-heading">' . esc_html($year->name) . '</h2>';
				echo '<section class="hom_grid">';

				// Get calendar-month terms ordered by ACF "order" field
				$months = get_terms([
					'taxonomy' => 'calendar-month',
					'hide_empty' => false,
					'meta_key' => 'order',
					'orderby' => 'meta_value_num',
					'order' => 'ASC',
				]);

				foreach ($months as $month) :
					// Get hero posts for this month + year
					$heroes = new WP_Query([
						'post_type' => 'hero',
						'posts_per_page' => -1,
						'tax_query' => [
							'relation' => 'AND',
							[
								'taxonomy' => 'calendar-year',
								'field' => 'term_id',
								'terms' => $year->term_id,
							],
							[
								'taxonomy' => 'calendar-month',
								'field' => 'term_id',
								'terms' => $month->term_id,
							],
						],
					]);

					if ($heroes->have_posts()) :

						while ($heroes->have_posts()) : $heroes->the_post();

						get_template_part( 'template-parts/loop/hom' );
						endwhile;


					endif;

					wp_reset_postdata();
				endforeach;
				echo '</section>';
			endforeach;
			?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
