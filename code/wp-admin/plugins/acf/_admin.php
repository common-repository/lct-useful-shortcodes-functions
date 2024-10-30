<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.02.24
 */
class lct_wp_admin_acf_admin
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.02.11
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
	 * @since    2017.12
	 * @verified 2017.09.27
	 */
	function load_hooks()
	{
		//bail early if already ran
		if ( lct_did() ) {
			return;
		}


		/**
		 * everytime - admin only
		 */
		add_filter( 'acf/location/rule_values/' . lct_org(), [ $this, 'register_rule_values_org' ] );

		add_filter( 'acf/location/rule_types', [ $this, 'register_rule_types' ] );


		if ( lct_wp_admin_non_ajax() ) {
			/**
			 * filters
			 */
			add_filter( 'manage_edit-acf-field-group_columns', [ $this, 'field_groups_columns' ], 11 );

			add_filter( 'acf/update_field', [ $this, 'update_field' ] );

			add_filter( 'lct/check_for_field_issues/duplicate_override', [ $this, 'check_for_field_issues_duplicate_override' ], 10, 2 );


			/**
			 * Special
			 */
			add_action( 'load-lct-panel_page_lct_acf_op_main_fixes_cleanups', [ $this, 'op_show_params_check_filters' ], 9 );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Load the filters for fields that need op_show_params_check()
	 *
	 * @since    7.21
	 * @verified 2018.08.30
	 */
	function op_show_params_check_filters()
	{
		add_filter( 'acf/prepare_field', [ $this, 'op_show_params_check' ] );
	}


	/**
	 * Hide params for people who don't know what they are.
	 *
	 * @param $field
	 *
	 * @return mixed
	 * @since    7.21
	 * @verified 2018.08.30
	 */
	function op_show_params_check( $field )
	{
		if (
			strpos( $field['_name'], 'show_params' ) !== false
			&& strpos( $field['_name'], 'show_params::' ) === false
			&& $field['value'] != 1
			&& (
				! ( $tmp = lct_is_user_a_dev() )
				|| empty( $tmp )
			)
		) {
			$field['conditional_logic'] = [
				[
					'field'    => $field['key'],
					'operator' => '==',
					'value'    => 1
				]
			];
		} elseif (
			strpos( $field['_name'], 'show_params' ) !== false
			&& (
				! ( $tmp = lct_is_user_a_dev() )
				|| empty( $tmp )
			)
		) {
			$field['conditional_logic'] = [
				[
					'field'    => $field['key'],
					'operator' => '==',
					'value'    => 'show'
				]
			];
		}


		return $field;
	}


	/**
	 * Add some custom columns to help us know where the heck the Field Groups go to.
	 *
	 * @param $columns
	 *
	 * @return mixed
	 * @date     2015.07.26
	 * @since    4.2.2.26
	 * @verified 2021.10.14
	 */
	function field_groups_columns( $columns )
	{
		$columns['lct_rule'] = 'Group Rules';


		return $columns;
	}


	/**
	 * Register all our awesome rules_types with ACF
	 *
	 * @param $choices
	 *
	 * @return array
	 * @since    7.17
	 * @verified 2016.10.11
	 */
	function register_rule_types( $choices )
	{
		$choices[ zxzb() ][ lct_org() ] = zxzb( ' Organization' );


		return $choices;
	}


	/**
	 * Register all org()s with ACF
	 *
	 * @param $choices
	 *
	 * @return mixed
	 * @since    7.17
	 * @verified 2019.04.02
	 */
	function register_rule_values_org( $choices )
	{
		$args = [
			'posts_per_page'         => - 1,
			'post_status'            => 'any',
			'orderby'                => 'title',
			'order'                  => 'ASC',
			'cache_results'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		];


		if ( lct_get_setting( 'use_org' ) ) {
			$args['post_type'] = 'lct_org';
			$orgs              = get_posts( $args );
		}


		if (
			lct_get_setting( 'use_org' )
			&& ! empty( $orgs )
		) {
			$choices['all']  = 'All';
			$choices['none'] = 'None';


			foreach ( $orgs as $org ) {
				$choices[ $org->ID ] = $org->post_title;
			}
		}


		return $choices;
	}


	/**
	 * We want to modify some things
	 *
	 * @param $field
	 *
	 * @return array
	 * @since    7.31
	 * @verified 2022.02.03
	 */
	function update_field( $field )
	{
		if (
			( $tmp = get_cnst( 'class_selector' ) )
			&& ! empty( $field[ $tmp ] )
			&& (
				in_array( 'dompdf_left', $field[ get_cnst( 'class_selector' ) ] )
				|| in_array( 'dompdf_right', $field[ get_cnst( 'class_selector' ) ] )
			)
		) {
			$field['wrapper']['width'] = 50;
		}


		return $field;
	}


	/**
	 * Allow some of our fields to be dupes
	 *
	 * @param null|true $override
	 * @param array     $field
	 *
	 * @return null|true
	 * @since    2018.33
	 * @verified 2022.09.29
	 */
	function check_for_field_issues_duplicate_override( $override, $field )
	{
		$overrides = [
			'lct:::org',
			'lct:::status',
			'lct:::dont_check_page_links',
			'lct:::dont_check_page_links',
			'lct:::tax_disable',
			'lct:::tax_public',
			'lct:::tax_show_in_admin_all_list',
			'lct:::tax_status',
		];


		if ( in_array( $field['_name'], $overrides ) ) {
			$override = true;
		}


		return $override;
	}
}
