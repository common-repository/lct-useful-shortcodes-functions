<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.08.24
 */
class lct_wp_admin_admin_update_extras
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.08.24
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
	 * @since    2017.69
	 * @verified 2019.02.13
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
			/**
			 * Filters
			 */
			add_filter( 'lct/editzz_update_files', [ $this, 'editzz_update_files' ], 10, 2 );


			/**
			 * Actions
			 */
			add_action( 'lct/ws_menu_editor', [ $this, 'update_ws_menu_editor' ] );

			//load-{$pagenow}
			add_action( 'load-update-core.php', [ $this, 'remove_crappy_caps' ] );
		}


		//if ( lct_ajax_only() ) {}


		/**
		 * SPECIAL
		 */
		if (
			lct_wp_admin_non_ajax()
			|| lct_doing_cron()
		) {
			/**
			 * Filters
			 */
			add_filter( 'lct/set_version/should_update', [ $this, 'should_update' ], 2 );


			/**
			 * Actions
			 */
			add_action( 'lct/set_version/update', [ $this, 'update_first' ], 2 );

			add_action( 'lct/set_version/update', [ $this, 'old_option_key' ] );

			add_action( 'lct/set_version/update', [ $this, 'force_update_db_values' ] );
		}
	}


	/**
	 * Check if we should update
	 *
	 * @param $update
	 *
	 * @return bool
	 * @since    2017.69
	 * @verified 2017.08.24
	 */
	function should_update( $update )
	{
		/**
		 * Set the loaded editzz_version
		 */
		$editzz_version = $this->get_editzz_version( lct_get_path( 'admin/git/_lct_wp/' ) );
		lct_update_setting( 'editzz_version', $editzz_version );

		/**
		 * Get editzz_version from DB
		 */
		$editzz_version_in_db = lct_get_option( 'editzz_version' );
		lct_update_setting( 'editzz_version_in_db', $editzz_version_in_db );


		/**
		 * Check if we should update
		 */
		if ( $editzz_version != $editzz_version_in_db ) {
			/**
			 * Set the editzz version in DB
			 */
			lct_update_option( 'editzz_version', $editzz_version );


			$update = true;
		}


		return $update;
	}


	/**
	 * Some other's calls we want to enact
	 *
	 * @since    2017.69
	 * @verified 2017.08.24
	 */
	function update_first()
	{
		apply_filters( 'lct/editzz_update_files', false, false );


		/**
		 * #2
		 * @date     0.0
		 * @since    5.35
		 * @verified 2021.08.30
		 */
		do_action( 'lct/ws_menu_editor', true );


		/**
		 * @date     0.0
		 * @since    7.38
		 * @verified 2021.08.30
		 */
		do_action( 'lct/database_check' );
	}


	/**
	 * We renamed the option_key for plugin version, so we need to update it if it's still present
	 *
	 * @since    2017.69
	 * @verified 2017.08.24
	 */
	function old_option_key()
	{
		if ( $version = get_option( lct_us( 'version' ) ) ) {
			lct_update_option( 'version', $version );

			delete_option( lct_us( 'version' ) );
		}
	}


	/**
	 * Force Update DB Values
	 *
	 * @since    2017.69
	 * @verified 2019.02.13
	 */
	function force_update_db_values()
	{
		global $wpdb;


		/**
		 * Force-update some of our LCT options
		 */
		lct_delete_option( 'wpall' );
		lct_delete_option( 'wpdev' );
		lct_delete_option( 'per_version_updated_ws_menu_editor' );
		lct_delete_option( 'per_version_updated_autoload_checker' );
		lct_delete_option( 'per_version_update_login_redirects' );
		lct_delete_option( 'per_version_cron_issues' );


		/**
		 * Delete old options we no longer use
		 */
		lct_delete_option( 'updated_ws_menu_editor' );


		/**
		 * Delete old ACF fields we no longer use
		 */
		$ACF_fields = [
			'options_' . zxzacf( 'choose_a_raw_tag_option' ),
			'options_' . zxzacf( 'default_taxonomy' ),
			'options_' . zxzacf( 'disable_auto_set_user_timezone' ),
			'options_' . zxzacf( 'disable_migrate_silencer' ),
			'options_' . zxzacf( 'fixed_buttons_excluded_pages' ),
			'options_' . zxzacf( 'fixed_buttons_horizontal_bottom_offset' ),
			'options_' . zxzacf( 'fixed_buttons_horizontal_right_offset' ),
			'options_' . zxzacf( 'fixed_buttons_right_offset' ),
			'options_' . zxzacf( 'fixed_buttons_scroll_show' ),
			'options_' . zxzacf( 'fixed_buttons_scroll_show_front' ),
			'options_' . zxzacf( 'fixed_buttons_top_offset' ),
			'options_' . zxzacf( 'mobile_button_class' ),
			'options_' . zxzacf( 'screen_height_scale_1' ),
			'options_' . zxzacf( 'screen_height_scale_2' ),
			'options_' . zxzacf( 'screen_height_scale_3' ),
			'options_' . zxzacf( 'screen_height_scale_4' ),
			'options_' . zxzacf( 'screen_height_scale_5' ),
			'options_' . zxzacf( 'screen_height_scale_6' ),
			'options_' . zxzacf( 'value_formatter' ),
			'options_' . '_validate_email',
		];

		foreach ( $ACF_fields as $ACF_field ) {
			delete_option( lct_pre_us( $ACF_field ) );
			delete_option( $ACF_field );
		}


		/**
		 * Delete old ACF postmeta fields we no longer use
		 */
		$ACF_fields = [
			'_validate_email',
		];

		foreach ( $ACF_fields as $ACF_field ) {
			$wpdb->get_results(
				$wpdb->prepare(
					"DELETE FROM `{$wpdb->postmeta}` WHERE `meta_key` = '%s' OR `meta_key` = '%s'",
					$ACF_field,
					lct_pre_us( $ACF_field )
				)
			);
		}


		/**
		 * Remove Bad user_meta
		 */
		$args  = [
			'fields' => 'ids',
		];
		$users = get_users( $args );


		if ( ! empty( $users ) ) {
			foreach ( $users as $user_id ) {
				$meta = get_user_meta( $user_id, 'closedpostboxes_acf_options_page', true );


				if (
					! empty( $meta )
					&& is_array( $meta )
					&& (
						in_array( 'acf-group_55b95d013ee9d', $meta )
						|| in_array( 'acf-group_55b0076c9b2bd', $meta )
					)
				) {
					delete_user_meta( $user_id, 'closedpostboxes_acf_options_page' );
				}
			}
		}
	}


	/**
	 * Update the ws_menu_editor version (Plugin: admin-menu-editor)
	 *
	 * @since    0.0
	 * @verified 2018.07.11
	 */
	function update_ws_menu_editor()
	{
		$r = false;


		if ( ! function_exists( 'lct_path_site' ) ) {
			return $r;
		}


		$name   = 'ws_menu_editor';
		$editor = get_option( $name );


		if ( $editor ) {
			$version = $this->get_editzz_version( lct_path_site( true ) );


			if ( $version ) {
				$new_title = "Dashboard {$version}";

				if ( $editor['custom_menu']['tree']['index.php']['menu_title'] != $new_title ) {
					$editor['custom_menu']['tree']['index.php']['menu_title'] = $new_title;

					update_option( $name, $editor );

					$r = true;
				}
			}
		}


		return $r;
	}


	/**
	 * Get the current version of editzz
	 *
	 * @param string $path
	 *
	 * @return string
	 * @since    0.0
	 * @verified 2017.08.24
	 */
	function get_editzz_version( $path )
	{
		$version             = '';
		$editzz_scanned_path = array_diff( scandir( $path ), [ '..', '.' ] );
		$editzz_files_path   = preg_grep( "/_editzz-(.*?).txt/", $editzz_scanned_path );


		if ( ! empty( $editzz_files_path ) ) {
			$editzz_files_path = array_values( $editzz_files_path );

			$version = str_replace( [ '_editzz-', '.txt' ], '', $editzz_files_path[0] );
		}


		return $version;
	}


	/**
	 * Pull the newest editzz files and then update the site.
	 * //TODO: cs - Need to add a return that will tell us whether this function was successful or not - 9/19/2015 2:00 PM
	 *
	 * @param bool $current_status
	 * @param bool $force
	 *
	 * @return bool
	 * @since    0.0
	 * @verified 2018.07.11
	 */
	function editzz_update_files(
		/** @noinspection PhpUnusedParameterInspection */
		$current_status = false,
		$force = false
	) {
		if (
			! function_exists( 'lct_path_site' )
			|| (
				! lct_is_dev()
				&& ! $force
			)
		) {
			return false;
		}


		//do the public_html root first
		$repo_path = lct_get_path( 'admin/git/_lct_wp/' );
		$site_path = lct_path_site( true );


		$updated_files = $this->editzz_file_update( $site_path, $repo_path );


		if (
			$updated_files
			|| $force
		) {
			$this->replace_files( $repo_path, $repo_path, $site_path, [ '_editzz-' ] );
		}


		//now do the cPanel root
		$repo_path = lct_get_path( 'admin/git/_lct_root/' );
		$site_path = lct_path_site( true ) . 'apps/___root/';


		if ( file_exists( $site_path ) ) {
			$updated_files = $this->editzz_file_update( $site_path, $repo_path );


			if (
				$updated_files
				|| $force
			) {
				$this->replace_files( $repo_path, $repo_path, $site_path, [ '_editzz-' ] );
			}
		}


		return true;
	}


	/**
	 * Copies content of master editzz to site editzz
	 *
	 * @param string $site_path
	 * @param string $repo_path
	 *
	 * @return bool|string
	 * @since    0.0
	 * @verified 2017.08.24
	 */
	function editzz_file_update( $site_path, $repo_path )
	{
		$r = false;


		$editzz_scanned_site = array_diff( scandir( $site_path ), [ '..', '.' ] );
		$editzz_files        = preg_grep( "/_editzz-(.*?).txt/", $editzz_scanned_site );

		if ( ! empty( $editzz_files ) ) {
			$editzz_files = array_values( $editzz_files );
		}


		$editzz_scanned_repo = array_diff( scandir( $repo_path ), [ '..', '.' ] );
		$editzz_files_repo   = preg_grep( "/_editzz-(.*?).txt/", $editzz_scanned_repo );

		if ( ! empty( $editzz_files_repo ) ) {
			$editzz_files_repo = array_values( $editzz_files_repo );
		}


		if (
			file_exists( $site_path . $editzz_files[0] )
			&& $editzz_files[0] != $editzz_files_repo[0]
		) {
			file_put_contents( $site_path . $editzz_files[0], file_get_contents( $repo_path . $editzz_files_repo[0] ) );


			$version = str_replace( [ '_editzz-', '.txt' ], '', $editzz_files_repo[0] );

			if ( $version ) {
				$r = $version;
			} else {
				$r = true;
			}
		}


		return $r;
	}


	/**
	 * Replace our real files with our template files
	 * //TODO: cs - Check the versions and only update if version is newer - 12/05/2016 12:45 PM
	 *
	 * @param        $dir
	 * @param string $repo_path
	 * @param string $site_path
	 * @param array  $excluded
	 *
	 * @since    7.48
	 * @verified 2017.08.24
	 */
	function replace_files( $dir, $repo_path = '', $site_path = '', $excluded = [] )
	{
		$iterator = new DirectoryIterator( $dir );


		foreach ( $iterator as $info ) {
			if ( $info->isFile() ) {
				//exclude some dirs
				if ( strpos_array( lct_static_cleaner( $info->getPathname() ), $excluded ) ) {
					continue;
				}


				//copy the file
				$file = str_replace( [ '//', $repo_path ], [ '/' ], lct_static_cleaner( $info->getPathname() ) );

				copy( $info->getPathname(), $site_path . $file );
				chmod( $site_path . $file, 0644 );
			} elseif ( ! $info->isDot() ) {
				//exclude some dirs
				if ( strpos_array( lct_static_cleaner( $info->getPathname() ), $excluded ) ) {
					continue;
				}


				$site_real_path = str_replace( [ '//', $repo_path ], [ '/', $site_path ], lct_static_cleaner( $info->getRealPath() ) );

				if ( ! file_exists( $site_real_path ) ) {
					mkdir( $site_real_path, 0755 );
				}


				$this->replace_files( $info->getRealPath(), $repo_path, $site_path, $excluded );
			}
		}
	}


	/**
	 * We have some caps that are 'super' old
	 *
	 * @since    7.24
	 * @verified 2017.08.24
	 */
	function remove_crappy_caps()
	{
		$crappy_caps = [
			'cgm_create_gallery',
			'cgm_insert_gallery',
			'cgm_select_images',
			'cgm_template_customize',
			'cgm_template_save',
			'cgm_upload_images',
			'mgm_root',
			'mgm_home',
			'mgm_members',
			'mgm_member_list',
			'mgm_subscription_options',
			'mgm_coupons',
			'mgm_addons',
			'mgm_roles_capabilities',
			'mgm_content_control',
			'mgm_protection',
			'mgm_downloads',
			'mgm_pages',
			'mgm_custom_fields',
			'mgm_ppp',
			'mgm_post_packs',
			'mgm_post_purchases',
			'mgm_addon_purchases',
			'mgm_payment_settings',
			'mgm_autoresponders',
			'mgm_reports',
			'mgm_sales',
			'mgm_earnings',
			'mgm_projection',
			'mgm_payment_history',
			'mgm_misc_settings',
			'mgm_general',
			'mgm_post_settings',
			'mgm_message_settings',
			'mgm_email_settings',
			'mgm_autoresponder_settings',
			'mgm_rest_API_settings',
			'mgm_tools',
			'mgm_data_migrate',
			'mgm_core_setup',
			'mgm_system_reset',
			'mgm_logs',
			'mgm_dependency',
			'mgm_widget_dashboard_statistics',
			'mgm_widget_dashboard_membership_options',
		];


		$administrator = get_role( 'administrator' );


		if ( ! empty( $administrator ) ) {
			foreach ( $crappy_caps as $cap ) {
				$administrator->remove_cap( $cap );
			}
		}
	}
}
