<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.07.31
 */
class lct_revslider_admin
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.07.31
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
	 * @since    7.62
	 * @verified 2017.07.31
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
		add_filter( 'revslider_slide_setLayersByPostData_post', [ $this, 'revslider_slide_setLayersByPostData_post' ], 10, 4 );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		if ( lct_wp_admin_non_ajax() ) {
			add_action( 'add_meta_boxes', [ $this, 'remove_meta_boxes' ], 999999 );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Remove stupid useless revslider metabox
	 *
	 * @since    7.62
	 * @verified 2017.04.28
	 */
	function remove_meta_boxes()
	{
		if (
			isset( $_GET['post'] )
			|| isset( $_GET['post_type'] )
		) {
			/**
			 * Revslider Meta Box
			 */
			$post_types = get_post_types();


			if ( ! empty( $post_types ) ) {
				foreach ( $post_types as $post_type ) {
					remove_meta_box( 'mymetabox_revslider_0', $post_type, 'normal' );
					remove_meta_box( 'mymetabox_revslider_0', $post_type, 'side' );
					remove_meta_box( 'mymetabox_revslider_0', $post_type, 'advanced' );
				}
			}
		}
	}


	/**
	 * Check the content for our shortcodes
	 *
	 * @param $attr
	 *
	 * @unused   param $postData
	 * @unused   param $sliderID
	 * @unused   param $RevSliderSlide
	 * @return mixed
	 * @since    2017.59
	 * @verified 2017.08.10
	 */
	function revslider_slide_setLayersByPostData_post( $attr )
	{
		$attr['content'] = lct_check_for_nested_shortcodes( $attr['content'] );


		//Add no-lazy to images
		if ( ! empty( $attr['img_urls'] ) ) {
			foreach ( $attr['img_urls'] as $img_url_key => $img_url ) {
				if ( $img_url['tag'] ) {
					$attr['img_urls'][ $img_url_key ]['tag'] = str_replace( '<img src', '<img class="no-lazy" src', $img_url['tag'] );
				}
			}
		}


		return $attr;
	}
}
