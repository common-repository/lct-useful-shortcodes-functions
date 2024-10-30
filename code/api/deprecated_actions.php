<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_action( 'lct_acf_new_post', 'deprecated_lct_acf_new_post', 2 );
/**
 * Renamed Action
 *
 * @return bool
 * @deprecated 2017.34
 * @since      5.30
 * @verified   2017.05.09
 */
function deprecated_lct_acf_new_post()
{
	add_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_action', 10, 4 );
	_deprecated_hook(
		str_replace( 'deprecated_', '', __FUNCTION__ ),
		'2017.34',
		'lct/acf/new_post'
	);
	remove_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_action' );


	remove_all_actions( 'lct_acf_new_post' );


	return false;
}


add_action( 'shutdown', 'shutdown_deprecated_lct_acf_new_post' );
function shutdown_deprecated_lct_acf_new_post()
{
	lct_shutdown_deprecated_action( 'lct_acf_new_post' );
}


add_action( 'lct_maps_google_api', 'deprecated_lct_maps_google_api', 2 );
/**
 * Renamed Action
 *
 * @return bool
 * @deprecated 7.49
 * @since      6.3
 * @verified   2017.05.09
 */
function deprecated_lct_maps_google_api()
{
	add_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_action', 10, 4 );
	_deprecated_hook(
		str_replace( 'deprecated_', '', __FUNCTION__ ),
		'7.49',
		'lct/maps_google_api'
	);
	remove_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_action' );


	remove_all_actions( 'lct_maps_google_api' );


	return false;
}


add_action( 'shutdown', 'shutdown_deprecated_lct_maps_google_api' );
function shutdown_deprecated_lct_maps_google_api()
{
	lct_shutdown_deprecated_action( 'lct_maps_google_api' );
}
