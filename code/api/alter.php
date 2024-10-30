<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Remove all new lines and tabs in a string of content
 *
 * @param $content
 *
 * @return mixed
 * @since    2017.27
 * @verified 2017.04.04
 */
function lct_strip_n_r_t( $content )
{
	$content = preg_replace( '#\r\n|\r|\n|\t#', '', $content );


	return $content;
}
