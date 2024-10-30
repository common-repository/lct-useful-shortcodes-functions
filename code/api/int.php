<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Append note to text if the site is a dev or sandbox
 *
 * @param       $text
 * @param array $appension
 *
 * @return string
 * @since    5.38
 * @verified 2017.07.11
 */
function lct_i_append_dev_sb( $text, $appension = [ 'dev' => '::DEV::', 'sb' => '::DEV_SB::' ] )
{
	if (
		lct_is_dev_or_sb()
		|| lct_acf_get_option_raw( 'force_append_dev_sb' )
	) {
		if ( lct_is_sb() ) {
			$appension = $appension['sb'];
		} else {
			$appension = $appension['dev'];
		}


		$text = sprintf( '%s %s', $appension, $text );
	}


	return $text;
}


/**
 * modify the gaTracker category before we echo it
 *
 * @param $category
 *
 * @return string
 * @since    5.38
 * @verified 2017.06.14
 */
function lct_i_get_gaTracker_category( $category )
{
	$category = esc_js( lct_i_append_dev_sb( $category ) );


	return $category;
}


/**
 * Delimiter for any LCT esc functions
 *
 * @return string
 * @since    0.0
 * @verified 2016.09.27
 */
function lct_i_esc_delimiter()
{
	$delimiter = '~!~' . zxzu( 'esc' ) . '~!~';


	return $delimiter;
}


/**
 * Temp replace brackets
 *
 * @param $string
 *
 * @return mixed
 * @since    6.4
 * @verified 2016.09.27
 */
function lct_i_esc_brackets( $string )
{
	$de = lct_i_esc_delimiter();


	$string = str_replace( [ '[', ']' ], [ $de . 'BRACKET_OPEN' . $de, $de . 'BRACKET_CLOSE' . $de ], $string );


	return $string;
}


/**
 * Return brackets
 *
 * @param $string
 *
 * @return mixed
 * @since    6.4
 * @verified 2016.09.27
 */
function lct_i_un_esc_brackets( $string )
{
	$de = lct_i_esc_delimiter();


	$string = str_replace( [ $de . 'BRACKET_OPEN' . $de, $de . 'BRACKET_CLOSE' . $de ], [ '[', ']' ], $string );


	return $string;
}
