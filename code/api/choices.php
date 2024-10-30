<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Get an array of years
 *
 * @param null $start      :: Older Year
 * @param null $end        :: Newer Year
 * @param bool $DESC_order :: if true Newest year is at the top of the list
 * @param null $options_before
 * @param null $options_after
 *
 * @return array
 * @since    7.49
 * @verified 2017.07.05
 */
function lct_get_pretty_years( $start = null, $end = null, $DESC_order = false, $options_before = null, $options_after = null )
{
	$choices = [];


	/**
	 * Add extras
	 */
	if ( $options_before ) {
		$choices[ key( $options_before ) ] = reset( $options_before );
	}


	/**
	 * start
	 */
	if ( is_int( $start ) ) {
		$year_old = $start;
	} else {
		$year_old = 1900;
	}

	if (
		strpos( $start, '+' ) !== false
		|| strpos( $start, '-' ) !== false
	) {
		if ( strpos( $start, '+' ) !== false ) {
			$start_diff = explode( '+', $start );
			$year_old   = $year_old + (int) $start_diff[1];
		} else {
			$start_diff = explode( '-', $start );
			$year_old   = $year_old - (int) $start_diff[1];
		}
	}


	/**
	 * end
	 */
	if ( is_int( $end ) ) {
		$year_new = $end;
	} else {
		$year_new = (int) date( 'Y' );
	}

	if (
		strpos( $end, '+' ) !== false
		|| strpos( $end, '-' ) !== false
	) {
		if ( strpos( $end, '+' ) !== false ) {
			$end_diff = explode( '+', $end );
			$year_new = $year_new + (int) $end_diff[1];
		} else {
			$end_diff = explode( '-', $end );
			$year_new = $year_new - (int) $end_diff[1];
		}
	}


	/**
	 * Get the dates
	 */
	if ( $DESC_order ) {
		for ( $i = $year_new; $i >= $year_old; $i -- ) {
			$choices[ $i ] = $i;
		}
	} else {
		for ( $i = $year_old; $i <= $year_new; $i ++ ) {
			$choices[ $i ] = $i;
		}
	}


	/**
	 * Add extras
	 */
	if ( $options_after ) {
		$choices[ key( $options_after ) ] = reset( $options_after );
	}


	$choices_complete = [];

	foreach ( $choices as $key => $choice ) {
		$choices_complete[ $key ] = $choice;
	}


	return $choices_complete;
}
