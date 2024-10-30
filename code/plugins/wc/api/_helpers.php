<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Get an array of product_ids from an order
 *
 * @param $order
 *
 * @return array
 * @since    5.36
 * @verified 2016.11.19
 */
function lct_get_order_product_ids( $order )
{
	if ( is_int( $order ) ) {
		$order = new WC_Order( $order );
	}

	$product_ids = [];
	$items       = $order->get_items();


	foreach ( $items as $item ) {
		$product_ids[] = $item['product_id'];
	}


	return $product_ids;
}


/**
 * Get an array of product_id terms from an order
 *
 * @param $order
 *
 * @return array
 * @since    5.36
 * @verified 2016.11.19
 */
function lct_get_order_product_id_terms( $order )
{
	$terms       = [];
	$product_ids = lct_get_order_product_ids( $order );


	foreach ( $product_ids as $product_id ) {
		$product_terms = wc_get_product_terms( $product_id, 'product_cat' );


		foreach ( $product_terms as $product_term ) {
			$terms[] = $product_term->term_id;
		}
	}


	array_unique( $terms );


	return $terms;
}
