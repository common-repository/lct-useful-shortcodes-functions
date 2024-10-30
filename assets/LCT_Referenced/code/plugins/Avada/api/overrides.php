<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) )
	exit;


/**
 * The default method for checking for the right file
 *
 * @param $template
 *
 * @since    2017.11
 * @verified 2019.06.05
 */
function lct_avada_default_overrider( $template ) {
	$last_valid_version = '5.9';


	//Use the theme file if it exists
	if ( file_exists( lct_path_theme( '/' . $template . '.php' ) ) ) {
		get_template_part( $template );


		//Or filter thru our template router
	} else if ( $located = lct_get_template_part( $template, 'v' . lct_current_theme_minor_version() ) ) {
		load_template( $located, false );


		//Or filter thru our template router
	} else if ( $located = lct_get_template_part( $template, 'v' . $last_valid_version ) ) {
		load_template( $located, false );


		//Or just use the WP one
	} else {
		get_template_part( $template );
	}
}


/**
 * Only if Avada is v5.0 and newer
 */
if ( version_compare( lct_theme_version( 'Avada' ), '5.0', '>=' ) ) : //Avada is v5.0 and newer


	if ( ! function_exists( 'avada_logo' ) ) {
		/**
		 * Gets the logo template if needed.
		 *
		 * @since    7.64
		 * @verified 2017.05.08
		 */
		function avada_logo() {
			// No need to proceed any further if no logo is set.
			if (
				'' === Avada()->settings->get( 'logo' ) &&
				'' === Avada()->settings->get( 'logo_retina' )
			) {
				return;
			}


			lct_avada_default_overrider( 'templates/logo' );
		}
	}


	if ( ! function_exists( 'avada_header_1' ) ) {
		/**
		 * Gets the header-1 template if needed.
		 *
		 * @since    7.62
		 * @verified 2017.05.08
		 */
		function avada_header_1() {
			if ( ! in_array( Avada()->settings->get( 'header_layout' ), [ 'v1', 'v2', 'v3' ] ) )
				return;


			lct_avada_default_overrider( 'templates/header-1' );
		}
	}


	if ( ! function_exists( 'avada_header_2' ) ) {
		/**
		 * Gets the header-2 template if needed.
		 *
		 * @since    7.62
		 * @verified 2017.05.08
		 */
		function avada_header_2() {
			if ( ! in_array( Avada()->settings->get( 'header_layout' ), [ 'v4', 'v5' ] ) )
				return;


			lct_avada_default_overrider( 'templates/header-2' );
		}
	}


	if ( ! function_exists( 'avada_header_3' ) ) {
		/**
		 * Gets the header-3 template if needed.
		 *
		 * @since    7.62
		 * @verified 2017.05.08
		 */
		function avada_header_3() {
			if ( 'v6' !== Avada()->settings->get( 'header_layout' ) )
				return;


			lct_avada_default_overrider( 'templates/header-3' );
		}
	}


	if ( ! function_exists( 'avada_header_4' ) ) {
		/**
		 * Gets the template part for the v7 header.
		 *
		 * @since    Avada 5.0
		 * @since    7.62
		 * @verified 2017.05.08
		 */
		function avada_header_4() {
			if ( 'v7' !== Avada()->settings->get( 'header_layout' ) )
				return;


			lct_avada_default_overrider( 'templates/header-4' );
		}
	}


	if ( ! function_exists( 'avada_side_header' ) ) {
		/**
		 * Avada Side Header Template Function.
		 *
		 * @return void
		 * @since    7.62
		 * @verified 2017.08.25
		 */
		function avada_side_header() {
			$queried_object_id = get_queried_object_id();


			if (
				! is_page_template( 'blank.php' ) &&
				'no' != get_post_meta( $queried_object_id, 'pyre_display_header', true )
			) {
				lct_avada_default_overrider( 'templates/side-header' );
			}
		}
	}


	if ( ! function_exists( 'avada_main_menu' ) ) {
		/**
		 * ROUTER FOR DIFFERENT AVADA VERSIONS
		 * The main menu.
		 *
		 * @param bool $flyout_menu Whether we want the flyout menu or not.
		 *
		 * @since    2017.34
		 * @verified 2018.03.22
		 */
		function avada_main_menu( $flyout_menu = false ) {
			/**
			 * Only if Avada is v5.4 and newer
			 */
			if ( version_compare( lct_theme_version( 'Avada' ), '5.4', '>=' ) ) { //Avada is v5.1 and newer
				avada_main_menu_v5_4( $flyout_menu );


				/**
				 * Only if Avada is v5.1 and newer
				 */
			} else if ( version_compare( lct_theme_version( 'Avada' ), '5.1', '>=' ) ) { //Avada is v5.1 and newer
				avada_main_menu_v5_1( $flyout_menu );


				/**
				 * Everything else
				 */
			} else {
				avada_main_menu_v5( $flyout_menu );
			}
		}


		/**
		 * The main menu.
		 *
		 * @param bool $flyout_menu Whether we want the flyout menu or not.
		 *
		 * @return false|object
		 * @since    7.67
		 * @verified 2018.02.23
		 */
		function avada_main_menu_v5( $flyout_menu = false ) {
			/** @noinspection PhpUnusedLocalVariableInspection */
			$sticky_menu = '';

			$menu_class = 'fusion-menu';
			if ( 'v7' == Avada()->settings->get( 'header_layout' ) ) {
				$menu_class .= ' fusion-middle-logo-ul';
			}

			$main_menu_args = [
				'theme_location'  => apply_filters( 'lct/avada_main_menu/theme_location', 'main_navigation' ),
				'depth'           => 5,
				'menu_class'      => $menu_class,
				'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'fallback_cb'     => 'Avada_Nav_Walker::fallback',
				'walker'          => new Avada_Nav_Walker(),
				'container_class' => 'fusion-main-menu',
				'container'       => 'nav',
			];

			if ( $flyout_menu ) {
				$flyout_menu_args = [
					'depth'     => 1,
					'container' => false,
					'echo'      => false,
				];

				$main_menu_args = wp_parse_args( $flyout_menu_args, $main_menu_args );

				$main_menu = wp_nav_menu( $main_menu_args );

				return $main_menu;

			} else {

				wp_nav_menu( $main_menu_args );

				if (
					has_nav_menu( 'sticky_navigation' ) &&
					'Top' == Avada()->settings->get( 'header_position' ) &&
					(
						! function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) ||
						(
							function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) &&
							! ubermenu_get_menu_instance_by_theme_location( 'sticky_navigation' )
						)
					)
				) {
					$sticky_menu_args = [
						'theme_location'  => 'sticky_navigation',
						'container_class' => 'fusion-main-menu fusion-sticky-menu',
						'menu_id'         => 'menu-main-menu-1',
						'walker'          => new Avada_Nav_Walker(),
					];

					$sticky_menu_args = wp_parse_args( $sticky_menu_args, $main_menu_args );

					wp_nav_menu( $sticky_menu_args );
				}

				// Make sure mobile menu is not loaded when we use slideout menu or ubermenu.
				if (
					! function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) ||
					(
						function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) &&
						! ubermenu_get_menu_instance_by_theme_location( 'main_navigation' )
					)
				) {
					if (
						(
							! lct_acf_get_option_raw( 'is_header_layout_home' ) &&
							! lct_acf_get_option_raw( 'is_header_layout' )
						) ||
						in_array( Avada()->settings->get( 'header_layout' ), [ 'v4' ] )
					) {
						if (
							! lct_acf_get_option( 'sc::overlay_menu_bg' ) &&
							! lct_plugin_active( 'nks' )
						) {
							avada_mobile_main_menu();
						}
					}
				}
			}


			return false;
		}


		/**
		 * The main menu.
		 *
		 * @param bool $flyout_menu Whether we want the flyout menu or not.
		 *
		 * @return false|object
		 * @since    7.67
		 * @verified 2018.02.23
		 */
		function avada_main_menu_v5_1( $flyout_menu = false ) {
			$menu_class = 'fusion-menu';
			if ( 'v7' === Avada()->settings->get( 'header_layout' ) ) {
				$menu_class .= ' fusion-middle-logo-ul';
			}

			$main_menu_args = [
				'theme_location' => apply_filters( 'lct/avada_main_menu/theme_location', 'main_navigation' ),
				'depth'          => 5,
				'menu_class'     => $menu_class,
				'items_wrap'     => '<ul role="menubar" id="%1$s" class="%2$s">%3$s</ul>',
				'fallback_cb'    => 'Avada_Nav_Walker::fallback',
				'walker'         => new Avada_Nav_Walker(),
				'container'      => false,
				'item_spacing'   => 'discard',
				'echo'           => false,
			];

			if ( $flyout_menu ) {
				$flyout_menu_args = [
					'depth'     => 1,
					'container' => false,
				];

				$main_menu_args = wp_parse_args( $flyout_menu_args, $main_menu_args );

				$main_menu = wp_nav_menu( $main_menu_args );

				return $main_menu;

			} else {
				$uber_menu_class = '';
				if ( function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) ) {
					$uber_menu_class = ' fusion-ubermenu';
				}

				echo '<nav class="fusion-main-menu' . esc_attr( $uber_menu_class ) . '" aria-label="Main Menu">';
				echo wp_nav_menu( $main_menu_args );
				echo '</nav>';

				if (
					has_nav_menu( 'sticky_navigation' ) &&
					'Top' === Avada()->settings->get( 'header_position' ) &&
					(
						! function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) ||
						(
							function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) &&
							! ubermenu_get_menu_instance_by_theme_location( 'sticky_navigation' )
						)
					)
				) {
					$sticky_menu_args = [
						'theme_location' => 'sticky_navigation',
						'menu_id'        => 'menu-main-menu-1',
						'walker'         => new Avada_Nav_Walker(),
						'item_spacing'   => 'discard',
					];

					$sticky_menu_args = wp_parse_args( $sticky_menu_args, $main_menu_args );

					echo '<nav class="fusion-main-menu fusion-sticky-menu" aria-label="Main Menu Sticky">';
					echo wp_nav_menu( $sticky_menu_args );
					echo '</nav>';
				}

				// Make sure mobile menu is not loaded when we use slideout menu or ubermenu.
				if (
					! function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) ||
					(
						function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) &&
						! ubermenu_get_menu_instance_by_theme_location( 'main_navigation' )
					)
				) {
					if (
						(
							! lct_acf_get_option_raw( 'is_header_layout_home' ) &&
							! lct_acf_get_option_raw( 'is_header_layout' )
						) ||
						in_array( Avada()->settings->get( 'header_layout' ), [ 'v4' ] )
					) {
						if (
							! lct_acf_get_option( 'sc::overlay_menu_bg' ) &&
							! lct_plugin_active( 'nks' )
						) {
							avada_mobile_main_menu();
						}
					}
				}
			}


			return false;
		}


		/**
		 * The main menu.
		 *
		 * @param bool $flyout_menu Whether we want the flyout menu or not.
		 *
		 * @return false|object
		 * @since    2018.33
		 * @verified 2018.03.22
		 */
		function avada_main_menu_v5_4( $flyout_menu = false ) {
			$menu_class = 'fusion-menu';
			if ( 'v7' === Avada()->settings->get( 'header_layout' ) ) {
				$menu_class .= ' fusion-middle-logo-ul';
			}

			$main_menu_args = [
				'theme_location' => apply_filters( 'lct/avada_main_menu/theme_location', 'main_navigation' ),
				'depth'          => 5,
				'menu_class'     => $menu_class,
				'items_wrap'     => '<ul role="menubar" id="%1$s" class="%2$s">%3$s</ul>',
				'fallback_cb'    => 'Avada_Nav_Walker::fallback',
				'walker'         => new Avada_Nav_Walker(),
				'container'      => false,
				'item_spacing'   => 'discard',
				'echo'           => false,
			];

			if ( $flyout_menu ) {
				$flyout_menu_args = [
					'depth'     => 1,
					'container' => false,
				];

				$main_menu_args = wp_parse_args( $flyout_menu_args, $main_menu_args );

				$main_menu = wp_nav_menu( $main_menu_args );

				if ( has_nav_menu( 'sticky_navigation' ) ) {
					$sticky_menu_args = [
						'theme_location' => 'sticky_navigation',
						'menu_id'        => 'menu-main-menu-1',
						'items_wrap'     => '<ul role="menubar" id="%1$s" class="%2$s">%3$s</ul>',
						'walker'         => new Avada_Nav_Walker(),
						'item_spacing'   => 'discard',
					];
					$sticky_menu_args = wp_parse_args( $sticky_menu_args, $main_menu_args );
					$main_menu        .= wp_nav_menu( $sticky_menu_args );
				}

				return $main_menu;

			} else {
				$uber_menu_class = '';
				if ( function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) ) {
					$uber_menu_class = ' fusion-ubermenu';
				}

				echo '<nav class="fusion-main-menu' . esc_attr( $uber_menu_class ) . '" aria-label="Main Menu">';
				echo wp_nav_menu( $main_menu_args );
				echo '</nav>';

				if (
					has_nav_menu( 'sticky_navigation' ) &&
					'Top' === Avada()->settings->get( 'header_position' ) &&
					(
						! function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) ||
						(
							function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) &&
							! ubermenu_get_menu_instance_by_theme_location( 'sticky_navigation' )
						)
					)
				) {
					$sticky_menu_args = [
						'theme_location' => 'sticky_navigation',
						'menu_id'        => 'menu-main-menu-1',
						'walker'         => new Avada_Nav_Walker(),
						'item_spacing'   => 'discard',
					];

					$sticky_menu_args = wp_parse_args( $sticky_menu_args, $main_menu_args );

					echo '<nav class="fusion-main-menu fusion-sticky-menu" aria-label="Main Menu Sticky">';
					echo wp_nav_menu( $sticky_menu_args );
					echo '</nav>';
				}

				// Make sure mobile menu is not loaded when we use slideout menu or ubermenu.
				if (
					! function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) ||
					(
						function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) &&
						! ubermenu_get_menu_instance_by_theme_location( 'main_navigation' )
					)
				) {
					if (
						(
							! lct_acf_get_option_raw( 'is_header_layout_home' ) &&
							! lct_acf_get_option_raw( 'is_header_layout' )
						) ||
						in_array( Avada()->settings->get( 'header_layout' ), [ 'v4' ] )
					) {
						if (
							! lct_acf_get_option( 'sc::overlay_menu_bg' ) &&
							! lct_plugin_active( 'nks' )
						) {
							if ( has_nav_menu( 'mobile_navigation' ) ) {
								$mobile_menu_args = [
									'theme_location'  => 'mobile_navigation',
									'menu_class'      => 'fusion-mobile-menu',
									'depth'           => 5,
									'walker'          => new Avada_Nav_Walker(),
									'item_spacing'    => 'discard',
									'container_class' => 'fusion-mobile-navigation',
								];
								echo wp_nav_menu( $mobile_menu_args );
							}


							avada_mobile_main_menu();
						}
					}
				}
			}


			return false;
		}
	}


	if ( ! function_exists( 'avada_mobile_main_menu' ) ) {
		/**
		 * Gets the menu-mobile-main template part.
		 */
		function avada_mobile_main_menu() {
			lct_avada_default_overrider( 'templates/menu-mobile-main' );
		}
	}


endif;


/**
 * Only if Avada older than v4.0
 */
if ( version_compare( lct_theme_version( 'Avada' ), '4.0', '<' ) ) : //Avada older than v4.0


	if (
		! function_exists( 'of_options' ) &&
		function_exists( 'of_options_array' )
	) {
		/**
		 * OVERRIDE: from /framework/admin/functions/functions.options.php
		 * We don't need this for Avada versions greater than v4.0
		 *
		 * @since    5.37
		 * @verified 2017.08.25
		 */
		function of_options() {
			global $of_options;


			if ( empty( $of_options ) )
				$of_options = of_options_array();
		}
	}


endif;
