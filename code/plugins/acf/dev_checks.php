<?php
/** @noinspection PhpMissingFieldTypeInspection */
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.08.29
 */
class lct_acf_dev_checks
{
	public $default_plugins = [];


	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.08.29
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
	 * @since    2017.70
	 * @verified 2017.08.29
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


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		if ( lct_wp_admin_non_ajax() ) {
			/**
			 * actions
			 */
			add_action( 'lct/acf/dev_report', [ $this, 'plugins_n_files' ] );

			add_action( 'lct/acf/dev_report', [ $this, 'modified_posts' ] );

			add_action( 'lct/acf/dev_report', [ $this, 'database_status_options' ] );

			add_action( 'lct/acf/dev_report', [ $this, 'database_status_postmeta' ] );

			add_action( 'lct/acf/dev_report', [ $this, 'database_status_usermeta' ] );


			/**
			 * filters
			 */
			add_filter( 'lct/dev_reports/post_types', [ $this, 'dev_reports_post_types' ], 10, 2 );

			add_filter( 'acf/load_field/name=' . zxzacf( 'db_status::options::ignore_names' ), [ $this, 'db_status_options_ignore_names' ] );

			add_filter( 'acf/load_field/name=' . zxzacf( 'db_status::postmeta::ignore_keys' ), [ $this, 'db_status_postmeta_ignore_keys' ] );

			add_filter( 'acf/load_field/name=' . zxzacf( 'db_status::usermeta::ignore_keys' ), [ $this, 'db_status_usermeta_ignore_keys' ] );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Check for files missing the ABSPATH conditional check
	 *
	 * @param        $dir
	 * @param        $find
	 * @param bool   $contains
	 * @param string $root_dir
	 * @param array  $excluded
	 * @param array  $files
	 *
	 * @return array
	 * @since    7.47
	 * @verified 2016.12.03
	 */
	function file_contains_check( $dir, $find, $contains = true, $root_dir = '', $excluded = [], $files = [] )
	{
		$find     = strtolower( $find );
		$iterator = new DirectoryIterator( $dir );


		foreach ( $iterator as $info ) {
			if ( $info->isFile() ) {
				//exclude some dirs
				if ( strpos_array( lct_static_cleaner( $info->getPathname() ), $excluded ) ) {
					continue;
				}

				$content = strtolower( file_get_contents( $info->getPathname() ) );


				//Add the file to the list if the $find is present
				if (
					$contains
					&& $content
					&& strpos( $content, $find ) !== false
				) {
					$files[] = str_replace( [ '//', $root_dir ], [ '/' ], lct_static_cleaner( $info->getPathname() ) );
					//Add the file to the list if the $find is missing
				} elseif (
					! $contains
					&& $content
					&& strpos( $content, $find ) === false
				) {
					$files[] = str_replace( [ '//', $root_dir ], [ '/' ], lct_static_cleaner( $info->getPathname() ) );
				}
			} elseif ( ! $info->isDot() ) {
				//exclude some dirs
				if ( strpos_array( lct_static_cleaner( $info->getPathname() ), $excluded ) ) {
					continue;
				}


				$files = $this->file_contains_check( $dir . DIRECTORY_SEPARATOR . $info->__toString(), $find, $contains, $root_dir, $excluded, $files );
			}
		}


		return $files;
	}


	/**
	 * Get the list of default plugins we want to run
	 *
	 * @param string|null $client
	 *
	 * @return array
	 * @since    2019.22
	 * @verified 2021.12.09
	 */
	function default_plugins( $client = null )
	{
		$this->update_plugin_details( 'admin-menu-editor', [ 'default' => true ] );
		$this->update_plugin_details( 'advanced-cron-manager', [ 'default' => true ] );
		$this->update_plugin_details( 'advanced-custom-fields-pro', [ 'default' => true, 'install_link' => '#C:\wamp\www\wp.eetah.com\x\lc-content\plugins\advanced-custom-fields-pro' ] );
		$this->update_plugin_details( 'call-tracking-metrics', [ 'default' => true, 'clients' => [ '00pimg' ] ] );
		$this->update_plugin_details( 'duplicate-post', [ 'default' => true ] );
		$this->update_plugin_details( 'fusion-builder', [ 'default' => true, 'install_link' => '#C:\wamp\www\wp.eetah.com\x\lc-content\plugins\fusion-builder' ] );
		$this->update_plugin_details( 'fusion-core', [ 'default' => true, 'install_link' => '#C:\wamp\www\wp.eetah.com\x\lc-content\plugins\fusion-core' ] );
		$this->update_plugin_details( 'github-updater', [ 'good' => true ] );
		$this->update_plugin_details( 'google-analytics-for-wordpress', [ 'default' => true ] );
		$this->update_plugin_details( 'gravityforms', [ 'default' => true, 'install_link' => '#C:\wamp\www\wp.eetah.com\x\lc-content\plugins\gravityforms' ] );
		$this->update_plugin_details( 'gravity-forms-zero-spam', [ 'default' => true ] );
		$this->update_plugin_details( lct_dash(), [ 'default' => true ] );
		$this->update_plugin_details( 'maintenance', [ 'default' => true ] );
		$this->update_plugin_details( 'redirection', [ 'default' => true ] );
		$this->update_plugin_details( 'revslider', [ 'good' => true, 'install_link' => '#C:\wamp\www\wp.eetah.com\x\lc-content\plugins\revslider' ] );
		$this->update_plugin_details( 'simple-image-sizes', [ 'default' => true ] );
		$this->update_plugin_details( 'simple-page-ordering', [ 'default' => true ] );
		$this->update_plugin_details( 'stream', [ 'default' => true ] );
		$this->update_plugin_details( 'transients-manager', [ 'default' => true ] );
		$this->update_plugin_details( 'widget-clone', [ 'default' => true ] );
		$this->update_plugin_details( 'widget-css-classes', [ 'default' => true ] );
		$this->update_plugin_details( 'wordfence', [ 'default' => true ] );
		$this->update_plugin_details( 'wordpress-seo', [ 'default' => true ] );
		$this->update_plugin_details( 'wp-mail-smtp', [ 'default' => true ] );
		$this->update_plugin_details( 'wp-rocket', [ 'default' => true ] );
		$this->update_plugin_details( 'wp-smushit', [ 'default' => true ] );
		$this->update_plugin_details( 'wp-sweep', [ 'default' => true ] );
		$this->update_plugin_details( 'wp-sync-db', [ 'default' => true, 'install_link' => 'https://github.com/wp-sync-db/wp-sync-db' ] );
		$this->update_plugin_details( 'wps-hide-login', [ 'default' => true ] );


		if ( $client === null ) {
			$client = lct_acf_get_option_raw( 'clientzz' );
		}


		foreach ( $this->default_plugins as $plugin => $details ) {
			if ( ! isset( $details['clients'] ) ) {
				continue;
			}


			if ( ! in_array( $client, $details['clients'] ) ) {
				$this->default_plugins[ $plugin ]['default'] = false;
				$this->default_plugins[ $plugin ]['good']    = true;
			}
		}


		$this->default_plugins = apply_filters( 'lct/acf/dev_checks/default_plugins', $this->default_plugins );


		return $this->default_plugins;
	}


	/**
	 * Update the details of a plugin
	 *
	 * @param string $slug
	 * @param array  $args
	 *
	 * @since    2019.22
	 * @verified 2019.08.08
	 */
	function update_plugin_details( string $slug, $args = [] )
	{
		if ( ! isset( $this->default_plugins[ $slug ] ) ) {
			$this->default_plugins[ $slug ] = [
				'dir'          => $slug,
				'basename'     => '',
				'install_link' => admin_url( 'plugin-install.php?tab=plugin-information&plugin=' . $slug ),
				'default'      => false,
				'installed'    => false,
				'active'       => false,
			];
		}


		if ( ! empty( $args ) ) {
			foreach ( $args as $k => $v ) {
				$this->default_plugins[ $slug ][ $k ] = $v;
			}
		}
	}


	/**
	 * Displays on the Dev Report Page
	 *
	 * @param array $field
	 *
	 * @since    7.47
	 * @verified 2019.11.08
	 */
	function plugins_n_files( array $field )
	{
		//TODO: cs - Improve this before using it again - 12/14/2023 4:55 PM
		return;


		if ( $field['key'] !== lct_raw_setting( 'acf_dev_report_plugins' ) ) {
			return;
		}


		$this->default_plugins = $this->default_plugins();
		$plugins_active        = get_option( 'active_plugins' );


		/**
		 * Go thru all the plugins from the directory
		 */
		foreach ( lct_list_directories( lct_path_plugin( true ) ) as $v ) {
			if ( strpos( $v, zxza( '-z-' ) ) !== false ) //LCT dev plugins
			{
				continue;
			}


			$this->update_plugin_details( $v, [ 'installed' => true ] );
		}


		/**
		 * Adjust for a multisite install
		 */
		if (
			is_multisite()
			&& ( $multisite_plugins_active = get_site_option( 'active_sitewide_plugins' ) )
			&& ! empty( $multisite_plugins_active )
		) {
			foreach ( $multisite_plugins_active as $multisite_plugin => $multisite_plugin_time ) {
				$plugins_active[] = $multisite_plugin;
			}


			$plugins_active = array_unique( $plugins_active );
		}


		/**
		 * Create active dir list array
		 */
		foreach ( $plugins_active as $plugin_active ) {
			$tmp = explode( '/', $plugin_active );


			$this->update_plugin_details( $tmp[0], [ 'active' => true ] );
		}


		ksort( $this->default_plugins );
		lct_delete_option( 'force_yes_fields' );
		$force_yes_fields = [];


		/**
		 * Print it all out
		 */
		echo '<style>.P_R .acf-fields > .acf-field{padding: 0;}</style>';


		echo '<table class="P_R" style="max-width: 1000px;width: 100%;margin: 0 auto;">';
		echo '<tr><th class="odd" colspan="2">' . zxzb( ' Plugin Status' ) . '</th></tr>';
		echo '<tr>';
		echo '<td class="even">';

		echo "<div class=\"even\" style=\"width:200px;display: inline-block;\">Plugin</div>";
		echo "<div class=\"odd\" style=\"width:65px;display: inline-block;text-align: center;\">Default</div>";
		echo "<div class=\"even\" style=\"width:65px;display: inline-block;text-align: center;\">Installed</div>";
		echo "<div class=\"odd\" style=\"width:65px;display: inline-block;text-align: center;\">Active</div>";
		echo "<div class=\"even\" style=\"width:120px;display: inline-block;text-align: center;\">Is this a good thing?</div>";
		echo "<div class=\"odd\" style=\"width:240px;display: inline-block;text-align: center;\">Force good thing as YES</div>";
		echo "<div class=\"clear\" style=\"border-bottom: 3px solid #000000;height: 1px;\"></div>";

		$style = 'display: inline-block;text-align: center;height: 30px;padding-top: 10px;color: #FFFFFF;width:';

		foreach ( $this->default_plugins as $plugin_list ) {
			if ( ! $plugin_list['installed'] ) {
				if ( strpos( $plugin_list['install_link'], '#' ) !== false ) {
					echo "<div class=\"even\" style=\"width:200px;display: inline-block;\"><a href=\"{$plugin_list['install_link']}\" title=\"{$plugin_list['install_link']}\">{$plugin_list['dir']}</a></div>";
				} else {
					echo "<div class=\"even\" style=\"width:200px;display: inline-block;\"><a href=\"{$plugin_list['install_link']}\" target=\"_blank\">{$plugin_list['dir']}</a></div>";
				}
			} else {
				echo "<div class=\"even\" style=\"width:200px;display: inline-block;\">{$plugin_list['dir']}</div>";
			}


			if ( $plugin_list['default'] ) {
				$color = 'green';
				$text  = 'YES';
			} else {
				$color = 'red';
				$text  = 'NO';
			}

			echo "<div style=\"{$style}65px;background-color: {$color};\">{$text}</div>";


			if ( $plugin_list['installed'] ) {
				$color = 'green';
				$text  = 'YES';
			} else {
				$color = 'red';
				$text  = 'NO';
			}

			echo "<div style=\"{$style}65px;background-color: {$color};\">{$text}</div>";


			if ( $plugin_list['active'] ) {
				$color = 'green';
				$text  = 'YES';
			} else {
				$color = 'red';
				$text  = 'NO';
			}

			echo "<div style=\"{$style}65px;background-color: {$color};\">{$text}</div>";


			$key  = 'field_58520778ff477_force_yes_field_' . $plugin_list['dir'];
			$name = zxzacf( 'force_yes_' . $plugin_list['dir'] );


			$field = [
				'default_value'          => 0,
				'message'                => '',
				'ui'                     => 1,
				'ui_on_text'             => 'Force Yes',
				'ui_off_text'            => 'Don\'t Force Yes',
				'key'                    => $key,
				'label'                  => 'Force Yes',
				'name'                   => $name,
				'type'                   => 'true_false',
				'instructions'           => '',
				'required'               => 0,
				'conditional_logic'      => 0,
				'wrapper'                => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				zxzu( 'class_selector' ) => [
					0 => 'hide_label',
				],
				'parent'                 => lct_get_setting( 'acf_group_dev_report' ),
			];
			acf_add_local_field( $field );
			$force_yes_fields[ $key ] = $field;


			$show_force_good = get_field( $name, lct_o() );


			if (
				$show_force_good === null
				|| $show_force_good === ''
			) {
				update_field( $key, 0, lct_o() );
			}


			if ( ! empty( $plugin_list['good'] ) ) {
				$color           = 'green';
				$text            = 'YES';
				$show_force_good = false;
			} elseif ( $show_force_good ) {
				$color           = 'green';
				$text            = 'YES';
				$show_force_good = true;
			} elseif (
				$plugin_list['default']
				&& $plugin_list['installed']
				&& $plugin_list['active']
			) {
				$color = 'green';
				$text  = 'YES';
			} elseif (
				$plugin_list['default']
				&& ! $plugin_list['installed']
				&& ! $plugin_list['active']
			) {
				$color           = 'red';
				$text            = 'Probably Not';
				$show_force_good = true;
			} elseif (
				$plugin_list['default']
				&& $plugin_list['installed']
				&& ! $plugin_list['active']
			) {
				$color           = 'orange';
				$text            = 'MAYBE';
				$show_force_good = true;
			} elseif (
				! $plugin_list['default']
				&& $plugin_list['installed']
				&& $plugin_list['active']
			) {
				$color           = 'orange';
				$text            = 'MAYBE';
				$show_force_good = true;
			} elseif (
				! $plugin_list['default']
				&& $plugin_list['installed']
				&& ! $plugin_list['active']
			) {
				$color           = 'red';
				$text            = 'Probably Not';
				$show_force_good = true;
			} else {
				$color           = 'red';
				$text            = 'NO';
				$show_force_good = true;
			}

			echo "<div style=\"{$style}120px;background-color: {$color};\">{$text}</div>";


			if ( $show_force_good ) {
				$a = [
					'post_id'           => lct_o(),
					'fields'            => $name,
					'form'              => false,
					zxzu( 'form_div' )  => 'div',
					'instant'           => true,
					zxzu( 'echo_form' ) => false,
				];
				echo "<div style=\"display: inline-block;height: 30px;width: 240px;padding-left: 10px;\">" . lct_acf_form2( $a ) . "</div>";
			}


			echo "<div class=\"clear\" style=\"border-bottom: 1px solid #000000;height: 1px;\"></div>";
		}


		lct_update_option( 'force_yes_fields', $force_yes_fields, false );


		echo '</td>';
		echo '</tr>';
		echo '</table>';


		echo '<br /><br />';


		/**
		 * Check for files missing a ABSPATH conditional check
		 */
		$find    = 'Do not allow directly accessing this file';
		$exclude = [
			'admin/direct',
			'admin/git/_lct_root',
			'admin/git/_lct_wp',
			'features/tpl/internal_link.tpl.php',
			'features/tpl/tel_link.tpl.php',
			'plugins/acf/field-types/_template_master',
			'plugins/Avada/override/fusion-core/admin/page-builder/assets/js/js-wp-editor-legacy_2.0.2.js',
			'plugins/photo-contest/assets',
		];

		$abspath_check = $this->file_contains_check( lct_get_setting( 'path' ), $find, false, lct_get_setting( 'path' ), $exclude );
		P_R( $abspath_check, zxzb( ' Files Missing ABSPATH Conditional Check' ) );


		echo '<br /><br />';


		/**
		 * Check for files containing editzz
		 */
		$find    = 'editzz';
		$exclude = [
			'code/admin/direct',
			'code/admin/git/_lct_root',
			'code/admin/git/_lct_wp',
			'code/api/get.php',
			'code/plugins/_/_editzz.php',
			'code/plugins/acf/_admin.php',
			'code/plugins/acf/dev_checks.php',
			'code/plugins/acf/op_main_dev_groups.php',
			'code/plugins/acf/op_main_fixes_cleanups.php',
			'code/plugins/acf/op_main_fixes_cleanups_groups.php',
			'code/wp-admin/admin/update_extras.php',
			'readme.txt',
		];

		$abspath_check = $this->file_contains_check( lct_get_setting( 'root_path' ), $find, true, lct_get_setting( 'root_path' ), $exclude );
		P_R( $abspath_check, zxzb( ' Files Containing editzz' ) );


		echo '<br /><br />';


		/**
		 * Check for files containing /*~~~ Verified ::
		 */
		$find    = '/*~~~ Verified ::';
		$exclude = [
			'admin/git/_lct_root',
			'admin/git/_lct_wp',
			'plugins/acf/dev_checks.php'
		];

		$abspath_check = $this->file_contains_check( lct_get_setting( 'path' ), $find, true, lct_get_setting( 'path' ), $exclude );
		P_R( $abspath_check, zxzb( ' Files Containing Verified ::' ) );


		echo '<br /><br />';


		/**
		 * Post Types
		 */
		$args           = [
			'_builtin' => false,
		];
		$all_post_types = get_post_types( $args );
		$all_post_types = apply_filters( 'lct/dev_reports/post_types', $all_post_types, $args );
		ksort( $all_post_types );


		$post_types = [];


		if ( ! empty( $all_post_types ) ) {
			foreach ( $all_post_types as $post_type ) {
				$post_types[] = $post_type;
			}
		}


		P_R( $post_types, zxzb( ' Review these Custom Post Types Permalinks (post_types) ::' ) );
	}


	/**
	 * Displays on the Dev Report Page
	 *
	 * @param array $field
	 *
	 * @since    2017.70
	 * @verified 2019.11.08
	 */
	function modified_posts( array $field )
	{
		if ( $field['key'] !== lct_raw_setting( 'acf_dev_report_modified_posts' ) ) {
			return;
		}


		/**
		 * Date is set
		 */
		if (
			version_compare( lct_get_setting( 'wp_version' ), '4.6', '>=' )
			&& //WP v4.6 or newer
			$mod_date = lct_acf_get_option( 'modified_posts_last_check' )
		) {
			$post_types_checked = [];
			$mod_date           = lct_DateTime( $mod_date, false, true );

			$date_query = [
				'column'    => 'post_modified_gmt',
				'after'     => [
					'year'   => $mod_date->format( 'Y' ),
					'month'  => $mod_date->format( 'm' ),
					'day'    => $mod_date->format( 'd' ),
					'hour'   => $mod_date->format( 'H' ),
					'minute' => $mod_date->format( 'i' ),
					'second' => $mod_date->format( 's' ),
				],
				'inclusive' => true,
			];


			$args               = [
				'_builtin' => false,
			];
			$post_types         = get_post_types( $args );
			$post_types['page'] = 'page';
			$post_types['post'] = 'post';


			$hidden_post_types = [
				'acf-field',
				'acf-field-group',
				'fusion_element',
				'fusion_template',
				'wp_stream_alerts',
				'ereminder',
			];
			$hidden_post_types = apply_filters( 'lct/dev_checks/modified_posts/hidden_post_types', $hidden_post_types );


			foreach ( $post_types as $post_type ) {
				if ( in_array( $post_type, $hidden_post_types ) ) {
					continue;
				}


				$args  = [
					'posts_per_page'         => - 1,
					'post_type'              => $post_type,
					'post_status'            => 'any',
					'date_query'             => $date_query,
					'cache_results'          => true,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
				];
				$posts = get_posts( $args );


				if ( $posts ) {
					echo sprintf( '<h1>%s</h1>', $post_type );


					//TODO: cs - Add a mark as checked button that resets the next time the post is modified - 8/29/2017 4:05 PM
					foreach ( $posts as $post ) {
						if (
							$post_type === 'ninja-table'
							&& strpos( $post->post_title, 'Master:' ) !== 0
						) {
							continue;
						}


						echo sprintf(
							'<h4>Modified Date: %s &mdash; %s &mdash; %s (%s) &mdash; <a target="_blank" href="%s">Edit</a> &mdash; <a target="_blank" href="%s">View</a></h4>',
							get_the_modified_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $post ),
							$post->ID,
							get_the_title( $post ),
							'/' . lct_get_the_slug( $post, true ),
							get_edit_post_link( $post ),
							get_the_permalink( $post )
						);
					}


					echo '<br />';
				}


				$post_types_checked[] = $post_type;
			}


			sort( $post_types_checked );
			echo sprintf(
				'<h4>Post Types Checked:<br />%s</h4>',
				implode( '<br />', $post_types_checked )
			);


			/**
			 * Date is NOT set
			 */
		} else {
			if ( $tmp = get_field_object( zxzacf( 'modified_posts_last_check' ), lct_o() ) ) {
				echo 'Please set the date in the <strong>"' . $tmp['label'] . '"</strong> field above.';
			}
		}
	}


