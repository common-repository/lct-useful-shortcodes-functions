<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Get settings from options table
 *
 * @param null $value
 *
 * @return array|mixed
 * @deprecated 5.40.24
 * @since      0.0
 * @verified   2016.09.27
 */
function lct_get_lct_useful_settings( $value = null )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '5.40.24' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	$settings = lct_get_option( 'useful_settings' );


	if ( ! $settings ) {
		$settings = [];
	}


	if ( $value ) {
		if ( array_key_exists( $value, $settings ) ) {
			return $settings[ $value ];
		} else {
			return false;
		}
	}


	return $settings;
}


/**
 * Get the slug of a particular post_type
 *
 * @param $post_type
 *
 * @return string
 * @deprecated 5.40.24
 * @since      5.35
 * @verified   2016.09.27
 */
function lct_get_post_type_slug( $post_type )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '5.40.24', 'get_post_type_archive_link()' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	return get_post_type_archive_link( $post_type );
}


/**
 * Get a single meta_value from a WP Term
 * Returns the meta_value of the key you set. Or the whole term_meta array is no key is set.
 * *
 * Keep $tax as lct_option just in case an old site is still relying on it.
 *
 * @param        $term_id
 * @param string $tax
 * @param null   $key
 *
 * @return bool|mixed
 * @deprecated 6.2
 * @since      0.0
 * @verified   2016.09.27
 */
function lct_get_term_meta( $term_id, $tax = 'lct_option', $key = null )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '6.2' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	$term_meta_value = false;


	if (
		! empty( $term_id )
		&& ! empty( $tax )
	) {
		$term_meta = get_option( $tax . "_" . $term_id );


		if ( ! empty( $term_meta ) ) {
			if ( $key ) {
				$term_meta_value = $term_meta[ $key ];
			} else {
				$term_meta_value = $term_meta;
			}
		}
	}


	return $term_meta_value;
}


/**
 * Get a single meta_value from the parent WP Term of a WP Term
 * Returns the meta_value of the key you set. Or the whole term_meta array is no key is set.
 * *
 * Keep $tax as lct_option just in case an old site is still relying on it.
 *
 * @param      $term_id
 * @param      $tax
 * @param null $key
 *
 * @return bool|mixed
 * @deprecated 6.2
 * @since      0.0
 * @verified   2017.05.18
 */
function lct_get_parent_term_meta( $term_id, $tax = 'lct_option', $key = null )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '6.2' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	$term_meta_value = false;


	if (
		! empty( $term_id )
		&& ! empty( $tax )
	) {
		$term = get_term( $term_id, $tax );


		if (
			! lct_is_wp_error( $term )
			&& ! empty( $term->parent )
		) {
			$parent_term_meta = get_option( $tax . "_" . $term->parent );


			if ( ! empty( $parent_term_meta ) ) {
				if ( $key ) {
					$term_meta_value = $parent_term_meta[ $key ];
				} else {
					$term_meta_value = $parent_term_meta;
				}
			}
		}
	}


	return $term_meta_value;
}


/**
 * Replaced function
 *
 * @deprecated 7.21
 * @since      4.2.2.24
 * @verified   2016.10.21
 */
function lct_get_dev_emails()
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '7.21', 'lct_acf_get_dev_emails()' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	return lct_acf_get_dev_emails();
}


/**
 * Check if we are running this site as a sandbox site
 *
 * @return bool
 * @deprecated 7.38
 * @since      5.36
 * @verified   2016.11.25
 */
function lct_is_sandbox()
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '7.38', 'lct_is_sb()' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	return lct_is_sb();
}


/**
 * Check if the ACF plugin is active
 *
 * @return bool
 * @deprecated 7.42
 * @since      0.0
 * @verified   2016.11.29
 */
function lct_acf_active()
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '7.42', 'lct_plugin_active( \'acf\' )' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	return lct_plugin_active( 'acf' );
}


/**
 * WordPress v4.6 broke this, even in the admin, so I had to fix it.
 *
 * @return bool
 * @deprecated 7.42
 * @since      7.9
 * @verified   2016.11.29
 */
