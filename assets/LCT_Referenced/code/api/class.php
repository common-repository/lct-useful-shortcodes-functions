<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) )
	exit;


/**
 * LCT Referenced
 *
 * @property array args
 * @property lct   zxzp
 * @verified 2019.07.15
 * @checked  2019.07.15
 */
class lct_api_class {
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.07.31
	 */
	function __construct( $args = [] ) {
		//Store $args
		$this->args = $args;


		//Store parent class, maybe
		if ( $this->args['load_parent'] )
			$this->zxzp = lct();


		//Setup WordPress action and filter hooks
		$this->load_hooks();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    LCT 7.42
	 * @verified 2018.08.22
	 */
	function load_hooks() {
		//bail early if already ran
		if ( lct_did() )
			return;


		/**
		 * everytime
		 */
		$this->acf_start_up();


		/**
		 * actions
		 */
		add_action( 'plugins_loaded', [ $this, 'prep_shutdown' ], 1 );

		add_action( 'plugins_loaded', [ $this, 'set_all_cnst' ], 6 ); //needs to be after post_types & taxonomies

		add_action( 'plugins_loaded', [ $this, 'enable_email_reminder' ], 11 );


		/**
		 * filters
		 */
		add_filter( 'plugins_loaded', [ $this, 'check_if_unfiltered_html_should_be_forced' ], 3 );

		add_filter( 'map_meta_cap', [ $this, 'force_allow_unfiltered_html' ], 1, 4 );

		add_filter( 'user_has_cap', [ $this, 'force_allow_cap_unfiltered_html' ], 1, 4 );

		add_filter( 'wp_revisions_to_keep', [ $this, 'iframe_filters_to_keep' ], 10, 2 );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}


		if ( lct_is_dev_or_sb() ) {
			/**
			 * actions
			 */
			add_action( 'admin_print_scripts', [ $this, 'jquery_migrate_echo_silencer' ] );


			/**
			 * filters
			 */
			add_filter( 'option_siteurl', [ $this, 'dev_url' ] );
			add_filter( 'option_home', [ $this, 'dev_url' ] );

			add_filter( 'script_loader_tag', [ $this, 'jquery_migrate_load_silencer' ], 10, 2 );
		}
	}


	/**
	 * Load shutdown actions when the time is right
	 *
	 * @since    2019.15
	 * @verified 2019.06.20
	 */
	function prep_shutdown() {
		add_action( 'shutdown', [ $this, 'always_shutdown' ], 5 );
	}


	/**
	 * Add any ACF includes
	 *
	 * @since    LCT 2018.27
	 * @verified 2018.08.22
	 */
	function acf_start_up() {
		/**
		 * Set the plugin
		 */
		lct_set_plugin( 'advanced-custom-fields-pro/acf.php', 'acf' ); //Last Check: Never


		/**
		 * Set functions if ACF plugin is not active
		 */
		add_action( 'plugins_loaded', [ $this, 'maybe_set_acf_functions' ], 3 );


		$plugin = 'acf';
		$dir    = "plugins/{$plugin}";

		if ( lct_plugin_active( $plugin ) ) {
			lct_include( "{$dir}/api/_helpers.php" );


			/**
			 * Set some ACF up
			 */
			add_action( 'plugins_loaded', [ $this, 'acf_startup' ], 3 );
		}
	}


	/**
	 * Set functions if ACF plugin is not active
	 * Called on action: 'plugins_loaded' priority: 3
	 *
	 * @since    LCT 7.62
	 * @verified 2018.08.22
	 */
	function maybe_set_acf_functions() {
		/**
		 * If the ACF is not active
		 */
		if (
			! lct_plugin_active( 'acf' ) &&
			! isset( $_GET['action'] )
		) {
			lct_include( 'plugins/acf/inactive.php' );


			/**
			 * If something is activating, we want to stop all our plugin from loading
			 */
		} else if (
			isset( $_GET['action'] ) &&
			$_GET['action'] === 'activate' &&
			! empty( $_GET['plugin'] ) &&
			strpos( $_GET['plugin'], 'acf' ) !== false
		) {
			lct_update_setting( 'stop_loading', true );
		}
	}


