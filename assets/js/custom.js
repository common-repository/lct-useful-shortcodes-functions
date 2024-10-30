var lct_c_vars = {};


jQuery( document ).ready( function() {
	/**
	 * Activate Lazy Frame
	 */
	if( typeof lct_google_map_api !== 'undefined' ) {
		lazyframe(
			'.lazyframe',
			{
				'apikey': lct_google_map_api,
				onAppend: function( iframe ) {
					lct_lazyframe_update();
				}
			}
		);
	} else {
		lazyframe(
			'.lazyframe',
			{
				onAppend: function( iframe ) {
					lct_lazyframe_update();
				}
			}
		);
	}


	/**
	 * Allow lazyframe to run in modals
	 */
	var fusion_modal = jQuery( '.fusion-modal' );
	fusion_modal.bind( 'show.bs.modal', function() {
		e = jQuery( this ), setTimeout( function() {
			e.find( '.lazyframe' ).attr( 'data-initinview', 'true' ),
				e.find( '.lazyframe' ).find( 'iframe' ).each( function() {
					this.contentWindow.postMessage( '{"event":"command","func":"playVideo","args":""}', '*' );
				} )
		}, 350 )
	} );


	fusion_modal.each( function() {
		jQuery( this ).on( 'hide.bs.modal', function() {
			jQuery( this ).find( '.lazyframe iframe' ).each( function() {
				this.contentWindow.postMessage( '{"event":"command","func":"pauseVideo","args":""}', '*' )
			} )
		} )
	} );


	if( typeof lct_disable_main_script === 'undefined' ) {
		/**
		 * Listen for the Menu Button
		 */
		jQuery( '.lct_overlay_menu .close, .lct_mobi_overlay_menu_button' ).click( function( e ) {
			e.preventDefault();
			jQuery( 'aside.lct_overlay_menu' ).toggleClass( 'open' );
		} );


		/**
		 * Elements: Defaults
		 */
		lct_c_vars.s_body = jQuery( 'body' );

		lct_c_vars.s_main = jQuery( '#main' );
		lct_c_vars.is_main = false;

		lct_c_vars.s_lct_mobi_menu = jQuery( '.lct_mobi_menu_button' );
		lct_c_vars.is_lct_mobi_menu = false;

		lct_c_vars.s_sideheader = jQuery( '#side-header' );
		lct_c_vars.is_sideheader = false;

		lct_c_vars.s_fusion_header_wrapper = jQuery( '.fusion-header-wrapper' );
		lct_c_vars.is_fusion_header_wrapper = false;

		lct_c_vars.s_fusion_header_wrapper_lct = jQuery( '.lct_mobi_nav_bar' );
		lct_c_vars.is_fusion_header_wrapper_lct = false;

		lct_c_vars.s_menu_sticky_buttons = jQuery( '.menu_sticky_buttons' );
		lct_c_vars.is_menu_sticky_buttons = false;

		lct_c_vars.s_mobile_nav_holder = jQuery( '.fusion-mobile-nav-holder' );
		lct_c_vars.is_mobile_nav_holder = false;
		lct_c_vars.is_mobile_nav_holder_visible = false;


		/**
		 * Elements: Set
		 */
		if( lct_c_vars.s_main.length ) {
			lct_c_vars.is_main = true;
		}

		if( lct_c_vars.s_lct_mobi_menu.length ) {
			lct_c_vars.is_lct_mobi_menu = true;
		}

		if( lct_c_vars.s_sideheader.length ) {
			lct_c_vars.is_sideheader = true;
		}

		if( lct_c_vars.s_fusion_header_wrapper.length ) {
			lct_c_vars.is_fusion_header_wrapper = true;
		}

		if( lct_c_vars.s_fusion_header_wrapper_lct.length ) {
			lct_c_vars.is_fusion_header_wrapper_lct = true;
		}

		if( lct_c_vars.s_menu_sticky_buttons.length ) {
			lct_c_vars.is_menu_sticky_buttons = true;
		}

		if( lct_c_vars.s_mobile_nav_holder.length ) {
			lct_c_vars.is_mobile_nav_holder = true;
		}


		/**
		 * Switches: Defaults
		 */
		lct_c_vars.is_desktop = true;
		lct_c_vars.is_tablet = false;
		lct_c_vars.menu_container_set = false;


		/**
		 * Other: Defaults
		 */
		lct_c_vars.external_fusion_main_menu_container = 0;


		/**
		 * Other: Set the value if an external value exists in the code
		 */
		if( typeof set_external_fusion_main_menu_container !== 'undefined' ) {
			lct_c_vars.external_fusion_main_menu_container = set_external_fusion_main_menu_container;
		}


		/**
		 * Any Site Static Vars: Defaults
		 */
		lct_c_vars.last_epoch = 0;
		lct_c_vars.last_scroll_to_top = 0;


		/**
		 * Avada Static Vars: Defaults
		 */
		lct_c_vars.sideheader_height = 0;
		lct_c_vars.static_fusion_header = 0;
		lct_c_vars.static_logo_header = 0;
		lct_c_vars.static_logo_header_set = false;
		lct_c_vars.mobile_nav_holder_height = 0;
		lct_c_vars.menu_sticky_buttons_height = 0;
		lct_c_vars.tablet_class = 'lct_window_under_tablet_threshold';

		var static_lca_home_logo = 0;


		/**
		 * Avada Static Vars: Set
		 */
		if( lct_c_vars.is_sideheader ) {
			lct_c_vars.sideheader_height = lct_c_vars.s_sideheader.actual( 'outerHeight' );
		}


		/**
		 * We are don't getting things setup, let do something now
		 */
		/**
		 * We are don't getting things setup, let do something now
		 */


		/**
		 * Run when the window loads
		 */
		jQuery( window ).on( 'load', function() {
			lct_set_mobile_status();


			jQuery( window ).scroll();
		} );


		/**
		 * Run when the window is resized
		 */
		jQuery( window ).resize( function() {
			lct_reset_header_stuff();

			lct_set_mobile_status();

			lct_check_mobile_nav_holder_visible();

			lct_set_fusion_header_wrapper();


			if(
				lct_c_vars.is_lct_mobi_menu &&
				lct_c_vars.is_mobile_nav_holder_visible
			) {
				lct_c_vars.static_fusion_header = lct_c_vars.static_fusion_header - lct_c_vars.s_mobile_nav_holder.actual( 'outerHeight' );
			}


			jQuery( window ).scroll();
		} );


		/**
		 * Run when the mouse is scrolled
		 */
		jQuery( window ).scroll( function() {
			lct_check_mobile_nav_holder_visible();


			/**
			 * Scroll to top
			 */
			if(
				lct_c_vars.is_lct_mobi_menu &&
				lct_c_vars.is_mobile_nav_holder_visible
			) {
				window.scrollTo( 0, 0 );
			}


			/**
			 * Elements: Defaults
			 */
			lct_c_vars.s_sideheader_sticky = jQuery( '#side-header.fusion-is-sticky' );
			lct_c_vars.is_sideheader_sticky = false;


			/**
			 * Elements: Set
			 */
			if( lct_c_vars.s_sideheader_sticky.length ) {
				lct_c_vars.is_sideheader_sticky = true;
			}


			/**
			 * Switches: Defaults
			 */
			//NONE


			/**
			 * Other: Defaults
			 */
			//NONE


			/**
			 * Any Site Static Vars: Defaults
			 */
			var epoch = (new Date).getTime();
			var lct_cancel = false;
			var window_height = jQuery( window ).height();
			var scroll_to_top = jQuery( window ).scrollTop();


			/**
			 * Make sure we are not over checking the scroll
			 */
			if( (epoch - lct_c_vars.last_epoch) < 150 ) {
				lct_cancel = true;
			}


			lct_c_vars.last_epoch = epoch;


			/**
			 * Scroll can get called incorrectly so we check to see if the scroll has actually happened
			 * We will only continue is the scroll has occurred
			 */
			if(
				lct_c_vars.last_scroll_to_top &&
				scroll_to_top === lct_c_vars.last_scroll_to_top
			) {
				return;
			} else {
				lct_c_vars.last_scroll_to_top = scroll_to_top;
			}


			/**
			 * Update Vars from ready
			 */
			lct_c_vars.mobile_nav_holder_height = window_height;


			/**
			 * Avada Static Vars: Defaults
			 */
			lct_c_vars.sideheader_sticky_height = 0;

			var main_height = window_height;
			var wpadminbar = 0;
			var fusion_header = 0;
			var fusion_title_bar = 0;
			var fusion_footer = 0;


			/**
			 * Avada Static Vars: Set
			 */
			if( lct_c_vars.is_sideheader_sticky ) {
				lct_c_vars.sideheader_sticky_height = lct_c_vars.sideheader_height;
			}


			/**
			 * Other Sets
			 */
			//noinspection JSJQueryEfficiency
			if( jQuery( '#wpadminbar' ).length ) {
				wpadminbar = jQuery( '#wpadminbar' ).actual( 'outerHeight' );

				main_height = main_height - wpadminbar;
			}


			if( lct_c_vars.is_fusion_header_wrapper ) {
				fusion_header = lct_c_vars.static_fusion_header;

				if( scroll_to_top !== 0 ) {
					fusion_header = lct_c_vars.s_fusion_header_wrapper.actual( 'outerHeight' );
				}

				main_height = main_height - fusion_header;
			}


			//noinspection JSJQueryEfficiency
			if(
				jQuery( '.fusion-page-title-bar' ).length &&
				jQuery( '.fusion-page-title-bar' ).is( ':visible' )
			) {
				fusion_title_bar = jQuery( '.fusion-page-title-bar' ).actual( 'outerHeight' );

				main_height = main_height - fusion_title_bar;
			}


			//noinspection JSJQueryEfficiency
			if( jQuery( '.fusion-footer' ).length ) {
				fusion_footer = jQuery( '.fusion-footer' ).actual( 'outerHeight' );

				main_height = main_height - fusion_footer;
			}


			/**
			 * Sideheader
			 */
			if( lct_c_vars.is_sideheader ) {
				if( lct_c_vars.is_sideheader_sticky ) {
					/**
					 * I don't know if we actually use this anymore, but we should keep it around
					 */
					//noinspection JSJQueryEfficiency
					if( jQuery( '.lca_home_logo' ).length ) {
						static_lca_home_logo = (jQuery( '.lca_home_logo' ).actual( 'outerHeight' ) + scroll_to_top);
					}
				}


				if( lct_c_vars.is_tablet ) {
					fusion_header = lct_c_vars.sideheader_height;
					main_height = main_height - fusion_header;
				}
			}


			/**
			 * We are don't getting things setup, let do something now
			 */
			/**
			 * We are don't getting things setup, let do something now
			 */


			/**
			 * Adjust Avada s_main height when it is too short
			 */
			if( lct_c_vars.is_main ) {
				lct_c_vars.s_main.css( {
					minHeight: main_height
				} );
			}


			/**
			 * Set anchor parameters if they exist
			 */
			//noinspection JSJQueryEfficiency
			if(
				fusion_header > 0 &&
				jQuery( '.fusion-menu-anchor' ).length
			) {
				jQuery( '.fusion-menu-anchor' ).css( {
					paddingTop: fusion_header,
					marginTop: (-1 * fusion_header)
				} );
			}


			/**
			 * Only run on 'sideheader' site
			 */
			if( lct_c_vars.is_sideheader ) {
				var s_mobi_menu_main = lct_c_vars.s_sideheader.find( '.fusion-mobile-nav-holder #mobile-menu-main' );


				/**
				 * Desktop device
				 */
				if( lct_c_vars.is_desktop ) {
					//NOTHING


					/**
					 * Mobile device
					 */
				} else {
					/**
					 * Make mobile sticky even when we are not using fusion sticky
					 */
					if(
						scroll_to_top !== 0 &&
						lct_c_vars.static_logo_header > 0 &&
						scroll_to_top >= lct_c_vars.static_logo_header
					) {
						if( lct_c_vars.is_menu_sticky_buttons ) {
							lct_c_vars.s_menu_sticky_buttons.css( {
								position: 'fixed',
								top: '0',
								width: '100%'
							} );
						}


						//noinspection JSJQueryEfficiency
						//if( jQuery( '#sliders-container' ).length ) {
						//	jQuery( '#sliders-container' ).css( {
						//		marginTop: lct_c_vars.menu_sticky_buttons_height
						//	} );
						//}
					} else {
						if( lct_c_vars.is_menu_sticky_buttons ) {
							lct_c_vars.s_menu_sticky_buttons.css( {
								position: '',
								top: '',
								width: ''
							} );
						}


						//noinspection JSJQueryEfficiency
						//if( jQuery( '#sliders-container' ).length ) {
						//	jQuery( '#sliders-container' ).css( {
						//		marginTop: ''
						//	} );
						//}
					}


					if(
						lct_c_vars.is_lct_mobi_menu &&
						!lct_cancel &&
						!lct_c_vars.menu_container_set
					) {
						lct_c_vars.menu_container_set = true;


						s_mobi_menu_main.css( {
							height: ''
						} );


						var mobile_menu_main_height = s_mobi_menu_main.actual( 'outerHeight' );

						lct_c_vars.mobile_nav_holder_height = lct_c_vars.mobile_nav_holder_height - lct_c_vars.static_fusion_header;

						lct_c_vars.mobile_nav_holder_height = lct_c_vars.mobile_nav_holder_height + static_lca_home_logo;


						if( mobile_menu_main_height >= lct_c_vars.mobile_nav_holder_height ) {
							s_mobi_menu_main.css( {
								height: lct_c_vars.mobile_nav_holder_height,
								overflowY: 'scroll'
							} );
						} else {
							s_mobi_menu_main.css( {
								height: '',
								overflowY: 'hidden'
							} );
						}
					}
				}
			}
		} );
	}
} );


