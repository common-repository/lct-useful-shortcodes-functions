<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) )
	exit;


/**
 * LCT Referenced
 *
 * @property array args
 * @property lct   zxzp
 * @verified 2017.09.29
 * @checked  2017.09.29
 */
class lct_admin_template_router {
	public $template_dir = 'templates/';


	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.07.31
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
	 * @since    LCT 7.56
	 * @verified 2017.07.31
	 */
	function load_hooks() {
		//bail early if already ran
		if ( lct_did() )
			return;


		/**
		 * everytime
		 */
		add_filter( 'archive_template', [ $this, 'archive_template' ], 5 );

		add_filter( 'page_template', [ $this, 'page_template' ], 5 );

		add_filter( 'single_template', [ $this, 'single_template' ], 5 );

		add_filter( 'taxonomy_template', [ $this, 'taxonomy_template' ], 5 );

		add_filter( '404_template', [ $this, 'a_404_template' ], 5 );

		add_filter( 'comments_template', [ $this, 'comments_template' ], 5 );

		add_filter( 'woocommerce_locate_template', [ $this, 'wc_locate_template' ], 10, 3 );


		if ( lct_frontend() ) {
			if ( ! shortcode_exists( 'get_template_part' ) )
				add_shortcode( 'get_template_part', [ $this, 'get_template_part' ] );

			add_shortcode( zxzu( 'get_template_part' ), [ $this, 'this_plugin_get_template_part' ] );
		}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * the child theme takes authority over all, so no need to continue if there is a match
	 *
	 * @param $template
	 *
	 * @return bool
	 * @since    LCT 7.58
	 * @verified 2017.07.31
	 */
	function file_in_active_theme( $template ) {
		$r = false;


		if (
			$template &&
			strpos( $template, get_stylesheet_directory() ) !== false &&
			file_exists( $template )
		) {
			$r = true;
		}


		return $r;
	}


	/**
	 * shortcut to get the new template file path
	 *
	 * @param $template
	 *
	 * @return bool
	 * @since    LCT 7.58
	 * @verified 2017.07.31
	 */
	function new_template( $template ) {
		global $wp_query;


		return lct_get_path( $this->template_dir . $template . '-' . $wp_query->get( 'post_type' ) . '.php' );
	}


	/**
	 * Try to load from the archive template file from the plugin first
	 *
	 * @param $template
	 *
	 * @return string
	 * @since    LCT 7.56
	 * @verified 2017.07.31
	 */
	function archive_template( $template ) {
		if ( ! $this->file_in_active_theme( $template ) ) {
			//let other plugins go first
			$new_template = apply_filters( 'lct/archive_template', null, $template );


			if ( $new_template ) {
				$template = $new_template;
			} else {
				$new_template = $this->new_template( 'archive' );


				if ( file_exists( $new_template ) )
					$template = $new_template;
			}
		}


		return $template;
	}


	/**
	 * Try to load from the page template file from the plugin first
	 *
	 * @param $template
	 *
	 * @return string
	 * @since    LCT 7.56
	 * @verified 2017.07.31
	 */
	function page_template( $template ) {
		if ( ! $this->file_in_active_theme( $template ) ) {
			global $post;

			$file_name = get_post_meta( $post->ID, '_wp_page_template', true );


			//let other plugins go first
			$new_template = apply_filters( 'lct/page_template', null, $template, $file_name );


			if ( $new_template ) {
				$template = $new_template;
			} else {
				if ( $file_name ) {
					$new_template = lct_get_path( $this->template_dir . $file_name . '.php' );


					if ( file_exists( $new_template ) )
						$template = $new_template;
				}
			}
		}


		return $template;
	}


	/**
	 * Try to load from the single template file from the plugin first
	 *
	 * @param $template
	 *
	 * @return string
	 * @since    LCT 7.56
	 * @verified 2017.07.31
	 */
	function single_template( $template ) {
		if ( ! $this->file_in_active_theme( $template ) ) {
			//let other plugins go first
			$new_template = apply_filters( 'lct/single_template', null, $template );


			if ( $new_template ) {
				$template = $new_template;
			} else {
				$new_template = $this->new_template( 'single' );


				if ( file_exists( $new_template ) )
					$template = $new_template;
			}
		}


		return $template;
	}


	/**
	 * Try to load from the taxonomy template file from the plugin first
	 *
	 * @param $template
	 *
	 * @return string
	 * @since    LCT 7.60
	 * @verified 2017.07.31
	 */
	function taxonomy_template( $template ) {
		if ( ! $this->file_in_active_theme( $template ) ) {
			$term = get_queried_object();


			if ( ! empty( $term->slug ) ) {
				//let other plugins go first
				$new_template = apply_filters( 'lct/taxonomy_template', null, $template, $term );


				if ( $new_template ) {
					$template = $new_template;
				} else {
					$new_template   = lct_get_path( $this->template_dir . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php' );
					$new_template_2 = lct_get_path( $this->template_dir . 'taxonomy-' . $term->taxonomy . '.php' );


					if ( file_exists( $new_template ) )
						$template = $new_template;
					else if ( file_exists( $new_template_2 ) )
						$template = $new_template_2;
				}
			}
		}


		return $template;
	}


	/**
	 * Try to load from the 404 template file from the plugin first
	 *
	 * @param $template
	 *
	 * @return string
	 * @since    LCT 7.56
	 * @verified 2017.07.31
	 */
	function a_404_template( $template ) {
		if ( ! $this->file_in_active_theme( $template ) ) {
			$file_name = '404';


			//let other plugins go first
			$new_template = apply_filters( 'lct/a_404_template', null, $template, $file_name );


			if ( $new_template ) {
				$template = $new_template;
			} else {
				if ( $file_name ) {
					$new_template = lct_get_path( $this->template_dir . $file_name . '.php' );


					if ( file_exists( $new_template ) )
						$template = $new_template;
				}
			}
		}


		return $template;
	}


	/**
	 * Try to load from the comments template file from the plugin first
	 *
	 * @param $template
	 *
	 * @return string
	 * @since    LCT 7.56
	 * @verified 2017.07.31
	 */
	function comments_template( $template ) {
		if ( ! $this->file_in_active_theme( $template ) ) {
			//let other plugins go first
			$new_template = apply_filters( 'lct/comments_template', null, $template );


			if ( $new_template ) {
				$template = $new_template;
			} else {
				$new_template = lct_get_path( $this->template_dir . 'comments-default.php' );


				if ( file_exists( $new_template ) )
					$template = $new_template;
			}
		}


		return $template;
	}


	/**
	 * Check our template folder to see if a template exists
	 *
	 * @param $template
	 * @param $template_name
	 *
	 * @unused   param $template_path
	 * @return string
	 * @since    LCT 7.36
	 * @verified 2017.08.18
	 */
	function wc_locate_template( $template, $template_name ) {
		if ( ! $this->file_in_active_theme( $template ) ) {
			//let other plugins go first
			$new_template = apply_filters( 'lct/wc_locate_template', null, $template, $template_name );


			if ( $new_template ) {
				$template = $new_template;
			} else {
				$new_template = lct_get_path( 'templates/wc/' . $template_name );


				if ( file_exists( $new_template ) )
					$template = $new_template;
			}
		}


		return $template;
	}


	/** @noinspection PhpInconsistentReturnPointsInspection */
	/**
	 * Route template files to this plugin unless they are overridden in the template
	 *
	 * @param      $template_path
	 * @param bool $return_template
	 *
	 * @return string
	 * @since    LCT 7.56
	 * @verified 2017.07.31
	 */
	function template_chooser( $template_path, $return_template = false ) {
		//let other plugins go first
		$located = apply_filters( 'lct/template_chooser', null, $template_path, $return_template );


		if ( ! $located ) {
			$dir_n_template         = $this->template_dir . $template_path;
			$located_dir_n_template = locate_template( $dir_n_template );
			$located                = locate_template( $template_path );


			if (
				! $this->file_in_active_theme( $located_dir_n_template ) &&
				! $this->file_in_active_theme( $located )
			) {
				$dir_n_template = lct_get_path( $dir_n_template );


				if ( file_exists( $dir_n_template ) ) {
					if ( ! $return_template )
						load_template( $dir_n_template );

					$located = $dir_n_template;


				} else if ( $located_dir_n_template ) {
					if ( ! $return_template )
						load_template( $located_dir_n_template );

					$located = $located_dir_n_template;


				} else if ( $located ) {
					if ( ! $return_template )
						load_template( $located );
				}


			} else {
				if ( $located_dir_n_template ) {
					if ( ! $return_template )
						load_template( $located_dir_n_template );

					$located = $located_dir_n_template;


				} else if ( $located ) {
					if ( ! $return_template )
						load_template( $located );
				}
			}
		}


		if ( $return_template )
			return $located;
	}


	/**
	 * [get_template_part]
	 * Get a template part using a shortcode
	 *
	 * @att      string slug
	 * @att      string name null
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    LCT 7.62
	 * @verified 2017.07.31
	 */
	function get_template_part( $a ) {
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'    => '',
				'slug' => '',
				'name' => null,
			],
			$a
		);


		if ( $a['slug'] ) {
			ob_start();


			get_template_part( $a['slug'], $a['name'] );


			$a['r'] = ob_get_clean();
		}


		return $a['r'];
	}


