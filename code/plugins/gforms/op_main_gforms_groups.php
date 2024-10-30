<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! lct_get_setting( 'acf_dev' ) ):

	acf_add_local_field_group( [
		'key'                        => 'group_55ce86abcd40a',
		'title'                      => 'General',
		'fields'                     => [
			[
				'key'                => 'field_55cea24748247',
				'label'              => 'LCT\'s Gravity Forms CSS Tweaks',
				'name'               => 'lct:::use_gforms_css_tweaks',
				'aria-label'         => '',
				'type'               => 'true_false',
				'instructions'       => '',
				'required'           => 0,
				'conditional_logic'  => 0,
				'wrapper'            => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'default_value'      => 1,
				'message'            => '',
				'ui'                 => 1,
				'ui_on_text'         => 'Enable',
				'ui_off_text'        => 'Disable',
				'lct_class_selector' => '',
				'menu_order'         => 0,
			],
			[
				'key'                => 'field_55ce86bd562d8',
				'label'              => 'Custom Submit Button Class',
				'name'               => 'lct:::gform_button_custom_class',
				'aria-label'         => '',
				'type'               => 'text',
				'instructions'       => '',
				'required'           => 0,
				'conditional_logic'  => [
					[
						[
							'field'    => 'field_55cea24748247',
							'operator' => '==',
							'value'    => '1',
						],
					],
				],
				'wrapper'            => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'default_value'      => '',
				'maxlength'          => '',
				'placeholder'        => 'button-lca_gforms',
				'prepend'            => '',
				'append'             => '',
				'lct_class_selector' => '',
				'menu_order'         => 1,
			],
			[
				'key'                => 'field_56e1220e69af1',
				'label'              => 'Cj Spam Check',
				'name'               => 'lct:::enable_cj_spam_check',
				'aria-label'         => '',
				'type'               => 'true_false',
				'instructions'       => '',
				'required'           => 0,
				'conditional_logic'  => 0,
				'wrapper'            => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'default_value'      => 0,
				'message'            => '',
				'ui'                 => 1,
				'ui_on_text'         => 'Enable',
				'ui_off_text'        => 'Disable',
				'lct_class_selector' => '',
				'menu_order'         => 2,
			],
			[
				'key'                => 'field_56e1221169af2',
				'label'              => 'Enable Cj Spam Check Email',
				'name'               => 'lct:::enable_cj_spam_check_email',
				'aria-label'         => '',
				'type'               => 'email',
				'instructions'       => '',
				'required'           => 1,
				'conditional_logic'  => [
					[
						[
							'field'    => 'field_56e1220e69af1',
							'operator' => '==',
							'value'    => '1',
						],
					],
				],
				'wrapper'            => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'default_value'      => '',
				'placeholder'        => '',
				'prepend'            => '',
				'append'             => '',
				'lct_class_selector' => '',
				'menu_order'         => 3,
			],
			[
				'key'                => 'field_56e1250c3ef86',
				'label'              => 'Store Form Data',
				'name'               => 'lct:::gforms_store',
				'aria-label'         => '',
				'type'               => 'radio',
				'instructions'       => '',
				'required'           => 1,
				'conditional_logic'  => 0,
				'wrapper'            => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'choices'            => [
					0 => 'Don\'t store the selected forms',
					1 => 'Yes, store the selected forms',
				],
				'allow_null'         => 1,
				'other_choice'       => 0,
				'save_other_choice'  => 0,
				'default_value'      => 0,
				'layout'             => 'vertical',
				'return_format'      => 'value',
				'lct_preset_choices' => '',
				'lct_class_selector' => [
					0 => 'hide_label',
				],
				'menu_order'         => 4,
			],
			[
				'key'                       => 'field_56e125133ef87',
				'label'                     => 'Select Gravity Forms',
				'name'                      => 'lct:::gforms',
				'aria-label'                => '',
				'type'                      => 'checkbox',
				'instructions'              => '',
				'required'                  => 0,
				'conditional_logic'         => 0,
				'wrapper'                   => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'choices'                   => [
				],
				'allow_custom'              => 0,
				'save_custom'               => 0,
				'default_value'             => [
				],
				'layout'                    => 'vertical',
				'toggle'                    => 0,
				'return_format'             => 'value',
				'lct_preset_choices'        => 'pretty_gforms_forms',
				'lct_class_selector'        => '',
				'custom_choice_button_text' => 'Add new choice',
				'menu_order'                => 5,
			],
		],
		'location'                   => [
			[
				[
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'lct_acf_op_main_gforms',
				],
			],
		],
		'menu_order'                 => 7,
		'position'                   => 'normal',
		'style'                      => 'default',
		'label_placement'            => 'top',
		'instruction_placement'      => 'label',
		'hide_on_screen'             => '',
		'active'                     => true,
		'description'                => '',
		'show_in_rest'               => 1,
		'afwp_use_exclude_audit_acf' => 0,
	] );

endif;