/**
 * Check and update the status of the 'mobile_nav_holder'
 */
function lct_check_mobile_nav_holder_visible() {
	if( lct_c_vars.is_mobile_nav_holder ) {
		lct_c_vars.is_mobile_nav_holder_visible = lct_c_vars.s_mobile_nav_holder.is( ':visible' );
	}
}


/**
 * Set the 'fusion_header_wrapper'
 */
function lct_set_fusion_header_wrapper() {
	if( lct_c_vars.is_fusion_header_wrapper ) {
		lct_c_vars.static_fusion_header = lct_c_vars.s_fusion_header_wrapper.actual( 'outerHeight' );
	} else if( lct_c_vars.is_fusion_header_wrapper_lct ) {
		lct_c_vars.static_fusion_header = lct_c_vars.s_fusion_header_wrapper_lct.actual( 'outerHeight' );
	}

	if( lct_c_vars.is_sideheader ) {
		lct_c_vars.sideheader_height = lct_c_vars.s_sideheader.actual( 'outerHeight' );
		lct_c_vars.static_fusion_header = lct_c_vars.sideheader_height;
	}


	if( lct_c_vars.is_menu_sticky_buttons ) {
		lct_c_vars.menu_sticky_buttons_height = lct_c_vars.s_menu_sticky_buttons.actual( 'outerHeight' );

		if( !lct_c_vars.static_logo_header_set ) {
			lct_c_vars.static_logo_header = lct_c_vars.static_fusion_header - lct_c_vars.menu_sticky_buttons_height;

			lct_c_vars.static_logo_header_set = true;
		}
	}
}


