<?php
/** @noinspection PhpMissingFieldTypeInspection */
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2016.12.15
 */
class lct_Avada_admin
{
	/**
	 * @var int
	 */
	var $column_count = 0;


	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2016.12.15
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
	 * @since    7.56
	 * @verified 2022.10.07
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
		/**
		 * actions
		 */
		add_action( 'avada_before_body_content', [ $this, 'avada_before_body_content' ] );

		//fusionredux/options/{$this->parent->args['opt_name']}/saved
		add_action( 'fusionredux/options/fusion_options/saved', [ $this, 'fusion_options_saved' ], 999 );


		/**
		 * filters
		 */
		add_filter( 'fusion_builder_allowed_post_types', [ $this, 'fusion_builder_allowed_post_types' ], 999 );

		//add_filter( 'fusion_element_image_content', [ $this, 'prevent_image_element_lazy_loading' ], 1, 2 );
		//add_filter( 'fusion_element_column_content', [ $this, 'prevent_column_element_lazy_loading' ], 1, 2 );
		//add_filter( 'fusion_element_container_content', [ $this, 'prevent_container_element_lazy_loading' ], 1, 2 );
		//add_filter( 'fusion_attr_image-shortcode-tag-element', [ $this, 'prevent_image_element_lazy_loading_deep' ], 999 );
		//add_filter( 'fusion_shortcode_content', [ $this, 'fusion_shortcode_content_fusion_imageframe' ], 10, 3 );

		add_filter( 'fusion_element_button_content', [ $this, 'add_yoast_ga_onclick' ], 10, 2 );


		if ( lct_frontend() ) {
			/**
			 * actions
			 */
			add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_styles' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );

			add_action( 'init', [ $this, 'remove_avada_render_footer_social_icons' ] );


			/**
			 * filters
			 */
			add_filter( 'lct_script_mobile_threshold', [ $this, 'script_mobile_threshold' ] );

			add_filter( 'nav_menu_item_id', [ $this, 'nav_menu_item_id' ], 2, 3 );

			add_filter( 'nav_menu_css_class', [ $this, 'nav_menu_css_class' ], 2, 3 );
		}


		if ( lct_wp_admin_all() ) {
			add_filter( 'fusion_builder_shortcode_migration_post_types', [ $this, 'fusion_builder_shortcode_migration_post_types' ] );
		}