	/**
	 * Displays on the Dev Report Page
	 * //TODO: cs - auto remove active ACF fields from list - 8/13/2019 7:20 AM
	 *
	 * @param array $field
	 *
	 * @since    2019.19
	 * @verified 2019.08.15
	 */
	function database_status_options( array $field )
	{
		if ( $field['key'] !== lct_raw_setting( 'acf_dev_report_database_status_options' ) ) {
			return;
		}


		global $wpdb;

		$html               = [];
		$greater_than_count = 0;
		$ignore             = $this->option_names_to_ignore();
		$not_in_array       = lct_acf_get_option( 'db_status::options::ignore_names' );
		if ( empty( $not_in_array ) ) {
			$not_in_array = [ LCT_VALUE_EMPTY ];
		}
		$not_in_array = (array) array_merge( $not_in_array, $ignore );
		$not_in       = lct_array_to_quoted_string( $not_in_array );
		$all_options  = $wpdb->get_results(
			"SELECT `option_name`, `option_value` 
				FROM {$wpdb->options} 
				WHERE `option_name` NOT IN ({$not_in})
				ORDER BY `option_name` ASC"
		);


		if ( ! empty( $all_options ) ) {
			$option_list              = [];
			$large_option_list        = [];
			$option_maybe_delete_list = [];
			$ignore_strpos            = $this->option_names_LIKE_to_ignore();
			$maybe_delete             = $this->option_names_to_maybe_delete();


			foreach ( $all_options as $option ) {
				if ( strpos_array( $option->option_name, $ignore_strpos, false, true ) === 0 ) {
					continue;
				}


				$name    = $option->option_name;
				$length  = strlen( $option->option_value );
				$value   = htmlentities( substr( $option->option_value, 0, 100 ) );
				$details = sprintf( '%s &mdash; %s &mdash; %s', $name, $length, $value );


				if ( $length > lct_acf_get_option( 'db_status::options::large_option_value_limit' ) ) {
					$length  = sprintf( '<span style="color: red;font-weight: bold;">%s [LARGE]</span>', $length );
					$details = sprintf( '%s &mdash; %s &mdash; %s', $name, $length, $value );


					$large_option_list[ $name ] = $details;
					$greater_than_count ++;
				}


				if ( in_array( $option->option_name, $maybe_delete ) ) {
					$option_maybe_delete_list[ $name ] = $details;
				}


				$option_list[ $name ] = $details;
			}


			$html[] = '<h3 style="margin-bottom: 0;">Important Details</h3>';
			$html[] = sprintf( '<strong>Greater than %s count: %s</strong>', lct_acf_get_option( 'db_status::options::large_option_value_limit' ), $greater_than_count );

			$html[] = '<h3 style="margin-bottom: 0;">Large Options List</h3>';
			if ( empty( $large_option_list ) ) {
				$large_option_list[] = 'None';
			}
			$html[] = lct_return( $large_option_list, '<br />' );
			$html[] = lct_return( array_keys( $large_option_list ), ', ' );

			$html[] = '<h3 style="margin-bottom: 0;">Options to Maybe Delete List</h3>';
			if ( empty( $option_maybe_delete_list ) ) {
				$option_maybe_delete_list[] = 'None';
			}
			$html[] = lct_return( $option_maybe_delete_list, '<br />' );
			$html[] = lct_return( array_keys( $option_maybe_delete_list ), ', ' );

			$html[] = '<h3 style="margin-bottom: 0;">Options List</h3>';
			if ( empty( $option_list ) ) {
				$option_list[] = 'None';
			}
			$html[] = lct_return( $option_list, '<br />' );
			$html[] = lct_return( array_keys( $option_list ), ', ' );
		}


		echo lct_return( $html, '<br />' );
	}


