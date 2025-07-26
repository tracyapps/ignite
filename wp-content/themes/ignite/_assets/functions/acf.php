<?php

/**
 * On post save, map values from 'extended_definition'
 * to WordPress's native content field
 */


add_filter( 'block_categories_all', 'ITS_add_block_categories', 10, 2 );

function ITS_add_block_categories( $categories ) {
	$custom_categories = array(
		array(
			'slug'     => 'ITS',
			'title'    => __( 'Ignite The Spirit Blocks', 'ITS' ),
			'icon'     => null,
			'position' => 1,
		)
	);

	$added_categories = array();

	// Prepare an associative array with positions as keys.
	foreach ( $custom_categories as $custom_category ) {
		$position = $custom_category['position'];
		unset( $custom_category['position'] );
		$added_categories[ $position ] = $custom_category;
	}

	// Sort the categories to insert by their positions/key.
	ksort( $added_categories );

	// Insert the sorted categories into the existing categories array.
	foreach ( $added_categories as $position => $custom_category ) {
		array_splice( $categories, $position, 0, array( $custom_category ) );
	}

	return $categories;
}

add_action('acf/init', 'ITS_initialize_acf_blocks');

function ITS_initialize_acf_blocks() {
  // Check function exists.

  if( function_exists('acf_register_block_type') ) {

	/*acf_register_block_type(array(
		'name' 				 => 'hero-framed',
		'title' 			 => 'Framed Hero',
		'active' 			 => true,
		'description' 		 => 'Hero block (new) with main column, and secondary column options',
		'category' 			 => 'ITS',
		'icon' 				 => file_get_contents( get_template_directory() . '/acf-blocks/_block-assets/icon-framed-hero.svg' ),
		'keywords' 			 => array(
				'hero, ITS,',
			),
		'post_types' 		 => array(
				'post',
				'page',
				'glossary',
			),
		'mode'				 => 'auto',
		'align' 			 => 'full',
		'align_text' 		 => '',
		'align_content' 	 => 'top',
		'render_template' 	 => 'acf-blocks/framed-hero-block/framed-hero.php',
		'render_callback' 	 => '',
		'enqueue_style' 	 => '',
		'enqueue_script' 	 => '',
		'enqueue_assets' 	 => '',
		'supports' 			 => array(
			'anchor' 			 => true,
			'align' 			 => ['full'],
			'align_text' 		 => false,
			'align_content' 	 => false,
			'full_height'  		 => true,
			'mode' 				 => true,
			'multiple' 			 => true,
			'example' 			 => array(),
			'jsx' 				 => false,
		),
	));*/




	// page link block
	acf_register_block_type(array(
		'name' 				=> 'page-link-block',
		'title' 			=> __('Page Link'),
		'description'		=> __('Similar to media/text box, with more options'),
		'render_template'  	=> get_template_directory() . '/acf-blocks/page-link-block/page-link.php',
		'icon'				=> file_get_contents( get_template_directory() . '/acf-blocks/_block-assets/icon-page-link.svg' ),
		'category' 			=> 'ITS',
		'mode' 				 => 'auto',
		'align' 			 => 'full',
		'example'  			=> array(
			'attributes' 	=> array(
				'mode' 		=> 'preview',
				'data' 		=> array(
					'is_preview'    => true
				)
			)
		)
	));



  }
}