<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @verified 2016.11.04
 */
class lct_features_shortcodes_sort
{
	/**
	 * Get the class running
	 *
	 * @verified 2016.11.04
	 */
	public static function init()
	{
		$class = __CLASS__;
		global ${$class};
		${$class} = new $class;
	}


	/**
	 * Setup action and filter hooks
	 *
	 * @verified 2016.11.04
	 */
	function __construct()
	{
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


		add_shortcode( 'clear', [ $this, 'clear' ] );

		add_shortcode( zxzu( 'auto_logout' ), [ $this, 'auto_logout' ] );

		add_shortcode( zxzu( 'jquery_mask' ), [ $this, 'jquery_mask' ] );

		add_shortcode( zxzu( 'preload' ), [ $this, 'preload' ] );

		add_shortcode( zxzu( 'amp' ), [ $this, 'amp' ] );

		add_shortcode( 'is_user_logged_in', [ $this, 'is_user_logged_in' ] );

		add_shortcode( zxzu( 'get_the_title' ), [ $this, 'get_the_title' ] );

		add_shortcode( zxzu( 'get_the_permalink' ), [ $this, 'get_the_permalink' ] );

		add_shortcode( zxzu( 'url_site' ), [ $this, 'url_site' ] );

		add_shortcode( zxzu( 'get_the_id' ), [ $this, 'get_the_ID' ] );

		add_shortcode( zxzu( 'get_the_date' ), [ $this, 'get_the_date' ] );

		add_shortcode( zxzu( 'get_the_modified_date_time' ), [ $this, 'get_the_modified_date_time' ] );

		add_shortcode( 'homeurl', [ $this, 'homeurl' ] );

		add_shortcode( 'homeurl_non_www', [ $this, 'homeurl_non_www' ] );

		add_shortcode( zxzu( 'current_year' ), [ $this, 'current_year' ] );
	}


	/**
	 * This is here just in case it was called directly in a plugin
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    0.0
	 * @verified 2016.12.08
	 */
	function theme_chunk( $a )
	{
		$theme_chunk = new lct_features_theme_chunk( lct_load_class_default_args() );


		return $theme_chunk->theme_chunk( $a );
	}


	/**
	 * [clear]
	 * add a clear div
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    0.0
	 * @verified 2016.11.04
	 */
	function clear( $a )
	{
		if ( isset( $a['style'] ) && $a['style'] ) {
			$r = '<div class="clear" style="' . $a['style'] . '"></div>';
		} else {
			$r = '<div class="clear"></div>';
		}


		return $r;
	}


	/**
	 * [lct_auto_logout]
	 *
	 * @since    0.0
	 * @verified 2016.11.04
	 */
	function auto_logout()
	{
		if ( is_user_logged_in() ) {
			$time = current_time( 'timestamp', 1 );

			echo '<a id="logout' . $time . '" href="' . wp_logout_url() . '">Logout</a>';

			$script = '<script>
				document.getElementById("logout' . $time . '").click();
			</script>';


			echo $script;
			exit;
		}
	}


	/**
	 * [lct_jquery_mask]
	 * //TODO: cs - Make this better - 11/04/2016 05:33 PM
	 * Add digit mask
	 *
	 * @since    0.0
	 * @verified 2016.11.04
	 */
	function jquery_mask()
	{
		if ( file_exists( lct_get_root_path( 'includes/jquery_mask/jquery_mask.js' ) ) ) {
			$data = file_get_contents( lct_get_root_path( 'includes/jquery_mask/jquery_mask.js' ) );
			echo sprintf( '<%s id="%s">%s</%s>', 'script', zxzu( 'jquery_mask' ), $data, 'script' );
		}
	}


