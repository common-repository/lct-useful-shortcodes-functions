<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @verified 2017.02.24
 */
class lct_acf_filters_load_value
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.02.24
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
	 * @since    2017.21
	 * @verified 2017.02.24
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


		if ( lct_frontend() ) {
			add_filter( 'acf/load_value/name=' . zxzacf( 'state' ), [ $this, 'state' ] );

			add_filter( 'acf/load_value/name=' . zxzacf( 'zip' ), [ $this, 'zip' ] );

			add_filter( 'acf/load_value/name=' . zxzacf( 'phone_number' ), [ $this, 'phone_number' ] );

			add_filter( 'acf/load_value/name=' . zxzacf( 'fax_number' ), [ $this, 'fax_number' ] );
		}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Get the international info if needed
	 *
	 * @param $value
	 *
	 * @unused   param $post_id
	 * @unused   param $field
	 * @return array
	 * @since    2017.21
	 * @verified 2017.03.22
	 */
	function state( $value )
	{
		if (
			lct_acf_get_option_raw( 'is_address_international' )
			&& lct_acf_get_field_option( 'international_state' )
		) {
			$value = lct_acf_get_field_option( 'international_state' );
		}


		return $value;
	}


	/**
	 * Get the international info if needed
	 *
	 * @param $value
	 *
	 * @unused   param $post_id
	 * @unused   param $field
	 * @return array
	 * @since    2017.21
	 * @verified 2017.03.22
	 */
	function zip( $value )
	{
		if (
			lct_acf_get_option_raw( 'is_address_international' )
			&& lct_acf_get_field_option( 'international_zip' )
		) {
			$value = lct_acf_get_field_option( 'international_zip' );
		}


		return $value;
	}


	/**
	 * Get the international info if needed
	 *
	 * @param $value
	 *
	 * @unused   param $post_id
	 * @unused   param $field
	 * @return array
	 * @since    2017.21
	 * @verified 2017.03.22
	 */
	function phone_number( $value )
	{
		if (
			lct_acf_get_option_raw( 'is_phone_number_international' )
			&& lct_acf_get_field_option( 'international_phone_number' )
		) {
			$value = lct_acf_get_field_option( 'international_phone_number' );
		}


		return $value;
	}


	/**
	 * Get the international info if needed
	 *
	 * @param $value
	 *
	 * @unused   param $post_id
	 * @unused   param $field
	 * @return array
	 * @since    2017.22
	 * @verified 2017.03.22
	 */
	function fax_number( $value )
	{
		if (
			lct_acf_get_option_raw( 'is_fax_number_international' )
			&& lct_acf_get_field_option( 'international_fax_number' )
		) {
			$value = lct_acf_get_field_option( 'international_fax_number' );
		}


		return $value;
	}
}
