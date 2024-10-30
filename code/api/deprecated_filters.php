<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_filter( 'lct_current_user_can_access', 'deprecated_lct_current_user_can_access', 2 );
/**
 * Renamed Filter
 *
 * @param $value
 *
 * @return mixed
 * @deprecated 7.49
 * @since      5.28
 * @verified   2016.12.05
 */
function deprecated_lct_current_user_can_access( $value )
{
	add_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter', 10, 4 );
	_deprecated_hook(
		str_replace( 'deprecated_', '', __FUNCTION__ ),
		'7.49',
		'lct/current_user_can_access'
	);
	remove_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter' );


	remove_all_filters( 'lct_current_user_can_access' );


	return $value;
}


add_action( 'shutdown', 'shutdown_deprecated_lct_current_user_can_access' );
function shutdown_deprecated_lct_current_user_can_access()
{
	lct_shutdown_deprecated( 'lct_current_user_can_access' );
}


add_filter( 'lct_current_user_can_view', 'deprecated_lct_current_user_can_view', 2 );
/**
 * Renamed Filter
 *
 * @param $value
 *
 * @return mixed
 * @deprecated 7.49
 * @since      5.28
 * @verified   2016.12.05
 */
function deprecated_lct_current_user_can_view( $value )
{
	add_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter', 10, 4 );
	_deprecated_hook(
		str_replace( 'deprecated_', '', __FUNCTION__ ),
		'7.49',
		'lct/current_user_can_view'
	);
	remove_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter' );


	remove_all_filters( 'lct_current_user_can_view' );


	return $value;
}


add_action( 'shutdown', 'shutdown_deprecated_lct_current_user_can_view' );
function shutdown_deprecated_lct_current_user_can_view()
{
	lct_shutdown_deprecated( 'lct_current_user_can_view' );
}


add_filter( 'lct_class_conditional_items', 'deprecated_lct_class_conditional_items', 2 );
/**
 * Removed Filter
 *
 * @param $value
 *
 * @return mixed
 * @deprecated 2017.34
 * @since      5.25
 * @verified   2017.04.27
 */
function deprecated_lct_class_conditional_items( $value )
{
	add_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter', 10, 4 );
	_deprecated_hook(
		str_replace( 'deprecated_', '', __FUNCTION__ ),
		'2017.34',
		'wp_nav_menu_objects OR wp_nav_menu_items'
	);
	remove_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter' );


	remove_all_filters( 'lct_class_conditional_items' );


	return $value;
}


add_action( 'shutdown', 'shutdown_deprecated_lct_class_conditional_items' );
function shutdown_deprecated_lct_class_conditional_items()
{
	lct_shutdown_deprecated( 'lct_class_conditional_items' );
}


add_filter( 'lct_get_format_acf_value', 'deprecated_lct_get_format_acf_value', 2 );
/**
 * Removed Filter
 *
 * @param $value
 *
 * @return mixed
 * @deprecated 2017.83
 * @since      5.28
 * @verified   2017.05.18
 */
function deprecated_lct_get_format_acf_value( $value )
{
	add_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter', 10, 4 );
	_deprecated_hook(
		str_replace( 'deprecated_', '', __FUNCTION__ ),
		'2017.83',
		'lct_acf_format_value()'
	);
	remove_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter' );


	remove_all_filters( 'lct_get_format_acf_value' );


	return $value;
}


add_action( 'shutdown', 'shutdown_deprecated_lct_get_format_acf_value' );
function shutdown_deprecated_lct_get_format_acf_value()
{
	lct_shutdown_deprecated( 'lct_get_format_acf_value' );
}


add_filter( 'lct_get_format_acf_date_picker', 'deprecated_lct_get_format_acf_date_picker', 2 );
/**
 * Removed Filter
 *
 * @param $value
 *
 * @return mixed
 * @deprecated 2017.83
 * @since      5.28
 * @verified   2016.09.29
 */
function deprecated_lct_get_format_acf_date_picker( $value )
{
	add_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter', 10, 4 );
	_deprecated_hook(
		str_replace( 'deprecated_', '', __FUNCTION__ ),
		'2017.83',
		'lct_acf_format_value()'
	);
	remove_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter' );


	remove_all_filters( 'lct_get_format_acf_date_picker' );


	return $value;
}


add_action( 'shutdown', 'shutdown_deprecated_lct_get_format_acf_date_picker' );
function shutdown_deprecated_lct_get_format_acf_date_picker()
{
	lct_shutdown_deprecated( 'lct_get_format_acf_date_picker' );
}


add_filter( 'lct/acf/get_pretty_taxonomies/choices', 'deprecated_lct_acf_get_pretty_taxonomies_choices', 2 );
/**
 * Removed Filter
 *
 * @param $value
 *
 * @return mixed
 * @deprecated 2018.32
 * @since      7.50
 * @verified   2018.03.20
 */
function deprecated_lct_acf_get_pretty_taxonomies_choices( $value )
{
	add_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter', 10, 4 );
	_deprecated_hook(
		str_replace( 'deprecated_', '', 'lct/acf/get_pretty_taxonomies/choices' ),
		'2018.32',
		'lct/pretty_wp_taxonomies'
	);
	remove_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter' );


	remove_all_filters( 'lct/acf/get_pretty_taxonomies/choices' );


	return $value;
}


