<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * A quick solution for echo when debugging.
 *
 * @param        $value
 * @param string $label
 * @param string $position
 * @param string $spacer
 *
 * @since    1.2.5
 * @verified 2016.12.05
 */
function echo_br( $value, $label = '', $position = 'before', $spacer = ' : ' )
{
	if ( $position == 'before' || $position == 'both' ) {
		echo '<br />';
	}


	echo $label . $spacer . $value;


	if ( $position == 'after' || $position == 'both' ) {
		echo '<br />';
	}
}


/**
 * A quick solution for echo when debugging.
 *
 * @param        $value
 * @param string $position :: before, after, both
 *
 * @since    7.48
 * @verified 2016.12.05
 */
function echo_br_o( $value, $position = 'after' )
{
	if ( $position == 'before' || $position == 'both' ) {
		echo '<br />';
	}


	echo $value;


	if ( $position == 'after' || $position == 'both' ) {
		echo '<br />';
	}
}
