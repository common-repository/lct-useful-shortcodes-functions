<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.03.24
 */
class lct_wc_emails
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
	 * @since    7.36
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
		add_action( 'lct/wc/email_default', [ $this, 'email_default' ], 10, 4 );


		add_filter( 'woocommerce_email_classes', [ $this, 'add_wc_email_classes' ] );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Default email
	 *
	 * @param        $user_id
	 * @param string $body
	 * @param null   $subject
	 * @param null   $heading
	 *
	 * @since    7.36
	 * @verified 2017.08.03
	 */
	function email_default( $user_id, $body = '', $subject = null, $heading = null )
	{
		if ( ! $user_id ) {
			return;
		}


		//Load the email class
		$wc_emails = WC_Emails::instance();
		$email     = $wc_emails->emails[ zxzu( 'default' ) ];


		//trigger the email to be sent
		/** @noinspection PhpUndefinedMethodInspection */
		$email->trigger( $user_id, $body, $subject, $heading );
	}


	/**
	 * Add custom emails to the list of emails WC should load
	 *
	 * @param array $email_classes available email classes
	 *
	 * @return array filtered available email classes
	 * @since    7.36
	 * @verified 2016.11.19
	 */
	function add_wc_email_classes( $email_classes )
	{
		require( 'default.php' );

		$email_classes[ zxzu( 'default' ) ] = new lct_wc_emails_lct_default();


		return $email_classes;
	}
}
