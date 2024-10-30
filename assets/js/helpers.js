var lct_is_dev = false;


/**
 * Send the actual ACF AJAX sync call
 * @param action string
 * @param args object
 * @returns {*}
 * @since 2020.5
 * @verified 2022.08.31
 */
var lct_acf_sync_ajax = function( action, args ) {
	return lct_acf_ajax( action, args, false );
};


/**
 * Send the actual ACF AJAX call
 * @param action string
 * @param args object
 * @param async bool
 * @returns resp {
 *     status (string)        => fail || valid || redirect || reload (use as last resort)
 *     atts (object)          => mixed //Any combination of attributes that may be useful for a specific call
 *     html (string)          => mixed //html to be relayed to the main content container
 *     response_html (string) => mixed //html to be relayed to the response container
 *     alert_status (string)  => general || error || success || notice || blank || custom (See: FusionSC_Alert{}render())
 *     alert_html (string)    => mixed //html to be relayed to the alert container
 *     results (mixed)        => mixed //ACF queries return this
 *     more (bool)            => false || true //NOT TRACKED //ACF queries return this
 * }
 * @since 2020.5
 * @verified 2022.08.31
 */
var lct_acf_ajax = function( action, args, async ) {
	/**
	 * Set main vars
	 */
	if( typeof args === 'undefined' ) {
		args = {};
	}
	if( async === 'undefined' ) {
		async = true;
	}


	/**
	 * Prep the form_obj
	 */
	let post_id = null;
	let form_obj = { action: action };
	jQuery.each( args, function( k, v ) {
		form_obj[ k ] = v;
	} );
	if( typeof form_obj.post_id !== 'undefined' ) {
		post_id = form_obj.post_id;
	}


	/**
	 * Let ACF prep things
	 */
	form_obj = acf.prepareForAjax( form_obj );


	/**
	 * Set the post_id after ACF sets it
	 */
	if( post_id ) {
		form_obj.post_id = post_id;
	}


	/**
	 * Send the actual AJAX call
	 */
	let call_obj = jQuery.ajax( {
		url: lct_custom.ajax_url,
		method: 'POST',
		dataType: 'json',
		async: async,
		data: form_obj,


		/**
		 * A successful AJAX call
		 * @param object resp
		 * @unused param string call_status
		 * @unused param jqXHR resp_obj
		 */
	} ).done( function( resp ) {
		//console.log( resp );


		/**
		 * Check the status of the status
		 * The outcome is either not needed for AJAX or unknown
		 */
		if( typeof resp.status === 'undefined' ) {
			resp.status = 'unknown';
			//console.log( 'AJAX Call Status: ' + resp.status ); //AJAX doesn't always use status, so don't log it.
		}


		/**
		 * Set main vars
		 */
		if( typeof resp.atts === 'undefined' ) {
			resp.atts = {};
		}
		if( typeof resp.html === 'undefined' ) {
			resp.html = '';
		}
		if( typeof resp.response_html === 'undefined' ) {
			resp.response_html = '';
		}
		if( typeof resp.alert_status === 'undefined' ) {
			resp.alert_status = 'general';
		}
		if( typeof resp.alert_html === 'undefined' ) {
			resp.alert_html = '';
		}
		if( typeof resp.results === 'undefined' ) {
			resp.results = '';
		}


		/**
		 * The outcome was a failure, even though the AJAX call was a success
		 */
		if( resp.status === 'fail' ) {
			console.log( 'AJAX Call Status: ' + resp.status );
			console.log( 'AJAX Call Alert HTML: ' + resp.alert_html );
			console.log( 'AJAX Call HTML: ' + resp.html );
			return;
		}


		/**
		 * The outcome was to redirect to a new page
		 */
		if(
			resp.status === 'redirect' &&
			typeof resp.atts.url !== 'undefined'
		) {
			window.location.replace( resp.atts.url );
			return;
		}


		/**
		 * The outcome was to reload the current page
		 */
		if( resp.status === 'reload' ) {
			window.location.reload();

		}


		/**
		 * Something went wrong with the AJAX call
		 * @param jqXHR resp_obj
		 * @param string call_status
		 * @param string error_text
		 */
	} ).fail( function( resp_obj, call_status, error_text ) {
		/**
		 * Check the status of the status
		 * The outcome is either not needed for AJAX or unknown
		 */
		if( typeof resp_obj.status === 'undefined' ) {
			resp_obj.status = 'unknown';
			//console.log( 'AJAX Call Status: ' + resp_obj.status ); //AJAX doesn't always use status, so don't log it.
		}


		/**
		 * Make an error log
		 */
		let answer = '[' + call_status + '] ' + error_text;
		console.log( 'Opps, that went bad! :: Details:', '\n', 'AJAX Action:', action, '\n', 'Error:', answer );


		/**
		 * Well...Always
		 * @param object|jqXHR resp
		 * @unused param string call_status
		 * @unused param jqXHR|string resp_obj|error_text
		 */
	} ).always( function( resp ) {
		//console.log( 'Complete' );


		/**
		 * Set main vars
		 */
		if( typeof resp.atts === 'undefined' ) {
			resp.atts = {};
		}
		if( typeof resp.html === 'undefined' ) {
			resp.html = '';
		}
		if( typeof resp.response_html === 'undefined' ) {
			resp.response_html = '';
		}
		if( typeof resp.alert_status === 'undefined' ) {
			resp.alert_status = 'general';
		}
		if( typeof resp.alert_html === 'undefined' ) {
			resp.alert_html = '';
		}
		if( typeof resp.results === 'undefined' ) {
			resp.results = '';
		}
	} );


	return call_obj;
};


