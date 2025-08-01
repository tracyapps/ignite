<?php 

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_688c2b87c4b37',
	'title' => 'testing one',
	'fields' => array(
		array(
			'key' => 'field_688c2b88f376e',
			'label' => '',
			'name' => '',
			'aria-label' => '',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'acfe_textarea_code' => 0,
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'rows' => 10,
			'placeholder' => '',
			'new_lines' => 'wpautop',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
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
	'modified' => 1754016683,
));

endif;