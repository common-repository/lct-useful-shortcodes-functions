<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2016.12.15
 */
class lct_Avada_testimony
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2016.12.15
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
	 * @since    7.56
	 * @verified 2016.12.15
	 */
	function load_hooks()
	{
		//bail early if already ran
		if ( lct_did() ) {
			return;
		}


		//bail if testimony not active
		if ( ! lct_get_setting( 'use_testimony' ) ) {
			return;
		}


		/**
		 * everytime
		 */


		if ( lct_frontend() ) {
			add_shortcode( zxzu( 'testimony' ), [ $this, 'testimony' ] );
		}


		//if ( lct_wp_admin_all() ) {}


		if ( lct_wp_admin_non_ajax() ) {
			/**
			 * actions
			 */
			add_action( 'pre_get_posts', [ $this, 'allow_page_ordering' ] );

			add_action( 'wp', [ $this, 'remove_allow_page_ordering' ] );


			/**
			 * filters
			 */
			add_filter( 'fusion_builder_allowed_post_types', [ $this, 'fusion_builder_allow' ] );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Allow page ordering for testimony
	 *
	 * @param $q WP_Query
	 *
	 * @since    7.56
	 * @verified 2017.04.27
	 */
	function allow_page_ordering( $q )
	{
		if (
			is_admin()
			&& $q->is_main_query()
			&& isset( $q->query_vars['post_type'] )
			&& $q->query_vars['post_type'] == get_cnst( 'testimony' )
		) {
			if ( ! $q->query_vars['orderby'] ) {
				$q->query_vars['orderby'] = 'menu_order';
			}

			if ( ! $q->query_vars['order'] ) {
				$q->query_vars['order'] = 'asc';
			}


			$this->remove_allow_page_ordering();
		}
	}


	/**
	 * Remove the allow_page_ordering pre_get_posts hook
	 *
	 * @since    2017.34
	 * @verified 2017.04.27
	 */
	function remove_allow_page_ordering()
	{
		remove_action( 'pre_get_posts', [ $this, 'allow_page_ordering' ] );
	}


	/**
	 * Allow fusion builder on testimony post_type
	 *
	 * @param $post_types
	 *
	 * @return array
	 * @since    7.56
	 * @verified 2016.12.08
	 */
	function fusion_builder_allow( $post_types )
	{
		$post_types[] = get_cnst( 'testimony' );


		return $post_types;
	}


	/**
	 * [lct_testimony]
	 * Get a loop of testimonies
	 *
	 * @att      string template
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    7.56
	 * @verified 2017.05.18
	 */
	function testimony( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'        => [],
				'template' => ''
			],
			$a
		);


		if ( $a['template'] ) {
			$template_content = lct_theme_chunk( [ 'id' => $a['template'], 'dont_check' => true, 'dont_sc' => true ] );


			$args        = [
				'posts_per_page'         => - 1,
				'post_type'              => get_cnst( 'testimony' ),
				'orderby'                => 'menu_order',
				'order'                  => 'ASC',
				'cache_results'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			];
			$testimonies = get_posts( $args );


			if ( ! lct_is_wp_error( $testimonies ) ) {
				foreach ( $testimonies as $testimony ) {
					/**
					 * Find & Replace
					 */
					$find_replace = [
						'{post_id}' => $testimony->ID,
						'{title}'   => $testimony->post_title,
						'{content}' => $testimony->post_content,
					];
					$fnr          = lct_create_find_and_replace_arrays( $find_replace );


					$a['r'][] = do_shortcode( str_replace( $fnr['find'], $fnr['replace'], $template_content ) );
				}
			}
		}


		return lct_return( $a['r'] );
	}
}
