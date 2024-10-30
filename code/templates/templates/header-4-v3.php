<?php //edit_zz cs - SEE .git ORIGINALzz - themes/Avada/templates/header-4.php
/**
 * Header-4 template.
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
<div class="fusion-header">
	<div class="fusion-row fusion-middle-logo-menu">
		<?php if ( 'flyout' === Avada()->settings->get( 'mobile_menu_design' ) ) : ?>
		<div class="fusion-header-has-flyout-menu-content">
			<?php endif; ?>


			<?php lct_header_layout(); ?>


			<?php if ( 'flyout' === Avada()->settings->get( 'mobile_menu_design' ) ) : ?>
		</div>
	<?php endif; ?>


		<?php /* default
		<?php if ( 'flyout' === Avada()->settings->get( 'mobile_menu_design' ) ) : ?>
			<div class="fusion-header-has-flyout-menu-content">
		<?php endif; ?>
		<?php avada_main_menu(); ?>
		<?php if ( 'flyout' === Avada()->settings->get( 'mobile_menu_design' ) ) : ?>
			</div>
		<?php endif; ?>
		*/ ?>
	</div>
</div>
