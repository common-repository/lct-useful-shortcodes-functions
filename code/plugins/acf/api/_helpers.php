<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Save the info, so we can update the field later. That way it will for sure not get overwritten
 *
 * @param $selector
 * @param $value
 * @param $post_id
 *
 * @since    2017.58
 * @verified 2017.07.19
 */
function lct_acf_update_field_later( $selector, $value, $post_id )
{
	$later = [
		'selector' => $selector,
		'value'    => $value,
		'post_id'  => $post_id
	];


	lct_append_later( 'update_field_later', $later );
}


/**
 * Easily hide admin fields with this function
 *
 * @param $field_type
 * @param $fields
 *
 * @return string
 * @since    2017.80
 * @verified 2017.09.15
 */
function lct_acf_admin_field_hide( $field_type, $fields )
{
	$r          = [];
	$css_fields = [];


	if ( ! empty( $fields ) ) {
		$r[] = '<style>';


		foreach ( $fields as $field ) {
			$css_fields[] = sprintf( '.acf-field-object.acf-field-object-%s .acf-field.acf-field-setting-%s', str_replace( '_', '-', $field_type ), $field );
		}


		$r[] = lct_return( $css_fields, ',' );
		$r[] = '{display: none !important;}';


		$r[] = '</style>';
	}


	return lct_return( $r );
}


/**
 * Get the front-end class of a field type
 *
 * @param      $field_type
 * @param null $field
 *
 * @return string
 * @since    2017.80
 * @verified 2017.09.18
 */
function lct_acf_field_type_class( $field_type, $field = null )
{
	if ( ! empty( $field ) ) {
		$r = sprintf( '.acf-field.acf-field-%s .acf-%s', str_replace( '_', '-', $field_type ), $field );
	} else {
		$r = sprintf( '.acf-field.acf-field-%s', str_replace( '_', '-', $field_type ) );
	}


	return $r;
}


/**
 * Easily hide front-end fields with this function
 *
 * @param $field_type
 * @param $fields
 *
 * @return string
 * @since    2017.80
 * @verified 2017.09.15
 */
function lct_acf_field_hide( $field_type, $fields = [] )
{
	$r          = [];
	$css_fields = [];


	$r[] = '<style>';


	if ( ! empty( $fields ) ) {
		foreach ( $fields as $field ) {
			$css_fields[] = sprintf( '.acf-field.acf-field-%s .acf-%s', str_replace( '_', '-', $field_type ), $field );
		}
	} else {
		$css_fields[] = lct_acf_field_type_class( $field_type );
	}


	$r[] = lct_return( $css_fields, ',' );
	$r[] = '{display: none !important;}';


	$r[] = '</style>';


	return lct_return( $r );
}


/**
 * Array of builtin settings
 *
 * @param array $exclude
 *
 * @return array
 * @since    2017.80
 * @verified 2017.09.19
 */
function lct_acf_builtin_field_settings( $exclude = [] )
{
	$r = [
		'_name'      => 'IGNORE',
		'_prepare'   => 'IGNORE',
		'_valid'     => 'IGNORE',
		'class'      => 'IGNORE',
		'ID'         => 'IGNORE',
		'id'         => 'IGNORE',
		'key'        => 'IGNORE',
		'menu_order' => 'IGNORE',
		'parent'     => 'IGNORE',
		'prefix'     => 'IGNORE',
		'type'       => 'IGNORE', //Always Present
		'value'      => 'IGNORE',

		'conditional_logic' => 0, //Always Present
		'instructions'      => '', //Always Present
		'label'             => '', //post_title
		'name'              => '', //post_excerpt
		'required'          => 0, //Always Present
		'wrapper'           => [
			'width' => '', //Always Present
			'class' => '', //Always Present
			'id'    => '', //Always Present
		],
	];


	if ( ! empty( $exclude ) ) {
		foreach ( $r as $k => $v ) {
			if (
				$v
				&& in_array( $v, $exclude )
			) {
				unset( $r[ $k ] );
			}
		}
	}


	return $r;
}


/**
 * Register a Field Setting
 *
 * @param $field
 * @param $prefix_class
 *
 * @since    2017.80
 * @verified 2017.09.15
 */
