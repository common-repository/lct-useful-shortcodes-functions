/**
 * Global vars
 * @type {Array}
 */
var lct_instant_vars = [];
var lct_instant_executed = [];


if( typeof lct_custom_admin !== 'undefined' ) {
	var lct_custom = lct_custom_admin;
}


/**
 * Defaults
 */
lct_instant_vars[ 'main_field' ] = '.lct_instant .acf-field';
lct_instant_vars[ 'field_text_only' ] = '.acf-input input[type="text"]';
lct_instant_vars[ 'field_text' ] = lct_instant_vars[ 'main_field' ] + ' ' + lct_instant_vars[ 'field_text_only' ];
lct_instant_vars[ 'field_url_only' ] = '.acf-input input[type="url"]';
lct_instant_vars[ 'field_url' ] = lct_instant_vars[ 'main_field' ] + ' ' + lct_instant_vars[ 'field_url_only' ];
lct_instant_vars[ 'field_number_only' ] = '.acf-input input[type="number"]';
lct_instant_vars[ 'field_number' ] = lct_instant_vars[ 'main_field' ] + ' ' + lct_instant_vars[ 'field_number_only' ];
lct_instant_vars[ 'field_email_only' ] = '.acf-input input[type="email"]';
lct_instant_vars[ 'field_email' ] = lct_instant_vars[ 'main_field' ] + ' ' + lct_instant_vars[ 'field_email_only' ];
lct_instant_vars[ 'field_select_only' ] = '.acf-input select';
lct_instant_vars[ 'field_select' ] = lct_instant_vars[ 'main_field' ] + ' ' + lct_instant_vars[ 'field_select_only' ];
lct_instant_vars[ 'field_select2_only' ] = '.acf-input .select2-selection';
lct_instant_vars[ 'field_select2' ] = lct_instant_vars[ 'main_field' ] + ' ' + lct_instant_vars[ 'field_select2_only' ];
lct_instant_vars[ 'field_textarea_only' ] = '.acf-input textarea';
lct_instant_vars[ 'field_textarea' ] = lct_instant_vars[ 'main_field' ] + ' ' + lct_instant_vars[ 'field_textarea_only' ];
lct_instant_vars[ 'default_fields' ] = lct_instant_vars[ 'field_text' ] + ', ' + lct_instant_vars[ 'field_url' ] + ', ' + lct_instant_vars[ 'field_number' ] + ', ' + lct_instant_vars[ 'field_email' ] + ', ' + lct_instant_vars[ 'field_select' ] + ', ' + lct_instant_vars[ 'field_textarea' ];
lct_instant_vars[ 'tf_field' ] = lct_instant_vars[ 'main_field' ] + '-true-false .acf-input input';
lct_instant_vars[ 'checkbox_field' ] = lct_instant_vars[ 'main_field' ] + '-checkbox .acf-input input';
lct_instant_vars[ 'radio_field' ] = lct_instant_vars[ 'main_field' ] + '-radio .acf-input input, ' + lct_instant_vars[ 'main_field' ] + '-taxonomy .acf-input input[type="radio"]';
lct_instant_vars[ 'all_classes' ] = lct_instant_vars[ 'default_fields' ] + ', ' + lct_instant_vars[ 'tf_field' ] + ', ' + lct_instant_vars[ 'checkbox_field' ] + ', ' + lct_instant_vars[ 'radio_field' ];
lct_instant_vars[ 'update_count' ] = 0;
lct_instant_vars[ 'update_text' ] = 'Updating...';


