<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Get a specific variable from later
 * The '_later' feature can be used to store any piece of data that you will want to use in a later function or method,
 * but that data is not specifically attached to any other sort of object that can be called later.
 *
 * @param string $name
 * @param string $var
 * @param mixed  $value
 *
 * @return mixed
 * @since        LCT 7.39
 * @verified     2020.11.10
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_get_later( $name, $var = '', $value = null )
{
	if (
		( $data = lct_get_data( '_later' ) )
		&& isset( $data[ $name ] )
	) {
		$tmp = $data[ $name ];


		if (
			$var
			&& isset( $tmp[ $var ] )
		) {
			$value = $tmp[ $var ];
		} elseif ( ! $var ) {
			$value = $tmp;
		}
	}


	return $value;
}


/**
 * Save a specific variable to later
 * The '_later' feature can be used to store any piece of data that you will want to use in a later function or method,
 * but that data is not specifically attached to any other sort of object that can be called later.
 *
 * @param string $name
 * @param mixed  $value
 * @param string $var
 *
 * @return bool
 * @since        LCT 7.39
 * @verified     2019.08.27
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_update_later( $name, $value, $var = '' )
{
	$data = lct_get_data( '_later' );


	if ( ! is_array( $data ) ) {
		$data = [];
	}


	if ( $var ) {
		if ( ! isset( $data[ $name ] ) ) {
			$data[ $name ] = [];
		}


		if ( $value === null ) {
			unset( $data[ $name ][ $var ] );
		} else {
			$data[ $name ][ $var ] = $value;
		}
	} else {
		if ( $value === null ) {
			unset( $data[ $name ] );
		} else {
			$data[ $name ] = $value;
		}
	}


	if ( empty( $data[ $name ] ) ) {
		unset( $data[ $name ] );
	}


	return lct_set_data( '_later', $data );
}


/**
 * Save an additional value to an array of a specific variable already in later
 * The '_later' feature can be used to store any piece of data that you will want to use in a later function or method,
 * but that data is not specifically attached to any other sort of object that can be called later.
 *
 * @param string $name
 * @param mixed  $value
 * @param string $var
 *
 * @return bool
 * @since        LCT 7.39
 * @verified     2019.07.12
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_append_later( $name, $value, $var = '' )
{
	$data = lct_get_data( '_later' );


	if ( ! is_array( $data ) ) {
		$data = [];
	}


	if ( ! isset( $data[ $name ] ) ) {
		$data[ $name ] = [];
	}


	if ( $var ) {
		$data[ $name ][ $var ][] = $value;


		/**
		 * Cleanup array
		 */
		if (
			count( $data[ $name ][ $var ] ) > 1
			&& isset( $data[0] )
			&& ! is_array( $data[ $name ][ $var ][0] )
		) {
			$data[ $name ][ $var ] = array_values( array_unique( $data[ $name ][ $var ] ) );
		}
	} else {
		$data[ $name ][] = $value;


		/**
		 * Cleanup array
		 */
		if (
			count( $data[ $name ] ) > 1
			&& isset( $data[0] )
			&& ! is_array( $data[ $name ][0] )
		) {
			$data[ $name ] = array_values( array_unique( $data[ $name ] ) );
		}
	}


	return lct_set_data( '_later', $data );
}


/**
 * Anytime on the front-end
 *
 * @return bool
 * @since    LCT 7.38
 * @verified 2018.02.20
 */
function lct_frontend()
{
	$r = false;


	if ( ! is_admin() ) {
		$r = true;
	}


	return $r;
}


/**
 * Anytime on the back-end, including ajax calls
 *
 * @return bool
 * @since    LCT 7.38
 * @verified 2018.02.20
 */
function lct_wp_admin_all()
{
	$r = false;


	if ( is_admin() ) {
		$r = true;
	}


	return $r;
}


/**
 * Anytime on the back-end, except ajax calls
 *
 * @return bool
 * @since    LCT 7.38
 * @verified 2018.02.20
 */
function lct_wp_admin_non_ajax()
{
	$r = false;


	if (
		is_admin()
		&& ! lct_doing()
	) {
		$r = true;
	}


	return $r;
}


/**
 * @return bool
 * @since    LCT 7.38
 * @verified 2018.02.20
 */
function lct_ajax_only()
{
	$r = false;


	if ( lct_doing() ) {
		$r = true;
	}


	return $r;
}


/**
 * Check if we are running this site as a dev site
 *
 * @return bool
 * @since    LCT 5.32
 * @verified 2018.02.20
 */
function lct_is_dev()
{
	$r = false;


	if (
		defined( 'LCT_DEV' )
		&& LCT_DEV == 1
	) {
		$r = true;
	}


	return $r;
}


/**
 * Possible sandbox subdomains
 *
 * @return array
 * @since    LCT 7.56
 * @verified 2020.09.04
 */
function lct_sb_prefixes()
{
	return [
		'dev.',
		'new.',
		'sandbox.',
		'sb.',
		'staging.',
	];
}


/**
 * Check if we are running this site as a sandbox site
 *
 * @return bool
 * @since    LCT 7.38
 * @verified 2018.02.20
 */
function lct_is_sb()
{
	$r = false;


	if (
		$_SERVER['HTTP_HOST']
		&& strpos_array( $_SERVER['HTTP_HOST'], lct_sb_prefixes() ) !== false
	) {
		$r = true;
	}


	return $r;
}


/**
 * Check if we are running this site as a dev or sandbox site
 *
 * @return bool
 * @since    LCT 5.36
 * @verified 2018.02.20
 */
function lct_is_dev_or_sb()
{
	$r = false;


	if (
		lct_is_dev()
		|| lct_is_sb()
	) {
		$r = true;
	}


	return $r;
}


/**
 * Check if we are running this site as wpall site
 *
 * @return bool
 * @since    LCT 7.29
 * @verified 2018.03.05
 */
function lct_is_wpall()
{
	/**
	 * Return early if cache is found
	 */
	$cache_key = lct_cache_key();
	if ( lct_isset_cache( $cache_key ) ) {
		return lct_get_cache( $cache_key );
	}


	$r     = false;
	$wpall = [];


	if (
		lct_plugin_active( 'acf' )
		&& ! ( $wpall = lct_get_option( 'wpall' ) )
		&& lct_acf_get_option_raw( 'api' )
	) {
		$url   = lct_get_api_url( 'wpall.php?key=' . lct_acf_get_option_raw( 'api' ) );
		$resp  = file_get_contents( $url );
		$wpall = json_decode( $resp, true );


		if ( $wpall ) {
			lct_update_option( 'wpall', $wpall );
		}
	}


	if (
		$wpall
		&& $_SERVER['HTTP_HOST']
		&& in_array( $_SERVER['HTTP_HOST'], $wpall )
	) {
		$r = true;
	}


	/**
	 * Save the value to the cache
	 */
	lct_set_cache( $cache_key, $r );


	return $r;
}


/**
 * Check if we are running this site as wpdev site
 *
 * @return bool
 * @since    LCT 7.39
 * @verified 2019.06.20
 */
function lct_is_wpdev()
{
	/**
	 * Return early if cache is found
	 */
	$cache_key = lct_cache_key();
	if ( lct_isset_cache( $cache_key ) ) {
		return lct_get_cache( $cache_key );
	}


	$r     = false;
	$wpdev = [];


	if (
		lct_plugin_active( 'acf' )
		&& ! ( $wpdev = lct_get_option( 'wpdev' ) )
		&& lct_acf_get_option_raw( 'api', true )
	) {
		$url   = lct_get_api_url( 'wpdev.php?key=' . lct_acf_get_option_raw( 'api', true ) );
		$resp  = file_get_contents( $url );
		$wpdev = json_decode( $resp, true );


		if ( $wpdev ) {
			lct_update_option( 'wpdev', $wpdev );
		}
	}


	if (
		$wpdev
		&& $_SERVER['HTTP_HOST']
		&& in_array( $_SERVER['HTTP_HOST'], $wpdev )
	) {
		$r = true;
	}


	/**
	 * Save the value to the cache
	 */
	lct_set_cache( $cache_key, $r );


	return $r;
}


/**
 * ACF Special Function
 * Add an underscore to the beginning of $name
 *
 * @param $name
 *
 * @return string
 * @since    LCT 7.18
 * @verified 2018.08.27
 */
function lct_pre_us( $name = '' )
{
	return '_' . $name;
}


/**
 * ACF Special Function
 * Remove the underscore at the beginning of $name
 *
 * @param $name
 *
 * @return string
 * @since    LCT 2017.93
 * @verified 2017.11.07
 */
function lct_un_pre_us( $name )
{
	if (
		$name
		&& strpos( $name, '_' ) === 0
	) {
		$name = preg_replace( "/_/", '', $name, 1 );
	}


	return $name;
}


/**
 * Preps a variable for return
 *
 * @param array  $array
 * @param string $glue
 * @param null   $key_glue
 * @param bool   $quote_value
 *
 * @return string
 * @since    LCT 5.37
 * @verified 2016.11.29
 */
function lct_return( $array = [], $glue = '', $key_glue = null, $quote_value = false )
{
	$r = '';


	if (
		is_array( $array )
		&& ! empty( $array )
	) {
		if ( $key_glue ) {
			$new_array = [];


			foreach ( $array as $k => $v ) {
				if ( $quote_value ) {
					$new_array[] = $k . $key_glue . '"' . $v . '"';
				} else {
					$new_array[] = $k . $key_glue . $v;
				}
			}


			$r = implode( $glue, $new_array );
		} else {
			$r = implode( $glue, $array );
		}
	}


	return $r;
}


/**
 * Redirect a page just before it is too late.
 * Don't use this unless you really have to
 *
 * @param bool|true  $force_exit
 * @param bool|false $headers_sent
 *
 * @since    LCT 0.0
 * @verified 2016.11.30
 */
