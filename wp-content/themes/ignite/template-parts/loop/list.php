<?php
/**
 * loop, list view
 *
 */

  $post_type = get_post_type( get_the_ID() );


?>

<article id="content_ID-<?php echo esc_attr( get_the_ID() );?>" <?php post_class( 'single_content_post list_item' ); ?>>
	<div class="flex-row">
		<aside class="thumbnail_image">
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large' ); ?></a>

		</aside>
		<div class="post_details">
			<a href="<?php the_permalink(); ?>"><h3 class="content_title"><?php the_title(); ?></h3></a>
			<div class="post_date"><?php the_date(); ?></div>
			<p><?php the_excerpt(); ?></p>
		</div>
	</div>
</article>