jQuery( document ).ready( function() {
	/**
	 * Don't continue if ACF is not loaded
	 */
	if( typeof acf === 'undefined' ) {
		return;
	}


	/**
	 * Disable the ACF js navigate away pop up. Super fucking annoying!!!
	 */
	acf.unload.active = 0;


	/**
	 * Vars
	 */
	var _body = jQuery( 'body' );


	//Save all the current values in a data element
	_body.on( 'focus', lct_instant_vars[ 'all_classes' ], function( e ) {
		if( jQuery( this ).attr( 'type' ) !== 'radio' ) {
			lct_instant_save_current_value( e.target );
		}
	} );


	//###//


	//Radio Group
	_body.on( 'mouseenter', lct_instant_vars[ 'radio_field' ], function( e ) {
		lct_instant_save_current_value( e.target );
	} );


	//###//


	//Select2
	_body.on( 'focus', lct_instant_vars[ 'field_select2' ], function( e ) {
		lct_instant_save_current_value( e.target );
	} );


	//###//


	//true_false
	_body.on( 'change', lct_instant_vars[ 'tf_field' ], function( e ) {
		lct_instant_true_false( e.target );
	} );


	//###//


	//Checkbox Group
	_body.on( 'change', lct_instant_vars[ 'checkbox_field' ], function( e ) {
		lct_instant_checkbox_group( e.target );
	} );


	//###//


	//Radio Group
	_body.on( 'change', lct_instant_vars[ 'radio_field' ], function( e ) {
		lct_instant_radio_group( e.target );
	} );


	//###//


	//Default Change/Blur Code
	_body.on( 'change blur', lct_instant_vars[ 'default_fields' ], function( e ) {
		lct_instant_default( e.target );
	} );
} );


//###//


/**
 * Save the current value
 */
function lct_instant_save_current_value( selector ) {
	var ee = jQuery( selector );
	var field_parent = ee.closest( lct_instant_vars[ 'main_field' ] );
	var current_value = ee.val();


	switch( field_parent.data( 'type' ) ) {
		case 'radio':
		case 'taxonomy':
			current_value = null;


			field_parent.find( "input[type='radio']:checked" ).each( function() {
				current_value = jQuery( this ).val();
			} );
			break;


		case 'true_false':
			if( ee.attr( 'type' ) === 'checkbox' ) {
				if( ee.is( ':checked' ) ) {
					current_value = 1;
				} else {
					current_value = 0;
				}
			}
			break;


		case 'checkbox':
			var checkbox_values = [];

			field_parent.find( "input[type='checkbox']:checked" ).each( function() {
				checkbox_values.push( jQuery( this ).val() );
			} );


			if( checkbox_values ) {
				current_value = checkbox_values;
			} else {
				current_value = '';
			}
			break;


		case 'select':
			if( !current_value ) {
				current_value = field_parent.find( 'select' ).val();
			}


			if(
				current_value === '' &&
				field_parent.data( 'lct:::value_old' ) === undefined
			) {
				current_value = '---empty---';
			} else if( field_parent.data( 'lct:::value_old' ) !== undefined ) {
				current_value = field_parent.data( 'lct:::value_old' );
			}
			break;


		default:
	}


	//console.log( field_parent.data( 'name' ) + ' :: ' + current_value );
	field_parent.data( 'lct:::value_old', current_value );
}


/**
 * true_false
 * @param selector
 */
function lct_instant_true_false( selector ) {
	var ee = jQuery( selector );
	var field_parent = ee.closest( lct_instant_vars[ 'main_field' ] );
	var field_form = ee.closest( 'form' );
	var zxza_vars = [];
	zxza_vars[ 'this' ] = ee;
	lct_instant_vars[ 'this' ] = zxza_vars[ 'this' ];
	zxza_vars[ 'field_parent' ] = field_parent;
	zxza_vars[ 'lct:::field_key' ] = field_parent.data( 'key' );
	zxza_vars[ 'lct:::executed' ] = field_form.attr( 'id' ) + '_' + ee.attr( 'id' );
	zxza_vars[ 'lct:::value' ] = ee.is( ':checked' ) ? 1 : 0;
	zxza_vars[ 'lct:::value_old' ] = field_parent.data( 'lct:::value_old' );

	//return if nothing changed
	//return if the field is a hidden field
	if(
		zxza_vars[ 'lct:::value' ] === zxza_vars[ 'lct:::value_old' ] ||
		field_parent.hasClass( 'acf-hidden' )
	) {
		return;
	}


	//We don't want this to double run because of the change blur check.
	if( !lct_instant_executed[ zxza_vars[ 'lct:::executed' ] ] ) {
		//Prevent the field from being edited while it is saving & make the field visually look unavailable
		zxza_vars[ 'disabled_selector' ] = '#' + field_form.attr( 'id' ) + lct_instant_vars[ 'main_field' ] + '-true-false:not(.lct_dont_disable)' + '[data-name="' + zxza_vars[ 'field_parent' ].data( 'name' ) + '"] input';
		lct_adjust_selector_access( 'disable', zxza_vars );

		lct_acf_instant_save_update( zxza_vars );
	}
}


