<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Disable cache when we don't need it
 *
 * @since    2018.62
 * @verified 2019.07.12
 */
function lct_disable_cache()
{
	lct_update_setting( 'cache_disabled', true );
}


/**
 * Re-enable cache when we need it
 *
 * @since    2018.62
 * @verified 2019.07.12
 */
function lct_enable_cache()
{
	lct_update_setting( 'cache_disabled', null );
}


/**
 * Check if cache is disabled
 *
 * @since    2019.25
 * @verified 2019.10.30
 */
function lct_is_cache_disabled()
{
	if ( $r = lct_get_setting( 'cache_disabled', false ) ) {
		$memory_used  = memory_get_usage();
		$memory_limit = ini_get( 'memory_limit' );


		if ( preg_match( '/^(\d+)(.)$/', $memory_limit, $matches ) ) {
			if ( $matches[2] == 'M' ) {
				$memory_limit = $matches[1] * 1024 * 1024;
			} // nnnM -> nnn MB
			elseif ( $matches[2] == 'K' ) {
				$memory_limit = $matches[1] * 1024;
			} // nnnK -> nnn KB
		}


		if ( ( $memory_used / $memory_limit ) > .80 ) {
			if ( 1 === 2 ) {
				echo '<br />Limit: ' . lct_get_nice_file_size( $memory_limit );
				echo '<br />Used: ' . lct_get_nice_file_size( $memory_used );
				echo '<br />Available: ' . lct_get_nice_file_size( $memory_limit - $memory_used );
				echo '<br />Percentage: ' . lct_get_percent( $memory_used / $memory_limit );
				exit;
			}


			/**
			 * @date     0.0
			 * @since    2019.25
			 * @verified 2021.08.27
			 */
			do_action( 'lct/is_cache_disabled/cache_flush' );


			wp_cache_flush();


			lct_debug_to_error_log( "\n" . __FUNCTION__ . '() cache was flushed :: Memory BEFORE: ' . lct_get_nice_file_size( $memory_used ) . ' :: AFTER: ' . lct_get_nice_file_size( memory_get_usage() ) );
		}
	}


	return $r;
}


/**
 * Create a cache key with the info provided
 *
 * @param array $vars
 * @param null  $func
 *
 * @return null|string
 * @since    2018.9
 * @verified 2019.01.29
 */
function lct_cache_key( $vars = [], $func = null )
{
	if ( ! $func ) {
		$bt   = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 );
		$func = $bt[1]['function'];
	}


	$r = $func;


	if ( ! empty( $vars ) ) {
		if (
			is_array( $vars )
			|| is_object( $vars )
		) {
			foreach ( (array) $vars as $k => $v ) {
				if (
					is_array( $v )
					|| is_object( $v )
				) {
					$vt = (array) $v;
					$v  = '';


					foreach ( $vt as $kk => $vv ) {
						if (
							is_array( $vv )
							|| is_object( $vv )
						) {
							$vv = md5( base64_encode( serialize( $vv ) ) );
						}


						$v .= '_' . $kk . '-' . $vv;
					}
				}


				if ( in_array( $v, [ '', false ] ) ) {
					continue;
				}


				$v = sanitize_key( ltrim( $v, '_' ) );


				$r .= "/{$k}={$v}";
			}
		} else {
			$r .= '/var=' . sanitize_key( trim( (string) $vars ) );
		}
	}


	return $r;
}


/**
 * Returns a cleaned version of cache key vars in a string.
 *
 * @param string|array $vars The cache key vars.
 *
 * @return string
 * @date     2024.02.20
 * @since    2024.02
 * @verified 2024.02.20
 */
function lct_cache_vars( $vars = '' )
{
	$vars       = func_get_args();
	$func       = lct_previous_function();
	$clean_vars = '';


	foreach ( (array) $vars as $k => $v ) {
		if (
			is_array( $v )
			|| is_object( $v )
		) {
			$v = md5( afwp_acf_base64_encode( serialize( (array) $v ) ) );
		}


		switch ( $v ) {
			case '':
				$v = 'empty_string';
				break;


			case false:
				$v = 'false';
				break;


			case null:
				$v = 'null';
				break;


			default:
		}


		$clean_vars .= "/{$k}={$v}";
	}


	return $func . '::' . md5( $clean_vars );
}


