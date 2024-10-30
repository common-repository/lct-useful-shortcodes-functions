<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Create the full_field_name so that we can reference it in the DB.
 * //TODO: cs - Try and get rid of this or improve it - 10/25/2016 02:57 PM
 *
 * @param null   $prefix
 * @param null   $field_name
 * @param string $delimiter
 *
 * @return bool|string
 */
function lct_acf_get_full_field_name( $prefix = null, $field_name = null, $delimiter = null )
{
	if ( empty( $prefix ) ) {
		return false;
	}

	if ( is_null( $delimiter ) ) {
		$delimiter = zxzd();
	}


	return $prefix . $delimiter . $field_name;
}


/**
 * Unsave the values in the DB, so the fields are empty again
 * //TODO: cs - Try and get rid of this or improve it - 10/25/2016 02:57 PM
 *
 * @param       $fields
 * @param       $prefix
 * @param array $custom_exclude
 * @param array $custom_include
 *
 * @return bool
 * @since    0.0
 * @verified 2016.12.10
 */
function lct_acf_unsave_db_values( $fields, $prefix, $custom_exclude = [], $custom_include = [] )
{
	$r = false;


	if ( ! $fields['show_params::save_field_values'] ) {
		foreach ( $fields as $field_name => $field_value ) {
			$exclude_from_clear = lct_acf_exclude_from_clear( $custom_exclude, $custom_include );


			if ( in_array( $field_name, $exclude_from_clear ) ) {
				continue;
			}


			$full_field_name = lct_acf_get_full_field_name( $prefix, $field_name );

			update_field( $full_field_name, '', lct_o() );
		}


		$r = true;
	}


	return $r;
}


/**
 * Set the fields that you don't want to have cleared out of the DB when Save Field Values is not set.
 * //TODO: cs - Try and get rid of this or improve it - 10/25/2016 02:57 PM
 *
 * @param array $custom_exclude
 * @param array $custom_include
 *
 * @return array
 */
function lct_acf_exclude_from_clear( $custom_exclude = [], $custom_include = [] )
{
	$exclude = [];


	if ( ! empty( $custom_exclude ) ) {
		foreach ( $custom_exclude as $excluded_field ) {
			$exclude[ $excluded_field ] = 1;
		}
	}

	if ( ! empty( $custom_include ) ) {
		foreach ( $custom_include as $included_field ) {
			$exclude[ $included_field ] = 0;
		}
	}

	if ( ! isset( $exclude['show_params'] ) ) {
		$exclude['show_params'] = 1;
	}

	$excluded_fields = [];

	foreach ( $exclude as $excluded_field => $status ) {
		if ( $status == 1 || ( $exclude['show_params'] == 1 && strpos( $excluded_field, 'show_params' ) !== false ) ) {
			$excluded_fields[] = $excluded_field;
		}
	}

	return $excluded_fields;
}


/**
 * Get fields and create our $fields array
 * //TODO: cs - Try and get rid of this or improve it - 10/25/2016 02:57 PM
 *
 * @param        $parent
 * @param        $prefix
 * @param null   $excluded_fields
 * @param bool   $just_field_name
 * @param null   $prefix_2
 * @param string $delimiter
 *
 * @return mixed
 */
function lct_acf_get_mapped_fields( $parent, $prefix = null, $excluded_fields = null, $just_field_name = false, $prefix_2 = null, $delimiter = null )
{
	$fields = [];

	if ( is_null( $delimiter ) ) {
		$delimiter = zxzd();
	}


	if ( strpos( $parent, 'group_' ) === 0 || is_null( $parent ) ) {
		$field_names = lct_acf_get_field_names_by_object( $prefix, $excluded_fields, $just_field_name, $prefix_2, $delimiter );
	} else {
		$field_names = lct_acf_get_field_names_by_parent( $parent, $prefix, $excluded_fields, $just_field_name, $prefix_2, $delimiter );
	}

	if ( empty( $field_names ) ) {
		return false;
	}

	foreach ( $field_names as $field_name ) {
		if ( ! is_null( $prefix_2 ) ) {
			$full_field_name = lct_acf_get_full_field_name( $prefix . $delimiter . $prefix_2, $field_name, '' );
		} else {
			$full_field_name = lct_acf_get_full_field_name( $prefix, $field_name );
		}

		$field_value = get_field( $full_field_name, lct_o() );

		$fields[ $field_name ] = $field_value;
	}

	//Unsave the values in the DB, so the fields are empty again
	lct_acf_unsave_db_values( $fields, $prefix );

	return $fields;
}


