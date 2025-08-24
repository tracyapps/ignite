<?php
/**
 * Adding useful site options
 */

/**
 * functions that are useful for displaying all these:
 *
 * contact info:		ITS_contact_info();
 * copyright text:		ITS_copyright_text();
 * social link list:	ITS_social_links( [li-class-prefix], [link-target], [ul-classes], [ul-id] );
 *
 * or social link custom template:
 *
 * foreach ( ITS_get_social_links() as $link ) :
 *  // Do something with $link
 * endforeach;
 *
 * google maps API key:	ITS_google_maps_api_key();  or  ITS_get_google_maps_api_key();
 * FB app ID:			ITS_fb_app_id();   or   ITS_get_fb_app_id();
 *
 */


/**
 * Add Site Options page (to be populated via ACF).
 */
function ITS_add_options_page() {
	acf_add_options_page( array(
		'page_title' => 'Site Options',
		'menu_slug'  => 'start-site-options',
	) );
}
add_action( 'after_setup_theme', 'ITS_add_options_page' );

/**
 * Return the URL for the Site Options page.
 */
function ITS_get_options_page_url() {
	return get_admin_url( 'admin.php?page=start-site-options' );
}


/**
 * If a default post thumbnail has been specified, use it when there's no post
 * thumbnail available for a given post.
 */
function ITS_default_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

	// If this post has a thumbnail, just return the real thumbnail HTML.
	if ( $post_thumbnail_id && ! empty( $html ) ) {
		return $html;
	}

	// If a default post thumbnail has been set for this post/post type, use it.
	if ( $default_thumbnail_id = ITS_get_post_thumbnail_id( $post_id ) ) {
		$html = wp_get_attachment_image( $default_thumbnail_id, $size, false, $attr );
	}

	return $html;
}
add_filter( 'post_thumbnail_html', 'ITS_default_post_thumbnail_html', 10, 5 );

/**
 * Short-circuited version of has_post_thumbnail().
 *
 * Always returns true for post types for which a default thumbnail has been set.
 */
function ITS_has_post_thumbnail( $post = null ) {
	return (bool) ITS_get_post_thumbnail_id( $post );
}

/**
 * Short-circuited version of get_post_thumbnail_id().
 *
 * Always returns the default thumbnail ID for post types for which a default
 * thumbnail has been set.
 */
function ITS_get_post_thumbnail_id( $post = null ) {

	// First, check for a real thumbnail, just in case.
	if ( $real_thumbnail_id = get_post_thumbnail_id( $post ) ) {
		return $real_thumbnail_id;
	}

	// Get the post, or bail if we can't.
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}

	// First, check for a post-type-specific default thumbnail.
	$default_thumbnail_id = get_field( "default_{$post->post_type}_thumbnail_id", 'option' );
	// If no post-type-specific thumbnail option was found or set, then fall back
	// on the default thumbnail for Posts.
	if ( ! $default_thumbnail_id ) {
		$default_thumbnail_id = get_field( 'default_post_thumbnail_id', 'option' );
	}

	return $default_thumbnail_id;
}

/**
 * Set the post excerpt length to the number of words set on the Site Options
 * page (if any).
 */
function ITS_excerpt_length( $length ) {
	if ( 'words' === get_field( 'excerpt_length_unit', 'option' ) ) {
		if ( $custom_length = get_field( 'excerpt_length', 'option' ) ) {
			$length = (int) $custom_length;
		}
	}
	return $length;
}
add_filter( 'excerpt_length', 'ITS_excerpt_length' );

/**
 * Add the custom Read More link text (if any) to auto-generated excerpts.
 */
function ITS_excerpt_more( $more_text ) {
	$more_text = __( ' &hellip; ', 'start' );
	if ( $more_link_text = get_field( 'excerpt_link_text', 'option' ) ) {
		$more_text .= '<a href="' . esc_attr( get_permalink() ) . '">' . esc_html( $more_link_text ) . ' &raquo;</a>';
	}
	return $more_text;
}
add_filter( 'excerpt_more', 'ITS_excerpt_more' );

/**
 * Re-trim post excerpts to a specific number of *characters*, instead of
 * words, if we've been asked to do so.
 */
