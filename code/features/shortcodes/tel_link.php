<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @verified 2017.04.20
 */
class lct_features_shortcodes_tel_link
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.04.20
	 */
	function __construct( $args = [] )
	{
		//Store $args
		$this->args = $args;


		//Store parent class, maybe
		if ( $this->args['load_parent'] ) {
			$this->zxzp = lct();
		}


		$this->il      = 'tel_link';
		$this->il_full = zxzu( $this->il );


		//Setup WordPress action and filter hooks
		$this->load_hooks();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    2017.33
	 * @verified 2017.04.20
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
		add_shortcode( $this->il_full, [ $this, 'add_shortcode' ] );


		if ( $this->page_supports_add_button() ) {
			lct_root_include( 'available/tooltips.php' );


			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_styles' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

			add_action( 'print_media_templates', [ $this, 'print_media_templates' ] );

			add_action( 'media_buttons', [ $this, 'add_button' ], 20 );

			add_action( 'admin_footer', [ $this, 'add_mce_popup' ] );
		}


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Register Styles
	 */
	function admin_enqueue_styles()
	{
		lct_admin_enqueue_style( $this->il_full, lct_get_root_url( 'assets/wp-admin/css/' . $this->il . '.min.css' ) );
	}


	/**
	 * Register Scripts
	 */
	function admin_enqueue_scripts()
	{
		wp_enqueue_script( $this->il_full, lct_get_root_url( 'assets/wp-admin/js/' . $this->il . '.min.js' ), [ 'jquery', 'wp-backbone' ], lct_get_setting( 'version' ), true );

		wp_localize_script(
			$this->il_full,
			$this->il_full . 'ShortcodeUIData',
			[
				'ajax_url'        => admin_url( 'admin-ajax.php' ),
				'shortcodes'      => $this->get_shortcodes(),
				'previewDisabled' => true,
				'strings'         => [
					'pleaseEnterAPhone'   => 'Please enter a phone number.',
					'errorLoadingPreview' => 'Failed to load the preview for this phone number.',
				]
			]
		);
	}


	/**
	 * Should we load everything?
	 *
	 * @return bool
	 */
	function page_supports_add_button()
	{
		global $pagenow;

		$r = false;


		if (
			is_admin()
			&& isset( $pagenow )
			&& $pagenow
			&& in_array( $pagenow, [ 'post.php', 'page.php', 'page-new.php', 'post-new.php' ] )
		) {
			$r = true;
		}


		return $r;
	}


	/**
	 * @param $a
	 * [lct_tel_link]
	 * Syntax:
	 * [lct_tel_link phone='{REQUIRED, with formatting}' action='{defaults to "tel_link", but you can change it in the advanced options}' category='{defaults to "{pre} {phone} {post}", but you can change it in the advanced options}' class='{optional}' pre='{optional pre text}' post='{optional post text}' text='{optional link text override}']
	 * converts to
	 * <a class="{class}" href="tel:{phone}" onclick="_gaq.push(['_trackEvent', '{category}', '{action}'])">{pre} {phone} {post}</a>
	 * Examples:
	 * (Basic)
	 * [lct_tel_link phone='(970) 555-1234']
	 * converts to
	 * <a href="tel:9705551234" onclick="_gaq.push(['_trackEvent', 'tel_link', '(970) 555-1234'])">(970) 555-1234</a>
	 * (Advanced)
	 * [lct_tel_link phone='(970) 555-1234' action='My Custom Action' category='Something_NOT_tel_link' class='button' pre='before number:' post='after the number.']
	 * converts to
	 * <a class="button" href="tel:9705551234" onclick="_gaq.push(['_trackEvent', 'Something_NOT_tel_link', 'My Custom Action'])">before number: (970) 555-1234 after the number.</a>
	 * (Link Text Override)
	 * [lct_tel_link phone='(970) 555-1234' text='Link Text Here']
	 * converts to
	 * <a href="tel:9705551234" onclick="_gaq.push(['_trackEvent', 'tel_link', '(970) 555-1234'])">Link Text Here</a>
	 *
	 * @return bool|string
	 * @since    0.0
	 * @verified 2016.12.14
	 */
	function add_shortcode( $a )
	{
		isset( $a['phone'] ) ? $phone = $a['phone'] : $phone = '';

		if ( isset( $a['dont_use_global_format'] ) ) {
			if ( $a['dont_use_global_format'] == 'false' ) {
				$dont_use_global_format = false;
			} else {
				$dont_use_global_format = true;
			}
		} else {
			$dont_use_global_format = false;
		}

		$dont_use_global_format = apply_filters( 'lct/tel_link/dont_use_global_format', $dont_use_global_format, $phone );

		isset( $a['category'] ) ? $category = $a['category'] : $category = $this->il;
		isset( $a['action'] ) ? $action = $a['action'] : $action = $phone;
		isset( $a['class'] ) ? $class = $a['class'] : $class = '';
		isset( $a['pre'] ) ? $pre = $a['pre'] : $pre = '';
		isset( $a['post'] ) ? $post = $a['post'] : $post = '';
		isset( $a['text'] ) ? $text = $a['text'] : $text = '';
		isset( $a['label'] ) ? $label = $a['label'] : $label = '';


		if ( empty( $phone ) ) {
			return false;
		}


		if ( ! $dont_use_global_format ) {
			$phone = lct_format_phone_number( $phone );
		}


		if ( ! empty( $class ) ) {
			$class = " class=\"{$class}\"";
		}


		if ( ! empty( $pre ) ) {
			$pre = $pre . ' ';
		}


		if ( ! empty( $post ) ) {
			$post = ' ' . $post;
		}


		if ( empty( $text ) ) {
			$text = $pre . $phone . $post;
		}


		return sprintf( '<a href="tel:%s" %s%s>%s</a>', lct_strip_phone( $phone ), lct_get_gaTracker_onclick( $category, $action, $label ), $class, $text );
	}


	/**
	 * Action target that displays the popup to insert a Phone # to a post/page
	 *
	 * @return array
	 */
	function get_shortcodes()
	{
		$atts = [
			[
				'label'       => 'Phone Number',
				'attr'        => 'phone',
				'type'        => 'text',
				'section'     => 'required',
				'description' => 'Be sure to INCLUDE the desired formatting.',
				'tooltip'     => 'Specify the phone number you are creating the link for. Ex: (970) 555-1234 or 970.555.1234'
			],
			[
				'label'   => 'Don\'t use site\'s global phone formatting?',
				'attr'    => 'dont_use_global_format',
				'type'    => 'checkbox',
				'section' => 'required',
				'tooltip' => 'Currently: ' . lct_format_phone_number( '9995551234' )
			],
			[
				'label'       => 'Link Class',
				'attr'        => 'class',
				'type'        => 'text',
				'section'     => 'standard',
				'description' => '(optional)',
				'tooltip'     => 'Use this to add custom css class to your link'
			],
			[
				'label'       => 'Text before the phone number',
				'attr'        => 'pre',
				'type'        => 'text',
				'section'     => 'standard',
				'description' => '(optional)',
				'tooltip'     => 'Use this to add some link text before the phone number.'
			],
			[
				'label'       => 'Text after the phone number',
				'attr'        => 'post',
				'type'        => 'text',
				'section'     => 'standard',
				'description' => '(optional)',
				'tooltip'     => 'Use this to add some link text after the phone number.'
			],
			[
				'label'       => 'Link Text Override',
				'attr'        => 'text',
				'type'        => 'text',
				'description' => 'Use this to override the default Link text that this shortcode creates.',
				'tooltip'     => 'Use this to override the default Link text that this shortcode creates.'
			],
			[
				'label'       => 'GATC Action',
				'attr'        => 'action',
				'type'        => 'text',
				'description' => 'ONLY change this if you do NOT want the action in Google Analytics to be \'{pre} {phone} {post}\'. See tooltip for more info.',
				'tooltip'     => 'See this for more info: <a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiEventTracking">Google Analytics Tracking Code: Event Tracking</a>'
			],
			[
				'label'       => 'GATC Category',
				'attr'        => 'category',
				'type'        => 'text',
				'description' => 'ONLY change this if you do NOT want the category in Google Analytics to be \'' . $this->il . '\'. See tooltip for more info.',
				'tooltip'     => 'See this for more info: <a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiEventTracking">Google Analytics Tracking Code: Event Tracking</a>'
			],
		];


		$shortcode = [
			'shortcode_tag' => $this->il_full,
			'action_tag'    => '',
			'label'         => 'Phone Number',
			'attrs'         => $atts,
		];


		return [ $shortcode ];
	}


	/**
	 * display button matching new UI
	 */
	function add_button()
	{
		echo '<a href="#" class="button ' . $this->il_full . '_media_link" id="add_' . $this->il_full . '" title="Add Phone #">
			<div>Add Phone #</div>
		</a>';
	}


	function add_mce_popup()
	{
		$popup = '<div id="select_' . $this->il_full . '" style="display:none;">
			<div id="' . $this->il_full . '-shortcode-ui-wrap" class="wrap">
				<div id="' . $this->il_full . '-shortcode-ui-container"></div>
			</div>
		</div>';


		echo $popup;
	}


	function print_media_templates()
	{
		$template = lct_get_path( 'features/tpl/' . $this->il . '.tpl.php' );


		if ( file_exists( $template ) ) {
			ob_start();


			lct_include( $template );


			echo ob_get_clean();
		}
	}
}