/**
 * isset_cache
 *
 * @param $key
 *
 * @return bool
 * @since    2018.14
 * @verified 2019.03.28
 */
function lct_isset_cache( $key )
{
	$found = false;


	if ( wp_cache_get( $key, 'lct', false, $found ) !== false ) {
		$found = true;
	}


	return $found;
}


/**
 * get_cache
 *
 * @param $key
 *
 * @return mixed
 * @since    2018.14
 * @verified 2019.03.28
 */
function lct_get_cache( $key )
{
	$found = false;


	return wp_cache_get( $key, 'lct', false, $found );
}


/**
 * set_cache
 *
 * @param $key
 * @param $data
 *
 * @return bool
 * @since    2018.14
 * @verified 2019.07.12
 */
function lct_set_cache( $key, $data )
{
	if ( lct_is_cache_disabled() ) {
		return false;
	}


	if ( ! ( $cache_data = lct_get_data( '_cache' ) ) ) {
		$cache_data = [];
	}


	$cache_data[ $key ] = true;


	lct_set_data( '_cache', $cache_data );


	return wp_cache_set( $key, $data, 'lct' );
}


/**
 * delete_cache
 *
 * @param $key
 *
 * @return bool
 * @since    2018.14
 * @verified 2019.07.12
 */
function lct_delete_cache( $key )
{
	if ( ! ( $cache_data = lct_get_data( '_cache' ) ) ) {
		$cache_data = [];
	}


	unset( $cache_data[ $key ] );


	lct_set_data( '_cache', $cache_data );


	return wp_cache_delete( $key, 'lct' );
}


/**
 * delete_cache for all LCT keys
 *
 * @return bool
 * @since    2018.46
 * @verified 2019.07.12
 */
function lct_delete_cache_all()
{
	$r = false;


	if (
		( $cache_data = lct_get_data( '_cache' ) )
		&& ! empty( $cache_data )
	) {
		foreach ( $cache_data as $key => $v ) {
			lct_delete_cache( $key );
		}


		$r = true;
	}


	return $r;
}


/**
 * DO NOT USE: use lct_isset_cache() instead
 * Check if we have the function cached
 *
 * @param null $function
 * @param null $variable
 *
 * @return bool
 * @since    2017.1
 * @verified 2018.01.30
 */
function lct_is_func_cache( $function = null, $variable = null )
{
	$r = false;


	if ( ! $function ) {
		$bt       = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 );
		$function = $bt[1]['function'];
	}


	//find out if already saved in settings
	if ( lct_get_later( $function, $variable ) !== null ) {
		$r = true;
	} //yep, already saved


	return $r;
}


/**
 * DO NOT USE: use lct_get_cache() instead
 * Get the cached value
 *
 * @param null $function
 * @param null $variable
 *
 * @return mixed
 * @since    2017.1
 * @verified 2018.01.30
 */
function lct_get_func_cache( $function = null, $variable = null )
{
	if ( ! $function ) {
		$bt       = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 );
		$function = $bt[1]['function'];
	}


	return lct_get_later( $function, $variable );
}


/**
 * DO NOT USE: use lct_set_cache() instead
 * Update the cached value
 *
 * @param      $value
 * @param null $function
 * @param null $variable
 *
 * @since    2017.1
 * @verified 2018.01.30
 */
function lct_update_func_cache( $value, $function = null, $variable = null )
{
	if ( ! $function ) {
		$bt       = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 );
		$function = $bt[1]['function'];
	}


	//nope, so set it now
	lct_update_later( $function, $value, $variable );
}


/**
 * Get post/user meta cache key
 *
 * @param string $key_name
 * @param bool   $full_prefix
 *
 * @return string
 * @since    2019.4
 * @verified 2019.03.08
 */
function lct_meta_cache_key( $key_name, $full_prefix = true )
{
	$dev = '';
	if ( lct_is_dev() ) {
		$dev = 'dev_';
	}


	$prefix = 'cache_' . $dev;
	if ( $full_prefix ) {
		$prefix = zxzu( $prefix );
	}


	if ( $key_name === 'prefix' ) {
		return $prefix;
	}


	return $prefix . $key_name;
}


