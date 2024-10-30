<?php
/** @noinspection PhpMissingFieldTypeInspection */
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * LCT Referenced
 *
 * @property array args
 * @property lct   zxzp
 * @verified 2018.08.23
 * @checked  2018.08.23
 */
class lct_wp_admin_admin_update
{
	/**
	 * @var
	 * @since LCT 7.26
	 */
	public $custom_roles;
	/**
	 * @var
	 * @since LCT 7.26
	 */
	public $custom_caps;
	/**
	 * @var
	 * @since LCT 2017.42
	 */
	public $custom_caps_remove;


	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.07.31
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
	 * @since    LCT 7.38
	 * @verified 2019.07.15
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
		add_action( 'shutdown', [ $this, 'always_shutdown_wp_admin' ], 4 );


		if ( lct_wp_admin_non_ajax() ) {
			add_action( 'admin_init', [ $this, 'always_check_admin' ], 4 );

			add_action( 'admin_init', [ $this, 'run_post_plugin_update' ] );

			add_action( 'upgrader_process_complete', [ $this, 'upgrader_process_complete' ], 10, 2 );

			//load-{$pagenow}
			add_action( 'load-update-core.php', [ $this, 'load_update_core' ] );

			//load-{$pagenow}
			add_action( 'load-update-core.php', [ $this, 'update_roles_n_caps' ] );
		}


		//if ( lct_ajax_only() ) {}


