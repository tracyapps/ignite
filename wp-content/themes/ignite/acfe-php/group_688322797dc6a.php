<?php 

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_688322797dc6a',
	'title' => 'Block : Page Hero',
	'fields' => array(
		array(
			'key' => 'field_6883227959c9b',
			'label' => 'Layout',
			'name' => 'layout',
			'aria-label' => '',
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'layout' => 'block',
			'acfe_seamless_style' => 0,
			'acfe_group_modal' => 0,
			'sub_fields' => array(
				array(
					'key' => 'field_688322a059c9c',
					'label' => 'Layout',
					'name' => 'layout',
					'aria-label' => '',
					'type' => 'acfe_image_selector',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => array(
						'light-text_left' => '/wp-content/themes/ignite/acf-blocks/_block-assets/Page-hero-block—light_text-left.svg',
						'light-text_right' => '/wp-content/themes/ignite/acf-blocks/_block-assets/Page-hero-block—light_text-right.svg',
						'dark-text_left' => '/wp-content/themes/ignite/acf-blocks/_block-assets/Page-hero-block—dark_text-left.svg',
						'dark-text_right' => '/wp-content/themes/ignite/acf-blocks/_block-assets/Page-hero-block—dark_text-right.svg',
					),
					'default_value' => false,
					'image_size' => 'navigation-image',
					'width' => '',
					'height' => '',
					'border' => 4,
					'return_format' => 'value',
					'allow_null' => 0,
					'multiple' => 0,
					'max' => '',
					'layout' => 'horizontal',
					'allow_in_bindings' => 0,
					'min' => '',
				),
			),
			'acfe_group_modal_close' => 0,
			'acfe_group_modal_button' => '',
			'acfe_group_modal_size' => 'large',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'block',
				'operator' => '==',
				'value' => 'acf/page-hero-block',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'left',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
	'acfe_autosync' => array(
		0 => 'php',
		1 => 'json',
	),
	'acfe_form' => 0,
	'acfe_display_title' => '',
	'acfe_meta' => '',
	'acfe_note' => '',
	'modified' => 1753994464,
));

endif;