	/**
	 * Set some ACF up
	 * Called on action: 'plugins_loaded' priority: 3
	 *
	 * @since    LCT 7.62
	 * @verified 2018.08.22
	 */
	function acf_startup() {
		$plugin = 'acf';
		$dir    = "plugins/{$plugin}";

		if ( lct_plugin_active( $plugin ) ) {
			/**
			 * Field Settings
			 */
			lct_load_class( "{$dir}/field_settings.php", 'field_settings', [ 'plugin' => $plugin ] );


			/**
			 * Get our field type includes in just in case a plugin calls get_field()
			 */
			add_action( 'acf/include_field_types', [ $this, 'acf_include_field_types' ] ); //v5


			/**
			 * Load our main admin settings pages
			 */
			lct_load_class( "{$dir}/op_main.php", 'op_main', [ 'plugin' => $plugin, 'dir' => $dir ] );


			/**
			 * Actions
			 */
			add_action( 'acf/include_fields', [ $this, 'create_local_field_key_reference_array' ], 999 );

			add_action( 'acf/include_fields', [ $this, 'acf_actions_n_filters_pre' ], 9999 );

			add_action( 'acf/init', [ $this, 'acf_actions_n_filters' ], 999 );


			/**
			 * filters
			 */
			add_filter( 'acf/settings/autoload', '__return_true' );

			//I disabled in 2019.3 to test if this is really needed anymore. I think it is too expensive now.
			//add_filter( 'acf/get_fields', [ $this, 'acf_get_fields' ], 10, 2 );

			add_filter( 'register_post_type_args', [ $this, 'acf_post_type_args' ], 10, 2 );


			if ( lct_wp_admin_non_ajax() ) {
				add_action( 'load-update-core.php', [ $this, 'autoload_checker' ] );
			}
		}
	}


	/**
	 * Get our field type includes in just in case a plugin calls get_field()
	 * include_field_types
	 * This function will include the field type classes for all custom LCT field types
	 *
	 * @param mixed $version major ACF version. Defaults to false
	 *
	 * @since    LCT 7.29
	 * @verified 2018.08.22
	 */
	function acf_include_field_types( $version = false ) {
		// support empty $version
		if ( ! $version )
			$version = 5;

		$dir = 'plugins/acf/field-types/';


		//Basic
		lct_include( "{$dir}phone/class-v{$version}.php" );
		lct_include( "{$dir}zip_code/class-v{$version}.php" );


		//Layout
		lct_include( "{$dir}section_header/class-v{$version}.php" );


		//PDF Layout
		lct_include( "{$dir}dompdf_clear/class-v{$version}.php" );
		lct_include( "{$dir}new_page/class-v{$version}.php" );
		lct_include( "{$dir}column_start/class-v{$version}.php" );
		lct_include( "{$dir}column_end/class-v{$version}.php" );


		//LCT
		lct_include( "{$dir}send_password/class-v{$version}.php" );


		//LCT DEV
		lct_include( "{$dir}serialize/class-v{$version}.php" );


		if ( lct_wp_admin_all() ) {
			//LCT DEV Check
			lct_include( "{$dir}dev_report/class-v{$version}.php" );
			lct_include( "{$dir}modified_posts/class-v{$version}.php" );
		}
	}


	/**
	 * Store our constants
	 *
	 * @since    LCT 7.27
	 * @verified 2018.01.03
	 */
	function set_all_cnst() {
		$default_fields = [
			'following',
		];

		$default_a_c_f_fields = [
			'a_c_f_following',
			'a_c_f_following_parent',
			'a_c_f_org',
			'a_c_f_status',
			'a_c_f_tax_disable',
			'a_c_f_tax_status',
			'a_c_f_tax_public',
			'a_c_f_tax_show_in_admin_all_list',
		];


		foreach ( $default_fields as $cnst ) {
			set_cnst( $cnst, zxzu( $cnst ) );
		}


		foreach ( $default_a_c_f_fields as $cnst ) {
			set_cnst( $cnst, zxzacf( str_replace( 'a_c_f_', '', $cnst ) ) );
		}


		$this->roles_n_caps_cnst();
	}


	/**
	 * Constants for roles_n_caps
	 *
	 * @since    LCT 7.27
	 * @verified 2017.07.31
	 */
	function roles_n_caps_cnst() {
		set_cnst( 'view_all_org', zxzu( 'view_all_' ) . zxzu( 'org' ) ); //don't use get_cnst as it might not be set
	}


	/**
	 * Stuff we need to always do when WP shuts down
	 *
	 * @since    LCT 7.4
	 * @verified 2017.07.31
	 */
	function always_shutdown() {
		do_action( 'lct/always_shutdown' );
	}


