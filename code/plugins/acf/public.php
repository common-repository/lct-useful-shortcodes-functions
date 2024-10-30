<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2016.11.28
 */
class lct_acf_public
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2016.12.03
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
	 * @since    7.36
	 * @verified 2016.12.03
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


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Deprecated: Add WordPress Post Types to choices
	 *
	 * @param $field
	 *
	 * @return mixed
	 * @deprecated 2018.32
	 * @since      7.50
	 * @verified   2018.03.20
	 */
	function get_pretty_post_types( $field )
	{
		add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
		_deprecated_function( __FUNCTION__, '2018.32', 'lct_acf_public_choices{}pretty_wp_post_types()' );
		remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


		return lct()->acf_public_choices->pretty_wp_post_types( $field );
	}


	/**
	 * Deprecated: Add WordPress Post Types to choices
	 *
	 * @param $field
	 *
	 * @return mixed
	 * @deprecated 2018.32
	 * @since      7.36
	 * @verified   2018.03.20
	 */
	function acf_get_post_types( $field )
	{
		add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
		_deprecated_function( __FUNCTION__, '2018.32', 'lct_acf_public_choices{}pretty_wp_post_types()' );
		remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


		return lct()->acf_public_choices->pretty_wp_post_types( $field );
	}


	/**
	 * Deprecated: Add WordPress Taxonomies to choices
	 *
	 * @param $field
	 *
	 * @return mixed
	 * @deprecated 2018.32
	 * @since      7.50
	 * @verified   2018.03.20
	 */
	function get_pretty_taxonomies( $field )
	{
		add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
		_deprecated_function( __FUNCTION__, '2018.32', 'lct_acf_public_choices{}pretty_wp_taxonomies()' );
		remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


		return lct()->acf_public_choices->pretty_wp_taxonomies( $field );
	}


	/**
	 * Deprecated: Add WordPress Taxonomies to choices
	 *
	 * @param $field
	 *
	 * @return mixed
	 * @deprecated 2018.32
	 * @since      7.31
	 * @verified   2018.03.20
	 */
	function acf_get_taxonomies( $field )
	{
		add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
		_deprecated_function( __FUNCTION__, '2018.32', 'lct_acf_public_choices{}pretty_wp_taxonomies()' );
		remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


		return lct()->acf_public_choices->pretty_wp_taxonomies( $field );
	}


	/**
	 * Moved to: acf_public_choices{}
	 * Alias to 'exhaustive_acf_field_groups_list'
	 *
	 * @param $field
	 *
	 * @return mixed
	 * @since    7.26
	 * @verified 2017.05.09
	 */
	function acf_field_group_list( $field )
	{
		$class = new lct_acf_public_choices( lct_load_class_default_args() );


		return $class->exhaustive_acf_field_groups_list( $field );
	}


	/**
	 * Cleanup the case of a name field
	 * Call:
	 * add_filter( 'acf/update_value/name=' . zxzacf( 'field' ), [ lct()->acf_public, 'update_name_case' ], 9, 3 );
	 * Best to run it before any other update_value filters
	 *
	 * @param $value
	 * @param $post_id
	 * @param $field
	 *
	 * @return bool|mixed|null
	 * @since    7.3
	 * @verified 2016.09.29
	 */
	function update_name_case(
		$value,
		/** @noinspection PhpUnusedParameterInspection */
		$post_id,
		/** @noinspection PhpUnusedParameterInspection */
		$field
	) {
		if ( $value ) {
			$value = ucwords( strtolower( $value ) );
		}


		return $value;
	}


	/**
	 * Cleanup the case of an email field
	 * Call:
	 * add_filter( 'acf/update_value/name=' . zxzacf( 'field' ), [ lct()->acf_public, 'update_email_case' ], 9, 3 );
	 * Best to run it before any other update_value filters
	 *
	 * @param $value
	 * @param $post_id
	 * @param $field
	 *
	 * @return bool|mixed|null
	 * @since    7.3
	 * @verified 2016.09.29
	 */
	function update_email_case(
		$value,
		/** @noinspection PhpUnusedParameterInspection */
		$post_id,
		/** @noinspection PhpUnusedParameterInspection */
		$field
	) {
		if ( $value ) {
			$value = strtolower( $value );
		}


		return $value;
	}


	/**
	 * hide the whole field if the value is not set
	 * Call:
	 * add_filter( 'acf/prepare_field/name=' . zxzacf( 'field' ), [ lct()->acf_public, 'hide_if_empty' ], 5 );
	 *
	 * @param $field
	 *
	 * @return mixed
	 * @since    7.35
	 * @verified 2016.11.16
	 */
	function hide_if_empty( $field )
	{
		if (
			( $tmp = $field[ get_cnst( 'class_selector' ) ] )
			&& ! empty( $tmp )
			&& ! $field['required']
		) {
			global $post;

			$hide_value = false;


			//Only hide it if the post_id is set and the value is not
			if (
				! empty( $post )
				&& ! get_field( $field['key'], $post->ID )
			) {
				$hide_value = true;
			}


			if ( $hide_value ) {
				$field['wrapper']['class'] .= ' hidden';
			}
		}


		return $field;
	}


	/**
	 * check the email to see if a user already exists with that email
	 * Call:
	 * add_filter( 'acf/validate_value/name=' . zxzacf( 'field' ), [ lct()->acf_public, 'unique_user_email' ], 10, 4 );
	 *
	 * @param $valid
	 * @param $value
	 * @param $field
	 * @param $input
	 *
	 * @return mixed
	 * @since    7.35
	 * @verified 2016.11.17
	 */
	function unique_user_email(
		$valid,
		$value,
		$field,
		/** @noinspection PhpUnusedParameterInspection */
		$input
	) {
		if (
			$valid
			&& $value
		) {
			global $post;

			$post_id = false;

			if ( $_POST['post_ID'] ) {
				$post_id = $_POST['post_ID'];
			} elseif ( $post ) {
				$post_id = $post->ID;
			}

			$current_value = get_field( $field['key'], $post_id );


			//only check the DB if the email entered is different from the entry in the DB
			if ( $current_value != $value ) {
				//invalid if email exists
				if ( email_exists( $value ) ) {
					$label = strtolower( $field['label'] );


					$valid = "This {$label} is already being used in the system. Please enter a different one.";
				}
			}
		}


		return $valid;
	}


	/**
	 * load value as dollar amount
	 * Call:
	 * add_filter( 'acf/format_value/name=' . zxzacf( 'field' ), [ lct()->acf_public, 'load_dollar_amount' ] );
	 *
	 * @param $value
	 * @param $post_id
	 * @param $field
	 *
	 * @return mixed
	 * @since    7.49
	 * @verified 2017.01.04
	 */
	function load_dollar_amount(
		$value,
		/** @noinspection PhpUnusedParameterInspection */
		$post_id,
		/** @noinspection PhpUnusedParameterInspection */
		$field
	) {
		return lct_get_dollar( $value );
	}
}
