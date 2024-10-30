<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Get the extra content for thank you pages
 * Add any JS tracking scripts
 *
 * @param $content
 *
 * @return mixed
 * @since    7.56
 * @verified 2017.02.07
 */
function lct_acf_is_thanks_page( $content )
{
	if ( ! $content ) {
		return $content;
	}


	$post_id = get_the_ID();


	if (
		$post_id
		&& get_field( zxzacf( 'is_thanks_page' ), $post_id )
	) {
		if ( get_field( zxzacf( 'is_google_event_tracking' ), $post_id ) ) {
			$action = esc_js( lct_check_for_nested_shortcodes( get_field( zxzacf( 'google_event_tracking_action' ), $post_id ) ) );
			$label  = esc_js( lct_check_for_nested_shortcodes( get_field( zxzacf( 'google_event_tracking_label' ), $post_id ) ) );


			if (
				empty( $action )
				&& isset( $_GET['thanks'] )
			) {
				$action = "gforms ID: {$_GET['thanks']}";
			}


			if (
				empty( $label )
				&& isset( $_GET['form_url'] )
			) {
				$label = $_GET['form_url'];
			}


			if ( $append_content = lct_get_gaTracker_onclick( 'form_submit', $action, $label, false ) ) {
				$content .= sprintf( '<script>%s</script>', $append_content );
			}
		}


		if (
			get_field( zxzacf( 'is_google_conversion_code' ), $post_id )
			&& ( $conversion_code = html_entity_decode( get_field( zxzacf( 'google_conversion_code' ), $post_id ) ) )
		) {
			$content .= $conversion_code;
		}


		if (
			get_field( zxzacf( 'is_bing_conversion_code' ), $post_id )
			&& ( $conversion_code = html_entity_decode( get_field( zxzacf( 'bing_conversion_code' ), $post_id ) ) )
		) {
			$content .= $conversion_code;
		}
	}


	return $content;
}


/**
 * Check if the page you are on is a field editing page
 *
 * @return bool
 * @since    2019.29
 * @verified 2023.09.21
 */
function lct_acf_is_field_group_editing_page()
{
	if (
		( $r = lct_get_setting( 'acf_is_field_group_editing_page' ) ) === null
		&& (
			(
				isset( $_REQUEST['post_type'] )
				&& $_REQUEST['post_type'] === 'acf-field-group'
			)
			|| (
				isset( $_REQUEST['post'] )
				&& get_post_type( $_REQUEST['post'] ) === 'acf-field-group'
			)
			|| (
				isset( $_REQUEST['action'] )
				&& str_ends_with( $_REQUEST['action'], '/query' )
				&& ! empty( $_SERVER['HTTP_REFERER'] )
				&& str_contains( $_SERVER['HTTP_REFERER'], 'action=edit' )
			)
		)
	) {
		$r = true;


		lct_update_setting( 'acf_is_field_group_editing_page', $r );
	}


	return $r;
}


/**
 * lct_acf_is_repeater_subfield
 * Returns true if the field is considered a subfield of a repeater
 *
 * @param array $field
 * @param mixed $post_id
 *
 * @return    bool
 * @date         2020.10.05
 * @since        2020.13
 * @verified     2020.10.05
 * @noinspection PhpMissingParamTypeInspection
 */
function lct_acf_is_repeater_subfield( $field, $post_id = false )
{
	$r = false;


	if (
		acf_is_field_key( $field['parent'] )
		&& ( $parent_obj = get_field_object( $field['parent'], $post_id ) )
		&& $parent_obj['type'] === 'repeater'
	) {
		$r = true;
	}


	return $r;
}


/**
 * Check if the request you are running is allowed
 *
 * @return bool
 * @date     2023.12.15
 * @since    2023.04
 * @verified 2023.12.15
 */
function lct_acf_is_process_shortcodes_needed()
{
	if ( ( $r = lct_get_setting( 'acf_is_process_shortcodes_allowed' ) ) === null ) {
		$r = true;


		lct_update_setting( 'acf_is_process_shortcodes_allowed', $r );
	}


	return $r;
}
