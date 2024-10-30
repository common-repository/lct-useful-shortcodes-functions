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
class lct_wp_admin_admin_onetime
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
	 * @verified 2017.02.13
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
			if (
				( $tmp = zxzu( 'run' ) )
				&& isset( $_GET[ $tmp ] )
			) {
				add_action( 'admin_init', [ $this, 'bulk_post_content_search' ] );

				add_action( 'admin_init', [ $this, 'bulk_post_content_delimit' ] );

				add_action( 'admin_init', [ $this, 'drupal_redirect_mapper' ] );

				add_action( 'admin_init', [ $this, 'avada_3_to_5_fusion_fixer' ] );

				add_action( 'admin_init', [ $this, 'sup_checker' ] );

				add_action( 'admin_init', [ $this, 'update_all_comment_counts' ] );

				add_action( 'admin_init', [ $this, 'scanner_postmeta' ] );

				add_action( 'admin_init', [ $this, 'move_attachments' ] );

				add_action( 'init', [ $this, 'db_looper' ] );
			}
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Don't Know
	 *
	 * @return bool
	 * @since    5.38
	 * @verified 2017.05.18
	 */
	function bulk_post_content_search()
	{
		if ( $_GET[ zxzu( 'run' ) ] !== __FUNCTION__ ) {
			return false;
		}


		//$return   = [];
		//$return[] = 'works<br />';
		$post_type = [ 'page' ];

		$hub_finds = [
			'<span(.*?)>(.*?)<\/span>',
		];


		$args  = [
			'posts_per_page'         => - 1,
			//'post__in'       => [ 783 ],
			'post_type'              => $post_type,
			'cache_results'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		];
		$posts = get_posts( $args );
		//P_R_O( $posts );


		foreach ( $posts as $post ) {
			$post_content = $post->post_content;
			foreach ( $hub_finds as $hub_find ) {
				$find = "/{$hub_find}/";

				preg_match( $find, $post_content, $matches );

				if ( isset( $matches[0] ) ) {
					echo $post->ID;
					P_R_O( $matches );
				}

				if (
					isset( $matches[0] )
					&& isset( $matches[2] )
				) {
					$post_content = str_replace( $matches[0], $matches[2], $post_content );

					if ( $post_content ) {
						$args           = [
							'ID'           => $post->ID,
							'post_content' => $post_content
						];
						$update_success = wp_update_post( $args );

						if ( ! lct_is_wp_error( $update_success ) ) {
							echo 'update_success ' . $post->ID . '<br />';
						}
					}
				}
			}
		}


		//return lct_return( $return );
		exit;
	}


	/**
	 * Don't Know
	 *
	 * @return bool
	 * @since    5.38
	 * @verified 2017.05.18
	 */
	function bulk_post_content_delimit()
	{
		if ( $_GET[ zxzu( 'run' ) ] !== __FUNCTION__ ) {
			return false;
		}


		$return = [];

		echo 'works<br />';

		$post_type = [ 'page', 'post' ];
		$delimit   = '~~~~~~~~~';

		$hub_finds = [
			'<!--HubSpot Call-to-Action Code-->',
			'<!-- HubSpot Call-to-Action Code -->',
			'<!--HubSpot Call-to-Action Code -->',
			'<!-- HubSpot Call-to-Action Code-->',
			'<!--end HubSpot Call-to-Action Code-->',
			'<!-- end HubSpot Call-to-Action Code -->',
			'<!--end HubSpot Call-to-Action Code -->',
			'<!-- end HubSpot Call-to-Action Code-->',
		];

		$hub_inside_finds = [
			'class="hs-cta-img"',
			'hs-cta-img',
		];

		$args  = [
			'posts_per_page'         => - 1,
			'post_type'              => $post_type,
			'cache_results'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		];
		$posts = get_posts( $args );
		//P_R_O( $posts );

		foreach ( $posts as $post ) {
			$update       = 0;
			$post_content = $post->post_content;
			//echo $post->post_content;

			foreach ( $hub_finds as $hub_find ) {
				if ( strpos( $post_content, $hub_find ) !== false ) {
					$post_content = str_replace( $hub_find, $delimit, $post_content );

					$update ++;
				}
			}

			$content_pieces = explode( $delimit, $post_content );

			foreach ( $content_pieces as $key => $content_piece ) {
				foreach ( $hub_inside_finds as $hub_inside_find ) {
					if ( strpos( $content_piece, $hub_inside_find ) !== false ) {
						unset( $content_pieces[ $key ] );

						$update ++;
					}
				}
			}

			$post_content = implode( $delimit, $content_pieces );

			if ( $update ) {
				$args           = [
					'ID'           => $post->ID,
					'post_content' => $post_content
				];
				$update_success = wp_update_post( $args );

				if ( ! lct_is_wp_error( $update_success ) ) {
					echo 'update_success ' . $post->ID . '<br />';
				}
			} else {
				$content_pieces = explode( $delimit, $post_content );
				$post_content   = implode( '', $content_pieces );

				$args           = [
					'ID'           => $post->ID,
					'post_content' => $post_content
				];
				$update_success = wp_update_post( $args );

				if ( ! lct_is_wp_error( $update_success ) ) {
					echo 'removed delimit ' . $post->ID . '<br />';
				}
			}
		}


		return implode( '', $return );
	}


	/**
	 * Set the slug from the auto generated one to the old slug
	 *
	 * @since    2017.12
	 * @verified 2019.02.11
	 */
	function drupal_redirect_mapper()
	{
		if ( $_GET[ zxzu( 'run' ) ] !== __FUNCTION__ ) {
			return;
		}


		global $wpdb;

		$dryrun = true;
		$r      = [
			'no_change' => [],
			'changed'   => [],
			'failed'    => []
		];


		$drupal_slugs = $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}fg_redirect`", ARRAY_A );


		if ( ! empty( $drupal_slugs ) ) {
			foreach ( $drupal_slugs as $drupal_slug ) {
				//if ( $drupal_slug['old_url'] == 'blog/a-dentist-for-the-whole-family.html' ) {
				//break;
				//}


				//The page exists
				if ( $post = get_post( $drupal_slug['id'] ) ) {
					$substr = null;


					//old_url ends with .html OR
					//old_url begins with blog/
					if (
						$drupal_slug['old_url']
						&& (
							(
								strpos( $drupal_slug['old_url'], '/' ) === false
								&& ( $strpos = strpos( $drupal_slug['old_url'], '.html' ) )
								&& ( $substr = ( $strpos - strlen( $drupal_slug['old_url'] ) ) ) === - 5
							)
							|| strpos( $drupal_slug['old_url'], 'blog/' ) === 0
						)
					) {
						//old_url begins with blog/
						if ( strpos( $drupal_slug['old_url'], 'blog/' ) === 0 ) {
							$new_url = substr( $drupal_slug['old_url'], 5 );

							if ( $substr === - 5 ) {
								$new_url = substr( $new_url, 0, $substr );
							}


							$drupal_slug['reason'] = 'old_url begins with blog/';


							//old_url ends with .html
						} else {
							$new_url = substr( $drupal_slug['old_url'], 0, $substr );


							$drupal_slug['reason'] = 'old_url ends with .html';
						}


						$drupal_slug['new_url'] = $new_url;


						/**
						 * let's do it
						 *
						 * @noinspection PhpConditionAlreadyCheckedInspection
						 */
						if ( ! $dryrun ) {
							//Save new_url
							$post->post_name = $new_url;

							wp_update_post( $post );


							//save a redirection item
							$old_url            = '/' . trim( $drupal_slug['old_url'] );
							$redirection_exists = $wpdb->get_results(
								$wpdb->prepare(
									"SELECT id FROM {$wpdb->prefix}redirection_items WHERE url = '%s'",
									$old_url
								)
							);


							if (
								! count( $redirection_exists )
								&& trailingslashit( $old_url ) != trailingslashit( lct_strip_url( get_the_permalink( $post ) ) )
							) {
								Red_Item::create( [
									'source'     => $old_url,
									'target'     => trailingslashit( lct_strip_url( get_the_permalink( $post ) ) ),
									'regex'      => $this->is_regex( $old_url ),
									'group_id'   => 1,
									'match'      => 'url',
									'red_action' => 'url',
								] );
							}


							//remove from fg
							$wpdb->get_results(
								$wpdb->prepare(
									"DELETE FROM `{$wpdb->prefix}fg_redirect` 
									WHERE `old_url` = '%s' AND 
									`id` = '%s'",
									$drupal_slug['old_url'],
									$drupal_slug['id']
								)
							);
						}


						$r['changed'][ $drupal_slug['reason'] ][] = $drupal_slug;


						//old_url does NOT begin with blog/ OR
						//old_url has a slash in it OR
						//old_url does NOT end with .html
					} else {
						//old_url begins with blog/
						if ( strpos( $drupal_slug['old_url'], 'blog/' ) === 0 ) {
							$drupal_slug['reason'] = 'Blog slug already matches';

							$r['no_change'][ $drupal_slug['reason'] ][] = $drupal_slug;


							//old_url has a slash in it
						} elseif ( strpos( $drupal_slug['old_url'], '/' ) !== false ) {
							$drupal_slug['reason'] = 'There is a slash in the old slug';

							$r['no_change'][ $drupal_slug['reason'] ][] = $drupal_slug;


							//.html is missing at the end of the old_url
						} else {
							$drupal_slug['reason'] = '.html is missing at the end of the old_url';

							$r['no_change'][ $drupal_slug['reason'] ][] = $drupal_slug;
						}
					}


					//The page was removed
				} else {
					if ( 1 == 2 ) {
						//save a redirection item
						$old_url            = '/' . trim( $drupal_slug['old_url'] );
						$redirection_exists = $wpdb->get_results(
							$wpdb->prepare(
								"SELECT id FROM {$wpdb->prefix}redirection_items WHERE url = '%s'",
								$old_url
							)
						);


						if (
							! count( $redirection_exists )
							&& trailingslashit( $old_url ) != '/'
						) {
							Red_Item::create( [
								'source'     => $old_url,
								'target'     => '/',
								'regex'      => $this->is_regex( $old_url ),
								'group_id'   => 1,
								'match'      => 'url',
								'red_action' => 'url',
							] );
						}


						//remove from fg
						$wpdb->get_results(
							$wpdb->prepare(
								"DELETE FROM `{$wpdb->prefix}fg_redirect` 
									WHERE `old_url` = '%s' AND 
									`id` = '%s'",
								$drupal_slug['old_url'],
								$drupal_slug['id']
							)
						);
					}


					$drupal_slug['reason'] = 'Page already deleted in WP.';

					$r['failed'][ $drupal_slug['reason'] ][] = $drupal_slug;
				}
			}
		}


		if ( ! empty( $r ) ) {
			foreach ( $r as $type_key => $reasons ) {
				foreach ( $reasons as $reason ) {
					foreach ( $reason as $type ) {
						switch ( $type_key ) {
							case 'no_change':
								$notice_type = 'warning';
								$message     = sprintf( 'Nothing Changed: "%s" (%s) (type: %s)', $type['reason'], $type['old_url'], $type['type'] );
								break;


							case 'changed':
								$notice_type = 'success';
								$message     = sprintf( 'Changed URL to: "%s" (%s) [%s]', $type['new_url'], $type['old_url'], $type['reason'] );
								break;


							case 'failed':
								$notice_type = 'error';
								$message     = sprintf( 'FAILED: "%s" (%s) (type: %s)', $type['reason'], $type['old_url'], $type['type'] );
								break;


							default:
								$notice_type = 'error';
								$message     = 'Default, something went wrong!';
						}


						lct_get_notice( $message, $notice_type );
					}
				}
			}
		}
	}


	/**
	 * Check is a URL is regex
	 *
	 * @param $url
	 *
	 * @return bool
	 * @since    2017.12
	 * @verified 2017.02.13
	 */
	function is_regex( $url )
	{
		$regex = '()[]$^*';


		if ( strpbrk( $url, $regex ) === false ) {
			return false;
		}


		return true;
	}


	/**
	 * Fix the piece of text that doesn't get put in the fusion builder shortcodes
	 *
	 * @since    2017.34
	 * @verified 2017.09.26
	 */
	function avada_3_to_5_fusion_fixer()
	{
		if ( $_GET[ zxzu( 'run' ) ] !== __FUNCTION__ ) {
			return;
		}


		$post_types        = [
			'page',
			'post',
			'lct_theme_chunk',
		];
		$container         = '[fusion_builder_container';
		$find_contain_link = '/\[fusion_builder_container(.*?)]\[link/';
		$find_spacing_yes  = '/spacing="yes"/';
		$updated_posts     = 0;


		foreach ( $post_types as $post_type ) {
			$args  = [
				'posts_per_page'         => - 1,
				'post_type'              => $post_type,
				'post_status'            => 'any',
				'cache_results'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			];
			$posts = get_posts( $args );


			foreach ( $posts as $post ) {
				/**
				 * Check if fusion_builder_container with bad link is being used on this post
				 */
				preg_match( $find_contain_link, $post->post_content, $fusion_sc_present );


				/**
				 * Check if spacing="yes" is being used on this post
				 */
				preg_match( $find_spacing_yes, $post->post_content, $spacing_yes_present );


				if (
					! empty( $fusion_sc_present )
					&& strpos( $post->post_content, $container ) //We DO want this check just like this. Because we don't want to continue if false or 0, only positive numbers
				) {
					/**
					 * Remove the fusion_builder_container part
					 */
					$post->post_content = preg_replace( $find_contain_link, '[link', $post->post_content, 1 );


					/**
					 * Put the fusion_builder_container part at the very beginning
					 */
					$post->post_content = $container . $fusion_sc_present[1] . ']' . $post->post_content;


					/**
					 * Save the new post_content to the DB
					 */
					$post_id = wp_update_post( $post );


					if ( $post_id ) {
						lct_get_notice( 'Post content for <a href="' . get_the_permalink( $post_id ) . '" target="_blank">' . get_the_title( $post_id ) . '</a> was updated.' );
					} else {
						lct_get_notice( 'Post content for <a href="' . get_the_permalink( $post_id ) . '" target="_blank">' . get_the_title( $post_id ) . '</a> not updated. Check Manually.', - 1 );
					}


					$updated_posts ++;
				}


				if (
					! empty( $spacing_yes_present )
					&& strpos( $post->post_content, $container ) !== false
				) {
					/**
					 * Replace spacing
					 */
					$post->post_content = preg_replace( $find_spacing_yes, 'spacing=""', $post->post_content );


					/**
					 * Save the new post_content to the DB
					 */
					$post_id = wp_update_post( $post );


					if ( $post_id ) {
						lct_get_notice( 'Post content for <a href="' . get_edit_post_link( $post_id ) . '" target="_blank">' . get_the_title( $post_id ) . '</a> was updated.' );
					} else {
						lct_get_notice( 'Post content for <a href="' . get_edit_post_link( $post->ID ) . '" target="_blank">' . get_the_title( $post->ID ) . '</a> not updated. Check Manually.', - 1 );
					}


					$updated_posts ++;
				}
			}
		}


		if ( ! $updated_posts ) {
			lct_get_notice( '[' . __FUNCTION__ . '] :: All post content is already fixed.' );
		}
	}


	/**
	 * Check for html inside an attribute where it doesn't belong
	 *
	 * @since    2017.63
	 * @verified 2017.09.26
	 */
	function sup_checker()
	{
		if ( $_GET[ zxzu( 'run' ) ] !== __FUNCTION__ ) {
			return;
		}


		$post_types = get_post_types();
		unset( $post_types['revision'] );
		$find_bad_html = '/\w+=("|\')(<|([^"\'*]*)<)(.*?)/';
		$updated_posts = 0;


		foreach ( $post_types as $post_type ) {
			$args  = [
				'posts_per_page'         => - 1,
				'post_type'              => $post_type,
				'post_status'            => 'any',
				'cache_results'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			];
			$posts = get_posts( $args );


			foreach ( $posts as $post ) {
				/**
				 * Check if < is being used inside an HTML element
				 */
				preg_match_all( $find_bad_html, $post->post_content, $bad_html_presents );


				if ( ! empty( $bad_html_presents[0] ) ) {
					foreach ( $bad_html_presents[0] as $bad_html_present ) {
						if ( strpos( $bad_html_present, 'text' ) === 0 ) {
							preg_match_all( '/\[link(.*?)text=(["\'])(<|([^"\'*]*)<)(.*?)]/', $post->post_content, $bad_links );


							if ( ! empty( $bad_links[0] ) ) {
								foreach ( $bad_links[0] as $bad_link_key => $bad_link ) {
									/**
									 * Add esc_html if needed
									 */
									if ( strpos( $bad_link, 'esc_html' ) === false ) {
										$post->post_content = str_replace( rtrim( $bad_link, ']' ), rtrim( $bad_link, ']' ) . ' esc_html=' . $bad_links[2][ $bad_link_key ] . 'false' . $bad_links[2][ $bad_link_key ], $post->post_content );


										/**
										 * Save the new post_content to the DB
										 */
										wp_update_post( $post );
									}
								}
							}


							continue;
						}


						lct_get_notice( 'Post content for <a href="' . get_edit_post_link( $post->ID ) . '" target="_blank">' . get_the_title( $post->ID ) . '</a> not updated. Check Manually.', - 1 );


						$updated_posts ++;


						break;
					}
				}
			}
		}


		if ( ! $updated_posts ) {
			lct_get_notice( '[' . __FUNCTION__ . '] :: All post content is already fixed.' );
		}


		/**
		 * Fix Yoast Term Meta
		 */
		$wpseo = get_option( 'wpseo_taxonomy_meta' );


		if ( $wpseo ) {
			$fr  = [
				'FOY™'                        => 'FOY&reg;',
				'FOY Dentures™'               => 'FOY Dentures&reg;',
				'Denture Fountain of Youth™'  => 'Denture Fountain of Youth&reg;',
				'Fountain of Youth Dentures™' => 'Fountain of Youth Dentures&reg;',
				'Denture Nation™'             => 'Denture Nation&reg;',
			];
			$fnr = lct_create_find_and_replace_arrays( $fr );


			foreach ( $wpseo as $tax_key => $terms ) {
				foreach ( $terms as $term_id => $infos ) {
					foreach ( $infos as $info_key => $info ) {
						$wpseo[ $tax_key ][ $term_id ][ $info_key ] = str_replace( $fnr['find'], $fnr['replace'], $info );
					}
				}
			}


			update_option( 'wpseo_taxonomy_meta', $wpseo );
		}
	}


	/**
	 * Check for html inside an attribute where it doesn't belong
	 *
	 * @since    2018.21
	 * @verified 2019.05.21
	 */
	function db_looper()
	{
		if ( $_GET[ zxzu( 'run' ) ] !== __FUNCTION__ ) {
			return;
		}


		global $wpdb;

		$database_objs  = [];
		$table_status   = [];
		$table_status[] = '<h1 style="margin-bottom: 0;">Table Status</h1>';
		$task_status    = [];
		$task_status[]  = '<h1 style="margin-bottom: 0;">Task Status</h1>';


		/**
		 * Main DB creds
		 */
		$database_main = $wpdb;
		$tmp_not_in    = [];


		/**
		 * Other Main Settings
		 */
		$show_db_names = false;

		$not_in = [
			'information_schema',
			'mysql',
			'performance_schema',
			'phpmyadmin',
			'sys',
			'test',
			'test.1',
			'test.2',
			'apps_uptime',
		];
		$not_in = array_merge( $not_in, $tmp_not_in );


		/**
		 * Only continue if we can connect to the main DB
		 */
		if (
			$database_main
			&& $database_main->ready
		) {
			/**
			 * Get all the DBs
			 */
			$all_databases = $database_main->get_col(
				lct_wpdb_prepare_in(
					sprintf( "SELECT `schema_name` FROM `information_schema`.`schemata` WHERE `schema_name` NOT IN ( %s )", '[IN]' ),
					$not_in
				)
			);


			/**
			 * Loop through all the DBs
			 */
			foreach ( $all_databases as $DB_NAME ) {
				/** @noinspection PhpUndefinedVariableInspection */
				$database_objs[ $DB_NAME ] = new wpdb( $DB_USER, $DB_PASSWORD, $DB_NAME, $DB_HOST );


				/**
				 * Only continue if we can connect to the DB
				 */
				if ( $database_objs[ $DB_NAME ]->ready ) {
					/**
					 * WP tables exist
					 */
					if ( $database_main->get_var( "SELECT `option_id` FROM `{$DB_NAME}`.`{$wpdb->options}` LIMIT 1;" ) ) {
						$table_status[] = lct_message_good( sprintf( 'is ready :: <strong>%s</strong>', $DB_NAME ) );


						/**
						 * Show DB names
						 *
						 * @noinspection PhpConditionAlreadyCheckedInspection
						 */
						if ( $show_db_names ) {
							$task_status[] = sprintf( '<h3 style="margin-bottom: 0;">%s</h3>', $DB_NAME );
						}


						/**
						 * Call a common db_looper
						 */
						//$task_status = $this->db_looper_check_if_cron_is_running( $task_status, $DB_NAME, $database_objs );


						/**
						 * Call a filter
						 */
						//add_filter( 'lct/db_looper/wp_exists', [ $this, 'db_looper_function' ], 10, 3 );


						$task_status = apply_filters( 'lct/db_looper/wp_exists', $task_status, $DB_NAME, $database_objs );


						/**
						 * WP tables DON'T exist
						 */
					} else {
						$table_status[] = lct_message_bad( sprintf( 'Prefix wrong or WP tables don\'t exist :: <strong>%s</strong>', $DB_NAME ) );


						unset( $database_objs[ $DB_NAME ] );
					}


					/**
					 * DB not ready
					 */
				} else {
					$table_status[] = lct_message_bad( sprintf( 'is NOT ready :: <strong>%s</strong>', $DB_NAME ) );


					unset( $database_objs[ $DB_NAME ] );
				}
			}


			/**
			 * Main not ready
			 */
		} else {
			$table_status[] = 'The main DB is NOT ready.';
		}


		echo lct_return( $task_status, '<br />' );


		echo lct_return( $table_status, '<br />' );


		exit;
	}


	/**
	 * Check if a DB option is set and what it is set to
	 *
	 * @param $task_status
	 * @param $DB_NAME
	 * @param $database_objs
	 *
	 * @return array
	 * @since    2018.25
	 * @verified 2018.03.01
	 */
	function db_looper_check_any_option( $task_status, $DB_NAME, $database_objs )
	{
		$exclude = [];


		if ( in_array( $DB_NAME, $exclude ) ) {
			return $task_status;
		}


		global $wpdb;


		/**
		 * Common options to lookup:
		 * GENERAL: admin_email, blogdescription, users_can_register, start_of_week, date_format, time_format
		 * WRITING: use_smilies, *use_balanceTags, default_category, default_post_format
		 * READING: show_on_front, *page_for_posts, rss_use_excerpt, posts_per_page, posts_per_rss, blog_public
		 */
		$option = 'admin_email';


		/**
		 * Write the client
		 */
		if ( $value = $database_objs[ $DB_NAME ]->get_var( $database_objs[ $DB_NAME ]->prepare( "SELECT `option_value` FROM `{$DB_NAME}`.`{$wpdb->options}` WHERE `option_name` = %s;", $option ) ) ) {
			//CAREFUL - $database_objs[ $DB_NAME ]->get_results( $database_objs[ $DB_NAME ]->prepare( "UPDATE `{$DB_NAME}`.`{$wpdb->options}` SET `option_value` = 10 WHERE `option_name` = %s;", $option ) );
			$task_status[] = lct_message_good( sprintf( 'Option is: %s &mdash; %s', $value, $DB_NAME ) );


			/**
			 * Option NOT set
			 */
		} else {
			$task_status[] = lct_message_bad( sprintf( 'Option is NOT set :: %s', $DB_NAME ) );
		}


		return $task_status;
	}


	/**
	 * Check if the ACF field client is set and what it is
	 *
	 * @param $task_status
	 * @param $DB_NAME
	 * @param $database_objs
	 *
	 * @return array
	 * @since    2018.21
	 * @verified 2018.02.28
	 */
	function db_looper_check_client_option( $task_status, $DB_NAME, $database_objs )
	{
		$exclude = [
			'offtopic_www',
		];


		if ( in_array( $DB_NAME, $exclude ) ) {
			return $task_status;
		}


		global $wpdb;


		/**
		 * Write the client
		 */
		if ( $client = $database_objs[ $DB_NAME ]->get_var( "SELECT `option_value` FROM `{$DB_NAME}`.`{$wpdb->options}` WHERE `option_name` = 'options_lct:::clientzz';" ) ) {
			$task_status[] = lct_message_good( sprintf( 'Client is: %s', $client ) );


			/**
			 * Client NOT set
			 */
		} else {
			$task_status[] = lct_message_bad( sprintf( 'Client is NOT set :: %s', $DB_NAME ) );
		}


		return $task_status;
	}


	/**
	 * Check if a plugin is running and what version it is
	 *
	 * @param $task_status
	 * @param $DB_NAME
	 * @param $database_objs
	 *
	 * @return array
	 * @since    2018.21
	 * @verified 2018.02.28
	 */
	function db_looper_check_plugin_status_version( $task_status, $DB_NAME, $database_objs )
	{
		$exclude = [];


		if ( in_array( $DB_NAME, $exclude ) ) {
			return $task_status;
		}


		global $wpdb;


		/**
		 * Set the plugin you want to check
		 */
		//$plugin         = 'lct-useful-shortcodes-functions/lct-useful-shortcodes-functions.php';
		//$plugin_version = 'lct_version';
		$plugin         = 'advanced-custom-fields-pro/acf.php';
		$plugin_version = 'acf_version';


		/**
		 * Check for active_plugins list
		 */
		if ( $active_plugins = $database_objs[ $DB_NAME ]->get_var( "SELECT `option_value` FROM `{$DB_NAME}`.`{$wpdb->options}` WHERE `option_name` = 'active_plugins';" ) ) {
			$active_plugins = maybe_unserialize( $active_plugins );


			/**
			 * Plugin is active
			 */
			if ( in_array( $plugin, $active_plugins ) ) {
				$version = $database_objs[ $DB_NAME ]->get_var(
					$database_objs[ $DB_NAME ]->prepare(
						"SELECT `option_value` FROM `{$DB_NAME}`.`{$wpdb->options}` WHERE `option_name` = %s;",
						$plugin_version
					)
				);
				if ( ! $version ) {
					$version = 'UNKNOWN';
				}


				$task_status[] = lct_message_good( sprintf( 'Plugin is active: %s &mdash; %s', $version, $DB_NAME ) );


				/**
				 * Plugin NOT set
				 */
			} else {
				$task_status[] = lct_message_bad( sprintf( 'Plugin is NOT set :: %s', $DB_NAME ) );
			}


			/**
			 * DB not set properly
			 */
		} else {
			$task_status[] = lct_message_bad( sprintf( 'Option \'active_plugins\' is NOT set :: %s', $DB_NAME ) );
		}


		return $task_status;
	}


	/**
	 * Check if cron is running
	 *
	 * @param $task_status
	 * @param $DB_NAME
	 * @param $database_objs
	 *
	 * @return array
	 * @since    2018.21
	 * @verified 2018.02.28
	 */
	function db_looper_check_if_cron_is_running( $task_status, $DB_NAME, $database_objs )
	{
		$exclude = [];


		if ( in_array( $DB_NAME, $exclude ) ) {
			return $task_status;
		}


		global $wpdb;


		/**
		 * Check the cron
		 */
		if ( $cron = $database_objs[ $DB_NAME ]->get_var( "SELECT `option_value` FROM `{$DB_NAME}`.`{$wpdb->options}` WHERE `option_name` = 'cron';" ) ) {
			$cron = maybe_unserialize( $cron );


			if ( ! empty( $cron ) ) {
				$time = current_time( 'timestamp', 1 );
				ksort( $cron );


				foreach ( $cron as $k => $v ) {
					if ( ! is_numeric( $k ) ) {
						continue;
					}


					if ( ( $time - $k ) > ( DAY_IN_SECONDS * 2 ) ) {
						$task_status[] = lct_message_bad( sprintf( 'Cron is NOT running :: %s', $DB_NAME ) );
					}


					break;
				}
			} else {
				$task_status[] = lct_message_bad( sprintf( 'Cron is empty: %s', $cron ) );
			}


			/**
			 * Cron NOT set
			 */
		} else {
			$task_status[] = lct_message_bad( sprintf( 'Cron is NOT set :: %s', $DB_NAME ) );
		}


		return $task_status;
	}


	/**
	 * Update comment counts for all posts
	 *
	 * @since    2018.59
	 * @verified 2018.07.25
	 */
	function update_all_comment_counts()
	{
		if ( $_GET[ zxzu( 'run' ) ] !== __FUNCTION__ ) {
			return;
		}


		$post_types           = get_post_types();
		$completed_post_types = lct_get_option( 'tmp_update_all_comment_counts', [] );

		if ( ! empty( $completed_post_types ) ) {
			foreach ( $completed_post_types as $completed_post_type ) {
				unset( $post_types[ $completed_post_type ] );
			}
		}


		if ( ! empty( $post_types ) ) {
			foreach ( $post_types as $post_type ) {
				$completed_posts = lct_get_option( 'tmp_update_all_comment_counts_' . $post_type, [] );
				$count           = 0;
				$count_posts     = wp_count_posts( $post_type );

				if ( ! empty( $count_posts ) ) {
					foreach ( $count_posts as $count_post ) {
						$count = $count + (int) $count_post;
					}
				}


				$args  = [
					'posts_per_page'         => - 1,
					'post_type'              => $post_type,
					'post_status'            => 'any',
					'post__not_in'           => $completed_posts,
					'fields'                 => 'ids',
					'cache_results'          => true,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
				];
				$posts = get_posts( $args );


				if ( ! empty( $posts ) ) {
					foreach ( $posts as $post_id ) {
						wp_update_comment_count_now( $post_id );


						$completed_posts[] = $post_id;
						lct_update_option( 'tmp_update_all_comment_counts_' . $post_type, $completed_posts );
					}
				}


				if ( count( $completed_posts ) === $count ) {
					$completed_post_types[] = $post_type;
					lct_update_option( 'tmp_update_all_comment_counts', $completed_post_types );


					lct_delete_option( 'tmp_update_all_comment_counts_' . $post_type );
				}
			}
		}
	}


	/**
	 * Update comment counts for all posts
	 *
	 * @since    2019.11
	 * @verified 2019.05.21
	 */
	function scanner_postmeta()
	{
		if ( $_GET[ zxzu( 'run' ) ] !== __FUNCTION__ ) {
			return;
		}


		global $wpdb;


		/**
		 * postmeta
		 */
		$ignored_postmeta = [
			'_edit_last', //core
			'_edit_lock', //core
			'_last_edited_by', //core
			'_last_edited_time', //core
			'_menu_item_classes', //core
			'_menu_item_menu_item_parent', //core
			'_menu_item_object', //core
			'_menu_item_object_id', //core
			'_menu_item_target', //core
			'_menu_item_type', //core
			'_menu_item_url', //core
			'_menu_item_xfn', //core
			'_wp_attached_file', //core
			'_wp_attachment_metadata', //core
			'_wp_page_template', //core

			'_menu_item_fusion_megamenu', //Avada
			'avada_post_views_count', //Avada
			'fusion_builder_converted', //Avada
			'fusion_builder_status', //Avada
			'pyre_author_info', //Avada
			'pyre_avada_rev_styles', //Avada
			'pyre_combined_header_bg_color', //Avada
			'pyre_demo_slider', //Avada
			'pyre_display_copyright', //Avada
			'pyre_display_footer', //Avada
			'pyre_display_header', //Avada
			'pyre_displayed_menu', //Avada
			'pyre_elasticslider', //Avada
			'pyre_fallback', //Avada
			'pyre_fallback_id', //Avada
			'pyre_fimg_height', //Avada
			'pyre_fimg_width', //Avada
			'pyre_footer_100_width', //Avada
			'pyre_header_100_width', //Avada
			'pyre_header_bg', //Avada
			'pyre_header_bg_color', //Avada
			'pyre_header_bg_full', //Avada
			'pyre_header_bg_id', //Avada
			'pyre_header_bg_opacity', //Avada
			'pyre_header_bg_repeat', //Avada
			'pyre_hundredp_padding', //Avada
			'pyre_image_rollover_icons', //Avada
			'pyre_link_icon_url', //Avada
			'pyre_main_bottom_padding', //Avada
			'pyre_main_top_padding', //Avada
			'pyre_mobile_header_bg_color', //Avada
			'pyre_page_bg', //Avada
			'pyre_page_bg_color', //Avada
			'pyre_page_bg_full', //Avada
			'pyre_page_bg_id', //Avada
			'pyre_page_bg_layout', //Avada
			'pyre_page_bg_repeat', //Avada
			'pyre_page_title', //Avada
			'pyre_page_title_100_width', //Avada
			'pyre_page_title_bar_bg', //Avada
			'pyre_page_title_bar_bg_color', //Avada
			'pyre_page_title_bar_bg_full', //Avada
			'pyre_page_title_bar_bg_id', //Avada
			'pyre_page_title_bar_bg_retina', //Avada
			'pyre_page_title_bar_bg_retina_id', //Avada
			'pyre_page_title_bar_borders_color', //Avada
			'pyre_page_title_bg_parallax', //Avada
			'pyre_page_title_breadcrumbs_search_bar', //Avada
			'pyre_page_title_custom_subheader', //Avada
			'pyre_page_title_custom_subheader_text_size', //Avada
			'pyre_page_title_custom_text', //Avada
			'pyre_page_title_font_color', //Avada
			'pyre_page_title_height', //Avada
			'pyre_page_title_line_height', //Avada
			'pyre_page_title_mobile_height', //Avada
			'pyre_page_title_subheader_font_color', //Avada
			'pyre_page_title_text', //Avada
			'pyre_page_title_text_alignment', //Avada
			'pyre_page_title_text_size', //Avada
			'pyre_portfolio_column_spacing', //Avada
			'pyre_portfolio_content_length', //Avada
			'pyre_portfolio_excerpt', //Avada
			'pyre_portfolio_featured_image_size', //Avada
			'pyre_portfolio_filters', //Avada
			'pyre_portfolio_text_layout', //Avada
			'pyre_portfolio_width_100', //Avada
			'pyre_post_comments', //Avada
			'pyre_post_links_target', //Avada
			'pyre_post_meta', //Avada
			'pyre_post_pagination', //Avada
			'pyre_related_posts', //Avada
			'pyre_responsive_sidebar_order', //Avada
			'pyre_revslider', //Avada
			'pyre_share_box', //Avada
			'pyre_show_first_featured_image', //Avada
			'pyre_sidebar_bg_color', //Avada
			'pyre_sidebar_position', //Avada
			'pyre_sidebar_sticky', //Avada
			'pyre_slider', //Avada
			'pyre_slider_position', //Avada
			'pyre_slider_type', //Avada
			'pyre_video', //Avada
			'pyre_wide_page_bg', //Avada
			'pyre_wide_page_bg_color', //Avada
			'pyre_wide_page_bg_full', //Avada
			'pyre_wide_page_bg_id', //Avada
			'pyre_wide_page_bg_repeat', //Avada
			'pyre_wooslider', //Avada
			'sbg_selected_sidebar', //Avada
			'sbg_selected_sidebar_2', //Avada
			'sbg_selected_sidebar_2_replacement', //Avada
			'sbg_selected_sidebar_replacement', //Avada
			'slide_template', //Avada

			'_dp_original', //duplicate-post

			'_yoast_wpseo_meta-robots-noindex', //yoast
		];


		if ( lct_plugin_active( 'ninja-tables' ) ) {
			$ignored_postmeta_plugin = [
				'_external_cached_data',
				'_last_external_cached_time',
				'_ninja_custom_filter_styling',
				'_ninja_custom_table_buttons',
				'_ninja_table_cache_html',
				'_ninja_table_cache_object',
				'_ninja_table_caption',
				'_ninja_table_columns',
				'_ninja_table_custom_filters',
				'_ninja_table_settings',
				'_ninja_tables_custom_css',
				'_ninja_tables_custom_js',
				'_ninja_tables_data_migrated_for_manual_sort',
			];


			$ignored_postmeta = array_merge( $ignored_postmeta, $ignored_postmeta_plugin );
		}


		if ( lct_plugin_active( 'rhc' ) ) {
			$ignored_postmeta_plugin = [
				'_wpb_vc_js_interface_version',
				'_wpb_vc_js_status',
				'enable_featuredimage',
				'enable_postinfo',
				'enable_postinfo_image',
				'enable_venuebox',
				'enable_venuebox_gmap',
				'fc_allday',
				'fc_click_link',
				'fc_click_target',
				'fc_color',
				'fc_dow_except',
				'fc_end',
				'fc_end_datetime',
				'fc_end_interval',
				'fc_end_time',
				'fc_event_map',
				'fc_exdate',
				'fc_interval',
				'fc_post_info',
				'fc_range_end',
				'fc_range_start',
				'fc_rdate',
				'fc_rrule',
				'fc_start',
				'fc_start_datetime',
				'fc_start_time',
				'fc_text_color',
				'postinfo_boxes',
				'rhc_dbox_image',
				'rhc_month_image',
				'rhc_tooltip_image',
				'rhc_top_image',
				'vc_teaser',
			];


			$ignored_postmeta = array_merge( $ignored_postmeta, $ignored_postmeta_plugin );
		}


		if ( lct_plugin_active( 'wc' ) ) {
			$ignored_postmeta_plugin = [
				'_action_manager_schedule',
			];


			$ignored_postmeta = array_merge( $ignored_postmeta, $ignored_postmeta_plugin );
		}


		if ( lct_plugin_active( 'wpdiscuz' ) ) {
			$ignored_postmeta_plugin = [
				'_wpdiscuz_statistics',
				'wpd_form_custom_css',
				'wpdiscuz_form_fields',
				'wpdiscuz_form_general_options',
				'wpdiscuz_form_structure',
			];


			$ignored_postmeta = array_merge( $ignored_postmeta, $ignored_postmeta_plugin );
		}


		/**
		 * ACF
		 */
		$allowed_acf_field_names_unused = [
			lct_status(),
		];


		/**
		 * Get all the postmeta
		 *
		 * @noinspection PhpExpressionWithSameOperandsInspection
		 */
		if (
			1 === 1
			|| ! ( $results = $results_raw = get_transient( zxzu( __FUNCTION__ . '_results' ) ) )
		) {
			$results = $results_raw = $wpdb->get_col( "SELECT `meta_key` FROM `{$wpdb->postmeta}` GROUP BY `meta_key` ASC" );


			set_transient( zxzu( __FUNCTION__ . '_results' ), $results, 300 );
		}


		/**
		 * Process the results
		 */
		if ( ! empty( $results ) ) {
			/**
			 * Filter out postmeta we don't need to track
			 */
			$results = array_diff( $results, $ignored_postmeta );


			/**
			 * Filter out ACF postmeta we don't need to track
			 */
			if ( $post_types = get_post_types() ) {
				$acf_field_names          = [];
				$acf_field_subfield_names = [];


				/** @noinspection PhpExpressionWithSameOperandsInspection */
				if (
					1 === 1
					|| ! ( $acf_fields = get_transient( zxzu( __FUNCTION__ . '_acf_fields' ) ) )
				) {
					$acf_fields = [];


					foreach ( $post_types as $post_type ) {
						if ( $post_type === 'afwp_input_group' ) {
							continue;
						}


						if ( $fields = lct_acf_get_field_groups_fields( [ 'post_type' => $post_type ] ) ) {
							$acf_fields = array_merge( $acf_fields, $fields );
						}
					}


					set_transient( zxzu( __FUNCTION__ . '_acf_fields' ), $acf_fields, 300 );
				}


				foreach ( $acf_fields as $field ) {
					if (
						isset( $field['key'] )
						&& ! empty( $field['name'] )
					) {
						$acf_field_names[ $field['key'] ]               = $field['name'];
						$acf_field_names[ lct_pre_us( $field['key'] ) ] = lct_pre_us( $field['name'] );
					}


					if (
						isset( $field['type'] )
						&& $field['type'] === 'repeater'
						&& ! empty( $field['sub_fields'] )
					) {
						foreach ( $field['sub_fields'] as $sub_field ) {
							$acf_field_subfield_names[ $field['key'] . '_' . $sub_field['key'] ] = $field['name'] . '_%_' . $sub_field['name'];
						}
					}
				}


				$acf_field_names_unused = array_diff( $acf_field_names, $results_raw );
				$results                = array_diff( $results, array_values( $acf_field_names ) );


				if ( ! empty( $acf_field_subfield_names ) ) {
					$used_sub_fields = [];


					foreach ( $results as $key => $result ) {
						foreach ( $acf_field_subfield_names as $acf_field_subfield_name ) {
							$parts = explode( '%', $acf_field_subfield_name );


							if (
								strpos( $result, $parts[0] ) === 0
								&& strpos( $result, $parts[1] ) !== false
								&& ( $index = str_replace( [ $parts[0], $parts[1] ], '', $result ) ) !== ''
								&& is_numeric( $index )
							) {
								unset( $results[ $key ] );
								if ( $pre_key = array_search( lct_pre_us( $parts[0] . $index . $parts[1] ), $results ) ) {
									unset( $results[ $pre_key ] );
								}


								$used_sub_fields[ $parts[0] . '%' . $parts[1] ] = true;
							}
						}
					}


					if ( ! empty( $used_sub_fields ) ) {
						foreach ( $used_sub_fields as $used_sub_field ) {
							if ( $key = array_search( $used_sub_field, $acf_field_subfield_names ) ) {
								unset( $acf_field_subfield_names[ $key ] );
							}
						}
					}
				}


				foreach ( $allowed_acf_field_names_unused as $allowed_unused ) {
					$count = (int) $wpdb->get_var(
						$wpdb->prepare(
							"SELECT COUNT(*) FROM `{$wpdb->postmeta}` WHERE `meta_key` = %s",
							$allowed_unused
						)
					);


					if ( ! $count ) {
						$acf_field_names_unused = array_diff( $acf_field_names_unused, [ $allowed_unused, lct_pre_us( $allowed_unused ) ] );
					}
				}
			}


			/**
			 * List the postmeta
			 */
			$i = 0;


			foreach ( $results as $result ) {
				$count = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT COUNT(*) FROM `{$wpdb->postmeta}` WHERE `meta_key` = %s",
						$result
					)
				);


				lct_get_notice( sprintf( '%s :: %s', $count, $result ), 0 );


				$i ++;


				if ( $i > 10 ) {
					break;
				}
			}


			/**
			 * List Unused ACF Fields
			 */
			if ( ! empty( $acf_field_names_unused ) ) {
				foreach ( $acf_field_names_unused as $unused_key => $unused ) {
					if (
						strpos( $unused, zxzacf() ) !== 0
						&& strpos( $unused, lct_pre_us( zxzacf() ) ) !== 0
					) {
						lct_get_notice( sprintf( 'ACF Field not being used %s :: %s', $unused, $unused_key ), - 1 );
					}
				}
			}
		}
	}


	/**
	 * Update metadata when moving attachments
	 *
	 * @since    2019.18
	 * @verified 2019.07.11
	 */
	function move_attachments()
	{
		if ( $_GET[ zxzu( 'run' ) ] !== __FUNCTION__ ) {
			return;
		}


		global $wpdb;

		$old_path  = '';
		$new_path  = 'old/';
		$regex     = [];
		$regex_old = [];
		//$post_ids = [];


		$args        = [
			'posts_per_page'         => - 1,
			'post_type'              => 'attachment',
			'post_status'            => 'any',
			'cache_results'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,

			//'post__in' => $post_ids,
		];
		$attachments = get_posts( $args );


		if ( empty( $attachments ) ) {
			return;
		}


		foreach ( $attachments as $attachment ) {
			$attachment_meta = get_post_meta( $attachment->ID );
			$image_filename  = $attachment_meta['_wp_attached_file'][0];

			if ( strpos( $image_filename, '/' ) !== false ) {
				$tmp            = explode( '/', $image_filename );
				$image_filename = array_pop( $tmp );
			}

			$old_image_location = '/uploads/' . $old_path . $image_filename;
			$new_image_location = '/uploads/' . $new_path . $image_filename;


			/**
			 * Update GUID
			 */
			if ( strpos( $attachment->guid, $old_image_location ) !== false ) {
				$new_guid       = null;
				$maybe_new_guid = str_replace( $old_image_location, $new_image_location, $attachment->guid );


				if (
					$attachment->guid !== $maybe_new_guid
					&& $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->posts} SET guid = %s WHERE ID = %d", $maybe_new_guid, $attachment->ID ) )
				) {
					$new_guid         = $maybe_new_guid;
					$attachment->guid = $new_guid;
				}


				if ( $new_guid ) {
					lct_get_notice( sprintf( 'Updated GUID of %s [%s]', $image_filename, $attachment->ID ) );
				} else {
					lct_get_notice( sprintf( 'ERROR GUID %s [%s]', $image_filename, $attachment->ID ), - 1 );
				}
			}


			/**
			 * Update meta
			 */
			if ( strpos( $attachment->guid, $new_image_location ) !== false ) {
				$regex[]     = $image_filename;
				$regex_old[] = $old_image_location;


				/**
				 * Update post_content
				 *
				 * @var $posts_w_old_url WP_Post[]
				 */
				if (
					( $posts_w_old_url = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} WHERE `post_content` LIKE '%{$old_image_location}%' AND `post_type` != 'revision'" ) )
					&& ! empty( $posts_w_old_url )
				) {
					foreach ( $posts_w_old_url as $post ) {
						if (
							( $post_content = str_replace( $old_image_location, $new_image_location, $post->post_content ) )
							&& lct_update_post_content( $post->ID, $post_content )
						) {
							lct_get_notice( sprintf( 'Updated post_content of %s [%s]', $post->post_title, $post->ID ) );
						} else {
							lct_get_notice( sprintf( 'ERROR post_content %s [%s]', $post->post_title, $post->ID ), - 1 );
						}
					}
				}


				/**
				 * Alert Options
				 */
				$old_image_location = $old_path . $image_filename;
				$new_image_location = $new_path . $image_filename;


				if (
					( $options_w_old_url = $wpdb->get_results( "SELECT * FROM {$wpdb->options} WHERE `option_value` LIKE '%{$old_image_location}%'" ) )
					&& ! empty( $options_w_old_url )
				) {
					foreach ( $options_w_old_url as $option ) {
						lct_get_notice( sprintf( 'CHECK options %s [%s]', $option->option_name, $option->option_id ), 0 );
					}
				}


				/**
				 * Update _wp_attachment_metadata
				 */
				if (
					! empty( $attachment_meta['_wp_attachment_metadata'][0] )
					&& ( $meta = maybe_unserialize( $attachment_meta['_wp_attachment_metadata'][0] ) )
					&& ! empty( $meta['file'] )
					&& strpos( $meta['file'], $new_image_location ) === false
				) {
					if (
						strpos( $meta['file'], $old_image_location ) !== false
						&& ( $meta['file'] = str_replace( $old_image_location, $new_image_location, $meta['file'] ) )
						&& update_post_meta( $attachment->ID, '_wp_attachment_metadata', $meta )
					) {
						lct_get_notice( sprintf( 'Updated _wp_attachment_metadata of %s [%s]', $image_filename, $attachment->ID ) );
					} else {
						lct_get_notice( sprintf( 'ERROR _wp_attachment_metadata %s [%s]', $image_filename, $attachment->ID ), - 1 );
					}
				}


				/**
				 * Update _wp_attached_file
				 */
				if (
					! empty( $attachment_meta['_wp_attached_file'][0] )
					&& strpos( $attachment_meta['_wp_attached_file'][0], $new_image_location ) === false
				) {
					if (
						strpos( $attachment_meta['_wp_attached_file'][0], $old_image_location ) !== false
						&& ( $_wp_attached_file = str_replace( $old_image_location, $new_image_location, $attachment_meta['_wp_attached_file'][0] ) )
						&& update_post_meta( $attachment->ID, '_wp_attached_file', $_wp_attached_file )
					) {
						lct_get_notice( sprintf( 'Updated _wp_attached_file of %s [%s]', $image_filename, $attachment->ID ) );
					} else {
						lct_get_notice( sprintf( 'ERROR _wp_attached_file %s [%s]', $image_filename, $attachment->ID ), - 1 );
					}
				}
			}
		}


		lct_get_notice( sprintf( 'Regex %s', lct_return( $regex, '|' ) ) );
		lct_get_notice( sprintf( 'Regex_old %s', lct_return( $regex_old, '|' ) ) );
	}
}
