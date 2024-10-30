<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'set_cnst' ) ) {
	/**
	 * Set a constant for the plugin
	 *
	 * @param      $var
	 * @param      $value
	 * @param null $a
	 *
	 * @return bool
	 * @since    7.27
	 * @verified 2016.11.04
	 */
	function set_cnst( $var, $value, $a = null )
	{
		if ( ! $a ) {
			$a = $GLOBALS['lct'];
		}

		$v = false;


		if (
			$var
			&& $value
			&& ! isset( $a->cnst[ $var ] )
		) {
			$a->cnst[ $var ] = $value;

			$v = true;
		} else {
			lct_debug_to_error_log( "cnst '{$var}' was already set!!!" );
		}


		return $v;
	}
}


if ( ! function_exists( 'is_set_cnst' ) ) {
	/**
	 * Check if a constant is set for the plugin
	 * Use carefully
	 *
	 * @param      $var
	 * @param null $a
	 *
	 * @return bool
	 * @since    2017.83
	 * @verified 2017.09.28
	 */
	function is_set_cnst( $var, $a = null )
	{
		if ( ! $a ) {
			$a = $GLOBALS['lct'];
		}

		$v = false;


		if (
			$var
			&& isset( $a->cnst[ $var ] )
		) {
			$v = true;
		}


		return $v;
	}
}


if ( ! function_exists( 'get_cnst' ) ) {
	/**
	 * Get a constant for the plugin
	 *
	 * @param string $var
	 * @param lct    $a
	 *
	 * @return bool|string|mixed
	 * @since        7.27
	 * @verified     2020.09.09
	 * @noinspection PhpStatementHasEmptyBodyInspection
	 */
	function get_cnst( $var, $a = null )
	{
		if ( ! $a ) {
			$a = $GLOBALS['lct'];
		}

		$v = false;


		if (
			$var
			&& isset( $a->cnst[ $var ] )
		) {
			$v = $a->cnst[ $var ];
		} elseif (
			$a->lct_mu
			&& ! empty( $a->lct_mu->fast_ajax )
		) {
			//Do nothing
		} else {
			lct_debug_to_error_log( "cnst_data for '{$var}' is not set yet!!!" );
		}


		return $v;
	}
}


if ( ! function_exists( 'set_cnst_data' ) ) {
	/**
	 * Set data for a constant for the plugin
	 *
	 * @param      $var
	 * @param      $value
	 * @param null $a
	 *
	 * @return bool
	 * @since    7.27
	 * @verified 2016.11.04
	 */
	function set_cnst_data( $var, $value, $a = null )
	{
		if ( ! $a ) {
			$a = $GLOBALS['lct'];
		}

		$v = false;


		if (
			$var
			&& $value
			&& isset( $a->cnst[ $var ] )
			&& ! isset( $a->cnst['data'][ $var ] )
		) {
			$a->cnst['data'][ $var ] = $value;

			$v = true;
		} else {
			lct_debug_to_error_log( "cnst_data for '{$var}' was already set!!!" );
		}


		return $v;
	}
}


if ( ! function_exists( 'get_cnst_data' ) ) {
	/**
	 * Get data for a constant for the plugin
	 *
	 * @param      $var
	 * @param null $a
	 *
	 * @return bool
	 * @since    7.27
	 * @verified 2016.11.04
	 */
	function get_cnst_data( $var, $a = null )
	{
		if ( ! $a ) {
			$a = $GLOBALS['lct'];
		}

		$v = false;


		if (
			$var
			&& isset( $a->cnst[ $var ] )
			&& isset( $a->cnst['data'][ $var ] )
		) {
			$v = $a->cnst['data'][ $var ];
		}


		return $v;
	}
}


/**
 * ACF Special Function
 * Sets $post_id to options
 *
 * @return string
 * @since    0.0
 * @verified 2016.11.04
 */
function lct_o()
{
	return 'options';
}


/**
 * Turn a value into a dollar amount
 *
 * @param float|string|int $value
 * @param bool             $format_value
 *
 * @return string|float
 * @since    7.63
 * @verified 2024.04.24
 */
function lct_get_dollar( $value, $format_value = true )
{
	$amount = lct_get_un_dollar( $value );


	if ( $format_value ) {
		$amount = number_format( $amount, 2 );
		if ( $amount < 0 ) {
			$amount = '- $' . str_replace( '-', '', $amount );
		} else {
			$amount = '$' . $amount;
		}
	}


	return $amount;
}


/**
 * Turn a dollar value into a float amount
 *
 * @param float|string|int $value
 *
 * @return float
 * @since    7.63
 * @verified 2024.04.24
 */
function lct_get_un_dollar( $value )
{
	$value = str_replace( [ '$', ',', ' ', '%' ], '', $value );
	is_numeric( $value ) ? : $value = 0.0;


	$amount = (float) $value;


	return round( $amount, 2 );
}


/**
 * Turn a value into a dollar amount
 *
 * @param $value
 *
 * @return string
 * @since    2019.1
 * @verified 2024.04.24
 */
function lct_get_dollar_wo_symbol( $value )
{
	return lct_get_dollar( $value, false );
}


/**
 * Turn a number into a negative one, or positive if it is already negative
 *
 * @param $value
 *
 * @return float
 * @since    2018.11
 * @verified 2018.02.08
 */
