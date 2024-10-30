<?php //edit_zz cs - SEE .git ORIGINALzz - completely custom
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/** @noinspection PhpUndefinedVariableInspection */
do_action( 'woocommerce_email_header', $email_heading, $email );

/** @noinspection PhpUndefinedVariableInspection */
printf( $body );

do_action( 'woocommerce_email_footer', $email );
