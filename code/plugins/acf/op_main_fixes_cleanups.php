<?php
/** @noinspection PhpMissingFieldTypeInspection */
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.02.20
 */
class lct_acf_op_main_fixes_cleanups
{
	public $siteurl;
	public $siteurl_host_non_www;
	public $clientzz;
	public $post_post_content;
	public $post_info;
	public $post_content_fnr;
	public $post_types;
	public $post_types_excluded;


	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.02.20
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
	 * @since    2017.13
	 * @verified 2017.02.20
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
		add_filter( 'content_save_pre', [ $this, 'save_post_cleanup_guid_post_content' ], 11 );
		add_filter( 'content_save_pre', [ $this, 'save_post_cleanup_guid_existing_link_sc_check' ], 12 );
		add_filter( 'content_save_pre', [ $this, 'save_post_cleanup_guid_link_cleanup' ], 13 );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		if ( lct_wp_admin_non_ajax() ) {
			add_action( 'load-lct-panel_page_lct_acf_op_main_fixes_cleanups', [ $this, 'page_load_fixes_cleanups' ] );

			add_action( 'admin_menu', [ $this, 'old_useful_menu' ] );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Only load on certain page(s)
	 *
	 * @since    2018.63
	 * @verified 2018.08.30
	 */
	function page_load_fixes_cleanups()
	{
		add_filter( 'acf/load_field/type=oembed', [ $this, 'fixes_cleanups' ] ); //load_field is the best option
	}


	/**
	 * Prefix the string with the zxzu value
	 *
	 * @param string $str
	 *
	 * @return string
	 * @since    2017.27
	 * @verified 2022.01.06
	 */
	function acf( $str = '' )
	{
		return zxzu( 'acf_' ) . $str;
	}


	/**
	 * OLD: Register the old_useful_menu menus
	 *
	 * @since    5.40
	 * @verified 2016.10.19
	 */
	function old_useful_menu()
	{
		add_submenu_page(
			$this->acf( 'op_main' ),
			'Cleanup siteurl, guid, etc.', 'Cleanup siteurl, guid, etc.',
			'manage_options',
			zxzu( 'cleanup_guid' ), [ $this, zxzu( 'cleanup_guid' ) ]
		);

		add_submenu_page(
			$this->acf( 'op_main' ),
			'Close All Pings & Comments', 'Close All Pings & Comments',
			'manage_options',
			zxzu( 'close_all_pings_and_comments' ), [ $this, zxzu( 'close_all_pings_and_comments' ) ]
		);

		add_submenu_page(
			$this->acf( 'op_main' ),
			'Cleanup Uploads Folder', 'Cleanup Uploads Folder',
			'manage_options',
			zxzu( 'cleanup_uploads' ), [ $this, zxzu( 'cleanup_uploads' ) ]
		);

		add_submenu_page(
			$this->acf( 'op_main' ),
			'Repair ACF User Meta Data', 'Repair ACF User Meta Data',
			'manage_options',
			zxzu( 'repair_acf_usermeta' ), [ $this, zxzu( 'repair_acf_usermeta' ) ]
		);

		add_submenu_page(
			$this->acf( 'op_main' ),
			'Repair ACF Post Meta Data', 'Repair ACF Post Meta Data',
			'manage_options',
			zxzu( 'repair_acf_postmeta' ), [ $this, zxzu( 'repair_acf_postmeta' ) ]
		);

		add_submenu_page(
			$this->acf( 'op_main' ),
			'Repair ACF Term Meta Data', 'Repair ACF Term Meta Data',
			'manage_options',
			zxzu( 'repair_acf_termmeta' ), [ $this, zxzu( 'repair_acf_termmeta' ) ]
		);

		add_submenu_page(
			$this->acf( 'op_main' ),
			'Repair Term Counts', 'Repair Term Counts',
			'manage_options',
			zxzu( 'repair_term_counts' ), [ $this, zxzu( 'repair_term_counts' ) ]
		);

		add_submenu_page(
			$this->acf( 'op_main' ),
			'Delete Empty Terms', 'Delete Empty Terms',
			'manage_options',
			zxzu( 'delete_empty_terms' ), [ $this, zxzu( 'delete_empty_terms' ) ]
		);
	}


	/**
	 * content_save_pre filter that updates the guid of a page or post you are saving
	 *
	 * @param $content
	 *
	 * @return mixed
	 * @since    5.40
	 * @verified 2016.09.29
	 */
	function save_post_cleanup_guid_post_content( $content )
	{
		global $post;


		if (
			! lct_is_wp_error( $post )
			&& ! in_array( $post->post_type, [ 'acf-field-group', 'acf-field' ] )
		) {
			$this->post_post_content = $content;

			$this->cleanup_guid_post_content( $post, true );

			$content = $this->post_post_content;
		}


		return $content;
	}


	/**
	 * content_save_pre filter that updates the internal links inside the post_content of a page or post you are saving
	 *
	 * @param $content
	 *
	 * @return mixed
	 * @since    5.40
	 * @verified 2016.09.29
	 */
	function save_post_cleanup_guid_existing_link_sc_check( $content )
	{
		global $post;


		if (
			! lct_is_wp_error( $post )
			&& ! in_array( $post->post_type, [ 'acf-field-group', 'acf-field' ] )
		) {
			$this->post_post_content = $content;

			$this->cleanup_guid_existing_link_sc_check( $post, true );

			$content = $this->post_post_content;
		}


		return $content;
	}


	/**
	 * content_save_pre filter that updates the internal links inside the post_content of a page or post you are saving
	 *
	 * @param $content
	 *
	 * @return mixed
	 * @since    5.40
	 * @verified 2016.11.18
	 */
	function save_post_cleanup_guid_link_cleanup( $content )
	{
		global $post;


		if (
			! lct_is_wp_error( $post )
			&& ! in_array( $post->post_type, [ 'acf-field-group', 'acf-field' ] )
		) {
			$this->post_post_content = $content;

			$this->cleanup_guid_link_cleanup( $post, true );

			$content = $this->post_post_content;
		}


		return $content;
	}


	/**
	 * populate the fixes_and_cleanups stuff
	 *
	 * @param $field
	 *
	 * @return mixed
	 * @since    5.40
	 * @verified 2018.08.30
	 */
	function fixes_cleanups( $field )
	{
		unset( $field['width'] );
		unset( $field['height'] );


		$fixes_and_cleanup = str_replace( zxzd( zxzu( 'fix' ) ), '', $field['name'] );


		$field['type']     = 'message';
		$field['message']  = lct_get_fixes_cleanups_message( $fixes_and_cleanup, $field['parent'] );
		$field['esc_html'] = 0;


		return $field;
	}


	/**
	 * We need to call these things in multiple places, so here you go
	 *
	 * @since    5.40
	 * @verified 2016.09.29
	 */
	function startup_cleanup_guid()
	{
		global $wpdb;

		$this->post_types          = get_post_types();
		$this->post_types_excluded = $this->post_types;

		unset( $this->post_types['revision'] );
		unset( $this->post_types_excluded['revision'] );

		$this->post_types          = apply_filters( 'lct_startup_cleanup_guid_post_types', $this->post_types );
		$this->post_types_excluded = apply_filters( 'lct_startup_cleanup_guid_post_types_excluded', $this->post_types_excluded );

		$this->siteurl              = parse_url( rtrim( esc_url( $wpdb->get_var( $wpdb->prepare( "SELECT `option_value` FROM {$wpdb->options} WHERE option_name = %s", 'home' ) ) ), '/' ) );
		$this->siteurl_host_non_www = str_replace( 'www.', '', $this->siteurl['host'] );
		$this->clientzz             = lct_acf_get_option_raw( 'clientzz' );
		$this->post_content_fnr     = $this->lct_get_post_content_fnr();
	}


	/**
	 * Clean up the siteurl, guid, etc. in the database
	 * old_useful_menu
	 * //TODO: cs - Add phone number - 05/19/2016 02:17 PM
	 *
	 * @since    5.40
	 * @verified 2022.01.06
	 */
	function lct_cleanup_guid()
	{
		global $wpdb;

		$this->post_info = [
			'guid',
			'redirection_items',
			'post_content',
			'existing_link_sc_check',
			'link_cleanup',
		];

		$this->startup_cleanup_guid();


		ksort( $this->post_types );
		ksort( $this->post_types_excluded );


		foreach ( $this->post_types as $post_type ) {
			$args  = [
				'posts_per_page'         => - 1,
				'post_type'              => $post_type,
				'post_status'            => 'any',
				'cache_results'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			];
			$posts = get_posts( $args );


			if ( ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					$post_guid               = $post->guid;
					$this->post_post_content = $post->post_content;


					//redirection_items --- redirection_items --- redirection_items --- redirection_items --- redirection_items
					$type                             = 'redirection_items';
					$update_success[ $type ]          = false;
					$redirection_items                = $wpdb->get_results( "SELECT `id`, `url`, `action_data` FROM {$wpdb->prefix}redirection_items WHERE regex = 0 ORDER BY position" );
					$redirection_item_url_new         = '';
					$redirection_item_action_data_new = '';


					if ( ! empty( $redirection_items ) ) {
						foreach ( $redirection_items as $redirection_item ) {
							if ( strpos( $redirection_item->url, $this->siteurl_host_non_www ) !== false ) {
								$redirection_item_url_new       = '';
								$redirection_item_url_new_parts = parse_url( $redirection_item->url );


								if ( ! empty( $redirection_item_url_new_parts ) ) {
									if ( $redirection_item_url_new_parts['path'] ) {
										$redirection_item_url_new .= $redirection_item_url_new_parts['path'];
									}

									if ( $redirection_item_url_new_parts['query'] ) {
										$redirection_item_url_new .= '?' . $redirection_item_url_new_parts['query'];
									}
								}


								if ( $redirection_item_url_new != $redirection_item->url ) {
									$update_success[ $type ] = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}redirection_items SET url = %s WHERE id = %d", $redirection_item_url_new, $redirection_item->id ) );
								}
							}

							if ( strpos( $redirection_item->action_data, $this->siteurl_host_non_www ) !== false ) {
								$redirection_item_action_data_new       = '';
								$redirection_item_action_data_new_parts = parse_url( $redirection_item->action_data );


								if ( ! empty( $redirection_item_action_data_new_parts ) ) {
									if ( $redirection_item_action_data_new_parts['path'] ) {
										$redirection_item_action_data_new .= $redirection_item_action_data_new_parts['path'];
									}

									if ( $redirection_item_action_data_new_parts['query'] ) {
										$redirection_item_action_data_new .= '?' . $redirection_item_action_data_new_parts['query'];
									}
								}


								if ( $redirection_item_action_data_new != $redirection_item->action_data ) {
									$update_success[ $type ] = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}redirection_items SET action_data = %s WHERE id = %d", $redirection_item_action_data_new, $redirection_item->id ) );
								}
							}

							if ( $update_success[ $type ] ) {
								$this->post_info[ $type ][] = sprintf(
									'<strong>%s: </strong><br />&nbsp;&nbsp;&nbsp;&nbsp;%s => %s<br />&nbsp;&nbsp;&nbsp;&nbsp;%s => %s',
									$redirection_item->id,
									$redirection_item->url,
									$redirection_item_url_new,
									$redirection_item->action_data,
									$redirection_item_action_data_new
								);
							}
						}
					}


					//guid --- guid --- guid --- guid --- guid --- guid --- guid --- guid --- guid --- guid --- guid
					$type                    = 'guid';
					$update_success[ $type ] = false;
					$guid                    = parse_url( str_replace( '&#038;', '&', $post_guid ) );


					$guid_new = $this->siteurl['scheme'] . '://' . $this->siteurl['host'];

					if ( $guid['path'] ) {
						$guid_new .= $guid['path'];
					}

					if ( $guid['query'] ) {
						$guid_new .= '?' . $guid['query'];
					}


					if ( $guid_new != $post_guid ) {
						$update_success[ $type ] = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->posts} SET guid = %s WHERE ID = %d", $guid_new, $post->ID ) );
					}


					if ( $update_success[ $type ] ) {
						$this->post_info[ $type ][] = sprintf( '<strong>%s: (%s)</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;%s<br />&nbsp;&nbsp;&nbsp;&nbsp;%s', $post->ID, $post_type, $post_guid, $guid_new );
					}


					//Just in case we need this later
					//$post_guid = $guid_new;


					if ( ! in_array( $post->post_type, [ 'acf-field-group', 'acf-field' ] ) ) {
						//post_content --- post_content --- post_content --- post_content --- post_content --- post_content --- post_content
						$this->cleanup_guid_post_content( $post );


						//existing_link_sc_check --- existing_link_sc_check --- existing_link_sc_check --- existing_link_sc_check
						$this->cleanup_guid_existing_link_sc_check( $post );


						//link cleanup --- link cleanup --- link cleanup --- link cleanup --- link cleanup --- link cleanup --- link cleanup
						$this->cleanup_guid_link_cleanup( $post );
					}


				}
			}
		}


		$like                         = '%' . esc_sql( $this->siteurl_host_non_www ) . '%';
		$manually_check_post_contents = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE post_content LIKE '%s' AND post_type NOT IN ( 'revision', 'attachment' ) ORDER BY post_type ASC", $like ) );

		if ( ! empty( $manually_check_post_contents ) ) {
			foreach ( $manually_check_post_contents as $manually_check_post_content ) {
				$this->post_info['manually_check_post_contents'][] = sprintf(
					'<strong><a href="%s" target="_blank">%s (%s)</a>:</strong> Manually Check <a href="%s" target="_blank">%s</a>',
					get_edit_post_link( $manually_check_post_content->ID ),
					$manually_check_post_content->ID,
					$manually_check_post_content->post_type,
					get_the_permalink( $manually_check_post_content ),
					get_the_title( $manually_check_post_content )
				);
			}
		}


		$like                         = '%' . esc_sql( 'href="/' ) . '%';
		$like2                        = '%' . esc_sql( 'href=\'/' ) . '%';
		$like3                        = '%' . esc_sql( 'link="/' ) . '%';
		$like4                        = '%' . esc_sql( 'link=\'/' ) . '%';
		$manually_check_link_cleanups = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE ( post_content LIKE '%s' OR post_content LIKE '%s' OR post_content LIKE '%s' OR post_content LIKE '%s' ) AND post_type != 'revision' ORDER BY post_type ASC", $like, $like2, $like3, $like4 ) );

		if ( ! empty( $manually_check_link_cleanups ) ) {
			foreach ( $manually_check_link_cleanups as $manually_check_link_cleanup ) {
				preg_match_all( '/<a(.*?)href="\/(.*?)"(.*?)>(.*?)<\/a>/', $manually_check_link_cleanup->post_content, $hrefs_double );
				preg_match_all( '/<a(.*?)href=\'\/(.*?)\'(.*?)>(.*?)<\/a>/', $manually_check_link_cleanup->post_content, $hrefs_single );
				preg_match_all( '/(.*?)link="\/(.*?)"/', $manually_check_link_cleanup->post_content, $hrefs_link_double );
				preg_match_all( '/(.*?)link=\'\/(.*?)\'/', $manually_check_link_cleanup->post_content, $hrefs_link_single );


				if (
					! empty( $hrefs_double )
					|| ! empty( $hrefs_single )
					|| ! empty( $hrefs_link_double )
					|| ! empty( $hrefs_link_single )
				) {
					if ( empty( $hrefs_double[2] ) ) {
						$hrefs_double[0] = [];
						$hrefs_double[1] = [];
						$hrefs_double[2] = [];
						$hrefs_double[3] = [];
						$hrefs_double[4] = [];
					}

					if ( ! empty( $hrefs_single[2] ) ) {
						$hrefs_double[0] = array_merge( $hrefs_double[0], $hrefs_single[0] );
						$hrefs_double[1] = array_merge( $hrefs_double[1], $hrefs_single[1] );
						$hrefs_double[2] = array_merge( $hrefs_double[2], $hrefs_single[2] );
						$hrefs_double[3] = array_merge( $hrefs_double[3], $hrefs_single[3] );
						$hrefs_double[4] = array_merge( $hrefs_double[4], $hrefs_single[4] );
					}

					if ( ! empty( $hrefs_link_double[2] ) ) {
						/** @noinspection PhpUnusedLocalVariableInspection */
						foreach ( $hrefs_link_double[2] as $tmp ) {
							$hrefs_link_double[3][] = 'link';
							$hrefs_link_double[4][] = '';
						}

						$hrefs_double[0] = array_merge( $hrefs_double[0], $hrefs_link_double[0] );
						$hrefs_double[1] = array_merge( $hrefs_double[1], $hrefs_link_double[1] );
						$hrefs_double[2] = array_merge( $hrefs_double[2], $hrefs_link_double[2] );
						$hrefs_double[3] = array_merge( $hrefs_double[3], $hrefs_link_double[3] );
						$hrefs_double[4] = array_merge( $hrefs_double[4], $hrefs_link_double[4] );
					}

					if ( ! empty( $hrefs_link_single[2] ) ) {
						/** @noinspection PhpUnusedLocalVariableInspection */
						foreach ( $hrefs_link_single[2] as $tmp ) {
							$hrefs_link_single[3][] = 'link';
							$hrefs_link_single[4][] = '';
						}

						$hrefs_double[0] = array_merge( $hrefs_double[0], $hrefs_link_single[0] );
						$hrefs_double[1] = array_merge( $hrefs_double[1], $hrefs_link_single[1] );
						$hrefs_double[2] = array_merge( $hrefs_double[2], $hrefs_link_single[2] );
						$hrefs_double[3] = array_merge( $hrefs_double[3], $hrefs_link_single[3] );
						$hrefs_double[4] = array_merge( $hrefs_double[4], $hrefs_link_single[4] );
					}


					$href_list_manual = [];


					foreach ( $hrefs_double[2] as $path_key => $path ) {
						if (
							strpos( $path, '-content/uploads/' ) !== false
							|| strpos( $path, 'uploads/' ) === 0
						) {
							continue;
						}


						if ( strpos( $path, '/' ) === 0 ) {
							continue;
						}


						if ( strpos( $path, 'mailto:' ) === 0 ) {
							continue;
						}


						$href_list_manual[] = htmlentities( '/' . $hrefs_double[2][ $path_key ] );
					}


					if ( ! empty( $href_list_manual ) ) {
						$this->post_info['manually_check_link_cleanups'][] = sprintf(
							'<strong><a href="%s" target="_blank">%s (%s)</a>:</strong> Manually Check <a href="%s" target="_blank">%s</a><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%s<br />',
							get_edit_post_link( $manually_check_link_cleanup->ID ),
							$manually_check_link_cleanup->ID,
							$manually_check_link_cleanup->post_type,
							get_the_permalink( $manually_check_link_cleanup ),
							get_the_title( $manually_check_link_cleanup ),
							lct_return( $href_list_manual, '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' )
						);
					}
				}
			}
		}


		$like                         = '%' . esc_sql( 'href="' ) . '%';
		$like2                        = '%' . esc_sql( 'href=\'' ) . '%';
		$manually_check_link_cleanups = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE ( post_content LIKE '%s' OR post_content LIKE '%s' ) AND post_type != 'revision' ORDER BY post_type ASC", $like, $like2 ) );

		if ( ! empty( $manually_check_link_cleanups ) ) {
			foreach ( $manually_check_link_cleanups as $manually_check_link_cleanup ) {
				preg_match_all( '/<a(.*?)href="(.*?)"(.*?)>(.*?)<\/a>/', $manually_check_link_cleanup->post_content, $hrefs_double );
				preg_match_all( '/<a(.*?)href=\'(.*?)\'(.*?)>(.*?)<\/a>/', $manually_check_link_cleanup->post_content, $hrefs_single );


				if (
					! empty( $hrefs_double[2] )
					|| ! empty( $hrefs_single[2] )
				) {
					if ( empty( $hrefs_double[2] ) ) {
						$hrefs_double[0] = [];
						$hrefs_double[1] = [];
						$hrefs_double[2] = [];
						$hrefs_double[3] = [];
						$hrefs_double[4] = [];
					}

					if ( ! empty( $hrefs_single[2] ) ) {
						$hrefs_double[0] = array_merge( $hrefs_double[0], $hrefs_single[0] );
						$hrefs_double[1] = array_merge( $hrefs_double[1], $hrefs_single[1] );
						$hrefs_double[2] = array_merge( $hrefs_double[2], $hrefs_single[2] );
						$hrefs_double[3] = array_merge( $hrefs_double[3], $hrefs_single[3] );
						$hrefs_double[4] = array_merge( $hrefs_double[4], $hrefs_single[4] );
					}


					$href_list_all = [];


					foreach ( $hrefs_double[2] as $path_key => $path ) {
						if (
							strpos( $path, '-content/uploads/' ) !== false
							|| strpos( $path, '/uploads/' ) === 0
						) {
							continue;
						}


						if (
							strpos( $path, 'mailto:' ) === 0
						) {
							continue;
						}


						$href_atts_1 = shortcode_parse_atts( $hrefs_double[1][ $path_key ] );
						$href_atts_3 = shortcode_parse_atts( $hrefs_double[3][ $path_key ] );

						if (
							(
								isset( $href_atts_1['target'] )
								&& $href_atts_1['target'] == '_blank'
							)
							|| (
								isset( $href_atts_3['target'] )
								&& $href_atts_3['target'] == '_blank'
							)
						) {
							$target = '<span style="color: green;">blank</span>';
						} else {
							$target = '<span style="font-weight:bold;color: red;">self</span>';
						}


						$href_list_all[] = 'Target: ' . $target . ' ' . zxzd() . ' ' . htmlentities( $hrefs_double[2][ $path_key ] );
					}

					if ( ! empty( $href_list_all ) ) {
						$this->post_info['manually_check_link_cleanups_all_hrefs'][] = sprintf(
							'<strong><a href="%s" target="_blank">%s (%s)</a>:</strong> All HREFs For <a href="%s" target="_blank">%s</a><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%s<br />',
							get_edit_post_link( $manually_check_link_cleanup->ID ),
							$manually_check_link_cleanup->ID,
							$manually_check_link_cleanup->post_type,
							get_the_permalink( $manually_check_link_cleanup ),
							get_the_title( $manually_check_link_cleanup ),
							lct_return( $href_list_all, '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' )
						);
					}
				}
			}
		}


		$like                         = '%' . esc_sql( 'src="' ) . '%';
		$like2                        = '%' . esc_sql( 'src=\'' ) . '%';
		$like3                        = '%' . esc_sql( '[/fusion_imageframe]' ) . '%';
		$manually_check_link_cleanups = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE ( post_content LIKE '%s' OR post_content LIKE '%s' OR post_content LIKE '%s' ) AND post_type != 'revision' AND post_type != 'oembed_cache' ORDER BY post_type ASC", $like, $like2, $like3 ) );

		if ( ! empty( $manually_check_link_cleanups ) ) {
			foreach ( $manually_check_link_cleanups as $manually_check_link_cleanup ) {
				preg_match_all( '/<(.*?)src="(.*?)"(.*?)>/', $manually_check_link_cleanup->post_content, $hrefs_double );
				preg_match_all( '/<(.*?)src=\'(.*?)\'(.*?)>/', $manually_check_link_cleanup->post_content, $hrefs_single );
				preg_match_all( '/]\/(.*?)\[\/fusion_imageframe]/', $manually_check_link_cleanup->post_content, $hrefs_link_double );


				if (
					! empty( $hrefs_double[2] )
					|| ! empty( $hrefs_single[2] )
					|| ! empty( $hrefs_link_double[1] )
				) {
					if ( empty( $hrefs_double[2] ) ) {
						$hrefs_double[0] = [];
						$hrefs_double[1] = [];
						$hrefs_double[2] = [];
					}

					if ( ! empty( $hrefs_single[2] ) ) {
						$hrefs_double[0] = array_merge( $hrefs_double[0], $hrefs_single[0] );
						$hrefs_double[1] = array_merge( $hrefs_double[1], $hrefs_single[1] );
						$hrefs_double[2] = array_merge( $hrefs_double[2], $hrefs_single[2] );
					}

					if ( ! empty( $hrefs_link_double[1] ) ) {
						foreach ( $hrefs_link_double[1] as $k => $v ) {
							$hrefs_link_double[1][ $k ] = '/' . $v;
						}


						$hrefs_double[0] = array_merge( $hrefs_double[0], $hrefs_link_double[1] );
						$hrefs_double[1] = array_merge( $hrefs_double[1], $hrefs_link_double[1] );
						$hrefs_double[2] = array_merge( $hrefs_double[2], $hrefs_link_double[1] );
					}


					$href_list_all = [];


					foreach ( $hrefs_double[2] as $path_key => $path ) {
						$target = 'good';


						if (
							strpos( $path, '//' ) !== false
						) {
							$target = 'EXTERNAL';
						}


						$exists = '';

						if (
							$target === 'good'
							&& ! file_exists( lct_path_site() . $hrefs_double[2][ $path_key ] )
						) {
							$exists = ' <span style="color: red;font-weight: bold;">:: FILE IS MISSING :: FILE IS MISSING :: FILE IS MISSING</span>';
						}


						$href_list_all[] = $target . ' ' . zxzd() . ' ' . htmlentities( $hrefs_double[2][ $path_key ] ) . $exists;
					}

					if ( ! empty( $href_list_all ) ) {
						$this->post_info['manually_check_link_cleanups_all_srcs'][] = sprintf(
							'<strong><a href="%s" target="_blank">%s (%s)</a>:</strong> All SRCs For <a href="%s" target="_blank">%s</a><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%s<br />',
							get_edit_post_link( $manually_check_link_cleanup->ID ),
							$manually_check_link_cleanup->ID,
							$manually_check_link_cleanup->post_type,
							get_the_permalink( $manually_check_link_cleanup ),
							get_the_title( $manually_check_link_cleanup ),
							lct_return( $href_list_all, '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' )
						);
					}
				}
			}
		}
		?>


		<h2 style="margin-bottom: 0;text-decoration: underline;">Post Types</h2>
		<p style="margin-top: 0;"><?php
			foreach ( $this->post_types as $tmp ) {
				$count = 0;


				if (
					( $count_posts = wp_count_posts( $tmp ) )
					&& ! empty( $count_posts )
				) {
					foreach ( $count_posts as $count_post ) {
						if ( $count_post ) {
							$count += (int) $count_post;
						}
					}
				}


				if ( $count ) {
					echo '<strong>' . $tmp . ' [' . $count . ']</strong><br />';
				} else {
					echo $tmp . ' [' . $count . ']<br />';
				}
			}
			?></p>
		<p>&nbsp;</p>


		<h2 style="margin-bottom: 0;text-decoration: underline;">Cleaning Up Redirection Items...</h2>
		<p style="margin-top: 0;"><?php echo lct_return( $this->post_info['redirection_items'], '<br />' ); ?></p>
		<h3 style="margin-bottom: 0;color: green;">Redirection Items Updated!</h3>
		<p>&nbsp;</p>


		<h2 style="margin-bottom: 0;text-decoration: underline;">Cleaning Up Guid...</h2>
		<h4 style="margin-bottom: 0;margin-top: 0;">Site Scheme: <?php echo $this->siteurl['scheme']; ?></h4>
		<h4 style="margin-bottom: 0;margin-top: 0;">Site URL: <?php echo $this->siteurl['host']; ?></h4>

		<p style="margin-top: 0;"><?php echo lct_return( $this->post_info['guid'], '<br />' ); ?></p>
		<h3 style="margin-bottom: 0;color: green;">Guids Updated!</h3>
		<p>&nbsp;</p>


		<h2 style="margin-bottom: 0;text-decoration: underline;">Cleaning Up Host in post_content HREFs & SRCs...</h2>
		<p style="margin-top: 0;"><?php echo lct_return( $this->post_info['post_content'], '<br />' ); ?></p>
		<h3 style="margin-bottom: 0;color: green;">Post_content HREFs & SRCs Updated!</h3>
		<p>&nbsp;</p>


		<h2 style="margin-bottom: 0;text-decoration: underline;">Converting Internal Links...</h2>
		<p style="margin-top: 0;"><?php echo lct_return( $this->post_info['link_cleanup'], '<br />' ); ?></p>
		<h3 style="margin-bottom: 0;color: green;">Internal Links Updated!</h3>
		<p>&nbsp;</p>


		<h2 style="margin-bottom: 0;text-decoration: underline;">MANUALLY Check post_content HREFs & SRCs for Bad Host...</h2>
		<p style="margin-top: 0;"><?php echo lct_return( $this->post_info['manually_check_post_contents'], '<br />' ); ?></p>
		<?php ! empty( $this->post_info['manually_check_post_contents'] ) ? $message = 'You need to manually check the items in the above list.' : $message = 'Yay, Nothing to Manually Check!'; ?>
		<?php ! empty( $this->post_info['manually_check_post_contents'] ) ? $color = 'red' : $color = 'green'; ?>
		<h3 style="margin-bottom: 0;color: <?php echo $color; ?>;"><?php echo $message; ?></h3>
		<p>&nbsp;</p>


		<h2 style="margin-bottom: 0;text-decoration: underline;">MANUALLY for Link Shortcodes That Go To Deleted Pages...</h2>
		<p style="margin-top: 0;"><?php echo lct_return( $this->post_info['existing_link_sc_check'], '<br />' ); ?></p>
		<?php ! empty( $this->post_info['existing_link_sc_check'] ) ? $message = 'You need to manually check the items in the above list.' : $message = 'Yay, Nothing to Manually Check!'; ?>
		<?php ! empty( $this->post_info['existing_link_sc_check'] ) ? $color = 'red' : $color = 'green'; ?>
		<h3 style="margin-bottom: 0;color: <?php echo $color; ?>;"><?php echo $message; ?></h3>
		<p>&nbsp;</p>


		<h2 style="margin-bottom: 0;text-decoration: underline;">MANUALLY Check post_content Internal Links That May Be 404 or Incorrect Format...</h2>
		<p style="margin-top: 0;"><?php echo lct_return( $this->post_info['manually_check_link_cleanups'], '<br />' ); ?></p>
		<?php ! empty( $this->post_info['manually_check_link_cleanups'] ) ? $message = 'You need to manually check the items in the above list.' : $message = 'Yay, Nothing to Manually Check!'; ?>
		<?php ! empty( $this->post_info['manually_check_link_cleanups'] ) ? $color = 'red' : $color = 'green'; ?>
		<h3 style="margin-bottom: 0;color: <?php echo $color; ?>;"><?php echo $message; ?></h3>
		<p>&nbsp;</p>


		<h2 style="margin-bottom: 0;text-decoration: underline;">Just A List of All Existing HREFs...</h2>
		<p style="margin-top: 0;"><?php echo lct_return( $this->post_info['manually_check_link_cleanups_all_hrefs'], '<br />' ); ?></p>
		<?php ! empty( $this->post_info['manually_check_link_cleanups_all_hrefs'] ) ? $message = 'You have HREFs, but that isn\'t a bad thing. Just letting you know.' : $message = 'Yay, Nothing Here!'; ?>
		<?php ! empty( $this->post_info['manually_check_link_cleanups_all_hrefs'] ) ? $color = 'red' : $color = 'green'; ?>
		<h3 style="margin-bottom: 0;color: <?php echo $color; ?>;"><?php echo $message; ?></h3>


		<h2 style="margin-bottom: 0;text-decoration: underline;">Just A List of All Existing SRCs...</h2>
		<p style="margin-top: 0;"><?php echo lct_return( $this->post_info['manually_check_link_cleanups_all_srcs'], '<br />' ); ?></p>
		<?php ! empty( $this->post_info['manually_check_link_cleanups_all_srcs'] ) ? $message = 'You have SRCs, but that isn\'t a bad thing. Just letting you know.' : $message = 'Yay, Nothing Here!'; ?>
		<?php ! empty( $this->post_info['manually_check_link_cleanups_all_srcs'] ) ? $color = 'red' : $color = 'green'; ?>
		<h3 style="margin-bottom: 0;color: <?php echo $color; ?>;"><?php echo $message; ?></h3>


		<h1 style="color: blue;">ALL Done!</h1>
	<?php }


	/**
	 * Close all the pings and comments on posts, pages, etc.
	 * old_useful_menu
	 *
	 * @since    5.40
	 * @verified 2019.08.15
	 */
	function lct_close_all_pings_and_comments()
	{
		$this->post_info  = [];
		$this->post_types = get_post_types();
		$taxonomies       = get_taxonomies();


		ksort( $this->post_types );


		foreach ( $this->post_types as $post_type ) {
			$args  = [
				'posts_per_page'         => - 1,
				'post_type'              => $post_type,
				'post_status'            => 'any',
				'cache_results'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			];
			$posts = get_posts( $args );


			if ( ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					if (
						$post->comment_status != 'closed'
						|| $post->ping_status != 'closed'
					) {
						$args           = [
							'ID'             => $post->ID,
							'comment_status' => 'closed',
							'ping_status'    => 'closed',
						];
						$update_success = wp_update_post( $args );

						if ( $update_success ) {
							$this->post_info[] = sprintf( '<strong>%s (%s):</strong> Pings and comments are now closed for %s', $post->ID, $post_type, get_the_title( $post ) );
						}
					}
				}
			}
		}
		?>


		<h2 style="margin-bottom: 0;text-decoration: underline;">Post Types</h2>
		<p style="margin-top: 0;"><?php echo lct_return( $this->post_types, '<br />' ); ?></p>


		<h2 style="margin-bottom: 0;text-decoration: underline;">Taxonomies</h2>
		<p style="margin-top: 0;"><?php echo lct_return( $taxonomies, '<br />' ); ?></p>


		<h2 style="margin-bottom: 0;text-decoration: underline;">Closing All Pings & Comments...</h2>
		<p style="margin-top: 0;"><?php echo lct_return( $this->post_info, '<br />' ); ?></p>
		<h3 style="margin-bottom: 0;color: green;">Pings & Comments Now Closed!</h3>


		<h1 style="color: blue;">ALL Done!</h1>
	<?php }


	/**
	 * Cleanup unneeded files
	 * //TODO: cs - Make this its own plugin - 9/27/2018 2:58 PM
	 * old_useful_menu
	 *
	 * @since    5.40
	 * @verified 2024.08.23
	 */
	function lct_cleanup_uploads()
	{
		/**
		 * Vars
		 */
		$uploads_path    = lct_path_up() . '/';
		$sys_image_sizes = acf_get_image_sizes();
		unset( $sys_image_sizes['full'] );
		$att_meta        = [];
		$att_meta_update = [];
		$this->post_info = [
			'message'              => [],
			'image_sizes'          => [],
			'image_sizes_managed'  => $sys_image_sizes,
			'image_sizes_disabled' => [],
			'disabled_images_note' => [],
			'none_images'          => [],
		];


		remove_action( 'image_get_intermediate_size', [ 'WC_Regenerate_Images', 'filter_image_get_intermediate_size' ] );


		$args        = [
			'posts_per_page'         => - 1,
			'post_type'              => 'attachment',
			'post_status'            => 'any',
			'cache_results'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		];
		$attachments = get_posts( $args );


		if ( ! empty( $attachments ) ) {
			/**
			 * Mark the attachment count
			 */
			$this->post_info['message'][] = '<h2>Attachments: ' . count( $attachments ) . '</h2>';


			/**
			 * Initially Go through all Media Library items
			 * Setup vars that we will use later
			 */
			foreach ( $attachments as $att_k => $att ) {
				$att_id = $att->ID;


				if ( ! wp_attachment_is_image( $att_id ) ) {
					$this->post_info['none_images'][ $att->post_mime_type . '_' . $att->ID ] = $att;


					unset( $attachments[ $att_k ] );


					continue;
				}


				$_att_meta = $att_meta[ $att_id ] = wp_get_attachment_metadata( $att_id );


				if ( ! empty( $_att_meta['sizes'] ) ) {
					foreach ( $_att_meta['sizes'] as $size => $size_info ) {
						/**
						 * Retired image sizes
						 * We don't want to delete the images, because they are a different image_size name, but we don't need the info about them.
						 */
						if ( in_array( $size, [ 'shop_catalog', 'shop_single', 'shop_thumbnail' ] ) ) {
							unset( $att_meta[ $att_id ]['sizes'][ $size ] );


							$att_meta_update[ $att_id ] = true;


							continue;
						}


						/**
						 * Set the image sizes that are represented in the DB
						 */
						if ( ! key_exists( $size, $this->post_info['image_sizes'] ) ) {
							$this->post_info['image_sizes'][ $size ] = true;
						}
					}
				}
			}


			/**
			 * Mark the image count
			 */
			$this->post_info['message'][] = '<h2>Images: ' . count( $attachments ) . '</h2>';


			/**
			 * Set the list of disabled images that are still attached to files in the DB
			 */
			if ( ! empty( $this->post_info['image_sizes'] ) ) {
				$this->post_info['image_sizes_disabled'] = array_diff_key( $this->post_info['image_sizes'], $this->post_info['image_sizes_managed'] );
			}


			ksort( $this->post_info['image_sizes'] );
			ksort( $this->post_info['image_sizes_managed'] );
			ksort( $this->post_info['image_sizes_disabled'] );
			ksort( $this->post_info['none_images'] );


			/**
			 * Prepare the array for counting images
			 */
			foreach ( $this->post_info['image_sizes_managed'] as $k => $v ) {
				$this->post_info['image_sizes_managed'][ $k ] = [ 'label' => $v, 'count' => 0 ];
			}


			/**
			 * Removed images if they are a disabled size
			 * Produce an error for images of disabled size that are still in use
			 */
			if (
				1 === 1
				&& ! empty( $attachments )
				&& ! empty( $this->post_info['image_sizes_disabled'] )
			) {
				foreach ( $attachments as $att ) {
					$att_id    = $att->ID;
					$_att_meta = $att_meta[ $att_id ];


					if ( ! empty( $_att_meta ) ) {
						if ( ! empty( $_att_meta['sizes'] ) ) {
							foreach ( $this->post_info['image_sizes_disabled'] as $size => $size_disabled ) {
								if ( ! isset( $_att_meta['sizes'][ $size ] ) ) {
									continue;
								}


								$_att_sizes  = $_att_meta['sizes'][ $size ];
								$_image_info = image_get_intermediate_size( $att_id, $size );
								$in_use      = false;


								/**
								 * Something weird is wrong
								 * check manually
								 */
								if (
									empty( $_image_info )
									|| empty( $_att_sizes['file'] )
									|| empty( $_image_info['file'] )
									|| empty( $_image_info['path'] )
									|| $_image_info['file'] !== $_att_sizes['file']
								) {
									$this->post_info['disabled_images_note'][ 'zwrong1_' . $att_id ] = sprintf( 'Something is wrong #1: %s %s (%s)', $att_id, $_att_sizes['file'], $size );


									/**
									 * Image is on the server
									 */
								} elseif (
									! empty( $_att_sizes['file'] )
									&& ! empty( $_image_info['path'] )
									&& $_image_info['file'] === $_att_sizes['file']
									&& ( $_image_path = $uploads_path . $_image_info['path'] )
									&& file_exists( $_image_path )
								) {
									if ( $exists = lct_get_posts_with_image( $att_id, $_att_sizes ) ) {
										if ( count( $exists ) > 1000 ) {
											$this->post_info['disabled_images_note'][ 'image_in_use_a_lot_' . $att_id ] = sprintf( 'Too many posts to check: %s %s (%s)', $att_id, $_att_sizes['file'], $size );
											break;
										} else {
											foreach ( $exists as $tmp ) {
												$this->post_info['disabled_images_note'][ 'image_in_use_' . $att_id ] = sprintf( 'Image is use: %s %s (%s) <a href="%s" target="_blank">%s</a>', $att_id, $_att_sizes['file'], $size, get_edit_post_link( $tmp->ID ), get_the_title( $tmp->ID ) );
											}


											$in_use = true;
										}
									}


									if ( $exists = lct_get_featured_image_posts_with_image( $att_id ) ) {
										foreach ( $exists as $tmp ) {
											$this->post_info['disabled_images_note'][ 'featured_image_in_use_postmeta_' . $att_id ] = sprintf( 'Featured Image is use [postmeta]: %s %s (%s) <a href="%s" target="_blank">%s</a>', $att_id, $_att_sizes['file'], $size, get_edit_post_link( $tmp->post_id ), get_the_title( $tmp->post_id ) );
										}


										$in_use = true;
									}


									if ( $exists = lct_get_postmetas_with_image( $att_id, $_att_sizes ) ) {
										foreach ( $exists as $tmp ) {
											$this->post_info['disabled_images_note'][ 'image_in_use_postmeta_' . $att_id ] = sprintf( 'Image is use [postmeta]: %s %s (%s) <a href="%s" target="_blank">%s</a>', $att_id, $_att_sizes['file'], $size, get_edit_post_link( $tmp->post_id ), get_the_title( $tmp->post_id ) );
										}


										$in_use = true;
									}


									if ( $exists = lct_get_termmetas_with_image( $att_id, $_att_sizes ) ) {
										foreach ( $exists as $tmp ) {
											$this->post_info['disabled_images_note'][ 'image_in_use_termmeta_' . $att_id ] = sprintf( 'Image is use [termmeta]: %s %s (%s) <a href="%s" target="_blank">%s</a>', $att_id, $_att_sizes['file'], $size, get_edit_term_link( $tmp->term_id ), $tmp->term_id );
										}


										$in_use = true;
									}


									if ( $exists = lct_get_usermetas_with_image( $att_id, $_att_sizes ) ) {
										foreach ( $exists as $tmp ) {
											$this->post_info['disabled_images_note'][ 'image_in_use_usermeta_' . $att_id ] = sprintf( 'Image is use [usermeta]: %s %s (%s) <a href="%s" target="_blank">%s</a>', $att_id, $_att_sizes['file'], $size, get_edit_user_link( $tmp->user_id ), $tmp->user_id );
										}


										$in_use = true;
									}


									if ( $exists = lct_get_options_with_image( $att_id, $_att_sizes ) ) {
										foreach ( $exists as $tmp ) {
											$this->post_info['disabled_images_note'][ 'image_in_use_options_' . $att_id ] = sprintf( 'Image is use [options]: %s %s (%s) :: %s', $att_id, $_att_sizes['file'], $size, $tmp->option_name );
										}


										$in_use = true;
									}


									if ( ! $in_use ) {
										@unlink( $_image_path );


										/**
										 * File was successfully deleted
										 */
										if ( ! file_exists( $_image_path ) ) {
											unset( $att_meta[ $att_id ]['sizes'][ $size ] );
											unset( $att_meta_update[ $att_id ] );


											/**
											 * Meta was successfully updated
											 */
											if ( wp_update_attachment_metadata( $att_id, $att_meta[ $att_id ] ) ) {
												$this->post_info['disabled_images_note'][ 'image_exists_deleted_' . $att_id ] = sprintf( 'GOOD :: Image Deleted: %s %s (%s)', $att_id, $_att_sizes['file'], $size );


												/**
												 * Meta failed to update
												 */
											} else {
												$this->post_info['disabled_images_note'][ 'image_exists_deleted_meta_not_updated_' . $att_id ] = sprintf( 'Image Deleted, but meta not updated: %s %s (%s)', $att_id, $_att_sizes['file'], $size );
											}


											/**
											 * File failed to delete
											 */
										} else {
											$this->post_info['disabled_images_note'][ 'image_exists_delete_failed_' . $att_id ] = sprintf( 'Image would not delete: %s %s (%s)', $att_id, $_att_sizes['file'], $size );
										}
									}


									/**
									 * Something weird is wrong
									 */
								} else {
									$this->post_info['disabled_images_note'][ 'zwrong2_' . $att_id ] = sprintf( 'Something is wrong #2: %s %s (%s)', $att_id, $_att_sizes['file'], $size );


									if (
										1 === 2
										&& ! empty( $_att_sizes['file'] )
										&& ! empty( $_image_info['path'] )
										&& ( $_image_path = $uploads_path . $_image_info['path'] )
										&& ! file_exists( $_image_path )
									) {
										unset( $att_meta[ $att_id ]['sizes'][ $size ] );
										unset( $att_meta_update[ $att_id ] );
										wp_update_attachment_metadata( $att_id, $att_meta[ $att_id ] );
									}
								}
							}
						}
					}
				}
			}


			/**
			 * Check all Media Library images and report if they are not in use
			 */
			if (
				1 === 1
				&& ! empty( $attachments )
			) {
				/**
				 * Use with caution
				 */
				$delete_all_sizes = false;


				foreach ( $attachments as $att ) {
					$att_id    = $att->ID;
					$_att_meta = $att_meta[ $att_id ];


					if ( ! empty( $_att_meta ) ) {
						$in_use = false;


						/**
						 * Check the full size image
						 */
						if ( ! empty( $_att_meta['file'] ) ) {
							if ( lct_get_posts_with_image( $att_id, $_att_meta ) ) {
								$in_use = true;
							} elseif ( lct_get_featured_image_posts_with_image( $att_id ) ) {
								$in_use = true;
							} elseif ( lct_get_postmetas_with_image( $att_id, $_att_meta ) ) {
								$in_use = true;
							} elseif ( lct_get_termmetas_with_image( $att_id, $_att_meta ) ) {
								$in_use = true;
							} elseif ( lct_get_usermetas_with_image( $att_id, $_att_meta ) ) {
								$in_use = true;
							} elseif ( lct_get_options_with_image( $att_id, $_att_meta ) ) {
								$in_use = true;
							}
						}


						/**
						 * Check the other sizes
						 */
						if ( ! empty( $_att_meta['sizes'] ) ) {
							foreach ( $_att_meta['sizes'] as $size => $_att_sizes ) {
								$this->post_info['image_sizes_managed'][ $size ]['count'] ++;


								if (
									$delete_all_sizes
									|| ! $in_use
								) {
									$_image_info = image_get_intermediate_size( $att_id, $size );


									/**
									 * Something weird is wrong
									 * check manually
									 */
									if (
										empty( $_image_info )
										|| empty( $_att_sizes['file'] )
										|| empty( $_image_info['file'] )
										|| empty( $_image_info['path'] )
										|| $_image_info['file'] !== $_att_sizes['file']
									) {
										$this->post_info['disabled_images_note'][ 'zwrong3_' . $att_id ] = sprintf( 'Something is wrong #3: %s %s (%s)', $att_id, $_att_sizes['file'], $size );


										/**
										 * Image is on the server
										 */
									} elseif (
										! empty( $_att_sizes['file'] )
										&& ! empty( $_image_info['path'] )
										&& $_image_info['file'] === $_att_sizes['file']
										&& ( $_image_path = $uploads_path . $_image_info['path'] )
										&& file_exists( $_image_path )
									) {
										if ( $delete_all_sizes ) {
											@unlink( $_image_path );


											unset( $att_meta[ $att_id ]['sizes'][ $size ] );
											unset( $att_meta_update[ $att_id ] );


											wp_update_attachment_metadata( $att_id, $att_meta[ $att_id ] );
										} elseif ( lct_get_posts_with_image( $att_id, $_att_sizes ) ) {
											$in_use = true;
											break;
										} elseif ( lct_get_featured_image_posts_with_image( $att_id ) ) {
											$in_use = true;
											break;
										} elseif ( lct_get_postmetas_with_image( $att_id, $_att_sizes ) ) {
											$in_use = true;
											break;
										} elseif ( lct_get_termmetas_with_image( $att_id, $_att_sizes ) ) {
											$in_use = true;
											break;
										} elseif ( lct_get_usermetas_with_image( $att_id, $_att_sizes ) ) {
											$in_use = true;
											break;
										} elseif ( lct_get_options_with_image( $att_id, $_att_sizes ) ) {
											$in_use = true;
											break;
										}


										/**
										 * Something weird is wrong
										 */
									} else {
										$this->post_info['disabled_images_note'][ 'zwrong4_' . $att_id ] = sprintf( 'Something is wrong #4: %s %s (%s)', $att_id, $_att_sizes['file'], $size );
									}
								}
							}
						}


						/**
						 * Ok we are probably not using the image
						 */
						if ( ! $in_use ) {
							$this->post_info['disabled_images_note'][ 'image_not_in_use_' . $att_id ] = sprintf( 'Library image does not appear to be in use: %s :: %s %sx%s<br /><img src="%s" style="max-width: 200px;" alt="" />', $att_id, $_att_meta['file'], $_att_meta['width'], $_att_meta['height'], lct_url_up( $_att_meta['file'] ) );


							$auto_delete_2 = false;
							if (
								$auto_delete_2
								&& ! empty( $_att_meta['file'] )
								&& ( $_image_path = $uploads_path . $_att_meta['file'] )
								&& file_exists( $_image_path )
							) {
								wp_delete_attachment( $att_id, true );
								@unlink( $_image_path );
							}
						}
					}
				}
			}


			/**
			 * Scan the uploads dir
			 */
			if ( 1 === 1 ) {
				$excluded         = [
					'.htaccess',
					'index.html',
					'mc4wp-debug-log.php',
				];
				$excluded_folders = [
					'fusion-gfonts',
					'fusionredux',
					'gravity_forms',
					'powerpress',
					'wc-logs',
					'woocommerce_uploads',
					'wp-sync-db',
				];
				$upload_files     = $this->get_all_upload_files( $uploads_path, $excluded, $excluded_folders );


				/**
				 * Remove images from the uploads' dir list that are in the Media Library DB
				 */
				if (
					! empty( $upload_files )
					&& ! empty( $attachments )
				) {
					foreach ( $attachments as $att ) {
						$att_id    = $att->ID;
						$_att_meta = $att_meta[ $att_id ];


						if ( ! empty( $_att_meta ) ) {
							if (
								! empty( $_att_meta['file'] )
								&& ( $uploads_key = $this->check_against_uploads( $upload_files, $_att_meta['file'] ) ) !== false
							) {
								unset( $upload_files[ $uploads_key ] );
							}


							if ( ! empty( $_att_meta['sizes'] ) ) {
								foreach ( $_att_meta['sizes'] as $_size ) {
									if (
										! empty( $_size['file'] )
										&& ( $uploads_key = $this->check_against_uploads( $upload_files, $_size['file'] ) ) !== false
									) {
										unset( $upload_files[ $uploads_key ] );
									}
								}
							}
						}
					}
				}


				/**
				 * Remove non-images from the uploads' dir list that are in the Media Library DB
				 */
				if (
					! empty( $upload_files )
					&& ! empty( $this->post_info['none_images'] )
				) {
					foreach ( $this->post_info['none_images'] as $att ) {
						$att_id        = $att->ID;
						$relative_path = lct_strip_site( wp_get_attachment_url( $att_id ) );


						if (
							! empty( $relative_path )
							&& ( $uploads_key = $this->check_against_uploads( $upload_files, $relative_path ) ) !== false
						) {
							unset( $upload_files[ $uploads_key ] );
						}
					}
				}


				/**
				 * Still have some orphans
				 */
				if ( ! empty( $upload_files ) ) {
					$att_id = null;


					foreach ( $upload_files as $file_key => $upload_file ) {
						$in_use               = false;
						$stripped_upload_file = str_replace( $uploads_path, '', $upload_file );
						$_att_sizes           = [ 'file' => $stripped_upload_file ];


						if ( $exists = lct_get_posts_with_image( null, $_att_sizes ) ) {
							if ( count( $exists ) > 1000 ) {
								$this->post_info['disabled_images_note'][ 'server_image_in_use_a_lot_' . $file_key ] = sprintf( 'Too many posts to check: %s', $stripped_upload_file );
								break;
							} else {
								foreach ( $exists as $tmp ) {
									$this->post_info['disabled_images_note'][ 'server_image_in_use_' . $file_key ] = sprintf( 'Image is use: %s <a href="%s" target="_blank">%s</a>', $_att_sizes['file'], get_edit_post_link( $tmp->ID ), get_the_title( $tmp->ID ) );
								}


								$in_use = true;
							}
						}


						if ( $exists = lct_get_postmetas_with_image( $att_id, $_att_sizes ) ) {
							foreach ( $exists as $tmp ) {
								$this->post_info['disabled_images_note'][ 'server_image_in_use_postmeta_' . $file_key ] = sprintf( 'Image is use [postmeta]: %s <a href="%s" target="_blank">%s</a>', $stripped_upload_file, get_edit_post_link( $tmp->post_id ), get_the_title( $tmp->post_id ) );
							}


							$in_use = true;
						}


						if ( $exists = lct_get_termmetas_with_image( $att_id, $_att_sizes ) ) {
							foreach ( $exists as $tmp ) {
								$this->post_info['disabled_images_note'][ 'server_image_in_use_termmeta_' . $file_key ] = sprintf( 'Image is use [termmeta]: %s <a href="%s" target="_blank">%s</a>', $stripped_upload_file, get_edit_term_link( $tmp->term_id ), $tmp->term_id );
							}


							$in_use = true;
						}


						if ( $exists = lct_get_usermetas_with_image( $att_id, $_att_sizes ) ) {
							foreach ( $exists as $tmp ) {
								$this->post_info['disabled_images_note'][ 'server_image_in_use_usermeta_' . $file_key ] = sprintf( 'Image is use [usermeta]: %s <a href="%s" target="_blank">%s</a>', $stripped_upload_file, get_edit_user_link( $tmp->user_id ), $tmp->user_id );
							}


							$in_use = true;
						}


						if ( $exists = lct_get_options_with_image( $att_id, $_att_sizes ) ) {
							foreach ( $exists as $tmp ) {
								$this->post_info['disabled_images_note'][ 'server_image_in_use_options_' . $file_key ] = sprintf( 'Image is use [options]: %s :: %s', $stripped_upload_file, $tmp->option_name );
							}


							$in_use = true;
						}


						/**
						 * Ok we are probably not using the image
						 */
						if ( ! $in_use ) {
							$auto_delete = false;
							if ( $auto_delete === true ) {
								@unlink( $upload_file );


								$this->post_info['disabled_images_note'][ 'server_image_on_server_not_library_deleted_' . $file_key ] = sprintf( 'GOOD Deleted: %s', $upload_file );
							} else {
								$this->post_info['disabled_images_note'][ 'image_on_server_not_library_' . $file_key ] = sprintf( 'GOODish not in use :: Image on server and not in Library: %s<br /><img src="%s" style="max-width: 200px;" alt="" />', $upload_file, lct_swap_path_to_url( $upload_file ) );
							}
						}
					}


					$this->post_info['message'][] = '<h2>Orphaned Uploads: ' . count( $upload_files ) . '</h2>';
				}
			}


			ksort( $this->post_info['disabled_images_note'] );


			/**
			 * Make any updates to the DB
			 */
			if ( ! empty( $att_meta_update ) ) {
				/**
				 * Update meta
				 */
				foreach ( $att_meta_update as $att_id => $update ) {
					if ( wp_update_attachment_metadata( $att_id, $att_meta[ $att_id ] ) ) {
						unset( $att_meta_update[ $att_id ] );
					}
				}
			}


			/**
			 * Fix Featured Images
			 */
			if ( $exists = lct_get_featured_image_posts_with_image( 'all' ) ) {
				foreach ( $exists as $tmp ) {
					if (
						! ( $image_exists = get_post( $tmp->meta_value ) )
						|| lct_is_wp_error( $image_exists )
					) {
						$this->post_info['disabled_images_note'][ 'featured_image_missing' . $tmp->post_id ] = sprintf( 'Featured Image is missing: <a href="%s" target="_blank">%s</a>', get_edit_post_link( $tmp->post_id ), get_the_title( $tmp->post_id ) );
					}
				}
			}


			/**
			 * Empty Media Library
			 */
		} else {
			$this->post_info['message'][] = '<h1>No images in the Media Library.</h1>';
		}


		/**
		 * Print out the details
		 */
		if ( ! empty( $this->post_info['message'] ) ) {
			echo '<p style="margin-top: 0;">' . lct_return( $this->post_info['message'] ) . '</p>';
		}


		if ( ! empty( $this->post_info['image_sizes'] ) ) {
			echo '<h2 style="margin-bottom: 0;text-decoration: underline;">All image sizes logged in DB</h2>';
			echo '<p style="margin-top: 0;">';
			foreach ( $this->post_info['image_sizes'] as $k => $v ) {
				echo $k . '<br />';
			}
			echo '</p>';
		}


		if ( ! empty( $this->post_info['image_sizes_managed'] ) ) {
			echo '<h2 style="margin-bottom: 0;text-decoration: underline;">Managed image sizes</h2>';
			echo '<p style="margin-top: 0;">';
			foreach ( $this->post_info['image_sizes_managed'] as $k => $info ) {
				echo $k . ' :: ' . $info['label'] . ' [' . $info['count'] . ']<br />';
			}
			echo '</p>';
		}


		if ( ! empty( $this->post_info['image_sizes_disabled'] ) ) {
			echo '<h2 style="margin-bottom: 0;text-decoration: underline;">Disabled image sizes that need to be removed</h2>';
			echo '<p style="margin-top: 0;">';
			foreach ( $this->post_info['image_sizes_disabled'] as $k => $v ) {
				echo $k . '<br />';
			}
			echo '</p>';
		}


		if ( ! empty( $this->post_info['disabled_images_note'] ) ) {
			echo '<h2 style="margin-bottom: 0;text-decoration: underline;">Checking disabled images...</h2>';
			echo '<p style="margin-top: 0;">' . lct_return( $this->post_info['disabled_images_note'], '<br />' ) . '</p>';
			echo '<h3 style="margin-bottom: 0;color: green;">Disabled images are now cleaned up!</h3>';
		}


		if ( ! empty( $this->post_info['none_images'] ) ) {
			echo '<h2 style="margin-bottom: 0;text-decoration: underline;">Non-image Files</h2>';
			echo '<p style="margin-top: 0;">';
			foreach ( $this->post_info['none_images'] as $v ) {
				echo $v->ID . ' &mdash; ' . $v->post_mime_type . ' &mdash; ' . $v->post_title . '<br />';
			}
			echo '</p>';
		}


		echo '<h1 style="color: blue;">ALL Done!</h1>';
	}


	/**
	 * Get a list of all the files in a folder
	 *
	 * @param        $dir
	 * @param array  $excluded
	 * @param array  $excluded_folders
	 * @param string $root_dir
	 * @param array  $files
	 *
	 * @return array
	 * @since    2018.64
	 * @verified 2018.09.27
	 */
	function get_all_upload_files( $dir, $excluded = [], $excluded_folders = [], $root_dir = '', $files = [] )
	{
		$iterator = new DirectoryIterator( $dir );


		foreach ( $iterator as $info ) {
			if ( $info->isFile() ) {
				//exclude some dirs
				if ( strpos_array( lct_static_cleaner( $info->getPathname() ), $excluded ) ) {
					continue;
				}


				$files[] = str_replace( [ '//', $root_dir ], [ '/' ], lct_static_cleaner( $info->getPathname() ) );
			} elseif ( ! $info->isDot() ) {
				//exclude some dirs
				if ( strpos_array( lct_static_cleaner( $info->getPathname() ), $excluded ) ) {
					continue;
				}
				if ( strpos_array( lct_static_cleaner( $info->getPathname() ), $excluded_folders ) ) {
					continue;
				}


				$files = $this->get_all_upload_files( $dir . DIRECTORY_SEPARATOR . $info->__toString(), $excluded, $excluded_folders, $root_dir, $files );
			}
		}


		return $files;
	}


	/**
	 * See if a file name exists in uploads
	 *
	 * @param $haystack
	 * @param $needle
	 *
	 * @return bool|int|string
	 * @since    2018.65
	 * @verified 2018.10.06
	 */
	function check_against_uploads( $haystack, $needle )
	{
		foreach ( $haystack as $k => $hay ) {
			if ( strpos( $hay, $needle ) !== false ) {
				return $k;
			}
		}


		return false;
	}


	/**
	 * Repair ACF User Meta Data
	 * old_useful_menu
	 *
	 * @since    5.40
	 * @verified 2017.06.13
	 */
	function lct_repair_acf_usermeta()
	{
		$report = [
			'no_change' => [],
			'changed'   => [],
			'failed'    => []
		];


		/**
		 * Set the users we want to check
		 */
		$args    = [
			'orderby' => 'ID',
		];
		$objects = get_users( $args );


		/**
		 * Loop through users
		 */
		if ( ! empty( $objects ) ) {
			foreach ( $objects as $object ) {
				$fields = lct_acf_get_field_groups_fields( [ 'user_id' => $object->ID ] );


				/**
				 * Loop through fields
				 */
				if ( ! empty( $fields ) ) {
					foreach ( $fields as $field ) {
						$key_exists = get_user_meta( $object->ID, $field['name'] );


						/**
						 * This field is not stored in the DB yet
						 */
						if (
							empty( $field['name'] )
							|| empty( $key_exists )
							|| $key_exists === ''
						) {
							continue;
						}


						/**
						 * Set the base info
						 */
						$field_key     = $field['key'];
						$current_key   = get_user_meta( $object->ID, lct_pre_us( $field['name'] ), true );
						$current_value = get_user_meta( $object->ID, $field['name'], true );


						/**
						 * Update repeaters
						 */
						if ( $field['type'] == 'repeater' ) {
							$report = $this->repair_acf_repeater_metadata( $report, 'user', $current_value, $object, $field );


							continue;
						}


						/**
						 * Update clones
						 */
						if ( ! empty( $field['_clone'] ) ) {
							$field_key = $field['__key'];


							/**
							 * Delete any lingering clone info in the DB
							 */
							delete_field( $field['_clone'], lct_u( $object ) );
						}


						/**
						 * There is no key saved in the DB
						 */
						if ( ! $current_key ) {
							update_field( $field_key, $current_value, lct_u( $object ) );


							$report['changed'][] = "{$object->ID} ({$object->display_name}) :: {$field['key']} :: {$field['name']}";


							/**
							 * There is a key saved in the DB
							 */
						} else {
							/**
							 * Keys match!
							 */
							if ( $current_key == $field_key ) {
								$report['no_change'][] = "{$object->ID} ({$object->display_name}) :: {$field['key']} :: {$field['name']}";


								/**
								 * Keys DON'T match, this is a serious problem
								 */
							} else {
								$report['failed'][] = "{$object->ID} ({$object->display_name}) :: SHOULD BE <strong>{$field['key']}</strong> BUT IS SET TO <strong>{$current_key}</strong> :: {$field['name']}";
							}
						}
					}
				}
			}
		}


		echo '<h3>Repair ACF User Meta Data</h3>';


		echo '<h1 style="color: red;">Users :: Failed (Review Manually)</h1>';

		if ( ! empty( $report['failed'] ) ) {
			echo '<p>' . lct_return( array_unique( $report['failed'] ), '<br />' ) . '</p>';
		} else {
			echo '<h2 style="color: green;">None</h2>';
		}


		echo '<h1 style="color: orange;margin-top: 40px;">Users :: Changed from ---EMPTY---</h1>';

		if ( ! empty( $report['changed'] ) ) {
			echo '<p>' . lct_return( array_unique( $report['changed'] ), '<br />' ) . '</p>';
		} else {
			echo '<h2 style="color: green;">None</h2>';
		}


		echo '<h1 style="color: green;margin-top: 40px;">Users :: No Change</h1>';

		if ( ! empty( $report['no_change'] ) ) {
			echo '<p>' . lct_return( array_unique( $report['no_change'] ), '<br />' ) . '</p>';
		} else {
			echo '<h2 style="color: green;">None</h2>';
		}


		echo '<h1>Done</h1>';
	}


	/**
	 * Repair ACF Post Meta Data
	 * old_useful_menu
	 *
	 * @since    5.40
	 * @verified 2017.06.13
	 */
	function lct_repair_acf_postmeta()
	{
		$post_types = [];

		$report = [
			'no_change' => [],
			'changed'   => [],
			'failed'    => []
		];

		$hidden_post_types = [
			'acf-field-group',
			'acf-field',
			'revision',
			'shop_webhook',
		];


		/**
		 * Set the post_types we want to check
		 */
		foreach ( get_post_types() as $post_type ) {
			if ( ! in_array( $post_type, $hidden_post_types ) ) {
				$post_types[] = $post_type;
			}
		}


		/**
		 * Loop through post_types
		 */
		foreach ( $post_types as $post_type ) {
			$args    = [
				'posts_per_page'         => - 1,
				'post_type'              => $post_type,
				'post_status'            => 'any',
				'cache_results'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			];
			$objects = get_posts( $args );


			/**
			 * Loop through posts
			 */
			if ( ! empty( $objects ) ) {
				foreach ( $objects as $object ) {
					$groups_fields_args = [ 'post_id' => $object->ID ];
					$org                = get_field( lct_org(), $object->ID );


					if ( $org ) {
						$groups_fields_args[ lct_org() ] = [ $org ];
					}


					$fields = lct_acf_get_field_groups_fields( $groups_fields_args );


					/**
					 * Loop through fields
					 */
					if ( ! empty( $fields ) ) {
						foreach ( $fields as $field ) {
							$key_exists = get_post_meta( $object->ID, $field['name'] );


							/**
							 * This field is not stored in the DB yet
							 */
							if (
								empty( $field['name'] )
								|| empty( $key_exists )
								|| $key_exists === ''
							) {
								continue;
							}


							/**
							 * Set the base info
							 */
							$field_key     = $field['key'];
							$current_key   = get_post_meta( $object->ID, lct_pre_us( $field['name'] ), true );
							$current_value = get_post_meta( $object->ID, $field['name'], true );


							/**
							 * Update repeaters
							 */
							if ( $field['type'] == 'repeater' ) {
								$report = $this->repair_acf_repeater_metadata( $report, 'post', $current_value, $object, $field );


								continue;
							}


							/**
							 * Update taxonomy relationships
							 */
							$this->repair_acf_taxonomy_relationships( $object, $current_value, $field );


							/**
							 * Update clones
							 */
							if ( ! empty( $field['_clone'] ) ) {
								$field_key = $field['__key'];


								/**
								 * Delete any lingering clone info in the DB
								 */
								delete_field( $field['_clone'], $object->ID );
							}


							/**
							 * There is no key saved in the DB
							 */
							if ( ! $current_key ) {
								update_field( $field_key, $current_value, $object->ID );


								$report['changed'][] = "{$object->ID} ({$object->post_type}: {$object->post_title}) :: {$field_key} :: {$field['name']}";


								/**
								 * There is a key saved in the DB
								 */
							} else {
								/**
								 * Keys match!
								 */
								if ( $current_key == $field_key ) {
									$report['no_change'][] = "{$object->ID} ({$object->post_type}: {$object->post_title}) :: {$field_key} :: {$field['name']}";


									/**
									 * Keys DON'T match, this is a serious problem
									 */
								} else {
									$report['failed'][] = "{$object->ID} ({$object->post_type}: {$object->post_title}) :: SHOULD BE <strong>{$field_key}</strong> BUT IS SET TO <strong>{$current_key}</strong> :: {$field['name']}";
								}
							}
						}
					}
				}
			}
		}


		echo '<h3>Repair ACF Post Meta Data</h3>';


		echo '<h1 style="color: red;">Posts :: Failed (Review Manually)</h1>';

		if ( ! empty( $report['failed'] ) ) {
			echo '<p>' . lct_return( array_unique( $report['failed'] ), '<br />' ) . '</p>';
		} else {
			echo '<h2 style="color: green;">None</h2>';
		}


		echo '<h1 style="color: orange;margin-top: 40px;">Posts :: Changed from ---EMPTY---</h1>';

		if ( ! empty( $report['changed'] ) ) {
			echo '<p>' . lct_return( array_unique( $report['changed'] ), '<br />' ) . '</p>';
		} else {
			echo '<h2 style="color: green;">None</h2>';
		}


		echo '<h1 style="color: green;margin-top: 40px;">Posts :: No Change</h1>';

		if ( ! empty( $report['no_change'] ) ) {
			echo '<p>' . lct_return( array_unique( $report['no_change'] ), '<br />' ) . '</p>';
		} else {
			echo '<h2 style="color: green;">None</h2>';
		}


		echo '<h1>Done</h1>';
	}


	/**
	 * Repair ACF User Meta Data
	 * old_useful_menu
	 *
	 * @since    5.40
	 * @verified 2018.04.12
	 */
	function lct_repair_acf_termmeta()
	{
		$report              = [
			'no_change' => [],
			'changed'   => [],
			'failed'    => []
		];
		$allowed_key_changes = apply_filters( 'lct/repair_acf_repeater_metadata/allowed_key_changes', [] );


		/**
		 * Set the taxonomies we want to check
		 */
		$args    = [
			'taxonomy'   => get_taxonomies(),
			'hide_empty' => false,
			'orderby'    => 'term_id',
		];
		$objects = get_terms( $args );


		/**
		 * Loop through terms
		 */
		if ( ! empty( $objects ) ) {
			foreach ( $objects as $object ) {
				$fields = lct_acf_get_field_groups_fields( [ 'taxonomy' => $object->taxonomy ] );


				/**
				 * Loop through fields
				 */
				if ( ! empty( $fields ) ) {
					foreach ( $fields as $field ) {
						$key_exists = get_term_meta( $object->term_id, $field['name'] );


						/**
						 * This field is not stored in the DB yet
						 */
						if (
							empty( $field['name'] )
							|| empty( $key_exists )
							|| $key_exists === ''
						) {
							continue;
						}


						/**
						 * Set the base info
						 */
						$field_key     = $field['key'];
						$current_key   = get_term_meta( $object->term_id, lct_pre_us( $field['name'] ), true );
						$current_value = get_term_meta( $object->term_id, $field['name'], true );


						/**
						 * Update repeaters
						 */
						if ( $field['type'] == 'repeater' ) {
							$report = $this->repair_acf_repeater_metadata( $report, 'term', $current_value, $object, $field );


							continue;
						}


						/**
						 * Update clones
						 */
						if ( ! empty( $field['_clone'] ) ) {
							$field_key = $field['__key'];


							/**
							 * Delete any lingering clone info in the DB
							 */
							delete_field( $field['_clone'], lct_t( $object ) );
						}


						/**
						 * There is no key saved in the DB
						 */
						if ( ! $current_key ) {
							update_field( $field_key, $current_value, lct_t( $object ) );

							delete_option( $object->taxonomy . '_' . $object->term_id . '_' . $field['name'] );
							delete_option( lct_pre_us( $object->taxonomy . '_' . $object->term_id . '_' . $field['name'] ) );


							$report['changed'][] = "{$object->term_id} ({$object->name}) :: {$field['key']} :: {$field['name']}";


							/**
							 * There is a key saved in the DB
							 */
						} else {
							/**
							 * Keys match!
							 */
							if ( $current_key == $field_key ) {
								$report['no_change'][] = "{$object->term_id} ({$object->name}) :: {$field['key']} :: {$field['name']}";


								delete_option( $object->taxonomy . '_' . $object->term_id . '_' . $field['name'] );
								delete_option( lct_pre_us( $object->taxonomy . '_' . $object->term_id . '_' . $field['name'] ) );


								/**
								 * Keys DON'T match, this is a serious problem
								 */
							} else {
								$changed_no_match = false;


								if (
									! empty( $allowed_key_changes )
									&& isset( $allowed_key_changes[ $field_key ] )
									&& $allowed_key_changes[ $field_key ] === $current_key
								) {
									update_field( $field_key, $current_value, lct_t( $object ) );

									delete_option( $object->taxonomy . '_' . $object->term_id . '_' . $field['name'] );
									delete_option( lct_pre_us( $object->taxonomy . '_' . $object->term_id . '_' . $field['name'] ) );


									$changed_no_match = true;
								}


								if ( $changed_no_match ) {
									$report['changed'][] = "{$object->term_id} ({$object->name}) :: {$field['key']} :: {$field['name']}";
								} else {
									$report['failed'][] = "{$object->term_id} ({$object->name}) :: SHOULD BE <strong>{$field['key']}</strong> BUT IS SET TO <strong>{$current_key}</strong> :: {$field['name']}";
								}
							}
						}
					}
				}
			}
		}


		echo '<h3>Repair ACF Term Meta Data</h3>';


		echo '<h1 style="color: red;">Terms :: Failed (Review Manually)</h1>';

		if ( ! empty( $report['failed'] ) ) {
			echo '<p>' . lct_return( array_unique( $report['failed'] ), '<br />' ) . '</p>';
		} else {
			echo '<h2 style="color: green;">None</h2>';
		}


		echo '<h1 style="color: orange;margin-top: 40px;">Terms :: Changed from ---EMPTY---</h1>';

		if ( ! empty( $report['changed'] ) ) {
			echo '<p>' . lct_return( array_unique( $report['changed'] ), '<br />' ) . '</p>';
		} else {
			echo '<h2 style="color: green;">None</h2>';
		}


		echo '<h1 style="color: green;margin-top: 40px;">Terms :: No Change</h1>';

		if ( ! empty( $report['no_change'] ) ) {
			echo '<p>' . lct_return( array_unique( $report['no_change'] ), '<br />' ) . '</p>';
		} else {
			echo '<h2 style="color: green;">None</h2>';
		}


		echo '<h1>Done</h1>';
	}


	/**
	 * Repeaters need to be repaired a little differently
	 * //TODO: cs - Need to expand this to handle nested repeaters - 10/19/2016 04:48 PM
	 *
	 * @param array $report
	 * @param       $type
	 * @param       $rows
	 * @param       $object
	 * @param       $parent_field
	 *
	 * @return array
	 * @since    7.19
	 * @verified 2022.01.06
	 */
	function repair_acf_repeater_metadata( $report, $type, $rows, $object, $parent_field )
	{
		$changed = 0;


		/**
		 * Loop through sub_fields
		 */
		if ( ! empty( $parent_field['sub_fields'] ) ) {
			foreach ( $parent_field['sub_fields'] as $field ) {
				/**
				 * Loop through each repeater of the sub_field
				 */
				for ( $i = 0; $i < (int) $rows; $i ++ ) {
					$field_name = $parent_field['name'] . '_' . $i . '_' . $field['name'];
					$key_exists = get_metadata( $type, $object->ID, $field_name );


					/**
					 * This field is not stored in the DB yet
					 */
					if (
						$field['type'] == 'repeater'
						|| empty( $field['name'] )
						|| empty( $key_exists )
						|| $key_exists === ''
					) {
						continue;
					}


					/**
					 * Set the base info
					 */
					$field_key     = $field['key'];
					$current_key   = get_metadata( $type, $object->ID, lct_pre_us( $field_name ), true );
					$current_value = get_metadata( $type, $object->ID, $field_name, true );


					/**
					 * Update taxonomy relationships
					 */
					$this->repair_acf_taxonomy_relationships( $object, $current_value, $field );


					/**
					 * Update clones
					 */
					if ( ! empty( $field['_clone'] ) ) {
						$field_key = $field['__key'];


						/**
						 * Delete any lingering clone info in the DB
						 */
						delete_field( $field['_clone'], $object->ID );
					}


					/**
					 * There is no key saved in the DB
					 */
					if ( ! $current_key ) {
						update_metadata( $type, $object->ID, $field_name, $current_value );
						update_metadata( $type, $object->ID, lct_pre_us( $field_name ), $field_key );


						$report['changed'][] = "{$object->ID} (sub_field: {$field_name}) :: {$field_key} :: {$field['name']}";


						$changed ++;


						/**
						 * There is a key saved in the DB
						 */
					} else {
						/**
						 * Keys match!
						 */
						if ( $current_key == $field_key ) {
							$report['no_change'][] = "{$object->ID} (sub_field: {$field_name}) :: {$field_key} :: {$field['name']}";


							/**
							 * Keys DON'T match, this is a serious problem
							 */
						} else {
							$report['failed'][] = "{$object->ID} (sub_field: {$field_name}) :: {$field_key} :: SHOULD BE <strong>{$field_key}</strong> BUT IS SET TO <strong>{$current_key}</strong> :: {$field['name']}";
						}
					}
				}
			}


			update_metadata( $type, $object->ID, lct_pre_us( $parent_field['name'] ), $parent_field['key'] );


			if ( $changed ) {
				$report['changed'][] = "{$object->ID} (REPEATER PARENT: {$parent_field['name']}) :: {$parent_field['key']} :: {$parent_field['name']}";
			}
		}


		return $report;
	}


	/**
	 * Sometimes the relationships get messed up, let's check them just to be sure
	 *
	 * @param $object
	 * @param $current_value
	 * @param $field
	 *
	 * @since    7.19
	 * @verified 2017.06.13
	 */
	function repair_acf_taxonomy_relationships( $object, $current_value, $field )
	{
		if (
			$field['type'] == 'taxonomy'
			&& isset( $field['save_terms'] )
			&& $field['save_terms']
			&& $current_value
		) {
			$terms = [];


			if ( ! is_array( $current_value ) ) {
				$value = [ $current_value ];
			} else {
				$value = $current_value;
			}


			foreach ( $value as $v ) {
				$int = (int) $v;


				if ( $int ) {
					$terms[] = $int;
				}
			}


			wp_delete_object_term_relationships( $object->ID, $field['taxonomy'] );
			wp_set_object_terms( $object->ID, $terms, $field['taxonomy'] );
		}
	}


	/**
	 * Sometimes the term counts get out of whack. This will set 'em straight
	 *
	 * @since    7.19
	 * @verified 2023.09.11
	 */
	function lct_repair_term_counts()
	{
		$report     = [
			'no_change' => [],
			'changed'   => [],
			'failed'    => [],
			'empty'     => [],
			'empty_ids' => [],
		];
		$taxonomies = get_taxonomies();


		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				if ( $taxonomy === 'xbs_customer_address' ) {
					continue;
				}


				$args  = [
					'taxonomy'   => $taxonomy,
					'hide_empty' => false,
					'orderby'    => 'term_id',
				];
				$terms = get_terms( $args );


				if ( ! lct_is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$old_term_count = $term->count;

						wp_update_term_count_now( [ $term->term_taxonomy_id ], $taxonomy );

						$new_term = get_term( $term->term_id, $taxonomy );

						if ( $old_term_count != $new_term->count ) {
							$report['changed'][] = "{$taxonomy} :: {$term->term_id} :: {$term->name} :: Old Count: {$old_term_count} :: New Count: {$new_term->count}";
						} else {
							$report['no_change'][] = "{$taxonomy} :: {$term->term_id} :: {$term->name}";
						}

						if (
							! in_array( $taxonomy, [ 'product_type', 'bp-email-type', 'nav_menu' ] )
							&& $new_term->count === 0

						) {
							$report['empty'][]                  = "{$taxonomy} :: {$term->term_id} :: {$term->name}";
							$report['empty_ids'][ $taxonomy ][] = $term->term_id;
						}
					}
				}
			}
		}


		echo '<h3>Repair Term Counts</h3>';


		echo '<h1 style="color: red;">Terms :: Failed (Review Manually)</h1>';

		if ( ! empty( $report['failed'] ) ) {
			echo '<p>' . lct_return( array_unique( $report['failed'] ), '<br />' ) . '</p>';
		} else {
			echo '<h2 style="color: green;">None</h2>';
		}


		echo '<h1 style="color: orange;margin-top: 40px;">Terms :: Changed</h1>';

		if ( ! empty( $report['changed'] ) ) {
			echo '<p>' . lct_return( array_unique( $report['changed'] ), '<br />' ) . '</p>';
		} else {
			echo '<h2 style="color: green;">None</h2>';
		}


		echo '<h1 style="color: green;margin-top: 40px;">Terms :: No Change</h1>';

		if ( ! empty( $report['no_change'] ) ) {
			//echo '<p>' . lct_return( array_unique( $report['no_change'] ), '<br />' ) . '</p>';
			echo '<h2 style="color: green;">Hidden in hard code</h2>';
		} else {
			echo '<h2 style="color: green;">None</h2>';
		}


		echo '<h1 style="color: green;margin-top: 40px;">Terms :: Empty</h1>';

		if ( ! empty( $report['empty'] ) ) {
			if ( 1 === 1 ) {
				echo '<h2 style="color: green;">Hidden in hard code</h2>';
			} else {
				echo '<p>' . lct_return( array_unique( $report['empty'] ), '<br />' ) . '</p>';

				foreach ( $report['empty_ids'] as $taxonomy => $ids ) {
					echo '<p>' . $taxonomy . ': ' . lct_return( array_unique( $ids ), ',' ) . '</p>';
					echo '<p>
						<a href="' . admin_url( 'admin.php?page=' . zxzu( 'delete_empty_terms' ) . '&delete_empty_terms=' . $taxonomy ) . '" class="button button-primary" target="_blank">Delete Empty ' . $taxonomy . '</a>
					</p>';
				}
			}
		} else {
			echo '<h2 style="color: green;">None</h2>';
		}


		echo '<h1>Done</h1>';
	}


	/**
	 * Delete unneeded terms
	 *
	 * @since    7.19
	 * @verified 2016.10.19
	 */
	function lct_delete_empty_terms()
	{
		$taxonomy = $_GET['delete_empty_terms'];


		$args  = [
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
			'orderby'    => 'term_id',
		];
		$terms = get_terms( $args );


		if ( ! lct_is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( $term->count === 0 ) {
					wp_delete_term( $term->term_id, $taxonomy );
				}
			}
		}


		echo '<h1>Done</h1>';
	}


	/**
	 * Produce a list of everything we want to change
	 * //TODO: cs - Add wp-content - 05/18/2016 02:24 PM
	 * //TODO: cs - Add revslider - 05/18/2016 02:24 PM
	 *
	 * @return array
	 * @since    5.40
	 * @verified 2021.07.06
	 */
	function lct_get_post_content_fnr()
	{
		$find_replace = [];

		$find_replace_hosts = [
			$this->siteurl_host_non_www,
			"new.{$this->siteurl_host_non_www}",
		];

		if ( $this->clientzz ) {
			$find_replace_hosts[] = "{$this->siteurl_host_non_www}.{$this->clientzz}.eetah.com";
		}

		if ( lct_acf_get_option_raw( 'has_custom_dev_host' ) ) {
			$find_replace_hosts[] = lct_acf_get_option( 'custom_dev_host' );
		}

		if ( lct_acf_get_option_raw( 'has_custom_sb_host' ) ) {
			$find_replace_hosts[] = lct_acf_get_option( 'custom_sb_host' );
		}

		$find_replace_prefixes = [
			"href=\"http://www."              => 'href="',
			"href=\"http://"                  => 'href="',
			"href=\"https://www."             => 'href="',
			"href=\"https://"                 => 'href="',
			"href=\"//www."                   => 'href="',
			"href=\"//"                       => 'href="',
			"src=\"http://www."               => 'src="',
			"src=\"http://"                   => 'src="',
			"src=\"https://www."              => 'src="',
			"src=\"https://"                  => 'src="',
			"src=\"//www."                    => 'src="',
			"src=\"//"                        => 'src="',
			"background_image=\"http://www."  => 'background_image="',
			"background_image=\"http://"      => 'background_image="',
			"background_image=\"https://www." => 'background_image="',
			"background_image=\"https://"     => 'background_image="',
			"background_image=\"//www."       => 'background_image="',
			"background_image=\"//"           => 'background_image="',
			"=\"http://www."                  => '="',
			"=\"http://"                      => '="',
			"=\"https://www."                 => '="',
			"=\"https://"                     => '="',
			"=\"//www."                       => '="',
			"=\"//"                           => '="',

			"href='http://www."              => 'href=\'',
			"href='http://"                  => 'href=\'',
			"href='https://www."             => 'href=\'',
			"href='https://"                 => 'href=\'',
			"href='//www."                   => 'href=\'',
			"href='//"                       => 'href=\'',
			"src='http://www."               => 'src=\'',
			"src='http://"                   => 'src=\'',
			"src='https://www."              => 'src=\'',
			"src='https://"                  => 'src=\'',
			"src='//www."                    => 'src=\'',
			"src='//"                        => 'src=\'',
			"background_image='http://www."  => 'background_image=\'',
			"background_image='http://"      => 'background_image=\'',
			"background_image='https://www." => 'background_image=\'',
			"background_image='https://"     => 'background_image=\'',
			"background_image='//www."       => 'background_image=\'',
			"background_image='//"           => 'background_image=\'',
			"='http://www."                  => '=\'',
			"='http://"                      => '=\'',
			"='https://www."                 => '=\'',
			"='https://"                     => '=\'',
			"='//www."                       => '=\'',
			"='//"                           => '=\'',


			"]http://www."  => ']',
			"]http://"      => ']',
			"]https://www." => ']',
			"]https://"     => ']',
			"]//www."       => ']',
			"]//"           => ']',
		];


		foreach ( $find_replace_hosts as $find_replace_host ) {
			foreach ( $find_replace_prefixes as $find_replace_prefix_key => $find_replace_prefix_value ) {
				$find_replace_prefix_key                  = "{$find_replace_prefix_key}{$find_replace_host}/";
				$find_replace[ $find_replace_prefix_key ] = "{$find_replace_prefix_value}/";
			}
		}


		return lct_create_find_and_replace_arrays( $find_replace );
	}


	/**
	 * Updates the guid of a page or post you are saving
	 *
	 * @param      $post
	 * @param bool $single
	 *
	 * @since    5.40
	 * @verified 2018.02.28
	 */
	function cleanup_guid_post_content( $post, $single = false )
	{
		if ( $single == true ) {
			$this->startup_cleanup_guid();
		}


		if ( ! is_object( $post ) ) {
			$post = get_post( $post );
		}


		//TODO: cs - Need to make sure we still list these links - 08/17/2016 01:27 PM
		if (
			lct_is_wp_error( $post )
			|| strpos( $this->post_post_content, '//' ) === 0
			|| ! in_array( $post->post_type, $this->post_types )
			|| get_post_meta( $post->ID, zxzacf( 'dont_check_page_links' ), true )
			|| defined( 'LCT_DONT_CHECK_LINKS' )
		) {
			return;
		}


		$type                    = 'post_content';
		$update_success[ $type ] = false;
		$post_content_new        = $this->post_post_content;
		$post_type               = $post->post_type;
		$wp_slash                = false;


		if ( $post_content_new != stripslashes_deep( $post_content_new ) ) {
			$post_content_new = stripslashes_deep( $post_content_new );
			$wp_slash         = true;
		}


		//TODO: cs - Need to come up with a way to handle serialized data - 05/18/2016 02:10 PM
		if (
			(
				strpos( $post_content_new, $this->siteurl_host_non_www ) !== false
				|| strpos( $post_content_new, 'homeurl' ) !== false
				|| (
					lct_acf_get_option_raw( 'has_custom_dev_host' )
					&& strpos( $post_content_new, lct_acf_get_option( 'custom_dev_host' ) ) !== false
				)
				|| (
					lct_acf_get_option_raw( 'has_custom_sb_host' )
					&& strpos( $post_content_new, lct_acf_get_option( 'custom_sb_host' ) ) !== false
				)
			)
			&& ! is_serialized( $post_content_new )
		) {
			$post_content_new = str_replace( $this->post_content_fnr['find'], $this->post_content_fnr['replace'], $post_content_new );
		}


		if ( $wp_slash ) {
			$post_content_new = wp_slash( $post_content_new );
		}


		if (
			$post_content_new != $this->post_post_content
			&& ! $single
		) {
			$args                    = [
				'ID'           => $post->ID,
				'post_content' => $post_content_new,
			];
			$update_success[ $type ] = wp_update_post( $args );
		}


		if ( $update_success[ $type ] ) {
			$this->post_info[ $type ][] = sprintf(
				'<strong><a href="%s" target="_blank">%s (%s)</a>:</strong> Post_content is now updated for <a href="%s" target="_blank">%s</a>',
				get_edit_post_link( $post->ID ),
				$post->ID,
				$post_type,
				get_the_permalink( $post ),
				get_the_title( $post )
			);
		}


		//Just in case we need this later
		$this->post_post_content = $post_content_new;
	}


	/**
	 * Updates the internal links inside the post_content of a page or post you are saving
	 *
	 * @param      $post
	 * @param bool $single
	 *
	 * @since    5.40
	 * @verified 2016.09.29
	 */
	function cleanup_guid_existing_link_sc_check( $post, $single = false )
	{
		if ( $single == true ) {
			$this->startup_cleanup_guid();
		}


		if ( ! is_object( $post ) ) {
			$post = get_post( $post );
		}


		//TODO: cs - Need to make sure we still list these links - 08/17/2016 01:27 PM
		if (
			lct_is_wp_error( $post )
			|| strpos( $this->post_post_content, '[link' ) === false
			|| get_post_meta( $post->ID, zxzacf( 'dont_check_page_links' ), true )
			|| defined( 'LCT_DONT_CHECK_LINKS' )
		) {
			return;
		}


		$type                    = 'existing_link_sc_check';
		$update_success[ $type ] = false;
		$post_content_new        = $this->post_post_content;
		$post_type               = $post->post_type;
		$wp_slash                = false;
		$bad_ids                 = [];


		if ( $post_content_new != stripslashes_deep( $post_content_new ) ) {
			$post_content_new = stripslashes_deep( $post_content_new );
			$wp_slash         = true;
		}


		//TODO: cs - Need to come up with a way to handle serialized data - 05/18/2016 02:10 PM
		if (
			strpos( $post_content_new, '[link' ) !== false
			&& ! is_serialized( $post_content_new )
		) {
			preg_match_all( '/\[link(.*?)]/s', $post_content_new, $links_sc );
			preg_match_all( '/{{{{link(.*?)}}}}/', $post_content_new, $links_sc_nested_4 );
			preg_match_all( '/{{{link(.*?)}}}/', $post_content_new, $links_sc_nested_3 );
			preg_match_all( '/{{link(.*?)}}/', $post_content_new, $links_sc_nested_2 );

			$links_sc[0] = array_merge( $links_sc[0], $links_sc_nested_4[0], $links_sc_nested_3[0], $links_sc_nested_2[0] );
			$links_sc[1] = array_merge( $links_sc[1], $links_sc_nested_4[1], $links_sc_nested_3[1], $links_sc_nested_2[1] );
			$links_sc[2] = $links_sc[0];


			if ( ! empty( $links_sc[0] ) ) {
				foreach ( $links_sc[0] as $link_key => $link ) {
					$link_atts = shortcode_parse_atts( $links_sc[1][ $link_key ] );


					if (
						! empty( $link_atts['id'] )
						&& strpos( $link_atts['id'], 'PageRemoved_' ) === false
					) {
						$link_post = get_post( $link_atts['id'] );


						//TODO: cs - Need to check for deleted taxonomies - 05/24/2016 04:05 PM
						if (
							empty( $link_post )
							&& empty( $link_atts['taxonomy'] )
						) {
							$links_sc[2][ $link_key ] = str_replace( [ "'{$link_atts['id']}'", "\"{$link_atts['id']}\"", "{$link_atts['id']}" ], [ "'PageRemoved_zzIDzz'", "'PageRemoved_zzIDzz'", "'PageRemoved_zzIDzz'" ], $links_sc[2][ $link_key ] );
							$links_sc[2][ $link_key ] = str_replace( 'zzIDzz', $link_atts['id'], $links_sc[2][ $link_key ] );

							$link_atts['id'] = "PageRemoved_{$link_atts['id']}";
						}
					}


					if ( strpos( $link_atts['id'], 'PageRemoved_' ) !== false ) {
						$old_id      = $link_atts['id'];
						$path_object = $this->check_redirection_items( $link_atts['id'] );

						if ( ! empty( $path_object ) ) {
							$link_atts['id'] = $path_object->ID;

							$links_sc[2][ $link_key ] = str_replace( $old_id, $link_atts['id'], $links_sc[2][ $link_key ] );
						} else {
							$bad_ids[ str_replace( 'PageRemoved_', '', $link_atts['id'] ) ] = true;
						}
					}
				}


				$post_content_new = str_replace( $links_sc[0], $links_sc[2], $post_content_new );
			}
		}


		if ( $wp_slash ) {
			$post_content_new = wp_slash( $post_content_new );
		}


		if (
			$post_content_new != $this->post_post_content
			&& ! $single
		) {
			$args                    = [
				'ID'           => $post->ID,
				'post_content' => $post_content_new,
			];
			$update_success[ $type ] = wp_update_post( $args );
		}


		if ( ! empty( $bad_ids ) ) {
			$bad_ids = array_keys( $bad_ids );

			foreach ( $bad_ids as $bad_id ) {
				if ( in_array( $post_type, $this->post_types_excluded ) ) {
					$this->post_info[ $type ][] = sprintf(
						'<strong><a href="%s" target="_blank">%s (%s)</a>:</strong> Post_content has a [link] with ID <strong style="font-size: 130%%;">' . $bad_id . '</strong> that does not exist. <a href="%s" target="_blank">%s</a>',
						get_edit_post_link( $post->ID ),
						$post->ID,
						$post_type,
						get_the_permalink( $post ),
						get_the_title( $post )
					);
				}
			}
		}


		//Just in case we need this later
		$this->post_post_content = $post_content_new;
	}


	/**
	 * Updates the internal links inside the post_content of a page or post you are saving
	 *
	 * @param      $post
	 * @param bool $single
	 *
	 * @since    5.40
	 * @verified 2020.04.24
	 */
	function cleanup_guid_link_cleanup( $post, $single = false )
	{
		if ( $single == true ) {
			$this->startup_cleanup_guid();
		}


		if ( ! is_object( $post ) ) {
			$post = get_post( $post );
		}


		//TODO: cs - Need to make sure we still list these links - 08/17/2016 01:27 PM2
		if (
			lct_is_wp_error( $post )
			|| strpos( $this->post_post_content, '//' ) === 0
			|| get_post_meta( $post->ID, zxzacf( 'dont_check_page_links' ), true )
			|| defined( 'LCT_DONT_CHECK_LINKS' )
		) {
			return;
		}


		$type                       = 'link_cleanup';
		$update_success[ $type ]    = false;
		$preg_delimiter             = '~~~~~';
		$link_cleanup_new           = str_replace( [ '</a>', 'link=' ], [ "</a>{$preg_delimiter}\n", "\n{$preg_delimiter}link=" ], $this->post_post_content );
		$post_type                  = $post->post_type;
		$check_for_nested_shortcode = [];
		$wp_slash                   = false;

		$excluded_shortcodes = [
			'link',
			'fusion_text',
			'fullwidth',
			'one_full',
			'five_sixth',
			'four_fifth',
			'three_fourth',
			'two_third',
			'three_fifth',
			'one_half',
			'two_fifth',
			'one_third',
			'one_fourth',
			'one_fifth',
			'one_sixth',
		];


		if ( $link_cleanup_new != stripslashes_deep( $link_cleanup_new ) ) {
			$link_cleanup_new = stripslashes_deep( $link_cleanup_new );
			$wp_slash         = true;
		}


		//TODO: cs - Need to come up with a way to handle serialized data - 05/18/2016 02:10 PM
		if (
			(
				strpos( $link_cleanup_new, 'href="/' ) !== false
				|| strpos( $link_cleanup_new, 'href=\'/' ) !== false
				|| strpos( $link_cleanup_new, 'link="/' ) !== false
				|| strpos( $link_cleanup_new, 'link=\'/' ) !== false
			)
			&& ! is_serialized( $link_cleanup_new )
		) {
			preg_match_all( '/<a(.*?)href="\/(.*?)"(.*?)>(.*?)<\/a>' . $preg_delimiter . '/', $link_cleanup_new, $hrefs_double );
			preg_match_all( '/<a(.*?)href=\'\/(.*?)\'(.*?)>(.*?)<\/a>' . $preg_delimiter . '/', $link_cleanup_new, $hrefs_single );
			preg_match_all( '/(.*?)' . $preg_delimiter . 'link="\/(.*?)"/', $link_cleanup_new, $hrefs_link_double );
			preg_match_all( '/(.*?)' . $preg_delimiter . 'link=\'\/(.*?)\'/', $link_cleanup_new, $hrefs_link_single );
			preg_match_all( '/\[(.*?)]/s', $link_cleanup_new, $content_shortcodes );

			if ( ! empty( $content_shortcodes[1] ) ) {
				foreach ( $content_shortcodes[1] as $content_shortcode ) {
					$content_shortcode_parts = explode( ' ', $content_shortcode );

					if (
						in_array( $content_shortcode_parts[0], $excluded_shortcodes )
						|| strpos( $content_shortcode, '/' ) === 0
					) {
						continue;
					}

					preg_match_all( '/\[' . $content_shortcode_parts[0] . '(.*?)](.*?)\[\/' . $content_shortcode_parts[0] . ']/s', $link_cleanup_new, $content_shortcodes_inner );

					if ( ! empty( $content_shortcodes_inner[1][0] ) ) {
						foreach ( $content_shortcodes_inner[1] as $content_shortcodes_inner_tmp ) {
							$check_for_nested_shortcode[] = $content_shortcodes_inner_tmp;
						}
					}

					if ( ! empty( $content_shortcodes_inner[2][0] ) ) {
						foreach ( $content_shortcodes_inner[2] as $content_shortcodes_inner_tmp ) {
							$check_for_nested_shortcode[] = $content_shortcodes_inner_tmp;
						}
					}
				}

				$check_for_nested_shortcode = lct_return( $check_for_nested_shortcode );
			}


			if (
				! empty( $hrefs_double )
				|| ! empty( $hrefs_single )
				|| ! empty( $hrefs_link_double )
				|| ! empty( $hrefs_link_single )
			) {
				$path_fnrs = [];


				if ( empty( $hrefs_double[2] ) ) {
					$hrefs_double[0] = [];
					$hrefs_double[1] = [];
					$hrefs_double[2] = [];
					$hrefs_double[3] = [];
					$hrefs_double[4] = [];
				}

				if ( ! empty( $hrefs_single[2] ) ) {
					$hrefs_double[0] = array_merge( $hrefs_double[0], $hrefs_single[0] );
					$hrefs_double[1] = array_merge( $hrefs_double[1], $hrefs_single[1] );
					$hrefs_double[2] = array_merge( $hrefs_double[2], $hrefs_single[2] );
					$hrefs_double[3] = array_merge( $hrefs_double[3], $hrefs_single[3] );
					$hrefs_double[4] = array_merge( $hrefs_double[4], $hrefs_single[4] );
				}

				if ( ! empty( $hrefs_link_double[2] ) ) {
					/** @noinspection PhpUnusedLocalVariableInspection */
					foreach ( $hrefs_link_double[2] as $tmp ) {
						$hrefs_link_double[3][] = 'link';
						$hrefs_link_double[4][] = '';
					}

					$hrefs_double[0] = array_merge( $hrefs_double[0], $hrefs_link_double[0] );
					$hrefs_double[1] = array_merge( $hrefs_double[1], $hrefs_link_double[1] );
					$hrefs_double[2] = array_merge( $hrefs_double[2], $hrefs_link_double[2] );
					$hrefs_double[3] = array_merge( $hrefs_double[3], $hrefs_link_double[3] );
					$hrefs_double[4] = array_merge( $hrefs_double[4], $hrefs_link_double[4] );
				}

				if ( ! empty( $hrefs_link_single[2] ) ) {
					/** @noinspection PhpUnusedLocalVariableInspection */
					foreach ( $hrefs_link_single[2] as $tmp ) {
						$hrefs_link_single[3][] = 'link';
						$hrefs_link_single[4][] = '';
					}

					$hrefs_double[0] = array_merge( $hrefs_double[0], $hrefs_link_single[0] );
					$hrefs_double[1] = array_merge( $hrefs_double[1], $hrefs_link_single[1] );
					$hrefs_double[2] = array_merge( $hrefs_double[2], $hrefs_link_single[2] );
					$hrefs_double[3] = array_merge( $hrefs_double[3], $hrefs_link_single[3] );
					$hrefs_double[4] = array_merge( $hrefs_double[4], $hrefs_link_single[4] );
				}


				foreach ( $hrefs_double[2] as $path_key => $path ) {
					$hrefs_double[0][ $path_key ] = str_replace( $preg_delimiter, '', $hrefs_double[0][ $path_key ] );


					if ( strpos( $path, '//' ) === false ) {
						$link_attrs        = [];
						$link_attrs_string = [];
						$path_object       = get_page_by_path( "/{$path}" );


						if ( empty( $path_object ) ) {
							$path_object = get_page_by_path( "{$path}", 'OBJECT', 'post' );
						}


						if ( empty( $path_object ) ) {
							$path_object = get_page_by_path( "{$path}", 'OBJECT', $this->post_types_excluded );
						}


						if ( strpos( $path, '#' ) !== false ) {
							$path_tmp             = explode( '#', $path );
							$path                 = $path_tmp[0];
							$link_attrs['anchor'] = $path_tmp[1];
						}


						if (
							empty( $path )
							|| in_array( $path, [ 'index.html', 'index.htm', 'index.php' ] )
						) {
							$path_object = get_post( get_option( 'page_on_front' ) );
						}


						if ( empty( $path_object ) ) {
							$path_tmp = explode( '/', rtrim( $path, '/' ) );


							if (
								isset( $path_tmp[1] )
								&& count( $path_tmp ) > 1
							) {
								if ( lct_taxonomy_exists_by_slug( $path_tmp[0] ) ) {
									unset( $path_tmp[0] );

									$path_tmp = implode( '/', $path_tmp );

									$path_object = lct_get_taxonomy_by_path( "/{$path_tmp}/" );


									if ( ! empty( $path_object ) ) {
										$path_object->ID        = $path_object->term_id;
										$link_attrs['taxonomy'] = $path_object->taxonomy;
									}
								} else {
									unset( $path_tmp[0] );

									$path_tmp = implode( '/', $path_tmp );

									$path_object = get_page_by_path( "/{$path_tmp}/", 'OBJECT', $this->post_types_excluded );


									if (
										! empty( $path_object )
										&& $path_object->post_type === 'tribe_rsvp_tickets'
										&& ( $event_id = get_post_meta( $path_object->ID, '_tribe_rsvp_for_event', true ) )
									) {
										$path_object = get_post( $event_id );
									}


									if ( empty( $path_object ) ) {
										$path_object = lct_get_taxonomy_by_path( "/{$path_tmp}/" );


										if ( ! empty( $path_object ) ) {
											$path_object->ID        = $path_object->term_id;
											$link_attrs['taxonomy'] = $path_object->taxonomy;
										}
									}
								}
							}
						}


						if ( empty( $path_object ) ) {
							$path_object = $this->check_redirection_items( $path );
						}


						if ( ! lct_is_wp_error( $path_object ) ) {
							if (
								! empty( $check_for_nested_shortcode )
								&& strpos( $check_for_nested_shortcode, $hrefs_double[0][ $path_key ] ) !== false
							) {
								$shortcode_wrapper = [ 'start' => '{{{{', 'end' => '}}}}' ];
							} else {
								$shortcode_wrapper = [ 'start' => '[', 'end' => ']' ];
							}


							$link_attrs['id']   = $path_object->ID;
							$link_attrs['text'] = $hrefs_double[4][ $path_key ];


							if ( lct_is_html( $link_attrs['text'] ) ) {
								$link_attrs['esc_html'] = 'false';
								$link_attrs['text']     = str_replace( [ '{{{{', '}}}}', '\'', '"' ], [ '{{{', '}}}', '&#39;', '&quot;' ], $link_attrs['text'] );
							} else {
								$link_attrs['text'] = str_replace( [ '{{{{', '}}}}', '\'' ], [ '{{{', '}}}', '&#39;' ], $link_attrs['text'] );
							}

							if ( $hrefs_double[3][ $path_key ] == 'link' ) {
								$hrefs_double[0][ $path_key ] = "link=\"/{$hrefs_double[2][ $path_key ]}\"";
								$link_attrs['url_only']       = 'true';
								$shortcode_wrapper            = [ 'start' => 'link="{{', 'end' => '}}"' ];
								unset( $link_attrs['text'] );
							}

							$link_attrs_old   = [];
							$link_attrs_old_1 = shortcode_parse_atts( $hrefs_double[1][ $path_key ] );
							$link_attrs_old_3 = shortcode_parse_atts( $hrefs_double[3][ $path_key ] );

							if ( ! empty( $link_attrs_old_1 ) ) {
								$link_attrs_old = array_merge( $link_attrs_old, $link_attrs_old_1 );
							}

							if ( ! empty( $link_attrs_old_3 ) ) {
								$link_attrs_old = array_merge( $link_attrs_old, $link_attrs_old_3 );
							}

							if ( ! empty( $link_attrs_old ) ) {
								foreach ( $link_attrs_old as $key => $value ) {
									if ( $key == 'text' ) {
										continue;
									}

									if ( $key == 'id' ) {
										$key = 'selector_id';
									}

									$link_attrs[ $key ] = $value;
								}
							}

							foreach ( $link_attrs as $key => $value ) {
								$link_attrs_string[] = $key . '=\'' . $value . '\'';
							}


							$path_fnrs[ $hrefs_double[0][ $path_key ] ] = $shortcode_wrapper['start'] . 'link ' . lct_return( $link_attrs_string, ' ' ) . $shortcode_wrapper['end'];
						}
					}
				}
			}


			if ( ! empty( $path_fnrs ) ) {
				$link_cleanup_fnr = lct_create_find_and_replace_arrays( $path_fnrs );
				$link_cleanup_new = str_replace( $link_cleanup_fnr['find'], $link_cleanup_fnr['replace'], $link_cleanup_new );
			}
		}


		if ( $wp_slash ) {
			$link_cleanup_new = wp_slash( $link_cleanup_new );
		}


		$link_cleanup_new = str_replace( [ "{$preg_delimiter}\n", "\n{$preg_delimiter}", $preg_delimiter ], [ '', '', '' ], $link_cleanup_new );

		if (
			$link_cleanup_new != $this->post_post_content
			&& ! $single
		) {
			$args                    = [
				'ID'           => $post->ID,
				'post_content' => $link_cleanup_new,
			];
			$update_success[ $type ] = wp_update_post( $args );
		}


		if ( $update_success[ $type ] ) {
			$this->post_info[ $type ][] = sprintf(
				'<strong><a href="%s" target="_blank">%s (%s)</a>:</strong> Internal link cleanup is now completed for <a href="%s" target="_blank">%s</a>',
				get_edit_post_link( $post->ID ),
				$post->ID,
				$post_type,
				get_the_permalink( $post ),
				get_the_title( $post )
			);
		}


		//Just in case we need this later
		$this->post_post_content = $link_cleanup_new;
	}


	/**
	 * Check and Update the redirections' plugin tables
	 *
	 * @param $path
	 *
	 * @return array|object|string|WP_Error|WP_Post
	 * @since    5.40
	 * @verified 2022.01.06
	 */
	function check_redirection_items( $path )
	{
		global $wpdb;


		$path_object = '';


		$redirection_items = $wpdb->get_results( $wpdb->prepare( "SELECT `id`, `url`, `action_data` FROM {$wpdb->prefix}redirection_items WHERE url = %s AND regex = 0 ORDER BY position", '/' . $path ) );

		if ( empty( $redirection_items[0] ) ) {
			$redirection_items = $wpdb->get_results( $wpdb->prepare( "SELECT `id`, `url`, `action_data` FROM {$wpdb->prefix}redirection_items WHERE url = %s AND regex = 0 ORDER BY position", '/' . $path . '/' ) );
		}

		if ( ! empty( $redirection_items[1] ) ) {
			echo '<h1>Duplicate Redirection Item, Fix Immediately: ' . $redirection_items[0]->url . '</h1>';
			echo '<h1><a href="' . admin_url( 'tools.php?page=redirection.php&s=' . $redirection_items[0]->url ) . '" target="_blank">GO NOW!!! DON\'T WAIT!!!</a></h1>';
			exit;
		}

		if ( ! empty( $redirection_items[0] ) ) {
			$path_object = get_page_by_path( "{$redirection_items[0]->action_data}" );


			if ( empty( $path_object ) ) {
				$path_object = get_page_by_path( "{$redirection_items[0]->action_data}", 'OBJECT', 'post' );
			}


			if ( empty( $path_object ) ) {
				$path_object = get_page_by_path( "{$redirection_items[0]->action_data}", 'OBJECT', $this->post_types_excluded );
			}


			if ( empty( $path_object ) ) {
				$path_tmp = explode( '/', rtrim( ltrim( $redirection_items[0]->action_data, '/' ), '/' ) );


				if (
					isset( $path_tmp[1] )
					&& count( $path_tmp ) > 1
				) {
					unset( $path_tmp[0] );

					$path_tmp = implode( '/', $path_tmp );

					$path_object = get_page_by_path( "/{$path_tmp}/", 'OBJECT', $this->post_types_excluded );

					if ( empty( $path_object ) ) {
						$path_object = lct_get_taxonomy_by_path( "/{$path_tmp}/" );


						if ( ! empty( $path_object ) ) {
							$path_object->ID = $path_object->term_id;
						}
					}
				}
			}
		}


		return $path_object;
	}
}


