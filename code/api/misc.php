<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Better nl2br()
 *
 * @param        $str
 * @param string $br
 *
 * @return mixed
 * @since    7.66
 * @verified 2017.01.06
 */
function lct_nl2br( $str, $br = '<br />' )
{
	return str_replace( [ "\r\n", "\r", "\n" ], $br, $str );
}


/**
 * <br /> TO \n
 *
 * @param $str
 *
 * @return mixed
 * @since    7.66
 * @verified 2017.01.06
 */
function lct_br2nl( $str )
{
	return preg_replace( '/\<br(\s*)?\/?\>/i', "\n", $str );
}


/**
 * returns an array of directories in a directory
 *
 * @param        $dir
 *
 * @return array
 * @since    7.53
 * @verified 2017.01.16
 */
function lct_list_directories( $dir )
{
	$dirs = [];


	if ( file_exists( $dir ) ) {
		$iterator = new DirectoryIterator( $dir );


		foreach ( $iterator as $info ) {
			if (
				! $info->isDot()
				&& $info->isDir()
			) {
				$dirs[] = str_replace( [ '//', $dir ], [ '/', '' ], lct_static_cleaner( $info->getPathname() ) );
			}
		}
	}


	return $dirs;
}


/**
 * returns an array of files in a directory
 *
 * @param        $dir
 *
 * @return array
 * @since    7.69
 * @verified 2017.01.16
 */
function lct_list_files( $dir )
{
	$files = [];


	if ( file_exists( $dir ) ) {
		$iterator = new DirectoryIterator( $dir );


		foreach ( $iterator as $info ) {
			if (
				! $info->isDot()
				&& $info->isFile()
			) {
				$files[] = str_replace( [ '//', $dir ], [ '/', '' ], lct_static_cleaner( $info->getPathname() ) );
			}
		}
	}


	return $files;
}


/**
 * Bad name, but hey that is what it does and it is very useful
 *
 * @param string $term_name
 * @param string $taxonomy
 * @param array  $args
 *
 * @return int|null
 * @since    2017.3
 * @verified 2019.03.15
 */
function lct_get_term_id_or_create_n_get_term_id( $term_name, $taxonomy, $args = [] )
{
	$term_id = null;


	if ( $term_name ) {
		$parent = null;

		if ( ! empty( $args['parent'] ) ) {
			$parent = $args['parent'];
		}


		/**
		 * If the term already exists
		 */
		if (
			( $term_info = term_exists( $term_name, $taxonomy, $parent ) )
			&& isset( $term_info['term_id'] )
		) {
			$term_id = (int) $term_info['term_id'];


			/**
			 * If it doesn't exist, let's create it
			 */
		} elseif ( ! is_numeric( $term_name ) ) {
			$term = wp_insert_term( $term_name, $taxonomy, $args );


			if ( ! lct_is_wp_error( $term ) ) {
				$term_id = (int) $term['term_id'];
			}
		}
	}


	return $term_id;
}


/**
 * Replacement for get_next_post()
 *
 * @param bool   $in_same_term
 * @param string $excluded_terms
 * @param string $taxonomy
 * @param null   $post_id
 * @param array  $args
 *
 * @return null|string|WP_Post
 * @since    2017.5
 * @verified 2017.01.30
 */
function lct_get_next_post( $in_same_term = false, $excluded_terms = '', $taxonomy = 'category', $post_id = null, $args = [] )
{
	if ( $post_id ) {
		lct_update_later( __FUNCTION__, $GLOBALS['post'], 'global_post' );

		$GLOBALS['post'] = get_post( $post_id );
	}


	if ( $args['sortby'] == 'menu_order' ) {
		add_filter( 'get_next_post_sort', 'lct_get_adjacent_post_sort_menu_order', 10, 2 );
		add_filter( 'get_next_post_where', 'lct_get_adjacent_post_where_menu_order', 10, 5 );
	}


	$adjacent_post = get_next_post( $in_same_term, $excluded_terms, $taxonomy );


	if ( $post_id ) {
		$GLOBALS['post'] = lct_get_later( __FUNCTION__, 'global_post' );
	}


	return $adjacent_post;
}


/**
 * Replacement for get_next_post()
 *
 * @param bool   $in_same_term
 * @param string $excluded_terms
 * @param string $taxonomy
 * @param null   $post_id
 * @param array  $args
 *
 * @return null|string|WP_Post
 * @since    2017.5
 * @verified 2017.01.30
 */
function lct_get_prev_post( $in_same_term = false, $excluded_terms = '', $taxonomy = 'category', $post_id = null, $args = [] )
{
	if ( $post_id ) {
		lct_update_later( __FUNCTION__, $GLOBALS['post'], 'global_post' );

		$GLOBALS['post'] = get_post( $post_id );
	}


	if ( $args['sortby'] == 'menu_order' ) {
		add_filter( 'get_previous_post_sort', 'lct_get_adjacent_post_sort_menu_order', 10, 2 );
		add_filter( 'get_previous_post_where', 'lct_get_adjacent_post_where_menu_order', 10, 5 );
	}


	$adjacent_post = get_previous_post( $in_same_term, $excluded_terms, $taxonomy );


	if ( $post_id ) {
		$GLOBALS['post'] = lct_get_later( __FUNCTION__, 'global_post' );
	}


	return $adjacent_post;
}


