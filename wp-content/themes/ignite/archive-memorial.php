<?php
/**
 * The template for displaying "memorial wall" archive (all in memoriam posts automatcially display)
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ITS
 */


get_header();

add_action('pre_get_posts', function($query) {
	if (!is_admin() && $query->is_main_query() && is_post_type_archive('memorial')) {
		$query->set('posts_per_page', 300);
	}
});

?>


	<section id="primary">
		<main id="main">
			<div class="no_featured_image">
				<h1 class="page_title">Memorial Wall</h1>
			</div>

			<section class="memorial_archive memorial_wall grid_container">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/loop/memorial' );
				endwhile;
				?>
			</section>
		</main><!-- #main -->
	</section><!-- #primary -->


	<svg width="300px" height="300px" viewBox="0 0 300 300" style="display: none;">
		<defs>
			<filter id="blur-v">
				<feGaussianBlur in="SourceGraphic" stdDeviation="3"></feGaussianBlur>
			</filter>
			<filter id="blur-h">
				<feGaussianBlur in="SourceGraphic" stdDeviation="3"></feGaussianBlur>
			</filter>
		</defs>
	</svg>
<?php
get_footer();
