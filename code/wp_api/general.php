<?php
/** @noinspection PhpMissingFieldTypeInspection */
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @verified 2022.09.28
 */
class lct_wp_api_general
{
	/**
	 * @var string
	 */
	public $namespace = 'lct/v1';
	/**
	 * For more details
	 * See jQuery: lct_acf_api_call()
	 *
	 * @var array
	 */
	public array $response_data = [
		'status'        => 'fail',
		'atts'          => [],
		'html'          => null,
		'response_html' => null,
		'alert_status'  => null,
		'alert_html'    => null,
	];
	/**
	 * @var int
	 */
	public int $resp_status = 200;


	/**
	 * Start up the class
	 *
	 * @verified 2022.09.28
	 */
	function __construct()
	{
		//Setup WordPress action and filter hooks
		$this->load_hooks();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    2020.5
	 * @verified 2022.09.28
	 */
	function load_hooks()
	{
		//bail early if already ran
		if ( lct_did() ) {
			return;
		}


		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}


	/**
	 * Register our routes
	 *
	 * @since    2020.5
	 * @verified 2022.09.28
	 */
	function register_routes()
	{
		/**
		 * do_shortcode() On Demand
		 */
		//Register
		register_rest_route(
			$this->namespace,
			'do_shortcode',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'do_shortcode' ],
				'permission_callback' => [ $this, 'permission_check' ],
			]
		);
	}


	/**
	 * Table a table filter
	 *
	 * @path     /wp-json/lct/v1/do_shortcode
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 * @since    2020.5
	 * @verified 2022.09.13
	 */
	function do_shortcode( $request )
	{
		$this->response_data['status'] = 'valid';
		$this->response_data['html']   = do_shortcode( $request->get_param( 'shortcode' ) );


		return new WP_REST_Response( $this->response_data, $this->resp_status );
	}


	/**
	 * Check whether the function is allowed to be run. Must have either capabilities to enact action, or a valid nonce.
	 *
	 * @return bool Does the user have access to LCT api?
	 * @since    2020.5
	 * @verified 2020.02.21
	 */
	function permission_check()
	{
		return true;
	}
}