function lct_get_negative_number( $value )
{
	$value = lct_get_un_dollar( $value );


	if ( $value ) {
		$value = $value * - 1;
	}


	return (float) $value;
}


/**
 * Turn a dollar amount into a negative one, or positive if it is already negative
 *
 * @param $value
 *
 * @return string
 * @since    2018.11
 * @verified 2018.02.08
 */
function lct_get_negative_dollar( $value )
{
	$value = lct_get_negative_number( $value );


	return lct_get_dollar( $value );
}


/**
 * We use this all over the place, so let's save some time
 *
 * @param        $category
 * @param string $action
 * @param string $label
 * @param bool   $onclick_wrap
 *
 * @return string
 * @since    5.38
 * @verified 2017.06.14
 */
function lct_get_gaTracker_onclick( $category, $action = '', $label = '', $onclick_wrap = true )
{
	$onclick = '';


	if ( lct_plugin_active( 'Yoast_GA' ) ) {
		if (
			is_user_logged_in()
			&& ( $ignore_users = lct_get_plugin_setting( 'Yoast_GA', 'ignore_users' ) )
		) {
			foreach ( $ignore_users as $ignore_user ) {
				if ( current_user_can( $ignore_user ) ) {
					return $onclick;
				}
			}
		}


		if ( $action ) {
			$action = ', \'' . esc_js( $action ) . '\'';
		}


		if ( $label ) {
			$label = ', \'' . esc_js( $label ) . '\'';
		}


		if ( lct_get_plugin_setting( 'Yoast_GA', 'universal' ) ) {
			$onclick = sprintf(
				'__gaTracker( \'send\', \'event\', \'%s\'%s%s );',
				lct_i_get_gaTracker_category( $category ),
				$action,
				$label
			);
		} else {
			$onclick = sprintf(
				'_gaq.push( [ \'_trackEvent\', \'%s\'%s%s ] );',
				lct_i_get_gaTracker_category( $category ),
				$action,
				$label
			);
		}

		if ( $onclick_wrap ) {
			$onclick = 'onclick="' . $onclick . '"';
		}
	}


	return $onclick;
}


/**
 * filter out the default results of get_terms()
 *
 * @param string $taxonomy
 * @param string $plugin
 * @param array  $args
 *
 * @return WP_Term[]|int|WP_Error
 * @since        5.38
 * @verified     2020.09.09
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_get_terms( $taxonomy, $plugin, $args = [] )
{
	$default_args = [
		'taxonomy'     => $taxonomy,
		'hide_empty'   => 0,
		'hierarchical' => 1,
		'pad_counts'   => false,
		'meta_query'   => lct_get_org_meta_query( $plugin, $args ),
	];


	$default_args = wp_parse_args( $args, $default_args );


	return get_terms( $default_args );
}


/**
 * filter out the default results of get_users()
 *
 * @param string $plugin
 * @param array  $args
 *
 * @return WP_User[]
 * @since        5.38
 * @verified     2020.09.09
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_get_users( $plugin, $args = [] )
{
	$default_args = [
		'meta_query' => lct_get_org_meta_query( $plugin, $args ),
	];


	$default_args = wp_parse_args( $args, $default_args );


	return get_users( $default_args );
}


/**
 * Produce the meta_query to filter by org
 *
 * @param string $plugin
 * @param array  $args
 *
 * @return array
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_get_org_meta_query( $plugin, $args )
{
	/**
	 * get the stuff going
	 */
	$meta_query = [];


	if (
		( $tmp = zxzu( 'global' ) )
		&& ! isset( $args[ $tmp ] )
		&& function_exists( $plugin . '_get_user_orgs' )
		&& ( $orgs = call_user_func( $plugin . '_get_user_orgs' ) )
	) {
		/**
		 * Let's start off with an OR so that we come from an inclusive angle and then pare it down from there.
		 */
		$meta_query['relation'] = 'OR';


		/**
		 * Check for data that was a direct entry into the DB
		 */
		$meta_query[] = [
			'key'     => lct_org(),
			'value'   => $orgs,
			'compare' => 'IN',
		];


		/**
		 * Check for data that was serialized by ACF
		 */
		foreach ( $orgs as $org ) {
			$meta_query[] = [
				'key'     => lct_org(),
				'value'   => serialize( (string) $org ),
				'compare' => 'LIKE'
			];

			$meta_query[] = [
				'key'     => lct_org(),
				'value'   => serialize( (int) $org ),
				'compare' => 'LIKE'
			];
		}
	}


	return $meta_query;
}


/**
 * Returns all the users that are a part of the org you set
 *
 * @param       $org
 * @param array $args
 *
 * @return array
 * @since    7.35
 * @verified 2017.05.09
 */
function lct_get_org_users( $org = null, $args = [] )
{
	$default_args = [];


	if (
		$org
		&& is_int( $org )
	) {
		/**
		 * get the stuff going
		 */
		$meta_query = [];


		/**
		 * Let's start off with an OR so that we come from an inclusive angle and then pare it down from there.
		 */
		$meta_query['relation'] = 'OR';


		/**
		 * Check for data that was a direct entry into the DB
		 */
		$meta_query[] = [
			'key'   => lct_org(),
			'value' => $org,
		];


		/**
		 * Check for data that was serialized by ACF
		 */
		$meta_query[] = [
			'key'     => lct_org(),
			'value'   => serialize( (string) $org ),
			'compare' => 'LIKE'
		];

		$meta_query[] = [
			'key'     => lct_org(),
			'value'   => serialize( (int) $org ),
			'compare' => 'LIKE'
		];


		$default_args['meta_query'] = $meta_query;
	}


	$default_args = wp_parse_args( $args, $default_args );


	return get_users( $default_args );
}


