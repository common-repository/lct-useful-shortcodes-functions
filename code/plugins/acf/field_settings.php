<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2016.12.09
 */
class lct_acf_field_settings
{
	public $excluded_field_types;
	public $check_when_cloned = [];


	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2016.12.09
	 */
	function __construct( $args = [] )
	{
		//Store $args
		$this->args = $args;


		//Store parent class, maybe
		if ( $this->args['load_parent'] ) {
			$this->zxzp = lct();
		}


		//Setup WordPress action and filter hooks
		$this->load_hooks();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    7.51
	 * @verified 2018.03.20
	 */
	function load_hooks()
	{
		//bail early if already ran
		if ( lct_did() ) {
			return;
		}


		/**
		 * everytime
		 */
		lct_acf_register_field_setting( 'preset_choices' );
		lct_acf_register_field_setting( 'class_selector' );
		lct_acf_register_field_setting( 'roles_n_caps' );
		lct_acf_register_field_setting( 'roles_n_caps_viewonly' );
		lct_acf_register_field_setting( 'pdf_display' );
		lct_acf_register_field_setting( 'reference_only' );


		/**
		 * checkbox
		 */
		$this->check_when_cloned[] = 'checkbox';
		add_filter( 'acf/load_field/type=checkbox', [ $this, 'load_field_update_choices' ] );

		/**
		 * radio
		 */
		$this->check_when_cloned[] = 'radio';
		add_filter( 'acf/load_field/type=radio', [ $this, 'load_field_update_choices' ] );

		/**
		 * select
		 */
		$this->check_when_cloned[] = 'select';
		add_filter( 'acf/load_field/type=select', [ $this, 'load_field_update_choices' ] );


		/**
		 * clone
		 */
		add_filter( 'acf/clone_field', [ $this, 'clone_field_update_choices' ], 999, 2 );


		//if ( lct_frontend() ) {}


		if ( lct_wp_admin_all() ) {
			add_action( 'acf/render_field_settings/type=button_group', [ $this, 'render_field_settings_button_group' ] );

			add_action( 'acf/render_field_settings/type=checkbox', [ $this, 'render_field_settings_checkbox' ] );

			add_action( 'acf/render_field_settings/type=date_picker', [ $this, 'render_field_settings_date_picker' ] );

			add_action( 'acf/render_field_settings/type=date_time_picker', [ $this, 'render_field_settings_date_time_picker' ] );

			add_action( 'acf/render_field_settings/type=time_picker', [ $this, 'render_field_settings_time_picker' ] );

			add_action( 'acf/render_field_settings/type=email', [ $this, 'render_field_settings_email' ] );

			add_action( 'acf/render_field_settings/type=message', [ $this, 'render_field_settings_message' ] );

			add_action( 'acf/render_field_settings/type=number', [ $this, 'render_field_settings_number' ] );

			add_action( 'acf/render_field_settings/type=post_object', [ $this, 'render_field_settings_post_object' ] );

			add_action( 'acf/render_field_settings/type=radio', [ $this, 'render_field_settings_radio' ] );

			add_action( 'acf/render_field_settings/type=repeater', [ $this, 'render_field_settings_repeater' ] );

			add_action( 'acf/render_field_settings/type=user', [ $this, 'render_field_settings_user' ] );

			add_action( 'acf/render_field_settings/type=select', [ $this, 'render_field_settings_select' ] );

			add_action( 'acf/render_field_settings/type=taxonomy', [ $this, 'render_field_settings_taxonomy' ] );

			add_action( 'acf/render_field_settings/type=text', [ $this, 'render_field_settings_text' ] );

			add_action( 'acf/render_field_settings/type=textarea', [ $this, 'render_field_settings_textarea' ] );

			add_action( 'acf/render_field_settings/type=true_false', [ $this, 'render_field_settings_true_false' ] );


			//3rd party
			add_action( 'acf/render_field_settings/type=validated_field', [ $this, 'render_field_settings_validated_field' ] );
		}


		if ( lct_wp_admin_non_ajax() ) {
			add_filter( 'acf/update_field/type=checkbox', [ $this, 'update_field_update_choices' ] );

			add_filter( 'acf/update_field/type=radio', [ $this, 'update_field_update_choices' ] );

			add_filter( 'acf/update_field/type=select', [ $this, 'update_field_update_choices' ] );
		}


		//if ( lct_ajax_only() ) {}


		//special :: run on front & back end as long as - no ajax
		if ( ! lct_doing() ) {
			/**
			 * checkbox
			 */
			add_filter( 'acf/prepare_field/type=checkbox', [ $this, 'prepare_field_add_class_selector' ] );

			/**
			 * clone
			 */
			add_filter( 'acf/prepare_field/type=date_picker', [ $this, 'prepare_field_add_class_selector' ] );

			add_filter( 'acf/prepare_field/type=date_time_picker', [ $this, 'prepare_field_add_class_selector' ] );

			add_filter( 'acf/prepare_field/type=email', [ $this, 'prepare_field_add_class_selector' ] );

			add_filter( 'acf/prepare_field/type=message', [ $this, 'prepare_field_add_class_selector' ] );

			add_filter( 'acf/prepare_field/type=number', [ $this, 'prepare_field_add_class_selector' ] );

			add_filter( 'acf/prepare_field/type=post_object', [ $this, 'prepare_field_add_class_selector' ] );

			/**
			 * radio
			 */
			add_filter( 'acf/prepare_field/type=radio', [ $this, 'prepare_field_add_class_selector' ] );

			add_filter( 'acf/prepare_field/type=repeater', [ $this, 'prepare_field_add_class_selector' ] );

			/**
			 * select
			 */
			add_filter( 'acf/prepare_field/type=select', [ $this, 'prepare_field_add_class_selector' ] );

			add_filter( 'acf/prepare_field/type=taxonomy', [ $this, 'prepare_field_add_class_selector' ] );

			add_filter( 'acf/prepare_field/type=text', [ $this, 'prepare_field_add_class_selector' ] );

			add_filter( 'acf/prepare_field/type=textarea', [ $this, 'prepare_field_add_class_selector' ] );

			add_filter( 'acf/prepare_field/type=true_false', [ $this, 'prepare_field_add_class_selector' ] );


			//3rd party
			add_filter( 'acf/prepare_field/type=validated_field', [ $this, 'prepare_field_add_class_selector' ] );
		}
	}


	/**
	 * type: button_group
	 *
	 * @param $field
	 *
	 * @date     2024.09.30
	 * @since    2024.10
	 * @verified 2024.09.30
	 */
	function render_field_settings_button_group( $field )
	{
		$this->field_setting_class_selector( $field );
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * type: checkbox
	 *
	 * @param $field
	 *
	 * @since    7.30
	 * @verified 2016.12.09
	 */
	function render_field_settings_checkbox( $field )
	{
		$this->field_setting_preset_choices( $field );
		$this->field_setting_class_selector( $field );
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * type: date_picker
	 *
	 * @param $field
	 *
	 * @since    7.31
	 * @verified 2016.11.12
	 */
	function render_field_settings_date_picker( $field )
	{
		$this->field_setting_class_selector( $field );
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * type: date_time_picker
	 *
	 * @param $field
	 *
	 * @since    7.31
	 * @verified 2016.11.12
	 */
	function render_field_settings_date_time_picker( $field )
	{
		$this->field_setting_class_selector( $field );
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * type: time_picker
	 *
	 * @param $field
	 *
	 * @since    2019.25
	 * @verified 2019.10.22
	 */
	function render_field_settings_time_picker( $field )
	{
		$this->field_setting_class_selector( $field );
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * type: email
	 *
	 * @param $field
	 *
	 * @since    7.30
	 * @verified 2016.11.11
	 */
	function render_field_settings_email( $field )
	{
		$this->field_setting_class_selector( $field );
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * type: message
	 *
	 * @param $field
	 *
	 * @since    7.31
	 * @verified 2016.11.12
	 */
	function render_field_settings_message( $field )
	{
		$this->field_setting_class_selector( $field );
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * type: number
	 *
	 * @param $field
	 *
	 * @since    7.30
	 * @verified 2016.11.11
	 */
	function render_field_settings_number( $field )
	{
		$this->field_setting_class_selector( $field );
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * type: post_object
	 *
	 * @param $field
	 *
	 * @since    7.35
	 * @verified 2016.11.16
	 */
	function render_field_settings_post_object( $field )
	{
		$this->field_setting_class_selector( $field );
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * type: radio
	 *
	 * @param $field
	 *
	 * @since    7.30
	 * @verified 2016.12.09
	 */
	function render_field_settings_radio( $field )
	{
		$this->field_setting_preset_choices( $field );
		$this->field_setting_class_selector( $field );
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * type: repeater
	 *
	 * @param $field
	 *
	 * @since    7.35
	 * @verified 2016.11.16
	 */
	function render_field_settings_repeater( $field )
	{
		$this->field_setting_class_selector( $field );
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * type: user
	 *
	 * @param $field
	 *
	 * @since    2017.34
	 * @verified 2017.04.29
	 */
	function render_field_settings_user( $field )
	{
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * type: select
	 *
	 * @param $field
	 *
	 * @since    7.30
	 * @verified 2016.12.09
	 */
	function render_field_settings_select( $field )
	{
		$this->field_setting_preset_choices( $field );
		$this->field_setting_class_selector( $field );
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * type: select
	 *
	 * @param $field
	 *
	 * @since    2017.34
	 * @verified 2023.09.11
	 */
	function render_field_settings_taxonomy( $field )
	{
		$this->field_setting_class_selector( $field );
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * type: text
	 *
	 * @param $field
	 *
	 * @since    7.29
	 * @verified 2016.11.10
	 */
	function render_field_settings_text( $field )
	{
		$this->field_setting_class_selector( $field );
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * type: textarea
	 *
	 * @param $field
	 *
	 * @since    7.32
	 * @verified 2016.11.14
	 */
	function render_field_settings_textarea( $field )
	{
		$this->field_setting_class_selector( $field );
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * type: true_false
	 *
	 * @param $field
	 *
	 * @since    7.30
	 * @verified 2016.11.11
	 */
	function render_field_settings_true_false( $field )
	{
		$this->field_setting_class_selector( $field );
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * type: validated_field
	 *
	 * @param $field
	 *
	 * @since    7.31
	 * @verified 2016.11.12
	 */
	function render_field_settings_validated_field( $field )
	{
		$this->field_setting_class_selector( $field );
		$this->field_setting_r_n_c( $field );
		$this->field_setting_r_n_c_viewonly( $field );
	}


	/**
	 * Custom Field Settings ::: Custom Field Settings ::: Custom Field Settings ::: Custom Field Settings ::: Custom Field Settings
	 */
	/**
	 * Custom Field Settings ::: Custom Field Settings ::: Custom Field Settings ::: Custom Field Settings ::: Custom Field Settings
	 */
	/**
	 * Custom Field Settings ::: Custom Field Settings ::: Custom Field Settings ::: Custom Field Settings ::: Custom Field Settings
	 */
	/**
	 * Custom Field Settings ::: Custom Field Settings ::: Custom Field Settings ::: Custom Field Settings ::: Custom Field Settings
	 */


	/**
	 * preset_choices ::: preset_choices ::: preset_choices ::: preset_choices
	 */


	/**
	 * preset_choices
	 *
	 * @param $field
	 *
	 * @since    7.51
	 * @verified 2016.12.09
	 */
	function field_setting_preset_choices( $field )
	{
		acf_render_field_setting( $field, [
			'label'        => 'Preset Choices',
			'instructions' => 'You can use a preset instead of setting all your choices',
			'type'         => 'select',
			'name'         => get_cnst( 'preset_choices' ),
			'choices'      => lct_acf_get_pretty_preset_choices(),
			'ui'           => 0,
			'allow_null'   => 1,
			'placeholder'  => 'Select a preset list of choices',
		] );
	}


	/**
	 * Remove the choices field value, since we will be supplying preset choices from another field 'preset_choices'
	 *
	 * @param $field
	 *
	 * @return array
	 * @since    7.51
	 * @verified 2022.09.07
	 */
	function update_field_update_choices( $field )
	{
		if ( ! empty( $field[ get_cnst( 'preset_choices' ) ] ) ) {
			$field['choices'] = [];
		}


		return $field;
	}


	/**
	 * Add the items of preset_choices to $field['choices']
	 *
	 * @param array $field
	 *
	 * @return array
	 * @since    7.29
	 * @verified 2021.04.30
	 */
	function load_field_update_choices( $field )
	{
		if ( lct_acf_is_field_group_editing_page() ) { //Don't load on ACf edit pages
			return $field;
		}


		if (
			( $tmp = get_cnst( 'preset_choices' ) )
			&& isset( $field[ $tmp ] )
			&& ( $preset_choice = $field[ $tmp ] )
		) {
			if ( strpos( $preset_choice, zxzd() ) !== false ) {
				$parts         = explode( zxzd(), $preset_choice );
				$plugin        = $parts[0];
				$preset_choice = $parts[1];


				if ( $class_set = lct_get_later( $plugin . '_acf_public_choices' ) ) {
					$class = $class_set;
				} else {
					$class = $plugin()->acf_public_choices;
				}


				add_filter( 'acf/prepare_field/key=' . $field['key'], [ $class, $preset_choice ] );


			} elseif (
				lct_get_setting( 'force_load_choices' )
				|| (
					isset( $GLOBALS['lct_mu']->action )
					&& strpos( $GLOBALS['lct_mu']->action, 'acf/' ) === 0
					&& strpos( $GLOBALS['lct_mu']->action, '/query' ) !== false
				)
			) {
				add_filter( 'acf/load_field/key=' . $field['key'], [ lct()->acf_public_choices, $preset_choice ] );
			} else {
				add_filter( 'acf/prepare_field/key=' . $field['key'], [ lct()->acf_public_choices, $preset_choice ] );
			}
		}


		return $field;
	}


	/**
	 * Add the items of preset_choices to $field['choices'] for clone fields
	 *
	 * @param array $field
	 * @param array $clone_field
	 *
	 * @return array
	 * @date     2022.10.24
	 * @since    2022.10
	 * @verified 2022.10.24
	 */
	function clone_field_update_choices( $field, $clone_field )
	{
		if (
			! empty( $field['type'] )
			&& in_array( $field['type'], $this->check_when_cloned )
		) {
			$field = $this->load_field_update_choices( $field );
		}

		return $field;
	}


	/**
	 * class_selector ::: class_selector ::: class_selector ::: class_selector
	 */


	/**
	 * class_selector
	 *
	 * @param $field
	 *
	 * @since    7.29
	 * @verified 2016.11.10
	 */
	function field_setting_class_selector( $field )
	{
		acf_render_field_setting( $field, [
			'label'        => 'Class Selector',
			'instructions' => '',
			'type'         => 'select',
			'name'         => get_cnst( 'class_selector' ),
			'choices'      => lct_acf_get_pretty_class_selector(),
			'multiple'     => 1,
			'ui'           => 1,
			'allow_null'   => 1,
			'placeholder'  => 'Select some classes',
		] );
	}


	/**
	 * Add the items of class_selector to the wrapper class
	 *
	 * @param $field
	 *
	 * @return mixed
	 * @since    7.29
	 * @verified 2022.09.29
	 */
	function prepare_field_add_class_selector( $field )
	{
		if ( ! lct_get_later( 'allow_not_function', 'prepare_field_add_class_selector' ) ) {
			if (
				( $tmp = get_cnst( 'class_selector' ) )
				&& ! empty( $field[ $tmp ] )
			) {
				$field['wrapper']['class'] .= ' ' . implode( ' ', $field[ get_cnst( 'class_selector' ) ] );
			}
		}


		return $field;
	}


	/**
	 * roles_n_caps ::: roles_n_caps ::: roles_n_caps ::: roles_n_caps
	 */


	/**
	 * roles_n_caps
	 *
	 * @param $field
	 *
	 * @since    2017.34
	 * @verified 2017.09.18
	 */
	function field_setting_r_n_c( $field )
	{
		if ( lct_acf_get_option_raw( 'enable_acf_field_restrictions' ) ) {
			acf_render_field_setting( $field, [
				'label'        => 'Restrict Access to Roles & Caps',
				'instructions' => 'You can choose which roles & caps have access to this field. Default is they are allowed access, until they are explicitly denied access.',
				'type'         => 'select',
				'name'         => get_cnst( 'roles_n_caps' ),
				'choices'      => lct_acf_get_pretty_roles_n_caps(),
				'multiple'     => 1,
				'ui'           => 1,
				'allow_null'   => 1,
				'placeholder'  => 'Select a roles or cap to allow access',
			] );
		}
	}


	/**
	 * roles_n_caps_viewonly ::: roles_n_caps_viewonly ::: roles_n_caps_viewonly ::: roles_n_caps_viewonly
	 */


	/**
	 * roles_n_caps_viewonly
	 *
	 * @param $field
	 *
	 * @since    2017.34
	 * @verified 2017.09.18
	 */
	function field_setting_r_n_c_viewonly( $field )
	{
		if ( lct_acf_get_option_raw( 'enable_acf_field_restrictions' ) ) {
			acf_render_field_setting( $field, [
				'label'        => 'Restrict View ONLY Access to Roles & Caps',
				'instructions' => 'You can choose which roles & caps have view ONLY access to this field. Default is they are allowed access, until they are explicitly denied access.',
				'type'         => 'select',
				'name'         => get_cnst( 'roles_n_caps_viewonly' ),
				'choices'      => lct_acf_get_pretty_roles_n_caps(),
				'multiple'     => 1,
				'ui'           => 1,
				'allow_null'   => 1,
				'placeholder'  => 'Select a roles or cap to allow view ONLY access',
			] );
		}
	}


	/**
	 * pdf_display ::: pdf_display ::: pdf_display ::: pdf_display
	 */


	/**
	 * pdf_display
	 *
	 * @param $field
	 *
	 * @since    2017.80
	 * @verified 2017.10.02
	 */
	function field_setting_pdf_display( $field )
	{
		acf_render_field_setting( $field, [
			'label'        => 'PDF Display Options',
			'instructions' => '',
			'type'         => 'radio',
			'name'         => get_cnst( 'pdf_display' ),
			'layout'       => 'horizontal',
			'choices'      => [
				''     => 'Default: Both',
				'form' => 'Show on Form Only',
				'pdf'  => 'Show on PDF Only',
				'none' => 'Hide EVERYWHERE',
			],
		] );
	}


	/**
	 * Updates wrapper class of the field to properly follow pdf_display settings
	 * //TODO: cs - Make this it's own visibility condition processor - 9/18/2017 11:19 AM
	 *
	 * @param $field
	 *
	 * @return mixed
	 * @since    2017.80
	 * @verified 2017.11.15
	 */
	function prepare_field_add_pdf_display( $field )
	{
		//Clone Field pre format
		if ( ! empty( $field['_clone'] ) ) {
			$cloned_field = get_field_object( $field['_clone'], lct_get_field_post_id( $field ), false, false );


			if (
				( $tmp = $cloned_field[ get_cnst( 'pdf_display' ) ] )
				&& ! empty( $tmp ) ) {
				$field[ get_cnst( 'pdf_display' ) ] = $cloned_field[ get_cnst( 'pdf_display' ) ];
			}
		}


		if (
			( $tmp = $field[ get_cnst( 'pdf_display' ) ] )
			&& ! empty( $tmp )
		) {
			switch ( $field[ get_cnst( 'pdf_display' ) ] ) {
				case 'form':
					$field['wrapper']['class'] .= ' hide_on_pdf';
					break;


				case 'pdf':
					$field['wrapper']['class'] .= ' show_on_pdf';
					break;


				case 'none':
					$field['wrapper']['class'] .= ' hidden';
					break;


				default:
			}
		}


		return $field;
	}


	/**
	 * reference_only ::: reference_only ::: reference_only ::: reference_only
	 */


	/**
	 * reference_only
	 *
	 * @param $field
	 *
	 * @since    2017.80
	 * @verified 2017.09.19
	 */
	function field_setting_reference_only( $field )
	{
		acf_render_field_setting( $field, [
			'label'        => 'Reference Value Only',
			'instructions' => 'When set to \'yes\' the field can never be edited, only displayed.',
			'type'         => 'true_false',
			'name'         => get_cnst( 'reference_only' ),
			'ui'           => 1,
		] );
	}


	/**
	 * Updates field to only reference the value
	 * //TODO: cs - Add a view only class or something like that - 9/19/2017 12:17 PM
	 *
	 * @param $field
	 *
	 * @return mixed
	 * @since    2017.80
	 * @verified 2017.09.19
	 */
	function prepare_field_reference_only( $field )
	{
		return $field;
	}





	/**
	 * Other Actions ::: Other Actions ::: Other Actions ::: Other Actions ::: Other Actions ::: Other Actions :::
	 */
	/**
	 * Other Actions ::: Other Actions ::: Other Actions ::: Other Actions ::: Other Actions ::: Other Actions :::
	 */
	/**
	 * Other Actions ::: Other Actions ::: Other Actions ::: Other Actions ::: Other Actions ::: Other Actions :::
	 */
	/**
	 * Other Actions ::: Other Actions ::: Other Actions ::: Other Actions ::: Other Actions ::: Other Actions :::
	 */


	/**
	 * This will allow this class to manage the rendering on it's own
	 *
	 * @param $field
	 *
	 * @since    2017.83
	 * @verified 2019.07.16
	 */
	function exclude_field_type( $field )
	{
		if ( ! empty( $field ) ) {
			$this->excluded_field_types[] = $field->name;


			add_filter( 'lct/acf_form_head_display_form/excluded_field_types', [ $this, 'excluded_field_types' ] );
		}
	}


	/**
	 * This will allow this class to manage the rendering on it's own
	 *
	 * @param $excluded_field_types
	 *
	 * @return array
	 * @since    2017.83
	 * @verified 2017.09.28
	 */
	function excluded_field_types( $excluded_field_types )
	{
		if ( ! empty( $this->excluded_field_types ) ) {
			foreach ( $this->excluded_field_types as $excluded_field_type ) {
				$excluded_field_types[] = $excluded_field_type;
			}
		}


		return $excluded_field_types;
	}
}