/**
 * Set the mobile status switches
 */
function lct_set_mobile_status() {
	var body_width = lct_c_vars.s_body.width();
	var window_width = body_width + scrollbar_width();

	lct_c_vars.is_desktop = true;
	lct_c_vars.is_tablet = false;


	lct_c_vars.s_body.removeClass( lct_c_vars.tablet_class );


	if( window_width <= mobile_threshold ) {
		lct_c_vars.is_desktop = false;
		lct_c_vars.is_tablet = true;


		lct_c_vars.s_body.removeClass( lct_c_vars.tablet_class ).addClass( lct_c_vars.tablet_class );
	}
}


/**
 * Reset everything, then we can recheck it.
 */
function lct_reset_header_stuff() {
	lct_c_vars.menu_container_set = false;
}


/**
 * Get the width of the scrollbar
 * Modified from: http://alexmansfield.com/javascript/css-jquery-screen-widths-scrollbars
 * Modified from: http://jdsharp.us/jQuery/minute/calculate-scrollbar-width.php
 */
function scrollbar_width() {
	var the_body = jQuery( 'body' );


	if( the_body.height() > jQuery( window ).height() ) {
		var calculation_content = jQuery( '<div style="width:50px;height:50px;overflow:hidden;position:absolute;top:-200px;left:-200px;"><div style="height:100px;"></div>' );
		the_body.append( calculation_content );
		var width_one = jQuery( 'div', calculation_content ).innerWidth();
		calculation_content.css( 'overflow', 'auto' );
		var width_two = jQuery( 'div', calculation_content ).innerWidth();
		jQuery( calculation_content ).remove();


		return (width_one - width_two);
	}


	return 0;
}