function lct_custom_redirect_wrapper( $force_exit = true, $headers_sent = false )
{
	if (
		(
			function_exists( 'headers_sent' )
			&& headers_sent()
		)
		|| $headers_sent
	) {
		$script = '<script type="text/javascript">
			window.location = "/redirect/"
		</script>';


		echo $script;
		exit;
	}


	$current_user = wp_get_current_user();


	if (
		$current_user->ID
		|| $force_exit
	) {
		$redirect_url = $redirect_to = home_url( '/' );


		if ( function_exists( 'redirect_wrapper' ) ) {
			$redirect_url = redirect_wrapper( $redirect_to, '', $current_user );
		}


		if ( wp_redirect( $redirect_url ) ) {
			exit;
		}
	}
}


/**
 * Redirect a page just before it is too late.
 * This is the better version, USE lct_wp_safe_redirect() if possible
 *
 * @param string $location
 * @param int    $status
 * @param bool   $headers_sent
 *
 * @since        LCT 5.33
 * @verified     2019.11.20
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_wp_redirect( $location, $status = 302, $headers_sent = false )
{
	if (
		(
			function_exists( 'headers_sent' )
			&& headers_sent()
		)
		|| $headers_sent
	) {
		lct_wp_safe_redirect_js( $location );
	} else {
		if ( wp_redirect( $location, $status ) ) {
			exit;
		}
	}
}


/**
 * Redirect a page just before it is too late.
 * This is the best version
 *
 * @param string $location
 * @param int    $status
 * @param bool   $headers_sent
 *
 * @since        LCT 2017.34
 * @verified     2019.11.20
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_wp_safe_redirect( $location, $status = 302, $headers_sent = false )
{
	if (
		(
			function_exists( 'headers_sent' )
			&& headers_sent()
		)
		|| $headers_sent
	) {
		lct_wp_safe_redirect_js( $location );
	} else {
		wp_safe_redirect( $location, $status );
		exit;
	}
}


/**
 * Redirect a page when it is too late.
 *
 * @param string $location
 * @param bool   $return
 *
 * @return string
 * @since        LCT 2019.27
 * @verified     2019.11.20
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_wp_safe_redirect_js( $location, $return = false )
{
	$script = sprintf( '<script type="text/javascript">window.location = "%s";</script>', $location );


	if ( $return ) {
		return $script;
	} else {
		echo $script;
		exit;
	}
}


/**
 * This will return the api URL to a request
 *
 * @param $request
 *
 * @return string
 * @since    LCT 7.47
 * @verified 2016.12.02
 */
function lct_get_api_url( $request )
{
	return lct_get_setting( 'api' ) . $request;
}


/**
 * Format a phone number
 *
 * @param string $phone
 * @param string $force_valid_phone return null if the digit count is not 7 or 10
 *
 * @return string|null
 * @since    LCT 7.12
 * @verified 2021.11.08
 */
function lct_format_phone_number( $phone, $force_valid_phone = false )
{
	if ( ! lct_acf_get_option_raw( 'is_phone_number_international' ) ) {
		$phone = lct_strip_phone( $phone, $force_valid_phone );
		$f_pre = 'phone_number_format::';


		if ( $phone === null ) {
			return null;
		}


		//formats
		$area_code_pre  = lct_acf_get_option( $f_pre . 'area_code_pre' );
		$area_code_post = lct_acf_get_option( $f_pre . 'area_code_post' );
		$number_spacer  = lct_acf_get_option( $f_pre . 'number_spacer' );


		//if all are empty set a default
		if (
			! $area_code_pre
			&& ! $area_code_post
			&& ! $number_spacer
		) {
			$area_code_pre  = '(';
			$area_code_post = ') ';
			$number_spacer  = '-';
		}


		$format = $area_code_pre . '$1' . $area_code_post . '$2' . $number_spacer . '$3';


		/**
		 * short format
		 */
		if ( strlen( $phone ) === 7 ) {
			$phone = preg_replace( '~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', $format, '000' . $phone );
			$phone = str_replace( $area_code_pre . '000' . $area_code_post, '', $phone );


			/**
			 * full format
			 */
		} else {
			$phone = preg_replace( '~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', $format, $phone );
		}


		$phone = do_shortcode( $phone );
	}


	return $phone;
}


/**
 * Only keep the numbers
 *
 * @param string $phone
 * @param string $force_valid_phone return null if the digit count is not 7 or 10
 *
 * @return string|null
 * @since    LCT 2017.21
 * @verified 2021.11.08
 */
function lct_strip_phone( $phone, $force_valid_phone = false )
{
	$phone = preg_replace( '/[^0-9]/', '', $phone );
	if ( $phone ) {
		$phone = ltrim( $phone, '1' );
	}


	/**
	 * Check if valid
	 */
	if (
		$phone
		&& $force_valid_phone
		&& (
			strlen( $phone ) !== 7
			&& strlen( $phone ) !== 10
		)
	) {
		return null;
	}


	return $phone;
}


if ( ! function_exists( 'lct_theme_chunk' ) ) {
	/**
	 * Direct function for a function wrapped in a class
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    LCT 0.0
	 * @verified 2016.12.08
	 */
	function lct_theme_chunk( $a )
	{
		$theme_chunk = new lct_features_theme_chunk( lct_load_class_default_args() );


		return $theme_chunk->theme_chunk( $a );
	}
}


/**
 * Get the tablet threshold
 * **we have to call this mobile threshold for backward compatibility
 *
 * @return mixed
 * @since    LCT 7.9
 * @verified 2020.11.27
 */
function lct_get_mobile_threshold()
{
	/**
	 * Return early if cache is found
	 */
	$cache_key = lct_cache_key();
	if ( lct_isset_cache( $cache_key ) ) {
		return lct_get_cache( $cache_key );
	}


	$r = 1024;


	if (
		lct_plugin_active( 'acf' )
		&& ( $acf_threshold = (int) lct_acf_get_option_raw( 'tablet_threshold' ) )
	) {
		$r = $acf_threshold;
	}


	$r = apply_filters( 'lct_script_mobile_threshold', $r );


	/**
	 * Save the value to the cache
	 */
	lct_set_cache( $cache_key, $r );


	return $r;
}


/**
 * Get the small (the real) mobile threshold
 *
 * @return mixed
 * @since    LCT 7.59
 * @verified 2020.11.27
 */
function lct_get_small_mobile_threshold()
{
	/**
	 * Return early if cache is found
	 */
	$cache_key = lct_cache_key();
	if ( lct_isset_cache( $cache_key ) ) {
		return lct_get_cache( $cache_key );
	}


	$r = 768;


	if (
		lct_plugin_active( 'acf' )
		&& ( $acf_threshold = (int) lct_acf_get_option_raw( 'mobile_threshold' ) )
	) {
		$r = $acf_threshold;
	}


	$r = apply_filters( 'lct/small_mobile_threshold', $r );


	/**
	 * Save the value to the cache
	 */
	lct_set_cache( $cache_key, $r );


	return $r;
}


/**
 * Get the mobile extreme threshold
 *
 * @return mixed
 * @since    LCT 7.59
 * @verified 2020.11.27
 */
function lct_get_mobile_extreme_threshold()
{
	/**
	 * Return early if cache is found
	 */
	$cache_key = lct_cache_key();
	if ( lct_isset_cache( $cache_key ) ) {
		return lct_get_cache( $cache_key );
	}


	$r = 319;


	if (
		lct_plugin_active( 'acf' )
		&& ( $acf_threshold = (int) lct_acf_get_option_raw( 'mobile_extreme_threshold' ) )
	) {
		$r = $acf_threshold;
	}


	$r = apply_filters( 'lct/mobile_extreme_threshold', $r );


	/**
	 * Save the value to the cache
	 */
	lct_set_cache( $cache_key, $r );


	return $r;
}


/**
 * Get the array of email template data
 *
 * @param $post_id
 * @param $template
 *
 * @return array
 * @since    LCT 7.3
 * @verified 2020.07.02
 */
function lct_pder_get_email_template( $post_id, $template )
{
	/**
	 * Return early if cache is found
	 */
	$cache_key = lct_cache_key( compact( 'post_id', 'template' ) );
	if ( lct_isset_cache( $cache_key ) ) {
		return lct_get_cache( $cache_key );
	}


	$r = [];


	if ( in_array( $template, [ 'blank', 'send_now' ] ) ) {
		$r = [
			'title'        => '',
			'body'         => '',
			'queue_delay'  => .0166,
			'delete_email' => 1,
			'slug'         => $template,
		];
	} else {
		if (
			( $selector = zxzacf( 'email-reminder_templates' ) )
			&& ( $repeater = lct_acf_get_repeater_array( $selector, $post_id ) )
			&& ! empty( $repeater[ $template ] )
			&& ( $r = $repeater[ $template ] )
		) {
			if ( ! empty( $r['users_to'] ) ) {
				$r['users_to_users'] = $r['users_to'];
				$r['users_to']       = [];


				foreach ( $r['users_to_users'] as $user ) {
					$r['users_to'][] = $user->user_email;
				}


				$r['users_to_str'] = implode( ',', $r['users_to'] );
			}


			$r['queue_delay'] = (float) $r['queue_delay'];
		}
	}


	/**
	 * Save the value to the cache
	 */
	lct_set_cache( $cache_key, $r );


	return $r;
}


/**
 * Create or update a reminder
 *
 * @param       $template
 * @param null  $org
 * @param array $custom_data
 * @param bool  $process_before
 * @param array $fnr
 * @param null  $parent_id
 *
 * @return bool|int|WP_Error
 * @since    2018.14
 * @verified 2019.02.07
 */
