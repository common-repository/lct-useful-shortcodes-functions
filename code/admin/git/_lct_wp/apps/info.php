<?php /*
_editzz Name: /apps/info.php
URI:
Version: 7.7
Author: Cary Smith
Author URI:
License: GPLv3 or higher
Description:
*/


$url         = 'https://eetah.com/api/lct/good_ips.php?key=simple_key';
$resp        = file_get_contents( $url );
$allowed_ips = json_decode( $resp, true );


if (
	! $allowed_ips
	|| (
		$allowed_ips
		&& ! in_array( $_SERVER['REMOTE_ADDR'], $allowed_ips )
		&& ! isset( $_GET['skip_ip_check'] )
	)
) {
	echo '<h1 style="text-align: center;">To use this page please request your IP (' . $_SERVER['REMOTE_ADDR'] . ') be added to the approved list.</h1>';
	exit;
}


echo phpinfo();
