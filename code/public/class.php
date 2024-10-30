<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2016.11.28
 */
class lct_public
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2016.11.28
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


		$this->load_other_public_classes();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    7.40
	 * @verified 2016.11.28
	 */
	function load_hooks()
	{
		//bail early if already ran
		if ( lct_did() ) {
			return;
		}
	}


	/**
	 * Load the other plugin public classes
	 *
	 * @since    7.40
	 * @verified 2016.12.20
	 */
	function load_other_public_classes()
	{
		$plugin = 'acf';

		if ( lct_plugin_active( $plugin ) ) {
			lct_load_class( 'plugins/' . $plugin . '/public.php', 'public', [ 'plugin' => $plugin, 'globalize' => true ] );

			lct_load_class( 'plugins/' . $plugin . '/public_choices.php', 'public_choices', [ 'plugin' => $plugin, 'globalize' => true ] );
		}
	}
}
