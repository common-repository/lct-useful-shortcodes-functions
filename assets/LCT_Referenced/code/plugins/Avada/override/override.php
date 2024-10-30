<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) )
	exit;


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.04.20
 */
class lct_Avada_override {
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.04.20
	 */
	function __construct( $args = [] ) {
		//Store $args
		$this->args = $args;


		//Store parent class, maybe
		if ( $this->args['load_parent'] )
			$this->zxzp = lct();


		//Setup WordPress action and filter hooks
		$this->load_hooks();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    2017.33
	 * @verified 2017.09.09
	 */
	function load_hooks() {
		//bail early if already ran
		if ( lct_did() )
			return;


		/**
		 * everytime
		 */
		/**
		 * Only if Avada older than v5.0
		 */
		if ( version_compare( lct_theme_version( 'Avada' ), '5.0', '<' ) ) : //Avada older than v5.0


			//We may want to disable this sometimes
			if ( ! defined( 'LCT_DO_NOT_OVERRIDE_PAGEBUILDER' ) ) {
				add_action( 'plugins_loaded', [ $this, 'Fusion_Core_PageBuilder_override_legacy_lt_5' ], 7 );

				if ( version_compare( lct_plugin_version( 'fusion_core' ), '2.0.2', '=' ) ) //fusion equal to v2.0.2
					add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts_legacy_2_0_2' ], 999999 );
			}


		endif;


		/**
		 * Only if Avada older than v4.0
		 */
		if ( version_compare( lct_theme_version( 'Avada' ), '4.0', '<' ) ) : //Avada older than v4.0


			add_action( 'plugins_loaded', [ $this, 'early_run_of_options' ], 1000001 );


		endif;


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Load our own version of the Fusion_Core_PageBuilder class
	 *
	 * @since    5.35
	 * @verified 2016.11.09
	 */
	function Fusion_Core_PageBuilder_override_legacy_lt_5() {
		$post_type = '';


		if ( ! empty( $_GET['post_type'] ) ) {
			$post_type = $_GET['post_type'];
		} else {
			if (
				isset( $_GET['post'] ) &&
				! empty( $_GET['post'] )
			) {
				$post_type = get_post_type( $_GET['post'] );
			}
		}


		if (
			empty( $post_type ) ||
			! in_array( $post_type, lct_get_setting( 'post_types', [] ) )
		) {
			return;
		}


		if ( lct_theme_version( 'Avada' ) == '3.8.8' ) {
			remove_action( 'plugins_loaded', [ 'Fusion_Core_PageBuilder', 'get_instance' ] );
			remove_action( 'after_setup_theme', [ 'Fusion_Core_PageBuilder', 'get_instance' ] );
		}


		include_once( 'fusion-core/admin/class-pagebuilder-legacy_lt-5.0.php' );
		add_action( 'plugins_loaded', [ zxzu( 'Fusion_Core_PageBuilder_legacy_lt_5_0' ), 'get_instance' ], 100 );
	}


	/**
	 * Override the wp-editor javascript
	 *
	 * @since    7.28
	 * @verified 2016.11.09
	 */
	function admin_enqueue_scripts_legacy_2_0_2() {
		$wp_scripts                 = wp_scripts();
		$file_fusionb_wpeditor_init = 'plugins/Avada/override/fusion-core/admin/page-builder/assets/js/js-wp-editor-legacy_' . lct_plugin_version( 'fusion_core' ) . '.js';


		if (
			! empty( $wp_scripts->registered['fusionb_wpeditor_init'] ) &&
			file_exists( lct_get_path( $file_fusionb_wpeditor_init ) )
		) {
			$fusionb_vars = [
				'url'          => get_home_url(),
				'includes_url' => includes_url()
			];


			wp_deregister_script( 'fusionb_wpeditor_init' );
			wp_register_script( 'fusionb_wpeditor_init', lct_get_root_url( $file_fusionb_wpeditor_init ), [ 'jquery' ], lct_get_setting( 'version' ), true );
			wp_localize_script( 'fusionb_wpeditor_init', 'fusionb_vars', $fusionb_vars );
			wp_enqueue_script( 'fusionb_wpeditor_init' );
		}
	}


	/**
	 * There is a bug in Avada and the 10 priority is just not soon enough.
	 *
	 * @since    0.0
	 * @verified 2017.04.20
	 */
	function early_run_of_options() {
		remove_action( 'init', 'of_options' );

		add_action( 'init', 'of_options', 1 );
	}
}
