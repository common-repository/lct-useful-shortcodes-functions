<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.01.06
 */
class lct_acf_shortcodes
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
	 * @since    7.49
	 * @verified 2017.08.10
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
		add_shortcode( zxzu( 'copyright' ), [ $this, 'copyright' ] );

		add_shortcode( zxzu( 'phone' ), [ $this, 'phone' ] );

		add_shortcode( zxzu( 'fax' ), [ $this, 'fax' ] );

		add_shortcode( zxzu( 'business_name' ), [ $this, 'business_name' ] );

		add_shortcode( zxzu( 'address' ), [ $this, 'address' ] );

		add_shortcode( zxzu( 'hours' ), [ $this, 'hours' ] );

		add_shortcode( zxzu( 'call_button' ), [ $this, 'call_button' ] );

		add_shortcode( zxzu( 'mobi_call_button' ), [ $this, 'mobi_call_button' ] );

		add_shortcode( zxzu( 'book_appt_button' ), [ $this, 'book_appt_button' ] );

		add_shortcode( zxzu( 'mobi_book_appt_button' ), [ $this, 'mobi_book_appt_button' ] );

		add_shortcode( zxzu( 'contact_button' ), [ $this, 'contact_button' ] );

		add_shortcode( zxzu( 'mobi_contact_button' ), [ $this, 'mobi_contact_button' ] );

		add_shortcode( zxzu( 'findus_button' ), [ $this, 'findus_button' ] );

		add_shortcode( zxzu( 'mobi_findus_button' ), [ $this, 'mobi_findus_button' ] );

		add_shortcode( zxzu( 'fixed_buttons' ), [ $this, 'fixed_buttons' ] );

		add_shortcode( zxzu( 'br' ), [ $this, 'br' ] );
		if ( ! shortcode_exists( 'br' ) ) {
			add_shortcode( 'br', [ $this, 'br' ] );
		}


		add_shortcode( zxzu( 'acf_repeater_items' ), [ $this, 'repeater_items_shortcode' ] );

		add_shortcode( zxzu( 'acf_load_gfont' ), [ $this, 'load_gfont' ] );

		add_shortcode( zxzu( 'acf_load_typekit' ), [ $this, 'load_typekit' ] );

		add_shortcode( zxzu( 'acf' ), [ $this, 'acf' ] );

		add_shortcode( zxzu( 'acf_term' ), [ $this, 'acf_term' ] );

		add_shortcode( zxzu( 'read_more' ), [ $this, 'read_more' ] );

		add_shortcode( zxzu( 'mobi_home_button' ), [ $this, 'mobi_home_button' ] );

		add_shortcode( zxzu( 'get_recent_post_permalink' ), [ $this, 'get_recent_post_permalink' ] );

		add_shortcode( zxzu( 'get_current_user' ), [ $this, 'get_current_user' ] );

		add_shortcode( zxzu( 'acf_display_value' ), [ $this, 'acf_display_value' ] );


		if ( lct_frontend() ) {
			add_action( 'wp_footer', [ $this, 'fixed_buttons' ] );
		}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Get the field for a shortcode
	 *
	 * @param $str
	 *
	 * @return mixed
	 * @since    7.66
	 * @verified 2017.01.06
	 */
	function sc( $str = '' )
	{
		return lct_acf_get_option( $this->sc . $str );
	}


	/**
	 * [lct_copyright]
	 * Create some copyright text based on the easy to use ACF form
	 *
	 * @return string
	 * @since    0.0
	 * @verified 2019.02.16
	 */
	function copyright()
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


		if ( $this->sc( 'use_this_shortcode' ) ) {
			if (
				$this->sc( 'link_title' )
				&& (
					! $this->sc( 'no_single_link' )
					|| (
						$this->sc( 'no_single_link' )
						&& ! is_single()
					)
				)
			) {
				$title_link = $this->sc( 'title_link' );


				if ( $this->sc( 'title_link_blank' ) ) {
					$target = 'target="_blank"';
				} else {
					$target = '';
				}


				if (
					(
						strpos( $title_link, 'http:' ) !== false
						|| strpos( $title_link, 'https:' ) !== false
					)
					&& strpos( $title_link, lct_url_site() ) === false
				) {
					$onclick = lct_get_gaTracker_onclick( 'Footer Title Link', $title_link );
				} else {
					$onclick = '';
				}


				$title = sprintf( '<a href="%s" %s %s>%s</a>', $title_link, $target, $onclick, $this->sc( 'title' ) );
			} else {
				$title = $this->sc( 'title' );
			}


			if ( $this->sc( 'use_copyright_layout_multi' ) ) {
				$copyright_layout = $this->sc( 'copyright_layout_multi' );
			} else {
				$copyright_layout = $this->sc( 'copyright_layout' );
			}


			$find_n_replace = [
				'{copy_symbol}'  => '&copy;',
				'{year}'         => lct_format_current_time( 'Y' ),
				'{title}'        => $title,
				'{builder_plug}' => $this->sc( 'builder_plug' ),
			];

			if ( $replace = $this->sc( 'xml' ) ) {
				$find_n_replace['{XML_sitemap}'] = sprintf( "<a href='%s'>Sitemap</a>", home_url( $replace ) );
			}

			if ( $replace = $this->sc( 'privacy_policy_page' ) ) {
				$find_n_replace['{privacy}'] = sprintf( "<a href='%s' rel='nofollow'>%s</a>", get_the_permalink( $replace ), get_the_title( $replace ) );
			}

			if ( $replace = $this->sc( 'terms_page' ) ) {
				$find_n_replace['{terms}'] = sprintf( "<a href='%s' rel='nofollow'>%s</a>", get_the_permalink( $replace ), get_the_title( $replace ) );
			}

			$fnr = lct_create_find_and_replace_arrays( $find_n_replace );


			$a['r'] = do_shortcode( str_replace( $fnr['find'], $fnr['replace'], do_shortcode( $copyright_layout ) ) );
		}


		return $a['r'];
	}


	/**
	 * [lct_phone]
	 * Display the phone number that we have stored as an ACF field in the DB
	 *
	 * @att      string number
	 * @att      bool tel_link_only
	 * @att      bool number_only
	 * @att      bool|string icon
	 * @att      string action
	 * @att      string class
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    7.59
	 * @verified 2019.08.22
	 */
	function phone( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'             => '',
				'number'        => '',
				'tel_link_only' => false,
				'number_only'   => false,
				'icon'          => false,
				'action'        => '',
				'class'         => '',
			],
			$a
		);


		/**
		 * tel_link_only
		 */
		$a['tel_link_only'] = filter_var( $a['tel_link_only'], FILTER_VALIDATE_BOOLEAN );


		/**
		 * number_only
		 */
		$a['number_only'] = filter_var( $a['number_only'], FILTER_VALIDATE_BOOLEAN );


		/**
		 * icon
		 */
		if ( $a['icon'] ) {
			$icon = 'phone';

			if ( filter_var( $a['icon'], FILTER_VALIDATE_BOOLEAN ) === false ) {
				$icon = $a['icon'];
			}


			$a['icon'] = do_shortcode( sprintf( '[faicon id="%s"]', $icon ) );
			$a['icon'] = sprintf( " pre='%s'", $a['icon'] );
		}


		//We are only going to continue, if the phone_number is stored in our ACF setting
		if ( lct_acf_get_option_raw( 'is_phone_number' ) ) {
			/**
			 * $phone_number
			 */
			$phone_number = lct_acf_get_field_option( 'phone_number' );


			if ( $a['number'] ) {
				$phone_number = $a['number'];
			}


			/**
			 * $phone_number_text
			 */
			if (
				lct_acf_get_option_raw( 'is_phone_number_format_letters' )
				&& ( $phone_number_text = lct_acf_get_field_option( 'phone_number_format_letters' ) )
			) {
				$phone_number_text = 'text="' . $phone_number_text . '" ';
			} else {
				$phone_number_text = '';
			}


			/**
			 * action
			 */
			if ( $a['action'] ) {
				$a['action'] = str_replace( '{phone}', lct_format_phone_number( $phone_number ), $a['action'] );
				$a['action'] = sprintf( " action='%s'", $a['action'] );
			}

			/**
			 * class
			 */
			if ( $a['class'] ) {
				$a['class'] = sprintf( " class='%s'", $a['class'] );
			}


			//This is a special tel_link that includes an open-ended onclick. It is designed to be placed inside href="", kind of hacky, but we have to do it right now.
			if ( $a['tel_link_only'] ) {
				$a['r'] = sprintf( 'tel:%s" onclick="%s', lct_strip_phone( $phone_number ), lct_get_gaTracker_onclick( 'tel_link', '', lct_format_phone_number( $phone_number ), false ) );
			} //Just return the formatted version of the phone_number
			elseif ( $a['number_only'] ) {
				$a['r'] = lct_format_phone_number( $phone_number );
			} else {
				$a['r'] = do_shortcode( sprintf( '[%s phone="%s" %s%s%s%s]', zxzu( 'tel_link' ), $phone_number, $phone_number_text, $a['icon'], $a['action'], $a['class'] ) );
			}
		}


		$a['r'] = do_shortcode( $a['r'] );


		return $a['r'];
	}


	/**
	 * [lct_fax]
	 * Display the fax number that we have stored as an ACF field in the DB
	 *
	 * @att      bool tel_link_only
	 * @att      bool number_only
	 * @att      bool icon
	 * @att      string action
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2017.22
	 * @verified 2017.05.02
	 */
	function fax( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'             => '',
				'tel_link_only' => false,
				'number_only'   => false,
				'icon'          => false,
				'action'        => '',
			],
			$a
		);


		/**
		 * tel_link_only
		 */
		$a['tel_link_only'] = filter_var( $a['tel_link_only'], FILTER_VALIDATE_BOOLEAN );


		/**
		 * number_only
		 */
		$a['number_only'] = filter_var( $a['number_only'], FILTER_VALIDATE_BOOLEAN );


		/**
		 * icon
		 */
		$a['icon'] = filter_var( $a['icon'], FILTER_VALIDATE_BOOLEAN );

		if ( $a['icon'] ) {
			$a['icon'] = do_shortcode( '[faicon id="fax"]' );
			$a['icon'] = "pre='{$a['icon']}'";
		}


		//We are only going to continue, if the fax_number is stored in our ACF setting
		if ( lct_acf_get_option_raw( 'is_fax_number' ) ) {
			/**
			 * $fax_number
			 */
			$fax_number = lct_acf_get_field_option( 'fax_number' );


			/**
			 * action
			 */
			if ( $a['action'] ) {
				$a['action'] = str_replace( '{fax}', lct_format_phone_number( $fax_number ), $a['action'] );
				$a['action'] = " action='{$a['action']}'";
			}


			//This is a special tel_link that includes an open-ended onclick. It is designed to be placed inside href="", kind of hacky, but we have to do it right now.
			if ( $a['tel_link_only'] ) {
				$a['r'] = sprintf( 'tel:%s" onclick="%s', lct_strip_phone( $fax_number ), lct_get_gaTracker_onclick( 'tel_link', '', lct_format_phone_number( $fax_number ), false ) );
			} //Just return the formatted version of the fax_number
			elseif ( $a['number_only'] ) {
				$a['r'] = lct_format_phone_number( $fax_number );
			} else {
				$a['r'] = do_shortcode( sprintf( '[%s phone="%s" %s%s]', zxzu( 'tel_link' ), $fax_number, $a['icon'], $a['action'] ) );
			}
		}


		$a['r'] = do_shortcode( $a['r'] );


		return $a['r'];
	}


	/**
	 * [lct_business_name]
	 * Display the business_name that we have stored as an ACF field in the DB
	 *
	 * @att      string font_weight
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    7.66
	 * @verified 2017.05.02
	 */
	function business_name( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'           => '',
				'font_weight' => 'bold',
			],
			$a
		);


		//We are only going to continue, if the business_name is stored in our ACF setting
		if ( lct_acf_get_option_raw( 'is_business_name' ) ) {
			/**
			 * $business_name
			 */
			$business_name = lct_acf_get_field_option( 'business_name' );


			$a['r'] = $business_name;


			if ( $a['font_weight'] == 'bold' ) {
				$a['r'] = sprintf( '<strong>%s</strong>', $a['r'] );
			}
		}


		$a['r'] = do_shortcode( $a['r'] );


		return $a['r'];
	}


	/**
	 * [lct_address]
	 * Display the address that we have stored as an ACF field in the DB
	 *
	 * @att      bool icon
	 * @att      bool newline i.e., comma, ,, oneline, one_line
	 * @att      mixed link (bool or url)
	 * @att      string target (_self or _blank)
	 * @att      bool directions
	 * @att      bool link_color_body
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    7.66
	 * @verified 2017.08.25
	 */
	function address( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'               => '',
				'icon'            => false,
				'newline'         => false,
				'link'            => false,
				'target'          => '_blank',
				'directions'      => false,
				'link_color_body' => true,
			],
			$a
		);


		/**
		 * icon
		 */
		$a['icon'] = filter_var( $a['icon'], FILTER_VALIDATE_BOOLEAN );

		if ( $a['icon'] ) {
			$a['icon'] = do_shortcode( '[faicon id="map-marker"]' );
		}


		/**
		 * link
		 */
		if (
			$a['link']
			&& ! filter_var( $a['link'], FILTER_VALIDATE_URL )
		) {
			$a['link'] = filter_var( $a['link'], FILTER_VALIDATE_BOOLEAN );
		}


		/**
		 * $full_address
		 */
		//We are only going to continue, if the phone_number is stored in our ACF setting
		if ( lct_acf_get_option_raw( 'is_address' ) ) {
			$full_address = '';
			$address      = lct_acf_get_field_option( 'address' );
			$city         = lct_acf_get_field_option( 'city' );
			$state        = lct_acf_get_field_option( 'state' );
			$zip          = lct_acf_get_field_option( 'zip' );

			if ( $address ) {
				$full_address = $address . '<br />' . $city . ', ' . $state . ' ' . $zip;

				if ( in_array( $a['newline'], [ 'oneline', 'one_line' ] ) ) {
					$full_address = lct_nl2br( lct_br2nl( $full_address ), ', ' );
				} elseif ( in_array( $a['newline'], [ 'comma', ',' ] ) ) {
					$full_address = lct_nl2br( $full_address, ', ' );
				} elseif ( $a['newline'] ) {
					$full_address = lct_nl2br( lct_br2nl( $full_address ), $a['newline'] . ' ' );
				} else {
					$full_address = lct_nl2br( $full_address );
				}
			}


			$a['r'] = sprintf( '%s %s', $a['icon'], $full_address );


			//Maybe wrap in a link
			if ( $a['link'] ) {
				if ( is_bool( $a['link'] ) ) {
					$a['link'] = lct_acf_get_field_option( 'get_directions' );
				}


				/**
				 * $class
				 */
				$class = '';

				if ( $a['link_color_body'] ) {
					$class = 'class="' . zxzu( 'body_typography_color' ) . '"';
				}


				$a['r'] = sprintf( '<a href="%s" target="%s"%s>%s</a>', $a['link'], $a['target'], $class, $a['r'] );
			}
		}


		/**
		 * directions
		 */
		if ( $a['directions'] ) {
			$a['r'] .= '<br />' . esc_js( do_shortcode( '[get_directions]' ) );
		}


		$a['r'] = do_shortcode( $a['r'] );


		return $a['r'];
	}


	/**
	 * [lct_hours]
	 * Display the hours that we have stored as an ACF field in the DB
	 *
	 * @return string
	 * @since    7.66
	 * @verified 2017.05.02
	 */
	function hours()
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


		//We are only going to continue, if the hours is stored in our ACF setting
		if ( lct_acf_get_option_raw( 'is_hours' ) ) {
			/**
			 * $hours
			 */
			$hours = lct_acf_get_field_option( 'hours' );


			$a['r'] = $hours;
		}


		$a['r'] = do_shortcode( $a['r'] );


		return $a['r'];
	}


	/**
	 * [lct_call_button]
	 * Get call button using a shortcode
	 *
	 * @att      string class
	 * @att      string float
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    7.67
	 * @verified 2017.01.09
	 */
	function call_button( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => '',
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
			$a['class'] = ' ' . zxzu( 'mobi_float_' . $a['float'] );
		}


		//We are only going to continue, if the phone_number is stored in our ACF setting
		if ( lct_acf_get_option_raw( 'is_phone_number' ) ) {
			/**
			 * $phone
			 */
			$phone    = lct_acf_get_field_option( 'phone_number' );
			$tel_link = 'tel:' . lct_strip_phone( $phone ) . '" onclick="' . lct_get_gaTracker_onclick( 'tel_link', '', $phone, false );


			/**
			 * $button_text
			 */
			$button_text = lct_acf_get_field_option( 'call_button_text' );


			/**
			 * $button
			 */
			$button = do_shortcode( "[fusion_button link=\"^^tel_link^^\" target=\"_self\" hide_on_mobile=\"small-visibility,medium-visibility,large-visibility\" color=\"custom\" " . lct_acf_get_specific_mobi_nav_color( 'call' ) . " border_width=\"0\" stretch=\"default\" icon_position=\"left\" icon_divider=\"no\" animation_direction=\"left\" animation_speed=\"0.3\" class=\"" . zxzu( 'cta_button' ) . "{$a['class']}\"]{$button_text}[/fusion_button]" );


			/**
			 * Final adjustment
			 */
			$a['r'] = str_replace( '^^tel_link^^', $tel_link, $button );
		}


		return $a['r'];
	}


	/**
	 * [lct_mobi_call_button]
	 * Get mobile call button using a shortcode
	 *
	 * @att      string class
	 * @att      string float
	 * @att      string image
	 * @att      string icon
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    7.62
	 * @verified 2020.03.03
	 */
	function mobi_call_button( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => '',
				'class' => '',
				'float' => '',
				'image' => '',
				'icon'  => '',
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
			$a['class'] = ' ' . zxzu( 'mobi_float_' . $a['float'] );
		}


		/**
		 * $phone
		 */
		$phone    = lct_acf_get_field_option( 'phone_number' );
		$tel_link = 'tel:' . lct_strip_phone( $phone ) . '" onclick="' . lct_get_gaTracker_onclick( 'tel_link', '', $phone, false );


		/**
		 * $button_text
		 */
		if ( $a['image'] ) {
			$default_image = filter_var( $a['image'], FILTER_VALIDATE_BOOLEAN );


			if ( $default_image === true ) {
				$url = lct_get_root_url( 'assets/images/Icon-Contact-H135.png' );
			} else {
				$url = $a['image'];
			}


			$contact_alt = lct_acf_get_field_option( 'call_button_text_mobi' );
			if ( lct_acf_get_field_option( 'contact_page_alt' ) ) {
				$contact_alt = lct_acf_get_field_option( 'contact_page_alt' );
			}


			$button_text = sprintf( '<img data-no-lazy="1" src="%s" alt="%s"/>', $url, $contact_alt );
		} else {
			$icon = 'phone';

			if ( $a['icon'] ) {
				$icon = $a['icon'];
			}


			$button_text = sprintf( '<i class=\'fa fa-%1$s\'></i><br class="%2$s"/><span class="%2$s">%3$s</span>', $icon, lct_acf_get_menu_button_class(), lct_acf_get_field_option( 'call_button_text_mobi' ) );
		}


		/**
		 * $button
		 */
		$button = do_shortcode( "[fusion_button link=\"^^tel_link^^\" target=\"_self\" hide_on_mobile=\"small-visibility,medium-visibility,large-visibility\" color=\"custom\" " . lct_acf_get_mobi_nav_colors() . " border_width=\"0\" stretch=\"default\" icon_position=\"left\" icon_divider=\"no\" animation_direction=\"left\" animation_speed=\"0.3\" class=\"" . zxzu( 'mobi_button' ) . ' ' . zxzu( 'mobi_button_call' ) . "{$a['class']}\"]{$button_text}[/fusion_button]" );


		/**
		 * Final adjustment
		 */
		$a['r'] = str_replace( '^^tel_link^^', $tel_link, $button );


		return $a['r'];
	}


	/**
	 * [lct_book_appt_button]
	 * Get book_appt button using a shortcode
	 *
	 * @att      string class
	 * @att      string float
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    7.67
	 * @verified 2019.08.22
	 */
	function book_appt_button( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => '',
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
			$a['class'] = ' ' . zxzu( 'mobi_float_' . $a['float'] );
		}


		/**
		 * $link
		 */
		$link = get_the_permalink( lct_acf_get_field_option( 'book_appt_page' ) );


		/**
		 * $button_text
		 */
		$button_text = lct_acf_get_field_option( 'book_appt_page_button_text' );


		$a['r'] = do_shortcode( "[fusion_button link=\"{$link}\" target=\"_self\" hide_on_mobile=\"small-visibility,medium-visibility,large-visibility\" color=\"custom\" " . lct_acf_get_specific_mobi_nav_color( 'book_appt_page' ) . " border_width=\"0\" stretch=\"default\" icon_position=\"left\" icon_divider=\"no\" animation_direction=\"left\" animation_speed=\"0.3\" class=\"" . zxzu( 'cta_button' ) . "{$a['class']}\"]{$button_text}[/fusion_button]" );


		return $a['r'];
	}


	/**
	 * [lct_mobi_book_appt_button]
	 * Get mobile book appt button using a shortcode
	 *
	 * @att      string class
	 * @att      string float
	 * @att      mixed image
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    7.62
	 * @verified 2020.03.03
	 */
	function mobi_book_appt_button( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => '',
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
			$a['class'] = ' ' . zxzu( 'mobi_float_' . $a['float'] );
		}


		/**
		 * $link
		 */
		$link = get_the_permalink( lct_acf_get_field_option( 'book_appt_page' ) );


		/**
		 * $button_text
		 */
		if ( $a['image'] ) {
			$default_image = filter_var( $a['image'], FILTER_VALIDATE_BOOLEAN );


			if ( $default_image === true ) {
				$url = lct_get_root_url( 'assets/images/Icon-Schedule-H135.png' );
			} else {
				$url = $a['image'];
			}


			$book_appt_alt = lct_acf_get_field_option( 'book_appt_page_button_text_mobile' );
			if ( lct_acf_get_field_option( 'book_appt_page_alt' ) ) {
				$book_appt_alt = lct_acf_get_field_option( 'book_appt_page_alt' );
			}


			$button_text = sprintf( '<img data-no-lazy="1" src="%s" alt="%s"/>', $url, $book_appt_alt );
		} else {
			$button_text = "<i class='fa fa-calendar'></i><br class=\"" . lct_acf_get_menu_button_class() . "\"/><span class=\"" . lct_acf_get_menu_button_class() . "\">" . lct_acf_get_field_option( 'book_appt_page_button_text_mobile' ) . "</span>";
		}


		$a['r'] = do_shortcode( "[fusion_button link=\"{$link}\" target=\"_self\" hide_on_mobile=\"small-visibility,medium-visibility,large-visibility\" color=\"custom\" " . lct_acf_get_mobi_nav_colors() . " border_width=\"0\" stretch=\"default\" icon_position=\"left\" icon_divider=\"no\" animation_direction=\"left\" animation_speed=\"0.3\" class=\"" . zxzu( 'mobi_button' ) . ' ' . zxzu( 'mobi_button_book_appt' ) . "{$a['class']}\"]{$button_text}[/fusion_button]" );


		return $a['r'];
	}


	/**
	 * [lct_contact_button]
	 * Get contact button using a shortcode
	 *
	 * @att      string class
	 * @att      string float
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    7.67
	 * @verified 2019.08.22
	 */
	function contact_button( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => '',
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
			$a['class'] = ' ' . zxzu( 'mobi_float_' . $a['float'] );
		}


		/**
		 * $link
		 */
		$link = get_the_permalink( lct_acf_get_field_option( 'contact_page' ) );


		/**
		 * $button_text
		 */
		$button_text = lct_acf_get_field_option( 'contact_page_button_text' );


		$a['r'] = do_shortcode( "[fusion_button link=\"{$link}\" target=\"_self\" hide_on_mobile=\"small-visibility,medium-visibility,large-visibility\" color=\"custom\" " . lct_acf_get_specific_mobi_nav_color( 'contact_page' ) . " border_width=\"0\" stretch=\"default\" icon_position=\"left\" icon_divider=\"no\" animation_direction=\"left\" animation_speed=\"0.3\" class=\"" . zxzu( 'cta_button' ) . "{$a['class']}\"]{$button_text}[/fusion_button]" );


		return $a['r'];
	}


	/**
	 * [lct_mobi_contact_button]
	 * Get mobile contact button using a shortcode
	 *
	 * @att      string class
	 * @att      string float
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    7.62
	 * @verified 2020.06.10
	 */
	function mobi_contact_button( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => '',
				'class' => '',
				'float' => '',
				'icon'  => 'map-marker',
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
			$a['class'] = ' ' . zxzu( 'mobi_float_' . $a['float'] );
		}


		/**
		 * $link
		 */
		$link = get_the_permalink( lct_acf_get_field_option( 'contact_page' ) );


		/**
		 * $button_text
		 */
		$button_text = "<i class='fa fa-" . $a['icon'] . "'></i><br class=\"" . lct_acf_get_menu_button_class() . "\"/><span class=\"" . lct_acf_get_menu_button_class() . "\">" . lct_acf_get_field_option( 'contact_page_button_text_mobile' ) . "</span>";


		$a['r'] = do_shortcode( "[fusion_button link=\"{$link}\" target=\"_self\" hide_on_mobile=\"small-visibility,medium-visibility,large-visibility\" color=\"custom\" " . lct_acf_get_mobi_nav_colors() . " border_width=\"0\" stretch=\"default\" icon_position=\"left\" icon_divider=\"no\" animation_direction=\"left\" animation_speed=\"0.3\" class=\"" . zxzu( 'mobi_button' ) . ' ' . zxzu( 'mobi_button_contact' ) . "{$a['class']}\"]{$button_text}[/fusion_button]" );


		return $a['r'];
	}


	/**
	 * [lct_findus_button]
	 * Get findus button using a shortcode
	 *
	 * @att      string class
	 * @att      string float
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    7.67
	 * @verified 2019.08.22
	 */
	function findus_button( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => '',
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
			$a['class'] = ' ' . zxzu( 'mobi_float_' . $a['float'] );
		}


		/**
		 * $link
		 */
		$link = get_the_permalink( lct_acf_get_field_option( 'contact_page' ) );


		/**
		 * $button_text
		 */
		$button_text = lct_acf_get_field_option( 'contact_page_findus_button_text' );


		$a['r'] = do_shortcode( "[fusion_button link=\"{$link}\" target=\"_self\" hide_on_mobile=\"small-visibility,medium-visibility,large-visibility\" color=\"custom\" " . lct_acf_get_specific_mobi_nav_color( 'contact_page' ) . " border_width=\"0\" stretch=\"default\" icon_position=\"left\" icon_divider=\"no\" animation_direction=\"left\" animation_speed=\"0.3\" class=\"" . zxzu( 'cta_button' ) . "{$a['class']}\"]{$button_text}[/fusion_button]" );


		return $a['r'];
	}


	/**
	 * [lct_mobi_findus_button]
	 * Get mobile findus button using a shortcode
	 *
	 * @att      string class
	 * @att      string float
	 * @att      mixed image
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    7.62
	 * @verified 2020.03.03
	 */
	function mobi_findus_button( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => '',
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
			$a['class'] = ' ' . zxzu( 'mobi_float_' . $a['float'] );
		}


		/**
		 * $link
		 */
		$target = '_self';

		if (
			lct_plugin_active( 'acf' )
			&& $get_directions = lct_acf_get_field_option( 'get_directions' )
		) {
			$link   = $get_directions;
			$target = '_blank';
		} elseif ( $contact_page = get_the_permalink( lct_acf_get_field_option( 'contact_page' ) ) ) {
			$link = $contact_page;
		} else {
			$link = '#';
		}


		/**
		 * $button_text
		 */
		if ( $a['image'] ) {
			$default_image = filter_var( $a['image'], FILTER_VALIDATE_BOOLEAN );


			if ( $default_image === true ) {
				$url = lct_get_root_url( 'assets/images/Icon-Map-H135.png' );
			} else {
				$url = $a['image'];
			}


			$contact_alt = lct_acf_get_field_option( 'contact_page_findus_button_text_mobile' );
			if ( lct_acf_get_field_option( 'contact_page_alt' ) ) {
				$contact_alt = lct_acf_get_field_option( 'contact_page_alt' );
			}


			$button_text = sprintf( '<img data-no-lazy="1" src="%s" alt="%s"/>', $url, $contact_alt );
		} else {
			$button_text = "<i class='fa fa-map-marker'></i><br class=\"" . lct_acf_get_menu_button_class() . "\"/><span class=\"" . lct_acf_get_menu_button_class() . "\">" . lct_acf_get_field_option( 'contact_page_findus_button_text_mobile' ) . "</span>";
		}


		$a['r'] = do_shortcode( "[fusion_button link=\"{$link}\" target=\"{$target}\" hide_on_mobile=\"small-visibility,medium-visibility,large-visibility\" color=\"custom\" " . lct_acf_get_mobi_nav_colors() . " border_width=\"0\" stretch=\"default\" icon_position=\"left\" icon_divider=\"no\" animation_direction=\"left\" animation_speed=\"0.3\" class=\"" . zxzu( 'mobi_button' ) . ' ' . zxzu( 'mobi_button_findus' ) . "{$a['class']}\"]{$button_text}[/fusion_button]" );


		return $a['r'];
	}


	/**
	 * [lct_fixed_buttons]
	 * Display fixed CTA buttons based on the easy to use ACF form
	 *
	 * @return string
	 * @since    7.66
	 * @verified 2023.05.03
	 */
	function fixed_buttons()
	{
		if ( ! $this->sc( 'use_fixed_buttons' ) ) {
			return '';
		}


		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r' => '',
			],
			[]
		);


		if ( $this->sc( 'use_fixed_buttons' ) ) {
			global $post;

			$status  = $this->sc( 'fixed_buttons_status' );
			$exclude = $this->sc( 'fixed_buttons_excluded_pages' );
			$include = $this->sc( 'fixed_buttons_included_pages' );


			//Inclusive: Fixed Buttons will display on ALL the pages, except for the ones you add to the 'exclude' list below.
			//Exclusive: Fixed Buttons will display on NONE of the pages, except for the ones you add to the 'include' list below.
			if (
				(
					! $status
					&& //Inclusive
					(
						! $exclude
						|| ! in_array( $post->ID, $exclude )
					)
				)
				|| (
					$status
					&& //Exclusive
					$include
					&& in_array( $post->ID, $include )
				)
			) {
				ob_start();
				?>


				<div class="<?php echo zxzu( 'cb_con' ); ?>" style="display: none;">
					<?php if ( lct_acf_get_option_raw( 'is_contact_page' ) ) {
						$contact_img_l = lct_acf_get_field_option( 'contact_page_button_image_large' );
						$contact_img_m = lct_acf_get_field_option( 'contact_page_button_image_medium' );
						$contact_img_s = lct_acf_get_field_option( 'contact_page_button_image_small' );
						$contact_alt   = lct_acf_get_field_option( 'contact_page_button_text' );
						if ( lct_acf_get_field_option( 'contact_page_alt' ) ) {
							$contact_alt = lct_acf_get_field_option( 'contact_page_alt' );
						}


						?>
						<a class="<?php echo zxzu( 'fix_button_contact' ); ?> fusion-button" href="<?php echo get_the_permalink( lct_acf_get_field_option( 'contact_page' ) ); ?>">
							<?php if ( ! empty( $contact_img_l['url'] ) ) { ?>
								<img data-no-lazy="1" class="<?php echo zxzu( 'height_1' ); ?>" src="<?php echo $contact_img_l['url']; ?>" alt="<?php echo $contact_alt; ?>"/>
							<?php } ?>

							<?php if ( ! empty( $contact_img_m['url'] ) ) { ?>
								<img data-no-lazy="1" class="<?php echo zxzu( 'height_2' ); ?>" src="<?php echo $contact_img_m['url']; ?>" alt="<?php echo $contact_alt; ?>"/>
							<?php } ?>

							<?php if ( ! empty( $contact_img_s['url'] ) ) { ?>
								<img data-no-lazy="1" class="<?php echo zxzu( 'height_3' ); ?>" src="<?php echo $contact_img_s['url']; ?>" alt="<?php echo $contact_alt; ?>"/>
							<?php } ?>
						</a>

						<div class="button_space"></div>


						<style>
							/*
												 * button stuff
												 */
							.<?php echo zxzu( 'fix_button_contact' ); ?>{
								background: <?php echo $this->sc( 'fixed_buttons_contact_page_button_color' ); ?> !important;
								border:         none !important;
								border-radius:  0 !important;

								font-size:      18px !important;
								line-height:    22px !important;
								padding:        8px 20px !important;

								text-transform: capitalize !important;
							}


							.<?php echo zxzu( 'fix_button_contact' ); ?>,
							.<?php echo zxzu( 'fix_button_contact' ); ?> .fusion-button-text,
							.<?php echo zxzu( 'fix_button_contact' ); ?> .fa{
								color: <?php echo $this->sc( 'fixed_buttons_contact_page_button_text_color'); ?> !important;
							}


							.<?php echo zxzu( 'fix_button_contact:hover' ); ?>{
								background: <?php echo $this->sc( 'fixed_buttons_contact_page_button_color'); ?> !important;
							}


							.<?php echo zxzu( 'fix_button_contact:hover' ); ?>,
							.<?php echo zxzu( 'fix_button_contact:hover' ); ?> .fusion-button-text,
							.<?php echo zxzu( 'fix_button_contact:hover' ); ?> .fa{
								color: <?php echo $this->sc( 'fixed_buttons_contact_page_button_text_color'); ?> !important;
							}
						</style>
					<?php } ?>


					<?php if ( lct_acf_get_option_raw( 'is_patient_forms_page' ) ) {
						$forms_img_l = lct_acf_get_field_option( 'patient_forms_page_button_image_large' );
						$forms_img_m = lct_acf_get_field_option( 'patient_forms_page_button_image_medium' );
						$forms_img_s = lct_acf_get_field_option( 'patient_forms_page_button_image_small' );
						$forms_alt   = lct_acf_get_field_option( 'patient_forms_page_button_text' );
						if ( lct_acf_get_field_option( 'patient_forms_page_alt' ) ) {
							$forms_alt = lct_acf_get_field_option( 'patient_forms_page_alt' );
						}

						?>
						<a class="<?php echo zxzu( 'fix_button_form' ); ?> fusion-button" href="<?php echo get_the_permalink( lct_acf_get_field_option( 'patient_forms_page' ) ); ?>">
							<?php if ( ! empty( $forms_img_l['url'] ) ) { ?>
								<img data-no-lazy="1" class="<?php echo zxzu( 'height_1' ); ?>" src="<?php echo $forms_img_l['url']; ?>" alt="<?php echo $forms_alt; ?>"/>
							<?php } ?>

							<?php if ( ! empty( $forms_img_m['url'] ) ) { ?>
								<img data-no-lazy="1" class="<?php echo zxzu( 'height_2' ); ?>" src="<?php echo $forms_img_m['url']; ?>" alt="<?php echo $forms_alt; ?>"/>
							<?php } ?>

							<?php if ( ! empty( $forms_img_s['url'] ) ) { ?>
								<img data-no-lazy="1" class="<?php echo zxzu( 'height_3' ); ?>" src="<?php echo $forms_img_s['url']; ?>" alt="<?php echo $forms_alt; ?>"/>
							<?php } ?>
						</a>

						<div class="button_space"></div>


						<style>
							/*
												 * button stuff
												 */
							.<?php echo zxzu( 'fix_button_form' ); ?>{
								background: <?php echo $this->sc( 'fixed_buttons_patient_forms_page_button_color'); ?> !important;
								border:         none !important;
								border-radius:  0 !important;

								font-size:      18px !important;
								line-height:    22px !important;
								padding:        8px 20px !important;

								text-transform: capitalize !important;
							}


							.<?php echo zxzu( 'fix_button_form' ); ?>,
							.<?php echo zxzu( 'fix_button_form' ); ?> .fusion-button-text,
							.<?php echo zxzu( 'fix_button_form' ); ?> .fa{
								color: <?php echo $this->sc( 'fixed_buttons_patient_forms_page_button_text_color'); ?> !important;
							}


							.<?php echo zxzu( 'fix_button_form:hover' ); ?>{
								background: <?php echo $this->sc( 'fixed_buttons_patient_forms_page_button_color'); ?> !important;
							}


							.<?php echo zxzu( 'fix_button_form:hover' ); ?>,
							.<?php echo zxzu( 'fix_button_form:hover' ); ?> .fusion-button-text,
							.<?php echo zxzu( 'fix_button_form:hover' ); ?> .fa{
								color: <?php echo $this->sc( 'fixed_buttons_patient_forms_page_button_text_color'); ?> !important;
							}
						</style>
					<?php } ?>


					<?php if ( lct_acf_get_option_raw( 'is_book_appt_page' ) ) {
						$book_appt_img_l = lct_acf_get_field_option( 'book_appt_page_button_image_large' );
						$book_appt_img_m = lct_acf_get_field_option( 'book_appt_page_button_image_medium' );
						$book_appt_img_s = lct_acf_get_field_option( 'book_appt_page_button_image_small' );
						$book_appt_alt   = lct_acf_get_field_option( 'book_appt_page_button_text' );
						if ( lct_acf_get_field_option( 'book_appt_page_alt' ) ) {
							$book_appt_alt = lct_acf_get_field_option( 'book_appt_page_alt' );
						}

						?>
						<a class="<?php echo zxzu( 'fix_button_book' ); ?> fusion-button" href="<?php echo get_the_permalink( lct_acf_get_field_option( 'book_appt_page' ) ); ?>">
							<?php if ( ! empty( $book_appt_img_l['url'] ) ) { ?>
								<img data-no-lazy="1" class="<?php echo zxzu( 'height_1' ); ?>" src="<?php echo $book_appt_img_l['url']; ?>" alt="<?php echo $book_appt_alt; ?>"/>
							<?php } ?>

							<?php if ( ! empty( $book_appt_img_m['url'] ) ) { ?>
								<img data-no-lazy="1" class="<?php echo zxzu( 'height_2' ); ?>" src="<?php echo $book_appt_img_m['url']; ?>" alt="<?php echo $book_appt_alt; ?>"/>
							<?php } ?>

							<?php if ( ! empty( $book_appt_img_s['url'] ) ) { ?>
								<img data-no-lazy="1" class="<?php echo zxzu( 'height_3' ); ?>" src="<?php echo $book_appt_img_s['url']; ?>" alt="<?php echo $book_appt_alt; ?>"/>
							<?php } ?>
						</a>


						<style>
							/*
												 * button stuff
												 */
							.<?php echo zxzu( 'fix_button_book' ); ?>{
								background: <?php echo $this->sc( 'fixed_buttons_book_appt_page_button_color'); ?> !important;
								border:         none !important;
								border-radius:  0 !important;

								font-size:      18px !important;
								line-height:    22px !important;
								padding:        8px 20px !important;

								text-transform: capitalize !important;
							}


							.<?php echo zxzu( 'fix_button_book' ); ?>,
							.<?php echo zxzu( 'fix_button_book' ); ?> .fusion-button-text,
							.<?php echo zxzu( 'fix_button_book' ); ?> .fa{
								color: <?php echo $this->sc( 'fixed_buttons_book_appt_page_button_text_color'); ?> !important;
							}


							.<?php echo zxzu( 'fix_button_book:hover' ); ?>{
								background: <?php echo $this->sc( 'fixed_buttons_book_appt_page_button_color'); ?> !important;
							}


							.<?php echo zxzu( 'fix_button_book:hover' ); ?>,
							.<?php echo zxzu( 'fix_button_book:hover' ); ?> .fusion-button-text,
							.<?php echo zxzu( 'fix_button_book:hover' ); ?> .fa{
								color: <?php echo $this->sc( 'fixed_buttons_book_appt_page_button_text_color'); ?> !important;
							}
						</style>
					<?php } ?>
				</div>


				<div class="<?php echo zxzu( 'cb_con_hor' ); ?>" style="display: none;">
					<?php if ( lct_acf_get_option_raw( 'is_contact_page' ) ) { ?>
						<a class="<?php echo zxzu( 'fix_button_contact' ); ?> fusion-button" href="<?php echo get_the_permalink( lct_acf_get_field_option( 'contact_page' ) ); ?>">
							<?php echo lct_acf_get_field_option( 'contact_page_button_text' ); ?>
						</a>
					<?php } ?>


					<?php if ( lct_acf_get_option_raw( 'is_patient_forms_page' ) ) { ?>
						<a class="<?php echo zxzu( 'fix_button_form' ); ?> fusion-button" href="<?php echo get_the_permalink( lct_acf_get_field_option( 'patient_forms_page' ) ); ?>">
							<?php echo lct_acf_get_field_option( 'patient_forms_page_button_text' ); ?>
						</a>
					<?php } ?>


					<?php if ( lct_acf_get_option_raw( 'is_book_appt_page' ) ) { ?>
						<a class="<?php echo zxzu( 'fix_button_book' ); ?> fusion-button" href="<?php echo get_the_permalink( lct_acf_get_field_option( 'book_appt_page' ) ); ?>">
							<?php echo lct_acf_get_field_option( 'book_appt_page_button_text' ); ?>
						</a>
					<?php } ?>
				</div>


				<style>
					/*
									 * button stuff
									 */
					.<?php echo zxzu( 'cb_con' ); ?> [class*="<?php echo zxzu( 'fix_button_' ); ?>"]{
						padding: 10px 14px !important;
					}


					.<?php echo zxzu( 'fix_button_contact' ); ?> .<?php echo zxzu( 'height_2' ); ?>,
					.<?php echo zxzu( 'fix_button_contact' ); ?> .<?php echo zxzu( 'height_3' ); ?>,
					.<?php echo zxzu( 'fix_button_form' ); ?> .<?php echo zxzu( 'height_2' ); ?>,
					.<?php echo zxzu( 'fix_button_form' ); ?> .<?php echo zxzu( 'height_3' ); ?>,
					.<?php echo zxzu( 'fix_button_book' ); ?> .<?php echo zxzu( 'height_2' ); ?>,
					.<?php echo zxzu( 'fix_button_book' ); ?> .<?php echo zxzu( 'height_3' ); ?>{
						display: none;
					}


					/*
									 * cb_con
									 */
					.<?php echo zxzu( 'cb_con' ); ?>{
						position: fixed;
						top:      <?php echo $this->sc( 'fixed_buttons_top_offset' ); ?>px;
						right:    <?php echo $this->sc( 'fixed_buttons_right_offset' ); ?>px;
						z-index:  99998;
					}


					.<?php echo zxzu( 'cb_con' ); ?> .button_space{
						display:       block;
						margin-bottom: 6px;
					}


					/*
									 * cb_con_hor
									 */
					.<?php echo zxzu( 'cb_con_hor' ); ?>{
						position: fixed;
						bottom:   <?php echo $this->sc( 'fixed_buttons_horizontal_bottom_offset' ); ?>px;
						right:    <?php echo $this->sc( 'fixed_buttons_horizontal_right_offset' ); ?>px;
						z-index:  99998;
					}


					.<?php echo zxzu( 'cb_con_hor' ); ?> a{
						margin-right: 7px;
					}


					@media (min-width: <?php echo lct_get_mobile_threshold() + 1; ?>px) and (max-width: 1320px) and (min-height: <?php echo $this->sc( 'fixed_buttons_screen_height_scale_6' ) + 1; ?>px){
						/* STARTzz */
					<?php if( ! is_front_page() ) { ?>
						#main,
						.fusion-footer-widget-area{
							padding-right: 57px !important;
						}


					<?php } ?>


						/* ENDzz */
					}


					<?php if( is_front_page() ) { ?>
					@media (max-width: 1320px){
						/* STARTzz */
						.<?php echo zxzu( 'cb_con' ); ?>{
							display: none !important;
						}


						.<?php echo zxzu( 'cb_con_hor' ); ?>{
							display: block !important;
						}


						/* ENDzz */
					}


					<?php } else { ?>
					@media (max-width: <?php echo lct_get_mobile_threshold(); ?>px){
						/* STARTzz */
						.<?php echo zxzu( 'cb_con' ); ?>{
							display: none !important;
						}


						.<?php echo zxzu( 'cb_con_hor' ); ?>{
							display: block !important;
						}


						/* ENDzz */
					}


					<?php } ?>

					@media (min-width: <?php echo lct_get_mobile_threshold() + 1; ?>px) and (min-height: <?php echo $this->sc( 'fixed_buttons_screen_height_scale_6' ) + 1; ?>px){
						/* STARTzz */
						#toTop{
							right: 65px !important;
						}


						/* ENDzz */
					}


					@media (max-width: <?php echo lct_get_small_mobile_threshold(); ?>px){
						/* STARTzz */
						.<?php echo zxzu( 'cb_con_hor' ); ?>{
							display: none !important;
						}


						/* ENDzz */
					}


					<?php
									/**
									 * special padding issue
									 */
									if( $this->sc( 'is_fixed_buttons_copyright_footer_padding' ) ){ ?>
					@media (min-width: <?php echo lct_get_small_mobile_threshold() + 1; ?>px) and (max-width: <?php echo lct_get_mobile_threshold(); ?>px){
						/* STARTzz */
						.fusion-footer-copyright-area{
							padding-bottom: <?php echo $this->sc( 'fixed_buttons_copyright_footer_padding' ); ?>px !important;
						}


						/* ENDzz */
					}


					@media (max-height: <?php echo $this->sc( 'fixed_buttons_screen_height_scale_6' ); ?>px){
						/* STARTzz */
						.fusion-footer-copyright-area{
							padding-bottom: <?php echo $this->sc( 'fixed_buttons_copyright_footer_padding' ); ?>px !important;
						}


						/* ENDzz */
					}


					<?php } ?>

					@media (max-height: <?php echo $this->sc( 'fixed_buttons_screen_height_scale_1' ); ?>px) and (min-height: <?php echo $this->sc( 'fixed_buttons_screen_height_scale_2' ) + 1; ?>px){
						/* STARTzz */
						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_book' ); ?> .<?php echo zxzu( 'height_1' ); ?>{
							display: none;
						}


						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_book' ); ?> .<?php echo zxzu( 'height_2' ); ?>{
							display: initial;
						}


						/* ENDzz */
					}


					@media (max-height: <?php echo $this->sc( 'fixed_buttons_screen_height_scale_2' ); ?>px) and (min-height: <?php echo $this->sc( 'fixed_buttons_screen_height_scale_3' ) + 1; ?>px){
						/* STARTzz */
						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_book' ); ?> .<?php echo zxzu( 'height_1' ); ?>,
						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_form' ); ?> .<?php echo zxzu( 'height_1' ); ?>{
							display: none;
						}


						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_book' ); ?> .<?php echo zxzu( 'height_2' ); ?>,
						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_form' ); ?> .<?php echo zxzu( 'height_2' ); ?>{
							display: initial;
						}


						/* ENDzz */
					}


					@media (max-height: <?php echo $this->sc( 'fixed_buttons_screen_height_scale_3' ); ?>px) and (min-height: <?php echo $this->sc( 'fixed_buttons_screen_height_scale_4' ) + 1; ?>px){
						/* STARTzz */
						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_book' ); ?> .<?php echo zxzu( 'height_1' ); ?>,
						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_form' ); ?> .<?php echo zxzu( 'height_1' ); ?>,
						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_contact' ); ?> .<?php echo zxzu( 'height_1' ); ?>{
							display: none;
						}


						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_book' ); ?> .<?php echo zxzu( 'height_2' ); ?>,
						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_form' ); ?> .<?php echo zxzu( 'height_2' ); ?>,
						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_contact' ); ?> .<?php echo zxzu( 'height_2' ); ?>{
							display: initial;
						}


						/* ENDzz */
					}


					@media (max-height: <?php echo $this->sc( 'fixed_buttons_screen_height_scale_4' ); ?>px) and (min-height: <?php echo $this->sc( 'fixed_buttons_screen_height_scale_5' ) + 1; ?>px){
						/* STARTzz */
						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_form' ); ?>,
						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_book' ); ?> .<?php echo zxzu( 'height_1' ); ?>,
						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_contact' ); ?> .<?php echo zxzu( 'height_1' ); ?>{
							display: none;
						}


						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_book' ); ?> .<?php echo zxzu( 'height_2' ); ?>,
						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_contact' ); ?> .<?php echo zxzu( 'height_2' ); ?>{
							display: initial;
						}


						/* ENDzz */
					}


					@media (max-height: <?php echo $this->sc( 'fixed_buttons_screen_height_scale_5' ); ?>px) and (min-height: <?php echo $this->sc( 'fixed_buttons_screen_height_scale_6' ) + 1; ?>px){
						/* STARTzz */
						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_contact' ); ?>,
						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_form' ); ?>,
						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_book' ); ?> .<?php echo zxzu( 'height_1' ); ?>{
							display: none;
						}


						.<?php echo zxzu( 'cb_con' ); ?> .<?php echo zxzu( 'fix_button_book' ); ?> .<?php echo zxzu( 'height_2' ); ?>{
							display: initial;
						}


						/* ENDzz */
					}


					@media (max-height: <?php echo $this->sc( 'fixed_buttons_screen_height_scale_6' ); ?>px) and (min-width: <?php echo lct_get_small_mobile_threshold() + 1; ?>px){
						/* STARTzz */
						.<?php echo zxzu( 'cb_con' ); ?>{
							display: none !important;
						}


						.<?php echo zxzu( 'cb_con_hor' ); ?>{
							display: block !important;
						}


						/* ENDzz */
					}


					<?php if( is_front_page() ) { ?>
					@media (max-width: 1320px){
						/* STARTzz */
						#toTop{
							bottom: 0 !important;
							right:  30px !important;
						}


					<?php if( $this->sc( 'is_fixed_buttons_copyright_footer_padding' )){?>
						.fusion-footer-copyright-area{
							padding-bottom: <?php echo $this->sc( 'fixed_buttons_copyright_footer_padding' ); ?>px !important;
						}


					<?php } ?>


						/* ENDzz */
					}


					<?php } else { ?>
					@media (max-width: <?php echo lct_get_mobile_threshold(); ?>px){
						/* STARTzz */
						#toTop{
							bottom: 0 !important;
						}


						/* ENDzz */
					}


					<?php } ?>
				</style>


				<script>
					jQuery( window ).load( function() {
						BookAppt();
					} );

					jQuery( window ).scroll( function() {
						BookAppt();
					} );

					function BookAppt() {
						var container_inner_scrollTop = jQuery( window ).scrollTop();

						<?php if( is_front_page() ) { ?>
						if( container_inner_scrollTop >= <?php echo $this->sc( 'fixed_buttons_scroll_show_front' ); ?> ) {
							jQuery( '.<?php echo zxzu( 'cb_con' ); ?>' ).show( 'slow' );
						} else {
							jQuery( '.<?php echo zxzu( 'cb_con' ); ?>' ).hide();
						}
						<?php } else { ?>
						if( container_inner_scrollTop >= <?php echo $this->sc( 'fixed_buttons_scroll_show' ); ?> ) {
							jQuery( '.<?php echo zxzu( 'cb_con' ); ?>'
							).show( 'slow' );
						} else {
							jQuery( '.<?php echo zxzu( 'cb_con' ); ?>' ).hide();
						}
						<?php } ?>
					}
				</script>


				<?php
				$a['r'] = ob_get_clean();
			}
		}


		//echo if called from action
		if (
			$a['r']
			&& current_action() == 'wp_footer'
		) {
			echo $a['r'];

			$a['r'] = '';
		}


		return $a['r'];
	}


	/**
	 * [lct_acf_repeater_items]
	 * shortcode for repeater field
	 * Similar: lct_acf_get_imploded_repeater()
	 *
	 * @att      string field
	 * @att      int post_id
	 * @att      bool format_value
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    0.0
	 * @verified 2017.09.28
	 */
	function repeater_items_shortcode( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'            => [],
				'field'        => '',
				'post_id'      => false,
				'format_value' => false,
			],
			$a
		);


		if ( ! empty( $a['field'] ) ) {
			if ( have_rows( $a['field'], $a['post_id'] ) ) {
				$the_first = '';
				$the_rest  = [];


				while( have_rows( $a['field'], $a['post_id'] ) ) {
					$row = the_row();


					foreach ( $row as $sub_field_key => $sub_field_value ) {
						$sub_field = get_field_object( $sub_field_key, $a['post_id'], $a['format_value'] );
						$value     = lct_acf_format_value( $sub_field_value, $a['post_id'], $sub_field, true );


						if ( ! $the_first ) {
							$the_first = $value;
						} else {
							$the_rest[] = sprintf( '<li><strong>%s</strong>: %s</li>', $sub_field['label'], $value );
						}
					}
				}


				$a['r'][] = '<h3>' . $the_first . '</h3>';
				$a['r'][] = '<ul>';
				$a['r'][] = lct_return( $the_rest );
				$a['r'][] = '</ul>';
			}
		}


		return lct_return( $a['r'] );
	}


	/**
	 * [lct_acf_load_gfont]
	 * ADD a single Google Font stylesheet
	 * //TODO: cs - update SC - 2/24/2017 6:09 PM
	 *
	 * @param $a
	 *
	 * @return bool
	 * @since    5.40
	 * @verified 2016.09.29
	 */
	function load_gfont( $a )
	{
		if ( isset( $a['id'] ) ) /**
		 * @date     0.0
		 * @since    0.0
		 * @verified 2021.08.30
		 */ {
			do_action( 'lct_acf_single_load_google_fonts', $a['id'] );
		}


		return false;
	}


	/**
	 * [lct_acf_load_typekit]
	 * ADD a single Adobe Typekit script
	 * //TODO: cs - update SC - 2/24/2017 6:09 PM
	 *
	 * @param $a
	 *
	 * @return false
	 * @since    0.0
	 * @verified 2016.09.29
	 */
	function load_adobe_typekit( $a )
	{
		if ( isset( $a['id'] ) ) /**
		 * @date     0.0
		 * @since    0.0
		 * @verified 2021.08.30
		 */ {
			do_action( 'lct_acf_single_load_adobe_typekit', $a['id'] );
		}


		return false;
	}


	/**
	 * [lct_acf]
	 * Display info an ACF field using a shortcode
	 * This was created because the standard [acf] shortcode cause a fatal error when you try to display a term
	 * //TODO: cs - Maybe tie into lct_acf_format_value() - 9/28/2017 10:34 PM
	 *
	 * @att      string field
	 * @att      mixed post_id
	 * @att      bool format_value
	 * @att      bool label
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2017.42
	 * @verified 2024.03.22
	 */
	function acf( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'            => '',
				'field'        => '',
				'post_id'      => false,
				'format_value' => true,
				'label'        => false,
			],
			$a
		);


		/**
		 * format_value
		 */
		$a['format_value'] = filter_var( $a['format_value'], FILTER_VALIDATE_BOOLEAN );


		/**
		 * label
		 */
		$a['label'] = filter_var( $a['label'], FILTER_VALIDATE_BOOLEAN );


		/**
		 * shortcode
		 */
		$shortcode = false;


		if ( function_exists( 'acf_shortcode' ) ) {
			$field_type = '';

			if (
				( $field = acf_maybe_get_field( $a['field'], $a['post_id'] ) )
				&& ! empty( $field['type'] )
			) {
				$field_type = $field['type'];
			}


			switch ( $field_type ) {
				case 'taxonomy':
					$shortcode = acf_shortcode( $a );


					if ( lct_is_a( $shortcode, 'WP_Term' ) ) {
						$shortcode = $shortcode->name;
					}
					break;


				case 'user':
					$shortcode = get_field_object( $a['field'], $a['post_id'], false );


					if (
						! empty( $shortcode['value'] )
						&& ( $user_obj = get_userdata( $shortcode['value'] ) )
					) {
						$shortcode = $user_obj->display_name;
					}
					break;


				default:
					$shortcode = acf_shortcode( $a );
			}
		}


		$a['r'] = $shortcode;


		if ( $a['label'] ) {
			$format = '<div class="acf-field">
					<div class="acf-label"><label>%s</label></div>
					<div class="acf-input">%s</div>
				</div>';


			$a['r'] = sprintf( $format, get_label( $a['field'], $a['post_id'] ), $a['r'] );
		}


		return $a['r'];
	}


	/**
	 * [lct_acf_term]
	 * Display info a term using a shortcode
	 * This was created because the standard [acf] shortcode cause a fatal error when you try to display a term
	 * //TODO: cs - Add a return att, so we can return things other than name - 4/18/2017 4:07 PM
	 *
	 * @att      string field
	 * @att      mixed post_id
	 * @att      bool format_value
	 * @att      bool label
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2017.32
	 * @verified 2017.06.06
	 */
	function acf_term( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'            => '',
				'field'        => '',
				'post_id'      => false,
				'format_value' => true,
				'label'        => false,
			],
			$a
		);


		/**
		 * format_value
		 */
		$a['format_value'] = filter_var( $a['format_value'], FILTER_VALIDATE_BOOLEAN );


		/**
		 * label
		 */
		$a['label'] = filter_var( $a['label'], FILTER_VALIDATE_BOOLEAN );


		/**
		 * shortcode
		 */
		if ( function_exists( 'acf_shortcode' ) ) {
			$shortcode = acf_shortcode( $a );


			if ( lct_is_a( $shortcode, 'WP_Term' ) ) {
				$shortcode = $shortcode->name;
			}
		} else {
			$shortcode = false;
		}


		$a['r'] = $shortcode;


		if ( $a['label'] ) {
			$format = '<div class="acf-field">
					<div class="acf-label"><label>%s</label></div>
					<div class="acf-input">%s</div>
				</div>';


			$a['r'] = sprintf( $format, get_label( $a['field'], $a['post_id'] ), $a['r'] );
		}


		return $a['r'];
	}


	/**
	 * [br]
	 * OR
	 * [lct_br]
	 * Place a <br /> where HTML encoding is sensitive
	 *
	 * @att      string class
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    0.0
	 * @verified 2017.07.21
	 */
	function br( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => '',
				'class' => '',
			],
			$a
		);


		if ( $a['class'] ) {
			$a['r'] = sprintf( '<br class="%s" />', $a['class'] );
		} else {
			$a['r'] = '<br />';
		}


		return $a['r'];
	}


	/**
	 * [lct_read_more]
	 * Create a read more hidden div, a read more button or of coarse both
	 *
	 * @att      string button
	 * @att      int id
	 * @att      string type
	 * @att      string text
	 * @att      string class
	 *
	 * @param $a
	 * @param $content
	 *
	 * @return string
	 * @since    5.4
	 * @verified 2017.12.18
	 */
	function read_more( $a, $content )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'      => [],
				'button' => 'read_more_button',
				'id'     => '0',
				'type'   => 'both',
				'text'   => 'Read More...',
				'class'  => '',
			],
			$a
		);


		/**
		 * Content
		 */
		if ( ! empty( $content ) ) {
			$content = do_shortcode( $content );
		}


		/**
		 * Read More Button
		 */
		if ( in_array( $a['type'], [ 'both', 'button' ] ) ) {
			$a['r'][] = sprintf( '<a class="%1$s %1$s_%2$s %3$s" href="#" data-read_class="%2$s">%4$s</a>', $a['button'], $a['id'], $a['class'], $a['text'] );
		}


		/**
		 * Content Wrapper
		 */
		if ( in_array( $a['type'], [ 'both', 'content' ] ) ) {
			$a['r'][] = sprintf( '<div class="%1$s_copy %1$s_%2$s_copy %3$s" style="display: none;">%4$s</div>', $a['button'], $a['id'], $a['class'], $content );
		}


		/**
		 * Read More JS
		 */
		if (
			! lct_get_setting( 'read_more_script_loaded' )
			&& file_exists( lct_get_root_path( 'assets/js/read_more.min.js' ) )
		) {
			$script   = file_get_contents( lct_get_root_path( 'assets/js/read_more.min.js' ) );
			$a['r'][] = sprintf( '<%1$s id="%2$s">%3$s</%1$s>', 'script', zxzu( 'read_more' ), $script );


			lct_update_setting( 'read_more_script_loaded', true );
		}


		return lct_return( $a['r'] );
	}


	/**
	 * [lct_mobi_home_button]
	 * Get mobile home button using a shortcode
	 *
	 * @att      string class
	 * @att      string float
	 * @att      mixed image
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2017.97
	 * @verified 2020.03.03
	 */
	function mobi_home_button( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => '',
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
			$a['class'] = ' ' . zxzu( 'mobi_float_' . $a['float'] );
		}


		/**
		 * $link
		 */
		$link = home_url( '/' );


		/**
		 * $button_text
		 */
		if ( $a['image'] ) {
			$default_image = filter_var( $a['image'], FILTER_VALIDATE_BOOLEAN );


			if ( $default_image === true ) {
				$url = lct_get_root_url( 'assets/images/Icon-Home-H135.png' );
			} else {
				$url = $a['image'];
			}


			$alt         = 'Home';
			$button_text = sprintf( '<img data-no-lazy="1" src="%s" alt="%s"/>', $url, $alt );
		} else {
			$button_text = "<i class='fa fa-home'></i><br class=\"" . lct_acf_get_menu_button_class() . "\"/><span class=\"" . lct_acf_get_menu_button_class() . "\">Home</span>";
		}


		$a['r'] = do_shortcode( "[fusion_button link=\"{$link}\" target=\"_self\" hide_on_mobile=\"small-visibility,medium-visibility,large-visibility\" color=\"custom\" " . lct_acf_get_mobi_nav_colors() . " border_width=\"0\" stretch=\"default\" icon_position=\"left\" icon_divider=\"no\" animation_direction=\"left\" animation_speed=\"0.3\" class=\"" . zxzu( 'mobi_button' ) . ' ' . zxzu( 'mobi_button_home' ) . "{$a['class']}\"]{$button_text}[/fusion_button]" );


		return $a['r'];
	}


	/**
	 * [lct_get_recent_post_permalink]
	 *
	 * @att      int id
	 * @att      int index
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2018.9
	 * @verified 2018.02.01
	 */
	function get_recent_post_permalink( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => '#',
				'id'    => null,
				'index' => 1,
			],
			$a
		);


		/**
		 * id
		 */
		$a['id'] = lct_get_post_id( $a['id'] );


		/**
		 * index
		 */
		$a['index'] = $a['index'] - 1;


		/**
		 * Get the post
		 */
		$args = [
			'posts_per_page'         => 1,
			'offset'                 => $a['index'],
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'orderby'                => 'date',
			'order'                  => 'DESC',
			'fields'                 => 'ids',
			'cache_results'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		];
		$post = get_posts( $args );


		/**
		 * Make the permalink
		 */
		if ( ! empty( $post ) ) {
			$a['r'] = get_the_permalink( $post[0] );
		}


		return $a['r'];
	}


	/**
	 * [lct_get_current_user]
	 *
	 * @att      string info
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2018.17
	 * @verified 2018.02.23
	 */
	function get_current_user( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'    => '',
				'info' => null,
			],
			$a
		);


		$user = wp_get_current_user();


		/**
		 * Display the user
		 */
		if ( ! empty( $user ) ) {
			switch ( $a['info'] ) {
				case 'user_email' :
				case 'email' :
					$a['r'] = $user->user_email;
					break;


				case 'user_login' :
				case 'login' :
					$a['r'] = $user->user_login;
					break;


				case 'first_name' :
					$a['r'] = $user->first_name;
					break;


				case 'last_name' :
					$a['r'] = $user->last_name;
					break;


				case 'nickname' :
					$a['r'] = $user->nickname;
					break;


				default:
					$a['r'] = $user->display_name;
			}
		}


		return $a['r'];
	}


	/**
	 * [lct_acf_display_value]
	 *
	 * @att      string field
	 * @att      int post_id
	 * @att      bool format_value
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2019.27
	 * @verified 2022.11.30
	 */
	function acf_display_value( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'            => '',
				'field'        => '',
				'post_id'      => false,
				'format_value' => true
			],
			$a
		);


		/**
		 * Check post_id
		 */
		$a['post_id'] = lct_pre_check_post_id( $a['post_id'] );


		/**
		 * format_value
		 */
		$a['format_value'] = filter_var( $a['format_value'], FILTER_VALIDATE_BOOLEAN );


		if (
			$a['post_id'] === null
			|| $a['post_id'] === 'null'
		) {
			return '';
		}


		return lct_acf_display_value( $a['field'], $a['post_id'], $a['format_value'] );
	}
}