/**
 * Processes a WordPress admin notice, it is much easier than remembering the whole HTML syntax
 *
 * @param        $message
 * @param int    $class
 * @param bool   $return
 * @param string $container
 *
 * @return string
 * @since    2017.69
 * @verified 2017.09.27
 */
function lct_get_notice( $message, $class = 1, $return = false, $container = 'p' )
{
	if ( is_numeric( $class ) ) {
		$class = (int) $class;
	}


	switch ( $class ) {
		case 'warning':
		case 0:
			$status = 'warning';
			break;


		case 'success':
		case 1:
			$status = 'success';
			break;


		case 'error':
		case - 1:
			$status = 'error';
			break;


		default:
			$status = 'warning';
	}


	$r = sprintf(
		'<div class="notice notice-%s">%s%s%s</div>',
		$status,
		'<' . $container . '>',
		$message,
		'</' . $container . '>'
	);


	if ( ! $return ) {
		echo $r;

		$r = '';
	}


	return $r;
}


/**
 * Display all the browscap data for a particular page
 *
 * @param null $print
 * @param null $hide
 *
 * @return bool|Browscap
 * @since    4.1.11
 * @verified 2019.02.11
 */
function lct_get_user_agent_info( $print = null, $hide = null )
{
	$cache_loc = '';

	$possible_locs   = [];
	$possible_locs[] = '/home/_apps/browscap/';

	if ( function_exists( 'getenv' ) ) {
		$apps_dir = getenv( '_EDITZZ_WAMP_APPS_DIR' );

		if ( $apps_dir ) {
			$possible_locs[] = $apps_dir . '/browscap/';
		}
	}

	$possible_locs[] = '/home/_apps/browscap/';


	foreach ( $possible_locs as $loc ) {
		if ( file_exists( "{$loc}Browscap.php" ) ) {
			include_once( "{$loc}Browscap.php" );
			$cache_loc = "{$loc}cache";

			break;
		}
	}


	if ( ! $cache_loc ) {
		return false;
	}


	try {
		$bc         = new Browscap( $cache_loc );
		$getBrowser = $bc->getBrowser();


		if ( $print ) {
			if ( $hide ) {
				if ( WP_CACHE ) {
					$before = '<pre id="browscap" style="display: none !important;">';
					$after  = '</pre>';
				} else {
					$before = '<!-- ## id="browscap" ';
					$after  = '-->';
				}
			} else {
				$before = '<pre>';
				$after  = '</pre>';
			}

			echo $before;
			print_r( $getBrowser );
			echo $after;


			return false;
		}
	} catch( Exception $e ) {
		$getBrowser = false;
	}


	return $getBrowser;
}


/**
 * Get the post_types that are attached to the taxonomy
 *
 * @param mixed $taxonomy
 *
 * @return array
 * @since    7.36
 * @verified 2017.12.13
 */
function lct_get_post_types_by_taxonomy( $taxonomy = 'category' )
{
	if ( lct_is_a( $taxonomy, 'WP_Term' ) ) {
		$taxonomy = $taxonomy->taxonomy;
	}


	$post_types = [];
	$tax_obj    = get_taxonomy( $taxonomy );


	if ( isset( $tax_obj->object_type ) ) {
		$post_types = $tax_obj->object_type;
	}


	return $post_types;
}


/**
 * Get the first post_type that is attached to the taxonomy
 *
 * @param string $taxonomy
 *
 * @return string
 * @since    7.36
 * @verified 2017.12.13
 */
function lct_get_post_type_by_taxonomy( $taxonomy = 'category' )
{
	$post_type  = '';
	$post_types = lct_get_post_types_by_taxonomy( $taxonomy );


	if ( ! empty( $post_types ) ) {
		$post_types = array_values( $post_types );

		$post_type = $post_types[0];
	}


	return $post_type;
}


/**
 * Get the attachment_id of an image URL
 *
 * @param $url
 *
 * @return array|int|null
 * @since    2018.53
 * @verified 2018.07.24
 */
function lct_get_attachment_id_by_url( $url )
{
	global $wpdb;

	$r = null;


	$url = str_replace( '.org/more/', '.org/', $url );


	if (
		strpos( $url, 'http' ) !== 0
		&& strpos( $url, '//' ) !== 0
	) {
		$url_tmp = lct_url_root_site();
		$url_tmp = parse_url( $url_tmp );


		if ( $url_tmp['path'] ) {
			$url = str_replace( $url_tmp['path'], '', $url );
		}
	}


	$image   = lct_strip_site( $url );
	$uploads = lct_up_dir_only();
	$image   = str_replace( $uploads, '', $image );


	if ( $image ) {
		$attachment_ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT post_id FROM `{$wpdb->postmeta}`
				WHERE `meta_key` = '%s'
				AND `meta_value` = '%s'",
				'_wp_attached_file',
				$image
			)
		);


		if (
			! empty( $attachment_ids )
			&& count( $attachment_ids ) === 1
		) {
			$r = (int) $attachment_ids[0];
		} elseif ( ! empty( $attachment_ids ) ) {
			$r = $attachment_ids;
		}
	}


	return $r;
}


