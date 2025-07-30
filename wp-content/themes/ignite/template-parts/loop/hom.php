<?php
/**
 * loop, heros of the month
 *
 */


?>

<article id="content_ID-<?php echo esc_attr( get_the_ID() );?>" <?php post_class( 'hom_list' ); ?>>
	<?php $month = get_the_terms( $post, 'calendar-month');
	$month_id = $month[0]->term_id ;
	$abbr = get_term_meta( $month_id, 'abbreviation' );
	$abbr = $abbr[0];

	?>

	<div class="hom_thumbnail_image">
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'full' ); ?></a>
		<div class="month_abbr"><span><?php esc_html_e( $abbr ); ?></span></div>
	</div>
</article>
