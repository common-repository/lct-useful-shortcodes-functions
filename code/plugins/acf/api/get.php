<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Choices for class_selector
 * //TODO: cs - document classes - 11/10/2016 12:48 PM
 * //TODO: cs - Add support to limit list by type - 11/11/2016 05:44 PM
 *
 * @return array
 * @since    7.29
 * @verified 2022.11.14
 */
function lct_acf_get_pretty_class_selector()
{
	$choices = [];


	//LCT Classes:
	$choices[ zxzu( 'select_display_choice_label' ) ] = 'Display the choice label instead of the value';                                   //Not actually needed as a class
	$choices['hide_if_true']                          = 'Hide if true';                                                                    //Not actually needed as a class
	$choices['hide_if_false']                         = 'Hide if false';                                                                   //Not actually needed as a class
	$choices['hide_if_yes']                           = 'Hide if yes';                                                                     //Not actually needed as a class
	$choices['hide_if_no']                            = 'Hide if no';                                                                      //Not actually needed as a class
	$choices['hide_if_empty']                         = 'Hide if value is NOT set';                                                        //Not actually needed as a class
	$choices[ zxzu( 'instant_save_delay_2_sec' ) ]    = 'Add a 2 second delay to LCT instant save trigger';                                //Not actually needed as a class

	$choices['hidden']                        = 'Hidden (Hide whole field completely)';
	$choices['hide_label']                    = 'Hide label';
	$choices['hide_label_maintain_height']    = 'Hide label, but maintain label height';
	$choices['hide_but_save']                 = 'Hide field, but save it';
	$choices['hide_input']                    = 'Hide input';
	$choices['hide_label_show_desc']          = 'Hide label, but show the description';
	$choices['show_on_pdf']                   = 'Show on PDF';
	$choices['hide_on_pdf']                   = 'Hide on PDF';
	$choices[ zxzu( 'ol_start' ) ]            = 'ol start';
	$choices[ zxzu( 'ol_continued' ) ]        = 'ol continued';
	$choices[ zxzu( 'ol_end' ) ]              = 'ol end';
	$choices[ zxzu( 'float_right_initial' ) ] = 'Float right initial field';
	$choices[ zxzu( 'float_left_initial' ) ]  = 'Float left initial field';
	$choices[ zxzu( 'large_checkbox' ) ]      = 'Make the checkboxes larger';
	$choices[ zxzu( 'nomp_top' ) ]            = 'No margin or padding on the top';
	$choices[ zxzu( 'nomp_bottom' ) ]         = 'No margin or padding on the bottom';
	$choices[ zxzu( 'nomp_top_n_bottom' ) ]   = 'No margin or padding on the top & bottom';

	//LCT Classes: DomPDF
	$choices['dompdf_only_show_checked'] = 'When displaying a list of check boxes, only display the checked items and hide the unchecked'; //Not actually needed as a class
	$choices['dompdf_1_column']          = '1 column checkbox list';                                                                       //Not actually needed as a class, but does use class: lct_acf_checkbox_column
	$choices['dompdf_2_column']          = '2 column checkbox list';                                                                       //Not actually needed as a class, but does use class: lct_acf_checkbox_column
	$choices['dompdf_3_column']          = '3 column checkbox list';                                                                       //Not actually needed as a class, but does use class: lct_acf_checkbox_column
	$choices['dompdf_4_column']          = '4 column checkbox list';                                                                       //Not actually needed as a class, but does use class: lct_acf_checkbox_column
	$choices['dompdf_5_column']          = '5 column checkbox list';                                                                       //Not actually needed as a class, but does use class: lct_acf_checkbox_column

	$choices['dompdf_left']                                    = 'PDF left half';
	$choices['dompdf_right']                                   = 'PDF right half';
	$choices['dompdf_inline_left']                             = 'PDF display inline left';
	$choices[ zxzu( 'dompdf_force_avoid_page_break_inside' ) ] = 'PDF avoid a page break inside this field';


	$choices = apply_filters( 'lct/acf/pretty_class_selector', $choices );
	ksort( $choices );


	return $choices;
}


/**
 * Get just the label of a key
 *
 * @param $key
 *
 * @return mixed|string
 * @since    7.30
 * @verified 2016.11.11
 */
function lct_acf_get_class_selector_label( $key )
{
	$r       = '';
	$choices = lct_acf_get_pretty_class_selector();


	if ( isset( $choices[ $key ] ) ) {
		$r = $choices[ $key ];
	}


	return $r;
}


/**
 * Choices for column_start_width
 *
 * @return array
 * @since    7.30
 * @verified 2016.12.09
 */
function lct_acf_get_pretty_column_start_width()
{
	$choices = [];


	$choices['50'] = '50%';
	$choices['98'] = '100%';


	return $choices;
}


/**
 * Get just the label of a key
 *
 * @param $key
 *
 * @return mixed|string
 * @since    7.30
 * @verified 2016.11.11
 */
function lct_acf_get_column_start_width_label( $key )
{
	$r       = '';
	$choices = lct_acf_get_pretty_column_start_width();


	if ( isset( $choices[ $key ] ) ) {
		$r = $choices[ $key ];
	}


	return $r;
}


/**
 * Choices for column_end_type
 *
 * @return array
 * @since    7.30
 * @verified 2016.12.09
 */
function lct_acf_get_pretty_column_end_type()
{
	$choices = [];


	$choices['break_in_row'] = 'Break in the row';
	$choices['end_of_row']   = 'End of the row';


	return $choices;
}


/**
 * Get just the label of a key
 *
 * @param $key
 *
 * @return mixed|string
 * @since    7.30
 * @verified 2016.11.11
 */
function lct_acf_get_column_end_type_label( $key )
{
	$r       = '';
	$choices = lct_acf_get_pretty_column_end_type();


	if ( isset( $choices[ $key ] ) ) {
		$r = $choices[ $key ];
	}


	return $r;
}


/**
 * Choices for text_wrapper
 *
 * @return array
 * @since    7.31
 * @verified 2016.11.12
 */
