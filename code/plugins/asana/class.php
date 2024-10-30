<?php
/**
 * @noinspection PhpMissingFieldTypeInspection
 */
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2019.07.23
 */
class lct_asana
{
	/**
	 * @var Asana\Client
	 */
	public $client = null;
	/**
	 * @var string
	 */
	public $client_id = null;
	/**
	 * @var string
	 */
	public $client_secret = null;
	/**
	 * @var string
	 */
	public $token = null;
	/**
	 * @var bool
	 */
	public $is_authorized = null;
	/**
	 * @var bool
	 */
	public $oauth_active = false;


	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2019.07.23
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
	 * @since    2019.21
	 * @verified 2019.07.23
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
		add_action( 'init', [ $this, 'new_oauth_check' ] );


		if ( lct_frontend() ) {
			add_shortcode( zxzu( 'asana_tags_list' ), [ $this, 'asana_tags_list' ] );
		}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Include the Asana library
	 *
	 * @since    2019.21
	 * @verified 2019.07.23
	 */
	function include_library()
	{
		//bail early if already ran
		if ( lct_did() ) {
			return;
		}


		if (
			! ( $api_includes = $_SERVER['DOCUMENT_ROOT'] . '/apps/inc/vendor/autoload.php' )
			|| ! file_exists( $api_includes )
		) {
			$this->is_authorized = false;
		}


		/** @noinspection PhpIncludeInspection */
		require_once $api_includes;
	}


	/**
	 * If we were just sent back from Asana we need to update our DB
	 *
	 * @since    2019.21
	 * @verified 2020.11.11
	 */
	function new_oauth_check()
	{
		if (
			empty( $_GET['code'] )
			|| empty( $_GET['state'] )
			|| ! lct_acf_get_option_raw( 'asana::access_api', true )
		) {
			return;
		}


		$this->oauth_active = true;


		$this->include_library();
		$this->get_client();
		$this->set_token();


		$url = trim( $_SERVER['HTTP_REFERER'], '/' ) . remove_query_arg( 'code', $_SERVER['REQUEST_URI'] );
		$url = remove_query_arg( 'state', $url );
		lct_wp_safe_redirect( $url );
	}


	/**
	 * Maybe redirect to Asana
	 *
	 * @since    2019.21
	 * @verified 2019.07.23
	 */
	function maybe_oauth_redirect()
	{
		if (
			! $this->oauth_active
			&& ! $this->is_authorized()
			&& ! $this->client->dispatcher->accessToken
		) {
			$state = null;
			lct_wp_safe_redirect( $this->client->dispatcher->authorizationUrl( $state ) );
		}
	}


	/**
	 * Save token in the DB
	 *
	 * @since    2019.21
	 * @verified 2019.07.23
	 */
	function set_token()
	{
		if (
			! $this->is_authorized()
			&& ( $token = $this->client->dispatcher->fetchToken( $_GET['code'] ) )
		) {
			$this->refresh_token( $token );
		}
	}


	/**
	 * Save token in the DB
	 *
	 * @since    2019.21
	 * @verified 2019.07.23
	 */
	function maybe_refresh_token()
	{
		if (
			! $this->oauth_active
			&& ! $this->is_authorized()
			&& ( $token = $this->client->dispatcher->refreshAccessToken() )
		) {
			$this->refresh_token( $token );
		}
	}


	/**
	 * Save token in the DB
	 *
	 * @param string $token
	 *
	 * @since        2019.21
	 * @verified     2022.01.21
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function refresh_token( $token )
	{
		if (
			$token
			&& ( $date = lct_current_time( true ) )
		) {
			$date->modify( '+' . $this->client->dispatcher->expiresIn . ' seconds' );


			$token = [
				'token' => $token,
				'exp'   => $date->format( 'U' ),
			];


			lct_update_option( 'asana::token', wp_json_encode( $token ) );
		}
	}


	/**
	 * Get the token from the DB
	 *
	 * @since    2019.21
	 * @verified 2019.07.23
	 */
	function get_token()
	{
		if (
			! $this->token
			&& ( $token = json_decode( lct_get_option( 'asana::token' ), true ) )
			&& ! empty( $token['token'] )
			&& ! empty( $token['exp'] )
			&& $token['exp'] > lct_format_current_time( 'U', true )
		) {
			$this->token = $token['token'];
		}


		return $this->token;
	}


