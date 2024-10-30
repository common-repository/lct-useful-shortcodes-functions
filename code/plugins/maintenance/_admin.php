<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @verified 2017.04.20
 */
class lct_maintenance_admin
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.04.20
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
	 * @verified 2017.06.01
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
		add_action( 'init', [ $this, 'maintenance_Avada_fix' ] );

		add_action( 'pre_kses', [ $this, 'pre_kses' ], 10, 3 );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Fixes the broken maintenance page for Avada sites
	 *
	 * @since    4.3.6
	 * @verified 2017.04.20
	 */
	function maintenance_Avada_fix()
	{
		$maintenance_options = get_option( 'maintenance_options' );


		if ( $maintenance_options['state'] ) {
			if ( ! function_exists( 'is_bbpress' ) ) {
				function is_bbpress()
				{
					return false;
				}
			}

			if ( ! function_exists( 'is_buddypress' ) ) {
				function is_buddypress()
				{
					return false;
				}
			}
		}
	}


	/**
	 * Run current_year shortcode if it is present
	 *
	 * @param $string
	 *
	 * @unused   param $allowed_html
	 * @unused   param $allowed_protocols
	 * @return string
	 * @since    2017.40
	 * @verified 2017.06.01
	 */
	function pre_kses( $string )
	{
		if (
			! is_admin()
			&& has_shortcode( $string, zxzu( 'current_year' ) )
		) {
			$string = do_shortcode( $string );
		}


		return $string;
	}
}