	/**
	 * Displays on the Dev Report Page
	 * //TODO: cs - auto remove active ACF fields from list - 8/13/2019 7:20 AM
	 *
	 * @param array $field
	 *
	 * @since    2019.19
	 * @verified 2019.07.16
	 */
	function database_status_postmeta( array $field )
	{
		if ( $field['key'] !== lct_raw_setting( 'acf_dev_report_database_status_postmeta' ) ) {
			return;
		}


		global $wpdb;

		$html         = [];
		$meta_count   = 0;
		$ignore       = $this->postmeta_to_ignore();
		$not_in_array = lct_acf_get_option( 'db_status::postmeta::ignore_keys' );
		if ( empty( $not_in_array ) ) {
			$not_in_array = [ LCT_VALUE_EMPTY ];
		}
		$not_in_array = array_merge( $not_in_array, $ignore );
		$not_in       = lct_array_to_quoted_string( $not_in_array );
		$all_meta     = $wpdb->get_col(
			"SELECT `meta_key` 
				FROM {$wpdb->postmeta} 
				WHERE `meta_key` NOT IN ({$not_in})
				GROUP BY `meta_key`
				ORDER BY `meta_key` ASC"
		);


		if ( ! empty( $all_meta ) ) {
			$meta_list              = [];
			$meta_maybe_delete_list = [];
			$ignore_strpos          = $this->postmeta_LIKE_to_ignore();
			$maybe_delete           = $this->postmeta_to_maybe_delete();


			foreach ( $all_meta as $metakey ) {
				if ( strpos_array( $metakey, $ignore_strpos, false, true ) === 0 ) {
					continue;
				}


				$count   = $wpdb->get_var(
					"SELECT count(*) 
						FROM {$wpdb->postmeta} 
						WHERE `meta_key` = '{$metakey}'"
				);
				$details = sprintf( '%s &mdash; %s', $metakey, $count );


				if ( in_array( $metakey, $maybe_delete ) ) {
					$meta_maybe_delete_list[] = $details;
				}


				$meta_list[] = $details;


				$meta_count ++;
			}


			$html[] = '<h3 style="margin-bottom: 0;">Important Details</h3>';
			$html[] = sprintf( '<strong>postmeta count: %s</strong>', $meta_count );

			$html[] = '<h3 style="margin-bottom: 0;">Metas to Maybe Delete List</h3>';
			if ( empty( $meta_maybe_delete_list ) ) {
				$meta_maybe_delete_list[] = 'None';
			}
			$html[] = lct_return( $meta_maybe_delete_list, '<br />' );

			$html[] = '<h3 style="margin-bottom: 0;">Metas List</h3>';
			if ( empty( $meta_list ) ) {
				$meta_list[] = 'None';
			}
			$html[] = lct_return( $meta_list, '<br />' );
		}


		echo lct_return( $html, '<br />' );
	}


