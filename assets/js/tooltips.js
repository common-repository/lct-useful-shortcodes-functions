jQuery( document ).ready( function() {
	lct_initialize_tooltips();
} );


function lct_initialize_tooltips() {
	jQuery( ".lct_tooltip" ).tooltip( {
		show: 500,
		hide: 1000,
		content: function() {
			return jQuery( this ).prop( 'title' );
		}
	} );
}