/**
 * Get the DateTime object of the start date & time of today
 *
 * @param bool $gmt
 *
 * @return DateTime
 * @since    2019.1
 * @verified 2019.02.11
 */
function lct_get_DateTime_today( $gmt = false )
{
	$DateTime = lct_DateTime();
	$DateTime->setTime( 0, 0 );


	if ( $gmt ) {
		$DateTime->setTimezone( new DateTimeZone( 'UTC' ) );
	}


	return $DateTime;
}


/**
 * Get the start date & time of today
 *
 * @param string|null $format
 * @param bool        $gmt
 *
 * @return string
 * @since    2018.54
 * @verified 2019.02.11
 */
function lct_get_today( $format = null, $gmt = false )
{
	if ( $format === null ) {
		$format = lct_db_date_format_no_seconds();
	}


	$DateTime = lct_get_DateTime_today( $gmt );


	return $DateTime->format( $format );
}


/**
 * Get the start date & time of today in GMT
 *
 * @param string|null $format
 *
 * @return string
 * @since    2019.1
 * @verified 2019.02.11
 */
function lct_get_today_gmt( $format = null )
{
	return lct_get_today( $format, true );
}


/**
 * Get the end date & time of today
 *
 * @param string|null $format
 * @param bool        $gmt
 *
 * @return string
 * @since    2019.1
 * @verified 2019.02.11
 */
function lct_get_today_end( $format = null, $gmt = false )
{
	if ( $format === null ) {
		$format = lct_db_date_format_no_seconds();
	}


	$DateTime = lct_get_DateTime_today( $gmt );
	$DateTime->modify( '+1 day -1 second' );


	return $DateTime->format( $format );
}


/**
 * Get the DateTime object of the date & time of a day in reference to today
 *
 * @param string      $inc
 * @param string      $type
 * @param bool        $return_as_UTC
 * @param string|null $format
 *
 * @return DateTime|string
 * @since    2019.1
 * @verified 2024.07.10
 */
function lct_get_DateTime_from_today( $inc = '+1', $type = 'day', $return_as_UTC = false, $format = null )
{
	$DateTime = lct_DateTime();


	if ( $return_as_UTC ) {
		$DateTime->setTimezone( new DateTimeZone( 'UTC' ) );
	}


	$DateTime->modify( sprintf( '%s %s', $inc, $type ) );


	if ( $format ) {
		return $DateTime->format( $format );
	}


	return $DateTime;
}


/**
 * Get the DateTime object of the date & time of a day in reference to today
 *
 * @param string      $inc
 * @param string      $type
 * @param string|null $format
 *
 * @return DateTime|string
 * @date     2024.06.18
 * @since    2024.06
 * @verified 2024.06.18
 */
function lct_get_WP_UTC_DateTime_from_today( $inc = '+1', $type = 'day', $format = null )
{
	$DateTime = lct_DateTime();


	if ( $tmp = lct_get_setting( 'timezone_wp' ) ) {
		$DateTime->setTimezone( new DateTimeZone( $tmp ) );
	} else {
		$DateTime->setTimezone( new DateTimeZone( 'UTC' ) );
	}


	$DateTime->modify( sprintf( '%s %s', $inc, $type ) );


	if ( $format ) {
		return $DateTime->format( $format );
	}

	return $DateTime;
}


/**
 * Get the start date & time of a day in reference to today
 *
 * @param string      $inc
 * @param string      $type
 * @param string|null $format
 * @param bool        $gmt
 *
 * @return string
 * @since    2018.54
 * @verified 2019.02.11
 */
function lct_get_day_from_today( $inc = '+1', $type = 'day', $format = null, $gmt = false )
{
	if ( $format === null ) {
		$format = lct_db_date_format_no_seconds();
	}


	$DateTime = lct_get_DateTime_from_today( $inc, $type );
	$DateTime->setTime( 0, 0 );


	if ( $gmt ) {
		$DateTime->setTimezone( new DateTimeZone( 'UTC' ) );
	}


	return $DateTime->format( $format );
}


/**
 * Get the start date & time of a day in reference to today in GMT
 *
 * @param string      $inc
 * @param string      $type
 * @param string|null $format
 *
 * @return string
 * @since    2019.1
 * @verified 2019.02.11
 */
function lct_get_day_from_today_gmt( $inc = '+1', $type = 'day', $format = null )
{
	return lct_get_day_from_today( $inc, $type, $format, true );
}


/**
 * Get the end date & time of a day in reference to today
 *
 * @param string      $inc
 * @param string      $type
 * @param string|null $format
 * @param bool        $gmt
 *
 * @return string
 * @since    2019.1
 * @verified 2019.02.11
 */
function lct_get_day_from_today_end( $inc = '+1', $type = 'day', $format = null, $gmt = false )
{
	if ( $format === null ) {
		$format = lct_db_date_format_no_seconds();
	}


	$DateTime = lct_get_DateTime_from_today( $inc, $type, $gmt );
	$DateTime->modify( '+1 day -1 second' );


	return $DateTime->format( $format );
}


