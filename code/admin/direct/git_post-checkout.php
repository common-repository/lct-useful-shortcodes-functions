<?php
$path_direct         = '/lct-useful-shortcodes-functions/code/admin/direct';
$this_file_reference = $path_direct . '/git_post-checkout.php';
$LCT_DEV_VARIABLES   = [];

$find = [
	"/plugins{$this_file_reference}",
	'/lc-content',
	'/wp-content',
];

if ( isset( $_GET['wp_content'] ) ) {
	$find[] = $_GET['wp_content'];
}

if ( isset( $_SERVER['REQUEST_URI'] ) ) {
	$path_site = $_SERVER['DOCUMENT_ROOT'];
} else {
	$path_site = str_replace( "/", "\\", $_SERVER['PWD'] );
}

if ( isset( $_SERVER['REQUEST_URI'] ) ) {
	$path_file = $_SERVER['REQUEST_URI'];
} else {
	$path_file = '/' . $_SERVER['PHP_SELF'];
}

$path_wp      = str_replace( $find, '', $path_file );
$path_site_wp = $path_site . $path_wp;


if ( function_exists( 'getenv' ) ) {
	$LCT_DEV_VARIABLE_KEYS = [
		'_EDITZZ_WAMP_PATH',
		'_EDITZZ_WAMP_APPS_DIR',
	];

	foreach ( $LCT_DEV_VARIABLE_KEYS as $var ) {
		$LCT_DEV_VARIABLES[ str_replace( '_EDITZZ_', '', $var ) ] = getenv( $var );
	}
}


if ( file_exists( $path_site_wp . '/wp-config.php' ) ) {
	require_once( $path_site_wp . '/wp-config.php' );
}

if ( file_exists( $LCT_DEV_VARIABLES['WAMP_APPS_DIR'] . '/_custom/_lct_plugin/_config.php' ) ) {
	require_once( $LCT_DEV_VARIABLES['WAMP_APPS_DIR'] . '/_custom/_lct_plugin/_config.php' );
} elseif ( file_exists( $LCT_DEV_VARIABLES['WAMP_APPS_DIR'] . '/_lct_plugin/_config.php' ) ) {
	require_once( $LCT_DEV_VARIABLES['WAMP_APPS_DIR'] . '/_lct_plugin/_config.php' );
}


if ( $DB['LIVE']['live_site_path'] == 'editzz' ) {
	$DB['LIVE']['live_site_path'] = 'addons_sub/wp/';
}

if ( empty( $DB['LIVE']['sftp_user'] ) ) {
	$DB['LIVE']['sftp_user'] = $DB['LIVE']['cpanel_user'];
}

( $DB['LIVE']['client'] == '00pimg ' ) ? $DB['LIVE']['auto_sftp_home'] = '/root' : $DB['LIVE']['auto_sftp_home'] = '/home/' . $DB['LIVE']['cpanel_user'];

$DB['LIVE']['site_home'] = '/home/' . $DB['LIVE']['cpanel_user'] . '/public_html/' . $DB['LIVE']['live_site_path'];


shell_exec( 'ssh ' . $DB['LIVE']['sftp_user'] . '@' . $DB['LIVE']['sftp_host'] . ' "cd ' . $DB['LIVE']['site_home'] . ' && git ll && git acu && git h"' );
shell_exec( 'git fetch --all' );


/*
echo '<br />';
echo '<br />';
echo 'TESTING<br />';

echo $path_site . '<br />';
echo $path_site_wp . '<br />';
echo WP_CONTENT_DIR . '<br />';

echo '<pre>';
print_r( $_SERVER );
print_r( $LCT_DEV_VARIABLES );
echo '</pre>';
*/