function lct_acf_get_pretty_section_text_wrapper()
{
	$choices = [];


	$choices['h1'] = htmlentities( '<h1>' );
	$choices['h2'] = htmlentities( '<h2>' );
	$choices['h3'] = htmlentities( '<h3>' );
	$choices['h4'] = htmlentities( '<h4>' );
	$choices['h5'] = htmlentities( '<h5>' );
	$choices['h6'] = htmlentities( '<h6>' );


	return $choices;
}


/**
 * Choices for preset field choices
 *
 * @return array
 * @since    7.51
 * @verified 2016.12.09
 */
function lct_acf_get_pretty_preset_choices()
{
	$choices = [];


	$choices = apply_filters( 'lct/acf/pretty_preset_choices', $choices );
	ksort( $choices );


	return $choices;
}


/**
 * Choices for preset field choices
 *
 * @return array
 * @since    2017.34
 * @verified 2017.04.29
 */
function lct_acf_get_pretty_roles_n_caps()
{
	$choices = [];


	$choices = apply_filters( 'lct/acf/pretty_roles_n_caps', $choices );


	return $choices;
}


/**
 * Easier way to get the unsaved values of acf
 *
 * @return array
 * @since    2017.77
 * @verified 2019.04.10
 */
function lct_acf_get_before_save_values()
{
	return lct_acf_get_POST_values_w_selector_key( null, true );
}


/**
 * Easier way to get the unsaved value of an acf field
 *
 * @param string $selector
 * @param int    $post_id
 * @param bool   $format_value
 *
 * @return mixed|null
 * @since    2017.77
 * @verified 2019.04.10
 */
function lct_acf_get_before_save_value( $selector, $post_id, $format_value = true )
{
	return lct_acf_get_POST_value( $selector, $post_id, $format_value );
}


/**
 * Returns an array of all sub_fields of an ACF Repeater field array
 * You can set the key of each element to a desired $sub_field_key of the ACF Repeater, otherwise it will be the first
 *
 * @param string $selector
 * @param int    $post_id
 * @param string $sub_field_key
 * @param int    $max_count
 *
 * @return array
 * @since    2017.58
 * @verified 2019.01.16
 */
function lct_acf_get_repeater_array( $selector, $post_id, $sub_field_key = null, $max_count = 0 )
{
	/**
	 * Return early if cache is found
	 */
	$cache_key = lct_cache_key( compact( 'selector', 'post_id', 'sub_field_key', 'max_count' ) );
	if ( lct_isset_cache( $cache_key ) ) {
		return lct_get_cache( $cache_key );
	}


	$r   = [];
	$row = 1;


	if (
		( $repeater = get_field( $selector, $post_id ) )
		&& ! empty( $repeater )
	) {
		/**
		 * Set the key to the first repeater field if it is not set
		 */
		if ( ! $sub_field_key ) {
			$sub_field_key = key( $repeater[0] );
		}


		/**
		 * Build the array
		 */
		foreach ( $repeater as $row_data ) {
			$return_key = null;

			if ( isset( $row_data[ $sub_field_key ] ) ) {
				$return_key = $row_data[ $sub_field_key ];
			}


			foreach ( $row_data as $data_key => $data ) {
				/**
				 * Term Objects
				 */
				if ( lct_is_a( $data, 'WP_Term' ) ) {
					if ( $data_key === 'status' ) //We have to have this for some old compatibility
					{
						$row_data[ $data_key . lct_pre_us( 'term_id' ) ] = $data->term_id;
					}
					$row_data['term_id'] = $data->term_id;


					if ( is_object( $row_data[ $sub_field_key ] ) ) {
						$return_key = $data->term_id;
					}


					/**
					 * Post Objects
					 */
				} elseif ( lct_is_a( $data, 'WP_Post' ) ) {
					$row_data['ID'] = $data->ID;


					if ( is_object( $row_data[ $sub_field_key ] ) ) {
						$return_key = $data->ID;
					}
				}
			}


			if (
				$return_key === null
				&& isset( $row_data[ $sub_field_key ] )
			) {
				$return_key = $row_data[ $sub_field_key ];
			}


			$r[ $return_key ] = $row_data;


			/**
			 * Stop if we reach max_count
			 */
			if (
				$max_count
				&& $row >= $max_count
			) {
				break;
			}


			$row ++;
		}
	}


	/**
	 * Save the value to the cache
	 */
	lct_set_cache( $cache_key, $r );


	return $r;
}


/**
 * Returns the keys of each element of an ACF Repeater field array
 * You can set the $sub_field_key of the ACF Repeater you would like returned, otherwise it will return the first
 *
 * @param string $selector
 * @param int    $post_id
 * @param string $sub_field_key
 * @param int    $max_count
 *
 * @return array
 * @since    2017.83
 * @verified 2019.01.10
 */
function lct_acf_get_repeater_array_keys( $selector, $post_id, $sub_field_key = null, $max_count = 0 )
{
	if (
		( $r = lct_acf_get_repeater_array( $selector, $post_id, $sub_field_key, $max_count ) )
		&& ! empty( $r )
	) {
		$r = array_keys( $r );
	}


	return $r;
}


/**
 * Returns the key of a specific ($sub_field_index) indexed element of an ACF Repeater field array
 * You can set the $sub_field_key of the ACF Repeater you would like returned, otherwise it will return the first
 *
 * @param string $selector
 * @param int    $post_id
 * @param string $sub_field_key
 * @param int    $sub_field_index
 *
 * @return string|mixed
 * @since    2018.59
 * @verified 2019.01.10
 */
function lct_acf_get_repeater_array_key( $selector, $post_id, $sub_field_key = null, $sub_field_index = 0 )
{
	$r = null;


	if (
		( $keys = lct_acf_get_repeater_array_keys( $selector, $post_id, $sub_field_key ) )
		&& ! empty( $keys[ $sub_field_index ] )
	) {
		$r = $keys[ $sub_field_index ];
	}


	return $r;
}


/**
 * Returns the values of each element of an ACF Repeater field array
 * You can set the $sub_field_key of the ACF Repeater you would like returned, otherwise it will return the first
 *
 * @param string $selector
 * @param int    $post_id
 * @param string $sub_field
 * @param string $sub_field_key
 * @param int    $max_count
 *
 * @return array
 * @since    2019.1
 * @verified 2019.04.09
 */
