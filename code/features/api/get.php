<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Derived from: get_category_by_path()
 * Retrieve category based on URL containing the category slug.
 * Breaks the $category_path parameter up to get the category slug.
 * Tries to find the child path and will return it. If it doesn't find a
 * match, then it will return the first category matching slug, if $full_match,
 * is set to false. If it does not, then it will return null.
 * It is also possible that it will return a WP_Error object on failure. Check
 * for it when using this function.
 *
 * @param string $category_path URL containing category slugs.
 * @param string $taxonomies
 * @param bool   $full_match    Optional. Whether full path should be matched.
 * @param string $output        Optional. Constant OBJECT, ARRAY_A, or ARRAY_N
 *
 * @return array|object|WP_Error Type is based on $output value.
 * @since    2.1.0
 * @since    5.40
 * @verified 2021.03.05
 */
function lct_get_taxonomy_by_path( $category_path, $taxonomies = 'all', $full_match = true, $output = OBJECT )
{
	if ( $taxonomies == 'all' ) {
		$taxonomies = get_taxonomies();
	} elseif ( ! is_array( $taxonomies ) ) {
		$taxonomies = [ $taxonomies ];
	}


	foreach ( $taxonomies as $taxonomy ) {
		$category_path  = rawurlencode( urldecode( $category_path ) );
		$category_path  = str_replace( '%2F', '/', $category_path );
		$category_path  = str_replace( '%20', ' ', $category_path );
		$category_paths = '/' . trim( $category_path, '/' );
		$leaf_path      = sanitize_title( basename( $category_paths ) );
		$category_paths = explode( '/', $category_paths );
		$full_path      = '';


		foreach ( (array) $category_paths as $pathdir ) {
			$full_path .= ( $pathdir != '' ? '/' : '' ) . sanitize_title( $pathdir );
		}


		$args       = [
			'taxonomy' => $taxonomy,
			'slug'     => $leaf_path,
			'get'      => 'all',
		];
		$categories = get_terms( $args );


		if ( empty( $categories ) ) {
			continue;
		}


		foreach ( $categories as $category ) {
			$path        = '/' . $leaf_path;
			$curcategory = $category;


			while(
				( $curcategory->parent != 0 )
				&& ( $curcategory->parent != $curcategory->term_id )
			) {
				$curcategory = get_term( $curcategory->parent, $taxonomy );


				if ( lct_is_wp_error( $curcategory ) ) {
					return $curcategory;
				}


				$path = '/' . $curcategory->slug . $path;
			}


			if ( $path === $full_path ) {
				return get_term( $category->term_id, $taxonomy, $output );
			}
		}


		// If full matching is not required, return the first cat that matches the leaf.
		if ( ! $full_match ) {
			return get_term( reset( $categories )->term_id, $taxonomy, $output );
		}
	}


	return null;
}


/**
 * Get the slug of a post
 *
 * @param int       $post_id
 * @param bool|true $slash
 *
 * @return string
 * @since    6.2
 * @verified 2019.01.23
 */
function lct_get_the_slug( $post_id = null, $slash = true )
{
	$r = [];


	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}


	$post_data = get_post( $post_id );


	if ( ! lct_is_wp_error( $post_data ) ) {
		$r[] = $post_data->post_name;


		if ( $slash ) {
			$r[] = '/';
		}
	}


	return lct_return( $r );
}


/**
 * Preps a list of site info that we care about
 *
 * @return string
 */
function lct_get_site_info()
{
	$site_info = [];


	$site_info[] = lct_get_site_info_post_types();

	$site_info[] = lct_get_site_info_taxonomies();

	$site_info[] = lct_get_site_info_roles();

	$site_info[] = lct_get_site_info_caps();


	return lct_return( $site_info );
}


/**
 * Preps a list of all post_types
 *
 * @return string
 * @since    0.0
 * @verified 2017.04.28
 */
function lct_get_site_info_post_types()
{
	$args       = [
		'_builtin' => false,
	];
	$post_types = get_post_types( $args );
	ksort( $post_types );


	$message   = [];
	$message[] = "<h2 style='color: green;font-weight: bold'>Post Types (post_types)</h2>";
	$message[] = '<ul style="padding-left: 20px;margin-top: 0;">';


	foreach ( $post_types as $post_type ) {
		$message[] = sprintf( '<li>%s</li>', $post_type );
	}


	$message[] = '</ul>';


	return lct_return( $message );
}


/**
 * Preps a list of all taxonomies
 *
 * @return string
 */
function lct_get_site_info_taxonomies()
{
	$message    = [];
	$taxonomies = get_taxonomies();


	if ( ! empty( $taxonomies ) ) {
		ksort( $taxonomies );


		$message[] = "<h2 style='color: green;font-weight: bold'>Taxonomies</h2>";

		$message[] = '<ul style="padding-left: 20px;margin-top: 0;">';

		foreach ( $taxonomies as $taxonomy ) {
			$message[] = sprintf( '<li>%s</li>', $taxonomy );
		}

		$message[] = '</ul>';
	}


	return lct_return( $message );
}


