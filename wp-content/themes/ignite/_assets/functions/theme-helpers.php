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

/**
 * helper to display heros, by year, in month order
 *
 * @param $year_term_id
 * @param $exclude_post_id
 * @param $title_override
 * @param $paragraph_text
 * @return void
 */

function ITS_display_heroes_by_calendar_year( $year_term_id = null, $exclude_post_id = null, $title_override = null, $paragraph_text = null ) {
	if ( !$year_term_id ) {
		$year_terms = get_the_terms( get_the_ID(), 'calendar-year' );
		if ( empty( $year_terms ) || is_wp_error( $year_terms ) ) {
			return;
		}
		$year_name = $year_terms[0]->name;
		$year_term_id = $year_terms[0]->term_id;
		$year_slug = $year_terms[0]->slug;
	} else {

		$year_terms = get_term_by('term_id', $year_term_id, 'calendar-year');
		$year_name = $year_terms->name;
		$year_slug = $year_terms->slug;
	}

	if ( !$exclude_post_id ) {
		$exclude_post_id = get_the_ID();
	}

	// default heading text, paragraph text, and back link on hero singular pages

	$back_to_link = ( 'hero' == get_post_type() ) ? '<span class="subhead"><a href="/heroes/' . esc_html( $year_slug ) . '">&laquo; Back to ' . esc_html( $year_name ) . '</a></span>' : '';

	//$back_to_link = '<span class="subhead"><a href="/heroes/' . esc_html( $year_slug ) . '">&laquo; Back to ' . esc_html( $year_name ) . '</a></span>';
	$heading_text = '<h2>Heroes of ' . esc_html( $year_name ) . $back_to_link . '</h2>';
	$added_paragraph_text = '';

	// if overrides exist...
	if( $title_override ) {
		$heading_text = '<h2>' . $title_override . $back_to_link .  '</h2>';
	}

	if( $paragraph_text ) {
		$added_paragraph_text =  '<div>' . wpautop( $paragraph_text ) . '</div>';
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
	echo '<section class="other-heroes">' . $heading_text . $added_paragraph_text;
	echo '<section class="hom_grid">';
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


/**
 * helper to get upcoming events
 *
 * @param $args
 * @return WP_Query
 */
function ITS_get_upcoming_events( $args = [] ) {
	$defaults = [
		'posts_per_page' => 5,
		'show_past'      => false,
		'post__in'       => [],
		'show_image'     => true,
		'show_excerpt'   => true,
	];
	$args = wp_parse_args( $args, $defaults );

	$meta_query = [
		[
			'key'     => 'event_start_date', // ✅ flattened key
			'compare' => '>=',
			'value'   => date('Y-m-d'),
			'type'    => 'DATE',
		],
	];

	$query_args = [
		'post_type'      => 'event',
		'posts_per_page' => $args['posts_per_page'],
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_key'       => 'event_start_date',
		'meta_type'      => 'DATE',
		'meta_query'     => $meta_query,
		'post__in'       => $args['post__in'],
	];

	return new WP_Query( $query_args );
}

/**
 * helper to render event items (loop of events)
 *
 * @param $post_id
 * @param $args
 * @return void
 */

function ITS_render_event_item( $post_id = null, $args = [] ) {
	if ( ! $post_id ) $post_id = get_the_ID();

	$defaults = [
		'show_image'   => true,
		'show_excerpt' => true,
	];
	$args = wp_parse_args( $args, $defaults );

	$start_date = get_field('event_start')['date'] ?? '';
	$start_time = get_field('event_start')['time'] ?? '';
	$end_date   = get_field('event_end')['date'] ?? '';
	$end_time   = get_field('event_end')['time'] ?? '';

	$is_past  = $end_date ? ( $end_date < date('Y-m-d') ) : ( $start_date < date('Y-m-d') );
	$classes  = ['event-item'];
	$classes[] = $is_past ? 'event--past' : 'event--upcoming';

	if ( get_field('online_event') ) $classes[] = 'event--online';
	if ( get_field('multi-day_event') ) $classes[] = 'event--multi-day';
	if ( get_field('all_day_event') ) $classes[] = 'event--all-day';

	$terms = wp_get_post_terms($post_id, ['category','post_tag','event-type']);
	foreach( $terms as $term ) {
		$classes[] = 'tax-' . sanitize_html_class($term->slug);
	}

	?>
	<article <?php post_class( $classes, $post_id ); ?>>
		<header>
			<?php if ( $start_date ): ?>
				<div class="event_date">
					<time datetime="<?php echo esc_attr($start_date); ?>">
						<span class="weekday_name">
							<?php
							echo date_i18n('l', strtotime($start_date));
							if( get_field('multi-day_event') ) :
								echo ' - ' .  date_i18n('l', strtotime($end_date));
							endif;
							?>
						</span>
						<span class="month">
							<?php echo date_i18n('M', strtotime($start_date)); ?> &nbsp; | &nbsp;
							<?php echo date_i18n('Y', strtotime($start_date)); ?>
						</span>
						<span class="day">
							<?php
							echo date_i18n('d', strtotime($start_date));
							if( get_field('multi-day_event') ) :
								echo ' - ' .  date_i18n('d', strtotime($end_date));
							endif;
							?>
						</span>
						<?php
							if( get_field('all_day_event') ) :
								// do nothing
							else :
						?>
							<span class="time">
								<?php
								if ($start_time) echo esc_html($start_time);
								if ($end_time) echo ' - ' . esc_html($end_time);
								?>
							</span>
						<?php endif; ?>
					</time>
				</div>
			<?php endif; ?>

			<?php if ( $args['show_image'] && has_post_thumbnail($post_id) ): ?>
				<div class="event_featured_image"><?php echo get_the_post_thumbnail($post_id, 'medium'); ?></div>
			<?php else : ?>
				<div class="event_no_image"></div>
			<?php endif; ?>
			<h3><a href="<?php the_permalink($post_id); ?>"><?php echo get_the_title($post_id); ?></a></h3>
		</header>
		<main>
			<?php if ( $args['show_excerpt'] ): ?>
				<div class="event_excerpt"><?php echo get_the_excerpt($post_id); ?></div>
			<?php endif; ?>
		</main>
		<?php if( ! $is_past ) : ?>
		<footer class="flex-row">
			<?php if ( get_field('display_event_ticket_link', $post_id) && get_field('buy_tickets_link', $post_id) ): ?>
				<a href="<?php echo esc_url(get_field('buy_tickets_link', $post_id)); ?>" class="primary_button">Buy Tickets</a>
			<?php endif;
			if( get_field('display_event_signup_link', $post_id) && get_field('event_signup_link', $post_id) ): ?>
				<a href="<?php echo esc_url(get_field('event_signup_link', $post_id)); ?>" class="primary_button">Sign Up</a>
			<?php endif; ?>
		</footer>
		<?php endif; ?>


	</article>
	<?php
}

/**
 * the full template for posts, press releases and events.
 * individual pieces below this
 * @return void
 */

function ITS_render_news_and_events() {
	// left column = posts + press_release
	$news_query = new WP_Query([
		'post_type'      => ['post', 'press_release'],
		'posts_per_page' => 10,
		'paged'          => get_query_var('paged') ?: 1,
	]);

	// right column = events
	$events_query = ITS_get_upcoming_events(['posts_per_page' => 5]);

	?>
	<div class="news-events-grid two_column_container full_width">
		<div class="news-col list_container">
			<?php if ( $news_query->have_posts() ): ?>
				<?php while ( $news_query->have_posts() ): $news_query->the_post(); ?>
					<article <?php post_class('news-item'); ?>>
						<div class="post_featured_image">
							<a href="<?php the_permalink(); ?>">
								<img
										src="<?php echo has_post_thumbnail() ? esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ) : get_template_directory_uri() . '/_assets/images/ITS-Chicago-Logo.svg' ; ?>"
								/>
							</a>
						</div>
						<div class="post_excerpt_container">
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<span class="post_meta">Posted: <?php the_date(); ?></span>
							<?php the_excerpt(); ?>
						</div>

					</article>
				<?php endwhile; ?>
				<?php the_posts_pagination(); ?>
			<?php endif; wp_reset_postdata(); ?>
		</div>

		<aside class="events-col aside_list_container">
			<h2>Upcoming Events</h2>
			<?php if ( $events_query->have_posts() ): ?>
				<?php while ( $events_query->have_posts() ): $events_query->the_post(); ?>
					<?php ITS_render_event_item(); ?>
				<?php endwhile; ?>
			<a href="/events/" class="button button_primary button_fullwidth">All Events &raquo;</a>
			<?php else: ?>
				<p>No upcoming events.</p>
			<?php endif; wp_reset_postdata(); ?>
		</aside>
	</div>
	<?php
}


/**
 * helper to render event date/time portion (on event single page)
 *
 * @param $post_id
 * @param $args
 * @return void
 */

function ITS_render_event_item_date_time( $post_id = null, $args = [] ) {
	if ( ! $post_id ) $post_id = get_the_ID();

	$defaults = [
		'show_image'   => true,
		'show_excerpt' => true,
	];
	$args = wp_parse_args( $args, $defaults );

	$start_date = get_field('event_start', $post_id)['date'] ?? '';
	$start_time = get_field('event_start', $post_id)['time'] ?? '';
	$end_date   = get_field('event_end', $post_id)['date'] ?? '';
	$end_time   = get_field('event_end', $post_id)['time'] ?? '';

	$is_past  = $end_date ? ( $end_date < date('Y-m-d') ) : ( $start_date < date('Y-m-d') );
	$classes  = ['event-item'];
	$classes[] = $is_past ? 'event--past' : 'event--upcoming';

	if ( get_field('online_event', $post_id) ) $classes[] = 'event--online';
	if ( get_field('multi-day_event', $post_id) ) $classes[] = 'event--multi-day';
	if ( get_field('all_day_event', $post_id) ) $classes[] = 'event--all-day';

	$terms = wp_get_post_terms($post_id, ['category','post_tag','event-type']);
	foreach( $terms as $term ) {
		$classes[] = 'tax-' . sanitize_html_class($term->slug);
	}

	?>
		<div class="event_day_time_container">
			<?php if ( $start_date ): ?>
				<div class="event_date">
					<time datetime="<?php echo esc_attr($start_date); ?>">
						<span class="weekday_name">
							<?php
							echo date_i18n('l', strtotime($start_date));
							if( get_field('multi-day_event', $post_id) ) :
								echo ' - ' .  date_i18n('l', strtotime($end_date));
							endif;
							?>
						</span>
						<span class="month">
							<?php echo date_i18n('M', strtotime($start_date)); ?> &nbsp; | &nbsp;
							<?php echo date_i18n('Y', strtotime($start_date)); ?>
						</span>
						<span class="day">
							<?php
							echo date_i18n('d', strtotime($start_date));
							if( get_field('multi-day_event', $post_id) ) :
								echo ' - ' .  date_i18n('d', strtotime($end_date));
							endif;
							?>
						</span>
						<?php
						if( get_field('all_day_event', $post_id) ) :
							// do nothing
						else :
							?>
							<span class="time">
								<?php
								if ($start_time) echo esc_html($start_time);
								if ($end_time) echo ' - ' . esc_html($end_time);
								?>
							</span>
						<?php endif; ?>
					</time>
				</div>
			<?php endif; ?>

			<?php if ( $args['show_image'] && has_post_thumbnail($post_id) ): ?>
				<div class="event_featured_image"><?php echo get_the_post_thumbnail($post_id, 'medium'); ?></div>
			<?php else : ?>
				<div class="event_no_image"></div>
			<?php endif; ?>
			<h3><a href="<?php the_permalink($post_id); ?>"><?php echo get_the_title($post_id); ?></a></h3>
		</div>

	<?php
}

/**
 * Get events (smart start/end handling).
 *
 * @param array $args {
 *     @type int    $posts_per_page   Number of events to fetch.
 *     @type string $order            ASC or DESC (default ASC).
 *     @type bool   $show_past        Whether to include past events.
 *     @type array  $post__in         Specific post IDs.
 * }
 * @return WP_Query
 */
function ITS_get_events( $args = [] ) {
	$defaults = [
		'posts_per_page' => 5,
		'order'          => 'ASC',
		'show_past'      => false,
		'post__in'       => [],
	];
	$args = wp_parse_args( $args, $defaults );

	$today = date('Y-m-d');

	// Meta query to handle start/end gracefully
	$meta_query = [
		'relation' => 'OR',

		// Case 1: Has an end date, compare against that
		[
			'key'     => 'event_end_date',
			'value'   => $today,
			'compare' => $args['show_past'] ? '<=' : '>=',
			'type'    => 'DATE',
		],

		// Case 2: No end date, fallback to start date
		[
			'key'     => 'event_start_date',
			'value'   => $today,
			'compare' => $args['show_past'] ? '<=' : '>=',
			'type'    => 'DATE',
		],
	];

	$query_args = [
		'post_type'      => 'event',
		'posts_per_page' => $args['posts_per_page'],
		'post__in'       => $args['post__in'],
		'orderby'        => 'meta_value',
		'meta_key'       => 'event_start_date', // used for ordering
		'meta_type'      => 'DATE',
		'order'          => $args['order'],
		'meta_query'     => $meta_query,
	];

	return new WP_Query( $query_args );
}



/**
 * Get sponsor levels ordered by custom taxonomy meta 'display_order'
 *
 * @return WP_Term[]
 */
function ITS_get_ordered_sponsor_levels() {
	$terms = get_terms([
		'taxonomy'   => 'sponsor-level',
		'hide_empty' => false,
	]);

	if (empty($terms) || is_wp_error($terms)) {
		return [];
	}

	usort($terms, function($a, $b) {
		$order_a = (int) get_term_meta($a->term_id, 'display_order', true);
		$order_b = (int) get_term_meta($b->term_id, 'display_order', true);
		return $order_a <=> $order_b;
	});

	return $terms;
}



/**
 * Get sponsors in a given sponsor-level term that are marked to display
 *
 * @param int $term_id
 * @return WP_Post[]
 */
function ITS_get_sponsors_by_level($term_id) {
	$args = [
		'post_type'      => 'sponsor',
		'posts_per_page' => 99,
		'meta_query'     => [
			[
				'key'   => 'display_on_sponsors_page',
				'value' => 1,
			]
		],
		'tax_query' => [
			[
				'taxonomy' => 'sponsor-level',
				'field'    => 'term_id',
				'terms'    => $term_id,
			]
		],
		'orderby' => 'title',
		'order'   => 'ASC',
	];
	return get_posts($args);
}

/**
 * Render all sponsor levels and their sponsors
 */
function ITS_render_all_sponsor_levels() {
	$levels = ITS_get_ordered_sponsor_levels();

	if (empty($levels)) {
		echo '<p>No sponsors available.</p>';
		return;
	}

	foreach ($levels as $level) {
		ITS_render_sponsor_level($level);
	}
}

/**
 * Render a sponsor level section
 *
 * @param WP_Term $level
 */
function ITS_render_sponsor_level($level) {
	$sponsors = ITS_get_sponsors_by_level($level->term_id);
	if (empty($sponsors)) {
		return;
	}

	get_template_part('template-parts/sponsor/level', null, [
		'level'    => $level,
		'sponsors' => $sponsors,
	]);
}

/**
 * Check if a sponsor should be featured today
 *
 * @param int $post_id
 * @return bool
 */
function ITS_is_sponsor_featured_today($post_id) {
	$dates = get_field('display_dates', $post_id);
	if (empty($dates)) {
		return false;
	}

	$today = new DateTime('now', new DateTimeZone('America/Chicago'));

	foreach ($dates as $row) {
		$start = $row['feature_dates']['start'] ?? null;
		$end   = $row['feature_dates']['end'] ?? null;

		if ($start && $end) {
			$start_date = DateTime::createFromFormat('Y-m-d', $start, new DateTimeZone('America/Chicago'));
			$end_date   = DateTime::createFromFormat('Y-m-d', $end, new DateTimeZone('America/Chicago'));

			if ($start_date && $end_date && $today >= $start_date && $today <= $end_date) {
				return true;
			}
		}
	}
	return false;
}


/**
 * Get sponsor(s) that are featured today
 *
 * @return WP_Post[]
 */
function ITS_get_featured_sponsors_today() {
	$args = [
		'post_type'      => 'sponsor',
		'posts_per_page' => 99,
		
	];
	$sponsors = get_posts($args);

	$featured = array_filter($sponsors, 'ITS_is_sponsor_featured_today');

	return $featured;
}