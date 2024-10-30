jQuery( document ).ready( function() {
	/**
	 * Mobile Navigation.
	 */
	jQuery( '.fusion-mobile-nav-holder' ).not( '.fusion-mobile-sticky-nav-holder' ).each( function() {
		var $mobileNavHolder = jQuery( this ),
			$mobileNav = '',
			$menu = jQuery( 'body' ).find( '.fusion-main-menu, .fusion-secondary-menu' ).not( '.fusion-sticky-menu' );

		if( $menu.length ) {
			if( 'classic' === avadaVars.mobile_menu_design ) {
				$mobileNavHolder.append( '<div class="fusion-mobile-selector"><span>' + avadaVars.dropdown_goto + '</span></div>' );
				jQuery( this ).find( '.fusion-mobile-selector' ).append( '<div class="fusion-selector-down"></div>' );
			}

			jQuery( $mobileNavHolder ).append( jQuery( $menu ).find( '> ul' ).clone() );

			$mobileNav = jQuery( $mobileNavHolder ).find( '> ul' );
			$mobileNav.removeClass( 'fusion-middle-logo-ul' );

			$mobileNav.find( '.fusion-middle-logo-menu-logo, .fusion-caret, .fusion-menu-login-box .fusion-custom-menu-item-contents, .fusion-menu-cart .fusion-custom-menu-item-contents, .fusion-main-menu-search, li> a > span > .button-icon-divider-left, li > a > span > .button-icon-divider-right' ).remove();

			if( 'classic' === avadaVars.mobile_menu_design ) {
				$mobileNav.find( '.fusion-menu-cart > a' ).html( avadaVars.mobile_nav_cart );
			} else {
				$mobileNav.find( '.fusion-main-menu-cart' ).remove();
			}

			$mobileNav.find( 'li' ).each( function() {

				var classes = 'fusion-mobile-nav-item';
				if( jQuery( this ).data( 'classes' ) ) {
					classes += ' ' + jQuery( this ).data( 'classes' );
				}

				jQuery( this ).find( '> a > .menu-text' ).removeAttr( 'class' ).addClass( 'menu-text' );

				if( jQuery( this ).hasClass( 'current-menu-item' ) || jQuery( this ).hasClass( 'current-menu-parent' ) || jQuery( this ).hasClass( 'current-menu-ancestor' ) ) {
					classes += ' fusion-mobile-current-nav-item';
				}

				jQuery( this ).attr( 'class', classes );

				if( jQuery( this ).attr( 'id' ) ) {
					jQuery( this ).attr( 'id', jQuery( this ).attr( 'id' ).replace( 'menu-item', 'mobile-menu-item' ) );
				}

				jQuery( this ).attr( 'style', '' );
			} );

			jQuery( this ).find( '.fusion-mobile-selector' ).click( function() {
				if( $mobileNav.hasClass( 'mobile-menu-expanded' ) ) {
					$mobileNav.removeClass( 'mobile-menu-expanded' );
				} else {
					$mobileNav.addClass( 'mobile-menu-expanded' );
				}

				$mobileNav.slideToggle( 200, 'easeOutQuad' );
			} );
		}
	} );


	/**
	 * Force Show the menu
	 */
	jQuery( '.fusion-mobile-nav-holder' ).show();
	jQuery( '.fusion-mobile-nav-holder ul.fusion-menu' ).show();
} );
