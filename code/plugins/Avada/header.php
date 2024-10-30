<?php
/**
 * @noinspection PhpMissingFieldTypeInspection
 */
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.01.06
 */
class lct_Avada_header
{
	public $sc;


	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.01.06
	 */
	function __construct( $args = [] )
	{
		//Store $args
		$this->args = $args;


		//Store parent class, maybe
		if ( $this->args['load_parent'] ) {
			$this->zxzp = lct();
		}


		$this->sc = 'sc::';


		//Setup WordPress action and filter hooks
		$this->load_hooks();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    7.62
	 * @verified 2017.01.06
	 */
	function load_hooks()
	{
		//bail early if already ran
		if ( lct_did() ) {
			return;
		}


		/**
		 * everytime
		 */


		if ( lct_frontend() ) {
			/**
			 * Shortcodes
			 */
			add_shortcode( zxzu( 'avada_logo' ), [ $this, 'avada_logo' ] );


			add_shortcode( zxzu( 'avada_logo_mobile' ), [ $this, 'avada_logo_mobile' ] );


			add_shortcode( zxzu( 'avada_main_menu' ), [ $this, 'avada_main_menu' ] );


			add_shortcode( zxzu( 'menu_mobile' ), [ $this, 'menu_mobile' ] );


			add_shortcode( zxzu( 'mobi_menu_button' ), [ $this, 'mobi_menu_button' ] );


			add_shortcode( zxzu( 'mobi_menu_button_js_only' ), [ $this, 'mobi_menu_button_js_only' ] );


			add_shortcode( zxzu( 'mobi_slide_menu_button' ), [ $this, 'mobi_slide_menu_button' ] );


			add_shortcode( zxzu( 'mobi_overlay_menu_button' ), [ $this, 'mobi_overlay_menu_button' ] );


			add_shortcode( zxzu( 'mobi_flyout_menu_button' ), [ $this, 'mobi_flyout_menu_button' ] );


			add_shortcode( zxzu( 'avada_mobile_main_menu' ), [ $this, 'avada_mobile_main_menu' ] );


			/**
			 * Actions
			 */
			add_action( 'avada_after_header_wrapper', [ $this, 'avada_after_header_wrapper' ] );

			add_action( 'wp_footer', [ $this, 'bottom_mobile_menu_wrapper' ], 1 );
		}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * [lct_avada_logo]
	 * Get avada_logo() using a shortcode
	 *
	 * @return string
	 * @since    7.62
	 * @verified 2017.01.06
	 */
	function avada_logo()
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r' => '',
			],
			[]
		);


		ob_start();
		avada_logo();
		$a['r'] = ob_get_clean();


