<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


//call like this: lct_root_include( 'available/map_icons.php' );
add_action( 'init', [ 'lct_available_map_icons', 'init' ], 999999 );


/**
 * @verified 2017.02.20
 */
class lct_available_map_icons
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
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 1999991 );
		}


		//if ( lct_wp_admin_all() ) {}


		if ( lct_wp_admin_non_ajax() ) {
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ], 1999991 );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Enqueue the required tooltip style(s)
	 */
	function enqueue_styles()
	{
		$file = lct_get_root_url( 'includes/map_icons/css/map-icons.min.css' );


		lct_enqueue_style( zxzu( 'map_icons' ), $file );
		lct_admin_enqueue_style( zxzu( 'map_icons' ), $file );


		if ( file_exists( lct_path_theme_parent() . '/assets/fonts/fontawesome/font-awesome.css' ) ) {
			wp_enqueue_style( 'fontawesome', lct_url_theme_parent() . '/assets/fonts/fontawesome/font-awesome.css', [], lct_get_setting( 'version' ) );
		} else {
			wp_enqueue_style( 'fontawesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css', [], lct_get_setting( 'version' ) );
		}
	}


	/**
	 * Enqueue the required tooltip script(s)
	 */
	function enqueue_scripts()
	{
		$file = lct_get_root_url( 'includes/map_icons/js/map-icons.min.js' );


		lct_enqueue_script( zxzu( 'map_icons' ), $file );
		lct_admin_enqueue_script( zxzu( 'map_icons' ), $file );
	}
}
