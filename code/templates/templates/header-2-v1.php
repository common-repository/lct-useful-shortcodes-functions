<?php //edit_zz cs - SEE .git ORIGINALzz - themes/Avada/templates/header-2.php
/**
 * Header-2 template.
 *
 * @author         ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link           http://theme-fusion.com
 * @package        Avada
 * @subpackage     Core
 */

//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="fusion-header-sticky-height"></div>
<div class="fusion-sticky-header-wrapper"> <!-- start fusion sticky header wrapper -->
	<div class="fusion-header">
		<div class="fusion-row">
			<?php lct_header_layout(); ?>
			<?php get_template_part( 'templates/menu-mobile-modern' ); ?>

			<?php /* default
			<?php avada_logo(); ?>
			<?php get_template_part( 'templates/menu-mobile-modern' ); ?>
			*/ ?>
		</div>
	</div>
