<?php
/**
 * `{zxzu}column_end`: Single word, no spaces. Underscores allowed. e.g. donate_button
 * `Column End`: Multiple words, can include spaces, visible when selecting a field type. e.g. Donate Button
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


if ( ! class_exists( 'acf_field_lct_column_end' ) ) :


	class acf_field_lct_column_end extends acf_field
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
		 * @verified 2017.11.10
		 */
		function __construct()
		{
			lct_acf_register_field_setting( 'column_end_type' );
			lct_acf_register_field_setting( 'column_break_width' );


			/**
			 * name (string) Single word, no spaces. Underscores allowed
			 */
			$this->name = zxzu( 'column_end' );

			/**
			 * label (string) Multiple words, can include spaces, visible when selecting a field type
			 */
			$this->label = __( 'Column End', 'TD_LCT' );

			/**
			 * category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
			 */
			$this->category = 'PDF Layout';

			/**
			 * defaults (array) Array of default settings which are merged into the field object. These are used later in settings
			 */
			$this->defaults = [
				get_cnst( 'column_end_type' )    => 'break_in_row',
				get_cnst( 'column_break_width' ) => '',
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
		 * @since    3.6
		 * @date     23/01/13
		 * @verified 2016.11.11
		 */
		function render_field_settings( $field )
		{
			acf_render_field_setting( $field, [
				'label'        => 'End Type',
				'instructions' => '',
				'type'         => 'select',
				'name'         => get_cnst( 'column_end_type' ),
				'choices'      => lct_acf_get_pretty_column_end_type(),
				'multiple'     => 0,
				'ui'           => 0,
				'allow_null'   => 1,
				'required'     => 1,
				'placeholder'  => 'Select an end type',
			] );


			acf_render_field_setting( $field, [
				'label'        => 'Column Width',
				'instructions' => 'You only need to set this if the end type is "Break in the row"',
				'type'         => 'select',
				'name'         => get_cnst( 'column_break_width' ),
				'choices'      => lct_acf_get_pretty_column_start_width(),
				'multiple'     => 0,
				'ui'           => 0,
				'allow_null'   => 1,
				'required'     => 1,
				'placeholder'  => 'Select a width',
			] );
		}


		/**
		 * render_field()
		 * Create the HTML interface for your field
		 *
		 * @param $field (array) the $field being rendered
		 *
		 * @type action
		 * @since    3.6
		 * @date     23/01/13
		 * @verified 2017.09.28
		 */
		function render_field( $field )
		{
			if ( $field[ get_cnst( 'column_end_type' ) ] ) {
				$end_type = $field[ get_cnst( 'column_end_type' ) ];
			} else {
				$end_type = $this->defaults[ get_cnst( 'column_end_type' ) ];
			}

			if ( $field[ get_cnst( 'column_break_width' ) ] ) {
				$width = $field[ get_cnst( 'column_break_width' ) ];
			} else {
				$width = $this->defaults[ get_cnst( 'column_break_width' ) ];
			}


			$data = [
				'data-' . get_cnst( 'column_end_type' )    => $end_type,
				'data-' . get_cnst( 'column_break_width' ) => $width,
			];
			$data = lct_return( $data, ' ', '=', true );


			echo sprintf( '<span class="data-%s" %s style="display: none;"></span>', $this->name, $data );
		}


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
			if ( lct_frontend() ) {
				echo lct_acf_field_hide( $this->name );
			}


			/**
			 * Back-end ONLY
			 */
			if ( lct_wp_admin_non_ajax() ) {
				echo lct_acf_field_hide( $this->name );
			}


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
		 * @verified 2017.09.29
		 */
		function update_field( $field )
		{
			$field = lct_acf_update_field_cleanup( $field, $this->unused_settings );


			if ( $field[ get_cnst( 'column_end_type' ) ] == 'break_in_row' ) {
				$width = ' ' . lct_acf_get_column_start_width_label( $field[ get_cnst( 'column_break_width' ) ] );
			} else {
				$width                                     = '';
				$field[ get_cnst( 'column_break_width' ) ] = $this->defaults[ get_cnst( 'column_break_width' ) ];
			}


			$field['label'] = 'Column: ' . lct_acf_get_column_end_type_label( $field[ get_cnst( 'column_end_type' ) ] ) . $width;


			return $field;
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
		 * @verified 2017.11.10
		 */
		function display_form( $acf_form )
		{
			$find = '#<div(.*?)acf-field-' . zxza() . '-column-end(.*?)>(.*?)data-' . $this->name . '"(.*?)></span></div></div>#';

			preg_match_all( $find, $acf_form, $matches );


			if ( ! empty( $matches[0] ) ) {
				foreach ( $matches[0] as $match_key => $find ) {
					$field_data = shortcode_parse_atts( trim( $matches[4][ $match_key ] ) );
					$end_type   = $field_data[ 'data-' . get_cnst( 'column_end_type' ) ];
					$width      = $field_data[ 'data-' . get_cnst( 'column_break_width' ) ];


					$replace = "</div>";

					if ( $end_type == 'break_in_row' ) {
						$replace .= "<div class=\"acf-field acf-field-" . zxza() . "-column-start\" style=\"width: {$width}%;\">";
					} else {
						$replace .= sprintf( '</div><div class="acf-field acf-field-%s"></div>', get_cnst( 'dompdf_clear_class' ) );
					}

					$acf_form = str_replace( $find, $replace, $acf_form );
				}
			}


			//hide if conditionally hidden
			$find = sprintf(
				'#<div %s>(.*?)data-%s"(.*?)>(.*?)<\/span><script(.*?)>(.*?)<\/script><\/div><\/div>#',
				'(.*?)acf-field-' . zxza() . '-column-end(.*?)',
				$this->name
			);

			preg_match_all( $find, $acf_form, $matches );


			if ( ! empty( $matches[0] ) ) {
				foreach ( $matches[0] as $match_key => $find ) {
					$field_data = shortcode_parse_atts( trim( $matches[4][ $match_key ] ) );
					$end_type   = $field_data[ 'data-' . get_cnst( 'column_end_type' ) ];


					$replace = "</div>";

					if ( $end_type == 'break_in_row' ) {
						$replace = '';
					} else {
						$replace .= sprintf( '</div><div class="acf-field acf-field-%s"></div>', get_cnst( 'dompdf_clear_class' ) );
					}

					$acf_form = str_replace( $find, $replace, $acf_form );
				}
			}


			return $acf_form;
		}
	}


	acf_register_field_type( 'acf_field_lct_column_end' );


endif; // class_exists check