/**
 * Get the date & time of a day in reference to today
 *
 * @param string      $inc
 * @param string      $type
 * @param string|null $format
 * @param bool        $gmt
 *
 * @return string
 * @since    2019.1
 * @verified 2019.02.11
 */
function lct_get_date_from_today( $inc = '+1', $type = 'day', $format = null, $gmt = false )
{
	if ( $format === null ) {
		$format = lct_db_date_format_no_seconds();
	}


	$DateTime = lct_get_DateTime_from_today( $inc, $type, $gmt );


	return $DateTime->format( $format );
}


/**
 * Get the DateTime object of the date & same time of a day in reference to a date
 *
 * @param string|null $date
 * @param string      $inc
 * @param string      $type
 * @param bool        $return_as_UTC
 * @param bool        $input_is_UTC
 *
 * @return DateTime
 * @since    2019.1
 * @verified 2023.09.14
 */
function lct_get_DateTime_from_date( $date = null, $inc = '+1', $type = 'day', $return_as_UTC = false, $input_is_UTC = false )
{
	$DateTime = lct_DateTime( $date, $input_is_UTC, $return_as_UTC );


	if ( ! lct_is_a( $DateTime, 'DateTime' ) ) {
		$data = compact( 'DateTime', 'date', 'inc', 'type', 'return_as_UTC', 'input_is_UTC' );


		if ( $error = new WP_Error( 'lct_get_DateTime_from_date', 'lct_get_DateTime_from_date() Function failed', $data ) ) {
			if ( function_exists( 'afwp_create_logged_error' ) ) {
				afwp_create_logged_error( null, null, $error );
			} else {
				lct_debug_to_error_log( $error );
			}
		}


		return lct_DateTime( null, $input_is_UTC, $return_as_UTC );
	}


	if ( $return_as_UTC ) {
		$DateTime->setTimezone( new DateTimeZone( 'UTC' ) );
	}


	$DateTime->modify( sprintf( '%s %s', $inc, $type ) );


	return $DateTime;
}


/**
 * Get the start date & time of a day in reference to a date
 *
 * @param string|null $date
 * @param string      $inc
 * @param string      $type
 * @param string|null $format
 * @param bool        $gmt
 *
 * @return string
 * @since    2019.1
 * @verified 2019.02.11
 */
function lct_get_day_from_date( $date = null, $inc = '+1', $type = 'day', $format = null, $gmt = false )
{
	if ( $format === null ) {
		$format = lct_db_date_format_no_seconds();
	}


	$DateTime = lct_get_DateTime_from_date( $date, $inc, $type );
	$DateTime->setTime( 0, 0 );


	if ( $gmt ) {
		$DateTime->setTimezone( new DateTimeZone( 'UTC' ) );
	}


	return $DateTime->format( $format );
}


/**
 * Get the start date & time of a day in reference to a date in GMT
 *
 * @param string|null $date
 * @param string      $inc
 * @param string      $type
 * @param string|null $format
 *
 * @return string
 * @since    2019.1
 * @verified 2019.02.11
 */
function lct_get_day_from_date_gmt( $date = null, $inc = '+1', $type = 'day', $format = null )
{
	return lct_get_day_from_date( $date, $inc, $type, $format, true );
}


/**
 * Get the start date & time of a day in reference to a date
 *
 * @param string|null $date
 * @param string      $inc
 * @param string      $type
 * @param string|null $format
 * @param bool        $gmt
 * @param bool        $input_is_UTC
 *
 * @return string
 * @since    2019.1
 * @verified 2019.02.11
 */
function lct_get_date_from_date( $date = null, $inc = '+1', $type = 'day', $format = null, $gmt = false, $input_is_UTC = false )
{
	if ( $format === null ) {
		$format = lct_db_date_format();
	}


	$DateTime = lct_get_DateTime_from_date( $date, $inc, $type, false, $input_is_UTC );


	if ( $gmt ) {
		$DateTime->setTimezone( new DateTimeZone( 'UTC' ) );
	}


	return $DateTime->format( $format );
}


/**
 * Get the start date & time of a day in reference to a date in GMT
 *
 * @param string|null $date
 * @param string      $inc
 * @param string      $type
 * @param string|null $format
 * @param bool        $input_is_UTC
 *
 * @return string
 * @since    2019.1
 * @verified 2019.02.11
 */
function lct_get_date_from_date_gmt( $date = null, $inc = '+1', $type = 'day', $format = null, $input_is_UTC = false )
{
	return lct_get_date_from_date( $date, $inc, $type, $format, true, $input_is_UTC );
}


/**
 * Turn a number into a percent
 *
 * @param     $value
 * @param int $accuracy
 *
 * @return string
 * @since    2018.54
 * @verified 2018.06.18
 */
function lct_get_percent( $value, $accuracy = 2 )
{
	if ( ! $value ) {
		$value = 0;
	}


	if ( $value ) {
		$value = $value * 100;
	}


	$value = number_format( $value, $accuracy ) . '%';


	return $value;
}


