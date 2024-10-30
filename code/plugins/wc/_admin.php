<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2016.12.30
 */
class lct_wc_admin
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2016.12.15
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
	 * @since    7.62
	 * @verified 2016.12.30
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
		add_action( 'woocommerce_save_account_details', [ $this, 'wc_save_account_details' ] );


		/**
		 * filters
		 */
		add_filter( 'woocommerce_min_password_strength', [ $this, 'wc_min_password_strength' ] );

		add_filter( 'woocommerce_get_image_size_single', [ $this, 'update_image_size' ] );
		add_filter( 'woocommerce_get_image_size_gallery_thumbnail', [ $this, 'update_image_size' ] );
		add_filter( 'woocommerce_get_image_size_thumbnail', [ $this, 'update_image_size' ] );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Turn off the password check
	 *
	 * @param $strength
	 *
	 * @return int
	 * @since    7.35
	 * @verified 2016.11.17
	 */
	function wc_min_password_strength( $strength )
	{
		if ( 1 == 1 ) {
			$strength = 0;
		}


		return $strength;
	}


	/**
	 * Update the nickname & display name when we change the first or last name
	 *
	 * @param $user_id
	 *
	 * @since    7.35
	 * @verified 2016.11.17
	 */
	function wc_save_account_details( $user_id )
	{
		$user = get_userdata( $user_id );


		$user->display_name = $user->first_name . ' ' . $user->last_name;
		$user->nickname     = $user->first_name . ' ' . $user->last_name;

		wp_update_user( $user );
	}


	/**
	 * Update this cause WC loads too late
	 *
	 * @param $size
	 *
	 * @return mixed
	 * @since    2018.64
	 * @verified 2018.09.28
	 */
	function update_image_size( $size )
	{
		$current_size       = str_replace( 'get_image_size_', '', current_filter() );
		$custom_image_sizes = get_option( 'custom_image_sizes', [] );


		if ( ! empty( $custom_image_sizes[ $current_size ]['custom'] ) ) {
			if ( isset( $custom_image_sizes[ $current_size ]['w'] ) ) {
				$size['width'] = absint( $custom_image_sizes[ $current_size ]['w'] );
			}


			if ( isset( $custom_image_sizes[ $current_size ]['h'] ) ) {
				$size['height'] = absint( $custom_image_sizes[ $current_size ]['h'] );
			}


			if ( ! empty( $custom_image_sizes[ $current_size ]['c'] ) ) {
				$size['crop'] = true;
			} else {
				$size['crop'] = false;
			}
		}


		return $size;
	}
}
