<?php
/** @noinspection PhpMissingFieldTypeInspection */
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.10.21
 */
class lct_wp_rocket_admin
{
	public $parallel_prefix = 'cdn';
	public $cdns = [];
	public $current_cdn = 0;


	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.10.21
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
	 * @since    2017.90
	 * @verified 2017.10.21
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
		$this->setup_cdns();

		$this->redirect_parallelize_hostnames();

		/**
		 * filters
		 */
		add_filter( 'rocket_buffer', [ $this, 'simple_user_agent_log' ], 99999 );

		add_filter( 'rocket_config_file', [ $this, 'add_user_agent_check_when_cookie_not_set' ], 10, 2 );

		add_filter( 'rocket_cache_dynamic_cookies', [ $this, 'add_user_agent_dynamic_cookies' ] );

		//Don't actually need this. The issue was with the server
		//add_filter( 'rocket_sitemap_preload_list', [ $this, 'add_yoast_sitemap' ] );

		add_filter( 'rocket_exclude_defer_js', [ $this, 'exclude_defer_random_js' ] );

		//Internal requests are not working without this
		add_filter( 'https_local_ssl_verify', '__return_false' );
		add_filter( 'https_ssl_verify', '__return_false' );

		//We don't need this when CDN is active on Rocket Cache
		//add_filter( 'wp_get_attachment_url', [ $this, 'parallelize_hostnames' ], 10, 2 );

		add_filter( 'rocket_clean_domain_urls', [ $this, 'clear_transients_acf_map_data' ] );

		add_filter( 'rocket_post_purge_urls', [ $this, 'force_front_page_purge_prematurely' ], 10, 2 );

		add_filter( 'rocket_clean_home_root', [ $this, 'force_front_page_purge_prematurely_2' ], 10, 3 );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * If we don't do this the homepage gets purged every time a change is made
	 *
	 * @param array   $purge_urls
	 * @param WP_Post $post
	 *
	 * @return array
	 * @date         2020.11.09
	 * @since        2020.14
	 * @verified     2020.11.09
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function force_front_page_purge_prematurely( $purge_urls, $post )
	{
		if ( get_option( 'show_on_front' ) === 'posts' ) {
			return $purge_urls;
		}


		lct_update_later( 'force_front_page_purge_prematurely', true );


		if ( (int) get_option( 'page_on_front' ) === $post->ID ) {
			$purge_urls[] = rtrim( get_option( 'home' ), '/' ) . '/index-https.html';
			$purge_urls[] = rtrim( get_option( 'home' ), '/' ) . '/index-https.html_gzip';
		}


		return $purge_urls;
	}


	/**
	 * If we don't do this the homepage gets purged every time a change is made
	 *
	 * @param string $root
	 * @param string $host
	 * @param string $path
	 *
	 * @return string
	 * @date         2020.11.09
	 * @since        2020.14
	 * @verified     2020.11.09
	 * @noinspection PhpMissingParamTypeInspection
	 * @noinspection PhpUnusedParameterInspection
	 */
	function force_front_page_purge_prematurely_2( $root, $host, $path )
	{
		if ( lct_get_later( 'force_front_page_purge_prematurely' ) ) {
			return __return_empty_string();
		}


		return $root;
	}


	/**
	 * Setup our CDNs here
	 *
	 * @since    2017.90
	 * @verified 2017.10.24
	 */
	function setup_cdns()
	{
		if ( ! empty( $_SERVER['HTTP_HOST'] ) ) {
			$host = preg_replace( '/www\./', '', $_SERVER['HTTP_HOST'], 1 );


			for ( $i = 1; $i <= 4; $i ++ ) {
				$this->cdns[] = 'www.' . $this->parallel_prefix . $i . '.' . $host;
			}
		}
	}


	/**
	 * Keep track of the current CDN we are serving from
	 *
	 * @return int
	 * @since    2017.90
	 * @verified 2017.10.24
	 */
	function get_current_cdn()
	{
		$current = $this->current_cdn;


		$this->current_cdn ++;

		if ( $this->current_cdn >= 4 ) {
			$this->current_cdn = 0;
		}


		return $current;
	}


