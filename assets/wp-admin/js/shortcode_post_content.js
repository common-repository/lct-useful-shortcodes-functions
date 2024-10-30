jQuery( function( $ ) {
	$( '#lctpcs_editor_button' ).click( function() {
		var id = " id=\"" + $( '#lctpcs_id' ).val() + "\"";

		window.send_to_editor( "[lct_post_content" + id + "]" );

		return false;
	} );

	$( '#lctpcs_id' ).keyup( function( e ) {
		form = $( '#lctpcs_meta_box' );
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
					$.get(
						'/x/wp-admin/index.php',
						{
							lctpcs_action: 'lctpcs_id_lookup',
							post_title: term
						},
						function( response ) {
							$( '#lctpcs_meta_box div.live_search_results' ).html( response ).find( 'li' ).click( function() {
								$( '#lctpcs_id' ).val( $( this ).attr( 'class' ) );
								$( '#lctpcs_extras' ).show();
								$( '#lctpcs_extras [id*="lctpcs_"]' ).val( '' );
								form.find( '.live_search_results ul' ).remove();
								return false;
							} );
						},
						'html'
					);
				}
		}
	} );
} );
