<?php
/**
 * The template for displaying heros of the month, by year
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package ITS
 */

get_header();
$taxonomy = get_queried_object();

?>
	<section id="primary">
		<main id="main">
			<header class="hom_archive no_featured_image">
				<h1 class="page_title"><span class="subhead">Heroes of the Month:</span> <?php echo esc_html( $taxonomy->name ); ?></h1>
			</header>
			<article id="tag-<?php the_ID(); ?>" <?php post_class(); ?>>

            <section class="hom_grid">
				<?php
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
						'posts_per_page' => 25,
						'tax_query' => [
							'relation' => 'AND',
							[
								'taxonomy' => 'calendar-year',
								'field' => 'term_id',
								'terms' => $taxonomy->term_id,
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
						get_template_part('template-parts/loop/hom');
					endwhile;
				endif;
				wp_reset_postdata();
				endforeach; ?>
			</section>
			</article>
		</main>
	</section>

<?php
get_footer();