function lct_acf_register_field_setting( $field, $prefix_class = null )
{
	$setting        = 'acf_field_settings';
	$field_settings = lct_get_setting( $setting, array_keys( lct_acf_builtin_field_settings() ) );


	if (
		$field
		&& ! in_array( $field, $field_settings )
	) {
		lct_append_setting( $setting, $field );

		if ( $prefix_class ) {
			set_cnst( $field, call_user_func( $prefix_class, $field ) );
		} else {
			set_cnst( $field, zxzu( $field ) );
		}
	} elseif (
		$field
		&& in_array( $field, $field_settings )
	) {
		lct_debug_to_error_log( "ACF field setting for '{$field}' was already set!!!" );
	}
}


/**
 * Update the $field with defaults and overrides
 *
 * @param $field
 * @param $cleanups
 *
 * @return array
 * @since    2017.80
 * @verified 2017.09.15
 */
function lct_acf_update_field_cleanup( $field, $cleanups )
{
	if ( ! empty( $cleanups ) ) {
		foreach ( $cleanups as $cleanup_key => $cleanup_value ) {
			$field[ $cleanup_key ] = $cleanup_value;
		}
	}


	return $field;
}


/**
 * Process $unused_settings
 *
 * @param $field_type
 *
 * @return array
 * @since    2017.80
 * @verified 2017.09.27
 */
function lct_acf_process_unused_settings( $field_type )
{
	if ( ! empty( $field_type->unused_settings ) ) {
		$unused           = [];
		$builtin_settings = lct_acf_builtin_field_settings();


		foreach ( $field_type->unused_settings as $key ) {
			if ( isset( $field_type->defaults[ $key ] ) ) {
				$unused[ $key ] = $field_type->defaults[ $key ];
			} elseif ( isset( $builtin_settings[ $key ] ) ) {
				$unused[ $key ] = $builtin_settings[ $key ];
			}
		}


		$field_type->unused_settings = $unused;
	}


	return $field_type->unused_settings;
}


/**
 * Process $defaults
 *
 * @param $field_type
 *
 * @return array
 * @since    2017.80
 * @verified 2017.09.27
 */
function lct_acf_process_defaults( $field_type )
{
	$builtin_settings = lct_acf_builtin_field_settings( [ 'IGNORE' ] );


	$field_type->defaults = wp_parse_args( $field_type->defaults, $builtin_settings );


	return $field_type->defaults;
}


/**
 * Register an LCT field type
 *
 * @param       $field
 * @param array $lists
 *
 * @since    2017.83
 * @verified 2017.09.28
 */
function lct_acf_register_field_type( $field, $lists = [] )
{
	if ( ! is_set_cnst( 'acf_field_type_' . $field->name ) ) {
		set_cnst( 'acf_field_type_' . $field->name, $field->name );

		set_cnst( zxzu_undo( $field->name ), $field->name );
	}


	lct_acf_process_defaults( $field );
	lct_acf_process_unused_settings( $field );


	$field_types = lct_get_setting( 'field_types', [] );


	$field_types[ $field->name ] = $field;


	lct_update_setting( 'field_types', $field_types );


	if ( ! empty( $lists ) ) {
		foreach ( $lists as $list ) {
			switch ( $list ) {
				case 'exclude_field_type':
					$lct_acf_field_settings = null;

					if ( class_exists( 'lct_acf_field_settings' ) ) {
						$lct_acf_field_settings = new lct_acf_field_settings( lct_load_class_default_args() );
					}


					if ( $lct_acf_field_settings ) {
						$lct_acf_field_settings->exclude_field_type( $field );
					}
				default:
			}


			$setting          = 'field_types_' . $list;
			$field_types_list = lct_get_setting( $setting, [] );


			$field_types_list[ $field->name ] = $field;


			lct_update_setting( $setting, $field_types_list );
		}
	}
}


/**
 * Get an LCT field type list
 *
 * @param null $list
 * @param null $field
 * @param null $array_key
 *
 * @return array
 * @since    2017.83
 * @verified 2018.02.20
 */
function lct_acf_get_field_types( $list = null, $field = null, $array_key = null )
{
	if ( $list ) {
		$r = lct_get_setting( 'field_types_' . $list, [] );
	} else {
		$r = lct_get_setting( 'field_types', [] );
	}


	if (
		! empty( $r )
		&& $field
	) {
		/**
		 * Return early if cache is found
		 */
		$cache_key = lct_cache_key( compact( 'list', 'field', 'array_key' ) );
		if ( lct_isset_cache( $cache_key ) ) {
			return lct_get_cache( $cache_key );
		}


		foreach ( $r as $k => $v ) {
			if ( $array_key ) {
				$r[ $k ] = $v->$field[ $array_key ];
			} else {
				$r[ $k ] = $v->$field;
			}
		}


		/**
		 * Save the value to the cache
		 */
		lct_set_cache( $cache_key, $r );
	}


	return $r;
}