function lct_is_plugin_active()
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '7.42', 'is_plugin_active()' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	return false;
}


/**
 * Check Avada Version
 *
 * @deprecated 7.42
 * @since      6.0
 * @verified   2017.01.10
 */
function lct_is_avada_version_any()
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '7.69' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	$r = false;


	if ( lct_theme_active( 'Avada' ) ) {
		$r = true;
	}


	return $r;
}


/**
 * Check Avada Version
 * Checks if the current Avada version is less than v4.0
 *
 * @deprecated 7.42
 * @since      6.0
 * @verified   2017.09.09
 */
function lct_is_avada_version_3_n_below()
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '7.69' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	$r = false;


	if ( version_compare( lct_theme_version( 'Avada' ), '4.0', '<' ) ) //Avada older than v4.0
	{
		$r = true;
	}


	return $r;
}


/**
 * This is used as a bug fix in Avada v3.8.7
 *
 * @param $url
 *
 * @return mixed
 * @deprecated 7.42
 * @since      0.0
 * @verified   2017.01.10
 */
function lct_Avada_get_url_with_correct_scheme( $url )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '7.69' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	return $url;
}


/**
 * Get an array of the parent id of a single term
 *
 * @param      $term_id
 * @param      $taxonomy
 * @param bool $get_all_parents
 *
 * @return array
 * @deprecated 7.42
 * @since      5.36
 * @verified   2017.05.18
 */
function lct_get_term_parent( $term_id, $taxonomy, $get_all_parents = false )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '2017.17' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	$parent = [];
	$term   = get_term( $term_id, $taxonomy );


	if ( ! lct_is_wp_error( $term ) ) {
		if ( $term->parent !== 0 ) {
			$parent[] = $term->parent;
		}


		if (
			$get_all_parents
			&& $term->parent !== 0
		) {
			$term = get_term( $term->parent, $taxonomy );


			if (
				! lct_is_wp_error( $term )
				&& $term->parent !== 0
			) {
				$parent[] = $term->parent;
			}
		}
	}


	return array_unique( $parent );
}


/**
 * Get an array of parent ids of an array of terms
 *
 * @param      $terms
 * @param      $taxonomy
 * @param bool $get_all_parents
 * @param bool $include_terms
 *
 * @return array
 * @deprecated 7.42
 * @since      5.36
 * @verified   2017.02.22
 */
function lct_get_terms_parents( $terms, $taxonomy, $get_all_parents = false, $include_terms = false )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '2017.17' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	$parents = [];


	foreach ( $terms as $term ) {
		if ( is_object( $term ) ) {
			$term = $term->term_id;
		}


		/** @noinspection PhpDeprecationInspection */
		$parent  = lct_get_term_parent( $term, $taxonomy, $get_all_parents );
		$parents = array_merge( $parents, $parent );


		if ( $include_terms ) {
			$parents = array_merge( $parents, [ $term ] );
		}
	}


	return array_unique( $parents );
}


/**
 * Get an array of term_ids from an array of term objects
 *
 * @param $terms
 *
 * @return array
 * @deprecated 7.42
 * @since      5.36
 * @verified   2017.02.22
 */
function lct_get_terms_ids( $terms )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '2017.17' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	$term_ids = [];


	if ( is_array( $terms ) ) {
		foreach ( $terms as $term ) {
			if ( is_object( $term ) ) {
				$term_ids[] = $term->term_id;
			}
		}
	}


	return $term_ids;
}


/**
 * Easily call a single ACF field and also have the ability to wrap it in a custom class
 *
 * @param        $field
 * @param array  $options
 * @param array  $our_options
 * @param bool   $return
 *
 * @return bool
 * @deprecated 2017.34
 * @since      5.25
 * @verified   2017.09.28
 */