	/**
	 * When we are on a dev or sb site we want WP to use our HTTP_HOST not the one in the DB.
	 * Then we don't have to make any manual changes to the DB or the wp-config.php file
	 *
	 * @param $url
	 *
	 * @return string
	 * @since    LCT 5.32
	 * @verified 2018.08.30
	 */
	function dev_url( $url ) {
		/**
		 * sometimes we need to access the raw DB info
		 */
		if ( lct_get_setting( 'tmp_disable_dev_url' ) )
			return $url;


		$current_filter = lct_pre_us( current_filter() );
		$blog_id        = get_current_blog_id();


		/**
		 * Return early if cache is found
		 */
		if (
			( $cache_key = lct_cache_key( compact( 'current_filter', 'blog_id' ) ) ) &&
			lct_isset_cache( $cache_key )
		) {
			return lct_get_cache( $cache_key );


			/**
			 * get the dev url
			 */
		} else {
			$url_parts = parse_url( $url );

			if ( is_multisite() )
				$url_parts['host'] .= sprintf( '.%s.eetah.com', get_option( 'options_' . zxzacf( 'clientzz' ), 'none' ) );
			else
				$url_parts['host'] = $_SERVER['HTTP_HOST'];

			$dev_url = unparse_url( $url_parts );


			$url = $dev_url;


			/**
			 * Save the value to the cache
			 */
			lct_set_cache( $cache_key, $url );
		}


		return $url;
	}


	/**
	 * Silencer for stoopid jQuery migrate script
	 *
	 * @return string
	 * @since    LCT 2017.1
	 * @verified 2017.07.31
	 */
	function jquery_migrate_silencer() {
		// create function copy
		$silencer = '<script>';
		$silencer .= 'window.console.logger = window.console.log; ';
		// modify original function to filter and use function copy
		$silencer .= 'window.console.log = function(tolog) {';
		// bug out if empty to prevent error
		$silencer .= 'if (tolog == null) {return;} ';
		// filter messages containing string
		$silencer .= 'if (tolog.indexOf("Migrate is installed") == -1) {';
		$silencer .= 'console.logger(tolog);} ';
		$silencer .= '}';
		$silencer .= '</script>';


		return $silencer;
	}


	/**
	 * for the frontend, use script_loader_tag filter
	 *
	 * @param $tag
	 * @param $handle
	 *
	 * @return string
	 * @since    LCT 2017.1
	 * @verified 2018.08.30
	 */
	function jquery_migrate_load_silencer( $tag, $handle ) {
		if (
			lct_plugin_active( 'acf' ) &&
			lct_acf_get_option_raw( 'enable_migrate_silencer' ) &&
			$handle == 'jquery-migrate'
		) {
			$silencer = $this->jquery_migrate_silencer();


			// prepend to jquery migrate loading
			$tag = $silencer . $tag;
		}

		return $tag;
	}


	/**
	 * for the admin, hook to admin_print_scripts
	 *
	 * @since    LCT 2017.1
	 * @verified 2018.08.30
	 */
	function jquery_migrate_echo_silencer() {
		if (
			lct_plugin_active( 'acf' ) &&
			lct_acf_get_option_raw( 'enable_migrate_silencer' )
		) {
			echo $this->jquery_migrate_silencer();
		}
	}


	/**
	 * Bug in WP where you can't be allowed unfiltered_html unless you are super_admin
	 *
	 * @param $caps
	 * @param $cap
	 * @param $user_id
	 * @param $args
	 *
	 * @return array
	 * @since    LCT 2017.14
	 * @verified 2017.07.31
	 */
	function force_allow_unfiltered_html(
		$caps,
		$cap,
		/** @noinspection PhpUnusedParameterInspection */
		$user_id,
		/** @noinspection PhpUnusedParameterInspection */
		$args
	) {
		if (
			$cap == 'unfiltered_html' &&
			lct_get_later( 'theme_chunk', 'save_iframe' )
		) {
			$caps = [ 'unfiltered_html' ];
		}


		return $caps;
	}


