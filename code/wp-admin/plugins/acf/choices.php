<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2016.12.08
 */
class lct_wp_admin_acf_choices
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2016.12.08
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
	 * @since    7.50
	 * @verified 2018.03.20
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
		add_filter( 'lct/pretty_wp_taxonomies_data/hidden_taxonomies', [ $this, 'exclude_taxonomies' ], 10, 2 );

		add_filter( 'lct/pretty_wp_post_types_data/hidden_post_types', [ $this, 'exclude_post_types' ], 10, 2 );


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Exclude Avada & WP taxonomies to keep it clean
	 *
	 * @param $exclude
	 * @param $field
	 *
	 * @return mixed
	 * @since    7.50
	 * @verified 2018.03.20
	 */
	function exclude_taxonomies( $exclude, $field )
	{
		if (
			isset( $field['_name'] )
			&& in_array( $field['_name'], [ zxzacf( 'remove_meta_boxes_taxonomies' ) ] )
		) {
			$custom_exclude = [
				'category',
				'post_tag',
				'post_format',
				'slide-page',
				'portfolio_category',
				'portfolio_skills',
				'portfolio_tags',
				'faq_category',
				'themefusion_es_groups',
				'element_category',
				'template_category',
				'action-group',
				'product_type',
				'product_cat',
				'product_tag',
				'product_shipping_class',
				'bp_member_type',
				'bp-email-type',
			];
			$exclude        = array_merge( $exclude, $custom_exclude );
		}


		return $exclude;
	}


	/**
	 * Exclude Avada & WP post_types to keep it clean
	 *
	 * @param $exclude
	 * @param $field
	 *
	 * @return mixed
	 * @since    7.50
	 * @verified 2018.03.20
	 */
	function exclude_post_types( $exclude, $field )
	{
		if (
			isset( $field['_name'] )
			&& in_array( $field['_name'], [ zxzacf( 'remove_avada_options_post_types' ), zxzacf( 'remove_featured_image_post_types' ) ] )
		) {
			$custom_exclude = [
				'post',
				'page',
				'attachment',
				'slide',
				'avada_page_options',
				'avada_portfolio',
				'avada_faq',
				'themefusion_elastic',
				'fusion_template',
				'fusion_element',
				'scheduled-action',
				'product',
				'product_variation',
				'shop_order',
				'shop_order_refund',
				'shop_webhook',
				'shop_coupon',
				'bp-email',
				'wc_membership_plan',
				'wc_user_membership',
				'wp_stream_alerts',
			];
			$exclude        = array_merge( $exclude, $custom_exclude );
		}


		return $exclude;
	}
}