function lct_acf_get_repeater_array_values( $selector, $post_id, $sub_field, $sub_field_key = null, $max_count = 0 )
{
	$r = [];


	if (
		( $arr = lct_acf_get_repeater_array( $selector, $post_id, $sub_field_key, $max_count ) )
		&& ! empty( $arr )
	) {
		foreach ( $arr as $k => $v ) {
			if ( isset( $v[ $sub_field ] ) ) {
				$r[ $k ] = $v[ $sub_field ];
			}
		}
	}


	return $r;
}


/**
 * Returns the value of a specific ($sub_field_index) indexed element of an ACF Repeater field array
 * You can set the $sub_field_key of the ACF Repeater you would like returned, otherwise it will return the first
 *
 * @param string $selector
 * @param int    $post_id
 * @param string $sub_field
 * @param string $sub_field_key
 * @param int    $sub_field_index
 *
 * @return string|mixed
 * @since    2019.1
 * @verified 2019.01.10
 */
function lct_acf_get_repeater_array_value( $selector, $post_id, $sub_field, $sub_field_key = null, $sub_field_index = 0 )
{
	$r = null;


	if (
		( $values = lct_acf_get_repeater_array_values( $selector, $post_id, $sub_field, $sub_field_key ) )
		&& ! empty( $values )
	) {
		if (
			! $sub_field_key
			&& is_int( $sub_field_index )
		) {
			$values = array_values( $values );
		}


		if ( ! empty( $values[ $sub_field_index ] ) ) {
			$r = $values[ $sub_field_index ];
		}
	}


	return $r;
}


/**
 * Returns the value of a specific ($sub_field_index) indexed element of an ACF Repeater field array
 *
 * @param string $selector
 * @param int    $post_id
 * @param string $sub_field
 * @param int    $sub_field_index
 *
 * @return string|mixed
 * @since    2019.1
 * @verified 2019.01.10
 */
function lct_acf_get_repeater_array_value_by_slug( $selector, $post_id, $sub_field, $sub_field_index = 0 )
{
	$r = null;


	if (
		( $value = lct_acf_get_repeater_array_value( $selector, $post_id, $sub_field, null, $sub_field_index ) )
		&& ! empty( $value )
	) {
		$r = $value;
	}


	return $r;
}


/**
 * Returns an imploded version of an ACF repeater
 *
 * @param        $selector
 * @param        $post_id
 * @param array  $order
 * @param string $glue
 * @param int    $max_count
 *
 * @return array
 * @since    7.12
 * @verified 2017.09.11
 */
function lct_acf_get_repeater( $selector, $post_id, $order = [], $glue = ',', $max_count = 0 )
{
	$r        = [];
	$row      = 1;
	$repeater = get_field( $selector, $post_id );


	if (
		! empty( $repeater )
		&& is_array( $repeater )
	) {
		foreach ( $repeater as $row_data ) {
			$this_row = [];


			if ( ! empty( $row_data ) ) {
				if ( ! empty( $order ) ) {
					$order_count = 0;

					foreach ( $order as $data ) {
						if (
							isset( $row_data[ $data ] )
							&& ( $value = $row_data[ $data ] )
						) {
							if (
								is_object( $value )
								&& lct_is_a( $value, 'WP_Term' )
							) {
								$value = $value->name;
							}


							if ( $glue ) {
								if (
									is_array( $glue )
									&& (
										(

											isset( $glue[ $order_count - 1 ] )
											&& $glue[ $order_count - 1 ]
										)
										|| (
											isset( $glue[ $order_count ]['pre'] )
											&& isset( $glue[ $order_count ] )
											&& $glue[ $order_count ]
										)
									)
								) {
									if ( isset( $glue[ $order_count ]['callback'] ) ) {
										$value = call_user_func( $glue[ $order_count ]['callback'], $value );
									}


									if ( isset( $glue[ $order_count ]['pre'] ) ) {
										$this_row[] = $glue[ $order_count ]['pre'];
										$this_row[] = $value;
										$this_row[] = $glue[ $order_count ]['post'];
									} else {
										$this_row[] = $glue[ $order_count - 1 ];
									}
								} elseif ( ! is_array( $glue ) ) {
									$this_row[] = $glue;
								}
							}

							if ( ! isset( $glue[ $order_count ]['pre'] ) ) {
								$this_row[] = $value;
							}
						}

						$order_count ++;
					}

					$r[] = lct_return( $this_row );
				} else {
					foreach ( $row_data as $data ) {
						if ( $data ) {
							$this_row[] = $data;
						}
					}

					$r[] = lct_return( $this_row, $glue );
				}
			}


			if (
				$max_count
				&& $row >= $max_count
			) {
				break;
			}

			$row ++;
		}


		reset_rows();
	}


	return $r;
}


/**
 * Returns an imploded version of one sub_field of an ACF repeater
 * Similar: repeater_items_shortcode()
 *
 * @param        $selector
 * @param        $post_id
 * @param        $sub_field
 * @param int    $max_count
 * @param string $glue
 *
 * @return string
 * @since    7.3
 * @verified 2016.09.29
 */
function lct_acf_get_imploded_repeater( $selector, $post_id, $sub_field, $max_count = 0, $glue = ',' )
{
	$r   = [];
	$row = 1;


	if ( have_rows( $selector, $post_id ) ) {
		while( have_rows( $selector, $post_id ) ) {
			the_row();

			$sub_field_value = get_sub_field( $sub_field );

			if ( $sub_field_value ) {
				$r[] = $sub_field_value;
			}

			if (
				$max_count
				&& $row >= $max_count
			) {
				break;
			}

			$row ++;
		}


		reset_rows();
	}


	return lct_return( $r, $glue );
}


/**
 * Get the ACF group of a field
 * Don't use this
 *
 * @param string|array $field_parent
 *
 * @return array
 * @since    2019.25
 * @verified 2019.11.01
 */
function lct_acf_get_field_group_of_field( $field_parent )
{
	$r = false;


	if ( is_array( $field_parent ) ) {
		$field_parent = $field_parent['parent'];
	}


	if (
		( $group = acf_get_field_group( $field_parent ) )
		&& acf_is_field_group( $group )
	) {
		$r = $group;
	} elseif (
		acf_is_field_key( $field_parent )
		&& ( $field = get_field_object( $field_parent, false, false, false ) )
		&& ( $group = lct_acf_get_field_group_of_field( $field['parent'] ) )
		&& acf_is_field_group( $group )
	) {
		$r = $group;
	}


	return $r;
}


