<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class PDER_Base
{
	function __construct()
	{
		//Setup WordPress action and filter hooks
		$this->load_hooks();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    2018.14
	 * @verified 2018.03.05
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
		$this->load_files();
		$this->init();


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		if ( lct_wp_admin_non_ajax() ) {
			$this->admin_init();
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * init
	 *
	 * @since    7.3
	 * @verified 2018.02.21
	 */
	function init()
	{
		/**
		 * post_type
		 */
		lct_append_setting( 'post_types', 'ereminder' );
		lct_append_setting( 'post_types_monitored', 'ereminder' );


		/**
		 * register Ereminder Custom Post Type
		 */
		add_action( 'init', [ $this, 'register_post_type' ], 2 );


		/**
		 * register our event to cron
		 */
		add_action( 'PDER_cron_send_reminders', [ 'PDER', 'send_ereminders' ] );
		//For Testing
		//add_action( 'init', [ 'PDER', 'send_ereminders' ] );


		/**
		 * Set the content_type
		 */
		add_filter( 'wp_mail_content_type', [ $this, 'content_type' ] );
	}


	/**
	 * Set email type
	 *
	 * @since    7.3
	 * @verified 2018.02.21
	 */
	function content_type()
	{
		return 'text/html';
	}


	/**
	 * load_files
	 *
	 * @since    7.3
	 * @verified 2018.02.21
	 */
	function load_files()
	{
		/** @noinspection PhpIncludeInspection */
		require_once( PDER_CLASSES . '/PDER.php' );
		/** @noinspection PhpIncludeInspection */
		require_once( PDER_CLASSES . '/PDER_Admin.php' );
		/** @noinspection PhpIncludeInspection */
		require_once( PDER_CLASSES . '/PDER_Utils.php' );
	}


	/**
	 * admin_init
	 *
	 * @since    7.3
	 * @verified 2018.03.05
	 */
	function admin_init()
	{
		new PDER_Admin;
	}


	/**
	 * Register reminder Custom Post Type
	 *
	 * @since    7.3
	 * @verified 2018.02.21
	 */
	function register_post_type()
	{
		$slug      = 'email_reminder';
		$post_type = 'ereminder';


		if ( post_type_exists( $post_type ) ) {
			return;
		}


		$labels = [];
		$labels = lct_post_type_default_labels( $labels, $slug );


		$args = [
			'public'              => false,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'show_in_menu'        => false,
			'publicly_queryable'  => false,
			'supports'            => [ '' ],
		];
		$args = lct_post_type_default_args( $args, $slug, $labels );


		register_post_type( $post_type, $args );
	}
}


/**
 * boot strap
 */
new PDER_Base();