	/**
	 * [lct_preload]
	 * preload an image or set of images
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    0.0
	 * @verified 2021.10.14
	 */
	function preload( $a )
	{
		extract(
			shortcode_atts(
				[
					'css'    => '',
					'js'     => '',
					'images' => '',
				],
				$a
			)
		);

		$time = current_time( 'timestamp', 1 );

		$html = '<div id="' . zxzu( 'preload' ) . '" style="position: fixed;top: 0;left: 0;height: 1px;width: 100px;z-index:9999;opacity: 0.1;"></div>';
		$html .= '<script>';
		$html .= 'jQuery(window).load( function() {';
		$html .= 'setTimeout(function() {';
		if ( ! empty( $css ) ) {
			$tmp = explode( ',', $css );
			foreach ( $tmp as $t ) {
				$html .= 'xhr = new XMLHttpRequest();';
				$html .= 'xhr.open(\'GET\', ' . $t . ');';
				$html .= 'xhr.send(\'\');';
			}
		}

		if ( ! empty( $js ) ) {
			$tmp = explode( ',', $js );
			foreach ( $tmp as $t ) {
				$html .= 'xhr = new XMLHttpRequest();';
				$html .= 'xhr.open(\'GET\', ' . $t . ');';
				$html .= 'xhr.send(\'\');';
			}
		}

		if ( ! empty( $images ) ) {
			$tmp = explode( ',', $images );
			$i   = 1;
			foreach ( $tmp as $t ) {
				$html .= 'jQuery("#' . zxzu( 'preload' ) . '").append(\'<img id="image_' . $time . '_' . $i . '" src="' . $t . '" style="height: 1px;width: 1px;" alt=""></div>\');';
				$i ++;
			}
		}
		$html .= '}, 1000 );';

		$html .= 'setTimeout(function() {';
		$html .= 'jQuery("#' . zxzu( 'preload' ) . '").hide();';
		$html .= '}, 1200 );';
		$html .= '});';
		$html .= '</script>';


		return $html;
	}


	/**
	 * [lct_amp]
	 * Place an & where HTML encoding is sensitive
	 *
	 * @return string
	 * @since    0.0
	 * @verified 2016.11.04
	 */
	function amp()
	{
		return '&';
	}


	/**
	 * [is_user_logged_in][/is_user_logged_in]
	 * Show the content only if the user is logged in.
	 * Or show the content only if the user is logged out, IF the logged_out attr is set to true.
	 *
	 * @param $a
	 * @param $content
	 *
	 * @return array|bool|string
	 * @since    5.36
	 * @verified 2017.02.08
	 */
	function is_user_logged_in( $a, $content = null )
	{
		if (
			(
				! isset( $a['logged_out'] )
				&& ! is_user_logged_in()
			)
			|| (
				isset( $a['logged_out'] )
				&& $a['logged_out'] == 'false'
				&& ! is_user_logged_in()
			)
			|| (
				isset( $a['logged_out'] )
				&& $a['logged_out'] == 'true'
				&& is_user_logged_in()
			)
		) {
			$content = '';
		}


		if ( $content ) {
			$content = do_shortcode( $content );
		}


		return $content;
	}


	/**
	 * [lct_get_the_title]
	 *
	 * @param array $a
	 *
	 * @return string
	 * @since    5.36
	 * @verified 2023.02.10
	 */
	function get_the_title( $a )
	{
		global $post;

		$title = '';


		if ( is_404() ) {
			$title = 'Not Found &mdash; 404';
		} elseif ( isset( $a['id'] ) ) {
			$post_id = $a['id'];
		} elseif ( ! empty( $post ) ) {
			if (
				is_archive()
				&& ( $post_obj = get_post_type_object( $post->post_type ) )
			) {
				$title = $post_obj->labels->archives;
			} else {
				$post_id = $post->ID;
			}
		} else {
			global $term, $taxonomy, $author;


			/**
			 * Check for a term page
			 */
			if (
				! empty( $term )
				&& ! empty( $taxonomy )
				&& ( $term_obj = get_term_by( 'slug', $term, $taxonomy ) )
				&& ! lct_is_wp_error( $term_obj )
			) {
				$title = $term_obj->name;


				/**
				 * Check for an author page
				 */
			} elseif (
				! empty( $author )
				&& ( $user_obj = get_userdata( $author ) )
				&& ! lct_is_wp_error( $user_obj )
			) {
				$title = $user_obj->display_name;


				/**
				 * is_archive()
				 */

			} elseif ( is_archive() ) {
				$title = 'Archive';
			}
		}


		if ( ! empty( $post_id ) ) {
			$title = get_the_title( $post_id );
		}


		return $title;
	}


	/**
	 * [lct_get_the_permalink]
	 *
	 * @param array $a
	 *
	 * @return string
	 * @since    5.36
	 * @verified 2016.11.04
	 */
	function get_the_permalink( $a )
	{
		global $post;

		$permalink = '';


		if ( isset( $a['id'] ) ) {
			$post_id = $a['id'];
		} elseif ( ! empty( $post ) ) {
			$post_id = $post->ID;
		} else {
			$post_id = '';
		}


		if ( $post_id ) {
			$permalink = get_the_permalink( $post_id );
		}


		return $permalink;
	}


