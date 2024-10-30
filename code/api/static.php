<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Cleanup the string when we are using a Windows server
 *
 * @param string $string
 * @param bool   $force_no_trailing_slash
 *
 * @return string
 * @since        0.0
 * @verified     2016.11.04
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_static_cleaner( $string, $force_no_trailing_slash = true )
{
	$string = str_replace( '\\', '/', $string );


	if ( $force_no_trailing_slash ) {
		$string = rtrim( $string, '/' );
	}


	return $string;
}


/**
 * Get the site's upload directory path
 * PATH :: NO trailing slash
 *
 * @param string $path
 *
 * @return string
 * @since    0.0
 * @verified 2016.11.04
 */
function lct_path_up( $path = '' )
{
	$var = wp_upload_dir();


	if ( $path ) {
		$path = '/' . $path;
	}


	return lct_static_cleaner( $var['basedir'] ) . $path;
}


/**
 * Get the site's upload directory path
 * PATH :: NO trailing slash
 *
 * @param string $path
 *
 * @return string
 * @since    2017.34
 * @verified 2017.05.02
 */
function lct_path_up_now( $path = '' )
{
	$var = wp_upload_dir();


	if ( $path ) {
		$path = '/' . $path;
	}


	return lct_static_cleaner( $var['path'] ) . $path;
}


/**
 * Get the site's upload directory URL
 * URL :: NO trailing slash
 *
 * @param string $path
 *
 * @return string
 * @since    0.0
 * @verified 2018.06.04
 */
function lct_url_up( $path = '' )
{
	$var = wp_upload_dir();


	if ( $path ) {
		$path = '/' . $path;
	}


	return lct_static_cleaner( $var['baseurl'] ) . $path;
}


/**
 * Get the site's uploads dir only
 * URL :: WITH trailing slash
 *
 * @return string
 * @since    7.58
 * @verified 2016.12.21
 */
function lct_up_dir_only()
{
	return str_replace( lct_path_site(), '', lct_path_up() ) . '/';
}


/**
 * Get the plugin's dir only
 * URL :: WITH trailing slash
 *
 * @param string $path
 *
 * @return string
 * @since    2018.61
 * @verified 2018.08.10
 */
function lct_root_dir_only( $path = '' )
{
	return str_replace( lct_path_site(), '', lct_get_root_path() ) . $path;
}


/**
 * Replaced by lct_path_up()
 * PATH :: NO trailing slash
 *
 * @return mixed
 * @since    0.0
 * @verified 2016.11.04
 */
function lct_up_path()
{
	return lct_path_up();
}


/**
 * Replaced by lct_url_up()
 * URL :: NO trailing slash
 *
 * @return mixed
 * @since    0.0
 * @verified 2016.11.04
 */
function lct_up()
{
	return lct_url_up();
}


/**
 * Get the site's public path
 * PATH :: NO trailing slash
 *
 * @param bool $add_trailing_slash
 *
 * @return mixed
 * @since    0.0
 * @verified 2016.12.27
 */
function lct_path_site( $add_trailing_slash = false )
{
	$path = lct_static_cleaner( $_SERVER['DOCUMENT_ROOT'] );

	$url = lct_url_root_site();
	$url = parse_url( $url );


	//check if site is in a sub-dir
	if (
		isset( $url['path'] )
		&& $url['path'] != '/'
	) {
		$path .= $url['path'];
	}


	if ( $add_trailing_slash ) {
		$path .= '/';
	}


	return $path;
}


/**
 * Get the site's public URL
 * URL :: NO trailing slash
 *
 * @return string
 * @since    0.0
 * @verified 2017.05.10
 */
function lct_url_site()
{
	return lct_static_cleaner( get_bloginfo( 'url' ) );
}


/**
 * Get the site's public URL
 * URL :: NO trailing slash
 *
 * @return string
 * @since    2018.53
 * @verified 2018.06.12
 */
function lct_url_site_when_dev()
{
	lct_update_setting( 'tmp_disable_dev_url', true );
	$r = lct_static_cleaner( get_bloginfo( 'url' ) );
	lct_update_setting( 'tmp_disable_dev_url', null );


	return $r;
}


/**
 * Get the site's WordPress path
 * PATH :: NO trailing slash
 *
 * @return string
 * @since    0.0
 * @verified 2016.11.04
 */
function lct_path_site_wp()
{
	return lct_static_cleaner( ABSPATH );
}


