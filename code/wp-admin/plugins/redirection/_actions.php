<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.02.11
 */
class lct_wp_admin_redirection_actions
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.02.11
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
	 * @since    2017.12
	 * @verified 2017.02.11
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
			add_action( 'admin_init', [ $this, 'update_login_redirects' ] );

			add_action( 'admin_init', [ $this, 'update_blog_redirects' ] );

			//load-{$pagenow}
			add_action( 'load-update-core.php', [ $this, 'update_redirection_options' ] );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Updated our default redirection entries
	 *
	 * @since    2017.61
	 * @verified 2017.08.14
	 */
	function update_login_redirects()
	{
		if ( ! lct_get_option( 'per_version_update_login_redirects' ) ) {
			global $wpdb;

			$group_id = $wpdb->get_var( "SELECT `group_id` FROM `{$wpdb->prefix}redirection_items` WHERE `url` = '/x/'" );

			if ( ! $group_id ) {
				$group_id = 1;
			}


			//Delete the originals
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM `{$wpdb->prefix}redirection_items` 
					WHERE `url` LIKE '%s' OR 
					`url` = '%s' OR 
					`url` = '%s'",
					'%sitelogin%',
					'/x/',
					'/index.html'
				)
			);


			//Update position
			$wpdb->query( "UPDATE `{$wpdb->prefix}redirection_items` SET `position` = '1' WHERE `position` = '0'" );


			//Add in the defaults
			if (
				lct_plugin_active( 'wf' )
				&& lct_plugin_active( 'wps-hide-login' )
			) {
				$wpdb->query(
					"INSERT INTO `{$wpdb->prefix}redirection_items` 
					(`id`, `url`, `regex`, `position`, `last_count`, `last_access`, `group_id`, `status`, `action_type`, `action_code`, `action_data`, `match_type`, `title`) VALUES 
					(49000, '/x/sitelogin/', '0', '901', '0', '', {$group_id}, 'enabled', 'url', '301', '/sitelogin/', 'url', NULL),
					(49001, '/x/sitelogin', '0', '902', '0', '', {$group_id}, 'enabled', 'url', '301', '/sitelogin/', 'url', NULL),
					(49002, '/x/', '0', '903', '0', '', {$group_id}, 'enabled', 'url', '301', '/', 'url', NULL),
					(49999, '/index.html', '0', '904', '0', '', {$group_id}, 'enabled', 'url', '301', '/', 'url', NULL)"
				);
			}


			lct_update_option( 'per_version_update_login_redirects', true, false );
		}
	}


	/**
	 * Updated redirects if permalink structure is change to /blog/
	 *
	 * @since    2017.61
	 * @verified 2018.03.22
	 */
	function update_blog_redirects()
	{
		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}


		if (
			( $permalink_structure = get_option( 'permalink_structure' ) )
			&& strpos( $permalink_structure, '/blog/' ) === 0
		) {
			if ( ! lct_get_option( 'update_blog_redirects' ) ) {
				global $wpdb;

				$group_id = $wpdb->get_var( "SELECT `group_id` FROM `{$wpdb->prefix}redirection_items` WHERE `url` = '/x/'" );

				if ( ! $group_id ) {
					$group_id = 1;
				}


				//Add in the redirects
				$args  = [
					'posts_per_page'         => - 1,
					'post_type'              => 'post',
					'cache_results'          => true,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
				];
				$posts = get_posts( $args );


				if ( ! empty( $posts ) ) {
					foreach ( $posts as $post ) {
						$old_slug             = '/' . $post->post_name . '/';
						$new_slug             = str_replace( '%postname%', $post->post_name, $permalink_structure );
						$old_url_status       = $wpdb->get_var( "SELECT `url` FROM `{$wpdb->prefix}redirection_items` WHERE `url` = '{$old_slug}'" );
						$new_url_status       = $wpdb->get_var( "SELECT `url` FROM `{$wpdb->prefix}redirection_items` WHERE `url` = '{$new_slug}'" );
						$old_n_new_url_status = $wpdb->get_var( "SELECT `url` FROM `{$wpdb->prefix}redirection_items` WHERE `url` = '{$old_slug}' AND `action_data` = '{$new_slug}'" );


						if (
							! $old_url_status
							&& ! $new_url_status
						) {
							$wpdb->query(
								"INSERT INTO `{$wpdb->prefix}redirection_items` 
								(`id`, `url`, `regex`, `position`, `last_count`, `last_access`, `group_id`, `status`, `action_type`, `action_code`, `action_data`, `match_type`, `title`) VALUES 
								(null, '{$old_slug}', '0', '0', '0', '', {$group_id}, 'enabled', 'url', '301', '{$new_slug}', 'url', NULL)"
							);
						} elseif ( $new_url_status ) {
							lct_get_notice( sprintf( 'You may want to check on redirects that contain <strong>%s</strong> something is wrong! Seriously.', $new_slug ), - 1 );
						} elseif (
							$old_url_status
							&& ! $old_n_new_url_status
						) {
							lct_get_notice( sprintf( 'You may want to check on redirects that contain <strong>%s</strong> something is wrong! Seriously.', $old_slug ), - 1 );
						} elseif ( $old_n_new_url_status ) {
							//Do nothing all good
						} else {
							lct_get_notice( sprintf( 'You may want to check on redirects that contain <strong>%s</strong> something is wrong! Seriously.', $post->post_name ), - 1 );
						}
					}
				}


				lct_update_option( 'update_blog_redirects', true, false );
			}
		}
	}


	/**
	 * Update the Redirection Settings to our desired parameters
	 *
	 * @since    2018.10
	 * @verified 2018.02.01
	 */
	function update_redirection_options()
	{
		//Update the Settings
		if (
			( $version = get_option( 'redirection_version' ) )
			&& version_compare( $version, '2.4', '<=' )
		) {
			$name    = 'redirection_options';
			$options = get_option( $name );


			//Force some options
			unset( $options['monitor_post'] );
			unset( $options['support'] );
			unset( $options['expire_redirect'] );
			unset( $options['expire_404'] );
			unset( $options['newsletter'] );
			unset( $options['monitor_types'] );
			unset( $options['redirect_cache'] );
			unset( $options['ip_logging'] );
			unset( $options['rest_api'] );


			$default_options = [
				'monitor_post'        => 0,
				'auto_target'         => '',
				'support'             => true,
				'expire_redirect'     => - 1,
				'expire_404'          => - 1,
				'newsletter'          => false,
				'monitor_types'       =>
					[
					],
				'associated_redirect' => '',
				'redirect_cache'      => - 1,
				'ip_logging'          => 1,
				'rest_api'            => 0,
			];
			$options         = wp_parse_args( $options, $default_options );


			update_option( $name, $options );
		}
	}
}