/**
 * Get an array of fields from groups
 * //TODO: cs - Need to improve the speed of this - 6/12/2017 9:31 PM
 *
 * @param array $group_args
 * @param array $args_field
 * @param bool  $return_raw
 *
 * @return array
 * @since    7.21
 * @verified 2019.11.26
 */
function lct_acf_get_field_groups_fields( $group_args = [], $args_field = [], $return_raw = false )
{
	// return early if cache is found
	$cache_key = lct_cache_key( compact( 'group_args', 'args_field' ) );
	if ( $r = wp_cache_get( $cache_key ) ) {
		return $r;
	}


	$fields     = [];
	$raw_fields = [];


	if ( lct_did() ) {
		return $fields;
	}


	/**
	 * Set default $args_field
	 */
	$default_args_field = [
		'filter_fields' => false,
		'format_value'  => true,
	];
	$args_field         = wp_parse_args( $args_field, $default_args_field );


	/**
	 * Get the list af groups that meet our args
	 */
	if ( isset( $group_args['selector'] ) ) {
		$groups = [];


		if ( ! is_array( $group_args['selector'] ) ) {
			$group_args['selector'] = [ $group_args['selector'] ];
		}


		foreach ( $group_args['selector'] as $group ) {
			$groups[] = acf_get_field_group( $group );
		}
	} else {
		$groups = acf_get_field_groups( $group_args );
	}


	/**
	 * Setup fields filters
	 */
	if (
		! empty( $args_field['where_field'] )
		&& ! empty( $args_field['where_operator'] )
		&& //Allowed: ===, ==, !==, !=, LIKE, NOT LIKE, IN, NOT IN
		isset( $args_field['where_value'] )
	) {
		$args_field['filter_fields'] = true;


		if ( empty( $args_field['where_relation'] ) ) {
			$args_field['where_relation'] = 'OR';
		}


		if ( ! is_array( $args_field['where_field'] ) ) {
			$args_field['where_field'] = [ $args_field['where_field'] ];
		}

		if ( ! is_array( $args_field['where_operator'] ) ) {
			$args_field['where_operator'] = [ $args_field['where_operator'] ];
		}

		if ( ! is_array( $args_field['where_value'] ) ) {
			$args_field['where_value'] = [ $args_field['where_value'] ];
		}
	}


	/**
	 * Start compiling the fields
	 */
	//only continue if we have groups
	if ( ! empty( $groups ) ) {
		foreach ( $groups as $group ) {
			$tmp = acf_get_fields( $group );

			if ( is_array( $tmp ) ) {
				$raw_fields = array_merge( $raw_fields, $tmp );
			}


			//only continue if we have fields
			if ( ! empty( $tmp ) ) {
				/**
				 * Filter the fields based on our parameters
				 */
				//only continue if we have filter_fields set to true
				if (
					! empty( $args_field['filter_fields'] )
					|| ! empty( $args_field['load_value'] )
					|| ! empty( $args_field['return'] )
				) {
					$tmp_field = $tmp;
					$tmp       = [];


					foreach ( $tmp_field as $field ) {
						//skip the field if it is filtered out by our parameters
						if (
							$args_field['filter_fields']
							&& lct_acf_get_filter_fields( $field, $args_field )
						) {
							continue;
						}


						/**
						 * load the value of the field
						 */
						if ( ! empty( $args_field['load_value'] ) ) {
							if (
								$field['type'] === 'clone'
								&& ! empty( $field['sub_fields'] )
							) {
								foreach ( $field['sub_fields'] as $sub_field_key => $sub_field ) {
									$field['sub_fields'][ $sub_field_key ]['value'] = get_field( $sub_field['__key'], $args_field['post_id'], $args_field['format_value'] );

									if (
										$sub_field['type'] === 'taxonomy'
										&& $field['sub_fields'][ $sub_field_key ]['value']
										&& ( $tmp_term = get_term( $field['sub_fields'][ $sub_field_key ]['value'], $sub_field['taxonomy'] ) )
										&& ! lct_is_wp_error( $tmp_term )
									) {
										$field['sub_fields'][ $sub_field_key ]['value_name'] = $tmp_term->name;
									}
								}
							}


							$field['value'] = get_field( $field['key'], $args_field['post_id'], $args_field['format_value'] );


							if (
								$field['type'] === 'taxonomy'
								&& $field['value']
								&& ( $tmp_term = get_term( $field['value'], $field['taxonomy'] ) )
								&& ! lct_is_wp_error( $tmp_term )
							) {
								$field['value_name'] = $tmp_term->name;
							}
						}


						/**
						 * Return the specific variable from the $field array
						 */
						if ( ! empty( $args_field['return'] ) ) {
							$field = acf_extract_var( $field, $args_field['return'] );
						}


						$tmp[] = $field;
					}
				}


				$fields = array_merge( $fields, $tmp );
			}
		}
	}


	if ( $return_raw ) {
		$r = [
			'fields'     => $fields,
			'raw_fields' => $raw_fields,
			'raw_groups' => $groups,
		];
	} else {
		$r = $fields;
	}


	if (
		! empty( $args_field['clear_cache'] )
		&& ! empty( $raw_fields )
	) {
		foreach ( $raw_fields as $raw_field ) {
			acf_flush_field_cache( $raw_field );
		}
	}


	wp_cache_set( $cache_key, $r );


	lct_undid();


	return $r;
}


/**
 * Check whether the field should be filtered out or not
 *
 * @param $field
 * @param $args_field
 *
 * @return bool
 * @since    7.33
 * @verified 2017.09.29
 */