function lct_update_reminder( $template, $org = null, $custom_data = [], $process_before = false, $fnr = [], $parent_id = null )
{
	if (
		! $template
		|| ! ( $template = lct_pder_get_email_template( $org, $template ) )
	) {
		return false;
	}


	if ( $process_before ) {
		$template['title'] = str_replace( $fnr['find'], $fnr['replace'], $template['title'] );
		$template['body']  = str_replace( $fnr['find'], $fnr['replace'], $template['body'] );
	} else {
		$template['body'] = '...';
	}


	$data = [
		'title'                    => $template['title'],
		'reminder'                 => $template['body'],
		'email'                    => '',
		'date'                     => lct_get_date_from_today( '+' . ( HOUR_IN_SECONDS * $template['queue_delay'] ), 'seconds' ),
		zxzacf( 'delete_email' )   => $template['delete_email'],
		zxzacf( 'process_before' ) => $process_before,
	];
	$data = wp_parse_args( $custom_data, $data );
	$data = apply_filters( 'lct/pder/update_reminder/data', $data, $template, $parent_id );


	/**
	 * Set Email if it is still not set
	 */
	if ( empty( $data['email'] ) ) {
		$data['email'] = get_option( 'admin_email' );
	}


	/**
	 * Set the org
	 */
	if (
		$org
		&& ( $tmp = lct_org() )
		&& (
			! isset( $data[ $tmp ] )
			|| empty( $data[ $tmp ] )
		)
	) {
		$data[ lct_org() ] = $org;
	}


	$pd            = new PDER_Admin;
	$sendable_data = [ 'pder' => $data ];


	//Update existing reminder
	if ( ! empty( $data['ID'] ) ) {
		$sendable_data['postid'] = $data['ID'];
		$reminder_id             = $pd->update_reminder( $sendable_data );


		//Create new reminder
	} else {
		$reminder_id = $pd->schedule_reminder( $sendable_data );
	}


	if (
		$reminder_id
		&& $template['slug'] === 'send_now'
	) {
		lct_send_reminder( $reminder_id );
	}


	return $reminder_id;
}


/**
 * Force-send a reminder
 *
 * @param int $reminder_id
 *
 * @since        2018.59
 * @verified     2018.07.27
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_send_reminder( $reminder_id )
{
	$pd = new PDER;
	$pd->send_ereminder( $reminder_id );
}


/**
 * Get a reminder
 *
 * @param int $reminder_id
 *
 * @return array|null
 * @since        2019.6
 * @verified     2019.04.01
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_get_reminder( $reminder_id )
{
	/**
	 * Return early if cache is found
	 */
	$cache_key = lct_cache_key( compact( 'reminder_id' ) );
	if ( lct_isset_cache( $cache_key ) ) {
		return lct_get_cache( $cache_key );
	}


	$r = null;


	if (
		$reminder_id
		&& ( $reminder = get_post( $reminder_id ) )
		&& ! empty( $reminder->post_type )
		&& $reminder->post_type === 'ereminder'
	) {
		$r['ID']       = $reminder->ID;
		$r['post']     = $reminder;
		$r['postmeta'] = lct_get_all_post_meta( $reminder_id, false, [ 'strpos_0' => [ '_', 'pyre' ] ] );
	}


	lct_set_cache( $cache_key, $r );


	return $r;
}


/**
 * Delete a reminder
 *
 * @param $reminder_id
 *
 * @since    2018.26
 * @verified 2018.03.06
 */
function lct_delete_reminder( $reminder_id )
{
	$pd = new PDER_Admin;


	$data['postid'] = $reminder_id;
	$pd->delete_reminder( $data );
}


/**
 * Determines whether the current request is a WordPress Ajax request.
 *
 * @return bool True if it's a WordPress Ajax request, false otherwise.
 * @since    2019.2
 * @verified 2019.02.13
 */
function lct_doing_ajax()
{
	if ( function_exists( 'wp_doing_ajax' ) ) {
		return wp_doing_ajax();
	}


	return defined( 'DOING_AJAX' ) && DOING_AJAX;
}


/**
 * Determines whether the current request is a WordPress autosave request.
 *
 * @return bool True if it's a WordPress autosave request, false otherwise.
 * @since    2019.2
 * @verified 2019.02.13
 */
function lct_doing_autosave()
{
	if ( function_exists( 'wp_doing_autosave' ) ) {
		return wp_doing_autosave();
	}


	return defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE;
}


/**
 * Determines whether the current request is a WordPress cron request.
 *
 * @return bool True if it's a WordPress cron request, false otherwise.
 * @since    2019.2
 * @verified 2019.02.13
 */
function lct_doing_cron()
{
	if ( function_exists( 'wp_doing_cron' ) ) {
		return wp_doing_cron();
	}


	return defined( 'DOING_CRON' ) && DOING_CRON;
}


/**
 * Determines whether the current request is a WordPress cron request.
 *
 * @return bool True if it's a WordPress cron request, false otherwise.
 * @since    2019.3
 * @verified 2019.02.18
 */
function lct_doing_api()
{
	if ( function_exists( 'wp_doing_api' ) ) {
		return wp_doing_api();
	}


	return defined( 'REST_REQUEST' ) && REST_REQUEST;
}


/**
 * Just tired of having to remember how to check DOING_*
 *
 * @param string $doing AJAX, AUTOSAVE
 *
 * @return bool True if it's a WordPress (AJAX|AUTOSAVE) request, false otherwise.
 * @since    LCT 5.36
 * @verified 2019.02.13
 */
function lct_doing( $doing = 'AJAX' )
{
	switch ( $doing ) {
		case 'AJAX':
			$r = lct_doing_ajax();
			break;


		case 'AUTOSAVE':
			$r = lct_doing_autosave();
			break;


		default:
			$r = false;
	}


	return $r;
}


/**
 * Take a single array and parse it into a find array and replace array
 *
 * @param $fr
 *
 * @return array
 * @since    LCT 4.2.2.26
 * @verified 2022.02.10
 */
function lct_create_find_and_replace_arrays( $fr )
{
	$f = [];
	$r = [];


	if ( is_array( $fr ) ) {
		foreach ( $fr as $ff => $rr ) {
			if ( ! is_string( $rr ) ) {
				if ( empty( $rr ) ) {
					$rr = '';
				} else {
					$rr = print_r( $rr, true );
				}
			}


			$f[] = $ff;
			$r[] = $rr;
		}
	}


	return [
		'find'    => $f,
		'replace' => $r
	];
}


/**
 * We want to do any of our shortcodes that are nested (so they don't break the theme or other plugins)
 *
 * @param $content
 *
 * @return mixed
 * @since    LCT 5.36
 * @verified 2023.02.10
 */
function lct_check_for_nested_shortcodes( $content )
{
	if ( ! $content ) {
		return '';
	}


	$delimiters = [
		[
			'before' => '{{{{',
			'after'  => '}}}}',
			'equal'  => '~~~~',
		],
		[
			'before' => '{{{',
			'after'  => '}}}',
			'equal'  => '~~~',
		],
		[
			'before' => '{{',
			'after'  => '}}',
			'equal'  => '~~',
		]
	];


	foreach ( $delimiters as $delimiter ) {
		/**
		 * Protect Vue placeholders
		 */
		preg_match_all( "/" . $delimiter['before'] . " (.*?) " . $delimiter['after'] . "/", $content, $vue_check );
		if ( ! empty( $vue_check[1] ) ) {
			$content = str_replace( [ '{{ ', ' }}' ], [ '~~vue(~~', '~~)vue~~' ], $content );
		}


		/**
		 * Nested shortcodes
		 */
		preg_match_all( "/" . $delimiter['before'] . "(.*?)" . $delimiter['after'] . "/", $content, $scs );
		if ( ! empty( $scs[1] ) ) {
			$content = process_nested_shortcode( $content, $scs, $delimiter );
		}


		/**
		 * Protect Vue placeholders
		 */
		if ( ! empty( $vue_check[1] ) ) {
			$content = str_replace( [ '~~vue(~~', '~~)vue~~' ], [ '{{ ', ' }}' ], $content );
		}
	}


	return $content;
}


/**
 * Sometimes we need to run a shortcode after all the other shortcodes have been run
 *
 * @param $content
 *
 * @return mixed
 * @since    LCT 7.59
 * @verified 2017.02.07
 */
function lct_final_shortcode_check( $content )
{
	if ( ! $content ) {
		return '';
	}


	$delimiters = [
		[
			'before' => '&#x60;&#x60;&#x60;',
			'after'  => '&#x60;&#x60;&#x60;',
		],
		[
			'before' => '```',
			'after'  => '```',
		]
	];


	foreach ( $delimiters as $delimiter ) {
		preg_match_all( "/" . $delimiter['before'] . "(.*?)" . $delimiter['after'] . "/", $content, $scs );


		if ( ! empty( $scs[1] ) ) {
			$content = process_nested_shortcode( $content, $scs );
		}
	}


	return $content;
}


/**
 * Process those funky shortcodes
 *
 * @param string $content
 * @param array  $scs
 * @param string $delimiter
 *
 * @return string
 * @since    LCT 2017.9
 * @verified 2018.09.19
 */
function process_nested_shortcode( $content, $scs, $delimiter = null )
{
	$unset_link      = false;
	$html_encode_fnr = lct_shortcode_html_decode();
	$find            = [];
	$replace         = [];
	$sc_to_esc_html  = apply_filters( 'lct/process_nested_shortcode/sc_to_esc_html', [ 'get_directions' ] );


	if ( strpos( $content, '{link' ) !== false ) {
		global $shortcode_tags;


		if (
			! isset( $shortcode_tags['link'] )
			&& class_exists( 'lct' )
			&& ( $tmp = lct_get_later( 'shortcode_tags_link_always' ) )
		) {
			$unset_link             = true;
			$shortcode_tags['link'] = $tmp;
		}
	}


	foreach ( $scs[1] as $sc_key => $sc ) {
		$sc_atts = shortcode_parse_atts( $sc );

		if ( isset( $delimiter['equal'] ) ) {
			$sc = str_replace( $delimiter['equal'], '=', $sc );
		}

		$sc = str_replace( $html_encode_fnr['find'], $html_encode_fnr['replace'], $sc );

		$sc = html_entity_decode( $sc );


		if (
			isset( $sc_atts[0] )
			&& shortcode_exists( $sc_atts[0] )
		) {
			$sc_out = do_shortcode( '[' . $sc . ']' );


			if ( in_array( $sc_atts[0], $sc_to_esc_html ) ) {
				$sc_out = esc_html( $sc_out );
			}
		} else {
			$sc_out = '';
		}


		$find[]    = $scs[0][ $sc_key ];
		$replace[] = $sc_out;
	}


	if ( $unset_link ) /** @noinspection PhpUndefinedVariableInspection */ {
		unset( $shortcode_tags['link'] );
	}


	return str_replace( $find, $replace, $content );
}


