<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * The default method for checking for the right file
 *
 * @param $template
 *
 * @since    2017.11
 * @verified 2019.09.11
 */
function lct_avada_default_overrider( $template )
{
	//Use the theme file if it exists
	if ( file_exists( lct_path_theme( '/' . $template . '.php' ) ) ) {
		get_template_part( $template );


		//Or filter through our template router
	} elseif ( $located = lct_get_template_part( lct_avada_template_version_router( $template ) ) ) {
		load_template( $located, false );


		//Or just use the WP one
	} else {
		get_template_part( $template );
	}
}


/**
 * Route to a template file based on the version of Avada running
 *
 * @param $template
 *
 * @return string
 * @since    2019.25
 * @verified 2024.09.11
 */
function lct_avada_template_version_router( $template )
{
	$version = lct_current_theme_minor_version();


	switch ( $template ) {
		case 'templates/header-1':
			switch ( $version ) {
				case 5:
				case 5.1:
					$piece = 'v1';
					break;


				case 5.4:
					$piece = 'v2';
					break;


				case 5.5:
				case 5.6:
				case 5.7:
				case 5.8:
				case 5.9:
				case 6:
				case 6.1:
				case 6.2:
				case 7.5:
				case 7.6:
				case 7.7:
				case 7.8:
				case 7.9:
				case 7.10:
				case 7.11:
					$piece = 'v3';
					break;


				default:
					$piece = 'v3';
			}
			break;


		case 'templates/header-2':
			switch ( $version ) {
				case 5:
				case 5.1:
					$piece = 'v1';
					break;


				case 5.4:
					$piece = 'v2';
					break;


				case 5.5:
				case 5.6:
				case 5.7:
				case 5.8:
				case 5.9:
				case 6:
				case 6.1:
				case 6.2:
				case 7.5:
				case 7.6:
				case 7.7:
				case 7.8:
				case 7.9:
				case 7.10:
				case 7.11:
					$template = 'templates/header-1';
					$piece    = 'v3';
					break;


				default:
					$template = 'templates/header-1';
					$piece    = 'v3';
			}
			break;


		case 'templates/header-3':
		case 'templates/header-4':
			switch ( $version ) {
				case 5:
					$piece = 'v1';
					break;


				case 5.1:
					$piece = 'v2';
					break;


				case 5.4:
					$piece = 'v3';
					break;


				case 5.5:
				case 5.6:
				case 5.7:
				case 5.8:
				case 5.9:
				case 6:
				case 6.1:
				case 6.2:
				case 7.5:
				case 7.6:
				case 7.7:
				case 7.8:
				case 7.9:
				case 7.10:
				case 7.11:
					$template = 'templates/header-1';
					$piece    = 'v3';
					break;


				default:
					$template = 'templates/header-1';
					$piece    = 'v3';
			}
			break;


		case 'templates/header-v1':
		case 'templates/header-v2':
		case 'templates/header-v3':
			$template = 'templates/header-v1';


			switch ( $version ) {
				case 5.5:
				case 5.6:
				case 5.7:
					$piece = 'v1';
					break;


				case 5.8:
				case 5.9:
				case 6:
				case 6.1:
				case 6.2:
				case 7.5:
				case 7.6:
				case 7.7:
				case 7.8:
				case 7.9:
				case 7.10:
				case 7.11:
					$piece = 'v2';
					break;


				default:
					$piece = 'v2';
			}
			break;


		case 'templates/header-v4':
		case 'templates/header-v5':
			$template = 'templates/header-v4';


			switch ( $version ) {
				case 5.5:
				case 5.6:
				case 5.7:
					$piece = 'v1';
					break;


				case 5.8:
				case 5.9:
				case 6:
				case 6.1:
				case 6.2:
				case 7.5:
				case 7.6:
				case 7.7:
				case 7.8:
				case 7.9:
				case 7.10:
				case 7.11:
					$piece = 'v2';
					break;


				default:
					$piece = 'v2';
			}
			break;


		case 'templates/header-v6':
			switch ( $version ) {
				case 5.5:
				case 5.6:
				case 5.7:
					$piece = 'v1';
					break;


				case 5.8:
				case 5.9:
					$piece = 'v2';
					break;


				case 6:
				case 6.1:
				case 6.2:
					$piece = 'v3';
					break;


				case 7.5:
				case 7.6:
				case 7.7:
					$piece = 'v4';
					break;


				case 7.8:
				case 7.9:
				case 7.10:
				case 7.11:
					$piece = 'v5';
					break;


				default:
					$piece = 'v5';
			}
			break;


		case 'templates/header-v7':
			switch ( $version ) {
				case 5.5:
				case 5.6:
				case 5.7:
					$piece = 'v1';
					break;


				case 5.8:
				case 5.9:
				case 6:
				case 6.1:
				case 6.2:
				case 7.5:
				case 7.6:
				case 7.7:
				case 7.8:
				case 7.9:
				case 7.10:
				case 7.11:
					$piece = 'v2';
					break;


				default:
					$piece = 'v2';
			}
			break;


		case 'templates/menu-mobile-main':
			switch ( $version ) {
				case 5.4:
					$piece = 'v1';
					break;


				case 5.5:
					$piece = 'v2';
					break;


				case 5.6:
					$piece = 'v3';
					break;


				case 5.7:
					$piece = 'v4';
					break;


				case 5.8:
					$piece = 'v5';
					break;


				case 5.9:
					$piece = 'v6';
					break;


				case 6:
				case 6.1:
				case 6.2:
				case 7.5:
				case 7.6:
				case 7.7:
					$piece = 'v7';
					break;


				case 7.8:
				case 7.9:
				case 7.10:
				case 7.11:
					$piece = 'v8';
					break;


				default:
					$piece = 'v8';
			}
			break;


		case 'templates/side-header':
			switch ( $version ) {
				case 5.0:
				case 5.1:
					$piece = 'v1';
					break;


				case 5.4:
				case 5.5:
				case 5.6:
					$piece = 'v2';
					break;


				case 5.7:
				case 5.8:
				case 5.9:
					$piece = 'v3';
					break;


				case 6:
				case 6.1:
				case 6.2:
				case 7.5:
				case 7.6:
				case 7.7:
				case 7.8:
				case 7.9:
				case 7.10:
				case 7.11:
					$piece = 'v4';
					break;


				default:
					$piece = 'v4';
			}
			break;


		default:
			$piece = '';
	}


	if ( $piece ) {
		$template .= '-' . $piece;
	}


	return $template;
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
		function avada_logo()
		{
			// No need to proceed any further if no logo is set.
			if (
				'' === Avada()->settings->get( 'logo' )
				&& '' === Avada()->settings->get( 'logo_retina' )
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
		function avada_header_1()
		{
			if ( ! in_array( Avada()->settings->get( 'header_layout' ), [ 'v1', 'v2', 'v3' ] ) ) {
				return;
			}


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
		function avada_header_2()
		{
			if ( ! in_array( Avada()->settings->get( 'header_layout' ), [ 'v4', 'v5' ] ) ) {
				return;
			}


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
		function avada_header_3()
		{
			if ( 'v6' !== Avada()->settings->get( 'header_layout' ) ) {
				return;
			}


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
		function avada_header_4()
		{
			if ( 'v7' !== Avada()->settings->get( 'header_layout' ) ) {
				return;
			}


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
		function avada_side_header()
		{
			$queried_object_id = get_queried_object_id();


			if (
				! is_page_template( 'blank.php' )
				&& 'no' != get_post_meta( $queried_object_id, 'pyre_display_header', true )
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
		 * @verified 2020.04.28
		 */
		function avada_main_menu( $flyout_menu = false )
		{
			/**
			 * Only if Avada is v5.4 and newer
			 */
			if ( version_compare( lct_theme_version( 'Avada' ), '5.4', '>=' ) ) { //Avada is v5.1 and newer
				avada_main_menu_v5_4( $flyout_menu );


				/**
				 * Only if Avada is v5.1 and newer
				 */
			} elseif ( version_compare( lct_theme_version( 'Avada' ), '5.1', '>=' ) ) { //Avada is v5.1 and newer
				avada_main_menu_v5_1( $flyout_menu );


				/**
				 * Everything else
				 */
			} else {
				avada_main_menu_v5( $flyout_menu );
			}


			/**
			 * @date     0.0
			 * @since    2020.7
			 * @verified 2021.08.30
			 */
			do_action( 'lct/avada_main_menu' );
		}


		/**
		 * The main menu.
		 *
		 * @param bool $flyout_menu Whether we want the flyout menu or not.
		 *
		 * @return void|string|false
		 * @since    7.67
		 * @verified 2020.01.16
		 */
		function avada_main_menu_v5( $flyout_menu = false )
		{
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

				return wp_nav_menu( $main_menu_args );
			} else {
				wp_nav_menu( $main_menu_args );

				if (
					has_nav_menu( 'sticky_navigation' )
					&& 'Top' == Avada()->settings->get( 'header_position' )
					&& (
						! function_exists( 'ubermenu_get_menu_instance_by_theme_location' )
						|| (
							function_exists( 'ubermenu_get_menu_instance_by_theme_location' )
							&& ! ubermenu_get_menu_instance_by_theme_location( 'sticky_navigation' )
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
					! function_exists( 'ubermenu_get_menu_instance_by_theme_location' )
					|| (
						function_exists( 'ubermenu_get_menu_instance_by_theme_location' )
						&& ! ubermenu_get_menu_instance_by_theme_location( 'main_navigation' )
					)
				) {
					if (
						(
							! lct_acf_get_option_raw( 'is_header_layout_home' )
							&& ! lct_acf_get_option_raw( 'is_header_layout' )
						)
						|| Avada()->settings->get( 'header_layout' ) === 'v4'
					) {
						if (
							! lct_acf_get_option( 'sc::use_overlay_menu' )
							&& ! lct_plugin_active( 'nks' )
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
		 * @return void|string|false
		 * @since    7.67
		 * @verified 2020.01.16
		 */
		function avada_main_menu_v5_1( $flyout_menu = false )
		{
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

				return wp_nav_menu( $main_menu_args );
			} else {
				$uber_menu_class = '';
				if ( function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) ) {
					$uber_menu_class = ' fusion-ubermenu';
				}

				echo '<nav class="fusion-main-menu' . esc_attr( $uber_menu_class ) . '" aria-label="Main Menu">';
				echo wp_nav_menu( $main_menu_args );
				echo '</nav>';

				if (
					has_nav_menu( 'sticky_navigation' )
					&& 'Top' === Avada()->settings->get( 'header_position' )
					&& (
						! function_exists( 'ubermenu_get_menu_instance_by_theme_location' )
						|| (
							function_exists( 'ubermenu_get_menu_instance_by_theme_location' )
							&& ! ubermenu_get_menu_instance_by_theme_location( 'sticky_navigation' )
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
					! function_exists( 'ubermenu_get_menu_instance_by_theme_location' )
					|| (
						function_exists( 'ubermenu_get_menu_instance_by_theme_location' )
						&& ! ubermenu_get_menu_instance_by_theme_location( 'main_navigation' )
					)
				) {
					if (
						(
							! lct_acf_get_option_raw( 'is_header_layout_home' )
							&& ! lct_acf_get_option_raw( 'is_header_layout' )
						)
						|| Avada()->settings->get( 'header_layout' ) === 'v4'
					) {
						if (
							! lct_acf_get_option( 'sc::use_overlay_menu' )
							&& ! lct_plugin_active( 'nks' )
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
		 * @return void|string|false
		 * @since    2018.33
		 * @verified 2020.01.16
		 */
		function avada_main_menu_v5_4( $flyout_menu = false )
		{
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
					has_nav_menu( 'sticky_navigation' )
					&& 'Top' === Avada()->settings->get( 'header_position' )
					&& (
						! function_exists( 'ubermenu_get_menu_instance_by_theme_location' )
						|| (
							function_exists( 'ubermenu_get_menu_instance_by_theme_location' )
							&& ! ubermenu_get_menu_instance_by_theme_location( 'sticky_navigation' )
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
					! function_exists( 'ubermenu_get_menu_instance_by_theme_location' )
					|| (
						function_exists( 'ubermenu_get_menu_instance_by_theme_location' )
						&& ! ubermenu_get_menu_instance_by_theme_location( 'main_navigation' )
					)
				) {
					if (
						(
							! lct_acf_get_option_raw( 'is_header_layout_home' )
							&& ! lct_acf_get_option_raw( 'is_header_layout' )
						)
						|| Avada()->settings->get( 'header_layout' ) === 'v4'
					) {
						if (
							! lct_acf_get_option( 'sc::use_overlay_menu' )
							&& ! lct_plugin_active( 'nks' )
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
		function avada_mobile_main_menu()
		{
			lct_avada_default_overrider( 'templates/menu-mobile-main' );
		}
	}


endif;


/**
 * Only if Avada older than v4.0
 */
if ( version_compare( lct_theme_version( 'Avada' ), '4.0', '<' ) ) : //Avada older than v4.0


	if (
		! function_exists( 'of_options' )
		&& function_exists( 'of_options_array' )
	) {
		/**
		 * OVERRIDE: from /framework/admin/functions/functions.options.php
		 * We don't need this for Avada versions greater than v4.0
		 *
		 * @since    5.37
		 * @verified 2017.08.25
		 */
		function of_options()
		{
			global $of_options;


			if ( empty( $of_options ) ) {
				$of_options = of_options_array();
			}
		}
	}


endif;
