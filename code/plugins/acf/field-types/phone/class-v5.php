<?php
/**
 * `{zxzu}phone`: Single word, no spaces. Underscores allowed. e.g. donate_button
 * `Phone Number ({zxza})`: Multiple words, can include spaces, visible when selecting a field type. e.g. Donate Button
 *
 * @since        7.29
 * @verified     2016.11.10
 * @noinspection PhpMissingFieldTypeInspection
 */


//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'acf_field_lct_phone' ) ) :


	class acf_field_lct_phone extends acf_field
	{
		public $name;
		public $label;
		public $category;
		public $defaults;
		public $l10n;

		public $unused_settings;

		public $lct_acf_field_settings;


		/**
		 * __construct
		 * This function will set up the field type data
		 *
		 * @date     5/03/2014
		 * @since    5.0.0
		 * @verified 2017.09.28
		 */
		function __construct()
		{
			/**
			 * name (string) Single word, no spaces. Underscores allowed
			 */
			$this->name = zxzu( 'phone' );

			/**
			 * label (string) Multiple words, can include spaces, visible when selecting a field type
			 */
			$this->label = __( 'Phone Number (lct)', 'TD_LCT' );

			/**
			 * category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
			 */
			$this->category = 'basic';

			/**
			 * defaults (array) Array of default settings which are merged into the field object. These are used later in settings
			 */
			$this->defaults = [];

			/**
			 * l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
			 */
			$this->l10n = [
				'error' => __( 'Error!', 'TD_LCT' ),
			];


			/**
			 * unused_settings (array) Array of settings we will not be using. See: lct_acf_builtin_field_settings() for options
			 */
			$this->unused_settings = [];


			// do not delete!
			parent::__construct();


			/**
			 * Custom
			 */
			//NONE


			/**
			 * Process Settings
			 */
			lct_acf_register_field_type( $this );
		}


		/**
		 * render_field_settings()
		 * Create extra settings for your field. These are visible when editing a field
		 *
		 * @param $field (array) the $field being edited
		 *
		 * @since    3.6
		 * @date     23/01/13
		 * @verified 2017.09.28
		 */
		function render_field_settings( $field )
		{
			if ( class_exists( 'lct_acf_field_settings' ) ) {
				$this->lct_acf_field_settings = new lct_acf_field_settings( lct_load_class_default_args() );


				$this->lct_acf_field_settings->field_setting_class_selector( $field );
				$this->lct_acf_field_settings->field_setting_r_n_c( $field );
				$this->lct_acf_field_settings->field_setting_r_n_c_viewonly( $field );
			}
		}


		/**
		 * render_field()
		 * Create the HTML interface for your field
		 *
		 * @param $field (array) the $field being rendered
		 *
		 * @since    3.6
		 * @date     23/01/13
		 * @verified 2020.09.04
		 */
		function render_field( $field )
		{
			if (
				lct_is_form_enterable()
				&& lct_acf_current_user_can_edit_field( $field )
			) {
				echo "<div class=\"acf-input-wrap\"><input type=\"text\" name=\"" . esc_attr( $field['name'] ) . "\" value=\"" . esc_attr( $field['value'] ) . "\"/></div>";
			} else {
				echo lct_acf_format_value( $field['value'], lct_get_field_post_id( $field ), $field );

				lct_append_later( 'acf_render_field_viewonly_fields', $field['key'] );
			}
		}


		/**
		 * input_admin_enqueue_scripts()
		 * This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
		 * Use this action to add CSS + JavaScript to assist your render_field() action.
		 *
		 * @since 3.6
		 * @date  23/01/13
		 */
		function input_admin_enqueue_scripts() {}


		/**
		 * input_admin_head()
		 * This action is called in the admin_head action on the edit screen where your field is created.
		 * Use this action to add CSS and JavaScript to assist your render_field() action.
		 *
		 * @since 3.6
		 * @date  23/01/13
		 */
		function input_admin_head() {}


		/**
		 * input_form_data()
		 * This function is called once on the 'input' page between the head and footer
		 * There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and
		 * 'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
		 * seen on comments / user edit forms on the front end. This function will always be called, and includes
		 * $args that related to the current screen such as $args['post_id']
		 *
		 * @unused   param $args (array)
		 * @date     6/03/2014
		 * @since    5.0.0
		 * @verified 2017.09.28
		 */
		function input_form_data()
		{
			/**
			 * everytime
			 */
			//echo lct_acf_field_hide( $this->name );


			/**
			 * Front-end ONLY
			 */
			//if ( lct_frontend() ) {}


			/**
			 * Back-end ONLY
			 */
			//if ( lct_wp_admin_non_ajax() ) {}


			/**
			 * Front-end ACF Form ONLY
			 */
			//if ( lct_is_form_only() ) {}


			/**
			 * Any ACF Form
			 */
			//if ( lct_is_form_enterable() ) {}


			/**
			 * Display form or PDF
			 */
			//if ( lct_is_display_form_or_pdf() ) {}


			/**
			 * Display form ONLY
			 */
			//if ( lct_is_display_form() ) {}


			/**
			 * PDF ONLY
			 */
			//if ( lct_is_pdf() ) {}
		}


		/**
		 * input_admin_footer()
		 * This action is called in the admin_footer action on the edit screen where your field is created.
		 * Use this action to add CSS and JavaScript to assist your render_field() action.
		 *
		 * @since 3.6
		 * @date  23/01/13
		 */
		function input_admin_footer() {}


		/**
		 * field_group_admin_enqueue_scripts()
		 * This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
		 * Use this action to add CSS + JavaScript to assist your render_field_options() action.
		 *
		 * @since 3.6
		 * @date  23/01/13
		 */
		function field_group_admin_enqueue_scripts() {}


		/**
		 * field_group_admin_head()
		 * This action is called in the admin_head action on the edit screen where your field is edited.
		 * Use this action to add CSS and JavaScript to assist your render_field_options() action.
		 *
		 * @since    3.6
		 * @date     23/01/13
		 * @verified 2017.09.28
		 */
		function field_group_admin_head()
		{
			echo lct_acf_admin_field_hide( $this->name, array_keys( $this->unused_settings ) );
		}


		/**
		 * load_value()
		 * This filter is applied to the $value after it is loaded from the db
		 *
		 * @param $value (mixed) the value found in the database
		 *
		 * @unused param $post_id (mixed) the $post_id from which the value was loaded
		 * @unused param $field   (array) the field array holding all the field options
		 * @return mixed
		 * @since  3.6
		 * @date   23/01/13
		 */
		function load_value( $value )
		{
			return $value;
		}


		/**
		 * update_value()
		 * This filter is applied to the $value before it is saved in the db
		 *
		 * @param $value   (mixed) the value found in the database
		 * @param $post_id (mixed) the $post_id from which the value was loaded
		 * @param $field   (array) the field array holding all the field options
		 *
		 * @return   string $value
		 * @since    3.6
		 * @date     23/01/13
		 * @verified 2017.09.28
		 */
		function update_value( $value, $post_id, $field )
		{
			if (
				$post_id == lct_o()
				&& $field['name'] == zxzacf( 'phone_number' )
			) {
				$value = lct_strip_phone( $value );
			} elseif ( function_exists( 'lct_format_phone_number' ) ) {
				$value = lct_format_phone_number( $value );
			}


			return $value;
		}


		/**
		 * format_value()
		 * This filter is applied to the $value after it is loaded from the db, and before it is returned to the template
		 *
		 * @param $value (mixed) the value which was loaded from the database
		 *
		 * @unused   param $post_id (mixed) the $post_id from which the value was loaded
		 * @unused   param $field   (array) the field array holding all the field options
		 * @return   string $value (mixed) the modified value
		 * @since    3.6
		 * @date     23/01/13
		 * @verified 2017.09.28
		 */
		function format_value( $value )
		{
			return $value;
		}


		/**
		 * validate_value()
		 * This filter is used to perform validation on the value prior to saving.
		 * All values are validated regardless of the field's required setting. This allows you to validate and return
		 * messages to the user if the value is not correct
		 *
		 * @param $valid (boolean) validation status based on the value and the field's required setting
		 * @param $value (mixed) the $_POST value
		 *
		 * @unused   param $field (array) the field array holding all the field options
		 * @unused   param $input (string) the corresponding input name for $_POST value
		 * @return bool $valid
		 * @date     11/02/2014
		 * @since    5.0.0
		 * @verified 2017.09.28
		 */
		function validate_value( $valid, $value )
		{
			if (
				$value
				&& strlen( lct_strip_phone( $value ) ) != 10
			) {
				$valid = __( 'Phone Number needs to be 10 digits', 'TD_LCT' );
			}


			return $valid;
		}


		/**
		 * delete_value()
		 * This action is fired after a value has been deleted from the db.
		 * Please note that saving a blank value is treated as an update, not a 'delete'
		 *
		 * @param $post_id (mixed) the $post_id from which the value was deleted
		 * @param $key     (string) the $meta_key which the value was deleted
		 *
		 * @date  6/03/2014
		 * @since 5.0.0
		 */
		function delete_value( $post_id, $key ) {}


		/**
		 * load_field()
		 * This filter is applied to the $field after it is loaded from the database
		 *
		 * @param $field (array) the field array holding all the field options
		 *
		 * @return   array $field
		 * @date     23/01/2013
		 * @since    3.6.0
		 * @verified 2017.09.28
		 */
		function load_field( $field )
		{
			if ( class_exists( 'lct_acf_field_settings' ) ) {
				$this->lct_acf_field_settings = new lct_acf_field_settings( lct_load_class_default_args() );


				$field = $this->lct_acf_field_settings->prepare_field_add_class_selector( $field );
			}


			return $field;
		}


		/**
		 * update_field()
		 * This filter is applied to the $field before it is saved to the database
		 *
		 * @param $field (array) the field array holding all the field options
		 *
		 * @return   array $field
		 * @date     23/01/2013
		 * @since    3.6.0
		 * @verified 2017.09.28
		 */
		function update_field( $field )
		{
			return lct_acf_update_field_cleanup( $field, $this->unused_settings );
		}


		/**
		 * delete_field()
		 * This action is fired after a field is deleted from the database
		 *
		 * @param $field (array) the field array holding all the field options
		 *
		 * @date  11/02/2014
		 * @since 5.0.0
		 */
		function delete_field( $field ) {}
	}


	acf_register_field_type( 'acf_field_lct_phone' );


endif; // class_exists check