/**
 * Checkbox Group
 * @param selector
 */
function lct_instant_checkbox_group( selector ) {
	var ee = jQuery( selector );

	if( ee.closest( 'form' ).hasClass( 'lct_disable_instant_checkbox_group' ) ) {
		return;
	}

	var field_parent = ee.closest( lct_instant_vars[ 'main_field' ] );
	var zxza_vars = [];
	zxza_vars[ 'this' ] = ee;
	lct_instant_vars[ 'this' ] = zxza_vars[ 'this' ];
	zxza_vars[ 'field_parent' ] = field_parent;
	zxza_vars[ 'lct:::field_key' ] = field_parent.data( 'key' );
	zxza_vars[ 'lct:::executed' ] = zxza_vars[ 'lct:::field_key' ];
	zxza_vars[ 'lct:::value_old' ] = field_parent.data( 'lct:::value_old' );

	var checkbox_values = [];
	field_parent.find( "input[type='checkbox']:checked" ).each( function() {
		checkbox_values.push( jQuery( this ).val() );
	} );

	if( checkbox_values ) {
		zxza_vars[ 'lct:::value' ] = checkbox_values;
	} else {
		zxza_vars[ 'lct:::value' ] = '';
	}

	//return if nothing changed
	//return if the field is a hidden field
	if(
		zxza_vars[ 'lct:::value' ] === zxza_vars[ 'lct:::value_old' ] ||
		field_parent.hasClass( 'acf-hidden' )
	) {
		return;
	}


	//We don't want this to double run because of the change blur check.
	if( !lct_instant_executed[ zxza_vars[ 'lct:::executed' ] ] ) {
		//Prevent the field from being edited while it is saving & make the field visually look unavailable
		var field_form = field_parent.closest( 'form' );
		zxza_vars[ 'disabled_selector' ] = '#' + field_form.attr( 'id' ) + lct_instant_vars[ 'main_field' ] + '-checkbox:not(.lct_dont_disable)' + '[data-name="' + zxza_vars[ 'field_parent' ].data( 'name' ) + '"] input';
		lct_adjust_selector_access( 'disable', zxza_vars );

		lct_acf_instant_save_update( zxza_vars );
	}
}


/**
 * Radio Group
 * @param selector
 */
function lct_instant_radio_group( selector ) {
	var ee = jQuery( selector );

	if( ee.closest( 'form' ).hasClass( 'lct_disable_instant_radio_group' ) ) {
		return;
	}

	var field_parent = ee.closest( lct_instant_vars[ 'main_field' ] );
	var zxza_vars = [];
	zxza_vars[ 'this' ] = ee;
	lct_instant_vars[ 'this' ] = zxza_vars[ 'this' ];
	zxza_vars[ 'field_parent' ] = field_parent;
	zxza_vars[ 'lct:::field_key' ] = field_parent.data( 'key' );
	zxza_vars[ 'lct:::executed' ] = zxza_vars[ 'lct:::field_key' ];
	zxza_vars[ 'lct:::value_old' ] = field_parent.data( 'lct:::value_old' );

	var radio_values = null;
	field_parent.find( "input:checked" ).each( function() {
		radio_values = jQuery( this ).val();
	} );


	zxza_vars[ 'lct:::value' ] = radio_values;


	//return if nothing changed
	//return if the field is a hidden field
	if(
		zxza_vars[ 'lct:::value' ] === zxza_vars[ 'lct:::value_old' ] ||
		field_parent.hasClass( 'acf-hidden' )
	) {
		return;
	}


	//We don't want this to double run because of the change blur check.
	if( !lct_instant_executed[ zxza_vars[ 'lct:::executed' ] ] ) {
		//Prevent the field from being edited while it is saving & make the field visually look unavailable
		var field_form = field_parent.closest( 'form' );
		zxza_vars[ 'disabled_selector' ] = '#' + field_form.attr( 'id' ) + lct_instant_vars[ 'main_field' ] + '-radio:not(.lct_dont_disable)' + '[data-name="' + zxza_vars[ 'field_parent' ].data( 'name' ) + '"] input';
		lct_adjust_selector_access( 'disable', zxza_vars );

		lct_acf_instant_save_update( zxza_vars );
	}
}


/**
 * Default
 * @param selector
 */