/**
 * Array of special decode maps for shortcodes
 *
 * @return array
 * @since    LCT 2017.9
 * @verified 2017.02.07
 */
function lct_shortcode_html_decode()
{
	$html_encode_find_n_replace = [
		'&#x22;'  => '"',
		'&#8220;' => '"',
		'&#8221;' => '"',
		'&#8243;' => '"',
		'&#x27;'  => '\'',
		'&#039;'  => '\'',
		'&#8216;' => '\'',
		'&#8217;' => '\'',
		'&#8242;' => '\'',
	];


	return lct_create_find_and_replace_arrays( $html_encode_find_n_replace );
}


/**
 * Bug fix for fusion_builder
 *
 * @param $content
 *
 * @return mixed
 * @since    LCT 2017.9
 * @verified 2017.04.04
 */
function lct_the_content_fusion_builder_bug_fix( $content )
{
	if ( ! $content ) {
		return '';
	}


	preg_match_all( '#</div></p>#', $content, $matches );

	if ( $matches ) {
		$content = str_replace( '</div></p>', '</div>', $content );
	}


	preg_match_all( '#<p><div#', $content, $matches );

	if ( $matches ) {
		$content = str_replace( '<p><div', '<div', $content );
	}


	//Used to clean up p tags around find n replace shortcodes
	preg_match_all( '#<p>{(.*?)}</p>#', $content, $matches );

	if ( $matches ) {
		$content = preg_replace( '#<p>{(.*?)}</p>#', '{$1}', $content );
	}


	return $content;
}


/**
 * We have to add this because wpautop doesn't check for script
 *
 * @param string $content
 *
 * @return string
 * @since        LCT 2017.9
 * @verified     2020.05.28
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_script_protector( $content )
{
	if ( ! $content ) {
		return $content;
	}


	$delimit = '~!~' . zxzu( 'script_protector' ) . '~!~';


	$content = str_replace(
		[
			'<script>',
			'<script ',
			'</script>'
		],
		[
			"{$delimit}<script>",
			"{$delimit}<script ",
			"</script>{$delimit}"
		],
		$content
	);


	preg_match_all( "/" . $delimit . "(.*?)" . $delimit . "/s", $content, $scripts );


	if ( ! empty( $scripts[1] ) ) {
		$find    = [];
		$replace = [];


		foreach ( $scripts[1] as $sc_key => $script ) {
			$script = '<div class="lct_script_protector">' . base64_encode( $script ) . '</div>';


			$find[]    = $scripts[0][ $sc_key ];
			$replace[] = $script;
		}


		$content = str_replace( $find, $replace, $content );
	}


	return $content;
}


/**
 * We have to add this because wpautop doesn't check for script
 *
 * @param $content
 *
 * @return mixed
 * @since    LCT 2017.9
 * @verified 2017.02.07
 */
function lct_script_protector_decode( $content )
{
	if ( ! $content ) {
		return '';
	}


	preg_match_all( "#<div class=\"lct_script_protector\">(.*?)</div>#", $content, $scripts );


	if ( ! empty( $scripts[1] ) ) {
		$find    = [];
		$replace = [];


		foreach ( $scripts[1] as $sc_key => $script ) {
			$script = base64_decode( $script );


			$find[]    = $scripts[0][ $sc_key ];
			$replace[] = $script;
		}


		$content = str_replace( $find, $replace, $content );
	}


	return $content;
}


/**
 * This will make relative links in to full URLs
 * It comes in handy when you need to use the content to send an email
 *
 * @param string $content
 * @param bool   $force_live_url
 *
 * @return string
 * @since        LCT 7.3
 * @verified     2020.12.08
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_add_url_site_to_content( $content, $force_live_url = false )
{
	$url_site_wp = lct_url_site_wp();
	$url_site    = lct_url_site();
	if ( ! ( $url_wp = str_replace( $url_site, '', $url_site_wp ) ) ) {
		$url_wp = '/';
	}
	if ( $force_live_url ) {
		$url_site_wp = lct_url_site_wp_when_dev();
	}


	$find_n_replace = [
		'//' => '~~~slash_slash~~~',

		'href="' . $url_wp             => 'href="' . $url_site_wp,
		'src="' . $url_wp              => 'src="' . $url_site_wp,
		'background_image="' . $url_wp => 'background_image="' . $url_site_wp,
		'="' . $url_wp                 => '="' . $url_site_wp,

		'href="/' => 'href="' . $url_site . '/',
		'src="/'  => 'src="' . $url_site . '/',

		'href=\'' . $url_wp             => 'href=\'' . $url_site_wp,
		'src=\'' . $url_wp              => 'src=\'' . $url_site_wp,
		'background_image=\'' . $url_wp => 'background_image=\'' . $url_site_wp,
		'=\'' . $url_wp                 => '=\'' . $url_site_wp,

		'href=\'/' => 'href=\'' . $url_site . '/',
		'src=\'/'  => 'src=\'' . $url_site . '/',

		']' . $url_wp => ']' . $url_site_wp,

		'~~~slash_slash~~~' => '//',
	];
	$fnr            = lct_create_find_and_replace_arrays( $find_n_replace );


	return str_replace( $fnr['find'], $fnr['replace'], $content );
}


/**
 * When you don't like writing 2 lines of code EVERY time you need the post_id
 *
 * @param int|WP_Post|null $post
 * @param bool|string      $parent
 *
 * @return int
 * @since    LCT 2017.34
 * @verified 2024.05.01
 */
function lct_get_post_id( $post = null, $parent = false )
{
	$r = null;


	if (
		( $post = get_post( $post ) )
		&& ! lct_is_wp_error( $post )
	) {
		$r = $post->ID;
	} elseif ( lct_doing_api() ) {
		if (
			! empty( $_REQUEST['post_id'] )
			&& is_numeric( $_REQUEST['post_id'] )
		) {
			$r = $_REQUEST['post_id'] = (int) $_REQUEST['post_id'];
		} elseif (
			( $tmp = lct_get_setting( 'root_post_id' ) )
			&& ! empty( $_REQUEST[ $tmp ] )
			&& is_numeric( $_REQUEST[ $tmp ] )
		) {
			$r = (int) $_REQUEST[ $tmp ];
		}
	} elseif (
		! empty( $_REQUEST['post_id'] )
		&& is_numeric( $_REQUEST['post_id'] )
		&& lct_doing()
	) {
		$r = $_REQUEST['post_id'] = (int) $_REQUEST['post_id'];
	}


	//If we specifically want the parent
	if (
		$parent
		&& ( $r = lct_get_setting( 'parent_post_id' ) ) === null
		&& ( $r = lct_get_post_id() )
	) {
		lct_update_setting( 'parent_post_id', $r );
	}


	if ( $r !== null ) {
		if (
			( $post = get_post( $r ) )
			&& ! lct_is_wp_error( $post )
		) {
			$r = $post->ID;
		} else {
			$r = null;
		}
	}


	return $r;
}


/**
 * When you don't like writing 2 lines of code EVERY time you need the root post_id
 *
 * @return int|null
 * @since    2020.5
 * @verified 2020.10.09
 */
function lct_get_root_post_id()
{
	$r = null;


	if (
		( $tmp = lct_get_setting( 'root_post_id' ) )
		&& ! empty( $_REQUEST[ $tmp ] )
	) {
		$r = (int) $_REQUEST[ $tmp ];


		if ( ! is_int( $_REQUEST[ $tmp ] ) ) {
			$_REQUEST[ $tmp ] = $r;
		}
	} elseif (
		( $post = get_post() )
		&& ! lct_is_wp_error( $post )
	) {
		$r = $_REQUEST[ $tmp ] = $post->ID;
	}


	return $r;
}


/**
 * When you don't like writing 2 lines of code EVERY time you need to check for an error
 *
 * @param null $obj
 *
 * @return bool
 * @since    LCT 2017.34
 * @verified 2017.05.18
 */
function lct_is_wp_error( $obj = null )
{
	$r = false;


	if (
		empty( $obj )
		|| is_wp_error( $obj )
	) {
		$r = true;
	}


	return $r;
}


/**
 * When you don't like writing 4 lines of code EVERY time you need to check for an error
 *
 * @param null $obj
 * @param null $class_name
 *
 * @return bool
 * @since    LCT 2017.34
 * @verified 2017.05.18
 */
function lct_is_a( $obj = null, $class_name = null )
{
	$r = false;


	if (
		! empty( $obj )
		&& is_object( $obj )
		&& ! is_wp_error( $obj )
		&& is_a( $obj, $class_name )
	) {
		$r = true;
	}


	return $r;
}


/**
 * Turn an array into shortcode_atts
 *
 * @param $old_atts
 *
 * @return string
 * @since    2017.61
 * @verified 2017.08.09
 */
function lct_make_shortcode_atts( $old_atts )
{
	$atts = '';


	if ( is_array( $old_atts ) ) {
		$atts = lct_return( $old_atts, ' ', '=', true );
	}


	return $atts;
}


/**
 * Get the DateTime object of the given time with the right time zone set
 *
 * @param string|null $DateTime format needs to be: Y-m-d H:i:s
 * @param bool        $input_is_UTC
 * @param bool        $return_as_UTC
 *
 * @return DateTime|null
 * @since    2019.1
 * @verified 2023.09.14
 */