/**
 * Routes you to the proper fixes_and_cleanups_message
 *
 * @param null $prefix
 * @param null $parent
 *
 * @return mixed
 * @since    5.40
 * @verified 2017.04.27
 */
function lct_get_fixes_cleanups_message( $prefix = null, $parent = null )
{
	$message = '';


	if ( function_exists( 'lct_get_fixes_cleanups_message___' . $prefix ) ) {
		$message = call_user_func( 'lct_get_fixes_cleanups_message___' . $prefix, $prefix, $parent );
	}


	return $message;
}


/**
 * DB Fix: Add taxonomy field data to old entries
 * Adds ACF taxonomy meta to newly created fields for existing groups
 *
 * @param $prefix
 * @param $parent
 *
 * @return string
 * @since    5.40
 * @verified 2018.08.27
 */
function lct_get_fixes_cleanups_message___db_fix_atfd_7637( $prefix, $parent )
{
	$message = '';

	$excluded_fields = [
		'show_params',
		zxzu( 'fix' )
	];

	$fields = lct_acf_get_mapped_fields( $parent, $prefix, $excluded_fields, true );


	if ( ! $fields['run_this'] ) {
		$message = "<h1 style='color: green;font-weight: bold'>Select some options below to run this Fix/Cleanup.</h1>";


		return $message;
	}


	//Ok, We are finally able to run the fix if we made it this far.


	$tax_args = [
		'taxonomy'     => $fields['taxonomy'],
		'hide_empty'   => 0,
		'hierarchical' => 1,
		'fields'       => 'ids'
	];
	$term_ids = get_terms( $tax_args );


	if ( ! lct_is_wp_error( $term_ids ) ) {
		$option_value = '';

		if ( $fields['is_array'][0] ) {
			$option_value = explode( ",", $fields['option_value'] );
		}

		$message .= '<h2>Updated Terms</h2>';

		$message .= '<ul>';

		foreach ( $term_ids as $term_id ) {
			$option_name = implode( '_', [ $fields['taxonomy'], $term_id, $fields['f_name'] ] );

			if ( ! $fields['overwrite_value'][0] ) {
				$current_option = get_option( lct_pre_us( $option_name ) );

				if ( ! empty( $current_option ) && $current_option == $fields['f_key'] ) {
					continue;
				}
			}

			$message .= "<li><span style='font-weight: bold;'>Term ID " . $term_id . ":</span> " . $fields['option_value'] . "</li>";

			update_option( $option_name, $option_value );
			update_option( lct_pre_us( $option_name ), $fields['f_key'] );
		}

		$message .= '</ul>';
	} else {
		$message = "<h1 style='color: red;font-weight: bold'>Invalid Taxonomy</h1>";


		return $message;
	}


	//Done with the fix


	$message .= lct_acf_recap_field_settings( $fields, $prefix );


	return $message;
}


