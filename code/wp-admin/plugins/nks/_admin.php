<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @verified 2017.06.26
 */
class lct_wp_admin_nks_admin
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.06.26
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
	 * @since    2017.49
	 * @verified 2017.06.26
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
		add_action( 'admin_init', [ $this, 'set_nks_cc_options' ] );

		add_action( 'pre_update_option_nks_cc_options', [ $this, 'update_nks_cc_options' ], 10, 3 );


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Set the defaults if they are not set yet
	 *
	 * @since    2017.49
	 * @verified 2017.06.29
	 */
	function set_nks_cc_options()
	{
		$all_options = wp_load_alloptions();
		$nks         = 'nks_cc_options';


		if ( ! array_key_exists( $nks, $all_options ) ) {
			$default_options = [
				'nks_cc_display_1'          => '{"user":{"everyone":1,"loggedin":0,"loggedout":0},"mobile":{"yes":1,"no":0},"rule":{"include":0,"exclude":1},"location":{"pages":{},"cposts":{},"cats":{},"taxes":{},"langs":{},"wp_pages":{},"ids":[]}}',
				'nks_cc_content_1'          => '[lct_avada_mobile_main_menu]',
				'nks_cc_link_1'             => '',
				'nks_cc_css_1'              => '',
				'nks_cc_fa_icon_1'          => 'Font Awesome_####_bars',
				'nks_cc_label_color_1'      => '#ff1bef',
				'nks_cc_label_style_1'      => 'square',
				'nks_cc_tab_tooltip_1'      => 'Not Used',
				'nks_cc_tab_bg_1'           => '#ff1bef',
				'nks_cc_tab_image_bg_1'     => 'none',
				'nks_cc_tab_text_color_1'   => '#000000',
				'nks_cc_tabs'               => '1',
				'nks_cc_sidebar_type'       => 'slide',
				'nks_cc_sidebar_scale'      => 'yes',
				'nks_cc_sidebar_pos'        => 'left',
				'nks_cc_sidebar_width'      => '400',
				'nks_cc_sidebar_gaps'       => '20',
				'nks_cc_base_color'         => '#333333',
				'nks_cc_selectors'          => '.lct_mobi_slide_menu_button',
				'nks_cc_fade_content'       => 'light',
				'nks_cc_label_size'         => '2x',
				'nks_cc_label_tooltip'      => 'none',
				'nks_cc_tooltip_color'      => 'rgba(0, 0, 0, 0.7)',
				'nks_cc_label_top'          => '10px',
				'nks_cc_label_top_mob'      => '10px',
				'nks_cc_label_vis'          => 'visible',
				'nks_cc_label_vis_selector' => '',
			];


			update_option( $nks, $default_options, true );
		}
	}


	/**
	 * Remove the damn wpautop tags
	 *
	 * @param $value
	 *
	 * @unused   param $old_value
	 * @unused   param $option
	 * @return mixed
	 * @since    2017.53
	 * @verified 2017.06.29
	 */
	function update_nks_cc_options( $value )
	{
		$find = '<p>[lct_avada_mobile_main_menu]</p>';


		if (
			! empty( $value['nks_cc_content_1'] )
			&& strpos( $value['nks_cc_content_1'], $find ) !== false
		) {
			$value['nks_cc_content_1'] = str_replace( $find, '[lct_avada_mobile_main_menu]', $value['nks_cc_content_1'] );
		}


		return $value;
	}
}
