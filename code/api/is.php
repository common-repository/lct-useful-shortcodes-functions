<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Checks if the value is in the page's permalink
 * Created for use in widget logic plugin, but you can use anywhere
 *
 * @param $search_this_in_url
 *
 * @return bool
 * @since    4.2.2.20
 * @verified 2016.12.17
 */
function lct_is_in_url( $search_this_in_url )
{
	$r = false;


	if (
		! empty( $search_this_in_url )
		&& strpos( get_permalink(), $search_this_in_url ) !== false
	) {
		$r = true;
	}


	return $r;
}


/**
 * Check if the page you are on is a page or subpage of the specified $page_id
 *
 * @param $page_id
 *
 * @return bool
 * @since    5.24
 * @verified 2016.12.17
 */
function lct_is_in_page( $page_id )
{
	global $post;

	$r = false;


	if (
		! empty( $page_id )
		&& $post
	) {
		$post_parent = $post->post_parent;


		if ( is_page( $page_id ) ) {
			$r = true;


		} else {
			while( $post_parent != 0 ) {
				if (
					is_page()
					&& $post_parent == $page_id
				) {
					$r = true;
					break;
				}


				$new_post    = get_post( $post_parent );
				$post_parent = $new_post->post_parent;
			}
		}


	}


	return $r;
}


/**
 * Check if this the post is brand new or not
 *
 * @param $post_id
 *
 * @return bool
 * @since    5.38
 * @verified 2019.04.08
 */
function lct_is_new_save_post( $post_id )
{
	$r = false;


	if (
		! empty( $post_id )
		&& ! ( $r = lct_get_later( $post_id, 'is_new_save_post', false ) )
		&& is_numeric( $post_id )
		&& ( $post = get_post( $post_id ) )
		&& is_object( $post )
		&& $post->post_modified_gmt
		&& $post->post_modified_gmt === $post->post_date_gmt
	) {
		$r = true;


		lct_update_later( $post_id, $r, 'is_new_save_post' );
	}


	return $r;
}


/**
 * Check is a variable contains HTML
 *
 * @param $var
 *
 * @return bool
 * @since    5.40
 * @verified 2016.12.17
 */
function lct_is_html( $var )
{
	$r = false;


	if ( $var != strip_tags( $var ) ) {
		$r = true;
	}


	return $r;
}


/**
 * Check if a page is a blogroll or single post.
 *
 * @return bool
 * @since    6.2
 * @verified 2016.12.17
 */
function lct_is_blog()
{
	global $post;

	$r         = false;
	$post_type = get_post_type( $post );


	if ( $post_type == 'post' ) {
		if (
			is_home()
			|| is_archive()
			|| is_single()
		) {
			$r = true;
		}
	}


	return $r;
}


/**
 * Check if the logged in user is a dev based on their email address on file.
 *
 * @param null $emails
 *
 * @return bool
 * @since    4.2.2.24
 * @verified 2018.03.05
 */
function lct_is_user_a_dev( $emails = null )
{
	/**
	 * Return early if cache is found
	 */
	$cache_key = lct_cache_key( compact( 'emails' ) );
	if ( lct_isset_cache( $cache_key ) ) {
		return lct_get_cache( $cache_key );
	}


	$r = false;


	/**
	 * Is the user logged in
	 */
	if ( is_user_logged_in() ) {
		$current_user = wp_get_current_user();


		/**
		 * Set the default emails array, if one is not specified
		 */

		if ( empty( $emails ) ) {
			$emails = lct_acf_get_dev_emails();
		}


		/**
		 * Check if the user is in the allowed list
		 */
		if ( in_array( $current_user->user_email, $emails ) ) {
			$r = true;
		}
	}


	/**
	 * Save the value to the cache
	 */
	lct_set_cache( $cache_key, $r );


	return $r;
}


/**
 * Get the extra content for thank you pages
 * Add any JS tracking scripts
 *
 * @param $content
 *
 * @return mixed
 * @since    5.36
 * @verified 2017.02.07
 */
function lct_is_thanks_page( $content )
{
	if ( ! $content ) {
		return $content;
	}


	if ( lct_plugin_active( 'acf' ) ) {
		$content = lct_acf_is_thanks_page( $content );
	}


	return $content;
}


/**
 * Simple check for validating a URL, it must start with http:// or https://.
 * and pass FILTER_VALIDATE_URL validation.
 *
 * @param string $url
 *
 * @return bool
 * @since    2017.3
 * @verified 2017.01.23
 */
function lct_is_valid_url( $url )
{
	$r = true;


	// Must start with http:// or https://
	if (
		0 !== strpos( $url, 'http://' )
		&& 0 !== strpos( $url, 'https://' )
	) {
		$r = false;
	}

	// Must pass validation
	if (
		$r
		&& ! filter_var( $url, FILTER_VALIDATE_URL )
	) {
		$r = false;
	}


	return $r;
}


/**
 * Check if a taxonomy exists by the slug
 *
 * @param $taxonomy_slug
 *
 * @return bool
 * @since    2017.78
 * @verified 2017.09.13
 */
function lct_taxonomy_exists_by_slug( $taxonomy_slug )
{
	global $wp_taxonomies;
	$r = false;


	if ( ! empty( $wp_taxonomies ) ) {
		foreach ( $wp_taxonomies as $wp_taxonomy ) {
			if (
				! empty( $wp_taxonomy->rewrite['slug'] )
				&& $wp_taxonomy->rewrite['slug'] === $taxonomy_slug
			) {
				$r = true;

				break;
			}
		}
	}


	return $r;
}


/**
 * Determine whether a taxonomy is a status taxonomy or not
 *
 * @param $taxonomy
 *
 * @return bool
 * @since    2017.96
 * @verified 2017.12.14
 */
function lct_is_status_taxonomy( $taxonomy )
{
	$r = false;


	if (
		$taxonomy
		&& (
			strpos( $taxonomy, '_status' ) !== false
			|| //If the taxonomy is a status taxonomy
			strpos( $taxonomy, 'status_' ) !== false
		)
	) {
		$r = true;
	}


	return $r;
}
