<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Add an fusion-clearfix clear div anywhere we want
 *
 * @att      string style
 *
 * @param      $a
 * @param bool $echo
 *
 * @return string
 * @since    7.69
 * @verified 2017.08.25
 */
function lct_Avada_clear( $a = [], $echo = false )
{
	/**
	 * set the default atts
	 */
	$a = shortcode_atts(
		[
			'r'     => '<div class="fusion-clearfix" %s></div>',
			'style' => ''
		],
		$a
	);


	/**
	 * style
	 */
	if ( $a['style'] ) {
		$a['style'] = sprintf( 'style="%s"', $a['style'] );
	}


	/**
	 * Main Attraction
	 */
	$a['r'] = sprintf( $a['r'], $a['style'] );


	if ( $echo ) {
		echo $a['r'];

		$a['r'] = '';
	}


	return $a['r'];
}


/**
 * Alias for lct_Avada_clear()
 *
 * @param array $a
 * @param bool  $echo
 *
 * @return string
 * @since    5.40
 * @verified 2017.05.08
 */
function Avada_clear( $a = [], $echo = false )
{
	return lct_Avada_clear( $a, $echo );
}
