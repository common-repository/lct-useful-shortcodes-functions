<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'createPath' ) ) {
	/**
	 * Create a long path if it does not exist, returns true if exists or finished creating
	 *
	 * @param        $path
	 * @param null   $startPath
	 *
	 * @return bool
	 * @since    0.0
	 * @verified 2016.12.17
	 */
	function createPath( $path, $startPath = null )
	{
		createPathFolders( $path );


		if ( ! $startPath ) {
			return true;
		}


		$startPath = rtrim( $startPath, "/" );
		$objects   = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $startPath ), RecursiveIteratorIterator::SELF_FIRST );


		foreach ( $objects as $name => $object ) {
			if (
				is_dir( $object->getPathname() )
				&& strpos( $object->getPathname(), '.svn' ) === false
				&& strpos( $object->getPathname(), '..' ) === false
			) {
				$dirs[] = $object->getPathname();
			}
		}


		return false;
	}
}


if ( ! function_exists( 'createPathFolders' ) ) {
	/**
	 * Create the folders - only call in createPath()
	 *
	 * @param $path
	 *
	 * @return bool
	 * @since    0.0
	 * @verified 2016.12.17
	 */
	function createPathFolders( $path )
	{
		$path = rtrim( $path, "/" );


		if ( ! $path ) {
			return false;
		}


		if ( is_dir( $path ) ) {
			return true;
		}


		$lastPath = substr( $path, 0, strrpos( $path, '/', - 2 ) + 1 );
		$r        = createPath( $lastPath );


		return ( $r && is_writable( $lastPath ) ) ? mkdir( $path, 0755, true ) : false;
	}
}


if ( ! function_exists( 'strpos_array' ) ) {
	/**
	 * Check an array with strpos
	 *
	 * @param string|array $haystack
	 * @param array|string $needle
	 * @param bool         $haystack_array
	 * @param bool         $strict
	 *
	 * @return bool|int
	 * @since    4.3.4
	 * @verified 2019.05.02
	 */
	function strpos_array( $haystack, $needle, $haystack_array = false, $strict = false )
	{
		if (
			! $haystack_array
			&& is_array( $needle )
			&& ! is_array( $haystack )
		) {
			foreach ( $needle as $need ) {
				$answer = strpos( $haystack, $need );


				if ( $answer !== false ) {
					if ( $strict ) {
						return $answer;
					}


					return true;
				}
			}


		} elseif (
			$haystack_array
			&& is_array( $haystack )
			&& ! is_array( $needle )
		) {
			foreach ( $haystack as $hay ) {
				$answer = strpos( $hay, $needle );


				if ( $answer !== false ) {
					if ( $strict ) {
						return $answer;
					}


					return true;
				}
			}


		} elseif (
			! is_array( $haystack )
			&& ! is_array( $needle )
		) {
			$answer = strpos( $haystack, $needle );


			if ( $answer !== false ) {
				if ( $strict ) {
					return $answer;
				}


				return true;
			}


		}


		return false;
	}
}


if ( ! function_exists( 'stripos_array' ) ) {
	/**
	 * Check an array with stripos
	 *
	 * @param      $haystack
	 * @param      $needle
	 * @param bool $haystack_array
	 *
	 * @return bool
	 * @since    2017.52
	 * @verified 2017.06.27
	 */
	function stripos_array( $haystack, $needle, $haystack_array = false )
	{
		if (
			! $haystack_array
			&& is_array( $needle )
		) {
			foreach ( $needle as $need ) {
				if ( stripos( $haystack, $need ) !== false ) {
					return true;
				}
			}


		} elseif (
			$haystack_array
			&& is_array( $haystack )
		) {
			foreach ( $haystack as $hay ) {
				if ( stripos( $hay, $needle ) !== false ) {
					return true;
				}
			}


		} else {
			if ( stripos( $haystack, $needle ) !== false ) {
				return true;
			}


		}


		return false;
	}
}