function lct_instant_default( selector ) {
	var ee = jQuery( selector );
	var field_parent = ee.closest( lct_instant_vars[ 'main_field' ] );
	var zxza_vars = [];
	zxza_vars[ 'this' ] = ee;
	lct_instant_vars[ 'this' ] = zxza_vars[ 'this' ];
	zxza_vars[ 'field_parent' ] = field_parent;
	zxza_vars[ 'lct:::field_key' ] = field_parent.data( 'key' );
	zxza_vars[ 'lct:::executed' ] = zxza_vars[ 'lct:::field_key' ];
	zxza_vars[ 'lct:::value' ] = ee.val();
	zxza_vars[ 'lct:::value_old' ] = field_parent.data( 'lct:::value_old' );

	//return if nothing changed
	//return if the field is a hidden field
	if(
		zxza_vars[ 'lct:::value' ] === zxza_vars[ 'lct:::value_old' ] ||
		field_parent.hasClass( 'acf-hidden' )
	) {
		return;
	}


	//We don't want this to double run because of the change blur check.
	if( !lct_instant_executed[ zxza_vars[ 'lct:::executed' ] ] ) {
		//Prevent the field from being edited while it is saving & make the field visually look unavailable
		var field_form = field_parent.closest( 'form' );
		var disabled_selector_pre = '#' + field_form.attr( 'id' ) + lct_instant_vars[ 'main_field' ] + '[data-name="' + zxza_vars[ 'field_parent' ].data( 'name' ) + '"] ';
		zxza_vars[ 'disabled_selector' ] =
			disabled_selector_pre + lct_instant_vars[ 'field_text_only' ] + ', ' +
			disabled_selector_pre + lct_instant_vars[ 'field_url_only' ] + ', ' +
			disabled_selector_pre + lct_instant_vars[ 'field_number_only' ] + ', ' +
			disabled_selector_pre + lct_instant_vars[ 'field_email_only' ] + ', ' +
			disabled_selector_pre + lct_instant_vars[ 'field_select_only' ] + ', ' +
			disabled_selector_pre + lct_instant_vars[ 'field_textarea_only' ];


		lct_adjust_selector_access( 'disable', zxza_vars );


		/**
		 * Date Adjustments for Repeaters
		 * //TODO: cs - Allow momnet or ACF date_format to handle this better - 11/17/2020 9:33 PM
		 */
		if( field_parent.hasClass( 'acf-field-date-picker' ) ) {
			var expected_date_format = field_parent.find( '.acf-input .acf-date-picker' ).data( 'date_format' );


			if( zxza_vars[ 'lct:::value' ].indexOf( '/' ) >= 0 ) {
				var date_obj = new Date( zxza_vars[ 'lct:::value' ] );


				if(
					expected_date_format &&
					expected_date_format.indexOf( '/' ) <= 0 &&
					lct_is_valid_date( date_obj )
				) {
					zxza_vars[ 'lct:::value' ] = date_obj.getFullYear() + '-' + (date_obj.getMonth() + 1) + '-' + date_obj.getDate();
					zxza_vars[ 'lct:::value' ] = zxza_vars[ 'lct:::value' ].replaceAll( '-', '' );
				}
			} else if( zxza_vars[ 'lct:::value' ].indexOf( '-' ) >= 0 ) {
				var date_obj = new Date( zxza_vars[ 'lct:::value' ] );


				if(
					expected_date_format &&
					expected_date_format.indexOf( '-' ) <= 0 &&
					lct_is_valid_date( date_obj )
				) {
					zxza_vars[ 'lct:::value' ] = date_obj.getFullYear() + '-' + (date_obj.getMonth() + 1) + '-' + date_obj.getDate();
					zxza_vars[ 'lct:::value' ] = zxza_vars[ 'lct:::value' ].replaceAll( '-', '' );
				}
			}


			field_parent.find( 'input[type="hidden"]' ).val( zxza_vars[ 'lct:::value' ] );
		}


		lct_acf_instant_save_update( zxza_vars );
	}
}


function lct_is_valid_date( d ) {
	return d instanceof Date && !isNaN( d );
}


/**
 * Disable a field while we are saving it
 * @param action
 */
