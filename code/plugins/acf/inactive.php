<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'get_field' ) ) {
	/**
	 * ACF is not active let's try not to error 500
	 *
	 * @param string   $selector
	 * @param int|bool $post_id
	 * @param bool     $format_value
	 *
	 * @return WP_Post|WP_Post[]|WP_User|WP_User[]|WP_Term|WP_Term[]|mixed|mixed[]
	 * @since        7.62
	 * @verified     2019.07.18
	 * @noinspection PhpMissingParamTypeInspection
	 * @noinspection PhpUnusedParameterInspection
	 */
	function get_field( $selector, $post_id = false, $format_value = true )
	{
		return null;
	}
}


if ( ! function_exists( 'update_field' ) ) {
	/**
	 * ACF is not active let's try not to error 500
	 *
	 * @param string   $selector
	 * @param mixed    $value
	 * @param int|bool $post_id
	 *
	 * @return null
	 * @since        7.62
	 * @verified     2016.12.30
	 * @noinspection PhpMissingParamTypeInspection
	 * @noinspection PhpUnusedParameterInspection
	 */
	function update_field( $selector, $value, $post_id = false )
	{
		return null;
	}
}
