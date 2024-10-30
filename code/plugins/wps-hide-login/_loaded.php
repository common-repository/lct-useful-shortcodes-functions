<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2019.08.21
 */
class lct_wps_hide_login_loaded
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2019.08.21
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
	 * @since    2019.23
	 * @verified 2019.08.21
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
		if ( 1 === 2 ) { //We only need this if wps uses site_url()
			add_filter( 'site_url', [ $this, 'site_url' ], 5, 4 );

			add_filter( 'network_site_url', [ $this, 'network_site_url' ], 5, 3 );

			add_filter( 'wp_redirect', [ $this, 'wp_redirect' ], 5, 2 );
		}


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		if ( lct_wp_admin_non_ajax() ) {
			add_action( 'admin_init', [ $this, 'set_login' ] );
		}


		//if ( lct_ajax_only() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * @param string $url
	 * @param string $path
	 * @param string $scheme
	 *
	 * @unused   param int    $blog_id
	 * @return string
	 * @since    2019.23
	 * @verified 2019.08.21
	 */
	function site_url( $url, $path, $scheme )
	{
		return $this->filter_wp_login_php( $url, $scheme, $path );
	}


	/**
	 * @param string $url
	 * @param string $path
	 * @param string $scheme
	 *
	 * @return string
	 * @since    2019.23
	 * @verified 2019.08.21
	 */
	function network_site_url( $url, $path, $scheme )
	{
		return $this->filter_wp_login_php( $url, $scheme, $path );
	}


	/**
	 * @param string $location
	 *
	 * @unused   param int    $status
	 * @return string
	 * @since    2019.23
	 * @verified 2019.08.21
	 */
	function wp_redirect( $location )
	{
		if ( strpos( $location, 'https://wordpress.com/wp-login.php' ) !== false ) {
			return $location;
		}


		return $this->filter_wp_login_php( $location );
	}


	/**
	 * Allow the wp-admin login to be on the home_url and not the site_url.
	 * most the time these are the same, but not always
	 *
	 * @param string $url
	 * @param string $scheme
	 * @param string $path
	 *
	 * @return string
	 * @since    2019.23
	 * @verified 2019.08.21
	 */
	function filter_wp_login_php( $url, $scheme = null, $path = null )
	{
		if (
			! is_user_logged_in()
			&& $url
			&& get_option( 'whl_page' )
			&& $path === get_option( 'whl_page' )
			&& ( $site_url = site_url( '', 'relative' ) )
			&& strpos( $url, $site_url ) === 0
			&& ( $new_url = str_replace( '/', '\/', $site_url ) )
			&& ( $new_url = preg_replace( '/' . $new_url . '/', '', $url, 1 ) )
			&& $url !== $new_url
		) {
			$url = $new_url;
		} else {
			global $pagenow;


			if (
				in_array( $pagenow, [ 'options-general.php', 'wp-login.php' ] )
				&& strpos( $url, '/wp-admin' ) === false
			) {
				if (
					( $bt = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 6 ) )
					&& isset( $bt[5]['file'] )
					&& strpos( lct_static_cleaner( $bt[5]['file'] ), lct_path_plugin( true ) . 'wps-hide-login' ) !== false
				) {
					if (
						$scheme === 'relative'
						&& ( $site_url = site_url( '', 'relative' ) )
						&& strpos( $url, $site_url ) === 0
						&& ( $new_url = str_replace( '/', '\/', $site_url ) )
						&& ( $new_url = preg_replace( '/' . $new_url . '/', '', $url, 1 ) )
						&& $url !== $new_url
					) {
						$url = $new_url;
					} elseif (
						( $site_url = site_url() )
						&& ( $home_url = home_url() )
						&& $site_url !== $home_url
						&& strpos( $url, $site_url ) === 0
					) {
						$url = str_replace( $site_url, $home_url, $url );
					}
				}
			}
		}


		return $url;
	}


	/**
	 * Sets the login path to our default
	 *
	 * @since    2017.61
	 * @verified 2017.08.11
	 */
	function set_login()
	{
		if ( ! get_option( 'whl_page' ) ) {
			lct_delete_option( 'per_version_update_login_redirects' );

			update_option( 'whl_page', 'sitelogin' );
		}
	}
}