	/**
	 * Prevent parallelize hostnames from being viewed or indexed
	 *
	 * @since    2017.90
	 * @verified 2017.10.24
	 */
	function redirect_parallelize_hostnames()
	{
		if (
			! empty( $this->cdns )
			&& ! empty( $_SERVER['HTTP_HOST'] )
			&& ! empty( $_SERVER['REQUEST_URI'] )
			&& (
				strpos( $_SERVER['HTTP_HOST'], 'www.' . $this->parallel_prefix ) === 0
				|| strpos( $_SERVER['HTTP_HOST'], $this->parallel_prefix ) === 0
			)
		) {
			lct_update_setting( 'tmp_disable_dev_url', true );
			lct_wp_safe_redirect( lct_url_root_site() . $_SERVER['REQUEST_URI'], 301 );
		}
	}


	/**
	 * Parallelize hostnames to speed up static resource serving
	 *
	 * @param $url
	 *
	 * @unused   param $post_id
	 * @return mixed
	 * @since    2017.90
	 * @verified 2017.10.24
	 */
	function parallelize_hostnames( $url )
	{
		$hostname = $this->cdns[ $this->get_current_cdn() ];
		$url      = str_replace( parse_url( get_bloginfo( 'url' ), PHP_URL_HOST ), $hostname, $url );


		return $url;
	}


