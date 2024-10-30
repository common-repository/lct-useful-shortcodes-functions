<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2019.07.23
 */
class lct_asana_acf
{
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


		if ( ! lct_plugin_active( 'acf' ) ) {
			return;
		}


		/**
		 * everytime
		 */
		add_filter( 'acf/load_field/name=' . zxzacf( 'asana::workspaces' ), [ $this, 'asana_workspaces_choices' ] );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Add a list of all Asana workspaces to the dropdown choices
	 *
	 * @param array $field
	 *
	 * @return array
	 * @since    2019.21
	 * @verified 2019.12.05
	 */
	function asana_workspaces_choices( $field )
	{
		if ( lct_acf_is_field_group_editing_page() ) { //Don't load on ACf edit pages
			return $field;
		}


		$choices = [];
		$asana   = lct_instances()->asana;


		if ( $asana->is_authorized() ) {
			foreach ( $asana->get_client()->workspaces->findAll() as $workspace ) {
				$choices[ $workspace->gid ] = $workspace->name;
			}


			$field['choices'] = $choices;
		}


		return $field;
	}
}
