jQuery( document ).ready( function( $ ) {
	$( document ).on( 'keydown', '#lct-shortcode-attr-id', function( e ) {
		form = $( '#lct_internal_link-shortcode-ui-container' );
		term = $( this ).val();

		// catch everything except up/down arrow
		switch( e.which ) {
			case 27: // esc
				form.find( '.live_search_results ul' ).remove();
				break;


			case 13: // enter
			case 38: // up
			case 40: // down
				break;


			default:
				if( term == '' ) {
					form.find( '.live_search_results ul' ).remove();
				}

				if( term.length > 2 ) {
					$.post(
						lct_internal_link_ajax.ajax_url,
						{
							action: 'lct_internal_link_ajax',
							post_title: term
						},
						function( data ) {
							$( '#lct_internal_link-shortcode-ui-container div.live_search_results' ).html( data ).find( 'li' ).click( function() {
								$( '#lct-shortcode-attr-id' ).val( $( this ).attr( 'class' ) );
								$( '#lct-shortcode-attr-id' ).keyup();

								form.find( '.live_search_results ul' ).remove();

								return false;
							} );
						}
					);
				}
		}
	} );
} );
