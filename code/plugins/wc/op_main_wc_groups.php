<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! lct_get_setting( 'acf_dev' ) ):

	acf_add_local_field_group( [
		'key'                        => 'group_5813d8d117f52',
		'title'                      => 'General',
		'fields'                     => [
			[
				'key'                => 'field_5813d8d1322f2',
				'label'              => 'Disable Image Sizes',
				'name'               => 'lct:::wc::disable_image_sizes',
				'aria-label'         => '',
				'type'               => 'select',
				'instructions'       => '',
				'required'           => 0,
				'conditional_logic'  => 0,
				'wrapper'            => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'choices'            => [
					'shop_thumbnail'                => 'shop_thumbnail',
					'shop_catalog'                  => 'shop_catalog',
					'shop_single'                   => 'shop_single',
					'woocommerce_gallery_thumbnail' => 'woocommerce_gallery_thumbnail',
					'woocommerce_thumbnail'         => 'woocommerce_thumbnail',
					'woocommerce_single'            => 'woocommerce_single',
				],
				'default_value'      => [
					0 => 'shop_thumbnail',
					1 => 'shop_catalog',
					2 => 'shop_single',
					3 => 'woocommerce_gallery_thumbnail',
					4 => 'woocommerce_thumbnail',
					5 => 'woocommerce_single',
				],
				'allow_null'         => 1,
				'multiple'           => 1,
				'ui'                 => 1,
				'ajax'               => 0,
				'return_format'      => 'value',
				'lct_preset_choices' => '',
				'lct_class_selector' => '',
				'placeholder'        => '',
				'menu_order'         => 0,
			],
			[
				'key'                => 'field_56df4a4eb6b2d',
				'label'              => 'WooCommerce items on profile page',
				'name'               => 'lct:::hide_woocommerce_items_on_profile_page',
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
				'ui_on_text'         => 'Hide',
				'ui_off_text'        => 'Show',
				'lct_class_selector' => '',
				'menu_order'         => 1,
			],
		],
		'location'                   => [
			[
				[
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'lct_acf_op_main_wc',
				],
			],
		],
		'menu_order'                 => 8,
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