/**
 * SORT adjustment for menu_order
 *
 * @param $sort
 * @param $post
 *
 * @return mixed
 * @since    2017.5
 * @verified 2017.01.30
 */
function lct_get_adjacent_post_sort_menu_order(
	$sort,
	/** @noinspection PhpUnusedParameterInspection */
	$post
) {
	$sort = str_replace( 'ORDER BY p.post_date', 'ORDER BY p.menu_order', $sort );


	return $sort;
}


/**
 * WHERE adjustment for menu_order
 *
 * @param $where
 * @param $in_same_term
 * @param $excluded_terms
 * @param $taxonomy
 * @param $post
 *
 * @return mixed
 * @since    2017.5
 * @verified 2017.01.30
 */
function lct_get_adjacent_post_where_menu_order(
	$where,
	/** @noinspection PhpUnusedParameterInspection */
	$in_same_term,
	/** @noinspection PhpUnusedParameterInspection */
	$excluded_terms,
	/** @noinspection PhpUnusedParameterInspection */
	$taxonomy,
	$post
) {
	$where = preg_replace( '/p.post_date (.*?) \'(.*?)\' AND/', 'p.menu_order $1 ' . $post->menu_order . ' AND', $where );


	return $where;
}


/**
 * Insert an array into an array
 *
 * @param $key
 * @param $source_array
 * @param $insert_array
 *
 * @return array
 * @since    2018.53
 * @verified 2018.06.12
 */
function lct_array_insert_after_key( $key, $source_array, $insert_array )
{
	if ( array_key_exists( $key, $source_array ) ) {
		$position     = array_search( $key, array_keys( $source_array ) ) + 1;
		$source_array = array_slice( $source_array, 0, $position, true ) + $insert_array + array_slice( $source_array, $position, null, true );
	} else {
		// If no key is found, then add it to the end of the array.
		$source_array += $insert_array;
	}

	return $source_array;
}


/**
 * Insert an array into an array
 *
 * @param $key
 * @param $source_array
 * @param $insert_array
 *
 * @return array
 * @since    2018.53
 * @verified 2018.06.12
 */
function lct_array_insert_before_key( $key, $source_array, $insert_array )
{
	if ( array_key_exists( $key, $source_array ) ) {
		$position     = array_search( $key, array_keys( $source_array ) );
		$source_array = array_slice( $source_array, 0, $position, true ) + $insert_array + array_slice( $source_array, $position, null, true );
	} else {
		// If no key is found, then add it to the end of the array.
		$source_array += $insert_array;
	}

	return $source_array;
}


/**
 * Return an array of all duplicate values
 *
 * @param $array
 *
 * @return array
 * @since    2019.6
 * @verified 2019.03.21
 */
function lct_array_not_unique( $array )
{
	$r         = [];
	$old_key   = null;
	$old_value = null;


	if (
		empty( $array )
		|| ! is_array( $array )
	) {
		return $r;
	}


	natcasesort( $array );
	reset( $array );


	foreach ( $array as $key => $value ) {
		if ( $value === null ) {
			continue;
		}


		if ( strcasecmp( $old_value, $value ) === 0 ) {
			$r[ $old_key ] = $old_value;
			$r[ $key ]     = $value;
		}


		$old_value = $value;
		$old_key   = $key;
	}


	return $r;
}


/**
 * Recursive find and replace
 * We have a multidimensional array and have to find and replace some values.
 * It would be the best if the result is the same array with values changed.
 * It is quite straightforward but I will explain anyway. Function first
 * checks if $array is actually an array and if it isn't, it returns
 * regular str_replace. If it is an array, we are creating empty array $newArray.
 * We advance through the given array by key and value and call recursively
 * the same function. This time $array is not an array and the function
 * returns regular str_replace, which is our value.
 *
 * @link     http://www.codeforest.net/quick-snip-recursive-find-and-replace
 *
 * @param string       $find
 * @param string       $replace
 * @param array|string $array
 *
 * @return array
 * @since    2019.6
 * @verified 2019.03.28
 */
function lct_array_replace( $find, $replace, $array )
{
	if ( ! is_array( $array ) ) {
		$new = str_replace( $find, $replace, $array );


		if ( $new === $array ) {
			//Just move on


		} elseif (
			$new !== $array
			&& strpos( $array, $find ) !== false
		) {
			if ( is_int( $replace ) ) {
				$array = (int) $new;
			} elseif ( is_float( $replace ) ) {
				$array = (float) $new;
			}


		} elseif ( $new != $array ) {//Don't want this to be strict
			$array = $new;
		}


		return $array;
	}


	$newArray = [];


	foreach ( $array as $key => $value ) {
		$newArray[ $key ] = lct_array_replace( $find, $replace, $value );
	}


	return $newArray;
}