/**
 * https://github.com/dreamerslab/jquery.actual
 * Copyright 2012, Ben Lin (http://dreamerslab.com/)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Version: 1.0.19
 *
 * Requires: jQuery >= 1.2.3
 */
(function( factory ) {
	if( typeof define === 'function' && define.amd ) {
		// AMD. Register module depending on jQuery using requirejs define.
		define( [ 'jquery' ], factory );
	} else {
		// No AMD.
		factory( jQuery );
	}
}( function( $ ) {
	$.fn.addBack = $.fn.addBack || $.fn.andSelf;

	$.fn.extend( {

		actual: function( method, options ) {
			// check if the jQuery method exist
			if( !this[ method ] ) {
				throw '$.actual => The jQuery method "' + method + '" you called does not exist';
			}

			var defaults = {
				absolute: false,
				clone: false,
				includeMargin: false,
				display: 'block'
			};

			var configs = $.extend( defaults, options );

			var $target = this.eq( 0 );
			var fix, restore;

			if( configs.clone === true ) {
				fix = function() {
					var style = 'position: absolute !important; top: -1000 !important; ';

					// this is useful with css3pie
					$target = $target.clone().attr( 'style', style ).appendTo( 'body' );
				};

				restore = function() {
					// remove DOM element after getting the width
					$target.remove();
				};
			} else {
				var tmp = [];
				var style = '';
				var $hidden;

				fix = function() {
					// get all hidden parents
					$hidden = $target.parents().addBack().filter( ':hidden' );
					style += 'visibility: hidden !important; display: ' + configs.display + ' !important; ';

					if( configs.absolute === true ) {
						style += 'position: absolute !important; ';
					}

					// save the origin style props
					// set the hidden el css to be got the actual value later
					$hidden.each( function() {
						// Save original style. If no style was set, attr() returns undefined
						var $this = $( this );
						var thisStyle = $this.attr( 'style' );

						tmp.push( thisStyle );
						// Retain as much of the original style as possible, if there is one
						$this.attr( 'style', thisStyle ? thisStyle + ';' + style : style );
					} );
				};

				restore = function() {
					// restore origin style values
					$hidden.each( function( i ) {
						var $this = $( this );
						var _tmp = tmp[ i ];

						if( _tmp === undefined ) {
							$this.removeAttr( 'style' );
						} else {
							$this.attr( 'style', _tmp );
						}
					} );
				};
			}

			fix();
			// get the actual value with user specific method
			// it can be 'width', 'height', 'outerWidth', 'innerWidth'... etc
			// configs.includeMargin only works for 'outerWidth' and 'outerHeight'
			var actual = /(outer)/.test( method ) ?
				$target[ method ]( configs.includeMargin ) :
				$target[ method ]();

			restore();
			// IMPORTANT, this plugin only return the value of the first element
			return actual;
		}
	} );
} ));


