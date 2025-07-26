<?php
/**
 * Helper Functions which enhance the theme by hooking into WordPress
 *
 * @package ITS
 */

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function ITS_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'ITS_pingback_header' );


/**
 * add page slug to body class names
 */

function ITS_add_page_slug_to_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}
add_filter( 'body_class', 'ITS_add_page_slug_to_body_class' );

/**
 * Changes comment form default fields.
 *
 * @param array $defaults The default comment form arguments.
 *
 * @return array Returns the modified fields.
 */
function ITS_comment_form_defaults( $defaults ) {
	$comment_field = $defaults['comment_field'];

	// Adjust height of comment form.
	$defaults['comment_field'] = preg_replace( '/rows="\d+"/', 'rows="5"', $comment_field );

	return $defaults;
}
add_filter( 'comment_form_defaults', 'ITS_comment_form_defaults' );

/**
 * Filters the default archive titles.
 */
function ITS_get_the_archive_title() {
	if ( is_category() ) {
		$title = __( 'Category Archives: ', 'ITS' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_tag() ) {
		$title = __( 'Tag Archives: ', 'ITS' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_author() ) {
		$title = __( 'Author Archives: ', 'ITS' ) . '<span>' . get_the_author_meta( 'display_name' ) . '</span>';
	} elseif ( is_year() ) {
		$title = __( 'Yearly Archives: ', 'ITS' ) . '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'ITS' ) ) . '</span>';
	} elseif ( is_month() ) {
		$title = __( 'Monthly Archives: ', 'ITS' ) . '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'ITS' ) ) . '</span>';
	} elseif ( is_day() ) {
		$title = __( 'Daily Archives: ', 'ITS' ) . '<span>' . get_the_date() . '</span>';
	} elseif ( is_post_type_archive() ) {
		$cpt   = get_post_type_object( get_queried_object()->name );
		$title = sprintf(
			/* translators: %s: Post type singular name */
			esc_html__( '%s Archives', 'ITS' ),
			$cpt->labels->singular_name
		);
	} elseif ( is_tax() ) {
		$tax   = get_taxonomy( get_queried_object()->taxonomy );
		$title = sprintf(
			/* translators: %s: Taxonomy singular name */
			esc_html__( '%s Archives', 'ITS' ),
			$tax->labels->singular_name
		);
	} else {
		$title = __( 'Archives:', 'ITS' );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'ITS_get_the_archive_title' );

/**
 * Determines if post thumbnail can be displayed.
 */
function ITS_can_show_post_thumbnail() {
	return apply_filters( 'ITS_can_show_post_thumbnail', ! post_password_required() && ! is_attachment() && has_post_thumbnail() );
}

/**
 * Returns the size for avatars used in the theme.
 */
function ITS_get_avatar_size() {
	return 60;
}

/**
 * Create the continue reading link
 */
function ITS_continue_reading_link() {

	if ( ! is_admin() ) {
		$continue_reading = sprintf(
			/* translators: %s: Name of current post. */
			wp_kses( __( 'Continue reading %s', 'ITS' ), array( 'span' => array( 'class' => array() ) ) ),
			the_title( '<span class="sr-only">"', '"</span>', false )
		);

		return '<a href="' . esc_url( get_permalink() ) . '">' . $continue_reading . '</a>';
	}
}

// Filter the excerpt more link.
add_filter( 'excerpt_more', 'ITS_continue_reading_link' );

// Filter the content more link.
add_filter( 'the_content_more_link', 'ITS_continue_reading_link' );

/**
 * Outputs a comment in the HTML5 format.
 *
 * This function overrides the default WordPress comment output in HTML5 format,
 * adding the required class for Tailwind Typography. Based on the
 * `html5_comment()` function from WordPress core.
 *
 * @param WP_Comment $comment Comment to display.
 * @param array      $args    An array of arguments.
 * @param int        $depth   Depth of the current comment.
 */
function ITS_html5_comment( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';

	$commenter          = wp_get_current_commenter();
	$show_pending_links = ! empty( $commenter['comment_author'] );

	if ( $commenter['comment_author_email'] ) {
		$moderation_note = __( 'Your comment is awaiting moderation.', 'ITS' );
	} else {
		$moderation_note = __( 'Your comment is awaiting moderation. This is a preview; your comment will be visible after it has been approved.', 'ITS' );
	}
	?>
	<<?php echo esc_attr( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $comment->has_children ? 'parent' : '', $comment ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
					if ( 0 !== $args['avatar_size'] ) {
						echo get_avatar( $comment, $args['avatar_size'] );
					}
					?>
					<?php
					$comment_author = get_comment_author_link( $comment );

					if ( '0' === $comment->comment_approved && ! $show_pending_links ) {
						$comment_author = get_comment_author( $comment );
					}

					printf(
						/* translators: %s: Comment author link. */
						wp_kses_post( __( '%s <span class="says">says:</span>', 'ITS' ) ),
						sprintf( '<b class="fn">%s</b>', wp_kses_post( $comment_author ) )
					);
					?>
				</div><!-- .comment-author -->

				<div class="comment-metadata">
					<?php
					printf(
						'<a href="%s"><time datetime="%s">%s</time></a>',
						esc_url( get_comment_link( $comment, $args ) ),
						esc_attr( get_comment_time( 'c' ) ),
						esc_html(
							sprintf(
							/* translators: 1: Comment date, 2: Comment time. */
								__( '%1$s at %2$s', 'ITS' ),
								get_comment_date( '', $comment ),
								get_comment_time()
							)
						)
					);

					edit_comment_link( __( 'Edit', 'ITS' ), ' <span class="edit-link">', '</span>' );
					?>
				</div><!-- .comment-metadata -->

				<?php if ( '0' === $comment->comment_approved ) : ?>
				<em class="comment-awaiting-moderation"><?php echo esc_html( $moderation_note ); ?></em>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div class="comment-content prose">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<?php
			if ( '1' === $comment->comment_approved || $show_pending_links ) {
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<div class="reply">',
							'after'     => '</div>',
						)
					)
				);
			}
			?>
		</article><!-- .comment-body -->
	<?php
}




// ---------------------------------site options ---------

/**
 * Set the post excerpt length to the number of words set on the Site Options
 * page (if any).
 */
function ITS_options_excerpt_length( $length ) {
	if ( 'words' === get_field( 'excerpt_length_unit', 'option' ) ) {
		if ( $custom_length = get_field( 'excerpt_length', 'option' ) ) {
			$length = (int) $custom_length;
		}
	}
	return $length;
}
add_filter( 'excerpt_length', 'ITS_options_excerpt_length' );


/**
 * Re-trim post excerpts to a specific number of *characters*, instead of
 * words, if we've been asked to do so.
 */
function ITS_options_trim_excerpt_characters( $trimmed, $raw_excerpt ) {

	// Per wp_trim_excerpt() behavior, if there *is* a raw excerpt, return it.
	if ( '' !== $raw_excerpt ) {
		return $raw_excerpt;
	}

	// If we're supposed to be trimming by words and not characters, bail.
	if ( 'chars' !== get_field( 'excerpt_length_unit', 'option' ) ) {
		return $trimmed;
	}

	// Get the number of characters to trim to.
	$excerpt_length = (int) get_field( 'excerpt_length', 'option' );

	// If we haven't been told how many characters to trim to, bail.
	if ( ! $excerpt_length ) {
		return $trimmed;
	}

	// Apply filters/etc from wp_trim_excerpt().
	$text = get_the_content( '' );
	$text = strip_shortcodes( $text );
	$text = apply_filters( 'the_content', $text );
	$text = str_replace( ']]>', ']]&gt;', $text );

	// Strip tags and condense whitespace like wp_trim_words().
	$text = wp_strip_all_tags( $text );
	$text = preg_replace( '/[\n\r\t ]+/', ' ', $text );

	// Trim the excerpt to the number of characters given.
	$trimmed_hard = substr( $text, 0, $excerpt_length );

	// Get the "more" text/link to append to the excerpt.
	$excerpt_more = apply_filters( 'excerpt_more', ' [&hellip;]', 'start' );

	// NOTE: We're trimming by words in an effort to keep all words whole. If you
	// don't care about your excerpts looking "like thi", you can skip the below
	// and just `return $trimmed_hard . $excerpt_more;`.
	// Get the number of words this corresponds to.
	$word_count = str_word_count( $trimmed_hard );
	// Decrement by one, since the chances are very good that $trimmed_hard ends
	// in the middle of a word.
	$word_count -= 1;
	// TrimSpa, baby!
	$trimmed_soft = wp_trim_words( $text, $word_count, $excerpt_more );

	return $trimmed_soft;
}
add_filter( 'wp_trim_excerpt', 'ITS_options_trim_excerpt_characters', 10, 2 );


//-----------------------------------------------------------------------------
// "Text" tab
//-----------------------------------------------------------------------------

/**
 * Return the footer text as specified on the Site Options page.
 */
function ITS_options_get_footer_text() {
	$footer_text = get_field( 'site_footer_text', 'option' );

	// Replace [year] with the year.
	$footer_text = str_replace( '[year]', date( 'Y' ), $footer_text );
	return $footer_text;
}

/**
 * Output the copyright text as specified on the Site Options page.
 */
function ITS_options_footer_text() {
	$footer_text = get_field( 'site_footer_text', 'option' );
	if ( $footer_text = '' ) {
		return;
	}
	// Escape output (allowing basic markp) & prettify dashes, apostrophes, etc.
	echo wp_kses_post( wptexturize( ITS_options_get_footer_text() ) );
}

/**
 * Return the contact info text as specified on the Site Options page.
 */
function ITS_options_get_contact_info() {
	return get_field( 'contact_info', 'option' );
}

/**
 * Output the contact info as specified on the Site Options page.
 */
function ITS_options_contact_info() {
	// Escape output (ACF takes care of wptexturize-ation, shortcodes, etc).
	echo wp_kses_post( ITS_options_get_contact_info() );
}


//-----------------------------------------------------------------------------
// "URLs" tab
//-----------------------------------------------------------------------------

/**
 * Return an array of social links.
 *
 * @param array $services An array of service names ('facebook', 'twitter', etc.)
 *                      to return links for, if links have been set in the Site
 *                      Options page. Omit or leave empty to return all links
 *                      provided.
 * @return array An array of arrays, each second-dimension array containing the
 *               keys 'service', 'handle', and 'url'. Note that 'service' and
 *               'handle' may be empty.
 */
function ITS_options_get_social_links( $services = false ) {


	// We'll collect the links in this variable.
	$links = array();

	// Iterate over 'social_media_urls' rows...
	while ( have_rows( 'ITS_social_media_links', 'option' ) ) {
		the_row();

		// Get all sub-fields.
		$link = array(
			'url'   => get_sub_field( 'link' ),
			'icon' => get_sub_field( 'icon' ),
		);


		$links[] = $link;
	}

	return $links;
}

/**
 * Output the social links as a nice UL with some helpful classing.
 *
 * @param string $class_prefix (default: 'icon-') prefix for html class generated by service name and applied to li elements
 * @param string $link_target (default: '_blank') html target attribute value for anchors on social links
 * @param string $ul_class (default: 'menu menu-social-links') css classes applied to enclosing ul
 * @param string $ul_id (default: '') html id attribute applied to encolding ul
 * @return void
 */
function ITS_options_social_links( $class_prefix = 'icon-', $link_target = '_blank', $ul_class = 'menu menu-social-links', $ul_id = '', $svg_icons = false ) {

	$social_links = ITS_options_get_social_links();
	$ul_id_html = '';

	if ( count( $social_links ) ) {

		// Give the UL an ID if specified
		if ( ! empty( $ul_id ) ) {
			$ul_id_html = 'id="' . sanitize_html_class( $ul_id ) . '"';
		}

		// Variable $classes can be a string or an array. Make it an array and sanitize it.
		$classes = ( ! is_array( $ul_class ) ) ? explode( ' ', $ul_class ) : $ul_class ;
		$classes = array_map( 'sanitize_html_class', $classes );

		// Output the UL element
		echo '<ul class="' . implode( ' ', apply_filters( 'ITS_options_social_links_ul_classes', $classes ) ) . '" ' . $ul_id_html . ">";

		// Loop through social links and output them
		foreach ( $social_links as $link ) {
			$li_class = sanitize_html_class( $class_prefix . ( ! empty( $link['icon'] ) ? $link['icon'] : 'unknown' ) );
			$li_class = apply_filters( 'ITS_options_social_links_li_class', $li_class );
			echo "<li class='$li_class'><a href='" . esc_url( $link['url'] ) . "' target='" . esc_attr( $link_target ) . "'>";

			if( $svg_icons == true ) :
				echo "<svg class='icon-" . esc_html( $link['icon'] ) . "-dims icon'><use xmlns:xlink='http://www.w3.org/1999/xlink' xlink:href='#" . esc_html( $link['icon'] ) . "'></use><text class='sr-only'>" . $link['icon'] . "</text></svg>";
			endif;
			echo "</a></li>";
		}

		// We're done, close the UL
		echo "</ul>";

	}
}

/**
 *
 * helpful little video embed code that checks to see if the video is uploaded to the site,
 * or if it is a youtube link, then displays it in the video template.
 *
 * @param int $video_ID -- the ID of the video post
 * @param string $file_upload_key -- the key used in ACF for an uploaded file. default: `upload_file`
 * @param string $youtube_link_key -- the key used in ACF for the youtube link option. default: `youtube_link`
 * @param bool $is_video_hosted_here -- ACF key for the true/false video location field
 *
 */
function ITS_display_video_embed( $video_ID = NULL, $file_upload_key = 'upload_file', $youtube_link_key = 'youtube_link' ) {
	$is_video_hosted_here = get_field( 'video_file_location', $video_ID, false );
	if( ( $is_video_hosted_here === '1' ) || $is_video_hosted_here === 1 ) :
		// self hosted video
		$video_embed = get_field( $file_upload_key, $video_ID );

		$output = sprintf(
			'<div class="video_container">
			<video poster="%s" controls>
				<source src="%s" type="%s"/>
			</video>',
			esc_url( get_the_post_thumbnail_url( $video_ID,'full') ),
			esc_url( $video_embed['url'] ),
			esc_attr( $video_embed['mime_type'])
		);
	else :
		// youtube video
		$video_url = get_field( $youtube_link_key, $video_ID, false);
		$videoargs = array(
				'width'		=> '700',
			);
			$youtube_embed = wp_oembed_get( esc_url( $video_url ), $videoargs );
			$output = '<div class="video_container">' . $youtube_embed . '';

	endif;
	// now add any glossary terms, if there are any.
	$output .= ITS_display_related_glossary_term_tags( $video_ID, 'video' );

	$output .= '</div>'; // end the video_container div

	return $output;
}

/**
 *
 * display the latest blog posts,
 *
 * @param int $max_to_display -- how many to display
 * @param string $section_title -- what the title should say
 *
 */
 function ITS_display_latest_blog_posts( $max_to_display = 8, $section_title = 'Latest Blog Posts' ) {
	$latest_blog_posts_args = array(
		'post_type' 		=> 'post',
		'posts_per_page'	=> $max_to_display,
		'orderby'			=> 'date',
		'order'				=> 'DESC',
	);
	$latest_blogs_query = new WP_Query( $latest_blog_posts_args );

	if( $latest_blogs_query->have_posts() ) : ?>
	<section class="related_content_container glossary_terms related">
			<header class="section_header">
				<h6><?php echo esc_html( $section_title ) ?></h6>
			</header>
		</section>
		<div class="content_grid_container blog_posts">
		<?php while( $latest_blogs_query->have_posts() ) :
			$latest_blogs_query->the_post();
			get_template_part( 'template-parts/loop/grid' );
		endwhile; ?>
		</div>
	<?php endif;

	wp_reset_postdata();
}