		/**
		 * SPECIAL
		 */
		if ( lct_doing_cron() ) {
			add_action( 'init', [ $this, 'set_version' ], 9 );
		}
	}


	/**
	 * Stuff we need to always do when WP shuts down in wp-admin
	 *
	 * @since    LCT 7.42
	 * @verified 2017.07.31
	 */
	function always_shutdown_wp_admin()
	{
		/**
		 * @date     0.0
		 * @since    7.42
		 * @verified 2021.08.30
		 */
		do_action( 'lct/always_shutdown_wp_admin' );
	}


	/**
	 * Check and make sure some things are set up properly
	 *
	 * @since    LCT 7.4
	 * @verified 2018.08.23
	 */
	function always_check_admin()
	{
		add_action( 'admin_init', [ $this, 'set_version' ], 5 );


		//Unfortunately we always have to check this
		//TODO: cs - We need to find a way to check if this has been run already - 11/15/2016 03:58 PM
		if ( ! get_transient( zxzu( 'did_update_roles_n_caps' ) ) ) {
			add_action( 'admin_init', [ $this, 'update_roles_n_caps' ] );
		}


		/**
		 * @date     0.0
		 * @since    7.4
		 * @verified 2021.08.30
		 */
		do_action( 'lct/always_check_admin' );
	}


	/**
	 * Set the current version of this plugin & update if needed
	 *
	 * @since    LCT 0.0
	 * @verified 2018.08.23
	 */
	function set_version()
	{
		//bail early if already ran
		if ( lct_did() ) {
			return;
		}


		/**
		 * Set the loaded version
		 */
		$plugin = get_file_data( lct_get_setting( 'plugin_file' ), [ 'Version' => 'Version' ], 'plugin' );
		//$plugin = get_plugin_data( lct_get_setting( 'plugin_file' ) ); //This is slower
		lct_update_setting( 'version', $plugin['Version'] );


		/**
		 * Get version from DB
		 */
		$version_in_db = lct_get_option( 'version' );
		lct_update_setting( 'version_in_db', $version_in_db );


		/**
		 * Check if we should update
		 */
		if ( $plugin['Version'] != $version_in_db ) {
			$update = true;
		} else {
			$update = false;
		}

		$update = apply_filters( 'lct/set_version/should_update', $update );


		/**
		 * Check the current loaded version against the version in the DB
		 * Run update if they are different
		 */
		if ( $update ) {
			/**
			 * Do anything that should be done when we have an LCT version update
			 *
			 * @date     0.0
			 * @since    0.0
			 * @verified 2021.08.30
			 */
			do_action( 'lct/set_version/update' );


			/**
			 * Set the plugin version in DB, if all went well
			 */
			lct_update_option( 'version', $plugin['Version'] );
		}
	}


	/**
	 * Add our custom roles and caps
	 * Each case represents the version of the plugin that the new role or cap was added.
	 * Newer versions should be added to the top of the list.
	 *
	 * @since    LCT 7.26
	 * @verified 2018.08.23
	 */
	function update_roles_n_caps()
	{
		if ( ! lct_doing() ) {
			set_transient( zxzu( 'did_update_roles_n_caps' ), true, DAY_IN_SECONDS );


			$this->set_roles_n_caps();


			/**
			 * Add new roles
			 */
			if ( ! empty( $this->custom_roles ) ) {
				foreach ( $this->custom_roles as $role => $name ) {
					$this->custom_roles[ $role ] = get_role( $role );

					if ( empty( $this->custom_roles[ $role ] ) ) {
						add_role( $role, $name, [] );
					}
				}
			}


			/**
			 * Add new caps
			 */
			if ( ! empty( $this->custom_caps ) ) {
				foreach ( $this->custom_caps as $cap => $cap_roles ) {
					$this->default_add_cap( $cap, $cap_roles );
				}
			}


			/**
			 * Remove caps that we had previously added
			 */
			if ( ! empty( $this->custom_caps_remove ) ) {
				foreach ( $this->custom_caps_remove as $cap => $cap_roles ) {
					$this->default_remove_cap( $cap, $cap_roles );
				}
			}
		}
	}


	/**
	 * We will put all our custom roles and caps in here, so they can be accessed by any function that needs them.
	 *
	 * @since    LCT 3.24
	 * @verified 2017.07.31
	 */
	function set_roles_n_caps()
	{
		$this->custom_roles = [];


		$this->custom_caps        = [];
		$this->custom_caps_remove = [];


		/**
		 * WordPress caps
		 */
		$this->custom_caps['read'] = [
			'administrator',
		];


		/**
		 * Plugin caps
		 */
		if ( post_type_exists( zxzu( 'org' ) ) ) {
			$this->custom_caps[ get_cnst( 'view_all_org' ) ] = [
				'administrator'
			];
		}
	}


	/**
	 * Add caps to roles
	 *
	 * @param $cap
	 * @param $cap_roles
	 *
	 * @since    LCT 7.33
	 * @verified 2017.07.31
	 */
	function default_add_cap( $cap, $cap_roles )
	{
		foreach ( $cap_roles as $cap_role ) {
			if (
				empty( $this->custom_roles[ $cap_role ] )
				&& ! empty( $cap_role )
			) {
				$this->custom_roles[ $cap_role ] = get_role( $cap_role );
			}


			if ( ! empty( $this->custom_roles[ $cap_role ] ) ) {
				$this->custom_roles[ $cap_role ]->add_cap( $cap );
			}
		}
	}


	/**
	 * Remove caps to roles
	 *
	 * @param $cap
	 * @param $cap_roles
	 *
	 * @since    LCT 2017.42
	 * @verified 2017.07.31
	 */
	function default_remove_cap( $cap, $cap_roles )
	{
		foreach ( $cap_roles as $cap_role ) {
			if (
				empty( $this->custom_roles[ $cap_role ] )
				&& ! empty( $cap_role )
			) {
				$this->custom_roles[ $cap_role ] = get_role( $cap_role );
			}


			if ( ! empty( $this->custom_roles[ $cap_role ] ) ) {
				$this->custom_roles[ $cap_role ]->remove_cap( $cap );
			}
		}
	}


	/**
	 * Remove our custom roles and caps
	 * The caps get removed when you remove the role.
	 *
	 * @since    LCT 7.26
	 * @verified 2017.07.31
	 */
	function deactivate_roles_n_caps()
	{
		$this->set_roles_n_caps();


		if ( ! empty( $this->custom_roles ) ) {
			foreach ( $this->custom_roles as $role => $name ) {
				$role = zxzu( $role );

				$this->custom_roles[ $role ] = get_role( $role );

				if ( ! empty( $this->custom_roles[ $role ] ) ) {
					remove_role( $role );
				}
			}
		}
	}


	/**
	 * Runs after this plugin is updated
	 *
	 * @since    LCT 7.44
	 * @verified 2017.07.31
	 */
	function run_post_plugin_update()
	{
		if ( lct_get_option( 'run_post_plugin_update' ) ) {
			$this->always_check_admin();

			lct_delete_option( 'run_post_plugin_update' );
		}
	}


	/**
	 * Only runs when this plugin is updated
	 *
	 * @param $WP_Upgrader
	 * @param $data
	 *
	 * @since    LCT 0.0
	 * @verified 2017.07.31
	 */
	function upgrader_process_complete(
		/** @noinspection PhpUnusedParameterInspection */
		$WP_Upgrader,
		$data
	) {
		if (
			! empty( $data['plugins'] )
			&& in_array( lct_get_setting( 'basename' ), $data['plugins'] )
		) {
			lct_update_option( 'run_post_plugin_update', true );

			$this->run_post_plugin_update();
		}
	}


	/**
	 * Do this stuff whenever someone goes to the /wp-admin/update-core.php page
	 *
	 * @since    LCT 0.0
	 * @verified 2017.07.31
	 */
	function load_update_core()
	{
		/**
		 * #1
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
}