/**
 * DO NOT USE
 * Wait to exec a function until an element is enabled
 *
 * @param selector
 * @param callback
 * @param time
 */
function lct_wait_for_enabled_selector( selector, callback, time ) {
	var disabled = jQuery( selector ).attr( 'disabled' );


	if(
		disabled === undefined ||
		disabled === false
	) {
		if( callback ) {
			this[ callback ]();
		}
	} else {
		if( time === undefined ) {
			time = 500;
		}


		setTimeout( function() {
			lct_wait_for_enabled_selector( selector, callback, time );
		}, time );
	}
}


/**
 * DO NOT USE
 * Wait to exec a function until an element is enabled
 *
 * @param selector
 * @param callback
 * @param time
 */
function lct_wait_for_existing_selector( selector, callback, time ) {
	if( typeof lct_c_vars.existing_selector_cbs === 'undefined' ) {
		lct_c_vars.existing_selector_cbs = {};
		lct_c_vars.existing_selector_intervals = {};
	}
	if( typeof lct_c_vars.existing_selector_cbs[ callback ] === 'undefined' ) {
		lct_c_vars.existing_selector_cbs[ callback ] = 0;
	}


	//if( jQuery( selector ).length ) {
	//if( callback ) {
	//if( lct_c_vars.existing_selector_cbs[ callback ] === 'hit' ) {
	//	return;
	//}


	//this[ callback ]();
	//lct_c_vars.existing_selector_cbs[ callback ] = 'hit';
	//}
	//} else {
	let limit = 15;
	if( time === undefined ) {
		time = 900;
	}


	//if( lct_c_vars.existing_selector_cbs[ callback ] === 'hit' ) {
	//	lct_c_vars.existing_selector_cbs[ callback ] = 0;
	//	return;
	//}
	//if( lct_c_vars.existing_selector_cbs[ callback ] >= limit ) {
	//	console.log( callback + ' Never hit' );
	//	lct_c_vars.existing_selector_cbs[ callback ] = 0;
	//	return;
	//}


	if( !lct_c_vars.existing_selector_intervals[ callback ] ) {
		lct_c_vars.existing_selector_intervals[ callback ] = setInterval( lct_wait_for_interval, time, limit, selector, callback );
	}


	//lct_c_vars.existing_selector_cbs[ callback ]++;
	//setTimeout( function() {
	//lct_wait_for_existing_selector_cbs( selector, callback, time );
	//}, time );
	//}
}


