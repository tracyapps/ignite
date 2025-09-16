<?php
/**
 *
 * looped memorials in a grid layout
 *
 */

 $post_type = get_post_type( get_the_ID() );



 ?>

 <article id="post-<?php the_ID(); ?>" <?php post_class( 'framed' ); ?>>
	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
		<object class="group_container">

			<?php the_post_thumbnail( 'medium' ) ?>

			<h3 class="item_title"><?php the_title(); ?></h3>
		</object>
	</a>
 </article>