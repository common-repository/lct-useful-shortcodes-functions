<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Return ALL Gravity form fields adminLabel's $gf_field['id']
 *
 * @param array $gf_fields ALL form field's data
 * @param array $lead      Optional. If set the adminLabel's entered value is returned
 *
 * @return array
 * @since    0.0
 * @verified 2017.04.20
 */
function lct_map_adminLabel_to_field_id( $gf_fields, $lead = null )
{
	$r = [];


	foreach ( $gf_fields as $gf_field ) {
		$gf_field['adminLabel'] ? $k = $gf_field['adminLabel'] : $k = $gf_field['id'];

		if ( ! $gf_field['adminLabel'] ) {
			$gf_field['inputName'] ? $k = $gf_field['inputName'] : $k = $gf_field['id'];
		}

		if ( $lead ) {
			$v = $lead[ $gf_field['id'] ];
		} else {
			$v = $gf_field['id'];
		}

		$r[ $k ] = $v;
	}


	return $r;
}


/**
 * Return ALL Gravity form fields Label's $gf_field['id']
 *
 * @param      $gf_fields
 * @param null $lead
 *
 * @return array
 * @since    0.0
 * @verified 2017.04.20
 */
function lct_map_label_to_field_id( $gf_fields, $lead = null )
{
	$r = [];


	foreach ( $gf_fields as $gf_field ) {
		$gf_field['label'] ? $k = sanitize_title( $gf_field['label'] ) : $k = $gf_field['id'];

		if ( $lead ) {
			if ( $gf_field['inputs'] ) {
				$tmp_v = [];
				foreach ( $gf_field['inputs'] as $tmp ) {
					$tmp_v[] = $lead[ $tmp['id'] ];
				}

				$v = implode( '~~~', $tmp_v );
			} else {
				$v = $lead[ $gf_field['id'] ];
			}
		} else {
			$v = $gf_field['id'];
		}

		$r[ $k ] = $v;
	}


	return $r;
}


/**
 * Checks if a gform should be altered
 *
 * @param $form_id
 *
 * @return bool
 * @since    0.0
 * @verified 2017.04.20
 */
function lct_gf_form_should_alter( $form_id )
{
	$r = false;


	if ( lct_plugin_active( 'acf' ) ) {
		$gf = lct_acf_get_option( 'gforms' );

		if ( empty( $gf ) ) {
			$gf = [ 0 ];
		}


		$r = in_array( $form_id, $gf );
	}


	return $r;
}
