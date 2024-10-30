<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.01.10
 */
class lct_Avada_shortcodes
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.01.10
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
	 * @since    7.69
	 * @verified 2017.01.23
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
		add_shortcode( zxzu( 'Avada_clear' ), [ $this, 'Avada_clear' ] );
		add_shortcode( zxzu( 'avada_clear' ), [ $this, 'Avada_clear' ] );
		add_shortcode( 'Avada_clear', [ $this, 'Avada_clear' ] );
		add_shortcode( 'avada_clear', [ $this, 'Avada_clear' ] );


		add_shortcode( zxzu( 'social_header' ), [ $this, 'social_header' ] );


		add_shortcode( zxzu( 'social_footer' ), [ $this, 'social_footer' ] );


		add_shortcode( zxzu( 'searchform' ), [ $this, 'searchform' ] );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * [Avada_clear]
	 * Add an fusion-clearfix clear div anywhere you want
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    5.40
	 * @verified 2017.01.31
	 */
	function Avada_clear( $a )
	{
		return lct_Avada_clear( $a );
	}


	/**
	 * [lct_social_header]
	 * Add the Avada social header anywhere you want
	 *
	 * @return string
	 * @since    6.2
	 * @verified 2017.01.10
	 */
	function social_header()
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


		if ( function_exists( 'avada_header_social_links' ) ) {
			$a['r'] = avada_header_social_links();
		}


		return $a['r'];
	}


	/**
	 * [lct_social_footer]
	 * Add the Avada social header anywhere you want
	 *
	 * @return string
	 * @since    7.69
	 * @verified 2018.01.04
	 */
	function social_footer()
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


		if (
			function_exists( 'avada_render_footer_social_icons' )
			&& ! is_admin()
		) {
			ob_start();
			avada_render_footer_social_icons();
			$a['r'] = ob_get_clean();
		}


		return $a['r'];
	}


	/**
	 * [lct_searchform]
	 * Display Avada Search Form
	 *
	 * @return string
	 * @since    2017.34
	 * @verified 2017.05.17
	 */
	function searchform()
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


		$locate = locate_template( 'searchform.php' );


		if ( $locate ) {
			ob_start();
			/** @noinspection PhpIncludeInspection */
			require( $locate );
			$a['r'] = ob_get_clean();
		}


		return $a['r'];
	}
}
