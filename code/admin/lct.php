<?php
/** @noinspection PhpMissingFieldTypeInspection */
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


//Just stop the madness if WP is busy installing
if ( ! defined( 'WP_INSTALLING' ) ) {
	/**
	 * SPECIAL CRITICAL ERROR NOTIFICATION :: ALSO IN MU
	 * Always email the admin when there is a critical error
	 *
	 * @date       2022.02.11
	 * @since      2022.1
	 * @verified   2022.02.11
	 */
	//add_filter( 'recovery_mode_email_rate_limit', function () {return 1;return HOUR_IN_SECONDS;}, 99999 );
	add_filter( 'is_protected_endpoint', '__return_true', 99999 );
	add_filter( 'recovery_mode_email', 'lct_mu_recovery_mode_email', 99999, 2 );
	add_filter( 'recovery_mode_email', 'lct_mu_recovery_mode_email_override', 99999, 2 );


	/**
	 * SPECIAL CRITICAL ERROR NOTIFICATION :: ALSO IN MU
	 * Make sure the email is formatted properly & easily grabbed by an email filter
	 *
	 * @param array $email
	 *
	 * @unused     param string $url
	 * @date       2022.02.11
	 * @since      2022.1
	 * @verified   2022.02.11
	 */
	function lct_mu_recovery_mode_email( $email )
	{
		/**
		 * Add filter text to the subject
		 */
		if ( strpos( $email['subject'], '[WP CRITICAL ERROR]' ) === false ) {
			$email['subject'] = '[WP CRITICAL ERROR] ' . $email['subject'];
		}


		/**
		 * Force Plain Text
		 */
		add_filter( 'wp_mail_content_type', //Do not place hook on new line
			function () {
				return 'text/plain';
			},
			99999
		);


		return $email;
	}


	/**
	 * SPECIAL CRITICAL ERROR NOTIFICATION
	 * Make sure the email is formatted properly & easily grabbed by an email filter
	 *
	 * @param array $email
	 *
	 * @unused     param string $url
	 * @date       2022.04.26
	 * @since      2022.4
	 * @verified   2022.05.10
	 */
	function lct_mu_recovery_mode_email_override( $email )
	{
		/**
		 * Add filter text to the subject & message
		 */
		$override = false;
		$plugin   = $error = $full_error = 'Unknown';


		if ( isset( $email['message'] ) ) {
			if (
				( $tmp_plugin = 'CallRail Phone' )
				&& strpos( $email['message'], 'Current plugin: ' . $tmp_plugin ) !== false
			) {
				/**
				 * Reference
				 * Line: if (array_key_exists($key, $params)) {
				 * Function calltrk_set_cookie()
				 */
				if (
					( $tmp_error_1 = 'E_ERROR was caused in line 255 of the file' )
					&& ( $tmp_error_2 = '/plugins/callrail-phone-call-tracking/callrail.php' )
					&& strpos( $email['message'], $tmp_error_1 ) !== false
					&& strpos( $email['message'], $tmp_error_2 ) !== false
				) {
					$override   = true;
					$plugin     = $tmp_plugin;
					$error      = $tmp_error_1 . ' ' . $tmp_error_2;
					$full_error = $error;
				}
			}
		}


		if ( $override ) {
			delete_option( 'recovery_mode_email_last_sent' );


			$email['subject'] = '[WP FALSE ALARM ERROR] ' . $plugin . ' ' . $error;
			$email['message'] = '[This is a false alarm that we have overridden for VisArts in MU] ' . "\n" . $full_error . "\n\n\n" . $email['message'];
		}


		return $email;
	}


	/**
	 * @verified 2022.02.17
	 */
	class lct_mu
	{
		public $plugins = [];
		public $plugins_cache = [];
		public $exclude_plugins = [];
		public $theme_swap = false;
		public $action = null;
		public $api = null;
		public $api_info = [
			'full'           => null,
			'version'        => null,
			'route'          => null,
			'extended_route' => null,
		];
		public $special_action = null;
		public $post = [];
		public $fast_ajax = false;
		public $fast_ajax_action = null;
		public $do_core_action_check = true;
		public $core_actions_post = [
			'oembed-cache',
			//'image-editor',
			//'delete-comment',
			//'delete-tag',
			//'delete-link',
			//'delete-meta',
			//'delete-post',
			//'trash-post',
			//'untrash-post',
			//'delete-page',
			'dim-comment',
			'add-link-category',
			//'add-tag',
			'get-tagcloud',
			'get-comments',
			'replyto-comment',
			'edit-comment',
			'add-menu-item',
			'add-meta',
			'add-user',
			'closed-postboxes',
			'hidden-columns',
			'update-welcome-panel',
			'menu-get-metabox',
			'wp-link-ajax',
			'menu-locations-save',
			'menu-quick-search',
			'meta-box-order',
			'get-permalink',
			'sample-permalink',
			'inline-save',
			'inline-save-tax',
			'find_posts',
			//Allow full load - 'widgets-order',
			//Allow full load - 'save-widget',
			'set-post-thumbnail',
			'date_format',
			'time_format',
			'dismiss-wp-pointer',
			//'upload-attachment',
			'get-attachment',
			//'query-attachments',
			//'save-attachment',
			//'save-attachment-compat',
			'send-link-to-editor',
			//'send-attachment-to-editor',
			'save-attachment-order',
			'get-revision-diffs',
			'save-user-color-scheme',
			//Allow full load - 'update-widget',
			'query-themes',
			'parse-embed',
			//'set-attachment-thumbnail',
			'parse-media-shortcode',
			'destroy-sessions',
			'install-plugin',
			'update-plugin',
			'press-this-save-post',
			'press-this-add-category',
			'crop-image',
		];
		public $core_actions_post_exclude = [
			'wp-remove-post-lock',
		];

		public $acf = 'advanced-custom-fields-pro/acf.php';
		public $fb = 'fusion-builder/fusion-builder.php';
		public $fc = 'fusion-core/fusion-core.php';
		public $googleanalytics = 'google-analytics-for-wordpress/googleanalytics.php';
		public $gforms = 'gravityforms/gravityforms.php';
		public $lct = 'lct-useful-shortcodes-functions/lct-useful-shortcodes-functions.php';
		public $ngg = 'nextgen-gallery/nggallery.php';
		public $smushit = 'wp-smushit/wp-smush.php';
		public $stream = 'stream/stream.php';
		public $to = 'taxonomy-terms-order/taxonomy-terms-order.php';
		public $wp_rocket = 'wp-rocket/wp-rocket.php';
		public $wc = 'woocommerce/woocommerce.php';
		public $wf = 'wordfence/wordfence.php';
		public $wpdiscuz = 'wpdiscuz/class.WpdiscuzCore.php';
		public $wps = 'wp-session-manager/wp-session-manager.php';
		public $yoast = 'wordpress-seo/wp-seo.php';
		public $xpg = 'pimg-gallery/pimg-gallery.php';

		public $wpdiscuz_actions = [
			'dismiss_wpdiscuz_addon_note',
			'dismiss_wpdiscuz_tip_note',
			'loadMoreComments',
			'voteOnComment',
			'wpdiscuzSorting',
			'addComment',
			'getSingleComment',
			'addSubscription',
			'checkNotificationType',
			'redirect',
			'editComment',
			'saveEditedComment',
			'updateAutomatically',
			'updateOnClick',
			'readMore',
			'wpdiscuzCustomFields',
			'adminFieldForm',
			'generateCaptcha',
			'nopriv_generateCaptcha',
		];

		public $doing_update_display_name = false;


		/**
		 * Setup action and filter hooks
		 *
		 * @verified 2017.01.16
		 */
		function __construct()
		{
			$this->init();
		}


		/**
		 * Get the class running
		 *
		 * @since    2017.2
		 * @verified 2023.12.20
		 */
		function init()
		{
			/**
			 * debug
			 */
			/*
			error_log( 'START' );
			error_log( 'PHP_SELF: ' . print_r( $_SERVER['PHP_SELF'], true ) );
			error_log( 'HTTP_REFERER: ' . print_r( $_SERVER['HTTP_REFERER'], true ) );
			error_log( print_r( $_REQUEST, true ) );
			error_log( 'END' );
			error_log( '' );
			error_log( '' );
			error_log( '' );
			*/

			/**
			 * everytime
			 */
			$this->pre_load_other_mus();

			add_action( 'muplugins_loaded', [ $this, 'load_other_mus' ], 2 );

			add_action( 'muplugins_loaded', [ $this, 'load_hooks' ] );


			/**
			 * WP Cron
			 */
			if (
				function_exists( 'wp_doing_cron' )
				&& wp_doing_cron()
			) {
				$this->exclude_plugins[ $this->wps ] = $this->wps;


				add_action( 'muplugins_loaded', [ $this, 'active_plugins' ] );
			}


			/**
			 * API Call
			 */
			if ( strpos( $_SERVER["REQUEST_URI"], 'wp-json' ) !== false ) {
				$URI_parts = explode( 'wp-json/', $_SERVER["REQUEST_URI"] );
				if ( ! empty( $URI_parts[1] ) ) {
					$this->api = $this->api_info['full'] = $URI_parts[1];


					$URI_parts = explode( '/', $URI_parts[1] );
					if ( ! empty( $URI_parts[0] ) ) {
						$this->api = $URI_parts[0];
					}
				}


				if ( isset( $URI_parts[1] ) ) {
					$this->api_info['version'] = $URI_parts[1];
				}


				if ( isset( $URI_parts[2] ) ) {
					$this->api_info['route'] = $URI_parts[2];
				}


				if ( isset( $URI_parts[3] ) ) {
					$this->api_info['extended_route'] = $URI_parts[3];
				}


				add_action( 'muplugins_loaded', [ $this, 'api_checker' ], 7 );


				add_action( 'muplugins_loaded', [ $this, 'set_theme_swap' ] );
				add_action( 'muplugins_loaded', [ $this, 'active_plugins' ] );
			}


			/**
			 * only if we are DOING_AJAX
			 */
			if (
				! isset( $_POST['action'] )
				&& isset( $_GET['action'] )
			) {
				$_POST['action'] = $_GET['action'];
			}


			if (
				(
					isset( $_POST )
					&& isset( $_POST['action'] )
					&& defined( 'DOING_AJAX' )
					&& DOING_AJAX
				)
				|| ! empty( $_POST['lct_special_action'] )
			) {
				if ( ! empty( $_POST['lct_special_action'] ) ) {
					$this->special_action = $this->action = $_POST['lct_special_action'];
				} else {
					$this->action = $_POST['action'];
				}

				$this->post = $_POST;


				/**
				 * Allow 3rd parties to alter the class
				 *
				 * @date     0.0
				 * @since    2018.52
				 * @verified 2021.08.27
				 */
				do_action( 'lct_mu/init', $this );


				/**
				 * Is a core action running?
				 */
				if ( in_array( $this->action, $this->core_actions_post_exclude ) ) {
					$this->action = 'core_action_exclude';
				}

				if ( $this->do_core_action_check ) {
					if ( in_array( $this->action, $this->core_actions_post ) ) {
						$this->action = 'core_action';
					} elseif ( in_array( $this->action, $this->wpdiscuz_actions ) ) {
						$this->action = 'wpdiscuz';
					}
				}


				add_action( 'muplugins_loaded', [ $this, 'ajax_checker' ], 4 );

				add_action( 'muplugins_loaded', [ $this, 'set_theme_swap' ] );
				add_action( 'muplugins_loaded', [ $this, 'active_plugins' ] );
			}
		}


		/**
		 * Load other mus, before this one
		 *
		 * @since    2018.52
		 * @verified 2018.06.06
		 */
		function pre_load_other_mus()
		{
			/**
			 * @date     0.0
			 * @since    2018.52
			 * @verified 2021.08.27
			 */
			do_action( 'lct_mu/pre_load_mu' );
		}


		/**
		 * Load other mus
		 *
		 * @since    2017.3
		 * @verified 2017.01.23
		 */
		function load_other_mus()
		{
			/**
			 * @date     0.0
			 * @since    2017.3
			 * @verified 2021.08.27
			 */
			do_action( 'lct_mu/load_mu' );
		}


		/**
		 * Load hooks
		 *
		 * @since    2019.25
		 * @verified 2019.09.11
		 */
		function load_hooks()
		{
			add_action( 'set_current_user', [ $this, 'update_display_name' ] );
		}


		/**
		 * Let's check the API call and get something done
		 *
		 * @since    2019.3
		 * @verified 2019.02.18
		 */
		function api_checker()
		{
			switch ( $this->api ) {
				/**
				 * Nothing Yet
				 */
				case 'nothing_yet':
					break;


				default:
			}
		}


		/**
		 * Let's check the action and get something done
		 *
		 * @since        2017.2
		 * @verified     2023.11.01
		 * @noinspection PhpDuplicateSwitchCaseBodyInspection
		 */
		function ajax_checker()
		{
			$hit = 0;


			switch ( $this->action ) {
				/**
				 * WP Heartbeat
				 */
				case 'heartbeat':
					//This will prevent false debug breakpoints from being triggered
					if ( file_exists( WP_PLUGIN_DIR . '/' . $this->wf ) ) {
						$this->add_to_plugins( $this->wf );
					}


					$this->add_to_plugins( $this->acf );
					$this->add_to_plugins( $this->lct );
					$this->add_to_plugins( $this->stream );


					if ( ! empty( $_REQUEST['data'] ) ) {
						foreach ( $_REQUEST['data'] as $data_key => $data ) {
							if ( strpos( $data_key, 'gform' ) === 0 ) {
								$this->add_to_plugins( $this->gforms );
								break;
							}
						}
					}


					$this->theme_swap = true;


					$this->fast_ajax        = true;
					$this->fast_ajax_action = $this->action;


					$hit ++;
					break;


				/**
				 * Main WP actions
				 */
				case 'core_action_exclude':
					$this->exclude_plugins[ $this->lct ] = $this->lct;
					$this->exclude_plugins[ $this->xpg ] = $this->xpg;


					$this->theme_swap = true;


					$hit ++;
					break;


				case 'core_action':
					$this->theme_swap = false;

					if ( file_exists( WP_PLUGIN_DIR . '/' . $this->wf ) ) {
						$this->add_to_plugins( $this->wf );
					}

					$this->add_to_plugins( $this->acf );
					$this->add_to_plugins( $this->fb );
					$this->add_to_plugins( $this->fc );
					$this->add_to_plugins( $this->lct );
					$this->add_to_plugins( $this->ngg );


					$hit ++;
					break;


				case 'acf/fields/select/query':
					/**
					 * Special fields
					 */
					$special_fields = [
						'field_587529374a05a', //lct:::css_files
						'field_587d8ab2b6460', //lct:::js_files
					];


					if (
						! empty( $this->post['field_key'] )
						&& in_array( $this->post['field_key'], $special_fields )
					) {
						$this->theme_swap = false;
						$this->add_to_plugins( $this->fb );
						$this->add_to_plugins( $this->fc );
					} else {
						$this->theme_swap = true;
					}


					$this->add_to_plugins( $this->acf );
					$this->add_to_plugins( $this->lct );


					$hit ++;
					break;


				case 'monsterinsights_vue_get_notifications':
					$this->theme_swap = true;
					$this->add_to_plugins( $this->googleanalytics );


					$hit ++;
					break;


				case 'lct_acf_instant_save':
					$this->theme_swap = true;
					$this->add_to_plugins( $this->acf );
					$this->add_to_plugins( $this->lct );

					if (
						isset( $this->post['fast_ajax'] )
						&& $this->post['fast_ajax'] === true
					) {
						$this->fast_ajax        = true;
						$this->fast_ajax_action = $this->action;
					}


					$hit ++;
					break;


				case 'lct_send_password':
					$this->theme_swap = true;
					$this->add_to_plugins( $this->acf );
					$this->add_to_plugins( $this->lct );
					$this->add_to_plugins( $this->wc );


					$hit ++;
					break;


				case 'lct_theme_chunk':
					$this->add_to_plugins( $this->acf );
					$this->add_to_plugins( $this->lct );
					$this->theme_swap = false;
					$this->add_to_plugins( $this->fb );
					$this->add_to_plugins( $this->fc );


					$hit ++;
					break;


				case 'smush_notice_s3_support_required':
					$this->theme_swap = true;
					$this->add_to_plugins( $this->smushit );


					$hit ++;
					break;


				case 'wpdiscuz':
					$this->theme_swap = true;
					$this->add_to_plugins( $this->acf );
					$this->add_to_plugins( $this->lct );
					$this->add_to_plugins( $this->wpdiscuz );


					$hit ++;
					break;


				case 'xpg_gallery_get_modal_content':
					$this->theme_swap = false;
					$this->add_to_plugins( $this->acf );
					$this->add_to_plugins( $this->fb );
					$this->add_to_plugins( $this->fc );
					$this->add_to_plugins( $this->lct );
					$this->add_to_plugins( $this->xpg );


					$hit ++;
					break;


				case (
					strpos( $this->action, 'acf/' ) !== false
					&& strpos( $this->action, 'taxonomy' ) !== false
				):
					$this->theme_swap = true;
					$this->add_to_plugins( $this->acf );
					$this->add_to_plugins( $this->lct );
					$this->add_to_plugins( $this->to );


					$hit ++;
					break;


				case (
					strpos( $this->action, 'acf/' ) !== false
					&& strpos( $this->action, '/query' ) !== false
				):
					$this->theme_swap = true;
					$this->add_to_plugins( $this->acf );
					$this->add_to_plugins( $this->lct );


					$hit ++;
					break;


				/**
				 * This needs to go last it is a catchall for our custom actions
				 */
				case ( strpos( $this->action, 'acf/' ) !== false ):
				case ( strpos( $this->action, 'lct_' ) !== false ):
					$this->theme_swap = true;
					$this->add_to_plugins( $this->acf );
					$this->add_to_plugins( $this->lct );


					$hit ++;
					break;


				default:
			}


			if ( $hit ) {
				$this->ajax_hit();
			}
		}


		/**
		 * This is called on all successful ajax calls
		 *
		 * @date     2020.11.10
		 * @since    2020.14
		 * @verified 2020.11.10
		 */
		function ajax_hit()
		{
			$this->prevent_avada_cache_clearing();
		}


		/**
		 * This keeps ajax calls from always clearing our caches
		 *
		 * @date     2020.11.10
		 * @since    2020.14
		 * @verified 2020.11.10
		 */
		function prevent_avada_cache_clearing()
		{
			add_filter( 'pre_option_fusion_supported_plugins_active', [ $this, 'pre_option_fusion_supported_plugins_active' ], 9999, 3 );
			add_filter( 'pre_update_option_fusion_supported_plugins_active', [ $this, 'pre_update_option_fusion_supported_plugins_active' ], 9999, 3 );
			add_filter( 'reset_all_caches', [ $this, 'reset_all_caches' ], 999 );
		}


		/**
		 * This keeps ajax calls from always clearing our caches
		 *
		 * @param mixed $pre_option
		 * @param sting $option
		 * @param mixed $default
		 *
		 * @return mixed
		 * @date         2020.11.10
		 * @since        2020.14
		 * @verified     2020.11.10
		 * @noinspection PhpMissingParamTypeInspection
		 * @noinspection PhpUnusedParameterInspection
		 */
		function pre_option_fusion_supported_plugins_active( $pre_option, $option, $default )
		{
			global $lct_mu_pre_option_fusion_supported_plugins_active;

			if ( $lct_mu_pre_option_fusion_supported_plugins_active ) {
				return $lct_mu_pre_option_fusion_supported_plugins_active;
			}

			global $wpdb;


			if ( ( $row = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $option ) ) ) ) {
				if ( is_object( $row ) ) {
					$lct_mu_pre_option_fusion_supported_plugins_active = $default = maybe_unserialize( $row->option_value );
				}
			}


			return $default;
		}


		/**
		 * This keeps ajax calls from always clearing our caches
		 *
		 * @param mixed  $value
		 * @param mixed  $old_value
		 * @param string $option
		 *
		 * @return mixed
		 * @date         2020.11.10
		 * @since        2020.14
		 * @verified     2020.11.10
		 * @noinspection PhpMissingParamTypeInspection
		 * @noinspection PhpUnusedParameterInspection
		 */
		function pre_update_option_fusion_supported_plugins_active( $value, $old_value, $option )
		{
			global $lct_mu_pre_option_fusion_supported_plugins_active;

			if ( $lct_mu_pre_option_fusion_supported_plugins_active ) {
				return $old_value;
			}


			return $value;
		}


		/**
		 * This keeps ajax calls from always clearing our caches
		 *
		 * @param array $caches
		 *
		 * @return array
		 * @date         2020.11.10
		 * @since        2020.14
		 * @verified     2020.11.10
		 * @noinspection PhpMissingParamTypeInspection
		 */
		function reset_all_caches( $caches )
		{
			foreach ( $caches as $k => $cache ) {
				$caches[ $k ] = false;
			}


			return $caches;
		}


		/**
		 * Add to plugins array
		 *
		 * @param $new_plugin
		 *
		 * @since    2017.2
		 * @verified 2017.01.16
		 */
		function add_to_plugins( $new_plugin )
		{
			$this->plugins = array_unique( array_merge( $this->plugins, [ $new_plugin ] ) );
		}


		/**
		 * Set this filter, so we can let WP know what we need loaded
		 *
		 * @since    2017.2
		 * @verified 2017.01.16
		 */
		function active_plugins()
		{
			if (
				! empty( $this->plugins )
				|| ! empty( $this->exclude_plugins )
			) {
				add_filter( 'option_active_plugins', [ $this, 'do_active_plugins' ] );
				add_filter( 'site_option_active_sitewide_plugins', [ $this, 'do_active_plugins' ] );
			}
		}


		/**
		 * Return the active_plugins we want for this action
		 *
		 * @param $plugins
		 *
		 * @return array
		 * @since    2017.2
		 * @verified 2018.03.13
		 */
		function do_active_plugins( $plugins )
		{
			if ( ! empty( $this->plugins_cache ) ) {
				$plugins = $this->plugins_cache;
			} elseif (
				$this->plugins_cache !== null
				&& ! empty( $plugins )
			) {
				$foreach_plugins = $plugins;
				$is_multisite    = false;

				if ( current_filter() === 'site_option_active_sitewide_plugins' ) {
					$is_multisite = true;
				}


				if ( $is_multisite ) {
					$foreach_plugins = array_keys( $foreach_plugins );
				}


				foreach ( $foreach_plugins as $key => $plugin ) {
					if ( $is_multisite ) {
						$key = $plugin;
					}


					if (
						$this->plugins
						&& ! in_array( $plugin, $this->plugins )
					) {
						unset( $plugins[ $key ] );
					}


					if (
						$this->exclude_plugins
						&& in_array( $plugin, $this->exclude_plugins )
					) {
						if ( isset( $plugins[ $key ] ) ) {
							unset( $plugins[ $key ] );
						}


						unset( $this->exclude_plugins[ $plugin ] );
					}
				}


				$this->plugins_cache = $plugins;
			}


			return $plugins;
		}


		/**
		 * Swap to a non-existent theme to speed things up
		 *
		 * @since    2017.2
		 * @verified 2017.01.16
		 */
		function set_theme_swap()
		{
			if ( $this->theme_swap == true ) {
				add_filter( 'option_current_theme', [ $this, 'do_theme_swap' ] );
				add_filter( 'option_template', [ $this, 'do_theme_swap' ] );
				add_filter( 'option_stylesheet', [ $this, 'do_theme_swap' ] );
			}
		}


		/**
		 * Return a non-existent theme value
		 *
		 * @return string
		 * @since    2017.2
		 * @verified 2017.01.16
		 */
		function do_theme_swap()
		{
			return 'None';
		}


		/**
		 * Programmatically update Display Name or First Name & Last Name when some info is missing
		 *
		 * @since    2019.25
		 * @verified 2021.11.02
		 */
		function update_display_name()
		{
			global $current_user;


			if (
				empty( $current_user->ID )
				|| $this->doing_update_display_name
			) {
				return;
			}


			$this->doing_update_display_name = true;


			if ( ! ( $display_name = trim( $current_user->display_name ) ) ) {
				global $user_identity;


				if ( ! ( $display_name = trim( trim( $current_user->first_name ) . ' ' . trim( $current_user->last_name ) ) ) ) {
					if ( trim( $current_user->nickname ) ) {
						$display_name = trim( $current_user->nickname );
					} else {
						$display_name = trim( $current_user->user_login );
					}


					$current_user = $this->update_names( $current_user, $display_name, false );
				}


				if ( $display_name ) {
					$current_user->display_name = $user_identity = $display_name;


					wp_update_user( $current_user );
				}
			} elseif (
				(
					! trim( $current_user->first_name )
					&& ! trim( $current_user->last_name )
				)
				|| (
					! trim( $current_user->first_name )
					&& trim( $current_user->last_name )
				)
			) {
				$current_user = $this->update_names( $current_user, $display_name );
			}


			$this->doing_update_display_name = false;
		}


		/**
		 * @param WP_User $current_user
		 * @param string  $display_name
		 * @param bool    $save_now
		 *
		 * @return WP_User
		 * @since        2019.25
		 * @verified     2019.09.11
		 * @noinspection PhpMissingParamTypeInspection
		 */
		function update_names( $current_user, $display_name, $save_now = true )
		{
			if ( ! trim( $current_user->nickname ) ) {
				$current_user->nickname = $display_name;
			}


			if ( strpos( $display_name, ' ' ) === false ) {
				$current_user->first_name = $display_name;
				$current_user->last_name  = '';
			} else {
				$display_name = explode( ' ', $display_name );


				$current_user->first_name = $display_name[0];


				unset( $display_name[0] );


				$display_name = implode( ' ', $display_name );


				$current_user->last_name = $display_name;
			}


			if ( $save_now ) {
				wp_update_user( $current_user );
			}


			return $current_user;
		}
	}


	$lct_mu = new lct_mu();


}