/**
 * Get the ACF label of a selector
 *
 * @param string $selector
 * @param int    $post_id
 *
 * @return string
 * @since        2018.11
 * @verified     2018.10.08
 */
function lct_acf_get_field_label( $selector, $post_id )
{
	$r = '';


	if ( $obj = get_field_object( $selector, $post_id, false, false ) ) {
		$r = acf_get_field_label( $obj );
	}


	return $r;
}


/**
 * Get a label of a selector, don't include any required suffix
 *
 * @param string $selector
 * @param int    $post_id
 *
 * @return string
 * @since    2018.11
 * @verified 2018.02.07
 */
function lct_acf_get_field_label_no_required( $selector, $post_id = false )
{
	$r   = '';
	$obj = get_field_object( $selector, $post_id, false, false );


	if ( $obj ) {
		$obj['required'] = false;


		$r = acf_get_field_label( $obj );
	}


	return $r;
}


/**
 * Get the post_id for a field
 *
 * @param array $field
 * @param null  $post
 * @param bool  $parent
 *
 * @return int|null
 * @since        2017.83
 * @verified     2022.01.06
 */
function lct_get_field_post_id( $field, $post = null, $parent = false )
{
	if ( $post === 'false' ) {
		$post = null;
	}


	if (
		empty( $post )
		&& ! empty( $field['post_id'] )
	) {
		$r = (int) $field['post_id'];


	} elseif (
		! empty( $post )
		&& ! is_numeric( $post )
		&& ! is_object( $post )
		&& ! is_array( $post )
	) {
		$r = $post;


	} else {
		$r = lct_get_acf_post_id( $post, $parent );
	}


	return $r;
}


/**
 * Get an ACF option value, but by-pass the ACF system
 * USE THIS WITH GREAT CARE!
 *
 * @param      $selector
 * @param bool $allow_null
 *
 * @return mixed
 * @since    2018.62
 * @verified 2018.10.08
 */
function lct_acf_get_option_raw( $selector, $allow_null = false )
{
	/**
	 * Return early if cache is found
	 */
	$cache_key = lct_cache_key( compact( 'selector' ) );
	if ( lct_isset_cache( $cache_key ) ) {
		return lct_get_cache( $cache_key );
	}


	$r = get_option( lct_o() . '_' . zxzacf( $selector ), null );


	/**
	 * Get the default if it is not in the DB
	 */
	if (
		$r === null
		&& ! $allow_null
	) {
		$r = lct_acf_get_option( $selector, false );
	}


	/**
	 * Save the value to the cache
	 */
	lct_set_cache( $cache_key, $r );


	return $r;
}


/**
 * Get a value that we access a lot
 * Alias to the better: get_field_option()
 *
 * @param      $selector
 * @param bool $format_value
 *
 * @return mixed
 * @since    7.62
 * @verified 2018.02.20
 */
function lct_acf_get_field_option( $selector, $format_value = true )
{
	return lct_acf_get_option( $selector, $format_value );
}


/**
 * Get an ACF option value
 *
 * @param      $selector
 * @param bool $format_value
 *
 * @return mixed
 * @since    2018.14
 * @verified 2019.02.18
 */
function lct_acf_get_option( $selector, $format_value = true )
{
	/**
	 * Return early if cache is found
	 */
	$cache_key = lct_cache_key( compact( 'selector', 'format_value' ) );
	if ( lct_isset_cache( $cache_key ) ) {
		return lct_get_cache( $cache_key );
	}


	$selector     = zxzacf( $selector );
	$selector_key = 'option_repeater_' . $selector;
	if ( $format_value ) {
		$selector_key .= ':formatted';
	}


	if ( ( $option_repeaters = lct_get_setting( 'option_repeaters' ) ) === null ) {
		$option_repeaters = lct_get_option( 'option_repeaters', [] );


		lct_update_setting( 'option_repeaters', $option_repeaters );
	}


	if (
		in_array( $selector, $option_repeaters )
		&& ( $repeater = lct_get_option( $selector_key ) )
	) {
		/**
		 * Save the value to the cache
		 */
		lct_set_cache( $cache_key, $repeater );


		return $repeater;
	}


	$r = get_field( $selector, lct_o(), $format_value );


	/**
	 * Save the value to the cache
	 */
	lct_set_cache( $cache_key, $r );


	if ( in_array( $selector, $option_repeaters ) ) {
		lct_update_option( $selector_key, $r );
	}


	return $r;
}