function lct_adjust_selector_access( action, zxza_vars, resp ) {
	/**
	 * Field Update Failed
	 */
	if( action === 'fail' ) {
		zxza_vars[ 'field_parent' ].find( '.lct_instant_message' ).html( resp );


		/**
		 * Disable the Field
		 */
	} else if( action === 'disable' ) {
		//set the var so that the field cannot be updated while we run the call
		lct_instant_executed[ zxza_vars[ 'lct:::executed' ] ] = true;


		//disable the field so that it can't be edited
		setTimeout( function() {
			jQuery( zxza_vars[ 'disabled_selector' ] ).attr( 'disabled', true );
		}, 20 );

		jQuery( zxza_vars[ 'disabled_selector' ] ).css( {
			'background': '#FFB0B0',
			'cursor': 'wait'
		} );


		//Set the response div
		zxza_vars[ 'field_parent' ].find( '.lct_instant_message' ).remove();

		if( zxza_vars[ 'field_parent' ].find( '.acf-label' ).is( ':visible' ) ) {
			var update = '<div class="lct_instant_message lct_instant_message_label">' + lct_instant_vars[ 'update_text' ] + '</div>';

			zxza_vars[ 'field_parent' ].find( '.acf-label' ).prepend( update );

			zxza_vars[ 'field_parent' ].find( '.acf-label label' ).css( {
				display: 'inline-block'
			} );
		} else {
			var update = '<div class="lct_instant_message">' + lct_instant_vars[ 'update_text' ] + '</div>';

			zxza_vars[ 'field_parent' ].find( '.acf-input' ).prepend( update );
		}


		/**
		 * Enable the Field
		 */
	} else if( action === 'enable' ) {
		//Re-enable the field so that it can be edited again & make the field visually look NORMAL
		var reenable = 1;
		if( jQuery(
				zxza_vars[ 'disabled_selector' ] ).closest( '.acf-field' ).length &&
			typeof jQuery( zxza_vars[ 'disabled_selector' ] ).closest( '.acf-field' ).attr( 'hidden' ) !== 'undefined'
		) {
			reenable = 0;
		}
		if( reenable ) {
			jQuery( zxza_vars[ 'disabled_selector' ] ).attr( 'disabled', false );
		}

		jQuery( zxza_vars[ 'disabled_selector' ] ).css( {
			'background': '',
			'cursor': ''
		} );


		//Disable the response
		zxza_vars[ 'field_parent' ].find( '.lct_instant_message' ).html( resp.status_message );


		if( resp.status === 'updated' ) {
			zxza_vars[ 'field_parent' ].find( '.lct_instant_message' ).css( {
				color: 'green'
			} );


			setTimeout( function() {
				zxza_vars[ 'field_parent' ].find( '.lct_instant_message' ).remove();
			}, 4000 );
		}


		//unset the var so that the field can be updated if it gets changed again
		lct_instant_executed[ zxza_vars[ 'lct:::executed' ] ] = false;
	}
}


/**
 * If there is a submit button on the form, disable while an update is in progress
 * @param action
 */
function lct_adjust_submit_button( action ) {
	if( lct_custom.acf_deny_submit_button_disable === true ) {
		return;
	}


	var submit_selector = jQuery( '.acf-form-submit [type="submit"].button' );
	var update_text = 'Saving please wait...';
	if(
		!lct_instant_vars[ 'submit_text' ] &&
		submit_selector.length > 0
	) {
		lct_instant_vars[ 'submit_text' ] = submit_selector.val();
	} else if( !lct_instant_vars[ 'submit_text' ] ) {
		lct_instant_vars[ 'submit_text' ] = 'Submit';
	}


	/**
	 * Disable the Submit Button
	 */
	if( action === 'disable' ) {
		lct_instant_vars[ 'update_count' ] += 1;
		submit_selector.val( update_text );
		if(
			lct_custom.acf_form_delayable_submit_check &&
			lct_instant_vars[ 'this' ].length &&
			lct_instant_vars[ 'this' ].closest( 'form' ).find( '[name="_lct_wp_api_submit_ready"]' ).length
		) {
			lct_instant_vars[ 'this' ].closest( 'form' ).find( '[name="_lct_wp_api_submit_ready"]' ).val( '0' );
		} else {
			submit_selector.attr( 'disabled', true );
		}
		submit_selector.css( {
			'cursor': 'wait'
		} );


		/**
		 * Enable the Submit Button
		 */
	} else if( action === 'enable' ) {
		lct_instant_vars[ 'update_count' ] -= 1;

		if( lct_instant_vars[ 'update_count' ] <= 0 ) {
			submit_selector.val( lct_instant_vars[ 'submit_text' ] );
			if(
				lct_custom.acf_form_delayable_submit_check &&
				lct_instant_vars[ 'this' ].length &&
				lct_instant_vars[ 'this' ].closest( 'form' ).find( '[name="_lct_wp_api_submit_ready"]' ).length
			) {
				lct_instant_vars[ 'this' ].closest( 'form' ).find( '[name="_lct_wp_api_submit_ready"]' ).val( '1' );
			} else {
				submit_selector.attr( 'disabled', false );
			}
			submit_selector.css( {
				'cursor': ''
			} );
		}
	}
}


