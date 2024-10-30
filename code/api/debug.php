<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Start a timer
 *
 * @since    5.32
 * @verified 2016.11.25
 */
function lct_timer_start()
{
	lct_update_setting( 'debug_timer', microtime( true ) );
}


/**
 * Stop a timer and display the data
 *
 * @param string $type       :: return|console|print|error_log|time|seconds
 * @param string $track_type :: function
 *
 * @return string|bool
 * @since    5.32
 * @verified 2021.06.15
 */
function lct_timer_end( $type = 'return', $track_type = 'function' )
{
	$r                        = true;
	$run_time                 = '';
	$time_elapsed             = number_format( microtime( true ) - lct_get_setting( 'debug_timer' ), 8 );
	$time_elapsed_millisecond = $time_elapsed * 1000;


	if ( $track_type ) {
		$debug = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 );


		$run_time_sec = sprintf( '%0.8f seconds (%0.4f milliseconds)', $time_elapsed, $time_elapsed_millisecond );
		$run_time     = sprintf( '%s() Run Time :: %s', $debug[1][ $track_type ], $run_time_sec );
	}


	switch ( $type ) {
		case 'console':
			lct_send_to_console( $run_time );
			break;


		case 'print':
			printf( '<p>%s</p>', $run_time );
			exit;


		case 'return':
			$r = $run_time;
			break;


		case 'error_log':
			error_log( $run_time );
			break;


		case 'time':
			$r = $time_elapsed_millisecond;
			break;


		case 'seconds':
			$r = sprintf( '%0.1f seconds (%0.4f milliseconds)', $time_elapsed, $time_elapsed_millisecond );
			break;
	}


	return $r;
}


if ( ! function_exists( 'P_R' ) ) {
	/**
	 * Used instead of print_r() function. It gives you a better understanding of how arrays are laid out.
	 *
	 * @param            $var
	 * @param string     $name
	 * @param bool|false $return
	 *
	 * @return bool|string
	 * @since    0.0
	 * @verified 2022.10.21
	 */
	function P_R( $var, $name = 'Name Not Set', $return = false )
	{
		$skip = [ 'HTTP_COOKIE' ];
		$c    = 'odd';

		$h = '<table class="P_R" style="max-width: 1200px;width: 100%;margin: 0 auto;">';
		$h .= '<tr><th class="' . $c . '" colspan="2">' . $name . '</th></tr>';


		foreach ( $var as $k => $v ) {
			if ( in_array( $k, $skip ) && $k !== 0 ) {
				continue;
			}

			if ( $c == 'even' ) {
				$c = 'odd';
			} else {
				$c = 'even';
			}

			$h .= '<tr>';
			$h .= '<td class="' . $c . '">';
			$h .= $k;
			$h .= '</td>';
			$h .= '<td class="' . $c . '">';

			$h .= P_R_loop( $v );

			$h .= '</td>';
			$h .= '</tr>';
		}

		if ( ! $var ) {
			$h .= '<tr><td class="' . $c . '">none</td></tr>';
		}

		$h .= '</table>';

		$h .= P_R_STYLE();


		if ( $return === true ) {
			return $h;
		}


		echo $h;


		if ( $return === 'exit' ) {
			exit;
		}


		return false;
	}


	/**
	 * Loop thru the table
	 *
	 * @param string|array $loop
	 *
	 * @return string
	 * @date     2022.10.21
	 * @since    2022.10
	 * @verified 2022.10.21
	 */
	function P_R_loop( $loop )
	{
		$h = '';
		$c = 'even';


		if ( is_array( $loop ) ) {
			$h .= '<table style="width:100%;margin:0 auto;">';
			foreach ( $loop as $k => $v ) {
				if ( $c == 'even' ) {
					$c = 'odd';
				} else {
					$c = 'even';
				}

				$h .= '<tr>';
				$h .= '<td class="' . $c . '">';
				$h .= $k;
				$h .= '</td>';
				$h .= '<td class="' . $c . '">';

				if ( is_array( $v ) ) {
					$h .= P_R_loop( $v );
				} else {
					$h .= $v;
				}

				$h .= '</td>';
				$h .= '</tr>';
			}

			$h .= '</table>';
		} else {
			$h .= $loop;
		}


		return $h;
	}
}


if ( ! function_exists( 'P_R_O' ) ) {
	/**
	 * For Objects - Used instead of print_r() function. It gives you a better understanding of how arrays are laid out.
	 *
	 * @param $var
	 *
	 * @since    0.0
	 * @verified 2016.11.29
	 */
	function P_R_O( $var )
	{
		echo '<pre>';
		print_r( $var );
		echo '</pre>';
	}
}


