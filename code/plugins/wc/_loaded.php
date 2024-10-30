<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.04.24
 */
class lct_wc_loaded
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.04.24
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
	 * @since    2017.33
	 * @verified 2018.09.27
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
		add_action( 'init', [ $this, 'remove_image_size' ], 11 );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		if ( lct_wp_admin_non_ajax() ) {
			add_action( 'lct/op_main/init', [ $this, 'add_op_main_wc' ] );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Remove image sizes
	 *
	 * @since    7.24
	 * @verified 2018.09.27
	 */
	function remove_image_size()
	{
		if (
			lct_plugin_active( 'acf' )
			&& ( $sizes = lct_acf_get_option_raw( 'wc::disable_image_sizes' ) )
			&& ! empty( $sizes )
		) {
			foreach ( $sizes as $size ) {
				remove_image_size( $size );
			}
		}
	}


	/**
	 * Custom ACF options menu
	 *
	 * @since    7.62
	 * @verified 2016.12.30
	 */
	function add_op_main_wc()
	{
		acf_add_options_sub_page( [
			'title'      => 'WooCommerce Settings',
			'menu'       => 'WooCommerce Settings',
			'slug'       => zxzu( 'acf_op_main_wc' ),
			'parent'     => zxzu( 'acf_op_main' ),
			'capability' => 'activate_plugins'
		] );
	}
}
