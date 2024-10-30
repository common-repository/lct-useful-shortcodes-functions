<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2019.08.08
 */
class lct_wp_admin_admin_menu_editor_action
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2019.08.08
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
	 * @verified 2019.08.08
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
			add_action( 'lct/ws_menu_editor', [ $this, 'update_options_to_desired_settings' ] );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Update plugin settings to our defaults
	 * We want to force a couple of settings
	 *
	 * @since    7.62
	 * @verified 2019.08.08
	 */
	function update_options_to_desired_settings()
	{
		if (
			( $name = 'ws_menu_editor' )
			&& ! lct_get_option( 'per_version_updated_' . $name )
			&& ( $option = get_option( $name ) )
		) {
			//Who can access this plugin
			$option['plugin_access'] = 'manage_options';

			//Interface
			$option['hide_advanced_settings'] = false;

			//Editor colour scheme
			$option['ui_colour_scheme'] = 'modern-one';

			//New menu position
			$option['unused_item_position'] = 'relative';

			//Error verbosity level
			$option['error_verbosity'] = 2;

			//Debugging
			$option['security_logging_enabled'] = false;
			$option['force_custom_dashicons']   = false;
			$option['compress_custom_menu']     = false;


			update_option( $name, $option );
			lct_update_option( 'per_version_updated_' . $name, true, false );
		}
	}
}
