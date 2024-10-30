<?php
/** @noinspection PhpMissingFieldTypeInspection */
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2022.01.06
 */
class lct_acf_loaded
{
	public $references = [];
	public $tmp_fields = [];


	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2018.02.20
	 */
	function __construct( $args = [] )
	{
		//Store $args
		$this->args = $args;


		//Store parent class, maybe
		if ( $this->args['load_parent'] ) {
			$this->zxzp = lct();
		}


		//Setup WordPress action and filter hooks
		$this->load_hooks();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    2018.0
	 * @verified 2023.08.31
	 */
	function load_hooks()
	{
		//bail early if already ran
		if ( lct_did() ) {
			return;
		}


		/**
		 * Actions
		 */
		add_action( 'acf/update_field_group', [ $this, 'delete_transient' ] );


		/**
		 * Don't run hooks if we are:
		 * Updating WordPress
		 * Doing ACF stuff
		 */
		if (
			empty( $_REQUEST['show_dupes'] )
			&& (
				(
					isset( $_GET['action'] )
					&& in_array( $_GET['action'], [ 'do-plugin-upgrade', 'update-selected' ] )
				)
				|| (
					! empty( $_REQUEST['post_type'] )
					&& in_array( $_REQUEST['post_type'], [ 'acf-field-group', 'acf-field' ] )
				)
				|| (
					! empty( $_REQUEST['post'] )
					&& ( $tmp = get_post_type( $_REQUEST['post'] ) )
					&& in_array( $tmp, [ 'acf-field-group', 'acf-field' ] )
				)
				|| (
					! empty( $_REQUEST['action'] )
					&& in_array( $_REQUEST['action'], [ 'heartbeat', 'wp-remove-post-lock', 'acf/fields/clone/query', 'simple_page_ordering' ] )
				)
				|| (
					! empty( $_REQUEST['action'] )
					&& str_contains( $_REQUEST['action'], 'acf/field_group/' )
				)
				|| (
					lct_wp_admin_non_ajax()
					&& isset( $_GET['action'] )
					&& $_GET['action'] === 'edit'
				)
			)
		) {
			return;
		}


		/**
		 * Special reference hooks
		 */
		add_filter( 'acf/prepare_fields_for_import', [ $this, 'prepare_fields_for_import' ], 9 );

		add_filter( 'acf/init', [ $this, 'prepare_fields_for_import_store' ], 9 );

		add_filter( 'acf/load_field', [ $this, 'load_field' ], 9 );

		add_filter( 'acf/pre_load_reference', [ $this, 'pre_load_reference' ], 9, 3 );

		add_filter( 'acf/load_reference', [ $this, 'load_reference' ], 9, 3 );

		add_filter( 'acf/load_field_group', [ $this, 'load_field_group' ], 9 );

		add_filter( 'lct/acf_loaded/load_reference/show_error_log', [ $this, 'show_error_log' ], 10, 2 );
	}


	/**
	 * Store all the references for faster page load
	 *
	 * @date     2023.05.17
	 * @since    2023.02
	 * @verified 2023.08.31
	 */
	function prepare_fields_for_import_store()
	{
		if ( ! get_transient( 'afwp_acf_loaded_references' ) ) {
			set_transient( 'afwp_acf_loaded_references', $this->references, HOUR_IN_SECONDS );
		}
	}


	/**
	 * Delete
	 *
	 * @date     2023.08.31
	 * @since    2023.03
	 * @verified 2023.08.31
	 */
	function delete_transient()
	{
		delete_transient( 'afwp_acf_loaded_references' );
	}


	/**
	 * We prepare the fields so that we can properly manage duplicate field_names 'references'
	 * This will also speed up WordPress.
	 *
	 * @param array $fields
	 *
	 * @return array
	 * @since    2019.2
	 * @verified 2023.08.31
	 */
	function prepare_fields_for_import( $fields )
	{
		if ( empty( $fields ) ) {
			return $fields;
		}


		/**
		 * Vars
		 */
		$this->load_references();
		$this->tmp_fields = $fields;
		$group_key        = null;


		foreach ( $fields as $field ) {
			/**
			 * Vars
			 */
			$field_key  = $field['key'];
			$field_name = null;
			if ( isset( $field['name'] ) ) {
				$field_name = $field['name'];
			}


			//Skip builtin keys
			if ( in_array( $field_key, [ '_post_title', '_post_content', '_validate_email' ] ) ) {
				continue;
			}


			/**
			 * Set the parent
			 * Do this when this field is loaded from the database
			 */
			if (
				! empty( $field['parent'] )
				&& is_numeric( $field['parent'] )
			) {
				$field = $this->load_field( $field );
			}


			//Skip some fields -- Don't save fields that meet 3rd party requirements
			if ( apply_filters( 'lct_acf_loaded/prepare_fields_for_import/ignore', false, $field, $this->references['keys'] ) ) {
				continue;
			}


			/**
			 * Set the $group_key
			 * Has group key assigned to the field as the parent
			 */
			if ( acf_is_field_group_key( $field['parent'] ) ) {
				$group_key = $field['parent'];

				//Skip if the group is already saved
				if ( in_array( $group_key, $this->references['groups_complete'] ) ) {
					break;
				}

				//Save the references we came up with
				$this->save_a_reference( $group_key, $field_key, $field_name, $field );


				/**
				 * Set the $group_key
				 * Do this when this field is a sub_field of another field
				 */
			} elseif ( acf_is_field_key( $field['parent'] ) ) {
				if (
					empty( $group_key )
					&& function_exists( 'afwp_acf_get_group_of_field' )
				) {
					$group_key = afwp_acf_get_group_of_field( $field['parent'], false );
				}

				//Skip if the group is already saved
				if (
					! empty( $group_key )
					&& in_array( $group_key, $this->references['groups_complete'] )
				) {
					break;
				}

				//Validate repeater will load properly
				if ( $field['parent_repeater'] !== $field['parent'] ) {
					lct_debug_to_error_log( sprintf( 'Something is wrong with: %s :: %s', __FUNCTION__, '#2' ) );

					//We already saved it.
				} elseif ( ! empty( $this->references['parents'][ $field['parent_repeater'] ] ) ) {
					continue;
				}

				//Loop
				$this->loop_thru_repeaters( $group_key, $field['parent_repeater'] );


				/**
				 * Set the $group_key
				 * Do nothing when there isn't a group_key
				 */
			} else {
				lct_debug_to_error_log( sprintf( 'Something is wrong with: %s :: %s', __FUNCTION__, '#1' ) );
				break;
			}
		}


		//Finish Group
		if ( $group_key ) {
			$this->references['groups_complete'][ $group_key ] = $group_key;
		}


		return $fields;
	}


	/**
	 * Get references for all the repeater fields
	 *
	 * @param string $group_key
	 * @param string $parent
	 *
	 * @date     2023.08.30
	 * @since    2023.03
	 * @verified 2023.08.31
	 */
	function loop_thru_repeaters( $group_key, $parent )
	{
		if (
			! empty( $group_key )
			&& ( $field_obj = $this->loop_repeater_field_obj( $parent ) )
			&& ! empty( $field_obj['sub_fields'] )
		) {
			$this->references['parents'][ $parent ] = $field_obj['_name'];


			foreach ( $field_obj['sub_fields'] as $sub_field ) {
				if (
					! empty( $sub_field['_clone'] )
					&& isset( $sub_field['__key'] )
					&& ( $tmp = acf_get_field( $sub_field['_clone'] ) )
				) {
					unset( $tmp['sub_fields'] );
					$sub_field = $tmp;
				} elseif (
					! empty( $sub_field['_clone'] )
					&& isset( $sub_field['__key'] )
				) {
					lct_debug_to_error_log( sprintf( 'Something is wrong with: %s :: %s', __FUNCTION__, '#2' ) );
				}


				if (
					! empty( $sub_field['sub_fields'] )
					&& ! empty( $sub_field['parent'] )
				) {
					$this->loop_thru_repeaters( $group_key, $sub_field['key'] );
				}


				/**
				 * Vars
				 */
				$field_key  = $sub_field['key'];
				$field_name = null;
				if ( isset( $sub_field['_name'] ) ) {
					$field_name = $sub_field['_name'];
				}
				$sub_field_full_name = $field_obj['_name'] . '_X_' . $sub_field['_name'];


				$this->references['sub_fields'][ $field_key ] = $sub_field_full_name;


				/**
				 * Save the references we came up with
				 */
				$this->save_a_reference( $group_key, $field_key, $field_name, $sub_field );


				acf_flush_field_cache( $sub_field );
				if ( ! empty( $field_name ) ) {
					acf_flush_value_cache( false, $field_name );
				}
			}


			acf_flush_field_cache( $field_obj );
			if ( ! empty( $field_obj['_name'] ) ) {
				acf_flush_value_cache( false, $field_obj['_name'] );
			}
		} else {
			lct_debug_to_error_log( sprintf( 'Something is wrong with: %s :: %s', __FUNCTION__, '#1' ) );
		}
	}


	/**
	 * Get references for all the repeater fields
	 *
	 * @param string $parent
	 *
	 * @date     2023.08.31
	 * @since    2023.03
	 * @verified 2023.08.31
	 */
	function loop_repeater_field_obj( $parent )
	{
		$field_obj = null;


		/**
		 * Sometimes we need to reference the field_obj, before it has even been loaded into the ACF stores
		 */
		if (
			function_exists( 'afwp_acf_get_field_object' )
			&& ! ( $field_obj = afwp_acf_get_field_object( $parent, false, false, false ) )
		) {
			$sub_fields     = [];
			$sub_sub_fields = [];

			foreach ( $this->tmp_fields as $tmp ) {
				if ( ! isset( $tmp['_name'] ) ) {
					$tmp['_name'] = $tmp['name'];
				}

				//Compile the sub_fields
				if (
					isset( $tmp['parent'] )
					&& $tmp['parent'] === $parent
				) {
					$sub_fields[] = $tmp;
				}

				if (
					isset( $tmp['parent_repeater'] )
					&& $tmp['parent_repeater'] === $parent
				) {
					$sub_sub_fields[] = $tmp;
				}

				//Get the main repeater
				if (
					isset( $tmp['key'] )
					&& $tmp['key'] === $parent
				) {
					$field_obj = $tmp;
				}
			}

			if (
				$field_obj
				&& $sub_fields
			) {
				$field_obj['sub_fields'] = $sub_fields;
			}


			if ( count( $sub_sub_fields ) != count( $sub_fields ) ) {
				lct_debug_to_error_log( sprintf( 'Something is wrong with: %s :: %s', __FUNCTION__, '#1' ) );
			}
		} elseif ( ! function_exists( 'afwp_acf_get_field_object' ) ) {
			lct_debug_to_error_log( sprintf( 'Something is wrong with: %s :: %s', __FUNCTION__, '#2' ) );
		}


		return $field_obj;
	}


	/**
	 * Load references
	 *
	 * @date     2023.08.30
	 * @since    2023.03
	 * @verified 2023.08.31
	 */
	function load_references()
	{
		if ( ! isset( $this->references['keys'] ) ) {
			$this->references = get_transient( 'afwp_acf_loaded_references' );
		}

		if ( ! isset( $this->references['groups'] ) ) {
			$this->references['groups'] = [];
		}

		if ( ! isset( $this->references['groups_complete'] ) ) {
			$this->references['groups_complete'] = [];
		}

		if ( ! isset( $this->references['parents'] ) ) {
			$this->references['parents'] = [];
		}

		if ( ! isset( $this->references['sub_fields'] ) ) {
			$this->references['sub_fields'] = [];
		}

		if ( ! isset( $this->references['clones'] ) ) {
			$this->references['clones'] = [];
		}

		if ( ! isset( $this->references['keys'] ) ) {
			$this->references['keys'] = [];
		}

		if ( ! isset( $this->references['names'] ) ) {
			$this->references['names'] = [];
		}

		if ( ! isset( $this->references['dupe_keys'] ) ) {
			$this->references['dupe_keys'] = [];
		}

		if ( ! isset( $this->references['dupe_names'] ) ) {
			$this->references['dupe_names'] = [];
		}

		if ( ! isset( $this->references['dupe_data'] ) ) {
			$this->references['dupe_data'] = [];
		}

		if ( ! isset( $this->references['count_key'] ) ) {
			$this->references['count_key'] = [];
		}

		if ( ! isset( $this->references['count_name'] ) ) {
			$this->references['count_name'] = [];
		}

		if ( ! isset( $this->references['map_name_key'] ) ) {
			$this->references['map_name_key'] = [];
		}
	}


	/**
	 * Save references
	 *
	 * @param string $group_key
	 * @param string $field_key
	 * @param string $field_name
	 * @param array  $field
	 *
	 * @date     2023.08.30
	 * @since    2023.03
	 * @verified 2023.09.15
	 */
	function save_a_reference( $group_key, $field_key, $field_name, $field )
	{
		/**
		 * Start the new group reference if empty
		 */
		if ( empty( $this->references['groups'][ $group_key ] ) ) {
			$this->references['groups'][ $group_key ] = [];
		}


		/**
		 * Sometimes we don't want to save the field references
		 */
		if (
			empty( $field_name ) //Don't save fields without field_names
			|| (
				! empty( $this->references['groups'][ $group_key ][ $field_key ] ) //Don't save fields that have already been saved
				&& $this->references['groups'][ $group_key ][ $field_key ] === $field_name
			)
			|| (
				str_starts_with( $field_key, '_' ) //Don't save fields with field_keys that start with _
				&& $field_key === $field_name //AND field_key matches the field_name
			)
		) {
			return;
		}


		/**
		 * Save the references we came up with
		 */
		$this->references['groups'][ $group_key ][ $field_key ] = $field_name;
		$this->references['keys'][]                             = $field_key;
		$this->references['names'][]                            = $field_name;


		/**
		 * Clones
		 */
		if (
			! empty( $field['clone'] )
			&& ! empty( $field['type'] )
			&& $field['type'] === 'clone'
		) {
			$this->references['clones'][ $field_key ] = [
				'field_key'  => $field_key,
				'field_name' => $field_name,
				'group_key'  => $group_key,
				'location'   => null,
				'clone'      => $field['clone'], //array
				'_name'      => null,
				'display'    => $field['display'],
			];
			if ( isset( $field['_name'] ) ) {
				$this->references['clones'][ $field_key ]['_name'] = $field['_name'];
			}
			//Intentionally don't IF wrap this so that we can code for an error
			$tmp                                                  = acf_get_field_group( $group_key );
			$this->references['clones'][ $field_key ]['location'] = $tmp['location'];
		} elseif (
			! empty( $field['_clone'] )
			&& ! empty( $field['__key'] )
		) {
			lct_debug_to_error_log( sprintf( 'Something is wrong with: %s :: %s', __FUNCTION__, '#1' ) );
		}


		/**
		 * Save Counts
		 */
		if ( empty( $this->references['count_key'][ $field_key ] ) ) {
			$this->references['count_key'][ $field_key ] = 0;
		}
		$this->references['count_key'][ $field_key ] ++;

		if ( empty( $this->references['count_name'][ $field_name ] ) ) {
			$this->references['count_name'][ $field_name ] = 0;
		}
		$this->references['count_name'][ $field_name ] ++;


		/**
		 * Save Maps
		 * Duplicate
		 */
		if ( $this->references['count_name'][ $field_name ] > 1 ) {
			/**
			 * We have to set the first instance of a duplicate when the 2nd one is found
			 */
			if (
				empty( $this->references['dupe_names'][ $field_key ] )
				&& ! in_array( $field_name, $this->references['dupe_names'] )
			) {
				$dupe = $this->references['map_name_key'][ $field_name ];
				if ( ! $dupe ) {
					lct_debug_to_error_log( sprintf( 'Something is wrong with: %s :: %s', __FUNCTION__, '#2' ) );
				}


				$dupe_arr = [
					'field_key'  => $dupe,
					'field_name' => $field_name,
					'group_key'  => null,
					'location'   => null,
				];


				if (
					function_exists( 'afwp_acf_get_group_of_field' )
					&& ( $tmp = afwp_acf_get_group_of_field( $dupe, false ) )
				) {
					$dupe_arr['group_key'] = $tmp;


					//Intentionally don't IF wrap this so that we can code for an error
					$tmp                  = acf_get_field_group( $dupe_arr['group_key'] );
					$dupe_arr['location'] = $tmp['location'];
				} elseif (
					$group_key
					&& ( $tmp = acf_get_field_group( $group_key ) )
				) {
					$dupe_arr['group_key'] = $group_key;
					$dupe_arr['location']  = $tmp['location'];
				} else {
					lct_debug_to_error_log( sprintf( 'Something is wrong with: %s :: %s', __FUNCTION__, '#2' ) );
				}


				if ( $tmp = get_field_object( $dupe, false, false, false ) ) {
					acf_flush_field_cache( $tmp );
					acf_flush_value_cache( false, $field_name );
				}


				$this->references['dupe_names'][ $dupe ] = $field_name;
				$this->references['dupe_data'][ $dupe ]  = $dupe_arr;
			}


			/**
			 * Save the new dupe
			 */
			$dupe_arr = [
				'field_key'  => $field_key,
				'field_name' => $field_name,
				'group_key'  => $group_key,
			];


			//Intentionally don't IF wrap this so that we can code for an error
			$tmp                  = acf_get_field_group( $dupe_arr['group_key'] );
			$dupe_arr['location'] = $tmp['location'];


			$this->references['dupe_names'][ $field_key ] = $field_name;
			$this->references['dupe_data'][ $field_key ]  = $dupe_arr;
			unset( $this->references['map_name_key'][ $field_name ] );


			/**
			 * Save Maps
			 * Standard
			 */
		} else {
			$this->references['map_name_key'][ $field_name ] = $field_key;
		}


		/**
		 * Duplicate Keys
		 */
		if ( $this->references['count_key'][ $field_key ] > 1 ) {
			lct_debug_to_error_log( sprintf( 'Duplicate ACF field keys are present...This is bad. (%s %s)', $field_key, $field_name ) );


			$this->references['dupe_keys'][ $field_name ] = $field_key;
		}


		/**
		 * Reset fields that require choices
		 * Chances are the choices didn't load properly
		 */
		if ( isset( $field['choices'] ) ) {
			acf_flush_field_cache( $field );
		}
	}


	/**
	 * We can use this to speed up WordPress.
	 *
	 * @param array $field
	 *
	 * @return array
	 * @since    2018.0
	 * @verified 2023.08.31
	 */
	function load_field( $field )
	{
		/**
		 * Set the Parent
		 */
		if (
			empty( $field['parent'] )
			|| ! is_numeric( $field['parent'] )
			|| acf_is_field_group_key( $field['parent'] )
			|| acf_is_field_key( $field['parent'] )
		) {
			return $field;
		}


		/**
		 * Set the Parent
		 */
		$is_parent_reset = false;

		if (
			( $tmp = acf_get_raw_field_group( $field['parent'] ) )
			&& ! empty( $tmp['key'] )
			&& acf_is_field_group_key( $tmp['key'] )
		) {
			$field['parent'] = $tmp['key'];
			$is_parent_reset = true;
		} elseif (
			( $tmp = acf_get_raw_field( $field['parent'] ) )
			&& ! empty( $tmp['key'] )
			&& acf_is_field_key( $tmp['key'] )
		) {
			$field['parent'] = $tmp['key'];
			$is_parent_reset = true;
		}


		if ( ! $is_parent_reset ) {
			lct_debug_to_error_log( sprintf( 'Parent is not set properly for ACF field: %s :: %s', $field['name'], $field['key'] ) );
		}


		return $field;
	}


	/**
	 * We can use this to speed up WordPress.
	 * We will snag the reference before it needs to be looked up in the DB
	 *
	 * @param string     $reference
	 * @param string     $field_name
	 * @param string|int $post_id
	 *
	 * @return string
	 * @since    2018.0
	 * @verified 2023.12.07
	 */
	function pre_load_reference( $reference, $field_name, $post_id )
	{
		//Reference was loaded
		if ( isset( $this->references['map_name_key'][ $field_name ] ) ) {
			return $this->references['map_name_key'][ $field_name ];
		}

		//Reference was accessed already
		if ( $tmp = $this->get_references_accessed( $field_name, $post_id ) ) {
			return $tmp;
		}

		//This IS a duplicate field_name, we will handle the logic on acf/load_reference
		if (
			isset( $this->references['dupe_names'] )
			&& in_array( $field_name, $this->references['dupe_names'] )
		) {
			return $reference;
		}

		//Skip some fields -- Don't save fields that meet 3rd party requirements
		if ( apply_filters( 'lct_acf_loaded/pre_load_reference/ignore', false, $field_name, $this->references ) ) {
			return $reference;
		}

		/**
		 * Check if this is a sub_field
		 */
		$rev_field_name = strrev( $field_name );
		preg_match( '/(.*?)_[0-9]*_(.*)/', $rev_field_name, $field_name_parts );

		if ( ! empty( $field_name_parts[2] ) ) {
			$field_name_end   = strrev( $field_name_parts[1] );
			$field_name_start = strrev( $field_name_parts[2] );

			preg_match( '/(.*?)_[0-9]*_(.*)/', $field_name_parts[2], $field_name_more_parts );
			if ( ! empty( $field_name_more_parts[1] ) ) {
				$field_name_start = strrev( $field_name_more_parts[1] );
			}

			$sub_field_name = $field_name_start . '_X_' . $field_name_end;


			if ( ( $tmp = array_search( $sub_field_name, $this->references['sub_fields'] ) ) ) {
				return $tmp;
			}
		}


		/**
		 * None of our pre-loads worked
		 * If reference is still missing let us know
		 */
		if (
			$reference === null
			//&& lct_is_dev()
			&& apply_filters( 'lct/acf_loaded/pre_load_reference/show_error_log', true, $field_name )
		) {
			lct_debug_to_error_log( sprintf( 'Something is wrong with: %s() :: field %s :: %s', __FUNCTION__, $field_name, '#1' ) );
		}


		return $reference;
	}


	/**
	 * Save the accessed reference
	 *
	 * @param string     $field_name
	 * @param string|int $post_id
	 *
	 * @return string
	 * @date     2023.08.30
	 * @since    2023.03
	 * @verified 2023.08.31
	 */
	function get_references_accessed( $field_name, $post_id )
	{
		$accessed = $this->get_references_accessed_key( $field_name, $post_id );

		if ( isset( $this->references['accessed'][ $accessed ] ) ) {
			return $this->references['accessed'][ $accessed ];
		}

		return null;
	}


	/**
	 * Get the accessed reference key
	 *
	 * @param string     $field_name
	 * @param string|int $post_id
	 *
	 * @return string
	 * @date     2023.08.30
	 * @since    2023.03
	 * @verified 2024.10.03
	 */
	function get_references_accessed_key( $field_name, $post_id )
	{
		$decoded  = acf_decode_post_id( $post_id );
		$accessed = $decoded['type'];

		if (
			$decoded['type'] === 'post'
			&& ( $tmp = get_post_type( $decoded['id'] ) )
		) {
			$accessed = $tmp;
		} elseif (
			$decoded['type'] === 'term'
			&& ( $tmp = afwp_string_ify( $decoded['id'] ) )
			&& ( $tmp = get_term( $tmp ) )
			&& ( $tmp = afwp_get_clean_term_taxonomy( $tmp ) )
		) {
			$accessed = $tmp;
		}

		return $field_name . '_' . $accessed;
	}


	/**
	 * Save the accessed reference
	 *
	 * @param string     $reference
	 * @param string     $field_name
	 * @param string|int $post_id
	 *
	 * @return string
	 * @since    2019.16
	 * @verified 2023.08.31
	 */
	function save_accessed_reference( $reference, $field_name, $post_id )
	{
		$accessed = $this->get_references_accessed_key( $field_name, $post_id );

		return $this->references['accessed'][ $accessed ] = $reference;
	}


	/**
	 * For some reason even the DB could not find a valid reference. Let's see if we can have any luck
	 *
	 * @param string     $reference
	 * @param string     $field_name
	 * @param string|int $post_id
	 *
	 * @return string
	 * @since    2018.0
	 * @verified 2024.01.24
	 */
	function load_reference( $reference, $field_name, $post_id )
	{
		if (
			$reference !== null
			|| ! $field_name
			|| is_array( $field_name )
		) {
			return $reference;
		}


		/**
		 * Vars
		 */
		$decoded = acf_decode_post_id( $post_id );


		//Skip some fields -- Don't save fields that meet 3rd party requirements
		if ( apply_filters( 'lct_acf_loaded/load_reference/ignore', false, $field_name, $this->references ) ) {
			return $reference;
		}


		//This IS a duplicate field_name
		if (
			! empty( $this->references['dupe_names'] )
			&& in_array( $field_name, $this->references['dupe_names'] )
		) {
			if ( ( $pre = apply_filters( 'lct/acf/load_reference/pre_check_duplicate', false, $reference, $field_name, $post_id ) ) !== false ) {
				return $pre;
			}


			if ( ! empty( $this->references['dupe_data'] ) ) {
				switch ( $decoded['type'] ) {
					case 'term':
						$decoded['type_match']      = [ 'taxonomy', 'post_taxonomy' ];
						$decoded['type_match_root'] = 'taxonomy';

						if (
							$post_id
							&& ( $tmp = lct_tt_tax( $post_id ) )
						) {
							if (
								str_starts_with( $post_id, 'term_' )
								&& ( $tmp = lct_tt( $post_id ) )
								&& ( $tmp = get_term( $tmp ) )
								&& ! afwp_is_wp_error( $tmp )
							) {
								$decoded['taxonomy'] = $tmp->taxonomy;
							} else {
								$decoded['taxonomy'] = $tmp;
							}
						}
						break;


					case 'user':
						$decoded['type_match']      = [ 'current_user', 'current_user_role', 'user_form', 'user_role' ];
						$decoded['type_match_root'] = 'user';

						if (
							! empty( $decoded['id'] )
							&& ( $tmp = get_userdata( $decoded['id'] ) )
						) {
							$decoded['user_id'] = $tmp->ID;
							//$decoded['user_role'] = 'user';
						}
						break;


					case 'comment':
						$decoded['type_match']      = [ 'comment' ];
						$decoded['type_match_root'] = 'comment';

						if (
							! empty( $decoded['id'] )
							&& ( $comment = get_comment( $decoded['id'] ) )
							&& ! lct_is_wp_error( $comment )
							&& ! empty( $comment->comment_post_ID )
						) {
							$decoded['comment'] = get_post_type( $comment->comment_post_ID );
						}
						break;


					default:
						$decoded['type_match']      = [ 'post_type', 'post', 'page_type', 'page' ];
						$decoded['type_match_root'] = 'post_type';

						if (
							$post_id
							&& ( $post = get_post( $post_id ) )
							&& ! lct_is_wp_error( $post )
						) {
							$decoded['post_id']   = $post->ID;
							$decoded['post_type'] = get_post_type( $post_id );

							if (
								! empty( $_REQUEST['_acf_screen'] )
								&& $_REQUEST['_acf_screen'] === 'comment'
							) {
								$decoded['comment'] = $decoded['post_type'];
							}
						}
				}


				/**
				 * By default, we want to return any potential dupe for consideration.
				 */
				$possible_dupes = array_filter( $this->references['dupe_data'], function ( $arr ) use ( $field_name, $post_id, $decoded ) {
					//exclude non-matching field_names
					if (
						empty( $arr['field_name'] )
						|| empty( $field_name )
						|| $arr['field_name'] !== $field_name
					) {
						return false;
					}

					//No locations are set
					if ( empty( $arr['location'] ) ) {
						return true;
					}


					/**
					 * Hack
					 */
					if (
						( $tmp = afwp_acf_get_form_arr() )
						&& ! empty( $tmp['form_data'] )
						&& str_contains( $tmp['form_data'], 'milestone_id : ' )
						&& (
							empty( $decoded['post_type'] )
							|| $decoded['post_type'] !== 'afwp_comms'
						)
					) {
						$tmp                  = explode( 'milestone_id : ', $tmp['form_data'] );
						$decoded['post_id']   = (int) $tmp[1];
						$decoded['post_type'] = get_post_type( $tmp[1] );
					}


					/**
					 * Check match_rule of locations
					 * Returns true if the given field group's location rules match the given $args.
					 */
					//Loop through location groups.
					foreach ( $arr['location'] as $group ) {
						//ignore group if no rules.
						if ( empty( $group ) ) {
							continue;
						}

						//Loop rules and determine if all rules match.
						$match_group = true;
						foreach ( $group as $rule ) {
							if ( ! acf_match_location_rule( $rule, $decoded, $arr['group_key'] ) ) {
								$match_group = false;
								break;
							}
						}

						//If this group matches, show the field group.
						if ( $match_group ) {
							return true;
						}
					}


					return false; //Return default.
				} );


				if (
					empty( $possible_dupes )
					&& ! empty( $this->references['clones'] )
				) {
					$possible_dupes = array_filter( $this->references['clones'], function ( $arr ) use ( $field_name, $post_id, $decoded ) {
						//Clone match
						$clone_match = false;

						foreach ( $arr['clone'] as $clone ) {
							if (
								acf_is_field_key( $clone )
								&& ( $tmp = afwp_acf_get_field_object( $clone, false, false, false ) )
								&& (
									(
										! empty( $tmp['_name'] )
										&& $tmp['_name'] === $field_name
									)
									|| (
										! empty( $tmp['name'] )
										&& $tmp['name'] === $field_name
									)
								)
							) {
								if ( ! empty( $tmp['_name'] ) ) {
									$arr['field_name'] = $tmp['_name'];
								} else {
									$arr['field_name'] = $tmp['name'];
								}
								$clone_match = true;
								break;
							}
						}

						if ( ! $clone_match ) {
							return false;
						}


						//exclude non-matching field_names
						if (
							empty( $arr['field_name'] )
							|| empty( $field_name )
							|| $arr['field_name'] !== $field_name
							|| $arr['group_key'] === 'group_57ebe5359fdd9'
						) {
							return false;
						}

						//No locations are set
						if ( empty( $arr['location'] ) ) {
							return true;
						}


						/**
						 * Check match_rule of locations
						 * Returns true if the given field group's location rules match the given $args.
						 */
						//Loop through location groups.
						foreach ( $arr['location'] as $group ) {
							//ignore group if no rules.
							if ( empty( $group ) ) {
								continue;
							}

							//Loop rules and determine if all rules match.
							$match_group = true;
							foreach ( $group as $rule ) {
								if ( ! acf_match_location_rule( $rule, $decoded, $arr['group_key'] ) ) {
									$match_group = false;
									break;
								}
							}

							//If this group matches, show the field group.
							if ( $match_group ) {
								return true;
							}
						}


						return false; //Return default.
					} );


					if ( count( $possible_dupes ) === 1 ) {
						foreach ( $possible_dupes as $possible_dupe ) {
							foreach ( $possible_dupe['clone'] as $clone ) {
								if (
									acf_is_field_key( $clone )
									&& ( $tmp = afwp_acf_get_field_object( $clone, false, false, false ) )
									&& (
										(
											! empty( $tmp['_name'] )
											&& $tmp['_name'] === $field_name
										)
										|| (
											! empty( $tmp['name'] )
											&& $tmp['name'] === $field_name
										)
									)
								) {
									return $clone;
								}
							}
						}
					} else {
						$possible_dupes = [];
					}
				}


				/**
				 * We got the reference!!!
				 */
				if ( count( $possible_dupes ) === 1 ) {
					$reference = array_key_first( $possible_dupes );
					$this->save_accessed_reference( $reference, $field_name, $post_id );


					/**
					 * Too Many Keys
					 */
				} elseif ( count( $possible_dupes ) > 1 ) {
					$debug = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 6 );
					lct_debug_to_error_log( 'Too Many references for ACF field: ' . $field_name . ' :: ' . $debug[5]['file'] . ':' . $debug[5]['line'] );


					/**
					 * Not found in local fields
					 */
				} else {
					//$debug = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 6 );
					//lct_debug_to_error_log( 'Check reference for ACF field: ' . $field_name . ' :: ' . $debug[5]['file'] . ':' . $debug[5]['line'] );
				}
			}
		}


		/**
		 * If reference is still missing let us know
		 */
		if (
			$reference === null
			//&& lct_is_dev()
			&& apply_filters( 'lct/acf_loaded/load_reference/show_error_log', true, $field_name )
		) {
			$debug = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 6 );
			lct_debug_to_error_log( 'Missing reference for ACF field: ' . $field_name . ' :: ' . $debug[5]['file'] . ':' . $debug[5]['line'] );
		}


