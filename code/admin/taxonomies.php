<?php
/** @noinspection PhpMissingFieldTypeInspection */

//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'lct_taxonomies' ) ) :


	/**
	 * @verified 2016.12.09
	 */
	class lct_taxonomies
	{
		public $post_types;
		/**
		 * @var array|mixed
		 */
		public $args;
		/**
		 * @var lct
		 */
		public lct $zxzp;


		/**
		 * Start up the class
		 *
		 * @param $args
		 *
		 * @verified 2016.12.09
		 */
		function __construct( $args = [] )
		{
			//Store $args
			$this->args = $args;


			//Store parent class, maybe
			if ( ! empty( $this->args['load_parent'] ) ) {
				$this->zxzp = lct();
			}


			$this->post_types = new lct_post_types( lct_load_class_default_args() );


			//Setup WordPress action and filter hooks
			$this->load_hooks();
		}


		/**
		 * Setup WordPress action and filter hooks
		 *
		 * @since    7.51
		 * @verified 2016.12.09
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
			$this->set_all_cnst();


			/**
			 * Actions
			 */
			add_action( 'init', [ $this, 'register_taxonomies' ], 1 );

			add_action( 'admin_footer-edit-tags.php', [ $this, 'disable_status_slug_editing' ] );

			add_action( 'admin_footer-term.php', [ $this, 'disable_status_slug_editing_on_term' ] );

			add_action( 'created_term', [ $this, 'clear_register_post_status_cache' ], 10, 3 );
			add_action( 'edited_term', [ $this, 'clear_register_post_status_cache' ], 10, 3 );


			/**
			 * Filters
			 */
			add_filter( 'get_post_status', [ $this, 'acf_post_status_check' ], 10, 2 );
		}


		/**
		 * Store our constants
		 *
		 * @since    7.27
		 * @verified 2024.08.27
		 */
		function set_all_cnst()
		{
			! lct_get_setting( 'use_org' ) || lct_append_setting( 'taxonomies', 'lct_org_status' );
		}


		/**
		 * Register all our awesome {taxonomies}s with WordPress
		 *
		 * @since    0.0
		 * @verified 2016.12.09
		 */
		function register_taxonomies()
		{
			if ( lct_get_setting( 'use_org' ) ) {
				$this->create_org_status();
			}
		}


		/**
		 * Register the taxonomy
		 *
		 * @since    7.27
		 * @verified 2016.11.04
		 */
		function create_org_status()
		{
			$slug      = 'org_status';
			$taxonomy  = 'lct_org_status';
			$post_type = [ 'lct_org' ];
			$lowercase = 'organization status';


			if ( taxonomy_exists( $taxonomy ) ) {
				return;
			}


			$labels = [];
			$labels = $this->default_labels( $labels, $lowercase, 'es' );


			$args = [];
			$args = $this->default_args( $args, $slug, $labels );


			register_taxonomy( $taxonomy, $post_type, $args );


			/**
			 * @date     0.0
			 * @since    0.0
			 * @verified 2021.08.27
			 */
			do_action( 'lct_after_register_taxonomy', $taxonomy, $post_type, $this );
		}


		/**
		 * Get all the label data that we need to properly register a taxonomy
		 *
		 * @param array  $custom_labels
		 * @param null   $lowercase
		 * @param string $s
		 *
		 * @return array
		 * @since    0.0
		 * @verified 2018.08.23
		 */
		function default_labels( $custom_labels = [], $lowercase = null, $s = 's' )
		{
			$lowercase = str_replace( '_', ' ', $lowercase );
			$capital   = ucwords( $lowercase );
			$lowercase = strtolower( $lowercase );

			if ( $s == 'ies' ) {
				$capitals   = rtrim( $capital, 'y' ) . $s;
				$lowercases = rtrim( $lowercase, 'y' ) . $s;
			} else {
				$capitals   = $capital . $s;
				$lowercases = $lowercase . $s;
			}


			$labels = [
				'name'                       => $capitals,
				'singular_name'              => $capital,
				'menu_name'                  => $capitals,
				'all_items'                  => "All {$capitals}",
				'edit_item'                  => "Edit {$capital}",
				'view_item'                  => "View {$capital}",
				'update_item'                => "Update {$capital}",
				'add_new_item'               => "Add New {$capital}",
				'new_item_name'              => "New {$capital} Name",
				'parent_item'                => "Parent {$capital}",
				'parent_item_colon'          => "Parent {$capitals}:",
				'search_items'               => "Search {$capitals}",
				'popular_items'              => "Popular {$capitals}",
				'separate_items_with_commas' => "Separate {$lowercases} with commas",
				'add_or_remove_items'        => "Add or remove {$lowercases}",
				'choose_from_most_used'      => "Choose from the most used {$lowercases}",
				'not_found'                  => "No {$lowercases} found."
			];


			return wp_parse_args( $custom_labels, $labels );
		}


		/**
		 * Get all the data that we need to properly register a taxonomy
		 *
		 * @param array $custom_args
		 * @param null  $slug
		 * @param null  $labels
		 *
		 * @return array
		 * @since    0.0
		 * @verified 2024.03.13
		 */
		function default_args( $custom_args = [], $slug = null, $labels = null )
		{
			$args = [
				//MISSING - 'label' => esc_html__( 'label', 'your-textdomain' ),
				'labels'              => $labels,
				'public'              => true,
				'publicly_queryable'  => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
				'show_in_rest'        => true,
				'rest_base'           => '',
				'show_tagcloud'       => true,
				'show_in_quick_edit'  => true,
				'meta_box_cb'         => null,
				'show_admin_column'   => false,
				'description'         => '',
				'hierarchical'        => false,
				//'update_count_callback' => null,
				//'query_var' => true,
				'rewrite'             => [ 'slug' => $slug, 'with_front' => true ],
				//'capabilities' => null,
				'exclude_from_search' => false
				//'sort' => null,
			];
			$args = apply_filters( 'lct_taxonomies_default_args', $args );


			return wp_parse_args( $custom_args, $args );
		}


		/**
		 * Do cool things after we register a custom taxonomy
		 *
		 * @param $taxonomy
		 * @param $post_type
		 * @param $class
		 *
		 * @since    0.0
		 * @verified 2016.11.04
		 */
		function after_register_taxonomy( $taxonomy, $post_type, $class ) {}


		/**
		 * Add custom statuses to a post_type from a taxonomy designed for statuses
		 * To use this call: add_action( 'lct_after_register_taxonomy', [ $this->lct_taxonomies, 'register_post_status' ], 10, 3 );
		 * BEFORE: action( 'lct_after_register_taxonomy' );
		 *
		 * @param $taxonomy
		 * @param $post_types
		 *
		 * @unused   param $class
		 * @since    0.0
		 * @verified 2024.03.13
		 */
		function register_post_status( $taxonomy, $post_types )
		{
			if (
				empty( $post_types )
				|| //If a post_type(s) is not set
				! lct_plugin_active( 'acf' )
				|| //If ACF is not installed
				! lct_is_status_taxonomy( $taxonomy ) //If the taxonomy is not a status taxonomy
			) {
				return;
			}


			$update_terms = false;


			if ( ! ( $terms_to_register = lct_get_option( $this->cache_key( $taxonomy ), [] ) ) ) {
				$tax_args = [
					'taxonomy'          => $taxonomy,
					'hide_empty'        => false,
					'hierarchical'      => true,
					'lct:::tax_disable' => true,
				];
				$terms    = get_terms( $tax_args );


				$update_terms = true;


				if ( lct_is_wp_error( $terms ) ) {
					return;
				}


				foreach ( $terms as $status ) {
					$status_meta          = lct_get_all_term_meta( $status, true );
					$lowercase            = strtolower( lct_make_status_name( $status, $status_meta ) );
					$slug                 = lct_make_status_slug( $status );
					$labels               = [];
					$labels               = $this->post_types->status_default_labels( $labels, $lowercase );
					$existing_post_status = get_post_status_object( $slug );


					if ( ! empty( $existing_post_status ) ) {
						continue;
					}


					if ( ! is_array( $post_types ) ) {
						$post_types = [ $post_types ];
					}


					$args = [ 'post_types' => $post_types ];


					/**
					 * Unused yet
					 * [exclude_from_search] =>
					 * [show_in_admin_status_list] => 1
					 */
					if ( $status_meta[ get_cnst( 'a_c_f_tax_status' ) ] === false ) {
						$args['public']                 = false;
						$args['protected']              = false;
						$args['show_in_admin_all_list'] = false;
					} elseif ( $status_meta[ get_cnst( 'a_c_f_tax_public' ) ] === true ) {
						$args['public']    = true;
						$args['protected'] = false;
					} else {
						$args['public']             = false;
						$args['protected']          = false;
						$args['private']            = true;
						$args['publicly_queryable'] = true;


						$args['show_in_admin_all_list'] = false;
						if ( $status_meta[ get_cnst( 'a_c_f_tax_show_in_admin_all_list' ) ] === true ) {
							$args['show_in_admin_all_list'] = true;
						}
					}


					$args = $this->post_types->status_default_args( $args, $labels );


					$terms_to_register[] = [
						'slug'       => $slug,
						'args'       => $args,
						'post_types' => $post_types,
					];
				}
			}


			if ( ! empty( $terms_to_register ) ) {
				$post_types = [];
				foreach ( $terms_to_register as $term ) {
					register_post_status( $term['slug'], $term['args'] );


					$post_types = array_merge( $post_types, $term['post_types'] );
				}


				$existing_post_types = lct_get_setting( 'post_types_w_statuses', [] );


				lct_update_setting( 'post_types_w_statuses', array_merge( $existing_post_types, array_unique( $post_types ) ) );
			}


			if ( $update_terms ) {
				lct_update_option( $this->cache_key( $taxonomy ), $terms_to_register );
			}


			global $pagenow;


			if (
				lct_wp_admin_non_ajax()
				&& in_array( $pagenow, [ 'post.php', 'post-new.php' ] )
				&& lct_get_setting( 'post_types_w_statuses', [] )
			) {
				add_action( "admin_footer-{$pagenow}", [ $this, 'extend_submitdiv_post_status' ] );
			}


			if (
				lct_wp_admin_non_ajax()
				&& $pagenow === 'edit.php'
				&& ! empty( $_GET['post_type'] )
				&& ( $post_types_w_statuses = lct_get_setting( 'post_types_w_statuses', [] ) )
				&& in_array( $_GET['post_type'], $post_types_w_statuses )
			) {
				add_action( "admin_footer-{$pagenow}", [ $this, 'extend_quick_edit_post_status' ] );
			}
		}


		/**
		 * Get the cache key
		 *
		 * @param string $taxonomy
		 * @param bool   $full_prefix
		 *
		 * @return string
		 * @since    2019.3
		 * @verified 2021.03.22
		 */
		function cache_key( $taxonomy, $full_prefix = false )
		{
			$dev = '';
			if ( lct_is_dev() ) {
				$dev = 'dev_';
			}


			$prefix = 'cache_' . $dev . 'register_post_status_';
			if ( $full_prefix ) {
				$prefix = zxzu( $prefix );
			}


			if ( $taxonomy === 'prefix' ) {
				return $prefix;
			}


			return $prefix . $taxonomy;
		}


		/**
		 * Clear taxonomy cached when a term is saved
		 *
		 * @param int    $term_id
		 * @param int    $tt_id
		 * @param string $taxonomy
		 *
		 * @since    2019.3
		 * @verified 2019.02.18
		 */
		function clear_register_post_status_cache(
			/** @noinspection PhpUnusedParameterInspection */
			$term_id,
			/** @noinspection PhpUnusedParameterInspection */
			$tt_id,
			$taxonomy
		) {
			lct_delete_option( $this->cache_key( $taxonomy ) );
		}


		/**
		 * Adds post status to the "submitdiv" (Publish) Meta Box and post type WP List Table screens
		 * Get all non-builtin post statuses and add them as an <option>
		 *
		 * @since    0.0
		 * @verified 2017.12.13
		 */
		function extend_submitdiv_post_status()
		{
			global $post_type;


			//Abort if we're on the wrong post type, but only if we have a restriction
			if (
				empty( $post_type )
				|| (
					( $post_types_w_statuses = lct_get_setting( 'post_types_w_statuses', [] ) )
					&& ! in_array( $post_type, $post_types_w_statuses )
				)
			) {
				return;
			}


			global $post, $wp_post_statuses;

			$options = [];
			$display = '';


			foreach ( $wp_post_statuses as $status ) {
				if (
					! $status->_builtin
					&& isset( $status->post_types )
					&& in_array( $post_type, $status->post_types )
				) {
					//Match against the current posts status
					$selected = selected( $post->post_status, $status->name, false );


					//If we one of our custom post status is selected, remember it
					if ( $selected ) {
						$display = $status->label;
					}


					//Make the options
					$options[] = sprintf( '<option %s value="%s">%s</option>', $selected, $status->name, $status->label );
				}
			}
			?>


			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					var appended = false;


					//Add the selected post status label to the "Status: [Name] (Edit)"
					<?php if ( ! empty( $display ) ) { ?>
					$( '#post-status-display' ).html( '<?php echo $display; ?>' );
					<?php } ?>


					//Add the options to the <select> element
					$( '.edit-post-status' ).on( 'click', function() {
						if( !appended ) {
							var select = $( '#post-status-select' ).find( 'select' );
							$( select ).append( "<?php echo esc_sql( lct_return( $options ) ); ?>" );
							appended = true;
						}
					} );
				} );
			</script>
		<?php }


		/**
		 * Adds post status to the "submitdiv" (Publish) Meta Box and post type WP List Table screens
		 * Get all non-builtin post statuses and add them as an <option>
		 *
		 * @since    2020.1
		 * @verified 2022.01.18
		 */
		function extend_quick_edit_post_status()
		{
			global $post_type, $post, $wp_post_statuses;

			$options = [];


			if ( lct_is_wp_error( $post ) ) {
				return;
			}


			foreach ( $wp_post_statuses as $status ) {
				if (
					! $status->_builtin
					&& isset( $status->post_types )
					&& in_array( $post_type, $status->post_types )
				) {
					//Match against the current posts status
					$selected = selected( $post->post_status, $status->name, false );


					//Make the options
					$options[] = sprintf( '<option %s value="%s">%s</option>', $selected, $status->name, $status->label );
				}
			}
			?>


			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					$( 'select[name="_status"]' ).append( "<?php echo esc_sql( lct_return( $options ) ); ?>" );
				} );
			</script>
		<?php }


		/**
		 * Don't allow slugs to be edited if they are for post_type statuses
		 *
		 * @since    2017.96
		 * @verified 2022.09.07
		 */
		function disable_status_slug_editing()
		{
			global $current_screen;


			if (
				! lct_plugin_active( 'afwp' )
				&& lct_is_status_taxonomy( $current_screen->taxonomy )
			) { //If the taxonomy is a status taxonomy ?>
				<style>
					.inline-edit-col .input-text-wrap input[name="slug"]{
						display: none !important;
					}
				</style>


				<script type="text/javascript">
					jQuery( document ).ready( function() {
						jQuery( '.inline-edit-col .input-text-wrap input[name="slug"]' ).each( function() {
							jQuery( this ).parent().append( '<span class="<?php echo zxzu( 'slug_message' ) ?>">(Can not edit slug)</span>' );
						} );


						jQuery( 'a.editinline' ).on( 'click', function( e ) {
							var lct_slug = jQuery( e.target ).closest( 'tr' ).find( 'td.slug' ).html();
							jQuery( '.inline-edit-col .input-text-wrap input[name="slug"]' ).parent().find( '.<?php echo zxzu( 'slug_message' ) ?>' ).html( lct_slug + ' (Can not edit slug)' );
						} );
					} );
				</script>
			<?php }
		}


		/**
		 * Don't allow slugs to be edited if they are for post_type statuses
		 *
		 * @since    2017.96
		 * @verified 2022.09.07
		 */
		function disable_status_slug_editing_on_term()
		{
			global $current_screen;


			if (
				! lct_plugin_active( 'afwp' )
				&& lct_is_status_taxonomy( $current_screen->taxonomy )
			) { //If the taxonomy is a status taxonomy ?>
				<style>
					.term-slug-wrap td input[name="slug"],
					.term-slug-wrap td .description{
						display: none !important;
					}
				</style>


				<script type="text/javascript">
					jQuery( document ).ready( function() {
						var lct_slug = jQuery( '.term-slug-wrap td input[name="slug"]' ).val();
						jQuery( '.term-slug-wrap td' ).append( '<span class="<?php echo zxzu( 'slug_message' ) ?>">' + lct_slug + ' (Can not edit slug)</span>' );
					} );
				</script>
			<?php }
		}


		/**
		 * Mark the post_status as 'publish' so that the results come back clean
		 *
		 * @param string  $post_status The post status.
		 * @param WP_Post $post        The post object.
		 *
		 * @return string
		 * @since    2020.3
		 * @verified 2020.01.21
		 */
		function acf_post_status_check( $post_status, $post )
		{
			if (
				( $bt = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 5 ) )
				&& isset( $bt[4]['function'] )
				&& $bt[4]['function'] === 'acf_get_post_title'
				&& ( $terms_to_register = lct_get_option( $this->cache_key( $post->post_type . '_status' ), [] ) )
				&& ( $terms_to_register = array_column( $terms_to_register, 'slug' ) )
				&& in_array( $post_status, $terms_to_register )
			) {
				$post_status = 'publish';
			}


			return $post_status;
		}
	}


	/**
	 * Instantiate.
	 */
	acf_new_instance( 'lct_taxonomies' );


endif; // class_exists check


/**
 * @return lct_taxonomies
 * @date     2021.04.30
 * @since    2021.4
 * @verified 2021.04.30
 */
function lct_taxonomies()
{
	/**
	 * @var lct_taxonomies $ins
	 */
	$ins = acf_get_instance( 'lct_taxonomies' );


	return $ins;
}
