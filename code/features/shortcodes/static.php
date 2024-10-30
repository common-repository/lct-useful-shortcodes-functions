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
class lct_features_shortcodes_static
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
		add_shortcode( 'up_path', [ $this, 'function_passthru' ] );
		add_shortcode( 'path_up', [ $this, 'function_passthru' ] );

		add_shortcode( 'path_site', [ $this, 'function_passthru' ] );

		add_shortcode( 'path_site_wp', [ $this, 'function_passthru' ] );

		add_shortcode( 'path_theme', [ $this, 'function_passthru' ] );

		add_shortcode( 'path_plugin', [ $this, 'function_passthru' ] );

		add_shortcode( 'path_theme_parent', [ $this, 'function_passthru' ] );


		add_shortcode( 'up', [ $this, 'function_passthru' ] );
		add_shortcode( 'url_up', [ $this, 'function_passthru' ] );

		add_shortcode( 'url_site', [ $this, 'function_passthru' ] );

		add_shortcode( 'url_root_site', [ $this, 'function_passthru' ] );

		add_shortcode( 'url_site_wp', [ $this, 'function_passthru' ] );

		add_shortcode( 'url_theme', [ $this, 'function_passthru' ] );

		add_shortcode( 'url_plugin', [ $this, 'function_passthru' ] );

		add_shortcode( 'url_theme_parent', [ $this, 'function_passthru' ] );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Get the site's upload directory URL
	 *
	 * @return mixed
	 * @since    0.0
	 * @verified 2018.01.30
	 */
	function function_passthru()
	{
		$caller   = debug_backtrace( true, 1 );
		$function = $caller[0]['args'][2];


		if ( function_exists( $function = zxzu( $function ) ) ) {
			return $function();
		} elseif ( function_exists( $function ) ) {
			return $function();
		}


		return false;
	}
}
