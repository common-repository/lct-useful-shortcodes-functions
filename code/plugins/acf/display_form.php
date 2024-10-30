<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.02.09
 */
class lct_acf_display_form
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.02.09
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
	 * @since    2017.11
	 * @verified 2017.09.28
	 */
	function load_hooks()
	{
		//bail early if already ran
		if ( lct_did() ) {
			return;
		}


		/**
		 * Filters
		 */
		add_filter( 'lct/should_set_empty_value', [ $this, 'should_set_empty_value' ], 5 );
	}


	/**
	 * Prep to do a display only version of a form
	 * Place lct_acf_form_head_display_form() directly below: acf_form_head()
	 *
	 * @since    7.3
	 * @verified 2022.01.06
	 */
	function acf_form_head()
	{
		lct_update_setting( 'acf_display_form_active', true );


		add_action( 'acf/input/admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

		add_action( 'lct/acf/display_form/type_true_false', [ $this, 'render_field_hide_if_true_false' ] );
		add_action( 'lct/acf/display_form/type_radio', [ $this, 'render_field_hide_if_yes_no' ] );


		add_filter( 'lct/acf/display_form/type_select/value', [ $this, 'render_select_value_choice' ], 10, 3 );


		$field_types = acf_get_field_types();
		$exclude     = apply_filters( 'lct/acf_form_head_display_form/excluded_field_types', [] );


		foreach ( $field_types as $field_type_name => $field_type_groups ) {
			foreach ( $field_type_groups as $field_type => $name ) {
				//skip some of our field_types
				if ( in_array( $field_type, $exclude ) ) {
					continue;
				}


				remove_all_actions( "acf/render_field/type={$field_type}" );

				add_action( "acf/render_field/type={$field_type}", [ $this, 'render_field' ] );
			}
		}


		/**
		 * @date     0.0
		 * @since    2017.80
		 * @verified 2021.08.30
		 */
		do_action( 'lct/acf_form_head' );
	}


	/**
	 * Render a field value for a display only form
	 *
	 * @param $field
	 *
	 * @since    7.3
	 * @verified 2017.09.28
	 */
	function render_field( $field )
	{
		if ( $post_id = lct_get_later( zxzu( 'acf_display_form_post_id' ) ) ) {
			echo lct_acf_display_form_format_value( $field['value'], $post_id, $field, $this );
		}
	}


	/**
	 * Sets the displayed value to the choice label of the value
	 *
	 * @param mixed    $value
	 * @param array    $field
	 * @param stdClass $class
	 *
	 * @return mixed
	 * @since    7.22
	 * @verified 2017.11.16
	 */
	function render_select_value_choice( $value, $field, $class )
	{
		if (
			( $tmp = $field[ get_cnst( 'class_selector' ) ] )
			&& ! empty( $tmp )
			&& in_array( zxzu( 'select_display_choice_label' ), $field[ get_cnst( 'class_selector' ) ] )
		) {
			if (
				! is_array( $value )
				&& $value
				&& isset( $field['choices'][ $value ] )
			) {
				$value = $field['choices'][ $value ];
			}
		}


		return $value;
	}


	/**
	 * Check if we have a 'hide_if' class_selector set. and if true or false, choose to show or hide the field
	 *
	 * @param $field
	 *
	 * @since    7.22
	 * @verified 2016.11.12
	 */
	function render_field_hide_if_true_false( $field )
	{
		if (
			( $tmp = $field[ get_cnst( 'class_selector' ) ] )
			&& ! empty( $tmp )
			&& (
				in_array( 'hide_if_true', $field[ get_cnst( 'class_selector' ) ] )
				|| in_array( 'hide_if_false', $field[ get_cnst( 'class_selector' ) ] )
			)
		) {
			if (
				$field['value']
				&& in_array( 'hide_if_true', $field[ get_cnst( 'class_selector' ) ] )
			) {
				echo "<style>[data-key=\"{$field['key']}\"]{display: none !important;}</style>";
			} elseif (
				! $field['value']
				&& in_array( 'hide_if_false', $field[ get_cnst( 'class_selector' ) ] )
			) {
				echo "<style>[data-key=\"{$field['key']}\"]{display: none !important;}</style>";
			}
		}
	}


	/**
	 * Check if we have a 'hide_if' class_selector set. and if yes or no, choose to show or hide the field
	 *
	 * @param $field
	 *
	 * @since    7.22
	 * @verified 2016.11.12
	 */
	function render_field_hide_if_yes_no( $field )
	{
		if (
			$field['value']
			&& ( $tmp = $field[ get_cnst( 'class_selector' ) ] )
			&& ! empty( $tmp )
			&& (
				in_array( 'hide_if_yes', $field[ get_cnst( 'class_selector' ) ] )
				|| in_array( 'hide_if_no', $field[ get_cnst( 'class_selector' ) ] )
			)
		) {
			if (
				strtolower( $field['value'] ) == 'yes'
				&& in_array( 'hide_if_yes', $field[ get_cnst( 'class_selector' ) ] )
			) {
				echo "<style>[data-key=\"{$field['key']}\"]{display: none !important;}</style>";
			} elseif (
				strtolower( $field['value'] ) == 'no'
				&& in_array( 'hide_if_no', $field[ get_cnst( 'class_selector' ) ] )
			) {
				echo "<style>[data-key=\"{$field['key']}\"]{display: none !important;}</style>";
			}
		}
	}


	/**
	 * Disable scripts that we do not need just to display the script
	 *
	 * @since    2017.11
	 * @verified 2017.02.09
	 */
	function admin_enqueue_scripts()
	{
		wp_deregister_script( 'acf-input' );
		wp_dequeue_script( 'acf-input' );
		wp_deregister_script( zxzu( 'acf_instant_save' ) );
		wp_dequeue_script( zxzu( 'acf_instant_save' ) );
	}


	/**
	 * Should We allow an EMPTY value
	 *
	 * @param $should_set
	 *
	 * @return bool
	 * @since    2017.83
	 * @verified 2017.09.28
	 */
	function should_set_empty_value( $should_set )
	{
		if (
			! $should_set
			&& lct_is_display_form_or_pdf()
		) {
			$should_set = true;
		}


		return $should_set;
	}
}