		return $reference;
	}


	/**
	 * Don't check some of our bad fields
	 *
	 * @param bool   $show_error
	 * @param string $field_name
	 *
	 * @return bool
	 * @date     2024.01.03
	 * @since    2023.04
	 * @verified 2024.01.03
	 */
	function show_error_log( $show_error, $field_name )
	{
		if ( $show_error ) {
			$dont_show_error = [
				'lct:::is_allowed_login',
				'lct:::is_allowed_role',
				'lct:::is_allowed_cap',
			];


			foreach ( $dont_show_error as $dont ) {
				if ( str_starts_with( $field_name, $dont ) ) {
					return false;
				}
			}
		}


		return $show_error;
	}


	/**
	 * Add any DB group fiends to the key_reference store
	 *
	 * @param array $field_group
	 *
	 * @return array
	 * @since    2019.2
	 * @verified 2023.08.31
	 */
	function load_field_group( $field_group )
	{
		if (
			lct_get_later( __FUNCTION__, 'running' )
			|| acf_is_local_field_group( $field_group['key'] )
			|| acf_get_store( 'field-groups' )->get( $field_group['key'] ) !== null
		) {
			return $field_group;
		}


		lct_update_later( __FUNCTION__, true, 'running' );
		acf_disable_filter( 'clone' );


		if ( $fields = acf_get_fields( $field_group['key'] ) ) {
			$this->prepare_fields_for_import( $fields );
		}


		acf_enable_filter( 'clone' );
		lct_update_later( __FUNCTION__, null, 'running' );


		return $field_group;
	}
}
