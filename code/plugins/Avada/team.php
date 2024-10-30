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
class lct_Avada_team
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


		//bail if team not active
		if ( ! lct_get_setting( 'use_team' ) ) {
			return;
		}


		/**
		 * everytime
		 */


		if ( lct_frontend() ) {
			add_shortcode( zxzu( 'team' ), [ $this, 'team' ] );
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
	 * Allow page ordering for team
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
			&& $q->query_vars['post_type'] == get_cnst( 'team' )
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
	 * Allow fusion builder on team post_type
	 *
	 * @param $post_types
	 *
	 * @return array
	 * @since    7.56
	 * @verified 2016.12.08
	 */
	function fusion_builder_allow( $post_types )
	{
		$post_types[] = get_cnst( 'team' );


		return $post_types;
	}


	/**
	 * [lct_team]
	 * Get a loop of testimonies
	 *
	 * @return string
	 * @since    7.56
	 * @verified 2017.01.06
	 */
	function team()
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


		/**
		 * $related_posts
		 */
		$related_posts = lct_fusion_get_custom_posttype_related_posts_team();


		// If there are related posts, display them.
		if (
			isset( $related_posts )
			&& $related_posts->have_posts()
		) {
			ob_start();
			/** @noinspection PhpIncludeInspection */
			include( lct_get_template_part( 'templates/related-posts', 'lct_team' ) );
			$a['r'] = ob_get_clean();
		}


		return $a['r'];
	}
}