	/**
	 * Get the client from Asana
	 *
	 * @since    2019.21
	 * @verified 2019.07.23
	 */
	function get_client()
	{
		if ( $this->client ) {
			return $this->client;
		}


		$this->client = Asana\Client::oauth( [
			'client_id' => $this->client_id,
			'token'     => $this->get_token()
		] );


		$this->maybe_new_client();
		$this->maybe_oauth_redirect();
		$this->maybe_refresh_token();


		return $this->client;
	}


	/**
	 * Maybe we aren't authorized yet
	 *
	 * @since    2019.21
	 * @verified 2019.07.23
	 */
	function maybe_new_client()
	{
		if (
			! $this->is_authorized()
			&& ! $this->client->dispatcher->accessToken
		) {
			$this->client = Asana\Client::oauth( [
				'client_id'     => $this->client_id,
				'client_secret' => $this->client_secret,
				'redirect_uri'  => home_url( '/' ),
			] );
		}
	}


	/**
	 * Check if we are allowed to access Asana
	 *
	 * @since    2019.21
	 * @verified 2019.07.23
	 */
	function is_authorized()
	{
		//bail early if already ran
		if ( $this->is_authorized !== null ) {
			return $this->is_authorized;
		}


		if (
			! lct_did()
			&& (
				! lct_acf_get_option_raw( 'asana::access_api', true )
				|| ! ( $this->client_id = lct_acf_get_option_raw( 'asana::client_id' ) )
				|| ! ( $this->client_secret = lct_acf_get_option_raw( 'asana::client_secret' ) )
			)
		) {
			$this->is_authorized = false;


			return $this->is_authorized;
		}


		$this->include_library();


		if ( ! $this->client ) {
			$this->get_client();
		}


		if ( $this->client->dispatcher->authorized ) {
			$this->is_authorized = true;
		} else {
			$this->is_authorized = false;
		}


		return $this->is_authorized;
	}


	/**
	 * Get the workspaces we want to access
	 *
	 * @since    2019.21
	 * @verified 2019.07.23
	 */
	function get_selected_workspaces()
	{
		$r = [];


		if ( $workspaces = lct_acf_get_option( 'asana::workspaces' ) ) {
			$r = $workspaces;
		}


		return $r;
	}


	/**
	 * Get the workspace ids we want to access
	 *
	 * @since    2019.21
	 * @verified 2019.07.23
	 */
	function get_selected_workspace_ids()
	{
		$r = [];


		if ( $workspaces = lct_acf_get_option( 'asana::workspaces', false ) ) {
			$r = $workspaces;
		}


		return $r;
	}


	/**
	 * [lct_asana_tags_list]
	 * Display the Asana tags of the logged in user's workspace(s)
	 *
	 * @att          string workspace
	 *
	 * @param $a
	 *
	 * @return string
	 * @since        2019.21
	 * @verified     2019.07.23
	 * @noinspection PhpMethodParametersCountMismatchInspection
	 */
	function asana_tags_list( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'         => [],
				'workspace' => '',
			],
			$a
		);


		if ( $a['workspace'] ) {
			$a['workspace'] = explode( ',', $a['workspace'] );
		} else {
			$a['workspace'] = $this->get_selected_workspace_ids();
		}


		if (
			! empty( $a['workspace'] )
			&& $this->is_authorized()
		) {
			foreach ( $a['workspace'] as $workspace_id ) {
				$tags = [];


				foreach ( $this->get_client()->tags->findByWorkspace( $workspace_id ) as $tag ) {
					$tags[ $tag->gid ] = $tag->name;
				}


				if ( ! empty( $tags ) ) {
					$workspace = $this->get_client()->workspaces->findById( $workspace_id );


					asort( $tags );


					$a['r'][] = '<h2 style="margin-bottom: 0;">' . ucwords( $workspace->name ) . ' Labels (' . count( $tags ) . ')</h2>';


					foreach ( $tags as $tag_k => $tag ) {
						$a['r'][] = $tag . ' (' . $tag_k . ')';
					}
				}
			}
		}


		return lct_return( $a['r'], '<br />' );
	}
}
