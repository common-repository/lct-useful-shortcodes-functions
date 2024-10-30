<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Get related posts by a custom post type category taxonomy.
 * Based off of: fusion_get_custom_posttype_related_posts()
 *
 * @param integer $post_id      Current post id.
 * @param integer $number_posts Number of posts to fetch.
 * @param string  $post_type    The custom post type that should be used.
 *
 * @return object                Object with posts info.
 * @since    7.56
 * @verified 2018.08.10
 */
function lct_fusion_get_custom_posttype_related_posts_team( $post_id = null, $number_posts = 20, $post_type = 'lct_team' )
{
	$post_id = lct_get_post_id( $post_id );
	$query   = new WP_Query();


	if ( 0 === $number_posts ) {
		return $query;
	}


	$args = [
		'ignore_sticky_posts' => 0,
		'posts_per_page'      => $number_posts,
		'post__not_in'        => [ $post_id ],
		'post_type'           => $post_type,
		'orderby'             => 'menu_order',
		'order'               => 'ASC',
	];


	// If placeholder images are disabled, add the _thumbnail_id meta key to the query to only retrieve posts with featured images.
	if ( ! Avada()->settings->get( 'featured_image_placeholder' ) ) {
		$args['meta_key'] = '_thumbnail_id';
	}


	if ( version_compare( lct_theme_version( 'Avada' ), '5.2', '<' ) ) //Avada older than v5.2
	{
		$query = avada_cached_query( $args );
	} else {
		$query = fusion_cached_query( $args );
	}


	return $query;
}