/**
 * DB Fix: Add Post Meta to Multiple Posts
 * Adds/Updates your desired post meta key and value to your noted array of posts
 *
 * @param $prefix
 * @param $parent
 *
 * @return string
 * @since    5.40
 * @verified 2022.01.06
 */
function lct_get_fixes_cleanups_message___db_fix_apmmp_5545( $prefix, $parent )
{
	$message = '';

	$excluded_fields = [
		'show_params',
		zxzu( 'fix' )
	];

	$fields = lct_acf_get_mapped_fields( $parent, $prefix, $excluded_fields, true );


	if ( ! $fields['run_this'] ) {
		$message = "<h1 style='color: green;font-weight: bold'>Select some options below to run this Fix/Cleanup.</h1>";


		return $message;
	}


	//Ok, We are finally able to run the fix if we made it this far.


	if ( ! empty( $fields['posts'] ) ) {
		$posts = explode( ',', $fields['posts'] );

		if ( $fields['is_array'][0] ) {
			$meta_value = explode( ",", $fields['meta_value'] );
		} else {
			$meta_value = $fields['meta_value'];
		}

		$message .= '<h2>Updated Post IDs</h2>';

		$message .= '<ul>';


		foreach ( $posts as $post_id ) {
			if ( ! is_numeric( $post_id ) ) {
				continue;
			}

			if ( ! $fields['overwrite_value'][0] ) {
				$current_value = get_field( $fields['meta_key'], $post_id );

				if ( ! empty( $current_value ) ) {
					continue;
				}
			}

			$message .= "<li><span style='font-weight: bold;'>Post ID " . $post_id . ":</span> " . get_the_title( $post_id ) . "</li>";


			//TODO: cs - This needs a little more testing - 4/3/2016 9:42 PM
			//lct_acf_disable_filters();
			acf_disable_filters();
			delete_post_meta( $post_id, $fields['meta_key'] );
			update_field( $fields['meta_key'], $meta_value, $post_id );
			acf_enable_filters();
			//lct_acf_enable_filters();


			if ( get_post_meta( $post_id, lct_pre_us( $fields['meta_key'] ), true ) === '' ) {
				delete_post_meta( $post_id, lct_pre_us( $fields['meta_key'] ) );
			}
		}

		$message .= '</ul>';
	} else {
		$message = "<h1 style='color: red;font-weight: bold'>Invalid Post ID Array</h1>";


		return $message;
	}


	//Done with the fix


	$message .= lct_acf_recap_field_settings( $fields, $prefix );


	return $message;
}