function lct_DateTime( $DateTime = null, $input_is_UTC = false, $return_as_UTC = false )
{
	if ( $DateTime === null ) {
		$DateTime     = current_time( 'mysql', 1 );
		$input_is_UTC = true;
	} elseif ( str_contains( $DateTime, '.' ) ) {
		$DateTime = str_replace( '.', '/', $DateTime );
	}


	try {
		if ( $input_is_UTC ) {
			$now = new DateTime( $DateTime );
		} else {
			$now = new DateTime( $DateTime, lct_get_setting( 'timezone_user_timezone' ) );
		}


		if (
			$input_is_UTC
			&& ! $return_as_UTC
		) {
			$now->setTimezone( lct_get_setting( 'timezone_user_timezone' ) );
		} elseif (
			! $input_is_UTC
			&& $return_as_UTC
		) {
			$now->setTimezone( new DateTimeZone( 'UTC' ) );
		}
	} catch( Exception $e ) {
		$now = null;
	}


	return $now;
}


/**
 * Get the DateTime object of the time NOW with the right time zone set
 *
 * @param bool $UTC
 *
 * @return DateTime
 * @since    2019.1
 * @verified 2019.02.11
 */
function lct_current_time( $UTC = false )
{
	return lct_DateTime( null, true, $UTC );
}


/**
 * Get the DateTime object in a particular format of the time NOW with the right time zone set
 *
 * @param string|null $format
 * @param bool        $UTC
 *
 * @return string
 * @since    2019.1
 * @verified 2019.02.11
 */
function lct_format_current_time( $format = null, $UTC = false )
{
	if ( $format === null ) {
		$format = lct_db_date_format();
	}


	$DateTime = lct_current_time( $UTC );


	if ( $DateTime ) {
		$r = $DateTime->format( $format );
	} else {
		$r = '';
	}


	return $r;
}


/**
 * Get the GMT DateTime object in a particular format of the time NOW with the right time zone set
 *
 * @param string|null $format
 *
 * @return string
 * @date     2020.11.25
 * @since    2020.14
 * @verified 2020.11.25
 */
function lct_format_current_time_gmt( $format = null )
{
	return lct_format_current_time( $format, true );
}


/**
 * Take an input date and update the time zone & format the date
 *
 * @param string      $date 'now' || an actual date
 * @param string|null $only
 * @param bool        $set_to_user_timezone
 * @param string|null $date_format
 * @param string|null $time_format
 * @param bool        $input_is_UTC
 *
 * @return string
 * @since        2017.77
 * @verified     2019.02.11
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_format_date( $date, $only = null, $set_to_user_timezone = true, $date_format = null, $time_format = null, $input_is_UTC = true )
{
	if ( empty( $date ) ) {
		return $date;
	}


	$return_as_UTC = false;

	if (
		$set_to_user_timezone
		&& $input_is_UTC
	) {
		$return_as_UTC = false;
	} elseif (
		! $set_to_user_timezone
		&& $input_is_UTC
	) {
		$return_as_UTC = true;
	} elseif (
		$set_to_user_timezone
		&& ! $input_is_UTC
	) {
		$return_as_UTC = false;
	} elseif (
		! $set_to_user_timezone
		&& ! $input_is_UTC
	) {
		$return_as_UTC = true;
	}


	if ( ( $DateTime = lct_DateTime( $date, $input_is_UTC, $return_as_UTC ) ) !== null ) {
		if ( $date_format === 'mysql' ) {
			$format = lct_db_date_format();
		} else {
			if ( empty( $date_format ) ) {
				$date_format = get_option( 'date_format' );
			}

			if ( empty( $time_format ) ) {
				$time_format = get_option( 'time_format' );
			}


			if ( $only === 'date' ) {
				$format = $date_format;
			} elseif ( $only === 'time' ) {
				$format = $time_format;
			} else {
				$format = $date_format . ' ' . $time_format;
			}
		}


		$date = $DateTime->format( $format );
	}


	return $date;
}


/**
 * Display the Time Zone
 *
 * @param string $date
 * @param bool   $set_to_user_timezone
 *
 * @return string
 * @since    2019.1
 * @verified 2019.02.11
 */
function lct_display_timezone( $date = 'now', $set_to_user_timezone = true )
{
	return lct_format_date( $date, 'date', $set_to_user_timezone, 'T' );
}


/**
 * Save the info, so we can run the function later. That way it will for sure not get overwritten
 *
 * @param string|array $function
 * @param array        $args
 *
 * @since    2017.77
 * @verified 2022.11.30
 */
function lct_function_later( $function, $args = [] )
{
	$later = [
		'function' => $function,
		'args'     => $args,
	];


	if ( is_array( $function ) ) {
		$later['class']    = $function[0];
		$later['function'] = $function[1];
	}


	lct_append_later( 'function_later', $later );
}


/**
 * is the page a PDF
 *
 * @return mixed
 * @since    2017.80
 * @verified 2017.09.15
 */
function lct_is_pdf()
{
	return lct_get_setting( 'is_pdf', false );
}


/**
 * is the page a Display Form
 *
 * @return mixed
 * @since    2017.80
 * @verified 2017.09.18
 */
function lct_is_display_form()
{
	$r = false;


	if ( ! lct_is_pdf() ) {
		$r = lct_get_setting( 'acf_display_form_active', false );
	}


	return $r;
}


/**
 * is the page a Display Form or PDF
 *
 * @return bool
 * @since    2017.83
 * @verified 2017.09.28
 */
function lct_is_display_form_or_pdf()
{
	$r = false;


	if (
		lct_is_display_form()
		|| lct_is_pdf()
	) {
		$r = true;
	}


	return $r;
}


/**
 * is the page a Form page
 *
 * @return bool
 * @since    2017.80
 * @verified 2017.09.18
 */
function lct_is_form_only()
{
	$r = false;


	if (
		lct_frontend()
		&& ! lct_is_pdf()
		&& ! lct_is_display_form()
	) {
		$r = true;
	}


	return $r;
}


/**
 * is the page a Form page
 *
 * @return bool
 * @since    2017.83
 * @verified 2017.09.28
 */
function lct_is_form_enterable()
{
	$r = false;


	if (
		! lct_is_pdf()
		&& ! lct_is_display_form()
	) {
		$r = true;
	}


	return $r;
}


/**
 * Sets and empty value
 *
 * @param $value
 *
 * @return string
 * @since    2017.83
 * @verified 2017.09.28
 */
function lct_set_empty_value( $value )
{
	/**
	 * Check Arrays
	 */
	if ( lct_should_set_empty_value() ) {
		if (
			is_array( $value )
			&& empty( $value )
		) {
			$value = '';
		}


		/**
		 * Check for empty values
		 */
		if (
			$value === ''
			|| $value === false
			|| $value === null
		) {
			$value = LCT_VALUE_EMPTY;
		}
	}


	return $value;
}


/**
 * Should We allow an EMPTY value
 *
 * @return bool
 * @since    2017.83
 * @verified 2017.09.28
 */
function lct_should_set_empty_value()
{
	return apply_filters( 'lct/should_set_empty_value', false );
}


/**
 * Count the number of filters in a tag
 *
 * @param string $tag
 *
 * @return int
 * @since        2020.11
 * @verified     2020.09.09
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_count_filter( $tag )
{
	global $wp_filter;


	if ( isset( $wp_filter[ $tag ] ) ) {
		return count( lct_array_flatten( $wp_filter[ $tag ]->callbacks, true, [], 1 ) );
	}


	return 0;
}


/**
 * Loop through to remove all applicable filters
 *
 * @param      $tag_like
 * @param bool $priority
 *
 * @since    2017.83
 * @verified 2017.10.02
 */
function lct_remove_all_filters_like( $tag_like, $priority = false )
{
	global $wp_filter;


	foreach ( $wp_filter as $tag => $hook ) {
		if ( strpos( $tag, $tag_like ) === 0 ) //Filter must start with the $tag_like
		{
			remove_all_filters( $tag, $priority );
		}
	}
}


/**
 * Loop through to remove all applicable filters
 *
 * @param      $tag_like
 * @param      $function_to_remove_like
 * @param bool $priority_is
 * @param bool $exact_tag
 * @param null $check_class
 *
 * @since    2017.83
 * @verified 2019.02.18
 */
function lct_remove_filter_like( $tag_like, $function_to_remove_like, $priority_is = false, $exact_tag = false, $check_class = null )
{
	global $wp_filter;


	if ( $exact_tag ) {
		if ( key_exists( $tag_like, $wp_filter ) ) {
			lct_remove_filter_like_2( $tag_like, $wp_filter[ $tag_like ], $function_to_remove_like, $priority_is, $check_class );
		}
	} else {
		foreach ( $wp_filter as $tag => $hook ) {
			if ( strpos( $tag, $tag_like ) === 0 ) { //$tag must start with the $tag_like
				lct_remove_filter_like_2( $tag, $hook, $function_to_remove_like, $priority_is, $check_class );
			}
		}
	}
}


/**
 * Loop through to remove all applicable filters
 *
 * @param      $tag
 * @param      $hook
 * @param      $function_to_remove_like
 * @param bool $priority_is
 * @param null $check_class
 *
 * @since        2019.3
 * @verified     2019.02.18
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_remove_filter_like_2( $tag, $hook, $function_to_remove_like, $priority_is, $check_class )
{
	foreach ( $hook->callbacks as $priority => $callbacks ) {
		if (
			$priority_is
			&& $priority_is !== $priority
		) {
			continue;
		}


		if ( $check_class === null ) {
			foreach ( $callbacks as $callback => $data ) {
				if ( strpos( $callback, $function_to_remove_like ) !== false ) //Filter must contain with the $function_to_remove_like
				{
					remove_filter( $tag, $callback, $priority );
				}
			}
		} else {
			foreach ( $callbacks as $callback => $data ) {
				if (
					strpos( $callback, $function_to_remove_like ) !== false
					&& //Filter must contain with the $function_to_remove_like
					isset( $data['function'][0] )
					&& lct_is_a( $data['function'][0], $check_class )
				) {
					remove_filter( $tag, $callback, $priority );
				}
			}
		}
	}
}


/**
 * Make the status name
 *
 * @param       $status
 * @param array $status_meta
 *
 * @return bool|string
 * @since    2017.96
 * @verified 2022.02.22
 */
