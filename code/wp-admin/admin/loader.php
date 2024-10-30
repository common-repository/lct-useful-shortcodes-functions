<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2018.08.30
 */
class lct_wp_admin_admin_loader
{
	public $vars = [];


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
	 * @since    7.54
	 * @verified 2018.08.30
	 */
	function load_hooks()
	{
		//bail early if already ran
		if ( lct_did() ) {
			return;
		}


		/**
		 * everytime - admin only
		 */


		if ( lct_wp_admin_non_ajax() ) {
			add_action( 'admin_init', [ $this, 'load_vars' ], 4 );

			add_action( 'admin_init', [ $this, 'load_admin' ], 5 );

			add_action( 'load-edit.php', [ $this, 'load_edit' ], 5 );

			add_action( 'load-post.php', [ $this, 'load_post' ], 5 );

			//add_action( 'admin_init', [ $this, 'load_tools' ], 5 );

			//add_action( 'admin_init', [ $this, 'load_themes' ], 5 );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * This will load up data that will will use throughout this class
	 *
	 * @since    2018.33
	 * @verified 2019.03.11
	 */
	function load_vars()
	{
		global $pagenow, $plugin_page;


		$this->vars['pagenow']           = $pagenow;
		$this->vars['plugin_page']       = $plugin_page;
		$this->vars['acf_options_pages'] = [];
		if ( lct_plugin_active( 'acf' ) ) {
			$this->vars['acf_options_pages'] = lct_acf_get_options_pages();
		}
	}


	/**
	 * This will run whenever admin.php is loaded. Then we can switch back on the plugin and do cool stuff.
	 *
	 * @since    7.16
	 * @verified 2022.09.29
	 */
	function load_admin()
	{
		lct_update_setting( 'acf_is_field_group_main_page', false );
		lct_update_setting( 'acf_is_field_group_editing_page', false );


		/**
		 * Plugin pages
		 */
		if (
			$this->vars['pagenow'] === 'admin.php'
			&& $this->vars['plugin_page']
		) {
			remove_action( 'admin_init', 'fusion_builder_register_layouts' );


			/**
			 * All ACF option pages
			 */
			if ( is_array( $this->vars['acf_options_pages'] ) ) {
				$acf_keys = array_keys( $this->vars['acf_options_pages'] );


				if ( in_array( $this->vars['plugin_page'], $acf_keys ) ) {
					lct_update_setting( 'acf_is_options_page', true );

					add_action( 'admin_enqueue_scripts', [ lct_instances()->wp_admin_admin_admin, 'sticky_admin_sidebar' ] );
				}
			}


			switch ( $this->vars['plugin_page'] ) {
				case zxzu( 'acf_op_main_settings' ):
				case zxzu( 'acf_op_main_advanced' ):
					//Do this just in case the dev_emails field is empty
					lct_acf_get_dev_emails();
					break;


				default:
			}


			/**
			 * edit.php
			 */
		} elseif (
			$this->vars['pagenow'] === 'edit.php'
		) {
			if (
				isset( $_REQUEST['post_type'] )
				&& $_REQUEST['post_type'] === 'acf-field-group'
			) {
				lct_update_setting( 'acf_is_field_group_main_page', true );
				lct_update_setting( 'acf_is_field_group_editing_page', true );
			}


			/**
			 * post.php & post-new.php
			 */
		} elseif (
			$this->vars['pagenow'] === 'post.php'
			|| $this->vars['pagenow'] === 'post-new.php'
		) {
			if ( $this->vars['pagenow'] === 'post-new.php' ) {
				if (
					isset( $_REQUEST['post_type'] )
					&& $_REQUEST['post_type'] === 'acf-field-group'
				) {
					lct_update_setting( 'acf_is_field_group_editing_page', true );
				}
			} else {
				if (
					(
						isset( $_REQUEST['post_type'] )
						&& $_REQUEST['post_type'] === 'acf-field-group'
					)
					|| (
						isset( $_REQUEST['post'] )
						&& get_post_type( $_REQUEST['post'] ) === 'acf-field-group'
					)
				) {
					lct_update_setting( 'acf_is_field_group_editing_page', true );
				}
			}


			/**
			 * Everything Else
			 */
		} else {
			remove_action( 'admin_init', 'fusion_builder_register_layouts' );
		}
	}


	/**
	 * This will run whenever edit.php is loaded. Then we can switch back on the plugin and do cool stuff.
	 *
	 * @since    7.21
	 * @verified 2018.11.09
	 */
	function load_edit()
	{
		if ( isset( $_REQUEST['post_type'] ) ) {
			switch ( $_REQUEST['post_type'] ) {
				case 'acf-field-group':
					lct_update_later( 'allow_not_function', true, 'acf/load_field' );
					lct_update_later( 'allow_not_function', true, 'acf/prepare_field' );

					lct_update_later( 'allow_not_function', true, 'prepare_field_add_class_selector' );
					break;


				default:
			}
		}
	}


	/**
	 * This will run whenever post.php is loaded. Then we can switch back on the plugin and do cool stuff.
	 *
	 * @since    7.21
	 * @verified 2018.08.30
	 */
	function load_post()
	{
		$post_type = null;


		if ( isset( $_REQUEST['post'] ) ) {
			$post_type = get_post_type( $_REQUEST['post'] );
		} elseif ( isset( $_REQUEST['post_type'] ) ) {
			$post_type = $_REQUEST['post_type'];
		}


		if ( $post_type ) {
			switch ( $post_type ) {
				case 'acf-field-group':
					lct_update_later( 'allow_not_function', true, 'acf/load_field' );
					lct_update_later( 'allow_not_function', true, 'acf/prepare_field' );

					lct_update_later( 'allow_not_function', true, 'prepare_field_add_class_selector' );
					break;


				default:
			}
		}
	}


	/**
	 * This will run whenever tools.php is loaded. Then we can switch back on the plugin and do cool stuff.
	 *
	 * @since    7.21
	 * @verified 2018.08.30
	 */
	function load_tools()
	{
		if (
			$this->vars['pagenow'] == 'themes.php'
			&& $this->vars['plugin_page']
		) {
			switch ( $this->vars['plugin_page'] ) {
				case 'none_yet':
					break;


				default:
			}
		}
	}


	/**
	 * This will run whenever themes.php is loaded. Then we can switch back on the plugin and do cool stuff.
	 *
	 * @since    7.56
	 * @verified 2018.08.30
	 */
	function load_themes()
	{
		if (
			$this->vars['pagenow'] == 'themes.php'
			&& $this->vars['plugin_page']
		) {
			switch ( $this->vars['plugin_page'] ) {
				case 'none_yet':
					break;


				default:
			}
		}
	}
}
