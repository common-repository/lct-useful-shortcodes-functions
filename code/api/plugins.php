<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * returns the full path of a plugin's basename
 *
 * @param string $basename
 *
 * @return string
 * @since        7.38
 * @verified     2016.11.28
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_path_basename( $basename )
{
	return lct_path_plugin( true ) . $basename;
}


/**
 * returns the full url of a plugin's basename
 *
 * @param string $basename
 *
 * @return string
 * @since        7.38
 * @verified     2016.11.28
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_url_basename( $basename )
{
	return lct_url_plugin( true ) . $basename;
}


/**
 * Store a plugin's info in the main obj
 *
 * @param string $plugin_basename
 * @param string $save_as
 *
 * @return bool
 * @since        7.38
 * @verified     2020.09.07
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_set_plugin( $plugin_basename, $save_as )
{
	$r             = false;
	$plugin_status = [];

	if ( ! ( $data = lct_get_data( '_plugins' ) ) ) {
		$data = [];
	}


	//need this for ajax calls
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}


	if (
		function_exists( 'is_plugin_active' )
		&& is_plugin_active( $plugin_basename )
	) {
		$plugin = get_file_data( lct_path_basename( $plugin_basename ), [ 'Version' => 'Version' ], 'plugin' );
		//$plugin = get_plugin_data( lct_path_basename( $plugin_basename ) ); //This is slower


		$r                        = true;
		$plugin_status['active']  = true;
		$plugin_status['version'] = $plugin['Version'];
	}


	$plugin_status    = wp_parse_args( $plugin_status, lct_plugin_default_args() );
	$data[ $save_as ] = $plugin_status;


	lct_set_data( '_plugins', $data );


	return $r;
}


/**
 * The default array for set_plugin
 *
 * @return array
 * @since    7.38
 * @verified 2020.09.07
 */
function lct_plugin_default_args()
{
	return [
		'active'  => false,
		'version' => '0.0',
	];
}


/**
 * Get a plugin setting
 *
 * @param string $plugin
 * @param string $setting
 * @param mixed  $value
 *
 * @return mixed
 * @since        7.42
 * @verified     2020.09.07
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_get_plugin_setting( $plugin, $setting, $value = null )
{
	if (
		( $data = lct_get_data( '_plugins' ) )
		&& isset( $data[ $plugin ][ $setting ] )
	) {
		$value = $data[ $plugin ][ $setting ];
	}


	return $value;
}


/**
 * Update a plugin status
 *
 * @param string $plugin
 * @param string $setting
 * @param mixed  $value
 *
 * @return bool
 * @since        7.42
 * @verified     2020.09.07
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_update_plugin_setting( $plugin, $setting, $value = null )
{
	$r = false;


	if (
		lct_plugin_active( $plugin )
		&& ( $data = lct_get_data( '_plugins' ) )
		&& isset( $data[ $plugin ] )
	) {
		$data[ $plugin ][ $setting ] = $value;


		lct_set_data( '_plugins', $data );


		$r = true;
	}


	return $r;
}


/**
 * Is a plugin action?
 *
 * @param string $plugin
 *
 * @return bool|null
 * @since        7.38
 * @verified     2019.07.12
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_plugin_active( $plugin )
{
	$r = null;


	if (
		( $data = lct_get_data( '_plugins' ) )
		&& isset( $data[ $plugin ]['active'] )
	) {
		$r = $data[ $plugin ]['active'];
	}


	return $r;
}


/**
 * Returns the version of a plugin
 *
 * @param string $plugin
 *
 * @return string|null
 * @since        7.38
 * @verified     2020.09.07
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_plugin_version( $plugin )
{
	$r = null;


	if (
		( $data = lct_get_data( '_plugins' ) )
		&& isset( $data[ $plugin ]['version'] )
	) {
		$r = $data[ $plugin ]['version'];
	}


	return $r;
}


/**
 * Set the settings for the Yoast_GA plugin
 *
 * @since    7.42
 * @verified 2022.01.07
 */
function lct_set_Yoast_GA_settings()
{
	$universal    = false;
	$ignore_users = [];


	if ( ! lct_plugin_active( 'Yoast_GA' ) ) {
		return;
	}


	$yst_ga = get_option( 'yst_ga' );


	if (
		! empty( $yst_ga )
		&& isset( $yst_ga['ga_general'] )
	) {
		if ( $yst_ga['ga_general']['enable_universal'] ) {
			$universal = true;
		}

		if ( $yst_ga['ga_general']['ignore_users'] ) {
			$ignore_users = $yst_ga['ga_general']['ignore_users'];
		}
	} else {
		$universal = true;

		if ( ! empty( $yst_ga['ignore_users'] ) ) {
			$ignore_users = $yst_ga['ignore_users'];
		}
	}


	lct_update_plugin_setting( 'Yoast_GA', 'universal', $universal );
	lct_update_plugin_setting( 'Yoast_GA', 'ignore_users', $ignore_users );
}
