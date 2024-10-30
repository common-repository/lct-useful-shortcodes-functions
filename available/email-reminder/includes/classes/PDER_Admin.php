<?php
/** @noinspection PhpMissingFieldTypeInspection */
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Admin Pages
 * dashboard_page_{zxza}-create-email-reminder
 */
class PDER_Admin
{
	private $_messages = [ 'error' => [], 'success' => [] ];


	function __construct()
	{
		//Setup WordPress action and filter hooks
		$this->load_hooks();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    7.49
	 * @verified 2018.02.21
	 */
	function load_hooks()
	{
		//bail early if already ran
		if ( lct_did() ) {
			return;
		}


		add_action( 'admin_menu', [ &$this, 'create_menu' ] );
		add_action( 'admin_init', [ &$this, 'process_submissions' ] );
	}


	/**
	 * Add the admin menu page
	 *
	 * @since    7.3
	 * @verified 2020.05.14
	 */
	function create_menu()
	{
		$page_title = __( 'Email Reminder', 'TD_LCT' );
		$menu_title = __( 'Email Reminder', 'TD_LCT' );


		//add_dashboard_page( $page_title, $menu_title, 'manage_options', 'lct_ereminder', [ &$this, 'ereminder_page' ] );
		add_menu_page( $page_title, $menu_title, 'manage_options', 'lct_ereminder', [ &$this, 'ereminder_page' ], PDER_ASSETS . '/images/icon.png' );
	}


	function ereminder_page()
	{
		$data             = [];
		$data['messages'] = $this->_messages;
		$file             = 'ereminder-page.php';


		//header('Content-type: text/html; charset=utf-8');
		echo PDER_Utils::get_view( $file, $data );
	}


	function process_submissions()
	{
		if (
			! isset( $_REQUEST['pder-submit'] )
			|| $_REQUEST['pder-submit'] != 'true'
		) {
			return;
		}


		/**
		 * Send reminder now
		 */
		if (
			$_REQUEST['pder-action'] === 'send'
			&& wp_verify_nonce( $_REQUEST['wpapi_nonce'], 'wp_rest' )
		) {
			lct_send_reminder( $_REQUEST['postid'] );


			/**
			 * Delete a single reminder
			 */
		} elseif (
			$_REQUEST['pder-action'] === 'delete'
			&& wp_verify_nonce( $_REQUEST['wpapi_nonce'], 'wp_rest' )
		) {
			$this->delete_reminder( $_REQUEST );


			/**
			 * Delete all sent reminders
			 */
		} elseif (
			$_REQUEST['pder-action'] === 'delete-all'
			&& wp_verify_nonce( $_REQUEST['wpapi_nonce'], 'wp_rest' )
		) {
			$this->delete_reminders_many( $_REQUEST );
		}
	}


	function update_reminder( $data )
	{
		$data['pder-action'] = 'update';


		return $this->schedule_reminder( $data );
	}


	/**
	 * Schedule a reminder
	 *
	 * @param $data_ar
	 *
	 * @return bool|int|WP_Error
	 * @since        7.3
	 * @verified     2022.04.21
	 * @noinspection PhpStatementHasEmptyBodyInspection
	 */
	function schedule_reminder( $data_ar )
	{
		$insert_post_id = false;


		if (
			empty( $data_ar['pder'] )
			|| ! is_array( $data_ar['pder'] )
		) {
			return $insert_post_id;
		}


		$data  = $data_ar['pder'];
		$clean = [];
		$error = [];


		/**
		 * Set delete_email setting
		 */
		if ( isset( $data['delete_email'] ) ) //legacy compatibility
		{
			$data[ zxzacf( 'delete_email' ) ] = $data['delete_email'];
		}

		if (
			( $tmp = zxzacf( 'delete_email' ) )
			&& ! isset( $data[ $tmp ] )
		) {
			$data[ $tmp ] = 0;
		}


		/**
		 * Set the reminder body
		 */
		if ( $data['reminder'] === '' ) {
			$error['reminder'] = __( 'Please enter a reminder.', 'TD_LCT' );
			$clean['reminder'] = '';
		} else {
			$clean['reminder'] = $data['reminder'];
		}


		/**
		 * Set the reminder subject
		 */
		if ( $data['title'] ) {
			$title = $data['title'];
		} else {
			//Create shortened version of reminder to use as title
			$title = substr( $clean['reminder'], 0, 30 );


			//add ellipses to title if needed
			if ( strlen( $clean['reminder'] ) > 30 ) {
				$title .= '...';
			}
		}


		/**
		 * Set To field
		 */
		if ( ! empty( $data['blank_email'] ) ) {
			$clean['email'] = '';
		} elseif (
			! empty( $data['email'] )
			&& is_array( $data['email'] )
		) {
			$clean['email'] = implode( ',', $data['email'] );
		} elseif (
			strpos( $data['email'], ',' ) !== false
			&& strpos( $data['email'], '@' ) !== false
		) {
			$clean['email'] = $data['email'];
		} elseif (
			$data['email'] === ''
			|| ! is_email( $data['email'] )
		) {
			$error['email'] = __( 'Please enter a valid e-mail address.', 'TD_LCT' );
			$clean['email'] = '';
		} else {
			$clean['email'] = $data['email'];
		}


		/**
		 * Dates
		 */
		$time_now     = current_time( 'timestamp' );    //local time
		$time_now_gmt = current_time( 'timestamp', 1 ); //utc time
		$time_delta   = $time_now_gmt - $time_now;      //if positive, local time is -gmt. else +gmt
		if ( $time_delta < 0 ) {
			$time_delta = $time_delta * - 1;
		}

		//validate dates and specify default one's if needed
		if ( '' === $data['date'] ) {
			$error['date'] = __( 'Please enter date in the correct format (YYYY-MM-DD).', 'TD_LCT' );
		}

		$date_unformatted = empty( $data['date'] ) ? $time_now : strtotime( $data['date'] );

		//convert date and time into required format for database entry (YYYY-MM-DD HH:MM:SS)
		$clean['date'] = date( lct_db_date_only_no_time_format(), $date_unformatted );
		$clean['time'] = date( lct_db_time_format_no_seconds(), $date_unformatted );
		$date_all      = "{$clean['date']} {$clean['time']}";

		//determine gmt time for schedule
		$date_all_gmt = date( lct_db_date_format_no_seconds(), strtotime( $date_all ) + $time_delta );


		/**
		 * Setup for writing to database
		 */
		$reminder = [
			'post_title'    => $title,
			'post_content'  => $clean['reminder'],
			'post_type'     => 'ereminder',
			'post_date'     => $date_all,
			'post_date_gmt' => $date_all_gmt,
			'post_excerpt'  => $clean['email'],
			'post_status'   => 'draft'
		];


		/**
		 * Set if we are updating an existing reminder
		 */
		if (
			! empty( $data_ar['postid'] )
			&& $data_ar['pder-action'] == 'update'
		) {
			$reminder['ID'] = $data_ar['postid'];
		}


		if ( empty( $error ) ) {
			/**
			 * create new / update post
			 */
			$add_back = false;

			if ( has_action( 'transition_post_status' ) ) {
				remove_action( 'transition_post_status', '_update_term_count_on_transition_post_status' );
				$add_back = true;
			}


			if ( ! empty( $reminder['ID'] ) ) {
				$insert_post_id = wp_update_post( $reminder );
			} else {
				$insert_post_id = wp_insert_post( $reminder );
			}


			if ( $add_back ) {
				add_action( 'transition_post_status', '_update_term_count_on_transition_post_status', 10, 3 );
			}


			/** In theory, $insert_post_id can be 0, but very unlikely on a WP site **/
			if (
				empty( $insert_post_id )
				|| lct_is_wp_error( $insert_post_id )
			) {
				//TODO: cs - Error - 8/27/2021 4:47 PM


			} else {
				if ( ! empty( $data['attachments'] ) ) {
					update_post_meta( $insert_post_id, 'attachments', $data['attachments'] );
				}


				if (
					( $tmp = lct_org() )
					&& isset( $data[ $tmp ] )
				) {
					update_field( lct_org(), $data[ lct_org() ], $insert_post_id );
				}


				update_field( zxzacf( 'delete_email' ), $data[ zxzacf( 'delete_email' ) ], $insert_post_id );


				/**
				 * Save any added postmeta
				 */
				foreach ( $data as $meta_save_key => $meta_save ) {
					if ( in_array( $meta_save_key, [ lct_org(), zxzacf( 'delete_email' ) ] ) ) {
						continue;
					}


					if ( strpos( $meta_save_key, zxzacf() ) === 0 ) {
						update_field( $meta_save_key, $meta_save, $insert_post_id );
					}
				}


				/**
				 * @date     0.0
				 * @since    2017.25
				 * @verified 2021.08.27
				 */
				do_action( 'lct/pder/after_wp_insert_post', $data, $insert_post_id );
			}
		}


		return $insert_post_id;
	}


	/**
	 * Delete a reminder
	 *
	 * @param        $data
	 *
	 * @since    7.3
	 * @verified 2022.01.21
	 */
	function delete_reminder( $data )
	{
		$post_id = $data['postid'];
		$post    = get_post( $post_id );
		$error   = [];
		$success = [];


		if ( empty( $post ) ) {
			$error[] = sprintf( __( 'Error: Invalid ID: <strong>#%d</strong>.', 'TD_LCT' ), $post_id );


		} else {
			/**
			 * Pre-delete the postmeta
			 */
			lct_delete_all_post_meta( $post_id );
			$result = wp_delete_post( $post_id, true ); //bypass trash and force deletion


			if ( ! $result ) {
				//failure
				$error[] = sprintf( __( 'Error: Failure deleting reminder <strong>#%d</strong>. Please try again.', 'TD_LCT' ), $post_id );


			} else {
				//successful
				$success[] = sprintf( __( 'Reminder <strong>#%s</strong> deleted.', 'TD_LCT' ), $post_id );
			}
		}


		/** Return response **/
		if ( isset( $data['ajax'] ) && $data['ajax'] == 'true' ) {
			$response = [
				/** TODO: add new nonce? Refer http://wordpress.stackexchange.com/questions/19826/multiple-ajax-nonce-requests **/
				'messages' => [
					'success' => $success,
					'error'   => $error
				]
			];
			echo wp_json_encode( $response );
			exit;


		} else {
			$this->_messages = [
				'success' => $success,
				'error'   => $error
			];
		}
	}


	/**
	 * Delete all sent reminders
	 *
	 * @param array $data
	 *
	 * @since    7.3
	 * @verified 2022.01.21
	 */
	function delete_reminders_many( $data )
	{
		$pd         = new PDER;
		$ereminders = $pd->get_ereminders( [ 'post_status' => 'publish' ] );
		$success    = [];
		$error      = [];


		/** @noinspection PhpStatementHasEmptyBodyInspection */
		if ( empty( $ereminders ) ) {
			//TODO: cs - error message? - 8/27/2021 4:48 PM


		} else {
			foreach ( $ereminders as $ereminder ) {
				if ( $pd->delete_reminder( $ereminder->ID ) ) {
					$success[] = sprintf( __( 'Reminder <strong>#%d</strong> deleted.', 'TD_LCT' ), $ereminder->ID );
				} else {
					$error[] = sprintf( __( 'Error deleting reminder <strong>#%d</strong>.', 'TD_LCT' ), $ereminder->ID );
				}
			}
		}


		if ( isset( $data['ajax'] ) && $data['ajax'] == 'true' ) {
			$response = [
				/** TODO: add new nonce? Refer http://wordpress.stackexchange.com/questions/19826/multiple-ajax-nonce-requests **/
				'messages' => [
					'success' => $success,
					'error'   => $error
				]
			];


			echo wp_json_encode( $response );
			exit;
		} else {
			$this->_messages = [
				'success' => $success,
				'error'   => $error
			];
		}
	}
}