/**
 * Update an ACF option value
 *
 * @param string $selector
 * @param mixed  $value
 *
 * @return bool
 * @since        2018.26
 * @verified     2018.03.05
 */
function lct_acf_update_option( $selector, $value = null )
{
	$r = update_field( zxzacf( $selector ), $value, lct_o() );


	/**
	 * Clear the value of the cache
	 */
	$format_value = true;
	$cache_key    = lct_cache_key( compact( 'selector', 'format_value' ) );
	if ( lct_isset_cache( $cache_key ) ) {
		lct_delete_cache( $cache_key );
	}
	$format_value = false;
	$cache_key    = lct_cache_key( compact( 'selector', 'format_value' ) );
	if ( lct_isset_cache( $cache_key ) ) {
		lct_delete_cache( $cache_key );
	}


	return $r;
}


/**
 * Checks if a field exists in the DB
 *
 * @param $field_name
 * @param $sub_field
 *
 * @return bool
 * @since    2018.59
 * @verified 2018.08.28
 */
function lct_acf_field_exists( $field_name, $sub_field = null )
{
	global $wpdb;

	$r      = false;
	$exists = false;


	/**
	 * Check if it exists
	 */
	if ( $sub_field ) {
		$parent_id = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT `ID`
				FROM `{$wpdb->posts}`
				WHERE `post_type` = 'acf-field' AND
				`post_excerpt` = %s",
				$sub_field
			)
		);


		if ( $parent_id ) {
			$exists = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT `post_name`
				FROM `{$wpdb->posts}`
				WHERE `post_type` = 'acf-field' AND
				`post_parent` = %s AND
				`post_excerpt` = %s",
					$parent_id,
					$field_name
				)
			);
		}
	} else {
		$exists = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT `post_name`
				FROM `{$wpdb->posts}`
				WHERE `post_type` = 'acf-field' AND
				`post_excerpt` = %s",
				$field_name
			)
		);
	}


	if ( $exists ) {
		$r = $exists;
	}


	return $r;
}


/**
 * Check and see if there are any rows in an option repeater
 *
 * @param string $selector
 *
 * @return bool
 * @since    2018.61
 * @verified 2018.08.24
 */
function lct_acf_option_repeater_empty( $selector )
{
	$r = true;


	if (
		acf_get_setting( 'autoload' )
		&& ( $all_options = wp_load_alloptions() )
		&& ( $tmp = lct_o() . '_' . $selector )
		&& isset( $all_options[ $tmp ] )
	) {
		$r = false;
	}


	return $r;
}


/**
 * Get the status WP_Term of a post
 *
 * @param int|bool $post_id
 * @param bool     $format_value
 * @param bool     $load_value
 *
 * @return array
 * @since    2019.7
 * @verified 2019.04.03
 */
function lct_acf_get_status_field_object( $post_id = false, $format_value = true, $load_value = true )
{
	return get_field_object( lct_status(), $post_id, $format_value, $load_value );
}


/**
 * Get the status WP_Term of a post
 *
 * @param int $post_id
 *
 * @return WP_Term|null
 * @since        2019.7
 * @verified     2019.04.03
 */
function lct_acf_get_status( $post_id )
{
	$r = null;


	if ( $answer = get_field( lct_status(), $post_id ) ) {
		if ( lct_is_a( $answer, 'WP_Term' ) ) {
			$r = $answer;
		} else {
			$field_obj = get_field_object( lct_status(), $post_id, false, false );


			if (
				! empty( $field_obj['taxonomy'] )
				&& ( $answer = get_term( $answer, $field_obj['taxonomy'] ) )
				&& ! lct_is_wp_error( $answer )
			) {
				$r = $answer;
			}
		}
	}


	return $r;
}


/**
 * Get the status term_id of a post
 *
 * @param int $post_id
 *
 * @return int|null
 * @since        2019.7
 * @verified     2019.04.03
 */
function lct_acf_get_status_id( $post_id )
{
	$r = null;


	if ( $answer = get_field( lct_status(), $post_id, false ) ) {
		$r = $answer;
	}


	return $r;
}