/**
 * Name says it all
 * //TODO: cs - Try and get rid of this or improve it - 10/25/2016 02:57 PM
 *
 * @param            $parent
 * @param null       $prefix
 * @param null       $excluded_fields
 * @param bool|false $just_field_name
 * @param null       $prefix_2
 * @param string     $delimiter
 *
 * @return array
 */
function lct_acf_get_field_names_by_parent( $parent, $prefix = null, $excluded_fields = null, $just_field_name = false, $prefix_2 = null, $delimiter = null )
{
	$fields = [];

	if ( is_null( $delimiter ) ) {
		$delimiter = zxzd();
	}


	$args          = [
		'posts_per_page'         => - 1,
		'post_type'              => 'acf-field',
		'post_status'            => 'any',
		'post_parent'            => $parent,
		'cache_results'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	];
	$field_objects = get_posts( $args );

	if ( ! lct_is_wp_error( $field_objects ) ) {
		foreach ( $field_objects as $field_object ) {
			$post_excerpt = str_replace( $prefix . $delimiter, '', $field_object->post_excerpt );

			if ( ! is_null( $prefix_2 ) ) {
				$post_excerpt = str_replace( $prefix_2, '', $post_excerpt );
			}

			if ( is_array( $excluded_fields ) && in_array( $post_excerpt, $excluded_fields ) ) {
				continue;
			}

			if ( $just_field_name ) {
				$fields[ $field_object->menu_order ] = $post_excerpt;
			} else {
				$fields[ $field_object->post_name ] = $post_excerpt;
			}
		}
	}

	sort( $fields );

	$fields = array_filter( $fields );

	return $fields;
}


/**
 * Name says it all
 * //TODO: cs - Try and get rid of this or improve it - 10/25/2016 02:57 PM
 *
 * @param null       $prefix
 * @param null       $excluded_fields
 * @param bool|false $just_field_name
 * @param null       $prefix_2
 * @param string     $delimiter
 *
 * @return array
 */
function lct_acf_get_field_names_by_object( $prefix = null, $excluded_fields = null, $just_field_name = false, $prefix_2 = null, $delimiter = null )
{
	$fields = [];

	if ( is_null( $delimiter ) ) {
		$delimiter = zxzd();
	}


	$field_objects = get_field_objects( lct_o() );

	foreach ( $field_objects as $key => $field_object ) {
		if ( ! is_null( $prefix ) && strpos( $key, $prefix . $delimiter ) === false ) {
			continue;
		}

		if ( ! is_null( $prefix_2 ) && strpos( $key, $prefix . $delimiter . $prefix_2 ) === false ) {
			continue;
		}

		$post_excerpt = str_replace( $prefix . $delimiter, '', $key );

		if ( ! is_null( $prefix_2 ) ) {
			$post_excerpt = str_replace( $prefix_2, '', $post_excerpt );
		}

		if ( is_array( $excluded_fields ) && in_array( $post_excerpt, $excluded_fields ) ) {
			continue;
		}

		if ( $just_field_name == true ) {
			$fields[ $field_object['menu_order'] ] = $post_excerpt;
		} else {
			$fields[ $field_object['name'] ] = $post_excerpt;
		}
	}

	sort( $fields );

	$fields = array_filter( $fields );

	return $fields;
}


/**
 * Print the settings for the function
 * //TODO: cs - Try and get rid of this or improve it - 10/25/2016 02:57 PM
 *
 * @param $fields
 * @param $prefix
 *
 * @return string
 */
