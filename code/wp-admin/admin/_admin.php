<?php
/** @noinspection GrazieInspection */
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2016.12.08
 */
class lct_wp_admin_admin_admin
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2016.12.08
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
	 * @since    7.47
	 * @verified 2017.02.23
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
		add_filter( 'image_send_to_editor', [ $this, 'remove_site_root' ] );

		add_filter( 'upload_dir', [ $this, 'varnish_update_upload_dir_urls' ] );

		add_filter( 'page_link', [ $this, 'varnish_update_page_link_url' ], 99, 3 );

		add_filter( '_wp_post_revision_fields', [ $this, 'wp_post_revision_fields' ], 10, 2 );

		add_filter( 'site_status_tests', [ $this, 'disable_site_status_tests' ] );

		add_filter( 'site_status_test_php_modules', [ $this, 'disable_optional_modules' ] );

		add_filter( 'wp_check_filetype_and_ext', [ $this, 'check_for_needed_filetype' ], 10, 4 );

		add_filter( 'lct/check_for_bad_youtubes/check_pages', [ $this, 'disable_warning_notifications' ] );
		add_filter( 'lct/check_for_bad_youtubes/check_posts', [ $this, 'disable_warning_notifications' ] );
		add_filter( 'lct/check_for_bad_youtubes/check_fusion', [ $this, 'disable_warning_notifications' ] );
		add_filter( 'lct/check_for_bad_iframes/check_pages', [ $this, 'disable_warning_notifications' ] );
		add_filter( 'lct/check_for_bad_iframes/check_posts', [ $this, 'disable_warning_notifications' ] );
		add_filter( 'lct/check_all_fusion_pages_for_bad_avada_assets', [ $this, 'disable_warning_notifications' ] );
		add_filter( 'lct/avada/check_for_bad_avada_assets/google_analytics', [ $this, 'disable_warning_notifications' ] );
		add_filter( 'lct/avada/check_for_bad_avada_assets/head_space', [ $this, 'disable_warning_notifications' ] );
		add_filter( 'lct/avada/check_for_bad_avada_assets/custom_css', [ $this, 'disable_warning_notifications' ] );


		if ( lct_wp_admin_non_ajax() ) {
			/**
			 * actions
			 */
			add_action( 'load-post.php', [ $this, 'page_load_post' ] );
			add_action( 'load-post-new.php', [ $this, 'page_load_post' ] );

			add_action( 'load-edit.php', [ $this, 'page_load_edit' ] );

			add_action( 'load-tools.php', [ $this, 'page_load_tools' ] );

			add_action( 'load-toplevel_page_gf_edit_forms', [ $this, 'page_load_gf' ] );

			add_action( 'load-lct-panel_page_lct_acf_op_main_dev', [ $this, 'page_load_acf' ] );

			add_action( 'current_screen', [ $this, 'page_load_acf_tools' ], 1 );
			add_action( 'load-custom-fields_page_acf-tools', [ $this, 'page_load_acf_tools' ] );
			add_action( 'load-custom-fields_page_acf-settings-tools', [ $this, 'page_load_acf_tools' ] ); //old ACF version of this page


			/**
			 * filters
			 */
			add_filter( 'user_contactmethods', [ $this, 'remove_contactmethods' ], 10, 2 );

			add_filter( 'post_row_actions', [ $this, 'add_post_id' ], 2, 2 );
			add_filter( 'media_row_actions', [ $this, 'add_post_id' ], 2, 2 );

			add_filter( 'page_row_actions', [ $this, 'add_page_id' ], 2, 2 );

			add_filter( 'content_save_pre', [ $this, 'replace_elan' ], 10 );

			add_filter( 'update_footer', [ $this, 'current_wp_version' ], 15 );

			add_filter( 'update_footer', [ $this, 'server_specs' ], 30 );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Only load on certain page(s)
	 *
	 * @since    2018.63
	 * @verified 2018.08.30
	 */
	function page_load_post()
	{
		/**
		 * All Pages
		 */
		add_action( 'add_meta_boxes', [ $this, 'remove_meta_boxes' ], 999999 );

		add_action( 'admin_enqueue_scripts', [ $this, 'sticky_admin_sidebar' ] );


		/**
		 * Conditional
		 */
		if ( current_action() === 'load-post.php' ) {
			$post_type = null;


			if ( isset( $_GET['post'] ) ) {
				$post_type = get_post_type( $_GET['post'] );
			} elseif ( isset( $_POST['post_type'] ) ) {
				$post_type = $_POST['post_type'];
			}


			if ( $post_type ) {
				add_filter( 'acf/location/screen', [ $this, 'register_screen' ], 10, 2 );


				switch ( $post_type ) {
					case 'acf-field-group':
						add_action( 'admin_notices', [ $this, 'check_for_field_issues' ] );

						add_action( 'acf/get_field_label', [ $this, 'get_field_label' ], 10, 2 );
						break;


					default:
				}
			}
		}
	}


	/**
	 * Only load on certain page(s)
	 *
	 * @since    2018.63
	 * @verified 2018.08.30
	 */
	function page_load_edit()
	{
		add_action( 'admin_notices', [ $this, 'check_for_bad_youtubes' ] );


		if ( isset( $_GET['post_type'] ) ) {
			switch ( $_GET['post_type'] ) {
				case 'acf-field-group':
					add_action( 'admin_notices', [ $this, 'check_for_field_issues' ] );
					break;


				default:
			}
		}
	}


	/**
	 * Only load on certain page(s)
	 *
	 * @since    2018.63
	 * @verified 2018.08.30
	 */
	function page_load_tools()
	{
		global $plugin_page;


		if (
			! $plugin_page
			|| ! in_array( $plugin_page, [ 'wp-sync-db', 'redirection.php' ] )
		) {
			add_action( 'admin_notices', [ $this, 'check_for_wrong_emails' ] );

			add_action( 'admin_notices', [ $this, 'check_for_bad_youtubes' ] );

			add_action( 'admin_notices', [ $this, 'check_for_cron_not_working' ] );

			add_action( 'admin_notices', [ $this, 'check_for_field_issues' ] );
		} elseif ( $plugin_page === 'redirection.php' ) {
			add_action( 'lct/always_shutdown_wp_admin', [ $this, 'after_redirection_apache_save' ] );
		}
	}


	/**
	 * Only load on certain page(s)
	 *
	 * @since    2018.63
	 * @verified 2018.08.30
	 */
	function page_load_gf()
	{
		add_action( 'admin_notices', [ $this, 'check_for_wrong_emails' ] );
	}


	/**
	 * Only load on certain page(s)
	 *
	 * @since    2018.63
	 * @verified 2018.08.30
	 */
	function page_load_acf()
	{
		add_action( 'admin_notices', [ $this, 'check_for_wrong_emails' ] );

		add_action( 'admin_notices', [ $this, 'check_for_bad_youtubes' ] );

		add_action( 'admin_notices', [ $this, 'check_for_field_issues' ] );
	}


	/**
	 * Only load on certain page(s)
	 *
	 * @since    2018.63
	 * @verified 2023.09.01
	 */
	function page_load_acf_tools()
	{
		// Bail early if not Field Groups admin page.
		if (
			! lct_plugin_active( 'acf' )
			|| ! acf_is_screen( [ 'acf-field-group', 'edit-acf-field-group', 'custom-fields_page_acf-tools', 'acf_page_acf-tools' ] )
		) {
			return;
		}


		lct_update_setting( 'acf_is_field_group_editing_page', true );


		lct_remove_filter_like( 'acf/prepare_field', 'prepare_field_pdf_display' );
		lct_remove_filter_like( 'acf/prepare_field', 'prepare_field_add_class_selector' );


		lct_update_later( 'allow_not_function', true, 'acf/load_field' );
		lct_update_later( 'allow_not_function', true, 'acf/prepare_field' );

		lct_update_later( 'allow_not_function', true, 'prepare_field_add_class_selector' );


		add_filter( 'acf/get_field_groups', [ $this, 'acf_settings_tools_title_mod' ], 11 );

		add_action( 'admin_notices', [ $this, 'check_for_field_with_empty_names' ] );
	}


	/**
	 * Set the org() arg
	 *
	 * @param $screen
	 *
	 * @unused   param $field_group
	 * @return mixed
	 * @since    7.17
	 * @verified 2019.04.01
	 */
	function register_screen( $screen )
	{
		if (
			( $post_type = get_post_type( $_GET['post'] ) )
			&& $post_type !== 'acf-field-group'
			&& ( $selector = lct_org() )
			&& ( $org = get_post_meta( $_GET['post'], $selector, true ) )
		) {
			$screen[ $selector ] = [ $org ];
		}


		return $screen;
	}


	/**
	 * Fix the RedirectMatch bug in the redirection plugin
	 *
	 * @since    5.28
	 * @verified 2018.08.30
	 */
	function after_redirection_apache_save()
	{
		remove_action( 'lct/always_shutdown_wp_admin', [ $this, 'after_redirection_apache_save' ] );


		$redirection_options = get_option( 'redirection_options' );

		if ( empty( $redirection_options['modules'][2]['location'] ) ) {
			return;
		}

		$htaccess_file_path = $redirection_options['modules'][2]['location'];

		if ( ! file_exists( $htaccess_file_path ) ) {
			return;
		}


		$htaccess_file = file_get_contents( $htaccess_file_path );


		preg_match_all( "#\# Created by Redirection(.*?)\# End of Redirection#s", $htaccess_file, $redirection_content );
		$redirection_content = $redirection_content[0][0];


		preg_match_all( "#<IfModule mod_rewrite.c>(.*?)</IfModule>#s", $redirection_content, $RewriteRule_lines );
		$RewriteRule_lines = explode( '~~', preg_replace( '/(\r\n|\n)+/', '~~', $RewriteRule_lines[1][0] ) );
		$RewriteRule_lines = array_values( array_filter( $RewriteRule_lines ) );


		foreach ( $RewriteRule_lines as $key => $RewriteRule_line ) {
			if ( strpos( $RewriteRule_line, 'RewriteRule' ) !== false ) {
				preg_match_all( "#\[R=(.*?),L]#s", $RewriteRule_line, $redirect_level );

				$RewriteRule_line_working = str_replace( [ 'RewriteRule ', $redirect_level[0][0] ], '', $RewriteRule_line );

				$RewriteRule_line = 'RedirectMatch ' . $redirect_level[1][0] . ' ' . trim( $RewriteRule_line_working );
			}

			$RewriteRule_lines[ $key ] = $RewriteRule_line;
		}


		$version = get_file_data( lct_path_plugin() . '/redirection.php', [ 'Version' => 'Version' ], 'plugin' );
		//$version = get_plugin_data( lct_path_plugin() . '/redirection.php' ); //This is slower

		$text[] = '# Created by Redirection';
		$text[] = '# ' . date( 'r' );
		$text[] = '# Redirection ' . trim( $version['Version'] ) . ' - https://urbangiraffe.com/plugins/redirection/';
		$text[] = '# modified by ' . zxzu( 'after_redirection_apache_save' );
		$text[] = '';

		// mod_rewrite section
		$text[] = '<IfModule mod_rewrite.c>';

		// Add redirects
		$text[] = implode( "\r\n", $RewriteRule_lines );

		// End of mod_rewrite
		$text[] = '</IfModule>';
		$text[] = '';

		// End of redirection section
		$text[] = '# End of Redirection';

		$text = implode( "\r\n", $text );

		file_put_contents( $htaccess_file_path, str_replace( $redirection_content, $text, $htaccess_file ) );
	}


	/**
	 * Sometimes we need some more information about a field when editing groups
	 *
	 * @param string $curr_label
	 * @param array  $field
	 *
	 * @return string
	 * @since        7.25
	 * @verified     2020.01.06
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function get_field_label( $curr_label, $field )
	{
		$label   = [ $curr_label ];
		$exclude = apply_filters( 'lct/acf/get_field_label/excluded_field_types', [ 'message' ] );


		if (
			$field['ID']
			&& ! in_array( $field['type'], $exclude )
			&& ( $tmp = 'lct_class_selector' )
			&& ! empty( $field[ $tmp ] )
		) {
			$class_selector = $field[ $tmp ];


			if (
				in_array( 'dompdf_left', $class_selector )
				|| in_array( 'dompdf_right', $class_selector )
				|| in_array( 'dompdf_inline_left', $class_selector )
			) {
				$label[] = '<br />';

				if ( in_array( 'dompdf_left', $class_selector ) ) {
					$label[] = lct_acf_get_class_selector_label( 'dompdf_left' ) . ':';
				} elseif ( in_array( 'dompdf_right', $class_selector ) ) {
					$label[] = lct_acf_get_class_selector_label( 'dompdf_right' ) . ':';
				} elseif ( in_array( 'dompdf_inline_left', $class_selector ) ) {
					$label[] = lct_acf_get_class_selector_label( 'dompdf_inline_left' ) . ':';
				}

				$label[] = $field['wrapper']['width'] . '%';
			}
		}


		return lct_return( $label, ' ' );
	}


	/**
	 * Check if a field name is used more than once
	 *
	 * @since    2017.82
	 * @verified 2023.08.31
	 */
	function check_for_field_issues()
	{
		if (
			! lct_get_setting( 'acf_is_field_group_main_page' )
			|| ! lct_plugin_active( 'acf' )
		) {
			return;
		}


		if ( ! afwp_REQUEST_arg( 'show_dupes' ) ) {
			lct_get_notice(
				sprintf(
					'<strong><a href="%s">Show Duplicate Field Report</a></strong>',
					add_query_arg( [ 'post_type' => 'acf-field-group', 'show_dupes' => 1 ], admin_url( 'edit.php' ) )
				),
				0
			);
		}


		if ( ! afwp_REQUEST_arg( 'show_dupes' ) ) {
			return;
		}


		/**
		 * Vars
		 */
		$show_clones = afwp_REQUEST_arg( 'show_clones' );
		$clone_count = 0;

		if (
			! function_exists( 'lct_instances' )
			|| empty( lct_instances()->acf_loaded )
			|| ! isset( lct_instances()->acf_loaded->references['map_name_key'] )
		) {
			return;
		}


		/**
		 * List clones
		 */
		if (
			! $show_clones
			&& function_exists( 'lct_instances' )
			&& ! empty( lct_instances()->acf_loaded )
			&& isset( lct_instances()->acf_loaded->references['clones'] )
			&& ( $clone_count = count( lct_instances()->acf_loaded->references['clones'] ) )
		) {
			lct_get_notice(
				sprintf(
					'<strong><a href="%s">Show %s Clones</a></strong>',
					add_query_arg( [ 'post_type' => 'acf-field-group', 'show_dupes' => 1, 'show_clones' => 1 ], admin_url( 'edit.php' ) ),
					$clone_count
				),
				0
			);
		} elseif ( ! afwp_REQUEST_arg( 'show_clone_details' ) ) {
			lct_get_notice(
				sprintf(
					'<strong><a href="%s">Show Clone Full Details</a></strong>',
					add_query_arg( [ 'post_type' => 'acf-field-group', 'show_dupes' => 1, 'show_clones' => 1, 'show_clone_details' => 1 ], admin_url( 'edit.php' ) ),
					$clone_count
				),
				0
			);
		}


		/**
		 * List the Clones
		 */
		if (
			$show_clones
			&& function_exists( 'lct_instances' )
			&& ! empty( lct_instances()->acf_loaded )
			&& isset( lct_instances()->acf_loaded->references['clones'] )
		) {
			foreach ( lct_instances()->acf_loaded->references['clones'] as $tmp_key => $arr ) {
				$field = null;
				if ( $tmp = afwp_acf_get_field_object( $tmp_key, false, false, false ) ) {
					$field = $tmp;
				}

				$clone_details = '';
				if ( afwp_REQUEST_arg( 'show_clone_details' ) ) {
					$clone_details = '<br /><pre>' . print_r( $field, true ) . '</pre>';
				}


				$field_label = 'Unknown Label';
				if ( ! empty( $field['label'] ) ) {
					$field_label = $field['label'];
				}
				$group_title      = 'Unknown Group Title';
				$group_key        = 'Unknown Group Key';
				$parent_group_obj = null;
				$field_group      = 'Unknown Group';
				$field_group_obj  = null;


				/**
				 * Set the parent
				 * Do this when this field is loaded from the database
				 */
				if (
					! empty( $field['parent'] )
					&& is_numeric( $field['parent'] )
				) {
					$field = lct_instances()->acf_loaded->load_field( $field );
				}


				if (
					acf_is_field_group_key( $field['parent'] )
					&& ( $field_group_obj = acf_get_field_group( $field['parent'] ) )
				) {
					$group_title = $field_group_obj['title'];
					$group_key   = $field['parent'];
				} elseif ( $tmp = afwp_acf_get_group_of_field( $field['key'] ) ) {
					$group_title     = $tmp['title'];
					$group_key       = $tmp['key'];
					$field_group_obj = $tmp;
				}


				if (
					! empty( $field['_clone'] )
					&& isset( $field['__key'] )
					&& ( $parent = acf_get_field( $field['_clone'] ) )
				) {
					if ( ! empty( $parent['_name'] ) ) {
						$parent_name = $parent['_name'];
					}
					if ( ! empty( $parent['parent'] ) ) {
						$parent_group     = $parent['parent'];
						$parent_group_obj = acf_get_field_group( $parent_group );
					}
					if ( ! empty( $parent['sub_fields'] ) ) {
						$parent_sub_field_count = count( $parent['sub_fields'] );
					}


					if ( ( $parent = acf_get_field( $field['__key'] ) ) ) {
						$field_group     = $parent['parent'];
						$field_group_obj = acf_get_field_group( $field_group );
					}
				}


				/**
				 * Clone Field Details
				 */
				$clone_fields = [];
				foreach ( $field['clone'] as $clone_field ) {
					if ( acf_is_field_group_key( $clone_field ) ) {
						$clone_group_obj = null;
						if ( $tmp = acf_get_field_group( $clone_field ) ) {
							$clone_group_obj = $tmp;
						}

						$clone_fields[ $clone_group_obj['key'] ] = $clone_group_obj['title'] . ' ' . afwp_acf_json_encode( $clone_group_obj['location'] );


						continue;
					}


					if ( $clone_field_obj = afwp_acf_get_field_object( $clone_field, false, false, false ) ) {
						$clone_group_obj = [
							'location' => 'Unknown location',
						];
						if ( $tmp = afwp_acf_get_group_of_field( $clone_field ) ) {
							$clone_group_obj = $tmp;
						}


						$clone_fields[ $clone_field_obj['key'] ] = $clone_field_obj['name'] . ' ' . $clone_field_obj['parent'] . ' ' . afwp_acf_json_encode( $clone_group_obj['location'] );
					}
				}


				lct_get_notice(
					sprintf(
						'Clone Field Details: <strong>%s :: %s</strong><br />Clone Wrapper of Field:<br />%s :: %s (%s--%s)<br />%s<br />Real Field(s):<br />%s :: sub_field count %s%s',
						$field_label,
						$field['type'],
						$field['_name'],
						$field['key'],
						$group_title,
						$group_key,
						afwp_acf_json_encode( $field_group_obj['location'] ),
						afwp_acf_json_encode( $clone_fields ),
						count( $clone_fields ),
						$clone_details
					)
				);
			}
		}


		/**
		 * List the Dupe Names
		 */
		if (
			function_exists( 'lct_instances' )
			&& ! empty( lct_instances()->acf_loaded )
			&& isset( lct_instances()->acf_loaded->references['dupe_names'] )
		) {
			$dupe_names = [];

			foreach ( lct_instances()->acf_loaded->references['dupe_names'] as $tmp_key => $field_name ) {
				$field = null;
				if ( $tmp = afwp_acf_get_field_object( $tmp_key, false, false, false ) ) {
					$field = $tmp;


					if ( ( apply_filters( 'lct/check_for_field_issues/duplicate_override', null, $field ) ) !== null ) {
						continue;
					}
				}
				$dupe_names[ $field_name ][ $tmp_key ] = $field;
			}


			foreach ( $dupe_names as $fields ) {
				if (
					empty( $fields )
					|| ! ( $first_key = array_key_first( $fields ) )
					|| empty( $fields[ $first_key ]['parent'] )
				) {
					lct_get_notice( 'Unknown Error: ' . print_r( $fields, true ), - 1 );


					continue;
				}


				$notice   = [];
				$notice[] = sprintf(
					'<strong>Field Name Duplicate: %s</strong>',
					$fields[ $first_key ]['_name']
				);


				foreach ( $fields as $field ) {
					$field_label = 'Unknown Label';
					if ( ! empty( $field['label'] ) ) {
						$field_label = $field['label'];
					}
					$group_title = 'Unknown Group Title';
					$group_key   = 'Unknown Group Key';


					/**
					 * Set the parent
					 * Do this when this field is loaded from the database
					 */
					if (
						! empty( $field['parent'] )
						&& is_numeric( $field['parent'] )
					) {
						$field = lct_instances()->acf_loaded->load_field( $field );
					}


					if (
						acf_is_field_group_key( $field['parent'] )
						&& ( $field_group = acf_get_field_group( $field['parent'] ) )
					) {
						$group_title = $field_group['title'];
						$group_key   = $field['parent'];
					} elseif ( $tmp = afwp_acf_get_group_of_field( $field['key'] ) ) {
						$group_title = $tmp['title'];
						$group_key   = $tmp['key'];
						$field_group = $tmp;
					}


					$dupe = sprintf(
						'<strong>%s :: %s</strong> (<em>%s :: %s</em>) -- %s',
						$field['key'],
						$field_label,
						$group_title,
						$group_key,
						afwp_acf_json_encode( $field_group['location'] )
					);


					if (
						acf_is_field_group_key( $field['parent'] )
						&& ! empty( $field_group )
					) {
						if ( ! empty( $field_group['ID'] ) ) {
							$dupe = sprintf(
								'<a href="%s" target="_blank">%s</a>',
								get_edit_post_link( $field_group['ID'] ),
								$dupe
							);
						} else {
							$dupe = sprintf(
								'[LOCAL ACF Field GROUP] %s',
								$dupe
							);
						}
					}


					$notice[] = $dupe;
				}


				lct_get_notice( lct_return( $notice, '<br />' ), - 1 );
			}
		}
	}


	/**
	 * Check if a field name is set when it is a field that should have an empty name
	 *
	 * @since    2017.83
	 * @verified 2019.07.16
	 */
	function check_for_field_with_empty_names()
	{
		$no_name_field_types = [
			'message',
			'tab',
			'afwp_dev_report',
			'lct_column_end',
			'lct_column_start',
			'lct_dev_report',
			'lct_dompdf_clear',
			'lct_new_page',
			'lct_section_header',
			'lct_send_password',
		];
		$no_name_field_types = apply_filters( 'lct/check_for_field_issues/no_name_field_types', $no_name_field_types );


		$args_field = [
			'where_relation' => 'AND',
			'where_field'    => [ 'type', 'name' ],
			'where_operator' => [ 'IN', '!==' ],
			'where_value'    => [ $no_name_field_types, '' ],
		];
		$fields     = lct_acf_get_field_groups_fields( [], $args_field );


		if ( ! empty( $fields ) ) {
			foreach ( $fields as $field ) {
				/**
				 * Skip field types that do have field names
				 */
				if (
					! empty( $field['name'] )
					&& ! empty( $field['ID'] )
					&& is_int( $field['ID'] ) //Only if a DB field, no locals
				) {
					$post_data = [
						'ID'           => $field['ID'],
						'post_excerpt' => '',
					];
					wp_update_post( $post_data );
				}
			}


			/**
			 * Let 3rd parties do some checks
			 *
			 * @date     0.0
			 * @since    0.0
			 * @verified 2021.08.30
			 */
			do_action( 'lct/check_for_field_with_empty_names/loop_done', $fields );
		}
	}


	/**
	 * We need to know what we are exporting. The title is just not enough info.
	 *
	 * @param array $field_groups
	 *
	 * @return array
	 * @since    4.2.2.26
	 * @verified 2018.08.30
	 */
	function acf_settings_tools_title_mod( $field_groups )
	{
		foreach ( $field_groups as $key => $field_group ) {
			$location = $field_group;
			$location = acf_extract_var( $location, 'location' );
			$location = $location[0][0];

			$title_addition = $location['param'] . '__' . $location['value'] . '___' . $field_group['title'];

			$fnr       = lct_create_find_and_replace_arrays(
				[
					' ' => '_',
					'-' => '_',
					'=' => '',
				]
			);
			$file_name = str_replace( $fnr['find'], $fnr['replace'], sanitize_title( $title_addition ) ) . '.json';


			$field_groups[ $key ]['title'] .= sprintf( ' &mdash; %s &mdash; Filename: %s', $field_group['key'], $file_name );
		}


		return $field_groups;
	}


	/**
	 * Load the admin Javascript
	 *
	 * @since    7.36
	 * @verified 2018.08.30
	 */
	function sticky_admin_sidebar()
	{
		lct_admin_enqueue_script( zxzu( 'sticky_admin_sidebar' ), lct_get_root_url( 'assets/wp-admin/js/sticky_admin_sidebar.min.js' ) );
	}


	/**
	 * Remove metaboxes that we want to use acf to set
	 *
	 * @since    7.31
	 * @verified 2022.04.04
	 */
	function remove_meta_boxes()
	{
		if (
			! empty( $_GET['post'] )
			|| ! empty( $_GET['post_type'] )
		) {
			/**
			 * Array of taxonomy meta_boxes we have set to be removed
			 */
			$taxonomies = lct_acf_get_option( 'remove_meta_boxes_taxonomies' );


			if ( ! empty( $taxonomies ) ) {
				if ( ! empty( $_GET['post'] ) ) {
					$post_type = get_post_type( $_GET['post'] );
				} else {
					$post_type = $_GET['post_type'];
				}


				foreach ( $taxonomies as $taxonomy ) {
					$taxonomy_obj = get_taxonomy( $taxonomy );


					if (
						lct_is_wp_error( $taxonomy_obj )
						|| empty( $taxonomy_obj->object_type )
						|| ! in_array( $post_type, $taxonomy_obj->object_type )
					) {
						continue;
					}


					if ( empty( $taxonomy_obj->hierarchical ) ) {
						remove_meta_box( 'tagsdiv-' . $taxonomy, $post_type, 'side' );
						remove_meta_box( 'tagsdiv-' . $taxonomy, $post_type, 'normal' );
					} else {
						remove_meta_box( $taxonomy . 'div', $post_type, 'normal' );
						remove_meta_box( $taxonomy . 'div', $post_type, 'side' );
					}
				}
			}


			/**
			 * Avada Meta Box
			 */
			$post_types = lct_acf_get_option( 'remove_avada_options_post_types' );


			if ( ! empty( $post_types ) ) {
				foreach ( $post_types as $post_type ) {
					switch ( $post_type ) {
						case 'product':
							$id = 'pyre_woocommerce_options';
							break;


						case 'tribe_events':
							$id = 'pyre_events_calendar_options';
							break;


						default:
							$id = 'pyre_post_options';
					}


					remove_meta_box( $id, $post_type, 'advanced' );
				}
			}


			/**
			 * Featured Image Meta Box
			 */
			$post_types = lct_acf_get_option( 'remove_featured_image_post_types' );


			if ( ! empty( $post_types ) ) {
				foreach ( $post_types as $post_type ) {
					remove_meta_box( 'pyre_post_options', $post_type, 'normal' );
					remove_meta_box( 'postimagediv', $post_type, 'side' );
				}
			}
		}
	}


	/**
	 * Remove the scheme and host of media URLs
	 *
	 * @param $html
	 *
	 * @return mixed
	 * @since    4.2.2.10
	 * @verified 2016.11.03
	 */
	function remove_site_root( $html )
	{
		return lct_remove_site_root( $html );
	}


	/**
	 * These things are annoying
	 *
	 * @param $contactmethods
	 *
	 * @return mixed
	 * @since    2017.93
	 * @verified 2018.08.23
	 */
	function remove_contactmethods( $contactmethods )
	{
		if ( lct_get_later( 'allow_function', 'remove_contactmethods' ) ) {
			unset( $contactmethods['aim'] );
			unset( $contactmethods['yim'] );
			unset( $contactmethods['jabber'] );
			unset( $contactmethods['author_facebook'] );
			unset( $contactmethods['author_twitter'] );
			unset( $contactmethods['author_linkedin'] );
			unset( $contactmethods['author_dribble'] );
			unset( $contactmethods['author_gplus'] );
			unset( $contactmethods['author_custom'] );
			unset( $contactmethods['author_email'] );
			unset( $contactmethods['author_whatsapp'] );
		}


		return $contactmethods;
	}


	/**
	 * Set row actions for all post_types.
	 *
	 * @param array   $actions
	 * @param WP_Post $post
	 *
	 * @return array
	 * @since        5.37
	 * @verified     2017.02.11
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function add_post_id( $actions, $post )
	{
		return array_merge( [ 'id' => 'ID: <a href="#' . $post->ID . '">' . $post->ID . '</a>' ], $actions );
	}


	/**
	 * Set row actions for all pages.
	 *
	 * @param array   $actions
	 * @param WP_Post $post
	 *
	 * @return array
	 * @since        5.37
	 * @verified     2016.11.03
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function add_page_id( $actions, $post )
	{
		return array_merge( [ 'id' => 'ID: <a href="#' . $post->ID . '">' . $post->ID . '</a>' ], $actions );
	}


	/**
	 * Replace Elan with elan
	 *
	 * @param $content
	 *
	 * @return mixed
	 * @since    2017.52
	 * @verified 2017.07.27
	 */
	function replace_elan( $content )
	{
		if (
			lct_plugin_active( 'acf' )
			&& lct_acf_get_option_raw( 'clientzz' ) === '00pimg'
			&& $content
		) {
			$elan_correct = 'élan';
			$elan_accent  = [
				'Élan',
				'&Eacute;lan',
			];
			$elan         = [
				'elan',
			];


			if ( strpos_array( $content, $elan_accent ) ) {
				$content = str_replace( $elan_accent[0], $elan_correct, $content );
			} elseif ( stripos_array( $content, $elan ) ) {
				$content = str_ireplace( $elan, $elan_correct, $content );


				/**
				 * Fixes, the words like Loveland
				 */
				$alphabet = [ 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z' ];

				foreach ( $alphabet as $letter ) {
					$content = str_replace( $elan_correct . $letter, 'elan' . $letter, $content );
				}
			}
		}


		return $content;
	}


	/**
	 * Check if we have default emails set on a live site.
	 *
	 * @since        2017.61
	 * @verified     2018.08.30
	 * @noinspection PhpRedundantOptionalArgumentInspection
	 */
	function check_for_wrong_emails()
	{
		if (
			! lct_plugin_active( 'acf' )
			|| ! current_user_can( 'administrator' )
			|| lct_is_dev_or_sb()
		) {
			return;
		}


		$bad_emails   = lct_acf_get_dev_emails();
		$bad_emails[] = '{admin_email}';
		$bad_emails   = apply_filters( 'lct/check_for_wrong_emails/bad_emails', $bad_emails );


		if (
			( $admin_email = get_option( 'admin_email' ) )
			&& in_array( $admin_email, $bad_emails )
		) {
			lct_get_notice( sprintf( 'You have the <strong>admin_email</strong> set to: <strong>%s</strong>, please set to client\'s email address.', $admin_email ), - 1 );
		}


		if (
			( $blogname = get_option( 'blogname' ) )
			&& $blogname === 'MAIN'
		) {
			lct_get_notice( sprintf( 'You have the <strong>blogname</strong> set to: <strong>%s</strong>, please set to client\'s company name.', $blogname ), - 1 );
		}


		if (
			lct_plugin_active( 'wf' )
			&& ( $admin_email = wfConfig::get( 'alertEmails' ) )
			&& in_array( $admin_email, $bad_emails )
		) {
			lct_get_notice( sprintf( 'You have the <strong>Wordfence Email</strong> set to: <strong>%s</strong>, please set to client\'s email address.', $admin_email ), - 1 );
		}


		if ( lct_plugin_active( 'gforms' ) ) {
			$gf_forms = RGFormsModel::get_forms( null, 'title' );


			if ( $gf_forms ) {
				foreach ( $gf_forms as $gf_form ) {
					$gf_form_meta = RGFormsModel::get_form_meta( $gf_form->id );


					if ( $gf_form_meta ) {
						$fields = [];


						foreach ( $gf_form_meta['fields'] as $field ) {
							$label = $field->label;


							if ( ! empty( $field->adminLabel ) ) {
								$label = $field->adminLabel;
							}


							$fields[] = sprintf( '{%s:%s}', $label, $field->id );
						}


						foreach ( $gf_form_meta['notifications'] as $notification ) {
							if (
								! empty( $notification['toType'] )
								&& $notification['toType'] == 'email'
								&& (
									! $notification['to']
									|| in_array( $notification['to'], $bad_emails )
								)
							) {
								lct_get_notice( sprintf( 'You have a Gravity Form (<strong>%s</strong>) email set to: <strong>%s</strong>, please set to client\'s email address.', $gf_form->title, $notification['to'] ), - 1 );
							}


							if (
								! empty( $notification['replyTo'] )
								&& strpos( $notification['replyTo'], '{' ) === 0
								&& strpos_array( $notification['replyTo'], $fields ) === false
							) {
								lct_get_notice( sprintf( 'You have a Gravity Form (<strong>%s</strong>)  with replyTo set incorrectly: <strong>%s</strong>, please check it.', $gf_form->title, $notification['replyTo'] ), - 1 );
							}
						}
					}
				}
			}
		}
	}


	/**
	 * Check if we have bad_youtubes
	 *
	 * @since    2017.61
	 * @verified 2020.11.30
	 */
	function check_for_bad_youtubes()
	{
		if (
			! lct_theme_active( 'Avada' )
			|| ! current_user_can( 'administrator' )
		) {
			return;
		}


		global $wpdb;


		/**
		 * Get all the posts that contain something we don't like
		 */
		$bad_posts = $wpdb->get_results(
			"SELECT `ID`, `post_content`, `post_type`
			FROM `{$wpdb->posts}`
			WHERE `post_type` IN ( 'page', 'post' )
			AND `post_status` = 'publish'
			AND ( `post_content` LIKE '%fusion_youtube%' OR `post_content` LIKE '%<iframe%' OR `post_content` LIKE '%[embed%' OR `post_content` LIKE '%{embed%' )"
		);


		if ( ! empty( $bad_posts ) ) {
			foreach ( $bad_posts as $post ) {
				/**
				 * [embed] is present in the content
				 */
				if (
					(
						strpos( $post->post_content, '[embed' ) !== false
						|| strpos( $post->post_content, '{embed' ) !== false
					)
					&& (
						(
							$post->post_type === 'page'
							&& apply_filters( 'lct/check_for_bad_youtubes/check_pages', true, $post )
						)
						|| (
							$post->post_type === 'post'
							&& apply_filters( 'lct/check_for_bad_youtubes/check_posts', true, $post )
						)
					)
				) {
					lct_get_notice( sprintf( 'Update [embed] videos immediately on <strong><a href="%s" target="_blank">%s</a></strong> to use the [lct_lazy_youtube] shortcode.', get_edit_post_link( $post->ID ), get_the_title( $post->ID ) ), - 1 );


					/**
					 * [fusion_youtube] is present in the content
					 */
				} elseif (
					strpos( $post->post_content, 'fusion_youtube' ) !== false
					&& apply_filters( 'lct/check_for_bad_youtubes/check_fusion', true, $post )
				) {
					lct_get_notice( sprintf( 'Update videos immediately on <strong><a href="%s" target="_blank">%s</a></strong> to use the [lct_lazy_youtube] shortcode.', get_edit_post_link( $post->ID ), get_the_title( $post->ID ) ), - 1 );


					/**
					 * <iframe> is present in the content
					 */
				} elseif (
					strpos( $post->post_content, '<iframe' ) !== false
					&& (
						(
							$post->post_type === 'page'
							&& apply_filters( 'lct/check_for_bad_iframes/check_pages', true, $post )
						)
						|| (
							$post->post_type === 'post'
							&& apply_filters( 'lct/check_for_bad_iframes/check_posts', true, $post )
						)
					)
				) {
					lct_get_notice( sprintf( 'Check iframes on <strong><a href="%s" target="_blank">%s</a></strong> maybe you can use a shortcode instead.', get_edit_post_link( $post->ID ), get_the_title( $post->ID ) ), - 1 );
				}
			}
		}
	}


	/**
	 * Modify update_footer to include the live WP version and the current WP version
	 *
	 * @param $update
	 *
	 * @return string
	 * @since    2017.81
	 * @verified 2017.09.25
	 */
	function current_wp_version( $update )
	{
		if ( current_user_can( 'update_core' ) ) {
			$cur = get_preferred_from_update_core();
			if ( ! is_object( $cur ) ) {
				$cur = new stdClass;
			}

			if ( ! isset( $cur->current ) ) {
				$cur->current = '';
			}

			$live_version = get_bloginfo( 'version' );


			if ( version_compare( $live_version, $cur->current, '<' ) ) {
				if ( strpos( $update, 'update-core.php' ) !== false ) {
					$update = ' (' . $update . ')';
				}


				$update = sprintf( esc_attr__( 'WordPress %s is currently running%s', 'TD_LCT' ), $live_version, $update );
			}
		}


		return $update;
	}


	/**
	 * Modify update_footer to include the server software, PHP version & MySQL version
	 *
	 * @param $update
	 *
	 * @return string
	 * @since    2017.81
	 * @verified 2018.03.15
	 */
	function server_specs( $update )
	{
		if ( current_user_can( 'update_core' ) ) {
			global $wpdb;


			if ( $version = lct_get_setting( 'version' ) ) {
				$update .= sprintf( esc_attr__( ' | %s %s', 'TD_LCT' ), zxzb(), $version );
			}


			if ( $version = lct_current_theme_version() ) {
				if (
					( $child_version = lct_active_theme_version() )
					&& $child_version != $version
				) {
					$child = ' (child: ' . $child_version . ')';
				} else {
					$child = '';
				}


				$update .= sprintf( esc_attr__( ' | %s v%s%s', 'TD_LCT' ), lct_get_setting( 'theme_current' ), $version, $child );
			}


			if ( ! empty( $_SERVER['SERVER_SOFTWARE'] ) ) {
				$update .= sprintf( esc_attr__( ' | %s', 'TD_LCT' ), $_SERVER['SERVER_SOFTWARE'] );
			}


			if ( function_exists( 'phpversion' ) ) {
				$update .= sprintf( esc_attr__( ' | PHP %s', 'TD_LCT' ), phpversion() );
			}


			if ( $MySQL = $wpdb->get_var( 'SELECT VERSION();' ) ) {
				$update .= sprintf( esc_attr__( ' | MySQL %s', 'TD_LCT' ), $MySQL );
			}
		}


		return $update;
	}


	/**
	 * Update the URL with the LIVE URL
	 *
	 * @param $url
	 *
	 * @return mixed
	 * @since    2017.95
	 * @verified 2018.02.14
	 */
	function varnish_update_url_to_live( $url )
	{
		if (
			defined( 'LCT_WP_VARNISH_URL' )
			&& defined( 'LCT_WP_LIVE_URL' )
			&& LCT_WP_VARNISH_URL
			&& LCT_WP_LIVE_URL
		) {
			$url = str_replace( LCT_WP_VARNISH_URL, LCT_WP_LIVE_URL, $url );
		}


		return $url;
	}


	/**
	 * Update upload_dir URLs that are incorrect because of our Varnish Bypass
	 *
	 * @param $uploads
	 *
	 * @return mixed
	 * @since    2017.95
	 * @verified 2018.02.14
	 */
	function varnish_update_upload_dir_urls( $uploads )
	{
		if (
			defined( 'LCT_ENABLE_VARNISH_CACHE' )
			&& defined( 'LCT_IS_VARNISH_BYPASS' )
			&& LCT_ENABLE_VARNISH_CACHE
			&& LCT_IS_VARNISH_BYPASS
		) {
			if ( isset( $uploads['baseurl'] ) ) {
				$uploads['baseurl'] = $this->varnish_update_url_to_live( $uploads['baseurl'] );
			}


			if ( isset( $uploads['url'] ) ) {
				$uploads['url'] = $this->varnish_update_url_to_live( $uploads['url'] );
			}
		}


		return $uploads;
	}


	/**
	 * Update page_link URL that are incorrect because of our Varnish Bypass
	 *
	 * @param $link
	 *
	 * @unused   param $post_id
	 * @unused   param $sample
	 * @return mixed
	 * @since    2017.95
	 * @verified 2017.11.30
	 */
	function varnish_update_page_link_url( $link )
	{
		return $this->varnish_update_url_to_live( $link );
	}


	/**
	 * We need to add this so that the guid is correct because of our Varnish Bypass
	 *
	 * @param $fields
	 *
	 * @unused   param $post
	 * @return mixed
	 * @since    2017.95
	 * @verified 2017.11.30
	 */
	function wp_post_revision_fields( $fields )
	{
		$fields['guid'] = 'guid';


		return $fields;
	}


	/**
	 * Check to see if the cron is not do its job
	 *
	 * @since    2018.22
	 * @verified 2018.03.22
	 */
	function check_for_cron_not_working()
	{
		if (
			! lct_plugin_active( 'acf' )
			|| ! current_user_can( 'administrator' )
		) {
			return;
		}


		$cron_issues = lct_get_option( 'per_version_cron_issues', [] );


		if ( ! empty( $cron_issues['not_working'] ) ) {
			lct_get_notice( 'Cron is not running properly. Go check it out', - 1 );
		}
	}


	/**
	 * Sometimes we don't want to run certain tests
	 *
	 * @param array $tests
	 *
	 * @return array
	 * @since        2019.19
	 * @verified     2020.06.24
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function disable_site_status_tests( $tests )
	{
		if ( empty( $tests ) ) {
			return $tests;
		}


		$ignore_test_types = [
			'async'  => [
				'background_updates' => [ 'disable_on_dev' => true ],
				'loopback_requests'  => [ 'disable_on_dev' => true ],
			],
			'direct' => [
				'php_version'       => [],
				'plugin_version'    => [],
				'rest_availability' => [ 'disable_on_dev' => true ],
				'scheduled_events'  => [ 'disable_on_dev' => true ],
			],
		];


		foreach ( $ignore_test_types as $test_type => $ignore_tests ) {
			foreach ( $ignore_tests as $test => $details ) {
				$check_disable_on_dev = false;
				$continue             = false;


				switch ( $test ) {
					case 'php_version':
						if (
							! ( $php_version_data = wp_check_php_version() )
							|| empty( $php_version_data['is_acceptable'] )
							|| empty( $php_version_data['is_secure'] )
							|| empty( $php_version_data['is_supported'] )
							|| empty( $php_version_data['recommended_version'] )
							|| version_compare( $php_version_data['recommended_version'], 7.4, '>=' )
							|| version_compare( $php_version_data['recommended_version'], 7.3, '<' )
							|| version_compare( phpversion(), 7.0, '<' )
						) {
							$continue = true;
						}
						break;


					case 'scheduled_events':
						if (
							! defined( 'DISABLE_WP_CRON' )
							|| DISABLE_WP_CRON !== true
						) {
							$continue = true;
						}
						break;


					default:
						$check_disable_on_dev = true;
				}


				if ( $continue ) {
					continue;
				}


				if (
					$check_disable_on_dev
					&& ! empty( $details['disable_on_dev'] )
					&& ! lct_is_dev()
				) {
					continue;
				}


				if ( key_exists( $test, $tests[ $test_type ] ) ) {
					unset( $tests[ $test_type ][ $test ] );
				}
			}
		}


		return $tests;
	}


	/**
	 * Sometimes we don't want to check optional php modules
	 *
	 * @param array $modules
	 *
	 * @return array
	 * @since        2019.19
	 * @verified     2020.09.11
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function disable_optional_modules( $modules )
	{
		if ( empty( $modules ) ) {
			return $modules;
		}


		//$ignore_modules = [
		//'imagick',
		//];


		/*
		foreach ( $ignore_modules as $module ) {
			if (
				key_exists( $module, $modules ) &&				(
					! isset( $modules[ $module ]['required'] ) ||					$modules[ $module ]['required'] === false
				)
			) {
				unset( $modules[ $module ] );
			}
		}
		*/


		return $modules;
	}


	/**
	 * Forces the correct file types, if the WP checks is incorrect
	 *
	 * @param array  $wp_check_filetype_and_ext File data array containing 'ext', 'type', and 'proper_filename' keys.
	 * @param string $file                      Full path to the file.
	 * @param string $filename                  The name of the file (may differ from $file due to $file being in a tmp directory).
	 * @param array  $mimes                     Key is the file extension with value as the mime type.
	 *
	 * @return array
	 * @since        2020.3
	 * @verified     2020.01.17
	 * @noinspection PhpUnusedParameterInspection
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function check_for_needed_filetype( $wp_check_filetype_and_ext, $file, $filename, $mimes )
	{
		if (
			! empty( $wp_check_filetype_and_ext['ext'] )
			&& ! empty( $wp_check_filetype_and_ext['type'] )
		) {
			return $wp_check_filetype_and_ext;
		}


		$wp_filetype = wp_check_filetype( $filename, $mimes );


		if ( $wp_filetype['ext'] !== 'xcf' ) {
			return $wp_check_filetype_and_ext;
		}


		return array_merge( $wp_check_filetype_and_ext, $wp_filetype );
	}


	/**
	 * Global setting for content notifications
	 *
	 * @param bool $do_the_check
	 *
	 * @return bool
	 * @date     2022.03.07
	 * @since    2022.2
	 * @verified 2022.03.07
	 */
	function disable_warning_notifications( $do_the_check )
	{
		if ( lct_acf_get_option( 'disable_warning_notifications' ) ) {
			return false;
		} elseif ( lct_acf_get_option( 'disable_warning_notifications' ) === false ) {
			return true;
		}


		return $do_the_check;
	}
}
