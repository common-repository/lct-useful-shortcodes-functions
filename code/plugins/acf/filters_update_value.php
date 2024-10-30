<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.02.23
 */
class lct_acf_filters_update_value
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.02.23
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
	 * @since    2017.18
	 * @verified 2017.06.21
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
		 * filters
		 */
		/**
		 * name
		 */
		add_filter( 'acf/update_value/name=' . zxzacf( 'google_map_api' ), [ $this, 'google_map_api' ], 10, 3 );

		/**
		 * type
		 */
		add_filter( 'acf/update_value/type=date_picker', [ $this, 'timezone_adjust' ], 100, 3 );
		add_filter( 'acf/update_value/type=date_time_picker', [ $this, 'timezone_adjust' ], 100, 3 );
		add_filter( 'acf/update_value/type=time_picker', [ $this, 'timezone_adjust' ], 100, 3 );

		add_filter( 'acf/update_value/type=repeater', [ $this, 'delete_option_repeater_cache' ], 999, 3 );

		add_filter( 'acf/load_value/type=date_picker', [ $this, 'timezone_adjust_from_gmt' ], 100, 3 );
		add_filter( 'acf/load_value/type=date_time_picker', [ $this, 'timezone_adjust_from_gmt' ], 100, 3 );
		add_filter( 'acf/load_value/type=time_picker', [ $this, 'timezone_adjust_from_gmt' ], 100, 3 );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		if ( lct_wp_admin_non_ajax() ) {
			/**
			 * filters
			 */
			/**
			 * name
			 */
			add_filter( 'acf/update_value/name=_validate_email', '__return_null', 10, 3 );

			add_filter( 'acf/update_value/name=' . zxzacf( 'lock_site_edits' ), [ $this, 'lock_site_edits' ], 10, 3 );

			add_filter( 'acf/update_value/name=' . zxzacf( 'is_phone_number_international' ), [ $this, 'is_phone_number_international' ], 10, 3 );
		}
	}


	/**
	 * Save the info, so we can update the field later. That way it will for sure not get overwritten
	 *
	 * @param string $selector the field name or key
	 * @param mixed  $value    the value to save in the database
	 * @param mixed  $post_id  the post_id of which the value is saved against
	 *
	 * @since    2017.21
	 * @verified 2017.07.19
	 */
	function update_field_later( $selector, $value, $post_id )
	{
		lct_acf_update_field_later( $selector, $value, $post_id );
	}


	/**
	 * Reset the conditional values that are hidden when we turn this field off
	 *
	 * @param $value
	 * @param $post_id
	 *
	 * @unused    param $field
	 * @return mixed
	 * @since     2017.18
	 * @verified  2017.02.24
	 */
	function lock_site_edits( $value, $post_id )
	{
		if ( ! $value ) {
			$this->update_field_later( zxzacf( 'lock_site_edits_allow' ), '', $post_id );
			$this->update_field_later( zxzacf( 'lock_site_edits_unlock_time' ), '', $post_id );
		}


		return $value;
	}


	/**
	 * Reset the conditional values that are hidden when we turn this field on
	 *
	 * @param $value
	 * @param $post_id
	 *
	 * @unused    param $field
	 * @return mixed
	 * @since     2017.21
	 * @verified  2017.02.24
	 */
	function is_phone_number_international( $value, $post_id )
	{
		if ( $value ) {
			$this->update_field_later( zxzacf( 'phone_number_format' ), '0', $post_id );
			$this->update_field_later( zxzacf( 'phone_number_format::area_code_pre' ), '', $post_id );
			$this->update_field_later( zxzacf( 'phone_number_format::area_code_post' ), '', $post_id );
			$this->update_field_later( zxzacf( 'phone_number_format::number_spacer' ), '', $post_id );
		}


		return $value;
	}


	/**
	 * ACF is not considering time zones, so we will do it for them
	 *
	 * @param $value
	 *
	 * @unused   param $post_id
	 * @unused   param $field
	 * @return mixed
	 * @since    2017.77
	 * @verified 2022.05.25
	 */
	function timezone_adjust( $value )
	{
		if ( function_exists( 'afwp' ) ) {
			return $value;
		}


		if ( $value ) {
			$value = get_gmt_from_date( $value );
		}


		return $value;
	}


	/**
	 * ACF is not considering timezones, so we will do it for them
	 *
	 * @param $value
	 *
	 * @unused   param $post_id
	 * @unused   param $field
	 * @return mixed
	 * @since    2019.1
	 * @verified 2022.05.25
	 */
	function timezone_adjust_from_gmt( $value )
	{
		if ( function_exists( 'afwp' ) ) {
			return $value;
		}


		if ( $value ) {
			$value = get_date_from_gmt( $value );
		}


		return $value;
	}


	/**
	 * Save some time on expensive repeater calls
	 *
	 * @param mixed      $value
	 * @param int|string $post_id
	 * @param array      $field
	 *
	 * @return mixed
	 * @since    2019.3
	 * @verified 2019.02.18
	 */
	function delete_option_repeater_cache( $value, $post_id, $field )
	{
		if ( $post_id !== lct_o() ) {
			return $value;
		}


		$option_repeaters = lct_get_option( 'option_repeaters', [] );


		lct_update_option( 'option_repeaters', array_unique( array_merge( $option_repeaters, [ $field['name'] ] ) ) );


		lct_delete_option( 'option_repeater_' . $field['name'] );
		lct_delete_option( 'option_repeater_' . $field['name'] . ':formatted' );


		return $value;
	}


	/**
	 * Update other options
	 *
	 * @param mixed $value
	 *
	 * @unused   param int   $post_id
	 * @unused   param array $field
	 * @return mixed
	 * @since    2019.18
	 * @verified 2019.07.12
	 */
	function google_map_api( $value )
	{
		if (
			lct_theme_active( 'Avada' )
			&& version_compare( lct_theme_version( 'Avada' ), '5.0', '>=' ) //Avada is v5.0 and newer
		) {
			Avada()->settings->set( 'gmap_api', $value );
		}


		return $value;
	}
}