function lct_acf_recap_field_settings( $fields, $prefix )
{
	$recap = "<h2 style='color: green;font-weight: bold; margin-bottom: 0;'>Settings Recap:</h2>";


	$recap .= '<ul style="margin-top: 0;">';

	foreach ( $fields as $field_name => $field_value ) {
		$excluded_fields = [
			'show_params::save_field_values',
			'run_this'
		];

		if (
			empty( $field_value )
			|| in_array( $field_name, $excluded_fields )
		) {
			if ( $field_name == 'run_this' ) {
				$recap .= "<li><span style='float: left;width: 115px;font-weight: bold;'>" . $field_name . ":</span><span style='color: green;font-weight: bold'>Just Ran {$prefix}</span></li>";
			}

			continue;
		}

		if ( is_array( $field_value ) ) {
			$field_value = $field_value[0];

			//TODO: cs - We probably need a more dynamic check here - 7/24/2015 12:24 PM
			if ( $field_value == 1 ) {
				$field_value = 'Yes';
			}
		}

		$recap .= "<li><span style='float: left;width: 115px;font-weight: bold;'>" . $field_name . ":</span> " . $field_value . "</li>";

	}

	$recap .= '</ul>';


	return $recap;
}


/**
 * Create a clean table out of the data
 * //TODO: cs - Try and get rid of this or improve it - 10/25/2016 02:57 PM
 *
 * @param $rows
 *
 * @return string
 */
function lct_acf_create_table( $rows )
{
	$table = '';

	if ( ! empty( $rows ) ) {
		$table .= '<table class="wp-list-table widefat fixed striped">';

		foreach ( $rows as $row_number => $row ) {
			if ( $row_number === 0 ) {
				$table .= '<thead>';
			}

			if ( $row_number === 1 ) {
				$table .= '</thead>';
				$table .= '<tbody id="the-list">';
			}

			$table .= '<tr>';

			foreach ( $row as $k => $column ) {
				if ( $row_number === 0 ) {
					$table .= '<th scope="col" id="' . $k . '" class="manage-column column-customer_name" style="">' . $column . '</th>';

					continue;
				}

				$table .= '<td class="' . $k . ' column-' . $k . '">' . $column . '</td>';
			}

			$table .= '</tr>';
		}

		$table .= '</tbody>';

		$table .= '</table>';
	}

	return $table;
}


/**
 * Check the conditional_logic to see if we should hide the field
 *
 * @param $field
 * @param $post_id
 *
 * @return string
 * @since    7.31
 * @verified 2021.02.28
 */
function lct_acf_hide_this( $field, $post_id )
{
	$show_this = false;
	$hide_this = '';

	if ( ! empty( $field['conditional_logic'] ) ) {
		foreach ( $field['conditional_logic'] as $group ) {
			$match_group = true;


			if ( ! empty( $group ) ) {
				foreach ( $group as $rule ) {
					$match         = false;
					$current_value = get_field( $rule['field'], $post_id, false );


					if ( $rule['operator'] == '==' ) {
						if ( is_array( $current_value ) ) {
							if ( ! in_array( $rule['value'], $current_value ) ) {
								$match = true;
							}
						} else {
							if ( $current_value != $rule['value'] ) {
								$match = true;
							}
						}
					} elseif ( $rule['operator'] == '!=' ) {
						if ( is_array( $current_value ) ) {
							if ( in_array( $rule['value'], $current_value ) ) {
								$match = true;
							}
						} else {
							if ( $current_value == $rule['value'] ) {
								$match = true;
							}
						}
					}

					if ( $match ) {
						$match_group = false;
						break;
					}
				}
			}


			if ( $match_group ) {
				$show_this = true;
			}
		}


		if ( ! ( $show_this = apply_filters( 'lct/acf_hide_this/show_this', $show_this, $field, $post_id ) ) ) {
			$selectors = [
				'[data-key="' . $field['key'] . '"]',
				'.' . zxzu( 'acf_display_form' ) . ' [data-key="' . $field['key'] . '"]',
				'.' . zxzu( 'acf_display_form' ) . ' .acf-field-date-picker[data-key="' . $field['key'] . '"]',
			];
			$selectors = lct_return( $selectors, ',' );


			$hide_this = sprintf( '<%1$s>%2$s{display: none !important;}</%1$s>', 'style', $selectors );
		}
	}


	return $hide_this;
}


/**
 * Special AFWP functions
 */