/**
 * Wait to exec a function until an lct_instant_executed is disabled
 *
 * @param executed
 * @param callback
 * @param time
 * @since 2020.1
 * @verify 2020.01.07
 */
function lct_wait_for_instant_executed( executed, callback, time ) {
	if(
		typeof lct_instant_executed[ executed ] === 'undefined' ||
		lct_instant_executed[ executed ] === false
	) {
		if( callback ) {
			this[ callback ]();
		}
	} else {
		if( time === undefined ) {
			time = 50;
		}


		setTimeout( function() {
			lct_wait_for_instant_executed( executed, callback, time );
		}, time );
	}
}


/**
 * Update the database value of an ACF field
 */
function lct_acf_instant_save_update( zxza_vars ) {
	var timeout = 1;

	if( zxza_vars[ 'field_parent' ].hasClass( 'lct_instant_save_delay_2_sec' ) ) {
		timeout = 2000;
	}


	setTimeout( function() {
		if( !zxza_vars[ 'post_id' ] ) {
			zxza_vars[ 'post_id' ] = zxza_vars[ 'this' ].closest( 'form' ).find( '[name="_acf_post_id"]' ).val();
		}


		lct_adjust_submit_button( 'disable' );


		//console.log( 'triggered lct_acf_instant_save_update()' );
		//console.log( zxza_vars );


		/**
		 * Send the actual ajax request
		 */
		jQuery.ajax( {
			url: lct_custom.ajax_url,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'lct_acf_instant_save',
				nonce: zxza_vars[ 'this' ].closest( 'form' ).find( '[name="_acf_nonce"]' ).val(),
				task: 'update',
				screen: zxza_vars[ 'this' ].closest( 'form' ).find( '[name="_acf_screen"]' ).val(),
				post_id: zxza_vars[ 'post_id' ],
				'lct:::field_key': zxza_vars[ 'lct:::field_key' ],
				'lct:::value': zxza_vars[ 'lct:::value' ],
				'lct:::value_old': zxza_vars[ 'lct:::value_old' ],
				'lct:::acf_form': zxza_vars[ 'this' ].closest( 'form' ).serialize(),
				'_acf_form': zxza_vars[ 'this' ].closest( 'form' ).serialize()
			}


			/**
			 * A success ajax response
			 */
		} ).done( function( resp, request_status ) {
			if( resp.status === 'refresh' ) {
				location.reload( true );
			}


			if( resp.status !== 'updated' ) {
				return;
			}


			/**
			 * Success answer in the response
			 */
			//Set the old value to the new value
			zxza_vars[ 'this' ].closest( lct_instant_vars[ 'main_field' ] ).data( 'lct:::value_old', zxza_vars[ 'lct:::value' ] );


			/**
			 * Something went wrong with the ajax call
			 */
		} ).fail( function( resp_obj, request_status, response_text ) {
			var answer = request_status + ' [' + response_text + ']';


			console.log( 'Opps, that went bad! :: lct_acf_instant_save_update(); ' + answer );


			lct_adjust_selector_access( 'fail', zxza_vars, answer );


			/**
			 * Well...Always
			 */
		} ).always( function( resp, request_status ) {
			//console.log( 'Complete' );


			zxza_vars[ 'this' ].closest( 'form' ).find( '[name="_acf_changed"]' ).val( 0 );
			lct_adjust_submit_button( 'enable' );
			lct_adjust_selector_access( 'enable', zxza_vars, resp );
		} );
	}, timeout );
}
