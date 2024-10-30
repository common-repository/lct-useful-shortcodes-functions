var executed = [];

jQuery( document ).ready( function() {
	//We someone clicks an email button
	jQuery( '.lct_send_welcome, .lct_send_reset' ).on( 'click', function() {
		var zxza_vars = [];
		zxza_vars[ 'button' ] = jQuery( this ).attr( 'id' );


		//We don't want this to double run because of the change blur check.
		if( !executed[ 'lct_send_password_ajax_button' ] ) {
			//so we will set a temporary var
			executed[ 'lct_send_password_ajax_button' ] = true;


			//Prevent the field from being edited while it is saving & make the field visually look unavailable
			jQuery( '.lct_send_password_ajax_button' ).attr( 'disabled', true );


			lct_send_password_do( zxza_vars );
		}
	} );
} );


//###//


/*
 * Send an email
 */
function lct_send_password_do( zxza_vars ) {
	jQuery.post(
		lct_send_password.ajax_url,
		{
			action: 'lct_send_password',
			wpapi_nonce: lct_send_password.wpapi_nonce,
			task: 'do',
			post_id: lct_send_password.post_id,
			lct_org: lct_send_password.lct_org,
			button: zxza_vars[ 'button' ]
		},
		function( data ) {
			var data = jQuery.parseJSON( data );


			console.log( data.status );


			//unset the var so that the field can be updated if it gets changed again
			executed[ 'lct_send_password_ajax_button' ] = false;


			//Re-enable the field so that it can be edited again  & make the field visually look NORMAL
			jQuery( '.lct_send_password_ajax_button' ).attr( 'disabled', false );


			//Display a message about the email status
			jQuery( '#lct_send_password_message' ).html( data.message );
		}
	);
}