function lct_acf_get_filter_fields( $field, $args_field )
{
	$skip_field = true;


	foreach ( $args_field['where_field'] as $key => $value ) {
		if ( $args_field['where_relation'] === 'AND' ) {
			$skip_field = true;
		}


		switch ( $args_field['where_operator'][ $key ] ) {
			case '===':
				if ( $field[ $args_field['where_field'][ $key ] ] === $args_field['where_value'][ $key ] ) {
					$skip_field = false;
				}
				break;


			case '==':
				if ( $field[ $args_field['where_field'][ $key ] ] == $args_field['where_value'][ $key ] ) {
					$skip_field = false;
				}
				break;


			case '!==':
				if ( $field[ $args_field['where_field'][ $key ] ] !== $args_field['where_value'][ $key ] ) {
					$skip_field = false;
				}
				break;


			case '!=':
				if ( $field[ $args_field['where_field'][ $key ] ] != $args_field['where_value'][ $key ] ) {
					$skip_field = false;
				}
				break;


			case 'LIKE':
			case 'like':
			case 'Like':
				if ( strpos( $field[ $args_field['where_field'][ $key ] ], $args_field['where_value'][ $key ] ) !== false ) {
					$skip_field = false;
				}
				break;


			case 'NOT LIKE':
			case 'not like':
			case 'Not Like':
			case 'Not like':
				if ( strpos( $field[ $args_field['where_field'][ $key ] ], $args_field['where_value'][ $key ] ) === false ) {
					$skip_field = false;
				}
				break;


			case 'IN':
			case 'in':
			case 'In':
				if ( in_array( $field[ $args_field['where_field'][ $key ] ], $args_field['where_value'][ $key ] ) ) {
					$skip_field = false;
				}
				break;


			case 'NOT IN':
			case 'not in':
			case 'Not In':
			case 'Not in':
				if ( ! in_array( $field[ $args_field['where_field'][ $key ] ], $args_field['where_value'][ $key ] ) ) {
					$skip_field = false;
				}
				break;


			default:
				$skip_field = false;
		}


		if (
			(
				! $skip_field
				&& $args_field['where_relation'] === 'OR'
			)
			|| (
				$skip_field
				&& $args_field['where_relation'] === 'AND'
			)
		) {
			break;
		}
	}


	return $skip_field;
}


/**
 * An array of emails that can be used to exclude or include in view, conditionals, etc.
 *
 * @return array
 * @since    4.2.2.24
 * @verified 2018.03.05
 */
function lct_acf_get_dev_emails()
{
	/**
	 * Return early if cache is found
	 */
	$cache_key = lct_cache_key();
	if ( lct_isset_cache( $cache_key ) ) {
		return lct_get_cache( $cache_key );
	}


	$r = [];


	/**
	 * Only if we have ACF running
	 */
	if ( lct_plugin_active( 'acf' ) ) {
		$dev_emails = lct_acf_get_option( 'dev_emails' );


		/**
		 * Set default emails if the field is empty & save them
		 */
		if (
			empty( $dev_emails )
			&& lct_acf_get_option_raw( 'api' )
		) {
			$url        = lct_get_api_url( 'dev_emails.php?key=' . lct_acf_get_option_raw( 'api' ) );
			$resp       = file_get_contents( $url );
			$dev_emails = json_decode( $resp, true );

			lct_acf_update_option( 'dev_emails', $dev_emails );
		}


		/**
		 * Create an array of only active emails
		 */
		if (
			! empty( $dev_emails )
			&& is_array( $dev_emails )
		) {
			foreach ( $dev_emails as $email ) {
				if ( $email['active'] ) {
					$r[] = $email['email'];
				}
			}
		}
	}


	/**
	 * Save the value to the cache
	 */
	lct_set_cache( $cache_key, $r );


	return $r;
}


if ( ! function_exists( 'get_label' ) ) {
	/**
	 * Get the label by the field name
	 *
	 * @param            $field_name
	 * @param bool|false $post_id
	 *
	 * @return mixed
	 * @since    5.2
	 * @verified 2022.08.24
	 */
	function get_label( $field_name, $post_id = false )
	{
		$field = get_field_object( $field_name, $post_id, [ 'load_value' => false ] );


		if ( isset( $field['label'] ) ) {
			return $field['label'];
		}


		return '';
	}
}


if ( ! function_exists( 'the_label' ) ) {
	/**
	 * echo the label by the field name
	 *
	 * @param            $field_name
	 * @param bool|false $post_id
	 *
	 * @since    5.2
	 * @verified 2019.02.16
	 */
	function the_label( $field_name, $post_id = false )
	{
		echo get_label( $field_name, $post_id );
	}
}


/**
 * When you are saving an ACF form they store the values in an array with the field_key as the array key.
 * This function allows you to look up a value by the selector as well
 *
 * @param string $selector
 * @param int    $post_id
 * @param bool   $format_value
 * @param bool   $double_check
 *
 * @return mixed|null
 * @since    2019.4
 * @verified 2023.08.31
 */
function lct_acf_get_POST_value( $selector, $post_id = null, $format_value = false, $double_check = true )
{
	if ( is_numeric( $post_id ) ) {
		$post_id = lct_get_acf_post_id( $post_id );
	}


	/**
	 * Return early if cache is found
	 */
	$cache_key = lct_cache_key( compact( 'selector', 'post_id', 'format_value' ) );
	if ( lct_isset_cache( $cache_key ) ) {
		return lct_get_cache( $cache_key );
	}


	$r            = null;
	$selector_set = null;


	if (
		function_exists( 'lct_instances' )
		&& ! empty( lct_instances()->acf_loaded )
	) {
		//Is a dupe field_name
		if (
			! empty( lct_instances()->acf_loaded->references['dupe_names'] )
			&& array_search( $selector, lct_instances()->acf_loaded->references['dupe_names'] )
		) {
			foreach ( lct_instances()->acf_loaded->references['dupe_data'] as $key => $maybe_selector ) {
				if (
					$maybe_selector['field_name'] === $selector
					&& isset( $_POST['acf'][ $key ] )
				) {
					$selector     = $key;
					$selector_set = true;
					break;
				}
			}


			//Is a non-dupe field_name
		} elseif ( ! empty( lct_instances()->acf_loaded->references['map_name_key'][ $selector ] ) ) {
			$selector     = lct_instances()->acf_loaded->references['map_name_key'][ $selector ];
			$selector_set = true;
		}
	}


	/**
	 * Final check
	 */
	if ( ! $selector_set ) {
		if (
			$double_check
			&& ( $tmp = get_field( $selector, $post_id, false ) )
			&& ! empty( $tmp['key'] )
		) {
			$selector = $tmp['key'];
		}
	}


	if ( isset( $_POST['acf'][ $selector ] ) ) {
		$r = $_POST['acf'][ $selector ];


		if ( lct_acf_is_field_repeater( $selector ) ) {
			$r = lct_acf_get_POST_values_w_selector_key( $r, false, true );
		} elseif (
			$format_value
			&& $post_id
			&& ( $field = acf_get_field( $selector ) )
			&& ( $formatted = acf_format_value( $r, $post_id, $field ) )
			&& ! lct_is_wp_error( $formatted )
		) {
			$r = $formatted;
		}
	}


	/**
	 * Save the value to the cache
	 */
	lct_set_cache( $cache_key, $r );


	return $r;
}