/**
 * Get the site's WordPress URL
 * URL :: NO trailing slash
 *
 * @return string
 * @since    0.0
 * @verified 2016.11.04
 */
function lct_url_site_wp()
{
	return lct_static_cleaner( get_site_url() );
}


/**
 * Get the site's WordPress URL
 * URL :: NO trailing slash
 *
 * @return string
 * @date     2020.12.08
 * @since    2020.14
 * @verified 2020.12.08
 */
function lct_url_site_wp_when_dev()
{
	lct_update_setting( 'tmp_disable_dev_url', true );
	$r = lct_static_cleaner( get_site_url() );
	lct_update_setting( 'tmp_disable_dev_url', null );


	return $r;
}


/**
 * Get the site's WordPress dir only
 * URL :: WITH trailing slash
 *
 * @return string
 * @since    7.58
 * @verified 2016.12.21
 */
function lct_wp_dir_only()
{
	return str_replace( lct_path_site(), '', lct_path_site_wp() ) . '/';
}


/**
 * Get the child theme's path
 * PATH :: NO trailing slash
 *
 * @param string $path
 *
 * @return string
 * @since    0.0
 * @verified 2019.02.18
 */
function lct_path_theme( $path = '' )
{
	/**
	 * Return early if cache is found
	 */
	$cache_key = lct_cache_key( compact( 'path' ) );
	if ( lct_isset_cache( $cache_key ) ) {
		return lct_get_cache( $cache_key );
	}


	$r = lct_static_cleaner( get_stylesheet_directory() ) . $path;

	/**
	 * Save the value to the cache
	 */
	lct_set_cache( $cache_key, $r );


	return $r;
}


/**
 * Get the child theme's URL
 * URL :: NO trailing slash
 *
 * @param string $path
 *
 * @return string
 * @since    0.0
 * @verified 2019.02.18
 */
function lct_url_theme( $path = '' )
{
	/**
	 * Return early if cache is found
	 */
	$cache_key = lct_cache_key( compact( 'path' ) );
	if ( lct_isset_cache( $cache_key ) ) {
		return lct_get_cache( $cache_key );
	}


	$r = lct_static_cleaner( get_stylesheet_directory_uri() ) . $path;

	/**
	 * Save the value to the cache
	 */
	lct_set_cache( $cache_key, $r );


	return $r;
}


/**
 * Get the parent theme's path
 * PATH :: NO trailing slash
 *
 * @return string
 * @since    0.0
 * @verified 2016.11.04
 */
function lct_path_theme_parent()
{
	return lct_static_cleaner( get_template_directory() );
}


/**
 * Get the parent theme's URL
 * URL :: NO trailing slash
 *
 * @return string
 * @since    0.0
 * @verified 2016.11.04
 */
function lct_url_theme_parent()
{
	return lct_static_cleaner( get_template_directory_uri() );
}


/**
 * Get the plugin directory path
 * PATH :: NO trailing slash
 *
 * @param bool $add_trailing_slash
 *
 * @return string
 * @since    0.0
 * @verified 2016.11.27
 */
function lct_path_plugin( $add_trailing_slash = false )
{
	$path_parts = explode( '/', lct_get_setting( 'root_path' ) );


	if ( end( $path_parts ) == '' ) {
		array_pop( $path_parts );
	}

	array_pop( $path_parts );

	$path = implode( '/', $path_parts );


	if ( $add_trailing_slash ) {
		$path .= '/';
	}


	return $path;
}


/**
 * Get the plugin directory URL
 * URL :: NO trailing slash
 *
 * @param bool $add_trailing_slash
 *
 * @return string
 * @since    0.0
 * @verified 2016.11.27
 */
function lct_url_plugin( $add_trailing_slash = false )
{
	$url_parts = explode( '/', lct_get_setting( 'root_url' ) );


	if ( end( $url_parts ) == '' ) {
		array_pop( $url_parts );
	}

	array_pop( $url_parts );

	$url = implode( '/', $url_parts );


	if ( $add_trailing_slash ) {
		$url .= '/';
	}


	return $url;
}


/**
 * Get the site's root URL
 * URL :: NO trailing slash
 *
 * @return string
 * @since    0.0
 * @verified 2016.11.04
 */
function lct_url_root_site()
{
	return lct_static_cleaner( get_option( 'home' ) );
}


