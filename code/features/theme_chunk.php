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
class lct_features_theme_chunk
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
	 * @verified 2018.02.13
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
		$this->set_cnst();

		add_action( 'init', [ $this, 'register_post_type' ], 5 );

		add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );


		if ( lct_frontend() ) {
			add_shortcode( 'theme_chunk', [ $this, 'theme_chunk' ] );
		}


		//if ( lct_wp_admin_all() ) {}


		if ( lct_wp_admin_non_ajax() ) {
			/**
			 * actions
			 */
			add_action( 'pre_get_posts', [ $this, 'allow_page_ordering' ] );

			add_action( 'wp', [ $this, 'remove_allow_page_ordering' ] );


			/**
			 * filters
			 */
			add_filter( 'fusion_builder_allowed_post_types', [ $this, 'fusion_builder_allow' ] );
		}


		if ( lct_ajax_only() ) {
			$this->register_ajax();

			add_action( 'plugins_loaded', [ $this, 'fast_ajax' ], 1 );
		}
	}


	/**
	 * Register the theme_chunk cnsts
	 *
	 * @since    2018.11
	 * @verified 2018.02.13
	 */
	function set_cnst()
	{
		/**
		 * theme_chunk
		 */
		lct_append_setting( 'post_types', 'lct_theme_chunk' );
		lct_append_setting( 'post_types_monitored', 'lct_theme_chunk' );
	}


	/**
	 * Register the theme_chunk post_type
	 *
	 * @since    2018.11
	 * @verified 2018.02.13
	 */
	function register_post_type()
	{
		$slug      = 'theme_chunk';
		$post_type = 'lct_theme_chunk';


		if ( post_type_exists( $post_type ) ) {
			return;
		}


		$labels = [];
		$labels = lct_post_type_default_labels( $labels, $slug );


		$args = [
			'public'              => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'has_archive'         => false,
			'supports'            => [ 'editor', 'title', 'author', 'thumbnail', 'page-attributes', 'revisions' ],
			'capabilities'        => [
				'edit_post'    => 'administrator',
				'delete_post'  => 'administrator',
				'edit_posts'   => 'administrator',
				'delete_posts' => 'administrator',
			]
		];
		$args = lct_post_type_default_args( $args, $slug, $labels );


		register_post_type( $post_type, $args );
	}


	/**
	 * Register the theme_chunk post_type
	 *
	 * @since    2018.11
	 * @verified 2018.02.13
	 */
	function register_ajax()
	{
		add_action( 'wp_ajax_lct_theme_chunk', [ $this, 'ajax_handler' ] );
		add_action( 'wp_ajax_nopriv_lct_theme_chunk', [ $this, 'ajax_handler' ] );
	}


	/**
	 * Register the theme_chunk post_type
	 *
	 * @since    2018.11
	 * @verified 2020.09.10
	 */
	function fast_ajax()
	{
		if ( lct()->lct_mu->fast_ajax ) {
			add_shortcode( zxzu( 'acf_form2' ), '__return_true' );
		}
	}


	/**
	 * Register Scripts
	 *
	 * @since    7.50
	 * @verified 2022.08.11
	 */
	function wp_enqueue_scripts()
	{
		lct_enqueue_script( 'lct_theme_chunk', lct_get_root_url( 'assets/js/theme_chunk.min.js' ), true, [ 'jquery' ], lct_get_setting( 'version' ), true );


		$a = [
			'ajax_url'    => admin_url( 'admin-ajax.php' ),
			'wpapi_nonce' => wp_create_nonce( 'wp_rest' )
		];


		if ( $post = get_post() ) {
			$a[ lct_get_setting( 'root_post_id' ) ] = $post->ID;
		}


		wp_localize_script(
			'lct_theme_chunk',
			'lct_theme_chunk',
			$a
		);
	}


	/**
	 * get the theme_chunk content
	 *
	 * @since    7.50
	 * @verified 2023.11.01
	 */
	function ajax_handler()
	{
		$r            = [];
		$r['status']  = 'Nothing Happened';
		$r['content'] = '';


		//Check for fishy actions going on
		if ( ! wp_verify_nonce( $_POST['wpapi_nonce'], 'wp_rest' ) ) {
			$r['status'] = 'Nonce Failed';
			echo wp_json_encode( $r );
			exit;
		}


		//We do not want to continue if there is not an post_id set
		if ( empty( $_POST['post_id'] ) ) {
			$r['status'] = 'post_id Not Set';
			echo wp_json_encode( $r );
			exit;
		}


		//Cleanup post_ids
		$theme_chunk_id = lct_get_post_id( $_POST['post_id'] );


		$args = [ 'id' => $theme_chunk_id ];
		if ( in_array( $theme_chunk_id, [ 1533165, 68094 ] ) ) { //XBS
			$args['dont_decode'] = true;
		}
		$r['content'] = $this->theme_chunk( $args );


		if ( $r['content'] ) {
			$r['status'] = 'Got Content';
		}


		echo wp_json_encode( $r );
		exit;
	}


	/**
	 * [theme_chunk]
	 * Get the post_content from your theme_chunk
	 *
	 * @att      int id
	 * @att      bool ajax
	 * @att      bool dont_check
	 * @att      bool dont_sc
	 * @att      bool fusion_calculate_columns
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    0.0
	 * @verified 2022.04.04
	 */
	function theme_chunk( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'                        => '',
				'id'                       => 0,
				'ajax'                     => false,
				'dont_check'               => false,
				'dont_sc'                  => false,
				'wpautop'                  => false,
				'fusion_calculate_columns' => true,
				'dont_decode'              => false,
			],
			$a
		);


		if ( $a['id'] ) {
			/**
			 * Switches
			 */
			$a['ajax']                     = filter_var( $a['ajax'], FILTER_VALIDATE_BOOLEAN );
			$a['dont_check']               = filter_var( $a['dont_check'], FILTER_VALIDATE_BOOLEAN );
			$a['dont_sc']                  = filter_var( $a['dont_sc'], FILTER_VALIDATE_BOOLEAN );
			$a['wpautop']                  = filter_var( $a['wpautop'], FILTER_VALIDATE_BOOLEAN );
			$a['fusion_calculate_columns'] = filter_var( $a['fusion_calculate_columns'], FILTER_VALIDATE_BOOLEAN );
			$a['dont_decode']              = filter_var( $a['dont_decode'], FILTER_VALIDATE_BOOLEAN );


			/**
			 * id
			 */
			if (
				is_object( $a['id'] )
				&& ! lct_is_wp_error( $a['id'] )
			) {
				$a['id'] = $a['id']->ID;
			}

			$a['id'] = (int) $a['id'];


			if ( $a['ajax'] ) {
				$type    = 'html';
				$div_id  = 'lct_theme_chunk_' . $a['id'];
				$class   = '';
				$message = '';


				if ( lct_is_user_a_dev() ) {
					$message .= sprintf( '<p style="text-align: center;">%s</p>', $type );

					$message .= sprintf( '<p style="text-align: center;">%s</p>', $div_id );
				}


				$a['r'] = sprintf( '<div id="%s" class="%s" data-content="%s">%s</div>', $div_id, trim( $class ), $type, $message );


				lct_append_setting( 'ajax_theme_chunks', $div_id );
			} else {
				$this->disable_balanceTags();


				$chunk_content = '';


				if (
					( $chunk = get_post( $a['id'] ) )
					&& isset( $chunk->post_content )
				) {
					$chunk_content = $chunk->post_content;
				}


				$a['r'] = apply_filters( 'lct/theme_chunk/content', $chunk_content );
			}


			/**
			 * Process Fusion Builder Columns
			 */
			if (
				version_compare( lct_theme_version( 'Avada' ), '7.0', '<' )
				&& //Avada older than v7.0
				$a['fusion_calculate_columns']
				&& $a['r']
				&& strpos( $a['r'], 'fusion_builder_container' ) !== false
				&& class_exists( 'FusionBuilder' )
			) {
				$FusionBuilder = FusionBuilder::get_instance();


				$a['r'] = $FusionBuilder->fusion_calculate_columns( $a['r'] );
			}


			/**
			 * Process nested shortcodes
			 */
			if ( ! $a['dont_check'] ) {
				$a['r'] = lct_check_for_nested_shortcodes( $a['r'] );
			}


			/**
			 * Process regular shortcodes
			 */
			if ( ! $a['dont_sc'] ) {
				if ( strpos( $a['r'], '[embed' ) !== false ) {
					global $wp_embed;
					add_shortcode( 'embed', [ $wp_embed, 'shortcode' ] );
				}


				$a['r'] = do_shortcode( $a['r'] );
			}


			/**
			 * Process final shortcodes
			 */
			$a['r'] = lct_final_shortcode_check( $a['r'] );


			/**
			 * Bug fix for fusion_builder
			 */
			$a['r'] = lct_the_content_fusion_builder_bug_fix( $a['r'] );


			/**
			 * Decode and HTML
			 */
			if ( ! $a['dont_decode'] ) {
				$a['r'] = html_entity_decode( $a['r'] );
			}


			/**
			 * WP auto p
			 */
			if ( $a['wpautop'] ) {
				$a['r'] = wpautop( $a['r'] );
			}
		}


		return $a['r'];
	}


	/**
	 * Allow page ordering for theme_chunk
	 *
	 * @param WP_Query $q
	 *
	 * @since        7.27
	 * @verified     2017.04.27
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function allow_page_ordering( $q )
	{
		if (
			is_admin()
			&& $q->is_main_query()
			&& isset( $q->query_vars['post_type'] )
			&& $q->query_vars['post_type'] == 'lct_theme_chunk'
		) {
			if ( ! $q->query_vars['orderby'] ) {
				$q->query_vars['orderby'] = 'menu_order';
			}

			if ( ! $q->query_vars['order'] ) {
				$q->query_vars['order'] = 'asc';
			}


			$this->remove_allow_page_ordering();
		}
	}


	/**
	 * Remove the allow_page_ordering pre_get_posts hook
	 *
	 * @since    2017.34
	 * @verified 2017.04.27
	 */
	function remove_allow_page_ordering()
	{
		remove_action( 'pre_get_posts', [ $this, 'allow_page_ordering' ] );
	}


	/**
	 * Allow fusion builder on theme_chunk post_type
	 *
	 * @param $post_types
	 *
	 * @return array
	 * @since    7.28
	 * @verified 2016.12.08
	 */
	function fusion_builder_allow( $post_types )
	{
		$post_types[] = 'lct_theme_chunk';


		return $post_types;
	}


	/**
	 * We need to disable balanceTags if the chunk being processed is in a widget
	 *
	 * @since    2017.9
	 * @verified 2017.02.07
	 */
	function disable_balanceTags()
	{
		$current_filter = current_filter();


		if (
			$current_filter
			&& strpos( $current_filter, 'widget_' ) !== false
		) {
			remove_filter( 'widget_text', 'balanceTags' );
			add_filter( 'widget_text', [ $this, 're_enable_balanceTags' ], 11 );
		}
	}


	/**
	 * We can re-enable balanceTags now
	 *
	 * @param $widget_text
	 *
	 * @return mixed
	 * @since    2017.9
	 * @verified 2017.02.07
	 */
	function re_enable_balanceTags( $widget_text )
	{
		add_filter( 'widget_text', 'balanceTags' );


		return $widget_text;
	}
}
