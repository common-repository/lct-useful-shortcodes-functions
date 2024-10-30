<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.02.11
 */
class lct_wp_admin_acf_actions
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
	 * @verified 2017.06.07
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


		if ( lct_wp_admin_non_ajax() ) {
			add_action( 'manage_acf-field-group_posts_custom_column', [ $this, 'field_groups_columns_values' ], 11, 2 );

			add_action( 'load-user-edit.php', [ $this, 'cleanup_profile_page' ] );
			add_action( 'load-profile.php', [ $this, 'cleanup_profile_page' ] );

			add_action( 'admin_init', [ $this, 'use_page_note' ] );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Process the values for our custom columns
	 *
	 * @param $column
	 * @param $post_id
	 *
	 * @date     2015.07.26
	 * @since    4.2.2.26
	 * @verified 2021.10.14
	 */
	function field_groups_columns_values( $column, $post_id )
	{
		if (
			$post_id
			&& $column === 'lct_rule'
			&& ( $group = acf_get_field_group( $post_id ) )
			&& ( $location_rules = acf_extract_var( $group, 'location' ) )
			&& ! empty( $location_rules )
		) {
			$rules = [];
			$space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			$AND   = ' <strong style="font-weight: bold;">AND</strong><br />';
			$OR    = '<br /><strong style="font-weight: bold;">OR</strong><br />';


			foreach ( $location_rules as $location_or ) {
				$rules_and = [];


				foreach ( $location_or as $location_and ) {
					$rules_and[] = $location_and['param'] . $space . $location_and['operator'] . $space . $location_and['value'];
				}


				if ( ! empty( $rules_and ) ) {
					$rules[] = lct_return( $rules_and, $AND );
				}
			}


			echo lct_return( $rules, $OR );
		}
	}


	/**
	 * We can hide some things that we never use.
	 *
	 * @since    5.37
	 * @verified 2018.08.30
	 */
	function cleanup_profile_page()
	{
		if ( lct_acf_get_option_raw( 'hide_contact_methods_on_profile_page' ) ) {
			lct_update_later( 'allow_function', true, 'remove_contactmethods' );
		}


		if ( lct_acf_get_option_raw( 'hide_woocommerce_items_on_profile_page' ) ) {
			add_filter( 'woocommerce_customer_meta_fields', '__return_empty_array' );
		}


		if ( lct_acf_get_option_raw( 'hide_color_picker_on_profile_page' ) ) {
			global $pagenow;


			if ( $pagenow === 'user-edit.php' ) {
				remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
			}
		}
	}


	/**
	 * Load things needed for page_note
	 *
	 * @since    0.0
	 * @verified 2018.08.30
	 */
	function use_page_note()
	{
		if ( lct_acf_get_option_raw( 'use_page_note' ) ) {
			add_filter( 'manage_edit-page_columns', [ $this, 'page_field_groups_columns' ], 1 );
			add_action( 'manage_pages_custom_column', [ $this, 'page_field_groups_columns_values' ], 1 );
		}
	}


	/**
	 * Add some custom columns to display page notes
	 *
	 * @param $columns
	 *
	 * @return array
	 * @since    0.0
	 * @verified 2016.10.21
	 */
	function page_field_groups_columns( $columns )
	{
		$new_columns = [];


		foreach ( $columns as $column_key => $column ) {
			$new_columns[ $column_key ] = $column;

			if ( $column_key == 'title' ) {
				$new_columns[ zxzacf( 'page_note' ) ] = 'Page Notes';
			}
		}


		return $new_columns;
	}


	/**
	 * Add some custom columns to display page notes
	 *
	 * @param $column
	 * @param $post_id
	 *
	 * @since    0.0
	 * @verified 2016.10.21
	 */
	function page_field_groups_columns_values( $column, $post_id = null )
	{
		if (
			$column == zxzacf( 'page_note' )
			&& get_field( zxzacf( 'has_page_note' ), $post_id )
		) {
			echo sprintf(
				'<span style="color: red;font-weight: bold;">%s</span>',
				get_field( zxzacf( 'page_note' ), $post_id )
			);
		}
	}
}
