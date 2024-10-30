<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.04.20
 */
class lct_features_shortcodes_post_content
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
		//TODO: cs - Add these to the shortcode creator - 6/20/2015 11:53 AM
		add_shortcode( zxzu( 'post_content' ), [ $this, 'add_shortcode' ] );


		if (
			( $tmp = zxza( 'pcs_action' ) )
			&& ! empty( $_GET[ zxza( 'pcs_action' ) ] )
		) {
			add_action( 'init', [ $this, 'request_handler' ] );
		}


		//if ( lct_frontend() ) {}


		if ( lct_wp_admin_all() ) {
			if ( isset( $_GET['post'] ) ) {
				add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

				//add_action( 'admin_init', [ $this, 'add_meta_box' ] );
			}
		}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * esc_html
	 *
	 * @param $a
	 *
	 * @return bool|mixed|string
	 */
	function add_shortcode( $a )
	{
		foreach ( $a as $k => $v ) {
			$a[ $k ] = do_shortcode( str_replace( [ "{", "}" ], [ "[", "]" ], $v ) );
		}

		extract(
			shortcode_atts(
				[
					'id'       => null,
					'esc_html' => null,
				],
				$a
			)
		);


		if ( empty( $id ) ) {
			return false;
		}


		if ( ! empty( $esc_html ) ) {
			$esc_html = esc_attr( $esc_html );
		} else {
			$esc_html = 'true';
		}


		$post_content = get_post( $id );
		$content      = $post_content->post_content;


		if ( $esc_html == 'true' ) {
			$content = apply_filters( 'the_content', $content );
		}


		return $content;
	}


	function request_handler()
	{
		$this->id_lookup();
	}


	function id_lookup()
	{
		global $wpdb;

		$title = stripslashes( $_GET['post_title'] );
		$wild  = '%' . esc_sql( $title ) . '%';


		if (
			strpos( $title, 'http:' ) !== false
			|| strpos( $title, 'https:' ) !== false
			|| strpos( $title, '//' ) !== false
		) {
			$output = sprintf( '<ul><li class=\'%s\'>%s</li></ul>', $title, $title );


			echo $output;
			exit;
		}


		$posts = $wpdb->get_results( "
			SELECT *
			FROM $wpdb->posts
			WHERE (
				post_title LIKE '$wild'
				OR post_name LIKE '$wild'
				OR ID = '$title'
			)
			AND post_status = 'publish'
			AND post_type NOT IN ( 'nav_menu_item', 'revision', 'attachment' )
			ORDER BY post_title
			LIMIT 25
		" );


		if ( count( $posts ) ) {
			$output = '<ul>';


			foreach ( $posts as $post ) {
				$title = esc_html( $post->post_title );


				if ( $post->post_type == 'page' ) {
					$extra_info = esc_html( '/' . lct_get_the_slug( $post->ID ) );
					$extra_info = " <strong>({$extra_info})</strong>";
				} else {
					$post_type  = esc_html( $post->post_type );
					$extra_info = " <strong>({$post_type})</strong>";
				}

				$output .= sprintf( "<li class='%s'>%s | ID=%s%s</li>", $post->ID, $title, $post->ID, $extra_info );
			}


			$output .= '</ul>';
		} else {
			$output = '<ul><li>Sorry, no matches.</li></ul>';
		}


		echo $output;
		exit;
	}


	function admin_enqueue_scripts()
	{
		wp_enqueue_script( zxza( 'pcs_admin_js' ), lct_get_root_url( 'assets/wp-admin/js/shortcode_post_content.min.js' ), [ 'jquery' ], lct_get_setting( 'version' ) );

		wp_enqueue_style( zxza( 'pcs_admin_css' ), lct_get_root_url( 'assets/wp-admin/css/shortcode_post_content.min.css' ), [], lct_get_setting( 'version' ) );
	}


	function pcs_meta_box()
	{ ?>
		<fieldset>
			<p style="margin: 0;text-align: center;">
				<strong>To start:</strong><br/>Search for a page that you would like to pull content from.</p>

			<input type="text" name="<?php echo zxza( 'pcs_id' ); ?>" id="<?php echo zxza( 'pcs_id' ); ?>" placeholder="Search by Page/Post Title..." autocomplete="off"/>

			<div class="live_search_results"></div>

			<div id="<?php echo zxza( 'pcs_extras' ); ?>" style="display: none;">
				<p style="margin: 0;text-align: center;">
					<strong>Now:</strong><br/>Add any of the optional features.<br/>And click Send To Content Area.
				</p>

				<p style="margin: 0;text-align: center;"><strong>Advanced Attributes:</strong><br/>esc_html</p>

				<button class="button button-primary button-large" name="<?php echo zxza( 'pcs_editor_button' ); ?>" id="<?php echo zxza( 'pcs_editor_button' ); ?>" style="margin: 0 auto; display: block;">Send To Content Area</button>
			</div>
		</fieldset>
	<?php }


	function add_meta_box()
	{
		add_meta_box( zxza( 'pcs_meta_box' ), 'Other Post Content Grabber', [ $this, 'pcs_meta_box' ], 'post', 'side' );
		add_meta_box( zxza( 'pcs_meta_box' ), 'Other Post Content Grabber', [ $this, 'pcs_meta_box' ], 'page', 'side' );


		$args       = [
			'public'   => true,
			'_builtin' => false
		];
		$post_types = get_post_types( $args );


		if ( ! empty( $post_types ) ) {
			foreach ( $post_types as $post_type ) {
				add_meta_box( zxza( 'pcs_meta_box' ), 'Other Post Content Grabber', [ $this, 'pcs_meta_box' ], $post_type, 'side' );
			}
		}
	}
}
