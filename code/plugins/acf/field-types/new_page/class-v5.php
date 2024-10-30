<?php
/**
 * `{zxzu}new_page`: Single word, no spaces. Underscores allowed. e.g. donate_button
 * `New Page`: Multiple words, can include spaces, visible when selecting a field type. e.g. Donate Button
 *
 * @since        7.29
 * @verified     2016.11.10
 * @noinspection PhpMissingFieldTypeInspection
 * @noinspection PhpDocSignatureInspection
 */


//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'acf_field_lct_new_page' ) ) :


	class acf_field_lct_new_page extends acf_field
	{
		public $name;
		public $label;
		public $category;
		public $defaults;
		public $l10n;

		public $unused_settings;


		/**
		 * __construct
		 * This function will set up the field type data
		 *
		 * @type function
		 * @date     5/03/2014
		 * @since    5.0.0
		 * @verified 2017.09.28
		 */
		function __construct()
		{
			/**
			 * name (string) Single word, no spaces. Underscores allowed
			 */
			$this->name = zxzu( 'new_page' );

			/**
			 * label (string) Multiple words, can include spaces, visible when selecting a field type
			 */
			$this->label = __( 'New Page', 'TD_LCT' );

			/**
			 * category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
			 */
			$this->category = 'PDF Layout';

			/**
			 * defaults (array) Array of default settings which are merged into the field object. These are used later in settings
			 */
			$this->defaults = [
				'label' => $this->label,
			];

			/**
			 * l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
			 */
			$this->l10n = [
				'error' => __( 'Error!', 'TD_LCT' ),
			];


			/**
			 * unused_settings (array) Array of settings we will not be using. See: lct_acf_builtin_field_settings() for options
			 */
			$this->unused_settings = [
				'conditional_logic',
				'instructions',
				'label',
				'name',
				'required',
				'wrapper',
			];


			// do not delete!
			parent::__construct();


			/**
			 * Custom
			 */
			set_cnst( 'new_page_class', str_replace( '_', '-', $this->name ) );


			/**
			 * filters
			 */
			add_filter( 'lct/acf/display_form', [ $this, 'display_form' ] );


			/**
			 * Process Settings
			 */
			lct_acf_register_field_type( $this, [ 'exclude_field_type' ] );
		}


		/**
		 * render_field_settings()
		 * Create extra settings for your field. These are visible when editing a field
		 *
		 * @param $field (array) the $field being edited
		 *
		 * @type action
		 * @since 3.6
		 * @date  23/01/13
		 */
		function render_field_settings( $field ) {}


		/**
		 * render_field()
		 * Create the HTML interface for your field
		 *
		 * @unused   param $field (array) the $field being rendered
		 * @type action
		 * @since    3.6
		 * @date     23/01/13
		 * @verified 2017.09.28
		 */
		function render_field() {}


		/**
		 * input_admin_enqueue_scripts()
		 * This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
		 * Use this action to add CSS + JavaScript to assist your render_field() action.
		 *
		 * @type action (admin_enqueue_scripts)
		 * @since 3.6
		 * @date  23/01/13
		 */
		function input_admin_enqueue_scripts() {}


		/**
		 * input_admin_head()
		 * This action is called in the admin_head action on the edit screen where your field is created.
		 * Use this action to add CSS and JavaScript to assist your render_field() action.
		 *
		 * @type action (admin_head)
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
		 * @type function
		 * @date     6/03/2014
		 * @since    5.0.0
		 * @verified 2017.09.28
		 */
		function input_form_data()
		{
			/**
			 * everytime
			 */
			//NONE


			/**
			 * Front-end ONLY
			 */
			//if ( lct_frontend() ) {}


			/**
			 * Back-end ONLY
			 */
			if ( lct_wp_admin_non_ajax() ) {
				echo lct_acf_field_hide( $this->name );
			}


			/**
			 * Front-end ACF Form ONLY
			 */
			if ( lct_is_form_only() ) {
				echo lct_acf_field_hide( $this->name );
			}


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
			if ( lct_is_display_form() ) {
				echo lct_acf_field_hide( $this->name );
			}


			/**
			 * PDF ONLY
			 */
			if ( lct_is_pdf() ) {
				echo '<style>
			' . lct_acf_field_type_class( $this->name ) . '{
				page-break-after: always;
				margin-bottom: 0 !important;
			}
			</style>';
			}
		}


		/**
		 * input_admin_footer()
		 * This action is called in the admin_footer action on the edit screen where your field is created.
		 * Use this action to add CSS and JavaScript to assist your render_field() action.
		 *
		 * @type action (admin_footer)
		 * @since 3.6
		 * @date  23/01/13
		 */
		function input_admin_footer() {}


		/**
		 * field_group_admin_enqueue_scripts()
		 * This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
		 * Use this action to add CSS + JavaScript to assist your render_field_options() action.
		 *
		 * @type action (admin_enqueue_scripts)
		 * @since 3.6
		 * @date  23/01/13
		 */
		function field_group_admin_enqueue_scripts() {}


		/**
		 * field_group_admin_head()
		 * This action is called in the admin_head action on the edit screen where your field is edited.
		 * Use this action to add CSS and JavaScript to assist your render_field_options() action.
		 *
		 * @type action (admin_head)
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
		 * @type filter
		 * @return $value
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
		 * @param $value (mixed) the value found in the database
		 *
		 * @unused param $post_id (mixed) the $post_id from which the value was loaded
		 * @unused param $field   (array) the field array holding all the field options
		 * @type filter
		 * @return $value
		 * @since  3.6
		 * @date   23/01/13
		 */
		function update_value( $value )
		{
			return $value;
		}


		/**
		 * format_value()
		 * This filter is applied to the $value after it is loaded from the db, and before it is returned to the template
		 *
		 * @param $value (mixed) the value which was loaded from the database
		 *
		 * @unused param $post_id (mixed) the $post_id from which the value was loaded
		 * @unused param $field   (array) the field array holding all the field options
		 * @type filter
		 * @return $value (mixed) the modified value
		 * @since  3.6
		 * @date   23/01/13
		 */
		function format_value( $value )
		{
			// bail early if no value
			if ( empty( $value ) ) {
				return $value;
			}


			return $value;
		}


		/**
		 * validate_value()
		 * This filter is used to perform validation on the value prior to saving.
		 * All values are validated regardless of the field's required setting. This allows you to validate and return
		 * messages to the user if the value is not correct
		 *
		 * @param $valid (boolean) validation status based on the value and the field's required setting
		 *
		 * @unused param $value (mixed) the $_POST value
		 * @unused param $field (array) the field array holding all the field options
		 * @unused param $input (string) the corresponding input name for $_POST value
		 * @type filter
		 * @return $valid
		 * @date   11/02/2014
		 * @since  5.0.0
		 */
		function validate_value( $valid )
		{
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
		 * @type action
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
		 * @type filter
		 * @return $field
		 * @date   23/01/2013
		 * @since  3.6.0
		 */
		function load_field( $field )
		{
			return $field;
		}


		/**
		 * update_field()
		 * This filter is applied to the $field before it is saved to the database
		 *
		 * @param $field (array) the field array holding all the field options
		 *
		 * @type filter
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
		 * @type action
		 * @date  11/02/2014
		 * @since 5.0.0
		 */
		function delete_field( $field ) {}


		/**
		 * Update the wrapped for this field type
		 *
		 * @param $acf_form
		 *
		 * @return mixed
		 * @since    7.30
		 * @verified 2017.09.28
		 */
		function display_form( $acf_form )
		{
			$class = 'acf-field-' . get_cnst( 'new_page_class' );
			$find  = sprintf( '#<div(.*?)%s(.*?)>(.*?)<div class="acf-input"><\/div><\/div>#', $class );

			preg_match_all( $find, $acf_form, $matches );


			if ( ! empty( $matches[0] ) ) {
				foreach ( $matches[0] as $find ) {
					if ( strpos( $find, 'hidden-imp' ) !== false ) {
						$replace = '';
					} else {
						$replace = sprintf( '<div class="acf-field %s"></div>', $class );
					}

					$acf_form = str_replace( $find, $replace, $acf_form );
				}
			}


			return $acf_form;
		}
	}


	acf_register_field_type( 'acf_field_lct_new_page' );


endif; // class_exists check
