<?php
/** @noinspection PhpMissingFieldTypeInspection */
/**
 * `{zxzu}send_password`: Single word, no spaces. Underscores allowed. e.g. donate_button
 * `Send Password Email`: Multiple words, can include spaces, visible when selecting a field type. e.g. Donate Button
 *
 * @since    7.35
 * @verified 2016.11.18
 */


//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'acf_field_lct_send_password' ) ) :


	class acf_field_lct_send_password extends acf_field
	{
		public $name;
		public $label;
		public $category;
		public $defaults;
		public $l10n;

		public $unused_settings;

		public $welcome_key;
		public $reset_key;
		public $org;


		/**
		 * __construct
		 * This function will set up the field type data
		 *
		 * @type function
		 * @date     5/03/2014
		 * @since    5.0.0
		 * @verified 2017.09.28
		 */
		function __construct()
		{
			$this->welcome_key = zxzu( 'send_welcome' );
			$this->reset_key   = zxzu( 'send_reset' );


			/**
			 * name (string) Single word, no spaces. Underscores allowed
			 */
			$this->name = zxzu( 'send_password' );

			/**
			 * label (string) Multiple words, can include spaces, visible when selecting a field type
			 */
			$this->label = __( 'Send Password Email', 'TD_LCT' );

			/**
			 * category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
			 */
			$this->category = zxzb();

			/**
			 * defaults (array) Array of default settings which are merged into the field object. These are used later in settings
			 */
			$this->defaults = [];

			/**
			 * l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
			 */
			$this->l10n = [
				'error' => __( 'Error!', 'TD_LCT' ),
			];


			/**
			 * unused_settings (array) Array of settings we will not be using. See: lct_acf_builtin_field_settings() for options
			 */
			$this->unused_settings = [
				'conditional_logic',
				'instructions',
				'name',
				'required',
			];


			// do not delete!
			parent::__construct();


			/**
			 * Custom
			 */
			add_action( 'wp_ajax_' . zxzu( 'send_password' ), [ $this, 'ajax_handler' ] );


			/**
			 * Process Settings
			 */
			lct_acf_register_field_type( $this, [ 'exclude_field_type' ] );
		}


		/**
		 * render_field_settings()
		 * Create extra settings for your field. These are visible when editing a field
		 *
		 * @param $field (array) the $field being edited
		 *
		 * @type action
		 * @since 3.6
		 * @date  23/01/13
		 */
		function render_field_settings( $field ) {}


		/**
		 * render_field()
		 * Create the HTML interface for your field
		 *
		 * @unused   param $field (array) the $field being rendered
		 * @type action
		 * @since    3.6
		 * @date     23/01/13
		 * @verified 2018.03.26
		 */
		function render_field( $field )
		{
			if ( isset( $_GET['post'] ) ) {
				$name = '';

				if ( strpos( $field['name'], '][' ) !== false ) {
					$repeater = explode( '][', $field['name'] );
					$name     = '_' . $repeater[1];
				}


				echo '<div style="display: inline-block;margin-right: 15px;">';
				echo "<input type=\"button\" id=\"{$this->welcome_key}{$name}\" class=\"button-primary " . get_cnst( 'send_password' ) . "_ajax_button {$this->welcome_key}\" value=\"Send Welcome Email\"/>";
				echo '</div>';

				echo '<div style="display: inline-block;margin-right: 15px;">';
				echo "<input type=\"button\" id=\"{$this->reset_key}{$name}\" class=\"button-primary " . get_cnst( 'send_password' ) . "_ajax_button {$this->reset_key}\" value=\"Reset Password\"/>";
				echo '</div>';
				echo '<div id="' . get_cnst( 'send_password' ) . '_message"></div>';
			} else {
				echo 'This item must be published first.';
			}
		}


		/**
		 * input_admin_enqueue_scripts()
		 * This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
		 * Use this action to add CSS + JavaScript to assist your render_field() action.
		 *
		 * @type action (admin_enqueue_scripts)
		 * @since    3.6
		 * @date     23/01/13
		 * @verified 2019.02.11
		 */
		function input_admin_enqueue_scripts()
		{
			if ( isset( $_GET['post'] ) ) {
				if (
					lct_get_setting( 'use_org' )
					&& get_post_type( $_GET['post'] ) == 'lct_org'
				) {
					$org = $_GET['post'];
				} elseif ( lct_get_setting( 'use_org' ) ) {
					$org = get_field( lct_org(), $_GET['post'], false );
				} else {
					$org = null;
				}


				lct_enqueue_script( get_cnst( 'send_password' ), lct_get_root_url( 'assets/js/plugins/acf/send_password_ajax.min.js' ), true, [ 'jquery' ], lct_get_setting( 'version' ), true );


				$localize = [
					'ajax_url'    => admin_url( 'admin-ajax.php' ),
					'wpapi_nonce' => wp_create_nonce( 'wp_rest' ),
					'post_id'     => $_GET['post']
				];


				if ( lct_get_setting( 'use_org' ) ) {
					$localize['lct_org'] = $org;
				}


				wp_localize_script(
					get_cnst( 'send_password' ),
					get_cnst( 'send_password' ),
					$localize
				);
			}
		}


		/**
		 * input_admin_head()
		 * This action is called in the admin_head action on the edit screen where your field is created.
		 * Use this action to add CSS and JavaScript to assist your render_field() action.
		 *
		 * @type action (admin_head)
		 * @since 3.6
		 * @date  23/01/13
		 */
		function input_admin_head() {}


		/**
		 * input_form_data()
		 * This function is called once on the 'input' page between the head and footer
		 * There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and
		 * 'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
		 * seen on comments / user edit forms on the front end. This function will always be called, and includes
		 * $args that related to the current screen such as $args['post_id']
		 *
		 * @unused   param $args (array)
		 * @type function
		 * @date     6/03/2014
		 * @since    5.0.0
		 * @verified 2017.09.28
		 */
		function input_form_data()
		{
			/**
			 * everytime
			 */
			//echo lct_acf_field_hide( $this->name );


			/**
			 * Front-end ONLY
			 */
			//if ( lct_frontend() ) {}


			/**
			 * Back-end ONLY
			 */
			//if ( lct_wp_admin_non_ajax() ) {}


			/**
			 * Front-end ACF Form ONLY
			 */
			//if ( lct_is_form_only() ) {}


			/**
			 * Any ACF Form
			 */
			//if ( lct_is_form_enterable() ) {}


			/**
			 * Display form or PDF
			 */
			//if ( lct_is_display_form_or_pdf() ) {}


			/**
			 * Display form ONLY
			 */
			//if ( lct_is_display_form() ) {}


			/**
			 * PDF ONLY
			 */
			//if ( lct_is_pdf() ) {}
		}


		/**
		 * input_admin_footer()
		 * This action is called in the admin_footer action on the edit screen where your field is created.
		 * Use this action to add CSS and JavaScript to assist your render_field() action.
		 *
		 * @type action (admin_footer)
		 * @since 3.6
		 * @date  23/01/13
		 */
		function input_admin_footer() {}


		/**
		 * field_group_admin_enqueue_scripts()
		 * This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
		 * Use this action to add CSS + JavaScript to assist your render_field_options() action.
		 *
		 * @type action (admin_enqueue_scripts)
		 * @since 3.6
		 * @date  23/01/13
		 */
		function field_group_admin_enqueue_scripts() {}


		/**
		 * field_group_admin_head()
		 * This action is called in the admin_head action on the edit screen where your field is edited.
		 * Use this action to add CSS and JavaScript to assist your render_field_options() action.
		 *
		 * @type action (admin_head)
		 * @since    3.6
		 * @date     23/01/13
		 * @verified 2017.09.28
		 */
		function field_group_admin_head()
		{
			echo lct_acf_admin_field_hide( $this->name, array_keys( $this->unused_settings ) );
		}


		/**
		 * load_value()
		 * This filter is applied to the $value after it is loaded from the db
		 *
		 * @param $value (mixed) the value found in the database
		 *
		 * @unused param $post_id (mixed) the $post_id from which the value was loaded
		 * @unused param $field   (array) the field array holding all the field options
		 * @type filter
		 * @return mixed
		 * @since  3.6
		 * @date   23/01/13
		 */
		function load_value( $value )
		{
			return $value;
		}


		/**
		 * update_value()
		 * This filter is applied to the $value before it is saved in the db
		 *
		 * @param $value (mixed) the value found in the database
		 *
		 * @unused param $post_id (mixed) the $post_id from which the value was loaded
		 * @unused param $field   (array) the field array holding all the field options
		 * @type filter
		 * @return mixed
		 * @since  3.6
		 * @date   23/01/13
		 */
		function update_value( $value )
		{
			return $value;
		}


		/**
		 * format_value()
		 * This filter is applied to the $value after it is loaded from the db, and before it is returned to the template
		 *
		 * @param $value (mixed) the value which was loaded from the database
		 *
		 * @unused param $post_id (mixed) the $post_id from which the value was loaded
		 * @unused param $field   (array) the field array holding all the field options
		 * @type filter
		 * @return mixed
		 * @since  3.6
		 * @date   23/01/13
		 */
		function format_value( $value )
		{
			// bail early if no value
			if ( empty( $value ) ) {
				return $value;
			}


			return $value;
		}


		/**
		 * validate_value()
		 * This filter is used to perform validation on the value prior to saving.
		 * All values are validated regardless of the field's required setting. This allows you to validate and return
		 * messages to the user if the value is not correct
		 *
		 * @param $valid (boolean) validation status based on the value and the field's required setting
		 *
		 * @unused param $value (mixed) the $_POST value
		 * @unused param $field (array) the field array holding all the field options
		 * @unused param $input (string) the corresponding input name for $_POST value
		 * @type filter
		 * @return mixed
		 * @date   11/02/2014
		 * @since  5.0.0
		 */
		function validate_value( $valid )
		{
			return $valid;
		}


		/**
		 * delete_value()
		 * This action is fired after a value has been deleted from the db.
		 * Please note that saving a blank value is treated as an update, not a 'delete'
		 *
		 * @param $post_id (mixed) the $post_id from which the value was deleted
		 * @param $key     (string) the $meta_key which the value was deleted
		 *
		 * @type action
		 * @date  6/03/2014
		 * @since 5.0.0
		 */
		function delete_value( $post_id, $key ) {}


		/**
		 * load_field()
		 * This filter is applied to the $field after it is loaded from the database
		 *
		 * @param $field (array) the field array holding all the field options
		 *
		 * @type filter
		 * @return array
		 * @date   23/01/2013
		 * @since  3.6.0
		 */
		function load_field( $field )
		{
			return $field;
		}


		/**
		 * update_field()
		 * This filter is applied to the $field before it is saved to the database
		 *
		 * @param $field (array) the field array holding all the field options
		 *
		 * @type filter
		 * @return   array $field
		 * @date     23/01/2013
		 * @since    3.6.0
		 * @verified 2017.09.28
		 */
		function update_field( $field )
		{
			return lct_acf_update_field_cleanup( $field, $this->unused_settings );
		}


		/**
		 * delete_field()
		 * This action is fired after a field is deleted from the database
		 *
		 * @param $field (array) the field array holding all the field options
		 *
		 * @type action
		 * @date  11/02/2014
		 * @since 5.0.0
		 */
		function delete_field( $field ) {}


		/**
		 * reset password and send welcome email check
		 *
		 * @since    7.35
		 * @verified 2022.01.21
		 */
		function ajax_handler()
		{
			if ( ! wp_verify_nonce( $_POST['wpapi_nonce'], 'wp_rest' ) ) {
				echo wp_json_encode( [ 'status' => 'Nonce Failed' ] );
				exit;
			}


			//We do not want to continue if there is not a post_id set
			if ( empty( $_POST['post_id'] ) ) {
				echo wp_json_encode( [ 'status' => 'post_id Not Set' ] );
				exit;
			}


			//We do not want to continue if there is not an org set
			if ( empty( $_POST['lct_org'] ) ) {
				echo wp_json_encode( [ 'status' => 'lct_org Not Set' ] );
				exit;
			}


			$this->org = $_POST['lct_org'];


			$r            = [];
			$r['status']  = 'Nothing Happened';
			$r['message'] = '';


			$button_parts  = explode( '_', $_POST['button'] );
			$button_end    = end( $button_parts );
			$user_position = null;


			if (
				is_numeric( $button_end )
				|| $button_end === 0
			) {
				array_pop( $button_parts );
				$user_position = $button_end;
			}


			$button = implode( '_', $button_parts );


			if ( $user_position !== null ) {
				$user = get_field( x_zxzacf( 'org_users_' . $user_position . '_user' ), $_POST['post_id'], false );


				if (
					user_can( $user, get_cnst( 'provider' ) )
					&& ! user_can( $user, 'administrator' )
				) {
					$reset        = $this->password_reset( $user, $button );
					$r['status']  = 'Email Sent';
					$r['message'] .= $reset['message'];
				}
			} else {
				$users = lct_get_org_users( $_POST['lct_org'] );


				if ( ! lct_is_wp_error( $users ) ) {
					foreach ( $users as $user ) {
						if (
							user_can( $user, get_cnst( 'provider' ) )
							&& ! user_can( $user, 'administrator' )
						) {
							$reset        = $this->password_reset( $user, $button );
							$r['status']  = 'Email Sent';
							$r['message'] .= $reset['message'];
						}
					}
				}
			}


			echo wp_json_encode( $r );
			exit;
		}


		/**
		 * Reset a user's password and send them a reset email
		 * //TODO: cs - Restrict - 11/17/2016 05:20 PM
		 *
		 * @param $user
		 * @param $email_type
		 *
		 * @return array
		 * @since    7.35
		 * @verified 2018.03.26
		 */
		function password_reset( $user, $email_type )
		{
			if ( ! is_object( $user ) ) {
				$user = get_userdata( $user );
			}


			//reset the password
			$password = wp_generate_password();
			wp_set_password( $password, $user->ID );


			if ( lct_plugin_active( 'wc' ) ) {
				$key = get_password_reset_key( $user );


				// Send email notification
				WC()->mailer(); // load email classes

				if ( $email_type == $this->welcome_key ) {
					add_action( 'pre_option_woocommerce_registration_generate_password', '__return_yes' );
					add_filter( 'woocommerce_email_subject_customer_new_account', [ $this, 'wc_email_subject_customer_new_account' ], 10, 2 );
					add_filter( 'woocommerce_email_heading_customer_new_account', [ $this, 'wc_email_heading_customer_new_account' ], 10, 2 );


					/**
					 * #2
					 * Woocommerce Hook
					 *
					 * @date     0.0
					 * @since    0.0
					 * @verified 2021.08.30
					 */
					do_action( 'woocommerce_created_customer_notification', $user->ID, [ 'user_pass' => $password ], true );
				} else {
					add_filter( 'woocommerce_email_subject_customer_reset_password', [ $this, 'wc_email_subject_customer_reset_password' ], 10, 2 );
					add_filter( 'woocommerce_email_heading_customer_reset_password', [ $this, 'wc_email_heading_customer_reset_password' ], 10, 2 );


					/**
					 * #2
					 * Woocommerce Hook
					 *
					 * @date     0.0
					 * @since    0.0
					 * @verified 2021.08.30
					 */
					do_action( 'woocommerce_reset_password_notification', $user->user_login, $key );
				}


				//TODO: cs - We need to actually check if it was sent - 11/17/2016 08:54 PM
				$sent = true;
			} else {
				$body = '<p>We have had to reset your password on ' . site_url() . '<br/>Your username is still ' . $user->user_login . ', please use the reset your password using this <a href="#">' . site_url() . '</a><br/> Thanks.</p>';

				$sent = wp_mail( $user->user_email, 'Password reset for ' . site_url(), $body );
			}


			if ( $sent ) {
				$message = lct_get_notice( 'Email Sent to ' . $user->user_email . '!', 1, true );
			} else {
				$message = lct_get_notice( 'Bummer, email not sent to ' . $user->user_email, - 1, true );
			}


			return [ 'sent' => $sent, 'message' => $message ];
		}


		/**
		 * Update the subject
		 *
		 * @param $value
		 * @param $user
		 *
		 * @return mixed|null
		 * @since    7.35
		 * @verified 2016.11.18
		 */
		function wc_email_subject_customer_new_account(
			$value,
			/** @noinspection PhpUnusedParameterInspection */
			$user
		) {
			$new_value = apply_filters( 'lct/acf/send_password/wc_email/subject/customer_new_account', $value, __FUNCTION__, $this->org );


			if ( $new_value ) {
				$value = $new_value;
			}


			return $value;
		}


		/**
		 * Update the header
		 *
		 * @param $value
		 * @param $user
		 *
		 * @return mixed|null
		 * @since    7.35
		 * @verified 2016.11.18
		 */
		function wc_email_heading_customer_new_account(
			$value,
			/** @noinspection PhpUnusedParameterInspection */
			$user
		) {
			$new_value = apply_filters( 'lct/acf/send_password/wc_email/heading/customer_new_account', $value, __FUNCTION__, $this->org );


			if ( $new_value ) {
				$value = $new_value;
			}


			return $value;
		}


		/**
		 * Update the subject
		 *
		 * @param $value
		 * @param $user
		 *
		 * @return mixed|null
		 * @since    7.35
		 * @verified 2016.11.18
		 */
		function wc_email_subject_customer_reset_password(
			$value,
			/** @noinspection PhpUnusedParameterInspection */
			$user
		) {
			$new_value = apply_filters( 'lct/acf/send_password/wc_email/subject/customer_reset_password', $value, __FUNCTION__, $this->org );


			if ( $new_value ) {
				$value = $new_value;
			}


			return $value;
		}


		/**
		 * Update the header
		 *
		 * @param $value
		 * @param $user
		 *
		 * @return mixed|null
		 * @since    7.35
		 * @verified 2016.11.18
		 */
		function wc_email_heading_customer_reset_password(
			$value,
			/** @noinspection PhpUnusedParameterInspection */
			$user
		) {
			$new_value = apply_filters( 'lct/acf/send_password/wc_email/heading/customer_reset_password', $value, __FUNCTION__, $this->org );


			if ( $new_value ) {
				$value = $new_value;
			}


			return $value;
		}
	}


	acf_register_field_type( 'acf_field_lct_send_password' );


endif; // class_exists check