function lct_acf_form( $field, $options = [], $our_options = [], $return = false )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '2017.34', 'lct_acf_form2()' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	if ( empty( $field ) ) {
		return false;
	}


	$output        = '';
	$current_field = get_field_object( $field, false, false );


	if (
		strpos( $current_field['wrapper']['class'], '_instant' ) !== false
		|| ! isset( $options['submit_value'] )
	) {
		$options['submit_value'] = '&nbsp;';
	}


	if (
		$current_field
		&& $current_field['wrapper']['class']
	) {
		$access = explode( ' ', $current_field['wrapper']['class'] );


		if ( ! apply_filters( 'lct/current_user_can_access', true, $access ) ) {
			if ( apply_filters( 'lct/current_user_can_view', false, $access ) ) {
				if ( $return ) {
					ob_start();
				}


				echo '<div class="acf-field">
					<div class="acf-label">
						<label>' . get_label( $current_field['key'] ) . '</label>
					</div>
					<div class="acf-input">
						' . lct_acf_format_value( $current_field['value'], false, $current_field, true ) . '
					</div>
				</div>';


				if ( $return ) {
					$output = ob_get_clean();
				}
			}


			return $output;
		}
	}


	$options_default = [
		'updated_message' => '',
		'form_attributes' => [ 'id' => zxzu( 'acf_form_' . sanitize_title( $current_field['key'] ) ) ],
	];

	$options = array_merge( $options, $options_default, [ 'fields' => [ $field ] ] );

	if ( ! isset( $our_options['wrapper_pre'] ) ) {
		$our_options['wrapper_pre'] = '<div class="%s" id="%s">';
	}

	if ( ! isset( $our_options['wrapper_post'] ) ) {
		$our_options['wrapper_post'] = '</div>';
	}


	if ( $return ) {
		ob_start();
	}


	/**
	 * #1
	 * @date     0.0
	 * @since    7.49
	 * @verified 2021.08.27
	 */
	do_action( 'lct/acf_form/before_acf_form', $options );


	echo sprintf( $our_options['wrapper_pre'], $our_options['wrapper_class'], $our_options['wrapper_id'] );


	acf_form( $options );


	echo $our_options['wrapper_post'];


	echo lct_Avada_clear();


	if ( $return ) {
		$output = ob_get_clean();
	}


	return $output;
}


/**
 * Easily call a FULL ACF form and also have the ability to wrap it in a custom class
 *
 * @param array $options
 * @param array $our_options
 * @param bool  $return
 *
 * @return bool
 * @deprecated 2017.34
 * @since      5.25
 * @verified   2017.04.29
 */
function lct_acf_form_full( $options = [], $our_options = [], $return = false )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '2017.34', 'lct_acf_form2()' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	if (
		isset( $our_options['access'] )
		&& ! apply_filters( 'lct/current_user_can_access', true, $our_options['access'] )
	) {
		return false;
	}


	$options_default = [
		'updated_message' => '',
		'form_attributes' => [ 'id' => lct_rand( zxzu( 'acf_form_rand_' ) ) ],
	];

	if ( $options['new_post'] ) {
		$options_default['form_attributes']['class'] .= 'acf-form lct_acf_new_post';
		$options_default['post_title']               = false;
		$options_default['post_content']             = false;

		$options['return'] = '%post_url%';
	}

	$options = array_merge( $options, $options_default );

	if ( $options['post_id'] == 'new_post' ) /**
	 * #1
	 * @date     0.0
	 * @since    5.25
	 * @verified 2021.08.27
	 */ {
		do_action( 'lct/acf/new_post' );
	}

	if ( ! isset( $our_options['wrapper_pre'] ) ) {
		$our_options['wrapper_pre'] = '<div class="%s" id="%s">';
	}

	if ( ! isset( $our_options['wrapper_post'] ) ) {
		$our_options['wrapper_post'] = '</div>';
	}

	$output = '';


	if ( $return ) {
		ob_start();
	}


	echo sprintf( $our_options['wrapper_pre'], $our_options['wrapper_class'], $our_options['wrapper_id'] );


	/**
	 * @date     0.0
	 * @since    5.25
	 * @verified 2021.08.27
	 */
	do_action( 'lct/acf/before_lct_acf_form_full', $our_options, $options );


	acf_form( $options );


	echo $our_options['wrapper_post'];


	echo lct_Avada_clear();


	if ( $return ) {
		$output = ob_get_clean();
	}


	return $output;
}