add_action( 'shutdown', 'shutdown_deprecated_lct_acf_get_pretty_taxonomies_choices' );
function shutdown_deprecated_lct_acf_get_pretty_taxonomies_choices()
{
	lct_shutdown_deprecated( 'lct/acf/get_pretty_taxonomies/choices' );
}


add_filter( 'lct/acf/acf_get_taxonomies/choices', 'deprecated_lct_acf_acf_get_taxonomies_choices', 2 );
/**
 * Removed Filter
 *
 * @param $value
 *
 * @return mixed
 * @deprecated 2018.32
 * @since      7.31
 * @verified   2018.03.20
 */
function deprecated_lct_acf_acf_get_taxonomies_choices( $value )
{
	add_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter', 10, 4 );
	_deprecated_hook(
		str_replace( 'deprecated_', '', 'lct/acf/acf_get_taxonomies/choices' ),
		'2018.32',
		'lct/pretty_wp_taxonomies'
	);
	remove_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter' );


	remove_all_filters( 'lct/acf/acf_get_taxonomies/choices' );


	return $value;
}


add_action( 'shutdown', 'shutdown_deprecated_lct_acf_acf_get_taxonomies_choices' );
function shutdown_deprecated_lct_acf_acf_get_taxonomies_choices()
{
	lct_shutdown_deprecated( 'lct/acf/acf_get_taxonomies/choices' );
}


add_filter( 'lct/acf/acf_get_post_types/choices', 'deprecated_lct_acf_acf_get_post_types_choices', 2 );
/**
 * Removed Filter
 *
 * @param $value
 *
 * @return mixed
 * @deprecated 2018.32
 * @since      7.36
 * @verified   2018.03.20
 */
function deprecated_lct_acf_acf_get_post_types_choices( $value )
{
	add_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter', 10, 4 );
	_deprecated_hook(
		str_replace( 'deprecated_', '', 'lct/acf/acf_get_post_types/choices' ),
		'2018.32',
		'lct/pretty_wp_post_types'
	);
	remove_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter' );


	remove_all_filters( 'lct/acf/acf_get_post_types/choices' );


	return $value;
}


add_action( 'shutdown', 'shutdown_deprecated_lct_acf_acf_get_post_types_choices' );
function shutdown_deprecated_lct_acf_acf_get_post_types_choices()
{
	lct_shutdown_deprecated( 'lct/acf/acf_get_post_types/choices' );
}


add_filter( 'lct/acf/get_pretty_post_types/choices', 'deprecated_lct_acf_get_pretty_post_types_choices', 2 );
/**
 * Removed Filter
 *
 * @param $value
 *
 * @return mixed
 * @deprecated 2018.32
 * @since      7.50
 * @verified   2018.03.20
 */
function deprecated_lct_acf_get_pretty_post_types_choices( $value )
{
	add_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter', 10, 4 );
	_deprecated_hook(
		str_replace( 'deprecated_', '', 'lct/acf/get_pretty_post_types/choices' ),
		'2018.32',
		'lct/pretty_wp_post_types'
	);
	remove_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter' );


	remove_all_filters( 'lct/acf/get_pretty_post_types/choices' );


	return $value;
}


add_action( 'shutdown', 'shutdown_deprecated_lct_acf_get_pretty_post_types_choices' );
function shutdown_deprecated_lct_acf_get_pretty_post_types_choices()
{
	lct_shutdown_deprecated( 'lct/acf/get_pretty_post_types/choices' );
}


/**
 * Alert filter deprecation and allow it to run
 * //TODO: cs - Make this process more simple - 9/9/2020 8:30 AM
 *
 * @param array $value
 *
 * @return array
 * @deprecated   2020.11
 * @since        5.28
 * @verified     2020.09.09
 * @noinspection PhpMissingParamTypeInspection
 */
function deprecated_lct_get_comment_type_lct_audit_settings( $value )
{
	add_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter', 10, 4 );
	_deprecated_hook(
		str_replace( 'deprecated_', '', 'lct_get_comment_type_lct_audit_settings' ),
		'2020.11',
		'lct/get_comment_type_audit_settings'
	);
	remove_action( 'deprecated_hook_run', 'lct_force_trigger_error_deprecated_filter' );


	return $value;
}


add_filter( 'lct/get_comment_type_audit_settings', 'run_old_lct_get_comment_type_lct_audit_settings', 1 );
function run_old_lct_get_comment_type_lct_audit_settings( $value )
{
	if ( has_filter( 'lct_get_comment_type_lct_audit_settings' ) ) {
		$value = apply_filters( 'lct_get_comment_type_lct_audit_settings', $value );
	}


	return $value;
}


add_action( 'shutdown', 'shutdown_deprecated_lct_get_comment_type_lct_audit_settings' );
function shutdown_deprecated_lct_get_comment_type_lct_audit_settings()
{
	lct_shutdown_deprecated( 'lct_get_comment_type_lct_audit_settings' );
}
