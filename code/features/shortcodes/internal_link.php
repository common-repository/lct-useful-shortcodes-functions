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
class lct_features_shortcodes_internal_link
{
	public $il;
	public $il_full;


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

		$this->il      = 'internal_link';
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
		add_shortcode( 'link', [ $this, 'add_shortcode' ] );


		add_action( 'wp_ajax_' . $this->il_full . '_ajax', [ $this, 'ajax_handler' ] );
		add_action( 'wp_ajax_nopriv_' . $this->il_full . '_ajax', [ $this, 'ajax_handler' ] );


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
					'pleaseEnterAnID'     => 'Please enter a Page/Post Title...',
					'errorLoadingPreview' => 'Failed to load the preview for this Internal Link.',
				]
			]
		);


		wp_enqueue_script( $this->il_full . '_ajax', lct_get_root_url( 'assets/wp-admin/js/' . $this->il . '_ajax.min.js' ), [ 'jquery' ], lct_get_setting( 'version' ), true );
		wp_localize_script(
			$this->il_full . '_ajax',
			$this->il_full . '_ajax',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
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
	 * [link]
	 * //TODO: cs - Add these to the shortcode creator - 6/20/2015 11:53 AM
	 * selector_id
	 * query
	 * imagetext
	 * textimage
	 * esc_html
	 * url_only
	 * taxonomy
	 * ***
	 *
	 * @param $a
	 *
	 * @return bool|string
	 * @since    0.0
	 * @verified 2017.08.25
	 */
	function add_shortcode( $a )
	{
		//Process any nested shortcodes
		foreach ( $a as $k => $v ) {
			if (
				strpos( $v, '{{' ) === false
				&& strpos( $v, '}}' ) === false
			) {
				$a[ $k ] = do_shortcode( str_replace( [ "{", "}" ], [ "[", "]" ], $v ) );
			}
		}


		$link = '';
		$url  = '';

		isset( $a['id'] ) ? $id = $a['id'] : $id = '';
		isset( $a['text'] ) ? $text = $a['text'] : $text = '';
		isset( $a['selector_id'] ) ? $selector_id = ' id="' . esc_attr( $a['selector_id'] ) . '"' : $selector_id = '';
		isset( $a['class'] ) ? $class = ' class="' . esc_attr( $a['class'] ) . '"' : $class = '';
		isset( $a['alt'] ) ? $alt = $a['alt'] : $alt = '';
		isset( $a['title'] ) ? $title = ' title="' . esc_attr( $a['title'] ) . '"' : $title = '';
		isset( $a['rel'] ) ? $rel = ' rel="' . esc_attr( $a['rel'] ) . '"' : $rel = '';
		isset( $a['style'] ) ? $style = ' style="' . esc_attr( $a['style'] ) . '"' : $style = '';
		isset( $a['src'] ) ? $src = $a['src'] : $src = '';
		isset( $a['query'] ) ? $query = $a['query'] : $query = '';
		isset( $a['anchor'] ) ? $anchor = $a['anchor'] : $anchor = '';
		isset( $a['onclick'] ) ? $onclick = ' onclick="' . esc_attr( $a['onclick'] ) . '"' : $onclick = '';
		isset( $a['target'] ) ? $target = ' target="' . esc_attr( $a['target'] ) . '"' : $target = '';
		isset( $a['imagetext'] ) ? $imagetext = $a['imagetext'] : $imagetext = '';
		isset( $a['textimage'] ) ? $textimage = $a['textimage'] : $textimage = '';
		isset( $a['esc_html'] ) ? $esc_html = esc_attr( $a['esc_html'] ) : $esc_html = 'true';
		isset( $a['url_only'] ) ? $url_only = $a['url_only'] : $url_only = '';
		isset( $a['taxonomy'] ) ? $taxonomy = $a['taxonomy'] : $taxonomy = '';


		if ( empty( $id ) ) {
			return false;
		}


		if ( is_numeric( $id ) ) {
			if ( taxonomy_exists( $taxonomy ) ) {
				$term = get_term( $id, $taxonomy );

				if ( ! lct_is_wp_error( $term ) ) {
					$url = get_term_link( $term );

					if ( empty( $text ) ) {
						$text = $term->name;
					}
				}
			} else {
				$url = get_permalink( $id );

				if ( empty( $text ) ) {
					$text = get_the_title( $id );
				}
			}
		} else {
			$url = $id;


			//See if we can find this in the DB, if we do let's update the shortcode with the ID
			$ids_actual_id = get_page_by_path( $id );


			if ( ! lct_is_wp_error( $ids_actual_id ) ) {
				global $post;


				$post->post_content = str_replace( 'id="' . $id . '"', 'id="' . $ids_actual_id->ID . '"', $post->post_content );
				$post->post_content = str_replace( 'id=\'' . $id . '\'', 'id=\'' . $ids_actual_id->ID . '\'', $post->post_content );
				$post->post_content = str_replace( 'id=' . $id, 'id=' . $ids_actual_id->ID, $post->post_content );

				wp_update_post( $post );
			}
		}


		if ( ! empty( $text ) ) {
			$text = html_entity_decode( $text );
		}


		if (
			! empty( $text )
			&& $esc_html == 'true'
		) {
			$text = esc_html( $text );
		}


		if ( empty( $alt ) ) {
			$alt = esc_html( $text );
			$alt = " alt=\"{$alt}\"";
		} else {
			$alt = esc_attr( $alt );
			$alt = " alt=\"{$alt}\"";
		}


		if (
			! empty( $src )
			&& $esc_html == 'true'
		) {
			$src = esc_html( $src );
		}


		if ( ! empty( $query ) ) {
			$query = "?{$query}";

			$url .= $query;
		}


		if ( ! empty( $anchor ) ) {
			$anchor = "#{$anchor}";

			$url .= $anchor;
		}


		if (
			$url == false
			|| strpos( $id, 'PageRemoved_' ) !== false
		) {
			if ( is_user_logged_in() ) {
				$id = str_replace( 'PageRemoved_', '', $id );

				if ( ! empty( $text ) ) {
					$text = sprintf( '<span style="text-decoration: line-through !important;">%s</span>', $text );
				}

				$url  = sprintf( '#PageRemoved_%s', $id );
				$text .= '<span style="font-size: 150%;color: #D93F69 !important;">(This page has been removed)</span>';
			} else {
				$url = '/';
			}
		}


		if ( $url_only ) {
			return $url;
		}


		$href = "href=\"{$url}\"";


		if ( ! empty( $src ) ) {
			if (
				$imagetext
				|| $textimage
			) {
				if ( $imagetext ) {
					$link = sprintf( '<a %s%s%s%s%s%s%s%s><img src="%s" %s />%s</a>', $href, $class, $selector_id, $title, $rel, $style, $onclick, $target, $src, $alt, $text );
				} elseif ( $textimage ) {
					$link = sprintf( '<a %s%s%s%s%s%s%s%s>%s<img src="%s" %s /></a>', $href, $class, $selector_id, $title, $rel, $style, $onclick, $target, $text, $src, $alt );
				}

			} else {
				$link = sprintf( '<a %s%s%s%s%s%s%s%s><img src="%s" %s /></a>', $href, $class, $selector_id, $title, $rel, $style, $onclick, $target, $src, $alt );
			}

		} else {
			$link = sprintf( '<a %s%s%s%s%s%s%s%s>%s</a>', $href, $class, $selector_id, $title, $rel, $style, $onclick, $target, $text );
		}


		return $link;
	}


	/**
	 * Action target that displays the popup to insert an Internal Link to a post/page
	 *
	 * @return array
	 */
	function get_shortcodes()
	{
		$atts = [
			[
				'label'       => 'Search by Page/Post Title...',
				'attr'        => 'id',
				'type'        => 'text',
				'section'     => 'required',
				'description' => 'To start: Search for a page to create a link. Or just type in the url if it is external.',
				'tooltip'     => '<h4>Shortcode Syntax / Customization</h4>

					<p>There are several different ways that you can enter the shortcode:</p>
					<ul>
						<li><code>[link id=\'123\']</code> =
							<code>&lt;a href="{url of post/page #123}">{title of post/page #123}&lt;/a></code>
						</li>
						<li><code>[link id=\'123\' text=\'<b>my link text</b>\']</code> =
							<code>&lt;a href="{url of post/page #123
								}"><b>my link text</b>&lt;/a></code>
						</li>
					</ul>
		
					<p>You can also add a <code>class</code> or <code>rel</code> attribute to the shortcode, and it will be included in the resulting <code>&lt;a></code> tag:</p>
					<ul>
						<li>
							<code>[link id=\'123\' text=\'my link text\' class=\'my-class\' rel=\'external\']</code> =
							<code>&lt;a href="{url of post/page #123}" class="my-class"
								rel="external">my link text&lt;/a></code>
						</li>
					</ul>
		
					<h4>Usage</h4>
		
					<p>Type into the search box and posts whose title matches your search will be returned so that you can grab an internal link shortcode for them for
						use in the content of a post / page.</p>
		
					<p>The shortcode to link to a page looks something like this:</p>
		
					<p><code>[link id=\'123\']</code></p>
		
					<p>Add this to the content of a post or page and when the post or page is displayed, this would be replaced with a link to the post or page with the id of 123.</p>
		
					<p>These internal links are site reorganization-proof, the links will change automatically to reflect the new location or name of a post or page when it is moved.</p>'
			],
			[
				'label'       => 'Link Text Override',
				'attr'        => 'text',
				'type'        => 'text',
				'section'     => 'standard',
				'description' => 'optional',
				'tooltip'     => 'Use this to override the default Link text that this shortcode creates.'
			],
			[
				'label'   => 'Link Class',
				'attr'    => 'class',
				'type'    => 'text',
				'section' => 'advanced',
				'tooltip' => 'Use this to add custom css class to your link'
			],
			[
				'label'   => 'Alt Tag',
				'attr'    => 'alt',
				'type'    => 'text',
				'section' => 'advanced',
			],
			[
				'label'   => 'Title Tag',
				'attr'    => 'title',
				'type'    => 'text',
				'section' => 'advanced',
			],
			[
				'label'   => 'Rel Tag',
				'attr'    => 'rel',
				'type'    => 'text',
				'section' => 'advanced',
			],
			[
				'label'   => 'Link Style',
				'attr'    => 'style',
				'type'    => 'text',
				'section' => 'advanced',
			],
			[
				'label'   => 'Linked Image src',
				'attr'    => 'src',
				'type'    => 'text',
				'section' => 'advanced',
			],
			[
				'label'   => 'Link Anchor',
				'attr'    => 'anchor',
				'type'    => 'text',
				'section' => 'advanced',
			],
			[
				'label'   => 'Link OnClick',
				'attr'    => 'onclick',
				'type'    => 'text',
				'section' => 'advanced',
			],
			[
				'label'   => 'Link Target',
				'attr'    => 'target',
				'type'    => 'text',
				'section' => 'advanced',
			]
		];


		$shortcode = [
			'shortcode_tag' => "link",
			'action_tag'    => '',
			'label'         => 'Internal Link',
			'attrs'         => $atts,
		];


		return [ $shortcode ];
	}


	/**
	 * display button matching new UI
	 */
	function add_button()
	{
		echo '<a href="#" class="button ' . $this->il_full . '_media_link" id="add_' . $this->il_full . '" title="Add Internal Link">
			<div>Add Internal Link</div>
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


	function ajax_handler()
	{
		global $wpdb;

		$title = stripslashes( $_POST['post_title'] );
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
}
