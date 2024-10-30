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

if ( 'flyout' === Avada()->settings->get( 'mobile_menu_design' ) && ( 'Top' !== Avada()->settings->get( 'header_position' ) || ! in_array( Avada()->settings->get( 'header_layout' ), [ 'v4', 'v5' ] ) ) ) :

	get_template_part( 'templates/menu-mobile-flyout' );

else :
	if (
		'Top' !== Avada()->settings->get( 'header_position' )
		|| ! in_array( Avada()->settings->get( 'header_layout' ), [ 'v4', 'v5' ] )
	) {
		get_template_part( 'templates/menu-mobile-modern' );
	}

endif; ?>

<?php
$mobile_menu_css_classes = 'flyout' === Avada()->settings->get( 'mobile_menu_design' )
	? ' fusion-flyout-menu fusion-flyout-mobile-menu'
	: ' fusion-mobile-menu-text-align-' . Avada()->settings->get( 'mobile_menu_text_align' );
?>

	<nav class="fusion-mobile-nav-holder<?php echo esc_attr( $mobile_menu_css_classes ); ?>">
		<?php if ( has_nav_menu( 'mobile_navigation' ) ) {
			echo wp_nav_menu(
				[
					'theme_location' => 'mobile_navigation',
					'depth'          => 5,
					'menu_class'     => 'fusion-menu',
					'items_wrap'     => '<ul role="menubar" id="%1$s" class="%2$s">%3$s</ul>',
					'fallback_cb'    => 'Avada_Nav_Walker::fallback',
					'walker'         => new Avada_Nav_Walker(),
					'container'      => false,
					'item_spacing'   => 'discard',
					'echo'           => false,
				]
			);
		} ?>
	</nav>

<?php if (
	! has_nav_menu( 'mobile_navigation' )
	&& has_nav_menu( 'sticky_navigation' )
	&& 'Top' === Avada()->settings->get( 'header_position' )
) : ?>
	<nav class="fusion-mobile-nav-holder<?php echo esc_attr( $mobile_menu_css_classes ); ?> fusion-mobile-sticky-nav-holder"></nav>
<?php
endif;

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
