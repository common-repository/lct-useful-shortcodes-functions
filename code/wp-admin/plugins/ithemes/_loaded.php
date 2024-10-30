<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @verified 2017.07.11
 */
class lct_wp_admin_ithemes_loaded
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.07.11
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
	 * @since    2017.57
	 * @verified 2017.07.11
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


		if ( lct_wp_admin_all() ) {
			add_filter( 'itsec_filter_server_config_file_path', [ $this, 'itsec_filter_server_config_file_path' ], 10, 2 );
		}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * If we are running WP in the /x/ directory let's put the iThemes .htaccess there to, That way it doesn't get in our way in our main .htaccess file.
	 * iThemes likes to take over and destroy things.
	 *
	 * @param $file_path
	 * @param $file
	 *
	 * @return string
	 * @since    4.3.5
	 * @verified 2017.07.11
	 */
	function itsec_filter_server_config_file_path( $file_path, $file )
	{
		$home_path = lct_path_site_wp() . '/';


		if ( strpos( $home_path, '/x/' ) !== false ) {
			$file_path = $home_path . $file;
		}


		return $file_path;
	}
}