function lct_make_status_name( $status, $status_meta = [] )
{
	$name = 'No Name';


	if ( ! lct_is_wp_error( $status ) ) {
		$name = $status->name;


		if (
			! isset( $status_meta[ get_cnst( 'a_c_f_tax_status' ) ] )
			|| $status_meta[ get_cnst( 'a_c_f_tax_status' ) ] === false
		) {
			$name .= ' (disabled)';
		}
	}


	return $name;
}


/**
 * Make the status slug
 *
 * @param          $status
 * @param          $taxonomy
 * @param bool|int $force false or 1 or 2
 *
 * @return bool|string
 * @since    2017.96
 * @verified 2019.04.02
 */
function lct_make_status_slug( $status, $taxonomy = null, $force = false )
{
	$slug = 'publish';


	/**
	 * Get WP_Term if we only received a term_id
	 */
	if (
		$taxonomy
		&& ! lct_is_a( $status, 'WP_Term' )
		&& is_numeric( $status )
	) {
		$status = get_term( $status, $taxonomy );
	}


	if (
		lct_is_wp_error( $status )
		|| ! lct_is_a( $status, 'WP_Term' )
	) {
		return $slug;
	}


	/**
	 * Create the status slug
	 */
	if (
		$force === 1
		|| (
			$force !== 2
			&& ( $taxonomy_obj = get_taxonomy( $status->taxonomy ) )
			&& ! empty( $taxonomy_obj->lct_tax_custom_status_slugs )
		)
	) {
		$slug = substr( $status->term_id . '_' . $status->slug, 0, 20 );
	} else {
		if ( $post_type = lct_get_post_type_by_taxonomy( $status ) ) {
			$post_type = sanitize_title( $post_type );
		}


		$slug = substr( substr( $status->slug, 0, 12 ) . '_' . $post_type, 0, 20 );
	}


	return $slug;
}


/**
 * Get the term object of the status term from the post_type status slug
 *
 * @param int|WP_Post $status_slug
 *
 * @return WP_Term
 * @since    2019.29
 * @verified 2019.12.12
 */
function lct_get_status_obj_from_status_slug( $status_slug )
{
	$r = null;


	if ( lct_is_a( $status_slug, 'WP_Post' ) ) {
		$status_slug = $status_slug->post_status;
	}


	$tmp         = explode( '_', $status_slug );
	$status_slug = (int) $tmp[0];


	if (
		$status_slug
		&& ( $term = get_term( $status_slug ) )
		&& ! lct_is_wp_error( $term )
	) {
		$r = $term;
	}


	return $r;
}


/**
 * Get the term name of the status term from the post_type status slug
 *
 * @param int|WP_Post $status_slug
 *
 * @return string
 * @since    2019.29
 * @verified 2019.12.12
 */
function lct_get_status_name_from_status_slug( $status_slug )
{
	$r = 'Unknown';


	if ( $status = lct_get_status_obj_from_status_slug( $status_slug ) ) {
		$r = $status->name;
	}


	return $r;
}


/**
 * Quick function to update a field of a post
 *
 * @param $post_id
 * @param $field
 * @param $value
 *
 * @return bool|int|WP_Error
 * @since    2018.59
 * @verified 2018.07.29
 */
function lct_update_post_field( $post_id, $field, $value )
{
	$r = false;


	if (
		$post_id
		&& $field
		&& $value
	) {
		$args = [
			'ID'   => $post_id,
			$field => $value,
		];
		$r    = wp_update_post( $args );
	}


	return $r;
}


/**
 * Quick function to update fields of a post
 *
 * @param $post_id
 * @param $fields
 *
 * @return bool|int|WP_Error
 * @since    2018.59
 * @verified 2018.07.29
 */
function lct_update_post_fields( $post_id, $fields )
{
	$r = false;


	if (
		$post_id
		&& is_array( $fields )
		&& ! empty( $fields )
	) {
		$args = [
			'ID' => $post_id
		];
		$args = wp_parse_args( $fields, $args );
		$r    = wp_update_post( $args );
	}


	return $r;
}


/**
 * Quick function to update a status of a post
 *
 * @param $post_id
 * @param $status
 *
 * @return bool|int|WP_Error
 * @since    2017.96
 * @verified 2017.12.14
 */
function lct_update_post_status( $post_id, $status )
{
	$r = false;


	if (
		$post_id
		&& $status
	) {
		$args = [
			'ID'          => $post_id,
			'post_status' => $status,
		];
		$r    = wp_update_post( $args );
	}


	return $r;
}


/**
 * Quick function to update an excerpt of a post
 *
 * @param $post_id
 * @param $data
 *
 * @return bool|int|WP_Error
 * @since    2018.55
 * @verified 2018.06.22
 */
function lct_update_post_excerpt( $post_id, $data = '' )
{
	$r = false;


	if ( $post_id ) {
		$args = [
			'ID'           => $post_id,
			'post_excerpt' => $data,
		];
		$r    = wp_update_post( $args );
	}


	return $r;
}


/**
 * Quick function to update the title of a post
 *
 * @param $post_id
 * @param $data
 *
 * @return bool|int|WP_Error
 * @since    2019.1
 * @verified 2019.01.14
 */
function lct_update_post_title( $post_id, $data = '' )
{
	$r = false;


	if ( $post_id ) {
		$args = [
			'ID'         => $post_id,
			'post_title' => $data,
		];
		$r    = wp_update_post( $args );
	}


	return $r;
}


/**
 * Quick function to update the content of a post
 *
 * @param $post_id
 * @param $data
 *
 * @return bool|int|WP_Error
 * @since    2018.55
 * @verified 2018.06.22
 */
function lct_update_post_content( $post_id, $data = '' )
{
	$r = false;


	if ( $post_id ) {
		$args = [
			'ID'           => $post_id,
			'post_content' => $data,
		];
		$r    = wp_update_post( $args );
	}


	return $r;
}


/**
 * Quick function to update a parent of a post
 *
 * @param $post_id
 * @param $parent
 *
 * @return bool|int|WP_Error
 * @since    2018.11
 * @verified 2018.02.13
 */
function lct_update_post_parent( $post_id, $parent = 0 )
{
	$r = false;


	if ( $post_id ) {
		$args = [
			'ID'          => $post_id,
			'post_parent' => $parent,
		];
		$r    = wp_update_post( $args );
	}


	return $r;
}


/**
 * Get all the label data that we need to properly register a post_type
 *
 * @param array  $custom_labels
 * @param null   $lowercase
 * @param string $s
 *
 * @return array
 * @since    2018.11
 * @verified 2018.08.23
 */
function lct_post_type_default_labels( $custom_labels = [], $lowercase = null, $s = 's' )
{
	$lowercase = str_replace( '_', ' ', $lowercase );
	$capital   = ucwords( $lowercase );
	$lowercase = strtolower( $lowercase );

	if ( $s == 'ies' ) {
		$capitals   = rtrim( $capital, 'y' ) . $s;
		$lowercases = rtrim( $lowercase, 'y' ) . $s;
	} else {
		$capitals   = $capital . $s;
		$lowercases = $lowercase . $s;
	}


	$labels = [
		'name'               => $capitals,
		'singular_name'      => $capital,
		'menu_name'          => $capitals,
		'name_admin_bar'     => $capital,
		'all_items'          => "All {$capitals}",
		'add_new'            => "Add New {$capital}",
		'add_new_item'       => "Add New {$capital}",
		'edit_item'          => "Edit {$capital}",
		'new_item'           => "New {$capital}",
		'view_item'          => "View {$capital}",
		'search_items'       => "Search {$capitals}",
		'not_found'          => "No {$lowercases} found.",
		'not_found_in_trash' => "No {$lowercases} found in Trash.",
		'parent_item_colon'  => "Parent {$capitals}:"
	];


	return wp_parse_args( $custom_labels, $labels );
}


/**
 * Get all the data that we need to properly register a post_type
 *
 * @param array $custom_args
 * @param null  $slug
 * @param null  $labels
 *
 * @return array
 * @since    2018.11
 * @verified 2021.04.30
 */
function lct_post_type_default_args( $custom_args = [], $slug = null, $labels = null )
{
	$args = [
		//MISSING - 'label' => esc_html__( 'Meetings', 'your-textdomain' ),
		'labels'               => $labels,
		'description'          => '',
		'public'               => true,
		'exclude_from_search'  => false,
		'publicly_queryable'   => true,
		'show_ui'              => true,
		'show_in_nav_menus'    => true,
		'show_in_menu'         => true,
		'show_in_admin_bar'    => true,
		'show_in_rest'         => true,
		'rest_base'            => '',
		'menu_position'        => null,
		'menu_icon'            => null,
		'capability_type'      => 'post',
		//'capabilities' => null,
		'map_meta_cap'         => null,
		'hierarchical'         => false,
		'supports'             => [ 'title', 'author', 'thumbnail', 'comments' ],
		'register_meta_box_cb' => null,
		//'taxonomies' => null,
		'has_archive'          => true,
		'rewrite'              => [ 'slug' => $slug, 'with_front' => false, 'feeds' => false ],
		'query_var'            => true,
		'can_export'           => true,
		'delete_with_user'     => false,
	];


	/**
	 * Custom Args
	 * lct_following_is_parent bool|true :: true if is parent (I don't think we really use this anymore)
	 * lct_following_parent string :: the post_type of the parent (I don't think we really use this anymore)
	 */
	return wp_parse_args( $custom_args, $args );
}


/**
 * When you don't like writing 2 lines of code EVERY time you need the ACF post_id
 *
 * @param int|WP_Post|null $post
 * @param bool|string      $parent
 * @param bool             $only
 *
 * @return int|null
 * @since    2018.11
 * @verified 2022.12.13
 */
