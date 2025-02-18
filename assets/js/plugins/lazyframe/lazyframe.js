!function( e, t ) {
	"object" == typeof exports && "undefined" != typeof module ? module.exports = t() : "function" == typeof define && define.amd ? define( t ) : e.lazyframe = t()
}( this, function() {
	"use strict";
	var e = Object.assign || function( e ) {
		for( var t = 1; t < arguments.length; t++ ) {
			var n = arguments[ t ];
			for( var i in n ) Object.prototype.hasOwnProperty.call( n, i ) && (e[ i ] = n[ i ])
		}
		return e
	};
	return function() {
		function t( t ) {
			if( d = e( {}, m, arguments.length <= 1 ? void 0 : arguments[ 1 ] ), "string" == typeof t ) {
				for( var i = document.querySelectorAll( t ), o = 0; o < i.length; o++ ) n( i[ o ] );
			} else if( void 0 === t.length ) {
				n( t );
			} else if( t.length > 1 ) {
				for( var r = 0; r < t.length; r++ ) n( t[ r ] );
			} else {
				n( t[ 0 ] );
			}
			d.lazyload && a()
		}

		function n( e ) {
			var t = this;
			if( e instanceof HTMLElement != !1 && !e.classList.contains( "lazyframe--loaded" ) ) {
				var n = { el: e, settings: i( e ) };
				n.el.addEventListener( "click", function() {
					n.el.appendChild( n.iframe );
					var i = e.querySelectorAll( "iframe" );
					n.settings.onAppend.call( t, i[ 0 ] )
				} ), d.lazyload ? l( n ) : u( n, !!n.settings.thumbnail )
			}
		}

		function i( t ) {
			var n = Array.prototype.slice.apply( t.attributes ).filter( function( e ) {
				return "" !== e.value
			} ).reduce( function( e, t ) {
				return e[ 0 === t.name.indexOf( "data-" ) ? t.name.split( "data-" )[ 1 ] : t.name ] = t.value, e
			}, {} ), i = e( {}, d, n, { y: t.offsetTop, parameters: o( n.src ) } );
			if( i.vendor ) {
				var r = i.src.match( p.regex[ i.vendor ] );
				i.id = p.condition[ i.vendor ]( r )
			}
			return i
		}

		function o( e ) {
			var t = e.split( "?" );
			if( t[ 1 ] ) {
				t = t[ 1 ];
				return -1 !== t.indexOf( "autoplay" ) ? t : t + "&autoplay=1"
			}
			return "autoplay=1"
		}

		function r( e ) {
			return !!e.vendor && ((!e.title || !e.thumbnail) && ("youtube" !== e.vendor && "youtube_nocookie" !== e.vendor || !!e.apikey))
		}

		function u( e ) {
			var t = this;
			r( e.settings ) ? s( e, function( n, i ) {
				if( !n ) {
					var o = i[ 0 ], r = i[ 1 ];
					if( r.settings.title || (r.settings.title = p.response[ r.settings.vendor ].title( o )), !r.settings.thumbnail ) {
						var u = p.response[ r.settings.vendor ].thumbnail( o );
						r.settings.thumbnail = u, e.settings.onThumbnailLoad.call( t, u )
					}
					l( r, !0 )
				}
			} ) : l( e, !0 )
		}

		function s( e, t ) {
			var n = p.endpoints[ e.settings.vendor ]( e.settings ), i = new XMLHttpRequest;
			i.open( "GET", n, !0 ), i.onload = function() {
				if( i.status >= 200 && i.status < 400 ) {
					var n = JSON.parse( i.responseText );
					t( null, [ n, e ] )
				} else {
					t( !0 )
				}
			}, i.onerror = function() {
				t( !0 )
			}, i.send()
		}

		function a() {
			var e = this, t = window.innerHeight, n = f.length, i = function( t, i ) {
				t.settings.initialized = !0, t.el.classList.add( "lazyframe--loaded" ), n--, u( t ), t.settings.initinview && t.el.click(), t.settings.onLoad.call( e, t )
			};
			f.filter( function( e ) {
				return e.settings.y < t
			} ).forEach( i );
			var o = function( e, t, n ) {
				var i = void 0;
				return function() {
					var o = this, r = arguments, u = function() {
						i = null, n || e.apply( o, r )
					}, s = n && !i;
					clearTimeout( i ), i = setTimeout( u, t ), s && e.apply( o, r )
				}
			}( function() {
				s = r < window.pageYOffset, r = window.pageYOffset, s && f.filter( function( e ) {
					return e.settings.y < t + r && !1 === e.settings.initialized
				} ).forEach( i ), 0 === n && window.removeEventListener( "scroll", o, !1 )
			}, d.debounce ), r = 0, s = !1;
			window.addEventListener( "scroll", o, !1 )
		}

		function l( e, t ) {
			if( e.iframe = c( e.settings ), e.settings.thumbnail && t && (e.el.style.backgroundImage = "url(" + e.settings.thumbnail + ")"), e.settings.title && 0 === e.el.children.length ) {
				var n = document.createDocumentFragment(), i = document.createElement( "span" );
				i.className = "lazyframe__title", i.innerHTML = e.settings.title, n.appendChild( i ), e.el.appendChild( n )
			}
			d.lazyload || (e.el.classList.add( "lazyframe--loaded" ), e.settings.onLoad.call( this, e ), f.push( e )), e.settings.initialized || f.push( e )
		}

		function c( e ) {
			var t = document.createDocumentFragment(), n = document.createElement( "iframe" );
			if( e.vendor && (e.src = p.src[ e.vendor ]( e )), n.setAttribute( "id", "lazyframe-" + e.id ), n.setAttribute( "src", e.src ), n.setAttribute( "frameborder", 0 ), n.setAttribute( "allowfullscreen", "" ), n.setAttribute( "allow", "autoplay;encrypted-media" ), "vine" === e.vendor ) {
				var i = document.createElement( "script" );
				i.setAttribute( "src", "https://platform.vine.co/static/scripts/embed.js" ), t.appendChild( i )
			}
			return t.appendChild( n ), t
		}

		var d = void 0, f = [], m = {
			vendor: void 0, id: void 0, src: void 0, thumbnail: void 0, title: void 0, apikey: void 0, initialized: !1, parameters: void 0, y: void 0, debounce: 250, lazyload: !0, initinview: !1, onLoad: function( e ) {
			}, onAppend: function( e ) {
			}, onThumbnailLoad: function( e ) {
			}
		}, p = {
			regex: { youtube_nocookie: /(?:youtube-nocookie\.com\/\S*(?:(?:\/e(?:mbed))?\/|watch\?(?:\S*?&?v\=)))([a-zA-Z0-9_-]{6,11})/, youtube: /(?:youtube\.com\/\S*(?:(?:\/e(?:mbed))?\/|watch\?(?:\S*?&?v\=))|youtu\.be\/)([a-zA-Z0-9_-]{6,11})/, vimeo: /vimeo\.com\/(?:video\/)?([0-9]*)(?:\?|)/, vine: /vine.co\/v\/(.*)/ }, condition: {
				youtube: function( e ) {
					return !(!e || 11 != e[ 1 ].length) && e[ 1 ]
				}, youtube_nocookie: function( e ) {
					return !(!e || 11 != e[ 1 ].length) && e[ 1 ]
				}, vimeo: function( e ) {
					return !!(e && 9 === e[ 1 ].length || 8 === e[ 1 ].length) && e[ 1 ]
				}, vine: function( e ) {
					return !(!e || 11 !== e[ 1 ].length) && e[ 1 ]
				}
			}, src: {
				youtube: function( e ) {
					return "https://www.youtube.com/embed/" + e.id + "/?" + e.parameters
				}, youtube_nocookie: function( e ) {
					return "https://www.youtube-nocookie.com/embed/" + e.id + "/?" + e.parameters
				}, vimeo: function( e ) {
					return "https://player.vimeo.com/video/" + e.id + "/?" + e.parameters
				}, vine: function( e ) {
					return "https://vine.co/v/" + e.id + "/embed/simple"
				}
			}, endpoints: {
				youtube: function( e ) {
					return "https://www.googleapis.com/youtube/v3/videos?id=" + e.id + "&key=" + e.apikey + "&fields=items(snippet(title,thumbnails))&part=snippet"
				}, youtube_nocookie: function( e ) {
					return "https://www.googleapis.com/youtube/v3/videos?id=" + e.id + "&key=" + e.apikey + "&fields=items(snippet(title,thumbnails))&part=snippet"
				}, vimeo: function( e ) {
					return "https://vimeo.com/api/oembed.json?url=https%3A//vimeo.com/" + e.id
				}, vine: function( e ) {
					return "https://vine.co/oembed.json?url=https%3A%2F%2Fvine.co%2Fv%2F" + e.id
				}
			}, response: {
				youtube: {
					title: function( e ) {
						return e.items[ 0 ].snippet.title
					}, thumbnail: function( e ) {
						var t = e.items[ 0 ].snippet.thumbnails;
						return (t.maxres || t.standard || t.high || t.medium || t.default).url
					}
				}, youtube_nocookie: {
					title: function( e ) {
						return e.items[ 0 ].snippet.title
					}, thumbnail: function( e ) {
						var t = e.items[ 0 ].snippet.thumbnails;
						return (t.maxres || t.standard || t.high || t.medium || t.default).url
					}
				}, vimeo: {
					title: function( e ) {
						return e.title
					}, thumbnail: function( e ) {
						return e.thumbnail_url
					}
				}, vine: {
					title: function( e ) {
						return e.title
					}, thumbnail: function( e ) {
						return e.thumbnail_url
					}
				}
			}
		};
		return t
	}()
} );
