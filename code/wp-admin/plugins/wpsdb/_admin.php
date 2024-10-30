<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.07.11
 */
class lct_wp_admin_wpsdb_admin
{
	public $wpdb;


	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.07.11
	 */
	function __construct( $args = [] )
	{
		//Store $args
		$this->args = $args;


		//Store parent class, maybe
		if ( $this->args['load_parent'] ) {
			$this->zxzp = lct();
		}


		global $wpdb;

		$this->wpdb = $wpdb;


		//Setup WordPress action and filter hooks
		$this->load_hooks();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    2017.33
	 * @verified 2018.07.12
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
		add_filter( 'wpsdb_tables', [ $this, 'wpsdb_tables' ], 10, 2 );


		//if ( lct_frontend() ) {}


		if ( lct_wp_admin_all() ) {
			if (
				! empty( $_GET['page'] )
				&& in_array( $_GET['page'], [ zxzu( 'acf_op_main_settings' ), zxzu( 'acf_op_main_advanced' ) ] )
			) {
				add_action( 'admin_init', [ $this, 'shutdown' ] );
			}
		}


		//if ( lct_wp_admin_non_ajax() ) {}


		if ( lct_ajax_only() ) {
			if ( isset( $_POST['action'] ) ) {
				/**
				 * wpsdb_initiate_migration
				 */
				if ( $_POST['action'] == 'wpsdb_initiate_migration' ) {
					add_action( 'init', [ $this, 'sql_before_push_or_pull' ] );

					add_action( 'init', [ $this, 'save_user_sessions' ] );
				}


				/**
				 * wpsdb_finalize_migration
				 */
				if ( $_POST['action'] == 'wpsdb_finalize_migration' ) {
					/**
					 * filters
					 */
					add_filter( 'wpsdb_preserved_options', [ $this, 'wpsdb_preserved_options' ] );


					/**
					 * actions
					 */
					add_action( 'wpsdb_migration_complete', [ $this, 'wpsdb_migration_complete' ], 10, 2 );

					add_action( 'lct/always_shutdown_wp_admin', [ $this, 'shutdown' ] );
				}
			}
		}
	}


	/**
	 * Do stuff when we migrate a DB
	 *
	 * @param $type
	 *
	 * @unused   param $location
	 * @since    0.0
	 * @verified 2017.07.11
	 */
	function wpsdb_migration_complete( $type )
	{
		switch ( $type ) {
			case 'pull':
				$this->run_sql( zxzacf( 'sql_scripts' ), 'after_pull' );
				$this->restore_user_sessions();
				break;


			case 'push':
				$this->run_sql( zxzacf( 'sql_scripts' ), 'after_push' );
				break;


			default:
		}
	}


	/**
	 * Do stuff when we migrate a DB
	 *
	 * @since    0.0
	 * @verified 2017.07.11
	 */
	function sql_before_push_or_pull()
	{
		$this->run_sql( zxzacf( 'sql_scripts' ), 'before_push_or_pull' );


		/**
		 * Turn on maintenance mode
		 */
		$sql = "UPDATE `{$this->wpdb->options}` SET `option_value` = REPLACE( `option_value`, 's:5:\"state\";i:0;', 's:5:\"state\";i:1;' ) WHERE `option_name` = 'maintenance_options';";

		$this->wpdb->query( $sql );
	}


	/**
	 * Do stuff when we are done migrating a DB
	 *
	 * @since    0.0
	 * @verified 2017.07.11
	 */
	function shutdown()
	{
		if ( lct_is_dev() ) {
			/**
			 * Turn off maintenance mode
			 */
			$sql = "UPDATE `{$this->wpdb->options}` SET `option_value` = REPLACE( `option_value`, 's:5:\"state\";i:1;', 's:5:\"state\";i:0;' ) WHERE `option_name` = 'maintenance_options';";

			$this->wpdb->query( $sql );
		}
	}


	/**
	 * Do the actual SQL work
	 *
	 * @param $selector
	 * @param $selector_type
	 *
	 * @since    0.0
	 * @verified 2018.08.24
	 */
	function run_sql( $selector, $selector_type )
	{
		if ( lct_acf_option_repeater_empty( $selector ) ) {
			while( have_rows( $selector, lct_o() ) ) {
				the_row();


				$type = get_sub_field( 'type' );


				if (
					empty( $type )
					|| (
						is_array( $type )
						&& ! in_array( $selector_type, $type )
					)
				) {
					continue;
				}


				$dont_run = get_sub_field( 'dont_run' );


				if (
					! empty( $dont_run )
					&& (
						(
							lct_is_dev()
							&& is_array( $dont_run )
							&& in_array( 'dev', $dont_run )
						)
						|| (
							! lct_is_dev()
							&& is_array( $dont_run )
							&& in_array( 'live', $dont_run )
						)
					)
				) {
					continue;
				}


				$sql = trim( str_replace( '{wpdb_prefix}', $this->wpdb->prefix, get_sub_field( 'script' ) ) );


				if ( strpos( $sql, "\r\n" ) !== false ) {
					$sqls = explode( "\r\n", $sql );


					foreach ( $sqls as $sql ) {
						if ( $sql ) {
							$this->wpdb->query( $sql );
						}
					}
				} elseif ( $sql ) {
					$this->wpdb->query( $sql );
				}
			}
		}
	}


