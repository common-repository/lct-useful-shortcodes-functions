<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Start your OLD engines
 */
global $g_lct;
/** @noinspection PhpDeprecationInspection */
$g_lct = new g_lct;

/**
 * Parent class for this whole plugin
 *
 * @deprecated 2017.33
 * @verified   2017.04.20
 */
class g_lct
{
	/**
	 * Include a file and load up the class(es) that are in it
	 *
	 * @param        $tag
	 * @param        $class
	 * @param int    $priority
	 * @param        $include
	 * @param string $callback
	 * @param string $load_type
	 *
	 * @return bool
	 * @deprecated 2017.33
	 * @since      5.38
	 * @verified   2016.11.25
	 */
	function load_class( $tag, $class, $priority = 10, $include = '', $load_type = 'non_ajax', $callback = 'init' )
	{
		$load_r_up = false;


		if ( ! $priority ) {
			$priority = 10;
		}


		switch ( $load_type ) {
			case 'every': //Absolutely ALL THE TIME
			case 'everywhere':
				$load_r_up = true;
				break;


			case 'all': //Anytime on the front-end
			case 'always':
				if ( ! is_admin() ) {
					$load_r_up = true;
				}
				break;


			case 'all_admin': //Anytime on the back-end
			case 'always_admin':
				if ( is_admin() ) {
					$load_r_up = true;
				}
				break;


			case 'ajax': //All ajax calls
				if ( lct_doing() ) {
					$load_r_up = true;
				}
				break;


			case 'non_ajax': //Anytime on the front-end, except ajax
				if ( ! lct_doing() && ! is_admin() ) {
					$load_r_up = true;
				}
				break;


			case 'non_ajax_admin': //Anytime on the back-end, except ajax
				if ( ! lct_doing() && is_admin() ) {
					$load_r_up = true;
				}
				break;


			case 'dev': //Only on a dev site
				if ( lct_is_dev() ) {
					$load_r_up = true;
				}
				break;


			case 'sb': //Only on a sandbox site
				if ( lct_is_sb() ) {
					$load_r_up = true;
				}
				break;


			case 'dev_or_sb': //Only on a dev/sandbox site
				if ( lct_is_dev_or_sb() ) {
					$load_r_up = true;
				}
				break;
		}


		if ( $load_r_up ) {
			if ( $include ) {
				include_once( $include );
			}

			add_action( "{$tag}", [ $class, $callback ], $priority );
		}


		return $load_r_up;
	}
}


/**
 * Force a message in the error_log
 *
 * @param        $function
 * @param        $replacement
 * @param        $version
 * @param        $message
 *
 * @since    7.49
 * @verified 2018.03.20
 */
function lct_force_trigger_error_deprecated_action( $function, $replacement, $version, $message = '' )
{
	$type = 'action';


	if ( ! is_null( $replacement ) ) {
		lct_deprecated_error_log( sprintf( 'ACTION \'%1$s\' is DEPRECATED since version %2$s! Use \'%3$s\' instead. %4$s', $function, $version, $replacement, $message ), $type, $function );
	} else {
		lct_deprecated_error_log( sprintf( 'ACTION \'%1$s\' is DEPRECATED since version %2$s with no alternative available. %3$s', $function, $version, $message ), $type, $function );
	}
}


/**
 * Force a message in the error_log
 *
 * @param        $function
 * @param        $replacement
 * @param        $version
 * @param        $message
 *
 * @since    7.49
 * @verified 2018.03.20
 */
function lct_force_trigger_error_deprecated_filter( $function, $replacement, $version, $message = '' )
{
	$type = 'filter';


	if ( ! is_null( $replacement ) ) {
		lct_deprecated_error_log( sprintf( 'FILTER \'%1$s\' is DEPRECATED since version %2$s! Use \'%3$s\' instead. %4$s', $function, $version, $replacement, $message ), $type, $function );
	} else {
		lct_deprecated_error_log( sprintf( 'FILTER \'%1$s\' is DEPRECATED since version %2$s with no alternative available. %3$s', $function, $version, $message ), $type, $function );
	}
}