	/**
	 * [lct_get_template_part]
	 * Get a template part using a shortcode
	 *
	 * @att      string template_path
	 *
	 * @param $a
	 *
	 * @return string
	 * @since    LCT 7.62
	 * @verified 2017.07.31
	 */
	function this_plugin_get_template_part( $a ) {
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'             => '',
				'template_path' => '',
			],
			$a
		);


		if ( $a['template_path'] ) {
			ob_start();


			$this->template_chooser( $a['template_path'] . '.php' );


			$a['r'] = ob_get_clean();
		}


		return $a['r'];
	}
}


/**
 * alias of  lct_admin_template_router()->template_chooser()
 *
 * @param      $template_path
 * @param null $template_piece
 *
 * @since    LCT 7.56
 * @verified 2017.07.31
 */
function lct_template_part( $template_path, $template_piece = null ) {
	$class = new lct_admin_template_router( lct_load_class_default_args() );


	if ( $template_piece )
		$template_path .= '-' . $template_piece;


	$class->template_chooser( $template_path . '.php' );
}


/**
 * alias of  lct_admin_template_router()->template_chooser()
 *
 * @param      $template_path
 * @param null $template_piece
 *
 * @return string
 * @since    LCT 7.56
 * @verified 2017.07.31
 */
function lct_get_template_part( $template_path, $template_piece = null ) {
	$class = new lct_admin_template_router( lct_load_class_default_args() );


	if ( $template_piece )
		$template_path .= '-' . $template_piece;


	return $class->template_chooser( $template_path . '.php', true );
}
