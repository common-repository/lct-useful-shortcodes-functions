<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @verified 2017.04.20
 */
class lct_features_shortcodes_file_processor
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.04.20
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
	 * @since    2017.33
	 * @verified 2017.04.20
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
		add_shortcode( zxzu( 'css' ), [ $this, 'processor' ] );
		add_shortcode( 'theme_css', [ $this, 'processor' ] );

		add_shortcode( zxzu( 'js' ), [ $this, 'processor' ] );
		add_shortcode( 'theme_js', [ $this, 'processor' ] );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * [lct_css file="{file_name}" write="{whether you want to write the css to the page or just add a link to it}"]
	 * Grab some custom css when this shortcode is called
	 * *
	 * You can also use shortcode:
	 * theme_css
	 *
	 * @param      $a
	 * @param null $content
	 * @param      $shortcode
	 *
	 * @return bool|string
	 */
	function processor(
		$a,
		/** @noinspection PhpUnusedParameterInspection */
		$content = null,
		$shortcode
	) {
		if ( empty( $a['file'] ) ) {
			return false;
		}


		if ( empty( $a['write'] ) ) {
			if ( current_user_can( 'administrator' ) ) {
				$a['write'] = false;
			} else {
				$a['write'] = true;
			}
		}


		$is_url = parse_url( $a['file'] );

		if ( isset( $is_url['host'] ) ) {
			$a['write'] = false;
		}


		$path_theme = lct_path_theme();
		$url_theme  = lct_url_theme();


		switch ( $shortcode ) {
			case 'theme_css':
				$type = 'css';
				$base = '/custom/' . $type . '/';
				$path = $path_theme . $base;
				$url  = $url_theme . $base;
				break;


			case zxzu( 'css' ):
				$type = 'css';
				$base = zxza( '/' . $type . '/' );
				$path = lct_get_root_path( $base );
				$url  = lct_get_root_url( $base );
				break;


			case 'theme_js':
				$type = 'js';
				$base = '/custom/' . $type . '/';
				$path = $path_theme . $base;
				$url  = $url_theme . $base;
				break;


			case zxzu( 'js' ):
				$type = 'js';
				$base = zxza( '/' . $type . '/' );
				$path = lct_get_root_path( $base );
				$url  = lct_get_root_url( $base );
				break;


			default:
				$type = '';
				$base = '';
				$path = '';
				$url  = '';
		}


		//Allow External Files
		if ( strpos( $a['file'], '//' ) !== false ) {
			$url = $a['file'];
		}

		$args   = [
			'file'  => $a['file'],
			'type'  => $type,
			'base'  => $base,
			'path'  => $path,
			'url'   => $url,
			'write' => $a['write']
		];
		$return = $this->shortcode_file_processor( $args );


		return $return;
	}


	/**
	 * Let's get this all processed
	 *
	 * @param $a
	 *
	 * @return bool|string
	 */
	function shortcode_file_processor( $a )
	{
		$return = '';

		$f = [
			'full' => $a['file'] . '.' . $a['type'],
			'min'  => $a['file'] . '.min.' . $a['type'],
		];

		$loc = [
			'min_path'  => $a['path'] . $f['min'],
			'min_url'   => $a['url'] . $f['min'],
			'full_path' => $a['path'] . $f['full'],
			'full_url'  => $a['url'] . $f['full']
		];

		$file_path = $loc['min_path'];
		$file_url  = $loc['min_url'];


		if (
			! file_exists( $loc['min_path'] )
			&& strpos( $a['file'], '//' ) === false
		) {
			if ( ! file_exists( $loc['full_path'] ) ) {
				return false;
			}

			$file_path = $loc['full_path'];
			$file_url  = $loc['full_url'];
		}


		//Allow External Files
		if ( strpos( $a['file'], '//' ) !== false ) {
			$file_url = $a['url'];
		}


		switch ( $a['type'] ) {
			case 'css' :
				$tag = 'style';
				break;


			case 'js' :
				$tag = 'script';
				break;


			default:
				$tag = '';
		}


		if ( $a['write'] == true ) {
			$return .= sprintf( '<%s id="%s_%s">', $tag, $a['type'], $a['file'] );
			$return .= file_get_contents( $file_path );
			$return .= sprintf( '</%s>', $tag );
		} else {
			switch ( $a['type'] ) {
				case 'css' :
					$return .= '<link rel="stylesheet" type="text/css" href="' . $file_url . '">';
					break;


				case 'js' :
					$return .= '<script type="text/javascript" src="' . $file_url . '"></script>';
					break;
			}
		}


		return $return;
	}
}