function lct_get_acf_post_id( $post = null, $parent = false, $only = false )
{
	$r       = null;
	$acf_pid = '_acf_post_id'; //Old Version
	$pid     = 'post_id';


	if ( ! empty( $_POST[ $acf_pid ] ) ) {
		$r = $_POST[ $acf_pid ];
		if ( is_numeric( $r ) ) {
			$r = (int) $_POST[ $acf_pid ];
		}


		if ( ! is_int( $_POST[ $acf_pid ] ) ) {
			$_POST[ $acf_pid ] = $r;
		}
	} elseif ( ! empty( $_POST[ $pid ] ) ) {
		$r = $_POST[ $pid ];
		if ( is_numeric( $r ) ) {
			$r = (int) $_POST[ $pid ];
		}


		if ( ! is_int( $_POST[ $pid ] ) ) {
			$_POST[ $pid ] = $r;
		}
	}


	if (
		! $only
		&& (
			$r === null
			|| $parent
		)
	) {
		$r = lct_get_post_id( $post, $parent );
	}


	if ( $r !== null ) {
		//Delete This
		if (
			isset( $_POST[ $acf_pid ] )
			&& isset( $_POST[ $pid ] )
			&& $_POST[ $acf_pid ] !== 'new_post'
			&& (int) $_POST[ $acf_pid ] !== (int) $_POST[ $pid ]
		) {
			if (
				$_POST[ $acf_pid ] === 'options'
				|| (int) $_POST[ $pid ] === 67179
				|| //Materials table [legacy]
				(int) $_POST[ $pid ] === 67199
				|| //Installation table [legacy]
				(int) $_POST[ $pid ] === 586848
				|| //Phone Numbers [legacy]
				(int) $_POST[ $pid ] === 67063
				|| //Add a New Address [legacy]
				(int) $_POST[ $pid ] === 67074
				|| //Add a New Address [legacy]
				(
					! empty( $_POST['_acf_screen'] )
					&& //Make a change [legacy]
					$_POST['_acf_screen'] === 'comment'
					&& empty( $_POST[ $acf_pid ] )
				)
			) {
				$_POST[ $pid ] = $_POST[ $acf_pid ];
			} else {
				lct_send_function_check_email( [ 'function' => __FUNCTION__, 'message' => __FUNCTION__ . '() mismatch IDs: ' . $_POST[ $acf_pid ] . ' :: ' . $_POST[ $pid ] ] );
				lct_debug_to_error_log( __FUNCTION__ . '() mismatch IDs: ' . $_POST[ $acf_pid ] . ' :: ' . $_POST[ $pid ] );
			}
		}


		$_POST[ $acf_pid ] = $_POST[ $pid ] = $r;
	}


	return $r;
}


/**
 * When you don't like writing 2 lines of code EVERY time you need the ACF post_id
 *
 * @param null $post
 * @param bool $parent
 *
 * @return int|null
 * @since    2018.11
 * @verified 2018.02.12
 */
function lct_get_acf_post_id_only( $post = null, $parent = false )
{
	return lct_get_acf_post_id( $post, $parent, true );
}


/**
 * Wrap up a good message
 *
 * @param $message
 *
 * @return string
 * @since    2018.21
 * @verified 2018.02.28
 */
function lct_message_good( $message )
{
	return sprintf( "<span style='color: green;'>%s</span>", $message );
}


/**
 * Wrap up a bad message
 *
 * @param $message
 *
 * @return string
 * @since    2018.21
 * @verified 2018.02.28
 */
function lct_message_bad( $message )
{
	return sprintf( "<span style='color: red;'>%s</span>", $message );
}


/**
 * Prepare an SQL statement that needs IN
 *
 * @param $sql
 * @param $vals
 *
 * @return mixed
 * @since    2018.21
 * @verified 2020.03.05
 */
function lct_wpdb_prepare_in( $sql, $vals )
{
	if ( substr_count( $sql, '[IN]' ) > 0 ) {
		global $wpdb;


		if ( ! is_array( $vals ) ) {
			$vals = [ $vals ];
		}


		/**
		 * Save step #2 placeholders for later
		 */
		$sql = str_replace( [ '%s2', '%d2', '%f2', '[IN2]' ], [ 'tmpzzszzhold', 'tmpzzdzzhold', 'tmpzzfzzhold', 'tmpzzINzzhold', '%%' ], $sql );


		/**
		 * Make the IN statement
		 */
		$args = [ str_replace( '[IN]', implode( ', ', array_fill( 0, count( $vals ), '%s' ) ), $sql ) ];


		/**
		 * This will populate ALL the [IN]'s with the $vals, assuming you have more than one [IN] in the sql
		 */
		for ( $i = 0; $i < substr_count( $sql, '[IN]' ); $i ++ ) {
			$args = array_merge( $args, $vals );
		}


		/**
		 * Prepare the statement
		 */
		$sql = call_user_func_array( [ $wpdb, 'prepare' ], array_merge( $args ) );


		/**
		 * Add the step #2 placeholders back in
		 */
		$sql = str_replace( [ 'tmpzzszzhold', 'tmpzzdzzhold', 'tmpzzfzzhold', 'tmpzzINzzhold' ], [ '%s', '%d', '%f', '[IN]' ], $sql );
	}


	return $sql;
}


/**
 * Send an email if a critical function fails
 *
 * @param $custom_args
 *
 * @return bool
 * @since    2018.22
 * @verified 2021.03.09
 */
function lct_quick_send_email( $custom_args = [] )
{
	$args                 = [
		'from_email'        => 'noreply@lctquicksend.com',
		'from_name'         => zxzb( ' Quick Send' ),
		'to'                => get_option( 'admin_email' ),
		'subject'           => 'Not Set',
		'message'           => 'Not Set',
		'send_limiter'      => null,
		'send_limiter_time' => HOUR_IN_SECONDS,
	];
	$args['send_limiter'] = $args['message'];
	$args                 = wp_parse_args( $custom_args, $args );
	if ( $args['send_limiter'] === 'Not Set' && $args['message'] !== 'Not Set' ) {
		$args['send_limiter'] = $args['message'];
	}
	$args['send_limiter'] = __FUNCTION__ . '_' . md5( $args['send_limiter'] );


	//Only send the email once per page load
	if ( lct_did( __FUNCTION__ . sanitize_title( $args['subject'] ) ) ) {
		return false;
	}


	if ( get_transient( $args['send_limiter'] ) ) {
		return false;
	}


	set_transient( $args['send_limiter'], 1, $args['send_limiter_time'] );


	$mail = new lct_features_class_mail( lct_load_class_default_args() );
	$mail->set_from( $args['from_email'], $args['from_name'] );
	$mail->set_to( $args['to'] );
	$mail->set_subject( $args['subject'] );
	$mail->set_message( $args['message'] );


	return $mail->send();
}


/**
 * Run any functions that were run by lct_function_later()
 *
 * @since    2019.1
 * @verified 2024.06.27
 */
function lct_do_function_later()
{
	if ( $updates = lct_get_later( 'function_later' ) ) {
		foreach ( $updates as $ud ) {
			if ( ! empty( $ud['class'] ) ) {
				call_user_func_array( [ $ud['class'], $ud['function'] ], $ud['args'] );
			} else {
				if ( function_exists( 'afwp_flush_cache' ) && $ud['function'] === 'xbs_calculate_totals' && ! empty( $ud['args'][0] ) ) {
					afwp_flush_cache( 'xbs_calculate_totals::' . $ud['args'][0] );
				}
				call_user_func_array( $ud['function'], $ud['args'] );
			}
		}


		lct_update_later( 'function_later', null );
	}
}


/**
 * Produce a random string
 *
 * @param string $prefix
 * @param string $suffix
 * @param int    $trim
 *
 * @return string
 * @since    2019.1
 * @verified 2020.01.21
 */
function lct_rand( $prefix = '', $suffix = '', $trim = null )
{
	$r = md5( rand() . current_time( 'timestamp', 1 ) );


	if ( $trim ) {
		$r = substr( $r, 0, (int) $trim );
	}


	return $prefix . $r . $suffix;
}


/**
 * Produce a short random string
 *
 * @param int    $trim
 * @param string $prefix
 * @param string $suffix
 *
 * @return string
 * @since    2020.3
 * @verified 2020.01.21
 */
function lct_rand_short( $trim = 8, $prefix = '', $suffix = '' )
{
	return lct_rand( $prefix, $suffix, $trim );
}


/**
 * Send an email if a critical function fails
 *
 * @param $args
 *
 * @return bool
 * @since    0.0
 * @verified 2024.05.21
 */
function lct_send_function_check_email( $args )
{
	global $sent_function_check_email;

	//Only send the email one per page load
	if ( ! empty( $sent_function_check_email ) ) {
		return false;
	}


	$message = '';

	if ( $args['message'] ) {
		$message = '<br /><br />' . $args['message'];
	}


	if ( class_exists( 'lct_features_class_mail' ) ) {
		$mail = new lct_features_class_mail( lct_load_class_default_args() );
		$mail->set_from( 'noreply@' . zxza( '-formcheck.com' ), zxzb( ' Auto Function Check' ) );
		$mail->set_to( get_option( 'admin_email' ) );
		$mail->set_subject( sprintf( '%s is not working properly.', $args['function'] ) );
		$mail->set_message( sprintf( '%s at %s%s%s', $mail->get_subject(), get_bloginfo( 'url' ), $_SERVER['REQUEST_URI'], $message ) );
		$mail->send();
	} else {
		lct_debug_to_error_log( 'TRIGGERED lct_send_function_check_email() :: ' . $message );
		lct_debug_to_error_log( $args );
	}


	$sent_function_check_email ++;


	return true;
}


/**
 * Delete all the postmeta for a post
 *
 * @param int|WP_Post $post_id
 *
 * @since    2019.6
 * @verified 2019.04.01
 */
function lct_delete_all_post_meta( $post_id )
{
	global $wpdb;


	if ( ! empty( $post_id ) ) {
		if ( lct_is_a( $post_id, 'WP_Post' ) ) {
			$post_id = $post_id->ID;
		}


		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM `{$wpdb->postmeta}` 
				WHERE `post_id` = '%s'",
				$post_id
			)
		);
	}
}


