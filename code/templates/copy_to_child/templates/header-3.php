<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Override this template file with the one in the LCT plugin or somewhere else custom
 * This sucks: maybe one day WordPress will put a filter get_template_part() or better locate_template()
 */
lct_template_part( 'templates/header-3', 'v' . lct_current_theme_major_version() );