/**
 * File Fix: Run _editzz File Overwrite
 * Overwrites managed _editzz files.
 *
 * @param $prefix
 * @param $parent
 *
 * @return string
 * @since    5.40
 * @verified 2022.02.11
 */
function lct_get_fixes_cleanups_message___file_fix_editzz_or( $prefix, $parent )
{
	$message = '';

	$excluded_fields = [
		'show_params',
		zxzu( 'fix' )
	];

	$fields = lct_acf_get_mapped_fields( $parent, $prefix, $excluded_fields, true );


	if ( ! $fields['run_this'] ) {
		$message = '<h1 style="color: green;font-weight: bold;">Check Run This Now and Save Options to overwrite _editzz files</h1>';

		$message .= '<table style="border: 5px solid #ff0000;">
			<tr>
				<td>
					<p>
						<a href="' . admin_url( 'admin.php?page=' . zxzu( 'cleanup_guid' ) ) . '" class="button button-primary" target="_blank">Cleanup siteurl, guid, etc.</a>
					</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>
						<a href="' . admin_url( 'admin.php?page=' . zxzu( 'close_all_pings_and_comments' ) ) . '" class="button button-primary" target="_blank">Close All Pings & Comments</a>
					</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>
						<a href="' . admin_url( 'admin.php?page=' . zxzu( 'cleanup_uploads' ) ) . '" class="button button-primary" target="_blank">Cleanup Uploads Folder</a>
					</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>
						<a href="' . admin_url( 'admin.php?page=' . zxzu( 'repair_acf_usermeta' ) ) . '" class="button button-primary" target="_blank">Repair ACF User Meta Data</a>
					</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>
						<a href="' . admin_url( 'admin.php?page=' . zxzu( 'repair_acf_postmeta' ) ) . '" class="button button-primary" target="_blank">Repair ACF Post Meta Data</a>
					</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>
						<a href="' . admin_url( 'admin.php?page=' . zxzu( 'repair_acf_termmeta' ) ) . '" class="button button-primary" target="_blank">Repair ACF Term Meta Data</a>
					</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>
						<a href="' . admin_url( 'admin.php?page=' . zxzu( 'repair_term_counts' ) ) . '" class="button button-primary" target="_blank">Repair Term Counts</a>
					</p>
				</td>
			</tr>
		</table>';


		return $message;
	}


	//Ok, We are finally able to run the fix if we made it this far.


	$files_updated = apply_filters( 'lct/editzz_update_files', false, true );

	if ( empty( $files_updated ) ) {
		$message = "<h1 style='color: red;font-weight: bold'>_editzz overwrite failed</h1>";


		return $message;
	}


	//Done with the fix


	$message .= "<h1 style='color: green;font-weight: bold'>_editzz overwrite was successful</h1>";


	return $message;
}


/**
 * Review: Shows a list of site info that we care about
 *
 * @param $prefix
 * @param $parent
 *
 * @return string
 * @since    5.40
 * @verified 2022.01.06
 */
function lct_get_fixes_cleanups_message___lct_review_site_info( $prefix, $parent )
{
	$message = [];


	if ( ! get_field( $prefix . zxzd() . 'run_this', lct_o() ) ) { //can't use lct_acf_get_option()
		$message[] = "<h1 style='color: green;font-weight: bold'>Check Run This Now and Save Options to see the Site Info</h1>";


		return lct_return( $message );
	}


	//Ok, We are finally able to run the fix if we made it this far.


	if (
		( $site_info = lct_get_site_info() )
		&& ! empty( $site_info )
	) {
		$message[] = $site_info;
	} else {
		$message[] = "<h1 style='color: red;font-weight: bold'>Gathering Site Info failed " . $parent . "</h1>";


		return lct_return( $message );
	}


	//Done with the fix


	$message[] = "<h1 style='color: green;font-weight: bold'>Gathering Site Info was successful</h1>";


	return lct_return( $message );
}
