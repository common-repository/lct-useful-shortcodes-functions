<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Store a theme's info in the main obj
 *
 * @param $theme_name
 * @param $save_as
 *
 * @since    7.42
 * @verified 2018.03.22
 */
function lct_set_current_theme( $theme_name, $save_as = null )
{
	$theme_status    = [];
	$current_theme   = wp_get_theme();
	$active_themes[] = $current_theme->__get( 'name' );
	$active_themes[] = $current_theme->__get( 'parent_theme' );

	if ( ! $save_as ) {
		$save_as = $theme_name;
	}


	/**
	 * Normal Theme Check
	 */
	if ( in_array( $theme_name, $active_themes ) ) {
		$theme_status['active']  = true;
		$theme_status['version'] = $current_theme->__get( 'Version' );


		if ( $current_theme->parent() ) {
			$theme_status['version'] = $current_theme->parent()->__get( 'Version' );
		}


		/**
		 * We need this special let thru for ACF queries
		 * We swap the theme for speed
		 * so we need to trick WP into thinking we loaded Avada so that ACF fields populate
		 */
	} elseif (
		lct_doing()
		&& ! empty( $_POST['action'] )
		&& in_array( 'None', $active_themes )
		&& strpos( $_POST['action'], 'acf/' ) !== false
		&& ! empty( $_POST['post_id'] )
		&& $_POST['post_id'] === lct_o()
	) {
		$theme_status['active']  = true;
		$theme_status['version'] = '5.4.2';


		/**
		 * If the activated theme is not what we are expecting
		 */
	} else {
		if ( $current_theme->parent() ) {
			$theme_name              = $current_theme->__get( 'parent_theme' );
			$theme_status['version'] = $current_theme->parent()->__get( 'Version' );
		} else {
			$theme_name              = $current_theme->__get( 'name' );
			$theme_status['version'] = $current_theme->__get( 'Version' );
		}
	}


	$theme_status = wp_parse_args( $theme_status, lct_theme_default_args() );


	lct_update_setting( 'theme_' . $save_as, $theme_status );

	lct_update_setting( 'theme_current', $theme_name );

	lct_update_setting( 'theme_current_version', $theme_status['version'] );

	lct_update_setting( 'theme_child_version', $current_theme->__get( 'Version' ) );
}


/**
 * The default array for set_theme
 *
 * @return array
 * @since    7.42
 * @verified 2016.11.29
 */
function lct_theme_default_args()
{
	$r = [
		'active'  => false,
		'version' => '0.0',
	];


	return $r;
}


/**
 * Is a theme action?
 *
 * @param $theme
 *
 * @return null
 * @since    7.42
 * @verified 2016.11.29
 */
function lct_theme_active( $theme )
{
	$r = null;


	$theme = lct_get_setting( 'theme_' . $theme );


	if ( ! empty( $theme ) ) {
		$r = $theme['active'];
	}


	return $r;
}


/**
 * Returns the version of a theme
 *
 * @param $theme
 *
 * @return null
 * @since    7.42
 * @verified 2016.11.29
 */
function lct_theme_version( $theme )
{
	$r = null;


	$theme = lct_get_setting( 'theme_' . $theme );


	if ( ! empty( $theme ) ) {
		$r = $theme['version'];
	}


	return $r;
}


/**
 * Returns the version of the current theme
 * Always returns the parent's version
 *
 * @return null
 * @since    7.62
 * @verified 2017.10.06
 */
function lct_current_theme_version()
{
	$r       = null;
	$version = lct_get_setting( 'theme_current_version' );


	if ( ! empty( $version ) ) {
		$r = $version;
	}


	return $r;
}


/**
 * Returns the version of the active theme
 * Child version if child theme is in use, otherwise just the active theme
 *
 * @return null
 * @since    2017.86
 * @verified 2017.10.06
 */
function lct_active_theme_version()
{
	$r       = null;
	$version = lct_get_setting( 'theme_child_version' );


	if ( ! empty( $version ) ) {
		$r = $version;
	}


	return $r;
}


/**
 * Returns the major version of the current theme
 *
 * @return null
 * @since    7.62
 * @verified 2017.01.04
 */
function lct_current_theme_major_version()
{
	return (int) lct_current_theme_version();
}


/**
 * Returns the minor version of the current theme
 *
 * @return null
 * @since    2017.34
 * @verified 2017.05.08
 */
function lct_current_theme_minor_version()
{
	return (float) lct_current_theme_version();
}
