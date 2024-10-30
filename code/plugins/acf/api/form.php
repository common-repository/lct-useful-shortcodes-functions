<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * We are wrapping the acf_form() function because they do not provide any hooks for us to customize programmatically
 * * = No processing, No Default
 * ** = Processing occurs, No Default Set
 * *** = Maybe some processing occurs, Default is Set
 * ======
 * $a = [
 ** 'id'                          => 'acf-form', //alias to form_attributes['id']
 *** 'post_id'                     => false, //OR $post_id OR new_post
 ** 'new_post'                     => false, //OR [
 * =>                                  'post_type'   => 'post',
 * =>                                  'post_status' => 'draft',
 * =>                              ] AND MORE... See wp_insert_post() for available parameters
 ** 'field_groups'                 => false,
 ** 'fields'                       => false,
 * 'post_title'                    => false,
 * 'post_content'                  => false,
 ** 'form'                         => true,
 *** 'form_attributes'             => [
 * =>                                  'id'     => $a['id'],
 * =>                                  'class'  => 'acf-form',
 * =>                                  'action' => '',
 * =>                                  'method' => 'post',
 * =>                              ],
 * 'return'                        => add_query_arg( 'updated', 'true', acf_get_current_url() ),
 * 'html_before_fields'            => '',
 * 'html_after_fields'             => '',
 * 'submit_value'                  => __( "Update", 'acf' ),
 * 'updated_message'               => __( "Post updated", 'acf' ),
 * 'label_placement'               => null, //top (default) || left
 * 'instruction_placement'         => null, //label (default) || field
 * 'field_el'                      => 'div',
 * 'uploader'                      => 'wp',
 * 'honeypot'                      => true,
 * 'html_updated_message'          => '<div id="message" class="updated"><p>%s</p></div>',
 * 'html_submit_button'            => '<input type="submit" class="acf-button button button-primary button-large" value="%s" />',
 * 'html_submit_spinner'           => '<span class="acf-spinner"></span>',
 * 'kses'                          => true,
 * //CUSTOM
 ** _form_data_lct                 => [
 ** =>                                 'wp_api'                         => false //submit the form to an API endpoint
 ** =>                                 'wp_api_submit_ready'            => true //Used to check whether the form is ready to be submitted or not
 ** =>                                 'wp_api_route'                   => null //route path of the API endpoint
 ** =>                                 'wp_api_response_container'      => null //the id of the response container
 ** =>                                 'print_response_container'       => true //bool || below //Print the response container with the form
 ** =>                                 'form_container'                 => null //the id of the form container
 ** =>                                 'empty_form_container_on_submit' => true //the id of the form container
 ** =>                                 'save_now'                       => false //bool
 ** =>                                 'save_sess'                      => false //bool
 * =>                              ],
 *** 'lct_echo_form'               => true,
 *** 'lct_access'                  => null, //TODO: cs - Need to improve doc on this - 01/15/2017 07:58 PM
 ** 'lct_edit'                     => null, //TODO: cs - Need to improve doc on this - 01/15/2017 07:58 PM
 ** 'lct_view'                     => null, //TODO: cs - Need to improve doc on this - 01/15/2017 07:58 PM
 *** 'lct_default_value'           => null, //TODO: cs - Need to improve doc on this - 8/13/2019 8:33 PM
 *** 'lct_hide_submit'             => null, //hides the button when we don't need it, used to be called: lct_form_show_button_hide
 *** 'lct_form_div'                => 'form',
 *** 'new_post_type'               => alias to new_post['post_type'],
 *** 'new_post_status'             => alias to new_post['post_status'],
 *** 'form_class'                  => alias to form_attributes['class'],
 *** 'form_action'                 => alias to form_attributes['action'],
 *** 'form_method'                 => alias to form_attributes['method'],
 *** 'form_data'                   => alias to form_attributes['data'], //Format: key : value :: key2 : value2; Don't use this if possible; //TODO: cs - Need to get this removed - 1/17/2022 4:40 PM
 *** 'instant'                     => false //force the field(s) to save to the DB instantly via ajax
 *** 'save_now'                    => false //force the field(s) to save to the DB instantly via ajax
 *** 'save_sess'                   => false //force the field(s) to save to the DB instantly via ajax
 * //CUSTOM - Field atts
 * 'lct_viewonly'                  => null
 * 'lct_roles_n_caps'              => null
 * 'lct_roles_n_caps_viewonly'     => null
 *** 'lct_pdf_view'                => true //true, false, hide_form (only, show on the PDF)
 *** 'lct_pdf_layout'              => '{value}' //{value}, {label}, {instructions}
 *
 * @param $a
 *
 * @return bool|string
 * @since    7.49
 * @verified 2024.04.25
 */
