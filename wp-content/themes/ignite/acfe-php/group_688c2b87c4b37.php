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
		array(
			'key' => 'field_68924659c3651',
			'label' => 'select one',
			'name' => 'select_one',
			'aria-label' => '',
			'type' => 'button_group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'one' => 'First one (condition, true)',
				'two' => 'second (false)',
				'three' => 'Third one, also true',
				'four' => 'Fourth, also false',
				'five' => 'and one more true for good measure',
			),
			'default_value' => '',
			'return_format' => 'value',
			'allow_null' => 0,
			'allow_in_bindings' => 0,
			'layout' => 'horizontal',
		),
		array(
			'key' => 'field_689246bac3652',
			'label' => 'conditional something',
			'name' => 'conditional_something',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_68924659c3651',
						'operator' => '==',
						'value' => 'one',
					),
				),
				array(
					array(
						'field' => 'field_68924659c3651',
						'operator' => '==',
						'value' => 'three',
					),
				),
				array(
					array(
						'field' => 'field_68924659c3651',
						'operator' => '==',
						'value' => 'five',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
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
	'position' => 'side',
	'style' => 'default',
	'label_placement' => 'top',
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
	'modified' => 1754416950,
));

endif;