/**
 * Get the human readable version
 *
 * @param int  $bytes
 * @param bool $binaryPrefix
 *
 * @return string
 * @since        2019.25
 * @verified     2019.10.30
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_get_nice_file_size( $bytes, $binaryPrefix = true )
{
	if ( $binaryPrefix ) {
		$unit = [ 'B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB' ];


		if ( $bytes == 0 ) {
			return '0 ' . $unit[0];
		}


		return @round( $bytes / pow( 1024, ( $i = floor( log( $bytes, 1024 ) ) ) ), 2 ) . ' ' . ( isset( $unit[ $i ] ) ? $unit[ $i ] : 'B' );
	} else {
		$unit = [ 'B', 'KB', 'MB', 'GB', 'TB', 'PB' ];


		if ( $bytes == 0 ) {
			return '0 ' . $unit[0];
		}


		return @round( $bytes / pow( 1000, ( $i = floor( log( $bytes, 1000 ) ) ) ), 2 ) . ' ' . ( isset( $unit[ $i ] ) ? $unit[ $i ] : 'B' );
	}
}


/**
 * Retrieves the amount of comments a post has, but only the type you ask for
 *
 * @param WP_Post|int $post_id Optional. Post ID or WP_Post object. Default is the global `$post`.
 * @param string      $type
 *
 * @return int If the post exists, an int representing the number of comments
 *                    the post has, otherwise 0.
 * @derived  from get_comments_number()
 * @since    2018.59
 * @verified 2020.10.26
 */
function lct_get_comments_number_by_type( $post_id = 0, $type = 'comment' )
{
	$post = get_post( $post_id );

	if ( $type === '' ) {
		$type = 'comment';
	}


	if ( ! $post ) {
		$count   = 0;
		$post_id = 0;
	} else {
		global $wpdb;


		if ( $type === 'comment' ) {
			$count = (int) $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM `{$wpdb->comments}` 
						WHERE `comment_post_ID` = %d AND 
						`comment_approved` = '1' AND
						`comment_type` IN ( 'comment', '' )",
					$post_id
				)
			);
		} else {
			$count = (int) $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM `{$wpdb->comments}` 
						WHERE `comment_post_ID` = %d AND 
						`comment_approved` = '1' AND
						`comment_type` = %s",
					$post_id,
					$type
				)
			);
		}


		$post_id = $post->ID;
	}


	/**
	 * Filters the returned comment count for a post.
	 *
	 * @param int $count   An int representing the number of comments a post has, otherwise 0.
	 * @param int $post_id Post ID.
	 */
	return apply_filters( 'lct/get_comments_number_by_type', $count, $post_id );
}


/**
 * Create an email ready version of the from email
 *
 * @param $from
 * @param $from_name
 *
 * @return string
 * @since    2018.59
 * @verified 2018.07.27
 */
function lct_get_email_ready_from( $from, $from_name )
{
	return sprintf( '"%s" <%s>', $from_name, $from );
}


/**
 * Get a list of all posts that have a specific image in the post_content
 *
 * @param $att_id
 * @param $size_meta
 *
 * @return array|null|object
 * @since    2018.64
 * @verified 2018.09.28
 */
function lct_get_posts_with_image( $att_id, $size_meta )
{
	global $wpdb;

	$r = null;


	if ( $att_id ) {
		$resp = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT `ID` FROM `{$wpdb->posts}` WHERE 
				(
					`post_content` LIKE '%s' OR 
					`post_excerpt` LIKE '%s' OR
					`post_content` LIKE '%s' OR 
					`post_excerpt` LIKE '%s'
				) AND 
				`post_type` NOT IN ( 'revision', 'attachment' )",
				'%' . $att_id . '%',
				'%' . $att_id . '%',
				'%' . $size_meta['file'] . '%',
				'%' . $size_meta['file'] . '%'
			)
		);
	} else {
		$resp = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT `ID` FROM `{$wpdb->posts}` WHERE 
				(
					`post_content` LIKE '%s' OR 
					`post_excerpt` LIKE '%s'
				) AND 
				`post_type` NOT IN ( 'revision', 'attachment' )",
				'%' . $size_meta['file'] . '%',
				'%' . $size_meta['file'] . '%'
			)
		);
	}


	if ( ! empty( $resp ) ) {
		$r = $resp;
	}


	return $r;
}


/**
 * Get a list of all posts that have a specific image in the postmeta
 *
 * @param $att_id
 *
 * @return array|null|object
 * @since    2018.64
 * @verified 2018.09.28
 */
function lct_get_featured_image_posts_with_image( $att_id )
{
	global $wpdb;

	$r = null;


	if ( $att_id === 'all' ) {
		$resp = $wpdb->get_results( "SELECT * FROM `{$wpdb->postmeta}` WHERE `meta_key` = '_thumbnail_id'" );
	} else {
		$resp = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT `post_id` FROM `{$wpdb->postmeta}` WHERE 
			`meta_value` = '%s' AND 
			`meta_key` = '_thumbnail_id'",
				$att_id
			)
		);
	}


	if ( ! empty( $resp ) ) {
		$r = $resp;
	}


	return $r;
}


/**
 * Get a list of all posts that have a specific image in the postmeta
 *
 * @param $att_id
 * @param $size_meta
 *
 * @return array|null|object
 * @since    2018.64
 * @verified 2018.09.28
 */