/**
 * Swap the site's URL with the site's path
 *
 * @param $content
 *
 * @return string
 * @since    0.0
 * @verified 2018.06.12
 */
function lct_swap_url_to_path( $content )
{
	if ( ! isset( $_GET['html'] ) ) {
		if ( lct_is_dev_or_sb() ) {
			$content = str_replace( lct_url_site_when_dev(), lct_path_site(), $content );
		} else {
			$content = str_replace( lct_url_site(), lct_path_site(), $content );
		}


	}


	return $content;
}


/**
 * Swap the site's path with the site's URL
 *
 * @param $content
 *
 * @return string
 * @since    0.0
 * @verified 2016.11.04
 */
function lct_swap_path_to_url( $content )
{
	return str_replace( lct_path_site(), lct_url_site(), $content );
}


/**
 * Strip the site's root URL & root path
 *
 * @param $content
 *
 * @return string
 * @since    7.27
 * @verified 2016.11.04
 */
function lct_strip_site( $content )
{
	return lct_strip_path( lct_strip_url( $content ) );
}


/**
 * Strip the site's root URL
 *
 * @param $content
 *
 * @return string
 * @since    7.27
 * @verified 2018.06.12
 */
function lct_strip_url( $content )
{
	$content = str_replace( lct_url_site(), '', lct_static_cleaner( $content ) );


	if ( lct_is_dev_or_sb() ) {
		$content = str_replace( lct_url_site_when_dev(), '', $content );
	}


	return $content;
}


/**
 * Strip the site's root path
 *
 * @param $content
 *
 * @return string
 * @since    7.27
 * @verified 2016.11.04
 */
function lct_strip_path( $content )
{
	return str_replace( lct_path_site(), '', lct_static_cleaner( $content ) );
}


/**
 * ACF Default field name
 *
 * @return string
 * @since    0.0
 * @verified 2016.11.04
 */
function lct_following()
{
	return get_cnst( 'a_c_f_following' );
}


/**
 * ACF Default field name
 *
 * @return string
 * @since    2017.37
 * @verified 2017.05.24
 */
function lct_following_us()
{
	return get_cnst( 'following' );
}


/**
 * ACF Default field name
 *
 * @return string
 * @since    0.0
 * @verified 2016.11.04
 */
function lct_following_parent()
{
	return get_cnst( 'a_c_f_following_parent' );
}


/**
 * ACF Default field name
 *
 * @return string
 * @since    0.0
 * @verified 2016.11.04
 */
function lct_org()
{
	return get_cnst( 'a_c_f_org' );
}


/**
 * ACF Default field name
 *
 * @return string
 * @since    0.0
 * @verified 2016.11.04
 */
function lct_status()
{
	return get_cnst( 'a_c_f_status' );
}


/**
 * ACF Default field name
 *
 * @return string
 * @since    0.0
 * @verified 2016.11.04
 */
function lct_tax_status()
{
	return get_cnst( 'a_c_f_tax_status' );
}


/**
 * Get the default DB date-time format
 * Y-m-d H:i:s
 *
 * @return string
 * @since    2018.58
 * @verified 2018.07.18
 */
function lct_db_date_format()
{
	return 'Y-m-d H:i:s';
}


/**
 * Get the default DB date-time format
 * Y-m-d H:i:00
 *
 * @return string
 * @since    2018.59
 * @verified 2018.07.27
 */
function lct_db_date_format_no_seconds()
{
	return 'Y-m-d H:i:00';
}


/**
 * Get the default DB date-time format
 * Y-m-d 00:00:00
 *
 * @return string
 * @since    2018.63
 * @verified 2018.09.06
 */
function lct_db_date_only_format()
{
	return 'Y-m-d 00:00:00';
}


/**
 * Get the default DB date-time format
 * Y-m-d
 *
 * @return string
 * @since    2018.65
 * @verified 2018.10.08
 */
function lct_db_date_only_no_time_format()
{
	return 'Y-m-d';
}


/**
 * Get the default DB date-time format
 * 00:00:00
 *
 * @return string
 * @since    2018.65
 * @verified 2018.10.08
 */
function lct_db_time_midnight_format()
{
	return '00:00:00';
}


/**
 * Get the default DB date-time format
 * H:i:00
 *
 * @return string
 * @since    2018.65
 * @verified 2018.10.08
 */
function lct_db_time_format_no_seconds()
{
	return 'H:i:00';
}
