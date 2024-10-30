(function() {
	/**
	 * Don't continue if ACF is not loaded
	 */
	if( typeof acf === 'undefined' ) {
		return;
	}


	acf.addAction( 'ready', function() {
		lct_acf_hooks();
	} );
})( jQuery );


/**
 * Initialize any ACF hooks we would like to have run on admin page loads
 * @since 2020.5
 * @verify 2019.02.07
 */
var lct_acf_hooks = function() {
	/**
	 * Don't continue if ACF is not loaded
	 */
	if( typeof acf === 'undefined' ) {
		return;
	}


	/**
	 * Actions
	 */
	acf.addAction( 'remove', lct_remove_instant_repeater );


	/**
	 * Filters
	 */
	acf.addFilter( 'prepare_for_ajax', lct_prepare_for_ajax );
};


/**
 * Update the DB when we remove a repeater row from an instant field
 * @since 2020.5
 * @verify 2019.02.07
 */
var lct_remove_instant_repeater = function( $el ) {
	if( $el.closest( 'form' ).hasClass( 'lct_instant' ) !== true ) {
		return;
	}


	var form = $el.closest( 'form' ).serialize();
	var selector = $el.closest( '.acf-field' );
	var main_repeater_selector = selector.closest( 'form > .acf-fields > .acf-field[data-type="repeater"]' ).data( 'key' );
	var affected_repeater = selector.data( 'key' );
	var repeater_parent_row = selector.closest( '.acf-row' ).data( 'id' );
	var skip = null;


	jQuery( $el.closest( '.acf-table' ).find( 'tr.acf-row:not(.acf-clone)' ) ).each( function() {
		if( $el[ 0 ].dataset.id === jQuery( this ).data( 'id' ) ) {
			skip = $el[ 0 ].dataset.id;
		}
	} );


	var args = { 'selector': main_repeater_selector, 'affected_repeater': affected_repeater, 'repeater_parent_row': repeater_parent_row, 'skip': skip, 'form': form };
	lct_acf_ajax( 'lct/acf/ajax/save_repeater_after_remove', args );
};


/**
 * Set args for ajax call
 * @since 2020.5
 * @verify 2019.02.07
 */
var lct_prepare_for_ajax = function( args ) {
	if(
		typeof lct_custom !== 'undefined' &&
		typeof lct_custom.acf_data !== 'undefined'
	) {
		if( typeof args.form !== 'undefined' ) {
			args.post_id = lct_get_url_parameter( '_acf_post_id', args.form );
		}


		args.lct_root_post_id = null;
		args.lct_acf_post_id = null; //Delete This


		if( typeof lct_custom.acf_data.lct_root_post_id !== 'undefined' ) {
			args.lct_root_post_id = lct_custom.acf_data.lct_root_post_id;
		}
		if( typeof lct_custom.acf_data.lct_acf_post_id !== 'undefined' ) {
			args.lct_acf_post_id = lct_custom.acf_data.lct_acf_post_id;
		} //Delete This
	}


	return args;
};