/**
 * Update the status of a post
 *
 * @param int|WP_Term $value
 * @param int|bool    $post_id
 *
 * @return bool
 * @since    2019.7
 * @verified 2019.04.08
 */
function lct_acf_update_status( $value, $post_id = false )
{
	$r = false;


	update_field( lct_status(), $value, $post_id );


	if (
		( $field = acf_maybe_get_field( lct_status(), $post_id ) )
		&& ( $updates = lct_get_later( 'update_post_status', $post_id ) )
		&& array_search( $field['key'], $updates ) !== false
	) {
		$r = true;
	}


	return $r;
}


/**
 * Display the value of an ACF field
 *
 * @param string   $selector
 * @param int|bool $post_id
 * @param bool     $format_value
 *
 * @return mixed
 * @since        2019.27
 * @verified     2020.11.23
 */
function lct_acf_display_value( $selector, $post_id = false, $format_value = true )
{
	if ( ! ( $acf_display_form_active = lct_get_setting( 'acf_display_form_active' ) ) ) {
		lct_update_setting( 'acf_display_form_active', true );
	}


	$value = '';


	if ( $field = get_field_object( $selector, $post_id, $format_value ) ) {
		$value = lct_acf_format_value( $field['value'], $post_id, $field );
	}


	if ( ! $acf_display_form_active ) {
		lct_update_setting( 'acf_display_form_active', null );
	}


	return $value;
}


/**
 * Clean up the ACF repeater
 *
 * @param array $array The input array.
 *
 * @return array
 * @since    2020.5
 * @verified 2022.10.24
 */
function lct_clean_acf_repeater( $array )
{
	if ( ! is_array( $array ) ) {
		return $array;
	}


	foreach ( $array as $k => $v ) {
		if ( $k === 'acfcloneindex' ) {
			unset( $array[ $k ] );
		} elseif ( is_array( $v ) ) {
			$array[ $k ] = lct_clean_acf_repeater( $v );
		}
	}


	return $array;
}


/**
 * Get the parent repeater
 *
 * @param array  $array
 * @param string $updated_key
 * @param int    $depth
 *
 * @return bool|int|string|null
 * @since        2020.5
 * @verified     2020.02.07
 */
function lct_find_repeater_field( array $array, $updated_key, $depth = 0 )
{
	$parent_key = null;


	foreach ( $array as $k => $v ) {
		if ( ! $depth ) {
			$parent_key = $k;
		}


		if ( isset( $v[ $updated_key ] ) ) {
			return true;
		} elseif ( is_array( $v ) ) {
			$rec_depth = $depth;
			$rec_depth ++;


			if ( lct_find_repeater_field( $v, $updated_key, $rec_depth ) === true ) {
				if ( $depth ) {
					return true;
				} else {
					break;
				}
			}
		}
	}


	return $parent_key;
}


/**
 * Check caps of an ACF field for a user
 *
 * @param array $field
 *
 * @return bool
 * @since    2020.11
 * @verified 2020.09.04
 */
function lct_acf_current_user_can_edit_field( array $field )
{
	$r = true;


	if (
		! empty( $field[ get_cnst( 'roles_n_caps' ) ] )
		&& ! lct_current_user_can_caps( $field[ get_cnst( 'roles_n_caps' ) ] )
	) {
		$r = false;
	}


	return $r;
}


/**
 * lct_acf_validate_subfield_parent
 * Make sure this field is a subfield of an allowed parent repeater
 *
 * @param array        $field
 * @param array|string $allowed_parents
 * @param mixed        $post_id
 *
 * @return    bool
 * @date         2020.10.05
 * @since        2020.13
 * @verified     2020.10.05
 */
function lct_acf_validate_subfield_parent( $field, $allowed_parents, $post_id = false )
{
	if ( ! lct_acf_is_repeater_subfield( $field ) ) {
		return false;
	}


	$all_allowed_parents = [];


	foreach ( (array) $allowed_parents as $allowed_parent ) {
		if ( $field_obj = get_field_object( $allowed_parent, $post_id, false, false ) ) {
			$all_allowed_parents[] = $field_obj['_name'];
			$all_allowed_parents[] = $field_obj['key'];
		}
	}


	if ( in_array( $field['parent'], $all_allowed_parents ) ) {
		return true;
	}


	return false;
}
