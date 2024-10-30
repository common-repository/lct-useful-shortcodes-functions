<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2016.12.09
 */
class lct_post_types
{
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


		//Setup WordPress action and filter hooks
		$this->load_hooks();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    7.51
	 * @verified 2016.12.20
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
		 * functions
		 */
		$this->set_all_cnst();


		/**
		 * actions
		 */
		add_action( 'init', [ $this, 'register_post_types' ], 1 );


		if ( lct_frontend() ) {
			add_filter( 'wp_get_nav_menu_items', [ $this, 'archive_menu_filter' ], 10, 3 );
		}


		if ( lct_wp_admin_non_ajax() ) {
			add_action( 'admin_head-nav-menus.php', [ $this, 'inject_archives_menu_meta_box' ] );
		}
	}


	/**
	 * Store our constants
	 *
	 * @since    7.27
	 * @verified 2018.08.30
	 */
	function set_all_cnst()
	{
		/**
		 * post_types
		 */
		/**
		 * org
		 */
		if ( lct_plugin_active( 'acf' ) ) {
			lct_update_setting( 'use_org', lct_acf_get_option_raw( 'use_lct_org' ) );

			if ( lct_get_setting( 'use_org' ) ) {
				lct_append_setting( 'post_types', 'lct_org' );
				lct_append_setting( 'post_types_monitored', 'lct_org' );
			}
		}


		/**
		 * team
		 */
		if ( lct_plugin_active( 'acf' ) ) {
			lct_update_setting( 'use_team', lct_acf_get_option_raw( 'use_' . zxzu( 'team' ) ) );

			if ( lct_get_setting( 'use_team' ) ) {
				set_cnst( 'team', zxzu( 'team' ) );

				lct_append_setting( 'post_types', get_cnst( 'team' ) );
				lct_append_setting( 'post_types_monitored', get_cnst( 'team' ) );
			}
		}


		/**
		 * testimony
		 */
		if ( lct_plugin_active( 'acf' ) ) {
			lct_update_setting( 'use_testimony', lct_acf_get_option_raw( 'use_' . zxzu( 'testimony' ) ) );

			if ( lct_get_setting( 'use_testimony' ) ) {
				set_cnst( 'testimony', zxzu( 'testimony' ) );

				lct_append_setting( 'post_types', get_cnst( 'testimony' ) );
				lct_append_setting( 'post_types_monitored', get_cnst( 'testimony' ) );
			}
		}


		/**
		 * comment_types
		 */
		lct_append_setting( 'comment_types', 'lct_audit' );
		lct_append_setting( 'comment_types_monitored', 'lct_audit' );
	}


	/**
	 * Register all our awesome post_types
	 *
	 * @since    0.0
	 * @verified 2016.11.04
	 */
	function register_post_types()
	{
		if ( lct_get_setting( 'use_org' ) ) {
			$this->create_org();
		}

		if ( lct_get_setting( 'use_team' ) ) {
			$this->create_team();
		}

		if ( lct_get_setting( 'use_testimony' ) ) {
			$this->create_testimony();
		}
	}


	/**
	 * Register
	 *
	 * @since    0.0
	 * @verified 2016.11.04
	 */
	function create_org()
	{
		$slug      = 'organization';
		$post_type = 'lct_org';


		if ( post_type_exists( $post_type ) ) {
			return;
		}


		$labels = [];
		$labels = $this->default_labels( $labels, $slug );


		$args = [
			'show_in_nav_menus' => false,
			'supports'          => [ 'title', 'author', 'thumbnail', 'comments', 'page-attributes' ]
		];
		$args = $this->default_args( $args, $slug, $labels );


		register_post_type( $post_type, $args );
	}


	/**
	 * Register
	 *
	 * @since    7.56
	 * @verified 2018.08.30
	 */
	function create_team()
	{
		if (
			lct_acf_get_option_raw( 'use_' . zxzu( 'team_slug' ) )
			&& ( $team_slug = lct_acf_get_option_raw( zxzu( 'team_slug' ) ) )
		) {
			$slug = $team_slug;
		} else {
			$slug = 'team';
		}


		$post_type = get_cnst( 'team' );
		$lowercase = 'team member';


		if ( post_type_exists( $post_type ) ) {
			return;
		}


		$labels = [];
		$labels = $this->default_labels( $labels, $lowercase );


		$args = [ 'supports' => [ 'title', 'editor', 'author', 'thumbnail', 'comments', 'page-attributes' ] ];
		$args = $this->default_args( $args, $slug, $labels );


		register_post_type( $post_type, $args );
	}


	/**
	 * Register
	 *
	 * @since    7.56
	 * @verified 2018.08.30
	 */
	function create_testimony()
	{
		if (
			lct_acf_get_option_raw( 'use_' . zxzu( 'testimony_slug' ) )
			&& ( $testimony_slug = lct_acf_get_option_raw( zxzu( 'testimony_slug' ) ) )
		) {
			$slug = $testimony_slug;
		} else {
			$slug = 'testimony';
		}


		$post_type = get_cnst( 'testimony' );
		$lowercase = 'testimony';


		if ( post_type_exists( $post_type ) ) {
			return;
		}


		$labels = [];
		$labels = $this->default_labels( $labels, $lowercase, 'ies' );


		$args = [ 'supports' => [ 'title', 'editor', 'author', 'thumbnail', 'comments', 'page-attributes' ] ];
		$args = $this->default_args( $args, $slug, $labels );


		register_post_type( $post_type, $args );
	}


	/**
	 * Get all the label data that we need to properly register a post_type
	 *
	 * @param array  $custom_labels
	 * @param null   $lowercase
	 * @param string $s
	 *
	 * @return array
	 * @since    0.0
	 * @verified 2018.02.13
	 */
	function default_labels( $custom_labels = [], $lowercase = null, $s = 's' )
	{
		return lct_post_type_default_labels( $custom_labels, $lowercase, $s );
	}


	/**
	 * Get all the data that we need to properly register a post_type
	 *
	 * @param array $custom_args
	 * @param null  $slug
	 * @param null  $labels
	 *
	 * @return array
	 * @since    0.0
	 * @verified 2018.02.13
	 */
	function default_args( $custom_args = [], $slug = null, $labels = null )
	{
		return lct_post_type_default_args( $custom_args, $slug, $labels );
	}


	/**
	 * Get all the label data that we need to properly register a post_type status
	 *
	 * @param array  $custom_labels
	 * @param null   $lowercase
	 * @param string $s
	 *
	 * @return array
	 * @since    0.0
	 * @verified 2016.11.04
	 */
	function status_default_labels( $custom_labels = [], $lowercase = null, $s = 's' )
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
			'capital'    => $capital,
			'lowercase'  => $lowercase,
			'capitals'   => $capitals,
			'lowercases' => $lowercases,
		];
		$labels = wp_parse_args( $custom_labels, $labels );


		return $labels;
	}


	/**
	 * Get all the data that we need to properly register a post_type status
	 *
	 * @param array $custom_args
	 * @param null  $labels
	 *
	 * @return array
	 * @since    0.0
	 * @verified 2018.08.23
	 */
	function status_default_args( $custom_args = [], $labels = null )
	{
		$args = [
			'label'                     => $labels['capital'],
			'label_count'               => [
				0          => $labels['capital'] . ' (%s)',
				1          => $labels['capitals'] . ' (%s)',
				'singular' => $labels['capital'] . ' (%s)',
				'plural'   => $labels['capitals'] . ' (%s)',
				'context'  => null,
				'domain'   => null,
			],
			'exclude_from_search'       => null,
			'_builtin'                  => false,
			'public'                    => null,
			'internal'                  => null,
			'protected'                 => true,
			'private'                   => null,
			'show_in_admin_status_list' => true,
			'show_in_admin_all_list'    => true,
		];
		$args = wp_parse_args( $custom_args, $args );


		return $args;
	}


	/**
	 * Add the menu metabox
	 *
	 * @since    0.0
	 * @verified 2016.11.04
	 */
	function inject_archives_menu_meta_box()
	{
		add_meta_box( 'add-' . zxza() . '-archives', 'Archive Pages', [ $this, 'wp_nav_menu_archives_meta_box' ], 'nav-menus', 'side' );
	}


	/**
	 * Render custom post_type archives metabox
	 *
	 * @since    0.0
	 * @verified 2017.04.28
	 */
	function wp_nav_menu_archives_meta_box()
	{
		/**
		 * get custom post types with archive support
		 */
		$args       = [
			'_builtin'    => false,
			'has_archive' => true,
		];
		$post_types = get_post_types( $args, 'object' );


		/**
		 * hydrate the necessary object properties for the walker
		 */
		foreach ( $post_types as &$post_type ) {
			$post_type->classes   = [];
			$post_type->type      = $post_type->name;
			$post_type->object_id = $post_type->name;
			$post_type->title     = $post_type->labels->name . ' Archive';
			$post_type->object    = zxza( '-archive' );
		}


		$walker = new Walker_Nav_Menu_Checklist( [] );
		?>


		<div id="<?php echo zxza(); ?>-archive" class="posttypediv">
			<div id="tabs-panel-<?php echo zxza(); ?>-archive" class="tabs-panel tabs-panel-active">
				<ul id="ctp-archive-checklist" class="categorychecklist form-no-clear">
					<?php
					echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $post_types ), 0, (object) [ 'walker' => $walker ] );
					?>
				</ul>
			</div>
			<!-- /.tabs-panel -->
		</div>
		<p class="button-controls">
			<span class="add-to-menu">
				<img class="waiting" src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" alt=""/>
				<input type="submit"
				       class="button-secondary submit-add-to-menu" value="<?php esc_attr_e( 'Add to Menu' ); ?>"
				       name="add-ctp-archive-menu-item"
				       id="submit-<?php echo zxza(); ?>-archive"/>
			</span>
		</p>
		<?php
	}


	/**
	 * Take care of the URLs
	 *
	 * @param $items
	 * @param $menu
	 * @param $args
	 *
	 * @return mixed
	 * @since    0.0
	 * @verified 2016.11.04
	 */
	function archive_menu_filter(
		$items,
		/** @noinspection PhpUnusedParameterInspection */
		$menu,
		/** @noinspection PhpUnusedParameterInspection */
		$args
	) {
		/* alter the URL for archive objects */
		foreach ( $items as &$item ) {
			if ( $item->object != zxza( '-archive' ) ) {
				continue;
			}
			$item->url = get_post_type_archive_link( $item->type );

			/* set current */
			if ( get_query_var( 'post_type' ) == $item->type ) {
				$item->classes [] = 'current-menu-item';
				$item->current    = true;
			}
		}


		return $items;
	}
}
