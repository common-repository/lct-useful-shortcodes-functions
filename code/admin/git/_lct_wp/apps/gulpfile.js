//v8.4RC1

//REQUIRED
var gv = {
	'site_secure': true,
	'site': 'https://www.wp.eetah.com.eetah.com/',
	'dev': '_dev',
	'content': '../x/lc-content',
	'theme': '/themes/a/custom',
	'dir_assets': '/assets',
	'dir_admin_assets': '/assets/wp-admin',
	'plugin': '/plugins/edit',
};


const { gulp, src, dest, watch } = require( 'gulp' ),
	browserSync = require( 'browser-sync' ).create(),
	plumber = require( 'gulp-plumber' ),
	rename = require( 'gulp-rename' ),
	scss = require( 'gulp-sass' ),
	uglify = require( 'gulp-uglify' );
//ENDzz :: REQUIRED


//scripts
/**
 * Do the actual work
 * @param var_src
 * @param var_dest
 */
function script_process_default( var_src, var_dest ) {
	src( var_src ) //File the source directory
		.pipe( rename( { suffix: '.min' } ) ) //Add .min to the file
		.pipe( uglify() ) //Minify the content of the file
		.pipe( dest( var_dest ) ); //Save the file
}


/**
 * Process all our scripts locations at once
 */
function process_all_scripts( cb ) {
	script_process_default( g_dev_src(), g_live_src() ); //Theme
	script_process_default( g_admin_dev_src(), g_admin_live_src() ); //Theme
	//uncomment for plugin - script_process_default( g_dev_src( 'plugin' ), g_live_src( 'plugin' ) ); //Default Plugin Front-end
	//uncomment for plugin - script_process_default( g_admin_dev_src( 'plugin' ), g_admin_live_src( 'plugin' ) ); //Default Plugin Back-end


	cb();
}


/**
 * Watch all our scripts locations at once
 */
function watch_all_scripts() {
	watch(
		[
			g_dev_src(),
			g_admin_dev_src(),
			g_dev_src( 'plugin' ),
			g_admin_dev_src( 'plugin' )
		],
		process_all_scripts
	);
}


//scss
/**
 * Do the actual work
 * @param var_src
 * @param var_dest
 */
function scss_process_default( var_src, var_dest ) {
	src( var_src ) //File the source directory
		.pipe( plumber() )
		.pipe( rename( { suffix: '.min' } ) ) //Add .min to the file
		.pipe( scss( { outputStyle: 'compressed' } ) ) //Minify the content of the file
		.on( 'error', scss.logError )
		.pipe( plumber.stop() )
		.pipe( dest( var_dest ) ); //Save the file
}


/**
 * Process all our scss locations at once
 */
function process_all_scss( cb ) {
	scss_process_default( [ g_dev_src( null, 'scss' ), '!' + g_dev_src( null, 'scss', 'custom' ) ], g_live_src( null, 'css' ) ); //Theme
	scss_process_default( g_dev_src( null, 'scss', 'custom' ), g_live_src( null, null, 'theme_only' ) ); //Theme - produce the minified master custom.css file
	//uncomment for plugin - scss_process_default( g_dev_src( 'plugin', 'scss' ), g_live_src( 'plugin', 'css' ) ); //Default Plugin Front-end
	//uncomment for plugin - scss_process_default( g_admin_dev_src( 'plugin', 'scss' ), g_admin_live_src( 'plugin', 'css' ) ); //Default Plugin Back-end


	cb();
}


/**
 * Watch all our scss locations at once
 */
function watch_all_scss() {
	watch(
		[
			g_dev_src( null, 'scss' ),
			g_dev_src( null, 'scss', 'custom' ),
			//uncomment for plugin - g_dev_src( 'plugin', 'scss' ),
			//uncomment for plugin - g_admin_dev_src( 'plugin', 'scss' )
		],
		process_all_scss
	);
}


//browser-sync
/**
 * Do the actual work
 * @param var_src
 * @param var_dest
 */
