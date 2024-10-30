<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'lct' ) ) :
	/**
	 * LCT Referenced
	 * 2017.09.29    /code/admin/template_router.php (optional: also see BNS)
	 * 2019.07.15    /code/api/_global.php
	 * 2019.07.15    /code/api/class.php
	 * 2018.08.30    /code/api/pre_suf_fix.php
	 * 2019.06.05    /code/plugins/Avada/api/overrides.php (Just location is important)
	 * 2018.08.22    /code/plugins/Avada/override/override.php (Just location is important)
	 * 2019.07.15    /code/wp-admin/admin/update.php
	 * 2019.07.15    /code/__init.php
	 *
	 * @verified 2019.07.15
	 * @checked  2019.07.15
	 */
	class lct
	{
		/**
		 * General
		 */
		/**
		 * @var lct_mu
		 * @since LCT 2018.11
		 */
		public $lct_mu;
		/**
		 * @var array
		 * @since LCT 7.27
		 */
		public $cnst = [];
		/**
		 * @var array
		 * @since LCT 2019.18
		 */
		public $data = [];
		/**
		 * @var array
		 * @since LCT 2019.18
		 */
		public $plugins = [];
		/**
		 * @var array
		 * @since LCT 7.38
		 */
		public $settings = [];


		/**
		 * Globalized Classes
		 */
		/**
		 * @var lct_acf_public
		 * @since LCT 7.42
		 */
		public $acf_public;
		/**
		 * @var lct_acf_public_choices
		 * @since LCT 7.50
		 */
		public $acf_public_choices;
		/**
		 * @var lct_api_class
		 * @since LCT 2019.15
		 */
		public $api_class;
		/**
		 * @var lct_public
		 * @since LCT 7.42
		 */
		public $public;


		/**
		 * A dummy constructor to ensure plugin is only initialized once
		 *
		 * @verified 2017.07.31
		 */
		function __construct()
		{
			/* Do nothing here */
		}


		/**
		 * The real constructor to initialize plugin
		 *
		 * @since    LCT 7.38
		 * @verified 2019.07.15
		 */
		function init()
		{
			global $lct_mu;
			$this->lct_mu = $lct_mu;


			/**
			 * vars
			 */
			$this->settings = [
				//basic
				'name'                                    => __( 'LCT Useful Shortcodes & Functions', 'TD_LCT' ),
				'_us'                                     => 'lct_useful_shortcodes_functions',
				'_dash'                                   => 'lct-useful-shortcodes-functions',
				'version'                                 => '0.0',
				'version_in_db'                           => '0.0',

				//urls
				'path'                                    => plugin_dir_path( __FILE__ ), //INCLUDES trailing slash
				'url'                                     => plugin_dir_url( __FILE__ ), //INCLUDES trailing slash
				'api'                                     => 'https://eetah.com/api/lct/',

				//constants
				'_zxzp'                                   => 'parent',
				'_zxza'                                   => 'lct',
				'_zxza_acf'                               => 'lct:::',
				'_zxzb'                                   => 'LCT',
				'_zxzu'                                   => 'lct_',
				'_zxzd'                                   => ':::',
				'_zxzs'                                   => 'lct/',

				//ACF
				'acf_dev'                                 => false,
				'acf_post_id'                             => 'lct_acf_post_id', //Delete This
				'root_post_id'                            => 'lct_root_post_id',

				//ACF Groups
				'acf_group_user_restriction_settings'     => 'group_590257b44e1b2',
				'acf_group_dev_report'                    => 'group_58434b9797e57',
				'acf_group_audit'                         => 'group_56d90ec87101f',
				'acf_dev_report_plugins'                  => 'field_58434bad2da02',
				'acf_dev_report_modified_posts'           => 'field_59a5bfe71bb40',
				'acf_dev_report_database_status_options'  => 'field_5d2df427e8996',
				'acf_dev_report_database_status_postmeta' => 'field_5d2e4f2945b8a',
				'acf_dev_report_database_status_usermeta' => 'field_5d2e4f38c0c4d',

				//Default settings
				'disable_avada_post_types'                => true,
			];


			/**
			 * Add any settings that were defined in the root directory
			 */
			if ( ! empty( $GLOBALS['lct_root_settings'] ) ) {
				$this->settings = array_merge( $GLOBALS['lct_root_settings'], $this->settings );
			}


			/**
			 * Include helpers
			 */
			include_once( 'api/_global.php' );
			include_once( 'api/_cache.php' );
			include_once( 'api/_helpers.php' );


			/**
			 * API load first
			 * ORDER MATTERS
			 */
			lct_include( 'api/pre_suf_fix.php' );


			/**
			 * api
			 */
			lct_include( 'api/access.php' );
			lct_include( 'api/alter.php' );
			lct_include( 'api/choices.php' );
			lct_include( 'api/conditional.php' );
			lct_include( 'api/debug.php' );
			lct_include( 'api/get.php' );
			lct_include( 'api/instances.php' );
			lct_include( 'api/int.php' );
			lct_include( 'api/is.php' );
			lct_include( 'api/misc.php' );
			lct_include( 'api/static.php' );
			lct_include( 'api/style.php' );
			lct_include( 'api/plugins.php' );
			lct_include( 'api/themes.php' );
			lct_include( 'api/deprecated.php' );
			lct_include( 'api/deprecated_actions.php' );
			lct_include( 'api/deprecated_filters.php' );
			lct_include( 'api/deprecated_functions.php' );
			lct_include( 'api/deprecated_shortcodes.php' );


			/**
			 * OLD
			 */
			lct_include( 'admin/old/_function.php' );
			lct_include( 'admin/old/display/field_data.php' );
			lct_include( 'admin/old/display/OLD_fields.php' );
			lct_include( 'admin/old/display/OLD_options.php' );
			lct_include( 'admin/old/display/sel_opts.php' );
			lct_include( 'admin/old/display/checkboxes.php' );


			/**
			 * startup tasks
			 */
			$this->startup_tasks();


			/**
			 * include some our classes, prior to plugins_loaded & init hook
			 */
			$this->include_classes();


			if (
				$this->lct_mu
				&& isset( $this->lct_mu->fast_ajax )
				&& $this->lct_mu->fast_ajax
				&& ! empty( $this->lct_mu->fast_ajax_action )
			) {
				$this->lct_mu->fast_ajax = true;


				switch ( $this->lct_mu->fast_ajax_action ) {
					case 'lct_acf_instant_save':
						/**
						 * ALWAYS FIRST
						 * advanced-custom-fields-pro
						 */
						$plugin = 'acf';
						$dir    = "plugins/{$plugin}";

						lct_include( "{$dir}/api/_helpers.php" );
						lct_include( "{$dir}/api/get.php" );

						lct_load_class( "{$dir}/instant_save.php", 'instant_save', [ 'plugin' => $plugin ] );
						break;


					default:
				}


				add_action( 'plugins_loaded', [ $this, 'fast_ajax' ], 1 );


				/**
				 * Set the statuses of themes and plugins
				 */
				add_action( 'plugins_loaded', [ $this, 'set_plugins_n_themes' ], 2 );


				/**
				 * plugins_loaded_first tasks
				 */
				add_action( 'plugins_loaded', [ $this, 'plugins_loaded_first' ], 4 );
			} else {
				/**
				 * Bypass sitelogin is needed
				 */
				add_action( 'plugins_loaded', [ $this, 'login_bypass' ], 0 );


				/**
				 * Set the statuses of themes and plugins
				 */
				add_action( 'plugins_loaded', [ $this, 'set_plugins_n_themes' ], 2 );


				/**
				 * Deny wp-login.php
				 */
				add_action( 'plugins_loaded', [ $this, 'deny_wp_login' ], 3 );


				/**
				 * plugins_loaded_first tasks
				 */
				add_action( 'plugins_loaded', [ $this, 'plugins_loaded_first' ], 4 );


				/**
				 * Load up all our classes
				 */
				add_action( 'init', [ $this, 'load_classes' ], 4 );


				/**
				 * Load up all classes that extend others' plugins
				 */
				add_action( 'init', [ $this, 'load_plugin_extensions' ], 5 );


				/**
				 * Instant action & filter testers
				 */
				//add_action( 'edit', [ $this, 'tester' ] );
				//add_filter( 'edit', [ $this, 'tester' ] );
			}
		}


		/**
		 * Instant action & filter testers
		 *
		 * @param $a_1
		 *
		 * @return mixed
		 * @since    LCT 2017.12
		 * @verified 2017.07.31
		 */
		function tester( $a_1 )
		{
			return $a_1;
		}


		/**
		 * Stuff to do right away
		 * Called during: lct()->init()
		 *
		 * @since    LCT 7.38
		 * @verified 2017.07.31
		 */
		function startup_tasks()
		{
			//bail early if already ran
			if ( lct_did() ) {
				return;
			}


			/**
			 * Fix paths on Windows systems
			 */
			lct_update_setting( 'plugin_file', lct_static_cleaner( lct_get_setting( 'plugin_file' ), false ) );
			lct_update_setting( 'root_path', lct_static_cleaner( lct_get_setting( 'root_path' ), false ) );
			lct_update_setting( 'path', lct_static_cleaner( lct_get_setting( 'path' ), false ) );


			/**
			 * Set Constants
			 */
			if ( ! defined( 'LCT_VALUE_EMPTY' ) ) {
				define( 'LCT_VALUE_EMPTY', '---empty---' );
			}

			if ( ! defined( 'LCT_VALUE_UNSET' ) ) {
				define( 'LCT_VALUE_UNSET', '---not set yet---' );
			}


			/**
			 * Set text domain
			 */
			//TODO: cs - set this up - 11/27/2016 03:35 PM
			//load_textdomain( 'TD_LCT', lct_get_path( 'lang/lct-' . get_locale() . '.mo' ) );


			/**
			 * Special plugin hooks
			 */
			register_activation_hook( lct_get_setting( 'plugin_file' ), [ $this, 'activate' ] );
			register_deactivation_hook( lct_get_setting( 'plugin_file' ), [ $this, 'deactivate' ] );
			register_uninstall_hook( lct_get_setting( 'plugin_file' ), [ zxza(), 'uninstall' ] );


			/**
			 * SPECIAL: iThemes
			 */
			if ( is_admin() ) {
				//Because iThemes sucks and it is using negative priorities for the 'plugins_loaded' hook
				add_action( 'plugins_loaded', [ $this, 'special_plugins_loaded_ithemes' ], - 999 );
			}
		}


		/**
		 * Include our classes that may need to be accessed before plugins_loaded or init
		 * Called during: lct()->init()
		 *
		 * @since    LCT 7.38
		 * @verified 2017.07.31
		 */
		function include_classes()
		{
			//bail early if already ran
			if ( lct_did() ) {
				return;
			}


			/**
			 * everytime
			 */
			lct_load_class( 'api/class.php', 'class', [ 'dir' => 'api' ] );
			lct_load_class( 'api/hacky.php', 'hacky', [ 'dir' => 'api' ] );


			/**
			 * wp-admin
			 */
			//if ( is_admin() ) {}


			/**
			 * front-end
			 */
			//if ( ! is_admin() ) {}
		}


		/**
		 * login_bypass
		 * Called on action: 'plugins_loaded' priority: 0
		 *
		 * @since    LCT 2017.63
		 * @verified 2019.08.21
		 */
		function login_bypass()
		{
			$whl_page_raw = get_option( 'whl_page' );
			$whl_page     = '/' . $whl_page_raw . '/';


			if (
				$whl_page_raw
				&& strpos( $_SERVER['REQUEST_URI'], $whl_page ) !== false
			) {
				if (
					is_user_logged_in()
					&& $_SERVER['REQUEST_URI'] === $whl_page
				) {
					lct_wp_safe_redirect( admin_url( '/' ) );
				} elseif (
					! is_user_logged_in()
					&& site_url( null, 'relative' )
					&& strpos( $_SERVER['REQUEST_URI'], site_url( $whl_page, 'relative' ) ) === 0
				) {
					$request = wp_parse_url( $_SERVER['REQUEST_URI'] );


					if ( ! empty( $request['query'] ) ) {
						$whl_page .= '?' . $request['query'];
					}


					lct_wp_safe_redirect( home_url( $whl_page ) );
				}
			}
		}


		/**
		 * fast_ajax only items
		 *
		 * @since    LCT 2018.11
		 * @verified 2018.02.13
		 */
		function fast_ajax() {}


		/**
		 * set_plugins_n_themes
		 * Called on action: 'plugins_loaded' priority: 2
		 *
		 * @since    LCT 7.45
		 * @verified 2017.08.18
		 */
		function set_plugins_n_themes()
		{
			//bail early if already ran
			if ( lct_did() ) {
				return;
			}


			/**
			 * Set up the theme status in our main obj
			 */
			lct_set_current_theme( 'Avada' );


			/**
			 * Set up the plugin statuses in our main obj
			 */
			lct_set_plugin( 'advanced-custom-fields-pro/acf.php', 'acf' );                      //Last Check: Never
			lct_set_plugin( 'advanced-features-wp/afwp.php', 'afwp' );                          //Last Check: Never
			lct_set_plugin( 'admin-menu-editor/menu-editor.php', 'admin-menu-editor' );         //Last Check: Never
			lct_set_plugin( 'calendarize-it/calendarize-it.php', 'rhc' );                       //Last Check: 4.5.2.80997
			lct_set_plugin( 'disable-json-api/disable-json-api.php', 'disable-json-api' );      //Last Check: Never
			lct_set_plugin( 'fusion-builder/fusion-builder.php', 'fusion_builder' );            //Last Check: Never
			lct_set_plugin( 'fusion-core/fusion-core.php', 'fusion_core' );                     //Last Check: Never
			lct_set_plugin( 'gravityforms/gravityforms.php', 'gforms' );                        //Last Check: Never
			lct_set_plugin( 'better-wp-security/better-wp-security.php', 'ithemes' );           //Last Check: Never
			lct_set_plugin( lct_get_setting( 'basename' ), 'lct' );                             //Last Check: N/A
			lct_set_plugin( 'maintenance/maintenance.php', 'maintenance' );                     //Last Check: Never
			lct_set_plugin( 'NKS-custom/main.php', 'nks' );                                     //Last Check: v3.0.4
			lct_set_plugin( 'q2w3-fixed-widget/q2w3-fixed-widget.php', 'q2w3' );                //Last Check: 5.0.4
			lct_set_plugin( 'redirection/redirection.php', 'redirection' );                     //Last Check: Never
			lct_set_plugin( 'revslider/revslider.php', 'revslider' );                           //Last Check: Never
			lct_set_plugin( 'simple-image-widget/simple-image-widget.php', 'siw' );             //Last Check: Never
			lct_set_plugin( 'stream/stream.php', 'stream' );                                    //Last Check: Never
			lct_set_plugin( 'taxonomy-terms-order/taxonomy-terms-order.php', 'to' );            //Last Check: Never
			lct_set_plugin( 'google-analytics-for-wordpress/googleanalytics.php', 'Yoast_GA' ); //Last Check: Never
			lct_set_plugin( 'w3-total-cache/w3-total-cache.php', 'w3tc' );                      //Last Check: Never
			lct_set_plugin( 'woocommerce/woocommerce.php', 'wc' );                              //Last Check: Never
			lct_set_plugin( 'wp-mail-smtp/wp_mail_smtp.php', 'wp-mail-smtp' );                  //Last Check: Never
			lct_set_plugin( 'wp-rocket/wp-rocket.php', 'wp-rocket' );                           //Last Check: Never
			lct_set_plugin( 'wp-sweep/wp-sweep.php', 'wp-sweep' );                              //Last Check: Never
			lct_set_plugin( 'wp-sync-db/wp-sync-db.php', 'wpsdb' );                             //Last Check: Never
			lct_set_plugin( 'pimg-gallery/pimg-gallery.php', 'xpg' );                           //Last Check: Never
			lct_set_plugin( 'wordfence/wordfence.php', 'wf' );                                  //Last Check: 6.3.15
			lct_set_plugin( 'wordpress-seo/wp-seo.php', 'yoast' );                              //Last Check: Never
			lct_set_plugin( 'wp-session-manager/wp-session-manager.php', 'wps' );               //Last Check: Never
			lct_set_plugin( 'wps-hide-login/wps-hide-login.php', 'wps-hide-login' );            //Last Check: 1.1.7
			lct_set_Yoast_GA_settings();


			/**
			 * Set acf_dev status
			 */
			if ( lct_is_wpdev() ) {
				lct_update_setting( 'acf_dev', true );
			}
		}


		/**
		 * deny_wp_login
		 * Called on action: 'plugins_loaded' priority: 3
		 *
		 * @since    LCT 2017.64
		 * @verified 2017.08.15
		 */
		function deny_wp_login()
		{
			if (
				lct_plugin_active( 'wps-hide-login' )
				&& $_SERVER['REQUEST_URI'] === '/' . str_repeat( '-/', 10 )
			) {
				lct_wp_safe_redirect( home_url( 'not_found/' ) );
			}
		}


		/**
		 * Stuff to do once all the plugins are loaded
		 * Called on action: 'plugins_loaded' priority: 4
		 *
		 * @since    LCT 7.38
		 * @verified 2019.07.15
		 */
		function plugins_loaded_first()
		{
			//bail early if already ran
			if ( lct_did() ) {
				return;
			}


			/**
			 * Load public classes
			 */
			lct_load_class( 'public/class.php', 'public', [ 'globalize' => true ] );


			/**
			 * Register post_types & taxonomies
			 */
			lct_load_class( 'admin/post_types.php', 'post_types', [ 'globalize_legacy' => true ] );
			lct_load_class( 'admin/taxonomies.php', 'taxonomies', [ 'globalize_legacy' => true ] );


			/**
			 * Other before init classes
			 */
			$dir = 'admin';
			lct_load_class( "{$dir}/time.php", 'time', [ 'dir' => $dir ] );


			/**
			 * ALWAYS FIRST :: Special
			 * advanced-custom-fields-pro
			 */
			$plugin = 'acf';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_include( "{$dir}/api/_sort.php" );
				lct_include( "{$dir}/api/form.php" );
				lct_include( "{$dir}/api/get.php" );
				lct_include( "{$dir}/api/is.php" );


				lct_load_class( "{$dir}/_loaded.php", 'loaded', [ 'plugin' => $plugin ] );
				lct_load_class( "{$dir}/filters_load_field.php", 'filters_load_field', [ 'plugin' => $plugin ] );


				/**
				 * wp-admin
				 */
				if ( is_admin() ) {
					$plugin = "wp-admin/{$plugin}";
					$dir    = "wp-admin/{$dir}";
					lct_load_class( "{$dir}/choices.php", 'choices', [ 'plugin' => $plugin ] );
				}
			}


			/**
			 * Asana Theme
			 */
			$plugin = 'asana';
			$dir    = "plugins/{$plugin}";

			lct_load_class( "{$dir}/acf.php", 'acf', [ 'plugin' => $plugin ] );
			lct_load_class( "{$dir}/class.php", '', [ 'plugin' => $plugin ] );


			/**
			 * Avada Theme
			 */
			$plugin = 'Avada';
			$dir    = "plugins/{$plugin}";

			if ( lct_theme_active( $plugin ) ) {
				lct_include( "{$dir}/api/overrides.php" );


				lct_load_class( "{$dir}/override/override.php", 'override', [ 'plugin' => $plugin ] );
				lct_load_class( "{$dir}/_loaded.php", 'loaded', [ 'plugin' => $plugin ] );
			}


			/**
			 * gforms
			 */
			$plugin = 'gforms';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_load_class( "{$dir}/_loaded.php", 'loaded', [ 'plugin' => $plugin ] );
			}


			/**
			 * woocommerce
			 */
			$plugin = 'wc';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_load_class( "{$dir}/_loaded.php", 'loaded', [ 'plugin' => $plugin ] );
			}


			/**
			 * wps-hide-login
			 */
			$plugin = 'wps-hide-login';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_load_class( "{$dir}/_loaded.php", 'loaded', [ 'plugin' => $plugin ] );
			}
		}


		/**
		 * SPECIAL: iThemes
		 * Called on action: 'plugins_loaded' priority: -999
		 *
		 * @since    LCT 2017.57
		 * @verified 2017.07.31
		 */
		function special_plugins_loaded_ithemes()
		{
			//We have to call this function now, because iThemes sucks and it is using negative priorities for the 'plugins_loaded' hook
			lct_set_plugin( 'better-wp-security/better-wp-security.php', 'ithemes' );


			/**
			 * better-wp-security
			 */
			$plugin = 'ithemes';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				$plugin = "wp-admin/{$plugin}";
				$dir    = "wp-admin/{$dir}";
				lct_load_class( "{$dir}/_loaded.php", 'loaded', [ 'plugin' => $plugin ] );
			}
		}


		/**
		 * Load up all our classes
		 * Called on action: 'init' priority: 4
		 *
		 * @since    LCT 7.38
		 * @verified 2019.07.15
		 */
		function load_classes()
		{
			//bail early if already ran
			if ( lct_did() ) {
				return;
			}


			/**
			 * everytime
			 */
			//apis need to go first
			$dir = 'features/api';
			lct_include( "{$dir}/comments.php" );
			lct_include( "{$dir}/dynamic_css.php" );
			lct_include( "{$dir}/geocode.php" );
			lct_include( "{$dir}/get.php" );
			lct_include( "{$dir}/sort.php" );


			$dir = 'admin';
			lct_load_class( "{$dir}/_admin.php", 'admin', [ 'dir' => $dir ] );
			lct_load_class( "{$dir}/cron.php", 'cron', [ 'dir' => $dir ] );
			lct_load_class( "{$dir}/template_router.php", 'template_router', [ 'dir' => $dir ] );


			$dir = 'features';
			lct_load_class( "{$dir}/access.php", 'access', [ 'dir' => $dir ] );
			lct_load_class( "{$dir}/asset_loader.php", 'asset_loader', [ 'dir' => $dir ] );
			lct_load_class( "{$dir}/comments.php", 'comments', [ 'dir' => $dir ] );
			lct_load_class( "{$dir}/content.php", 'content', [ 'dir' => $dir ] );
			lct_load_class( "{$dir}/nav_menu_cache.php", 'nav_menu_cache', [ 'dir' => $dir ] );
			lct_load_class( "{$dir}/theme_chunk.php", 'theme_chunk', [ 'dir' => $dir ] );


			$dir = 'features/class';
			//Don't need to load this, We will just load it when we need it. So it does need to be available at all times
			//$mail = new lct_features_class_mail();
			//lct_load_class( 'features/class/mail.php', 'mail', [ 'dir' => $dir ] );
			lct_include( "{$dir}/mail.php" );


			$dir = 'features/shortcodes';
			lct_load_class( "{$dir}/_shortcodes.php", 'shortcodes', [ 'dir' => $dir ] );
			lct_load_class( "{$dir}/file_processor.php", 'file_processor', [ 'dir' => $dir ] );

			if ( ! defined( 'DISABLE_LTLS' ) ) //We may want to disable this sometimes
			{
				lct_load_class( "{$dir}/internal_link.php", 'internal_link', [ 'dir' => $dir ] );
			}

			lct_load_class( "{$dir}/post_content.php", 'post_content', [ 'dir' => $dir ] );
			lct_load_class( "{$dir}/sort.php", 'sort', [ 'dir' => $dir ] );
			lct_load_class( "{$dir}/static.php", 'static', [ 'dir' => $dir ] );
			lct_load_class( "{$dir}/tel_link.php", 'tel_link', [ 'dir' => $dir ] );


			$dir = 'wp_api';
			lct_load_class( "{$dir}/general.php", 'general', [ 'dir' => $dir ] );


			/**
			 * wp-admin
			 */
			if ( is_admin() ) {
				$dir = 'wp-admin/admin';
				lct_load_class( "{$dir}/_admin.php", 'admin', [ 'dir' => $dir ] );
				lct_load_class( "{$dir}/loader.php", 'loader', [ 'dir' => $dir ] );
				lct_load_class( "{$dir}/onetime.php", 'onetime', [ 'dir' => $dir ] );
			}


			/**
			 * wp-admin
			 */
			if (
				is_admin()
				|| lct_doing_cron()
			) {
				$dir = 'wp-admin/admin';
				lct_load_class( "{$dir}/update.php", 'update', [ 'dir' => $dir ] );
				lct_load_class( "{$dir}/update_extras.php", 'update_extras', [ 'dir' => $dir ] );
			}


			/**
			 * front-end
			 */
			//if ( ! is_admin() ) {}
		}


		/**
		 * Load up all classes that extend others' plugins
		 * Called on action: 'init' priority: 5
		 *
		 * @since    LCT 7.38
		 * @verified 2017.09.29
		 */
		function load_plugin_extensions()
		{
			//bail early if already ran
			if ( lct_did() ) {
				return;
			}


			/**
			 * ALWAYS FIRST
			 * advanced-custom-fields-pro
			 */
			$plugin = 'acf';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_include( "{$dir}/ajax.php" );
				lct_include( "{$dir}/_admin.php" );

				lct_load_class( "{$dir}/_shortcodes.php", 'shortcodes', [ 'plugin' => $plugin ] );

				lct_load_class( "{$dir}/filters_load_value.php", 'filters_load_value', [ 'plugin' => $plugin ] );
				lct_load_class( "{$dir}/filters_update_value.php", 'filters_update_value', [ 'plugin' => $plugin ] );

				lct_load_class( "{$dir}/dev_checks.php", 'dev_checks', [ 'plugin' => $plugin ] );
				lct_load_class( "{$dir}/display_form.php", 'display_form', [ 'plugin' => $plugin ] );
				lct_load_class( "{$dir}/form.php", 'form', [ 'plugin' => $plugin ] );
				lct_load_class( "{$dir}/instant_save.php", 'instant_save', [ 'plugin' => $plugin ] );


				/**
				 * wp-admin
				 */
				if ( is_admin() ) {
					$plugin = "wp-admin/{$plugin}";
					$dir    = "wp-admin/{$dir}";
					lct_load_class( "{$dir}/_admin.php", 'admin', [ 'plugin' => $plugin ] );
					lct_load_class( "{$dir}/_actions.php", 'actions', [ 'plugin' => $plugin ] );
				}
			}


			/**
			 * Avada Theme
			 */
			$plugin = 'Avada';
			$dir    = "plugins/{$plugin}";

			if ( lct_theme_active( $plugin ) ) {
				lct_include( "{$dir}/api/_helpers.php" );
				lct_include( "{$dir}/api/get.php" );


				lct_load_class( "{$dir}/_admin.php", 'admin', [ 'plugin' => $plugin ] );
				lct_load_class( "{$dir}/_shortcodes.php", 'shortcodes', [ 'plugin' => $plugin ] );
				lct_load_class( "{$dir}/header.php", 'header', [ 'plugin' => $plugin ] );
				lct_load_class( "{$dir}/team.php", 'team', [ 'plugin' => $plugin ] );
				lct_load_class( "{$dir}/testimony.php", 'testimony', [ 'plugin' => $plugin ] );
			}


			/**
			 * admin-menu-editor
			 */
			$plugin = 'admin-menu-editor';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				/**
				 * wp-admin
				 */
				if ( is_admin() ) {
					$plugin = "wp-admin/{$plugin}";
					$dir    = "wp-admin/{$dir}";
					lct_load_class( "{$dir}/_action.php", 'action', [ 'plugin' => $plugin ] );
				}
			}


			/**
			 * gravityforms
			 */
			$plugin = 'gforms';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_include( "{$dir}/api/_helpers.php" );


				lct_load_class( "{$dir}/_admin.php", 'admin', [ 'plugin' => $plugin ] );
				lct_load_class( "{$dir}/_shortcodes.php", 'shortcodes', [ 'plugin' => $plugin ] );
			}


			/**
			 * maintenance
			 */
			$plugin = 'maintenance';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_load_class( "{$dir}/_admin.php", 'admin', [ 'plugin' => $plugin ] );
			}


			/**
			 * NKS-custom
			 */
			$plugin = 'nks';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				/**
				 * wp-admin
				 */
				if ( is_admin() ) {
					$plugin = "wp-admin/{$plugin}";
					$dir    = "wp-admin/{$dir}";
					lct_load_class( "{$dir}/_admin.php", 'admin', [ 'plugin' => $plugin ] );
				}
			}


			/**
			 * q2w3-fixed-widget
			 */
			$plugin = 'q2w3';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_load_class( "{$dir}/_admin.php", 'admin', [ 'plugin' => $plugin ] );
			}


			/**
			 * redirection
			 */
			$plugin = 'redirection';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				/**
				 * wp-admin
				 */
				if ( is_admin() ) {
					$plugin = "wp-admin/{$plugin}";
					$dir    = "wp-admin/{$dir}";
					lct_load_class( "{$dir}/_actions.php", 'actions', [ 'plugin' => $plugin ] );
				}
			}


			/**
			 * revslider
			 */
			$plugin = 'revslider';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_load_class( "{$dir}/_admin.php", 'admin', [ 'plugin' => $plugin ] );
			}


			/**
			 * simple-image-widget
			 */
			$plugin = 'siw';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_load_class( "{$dir}/widget.php", 'widget', [ 'plugin' => $plugin ] );
			}


			/**
			 * stream
			 */
			$plugin = 'stream';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_load_class( "{$dir}/_admin.php", 'admin', [ 'plugin' => $plugin ] );
			}


			/**
			 * taxonomy-terms-order
			 */
			$plugin = 'to';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_load_class( "{$dir}/_filter.php", 'filter', [ 'plugin' => $plugin ] );
			}


			/**
			 * w3-total-cache
			 */
			$plugin = 'w3tc';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_load_class( "{$dir}/_action.php", 'action', [ 'plugin' => $plugin ] );
			}


			/**
			 * woocommerce
			 */
			$plugin = 'wc';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_include( "{$dir}/api/_helpers.php" );


				lct_load_class( "{$dir}/emails/__init.php", 'emails', [ 'plugin' => $plugin ] );

				lct_load_class( "{$dir}/_admin.php", 'admin', [ 'plugin' => $plugin ] );
				lct_load_class( "{$dir}/_shortcodes.php", 'shortcodes', [ 'plugin' => $plugin ] );
			}


			/**
			 * wp-mail-smtp
			 */
			$plugin = 'wp-mail-smtp';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_load_class( "{$dir}/_admin.php", 'admin', [ 'plugin' => $plugin ] );
			}


			/**
			 * wp-rocket
			 */
			$plugin = 'wp-rocket';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_load_class( "{$dir}/_admin.php", 'admin', [ 'plugin' => $plugin ] );
			}


			/**
			 * wp-sweep
			 */
			$plugin = 'wp-sweep';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_load_class( "{$dir}/_filter.php", 'filter', [ 'plugin' => $plugin ] );
			}


			/**
			 * wp-sync-db
			 */
			$plugin = 'wpsdb';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				/**
				 * wp-admin
				 */
				if ( is_admin() ) {
					$plugin = "wp-admin/{$plugin}";
					$dir    = "wp-admin/{$dir}";
					lct_load_class( "{$dir}/_admin.php", 'admin', [ 'plugin' => $plugin ] );
				}
			}


			/**
			 * wordfence
			 */
			$plugin = 'wf';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				/**
				 * wp-admin
				 */
				if ( is_admin() ) {
					$plugin = "wp-admin/{$plugin}";
					$dir    = "wp-admin/{$dir}";
					lct_load_class( "{$dir}/_admin.php", 'admin', [ 'plugin' => $plugin ] );
				}
			}


			/**
			 * wordpress-seo
			 */
			$plugin = 'yoast';
			$dir    = "plugins/{$plugin}";

			if ( lct_plugin_active( $plugin ) ) {
				lct_load_class( "{$dir}/_filter.php", 'filter', [ 'plugin' => $plugin ] );
			}
		}


		/**
		 * Returns true if has setting
		 *
		 * @param string $name
		 *
		 * @return bool
		 * @since    LCT 2019.18
		 * @verified 2019.07.15
		 */

		function has_setting( $name )
		{
			return isset( $this->settings[ $name ] );
		}


		/**
		 * Returns a setting
		 *
		 * @param string $name
		 *
		 * @return mixed
		 * @since    LCT 7.38
		 * @verified 2019.07.15
		 */
		function get_setting( $name )
		{
			return isset( $this->settings[ $name ] ) ? $this->settings[ $name ] : null;
		}


		/**
		 * Updates a setting
		 *
		 * @param string $name
		 * @param mixed  $value
		 *
		 * @return bool
		 * @since    LCT 7.38
		 * @verified 2019.07.15
		 */
		function update_setting( $name, $value )
		{
			$this->settings[ $name ] = $value;


			return true;
		}


		/**
		 * Returns data
		 *
		 * @param string $name
		 *
		 * @return mixed
		 * @since    LCT 2019.18
		 * @verified 2019.07.15
		 */
		function get_data( $name )
		{
			return isset( $this->data[ $name ] ) ? $this->data[ $name ] : null;
		}


		/**
		 * Sets data
		 *
		 * @param string $name
		 * @param mixed  $value
		 *
		 * @return bool
		 * @since    LCT 2019.18
		 * @verified 2019.07.15
		 */
		function set_data( $name, $value )
		{
			$this->data[ $name ] = $value;


			return true;
		}


		/**
		 * Only runs when the plugin is activated
		 *
		 * @since    LCT 0.0
		 * @verified 2017.07.31
		 */
		function activate()
		{
			lct_update_option( 'run_post_plugin_update', true );
		}


		/**
		 * Only runs when the plugin is deactivated
		 * DON'T USE ANY LCT CLASSES, they won't be loaded
		 * ALL wp_schedule_event()
		 *
		 * @since    LCT 0.0
		 * @verified 2018.03.05
		 */
		function deactivate()
		{
			/**
			 * clear scheduled cron events
			 */
			$events = [
				'lct_auto_set_lct_api',
				'lct_add_default_wp_users',
				'lct_emergency_hack_checker',
				'PDER_cron_send_reminders',
			];


			foreach ( $events as $event ) {
				while( wp_next_scheduled( $event ) ) {
					$timestamp = wp_next_scheduled( $event );
					wp_unschedule_event( $timestamp, $event );
				}
			}
		}


		/**
		 * Only runs when the plugin is uninstalled
		 * DON'T USE ANY LCT CLASSES, they won't be loaded
		 *
		 * @since    LCT 0.0
		 * @verified 2017.07.31
		 */
		static function uninstall()
		{
			delete_option( 'lct_version' );
		}
	}


	/**
	 * The main function responsible for returning the one true lct Instance to functions everywhere.
	 * Use this function like you would a global variable, except without needing to declare the global.
	 * Example: <?php $lct = lct(); ?>
	 *
	 * @return lct object
	 * @since    LCT 7.38
	 * @verified 2017.07.31
	 */
	function lct()
	{
		global $lct;


		if ( ! isset( $lct ) ) {
			$lct = new lct();

			$lct->init();
		}


		return $lct;
	}


	/**
	 * Initialize
	 */
	lct();
endif;
