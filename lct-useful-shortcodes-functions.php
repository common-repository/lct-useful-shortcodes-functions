<?php
/**
 * Plugin Name: LCT Useful Shortcodes & Functions
 * Plugin URI: https://www.simplesmithmedia.com
 * Description: Shortcodes & Functions that will help make your life easier.
 * Version: 2024.10
 * Author: SimpleSmithMedia
 * Author URI: https://www.simplesmithmedia.com
 * Text Domain: TD_LCT
 * Domain Path: /lang
 * License: GPLv2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * Copyright 2024 SimpleSmithMedia (email : info@simplesmithmedia.com)
 *
 * @copyright 2024
 * @license   GPLv2
 * @since     1.0
 */

/**
 * Copyright (C) 2024 SimpleSmithMedia
 * *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */


//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


//Just stop the madness if WP is busy installing
if ( defined( 'WP_INSTALLING' ) ) {
	return;
}


/**
 * MUST HAVE Settings
 *
 * @since    7.38
 * @verified 2017.03.28
 */
global $lct_root_settings;

$lct_root_settings = [
	// urls
	'plugin_file' => __FILE__,
	'basename'    => plugin_basename( __FILE__ ),
	'root_path'   => plugin_dir_path( __FILE__ ), //INCLUDES trailing slash
	'root_url'    => plugin_dir_url( __FILE__ ), //INCLUDES trailing slash
];


/**
 * SPECIAL CRITICAL ERROR NOTIFICATION :: ALSO IN MU
 * Always email the admin when there is a critical error
 *
 * @date       2022.02.11
 * @since      2022.1
 * @verified   2022.02.11
 */
//add_filter( 'recovery_mode_email_rate_limit', function () {return 1;return HOUR_IN_SECONDS;}, 99999 );
add_filter( 'is_protected_endpoint', '__return_true', 99999 );
add_filter( 'recovery_mode_email', 'lct_recovery_mode_email', 99999, 2 );


/**
 * SPECIAL CRITICAL ERROR NOTIFICATION :: ALSO IN MU
 * Make sure the email is formatted properly & easily grabbed by an email filter
 *
 * @param array $email
 *
 * @unused     param string $url
 * @date       2022.02.11
 * @since      2022.1
 * @verified   2022.02.11
 */
function lct_recovery_mode_email( $email )
{
	/**
	 * Add filter text to the subject
	 */
	if ( strpos( $email['subject'], '[WP CRITICAL ERROR]' ) === false ) {
		$email['subject'] = '[WP CRITICAL ERROR] ' . $email['subject'];
	}


	/**
	 * Force Plain Text
	 */
	add_filter( 'wp_mail_content_type', //Do not place hook on new line
		function () {
			return 'text/plain';
		},
		99999
	);


	return $email;
}


/**
 * Everything starts here
 *
 * @since    7.38
 * @verified 2017.03.28
 */
include_once( 'code/__init.php' );