function run_bsync( var_src, var_dest ) {
	browserSync.init( {
		https: gv.site_secure,
		proxy: gv.site,
		files: [ '../**/*.css', '../**/*.js' ]
	} );


	watch(
		[
			g_live_src( null, null, 'theme_only' ) + '/**/*.php',
			g_live_src( null, null, 'theme_only' ) + '/**/*.html',
			g_live_src( null, null, 'theme_only' ) + '/**/*.htm',
		],
		bsync_reload
	);


	//gulp.task( 'bsync:scripts:watch', [ 'scripts' ], function() { browserSync.reload(); } );
	//gulp.task( 'bsync:scss:watch', [ 'scss' ], function() { browserSync.reload(); } );
}


/**
 * Reload bsync
 */
function bsync_reload() {
	browserSync.reload;
}


//https://gist.github.com/jeromecoupe/0b807b0c1050647eb340360902c3203a
// export tasks
exports.process_all_scripts = process_all_scripts;
exports.process_all_scss = process_all_scss;
exports.watch_all_scripts = watch_all_scripts;
exports.watch_all_scss = watch_all_scss;
exports.run_bsync = run_bsync;


/**
 * Dev src creator
 *
 * @param plugin (plugin OR custom_plugin)
 * @param type (js OR scss OR css)
 * @param type_search_override (file name)
 */
function g_dev_src( plugin = null, type = 'js', type_search_override = null ) {
	return g_src( 'dev', plugin, type, type_search_override );
}


/**
 * Live src creator
 *
 * @param plugin (plugin OR custom_plugin)
 * @param type (js OR scss OR css)
 * @param type_search_override (file name)
 */
function g_live_src( plugin = null, type = 'js', type_search_override = null ) {
	return g_src( 'live', plugin, type, type_search_override );
}


/**
 * Dev Admin src creator
 *
 * @param plugin (plugin OR custom_plugin)
 * @param type (js OR scss OR css)
 * @param type_search_override (file name)
 */
function g_admin_dev_src( plugin = null, type = 'js', type_search_override = null ) {
	return g_src( 'dev_admin', plugin, type, type_search_override );
}


/**
 * Live Admin src creator
 *
 * @param plugin (plugin OR custom_plugin)
 * @param type (js OR scss OR css)
 * @param type_search_override (file name)
 */
function g_admin_live_src( plugin = null, type = 'js', type_search_override = null ) {
	return g_src( 'live_admin', plugin, type, type_search_override );
}


/**
 * Main src creator
 *
 * @param source (dev OR live OR dev_admin OR live_admin)
 * @param plugin (plugin OR custom_plugin)
 * @param type (js OR scss OR css)
 * @param type_search_override (full file name)
 */
function g_src( source, plugin = null, type = 'js', type_search_override = null ) {
	let r = '',
		is_theme = false,
		js = '/js/**/!(*.min).js',
		scss = '/scss/**/!(*.min).scss',
		css = '/css/**/!(*.min).css';


	if(
		plugin && plugin !== 'plugin'
	) {
		r = gv[ plugin ];
	} else if(
		plugin && gv.plugin
	) {
		r = gv.plugin;
	} else {
		is_theme = true;


		if(
			source === 'live' || source === 'live_admin'
		) {
			r = gv.theme;
		}
	}


	if( !is_theme ) {
		if(
			source === 'dev' || source === 'live'
		) {
			r += gv.dir_assets;
		} else {
			r += gv.dir_admin_assets;
		}
	} else if( source === 'dev_admin' ) {
		r += '/wp-admin';
	}


	if(
		!type_search_override && (
			source === 'live' || source === 'live_admin'
		)
	) {
		r += '/' + type;
	}


	if(
		source === 'live' || source === 'live_admin'
	) {
		if( source === 'live_admin' ) {
			r = gv.content + r + '/wp-admin';
		} else {
			r = gv.content + r;
		}


		if( type_search_override === 'theme_only' ) {
			r = r.replace( '/custom', '' );
		} else if( type_search_override ) {
			r = r.replace( '/custom', '' );
			r += '/' + type_search_override + '.' + type;
		}
	} else if( type_search_override ) {
		r = gv.dev + r;
		r = r.replace( '/custom', '' );
		r += '/' + type + '/' + type_search_override + '.' + type;
	} else {
		let file_type = '';

		r = gv.dev + r;


		switch( type ) {
			case 'js':
				file_type = js;
				break;


			case 'scss':
				file_type = scss;
				break;


			case 'css':
				file_type = css;
				break;
		}


		r += file_type;
	}


	return r;
}