function lct_wait_for_interval( limit, selector, callback ) {
	lct_c_vars.existing_selector_cbs[ callback ]++;
	if( lct_c_vars.existing_selector_cbs[ callback ] >= limit ) {
		//console.log( callback + ' Never hit' );
		clearInterval( lct_c_vars.existing_selector_intervals[ callback ] );
		lct_c_vars.existing_selector_cbs[ callback ] = 0;
		lct_c_vars.existing_selector_intervals[ callback ] = 0;
		return;
	}


	if(
		jQuery( selector ).length
		&& callback
	) {
		//if( lct_c_vars.existing_selector_cbs[ callback ] === 'hit' ) {
		//	return;
		//}


		this[ callback ]();


		/**
		 * Cancel the interval
		 */
		//console.log( callback + ' ' + lct_c_vars.existing_selector_intervals[ callback ] + ' HIT' );
		clearInterval( lct_c_vars.existing_selector_intervals[ callback ] );
		lct_c_vars.existing_selector_cbs[ callback ] = 0;
		lct_c_vars.existing_selector_intervals[ callback ] = 0;
	}
}


/**
 * Add no scrolling to iframes
 */
function lct_lazyframe_update() {
	jQuery( '.lazyframe' ).each( function() {
		jQuery( this ).find( 'iframe' ).attr( 'scrolling', 'no' );
	} );
}
