<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2016.12.05
 */
class lct_features_shortcodes_shortcodes
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2016.11.29
	 */
	function __construct( $args = [] )
	{
		//Store $args
		$this->args = $args;


		//Store parent class, maybe
		if ( $this->args['load_parent'] ) {
			$this->zxzp = lct();
		}


		//Setup WordPress action and filter hooks
		$this->load_hooks();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    7.41
	 * @verified 2017.04.18
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
		add_filter( 'no_texturize_shortcodes', [ $this, 'no_texturize_shortcodes' ] );


		if ( ! shortcode_exists( 'span' ) ) {
			add_shortcode( 'span', [ $this, 'span' ] );
		}


		if ( ! shortcode_exists( 'space' ) ) {
			add_shortcode( 'space', [ $this, 'space' ] );
		}


		add_shortcode( 'pimg_link', [ $this, 'pimg_link' ] );


		add_shortcode( 'get_directions', [ $this, 'get_directions' ] );


		if ( ! shortcode_exists( 'raw' ) ) {
			add_shortcode( 'raw', [ $this, 'raw' ] );
		}

		add_shortcode( zxzu( 'raw' ), [ $this, 'raw' ] );


		add_shortcode( 'faicon', [ $this, 'faicon' ] );


		add_shortcode( zxzu( 'show_if' ), [ $this, 'show_if' ] );


		add_shortcode( zxzu( 'hide_if' ), [ $this, 'hide_if' ] );


		add_shortcode( zxzu( 'show_if_current_user_can' ), [ $this, 'show_if_current_user_can' ] );


		add_shortcode( zxzu( 'iframe' ), [ $this, 'iframe' ] );

		add_shortcode( zxzu( 'scroll_arrow' ), [ $this, 'scroll_arrow' ] );

		add_shortcode( zxzu( 'lazy_youtube' ), [ $this, 'lazy_youtube' ] );

		add_shortcode( zxzu( 'lazy_vimeo' ), [ $this, 'lazy_vimeo' ] );

		add_shortcode( zxzu( 'lazy_birdeye' ), [ $this, 'lazy_birdeye' ] );

		add_shortcode( zxzu( 'lazy_gmaps' ), [ $this, 'lazy_gmaps' ] );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}


		if ( lct_is_dev_or_sb() ) {
			add_shortcode( zxzu( 'test' ), [ $this, 'test' ] );
			add_shortcode( zxzu( 'test_2' ), [ $this, 'test_2' ] );
			add_shortcode( zxzu( 'test_3' ), [ $this, 'test_3' ] );
			add_shortcode( zxzu( 'test_4' ), [ $this, 'test_4' ] );
		}
	}


	/**
	 * [span]{$content}[/span]
	 * Add a span
	 *
	 * @att      string class
	 * @att      string content
	 *
	 * @param $a
	 * @param $content
	 *
	 * @return string
	 * @since    7.41
	 * @verified 2020.12.21
	 */
	function span( $a, $content )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'       => '',
				'class'   => '',
				'content' => '',
			],
			$a
		);


		/**
		 * Content
		 */
		if (
			$a['content']
			&& ! $content
		) {
			$content = $a['content'];
		}


		/**
		 * class
		 */
		if ( $a['class'] ) {
			$a['class'] = sprintf( 'class="%s"', $a['class'] );
		}


		$a['r'] = sprintf( '<span %s>%s</span>', $a['class'], do_shortcode( $content ) );


		return $a['r'];
	}


	/**
	 * [space]
	 * Add a space
	 *
	 * @return string
	 * @since    7.50
	 * @verified 2017.01.06
	 */
	function space()
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r' => ' ',
			],
			[]
		);


		return $a['r'];
	}


	/**
	 * [pimg_link]
	 * Generate a PIMG link
	 *
	 * @att      string text
	 * @att      url url
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    0.0
	 * @verified 2017.04.04
	 */
	function pimg_link( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'    => '',
				'text' => 'Pro Impressions Marketing Group',
				'url'  => 'https://www.proimpressionsgroup.com/',
			],
			$a
		);


		/**
		 * $onclick
		 */
		$onclick = lct_get_gaTracker_onclick( 'PIMG Link', $a['url'] );


		$a['r'] = sprintf( '<a href="%s" rel="nofollow" target="_blank" %s>%s</a>', $a['url'], $onclick, $a['text'] );


		return $a['r'];
	}


	/**
	 * [get_directions]
	 * Generate a get directions link
	 *
	 * @att      string url
	 * @att      string text
	 * @att      string class
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    5.38
	 * @verified 2017.04.04
	 */
	function get_directions( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => '',
				'url'   => '/contact/directions/',
				'text'  => 'Get Directions',
				'class' => '',
			],
			$a
		);


		/**
		 * url
		 */
		if (
			lct_plugin_active( 'acf' )
			&& $get_directions = lct_acf_get_field_option( 'get_directions' )
		) {
			$a['url'] = $get_directions;
		}


		/**
		 * class
		 */
		if ( $a['class'] ) {
			$a['class'] = sprintf( 'class="%s"', $a['class'] );
		}


		/**
		 * $onclick
		 */
		$onclick = lct_i_esc_brackets( lct_get_gaTracker_onclick( 'get_directions', $a['url'] ) );


		$a['r'] = sprintf( '<a href="%s" target="_blank" %s %s>%s</a>', $a['url'], $onclick, $a['class'], $a['text'] );


		return $a['r'];
	}


	/**
	 * [raw][/raw]
	 * OR
	 * [lct_raw][/lct_raw]
	 * Generate a get directions link
	 *
	 * @att      string id
	 * @att      string class
	 *
	 * @param $a
	 * @param $content
	 *
	 * @return string
	 * @since    2017.8
	 * @verified 2017.04.04
	 */
	function raw( $a, $content )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'     => '',
				'id'    => '',
				'class' => '',
			],
			$a
		);


		/**
		 * id
		 */
		if ( $a['id'] ) {
			$a['id'] = sprintf( 'id="%s"', $a['id'] );
		}


		/**
		 * class
		 */
		if ( $a['class'] ) {
			$a['class'] = sprintf( 'class="%s"', $a['class'] );
		}


		/**
		 * $content
		 */
		//Remove extra </p> tag
		if ( strpos( $content, '</p>' ) === 0 ) {
			$content = substr( $content, 4 );
		}

		//Remove extra <p> tag
		if ( ( strlen( $content ) - strrpos( $content, '<p>' ) - 3 ) === 0 ) {
			$content = substr( $content, 0, - 3 );
		}

		//Remove all new lines and tabs, so we can properly display the content as raw
		$content = lct_strip_n_r_t( $content );


		//process any nested shortcodes
		$content = sprintf( '<div %s %s>%s</div>', $a['id'], $a['class'], do_shortcode( $content ) );


		//Remove all new lines and tabs, so we can properly display the content as raw
		$a['r'] = lct_strip_n_r_t( $content );


		return $a['r'];
	}


	/**
	 * [lct_test][/lct_test]
	 * for testing only
	 *
	 * @param $a
	 * @param $content
	 *
	 * @return string
	 * @since    2017.9
	 * @verified 2017.02.07
	 */
	function test( $a, $content = '' )
	{
		$a['r'][] = '<pre>';


		if ( $content ) {
			$a['r'][] = $content . '<br />';
		}


		if ( $a ) {
			foreach ( $a as $k => $v ) {
				$a['r'][] = $k . '=' . $v . '<br />';
			}
		}


		$a['r'][] = '</pre>';


		return lct_return( $a['r'] );
	}


	/**
	 * [lct_test_2]
	 * for testing only
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2017.9
	 * @verified 2017.02.07
	 */
	function test_2( $a )
	{
		$a['r'][] = ' ::works 2:: ';


		if ( $a['test'] ) {
			$a['r'][] = $a['test'];
		}


		return lct_return( $a['r'] );
	}


	/**
	 * [lct_test_3]
	 * for testing only
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2017.9
	 * @verified 2017.02.07
	 */
	function test_3( $a )
	{
		$a['r'][] = ' ::works 3:: ';


		if ( $a['test'] ) {
			$a['r'][] = $a['test'];
		}


		return lct_return( $a['r'] );
	}


	/**
	 * [lct_test_4]
	 * for testing only
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2017.9
	 * @verified 2017.02.07
	 */
	function test_4( $a )
	{
		$a['r'][] = ' ::works 4:: ';


		if ( $a['test'] ) {
			$a['r'][] = $a['test'];
		}


		return lct_return( $a['r'] );
	}


	/**
	 * We don't want some of our shortcodes texturized
	 *
	 * @param $shortcodes
	 *
	 * @return array
	 * @since    2017.8
	 * @verified 2017.02.06
	 */
	function no_texturize_shortcodes( $shortcodes )
	{
		$shortcodes[] = 'raw';
		$shortcodes[] = zxzu( 'raw' );


		return $shortcodes;
	}


	/**
	 * [faicon]
	 * Add a font awesome icon
	 *
	 * @att      string id
	 * @att      string style
	 * @att      string class
	 * @att      string link
	 * @att      string target
	 * @att      string gatracker_cat
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    0.0
	 * @verified 2020.02.21
	 */
	function faicon( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'             => '',
				'id'            => '',
				'style'         => '',
				'class'         => '',
				'link'          => '',
				'target'        => '',
				'gatracker_cat' => '',
				'data-toggle'   => '',
				'data-target'   => '',
			],
			$a
		);


		/**
		 * data-toggle
		 */
		if ( $a['data-toggle'] ) {
			$a['data-toggle'] = sprintf( 'data-toggle="%s"', $a['data-toggle'] );
		}


		/**
		 * data-target
		 */
		if ( $a['data-target'] ) {
			$a['data-target'] = sprintf( 'data-target="%s"', $a['data-target'] );
		}


		/**
		 * style
		 */
		if ( $a['style'] ) {
			$a['style'] = sprintf( 'style="%s"', $a['style'] );
		}


		/**
		 * icon
		 */
		if ( $a['id'] ) {
			$a['r'] = sprintf( '<i class="fa fa-%s %s" %s></i>', $a['id'], $a['class'], $a['style'] );
		}


		/**
		 * link
		 */
		if ( $a['link'] ) {
			/**
			 * target
			 */
			if ( $a['target'] ) {
				$a['target'] = sprintf( 'target="%s"', $a['target'] );
			}


			/**
			 * gatracker_cat
			 */
			if ( $a['gatracker_cat'] ) {
				$a['gatracker_cat'] = lct_get_gaTracker_onclick( $a['gatracker_cat'], $a['link'] );
			}


			$a['r'] = sprintf( '<a href="%s" %s %s %s %s>%s</a>', $a['link'], $a['target'], $a['data-toggle'], $a['data-target'], $a['gatracker_cat'], $a['r'] );
		}


		return $a['r'];
	}


	/**
	 * [lct_show_if]
	 *
	 * @att      bool show
	 * @att      string hide (class name for hiding element)
	 * @att      string name
	 *
	 * @param array  $a
	 * @param string $content
	 *
	 * @return string
	 * @since    2017.32
	 * @verified 2019.11.20
	 */
	function show_if( $a, $content = '' )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'    => '',
				'show' => false,
				'hide' => 'hidden-imp',
				'name' => '',
			],
			$a
		);


		/**
		 * switch ( $a['name'] ) {
		 * default:
		 * }
		 */


		if ( $a['name'] ) {
			$a = apply_filters( 'lct/show_if', $a );
		}


		if ( $a['show'] ) {
			if ( $content ) {
				$a['r'] = do_shortcode( $content );
			}
		} else {
			if ( $content ) {
				$a['r'] = '';
			} else {
				$a['r'] = $a['hide'];
			}
		}


		return $a['r'];
	}


	/**
	 * [lct_hide_if]
	 *
	 * @att      bool show
	 * @att      string hide (class name for hiding element)
	 * @att      string name
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2017.68
	 * @verified 2017.08.18
	 */
	function hide_if( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'    => '',
				'show' => true,
				'hide' => 'hidden-imp',
				'name' => '',
			],
			$a
		);


		/**
		 * switch ( $a['name'] ) {
		 * default:
		 * }
		 */


		if ( $a['name'] ) {
			$a = apply_filters( 'lct/hide_if', $a );
		}


		if ( ! $a['show'] ) {
			$a['r'] = $a['hide'];
		}


		return $a['r'];
	}


	/**
	 * [lct_show_if_current_user_can]
	 *
	 * @att      bool show
	 * @att      string hide (class name for hiding element)
	 * @att      mixed cap
	 * @att      mixed role
	 *
	 * @param mixed $a
	 *
	 * @return string
	 * @since    2017.93
	 * @verified 2017.11.09
	 */
	function show_if_current_user_can( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'    => '',
				'show' => false,
				'hide' => 'hidden-imp',
				'cap'  => [],
				'role' => [],
			],
			$a
		);


		if ( ! empty( $a['role'] ) ) {
			$a['cap'] = $a['role'];
		}


		if ( ! empty( $a['cap'] ) ) {
			$can = 0;

			if ( ! is_array( $a['cap'] ) ) {
				$caps = explode( ',', $a['cap'] );
			} else {
				$caps = [ $a['cap'] ];
			}


			foreach ( $caps as $cap_key => $cap ) {
				$role = str_replace( lct_get_role_cap_prefixes_only(), '', $cap );


				if ( current_user_can( $role ) ) {
					$can ++;
				}
			}


			if ( $can ) {
				$a['show'] = true;
			}


			$a = apply_filters( 'lct/show_if_current_user_can', $a );
		}


		if ( ! $a['show'] ) {
			$a['r'] = $a['hide'];
		}


		return $a['r'];
	}


	/**
	 * [lct_iframe]
	 *
	 * @att      string src
	 * @att      string style
	 * @att      string frameborder
	 * @att      string width
	 * @att      string height
	 * @att      bool allowfullscreen
	 * @att      string align
	 * @att      bool resizer
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2017.61
	 * @verified 2018.12.28
	 */
	function iframe( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'               => '',
				'src'             => '',
				'style'           => '',
				'frameborder'     => '0',
				'width'           => '',
				'height'          => '',
				'align'           => '',
				'allowfullscreen' => false,
				'resizer'         => false,
			],
			$a
		);


		if ( ! $a['src'] ) {
			return $a['r'];
		}


		/**
		 * allowfullscreen
		 */
		$a['allowfullscreen'] = filter_var( $a['allowfullscreen'], FILTER_VALIDATE_BOOLEAN );

		if ( $a['allowfullscreen'] ) {
			$a['allowfullscreen'] = 'allowfullscreen';
		}


		/**
		 * resizer
		 * NOTE: You have to add this code on the iframe page.
		 * <script type="text/javascript" src="https://www.site.com/x/lc-content/plugins/lct-useful-shortcodes-functions/includes/iframe_resizer/js/iframeResizer.contentWindow.min.js"></script>
		 */
		$a['resizer'] = filter_var( $a['resizer'], FILTER_VALIDATE_BOOLEAN );

		if ( $a['resizer'] ) {
			$url = lct_get_root_url( 'includes/iframe_resizer/js/iframeResizer.min.js' );


			$a['resizer'] = sprintf(
				'<script type="text/javascript" src="%s"></script>
				<script>
				jQuery( \'iframe\' ).iFrameResize( 
					{
						log : false,
						enablePublicMethods : true,
						targetOrigin: \'%s\'
					}
				);
				</script>',
				$url,
				home_url( '/' )
			);
		} else {
			$a['resizer'] = '';
		}


		/**
		 * align
		 */
		if ( $a['align'] === 'center' ) {
			$a['style'] .= 'display: block;margin: 0 auto;';
			$a['align'] = '';
		}


		$a['r'] = sprintf(
			'<iframe src="%s" style="border: none;%s" width="%s" height="%s" frameborder="%s" align="%s" %s></iframe>%s',
			$a['src'],
			$a['style'],
			$a['width'],
			$a['height'],
			$a['frameborder'],
			$a['align'],
			$a['allowfullscreen'],
			$a['resizer']
		);


		return $a['r'];
	}


	/**
	 * [lct_scroll_arrow]
	 * @credit https://codepen.io/nxworld/pen/OyRrGy
	 *
	 * @att      int id
	 * @att      string class
	 * @att      string anchor
	 * @att      int anchor_offset
	 * @att      int anchor_offset_sticky
	 * @att      bool by_container_height
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2017.97
	 * @verified 2019.10.27
	 */
	function scroll_arrow( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'                    => [],
				'id'                   => 1,
				'class'                => '',
				'anchor'               => 'main',
				'anchor_offset'        => 0,
				'anchor_offset_sticky' => 0,
				'by_container_height'  => false,
			],
			$a
		);


		/**
		 * by_container_height
		 */
		$a['by_container_height'] = filter_var( $a['by_container_height'], FILTER_VALIDATE_BOOLEAN );


		if ( $a['id'] < 10 ) {
			$a['id'] = '0' . $a['id'];
		}


		$a['r'][] = sprintf(
			'<div id="lct_scroll_arrow%s" class="%s"><a href="#%s"><span></span><span></span><span></span></a></div>',
			$a['id'],
			$a['class'],
			$a['anchor']
		);


		/**
		 * Scroll Arrow Style
		 */
		if (
			! lct_get_setting( 'scroll_arrow_style_loaded' )
			&& file_exists( lct_get_root_path( 'assets/css/scroll_arrow.min.css' ) )
		) {
			$style    = file_get_contents( lct_get_root_path( 'assets/css/scroll_arrow.min.css' ) );
			$a['r'][] = sprintf( '<%1$s >%2$s</%1$s>', 'style', $style );


			lct_update_setting( 'scroll_arrow_style_loaded', true );
		}


		/**
		 * Scroll Arrow Script
		 */
		if ( ! lct_get_setting( 'scroll_arrow_script_loaded' ) ) {
			$offset = "( jQuery( jQuery( this ).attr( 'href' ) ).offset().top - offset_anchor )";


			if ( $a['by_container_height'] ) {
				$offset = "( jQuery( jQuery( this ).attr( 'href' ) ).actual( 'outerHeight' ) - offset_anchor )";
			}


			$script   = "<script>
			jQuery( function() {
				jQuery( 'div[id*=\"lct_scroll_arrow\"] a[href*=#]' ).on( 'click', function( e ) {
					e.preventDefault();


					var offset_anchor = '';
					offset_anchor = " . $a['anchor_offset'] . ";

					if( jQuery( '#wrapper header' ).hasClass( 'fusion-is-sticky' ) )
						offset_anchor = " . $a['anchor_offset_sticky'] . ";


					jQuery( 'html, body' ).animate( 
						{ 
							scrollTop: " . $offset . "
						}, 
						500, 
						'linear'
					)
				});
			});
			</script>";
			$a['r'][] = $script;


			lct_update_setting( 'scroll_arrow_script_loaded', true );
		}


		return lct_return( $a['r'] );
	}


	/**
	 * [lct_lazy_youtube]
	 * Lazy Load a youtube URL
	 *
	 * @att      string id
	 * @att      string class
	 * @att      string title
	 * @att      string params
	 * @att      string thumbnail
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2018.11
	 * @verified 2020.03.18
	 */
	function lazy_youtube( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'         => '',
				'id'        => null,
				'class'     => '',
				'title'     => ' ',
				'params'    => 'feature=oembed&rel=0&controls=0&showinfo=0&modestbranding=1&enablejsapi=1&wmode=opaque',
				'thumbnail' => null,
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
		 * title
		 */
		if ( strtolower( $a['title'] ) === 'true' ) {
			$a['title'] = '';
		}

		/**
		 * thumbnail
		 */
		if ( $a['thumbnail'] === null ) {
			$a['thumbnail'] = sprintf( 'https://img.youtube.com/vi/%s/maxresdefault.jpg', $a['id'] );
		}


		if ( $a['id'] ) {
			$a['r'] = sprintf(
				'<div
					class="lazyframe%s"
					data-src="https://www.youtube-nocookie.com/embed/%s?%s"
					data-vendor="youtube"
					data-title="%s"
					data-thumbnail="%s">
				</div>',
				$a['class'],
				$a['id'],
				$a['params'],
				$a['title'],
				$a['thumbnail']
			);
		}


		return $a['r'];
	}


	/**
	 * [lct_lazy_vimeo]
	 * Lazy Load a Vimeo URL
	 *
	 * @att      string id
	 * @att      string class
	 * @att      string title
	 * @att      string thumbnail
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2018.42
	 * @verified 2018.04.10
	 */
	function lazy_vimeo( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'         => '',
				'id'        => null,
				'class'     => '',
				'title'     => ' ',
				'thumbnail' => '',
				'width'     => null,
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
		 * title
		 */
		if ( strtolower( $a['title'] ) === 'true' ) {
			$a['title'] = '';
		}


		/**
		 * width
		 */
		if ( $a['width'] ) {
			$a['width'] = 'max-width: ' . $a['width'] . 'px;';
		}


		if ( $a['id'] ) {
			$a['r'] = sprintf(
				'<div
					class="lazyframe%s"
					data-src="https://player.vimeo.com/video/%s"
					data-vendor="vimeo"
					data-title="%s"
					data-thumbnail="%s"
					style="%s">
				</div>',
				$a['class'],
				$a['id'],
				$a['title'],
				$a['thumbnail'],
				$a['width']
			);
		}


		return $a['r'];
	}


	/**
	 * [lct_lazy_birdeye]
	 * Lazy Load a birdeye URL
	 *
	 * @att      string id
	 * @att      string class
	 * @att      string thumbnail
	 * @att      int wid
	 * @att      int ver
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2018.11
	 * @verified 2018.02.15
	 */
	function lazy_birdeye( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'         => '',
				'id'        => null,
				'class'     => '',
				'thumbnail' => '',
				'wid'       => 8,
				'ver'       => 4,
			],
			$a
		);


		/**
		 * class
		 */
		if ( $a['class'] ) {
			$a['class'] = ' ' . $a['class'];
		}


		if ( $a['id'] ) {
			$a['r'] = sprintf(
				'<div
					class="lazyframe birdeye%s"
					data-src="https://birdeye.com/widget/render.php?bid=%s&wid=%s&ver=%s"
					data-thumbnail="%s">
				</div>',
				$a['class'],
				$a['id'],
				$a['wid'],
				$a['ver'],
				$a['thumbnail']
			);
		}


		return $a['r'];
	}


	/**
	 * [lct_lazy_gmaps]
	 * Lazy Load a Google Map
	 *
	 * @att      string id
	 * @att      string class
	 * @att      string key
	 * @att      string title
	 * @att      string thumbnail
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    2018.42
	 * @verified 2018.04.10
	 */
	function lazy_gmaps( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'         => '',
				'id'        => null,
				'class'     => '',
				'key'       => lct_acf_get_option_raw( 'google_map_api' ),
				'height'    => '300',
				'thumbnail' => '',
			],
			$a
		);


		/**
		 * class
		 */
		if ( $a['class'] ) {
			$a['class'] = ' ' . $a['class'];
		}


		if ( $a['id'] ) {
			$a['r'] = sprintf(
				'<div
					class="lazyframe lazyframe_gmap%s"
					data-src="https://www.google.com/maps/embed/v1/place?&key=%s&q=place_id:%s"
					data-thumbnail="%s"
					style="height: %spx;width: 100%%;">
				</div>',
				$a['class'],
				$a['key'],
				$a['id'],
				$a['thumbnail'],
				$a['height']
			);
		}


		return $a['r'];
	}
}
