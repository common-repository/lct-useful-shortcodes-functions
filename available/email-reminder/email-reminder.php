<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Version: 1.3
 * License: GPLv2
 * Author: Ryann Micua
 */


/**
 * Constants
 */
define( 'PDER_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'PDER_URI', trailingslashit( plugins_url( '', __FILE__ ) ) );
define( 'PDER_ASSETS', PDER_URI . 'assets' );
define( 'PDER_INC_DIR', trailingslashit( PDER_DIR ) . 'includes' );
define( 'PDER_CLASSES', trailingslashit( PDER_INC_DIR ) . 'classes' );
define( 'PDER_VIEWS', PDER_DIR . 'views' );


/**
 * Load Base class
 */
/** @noinspection PhpIncludeInspection */
require_once( trailingslashit( PDER_CLASSES ) . 'PDER_Base.php' );
