<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.02.23
 */
class lct_admin_cron
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.02.23
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
	 * @since    2017.18
	 * @verified 2017.02.24
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
		add_action( 'init', [ $this, 'activate' ] );

		add_action( 'lct_auto_set_lct_api', [ $this, 'auto_set_api' ] );
		//load-{$pagenow}
		add_action( 'load-update-core.php', [ $this, 'auto_set_api' ] );
		//load-{$pagenow}
		add_action( 'load-users.php', [ $this, 'auto_set_api' ] );

		add_action( 'lct_add_default_wp_users', [ $this, 'add_default_wp_users' ] );
		//load-{$pagenow}
		add_action( 'load-update-core.php', [ $this, 'add_default_wp_users' ] );
		//load-{$pagenow}
		add_action( 'load-users.php', [ $this, 'add_default_wp_users' ] );

		add_action( 'lct_emergency_hack_checker', [ $this, 'emergency_hack_checker' ] );
		add_action( 'lct/emergency_hack_checker/unworthy_recheck', [ $this, 'emergency_hack_checker_unworthy_recheck' ] );

		/**
		 * filters
		 */
		add_filter( 'cron_schedules', [ $this, 'add_cron_intervals' ] );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Activate cron events
	 *
	 * @since    2017.18
	 * @verified 2018.08.30
	 */
	function activate()
	{
		/**
		 * Schedule the auto set API cron event
		 */
		if (
			! ( $timestamp = wp_next_scheduled( zxzu( 'auto_set_' . zxzu( 'api' ) ) ) )
			&& //Only if the event is not scheduled yet
			lct_plugin_active( 'acf' )
			&& ! lct_acf_get_option_raw( 'api' ) //Only if we don't have an API set
		) {
			wp_schedule_event( current_time( 'timestamp', 1 ), zxzu( 'one_hour' ), zxzu( 'auto_set_' . zxzu( 'api' ) ) );
		} elseif (
			$timestamp
			&& //Only if the event is already scheduled
			lct_plugin_active( 'acf' )
			&& lct_acf_get_option_raw( 'api' ) //Only if we have an API set
		) {
			wp_unschedule_event( $timestamp, zxzu( 'auto_set_' . zxzu( 'api' ) ) );
		}


		/**
		 * Schedule the Default WP Users cron event
		 */
		//Check for the old One-Hour time and reset it to 10 Minutes immediately
		if (
			( $timestamp = wp_next_scheduled( zxzu( 'add_default_wp_users' ) ) )
			&& //Only if the event is already scheduled
			( $timestamp - current_time( 'timestamp', 1 ) ) > ( 60 * 10 ) //If the event interval is longer than 10 minutes
		) {
			wp_unschedule_event( $timestamp, 'lct_add_default_wp_users' );
		}


		if ( ! wp_next_scheduled( zxzu( 'add_default_wp_users' ) ) )  //Only if the event is not scheduled yet
		{
			wp_schedule_event( current_time( 'timestamp', 1 ), zxzu( 'ten_minutes' ), zxzu( 'add_default_wp_users' ) );
		}


		/**
		 * Schedule the PDER Email Reminder cron event
		 */
		if (
			lct_plugin_active( 'acf' )
			&& //Only if ACF is active
			lct_acf_get_option_raw( 'enable_email-reminder' )
			&& //Only if email-reminder is activated
			! wp_next_scheduled( 'PDER_cron_send_reminders' ) //Only if the event is not scheduled yet
		) {
			wp_schedule_event( current_time( 'timestamp', 1 ), zxzu( 'five_minutes' ), 'PDER_cron_send_reminders' );
		}


		/**
		 * Schedule the Emergency Hack Check cron event
		 */
		$timestamp = wp_next_scheduled( zxzu( 'emergency_hack_checker' ) );
		//For Testing
		//if ( $timestamp )
		//wp_unschedule_event( $timestamp, zxzu( 'emergency_hack_checker' ) );
		if (
			! $timestamp
			&& //Only if the event is not scheduled yet
			defined( 'EMERGENCY_HACK_CHECK' ) //Only if Emergency Hack Check is activated
		) {
			wp_schedule_event( current_time( 'timestamp', 1 ), zxzu( 'five_minutes' ), zxzu( 'emergency_hack_checker' ) );
		} elseif (
			$timestamp
			&& //Only if the event is scheduled
			! defined( 'EMERGENCY_HACK_CHECK' ) //Only if Emergency Hack Check is de-activated
		) { //Only if the event is scheduled
			wp_unschedule_event( $timestamp, zxzu( 'emergency_hack_checker' ) );
		}
	}


	/**
	 * Auto sets the LCT API if the person requesting is authenticated
	 *
	 * @since    7.47
	 * @verified 2018.08.30
	 */
	function auto_set_api()
	{
		if (
			lct_plugin_active( 'acf' )
			&& ! lct_acf_get_option_raw( 'api' )
		) {
			$url  = lct_get_api_url( 'key.php?key=' . $_SERVER['REMOTE_ADDR'] . '&server=' . $_SERVER['HTTP_HOST'] );
			$resp = file_get_contents( $url );
			$api  = json_decode( $resp, true );


			if ( isset( $api['status'] ) ) {
				if (
					$api['status'] === 'success'
					&& $api['key']
				) {
					lct_acf_update_option( 'api', $api['key'] );
				} elseif ( lct_acf_get_option_raw( 'clientzz' ) === '00pimg' ) {
					echo sprintf( $api['response'], $_SERVER['REMOTE_ADDR'] );
				}
			}
		}
	}


	/**
	 * Add a 'one minute' schedule to the existing set, but only when in dev
	 *
	 * @param $schedules
	 *
	 * @return mixed
	 * @since    7.3
	 * @verified 2018.08.23
	 */
	function add_cron_intervals( $schedules )
	{
		if ( lct_is_dev() ) {
			$schedules[ zxzu( 'one_minute' ) ] = [
				'interval' => 60,
				'display'  => zxzb( ' Every Minute (dev)' )
			];
		}


		$schedules[ zxzu( 'five_minutes' ) ] = [
			'interval' => 60 * 5,
			'display'  => zxzb( ' Every Five Minutes' )
		];


		$schedules[ zxzu( 'ten_minutes' ) ] = [
			'interval' => 60 * 10,
			'display'  => zxzb( ' Every Ten Minutes' )
		];


		$schedules[ zxzu( 'one_hour' ) ] = [
			'interval' => 60 * 60,
			'display'  => zxzb( ' Every Hour' )
		];


		return $schedules;
	}


	/**
	 * Update the users allowed to have access to WP for this site
	 * We wanted to automate the addition of users to the WP, so we can stop using the MasterAdmin user
	 *
	 * @since    7.4
	 * @verified 2021.03.15
	 */
	function add_default_wp_users()
	{
		if (
			lct_plugin_active( 'acf' )
			&& ( $client = lct_acf_get_option_raw( 'clientzz' ) )
		) {
			$this->default_users( $client );
			$this->wp_users( $client );
		}
	}


	/**
	 * Reset the userdata for default users
	 *
	 * @param $client
	 *
	 * @since    7.4
	 * @verified 2018.08.30
	 */
	function default_users( $client )
	{
		global $wpdb;

		$resp      = file_get_contents( lct_get_api_url( 'wp_default_users.php?clientzz=' . $client . '&key=' . lct_acf_get_option_raw( 'api' ) ) );
		$api_users = json_decode( $resp, true );


		if ( empty( $api_users ) ) {
			return;
		}


		foreach ( $api_users as $api_user ) {
			/**
			 * Only continue if user is active
			 */
			if (
				isset( $api_user['status'] )
				&& $api_user['status'] === 'delete'
			) {
				continue;
			}


			/**
			 * Get the info for the user stored in the DB
			 */
			$user_results = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT *
						FROM {$wpdb->users}
						WHERE user_login = %s
						LIMIT 1",
					$api_user['user_login']
				)
			);


			/**
			 * Only continue if the user is in the DB already
			 */
			if ( ! empty( $user_results ) ) {
				$userdata = get_user_by( 'login', $user_results->user_login );


				/**
				 * Only continue if the $userdata is returned
				 */
				if (
					empty( $userdata )
					|| strpos( $user_results->user_email, 'deactivated' ) !== false
				) {
					continue;
				}


				/**
				 * Update the case of the user_login
				 */
				if ( $userdata->user_login !== $api_user['user_login'] ) {
					$wpdb->query(
						$wpdb->prepare(
							"UPDATE {$wpdb->users} 
								SET user_login = %s 
								WHERE ID = %d",
							$api_user['user_login'],
							$userdata->ID
						)
					);
				}


				/**
				 * Deactivate MasterAdmin & Blogger
				 */
				if (
					in_array( $userdata->user_login, [ 'MasterAdmin', 'blogger', 'user' ] )
					&& (
						$userdata->user_pass !== $api_user['user_pass']
						|| $userdata->user_email !== $api_user['user_email']
					)
				) {
					$wpdb->query(
						$wpdb->prepare(
							"UPDATE {$wpdb->users} 
								SET user_pass = %s, 
								user_email = %s 
								WHERE ID = %d",
							$api_user['user_pass'],
							$api_user['user_email'],
							$userdata->ID
						)
					);


					/**
					 * Change the email if needed
					 */
				} elseif ( $userdata->user_email !== $api_user['user_email'] ) {
					$userdata->user_email = $api_user['user_email'];
					wp_update_user( $userdata );
				}


				/**
				 * Change display_name & nickname if needed
				 */
				if (
					! empty( $api_user['display_name'] )
					&& $userdata->display_name !== $api_user['display_name']
				) {
					$userdata->nickname     = $api_user['display_name'];
					$userdata->display_name = $api_user['display_name'];
					wp_update_user( $userdata );
				}
			}
		}
	}


	/**
	 * Update the users list in the database
	 *
	 * @param string $client
	 *
	 * @since    7.4
	 * @verified 2021.03.15
	 */
	function wp_users( $client )
	{
		global $wpdb;

		$resp      = file_get_contents( lct_get_api_url( 'wp_users.php?clientzz=' . $client . '&key=' . lct_acf_get_option_raw( 'api' ) ) );
		$api_users = json_decode( $resp, true );


		if ( empty( $api_users ) ) {
			return;
		}


		foreach ( $api_users as $user_login => $api_userdata ) {
			/**
			 * Get the info for the user stored in the DB
			 */
			$user_results = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT *
						FROM {$wpdb->users}
						WHERE user_login = %s
						LIMIT 1",
					$user_login
				)
			);


			/**
			 * Only continue if the user is in the DB already
			 */
			if ( ! empty( $user_results ) ) {
				/**
				 * Fix Name bug
				 */
				if ( strpos( $user_results->display_name, '(DEACTIVATED) (DEACTIVATED)' ) === 0 ) {
					$userdata = get_user_by( 'login', $user_login );


					if ( lct_is_wp_error( $userdata ) ) {
						return;
					}


					/**
					 * Update the user's display name
					 */
					$userdata->first_name   = '(DEACTIVATED) ' . $api_userdata['first_name'];
					$userdata->display_name = $userdata->first_name . ' ' . $userdata->last_name;
					$userdata->nickname     = $userdata->first_name . ' ' . $userdata->last_name;
					wp_update_user( $userdata );
				}


				/**
				 * Delete the user
				 */
				if ( $api_userdata['status'] === 'delete' ) {
					$this->delete_user( $user_login );


					/**
					 * Deactivate the user
					 */
				} elseif (
					$api_userdata['status'] === 'inactive'
					&& strpos( $user_results->user_email, 'deactivated' ) === false
				) {
					$this->deactivate_user( $user_login );


					/**
					 * Reactivate the user
					 */
				} elseif (
					$api_userdata['status'] === 'active'
					&& strpos( $user_results->user_email, 'deactivated' ) !== false
				) {
					$this->reactivate_user( $user_login, $api_userdata );


					/**
					 * Check the userdata integrity
					 */
				} elseif ( $api_userdata['status'] === 'active' ) {
					/**
					 * Only continue if the $userdata is returned
					 */
					if (
						( $userdata = get_user_by( 'login', $user_results->user_login ) )
						&& ! empty( $userdata )
					) {
						/**
						 * Update the case of the user_login
						 */
						if ( $userdata->user_login !== $user_login ) {
							$wpdb->query(
								$wpdb->prepare(
									"UPDATE {$wpdb->users} 
										SET user_login = %s 
										WHERE ID = %d",
									$user_login,
									$api_userdata->ID
								)
							);
						}
					}
				}


				/**
				 * Create a new user, because they don't exist yet
				 */
			} elseif ( $api_userdata['status'] === 'active' ) {
				$this->add_user( $user_login, $api_userdata );
			}
		}
	}


	/**
	 * Add a new user
	 * //TODO: cs - Fix the metaboxes - 08/08/2016 10:20 AM
	 *
	 * @param $user_login
	 * @param $api_userdata
	 *
	 * @since    7.4
	 * @verified 2019.11.22
	 */
	function add_user( $user_login, $api_userdata )
	{
		global $wpdb;


		if ( ! $api_userdata['pass_hash'] ) {
			return;
		}


		$args    = [
			'user_login'   => $user_login,
			'user_email'   => $api_userdata['email'],
			'first_name'   => $api_userdata['first_name'],
			'last_name'    => $api_userdata['last_name'],
			'nickname'     => $api_userdata['first_name'] . ' ' . $api_userdata['last_name'],
			'display_name' => $api_userdata['first_name'] . ' ' . $api_userdata['last_name'],
		];
		$user_id = wp_insert_user( $args );


		if ( lct_is_wp_error( $user_id ) ) {
			return;
		}


		$userdata = get_userdata( $user_id );


		/**
		 * Set the role
		 */
		if ( strpos( $api_userdata['role'], ',' ) !== false ) {
			$roles = explode( ',', $api_userdata['role'] );


			$userdata->set_role( $roles[0] );
			unset( $roles[0] );


			foreach ( $roles as $role ) {
				$userdata->add_role( $role );
			}
		} else {
			$userdata->set_role( $api_userdata['role'] );
		}


		/**
		 * Set the meta
		 */
		update_user_meta( $user_id, '_yoast_wpseo_profile_updated', current_time( 'timestamp' ) );
		update_user_meta( $user_id, 'acf_user_settings', unserialize( 'a:0:{}' ) );
		update_user_meta( $user_id, 'admin_color', 'ocean' );
		update_user_meta( $user_id, 'ame_show_hints', unserialize( 'a:3:{s:17:"ws_sidebar_pro_ad";b:0;s:16:"ws_whats_new_120";b:0;s:24:"ws_hint_menu_permissions";b:1;}' ) );
		update_user_meta( $user_id, 'closedpostboxes_dashboard', unserialize( 'a:0:{}' ) );
		update_user_meta( $user_id, 'closedpostboxes_nav-menus', unserialize( 'a:0:{}' ) );
		update_user_meta( $user_id, 'closedpostboxes_page', unserialize( 'a:0:{}' ) );
		update_user_meta( $user_id, 'closedpostboxes_post', unserialize( 'a:0:{}' ) );
		update_user_meta( $user_id, 'closedpostboxes_toplevel_page_itsec', unserialize( 'a:0:{}' ) );
		update_user_meta( $user_id, 'closedpostboxes_toplevel_page_maintenance', unserialize( 'a:3:{i:0;s:21:"mymetabox_revslider_0";i:1;s:16:"promo-our-themes";i:2;s:13:"promo-content";}' ) );
		update_user_meta( $user_id, 'comment_shortcuts', 'true' );
		update_user_meta( $user_id, 'description', '' );
		update_user_meta( $user_id, 'dismissed_lc_pointers', 'wp330_toolbar,wp330_saving_widgets,wp340_choose_image_from_library,wp340_customize_current_theme_link,wp350_media,wp360_revisions' );
		update_user_meta( $user_id, 'dismissed_wp_pointers', 'wp390_widgets,wp350_media,wp360_revisions,wp410_dfw' );
		update_user_meta( $user_id, 'doctitle', '' );
		update_user_meta( $user_id, 'edit_comments_per_page', '20' );
		update_user_meta( $user_id, 'edit_lct_theme_chunk_per_page', '100' );
		update_user_meta( $user_id, 'edit_page_per_page', '100' );
		update_user_meta( $user_id, 'edit_post_per_page', '20' );
		update_user_meta( $user_id, 'edit_stream_per_page', '50' );
		update_user_meta( $user_id, 'headline', '' );
		update_user_meta( $user_id, 'intro_text', '' );
		update_user_meta( $user_id, 'itsec-settings-view', 'list' );
		update_user_meta( $user_id, 'layout', '' );
		update_user_meta( $user_id, 'lc_dashboard_quick_press_last_post_id', '50070' );
		update_user_meta( $user_id, 'lc_media_library_mode', 'list' );
		update_user_meta( $user_id, 'lc_r_tru_u_x', unserialize( 'a:2:{s:2:"id";i:0;s:7:"expires";i:1461004014;}' ) );
		update_user_meta( $user_id, 'lc_user-settings', 'editor=html&libraryContent=browse&imgsize=thumbnail&urlbutton=none&ed_size=588&posts_list_mode=list' );
		update_user_meta( $user_id, 'lc_user-settings-time', current_time( 'timestamp' ) );
		update_user_meta( $user_id, 'manageedit-pagecolumnshidden', unserialize( 'a:0:{}' ) );
		update_user_meta( $user_id, 'manageedit-postcolumnshidden', unserialize( 'a:1:{i:0;s:4:"tags";}' ) );
		update_user_meta( $user_id, 'managenav-menuscolumnshidden', unserialize( 'a:3:{i:0;s:11:"css-classes";i:1;s:3:"xfn";i:2;s:11:"description";}' ) );
		update_user_meta( $user_id, 'managetoplevel_page_wp_streamcolumnshidden', unserialize( 'a:1:{i:0;s:2:"id";}' ) );
		update_user_meta( $user_id, 'meta-box-order_dashboard', unserialize( 'a:4:{s:6:"normal";s:65:"dashboard_quick_press,rg_forms_dashboard,wpseo-dashboard-overview";s:4:"side";s:38:"dashboard_right_now,dashboard_activity";s:7:"column3";s:0:"";s:7:"column4";s:34:"dashboard_primary,themefusion_news";}' ) );
		update_user_meta( $user_id, 'meta-box-order_toplevel_page_maintenance', unserialize( 'a:3:{s:6:"normal";s:57:"mymetabox_revslider_0,maintenance-general,maintenance-css";s:8:"advanced";s:0:"";s:4:"side";s:70:"maintenance-excludepages,promo-extended,promo-our-themes,promo-content";}' ) );
		update_user_meta( $user_id, 'meta_description', '' );
		update_user_meta( $user_id, 'meta_keywords', '' );
		update_user_meta( $user_id, 'metaboxhidden_acf_options_page', unserialize( 'a:0:{}' ) );
		update_user_meta( $user_id, 'metaboxhidden_dashboard', unserialize( 'a:2:{i:0;s:21:"dashboard_quick_press";i:1;s:16:"themefusion_news";}' ) );
		update_user_meta( $user_id, 'metaboxhidden_nav-menus', unserialize( 'a:1:{i:0;s:12:"add-post_tag";}' ) );
		update_user_meta( $user_id, 'metaboxhidden_toplevel_page_itsec', unserialize( 'a:0:{}' ) );
		update_user_meta( $user_id, 'metaboxhidden_toplevel_page_maintenance', unserialize( 'a:1:{i:0;s:21:"mymetabox_revslider_0";}' ) );
		update_user_meta( $user_id, 'nav_menu_recently_edited', '500' );
		update_user_meta( $user_id, 'noarchive', '' );
		update_user_meta( $user_id, 'nofollow', '' );
		update_user_meta( $user_id, 'noindex', '' );
		update_user_meta( $user_id, 'rich_editing', 'true' );
		update_user_meta( $user_id, 'screen_layout_dashboard', '2' );
		update_user_meta( $user_id, 'screen_layout_lct_theme_chunk', '2' );
		update_user_meta( $user_id, 'screen_layout_page', '2' );
		update_user_meta( $user_id, 'screen_layout_post', '2' );
		update_user_meta( $user_id, 'show_admin_bar_front', 'true' );
		update_user_meta( $user_id, 'show_welcome_panel', '0' );
		update_user_meta( $user_id, 'stream_live_update_records', 'on' );
		update_user_meta( $user_id, 'tgmpa_dismissed_notice', '1' );
		update_user_meta( $user_id, 'use_ssl', '0' );
		update_user_meta( $user_id, 'users_per_page', '20' );
		update_user_meta( $user_id, 'wpseo-dismiss-about', 'seen' );
		update_user_meta( $user_id, 'wpseo-dismiss-gsc', 'seen' );
		update_user_meta( $user_id, 'wpseo_dismissed_gsc_notice', '1' );
		update_user_meta( $user_id, 'wpseo_excludeauthorsitemap', '' );
		update_user_meta( $user_id, 'wpseo_ignore_tour', '1' );
		update_user_meta( $user_id, 'wpseo_metadesc', '' );
		update_user_meta( $user_id, 'wpseo_metakey', '' );
		update_user_meta( $user_id, 'wpseo_seen_about_version', '3.3.1' );
		update_user_meta( $user_id, 'wpseo_title', '' );


		/**
		 * Update the password, Must be done last
		 */
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->users} 
						SET user_pass = %s
						WHERE ID = %d",
				$api_userdata['pass_hash'],
				$user_id
			)
		);
	}


	/**
	 * Delete a user
	 *
	 * @param $user_login
	 *
	 * @since    2018.36
	 * @verified 2018.04.05
	 */
	function delete_user( $user_login )
	{
		$master_userdata = get_user_by( 'login', 'MasterAdmin' );
		$userdata        = get_user_by( 'login', $user_login );


		if ( ! lct_is_wp_error( $master_userdata ) ) {
			/**
			 * Update the user's display name
			 */
			require_once( ABSPATH . 'wp-admin/includes/user.php' );


			wp_delete_user( $userdata->ID, $master_userdata->ID );
		} else {
			$this->deactivate_user( $user_login );
		}
	}


	/**
	 * Deactivate a user
	 *
	 * @param $user_login
	 *
	 * @since    2017.18
	 * @verified 2019.03.25
	 */
	function deactivate_user( $user_login )
	{
		global $wpdb;

		$userdata = get_user_by( 'login', $user_login );


		if ( lct_is_wp_error( $userdata ) ) {
			return;
		}


		/**
		 * Update the user's display name
		 */
		$userdata->first_name   = '(DEACTIVATED) ' . $userdata->first_name;
		$userdata->display_name = $userdata->first_name . ' ' . $userdata->last_name;
		$userdata->nickname     = $userdata->first_name . ' ' . $userdata->last_name;
		wp_update_user( $userdata );


		/**
		 * Reset the role
		 */
		$userdata->set_role( 'subscriber' );


		/**
		 * Reset the PW
		 */
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->users} 
					SET user_pass = %s, 
					user_email = %s 
					WHERE ID = %d",
				lct_rand(),
				lct_rand( 'deactivated@', '.com' ),
				$userdata->ID
			)
		);
	}


	/**
	 * Reactivate a user
	 *
	 * @param $user_login
	 * @param $api_userdata
	 *
	 * @since    2017.18
	 * @verified 2018.04.01
	 */
	function reactivate_user( $user_login, $api_userdata )
	{
		global $wpdb;

		$userdata = get_user_by( 'login', $user_login );


		if ( lct_is_wp_error( $userdata ) ) {
			return;
		}


		/**
		 * Update the user's display name
		 */
		$userdata->first_name   = $api_userdata['first_name'];
		$userdata->last_name    = $api_userdata['last_name'];
		$userdata->display_name = $userdata->first_name . ' ' . $userdata->last_name;
		$userdata->nickname     = $userdata->first_name . ' ' . $userdata->last_name;
		wp_update_user( $userdata );


		/**
		 * Reset the role
		 */
		$userdata->set_role( $api_userdata['role'] );


		/**
		 * Reset the PW
		 */
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->users} 
					SET user_pass = %s, 
					user_email = %s 
					WHERE ID = %d",
				$api_userdata['pass_hash'],
				$api_userdata['email'],
				$userdata->ID
			)
		);
	}


	/**
	 * Check for a hacked git repo
	 * //ADD to wp-config.php: define( 'EMERGENCY_HACK_CHECK', true );
	 *
	 * @since    2019.29
	 * @verified 2019.12.22
	 */
	function emergency_hack_checker()
	{
		$checker = lct_get_option( 'per_version_emergency_hack_checker', [] );


		/**
		 * Do the check
		 */
		if (
			empty( $checker['not_working'] )
			|| ! get_transient( zxzu( 'emergency_hack_checker_limiter' ) )
			|| $this->status_worthy_commit()
		) {
			if ( lct_is_dev_or_sb() ) {
				lct_delete_option( 'per_version_emergency_hack_checker' );


				return;
			}


			$worthy = false;


			set_transient( zxzu( 'emergency_hack_checker_limiter' ), 1, ( HOUR_IN_SECONDS * 6 ) );


			if ( $this->status_worthy_commit() ) {
				$worthy = true;
			} else {
				/**
				 * @date     0.0
				 * @since    2019.29
				 * @verified 2021.08.27
				 */
				do_action( 'lct/emergency_hack_checker/unworthy_recheck' );


				if ( $this->status_worthy_commit() ) {
					$worthy = true;
				}
			}


			if ( $worthy ) {
				if ( ! empty( $checker['not_working'] ) ) {
					$url = get_option( 'home' );


					$args = [
						'from_name' => zxzb( ' Emergency Hack Checker' ),
						'subject'   => sprintf( '%s was fixed.', $url ),
						'message'   => sprintf( '<a href="%1$s">%1$s</a> was fixed.', $url ),
					];
					lct_quick_send_email( $args );


					$checker['email_sent'] = true;
				}


				$checker = [];
				lct_delete_option( 'per_version_emergency_hack_checker' );
			} else {
				$checker['not_working'] = true;
			}
		}


		/**
		 * Check has already been determined to have failed
		 */
		if ( ! empty( $checker['not_working'] ) ) {
			if ( empty( $checker['email_sent'] ) ) {
				$url = get_option( 'home' );


				$args = [
					'from_name' => zxzb( ' Emergency Hack Checker' ),
					'subject'   => sprintf( '%s may have been hacked.', $url ),
					'message'   => sprintf( '<a href="%1$s">%1$s</a> may have been hacked.', $url ),
				];
				lct_quick_send_email( $args );


				$checker['email_sent'] = true;
			}


			lct_update_option( 'per_version_emergency_hack_checker', $checker, true );
		}
	}


	/**
	 * Check for a hacked git repo
	 *
	 * @since    2019.29
	 * @verified 2022.08.04
	 */
	function status_worthy_commit()
	{
		//load our git library
		require_once( lct_get_root_path( 'includes/Git.php/Git.php' ) );
		$repo = Git::open( lct_path_site() );


		//update the git path if we are on our dev
		if ( lct_is_dev() ) {
			Git::set_bin( 'C:\wamp\_apps\git\bin\git.exe' );
		}


		if ( ! ( $status = $repo->status() ) ) {
			$status = 'none';
		}


		//only if we have a status worthy of a commit
		if (
			strpos( $status, 'nothing to commit, working directory clean' ) !== false
			|| strpos( $status, 'nothing to commit (working directory clean)' ) !== false
			|| strpos( $status, 'nothing to commit, working tree clean' ) !== false
			|| strpos( $status, 'nothing to commit (working tree clean)' ) !== false
		) {
			return true;
		} else {
			lct_debug_to_error_log( $status );
		}


		return false;
	}


	/**
	 * Check for a hacked git repo
	 *
	 * @since    2019.29
	 * @verified 2020.01.10
	 */
	function emergency_hack_checker_unworthy_recheck()
	{
		shell_exec( 'rm -rf ' . lct_path_site() . '/apps/index.php' );

		shell_exec( 'git co ' . lct_path_site() . '/wp-admin/' );
		shell_exec( 'git co ' . lct_path_site() . '/wp-includes/' );
		shell_exec( 'git co ' . lct_path_site() . '/x/wp-admin/' );
		shell_exec( 'git co ' . lct_path_site() . '/x/wp-includes/' );

		shell_exec( 'git co ' . lct_path_site() . '/x/index.php' );
		shell_exec( 'git co ' . lct_path_site() . '/x/wp-config.php' );
		shell_exec( 'git co ' . lct_path_site() . '/index.php' );
		shell_exec( 'git co ' . lct_path_site() . '/wp-config.php' );

		shell_exec( 'git co ' . lct_path_site() . '/x/wp-blog-header.php' );
		shell_exec( 'git co ' . lct_path_site() . '/x/wp-settings.php' );
		shell_exec( 'git co ' . lct_path_site() . '/wp-blog-header.php' );
		shell_exec( 'git co ' . lct_path_site() . '/wp-settings.php' );


		/**
		 * Put in mu-plugins
		 */
		/*
		add_action( 'lct/emergency_hack_checker/unworthy_recheck', 'lca_emergency_hack_checker_unworthy_recheck' );
		* Check for a hacked git repo
		* @since    2019.29
		* @verified 2019.12.22
		function lca_emergency_hack_checker_unworthy_recheck() {
			shell_exec( 'rm -rf ' . lct_path_site() . '/apps/index.php' );

			shell_exec( 'git co ' . lct_path_site() . '/index.php' );
		}
		*/
	}
}
