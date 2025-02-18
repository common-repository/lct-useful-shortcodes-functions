function q2w3_sidebar( options ) {

	if( !options.widgets ) {
		return false;
	}

	if( options.widgets.length < 1 ) {
		return false;
	}

	if( !options.sidebar ) {
		options.sidebar = 'q2w3-default-sidebar';
	}

	function widget() {
	} // widget class

	var widgets = [];

	var window_height = jQuery( window ).height();
	var document_height = jQuery( document ).height();
	var fixed_margin_top = options.margin_top;

	jQuery( '.q2w3-widget-clone-' + options.sidebar ).remove(); // clear fixed mode p1

	for( var i = 0; i < options.widgets.length; i++ ) {
		widget_obj = jQuery( '#' + options.widgets[ i ] );
		widget_obj.css( 'position', '' ); // clear fixed mode p2
		if( widget_obj.attr( 'id' ) ) {
			widgets[ i ] = new widget();
			widgets[ i ].obj = widget_obj;
			widgets[ i ].clone = widget_obj.clone();
			widgets[ i ].clone.children().remove();
			widgets[ i ].clone_id = widget_obj.attr( 'id' ) + '_clone';
			widgets[ i ].clone.addClass( 'q2w3-widget-clone-' + options.sidebar );
			widgets[ i ].clone.attr( 'id', widgets[ i ].clone_id );
			widgets[ i ].clone.css( 'height', widget_obj.height() );
			widgets[ i ].clone.css( 'visibility', 'hidden' );
			widgets[ i ].offset_top = widget_obj.offset().top;
			widgets[ i ].fixed_margin_top = fixed_margin_top;
			widgets[ i ].height = widget_obj.outerHeight( true );
			widgets[ i ].fixed_margin_bottom = fixed_margin_top + widgets[ i ].height;
			fixed_margin_top += widgets[ i ].height;
		} else {
			widgets[ i ] = false;
		}
	}

	var next_widgets_height = 0;

	var widget_parent_container;

	for( var i = widgets.length - 1; i >= 0; i-- ) {
		if( widgets[ i ] ) {
			widgets[ i ].next_widgets_height = next_widgets_height;
			widgets[ i ].fixed_margin_bottom += next_widgets_height;
			next_widgets_height += widgets[ i ].height;
			if( !widget_parent_container ) {
				widget_parent_container = widget_obj.parent();
				widget_parent_container.css( 'height', '' );
				widget_parent_container.height( widget_parent_container.height() );
			}
		}
	}

	jQuery( window ).off( 'load scroll.' + options.sidebar );

	for( var i = 0; i < widgets.length; i++ ) {
		if( widgets[ i ] ) {
			fixed_widget( widgets[ i ] );
		}
	}

	function fixed_widget( widget ) {

		var lct_title_bar_height = 0;
		var lct_footer = 0;

		if( jQuery( '.fusion-page-title-bar' ).length ) {
			lct_title_bar_height = jQuery( '.fusion-page-title-bar' ).actual( 'outerHeight' );
		}

		if( jQuery( '#footer' ).length ) {
			lct_footer = jQuery( '#footer' ).actual( 'outerHeight' );
		}

		var use_trigger_bottom = false;
		var trigger_top = widget.offset_top - widget.fixed_margin_top;
		var trigger_bottom = document_height - options.margin_bottom + lct_title_bar_height + lct_footer;

		var widget_width;
		if( options.width_inherit ) {
			widget_width = 'inherit';
		} else {
			widget_width = widget.obj.css( 'width' );
		}

		var style_applied_top = false;
		var style_applied_bottom = false;
		var style_applied_normal = false;

		jQuery( window ).on( 'scroll.' + options.sidebar, function( event ) {
			var scroll = jQuery( this ).scrollTop();

			if( scroll + widget.fixed_margin_bottom >= trigger_bottom ) { // fixed bottom
				if( !style_applied_bottom ) {
					widget.obj.css( 'position', 'fixed' );
					widget.obj.css( 'top', '' );
					widget.obj.css( 'width', widget_width );
					if( jQuery( '#' + widget.clone_id ).length <= 0 ) {
						widget.obj.before( widget.clone );
					}
					style_applied_bottom = true;
					style_applied_top = false;
					style_applied_normal = false;
				}
				widget.obj.css( 'bottom', scroll + window_height + widget.next_widgets_height - trigger_bottom );
			} else if( scroll >= trigger_top ) { // fixed top
				if( !style_applied_top ) {
					widget.obj.css( 'position', 'fixed' );
					widget.obj.css( 'top', widget.fixed_margin_top );
					widget.obj.css( 'bottom', '' );
					widget.obj.css( 'width', widget_width );
					if( jQuery( '#' + widget.clone_id ).length <= 0 ) {
						widget.obj.before( widget.clone );
					}
					style_applied_top = true;
					style_applied_bottom = false;
					style_applied_normal = false;
				}
			} else { // normal
				if( !style_applied_normal ) {
					widget.obj.css( 'position', '' );
					widget.obj.css( 'top', '' );
					widget.obj.css( 'width', '' );
					if( jQuery( '#' + widget.clone_id ).length > 0 ) {
						jQuery( '#' + widget.clone_id ).remove();
					}
					style_applied_normal = true;
					style_applied_top = false;
					style_applied_bottom = false;
				}
			}
		} ).trigger( 'scroll.' + options.sidebar );

		jQuery( window ).on( 'resize', function() {
			if( jQuery( window ).width() <= options.screen_max_width ) {
				jQuery( window ).off( 'load scroll.' + options.sidebar );
				widget.obj.css( 'position', '' );
				widget.obj.css( 'top', '' );
				widget.obj.css( 'width', '' );
				widget.obj.css( 'margin', '' );
				widget.obj.css( 'padding', '' );
				if( jQuery( '#' + widget.clone_id ).length > 0 ) {
					jQuery( '#' + widget.clone_id ).remove();
				}
				style_applied_normal = true;
				style_applied_top = false;
				style_applied_bottom = false;
			}
		} ).trigger( 'resize' );

	}

}