/**
 * Direct function call to a function inside the class
 *
 * @since    7.22
 * @verified 2017.02.09
 */
function lct_acf_form_head_display_form()
{
	$class = new lct_acf_display_form( lct_load_class_default_args() );


	$class->acf_form_head();
}


/**
 * Easily call an acf_form() in a display only format
 *
 * @param $args
 *
 * @since    7.3
 * @verified 2017.09.28
 */
function lct_acf_display_form( $args )
{
	$args    = wp_parse_args( $args, [ 'post_id' => 0 ] );
	$post_id = lct_get_post_id( $args['post_id'] );


	lct_update_later( zxzu( 'acf_display_form_post_id' ), $post_id );


	ob_start();
	echo '<div class="' . zxzu( 'acf_display_form' ) . '">';
	acf_form( $args );
	echo '</div>';
	$acf_form = ob_get_clean();


	if ( $acf_form ) {
		//Remove all new lines and tabs, so we can properly search the content for things we want to change
		$acf_form = lct_strip_n_r_t( $acf_form );

		//Add a line break at the beginning of each field, so we don't accidentally regex out a field
		$acf_form = preg_replace( "#<div class=\"acf-field#", "\r\n<div class=\"acf-field", $acf_form );


		//Add clear divs after a row is done
		$find = "#<div(.*?)acf-field(.*?)dompdf_right(.*?)>(.*?)acf-input\">(.*?)</div></div>#";

		preg_match_all( $find, $acf_form, $matches );

		if ( ! empty( $matches[0] ) ) {
			foreach ( $matches[0] as $find ) {
				$replace = $find;
				$replace .= sprintf( '<div class="acf-field acf-field-%s"></div>', get_cnst( 'dompdf_clear_class' ) );

				$acf_form = str_replace( $find, $replace, $acf_form );
			}
		}


		//Add clear divs after a row is done
		$find = "#<div(.*?)acf-field(.*?)dompdf_right(.*?)>(.*?)acf-input\">(.*?)</script></div>#";

		preg_match_all( $find, $acf_form, $matches );

		if ( ! empty( $matches[0] ) ) {
			foreach ( $matches[0] as $find ) {
				$replace = $find;
				$replace .= sprintf( '<div class="acf-field acf-field-%s"></div>', get_cnst( 'dompdf_clear_class' ) );

				$acf_form = str_replace( $find, $replace, $acf_form );
			}
		}


		//let other people have a shot at it.
		$acf_form = apply_filters( 'lct/acf/display_form', $acf_form );
	}


	echo $acf_form;
}
