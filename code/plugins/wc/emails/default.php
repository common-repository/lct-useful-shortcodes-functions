<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'lct_wc_emails_lct_default' ) ) :
	/**
	 * Default email
	 *
	 * @version     2.3.0
	 * @package     WC/Classes/Emails
	 * @author      WooThemes
	 * @extends     WC_Email
	 * @since       7.36
	 * @verified    2016.11.19
	 */
	class lct_wc_emails_lct_default extends WC_Email
	{
		/**
		 * User email.
		 *
		 * @var string
		 */
		public $user_email;
		/**
		 * User login name.
		 *
		 * @var string
		 */
		public $body;


		/**
		 * Constructor.
		 *
		 * @verified 2017.03.24
		 */
		function __construct()
		{
			$this->id             = zxzu( 'default' );
			$this->customer_email = true;
			$this->title          = __( 'Default Email Template', 'TD_LCT' );
			$this->description    = __( 'Default Email Template', 'TD_LCT' );

			$this->template_html  = 'emails/' . zxzu( 'default.php' );
			$this->template_plain = 'emails/plain/' . zxzu( 'default.php' );

			$this->subject = __( 'Default Email Subject', 'TD_LCT' );
			$this->heading = __( 'Default Email Heading', 'TD_LCT' );


			// Call parent constructor
			parent::__construct();
		}


		/**
		 * Trigger.
		 *
		 * @param        $user_id
		 * @param string $body
		 * @param null   $subject
		 * @param null   $heading
		 *
		 * @since    7.36
		 * @verified 2017.03.24
		 */
		function trigger( $user_id, $body = '', $subject = null, $heading = null )
		{
			if ( is_email( $user_id ) ) {
				$this->recipient = $user_id;
			} elseif ( $user_id ) {
				$this->object = new WP_User( $user_id );

				$this->user_email = stripslashes( $this->object->user_email );
				$this->recipient  = $this->user_email;
			}


			if ( ! $this->get_recipient() ) {
				return;
			}


			$this->body = $body;


			if ( $subject ) {
				$this->subject = $subject;
			}


			if ( $heading ) {
				$this->heading = $heading;
			}


			$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
		}


		/**
		 * Get content html.
		 *
		 * @access   public
		 * @return string
		 * @since    7.36
		 * @verified 2017.03.24
		 */
		function get_content_html()
		{
			return wc_get_template_html(
				$this->template_html,
				[
					'email_heading' => $this->get_heading(),
					'body'          => $this->body,
					'sent_to_admin' => false,
					'plain_text'    => false,
					'email'         => $this
				]
			);
		}


		/**
		 * Get content plain.
		 *
		 * @access   public
		 * @return string
		 * @since    7.36
		 * @verified 2017.03.24
		 */
		function get_content_plain()
		{
			return wc_get_template_html(
				$this->template_plain,
				[
					'email_heading' => $this->get_heading(),
					'body'          => $this->body,
					'sent_to_admin' => false,
					'plain_text'    => true,
					'email'         => $this
				]
			);
		}
	}
endif;


return new lct_wc_emails_lct_default();
