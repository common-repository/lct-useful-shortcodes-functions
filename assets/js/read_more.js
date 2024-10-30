jQuery( document ).ready( function() {
	jQuery( '.read_more_button_copy' ).hide();

	jQuery( '.read_more_button' ).click( function( e ) {
		var read_class = jQuery( this ).data( 'read_class' );

		jQuery( '.read_more_button_' + read_class ).hide();
		jQuery( '.read_more_button_' + read_class + '_copy' ).show( 'slow' );

		e.preventDefault();
	} );
} );
