<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.02.06
 */
class lct_features_content
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.02.06
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
	 * @since    2017.8
	 * @verified 2017.02.07
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
		add_action( 'init', [ $this, 'register_handlers' ] );

		add_filter( 'embed_handler_html', [ $this, 'embed' ], 10, 3 );

		add_filter( 'embed_defaults', [ $this, 'embed_defaults' ], 10, 2 );


		if ( lct_frontend() ) {
			add_filter( 'the_content', [ $this, 'the_content_first' ], 3 );
			add_filter( 'the_content', [ $this, 'the_content_after_shortcodes' ], 12 );
			add_filter( 'the_content', [ $this, 'the_content_final' ], 99999 );
			add_filter( 'the_content', [ $this, 'bracket_cleanup' ], 100000 );

			//Check for shortcodes in the widget title
			add_filter( 'widget_title', 'do_shortcode', 5 );

			add_filter( 'widget_title', [ $this, 'html_widget_title' ] );

			//Check for shortcodes in widget content area
			add_filter( 'widget_text', 'do_shortcode', 12 );

			add_filter( 'widget_text', [ $this, 'execute_php' ] );

			add_filter( 'widget_text', [ $this, 'widget_text_first' ], 3 );
			add_filter( 'widget_text', [ $this, 'widget_text_final' ], 99999 );
			add_filter( 'widget_text', [ $this, 'bracket_cleanup' ], 100000 );

			add_filter( 'post_thumbnail_html', [ $this, 'remove_thumbnail_dimensions' ] );
		}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		if ( lct_ajax_only() ) {
			add_filter( 'image_send_to_editor', [ $this, 'remove_thumbnail_dimensions' ] );
		}
	}


	/**
	 * Execute php in the text widget
	 *
	 * @param $content
	 *
	 * @return string
	 * @since    0.0
	 * @verified 2017.02.07
	 */
	function execute_php( $content )
	{
		if ( ! $content ) {
			return $content;
		}


		if ( strpos( $content, '<?php' ) !== false ) {
			ob_start();
			eval( '?>' . $content );
			$content = ob_get_clean();
		}


		return $content;
	}


	/**
	 * Cleans up representations of a bracket
	 *
	 * @param $content
	 *
	 * @return mixed
	 * @since    0.0
	 * @verified 2017.02.07
	 */
	function bracket_cleanup( $content )
	{
		if ( ! $content ) {
			return $content;
		}


		return lct_i_un_esc_brackets( $content );
	}


	/**
	 * Do any first tweaks to the_content at the very beginning of the process
	 *
	 * @param $content
	 *
	 * @return mixed
	 * @since    0.0
	 * @verified 2017.02.07
	 */
	function the_content_first( $content )
	{
		if ( ! $content ) {
			return $content;
		}


		/**
		 * Encode any lct_final_shortcode_check()s
		 */
		$content = str_replace( '```', '&#x60;&#x60;&#x60;', $content );


		/**
		 * Process nested shortcodes
		 */
		$content = lct_check_for_nested_shortcodes( $content );


		/**
		 * We have to add this because wpautop doesn't check for script
		 */
		$content = lct_script_protector( $content );


		return $content;
	}


	function the_content_after_shortcodes( $content )
	{
		if ( ! $content ) {
			return $content;
		}


		/**
		 * We have to add this because wpautop doesn't check for script
		 */
		$content = lct_script_protector( $content );


		return $content;
	}


	/**
	 * Do any final tweaks to the_content at the very end of the process
	 *
	 * @param $content
	 *
	 * @return mixed
	 * @since    5.21
	 * @verified 2017.02.07
	 */
	function the_content_final( $content )
	{
		if ( ! $content ) {
			return $content;
		}


		/**
		 * Add any JS tracking scripts
		 */
		$content = lct_is_thanks_page( $content );


		/**
		 * Process final shortcodes
		 */
		$content = lct_final_shortcode_check( $content );


		/**
		 * Bug fix for fusion_builder
		 */
		$content = lct_the_content_fusion_builder_bug_fix( $content );


		/**
		 * We have to add this because wpautop doesn't check for script
		 */
		$content = lct_script_protector_decode( $content );


		return $content;
	}


	/**
	 * Do any first tweaks to widget_text at the very beginning of the process
	 *
	 * @param $content
	 *
	 * @return mixed
	 * @since    6.6
	 * @verified 2017.02.07
	 */
	function widget_text_first( $content )
	{
		if ( ! $content ) {
			return $content;
		}


		/**
		 * Process nested shortcodes
		 */
		$content = lct_check_for_nested_shortcodes( $content );


		return $content;
	}


	/**
	 * Do any final tweaks to widget_text at the very end of the process
	 *
	 * @param $content
	 *
	 * @return mixed
	 * @since    5.36
	 * @verified 2017.02.07
	 */
	function widget_text_final( $content )
	{
		if ( ! $content ) {
			return $content;
		}


		/**
		 * Process final shortcodes
		 */
		$content = lct_final_shortcode_check( $content );


		return $content;
	}


	/**
	 * [embed]
	 * Add query string elements into an embed URL
	 *
	 * @att      string id
	 * @att      string style
	 * @att      string class
	 * @att      bool legacy
	 * @att      string ratio
	 * @att      bool rel (0 = don't show related videos at the end of play)
	 * @att      string query_* (Any other Youtube query setting can be used by prefixing query_ to it in the shortcode)
	 * @att      string width
	 * @att      string height
	 * @att      array querys
	 *
	 * @param $return
	 * @param $url
	 * @param $a
	 *
	 * @return string
	 * @since    5.0
	 * @verified 2017.08.10
	 */
	function embed( $return, $url, $a )
	{
		/**
		 * Add query items to their own array
		 */
		$querys = [];

		foreach ( $a as $key => $aa ) {
			if ( strpos( $key, 'query_' ) === 0 ) {
				$key            = str_replace( 'query_', '', $key );
				$querys[ $key ] = $aa;
			}
		}


		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'      => '',
				'id'     => str_replace( '-', '_', sanitize_title( $url ) ),
				'style'  => [],
				'class'  => [],
				'legacy' => false,
				'ratio'  => '.5625',
				'rel'    => '0',
				'width'  => '',
				'height' => '',
				'querys' => $querys,
			],
			$a
		);


		/**
		 * vars
		 */
		$script    = '';
		$new_query = [];


		/**
		 * style
		 */
		if ( ! empty( $a['style'] ) ) {
			$tmp        = $a['style'];
			$a['style'] = [];

			$a['style'][] = rtrim( $tmp, ';' );
		}


		/**
		 * class
		 */
		if ( ! empty( $a['class'] ) ) {
			$tmp        = $a['class'];
			$a['class'] = [];

			$a['class'][] = trim( $tmp );
		}

		$a['class'][] = 'videoWrapper';


		/**
		 * legacy
		 */
		$a['legacy'] = filter_var( $a['legacy'], FILTER_VALIDATE_BOOLEAN );

		if ( $a['legacy'] ) {
			$a['class'][] = zxzu( 'legacy' );
		}


		/**
		 * ratio
		 */
		if (
			empty( $a['ratio'] )
			&& ! empty( $a['width'] )
			&& ! empty( $a['height'] )
		) {
			$a['ratio'] = ( (int) $a['height'] / (int) $a['width'] );
		}


		/**
		 * Get the src URL squared away
		 */
		preg_match( '/src="(.*?)"/', $return, $src );
		$src = $src[1];


		if ( $src ) {
			$src_new = parse_url( $src );


			/**
			 * Get the current query string if there is one
			 */
			if ( $src_new['query'] ) {
				$new_query[] = $src_new['query'];
			}


			/**
			 * Add rel to query string
			 */
			$new_query[] = 'rel=' . $a['rel'];


			/**
			 * Add any other query items to query string
			 */
			foreach ( $a['querys'] as $key => $aa ) {
				$new_query[] = $key . '=' . $aa;
			}


			/**
			 * Turn the array back to a query string
			 */
			if ( ! empty( $new_query ) ) {
				$src_new['query'] = lct_return( $new_query, '&' );
			}


			/**
			 * Replace the URL in the $return
			 */
			$return = str_replace( $src, unparse_url( $src_new ), $return );
		}


		/**
		 * Set up the code to make the video responsive
		 */
		if ( ! empty( $a['width'] ) ) {
			$padding_bottom = $a['width'] * $a['ratio'];


			$script = "<script>
			jQuery( document ).ready( function() {
				resize_{$a['id']}();


				jQuery( window ).resize(function() {
					resize_{$a['id']}();
				} );
			} );


			function resize_{$a['id']}() {
				var resize_id = jQuery( '#{$a['id']}' );


				if( resize_id.width() < {$a['width']} ) {
					resize_id.css( { 'padding-bottom': '' } );
				} else {
					resize_id.css( { 'padding-bottom': '{$padding_bottom}px' } );
				}
			}
			</script>";
		}


		/**
		 * width
		 */
		if ( ! empty( $a['width'] ) ) {
			$a['style'][] = 'max-width:' . $a['width'] . 'px';
		}


		/**
		 * height
		 */
		if ( ! empty( $a['height'] ) ) {
			$a['style'][] = 'max-height:' . $a['height'] . 'px';
		}


		/**
		 * Wrap the $return
		 */
		$return = preg_replace(
			'/(\r\n|\n|\r)+/',
			'',
			sprintf(
				'<div id="%s" class="%s" style="%s">%s%s</div>',
				$a['id'],
				lct_return( $a['class'], ' ' ),
				lct_return( $a['style'], ';' ),
				$return,
				$script
			)
		);


		return $return;
	}


	/**
	 * Set the defaults for embedded video to full size
	 *
	 * @param $atts
	 * @param $url
	 *
	 * @return mixed
	 * @since    0.0
	 * @verified 2016.10.31
	 */
	function embed_defaults(
		$atts,
		/** @noinspection PhpUnusedParameterInspection */
		$url
	) {
		if (
			isset( $atts['width'] )
			&& $atts['width'] == 669
		) {
			$atts['width'] = '';
		}


		if (
			isset( $atts['height'] )
			&& $atts['height'] == 1000
		) {
			$atts['height'] = '';
		}


		return $atts;
	}


	/**
	 * Remove width & height tags from img
	 *
	 * @param $html
	 *
	 * @return mixed
	 * @since    0.0
	 * @verified 2017.02.06
	 */
	function remove_thumbnail_dimensions( $html )
	{
		return preg_replace( '/(width|height)=\"\d*\"\s/', '', $html );
	}


	/**
	 * Allow html tags in widget titles
	 *
	 * @param $content
	 *
	 * @return string
	 * @since    0.0
	 * @verified 2017.02.07
	 */
	function html_widget_title( $content )
	{
		if ( ! $content ) {
			return $content;
		}


		return htmlspecialchars_decode( $content );
	}


	/**
	 * We need this to run Vimeo thru the embed_handler_html filter
	 *
	 * @since    2017.12
	 * @verified 2017.02.13
	 */
	function register_handlers()
	{
		wp_embed_register_handler( 'lct_vimeo_embed_url', '#https?://(www.)?player\.vimeo\.com/(?:video|embed)/([^/]+)#i', [ $this, 'embed_vimeo' ] );
	}


	/**
	 * Callback for {zxzu}vimeo_embed_url
	 *
	 * @param $matches
	 * @param $attr
	 * @param $url
	 * @param $rawattr
	 *
	 * @return mixed
	 * @since    2017.12
	 * @verified 2017.02.13
	 */
	function embed_vimeo(
		/** @noinspection PhpUnusedParameterInspection */
		$matches,
		$attr,
		$url,
		$rawattr
	) {
		$embed = sprintf( '<iframe src="%s" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>', $url );


		return apply_filters( 'lct_wp_embed_handler_vimeo', $embed, $attr, $url, $rawattr );
	}
}