		return $a['r'];
	}


	/**
	 * [lct_avada_logo_mobile]
	 * Get avada_logo() using a shortcode
	 *
	 * @att      string float
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    7.62
	 * @verified 2017.01.06
	 */
	function avada_logo_mobile( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => '',
				'float' => 'none',
			],
			$a
		);


		ob_start();


		if ( Avada()->settings->get( 'mobile_logo', 'url' ) ) { ?>
			<?php
			$logo_url         = Fusion_Sanitize::get_url_with_correct_scheme( Avada()->settings->get( 'mobile_logo', 'url' ) );
			$mobile_logo_data = [
				'url'    => $logo_url,
				'width'  => '',
				'height' => '',
			];
			?>
			<a href="/" style="float:<?php esc_attr_e( $a['float'] ); ?>"><img src="<?php echo esc_url_raw( $mobile_logo_data['url'] ); ?>" width="<?php echo esc_attr( $mobile_logo_data['width'] ); ?>" height="<?php echo esc_attr( $mobile_logo_data['height'] ); ?>" alt="<?php bloginfo( 'name' ); ?> <?php esc_attr_e( 'Mobile Logo', 'Avada' ); ?>" class="fusion-logo-1x fusion-mobile-logo-1x"/></a>
		<?php }


		$a['r'] = ob_get_clean();


		return $a['r'];
	}


	/**
	 * [lct_avada_main_menu]
	 * Get avada_main_menu using a shortcode
	 *
	 * @return string
	 * @since    7.62
	 * @verified 2017.01.06
	 */
	function avada_main_menu()
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r' => '',
			],
			[]
		);


		ob_start();
		avada_main_menu();
		$a['r'] = ob_get_clean();


		return $a['r'];
	}


	/**
	 * [lct_menu_mobile]
	 * Get menu-mobile-modern template using a shortcode
	 *
	 * @return string
	 * @since    7.62
	 * @verified 2017.01.06
	 */
	function menu_mobile()
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r' => '',
			],
			[]
		);


		ob_start();
		get_template_part( 'templates/menu-mobile-modern' );
		$a['r'] = ob_get_clean();


		return $a['r'];
	}


	/**
	 * [lct_mobi_menu_button]
	 * Get menu-mobile-modern using a shortcode
	 *
	 * @att      string class
	 * @att      string float
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    7.62
	 * @verified 2019.08.22
	 */
	function mobi_menu_button( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => [],
				'class' => '',
				'float' => '',
			],
			$a
		);


		/**
		 * class
		 */
		if ( $a['class'] ) {
			$a['class'] = ' ' . $a['class'];
		}


		/**
		 * float
		 */
		if ( $a['float'] ) {
			$a['class'] .= ' ' . zxzu( 'mobi_float_' . $a['float'] );
		}


		/**
		 * $link
		 */
		$link = '#';


		/**
		 * $button_text
		 */
		$button_text = "<i class='fa fa-bars'></i><br class=\"" . lct_acf_get_menu_button_class() . "\"/><span class=\"" . lct_acf_get_menu_button_class() . "\">Menu</span>";


		$a['r'][] = do_shortcode( "[fusion_button link=\"{$link}\" target=\"_self\" hide_on_mobile=\"small-visibility,medium-visibility,large-visibility\" color=\"custom\" " . lct_acf_get_mobi_nav_colors() . " border_width=\"0\" stretch=\"default\" icon_position=\"left\" icon_divider=\"no\" animation_direction=\"left\" animation_speed=\"0.3\" class=\"" . zxzu( 'mobi_button' ) . " " . zxzu( 'mobi_menu_button' ) . "{$a['class']}\"]{$button_text}[/fusion_button]" );


		$a['r'][] = $this->mobi_menu_button_js_only();


		return lct_return( $a['r'] );
	}


	/**
	 * [lct_mobi_menu_button_js_only]
	 * Get menu-mobile-modern using a shortcode
	 *
	 * @return string
	 * @since    2017.34
	 * @verified 2017.06.19
	 */
	function mobi_menu_button_js_only()
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r' => '',
			],
			[]
		);


		$a['r'] = "<script>
			jQuery(document).ready( function() {
				jQuery('." . zxzu( 'mobi_menu_button' ) . "').click( function( e ) {
					e.preventDefault();

					lct_check_mobile_nav_holder_visible();

					/**
					 * Scroll to top
					 */
					if( !lct_c_vars.is_mobile_nav_holder_visible )
						window.scrollTo( 0, 0 );

					jQuery('.fusion-mobile-menu-icons a').click();
				});
			});
		</script>";


		return $a['r'];
	}


	/**
	 * [lct_mobi_slide_menu_button]
	 * Get menu-mobile-modern using a shortcode
	 *
	 * @att      string class
	 * @att      string float
	 * @att      mixed image
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2017.48
	 * @verified 2019.08.22
	 */
	function mobi_slide_menu_button( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => [],
				'class' => '',
				'float' => '',
				'image' => '',
			],
			$a
		);


		/**
		 * class
		 */
		if ( $a['class'] ) {
			$a['class'] = ' ' . $a['class'];
		}


		/**
		 * float
		 */
		if ( $a['float'] ) {
			$a['class'] .= ' ' . zxzu( 'mobi_float_' . $a['float'] );
		}


		/**
		 * $link
		 */
		$link = '#';


		/**
		 * $button_text
		 */
		if ( $a['image'] ) {
			$default_image = filter_var( $a['image'], FILTER_VALIDATE_BOOLEAN );


			if ( $default_image ) {
				$url = lct_get_root_url( 'assets/images/Icon-Menu-H135.png' );
			} else {
				$url = $default_image;
			}


			$button_text = sprintf( '<img src="%s" alt="" />', $url );
		} else {
			$button_text = "<i class='fa fa-bars'></i><br class=\"" . lct_acf_get_menu_button_class() . "\"/><span class=\"" . lct_acf_get_menu_button_class() . "\">Menu</span>";
		}


		$a['r'][] = do_shortcode( "[fusion_button link=\"{$link}\" target=\"_self\" hide_on_mobile=\"small-visibility,medium-visibility,large-visibility\" color=\"custom\" " . lct_acf_get_mobi_nav_colors() . " border_width=\"0\" stretch=\"default\" icon_position=\"left\" icon_divider=\"no\" animation_direction=\"left\" animation_speed=\"0.3\" class=\"" . zxzu( 'mobi_button' ) . " " . zxzu( 'mobi_slide_menu_button' ) . "{$a['class']}\"]{$button_text}[/fusion_button]" );


		return lct_return( $a['r'] );
	}


	/**
	 * Get the field for a shortcode
	 *
	 * @param $str
	 *
	 * @return mixed
	 * @since    2018.18
	 * @verified 2018.02.23
	 */
	function sc( $str = '' )
	{
		return lct_acf_get_option( $this->sc . strval( $str ) );
	}


	/**
	 * [lct_mobi_overlay_menu_button]
	 * Create a menu button that when clicked reveals a full overlay main menu of the site
	 *
	 * @att      string class
	 * @att      string float
	 * @att      mixed image
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2018.18
	 * @verified 2019.11.20
	 */
	function mobi_overlay_menu_button( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => [],
				'class' => '',
				'float' => '',
				'image' => '',
			],
			$a
		);


		if ( ! $this->sc( 'use_overlay_menu' ) ) {
			return lct_return( $a['r'] );
		}


		/**
		 * class
		 */
		if ( $a['class'] ) {
			$a['class'] = ' ' . $a['class'];
		}


		/**
		 * float
		 */
		if ( $a['float'] ) {
			$a['class'] .= ' ' . zxzu( 'mobi_float_' . $a['float'] );
		}


		/**
		 * $link
		 */
		$link = '#';


		/**
		 * $button_text
		 */
		if ( $a['image'] ) {
			$default_image = filter_var( $a['image'], FILTER_VALIDATE_BOOLEAN );


			if ( $default_image === true ) {
				$url = lct_get_root_url( 'assets/images/Icon-Menu-H135.png' );
			} else {
				$url = $a['image'];
			}


			$alt         = 'Menu';
			$button_text = sprintf( '<img src="%s" alt="%s"/>', $url, $alt );
		} else {
			$button_text = "<i class='fa fa-bars'></i><br class=\"" . lct_acf_get_menu_button_class() . "\"/><span class=\"" . lct_acf_get_menu_button_class() . "\">Menu</span>";
		}


		/**
		 * CSS
		 */
		$color_bg = '#333333';
		if ( $tmp = $this->sc( 'overlay_menu_bg' ) ) {
			$color_bg = $tmp;
		} elseif ( $tmp = lct_acf_get_option( 'mobi_nav_bar_bg_color' ) ) {
			$color_bg = $tmp;
		}

		if (
			( $tmp = $this->sc( 'overlay_menu_bg_opacity' ) )
			&& $tmp < 1
		) {
			[ $r, $g, $b ] = sscanf( $color_bg, "#%02x%02x%02x" );
			$color_bg = sprintf( 'rgba( %d, %d, %d, %s )', $r, $g, $b, $tmp );
		}


		$color_text = '#FFFFFF';
		if ( $tmp = $this->sc( 'overlay_menu_color' ) ) {
			$color_text = $tmp;
		} elseif ( $tmp = lct_acf_get_option( 'mobi_nav_bar_color' ) ) {
			$color_text = $tmp;
		}


		$color_hover = '#777777';
		if ( $tmp = $this->sc( 'overlay_menu_color_hover' ) ) {
			$color_hover = $tmp;
		} elseif ( $tmp = lct_acf_get_option( 'mobi_nav_bar_bg_color' ) ) {
			$color_hover = $tmp;
		}


		$style    = [];
		$style[]  = '.lct_overlay_menu{
			background: rgba(0, 0, 0, 0.9);
			background: ' . $color_bg . ';
		}';
		$style[]  = '.lct_overlay_menu .close{
			color:       ' . $color_text . ';
		}';
		$style[]  = '.lct_overlay_menu .close span,
		.lct_overlay_menu .close span:before,
		.lct_overlay_menu .close span:after{
			background:    ' . $color_text . ';
		}';
		$style[]  = '.lct_overlay_menu .fusion-mobile-current-nav-item > a,
		.lct_overlay_menu .fusion-mobile-nav-item a{
			background-color: transparent;
			color:            ' . $color_text . ';
		}';
		$style[]  = '.lct_overlay_menu .fusion-mobile-nav-item a:hover{
			background-color: ' . $color_hover . ';
			color:            ' . $color_text . ';
		}';
		$style[]  = '.lct_overlay_menu .fusion-mobile-nav-item .fusion-open-submenu,
		.lct_overlay_menu .fusion-mobile-nav-item a:before{
			color: ' . $color_text . ' !important;
		}';
		$a['r'][] = sprintf( '<style>@media screen and (max-width: 1024px){ %s }</style>', lct_return( $style ) );


		$a['r'][] = "[fusion_button link=\"{$link}\" target=\"_self\" hide_on_mobile=\"small-visibility,medium-visibility,large-visibility\" color=\"custom\" " . lct_acf_get_mobi_nav_colors() . " border_width=\"0\" stretch=\"default\" icon_position=\"left\" icon_divider=\"no\" animation_direction=\"left\" animation_speed=\"0.3\" class=\"" . zxzu( 'mobi_button' ) . " " . zxzu( 'mobi_overlay_menu_button' ) . "{$a['class']}\"]{$button_text}[/fusion_button]";


		$a['r'][] = '<aside class="lct_overlay_menu">';
		$a['r'][] = '<div class="outer-close toggle-overlay"><a class="close"><span></span></a></div>';
		$a['r'][] = $this->sc( 'overlay_menu_before' );
		$a['r'][] = $this->avada_mobile_main_menu();
		$a['r'][] = $this->sc( 'overlay_menu_after' );
		$a['r'][] = '</aside>';


		return do_shortcode( lct_return( $a['r'] ) );
	}


	/**
	 * [lct_mobi_flyout_menu_button]
	 * Create a menu button that when clicked reveals a full Avada flyout main menu of the site
	 *
	 * @att      string class
	 * @att      string float
	 * @att      mixed image
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2019.23
	 * @verified 2019.10.24
	 */
	function mobi_flyout_menu_button( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => [],
				'class' => '',
				'float' => '',
				'image' => '',
			],
			$a
		);


		/**
		 * class
		 */
		if ( $a['class'] ) {
			$a['class'] = ' ' . $a['class'];
		}


		/**
		 * float
		 */
		if ( $a['float'] ) {
			$a['class'] .= ' ' . zxzu( 'mobi_float_' . $a['float'] );
		}


		/**
		 * $link
		 */
		$link = '#';


		/**
		 * $button_text
		 */
		if ( $a['image'] ) {
			$default_image = filter_var( $a['image'], FILTER_VALIDATE_BOOLEAN );


			if ( $default_image === true ) {
				$url = lct_get_root_url( 'assets/images/Icon-Menu-H135.png' );
			} else {
				$url = $a['image'];
			}


			$alt         = 'Menu';
			$button_text = sprintf( '<img src="%s" alt="%s"/>', $url, $alt );
		} else {
			$button_text = "<i class='fa fa-bars'></i><br class=\"" . lct_acf_get_menu_button_class() . "\"/><span class=\"" . lct_acf_get_menu_button_class() . "\">Menu</span>";
		}


		$a['r'][] = "<div class=\"fusion-flyout-menu-icons fusion-flyout-mobile-menu-icons\">[fusion_button link=\"{$link}\" target=\"_self\" hide_on_mobile=\"small-visibility,medium-visibility,large-visibility\" color=\"custom\" " . lct_acf_get_mobi_nav_colors() . " border_width=\"0\" stretch=\"default\" icon_position=\"left\" icon_divider=\"no\" animation_direction=\"left\" animation_speed=\"0.3\" class=\"fusion-flyout-menu-toggle " . zxzu( 'mobi_button' ) . " " . zxzu( 'mobi_overlay_menu_button' ) . "{$a['class']}\"]{$button_text}[/fusion_button]</div>";


		return do_shortcode( lct_return( $a['r'] ) );
	}


	/**
	 * [lct_avada_mobile_main_menu]
	 * Get mobile menu using a shortcode
	 *
	 * @return string
	 * @since    2017.48
	 * @verified 2017.06.22
	 */
	function avada_mobile_main_menu()
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r' => '',
			],
			[]
		);


		ob_start();


		avada_mobile_main_menu();


		$a['r'] = lct_strip_n_r_t( ob_get_clean() );


		return $a['r'];
	}


	/**
	 * Get the header layout content
	 *
	 * @since        7.62
	 * @verified     2022.10.13
	 * @noinspection PhpStatementHasEmptyBodyInspection
	 */
	function header_layout()
	{
		$used            = false;
		$header_position = ucwords( Avada()->settings->get( 'header_position' ) );


		// Only load the homepage stuff if we are loading it
		if ( is_front_page() ) {
			if ( lct_acf_get_option_raw( 'is_header_layout_home' ) ) {
				echo '<div class="' . zxzu( 'header' ) . '">';
				echo lct_theme_chunk( [ 'id' => lct_acf_get_option_raw( 'header_layout_home' ) ] );
				echo '</div>';


				if ( $header_position !== 'Top' ) { //side menu


					if (
						! $this->sc( 'use_overlay_menu' )
						&& ! lct_plugin_active( 'nks' )
					) {
						avada_mobile_main_menu();
					}


				} elseif ( in_array( Avada()->settings->get( 'header_layout' ), [ 'v1', 'v2', 'v3' ] ) ) {
					//TODO: cs - work on v2 - 01/03/2017 10:40 AM
					//TODO: cs - work on v3 - 01/03/2017 10:40 AM


					if (
						! $this->sc( 'use_overlay_menu' )
						&& ! lct_plugin_active( 'nks' )
					) {
						avada_mobile_main_menu();
					}


				} elseif ( in_array( Avada()->settings->get( 'header_layout' ), [ 'v4', 'v5' ] ) ) {
					//TODO: cs - work on v5 - 01/03/2017 10:40 AM


					//Nothing needed for v4


				} elseif ( Avada()->settings->get( 'header_layout' ) == 'v6' ) {
					//TODO: cs - work on v6 - 01/03/2017 10:40 AM


				} elseif ( Avada()->settings->get( 'header_layout' ) == 'v7' ) {
					//Nothing needed for v7


				}


				$used = true;
			}
		} else {
			if ( lct_acf_get_option_raw( 'is_header_layout' ) ) {
				echo '<div class="' . zxzu( 'header' ) . '">';
				echo lct_theme_chunk( [ 'id' => lct_acf_get_option_raw( 'header_layout' ) ] );
				echo '</div>';


				if ( $header_position !== 'Top' ) { //side menu


					if (
						! $this->sc( 'use_overlay_menu' )
						&& ! lct_plugin_active( 'nks' )
					) {
						avada_mobile_main_menu();
					}


				} elseif ( in_array( Avada()->settings->get( 'header_layout' ), [ 'v1', 'v2', 'v3' ] ) ) {
					//TODO: cs - work on v2 - 01/03/2017 10:40 AM
					//TODO: cs - work on v3 - 01/03/2017 10:40 AM


					if (
						! $this->sc( 'use_overlay_menu' )
						&& ! lct_plugin_active( 'nks' )
					) {
						avada_mobile_main_menu();
					}


				} elseif ( in_array( Avada()->settings->get( 'header_layout' ), [ 'v4', 'v5' ] ) ) {
					//TODO: cs - work on v5 - 01/03/2017 10:40 AM


					//Nothing needed for v4


				} elseif ( Avada()->settings->get( 'header_layout' ) == 'v6' ) {
					//TODO: cs - work on v6 - 01/03/2017 10:40 AM


				} elseif ( Avada()->settings->get( 'header_layout' ) == 'v7' ) {
					//Nothing needed for v7


				}


				$used = true;
			}
		}


		if (
			(
				is_front_page()
				&& lct_acf_get_option_raw( 'is_bottom_mobile_menu_layout_home' )
			)
			|| (
				! is_front_page()
				&& lct_acf_get_option_raw( 'is_bottom_mobile_menu_layout' )
			)
		) {
			echo "<style>";


			echo '.' . zxzu( 'bottom_mobile_menu_wrapper' ) . '{
					position: fixed !important;
					bottom:   0 !important;
					width:    100% !important;
					z-index:  99998;
				}';


			if ( ! defined( 'LCT_DISABLE_AVADA_HEADER_LAYOUT' ) ) {
				if ( $tmp = lct_acf_get_field_option( 'mobi_nav_bar_bg_color' ) ) {
					echo '.' . zxzu( 'mobi_nav_bar' ) . ',
				.' . zxzu( 'mobi_nav_bar' ) . ' .' . zxzu( 'mobi_button' ) . '{
					background-color: ' . $tmp . ' !important;
				}';
				}

				if ( $tmp = lct_acf_get_field_option( 'mobi_nav_bar_bg_color_hover' ) ) {
					echo '.' . zxzu( 'mobi_nav_bar' ) . ' .' . zxzu( 'mobi_button' ) . ':hover{
					background-color: ' . $tmp . ' !important;
				}';
				}

				if ( $tmp = lct_acf_get_field_option( 'mobi_nav_bar_color' ) ) {
					echo '.' . zxzu( 'mobi_nav_bar' ) . ' .' . zxzu( 'mobi_button' ) . '{
					color: ' . $tmp . ' !important;
				}';
				}

				if ( $tmp = lct_acf_get_field_option( 'mobi_nav_bar_color_hover' ) ) {
					echo '.' . zxzu( 'mobi_nav_bar' ) . ' .' . zxzu( 'mobi_button' ) . ':hover{
					color: ' . $tmp . ' !important;
				}';
				}
			}


			echo "</style>";
		}


		if ( $used ) {
			/**
			 * CSS
			 */
			echo "<style>";


			if ( $tmp = lct_acf_get_field_option( 'mobi_nav_bar_bg_color' ) ) {
				echo '.' . zxzu( 'mobi_nav_bar' ) . ',
				.' . zxzu( 'mobi_nav_bar' ) . ' .' . zxzu( 'mobi_button' ) . '{
					background-color: ' . $tmp . ' !important;
				}';
			}

			if ( $tmp = lct_acf_get_field_option( 'mobi_nav_bar_bg_color_hover' ) ) {
				echo '.' . zxzu( 'mobi_nav_bar' ) . ' .' . zxzu( 'mobi_button' ) . ':hover{
					background-color: ' . $tmp . ' !important;
				}';
			}

			if ( $tmp = lct_acf_get_field_option( 'mobi_nav_bar_color' ) ) {
				echo '.' . zxzu( 'mobi_nav_bar' ) . ' .' . zxzu( 'mobi_button' ) . '{
					color: ' . $tmp . ' !important;
				}';
			}

			if ( $tmp = lct_acf_get_field_option( 'mobi_nav_bar_color_hover' ) ) {
				echo '.' . zxzu( 'mobi_nav_bar' ) . ' .' . zxzu( 'mobi_button' ) . ':hover{
					color: ' . $tmp . ' !important;
				}';
			}


			if (
				! ( $tmp = fusion_get_option( 'header_bg_color' ) )
				|| substr_count( $tmp, ',' ) < 3
			) {
				echo ".fusion-header-v1{
					background-color: " . $tmp . ";
				}";
			}


			echo ".fusion-header p{
				margin: 0;
			}


			body .fusion-header-v4 .fusion-header{
				padding-top:    0;
				padding-bottom: 0;
			}


			.fusion-mobile-menu-icons{
				display: none !important;
			}


			#side-header.fusion-is-sticky{
				padding-top: 5px !important;
				padding-bottom: 5px !important;
			}


			@media (max-width: " . lct_get_mobile_threshold() . "px){
				/* STARTzz */
				.fusion-mobile-menu-design-modern#side-header .fusion-mobile-nav-holder > ul{
					display: inherit;
				}


				body .fusion-mobile-menu-design-modern.fusion-header-v1 .fusion-header{
					padding-top:    0;
					padding-bottom: 0;
					padding-left:   0;
					padding-right:  0 !important;
				}


				.fusion-mobile-menu-design-modern.fusion-header-v1 .fusion-mobile-nav-holder{
					padding-top: 0;
					margin-right: 0;
				}


				.fusion-mobile-menu-design-modern.fusion-header-v1 .fusion-mobile-nav-holder[style*=\"display: block;\"]{
					margin-bottom: 0;
				}


				body .fusion-mobile-menu-design-modern.fusion-header-v3 .fusion-header{
					padding-top:    0;
					padding-bottom: 0;
					padding-left:   0;
					padding-right:  0;
				}


				body .fusion-mobile-menu-design-modern.fusion-header-v4 .fusion-header{
					padding-top:    0;
					padding-bottom: 0;
					padding-left:   0;
					padding-right:  0;
				}
				/* ENDzz */
			}";


			echo "</style>";
		} else {
			if ( $header_position !== 'Top' ) { //side menu


				$mobile_logo = ( Avada()->settings->get( 'mobile_logo' ) ) ? true : false
				?>
				<div class="side-header-content fusion-logo-<?php echo esc_attr( strtolower( Avada()->settings->get( 'logo_alignment' ) ) ); ?> fusion-mobile-logo-<?php echo esc_attr( $mobile_logo ); ?>">
					<?php avada_logo(); ?>
				</div>
				<div class="fusion-main-menu-container fusion-logo-menu-<?php echo esc_attr( strtolower( Avada()->settings->get( 'logo_alignment' ) ) ); ?>">
					<?php avada_main_menu(); ?>
				</div>
				<?php


			} elseif ( in_array( Avada()->settings->get( 'header_layout' ), [ 'v1', 'v2', 'v3' ] ) ) {
				//TODO: cs - work on v2 - 01/03/2017 10:40 AM
				//TODO: cs - work on v3 - 01/03/2017 10:40 AM


				avada_logo();
				avada_main_menu();


			} elseif ( in_array( Avada()->settings->get( 'header_layout' ), [ 'v4', 'v5' ] ) ) {
				//TODO: cs - work on v5 - 01/03/2017 10:40 AM


				avada_logo();


			} elseif ( Avada()->settings->get( 'header_layout' ) == 'v6' ) {
				//TODO: cs - work on v6 - 01/03/2017 10:40 AM


			} elseif ( Avada()->settings->get( 'header_layout' ) == 'v7' ) {
				avada_main_menu();


			}


			/**
			 * CSS
			 */
			echo "<style>";


			echo "@media (max-width: " . lct_get_mobile_threshold() . "px){
				/* STARTzz */
				body .fusion-mobile-menu-design-modern.fusion-header-v3 .fusion-header{
					padding-top:    10px;
					padding-bottom: 10px;
				}
				/* ENDzz */
			}";


			echo "</style>";
		}
	}


	/**
	 * Get the header layout content
	 *
	 * @since    7.62
	 * @verified 2017.12.20
	 */
	function avada_after_header_wrapper()
	{
		// Only load the homepage stuff if we are loading it
		if ( is_front_page() ) {
			if ( lct_acf_get_option_raw( 'is_after_header_layout_home' ) ) {
				echo '<div class="' . zxzu( 'after_header_wrapper' ) . '">';
				echo lct_theme_chunk( [ 'id' => lct_acf_get_option_raw( 'after_header_layout_home' ) ] );
				echo '</div>';
			}
		} else {
			if ( lct_acf_get_option_raw( 'is_after_header_layout' ) ) {
				echo '<div class="' . zxzu( 'after_header_wrapper' ) . '">';
				echo lct_theme_chunk( [ 'id' => lct_acf_get_option_raw( 'after_header_layout' ) ] );
				echo '</div>';
			}
		}
	}


	/**
	 * Get the Fixed Bottom Mobile Menu layout content
	 *
	 * @since    2017.97
	 * @verified 2017.12.20
	 */
	function bottom_mobile_menu_wrapper()
	{
		// Only load the homepage stuff if we are loading it
		if ( is_front_page() ) {
			if ( lct_acf_get_option_raw( 'is_bottom_mobile_menu_layout_home' ) ) {
				echo '<div class="' . zxzu( 'bottom_mobile_menu_wrapper' ) . '">';
				echo lct_theme_chunk( [ 'id' => lct_acf_get_option_raw( 'bottom_mobile_menu_layout_home' ) ] );
				echo '</div>';
			}
		} else {
			if ( lct_acf_get_option_raw( 'is_bottom_mobile_menu_layout' ) ) {
				echo '<div class="' . zxzu( 'bottom_mobile_menu_wrapper' ) . '">';
				echo lct_theme_chunk( [ 'id' => lct_acf_get_option_raw( 'bottom_mobile_menu_layout' ) ] );
				echo '</div>';
			}
		}
	}
}


/**
 * alias for header_layout()
 *
 * @since    7.62
 * @verified 2017.01.06
 */
function lct_header_layout()
{
	$class = new lct_Avada_header( lct_load_class_default_args() );


	$class->header_layout();
}
