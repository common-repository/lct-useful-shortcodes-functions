<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @verified 2016.11.28
 */
class lct_to_filter
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2016.11.28
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
	 * @since    7.38
	 * @verified 2016.11.28
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


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		if ( lct_wp_admin_non_ajax() ) {
			//only load these if ACF plugin is active
			if ( lct_plugin_active( 'acf' ) ) {
				add_filter( 'to/term_title', [ $this, 'term_title' ], 10, 2 );
			}
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Add the term's org to the title
	 *
	 * @param $term_name
	 * @param $term
	 *
	 * @return string
	 * @since    7.37
	 * @verified 2016.11.28
	 */
	function term_title( $term_name, $term )
	{
		$org = get_field( lct_org(), lct_t( $term ), false );


		if ( $org ) {
			$org = get_the_title( $org );

			$term_name = sprintf( '<strong>%s:</strong> %s', $org, $term_name );
		}


		return $term_name;
	}
}
