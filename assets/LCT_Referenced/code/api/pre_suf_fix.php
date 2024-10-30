<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) )
	exit;


/**
 * LCT Referenced
 *
 * @verified 2018.08.30
 * @verified 2018.08.30
 */


/**
 * Prefix the string with the us value
 *
 * @param $str
 *
 * @return string
 * @since    LCT 7.38
 * @verified 2018.08.30
 */
function lct_us( $str = '' ) {
	$spacer = '';

	if ( $str )
		$spacer = '_';


	$str = $GLOBALS['lct']->settings['_us'] . $spacer . $str;


	return $str;
}


/**
 * Prefix the string with the dash value
 *
 * @param $str
 *
 * @return string
 * @since    LCT 7.38
 * @verified 2018.08.30
 */
function lct_dash( $str = '' ) {
	$spacer = '';

	if ( $str )
		$spacer = '-';


	$str = $GLOBALS['lct']->settings['_dash'] . $spacer . $str;


	return $str;
}


/**
 * Prefix the string with the zxza value
 *
 * @param $str
 *
 * @return string
 * @since    LCT 7.38
 * @verified 2018.08.30
 */
function zxza( $str = '' ) {
	$str = $GLOBALS['lct']->settings['_zxza'] . $str;


	return $str;
}


/**
 * Prefix the string with the zxza_acf value
 *
 * @param $str
 *
 * @return string
 * @since    LCT 7.38
 * @verified 2018.08.30
 */
function zxzacf( $str = '' ) {
	$str = $GLOBALS['lct']->settings['_zxza_acf'] . $str;


	return $str;
}


/**
 * Prefix the string with the zxzu value
 *
 * @param $str
 *
 * @return string
 * @since    LCT 7.38
 * @verified 2018.08.30
 */
function zxzu( $str = '' ) {
	$str = $GLOBALS['lct']->settings['_zxzu'] . $str;


	return $str;
}


/**
 * Remove zxzu prefix on a string
 *
 * @param $str
 *
 * @return string
 * @since    LCT 2017.83
 * @verified 2018.08.30
 */
function zxzu_undo( $str = '' ) {
	$undo = $GLOBALS['lct']->settings['_zxzu'];


	if ( strpos( $str, $undo ) === 0 )
		$str = preg_replace( '/' . $undo . '/', '', $str, 1 );


	return $str;
}


/**
 * Prefix the string with the zxzs value
 *
 * @param $str
 *
 * @return string
 * @since    LCT 7.38
 * @verified 2018.08.30
 */
function zxzs( $str = '' ) {
	$str = $GLOBALS['lct']->settings['_zxzs'] . $str;


	return $str;
}


/**
 * Prefix the string with the zxzb value
 *
 * @param $str
 *
 * @return string
 * @since    LCT 7.49
 * @verified 2018.08.30
 */
function zxzb( $str = '' ) {
	$str = $GLOBALS['lct']->settings['_zxzb'] . $str;


	return $str;
}


/**
 * Prefix the string with the zxzd value
 *
 * @param $str
 *
 * @return string
 * @since    LCT 7.52
 * @verified 2018.08.30
 */
function zxzd( $str = '' ) {
	$str = $GLOBALS['lct']->settings['_zxzd'] . $str;


	return $str;
}


/**
 * ACF Special Function
 * Add comment_ to the beginning of $post_id
 *
 * @param $id
 *
 * @return string
 * @since    LCT 0.0
 * @verified 2017.07.31
 */
function lct_c( $id ) {
	if ( $id )
		$id = 'comment_' . $id;


	return $id;
}


/**
 * ACF Special Function
 * Remove the first case of comment_ from the $post_id
 *
 * @param $id
 *
 * @return mixed
 * @since    LCT 0.0
 * @verified 2017.07.31
 */
function lct_cc( $id ) {
	if ( $id )
		$id = preg_replace( '/comment_/', '', $id, 1 );


	return $id;
}


/**
 * ACF Special Function
 * Sets the $post_id to {$term->taxonomy}_{$term->term_id}
 *
 * @param $id
 * @param $taxonomy
 *
 * @return string
 * @since    LCT 0.0
 * @verified 2017.07.31
 */
function lct_t( $id, $taxonomy = '' ) {
	if (
		(
			$id &&
			! is_object( $id ) &&
			$taxonomy
		) ||
		(
			is_object( $id ) &&
			! lct_is_wp_error( $id )
		)
	) {
		if ( is_object( $id ) ) {
			$taxonomy = $id->taxonomy;
			$id       = $id->term_id;
		}


		$id = $taxonomy . '_' . $id;
	}


	return $id;
}


/**
 * ACF Special Function
 * Remove the first case of {$term->taxonomy}_ from the $post_id
 *
 * @param $id
 *
 * @return mixed
 * @since    LCT 0.0
 * @verified 2017.07.31
 */
function lct_tt( $id ) {
	if (
		is_object( $id ) &&
		! lct_is_wp_error( $id )
	) {
		$id = $id->term_id;
	} else if ( $id ) {
		$id = (int) filter_var( $id, FILTER_SANITIZE_NUMBER_INT );
	}


	return $id;
}


/**
 * ACF Special Function
 * Remove the _{$term->term_id} from the $post_id
 *
 * @param $taxonomy
 *
 * @return mixed
 * @since    LCT 7.17
 * @verified 2017.07.31
 */
function lct_tt_tax( $taxonomy ) {
	if (
		is_object( $taxonomy ) &&
		! lct_is_wp_error( $taxonomy )
	) {
		$taxonomy = $taxonomy->taxonomy;
	} else if ( $taxonomy ) {
		$split = explode( '_', $taxonomy );
		array_pop( $split );


		$taxonomy = implode( '_', $split );
	}


	return $taxonomy;
}


/**
 * ACF Special Function
 * Add user_ to the beginning of $post_id
 *
 * @param $id
 *
 * @return string
 * @since    LCT 0.0
 * @verified 2017.07.31
 */
function lct_u( $id ) {
	if (
		is_object( $id ) &&
		! lct_is_wp_error( $id )
	) {
		$id = $id->ID;
	}


	if ( $id )
		$id = 'user_' . $id;


	return $id;
}


/**
 * ACF Special Function
 * Remove the first case of user_ from the $post_id
 *
 * @param $id
 *
 * @return mixed
 * @since    LCT 0.0
 * @verified 2017.07.31
 */
function lct_uu( $id ) {
	if ( $id )
		$id = preg_replace( '/user_/', '', $id, 1 );


	return $id;
}


/**
 * ACF Special Function
 * Add widget_ to the beginning of $post_id
 *
 * @param $id
 *
 * @return string
 * @since    LCT 0.0
 * @verified 2017.07.31
 */
function lct_w( $id ) {
	if ( $id )
		$id = 'widget_' . $id;


	return $id;
}


/**
 * ACF Special Function
 * Remove the first case of widget_ from the $post_id
 *
 * @param $id
 *
 * @return mixed
 * @since    LCT 0.0
 * @verified 2017.07.31
 */
function lct_ww( $id ) {
	if ( $id )
		$id = preg_replace( '/widget_/', '', $id, 1 );


	return $id;
}
