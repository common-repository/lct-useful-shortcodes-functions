<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @verified 2017.03.24
 */
class lct_wc_shortcodes
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.03.24
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
	 * @since    2017.26
	 * @verified 2017.03.24
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
		add_shortcode( zxzu( 'wc_login_form' ), [ $this, 'wc_login_form' ] );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * [lct_wc_login_form]
	 * Generate the WC login form
	 *
	 * @return string
	 * @since    5.25
	 * @verified 2017.03.24
	 */
	function wc_login_form()
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r' => '',
			],
			[]
		);


		global $wp;


		ob_start();


		if ( ! is_user_logged_in() ) {
			$message = apply_filters( 'woocommerce_my_account_message', '' );

			if ( ! empty( $message ) ) {
				wc_add_notice( $message );
			}


			if ( isset( $wp->query_vars['lost-password'] ) ) {
				WC_Shortcode_My_Account::lost_password();
			} else {
				wc_get_template( 'myaccount/form-login.php' );
			}
		}


		$a['r'] = ob_get_clean();


		return $a['r'];
	}
}
