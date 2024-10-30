<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.08.03
 */
class lct_wp_admin_wf_admin
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.08.03
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
	 * @since    2017.61
	 * @verified 2017.08.03
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
			add_action( 'admin_init', [ $this, 'initial_tasks' ] );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Sets the login path to our default
	 *
	 * @since    2017.61
	 * @verified 2019.08.07
	 */
	function initial_tasks()
	{
		if ( ! lct_get_option( 'wf_initial_tasks' ) ) {
			/**
			 * Remove unneeded files
			 */
			if (
				file_exists( lct_path_site() . '/.git' )
				&& function_exists( 'shell_exec' )
			) {
				//get the current branch
				$branch = shell_exec( 'cd ' . lct_path_site() . ' && git branch | grep \'* \' | tr -d \'* \'' );


				//delete old ithemes files
				shell_exec( 'rm -rf ' . lct_path_up() . '/ithemes-security' );


				//only is a branch is set
				if ( $branch ) {
					//load our git library
					/** @noinspection PhpIncludeInspection */
					require_once( lct_get_root_path( 'includes/Git.php/Git.php' ) );
					$repo = Git::open( lct_path_site() );


					//update the git path if we are on our dev
					if ( lct_is_dev() ) {
						Git::set_bin( 'C:\wamp\_apps\git\bin\git.exe' );
					}


					//only do this on the live server. Otherwise it will commit too much, because phpstorm auto stages files
					if ( ! lct_is_dev() ) {
						//stage any deleted files
						if ( $repo->run( 'ls-files --deleted' ) ) {
							$repo->run( 'rm $(git ls-files --deleted)' );
						}


						//add the uploads folder
						$repo->add( '--all ' . ltrim( lct_strip_site( lct_path_up() ), '/' ) );


						//check the status
						$status = $repo->status();


						//only if we have a status worthy of a commit
						if (
							strpos( $status, 'nothing to commit, working directory clean' ) === false
							&& strpos( $status, 'nothing to commit (working directory clean)' ) === false
							&& strpos( $status, 'nothing to commit, working tree clean' ) === false
							&& strpos( $status, 'nothing to commit (working tree clean)' ) === false
							&& strpos( $status, 'no changes added to commit' ) === false
						) {
							//commit it
							$repo->commit( 'Uploads', false );


							//the the main repo know about it
							$repo->push( 'a', $branch );
						}
					}
				}
			}


			/**
			 * Update the sql_scripts
			 */
			if ( $sql_scripts = lct_acf_get_option( 'sql_scripts' ) ) {
				foreach ( $sql_scripts as $key => $sql_script ) {
					$new_sub_field = str_replace( [ 'TRUNCATE `{wpdb_prefix}itsec_lockouts`;', 'TRUNCATE `{wpdb_prefix}itsec_log`;', 'TRUNCATE `{wpdb_prefix}itsec_temp`;' ], '', $sql_script['script'] );

					$sql_scripts[ $key ]['script'] = trim( str_replace( [ "\r\n\r\n\r\n", "\r\n\r\n" ], "\r\n", $new_sub_field ) );
				}


				lct_acf_update_option( 'sql_scripts', $sql_scripts );
			}


			if ( ! lct_is_dev() ) {
				lct_update_option( 'wf_initial_tasks', true, false );
			}
		}
	}
}
