<?php

function ITS_custom_logo_setup() {
	$defaults = array(
		'height'               => 94,
		'width'                => 200,
		'flex-height'          => true,
		'flex-width'           => true,
		'header-text'          => array( 'site-title', 'site-description' ),
		'unlink-homepage-logo' => true,
	);
	add_theme_support( 'custom-logo', $defaults );
}
add_action( 'after_setup_theme', 'ITS_custom_logo_setup' );

function the_breadcrumb() {

    $sep = ' > ';

    if (!is_front_page()) {

	// Start the breadcrumb with a link to your homepage
        echo '<div class="breadcrumbs">';
       if(is_tag()){
            echo "Related Content " . $sep . single_tag_title( '', false );
        }
	// If the current page is a single post, show its title with the separator

    // Search

    if(is_search()) {
        echo "Search Results for" . $sep . get_search_query();
    }
	// if you have a static page assigned to be you posts list page. It will find the title of the static page and display it. i.e Home >> Blog
        if (is_home()){
            global $post;
            $page_for_posts_id = get_option('page_for_posts');
            if ( $page_for_posts_id ) {
                $post = get_post($page_for_posts_id);
                setup_postdata($post);
                the_title();
                rewind_posts();
            }
        }

        echo '</div>';
    }
}
/*
* Credit: http://www.thatweblook.co.uk/blog/tutorials/tutorial-wordpress-breadcrumb-function/
*/


add_post_type_support( 'page', 'excerpt' );


function ITS_display_heroes_by_calendar_year( $year_term_id = null, $exclude_post_id = null ) {
	if ( !$year_term_id ) {
		$year_terms = get_the_terms( get_the_ID(), 'calendar-year' );
		if ( empty( $year_terms ) || is_wp_error( $year_terms ) ) {
			return;
		}
		$year_name = $year_terms[0]->name;
		$year_term_id = $year_terms[0]->term_id;
	} else {

		$year_terms = get_term_by('term_id', $year_term_id, 'calendar-year');
		$year_name = $year_terms->name;
	}

	if ( !$exclude_post_id ) {
		$exclude_post_id = get_the_ID();
	}

	// Step 1: Get all hero posts from that year
	$heroes = get_posts([
		'post_type' => 'hero',
		'posts_per_page' => -1,
		'post__not_in' => [$exclude_post_id],
		'tax_query' => [
			[
				'taxonomy' => 'calendar-year',
				'field' => 'term_id',
				'terms' => $year_term_id,
			]
		],
	]);

	if ( empty( $heroes ) ) return;

	// Step 2: Attach calendar-month term and its ACF order to each hero
	$sorted_heroes = [];
	foreach ( $heroes as $hero ) {
		$month_terms = get_the_terms( $hero->ID, 'calendar-month' );
		if ( empty( $month_terms ) || is_wp_error( $month_terms ) ) continue;

		$month = $month_terms[0];
		$order = get_term_meta( $month->term_id, 'order', true );
		$order = is_numeric( $order ) ? intval( $order ) : 999;

		$sorted_heroes[] = [
			'post'       => $hero,
			'month'      => $month,
			'order'      => $order,
			'abbr'       => get_term_meta( $month->term_id, 'abbreviation', true ),
		];
	}

	// Step 3: Sort the array by the ACF order
	usort( $sorted_heroes, fn($a, $b) => $a['order'] <=> $b['order'] );

	// Step 4: Output
	echo '<section class="other-heroes"><h2>Heroes of ' . esc_html( $year_name ) . '</h2>';
	echo '<section class="hom_archive hom_grid">';
	foreach ( $sorted_heroes as $item ) {
		$hero = $item['post'];
		setup_postdata( $hero );
		?>
		<article id="content_ID-<?php echo esc_attr( $hero->ID ); ?>" <?php post_class( 'hom_list', $hero->ID ); ?>>
			<div class="hom_thumbnail_image">
				<a href="<?php echo get_permalink( $hero ); ?>">
					<?php echo get_the_post_thumbnail( $hero, 'full' ); ?>
				</a>
				<div class="month_abbr"><span><?php echo esc_html( $item['abbr'] ); ?></span></div>
			</div>
		</article>
		<?php
	}

	echo '</section></section>';

	wp_reset_postdata();
}