	/**
	 * Bug in WP where you can't be allowed unfiltered_html unless you are super_admin
	 *
	 * @param $allcaps
	 * @param $caps
	 * @param $args
	 * @param $class
	 *
	 * @return array
	 * @since    LCT 2017.14
	 * @verified 2017.07.31
	 */
	function force_allow_cap_unfiltered_html(
		$allcaps,
		$caps,
		/** @noinspection PhpUnusedParameterInspection */
		$args,
		/** @noinspection PhpUnusedParameterInspection */
		$class
	) {
		if (
			in_array( 'unfiltered_html', $caps ) &&
			lct_get_later( 'theme_chunk', 'save_iframe' )
		) {
			$allcaps['unfiltered_html'] = true;
		}


		return $allcaps;
	}


	/**
	 * Need to set a later, for security
	 *
	 * @since    LCT 2017.14
	 * @verified 2017.07.31
	 */
	function check_if_unfiltered_html_should_be_forced() {
		if (
			isset( $_POST['action'] ) &&
			$_POST['action'] == zxzu( 'theme_chunk' ) &&
			isset( $_POST['content_type'] ) &&
			$_POST['content_type'] == 'iframe'
		) {
			lct_update_later( 'theme_chunk', true, 'save_iframe' );
		}
	}


	/**
	 * Only Load up the email_reminder files if we want them to be
	 *
	 * @since    LCT 7.3
	 * @verified 2018.08.30
	 */
	function enable_email_reminder() {
		if (
			lct_plugin_active( 'acf' ) &&
			lct_acf_get_option_raw( 'enable_email-reminder' )
		) {
			lct_root_include( 'available/email-reminder/email-reminder.php' );
		}
	}


	/**
	 * Saves us from running unnecessary queries for conditionally hidden sub_fields
	 *
	 * @param $fields
	 * @param $parent
	 *
	 * @return mixed
	 * @since    LCT 2017.42
	 * @verified 2018.10.08
	 */
	function acf_get_fields( $fields, $parent ) {
		if (
			! empty( $fields ) &&
			! empty( $parent['type'] ) &&
			! empty( $parent['name'] ) &&
			$parent['type'] === 'repeater' &&
			acf_get_setting( 'autoload' ) &&
			! lct_get_setting( 'acf_is_options_page' ) &&
			! lct_get_setting( 'single_version_db_updates' )
		) {
			$all_options = wp_load_alloptions();
			$check       = 'options_' . $parent['name'];


			if ( array_key_exists( $check, $all_options ) ) {
				if ( $all_options[ $check ] ) {
					for ( $i = 0; $i <= $all_options[ $check ]; $i ++ ) {
						foreach ( $fields as $k => $field ) {
							$check_nest_1 = 'options_' . $parent['name'] . '_' . $i . '_' . $field['name'];


							if ( ! array_key_exists( $check_nest_1, $all_options ) )
								add_filter( 'pre_option_' . $check_nest_1, '__return_null' );
						}
					}
				}
			} else {
				add_filter( 'pre_option_' . $check, '__return_null' );
			}
		}


		return $fields;
	}


	/**
	 * Allow revisions for ACF fields
	 *
	 * @param $args
	 * @param $post_type
	 *
	 * @return mixed
	 * @since    LCT 7.32
	 * @verified 2018.08.22
	 */
	function acf_post_type_args( $args, $post_type ) {
		if ( $post_type !== 'acf-field' )
			return $args;


		if (
			isset( $args['supports'] ) &&
			is_array( $args['supports'] ) &&
			! array_search( 'revisions', $args['supports'] )
		) {
			$args['supports'][] = 'revisions';
		} else if ( empty( $args['supports'] ) ) {
			$args['supports'] = [ 'revisions' ];
		}


		return $args;
	}


	/**
	 * Don't save page revisions for iframe pages, when you are making change in wp-admin
	 *
	 * @param $num
	 * @param $post
	 *
	 * @return int
	 * @since    LCT 2018.45
	 * @verified 2018.09.22
	 */
	function iframe_filters_to_keep( $num, $post ) {
		if (
			! lct_doing() &&
			isset( $post->post_parent ) &&
			( $iframe_page = (int) lct_acf_get_option_raw( 'iframe_page' ) ) &&
			$iframe_page === $post->post_parent
		) {
			$num = 0;
		}


		return $num;
	}


