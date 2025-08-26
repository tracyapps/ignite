<?php
/**
 * 	Template part for singular post display
 *  DEFAULT view
 *
 *
 */

$start_date = get_field('event_start')['date'] ?? '';
$start_time = get_field('event_start')['time'] ?? '';
$end_date   = get_field('event_end')['date'] ?? '';
$end_time   = get_field('event_end')['time'] ?? '';

?>

<article id="post-<?php the_ID(); ?>">
	<header class="post_header">
		<?php
		if( has_post_thumbnail() ) :
			$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
			echo '<div class="page_featured_image">';
			echo '<img class="fade-bottom" src="' . esc_url( $featured_img_url ) . '" />';
			echo '<h1 class="post_title">' . wp_kses_post( get_the_title() ) . '</h1>';
			echo '</div>';

		else :
			echo '<div class="no_featured_image">';
			echo '<h1 class="post_title">' . wp_kses_post( get_the_title() ) . '</h1>';
			echo '</div>';
		endif;
		?>
		<div class="event_details">
			<?php if ( $start_date ): ?>
				<p class="event-date">
					<time datetime="<?php echo esc_attr($start_date); ?>">
						<?php echo date_i18n('F j, Y', strtotime($start_date)); ?>
						<?php if ($start_time) echo ' &bull; ' . esc_html($start_time); ?>
					</time>
				</p>
			<?php endif; ?>
		</div>

	</header>
	<main class="event_main">
		<?php the_content(); ?>
	</main>
	<footer class="event_footer">
		<?php
		if( get_field( 'display_event_organizer_contact' ) ) : ?>
		<div class="organizer card max_text_width">
			<?php
			$organizer_name = get_field('event_organizer_name' ) ?  esc_html( get_field( 'event_organizer_name' ) )  : '';
			$organizer_photo = get_field('event_organizer_photo' ) ? '<div class="organizer_photo"><img src="' . esc_url(  get_field('event_organizer_photo' ) ) . '"></div>' : '';
			$organizer_email = get_field('event_organizer_email' ) ? '<a href="mailto:' . esc_attr( get_field('event_organizer_email' ) ) . '">Email Organizer</a>' : '';
			$organizer_bio = get_field('event_organizer_bio' ) ? '<div class="organizer_bio">' . wpautop( get_field('event_organizer_bio' ) ) . '</div>' : '';

			printf(
					'
						 <div class="flex-row">
							 <div class="organizer_details">
							 <h4>
								<span class="event_organizer_title">Event Organizer:</span>
								%s
							 </h4>
								%s
								%s
							 </div> 
							 %s
						 </div>
					',
				$organizer_name,
				$organizer_email,
				$organizer_bio,
				$organizer_photo
			);

			?>
		</div>
		<?php endif; ?>
		<div class="list_of_all_tags">
			<?php the_tags( '', ' | ', '' ); ?>
		</div>
	</footer>

</article>