	/**
	 * Displays on the Dev Report Page
	 * //TODO: cs - auto remove active ACF fields from list - 8/13/2019 7:20 AM
	 *
	 * @param array $field
	 *
	 * @since    2019.19
	 * @verified 2019.07.16
	 */
	function database_status_usermeta( array $field )
	{
		if ( $field['key'] !== lct_raw_setting( 'acf_dev_report_database_status_usermeta' ) ) {
			return;
		}


		global $wpdb;

		$html         = [];
		$meta_count   = 0;
		$ignore       = $this->usermeta_to_ignore();
		$not_in_array = lct_acf_get_option( 'db_status::usermeta::ignore_keys' );
		if ( empty( $not_in_array ) ) {
			$not_in_array = [ LCT_VALUE_EMPTY ];
		}
		$not_in_array = array_merge( $not_in_array, $ignore );
		$not_in       = lct_array_to_quoted_string( $not_in_array );
		$all_meta     = $wpdb->get_col(
			"SELECT `meta_key` 
				FROM {$wpdb->usermeta} 
				WHERE `meta_key` NOT IN ({$not_in})
				GROUP BY `meta_key`
				ORDER BY `meta_key` ASC"
		);


		if ( ! empty( $all_meta ) ) {
			$meta_list              = [];
			$meta_maybe_delete_list = [];
			$ignore_strpos          = $this->usermeta_LIKE_to_ignore();
			$maybe_delete           = $this->usermeta_to_maybe_delete();


			foreach ( $all_meta as $metakey ) {
				if ( strpos_array( $metakey, $ignore_strpos, false, true ) === 0 ) {
					continue;
				}


				$count   = $wpdb->get_var(
					"SELECT count(*) 
						FROM {$wpdb->usermeta} 
						WHERE `meta_key` = '{$metakey}'"
				);
				$details = sprintf( '%s &mdash; %s', $metakey, $count );


				if ( in_array( $metakey, $maybe_delete ) ) {
					$meta_maybe_delete_list[] = $details;
				}


				$meta_list[] = $details;


				$meta_count ++;
			}


			$html[] = '<h3 style="margin-bottom: 0;">Important Details</h3>';
			$html[] = sprintf( '<strong>usermeta count: %s</strong>', $meta_count );

			$html[] = '<h3 style="margin-bottom: 0;">Metas to Maybe Delete List</h3>';
			if ( empty( $meta_maybe_delete_list ) ) {
				$meta_maybe_delete_list[] = 'None';
			}
			$html[] = lct_return( $meta_maybe_delete_list, '<br />' );

			$html[] = '<h3 style="margin-bottom: 0;">Metas List</h3>';
			if ( empty( $meta_list ) ) {
				$meta_list[] = 'None';
			}
			$html[] = lct_return( $meta_list, '<br />' );
		}


		echo lct_return( $html, '<br />' );
	}