/**
 * Add a local field_group into the DB
 *
 * @param $field_group
 *
 * @deprecated 2017.42
 * @since      7.35
 * @verified   2017.06.08
 */
function lct_update_db_with_local_group( $field_group )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '2017.42', 'ACF LOAD JSON' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	if ( lct_doing() ) {
		return;
	}


	$current_field_group = acf_get_field_group( $field_group['key'] );

	if (
		isset( $current_field_group['ID'] )
		&& $current_field_group['ID']
	) {
		$field_group['ID'] = $current_field_group['ID'];
	}


	$field_group['title'] = '[LOCAL] ' . $field_group['title'];


	//create the new field_group, or update the existing one
	$new_field_group = acf_update_field_group( $field_group );


	//create the new fields
	if ( $new_field_group ) {
		foreach ( $field_group['fields'] as $key => $field ) {
			$current_field = _acf_get_field_by_key( $field['key'] );

			if (
				isset( $current_field['ID'] )
				&& $current_field['ID']
			) {
				$field['ID'] = $current_field['ID'];
			}


			$field['parent'] = $new_field_group['ID'];


			$field['menu_order'] = $key;


			acf_update_field( $field );


			//TODO: cs - this needs to work with nested sub_fields - 11/18/2016 10:20 AM
			if ( isset( $field['sub_fields'] ) ) {
				foreach ( $field['sub_fields'] as $sub_key => $sub_field ) {
					$current_sub_field = _acf_get_field_by_key( $sub_field['key'] );

					if (
						isset( $current_sub_field['ID'] )
						&& $current_sub_field['ID']
					) {
						$sub_field['ID'] = $current_sub_field['ID'];
					}


					$sub_field['parent'] = $field['ID'];


					$sub_field['menu_order'] = $sub_key;


					acf_update_field( $sub_field );
				}
			}
		}
	}
}


/**
 * Retrieve all the field objects of an ACF group
 *
 * @param array $group
 *
 * @return array
 * @deprecated 2017.42
 * @since      7.17
 * @verified   2017.06.09
 */
function lct_acf_get_group_fields( $group )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '2017.42', 'acf_get_fields()' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	return acf_get_fields( $group );
}


/**
 * This function exists to get a field value during the saving of a field/form and clear the cache, so it will not affect the true value that needs to be stored in the cache
 *
 * @param $selector
 * @param $post_id
 *
 * @return bool|mixed|null
 * @deprecated 2019.2
 * @since      7.3
 * @verified   2019.02.16
 */
function lct_acf_get_old_field( $selector, $post_id )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '2019.2' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	if ( function_exists( 'get_field' ) ) {
		return get_field( $selector, $post_id );
	}


	return [];
}


/**
 * Clear the ACF cache for a particular selector
 *
 * @param $selector
 * @param $post_id
 *
 * @deprecated 2019.2
 * @since      7.3
 * @verified   2019.02.16
 */
function lct_acf_cache_delete( $selector, $post_id )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '2019.2', 'acf_flush_value_cache( $post_id, $field_name )' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	if ( function_exists( 'acf_flush_value_cache' ) ) {
		acf_flush_value_cache( $post_id, $selector );
	}
}


/**
 * Best way to solve an update issue with ACF for now.
 *
 * @param $field_name
 * @param $post_type
 *
 * @return mixed
 * @deprecated 2019.2
 * @since      7.3
 * @verified   2019.02.16
 */
