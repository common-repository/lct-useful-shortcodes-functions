<?php
/** @noinspection PhpMissingFieldTypeInspection */

//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @verified 2016.12.05
 */
class lct_acf_form
{
	/**
	 * Start up the class
	 *
	 * @verified 2016.12.05
	 */
	function __construct()
	{
		//Setup WordPress action and filter hooks
		$this->load_hooks();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    7.49
	 * @verified 2016.12.09
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
		/**
		 * actions
		 */
		add_action( 'acf/input/admin_footer', [ $this, 'form_data_post_id_ajax' ] );

		add_action( 'lct/acf_form/before_acf_form', [ $this, 'set_current_form' ], 0 );

		add_action( 'acf/input/form_data', [ $this, 'add_custom_form_data' ] );


		/**
		 * filters
		 */
		add_filter( 'lct/acf_form/post_id', [ $this, 'set_acf_form_post_id_for_instant' ], 15, 2 );

		add_filter( 'lct/acf_form/post_id', [ $this, 'set_acf_form_post_id_for_author_page' ], 16, 2 );


		/**
		 * shortcodes
		 */
		add_shortcode( zxzu( 'acf_form2' ), [ $this, 'form_shortcode' ] );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Set the post_id for instant save fields
	 * They sometimes don't have the post_id set
	 *
	 * @param $post_id
	 * @param $a
	 *
	 * @return int
	 * @since    7.49
	 * @verified 2023.09.14
	 */
	function set_acf_form_post_id_for_instant( $post_id, $a )
	{
		if (
			! $post_id
			&& lct_doing()
			&& ! empty( $_REQUEST['action'] )
			&& $_REQUEST['action'] === 'lct_theme_chunk'
		) {
			$post_id = 'new_post';
		} elseif (
			! $post_id
			&& //only if post_id not set yet
			(
				$a['instant']
				|| //only if instant
				lct_doing() //only if an ajax call
			)
		) {
			$post_id = lct_get_acf_post_id();
		}


		return $post_id;
	}


	/**
	 * Set the post_id for author pages
	 *
	 * @param int|string $post_id
	 *
	 * @unused   param array      $a
	 * @return int|string
	 * @since    2020.3
	 * @verified 2020.01.21
	 */
	function set_acf_form_post_id_for_author_page( $post_id )
	{
		if (
			! $post_id
			&& //only if post_id not set yet
			( $author_id = get_query_var( 'author' ) )
		) {
			$post_id = lct_u( $author_id );
		}


		return $post_id;
	}


	/**
	 * Set acf_data for ajax calls
	 *
	 * @since    7.49
	 * @verified 2020.02.07
	 */
	function form_data_post_id_ajax()
	{
		$data = [];


		if (
			( $post = get_post() )
			&& ! lct_is_wp_error( $post )
		) {
			$data[ lct_get_setting( 'root_post_id' ) ] = $post->ID;
			$data[ lct_get_setting( 'acf_post_id' ) ]  = $post->ID; //Delete This
		} ?>


		<script type="text/javascript">
			lct_custom.acf_data = <?php echo wp_json_encode( $data ); ?>;
		</script>
		<?php
	}


	/**
	 * [lct_acf_form2]
	 * shortcode for matching function
	 *
	 * @att      See: lct_acf_form2() for attribute information
	 *
	 * @param $a
	 *
	 * @return bool|string
	 * @since    7.49
	 * @verified 2023.01.02
	 */
	function form_shortcode( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
		/**
		 * @date     2022.01.07
		 * @since    2022.1
		 * @verified 2022.01.07
		 */
			apply_filters( 'lct/acf_form/shortcode_atts',
				[
					'r'                             => '',
					'id'                            => null,
					'post_id'                       => null,
					'new_post'                      => null,
					'field_groups'                  => null,
					'fields'                        => null,
					'post_title'                    => null,
					'post_content'                  => null,
					'form'                          => false, //Usually true, but we want it to be false for the shortcode
					'form_attributes'               => null,
					'return'                        => null,
					'html_before_fields'            => null,
					'html_after_fields'             => null,
					'submit_value'                  => null,
					'updated_message'               => null,
					'label_placement'               => null,
					'instruction_placement'         => null,
					'field_el'                      => null,
					'uploader'                      => null,
					'honeypot'                      => null,
					'html_updated_message'          => null,
					'html_submit_button'            => null,
					'html_submit_spinner'           => null,
					'kses'                          => null,
					//CUSTOM
					'_form_data_lct'                => null,
					zxzu( 'echo_form' )             => false, //since this is a shortcode we want to set it so false unless the user specifically sets it to true
					zxzu( 'access' )                => null,
					zxzu( 'edit' )                  => null,
					zxzu( 'view' )                  => null,
					zxzu( 'default_value' )         => null,
					zxzu( 'hide_submit' )           => null,
					zxzu( 'form_div' )              => null,
					'new_post_type'                 => null,
					'new_post_status'               => null,
					'form_class'                    => null,
					'form_action'                   => null,
					'form_method'                   => null,
					'form_data'                     => null,
					'instant'                       => null,
					'save_now'                      => null,
					'save_sess'                     => null,
					//CUSTOM - Field atts
					zxzu( 'viewonly' )              => null,
					zxzu( 'roles_n_caps' )          => null,
					zxzu( 'roles_n_caps_viewonly' ) => null,
					zxzu( 'pdf_view' )              => true,
					zxzu( 'pdf_layout' )            => null,
				]
			),
			$a
		);


		if ( $a['instant'] || $a['save_now'] || $a['save_sess'] ) {
			$a['honeypot'] = false;
		}


		/**
		 * just unset all the null, so they get properly set in acf_form()
		 */
		foreach ( $a as $ak => $av ) {
			if ( $av === null ) {
				unset( $a[ $ak ] );
			}
		}


		return lct_acf_form2( $a );
	}


