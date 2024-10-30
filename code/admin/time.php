<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2018.10.08
 */
class lct_admin_time
{
	public $timezone_wp;
	public $timezone_user;
	public $timezone_user_timezone;
	public $timezone_user_offset = 0;
	public $timezone_user_delta = 0;
	public $timezone_user_delta_hour = '00';

	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2016.12.21
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
	 * @since    7.58
	 * @verified 2018.10.08
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
		add_action( 'plugins_loaded', [ $this, 'timezone_settings' ], 99 );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Update all the time zone settings for later use
	 *
	 * @since    7.58
	 * @verified 2019.02.16
	 */
	function timezone_settings()
	{
		$tz_string = get_option( 'timezone_string', 'America/New_York' );
		if ( ! $tz_string ) {
			$tz_string = 'UTC';
		}

		$this->timezone_user = $this->timezone_wp = $tz_string;


		if (
			lct_plugin_active( 'acf' )
			&& ( $user_id = get_current_user_id() )
		) {
			if ( lct_acf_get_option_raw( 'timezone_user', true ) ) {
				$tz_user = get_field( zxzacf( 'timezone_user' ), lct_u( $user_id ) );
			} else {
				$tz_user = $this->timezone_wp;
			}


			$tz_user = apply_filters( 'lct/time/timezone_user', $tz_user, $user_id );


			if (
				$tz_user
				//&& $tz_user !== $this->timezone_wp
			) {
				global $pagenow;

				$this->timezone_user = $tz_user;


				if (
					! $pagenow
					|| $pagenow !== 'options-general.php'
				) {
					add_filter( 'pre_option_timezone_string', [ $this, 'timezone_string' ], 99, 3 );

					add_filter( 'pre_option_gmt_offset', [ $this, 'gmt_offset' ], 99, 3 );

					//add_filter( 'get_post_time', [ $this, 'get_post_time' ], 99, 3 ); //Do not create a filter for this, it will cause CHAOS, don't even use the function

					add_filter( 'get_the_date', [ $this, 'get_the_date' ], 99, 3 );

					add_filter( 'get_the_time', [ $this, 'get_the_time' ], 99, 3 );

					//add_filter( 'get_post_modified_time', [ $this, 'get_post_modified_time' ], 99, 3 ); //Do not create a filter for this, it will cause CHAOS, don't even use the function

					add_filter( 'get_the_modified_date', [ $this, 'get_the_modified_date' ], 99, 3 );

					add_filter( 'get_the_modified_time', [ $this, 'get_the_modified_time' ], 99, 3 );

					add_filter( 'post_date_column_status', [ $this, 'post_date_column_status' ], 99, 4 );

					add_filter( 'post_date_column_time', [ $this, 'post_date_column_time' ], 99, 4 );

					//TODO: cs - Add filters for other's e.g., users - 12/21/2016 10:59 PM
				}
			}
		}


		//This code will only work in America, I would need to update it to be internationally viable
		$this->timezone_user_timezone = timezone_open( $this->timezone_user );
		$datetime_now                 = new DateTime( 'now', $this->timezone_user_timezone );
		$this->timezone_user_offset   = $this->timezone_user_timezone->getOffset( $datetime_now );
		if ( $this->timezone_user_offset ) {
			$this->timezone_user_offset = round( $this->timezone_user_offset / HOUR_IN_SECONDS, 2 );
			$this->timezone_user_delta  = round( $this->timezone_user_offset * - 1 );
		}
		$this->timezone_user_delta_hour = sprintf( '%02d', $this->timezone_user_delta );


		lct_update_setting( 'timezone_wp', $this->timezone_wp );
		lct_update_setting( 'timezone_user', $this->timezone_user );
		lct_update_setting( 'timezone_user_timezone', $this->timezone_user_timezone );
		lct_update_setting( 'timezone_user_offset', $this->timezone_user_offset );
		lct_update_setting( 'timezone_user_delta', $this->timezone_user_delta );
		lct_update_setting( 'timezone_user_delta_hour', $this->timezone_user_delta_hour );
	}


	/**
	 * Use the logged in user's time zone string, if it is available
	 *
	 * @unused   param $pre
	 * @unused   param $option
	 * @unused   param $default
	 * @return string
	 * @since    7.58
	 * @verified 2018.10.08
	 */
	function timezone_string()
	{
		return $this->timezone_user;
	}


	/**
	 * Use the logged in user's gmt_offset, if it is available
	 *
	 * @unused   param $pre
	 * @unused   param $option
	 * @unused   param $default
	 * @return bool|mixed
	 * @since    2018.65
	 * @verified 2018.10.08
	 */
	function gmt_offset()
	{
		return $this->timezone_user_offset;
	}


	/**
	 * Use the logged-in user's time zone, to adjust the time accordingly
	 *
	 * @param string|int $the_date Formatted date string or Unix timestamp if `$format` is 'U' or 'G'.
	 * @param string     $format   PHP date format.
	 * @param WP_Post    $post     The post object.
	 *
	 * @return string|int|false Date the current post was written. False on failure.
	 * @since    7.58
	 * @verified 2023.12.21
	 */
	function get_the_date( $the_date, $format, $post )
	{
		if ( $post ) {
			$_format = ! empty( $format ) ? $format : get_option( 'date_format' );


			if ( $not_gmt = get_date_from_gmt( $post->post_date_gmt, $_format ) ) {
				$the_date = $not_gmt;
			}
		}


		return $the_date;
	}