function lct_acf_get_key_post_type( $field_name, $post_type )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '2019.2' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	global $wpdb;

	$success = false;

	$fields_ids = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT `pm`.`ID` FROM `lc_posts` `pm`
			WHERE `pm`.`post_type` = '%s' AND
			`pm`.`post_status` = '%s' AND
			`pm`.`post_excerpt` = '%s'",
			'acf-field',
			'publish',
			$field_name
		)
	);


	if ( ! empty( $fields_ids ) ) {
		$field_id = null;


		if ( count( $fields_ids ) === 1 ) {
			$field_id = $fields_ids[0];
		} else {
			foreach ( $fields_ids as $field_id ) {
				$field_obj = _acf_get_field_by_id( $field_id );
				$group     = acf_get_field_group( $field_obj['parent'] );


				if ( ! empty( $group ) ) {
					foreach ( $group['location'] as $location ) {
						foreach ( $location as $location_or ) {
							if (
								$location_or['operator'] === '=='
								&& $location_or['param'] === 'post_type'
								&& $location_or['operator'] === $post_type
							) {
								//Do nothing but break
								break;
							}
						}
					}
				}
			}
		}


		if ( $field_obj = _acf_get_field_by_id( $field_id ) ) {
			if ( ! empty( $field['_clone'] ) ) {
				$field_name = $field_obj['__key'];
			} else {
				$field_name = $field_obj['key'];
			}


			$success = true;
		}
	}


	if ( ! $success ) {
		$args = [ 'post_type' => $post_type ];


		$args = apply_filters( 'lct/acf/get_key_post_type', $args );


		$fields = lct_acf_get_field_groups_fields( $args );


		if ( ! empty( $fields ) ) {
			$field_name_exists = array_search( $field_name, array_column( $fields, 'name' ), true );


			if ( $field_name_exists !== false ) {
				$field = $fields[ $field_name_exists ];


				if ( ! empty( $field['_clone'] ) ) {
					$field_name = $field['__key'];
				} else {
					$field_name = $field['key'];
				}
			}
		}
	}


	return $field_name;
}


/**
 * Best way to solve an update issue with ACF for now.
 *
 * @param $field_name
 * @param $taxonomy
 *
 * @return mixed
 * @deprecated 2019.2
 * @since      5.39
 * @verified   2019.02.16
 */
function lct_acf_get_key_taxonomy( $field_name, $taxonomy )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '2019.2' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	global $wpdb;

	$success = false;

	$fields_ids = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT `pm`.`ID` FROM `lc_posts` `pm`
			WHERE `pm`.`post_type` = '%s' AND
			`pm`.`post_status` = '%s' AND
			`pm`.`post_excerpt` = '%s'",
			'acf-field',
			'publish',
			$field_name
		)
	);


	if ( ! empty( $fields_ids ) ) {
		$field_id = null;


		if ( count( $fields_ids ) === 1 ) {
			$field_id = $fields_ids[0];
		} else {
			foreach ( $fields_ids as $field_id ) {
				$field_obj = _acf_get_field_by_id( $field_id );
				$group     = acf_get_field_group( $field_obj['parent'] );


				if ( ! empty( $group ) ) {
					foreach ( $group['location'] as $location ) {
						foreach ( $location as $location_or ) {
							if (
								$location_or['operator'] === '=='
								&& $location_or['param'] === 'taxonomy'
								&& $location_or['operator'] === $taxonomy
							) {
								//Do nothing but break
								break;
							}
						}
					}
				}
			}
		}


		if ( $field_obj = _acf_get_field_by_id( $field_id ) ) {
			if ( ! empty( $field['_clone'] ) ) {
				$field_name = $field_obj['__key'];
			} else {
				$field_name = $field_obj['key'];
			}


			$success = true;
		}
	}


	if ( ! $success ) {
		$fields = lct_acf_get_field_groups_fields( [ 'taxonomy' => $taxonomy ] );


		if ( ! empty( $fields ) ) {
			$field_name_exists = array_search( $field_name, array_column( $fields, 'name' ), true );


			if ( $field_name_exists !== false ) {
				return $fields[ $field_name_exists ]['key'];
			}
		}
	}


	return $field_name;
}


/**
 * Best way to solve an update issue with ACF for now.
 * //TODO: cs - This needs some love - 6/9/2017 1:35 PM
 *
 * @param        $field_name
 * @param string $user_id
 *
 * @return mixed
 * @deprecated 2019.2
 * @since      5.38
 * @verified   2019.02.16
 */
