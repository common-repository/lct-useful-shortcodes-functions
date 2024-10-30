<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) )
	exit;


/**
 * LCT Referenced
 *
 * @verified 2019.07.15
 * @checked  2019.07.15
 */


/**
 * alias of lct()->has_setting()
 *
 * @param string $name
 *
 * @return bool
 * @since    LCT 2019.18
 * @verified 2019.07.12
 */

function lct_has_setting( $name = '' ) {
	return lct()->has_setting( $name );
}


/**
 * alias of lct()->get_setting()
 *
 * @param string $name
 *
 * @return mixed
 * @since    LCT 2019.18
 * @verified 2019.07.12
 */
function lct_raw_setting( $name ) {
	return lct()->get_setting( $name );
}


/**
 * Returns the changed setting name if available
 *
 * @param string $name
 *
 * @return string
 * @since    LCT 2019.18
 * @verified 2019.07.12
 */
function lct_validate_setting( $name = '' ) {
	return apply_filters( 'lct/validate_setting', $name );
}


/**
 * alias of lct()->get_setting()
 *
 * @param string $name
 * @param mixed  $value
 *
 * @return mixed
 * @since    LCT 7.38
 * @verified 2019.07.12
 */
function lct_get_setting( $name, $value = null ) {
	$name = lct_validate_setting( $name );


	if ( lct_has_setting( $name ) )
		$value = lct_raw_setting( $name );


	/**
	 * Filter for 3rd party customization
	 */
	if ( substr( $name, 0, 1 ) !== '_' )
		$value = apply_filters( "lct/settings/{$name}", $value );


	return $value;
}


/**
 * alias of  lct()->update_setting()
 *
 * @param string $name
 * @param mixed  $value
 *
 * @return bool
 * @since    LCT 7.38
 * @verified 2017.07.31
 */
function lct_update_setting( $name, $value ) {
	$name = lct_validate_setting( $name );


	return lct()->update_setting( $name, $value );
}


/**
 * This function will add a value into the settings array found in the lct object
 *
 * @param string $name
 * @param mixed  $value
 *
 * @return bool
 * @since    LCT 7.38
 * @verified 2019.07.12
 */
function lct_append_setting( $name, $value ) {
	$setting = lct_raw_setting( $name );


	if ( ! is_array( $setting ) )
		$setting = [];


	$setting[] = $value;


	/**
	 * Cleanup array
	 */
	if (
		count( $setting ) > 1 &&
		isset( $setting[0] ) &&
		! is_array( $setting[0] )
	) {
		$setting = array_values( array_unique( $setting ) );
	}


	return lct_update_setting( $name, $setting );
}


/**
 * alias of lct()->get_data()
 *
 * @param string $name
 *
 * @return mixed
 * @since    LCT 2019.18
 * @verified 2019.07.12
 */
function lct_get_data( $name ) {
	return lct()->get_data( $name );
}


/**
 * alias of  lct()->set_data()
 *
 * @param string $name
 * @param mixed  $value
 *
 * @return bool
 * @since    LCT 2019.18
 * @verified 2019.07.12
 */
function lct_set_data( $name, $value ) {
	return lct()->set_data( $name, $value );
}


/**
 * This function will add a value into the data array found in the lct object
 *
 * @param string $name
 * @param mixed  $value
 *
 * @return bool
 * @since    LCT 2019.18
 * @verified 2019.07.12
 */
function lct_append_data( $name, $value ) {
	$data = lct_get_data( $name );


	if ( ! is_array( $data ) )
		$data = [];


	$data[] = $value;


	/**
	 * Cleanup array
	 */
	if (
		count( $data ) > 1 &&
		isset( $data[0] ) &&
		! is_array( $data[0] )
	) {
		$data = array_values( array_unique( $data ) );
	}


	return lct_set_data( $name, $data );
}


/**
 * This will return the path to a file within the plugin folder
 *
 * @param $path
 *
 * @return string
 * @since    LCT 7.38
 * @verified 2019.07.12
 */
function lct_get_path( $path = '' ) {
	return lct_raw_setting( 'path' ) . $path;
}


/**
 * This will return the root path to a file within the plugin folder
 *
 * @param $path
 *
 * @return string
 * @since    LCT 7.50
 * @verified 2019.07.12
 */
function lct_get_root_path( $path = '' ) {
	return lct_raw_setting( 'root_path' ) . $path;
}


/**
 * This will return the url to a file within the plugin folder
 *
 * @param $url
 *
 * @return string
 * @since    LCT 7.50
 * @verified 2019.07.12
 */
function lct_get_url( $url = '' ) {
	return lct_raw_setting( 'url' ) . $url;
}


/**
 * This will return the root url to a file within the plugin folder
 *
 * @param $url
 *
 * @return string
 * @since    LCT 7.50
 * @verified 2019.07.12
 */
function lct_get_root_url( $url = '' ) {
	return lct_raw_setting( 'root_url' ) . $url;
}


/**
 * This will include a file
 *
 * @param $file
 *
 * @return bool
 * @since    LCT 7.38
 * @verified 2017.07.31
 */
