<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2016.12.30
 */
class lct_Avada_loaded
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2016.12.30
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
	 * @since    7.62
	 * @verified 2016.12.30
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
		/**
		 * actions
		 */
		add_action( 'widgets_init', [ $this, 'disable_blog_sidebar' ], 11 );


		add_action( 'after_setup_theme', [ $this, 'remove_image_size' ], 11 );


		add_action( 'after_setup_theme', [ $this, 'remove_theme_supports' ], 11 );


		add_action( 'plugins_loaded', [ $this, 'disable_fusion_builder_activate' ] );


		add_action( 'init', [ $this, 'remove_post_types' ], 9 );


		if ( lct_wp_admin_non_ajax() ) {
			add_action( 'lct/op_main/init', [ $this, 'add_op_main_Avada' ] );


			add_action( 'plugins_loaded', [ $this, 'disable_admin_hooks_by_removal' ] );


			if ( ! empty( $_GET['post'] ) ) {
				add_filter( 'gettext', [ $this, 'avada_admin_language' ], 99, 3 );
			}
		}
	}


	/**
	 * Disable Fusion to improve page load speed
	 *
	 * @since    2018.11
	 * @verified 2020.12.07
	 */
	function disable_fusion_builder_activate()
	{
		global $pagenow;

		$disable = false;


		/**
		 * External Check
		 */
		if ( ( $tmp = apply_filters( 'lct/disable_fusion_builder_activate/external_check', null ) ) === null ) {
			$disable = $tmp;


			/**
			 * edit pages
			 */
		} elseif ( in_array( $pagenow, [ 'edit.php', 'edit-tags.php' ] ) ) {
			$disable = true;


			/**
			 * post pages
			 */
		} elseif ( $pagenow === 'post.php' ) {
			$disable              = true;
			$post_types_enabled   = get_option( 'fusion_builder_settings', [ 'post_types' => [ 'post', 'page' ] ] );
			$post_types_enabled   = $post_types_enabled['post_types'];
			$post_types_enabled[] = 'lct_theme_chunk';


			if ( ! empty( $_GET['post'] ) ) {
				$post_type = get_post_type( $_GET['post'] );


				if ( in_array( $post_type, $post_types_enabled ) ) {
					$disable = false;
				}
			} elseif ( ! empty( $_POST['post_type'] ) ) {
				$post_type = $_POST['post_type'];


				if ( in_array( $post_type, $post_types_enabled ) ) {
					$disable = false;
				}
			}


			/**
			 * other pages
			 */
		} elseif (
			$pagenow === 'post-new.php'
			|| (
				$pagenow == 'admin.php'
				&& ! empty( $_GET['page'] )
				&& (
					strpos( $_GET['page'], 'fusion-builder' ) !== false
					|| strpos( $_GET['page'], 'avada' ) !== false
				)
			)
		) {
			$disable = false;
		}


		if ( $disable ) {
			add_filter( 'tgmpa_load', '__return_false', 999 ); //TODO: cs - Need to allow this sometimes - 2/13/2018 8:35 PM

			remove_action( 'after_setup_theme', 'fusion_builder_activate' );
		} elseif ( lct_doing() ) {
			add_filter( 'tgmpa_load', '__return_false', 999 ); //TODO: cs - Need to allow this sometimes - 2/13/2018 8:35 PM
		}
	}


	/**
	 * We have never used this so we should just hide it.
	 *
	 * @since    7.62
	 * @verified 2016.12.30
	 */
	function disable_blog_sidebar()
	{
		if ( ! lct_acf_get_option_raw( 'enable_avada-blog-sidebar' ) ) {
			unregister_sidebar( 'avada-blog-sidebar' );
		}
	}


	/**
	 * Remove image sizes
	 *
	 * @since    5.37
	 * @verified 2018.09.27
	 */
	function remove_image_size()
	{
		if (
			lct_plugin_active( 'acf' )
			&& ( $sizes = lct_acf_get_option_raw( 'disable_image_sizes' ) )
			&& ! empty( $sizes )
		) {
			foreach ( $sizes as $size ) {
				remove_image_size( $size );
			}
		}
	}


	/**
	 * Replace Some language with our own
	 *
	 * @param $translation
	 * @param $text
	 * @param $domain
	 *
	 * @return mixed
	 * @since    2018.8
	 * @verified 2018.01.26
	 */
	function avada_admin_language(
		$translation,
		/** @noinspection PhpUnusedParameterInspection */
		$text,
		$domain
	) {
		if ( $domain === 'fusion-builder' ) {
			$change_array = [
				'Choose the postion of the background image.'  => 'Choose the position of the background image. You can also add classes to adjust the position. Example: "lct_bg_mobi_right_20" will shift the BG image 20% to the right. Available: lct_bg_mobi_right_[1% - 50%] & lct_bg_mobi_left_[1% - 50%]',
				'Choose the position of the background image.' => 'Choose the position of the background image. You can also add classes to adjust the position. Example: "lct_bg_mobi_right_20" will shift the BG image 20% to the right. Available: lct_bg_mobi_right_[1% - 50%] & lct_bg_mobi_left_[1% - 50%]',
			];
			$translation  = str_replace( array_keys( $change_array ), $change_array, $translation );
		}


		return $translation;
	}


	/**
	 * Remove them we don't need 'em
	 *
	 * @since    2018.14
	 * @verified 2018.08.30
	 */
	function remove_post_types()
	{
		if ( lct_get_setting( 'disable_avada_post_types' ) ) {
			lct_remove_filter_like( 'init', 'register_post_types', false, true, 'FusionCore_Plugin' );


			remove_action( 'wp_loaded', 'fusion_element_portfolio' );
			remove_action( 'wp_loaded', 'fusion_element_faq' );
			remove_action( 'wp_loaded', 'fusion_element_fusionslider', 20 );
			remove_action( 'wp_loaded', 'fusion_element_products_slider' );
		}
	}


	/**
	 * Custom ACF options menu
	 *
	 * @since    7.62
	 * @verified 2016.12.30
	 */
	function add_op_main_Avada()
	{
		acf_add_options_sub_page( [
			'title'      => 'Avada Settings',
			'menu'       => 'Avada Settings',
			'slug'       => zxzu( 'acf_op_main_Avada' ),
			'parent'     => zxzu( 'acf_op_main' ),
			'capability' => 'activate_plugins'
		] );
	}


	/**
	 * Remove theme supports dynamically
	 *
	 * @since    2019.14
	 * @verified 2019.06.21
	 */
	function remove_theme_supports()
	{
		/**
		 * Remove Fusion Builder Demos support
		 */
		if (
			! lct_plugin_active( 'acf' )
			|| (
				lct_plugin_active( 'acf' )
				&& ! lct_acf_get_option_raw( 'enable_fb_demos', true )
			)
		) {
			remove_theme_support( 'fusion-builder-demos' );
		}
	}


	/**
	 * Disable Avada hooks that we don't want running
	 *
	 * @since    2019.29
	 * @verified 2019.12.10
	 */
	function disable_admin_hooks_by_removal()
	{
		add_action( 'wp', [ $this, 'disable_by_removal_wp' ], 1 );
	}


	/**
	 * Disable Avada hooks that we don't want running
	 *
	 * @since    2019.29
	 * @verified 2019.12.10
	 */
	function disable_by_removal_wp()
	{
		remove_action( 'wp', [ Avada()->template, 'init' ], 20 );

		remove_action( 'wp', [ fusion_library()->scripts, 'init' ] );
	}
}