if ( ! function_exists( 'wp_add_inline_script' ) ) {
	/**
	 * WordPress function that was added in v4.5. This will run if WP is a lesser version.
	 *
	 * @param $handle
	 * @param $data
	 *
	 * @since    6.0
	 * @verified 2019.03.25
	 */
	function wp_add_inline_script( $handle, $data )
	{
		add_action( 'wp_head', 'lct_wp_add_inline_script_head', 2000001 );
		add_action( 'admin_head', 'lct_wp_add_inline_script_head', 2000001 );


		$script_head = lct_get_setting( 'wp_add_inline_script_head', [] );


		if ( isset( $script_head[ $handle ] ) ) {
			$handle .= lct_pre_us( lct_rand() );
		}


		$script_head[ $handle ] = $data;


		lct_update_setting( 'wp_add_inline_script_head', $script_head );
	}


	/**
	 * WordPress function that was added in v4.5. This will run if WP is a lesser version.
	 *
	 * @since    6.0
	 * @verified 2017.06.27
	 */
	function lct_wp_add_inline_script_head()
	{
		if ( $script_head = lct_get_setting( 'wp_add_inline_script_head' ) ) {
			foreach ( $script_head as $k => $v ) {
				echo sprintf( '<%s id="%s">%s</%s>', 'script', $k, $v, 'script' );
			}
		}
	}
}


if ( ! function_exists( 'wp_add_inline_style' ) ) {
	/**
	 * WordPress function that was added in v4.5. This will run if WP is a lesser version.
	 *
	 * @param $handle
	 * @param $data
	 *
	 * @since    6.0
	 * @verified 2019.03.25
	 */
	function wp_add_inline_style( $handle, $data )
	{
		add_action( 'wp_head', 'lct_wp_add_inline_style_head', 2000001 );
		add_action( 'admin_head', 'lct_wp_add_inline_style_head', 2000001 );


		$style_head = lct_get_setting( 'wp_add_inline_style_head', [] );


		if ( isset( $style_head[ $handle ] ) ) {
			$handle .= lct_pre_us( lct_rand() );
		}


		$style_head[ $handle ] = $data;


		lct_update_setting( 'wp_add_inline_style_head', $style_head );
	}


	/**
	 * WordPress function that was added in v4.5. This will run if WP is a lesser version.
	 *
	 * @since    6.0
	 * @verified 2018.04.05
	 */
	function lct_wp_add_inline_style_head()
	{
		if ( $style_head = lct_get_setting( 'wp_add_inline_style_head' ) ) {
			foreach ( $style_head as $k => $v ) {
				echo sprintf( '<style id="%s">%s</style>', $k, $v );
			}
		}
	}
}


if ( ! function_exists( 'unparse_url' ) ) {
	/**
	 * Takes a URL array and turns it into a URL string
	 *
	 * @param array $parts
	 *
	 * @return string
	 * @since    6.0
	 * @verified 2016.12.17
	 */
	function unparse_url( array $parts )
	{
		return ( isset( $parts['scheme'] ) ? "{$parts['scheme']}:" : '' ) .
		       (
		       (
			       isset( $parts['user'] )
			       || isset( $parts['host'] )
		       ) ? '//' : ''
		       ) .
		       ( isset( $parts['user'] ) ? "{$parts['user']}" : '' ) .
		       ( isset( $parts['pass'] ) ? ":{$parts['pass']}" : '' ) .
		       ( isset( $parts['user'] ) ? '@' : '' ) .
		       ( isset( $parts['host'] ) ? "{$parts['host']}" : '' ) .
		       ( isset( $parts['port'] ) ? ":{$parts['port']}" : '' ) .
		       ( isset( $parts['path'] ) ? "{$parts['path']}" : '' ) .
		       ( isset( $parts['query'] ) ? "?{$parts['query']}" : '' ) .
		       ( isset( $parts['fragment'] ) ? "#{$parts['fragment']}" : '' );
	}
}


