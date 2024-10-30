<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.04.20
 */
class lct_yoast_filter
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
	 * @verified 2017.04.20
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
		add_filter( 'wpseo_opengraph_site_name', [ $this, 'opengraph_site_name' ] );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Disable Yoast SEO's wpseo_opengraph_site_name when the lct setting is checked
	 *
	 * @param $title
	 *
	 * @return bool
	 * @since    1.4.6
	 * @verified 2017.04.20
	 */
	function opengraph_site_name( $title )
	{
		if ( lct_plugin_active( 'acf' ) ) {
			if ( lct_acf_get_option_raw( 'hide_og_site_name' ) ) {
				$title = false;
			}
		}


		return $title;
	}
}
