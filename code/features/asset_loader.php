<?php
/** @noinspection PhpMissingFieldTypeInspection */
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2016.12.17
 * //TODO: cs - Need to clean this up - 12/23/2016 09:39 AM
 */
class lct_features_asset_loader
{
	public $handle_main;
	public $handle_main_admin;
	public $wp_head_last_wp_print_styles = [];
	public $wp_head_last_wp_print_scripts = [];
	public $admin_head_last_wp_print_styles = [];
	public $admin_head_last_wp_print_scripts = [];
	/**
	 * @var string
	 */
	public string $alert_type = '';


	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2019.02.11
	 */
	function __construct( $args = [] )
	{
		//Store $args
		$this->args = $args;


		//Store parent class, maybe
		if ( $this->args['load_parent'] ) {
			$this->zxzp = lct();
		}


		global $lct_features_asset_loader;


		if ( ! $lct_features_asset_loader ) {
			$lct_features_asset_loader = $this;
		}


		$this->handle_main       = zxzu( 'custom' );
		$this->handle_main_admin = zxzu( 'custom_admin' );

		lct_update_setting( 'handle_main', $this->handle_main );
		lct_update_setting( 'handle_main_admin', $this->handle_main_admin );


		//Setup WordPress action and filter hooks
		$this->load_hooks();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    7.49
	 * @verified 2018.05.19
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
		add_action( 'lct_jq_doc_ready_add', [ $this, 'jq_doc_ready_add' ], 10, 2 );

		add_action( 'lct_wp_footer_style_add', [ $this, 'wp_footer_style_add' ] );

		add_action( 'lct_jq_autosize', [ $this, 'jq_autosize' ] );
		add_action( 'lct_jquery_autosize_min_js', [ $this, 'jq_autosize' ] ); //old version that may still be in use

		add_action( 'lct/maps_google_api', [ $this, 'maps_google_api' ] );


		if ( lct_frontend() ) {
			add_action( 'wp_head', [ $this, 'wp_head_after_beginning_of_head' ], 1 );
			add_action( 'wp_head', [ $this, 'wp_head_last' ], 2000000 );

			add_action( 'wp_footer', [ $this, 'wp_footer_last' ], 2000000 );

			add_action( 'init', [ $this, 'register_main_styles' ] );
			add_action( 'init', [ $this, 'register_main_scripts' ] );

			//add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_styles' ] );
			//add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
		}


		//if ( lct_wp_admin_all() ) {}


		if ( lct_wp_admin_non_ajax() ) {
			global $wp_version;


			add_action( 'admin_head', [ $this, 'admin_head_last' ], 2000000 );


			if ( version_compare( $wp_version, '4.4', '<' ) ) { //WP older than v4.4
				add_action( 'admin_head', [ $this, 'admin_register_main_styles' ] );
				add_action( 'admin_head', [ $this, 'admin_register_main_scripts' ] );
			} else {
				add_action( 'init', [ $this, 'admin_register_main_styles' ] );
				add_action( 'init', [ $this, 'admin_register_main_scripts' ] );
			}


			//add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_styles' ] );
			//add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Add a document ready item
	 *
	 * @param        $item
	 * @param string $location
	 */
	function jq_doc_ready_add( $item, $location = 'wp_footer' )
	{
		global ${zxzu( 'jq_doc_ready_' . $location )};


		if ( ! ${zxzu( 'jq_doc_ready_' . $location )} ) {
			${zxzu( 'jq_doc_ready_' . $location )} = [];
		}

		${zxzu( 'jq_doc_ready_' . $location )}[] = $item;


		add_action( "{$location}", [ $this, 'jq_doc_ready' ], 999 );
	}


	/**
	 * Print out any custom document ready items that have been queued
	 * *
	 * Called as an action in jq_doc_ready_add()
	 */
	function jq_doc_ready()
	{
		$current_action = current_action();

		global ${zxzu( 'jq_doc_ready_' . $current_action )};

		$jq = ${zxzu( 'jq_doc_ready_' . $current_action )};


		if ( $jq ) {
			$jq = array_unique( $jq );

			echo sprintf( '<%s>jQuery(document).ready( function() {%s});<%s>', 'script', lct_return( $jq ), '/script' );
		}
	}


	/**
	 * Add a CSS item
	 *
	 * @param $item
	 */
	function wp_footer_style_add( $item )
	{
		global ${zxzu( 'wp_footer_style' )};


		if ( ! ${zxzu( 'wp_footer_style' )} ) {
			${zxzu( 'wp_footer_style' )} = [];
		}

		${zxzu( 'wp_footer_style' )}[] = $item;


		add_action( 'wp_footer', [ $this, 'wp_footer_style' ], 998 );
	}


	/**
	 * Print out any custom CSS items that have been queued
	 * *
	 * Called as an action in wp_footer_style_add()
	 */
	function wp_footer_style()
	{
		global ${zxzu( 'wp_footer_style' )};

		$style = ${zxzu( 'wp_footer_style' )};


		if ( $style ) {
			$style = array_unique( $style );

			echo sprintf( '<style>%s</style>', lct_return( $style ) );
		}
	}


	/**
	 * Register Scripts
	 */
	function jq_autosize_wp_enqueue_scripts()
	{
		lct_enqueue_script( zxzu( 'jq_autosize' ), lct_get_root_url( 'includes/autosize/autosize.min.js' ) );
		lct_admin_enqueue_script( zxzu( 'jq_autosize' ), lct_get_root_url( 'includes/autosize/autosize.min.js' ) );
	}


	/**
	 * ADD autosize.js assets when they are needed
	 */
	function jq_autosize()
	{
		add_action( 'wp_enqueue_scripts', [ $this, 'jq_autosize_wp_enqueue_scripts' ], 1999990 );
	}


	/**
	 * Router the handle to either be printed or added as an inline style
	 *
	 * @param string $type
	 * @param string $loc
	 * @param        $handle
	 * @param        $src
	 * @param bool   $force_no_inline
	 * @param array  $deps
	 * @param bool   $ver
	 * @param bool   $media_in_footer
	 * @param null   $plugin
	 *
	 * @since    0.0
	 * @verified 2019.01.29
	 */
	function lct_enqueue( $type = 'script', $loc = 'wp', $handle = null, $src = null, $force_no_inline = false, $deps = [], $ver = false, $media_in_footer = false, $plugin = null )
	{
		$enqueue_handles = lct_get_setting( 'enqueue_handles', [] );
		$shall_we_inline = false;
		$data            = '';


		if ( $ver === false ) {
			if (
				! empty( $plugin )
				&& function_exists( $plugin . '_get_setting' )
			) {
				$ver = call_user_func( $plugin . '_get_setting', 'version' );
			} else {
				$ver = lct_get_setting( 'version' );
			}
		}


		if (
			(
				! is_admin()
				&& $loc == 'admin'
			)
			|| (
				is_admin()
				&& $loc != 'admin'
			)
		) {
			return;
		}


		/**
		 * Switch things up if we are in dev mode
		 */
		if (
			lct_is_dev_or_sb()
			&& strpos( $src, '.' ) !== false
		) {
			$ver .= '.' . current_time( 'timestamp' );

			$src_tmp           = explode( '.', $src );
			$src_tmp_file_type = array_pop( $src_tmp );

			if ( end( $src_tmp ) ) {
				array_pop( $src_tmp );
			}


			$src_tmp = implode( '.', $src_tmp ) . '.' . $src_tmp_file_type;


			if ( $src_tmp !== $src ) {
				if (
					! empty( $plugin )
					&& function_exists( $plugin . '_get_root_url' )
					&& function_exists( $plugin . '_get_root_path' )
				) {
					$src_dev = str_replace( call_user_func( $plugin . '_get_root_url' ), call_user_func( $plugin . '_get_root_path' ), $src_tmp );


					if ( file_exists( $src_dev ) ) {
						$src = str_replace( lct_path_site(), lct_url_site(), $src_dev );
					}
					if ( file_exists( str_replace( call_user_func( $plugin . '_get_root_url' ), call_user_func( $plugin . '_get_root_path' ), $src_tmp ) ) ) {
						$src = $src_tmp;
					} elseif (
						is_object( $plugin )
						&& file_exists( str_replace( $plugin->plugin_dir_url, $plugin->plugin_dir_path, $src_tmp ) )
					) {
						$src = $src_tmp;
					}
				} else {
					$src_dev = str_replace( lct_get_root_url(), lct_get_root_path(), $src_tmp );


					if ( file_exists( $src_dev ) ) {
						$src = str_replace( lct_path_site(), lct_url_site(), $src_dev );
					} elseif ( file_exists( str_replace( lct_get_root_url(), lct_get_root_path(), $src_tmp ) ) ) {
						$src = $src_tmp;
					} elseif ( file_exists( str_replace( lct_url_theme(), lct_path_theme(), $src_tmp ) ) ) {
						$src = $src_tmp;
					}
				}
			}
		}


		if (
			$type == 'style'
			&& $media_in_footer === false
		) {
			$media_in_footer = 'all';
		}


		if (
			! empty( $deps )
			|| (
				defined( 'LCT_FORCE_ENQUEUE' )
				&& LCT_FORCE_ENQUEUE === true
			)
		) {
			if ( $type == 'style' ) {
				wp_enqueue_style( $handle, $src, $deps, $ver, $media_in_footer );
			} else {
				wp_enqueue_script( $handle, $src, $deps, $ver, $media_in_footer );
			}


			return;
		}


		if ( empty( $enqueue_handles ) ) {
			$enqueue_handles = [ 'wp' => [ 'script' => [], 'style' => [] ], 'admin' => [ 'script' => [], 'style' => [] ] ];
		}


		if ( in_array( $handle, $enqueue_handles[ $loc ][ $type ] ) ) {
			return;
		}


		$enqueue_handles[ $loc ][ $type ][] = $handle;


		lct_update_setting( 'enqueue_handles', $enqueue_handles );


		if ( empty( $force_no_inline ) ) {
			if (
				is_admin()
				&& $loc == 'admin'
			) {
				$handle          = $this->handle_main_admin;
				$shall_we_inline = true;
			} else {
				if ( ! current_user_can( 'administrator' ) ) {
					$handle          = $this->handle_main;
					$shall_we_inline = true;
				}
			}


			$is_url = parse_url( $src );

			if ( ! isset( $is_url['host'] ) ) {
				$shall_we_inline = true;

				if (
					is_admin()
					&& $loc == 'admin'
				) {
					$handle = $this->handle_main_admin;
				} else {
					$handle = $this->handle_main;
				}
			}


			if ( $shall_we_inline ) {
				if (
					! empty( $src_dev )
					&& file_exists( $src_dev )
				) {
					$data = file_get_contents( $src_dev );
				} elseif (
					! empty( $plugin )
					&& function_exists( $plugin . '_get_root_url' )
					&& function_exists( $plugin . '_get_root_path' )
				) {
					if ( file_exists( str_replace( call_user_func( $plugin . '_get_root_url' ), call_user_func( $plugin . '_get_root_path' ), $src ) ) ) {
						$data = file_get_contents( str_replace( call_user_func( $plugin . '_get_root_url' ), call_user_func( $plugin . '_get_root_path' ), $src ) );
					} elseif (
						is_object( $plugin )
						&& file_exists( str_replace( $plugin->plugin_dir_url, $plugin->plugin_dir_path, $src ) )
					) {
						$data = file_get_contents( str_replace( $plugin->plugin_dir_url, $plugin->plugin_dir_path, $src ) );
					}
				} else {
					if ( file_exists( str_replace( lct_get_root_url(), lct_get_root_path(), $src ) ) ) {
						$data = file_get_contents( str_replace( lct_get_root_url(), lct_get_root_path(), $src ) );
					}

					if ( file_exists( str_replace( lct_url_theme(), lct_path_theme(), $src ) ) ) {
						$data = file_get_contents( str_replace( lct_url_theme(), lct_path_theme(), $src ) );
					}
				}

				if (
					empty( $data )
					&& ! isset( $is_url['host'] )
				) {
					$data = $src;
				}

				if ( $type == 'style' ) {
					wp_add_inline_style( $handle, $data );
				} else {
					wp_add_inline_script( $handle, $data );
				}


				return;
			}
		}


		if (
			is_admin()
			&& $loc == 'admin'
		) {
			if ( $type == 'style' ) {
				$this->admin_head_last_wp_print_styles[] = $handle;
			} else {
				$this->admin_head_last_wp_print_scripts[] = $handle;
			}
		} else {
			if ( $type == 'style' ) {
				$this->wp_head_last_wp_print_styles[] = $handle;
			} else {
				$this->wp_head_last_wp_print_scripts[] = $handle;
			}
		}


		if ( $type == 'style' ) {
			wp_enqueue_style( $handle, $src, $deps, $ver, $media_in_footer );
		} else {
			wp_enqueue_script( $handle, $src, $deps, $ver, $media_in_footer );
		}
	}


	/**
	 * Print styles and scripts at the beginning of <head>
	 */
	function wp_head_after_beginning_of_head()
	{
		wp_print_styles( zxzu( 'theme_style_parent' ) );
	}


	/**
	 * Print styles and scripts at the end of </head>     *
	 *
	 * @since    0.0
	 * @verified 2022.02.03
	 */
	function wp_head_last()
	{
		$post              = get_post();
		$theme_style_child = '/custom.min.css';

		if ( file_exists( lct_path_theme() . $theme_style_child ) ) {
			lct_enqueue_style( zxzu( 'theme_style_child' ), lct_url_theme() . $theme_style_child, false, [], lct_active_theme_version() );
		}


		$theme_script_child = '/custom/js/custom.min.js';

		if ( file_exists( lct_path_theme() . $theme_script_child ) ) {
			lct_enqueue_script( zxzu( 'theme_script_child' ), lct_url_theme() . $theme_script_child, false, [], lct_active_theme_version() );
		}


		/**
		 * Finalize styles
		 */
		lct_enqueue_style( $this->handle_main, lct_get_root_url( 'assets/css/custom.min.css' ), true, [], lct_get_setting( 'version' ) );


		/**
		 * Finalize scripts
		 */
		lct_enqueue_script( $this->handle_main, lct_get_root_url( 'assets/js/custom.min.js' ), true, [ 'jquery', zxzu( 'helpers' ) ], lct_get_setting( 'version' ) );

		lct_enqueue_script( zxzu( 'helpers' ), lct_get_root_url( 'assets/js/helpers.min.js' ), true, [ 'jquery', 'acf-input' ], lct_get_setting( 'version' ), true );

		lct_enqueue_script( zxzu( 'acf_front' ), lct_get_root_url( 'assets/js/plugins/acf/front.min.js' ), true, [ zxzu( 'helpers' ) ], lct_get_setting( 'version' ), true );


		/**
		 * @date     0.0
		 * @since    0.0
		 * @verified 2021.08.27
		 */
		do_action( 'lct/wp_head_last' );


		wp_print_styles( array_merge( [ $this->handle_main ], $this->wp_head_last_wp_print_styles ) );
		wp_print_scripts( array_merge( $this->wp_head_last_wp_print_scripts, [ $this->handle_main ] ) );


		if (
			lct_plugin_active( 'acf' )
			&& lct_acf_get_option_raw( 'is_header_code' )
			&& lct_acf_get_field_option( 'header_code' )
			&& (
				! $post
				|| ! lct_acf_get_option_raw( 'header_code_exclude' )
				|| (
					lct_acf_get_option_raw( 'header_code_exclude' )
					&& ! in_array( $post->ID, lct_acf_get_option_raw( 'header_code_exclude' ) )
				)
			)
		) {
			echo lct_acf_get_field_option( 'header_code' );
		}
	}


	/**
	 * Print styles and scripts at the end of </body>
	 */
	function wp_footer_last()
	{
		$post = get_post();


		if (
			lct_plugin_active( 'acf' )
			&& lct_acf_get_option_raw( 'is_footer_code' )
			&& lct_acf_get_field_option( 'footer_code' )
			&& (
				! $post
				|| ! lct_acf_get_option_raw( 'footer_code_exclude' )
				|| (
					lct_acf_get_option_raw( 'footer_code_exclude' )
					&& ! in_array( $post->ID, lct_acf_get_option_raw( 'footer_code_exclude' ) )
				)
			)
		) {
			echo lct_acf_get_field_option( 'footer_code' );
		}
	}


	/**
	 * Register Styles
	 *
	 * @since    0.0
	 * @verified 2019.01.29
	 */
	function register_main_styles()
	{
		$ver = '';

		if ( lct_is_dev_or_sb() ) {
			$ver = '.' . current_time( 'timestamp' );
		}


		wp_register_style( zxzu( 'theme_style_parent' ), lct_url_theme_parent() . '/style.css', [], lct_current_theme_version() . $ver );
		wp_register_style( $this->handle_main, lct_get_root_url( 'assets/css/custom.min.css' ), [], lct_get_setting( 'version' ) . $ver );

		lct_enqueue_style( zxzu( 'lazyframe' ), lct_get_root_url( 'includes/lazyframe/lazyframe.min.css' ) );
	}


	/**
	 * Register Scripts
	 *
	 * @since    0.0
	 * @verified 2022.09.13
	 */
	function register_main_scripts()
	{
		$ver = '';

		if ( lct_is_dev_or_sb() ) {
			$ver = '.' . current_time( 'timestamp' );
		}


		wp_register_script( $this->handle_main, lct_get_root_url( 'assets/js/custom.min.js' ), [ 'jquery' ], lct_get_setting( 'version' ) . $ver );

		lct_enqueue_script( zxzu( 'lazyframe' ), lct_get_root_url( 'includes/lazyframe/lazyframe.min.js' ) );


		if (
			lct_plugin_active( 'acf' )
			&& ( $google_map_api = lct_acf_get_option_raw( 'google_map_api' ) )
		) {
			lct_enqueue_script( zxzu( 'google_map_api' ), sprintf( "var %s = '%s';", zxzu( 'google_map_api' ), $google_map_api ) );
		}


		//user_logged_in_status
		$is_user_logged_in = 0;
		if ( is_user_logged_in() ) {
			$is_user_logged_in = 1;
		}
		lct_enqueue_script( zxzu( 'is_user_logged_in' ), sprintf( "var %s = %s;", zxzu( 'is_user_logged_in' ), $is_user_logged_in ) );


		//really tablet_threshold
		$threshold = lct_get_mobile_threshold();
		lct_enqueue_script( zxzu( 'mobile_threshold' ), "var mobile_threshold = {$threshold};" );


		$threshold = lct_get_small_mobile_threshold();
		lct_enqueue_script( zxzu( 'small_mobile_threshold' ), "var small_mobile_threshold = {$threshold};" );


		$threshold = lct_get_mobile_extreme_threshold();
		lct_enqueue_script( zxzu( 'mobile_extreme_threshold' ), "var mobile_extreme_threshold = {$threshold};" );


		$a = [
			'ajax_url'          => admin_url( 'admin-ajax.php' ),
			'api_url'           => get_rest_url(),
			'wpapi_nonce'       => wp_create_nonce( 'wp_rest' ),
			'is_user_logged_in' => $is_user_logged_in,
		];


		/**
		 * Generate the text for default alert messages
		 */
		$this->generate_alert_message_texts();


		/**
		 * Add in the final variables
		 */
		if ( $tmp = lct_get_setting( 'localized_variables', [] ) ) {
			$a = array_merge( $a, $tmp );
		}


		wp_localize_script(
			$this->handle_main,
			$this->handle_main,
			$a
		);
	}


	/**
	 * Generate the text for default alert messages
	 *
	 * @date     2022.09.13
	 * @since    2022.7
	 * @verified 2022.09.13
	 */
	function generate_alert_message_texts()
	{
		$messages = [
			'api_error_text',
			'redirect_page_text',
		];


		foreach ( $messages as $message ) {
			/**
			 * The main message text
			 */
			$alert = $this->{$message}();


			/**
			 * Set default API Error message
			 */
			if ( shortcode_exists( 'fusion_alert' ) ) {
				/**
				 * Vars
				 */
				$type = 'general';
				if ( $this->alert_type ) {
					$type = $this->alert_type;
				}


				/**
				 * Create the alert
				 */
				$atts = [
					'type'        => $type,
					'dismissable' => 'no',
				];
				$atts = lct_implode_html_attributes( $atts );


				$alert = sprintf(
					'[fusion_alert %s]%s[/fusion_alert]',
					$atts,
					$alert
				);
				$alert = do_shortcode( $alert );


				$this->alert_type = '';
			}


			lct_append_setting( 'localized_variables', $alert, $message );
		}
	}


	/**
	 * Print styles and scripts at the beginning of <head>
	 */
	function admin_head_last()
	{
		wp_print_styles( array_merge( $this->admin_head_last_wp_print_styles, [ $this->handle_main_admin ] ) );
		wp_print_scripts( array_merge( $this->admin_head_last_wp_print_scripts, [ $this->handle_main_admin ] ) );
	}


	/**
	 * Register Styles
	 *
	 * @since    0.0
	 * @verified 2019.02.11
	 */
	function admin_register_main_styles()
	{
		lct_admin_enqueue_style( $this->handle_main_admin, lct_get_root_url( 'assets/wp-admin/css/custom.min.css' ), true, [], lct_get_setting( 'version' ) );


		$theme_child = '/custom/css/wp-admin/custom.min.css';

		if ( file_exists( lct_path_theme() . $theme_child ) ) {
			lct_admin_enqueue_style( zxzu( 'admin_theme_style_child' ), lct_url_theme() . $theme_child, false, [], lct_active_theme_version() );
		}
	}


	/**
	 * Register Scripts
	 *
	 * @since    0.0
	 * @verified 2022.08.11
	 */
	function admin_register_main_scripts()
	{
		lct_admin_enqueue_script( $this->handle_main_admin, lct_get_root_url( 'assets/wp-admin/js/custom.min.js' ), true, [ 'jquery', zxzu( 'helpers' ) ], lct_get_setting( 'version' ) );

		lct_admin_enqueue_script( zxzu( 'helpers' ), lct_get_root_url( 'assets/js/helpers.min.js' ), true, [ 'jquery', 'acf-input' ], lct_get_setting( 'version' ), true );


		$a = [
			'ajax_url'    => admin_url( 'admin-ajax.php' ),
			'api_url'     => get_rest_url(),
			'wpapi_nonce' => wp_create_nonce( 'wp_rest' ),
		];


		wp_localize_script(
			$this->handle_main_admin,
			$this->handle_main_admin,
			$a
		);


		$theme_child = '/custom/js/wp-admin/custom.min.js';

		if ( file_exists( lct_path_theme() . $theme_child ) ) {
			lct_admin_enqueue_script( zxzu( 'admin_theme_script_child' ), lct_url_theme() . $theme_child, false, [], lct_active_theme_version() );
		}
	}


	/**
	 * ADD Google Maps API assets when they are needed
	 *
	 * @since    6.3
	 * @verified 2016.12.06
	 */
	function maps_google_api()
	{
		add_action( 'wp_enqueue_scripts', [ $this, 'maps_google_api_enqueue_scripts' ], 1999990 );
	}


	/**
	 * Register Scripts
	 *
	 * @since    0.0
	 * @verified 2016.12.06
	 */
	function maps_google_api_enqueue_scripts()
	{
		if ( ! lct_plugin_active( 'acf' ) ) {
			return;
		}


		$api = lct_acf_get_option_raw( 'google_map_api' );


		$query_vars = [
			'language' => substr( get_locale(), 0, 2 ),
			'key'      => $api,
		];

		$url_parts          = [];
		$url_parts['host']  = 'maps.googleapis.com';
		$url_parts['path']  = '/maps/api/js';
		$url_parts['query'] = unparse_query( $query_vars );
		$src                = unparse_url( $url_parts );


		wp_enqueue_script( zxzu( 'maps_google_api' ), $src, [], lct_get_setting( 'version' ) );
	}


	/**
	 * API Error Message Text
	 *
	 * @return string
	 * @date     2022.09.13
	 * @since    2022.7
	 * @verified 2022.09.13
	 */
	function api_error_text()
	{
		$this->alert_type = 'error';
		$message          = 'The API request failed.<br />Please refresh & try again. Contact the site admin if it continues.';


		if ( shortcode_exists( 'fusion_alert' ) ) {
			return sprintf( '<h4>%s</h4>', $message );
		} else {
			return sprintf( '<h2>%s</h2>', $message );
		}
	}


	/**
	 * Redirect Page Message Text
	 *
	 * @return string
	 * @date     2022.09.13
	 * @since    2022.7
	 * @verified 2022.09.13
	 */
	function redirect_page_text()
	{
		$this->alert_type = 'success';
		$message          = 'Redirecting, one moment please...';


		if ( shortcode_exists( 'fusion_alert' ) ) {
			return sprintf( '<h4>%s</h4>', $message );
		} else {
			return sprintf( '<h2>%s</h2>', $message );
		}
	}
}


/**
 * Use this function like you would a global variable, except without needing to declare the global.
 *
 * @return lct_features_asset_loader
 * @since    7.49
 * @verified 2016.12.07
 */
function lct_features_asset_loader()
{
	global $lct_features_asset_loader;


	if ( ! isset( $lct_features_asset_loader ) ) {
		$lct_features_asset_loader = new lct_features_asset_loader( lct_load_class_default_args() );
	}


	return $lct_features_asset_loader;
}


/**
 * Direct function for a function wrapped in a class
 *
 * @param        $handle
 * @param        $src
 * @param bool   $force_no_inline
 * @param array  $deps
 * @param bool   $ver
 * @param string $media
 * @param null   $plugin
 */
function lct_enqueue_style( $handle, $src, $force_no_inline = false, $deps = [], $ver = false, $media = 'all', $plugin = null )
{
	lct_features_asset_loader()->lct_enqueue( 'style', 'wp', $handle, $src, $force_no_inline, $deps, $ver, $media, $plugin );
}


/**
 * Direct function for a function wrapped in a class
 *
 * @param        $handle
 * @param        $src
 * @param bool   $force_no_inline
 * @param array  $deps
 * @param bool   $ver
 * @param string $media
 * @param null   $plugin
 */
function lct_admin_enqueue_style( $handle, $src, $force_no_inline = false, $deps = [], $ver = false, $media = 'all', $plugin = null )
{
	lct_features_asset_loader()->lct_enqueue( 'style', 'admin', $handle, $src, $force_no_inline, $deps, $ver, $media, $plugin );
}


/**
 * Direct function for a function wrapped in a class
 *
 * @param             $handle
 * @param             $src
 * @param bool        $force_no_inline
 * @param array       $deps
 * @param bool        $ver
 * @param bool|string $in_footer
 * @param null        $plugin
 */
function lct_enqueue_script( $handle, $src, $force_no_inline = false, $deps = [], $ver = false, $in_footer = false, $plugin = null )
{
	lct_features_asset_loader()->lct_enqueue( 'script', 'wp', $handle, $src, $force_no_inline, $deps, $ver, $in_footer, $plugin );
}


/**
 * Direct function for a function wrapped in a class
 *
 * @param             $handle
 * @param             $src
 * @param bool        $force_no_inline
 * @param array       $deps
 * @param bool        $ver
 * @param bool|string $in_footer
 * @param null        $plugin
 */
function lct_admin_enqueue_script( $handle, $src, $force_no_inline = false, $deps = [], $ver = false, $in_footer = false, $plugin = null )
{
	lct_features_asset_loader()->lct_enqueue( 'script', 'admin', $handle, $src, $force_no_inline, $deps, $ver, $in_footer, $plugin );
}
