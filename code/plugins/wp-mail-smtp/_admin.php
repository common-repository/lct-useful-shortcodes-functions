<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @verified 2020.04.09
 */
class lct_wp_mail_smtp_admin
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2020.04.09
	 */
	function __construct( $args = [] )
	{
		//Store $args
		$this->args = $args;


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


		/**
		 * everytime
		 */
		add_filter( 'wp_mail_smtp_options_get', [ $this, 'disable_smtp_on_dev' ], 10, 3 );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * We don't want the SMTP server to run when we are on dev
	 *
	 * @param mixed  $value
	 * @param string $group
	 * @param string $key
	 *
	 * @return mixed
	 * @since        2020.7
	 * @verified     2020.04.16
	 * @noinspection PhpUnusedParameterInspection
	 */
	function disable_smtp_on_dev( $value, $group, $key )
	{
		if ( ! lct_is_dev_or_sb() ) {
			remove_filter( 'wp_mail_smtp_options_get', [ $this, 'disable_smtp_on_dev' ] );


			return $value;
		}


		if ( $key === 'mailer' ) {
			$value = 'mail';
		}


		return $value;
	}
}
