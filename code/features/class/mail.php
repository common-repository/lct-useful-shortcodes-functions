<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @verified 2017.04.20
 */
class lct_features_class_mail
{
	public $line_ending = "\r\n";
	public $content_type = 'text/html';
	public $From;
	public $to = [];
	public $Cc = [];
	public $Bcc = [];
	public $other_headers = [];
	public $subject = [];
	public $message = [];


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


		//Setup WordPress action and filter hooks
		$this->load_hooks();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    2017.33
	 * @verified 2017.04.20
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
		add_filter( 'wp_mail_content_type', [ $this, 'content_type' ] );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	function get_to()
	{
		return lct_return( $this->to, ',' );
	}


	function get_headers()
	{
		$headers = [];


		if ( $this->From ) {
			$headers[] = $this->From;
		}

		if ( ! empty( $this->Cc ) ) {
			$headers[] = 'Cc: ' . lct_return( $this->Cc, ',' );
		}

		if ( ! empty( $this->Bcc ) ) {
			$headers[] = 'Bcc: ' . lct_return( $this->Bcc, ',' );
		}

		if ( ! empty( $this->other_headers['headers'] ) ) {
			foreach ( $this->other_headers['headers'] as $header => $header_data ) {
				if ( ! empty( $this->other_headers[ $header ] ) ) {
					$headers[] = $header . ': ' . lct_return( $this->other_headers[ $header ], $header_data['delimiter'] );
				}
			}
		}


		return lct_return( $headers, $this->line_ending );
	}


	function get_subject()
	{
		return lct_return( $this->subject );
	}


	function get_message()
	{
		return lct_return( $this->message );
	}


	function set_line_ending( $line_ending )
	{
		if ( $line_ending ) {
			$this->line_ending = $line_ending;
		}
	}


	function set_content_type( $content_type )
	{
		if ( $content_type ) {
			$this->content_type = $content_type;
		}
	}


	/**
	 * Set email type
	 *
	 * @param $content_type
	 *
	 * @return string
	 * @since    0.0
	 * @verified 2016.10.31
	 */
	function content_type(
		/** @noinspection PhpUnusedParameterInspection */
		$content_type
	) {
		return $this->content_type;
	}


	function set( $header, $value, $force_overwrite = false, $delimiter = ',' )
	{
		$this->other_headers['headers'] = [ $header => [ 'delimiter' => $delimiter ] ];

		if (
			empty( $this->other_headers[ $header ] )
			|| (
				! empty( $this->other_headers[ $header ] )
				&& $force_overwrite
			)
		) {
			$this->other_headers[ $header ] = [ $value ];
		} else {
			$this->other_headers[ $header ] = array_merge( $this->other_headers[ $header ], [ $value ] );
		}
	}


	function set_to( $email, $force_overwrite = false )
	{
		if (
			empty( $this->to )
			|| (
				! empty( $this->to )
				&& $force_overwrite
			)
		) {
			$this->to = [ $email ];
		} else {
			$this->to = array_merge( $this->to, [ $email ] );
		}
	}


	function set_from( $from, $from_name, $force_overwrite = true )
	{
		if (
			! $this->From
			|| (
				$this->From
				&& $force_overwrite
			)
		) {
			if ( empty( $from_name ) ) {
				$from_name = $from;
			}

			$from = sprintf( 'From: "%s" <%s>', $from_name, $from );

			$this->From = $from;
		}
	}


	function set_cc( $email, $force_overwrite = false )
	{
		if (
			empty( $this->Cc )
			|| (
				! empty( $this->Cc )
				&& $force_overwrite
			)
		) {
			$this->Cc = [ $email ];
		} else {
			$this->Cc = array_merge( $this->Cc, [ $email ] );
		}
	}


	function set_bcc( $email, $force_overwrite = true )
	{
		if (
			empty( $this->Bcc )
			|| (
				! empty( $this->Bcc )
				&& $force_overwrite
			)
		) {
			$this->Bcc = [ $email ];
		} else {
			$this->Bcc = array_merge( $this->Bcc, [ $email ] );
		}
	}


	/**
	 * Set the email's subject
	 *
	 * @param      $subject
	 * @param bool $force_overwrite
	 */
	function set_subject( $subject, $force_overwrite = true )
	{
		if (
			empty( $this->subject )
			|| (
				! empty( $this->subject )
				&& $force_overwrite
			)
		) {
			$this->subject = [ $subject ];
		} else {
			$this->subject = array_merge( $this->subject, [ $subject ] );
		}
	}


	/**
	 * Set/Append to an email message
	 *
	 * @param $message
	 * @param $line_ending
	 */
	function set_message( $message, $line_ending = '<br />' )
	{
		$message = array_merge( $this->message, [ $message . $line_ending ] );

		$this->message = $message;
	}


	function send()
	{
		if ( $this->to ) {
			return wp_mail( $this->get_to(), $this->get_subject(), $this->get_message(), $this->get_headers() );
		}


		return false;
	}
}
