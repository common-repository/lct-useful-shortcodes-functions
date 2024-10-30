<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'lct_acf_admin' ) ) :


	/**
	 * lct_acf_admin
	 *
	 * @verified 2023.11.27
	 */
	class lct_acf_admin
	{
		/**
		 * __construct
		 * This function will set up the class functionality
		 *
		 * @verified 2023.11.27
		 */
		function __construct()
		{
			/**
			 * Setup WordPress action and filter hooks
			 */
			$this->load_hooks();
		}


		/**
		 * load_hooks
		 * Setup WordPress action and filter hooks
		 *
		 * @since    7.39
		 * @verified 2017.06.08
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
			add_action( 'init', [ $this, 'maintenance_mode' ], 6 );

			add_action( 'init', [ $this, 'lock_site_edits' ], 6 );

			add_action( 'init', [ $this, 'disable_connection_services' ] );

			add_action( 'acf/render_fields', [ $this, 'unset_new_post_setting' ] );

			add_action( 'acf/fields/google_map/api', [ $this, 'set_google_map_api' ] );


			/**
			 * filters
			 */
			add_filter( 'acf/location/rule_match/options_page', [ $this, 'register_rule_match_options_page' ], 10, 3 );

			add_filter( 'acf/location/rule_match/post_type', [ $this, 'register_rule_match_post_type' ], 999, 3 );

			add_filter( 'acf/location/rule_match/comment', [ $this, 'register_rule_match_comment' ], 999, 3 );

			add_filter( 'acf/location/rule_match/' . lct_org(), [ $this, 'register_rule_match_lct_org' ], 10, 3 );

			add_filter( 'acf/validate_form', [ $this, 'set_new_post_setting' ] );

			add_filter( 'acf/fields/post_object/query', [ $this, 'update_status_filter' ], 10, 3 );

			add_filter( 'acf/fields/post_object/query', [ $this, 'update_posts_per_page' ], 10, 3 );

			add_filter( 'acf/prepare_field_group_for_export', [ $this, 'add_menu_order_to_fields' ] );

			add_filter( 'acf/acf_get_hidden_input/attrs', [ $this, 'unique_id' ] );


			if ( lct_frontend() ) {
				/**
				 * actions
				 */
				add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_styles' ] );
				add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
				add_action( 'wp_enqueue_scripts', [ $this, 'always_load_google_fonts' ] );
				add_action( 'wp_enqueue_scripts', [ $this, 'always_load_typekit' ] );

				add_action( 'lct_acf_single_load_google_fonts', [ $this, 'single_load_google_fonts' ], 10, 1 );

				add_action( 'lct_acf_single_load_typekit', [ $this, 'single_load_typekit' ], 10, 1 );

				add_action( 'wp_footer', [ $this, 'wp_footer_get_user_agent_info' ], 99999 );

				add_action( 'get_header', [ $this, 'acf_form_head' ] );

				add_action( 'lct_get_user_agent_info', [ $this, 'get_user_agent_info' ], 10, 2 );


				/**
				 * filters
				 */
				add_filter( 'script_loader_src', [ $this, 'remove_script_version' ], 15, 1 );

				add_filter( 'style_loader_src', [ $this, 'remove_script_version' ], 15, 1 );

				add_filter( 'show_admin_bar', [ $this, 'show_admin_bar' ], 11 );

				add_filter( 'avada_blog_read_more_excerpt', [ $this, 'avada_blog_read_more_excerpt' ] );
			}


			//if ( lct_wp_admin_all() ) {}


			if ( lct_wp_admin_non_ajax() ) {
				/**
				 * actions
				 */
				add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_styles' ] );

				//load-{$pagenow}
				add_action( 'load-update-core.php', [ $this, 'activate_license' ] );
				add_action( 'load-custom-fields_page_acf-settings-updates', [ $this, 'activate_license' ] );

				add_action( 'acf/update_field_group', [ $this, 'import_cleanup' ] );
			}


			if ( lct_ajax_only() ) {
				add_action( 'init', [ $this, 'set_force_yes_fields' ] );
			}
		}


		/**
		 * Set the google_map API for ACF with our saved value if it exists
		 *
		 * @param $api
		 *
		 * @return mixed
		 * @since    7.49
		 * @verified 2018.08.30
		 */
		function set_google_map_api( $api )
		{
			if (
				! $api['key']
				&& ( $saved_api = lct_acf_get_option_raw( 'google_map_api' ) )
			) {
				$api['key'] = $saved_api;
			}


			return $api;
		}


		/**
		 * Load our custom fields
		 *
		 * @since    7.53
		 * @verified 2017.01.16
		 */
		function set_force_yes_fields()
		{
			if (
				( $tmp = zxzacf( 'field_key' ) )
				&& isset( $_POST[ $tmp ] )
				&& strpos( $_POST[ $tmp ], 'force_yes_field' ) !== false
			) {
				$fields = lct_get_option( 'force_yes_fields' );


				if ( $fields ) {
					foreach ( $fields as $field ) {
						acf_add_local_field( $field );
					}
				}
			}
		}


		/**
		 * Strip out the ver query var that shows the WP version, on all enqueued scripts and styles
		 *
		 * @param $src
		 *
		 * @return mixed
		 * @since    7.50
		 * @verified 2018.08.30
		 */
		function remove_script_version( $src )
		{
			$type = str_replace( '_loader_src', '', current_filter() );


			/**
			 * Remove the version var from the URL
			 */
			if ( lct_acf_get_option_raw( 'remove_script_version_' . $type ) ) {
				$url_parts = parse_url( $src );


				if ( ! empty( $url_parts['query'] ) ) {
					$vars = parse_query( $url_parts['query'] );

					if ( isset( $vars['ver'] ) ) {
						unset( $vars['ver'] );
					}


					$url_parts['query'] = unparse_query( $vars );

					if ( empty( $url_parts['query'] ) ) {
						unset ( $url_parts['query'] );
					}
				}


				$src = unparse_url( $url_parts );
			}


			return $src;
		}


		/**
		 * If we set the options_page arg to true we want to force a match
		 *
		 * @param $match
		 * @param $rule
		 * @param $screen
		 *
		 * @return bool
		 * @since    7.50
		 * @verified 2019.04.01
		 */
		function register_rule_match_options_page(
			$match,
			/** @noinspection PhpUnusedParameterInspection */
			$rule,
			$screen
		) {
			if (
				isset( $screen['options_page'] )
				&& $screen['options_page'] === true
			) {
				$match = true;


				//This is to fix a bug when you are on an options page
			} elseif (
				$match === true
				&& ! isset( $screen['options_page'] )
			) {
				$match = false;
			}


			return $match;
		}


		/**
		 * If we set the options_page arg to true we want to force a match
		 *
		 * @param $match
		 * @param $rule
		 * @param $screen
		 *
		 * @return bool
		 * @since    2019.8
		 * @verified 2019.04.10
		 */
		function register_rule_match_post_type( $match, $rule, $screen )
		{
			if (
				! isset( $screen['param'] )
				|| $screen['param'] !== 'comment'
				|| ! isset( $screen['post_type'] )
				|| ! isset( $rule['value'] )
				|| $screen['post_type'] !== $rule['value']
			) {
				return $match;
			}


			if (
				$match === true
				&& isset( $rule['param'] )
				&& $rule['param'] !== 'comment'
			) {
				$match = false;
			}


			return $match;
		}


		/**
		 * If we set the options_page arg to true we want to force a match
		 *
		 * @param $match
		 * @param $rule
		 * @param $screen
		 *
		 * @return bool
		 * @since    2019.8
		 * @verified 2019.04.10
		 */
		function register_rule_match_comment( $match, $rule, $screen )
		{
			if (
				! isset( $screen['param'] )
				|| $screen['param'] !== 'comment'
				|| ! isset( $screen['post_type'] )
				|| ! isset( $rule['value'] )
				|| $screen['post_type'] !== $rule['value']
			) {
				return $match;
			}


			if (
				! $match
				&& isset( $rule['param'] )
				&& $rule['param'] === 'comment'
			) {
				$match = true;
			}


			return $match;
		}


		/**
		 * Activate our ACF license, but only if we are allowed to
		 *
		 * @since    7.54
		 * @verified 2022.01.06
		 */
		function activate_license()
		{
			$url     = lct_url_site();
			$license = acf_pro_get_license();


			/**
			 * We have a key saved but for the wrong URL
			 */
			if (
				isset( $license['key'] )
				&& $license['url'] !== $url
			) {
				$url = lct_get_api_url( 'acf_key.php?key=' . lct_acf_get_option_raw( 'api' ) );


				if ( $acf_key = file_get_contents( $url ) ) {
					$_POST['acf_pro_licence'] = $acf_key;
				} else {
					$_POST['acf_pro_licence'] = $license['key'];
				}


				delete_option( 'acf_pro_license' );


				if ( version_compare( lct_plugin_version( 'acf' ), '5.11', '<' ) ) { //ACF older than v5.11
					$acf = new ACF_Admin_Updates();
					$acf->activate_pro_licence();
				} else {
					acf_pro_activate_license( $license['key'], true );
				}


				/**
				 * Nothing is saved yet
				 */
			} elseif ( ! $license ) {
				$url = lct_get_api_url( 'acf_key.php?key=' . lct_acf_get_option_raw( 'api' ) );


				if ( $acf_key = file_get_contents( $url ) ) {
					$_POST['acf_pro_licence'] = $acf_key;


					if ( version_compare( lct_plugin_version( 'acf' ), '5.11', '<' ) ) { //ACF older than v5.11
						$acf = new ACF_Admin_Updates();
						$acf->activate_pro_licence();
					} else {
						acf_pro_activate_license( $acf_key, true );
					}
				}
			}
		}


		/**
		 * Register Styles
		 *
		 * @since    0.0
		 * @verified 2018.08.30
		 */
		function wp_enqueue_styles()
		{
			$post_id = lct_get_post_id();


			/**
			 * The old front CSS
			 */
			if ( lct_acf_get_option_raw( 'enable_front_css' ) ) {
				lct_enqueue_style( zxzu( 'front' ), lct_get_root_url( 'assets/css/front.min.css' ) );
			}


			/**
			 * The main CSS
			 */
			lct_enqueue_style( zxzu( 'acf' ), lct_get_root_url( 'assets/css/plugins/acf/main.min.css' ) );


			/**
			 * Media specific CSS
			 */
			switch ( lct_acf_get_option_raw( 'tablet_threshold' ) ) {
				case '800':
					lct_enqueue_style( zxzu( 'acf_media' ), lct_get_root_url( 'assets/css/plugins/acf/main-media-tablet-800.min.css' ) );
					break;


				case '1024':
					lct_enqueue_style( zxzu( 'acf_media' ), lct_get_root_url( 'assets/css/plugins/acf/main-media-tablet-1024.min.css' ) );
					break;


				default:
			}


			/**
			 * #3
			 * Inline CSS
			 *
			 * @date     0.0
			 * @since    0.0
			 * @verified 2021.08.27
			 */
			do_action( 'lct_wp_footer_style_add', '.acf-field textarea{min-height: 50px;}' );


			/**
			 * Any user added custom CSS files
			 */
			if (
				get_post_meta( $post_id, zxzacf( 'is_css_file' ), true )
				&& //Don't use get_field()
				( $files = get_field( zxzacf( 'css_files' ), $post_id ) )
			) {
				foreach ( $files as $file ) {
					if ( file_exists( lct_path_theme( '/custom/css/' . $file ) ) ) {
						lct_enqueue_style( zxzu( 'css_files_' . sanitize_title( $file ) ), lct_url_theme( '/custom/css/' . $file ), false, [], lct_active_theme_version() );
					}
				}
			}
		}


		/**
		 * Register Scripts
		 *
		 * @since    0.0
		 * @verified 2019.02.14
		 */
		function wp_enqueue_scripts()
		{
			$post_id = lct_get_post_id();


			/**
			 * Add textarea autosize to acf
			 */
			$jq = 'jQuery( \'body\' ).on( \'focus\', \'.acf-field textarea\', function( e ) {
			autosize( jQuery( e.target ) );
		} );';


			/**
			 * If you want to update size on page load
			 */
			/*
			$jq = 'jQuery( \'textarea\' ).each( function( e ) {
				autosize( jQuery( this ) );
			} );';
			*/


			/**
			 * #1
			 * @date     0.0
			 * @since    0.0
			 * @verified 2021.08.27
			 */
			do_action( 'lct_jq_autosize' );


			/**
			 * #5
			 * @date     0.0
			 * @since    0.0
			 * @verified 2021.08.27
			 */
			do_action( 'lct_jq_doc_ready_add', $jq );


			/**
			 * Any user added custom JS files
			 */
			if (
				get_post_meta( $post_id, zxzacf( 'is_js_file' ), true )
				&& //Don't use get_field()
				( $files = get_field( zxzacf( 'js_files' ), $post_id ) )
			) {
				foreach ( $files as $file ) {
					if ( file_exists( lct_path_theme( '/custom/js/' . $file ) ) ) {
						lct_enqueue_script( zxzu( 'js_files_' . sanitize_title( $file ) ), lct_url_theme( '/custom/js/' . $file ), false, [ 'jquery' ], lct_active_theme_version() );
					}
				}
			}
		}


		/**
		 * ADD Google Font stylesheets
		 *
		 * @since    5.40
		 * @verified 2018.08.24
		 */
		function always_load_google_fonts()
		{
			if ( lct_acf_option_repeater_empty( zxzacf( 'load_google_fonts' ) ) ) {
				$row = 1;


				while( have_rows( zxzacf( 'load_google_fonts' ), lct_o() ) ) {
					the_row();


					wp_register_style( zxzu( 'gfont-' . $row ), '//fonts.googleapis.com/css?family=' . str_replace( ' ', '+', get_sub_field( 'name' ) ) . get_sub_field( 'additional' ), [], lct_get_setting( 'version' ) );

					if ( get_sub_field( 'always_load' ) ) {
						wp_enqueue_style( zxzu( 'gfont-' . $row ) );
					}


					$row ++;
				}
			}
		}


		/**
		 * ADD Adobe Typekit script
		 *
		 * @since    5.40
		 * @verified 2018.08.24
		 */
		function always_load_typekit()
		{
			if ( lct_acf_option_repeater_empty( zxzacf( 'load_typekit' ) ) ) {
				$row = 1;


				while( have_rows( zxzacf( 'load_typekit' ), lct_o() ) ) {
					the_row();


					wp_register_script( zxzu( 'typekit-' . $row ), 'https://use.typekit.net/' . get_sub_field( 'name' ) . '.js', [], lct_get_setting( 'version' ) );

					if ( get_sub_field( 'always_load' ) ) {
						wp_enqueue_script( zxzu( 'typekit-' . $row ) );
					}


					$row ++;
				}


				lct_enqueue_script( zxzu( 'typekit-' . $row ), 'try{Typekit.load({ async: true });}catch(e){};' );
			}
		}


		/**
		 * ADD a single Google Font stylesheet
		 *
		 * @param $font_id
		 *
		 * @since    5.40
		 * @verified 2016.09.29
		 */
		function single_load_google_fonts( $font_id )
		{
			wp_print_styles( zxzu( 'gfont-' . $font_id ) );
		}


		/**
		 * ADD a single Adobe Typekit script
		 *
		 * @param $font_id
		 *
		 * @since    5.40
		 * @verified 2016.09.29
		 */
		function single_load_typekit( $font_id )
		{
			wp_print_scripts( zxzu( 'typekit-' . $font_id ) );
			wp_add_inline_script( zxzu( 'typekit-' . $font_id ), 'try{Typekit.load({ async: true });}catch(e){}' );
		}


		/**
		 * Add some stuff to wp_footer action
		 *
		 * @since    0.0
		 * @verified 2018.08.30
		 */
		function wp_footer_get_user_agent_info()
		{
			if ( lct_acf_get_option_raw( 'print_user_agent_in_footer' ) ) /**
			 * @date     0.0
			 * @since    0.0
			 * @verified 2021.08.30
			 */ {
				do_action( 'lct_get_user_agent_info', true, true );
			}
		}


		/**
		 * Register Styles
		 *
		 * @since    0.0
		 * @verified 2016.09.29
		 */
		function admin_enqueue_styles()
		{
			lct_admin_enqueue_style( zxzu( 'acf' ), lct_get_root_url( 'assets/wp-admin/css/plugins/acf/main.min.css' ) );
		}


		/**
		 * Help out our iframe by 'auto' loading this
		 *
		 * @since    2017.6
		 * @verified 2017.02.02
		 */
		function acf_form_head()
		{
			if (
				isset( $_GET['acf_form_head'] )
				&& $_GET['acf_form_head']
			) {
				acf_form_head();
			}
		}


		/**
		 * This will put the site in maintenance mode, when we check an ACF setting
		 *
		 * @since    7.16
		 * @verified 2018.08.30
		 */
		function maintenance_mode()
		{
			if ( $maintenance_mode = lct_acf_get_option_raw( 'maintenance_mode' ) ) {
				add_action( 'admin_bar_menu', [ $this, 'maintenance_mode_in_admin_bar_menu' ], 999999 );
			}


			if (
				$maintenance_mode
				&& ! lct_doing()
				&& ! is_admin()
				&& ! lct_is_user_a_dev()
			) {
				echo '<h1 style="text-align: center;">Site is down for maintenance. Please check back in a few minutes.</h1>';
				exit;
			}
		}


		/**
		 * Added a note about the site being in maintenance mode into the wp_admin_bar
		 *
		 * @param WP_Admin_Bar $wp_admin_bar
		 *
		 * @since        7.16
		 * @verified     2018.08.27
		 * @noinspection PhpMissingParamTypeInspection
		 */
		function maintenance_mode_in_admin_bar_menu( $wp_admin_bar )
		{
			$args = [
				'id'    => lct_pre_us( zxzu( 'maintenance_mode' ) ),
				'title' => '<span style="color:#FF0000 !important;">' . zxzb( ' Maintenance On' ) . '</span>',
			];


			$wp_admin_bar->add_menu( $args );
		}


		/**
		 * This will prevent people from accessing the wp-admin back-end while this ACF setting is active
		 *
		 * @since    2017.18
		 * @verified 2018.08.30
		 */
		function lock_site_edits()
		{
			if ( $lock_site = lct_acf_get_option_raw( 'lock_site_edits' ) ) {
				add_action( 'admin_bar_menu', [ $this, 'lock_site_edits_in_admin_bar_menu' ], 999999 );
			}


			if (
				$lock_site
				&& ! lct_doing()
				&& is_admin()
				&& is_user_logged_in()
			) {
				$allowed              = false;
				$allowed_users        = lct_acf_get_option_raw( 'lock_site_edits_allow' );
				$allowed_primary_user = get_userdata( $allowed_users[0] );


				if ( is_user_logged_in() ) {
					$current_user = wp_get_current_user();


					if ( in_array( $current_user->ID, $allowed_users ) ) {
						$allowed = true;
					}
				}


				if ( ! $allowed ) {
					echo sprintf(
						'<h1 style="text-align: center;">wp-admin has been temporarily locked by:<br />%1$s</h1>
					<h2 style="text-align: center;">If you need to make an immediate edit please contact <span style="color:#FF0000 !important;">%1$s</span> via Slack.<br />Otherwise check back around</h2>
					<h1 style="text-align: center;color:#006400 !important;">%2$s</h1>',
						$allowed_primary_user->display_name,
						lct_acf_get_option( 'lock_site_edits_unlock_time' )
					);
					exit;
				}
			}
		}


		/**
		 * Adds a note about the site back-end not being accessible into the wp_admin_bar
		 *
		 * @param WP_Admin_Bar $wp_admin_bar
		 *
		 * @since        2017.18
		 * @verified     2018.08.30
		 * @noinspection PhpMissingParamTypeInspection
		 */
		function lock_site_edits_in_admin_bar_menu( $wp_admin_bar )
		{
			$allowed_users        = lct_acf_get_option_raw( 'lock_site_edits_allow' );
			$allowed_primary_user = get_userdata( $allowed_users[0] );


			$args = [
				'id'    => '_lock_site_edits',
				'title' => sprintf( '<span style="color:#FF0000 !important;">%s temporarily locked wp-admin</span>', $allowed_primary_user->display_name )
			];


			$wp_admin_bar->add_menu( $args );
		}


		/**
		 * Display all the browscap data for a particular page
		 * Alias to: lct_get_user_agent_info()
		 *
		 * @param null $print
		 * @param null $hide
		 *
		 * @return bool|Browscap
		 * @since    4.1.11
		 * @verified 2017.09.14
		 */
		function get_user_agent_info( $print = null, $hide = null )
		{
			return lct_get_user_agent_info( $print, $hide );
		}


		/**
		 * Check if the field group matches lct_org()
		 * //TODO: cs - Need to clean this up - 2016.10.11 09:53 PM
		 *
		 * @param $match
		 * @param $rule
		 * @param $screen
		 *
		 * @return bool
		 * @since    7.17
		 * @verified 2019.04.01
		 */
		function register_rule_match_lct_org( $match, $rule, $screen )
		{
			if (
				( $tmp = $screen[ lct_org() ] )
				&& ! empty( $tmp )
			) {
				if ( ! is_array( $screen[ lct_org() ] ) ) {
					$screen[ lct_org() ] = [ $screen[ lct_org() ] ];
				}


				if ( $rule['value'] == 'all' ) {
					$match = true;
				} else {
					$selected_org = (int) $rule['value'];


					if ( $rule['operator'] == "==" ) {
						$match = in_array( $selected_org, $screen[ lct_org() ] );
					} elseif ( $rule['operator'] == "!=" ) {
						$match = ! in_array( $selected_org, $screen[ lct_org() ] );
					}
				}
			}


			return $match;
		}


		/**
		 * Remove the front-end admin bar from selected users in the Useful Settings
		 *
		 * @return bool
		 * @since    0.0
		 * @verified 2018.08.30
		 */
		function show_admin_bar()
		{
			//always show in wp-admin
			if ( is_admin() ) {
				return true;
			}

			//always hide if no one is logged in
			if ( ! is_user_logged_in() ) {
				return false;
			}

			//hide it if the profile says so
			if ( get_user_meta( get_current_user_id(), 'show_admin_bar_front', true ) == 'false' ) {
				return false;
			}


			//get the roles that are to be hidden
			$roles_to_hide = lct_acf_get_option_raw( 'hide_admin_bar__by_role' );


			//compare those roles to what this user's roles are
			if ( ! empty( $roles_to_hide ) ) {
				$current_user = wp_get_current_user();


				foreach ( $current_user->roles as $role ) {
					//if there is a match, hide the admin bar
					if ( in_array( $role, $roles_to_hide ) ) {
						return false;
					}
				}
			}


			//if we made it through all that show the dang the admin bar
			return true;
		}


		/**
		 * Replace the default read more text with an ACF value
		 *
		 * @param $read_more_text
		 *
		 * @return bool|mixed|null
		 * @since    4.3.1
		 * @verified 2018.08.30
		 */
		function avada_blog_read_more_excerpt( $read_more_text )
		{
			if ( lct_acf_get_option_raw( 'avada::is_post_excerpt_read_more' ) ) {
				$change = true;


				if ( lct_acf_get_option_raw( 'avada::is_post_excerpt_read_more_certain_pages' ) ) {
					$page = get_queried_object_id();


					if (
						(
							$page
							&& ! in_array( get_queried_object_id(), lct_acf_get_option_raw( 'avada::post_excerpt_read_more_certain_pages' ) )
						)
						|| ! $page
					) {
						$change = false;
					}
				}


				if ( $change ) {
					$read_more_text = lct_acf_get_option( 'avada::post_excerpt_read_more' );
				}
			}


			return $read_more_text;
		}


		/**
		 * ACF sets a 'local' key to 'php'
		 * I don't know why, but it messes crap up real bad.
		 *
		 * @param $field_group
		 *
		 * @since    7.26
		 * @verified 2017.07.18
		 */
		function import_cleanup( $field_group )
		{
			if (
				$field_group['ID']
				&& isset( $field_group['local'] )
				&& in_array( $field_group['local'], [ 'php', 'json' ] )
			) {
				unset( $field_group['local'] );
				unset( $field_group['modified'] );


				acf_update_field_group( $field_group );
			}
		}


		/**
		 * Disable Services
		 * xmlrpc
		 * wlwmanifest
		 * WP REST API
		 *
		 * @since    2017.90
		 * @verified 2018.08.30
		 */
		function disable_connection_services()
		{
			/**
			 * Disable xmlrpc
			 */
			if ( ! lct_acf_get_option_raw( 'use_xmlrpc' ) ) {
				add_filter( 'xmlrpc_enabled', '__return_false' );

				remove_action( 'wp_head', 'rsd_link' );
			}


			/**
			 * Disable wlwmanifest
			 */
			if ( ! lct_acf_get_option_raw( 'use_wlwmanifest' ) ) {
				remove_action( 'wp_head', 'wlwmanifest_link' );
			}


			/**
			 * Disable WP REST API
			 * Only disables the links, use this plugin if you actually want to disable REST API
			 * https://wordpress.org/plugins/disable-json-api/
			 */
			if (
				! lct_plugin_active( 'disable-json-api' )
				&& ! lct_acf_get_option_raw( 'use_wp_rest_api' )
			) {
				// Remove REST API info from head and headers
				remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
				remove_action( 'wp_head', 'rest_output_link_wp_head' );
				remove_action( 'template_redirect', 'rest_output_link_header', 11 );
			}
		}


		/**
		 * Create a setting so that we can know when we are in the middle of rendering a new_post form
		 *
		 * @param $args
		 *
		 * @return mixed
		 * @since    2018.62
		 * @verified 2018.08.27
		 */
		function set_new_post_setting( $args )
		{
			if ( $args['post_id'] === 'new_post' ) {
				lct_update_setting( 'acf/render_form/new_post', $args['new_post']['post_type'] );
			}


			return $args;
		}


		/**
		 * Create a setting so that we can know when we are in the middle of rendering a new_post form
		 *
		 * @since    2018.62
		 * @verified 2018.08.27
		 */
		function unset_new_post_setting()
		{
			lct_update_setting( 'acf/render_form/new_post', null );
		}


		/**
		 * Update the tax filter to a status filter
		 *
		 * @param array $args
		 *
		 * @unused       param $field
		 * @unused       param $post_id
		 * @return array
		 * @since        2019.26
		 * @verified     2019.11.22
		 * @noinspection PhpMissingParamTypeInspection
		 */
		function update_status_filter( $args )
		{
			if ( empty( $args['tax_query'] ) ) {
				return $args;
			}


			foreach ( $args['tax_query'] as $k => $tax ) {
				if (
					( $taxonomy = get_taxonomy( $tax['taxonomy'] ) )
					&& ! empty( $taxonomy->lct_tax_custom_status_slugs )
				) {
					if ( empty( $args['post_status'] ) ) {
						$args['post_status'] = [];
					}


					foreach ( $tax['terms'] as $term ) {
						$args['post_status'][] = lct_make_status_slug( get_term_by( 'slug', $term, $tax['taxonomy'] ) );
					}


					unset( $args['tax_query'][ $k ] );
				}
			}


			return $args;
		}


		/**
		 * Add the menu order back into fields when they are exported
		 *
		 * @param array $field_group
		 *
		 * @return array
		 * @since        2019.29
		 * @verified     2019.12.05
		 * @noinspection PhpMissingParamTypeInspection
		 */
		function add_menu_order_to_fields( $field_group )
		{
			if ( ! empty( $field_group['fields'] ) ) {
				$field_group['fields'] = $this->add_menu_order_loop_fields( $field_group['fields'] );
			}


			return $field_group;
		}


		/**
		 * Add the menu order back into fields when they are exported
		 *
		 * @param array $fields
		 *
		 * @return array
		 * @since        2019.29
		 * @verified     2019.12.05
		 * @noinspection PhpMissingParamTypeInspection
		 */
		function add_menu_order_loop_fields( $fields )
		{
			$menu_order = 0;


			foreach ( $fields as $key => $field ) {
				if ( ! empty( $field['sub_fields'] ) ) {
					$fields[ $key ]['sub_fields'] = $this->add_menu_order_loop_fields( $field['sub_fields'] );
				}


				$fields[ $key ]['menu_order'] = $menu_order;


				$menu_order ++;
			}


			return $fields;
		}


		/**
		 * Allow all the posts to be returned
		 *
		 * @param array $args
		 *
		 * @unused       param $field
		 * @unused       param $post_id
		 * @return array
		 * @since        2020.3
		 * @verified     2020.01.22
		 * @noinspection PhpMissingParamTypeInspection
		 */
		function update_posts_per_page( $args )
		{
			if ( ! isset( $args['paged'] ) ) {
				return $args;
			}


			$paged = $args['paged'];


			if (
				is_numeric( $paged )
				&& (int) $paged > 1
			) {
				$paged = true;
			}


			if ( filter_var( $paged, FILTER_VALIDATE_BOOLEAN ) === false ) {
				$args['posts_per_page'] = - 1;
			}


			return $args;
		}


		/**
		 * Prevent duplicate field elements
		 *
		 * @param array $attrs
		 *
		 * @return array
		 * @since        2020.3
		 * @verified     2020.10.01
		 * @noinspection PhpMissingParamTypeInspection
		 */
		function unique_id( $attrs )
		{
			if (
				! empty( $attrs['id'] )
				&& $attrs['id'] !== '_acf_delete_fields'
				&& strpos( $attrs['id'], '_acf' ) === 0
			) {
				$attrs['id'] .= '_' . lct_rand_short();
			}


			return $attrs;
		}
	}


	/**
	 * Instantiate.
	 */
	acf_new_instance( 'lct_acf_admin' );


endif; // class_exists check


/**
 * @return lct_acf_admin
 * @date     2023.11.27
 * @since    2023.04
 * @verified 2023.11.27
 */
function lct_acf_admin()
{
	/**
	 * @var lct_acf_admin $ins
	 */
	$ins = acf_get_instance( 'lct_acf_admin' );


	return $ins;
}
