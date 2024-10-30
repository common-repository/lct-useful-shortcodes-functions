<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.04.20
 */
class lct_gforms_admin
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.04.20
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
	 * @since    7.62
	 * @verified 2017.09.09
	 */
	function load_hooks()
	{
		//bail early if already ran
		if ( lct_did() ) {
			return;
		}


		/**
		 * everytime
		 */
		/**
		 * actions
		 */
		add_action( 'gform_after_submission', [ $this, 'remove_form_entry' ], 13, 2 );

		add_action( 'gform_notification', [ $this, 'cj_check' ], 9999, 3 );

		add_action( 'gform_confirmation', [ $this, 'query_string_add' ], 9999, 4 );


		/**
		 * filters
		 */
		add_filter( 'gform_merge_tag_filter', [ $this, 'all_fields_extra_options' ], 11, 5 );

		add_filter( 'gform_enable_field_label_visibility_settings', [ $this, 'gform_enable_field_label_visibility_settings' ] );

		add_filter( 'gform_submit_button', [ $this, 'gform_submit_button' ], 10, 2 );


		add_filter( 'gform_field_content', [ $this, 'form_with_columns' ], 10, 5 );

		add_filter( 'gform_pre_render', [ $this, 'submit_button_anywhere' ] );

		if ( version_compare( lct_plugin_version( 'gforms' ), '2.0', '<' ) ) {  //gforms older than v2.0
			add_filter( 'gform_multiselect_placeholder', [ $this, 'gform_multiselect_placeholder_legacy_lt_1' ], 10, 2 );
			add_filter( 'gform_pre_render', [ $this, 'mobile_placeholder_legacy_lt_1' ], 10, 5 );
		} else {
			add_filter( 'gform_pre_render', [ $this, 'mobile_placeholder' ], 10, 5 );
			add_filter( 'gform_multiselect_placeholder', [ $this, 'gform_multiselect_placeholder' ], 10, 3 );
		}


		if ( lct_frontend() ) {
			add_action( 'gform_enqueue_scripts', [ $this, 'wp_enqueue_styles' ], 10, 3 );
			add_action( 'gform_enqueue_scripts', [ $this, 'wp_enqueue_styles_form_specific' ] );
			add_action( 'gform_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
			add_action( 'gform_enqueue_scripts', [ $this, 'wp_enqueue_scripts_form_specific' ] );
		}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Store or remove entry based on settings
	 *
	 * @param array $gf_entry ALL entry data
	 * @param array $gf_form  ALL form data
	 *
	 * @since        0.0
	 * @noinspection PhpUnnecessaryStopStatementInspection
	 */
	function remove_form_entry( $gf_entry, $gf_form )
	{
		if ( lct_plugin_active( 'acf' ) ) {
			if ( lct_acf_get_option_raw( 'gforms_store' ) ) {
				if ( lct_gf_form_should_alter( $gf_form['id'] ) ) {
					return;
				}
			} else {
				if ( ! lct_gf_form_should_alter( $gf_form['id'] ) ) {
					return;
				}
			}


			global $wpdb;

			$entry_id  = $gf_entry['id'];
			$entry_key = 'entry_id';


			/**
			 * Legacy tables
			 */
			if ( ! method_exists( 'RGFormsModel', 'get_entry_table_name' ) ) {
				$entry_key = 'lead_id';

				$main_table  = RGFormsModel::get_lead_table_name();
				$meta_table  = RGFormsModel::get_lead_meta_table_name();
				$notes_table = RGFormsModel::get_lead_notes_table_name();

				$detail_long_table = RGFormsModel::get_lead_details_long_table_name();
				$detail_table      = RGFormsModel::get_lead_details_table_name();


				/**
				 * Delete from detail long
				 */
				$sql = $wpdb->prepare(
					"DELETE FROM `{$detail_long_table}`
						WHERE `lead_detail_id` IN
							( SELECT `id` FROM `{$detail_table}` WHERE `{$entry_key}` = %d )",
					$entry_id
				);
				$wpdb->query( $sql );


				/**
				 * Delete from detail
				 */
				$sql = $wpdb->prepare(
					"DELETE FROM `{$detail_table}` WHERE `{$entry_key}` = %d",
					$entry_id
				);
				$wpdb->query( $sql );


				/**
				 * Current tables
				 */
			} else {
				$main_table  = RGFormsModel::get_entry_table_name();
				$meta_table  = RGFormsModel::get_entry_meta_table_name();
				$notes_table = RGFormsModel::get_entry_notes_table_name();
			}


			/**
			 * Delete from meta
			 */
			$sql = $wpdb->prepare(
				"DELETE FROM `{$meta_table}` WHERE `{$entry_key}` = %d",
				$entry_id
			);
			$wpdb->query( $sql );


			/**
			 * Delete from notes
			 */
			$sql = $wpdb->prepare(
				"DELETE FROM `{$notes_table}` WHERE `{$entry_key}` = %d",
				$entry_id
			);
			$wpdb->query( $sql );


			/**
			 * Delete from main
			 */
			$sql = $wpdb->prepare(
				"DELETE FROM `{$main_table}` WHERE `id` = %d",
				$entry_id
			);
			$wpdb->query( $sql );
		}
	}


	/**
	 * Our custom SPAM checker
	 * //TODO: cs - the foreach(s) could be made more efficient - 12/17/2015 3:32 PM
	 *
	 * @param $gf_notification
	 * @param $gf_form
	 * @param $gf_entry
	 *
	 * @return mixed
	 * @since    0.0
	 */
	function cj_check( $gf_notification, $gf_form, $gf_entry )
	{
		if ( lct_plugin_active( 'acf' ) ) {
			if ( ! lct_acf_get_option_raw( 'enable_cj_spam_check' ) ) {
				return $gf_notification;
			}


			$failed_cj_checks = 0;


			$cj_checks_name   = [];
			$cj_checks_name[] = 'c~~~';
			$cj_checks_name[] = 'c ~~~';
			$cj_checks_name[] = 'c~~~j';
			$cj_checks_name[] = 'c ~~~j';

			$cj_checks_phone   = [];
			$cj_checks_phone[] = '6192025400';

			$cj_checks_email   = [];
			$cj_checks_email[] = 'resultsfirst';

			$mapped_fields = lct_map_label_to_field_id( $gf_form['fields'], $gf_entry );


			foreach ( $mapped_fields as $k => $v ) {
				if ( strpos( $k, 'name' ) !== false ) {
					foreach ( $cj_checks_name as $name ) {
						if ( strpos( strtolower( $v ), $name ) !== false ) {
							$failed_cj_checks ++;
						}
					}
				}

				if ( strpos( $k, 'phone' ) !== false ) {
					$phone = preg_replace( '/\D/', '', $v );
					if ( in_array( $phone, $cj_checks_phone ) ) {
						$failed_cj_checks ++;
					}
				}

				if ( strpos( $k, 'email' ) !== false ) {
					foreach ( $cj_checks_email as $email ) {
						if ( strpos( $v, $email ) !== false ) {
							$failed_cj_checks ++;
						}
					}
				}
				$tmp[] = $k . '...' . $v;
			}


			if ( $failed_cj_checks ) {
				$gf_notification['subject'] = '[CAUGHT BY CJ SPAM CHECK] :: ' . $gf_notification['subject'];

				$emails   = [];
				$emails[] = lct_acf_get_option( 'enable_cj_spam_check_email' );

				$gf_notification['toType'] = "email";
				$gf_notification['to']     = implode( ",", $emails );
				$gf_notification['bcc']    = '';
			}
		}


		return $gf_notification;
	}


	/**
	 * We need some variables from the form in order to proper set our contact page trackers
	 *
	 * @param $confirmation
	 * @param $form
	 * @param $lead
	 * @param $ajax
	 *
	 * @return array
	 * @since 5.36
	 */
	function query_string_add(
		$confirmation,
		$form,
		$lead,
		/** @noinspection PhpUnusedParameterInspection */
		$ajax
	) {
		if (
			lct_plugin_active( 'acf' )
			&& ! empty( $form['confirmation'] )
			&& $form['confirmation']['type'] == 'page'
			&& get_field( zxzacf( 'is_thanks_page' ), $form['confirmation']['pageId'] )
			&& get_field( zxzacf( 'is_google_event_tracking' ), $form['confirmation']['pageId'] )
		) {
			$queryString   = [];
			$queryString[] = 'form_url=' . urlencode( str_replace( lct_url_site(), '', $lead['source_url'] ) );
			$queryString[] = 'thanks=' . $form['id'];


			if ( ! empty( $form['confirmation']['queryString'] ) ) {
				$queryString[] = GFCommon::replace_variables( trim( $form['confirmation']['queryString'] ), $form, $lead, true, true, false, 'text' );
			}

			$confirmation = [ "redirect" => get_the_permalink( $form['confirmation']['pageId'] ) . '?' . implode( '&', $queryString ) ];
		}


		return $confirmation;
	}


	/**
	 * To exclude field from notification add 'exclude[ID]' option to {all_fields} tag
	 * 'include[ID]' option includes HTML field / Section Break field description / Signature image in notification
	 * *
	 * usage: {all_fields:include[ID],exclude[ID,ID]}
	 * *
	 * see http://www.gravityhelp.com/documentation/page/Merge_Tags for a list of standard options
	 * Credit: https://gist.github.com/richardW8k/6947682
	 * example: {all_fields:exclude[2,3]}
	 * example: {all_fields:include[6]}
	 * example: {all_fields:include[6],exclude[2,3]}
	 *
	 * @param $value
	 * @param $merge_tag
	 * @param $options
	 * @param $gf_field
	 * @param $raw_value
	 *
	 * @return bool|string
	 * @since 5.0
	 */
	function all_fields_extra_options(
		$value,
		$merge_tag,
		$options,
		$gf_field,
		/** @noinspection PhpUnusedParameterInspection */
		$raw_value
	) {
		if ( $merge_tag == 'all_fields' ) {
			$log           = "all_fields_extra_options(): {$gf_field['label']}({$gf_field['id']} - {$gf_field['type']}) - ";
			$include       = preg_match( "/include\[(.*?)]/", $options, $include_match );
			$include_array = explode( ',', rgar( $include_match, 1 ) );
			$exclude       = preg_match( "/exclude\[(.*?)]/", $options, $exclude_match );
			$exclude_array = explode( ',', rgar( $exclude_match, 1 ) );


			if (
				$include
				&& in_array( $gf_field['id'], $include_array )
			) {
				switch ( $gf_field['type'] ) {
					case 'html' :
						$value = $gf_field['content'];
						break;


					case 'section' :
						$value .= sprintf(
							'<tr bgcolor="#FFFFFF">
							<td width="20">&nbsp;</td>
							<td>
								<span style="font-family: sans-serif; font-size:12px;">%s</span>
							</td>
						</tr>',
							$gf_field['description']
						);
						break;
				}


				GFCommon::log_debug( $log . 'included.' );
			}


			if (
				$exclude
				&& in_array( $gf_field['id'], $exclude_array )
			) {
				GFCommon::log_debug( $log . 'excluded.' );


				return false;
			}
		}


		return $value;
	}


	/**
	 * Enables the Gravity Forms label visibility/hidden dropdown
	 *
	 * @return bool
	 * @since    5.7
	 * @verified 2017.04.20
	 */
	function gform_enable_field_label_visibility_settings()
	{
		return true;
	}


	/**
	 * Filter the Gravity Forms button class to add our own custom one if set
	 *
	 * @param $button
	 * @param $gf_form
	 *
	 * @return mixed
	 * @since 0.0
	 */
	function gform_submit_button(
		$button,
		/** @noinspection PhpUnusedParameterInspection */
		$gf_form
	) {
		if (
			lct_plugin_active( 'acf' )
			&& $custom_class = lct_acf_get_option( 'gform_button_custom_class' )
		) {
			$fnr = [
				"class='button "        => "class='{$custom_class} button ",
				"class='gform_button "  => "class='{$custom_class} gform_button ",
				"class=\"button "       => "class=\"{$custom_class} button ",
				"class=\"gform_button " => "class=\"{$custom_class} gform_button ",
			];
			$fnr = lct_create_find_and_replace_arrays( $fnr );

			$button = str_replace( $fnr['find'], $fnr['replace'], $button );
		}


		return $button;
	}


	/**
	 * Add columns to the form
	 * //TODO: cs - Need to make the <li> thing better. the function currently creates empty divs that we have to hide with CSS - 12/2/2015 12:40 PM
	 *
	 * @param $content
	 * @param $gf_field
	 * @param $value
	 * @param $lead_id
	 * @param $gf_form_id
	 *
	 * @return string
	 * @since    0.0
	 * @verified 2022.02.03
	 */
	function form_with_columns(
		$content,
		$gf_field,
		/** @noinspection PhpUnusedParameterInspection */
		$value,
		/** @noinspection PhpUnusedParameterInspection */
		$lead_id,
		$gf_form_id
	) {
		//Only modify HTML on the front end
		if ( is_admin() ) {
			return $content;
		}


		if ( ! is_array( $gf_field ) ) {
			$gf_field = (array) $gf_field;
		}


		$gf_form          = RGFormsModel::get_form_meta( $gf_form_id );
		$gf_form_class    = array_key_exists( 'cssClass', $gf_form ) ? $gf_form['cssClass'] : '';
		$gf_form_classes  = preg_split( '/[\n\r\t ]+/', $gf_form_class, - 1, PREG_SPLIT_NO_EMPTY );
		$gf_fields_class  = array_key_exists( 'cssClass', $gf_field ) ? $gf_field['cssClass'] : '';
		$gf_field_classes = preg_split( '/[\n\r\t ]+/', $gf_fields_class, - 1, PREG_SPLIT_NO_EMPTY );


		if ( $gf_field['type'] == 'section' ) {
			//check for the presence of multi-column form classes
			$gf_form_class_matches = array_intersect( $gf_form_classes, [ zxzu( 'gf_2_col' ), zxzu( 'gf_3_col' ) ] );

			//check for the presence of section break column classes
			$gf_field_class_matches = array_intersect( $gf_field_classes, [ zxzu( 'gf_col' ) ] );

			//if field is a column break in a multi-column form, perform the list split
			if (
				! empty( $gf_form_class_matches )
				&& ! empty( $gf_field_class_matches )
			) {
				//retrieve the form's field list classes for consistency
				$ul_classes = GFCommon::get_ul_classes( $gf_form ) . ' ' . $gf_field['cssClass'];

				//close current field's li and ul and begin a new list with the same form field list classes
				return '</li></ul><ul class="' . $ul_classes . '"><li class="gfield gsection empty">';
			}
		}


		return $content;
	}


	/**
	 * Add a forms submit button anywhere you feel like it should go
	 *
	 * @param array $gf_form
	 *
	 * @return array
	 * @since    0.0
	 * @verified 2017.01.23
	 */
	function submit_button_anywhere( $gf_form )
	{
		$shortcode = zxzu( 'gf_submit' );


		if ( ! empty( $gf_form['fields'] ) ) {
			foreach ( $gf_form['fields'] as $gf_entry_id => $gf_field ) {
				if (
					$gf_field['type'] == 'html'
					&& strpos( $gf_field['content'], $shortcode ) !== false
				) {
					$atts = [];
					$find = '/\[' . $shortcode . '(.*?)]/';

					preg_match( $find, $gf_field['content'], $matches );

					if ( isset( $matches[1] ) && $matches[1] ) {
						$tmp_atts = explode( ' ', trim( $matches[1] ) );

						foreach ( $tmp_atts as $tmp_att ) {
							$tmp = explode( '=', $tmp_att );

							$atts[ strtolower( $tmp[0] ) ] = rtrim( rtrim( ltrim( ltrim( $tmp[1], '"' ), "'" ), '"' ), "'" );
						}
					}

					if ( empty( $atts['id'] ) ) {
						$atts['id'] = $gf_form['id'];
					}

					if ( $gf_form['button']['type'] == 'text' ) {
						$atts['text'] = $gf_form['button']['text'];
					}

					$atts_replace = $atts;
					$atts         = [];

					foreach ( $atts_replace as $att_k => $att ) {
						$atts[] = $att_k . "='" . $att . "'";
					}

					$atts = implode( ' ', $atts );

					$replacement = "[{$shortcode} {$atts}]";

					$content = preg_replace( $find, $replacement, $gf_field['content'] );

					$gf_form['fields'][ $gf_entry_id ]['content'] = $content;
				}


			}
		}


		return $gf_form;
	}


	/**
	 * Use the label as a placeholder for the multi-select chosen
	 *
	 * @param $placeholder
	 * @param $gf_form_id
	 * @param $gf_field
	 *
	 * @return mixed
	 * @since 5.7
	 */
	function gform_multiselect_placeholder(
		$placeholder,
		/** @noinspection PhpUnusedParameterInspection */
		$gf_form_id,
		$gf_field
	) {
		if (
			isset( $gf_field->label )
			&& $gf_field->label
		) {
			$placeholder = $gf_field->label;
		}


		return $placeholder;
	}


	/**
	 * Use the label as a placeholder for the multi-select chosen
	 *
	 * @param $placeholder
	 * @param $gf_form_id
	 *
	 * @return mixed
	 * @since 5.7
	 */
	function gform_multiselect_placeholder_legacy_lt_1( $placeholder, $gf_form_id )
	{
		$gf_form = RGFormsModel::get_form_meta( $gf_form_id );


		if ( ! empty( $gf_form['fields'] ) ) {
			foreach ( $gf_form['fields'] as $gf_field ) {
				if (
					$gf_field['type'] == 'multiselect'
					&& (
						$gf_field['labelPlacement'] == 'hidden_label'
						|| strpos( $gf_field['cssClass'], 'hide_label_if_desktop' ) !== false
					)
				) {
					return $gf_field['label'];
				}
			}
		}


		return $placeholder;
	}


	/**
	 * Placeholder
	 *
	 * @param $gf_form
	 *
	 * @return mixed
	 * @since    5.34
	 * @verified 2017.04.04
	 */
	function mobile_placeholder( $gf_form )
	{
		$ph_classes = [
			'mobile_placeholder',
			'mobile_ph'
		];


		if ( ! empty( $gf_form['fields'] ) ) {
			foreach ( $gf_form['fields'] as $gf_entry_id => $gf_field ) {
				if ( $gf_field->cssClass ) {
					$cssClasses = explode( ' ', $gf_field->cssClass );
				} else {
					$cssClasses = [];
				}

				$ph_classes_intersect = array_intersect( $ph_classes, $cssClasses );


				if (
					$gf_field->type == 'multiselect'
					&& $cssClasses
					&& ! empty( $ph_classes_intersect )
				) {
					if ( $gf_field->label ) {
						$ph_label = $gf_field->label;
					} else {
						$ph_label = 'Click to select...';
					}

					$ph_choice = [
						[
							'text'       => $ph_label,
							'value'      => $ph_label,
							'isSelected' => true,
							'price'      => ''
						]
					];


					$gf_form['fields'][ $gf_entry_id ]->choices = array_merge_recursive( $ph_choice, $gf_field->choices );


					$gform_post_render = sprintf(
						'%1$sjQuery(document).bind( "gform_post_render", function(){
							var width = jQuery(window).width();
		
							if( width >= %3$s ){
								jQuery( "#input_%4$s_%5$s" ).val( "" ).trigger( "chosen:updated" );
								jQuery( \'#input_%4$s_%5$s option[value="%6$s"]\' ).remove();
							}
						});%2$s',
						'<script type="text/javascript">',
						'</script>',
						lct_get_mobile_threshold(),
						$gf_form['id'],
						$gf_field->id,
						$ph_label
					);


					echo $gform_post_render;
				}


			}
		}


		return $gf_form;
	}


	/**
	 * Placeholder
	 *
	 * @param $gf_form
	 *
	 * @return mixed
	 * @since    5.34
	 * @verified 2017.04.04
	 */
	function mobile_placeholder_legacy_lt_1( $gf_form )
	{
		$ph_classes = [
			'mobile_placeholder',
			'mobile_ph'
		];


		if ( ! empty( $gf_form['fields'] ) ) {
			foreach ( $gf_form['fields'] as $gf_entry_id => $gf_field ) {
				if ( $gf_field['cssClass'] ) {
					$cssClasses = explode( ' ', $gf_field['cssClass'] );
				} else {
					$cssClasses = [];
				}

				$ph_classes_intersect = array_intersect( $ph_classes, $cssClasses );


				if (
					$gf_field['type'] == 'multiselect'
					&& $cssClasses
					&& ! empty( $ph_classes_intersect )
				) {
					if ( $gf_field['label'] ) {
						$ph_label = $gf_field['label'];
					} else {
						$ph_label = 'Click to select...';
					}

					$ph_choice = [
						[
							'text'       => $ph_label,
							'value'      => $ph_label,
							'isSelected' => true,
							'price'      => ''
						]
					];

					$gf_form['fields'][ $gf_entry_id ]['choices'] = array_merge_recursive( $ph_choice, $gf_field['choices'] );


					$gform_post_render = sprintf(
						'%1$sjQuery(document).bind( "gform_post_render", function(){
							var width = jQuery(window).width();
		
							if( width >= %3$s ){
								jQuery( "#input_%4$s_%5$s" ).val( "" ).trigger( "chosen:updated" );
								jQuery( \'#input_%4$s_%5$s option[value="%6$s"]\' ).remove();
							}
						});%2$s',
						'<script type="text/javascript">',
						'</script>',
						lct_get_mobile_threshold(),
						$gf_form['id'],
						$gf_field['id'],
						$ph_label
					);


					echo $gform_post_render;
				}


			}
		}


		return $gf_form;
	}


	/**
	 * Register Styles
	 *
	 * @param      $gf_form_id
	 *
	 * @unused   param      $form
	 * @unused   param      $ajax
	 * @since    0.0
	 * @verified 2017.11.06
	 */
	function wp_enqueue_styles( $gf_form_id )
	{
		if (
			lct_plugin_active( 'acf' )
			&& ! empty( $gf_form_id )
		) {
			$legacy_highest = version_compare( lct_plugin_version( 'gforms' ), '2.2', '<' ); //gforms older than v2.2
			//$legacy_lt_2_2 = version_compare( lct_plugin_version( 'gforms' ), '2.2', '<' ); //gforms older than v2.2
			$legacy_lt_2_0 = version_compare( lct_plugin_version( 'gforms' ), '2.0', '<' );  //gforms older than v2.0
			$legacy        = '';

			if ( $legacy_lt_2_0 ) {
				$legacy = '2.0';
			} elseif ( $legacy_highest ) {
				$legacy = 2.2;
			}


			//Should we load our gforms css?
			if ( lct_acf_get_option_raw( 'use_gforms_css_tweaks' ) ) {
				if ( $legacy_highest ) {
					$file = lct_get_root_path( sprintf( 'assets/css/plugins/gforms/main-legacy_lt-%s.min.css', $legacy ) );
				} else {
					$file = lct_get_root_path( 'assets/css/plugins/gforms/main.min.css' );
				}

				//TODO: cs - it would be nice to use lct_enqueue_style(), but gform_enqueue_scripts action does no currently work correctly - 06/01/2016 11:18 AM
				//lct_enqueue_style( zxzu( 'gforms' ), $file );
				/**
				 * #4
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.27
				 */
				do_action( 'lct_wp_footer_style_add', file_get_contents( $file ) );


				if ( ! $legacy_lt_2_0 ) {
					lct_dynamic_css_gforms();
				}
			}


			//Should we load our Avada gforms css?
			if (
				lct_theme_active( 'Avada' )
				&& lct_acf_get_option_raw( 'use_gforms_css_tweaks' )
				&& ! lct_acf_get_option_raw( 'disable_avada_css' )
			) {
				if ( $legacy_highest ) {
					$file = lct_get_root_path( sprintf( 'assets/css/plugins/Avada/gforms-legacy_lt-%s.min.css', $legacy ) );
				} else {
					$file = lct_get_root_path( 'assets/css/plugins/Avada/gforms.min.css' );
				}

				//TODO: cs - it would be nice to use lct_enqueue_style(), but gform_enqueue_scripts action does no currently work correctly - 06/01/2016 11:18 AM
				//lct_enqueue_style( zxzu( 'Avada_gforms' ), $file );
				/**
				 * #5
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.27
				 */
				do_action( 'lct_wp_footer_style_add', file_get_contents( $file ) );
			}


			//Let's check and see if the child theme has any gforms css it wants to load?
			$file = lct_path_theme() . '/custom/css/gforms.min.css';

			if ( file_exists( $file ) ) {
				//TODO: cs - it would be nice to use lct_enqueue_style(), but gform_enqueue_scripts action does no currently work correctly - 06/01/2016 11:18 AM
				//lct_enqueue_style( zxzu( 'theme_gforms' ), $file );
				/**
				 * #6
				 * @date     0.0
				 * @since    0.0
				 * @verified 2021.08.27
				 */
				do_action( 'lct_wp_footer_style_add', file_get_contents( $file ) );
			}


			remove_action( 'gform_enqueue_scripts', [ $this, 'wp_enqueue_styles' ] );
		}
	}


	/**
	 * Register Styles
	 *
	 * @param      $gf_form
	 *
	 * @since    0.0
	 */
	function wp_enqueue_styles_form_specific( $gf_form )
	{
		if (
			! lct_plugin_active( 'acf' )
			|| empty( $gf_form )
		) {
			return;
		}


		//Let's check and see if the child theme has any gforms form specific css it wants to load?
		$file = lct_path_theme() . '/custom/css/gforms_' . $gf_form['id'] . '.min.css';

		if ( file_exists( $file ) ) {
			//TODO: cs - it would be nice to use lct_enqueue_style(), but gform_enqueue_scripts action does no currently work correctly - 06/01/2016 11:18 AM
			//lct_enqueue_style( zxzu( 'theme_gforms_{$gf_form['id']}' ), $file );
			/**
			 * #7
			 * @date     0.0
			 * @since    0.0
			 * @verified 2021.08.27
			 */
			do_action( 'lct_wp_footer_style_add', file_get_contents( $file ) );
		}
	}


	/**
	 * Register Scripts
	 *
	 * @param      $gf_form
	 *
	 * @since    0.0
	 */
	function wp_enqueue_scripts( $gf_form )
	{
		if (
			! lct_plugin_active( 'acf' )
			|| empty( $gf_form )
		) {
			return;
		}


		//Let's check and see if the child theme has any gforms js it wants to load?
		$file = lct_path_theme() . '/custom/js/gforms.min.js';

		if ( file_exists( $file ) ) {
			//TODO: cs - it would be nice to use lct_enqueue_style(), but gform_enqueue_scripts action does no currently work correctly - 06/01/2016 11:18 AM
			//lct_enqueue_script( zxzu( 'theme_gforms' ), $file );


			/**
			 * #6
			 * @date     0.0
			 * @since    0.0
			 * @verified 2021.08.27
			 */
			do_action( 'lct_jq_doc_ready_add', file_get_contents( $file ) );
		}


		//Add textarea autosize to gforms
		$jq = "\n" . 'var gforms_action_style_n_script_selector = \'.gform_wrapper textarea\';

		if( jQuery( gforms_action_style_n_script_selector ).length ) {
			var gforms_action_style_n_script_ta = document.querySelector( gforms_action_style_n_script_selector );
			gforms_action_style_n_script_ta.addEventListener( \'focus\', function() {
				autosize( gforms_action_style_n_script_ta );
			});
		}' . "\n";

		//Set all stars in a sub-label to gfield_required
		$jq .= "\n" . 'jQuery(\'.gform_wrapper .ginput_complex label\').each( function () {
			jQuery(this).html( jQuery(this).html().replace(/(\*)/g, \'<span class="gfield_required">$1</span>\'));
		});' . "\n";


		/**
		 * #7
		 * @date     0.0
		 * @since    0.0
		 * @verified 2021.08.27
		 */
		do_action( 'lct_jq_doc_ready_add', $jq );


		/**
		 * #2
		 * @date     0.0
		 * @since    0.0
		 * @verified 2021.08.27
		 */
		do_action( 'lct_jq_autosize' );


		remove_action( 'gform_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
	}


	/**
	 * Register Scripts
	 *
	 * @param      $gf_form
	 *
	 * @since    0.0
	 */
	function wp_enqueue_scripts_form_specific( $gf_form )
	{
		if (
			! lct_plugin_active( 'acf' )
			|| empty( $gf_form )
		) {
			return;
		}


		//Let's check and see if the child theme has any gforms form specific js it wants to load?
		$file = lct_path_theme() . '/custom/js/gforms_' . $gf_form['id'] . '.min.js';

		if ( file_exists( $file ) ) {
			//TODO: cs - it would be nice to use lct_enqueue_style(), but gform_enqueue_scripts action does no currently work correctly - 06/01/2016 11:18 AM
			//lct_enqueue_script( zxzu( 'theme_gforms_{$gf_form['id']}' ), $file );


			/**
			 * #8
			 * @date     0.0
			 * @since    0.0
			 * @verified 2021.08.27
			 */
			do_action( 'lct_jq_doc_ready_add', file_get_contents( $file ) );
		}
	}
}
