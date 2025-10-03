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
		<h1 class="hom_name">
			<span class="subhead"><?php echo $subhead_text; ?></span>
			<?php the_title(); ?>
		</h1>
		<span class="hom_title"><?php the_field('title') ?></span>
	</header>

	<main class="post_main">
		<?php the_content(); ?>
	</main>

		<?php
		$photo_gallery = get_field( 'photos' );

		if( $photo_gallery ) {
			echo '<footer class="hom_photo_gallery"><ul class="flex-row js-gallery" role="list">';

			foreach( $photo_gallery as $photo ) {
				echo '<li class="gallery_image"><button aria-expanded="false"><img src="' . esc_url( $photo ) . '"  /></button></li>';
			}

			echo '</div></footer>';
		}
		?>

	<?php echo ITS_display_featured_sponsors_today(); ?>

	<?php ITS_display_heroes_by_calendar_year(); ?>

</article>

