<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * OLD: Get a list of ALL gravity forms
 * DO NOT MOVE TO: deprecated it will break!
 *
 * @param $hide
 *
 * @return array
 * @since    0.0
 * @verified 2016.10.11
 */
function lct_select_options_gravity_forms( $hide )
{
	$select_options = [];


	if ( ! class_exists( 'RGFormsModel' ) ) {
		return $select_options;
	}


	$gf_forms = RGFormsModel::get_forms( null, 'title' );


	if ( empty( $gf_forms ) ) {
		return $select_options;
	}


	if ( ! $hide ) {
		$select_options[] = lct_get_select_blank();
	}


	foreach ( $gf_forms as $gf_form ) {
		$select_options[] = [ 'label' => $gf_form->title, 'value' => $gf_form->id ];
	}


	return $select_options;
}


/**
 * OLD: Get a list of ALL fields for a single gravity form
 * DO NOT MOVE TO: deprecated it will break!
 *
 * @param $hide
 * @param $type
 * @param $v
 *
 * @return array
 * @since    0.0
 * @verified 2016.10.11
 */
function lct_select_options_gravity_forms_form_fields(
	$hide,
	/** @noinspection PhpUnusedParameterInspection */
	$type,
	$v
) {
	$select_options = [];


	if ( ! class_exists( 'RGFormsModel' ) ) {
		return $select_options;
	}


	$gf_form = RGFormsModel::get_form_meta( $v['gform_id'] );


	if ( ! $hide ) {
		$select_options[] = lct_get_select_blank();
	}


	foreach ( $gf_form['fields'] as $gf_fields ) {
		$exclude_type = [
			'section',
			'html',
		];

		if ( in_array( $gf_fields['type'], $exclude_type ) ) {
			continue;
		}


		switch ( $gf_fields['type'] ) {
			case 'address':
				foreach ( $gf_fields['inputs'] as $tmp ) {
					$select_options[] = [ 'label' => $tmp['label'], 'value' => $tmp['id'] ];
				}
				break;


			case 'checkbox':
				foreach ( $gf_fields['inputs'] as $tmp ) {
					$select_options[] = [ 'label' => $gf_fields['label'] . ': ' . $tmp['label'], 'value' => $tmp['id'] ];
				}
				break;


			default:
				$select_options[] = [ 'label' => $gf_fields['label'], 'value' => $gf_fields['id'] ];
				break;
		}
	}


	return $select_options;
}