		if ( lct_wp_admin_non_ajax() ) {
			//load-{$pagenow}
			add_action( 'load-update-core.php', [ $this, 'update_all_sidebar_metas' ] );

			//load-{$pagenow}
			add_action( 'load-update-core.php', [ $this, 'fusion_options_saved' ] );

			//save_post_{$post->post_type}
			add_action( 'save_post_post', [ $this, 'allow_update_sidebar_meta' ], 10, 3 );

			add_action( 'admin_notices', [ $this, 'check_for_bad_avada_assets' ] );

			//load-{$pagenow}
			add_action( 'load-update-core.php', [ $this, 'check_all_fusion_pages_for_bad_avada_assets' ] );
		}
	}


	/**
	 * Remove the full url for images stored in Avada theme options when saving
	 * Update the Google Maps API that Avada uses
	 *
	 * @since    0.0
	 * @verified 2022.10.07
	 */
	function fusion_options_saved()
	{
		if ( ! ( $options = get_option( 'fusion_options' ) ) ) {
			return;
		}


		/**
		 * Update Images
		 */
		$options_string = wp_json_encode( $options );
		$keys           = [
			'url',
			'thumbnail',
		];


		if ( lct_is_dev_or_sb() ) {
			$url = str_replace( '/', '\/', lct_url_site_when_dev() . '/' );


			if ( str_contains( $options_string, $url ) ) {
				$check_options = true;
			}
		} else {
			$url = str_replace( '/', '\/', lct_url_site() . sprintf( '.%s.eetah.com', get_option( 'options_' . zxzacf( 'clientzz' ), 'none' ) ) . '/' );


			if ( str_contains( $options_string, $url ) ) {
				$check_options = true;
			}
		}


		//Check all the options for images
		if ( ! empty( $check_options ) ) {
			foreach ( $options as $k => $v ) {
				if ( $k === 'social_media_icons' ) {
					continue;
				}


				if (
					isset( $v['url'] )
					&& (
						! empty( $v['url'] )
						|| ! empty( $v['thumbnail'] )
					)
				) {
					$fields_to_update[] = $k;
				}
			}


			//Change the urls
			if ( ! empty( $fields_to_update ) ) {
				foreach ( $fields_to_update as $field ) {
					foreach ( $keys as $key ) {
						if ( ! empty( $options[ $field ][ $key ] ) ) {
							$new_url = lct_remove_site_root_all( $options[ $field ][ $key ] );
							$new_url = lct_url_site() . $new_url;


							if ( $new_url !== $options[ $field ][ $key ] ) {
								$options[ $field ][ $key ] = $new_url;


								$update = true;
							}
						}
					}
				}
			}
		}


		/**
		 * Avada has a field to store our Google Maps API. We will just auto give Avada the one already stored in LCT options
		 */
		$field = 'gmap_api';


		if (
			empty( $options[ $field ] )
			&& //Don't Update it is there is already a stored value
			( $api_key = lct_acf_get_option_raw( 'google_map_api' ) ) //Only update it if LCT has a stored value
		) {
			$options[ $field ] = $api_key;


			$update = true;
		}


		/**
		 * Update
		 */
		if ( ! empty( $update ) ) {
			update_option( 'fusion_options', $options );
		}
	}


	/**
	 * ADD sandbox bars so that people don't put in content meant for live site.
	 *
	 * @since    5.37
	 * @verified 2017.07.25
	 */
	function avada_before_body_content()
	{
		if ( lct_plugin_active( 'acf' ) ) {
			$do_it = false;


			if ( lct_acf_get_option_raw( 'show_sandbox_warning' ) ) {
				if (
					lct_acf_get_option_raw( 'show_sandbox_warning_dev' )
					&& lct_is_dev()
				) {
					$do_it = true;
				}

				if (
					lct_acf_get_option_raw( 'show_sandbox_warning_sandbox' )
					&& lct_is_sb()
				) {
					$do_it = true;
				}
			}


			if ( $do_it ) {
				lct_update_setting( 'tmp_disable_dev_url', true );
				$root_site = lct_url_root_site();
				lct_update_setting( 'tmp_disable_dev_url', null );


				$message = "
						<a href='" . $root_site . "' target='_blank'>
						DEV<br />
						SITE<br />
						DO NOT<br />
						PUT IN<br />
						LIVE<br />
						CHANGES<br />
						CLICK HERE
						</a>
						";
				$message = $message . $message . $message . $message . $message;

				if ( lct_acf_get_option_raw( 'show_sandbox_warning_side' ) ) {
					echo "<div class='" . zxzu() . "sandbox " . zxzu() . "sandbox_left'>{$message}</div>";
					echo "<div class='" . zxzu() . "sandbox " . zxzu() . "sandbox_right'>{$message}</div>";
				} else {
					$message = str_replace( '<br />', ' ', $message );

					echo "<div class='" . zxzu() . "sandbox " . zxzu() . "sandbox_bottom'>{$message}</div>";
				}

				echo "<style>
					." . zxzu() . "sandbox{
						color: #FFFFFF;
					}


					." . zxzu() . "sandbox a{
						color: #FFFFFF;
					}


					." . zxzu() . "sandbox_left{
						z-index:    99999;
						position:   fixed;
						top:        0;
						left:       0;
						background: red;
						height:     100%;
						width:      75px;
						text-align: center;
					}


					." . zxzu() . "sandbox_right{
						z-index:    99999;
						position:   fixed;
						top:        0;
						right:      0;
						background: red;
						height:     100%;
						width:      75px;
						text-align: center;
					}


					." . zxzu() . "sandbox_bottom{
						z-index:    99999;
						position:   fixed;
						bottom:     0;
						background: red;
						height:     55px;
						width:      100%;
						text-align: center;
					}
					</style>";
			}
		}
	}


	/**
	 * Register Scripts
	 *
	 * @since    2017.48
	 * @verified 2020.01.16
	 */
	function wp_enqueue_scripts()
	{
		if (
			lct_acf_get_option( 'sc::use_overlay_menu' )
			|| lct_plugin_active( 'nks' )
		) {
			if ( has_nav_menu( 'mobile_navigation' ) ) {
				$file = 'nks-mobile.min.js';
			} elseif ( version_compare( lct_theme_version( 'Avada' ), '5.1', '<' ) ) //Avada older than v5.1
			{
				$file = 'nks-legacy_lt-5.1.min.js';
			} else {
				$file = 'nks.min.js';
			}


			lct_enqueue_script( zxzu( 'Avada_nks' ), lct_get_root_url( 'assets/js/plugins/Avada/' . $file ) );
		}


		/**
		 * Fix the stupid Comments Off text
		 */
		if (
			( $blogger = get_user_by( 'login', 'blogger' ) )
			&& $blogger->display_name
		) {
			$jq = "jQuery( window ).load( function() {
			if( jQuery( '.fusion-recent-posts .meta' ).length ) {
				jQuery( '.fusion-recent-posts .meta' ).each( function() {
					var text = jQuery( this ).html();
					text = text.replace( 'Comments Off', '" . $blogger->display_name . "' );
					jQuery( this ).html( text );
				} );
			}
		} );";


			/**
			 * #4
			 * @date     0.0
			 * @since    0.0
			 * @verified 2021.08.27
			 */
			do_action( 'lct_jq_doc_ready_add', $jq );
		}
	}


	/**
	 * Register Styles
	 *
	 * @since    0.0
	 * @verified 2022.02.23
	 */
	function wp_enqueue_styles()
	{
		if ( lct_plugin_active( 'acf' ) ) {
			if ( ! lct_acf_get_option_raw( 'disable_avada_css' ) ) {
				if ( version_compare( lct_theme_version( 'Avada' ), '4.0', '<' ) ) //Avada older than v4.0
				{
					lct_enqueue_style( zxzu( 'Avada' ), lct_get_root_url( 'assets/css/plugins/Avada/main-legacy_lt-4.0.min.css' ) );
				} else {
					lct_enqueue_style( zxzu( 'Avada' ), lct_get_root_url( 'assets/css/plugins/Avada/main.min.css' ) );
				}


				lct_enqueue_style( zxzu( 'Avada_dynamic_css' ), $this->dynamic_css() );
			}


			if ( ! lct_acf_get_option_raw( 'enable_avada_css_page_defaults' ) ) {
				lct_enqueue_style( zxzu( 'Avada_page_defaults' ), lct_get_root_url( 'assets/css/plugins/Avada/page_defaults.min.css' ) );


				switch ( lct_acf_get_option_raw( 'tablet_threshold' ) ) {
					case '800':
						lct_enqueue_style( zxzu( 'Avada_page_defaults_media' ), lct_get_root_url( 'assets/css/plugins/Avada/page_defaults-media-tablet-800.min.css' ) );
						break;


					case '1024':
						lct_enqueue_style( zxzu( 'Avada_page_defaults_media' ), lct_get_root_url( 'assets/css/plugins/Avada/page_defaults-media-tablet-1024.min.css' ) );
						break;


					default:
				}
			}


			if ( lct_acf_get_option_raw( 'page_title_bar_auto' ) ) {
				$top    = str_replace( 'px', '', lct_acf_get_option( 'page_title_bar_padding_top' ) );
				$bottom = str_replace( 'px', '', lct_acf_get_option( 'page_title_bar_padding_bottom' ) );

				if ( strpos( $top, '%' ) === false ) {
					$top .= 'px';
				}

				if ( strpos( $bottom, '%' ) === false ) {
					$bottom .= 'px';
				}

				$style = sprintf(
					'.fusion-body .fusion-page-title-bar{
						height: auto !important;
						padding-top: %s;
						padding-bottom: %s;
					}
					.fusion-body .fusion-page-title-bar .fusion-page-title-row,
					.fusion-body .fusion-page-title-bar .fusion-page-title-wrapper{
						height: auto !important;
					}',
					$top,
					$bottom
				);


				/**
				 * #2
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.27
				 */
				do_action( 'lct_wp_footer_style_add', $style );
			}
		}
	}


	/**
	 * Update all the post sidebars if the conditions are right
	 * //TODO: cs - This needs to be improved - 8/25/2017 5:06 PM
	 *
	 * @since    7.56
	 * @verified 2017.08.25
	 */
	function update_all_sidebar_metas()
	{
		$options = get_option( 'fusion_options' );


		if (
			! lct_get_option( 'update_all_sidebar_metas' )
			&& ! empty( $options )
			&& isset( $options['posts_global_sidebar'] )
			&& ! $options['posts_global_sidebar']
		) {
			$args  = [
				'posts_per_page'         => - 1,
				'post_type'              => 'post',
				'cache_results'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			];
			$posts = get_posts( $args );


			if ( ! lct_is_wp_error( $posts ) ) {
				foreach ( $posts as $post ) {
					$this->update_sidebar_meta( $post->ID );
				}


				//only do it once
				lct_update_option( 'update_all_sidebar_metas', true, false );
			}
		}


		if (
			! lct_get_option( 'update_all_page_sidebar_metas' )
			&& ! empty( $options )
			&& isset( $options['pages_global_sidebar'] )
			&& ! $options['pages_global_sidebar']
		) {
			$args  = [
				'posts_per_page'         => - 1,
				'post_type'              => 'page',
				'cache_results'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			];
			$posts = get_posts( $args );


			if ( ! lct_is_wp_error( $posts ) ) {
				foreach ( $posts as $post ) {
					$this->update_page_sidebar_meta( $post->ID );
				}


				//only do it once
				lct_update_option( 'update_all_page_sidebar_metas', true, false );
			}
		}
	}


	/**
	 * Update the saved post sidebars if the conditions are right
	 *
	 * @param $post_id
	 * @param $post
	 * @param $update
	 *
	 * @since    7.56
	 * @verified 2016.12.15
	 */
	function allow_update_sidebar_meta(
		/** @noinspection PhpUnusedParameterInspection */
		$post_id,
		/** @noinspection PhpUnusedParameterInspection */
		$post,
		/** @noinspection PhpUnusedParameterInspection */
		$update
	) {
		add_action( 'lct/always_shutdown_wp_admin', [ $this, 'update_sidebar_meta' ] );
	}


	/**
	 * Update the saved post sidebars if the conditions are right
	 *
	 * @param null $post_id
	 *
	 * @since    7.56
	 * @verified 2018.03.22
	 */
	function update_sidebar_meta( $post_id = null )
	{
		if ( ! lct_is_user_a_dev() ) {
			return;
		}


		if ( $post_id = lct_get_post_id( $post_id ) ) {
			$sidebar  = get_post_meta( $post_id, 'sbg_selected_sidebar_replacement', true );
			$position = get_post_meta( $post_id, 'pyre_sidebar_position', true );


			/**
			 * Success
			 */
			if (
				(
					$position == 'default'
					&& isset( $sidebar[0] )
					&& $sidebar[0] === 'Blog Sidebar'
				)
				|| (
					! $position
					&& ! $sidebar
				)
			) {
				update_post_meta( $post_id, 'sbg_selected_sidebar_replacement', [ 'Blog' ] );


				lct_get_notice( 'Post sidebar for <a href="' . get_the_permalink( $post_id ) . '" target="_blank">' . get_the_title( $post_id ) . '</a> was updated.' );


				/**
				 * Error
				 */
			} else {
				lct_get_notice( 'Post sidebar for <a href="' . get_the_permalink( $post_id ) . '" target="_blank">' . get_the_title( $post_id ) . '</a> not updated.', - 1 );
			}
		}
	}


	/**
	 * Update the saved post sidebars if the conditions are right
	 *
	 * @param null $post_id
	 *
	 * @since    7.62
	 * @verified 2018.03.22
	 */
	function update_page_sidebar_meta( $post_id = null )
	{
		if ( ! lct_is_user_a_dev() ) {
			return;
		}


		if ( $post_id = lct_get_post_id( $post_id ) ) {
			$position = get_post_meta( $post_id, 'pyre_sidebar_position', true );


			/**
			 * Success
			 */
			if (
				(
					$position == 'default'
					|| ! $position
				)
				&& ! get_post_meta( $post_id, 'sbg_selected_sidebar_replacement', true )
			) {
				update_post_meta( $post_id, 'sbg_selected_sidebar_replacement', [ 'Page' ] );


				lct_get_notice( 'Page sidebar for <a href="' . get_the_permalink( $post_id ) . '" target="_blank">' . get_the_title( $post_id ) . '</a> was updated.' );


				/**
				 * Error
				 */
			} else {
				lct_get_notice( 'Page sidebar for <a href="' . get_the_permalink( $post_id ) . '" target="_blank">' . get_the_title( $post_id ) . '</a> not updated.', - 1 );
			}
		}
	}


	/**
	 * Add our post_types to the array.
	 * We have to check all of them and reset the keys or the migration won't work
	 *
	 * @param $post_types
	 *
	 * @return array
	 * @since    7.36
	 * @verified 2016.11.21
	 */
	function fusion_builder_shortcode_migration_post_types( $post_types )
	{
		$post_types[] = 'lct_theme_chunk';

		if ( lct_get_setting( 'use_org' ) ) {
			$post_types[] = 'lct_org';
		}


		foreach ( $post_types as $key => $post_type ) {
			if ( ! post_type_exists( $post_type ) ) {
				unset( $post_types[ $key ] );
			}
		}


		return array_values( $post_types );
	}


	/**
	 * dynamic_css
	 *
	 * @return string
	 * @since    7.66
	 * @verified 2017.09.09
	 */
	function dynamic_css()
	{
		if ( version_compare( lct_theme_version( 'Avada' ), '4.0', '<' ) ) //Avada older than v4.0
		{
			return '';
		}


		ob_start();


		//You must call the whole setting first, or it will break Avada's cache
		Avada()->settings->get( 'body_typography' );
		?>


		<style>
			/*
			 * Add BORDER BOTTOMS to links
			 */
			<?php if( lct_acf_get_option_raw( 'border_underline_links' ) ){ ?>
			#content a,
			#content a:hover,
			.fusion-post-content-container a,
			.fusion-post-content-container a:hover{
				text-decoration: none;
			}


			#main .post h2 a,
			.fusion-read-more{
				text-decoration: none;
				border-bottom:   <?php echo lct_acf_get_option( 'border_underline_links_thickness' ); ?>px solid transparent !important;
			}


			#main .post h2 a:hover,
			.fusion-read-more:hover{
				border-bottom: <?php echo lct_acf_get_option( 'border_underline_links_thickness' ); ?>px solid <?php echo lct_acf_get_option( 'border_underline_links_color' ); ?> !important;
			}


			/*
			 * When NOT hover
			 */
			<?php if( lct_acf_get_option_raw( 'underline_links' ) ){ ?>
			#content a,
			.fusion-post-content-container a,
			.fusion-footer-widget-area a{
				border-bottom: <?php echo lct_acf_get_option( 'border_underline_links_thickness' ); ?>px solid<?php echo lct_acf_get_option( 'border_underline_links_color' ); ?>;
			}


			#content a:hover,
			.fusion-post-content-container a:hover,
			.fusion-footer-widget-area a:hover{
				border-bottom: <?php echo lct_acf_get_option( 'border_underline_links_thickness' ); ?>px solid transparent;
			}


			/*
			 * When hover
			 */
			<?php } else { ?>
			#content a,
			.fusion-post-content-container a,
			.fusion-footer-widget-area a{
				border-bottom: none;
			}


			#content a:hover,
			.fusion-post-content-container a:hover,
			.fusion-footer-widget-area a:hover{
				border-bottom: <?php echo lct_acf_get_option( 'border_underline_links_thickness' ); ?>px solid<?php echo lct_acf_get_option( 'border_underline_links_color' ); ?>;
			}


			<?php } ?>

			/*
			 * Add underlines to links
			 */
			<?php }else{ ?>
			#main .post h2 a,
			.fusion-read-more{
				text-decoration: none !important;
			}


			#main .post h2 a:hover,
			.fusion-read-more:hover{
				text-decoration: underline !important;
			}


			/*
			 * When NOT hover
			 */
			<?php if( lct_acf_get_option_raw( 'underline_links' ) ){ ?>
			#content a,
			.fusion-post-content-container a,
			.fusion-footer-widget-area a{
				text-decoration: underline;
			}


			#content a:hover,
			.fusion-post-content-container a:hover,
			.fusion-footer-widget-area a:hover{
				text-decoration: none;
			}


			/*
			 * When hover
			 */
			<?php } else { ?>
			#content a,
			.fusion-post-content-container a,
			.fusion-footer-widget-area a{
				text-decoration: none;
			}


			#content a:hover,
			.fusion-post-content-container a:hover,
			.fusion-footer-widget-area a:hover{
				text-decoration: underline;
			}


			<?php } ?>

			<?php } ?>

			/*
			 * When NOT hover
			 */
			<?php if( lct_acf_get_option_raw( 'underline_links_header' ) ){ ?>
			.fusion-header-wrapper a{
				text-decoration: underline;
			}


			.fusion-header-wrapper a:hover{
				text-decoration: none;
			}


			/*
			 * When hover
			 */
			<?php } else { ?>
			.fusion-header-wrapper a{
				text-decoration: none;
			}


			.fusion-header-wrapper a:hover{
				text-decoration: underline;
			}


			<?php } ?>

			/* Force body color */
			/*noinspection CssUnusedSymbol*/
			.<?php echo zxzu( 'body_typography_color' ); ?>,
			.<?php echo zxzu( 'body_typography_color' ); ?> .fa{
				color: <?php echo Avada()->settings->get( 'body_typography', 'color' ); ?>;
			}


			/*noinspection CssUnusedSymbol*/
			.<?php echo zxzu( 'body_typography_color:hover' ); ?>,
			.<?php echo zxzu( 'body_typography_color:hover' ); ?> .fa{
				color: <?php echo Avada()->settings->get( 'primary_color' ); ?>;
			}


			/* Make tel links look like regular text on desktops */
			@media (min-width: <?php echo lct_get_mobile_threshold() + 1;?>px){
				/* STARTzz */
				.tel,
				.tel:hover,
				[href*="tel:"],
				[href*="tel:"]:hover,
				.tel .fa,
				.tel:hover .fa,
				[href*="tel:"] .fa,
				[href*="tel:"]:hover .fa,
				.fusion-footer-widget-area [href*="tel:"],
				.fusion-footer-widget-area [href*="tel:"]:hover,
				.fusion-footer-widget-area .widget [href*="tel:"],
				.fusion-footer-widget-area .widget [href*="tel:"]:hover{
					color: <?php echo Avada()->settings->get( 'body_typography', 'color' ); ?>;
				}


				/* ENDzz */
			}


			/* videoWrapper */
			@media (max-width: <?php echo lct_get_small_mobile_threshold(); ?>px){
				/* STARTzz */
				.videoWrapper.alignleft,
				.videoWrapper.alignright{
					display:      block;
					float:        none;
					margin-right: auto;
					margin-left:  auto;
				}


				/* ENDzz */
			}
		</style>


		<?php
		return str_replace( [ '<style>', '</style>' ], '', ob_get_clean() );
	}


	/**
	 * Disable Avada's social link icons that are printed in the footer
	 *
	 * @since    7.69
	 * @verified 2017.01.10
	 */
	function remove_avada_render_footer_social_icons()
	{
		if ( lct_acf_get_option_raw( 'disable_social_footer' ) ) {
			remove_action( 'avada_footer_copyright_content', 'avada_render_footer_social_icons', 15 );
		}
	}


	/**
	 * Set the tablet threshold
	 * **we have to call this mobile threshold for backward compatibility
	 *
	 * @param $threshold
	 *
	 * @return int
	 * @since    6.0
	 * @verified 2017.09.09
	 */
	function script_mobile_threshold( $threshold = 1024 )
	{
		if ( version_compare( lct_theme_version( 'Avada' ), '4.0', '>=' ) ) { //Avada is v4.0 and newer
			$new_threshold = (int) Avada()->settings->get( 'side_header_break_point' );

			if ( $new_threshold ) {
				$threshold = $new_threshold;
			}
		}


		return $threshold;
	}


	/**
	 * We have to check all of them and reset the keys or the migration won't work, do this last as we have other calls that are adding post_types
	 *
	 * @param $post_types
	 *
	 * @return array
	 * @since    7.28
	 * @verified 2016.12.08
	 */
	function fusion_builder_allowed_post_types( $post_types )
	{
		foreach ( $post_types as $key => $post_type ) {
			if ( ! post_type_exists( $post_type ) ) {
				unset( $post_types[ $key ] );
			}
		}


		return array_values( $post_types );
	}


	/**
	 * We don't want to store stuff here as it could get lost. So let's warn people.
	 *
	 * @since    7.69
	 * @verified 2021.10.05
	 */
	function check_for_bad_avada_assets()
	{
		global $pagenow;


		if (
			! current_user_can( 'administrator' )
			|| version_compare( lct_theme_version( 'Avada' ), '4.0', '<' )
			|| //Avada is elder than v4.0
			$pagenow === 'post.php'
		) {
			return;
		}


		$post      = get_post();
		$zxza      = zxzb( ' Panel' );
		$zxza_link = admin_url( 'admin.php?page=' . zxzu( 'acf_op_main_settings' ) );


		if (
			Avada()->settings->get( 'google_analytics' )
			&& apply_filters( 'lct/avada/check_for_bad_avada_assets/google_analytics', true )
		) {
			$yoast = 'Google Analytics by MonsterInsights';


			if ( lct_plugin_active( 'Yoast_GA' ) ) {
				$yoast_link = admin_url( 'admin.php?page=yst_ga_settings' );


				lct_get_notice( 'You have tracking code in the Avada Theme Options, please move the code to <a href="' . $yoast_link . '">' . $yoast . '</a>', - 1 );
			} else {
				$yoast_link = admin_url( 'plugin-install.php?s=Google+Analytics+by+MonsterInsights&tab=search&type=term' );


				lct_get_notice( 'You have tracking code in the Avada Theme Options, please install <a href="' . $yoast_link . '">' . $yoast . '</a> & move the code there.', - 1 );
			}
		}


		if (
			Avada()->settings->get( 'space_head' )
			&& apply_filters( 'lct/avada/check_for_bad_avada_assets/head_space', true )
		) {
			lct_get_notice( 'You have "Space before ' . esc_html( '</head>' ) . '" code in the Avada Theme Options, please move the code to <a href="' . $zxza_link . '">' . $zxza . '</a>', - 1 );
		}


		if (
			Avada()->settings->get( 'space_body' )
			&& apply_filters( 'lct/avada/check_for_bad_avada_assets/head_space', true )
		) {
			lct_get_notice( 'You have "Space before ' . esc_html( '</body>' ) . '" code in the Avada Theme Options, please move the code to <a href="' . $zxza_link . '">' . $zxza . '</a>', - 1 );
		}


		if (
			Avada()->settings->get( 'custom_css' )
			&& apply_filters( 'lct/avada/check_for_bad_avada_assets/custom_css', true, Avada()->settings->get( 'custom_css' ) )
		) {
			lct_get_notice( 'You have "CSS Code" in the Avada Theme Options, please move the code to a CSS file in the /apps/dev/ directory and recompile the .min.css files.', - 1 );
		}


		if (
			$post
			&& get_post_meta( $post->ID, '_fusion_builder_custom_css', true )
		) {
			lct_get_notice( 'You have "Custom CSS" in the Avada Fusion Page Builder, please move the code to a CSS file in the /apps/dev/ directory and recompile the .min.css files.', - 1 );
		}
	}


	/**
	 * We don't want to store stuff here as it could get lost. So let's warn people.
	 *
	 * @since    7.69
	 * @verified 2023.05.03
	 */
	function check_all_fusion_pages_for_bad_avada_assets()
	{
		if (
			! lct_is_user_a_dev()
			|| version_compare( lct_theme_version( 'Avada' ), '5.1', '<' ) //Avada is older than v5.1
		) {
			return;
		}


		$args               = [
			'_builtin' => false,
		];
		$post_types         = get_post_types( $args );
		$post_types['page'] = 'page';
		$post_types['post'] = 'post';
		$post_types         = apply_filters( 'lct/check_all_fusion_pages_for_bad_avada_assets', $post_types );


		$hidden_post_types = [
			'acf-field',
			'acf-field-group',
		];


		if (
			! empty( $post_types )
			&& is_array( $post_types )
		) {
			foreach ( $post_types as $post_type ) {
				if ( in_array( $post_type, $hidden_post_types ) ) {
					continue;
				}


				$args  = [
					'posts_per_page'         => - 1,
					'post_type'              => $post_type,
					'post_status'            => 'any',
					'cache_results'          => true,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
				];
				$posts = get_posts( $args );


				if ( $posts ) {
					foreach ( $posts as $post ) {
						if (
							get_post_meta( $post->ID, 'fusion_builder_status', true ) == 'active'
							&& get_post_meta( $post->ID, '_fusion_builder_custom_css', true )
						) {
							lct_get_notice( 'You have "Custom CSS" in the Avada Fusion Page Builder for <a href="' . get_the_permalink( $post ) . '">' . get_the_title( $post ) . '</a>, please move the code to a CSS file in the /apps/dev/ directory and recompile the .min.css files.', - 1 );
						}
					}
				}
			}
		}
	}


	/**
	 * Update the menu_item id of mobile nav items
	 *
	 * @param $id
	 * @param $item
	 * @param $args
	 *
	 * @return mixed
	 * @since    2018.33
	 * @verified 2018.03.22
	 */
	function nav_menu_item_id(
		$id,
		/** @noinspection PhpUnusedParameterInspection */
		$item,
		$args
	) {
		if ( $args->theme_location === 'mobile_navigation' ) {
			$id = str_replace( 'menu-item-', 'mobile-menu-item-', $id );
		}


		return $id;
	}


	/**
	 * Update the menu_item id of mobile nav items
	 *
	 * @param $classes
	 * @param $item
	 * @param $args
	 *
	 * @return mixed
	 * @since    2018.33
	 * @verified 2018.03.22
	 */
	function nav_menu_css_class(
		$classes,
		/** @noinspection PhpUnusedParameterInspection */
		$item,
		$args
	) {
		if ( $args->theme_location === 'mobile_navigation' ) {
			$classes[] = 'fusion-mobile-nav-item';
		}


		return $classes;
	}


	/**
	 * Disable lazy loading for image_element images.
	 *
	 * @param string $html Full html string.
	 * @param array  $args
	 *
	 * @return string Altered html markup.
	 * @date         2020.10.30
	 * @since        2020.14
	 * @verified     2020.10.30
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function prevent_image_element_lazy_loading( $html, $args )
	{
		if ( strpos( $args['class'], 'disable-lazyload' ) !== false ) {
			preg_match_all( '/<img\s+[^>]*src="([^"]*)"[^>]*>/isU', $html, $images );


			if ( array_key_exists( 1, $images ) ) {
				foreach ( $images[0] as $image ) {
					$orig = $image;


					if ( strpos( $image, ' class=' ) ) {
						$image = str_replace( ' class="', ' class="disable-lazyload ', $image );
					} else {
						$image = str_replace( '<img ', '<img class="disable-lazyload" ', $image );
					}


					// Replace image.
					$html = str_replace( $orig, $image, $html );
				}
			}
		}


		return $html;
	}


	/**
	 * Disable lazy loading for image_element images.
	 *
	 * @param string $html Full html string.
	 * @param array  $args
	 *
	 * @return string Altered html markup.
	 * @date         2020.10.30
	 * @since        2020.14
	 * @verified     2022.02.03
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function prevent_column_element_lazy_loading( $html, $args )
	{
		if (
			! empty( $args['class'] )
			&& strpos( $args['class'], 'disable-lazyload' ) !== false
		) {
			preg_match_all( '/<div\s+[^>]*class="([^"]*)fusion-column-wrapper lazyload([^"]*)"[^>]*style="([^"]*)"[^>]*data-bg-url="([^"]*)"[^>]*>/isU', $html, $wrappers );


			if ( array_key_exists( 1, $wrappers ) ) {
				foreach ( $wrappers[0] as $k => $wrapper ) {
					$html = str_replace( 'class="' . $wrappers[1][ $k ] . 'fusion-column-wrapper lazyload' . $wrappers[2][ $k ], 'class="' . $wrappers[1][ $k ] . 'fusion-column-wrapper disable-lazyload' . $wrappers[2][ $k ], $html );
					$html = str_replace( $wrappers[3][ $k ], $wrappers[3][ $k ] . ';background-image: url(&quot;' . $wrappers[4][ $k ] . '&quot;);', $html );
				}
			}
		}


		return $html;
	}


	/**
	 * Disable lazy loading for image_element images.
	 *
	 * @param string $html Full html string.
	 * @param array  $args
	 *
	 * @return string Altered html markup.
	 * @date         2020.10.30
	 * @since        2020.14
	 * @verified     2022.02.03
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function prevent_container_element_lazy_loading( $html, $args )
	{
		if (
			! empty( $args['class'] )
			&& strpos( $args['class'], 'disable-lazyload' ) !== false
		) {
			preg_match_all( '/<div\s+[^>]*class="([^"]*) lazyload([^"]*)"[^>]*style="([^"]*)"[^>]*data-bg="([^"]*)"[^>]*>/isU', $html, $wrappers );


			if ( array_key_exists( 1, $wrappers ) ) {
				foreach ( $wrappers[0] as $k => $wrapper ) {
					$html = str_replace( 'class="' . $wrappers[1][ $k ] . ' lazyload' . $wrappers[2][ $k ], 'class="' . $wrappers[1][ $k ] . ' ' . $wrappers[2][ $k ], $html );
					$html = str_replace( $wrappers[3][ $k ], $wrappers[3][ $k ] . ';background-image: url(&quot;' . $wrappers[4][ $k ] . '&quot;);', $html );
				}
			}
		}


		return $html;
	}


	/**
	 * Disable lazy loading for image_element images.
	 *
	 * @param array $args
	 *
	 * @return array
	 * @date         2020.10.30
	 * @since        2020.14
	 * @verified     2022.02.03
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function prevent_image_element_lazy_loading_deep( $args )
	{
		if (
			! empty( $args['src'] )
			&& strpos( $args['src'], 'disable-lazyload:::' ) === 0
		) {
			$args['src'] = str_replace( 'disable-lazyload:::', '', $args['src'] );


			if ( key_exists( 'class', $args ) ) {
				$args['class'] = $args['class'] . ' disable-lazyload';
			} else {
				$args['class'] = 'disable-lazyload';
			}
		}


		return $args;
	}


	/**
	 * Disable lazy loading for image_element images.
	 *
	 * @param string $content Full html string.
	 * @param string $type
	 * @param array  $args
	 *
	 * @return string Altered html markup.
	 * @date         2020.10.30
	 * @since        2020.14
	 * @verified     2022.02.03
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function fusion_shortcode_content_fusion_imageframe( $content, $type, $args )
	{
		if (
			! empty( $args['class'] )
			&& $type === 'fusion_imageframe'
			&& strpos( $args['class'], 'disable-lazyload' ) !== false
		) {
			$content = 'disable-lazyload:::' . $content;
		}


		return $content;
	}


	/**
	 * Add a Google Analytics event to a _blank fusion button
	 *
	 * @param sting $html
	 * @param array $args
	 *
	 * @return string
	 * @date     2021.03.08
	 * @since    2021.1
	 * @verified 2024.02.23
	 */
	function add_yoast_ga_onclick( $html, $args )
	{
		if (
			! empty( $args['target'] )
			&& $args['target'] === '_blank'
		) {
			if ( ! isset( $args['link'] ) ) {
				$args['link'] = '';
			}

			$html = str_replace( 'target="_blank"', 'target="_blank" ' . lct_get_gaTracker_onclick( 'Outbound Button', $args['link'] ), $html );
		}


		return $html;
	}
}