	/**
	 * [lct_url_site]
	 *
	 * @return string
	 * @date     2020.11.27
	 * @since    2020.14
	 * @verified 2020.11.27
	 */
	function url_site()
	{
		return lct_url_site();
	}


	/**
	 * [lct_get_the_id]
	 *
	 * @return array|bool|string
	 * @since    5.38
	 * @verified 2023.02.10
	 */
	function get_the_ID()
	{
		global $post;

		$ID = '';


		if ( ! empty( $post ) ) {
			$ID = $post->ID;
		} elseif (
			is_author()
			&& ( $author_id = get_query_var( 'author' ) )
		) {
			$ID = $author_id;
		} elseif (
			! empty( $_REQUEST['afwp_root_post_id'] )
			&& function_exists( 'afwp_acf_decoded_is_type' )
			&& afwp_acf_decoded_is_type( $_REQUEST['afwp_root_post_id'], 'user' )
		) {
			$ID = afwp_acf_decoded_id( $_REQUEST['afwp_root_post_id'] );
		}


		return $ID;
	}


	/**
	 * [lct_get_the_date]
	 *
	 * @param array $a
	 *
	 * @return string
	 * @since    5.38
	 * @verified 2021.10.15
	 */
	function get_the_date( $a )
	{
		global $post;

		$post_id = null;
		$value   = '';


		if ( isset( $a['id'] ) ) {
			$post_id = $a['id'];
		} elseif ( ! empty( $post ) ) {
			$post_id = $post->ID;
		} elseif (
			( $tmp = lct_get_setting( 'root_post_id' ) )
			&& ! empty( $_REQUEST[ $tmp ] )
		) {
			$post_id = $_REQUEST[ $tmp ];
		} else {
			/**
			 * @date     2021.10.15
			 * @since    2021.5
			 * @verified 2021.10.15
			 */
			$post_id = apply_filters( 'lct/get_the_date/post_id', $post_id );
		}


		if ( $post_id ) {
			$value = get_the_date( get_option( 'date_format' ) . ' \@ ' . get_option( 'time_format' ), $post_id );
		}


		return $value;
	}


	/**
	 * [lct_get_the_modified_date_time]
	 *
	 * @att          string format
	 *
	 * @param array $a
	 *
	 * @return string
	 * @since        5.38
	 * @verified     2021.10.15
	 */
	function get_the_modified_date_time( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'      => '',
				'format' => get_option( 'date_format' ) . ' \@ ' . get_option( 'time_format' ),
			],
			$a
		);


		global $post;


		/**
		 * post_id
		 */
		$post_id = null;
		if ( isset( $a['id'] ) ) {
			$post_id = $a['id'];
		} elseif ( ! empty( $post ) ) {
			$post_id = $post->ID;
		} elseif (
			( $tmp = lct_get_setting( 'root_post_id' ) )
			&& ! empty( $_REQUEST[ $tmp ] )
		) {
			$post_id = $_REQUEST[ $tmp ];
		} else {
			/**
			 * @date     2021.10.15
			 * @since    2021.5
			 * @verified 2021.10.15
			 */
			$post_id = apply_filters( 'lct/get_the_modified_date_time/post_id', $post_id );
		}


		if ( $post_id ) {
			$a['r'] = get_the_modified_date( $a['format'], $post_id );
		}


		return $a['r'];
	}


	/**
	 * [homeurl]
	 *
	 * @return string
	 * @since    5.40
	 * @verified 2021.10.14
	 */
	function homeurl()
	{
		$homeurl = parse_url( rtrim( esc_url( get_option( 'home' ) ), '/' ) );


		return $homeurl['host'];
	}


	/**
	 * [homeurl_non_www]
	 *
	 * @return string
	 * @since    5.40
	 * @verified 2021.10.14
	 */
	function homeurl_non_www()
	{
		$homeurl = parse_url( rtrim( esc_url( get_option( 'home' ) ), '/' ) );


		return str_replace( 'www.', '', $homeurl['host'] );
	}


	/**
	 * [lct_current_year]
	 *
	 * @return string
	 * @since    7.10
	 * @verified 2020.09.10
	 */
	function current_year()
	{
		return date( 'Y' );
	}
}