/**
 * Delete all the postmeta for multiple posts
 *
 * @param int|array|WP_Post $post_ids
 *
 * @since    2019.6
 * @verified 2019.04.01
 */
function lct_delete_all_post_meta_by_post_ids( $post_ids )
{
	global $wpdb;
	$delete_ids = [];

	if ( ! is_array( $post_ids ) ) {
		$post_ids = [ $post_ids ];
	}


	if ( ! empty( $post_ids ) ) {
		foreach ( $post_ids as $post ) {
			if ( lct_is_a( $post, 'WP_Post' ) ) {
				$delete_ids[] = $post->ID;
			} elseif ( is_numeric( $post ) ) {
				$delete_ids[] = $post;
			}
		}


		if ( ! empty( $delete_ids ) ) {
			$wpdb->query(
				lct_wpdb_prepare_in(
					"DELETE FROM `{$wpdb->postmeta}` 
					WHERE `post_id` IN ( [IN] )",
					$delete_ids
				)
			);
		}
	}
}


/**
 * Make a value quoted string from an array
 *
 * @param array  $array
 * @param string $quote
 * @param string $glue
 *
 * @return string
 * @since        2019.19
 * @verified     2019.07.16
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_array_to_quoted_string( $array, $quote = '"', $glue = ',' )
{
	$r = '';


	if (
		! empty( $array )
		&& is_array( $array )
	) {
		$r_arr = [];


		foreach ( $array as $arr ) {
			$r_arr[] = $quote . trim( $arr ) . $quote;
		}


		$r = implode( $glue, $r_arr );
	}


	return $r;
}


/**
 * Programmatically create a shortcode
 *
 * @param string      $tag
 * @param array       $atts
 * @param string|null $content
 *
 * @return string
 * @since        2019.25
 * @verified     2022.02.08
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_produce_shortcode( $tag, $atts = [], $content = null )
{
	$atts_string = [];


	foreach ( $atts as $k => $v ) {
		if ( $v === false ) {
			$v = 0;
		} elseif ( $v === true ) {
			$v = 1;
		}


		$atts_string[] = $k . '="' . $v . '"';
	}


	if ( $content !== null ) {
		$r = sprintf(
			'[%1$s %2$s]%3$s[/%1$s]',
			$tag,
			implode( ' ', $atts_string ),
			$content
		);
	} else {
		$r = sprintf(
			'[%s %s]',
			$tag,
			implode( ' ', $atts_string ) );
	}


	return $r;
}


/**
 * Flatten a multidimensional array
 *
 * @param array $arr
 * @param bool  $maintain_keys
 * @param array $r
 * @param int   $level
 * @param int   $curr_level
 *
 * @return array
 * @since        2020.6
 * @verified     2020.03.05
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_array_flatten( $arr, $maintain_keys = false, $r = [], $level = 0, $curr_level = 0 )
{
	$curr_level ++;


	foreach ( $arr as $k => $v ) {
		if (
			is_array( $v )
			&& (
				$level === 0
				|| $curr_level <= $level
			)
		) {
			$r = lct_array_flatten( $v, $maintain_keys, $r, $level, $curr_level );
		} elseif ( isset( $v ) ) {
			if ( $maintain_keys ) {
				$r[ $k ] = $v;
			} else {
				$r[] = $v;
			}
		}
	}


	return $r;
}


/**
 * Flatten a multidimensional array and make it unique
 *
 * @param array $arr
 * @param bool  $maintain_keys
 *
 * @return array
 * @since        2020.6
 * @verified     2020.03.04
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_array_flatten_unique( $arr, $maintain_keys = false )
{
	if ( $arr = lct_array_flatten( $arr, $maintain_keys ) ) {
		$arr = array_unique( $arr );
	}


	return $arr;
}


/**
 * Check if a value is not null
 *
 * @param mixed $v
 *
 * @return bool
 * @since    2020.6
 * @verified 2020.03.04
 */
function lct_is_not_null( $v )
{
	return $v !== null;
}


/**
 * Check if a post is the desired post_type
 *
 * @param WP_Post|int $post
 * @param string      $post_type
 *
 * @return bool
 * @since        2020.7
 * @verified     2020.04.09
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_check_post_type_match( $post, $post_type )
{
	$r = false;


	if (
		! lct_is_wp_error( $post )
		&& isset( $post->post_type )
		&& $post->post_type === $post_type
	) {
		$r = true;
	}


	return $r;
}


/**
 * Check multiple caps for a user
 *
 * @param array $caps
 *
 * @return bool
 * @since    2020.11
 * @verified 2020.09.04
 */
function lct_current_user_can_caps( array $caps )
{
	$r = false;


	foreach ( $caps as $cap ) {
		if ( current_user_can( $cap ) ) {
			$r = true;
			break;
		}
	}


	return $r;
}


/**
 * lct_is_empty
 * Returns true if the value provided is considered "empty". Allow numbers such as 0.
 *
 * @param mixed $var The value to check.
 *
 * @return    bool
 * @date     2020.10.05
 * @since    2020.13
 * @verified 2020.10.05
 */
function lct_is_empty( $var )
{
	return ( ! $var && ! is_numeric( $var ) );
}


/**
 * lct_not_empty
 * Returns true if the value provided is considered "not empty". Allow numbers such as 0.
 *
 * @param mixed $var The value to check.
 *
 * @return    bool
 * @date     2020.10.05
 * @since    2020.13
 * @verified 2020.10.05
 */
function lct_not_empty( $var )
{
	return ( $var || is_numeric( $var ) );
}


/**
 * lct_previous_function
 * Returns the details of the previous function
 *
 * @param string $sep The separator between the class and the function.
 * @param int    $level
 *
 * @return    string
 * @date     2020.11.24
 * @since    2020.14
 * @verified 2022.08.19
 */
function lct_previous_function( $sep = '::', $level = 2 )
{
	$bt = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, $level + 1 );
	$r  = $bt[ $level ]['function'];


	if (
		! empty( $bt[ $level ]['class'] )
		&& lct_not_empty( $bt[ $level ]['class'] )
	) {
		$r = $bt[ $level ]['class'] . $sep . $r;
	}


	return $r;
}


/**
 * lct_previous_function_deep
 * Returns the details of the previous function automatically
 *
 * @param string $sep The separator between the class and the function.
 *
 * @return    string
 * @date     2020.11.24
 * @since    2020.14
 * @verified 2020.11.24
 */
function lct_previous_function_deep( $sep = '::' )
{
	return lct_previous_function( $sep, 4 );
}


/**
 * Update a postmeta value
 *
 * @param WP_Post $post
 * @param string  $meta_key
 * @param mixed   $meta_value
 *
 * @date         2020.11.25
 * @since        2020.14
 * @verified     2020.11.25
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_WP_Post_update_postmeta( $post, $meta_key, $meta_value )
{
	if ( ! isset( $post->update_postmeta ) ) {
		$post->update_postmeta = [];
	}


	$post->update_postmeta[ $meta_key ] = $meta_value;
}


/**
 * Update an ACF postmeta value
 *
 * @param WP_Post $post
 * @param string  $meta_key
 * @param mixed   $meta_value
 *
 * @date         2020.11.25
 * @since        2020.14
 * @verified     2020.11.25
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_WP_Post_update_acf( $post, $meta_key, $meta_value )
{
	if ( ! isset( $post->update_acf ) ) {
		$post->update_acf = [];
	}


	$post->update_acf[ $meta_key ] = $meta_value;
}


/**
 * Retrieves the edit post link for post.
 * Can be used within the WordPress loop or outside it. Can be used with
 * pages, posts, attachments, and revisions.
 *
 * @param int|WP_Post $id      Optional. Post ID or post object. Default is the global `$post`.
 * @param string      $context Optional. How to output the '&' character. Default '&amp;'.
 *
 * @return string|null The edit post link for the given post. null if the post type is invalid or does
 *                     not allow an editing UI.
 * @date         2020.11.25
 * @since        2020.14
 * @verified     2020.11.25
 */
function lct_get_edit_post_link( $id = 0, $context = 'display' )
{
	$post = get_post( $id );
	if ( ! $post ) {
		return null;
	}

	if ( 'revision' === $post->post_type ) {
		$action = '';
	} elseif ( 'display' === $context ) {
		$action = '&amp;action=edit';
	} else {
		$action = '&action=edit';
	}

	$post_type_object = get_post_type_object( $post->post_type );
	if ( ! $post_type_object ) {
		return null;
	}


	if ( $post_type_object->_edit_link ) {
		$link = admin_url( sprintf( $post_type_object->_edit_link . $action, $post->ID ) );
	} else {
		$link = '';
	}

	/**
	 * Filters the post edit link.
	 *
	 * @param string $link    The edit link.
	 * @param int    $post_id Post ID.
	 * @param string $context The link context. If set to 'display' then ampersands
	 *                        are encoded.
	 *
	 * @since 2.3.0
	 */
	return apply_filters( 'get_edit_post_link', $link, $post->ID, $context );
}


/**
 * Implode and escape HTML attributes for output.
 *
 * @param array $raw_attributes Attribute name value pairs.
 *
 * @return string
 * @date     2022.09.13
 * @since    2022.7
 * @verified 2022.09.13
 */
function lct_implode_html_attributes( $raw_attributes )
{
	$attributes = [];


	foreach ( $raw_attributes as $name => $value ) {
		$attributes[] = esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
	}


	return implode( ' ', $attributes );
}


/**
 * Injects the root post_id if there is a placeholder present
 *
 * @param mixed $post_id The post_id for this data.
 *
 * @return  mixed
 * @date     2022.11.30
 * @since    2022.11
 * @verified 2022.11.30
 */
function lct_pre_check_post_id( $post_id = null )
{
	$placeholder = '{post_id}';


	if (
		$post_id === $placeholder
		&& ( $root_id = afwp_get_root_post_id() )
	) {
		return str_replace( $placeholder, $root_id, $post_id );
	}


	return $post_id;
}
