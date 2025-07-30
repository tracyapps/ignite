<?php
/**
 * 	Template part for singular hero of the month
 *
 */

$subhead_text = ! empty( get_field( 'top_banner_text', get_the_ID() ) ) ? esc_html( get_field( 'top_banner_text' ) ) : 'Hero Of The Month';

?>

<article id="post-<?php the_ID(); ?>" class="hom_single">

	<?php if( has_post_thumbnail() ) :
	$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
	echo '<img class="hom_featured_image" src="' . esc_url( $featured_img_url ) . '" />';
	endif;
	?>

	<header class="hom_header">
		<h1 class="post_title">
			<span class="subhead"><?php echo $subhead_text; ?></span>
			<?php the_title(); ?>
		</h1>
	</header>

	<main class="post_main">
		<?php the_content(); ?>
	</main>
	<footer class="post_footer">
		<div class="list_of_all_tags">
			<?php the_tags( '', ' | ', '' ); ?>
		</div>

	</footer>


	<?php ITS_display_heroes_by_calendar_year(); ?>

</article>