/**
 * When you are saving an ACF form they store the values in an array with the field_key as the array key.
 * This function allows you to look up a value by the selector as well
 * Don't use this
 *
 * @param string $selector
 * @param int    $row
 * @param string $sub_field
 * @param int    $post_id
 * @param bool   $format_value
 *
 * @return mixed|null
 * @since    2019.8
 * @verified 2022.11.15
 */
function lct_acf_get_POST_repeater_value( $selector, $row, $sub_field, $post_id = null, $format_value = false )
{
	$post_id = lct_get_acf_post_id( $post_id );


	/**
	 * Return early if cache is found
	 */
	$cache_key = lct_cache_key( compact( 'selector', 'row', 'sub_field', 'post_id', 'format_value' ) );
	if ( lct_isset_cache( $cache_key ) ) {
		return lct_get_cache( $cache_key );
	}


	$r = null;


	if ( ( $parent_value = lct_acf_get_POST_value( $selector, $post_id ) ) === null ) {
		return $r;
	}


	$parent_value = array_values( $parent_value );


	if ( isset( $parent_value[ $row ][ $sub_field ] ) ) {
		$r = $parent_value[ $row ][ $sub_field ];


		if (
			$format_value
			&& $post_id
			&& ( $field = acf_get_field( $sub_field ) )
			&& ( $formatted = acf_format_value( $r, $post_id, $field ) )
			&& ! lct_is_wp_error( $formatted )
		) {
			$r = $formatted;
		}
	}


	/**
	 * Save the value to the cache
	 */
	lct_set_cache( $cache_key, $r );


	return $r;
}


/**
 * When you are saving an ACF form they store the values in an array with the field_key as the array key.
 * This function allows you to look up a value by the selector as well
 *
 * @param array       $post
 * @param bool        $return_field    //Return the full ACF field array or just the value
 * @param bool|string $special_process //false | true (repeater field_type) OR repeater_{key} | looping | clone_{key} | maintain_seamless_clones
 *
 * @return array
 * @since    2019.6
 * @verified 2023.12.19
 */
function lct_acf_get_POST_values_w_selector_key( $post = null, $return_field = false, $special_process = false )
{
	/**
	 * Vars
	 */
	$r = [];

	if ( $post === null && isset( $_POST ) ) {
		$post = $_POST;
	}

	if ( empty( $post ) ) {
		return $r;
	}

	$post = lct_clean_acf_repeater( $post );


	/**
	 * Special processing for a repeater field
	 */
	if (
		$special_process === true
		|| str_starts_with( $special_process, 'repeater_' )
	) {
		foreach ( $post as $k => $v ) {
			/**
			 * Check for clones in a repeater
			 */
			if (
				$special_process !== true
				&& ( $repeater_key = substr( $special_process, 9 ) ) //remove repeater_
				&& is_array( $v )
				&& $k !== $repeater_key
			) {
				$has_clones = false;

				foreach ( $v as $tmp => $not_needed ) {
					if (
						acf_is_field_key( $tmp )
						&& ! acf_get_field( $tmp )
					) {
						$has_clones = true;
					}
				}


				if ( $has_clones ) {
					$new_v = [];


					foreach ( $v as $clone_k => $clone_v ) {
						$explode_k      = explode( 'field_', $clone_k );
						$clone_parent_k = $clone_k;


						if (
							count( $explode_k ) >= 3
							&& ( $tmp = 'field_' . rtrim( $explode_k[1], '_' ) )
							&& lct_acf_is_field_clone( $tmp )
						) {
							$clone_parent_k = $tmp;
						}


						if (
							! lct_acf_is_field_clone( $tmp )
							|| count( $explode_k ) < 3
						) {
							$new_v[ $clone_parent_k ] = $clone_v;
						} else {
							$new_v[ $clone_parent_k ][ $clone_k ] = $clone_v;
						}
					}


					$v = $new_v;
				}
			}


			/**
			 * Send the fields in the repeater
			 */
			$send = [ 'acf' => $v ];


			$r[ $k ] = lct_acf_get_POST_values_w_selector_key( $send, $return_field, 'looping' );
		}


		return $r;


		/**
		 * Special processing for a clone field
		 */
	} elseif (
		str_starts_with( $special_process, 'clone_' )
		&& is_array( $post )
	) {
		$key = substr( $special_process, 6 ); //remove clone_


		foreach ( $post as $k => $v ) {
			$clone_key = substr( $k, strlen( $key ) + 1 );


			if (
				! acf_is_field_key( $k )
				|| ! acf_is_field_key( $clone_key )
			) {
				continue;
			}


			$send = [ 'acf' => [ $clone_key => $v ] ];


			$cloned_fields = lct_acf_get_POST_values_w_selector_key( $send, $return_field, 'looping' );


			if ( $return_field ) {
				foreach ( $cloned_fields as $cloned_field_key => $cloned_field ) {
					$cloned_fields[ $cloned_field_key ]['afwp_tmp_is_clonesub_field'] = true;
				}
			}


			$r = array_merge( $r, $cloned_fields );
		}


		return $r;
	}


	/**
	 * Swap out keys --TO-- selectors
	 */
	if ( ! empty( $post['acf'] ) ) {
		foreach ( $post['acf'] as $key => $v ) {
			/**
			 * Skip any non-ACF elements
			 * This should ever happen
			 */
			if ( ! acf_is_field_key( $key ) ) {
				continue;
			}


			/**
			 * Vars
			 */
			$field    = acf_get_field( $key );
			$selector = lct_acf_get_selector( $key, false );


			/**
			 * Loop thru a repeater
			 */
			if ( lct_acf_is_field_repeater( $key ) ) {
				$v = lct_acf_get_POST_values_w_selector_key( $v, $return_field, 'repeater_' . $key );
			}


			/**
			 * Loop thru clones
			 */
			if ( lct_acf_is_field_clone( $key ) ) {
				$v = lct_acf_get_POST_values_w_selector_key( $v, $return_field, 'clone_' . $key );
			}


			/**
			 * Return the full ACF field array
			 */
			if ( $return_field ) {
				//continue if no field
				if ( ! $field ) {
					$r[ $key ] = $v;


					continue;
				}


				//Add the value to the field array
				$field['value'] = $v;


				$r[ $selector ] = $field;


				/**
				 * Return just the value
				 */
			} else {
				$r[ $selector ] = $v;
			}


			/**
			 * Flatten clones
			 */
			if (
				lct_acf_is_field_seamless_clone( $key )
				&& $special_process !== 'maintain_seamless_clones'
				&& ! empty( $r[ $selector ] )
				&& is_array( $r[ $selector ] )
			) {
				if ( $return_field ) {
					$r = array_merge( $r, $r[ $selector ]['value'] );
				} else {
					$r = array_merge( $r, $r[ $selector ] );
				}


				unset( $r[ $selector ] );
			}
		}
	}


	return $r;
}