	/**
	 * Create a reference of field names keyed with the field key
	 * We will use this later to save time during a reference lookup
	 * ACF changed their storage method in v5.7.10
	 * This is the old fashioned way
	 *
	 * @since    LCT 2018.62
	 * @verified 2019.03.11
	 */
	function create_local_field_key_reference_array() {
		if (
			lct_did() ||
			version_compare( lct_plugin_version( 'acf' ), '5.7.10', '>=' ) || //ACF v5.7.10 or newer
			! function_exists( 'acf_local' )
		) {
			return;
		}


		if ( ! empty( acf_local()->parents ) ) {
			acf_local()->{lct_key_reference()} = [];


			foreach ( acf_local()->parents as $parent_key => $fields ) {
				foreach ( $fields as $field ) {
					if ( acf_local()->fields[ $field ]['name'] )
						acf_local()->{lct_key_reference()}[ $field ] = acf_local()->fields[ $field ]['name'];
				}
			}
		}
	}


	/**
	 * ACF page specific actions & filters
	 *
	 * @since    2019.7
	 * @verified 2019.04.08
	 */
	function acf_actions_n_filters_pre() {
		/**
		 * Actions
		 */
		add_action( 'acf/save_post', [ $this, 'prevent_taxonomy_saving' ], 0 );


		/**
		 * Filters
		 */
		add_filter( 'acf/load_value/type=taxonomy', [ $this, 'load_taxonomy' ], 9999, 3 );
		add_filter( 'acf/load_value/name=' . lct_status(), [ $this, 'load_status_of_post_type' ], 10, 3 );

		add_filter( 'acf/update_value', [ $this, 'finish_taxonomy_update' ], 999999, 3 );
		add_filter( 'acf/update_value/type=taxonomy', [ $this, 'update_taxonomy' ], 999980, 3 );
		add_filter( 'acf/update_value/name=' . lct_status(), [ $this, 'update_taxonomy_status' ], 999970, 3 );


		if ( lct_frontend() ) {
			add_action( 'lct/always_shutdown', [ $this, 'do_update_field_later' ], 11 );
		}


		if ( lct_wp_admin_non_ajax() ) {
			add_action( 'acf/save_post', [ $this, 'do_update_field_later' ], 100 );
		}
	}


	/**
	 * ACF page specific actions & filters
	 *
	 * @since    2019.4
	 * @verified 2019.06.18
	 */
	function acf_actions_n_filters() {
		if (
			lct_wp_admin_non_ajax() &&
			function_exists( 'lct_acf_get_options_pages' ) &&
			( $pages = lct_acf_get_options_pages() ) &&
			! empty( $pages )
		) {
			foreach ( $pages as $page ) {
				add_action( 'load-toplevel_page_' . $page['menu_slug'], [ $this, 'autoload_checker' ] );
			}
		}
	}


	/**
	 * Check options table and fix any incorrect autoload settings
	 *
	 * @since    2017.42
	 * @verified 2019.03.11
	 */
	function autoload_checker() {
		if ( lct_get_option( 'per_version_updated_autoload_checker' ) )
			return;


		global $wpdb;

		$fields = lct_acf_get_field_groups_fields( [ 'options_page' => true ] );


		if ( ! empty( $fields ) ) {
			foreach ( $fields as $field ) {
				if ( $field['name'] ) {
					$option_name  = 'options_' . $field['name'];
					$option_names = [
						lct_pre_us( $option_name ),
						$option_name,
					];


					foreach ( $option_names as $option_name ) {
						$autoload = $wpdb->get_var(
							$wpdb->prepare(
								"SELECT `autoload` FROM `{$wpdb->options}` WHERE `option_name` = %s",
								$option_name
							)
						);


						if ( $autoload !== 'yes' )
							$wpdb->update( $wpdb->options, [ 'autoload' => 'yes' ], [ 'option_name' => $option_name ] );
					}


					if ( ! empty( $field['sub_fields'] ) ) {
						$wpdb->query(
							$wpdb->prepare(
								"UPDATE `{$wpdb->options}` SET `autoload` = 'yes' WHERE `option_name` LIKE %s",
								'%' . $option_name . '%'
							)
						);
					}
				}
			}
		}


		/**
		 * Force 'no' autoload
		 */
		$no_autoloads = [
			zxzu( 'update_all_sidebar_metas' ),
			zxzu( 'update_all_page_sidebar_metas' ),
		];
		$no_autoloads = apply_filters( 'lct/autoload_checker/force_no', $no_autoloads );

		$no_autoload = [];

		foreach ( $no_autoloads as $v ) {
			$no_autoload[] = "'" . $v . "'";
		}

		$no_autoload = implode( ',', $no_autoload );


		$wpdb->query(
			$wpdb->prepare(
				"UPDATE `{$wpdb->options}` SET `autoload` = %s WHERE `option_name` IN ( {$no_autoload} )",
				'no'
			)
		);


		lct_update_option( 'per_version_updated_autoload_checker', true, false );
	}