/**
 * Preps a list of all user roles
 *
 * @return string
 */
function lct_get_site_info_roles()
{
	$message = [];
	$roles   = get_editable_roles();


	if ( ! empty( $roles ) ) {
		ksort( $roles );
		unset( $roles['administrator'] );
		unset( $roles['author'] );
		unset( $roles['contributor'] );
		unset( $roles['editor'] );
		unset( $roles['subscriber'] );


		$message[] = "<h2 style='color: green;font-weight: bold'>Custom User Roles</h2>";

		$message[] = '<ul style="padding-left: 20px;margin-top: 0;">';

		if ( ! empty( $roles ) ) {
			foreach ( $roles as $role_key => $role ) {
				$message[] = sprintf( '<li>%s</li>', $role_key );
			}
		} else {
			$message[] = '<li>None</li>';
		}

		$message[] = '</ul>';
	}


	return lct_return( $message );
}


/**
 * Preps a list of all user capabilities
 * //TODO: cs - Come up with a way to filter this more. Maybe look at: user-role-editor - 3/5/2016 12:02 PM
 *
 * @return string
 */
function lct_get_site_info_caps()
{
	$message = [];
	$roles   = get_editable_roles();


	if ( ! empty( $roles['administrator']['capabilities'] ) ) {
		ksort( $roles['administrator']['capabilities'] );


		$message[] = "<h2 style='color: green;font-weight: bold'>User Capabilities</h2>";

		$message[] = '<ul style="padding-left: 20px;margin-top: 0;">';

		foreach ( $roles['administrator']['capabilities'] as $cap_key => $cap ) {
			$message[] = sprintf( '<li>%s</li>', $cap_key );
		}

		$message[] = '</ul>';
	}


	return lct_return( $message );
}


/**
 * Get a single value from a WP Term
 * Returns the value of the key you set. Or the whole term object is no key is set.
 * *
 * Keep $tax as {zxzu}option just in case an old site is still relying on it.
 *
 * @param      $term_id
 * @param      $tax
 * @param null $key
 *
 * @return array|bool|mixed|null|WP_Error|WP_Term
 */
function lct_get_term_value( $term_id, $tax = 'lct_option', $key = null )
{
	$term_value = false;


	if (
		! empty( $term_id )
		&& ! empty( $tax )
	) {
		$term = get_term( $term_id, $tax );


		if ( ! lct_is_wp_error( $term ) ) {
			if ( $key ) {
				$term_value = $term->$key;
			} else {
				$term_value = $term;
			}
		}
	}


	return $term_value;
}


/**
 * Get a single value from the parent WP Term of a WP Term
 * Returns the value of the key you set. Or the whole term object is no key is set.
 * *
 * Keep $tax as {zxzu}option just in case an old site is still relying on it.
 *
 * @param        $term_id
 * @param string $tax
 * @param null   $key
 *
 * @return array|bool|mixed|null|WP_Error|WP_Term
 */
function lct_get_parent_term_value( $term_id, $tax = 'lct_option', $key = null )
{
	$term_value = false;


	if (
		! empty( $term_id )
		&& ! empty( $tax )
	) {
		$term = get_term( $term_id, $tax );


		if (
			! lct_is_wp_error( $term )
			&& ! empty( $term->parent )
		) {
			$parent_term = get_term( $term->parent, $tax );


			if ( ! lct_is_wp_error( $parent_term ) ) {
				if ( $key ) {
					$term_value = $parent_term->$key;
				} else {
					$term_value = $parent_term;
				}
			}
		}
	}


	return $term_value;
}


/**
 * Get an array from a json response thru curl
 *
 * @param string $url
 * @param string $post JSON
 *
 * @return array
 * @since    7.1
 * @verified 2019.09.20
 */
function lct_get_json_thru_curl( $url, $post = null )
{
	$resp = '';


	if (
		function_exists( 'curl_init' )
		&& ! empty( $url )
	) {
		$request = curl_init();

		curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $request, CURLOPT_VERBOSE, false );
		curl_setopt( $request, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $request, CURLOPT_HEADER, true );
		curl_setopt( $request, CURLOPT_URL, $url );
		curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );

		if ( $post ) {
			curl_setopt( $request, CURLOPT_POSTFIELDS, $post );
		}

		$data        = curl_exec( $request );
		$header_size = curl_getinfo( $request, CURLINFO_HEADER_SIZE );
		$body        = substr( $data, $header_size );

		$resp = json_decode( $body, true );
	}


	return $resp;
}
