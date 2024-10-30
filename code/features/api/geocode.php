<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Function to geocode an address or an address array, it will return false if unable to geocode the address
 *
 * @param array|string $address     You can provide a google_map address array -- Keys: address | place_id
 *                                  OR you can provide a full string of an address and the geocode will try to produce the google_map address array
 * @param bool         $whole_resp  Return the full response from Google
 * @param string       $result_type The element of the Google response you want to retrieve -- address_components | formatted_address | OTHERS
 *
 * @return array|false
 * @since    5.0
 * @verified 2024.02.20
 */
function lct_geocode( $address, $whole_resp = false, $result_type = null )
{
	/**
	 * Make sure we can run this function
	 */
	if (
		! lct_plugin_active( 'acf' )
		|| empty( $address )
		|| ! ( $api = lct_acf_get_option_raw( 'google_map_api_server' ) )
	) {
		return false;
	}


	/**
	 * Process the received address into something we can rely on
	 */
	$r          = false;
	$query_vars = [ 'key' => $api ];
	if ( is_string( $address ) ) {
		$address = [ 'address' => $address ];
	}

	if ( ! empty( $address['place_id'] ) ) {
		$query_vars['place_id'] = $address['place_id'];
	} elseif ( ! empty( $address['address'] ) ) {
		$url_ready_address     = urlencode( $address['address'] );
		$query_vars['address'] = $url_ready_address;
	} else {
		return false;
	}


	/**
	 * Return early if cache is found
	 */
	$cache_key = lct_cache_key( lct_cache_vars( $query_vars, $whole_resp, $result_type ) );
	if ( lct_isset_cache( $cache_key ) ) {
		return lct_get_cache( $cache_key );
	}


	/**
	 * Reach out to Google
	 */
	$url_parts           = [];
	$url_parts['scheme'] = 'https';
	$url_parts['host']   = 'maps.google.com';
	$url_parts['path']   = '/maps/api/geocode/json';
	$url_parts['query']  = unparse_query( $query_vars );
	$url                 = unparse_url( $url_parts );

	$resp = lct_get_json_thru_curl( $url );


	/**
	 * response status will be 'OK', if able to geocode given address
	 */
	if ( $resp['status'] == 'OK' ) {
		if ( $whole_resp ) {
			$r = $resp['results'][0];

			if ( $result_type ) {
				$r = $resp['results'][0][ $result_type ];
			}
		} else {
			//get the important data
			$lat               = $resp['results'][0]['geometry']['location']['lat'];
			$lng               = $resp['results'][0]['geometry']['location']['lng'];
			$formatted_address = $resp['results'][0]['formatted_address'];

			//verify if data is complete
			if ( $lat && $lng && $formatted_address ) {
				//put the data in the array
				$data_arr = [];

				array_push(
					$data_arr,
					$lat,
					$lng,
					$formatted_address
				);


				$r = $data_arr;
			}
		}
	} elseif ( $resp['status'] == 'OVER_QUERY_LIMIT' ) {
		lct_send_function_check_email( [ 'function' => 'lct_geocode OVER_QUERY_LIMIT', 'message' => $resp['error_message'] ] );
		lct_debug_to_error_log( $resp['status'] . ': ' . $resp['error_message'] );
	} elseif ( $resp['status'] == 'REQUEST_DENIED' ) {
		lct_send_function_check_email( [ 'function' => 'lct_geocode REQUEST_DENIED', 'message' => $resp['error_message'] ] );
		lct_debug_to_error_log( $resp['status'] . ': ' . $resp['error_message'] );
	}


	/**
	 * Save the value to the cache
	 */
	lct_set_cache( $cache_key, $r );


	return $r;
}


/**
 * Parse address component from an address or an address array
 *
 * @param array|string $address You can provide a google_map address array -- Keys: address | place_id
 *                              OR you can provide a full string of an address and the geocode will try to produce the google_map address array
 *
 * @return array|false
 * @since    5.0
 * @verified 2024.02.20
 */
function lct_parse_address_components( $address )
{
	if (
		empty( $address )
		|| ! ( $geocode = lct_geocode( $address, true, 'address_components' ) )
	) {
		return false;
	}


	$address_components = [];

	foreach ( $geocode as $tmp ) {
		$address_components[ $tmp['types'][0] ]['long_name']  = $tmp['long_name'];
		$address_components[ $tmp['types'][0] ]['short_name'] = $tmp['short_name'];
	}


	return $address_components;
}