	/**
	 * Add Yoast Sitemap to the preload list, because the default rocket one is not working
	 * Don't actually need this. The issue was with the server
	 *
	 * @param Array $sitemaps Array of sitemaps to preload.
	 *
	 * @return Array Updated array of sitemaps to preload
	 * @since        2017.90
	 * @verified     2017.10.21
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function add_yoast_sitemap( $sitemaps )
	{
		$custom_sitemaps = [
			'page-sitemap',
			'post-sitemap',
			'xpg_gallery-sitemap',
			'sitemap_index',
		];


		foreach ( $custom_sitemaps as $custom_sitemap ) {
			$sitemaps[] = trailingslashit( home_url() ) . $custom_sitemap . '.xml';
		}


		return $sitemaps;
	}


	/**
	 * We want to record the user_agent responsible for the generation of a cached file, that way we can add exceptions when things blow up
	 *
	 * @param $buffer
	 *
	 * @return string
	 * @since    2017.90
	 * @verified 2017.10.21
	 */
	function simple_user_agent_log( $buffer )
	{
		if ( isset( $_COOKIE['lct_user_agent'] ) ) {  //Only continue if we have set the cookie yet
			$cookie = $_COOKIE['lct_user_agent'];

			if ( empty( $cookie ) ) {
				$cookie = LCT_VALUE_EMPTY;
			}

			$buffer .= sprintf( '%s<!-- Custom Caching Debug - User Agent Cookie: %s -->', "\n", $cookie );
		}


		if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) )  //Only continue if this server running the site is setting the "HTTP_USER_AGENT" variable
		{
			$buffer .= sprintf( '%s<!-- Custom Caching Debug - User Agent: %s -->', "\n", $_SERVER['HTTP_USER_AGENT'] );
		}


		return $buffer;
	}


	/**
	 * Add user_agent cookies to the dynamic cookies list
	 *
	 * @param array $cookies Cookies to use for dynamic caching.
	 *
	 * @return array Updated cookies list
	 * @since        2017.90
	 * @verified     2017.10.21
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function add_user_agent_dynamic_cookies( $cookies )
	{
		$cookies[] = 'lct_user_agent';


		return $cookies;
	}


	/**
	 * Some times external JS should not be deferred, and we need to to let rocket cache know that.
	 * //TODO: cs - Make this a setting in wp-admin, if we encounter a lot of these instances - 10/24/2017 12:17 AM
	 *
	 * @param $exclude_defer_js
	 *
	 * @return array
	 * @since    2017.90
	 * @verified 2017.10.24
	 */
	function exclude_defer_random_js( $exclude_defer_js )
	{
		//Originated from: fs10.formsite.com/include/form/embedManager.js?1813752488
		$exclude_defer_js[] = '/include/form/embedManager.js';


		return $exclude_defer_js;
	}


	/**
	 * We want to set the user_agent cookie And this is where we properly do it.
	 *
	 * @param $buffer
	 *
	 * @unused   param $config_files_path
	 * @return string
	 * @since    2017.90
	 * @verified 2022.02.15
	 */
	function add_user_agent_check_when_cookie_not_set( $buffer )
	{
		$custom_buffer = [];


		/**
		 * The Main Event
		 */
		$custom_buffer[] = '

/**
 * You can test anything below here by pasting it directly into the file in: /(wp|lc)-content/wp-rocket-config/
 * Just be careful, it will get overwritten when you save WP Rocket settings
 */


/**
 * We need to set the user_agent before we go any further. That way we can set user_agent specific caches (mostly just Internet Explorer, big surprise)
 *
 * @source   See for more details and to update this script: /wp-includes/vars.php
 * *
 * Possible user_agent
 * WP Rocket
 * bots (Google, Bing, etc.)
 * IEMobile
 * iphone
 * ipad
 * lynx
 * edge
 * chrome
 * safari
 * winIE
 * macIE
 * gecko
 * opera
 * NS4
 */
if (
	empty( $_COOKIE["lct_user_agent"] ) && //Only continue if we haven\'t set the cookie yet
	empty( $_COOKIE["lct_user_agent_default"] ) && //Only continue if we haven\'t set the DEFAULT cookie yet
	! empty( $_SERVER["HTTP_USER_AGENT"] ) //Only continue if this server running the site is setting the "HTTP_USER_AGENT" variable
) {
	/**
	 * Vars
	 */
	 $request_uri = "";


	 /**
	 * Used for debugging
	 */
	if ( 1 === 2 ) {
		$_SERVER["HTTP_USER_AGENT"] = "custom user_agent goes here";
	}


	/**
	 * Set is_mobile
	 */
	$lct_rocket_is_mobile = false;

	if (
		isset( $rocket_cache_mobile, $rocket_do_caching_mobile_files ) &&		class_exists( "Rocket_Mobile_Detect" )
	) {
		$detect = new Rocket_Mobile_Detect();


		if (
			$detect->isMobile() &&			! $detect->isTablet()
		) {
			$lct_rocket_is_mobile = true;
		}
	}


	/**
	 * Set the user_agent
	 */
	$bots = "googlebot|bot\b|spider|crawl|wget|slurp|facebookexternalhit|Mediapartners-Google";


	if ( $rocket_cache_reject_ua )
		$bots .= "|" . $rocket_cache_reject_ua;


	/**
	 * wprocketbot
	 */
	if (
		stripos( $_SERVER["HTTP_USER_AGENT"], "wprocketbot" ) !== false ||		stripos( $_SERVER["HTTP_USER_AGENT"], "WP Rocket/Preload" ) !== false
	) {
		$lct_rocket_user_agent = "wprocketbot";


		/**
		 * Bots
		 */
	} elseif ( preg_match( "/" . $bots . "/i", $_SERVER["HTTP_USER_AGENT"] ) ) {
		$lct_rocket_user_agent = "bot";


		/**
		 * Mobile Microsoft Internet Explorer
		 */
	} elseif ( stripos( $_SERVER["HTTP_USER_AGENT"], "iemobile" ) !== false ) {
		$lct_rocket_user_agent = "iemobile" . lct_rocket_get_ua_version( $_SERVER["HTTP_USER_AGENT"], "iemobile/" );


		/**
		 * iPhone
		 */
	} elseif ( stripos( $_SERVER["HTTP_USER_AGENT"], "iphone" ) !== false ) {
		$lct_rocket_user_agent = "default";


		/**
		 * iPad
		 */
	} elseif ( stripos( $_SERVER["HTTP_USER_AGENT"], "ipad" ) !== false ) {
		$lct_rocket_user_agent = "default";


		/**
		 * Lynx
		 */
	} elseif ( strpos( $_SERVER["HTTP_USER_AGENT"], "Lynx" ) !== false ) {
		$lct_rocket_user_agent = "lynx";


		/**
		 * Microsoft Edge
		 */
	} elseif ( strpos( $_SERVER["HTTP_USER_AGENT"], "Edge" ) !== false ) {
		$lct_rocket_user_agent = "edge" . lct_rocket_get_ua_version( $_SERVER["HTTP_USER_AGENT"], "edge/", [ 13 ] );


		/**
		 * Google Chrome
		 */
	} elseif ( stripos( $_SERVER["HTTP_USER_AGENT"], "chrome" ) !== false ) {
		//Doubt anyone is using this, but who knows
		if ( stripos( $_SERVER["HTTP_USER_AGENT"], "chromeframe" ) !== false ) {
			$lct_rocket_user_agent = "chromeframe";


			//Mobile
		} elseif ( $lct_rocket_is_mobile ) {
			$lct_rocket_user_agent = "default";


			//Desktop
		} else {
			//Check for last supported version: https://en.wikipedia.org/wiki/Google_Chrome_version_history
			if ( $version = lct_rocket_get_ua_version( $_SERVER["HTTP_USER_AGENT"], "chrome/", [ 15 ] ) )
				$lct_rocket_user_agent = "chrome" . $version;
			else
				$lct_rocket_user_agent = "default";
		}


		/**
		 * Apple Safari
		 */
	} elseif ( stripos( $_SERVER["HTTP_USER_AGENT"], "safari" ) !== false ) {
		//Check for last supported version: https://en.wikipedia.org/wiki/Safari_version_history
		if ( $version = lct_rocket_get_ua_version( $_SERVER["HTTP_USER_AGENT"], "version/", [ 6 ] ) )
			$lct_rocket_user_agent = "safari" . $version;
		else
			$lct_rocket_user_agent = "default";


		/**
		 * Microsoft Internet Explorer
		 */
	} elseif ( ( strpos( $_SERVER["HTTP_USER_AGENT"], "MSIE" ) !== false || strpos( $_SERVER["HTTP_USER_AGENT"], "Trident" ) !== false ) && strpos( $_SERVER["HTTP_USER_AGENT"], "Win" ) !== false ) {
		$lct_rocket_user_agent = "winie" . lct_rocket_get_ua_version( $_SERVER["HTTP_USER_AGENT"], "ie" );


		/**
		 * Apple Microsoft Internet Explorer
		 */
	} elseif ( strpos( $_SERVER["HTTP_USER_AGENT"], "MSIE" ) !== false && strpos( $_SERVER["HTTP_USER_AGENT"], "Mac" ) !== false ) {
		$lct_rocket_user_agent = "macie" . lct_rocket_get_ua_version( $_SERVER["HTTP_USER_AGENT"], "ie" );


		/**
		 * Firefox
		 */
	} elseif ( strpos( $_SERVER["HTTP_USER_AGENT"], "Gecko" ) !== false ) {
		//Mobile
		if ( $lct_rocket_is_mobile ) {
			$lct_rocket_user_agent = "default";


			//Desktop
		} else {
			//Check for last supported version: https://www.mozilla.org/en-US/firefox/organizations/faq/ (ESR Overview)
			if ( $version = lct_rocket_get_ua_version( $_SERVER["HTTP_USER_AGENT"], "firefox/", [ 15 ] ) )
				$lct_rocket_user_agent = "gecko" . $version;
			else
				$lct_rocket_user_agent = "default";
		}


		/**
		 * Opera
		 */
	} elseif ( strpos( $_SERVER["HTTP_USER_AGENT"], "Opera" ) !== false || strpos( $_SERVER["HTTP_USER_AGENT"], "OPR" ) !== false ) {
		//Mobile
		if ( $lct_rocket_is_mobile ) {
			$lct_rocket_user_agent = "default";


			//Desktop
		} else {
			if ( $version = lct_rocket_get_ua_version( $_SERVER["HTTP_USER_AGENT"], "opr/", [ 15 ] ) )
				$lct_rocket_user_agent = "opera" . $version;
			elseif ( $version = lct_rocket_get_ua_version( $_SERVER["HTTP_USER_AGENT"], "opera/", [ 15 ] ) )
				$lct_rocket_user_agent = "opera" . $version;
			else
				$lct_rocket_user_agent = "default";
		}


		/**
		 * Nav
		 */
	} elseif ( strpos( $_SERVER["HTTP_USER_AGENT"], "Nav" ) !== false && strpos( $_SERVER["HTTP_USER_AGENT"], "Mozilla/4." ) !== false ) {
		$lct_rocket_user_agent = "ns4";


		/**
		 * Everything Else
		 */
	} else {
		if ( $lct_rocket_is_mobile )
			$lct_rocket_user_agent = "default";
		else
			$lct_rocket_user_agent = "unknown";
	}


	/**
	 * END :: Set the user_agent
	 */


	/**
	 * Set some variables
	 */
	$default_user_agents = [ "default", "wprocketbot" ];

	//path
	$request_uri_path = $config_file_path[\'path\'] . rtrim( $_SERVER[\'REQUEST_URI\'], "/" );
	//$request_uri_path = preg_replace_callback( "/%[0-9A-F]{2}/", "rocket_urlencode_lowercase", $request_uri_path );

	//path root
	$filename = "index";

	// Rename the caching filename for mobile.
	if ( $lct_rocket_is_mobile )
		$filename .= "-mobile";

	// Rename the caching filename for SSL URLs.
	if (
		lct_rocket_is_ssl() &&		! empty( $rocket_cache_ssl )
	) {
		$filename .= "-https";
	}

	//user specific cache
	if ( in_array( $lct_rocket_user_agent, $default_user_agents ) )
		$browser_filename = $filename;
	else
		$browser_filename = $filename . "-" . $lct_rocket_user_agent;

	//file path
	$rocket_cache_filepath         = $request_uri_path . "/" . $filename . ".html";
	$rocket_cache_browser_filepath = $request_uri_path . "/" . $browser_filename . ".html";


	/**
	 * Used for debugging
	 */
	if ( 1 === 2 ) {
		echo $lct_rocket_user_agent;
		echo "<br />";
		echo $_SERVER["HTTP_USER_AGENT"];
		echo "<br />";
		echo $_SERVER["REQUEST_URI"];
		exit;
	}


	/**
	 * Set browser specific cookies
	 * First for bots
	 */
	if (
		$lct_rocket_user_agent === "bot" && //Only if they are a bot
		! file_exists( $rocket_cache_filepath ) //cache default not-exists
	) {
		//Set this to the requested URI, it will prevent the bot from creating a cache file, which could suck
		$rocket_cache_reject_uri = $request_uri;

		//Just skip setting a cookie, they won\'t eat it.


		/**
		 * Any first visits or defaults
		 */
	} elseif (
		! empty( $_COOKIE["lct_user_agent_first_visit"] ) || //If second time
		in_array( $lct_rocket_user_agent, $default_user_agents ) || //user_agent is default
		(
			! in_array( $lct_rocket_user_agent, $default_user_agents ) && //user_agent is not-default
			(
				(
					file_exists( $rocket_cache_browser_filepath ) && //cache user specific exists
					is_readable( $rocket_cache_browser_filepath ) //cache user specific exists
				) ||				(
					! file_exists( $rocket_cache_browser_filepath ) && //cache user specific not-exists
					! file_exists( $rocket_cache_filepath ) //cache default not-exists
				)
			)
		)
	) {
		/**
		 * Delete First Visit Cookie
		 */
		if ( ! empty( $_COOKIE["lct_user_agent_first_visit"] ) ) {
			unset( $_COOKIE["lct_user_agent_first_visit"] );
			setcookie( "lct_user_agent_first_visit", "", time() - 3600 );
		}


		/**
		 * Set the cookie
		 */
		if ( in_array( $lct_rocket_user_agent, $default_user_agents ) )
			setcookie( "lct_user_agent_default", true );
		else
			setcookie( "lct_user_agent", $lct_rocket_user_agent );


		/**
		 * Redirect so that we can serve the proper cache file
		 * //TODO: cs - Add checks for testing sites. Ex: pingdom, google, etc. - 10/22/2017 1:52 PM
		 */
		if (
			! empty( $_SERVER["REQUEST_URI"] ) &&			! in_array( $lct_rocket_user_agent, array_merge( [ "bot" ], $default_user_agents ) ) //don\'t redirect bots OR default
		) {
			header( "Location: {$_SERVER["REQUEST_URI"]}", true );
			exit;
		}


		/**
		 * If first time and UA is not default or not bot and cache user specific not-exists and default cache default exists
		 */
	} elseif ( $lct_rocket_user_agent !== "bot" ) {
		/**
		 * Set the cookie
		 */
		setcookie( "lct_user_agent_first_visit", true );
	}


	/**
	 * No user_agent is present
	 */
} elseif ( empty( $_SERVER["HTTP_USER_AGENT"] ) ) {
	/**
	 * Set the cookie
	 */
	setcookie( "lct_user_agent", "no_user_agent" );
}


/**
 * Set the version based on the user_agent
 *
 * @param string $ua
 * @param string $delimiter
 * @param array  $less_than must be listed highest to lowest
 *
 * @return string
 * @since    2017.90
 * @verified 2017.10.21
 */
function lct_rocket_get_ua_version( $ua, $delimiter, $less_than = [] ) {
	/**
	 * Microsoft Internet Explorer
	 */
	if ( $delimiter === "ie" ) {
		$ua = strtolower( $ua );
		if ( strpos( $ua, "rv:11" ) !== false ) {
			$version = "-v11";
		} elseif ( strpos( $ua, "msie 10" ) !== false ) {
			$version = "-v10";
		} elseif ( strpos( $ua, "msie 9" ) !== false ) {
			$version = "-v9";
		} elseif ( strpos( $ua, "msie 8" ) !== false ) {
			$version = "-v8";
		} else {
			$version = "-vold";
		}


		/**
		 * Everything Else
		 */
	} else {
		//Drill down to get the version of the user_agent
		$explode = explode( $delimiter, strtolower( $ua ) );


		if ( ! empty( $explode[1] ) ) {
			$version = "";
			$explode = explode( " ", $explode[1] );


			if ( ! empty( $explode[0] ) ) {
				$explode = explode( ".", $explode[0] );


				if ( ! empty( $explode[0] ) ) {
					/**
					 * If we just want some breakpoints for the same browser we can use a specific less_than version(s)
					 * Or we can set it to true, to  save a cache for each version (not recommended)
					 */
					if ( $less_than === true ) {
						$version = "-v" . (int) $explode[0];
					} elseif ( ! empty( $less_than ) ) {
						if ( ! is_array( $less_than ) )
							$less_than = [ $less_than ];

						$active_version = (int) $explode[0];


						foreach ( $less_than as $less ) {
							if ( version_compare( $active_version, $less, ">=" ) ) {
								if ( ! $version )
									$version = "";


								break;
							} else {
								$version = "-lt-v" . $less;
							}
						}
					}


					//drill down didn\'t work (funky user_agent), so just set it as unknown
				} else {
					$version = "-vunknown";
				}
			}


			//drill down didn\'t work (funky user_agent), so just set it as unknown
		} else {
			$version = "-vunknown";
		}
	}


	return $version;
}


/**
 * Determine if SSL is used
 *
 * @source   rocket_is_ssl() in /plugins/wp-rocket/inc/front/process.php
 * @since    2017.90
 * @verified 2017.10.21
 */
function lct_rocket_is_ssl() {
	if ( isset( $_SERVER["HTTPS"] ) ) {
		if ( "on" === strtolower( $_SERVER["HTTPS"] ) ) {
			return true;
		}
		if ( "1" === $_SERVER["HTTPS"] ) {
			return true;
		}
	} elseif ( 
		isset( $_SERVER["SERVER_PORT"] ) && 
		( "443" === $_SERVER["SERVER_PORT"] ) 
	) {
		return true;
	}

	return false;
}
';


		if ( ! empty( $custom_buffer ) ) {
			$buffer .= lct_return( $custom_buffer, "\n" );
		}


		return $buffer;
	}


	/**
	 * Remove acf_map_data transients
	 * This is used on complex sites like foy & nc so be very careful
	 *
	 * @param $urls
	 *
	 * @return mixed
	 * @since    2018.6
	 * @verified 2018.01.22
	 */
	function clear_transients_acf_map_data( $urls )
	{
		global $wpdb;


		$trans = $wpdb->get_results(
			$wpdb->prepare( "SELECT `option_name` FROM {$wpdb->options}
				WHERE `option_name` LIKE '%s'",
				[ '%' . esc_sql( 'transient_' . zxzu( 'acf_map_data_' ) ) . '%' ]
			)
		);


		if ( ! empty( $trans ) ) {
			foreach ( $trans as $tran ) {
				delete_transient( str_replace( '_transient_', '', $tran->option_name ) );
			}
		}


		return $urls;
	}
}
