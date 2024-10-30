<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Returns a 2 key array based on your $needle and $offset
 *
 * @param $string
 * @param $needle
 * @param $offset
 *
 * @return array|bool
 */
function lct_explode_nth( $string, $needle, $offset )
{
	$newString = $string;
	$totalPos  = 0;
	$length    = strlen( $needle );

	for ( $i = 0; $i < $offset; $i ++ ) {
		$pos = strpos( $newString, $needle );

		// If you run out of string before you find all your needles
		if ( $pos === false ) {
			return false;
		}


		$newString = substr( $newString, $pos + $length );
		$totalPos  += $pos + $length;
	}


	return [ substr( $string, 0, $totalPos - $length ), substr( $string, $totalPos ) ];
}


/**
 * Remove the scheme and host of URLs in a string
 *
 * @param $html
 *
 * @return mixed
 * @since    4.2.2.10
 * @verified 2016.12.17
 */
function lct_remove_site_root( $html )
{
	$find            = []; //the find order matters!
	$root_site       = lct_url_root_site();
	$root_site_parts = parse_url( $root_site );


	$root_site_https_parts = [ 'scheme' => 'https', 'host' => $root_site_parts['host'] ];
	$find[]                = unparse_url( $root_site_https_parts ) . '/';


	$root_site_http_parts = [ 'scheme' => 'http', 'host' => $root_site_parts['host'] ];
	$find[]               = unparse_url( $root_site_http_parts ) . '/';


	$find[] = '//' . $root_site_parts['host'] . '/';


	$html = str_replace( $find, '/', $html );


	return $html;
}


/**
 * Remove all the possible scheme and host of URLs in a string
 *
 * @param $html
 *
 * @return mixed
 * @since    7.56
 * @verified 2019.08.29
 */
function lct_remove_site_root_all( $html )
{
	$find           = []; //the find order matters!
	$html           = lct_remove_site_root( $html );
	$sandbox_suffix = sprintf( '.%s.eetah.com', get_option( 'options_' . zxzacf( 'clientzz' ), 'none' ) );

	lct_update_setting( 'tmp_disable_dev_url', true );
	$root_site = lct_url_root_site();
	lct_update_setting( 'tmp_disable_dev_url', null );

	$root_site_parts        = parse_url( $root_site );
	$root_site_host_non_www = substr( $root_site_parts['host'], 4 );


	$root_site_https_parts = [ 'scheme' => 'https', 'host' => $root_site_parts['host'] ];
	$find[]                = unparse_url( $root_site_https_parts ) . '/';


	$root_site_https_parts = [ 'scheme' => 'https', 'host' => $root_site_host_non_www ];
	$find[]                = unparse_url( $root_site_https_parts ) . '/';


	$root_site_http_parts = [ 'scheme' => 'http', 'host' => $root_site_parts['host'] ];
	$find[]               = unparse_url( $root_site_http_parts ) . '/';


	$root_site_http_parts = [ 'scheme' => 'http', 'host' => $root_site_host_non_www ];
	$find[]               = unparse_url( $root_site_http_parts ) . '/';


	$find[] = '//' . $root_site_parts['host'] . '/';


	$find[] = '//' . $root_site_host_non_www . '/';


	foreach ( lct_sb_prefixes() as $sb ) {
		$root_site_https_parts = [ 'scheme' => 'https', 'host' => $sb . $root_site_host_non_www ];
		$find[]                = unparse_url( $root_site_https_parts ) . '/';
		$find[]                = unparse_url( $root_site_https_parts ) . $sandbox_suffix . '/';


		$root_site_https_parts = [ 'scheme' => 'https', 'host' => 'www.' . $sb . $root_site_host_non_www ];
		$find[]                = unparse_url( $root_site_https_parts ) . '/';
		$find[]                = unparse_url( $root_site_https_parts ) . $sandbox_suffix . '/';


		$root_site_http_parts = [ 'scheme' => 'http', 'host' => $sb . $root_site_host_non_www ];
		$find[]               = unparse_url( $root_site_http_parts ) . '/';
		$find[]               = unparse_url( $root_site_http_parts ) . $sandbox_suffix . '/';


		$root_site_http_parts = [ 'scheme' => 'http', 'host' => 'www.' . $sb . $root_site_host_non_www ];
		$find[]               = unparse_url( $root_site_http_parts ) . '/';
		$find[]               = unparse_url( $root_site_http_parts ) . $sandbox_suffix . '/';


		$find[] = '//' . $sb . $root_site_host_non_www . '/';
		$find[] = '//' . $sb . $root_site_host_non_www . $sandbox_suffix . '/';


		$find[] = '//www.' . $sb . $root_site_host_non_www . '/';
		$find[] = '//www.' . $sb . $root_site_host_non_www . $sandbox_suffix . '/';
	}


	$html = str_replace( $find, '/', $html );


	return $html;
}


/**
 * fix a number
 *
 * @param $number
 *
 * @return float
 * @since    0.0
 * @verified 2017.01.04
 */
function lct_clean_number_for_math( $number )
{
	$new_number = preg_replace( "/[^-0-9\.]/", '', $number );


	return (float) $new_number;
}


/**
 * I don't know
 *
 * @param $content
 * @param $max_chars
 *
 * @return string
 */
function lct_excerpt_of_string( $content, $max_chars )
{
	$content = substr( $content, 0, $max_chars );
	$pos     = strrpos( $content, " " );


	if ( $pos > 0 ) {
		$content = substr( $content, 0, $pos );
	}


	return $content;
}


/**
 * Strip out alpha & special chars, leaving only numbers
 *
 * @param $str
 *
 * @return mixed
 * @since    7.36
 * @verified 2016.11.22
 */
function lct_number_only( $str )
{
	$str = preg_replace( '/[^0-9.]/', '', $str );


	return $str;
}


/**
 * Strip out alpha & special chars, leaving only numbers, forces int
 *
 * @param $str
 *
 * @return mixed
 * @since    7.36
 * @verified 2016.11.22
 */
function lct_int_only( $str )
{
	$str = (int) lct_number_only( $str );


	return $str;
}


/**
 * Create a completely random slug for a post
 *
 * @param        $data
 * @param string $prefix
 *
 * @return mixed
 * @since    7.37
 * @verified 2020.11.25
 */
function lct_generate_random_post_name( $data, $prefix = 'h4f9' )
{
	$generate = false;


	if ( empty( $data['ID'] ) ) {
		if ( empty( $data['post_name'] ) ) {
			$generate = true;
		}
	} else {
		$post = get_post( $data['ID'] );


		if ( ! lct_is_wp_error( $post ) ) {
			if ( strpos( $post->post_name, $prefix ) !== 0 ) {
				$generate = true;
			}
		} else {
			$generate = true;
		}
	}


	if ( $generate ) {
		$data['post_name'] = lct_rand( $prefix );
	}


	return $data;
}
