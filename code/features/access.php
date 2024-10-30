<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.02.09
 */
class lct_features_access
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.02.09
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
	 * @since    2017.11
	 * @verified 2017.04.27
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
		 * filters
		 */
		add_filter( 'lct/current_user_can_access', [ $this, 'current_user_can_access' ], 5, 2 );

		add_filter( 'lct/direct_current_user_can_edit', [ $this, 'direct_current_user_can_edit' ], 5, 2 );

		add_filter( 'lct/current_user_can_view', [ $this, 'current_user_can_view' ], 5, 2 );

		add_filter( 'lct/direct_current_user_can_view', [ $this, 'direct_current_user_can_view' ], 5, 2 );


		/**
		 * Actions
		 */
		add_action( 'set_logged_in_cookie', [ $this, 'varnish_set_2nd_logged_in_cookie' ], 10, 5 );


		/**
		 * shortcodes
		 */
		add_shortcode( zxzu( 'current_user_can' ), [ $this, 'current_user_can' ] );


		if ( lct_frontend() ) {
			/**
			 * actions
			 */
			add_action( 'pre_get_posts', [ $this, 'pre_get_posts_front_access' ], 5 );

			add_action( 'wp', [ $this, 'remove_pre_get_posts_front_access' ] );

			add_action( 'template_redirect', [ $this, 'template_redirect_front_access' ], 5 );

			add_action( 'acf/render_field', [ $this, 'render_field_viewonly' ], 20 );


			/**
			 * filters
			 */
			add_filter( 'wp_nav_menu_objects', [ $this, 'wp_nav_menu_objects' ], 5, 2 );

			add_filter( 'acf/prepare_field', [ $this, 'prepare_field_access_primary' ], 5 ); //We don't want to alter the class on the field editing page or anywhere on the back-end
		}


		if ( lct_wp_admin_all() ) {
			add_filter( 'acf/get_field_group', [ $this, 'update_field_group' ], 5 );
		}


		//if ( lct_wp_admin_non_ajax() ) {}


		if ( lct_ajax_only() ) {
			/**
			 * actions
			 */
			add_action( 'acf/render_field', [ $this, 'render_field_viewonly' ], 20 );


			/**
			 * filters
			 */
			add_filter( 'acf/prepare_field', [ $this, 'prepare_field_access_primary' ], 5 ); //We don't want to alter the class on the field editing page or anywhere on the back-end
		}
	}


	/**
	 * DO NOT USE: use 'direct_current_user_can_edit' instead
	 * Takes an array of roles/caps and lets us know if the current user can do at least one of them
	 * Default is that they will have access
	 *
	 * @param bool         $has_access
	 * @param array|string $r_n_c
	 *
	 * @return bool
	 * @since    5.28
	 * @verified 2017.06.09
	 */
	function current_user_can_access( $has_access = true, $r_n_c = [] )
	{
		if ( ! empty( $r_n_c ) ) {
			//Set as array if it is a string
			if ( ! is_array( $r_n_c ) ) {
				$r_n_c = explode( ',', $r_n_c );
			}

			//Set as array if it is a string
			if ( ! is_array( $r_n_c ) ) {
				$r_n_c = explode( ' ', $r_n_c );
			}


			/**
			 * Default is that they will have access
			 */
			$has_role_cap_prefixes = false;
			$can_access            = true;


			/**
			 * first check and see if we are filtering by role_cap
			 */
			foreach ( $r_n_c as $k => $item ) {
				//If the item has a role/cap prefix
				if ( strpos_array( $item, lct_get_role_cap_prefixes_only() ) !== false ) {
					$has_role_cap_prefixes = true;
					$can_access            = false; //Set to false, so we can restrict access until they are a member of one of the role/caps


					//Remove the item if it does NOT have a role/cap prefix
				} else {
					unset( $r_n_c[ $k ] );
				}
			}


			/**
			 * Continue with the checking process if there are any role/cap items
			 */
			if (
				$has_role_cap_prefixes
				&& ! empty( $r_n_c )
			) {
				foreach ( $r_n_c as $role ) {
					$role = str_replace( lct_get_role_cap_prefixes_only(), '', $role );


					if ( current_user_can( $role ) ) {
						$can_access = true;


						break;
					}
				}
			}


			/**
			 * We have to explicitly set this to true or false. Just in case
			 */
			if ( $can_access ) {
				$has_access = true;
			} else {
				$has_access = false;
			}
		}


		return $has_access;
	}


	/**
	 * Takes an array of roles/caps and lets us know if the current user can do at least one of them
	 * Default is that they will have access
	 *
	 * @param bool         $has_access
	 * @param array|string $r_n_c
	 *
	 * @return bool
	 * @since        2017.34
	 * @verified     2017.06.09
	 */
	function direct_current_user_can_edit( $has_access = true, $r_n_c = [] )
	{
		if ( ! empty( $r_n_c ) ) {
			//Set as array if it is a string
			if ( ! is_array( $r_n_c ) ) {
				$r_n_c = explode( ',', $r_n_c );
			}

			//Set as array if it is a string
			if ( ! is_array( $r_n_c ) ) {
				$r_n_c = explode( ' ', $r_n_c );
			}


			/**
			 * Set access to 'no' until we check their roles & caps
			 */
			$can_access = false;


			/**
			 * Checking process if there are any role/cap items
			 */
			foreach ( $r_n_c as $role ) {
				if ( current_user_can( $role ) ) {
					$can_access = true;


					break;
				}
			}


			/**
			 * We have to explicitly set this to true or false. Just in case
			 */
			if ( $can_access ) {
				$has_access = true;
			} else {
				$has_access = false;
			}
		}


		return $has_access;
	}


	/**
	 * DO NOT USE: use 'direct_current_user_can_view' instead
	 * Takes an array of roles/caps and lets us know if the current user can do at least one of them, in view only mode
	 * Default is that they will NOT have access
	 *
	 * @param bool              $has_access
	 * @param array|string|null $r_n_c
	 *
	 * @return bool
	 * @since    5.28
	 * @verified 2017.06.09
	 */
	function current_user_can_view( $has_access = false, $r_n_c = null )
	{
		if ( ! empty( $r_n_c ) ) {
			//Set as array if it is a string
			if ( ! is_array( $r_n_c ) ) {
				$r_n_c = explode( ',', $r_n_c );
			}

			//Set as array if it is a string
			if ( ! is_array( $r_n_c ) ) {
				$r_n_c = explode( ' ', $r_n_c );
			}


			/**
			 * Default is that they will have access
			 */
			$has_role_cap_prefixes = false;
			$can_access            = false;


			/**
			 * first check and see if we are filtering by role_cap
			 */
			foreach ( $r_n_c as $k => $item ) {
				//If the item has a role/cap prefix
				if (
					strpos_array( $item, lct_get_role_cap_prefixes_only() ) !== false
					&& strpos( $item, 'viewonly_' ) !== false
				) {
					$has_role_cap_prefixes = true;


					//Remove the item if it does NOT have a role/cap prefix
				} else {
					unset( $r_n_c[ $k ] );
				}
			}


			/**
			 * Continue with the checking process if there are any role/cap items
			 */
			if (
				$has_role_cap_prefixes
				&& ! empty( $r_n_c )
			) {
				foreach ( $r_n_c as $role ) {
					$role = str_replace( array_merge( lct_get_role_cap_prefixes_only(), [ 'viewonly_' ] ), '', $role );


					if ( current_user_can( $role ) ) {
						$can_access = true;


						break;
					}
				}
			}


			/**
			 * We have to explicitly set this to true or false. Just in case
			 */
			if ( $can_access ) {
				$has_access = true;
			} else {
				$has_access = false;
			}
		}


		return $has_access;
	}


	/**
	 * Takes an array of roles/caps and lets us know if the current user can do at least one of them, in view only mode
	 * Default is that they will NOT have access
	 *
	 * @param bool              $has_access
	 * @param array|string|null $r_n_c
	 *
	 * @return bool
	 * @since        2017.34
	 * @verified     2017.06.09
	 */
	function direct_current_user_can_view( $has_access = false, $r_n_c = null )
	{
		if ( ! empty( $r_n_c ) ) {
			//Set as array if it is a string
			if ( ! is_array( $r_n_c ) ) {
				$r_n_c = explode( ',', $r_n_c );
			}

			//Set as array if it is a string
			if ( ! is_array( $r_n_c ) ) {
				$r_n_c = explode( ' ', $r_n_c );
			}


			/**
			 * Default is that they will have access
			 */
			$can_access = false;


			/**
			 * Checking process if there are any role/cap items
			 */
			foreach ( $r_n_c as $role ) {
				if ( current_user_can( $role ) ) {
					$can_access = true;


					break;
				}
			}


			/**
			 * We have to explicitly set this to true or false. Just in case
			 */
			if ( $can_access ) {
				$has_access = true;
			} else {
				$has_access = false;
			}
		}


		return $has_access;
	}


	/**
	 * Use the class field to make menu items conditional
	 *
	 * @param $items
	 *
	 * @unused   param $args
	 * @return string
	 * @since    2017.34
	 * @verified 2020.01.15
	 */
	function wp_nav_menu_objects( $items )
	{
		if ( ! empty( $items ) ) {
			$disabled_parents = [];


			foreach ( $items as $k => $item ) {
				/**
				 * Check if this item is a child of a disabled parent
				 */
				if (
					! empty( $disabled_parents )
					&& $item->menu_item_parent
					&& in_array( $item->menu_item_parent, $disabled_parents )
				) {
					unset( $items[ $k ] );

					continue;
				}


				if ( apply_filters( 'lct/access/wp_nav_menu_objects/pre_check_unset', null, $item ) !== null ) {
					$disabled_parents[] = $item->ID;

					unset( $items[ $k ] );

					continue;
				}


				/**
				 * Check if this item should be unset based on front_access settings
				 */
				if (
					lct_plugin_active( 'acf' )
					&& lct_acf_get_option_raw( 'enable_nav_item_restrictions' )
					&& (
						(
							$item->object
							&& $item->object === zxza( '-archive' )
							&& ! $this->check_restrictions_by_post_type( $item->type )
						)
						|| (
							$item->type === 'taxonomy'
							&& ! $this->check_restrictions_by_taxonomy( get_term( $item->object_id ) )
						)
						|| (
							$item->object_id
							&& ! $this->check_restrictions_by_post_id( $item->object_id )
						)
					)
				) {
					$disabled_parents[] = $item->ID;

					unset( $items[ $k ] );

					continue;
				}


				/**
				 * Check if this item should be unset based on user_logged_in status
				 */
				if ( lct_check_user_logged_in_of_class( $item->classes ) ) {
					$disabled_parents[] = $item->ID;

					unset( $items[ $k ] );

					continue;
				}


				/**
				 * Check if this item is for a particular role
				 */
				foreach ( lct_get_role_cap_prefixes_only() as $role ) {
					if ( strpos_array( $item->classes, $role, true ) !== false ) {
						if ( lct_check_role_of_class( $item->classes ) ) {
							$disabled_parents[] = $item->ID;

							unset( $items[ $k ] );
						} else {
							$items[ $k ]->classes = lct_cleanup_role_classes_array( $item->classes, $role );
						}
					}
				}
			}
		}


		return $items;
	}


	/**
	 * Check if the user has access to their request
	 *
	 * @param $q
	 *
	 * @since    2017.34
	 * @verified 2017.04.27
	 */
	function pre_get_posts_front_access( $q )
	{
		/*
		if (
			! is_admin() &&			$q->is_main_query() &&			! $q->is_404
		) {
			$this->remove_pre_get_posts_front_access();
		}
		*/
	}


	/**
	 * Remove the pre_get_posts_front_access pre_get_posts hook
	 *
	 * @since    2017.34
	 * @verified 2017.04.27
	 */
	function remove_pre_get_posts_front_access()
	{
		remove_action( 'pre_get_posts', [ $this, 'pre_get_posts_front_access' ] );
	}


	/**
	 * Check if the user has access to their request
	 * Default is they are allowed access, until they are explicitly denied access
	 * //TODO: cs - Add column in taxonomy list and post/page list letting us know the status of each page - 4/28/2017 4:21 PM
	 * //https://www.ractoon.com/2016/11/wordpress-custom-sortable-admin-columns-for-custom-posts/
	 * //TODO: cs - Add to bulk edit - 4/28/2017 4:22 PM
	 * //TODO: cs - Add filters for get_posts and other loops - 4/28/2017 4:24 PM
	 *
	 * @since    2017.34
	 * @verified 2017.05.09
	 */
	function template_redirect_front_access()
	{
		global $wp_query;

		$allow_access = true;


		/**
		 * Pages & Posts
		 */
		if (
			$wp_query->is_page
			|| $wp_query->is_single
		) {
			$allow_access = $this->check_restrictions_by_post_id( $wp_query->queried_object_id );


			/**
			 * Category Pages
			 */
		} elseif ( $wp_query->is_category ) {
			$taxonomy = get_taxonomy( 'category' );


			if ( ! empty( $taxonomy->object_type ) ) {
				foreach ( $taxonomy->object_type as $post_type ) {
					$allow_access = $this->check_restrictions_by_post_type( $post_type );


					if ( ! $allow_access ) {
						break;
					}
				}
			}


			/**
			 * Check Taxonomy Restrictions
			 */
			if ( $allow_access ) {
				$allow_access = $this->check_restrictions_by_taxonomy( $wp_query->queried_object );
			}


			/**
			 * Taxonomy Pages
			 */
		} elseif ( $wp_query->is_tax ) {
			if ( $wp_query->get( 'taxonomy' ) ) {
				$taxonomy = get_taxonomy( $wp_query->get( 'taxonomy' ) );


				if ( ! empty( $taxonomy->object_type ) ) {
					foreach ( $taxonomy->object_type as $post_type ) {
						$allow_access = $this->check_restrictions_by_post_type( $post_type );


						if ( ! $allow_access ) {
							break;
						}
					}
				}
			}


			/**
			 * Check Taxonomy Restrictions
			 */
			if ( $allow_access ) {
				$allow_access = $this->check_restrictions_by_taxonomy( $wp_query->queried_object );
			}


			/**
			 * Archive Pages
			 */
		} elseif ( $wp_query->is_archive ) {
			if ( $wp_query->get( 'post_type' ) ) {
				$allow_access = $this->check_restrictions_by_post_type( $wp_query->get( 'post_type' ) );
			}


			/**
			 * 404
			 */
		} elseif ( $wp_query->is_404 ) {
			$allow_access = false;
		}


		if ( ! $allow_access ) {
			$wp_query->set_404();


			/**
			 * @date     0.0
			 * @since    2017.34
			 * @verified 2021.08.27
			 */
			do_action( 'lct/template_redirect_front_access/404' );
		}
	}


	/**
	 * Check if the user has access to the page they are requesting
	 * Default is they are allowed access, until they are explicitly denied access
	 *
	 * @param $post_id
	 *
	 * @return bool
	 * @since    2017.34
	 * @verified 2018.08.23
	 */
	function check_restrictions_by_post_id( $post_id )
	{
		$allow_access = true;


		if (
			$post_id
			&& lct_plugin_active( 'acf' )
		) {
			/**
			 * Check Post Type Restrictions First
			 */
			if ( $post_type = get_post_type( $post_id ) ) {
				$allow_access = $this->check_restrictions_by_post_type( $post_type );
			}


			/**
			 * Check Taxonomy Restrictions First
			 */
			if (
				$allow_access
				&& (
					lct_acf_get_option_raw( 'enable_taxonomy_restrictions' )
					|| lct_acf_get_option_raw( 'enable_single_taxonomy_restrictions' )
				)
			) {
				if ( ( $restricted_taxonomies = lct_get_setting( 'check_restrictions_by_post_id_restricted_taxonomies' ) ) === null ) {
					$should_check          = false;
					$restricted_taxonomies = [];


					if (
						lct_acf_get_option_raw( 'enable_taxonomy_restrictions' )
						&& ( $taxonomy_restrictions = lct_acf_get_option( 'taxonomy_restrictions' ) )
					) {
						foreach ( $taxonomy_restrictions as $taxonomy_restriction ) {
							$restricted_taxonomies[] = $taxonomy_restriction['taxonomy'];
						}
					}


					if (
						lct_acf_get_option_raw( 'enable_single_taxonomy_restrictions' )
						&& ( $taxonomy_restrictions = lct_acf_get_option( 'single_taxonomy_restrictions' ) )
					) {
						foreach ( $taxonomy_restrictions as $taxonomy_restriction ) {
							$restricted_taxonomies[] = $taxonomy_restriction;
						}
					}


					if ( ! empty( $restricted_taxonomies ) ) {
						$restricted_taxonomies = array_unique( $restricted_taxonomies );


						foreach ( $restricted_taxonomies as $restricted_taxonomy ) {
							if (
								$post_type
								&& ( $tax_obj = get_taxonomy( $restricted_taxonomy ) )
								&& ! empty( $tax_obj->object_type )
								&& in_array( $post_type, $tax_obj->object_type )
							) {
								$should_check = true;


								break;
							}
						}
					}


					lct_update_setting( 'check_restrictions_by_post_id_should_check', $should_check );
					lct_update_setting( 'check_restrictions_by_post_id_restricted_taxonomies', $restricted_taxonomies );
				}


				if (
					lct_get_setting( 'check_restrictions_by_post_id_should_check' )
					&& ( $terms = wp_get_post_terms( $post_id, $restricted_taxonomies ) )
				) {
					$allow_access = $this->check_restrictions_by_taxonomy( $terms );
				}
			}


			/**
			 * Check Post Restrictions
			 */
			if (
				$allow_access
				&& $post_type
				&& (
					lct_acf_get_option_raw( 'enable_page_post_restrictions' )
					&& in_array( $post_type, [ 'page', 'post' ] )
				)
				|| (
					lct_acf_get_option_raw( 'enable_single_post_type_restrictions' )
					&& ( $post_type_restrictions = lct_acf_get_option( 'single_post_type_restrictions' ) )
					&& in_array( $post_type, $post_type_restrictions )
				)
			) {
				/**
				 * Restrict by Login Status
				 */
				if (
					$allow_access
					&& ! current_user_can( 'administrator' )
					&& get_field( zxzacf( 'is_allowed_login' ), $post_id )
				) {
					$allowed_login      = get_field( zxzacf( 'allowed_login' ), $post_id );
					$allow_access_login = false;


					if (
						(
							is_user_logged_in()
							&& $allowed_login
						)
						|| (
							! is_user_logged_in()
							&& ! $allowed_login
						)
					) {
						$allow_access_login = true;
					}


					if ( ! $allow_access_login ) {
						$allow_access = false;
					}
				}


				/**
				 * Restrict by role
				 */
				if (
					$allow_access
					&& ! current_user_can( 'administrator' )
					&& get_field( zxzacf( 'is_allowed_role' ), $post_id )
					&& $allowed_roles = get_field( zxzacf( 'allowed_role' ), $post_id, false )
				) {
					$allow_access_role = false;


					if ( is_user_logged_in() ) {
						foreach ( $allowed_roles as $allowed_role ) {
							if ( current_user_can( $allowed_role ) ) {
								$allow_access_role = true;
							}
						}
					}


					if ( ! $allow_access_role ) {
						$allow_access = false;
					}
				}


				/**
				 * Restrict by capability
				 */
				if (
					$allow_access
					&& ! current_user_can( 'administrator' )
					&& get_field( zxzacf( 'is_allowed_cap' ), $post_id )
					&& $allowed_caps = get_field( zxzacf( 'allowed_cap' ), $post_id, false )
				) {
					$allow_access_cap = false;


					if ( is_user_logged_in() ) {
						foreach ( $allowed_caps as $allowed_cap ) {
							if ( current_user_can( $allowed_cap ) ) {
								$allow_access_cap = true;
							}
						}
					}


					if ( ! $allow_access_cap ) {
						$allow_access = false;
					}
				}
			}
		}


		return $allow_access;
	}


	/**
	 * Check if the user has access to their request
	 * Default is they are allowed access, until they are explicitly denied access
	 * //TODO: cs - Add an ACF field to exclude certain pages from being checked - 4/28/2017 10:34 AM
	 *
	 * @param $post_type
	 *
	 * @return bool
	 * @since        2017.34
	 * @verified     2017.04.28
	 */
	function check_restrictions_by_post_type( $post_type )
	{
		$allow_access = true;


		if (
			$post_type
			&& lct_plugin_active( 'acf' )
			&& lct_acf_get_option_raw( 'enable_post_type_restrictions' )
			&& $post_type_restrictions = lct_acf_get_option( 'post_type_restrictions' )
		) {
			$settings = [];


			foreach ( $post_type_restrictions as $post_type_restriction ) {
				if ( $post_type === $post_type_restriction['post_type'] ) {
					$settings = $post_type_restriction;

					break;
				}
			}


			if ( ! empty( $settings ) ) {
				/**
				 * Restrict by Login Status
				 */
				if (
					! current_user_can( 'administrator' )
					&& $settings[ zxzacf( 'is_allowed_login' ) ]
				) {
					$allowed_login      = $settings[ zxzacf( 'allowed_login' ) ];
					$allow_access_login = false;


					if (
						(
							is_user_logged_in()
							&& $allowed_login
						)
						|| (
							! is_user_logged_in()
							&& ! $allowed_login
						)
					) {
						$allow_access_login = true;
					}


					if ( ! $allow_access_login ) {
						$allow_access = false;
					}
				}


				/**
				 * Restrict by role
				 */
				if (
					$allow_access
					&& ! current_user_can( 'administrator' )
					&& $settings[ zxzacf( 'is_allowed_role' ) ]
					&& $allowed_roles = $settings[ zxzacf( 'allowed_role' ) ]
				) {
					$allow_access_role = false;


					if ( is_user_logged_in() ) {
						foreach ( $allowed_roles as $allowed_role ) {
							if ( current_user_can( $allowed_role ) ) {
								$allow_access_role = true;
							}
						}
					}


					if ( ! $allow_access_role ) {
						$allow_access = false;
					}
				}


				/**
				 * Restrict by capability
				 */
				if (
					$allow_access
					&& ! current_user_can( 'administrator' )
					&& $settings[ zxzacf( 'is_allowed_cap' ) ]
					&& $allowed_caps = $settings[ zxzacf( 'allowed_cap' ) ]
				) {
					$allow_access_cap = false;


					if ( is_user_logged_in() ) {
						foreach ( $allowed_caps as $allowed_cap ) {
							if ( current_user_can( $allowed_cap ) ) {
								$allow_access_cap = true;
							}
						}
					}


					if ( ! $allow_access_cap ) {
						$allow_access = false;
					}
				}
			}
		}


		return $allow_access;
	}


	/**
	 * Check if the user has access to their request
	 * Default is they are allowed access, until they are explicitly denied access
	 * //TODO: cs - Add an ACF field to exclude certain terms from being checked - 4/28/2017 10:34 AM
	 *
	 * @param $terms
	 *
	 * @return bool
	 * @since        2017.34
	 * @verified     2023.11.07
	 */
	function check_restrictions_by_taxonomy( $terms )
	{
		$allow_access = true;


		if (
			empty( $terms )
			|| ! lct_plugin_active( 'acf' )
		) {
			return $allow_access;
		}


		if ( ! is_array( $terms ) ) {
			$terms = [ $terms ];
		}


		if (
			lct_acf_get_option_raw( 'enable_taxonomy_restrictions' )
			&& ( $taxonomy_restrictions = lct_acf_get_option( 'taxonomy_restrictions' ) )
		) {
			foreach ( $terms as $term ) {
				$settings = [];


				foreach ( $taxonomy_restrictions as $taxonomy_restriction ) {
					if ( $term->taxonomy === $taxonomy_restriction['taxonomy'] ) {
						$settings = $taxonomy_restriction;

						break;
					}
				}


				if ( ! empty( $settings ) ) {
					/**
					 * Restrict by Login Status
					 */
					if (
						! current_user_can( 'administrator' )
						&& $settings[ zxzacf( 'is_allowed_login' ) ]
					) {
						$allowed_login      = $settings[ zxzacf( 'allowed_login' ) ];
						$allow_access_login = false;


						if (
							(
								is_user_logged_in()
								&& $allowed_login
							)
							|| (
								! is_user_logged_in()
								&& ! $allowed_login
							)
						) {
							$allow_access_login = true;
						}


						if ( ! $allow_access_login ) {
							$allow_access = false;
						}
					}


					/**
					 * Restrict by role
					 */
					if (
						$allow_access
						&& ! current_user_can( 'administrator' )
						&& $settings[ zxzacf( 'is_allowed_role' ) ]
						&& $allowed_roles = $settings[ zxzacf( 'allowed_role' ) ]
					) {
						$allow_access_role = false;


						if ( is_user_logged_in() ) {
							foreach ( $allowed_roles as $allowed_role ) {
								if ( current_user_can( $allowed_role ) ) {
									$allow_access_role = true;
								}
							}
						}


						if ( ! $allow_access_role ) {
							$allow_access = false;
						}
					}


					/**
					 * Restrict by capability
					 */
					if (
						$allow_access
						&& ! current_user_can( 'administrator' )
						&& $settings[ zxzacf( 'is_allowed_cap' ) ]
						&& $allowed_caps = $settings[ zxzacf( 'allowed_cap' ) ]
					) {
						$allow_access_cap = false;


						if ( is_user_logged_in() ) {
							foreach ( $allowed_caps as $allowed_cap ) {
								if ( current_user_can( $allowed_cap ) ) {
									$allow_access_cap = true;
								}
							}
						}


						if ( ! $allow_access_cap ) {
							$allow_access = false;
						}
					}


					if ( ! $allow_access ) {
						break;
					}
				}
			}
		}


		if (
			$allow_access
			&& lct_acf_get_option_raw( 'enable_single_taxonomy_restrictions' )
			&& ( $taxonomy_restrictions = lct_acf_get_option( 'single_taxonomy_restrictions' ) )
		) {
			foreach ( $terms as $term ) {
				if ( ! in_array( $term->taxonomy, $taxonomy_restrictions ) ) {
					continue;
				}


				/**
				 * Restrict by Login Status
				 */
				if (
					! current_user_can( 'administrator' )
					&& get_term_meta( $term->term_id, 'lct:::is_allowed_login', true )
				) {
					$allowed_login      = get_field( zxzacf( 'allowed_login' ), lct_t( $term ) );
					$allow_access_login = false;


					if (
						(
							is_user_logged_in()
							&& $allowed_login
						)
						|| (
							! is_user_logged_in()
							&& ! $allowed_login
						)
					) {
						$allow_access_login = true;
					}


					if ( ! $allow_access_login ) {
						$allow_access = false;
					}
				}


				/**
				 * Restrict by role
				 */
				if (
					$allow_access
					&& ! current_user_can( 'administrator' )
					&& get_field( zxzacf( 'is_allowed_role' ), lct_t( $term ) )
					&& get_term_meta( $term->term_id, 'lct:::is_allowed_role', true )
					&& $allowed_roles = get_term_meta( $term->term_id, 'lct:::allowed_role', true )
				) {
					$allow_access_role = false;


					if ( is_user_logged_in() ) {
						foreach ( $allowed_roles as $allowed_role ) {
							if ( current_user_can( $allowed_role ) ) {
								$allow_access_role = true;
							}
						}
					}


					if ( ! $allow_access_role ) {
						$allow_access = false;
					}
				}


				/**
				 * Restrict by capability
				 */
				if (
					$allow_access
					&& ! current_user_can( 'administrator' )
					&& get_term_meta( $term->term_id, 'lct:::is_allowed_cap', true )
					&& $allowed_caps = get_term_meta( $term->term_id, 'lct:::allowed_cap', true )
				) {
					$allow_access_cap = false;


					if ( is_user_logged_in() ) {
						foreach ( $allowed_caps as $allowed_cap ) {
							if ( current_user_can( $allowed_cap ) ) {
								$allow_access_cap = true;
							}
						}
					}


					if ( ! $allow_access_cap ) {
						$allow_access = false;
					}
				}


				if ( ! $allow_access ) {
					break;
				}
			}
		}


		return $allow_access;
	}


	/**
	 * Add ACF fields to selected custom post_types
	 *
	 * @param array $field_group
	 *
	 * @return array
	 * @since    2017.34
	 * @verified 2019.03.11
	 */
	function update_field_group( $field_group )
	{
		if ( $field_group['key'] === lct_get_setting( 'acf_group_user_restriction_settings' ) ) {
			if (
				lct_acf_get_option_raw( 'enable_single_post_type_restrictions' )
				&& ( $objects = lct_acf_get_option( 'single_post_type_restrictions' ) )
			) {
				foreach ( $objects as $object ) {
					$field_group['location'][][] = [
						'operator' => '==',
						'param'    => 'post_type',
						'value'    => $object,
					];
				}
			}


			if (
				lct_acf_get_option_raw( 'enable_single_taxonomy_restrictions' )
				&& ( $objects = lct_acf_get_option( 'single_taxonomy_restrictions' ) )
			) {
				foreach ( $objects as $object ) {
					$field_group['location'][][] = [
						'operator' => '==',
						'param'    => 'taxonomy',
						'value'    => $object,
					];
				}
			}
		}


		return $field_group;
	}


	/**
	 * Check some stuff, including current_user_can(), so we can hide stuff from unauthorized people
	 *
	 * @param array $field
	 *
	 * @return array
	 * @since    0.0
	 * @verified 2023.09.07
	 */
	function prepare_field_access_primary( $field )
	{
		if (
			$field
			&& lct_acf_get_option_raw( 'enable_acf_field_restrictions' )
		) {
			if (
				! empty( $field['_name'] )
				&& str_starts_with( $field['_name'], 'afwp:::primer' )
			) {
				return $field;
			}


			$post_id_fields         = lct_get_later( 'acf_post_id_fields' );
			$post_id_field_groups   = lct_get_later( 'acf_post_id_field_groups' );
			$view_only_fields       = lct_get_later( 'acf_viewonly_fields' );
			$view_only_field_groups = lct_get_later( 'acf_viewonly_field_groups' );


			if (
				$post_id_fields
				&& (
					array_key_exists( $field['name'], $post_id_fields )
					|| (
						isset( $field['_name'] )
						&& array_key_exists( $field['_name'], $post_id_fields )
					)
				)
			) {
				if ( array_key_exists( $field['name'], $post_id_fields ) ) {
					$field['post_id'] = $post_id_fields[ $field['name'] ]['post_id'];
				} else {
					$field['post_id'] = $post_id_fields[ $field['_name'] ]['post_id'];
				}
			} elseif (
				$post_id_field_groups
				&& (
					array_key_exists( $field['name'], $post_id_field_groups )
					|| array_key_exists( $field['parent'], $post_id_field_groups )
					|| (
						isset( $field['_name'] )
						&& array_key_exists( $field['_name'], $post_id_field_groups )
					)
				)
			) {
				if ( array_key_exists( $field['name'], $post_id_field_groups ) ) {
					$field['post_id'] = $post_id_field_groups[ $field['name'] ]['post_id'];
				} elseif ( array_key_exists( $field['parent'], $post_id_field_groups ) ) {
					$field['post_id'] = $post_id_field_groups[ $field['parent'] ]['post_id'];
				} else {
					$field['post_id'] = $post_id_field_groups[ $field['_name'] ]['post_id'];
				}
			}


			if (
				$view_only_fields
				&& (
					in_array( $field['name'], $view_only_fields )
					|| (
						isset( $field['_name'] )
						&& in_array( $field['_name'], $view_only_fields )
					)
				)
			) {
				$field[ zxzu( 'viewonly' ) ] = true;
				$field['wrapper']['class']   .= ' ' . zxzu( 'acf_viewonly' );


			} elseif (
				$view_only_field_groups
				&& (
					in_array( $field['name'], $view_only_field_groups )
					|| (
						isset( $field['_name'] )
						&& in_array( $field['_name'], $view_only_field_groups )
					)
				)
			) {
				$field[ zxzu( 'viewonly' ) ] = true;
				$field['wrapper']['class']   .= ' ' . zxzu( 'acf_viewonly' );


			} elseif (
				(
					( $tmp = zxzu( 'roles_n_caps' ) )
					&& isset( $field[ $tmp ] )
					&& $field[ $tmp ]
				)
				|| (
					( $tmp = zxzu( 'roles_n_caps_viewonly' ) )
					&& isset( $field[ $tmp ] )
					&& $field[ $tmp ]
				)
			) {
				$current_user_can_edit_or_view = false;


				if (
					( $tmp = zxzu( 'roles_n_caps' ) )
					&& isset( $field[ $tmp ] )
					&& $field[ $tmp ]
					&& apply_filters( 'lct/direct_current_user_can_edit', true, $field[ $tmp ] )
				) {
					$current_user_can_edit_or_view = true;
				}


				/**
				 * Show a read only version if they are allowed to
				 */
				if (
					( $tmp = zxzu( 'roles_n_caps_viewonly' ) )
					&& isset( $field[ $tmp ] )
					&& $field[ $tmp ]
					&& ! $current_user_can_edit_or_view
					&& apply_filters( 'lct/direct_current_user_can_view', false, $field[ $tmp ] )
				) {
					$current_user_can_edit_or_view = true;


					$field[ zxzu( 'viewonly' ) ] = true;
					$field['wrapper']['class']   .= ' ' . zxzu( 'acf_viewonly' );
				}


				/**
				 * Return nothing if they don't need to see it
				 */
				if ( ! $current_user_can_edit_or_view ) {
					//TODO: cs - Instead of just hiding this we can have someone just not even produce the HTML for it - 4/29/2017 4:10 PM
					//return []; //we can only do this if it is in an LCT instant form
					$field[ zxzu( 'current_user_can_not_access' ) ] = true;
					$field['wrapper']['class']                      .= ' ' . zxzu( 'current_user_can_not_access' );
				}


				/**
				 * Older way of doing things that may still be in action
				 * If you are using it replace with the method above
				 */
			} elseif ( $field['wrapper']['class'] ) {
				if ( ! apply_filters( 'lct/current_user_can_access', true, $field['wrapper']['class'] ) ) {
					/**
					 * Show a read only version if they are allowed to
					 */
					if ( apply_filters( 'lct/current_user_can_view', false, $field['wrapper']['class'] ) ) {
						$field[ zxzu( 'viewonly' ) ] = true;
						$field['wrapper']['class']   .= ' ' . zxzu( 'acf_viewonly' );


						/**
						 * Return nothing if they don't need to see it
						 */
					} else {
						//TODO: cs - Instead of just hiding this we can have someone just not even produce the HTML for it - 4/29/2017 4:10 PM
						//return []; //we can only do this if it is in an LCT instant form
						$field[ zxzu( 'current_user_can_not_access' ) ] = true;
						$field['wrapper']['class']                      .= ' ' . zxzu( 'current_user_can_not_access' );
					}
				}
			}
		}


		return $field;
	}


	/**
	 * Show the value as a read only
	 * //TODO: cs - Need to make this way better - 4/30/2017 9:00 PM
	 *
	 * @param $field
	 *
	 * @since    2017.34
	 * @verified 2024.08.19
	 */
	function render_field_viewonly( $field )
	{
		/**
		 * Vars
		 */
		$viewonly          = zxzu( 'viewonly' );
		$already_processed = lct_get_later( 'acf_render_field_viewonly_fields', '', [] );


		/**
		 * Check for nested fields
		 */
		if (
			! empty( $field[ $viewonly ] )
			&& ( $tmp_field_obj = afwp_acf_get_field_object( $field['key'], false, false, false ) )
			&& $field['type'] !== $tmp_field_obj['type']
		) {
			return;
		}


		if ( ! ( $acf_display_form_active = lct_get_setting( 'acf_display_form_active' ) ) ) {
			lct_update_setting( 'acf_display_form_active', true );
		}


		if (
			$field['type'] === 'repeater'
			&& ! empty( $field[ $viewonly ] )
		) {
			lct_append_later( 'acf_render_field_viewonly_fields', $field['key'] );
		} elseif (
			! empty( $field[ $viewonly ] )
			&& ! empty( $field['key'] )
			&& $field['key'] !== '_validate_email'
			&& (
				! in_array( $field['key'], $already_processed )
				|| (
					in_array( $field['key'], $already_processed )
					&& $field['key'] === 'select'
				)
				|| (
					! empty( $field['parent'] )
					&& ! acf_is_field_group_key( $field['parent'] )
					&& ! in_array( $field['id'], $already_processed )
				)
			)
		) {
			if (
				! empty( $field['value'] )
				&& is_array( $field['value'] )
				&& count( $field['value'] ) === 1
			) {
				$field['value'] = reset( $field['value'] );
			}


			lct_append_later( 'acf_render_field_viewonly_fields', $field['key'] );


			if (
				! empty( $field['parent'] )
				&& ! acf_is_field_group_key( $field['parent'] )
			) {
				lct_append_later( 'acf_render_field_viewonly_fields', $field['id'] );
			}


			echo lct_acf_format_value( $field['value'], lct_get_field_post_id( $field ), $field );


			$js_base     = '[data-key="' . $field['key'] . '"] .acf-input';
			$js_selector = $js_base . ' input, ' . $js_base . ' select, ' . $js_base . ' textarea';

			if (
				! empty( $field['type'] )
				&& $field['type'] === 'number'
			) {
				echo '<script>setTimeout( function() { jQuery( \'' . $js_selector . '\').attr( \'disabled\', true ); }, 2000 );</script>';
			} elseif (
				! empty( $field['type'] )
				&& $field['type'] !== 'repeater'
			) {
				echo '<script>jQuery( \'' . $js_selector . '\').attr( \'disabled\', true );</script>';
			}
		} elseif (
			acf_is_field_key( $field['parent'] )
			&& ( $parent_field = get_field_object( $field['parent'], false, false, false ) )
			&& ( $parent_field = acf_prepare_field( $parent_field ) )
			&& isset( $parent_field[ $viewonly ] )
		) {
			echo '<span class="lct_render_field_viewonly">';
			echo lct_acf_format_value( $field['value'], lct_get_field_post_id( $field ), $field );
			echo '</span>';
		}


		if ( ! $acf_display_form_active ) {
			lct_update_setting( 'acf_display_form_active', null );
		}
	}


	/**
	 * [lct_current_user_can]
	 * Sometimes you just need to know if the current_user_can() anywhere.
	 *
	 * @att      array cap (WP capabilities)
	 *
	 * @param array $a
	 *
	 * @return string
	 * @since    5.28
	 * @verified 2017.04.29
	 */
	function current_user_can( $a )
	{
		/**
		 * set the default atts
		 */
		$a = shortcode_atts(
			[
				'r'   => '',
				'cap' => [],
			],
			$a
		);


		if ( empty( $a['cap'] ) ) {
			return false;
		}


		$a['r']   = explode( ',', $a['cap'] );
		$can_view = 0;


		foreach ( $a['r'] as $cap_key => $cap ) {
			$role = str_replace( lct_get_role_cap_prefixes_only(), '', $cap );

			$a['r'][ $cap_key ] = $role;

			if ( current_user_can( $role ) ) {
				$can_view ++;
			}
		}


		if ( ! $can_view ) {
			$a['r'][] = zxzu( 'current_user_can_not_access' );
		}


		$a['r'] = implode( ' ', $a['r'] );


		return $a['r'];
	}


	/**
	 * This allows the Varnish front-end to show the admin bar which we use pretty heavily
	 *
	 * @param $logged_in_cookie
	 * @param $expire
	 *
	 * @unused    param $expiration
	 * @unused    param $user_id
	 * @unused    param $logged_in
	 * @since     2017.95
	 * @verified  2018.04.05
	 */
	function varnish_set_2nd_logged_in_cookie( $logged_in_cookie, $expire )
	{
		if (
			defined( 'LCT_ENABLE_VARNISH_CACHE' )
			&& defined( 'LCT_WP_LIVE_URL_ROOT' )
			&& LCT_ENABLE_VARNISH_CACHE
		) {
			$domain = preg_replace( '/www\./', '', LCT_WP_LIVE_URL_ROOT, 1 );


			setcookie( LOGGED_IN_COOKIE, $logged_in_cookie, $expire, COOKIEPATH, $domain, true, true );
		}
	}
}