	/**
	 * post_types for dev_report
	 *
	 * @param $all_post_types
	 *
	 * @unused   param $args
	 * @return mixed
	 * @since    2017.81
	 * @verified 2018.02.17
	 */
	function dev_reports_post_types( $all_post_types )
	{
		if ( ! empty( $all_post_types ) ) {
			$exclude = [
				'acf-field',
				'acf-field-group',
				'amn_mi-lite',
				'fusion_element',
				'fusion_template',
				'lct_theme_chunk',
				'slide',
				'wp_stream_alerts',
				'scheduled-action',
				'wc_membership_plan',
				'wc_user_membership',
			];
			$exclude = apply_filters( 'lct/dev_checks/dev_reports_post_types/exclude', $exclude );


			foreach ( $all_post_types as $key => $all_post_type ) {
				if ( in_array( $all_post_type, $exclude ) ) {
					unset( $all_post_types[ $key ] );


					continue;
				}


				$args  = [
					'posts_per_page'         => - 1,
					'post_type'              => $all_post_type,
					'post_status'            => 'any',
					'cache_results'          => true,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
				];
				$posts = get_posts( $args );


				if ( empty( $posts ) ) {
					unset( $all_post_types[ $key ] );


					continue;
				}
			}
		}


		return $all_post_types;
	}