function lct_get_postmetas_with_image( $att_id, $size_meta )
{
	global $wpdb;

	$r = null;


	if ( $att_id ) {
		$resp = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT `post_id` FROM `{$wpdb->postmeta}` WHERE 
				(
					`meta_value` = '%s' OR 
					`meta_value` LIKE '%s'
				) AND 
				`meta_key` NOT IN ( '_thumbnail_id', '_wp_attached_file', '_wp_attachment_metadata', '_wp_attachment_backup_sizes' )",
				$att_id,
				'%' . $size_meta['file'] . '%'
			)
		);
	} else {
		$resp = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT `post_id` FROM `{$wpdb->postmeta}` WHERE 
				`meta_value` LIKE '%s' AND 
				`meta_key` NOT IN ( '_thumbnail_id', '_wp_attached_file', '_wp_attachment_metadata', '_wp_attachment_backup_sizes' )",
				'%' . $size_meta['file'] . '%'
			)
		);
	}


	if ( ! empty( $resp ) ) {
		$r = $resp;
	}


	return $r;
}


/**
 * Get a list of all terms that have a specific image in the termmeta
 *
 * @param $att_id
 * @param $size_meta
 *
 * @return array|null|object
 * @since    2018.64
 * @verified 2018.09.28
 */
function lct_get_termmetas_with_image( $att_id, $size_meta )
{
	global $wpdb;

	$r = null;


	if ( $att_id ) {
		$resp = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT `term_id` FROM `{$wpdb->termmeta}` WHERE 
				(
					`meta_value` = '%s' OR 
					`meta_value` LIKE '%s'
				)",
				$att_id,
				'%' . $size_meta['file'] . '%'
			)
		);
	} else {
		$resp = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT `term_id` FROM `{$wpdb->termmeta}` WHERE 
				`meta_value` LIKE '%s'",
				'%' . $size_meta['file'] . '%'
			)
		);
	}


	if ( ! empty( $resp ) ) {
		$r = $resp;
	}


	return $r;
}


/**
 * Get a list of all users that have a specific image in the usermeta
 *
 * @param $att_id
 * @param $size_meta
 *
 * @return array|null|object
 * @since    2018.64
 * @verified 2018.09.28
 */
function lct_get_usermetas_with_image( $att_id, $size_meta )
{
	global $wpdb;

	$r = null;


	if ( $att_id ) {
		$resp = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT `user_id` FROM `{$wpdb->usermeta}` WHERE 
				(
					`meta_value` = '%s' OR 
					`meta_value` LIKE '%s'
				)",
				$att_id,
				'%' . $size_meta['file'] . '%'
			)
		);
	} else {
		$resp = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT `user_id` FROM `{$wpdb->usermeta}` WHERE 
				`meta_value` LIKE '%s'",
				'%' . $size_meta['file'] . '%'
			)
		);
	}


	if ( ! empty( $resp ) ) {
		$r = $resp;
	}


	return $r;
}


/**
 * Get a list of all options that have a specific image in the value
 *
 * @param $att_id
 * @param $size_meta
 *
 * @return array|null|object
 * @since    2018.64
 * @verified 2018.09.28
 */
function lct_get_options_with_image( $att_id, $size_meta )
{
	global $wpdb;

	$r = null;


	if ( $att_id ) {
		$resp = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT `option_name` FROM `{$wpdb->options}` WHERE 
			(
				`option_value` = '%s' OR 
				`option_value` LIKE '%s'
			)",
				$att_id,
				'%' . $size_meta['file'] . '%'
			)
		);
	} else {
		$resp = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT `option_name` FROM `{$wpdb->options}` WHERE 
			`option_value` LIKE '%s'",
				'%' . $size_meta['file'] . '%'
			)
		);
	}


	if ( ! empty( $resp ) ) {
		$r = $resp;
	}


	return $r;
}


/**
 * Return all metadata for an object, all values as a single value, instead of an array
 *
 * @param string $type
 * @param int    $obj_id
 * @param bool   $return_bool_values
 * @param array  $exclude
 *
 * @return array|null
 * @since        2019.3
 * @verified     2019.04.01
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_get_all_metadata( $type, $obj_id, $return_bool_values = false, $exclude = [] )
{
	$r = null;


	if ( $metas = get_metadata( $type, $obj_id ) ) {
		if ( $return_bool_values ) {
			$r = array_map(
				function ( $v ) {
					if (
						count( $v ) === 1
						&& isset( $v[0] )
					) {
						$v = (bool) $v[0];
					}

					return $v;
				},
				$metas
			);
		} else {
			if ( ! empty( $exclude ) ) {
				$exclude_strpos = [];


				if ( ! empty( $exclude['strpos'] ) ) {
					$exclude_strpos = $exclude['strpos'];
					unset( $exclude['strpos'] );
				}


				if ( ! empty( $exclude['strpos_0'] ) ) {
					$exclude_strpos_0 = $exclude['strpos_0'];
					unset( $exclude['strpos_0'] );
				}


				foreach ( $metas as $k => $v ) {
					if (
						! empty( $exclude )
						&& in_array( $k, $exclude )
					) {
						unset( $metas[ $k ] );


						continue;
					}


					if (
						! empty( $exclude_strpos )
						&& strpos_array( $k, $exclude_strpos )
					) {
						unset( $metas[ $k ] );


						continue;
					}


					if (
						! empty( $exclude_strpos_0 )
						&& strpos_array( $k, $exclude_strpos_0, false, true ) === 0
					) {
						unset( $metas[ $k ] );


						continue;
					}
				}
			}


			$r = array_map(
				function ( $v ) {
					if (
						count( $v ) === 1
						&& isset( $v[0] )
					) {
						$v = maybe_unserialize( $v[0] );
					}

					return $v;
				},
				$metas
			);
		}
	}


	return $r;
}


/**
 * Return all postmeta for a post, all values as a single value, instead of an array
 *
 * @param int|WP_Post $post_id
 * @param bool        $return_bool_values
 * @param array       $exclude
 *
 * @return array|null
 * @since    2019.1
 * @verified 2019.04.01
 */