/**
 * Force a message in the error_log
 *
 * @param        $function
 * @param        $replacement
 * @param        $version
 *
 * @since    7.42
 * @verified 2018.03.20
 */
function lct_force_trigger_error_deprecated_function( $function, $replacement, $version )
{
	$type = 'function';


	if ( ! is_null( $replacement ) ) {
		lct_deprecated_error_log( sprintf( '\'%1$s()\' is DEPRECATED since version %2$s! Use \'%3$s\' instead.', $function, $version, $replacement ), $type, $function );
	} else {
		lct_deprecated_error_log( sprintf( '\'%1$s()\' is DEPRECATED since version %2$s with no alternative available.', $function, $version ), $type, $function );
	}
}


/**
 * Force a message in the error_log
 *
 * @param        $function
 * @param        $replacement
 * @param        $version
 * @param        $message
 *
 * @since    2017.34
 * @verified 2018.03.20
 */
function lct_force_trigger_error_deprecated_shortcode( $function, $replacement, $version, $message = '' )
{
	$type = 'shortcode';


	if ( ! is_null( $replacement ) ) {
		lct_deprecated_error_log( sprintf( 'SHORTCODE [%1$s] is DEPRECATED since version %2$s! Use %3$s instead. %4$s', $function, $version, $replacement, $message ), $type, $function );
	} else {
		lct_deprecated_error_log( sprintf( 'SHORTCODE [%1$s] is DEPRECATED since version %2$s with no alternative available. %3$s', $function, $version, $message ), $type, $function );
	}
}


/**
 * Send DEPRECATED info to the error_log
 *
 * @param $reason
 * @param $type
 * @param $dep_function
 *
 * @since    7.42
 * @verified 2020.09.09
 */
