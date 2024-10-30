<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * //TODO: cs - This is in BETA and only being used on B&S - 2/17/2019 1:10 PM
 *
 * @property array args
 * @property lct   zxzp
 * @verified 2019.02.17
 */
class lct_features_nav_menu_cache
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2019.02.17
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
	 * @since    2019.2
	 * @verified 2019.02.17
	 */
	function load_hooks()
	{
		//bail early if already ran
		if ( lct_did() ) {
			return;
		}


		/**
		 * //TODO: cs - Make an ACF setting - 2/17/2019 1:02 PM
		 */
		if ( ! lct_get_setting( 'enable_features_nav_menu_cache' ) ) {
			return;
		}


		/**
		 * everytime
		 */
		add_action( 'wp_update_nav_menu', [ $this, 'clear_menu_cache_when_nav_menu_is_saved' ], 10, 2 );
		add_action( 'post_updated', [ $this, 'clear_menu_cache_when_post_is_saved' ], 10, 3 );


		if ( lct_frontend() ) {
			add_filter( 'pre_wp_nav_menu', [ $this, 'get_user_menu_cache' ], 10, 2 );

			add_filter( 'wp_nav_menu', [ $this, 'save_user_menu_cache' ], 999, 2 );
		}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Check if we are allowed to cache the request
	 *
	 * @param stdClass $args
	 *
	 * @return array|bool|WP_Post
	 * @since    2019.2
	 * @verified 2019.02.17
	 */
	function is_eligible_to_cache( $args )
	{
		$r    = true;
		$post = null;


		if (
			! is_user_logged_in()
			|| ! is_object( $args )
			|| empty( $args->theme_location )
			|| ! ( $post = get_post() )
			|| lct_is_wp_error( $post )
		) {
			$r = false;
		}


		if (
			$r
			&& ! lct_is_wp_error( $post )
		) {
			$r = $post;
		} else {
			$r = false;
		}


		return $r;
	}


	/**
	 * Get the cache key
	 *
	 * @param WP_Post|string $post
	 * @param stdClass       $args
	 *
	 * @return string
	 * @since    2019.2
	 * @verified 2019.06.20
	 */
	function cache_key( $post, $args = null )
	{
		$dev = '';
		if ( lct_is_dev() ) {
			$dev = 'dev_';
		}


		$prefix = zxzu( 'cache_' . $dev . 'nav_menu_' );


		if ( $post === 'prefix' ) {
			return $prefix;
		}
		if ( $post === 'force_prefix' ) {
			return zxzu( 'cache_' . 'nav_menu_' );
		}
		if ( $post === 'dev_prefix' ) {
			return zxzu( 'cache_' . 'dev_' . 'nav_menu_' );
		}


		$type = $post->ID;

		if ( is_single( $post ) ) {
			$type = 'single_' . $post->post_type;
		} elseif ( is_tax() ) {
			$type = 'tax_' . get_query_var( 'taxonomy' ) . '_' . get_query_var( 'term' );
		} elseif ( is_archive() ) {
			$type = 'archive_' . $post->post_type;
		}


		$r = $prefix . $type . '_' . $args->theme_location;


		return $r;
	}


	/**
	 * Get the stored menu from the DB
	 *
	 * @param string   $nav_menu
	 * @param stdClass $args
	 *
	 * @return string
	 * @since    2019.2
	 * @verified 2019.02.17
	 */
	function get_user_menu_cache( $nav_menu, $args )
	{
		if ( ! ( $post = $this->is_eligible_to_cache( $args ) ) ) {
			return $nav_menu;
		}


		if ( $cached = get_user_meta( get_current_user_id(), $this->cache_key( $post, $args ), true ) ) {
			$nav_menu = $cached;
		}


		return $nav_menu;
	}


	/**
	 * Store the menu in the DB
	 *
	 * @param string   $nav_menu
	 * @param stdClass $args
	 *
	 * @return string
	 * @since    2019.2
	 * @verified 2019.02.17
	 */
	function save_user_menu_cache( $nav_menu, $args )
	{
		if ( ! ( $post = $this->is_eligible_to_cache( $args ) ) ) {
			return $nav_menu;
		}


		update_user_meta( get_current_user_id(), $this->cache_key( $post, $args ), $nav_menu );


		return $nav_menu;
	}


	/**
	 * Clear all cached nav menus
	 *
	 * @since    2019.2
	 * @verified 2019.06.20
	 */
	function clear_menu_cache()
	{
		global $wpdb;

		$like = $this->cache_key( 'force_prefix' ) . '%';
		$wpdb->query( "DELETE FROM `{$wpdb->usermeta}` WHERE `meta_key` LIKE '{$like}'" );


		$like = $this->cache_key( 'dev_prefix' ) . '%';
		$wpdb->query( "DELETE FROM `{$wpdb->usermeta}` WHERE `meta_key` LIKE '{$like}'" );
	}


	/**
	 * Clear all cached nav menus when a menu is saved
	 *
	 * @unused   param $menu_id
	 * @unused   param $menu_data
	 * @since    2019.11
	 * @verified 2019.06.20
	 */
	function clear_menu_cache_when_nav_menu_is_saved()
	{
		$this->clear_menu_cache();
	}


	/**
	 * Clear all cached nav menus when a post is saved
	 *
	 * @param int     $post_ID
	 * @param WP_Post $post_after
	 *
	 * @unused   param WP_Post $post_before
	 * @since    2019.11
	 * @verified 2019.06.20
	 */
	function clear_menu_cache_when_post_is_saved( $post_ID, $post_after )
	{
		if (
			! ( $check_post_types = apply_filters( 'lct/clear_menu_cache/check_post_types', [ 'post', 'page' ] ) )
			|| ! in_array( $post_after->post_type, $check_post_types )
		) {
			return;
		}


		$args             = [
			'posts_per_page'         => 1,
			'post_type'              => 'nav_menu_item',
			'post_status'            => 'any',
			'post_parent'            => $post_ID,
			'cache_results'          => false, //OK
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		];
		$menu_item_exists = get_posts( $args );


		if ( empty( $menu_item_exists ) ) {
			return;
		}


		$this->clear_menu_cache();
	}
}