	/**
	 * Use the logged-in user's time zone, to adjust the time accordingly
	 *
	 * @param string|int|false $the_time  The formatted time or false if no post is found.
	 * @param string           $format    Format to use for retrieving the time the post
	 *                                    was modified. Accepts 'G', 'U', or PHP date format.
	 * @param WP_Post|null     $post      WP_Post object or null if no post is found.
	 *
	 * @return string|int|false Formatted date string or Unix timestamp. False on failure.
	 * @since    7.58
	 * @verified 2023.12.21
	 */
	function get_the_modified_time( $the_time, $format, $post )
	{
		if ( $post ) {
			$_format = ! empty( $format ) ? $format : get_option( 'time_format' );


			if ( $not_gmt = get_date_from_gmt( $post->post_modified_gmt, $_format ) ) {
				$the_time = $not_gmt;
			}
		}


		return $the_time;
	}


	/**
	 * Use the logged-in user's time zone, to adjust the time accordingly
	 * //TODO: cs - Do not create a filter for this, it will cause CHAOS - 12/21/2023 2:42 PM
	 *
	 * @param $time
	 * @param $format
	 * @param $gmt
	 *
	 * @return string
	 * @since    7.58
	 * @verified 2023.12.19
	 */
	function get_post_modified_time( $time, $format, $gmt )
	{
		if (
			! $gmt
			&& ! lct_doing_api()
			&& ( $post = get_post() )
			&& ! lct_is_wp_error( $post )
		) {
			if ( $format == '' ) {
				$format = get_option( 'time_format' );
			}


			if ( $not_gmt = get_date_from_gmt( $post->post_modified_gmt, $format ) ) {
				$time = $not_gmt;
			}
		}


		return $time;
	}


	/**
	 * Use the logged-in user's time zone, to adjust the time accordingly
	 *
	 * @param string|int|false $the_time The formatted date or false if no post is found.
	 * @param string           $format   PHP date format.
	 * @param WP_Post|null     $post     WP_Post object or null if no post is found.
	 *
	 * @return string|int|false Date the current post was modified. False on failure.
	 * @date     2023.12.21
	 * @since    2023.04
	 * @verified 2023.12.21
	 */
	function get_the_modified_date( $the_time, $format, $post )
	{
		if ( $post ) {
			$_format = ! empty( $format ) ? $format : get_option( 'date_format' );


			if ( $not_gmt = get_date_from_gmt( $post->post_modified_gmt, $_format ) ) {
				$the_time = $not_gmt;
			}
		}


		return $the_time;
	}


	/**
	 * Use the logged-in user's time zone, to adjust the time accordingly
	 *
	 * @param string|int $the_time   Formatted date string or Unix timestamp if `$format` is 'U' or 'G'.
	 * @param string     $format     Format to use for retrieving the time the post
	 *                               was written. Accepts 'G', 'U', or PHP date format.
	 * @param WP_Post    $post       Post object.
	 *
	 * @return string|int|false Formatted date string or Unix timestamp if `$format` is 'U' or 'G'.
	 *                           False on failure.
	 * @date     2023.12.21
	 * @since    2023.04
	 * @verified 2023.12.21
	 */
	function get_the_time( $the_time, $format, $post )
	{
		if ( $post ) {
			$_format = ! empty( $format ) ? $format : get_option( 'time_format' );


			if ( $not_gmt = get_date_from_gmt( $post->post_date_gmt, $_format ) ) {
				$the_time = $not_gmt;
			}
		}


		return $the_time;
	}


	/**
	 * Adjust for custom statuses
	 *
	 * @param string  $status      The status text.
	 * @param WP_Post $post        Post object.
	 * @param string  $column_name The column name.
	 * @param string  $mode        The list display mode ('excerpt' or 'list').
	 *
	 * @return string
	 * @date     2023.12.21
	 * @since    2023.04
	 * @verified 2024.01.23
	 */
	function post_date_column_status( $status, $post, $column_name, $mode )
	{
		if ( in_array( $post->post_status, [ 'publish', 'future' ] ) ) {
			$created_date = get_the_date( null, $post );
			$created_time = get_the_time( null, $post );

			return sprintf( 'Created: %s %s<br /><em>%s</em>', $created_date, $created_time, $status );
		} elseif ( $tmp = lct_get_status_obj_from_status_slug( $post->post_status ) ) {
			$created_date = get_the_date( null, $post );
			$created_time = get_the_time( null, $post );

			return sprintf( 'Created: %s %s<br /><em>%s</em> as of', $created_date, $created_time, $tmp->name );
		}


		return $status;
	}


	/**
	 * Adjust for timezones
	 *
	 * @param string  $t_time      The published time.
	 * @param WP_Post $post        Post object.
	 * @param string  $column_name The column name.
	 * @param string  $mode        The list display mode ('excerpt' or 'list').
	 *
	 * @return string
	 * @date     2023.12.21
	 * @since    2023.04
	 * @verified 2023.12.21
	 */
	function post_date_column_time( $t_time, $post, $column_name, $mode )
	{
		$format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );


		if ( in_array( $post->post_status, [ 'publish', 'future' ] ) ) {
			return get_the_date( $format, $post );
		} else {
			return get_the_modified_date( $format, $post );
		}
	}
}