function lct_acf_form2( $a )
{
	/**
	 * CUSTOM
	 * defaults
	 */
	$a = wp_parse_args(
		$a,
		[
			'r'                     => '',
			'post_id'               => null,
			'form_attributes'       => [
				'class'  => 'acf-form',
				'action' => '',
				'method' => 'post',
			],
			'lct_echo_form'         => true,
			'lct_access'            => null,
			'lct_default_value'     => null,
			'lct_hide_submit'       => null,
			'lct_form_div'          => 'form',
			'new_post_type'         => null,
			'new_post_status'       => null,
			'form_class'            => null,
			'form_action'           => null,
			'form_method'           => null,
			'form_data'             => null,
			'label_placement'       => null,
			'instruction_placement' => null,
			'instant'               => false,
			'save_now'              => false,
			'save_sess'             => false,
			'lct_pdf_view'          => true, //true, false, hide_form (only, show on the PDF)
			'lct_pdf_layout'        => '{value}', //{value}, {label}, {instructions}
		]
	);


	/**
	 * Pre-Filter $args
	 */
	$a = apply_filters( 'lct/acf_form/pre_args', $a );


	/**
	 * CUSTOM
	 * 'lct_echo_form'
	 */
	$a['lct_echo_form'] = filter_var( $a['lct_echo_form'], FILTER_VALIDATE_BOOLEAN );


	/**
	 * Set id, aka id of the form element
	 */
	if ( ! isset( $a['id'] ) ) {
		$a['id'] = lct_rand( 'lct_acf_form_rand_' );
	}


	/**
	 * Set new_post
	 */
	if ( isset( $a['new_post'] ) ) {
		$a['post_id'] = 'new_post';


		if ( ! is_array( $a['new_post'] ) ) {
			$a['new_post'] = [];
		}


		/**
		 * #2
		 * @date     0.0
		 * @since    7.49
		 * @verified 2021.08.27
		 */
		do_action( 'lct/acf/new_post' );
	}


	if ( $a['post_id'] === 'new_post' ) {
		/**
		 * Set new_post_type
		 */
		if (
			! isset( $a['new_post']['post_type'] )
			&& $a['new_post_type']
		) {
			$a['new_post']['post_type'] = $a['new_post_type'];
		}


		/**
		 * Set new_post_status
		 */
		if (
			! isset( $a['new_post']['post_status'] )
			&& $a['new_post_status']
		) {
			$a['new_post']['post_status'] = $a['new_post_status'];


			/**
			 * Set the post_status to 'publish' by default
			 * //TODO: cs - We may need to filter this in the future - 5/9/2017 2:07 PM
			 */
		} elseif ( ! isset( $a['new_post']['post_status'] ) ) {
			$a['new_post']['post_status'] = 'publish';
		}


		/**
		 * add a new_post class
		 */
		if ( ! isset( $a['form_class'] ) ) {
			$a['form_class'] = '';
		}


		$a['form_class'] .= ' ' . 'lct_acf_new_post';


		//sets the form to return to the newly created post after it is saved
		$a['return'] = '%post_url%';
	}


	unset( $a['new_post_type'] );
	unset( $a['new_post_status'] );


	/**
	 * Set field_groups list into an array
	 */
	if (
		isset( $a['field_groups'] )
		&& ! is_array( $a['field_groups'] )
	) {
		$a['field_groups'] = explode( ',', $a['field_groups'] );
	}


	/**
	 * Get group details
	 */
	if ( ! empty( $a['field_groups'] ) ) {
		$a['field_groups'] = array_values( $a['field_groups'] );
		$tmp               = reset( $a['field_groups'] );


		if ( ( $group_obj = acf_get_field_group( $tmp ) ) ) {
			if (
				$a['label_placement'] === null
				&& ! empty( $group_obj['label_placement'] )
			) {
				$a['label_placement'] = $group_obj['label_placement'];
			}


			if (
				$a['instruction_placement'] === null
				&& ! empty( $group_obj['instruction_placement'] )
			) {
				$a['instruction_placement'] = $group_obj['instruction_placement'];
			}
		}
	}


	/**
	 * Set fields list into an array
	 */
	if (
		isset( $a['fields'] )
		&& ! is_array( $a['fields'] )
	) {
		$a['fields'] = explode( ',', $a['fields'] );


		if ( count( $a['fields'] ) === 1 ) {
			$a['form_class'] .= ' ' . 'lct_acf_single_field_form';
		}
	}


	/**
	 * label_placement
	 */
	if ( empty( $a['label_placement'] ) ) {
		$a['label_placement'] = 'top';
	}


	/**
	 * instruction_placement
	 */
	if ( empty( $a['instruction_placement'] ) ) {
		$a['instruction_placement'] = 'label';
	}


	/**
	 * form
	 */
	if ( isset( $a['form'] ) ) {
		$a['form'] = filter_var( $a['form'], FILTER_VALIDATE_BOOLEAN );
	}


	/**
	 * CUSTOM
	 * Set the form_attributes->class
	 */
	if ( $a['form_class'] ) {
		$a['form_attributes']['class'] = 'acf-form ' . $a['form_class'];


		//for backwards compatibility
		if (
			! $a['save_now']
			&& ! $a['save_sess']
			&& strpos( $a['form_class'], 'lct_instant' ) !== false
		) { //only if class contains LCT instant
			$a['instant'] = true;


			//for backwards compatibility :: Hide the submit button
			if ( strpos( $a['form_attributes']['class'], 'show_submit' ) === false ) { //only if class does NOT contain show_submit
				if ( $a['lct_hide_submit'] === null ) {
					$a['lct_hide_submit'] = true;
				}
			} elseif ( strpos( $a['form_attributes']['class'], 'show_submit' ) !== false ) { //only if class does contain show_submit
				$a['lct_hide_submit'] = false;
			}
		}
	}


	/**
	 * CUSTOM
	 * Set the form_attributes->action
	 */
	if ( $a['form_action'] ) {
		$a['form_attributes']['action'] = $a['form_action'];
	}


	/**
	 * CUSTOM
	 * Set the form_attributes->method
	 */
	if ( $a['form_method'] ) {
		$a['form_attributes']['method'] = $a['form_method'];
	}


	/**
	 * CUSTOM
	 * Set the form_attributes->data
	 * //TODO: cs - Need to get this removed - 1/17/2022 4:40 PM
	 */
	if (
		$a['form_data']
		&& ( $form_data = explode( ' :: ', $a['form_data'] ) )
		&& ! empty( $form_data )
	) {
		foreach ( $form_data as $kv ) {
			if (
				( $single_data = explode( ' : ', $kv ) )
				&& ! empty( $single_data[0] )
				&& isset( $single_data[1] )
			) {
				$a['form_attributes'][ 'data-' . $single_data[0] ] = $single_data[1];
			}
		}
	}

	/**
	 * CUSTOM
	 * Set the instant
	 * Set the save_now
	 * Set the save_sess
	 */
	$a['instant']   = filter_var( $a['instant'], FILTER_VALIDATE_BOOLEAN );
	$a['save_now']  = filter_var( $a['save_now'], FILTER_VALIDATE_BOOLEAN );
	$a['save_sess'] = filter_var( $a['save_sess'], FILTER_VALIDATE_BOOLEAN );


	if (
		$a['save_now']
		|| $a['save_sess']
		|| $a['instant']
	) {
		$a['honeypot'] = false;
		if ( ! $a['save_now'] && ! $a['save_sess'] ) {
			$a['form_attributes']['class'] .= ' ' . 'lct_instant';
		}


		//Hide the submit button
		if ( $a['lct_hide_submit'] === null ) {
			$a['form']            = false;
			$a['lct_hide_submit'] = true;
		}
	}


	/**
	 * CUSTOM
	 * Set the lct_hide_submit
	 */
	$a['lct_hide_submit'] = filter_var( $a['lct_hide_submit'], FILTER_VALIDATE_BOOLEAN );


	if ( $a['lct_hide_submit'] === false ) {
		$a['form_attributes']['class'] .= ' show_submit';


		$a['form'] = true;
	}


	/**
	 * CUSTOM
	 * Goes before FILTERS, so we don't waste our time if the user is not allowed to see it
	 * Check if there are any user access restrictions for this form
	 */
	if (
		(
			( $tmp = 'lct_edit' )
			&& isset( $a[ $tmp ] )
			&& $a[ $tmp ] !== null
		)
		|| (
			( $tmp = 'lct_view' )
			&& isset( $a[ $tmp ] )
			&& $a[ $tmp ] !== null
		)
	) {
		$current_user_can_edit_or_view = false;


		if (
			$a['lct_edit']
			&& apply_filters( 'lct/direct_current_user_can_edit', true, $a['lct_edit'] )
		) {
			$current_user_can_edit_or_view = true;
		}


		/**
		 * Show a read only version if they are allowed to
		 */
		if (
			$a['lct_view']
			&& ! $current_user_can_edit_or_view
			&& apply_filters( 'lct/direct_current_user_can_view', false, $a['lct_view'] )
		) {
			if ( $a['post_id'] != 'new_post' ) {
				$current_user_can_edit_or_view = true;
			}


			if ( ! empty( $a['fields'] ) ) {
				foreach ( $a['fields'] as $field ) {
					lct_append_later( 'acf_viewonly_fields', $field );
				}
			} elseif ( ! empty( $a['field_groups'] ) ) {
				foreach ( $a['field_groups'] as $field ) {
					lct_append_later( 'acf_viewonly_field_groups', $field );
				}
			}


			/**
			 * add an acf_viewonly class
			 */
			if ( ! isset( $a['form_attributes']['class'] ) ) {
				$a['form_attributes']['class'] = '';
			}


			$a['form_attributes']['class'] .= ' ' . 'lct_acf_viewonly';


			//TODO: cs - Add a viewonly save for a request without fields or field_groups - 4/29/2017 3:52 PM
		}


		/**
		 * Return nothing if they don't need to see it
		 */
		if ( ! $current_user_can_edit_or_view ) {
			return false;
		}


		/**
		 * Older way of doing things that may still be in action
		 * If you are using it replace with the method above
		 */
	} elseif ( $a['lct_access'] !== null ) {
		if ( ! apply_filters( 'lct/current_user_can_access', true, $a['lct_access'] ) ) {
			/**
			 * Show a read only version if they are allowed to
			 */
			if ( apply_filters( 'lct/current_user_can_view', false, $a['lct_access'] ) ) {
				if ( ! empty( $a['fields'] ) ) {
					foreach ( $a['fields'] as $field ) {
						lct_append_later( 'acf_viewonly_fields', $field );
					}
				} elseif ( ! empty( $a['field_groups'] ) ) {
					foreach ( $a['field_groups'] as $field ) {
						lct_append_later( 'acf_viewonly_field_groups', $field );
					}
				}


				//TODO: cs - Add a viewonly save for a request without fields or field_groups - 4/29/2017 3:52 PM


				/**
				 * Return nothing if they don't need to see it
				 */
			} else {
				return false;
			}
		}
	}


	/**
	 * CUSTOM
	 * _form_data_lct
	 */
	if ( ! isset( $a['_form_data_lct'] ) ) {
		$a['_form_data_lct'] = [];
	}
	$a['_form_data_lct'] = afwp_acf_maybe_json_decode( $a['_form_data_lct'] );


	/**
	 * ACF API Form Variables
	 */
	if ( isset( $a['_form_data_lct']['wp_api'] ) ) {
		/**
		 * wp_api
		 */
		$a['_form_data_lct']['wp_api'] = filter_var( $a['_form_data_lct']['wp_api'], FILTER_VALIDATE_BOOLEAN );


		/**
		 * wp_api_submit_ready
		 */
		if ( ! isset( $a['_form_data_lct']['wp_api_submit_ready'] ) ) {
			$a['_form_data_lct']['wp_api_submit_ready'] = true;
		}

		$a['_form_data_lct']['wp_api_submit_ready'] = filter_var( $a['_form_data_lct']['wp_api_submit_ready'], FILTER_VALIDATE_BOOLEAN );


		/**
		 * print_response_container
		 */
		if ( ! isset( $a['_form_data_lct']['print_response_container'] ) ) {
			$a['_form_data_lct']['print_response_container'] = true;
		} elseif ( $a['_form_data_lct']['print_response_container'] !== 'below' ) {
			$a['_form_data_lct']['print_response_container'] = filter_var( $a['_form_data_lct']['print_response_container'], FILTER_VALIDATE_BOOLEAN );
		}


		/**
		 * empty_form_container_on_submit
		 */
		if ( ! isset( $a['_form_data_lct']['empty_form_container_on_submit'] ) ) {
			$a['_form_data_lct']['empty_form_container_on_submit'] = true;
		}

		$a['_form_data_lct']['empty_form_container_on_submit'] = filter_var( $a['_form_data_lct']['empty_form_container_on_submit'], FILTER_VALIDATE_BOOLEAN );
	}


	/**
	 * save_now Variables
	 */
	if ( $a['save_now'] ) {
		$a['_form_data_lct']['save_now'] = true;
	}


	/**
	 * save_sess Variables
	 */
	if ( $a['save_sess'] ) {
		$a['_form_data_lct']['save_sess'] = true;
	}


	/**
	 * instruction_placement Variables
	 */
	if (
		! empty( $a['instruction_placement'] )
		&& empty( $a['_form_data_lct']['instruction_placement'] )
	) {
		$a['_form_data_lct']['instruction_placement'] = $a['instruction_placement'];
	}


	/**
	 * FILTERS
	 */


	/**
	 * Filter post_id
	 */
	$a['post_id'] = apply_filters( 'lct/acf_form/post_id', $a['post_id'], $a );


	/**
	 * Set post_id; if needed
	 */
	if ( isset( $a['post_id'] ) ) {
		if ( ! empty( $a['fields'] ) ) {
			$arr = [];


			foreach ( $a['fields'] as $field ) {
				$arr[ $field ] = [ 'post_id' => $a['post_id'] ];
			}


			lct_update_later( 'acf_post_id_fields', $arr );
		} elseif ( ! empty( $a['field_groups'] ) ) {
			$arr = [];


			foreach ( $a['field_groups'] as $field ) {
				$arr[ $field ] = [ 'post_id' => $a['post_id'] ];
			}


			lct_update_later( 'acf_post_id_field_groups', $arr );
		}


		//TODO: cs - Add a save for a request without fields or field_groups - 4/29/2017 3:52 PM
	}


	/**
	 * Check for conditional fields
	 * Add them to the form if they exist
	 */
	if ( ! empty( $a['fields'] ) ) {
		AFWP_Acf_Core()->set_acf_form( $a );
		$conditional_fields = [];


		foreach ( $a['fields'] as $field ) {
			$field_obj = get_field_object( $field, $a['post_id'], false, false );
			if ( ! empty( $field_obj['name'] ) ) {
				acf_flush_field_cache( $field_obj );
			}


			if ( empty( $field_obj['conditional_logic'] ) ) {
				continue;
			}


			foreach ( $field_obj['conditional_logic'] as $or_and_s ) {
				foreach ( $or_and_s as $or_and ) {
					if (
						! empty( $or_and['field'] )
						&& ( $con_field_key = $or_and['field'] )
						&& ( $conditional_field_obj = get_field_object( $con_field_key, $a['post_id'], false, false ) )
						&& ! in_array( $conditional_field_obj['_name'], $a['fields'] )
						&& ! in_array( $conditional_field_obj['key'], $a['fields'] )
					) {
						$conditional_fields[ $con_field_key ] = $con_field_key;
					}
				}
			}
		}


		$a['fields'] = array_merge( $a['fields'], $conditional_fields );
		AFWP_Acf_Core()->unset_acf_form();
	}


	/**
	 * Filter $args
	 */
	$a = apply_filters( 'lct/acf_form/args', $a );


	/**
	 * Display Values only
	 */
	if ( ( $tmp = filter_var( $a['lct_pdf_view'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) ) === null ) {
		$tmp = $a['lct_pdf_view'];
	}
	$a['lct_pdf_view'] = $tmp;
	if (
		lct_is_display_form_or_pdf()
		|| (
			! lct_is_display_form_or_pdf()
			&& $a['lct_pdf_view'] === 'layout_form'
		)
	) {
		$a['r'] = lct_instances()->acf_form->process_pdf_fields( $a );


		/**
		 * PDF Only, hide on the form
		 */
	} elseif (
		! lct_is_display_form_or_pdf()
		&& $a['lct_pdf_view'] === 'hide_form'
	) {
		$a['r'] = null;


		/**
		 * Normal Form Loading
		 */
	} else {
		/**
		 * go and get the acf_form()
		 */
		if (
			isset( $a['form'] )
			&& ! empty( $a['lct_hide_submit'] )
			&& $a['form'] === false
			&& $a['lct_hide_submit'] === true
		) {
			$display_form_here = true;
		} else {
			$display_form_here = false;
		}


		if ( $a['lct_echo_form'] === false ) {
			ob_start();
		}


		/**
		 * #2
		 * @date     0.0
		 * @since    7.49
		 * @verified 2021.08.27
		 */
		do_action( 'lct/acf_form/before_acf_form', $a );


		/**
		 * API Response Container
		 */
		if (
			! empty( $a['_form_data_lct']['print_response_container'] )
			&& $a['_form_data_lct']['print_response_container'] !== 'below'
			&& ! empty( $a['_form_data_lct']['wp_api_response_container'] )
		) {
			echo sprintf( '<div id="%s"></div>', $a['_form_data_lct']['wp_api_response_container'] );
		}


		/**
		 * Form Container :: beginning
		 */
		if ( ! empty( $a['_form_data_lct']['form_container'] ) ) {
			echo sprintf( '<div id="%s">', $a['_form_data_lct']['form_container'] );
		}


		/**
		 * display form :: beginning
		 */
		if ( $display_form_here ) {
			$display_form_attributes = wp_parse_args( $a['form_attributes'], [
				'id'    => $a['id'],
				'class' => 'acf-form',
			] );


			unset( $display_form_attributes['action'] );
			unset( $display_form_attributes['method'] );


			echo sprintf( '<%s %s>', $a['lct_form_div'], acf_esc_attrs( $display_form_attributes ) );
		}


		/**
		 * lct_default_value :: beginning
		 */
		$is_default_value = false;


		if (
			$a['lct_default_value'] !== null
			&& isset( $a['fields'] )
			&& count( $a['fields'] ) === 1
		) {
			$is_default_value = true;
			lct_update_setting( 'lct_acf_form2/lct_default_value', $a['lct_default_value'] );
			lct_update_setting( 'lct_acf_form2/lct_default_value/selector', $a['fields'][0] );

			add_filter( 'acf/load_field/name=' . $a['fields'][0], 'lct_acf_default_value' );

			add_filter( 'acf/pre_render_fields', 'lct_acf_default_value_pre_render' );
		}


		acf_form( $a );


		/**
		 * lct_default_value :: end
		 */
		if ( $is_default_value ) {
			lct_update_setting( 'lct_acf_form2/lct_default_value', null );
			lct_update_setting( 'lct_acf_form2/lct_default_value/selector', null );


			remove_filter( 'acf/load_field/name=' . $a['fields'][0], 'lct_acf_default_value' );

			remove_filter( 'acf/pre_render_fields', 'lct_acf_default_value_pre_render' );
		}


		/**
		 * display form :: end
		 */
		if ( $display_form_here ) {
			echo '</' . $a['lct_form_div'] . '>';
		}


		/**
		 * Form Container :: end
		 */
		if ( ! empty( $a['_form_data_lct']['form_container'] ) ) {
			echo '</div>';
		}


		/**
		 * API Response Container :: below
		 */
		if (
			! empty( $a['_form_data_lct']['print_response_container'] )
			&& $a['_form_data_lct']['print_response_container'] === 'below'
			&& ! empty( $a['_form_data_lct']['wp_api_response_container'] )
		) {
			echo sprintf( '<div id="%s"></div>', $a['_form_data_lct']['wp_api_response_container'] );
		}


		if ( $a['lct_echo_form'] === false ) {
			$a['r'] = do_shortcode( ob_get_clean() );
		}


		/**
		 * Action Hook
		 *
		 * @date     2022.11.28
		 * @since    2022.11
		 * @verified 2022.11.28
		 */
		do_action( 'lct/acf_form/after_acf_form', $a );
	}


	return $a['r'];
}


/**
 * Set the default value
 *
 * @param array $field
 *
 * @return array
 * @date       2022.02.08
 * @since      2022.1
 * @verified   2022.02.08
 */
function lct_acf_default_value( $field )
{
	if ( isset( $field['default_value'] ) ) {
		$field['default_value'] = lct_get_setting( 'lct_acf_form2/lct_default_value' );
	}


	return $field;
}


/**
 * Set the default value
 *
 * @param array $fields
 *
 * @unused     param int $post_id
 * @return array
 * @date       2022.02.09
 * @since      2022.1
 * @verified   2022.02.09
 */
function lct_acf_default_value_pre_render( $fields )
{
	if ( $selector = lct_get_setting( 'lct_acf_form2/lct_default_value/selector' ) ) {
		foreach ( $fields as $k => $field ) {
			if (
				$field['_name'] === $selector
				&& isset( $field['default_value'] )
			) {
				$fields[ $k ]['default_value'] = lct_get_setting( 'lct_acf_form2/lct_default_value' );
			}
		}
	}


	return $fields;
}


/**
 * Use a raw ACF value and get it back in its formatted value
 *
 * @param      $raw_value
 * @param null $post_id
 * @param      $field
 * @param null $class
 * @param bool $clear_cache
 *
 * @return string
 * @since    2017.83
 * @verified 2018.08.27
 */
function lct_acf_display_form_format_value( $raw_value, $post_id, $field, $class = null, $clear_cache = false )
{
	return lct_acf_format_value( $raw_value, $post_id, $field, $clear_cache, $class );
}


/**
 * Simple
 *
 * @param mixed  $raw_value
 * @param string $selector
 * @param int    $post_id
 *
 * @return mixed
 * @since        2019.17
 * @verified     2019.07.02
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_acf_format_value_from_selector( $raw_value, $selector, $post_id )
{
	if ( $field = get_field_object( $selector, $post_id, false, false ) ) {
		$raw_value = acf_format_value( $raw_value, $post_id, $field );
	}


	return $raw_value;
}


/**
 * Use a raw ACF value and get it back in its formatted value
 * //TODO: cs - Just stop using this - 9/11/2023 11:18 AM
 * //TODO: cs - Improve for clone field type - 10/26/2016 11:16 AM
 * //TODO: cs - Maybe tie into [lct_acf] - 9/28/2017 10:34 PM
 *
 * @param mixed    $raw_value
 * @param mixed    $post_id
 * @param array    $field
 * @param bool     $clear_cache ( before, after ) Use true for repeater fields
 * @param stdClass $class
 *
 * @return mixed
 * @since    2017.34
 * @verified 2022.09.21
 */
function lct_acf_format_value( $raw_value, $post_id, $field, $clear_cache = false, $class = null )
{
	if ( $raw_value === LCT_VALUE_EMPTY ) {
		return $raw_value;
	}


	/**
	 * Clear the cache, Usually needed for repeater sub_fields
	 */
	if (
		$clear_cache
		&& in_array( $clear_cache, [ 'both', 'before' ] )
	) {
		acf_flush_value_cache( $post_id, $field['name'] );
	}


	$post_id = $field['post_id'] = lct_get_field_post_id( $field, $post_id );
	if (
		$field['type'] === 'repeater'
		&& ! isset( $raw_value[0] )
	) {
		$value = acf_format_value( [ $raw_value ], $post_id, $field );
	} else {
		$value = acf_format_value( $raw_value, $post_id, $field );
	}


	/**
	 * Clear the cache, Usually needed for repeater sub_fields
	 */
	if (
		$clear_cache
		&& in_array( $clear_cache, [ 'both', 'after' ] )
	) {
		acf_flush_value_cache( $post_id, $field['name'] );
	}


	/**
	 * Check the conditional_logic
	 */
	if (
		lct_is_display_form_or_pdf()
		&& ! empty( $field['conditional_logic'] )
	) {
		echo lct_acf_hide_this( $field, $post_id );


		/**
		 * @date     0.0
		 * @since    7.14
		 * @verified 2021.08.30
		 */
		do_action( 'lct/acf/display_form/conditional_logic', $field );
	}


	/**
	 * Do some special things for certain field types
	 */
	$switch_type = $field['type'];
	if (
		$field['type'] === 'select'
		&& ! in_array( $field['_name'], [ zxzacf( 'value' ), zxzacf( 'value_old' ) ] )
		&& ( $tmp = get_field_object( $field['key'], $post_id, false, false ) )
	) {
		$switch_type = $tmp['type'];
	}


	switch ( $switch_type ) {
		/**
		 * checkbox
		 */
		case 'checkbox':
			if ( lct_is_display_form_or_pdf() ) {
				$value_reference   = get_field( $field['key'], $post_id, false );
				$only_show_checked = false;


				if (
					! empty( $field[ get_cnst( 'class_selector' ) ] )
					&& in_array( 'dompdf_only_show_checked', $field[ get_cnst( 'class_selector' ) ] )
				) {
					$only_show_checked = true;
				}


				if ( $only_show_checked ) {
					$value_array    = $value_reference;
					$checkbox_count = count( $value_reference );
				} else {
					$value_array    = $field['choices'];
					$checkbox_count = count( $field['choices'] );
				}


				if (
					! empty( $value_reference )
					|| ! $only_show_checked
				) {
					$column_number = null;


					if (
						! empty( $field['lct_class_selector'] )
						&& (
							in_array( 'dompdf_1_column', $field[ get_cnst( 'class_selector' ) ] )
							|| in_array( 'dompdf_2_column', $field[ get_cnst( 'class_selector' ) ] )
							|| in_array( 'dompdf_3_column', $field[ get_cnst( 'class_selector' ) ] )
							|| in_array( 'dompdf_4_column', $field[ get_cnst( 'class_selector' ) ] )
							|| in_array( 'dompdf_5_column', $field[ get_cnst( 'class_selector' ) ] )
						)
					) {
						if ( in_array( 'dompdf_1_column', $field[ get_cnst( 'class_selector' ) ] ) ) {
							$column_number = 1;
						}
						if ( in_array( 'dompdf_2_column', $field[ get_cnst( 'class_selector' ) ] ) ) {
							$column_number = 2;
						} elseif ( in_array( 'dompdf_3_column', $field[ get_cnst( 'class_selector' ) ] ) ) {
							$column_number = 3;
						} elseif ( in_array( 'dompdf_4_column', $field[ get_cnst( 'class_selector' ) ] ) ) {
							$column_number = 4;
						} elseif ( in_array( 'dompdf_5_column', $field[ get_cnst( 'class_selector' ) ] ) ) {
							$column_number = 5;
						}
					}


					$value                 = [];
					$width                 = '';
					$padding               = '0 1% 0 0';
					$column_count          = 0;
					$column_count_leftover = 0;


					if ( $column_number ) {
						$column_count          = ceil( $checkbox_count / $column_number );
						$column_count_leftover = $checkbox_count - ( floor( $checkbox_count / $column_number ) * $column_number );


						switch ( $column_number ) {
							case 1:
								$width   = '100';
								$padding = '0';
								break;


							case 2:
								$width = '49';
								break;


							case 3:
								$width = '32';
								break;


							case 4:
								$width = '24';
								break;


							case 5:
								$width = '19';
								break;


							default:
						}
					}


					$current_column         = 0;
					$current_column_count   = 0;
					$current_checkbox_count = 0;


					foreach ( $value_array as $tmp_key => $tmp_value ) {
						if (
							$width
							&& (
								! $current_checkbox_count
								|| $current_checkbox_count == $current_column_count
							)
						) {
							$value[] = sprintf( '<div class="%s" style="width: %s;padding: %s;"><p>', zxzu( 'acf_checkbox_column' ), $width . '%', $padding );

							$current_column ++;
							$current_column_count = $current_column_count + $column_count;


							if (
								$column_count_leftover
								&& $current_column > $column_count_leftover
							) {
								$current_column_count --;
							}
						}


						if ( $tmp_key ) {
							if ( $only_show_checked ) {
								$value[] = $tmp_value;
							} else {
								if (
									is_array( $value_reference )
									&& in_array( $tmp_key, $value_reference )
								) {
									$value[] = sprintf( '<span class="%s" style="color: %s;font-weight: %s;">%s</span>&nbsp;&nbsp;', zxzu( 'acf_yes' ), '#006400', 'bold', 'Yes' );
								} else {
									$value[] = sprintf( '<span class="%s" style="color: %s;font-weight: %s;padding-right: %s;">%s</span>&nbsp;&nbsp;', zxzu( 'acf_no' ), '#ff0000', 'normal', '6px', 'No' );
								}


								$value[] = $tmp_value;
							}


							$current_checkbox_count ++;


							if (
								$current_checkbox_count != $current_column_count
								&& $current_checkbox_count != $checkbox_count
							) {
								$value[] = '<br />';
							}
						}


						if (
							$width
							&& (
								! $current_checkbox_count
								|| $current_checkbox_count == $current_column_count
								|| $current_checkbox_count == $checkbox_count
							)
						) {
							$value[] = "</p></div>";
						}
					}


					$value[] = '<div class="clear"></div>';


					$value = lct_return( $value );
				} else {
					$value = 'No items were selected';
				}


				$value = apply_filters( 'lct/acf/display_form/type_checkbox/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    7.14
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/display_form/type_checkbox', $field );


				/**
				 * Default
				 */
			} else {
				$value = lct_acf_format_value_checkbox( $value, $field );
				$value = apply_filters( 'lct/acf/format_value/type_checkbox/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/format_value/type_checkbox', $field );
			}
			break;


		/**
		 * clone
		 */
		case 'clone':
			if ( lct_is_display_form_or_pdf() ) {
				if ( count( $field['clone'] ) <= 1 ) {
					$clone_field = get_field_object( $field['clone'][0], $post_id, false );
					$value       = acf_format_value( $clone_field['value'], $post_id, $clone_field );
				}


				$value = apply_filters( 'lct/acf/display_form/type_clone/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/display_form/type_clone', $field );
			}
			break;


		/**
		 * date_picker
		 */
		case 'date_picker':
			/**
			 * Display Form or PDF
			 */
			if ( lct_is_display_form_or_pdf() ) {
				$value = lct_acf_format_value_date_display_format( $value, $field );
				$value = apply_filters( 'lct/acf/display_form/type_date_picker/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    7.31
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/display_form/type_date_picker', $field );


				/**
				 * Default
				 */
			} else {
				$value = lct_acf_format_value_date_display_format( $value, $field );
				$value = apply_filters( 'lct/acf/format_value/type_date_picker/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/format_value/type_date_picker', $field );
			}
			break;


		/**
		 * date_time_picker
		 */
		case 'date_time_picker':
			/**
			 * Display Form or PDF
			 */
			if ( lct_is_display_form_or_pdf() ) {
				$value = lct_acf_format_value_date_display_format( $value, $field );
				$value = apply_filters( 'lct/acf/display_form/type_date_time_picker/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    7.31
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/display_form/type_date_time_picker', $field );


				/**
				 * Default
				 */
			} else {
				$value = lct_acf_format_value_date_display_format( $value, $field );
				$value = apply_filters( 'lct/acf/format_value/type_date_time_picker/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/format_value/type_date_time_picker', $field );
			}
			break;


		/**
		 * message
		 */
		case 'message':
			if ( lct_is_display_form_or_pdf() ) {
				/**
				 * Process any present shortcodes
				 */
				$value = lct_check_for_nested_shortcodes( $field['message'] );
				$value = do_shortcode( $value );


				//wptexturize (improves "quotes")
				$value = wptexturize( $value );


				//esc_html
				if ( $field['esc_html'] ) {
					$value = esc_html( $value );
				}


				//new lines
				if ( $field['new_lines'] == 'wpautop' ) {
					$value = wpautop( $value );
				} elseif ( $field['new_lines'] == 'br' ) {
					$value = lct_nl2br( $value );
				}


				$value = apply_filters( 'lct/acf/display_form/type_message/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    7.14
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/display_form/type_message', $field );
			}
			break;


		/**
		 * post_object
		 */
		case 'post_object':
			/**
			 * Display Form or PDF
			 */
			if ( lct_is_display_form_or_pdf() ) {
				$value = lct_acf_format_value_post_object( $value );
				$value = apply_filters( 'lct/acf/display_form/type_post_object/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/display_form/type_post_object', $field );


				/**
				 * Default
				 */
			} else {
				$value = lct_acf_format_value_post_object( $value );
				$value = apply_filters( 'lct/acf/format_value/type_post_object/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/format_value/type_post_object', $field );
			}
			break;


		/**
		 * radio
		 */
		case 'radio':
			/**
			 * Display Form or PDF
			 */
			if ( lct_is_display_form_or_pdf() ) {
				$value = lct_acf_format_value_radio_display_format( $value, $field );
				$value = apply_filters( 'lct/acf/display_form/type_radio/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/display_form/type_radio', $field );


				/**
				 * Default
				 */
			} else {
				$value = lct_acf_format_value_radio_display_format( $value, $field );
				$value = apply_filters( 'lct/acf/format_value/type_radio/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/format_value/type_radio', $field );
			}
			break;


		/**
		 * select
		 */
		case 'select':
			/**
			 * Display Form or PDF
			 */
			if ( lct_is_display_form_or_pdf() ) {
				$value = apply_filters( 'lct/acf/display_form/type_select/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    7.22
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/display_form/type_select', $field );


				/**
				 * Default
				 */
			} else {
				if ( is_array( $field['value'] ) ) {
					if ( ! empty( $field['value'] ) ) {
						$value = [];


						foreach ( $field['value'] as $key_part ) {
							if ( isset( $field['choices'][ $key_part ] ) ) {
								$value[] = $field['choices'][ $key_part ];
							}
						}


						$value = lct_return( $value, ', ' );
					}
				} else {
					$key = $field['value'];


					if ( isset( $field['choices'][ $key ] ) ) {
						$value = $field['choices'][ $key ];
					}
				}


				if ( empty( $value ) ) {
					$value = null;
				}


				$value = apply_filters( 'lct/acf/format_value/type_select/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/format_value/type_select', $field );
			}
			break;


		/**
		 * taxonomy
		 */
		case 'taxonomy':
			/**
			 * Display Form or PDF
			 */
			if ( lct_is_display_form_or_pdf() ) {
				$value = lct_acf_format_value_taxonomy( $value, $field );
				$value = apply_filters( 'lct/acf/display_form/type_taxonomy/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/display_form/type_taxonomy', $field );


				/**
				 * Default
				 */
			} else {
				$value = lct_acf_format_value_taxonomy( $value, $field );
				$value = apply_filters( 'lct/acf/format_value/type_taxonomy/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/format_value/type_taxonomy', $field );
			}
			break;


		/**
		 * time_picker
		 */
		case 'time_picker':
			/**
			 * Display Form or PDF
			 */
			if ( lct_is_display_form_or_pdf() ) {
				$value = lct_acf_format_value_date_display_format( $value, $field );
				$value = apply_filters( 'lct/acf/display_form/type_time_picker/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/display_form/type_time_picker', $field );


				/**
				 * Default
				 */
			} else {
				$value = lct_acf_format_value_date_display_format( $value, $field );
				$value = apply_filters( 'lct/acf/format_value/type_time_picker/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/format_value/type_time_picker', $field );
			}
			break;


		/**
		 * true_false
		 */
		case 'true_false':
			/**
			 * Display Form or PDF
			 */
			if ( lct_is_display_form_or_pdf() ) {
				$value = lct_acf_format_value_true_false_display_format( $value, $field );
				$value = apply_filters( 'lct/acf/display_form/type_true_false/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    7.14
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/display_form/type_true_false', $field );


				/**
				 * Default
				 */
			} else {
				$value = lct_acf_format_value_true_false_display_format( $value, $field );
				$value = apply_filters( 'lct/acf/format_value/type_true_false/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/format_value/type_true_false', $field );
			}
			break;


		/**
		 * user
		 */
		case 'user':
			/**
			 * Display Form or PDF
			 */
			if ( lct_is_display_form_or_pdf() ) {
				$value = lct_acf_format_value_user( $value, $field['return_format'] );
				$value = apply_filters( 'lct/acf/display_form/type_user/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/display_form/type_user', $field );


				/**
				 * Default
				 */
			} else {
				$return_format = 'id';
				if ( isset( $field['return_format'] ) ) {
					$return_format = $field['return_format'];
				}


				$value = lct_acf_format_value_user( $value, $return_format );
				$value = apply_filters( 'lct/acf/format_value/type_user/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/format_value/type_user', $field );
			}
			break;


		/**
		 * lct_zip_code
		 */
		case zxzu( 'zip_code' ):
			/**
			 * Display Form or PDF
			 */
			if ( lct_is_display_form_or_pdf() ) {
				$value = lct_acf_format_value_zip_code( $value );
				$value = apply_filters( 'lct/acf/display_form/type_zip_code/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/display_form/type_zip_code', $field );


				/**
				 * Default
				 */
			} else {
				$value = lct_acf_format_value_zip_code( $value );
				$value = apply_filters( 'lct/acf/format_value/type_zip_code/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/format_value/type_zip_code', $field );
			}
			break;


		/**
		 * Repeater
		 */
		case 'repeater':
			$value = '';


			/**
			 * @date     0.0
			 * @since    0.0
			 * @verified 2021.08.30
			 */
			do_action( 'lct/acf/format_value/type_repeater', $field );
			break;


		/**
		 * lct_section_header
		 */
		case zxzu( 'section_header' ):
			/**
			 * Display Form or PDF
			 */
			if ( lct_is_display_form_or_pdf() ) {
				$value = ' ';
				$value = apply_filters( 'lct/acf/display_form/type_section_header/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    2021.1
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/display_form/type_section_header', $field );


				/**
				 * Default
				 */
			} else {
				$value = apply_filters( 'lct/acf/format_value/type_section_header/value', $value, $field, $class );


				/**
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/format_value/type_section_header', $field );
			}
			break;


		/**
		 * default
		 */
		default:
			/**
			 * OBJECTS
			 */
			if ( is_object( $value ) ) {
				/**
				 * WP_Post
				 */
				if ( lct_is_a( $value, 'WP_Post' ) ) {
					$value = $value->post_title;


					/**
					 * WP_Term
					 */
				} elseif ( lct_is_a( $value, 'WP_Term' ) ) {
					$value = $value->name;


					/**
					 * everything else
					 */
				} else {
					$value = 'Something went wrong with OBJECT. Contact Admin.';
				}
			}


			if ( lct_is_display_form_or_pdf() ) {
				$value = apply_filters( 'lct/acf/display_form/type_default/value', $value, $field, $class, $post_id );


				/**
				 * @date     0.0
				 * @since    7.14
				 * @verified 2021.08.30
				 */
				do_action( 'lct/acf/display_form/type_default', $field );
			}
	}


	/**
	 * Set to default empty value
	 */
	$value = lct_set_empty_value( $value );


	/**
	 * Process any present shortcodes
	 */
	if ( ! is_array( $value ) ) {
		$value = do_shortcode( $value );
	} else {
		$value = print_r( $value, true );
	}


	return $value;
}


/**
 * Default Processor for formatting value of display_format, date_picker, date_time_picker, time_picker
 *
 * @param string $value
 * @param array  $field
 *
 * @return string
 * @since    2017.84
 * @verified 2022.03.08
 */
function lct_acf_format_value_date_display_format( $value, $field )
{
	if ( ! empty( $field['display_format'] ) ) {
		$value = acf_format_date( $value, $field['display_format'] );
	} elseif (
		! empty( $field['post_id'] )
		&& strpos( $field['post_id'], 'comment_' ) !== false
		&& ! empty( $field['comment_post_field']['display_format'] )
	) {
		$value = acf_format_date( $value, $field['comment_post_field']['display_format'] );
	}


	return $value;
}


/**
 * Default Processor for formatting value of true_false
 *
 * @param string|bool $value
 * @param array       $field
 *
 * @return string
 * @since    2019.1
 * @verified 2022.02.15
 */
function lct_acf_format_value_true_false_display_format( $value, $field )
{
	$answer = sprintf( '<span class="%s" style="color: %s;font-weight: %s;">%s</span>', zxzu( 'acf_no' ), '#ff0000', 'normal', 'No' );


	if ( $value ) {
		$answer = sprintf( '<span class="%s" style="color:%s;font-weight: %s;">%s</span>', zxzu( 'acf_yes' ), '#006400', 'bold', 'Yes' );
	}


	if (
		isset( $field['message'] )
		&& ( $message = wp_strip_all_tags( $field['message'] ) )
	) {
		$value = $answer . ' &mdash; ' . $message;
	} else {
		$value = $answer;
	}


	return $value;
}


/**
 * Default Processor for formatting value of radio
 *
 * @param $value
 * @param $field
 *
 * @return mixed
 * @since    2019.1
 * @verified 2022.09.21
 */
function lct_acf_format_value_radio_display_format( $value, $field )
{
	if ( isset( $field['choices'][ $value ] ) && $field['choices'][ $value ] === 'No' ) {
		$value = sprintf( '<span class="%s" style="color: %s;font-weight: %s;">%s</span>', zxzu( 'acf_no' ), '#ff0000', 'normal', 'No' );
	} elseif ( isset( $field['choices'][ $value ] ) && $field['choices'][ $value ] === 'Yes' ) {
		$value = sprintf( '<span class="%s" style="color: %s;font-weight: %s;">%s</span>', zxzu( 'acf_yes' ), '#006400', 'bold', 'Yes' );
	} elseif ( isset( $field['return_format'] ) && $field['return_format'] === 'label' ) {
		$value = '<span>' . $value . '</span>';
	} elseif ( isset( $field['choices'][ $value ] ) ) {
		$value = $field['choices'][ $value ];
	}


	return $value;
}


/**
 * Default Processor for formatting value of post_object
 *
 * @param $value
 *
 * @return mixed
 * @since    2017.84
 * @verified 2020.09.04
 */
function lct_acf_format_value_post_object( $value )
{
	$post = null;


	if (
		is_array( $value )
		&& count( $value ) > 1
		&& ( $tmp = reset( $value ) )
		&& ! empty( $tmp )
		&& ( get_post( $tmp ) )
	) {
		$objects = [];


		foreach ( $value as $single ) {
			$objects[] = lct_acf_format_value_post_object( $single );
		}


		$value = implode( ', ', $objects );
	} elseif ( lct_is_a( $value, 'WP_Post' ) ) {
		$post = $value;
	} elseif ( ! is_object( $value ) ) {
		$post = get_post( (int) $value );
	}


	if ( lct_is_a( $post, 'WP_Post' ) ) {
		$value = $post->post_title;
	}


	return $value;
}


/**
 * Default Processor for formatting value of checkbox
 *
 * @param mixed $value
 * @param array $field
 *
 * @return mixed
 * @since        2020.11
 * @verified     2020.09.09
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_acf_format_value_checkbox( $value, $field )
{
	if (
		is_array( $value )
		&& ( $tmp = reset( $value ) )
		&& ! empty( $tmp )
	) {
		$objects = [];


		foreach ( $value as $single ) {
			$objects[] = lct_acf_format_value_checkbox( $single, $field );
		}


		$value = '<ul><li>';
		$value .= implode( '</li><li>', $objects );
		$value .= '</li></ul>';
	} elseif ( empty( $value ) ) {
		$value = null;
	} elseif ( $field['return_format'] === 'value' ) {
		$value = $field['choices'][ $value ];
	}


	return $value;
}


/**
 * Default Processor for formatting value of taxonomy
 *
 * @param $value
 * @param $field
 *
 * @return mixed
 * @since    2017.84
 * @verified 2022.02.17
 */
function lct_acf_format_value_taxonomy( $value, $field )
{
	$term_ids = [];


	if ( empty( $value ) ) {
		$value = '';
	} elseif ( is_array( $value ) ) {
		foreach ( $value as $term ) {
			if ( lct_is_a( $term, 'WP_Term' ) ) {
				$term_ids[] = $term;
			} else {
				$taxonomy = '';
				if ( isset( $field['taxonomy'] ) ) {
					$taxonomy = $field['taxonomy'];
				}


				$term_ids[] = get_term( (int) $term, $taxonomy );
			}
		}
	} elseif ( lct_is_a( $value, 'WP_Term' ) ) {
		$term_ids[] = $value;
	} elseif ( is_numeric( $value ) ) {
		$taxonomy = '';
		if ( isset( $field['taxonomy'] ) ) {
			$taxonomy = $field['taxonomy'];
		}


		$term_ids[] = get_term( (int) $value, $taxonomy );
	}


	if ( ! empty( $term_ids ) ) {
		$value  = [];
		$format = '%s';


		if ( count( $term_ids ) > 1 ) {
			$value[] = '<ul>';
			$format  = '<li>%s</li>';
		}


		foreach ( $term_ids as $term_id ) {
			if ( ! lct_is_wp_error( $term_id ) ) {
				$value[] = sprintf( $format, $term_id->name );
			}
		}


		if ( count( $term_ids ) > 1 ) {
			$value[] = '</ul>';
		}


		$value = lct_return( $value );
	}


	return $value;
}


/**
 * Default Processor for formatting value of user
 *
 * @param mixed  $value
 * @param string $return_format
 *
 * @return mixed
 * @since    2017.84
 * @verified 2020.04.16
 */
function lct_acf_format_value_user( $value, $return_format = 'value' )
{
	$user_ids = [];


	if (
		$return_format === 'array'
		&& ! empty( $value['value'] )
	) {
		$user_ids[] = $value['value'];
	} elseif ( is_array( $value ) ) {
		foreach ( $value as $key => $user ) {
			if (
				! is_int( $key )
				&& ! empty( $value['ID'] )
			) {
				$user_ids[] = (int) $value['ID'];
				break;
			} elseif ( ! empty( $user->ID ) ) {
				$user_ids[] = (int) $user->ID;
			} elseif ( ! empty( $user['ID'] ) ) {
				$user_ids[] = (int) $user['ID'];
			} else {
				$user_ids[] = (int) $user;
			}
		}
	} elseif ( is_numeric( $value ) ) {
		$user_ids[] = $value;
	}


	if ( ! empty( $user_ids ) ) {
		$value  = [];
		$format = '%s';


		if ( count( $user_ids ) > 1 ) {
			$value[] = '<ul>';
			$format  = '<li>%s</li>';
		}


		foreach ( $user_ids as $user_id ) {
			$userdata = get_userdata( $user_id );
			$value[]  = sprintf( $format, $userdata->display_name );
		}


		if ( count( $user_ids ) > 1 ) {
			$value[] = '</ul>';
		}


		$value = lct_return( $value );
	}


	return $value;
}


/**
 * Default Processor for formatting value of zip_code
 *
 * @param mixed $value
 *
 * @return mixed
 * @since    2020.11
 * @verified 2020.09.04
 */
function lct_acf_format_value_zip_code( $value )
{
	return $value;
}
