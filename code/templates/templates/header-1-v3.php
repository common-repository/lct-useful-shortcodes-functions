<?php //edit_zz cs - SEE .git ORIGINALzz - themes/Avada/templates/header-1.php
/**
 * Header-1 template.
 * Header-2 template.
 * Header-3 template.
 * Header-4 template.
 *
 * @author         ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link           https://theme-fusion.com
 * @package        Avada
 * @subpackage     Core
 */

//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$header_type = Avada()->settings->get( 'header_layout' );
lct_avada_default_overrider( 'templates/header-' . $header_type );
