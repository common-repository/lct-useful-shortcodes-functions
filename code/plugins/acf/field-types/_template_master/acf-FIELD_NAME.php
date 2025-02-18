<?php

/*
Plugin Name: Advanced Custom Fields: FIELD_LABEL
Plugin URI: PLUGIN_URL
Description: SHORT_DESCRIPTION
Version: 1.0.0
Author: AUTHOR_NAME
Author URI: AUTHOR_URL
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// check if class already exists
if ( ! class_exists( 'acf_plugin_FIELD_NAME' ) ) :

	class acf_plugin_FIELD_NAME
	{

		/*
		*  __construct
		*
		*  This function will set up the class functionality
		*
		*  @type	function
		*  @date	17/02/2016
		*  @since	1.0.0
		*
		*  @param	n/a
		*  @return	n/a
		*/

		function __construct()
		{
			// vars
			$this->settings = [
				'version' => '1.0.0',
			];


			// set text domain
			// https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
			load_plugin_textdomain( 'acf-FIELD_NAME', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );


			// include field
			add_action( 'acf/include_field_types', [ $this, 'include_field_types' ] ); // v5
			add_action( 'acf/register_fields', [ $this, 'include_field_types' ] );     // v4

		}


		/*
		*  include_field_types
		*
		*  This function will include the field type class
		*
		*  @type	function
		*  @date	17/02/2016
		*  @since	1.0.0
		*
		*  @param	$version (int) major ACF version. Defaults to false
		*  @return	n/a
		*/

		function include_field_types( $version = false )
		{
			// support empty $version
			if ( ! $version ) {
				$version = 4;
			}


			// include
			include_once( 'fields/acf-FIELD_NAME-v' . $version . '.php' );

		}

	}


	// initialize
	new acf_plugin_FIELD_NAME();


	// class_exists check
endif;