	/**
	 * Update the status of a post
	 *
	 * @param int|string $value
	 * @param int        $post_id
	 * @param array      $field
	 *
	 * @return int|string|null
	 * @since    2017.96
	 * @verified 2019.04.08
	 */
	function update_taxonomy_status( $value, $post_id, $field ) {
		if (
			$field['type'] === 'taxonomy' &&
			! empty( $field['taxonomy'] ) &&
			empty( $field['multiple'] ) &&
			( $info = acf_decode_post_id( $post_id ) ) &&
			$info['type'] === 'post' &&
			( $status = get_term( $value, $field['taxonomy'] ) ) &&
			! lct_is_wp_error( $status ) &&
			get_field( get_cnst( 'a_c_f_tax_status' ), lct_t( $status ) )
		) {
			/**
			 * Update the status of the post
			 */
			lct_append_later( 'update_post_status', $field['key'], $post_id );


			/**
			 * Remove term relationships and postmeta
			 */
			if (
				empty( $field['save_terms'] ) &&
				empty( $field['load_terms'] )
			) {
				wp_delete_object_term_relationships( $post_id, $field['taxonomy'] );


				/**
				 * This will delete the postmeta later
				 */
				lct_append_later( 'post_meta_delete', $field['key'], $post_id );
			} else if (
				! empty( $field['save_terms'] ) &&
				! empty( $field['load_terms'] )
			) {
				/**
				 * This will delete the postmeta later
				 */
				lct_append_later( 'post_meta_delete', $field['key'], $post_id );
			}
		}


		return $value;
	}


	/**
	 * Get the status of a post
	 *
	 * @param int|string $value
	 * @param int        $post_id
	 * @param array      $field
	 *
	 * @return int|string|null
	 * @since    2019.7
	 * @verified 2019.04.29
	 */
	function load_status_of_post_type( $value, $post_id, $field ) {
		if (
			! empty( $post_id ) &&
			$field['type'] === 'taxonomy' &&
			! empty( $field['taxonomy'] ) &&
			empty( $field['multiple'] ) &&
			( $info = acf_decode_post_id( $post_id ) ) &&
			$info['type'] === 'post' &&
			( $post_status = get_post_status( $post_id ) ) &&
			strpos( $post_status, '_' ) !== false
		) {
			$post_status_arr = explode( '_', $post_status );
			$post_status     = $post_status_arr[0];


			if (
				is_numeric( $post_status ) &&
				( $status = get_term( $post_status, $field['taxonomy'] ) ) &&
				! lct_is_wp_error( $status )
			) {
				if (
					! empty( $field['save_terms'] ) &&
					(int) $value !== $status->term_id
				) {
					wp_set_object_terms( $post_id, $status->term_id, $field['taxonomy'] );
				}


				$value = $status->term_id;
			}
		} else if ( $value ) {
			$value = (int) $value;
		}


		return $value;
	}


	/**
	 * Update the value of a stored taxonomy
	 *
	 * @param int|string $value
	 * @param int        $post_id
	 * @param array      $field
	 *
	 * @return int|string|null
	 * @since    2019.7
	 * @verified 2019.04.04
	 */
	function load_taxonomy( $value, $post_id, $field ) {
		if (
			empty( $field['load_terms'] ) ||
			$field['_name'] === lct_status() ||
			(
				$value !== false &&
				(
					! isset( $field['default_value'] ) ||
					(
						isset( $field['default_value'] ) &&
						$value !== $field['default_value']
					)
				)
			)
		) {
			return $value;
		}


		if (
			$value === false &&
			( $meta = get_post_meta( $post_id, $field['_name'], true ) )
		) {
			if ( ! is_array( $meta ) )
				$meta = [ $meta ];


			foreach ( $meta as $k => $v ) {
				$meta[ $k ] = (int) $v;
			}


			wp_set_object_terms( $post_id, $meta, $field['taxonomy'] );


			delete_post_meta( $post_id, $field['_name'] );
			delete_post_meta( $post_id, lct_pre_us( $field['_name'] ) );
		}


		//get terms
		$info     = acf_get_post_id_info( $post_id );
		$term_ids = wp_get_object_terms( $info['id'], $field['taxonomy'], [ 'fields' => 'ids' ] );


		//bail early if no terms
		if (
			empty( $term_ids ) ||
			is_wp_error( $term_ids )
		) {
			return $value;
		}


		if ( empty( $field['multiple'] ) )
			$term_ids = $term_ids[0];


		//update value
		$value = $term_ids;


		return $value;
	}