if ( ! function_exists( 'afwp' ) ) {
	/**
	 * Returns a single $_REQUEST arg with fallback.
	 *
	 * @param string $key     The property name.
	 * @param mixed  $default The default value to fall back to.
	 *
	 * @return  mixed
	 * @date     2024.05.03
	 * @since    2024.04
	 * @verified 2024.05.03
	 */
	function afwp_REQUEST_arg( $key = '', $default = null )
	{
		return $_REQUEST[ $key ] ?? $default;
	}


	/**
	 * This function will return true if the tool is active
	 *
	 * @param string $function
	 *
	 * @return bool
	 * @date     2021.07.01
	 * @since    2021.2
	 * @verified 2022.02.24
	 */

	function afwp_tool_is_active( $function = null )
	{
		if ( $function === null ) {
			$function = afwp_previous_function_auto();
		}


		return (
			(
				afwp_REQUEST_arg( 'tool' ) === $function
				|| afwp_REQUEST_arg( 'tool' ) === str_replace( '\\', '\\\\', $function )
			)
			&& current_user_can( 'administrator' )
		);
	}


	/**
	 * Returns the details of the previous function
	 *
	 * @param string $sep The separator between the class and the function.
	 * @param int    $level
	 *
	 * @return    string
	 * @date     2020.09.14
	 * @since    2020.0
	 * @verified 2022.01.07
	 */
	function afwp_previous_function( $sep = '::', $level = 2 )
	{
		$bt = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, $level + 1 );
		$r  = $bt[ $level ]['function'];


		if (
			isset( $bt[ $level ]['class'] )
			&& ( $bt[ $level ]['class'] || is_numeric( $bt[ $level ]['class'] ) )
		) {
			$r = $bt[ $level ]['class'] . $sep . $r;
		}


		return $r;
	}


	/**
	 * Returns the details of the previous function automatically
	 *
	 * @param string $sep The separator between the class and the function.
	 *
	 * @return    string
	 * @date     2020.09.15
	 * @since    2020.0
	 * @verified 2020.09.15
	 */
	function afwp_previous_function_deep( $sep = '::' )
	{
		return afwp_previous_function( $sep, 4 );
	}


	/**
	 * Returns the details of the previous function automatically
	 *
	 * @param string $function
	 * @param string $class
	 *
	 * @return    string
	 * @date     2020.09.15
	 * @since    2020.0
	 * @verified 2021.06.03
	 */
	function afwp_previous_function_auto( $function = '', $class = '' )
	{
		$r = null;


		/**
		 * Grab the previous function or method.
		 */
		if (
			! $function
			&& ! $class
		) {
			$r = afwp_previous_function_deep();


			/**
			 * If class is set, prepend it to the method.
			 */
		} elseif ( $class ) {
			$r = $class . '::' . $function;
		} elseif ( $function ) {
			$r = $function;
		}


		if ( ! $r ) {
			error_log( __FUNCTION__ . '() returned null' );
		}


		return $r;
	}


	/**
	 * base64 decode a value
	 *
	 * @param string $value
	 * @param bool   $encode_specialchars //Only use this in extreme situations
	 *
	 * @return string
	 * @date     2021.10.19
	 * @since    2021.3
	 * @verified 2022.04.12
	 */
	function afwp_acf_base64_decode( $value, $encode_specialchars = false )
	{
		$prefix = 'base64::';


		/**
		 * Base decode the value if needed
		 */
		if (
			is_string( $value )
			&& //Has to be a sting
			strpos( $value, $prefix ) === 0 //Needs to already be encoded
		) {
			$value = base64_decode( str_replace( $prefix, '', $value ) );


			if ( $encode_specialchars ) {
				$value = _wp_specialchars( $value );
			}
		}


		return $value;
	}


	/**
	 * base64 encode a value
	 *
	 * @param string $value
	 *
	 * @return string
	 * @date     2021.10.19
	 * @since    2021.3
	 * @verified 2022.07.27
	 */
	function afwp_acf_base64_encode( $value )
	{
		$prefix = 'base64::';


		/**
		 * Base encode the value if needed
		 */
		if (
			is_string( $value )
			&& //Has to be a sting
			strpos( $value, $prefix ) !== 0 //Can't already be encoded
		) {
			$value = $prefix . base64_encode( $value );
		}


		return $value;
	}


	/**
	 * JSON decode a value
	 *
	 * @param string $value
	 *
	 * @return array|string|mixed
	 * @date     2021.10.19
	 * @since    2021.3
	 * @verified 2022.07.30
	 */
	function afwp_acf_json_decode( $value )
	{
		/**
		 * Do a quick decode to get the last error
		 */
		$decoded = afwp_acf_base64_decode( $value );


		/**
		 * Not a JSON string, force an error
		 */
		if ( ! is_string( $decoded ) ) {
			json_decode( INF );


			return null;
		}

		/**
		 * Do a quick decode to get the last error
		 */
		$decoded = json_decode( $decoded, true, 512, JSON_BIGINT_AS_STRING );


		/**
		 * Decode chars
		 */
		$chars   = [
			'double_quote' => true,
		];
		$decoded = afwp_decode_chars( $decoded, $chars );


		/**
		 * JSON decode the value if needed
		 */
		if ( json_last_error() === JSON_ERROR_NONE ) {
			return $decoded;
		}


		/**
		 * Return null
		 */
		return null;
	}


	/**
	 * MAYBE JSON decode a value
	 *
	 * @param string|array $value
	 *
	 * @return array|string|mixed
	 * @date       2022.01.18
	 * @since      2022.1
	 * @verified   2022.01.20
	 */
	function afwp_acf_maybe_json_decode( $value )
	{
		/**
		 * Do a quick decode to get the last error
		 */
		$decoded = afwp_acf_json_decode( $value );


		/**
		 * JSON decode the value if needed
		 */
		if ( json_last_error() === JSON_ERROR_NONE ) {
			return $decoded;
		}


		/**
		 * Just send back the original value
		 */
		return $value;
	}


	/**
	 * JSON encode a value
	 *
	 * @param array $value
	 *
	 * @return string|false
	 * @date     2021.10.19
	 * @since    2021.3
	 * @verified 2022.07.27
	 */
	function afwp_acf_json_encode( $value )
	{
		/**
		 * Preserve chars
		 */
		$chars = [
			'backslash'    => true,
			'line_break'   => true,
			'double_quote' => true,
		];
		$value = afwp_escape_chars( $value, $chars );


		/**
		 * Do a quick encode to get the last error
		 */
		$encoded = wp_json_encode( $value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS | JSON_NUMERIC_CHECK );


		/**
		 * JSON encode the value if needed
		 */
		if ( json_last_error() === JSON_ERROR_NONE ) {
			return $encoded;
		}


		/**
		 * Return false
		 */
		return false;
	}


	/**
	 * Escape characters in a recursive string
	 *
	 * @param mixed $value
	 * @param array $chars backslash | line_break | double_quote
	 *
	 * @return mixed
	 * @date     2022.07.27
	 * @since    2022.6
	 * @verified 2022.07.27
	 */
	function afwp_escape_chars( $value, $chars = [] )
	{
		/**
		 * Vars
		 */
		$default = false;
		if ( empty( $chars ) ) {
			$default = true;
		}

		$defaults = [
			'backslash'    => $default,
			'line_break'   => $default,
			'double_quote' => $default,
		];
		$chars    = wp_parse_args( $chars, $defaults );


		/**
		 * Array
		 */
		if ( is_array( $value ) ) {
			$output = [];


			foreach ( $value as $id => $el ) {
				if ( is_array( $el ) || is_object( $el ) ) {
					$output[ $id ] = afwp_escape_chars( $el, $chars );
				} elseif ( is_string( $el ) ) {
					$output[ $id ] = _afwp_escape_chars( $el, $chars );
				} else {
					$output[ $id ] = $el;
				}
			}


			/**
			 * Object
			 */
		} elseif ( is_object( $value ) ) {
			$output = new stdClass;


			foreach ( $value as $id => $el ) {
				if ( is_array( $el ) || is_object( $el ) ) {
					$output->$id = afwp_escape_chars( $el, $chars );
				} elseif ( is_string( $el ) ) {
					$output->$id = _afwp_escape_chars( $el, $chars );
				} else {
					$output->$id = $el;
				}
			}


			/**
			 * String
			 */
		} elseif ( is_string( $value ) ) {
			return _afwp_escape_chars( $value, $chars );


			/**
			 * Anything Else
			 */
		} else {
			return $value;
		}


		return $output;
	}


	/**
	 * Escape characters in a recursive string
	 *
	 * @param string $value
	 * @param array  $chars
	 *
	 * @return string
	 * @date     2022.07.27
	 * @since    2022.6
	 * @verified 2022.07.27
	 */
	function _afwp_escape_chars( $value, $chars )
	{
		if ( ! empty( $chars ) ) {
			foreach ( $chars as $char => $run_char ) {
				if ( ! $run_char ) {
					continue;
				}


				switch ( $char ) {
					case 'backslash':
						$value = _afwp_escape_backslash( $value );
						break;


					case 'line_break':
						$value = _afwp_escape_line_break( $value );
						break;


					case 'double_quote':
						$value = _afwp_escape_double_quote( $value );
						break;


					default;
				}
			}
		}


		return $value;
	}


	/**
	 * Preserve the backslash
	 *
	 * @param mixed $value
	 *
	 * @return mixed
	 * @date     2022.07.27
	 * @since    2022.6
	 * @verified 2022.07.27
	 */
	function afwp_escape_backslash( $value )
	{
		return afwp_escape_chars( $value, [ 'backslash' => true ] );
	}


	/**
	 * Preserve the backslash
	 *
	 * @param string $value
	 *
	 * @return string
	 * @date     2022.07.27
	 * @since    2022.6
	 * @verified 2022.07.27
	 */
	function _afwp_escape_backslash( $value )
	{
		return str_replace( [ "\\\\", "\\" ], "\\\\", $value );
	}


	/**
	 * Preserve the line breaks
	 *
	 * @param mixed $value
	 *
	 * @return mixed
	 * @date     2022.07.27
	 * @since    2022.6
	 * @verified 2022.07.27
	 */
	function afwp_escape_line_break( $value )
	{
		return afwp_escape_chars( $value, [ 'line_break' => true ] );
	}


	/**
	 * Preserve the line breaks
	 *
	 * @param string $value
	 *
	 * @return string
	 * @date     2022.07.27
	 * @since    2022.6
	 * @verified 2022.07.27
	 */
	function _afwp_escape_line_break( $value )
	{
		$value = afwp_nl2br( $value, "\n" );


		return str_replace( [ "\\\\n", "\\n", "\n" ], "\\n", $value );
	}


	/**
	 * Preserve the double quotes
	 *
	 * @param mixed $value
	 *
	 * @return mixed
	 * @date     2022.07.27
	 * @since    2022.6
	 * @verified 2022.07.27
	 */
	function afwp_escape_double_quote( $value )
	{
		return afwp_escape_chars( $value, [ 'double_quote' => true ] );
	}


	/**
	 * Preserve the double quotes
	 *
	 * @param string $value
	 *
	 * @return string
	 * @date     2022.07.27
	 * @since    2022.6
	 * @verified 2022.07.27
	 */
	function _afwp_escape_double_quote( $value )
	{
		return str_replace( '"', '&quot;', $value );
	}


	/**
	 * Decode characters in a recursive string
	 *
	 * @param mixed $value
	 * @param array $chars double_quote
	 *
	 * @return mixed
	 * @date     2022.07.27
	 * @since    2022.6
	 * @verified 2022.07.30
	 */
	function afwp_decode_chars( $value, $chars = [] )
	{
		/**
		 * Vars
		 */
		$default = false;
		if ( empty( $chars ) ) {
			$default = true;
		}

		$defaults = [
			'backslash'    => $default,
			'double_quote' => $default,
		];
		$chars    = wp_parse_args( $chars, $defaults );


		/**
		 * Array
		 */
		if ( is_array( $value ) ) {
			$output = [];


			foreach ( $value as $id => $el ) {
				if ( is_array( $el ) || is_object( $el ) ) {
					$output[ $id ] = afwp_decode_chars( $el, $chars );
				} elseif ( is_string( $el ) ) {
					$output[ $id ] = _afwp_decode_chars( $el, $chars );
				} else {
					$output[ $id ] = $el;
				}
			}


			/**
			 * Object
			 */
		} elseif ( is_object( $value ) ) {
			$output = new stdClass;


			foreach ( $value as $id => $el ) {
				if ( is_array( $el ) || is_object( $el ) ) {
					$output->$id = afwp_decode_chars( $el, $chars );
				} elseif ( is_string( $el ) ) {
					$output->$id = _afwp_decode_chars( $el, $chars );
				} else {
					$output->$id = $el;
				}
			}


			/**
			 * String
			 */
		} elseif ( is_string( $value ) ) {
			return _afwp_decode_chars( $value, $chars );


			/**
			 * Anything Else
			 */
		} else {
			return $value;
		}


		return $output;
	}


	/**
	 * Decode characters in a recursive string
	 *
	 * @param string $value
	 * @param array  $chars
	 *
	 * @return string
	 * @date     2022.07.27
	 * @since    2022.6
	 * @verified 2022.07.30
	 */
	function _afwp_decode_chars( $value, $chars )
	{
		if ( ! empty( $chars ) ) {
			foreach ( $chars as $char => $run_char ) {
				if ( ! $run_char ) {
					continue;
				}


				switch ( $char ) {
					case 'backslash':
						$value = _afwp_decode_backslash( $value );
						break;


					case 'double_quote':
						$value = _afwp_decode_double_quote( $value );
						break;


					default;
				}
			}
		}


		return $value;
	}


	/**
	 * Decode the double quotes
	 *
	 * @param mixed $value
	 *
	 * @return mixed
	 * @date     2022.07.30
	 * @since    2022.6
	 * @verified 2022.07.30
	 */
	function afwp_decode_backslash( $value )
	{
		return afwp_decode_chars( $value, [ 'backslash' => true ] );
	}


	/**
	 * Decode the double quotes
	 *
	 * @param string $value
	 *
	 * @return string
	 * @date     2022.07.30
	 * @since    2022.6
	 * @verified 2022.07.30
	 */
	function _afwp_decode_backslash( $value )
	{
		return str_replace( '\\\\', '\\', $value );
	}


	/**
	 * Decode the double quotes
	 *
	 * @param mixed $value
	 *
	 * @return mixed
	 * @date     2022.07.27
	 * @since    2022.6
	 * @verified 2022.07.27
	 */
	function afwp_decode_double_quote( $value )
	{
		return afwp_decode_chars( $value, [ 'double_quote' => true ] );
	}


	/**
	 * Decode the double quotes
	 *
	 * @param string $value
	 *
	 * @return string
	 * @date     2022.07.27
	 * @since    2022.6
	 * @verified 2022.07.27
	 */
	function _afwp_decode_double_quote( $value )
	{
		return str_replace( '&quot;', '"', $value );
	}


	/**
	 * Better nl2br() Recursive
	 *
	 * @param string|array $str
	 * @param string       $br
	 *
	 * @return string|array
	 * @date     2022.07.27
	 * @since    2022.6
	 * @verified 2022.07.27
	 */
	function afwp_nl2br_recursive( $str, $br = '<br />' )
	{
		/**
		 * Array
		 */
		if ( is_array( $str ) ) {
			$output = [];


			foreach ( $str as $id => $el ) {
				if ( is_array( $el ) || is_object( $el ) ) {
					$output[ $id ] = afwp_nl2br_recursive( $el, $br );
				} elseif ( is_string( $el ) ) {
					$output[ $id ] = afwp_nl2br( $el );
				} else {
					$output[ $id ] = $el;
				}
			}


			/**
			 * Object
			 */
		} elseif ( is_object( $str ) ) {
			$output = new stdClass;


			foreach ( $str as $id => $el ) {
				if ( is_array( $el ) || is_object( $el ) ) {
					$output->$id = afwp_nl2br_recursive( $el, $br );
				} elseif ( is_string( $el ) ) {
					$output->$id = afwp_nl2br( $el );
				} else {
					$output->$id = $el;
				}
			}


			/**
			 * String
			 */
		} elseif ( is_string( $str ) ) {
			return afwp_nl2br( $str );


			/**
			 * Anything Else
			 */
		} else {
			return $str;
		}


		return $output;
	}


	/**
	 * Better nl2br()
	 *
	 * @param string $str
	 * @param string $br
	 *
	 * @return string
	 * @date     2022.07.27
	 * @since    2022.6
	 * @verified 2022.07.27
	 */
	function afwp_nl2br( $str, $br = '<br />' )
	{
		if ( is_string( $str ) ) {
			return str_replace( [ "\r\n", "\r", "\n" ], $br, $str );
		}


		return $str;
	}
}