/**
 * Set/Update post/user meta cache
 *
 * @param int    $obj_id
 * @param string $meta_type
 * @param string $key_name
 * @param mixed  $value
 *
 * @return int|bool
 * @since    2019.4
 * @verified 2019.03.15
 */
function lct_update_meta_cache( $obj_id, $meta_type, $key_name, $value )
{
	$r   = null;
	$key = lct_meta_cache_key( $key_name );


	switch ( $meta_type ) {
		case 'post':
			$r = update_post_meta( $obj_id, $key, $value );
			break;


		case 'term':
			$r = update_term_meta( $obj_id, $key, $value );
			break;


		default:
	}


	return $r;
}


/**
 * Set/Update postmeta cache
 *
 * @param int    $post_id
 * @param string $key_name
 * @param mixed  $value
 *
 * @return int|bool
 * @since    2019.4
 * @verified 2019.03.08
 */
function lct_update_post_meta_cache( $post_id, $key_name, $value )
{
	return lct_update_meta_cache( $post_id, 'post', $key_name, $value );
}


/**
 * Set/Update termmeta cache
 *
 * @param int    $term_id
 * @param string $key_name
 * @param mixed  $value
 *
 * @return int|bool
 * @since    2019.5
 * @verified 2019.03.15
 */
function lct_update_term_meta_cache( $term_id, $key_name, $value )
{
	return lct_update_meta_cache( $term_id, 'term', $key_name, $value );
}


/**
 * Get post/user meta cache
 *
 * @param int    $obj_id
 * @param string $meta_type
 * @param string $key_name
 * @param bool   $single
 *
 * @return mixed
 * @since    2019.4
 * @verified 2019.03.15
 */
function lct_get_meta_cache( $obj_id, $meta_type, $key_name, $single = true )
{
	$r   = null;
	$key = lct_meta_cache_key( $key_name );


	switch ( $meta_type ) {
		case 'post':
			$r = get_post_meta( $obj_id, $key, $single );
			break;


		case 'term':
			$r = get_term_meta( $obj_id, $key, $single );
			break;


		default:
	}


	return $r;
}


/**
 * Get postmeta cache
 *
 * @param int    $post_id
 * @param string $key_name
 * @param mixed  $single
 *
 * @return mixed
 * @since    2019.4
 * @verified 2019.03.08
 */
function lct_get_post_meta_cache( $post_id, $key_name, $single = true )
{
	return lct_get_meta_cache( $post_id, 'post', $key_name, $single );
}


/**
 * Get termmeta cache
 *
 * @param int    $term_id
 * @param string $key_name
 * @param mixed  $single
 *
 * @return mixed
 * @since    2019.5
 * @verified 2019.03.15
 */
function lct_get_term_meta_cache( $term_id, $key_name, $single = true )
{
	return lct_get_meta_cache( $term_id, 'term', $key_name, $single );
}


/**
 * Delete post/user meta cache
 *
 * @param int    $obj_id
 * @param string $meta_type
 * @param string $key_name
 * @param mixed  $value
 *
 * @return bool
 * @since    2019.25
 * @verified 2019.09.13
 */
function lct_delete_meta_cache( $obj_id, $meta_type, $key_name, $value = '' )
{
	$r   = null;
	$key = lct_meta_cache_key( $key_name );


	switch ( $meta_type ) {
		case 'post':
			$r = delete_post_meta( $obj_id, $key, $value );
			break;


		case 'term':
			$r = delete_term_meta( $obj_id, $key, $value );
			break;


		default:
	}


	return $r;
}


/**
 * Delete postmeta cache
 *
 * @param int    $post_id
 * @param string $key_name
 * @param mixed  $value
 *
 * @return bool
 * @since    2019.25
 * @verified 2019.09.13
 */
function lct_delete_post_meta_cache( $post_id, $key_name, $value = '' )
{
	return lct_delete_meta_cache( $post_id, 'post', $key_name, $value );
}


/**
 * Delete termmeta cache
 *
 * @param int    $term_id
 * @param string $key_name
 * @param mixed  $value
 *
 * @return bool
 * @since    2019.25
 * @verified 2019.09.13
 */
function lct_delete_term_meta_cache( $term_id, $key_name, $value = '' )
{
	return lct_delete_meta_cache( $term_id, 'term', $key_name, $value );
}