function ITS_trim_excerpt_characters( $trimmed, $raw_excerpt ) {

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
add_filter( 'wp_trim_excerpt', 'ITS_trim_excerpt_characters', 10, 2 );


//-----------------------------------------------------------------------------
// "Text" tab
//-----------------------------------------------------------------------------

/**
 * Return the copyright text as specified on the Site Options page.
 */
function ITS_get_copyright_text() {
	$copyright_text = get_field( 'copyright_text', 'option' );
	// Replace [year] with the year.
	$copyright_text = str_replace( '[year]', date( 'Y' ), $copyright_text );
	return $copyright_text;
}

/**
 * Output the copyright text as specified on the Site Options page.
 */
function ITS_copyright_text() {
	// Escape output (allowing basic markp) & prettify dashes, apostrophes, etc.
	echo wp_kses_post( wptexturize( ITS_get_copyright_text() ) );
}

/**
 * Return the contact info text as specified on the Site Options page.
 */
function ITS_options_get_footer_text() {
	$footer_text = get_field( 'contact_info', 'option' );

	// Replace [year] with the year.
	$footer_text = str_replace( '[year]', date( 'Y' ), $footer_text );
	return $footer_text;
}

/**
 * Output the copyright text as specified on the Site Options page.
 */
function ITS_options_footer_text() {
	$footer_text = get_field( 'contact_info', 'option' );
	if ( $footer_text = '' ) {
		return;
	}
	// Escape output (allowing basic markp) & prettify dashes, apostrophes, etc.
	echo wp_kses_post( wptexturize( ITS_options_get_footer_text() ) );
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
function ITS_get_social_links( $services = false ) {

	// Make sure $services is an array.
	if ( $services ) {
		$services = (array) $services;
	} else {
		$services = array();
	}

	// We'll collect the links in this variable.
	$links = array();

	// Iterate over 'social_media_urls' rows...
	while ( have_rows( 'social_links', 'option' ) ) {
		the_row();

		// Get all sub-fields.
		$link = array(
			'service'   => get_sub_field( 'service' ),
			'link_text' => get_sub_field( 'link_text' ),
			'url'       => get_sub_field( 'url' ),
		);

		if ( $services && ! empty( $services ) ) {
			if ( ! in_array( $link['service'], $services, true ) ) {
				// If an array of services was passed as an argument and this link's
				// service wasn't one of them, skip it.
				continue;
			}
		}

		// If we got this far, add this link to the return array.
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
function ITS_social_links( $class_prefix = 'icon-', $link_target = '_blank', $ul_class = 'menu menu-social-links', $ul_id = '', $svg_icons = false ) {

	$social_links = ITS_get_social_links();
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
		echo '<ul class="' . implode( ' ', apply_filters( 'ITS_social_links_ul_classes', $classes ) ) . '" ' . $ul_id_html . ">";

		// Loop through social links and output them
		foreach ( $social_links as $link ) {
			$li_class = sanitize_html_class( $class_prefix . ( ! empty( $link['service'] ) ? $link['service'] : 'unknown' ) );
			$li_class = apply_filters( 'ITS_social_links_li_class', $li_class );
			echo "<li class='$li_class'><a href='" . esc_url( $link['url'] ) . "' target='" . esc_attr( $link_target ) . "'>";

			if( $svg_icons == true ) :
				echo "<span><svg class='icon-" . esc_html( $link['service'] ) . "-dims icon'><use xmlns:xlink='http://www.w3.org/1999/xlink' xlink:href='#" . esc_html( $link['service'] ) . "'></use><text>" . $link['link_text'] . "</text></svg></span>";
			else :
				echo "<span>" . $link['link_text'] . "</span>";
			endif;
			echo "</a></li>";
		}

		// We're done, close the UL
		echo "</ul>";

	}
}



//-----------------------------------------------------------------------------
// "Integrations" tab
//-----------------------------------------------------------------------------

/**
 * Return the Facebook App ID as specified on the Site Options page.
 */
function ITS_get_fb_app_id() {
	return get_field( 'facebook_app_id', 'option' );
}

/**
 * Output the Facebook App ID as specified on the Site Options page.
 */
function ITS_fb_app_id() {
	echo esc_attr( ITS_get_fb_app_id() );
}

/**
 * Return the Google Maps API key as specified on the Site Options page.
 */
function ITS_get_google_maps_api_key() {
	return get_field( 'google_maps_api_key', 'option' );
}

/**
 * Output the Google Maps API key as specified on the Site Options page.
 *
 * Output will be urlencode'd, since it looks like this usually gets passed in
 * via a GET param in a URL somewhere.
 */
function ITS_google_maps_api_key() {
	echo rawurlencode( ITS_get_google_maps_api_key() );
}

/**
 * Tell ACF about the Google Maps API key, in case it uses a location field.
 *
 * For details see https://www.advancedcustomfields.com/resources/google-map/
 */
function add_google_maps_key_to_acf() {
	if ( ! empty( ITS_get_google_maps_api_key() ) ) {
		acf_update_setting( 'google_api_key', ITS_get_google_maps_api_key() );
	}
}
add_action( 'acf/init', 'add_google_maps_key_to_acf' );