	/**
	 * Set the form that we are currently rendering, so we can reference it when it is needed.
	 *
	 * @param $a
	 *
	 * @since    2019.6
	 * @verified 2019.04.08
	 */
	function set_current_form( $a )
	{
		if ( ! empty( $a['id'] ) ) {
			lct_update_setting( 'acf_current_form', $a['id'] );
		} else {
			lct_update_setting( 'acf_current_form', null );
		}


		if ( ! empty( $a ) ) {
			lct_update_setting( 'acf_current_form_args', $a );
		} else {
			lct_update_setting( 'acf_current_form_args', null );
		}
	}


	/**
	 * Product PDF details
	 *
	 * @param array $args
	 *
	 * @return string
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function process_pdf_fields( $args )
	{
		if ( lct_is_display_form_or_pdf() ) {
			if (
				$args[ zxzu( 'pdf_view' ) ] === false
				|| ! $args[ zxzu( 'pdf_layout' ) ]
			) {
				return '';
			}
		}


		// Vars.
		$r            = [];
		$field_groups = [];
		$fields       = [];


		// Allow form settings to be directly provided.
		if ( is_array( $args ) ) {
			$args = acf()->form_front->validate_form( $args );


			// Otherwise, lookup registered form.
		} else {
			$args = acf()->form_front->get_form( $args );
			if ( ! $args ) {
				return '';
			}
		}


		// Extract vars.
		$post_id = $args['post_id'];
		// Prevent ACF from loading values for "new_post".
		if ( $post_id === 'new_post' ) {
			$post_id = false;
		}


		// Load specific fields.
		if ( $args['fields'] ) {
			// Lookup fields using $strict = false for better compatibility with field names.
			foreach ( $args['fields'] as $selector ) {
				$fields[] = acf_maybe_get_field( $selector, $post_id, false );
			}


			// Load specific field groups.
		} elseif ( $args['field_groups'] ) {
			foreach ( $args['field_groups'] as $selector ) {
				$field_groups[] = acf_get_field_group( $selector );
			}


			// Load fields for the given "new_post" args.
		} elseif ( $args['post_id'] === 'new_post' ) {
			$field_groups = acf_get_field_groups( $args['new_post'] );


			// Load fields for the given "post_id" arg.
		} else {
			$field_groups = acf_get_field_groups( [
				'post_id' => $args['post_id']
			] );
		}


		// load fields from the found field groups.
		if ( $field_groups ) {
			foreach ( $field_groups as $field_group ) {
				$_fields = acf_get_fields( $field_group );


				if ( $_fields ) {
					foreach ( $_fields as $_field ) {
						$fields[] = $_field;
					}
				}
			}
		}


		$fields = apply_filters( 'acf/pre_render_fields', $fields, $post_id );
		// Filter our false results.
		$fields = array_filter( $fields );


		// Loop over and render fields.
		if ( $fields ) {
			foreach ( $fields as $field ) {
				// Load value if not already loaded.
				if ( $field['value'] === null ) {
					$field['value'] = acf_get_value( $post_id, $field );
				}
				if ( $field['value'] ) {
					$field['value'] = acf_format_value( $field['value'], $post_id, $field );
				}


				if (
					(
						lct_is_display_form_or_pdf()
						&& ! $field['value']
					)
					|| (
						! $field['value']
						&& ! lct_is_display_form_or_pdf()
						&& $args[ zxzu( 'pdf_view' ) ] !== 'layout_form'
					)
				) {
					continue;
				}


				if (
					! $field['value']
					&& $args[ zxzu( 'pdf_view' ) ] === 'layout_form'
				) {
					$field['value'] = '{' . $field['label'] . '}';
				}


				$fr              = [
					'{value}' => $field['value'],
					'{label}' => $field['label'],
				];
				$fnr             = lct_create_find_and_replace_arrays( $fr );
				$this_field_html = str_replace( $fnr['find'], $fnr['replace'], $args[ zxzu( 'pdf_layout' ) ] );


				if (
					! lct_is_display_form_or_pdf()
					&& $args[ zxzu( 'pdf_view' ) ] === 'layout_form'
				) {
					$this_field_html = sprintf( '<strong>%s</strong>', $this_field_html );
				}


				$r[] = $this_field_html;
			}
		}


		return implode( ', ', $r );
	}


	/**
	 * Print our custom form_data into the acf-form-data element
	 *
	 * @param array $data The form data.
	 *
	 * @date     2022.01.07
	 * @since    2022.1
	 * @verified 2023.12.14
	 */
	function add_custom_form_data( $data )
	{
		$prefix       = '_lct';
		$data_prefix  = '_form_data' . $prefix;
		$input_prefix = $prefix . '_';


		if (
			isset( $data['form'] )
			&& ( $form_arr = acf_decrypt( $data['form'] ) )
			&& ! empty( $form_arr )
			&& ( $form_arr = afwp_acf_maybe_json_decode( $form_arr ) )
			&& isset( $form_arr[ $data_prefix ] )
			&& ( $data = $form_arr[ $data_prefix ] )
			&& is_array( $data )
		) {
			foreach ( $data as $name => $value ) {
				acf_hidden_input(
					[
						'id'    => $input_prefix . $name,
						'value' => $value,
						'name'  => $input_prefix . $name,
					]
				);
			}
		}
	}
}
