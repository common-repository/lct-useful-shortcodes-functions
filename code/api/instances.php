<?php
/** @noinspection PhpMissingFieldTypeInspection */

//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class lct_instances
{
	/**
	 * Globalized classes
	 */
	/**
	 * @var lct_acf_loaded
	 * @since 2019.18
	 */
	public $acf_loaded;
	/**
	 * @var lct_acf_field_settings
	 * @since 2019.22
	 */
	public $acf_field_settings;
	/**
	 * @var lct_api_class
	 * @since 2019.18
	 */
	public $api_class;
	/**
	 * @var lct_asana
	 * @since 2019.21
	 */
	public $asana;
	/**
	 * @var lct_public
	 * @since 2019.18
	 */
	public $public;
	/**
	 * @var lct_acf_form
	 * @since 2020.14
	 */
	public $acf_form;
	/**
	 * @var lct_features_shortcodes_shortcodes
	 * @since 2020.5
	 */
	public $features_shortcodes_shortcodes;
	/**
	 * @var lct_wp_admin_acf_admin
	 * @since 2019.18
	 */
	public $wp_admin_acf_admin;
	/**
	 * @var lct_wp_admin_admin_admin
	 * @since 2019.18
	 */
	public $wp_admin_admin_admin;
	/**
	 * @var lct_wp_admin_admin_update
	 * @since 2019.18
	 */
	public $wp_admin_admin_update;


	/**
	 * A dummy constructor to ensure plugin is only initialized once
	 *
	 * @verified 2019.07.12
	 */
	function __construct()
	{
		/* Do nothing here */
	}


	/**
	 * The real constructor to initialize plugin
	 *
	 * @since    2019.18
	 * @verified 2019.07.12
	 */
	function init()
	{
		/* Do nothing here */
	}


	/**
	 * Creates a new instance of the given class and stores it in the instances data store
	 *
	 * @param string $class The class name
	 * @param array  $args
	 *
	 * @return object The instance
	 * @since    2019.18
	 * @verified 2019.07.15
	 */
	function new_instance( $class = '', $args = [] )
	{
		$r        = null;
		$instance = $class;

		if ( $args['class_suffix'] ) {
			$instance = $args['class_suffix'];
		}


		if ( class_exists( $class ) ) {
			$r = new $class( $args );


			$this->$instance = $r;
		}


		return $r;
	}

	/**
	 * Returns an instance for the given class
	 *
	 * @param string $class The class name
	 * @param array  $args
	 *
	 * @return    object The instance
	 * @since    2019.18
	 * @verified 2019.09.12
	 */
	function get_instance( $class = '', $args = [] )
	{
		$instance = $class;

		if ( $args['class_suffix'] ) {
			$instance = $args['class_suffix'];
		}


		if ( isset( $this->$instance ) ) {
			$r = $this->$instance;
		} else {
			$r = $this->new_instance( $class, $args );
		}


		return $r;
	}
}


/**
 * alias of lct_instances()->new_instance()
 *
 * @param string $class The class name
 * @param array  $args
 *
 * @return object The instance
 * @since    2019.18
 * @verified 2019.07.12
 */
function lct_new_instance( $class = '', $args = [] )
{
	return lct_instances()->new_instance( $class, $args );
}


/**
 * alias of lct_instances()->get_instance()
 *
 * @param string $class The class name
 * @param array  $args
 *
 * @return    object The instance
 * @since    2019.18
 * @verified 2019.07.12
 */
function lct_get_instance( $class = '', $args = [] )
{
	return lct_instances()->get_instance( $class, $args );
}


/**
 * @return lct_instances object
 * @since    2019.18
 * @verified 2019.07.12
 */
function lct_instances()
{
	global $lct_instances;


	if ( ! isset( $lct_instances ) ) {
		$lct_instances = new lct_instances();

		$lct_instances->init();
	}


	return $lct_instances;
}


/**
 * Initialize
 */
lct_instances();