	/**
	 * Update the dropdown for option_name(s) to ignore
	 *
	 * @param array $field
	 *
	 * @return array
	 * @since    2019.19
	 * @verified 2019.12.05
	 */
	function db_status_options_ignore_names( array $field )
	{
		if ( lct_acf_is_field_group_editing_page() ) { //Don't load on ACf edit pages
			return $field;
		}


		//bail early if already ran
		if ( lct_did() ) {
			return $field;
		}


		global $wpdb;

		$choices       = [];
		$all_options   = $wpdb->get_results(
			"SELECT `option_name`, `option_value` 
				FROM {$wpdb->options}
				ORDER BY `option_name` ASC"
		);
		$ignore        = $this->option_names_to_ignore();
		$ignore_strpos = $this->option_names_LIKE_to_ignore();


		if ( ! empty( $all_options ) ) {
			foreach ( $all_options as $option ) {
				if ( in_array( $option->option_name, $ignore ) ) {
					continue;
				} elseif ( strpos_array( $option->option_name, $ignore_strpos, false, true ) === 0 ) {
					continue;
				}


				$choices[ $option->option_name ] = $option->option_name;
			}
		}


		$field['choices'] = $choices;


		return $field;
	}


	/**
	 * Array of option_name(s) to ignore
	 *
	 * @return array
	 * @since    2019.19
	 * @verified 2019.07.16
	 */
	function option_names_to_ignore()
	{
		$ignore = [
			'_amn_mi-lite_last_checked'             => true,
			'_amn_mi-lite_to_check'                 => true,
			zxzu( 'editzz_version' )                => true,
			zxzu( 'force_yes_fields' )              => true,
			zxzu( 'option_repeaters' )              => true,
			zxzu( 'update_all_page_sidebar_metas' ) => true,
			zxzu( 'update_all_sidebar_metas' )      => true,
			zxzu( 'version' )                       => true,
			zxzu( 'wf_initial_tasks' )              => true,
			zxzu( 'wpdev' )                         => true,
		];


		/**
		 * Add LCT ACF Fields
		 */
		if (
			( $option_groups_fields = lct_acf_get_field_groups_fields( [ 'options_page' => true ] ) )
			&& ! empty( $option_groups_fields )
		) {
			foreach ( $option_groups_fields as $field ) {
				if ( empty( $field['_name'] ) ) {
					continue;
				}


				$option = 'options_' . $field['_name'];


				$ignore[ $option ]               = true;
				$ignore[ lct_pre_us( $option ) ] = true;
			}
		}


		if ( $ignore ) {
			$ignore = array_keys( $ignore );
		}


		return $ignore;
	}


