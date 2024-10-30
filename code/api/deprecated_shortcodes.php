<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! shortcode_exists( 'up_root' ) ) {
	/**
	 * We should not use up_root anymore, but it may be used on a really old site
	 *
	 * @param $a
	 * @param $content
	 * @param $shortcode
	 *
	 * @return mixed
	 * @deprecated 5.40.24
	 * @since      0.0
	 * @verified   2016.11.29
	 */
	function deprecated_lct_up_root(
		/** @noinspection PhpUnusedParameterInspection */
		$a,
		/** @noinspection PhpUnusedParameterInspection */
		$content,
		$shortcode
	) {
		add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_shortcode', 10, 3 );
		_deprecated_function( $shortcode, '5.40.24', 'lct_path_up() OR [path_up]' );
		remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_shortcode' );


		return lct_path_up();
	}


	add_shortcode( 'up_root', 'deprecated_lct_up_root' );
}


/**
 * [custom_php page=""]
 * Grab some custom php when this shortcode is called
 *
 * @param $a
 * @param $content
 * @param $shortcode
 *
 * @return bool|string
 * @deprecated 5.40.24
 * @since      0.0
 * @verified   2016.09.27
 */
function deprecated_lct_php(
	$a,
	/** @noinspection PhpUnusedParameterInspection */
	$content,
	$shortcode
) {
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_shortcode', 10, 3 );
	_deprecated_function( $shortcode, '5.40.24' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_shortcode' );


	extract(
		shortcode_atts(
			[
				'page' => trim( $_SERVER['REQUEST_URI'], "/" ),
			],
			$a
		)
	);


	if ( empty( $page ) ) {
		return false;
	}


	$r                = [];
	$uploads_dir_path = lct_path_up() . '/lct/';
	$file             = 'php/' . $page . '.php';

	if ( file_exists( $uploads_dir_path . $file ) ) {
		$r[] = file_get_contents( $uploads_dir_path . $file );
	}


	return lct_return( $r );
}


add_shortcode( 'custom_php', 'deprecated_lct_php' );


/**
 * @param $a
 * @param $content
 * @param $shortcode
 *
 * @return string
 * @deprecated 5.40.24
 * @since      0.0
 * @verified   2016.09.27
 */
function deprecated_lct_get_test(
	/** @noinspection PhpUnusedParameterInspection */
	$a,
	/** @noinspection PhpUnusedParameterInspection */
	$content,
	$shortcode
) {
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_shortcode', 10, 3 );
	_deprecated_function( $shortcode, '5.40.24' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_shortcode' );


	return 'Deprecated shortcode... See: error_log for more info.';
}


add_shortcode( 'get_test', 'deprecated_lct_get_test' );


if ( ! shortcode_exists( 'copyyear' ) ) {
	/**
	 * [copyyear]
	 * Get the current Year i.e. 2014
	 *
	 * @param $a
	 * @param $content
	 * @param $shortcode
	 *
	 * @return bool|string
	 * @deprecated 5.40.24
	 * @since      0.0
	 * @verified   2016.09.27
	 */
	function deprecated_lct_copyyear(
		/** @noinspection PhpUnusedParameterInspection */
		$a,
		/** @noinspection PhpUnusedParameterInspection */
		$content,
		$shortcode
	) {
		add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_shortcode', 10, 3 );
		_deprecated_function( $shortcode, '5.40.24', '[lct_current_year]' );
		remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_shortcode' );


		return date( 'Y' );
	}


	add_shortcode( 'copyyear', 'deprecated_lct_copyyear' );
}


if ( ! shortcode_exists( 'css' ) ) {
	/**
	 * Grab some custom css when this shortcode is called
	 *
	 * @param $a
	 * @param $content
	 * @param $shortcode
	 *
	 * @return bool|string
	 * @deprecated 5.40.24
	 * @since      0.0
	 * @verified   2016.09.27
	 */
	function deprecated_lct_css_uploads_dir(
		$a,
		/** @noinspection PhpUnusedParameterInspection */
		$content,
		$shortcode
	) {
		add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_shortcode', 10, 3 );
		_deprecated_function( $shortcode, '5.40.24', '[lct_css] OR [theme_css]' );
		remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_shortcode' );


		extract(
			shortcode_atts(
				[
					'page'  => trim( $_SERVER['REQUEST_URI'], "/" ),
					'write' => false,
				],
				$a
			)
		);


		if ( empty( $page ) ) {
			return false;
		}


		$r                = [];
		$file             = 'css/' . $page . '.css';
		$uploads_dir_path = lct_path_up() . '/lct/';
		$uploads_dir_url  = lct_url_up() . '/lct/';
		$full_path        = $uploads_dir_path . $file;
		$full_url         = $uploads_dir_url . $file;


		if ( ! file_exists( $full_path ) ) {
			$full_path = lct_get_root_path( 'lct/' . $file );
			$full_url  = lct_get_root_url( 'lct/' . $file );


			if ( ! file_exists( $full_path ) ) {
				return false;
			}
		}


		if ( ! empty( $write ) ) {
			$r[] = '<style>';
			$r[] = file_get_contents( $full_path );
			$r[] = '</style>';
		} else {
			$r[] = '<link rel="stylesheet" type="text/css" href="' . $full_url . '">';
		}


		return lct_return( $r );
	}


	add_shortcode( 'css', 'deprecated_lct_css_uploads_dir' );
}


if ( ! shortcode_exists( 'js' ) ) {
	/**
	 * Grab some custom js when this shortcode is called
	 *
	 * @param $a
	 * @param $content
	 * @param $shortcode
	 *
	 * @return bool|string
	 * @deprecated 5.40.24
	 * @since      0.0
	 * @verified   2016.09.27
	 */
	function deprecated_lct_js_uploads_dir(
		$a,
		/** @noinspection PhpUnusedParameterInspection */
		$content,
		$shortcode
	) {
		add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_shortcode', 10, 3 );
		_deprecated_function( $shortcode, '5.40.24', '[lct_js] OR [theme_js]' );
		remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_shortcode' );


		extract(
			shortcode_atts(
				[
					'page'  => trim( $_SERVER['REQUEST_URI'], "/" ),
					'write' => false,
				],
				$a
			)
		);


		if ( empty( $page ) ) {
			return false;
		}


		$r                = [];
		$file             = 'js/' . $page . '.js';
		$uploads_dir_path = lct_path_up() . '/lct/';
		$uploads_dir_url  = lct_url_up() . '/lct/';
		$full_path        = $uploads_dir_path . $file;
		$full_url         = $uploads_dir_url . $file;


		if ( ! file_exists( $full_path ) ) {
			$full_path = lct_get_root_path( 'lct/' . $file );
			$full_url  = lct_get_root_url( 'lct/' . $file );


			if ( ! file_exists( $full_path ) ) {
				return false;
			}
		}


		if ( ! empty( $write ) ) {
			$r[] = '<script>';
			$r[] = file_get_contents( $full_path );
			$r[] = '</script>';
		} else {
			$r[] = sprintf( '<%s type="text/javascript" src="%s"></%s>', 'script', $full_url, 'script' );
		}


		return lct_return( $r );
	}


	add_shortcode( 'js', 'deprecated_lct_js_uploads_dir' );
}


/**
 * [lct_acf_form]
 * shortcode for acf_form
 *
 * @param $a
 * @param $content
 * @param $shortcode
 *
 * @return bool
 * @deprecated 2017.34
 * @since      0.0
 * @verified   2016.09.29
 */
function lct_form_shortcode(
	$a,
	/** @noinspection PhpUnusedParameterInspection */
	$content,
	$shortcode
) {
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_shortcode', 10, 3 );
	_deprecated_function( $shortcode, '2017.34', '[lct_acf_form2]' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_shortcode' );


	if ( empty( $a['field'] ) ) {
		return false;
	}


	$options     = [];
	$our_options = [];


	if ( $a['o_field_groups'] ) {
		$options['field_groups'] = $a['o_field_groups'];
	}

	if ( $a['o_submit_value'] ) {
		$options['submit_value'] = $a['o_submit_value'];
	}

	$our_options['wrapper_class'] = $a['class'];

	$our_options['wrapper_id'] = $a['id'];


	/** @noinspection PhpDeprecationInspection */
	return lct_acf_form( $a['field'], $options, $our_options, true );
}


add_shortcode( zxzu( 'acf_form' ), 'lct_form_shortcode' );


/**
 * [lct_acf_form_full]
 * shortcode for acf_form full
 *
 * @param $a
 * @param $content
 * @param $shortcode
 *
 * @return bool
 * @deprecated 2017.34
 * @since      0.0
 * @verified   2016.09.29
 */
function lct_form_full_shortcode(
	$a,
	/** @noinspection PhpUnusedParameterInspection */
	$content,
	$shortcode
) {
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_shortcode', 10, 3 );
	_deprecated_function( $shortcode, '2017.34', '[lct_acf_form2]' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_shortcode' );


	$options     = [];
	$our_options = [];
	$new_post    = [];


	foreach ( $a as $k => $v ) {
		if ( in_array( $k, [ 'o_new_post', 'access', 'class', 'id' ] ) ) {
			continue;
		}

		if ( strpos( $k, 'o_new_' ) !== false ) {
			$k_only = str_replace( 'o_new_', '', $k );

			if ( $a[ $k ] ) {
				$new_post[ $k_only ] = $a[ $k ];
			}
		} elseif ( strpos( $k, 'o_' ) !== false ) {
			$k_only = str_replace( 'o_', '', $k );

			if ( $a[ $k ] ) {
				$options[ $k_only ] = $a[ $k ];
			}
		} else {
			if ( $a[ $k ] ) {
				$our_options[ $k ] = $a[ $k ];
			}
		}
	}


	if (
		$a['o_new_post']
		&& empty( $a['o_post_id'] )
	) {
		$options['post_id'] = 'new_post';

		if ( ! isset( $new_post['post_type'] ) ) {
			$new_post['post_type'] = 'post';
		}


		if ( ! isset( $new_post['post_status'] ) ) {
			$new_post['post_status'] = 'publish';
		}


		$options['new_post'] = $new_post;
	}


	if ( $a['o_field_groups'] ) {
		$options['field_groups'] = explode( ',', $a['o_field_groups'] );
	}


	$our_options['wrapper_class'] = $a['class'];
	$our_options['wrapper_id']    = $a['id'];


	/** @noinspection PhpDeprecationInspection */
	return lct_acf_form_full( $options, $our_options, true );
}


add_shortcode( zxzu( 'acf_form_full' ), 'lct_form_full_shortcode' );