/**
 * Get just the street address from a whole address
 *
 * @param string $address
 * @param string $type long_name|short_name
 *
 * @return string
 * @since    5.0
 * @verified 2019.07.18
 */
function lct_get_street_address( $address, $type = 'long_name' )
{
	$r          = '';
	$route      = '';
	$subpremise = '';


	if (
		empty( $address )
		|| ! ( $comp = lct_parse_address_components( $address ) )
		|| empty( $comp )
		|| empty( $comp['street_number'][ $type ] )
	) {
		return $r;
	}


	if ( ! empty( $comp['route'][ $type ] ) ) {
		$route = $comp['route'][ $type ];
	}


	if ( ! empty( $comp['subpremise'][ $type ] ) ) {
		$subpremise_prefix = '';
		$space             = ' ';
		$no_space          = [ '#' ];
		$tmp               = explode( $comp['subpremise'][ $type ] . ',', $address );

		if ( ! $tmp[1] ) {
			$tmp = explode( $comp['subpremise'][ $type ], $address );
		}


		if ( $tmp[1] ) {
			$find_and_replace = [
				PHP_EOL                              => ' ',
				'.'                                  => '',
				$comp['street_number']['long_name']  => '',
				$comp['route']['long_name']          => '',
				$comp['street_number']['short_name'] => '',
				$comp['route']['short_name']         => '',
			];
			$fnr              = lct_create_find_and_replace_arrays( $find_and_replace );


			$subpremise_prefix = preg_replace( "#\r|\n#", ' ', $tmp[0] );
			$subpremise_prefix = str_replace( $fnr['find'], $fnr['replace'], $subpremise_prefix );
			$subpremise_prefix = trim( $subpremise_prefix );


			if ( strpos( $subpremise_prefix, ' ' ) !== false ) {
				$subpremise_prefix_tmp = explode( ' ', $subpremise_prefix );
				$subpremise_prefix     = trim( end( $subpremise_prefix_tmp ) );
			}


			if ( in_array( $subpremise_prefix, $no_space ) ) {
				$space = '';
			}
		}


		$subpremise = ' ' . $subpremise_prefix . $space . $comp['subpremise'][ $type ];
	}


	$r = $comp['street_number'][ $type ] . ' ' . $route . $subpremise;


	return $r;
}


/**
 * Get just the city from a whole address
 *
 * @param string|bool $address
 * @param string      $type
 *
 * @return string
 * @since    5.0
 * @verified 2019.07.12
 */
function lct_get_city( $address, $type = 'long_name' )
{
	if (
		empty( $address )
		|| ! ( $comp = lct_parse_address_components( $address ) )
		|| empty( $comp['locality'][ $type ] )
	) {
		return '';
	}


	return $comp['locality'][ $type ];
}


/**
 * Get just the zip code from a whole address
 *
 * @param string|bool $address
 * @param string      $type
 *
 * @return string
 * @since    5.0
 * @verified 2019.07.12
 */
function lct_get_zip( $address, $type = 'long_name' )
{
	if (
		empty( $address )
		|| ! ( $comp = lct_parse_address_components( $address ) )
		|| empty( $comp['postal_code'][ $type ] )
	) {
		return '';
	}


	return $comp['postal_code'][ $type ];
}


/**
 * Get just the state from a whole address
 *
 * @param string|bool $address
 * @param string      $type
 *
 * @return string
 * @since    5.0
 * @verified 2019.07.12
 */
function lct_get_state( $address, $type = 'long_name' )
{
	if (
		empty( $address )
		|| ! ( $comp = lct_parse_address_components( $address ) )
		|| empty( $comp['administrative_area_level_1'][ $type ] )
	) {
		return '';
	}


	return $comp['administrative_area_level_1'][ $type ];
}


/**
 * Get just the full formatted address from a whole address
 *
 * @param string $address
 * @param string $type
 * @param string $break
 *
 * @return string
 * @since    5.37
 * @verified 2019.07.12
 */
function lct_get_full_address( $address, $type = 'long_name', $break = ', ' )
{
	if (
		empty( $address )
		|| ! ( $comp = lct_parse_address_components( $address ) )
		|| empty( $comp )
	) {
		return '';
	}


	$address = lct_get_street_address( $address, $type ) . $break . lct_get_city( $address, $type ) . ', ' . lct_get_state( $address, $type ) . ' ' . lct_get_zip( $address, $type );


	return $address;
}