if ( ! function_exists( 'P_R_STYLE' ) ) {
	/**
	 * Creates the table styling for the P_R function
	 *
	 * @return string
	 * @since    0.0
	 * @verified 2016.11.29
	 */
	function P_R_STYLE()
	{
		return '<style>
		.P_R p{
			text-align: center;
			margin: 0;
			padding: 0;
		}

		.P_R input[type="file"]{
			border: 1px solid #BBB;
		}

		.P_R td{
			padding: 5px;
			margin: 2px 15px;
		}

		.P_R .even{
			background-color: #aaa;
		}

		.P_R .odd{
			background-color: #ccc;
		}
		</style>';
	}
}


/**
 * Store a debug row in the options table
 *
 * @param        $data
 * @param string $extra
 *
 * @since    0.0
 * @verified 2017.06.08
 */
function lct_debug( $data, $extra = '' )
{
	if ( $extra ) {
		$extra = '_' . $extra;
	}


	lct_update_option( 'debug' . $extra, $data, false );
}


/**
 * Send debug info to the site's error_log
 *
 * @param string|array|WP_Error|mixed $data
 *
 * @since    0.0
 * @verified 2023.11.07
 */
function lct_debug_to_error_log( $data )
{
	/**
	 * Vars
	 */
	global $lct_debug_to_error_log;

	$sep = "=========================> ";
	if ( ! is_string( $data ) ) {
		if (
			is_wp_error( $data )
			&& ( $error_code = $data->get_error_code() )
		) {
			$data_new = sprintf( '[n][sep]Error Code:             %s', $error_code );


			if ( $tmp = $data->get_error_message( $error_code ) ) {
				$data_new .= sprintf( '[n][sep]Error Message:          %s', $tmp );
			}


			if ( empty( $error_data = $data->get_error_data( $error_code ) ) ) {
				$error_data = [];
			}


			function_exists( 'afwp_acf_json_encode' ) ? $error_data = afwp_acf_json_encode( $error_data ) : $error_data = wp_json_encode( $error_data );


			$data_new .= sprintf( '[n][sep]Error Data:             %s', $error_data );


			$data = 'WP_Error{} :: ' . $error_code . $data_new;
		} else {
			function_exists( 'afwp_acf_json_encode' ) ? $data = afwp_acf_json_encode( $data ) : $data = wp_json_encode( $data );
		}
	}
	$data      = str_replace( [ '[n]', '[sep]' ], [ "\n", $sep ], $data );
	$debug_key = md5( $data );


	/**
	 * Only send the error to the error_log once per page load
	 */
	if ( ! empty( $lct_debug_to_error_log[ $debug_key ] ) ) {
		return;
	}


	/**
	 * Error backtrace details
	 */
	$backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
	if (
		! empty( $backtrace[1]['function'] )
		&& $backtrace[1]['function'] === 'create_logged_error'
	) {
		$file     = lct_strip_path( $backtrace[2]['file'] ) . ':' . $backtrace[2]['line'];
		$start_at = 3;
	} else {
		$file     = lct_strip_path( $backtrace[0]['file'] ) . ':' . $backtrace[0]['line'];
		$start_at = 1;
	}

	$file_2_thru_x = [];
	foreach ( $backtrace as $k => $bt ) {
		if (
			$k < $start_at
			|| empty( $bt['file'] )
			|| empty( $bt['line'] )
		) {
			continue;
		}

		$file_2_thru_x[] = sprintf( '%scalled from:            %s:%s', $sep, lct_strip_path( $bt['file'] ), $bt['line'] );
	}
	$file_2_thru_x = implode( "\n", $file_2_thru_x );


	/**
	 * Set user
	 */
	$user = 'guest';
	if (
		get_current_user_id()
		&& ( $tmp = wp_get_current_user() )
	) {
		$user = $tmp->display_name . ' (' . $tmp->ID . ')';
	}


	/**
	 * Set request type
	 */
	$request_type = [];
	function_exists( 'lct_doing_ajax' ) ? ( lct_doing_ajax() ? $tmp = 'YES' : $tmp = 'no' ) : $tmp = 'unknown';
	$request_type[] = 'ajax: ' . $tmp;
	function_exists( 'lct_doing_api' ) ? ( lct_doing_api() ? $tmp = 'YES' : $tmp = 'no' ) : $tmp = 'unknown';
	$request_type[] = 'api: ' . $tmp;
	function_exists( 'lct_doing_cron' ) ? ( lct_doing_cron() ? $tmp = 'YES' : $tmp = 'no' ) : $tmp = 'unknown';
	$request_type[] = 'cron: ' . $tmp;


	/**
	 * Set REQUEST
	 */
	function_exists( 'afwp_acf_json_encode' ) ? $request = afwp_acf_json_encode( $_REQUEST ) : $request = wp_json_encode( $_REQUEST );


	/**
	 * Set REQUEST vars
	 */
	$REQUEST_URI     = $_SERVER['REQUEST_URI'] ?? 'unknown';
	$HTTP_REFERER    = $_SERVER['HTTP_REFERER'] ?? 'unknown';
	$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
	$request_type    = implode( ' :: ', $request_type );


	/**
	 * Make the message
	 */
	$message   = [];
	$message[] = 'lct_debug Fatal error: ' . $data;
	$message[] = $sep . 'originating file:       ' . $file;
	$message[] = $file_2_thru_x;
	$message[] = $sep . 'on page:                ' . $REQUEST_URI;
	$message[] = $sep . 'referer:                ' . $HTTP_REFERER;
	$message[] = $sep . 'user agent:             ' . $HTTP_USER_AGENT;
	$message[] = $sep . 'logged in user:         ' . $user;
	$message[] = $sep . 'request type:           ' . $request_type;
	$message[] = $sep . 'request:                ' . $request;
	$message[] = $sep . 'END OF THIS ERROR       END OF THIS ERROR' . "\n\n";


	error_log( implode( "\n", $message ) );


	/**
	 * Finish
	 */
	if ( ! $lct_debug_to_error_log ) {
		$lct_debug_to_error_log = [];
	}
	$lct_debug_to_error_log[ $debug_key ] = true;
}


