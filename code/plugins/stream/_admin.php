<?php

use WP_Stream\Alert;


//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2019.08.21
 */
class lct_stream_admin
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2019.08.21
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
	 * @since    2019.23
	 * @verified 2019.08.21
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
		add_filter( 'wp_stream_alert_trigger_check', [ $this, 'trigger_check' ], 10, 4 );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Change the alert meta
	 *
	 * @param bool  $check
	 * @param int   $record_id
	 * @param array $recordarr
	 * @param Alert $class
	 *
	 * @return bool
	 * @since    2019.23
	 * @verified 2019.08.21
	 */
	function trigger_check(
		$check,
		/** @noinspection PhpUnusedParameterInspection */
		$record_id,
		/** @noinspection PhpUnusedParameterInspection */
		$recordarr,
		$class
	) {
		$find_n_replace = [
			'{site}' => get_bloginfo(),
		];
		$fnr            = lct_create_find_and_replace_arrays( $find_n_replace );


		if ( isset( $class->alert_meta['email_subject'] ) ) {
			$class->alert_meta['email_subject'] = str_replace( $fnr['find'], $fnr['replace'], $class->alert_meta['email_subject'] );
		}


		return $check;
	}
}
