<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * When we need the keys of any ACF fields that are stored as meta for a certain comment
 *
 * @param       $comment_ID
 * @param array $exclude
 *
 * @return array
 * @since    5.38
 * @verified 2024.06.04
 */
function lct_get_comment_meta_field_keys( $comment_ID, $exclude = [] )
{
	$keys = [];
	$meta = get_comment_meta( $comment_ID );


	if ( $meta ) {
		foreach ( $meta as $k => $v ) {
			if ( strpos( $k, '_' ) === 0 ) {
				if (
					in_array( lct_un_pre_us( $k ), $exclude )
					|| lct_is_empty( $v[0] )
				) {
					continue;
				}


				$keys[] = $v[0];
			}
		}
	}


	return $keys;
}


/**
 * Return an array of {zxzu}audit comment_type settings
 *
 * @param array $args
 *
 * @return array
 * @since    5.28
 * @verified 2020.09.09
 */
function lct_get_comment_type_lct_audit_settings( $args = [] )
{
	$audit_types = [];

	$audit_types['acf_update_field'] = [
		'name' => 'ACF Field Updated',
		'text' => 'An ACF field was updated.',
	];

	$defaults = [
		'singular'    => 'Audit Log Entry',
		'plural'      => 'Audit Log Entries',
		'audit_types' => $audit_types,
	];


	return apply_filters( 'lct/get_comment_type_audit_settings', wp_parse_args( $args, $defaults ) );
}


/**
 * Return an array of comment_type settings
 *
 * @param string $comment_type
 * @param array  $args
 *
 * @return array
 * @since    2020.11
 * @verified 2020.09.07
 */
function lct_get_comment_type_settings( $comment_type = 'comment', $args = [] )
{
	if (
		$comment_type !== 'comment'
		&& function_exists( 'lct_get_comment_type_' . $comment_type . '_settings' )
	) {
		return call_user_func( 'lct_get_comment_type_' . $comment_type . '_settings', $args );
	} else {
		$defaults = [
			'singular' => 'Comment',
			'plural'   => 'Comments',
		];


		return apply_filters( 'lct/get_comment_type_settings', wp_parse_args( $args, $defaults ) );
	}
}
