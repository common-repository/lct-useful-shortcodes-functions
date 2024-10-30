<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Pairs with: assets/css/plugins/gforms/main.min.css
 */
function lct_dynamic_css_gforms()
{
	$css              = [];
	$mobile_threshold = lct_get_mobile_threshold();


	$css[] = '
	@media (max-width: ' . $mobile_threshold . 'px) {
		/* STARTzz */
		.hide_label_if_mobile label{
			display: none !important;
		}

		.gform_wrapper.' . zxzu( 'gf_2_col_wrapper' ) . ' ul.gform_fields.' . zxzu( 'gf_col' ) . ',
		.gform_wrapper.' . zxzu( 'gf_3_col_wrapper' ) . ' ul.gform_fields.' . zxzu( 'gf_col' ) . '{
			display:       block;
			float:         none;
			width:         inherit;
			margin-right:  inherit !important;
			padding-right: 16px;
		}
		/* ENDzz */
	}


	@media (min-width: ' . ( $mobile_threshold + 1 ) . 'px) {
		/* STARTzz */
		.hide_label_if_desktop label{
			display: none !important;
		}
		/* ENDzz */
	}
	';


	/**
	 * #1
	 * @date     0.0
	 * @since    0.0
	 * @verified 2021.08.27
	 */
	do_action( 'lct_wp_footer_style_add', lct_return( $css ) );
}