function lct_deprecated_error_log( $reason, $type, $dep_function )
{
	$dep_function_key = 0;
	$message          = [];
	$sep              = "\n==========================>";
	$file             = null;
	$file_2           = null;
	$bad_filters      = [];
	$backtrace        = debug_backtrace();


	/**
	 * Get the correct backtrace starting point
	 */
	if ( $type === 'function' ) {
		foreach ( $backtrace as $bt_key => $bt ) {
			if (
				isset( $bt['function'] )
				&& $bt['function'] === $dep_function
			) {
				$dep_function_key = $bt_key;


				break;
			}
		}
	} elseif ( in_array( $type, [ 'filter', 'action' ] ) ) {
		global $wp_filter;


		if ( isset( $wp_filter[ $dep_function ] ) ) {
			$function_details = $wp_filter[ $dep_function ];


			if ( ! empty( $function_details->callbacks ) ) {
				foreach ( $function_details->callbacks as $callback ) {
					foreach ( $callback as $callback_function ) {
						if ( is_array( $callback_function['function'] ) ) {
							$bad_filters[] = get_class( $callback_function['function'][0] ) . '{}' . $callback_function['function'][1] . '()';


						} else {
							if ( strpos( $callback_function['function'], 'deprecated' ) === 0 ) {
								continue;
							}


							$bad_filters[] = $callback_function['function'];
						}
					}
				}
			}
		}


		foreach ( $backtrace as $bt_key => $bt ) {
			if (
				(
					(
						$type === 'filter'
						&& isset( $bt['function'] )
						&& $bt['function'] === 'apply_filters'
					)
					|| (
						$type === 'action'
						&& isset( $bt['function'] )
						&& $bt['function'] === 'do_action'
					)
				)
				&& isset( $bt['args'] )
				&& $bt['args'][0] === $dep_function
				&& isset( $bt['file'] )
				&& strpos( $bt['file'], 'deprecated' ) === false
			) {
				$dep_function_key = $bt_key;


				break;
			}
		}
	}


	if ( $dep_function_key ) {
		/**
		 * Set our plus 1
		 */
		$dep_function_key_plus_1 = ( $dep_function_key + 1 );


		/**
		 * Extract all the important information
		 */
		$file = lct_strip_path( $backtrace[ $dep_function_key ]['file'] ) . ':' . $backtrace[ $dep_function_key ]['line'];
		isset( $backtrace[ $dep_function_key_plus_1 ]['class'] ) ? $class = $backtrace[ $dep_function_key_plus_1 ]['class'] . '{} ' : $class = '';
		isset( $backtrace[ $dep_function_key_plus_1 ]['function'] ) ? $function = $backtrace[ $dep_function_key_plus_1 ]['function'] . '() ' : $function = '';
		$file_2 = $class . $function . lct_strip_path( $backtrace[ $dep_function_key_plus_1 ]['file'] ) . ':' . $backtrace[ $dep_function_key_plus_1 ]['line'];
	}


	/**
	 * Create the message
	 */
	$message[] = zxzb( ' DEPRECATED: ' . $reason );

	if ( $file ) {
		$message[] = sprintf( '%slocated at: %s', $sep, $file );
	}

	if ( $file_2 ) {
		$message[] = sprintf( '%scalled from: %s', $sep, $file_2 );
	}

	if ( ! empty( $bad_filters ) ) {
		foreach ( $bad_filters as $bad_filter ) {
			$message[] = sprintf( '%sRemove/Update %s: %s', $sep, strtoupper( $type ), $bad_filter );
		}
	}

	$message[] = sprintf( '%son page: %s', $sep, $_SERVER['REQUEST_URI'] );

	$message[] = "\n";


	/**
	 * Send an email
	 */
	if ( lct_is_user_a_dev() ) {
		$args = [
			'from_name'         => zxzb( ' Deprecated ' . ucwords( $type ) ),
			'to'                => 'info@ircary.com',
			'subject'           => sprintf( 'Deprecated %s is Running on %s', ucwords( $type ), get_option( 'home' ) ),
			'message'           => sprintf( 'Deprecated %2$s (%3$s) is running on <a href="%1$s">%1$s</a>.<br /><br />%4$s', get_option( 'home' ), $type, $dep_function, implode( '<br />', $message ) ),
			'send_limiter'      => $dep_function,
			'send_limiter_time' => DAY_IN_SECONDS,
		];
		lct_quick_send_email( $args );
	}


	/**
	 * Print info to the site's error_log
	 */
	error_log( implode( '', $message ) );
}


/**
 * Run a deprecated filter so that we can produce an error in the error_log
 *
 * @param $tag
 *
 * @since    2017.34
 * @verified 2018.03.20
 */
function lct_shutdown_deprecated( $tag )
{
	$dep_tag = str_replace( '/', '_', $tag );


	remove_filter( $tag, 'deprecated_' . $dep_tag, 2 );


	if ( has_filter( $tag ) ) {
		add_filter( "{$tag}", 'deprecated_' . $dep_tag, 2 );
		apply_filters( "{$tag}", false );
	}
}


/**
 * Run a deprecated action so that we can produce an error in the error_log
 *
 * @param $deprecated_tag
 *
 * @since    2017.34
 * @verified 2018.03.20
 */
function lct_shutdown_deprecated_action( $deprecated_tag )
{
	$dep_tag = str_replace( '/', '_', $deprecated_tag );


	remove_action( $deprecated_tag, 'deprecated_' . $dep_tag, 2 );


	if ( has_action( $deprecated_tag ) ) {
		add_action( "{$deprecated_tag}", 'deprecated_' . $dep_tag, 2 );


		/**
		 * @date     0.0
		 * @since    0.0
		 * @verified 2021.08.27
		 */
		do_action( "{$deprecated_tag}", false );
	}
}