function lct_include( $file ) {
	$r    = false;
	$path = lct_get_path( $file );


	if ( file_exists( $path ) ) {
		/** @noinspection PhpIncludeInspection */
		include_once( $path );

		$r = true;
	}


	return $r;
}


/**
 * This will include a file
 *
 * @param $file
 *
 * @return bool
 * @since    LCT 2017.13
 * @verified 2017.07.31
 */
function lct_root_include( $file ) {
	$r    = false;
	$path = lct_get_root_path( $file );


	if ( file_exists( $path ) ) {
		/** @noinspection PhpIncludeInspection */
		include_once( $path );

		$r = true;
	}


	return $r;
}


/**
 * This will include a file and create the main global instance of class contained in said file
 *
 * @param string $file
 * @param string $class_suffix
 * @param array  $args
 *
 * @return bool
 * @since    LCT 7.38
 * @verified 2019.07.15
 */
function lct_load_class( $file, $class_suffix, $args = [] ) {
	$r    = false;
	$args = wp_parse_args( $args, lct_load_class_default_args() );


	if ( $args['plugin'] )
		$class_suffix = "{$args['plugin']}_{$class_suffix}";
	else if ( $args['dir'] )
		$class_suffix = "{$args['dir']}_{$class_suffix}";


	if (
		! lct_did( "load_class_{$class_suffix}" ) &&
		lct_include( $file )
	) {
		$class_suffix         = str_replace( [ '-', '/', '_plugins_' ], '_', $class_suffix );
		$args['class_suffix'] = $class_suffix;
		$class                = zxzu( $class_suffix );


		$r = lct_get_instance( $class, $args );


		if ( $args['globalize'] )
			lct()->{$class_suffix} = $r;


		if ( $args['globalize_legacy'] ) {
			global ${$class};
			${$class} = $r;
		}
	}


	return $r;
}


/**
 * The default array for load_class
 *
 * @return array
 * @since    LCT 7.38
 * @verified 2019.07.15
 */
function lct_load_class_default_args() {
	$r = [
		'dir'              => null,
		'plugin'           => null,
		'class_suffix'     => null,
		'globalize'        => false,
		'globalize_legacy' => false,
		'load_parent'      => false,
	];


	return $r;
}


/**
 * wrapper so that we can easily track our options
 *
 * @param       $option
 * @param mixed $value
 *
 * @return mixed
 * @since    LCT 7.56
 * @verified 2017.07.31
 */
function lct_get_option( $option, $value = false ) {
	$option = zxzu( $option );


	return get_option( $option, $value );
}


/**
 * wrapper so that we can easily track our options
 *
 * @param       $option
 * @param mixed $value
 * @param null  $autoload
 *
 * @return bool
 * @since    LCT 7.56
 * @verified 2017.07.31
 */
function lct_update_option( $option, $value, $autoload = null ) {
	$option = zxzu( $option );


	return update_option( $option, $value, $autoload );
}


/**
 * wrapper so that we can easily track our options
 *
 * @param      $option
 *
 * @return bool
 * @since    LCT 7.56
 * @verified 2017.07.31
 */
function lct_delete_option( $option ) {
	$option = zxzu( $option );


	return delete_option( $option );
}


/**
 * Check if a function has already been ran
 *
 * @param string $function
 * @param string $class
 *
 * @return bool
 * @since    LCT 7.38
 * @verified 2019.07.12
 */
function lct_did( $function = null, $class = null ) {
	$r = false;


	/**
	 * Grab the info from backtrace
	 */
	if (
		! $function &&
		! $class
	) {
		$bt       = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 );
		$function = $bt[1]['function'];


		if ( ! empty( $bt[1]['class'] ) )
			$function = $bt[1]['class'] . '_' . $function;
	}


	/**
	 * If class is set, prepend it to the function
	 */
	if ( $class )
		$function = $class . '_' . $function;


	/**
	 * Find out if it exists
	 */
	$data = lct_get_data( '_did' );

	if ( ! is_array( $data ) )
		$data = [];


	if ( ! empty( $data[ $function ] ) ) {
		$r = true;//yep, already did
	} else {
		$data[ $function ] = true; //nope, so set it as did


		lct_set_data( '_did', $data );
	}

	/**
	 * Sometimes we want to halt our loading efforts
	 */
	if ( lct_raw_setting( 'stop_loading' ) )
		$r = true;


	return $r;
}


/**
 * Undo a did function setting
 *
 * @param null $function
 * @param null $class
 *
 * @since    LCT 2018.59
 * @verified 2019.07.15
 */
function lct_undid( $function = null, $class = null ) {
	/**
	 * Grab the info from backtrace
	 */
	if (
		! $function &&
		! $class
	) {
		$bt       = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 );
		$function = $bt[1]['function'];


		if ( ! empty( $bt[1]['class'] ) )
			$function = $bt[1]['class'] . '_' . $function;
	}


	/**
	 * If class is set, prepend it to the function
	 */
	if ( $class )
		$function = $class . '_' . $function;


	unset( lct()->data['_did'][ $function ] );
}
