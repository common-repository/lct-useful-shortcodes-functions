<?php //edit_zz cs - SEE .git ORIGINALzz - plugins/fusion-core/admin/class-pagebuilder.php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Page Builder Class for Fusion Core
 *
 * @package   FusionCore
 * @author    ThemeFusion
 * @link      http://theme-fusion.com
 * @copyright ThemeFusion
 */

if ( ! class_exists( 'lct_Fusion_Core_PageBuilder_legacy_lt_5_0' ) ) {
	class lct_Fusion_Core_PageBuilder_legacy_lt_5_0
	{
		/**
		 * Instance of this class.
		 *
		 * @since    1.0.0
		 * @var      object
		 */
		protected static $instance = null;

		/**
		 * Instances of dependent classes.
		 *
		 * @since  1.0.0
		 * @var    array array of classes object
		 */
		protected static $instances = [];

		/**
		 * Slug of the plugin screen.
		 *
		 * @since    1.0.0
		 * @var      string
		 */
		protected $plugin_screen_hook_suffix = null;


		/**
		 * Plugin slug.
		 *
		 * @since    2.0.0
		 * @var      string
		 */
		protected $plugin_slug = 'fusion-core_page-builder';

		var $allowed_post_types = [ 'page', 'post', 'avada_faq', 'avada_portfolio', 'lct_theme_chunk' ];

		/**
		 * Initialize the plugin by loading admin scripts & styles and adding a
		 * settings page and menu.
		 *
		 * @since     1.0.0
		 */


		private function __construct()
		{
			// Load admin style sheet and JavaScript.
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_styles' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
			add_action( 'admin_print_footer_scripts', [ $this, 'enqueue_wp_editor_scripts' ] );

			//load editor
			add_action( 'edit_form_after_editor', [ $this, 'get_builder_canvas' ], 1000 );
			//register AJAX actions
			add_action( 'wp_ajax_fusion_pallete_elements', [ $this, 'get_pallete_elements' ] );
			add_action( 'wp_ajax_fusion_update_builder_data', [ $this, 'update_builder_data' ] );
			add_action( 'wp_ajax_fusion_custom_tabs', [ $this, 'custom_tabs_handler' ] );
			add_action( 'wp_ajax_fusion_get_shortcodes', [ $this, 'get_shortocodes_from_json' ] );
			add_action( 'wp_ajax_fusion_content_to_elements', [ $this, 'get_elements_from_content' ] );
			add_action( 'wp_ajax_fusion_get_attachment_url_from_id', [ $this, 'get_attachment_id_from_url' ] );

			//register actions to save builder content revisions
			add_action( 'save_post', [ $this, 'save_fusion_revisions_with_post' ] );
			add_action( 'wp_restore_post_revision', [ $this, 'fusion_restore_revision' ], 10, 2 );
			add_filter( '_wp_post_revision_fields', [ $this, 'fusion_revision_fields' ] );
			add_filter( '_wp_post_revision_field_fb_content', [ $this, 'fusion_revision_field' ], 10, 2 );
			add_filter( 'tiny_mce_before_init', [ $this, 'fusion_tinymce_rtl_fix' ], 10 );

			// Admin Notices
			add_action( 'admin_notices', [ $this, 'admin_notices' ] );

			// Load page builder classes
			require_once( lct_path_plugin() . '/fusion-core/admin/page-builder/classes/class-ui.php' );

			$settings['allowed_post_types'] = $this->allowed_post_types;
			// Create a new instance of page builder classes
			$instances['ui'] = Fusion_Core_PageBuilder_UI::get_instance( $settings );

			//load API and required files
			require_once( lct_path_plugin() . '/fusion-core/admin/page-builder/api/Palette.php' );

			//load required classes
			require_once( lct_path_plugin() . '/fusion-core/admin/page-builder/classes/class-custom-templates.php' );
			require_once( lct_path_plugin() . '/fusion-core/admin/page-builder/classes/class-prebuilt-templates.php' );
			require_once( lct_path_plugin() . '/fusion-core/admin/page-builder/classes/class-shortcodes-parser.php' );
			require_once( lct_path_plugin() . '/fusion-core/admin/page-builder/classes/class-fusion-reversal.php' );

		}

		/**
		 * Return an instance of this class.
		 *
		 * @return    object    A single instance of this class.
		 * @since     1.0.0
		 */
		public static function get_instance()
		{
			global $wp_rich_edit, $is_gecko, $is_opera, $is_safari, $is_chrome, $is_IE, $is_edge;

			if ( ! isset( $wp_rich_edit ) ) {
				$wp_rich_edit = false;

				if (
					'true' == @get_user_option( 'rich_editing' )
					|| ! @is_user_logged_in()
				) { // default to 'true' for logged out users
					if ( $is_safari ) {
						$wp_rich_edit = ! wp_is_mobile() || ( preg_match( '!AppleWebKit/(\d+)!', $_SERVER['HTTP_USER_AGENT'], $match ) && intval( $match[1] ) >= 534 );
					} elseif (
						$is_gecko
						|| $is_chrome
						|| $is_IE
						|| $is_edge
						|| (
							$is_opera
							&& ! wp_is_mobile()
						)
					) {
						$wp_rich_edit = true;
					}
				}
			}

			if ( $wp_rich_edit ) {
				// If the single instance hasn't been set, set it now.
				if ( null == self::$instance ) {
					self::$instance = new self;
				}
			} else {
				add_action( 'edit_form_after_title', 'add_notice_of_disabled_rich_editor' );
			}

			return self::$instance;

		}

		/**
		 * Register and enqueue admin-specific style sheet.
		 *
		 * @return    null    Return early if no settings page is registered.
		 * @since     1.0.0
		 */
		public function enqueue_admin_styles()
		{
			$screen          = get_current_screen();
			$allowed_screens = $this->allowed_post_types;
			if ( in_array( $screen->id, $allowed_screens ) ) {
				wp_enqueue_style( 'wp-color-picker' ); // for color picker
				wp_enqueue_style( 'fusionb_icomoon', plugins_url( 'page-builder/assets/fonts/icomoon.css', lct_path_plugin() . '/fusion-core/admin/class-pagebuilder.php' ), [], FusionCore_Plugin::VERSION );
				wp_enqueue_style( 'fusionb_jq-ui-style', plugins_url( 'page-builder/assets/css/jquery/jquery-ui-skeleton.css', lct_path_plugin() . '/fusion-core/admin/class-pagebuilder.php' ), [], FusionCore_Plugin::VERSION );
				wp_enqueue_style( 'fusionb_builder-style', plugins_url( 'page-builder/assets/css/application.css', lct_path_plugin() . '/fusion-core/admin/class-pagebuilder.php' ), [], FusionCore_Plugin::VERSION );
			}

		}

		/**
		 * Register and enqueue admin-specific JavaScript.
		 *
		 * @return    null    Return early if no settings page is registered.
		 * @since     1.0.0
		 */
		public function enqueue_admin_scripts()
		{
			global $wp_version;
			$screen          = get_current_screen();
			$allowed_screens = $this->allowed_post_types;

			if ( in_array( $screen->id, $allowed_screens ) ) {
				$fusionb_vars = [
					'url'          => get_home_url(),
					'includes_url' => includes_url()
				];

				wp_register_script( 'fusionb_wpeditor_init', plugins_url( 'page-builder/assets/js/js-wp-editor.js', lct_path_plugin() . '/fusion-core/admin/class-pagebuilder.php' ), [ 'jquery' ], FusionCore_Plugin::VERSION, true );
				wp_localize_script( 'fusionb_wpeditor_init', 'fusionb_vars', $fusionb_vars );
				wp_enqueue_script( 'fusionb_wpeditor_init' );
				wp_enqueue_script( 'fusionb_admin-script', plugins_url( 'page-builder/assets/js/admin.js', lct_path_plugin() . '/fusion-core/admin/class-pagebuilder.php' ), [ 'jquery' ], FusionCore_Plugin::VERSION );
				wp_enqueue_script( 'fusionb_custom-templates-script', plugins_url( 'page-builder/assets/js/custom-templates.js', lct_path_plugin() . '/fusion-core/admin/class-pagebuilder.php' ), [ 'jquery' ], FusionCore_Plugin::VERSION );
				wp_enqueue_script( 'fusionb_prebuilt-templates-script', plugins_url( 'page-builder/assets/js/pre-built-templates.js', lct_path_plugin() . '/fusion-core/admin/class-pagebuilder.php' ), [ 'jquery' ], FusionCore_Plugin::VERSION );
				wp_enqueue_script( 'wp-color-picker' ); //for wp color picker
				//Page builder core scripts
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_script( 'jquery-ui-draggable' );
				wp_enqueue_script( 'jquery-ui-droppable' );
				wp_enqueue_script( 'jquery-ui-dialog' );
				wp_enqueue_script( 'jquery-ui-button' );
				wp_enqueue_script( 'jquery-ui-tabs' );

				$handle = 'fluidVids.js';
				$list   = 'enqueued';
				wp_enqueue_script( 'backbone' );
				wp_enqueue_script( 'underscore' );
				wp_enqueue_script( 'fusionb_bk-handlers', plugins_url( 'page-builder/assets/js/handlebars.js', lct_path_plugin() . '/fusion-core/admin/class-pagebuilder.php' ), [ 'jquery' ], FusionCore_Plugin::VERSION );
				wp_enqueue_script( 'fusionb_fusion-history', plugins_url( 'page-builder/assets/js/fusion-history.js', lct_path_plugin() . '/fusion-core/admin/class-pagebuilder.php' ), [], FusionCore_Plugin::VERSION, true );
				wp_enqueue_script( 'fusionb_fusion-parser', plugins_url( 'page-builder/assets/js/fusion-parser.js', lct_path_plugin() . '/fusion-core/admin/class-pagebuilder.php' ), [], FusionCore_Plugin::VERSION, true );
				//localize script
				$data_to_pass = [
					'disable_encoding' => get_option( 'avada_disable_encoding' )
				];

				wp_localize_script( 'fusionb_fusion-parser', 'fusion_vars', $data_to_pass );

				wp_enqueue_script( 'fusionb_dd-parser', plugins_url( 'page-builder/assets/js/dd-element-parser.js', lct_path_plugin() . '/fusion-core/admin/class-pagebuilder.php' ), [], FusionCore_Plugin::VERSION, true );
				wp_enqueue_script( 'fusionb_builder-helper', plugins_url( 'page-builder/assets/js/DdHelper.js', lct_path_plugin() . '/fusion-core/admin/class-pagebuilder.php' ), [ 'jquery' ], FusionCore_Plugin::VERSION, true );
				wp_enqueue_script( 'fusionb_builder-cat', plugins_url( 'page-builder/assets/js/category.js', lct_path_plugin() . '/fusion-core/admin/class-pagebuilder.php' ), [], FusionCore_Plugin::VERSION, true );
				wp_enqueue_script( 'fusionb_builder-palette', plugins_url( 'page-builder/assets/js/palette.js', lct_path_plugin() . '/fusion-core/admin/class-pagebuilder.php' ), [], FusionCore_Plugin::VERSION, true );
				wp_enqueue_script( 'fusionb_builder-editor', plugins_url( 'page-builder/assets/js/editor.js', lct_path_plugin() . '/fusion-core/admin/class-pagebuilder.php' ), [], FusionCore_Plugin::VERSION, true );
				wp_enqueue_script( 'fusionb_builder-app', plugins_url( 'page-builder/assets/js/application.js', lct_path_plugin() . '/fusion-core/admin/class-pagebuilder.php' ), [], FusionCore_Plugin::VERSION, true );
				wp_enqueue_script( 'fusionb_builder-previews', plugins_url( 'page-builder/assets/js/fusion-previews.js', lct_path_plugin() . '/fusion-core/admin/class-pagebuilder.php' ), [], FusionCore_Plugin::VERSION, true );

			}

		}

		public function enqueue_wp_editor_scripts()
		{
			$screen          = get_current_screen();
			$allowed_screens = $this->allowed_post_types;
			if ( in_array( $screen->id, $allowed_screens ) ) {
				if ( ! class_exists( '_WP_Editors' ) ) {
					require( ABSPATH . WPINC . '/class-wp-editor.php' );
				}

				$set = _WP_Editors::parse_settings( 'fusionb_id', [] );

				if ( ! current_user_can( 'upload_files' ) ) {
					$set['media_buttons'] = false;
				}

				if ( $set['media_buttons'] ) {
					wp_enqueue_script( 'thickbox' );
					wp_enqueue_style( 'thickbox' );
					wp_enqueue_script( 'media-upload' );

					$post = get_post();
					if ( ! $post && ! empty( $GLOBALS['post_ID'] ) ) {
						$post = $GLOBALS['post_ID'];
					}

					wp_enqueue_media( [
						'post' => $post
					] );
				}

				_WP_Editors::editor_settings( 'fusionb_id', $set );
			}
		}

		/**
		 * function to return Json response for all Palette/Editor elements
		 *
		 * @param Post data [action],[category]
		 *
		 * @return    JSON data
		 * @since     2.0.0
		 */
		public function get_pallete_elements()
		{
			if ( isset( $_POST['category'] ) && $_POST['category'] == 'Palette' ) { //if pallete required
				try {
					header( "Content-Type: application/json" );

					$palette  = new Palette();
					$elements = $palette->to_JSON();
					echo $elements;
				} catch( Exception $e ) {
					echo '{"error":{"text":' . $e->getMessage() . '}}';
				}
			} else {    //if editor elements required
				try {
					header( "Content-Type: application/json" );
					$instance = $_POST['instance'];
					$editor   = new Editor ( $instance );
					$elements = $editor->to_JSON();
					echo $elements;
				} catch( Exception $e ) {
					echo '{"error":{"text":' . $e->getMessage() . '}}';
				}
			}
			exit();
		}

		/**
		 * Function to update builder content
		 *
		 * @return    JSON data
		 * @Param      Post Data ['model']
		 * @Param      Post Data ['instance']
		 * @since      2.0.0
		 */
		function update_builder_data()
		{
			$instance = $_POST['instance'];
			$model    = $_POST['model'];
			$model    = str_replace( "\'", "'", $model );
			$model    = str_replace( '\"', '"', $model );
			$model    = preg_replace( "~\\\\+([\"\'\\x00\\\\])~", '\\"', $model );
			$model    = json_decode( $model );
			$state    = $_POST['state'];
			//save state if builder active or WP default editor
			update_post_meta( $instance, 'fusion_builder_status', [ $state ] );

			$resonse = update_post_meta( $instance, 'fusion_builder_content', $model );

			if ( sizeof( $model ) < 1 ) {
				$resonse = delete_post_meta( $instance, 'fusion_builder_content', $model );
			}

			header( "Content-Type: application/json" );
			if ( $resonse != false ) {
				echo '{"success":{"text":', json_encode( __( 'Builder content have been updated successfully.', 'fusion-core' ) ), '}}';
			} else {
				echo '{"error":{"text":', json_encode( __( 'There was some error, could not update fusion builder data. Please try again.', 'fusion-core' ) ), '}}';
			}
			exit();
		}

		/**
		 * Function to get attachment ID from URL
		 *
		 * @return        ID
		 * @Param        URL
		 * @since        2.0.0
		 */
		public function get_attachment_id_from_url()
		{
			global $wpdb;

			$attachment_id  = false;
			$attachment_url = $_POST['url'];

			// If there is no url, return.
			if ( '' == $attachment_url ) {
				return;
			}

			// Get the upload directory paths
			$upload_dir_paths = wp_upload_dir();

			// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
			if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
				// If this is the URL of an auto-generated thumbnail, get the URL of the original image
				$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
				// Remove the upload path base directory from the attachment URL
				$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
				// Finally, run a custom database query to get the attachment ID from the modified attachment URL
				$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE 
									wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' 
									AND wposts.post_type = 'attachment'", $attachment_url ) );
			}

			echo $attachment_id;
			die();
		}

		/**
		 * Function to hanlde custom and pre-built templates
		 *
		 * @return    Content
		 * @Param      Action, InstanceID   :: Post Params
		 * @since      2.0.0
		 */
		public function custom_tabs_handler()
		{
			$action = $_POST['post_action'];

			switch ( $action ) {
				case 'get_custom_templates':

					$custom_templates = new Fusion_Core_Custom_Templates();
					echo $custom_templates->get_custom_templates();
					exit();

					break;

				case 'get_prebuilt_templates':

					$prebuilt_templates = new Fusion_Core_Prebuilt_Templates();
					echo $prebuilt_templates->get_prebuilt_templates();
					exit();

					break;

				case 'save_custom_template' :
					$content          = [];
					$custom_templates = new Fusion_Core_Custom_Templates();
					$response         = $custom_templates->save_single_template();
					header( "Content-Type: application/json" );
					if ( $response ) {
						$content['message']          = '{"success":{"text":' . json_encode( __( 'Temaplte have been saved successfully.', 'fusion-core' ) ) . '}}';
						$custom_templates            = new Fusion_Core_Custom_Templates();
						$content['custom_templates'] = $custom_templates->get_custom_templates();
						echo json_encode( $content );
					} else {
						echo '{"error":{"text":', json_encode( __( 'There was some error, could not add custom template. Kindly try again.', 'fusion-core' ) ), '}}';
					}
					exit();
					break;

				case 'delete_custom_template':
					$content          = [];
					$custom_templates = new Fusion_Core_Custom_Templates();
					$response         = $custom_templates->delete_single_template();
					header( "Content-Type: application/json" );
					if ( $response ) {
						$content['message']          = '{"success":{"text":' . json_encode( __( 'Template deleted successfully.', 'fusion-core' ) ) . '}}';
						$custom_templates            = new Fusion_Core_Custom_Templates();
						$content['custom_templates'] = $custom_templates->get_custom_templates();
						echo json_encode( $content );
					} else {
						echo '{"error":{"text":', json_encode( __( 'There was some error, could not delete custom template. Kindly try again.', 'fusion-core' ) ), '}}';
					}
					exit();
					break;

				case 'load_custom_template':
					$custom_templates = new Fusion_Core_Custom_Templates();
					$template         = $custom_templates->get_single_template();

					if ( $template != false ) {
						echo $template;
					} else {
						echo json_encode( [] );
					}
					exit();

					break;

				case 'load_prebuilt_template':

					$prebuilt_templates = new Fusion_Core_Prebuilt_Templates();
					$template           = $prebuilt_templates->get_single_template();

					if ( $template != false ) {
						echo $template;
					} else {
						echo json_encode( [] );
					}
					exit();

					break;

				case 'get_custom_and_prebuilt_templates':
					$content                       = [];
					$custom_templates              = new Fusion_Core_Custom_Templates();
					$content['custom_templates']   = $custom_templates->get_custom_templates();
					$prebuilt_templates            = new Fusion_Core_Prebuilt_Templates();
					$content['prebuilt_templates'] = $prebuilt_templates->get_prebuilt_templates();
					header( "Content-Type: application/json" );
					echo json_encode( $content );
					exit();
					break;

			}


		}

		public function get_elements_from_content()
		{
			echo Fusion_Core_Reversal::content_to_elements( $_POST['content'] );
			exit();
		}

		/**
		 * Function to get shortcodes from JSON content
		 *
		 * @return    NULL
		 * @Param      POST['data']
		 * @since      2.0.0
		 */
		public function get_shortocodes_from_json()
		{
			$builder_data = $_POST['builder_data'];
			$builder_data = str_replace( "\'", "'", $builder_data );
			$builder_data = str_replace( '\"', '"', $builder_data );
			$builder_data = preg_replace( "~\\\\+([\"\'\\x00\\\\])~", '\\"', $builder_data );
			$builder_data = json_decode( $builder_data );

			Fusion_Core_Shortcodes_Parser::set_content( $builder_data );

			$response = Fusion_Core_Shortcodes_Parser::parse_column_options();

			echo $response;
			exit();

		}

		/**
		 * Function to save fusion builder content revisions
		 *
		 * @return    NULL
		 * @Param      Post ID
		 * @since      2.0.0
		 */
		public function save_fusion_revisions_with_post( $post_id )
		{
			if ( isset( $_POST['fusion_builder_status'] ) && $_POST['fusion_builder_status'] ) {
				update_post_meta( $post_id, 'fusion_builder_status', $_POST['fusion_builder_status'] );
			}

			$parent_id = wp_is_post_revision( $post_id );

			if ( $parent_id ) {
				$parent     = get_post( $parent_id );
				$FB_content = get_post_meta( $parent->ID, 'fusion_builder_content', true );

				if ( false !== $FB_content ) {
					add_metadata( 'post', $post_id, 'FB_content', $FB_content );
				}

			}

		}

		/**
		 * Function to restore fusion builder content along with revision
		 *
		 * @return    NULL
		 * @Param      Post ID, Revision ID
		 * @since      2.0.0
		 */
		public function fusion_restore_revision( $post_id, $revision_id )
		{
			$post       = get_post( $post_id );
			$revision   = get_post( $revision_id );
			$FB_content = get_metadata( 'post', $revision->ID, 'FB_content', true );

			if ( false !== $FB_content ) {
				update_post_meta( $post_id, 'fusion_builder_content', $FB_content );
			} else {
				delete_post_meta( $post_id, 'fusion_builder_content' );
			}

		}

		/**
		 * Function to show revision on revisions screen
		 *
		 * @return    Array data
		 * @Param      Revision fields array
		 * @since      2.0.0
		 */
		public function fusion_revision_fields( $revision_fields )
		{
			$revision_fields['fb_content'] = __( 'Fusion builder elements', 'fusion-core' );

			return $revision_fields;
		}

		/**
		 * comparator function helper for revisions
		 *
		 * @return    String data
		 * @Param      value, field name
		 * @since      2.0.0
		 */
		public function fusion_revision_field( $value, $field )
		{
			return sprintf( __( '# of elements: %s', 'fusion-core' ), count( ( $value ) ) );
		}

		/**
		 * get editor convas ready.
		 *
		 * @return    null    includes script.
		 * @since     2.0.0
		 */
		public function get_builder_canvas()
		{
			$screen          = get_current_screen();
			$allowed_screens = $this->allowed_post_types;
			if ( in_array( $screen->id, $allowed_screens ) ) {
				require( lct_path_plugin() . '/fusion-core/admin/page-builder/views/builder.php' );
			}
		}

		/**
		 * TinyMCE fix for RTL languages
		 *
		 * @param array $settings
		 *
		 * @return    array Filtered settings array
		 * @since     1.7.0
		 */
		public function fusion_tinymce_rtl_fix( $settings )
		{
			if ( is_rtl()
			     && isset( $settings['plugins'] )
			     && ',directionality' == $settings['plugins']
			) {
				unset( $settings['plugins'] );
			}

			return $settings;
		}

		/**
		 * let_to_num function.
		 * This function transforms the php.ini notation for numbers (like '2M') to an integer.
		 *
		 * @param $size
		 *
		 * @return int
		 */
		function let_to_num( $size )
		{
			$l   = substr( $size, - 1 );
			$ret = substr( $size, 0, - 1 );
			switch ( strtoupper( $l ) ) {
				case 'P':
					$ret *= 1024;
				case 'T':
					$ret *= 1024;
				case 'G':
					$ret *= 1024;
				case 'M':
					$ret *= 1024;
				case 'K':
					$ret *= 1024;
			}

			return $ret;
		}

		/**
		 * Admin notices for required system settings
		 *
		 * @since     1.7.0
		 */
		public function admin_notices()
		{
			global $smof_data, $current_user;
			$user_id = $current_user->ID;

			$screen = get_current_screen();

			$allowed_screens = $this->allowed_post_types;
			if ( ! in_array( $screen->id, $allowed_screens ) ) {
				return;
			}

			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			$current_uri = $_SERVER['REQUEST_URI'];
			$uri_parts   = parse_url( $current_uri );
			if ( ! isset( $uri_parts['query'] ) ) {
				$uri_parts['query'] = '';
			}
			$path      = explode( '/', $uri_parts['path'] );
			$last      = end( $path );
			$full_link = admin_url() . $last . '?' . $uri_parts['query'];

			if ( isset( $_GET['fb_system_req_nag'] ) && '0' == $_GET['fb_system_req_nag'] ) {
				add_user_meta( $user_id, 'fb_system_req_nag', 'true', true );
			}
			if ( $smof_data['disable_builder'] && ! get_user_meta( $user_id, 'fb_system_req_nag' ) ):
				?>
				<div class="error fusion-builder-settings-error" style="display: none; position: relative;">
					<p><strong><?php echo __( "We're sorry but Fusion Builder has timed out. It is most likely due to low PHP configurations on your server or a php error.  There are 3 possible solutions.", 'Avada' ); ?></strong></p>
					<p><strong><?php _e( 'Solution 1:', 'Avada' ); ?></strong> <?php echo __( 'Fix the PHP configurations in the System Status that are reported in <strong style="color: red;">RED</strong>.', 'Avada' ); ?><a href="<?php echo admin_url( 'admin.php?page=avada-system-status' ); ?>" class="button-primary" target="_blank" style="margin-left: 10px;"><?php _e( 'System Status', 'Avada' ); ?></a></p>
					<p><strong><?php _e( 'Solution 2:', 'Avada' ); ?></strong> <?php echo __( 'Make sure WP-DEBUG is turned off in your wp-config file.', 'Avada' ); ?></p>
					<p><strong><?php _e( 'Solution 3:', 'Avada' ); ?></strong> <?php echo __( 'Disable all plugins except for the ones that came with the theme to see is there is a conflict.', 'Avada' ); ?></p>
					<a style="position:absolute;bottom: 10px; right: 10px;" href="<?php echo $full_link; ?>&amp;fb_system_req_nag=0" class="button-secondary"><?php _e( 'Dismiss Notice', 'Avada' ); ?></a>
				</div>
			<?php
			endif;
		}
	}
}
