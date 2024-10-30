<?php
/** @noinspection PhpMissingFieldTypeInspection */
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @verified NEVER
 */
class PDER
{
	/**
	 * The slug we are tracking with this class
	 *
	 * @var string
	 */
	public string $slug = '';
	/**
	 * The post_type we are tracking with this class
	 *
	 * @var string
	 */
	public string $post_type = '';


	/**
	 * Start up the class
	 *
	 * @param array $args
	 *
	 * @verified 2020.04.09
	 */
	function __construct( $args = [] )
	{
		//Store $args
		$this->args = $args;


		$this->slug      = 'reminder';
		$this->post_type = 'ereminder';


		//Setup WordPress action and filter hooks
		$this->load_hooks();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    2020.7
	 * @verified 2020.04.09
	 */
	function load_hooks()
	{
		//bail early if already ran
		if ( lct_did() ) {
			return;
		}
	}


	/**
	 * Clean the ID of the post_id
	 *
	 * @param int|WP_Post $post_id
	 *
	 * @return int|null //of WP_Post
	 * @since    2020.7
	 * @verified 2024.09.26
	 */
	function _get_post_id( $post_id, $include_trashed = false )
	{
		return afwp_check_post_type_match( $post_id, $this->post_type, 'any', $include_trashed ? [] : [ 'trash', 'future', 'pending', 'private', 'auto-draft' ] );
	}


	/**
	 * Retrieves post data given a post ID or post object.
	 *
	 * @param int|WP_Post|null $post   Optional. Post ID or post object. `null`, `false`, `0` and other PHP falsey
	 *                                 values return the current global post inside the loop. A numerically valid post
	 *                                 ID that points to a non-existent post returns `null`. Defaults to global $post.
	 * @param string           $output Optional. The required return type. One of OBJECT, ARRAY_A, or ARRAY_N, which
	 *                                 correspond to a WP_Post object, an associative array, or a numeric array,
	 *                                 respectively. Default OBJECT.
	 * @param string           $filter Optional. Type of filter to apply. Accepts 'raw', 'edit', 'db',
	 *                                 or 'display'. Default 'raw'.
	 *
	 * @return WP_Post|array|null Type corresponding to $output on success or null on failure.
	 *                             When $output is OBJECT, a `WP_Post` instance is returned.
	 * @since    2018.26
	 * @verified 2020.09.04
	 */
	function get_ereminder( $post, $output = OBJECT, $filter = 'raw' )
	{
		if (
			! ( $post_id = $this->_get_post_id( $post ) )
			|| ! ( $post = get_post( $post_id, $output, $filter ) )
		) {
			return null;
		}


		$post->postmeta = lct_get_all_post_meta( $post_id, false, [ 'strpos_0' => [ 'pyre' ] ] );


		return $post;
	}


	/**
	 * Delete a post & everything attached to it
	 *
	 * @param int|WP_Post $post_id Required. Post ID or post object.
	 *
	 * @return WP_Post|false|null Post data on success, false or null on failure.
	 * @since    2020.7
	 * @verified 2024.05.06
	 */
	function delete_reminder( $post_id )
	{
		if ( $post_id = $this->_get_post_id( $post_id ) ) {
			/**
			 * Delete Post
			 */
			lct_delete_all_post_meta( $post_id );

			return wp_delete_post( $post_id, true );
		}
	}


	/**
	 * Get an array of a Reminder objects
	 *
	 * @param array $args
	 *
	 * @return WP_Post[]
	 * @since    7.3
	 * @verified 2020.04.09
	 */
	function get_ereminders( $args = [] )
	{
		$r = [];


		/**
		 * Return early if cache is found
		 */
		$cache_key = lct_cache_key( compact( 'args' ) );
		if ( lct_isset_cache( $cache_key ) ) {
			return lct_get_cache( $cache_key );
		}


		/**
		 * Full Post Array
		 */
		$postarr = [
			'posts_per_page'         => - 1,
			'post_type'              => $this->post_type,
			'post_status'            => 'draft',
			'cache_results'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'orderby'                => 'post_date_gmt',
			'order'                  => 'ASC',
		];
		$postarr = wp_parse_args( $args, $postarr );
		$posts   = get_posts( $postarr );


		if (
			! empty( $posts )
			&& ! empty( $posts[0] )
			&& empty( $postarr['fields'] )
		) {
			if ( lct_is_a( $posts[0], 'WP_Post' ) ) {
				foreach ( $posts as $v ) {
					$r[ $v->ID ] = $this->get_ereminder( $v->ID );
				}
			} else {
				foreach ( $posts as $v ) {
					$r[ $v ] = $this->get_ereminder( $v );
				}
			}
		} elseif ( ! empty( $posts ) ) {
			$r = $posts;
		}


		/**
		 * Save the value to the cache
		 */
		lct_set_cache( $cache_key, $r );


		return $r;
	}


	/**
	 * Send Ereminders
	 *
	 * @since    7.3
	 * @verified 2024.09.25
	 */
	public static function send_ereminders()
	{
		$pd         = new PDER;
		$DateTime   = lct_DateTime();
		$date_query = [
			'before'    => [
				'year'   => $DateTime->format( 'Y' ),
				'month'  => $DateTime->format( 'm' ),
				'day'    => $DateTime->format( 'd' ),
				'hour'   => $DateTime->format( 'H' ),
				'minute' => $DateTime->format( 'i' ),
				'second' => $DateTime->format( 's' ),
			],
			'inclusive' => true,
		];
		$ereminders = $pd->get_ereminders( [ 'date_query' => $date_query ] );


		if ( ! empty( $ereminders ) ) {
			foreach ( $ereminders as $ereminder ) {
				if ( ! lct_is_a( $ereminder, 'WP_Post' ) ) {
					continue;
				}

				if ( $ereminder->post_date_gmt < lct_format_current_time_gmt() ) {
					$pd->send_ereminder( $ereminder->ID );
				}
			}
		}
	}


	/**
	 * Send a specific Ereminder
	 *
	 * @param int $post_id
	 *
	 * @since    2018.26
	 * @verified 2024.09.27
	 */
	function send_ereminder( $post_id )
	{
		if ( ! $post_id ) {
			return;
		}


		if (
			( $ereminder = $this->get_ereminder( $post_id ) )
			&& ! empty( $ereminder )
		) {
			/**
			 * To
			 */
			$to = [];

			if ( $ereminder->post_excerpt ) {
				$to = explode( ',', $ereminder->post_excerpt );
			}
			foreach ( $to as $k => $v ) {
				if ( ! is_email( $v ) ) {
					unset( $to[ $k ] );
				}
			}


			/**
			 * Custom from email address
			 */
			$from = get_field( zxzacf( 'send_from' ), $ereminder->ID );
			//Default from email address
			if ( ! $from ) {
				$from = lct_get_email_ready_from( get_option( 'admin_email' ), get_option( 'blogname' ) );
			}
			$from = apply_filters( 'lct/pder/from_email', $from, $ereminder );


			/**
			 * reply_to email address(es)
			 */
			$reply_to = get_field( zxzacf( 'reply_to' ), $ereminder->ID );
			if ( $reply_to === null ) {
				$reply_to = [];
			}
			if ( ! is_array( $reply_to ) ) {
				$reply_to = [ $reply_to ];
			}
			$reply_to = apply_filters( 'lct/pder/reply_to_email', $reply_to, $ereminder );
			foreach ( $reply_to as $k => $v ) {
				if ( ! is_email( $v ) ) {
					unset( $reply_to[ $k ] );
				}
			}


			/**
			 * cc email address(es)
			 */
			$cc = get_field( zxzacf( 'cc' ), $ereminder->ID );
			if ( $cc === null ) {
				$cc = [];
			}
			if ( ! is_array( $cc ) ) {
				$cc = explode( ',', $cc );
			}
			$cc = apply_filters( 'lct/pder/cc_email', $cc, $ereminder );
			foreach ( $cc as $k => $v ) {
				if ( ! is_email( $v ) ) {
					unset( $cc[ $k ] );
				}
			}


			/**
			 * bcc email address(es)
			 */
			$bcc = get_field( zxzacf( 'bcc' ), $ereminder->ID );
			if ( $bcc === null ) {
				$bcc = [];
			}
			if ( ! is_array( $bcc ) ) {
				$bcc = explode( ',', $bcc );
			}
			$bcc = apply_filters( 'lct/pder/bcc_email', $bcc, $ereminder );
			foreach ( $bcc as $k => $v ) {
				if ( ! is_email( $v ) ) {
					unset( $bcc[ $k ] );
				}
			}


			/**
			 * Body Header
			 */
			$body_header = apply_filters( 'lct/pder/body_header', $ereminder->post_title, $ereminder );


			/**
			 * All the reminder data
			 */
			$mail = [
				'to'                 => $to,
				'subject'            => $ereminder->post_title,
				'header'             => $body_header,
				'message'            => $ereminder->post_content,
				'from'               => $from,
				'reply_to'           => $reply_to,
				'cc'                 => $cc,
				'bcc'                => $bcc,
				'additional_headers' => '',
				'attachments'        => get_post_meta( $ereminder->ID, 'attachments', true ),
			];
			$mail = apply_filters( 'lct/pder/send_ereminders/mail', $mail, $ereminder );


			/**
			 * Send the reminder, but only if the 'to' field is ready
			 */
			if (
				empty( $mail['cancel'] )
				&& ! empty( $mail['to'] )
			) {
				/**
				 * Setup to
				 */
				$mail['to'] = html_entity_decode( implode( ',', $mail['to'] ) );

				/**
				 * Setup subject
				 */
				$mail['subject'] = html_entity_decode( $mail['subject'] );

				/**
				 * Setup header
				 */
				$mail['header'] = html_entity_decode( $mail['header'] );

				/**
				 * Set up the headers
				 */
				$headers = 'From: ' . html_entity_decode( $mail['from'] ) . "\r\n";

				/**
				 * Reply-To
				 */
				if ( ! empty( $mail['reply_to'] ) ) {
					if ( ! is_array( $mail['reply_to'] ) ) {
						$mail['reply_to'] = [ $mail['reply_to'] ];
					}

					$headers .= 'Reply-To: ' . html_entity_decode( implode( ',', $mail['reply_to'] ) ) . "\r\n";
				}

				/**
				 * Cc
				 */
				if ( ! empty( $mail['cc'] ) ) {
					$headers .= 'Cc: ' . html_entity_decode( implode( ',', $mail['cc'] ) ) . "\r\n";
				}

				/**
				 * Bcc
				 */
				if ( ! empty( $mail['bcc'] ) ) {
					$headers .= 'Bcc: ' . html_entity_decode( implode( ',', $mail['bcc'] ) ) . "\r\n";
				}


				/**
				 * additional_headers
				 */
				$headers .= $mail['additional_headers'];


				/**
				 * Attachments
				 */
				if (
					! empty( $mail['attachments'] )
					&& ! is_array( $mail['attachments'] )
				) {
					$mail['attachments'] = [ $mail['attachments'] ];
				}


				/**
				 * Cleanup message
				 */
				$mail['message']       = lct_check_for_nested_shortcodes( $mail['message'] );
				$mail['message']       = do_shortcode( $mail['message'] );
				$mail['message']       = lct_add_url_site_to_content( $mail['message'] );
				$mail['message_after'] = '';
				if ( strpos( $mail['message'], '[WP_WC_NOTIFIER_TAG_SPLITTER]' ) !== false ) {
					$SPLITTER              = explode( '[WP_WC_NOTIFIER_TAG_SPLITTER]', $mail['message'], 2 );
					$mail['message']       = $SPLITTER[0];
					$mail['message_after'] = $SPLITTER[1];
				}


				if (
					lct_plugin_active( 'wc' )
					&& (
						! isset( $mail['wc'] )
						|| ! empty( $mail['wc'] )
					)
				) {
					// Get woocommerce mailer from instance
					$mailer = WC()->mailer();

					// Wrap message using woocommerce html email template
					$wrapped_message = $mailer->wrap_message( $mail['header'], $mail['message'] );

					// Create new WC_Email instance
					$wc_email = new WC_Email;

					// Style the wrapped message with woocommerce inline styles
					$mail['message'] = $wc_email->style_inline( $wrapped_message );

					//Adjust headers
					$headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
				}


				/**
				 * message_after
				 */
				if ( ! empty( $mail['message_after'] ) ) {
					$mail['message'] .= $mail['message_after'];
				}
				$mail['message'] = str_replace( '[WP_WC_NOTIFIER_TAG_SPLITTER]', '', $mail['message'] );


				/**
				 * Actually send the reminder now
				 */
				//wp_mail() processed the request successfully
				if ( wp_mail( $mail['to'], $mail['subject'], $mail['message'], $headers, $mail['attachments'] ) ) {
					/**
					 * Do stuff once the email is sent
					 *
					 * @date     0.0
					 * @since    2018.59
					 * @verified 2021.08.27
					 */
					do_action( 'lct/pder/send_ereminders/sent', $ereminder, $mail );


					/**
					 * Set reminder to 'publish' or delete the reminder
					 */
					//delete reminder
					if ( get_field( zxzacf( 'delete_email' ), $ereminder->ID, false ) ) {
						$this->delete_reminder( $ereminder->ID );


						//Set the reminder as publish
					} else {
						/**
						 * Cc
						 */
						if (
							! empty( $mail['cc'] )
							&& lct_acf_field_exists( zxzacf( 'cc' ) )
						) {
							update_field( zxzacf( 'cc' ), $mail['cc'], $ereminder->ID );
						}


						/**
						 * Bcc
						 */
						if (
							! empty( $mail['bcc'] )
							&& lct_acf_field_exists( zxzacf( 'bcc' ) )
						) {
							update_field( zxzacf( 'bcc' ), $mail['bcc'], $ereminder->ID );
						}


						$args = [
							'post_status'  => 'publish',
							'post_title'   => $mail['subject'],
							'post_excerpt' => $mail['to'],
						];
						lct_update_post_fields( $post_id, $args );
					}


					/**
					 * wp_mail() FAILED
					 */
				} elseif ( $error = new WP_Error( 'lct/PDER::send_ereminder/1', 'wp_mail() Failed to send', $mail ) ) {
					/**
					 * Clean up the error data
					 */
					$error_data = $error->get_error_data( 'lct/PDER::send_ereminder/1' );
					unset( $error_data['message'] );
					unset( $error_data['message_after'] );
					$error->add_data( $error_data, 'lct/PDER::send_ereminder/1' );


					if ( function_exists( 'afwp_create_logged_error' ) ) {
						$conditions = null;
						$args       = [ 'post_title_suffix' => ' #1' ];
						afwp_create_logged_error_condition( $conditions, $ereminder->ID, 'draft', 'post_status', '!=' );
						afwp_create_logged_error_condition( $conditions, $ereminder->ID, null, 'post_exists', 'NOT' );
						afwp_create_logged_error( $conditions, null, $error, $args );
					} else {
						lct_debug_to_error_log( $error );
					}
				}


				/**
				 * Force-cancel the reminder
				 */
			} elseif ( ! empty( $mail['cancel'] ) ) {
				$this->delete_reminder( $ereminder->ID );


				/**
				 * To was not set
				 */
			} elseif ( empty( $mail['to'] ) ) {
				if ( $error = new WP_Error( 'lct/PDER::send_ereminder/2', 'The "TO" field was not set', $mail ) ) {
					/**
					 * Clean up the error data
					 */
					$error_data = $error->get_error_data( 'lct/PDER::send_ereminder/2' );
					unset( $error_data['message'] );
					unset( $error_data['message_after'] );
					$error->add_data( $error_data, 'lct/PDER::send_ereminder/2' );


					if ( function_exists( 'afwp_create_logged_error' ) ) {
						$args = [ 'post_title_suffix' => ' #2' ];
						afwp_create_logged_error( null, null, $error, $args );
					} else {
						lct_debug_to_error_log( $error );
					}
				}
			}
		}
	}
}
