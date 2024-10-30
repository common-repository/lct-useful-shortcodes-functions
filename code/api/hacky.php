<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2018.01.16
 */
class lct_api_hacky
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2018.01.16
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
	 * @since    2018.4
	 * @verified 2018.01.16
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
		add_action( 'init', [ $this, 'set_shortcode_tags_link_always' ] );


		if ( lct_frontend() ) {
			add_action( 'avada_blog_post_content', [ $this, 'avada_render_blog_post_content' ], 9 );

			add_action( 'fusion_blog_shortcode_loop_content', [ $this, 'fusion_blog_shortcode_loop_content' ], 2 );
		}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Super hacky way to allow our link shortcodes to process in excerpts
	 *
	 * @since    2017.34
	 * @verified 2018.01.16
	 */
	function set_shortcode_tags_link_always()
	{
		global $shortcode_tags;


		if ( isset( $shortcode_tags['link'] ) ) {
			lct_update_later( 'shortcode_tags_link_always', $shortcode_tags['link'] );
		}
	}


	/**
	 * Get the post (excerpt).
	 * hacky
	 *
	 * @return void Content is directly echoed.
	 * @since    2017.67
	 * @verified 2018.01.16
	 */
	function avada_render_blog_post_content()
	{
		if (
			! lct_theme_active( 'Avada' )
			|| (
				is_search()
				&& ! Avada()->settings->get( 'search_excerpt' )
			)
		) {
			return;
		}


		if ( $tmp = lct_get_later( 'shortcode_tags_link_always' ) ) {
			remove_action( 'avada_blog_post_content', 'avada_render_blog_post_content', 10 );


			global $shortcode_tags;

			$unset_link = false;


			if ( ! isset( $shortcode_tags['link'] ) ) {
				$unset_link = true;
			} else {
				unset( $shortcode_tags['link'] );
			}


			ob_start();


			$html_encode_fnr = lct_shortcode_html_decode();
			$output          = str_replace( $html_encode_fnr['find'], $html_encode_fnr['replace'], fusion_get_post_content() );


			if ( ! isset( $shortcode_tags['link'] ) ) {
				$shortcode_tags['link'] = $tmp;
			}

			echo do_shortcode( $output );


			echo ob_get_clean();


			if ( $unset_link ) {
				unset( $shortcode_tags['link'] );
			}
		}
	}


	/**
	 * Super hacky way to allow our link shortcodes to process in excerpts
	 *
	 * @since    2017.15
	 * @verified 2024.09.25
	 */
	function fusion_blog_shortcode_loop_content()
	{
		if ( lct_theme_active( 'Avada' ) ) {
			global $shortcode_tags;

			$parent_post = get_post( lct_get_post_id( null, true ) );
			preg_match( '/\[fusion_blog(.*?)\/\]/', $parent_post->post_content, $fusion_blog );
			isset( $fusion_blog[1] ) ? $fusion_blog_atts = shortcode_parse_atts( $fusion_blog[1] ) : null;


			//Only enact these hooks if we are displaying an 'excerpt'
			if (
				isset( $fusion_blog_atts['excerpt'] )
				&& $fusion_blog_atts['excerpt'] == 'yes'
			) {
				if ( isset( $shortcode_tags['link'] ) ) {
					lct_update_later( __CLASS__, $shortcode_tags['link'], 'shortcode_tags_link' );
					unset( $shortcode_tags['link'] );
				}


				add_action( 'fusion_blog_shortcode_loop_content', [ $this, 'fusion_blog_shortcode_loop_content_done' ], 11 );
				add_filter( 'do_shortcode_tag', [ $this, 'do_shortcode_tag' ] );
			}
		}
	}


	/**
	 * Super hacky way to allow our link shortcodes to process in excerpts
	 *
	 * @since    2017.15
	 * @verified 2018.01.16
	 */
	function fusion_blog_shortcode_loop_content_done()
	{
		if ( lct_theme_active( 'Avada' ) ) {
			global $shortcode_tags;


			if ( $tmp = lct_get_later( __CLASS__, 'shortcode_tags_link' ) ) {
				lct_update_later( __CLASS__, null, 'shortcode_tags_link' );
				$shortcode_tags['link'] = $tmp;
			}
		}
	}


	/**
	 * Super hacky way to allow our link shortcodes to process in excerpts
	 *
	 * @param $output
	 *
	 * @return string
	 * @since    2017.15
	 * @verified 2018.03.08
	 */
	function do_shortcode_tag( $output )
	{
		if ( lct_theme_active( 'Avada' ) ) {
			global $shortcode_tags;


			if ( $tmp = lct_get_later( __CLASS__, 'shortcode_tags_link' ) ) {
				lct_update_later( __CLASS__, null, 'shortcode_tags_link' );
				$shortcode_tags['link'] = $tmp;
			}


			remove_filter( 'do_shortcode_tag', [ $this, 'do_shortcode_tag' ] );

			$html_encode_fnr = lct_shortcode_html_decode();
			$output          = str_replace( $html_encode_fnr['find'], $html_encode_fnr['replace'], $output );


			if ( strpos( $output, '[' ) !== false ) {
				preg_match_all( '/\[link(([^\]])*?)<a/s', $output, $bad_shortcodes );


				if ( ! empty( $bad_shortcodes[0] ) ) {
					$output = str_replace( $bad_shortcodes[0], '<a', $output );
				}
			}


			$output = do_shortcode( $output );
		}


		return $output;
	}
}
