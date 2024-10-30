<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.06.26
 */
class lct_q2w3_admin
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.06.26
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
	 * @verified 2017.06.26
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
			add_action( 'init', [ $this, 'q2w3_fixed_widget_js_override' ], 100 );
		}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Override the JS files of the q2w3 plugin
	 * Crappy way to get what we want
	 *
	 * @since    6.6
	 * @verified 2019.02.11
	 */
	function q2w3_fixed_widget_js_override()
	{
		//We may want to enable this sometimes
		if ( defined( 'ENABLE_Q2W3_JS_OVERRIDE' ) ) {
			$file = 'q2w3-fixed-widget';


			wp_deregister_script( $file );

			lct_enqueue_script( $file, lct_get_root_url( 'assets/js/plugins/q2w3/' . $file . '.min.js' ), true, [], lct_plugin_version( 'q2w3' ), true );
		}
	}
}