	/**
	 * Save current site's session_tokens, so that we can restore them after we update the DB
	 *
	 * @since    0.0
	 * @verified 2017.06.08
	 */
	function save_user_sessions()
	{
		$sql = "SELECT * FROM {$this->wpdb->usermeta} WHERE `meta_key` = 'session_tokens'";

		$sessions = $this->wpdb->get_results( $sql );


		lct_update_option( 'wpsdb_user_sessions', $sessions, false );
	}


	/**
	 * Restore current site's session_tokens, so that we don't get logged out
	 *
	 * @since    0.0
	 * @verified 2017.07.11
	 */
	function restore_user_sessions()
	{
		$sessions = lct_get_option( 'wpsdb_user_sessions' );


		if ( ! empty( $sessions ) ) {
			lct_delete_option( 'wpsdb_user_sessions' );


			$sql = "DELETE FROM {$this->wpdb->usermeta} WHERE `meta_key` = 'session_tokens'";
			$this->wpdb->query( $sql );


			foreach ( $sessions as $session ) {
				$sql = "INSERT INTO {$this->wpdb->usermeta} ( `user_id`, `meta_key`, `meta_value` ) VALUES ( %d, '%s', '%s' );";

				$this->wpdb->query(
					$this->wpdb->prepare( $sql,
						[
							$session->user_id,
							$session->meta_key,
							$session->meta_value
						]
					)
				);
			}
		}
	}


	/**
	 * Add options to the preserved list so we can use them.
	 *
	 * @param $preserved_options
	 *
	 * @return array
	 * @since    2017.57
	 * @verified 2017.07.11
	 */
	function wpsdb_preserved_options( $preserved_options )
	{
		$preserved_options[] = zxzu( 'wpsdb_user_sessions' );


		return $preserved_options;
	}


	/**
	 * Filter out tables that we don't need
	 *
	 * @param $clean_tables
	 * @param $scope
	 *
	 * @return mixed
	 * @since    2018.33
	 * @verified 2018.05.20
	 */
	function wpsdb_tables( $clean_tables, $scope )
	{
		if (
			! in_array( $scope, [ 'regular', 'prefix' ] )
			|| empty( $clean_tables )
			|| defined( 'DISABLE_WPSDB_TABLES_FILTER' )
			|| ! defined( 'ENABLE_WPSDB_TABLES_FILTER' )
		) {
			return $clean_tables;
		}


		global $wpdb;

		$exclude = [
			$wpdb->prefix . 'ewwwio_images',
			$wpdb->links,
			$wpdb->prefix . 'gf_entry',
			$wpdb->prefix . 'gf_entry_meta',
			$wpdb->prefix . 'gf_entry_notes',
			$wpdb->prefix . 'gf_form_view',
			$wpdb->prefix . 'redirection_404',
			$wpdb->prefix . 'redirection_logs',
			$wpdb->prefix . 'revslider_css',
			$wpdb->prefix . 'revslider_layer_animations',
			$wpdb->prefix . 'revslider_navigations',
			$wpdb->prefix . 'revslider_settings',
			$wpdb->prefix . 'revslider_sliders',
			$wpdb->prefix . 'revslider_slides',
			$wpdb->prefix . 'revslider_static_slides',
			$wpdb->prefix . 'rg_form_view',
			$wpdb->prefix . 'rg_lead',
			$wpdb->prefix . 'rg_lead_detail',
			$wpdb->prefix . 'rg_lead_detail_long',
			$wpdb->prefix . 'rg_lead_meta',
			$wpdb->prefix . 'rg_lead_notes',
			$wpdb->prefix . 'stream',
			$wpdb->prefix . 'stream_meta',
			$wpdb->prefix . 'yoast_seo_links',
			$wpdb->prefix . 'yoast_seo_meta',
			$wpdb->prefix . 'zerospam_blocked_ips',
			$wpdb->prefix . 'zerospam_log',
		];


		foreach ( $clean_tables as $k => $clean_table ) {
			if (
				strpos( $clean_table, $wpdb->prefix . 'wf' ) === 0
				|| in_array( $clean_table, $exclude )
			) {
				unset( $clean_tables[ $k ] );
			}
		}


		$clean_tables = array_values( $clean_tables );


		return $clean_tables;
	}
}
