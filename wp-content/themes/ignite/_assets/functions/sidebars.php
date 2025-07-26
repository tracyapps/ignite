<?php

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function ITS_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer', 'ITS' ),
			'id'            => 'footer-main',
			'description'   => __( 'Add widgets here to appear in your footer.', 'ITS' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);


}
add_action( 'widgets_init', 'ITS_widgets_init' );
