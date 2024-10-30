<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.04.04
 */
class lct_gforms_shortcodes
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.04.04
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
	 * @since    2017.27
	 * @verified 2017.04.04
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
		add_shortcode( zxzu( 'gf_submit' ), [ $this, 'gf_submit' ] );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * [lct_gf_submit]
	 * Shortcode to put a submit button anywhere you feel like it should go
	 *
	 * @att      int id
	 * @att      string text
	 * @att      string class
	 * @att      string full_class
	 * @att      string style
	 * @att      string live
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    5.7
	 * @verified 2017.04.04
	 */
	function gf_submit( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'          => [],
				'id'         => 0,
				'text'       => 'Send',
				'class'      => '',
				'full_class' => [],
				'style'      => '',
				'live'       => '',
			],
			$a
		);


		if ( ! $a['id'] ) {
			return lct_return( $a['r'] );
		}


		/**
		 * class
		 */
		$a['full_class']   = [];
		$a['full_class'][] = zxzu( 'gf_submit_' . $a['id'] );
		$a['full_class'][] = 'gform_button button';

		if ( $a['class'] ) {
			$a['full_class'][] = $a['class'];
		}

		if (
			lct_plugin_active( 'acf' )
			&& $gform_button_custom_class = lct_acf_get_option( 'gform_button_custom_class' )
		) {
			$a['full_class'][] = $gform_button_custom_class;
		}

		$a['full_class'] = sprintf( 'class="%s"', implode( ' ', $a['full_class'] ) );


		/**
		 * style
		 */
		if ( $a['style'] ) {
			$a['style'] = sprintf( 'style="%s"', lct_return( $a['style'] ) );
		}


		/**
		 * live
		 */
		if ( ! empty( $a['live'] ) ) {
			switch ( $a['live'] ) {
				case 'hide':
					/**
					 * #8
					 * @date     0.0
					 * @since    0.0
					 * @verified 2021.08.27
					 */
					do_action( 'lct_wp_footer_style_add', sprintf( '#gform_submit_button_%s{display: none !important;}', $a['id'] ) );
					break;


				default:
			}
		}


		$a['r'][] = sprintf( '<a href="#" %s %s>%s</a>', $a['full_class'], $a['style'], $a['text'] );


		$jq = sprintf(
			"jQuery('.%s').click( function(e) {
				jQuery('#gform_submit_button_%s').click();
				e.preventDefault();
			});",
			zxzu( 'gf_submit_' . $a['id'] ),
			$a['id']
		);


		/**
		 * #3
		 * @date     0.0
		 * @since    0.0
		 * @verified 2021.08.27
		 */
		do_action( 'lct_jq_doc_ready_add', $jq );


		return lct_return( $a['r'] );
	}
}