	/**
	 * Update the value of a stored taxonomy
	 *
	 * @param int|string $value
	 * @param int        $post_id
	 * @param array      $field
	 *
	 * @return int|string|null
	 * @since    2019.7
	 * @verified 2019.04.08
	 */
	function update_taxonomy( $value, $post_id, $field ) {
		if (
			$field['_name'] !== lct_status() &&
			! empty( $field['taxonomy'] ) &&
			! empty( $field['save_terms'] )
		) {
			/**
			 * save the terms if this is a post
			 */
			if (
				$value &&
				( $info = acf_decode_post_id( $post_id ) ) &&
				$info['type'] === 'post'
			) {
				$terms     = [];
				$raw_terms = $value;


				/**
				 * Make an array if it is a single term
				 */
				if ( ! is_array( $raw_terms ) ) {
					if ( ! is_object( $raw_terms ) )
						$raw_terms = trim( $raw_terms );


					$raw_terms = [ $raw_terms ];
				}


				/**
				 * Loop through and make sure we have them set as integers
				 */
				foreach ( $raw_terms as $v ) {
					if (
						is_object( $v ) &&
						! lct_is_wp_error( $v )
					) {
						$terms[] = (int) $v->term_id;


					} else if ( is_numeric( $v ) ) {
						$terms[] = (int) $v;
					}
				}


				if ( ! empty( $terms ) )
					wp_set_object_terms( $post_id, $terms, $field['taxonomy'], false );
			}


			/**
			 * This will delete the postmeta later
			 */
			if ( ! empty( $field['load_terms'] ) )
				lct_append_later( 'post_meta_delete', $field['key'], $post_id );
		}


		return $value;
	}


	/**
	 * Update the value of a stored taxonomy
	 *
	 * @param int|string $value
	 * @param int        $post_id
	 * @param array      $field
	 *
	 * @return int|string|null
	 * @since    2019.7
	 * @verified 2019.04.08
	 */
	function finish_taxonomy_update( $value, $post_id, $field ) {
		if (
			$post_id &&
			! empty( $field['key'] ) &&
			! empty( $field['type'] ) &&
			$field['type'] === 'taxonomy'
		) {
			if (
				$value &&
				( $updates = lct_get_later( 'update_post_status', $post_id ) ) &&
				array_search( $field['key'], $updates ) !== false &&
				( $status = get_term( $value, $field['taxonomy'] ) ) &&
				! lct_is_wp_error( $status ) &&
				get_field( get_cnst( 'a_c_f_tax_status' ), lct_t( $status ) )
			) {
				/**
				 * Update the status of the post
				 */
				lct_update_post_status( $post_id, lct_make_status_slug( $status ) );
			}


			if (
				( $deletes = lct_get_later( 'post_meta_delete', $post_id ) ) &&
				array_search( $field['key'], $deletes ) !== false
			) {
				/**
				 * This will delete the postmeta
				 */
				$value = null;
			}
		}


		return $value;
	}


	/**
	 * Remove the ACF taxonomy save_post filter so we can handle it on our own
	 * We do this so that comments, user, options don't accidentally get saved as taxonomy relations
	 *
	 * @unused   param int $post_id
	 * @since    2019.7
	 * @verified 2019.04.08
	 */
	function prevent_taxonomy_saving() {
		lct_remove_filter_like( 'acf/save_post', 'save_post', 15, true, 'acf_field_taxonomy' );
	}


	/**
	 * Save any fields that were run by update_field_later()
	 *
	 * @since    2017.21
	 * @verified 2017.07.19
	 */
	function do_update_field_later() {
		if ( $updates = lct_get_later( 'update_field_later' ) ) {
			foreach ( $updates as $ud ) {
				update_field( $ud['selector'], $ud['value'], $ud['post_id'] );
			}


			lct_update_later( 'update_field_later', null );
		}
	}
}
