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
class lct_acf_op_main
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
	 * @verified 2019.03.11
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
		add_action( 'acf/include_fields', [ $this, 'include_fields' ], 15 );

		add_action( 'acf/include_fields', [ $this, 'include_fields_plugins_Avada' ], 16 );

		add_action( 'acf/include_fields', [ $this, 'include_fields_plugins_gforms' ], 16 );

		add_action( 'acf/include_fields', [ $this, 'include_fields_plugins_wc' ], 16 );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		if ( lct_wp_admin_non_ajax() ) {
			add_action( 'acf/include_fields', [ $this, 'include_fields_wp_admin_non_ajax' ], 20 );


			/**
			 * op_main
			 */
			add_action( 'acf/init', [ $this, 'add_op_main' ], 9 );


			/**
			 * hook in with other apps
			 */
			add_action( 'acf/init', [ $this, 'add_op_main_init' ], 9 );


			/**
			 * op_main_fixes_cleanups
			 */
			add_action( 'acf/init', [ $this, 'add_op_main_fixes_cleanups' ], 9 );

			lct_load_class( "{$this->args['dir']}/op_main_fixes_cleanups.php", 'op_main_fixes_cleanups', [ 'plugin' => $this->args['plugin'] ] );


			/**
			 * op_main_dev
			 */
			add_action( 'acf/init', [ $this, 'add_op_main_dev' ], 9 );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Include our hard coded field groups and fields
	 *
	 * @since    2019.2
	 * @verified 2019.02.16
	 */
	function include_fields()
	{
		lct_include( "{$this->args['dir']}/op_main_settings_groups.php" );
	}


	/**
	 * Include our hard coded field groups and fields for Avada
	 *
	 * @since    2019.2
	 * @verified 2019.02.16
	 */
	function include_fields_plugins_Avada()
	{
		if ( lct_theme_active( 'Avada' ) ) {
			lct_include( 'plugins/Avada/op_main_Avada_groups.php' );
		}
	}


	/**
	 * Include our hard coded field groups and fields for gforms
	 *
	 * @since    2019.2
	 * @verified 2019.02.16
	 */
	function include_fields_plugins_gforms()
	{
		if ( lct_plugin_active( 'gforms' ) ) {
			lct_include( 'plugins/gforms/op_main_gforms_groups.php' );
		}
	}


	/**
	 * Include our hard coded field groups and fields for wc
	 *
	 * @since    2019.2
	 * @verified 2019.02.16
	 */
	function include_fields_plugins_wc()
	{
		if ( lct_plugin_active( 'wc' ) ) {
			lct_include( 'plugins/wc/op_main_wc_groups.php' );
		}
	}


	/**
	 * Include our hard coded field groups and fields
	 *
	 * @since    2019.2
	 * @verified 2019.02.16
	 */
	function include_fields_wp_admin_non_ajax()
	{
		lct_include( "{$this->args['dir']}/op_main_fixes_cleanups_groups.php" );

		lct_include( "{$this->args['dir']}/op_main_dev_groups.php" );
	}


	/**
	 * Custom ACF options menu
	 *
	 * @since    7.62
	 * @verified 2016.12.30
	 */
	function add_op_main_init()
	{
		/**
		 * @date     0.0
		 * @since    0.0
		 * @verified 2021.08.30
		 */
		do_action( 'lct/op_main/init' );
	}


	/**
	 * Custom ACF options menu
	 *
	 * @since    7.62
	 * @verified 2018.02.23
	 */
	function add_op_main()
	{
		acf_add_options_page( [
			'page_title' => zxzb( ' Panel' ),
			'menu_title' => zxzb( ' Panel' ),
			'menu_slug'  => zxzu( 'acf_op_main' ),
			'capability' => 'activate_plugins',
			'redirect'   => true
		] );


		acf_add_options_sub_page( [
			'title'      => 'Main Settings',
			'menu'       => 'Main Settings',
			'slug'       => zxzu( 'acf_op_main_settings' ),
			'parent'     => zxzu( 'acf_op_main' ),
			'capability' => 'activate_plugins'
		] );


		acf_add_options_sub_page( [
			'title'      => 'Shortcodes',
			'menu'       => 'Shortcodes',
			'slug'       => zxzu( 'acf_op_main_shortcodes' ),
			'parent'     => zxzu( 'acf_op_main' ),
			'capability' => 'activate_plugins'
		] );


		acf_add_options_sub_page( [
			'title'      => 'Advanced',
			'menu'       => 'Advanced',
			'slug'       => zxzu( 'acf_op_main_advanced' ),
			'parent'     => zxzu( 'acf_op_main' ),
			'capability' => 'activate_plugins'
		] );
	}


	/**
	 * Custom ACF options menu
	 *
	 * @since    7.62
	 * @verified 2016.12.30
	 */
	function add_op_main_fixes_cleanups()
	{
		acf_add_options_sub_page( [
			'title'      => 'Fixes and Cleanups',
			'menu'       => 'Fixes and Cleanups',
			'slug'       => zxzu( 'acf_op_main_fixes_cleanups' ),
			'parent'     => zxzu( 'acf_op_main' ),
			'capability' => 'activate_plugins'
		] );
	}


	/**
	 * Custom ACF options menu
	 *
	 * @since    7.62
	 * @verified 2016.12.30
	 */
	function add_op_main_dev()
	{
		acf_add_options_sub_page( [
			'title'      => 'Dev',
			'menu'       => 'Dev',
			'slug'       => zxzu( 'acf_op_main_dev' ),
			'parent'     => zxzu( 'acf_op_main' ),
			'capability' => 'activate_plugins'
		] );
	}
}