/**
 * Send the actual ACF API sync call
 * @param api_route string
 * @param args object
 * @returns {*}
 * @since 2020.5
 * @verified 2022.08.31
 */
var lct_acf_sync_api_GET = function( api_route, args ) {
	return lct_acf_sync_api_call( api_route, 'GET', args );
};


/**
 * Send the actual ACF API sync call
 * @param api_route string
 * @param args object
 * @returns {*}
 * @since 2020.5
 * @verified 2022.08.31
 */
var lct_acf_sync_api_POST = function( api_route, args ) {
	return lct_acf_sync_api_call( api_route, 'POST', args );
};


/**
 * Send the actual ACF API sync call
 * @param api_route string
 * @param method string
 * @param args object
 * @returns {*}
 * @since 2020.5
 * @verified 2022.08.31
 */
var lct_acf_sync_api_call = function( api_route, method, args ) {
	return lct_acf_api_call( api_route, method, args, false );
};


/**
 * Send the actual ACF API call
 * @param api_route string
 * @param args object
 * @returns {*}
 * @since 2020.5
 * @verified 2022.08.31
 */
var lct_acf_api_GET = function( api_route, args ) {
	return lct_acf_api_call( api_route, 'GET', args );
};


/**
 * Send the actual ACF API call
 * @param api_route string
 * @param args object
 * @returns {*}
 * @since 2020.5
 * @verified 2022.08.31
 */
var lct_acf_api_POST = function( api_route, args ) {
	return lct_acf_api_call( api_route, 'POST', args );
};


/**
 * Send the actual ACF API call
 * @param api_route string
 * @param method string
 * @param args object
 * @param async bool
 * @returns resp {
 *     status (string)        => fail || valid || redirect || reload (use as last resort)
 *     atts (object)          => mixed //Any combination of attributes that may be useful for a specific call
 *     html (string)          => mixed //html to be relayed to the main content container
 *     response_html (string) => mixed //html to be relayed to the response container
 *     alert_status (string)  => general || error || success || notice || blank || custom (See: FusionSC_Alert{}render())
 *     alert_html (string)    => mixed //html to be relayed to the alert container
 * }
 * @since 2020.5
 * @verified 2022.08.31
 */
