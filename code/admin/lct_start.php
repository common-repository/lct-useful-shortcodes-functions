<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Load the default class
 */
if ( file_exists( WP_PLUGIN_DIR . '/lct-useful-shortcodes-functions/code/admin/lct.php' ) ) {
	include_once( WP_PLUGIN_DIR . '/lct-useful-shortcodes-functions/code/admin/lct.php' );
}
