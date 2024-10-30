<?php
/** @noinspection PhpMissingFieldTypeInspection */
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class lct_acf_ajax_save_repeater_after_remove extends ACF_Ajax
{
	/** @var string The AJAX action name. */
	var $action = 'lct/acf/ajax/save_repeater_after_remove';

	/** @var bool Prevents access for non-logged in users. */
	var $public = true;


	/**
	 * Returns the response data to sent back.
	 *
	 * @param array $request The request args.
	 *
	 * @return    bool
	 * @since        2020.5
	 * @verified     2020.02.07
	 */
	function get_response( $request )
	{
		$r = false;


		if (
			$this->has( 'form' )
			&& $this->has( 'selector' )
		) {
			$full_form = urldecode_deep( $this->get( 'form' ) );
			parse_str( $full_form, $full_form_arr );


			//Only continue if the repeater field data was sent
			if ( empty( $full_form_arr['acf'][ $this->get( 'selector' ) ] ) ) {
				return $r;
			}


			//We only need the repeater field that was altered
			$acf = $this->check_acf_repeater( lct_clean_acf_repeater( $full_form_arr['acf'][ $this->get( 'selector' ) ] ), $this->get( 'selector' ) );


			if ( empty( $acf ) ) {
				$acf = null;
			}


			$_POST[ zxzacf( 'acf_form' ) ]['acf'][ $this->get( 'selector' ) ] = $acf;


			$r = update_field( $this->get( 'selector' ), $acf, $this->get( 'post_id' ) );
		}


		return $r;
	}


	/**
	 * Check and update the whole ACF repeater
	 * Remove the row that we wanted to remove
	 *
	 * @param array       $array
	 * @param string      $sel
	 * @param string|null $sel_parent
	 *
	 * @return array
	 * @since        2020.5
	 * @verified     2020.02.07
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function check_acf_repeater( array $array, $sel, $sel_parent = null )
	{
		foreach ( $array as $k => $v ) {
			if (
				$sel === $this->get( 'affected_repeater' )
				&& $k === $this->get( 'skip' )
			) {
				if ( $sel_parent === $this->get( 'repeater_parent_row' ) ) {
					unset( $array[ $k ] );
				}
			} elseif ( is_array( $v ) ) {
				$array[ $k ] = $this->check_acf_repeater( $v, $k, $sel );


				if ( empty( $array[ $k ] ) ) {
					unset( $array[ $k ] );
				}
			}
		}


		return $array;
	}
}

acf_new_instance( 'lct_acf_ajax_save_repeater_after_remove' );


class lct_acf_ajax_send_user_login_invite extends ACF_Ajax
{
	/** @var string The AJAX action name. */
	var $action = 'lct/acf/ajax/send_user_login_invite';

	/** @var bool Prevents access for non-logged in users. */
	var $public = true;

	var $welcome_key = '';

	var $reset_key = '';


	function initialize()
	{
		$this->welcome_key = zxzu( 'send_welcome' );
		$this->reset_key   = zxzu( 'send_reset' );
	}


	/**
	 * Returns the response data to sent back.
	 *
	 * @param array $request The request args.
	 *
	 * @return    bool
	 * @since        2020.5
	 * @verified     2020.02.07
	 */
	function get_response( $request )
	{
		$r = false;


		if (
			$this->has( 'user_id' )
			&& $this->has( 'email_type' )
		) {
			$r = $this->password_reset( $this->get( 'user_id' ), $this->get( 'email_type' ) );
		}


		return $r;
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

			if ( $email_type === $this->welcome_key ) {
				add_action( 'pre_option_woocommerce_registration_generate_password', '__return_yes' );
				add_filter( 'woocommerce_email_subject_customer_new_account', [ $this, 'wc_email_subject_customer_new_account' ], 10, 2 );
				add_filter( 'woocommerce_email_heading_customer_new_account', [ $this, 'wc_email_heading_customer_new_account' ], 10, 2 );


				/**
				 * #1
				 * Woocommerce Hook
				 *
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'woocommerce_created_customer_notification', $user->ID, [ 'user_pass' => $key, 'user_id' => $user->ID ], true );
			} else {
				add_filter( 'woocommerce_email_subject_customer_reset_password', [ $this, 'wc_email_subject_customer_reset_password' ], 10, 2 );
				add_filter( 'woocommerce_email_heading_customer_reset_password', [ $this, 'wc_email_heading_customer_reset_password' ], 10, 2 );


				/**
				 * #1
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
	 * @unused   param string $value
	 * @unused   param        $user
	 * @return string
	 * @since    7.35
	 * @verified 2024.02.14
	 */
	function wc_email_subject_customer_new_account()
	{
		return sprintf( 'Your %s Login is Ready', get_bloginfo( 'name' ) );
	}


	/**
	 * Update the header
	 *
	 * @unused   param string $value
	 * @unused   param        $user
	 * @return string
	 * @since    7.35
	 * @verified 2024.02.14
	 */
	function wc_email_heading_customer_new_account()
	{
		return sprintf( '%s Login Details', get_bloginfo( 'name' ) );
	}


	/**
	 * Update the subject
	 *
	 * @unused   param string $value
	 * @unused   param        $user
	 * @return string
	 * @since    7.35
	 * @verified 2024.02.14
	 */
	function wc_email_subject_customer_reset_password()
	{
		return sprintf( 'Your %s Login has been Reset', get_bloginfo( 'name' ) );
	}


	/**
	 * Update the header
	 *
	 * @unused   param string $value
	 * @unused   param        $user
	 * @return string
	 * @since    7.35
	 * @verified 2024.02.14
	 */
	function wc_email_heading_customer_reset_password()
	{
		return sprintf( '%s Login Details', get_bloginfo( 'name' ) );
	}
}

acf_new_instance( 'lct_acf_ajax_send_user_login_invite' );
