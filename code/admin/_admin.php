<?php /** @noinspection PhpMissingParamTypeInspection */
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2016.12.15
 */
class lct_admin_admin
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2016.12.15
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
	 * @since    7.56
	 * @verified 2019.02.11
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
		$this->force_html_emails();


		/**
		 * actions
		 */
		add_action( 'tool_box', [ $this, 'add_tool_boxes' ] );

		add_action( 'admin_init', [ $this, 'wp_recovery_mode_clear_rate_limit' ], 999 );

		add_action( 'init', [ $this, 'add_image_sizes' ] );

		add_action( 'init', [ $this, 'set_wp_version' ] );

		add_action( 'init', [ $this, 'check_for_cron_not_working' ] );

		add_action( 'wp', [ $this, 'set_parent_post_id' ] );

		add_action( 'updated_postmeta', [ $this, 'mark_post_to_be_updated_later' ], 10, 4 );

		add_action( 'updated_user_meta', [ $this, 'updated_user_meta' ], 10, 4 );

		add_action( 'lct/always_shutdown', [ $this, 'mark_posts_as_updated_with_postmeta_changes' ], 19 );

		add_action( 'lct/always_shutdown', [ $this, 'do_function_later' ], 20 );

		add_action( 'delete_attachment', [ $this, 'delete_attachment' ] );

		add_action( 'template_redirect', [ $this, 'remove_wp_admin_menu_items' ], 999 );
		add_action( 'admin_init', [ $this, 'remove_wp_admin_menu_items' ], 999 );

		add_action( 'upload_mimes', [ $this, 'add_file_types_to_uploads' ] );


		/**
		 * filters
		 */
		add_filter( 'auth_cookie_expiration', [ $this, 'auth_cookie_expiration' ], 999, 3 );

		add_filter( 'pre_wp_update_comment_count_now', [ $this, 'only_count_comments' ], 10, 3 );

		add_action( 'add_post_metadata', [ $this, 'dont_save_pings' ], 10, 5 );

		add_filter( 'wp_mail', [ $this, 'force_send_to_on_sb' ] );


		/**
		 * This will set the vcs as false so that the minor version auto update will still run for us.
		 */
		add_filter( 'automatic_updates_is_vcs_checkout', '__return_false', 1 );

		if (
			! function_exists( 'lct_domain_mapping_plugins_uri' )
			&& function_exists( 'domain_mapping_plugins_uri' )
		) {
			remove_filter( 'plugins_url', 'domain_mapping_plugins_uri', 1 );
			add_filter( 'plugins_url', [ $this, 'domain_mapping_plugins_uri' ], 1 );
		}


		if ( lct_frontend() ) {
			/**
			 * actions
			 */
			add_action( 'init', [ $this, 'set_version' ], 5 );

			add_action( 'admin_bar_menu', [ $this, 'add_post_id_to_admin_bar' ], 999 );


			/**
			 * filters
			 */
			add_filter( 'rpwe_markup', [ $this, 'rpwe_markup' ] );

			add_filter( 'wpseo_robots', [ $this, 'wpseo_robots' ] );


			/**
			 * SPECIAL
			 */
			if (
				lct_is_dev_or_sb()
				|| (
					lct_plugin_active( 'acf' )
					&& lct_acf_get_option_raw( 'force_append_dev_sb' )
				)
			) {
				add_filter( 'document_title_parts', [ $this, 'doc_title' ] );

				add_filter( 'pre_get_document_title', [ $this, 'pre_get_document_title' ], 999 );

				add_filter( 'option_blog_public', '__return_false' ); //adds noindex, so hopefully a sandbox won't accidentally get indexed.

				//Disable SSL check for our self signed certs
				add_filter( 'https_local_ssl_verify', '__return_false' );
				add_filter( 'https_ssl_verify', '__return_false' );
			}
		}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Add links to any special tools we created
	 *
	 * @date     2022.03.11
	 * @since    2022.3
	 * @verified 2022.03.11
	 */
	function add_tool_boxes()
	{
		if ( function_exists( 'afwp' ) ) {
			return;
		}


		if (
			! current_user_can( 'administrator' )
			|| afwp_REQUEST_arg( 'tool' )
		) {
			echo sprintf( '<div class="card">
						<h2 class="title">%s</h2>
						<p><a href="%s" class="button button-primary" style="margin-top: 6px;">Back to Tools Page</a></p>
					</div>',
				'Return to the main tools page',
				admin_url( 'tools.php' )
			);


			return;
		}


		/**
		 * List all the tools
		 */
		foreach ( $this->all_tools() as $key => $tool ) {
			if ( ! empty( $tool['class'] ) ) {
				$tool_slug = $tool['class'] . '::' . $key;
			} else {
				$tool_slug = $key;
			}


			echo sprintf( '<div class="card">
						<h2 class="title">%s</h2>
						<p>%s<br /><a href="%s" class="button button-primary" style="margin-top: 6px;">Run Now</a></p>
					</div>',
				$tool['title'],
				$tool['description'],
				admin_url( 'tools.php?tool=' . $tool_slug )
			);
		}
	}


	/**
	 * A list of tools we can run
	 *
	 * @return array
	 * @date     2022.03.11
	 * @since    2022.3
	 * @verified 2022.03.11
	 */
	function all_tools()
	{
		return [
			'wp_recovery_mode_clear_rate_limit' => [
				'class'       => __CLASS__,
				'title'       => 'LCT Clear the time of the last email sent for WP Recovery Mode',
				'description' => 'Do this when you have received a "[WP CRITICAL ERROR]" email',
			],
		];
	}


	/**
	 * Clear the time of the last email sent for WP Recovery Mode
	 *
	 * @date     2022.03.11
	 * @since    2022.3
	 * @verified 2022.03.11
	 */
	function wp_recovery_mode_clear_rate_limit()
	{
		if ( ! afwp_tool_is_active() ) {
			return;
		}


		delete_option( 'recovery_mode_email_last_sent' );
	}


	/**
	 * Set the filters, so that system emails will be formatted properly.
	 *
	 * @date       2022.02.12
	 * @since      2022.1
	 * @verified   2022.02.12
	 */
	function force_html_emails()
	{
		/**
		 * Admin
		 */
		add_filter( 'new_admin_email_content', [ $this, 'force_email_html_w_content' ], 99 );

		add_filter( 'auto_core_update_email', [ $this, 'force_email_html_w_mail' ], 99 );
		add_filter( 'auto_core_update_email', [ $this, 'force_email_subject_tag' ], 99 );
		add_filter( 'auto_core_update_email', [ $this, 'force_email_tag_8' ], 99999 );

		add_filter( 'auto_plugin_theme_update_email', [ $this, 'force_email_html_w_mail' ], 99 );
		add_filter( 'auto_plugin_theme_update_email', [ $this, 'force_email_subject_tag' ], 99 );
		add_filter( 'auto_plugin_theme_update_email', [ $this, 'force_email_tag_8' ], 99999 );

		add_filter( 'automatic_updates_debug_email', [ $this, 'force_email_html_w_mail' ], 99 );
		add_filter( 'automatic_updates_debug_email', [ $this, 'force_email_subject_tag' ], 99 );
		add_filter( 'automatic_updates_debug_email', [ $this, 'force_email_tag_8' ], 99999 );

		add_filter( 'site_admin_email_change_email', [ $this, 'force_email_html_w_mail' ], 99 );
		add_filter( 'site_admin_email_change_email', [ $this, 'force_email_subject_tag' ], 99 );
		add_filter( 'site_admin_email_change_email', [ $this, 'force_email_tag_8' ], 99999 );

		add_filter( 'wp_installed_email', [ $this, 'force_email_html_w_mail' ], 99 );

		add_filter( 'wp_new_user_notification_email_admin', [ $this, 'force_email_html_w_mail' ], 99 );
		add_filter( 'wp_new_user_notification_email_admin', [ $this, 'force_email_subject_tag' ], 99 );
		add_filter( 'wp_new_user_notification_email_admin', [ $this, 'force_email_tag_1' ], 99999 );

		add_filter( 'wp_password_change_notification_email', [ $this, 'force_email_html_w_mail' ], 99 );
		add_filter( 'wp_password_change_notification_email', [ $this, 'force_email_subject_tag' ], 99 );
		add_filter( 'wp_password_change_notification_email', [ $this, 'force_email_tag_1' ], 99999 );


		/**
		 * User
		 */
		add_filter( 'new_user_email_content', [ $this, 'force_email_html_w_content' ], 99 );

		add_filter( 'retrieve_password_message', [ $this, 'force_email_html_w_content' ], 99 );

		add_filter( 'user_confirmed_action_email_content', [ $this, 'force_email_html_w_content' ], 99 );

		add_filter( 'user_request_action_email_content', [ $this, 'force_email_html_w_content' ], 99 );

		add_filter( 'wp_new_user_notification_email', [ $this, 'force_email_html_w_mail' ], 99 );

		add_filter( 'wp_privacy_personal_data_email_content', [ $this, 'force_email_html_w_content' ], 99 );

		add_filter( 'email_change_email', [ $this, 'force_email_html_w_mail' ], 99 );
		add_filter( 'email_change_email', [ $this, 'force_email_tag_1' ], 99999 );

		add_filter( 'password_change_email', [ $this, 'force_email_html_w_mail' ], 99 );
		add_filter( 'password_change_email', [ $this, 'force_email_tag_1' ], 99999 );
	}


	/**
	 * Add a filter tag to the subject, so that we can easily filter out emails
	 *
	 * @param array $mail
	 *
	 * @return array
	 * @date       2022.02.12
	 * @since      2022.1
	 * @verified   2022.02.12
	 */
	function force_email_subject_tag( $mail )
	{
		/**
		 * Add filter tag to the subject
		 */
		if ( strpos( $mail['subject'], '[WP Notification]' ) === false ) {
			$mail['subject'] = '[WP Notification] ' . $mail['subject'];
		}


		return $mail;
	}


	/**
	 * Add a filter tag to the message body, so that we can easily filter out emails
	 *
	 * @param array $mail
	 *
	 * @return array
	 * @date       2022.02.12
	 * @since      2022.1
	 * @verified   2022.02.12
	 */
	function force_email_tag_1( $mail )
	{
		return $this->force_email_tag_scale( $mail, 1 );
	}


	/**
	 * Add a filter tag to the message body, so that we can easily filter out emails
	 *
	 * @param array $mail
	 *
	 * @return array
	 * @date       2022.02.12
	 * @since      2022.1
	 * @verified   2022.02.12
	 */
	function force_email_tag_8( $mail )
	{
		return $this->force_email_tag_scale( $mail, 8 );
	}


	/**
	 * Return a filter tag, so that we can easily filter out emails
	 * Priority scale is 1 to 10
	 *  1 = The Lowest Priority
	 * 10 = The Highest Priority
	 *
	 * @param array $mail
	 * @param int   $priority
	 *
	 * @return array
	 * @date       2022.02.12
	 * @since      2022.1
	 * @verified   2022.02.12
	 */
	function force_email_tag_scale( $mail, $priority = 0 )
	{
		/**
		 * Make the tag
		 */
		$the_tag = '<div style="display: none !important;">[WP_NOTIFIER_TAG][' . $priority . ']</div>';


		/**
		 * Add filter text to the message body
		 */
		if ( isset( $mail['message'] ) && strpos( $mail['message'], '[WP_NOTIFIER_TAG]' ) === false ) {
			$mail['message'] .= $the_tag;
		}
		if ( isset( $mail['body'] ) && strpos( $mail['body'], '[WP_NOTIFIER_TAG]' ) === false ) {
			$mail['body'] .= $the_tag;
		}


		return $mail;
	}


	/**
	 * Return a filter tag, so that we can easily filter out emails
	 * Add the filter that this email is a part of
	 *
	 * @param string $content
	 *
	 * @return string
	 * @date       2022.02.12
	 * @since      2022.1
	 * @verified   2022.02.12
	 */
	function force_email_tag_filter( $content )
	{
		if ( strpos( $content, '[WP_NOTIFIER_FILTER]' ) === false ) {
			$content .= '<div style="display: none !important;">[WP_NOTIFIER_FILTER][' . current_filter() . ']</div>';
		}


		return $content;
	}


	/**
	 * Do the actual forcing of HTML Text
	 *
	 * @param string $return
	 *
	 * @return string
	 * @date       2022.02.12
	 * @since      2022.1
	 * @verified   2022.02.12
	 */
	function force_email_html_w_content( $return )
	{
		/**
		 * Force HTML text
		 */
		add_filter( 'wp_mail_content_type', [ $this, 'return_html' ], 99999 );


		/**
		 * Make the text HTML
		 */
		$return = str_replace( [ "\r\n", "\r", "\n" ], '<br />', $return );


		return $this->force_email_tag_filter( $return );
	}


	/**
	 * Do the actual forcing of HTML Text
	 *
	 * @param array $mail
	 *
	 * @return array
	 * @date       2022.02.12
	 * @since      2022.1
	 * @verified   2022.02.12
	 */
	function force_email_html_w_mail( $mail )
	{
		/**
		 * Force HTML text
		 */
		add_filter( 'wp_mail_content_type', [ $this, 'return_html' ], 99999 );


		/**
		 * Make the text HTML
		 */
		if ( isset( $mail['message'] ) ) {
			$mail['message'] = str_replace( [ "\r\n", "\r", "\n" ], '<br />', $mail['message'] );
			$mail['message'] = $this->force_email_tag_filter( $mail['message'] );
		}

		if ( isset( $mail['body'] ) ) {
			$mail['body'] = str_replace( [ "\r\n", "\r", "\n" ], '<br />', $mail['body'] );
			$mail['body'] = $this->force_email_tag_filter( $mail['body'] );
		}


		return $mail;
	}


	/**
	 * Do the actual forcing of HTML Text
	 *
	 * @return string
	 * @date       2022.02.12
	 * @since      2022.1
	 * @verified   2022.02.12
	 */
	function return_html()
	{
		return 'text/html';
	}


	/**
	 * Set the current version of this plugin
	 *
	 * @since    2017.13
	 * @verified 2018.08.23
	 */
	function set_version()
	{
		//bail early if already ran
		if ( lct_did() ) {
			return;
		}


		//set the loaded version
		$plugin = get_file_data( lct_get_setting( 'plugin_file' ), [ 'Version' => 'Version' ], 'plugin' );
		//$plugin = get_plugin_data( lct_get_setting( 'plugin_file' ) ); //This is slower
		lct_update_setting( 'version', $plugin['Version'] );
	}


	function add_image_sizes()
	{
		if ( lct_get_setting( 'use_team' ) ) {
			add_image_size( get_cnst( 'team' ), '400', '400' );
		}
	}


	/**
	 * Fix Multisite plugins_url issue
	 *
	 * @param      $full_url
	 * @param null $path
	 * @param null $plugin
	 *
	 * @return string
	 * @since    0.0
	 * @verified 2016.10.31
	 */
	function domain_mapping_plugins_uri(
		$full_url,
		/** @noinspection PhpUnusedParameterInspection */
		$path = null,
		/** @noinspection PhpUnusedParameterInspection */
		$plugin = null
	) {
		$pos = stripos( $full_url, PLUGINDIR );


		if ( $pos === false ) {
			return $full_url;
		} else {
			return get_option( 'siteurl' ) . substr( $full_url, $pos - 1 );
		}
	}


	/**
	 * Set the $object_id so we can mark that $object_id as modified later
	 *
	 * @param $meta_id
	 * @param $object_id
	 * @param $meta_key
	 *
	 * @unused   param $meta_value
	 * @since    0.0
	 * @verified 2023.09.20
	 */
	function mark_post_to_be_updated_later( $meta_id, $object_id, $meta_key )
	{
		/**
		 * postmeta changes that we should ignore
		 */
		$exclude = [
			'_edit_last',
			'_edit_lock',
			'_encloseme',
			'_pingme',
			//Special
			'_schedule_id',
			'_transient_tribe_attendees',
			'_tribe_modified_fields',
			'_tribe_progressive_ticket_current_number',
			'avada_post_views_count',
			'avada_today_post_views_count',
			'xbs:::last_updated_installations',
		];


		if (
			( $post_type = get_post_type( $object_id ) )
			&& $post_type === 'ninja-table'
		) {
			return;
		}


		if ( ! in_array( $meta_key, $exclude ) ) {
			lct_append_later( 'updated_postmeta', $object_id );
		}
	}


	/**
	 * Don't Save the ping meta. It is useless
	 *
	 * @param $check
	 * @param $object_id
	 * @param $meta_key
	 *
	 * @unused   param $meta_value
	 * @unused   param $unique
	 * @return mixed
	 * @since    2019.2
	 * @verified 2019.02.13
	 */
	function dont_save_pings(
		$check,
		/** @noinspection PhpUnusedParameterInspection */
		$object_id,
		$meta_key
	) {
		/**
		 * postmeta we should not save
		 */
		$exclude = [
			'_pingme',
			'_encloseme',
		];


		if ( in_array( $meta_key, $exclude ) ) {
			$check = false;
		}


		return $check;
	}


	/**
	 * Update the nickname & display name when we change the first or last name
	 *
	 * @param $meta_id
	 * @param $object_id
	 * @param $meta_key
	 * @param $_meta_value
	 *
	 * @since    7.35
	 * @verified 2016.11.17
	 */
	function updated_user_meta(
		/** @noinspection PhpUnusedParameterInspection */
		$meta_id,
		$object_id,
		$meta_key,
		/** @noinspection PhpUnusedParameterInspection */
		$_meta_value
	) {
		if ( in_array( $meta_key, [ 'first_name', 'last_name' ] ) ) {
			$user = get_userdata( $object_id );


			$user->display_name = $user->first_name . ' ' . $user->last_name;
			$user->nickname     = $user->first_name . ' ' . $user->last_name;

			wp_update_user( $user );
		}
	}


	/**
	 * Update the modified time of a post when we edit any meta
	 *
	 * @since    7.26
	 * @verified 2019.06.20
	 */
	function mark_posts_as_updated_with_postmeta_changes()
	{
		if (
			! ( $updated_posts = lct_get_later( 'updated_postmeta' ) )
			&& empty( $updated_posts )
		) {
			return;
		}


		/**
		 * Remove Actions
		 */
		remove_action( 'transition_post_status', '_update_term_count_on_transition_post_status' );
		remove_all_actions( 'edit_post' );
		remove_all_actions( 'post_updated' );
		remove_all_actions( 'save_post' );
		remove_all_actions( 'wp_insert_post' );


		/**
		 * Update each post
		 */
		foreach ( array_unique( $updated_posts ) as $post_id ) {
			$args = [
				'ID' => $post_id
			];
			wp_update_post( $args );
		}
	}


	/**
	 * Set the expiration, so we don't have to keep logging in
	 *
	 * @param $seconds
	 * @param $user_id
	 * @param $remember
	 *
	 * @return int
	 * @since    7.27
	 * @verified 2016.11.04
	 */
	function auth_cookie_expiration(
		$seconds,
		$user_id,
		/** @noinspection PhpUnusedParameterInspection */
		$remember
	) {
		if (
			defined( 'DAY_IN_SECONDS' )
			&& user_can( $user_id, 'administrator' )
		) {
			$seconds = ( DAY_IN_SECONDS * 365 );
		}


		return $seconds;
	}


	/**
	 * Update the document title
	 *
	 * @param $title
	 *
	 * @return mixed
	 * @since    2017.57
	 * @verified 2017.07.11
	 */
	function doc_title( $title )
	{
		$title['dev'] = lct_i_append_dev_sb( '' );


		if ( isset( $title['title'] ) ) {
			$working_title = $title['title'];

			unset( $title['title'] );

			$title['title'] = $working_title;
		}


		if ( isset( $title['site'] ) ) {
			$working_site = $title['site'];

			unset( $title['site'] );

			$title['site'] = $working_site;
		}


		return $title;
	}


	/**
	 * Update the document title
	 *
	 * @param $title
	 *
	 * @return mixed
	 * @since    2017.57
	 * @verified 2017.07.11
	 */
	function pre_get_document_title( $title )
	{
		if ( $title ) {
			$title = lct_i_append_dev_sb( $title );
		}


		return $title;
	}


	/**
	 * Save the WP version in a setting
	 *
	 * @since    2017.71
	 * @verified 2017.08.29
	 */
	function set_wp_version()
	{
		global $wp_version;


		lct_update_setting( 'wp_version', $wp_version );
	}


	/**
	 * Run any functions that were run by lct_function_later()
	 *
	 * @since    2017.77
	 * @verified 2019.01.28
	 */
	function do_function_later()
	{
		lct_do_function_later();
	}


	/**
	 * Process the HTML of a title if needed
	 *
	 * @param $html
	 *
	 * @return mixed
	 * @since    2017.74
	 * @verified 2017.09.06
	 */
	function rpwe_markup( $html )
	{
		if (
			! empty( $html )
			&& strpos( $html, '&lt;sup&gt;' ) !== false
		) {
			$html = html_entity_decode( $html );
		}


		return $html;
	}


	/**
	 * Set the post_id for the main page we are on, basically before we enter a loop of some sort
	 *
	 * @since    2018.4
	 * @verified 2018.07.28
	 */
	function set_parent_post_id()
	{
		lct_update_setting( 'parent_post_id', lct_get_post_id() );
	}


	/**
	 * Check to see if the cron is not do its job
	 *
	 * @since    2018.26
	 * @verified 2018.07.12
	 */
	function check_for_cron_not_working()
	{
		$cron_issues = lct_get_option( 'per_version_cron_issues', [] );


		if (
			! defined( 'LCT_DISABLE_CHECK_CRON_NOT_WORKING' )
			&& ! get_transient( zxzu( 'check_for_cron_not_working_limiter' ) )
		) {
			set_transient( zxzu( 'check_for_cron_not_working_limiter' ), 1, HOUR_IN_SECONDS );


			if ( lct_is_dev_or_sb() ) {
				lct_delete_option( 'per_version_cron_issues' );


				return;
			}


			$cron                       = get_option( 'cron', [] );
			$cron_issues['not_working'] = false;


			if ( ! empty( $cron ) ) {
				$time = current_time( 'timestamp', 1 );
				ksort( $cron );


				foreach ( $cron as $k => $v ) {
					if ( ! is_numeric( $k ) ) {
						continue;
					}


					if ( ( $time - $k ) > ( DAY_IN_SECONDS * 2 ) ) {
						$cron_issues['not_working'] = true;
					} else {
						$cron_issues = [];
						lct_delete_option( 'per_version_cron_issues' );
					}


					break;
				}
			}
		}


		if ( ! empty( $cron_issues['not_working'] ) ) {
			if ( empty( $cron_issues['email_sent'] ) ) {
				$url = get_option( 'home' );


				$args = [
					'from_name' => zxzb( ' Cron Checker' ),
					'subject'   => sprintf( 'Cron may not be working properly on %s', $url ),
					'message'   => sprintf( 'Cron may not be working properly on <a href="%1$s">%1$s</a>.', $url ),
				];
				lct_quick_send_email( $args );


				$cron_issues['email_sent'] = true;
			}


			lct_update_option( 'per_version_cron_issues', $cron_issues, true );
		}
	}


	/**
	 * noindex,follow and pages that are not the first page
	 *
	 * @param $robots
	 *
	 * @return string
	 * @since    2018.43
	 * @verified 2018.04.10
	 */
	function wpseo_robots( $robots )
	{
		if ( is_paged() ) {
			$robots = 'noindex,follow';
		}


		return $robots;
	}


	/**
	 * We only want the count for comments to be comments
	 * NOT Trackbacks, Pingbacks or any other custom comment type
	 *
	 * @param int $new
	 * @param int $old
	 * @param int $post_id
	 *
	 * @return int
	 * @since        2018.59
	 * @verified     2020.10.26
	 * @noinspection PhpUnusedParameterInspection
	 */
	function only_count_comments( $new, $old, $post_id )
	{
		global $wpdb;


		$new_count = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM `{$wpdb->comments}` 
					WHERE `comment_post_ID` = %d AND 
					`comment_approved` = '1' AND
					`comment_type` IN ( 'comment', '' )",
				$post_id
			)
		);


		if ( is_int( $new_count ) ) {
			$new = $new_count;
		}


		return $new;
	}


	/**
	 * Display the post_type and post_id in the admin bar
	 *
	 * @param WP_Admin_Bar $wp_admin_bar
	 *
	 * @return array|mixed|string|void
	 * @since        2018.64
	 * @verified     2018.09.18
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function add_post_id_to_admin_bar( $wp_admin_bar )
	{
		global $user_ID;

		if ( $user_ID === 0 ) {
			return;
		}


		if (
			! ( $current_user = wp_get_current_user() )
			|| is_null( $current_user )
		) {
			return;
		}


		if ( ! in_array( 'administrator', $current_user->roles ) ) {
			return;
		}


		$identify        = 'lct_post_id_n_post_type';
		$color           = 'lightgreen';
		$post_type       = 'ID';
		$post_type_label = 'ID';
		$post_id         = null;


		if (
			is_single()
			|| is_page()
		) {
			if ( $post_type_obj = get_post_type_object( get_post_type() ) ) {
				$post_type       = $post_type_obj->name;
				$post_type_label = $post_type_obj->labels->singular_name;
			}

			$post_id = lct_get_post_id();
		}


		if ( $post_id ) {
			$args = [
				'id'    => $identify,
				'title' => sprintf( '<span style="color: %s !important;">%s: %s</span>', $color, $post_type_label, $post_id ),
				'meta'  => [
					'title' => $post_type,
					'class' => $identify
				]
			];
			$wp_admin_bar->add_menu( $args );
		}
	}


	/**
	 * Make sure all the images get deleted
	 *
	 * @param $att_id
	 *
	 * @since    2018.64
	 * @verified 2018.09.27
	 */
	function delete_attachment( $att_id )
	{
		if (
			get_post_type( $att_id ) !== 'attachment'
			|| ! wp_attachment_is_image( $att_id )
		) {
			return;
		}


		$att_meta = wp_get_attachment_metadata( $att_id );


		if ( ! empty( $att_meta['sizes'] ) ) {
			$uploads_path = lct_path_up() . '/';


			foreach ( $att_meta['sizes'] as $size => $_att_sizes ) {
				$in_use      = false;
				$_image_info = image_get_intermediate_size( $att_id, $size );


				if (
					! empty( $_att_sizes['file'] )
					&& ! empty( $_image_info['file'] )
					&& ! empty( $_image_info['path'] )
					&& $_image_info['file'] === $_att_sizes['file']
					&& ( $_image_path = $uploads_path . $_image_info['path'] )
					&& file_exists( $_image_path )
				) {
					if ( $exists = lct_get_posts_with_image( null, $_att_sizes ) ) {
						$in_use = true;
					} elseif ( $exists = lct_get_postmetas_with_image( $att_id, $_att_sizes ) ) {
						$in_use = true;
					} elseif ( $exists = lct_get_termmetas_with_image( $att_id, $_att_sizes ) ) {
						$in_use = true;
					} elseif ( $exists = lct_get_usermetas_with_image( $att_id, $_att_sizes ) ) {
						$in_use = true;
					} elseif ( $exists = lct_get_options_with_image( $att_id, $_att_sizes ) ) {
						$in_use = true;
					}


					if ( ! $in_use ) {
						@unlink( $_image_path );
					}
				}
			}
		}
	}


	/**
	 * Cleanup the Admin Menu
	 *
	 * @since    2019.3
	 * @verified 2019.02.18
	 */
	function remove_wp_admin_menu_items()
	{
		remove_action( 'admin_bar_menu', 'wp_admin_bar_search_menu', 4 );
		remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu' );
		remove_action( 'admin_bar_menu', 'wp_admin_bar_customize_menu', 40 );
		remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
		remove_action( 'admin_bar_menu', 'wp_admin_bar_new_content_menu', 70 );


		lct_remove_filter_like( 'admin_bar_menu', 'suspend_transients_button', 999, true );
		lct_remove_filter_like( 'wp_before_admin_bar_render', 'add_wp_toolbar_menu', false, true, 'Avada_Admin' );


		if ( lct_frontend() ) {
			remove_action( 'admin_bar_menu', 'wp_admin_bar_updates_menu', 50 );
		}
	}


	/**
	 * Allow SVG to be uploaded
	 *
	 * @param array $file_types
	 *
	 * @return array
	 * @since        2019.23
	 * @verified     2019.08.22
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function add_file_types_to_uploads( $file_types )
	{
		$new_file_types = [
			'svg' => 'image/svg+xml',
		];


		$file_types = array_merge( $file_types, $new_file_types );


		return $file_types;
	}


	/**
	 * Force all emails to be sent to the admin when you are on a sandbox
	 *
	 * @param array $mail
	 *
	 * @return array
	 * @since    2020.7
	 * @verified 2023.07.10
	 */
	function force_send_to_on_sb( $mail )
	{
		if ( ! lct_is_dev_or_sb() ) {
			remove_filter( 'wp_mail', [ $this, 'force_send_to_on_sb' ] );


			return $mail;
		}


		$original_send         = [];
		$original_send['from'] = 'from: Unknown';
		$original_sending      = 'Original Sending Settings:';
		$is_headers_array      = false;
		$admin_email           = get_option( 'admin_email' );
		/**
		 * We may want to change lct_is_dev_or_sb() --TO-- lct_is_dev()
		 */
		$admin_email     = ( lct_is_dev_or_sb() || ! is_email( $admin_email ) ) ? 'dev@eetah.com' : get_option( 'admin_email' );
		$mail['subject'] = $mail['subject'] ?? 'NO SUBJECT';
		$mail['subject'] = lct_i_append_dev_sb( $mail['subject'] );


		$original_send_already_set = false;
		if (
			(
				isset( $mail['message'] )
				&& strpos( $mail['message'], $original_sending ) !== false
			)
			|| (
				isset( $mail['body'] )
				&& strpos( $mail['body'], $original_sending ) !== false
			)
		) {
			$original_send_already_set = true;
		}


		if (
			! $original_send_already_set
			&& ! empty( $mail['to'] )
		) {
			$original_send[] = 'to: ' . $mail['to'];
			$mail['to']      = $admin_email;


			if ( $tmp = lct_get_setting( 'force_send_to_on_sb_to' ) ) {
				if ( is_array( $tmp ) ) {
					$tmp = implode( ',', $tmp );
				}


				$mail['to'] = $tmp;
			}
		}


		if ( ! empty( $mail['headers'] ) ) {
			if ( ! is_array( $mail['headers'] ) ) {
				// Explode the headers out, so this function can take
				// both string headers and an array of headers.
				$tmp_headers = explode( "\n", str_replace( "\r\n", "\n", $mail['headers'] ) );
			} else {
				$is_headers_array = true;
				$tmp_headers      = $mail['headers'];
			}


			// If it's actually got contents.
			if ( ! empty( $tmp_headers ) ) {
				// Iterate through the raw headers.
				foreach ( (array) $tmp_headers as $k => $header ) {
					if ( strpos( $header, ':' ) === false ) {
						continue;
					}


					// Explode them out.
					$tmp = explode( ':', trim( $header ), 2 );
					// Cleanup crew.
					$key     = strtolower( trim( $tmp[0] ) );
					$content = trim( $tmp[1] );


					switch ( $key ) {
						case 'from':
							$original_send['from'] = $key . ': ' . str_replace( [ '<', '>' ], '', $content );
							break;


						case 'cc':
						case 'bcc':
						case 'reply-to':
							$original_send[] = $key . ': ' . $content;
							unset( $tmp_headers[ $k ] );
							break;


						default:
					}
				}
			}


			/**
			 * Update headers
			 */
			if ( ! $is_headers_array ) {
				$tmp_headers = implode( "\r\n", $tmp_headers );
			}

			$mail['headers'] = $tmp_headers;
		}


		/**
		 * Add the Original info to the top of the message
		 */
		if (
			! $original_send_already_set
			&& ! empty( $original_send )
		) {
			$original_send = '<h3>' . $original_sending . '</h3>' . implode( '<br />', $original_send ) . '<br />~~~~~~~~~~<br /><br />';

			if ( isset( $mail['message'] ) ) {
				$mail['message'] = $original_send . $mail['message'];
			}

			if ( isset( $mail['body'] ) ) {
				$mail['body'] = $original_send . $mail['body'];
			}
		}


		return $mail;
	}
}