	/**
	 * Array of LIKE option_name(s) to ignore
	 *
	 * @return array
	 * @since    2019.19
	 * @verified 2019.07.16
	 */
	function option_names_LIKE_to_ignore()
	{
		$ignore = [
			'_options_lct:::force_yes_'   => true,
			'options_lct:::force_yes_'    => true,
			'_site_transient_'            => true,
			'_site_transient_timeout_'    => true,
			'_transient_'                 => true,
			'_transient_timeout_'         => true,
			zxzu( 'option_repeater_lct' ) => true,
		];


		/**
		 * Add LCT ACF Fields
		 */
		if (
			( $option_groups_fields = lct_acf_get_field_groups_fields( [ 'options_page' => true ] ) )
			&& ! empty( $option_groups_fields )
		) {
			foreach ( $option_groups_fields as $field ) {
				if (
					empty( $field['_name'] )
					|| empty( $field['type'] )
					|| empty( $field['sub_fields'] )
					|| ! in_array( $field['type'], [ 'repeater' ] )
					|| ! ( $repeater_count = acf_get_value( lct_o(), $field ) )
				) {
					continue;
				}


				foreach ( $field['sub_fields'] as $sub_field ) {
					foreach ( $repeater_count as $k => $data ) {
						$option = 'options_' . $field['_name'] . '_' . $k . '_' . $sub_field['_name'];


						$ignore[ $option ]               = true;
						$ignore[ lct_pre_us( $option ) ] = true;
					}
				}
			}
		}


		if ( $ignore ) {
			$ignore = array_keys( $ignore );
			sort( $ignore );
		}


		return $ignore;
	}


	/**
	 * Array of option_name(s) to maybe delete
	 *
	 * @return array
	 * @since    2019.19
	 * @verified 2019.07.16
	 */
	function option_names_to_maybe_delete()
	{
		$options = [
			'avada_applied_patches'       => true,
			'Avada_options'               => true,
			'avada_patcher_messages '     => true,
			'avada_theme_options'         => true,
			'fusion_builder_options'      => true,
			zxzu( 'wpsdb_user_sessions' ) => true,
			'nks_cc_options'              => true,
			'revslider-addons'            => true,
			'revslider-notices'           => true,
			'rs-library'                  => true,
			'rs-templates'                => true,
			'wpsdb_error_log'             => true,
		];


		if ( $options ) {
			$options = array_keys( $options );
		}


		return $options;
	}


	/**
	 * Update the dropdown for postmeta(s) to ignore
	 *
	 * @param array $field
	 *
	 * @return array
	 * @since    2019.19
	 * @verified 2019.12.05
	 */
	function db_status_postmeta_ignore_keys( array $field )
	{
		if ( lct_acf_is_field_group_editing_page() ) { //Don't load on ACf edit pages
			return $field;
		}


		//bail early if already ran
		if ( lct_did() ) {
			return $field;
		}


		global $wpdb;

		$choices       = [];
		$all_meta      = $wpdb->get_col(
			"SELECT `meta_key` 
				FROM {$wpdb->postmeta}
				GROUP BY `meta_key`
				ORDER BY `meta_key` ASC"
		);
		$ignore        = $this->postmeta_to_ignore();
		$ignore_strpos = $this->postmeta_LIKE_to_ignore();


		if ( ! empty( $all_meta ) ) {
			foreach ( $all_meta as $meta ) {
				if ( in_array( $meta, $ignore ) ) {
					continue;
				} elseif ( strpos_array( $meta, $ignore_strpos, false, true ) === 0 ) {
					continue;
				}


				$choices[ $meta ] = $meta;
			}
		}


		$field['choices'] = $choices;


		return $field;
	}


	/**
	 * Array of postmeta(s) to ignore
	 *
	 * @return array
	 * @since        2019.19
	 * @verified     2019.07.16
	 * @noinspection DuplicatedCode
	 */
	function postmeta_to_ignore()
	{
		$groups_fields = [];
		$ignore        = [
			'_dp_original' => true,
			'_edit_last'   => true,
			'_edit_lock'   => true,
		];


		/**
		 * Add LCT ACF Fields
		 */
		foreach ( get_post_types() as $post_type ) {
			if ( $group = lct_acf_get_field_groups_fields( [ 'post_type' => $post_type ] ) ) {
				$groups_fields = array_merge( $groups_fields, $group );
			}
		}


		if ( ! empty( $groups_fields ) ) {
			foreach ( $groups_fields as $field ) {
				if ( empty( $field['_name'] ) ) {
					continue;
				}


				$key = $field['_name'];


				$ignore[ $key ]               = true;
				$ignore[ lct_pre_us( $key ) ] = true;
			}
		}


		if ( $ignore ) {
			$ignore = array_keys( $ignore );
		}


		return $ignore;
	}