/**
 * Send debug info to the browser's console
 *
 * @param      $data
 * @param null $label
 *
 * @since    0.0
 * @verified 2016.11.29
 */
function lct_send_to_console( $data, $label = null )
{
	if ( empty( $data ) ) {
		return;
	}


	$console = [];


	if ( ! empty( $label ) ) {
		$label = '( ' . $label . ' ) ';
	}


	if ( is_object( $data ) ) {
		$data = (array) $data;
	}


	if ( is_array( $data ) ) {
		foreach ( $data as $k => $v ) {
			//Weird wc bug
			if ( strpos( $k, 'product_categories' ) !== false ) {
				continue;
			}

			if ( is_array( $v ) ) {
				if ( ! empty( $v ) ) {
					$sub_array = '(array) ';

					foreach ( $v as $sub_k => $sub_v ) {
						$sub_array .= '[' . $sub_k . '] = ' . $sub_v;
					}
					$v = $sub_array;
				} else {
					$v = '(array) __EMPTY__';
				}
			}

			if ( $v === '' || ! strlen( $v ) ) {
				$v = '__EMPTY__';
			}

			$console[] = lct_console_log_sprint( zxzu() . "debug: ARRAY{$label}[{$k}] = {$v}" );
		}
	} else {
		$console[] = lct_console_log_sprint( zxzu() . "debug: {$label}{$data}" );
	}


	$script = lct_return( $console );

	if ( is_admin() ) {
		/**
		 * #1
		 * @date     0.0
		 * @since    0.0
		 * @verified 2021.08.27
		 */
		do_action( 'lct_jq_doc_ready_add', $script, 'admin_footer' );
	} else {
		/**
		 * #2
		 * @date     0.0
		 * @since    0.0
		 * @verified 2021.08.27
		 */
		do_action( 'lct_jq_doc_ready_add', $script, 'wp_footer' );
	}
}


/**
 * Process a variable to it can be added to the console
 *
 * @param $data
 *
 * @return string
 * @since    0.0
 * @verified 2016.11.29
 */
function lct_console_log_sprint( $data )
{
	$r = false;


	if ( ! empty( $data ) ) {
		$r = sprintf( 'console.log( "%s" );', $data );
	}


	return $r;
}


if ( ! function_exists( 'P_R_SERVER' ) ) {
	/**
	 * Print All _SERVER vars
	 *
	 * @param bool $return
	 *
	 * @return bool|string
	 * @since    0.0
	 * @verified 2016.11.29
	 */
	function P_R_SERVER( $return = false )
	{
		if ( $return ) {
			return P_R( $_SERVER, '$_SERVER', true );
		}


		P_R( $_SERVER, '$_SERVER' );


		return false;
	}
}


add_shortcode( 'P_R_SERVER', 'lct_sc_P_R_SERVER' );
/**
 * [P_R_SERVER]
 *
 * @return bool|string
 * @since    0.0
 * @verified 2016.11.29
 */
function lct_sc_P_R_SERVER()
{
	return P_R_SERVER( true );
}


if ( ! function_exists( 'P_R_POST' ) ) {
	/**
	 * Print All _POST vars
	 *
	 * @param bool $return
	 *
	 * @return bool|string
	 * @since    0.0
	 * @verified 2016.11.29
	 */
	function P_R_POST( $return = false )
	{
		if ( $return ) {
			return P_R( $_POST, '$_POST', true );
		}


		P_R( $_POST, '$_POST' );


		return false;
	}
}


add_shortcode( 'P_R_POST', 'lct_sc_P_R_POST' );
/**
 * [P_R_POST]
 *
 * @return bool|string
 * @since    0.0
 * @verified 2016.11.29
 */
function lct_sc_P_R_POST()
{
	return P_R_POST( true );
}
