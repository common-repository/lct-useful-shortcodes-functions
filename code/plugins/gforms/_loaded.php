<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2019.03.11
 */
class lct_gforms_loaded
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2019.03.11
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
	 * @since    2019.4
	 * @verified 2019.03.11
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


		if ( lct_wp_admin_non_ajax() ) {
			add_action( 'lct/op_main/init', [ $this, 'add_op_main_gforms' ] );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Custom ACF options menu
	 *
	 * @since    7.62
	 * @verified 2017.04.20
	 */
	function add_op_main_gforms()
	{
		acf_add_options_sub_page( [
			'title'      => 'Gravity Forms Settings',
			'menu'       => 'Gravity Forms Settings',
			'slug'       => zxzu( 'acf_op_main_gforms' ),
			'parent'     => zxzu( 'acf_op_main' ),
			'capability' => 'activate_plugins'
		] );
	}
}