	/**
	 * Array of LIKE postmeta(s) to ignore
	 *
	 * @return array
	 * @since        2019.19
	 * @verified     2019.07.16
	 * @noinspection DuplicatedCode
	 */
	function postmeta_LIKE_to_ignore()
	{
		$groups_fields = [];
		$ignore        = [
			'pyre_'                => true,
			'sbg_selected_sidebar' => true,
		];


		/**
		 * Add LCT ACF Fields
		 */
		foreach ( get_post_types() as $post_type ) {
			if ( $group = lct_acf_get_field_groups_fields( [ 'post_type' => $post_type ] ) ) {
				$groups_fields = array_merge( $groups_fields, $group );
			}
		}


		if ( ! empty( $groups_fields ) ) {
			foreach ( $groups_fields as $field ) {
				if (
					empty( $field['_name'] )
					|| empty( $field['type'] )
					|| empty( $field['sub_fields'] )
					|| ! in_array( $field['type'], [ 'repeater' ] )
					|| ! ( $repeater_count = acf_get_value( lct_o(), $field ) )
				) {
					continue;
				}


				foreach ( $field['sub_fields'] as $sub_field ) {
					foreach ( $repeater_count as $k => $data ) {
						$option = $field['_name'] . '_' . $k . '_' . $sub_field['_name'];


						$ignore[ $option ]               = true;
						$ignore[ lct_pre_us( $option ) ] = true;
					}
				}
			}
		}


		if ( $ignore ) {
			$ignore = array_keys( $ignore );
			sort( $ignore );
		}


		return $ignore;
	}


	/**
	 * Array of postmeta(s) to maybe delete
	 *
	 * @return array
	 * @since    2019.19
	 * @verified 2019.07.16
	 */
	function postmeta_to_maybe_delete()
	{
		$meta = [
			'_bj_lazy_load_skip_post' => true,
			'_map'                    => true,
		];


		if ( $meta ) {
			$meta = array_keys( $meta );
		}


		return $meta;
	}


	/**
	 * Update the dropdown for usermeta(s) to ignore
	 *
	 * @param array $field
	 *
	 * @return array
	 * @since    2019.19
	 * @verified 2019.12.05
	 */
	function db_status_usermeta_ignore_keys( array $field )
	{
		if ( lct_acf_is_field_group_editing_page() ) { //Don't load on ACf edit pages
			return $field;
		}


		//bail early if already ran
		if ( lct_did() ) {
			return $field;
		}


		global $wpdb;

		$choices       = [];
		$all_meta      = $wpdb->get_col(
			"SELECT `meta_key` 
				FROM {$wpdb->usermeta}
				GROUP BY `meta_key`
				ORDER BY `meta_key` ASC"
		);
		$ignore        = $this->usermeta_to_ignore();
		$ignore_strpos = $this->usermeta_LIKE_to_ignore();


		if ( ! empty( $all_meta ) ) {
			foreach ( $all_meta as $meta ) {
				if ( in_array( $meta, $ignore ) ) {
					continue;
				} elseif ( strpos_array( $meta, $ignore_strpos, false, true ) === 0 ) {
					continue;
				}


				$choices[ $meta ] = $meta;
			}
		}


		$field['choices'] = $choices;


		return $field;
	}


	/**
	 * Array of usermeta(s) to ignore
	 *
	 * @return array
	 * @since    2019.19
	 * @verified 2019.07.16
	 */
	function usermeta_to_ignore()
	{
		$groups_fields = [];
		$ignore        = [];


		/**
		 * Add LCT ACF Fields
		 */
		foreach ( get_post_types() as $post_type ) {
			if ( $group = lct_acf_get_field_groups_fields( [ 'post_type' => $post_type ] ) ) {
				$groups_fields = array_merge( $groups_fields, $group );
			}
		}


		if ( ! empty( $groups_fields ) ) {
			foreach ( $groups_fields as $field ) {
				if ( empty( $field['_name'] ) ) {
					continue;
				}


				$key = $field['_name'];


				$ignore[ $key ]               = true;
				$ignore[ lct_pre_us( $key ) ] = true;
			}
		}


		if ( $ignore ) {
			$ignore = array_keys( $ignore );
		}


		return $ignore;
	}


	/**
	 * Array of LIKE usermeta(s) to ignore
	 *
	 * @return array
	 * @since        2019.19
	 * @verified     2019.07.16
	 * @noinspection DuplicatedCode
	 */
	function usermeta_LIKE_to_ignore()
	{
		$groups_fields = [];
		$ignore        = [];


		/**
		 * Add LCT ACF Fields
		 */
		foreach ( get_post_types() as $post_type ) {
			if ( $group = lct_acf_get_field_groups_fields( [ 'post_type' => $post_type ] ) ) {
				$groups_fields = array_merge( $groups_fields, $group );
			}
		}


		if ( ! empty( $groups_fields ) ) {
			foreach ( $groups_fields as $field ) {
				if (
					empty( $field['_name'] )
					|| empty( $field['type'] )
					|| empty( $field['sub_fields'] )
					|| ! in_array( $field['type'], [ 'repeater' ] )
					|| ! ( $repeater_count = acf_get_value( lct_o(), $field ) )
				) {
					continue;
				}


				foreach ( $field['sub_fields'] as $sub_field ) {
					foreach ( $repeater_count as $k => $data ) {
						$option = $field['_name'] . '_' . $k . '_' . $sub_field['_name'];


						$ignore[ $option ]               = true;
						$ignore[ lct_pre_us( $option ) ] = true;
					}
				}
			}
		}


		if ( $ignore ) {
			$ignore = array_keys( $ignore );
			sort( $ignore );
		}


		return $ignore;
	}


	/**
	 * Array of usermeta(s) to maybe delete
	 *
	 * @return array
	 * @since    2019.19
	 * @verified 2019.07.16
	 */
	function usermeta_to_maybe_delete()
	{
		$meta = [];


		if ( $meta ) {
			$meta = array_keys( $meta );
		}


		return $meta;
	}
}
