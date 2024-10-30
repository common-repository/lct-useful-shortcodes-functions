<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * List of only role and cap prefixes
 *
 * @return array
 * @since    5.28
 * @verified 2017.04.29
 */
function lct_get_role_cap_prefixes_only()
{
	return [
		zxzu( 'role_' ),
		zxzu( 'cap_' ),
	];
}


/**
 * List of any prefixed we are using for role/cap filtering
 *
 * @return array
 * @since    5.28
 * @verified 2017.04.29
 */
function lct_get_role_cap_prefixes()
{
	return array_merge(
		lct_get_role_cap_prefixes_only(),
		[
			zxzu( 'ch_' ),
		]
	);
}


/**
 * unset classes that are only for access checks
 *
 * @param      $item
 * @param null $roles
 *
 * @return mixed
 * @since    2017.23
 * @verified 2017.04.27
 */
function lct_cleanup_role_classes( $item, $roles = null )
{
	$classes_str    = '';
	$should_replace = 0;


	if ( ! $roles ) {
		$roles = lct_get_role_cap_prefixes();
	}


	if ( ! is_array( $roles ) ) {
		$roles = [ $roles ];
	}


	/**
	 * parse the classes
	 */
	preg_match_all( '/class="(.*?)"/', $item, $classes_match );


	if ( ! empty( $classes_match[1] ) ) {
		if ( is_array( $classes_match[1] ) ) {
			$classes_str = $classes_match[1][0];
		} elseif ( $classes_match[1] ) {
			$classes_str = $classes_match[1];
		}


		if ( $classes_str ) {
			$classes = explode( ' ', $classes_str );


			foreach ( $roles as $role ) {
				if ( strpos( $classes_str, $role ) !== false ) {
					foreach ( $classes as $class_k => $class ) {
						if ( strpos( $class, $role ) !== false ) {
							unset( $classes[ $class_k ] );
							$should_replace ++;
						}
					}
				}
			}


			if ( $should_replace ) {
				$item = str_replace( $classes_str, implode( ' ', $classes ), $item );
			}
		}
	}


	return $item;
}


/**
 * unset classes that are only for access checks
 *
 * @param      $classes
 * @param null $roles
 *
 * @return mixed
 * @since    2017.34
 * @verified 2017.04.27
 */
function lct_cleanup_role_classes_array( $classes = [], $roles = null )
{
	if ( ! $roles ) {
		$roles = lct_get_role_cap_prefixes();
	}


	if ( ! is_array( $roles ) ) {
		$roles = [ $roles ];
	}


	if ( $classes ) {
		foreach ( $roles as $role ) {
			if ( strpos_array( $classes, $role, true ) !== false ) {
				foreach ( $classes as $class_k => $class ) {
					if ( strpos( $class, $role ) !== false ) {
						unset( $classes[ $class_k ] );
					}
				}
			}
		}
	}


	return $classes;
}


/**
 * Check if this item should be unset based on user_logged_in status
 *
 * @param $classes
 *
 * @return bool
 * @since    0.0
 * @verified 2017.04.27
 */
function lct_check_user_logged_in_of_class( $classes )
{
	$r = false;


	if ( is_array( $classes ) ) {
		$classes = implode( ' ', $classes );
	}


	/**
	 * Check if this item is for any guest user
	 */
	if ( strpos( $classes, zxzu( 'ch_not_is_user_logged_in' ) ) !== false ) {
		if ( is_user_logged_in() ) {
			$r = true;
		}


		/**
		 * Check if this item is for any logged in user
		 */
	} elseif ( strpos( $classes, zxzu( 'ch_is_user_logged_in' ) ) !== false ) {
		if ( ! is_user_logged_in() ) {
			$r = true;
		}
	}


	return $r;
}


/**
 * Check if this item should be unset
 * See: lct_get_role_cap_prefixes() to properly set $class_types
 *
 * @param       $classes
 *
 * @return bool
 * @since    0.0
 * @verified 2017.06.30
 */
function lct_check_role_of_class( $classes )
{
	$class_types = lct_get_role_cap_prefixes_only();
	$r           = false;


	/**
	 * parse the classes
	 */
	if ( ! is_array( $classes ) ) {
		preg_match_all( '/class="(.*?)"/', $classes, $classes_match );

		if ( is_array( $classes_match[1] ) ) {
			$classes = explode( ' ', $classes_match[1][0] );
		} elseif ( $classes_match[1] ) {
			$classes = explode( ' ', $classes_match[1] );
		} else {
			$classes = explode( ' ', $classes );
		}
	}


	if ( ! empty( $classes ) ) {
		foreach ( $classes as $class_k => $class ) {
			if (
				strpos( $class, 'menu-item' ) !== false
				|| strpos( $class, 'fusion' ) !== false
				|| strpos( $class, 'current_' ) === 0
			) {
				unset( $classes[ $class_k ] );
			}
		}
	}


	if ( ! empty( $classes ) ) {
		$r = true;


		foreach ( $class_types as $class_type ) {
			if ( strpos_array( $classes, $class_type, true ) !== false ) {
				$should_keep = 0;


				foreach ( $classes as $class_k => $class ) {
					$role = str_replace( $class_type, '', $class );


					if ( current_user_can( $role ) ) {
						$should_keep ++;
					}
				}


				if ( ! empty( $should_keep ) ) {
					$r = false;


					break;
				}
			}
		}
	}


	return $r;
}