function lct_get_all_post_meta( $post_id, $return_bool_values = false, $exclude = [] )
{
	if ( lct_is_a( $post_id, 'WP_Post' ) ) {
		$post_id = $post_id->ID;
	}


	return lct_get_all_metadata( 'post', $post_id, $return_bool_values, $exclude );
}


/**
 * Return all usermeta for a user, all values as a single value, instead of an array
 *
 * @param int|WP_User $user_id
 * @param bool        $return_bool_values
 * @param array       $exclude
 *
 * @return array|null
 * @since    2019.26
 * @verified 2019.11.04
 */
function lct_get_all_user_meta( $user_id, $return_bool_values = false, $exclude = [] )
{
	if ( lct_is_a( $user_id, 'WP_User' ) ) {
		$user_id = $user_id->ID;
	}


	return lct_get_all_metadata( 'user', $user_id, $return_bool_values, $exclude );
}


/**
 * Return all termmeta for a term, all values as a single value, instead of an array
 *
 * @param int|WP_Term $term_id
 * @param bool        $return_bool_values
 * @param array       $exclude
 *
 * @return array|null
 * @since    2019.1
 * @verified 2019.04.01
 */
function lct_get_all_term_meta( $term_id, $return_bool_values = false, $exclude = [] )
{
	if ( lct_is_a( $term_id, 'WP_Term' ) ) {
		$term_id = $term_id->term_id;
	}


	return lct_get_all_metadata( 'term', $term_id, $return_bool_values, $exclude );
}


/**
 * Get the role of the current user in a human readable format
 *
 * @param bool $return_all
 *
 * @return string
 * @since    2019.25
 * @verified 2020.01.21
 */
function lct_get_current_user_role_display( $return_all = false )
{
	$r = 'Guest';


	if (
		is_user_logged_in()
		&& ( $current_user = wp_get_current_user() )
		&& ! empty( $current_user->caps )
	) {
		$roles = [];


		foreach ( $current_user->caps as $role => $not_needed ) {
			$role = apply_filters( 'lct/get_current_user_role_display/raw_role', $role );


			if ( $role_label = lct_get_role_name( $role ) ) {
				$roles[] = $role_label;
			}


			if ( ! $return_all ) {
				break;
			}
		}


		$r = lct_return( $roles, ', ' );
	}


	return $r;
}


/**
 * Clean the ID of the post_id
 *
 * @param int|WP_Post $post_id
 *
 * @return int|null
 * @since    2019.25
 * @verified 2019.09.18
 */
function lct_get_clean_post_id( $post_id )
{
	if ( empty( $post_id ) ) {
		return null;
	} elseif ( is_int( $post_id ) ) {
		return $post_id;
	} elseif ( is_numeric( $post_id ) ) {
		return (int) $post_id;
	} elseif ( lct_is_a( $post_id, 'WP_Post' ) ) {
		return $post_id->ID;
	} elseif (
		is_array( $post_id )
		&& isset( $post_id['ID'] )
	) {
		return (int) $post_id['ID'];
	}

	return null;
}


/**
 * Clean the term_id of the term
 *
 * @param int|WP_Term $term_id
 *
 * @return int
 * @since    2019.25
 * @verified 2021.05.21
 */
function lct_get_clean_term_id( $term_id )
{
	if ( empty( $term_id ) ) {
		return null;
	} elseif ( is_int( $term_id ) ) {
		return $term_id;
	} elseif ( is_numeric( $term_id ) ) {
		return (int) $term_id;
	} elseif ( lct_is_a( $term_id, 'WP_Term' ) ) {
		return $term_id->term_id;
	} elseif (
		is_array( $term_id )
		&& isset( $term_id['term_id'] )
	) {
		return (int) $term_id['term_id'];
	}


	return null;
}


/**
 * Clean the ID of the user_id
 *
 * @param int|WP_Post $user_id
 *
 * @return int
 * @since    2019.26
 * @verified 2019.10.31
 */
function lct_get_clean_user_id( $user_id )
{
	if ( empty( $user_id ) ) {
		return null;
	} elseif ( is_int( $user_id ) ) {
		return $user_id;
	} elseif ( is_numeric( $user_id ) ) {
		return (int) $user_id;
	} elseif ( lct_is_a( $user_id, 'WP_User' ) ) {
		return $user_id->ID;
	} elseif (
		is_array( $user_id )
		&& isset( $user_id['ID'] )
	) {
		return (int) $user_id['ID'];
	}

	return null;
}


/**
 * Get the human readable role name
 *
 * @param string $user_role
 *
 * @return string
 * @since        2019.26
 * @verified     2019.10.31
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_get_role_name( $user_role )
{
	if (
		! empty( $user_role )
		&& ( $wp_roles = wp_roles()->get_names() )
		&& ! empty( $wp_roles[ $user_role ] )
	) {
		$user_role = $wp_roles[ $user_role ];
	}


	return $user_role;
}
