<?php
/**
 * 	Template part for singular post display
 *  DEFAULT view
 *
 *
 */



if( has_post_thumbnail() ) :
		$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
		echo '<div class="featured_image">';
		echo '<img class="fade-bottom" src="' . esc_url( $featured_img_url ) . '" />';
		echo '<h1 class="post_title">' . wp_kses_post( get_the_title() ) . '</h1>';
		echo '</div>';

	else :
		echo '<div class="no_featured_image">';
		echo '<h1 class="post_title">' . wp_kses_post( get_the_title() ) . '</h1>';
		echo '</div>';
	endif;

?>

<article id="post-<?php the_ID(); ?>">
	<header class="post_header">
		<
		<?php the_title( '<h1 class="post_title">', '</h1>' ); ?>
		<div class="post_date">
			<?php echo get_the_date('M d, Y'); ?>
		</div>
		
	</header>
	<main class="post_main">
		<?php the_content(); ?>
	</main>
	<footer class="post_footer">
		<div class="list_of_all_tags">
			<?php the_tags( '', ' | ', '' ); ?>
		</div>

	</footer>

</article>