if ( ! function_exists( 'parse_query' ) ) {
	/**
	 * parse a query
	 *
	 * @param $query
	 *
	 * @return array
	 * @since    6.0
	 * @verified 2018.09.23
	 */
	function parse_query( $query )
	{
		if (
			strpos( $query, 'http' ) === 0
			|| strpos( $query, '//' ) === 0
		) {
			$url_parts = parse_url( $query );


			if ( ! empty( $url_parts['query'] ) ) {
				$query = $url_parts['query'];
			}
		}


		$query_array = [];
		$query_parts = explode( '&', $query );


		foreach ( $query_parts as $query_part ) {
			$key_value                    = explode( '=', $query_part, 2 );
			$query_array[ $key_value[0] ] = $key_value[1];
		}


		return $query_array;
	}
}


if ( ! function_exists( 'unparse_query' ) ) {
	/**
	 * unparse a query
	 *
	 * @param $query_parts
	 *
	 * @return string
	 * @since    6.0
	 * @verified 2016.12.17
	 */
	function unparse_query( $query_parts )
	{
		$query = [];


		if ( ! empty( $query_parts ) ) {
			foreach ( $query_parts as $k => $v ) {
				$query[] = $k . '=' . $v;
			}
		}


		return implode( '&', $query );
	}
}


if ( ! function_exists( 'is_blog' ) ) {
	/**
	 * Check if a page is a blogroll or single post.
	 *
	 * @return bool
	 * @since    6.2
	 * @verified 2016.12.17
	 */
	function is_blog()
	{
		return lct_is_blog();
	}
}


if ( ! function_exists( 'the_slug' ) ) {
	/**
	 * Get the slug of a post
	 *
	 * @param           $post_id
	 * @param bool|true $slash
	 *
	 * @return bool|string
	 * @since    6.2
	 * @verified 2016.12.17
	 */
	function the_slug( $post_id, $slash = true )
	{
		return lct_get_the_slug( $post_id, $slash );
	}
}


if ( ! function_exists( '__return_yes' ) ) {
	/**
	 * Return 'yes'
	 *
	 * @return string
	 * @since    2018.34
	 * @verified 2018.03.26
	 */
	function __return_yes()
	{
		return 'yes';
	}
}


if ( ! function_exists( 'stable_uasort' ) ) {
	/**
	 * If you want to keep the order when two members compare as equal, use this.
	 * http://php.net/manual/en/function.uasort.php
	 * Search: If you want to keep the order when two members compare as equal, use this.
	 *
	 * @param $array
	 * @param $cmp_function
	 *
	 * @since    2018.51
	 * @verified 2018.05.22
	 */
	function stable_uasort( &$array, $cmp_function )
	{
		if ( count( $array ) < 2 ) {
			return;
		}


		$halfway = count( $array ) / 2;
		$array1  = array_slice( $array, 0, $halfway, true );
		$array2  = array_slice( $array, $halfway, null, true );


		stable_uasort( $array1, $cmp_function );
		stable_uasort( $array2, $cmp_function );


		if ( call_user_func( $cmp_function, end( $array1 ), reset( $array2 ) ) < 1 ) {
			$array = $array1 + $array2;


			return;
		}


		$array = [];
		reset( $array1 );
		reset( $array2 );


		while(
			current( $array1 )
			&& current( $array2 )
		) {
			if ( call_user_func( $cmp_function, current( $array1 ), current( $array2 ) ) < 1 ) {
				$array[ key( $array1 ) ] = current( $array1 );
				next( $array1 );
			} else {
				$array[ key( $array2 ) ] = current( $array2 );
				next( $array2 );
			}
		}


		while( current( $array1 ) ) {
			$array[ key( $array1 ) ] = current( $array1 );
			next( $array1 );
		}


		while( current( $array2 ) ) {
			$array[ key( $array2 ) ] = current( $array2 );
			next( $array2 );
		}


		return;
	}
}
