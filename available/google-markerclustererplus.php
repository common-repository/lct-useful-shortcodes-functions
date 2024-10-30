<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


//call like this: lct_root_include( 'available/google-markerclustererplus.php' );
add_action( 'init', [ 'lct_available_google_mcp', 'init' ], 999999 );


/**
 * @verified 2017.02.20
 */
class lct_available_google_mcp
{
	/**
	 * Run on demand
	 */
	static function init()
	{
		$class = __CLASS__;
		new $class( lct_load_class_default_args() );
	}


	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.02.20
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
	 * @since    2017.13
	 * @verified 2017.02.20
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
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 1999991 );
		}


		//if ( lct_wp_admin_all() ) {}


		if ( lct_wp_admin_non_ajax() ) {
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ], 1999991 );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Enqueue the required tooltip script(s)
	 *
	 * @file_source  markerclusterer https://github.com/googlemaps/v3-utility-library/blob/master/markerclusterer/src/markerclusterer.js
	 * @version      1.0.1
	 * @file_source  markerclustererplus_goog https://github.com/googlemaps/v3-utility-library/blob/master/markerclustererplus/src/markerclusterer.js
	 * @version      2.1.2
	 * @file_source  markerclustererplus https://github.com/mahnunchik/markerclustererplus/blob/master/dist/markerclusterer.min.js
	 * @version      unknown
	 * @last_checked 2017.02.20
	 * @since        0.0
	 * @verified     2017.02.20
	 */
	function enqueue_scripts()
	{
		$file = lct_get_root_url( 'includes/google/maps-utility-library-v3/markerclustererplus.min.js' );


		lct_enqueue_script( zxzu( 'google_mcp' ), $file );
		lct_admin_enqueue_script( zxzu( 'google_mcp' ), $file );
	}
}