/**
 * When you are saving an ACF form they store the values in an array with the field_key as the array key.
 * This function allows you to look up a value by the selector as well
 *
 * @param array       $post
 * @param bool|string $special_process //false | true (repeater field_type) OR repeater_{key} | looping | clone_{key} | maintain_seamless_clones
 *
 * @return array
 * @date     2022.10.21
 * @since    2022.10
 * @verified 2022.10.24
 */
function lct_acf_get_POST_key_selector_map( $post = null, $special_process = false )
{
	/**
	 * Vars
	 */
	$r = [];

	if ( $post === null && isset( $_POST ) ) {
		$post = $_POST;
	}

	if ( empty( $post ) ) {
		return $r;
	}

	$post = lct_clean_acf_repeater( $post );


	/**
	 * Special processing for a repeater field
	 */
	if ( $special_process === true ) {
		foreach ( $post as $v ) {
			$send = [ 'acf' => $v ];


			$r = lct_acf_get_POST_key_selector_map( $send, 'looping' );


			break;
		}


		return $r;


		/**
		 * Special processing for a clone field
		 */
	} elseif (
		str_starts_with( $special_process, 'clone_' )
		&& is_array( $post )
	) {
		$key = substr( $special_process, 6 ); //remove clone_


		foreach ( $post as $k => $v ) {
			$clone_key = substr( $k, strlen( $key ) + 1 );


			if (
				! acf_is_field_key( $k )
				|| ! acf_is_field_key( $clone_key )
			) {
				continue;
			}


			$send = [ 'acf' => [ $clone_key => $v ] ];


			$r = array_merge( $r, lct_acf_get_POST_key_selector_map( $send, 'looping' ) );
		}


		return $r;
	}


	/**
	 * Set selectors for each key
	 */
	if ( ! empty( $post['acf'] ) ) {
		foreach ( $post['acf'] as $key => $v ) {
			/**
			 * Skip any non-ACF elements
			 * This should ever happen
			 */
			if ( ! acf_is_field_key( $key ) ) {
				$r[ $key ] = $key;


				continue;
			}


			/**
			 * Vars
			 */
			$selector = lct_acf_get_selector( $key, false );


			/**
			 * Loop thru a repeater
			 */
			if ( lct_acf_is_field_repeater( $key ) ) {
				$selector = [ $selector => lct_acf_get_POST_key_selector_map( $v, true ) ];
			}


			/**
			 * Loop thru clones
			 */
			if ( lct_acf_is_field_clone( $key ) ) {
				$selector = lct_acf_get_POST_key_selector_map( $v, 'clone_' . $key );
			}


			/**
			 * Return just the value
			 */
			$r[ $key ] = $selector;


			/**
			 * Flatten clones
			 */
			if (
				lct_acf_is_field_seamless_clone( $key )
				&& $special_process !== 'maintain_seamless_clones'
				&& ! empty( $r[ $key ] )
				&& is_array( $r[ $key ] )
			) {
				$r = array_merge( $r, $r[ $key ] );


				unset( $r[ $key ] );
			}
		}
	}


	return $r;
}


/**
 * Get the selector when you know saving an instant field
 *
 * @return string|null
 * @since    2019.4
 * @verified 2024.04.19
 */
function lct_acf_get_POST_instant_selector()
{
	$r = null;


	if ( ! empty( $_POST['lct:::field_key'] ) ) {
		$r = lct_acf_get_selector( $_POST['lct:::field_key'] );
	} elseif (
		function_exists( 'afwp_acf_get_form_data' )
		&& afwp_acf_get_form_data( 'save_now', 'lct' ) === true
		&& ( $tmp = afwp_acf_get_form_arr_var( 'fields' ) )
	) {
		$r = reset( $tmp );
	}


	return $r;
}


/**
 * Get the value when you know saving an instant field
 *
 * @return string|null
 * @since    2019.4
 * @verified 2024.04.19
 */
function lct_acf_get_POST_instant_value()
{
	$r = null;


	if ( ! empty( $_POST['lct:::value'] ) ) {
		$r = $_POST['lct:::value'];
	} elseif (
		function_exists( 'afwp_acf_get_form_data' )
		&& afwp_acf_get_form_data( 'save_now', 'lct' ) === true
	) {
		$r = afwp_REQUEST_arg( 'value' );
	}


	return $r;
}


/**
 * Get the selector when you know the key
 *
 * @param string $key    //Must be a field_key, or you may get the wrong field returned
 * @param bool   $strict //Return the found selector OR null ONLY if true, return the provided key if false and selector is not found
 *
 * @return string|null
 * @since    2019.4
 * @verified 2023.08.31
 */
