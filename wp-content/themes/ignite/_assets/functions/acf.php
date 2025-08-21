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

	  // page section block
	  acf_register_block_type(array(
		  'name' => 'page-section',
		  'title' => 'Page Section',
		  'active' => true,
		  'description' => 'A section or grouping with it\'s own design element(s)',
		  'category' => 'ITS',
		  'icon' => file_get_contents( get_template_directory() . '/acf-blocks/_block-assets/icon-section.svg' ),
		  'keywords' => array(),
		  'post_types' => array(
			  'post',
			  'page',
		  ),
		  'mode' => 'auto',
		  'align' => '',
		  'align_text' => '',
		  'align_content' => 'top',
		  'render_template' => get_template_directory() . '/acf-blocks/page-section/section.php',
		  'render_callback' => '',
		  'enqueue_style' => '',
		  'enqueue_script' => '',
		  'enqueue_assets' => '',
		  'supports' => array(
			  'anchor' => false,
			  'align' => true,
			  'align_text' => false,
			  'align_content' => false,
			  'full_height' => false,
			  'mode' => true,
			  'multiple' => true,
			  'example' => array(),
			  'jsx' => false,
		  ),
		  'acfe_autosync' => array(
			  'json',
		  ),
	  ));


	  // display content block
	  acf_register_block_type(array(
		  'name' => 'display-content',
		  'title' => 'Display Content',
		  'active' => true,
		  'description' => 'Display a grid, list, or individual posts.',
		  'category' => 'ITS',
		  'icon' => file_get_contents( get_template_directory() . '/acf-blocks/_block-assets/icon-display-content.svg' ),
		  'keywords' => array(
			  'content',
			  'custom post',
			  'board members',
			  'memorial',
			  'ITS',
		  ),
		  'post_types' => array(
			  'post',
			  'page',
		  ),
		  'mode' => 'auto',
		  'align' => 'full',
		  'align_text' => '',
		  'align_content' => 'top',
		  'render_template' => get_template_directory() . '/acf-blocks/display-content/display-content.php',
		  'render_callback' => '',
		  'enqueue_style' => '',
		  'enqueue_script' => '',
		  'enqueue_assets' => '',
		  'supports' => array(
			  'anchor' => true,
			  'align' => array(
				  'wide',
				  'full',
			  ),
			  'align_text' => false,
			  'align_content' => false,
			  'full_height' => false,
			  'mode' => true,
			  'multiple' => true,
			  'example' => array(),
			  'jsx' => false,
		  ),
		  'acfe_autosync' => array(
			  'json',
		  ),
	  ));

	  // slider block

	  acf_register_block_type(array(
		  'name' => 'slider-block',
		  'title' => 'Slider',
		  'active' => true,
		  'description' => '',
		  'category' => 'ITS',
		  'icon' => file_get_contents( get_template_directory() . '/acf-blocks/_block-assets/icon-page-hero.svg' ),
		  'keywords' => array(
			  'header',
			  'content',
			  'slider',
			  'carousel',
			  'ITS',
		  ),
		  'post_types' => array(
			  'page',
		  ),
		  'mode' => 'auto',
		  'align' => 'full',
		  'align_text' => '',
		  'align_content' => 'top',
		  'render_template' => get_template_directory() . '/acf-blocks/slider-block/slider.php',
		  'render_callback' => '',
		  'enqueue_style' => '',
		  'enqueue_script' => get_template_directory_uri() . '/_assets/js/block_scripts/splide.js',
		  'enqueue_assets' => '',
		  'supports' => array(
			  'anchor' => true,
			  'align' => array(
				  'full',
				  ),
			  'align_text' => false,
			  'align_content' => 'matrix',
			  'full_height' => true,
			  'mode' => true,
			  'multiple' => true,
			  'example' => array(),
			  'jsx' => true,
		  ),
		  'acfe_autosync' => array(
			  'json',
		  ),
	  ));

	  // display heros block
	  acf_register_block_type(array(
		  'name' => 'display-heros',
		  'title' => 'Display Heros',
		  'active' => true,
		  'description' => 'Display a grid of heros of the month, by year',
		  'category' => 'ITS',
		  'icon' => file_get_contents( get_template_directory() . '/acf-blocks/_block-assets/icon-display-heros.svg' ),
		  'keywords' => array(
			  'content',
			  'custom post',
			  'hom',
			  'heros',
			  'heros of the month',
			  'ITS',
		  ),
		  'post_types' => array(
			  'post',
			  'page',
		  ),
		  'mode' => 'auto',
		  'align' => 'full',
		  'align_text' => '',
		  'align_content' => 'top',
		  'render_template' => get_template_directory() . '/acf-blocks/display-heros/display-heros.php',
		  'render_callback' => '',
		  'enqueue_style' => '',
		  'enqueue_script' => '',
		  'enqueue_assets' => '',
		  'supports' => array(
			  'anchor' => true,
			  'align' => array(
				  'wide',
				  'full',
			  ),
			  'align_text' => false,
			  'align_content' => false,
			  'full_height' => false,
			  'mode' => true,
			  'multiple' => true,
			  'example' => array(),
			  'jsx' => false,
		  ),
		  'acfe_autosync' => array(
			  'json',
		  ),
	  ));

  }
}