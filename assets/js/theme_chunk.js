/**
 * Examples
 jQuery( document ).ready( function() {

 jQuery( '.ajax' ).on( 'shown.bs.modal', function() {
 lct_theme_chunk_acf_do( 53212 );
 } );

 jQuery( '#lct_button_50042' ).click( function() {
 lct_theme_chunk_do( 50042 );
 } );


 jQuery( '#lct_button_50028' ).click( function() {
 lct_theme_chunk_do( 50028 );
 jQuery( '.modal_50028' ).modal( 'show' );
 } );

 } );


//###//


var lct_theme_chunk_vars = {};


/**
 * Get theme_chunk content
 */
function lct_theme_chunk_do( post_id, args ) {
	var $lct_theme_chunk_do = jQuery.noConflict();
	var update_id = '#lct_theme_chunk_' + post_id;
	var update_el = $lct_theme_chunk_do( update_id );

	//cleanup first
	update_el.removeData();
	update_el.html( '<h1 style="text-align: center;">LOADING...</h1>' );

	var content_type = update_el.data( 'content' );

	let afwp_root_post_id = null;
	if(
		typeof args !== 'undefined'
		&& typeof args.afwp_root_post_id !== 'undefined'
	) {
		afwp_root_post_id = args.afwp_root_post_id;
	}


	$lct_theme_chunk_do.ajax( {
		url: lct_theme_chunk.ajax_url,
		type: 'POST',
		dataType: 'json',
		data: {
			action: 'lct_theme_chunk',
			wpapi_nonce: lct_theme_chunk.wpapi_nonce,
			post_id: post_id,
			afwp_root_post_id: afwp_root_post_id,
			content_type: content_type,
			args: args,
			height: ($lct_theme_chunk_do( window ).height() - 210)
		}
	} ).done( function( resp ) {
		//console.log( resp.status );


		//Update the Modal title
		if( resp.title ) {
			update_el.closest( '.modal-content' ).find( '.modal-title' ).html( resp.title );
		}


		//Update the content
		update_el.html( resp.content );


	} ).fail( function( resp ) {
		console.log( 'Opps, that went bad! :: lct_theme_chunk_do()' );
		console.log( resp );


	} ).always( function( resp ) {
		//console.log( 'Complete' );
	} );
}


/**
 * Get theme_chunk content
 */
function lct_theme_chunk_acf_do( post_id, args ) {
	var $lct_theme_chunk_do = jQuery.noConflict();
	var update_id = '#lct_theme_chunk_' + post_id;
	var update_el = $lct_theme_chunk_do( update_id );


	//cleanup first
	update_el.removeData();
	update_el.html( '<h1 style="text-align: center;">LOADING...</h1>' );


	var content_type = update_el.data( 'content' );


	$lct_theme_chunk_do.ajax( {
		url: lct_theme_chunk.ajax_url,
		type: 'POST',
		dataType: 'json',
		data: {
			action: 'lct_theme_chunk',
			wpapi_nonce: lct_theme_chunk.wpapi_nonce,
			post_id: post_id,
			content_type: content_type,
			args: args,
			height: ($lct_theme_chunk_do( window ).height() - 210),
			lct_root_post_id: lct_theme_chunk.lct_root_post_id
		}
	} ).done( function( resp ) {
		//console.log( resp.status );


		//Update the Modal title
		if( resp.title ) {
			update_el.closest( '.modal-content' ).find( '.modal-title' ).html( resp.title );
		}


		//Update the content
		update_el.html( resp.content );


	} ).fail( function( resp ) {
		console.log( 'Opps, that went bad! :: lct_theme_chunk_acf_do()' );
		console.log( resp );


	} ).always( function( resp ) {
		//console.log( 'Complete' );
	} );
}