var lct_acf_api_call = function( api_route, method, args, async ) {
	/**
	 * Set main vars
	 */
	if( method === undefined ) {
		method = 'GET';
	}
	if( args === undefined ) {
		args = {};
	}
	if( async === undefined ) {
		async = true;
	}


	/**
	 * Prep the form_obj
	 */
	let post_id = null;
	let form_obj = {};
	jQuery.each( args, function( k, v ) {
		if( k === 'acf_form' ) { //Skip acf_form
			return;
		}


		form_obj[ k ] = v;
	} );
	if( typeof form_obj.post_id !== 'undefined' ) {
		post_id = form_obj.post_id;
	}


	/**
	 * Let ACF prep things
	 */
	form_obj = acf.prepareForAjax( form_obj );


	/**
	 * Set the post_id after ACF sets it
	 */
	if( post_id ) {
		form_obj.post_id = post_id;
	}


	/**
	 * Set form_obj to acf_form if it exists
	 */
	if( typeof args.acf_form !== 'undefined' ) {
		form_obj = args.acf_form.serialize();
	}


	/**
	 * Send the actual API call
	 */
	let call_obj = jQuery.ajax( {
		url: lct_custom.api_url + api_route,
		method: method,
		dataType: 'json',
		async: async,
		data: form_obj,
		beforeSend: function( xhr ) {
			xhr.setRequestHeader( 'X-WP-Nonce', lct_custom.wpapi_nonce );
		},


		/**
		 * A successful API call
		 * @param object resp
		 * @unused param string call_status
		 * @unused param jqXHR resp_obj
		 */
	} ).done( function( resp ) {
		//console.log( resp );


		/**
		 * Check the status of the status
		 */
		if( typeof resp.status === 'undefined' ) {
			resp.status = 'unknown';
		}


		/**
		 * Set main vars
		 */
		if( typeof resp.atts === 'undefined' ) {
			resp.atts = {};
		}
		if( typeof resp.html === 'undefined' ) {
			resp.html = '';
		}
		if( typeof resp.response_html === 'undefined' ) {
			resp.response_html = '';
		}
		if( typeof resp.alert_status === 'undefined' ) {
			resp.alert_status = 'general';
		}
		if( typeof resp.alert_html === 'undefined' ) {
			resp.alert_html = '';
		}


		/**
		 * Set the alert
		 */
		if( typeof vm_alert_area !== 'undefined' ) {
			if( resp.status === 'fail' ) {
				resp.alert_html = lct_custom.api_error_text + '<br />' + resp.alert_html;
			}


			if( resp.alert_html ) {
				args = {
					'type': resp.alert_status,
					'content': resp.alert_html,
				};


				vm_alert_area.display_alert( args, resp );
			} else {
				vm_alert_area.update_alert( '', false );
			}
		}


		/**
		 * The outcome was a failure, even though the API call was a success
		 */
		if( resp.status === 'fail' ) {
			console.log( 'API Call Status: ' + resp.status );
			console.log( 'API Call Alert HTML: ' + resp.alert_html );
			console.log( 'API Call HTML: ' + resp.html );


			return;
		}


		/**
		 * The outcome was to redirect to a new page
		 */
		if(
			resp.status === 'redirect' &&
			typeof resp.atts.url !== 'undefined'
		) {
			/**
			 * Set the alert
			 */
			if( typeof vm_alert_area !== 'undefined' ) {
				vm_alert_area.update_alert( lct_custom.redirect_page_text, false );
			}


			/**
			 * Redirect the page
			 */
			window.location.replace( resp.atts.url );
			return;
		}


		/**
		 * The outcome was to reload the current page
		 */
		if( resp.status === 'reload' ) {
			/**
			 * Set the alert
			 */
			if( typeof vm_alert_area !== 'undefined' ) {
				vm_alert_area.update_alert( lct_custom.redirect_page_text, false );
			}


			/**
			 * Reload the page
			 */
			window.location.reload();
			return;
		}


		/**
		 * The outcome is unknown or invalid
		 */
		if( resp.status !== 'valid' ) {
			console.log( 'API Call Status: ' + resp.status );
		}


		/**
		 * Something went wrong with the API call
		 * @param jqXHR resp_obj
		 * @param string call_status
		 * @param string error_text
		 */
	} ).fail( function( resp_obj, call_status, error_text ) {
		/**
		 * Check the status of the status
		 */
		if( typeof resp_obj.status === 'undefined' ) {
			resp_obj.status = 'unknown';
		}


		/**
		 * Make an error log
		 */
		let answer = '[' + call_status + '] ' + error_text;
		console.log( 'Opps, that went bad! :: Details:', '\n', 'Call:', api_route, '\n', 'Error:', answer );


		/**
		 * Set the alert
		 */
		if( typeof vm_alert_area !== 'undefined' ) {
			if( typeof resp_obj.alert_html !== 'undefined' ) {
				args = {
					'type': resp_obj.alert_status,
					'content': resp_obj.alert_html,
				};


				vm_alert_area.display_alert( args, resp_obj );
			} else {
				let content = '<h2 style="margin: 20px 0;">' + lct_custom.api_error_text + '</h2>';


				if(
					typeof resp_obj !== 'undefined' &&
					typeof resp_obj.responseJSON !== 'undefined' &&
					typeof resp_obj.responseJSON.message !== 'undefined'
				) {
					content = resp_obj.responseJSON.message;
				}


				vm_alert_area.update_alert( content, false );
			}
		}


		/**
		 * Well...Always
		 * @param object|jqXHR resp
		 * @unused param string call_status
		 * @unused param jqXHR|string resp_obj|error_text
		 */
	} ).always( function( resp ) {
		//console.log( 'Complete' );


		/**
		 * Set main vars
		 */
		if( typeof resp.atts === 'undefined' ) {
			resp.atts = {};
		}
		if( typeof resp.html === 'undefined' ) {
			resp.html = '';
		}
		if( typeof resp.response_html === 'undefined' ) {
			resp.response_html = '';
		}
		if( typeof resp.alert_status === 'undefined' ) {
			resp.alert_status = 'general';
		}
		if( typeof resp.alert_html === 'undefined' ) {
			resp.alert_html = '';
		}
	} );


	return call_obj;
};