function lct_acf_get_key_user( $field_name, $user_id = '' )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '2019.2' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	if ( $user_id ) {
		$fields = lct_acf_get_field_groups_fields( [ 'user_id' => $user_id ] );


		if ( ! empty( $fields ) ) {
			$field_name_exists = array_search( $field_name, array_column( $fields, 'name' ), true );


			if ( $field_name_exists !== false ) {
				return $fields[ $field_name_exists ]['key'];
			}
		}
	} else {
		global $wp_roles;

		$groups = [];
		$roles  = [ 'all' => 'All' ];

		if ( ! empty( $wp_roles ) ) {
			$roles = array_merge( $roles, $wp_roles->roles );
		}

		foreach ( $roles as $role_key => $role ) {
			$tmp = acf_get_field_groups( [ 'user_role' => $role_key ] );

			if ( ! empty( $tmp ) ) {
				$groups = array_merge( $groups, $tmp );
			}
		}

		$groups = array_unique( $groups );


		if ( ! empty( $groups ) ) {
			foreach ( $groups as $group ) {
				$location_rules = acf_extract_var( $group, 'location' );


				if ( ! empty( $location_rules ) ) {
					foreach ( $location_rules as $location_or ) {
						foreach ( $location_or as $location_and ) {
							if ( $location_and['param'] == 'user_role' ) {
								$fields = acf_get_fields( $group );


								if ( $fields ) {
									foreach ( $fields as $field ) {
										if (
											isset( $field['name'] )
											&& $field['name'] == $field_name
										) {
											return $field['key'];
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}


	return $field_name;
}


/**
 * We can not use update_field() because it might save the terms to the comment and not the post
 *
 * @param $selector
 * @param $value
 * @param $post_id
 *
 * @return bool
 * @deprecated 2019.7
 * @since      7.19
 * @verified   2019.04.08
 */
function lct_acf_update_field_inside_comment( $selector, $value, $post_id )
{
	add_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function', 10, 3 );
	_deprecated_function( __FUNCTION__, '2019.7' );
	remove_action( 'deprecated_function_run', 'lct_force_trigger_error_deprecated_function' );


	if (
		( $field = get_field_object( $selector, $post_id, true, false ) )
		&& empty( $field )
	) {
		return false;
	}


	/**
	 * comments
	 */
	if ( strpos( $post_id, 'comment_' ) !== false ) {
		$comment_id = lct_cc( $post_id );


		update_comment_meta( $comment_id, $field['name'], $value );
		update_comment_meta( $comment_id, lct_pre_us( $field['name'] ), $field['key'] );


		/**
		 * posts
		 */
	} else {
		switch ( $field['type'] ) {
			case 'taxonomy':
				if ( is_array( $value ) ) {
					$tmp   = $value;
					$value = [];


					foreach ( $tmp as $v ) {
						if ( is_numeric( $v ) ) {
							$value[] = (int) $v;
						}
					}
				} else {
					if ( is_numeric( $value ) ) {
						$value = (int) $value;
					}


					if ( $field['multiple'] == 1 ) {
						$value = [ $value ];
					}
				}


				if ( $field['save_terms'] ) {
					wp_set_object_terms( $post_id, $value, $field['taxonomy'] );
				}
				break;


			default:
		}


		$allow_update = false;


		if ( $selector === lct_status() ) {
			/**
			 * Update the status of the post
			 */
			lct_append_later( 'update_post_status', $field['key'], $post_id );


			/**
			 * Remove term relationships and postmeta
			 */
			if (
				empty( $field['save_terms'] )
				&& empty( $field['load_terms'] )
			) {
				wp_delete_object_term_relationships( $post_id, $field['taxonomy'] );


				/**
				 * This will delete the postmeta
				 */
				delete_post_meta( $post_id, $field['name'] );
				delete_post_meta( $post_id, lct_pre_us( $field['name'] ) );
			} elseif (
				! empty( $field['save_terms'] )
				&& ! empty( $field['load_terms'] )
			) {
				/**
				 * This will delete the postmeta
				 */
				delete_post_meta( $post_id, $field['name'] );
				delete_post_meta( $post_id, lct_pre_us( $field['name'] ) );
			} else {
				$allow_update = true;
			}
		}


		if ( $allow_update ) {
			update_post_meta( $post_id, $field['name'], $value );
			update_post_meta( $post_id, lct_pre_us( $field['name'] ), $field['key'] );
			lct_acf_update_field_later( $selector, $value, $post_id );
		}
	}


	return true;
}