function lct_acf_get_selector( $key, $strict = true )
{
	/**
	 * Vars
	 */
	if ( $strict ) {
		$selector = null;
	} else {
		$selector = $key;
	}


	/**
	 * Search for the selector
	 */
	if ( $answer = acf_get_field( $key ) ) {
		if ( ! empty( $answer['_name'] ) ) {
			$selector = $answer['_name'];
		} elseif ( ! empty( $answer['name'] ) ) {
			$selector = $answer['name'];
		}
	} elseif (
		function_exists( 'lct_instances' )
		&& ! empty( lct_instances()->acf_loaded )
		&& ! empty( lct_instances()->acf_loaded->references['map_name_key'] )
		&& ( $tmp = lct_instances()->acf_loaded->references['map_name_key'] )
		&& ( $tmp = array_search( $key, $tmp ) )
	) {
		$selector = $tmp;
	}


	return $selector;
}


/**
 * Check if the field is a repeater type field
 *
 * @param string $key //Should be a key, You can use a selector as well, but you may get the wrong field returned
 *
 * @return bool
 * @date     2022.10.20
 * @since    2022.10
 * @verified 2022.10.24
 */
function lct_acf_is_field_repeater( $key )
{
	/**
	 * Check the type
	 */
	if (
		( $answer = acf_get_field( $key ) )
		&& $answer['type'] === 'repeater'
	) {
		return true;
	}


	return false;
}


/**
 * Check if the field is a clone type field
 *
 * @param string $key //Should be a key, You can use a selector as well, but you may get the wrong field returned
 *
 * @return bool
 * @date     2022.10.20
 * @since    2022.10
 * @verified 2022.10.24
 */
function lct_acf_is_field_clone( $key )
{
	/**
	 * Check the type
	 */
	if (
		( $answer = acf_get_field( $key ) )
		&& $answer['type'] === 'clone'
	) {
		return true;
	}


	return false;
}


/**
 * Check if the field is a seamless clone type field
 *
 * @param string $key //Should be a key, You can use a selector as well, but you may get the wrong field returned
 *
 * @return bool
 * @date     2022.10.20
 * @since    2022.10
 * @verified 2022.10.20
 */
function lct_acf_is_field_seamless_clone( $key )
{
	/**
	 * Check the display
	 */
	if (
		lct_acf_is_field_clone( $key )
		&& ( $answer = acf_get_field( $key ) )
		&& ! empty( $answer['display'] )
		&& $answer['display'] === 'seamless'
	) {
		return true;
	}


	return false;
}


/**
 * Get the acf options pages
 *
 * @return array
 * @since    2019.4
 * @verified 2022.01.06
 */
function lct_acf_get_options_pages()
{
	$r = null;


	if ( lct_plugin_active( 'acf' ) ) {
		$r = acf_get_options_pages();
	}


	return $r;
}


/**
 * Get the old value of a field while you are actively saving the new value
 *
 * @param      $selector
 * @param bool $post_id
 * @param bool $format_value
 *
 * @return mixed|null
 * @since    2019.7
 * @verified 2019.04.08
 */
function lct_acf_get_old_field_value( $selector, $post_id = false, $format_value = true )
{
	acf_flush_value_cache( $post_id, $selector );
	$old_value = get_field( $selector, $post_id, $format_value );
	acf_flush_value_cache( $post_id, $selector );


	return $old_value;
}


/**
 * Button Text Class
 *
 * @return string
 * @since    2019.23
 * @verified 2019.08.22
 */
function lct_acf_get_menu_button_class()
{
	$class = '';


	if (
		lct_acf_get_option( 'menu_button_text_in_header' )
		&& ! lct_acf_get_option( 'menu_button_text_in_header_sticky' )
	) {
		$class = 'hide_if_sticky';
	} elseif (
		! lct_acf_get_option( 'menu_button_text_in_header' )
		&& lct_acf_get_option( 'menu_button_text_in_header_sticky' )
	) {
		$class = 'show_if_sticky';
	} elseif (
		! lct_acf_get_option( 'menu_button_text_in_header' )
		&& ! lct_acf_get_option( 'menu_button_text_in_header_sticky' )
	) {
		$class = 'hidden';
	}


	return $class;
}


/**
 * Mobile Nav Colors
 *
 * @return string
 * @since    2019.23
 * @verified 2019.08.22
 */
function lct_acf_get_mobi_nav_colors()
{
	$colors = '';


	if ( $tmp = lct_acf_get_option( 'mobi_nav_bar_bg_color' ) ) {
		$colors .= " button_gradient_top_color=\"{$tmp}\" button_gradient_bottom_color=\"{$tmp}\" ";
	}

	if ( $tmp = lct_acf_get_option( 'mobi_nav_bar_bg_color_hover' ) ) {
		$colors .= " button_gradient_top_color_hover=\"{$tmp}\" button_gradient_bottom_color_hover=\"{$tmp}\" ";
	}

	if ( $tmp = lct_acf_get_option( 'mobi_nav_bar_color' ) ) {
		$colors .= " accent_color=\"{$tmp}\" ";
	}

	if ( $tmp = lct_acf_get_option( 'mobi_nav_bar_color_hover' ) ) {
		$colors .= " accent_hover_color=\"{$tmp}\" ";
	}


	return $colors;
}


/**
 * Mobile Nav Colors
 * //TODO: cs - Need phone color ACF - 01/06/2017 11:41 PM
 *
 * @param string $type
 *
 * @return string
 * @since    2019.23
 * @verified 2019.08.22
 */
function lct_acf_get_specific_mobi_nav_color( $type = null )
{
	$colors = '';


	if ( $tmp = lct_acf_get_field_option( $type . '_button_color' ) ) {
		$colors .= " button_gradient_top_color=\"{$tmp}\" button_gradient_bottom_color=\"{$tmp}\" ";
		$colors .= " button_gradient_top_color_hover=\"{$tmp}\" button_gradient_bottom_color_hover=\"{$tmp}\" ";
	}

	if ( $tmp = lct_acf_get_field_option( $type . '_button_text_color' ) ) {
		$colors .= " accent_color=\"{$tmp}\" ";
		$colors .= " accent_hover_color=\"{$tmp}\" ";
	}


	return $colors;
}