/**
 * Clean up the data
 * @since 2020.5
 * @verified 2022.08.23
 */
var lct_acf_data_check = function( data ) {
	if( typeof data.lct === 'undefined' ) {
		data.lct = {};
	}


	return data;
};


/**
 * ACF: parse value
 * @since 2020.5
 * @verified 2019.02.07
 */
var lct_parseString = function( val ) {
	return val ? '' + val : '';
};


/**
 * ACF: are values equal
 * @since 2020.5
 * @verified 2019.02.07
 */
var lct_isEqualTo = function( v1, v2 ) {
	return (lct_parseString( v1 ).toLowerCase() === lct_parseString( v2 ).toLowerCase());
};


/**
 * ACF: inArray
 * @since 2020.5
 * @verified 2022.08.23
 */
var lct_inArray = function( v1, array ) {
	if( jQuery.isArray( array ) === false ) {
		return false;
	}


	array = array.map( function( v2 ) {
		return lct_parseString( v2 );
	} );


	return (array.indexOf( v1 ) > -1);
};


/**
 * Get a value of a parameter
 * @since 2020.5
 * @verified 2022.08.23
 */
var lct_get_url_parameter = function( name, url ) {
	if( url === undefined ) {
		url = location.search;
	}


	name = name.replace( /[\[]/, '\\[' ).replace( /[\]]/, '\\]' );
	var regex = new RegExp( '[\\?&]' + name + '=([^&#]*)' );
	var results = regex.exec( url );


	return results === null ? '' : decodeURIComponent( results[ 1 ].replace( /\+/g, ' ' ) );
};


/**
 * Custom methods go below here
 */
