<?php //edit_zz cs - SEE .git ORIGINALzz - themes/Avada/templates/menu-mobile-main.php
/**
 * Mobile main menu template.
 *
 * @author         ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link           http://theme-fusion.com
 * @package        Avada
 * @subpackage     Core
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

if ( 'flyout' === Avada()->settings->get( 'mobile_menu_design' ) && ( 'Top' !== Avada()->settings->get( 'header_position' ) || ! in_array( Avada()->settings->get( 'header_layout' ), [ 'v4', 'v5' ] ) ) ) {
	get_template_part( 'templates/menu-mobile-flyout' );
} elseif ( 'Top' !== Avada()->settings->get( 'header_position' ) || ( ! in_array( Avada()->settings->get( 'header_layout' ), [ 'v4', 'v5' ] ) ) ) {
	get_template_part( 'templates/menu-mobile-modern' );
}

$mobile_menu_css_classes = ' fusion-flyout-menu fusion-flyout-mobile-menu';
if ( 'flyout' !== Avada()->settings->get( 'mobile_menu_design' ) ) {
	$mobile_menu_css_classes = ' fusion-mobile-menu-text-align-' . Avada()->settings->get( 'mobile_menu_text_align' );
}

if ( ! Avada()->settings->get( 'mobile_menu_submenu_indicator' ) ) {
	$mobile_menu_css_classes .= ' fusion-mobile-menu-indicator-hide';
}
?>

	<nav class="fusion-mobile-nav-holder<?php echo esc_attr( $mobile_menu_css_classes ); ?>" aria-label="<?php esc_attr_e( 'Main Menu Mobile', 'Avada' ); ?>"></nav>

<?php if ( has_nav_menu( 'sticky_navigation' ) && 'Top' === Avada()->settings->get( 'header_position' ) ) : ?>
	<nav class="fusion-mobile-nav-holder<?php echo esc_attr( $mobile_menu_css_classes ); ?> fusion-mobile-sticky-nav-holder" aria-label="<?php esc_attr_e( 'Main Menu Mobile Sticky', 'Avada' ); ?>"></nav>
<?php endif; ?>
<?php

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
