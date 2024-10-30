!function( t, e ) {
	"object" == typeof exports && "undefined" != typeof module ? module.exports = e() : "function" == typeof define && define.amd ? define( e ) : t.lazyframe = e()
}( this, function() {
	"use strict";
	var t = Object.assign || function( t ) {
		for( var e = 1; e < arguments.length; e++ ) {
			var n = arguments[ e ];
			for( var i in n ) Object.prototype.hasOwnProperty.call( n, i ) && (t[ i ] = n[ i ])
		}
		return t
	};
	return function() {
		function e( t ) {
			var e = this;
			if( t instanceof HTMLElement != 0 && !t.classList.contains( "lazyframe--loaded" ) ) {
				var i = { el: t, settings: n( t ) };
				i.el.addEventListener( "click", function() {
					i.el.appendChild( i.iframe );
					var n = t.querySelectorAll( "iframe" );
					i.settings.onAppend.call( e, n[ 0 ] )
				} ), a.lazyload ? r( i ) : o( i, i.settings.thumbnail )
			}
		}

		function n( e ) {
			var n = Array.prototype.slice.apply( e.attributes ).filter( function( t ) {
				return "" !== t.value
			} ).reduce( function( t, e ) {
				return t[ 0 === e.name.indexOf( "data-" ) ? e.name.split( "data-" )[ 1 ] : e.name ] = e.value, t
			}, {} ), o = t( {}, a, n, { y: e.offsetTop, parameters: i( n.src ) } );
			if( o.vendor ) {
				var r = o.src.match( u.regex[ o.vendor ] );
				o.id = u.condition[ o.vendor ]( r )
			}
			return o
		}

		function i( t ) {
			var e = t.split( "?" );
			return e[ 1 ] ? -1 !== (e = e[ 1 ]).indexOf( "autoplay" ) ? e : e + "&autoplay=1" : "autoplay=1"
		}

		function o( t ) {
			var e = this;
			!function( t ) {
				return !(!t.vendor || t.title && t.thumbnail || "youtube" === t.vendor && !t.apikey)
			}( t.settings ) ? r( t, !0 ) : function( t, e ) {
				var n = u.endpoints[ t.settings.vendor ]( t.settings ), i = new XMLHttpRequest;
				i.open( "GET", n, !0 ), i.onload = function() {
					if( i.status >= 200 && i.status < 400 ) {
						var n = JSON.parse( i.responseText );
						e( null, [ n, t ] )
					} else {
						e( !0 )
					}
				}, i.onerror = function() {
					e( !0 )
				}, i.send()
			}( t, function( n, i ) {
				if( !n ) {
					var o = i[ 0 ], a = i[ 1 ];
					if( a.settings.title || (a.settings.title = u.response[ a.settings.vendor ].title( o )), !a.settings.thumbnail ) {
						var s = u.response[ a.settings.vendor ].thumbnail( o );
						a.settings.thumbnail = s, t.settings.onThumbnailLoad.call( e, s )
					}
					r( a, !0 )
				}
			} )
		}

		function r( t, e ) {
			if( t.iframe = function( t ) {
				var e = document.createDocumentFragment(), n = document.createElement( "iframe" );
				if( t.vendor && (t.src = u.src[ t.vendor ]( t )), n.setAttribute( "id", "lazyframe-" + t.id ), n.setAttribute( "src", t.src ), n.setAttribute( "frameborder", 0 ), n.setAttribute( "allowfullscreen", "" ), n.setAttribute( "allow", "autoplay;encrypted-media" ), "vine" === t.vendor ) {
					var i = document.createElement( "script" );
					i.setAttribute( "src", "https://platform.vine.co/static/scripts/embed.js" ), e.appendChild( i )
				}
				return e.appendChild( n ), e
			}( t.settings ), t.settings.thumbnail && e && (t.el.style.backgroundImage = "url(" + t.settings.thumbnail + ")"), t.settings.title && 0 === t.el.children.length ) {
				var n = document.createDocumentFragment(), i = document.createElement( "span" );
				i.className = "lazyframe__title", i.innerHTML = t.settings.title, n.appendChild( i ), t.el.appendChild( n )
			}
			a.lazyload || (t.el.classList.add( "lazyframe--loaded" ), t.settings.onLoad.call( this, t ), s.push( t )), t.settings.initialized || s.push( t )
		}

		var a = void 0, s = [], l = {
			vendor: void 0, id: void 0, src: void 0, thumbnail: void 0, title: void 0, apikey: void 0, initialized: !1, parameters: void 0, y: void 0, debounce: 250, lazyload: !0, initinview: !1, onLoad: function( t ) {
			}, onAppend: function( t ) {
			}, onThumbnailLoad: function( t ) {
			}
		}, u = {
			regex: { youtube: /(?:youtube-nocookie\.com\/\S*(?:(?:\/e(?:mbed))?\/|watch\?(?:\S*?&?v\=))|youtu\.be\/)([a-zA-Z0-9_-]{6,11})/, vimeo: /vimeo\.com\/(?:video\/)?([0-9]*)(?:\?|)/, vine: /vine.co\/v\/(.*)/ }, condition: {
				youtube: function( t ) {
					return !(!t || 11 != t[ 1 ].length) && t[ 1 ]
				}, vimeo: function( t ) {
					return !!(t && 9 === t[ 1 ].length || 8 === t[ 1 ].length) && t[ 1 ]
				}, vine: function( t ) {
					return !(!t || 11 !== t[ 1 ].length) && t[ 1 ]
				}
			}, src: {
				youtube: function( t ) {
					return "https://www.youtube-nocookie.com/embed/" + t.id + "/?" + t.parameters
				}, vimeo: function( t ) {
					return "https://player.vimeo.com/video/" + t.id + "/?" + t.parameters
				}, vine: function( t ) {
					return "https://vine.co/v/" + t.id + "/embed/simple"
				}
			}, endpoints: {
				youtube: function( t ) {
					return "https://www.googleapis.com/youtube/v3/videos?id=" + t.id + "&key=" + t.apikey + "&fields=items(snippet(title,thumbnails))&part=snippet"
				}, vimeo: function( t ) {
					return "https://vimeo.com/api/oembed.json?url=https%3A//vimeo.com/" + t.id
				}, vine: function( t ) {
					return "https://vine.co/oembed.json?url=https%3A%2F%2Fvine.co%2Fv%2F" + t.id
				}
			}, response: {
				youtube: {
					title: function( t ) {
						return t.items[ 0 ].snippet.title
					}, thumbnail: function( t ) {
						var e = t.items[ 0 ].snippet.thumbnails;
						return e.maxres ? e.maxres.url : e.standard.url
					}
				}, vimeo: {
					title: function( t ) {
						return t.title
					}, thumbnail: function( t ) {
						return t.thumbnail_url
					}
				}, vine: {
					title: function( t ) {
						return t.title
					}, thumbnail: function( t ) {
						return t.thumbnail_url
					}
				}
			}
		};
		return function( n ) {
			if( a = t( {}, l, arguments.length <= 1 ? void 0 : arguments[ 1 ] ), "string" == typeof n ) {
				for( var i = document.querySelectorAll( n ), r = 0; r < i.length; r++ ) e( i[ r ] );
			} else if( void 0 === n.length ) {
				e( n );
			} else if( n.length > 1 ) {
				for( var u = 0; u < n.length; u++ ) e( n[ u ] );
			} else {
				e( n[ 0 ] );
			}
			a.lazyload && function() {
				var t = this, e = window.innerHeight, n = s.length, i = function( e, i ) {
					e.settings.initialized = !0, e.el.classList.add( "lazyframe--loaded" ), n--, o( e ), e.settings.initinview && e.el.click(), e.settings.onLoad.call( t, e )
				};
				s.filter( function( t ) {
					return t.settings.y < e
				} ).forEach( i );
				var r = function( t, e, n ) {
					var i = void 0;
					return function() {
						var o = this, r = arguments, a = n && !i;
						clearTimeout( i ), i = setTimeout( function() {
							i = null, n || t.apply( o, r )
						}, e ), a && t.apply( o, r )
					}
				}( function() {
					u = l < window.scrollY, l = window.scrollY, u && s.filter( function( t ) {
						return t.settings.y < e + l && !1 === t.settings.initialized
					} ).forEach( i ), 0 === n && window.removeEventListener( "scroll", r, !1 )
				}, a.debounce ), l = 0, u = !1;
				window.addEventListener( "scroll", r, !1 )
			}()
		}
	}()
} ), function( t, e ) {
	"object" == typeof exports && "undefined" != typeof module ? module.exports = e() : "function" == typeof define && define.amd ? define( e ) : t.lazyframe = e()
}( this, function() {
	"use strict";
	var t = Object.assign || function( t ) {
		for( var e = 1; e < arguments.length; e++ ) {
			var n = arguments[ e ];
			for( var i in n ) Object.prototype.hasOwnProperty.call( n, i ) && (t[ i ] = n[ i ])
		}
		return t
	};
	return function() {
		function e( t ) {
			var e = this;
			if( t instanceof HTMLElement != 0 && !t.classList.contains( "lazyframe--loaded" ) ) {
				var i = { el: t, settings: n( t ) };
				i.el.addEventListener( "click", function() {
					i.el.appendChild( i.iframe );
					var n = t.querySelectorAll( "iframe" );
					i.settings.onAppend.call( e, n[ 0 ] )
				} ), a.lazyload ? r( i ) : o( i, i.settings.thumbnail )
			}
		}

		function n( e ) {
			var n = Array.prototype.slice.apply( e.attributes ).filter( function( t ) {
				return "" !== t.value
			} ).reduce( function( t, e ) {
				return t[ 0 === e.name.indexOf( "data-" ) ? e.name.split( "data-" )[ 1 ] : e.name ] = e.value, t
			}, {} ), o = t( {}, a, n, { y: e.offsetTop, parameters: i( n.src ) } );
			if( o.vendor ) {
				var r = o.src.match( u.regex[ o.vendor ] );
				o.id = u.condition[ o.vendor ]( r )
			}
			return o
		}

		function i( t ) {
			var e = t.split( "?" );
			return e[ 1 ] ? -1 !== (e = e[ 1 ]).indexOf( "autoplay" ) ? e : e + "&autoplay=1" : "autoplay=1"
		}

		function o( t ) {
			var e = this;
			!function( t ) {
				return !(!t.vendor || t.title && t.thumbnail || "youtube" === t.vendor && !t.apikey)
			}( t.settings ) ? r( t, !0 ) : function( t, e ) {
				var n = u.endpoints[ t.settings.vendor ]( t.settings ), i = new XMLHttpRequest;
				i.open( "GET", n, !0 ), i.onload = function() {
					if( i.status >= 200 && i.status < 400 ) {
						var n = JSON.parse( i.responseText );
						e( null, [ n, t ] )
					} else {
						e( !0 )
					}
				}, i.onerror = function() {
					e( !0 )
				}, i.send()
			}( t, function( n, i ) {
				if( !n ) {
					var o = i[ 0 ], a = i[ 1 ];
					if( a.settings.title || (a.settings.title = u.response[ a.settings.vendor ].title( o )), !a.settings.thumbnail ) {
						var s = u.response[ a.settings.vendor ].thumbnail( o );
						a.settings.thumbnail = s, t.settings.onThumbnailLoad.call( e, s )
					}
					r( a, !0 )
				}
			} )
		}

		function r( t, e ) {
			if( t.iframe = function( t ) {
				var e = document.createDocumentFragment(), n = document.createElement( "iframe" );
				if( t.vendor && (t.src = u.src[ t.vendor ]( t )), n.setAttribute( "id", "lazyframe-" + t.id ), n.setAttribute( "src", t.src ), n.setAttribute( "frameborder", 0 ), n.setAttribute( "allowfullscreen", "" ), n.setAttribute( "allow", "autoplay;encrypted-media" ), "vine" === t.vendor ) {
					var i = document.createElement( "script" );
					i.setAttribute( "src", "https://platform.vine.co/static/scripts/embed.js" ), e.appendChild( i )
				}
				return e.appendChild( n ), e
			}( t.settings ), t.settings.thumbnail && e && (t.el.style.backgroundImage = "url(" + t.settings.thumbnail + ")"), t.settings.title && 0 === t.el.children.length ) {
				var n = document.createDocumentFragment(), i = document.createElement( "span" );
				i.className = "lazyframe__title", i.innerHTML = t.settings.title, n.appendChild( i ), t.el.appendChild( n )
			}
			a.lazyload || (t.el.classList.add( "lazyframe--loaded" ), t.settings.onLoad.call( this, t ), s.push( t )), t.settings.initialized || s.push( t )
		}

		var a = void 0, s = [], l = {
			vendor: void 0, id: void 0, src: void 0, thumbnail: void 0, title: void 0, apikey: void 0, initialized: !1, parameters: void 0, y: void 0, debounce: 250, lazyload: !0, initinview: !1, onLoad: function( t ) {
			}, onAppend: function( t ) {
			}, onThumbnailLoad: function( t ) {
			}
		}, u = {
			regex: { youtube: /(?:youtube-nocookie\.com\/\S*(?:(?:\/e(?:mbed))?\/|watch\?(?:\S*?&?v\=))|youtu\.be\/)([a-zA-Z0-9_-]{6,11})/, vimeo: /vimeo\.com\/(?:video\/)?([0-9]*)(?:\?|)/, vine: /vine.co\/v\/(.*)/ }, condition: {
				youtube: function( t ) {
					return !(!t || 11 != t[ 1 ].length) && t[ 1 ]
				}, vimeo: function( t ) {
					return !!(t && 9 === t[ 1 ].length || 8 === t[ 1 ].length) && t[ 1 ]
				}, vine: function( t ) {
					return !(!t || 11 !== t[ 1 ].length) && t[ 1 ]
				}
			}, src: {
				youtube: function( t ) {
					return "https://www.youtube-nocookie.com/embed/" + t.id + "/?" + t.parameters
				}, vimeo: function( t ) {
					return "https://player.vimeo.com/video/" + t.id + "/?" + t.parameters
				}, vine: function( t ) {
					return "https://vine.co/v/" + t.id + "/embed/simple"
				}
			}, endpoints: {
				youtube: function( t ) {
					return "https://www.googleapis.com/youtube/v3/videos?id=" + t.id + "&key=" + t.apikey + "&fields=items(snippet(title,thumbnails))&part=snippet"
				}, vimeo: function( t ) {
					return "https://vimeo.com/api/oembed.json?url=https%3A//vimeo.com/" + t.id
				}, vine: function( t ) {
					return "https://vine.co/oembed.json?url=https%3A%2F%2Fvine.co%2Fv%2F" + t.id
				}
			}, response: {
				youtube: {
					title: function( t ) {
						return t.items[ 0 ].snippet.title
					}, thumbnail: function( t ) {
						var e = t.items[ 0 ].snippet.thumbnails;
						return (e.maxres || e.standard || e.high || e.medium || e.default).url
					}
				}, vimeo: {
					title: function( t ) {
						return t.title
					}, thumbnail: function( t ) {
						return t.thumbnail_url
					}
				}, vine: {
					title: function( t ) {
						return t.title
					}, thumbnail: function( t ) {
						return t.thumbnail_url
					}
				}
			}
		};
		return function( n ) {
			if( a = t( {}, l, arguments.length <= 1 ? void 0 : arguments[ 1 ] ), "string" == typeof n ) {
				for( var i = document.querySelectorAll( n ), r = 0; r < i.length; r++ ) e( i[ r ] );
			} else if( void 0 === n.length ) {
				e( n );
			} else if( n.length > 1 ) {
				for( var u = 0; u < n.length; u++ ) e( n[ u ] );
			} else {
				e( n[ 0 ] );
			}
			a.lazyload && function() {
				var t = this, e = window.innerHeight, n = s.length, i = function( e, i ) {
					e.settings.initialized = !0, e.el.classList.add( "lazyframe--loaded" ), n--, o( e ), e.settings.initinview && e.el.click(), e.settings.onLoad.call( t, e )
				};
				s.filter( function( t ) {
					return t.settings.y < e
				} ).forEach( i );
				var r = function( t, e, n ) {
					var i = void 0;
					return function() {
						var o = this, r = arguments, a = n;
						clearTimeout( i ), i = setTimeout( function() {
							i = null, t.apply( o, r )
						}, e ), a && t.apply( o, r )
					}
				}( function() {
					u = l < window.scrollY, l = window.scrollY, u && s.filter( function( t ) {
						return t.settings.y < e + l && !1 === t.settings.initialized
					} ).forEach( i ), 0 === n && window.removeEventListener( "scroll", r, !1 )
				}, a.debounce ), l = 0, u = !1;
				window.addEventListener( "scroll", r, !1 )
			}()
		}
	}()
} );
