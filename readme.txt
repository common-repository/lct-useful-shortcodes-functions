=== LCT Useful Shortcodes & Functions ===
Contributors: ircary, pimg
Tags: Functions, Shortcodes
Requires at least: 6.6
Tested up to: 6.6.2
Requires PHP: 8.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Shortcodes & Functions that will help make your life easier.

== Description ==
Shortcodes & Functions that will help make your life easier.
= Links =
* [Website](https://www.simplesmithmedia.com)

== Installation ==
From your WordPress dashboard

1. **Visit** Plugins > Add New
2. **Search** for "LCT Useful Shortcodes & Functions"
3. **Activate** LCT Useful Shortcodes & Functions from your Plugins page

== Screenshots ==
none

== Frequently Asked Questions ==
none

== Important Stats ==
* Action Hook Count [verified: 2023.02]: [114] 93 (Not included: 21 Templates) [do_action(]
* Filter Hook Count [verified: ????.??]: [00] 00 (Not included: 00 Templates) [apply_filters(]

== Changelog ==
= 2024.10 =
*Release Date - 14 October 2024*

* Added:
	* add_action( 'acf/render_field_settings/type=button_group', [ $this, 'render_field_settings_button_group' ] );
* Removed:
	* lct_prep_custom_WP_Post_obj_to_array()
	* lct_prep_custom_WP_User_obj_to_array()

= 2024.09 =
*Release Date - 27 September 2024*

* PHP v8.3 Ready

= 2024.08 =
*Release Date - 11 September 2024*

* WP v6.6.2 Ready
* Avada v7.11.10 Ready
* JS Tweaks
* Removed
	* lct_org_us()
	* lct_org_status_us()

= 2024.07 =
*Release Date - 16 August 2024*

* WP v6.4.5 Ready
* CSS Tweaks

= 2024.06 =
*Release Date - 3 July 2024*

* Added:
	* lct_get_WP_UTC_DateTime_from_today()

= 2024.05 =
*Release Date - 31 May 2024*

* JS Tweaks

= 2024.04 =
*Release Date - 14 May 2024*

* WP v6.4.4 Ready
* JS Tweaks
* Improved:
	* PDER{}
* Removed:
	* add_filter( 'acf/update_value/type=number', [ $this, 'check_min_max' ], 10, 3 );
	* lct_features_theme_chunk{} iFrame support

= 2024.03 =
*Release Date - 01 April 2024*

* Removed:
	* add_filter( 'register_post_type_args', [ $this, 'prevent_bad_permalinks' ], 10, 2 );
	* lct_update_status_taxonomy_term_count()
	* add_filter( 'acf/get_fields', [ $this, 'acf_get_fields' ], 10, 2 );
	* add_filter( 'lct/post_types/prevent_bad_permalinks', [ $this, 'prevent_bad_permalinks' ], 10, 3 );

= 2024.02 =
*Release Date - 13 March 2024*

* Added:
	* lct_cache_vars()
* Removed:
	* add_action( 'lct_add_tax_to_user_admin_page', [ $this, 'add_tax_to_user_admin_page' ] );

= 2024.01 =
*Release Date - 01 February 2024*

* WP v6.4.3 Ready
* CSS Tweaks

= 2023.04 =
*Release Date - 03 January 2024*

* WP v6.4.2 Ready
* Avada v7.11.3 Ready
* CSS Tweaks
* Added
	* add_filter( 'lct/acf_loaded/load_reference/show_error_log', [ $this, 'show_error_log' ], 10, 2 );
	* lct_acf_is_process_shortcodes_needed()
	* lct_acf_admin()
* Improved:
	* timezone_settings()
* Removed:
	* add_action( 'acf/input/form_data', [ $this, 'form_data_nested_field_check' ], 15 );

= 2023.03 =
*Release Date - 07 November 2023*

* WP v6.3.2 Ready
* Avada v7.11 Ready
* Minor code tweaks

= 2023.02 =
*Release Date - 07 August 2023*

* Added:
	* add_filter( 'acf/init', [ $this, 'prepare_fields_for_import_store' ], 9 );
	* lct_acf_is_process_shortcodes_needed()
* Removed:
	* add_action( 'acf/input/form_data', [ $this, 'form_data_nested_field_check' ], 15 );

= 2023.01 =
*Release Date - 05 April 2023*

* Code Reformat

= 2022.12 =
*Release Date - 09 January 2023*

* WP v6.1.1 Ready

= 2022.11 =
*Release Date - 02 December 2022*

* CSS Tweaks
* New Action:
	* lct/acf_form/after_acf_form
* Added:
	* lct_pre_check_post_id()

= 2022.10 =
*Release Date - 09 November 2022*

* WP v6.1 Ready
* Added:
	* lct_acf_public_choices{}pretty_state_abbr_value_list()
	* lct_acf_public_choices{}pretty_state_abbr_value_list_data()
	* lct_acf_is_field_repeater()
	* lct_acf_is_field_clone()
	* lct_acf_is_field_seamless_clone()
	* P_R_loop()
	* lct_acf_get_POST_key_selector_map()
	* add_filter( 'acf/clone_field', [ $this, 'clone_field_update_choices' ], 999, 2 );
* Updated:
	* lct_acf_get_POST_values_w_selector_key()
	* P_R()
	* [lct_get_the_title]
* Improved:
	* lct_acf_loaded{}prepare_fields_for_import()
	* lct_acf_get_POST_value()
	* lct_acf_get_selector()
	* lct_clean_acf_repeater()
* Removed:
	* lct_acf_is_selector_repeater()
	* add_filter( 'acf/load_field/type=clone', [ $this, 'load_field_update_choices_clone' ] );

= 2022.9 =
*Release Date - 17 October 2022*

* JS Tweaks
* CSS Tweaks
* Updated:
	* lct_wp_api_general{}
	* lct_acf_form2()
	* lct_Avada_admin{}fusion_options_saved()
	* lct_Avada_header{}header_layout()
	* lct_wp_admin_acf_admin{}check_for_field_issues_duplicate_override()
	* lct_wp_admin_admin_loader{}load_admin()
	* lct_wp_admin_admin_admin{}check_for_field_issues()
	* lct_acf_field_settings{}prepare_field_add_class_selector()
* Improved:
	* lct_acf_format_value()
	* lct_acf_format_value_radio_display_format()
	* lct_api_class{}set_all_cnst()
* Removed:
	* lct_wp_api_api{}
	* lct_rel_tax()
	* lct_rel_post()
	* lct_add_rel_term()
	* lct_get_rel()
	* lct_get_rel_id()
	* lct_get_rel_post()
	* lct_get_rel_post_id()
	* lct_get_rel_tax()
	* lct_get_rel_tax_id()
	* add_action( 'wp_enqueue_scripts', [ $this, 'fix_google_api_scripts' ], 999999 );
	* add_filter( 'fusion_element_column_content', [ $this, 'prevent_column_element_lazy_loading' ], 1, 2 );
	* add_filter( 'fusion_element_container_content', [ $this, 'prevent_container_element_lazy_loading' ], 1, 2 );
	* add_filter( 'fusion_attr_image-shortcode-tag-element', [ $this, 'prevent_image_element_lazy_loading_deep' ], 999 );
	* add_filter( 'fusion_shortcode_content', [ $this, 'fusion_shortcode_content_fusion_imageframe' ], 10, 3 );
	* add_filter( 'fusion_element_column_content', [ $this, 'reset_column_count' ], 2, 2 );
	* add_action( 'wp_enqueue_scripts', [ $this, 'fix_google_api_scripts' ], 999999 );
	* add_action( 'load-appearance_page_avada_options', [ $this, 'autoload_google_map_api_key' ] );
	* add_action( 'load-update-core.php', [ $this, 'autoload_google_map_api_key' ] );
	* add_filter( 'pre_update_option_fusion_dynamic_css_posts', [ $this, 'fusion_dynamic_css_posts' ], 10, 3 );
* Removed:
	* add_filter( 'acf/load_field/type=clone', [ $this, 'modify_clone' ] );
	* add_filter( 'lct/check_for_field_issues/duplicate_clone_override', [ $this, 'check_for_field_issues_duplicate_clone_override' ], 10, 2 );
	* add_action( 'acf/render_field_settings/type=clone', [ $this, 'render_field_settings_clone' ] );
	* add_filter( 'acf/prepare_field/type=clone', [ $this, 'prepare_field_add_class_selector' ] );
	* add_filter( 'acf/prepare_field/type=clone', [ $this, 'prepare_field_add_clone_width_override' ] );
	* lct_acf_field_settings{}field_setting_clone_override_class_selector()
	* lct_acf_field_settings{}field_setting_clone_width_override()

= 2022.8 =
*Release Date - 19 September 2022*

* Updated:
	* [lct_acf_form2]
	* lct_acf_form2()

= 2022.7 =
*Release Date - 14 September 2022*

* WP v6.0.2 Ready
* JS Tweaks
* Added:
	* lct_features_asset_loader{}generate_alert_message_texts()
	* lct_features_asset_loader{}api_error_text()
	* lct_features_asset_loader{}redirect_page_text()
	* lct_implode_html_attributes()
* Updated:
	* lct_features_asset_loader{}register_main_scripts()
* Improved:
	* lct_api_class{}load_status_of_post_type()
	* lct_append_setting()
	* lct_wp_api_general{}do_shortcode()

= 2022.6 =
*Release Date - 8 September 2022*

* PHP v8.1 Ready
* WP v6.0.1 Ready
* Avada v7.8.1 Ready
* Updated:
	* lct_avada_template_version_router()
* Improved:
	* lct_previous_function()
	* lct_debug_to_error_log()
	* lct_acf_form2()
	* lct_acf_format_value()
	* get_label()
	* [lct_acf]
	* lct_acf_instant_save{}non_ajax_add_comment()
	* PDER{}send_ereminder()
	* lct_acf_field_settings{}update_field_update_choices()
	* lct_taxonomies{}disable_status_slug_editing()
	* lct_taxonomies{}disable_status_slug_editing_on_term()

= 2022.5 =
*Release Date - 16 August 2022*

* Updated:
	* Special AFWP functions
* Improved:
	* lct_debug_to_error_log()
	* lct_admin_cron{}status_worthy_commit()
	* PDER{}send_ereminder()
	* lct_acf_loaded{}load_reference()
	* lct_features_asset_loader{}register_main_scripts()
	* lct_features_asset_loader{}admin_register_main_scripts()
	* lct_features_theme_chunk{}wp_enqueue_scripts()
	* lct_features_theme_chunk{}ajax_handler()

= 2022.4 =
*Release Date - 06 June 2022*

* WP v5.9.3 Ready
* Added
	* add_filter( 'recovery_mode_email', 'lct_mu_recovery_mode_email_override', 99999, 2 );
	* add_filter( 'lct/check_all_fusion_pages_for_bad_avada_assets', [ $this, 'disable_warning_notifications' ] );
* Improved:
	* PDER_Admin{}schedule_reminder()
	* [theme_chunk]
	* lct_acf_filters_update_value{}timezone_adjust()
	* lct_acf_filters_update_value{}timezone_adjust_from_gmt()
	* lct_wp_admin_admin_admin{}remove_meta_boxes()

= 2022.3.1 =
*Release Date - 15 March 2022*

* Improved:
	* afwp_acf_base64_decode()

= 2022.3 =
*Release Date - 11 March 2022*

* Updated:
	* lct_acf_format_value_date_display_format()
	* add_action( 'tool_box', [ $this, 'add_tool_boxes' ] );
	* add_action( 'admin_init', [ $this, 'wp_recovery_mode_clear_rate_limit' ], 999 );

= 2022.2 =
*Release Date - 07 March 2022*

* Added:
	* add_filter( 'lct/check_for_bad_youtubes/check_pages', [ $this, 'disable_warning_notifications' ] );
	* add_filter( 'lct/check_for_bad_youtubes/check_posts', [ $this, 'disable_warning_notifications' ] );
	* add_filter( 'lct/check_for_bad_youtubes/check_fusion', [ $this, 'disable_warning_notifications' ] );
	* add_filter( 'lct/check_for_bad_iframes/check_pages', [ $this, 'disable_warning_notifications' ] );
	* add_filter( 'lct/check_for_bad_iframes/check_posts', [ $this, 'disable_warning_notifications' ] );
	* add_filter( 'lct/avada/check_for_bad_avada_assets/google_analytics', [ $this, 'disable_warning_notifications' ] );
	* add_filter( 'lct/avada/check_for_bad_avada_assets/head_space', [ $this, 'disable_warning_notifications' ] );
	* add_filter( 'lct/avada/check_for_bad_avada_assets/custom_css', [ $this, 'disable_warning_notifications' ] );
* Updated:
	* lct_mu{}
* Improved:
	* lct_make_status_name()
	* lct_acf_format_value_true_false_display_format()
	* lct_acf_format_value_taxonomy()
	* lct_acf_loaded{}get_group_of_field()
	* lct_Avada_admin{}wp_enqueue_styles()
* Moved:
	* afwp_acf_base64_decode()

= 2022.1 =
*Release Date - 15 February 2022*

* WP v5.9 Ready
* Avada v7.6 Ready
* New Filter:
	* lct/acf_form/shortcode_atts
* Added:
	* add_action( 'acf/input/form_data', [ $this, 'add_custom_form_data' ] );
	* lct_acf_default_value()
	* lct_acf_default_value_pre_render()
	* add_filter( 'is_protected_endpoint', '__return_true', 99999 );
	* add_filter( 'recovery_mode_email', 'lct_recovery_mode_email', 99999, 2 );
	* add_filter( 'recovery_mode_email', 'lct_mu_recovery_mode_email', 99999, 2 );
	* lct_admin_admin{}force_html_emails()
	* lct_admin_admin{}force_email_tag_scale()
	* lct_admin_admin{}force_email_tag_filter()
	* add_filter( 'wp_mail_content_type', [ $this, 'return_html' ], 99999 );
	* add_filter( 'new_admin_email_content', [ $this, 'force_email_html_w_content' ], 99 );
	* add_filter( 'wp_installed_email', [ $this, 'force_email_html_w_mail' ], 99 );
	* add_filter( 'wp_new_user_notification_email_admin', [ $this, 'force_email_html_w_mail' ], 99 );
	* add_filter( 'wp_new_user_notification_email_admin', [ $this, 'force_email_subject_tag' ], 99 );
	* add_filter( 'wp_new_user_notification_email_admin', [ $this, 'force_email_tag_1' ], 99999 );
	* add_filter( 'auto_core_update_email', [ $this, 'force_email_html_w_mail' ], 99 );
	* add_filter( 'auto_core_update_email', [ $this, 'force_email_subject_tag' ], 99 );
	* add_filter( 'auto_core_update_email', [ $this, 'force_email_tag_8' ], 99999 );
	* add_filter( 'auto_plugin_theme_update_email', [ $this, 'force_email_html_w_mail' ], 99 );
	* add_filter( 'auto_plugin_theme_update_email', [ $this, 'force_email_subject_tag' ], 99 );
	* add_filter( 'auto_plugin_theme_update_email', [ $this, 'force_email_tag_8' ], 99999 );
	* add_filter( 'automatic_updates_debug_email', [ $this, 'force_email_html_w_mail' ], 99 );
	* add_filter( 'automatic_updates_debug_email', [ $this, 'force_email_subject_tag' ], 99 );
	* add_filter( 'automatic_updates_debug_email', [ $this, 'force_email_tag_8' ], 99999 );
	* add_filter( 'site_admin_email_change_email', [ $this, 'force_email_html_w_mail' ], 99 );
	* add_filter( 'site_admin_email_change_email', [ $this, 'force_email_subject_tag' ], 99 );
	* add_filter( 'site_admin_email_change_email', [ $this, 'force_email_tag_8' ], 99999 );
	* add_filter( 'wp_password_change_notification_email', [ $this, 'force_email_html_w_mail' ], 99 );
	* add_filter( 'wp_password_change_notification_email', [ $this, 'force_email_subject_tag' ], 99 );
	* add_filter( 'wp_password_change_notification_email', [ $this, 'force_email_tag_1' ], 99999 );
	* add_filter( 'new_user_email_content', [ $this, 'force_email_html_w_content' ], 99 );
	* add_filter( 'retrieve_password_message', [ $this, 'force_email_html_w_content' ], 99 );
	* add_filter( 'user_request_action_email_content', [ $this, 'force_email_html_w_content' ], 99 );
	* add_filter( 'user_confirmed_action_email_content', [ $this, 'force_email_html_w_content' ], 99 );
	* add_filter( 'wp_privacy_personal_data_email_content', [ $this, 'force_email_html_w_content' ], 99 );
	* add_filter( 'wp_new_user_notification_email', [ $this, 'force_email_html_w_mail' ], 99 );
	* add_filter( 'email_change_email', [ $this, 'force_email_html_w_mail' ], 99 );
	* add_filter( 'email_change_email', [ $this, 'force_email_tag_1' ], 99999 );
	* add_filter( 'password_change_email', [ $this, 'force_email_html_w_mail' ], 99 );
	* add_filter( 'password_change_email', [ $this, 'force_email_tag_1' ], 99999 );
* Updated:
	* PDER{}send_ereminder()
	* lct_avada_template_version_router()
	* lct_acf_form2()
	* [lct_acf_form2]
	* lct_features_asset_loader{}admin_register_main_scripts()
	* lct_features_asset_loader{}wp_head_last()
	* lct_get_fixes_cleanups_message___file_fix_editzz_or()
* Improved:
	* lct_api_class{}force_allow_unfiltered_html()
	* lct_api_class{}force_allow_cap_unfiltered_html()
	* lct_api_class{}load_taxonomy()
	* lct_acf_loaded{}save_references_accessed()
	* lct_get_field_post_id()
	* lct_acf_admin{}activate_license()
	* lct_acf_op_main_fixes_cleanups{}acf()
	* lct_acf_op_main_fixes_cleanups{}lct_cleanup_guid()
	* lct_acf_op_main_fixes_cleanups{}lct_cleanup_uploads()
	* lct_acf_op_main_fixes_cleanups{}repair_acf_repeater_metadata()
	* lct_acf_op_main_fixes_cleanups{}check_redirection_items()
	* lct_acf_op_main_fixes_cleanups{}lct_get_fixes_cleanups_message___db_fix_apmmp_5545()
	* lct_acf_op_main_fixes_cleanups{}lct_get_fixes_cleanups_message___lct_review_site_info()
	* lct_acf_format_value()
	* lct_acf_loaded{}
	* lct_acf_loaded{}set_fields()
	* lct_acf_loaded{}load_hooks()
	* lct_acf_display_form{}acf_form_head()
	* lct_acf_get_options_pages()
	* lct_wp_admin_admin_admin{}page_load_acf_tools()
	* lct_acf_filters_load_field{}process_shortcodes()
	* lct_set_Yoast_GA_settings()
	* lct_acf_form{}theme_chunk_iframe_json()
	* lct_taxonomies{}extend_quick_edit_post_status()
	* lct_acf_instant_save{}ajax_handler()
	* PDER_Admin{}delete_reminder()
	* PDER_Admin{}delete_reminders_many()
	* lct_features_theme_chunk{}ajax_handler()
	* acf_field_lct_json{}
	* acf_field_lct_send_password{}ajax_handler()
	* lct_asana{}refresh_token()
	* lct_Avada_admin{}add_yoast_ga_onclick()
	* lct_Avada_admin{}prevent_column_element_lazy_loading()
	* lct_Avada_admin{}prevent_container_element_lazy_loading()
	* lct_Avada_admin{}prevent_image_element_lazy_loading_deep()
	* lct_Avada_admin{}fusion_shortcode_content_fusion_imageframe()
	* lct_gforms_admin{}form_with_columns()
	* [lct_acf_display_value]
	* lct_wp_admin_acf_admin{}update_field()
	* lct_taxonomies{}disable_status_slug_editing_on_term()
	* lct_taxonomies{}disable_status_slug_editing()
	* lct_produce_shortcode()
	* lct_create_find_and_replace_arrays()
	* lct_admin_admin{}force_send_to_on_sb()
	* lct_wp_rocket_admin{}add_user_agent_check_when_cookie_not_set()
	* lct_acf_format_value_radio_display_format()
* Removed:
	* add_action( 'acf/include_fields', [ $this, 'create_local_field_key_reference_array' ], 999 );
	* add_filter( 'acf/pre_load_reference', [ $this, 'pre_load_reference_old' ], 9, 3 );
	* add_filter( 'acf/load_reference', [ $this, 'load_reference_old' ], 9, 3 );
	* lct_acf_loaded{}get_group_of_field_old()
	* lct_acf_admin{}get_field_reference()
	* lct_acf_disable_filters()
	* lct_acf_enable_filters()
	* lct_acf_admin{}set_object_terms()
	* lct_acf_admin{}deprecated()
	* lct_acf_termmeta{}
	* add_action( 'wp_loaded', [ $this, 'disable_fusion_widgets' ], 1 );

= 2021.6 =
*Release Date - 15 December 2021*

* WP v5.8.2 Ready
* Avada v7.5 Ready
* JS Tweaks
* Updated:
	* lct_avada_template_version_router()
	* lct_acf_admin{}activate_license()

= 2021.5 =
*Release Date - 09 December 2021*

* WP v5.7.3 Ready
* New Filter:
	* lct/get_the_date/post_id
	* lct/get_the_modified_date_time/post_id
* Improved:
	* ACF Fields
	* PDER{}send_ereminder()
	* lct_mu{}update_display_name()
	* lct_api_class{}load_status_of_post_type()
	* [lct_preload]
	* [lct_get_the_date]
	* [lct_get_the_modified_date_time]
	* [homeurl]
	* [homeurl_non_www]
	* lct_Avada_admin{}check_for_bad_avada_assets()
	* lct_wp_api_general{}
	* lct_wp_api_general{}do_shortcode()
* Updated:
	* lct_format_phone_number()
	* lct_strip_phone()
	* lct_wp_admin_acf_actions{}field_groups_columns_values()
	* lct_wp_admin_acf_admin{}field_groups_columns()
	* lct_acf_dev_checks{}default_plugins()

= 2021.4 =
*Release Date - 30 August 2021*

* WP v5.7.2 Ready
* Legacy Tracking Action:
	* lct_after_register_taxonomy
	* edit_term_taxonomy (WordPress)
	* edited_term_taxonomy (WordPress)
	* lct_jq_doc_ready_add #1
	* lct_jq_doc_ready_add #2
	* lct_jq_doc_ready_add #3
	* lct_jq_doc_ready_add #4
	* lct_jq_doc_ready_add #5
	* lct_jq_doc_ready_add #6
	* lct_jq_doc_ready_add #7
	* lct_jq_doc_ready_add #8
	* $deprecated_tag (lct_shutdown_deprecated_action())
	* lct/acf/new_post #1
	* lct/acf/new_post #2
	* lct/acf/before_lct_acf_form_full
	* lct/wp_head_last
	* lct_wp_footer_style_add #1
	* lct_wp_footer_style_add #2
	* lct_wp_footer_style_add #3
	* lct_wp_footer_style_add #4
	* lct_wp_footer_style_add #5
	* lct_wp_footer_style_add #6
	* lct_wp_footer_style_add #7
	* lct_wp_footer_style_add #8
	* lct_jq_autosize #1
	* lct_jq_autosize #2
	* lct_get_user_agent_info
	* lct_acf_single_load_google_fonts
	* lct_acf_single_load_adobe_typekit
	* woocommerce_created_customer_notification #1 (WooCommerce)
	* woocommerce_created_customer_notification #2 (WooCommerce)
	* woocommerce_reset_password_notification #1 (WooCommerce)
	* woocommerce_reset_password_notification #2 (WooCommerce)
	* lct/acf/instant_save/repeater_updated
	* lct/acf/instant_save/do_function_later
	* lct/op_main/init
	* lct/acf/display_form/type_clone
	* lct/acf/display_form/type_post_object
	* lct/acf/display_form/type_taxonomy
	* lct/acf/display_form/type_time_picker
	* lct/acf/display_form/type_user
	* lct/acf/display_form/type_zip_code
	* lct/acf/format_value/type_checkbox
	* lct/acf/format_value/type_date_picker
	* lct/acf/format_value/type_date_time_picker
	* lct/acf/format_value/type_post_object
	* lct/acf/format_value/type_radio
	* lct/acf/format_value/type_select
	* lct/acf/format_value/type_taxonomy
	* lct/acf/format_value/type_time_picker
	* lct/acf/format_value/type_true_false
	* lct/acf/format_value/type_user
	* lct/acf/format_value/type_zip_code
	* lct/acf/format_value/type_repeater
	* acf/create_field (ACF)
	* lct/acf/dev_report
	* lct/check_for_field_with_empty_names/loop_done
	* lct/set_version/update
* Updated:
	* lct_mu{}
	* lct_post_type_default_args()
	* lct_taxonomies{}default_args()
	* load_field_update_choices_clone()
	* load_field_update_choices()
	* lct_timer_end()
* Improved:
	* P_R_STYLE(); wrapped in function_exists()
	* lct_acf_loaded{}save_key_references()
	* lct_acf_loaded{}load_reference()
	* lct_acf_loaded{}load_reference_old()
	* lct_get_clean_term_id()
	* lct_get_post_content_fnr()
	* lct_get_acf_post_id()
* Removed:
	* do_action( 'lct_after_register_post_type', $post_type, $this );
	* lct_WP_Post_get_postmeta()
	* add_action( 'admin_init', [ $this, 'grant_super_admin' ] );

= 2021.3 =
*Release Date - 24 March 2021*

* WP v5.7 Ready
* Added:
	* add_filter( 'fusion_element_column_content', [ $this, 'reset_column_count' ], 2, 2 );
* Updated:
	* register_post_status(); Need an adjustment to work with v5.7 & wp_force_plain_post_permalink()
* Improved:
	* cache_key()

= 2021.2 =
*Release Date - 15 March 2021*

* Removed:
	* lct_admin_cron{}pimg_users()
* Added:
	* lct_admin_cron{}wp_users()

= 2021.1 =
*Release Date - 11 March 2021*

* WP v5.6.2 Ready
* Avada v7.2.1 Ready
* PHP v7.4 Ready
* CSS Tweaks
* Added FILTER:
	* lct/acf_hide_this/show_this; lct_acf_hide_this()
	* lct/acf/display_form/type_section_header/value; lct_acf_format_value()
	* lct/acf/format_value/type_section_header/value; lct_acf_format_value()
* New Action:
	* lct/acf/display_form/type_section_header
	* lct/acf/format_value/type_section_header
* Added:
	* add_yoast_ga_onclick()
* Updated:
	* lct_acf_hide_this()
	* lct_acf_format_value()
* Improved:
	* render_field_viewonly()
	* lct_acf_get_full_field_name()
	* PDER{}send_ereminders()
	* PDER{}send_ereminder()
	* lct_get_taxonomy_by_path()
	* lct_quick_send_email()
	* lct_acf_get_POST_value()

= 2020.14 =
*Release Date - 20 January 2021*

* Bug Fix:
	* lct_get_comments_number_by_type(); make comment query v5.5 ready
	* only_count_comments(); make comment query v5.5 ready
	* page_load_acf_tools()
* Added Filter:
	* lct/check_for_bad_youtubes/check_fusion
	* lct/disable_fusion_builder_activate/external_check
	* lct/acf/instant_save/pre_process_task
* Added:
	* add_filter( 'fusion_attr_image-shortcode-tag-element', [ $this, 'prevent_image_element_lazy_loading_deep' ], 999 );
	* add_filter( 'fusion_shortcode_content', [ $this, 'fusion_shortcode_content_fusion_imageframe' ], 10, 3 );
	* add_filter( 'fusion_element_column_content', [ $this, 'prevent_column_element_lazy_loading' ], 1, 2 );
	* add_filter( 'fusion_element_container_content', [ $this, 'prevent_container_element_lazy_loading' ], 1, 2 );
	* add_filter( 'rocket_post_purge_urls', [ $this, 'force_front_page_purge_prematurely' ], 10, 2 );
	* add_filter( 'rocket_clean_home_root', [ $this, 'force_front_page_purge_prematurely_2' ], 10, 3 );
	* add_action( 'current_screen', [ $this, 'page_load_acf_tools' ], 1 );
	* lct_previous_function()
	* lct_previous_function_deep()
	* lct_format_current_time_gmt()
	* lct_WP_Post_get_postmeta()
	* lct_WP_Post_update_postmeta()
	* lct_WP_Post_update_acf()
	* lct_get_edit_post_link()
	* [lct_url_site]
	* lct_url_site_wp_when_dev()
	* add_action( 'wp_loaded', [ $this, 'disable_fusion_widgets' ], 1 );
* Removed:
	* add_filter( 'rocket_preload_url_request_args', [ $this, 'preload_url_request_args' ] ); not needed anymore
* Improved:
	* header_layout(); privacy was hidden by mobile menu
	* lct_mu{}
	* mark_post_to_be_updated_later()
	* lct_get_later()
	* force_send_to_on_sb()
	* new_oauth_check()
	* lct_acf_display_value()
	* acf_field_lct_json{}
	* send_ereminder()
	* lct_generate_random_post_name()
	* acf_field_lct_json{}
	* lct_get_mobile_threshold()
	* lct_get_small_mobile_threshold()
	* lct_get_mobile_extreme_threshold()
	* wp_enqueue_styles()
	* lct_add_url_site_to_content()
	* [lct_get_the_modified_date_time]
	* [span]
* Updated:
	* process_shortcodes()
	* check_for_bad_youtubes(); Added filter
	* disable_fusion_builder_activate(); Added filter
	* lct_acf_form2()
	* form_shortcode()
	* process_pdf_fields()
	* lct_acf_instant_save(); Added filter

= 2020.13 =
*Release Date - 22nd October 2020*

* Bug Fix:
	* unique_id()
	* force_send_to_on_sb(); made compatible < PHP 7.4
* JS Updates:
	* instant_save.js
* Added Functions:
	* lct_is_empty()
	* lct_not_empty()
	* lct_acf_is_repeater_subfield()
	* lct_acf_validate_subfield_parent()
* Improved:
	* lct_acf_format_value()
	* lct_get_post_id()
	* lct_get_root_post_id()
* Updated:
	* lct_acf_instant_save{}ajax_handler()

= 2020.12 =
* WP v5.5.1 Ready
* Avada v7.0.2 Ready
* Improved: [theme_chunk]
* Improved: lct_features_theme_chunk{}fast_ajax()
* Improved: lct_features_theme_chunk{}ajax_handler()
* Improved: disable_optional_modules()

= 2020.11 =
* CSS Tweaks
* Improved: PDER{}get_ereminder()
* Improved: PDER{}send_ereminder()
* WP Rocket v3.7.0.1 Ready
* Updated: lct_sb_prefixes()
* Updated: lct_pder_get_email_template()
* Added: lct_count_filter()
* Updated: lct_quick_send_email()
* Added: lct_current_user_can_caps()
* Improved: lct_deprecated_error_log()
* Deprecated FILTER: lct_get_comment_type_lct_audit_settings
* Improved: get_cnst()
* Improved: lct_get_terms()
* Improved: lct_get_users()
* Added: lct_get_org_meta_query()
* Improved: lct_set_plugin()
* Improved: lct_plugin_default_args()
* Improved: lct_get_plugin_setting()
* Improved: lct_update_plugin_setting()
* Improved: lct_plugin_active()
* Improved: lct_plugin_version()
* Improved: lct_set_Yoast_GA_settings()
* Added FILTER: lct/get_comment_type_audit_settings
* Added FILTER: lct/get_comment_type_settings
* Added: lct_get_comment_type_settings()
* Improved: [lct_preload]
* Updated: [lct_get_the_id]
* Improved: [lct_current_year]
* Improved: check_restrictions_by_taxonomy()
* Improved: render_field_viewonly()
* Added: lct_acf_current_user_can_edit_field()
* Improved: lct_acf_form2()
* Improved: lct_acf_format_value()
* Improved: lct_acf_format_value_radio_display_format()
* Improved: lct_acf_format_value_post_object()
* Added: lct_acf_format_value_checkbox()
* Improved: lct_acf_format_value_taxonomy()
* Added: lct_acf_format_value_zip_code()
* Improved: acf_field_lct_zip_code{}render_field()
* Improved: acf_field_lct_phone{}render_field()
* Updated: save_key_references()
* Improved: load_reference()
* Improved: load_reference_old()
* Added: lct_acf_ajax_send_user_login_invite{}
* Added: add_filter( 'lct/lct_acf_instant_save/add_comment/user', [ $this, 'add_comment_user_is_cron' ], 999 );
* Updated: lct_acf_instant_save{}ajax_handler()
* Improved: lct_acf_instant_save{}add_comment()
* Added: 'FILTER' lct/lct_acf_instant_save/add_comment/user
* Updated: add_user_agent_check_when_cookie_not_set()
* Improved: disable_site_status_tests()

= 2020.9 =
* WP v5.4.2 Ready
* Improved: header_layout()
* Improved: lct_mobi_contact_button()
* Improved: non_ajax_add_comment()
* Improved: lct_update_status_taxonomy_term_count()

= 2020.8 =
* WP v5.4.1 Ready
* Improved: lct_script_protector();

= 2020.7 =
* Avada v6.2.2 Ready
* WP v5.4.1 Ready
* WP Rocket v3.5.5.1 Ready
* CSS Tweaks
* New Action:
	* lct/avada_main_menu
* Improved: PDER{}
* Improved: send_ereminder()
* Improved: delete_reminders_many()
* Added: add_filter( 'wp_mail', [ $this, 'force_send_to_on_sb' ] );
* Improved: lct_mu{}
* Improved: lct_quick_send_email()
* Improved: lct_get_rel_tax_id()
* Added: lct_check_post_type_match()
* Improved: lct_features_class_mail{}
* Improved: lct_acf_form2()
* Improved: lct_acf_format_value_user()
* Improved: lct_acf_instant_save{}ajax_handler()
* Improved: add_comment()
* Improved: non_ajax_add_comment()
* Updated: lct_cleanup_uploads()
* Improved: cleanup_guid_link_cleanup()
* ACF Field Updates; for LCT Audit Settings
* Added: lct_wp_mail_smtp_admin{}
* Added: add_filter( 'wp_mail_smtp_options_get', [ $this, 'disable_smtp_on_dev' ], 10, 3 );
* Updated: create_menu(); removed dashboard link

= 2020.6 =
* Avada v6.2.1 Ready
* JS Tweaks
* Improved: lct_wpdb_prepare_in()
* Improved: lct_add_rel_term()
* Added: lct_array_flatten()
* Added: lct_array_flatten_unique()
* Added: lct_is_not_null()
* Updated: lct_avada_template_version_router(); Avada v6.2.1 Ready
* Improved: [lct_lazy_youtube]

= 2020.5 =
* Improved: lct_get_field_post_id()
* Added: lct_clean_acf_repeater()
* Added: lct_find_repeater_field()
* Improved: lct_acf_form2()
* Improved: prepare_fields_for_import()
* Improved: [lct_mobi_call_button]
* Improved: [lct_mobi_book_appt_button]
* Improved: [lct_mobi_findus_button]
* Improved: [lct_fixed_buttons]
* Improved: [lct_mobi_home_button]
* Added: lct_acf_ajax_save_repeater_after_remove{}
* Added: lct_acf_ajax_save_repeater_after_remove{}check_acf_repeater()
* Updated: default_plugins(); Added 'wp-smushit' & removed 'ewww-image-optimizer'
* Updated: default_plugins(); Added 'wp-mail-smtp'
* Updated: load_field_update_choices()
* Removed: jquery_main_vars, jquery_main_vars_no_field_check, jquery_ready_conditional_logic_type, jquery_conditional_logic_hide_field, admin_jquery_main_vars, admin_jquery_label, admin_jquery_required, admin_jquery_display_format, admin_jquery_choices, admin_jquery_ready_conditional_logic_type, admin_jquery_conditional_logic_type
* Updated: form_data_post_id_ajax()
* Improved: lct_acf_instant_save{}ajax_handler()
* Improved: lct_get_post_id()
* Added: lct_get_root_post_id()
* Improved: lct_get_acf_post_id()
* Updated: [faicon]
* Improved: prepare_field_access_primary()
* Updated: wp_head_last()
* Improved: lct_features_theme_chunk{}wp_enqueue_scripts()
* Improved: lct_features_theme_chunk{}ajax_handler()
* Added: lct_wp_api_api{}
* Added: lct_wp_api_general{}
* Added: ../assets/js/plugins/acf/front.min.js
* Added: ../assets/js/helpers.min.js
* Improved: instant_save.min.js
* Improved: theme_chunk.min.js

= 2020.4 =
* Improved: lct_acf_loaded{}load_hooks(); Prevent from running on update

= 2020.3 =
* ACF Field Changes
* Added: remove_action( 'plugins_loaded', '_wp_add_additional_image_sizes', 0 );
* Improved: avada_main_menu_v5()
* Improved: avada_main_menu_v5_1()
* Improved: avada_main_menu_v5_4()
* Improved: wp_enqueue_scripts()
* Improved: header_layout()
* Added: add_filter( 'wp_check_filetype_and_ext', [ $this, 'check_for_needed_filetype' ], 10, 4 );
* Improved: lct_get_current_user_role_display()
* Added: add_filter( 'lct/acf_form/post_id', [ $this, 'set_acf_form_post_id_for_author_page' ], 16, 2 );
* Improved: pretty_state_list()
* Added: add_filter( 'get_post_status', [ $this, 'acf_post_status_check' ], 10, 2 );
* Improved: lct_timer_end()
* Improved: lct_acf_form2()
* Added: add_filter( 'acf/fields/post_object/query', [ $this, 'update_posts_per_page' ], 10, 3 );
* Added: add_filter( 'acf/acf_get_hidden_input/attrs', [ $this, 'unique_id' ] );
* Added: lct_rand_short()
* Improved: lct_rand()
* Improved: instant_save.js
* Improved: register_main_scripts()
* Improved: load_reference()
* Improved: lct_acf_instant_save{}ajax_handler()
* Gulp v4 Ready

= 2020.2 =
* No Updates

= 2020.1 =
* WP Rocket v3.4.4 Ready
* Avada v6.1.2 Ready
* New Filter:
	* lct/access/wp_nav_menu_objects/pre_check_unset
* Updated: instant_save.js
* Improved: lct_acf_format_value()
* Improved: acf_field_lct_json{}
* Updated: lct_acf_instant_save{}ajax_handler()
* Improved: emergency_hack_checker_unworthy_recheck()
* Updated: register_post_status()
* Added: add_action( "admin_footer-{$pagenow}", [ $this, 'extend_quick_edit_post_status' ] );
* Updated: get_field_label()
* Improved: lct_update_status_taxonomy_term_count()
* Improved: lct_add_rel_term()

= 2019.31 =
* Updated: emergency_hack_checker_unworthy_recheck()
* Improved: lct_acf_format_value_user()
* Improved: lct_add_rel_term()
* Improved: lct_quick_send_email()

= 2019.29 =
* New Action:
	* lct/emergency_hack_checker/unworthy_recheck
* Added: add_filter( 'acf/prepare_field_group_for_export', [ $this, 'add_menu_order_to_fields' ] );
* Added: add_menu_order_loop_fields()
* Improved: lct_acf_is_field_group_editing_page()
* Improved: load_admin()
* Improved: db_status_options_ignore_names()
* Improved: page_load_acf_tools()
* Improved: db_status_postmeta_ignore_keys()
* Improved: db_status_usermeta_ignore_keys()
* Improved: load_field_update_choices()
* Improved: load_field_update_choices_clone()
* Improved: css_files()
* Improved: js_files()
* Improved: modify_clone()
* Improved: process_shortcodes()
* Improved: asana_workspaces_choices()
* Added: add_action( 'wp', [ $this, 'disable_admin_hooks_by_removal' ], 1 );
* Added: add_action( 'wp', [ $this, 'disable_by_removal_wp' ], 1 );
* Added: lct_get_status_obj_from_status_slug()
* Added: lct_get_status_name_from_status_slug()
* Improved: [theme_chunk]
* Updated: set_all_cnst()
* Added: lct_rel_tax()
* Added: lct_rel_post()
* Added: lct_add_rel_term()
* Added: lct_get_rel()
* Added: lct_get_rel_id()
* Added: lct_get_rel_post()
* Added: lct_get_rel_post_id()
* Added: lct_get_rel_tax()
* Added: lct_get_rel_tax_id()
* Added: add_action( 'lct_emergency_hack_checker', [ $this, 'emergency_hack_checker' ] );
* Added: add_action( 'lct/emergency_hack_checker/unworthy_recheck', [ $this, 'emergency_hack_checker_unworthy_recheck' ] );
* Added: status_worthy_commit()

= 2019.28 =
* Avada v6.1.2 Ready
* WP Rocket v3.4.2.2 Ready
* Updated: lct_acf_instant_save{}ajax_handler()
* Added 'filter': lct/acf/instant_save/final_response

= 2019.27 =
* Avada v6.1.1 Ready
* Added: lct_acf_display_value()
* Improved: lct_acf_format_value()
* Added: [lct_acf_display_value]
* Updated: [lct_show_if]
* Improved: lct_wp_redirect()
* Improved: lct_wp_safe_redirect()
* Added: lct_wp_safe_redirect_js()
* Updated: templates/menu-mobile-main.php
* Improved: [lct_mobi_overlay_menu_button]
* Improved: add_user()
* Added: add_filter( 'acf/fields/post_object/query', [ $this, 'update_status_filter' ], 10, 3 );
* Improved: lct_acf_get_field_groups_fields()
* Updated: lct_acf_public_choices{}load_hooks()
* Added: pretty_acf_fields_list_data()
* Added: pretty_acf_fields_list()

= 2019.26 =
* WP v5.3 Ready
* Avada v6.1.1 Ready
* JS Tweaks - instant_save
* WP Rocket v3.4.1.2 Ready
* Added: lct_prep_custom_WP_User_obj_to_array()
* Added: lct_get_all_user_meta()
* Added: lct_get_clean_user_id()
* Added: lct_get_role_name()
* Improved: lct_acf_get_field_group_of_field()
* Improved: lct_acf_get_POST_value()
* Improved: load_reference()
* Improved: plugins_n_files()
* Improved: modified_posts()
* Improved: lct_acf_instant_save{}ajax_handler()
* Improved: lct_avada_template_version_router()
* Updated: lct_wp_admin_admin_loader{}load_admin()

= 2019.25 =
* New Action:
	* lct/is_cache_disabled/cache_flush
* WP v5.2.4 Ready
* Avada v6.0.3 Ready
* JS Tweaks
* UD .htaccess
* Added: add_action( 'set_current_user', [ $this, 'update_display_name' ] );
* Added: update_names()
* Added: lct_get_current_user_role_display()
* Removed: template overrides for logo.php
* Improved: lct_avada_default_overrider()
* Added: lct_avada_template_version_router()
* Updated: get_instance()
* Improved: pre_load_reference()
* Added: save_references_accessed()
* Improved: load_reference()
* Added: lct_delete_meta_cache()
* Added: lct_delete_post_meta_cache()
* Added: lct_delete_term_meta_cache()
* Added: lct_get_clean_post_id()
* Added: lct_get_clean_term_id()
* Improved: lct_prep_custom_WP_Post_obj_to_array()
* Added: lct_get_date_from_date()
* Added: lct_get_date_from_date_gmt()
* Updated: lct_get_json_thru_curl()
* Added: lct_acf_get_field_group_of_field()
* Added: ACF Field 'JSON Data'
* Improved: lct_acf_get_POST_values_w_selector_key()
* Added: lct_produce_shortcode()
* Updated: lct_acf_form2()
* Updated: [lct_acf_form2]
* Improved: lct_acf_instant_save{}ajax_handler()
* Improved: lct_features_theme_chunk{}ajax_handler()
* Updated: prepare_fields_for_import()
* Added: add_action( 'acf/render_field_settings/type=time_picker', [ $this, 'render_field_settings_time_picker' ] );
* Added: lct_is_cache_disabled()
* Improved: [lct_mobi_flyout_menu_button]
* Improved: [lct_scroll_arrow]
* Updated: [lct_mobi_overlay_menu_button]
* Improved: lct_acf_instant_save{}ajax_handler()

= 2019.24 =
* JS Tweaks
* Improved: lct_get_fixes_cleanups_message___lct_review_site_info()
* Improved: lct_remove_site_root_all()
* Improved: fusion_options_saved()
* Updated: lct_acf_form2()
* Updated: [lct_acf_form2]

= 2019.23 =
* CSS Tweaks
* Improved: database_status_options()
* Updated: lct_close_all_pings_and_comments()
* Improved: login_bypass()
* Moved: add_action( 'admin_init', [ $this, 'set_login' ] );
* Removed: lct_wp_admin_wps_hide_login_admin{}
* Added: lct_wps_hide_login_loaded{}
* Added: add_filter( 'site_url', [ $this, 'site_url' ], 5, 4 );
* Added: add_filter( 'network_site_url', [ $this, 'network_site_url' ], 5, 3 );
* Added: add_filter( 'wp_redirect', [ $this, 'wp_redirect' ], 5, 2 );
* Added: filter_wp_login_php()
* Added: lct_stream_admin{}
* Added: add_filter( 'wp_stream_alert_trigger_check', [ $this, 'trigger_check' ], 10, 4 );
* Added: add_action( 'upload_mimes', [ $this, 'add_file_types_to_uploads' ] );
* Improved: [lct_phone]
* Added: lct_acf_get_menu_button_class()
* Added: lct_acf_get_mobi_nav_colors()
* Improved: [lct_mobi_call_button]
* Improved: [lct_mobi_book_appt_button]
* Improved: [lct_mobi_contact_button]
* Improved: [lct_mobi_findus_button]
* Added: lct_acf_get_specific_mobi_nav_color()
* Improved: [lct_mobi_home_button]
* Improved: [lct_findus_button]
* Improved: [lct_contact_button]
* Improved: [lct_book_appt_button]
* Improved: [lct_mobi_menu_button]
* Improved: [lct_mobi_slide_menu_button]
* Improved: [lct_mobi_overlay_menu_button]
* Added: [lct_mobi_flyout_menu_button]
* Improved: header_layout()
* Improved: lct_update_later()

= 2019.22 =
* Improved: load_reference()
* Improved: cleanup_guid_link_cleanup()
* Improved: initial_tasks()
* Added: default_plugins()
* Added: update_plugin_details()
* Updated: plugins_n_files()
* Improved: database_status_options()
* Renamed: lct_admin_menu_editor_action{} TO lct_wp_admin_admin_menu_editor_action{}
* Improved: update_options_to_desired_settings()
* Updated: [lct_acf_form2]
* Updated: lct_acf_form2()

= 2019.21 =
* Improved: lct_get_street_address()
* Improved: get_field()
* Improved: lct_load_class()
* Added: lct_asana{}
* Added: lct_asana_acf{}
* Added: add_filter( 'acf/load_field/name=' . zxzacf( 'asana::workspaces' ), [ $this, 'asana_workspaces_choices' ] );

= 2019.19 =
* ACF Fields
* Added: add_filter( 'site_status_tests', [ $this, 'disable_site_status_tests' ] );
* Added: add_filter( 'site_status_test_php_modules', [ $this, 'disable_optional_modules' ] );
* Improved: acf_include_field_types()
* Improved: acf_field_lct_dev_report{}
* Removed: acf_field_lct_modified_posts{}
* Renamed: dev_report() TO plugins_n_files()
* Added: add_action( 'lct/acf/dev_report', [ $this, 'modified_posts' ] );
* Removed: add_action( 'lct/acf/modified_posts', [ $this, 'modified_posts' ] );
* Added: add_action( 'lct/acf/dev_report', [ $this, 'database_status_options' ] );
* Improved: exclude_field_type()
* Improved: check_for_field_with_empty_names()
* Improved: check_for_field_issues()
* Added: lct_array_to_quoted_string()
* Added: add_filter( 'acf/load_field/name=' . zxzacf( 'db_status::options::ignore_names' ), [ $this, 'db_status_options_ignore_names' ] );
* Added: add_filter( 'acf/update_value/name=_validate_email', '__return_null', 10, 3 );
* Added: add_action( 'lct/acf/dev_report', [ $this, 'database_status_postmeta' ] );
* Added: add_action( 'lct/acf/dev_report', [ $this, 'database_status_usermeta' ] );
* Added: add_filter( 'acf/load_field/name=' . zxzacf( 'db_status::postmeta::ignore_keys' ), [ $this, 'db_status_postmeta_ignore_keys' ] );
* Added: add_filter( 'acf/load_field/name=' . zxzacf( 'db_status::usermeta::ignore_keys' ), [ $this, 'db_status_usermeta_ignore_keys' ] );

= 2019.18 =
* Improved: lct{}
* Improved: lct{}init()
* Improved: lct{}plugins_loaded_first()
* Improved: lct{}load_classes()
* Added: lct{}has_setting()
* Improved: lct{}get_setting()
* Improved: lct{}update_setting()
* Added: lct{}get_data()
* Added: lct{}set_data()
* Improved: lct_disable_cache()
* Improved: lct_enable_cache()
* Improved: lct_set_cache()
* Improved: lct_delete_cache()
* Improved: lct_delete_cache_all()
* Added: lct_has_setting()
* Added: lct_raw_setting()
* Added: lct_validate_setting()
* Improved: lct_get_setting()
* Improved: lct_update_setting()
* Improved: lct_append_setting()
* Added: lct_get_data()
* Added: lct_set_data()
* Added: lct_append_data()
* Improved: lct_get_path()
* Improved: lct_get_root_path()
* Improved: lct_get_url()
* Improved: lct_get_root_url()
* Improved: lct_load_class()
* Improved: lct_load_class_default_args()
* Improved: lct_did()
* Improved: lct_undid()
* Improved: lct_get_later()
* Improved: lct_update_later()
* Improved: lct_append_later()
* Added: lct_instances{}
* Improved: lct_set_plugin()
* Improved: lct_get_plugin_setting()
* Improved: lct_update_plugin_setting()
* Improved: lct_plugin_active()
* Improved: lct_plugin_version()
* Improved: lct_get_city()
* Improved: lct_get_zip()
* Improved: lct_get_state()
* Improved: lct_get_full_address()
* Improved: lct_acf_get_POST_value()
* Added: add_filter( 'acf/update_value/name=' . zxzacf( 'google_map_api' ), [ $this, 'google_map_api' ], 10, 3 );
* Improved: lct_cleanup_guid()
* Improved: lct_cleanup_uploads()
* Improved: load_admin()
* Added: add_action( 'admin_init', [ $this, 'move_attachments' ] );

= 2019.17 =
* Improved: get_field()
* Added: lct_prep_custom_WP_Post_obj_to_array()
* Added: lct_acf_format_value_from_selector()

= 2019.16 =
* Bug Fix: remove_theme_supports(); can't get default value before init action

= 2019.15 =
* Renamed: updated_postmeta_update_post() TO mark_posts_as_updated_with_postmeta_changes()
* Renamed: updated_postmeta() TO mark_post_to_be_updated_later()
* Improved: mark_posts_as_updated_with_postmeta_changes()
* Improved: mark_post_to_be_updated_later()
* Added: add_action( 'plugins_loaded', [ $this, 'prep_shutdown' ], 1 );
* Added: add_action( 'wp_update_nav_menu', [ $this, 'clear_menu_cache_when_nav_menu_is_saved' ], 10, 2 );
* Added: add_action( 'post_updated', [ $this, 'clear_menu_cache_when_post_is_saved' ], 10, 3 );
* Improved: lct_features_nav_menu_cache{}cache_key()

= 2019.14 =
* Added: add_action( 'after_setup_theme', [ $this, 'remove_theme_supports' ], 11 );

= 2019.13 =
* WP v5.2.2 Ready
* Improved: get_field()
* Improved: lct_get_DateTime_from_date()
* Improved: acf_actions_n_filters()
* Bug Fix: lct_is_wpdev()

= 2019.12 =
* WP v5.2.1 Ready
* Avada v5.9.1 Ready
* Improved: lct_avada_default_overrider()

= 2019.11 =
* WP v5.2.1 Ready
* Added: add_action( 'admin_init', [ $this, 'scanner_postmeta' ] );
* Improved: db_looper()

= 2019.9 =
* WP v5.2 Ready
* Updated: lct_mu{}init(); Added cron support
* Bug Fix: load_status_of_post_type(); stopped the saving of term relationships when not needed
* Improved: strpos_array()
* Bug Fix: render_field_viewonly(); Don't disable repeaters
* Bug Fix: lct_get_field_post_id()
* Bug Fix: lct_acf_format_value()
* Improved: load_reference()
* Improved: get_group_of_field()
* Bug Fix: non_ajax_add_comment(); repeater get_field() was not working

= 2019.8 =
* Added lazyframe() support to Fusion modals

= 2019.7 =
* Improved: instant_save.js
* Updated: lct_taxonomies{}set_all_cnst(); Added cnst 'tax_status_slugs'
* Improved: lct_get_post_id()
* Improved: lct_make_status_slug()
* Updated: lct_get_acf_post_id()
* Added: add_action( 'acf/include_fields', [ $this, 'acf_actions_n_filters_pre' ], 9999 );
* Improved: acf_actions_n_filters()
* Added: add_filter( 'acf/update_value/name=' . lct_status(), [ $this, 'update_taxonomy_status' ], 999970, 3 );
* Added: add_filter( 'acf/load_value/name=' . lct_status(), [ $this, 'load_status_of_post_type' ], 10, 3 );
* Added: add_filter( 'acf/load_value/type=taxonomy', [ $this, 'load_taxonomy' ], 9999, 3 );
* Added: add_filter( 'acf/update_value/type=taxonomy', [ $this, 'update_taxonomy' ], 999980, 3 );
* Added: add_filter( 'acf/update_value', [ $this, 'finish_taxonomy_update' ], 999999, 3 );
* Added: add_action( 'acf/save_post', [ $this, 'prevent_taxonomy_saving' ], 0 );
* Moved: add_action( 'shutdown', [ $this, 'do_update_field_later' ] );
* Moved: add_action( 'acf/save_post', [ $this, 'do_update_field_later' ], 100 );
* Deprecated: lct_acf_update_field_inside_comment()
* Improved: lct_is_new_save_post()
* Removed: lct_dont_save_terms_on_comments()
* Improved: render_field_viewonly()
* Removed: lct_update_status_of_post_type_also()
* Added: lct_acf_get_status_field_object()
* Added: lct_acf_get_status()
* Added: lct_acf_get_status_id()
* Added: lct_acf_update_status()
* Improved: lct_acf_format_value_true_false_display_format()
* Improved: lct_acf_get_before_save_values()
* Improved: lct_acf_get_before_save_value()
* Improved: lct_acf_get_repeater_array_values()
* Improved: lct_acf_get_POST_value()
* Added: lct_acf_get_POST_repeater_value()
* Improved: lct_acf_get_POST_values_w_selector_key()
* Added: lct_acf_is_selector_repeater()
* Added: lct_acf_get_old_field_value()
* Added: add_filter( 'acf/location/rule_match/post_type', [ $this, 'register_rule_match_post_type' ], 999, 3 );
* Added: add_filter( 'acf/location/rule_match/comment', [ $this, 'register_rule_match_comment' ], 999, 3 );
* Updated: load_reference()
* Removed: add_filter( 'acf/update_value/type=taxonomy', [ $this, 'wp_set_object_terms' ], 100, 3 );
* Updated: set_current_form()
* Improved: lct_acf_instant_save{}ajax_handler()
* Improved: lct_acf_instant_save{}add_comment()
* Improved: lct_acf_instant_save{}non_ajax_add_comment()
* Improved: check_all_fusion_pages_for_bad_avada_assets()
* Improved: register_rule_values_org()
* Updated: includes: lazyframe TO v1.1.6

= 2019.6 =
* New Action:
	* lct/acf/load_reference/pre_check_duplicate_failed
* Added: lct_array_not_unique()
* Improved: prepare_fields_for_import()
* Improved: save_key_references()
* Added: lct_acf_get_POST_values_w_selector_key()
* Improved: lct_acf_get_selector()
* Improved: lct_rand()
* Improved: wp_add_inline_script()
* Improved: wp_add_inline_style()
* Improved: lct_acf_form_full()
* Improved: lct_generate_random_post_name()
* Improved: lct_acf_form2()
* Improved: pretty_acf_field_groups_list_data()
* Improved: exhaustive_acf_field_groups_list_data()
* Added: add_action( 'lct/acf_form/before_acf_form', [ $this, 'set_current_form' ], 0 );
* Updated: lct_mu{}
* Improved: lct_get_cache()
* Updated: lct_isset_cache()
* Added: lct_array_replace()
* Improved: schedule_reminder()
* Improved: delete_reminder()
* Improved: mark_posts_as_updated_with_postmeta_changes()
* Improved: lct_pder_get_email_template()
* Added: lct_get_reminder()
* Added: lct_delete_all_post_meta()
* Added: lct_delete_all_post_meta_by_post_ids()
* Updated: strpos_array()
* Updated: lct_get_all_metadata()
* Updated: lct_get_all_post_meta()
* Updated: lct_get_all_term_meta()
* Improved: register_rule_match_options_page()
* Improved: register_rule_match_lct_org()
* Improved: load_reference()
* Added: FILTER 'lct/acf/load_reference/pre_check_duplicate'
* Improved: register_screen()

= 2019.5 =
* Avada v5.8.2 Ready
* Improved: lct_update_meta_cache()
* Improved: lct_get_meta_cache()
* Added: lct_update_term_meta_cache()
* Added: lct_get_term_meta_cache()
* Improved: lct_get_term_id_or_create_n_get_term_id()

= 2019.4 =
* WP v5.1 Ready
* WP v5.1.1 Ready
* JS Tweaks; instant_save.min.js
* CSS Tweaks
* Improved: lct_taxonomies{}cache_key()
* Added: lct_meta_cache_key()
* Added: lct_update_meta_cache()
* Added: lct_update_post_meta_cache()
* Added: lct_get_meta_cache()
* Added: lct_get_post_meta_cache()
* Changed: add_action( 'acf/init', [ $this, 'create_local_field_key_reference_array' ], 3 ); TO 'acf/include_fields'
* Improved: create_local_field_key_reference_array()
* Removed: add_action( 'acf/init', [ $this, 'create_acf_by_selector' ], 9 );
* Moved: add_action( 'load-update-core.php', [ $this, 'autoload_checker' ] );
* Added: add_action( 'acf/init', [ $this, 'acf_actions_n_filters' ], 999 );
* Improved: autoload_checker()
* Added: FILTER 'lct/autoload_checker/force_no'
* Added: lct_key_reference()
* Added: lct_duplicate_names()
* Added: add_action( 'set_object_terms', 'lct_dont_save_terms_on_comments', 10, 6 );
* Improved: update_field_group()
* Improved: lct_features_nav_menu_cache{}cache_key()
* Added: lct_acf_get_POST_value()
* Added: lct_acf_get_POST_instant_selector()
* Added: lct_acf_get_POST_instant_value()
* Added: lct_acf_get_selector()
* Added: lct_acf_get_options_pages()
* Improved: dev_report()
* Improved: lct_acf_instant_save{}__construct()
* Improved: lct_acf_instant_save{}ajax_handler()
* Improved: lct_acf_instant_save{}add_comment()
* Improved: lct_acf_op_main{}load_hooks()
* Moved: add_action( 'lct/op_main/init', [ $this, 'add_op_main_Avada' ] );
* Improved: set_fields()
* Improved: pre_load_reference_old()
* Improved: load_reference_old()
* Improved: disable_fusion_builder_activate()
* Added: lct_gforms_loaded{}
* Moved: add_action( 'lct/op_main/init', [ $this, 'add_op_main_gforms' ] );
* Moved: add_action( 'lct/op_main/init', [ $this, 'add_op_main_wc' ] );
* Improved: load_vars()
* Improved: load_admin()

= 2019.3 =
* Added: add_action( 'created_term', [ $this, 'clear_register_post_status_cache' ], 10, 3 );
* Added: add_action( 'edited_term', [ $this, 'clear_register_post_status_cache' ], 10, 3 );
* Improved: register_post_status()
* Added: cache_key();
* Disabled: add_filter( 'acf/get_fields', [ $this, 'acf_get_fields' ], 10, 2 );
* Improved: lct_acf_get_option()
* Added: add_filter( 'acf/update_value/type=repeater', [ $this, 'delete_option_repeater_cache' ], 999, 3 );
* Added: add_action( 'template_redirect', [ $this, 'remove_wp_admin_menu_items' ], 999 );
* Added: add_action( 'admin_init', [ $this, 'remove_wp_admin_menu_items' ], 999 );
* Improved: lct_remove_filter_like()
* Added: lct_remove_filter_like_2()
* Improved: lct_path_theme()
* Improved: lct_url_theme()
* lct_mu{}; REST API Ready
* Added: lct_mu{}api_checker()
* Added: lct_doing_api()
* Updated: register_main_scripts(); REST API Ready
* Updated: admin_register_main_scripts(); REST API Ready
* Bug Fix: timezone_adjust()
* Bug Fix: timezone_adjust_from_gmt()

= 2019.2 =
* Avada v5.8.1 Ready
* ACF v5.7.12 Ready
* Added: lct_doing_ajax()
* Added: lct_doing_autosave()
* Added: lct_doing_cron()
* Updated: lct_doing()
* Removed: wp_doing_cron(); Unneeded backup
* Changed: lct_wp_admin_admin_update{}load_hooks(); 'wp_doing_cron' to 'lct_doing_cron'
* Changed: lct_wp_admin_admin_update_extras{}load_hooks(); 'wp_doing_cron' to 'lct_doing_cron'
* Improved: force_update_db_values()
* Improved: cleanup_do_pings()
* Changed: lct{}load_classes(); 'wp_doing_cron' to 'lct_doing_cron'
* Added: add_action( 'add_post_metadata', [ $this, 'dont_save_pings' ], 10, 5 );
* Removed: add_action( 'load-update-core.php', [ $this, 'cleanup_do_pings' ] );
* Improved: lct_debug_to_error_log();
* Updated: included 'autosize' to v4.0.2
* Improved: lct_acf_admin{}wp_enqueue_scripts()
* Improved: register_main_scripts()
* Improved: admin_register_main_scripts()
* Improved: create_local_field_key_reference_array()
* Updated: lct_timer_end()
* Improved: lct_acf_op_main{}load_hooks()
* Added: add_action( 'acf/include_fields', [ $this, 'include_fields_plugins_Avada' ], 16 );
* Added: add_action( 'acf/include_fields', [ $this, 'include_fields_plugins_gforms' ], 16 );
* Added: add_action( 'acf/include_fields', [ $this, 'include_fields_plugins_wc' ], 16 );
* Improved: timezone_settings(); Prevent 500 error
* Overhaul: lct_acf_loaded{}
* Improved: lct_acf_loaded{}load_hooks()
* Updated: lct_acf_loaded{}set_fields()
* Added: add_filter( 'acf/prepare_fields_for_import', [ $this, 'prepare_fields_for_import' ], 9 );
* Added: lct_acf_loaded{}save_key_references()
* Updated: lct_acf_loaded{}pre_load_reference()
* Added: lct_acf_loaded{}pre_load_reference_old()
* Updated: lct_acf_loaded{}load_reference()
* Updated: lct_acf_loaded{}get_group_of_field()
* Added: add_filter( 'acf/load_field_group', [ $this, 'load_field_group' ], 9 );
* Deprecated: lct_acf_get_old_field()
* Deprecated: lct_acf_cache_delete()
* Deprecated: lct_acf_get_key_post_type()
* Deprecated: lct_acf_get_key_taxonomy()
* Deprecated: lct_acf_get_key_user()
* Moved: get_label()
* Moved: the_label()
* Improved: lct_acf_enable_filters()
* Improved: lct_acf_format_value()
* Added: lct_features_nav_menu_cache{}

= 2019.1 =
* WP v5.0.3 Ready
* CSS Tweaks
* JS Tweaks; instant_save.min.js
* ACF Field Tweaks
* Removed: add_filter( 'theme_page_templates', [ $this, 'theme_page_templates' ], 5, 4 ); was inactive
* Improved: lct_admin_admin{}do_function_later()
* Improved: lct_mu{}ajax_checker()
* Improved: lct_admin_time{}timezone_settings()
* Improved: get_the_date()
* Improved: get_post_modified_time()
* Improved: lct_cache_key()
* Updated: lct_update_reminder()
* Added: lct_DateTime()
* Added: lct_current_time()
* Added: lct_format_current_time()
* Updated: lct_format_date()
* Added: lct_display_timezone()
* Added: lct_update_post_title()
* Bug Fix: lct_get_acf_post_id(); Missing check for $_POST['_acf_post_id']
* Added: lct_do_function_later()
* Added: lct_rand()
* Improved: create_local_field_key_reference_array()
* Added: lct_get_dollar_wo_symbol()
* Improved: lct_get_user_agent_info()
* Added: lct_get_DateTime_today()
* Improved: lct_get_today()
* Added: lct_get_today_gmt()
* Added: lct_get_today_end()
* Added: lct_get_DateTime_from_today()
* Improved: lct_get_day_from_today()
* Added: lct_get_day_from_today_gmt()
* Added: lct_get_day_from_today_end()
* Added: lct_get_date_from_today()
* Added: lct_get_DateTime_from_date()
* Added: lct_get_day_from_date()
* Added: lct_get_day_from_date_gmt()
* Added: lct_get_all_post_meta()
* Bug Fix: lct_acf_update_field_inside_comment(); Post Status was not being updated
* Improved: lct_geocode()
* Improved: lct_get_the_slug()
* Bug Fix & Moved: lct_send_function_check_email()
* Improved: render_field_viewonly()
* Updated: lct_enqueue()
* Updated: wp_head_last()
* Updated: register_main_styles()
* Updated: register_main_scripts()
* Updated: admin_register_main_styles()
* Bug Fix: admin_register_main_scripts()
* Improved: lct_features_theme_chunk{}wp_enqueue_scripts()
* Improved: lct_features_theme_chunk{}ajax_handler()
* Added: lct_update_status_of_post_type_also()
* Updated: lct_acf_format_value()
* Added: lct_acf_format_value_true_false_display_format()
* Added: lct_acf_format_value_radio_display_format()
* Improved: lct_acf_get_repeater_array()
* Improved: lct_acf_get_repeater_array_keys()
* Improved: lct_acf_get_repeater_array_key()
* Added: lct_acf_get_repeater_array_values()
* Added: lct_acf_get_repeater_array_value()
* Added: lct_acf_get_repeater_array_value_by_slug()
* Improved: input_admin_enqueue_scripts{}acf_field_lct_send_password()
* Improved: activate_license()
* Improved: set_object_terms()
* Improved: pre_load_reference()
* Improved: load_reference()
* Improved: [lct_copyright]
* Updated: [lct_mobi_call_button]; Added ALT tags to images
* Updated: [lct_mobi_book_appt_button]; Added ALT tags to images
* Updated: [lct_mobi_findus_button]; Added ALT tags to images
* Updated: [lct_fixed_buttons]; Added ALT tags to images
* Updated: [lct_mobi_home_button]; Added ALT tags to images
* Improved: lct_acf_dev_checks{}modified_posts()
* Removed: add_filter( 'acf/update_value/key=_validate_email', [ $this, 'damn_validate_email' ], 99, 3 );
* Added: add_filter( 'acf/update_value/type=date_picker', [ $this, 'timezone_adjust' ], 100, 3 );
* Added: add_filter( 'acf/update_value/type=time_picker', [ $this, 'timezone_adjust' ], 100, 3 );
* Added: add_filter( 'acf/load_value/type=date_picker', [ $this, 'timezone_adjust_from_gmt' ], 100, 3 );
* Added: add_filter( 'acf/load_value/type=date_time_picker', [ $this, 'timezone_adjust_from_gmt' ], 100, 3 );
* Added: add_filter( 'acf/load_value/type=time_picker', [ $this, 'timezone_adjust_from_gmt' ], 100, 3 );
* Improved: timezone_adjust()
* Improved: update_status_of_post_type_also()
* Removed: add_action( 'acf/input/form_data', [ $this, 'form_data_post_id' ] );
* Improved: form_data_post_id_ajax()
* Improved: [lct_acf_form2]
* Improved: form_data_nested_field_check()
* Added: add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
* Added: add_action( 'lct/acf/instant_save/do_function_later', 'lct_do_function_later', 11 );
* Added: add_action( 'acf/delete_value', [ $this, 'tag_as_deleted' ], 10, 3 );
* Improved: lct_acf_instant_save{}wp_enqueue_scripts()
* Updated: lct_acf_instant_save{}ajax_handler()
* Improved: lct_acf_instant_save{}add_comment()
* Added: pretty_months_leading_zero()
* Improved: fusion_dynamic_css_posts()
* Improved: q2w3_fixed_widget_js_override()
* Improved: get_field_label()
* Improved: drupal_redirect_mapper()

= 2018.73 =
* CSS Tweaks
* Improved: load_reference()
* Added: FILTER 'lct/acf/load_reference/unique_post_types'

= 2018.72 =
* Updated: included 'iframe_resizer' to v3.6.3
* Updated: [lct_iframe]
* Improved: [lct_scroll_arrow]

= 2018.71 =
* WP v5.0.2 Ready
* Improved: send_ereminder()
* Improved: schedule_reminder()
* Update to _editzz-v8.1
* Improved: lct_update_reminder()
* Improved: lct_acf_form2()
* Improved: lct_acf_format_value_post_object()
* Improved: load_reference()
* Improved: prepare_field_add_class_selector()
* Updated: wp_sweep_details()
* Added: detail_compiler()

= 2018.69 =
* Update to _editzz-v8.1
* Avada v5.7.2 Ready
* Improved: page_load_acf_tools()
* Improved: load_edit()
* ACF Field Updates
* Updated: [lct_copyright]
* Improved: lct_cleanup_guid()
* Updated: check_for_bad_avada_assets()
* Added FILTER: 'lct/avada/check_for_bad_avada_assets/custom_css'
* Improved: send_ereminder()
* Improved: lct_add_url_site_to_content()
* Improved: lct_get_plugin_setting()

= 2018.68 =
* Improved: lct_did()
* Improved: lct_undid()
* Improved: lct_acf_get_field_groups_fields()
* Added: pretty_months_data()
* Added: pretty_months()

= 2018.67 =
* Avada v5.7.1 Ready :: Templates

= 2018.66 =
* Avada v5.7.1 Ready

= 2018.65 =
* CSS Tweaks
* JS Tweaks
* MU Tweaks
* ACF Field Tweaks
* Added: add_action( 'delete_attachment', [ $this, 'delete_attachment' ] );
* Added: lct_get_posts_with_image()
* Added: lct_get_featured_image_posts_with_image()
* Added: lct_get_postmetas_with_image()
* Added: lct_get_termmetas_with_image()
* Added: lct_get_usermetas_with_image()
* Added: lct_get_options_with_image()
* Updated: register_main_scripts()
* Updated: [theme_chunk]
* Improved: [lct_copyright]
* Improved: lct_cleanup_guid()
* Rewrote: lct_cleanup_uploads()
* Added: get_all_upload_files()
* Added: check_against_uploads()
* Improved: lct_Avada_admin{}wp_enqueue_styles()
* Improved: remove_image_size()
* Added: add_filter( 'woocommerce_get_image_size_single', [ $this, 'update_image_size' ] );
* Added: add_filter( 'woocommerce_get_image_size_gallery_thumbnail', [ $this, 'update_image_size' ] );
* Added: add_filter( 'woocommerce_get_image_size_thumbnail', [ $this, 'update_image_size' ] );
* Renamed: add_action( 'init', [ $this, 'remove_image_size' ], 11 ); FROM add_action( 'after_setup_theme', [ $this, 'remove_image_size' ], 11 );
* Added: add_action( 'acf/init', [ $this, 'create_acf_by_selector' ], 9 );
* Updated: add_action( 'init', [ $this, 'register_post_type' ], 2 );
* Re-wrote: lct_admin_time{}
* Improved: lct_acf_get_option_raw()
* Tweaks: PDER{}
* Tweaks: current_time()
* Improved: lct_format_date()
* Improved: acf_get_fields()
* Improved: lct_get_today()
* Improved: lct_get_day_from_today()
* Added: lct_db_date_only_no_time_format()
* Added: lct_db_time_midnight_format()
* Added: lct_db_time_format_no_seconds()
* Improved: lct_acf_get_field_label()
* Improved: modified_posts()
* Improved: lct_cache_key()

= 2018.64 =
* Code Cleanup: isset() & empty()
* Added: add_action( 'admin_bar_menu', [ $this, 'add_post_id_to_admin_bar' ], 999 );
* Improved: process_nested_shortcode()
* Improved: render_field_viewonly()
* Improved: iframe_filters_to_keep()
* Improved: parse_query()
* Improved: lct_get_acf_post_id()
* Improved: lct_features_theme_chunk{}ajax_handler()
* Added: add_filter( 'body_class', [ $this, 'iframe_body_class' ], 20 );
* Bug Fix: disable_fusion_builder_activate(); fusion_builder_status setting not saving on post save
* Added: add_filter( 'acf/load_field/type=clone', [ $this, 'load_field_update_choices_clone' ] );
* Improved: [lct_copyright]; Added rel="nofollow"
* Improved: remove_image_size()
* Updated: lct_cleanup_uploads()
* Updated: MU
* Added: add_action( 'delete_attachment', [ $this, 'delete_attachment' ] );
* Updated: lct_cleanup_guid()
* Added: lct_get_posts_with_image()
* Added: lct_get_featured_image_posts_with_image()
* Added: lct_get_postmetas_with_image()
* Added: lct_get_termmetas_with_image()
* Added: lct_get_usermetas_with_image()
* Added: lct_get_options_with_image()
* Added: add_filter( 'woocommerce_get_image_size_single', [ $this, 'update_image_size' ] );
* Added: add_filter( 'woocommerce_get_image_size_gallery_thumbnail', [ $this, 'update_image_size' ] );
* Added: add_filter( 'woocommerce_get_image_size_thumbnail', [ $this, 'update_image_size' ] );

= 2018.63 =
* Improved: lct_post_types{}
* Added: lct_acf_get_option_raw()
* Improved: lct_admin_cron{}
* Improved: enable_email_reminder()
* Improved: iframe_filters_to_keep()
* Improved: pre_suf_fix.php
* Improved: lct_acf_admin{}
* Improved: activate_license()
* Improved: autoload_checker()
* Improved: lct_acf_op_main_fixes_cleanups{}
* Improved: add_user()
* Improved: lct_load_class()
* Improved: lct_wp_admin_acf_admin{}
* Improved: autoload_google_map_api_key()
* Improved: fusion_options_saved()
* Improved: lct_wp_admin_admin_admin{}
* Improved: cleanup_profile_page()
* Improved: use_page_note()
* Improved: lct_wp_admin_admin_loader{}
* Improved: lct_get_later()
* Improved: lct_update_later()
* Improved: lct_append_later()
* Improved: dev_url()
* Improved: show_admin_bar()
* Improved: lct_did()
* Improved: lct_undid()
* Improved: lct_set_plugin()
* Improved: lct_get_plugin_setting()
* Improved: lct_update_plugin_setting()
* Improved: lct_plugin_active()
* Improved: lct_plugin_version()
* Improved: lct_timer_end()
* Added: add_action( 'init', [ $this, 'remove_post_types' ], 9 );
* Added: lct_db_date_only_format()

= 2018.62 =
* WP v4.9.8 Ready
* ACF v5.7 Ready
* Improved: lct_make_status_slug()
* Added: add_action( 'acf/init', [ $this, 'create_local_field_key_reference_array' ], 3 );
* Improved: lct_acf_form2()
* Improved: lct_acf_display_form_format_value()
* Improved: lct_acf_format_value()
* Added: add_filter( 'acf/validate_form', [ $this, 'set_new_post_setting' ] );
* Added: add_action( 'acf/render_fields', [ $this, 'unset_new_post_setting' ] );
* Disabled: get_field_reference()
* Updated: lct_acf_loaded{}
* Improved: load_reference()
* Improved: lct_pre_us()
* Improved: maintenance_mode_in_admin_bar_menu()
* Improved: lct_get_fixes_cleanups_message___db_fix_atfd_7637()
* Improved: lct_acf_instant_save{}ajax_handler()
* Added: lct_disable_cache()
* Added: lct_enable_cache()
* Improved: lct_set_cache()
* Updated: send_ereminder()
* Updated: schedule_reminder()
* Improved: lct_acf_field_exists()
* Improved: register_post_status()

= 2018.61 =
* Avada v5.6.2 Ready
* Improved: set_version()
* Improved: add_cron_intervals()
* Updated: lct_mu{}
* Improved: status_default_args()
* Improved: default_labels()
* Updated: lct_remove_filter_like()
* Improved: lct_post_type_default_labels()
* Updated: lct_api_class{}
* Improved: iframe_filters_to_keep()
* Improved: lct_set_plugin()
* Added: lct_root_dir_only()
* Improved: check_restrictions_by_post_id()
* Improved: check_restrictions_by_taxonomy()
* Improved: lct_acf_field_exists()
* Added: lct_acf_option_repeater_empty()
* Improved: get_field_reference()
* Improved: always_load_google_fonts()
* Improved: always_load_typekit()
* Improved: modified_posts()
* Improved: lct_fusion_get_custom_posttype_related_posts_team()
* Improved: check_for_bad_avada_assets()
* Improved: disable_fusion_builder_activate()
* Updated: remove_contactmethods()
* Improved: always_check_admin()
* Improved: update_roles_n_caps()
* Improved: cleanup_profile_page()
* Moved: add_filter( 'register_post_type_args', [ $this, 'acf_post_type_args' ], 10, 2 );
* Improved: acf_post_type_args()
* Improved: after_redirection_apache_save()
* Improved: run_sql()

= 2018.59 =
* New Action:
	* lct/pder/send_ereminders/sent
* Code Cleanup
* Added: add_filter( 'pre_wp_update_comment_count_now', [ $this, 'only_count_comments' ], 10, 3 );
* Added: lct_undid()
* Added: add_action( 'admin_init', [ $this, 'update_all_comment_counts' ] );
* Added: lct_get_comments_number_by_type()
* Removed: add_filter( 'get_comments_number', [ $this, 'comment_count' ], 11, 2 );
* Improved: schedule_reminder()
* Updated: lct_pder_get_email_template()
* Updated: lct_update_reminder()
* Added: lct_db_date_format_no_seconds()
* Added: lct_send_reminder()
* Added: lct_get_email_ready_from()
* Updated: send_ereminder()
* Improved: set_parent_post_id()
* Improved: lct_get_post_id()
* Improved: lct_quick_send_email()
* Improved: mark_posts_as_updated_with_postmeta_changes()
* Improved: lct_acf_get_field_groups_fields()
* Added: lct_update_post_field()
* Added: lct_update_post_fields()
* Added: lct_acf_field_exists()
* Added: lct_acf_get_repeater_array_key()
* Improved: lct_format_phone_number()
* Improved: disable_fusion_builder_activate()

= 2018.58 =
* Improved: mark_posts_as_updated_with_postmeta_changes()
* Wrapped: lct_theme_chunk()
* Improved: get_fields()
* Improved: lct_get_attachment_id_by_url()
* Added: lct_db_date_format()
* Improved: remove_form_entry()

= 2018.57 =
* WP v4.9.7 Ready
* Added FILTER: 'lct/acf_loaded/load_reference/show_error_log'
* Improved: lct_wp_admin_wpsdb_admin{}
* Improved: check_for_cron_not_working()
* Improved: default_users()
* JS Tweaks

= 2018.56 =
* Avada v5.6 Ready
* Improved: update_ws_menu_editor()

= 2018.55 =
* Improved: lct_mu{}
* Added: lct_update_post_excerpt()
* Added: lct_update_post_content()
* Added: FILTER 'lct/check_for_bad_iframes/check_pages'
* Added: FILTER 'lct/check_for_bad_iframes/check_posts'

= 2018.54 =
* Code Cleanup: time()
* Updated: lct_sb_prefixes()
* Improved: lct_wpdb_prepare_in()
* Bug Fix: lct_get_attachment_id_by_url()
* Added: lct_get_today()
* Added: lct_get_day_from_today()
* Added: lct_get_percent()

= 2018.53 =
* Added: lct_get_attachment_id_by_url()
* Updated: lct_swap_url_to_path()
* Updated: lct_strip_url()
* Added: lct_url_site_when_dev()
* Added: lct_array_insert_after_key()
* Added: lct_array_insert_before_key()
* Added: ACF Field 'lct:::enable_nav_item_restrictions'
* Improved: wp_nav_menu_objects()
* Updated: set_fields()
* Improved: lct_mu{}
* Improved: disable_fusion_builder_activate()

= 2018.52 =
* New Action:
	* lct_mu/init
	* lct_mu/pre_load_mu
* Improved: lct_mu{}
* Improved: lct_url_up()
* Improved: [lct_lazy_youtube]; GDPR Ready
* Updated: admin_register_main_styles()
* Updated: admin_register_main_scripts()
* Improved: lct_acf_loaded{}
* Improved: lct_acf_op_main{}load_hooks()
* Improved: lct_fusion_get_custom_posttype_related_posts_team()
* Updated: [lct_team] & templates
* Improved: remove_meta_boxes()

= 2018.51 =
* Added: stable_uasort()
* Improved: load_reference()
* Improved: lct_acf_format_value()

= 2018.49 =
* WP v4.9.6 Ready
* Improved: admin_register_main_styles()
* Improved: admin_register_main_scripts()
* Updated: wpsdb_tables(); New gforms 2.3 table structure

= 2018.48 =
* Bug Fix: lct_update_status_taxonomy_term_count(); Phpstorm added some unneeded characters
* Avada v5.5 Ready

= 2018.47 =
* Improved: lct_acf_get_field_groups_fields()

= 2018.46 =
* Improved: lct_set_cache()
* Improved: lct_delete_cache()
* Added: lct_delete_cache_all()
* Improved: add_user_agent_check_when_cookie_not_set(); Allow $rocket_cache_reject_ua to be added to the cookie reject also

= 2018.45 =
* Improved: lct_repair_acf_termmeta():
* Added FILTER: 'lct/repair_acf_repeater_metadata/allowed_key_changes'
* Bug Fix: lct_get_post_id(); did not consider 'options' as a valid post_id
* Added: add_filter( 'wp_revisions_to_keep', [ $this, 'iframe_filters_to_keep' ], 10, 2 );
* Improved: lct_get_field_post_id()
* Improved: wpsdb_tables()

= 2018.44 =
* Bug fix: fusion_blog_shortcode_loop_content(); causing php warnings

= 2018.43 =
* Added: add_filter( 'wpseo_robots', [ $this, 'wpseo_robots' ] );

= 2018.42 =
* Added: [lct_lazy_gmaps]
* Added: [lct_lazy_vimeo]

= 2018.41 =
* Added pimg as contributor

= 2018.39 =
* Bug Fix: delete_user(); Was not working on cron

= 2018.38 =
* Wordpress v4.9.5 Ready
* Bug fix: Added a backup wp_doing_cron() function for older installs
* Bug fix: varnish_set_2nd_logged_in_cookie(); check if defined first
* Code Cleanup: lct_wp_add_inline_style_head()

= 2018.37 =
* Improved: default_users()

= 2018.36 =
* Improved: default_users()
* Improved: pimg_users()
* Improved: add_user()
* Improved: deactivate_user()
* Improved: reactivate_user()
* Added: delete_user()

= 2018.35 =
* CSS Tweaks
* Improved: get_fields()
* Improved: get_posts() Calls

= 2018.34 =
* Improved: timezone_settings()
* Improved: get_fields()
* Added: __return_yes()
* Bug Fix: input_admin_enqueue_scripts(); not properly checking for org
* Improved: password_reset()
* Bug Fix: jquery_conditional_logic_hide_field(); JS errors on backend
* Improved: check_for_bad_youtubes()
* Added FILTER: 'lct/check_for_bad_youtubes/check_pages'
* Added FILTER: 'lct/check_for_bad_youtubes/check_posts'
* Updated: lct_wp_admin_admin_update{}
* Updated: lct_wp_admin_admin_update_extras{}
* Improved: acf_field_lct_send_password{}
* Improved: lct_acf_format_value()

= 2018.33 =
* Improved: lct_set_current_theme()
* Improved: check_for_bad_youtubes()
* Added: add_action( 'admin_init', [ $this, 'load_vars' ], 4 );
* Improved: load_admin()
* Improved: load_edit()
* Improved: load_post()
* Improved: load_tools()
* Improved: load_themes()
* Improved: update_sidebar_meta()
* Improved: update_page_sidebar_meta()
* Improved: check_for_bad_avada_assets()
* Improved: check_all_fusion_pages_for_bad_avada_assets()
* Improved: check_for_wrong_emails()
* Improved: check_for_cron_not_working()
* Improved: update_blog_redirects()
* Improved: check_for_field_issues()
* Added: add_filter( 'lct/check_for_field_issues/duplicate_override', [ $this, 'check_for_field_issues_duplicate_override' ], 10, 2 );
* Added: add_filter( 'lct/check_for_field_issues/duplicate_clone_override', [ $this, 'check_for_field_issues_duplicate_clone_override' ], 10, 2 );
* Added FILTER: 'lct/check_for_field_issues/duplicate_clone_override'
* Bug Fix: Added scrolling="no" to lazyframe
* Added: add_filter( 'wpsdb_tables', [ $this, 'wpsdb_tables' ], 10, 2 );
* Updated: avada_main_menu()
* Added: Template: menu-mobile-main-v5.4.php
* JS Tweaks
* Added: Override avada_mobile_main_menu()
* Updated: wp_enqueue_scripts()
* Added: add_filter( 'nav_menu_item_id', [ $this, 'nav_menu_item_id' ], 10, 3 );
* Added: add_filter( 'nav_menu_css_class', [ $this, 'nav_menu_css_class' ], 10, 3 );
* Improved: lct_avada_default_overrider()

= 2018.32 =
* Improved: lct_mu{}
* Updated: send_ereminder()
* Improved: lct_force_trigger_error_deprecated_action()
* Improved: lct_force_trigger_error_deprecated_filter()
* Improved: lct_force_trigger_error_deprecated_function()
* Improved: lct_force_trigger_error_deprecated_shortcode()
* Updated: lct_deprecated_error_log()
* Improved: lct_shutdown_deprecated()
* Improved: lct_shutdown_deprecated_action()
* Deprecated FILTER: lct/acf/get_pretty_taxonomies/choices
* Improved: load_edit()
* Improved: pretty_wp_taxonomies_data()
* Improved: pretty_wp_taxonomies()
* Deprecated public function: get_pretty_taxonomies()
* Improved: exclude_taxonomies()
* Deprecated FILTER: lct/acf/acf_get_taxonomies/choices
* Deprecated public function: acf_get_taxonomies()
* Deprecated FILTER: lct/acf/acf_get_post_types/choices
* Deprecated FILTER: lct/acf/get_pretty_post_types/choices
* Deprecated public function: get_pretty_post_types()
* Deprecated public function: acf_get_post_types()
* Improved: pretty_wp_post_types_data()
* Improved: pretty_wp_post_types()
* Improved: exclude_post_types()
* Improved: load_field_update_choices()
* Updated: process_shortcodes()
* Updated: css_files()
* Updated: js_files()
* Updated: modify_clone()
* Removed: add_filter( "acf/prepare_field/type=message", [ $this, 'check_shortcodes' ] );
* Removed: add_filter( 'acf/prepare_field/name=' . zxzacf( 'gforms' ), [ $this, 'gforms' ] );
* Removed: lct_acf_filters_prepare_field{}
* Added: pretty_gforms_forms_data()
* Added: pretty_gforms_forms()
* Bug Fix: lct_format_phone_number(); double prefixed
* Improved: lct_set_current_theme()
* Improved: remove_meta_boxes()

= 2018.31 =
* Improved: lct_set_current_theme(); Better check for weird themes
* Improved: server_specs()

= 2018.29 =
* Improved: check_for_cron_not_working(); Added lct_DISABLE_CHECK_CRON_NOT_WORKING const check

= 2018.28 =
* Updated: [lct_scroll_arrow]; Added class att
* Updated: register_main_scripts(); Added global 'is_user_logged_in' JS var
* Improved: lct_mu{}do_active_plugins(); Added cache to the filter answer

= 2018.27 =
* Bug Fix: acf functions not available soon enough; Added: acf_start_up()
* Bug Fix: lct_api_hacky{}do_shortcode_tag(); cutting of posts too soon

= 2018.26 =
* Added: add_action( 'init', [ $this, 'check_for_cron_not_working' ] );
* Updated: lct_admin_cron{}activate()
* Updated: lct{}deactivate()
* Improved: PDER{}
* Improved: default_users()
* Improved: add_default_wp_users()
* Improved: lct_is_wpall()
* Improved: lct_is_wpdev()
* Improved: lct_format_phone_number()
* Moved & Improved: lct_acf_get_dev_emails()
* Improved: lct_is_user_a_dev()
* Added: lct_acf_update_option()
* Improved: set_google_map_api()
* Improved: remove_script_version()
* Improved: activate_license()
* Improved: lct_acf_admin{}wp_enqueue_styles()
* Improved: lct_acf_admin{}wp_enqueue_scripts()
* Improved: always_load_google_fonts()
* Improved: wp_footer_get_user_agent_info()
* Improved: maintenance_mode()
* Improved: lock_site_edits()
* Improved: lock_site_edits_in_admin_bar_menu()
* Improved: show_admin_bar()
* Improved: avada_blog_read_more_excerpt()
* Improved: disable_connection_services()
* Improved: enable_email_reminder()
* Cleanup: lct_acf_get_option()
* Improved: lct_features_theme_chunk{}ajax_handler()
* Improved: lct_update_reminder()
* Added: lct_delete_reminder()

= 2018.25 =
* Added: db_looper_check_any_option()

= 2018.24 =
* Bug Fix: check_for_cron_not_working(); wrong time

= 2018.23 =
* Improved: lct_get_post_content_fnr(); Added check for lct_DONT_CHECK_LINKS
* Improved: cleanup_guid_post_content(); Added check for lct_DONT_CHECK_LINKS

= 2018.22 =
* Added: lct_quick_send_email()
* Added: add_action( 'admin_notices', [ $this, 'check_for_cron_not_working' ] );

= 2018.21 =
* Improved: lct_admin_cron{}activate(); Needed to run cron every 10 minutes
* Added: lct_message_good()
* Added: lct_message_bad()
* Added: lct_wpdb_prepare_in()
* Added: add_action( 'init', [ $this, 'db_looper' ] );
* Added: db_looper_check_client_option()
* Added: db_looper_check_plugin_status_version()
* Added: db_looper_check_if_cron_is_running()

= 2018.20 =
* Improved: lct_enqueue()

= 2018.19 =
* Bug Fix: lct_set_current_theme(); Avada ajax fields not loading with the new MU settings

= 2018.18 =
* Added: [lct_mobi_overlay_menu_button]

= 2018.17 =
* CSS Tweaks
* Added: [lct_get_current_user]
* Improved: [lct_mobi_call_button]
* Improved: [lct_mobi_book_appt_button]
* Improved: [lct_mobi_contact_button]
* Improved: [lct_mobi_findus_button]
* Updated: check_for_bad_youtubes(); Added check for [embed shortcode]

= 2018.16 =
* Updated: dev_report(); Removed NKS-custom Plugin from default list
* Updated: MU

= 2018.15 =
* Updated: server_specs(); Added theme version
* Updated to editzz v8.0

= 2018.14 =
* Improved: force_update_db_values()
* Added: FILTER 'lct/dev_checks/modified_posts/hidden_post_types'
* Added: FILTER 'lct/dev_checks/dev_reports_post_types/exclude'
* Bug Fix: lct_acf_get_field_groups_fields(); Causing PHP error
* Moved & Improved: lct_cache_key()
* Added: lct_isset_cache()
* Added: lct_get_cache()
* Added: lct_set_cache()
* Added: lct_delete_cache()
* Moved & Improved: lct_acf_get_field_option()
* Added: lct_acf_loaded{}
* Added: add_filter( 'acf/load_reference', [ $this, 'load_references' ], 7, 3 );
* Added: lct_acf_get_option()
* Improved: get_field_reference()
* Improved: lct_acf_get_field_types()
* Improved: lct_acf_get_before_save_values()
* PDER Cleanup
* Improved: lct_pder_get_email_template()
* Added: lct_update_reminder()

= 2018.13 =
* Added: YouTube title support for lazyframe

= 2018.12 =
* Added: API support for lazyframe

= 2018.11 =
* WP v4.9.4 Ready
* JS Tweaks
* Added: lct_acf_get_field_label()
* Added: lct_acf_get_field_label_no_required()
* Bug Fix: lct_append_setting()
* Added: lct_get_negative_number()
* Added: lct_get_negative_dollar()
* Improved: lct_get_un_dollar()
* Improved: lct_get_dollar()
* Improved: lct_get_post_id()
* Improved: lct_features_theme_chunk{}ajax_handler()
* Moved & Improved: lct_get_field_post_id()
* Added: lct_get_acf_post_id()
* Improved: set_acf_form_post_id_for_instant()
* Added: lct_get_acf_post_id_only()
* Added: 'FILTER' 'lct/theme_chunk/ajax/iframe_url'
* Added: lct_update_post_parent()
* Created: fast_ajax
* Updated: lct_mu{}
* Updated: lct_features_theme_chunk{}
* Added: lct_post_type_default_labels()
* Added: lct_post_type_default_args()
* Improved: get_cnst()
* Improved: lct_make_status_name()
* Improved: lct_acf_get_key_post_type()
* Improved: lct_acf_get_field_groups_fields()
* Improved: lct_acf_get_key_taxonomy()
* Added: add_action( 'plugins_loaded', [ $this, 'disable_fusion_builder_activate' ] );
* Added: FILTER 'lct/theme_chunk/content'
* Added: [lct_lazy_youtube]
* Added: [lct_lazy_birdeye]

= 2018.10 =
* Bug Fix: Misc. ACF Fatal Errors
* Added: add_action( 'load-update-core.php', [ $this, 'update_redirection_options' ] );

= 2018.9 =
* Avada v5.4.2 Ready
* Improved: debug_backtrace() performance
* Added: lct_cache_key()
* Added: [lct_get_recent_post_permalink]
* Improved: check_all_fusion_pages_for_bad_avada_assets()
* Added: FILTER 'lct/check_all_fusion_pages_for_bad_avada_assets'

= 2018.8 =
* Updated: [lct_fixed_buttons]; Inclusive/Exclusive
* Added ACF Fields
* Added: add_filter( 'gettext', [ $this, 'avada_admin_language' ], 99, 3 );
* CSS Tweaks; Added classes for BG image adjustments

= 2018.7 =
* Improved lct_instant JS, Disable submit button while saving

= 2018.6 =
* WP v4.9.2 Ready
* Added: add_filter( 'rocket_clean_domain_urls', [ $this, 'clear_transients_acf_map_data' ] );
* CSS Tweaks
* Improved: [lct_mobi_findus_button]; Update lct Plugin for Mobile Directions Button
* Updated: package.json
* Improved: [lct_mobi_home_button]

= 2018.5 =
* Avada v5.4.1 Ready

= 2018.4 =
* Added: lct_api_hacky{}
* Moved: add_action( 'init', [ $this, 'set_shortcode_tags_link_always' ] );
* Moved: add_action( 'avada_blog_post_content', [ $this, 'avada_render_blog_post_content' ], 9 );
* Moved: add_action( 'fusion_blog_shortcode_loop_content', [ $this, 'fusion_blog_shortcode_loop_content' ], 2 );
* Moved: fusion_blog_shortcode_loop_content_done()
* Moved: add_filter( 'do_shortcode_tag', [ $this, 'do_shortcode_tag' ] );
* Added: add_action( 'wp', [ $this, 'set_parent_post_id' ] );
* Improved: lct_get_post_id()
* Improved: fusion_blog_shortcode_loop_content()

= 2018.3 =
* Improved: initial_tasks()

= 2018.2 =
* Bug Fix: [lct_social_footer]; Avada v5.4 Bug Fix

= 2018.1 =
* Avada v5.4 Ready

= 2018.0 =
* Added: cnst 'a_c_f_tax_disable'

= 2017.98 =
* Added: Menu Assets

= 2017.97 =
* Moved & Improved: [lct_read_more]
* Added: [lct_scroll_arrow]
* Added: Menu Assets
* Updated: [lct_mobi_call_button]
* Updated: [lct_mobi_book_appt_button]
* Updated: [lct_mobi_findus_button]
* Added: [lct_mobi_home_button]
* Added: add_action( 'wp_footer', [ $this, 'bottom_mobile_menu_wrapper' ], 1 );
* Updated: [lct_mobi_slide_menu_button]
* Improved: avada_after_header_wrapper()
* Added: New ACF fields
* Updated: header_layout()
* Updated: [lct_phone]

= 2017.96 =
* Improved: status_default_args()
* Added: add_action( "admin_footer-edit-tags.php", [ $this, 'disable_status_slug_editing' ] );
* Added: add_action( "admin_footer-term.php", [ $this, 'disable_status_slug_editing_on_term' ] );
* Added: FILTER 'lct_taxonomies_default_args'
* Improved: register_post_status()
* Improved: extend_submitdiv_post_status()
* Improved: lct_append_setting()
* Added: lct_make_status_name()
* Added: lct_make_status_slug()
* Added: lct_update_post_status()
* Added: lct_update_status_taxonomy_term_count()
* Updated: set_all_cnst(); a_c_f_tax_show_in_admin_all_list
* Moved & Improved: lct_get_post_types_by_taxonomy()
* Moved & Improved: lct_get_post_type_by_taxonomy()
* Added: lct_is_status_taxonomy()
* Improved: get_field_reference()
* Added: add_filter( 'acf/update_value/key=_validate_email', [ $this, 'damn_validate_email' ], 99, 3 );
* Added: add_filter( 'acf/update_value/name=' . lct_status(), [ $this, 'update_status_of_post_type_also' ], 99, 3 );
* Improved: repair_acf_repeater_metadata()
* Updated: force_update_db_values()

= 2017.95 =
* WP v4.9.1 Ready
* lct Tweaks
* Bug Fix: theme_chunk(); HTML entities were not being decoded
* Added: varnish_update_url_to_live()
* Added: add_filter( 'upload_dir', [ $this, 'varnish_update_upload_dir_urls' ] );
* Added: add_filter( 'page_link', [ $this, 'varnish_update_page_link_url' ], 99, 3 );
* Added: add_filter( '_wp_post_revision_fields', [ $this, 'wp_post_revision_fields' ], 10, 2 );
* Added: add_action( 'set_logged_in_cookie', [ $this, 'varnish_set_2nd_logged_in_cookie' ], 10, 5 );

= 2017.94 =
* Updated MU
* Bug Fix: acf_field_lct_column_end{}; regex not working properly for conditional logic
* Bug Fix: acf_field_lct_column_start{}; regex not working properly for conditional logic
* Improved: render_select_value_choice()
* Updated: prepare_field_add_pdf_display(); Added support for clone type
* Improved: check_for_field_issues()
* Added: FILTER 'lct/check_for_field_issues/dupe_clones'

= 2017.93 =
* Update MU
* ACF v5.6.5 Ready
* lct_wp_admin_admin_loader{}load_edit(); ACF v5.6.5 Ready
* Added: lct_un_pre_us()
* Improved: lct_get_comment_meta_field_keys()
* Removed: add_filter( 'lct_get_comments_number', [ $this, 'comment_count' ], 11, 2 );
* Removed: add_filter( 'init', [ $this, 'allow_comments_for_loop_only' ] );
* Removed: add_filter( 'pre_get_comments', [ $this, 'pre_get_comments' ] );
* Improved: comment_count()
* Improved: lct_acf_format_value(); case 'select'
* Improved: lct_acf_instant_save{}
* Added: add_filter( 'user_contactmethods', [ $this, 'remove_contactmethods' ], 10, 2 );
* Moved: add_filter( 'rpwe_markup', [ $this, 'rpwe_markup' ] );
* Moved: lct_get_comment_meta_field_keys()
* Moved: lct_get_comment_type_lct_audit_settings()
* Moved: lct_acf_update_field_inside_comment()
* Moved: add_filter( 'get_comments_number', [ $this, 'comment_count' ], 11, 2 );
* Removed: lct_features_filters_filters{}
* Added: lct_features_comments{}
* Added: [lct_show_if_current_user_can]

= 2017.92 =
* Big Fix: lct_gforms_admin{}wp_enqueue_styles(); legacy order was incorrect
* Bug Fix: check_all_fusion_pages_for_bad_avada_assets(); Doesn't work on old Avada versions

= 2017.91 =
* Bug Fix: check_for_wrong_emails(); false error when adminLabel was in use

= 2017.90 =
* Added: lct_wp_rocket_admin{}
* Added: add_filter( 'rocket_config_file', [ $this, 'add_user_agent_check_when_cookie_not_set' ], 10, 2 );
* Added: add_filter( 'rocket_cache_dynamic_cookies', [ $this, 'add_user_agent_dynamic_cookies' ] );
* Added: add_filter( 'rocket_buffer', [ $this, 'simple_user_agent_log' ], 99999 );
* Added: add_filter( 'rocket_sitemap_preload_list', [ $this, 'add_yoast_sitemap' ] );
* Added: add_filter( 'rocket_exclude_defer_js', [ $this, 'exclude_defer_random_js' ] );
* Added: add_filter( 'rocket_preload_url_request_args', [ $this, 'preload_url_request_args' ] );
* Added: add_filter( 'https_local_ssl_verify', '__return_false' );
* Added: add_filter( 'https_ssl_verify', '__return_false' );
* Added: add_filter( 'wp_get_attachment_url', [ $this, 'parallelize_hostnames' ], 10, 2 );
* Added: redirect_parallelize_hostnames()
* Added: setup_cdns()
* Added: get_current_cdn()
* Added: ACF Fields; use_xmlrpc, use_wlwmanifest, use_wp_rest_api
* Added: add_action( 'init', [ $this, 'disable_connection_services' ] );
* UD to _editzz-v7.8
* Updated: lct_mu{}
* Removed from default plugins: bj-lazy-load, w3-total-cache
* Added to default plugins: wp-rocket, disable-json-api

= 2017.89 =
* New Action:
	* lct/check_for_field_issues/all_name_updated
* ACF/PDF CSS Tweaks
* Wordpress TO WordPress cleanup

= 2017.88 =
* Bug Fix: check_for_wrong_emails(); field without placeholder was producing a false positive
* Bug Fix: dev_report(); Added plugins/photo-contest/assets to exclude list
* Updated: default_users(); auto-update display_name
* Improved: [theme_chunk]; Added support for the use of [embed] shortcode inside theme_chunks

= 2017.87 =
* Added custom images for photo-contest

= 2017.86 =
* Improved: lct_current_theme_version()
* Added: lct_active_theme_version()
* Improved: wp_head_last(); Wrong version being reported
* Improved: register_main_styles(); Wrong version being reported
* Improved: wp_enqueue_styles(); Wrong version being reported

= 2017.85 =
* WP v4.9 Ready
* Bug Fix: add_filter( 'acf/location/rule_types', [ $this, 'register_rule_types' ] ); Not loading when needed

= 2017.84 =
* Updated: lct_acf_format_value(); Added: post_object case
* Added: lct_acf_format_value_post_object()
* Added: lct_acf_format_value_user()
* Added: lct_acf_format_value_date_display_format()
* Added: lct_acf_format_value_taxonomy()
* Updated: check_for_field_issues()
* Updated: lct_get_field_post_id()

= 2017.83 =
* New Action:
	* lct/check_for_field_issues/field_name_check
	* lct/check_for_field_issues/loop_done
* Improved: lct_get_notice()
* Added: add_action( 'admin_notices', [ $this, 'check_for_field_issues' ] );
* Added: FILTER 'lct/check_for_field_issues/no_name_field_types'
* Improved: lct_acf_process_unused_settings()
* Improved: lct_acf_process_defaults()
* Bug Fix: lct_acf_disable_filters(); 500 Error on ACF older than v5.4
* Bug Fix: lct_acf_enable_filters(); 500 Error on ACF older than v5.4
* Added: exclude_field_type()
* Added: excluded_field_types()
* Added: lct_acf_register_field_type()
* Added: lct_acf_get_field_types()
* Improved: acf_form_head()
* Improved: get_field_label()
* Added: lct_is_display_form_or_pdf()
* Added: lct_is_form_enterable()
* Added: zxzu_undo()
* Added: is_set_cnst()
* Added: lct_set_empty_value()
* Improved: render_field_viewonly()
* Improved: render_field()
* Improved: lct_acf_display_form()
* Added: lct_get_field_post_id()
* Deprecated: FILTER lct_get_format_acf_value
* Deprecated: FILTER lct_get_format_acf_date_picker
* Improved: [lct_acf_repeater_items]
* Improved: lct_acf_format_value()
* Updated: lct_acf_format_value()
* Added: lct_acf_display_form_format_value()
* Added: lct_should_set_empty_value()
* Added: add_filter( 'lct/should_set_empty_value', [ $this, 'should_set_empty_value' ], 5 );
* Added: lct_remove_all_filters_like()
* Improved/Updated: lct_acf_get_field_groups_fields(); //TO-DO: cs - Allow $args_field to check ors as well as ands - 11/15/2016 12:27 PM
* Improved/Updated: lct_acf_get_field_groups_fields(); Added IN & NOT IN operators
* Improved/Updated: lct_acf_get_filter_fields(); Added IN & NOT IN operators
* Added: add_action( 'admin_notices', [ $this, 'check_for_field_with_empty_names' ] );
* Moved: lct_acf_get_repeater_array()
* Added: lct_acf_get_repeater_array_keys()
* Moved: lct_acf_get_repeater()
* Moved: lct_acf_get_imploded_repeater()
* Added: FILTER 'lct/check_for_field_issues/duplicate_override'
* Added: lct_remove_filter_like()

= 2017.82 =
* Update ALL lct_get_notice()
* Updated: check_for_wrong_emails()

= 2017.81 =
* WP v4.8.2 Ready
* Added: add_filter( 'update_footer', [ $this, 'current_wp_version' ], 15 );
* Added: add_filter( 'update_footer', [ $this, 'server_specs' ], 16 );
* Added: add_filter( 'register_post_type_args', [ $this, 'prevent_bad_permalinks' ], 10, 2 );
* Added: add_filter( 'lct/post_types/prevent_bad_permalinks', [ $this, 'prevent_bad_permalinks' ], 10, 3 );
* Updated: dev_report()
* Added: add_filter( 'lct/dev_reports/post_types', [ $this, 'dev_reports_post_types' ], 10, 2 );

= 2017.80 =
* New Action:
	* lct/acf_form_head
* Added: lct_get_user_agent_info()
* Updated: get_user_agent_info()
* Improved: lct_acf_field_settings{}
* Updated: acf_field_FIELD_FULL_NAME{}
* Moved: ACF api/_helpers.php load location
* Added: lct_is_pdf()
* Added: lct_acf_admin_field_hide()
* Added: lct_acf_field_type_class()
* Added: lct_acf_field_hide()
* Added: lct_acf_register_field_setting()
* Added: lct_acf_update_field_cleanup()
* Added: lct_acf_process_unused_settings()
* Updated: ALL acf field types
* Added: lct_acf_register_field_setting( 'pdf_display' );
* Added: ACF Field 'lct:::enable_acf_field_restrictions'
* Updated: prepare_field_access_primary()
* Updated: field_setting_r_n_c()
* Updated: field_setting_r_n_c_viewonly()
* Added: field_setting_pdf_display()
* Added: prepare_field_add_pdf_display()
* Updated: acf_field_lct_column_end{}
* Added: lct_is_display_form()
* Updated: acf_field_lct_column_start{}
* Added: lct_is_form_only()
* Updated: acf_field_lct_dev_report{}
* Updated: acf_field_lct_dompdf_clear{}
* Updated: lct_acf_display_form()
* Updated: acf_field_lct_modified_posts{}
* Updated: acf_field_lct_new_page{}
* Updated: acf_field_lct_send_password{}
* Updated: acf_field_lct_serialize{}
* CSS Tweaks
* Updated: acf_field_lct_phone{}
* Updated: acf_field_lct_section_header{}
* Updated: acf_field_lct_zip_code{}
* Bug Fix: process_shortcodes(); don't process them on the back-end
* Updated: acf_settings_tools_title_mod(); ACF now esc HTML
* Updated mu plugin for multisite support
* Added: field_setting_reference_only()
* Added: prepare_field_reference_only()
* Added: lct_acf_builtin_field_settings()
* Added: lct_acf_process_defaults()

= 2017.79 =
* Opps Wrong version number pushed last time

= 2017.78 =
* Bug Fix: cleanup_guid_link_cleanup(); taxonomy links not working right
* Added: lct_taxonomy_exists_by_slug()

= 2017.77 =
* ACF Fields
* Bug Fix: get_field_reference(); Not on ACF edit & options pages
* Updated: load_admin(); Added setting 'acf_is_options_page'
* Bug Fix: get_fields(); Not on ACF options pages
* Added: lct_acf_get_before_save_values()
* Added: lct_acf_get_before_save_value()
* Bug Fix: wp_set_object_terms(); Needed support for values that are objects
* Bug Fix: lct_acf_get_repeater(); Added object check
* Added: add_filter( 'acf/update_value/type=date_time_picker', [ $this, 'timezone_adjust' ], 100, 3 );
* Added: lct_format_date()
* Added: add_action( 'shutdown', [ $this, 'do_function_later' ], 11 );
* Added: lct_function_later()

= 2017.76 =
* Code Cleanup: lct_is_avada_version_3_n_below()
* Bug Fix: update_field_group(); options reference not set correctly
* Code Cleanup: lct_acf_admin{}deprecated()
* Code Cleanup: activate_license()
* Code Cleanup: modified_posts()
* Code Cleanup: acf_form_head()
* Code Cleanup: lct_acf_termmeta{}
* Code Cleanup: overrides.php
* Code Cleanup: lct_Avada_override{}
* Code Cleanup: wp_enqueue_scripts()
* Code Cleanup: wp_enqueue_styles()
* Code Cleanup: fix_google_api_scripts()
* Code Cleanup: autoload_google_map_api_key()
* Code Cleanup: dynamic_css()
* Code Cleanup: script_mobile_threshold()
* Code Cleanup: check_for_bad_avada_assets()
* Code Cleanup: lct_gforms_admin{}
* Code Cleanup: wp_enqueue_styles()
* Updated: load_admin(); ACF v5.6 Ready

= 2017.75 =
* UD to _editzz-v7.7

= 2017.74 =
* Added: add_filter( 'rpwe_markup', [ $this, 'rpwe_markup' ] );

= 2017.73 =
* Update: _lct_wp

= 2017.72 =
* lct Referenced Cleanup
* Bug Fix: form_data_nested_field_check()

= 2017.71 =
* Added: add_action( 'init', [ $this, 'set_wp_version' ] );
* Improved: modified_posts()

= 2017.70 =
* Improved: acf_field_lct_dev_report{}
* Moved: add_action( 'lct/acf/dev_report', [ $this, 'dev_report' ] );
* Moved: file_contains_check()
* Added: lct_acf_dev_checks{}
* Added: acf_field_lct_modified_posts{}
* Added: ACF Group Modified Posts
* Updated: dev_report()
* Improved: lct_acf_form2()
* Added: add_action( 'acf/input/form_data', [ $this, 'form_data_nested_field_check' ] );
* Added: add_action( 'lct/acf/modified_posts', [ $this, 'modified_posts' ] );

= 2017.69 =
* Improved: update_page_sidebar_meta()
* Improved: avada_3_to_5_fusion_fixer()
* Improved: set_version()
* Moved: update_ws_menu_editor()
* Moved: get_editzz_version()
* Added: lct_wp_admin_admin_update_extras{}
* Added: add_filter( 'lct/set_version/should_update', [ $this, 'should_update' ], 2 );
* Added: add_action( 'lct/set_version/update', [ $this, 'update_first' ], 2 );
* Added: add_action( 'lct/set_version/update', [ $this, 'old_option_key' ] );
* Added: add_action( 'lct/set_version/update', [ $this, 'force_update_db_values' ] );
* Moved: remove_crappy_caps()
* Moved: cleanup_do_pings()
* Moved: editzz_update_files()
* Moved: replace_files()
* Moved: editzz_file_update()
* Improved: [link]
* Updated: dev_report()
* Updated: [lct_address]
* Updated: dynamic_css()
* Improved: add_user()
* Added: lct_get_notice()
* Moved: of_options()
* Improved: lct_Avada_clear()
* Reviewed ALL Avada Code
* Removed: add_action( 'avada_save_options', [ $this, 'avada_save_options' ] );
* CSS Tweaks

= 2017.68 =
* Improved: dev_url(); Added multisite support
* Improved: wc_locate_template()
* Added FILTER: 'lct/wc_locate_template'
* Updated: load settings for template_router.php
* Improved: [lct_show_if]
* Added: [lct_hide_if]
* CSS Teaks; ACF field type 'New Page'
* Updated & Improved: acf_field_lct_new_page{}
* Added: add_filter( 'acf/load_field', [ $this, 'process_shortcodes' ] );
* Bug Fix: acf_form_head(); Was not ready for ACF v5.6

= 2017.67 =
* Added: add_action( 'avada_blog_post_content', [ $this, 'avada_render_blog_post_content' ], 9 );
* Updated: add_action( 'fusion_blog_shortcode_loop_content', [ $this, 'fusion_blog_shortcode_loop_content' ], 2 ); allow in avada v5

= 2017.66 =
* CSS Tweaks; Fix Avada strip HTML issue

= 2017.65 =
* Updated: lct_get_post_id()
* Improved: lct_acf_get_dev_emails()
* Updated: dev_report(); Added: zero-spam to default plugin list
* Improved: remove_meta_boxes()
* Updated: check_for_wrong_emails()
* Added FILTER: 'lct/check_for_wrong_emails/bad_emails'
* Added: lct_set_plugin( 'calendarize-it/calendarize-it.php', 'rhc' );

= 2017.64 =
* Added: add_action( 'plugins_loaded', [ $this, 'deny_wp_login' ], 3 );

= 2017.63 =
* Improved: avada_3_to_5_fusion_fixer()
* Added: add_action( 'admin_init', [ $this, 'sup_checker' ] );
* Improved: lct_acf_get_repeater()
* Improved: update_login_redirects()
* Added: add_action( 'plugins_loaded', [ $this, 'login_bypass' ], 0 );

= 2017.62 =
* Bug Fix: update_login_redirects(); Hardcoded DB prefix
* Bug Fix: update_blog_redirects(); Hardcoded DB prefix
* Bug Fix: set_login(); bad redirect check

= 2017.61 =
* WP v4.8.1 Ready
* Ditching iThemes
* Updated: dev_report(); Ditching iThemes
* Added: lct_wp_admin_wps_hide_login_admin{}
* Added: add_action( 'admin_init', [ $this, 'set_login' ] );
* Added: add_action( 'admin_init', [ $this, 'update_login_redirects' ] );
* Added: lct_wp_admin_wf_admin{}
* Added: add_action( 'admin_init', [ $this, 'initial_tasks' ] );
* Renamed: lct_wc_emails{}default() TO lct_wc_emails{}email_default()
* Added: add_action( 'admin_notices', [ $this, 'check_for_wrong_emails' ] );
* Updated: lct_Avada_admin{}wp_enqueue_scripts(); Annoying 'Comments Off' in Avada Recent Posts
* Added: add_filter( 'option_blog_public', '__return_false' );
* Improved: run_sql()
* CSS Tweaks
* Added: lct_make_shortcode_atts()
* Improved: [lct_phone]
* Minor Code Cleanup
* Added: [lct_iframe]
* Added: add_action( 'admin_notices', [ $this, 'check_for_bad_youtubes' ] );
* Updated: revslider_slide_setLayersByPostData_post()
* Updated: embed()
* Added: add_action( 'admin_init', [ $this, 'update_blog_redirects' ] );

= 2017.60 =
* Code Cleanup

= 2017.59 =
* ACF v5.6 Ready
* New ACF Fields
* CSS Cleanup
* Updated _lct_wp to v7.7RC2
* Bug Fix: deprecated_shortcodes.php; Needed to change add_shortcode() order
* Moved & Updated: [br]
* Updated activate_license(); ACF v5.6 Ready
* Improved: avada_before_body_content()
* Updated: dynamic_css()
* Updated: header_layout()
* Updated: header-4-v5.1.php
* Improved: mark_post_to_be_updated_later()
* Added: add_filter( 'automatic_updates_is_vcs_checkout', '__return_false', 1 );
* Bug Fix: replace_elan(); Was changing some words it shouldn't
* Updated LCT Referenced: /code/admin/template_router.php
* Updated LCT Referenced: /code/api/_global.php
* Updated LCT Referenced: /code/api/class.php
* Bug Fix: __construct() syntax issue
* Updated LCT Referenced: /code/api/pre_suf_fix.php
* Updated: _helpers.php
* Updated LCT Referenced: /code/wp-admin/admin/update.php
* Updated LCT Referenced: /code/__init.php
* Renamed: lct_revslider_action{} TO lct_revslider_admin{}
* Added: add_filter( 'revslider_slide_setLayersByPostData_post', [ $this, 'revslider_slide_setLayersByPostData_post' ], 10, 4 );
* Improved: process_nested_shortcode()
* Added FILTER: 'lct/process_nested_shortcode/sc_to_esc_html'

= 2017.58 =
* Added FILTER: 'lct/ere_reply_to'
* Modified PDER{}
* Added: lct_acf_update_field_later()
* Added: lct_acf_get_repeater_array()
* Updated: lct_acf_get_repeater(); Added callback arg
* Improved: lct_acf_get_key_post_type(); support for clone field
* Bug Fix: import_cleanup(); local was being stored in the DB
* Added: add_action( 'shutdown', [ $this, 'do_update_field_later' ] );
* Updated: do_update_field_later()
* Updated: update_field_later()

= 2017.57 =
* Added: add_filter( 'document_title_parts', [ $this, 'doc_title' ] );
* Added: add_filter( 'pre_get_document_title', [ $this, 'pre_get_document_title' ], 999 );
* Improved: lct_i_append_dev_sb()
* Added: ACF field 'force_append_dev_sb'
* Removed: lct_ithemes_admin{}
* Added: lct_wp_admin_ithemes_loaded{}
* Moved: add_filter( 'itsec_filter_server_config_file_path', [ $this, 'itsec_filter_server_config_file_path' ], 10, 2 );
* Renamed: lct_wpsdb_action{} TO lct_wp_admin_wpsdb_admin{}
* Added: add_filter( 'wpsdb_preserved_options', [ $this, 'wpsdb_preserved_options' ] );
* Improved: lct_wp_admin_wpsdb_admin{}shutdown()
* Added: add_action( 'plugins_loaded', [ $this, 'special_plugins_loaded_ithemes' ], - 999 );

= 2017.56 =
* Improved: lct_get_pretty_years()
* Improved: lct_acf_form2()

= 2017.55 =
* Improved: lct_check_role_of_class()

= 2017.54 =
* Improved: lct_acf_get_key_post_type()
* Added: FILTER 'lct/acf/get_key_post_type'
* Improved: mark_posts_as_updated_with_postmeta_changes()

= 2017.53 =
* Updated: set_nks_cc_options(); push type didn't work well so we switched to slide
* Added: add_action( 'pre_update_option_nks_cc_options', [ $this, 'update_nks_cc_options' ], 10, 3 );
* Updated: various z-index to better work with NKS
* Updated: [lct_fixed_buttons]; various z-index to better work with NKS
* Updated: avada_before_body_content(); various z-index to better work with NKS

= 2017.52 =
* Added: stripos_array()
* Code Cleanup: lct_wp_add_inline_script_head()
* Added: add_filter( 'content_save_pre', [ $this, 'replace_elan' ], 10 );

= 2017.51 =
* Added NKS slider support for Avada v5.0

= 2017.50 =
* Bug Fix: q2w3_fixed_widget_js_override(); Wrong URL
* Improved: dev_report(); Added NKS to Default Plugin List

= 2017.49 =
* Added: lct_wp_admin_nks_admin{}
* Added: add_action( 'admin_init', [ $this, 'set_nks_cc_options' ] );
* Improved: wp_enqueue_scripts(); Renamed NKS JS/CSS
* Improved: q2w3_fixed_widget_js_override()
* Removed: ga.js; Don't think we need it anymore
* Updated: tooltips-no-avada.min.js

= 2017.48 =
* Added NKS slider support
* Bug Fix: [lct_mobi_menu_button]
* Added: [lct_mobi_slide_menu_button]
* Added: [lct_avada_mobile_main_menu]

= 2017.47 =
* New JS function: lct_wait_for_existing_selector()
* Improved: instant_save.js
* Improved: prepare_field_access_primary()

= 2017.46 =
* Improved: lct_acf_get_key_post_type()
* Improved: lct_features_theme_chunk{}ajax_handler()
* Improved: do_update_field_later()
* Improved: wp_set_object_terms()
* Improved: check_min_max()

= 2017.45 =
* Improved: Main JS

= 2017.44 =
* Improved: header_layout(); side-header

= 2017.43 =
* Bug Fix: update_field_group(); Needed to check if the field has been saved into the DB first
* Added: lct_acf_enable_filters()
* Improved: lct_acf_get_field_groups_fields()
* Improved: lct_get_fixes_cleanups_message___db_fix_apmmp_5545()
* Improved: op_show_params_check_filters()
* Improved: timezone_settings()
* Improved: get_field_reference()
* Improved: lct_repair_acf_postmeta()
* Improved: repair_acf_repeater_metadata()
* Improved: repair_acf_taxonomy_relationships()
* Improved: lct_repair_acf_termmeta()
* Improved: lct_repair_acf_usermeta()
* Improved: lct_get_gaTracker_onclick()
* Improved: lct_i_get_gaTracker_category()

= 2017.42 =
* Primary File Cleanup
* Improved: lct_admin_template_router{}
* Improved: _global.php
* Improved: lct_api_class{}
* Added: add_filter( 'acf/settings/autoload', [ $this, 'autoload' ] );
* Added: add_filter( 'acf/get_fields', [ $this, 'get_fields' ], 10, 2 );
* Improved: lct_debug()
* Deprecated: lct_update_db_with_local_group()
* Improved: current_user_can_access()
* Improved: direct_current_user_can_edit()
* Improved: current_user_can_view()
* Improved: direct_current_user_can_view()
* Improved: wp_nav_menu_objects()
* Resolved: wp_nav_menu_objects(); TO-DO: cs - Speed up by not checking the children when we exclude the parent - 4/28/2017 11:36 AM
* Added: add_action( 'acf/update_field_group', [ $this, 'import_cleanup' ] );
* Added: add_action( 'admin_init', [ $this, 'autoload_checker' ] );
* Improved: dev_report()
* Added: [lct_acf]
* Improved: [lct_acf_term]
* Removed: add_action( 'admin_init', [ $this, 'update_options_to_desired_settings' ] );
* Added: add_action( 'load-update-core.php', [ $this, 'update_options_to_desired_settings' ] );
* Improved: update_options_to_desired_settings()
* Added: add_filter( 'pre_update_option_fusion_dynamic_css_posts', [ $this, 'fusion_dynamic_css_posts' ], 10, 3 );
* Improved: update_all_sidebar_metas()
* Improved: lct_wpsdb_action{}shutdown()
* Improved: save_user_sessions()
* Updated: load_admin()
* Updated: load_edit()
* Improved: lct_wp_admin_admin_update{}
* Improved: set_version()
* Removed: add_action( 'admin_init', [ $this, 'import_cleanup' ] );
* Move ACF loading location
* Improved: lct_repair_acf_usermeta()
* Improved: lct_repair_acf_postmeta()
* Improved: lct_repair_acf_termmeta()
* Improved: lct_acf_get_key_post_type()
* Improved: lct_acf_get_key_taxonomy()
* Improved: lct_acf_get_key_user()
* Deprecated: lct_acf_get_group_fields()
* Improved: lct_acf_get_field_groups_fields()
* Improved: add_comment()
* Improved: field_groups_columns_values()
* Improved: non_ajax_add_comment()
* Added: lct_acf_disable_filters()
* Improved: get_field_reference()
* Improved: lct_get_fixes_cleanups_message___db_fix_apmmp_5545()
* Improved: op_show_params_check_filters()

= 2017.41 =
* Cleanup: acf_post_id

= 2017.40 =
* Added: add_action( 'pre_kses', [ $this, 'pre_kses' ], 10, 3 );
* Added: Avada v5 Content Fixer Button

= 2017.39 =
* Removed: 'simple-image-widget' from required plugin list

= 2017.38 =
* WP v4.8 Ready
* UD to _editzz-v7.6

= 2017.37 =
* WordPress v4.7.5 Ready
* moved acf/load_filed in queue
* Added: add_filter( 'acf/update_value/type=number', [ $this, 'check_min_max' ], 10, 3 );
* Updated: set_all_cnst()
* Added: lct_following_us()
* Improved: lct_acf_instant_save{}ajax_handler()
* Improved: get_field_reference()
* Improved: lct_acf_update_field_inside_comment()
* Improved: lct_swap_url_to_path()

= 2017.36 =
* Avada v5.1.6 Support

= 2017.35 =
* Code Cleanup

= 2017.34 =
* New Action:
	* lct/template_redirect_front_access/404
* Deprecated Action:
	* lct_acf_new_post
* WP V4.7.4 Ready
* Completed: TO-DO: cs - UD get_terms() when you think everyone is running v4.5
* Improved: register_post_status()
* Improved: lct_get_terms()
* Improved: lct_get_taxonomy_by_path()
* Improved: lct_repair_acf_termmeta()
* Improved: lct_get_fixes_cleanups_message___db_fix_atfd_7637()
* Improved: register_post_status()
* Completed: TO-DO: cs - This can be improved by using global $wp_roles
* Improved: [lct_current_user_can]
* Improved: current_user_can_access()
* Improved: prepare_field_primary()
* Improved: lct_get_fixes_cleanups_message()
* Improved: op_show_params_check_filters()
* Improved: current_user_can_view()
* Improved: add_action( 'pre_get_posts', [ $this, 'allow_page_ordering' ] );
* Added: add_action( 'pre_get_posts', [ $this, 'pre_get_posts_front_access' ], 2 );
* Added: add_action( 'wp', [ $this, 'remove_allow_page_ordering' ] );
* Added: add_action( 'template_redirect', [ $this, 'template_redirect_front_access' ], 2 );
* Removed: add_filter( 'acf/prepare_field/name=' . zxzacf( 'hide_admin_bar__by_role' ), [ $this, 'wp_roles' ] );
* Added: pretty_wp_roles_data()
* Added: pretty_wp_roles()
* Added: pretty_wp_caps_data()
* Added: pretty_wp_caps()
* Added: lct_shutdown_deprecated()
* Added: add_action( 'shutdown', 'shutdown_deprecated_lct_current_user_can_access' );
* Added: add_action( 'shutdown', 'shutdown_deprecated_lct_current_user_can_view' );
* Added: add_action( 'shutdown', 'shutdown_deprecated_lct_maps_google_api' );
* Added: add_filter( 'lct_class_conditional_items', 'deprecated_lct_class_conditional_items', 2 );
* Added: add_action( 'shutdown', 'shutdown_deprecated_lct_class_conditional_items' );
* Deprecated: filter 'lct_class_conditional_items'
* Improved: lct_cleanup_role_classes()
* Added: lct_cleanup_role_classes_array()
* Moved: lct_check_user_logged_in_of_class()
* Moved: lct_check_role_of_class()
* Removed: add_filter( 'wp_nav_menu_items', [ $this, 'wp_nav_menu_items' ], 10, 2 );
* Added: add_filter( 'wp_nav_menu_objects', [ $this, 'wp_nav_menu_objects' ], 10, 2 );
* Added: check_restrictions_by_post_id()
* Improved: render_field_settings_clone()
* Added: pretty_wp_post_types_data()
* Added: pretty_wp_post_types()
* Added: check_restrictions_by_post_type()
* Improved: wp_nav_menu_archives_meta_box()
* Improved: lct_get_site_info_post_types()
* Improved: lct_repair_acf_postmeta()
* Improved: acf_get_post_types()
* Improved: check_all_fusion_pages_for_bad_avada_assets()
* Improved: remove_meta_boxes()
* Added: pretty_wp_taxonomies_data()
* Added: pretty_wp_taxonomies()
* Added: check_restrictions_by_taxonomy()
* Added: add_filter( 'acf/get_field_group', [ $this, 'update_field_group' ] );
* Improved: get_field_reference()
* Improved: lct_acf_form_full()
* Improved: lct_acf_form()
* Completed: TO-DO: cs - Add current_user_can_view :: See: lct_acf_form() - 12/05/2016 11:19 PM
* Improved: lct_acf_form2()
* Moved: add_filter( 'acf/prepare_field', [ $this, 'prepare_field_primary' ] );
* Added: add_action( 'acf/render_field', [ $this, 'render_field_viewonly' ] );
* Added: lct_acf_get_pretty_roles_n_caps()
* Added: field_setting_r_n_c()
* Added: add_filter( 'lct/acf/pretty_roles_n_caps', [ $this, 'add_to_pretty_roles_n_caps' ] );
* Added: add_filter( 'lct/direct_current_user_can_edit', [ $this, 'direct_current_user_can_edit' ], 10, 2 );
* Added: add_filter( 'lct/direct_current_user_can_view', [ $this, 'direct_current_user_can_view' ], 10, 2 );
* Added: field_setting_r_n_c_viewonly()
* Added: add_action( 'acf/render_field_settings/type=user', [ $this, 'render_field_settings_user' ] );
* Added: add_action( 'acf/render_field_settings/type=taxonomy', [ $this, 'render_field_settings_taxonomy' ] );
* Moved: [lct_current_user_can]
* Deprecated: [lct_acf_form]
* Deprecated: [lct_acf_form_full]
* Added: lct_force_trigger_error_deprecated_shortcode()
* Renamed: prepare_field_primary() TO prepare_field_access_primary()
* Added: lct_acf_format_value()
* Improved: get_format_acf_value()
* Improved: avada_main_menu()
* Improved: lct_path_up()
* Added: lct_path_up_now()
* Improved: theme_chunk()
* Improved: [lct_phone]
* Improved: [lct_fax]
* Improved: [lct_business_name]
* Improved: [lct_address]
* Improved: [lct_hours]
* Improved: lct_debug()
* Added: add_action( 'admin_init', [ $this, 'avada_3_to_5_fusion_fixer' ] );
* Renamed: lct_theme_chunk_iframe() TO theme_chunk_iframe()
* Added: lct_current_theme_minor_version()
* Improved: lct_avada_default_overrider()
* Avada v5.1 Ready
* Added: avada_main_menu_v5()
* Added: avada_main_menu_v5_1()
* Bug Fix: lct_acf_update_field_inside_comment()
* Added: jQuery: lct_wait_for_enabled_selector()
* Improved: theme_chunk.js
* Improved: lct_acf_instant_save{}
* Added: lct_shutdown_deprecated_action()
* Added: pretty_us_timezone_data()
* Added: pretty_us_timezone()
* Added: exhaustive_acf_field_groups_list_data()
* Added: exhaustive_acf_field_groups_list()
* Added: lct_get_post_id()
* Added: lct_is_wp_error()
* Added: lct_wp_safe_redirect()
* Added: [lct_avada_logo_mobile]
* Added: [lct_mobi_menu_button_js_only]
* Added: header_layout()
* Improved: get_street_address()
* Added FILTER: 'lct/tel_link/dont_use_global_format'
* Added: [lct_searchform]
* Added: lct_is_a()
* Improved: lct_acf_format_value()
* Improved: lct_acf_get_field_option()
* Improved: acf_term()
* Improved: render_field()
* Improved: add_user()
* Improved: register_post_status()
* Improved: lct_get_parent_term_meta()
* Improved: lct_get_term_parent()
* Improved: lct_get_term_id_or_create_n_get_term_id()
* Improved: lct_t()
* Improved: lct_tt()
* Improved: lct_tt_tax()
* Improved: lct_u()
* Improved: lct_get_the_slug()
* Improved: lct_generate_random_post_name()
* Improved: ajax_handler()
* Improved: theme_chunk()
* Improved: get_format_acf_value()
* Improved: lct_acf_get_repeater()
* Improved: lct_acf_get_field_groups_fields()
* Improved: ajax_handler()
* Improved: load_term_meta()
* Improved: update_all_sidebar_metas()
* Improved: testimony()
* Improved: bulk_post_content_search()
* Improved: bulk_post_content_delimit()
* Improved: lct_acf_get_field_option()
* Moved: lct_is_func_cache()
* Moved: lct_get_func_cache()
* Moved: lct_update_func_cache()
* Improved: process_nested_shortcode()
* Added: add_action( 'init', [ $this, 'set_shortcode_tags_link_always' ] );

= 2017.33 =
* Moved gforms files into /code/
* Improved: lct_map_adminLabel_to_field_id()
* Improved: lct_map_label_to_field_id()
* Improved: lct_gf_form_should_alter()
* Moved: add_action( 'gform_after_submission', [ $this, 'remove_form_entry' ], 13, 2 );
* Moved: add_action( 'gform_notification', [ $this, 'cj_check' ], 9999, 3 );
* Moved: add_action( 'gform_confirmation', [ $this, 'query_string_add' ], 9999, 4 );
* Moved: add_action( 'gform_enqueue_scripts', [ $this, 'wp_enqueue_styles' ] );
* Moved: add_action( 'gform_enqueue_scripts', [ $this, 'wp_enqueue_styles_form_specific' ] );
* Moved: add_action( 'gform_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
* Moved: add_action( 'gform_enqueue_scripts', [ $this, 'wp_enqueue_scripts_form_specific' ] );
* Moved: add_filter( 'gform_merge_tag_filter', [ $this, 'all_fields_extra_options' ], 11, 5 );
* Moved: add_filter( 'gform_enable_field_label_visibility_settings', [ $this, 'gform_enable_field_label_visibility_settings' ] );
* Moved: add_filter( 'gform_submit_button', [ $this, 'gform_submit_button' ], 10, 2 );
* Moved: add_filter( 'gform_field_content', [ $this, 'form_with_columns' ], 10, 5 );
* Moved: add_filter( 'gform_pre_render', [ $this, 'submit_button_anywhere' ] );
* Moved: add_filter( 'gform_multiselect_placeholder', [ $this, 'gform_multiselect_placeholder_legacy_lt_1' ], 10, 2 );
* Moved: add_filter( 'gform_pre_render', [ $this, 'mobile_placeholder_legacy_lt_1' ], 10, 5 );
* Moved: add_filter( 'gform_pre_render', [ $this, 'mobile_placeholder' ], 10, 5 );
* Moved: add_filter( 'gform_multiselect_placeholder', [ $this, 'gform_multiselect_placeholder' ], 10, 3 );
* Moved: lct_Avada_override{}
* Moved: baw-force-plugin-updates
* Added: lct_ithemes_admin{}
* Added: lct_maintenance_admin{}
* Moved: lct_w3tc_action{}
* Moved: lct_yoast_filter{}
* Moved: lct_wp_sweep{}
* Moved: lct_wpsdb_action{}
* Moved: g_lct{}
* Moved: lct_features_class_mail{}
* Moved: lct_features_filters_filters{}
* Moved: lct_features apis
* Changed: extend_plugins to plugins
* Updated: css_files()
* Improved: lct_gforms_admin{}wp_enqueue_styles()
* Improved: lct_dynamic_css_gforms()
* Improved: lct_org_status_us()
* Improved: lct_org_us()
* Improved: register_rule_values_org()
* Improved: theme_chunk()
* Moved: add_action( 'after_setup_theme', [ $this, 'remove_image_size' ], 11 );

= 2017.32 =
* Added: [lct_acf_term]
* Added: [lct_show_if]

= 2017.31 =
* WP v4.7.3 Ready
* Removed: $cnst; no longer needed, inside $settings now

= 2017.30 =
* Updated: dev_report(); Added: advanced-cron-manager as default plugin
* Added: add_action( 'load-update-core.php', [ $this, 'cleanup_do_pings' ] );

= 2017.29 =
* UD to _editzz-v7.5

= 2017.28 =
* Improved: lct_set_Yoast_GA_settings(); So that it works with new version of MonsterInsights

= 2017.27 =
* Removed: update_g_lct()
* Removed: update_g_lct_plugins_loaded_first()
* Code Cleanup
* Improved: lct_the_content_fusion_builder_bug_fix()
* Added: lct_strip_n_r_t()
* Improved: [span]
* Improved: [pimg_link]
* Improved: [get_directions]
* Improved: [raw]
* Improved: [faicon]
* Improved: render_field()
* Improved: lct_acf_display_form()
* Updated: lct_acf_field_settings{}->load_hooks()
* Improved: prepare_field_add_class_selector()
* Added: add_action( 'acf/render_field_settings/type=clone', [ $this, 'render_field_settings_clone' ] );
* Added: add_filter( 'acf/prepare_field/type=clone', [ $this, 'prepare_field_add_class_selector' ] );
* Added: add_filter( 'acf/prepare_field/type=clone', [ $this, 'prepare_field_add_clone_width_override' ] );
* Added: field_setting_clone_override_class_selector()
* Added: field_setting_clone_width_override()
* Added: zxzu_acf()
* Improved: raw()
* Improved: [lct_gf_submit]
* Improved: mobile_placeholder()

= 2017.26 =
* Check if WP_INSTALLING
* Modified: add_filter( 'acf/update_value/type=taxonomy', [ $this, 'wp_set_object_terms' ], 10, 3 ); changed firing
* Moved: WC files into /code/
* Moved: add_action( 'after_setup_theme', [ $this, 'remove_image_size' ], 11 );
* Moved: add_action( 'woocommerce_save_account_details', [ $this, 'wc_save_account_details' ] );
* Moved: add_filter( 'woocommerce_min_password_strength', [ $this, 'wc_min_password_strength' ] );
* Moved: add_filter( 'woocommerce_locate_template', [ $this, 'wc_locate_template' ], 10, 3 );
* Improved: [lct_wc_login_form]
* Renamed: woocommerce_locate_template() TO wc_locate_template()
* Improved: wc_locate_template()
* Bug Fix: dynamic_css(); Didn't check Avada version first

= 2017.25 =
* New Action:
	* lct/pder/after_wp_insert_post
* Improved: state()
* Improved: zip()
* Improved: phone_number()
* Improved: fax_number()

= 2017.24 =
* Improved: [theme_chunk]; Added fusion_calculate_columns check for content
* Updated: set_plugins_n_themes(); Added: xpg

= 2017.23 =
* Improved: logo-v5.php
* Added: access.php api
* Added: lct_cleanup_role_classes()
* Improved: wp_nav_menu_items()
* Improved: lct_check_role_of_class()

= 2017.22 =
* CSS Tweaks
* Bug Fix: register_rule_match_options_page()
* Improved: [lct_phone]
* Added: [lct_fax]
* Added: add_filter( 'acf/load_value/name=' . zxzacf( 'fax_number' ), [ $this, 'fax_number' ] );
* Added: ACF Fields: lct:::is_fax_number, lct:::is_fax_number_international, lct:::fax_number, lct:::fax_number_international
* Improved: [lct_testimony]
* Improved: op_show_params_check()

= 2017.21 =
* Renamed: lct_acf_update_value{} TO lct_acf_filters_update_value{}
* Added: add_action( 'acf/save_post', [ $this, 'do_update_field_later' ] );
* Added: add_filter( 'acf/update_value/name=' . zxzacf( 'is_phone_number_international' ), [ $this, 'is_phone_number_international' ], 10, 3 );
* Added: update_field_later()
* Improved: lock_site_edits()
* Moved & Improved: wp_set_object_terms()
* Removed: add_filter( 'acf/prepare_field/name=dyn::taxonomy', [ lct()->acf_public, 'get_pretty_taxonomies' ] );
* Added: lct_acf_filters_load_field{}
* Moved: css_files()
* Moved: js_files()
* Moved: modify_clone()
* Removed: lct_acf_filters{}
* Renamed: lct_wp_admin_acf_filters{} TO lct_wp_admin_acf_admin{}
* Moved: wp_roles()
* Moved: gforms()
* Moved: check_shortcodes()
* Moved: prepare_field_primary()
* Moved: get_format_acf_value()
* Moved: get_format_acf_date_picker()
* Moved: register_rule_match_lct_org()
* Moved: show_admin_bar()
* Moved: avada_blog_read_more_excerpt()
* Removed: set_term_option_override()
* Removed: load_term_option_override()
* Removed: lct_acf_filter{}
* Moved: [lct_acf_form]
* Moved: [lct_acf_form_full]
* Moved: [lct_acf_repeater_items]
* Moved: [lct_acf_load_gfont]
* Moved: [lct_acf_load_typekit]
* Removed: lct_acf_shortcode{}
* Moved: lct_acf_instant_save{}
* Improved: lct_format_phone_number()
* Added: lct_strip_phone()
* Added: lct_acf_filters_load_value{}
* Added: add_filter( 'acf/load_value/name=' . zxzacf( 'state' ), [ $this, 'state' ] );
* Added: add_filter( 'acf/load_value/name=' . zxzacf( 'zip' ), [ $this, 'zip' ] );
* Added: add_filter( 'acf/load_value/name=' . zxzacf( 'phone_number' ), [ $this, 'phone_number' ] );

= 2017.20 =
* lct file update
* Added: ACF Field: lct:::avada::is_post_excerpt_read_more_certain_pages
* Added: ACF Field: lct:::avada::post_excerpt_read_more_certain_pages
* Improved: avada_blog_read_more_excerpt(); Read More Toggle for Certain Pages only (https://app.asana.com/0/7167135393388/280633812089724)

= 2017.19 =
* Added: cron 'lct_auto_set_lct_api'
* Moved & Improved: auto_set_lct_api()
* Improved: default_users()

= 2017.18 =
* ACF Cleanup
* Added: add_action( 'plugins_loaded', [ $this, 'lock_site_edits' ], 11 );
* Added: add_action( 'admin_bar_menu', [ $this, 'lock_site_edits_in_admin_bar_menu' ], 999999 );
* Moved & Improved: maintenance_mode()
* Moved: maintenance_mode_in_admin_bar_menu()
* Added: lct_acf_update_value{}
* Added: add_filter( 'acf/update_value/name=' . zxzacf( 'lock_site_edits' ), [ $this, 'lock_site_edits' ], 10, 3 );
* Added: lct_q2w3_admin{}
* Moved: get_user_agent_info()
* Moved: q2w3_fixed_widget_js_override()
* Removed: lct_features_action{}
* Removed: add_action( 'after_setup_theme', [ $this, 'ajax_disable_stuff' ] ); mu-plugins now handles this much more effectively
* Added: lct_admin_cron{}
* Renamed & Moved & Improved: one_minute_dev() TO add_cron_intervals()
* Added: cron 'lct_add_default_wp_users'
* Moved: add_default_wp_users()
* Moved: default_users()
* Moved: pimg_users()
* Moved: add_user()
* Added: deactivate_user()
* Added: reactivate_user()
* Improved: lct_mu{}

= 2017.17 =
* Moved & Improved: lct_get_terms()
* Deprecated: lct_get_term_parent()
* Deprecated: lct_get_terms_parents()
* Deprecated: lct_get_terms_ids()
* Moved & Improved: lct_get_users()
* Moved & Improved: lct_get_org_users()
* Improved: lct_set_current_theme(); Added a setting for theme_child_version & theme_current_version
* Improved: register_main_styles()
* Improved: wp_head_last()

= 2017.16 =
* Minor Notes

= 2017.15 =
* Added: add_action( 'fusion_blog_shortcode_loop_content', [ $this, 'fusion_blog_shortcode_loop_content' ], 2 );
* Added: add_action( 'fusion_blog_shortcode_loop_content', [ $this, 'fusion_blog_shortcode_loop_content_done' ], 11 );
* Added: add_filter( 'do_shortcode_tag', [ $this, 'do_shortcode_tag' ] );

= 2017.14 =
* Improved: lct_enqueue()
* Added: add_filter( 'plugins_loaded', [ $this, 'check_if_unfiltered_html_should_be_forced' ], 3 );
* Added: add_filter( 'map_meta_cap', [ $this, 'force_allow_unfiltered_html' ], 1, 4 );
* Added: add_filter( 'user_has_cap', [ $this, 'force_allow_cap_unfiltered_html' ], 1, 4 );

= 2017.13 =
* Update map_icons to v3.0.0
* Update maps-utility-library-v3 infobox v1.1.13
* Moved: fix_google_api_scripts()
* Improved: lct_available_google_mcp{}
* Improved: lct_acf_op_main_fixes_cleanups{}
* Improved: lct_available_tooltips{}
* Removed: lct_acf_action{}
* Improved: lct_acf_instant_save{}
* Added: lct_root_include()
* Added: add_action( 'init', [ $this, 'set_version' ], 5 );

= 2017.12 =
* Added: add_action( 'init', [ $this, 'register_handlers' ] );
* Added: embed_vimeo()
* Added: lct_wp_admin_admin_onetime{}
* Removed: lct_wp_admin_filter{}
* UD to _editzz-v7.4.txt
* Bug Fix: default_args(); with_front should be false
* Added: add_action( 'admin_init', [ $this, 'drupal_redirect_mapper' ] );

= 2017.11 =
* Bug Fix: lct_acf_form2(); backwards compatibility for show_submit was not working properly
* Improved: roles_n_caps_cnst()
* Organize form bars - clean up page (shortest to longest) or show Rob how to
* Improved: dev_report(); multisite support
* Improved: span(); Process nested shortcodes
* Improved: is_user_logged_in(); Process nested shortcodes
* Added: lct_avada_default_overrider()
* Improved: avada_logo()
* Improved: avada_header_1()
* Improved: avada_header_2()
* Improved: avada_header_3()
* Improved: avada_header_4()
* Improved: avada_side_header()
* Improved: template_chooser()
* Added: FILTER 'lct/template_chooser'
* Added: lct_features_access{}
* Moved: add_filter( 'lct/current_user_can_access', [ $this, 'current_user_can_access' ], 10, 2 );
* Moved: add_filter( 'lct/current_user_can_view', [ $this, 'current_user_can_view' ], 10, 2 );
* Moved: add_filter( 'wp_nav_menu_items', [ $this, 'wp_nav_menu_items' ], 10, 2 );
* Improved: lct_acf_display_form{}
* Improved: form_data_post_id_ajax()
* Added: add_action( 'acf/input/admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

= 2017.10 =
* Bug Fix: Adjusted priority; add_filter( 'widget_title', 'do_shortcode', 5 );
* Bug Fix: lct_enqueue(); need to check if functions exist

= 2017.9 =
* Added: Shortcode [lct_test]
* Added: Shortcode [lct_test_2]
* Added: Shortcode [lct_test_3]
* Added: Shortcode [lct_test_4]
* Moved: lct_check_for_nested_shortcodes()
* Moved: lct_create_find_and_replace_arrays()
* Improved: lct_check_for_nested_shortcodes()
* Improved: lct_final_shortcode_check()
* Added: lct_shortcode_html_decode()
* Added: process_nested_shortcode()
* Added: lct_the_content_fusion_builder_bug_fix()
* Improved: theme_chunk()
* Improved: lct_is_thanks_page()
* Improved: lct_acf_is_thanks_page()
* Improved: execute_php()
* Improved: bracket_cleanup()
* Improved: the_content_first()
* Improved: the_content_final()
* Improved: widget_text_first()
* Improved: widget_text_final()
* Improved: html_widget_title()
* Added: disable_balanceTags()
* Added: add_filter( 'widget_text', [ $this, 're_enable_balanceTags' ], 11 );
* Added: lct_script_protector()
* Added: lct_script_protector_decode()
* Improved: raw()
* Improved: embed()
* Improved: dynamic_css()
* New class call cleanup
* Improved: load_field_update_choices()
* Improved: register_rule_match_options_page()
* Improved: [faicon]

= 2017.8 =
* Added: ACF field 'enable_migrate_silencer'
* Updated: jquery_migrate_echo_silencer()
* Updated: jquery_migrate_load_silencer()
* Removed: lct_select_options_get_raw_prefs()
* Added: [lct_raw] & [raw]
* Added: add_filter( 'no_texturize_shortcodes', [ $this, 'no_texturize_shortcodes' ] );
* Added: lct_features_content{}
* Moved: one_minute_dev()
* Moved: domain_mapping_plugins_uri()
* Moved: execute_php()
* Moved: bracket_cleanup()
* Moved: the_content_first()
* Moved: the_content_final()
* Moved: widget_text_first()
* Moved: widget_text_final()
* Moved: embed()
* Moved: embed_defaults()
* Moved: remove_thumbnail_dimensions()
* Moved: lct_get_gaTracker_onclick()
* Renamed: lct_set_Yoast_GA_universal() TO lct_set_Yoast_GA_settings()

= 2017.7 =
* CSS Tweaks
* Improved: lct_features_theme_chunk{} ajax_handler()

= 2017.6 =
* CSS Tweaks
* JS Tweaks: theme_chunk
* Added: ACF field 'disable_migrate_silencer'
* Added: ACF field 'is_iframe'
* Added: ACF field 'iframe_page_title'
* Added: ACF field 'iframe_page'
* Improved: lct_acf_get_field_groups_fields()
* Improved: get_field_reference(); Must have a $post_id set to continue
* Improved: lct_acf_update_field_inside_comment(); Save the terms if needed
* Added: add_action( 'wp_footer', [ $this, 'theme_chunk_iframe' ] );
* Improved: jquery_migrate_silencer()
* Improved: jquery_migrate_load_silencer()
* Improved: jquery_migrate_echo_silencer()
* Improved: lct_features_theme_chunk{} ajax_handler()
* Improved: theme_chunk()
* Added: add_action( 'get_header', [ $this, 'acf_form_head' ] );
* Added FILTER: 'lct/theme_chunk_iframe/json'
* Added: add_filter( 'lct/theme_chunk_iframe/json', [ $this, 'theme_chunk_iframe_json' ] );
* Added: add_filter( 'acf/load_field/type=clone', [ $this, 'modify_clone' ] );

= 2017.5 =
* Moved: add_filter( 'theme_page_templates', [ $this, 'theme_page_templates' ], 5, 4 );
* Improved: lct_mu{}
* Improved: lct_mu{}
* Added: lct_get_next_post()
* Added: lct_get_prev_post()
* Added: add_filter( 'get_previous_post_sort', 'lct_get_adjacent_post_sort_menu_order', 10, 2 );
* Added: add_filter( 'get_previous_post_where', 'lct_get_adjacent_post_where_menu_order', 10, 5 );
* Improved: Avada_clear()

= 2017.4 =
* Update to _editzz-v7.3
* WP v4.7.2 Ready
* Improved: dev_report(); added default plugin: call-tracking-metrics

= 2017.3 =
* New Action:
	* lct_mu/load_mu
* Added: lct_mu{}
* Improved: lct_mu{}
* Improved: lct_format_phone_number()
* Improved: lct_debug_to_error_log()
* Improved: get_cnst()
* Added: lct_is_valid_url()
* Added: lct_get_term_id_or_create_n_get_term_id()
* Improved: input_admin_enqueue_scripts()
* Improved: add_shortcode() now loaded everytime
* Minor Bug Fix: gf_submit()
* Improved: lct_geocode()
* Improved: lct_parse_address_components()

= 2017.2 =
* Added: lct_mu{}
* Removed: get_taxonomies()
* Improved: lct_list_directories()
* Improved: lct_list_files()
* Improved: get_field_reference()
* Updated: wp_enqueue_styles(); added is_js_file support
* Improved: css_files()
* Added: add_filter( 'acf/load_field/name=' . zxzacf( 'js_files' ), [ $this, 'js_files' ] );
* Added: ACF field lct:::is_js_file
* Added: ACF field lct:::js_files
* Improved: fixed_buttons()

= 2017.1 =
* WP v4.7.1 Ready
* Moved: some _helpers.php functions TO _global.php functions
* Moved: lct_doing()
* Moved: lct_did()
* Added: lct_is_func_cache()
* Added: lct_get_func_cache()
* Added: lct_update_func_cache()
* Moved: add_action( 'plugins_loaded', [ $this, 'set_plugins_n_themes' ], 2 );
* Added: add_action( 'admin_print_scripts', [ $this, 'jquery_migrate_echo_silencer' ] );
* Added: add_filter( 'script_loader_tag', [ $this, 'jquery_migrate_load_silencer' ], 10, 2 );
* Added: jquery_migrate_silencer()
* Improved: wp_enqueue_scripts()
* Improved: lct_acf_form2()
* Improved: dev_report()
* Improved: set_force_yes_fields()
* Removed: add_filter( 'lct/acf_form/args', [ $this, 'set_acf_form_args' ] );
* Improved: form_data_post_id()
* Improved: set_acf_form_post_id_for_instant()
* Improved: form_data_post_id_ajax()
* Improved: [lct_acf_form2]
* Minor Bug Fix: social_footer()
* Renamed: lct_form_show_button_hide TO lct_hide_submit

= 7.70 =
* CSS Tweaks: simple-image-widget
* Added: simple-image-widget to our default list
* Added: widget-clone to our default list
* Added: widget-css-classes to our default list
* Added: lct_siw_widget{}
* Added: template: siw/widget.
* Improved: fixed_buttons()

= 7.69 =
* Deprecated: lct_is_avada_version_any()
* Deprecated: lct_is_avada_version_3_n_below()
* Deprecated: lct_Avada_get_url_with_correct_scheme()
* Improved: [Avada_clear]
* Moved: Avada_clear()
* Added: lct_Avada_clear()
* Added: lct_Avada_shortcodes{}
* Removed: lct_Avada_shortcode{}
* Moved: [lct_social_header]
* Added: [lct_social_footer]
* Added: ACF Field 'disable_social_footer'
* Added: add_action( 'init', [ $this, 'remove_avada_render_footer_social_icons' ] );
* Moved: of_options()
* Removed: lct_Avada_filter{}
* Added: New ACF fields
* Renamed: list_directories() TO lct_list_directories()
* Moved: lct_list_directories()
* Added: lct_list_files()
* Improved: lct_path_theme()
* Improved: lct_url_theme()
* Added: add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_styles' ] );
* Added: add_filter( 'acf/load_field/name=' . zxzacf( 'css_files' ), [ $this, 'css_files' ] );
* Improved: wp_head_last()
* Added: add_action( 'wp_footer', [ $this, 'wp_footer_last' ], 2000000 );
* Added: add_action( 'admin_notices', [ $this, 'check_for_bad_avada_assets' ] );
* Added: add_action( 'load-update-core.php', [ $this, 'check_all_fusion_pages_for_bad_avada_assets' ] );
* Improved: update_page_sidebar_meta()

= 7.68 =
* Minor Bug Fix: overrides.php
* Minor Bug Fix: script_mobile_threshold()

= 7.67 =
* CSS Tweaks
* Added: Many new ACF fields
* Added: [lct_call_button]
* Added: [lct_book_appt_button]
* Added: [lct_contact_button]
* Added: [lct_mobi_contact_button]
* Added: [lct_findus_button]
* Improved: [lct_phone]
* Improved: [lct_business_name]
* Improved: [lct_address]
* Improved: [lct_hours]
* Improved: fixed_buttons()
* Added: Override: avada_main_menu()
* Improved: dynamic_css()
* Improved: header_layout()
* Minor Bug Fix: lct_get_gaTracker_onclick()

= 7.66 =
* Many New ACF fields
* Cleanup: lct_acf_field_settings{}
* Improved: lct_acf_shortcodes{}
* Improved: [lct_copyright]
* Improved: [lct_phone]
* Improved: [lct_mobi_call_button]
* Improved: [lct_mobi_book_appt_button]
* Improved: [lct_mobi_findus_button]
* Improved: lct_Avada_header{}
* Improved: [lct_avada_logo]
* Improved: [lct_avada_main_menu]
* Improved: [lct_menu_mobile]
* Improved: [lct_mobi_menu_button]
* Improved: [span]
* Improved: [space]
* Improved: [pimg_link]
* Improved: [lct_acf_form2]
* Improved: [lct_team]
* Improved: [lct_testimony]
* Improved: [theme_chunk]
* Added: [lct_address]
* Moved: add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_styles' ] );
* Added: dynamic_css()
* Added: lct_nl2br()
* Added: lct_br2nl()
* Improved: [get_directions]
* Added: [lct_business_name]
* Added: [hours]
* Added: [lct_mobi_findus_button]

= 7.65 =
* Added: Google Map clusterer images
* Improved: acf_startup()
* Updated: set_plugins_n_themes(); added: 'w3tc'
* Improved: lct_w3tc_action{}
* Improved: clear_transients_acf_map_data()

= 7.64 =
* Improved: [lct_phone]
* Improved: [get_template_part]
* Improved: [lct_get_template_part]
* Added: template file: logo-v5.php
* Added: Override for: avada_logo()

= 7.63 =
* PDER{} Tweaks
* Added: lct_get_dollar()
* Added: lct_get_un_dollar()
* Improved: lct_current_theme_major_version()
* Improved: load_dollar_amount()
* Improved: update_sidebar_meta()
* Improved: update_page_sidebar_meta()
* Improved: header_layout()
* Improved: header-1-v5.php
* Improved: lct_clean_number_for_math()

= 7.62 =
* Added: ACF field 'lct:::enable_avada-blog-sidebar'
* Improved: update_all_sidebar_metas()
* Improved: update_sidebar_meta()
* Added: update_page_sidebar_meta()
* Added: lct_Avada_loaded{}
* Added: add_action( 'widgets_init', [ $this, 'disable_blog_sidebar' ], 11 );
* Moved: templates dir
* Improved: lct_set_current_theme()
* Improved: file_in_active_theme()
* Added: lct_current_theme_version()
* Added: lct_current_theme_major_version()
* Imported: header-2.php from Avada v5
* Improved: set_plugins_n_themes()
* Improved: plugins_loaded_first()
* Added: lct_Avada_header{}
* Added: add_action( 'plugins_loaded', [ $this, 'maybe_set_acf_functions' ], 2 );
* Added: add_action( 'plugins_loaded', [ $this, 'acf_startup' ], 2 );
* Improved: lct_did()
* Minor Bug Fix: lct_load_class()
* Improved: include_field_types()
* Added: lct_acf_op_main{}
* Moved: ACF opmain TO lct_acf_op_main{}
* Added: acf_dev setting
* Added: add_action( 'lct/op_main/init', [ $this, 'add_op_main_Avada' ] );
* Updated: dev_report()
* Added: lct_gforms_admin{}
* Added: lct_wc_admin{}
* Removed: global zxzd
* Added: Override for: avada_header_1()
* Added: Override for: avada_header_2()
* Added: Override for: avada_header_3()
* Added: Override for: avada_header_4()
* Added: Override for: avada_side_header()
* Added: [get_template_part]
* Added: [lct_get_template_part]
* Added: [lct_avada_logo]
* Added: [lct_avada_main_menu]
* Added: [lct_menu_mobile]
* Added: lct_revslider_action{}
* Added: add_action( 'add_meta_boxes', [ $this, 'remove_meta_boxes' ], 999999 );
* Added: lct_header_layout()
* Added: header_layout()
* Added: lct_acf_get_field_option()
* Added: [lct_mobi_call_button]
* Added: [lct_mobi_book_appt_button]
* Added: [lct_mobi_findus_button]
* Added: add_action( 'avada_after_header_wrapper', [ $this, 'avada_after_header_wrapper' ] );
* Added: lct_admin_menu_editor_action{}
* Added: add_action( 'admin_init', [ $this, 'update_options_to_desired_settings' ] );

= 7.61 =
* Tweak: PDER{}
* Moved: lct_pder_get_email_template()
* Improved: strpos_array()
* Improved: lct_path_site()
* Improved: lct_acf_get_field_groups_fields()

= 7.60 =
* Added: add_filter( 'taxonomy_template', [ $this, 'taxonomy_template' ], 5 );
* Improved: lct_enqueue()
* Moved: [lct_copyright]
* Added: lct_disable_instant_checkbox_group class
* Improved: lct_acf_form2()

= 7.59 =
* Removed: /sitemap_generator/
* Improved: shortcode_copyright()
* Updated: acf_field_lct_phone{}
* Updated: exclude_post_types(); added post_type
* Improved: lct_check_for_nested_shortcodes()
* Added: lct_final_shortcode_check()
* Added: [lct_phone]
* Added: lct_get_small_mobile_threshold()
* Added: lct_get_mobile_extreme_threshold()

= 7.58 =
* Moved: lct_get_template_part()
* Moved: lct_template_part()
* Minor Bug Fix: set_version()
* Minor Bug Fix: lct_post_types{}
* Removed: get_post_types()
* Removed: get_post_types_all_monitored()
* Removed: get_post_types_parents()
* Removed: get_post_types_all_parents()
* Removed: get_comment_types()
* Removed: get_comment_types_all_monitored()
* Improved: lct_load_class()
* Improved: lct_admin_template_router{}
* Renamed dir: lct_templates TO templates
* Added: file_in_active_theme()
* Added: new_template()
* Improved: load_field_update_choices()
* Improved: theme_chunk()
* Added: lct_up_dir_only()
* Added: lct_wp_dir_only()
* Removed: lct_select_options_lct_user_timezone()
* Removed: ACF field lct:::disable_auto_set_user_timezone
* Removed: add_action( 'init', [ $this, 'set_user_timezone' ] );
* Added: lct_admin_time{}
* Improved: get_the_date()
* Improved: get_the_modified_date_time()
* Added FILTER: 'lct/time/timezone_user'
* Improved: lct_get_pretty_years()

= 7.57 =
* Changed our default mobile threshold from 800 TO 1024
* Moved & Improved: lct_get_mobile_threshold()
* Updated: script_mobile_threshold()
* Added Many new ACF fields

= 7.56 =
* Added: lct_admin_admin{}
* Added: add_action( 'init', [ $this, 'add_image_sizes' ] );
* Added: lct_team post_type
* Added: ACF field lct:::use_lct_team
* Added: ACF field lct:::use_lct_team_slug
* Added: ACF field lct:::lct_team_slug
* Added: lct_fusion_get_custom_posttype_related_posts_team()
* Added: lct_Avada_team{}
* Added: [lct_team]
* Added: single-lct_team.php
* Added: related-posts-lct_team.php
* Added: lct_testimony post_type
* Added: ACF field lct:::use_lct_testimony
* Added: ACF field lct:::use_lct_testimony_slug
* Added: ACF field lct:::lct_testimony_slug
* Added: lct_Avada_testimony{}
* Added: [lct_testimony]
* Added: lct_admin_template_router{}
* Improved: lct_get_later()
* Improved: lct_update_later()
* Improved: lct_append_later()
* Improved: lct_get_path()
* Improved: lct_get_root_path()
* Improved: lct_get_url()
* Improved: lct_get_root_url()
* Added: lct_sb_prefixes()
* Improved: lct_is_sb()
* Added: lct_get_option()
* Added: lct_update_option()
* Added: lct_delete_option()
* Cleanup: lct_get_option()
* Cleanup: lct_update_option()
* Cleanup: lct_delete_option()
* Added: lct_template_part()
* Added: lct_get_template_part()
* Improved: dev_url()
* Moved: function_conditional.php
* Moved: _function_is.php
* Removed: lct_features_class_asset_loader{}
* Improved: theme_chunk()
* Moved: acf/_function_is.php
* Added: lct_acf_is_thanks_page()
* Moved: add_action( 'wp_enqueue_scripts', [ $this, 'fix_google_api_scripts' ], 999999 );
* Cleanup: global class variable;
* Added: lct_Avada_admin{}
* Added: add_action( 'load-update-core.php', [ $this, 'update_all_sidebar_metas' ] );
* Added: add_action( 'save_post_post', [ $this, 'allow_update_sidebar_meta' ], 10, 3 );
* Added: add_action( 'lct/always_shutdown_wp_admin', [ $this, 'update_sidebar_meta' ] );
* Added: add_action( 'load-update-core.php', [ $this, 'autoload_google_map_api_key' ] );
* Added: add_action( 'admin_init', [ $this, 'autoload_google_map_api_key' ] );
* Added: add_action( 'admin_init', [ $this, 'load_themes' ], 5 );
* Improved: the_content_final()
* Added: lct_remove_site_root_all()

= 7.55 =
* Tweak: activate_license()

= 7.54 =
* Added: lct_wp_admin_admin_loader{}
* Removed: lct_wp_admin_action{}
* Cleanup: do_later
* Added: add_action( 'load-update-core.php', [ $this, 'activate_license' ] );
* Added: add_action( 'admin_init', [ $this, 'activate_license' ] );
* Added: add_action( 'load-update-core.php', [ $this, 'load_update_core' ], 5 );

= 7.53 =
* Improved: lct_format_phone_number()
* Updated: lct_acf_form2(); added 'lct_form_div'
* Updated: acf_field_lct_dev_report{}; run on is_admin()
* Updated: dev_report(); run on is_admin()
* Added: add_action( 'init', [ $this, 'set_force_yes_fields' ] );
* Added: list_directories()
* Updated: lct_acf_instant_save{}; Load on wp-admin
* Improved: lct_get_fixes_cleanups_message___db_fix_apmmp_5545()
* Improved: read_more()
* Improved: lct_features_shortcode_internal_link{} add_shortcode()

= 7.52 =
* Improved: op_show_params_check_filters()
* Added: zxzd()
* Improved: lct_acf_unsave_db_values()
* Cleanup: 'run_this' ACF field

= 7.51 =
* Added: acf_render_field_setting() 'preset_choices'
* Added: field_setting_preset_choices()
* Added: field_setting_preset_choices() TO Radio
* Added: field_setting_preset_choices() TO Checkbox
* Added: field_setting_preset_choices() TO Select
* Improved: lct_acf_get_pretty_column_start_width()
* Improved: lct_acf_get_pretty_column_end_type()
* Added: lct_acf_get_pretty_preset_choices()
* Added FILTER: 'lct/acf/pretty_preset_choices'
* Added: lct_acf_public_choices{}
* Added: add_filter( 'lct/acf/pretty_preset_choices', [ $this, 'add_to_pretty_preset_choices' ] );
* Added: pretty_state_list_data()
* Added: pretty_state_list()
* Improved: lct_features_shortcode_internal_link{} add_shortcode()
* Improved: lct_post_types{}
* Improved: lct_post_types{}
* Improved: lct_load_class()
* Added: pretty_acf_field_groups_list_data()
* Added: pretty_acf_field_groups_list()

= 7.50 =
* Removed: add_action( 'plugins_loaded', [ $this, 'load_lct_public_filters' ], 20 );
* Removed: add_filter( 'acf/load_field/name=' . zxzacf( 'remove_meta_boxes_taxonomies' ), [ zxzp()->acf_public, 'acf_get_taxonomies' ] );
* Removed: add_filter( 'acf/load_field/name=' . zxzacf( 'remove_avada_options_post_types' ), [ zxzp()->acf_public, 'acf_get_post_types' ] );
* Removed: add_filter( 'acf/load_field/name=' . zxzacf( 'remove_featured_image_post_types' ), [ zxzp()->acf_public, 'acf_get_post_types' ] );
* Moved: add_action( 'pre_get_posts', [ $this, 'allow_page_ordering' ] );
* Removed: ACF Field 'lct:::default_taxonomy'
* Added: lct_get_root_path()
* Added: lct_get_url()
* Added: lct_get_root_url()
* Updated: lct_load_class()
* Improved: lct_format_phone_number()
* Moved: lct_theme_chunk()
* Added Shortcode: [space]
* Added: lct_features_theme_chunk{}
* Improved: fusion_builder_allowed_post_types()
* Added: add_filter( 'fusion_builder_allowed_post_types', [ $this, 'fusion_builder_allow' ] );
* Improved: lct_acf_form2()
* Added: add_filter( 'acf/location/rule_match/options_page', [ $this, 'register_rule_match_options_page' ], 10, 3 );
* Added: add_filter( 'script_loader_src', [ $this, 'remove_script_version' ], 15, 1 );
* Added: add_filter( 'style_loader_src', [ $this, 'remove_script_version' ], 15, 1 );
* Improved: get_field_reference()
* Added: lct_acf_filters{}
* Improved: set_acf_form_post_id_for_instant()
* Added FILTER: 'lct/acf/acf_get_post_types/choices' TO acf_get_post_types()
* Added: add_filter( 'lct/acf/get_pretty_post_types/choices', [ $this, 'exclude_post_types' ], 10, 2 );
* Added FILTER: 'lct/acf/acf_get_taxonomies/choices' TO acf_get_taxonomies()
* Added: add_filter( 'lct/acf/get_pretty_taxonomies/choices', [ $this, 'exclude_taxonomies' ], 10, 2 );
* Added: get_pretty_taxonomies()
* Added: get_pretty_post_types()
* Removed: lct_admin_admin{}
* Added: lct_wp_admin_admin_admin{}
* Removed: lct_admin_update{}
* Added: lct_wp_admin_admin_update{}
* Added: lct_wp_admin_acf_choices{}
* Added: add_filter( 'acf/load_field/name=' . zxzacf( 'remove_meta_boxes_taxonomies' ), [ zxzp()->acf_public, 'get_pretty_taxonomies' ] );
* Added: add_filter( 'acf/load_field/name=' . zxzacf( 'remove_avada_options_post_types' ), [ zxzp()->acf_public, 'get_pretty_post_types' ] );
* Added: add_filter( 'acf/load_field/name=' . zxzacf( 'remove_featured_image_post_types' ), [ zxzp()->acf_public, 'get_pretty_post_types' ] );
* Improved: wp_enqueue_scripts()
* Removed: all_taxonomies()
* Improved: set_acf_form_args()
* Improved: form_shortcode()

= 7.49 =
* New Action:
	* lct/acf_form/before_acf_form #1
	* lct/acf_form/before_acf_form #2
* Added: zxzb()
* Cleanup: $ab
* Tweaks: CSS
* Bug Fix: dev_url(); wrong filter call
* Added: lct_force_trigger_error_deprecated_filter()
* Added: lct_force_trigger_error_deprecated_action()
* Deprecated FILTER: 'lct_current_user_can_access'
* Deprecated FILTER: 'lct_current_user_can_view'
* Added: lct_get_pretty_years()
* Renamed: lct_features_shortcodes_shortcode{} TO lct_features_shortcodes_shortcodes{}
* Added: lct_features_asset_loader{}
* Added: add_action( 'acf/fields/google_map/api', [ $this, 'set_google_map_api' ] );
* Added: code/plugins/acf/api/form.php
* Added: lct_acf_form2()
* Added Shortcode: [lct_acf_form2]
* Moved: add_action( 'wp_enqueue_scripts', [ $this, 'fix_google_api_scripts' ], 999999 );
* Added: lct_acf_shortcodes{}
* Added: lct_acf_form{}
* Moved: add_action( 'acf/input/form_data', [ $this, 'form_data_post_id' ] );
* Added: add_filter( 'lct/acf_form/post_id', [ $this, 'set_acf_form_post_id_for_instant' ], 10, 2 );
* Added: add_action( 'acf/input/admin_footer', [ $this, 'form_data_post_id_ajax' ] );
* Added FILTER: 'lct/acf_form/post_id'
* Added FILTER: 'lct/acf_form/args'
* Cleanup: lct_
* Added: add_filter( 'lct/acf_form/args', [ $this, 'set_acf_form_args' ] );
* Added: load_dollar_amount()
* Moved: prepare_field_remove_conditionals(); TO NPL
* Moved: extend_plugins/acf/field-settings/_functions_get.php TO code/plugins/acf/api/get.php
* Added: lct_features_class_asset_loader()

= 7.48 =
* Added: includes\phpseclib
* Removed: unneeded admin\direct files
* Added: code/admin/git/_lct_root_git/git_repo/
* Added: code/admin/git/_lct_wp_git/git_repo/
* Improved: editzz_update_files()
* Added: replace_files()
* Removed: copy_files()
* Removed: recurse_copy()
* Moved: echo_br()
* Improved: echo_br()
* Added: echo_br_o()
* Improved: include_classes(); Made api class globalized

= 7.47 =
* Removed: lct_int_wp_admin_action{}
* Moved: int functions
* CSS Tweaks
* Added: lct_admin_admin{}
* Renamed: add_action( 'lct/always_check_admin', [ $this, 'add_default_wp_users' ] ); TO add_action( 'load-update-core.php', [ $this, 'add_default_wp_users' ] );
* Improved: add_default_wp_users()
* Improved: default_users()
* Improved: pimg_users()
* Improved: add_user()
* Added: ACF field lct:::api
* Cleanup: lct_admin_update{}
* Renamed: add_action( 'load-update-core.php', [ $this, 'update_core_load' ] ); TO add_action( 'load-update-core.php', [ $this, 'load_update_core' ] );
* Renamed: add_action( 'lct/update_core_load', [ $this, 'remove_crappy_caps' ] ); TO add_action( 'load-update-core.php', [ $this, 'remove_crappy_caps' ] );
* Added: wpall api to lct_is_wpall()
* Added: wpdev api to lct_is_wpdev()
* Moved: lct_use_lct_dev_url(); Renamed TO dev_url()
* Added: lct_get_api_url()
* Removed: lct_is_special_dev()
* Moved: [pimg_link]
* Added: add_action( 'load-update-core.php', [ $this, 'auto_set_lct_api' ] );
* Added: add_action( 'lct/acf/dev_report', [ $this, 'dev_report' ] );
* Added: file_contains_check()
* Updated: zxzp{}; setting: current_version TO version_in_db
* Added: acf_field_lct_dev_report{}
* Added: dev_emails api to lct_acf_get_dev_emails()
* Added: ACF sub_page for Dev
* Moved: Users array to external json
* Merged: fix_old_version_entry() INTO set_version()
* Merged: check_version() INTO set_version()
* Merged: update_version() INTO set_version()
* Improved: update_roles_n_caps()

= 7.46 =
* Minor Code Cleanup
* Cleanup: zxzp{}
* Improved: acf_form_head(); ACF 5.5 ready

= 7.45 =
* Minor Code Cleanup
* Bug Fix: add_user(); password was getting changed
* Moved: lct_custom_redirect_wrapper()
* Moved: lct_wp_redirect()
* Improved: lct_custom_redirect_wrapper()
* Improved: lct_wp_redirect()
* Added: add_action( 'admin_init', [ $this, 'run_post_plugin_update' ], 5 );
* Improved: lct_set_plugin()
* Added: add_action( 'plugins_loaded', [ $this, 'set_plugins_n_themes' ], 2 );

= 7.44 =
* update.php tweaks
* Added: run_post_plugin_update()

= 7.43 =
* Renamed: update() TO upgrader_process_complete()
* Improved: update_version()
* Added: upgrader_process_complete()
* Bug Fix: 'basename'
* Improved: include_classes()
* Improved: activate()

= 7.42 =
* New Action:
	* lct/always_shutdown_wp_admin
* Moved: admin/deprecated/__init.php TO code/api/deprecated.php
* Moved: admin/git/* TO code/admin/git/*
* Moved: admin/plugin_reliant/* functions TO code/
* Moved: OLD functions
* Moved: set_all_cnst()
* Moved: always_shutdown()
* Added: add_action( 'shutdown', [ $this, 'always_shutdown_wp_admin' ] );
* Moved: roles_n_caps_cnst()
* Moved: update_ws_menu_editor()
* Moved: editzz_update_files()
* Moved: editzz_file_update()
* Moved: get_editzz_version()
* Moved: copy_files()
* Moved: recurse_copy()
* Added: lct_api_class{}
* Moved: debug functions
* Added: lct_force_trigger_error_deprecated_function()
* Added: lct_deprecated_error_log()
* Deprecated: lct_acf_active()
* Deprecated: lct_is_plugin_active()
* Improved: set_user_timezone()
* Improved: lct_geocode()
* Improved: lct_features_shortcode_tel_link{} add_shortcode()
* Added: lct_set_current_theme()
* Added: lct_theme_default_args()
* Added: lct_theme_active()
* Added: lct_theme_version()
* Removed: lct_plugin_reliant{}
* Improved: lct_set_plugin()
* Added: lct_update_plugin_setting()
* Added: lct_get_plugin_setting()
* Added: lct_set_Yoast_GA_universal()
* Added: update_g_lct_plugins_loaded_first()
* update edit_zz functions
* Improved: lct_path_site()

= 7.41 =
* Bug Fixes: causes by new /code/ dir
* Improved: lct_admin_update{ load_hooks() }; Bug Fixes: causes by new /code/ dir
* Improved: lct_load_class()
* Added: lct_features_shortcodes_shortcode{}
* Added: add_filter( 'the_content', 'do_shortcode' );
* Added: add_filter( 'widget_title', 'do_shortcode', 1 );
* Added: add_filter( 'widget_text', 'do_shortcode', 100 );
* Added: add_filter( 'widget_execphp', 'do_shortcode' );
* Added Shortcode: [span class=""]{$content}[/span]
* Improved: include_classes(); Bug Fixes: causes by new /code/ dir
* Improved: load_classes(); Bug Fixes: causes by new /code/ dir

= 7.40 =
* ACF 5.5 Ready
* Moved: public classes to /code/
* Changed: add_filter( "acf/prepare_field/name={$this->zxzp->zxza_acf}remove_meta_boxes_taxonomies" ); TO load_field
* Changed: add_filter( "acf/prepare_field/name={$this->zxzp->zxza_acf}remove_avada_options_post_types" ); TO load_field
* Changed: add_filter( "acf/prepare_field/name={$this->zxzp->zxza_acf}remove_featured_image_post_types" ); TO load_field
* Improved: lct_load_class()
* Added: load_other_public_classes()

= 7.39 =
* ACF 5.5 Ready
* Added: lct_get_later()
* Added: lct_update_later()
* Added: lct_append_later()
* Added: lct_is_wpdev()
* Added: lct_acf_admin{}
* Moved: add_filter( 'acf/get_field_reference', [ $this, 'get_field_reference' ], 10, 3 );
* Moved: lct_acf_termmeta{}; code/plugins/acf/termmeta.php

= 7.38 =
* New Action:
	* lct/always_shutdown
	* lct/database_check #1
	* lct/database_check #2
* Started code directory
* Added FILTER: 'lct/settings/{$name}'
* Added FILTER: 'lct/editzz_update_files'
* Deprecated: lct_is_sandbox()
* Cleanup: lct_is_sandbox()
* Improved: lct_i_append_dev_sb()
* Cleanup: lct_doing()
* Moved: admin/plugin_reliant/_function_static.php TO code/api/static.php
* Moved: lct_is_dev()
* Moved: lct_is_dev_or_sb()
* Moved: lct_is_wpall()
* Moved: lct_is_special_dev()
* Moved: lct_doing()
* Moved: add_action( 'plugins_loaded', [ $this, 'set_all_cnst' ], 6 );
* Moved: add_action( 'admin_init', [ $this, 'always_check_admin' ], 5 );
* Moved: fix_old_version_entry()
* Moved: set_version()
* Moved: check_version()
* Moved: update_version()
* Moved: roles_n_caps_cnst()
* Moved: set_roles_n_caps()
* Moved: update_roles_n_caps()
* Moved: default_add_cap()
* Moved: deactivate_roles_n_caps()
* Moved: add_action( 'upgrader_process_complete', [ $this, 'update' ] );
* Moved: add_action( 'load-update-core.php', [ $this, 'update_core_load' ] );
* Moved: add_action( 'lct_update_core_load', [ $this, 'remove_crappy_caps' ] );
* Moved: add_action( 'shutdown', [ $this, 'always_shutdown' ] );
* Moved: lct_timer_start()
* Moved: lct_timer_end()
* Added: lct_admin_update{}
* Added: lct_get_setting()
* Added: lct_update_setting()
* Added: lct_append_setting()
* Added: lct_get_path()
* Added: lct_include()
* Added: lct_load_class()
* Added: lct_load_class_default_args()
* Added: lct_frontend()
* Added: lct_wp_admin_all()
* Added: lct_wp_admin_non_ajax()
* Added: lct_ajax_only()
* Added: lct_is_sb()
* Added: lct_did()
* Added: lct_path_basename()
* Added: lct_url_basename()
* Added: lct_set_plugin()
* Added: lct_plugin_default_args()
* Added: lct_plugin_active()
* Added: lct_plugin_version()
* Added: lct_us()
* Added: lct_dash()
* Added: zxza()
* Added: zxzacf()
* Added: zxzu()
* Added: zxzs()
* Improved: lct_path_plugin()
* Improved: lct_url_plugin()
* Moved: lct_TO_filter{} to code
* Added: lct{}
* Added: update_g_lct()
* Removed: set_globals()
* Moved: register_activation_hook
* Moved: register_deactivation_hook
* Moved: register_uninstall_hook
* Improved: load_class()

= 7.37 =
* Minor JS Tweaks
* Improved: acf_field_lct_serialize{}
* Improved: lct_acf_form_full()
* Added: lct_TO_filter{}
* Added: add_filter( 'to/term_title', [ $this, 'term_title' ], 10, 2 );
* Added: lct_generate_random_post_name()

= 7.36 =
* Improved: lct_is_wpall()
* Added: lct_is_special_dev()
* Improved: default_add_cap()
* Added: add_filter( "acf/prepare_field/name={$this->zxzp->zxza_acf}remove_avada_options_post_types", [ lct()->acf_public, 'acf_get_post_types' ] );
* Added: add_filter( "acf/prepare_field/name={$this->zxzp->zxza_acf}remove_featured_image_post_types", [ lct()->acf_public, 'acf_get_post_types' ] );
* Added: lct_wp_admin_Avada_filter{}
* Added: add_filter( 'fusion_builder_shortcode_migration_post_types', [ $this, 'fusion_builder_shortcode_migration_post_types' ] );
* Added: add_action( 'admin_enqueue_scripts', [ $this, 'sticky_admin_sidebar' ] );
* Improved: remove_meta_boxes()
* Improved: load_admin()
* Improved: load_post()
* Improved: allow_page_ordering()
* CSS Tweaks
* Improved: lct_acf_field_settings{}
* Added: 'lct_instant_save_delay_2_sec' class
* Improved: acf_field_lct_send_password{}
* Added FILTER: 'lct/acf/send_password/wc_email/subject/customer_new_account'
* Added FILTER: 'lct/acf/send_password/wc_email/heading/customer_new_account'
* Added FILTER: 'lct/acf/send_password/wc_email/subject/customer_reset_password'
* Added FILTER: 'lct/acf/send_password/wc_email/heading/customer_reset_password'
* Bug Fix: wp_set_object_terms(); We were altering the $value and should not have been
* Added: lct_acf_form()
* Added: lct_acf_form_full()
* Improved: lct_acf_get_dev_emails(); maybe
* Improved: add_comment(); maybe
* Improved: lct_cleanup_guid(); be careful with acf
* Improved: save_post_cleanup_guid_link_cleanup(); be careful with acf
* Added: acf_get_post_types()
* Improved: fusion_builder_allowed_post_types()
* Added: lct_wc_emails{}
* Added: add_action( 'lct/wc/email_default', [ $this, 'default' ], 10, 4 );
* Added: add_filter( 'woocommerce_email_classes', [ $this, 'add_wc_email_classes' ] );
* Added: lct_wc_emails_lct_default{}
* Added: add_filter( 'woocommerce_locate_template', [ $this, 'wc_locate_template' ], 10, 3 );
* Improved: lct_get_order_product_ids()
* Improved: lct_get_order_product_id_terms()
* Renamed: lct_woocommerce_shortcode{} TO lct_wc_shortcode{}
* Improved: wc_login_form()
* Bug Fix: lct_enqueue(); enqueues with deps were not getting enqueued
* Added: lct_number_only()
* Added: lct_int_only()
* Added: lct_get_post_types_by_taxonomy()
* Added: lct_get_post_type_by_taxonomy()

= 7.35 =
* Clean up: get_cnst( 'class_selector' )
* Now checking post-new.php IN load_post()
* Improved: remove_meta_boxes()
* Added: add_action( 'updated_user_meta', [ $this, 'updated_user_meta' ], 10, 4 );
* CSS Tweaks
* Added: acf_field_lct_send_password{}
* Added: add_action( "{$filter}/type=post_object", [ $this, 'render_field_settings_post_object' ] );
* Added: add_action( "{$filter}/type=repeater", [ $this, 'render_field_settings_repeater' ] );
* Added: add_filter( "{$filter}/type=post_object", [ $this, 'prepare_field_add_class_selector' ] );
* Added: add_filter( "{$filter}/type=text", [ $this, 'prepare_field_add_class_selector' ] );
* Added: 'hide_if_empty' class TO lct_acf_get_pretty_class_selector()
* Added FILTER: 'lct/acf/pretty_class_selector'
* Improved: acf_field_lct_column_end{}
* Improved: acf_field_lct_column_start{}
* Improved: acf_field_lct_dompdf_clear{}
* Improved: acf_field_lct_new_page{}
* Improved: acf_field_lct_section_header{}
* Added: lct_update_db_with_local_group()
* Removed: add_action( 'init', [ $this, 'set_conditional_filters' ] );
* Improved: render_field(); added do_shortcode() to $value
* Improved: render_field(); added new_line check
* Removed: lct_acf_display_form_filter_turn_on(); stupid idea
* Added: hide_if_empty()
* Added: unique_user_email()
* Added: add_action( 'woocommerce_save_account_details', [ $this, 'wc_save_account_details' ] );
* Added: lct_wc_filter{}
* Added: add_filter( 'woocommerce_min_password_strength', [ $this, 'wc_min_password_strength' ] );
* Added: lct_get_org_users()

= 7.34 =
* Code Cleanup
* Added: baw-force-plugin-updates
* Bug Fix: update_roles_n_caps(); need to check with version_compare(), instead of floatval()

= 7.33 =
* Improved: update_roles_n_caps()
* Added: default_add_cap()
* Improved: lct_timer_end()
* Added: add_action( 'plugins_loaded', [ $this, 'load_lct_public_filters' ], 20 );
* Improved: lct_acf_get_field_groups_fields()
* Added: lct_acf_get_filter_fields()

= 7.32 =
* Modified: create_theme_chunk(); changed caps
* Added: add_filter( 'register_post_type_args', [ $this, 'register_post_type_args' ], 10, 2 ); saves revisions for acf fields
* CSS Tweaks: acf
* CSS Tweaks: dompdf
* Added: add_action( 'acf/render_field_settings/type=textarea', [ $this, 'render_field_settings_textarea' ] );
* Added: add_filter( 'acf/prepare_field/type=textarea', [ $this, 'prepare_field_add_class_selector' ] );
* Added: 'lct_nomp_top' class TO lct_acf_get_pretty_class_selector()
* Added: 'lct_nomp_bottom' class TO lct_acf_get_pretty_class_selector()
* Added: 'lct_nomp_top_n_bottom' class TO lct_acf_get_pretty_class_selector()
* Added: 'dompdf_1_column' class TO lct_acf_get_pretty_class_selector()
* Added: 'lct_dompdf_force_avoid_page_break_inside' class TO lct_acf_get_pretty_class_selector()
* Improved: acf_field_lct_column_end{}; Added containing div
* Improved: acf_field_lct_column_start{}; Added containing div
* Improved: acf_field_lct_dompdf_clear{}
* Added: add_filter( "acf/prepare_field/type=message", [ $this, 'check_shortcodes' ] );

= 7.31 =
* New Action:
	* lct/acf/display_form/type_date_picker
	* lct/acf/display_form/type_date_time_picker
* Improved: lct_public{}; better load order
* Improved: lct_acf_public{}; better load order
* Added: add_filter( "acf/prepare_field/name={$this->zxzp->zxza_acf}remove_meta_boxes_taxonomies", [ lct()->acf_public, 'acf_get_taxonomies' ] );
* Added: add_filter( 'acf/update_field', [ $this, 'update_field' ] );
* Improved: get_field_label()
* Added FILTER: 'lct/acf/get_field_label/excluded_field_types'
* Added: add_action( 'admin_init', [ $this, 'remove_meta_boxes' ], 999999999 );
* CSS Tweaks: acf
* CSS Tweaks: dompdf
* CSS Tweaks: wp-admin acf
* CSS Tweaks: wp-admin custom
* Added: add_action( 'acf/render_field_settings/type=date_picker', [ $this, 'render_field_settings_date_picker' ] );
* Added: add_action( 'acf/render_field_settings/type=date_time_picker', [ $this, 'render_field_settings_date_time_picker' ] );
* Added: add_action( 'acf/render_field_settings/type=message', [ $this, 'render_field_settings_message' ] );
* Added: add_action( 'acf/render_field_settings/type=validated_field', [ $this, 'render_field_settings_validated_field' ] );
* Added: add_action( 'acf/render_field_settings/type=lct_section_header', [ $this, 'render_field_settings_zxza_section_header' ] );
* Added: add_filter( 'acf/prepare_field/type=date_picker', [ $this, 'prepare_field_add_class_selector' ] );
* Added: add_filter( 'acf/prepare_field/type=date_time_picker', [ $this, 'prepare_field_add_class_selector' ] );
* Added: add_filter( 'acf/prepare_field/type=message', [ $this, 'prepare_field_add_class_selector' ] );
* Added: add_filter( 'acf/prepare_field/type=validated_field', [ $this, 'prepare_field_add_class_selector' ] );
* Added: add_filter( 'acf/prepare_field/type=lct_section_header', [ $this, 'prepare_field_add_class_selector' ] );
* Added: 'hidden' class TO lct_acf_get_pretty_class_selector()
* Added: 'dompdf_left' class TO lct_acf_get_pretty_class_selector()
* Added: the missing 'show_on_pdf' class TO lct_acf_get_pretty_class_selector()
* Added: lct_acf_get_pretty_section_text_wrapper()
* Code Cleanup: acf_field_lct_column_end{}
* Code Cleanup: acf_field_lct_column_start{}
* Code Cleanup: acf_field_lct_dompdf_clear{}
* Code Cleanup: acf_field_lct_new_page{}
* Added: acf_field_lct_section_header{}
* Added: lct_acf_hide_this()
* Updated: acf_form_head(); Added section_header to $excluded_field_types
* Renamed FILTER: 'lct_acf_form_head_display_form_excluded_field_types' TO 'lct/acf_form_head_display_form/excluded_field_types'
* Improved: render_field()
* Added FILTER: 'lct/acf/display_form/type_date_picker/value'
* Improved: render_field_hide_if_true_false()
* Improved: render_field_hide_if_yes_no()
* Improved: lct_acf_display_form(); auto add clear divs
* Renamed FILTER: 'lct_acf_display_form' TO 'lct/acf/display_form'
* Bug Fix: ajax_handler(); fatal error when date field was empty
* Added: ACF Field: remove_meta_boxes_taxonomies
* Added: acf_get_taxonomies()

= 7.30 =
* Improved: lct_return()
* CSS Tweaks: acf.min.css
* Added: dompdf.min.css
* CSS Tweaks: wp-admin acf.min.css
* CSS Tweaks: wp-admin custom.min.css
* Added: add_action( 'acf/render_field_settings/type=checkbox', [ $this, 'render_field_settings_checkbox' ] );
* Added: add_action( 'acf/render_field_settings/type=email', [ $this, 'render_field_settings_email' ] );
* Added: add_action( 'acf/render_field_settings/type=number', [ $this, 'render_field_settings_number' ] );
* Added: add_action( 'acf/render_field_settings/type=radio', [ $this, 'render_field_settings_radio' ] );
* Added: add_action( 'acf/render_field_settings/type=select', [ $this, 'render_field_settings_select' ] );
* Added: add_action( 'acf/render_field_settings/type=true_false', [ $this, 'render_field_settings_true_false' ] );
* Added: add_action( 'acf/render_field_settings/type=lct_phone', [ $this, 'render_field_settings_zxza_phone' ] );
* Added: add_action( 'acf/render_field_settings/type=lct_zip_code', [ $this, 'render_field_settings_zxza_zip_code' ] );
* Renamed: prepare_field_text TO prepare_field_add_class_selector()
* Added: add_filter( 'acf/prepare_field/type=checkbox', [ $this, 'prepare_field_add_class_selector' ] );
* Added: add_filter( 'acf/prepare_field/type=email', [ $this, 'prepare_field_add_class_selector' ] );
* Added: add_filter( 'acf/prepare_field/type=number', [ $this, 'prepare_field_add_class_selector' ] );
* Added: add_filter( 'acf/prepare_field/type=radio', [ $this, 'prepare_field_add_class_selector' ] );
* Added: add_filter( 'acf/prepare_field/type=select', [ $this, 'prepare_field_add_class_selector' ] );
* Added: add_filter( 'acf/prepare_field/type=true_false', [ $this, 'prepare_field_add_class_selector' ] );
* Added: add_filter( 'acf/prepare_field/type=lct_phone', [ $this, 'prepare_field_add_class_selector' ] );
* Added: add_filter( 'acf/prepare_field/type=lct_zip_code', [ $this, 'prepare_field_add_class_selector' ] );
* Improved: lct_acf_get_pretty_class_selector()
* Added: lct_large_checkbox class_selector
* Added: lct_acf_get_class_selector_label()
* Added: lct_acf_get_pretty_column_start_width()
* Added: lct_acf_get_column_start_width_label()
* Added: lct_acf_get_pretty_column_end_type()
* Added: lct_acf_get_column_end_type_label()
* Improved: acf_field_lct_template_v5{}
* Improved: acf_field_lct_column_end{}
* Improved: acf_field_lct_column_start{}
* Improved: acf_field_lct_dompdf_clear{}
* Improved: acf_field_lct_new_page{}
* Improved: acf_field_lct_phone{}
* Improved: acf_field_lct_serialize{}
* Improved: acf_field_lct_zip_code{}
* Resolved: //TO-DO: cs - Retrieve $field_types dynamically - 10/25/2016 02:17 PM
* Added FILTER: 'lct_acf_form_head_display_form_excluded_field_types'
* Integrated: class_selector into render_field()
* Integrated: class_selector into render_select_value_choice()
* Integrated: class_selector into render_field_hide_if_true_false()
* Integrated: class_selector into render_field_hide_if_yes_no()
* Improved: lct_acf_display_form()
* Added FILTER: 'lct_acf_display_form'

= 7.29 =
* Improved: plugins_loaded_acf_field_types()
* Added: Rob TO pimg_users()
* Cleanup: plugin_reliant _function.php
* Improved: lct_doing()
* Added: lct_is_wpall()
* Moved: lct_return()
* Added: lct_acf_field_settings{}
* Added: lct_acf_get_pretty_class_selector()
* Added: add_action( 'acf/render_field_settings/type=text', [ $this, 'render_field_settings_text' ] );
* Added: add_filter( 'acf/prepare_field/type=text', [ $this, 'prepare_field_text' ] );
* Added: field_setting_class_selector()
* Added: ACF field-type dompdf_clear
* Added: ACF field-type column_start
* Added: ACF field-type column_end
* Added: ACF field-type new_page
* Improved: ACF field-type phone
* Improved: ACF field-type serialize
* Improved: ACF field-type zip_code
* Added: add_action( 'acf/include_field_types', 'lct_acf_include_field_types' );

= 7.28 =
* Bug Fix: Minor in get_cnst( 'org' )
* Added: ACF field-type lct_serialize
* Moved: ACF Field Types include()s
* Added: add_action( 'plugins_loaded', [ $this, 'plugins_loaded_acf_field_types' ], 1 );
* Avada v5.0 Ready
* Removed: Override: avadareduxNewsflash{}
* Added: add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts_legacy_2_0_2' ], 999999 );
* Renamed: lct_Fusion_Core_PageBuilder_override TO lct_Fusion_Core_PageBuilder_override_legacy_lt_5
* Added: add_filter( 'fusion_builder_allowed_post_types', [ $this, 'fusion_builder_allowed_post_types' ] );
* Bug Fix: Minor in lct_features_shortcode_post_content{}

= 7.27 =
* Verified: git directory
* Verified: int directory
* Added: g_lct_{$cnst}
* Clean Up: cnst variables thru out the plugin
* Added: set_cnst()
* Added: get_cnst()
* Added: set_cnst_data()
* Added: get_cnst_data()
* Moved: lct_o()
* Added: lct_strip_site()
* Added: lct_strip_url()
* Added: lct_strip_path()
* Removed: lct_tax_status_us()
* Removed: lct_tax_public()
* Removed: lct_tax_published()
* Added: add_action( 'plugins_loaded', [ $this, 'set_all_cnst' ], 4 ); TO lct_plugin_reliant{}
* Added: roles_n_caps_cnst()
* Moved: debug.php
* Cleaned Up: debug.php
* Moved: echo_br()
* Cleaned Up: lct_post_types{}
* Added: ACF Field lct:::use_lct_org
* Added: set_all_cnst() TO lct_post_types{}
* Added: conditional 'lct_org' post_type
* Cleaned Up: lct_taxonomies{}
* Added: set_all_cnst() TO lct_taxonomies{}
* Added: conditional 'lct_org_status' taxonomy
* Added: add_action( 'pre_get_posts', [ $this, 'allow_page_ordering' ] );
* Removed: lct_admin_shortcode{}
* Removed: Shortcode [lct_admin_onetime_script_run]
* Added: lct_admin_filter{}
* Added: add_filter( 'auth_cookie_expiration', [ $this, 'auth_cookie_expiration' ], 999, 3 );
* Cleaned Up: lct_features_shortcode{}
* Cleaned Up: lct_features_shortcode_post_content{}

= 7.26 =
* Modified: update_version()
* Added: set_roles_n_caps()
* Added: update_roles_n_caps()
* Added: deactivate_roles_n_caps()
* Added: add_action( 'admin_init', [ $this, 'import_cleanup' ] );
* Moved: onetime.php
* Renamed: lct_admin_onetime{} TO lct_wp_admin_onetime{}
* Modified: load_edit()
* Moved: add_action( 'lct_add_tax_to_user_admin_page', [ $this, 'add_tax_to_user_admin_page' ] );
* Moved: add_filter( 'image_send_to_editor', [ $this, 'remove_site_root' ] );
* Moved: add_filter( 'post_row_actions', [ $this, 'add_post_id' ], 2, 2 );
* Moved: add_filter( 'media_row_actions', [ $this, 'add_post_id' ], 2, 2 );
* Moved: add_filter( 'page_row_actions', [ $this, 'add_page_id' ], 2, 2 );
* Improved: mark_post_to_be_updated_later()
* Added: mark_posts_as_updated_with_postmeta_changes()
* Bug Fix: lct_cleanup_uploads(); PHP Warning: Illegal string offset 'file'
* Added: acf_field_group_list();

= 7.25 =
* Added: add_action( 'acf/get_field_label', [ $this, 'get_field_label' ], 10, 2 );
* Bug Fix: register_rule_match_lct_org(); in_array() expects parameter 2 to be array
* Added: ACF field: lct:::dont_check_page_links TO lct_theme_chunk post_type

= 7.24 =
* Cleaned: global ${$class};
* Added: g_lct_{$current_version}
* Improved: check_version()
* Added: add_action( 'lct/update_core_load', [ $this, 'remove_crappy_caps' ] );
* Added: ACF Settings Group for WooCommerce
* Added: ACF Field lct:::wc::disable_image_sizes
* Removed: add_action( 'admin_init', [ $this, 'remove_image_size' ], 11 );
* Improved: social_header()
* Added: lct_wc_action{}
* Added: add_action( 'after_setup_theme', [ $this, 'remove_image_size' ], 11 );
* Added: add_action( 'init', [ $this, 'wc_memberships_comments_open_hack' ] );
* Improved: wpsdb_migration_complete()
* Improved: content_type()

= 7.23 =
* Improved: get_format_acf_value(); Added support for clone field type
* Improved: lct_acf_get_field_groups_fields(); Added selector to $args

= 7.22 =
* New Action:
	* lct/acf/display_form/type_select
* Improved: instant_save.js
* Added: lct_acf_display_form{}
* Renamed: lct_acf_form_head_display_only_full() TO lct_acf_form_head_display_form()
* Renamed: lct_display_only_full_render_field() TO render_field()
* Renamed: lct_acf_form_display_only_full() TO lct_acf_display_form()
* Improved: render_field(); Added single column, Added value array support
* Added: add_action( 'init', [ $this, 'set_conditional_filters' ] );
* Added: add_filter( 'lct/acf/display_form/type_select/value', [ $this, 'render_select_value_choice' ], 10, 3 );
* Added: add_action( 'lct/acf/display_form/type_true_false', [ $this, 'render_field_hide_if_true_false' ] );2
* Added: add_action( 'lct/acf/display_form/type_radio', [ $this, 'render_field_hide_if_yes_no' ] );
* Added: lct_acf_display_form_filter_turn_on()
* Added: Filter: 'lct/acf/display_form/type_message/value'
* Added: Filter: 'lct/acf/display_form/type_true_false/value'
* Added: Filter: 'lct/acf/display_form/type_radio/value'
* Added: Filter: 'lct/acf/display_form/type_select/value'
* Added: Filter: 'lct/acf/display_form/type_checkbox/value'
* Added: Filter: 'lct/acf/display_form/type_default/value'
* Bug Fix: lct_cleanup_guid(); URL encoding causes issues sometimes
* Bug Fix: save_post_cleanup_guid_post_content(); was messing up ACF fields
* Bug Fix: save_post_cleanup_guid_existing_link_sc_check(); was messing up ACF fields

= 7.21 =
* Code Cleanup
* Replace lct_get_dev_emails() with lct_acf_get_dev_emails()
* Moved some actions & filters
* Removed: legacy_rename_option_fields
* Improved: field_groups_columns_values()
* Renamed: export_title_mod() TO acf_settings_tools_title_mod()
* Added: add_action( 'admin_init', [ $this, 'load_edit' ] );
* Added: add_action( 'admin_init', [ $this, 'post_edit' ] );
* Improved: lct_acf_get_key_user()
* Resolved: //TO-DO: cs - Make this better - 2016.09.29 11:19 PM; op_show_params_check()
* Added: add_action( 'admin_init', [ $this, 'op_show_params_check_filters' ] );
* Added: lct_acf_get_field_groups_fields()
* Improved: render_field()
* Override: avadareduxNewsflash{}
* Improved: lct_redirection_action{}

= 7.20 =
* Bug Fix: register_screen(); some sort of weird loop
* Bug Fix: maintenance_mode(); forgot to check is ACF was running

= 7.19 =
* Removed: add_action( 'edited_term_taxonomy', [ $this, 'edited_term_taxonomy' ], 10, 2 ); Replaced with lct_repair_term_counts()
* Added: add_filter( "acf/update_value/type=taxonomy", [ $this, 'wp_set_object_terms' ], 10, 3 );
* Added: lct_acf_update_field_inside_comment(); to help resolve a bug when you try to use update_field() inside a comment
* Bug Fix: In non_ajax_add_comment(); Re: lct_acf_update_field_inside_comment()
* Added: lct_repair_term_counts()
* Added: lct_delete_empty_terms()
* Bug Fix: lct_repair_acf_usermeta(); Would not have key if the entry was not in the DB
* Bug Fix: lct_repair_acf_postmeta(); Would not have key if the entry was not in the DB
* Bug Fix: lct_repair_acf_termmeta(); Would not have key if the entry was not in the DB
* Added: repair_acf_repeater_metadata()
* Added: repair_acf_taxonomy_relationships()
* Added: lct_wp_sweep{}
* Added: add_filter( 'wp_sweep_details', [ $this, 'wp_sweep_details' ], 10, 2 );

= 7.18 =
* Added: lct_pre_us()
* Improved: lct_u()
* Removed: add_action( 'created_term', [ $this, 'created_term' ], 10, 3 );
* Removed: add_filter( 'wp_insert_post_data', [ $this, 'insert_post_data' ], 10, 2 );
* Removed: add_filter( 'insert_user_meta', [ $this, 'insert_user_meta' ], 10, 3 );
* Improved: lct_acf_get_key_user()
* Improved: lct_repair_acf_usermeta()
* Improved: lct_repair_acf_postmeta()
* Improved: lct_repair_acf_termmeta()
* Improved: update_term_meta()
* Improved: load_term_meta()
* Improved: get_field_reference()
* Cleaned up acf_get_local_fields() & acf_get_fields_by_id() calls

= 7.17 =
* Code Reformat
* Moved: lct_org()
* Added: lct_org_us()
* Added: lct_org_status_us()
* Added: lct_tax_status_us()
* Added: lct_tt_tax()
* Improved: lct_taxonomies{}
* Moved & Changed: register_comment_types_w_acf() TO register_rule_values_comment()
* Modified: status_default_args()
* Modified: register_post_status()
* Modified: extend_submitdiv_post_status()
* Moved: instant_save.php
* Added: termmeta.php
* Added: lct_acf_termmeta{}
* Added: add_filter( 'acf/get_field_reference', [ $this, 'get_field_reference' ], 10, 3 ); because ACF get_field() was not retrieving termmeta values
* Added: add_filter( 'acf/location/screen', [ $this, 'register_screen' ], 10, 2 );
* Added: add_filter( 'acf/location/rule_types', [ $this, 'register_rule_types' ] );
* Added: add_filter( 'acf/location/rule_values/' . lct_org(), [ $this, 'register_rule_values_lct_org' ] );
* Added: add_filter( 'acf/location/rule_match/' . lct_org(), [ $this, 'register_rule_match_lct_org' ], 10, 3 );
* Added: lct_acf_get_group_fields()
* Changed: non_ajax_update_value() TO non_ajax_add_comment()
* Added: New ACF field: lct:::audit_save_postmeta
* Improved: lct_audit entries are only stored when the lct:::audit_save_postmeta toggle is true
* Improved: acf/op_main.php
* Improved: shortcode_copyright()

= 7.16 =
* Added: New ACF field: lct:::dev_emails
* Added: New ACF field: lct:::maintenance_mode
* Code Reformat
* Added: lct_wp_admin_action{}
* Added: add_action( 'admin_init', [ $this, 'load_admin' ] );
* Resolved: //TO-DO: cs - Make this into an Useful Setting - 11/11/2015 5:38 PM; By improving lct_get_dev_emails() & ACF field: lct:::dev_emails
* Added: add_action( 'plugins_loaded', [ $this, 'maintenance_mode' ], 11 );
* Added: add_action( 'admin_bar_menu', [ $this, 'maintenance_mode_in_admin_bar_menu' ], 999999 );

= 7.15 =
* Improved: lct_use_lct_dev_url()

= 7.14 =
* New Action:
	* lct/acf/display_form/conditional_logic
	* lct/acf/display_form/type_checkbox
	* lct/acf/display_form/type_message
	* lct/acf/display_form/type_radio
	* lct/acf/display_form/type_true_false
	* lct/acf/display_form/type_default
* Added: lct_swap_url_to_path()
* Added: lct_swap_path_to_url()
* Bug Fix: acf-lct_phone tweak; don't allow more than 10 digits
* Added: Custom ACF field type (lct_zip_code)
* Added: hide conditional_logic TO render_field()

= 7.13 =
* CSS Tweaks for gforms
* Made some gforms function gforms v2.0 ready

= 7.12 =
* WP v4.6.1 Ready
* Added: Custom ACF field type (lct_phone)
* Added: lct_acf_get_repeater()
* Added: ACF Field(s) lct:::phone_number_format
* Added: lct_format_phone_number()
* Code Cleanup

= 7.11 =
* Added: add_action( 'admin_init', [ $this, 'grant_super_admin' ] );
* Added: Custom Checkbox check to lct_acf_instant_save{} JS
* Bug Fix: lct_enqueue(); Empty CSS files were printing the CSS URL and breaking the styles

= 7.10 =
* Improved: lct_tt()
* Added: Shortcode [lct_current_year]

= 7.9 =
* WP v4.6 Ready
* Code Cleanup
* Added: lct_is_plugin_active(); WordPress v4.6 broke this, even in the admin, so I had to fix it.
* Added: add_action( 'plugins_loaded', [ $this, 'set_is_gforms_active' ], 4 );
* Minified files tweaked because of updated node.js
* Renamed: Avada-legacy_lt-4.0.min.css
* Added: gforms-legacy_lt-2.0.scss
* Added: Avada-gforms-legacy_lt-2.0.scss
* Improved: gforms.scss
* Improved: Avada-gforms.scss
* Avada_version cleanup
* Added: features/function/dynamic_css.php
* Bug Fix: DB Sync Maintenance Mode
* Added: lct:::sql_scripts_dont_run
* Added: lct_get_mobile_threshold()
* Bug Fix: Avada Theme Options not saving right
* Updated: lct_get_dev_emails()

= 7.8 =
* Added: Directive to omit a page from lct_guid checks
* Added: ACF lct:::dont_check_page_links

= 7.7 =
* Bug Fix: add_action( 'wp_enqueue_scripts', [ $this, 'always_load_typekit' ] ); wp_add_inline_script() was not working right
* Updated Email

= 7.6 =
* Made /apps/ dir references dynamic

= 7.5 =
* Moved: plugin_reliant so we can better call reliant classes
* Moved: Static functions
* Moved: some ACF functions to static
* Added: A version conditional on add_action( 'set_object_terms', [ $this, 'set_object_terms' ], 10, 6 ); since ACF 5.4.0 is now doing this task

= 7.4 =
* New Action:
	* lct/always_check_admin
* Reorder plugin_reliant.php functions
* Added: fix_old_version_entry()
* Added: lct_int_action{}
* Added: add_action( 'lct/always_check_admin', [ $this, 'add_default_wp_users' ] ); So we can auto add necessary users
* Added: add_action( 'shutdown', [ $this, 'always_shutdown' ] );

= 7.3 =
* Added: Email Reminder Plugin
* Enhanced: Email Reminders
* Added: lct_pder_get_email_template()
* Bug Fix: class 'lct_acf_filter' does not have a method 'after_save_post' in V:\wamp\www\0lwi\system.brayandscarff.com\x\wp-includes\plugin.php on line 525
* Added: lct_acf_cache_delete()
* Added: lct_acf_get_old_field()
* Added: lct_acf_get_imploded_repeater()
* Added: filter: lct_startup_cleanup_guid_post_types
* Added: filter: lct_startup_cleanup_guid_post_types_excluded
* Modified: cleanup_guid_post_content(); so that there is a post_type check
* Added ACF Field: lct:::enable_email-reminder
* Enhanced: lct_enqueue() so we can use it in other plugins
* Added: add_filter( 'cron_schedules', [ $this, 'one_minute_dev' ] );
* Added: lct_add_url_site_to_content()
* Admin Onetime Cleanup
* Bug Fix: Action gform_enqueue_scripts; was not setup properly for pages with more than one form ID
* Improved the way JS::lct_acf_instant_save_update() gets the form's post_id
* Bug Fix: [is_user_logged_in] shortcode was not working properly
* Added: lct_public{}
* Added: lct_acf_public{ update_name_case() }
* Added: lct_acf_public{ update_email_case() }
* Added: lct_acf_get_key_post_type()
* Bug Fix: add_filter( 'wp_insert_post_data', [ $this, 'insert_post_data' ], 10, 2 ); Issues with this firing during lct_acf_instant_save
* Added: lct_acf_form_head_display_form()
* Added: render_field()
* Added: lct_acf_display_form()
* Improved: instant_save.js
* Bug Fix: ereminder was not loading on linux systems
* Minor Tweak: to lct_cleanup_guid(); ignore root level uploads directory

= 7.2 =
* Bug Fix: lct_cleanup_guid() No longer checking or processing revisions

= 7.1 =
* Fixed google_map_api inconsistencies
* Added: lct_get_json_thru_curl()

= 7.0 =
* Added: /admin/direct/* to replace ../_sql_CAREFUL/*
* Added: lct_wpsdb_action{}
* Added: New ACF Tab "Migrate DB"
* Added: New ACF field "Google Map API (Server)"


= 6.6 =
* Added: add_filter( 'widget_text', [ $this, 'widget_text_first' ] );
* Bug Fix: Moved add_filter( 'wp_nav_menu_items', [ $this, 'wp_nav_menu_items' ], 10, 2 ); so it would load at the right time
* Bug Fix: Added check for side_header_break_point to script_mobile_threshold()
* Added Better support for Avada Mobile Menu
* Added: add_action( 'init', [ $this, 'q2w3_fixed_widget_js_override' ], 100 );
* Bug Fix: script_mobile_threshold(); 500 Error on older Avada Versions
* Bug Fix: lct_available_tooltips{}; WP tooltips asset was not getting called on non-Avada sites
* Bug Fix: lct_features_shortcode_file_processor{}; Need to check the file to see if it is an external URL

= 6.5 =
* Enhanced: after_register_post_type(); Needed more path options
* Enhanced: fusion-menu-anchor
* Bug Fix: script_mobile_threshold(); var should be int
* Bug Fix: lct_gf_form_should_alter(); was illegally using $this
* Bug Fix: wp_head_last(); file_exists was looking at url
* Enhanced: lct_get_term_value()
* Enhanced: lct_get_parent_term_value()
* Bug Fix: wp_add_inline_script(); Need to randomize when handle exists
* Bug Fix: wp_add_inline_style(); Need to randomize when handle exists
* Bug Fix: lct_geocode(); Requests to this API must be over SSL

= 6.4 =
* Bug Fix: add_filter( 'lct_script_mobile_threshold', [ $this, 'script_mobile_threshold' ] ); Was not working for older Avada Versions
* Bug Fix: the_content Filter; by adding: add_filter( 'the_content', [ $this, 'bracket_cleanup' ], 100000 );
* Added: lct_i_esc_brackets()
* Added: lct_i_un_esc_brackets()
* Bug Fix: Needed lct_is_thanks_page() for non-ACF users
* Bug Fix: Fatal Error on activation

= 6.3 =
* Added Image Assets for: lct_available_google_mcp{}
* Clean Up: maps-utility-library-v3
* Bug Fix: lct_available_google_mcp{}; Priority Issue & wrong action call
* Added: lct_available_map_icons{}
* Bug Fix: lct_available_tooltips{}; Priority Issue
* Added: add_action( 'lct/maps_google_api', [ $this, 'maps_google_api' ] );
* Added: $deps to lct_enqueue()
* Bug Fix: Asset Loader; custom.min.css was not getting called properly
* Imported Project: /includes/map_icons/*

= 6.2 =
* Cleanup Code
* Cleanup: /features/function/static.php
* Added: add_action( 'plugins_loaded', [ $this, 'set_is_acf_active' ], 4 );
* Moved ACF functions to the proper location
* Removed: /admin/legacy.php
* Enhanced: add_filter( 'image_send_to_editor', 'lct_remove_site_root' );
* Added: lct_get_the_slug()
* Added: lct_is_blog()
* Added: Shortcode [lct_social_header]
* Imported: /includes/google/maps-utility-library-v3/markerclusterer.min.js
* Added: lct_available_google_mcp{}
* Added: add_action( 'w3tc_flush_all', [ $this, 'clear_transients' ] );

= 6.1 =
* Clean Up and reference wp_register_script() in fix_google_api_scripts()
* Bug Fix: Missing Space in <script>

= 6.0 =
* Cleaned Up /deprecated.php
* Cleaned Up /admin/__init.php
* Cleaned Up /available/tooltips.php
* Cleaned Up /extend_plugins/acf/_action.php
* Cleaned Up /features/__init.php
* Cleaned Up /features/shortcode/file_processor.php
* Cleaned Up /features/shortcode/post_content.php
* Cleaned Up /int/_function.php
* Cleaned Up $Avada_version
* Removed: font-awesome.scss
* Added: lct_features_class_asset_loader{}; To clean up the way we load assets
* Added: wp_add_inline_style(); in case the WP version is less than v4.5
* Added: wp_add_inline_script(); in case the WP version is less than v4.5
* Improved: remove_script_version()
* Added: unparse_url()
* Added: parse_query()
* Added: unparse_query()
* Added: lct_is_avada_version_any()
* Added: lct_is_avada_version_3_n_below()
* Added: add_filter( 'lct_script_mobile_threshold', [ $this, 'script_mobile_threshold' ] );
* Added: apply_filters( 'lct_script_mobile_threshold', '800' );
* Added: Avada #main autoresize support
* Bug Fix: Avada JS


= 5.40 =
* Modified: The way plugins_loaded is called in the plugin.
* Moved: Avada overrides
* Bug Fix: lct_t()
* Cleanup: lct_doing()
* Added: support for Avada v4.0
* Replaced: deprecated function get_currentuserinfo()
* Add More Avada CSS Support
* Avada CSS Cleanup
* Moved: instant_save.js
* Added: ACF lct:::enable_avada_css_page_defaults
* Added: ACF lct:::page_title_bar_auto
* Added: ACF lct:::page_title_bar_padding_top
* Added: ACF lct:::page_title_bar_padding_bottom
* Cleanup: Features Action
* Added: add_action( 'wp_enqueue_scripts', [ $this, 'always_load_google_fonts' ] );
* Added: add_action( 'lct_acf_single_load_google_fonts', [ $this, 'single_load_google_fonts' ], 10, 1 );
* Added: Shortcode [lct_load_gfont]
* Added: ACF lct:::load_google_fonts
* Reinstate lct_theme_chunk()
* Update: Avada-page_defaults.scss
* Enhanced: read_more()
* Cleanup: instant_save.js
* Cleanup: instant_save.php
* Bug Fix: For Avada versions that do not have of_options(), causing FATAL error
* Removed: add_filter( 'wpseo_opengraph_image', 'lct_opengraph_single_image_filter' ); as it was actually causing errors now with the newer version of Yoast SEO
* Added: Adobe Typekit Support
* Added: ACF load_typekit field
* Added: add_action( 'wp_enqueue_scripts', [ $this, 'always_load_typekit' ] );
* Added: add_action( 'lct_acf_single_load_typekit', [ $this, 'single_load_typekit' ], 10, 1 );
* Added: Shortcode [lct_acf_load_typekit]
* Bug Fix: Fixed fatal errors caused by ACF not being activated
* Bug Fix: lct_Avada_override{} on Avada v4.0.x
* Added: add_filter( 'init', [ $this, 'allow_comments_for_loop_only' ] );
* Changed Text Domain
* Added: Avada_clear()
* Bug Fix: add_filter( 'embed_handler_html', [ $this, 'embed' ], 10, 3 );
* Added: add_action( 'wp_enqueue_scripts', [ $this, 'fix_google_api_scripts' ], 999999 );
* Added: class to [get_directions]
* Bug Fix: Quote encoding issues on lct_check_for_nested_shortcodes()
* Added: Override for js-wp-editor.js
* Added: add_action( 'plugins_loaded', [ $this, 'set_is_fusion_core_active' ] );
* Cleaned up lct_tel_link{}
* Added: lct_internal_link{}
* Cleanup sprintf()'s
* lct_cleanup_guid() Cleanup
* Added: lct_is_html()
* Added: Shortcode [homeurl]
* Added: Shortcode [homeurl_non_www]
* Added: lct_get_taxonomy_by_path()
* lct_cleanup_guid() Cleanup
* do_shortcode filters Cleanup
* Allow External Files in shortcode_file_processor
* Added: add_filter( 'content_save_pre', [ $this, 'save_post_cleanup_guid_post_content' ], 11 );
* Added: add_filter( 'content_save_pre', [ $this, 'save_post_cleanup_guid_link_cleanup' ], 12 );
* Bug Fix: cleanup_guid_link_cleanup(); Was not catching custom post_types with post_type slug in permalink
* Added: Support for post_ids that do not exist in lct_cleanup_guid()
* Added: Support for checking the target of external links in lct_cleanup_guid()
* Added: wp-caption Custom CSS
* Bug Fix: in cleanup_guid_link_cleanup(); that caused multiple links in a single line to not update correctly.
* Bug Fix: in cleanup_guid_link_cleanup(); that caused style, title, etc. atts on an a tag to get removed.
* Added: selector_id to [link]
* Bug Fix: in cleanup_guid_link_cleanup(); that caused multiple links in a single line to not update correctly.


= 5.39 =
* WP v4.5 Ready
* Added: lct_features_class_mail{}
* Updated: lct_send_function_check_email()
* Removed call_outside/login.php
* Modified: lct_acf_get_key_taxonomy(), it is now more efficient
* Modified: lct_acf_get_key_user(), it is now more efficient
* Code Cleanup: taxonomies.php
* Added: add_action( "created_term", [ $this, 'created_term' ], 10, 3 );
* Added: lct_repair_acf_usermeta()
* Added: lct_repair_acf_postmeta()
* Added: lct_repair_acf_termmeta()
* Added: add_filter( "insert_user_meta", [ $this, 'insert_user_meta' ], 10, 3 );
* Bug Fix: in lct_acf_instant_save{add_comment()}
* Added: add_filter( 'wp_insert_post_data', [ $this, 'insert_post_data' ], 10, 2 );
* Modified: update_term_meta(), Added support for repeater fields
* Added: load_term_option_override()
* Added: set_term_option_override()
* Added: do_action( 'lct/acf/before_lct_acf_form_full', $zxza_options, $options );
* Modified: [lct_acf_form_full]
* Added: lct_explode_nth()
* Modified: theme_chunk()
* Modified: Video embed filter. Made it work much better
* Bug Fix: get_format_acf_value(); Display Error
* Modified lct_t(); to allow just a term object
* Refactored: lct_t()

= 5.38 =
* add_shortcode() Cleanup
* Added: [br]
* Added: [lct_br]
* Added: [faicon]
* gaTracker Code Cleanup
* Added: ACF lct:::get_directions
* Added: lct_get_gaTracker_onclick()
* Added: lct_i_append_dev_sb()
* Added: lct_i_get_gaTracker_category()
* Modified [get_directions]
* Modified shortcode_copyright()
* Added: Onetime [lct_bulk_post_content_search]
* Minor Tweaks
* Improve lct_check_for_nested_shortcodes() for esc_html
* Added: class to faicon()
* Allow lct_features_shortcode{} to be accessed outside of plugin
* CSS Tweak ACF
* Moved: lct_org() to plugin_reliant{}
* Added: lct_get_checkboxes()
* Added: lct_get_checkboxes_get_terms()
* Added: lct_get_terms()
* Added: lct_get_comment_meta_field_keys()
* Moved: lct_get_post_type_slug() to deprecated
* Modified: lct_get_field_data_array()
* Added: lct_get_field_data_get_users()
* Added: lct_get_sel_opts_get_users()
* Added: lct_get_users()
* Added: apply_filters( 'lct_field_data_array_obj_label_piece' );
* Added: lct_acf_get_key_taxonomy()
* Fixed: Bug in load_field_primary()
* Fixed: Bug in current_user_can_access()
* Added: Shortcode [lct_get_the_id]
* Added: Shortcode [lct_get_the_modified_date_time]
* Added: Shortcode [lct_get_the_date]
* Modified lct_acf_instant_save{}, so that lct_audit logs are not saved it a new post is being generated
* Modified: load_term_meta(), filtering out comments and users now
* Modified: update_term_meta(), filtering out comments and users now
* Added: lct_acf_get_key_user()
* Modified: echo_br()
* Added: lct_is_new_save_post()
* Moved lct_doing() to plugin_reliant.php
* Modified: [Avada_clear]
* Updated: Includes in extend_plugins/Avada/

= 5.37 =
* Added: add_action( 'edited_term_taxonomy', 'lct_edited_term_taxonomy', 10, 2 );
* Bug fix: in update_version()
* Added: add_action( 'avada_before_body_content', 'lct_avada_before_body_content' );
* Added: add_filter( 'acf/location/rule_values/comment', [ $this, 'register_comment_types_w_acf' ] );
* Bug Fix: in get_comment_types()
* Added: ACF Group Audit Type
* Added: ACF Group Review::: Site Info
* Added: lct_get_fixes_cleanups_message___lct_review_site_info()
* Added: lct_get_site_info()
* Added: lct_return()
* Added: add_action( 'init', [ $this, 'wp_init' ] );
* Added: add_filter( 'acf/update_value', [ $this, 'update_term_meta' ], 10, 3 );
* Added: add_filter( 'acf/load_value', [ $this, 'load_term_meta' ], 10, 3 );
* Added: add_action( 'acf/save_post', [ $this, 'after_save_post' ], 100001 );
* Converted to lct_admin_action{}
* Added: add_action( 'admin_init', [ $this, 'cleanup_profile_page' ] );
* Added: Some ACF Special Functions to handle the lame $post_ids
* Modified: lct_instant so that we can use it for users
* Added: google_map_api to ACF General Settings
* Added: lct_get_full_address()
* Added: shortcode [lct_acf_repeater_items]
* Plugin Cleanup
* Resolved (2): //TO-DO: cs - Maybe we want to narrow this down a bit - 12/7/2015 3:15 PM
* ACF filter cleanup
* Moved: lct_get_lct_useful_settings() to deprecated
* Modified: add_action( 'manage_acf-field-group_posts_custom_column', [ $this, 'field_groups_columns_values' ], 11 );
* Modified: add_filter( 'acf/get_field_groups', [ $this, 'export_title_mod' ], 11 );
* Modified: add_filter( 'acf/update_value', [ $this, 'non_ajax_update_value' ], 10, 3 );
* Added: add_filter( 'post_row_actions', [ $this, 'add_post_id' ], 2, 101 );
* Added: add_filter( 'media_row_actions', [ $this, 'add_post_id' ], 2, 101 );
* Added: add_filter( 'page_row_actions', [ $this, 'add_page_id' ], 2, 101 );
* Added: add_action( 'lct_after_register_post_type', [ $this, 'after_register_post_type' ], 10, 2 );
* Added: lct_taxonomies{}
* Added: lct_get_field_data_array()
* Added: lct_get_sel_opts()
* Added: lct_get_sel_opts_get_terms()
* Added: lct_org()
* Added: lct_status()
* Added: lct_tax_status()
* Added: lct_tax_public()
* Added: lct_tax_published()
* Added: lct_merge_w_select_blank()
* Added: lct_following()
* Added: lct_following_parent()
* Modified: the slow lct_acf_shortcode{shortcode_copyright()}
* Added: of_options() Avada Override
* Fixed: lct_return() (PHP Warning: implode(): Invalid arguments passed in)
* Fixed: Bug in add_filter( 'acf/update_value', [ $this, 'update_term_meta' ], 10, 3 );
* Fixed: Bug in add_filter( 'acf/load_value', [ $this, 'load_term_meta' ], 10, 3 );
* Added: apply_filters( 'lct_class_conditional_items', $the_items );
* Added: support for page notes
* Fixed: bad conditional_logic in support for page notes
* Added: ACF lct:::disable_image_sizes
* Added: admin.php?page=lct_cleanup_uploads
* Added: add_action( 'after_setup_theme', [ $this, 'remove_image_size' ], 11 );
* Added: add_action( 'admin_init', [ $this, 'remove_image_size' ], 11 );
* Bug Fix: in lct_legacy_rename_option_fields()
* Added: privacy_policy_page to shortcode_copyright()
* Minor lct_audit bug fix

= 5.36 =
* WP v4.4.2 Ready
* Minor gforms CSS tweak
* Minor Avada-gforms CSS tweak
* Fixed Bug: output of shortcode lct_wc_login_form
* Added lct_check_for_nested_shortcodes()
* Added add_filter( 'widget_text', [ $this, 'widget_text_final' ], 99999 );
* Added lct_doing()
* Added add_shortcode( 'is_user_logged_in', 'lct_sc_is_user_logged_in' );
* Fixed [embed] for vimeo
* Added [lct_get_the_title]
* Added lct_get_order_product_ids()
* Added lct_get_order_product_id_terms()
* Added lct_get_term_parent()
* Added lct_get_terms_parents()
* Added lct_get_terms_ids()
* Added [lct_get_the_permalink]
* Added: lct_is_thanks_page()
* Added: add_action( 'gform_confirmation', [ $this, 'query_string_add' ], 9999, 4 );
* Modified [lct_tel_link]; Added label support and lct_is_dev() check
* Added: lct_is_sandbox()
* Added: lct_is_dev_or_sb()

= 5.35 =
* New Action:
	* lct/ws_menu_editor #1
	* lct/ws_menu_editor #2
* Made sure the plugin was ready for Avada v3.9
* Finally fixed the add_action( 'plugins_loaded', 'lct_Fusion_Core_PageBuilder_override', 2 ); override
* Clean min CSS
* Minor CSS
* Minor gforms CSS Tweaks
* Minor Bug Fix in field_groups_columns_values()
* Moved call_outside/login.php
* Added lct_get_post_type_slug();
* Better way to explode items in add_filter( 'wp_nav_menu_items', 'lct_wp_nav_menu_items', 10, 2 );
* autosize tweaks
* Added: autosize to acf textarea
* Bug Fix: meta_value was saving in lct_get_fixes_cleanups_message___db_fix_apmmp_5545()
* Comment Bug
* Added support for analytics.js when using the Yoast GA Plugin to lct_tel_link shortcode
* Set all stars in a sub-label to gfield_required

= 5.34 =
* Converted gforms action functions to class: lct_gforms_action{}
* Converted gforms filter functions to class: lct_gforms_filter{}
* Converted gforms shortcode functions to class: lct_gforms_shortcode{}
* Simplified lct_path_theme()
* Simplified lct_path_theme_parent()
* Cleaned up features/function
* Completed: //TO-DO: cs - get this in an action - 7/29/2015 2:12 PM
* Converted features action functions to class: lct_features_action{}
* Cleaned up static shortcodes
* Replaced lct_path_theme() with get_stylesheet_directory()
* Code Cleanup
* Added: lct_gforms_filter::mobile_placeholder()
* Changed: $form to $gf_form
* Changed: $field to $gf_field
* Converted features filter functions to class: lct_features_filter{}
* Fixed Bug with lct_features_filter::embed()
* UD lct_Fusion_Core_PageBuilder{}

= 5.33 =
* Added: lct_wp_redirect()
* Added: non-logged in support for lct_acf_instant_save{}

= 5.32 =
* Improved: lct_jq_doc_ready() and allowed it to work in wp-admin or really any action
* Cleaned up debug functions
* Added: lct_timer_start() & lct_timer_end()
* renamed lct_clean_sb_url() to lct_use_lct_dev_url()
* Added: lct_use_lct_dev_url()
* Added: lct_is_dev()
* Clean up main class
* Cleanup lct_tel_link {}
* Improved: lct_acf_filter::show_admin_bar()

= 5.31 =
* WP v4.4 Ready

= 5.30 =
* Cleaned up require and include lines
* Changed include_once to include
* Modified lct_acf_form_full() so that it can process new_posts
* Added: lct_instant_startup()
* Added: do_action( 'lct_acf_new_post' );
* lct_jq_doc_ready is now allowed to run in wp_head and wp_footer
* Made lct_instant buttons hide a little quicker

= 5.29 =
* Modified: add_filter( 'acf/load_field', 'lct_acf_load_field_primary' );
	* We don't want to alter the class on the field editing page
* Minor bug tweaks
* Added: add_action( 'after_setup_theme', 'lct_after_setup_theme_ajax_disable_stuff' ); so we can disable crap on an ajax call
* wp_enqueue cleanup
* Added: add_action( 'wp_footer', 'lct_jq_doc_ready', 999 );
* Added: add_action( 'wp_footer', 'lct_wp_footer_style', 998 );
* Update include library autosize to v3.0.14
* Modified autosize in gform_enqueue_scripts
* fixed double ajax calls
* Added woo updater to load_updater_instances

= 5.28 =
* Added: lct_get_comment_type_lct_audit_settings()
* Improved: add_comment_lct_audit()
* Improved: lct_non_ajax_update_value()
* Added: add_filter( 'lct_get_format_acf_value', 'lct_get_format_acf_value', 10, 2 );
* Added: add_filter( 'lct_get_format_acf_date_picker', 'lct_get_format_acf_date_picker', 10, 2 );
* Added: add_filter( 'get_comments_number', 'lct_comment_count', 11, 1 );
* Added: add_filter( 'lct_get_comments_number', 'lct_comment_count', 11, 2 );
* Added: add_filter( 'lct_get_comments_number', 'lct_comment_count', 11, 2 );
* Added: Added: lct_get_role_cap_prefixes()
* Fixed the_label() function check
* Improved: lct_acf_form()
* Added: access support to lct_acf_form()
* Added: add_filter( 'lct_current_user_can_access', 'lct_current_user_can_access', 10, 2 );
* Added: add_filter( 'lct_current_user_can_view', 'lct_current_user_can_view', 10, 2 );
* Added: lct_get_role_cap_prefixes_only()
* Added: add_filter( 'acf/load_field', 'lct_acf_load_field_primary' );
* Improved can_view view
* Added Shortcode [lct_current_user_can]
* Added: add_action( 'shutdown', 'lct_after_redirection_apache_save' );

= 5.27 =
* Added: hidden-imp to front.css

= 5.26 =
* Fix the RedirectMatch bug in the redirection plugin

= 5.25 =
* Cleaned up and moved around some misplaced functions
* ADDED add_filter( 'wp_nav_menu_items', 'lct_wp_nav_menu_items', 10, 2 );
* Cleaned up main file
* ADDED lct_instant
* ADDED lct_acf_form & lct_acf_form_full
* Hot fix for lct_Fusion_Core_PageBuilder issue in Avada v3.8.8
* ADDED shortcode [lct_wc_login_form]
* Cleaned up create_lct_theme_chunk so it is better hidden on the front-end
* ADDED add_action( 'set_object_terms', 'lct_acf_set_object_terms', 10, 6 );
* ADDED acf.unload.active = false; to lct_instant
* Reworked lct_instant()
* ADDED add_action( 'lct_add_tax_to_user_admin_page', 'lct_add_tax_to_user_admin_page' );

= 5.24 =
* lct_is_in_page()

= 5.22 - 5.23 =
* Tweaked gform css

= 5.21 =
* ADDED lct_the_content_final()
* ADDED shortcode lct_amp
* ADDED shortcode get_directions

= 5.20 =
* WP Standards code reformat

= 5.19 =
* Tweaked lct_avada_save_options()

= 5.18 =
* Fixed bug with lct_acf_unsave_db_values()

= 5.17 =
* Add an Avada sanitize bug fix

= 5.16 =
* Code Reformat

= 5.15 =
* Bug Fix: lct_plugin_reliant_plugins_loaded
* Added lct_additional_primes action to do multiple user-agent primes with W3 total cache
* Bug Fix: Embed filter

= 5.14 =
* Responsive Video Embed

= 5.13 - 5.13.7 =
* Tweak Avada CSS
* Minor Bug fixes: PHP Warnings

= 5.12 - 5.12.2 =
* ADDED custom_post_type support
* ADDED override for Fusion_Core_PageBuilder
* ADDED shortcode to access theme_chucks
* Tweak theme_chucks
* Fixed theme_chunk shortcode bug

= 5.11 =
* ADDED add_shortcode( 'Avada_clear', 'Avada_clear' );
* Fixed return bug in the [clear] shortcode

= 5.10 - 5.10.11 =
* Fixed plugin activation hook
* Added code to manage _editzz Files
* Fixed mkdir bug
* Minor CSS Tweaks

= 5.9 =
* Fixed bugs that were causing sites without ACF installed to break
* Moved geocode functions to it's own file
* Added better failure checks to geocode functions

= 5.8 - 5.8.1 =
* WP v4.3.1 Ready
* Add OVER_QUERY_LIMIT check to lct_geocode()
* lct_gforms_css() Tweak

= 5.7 =
* NEW: add_filter( 'gform_enable_field_label_visibility_settings', 'lct_gform_enable_field_label_visibility_settings' );
* Only run lct_gforms_css when it is_gravity_page()
* re-organized gform items
* Modified: add_action( 'gform_enqueue_scripts', 'lct_gforms_css', 11 );
* Modified gforms CSS
* Modified Avada CSS
* NEW: add_filter( 'gform_multiselect_placeholder', 'set_multiselect_placeholder', 10, 2 );
* NEW: add_filter( 'gform_field_content', 'lct_gf_columns', 10, 5 ); 2 & 3 column forms
* NEW: add_shortcode( 'lct_gf_submit', 'lct_gf_sc_submit_button' );

= 5.6.1 =
* Added do_shortcode() to the [lct_copyright] shortcode

= 5.6 =
* Modified [lct_copyright] shortcode

= 5.5 =
* Fixed wp_enqueue_scripts bug

= 5.4 =
* Added shortcode [lct_read_more]

= 5.3 =
* Updated to work with new ACF Pro
* Added lct_send_function_check_email()
* Tweak to lct_url_root_site()

= 5.2 =
* Fixed non-LF Files
* Added get_label()
* Added the_label()

= 5.1 =
* Tweaked gforms.css

= 5.0 =
* WP v4.3 Ready
* Added Google Maps Geocode support
* Added lct_geocode()
* Added lct_parse_address_components()
* Added lct_get_street_address()
* Added lct_get_city()
* Added lct_get_zip()
* CSS Tweaks
* Added lct_get_state()
* redo of gforms.css
* redo of avada.css
* Removed lct_use_placeholders_instead_of_labels();
* Moved extend_plugins\gforms\_function.php
* Moved gform_button_custom_class
* Fixed lct_gform_submit_button() bug
* Moved lct_store_gforms_array()
* File restructure
* Moved disable_avada_css
* Added lct_all_fields_extra_options()
* Changed include to include_once
* Added add_filter( 'embed_handler_html', 'lct_embed_handler_html', 10, 3 );

= 4.3.7 =
* Tweaked acf.css

= 4.3.6 =
* Added lct_maintenance_Avada_fix()

= 4.3.5 =
* Added: add_filter( 'itsec_filter_server_config_file_path', 'lct_itsec_filter_server_config_file_path', 10, 2 );
* Fixed buggy lct_path_site_wp()

= 4.3.4 =
* Added strpos_array()

= 4.3.3 =
* Fixed a bug that will now redirect to the directory to check if a file exists and then it will return false. lct_js_uploads_dir() & lct_css_uploads_dir()

= 4.3.2 =
* Added lct_avada_save_options() to do_action( 'avada_save_options' );
* Fixed bug that was showing an empty admin bar to visitors

= 4.3.1 =
* Added shortcode [theme_css]
* Cleaned up some code bugs in /misc/shortcodes.php
* Stopped saving directory in uploads when the plugin is activated
* Deprecated lct_get_test()
* Deprecated lct_php()
* Deprecated lct_copyyear()
* Moved /misc/shortcodes.php TO /features/shortcode/shortcode.php
* Moved /features/lct_post_content_shortcode/index.php TO /features/shortcode/lct_post_content.php
* Moved /features/shortcode_tel_link.php TO /features/shortcode/tel_link.php
* Moved /features/misc_functions.php TO /features/function/_function.php
* Code Reformat plugin wide
* Deprecated lct_css_uploads_dir()
* Deprecated lct_js_uploads_dir()
* Moved lct_theme_css() into file_processor.php
* Finished lct_shortcode_file_processor()
* gforms.css tweaks
* Added add_filter( 'avada_blog_read_more_excerpt', 'lct_acf_avada_blog_read_more_excerpt' );
* Added ACF Group Theme Settings: Avada
* Added Fix/Cleanup 'DB Fix::: Add Post Meta to Multiple Posts'
* Removed lct_acf_get_fields_mapped()
* Removed lct_acf_get_mapped_fields_of_object()

= 4.3 =
* WP v4.2.3 Ready
* Added shortcode.php to ACF
* Added $prefix_2 to lct_acf_get_fields_by_parent()
* Added lct_acf_get_mapped_fields()
* Added Shortcode [lct_copyright]
* Added lct_acf_get_mapped_fields_of_object()
* Added lct_acf_get_fields_by_object()
* Added Shortcodes group to lct_acf_op_main_settings

= 4.2.2.27 =
* Moved: lct_remove_admin_bar() to lct_show_admin_bar(), under /acf/filter.php
* Modified lct_show_admin_bar() so that it will be a dynamic setting in ACF Main Settings, rather than being hard coded.
* Updated fields in lct_acf_op_main_settings_groups.php to support lct_show_admin_bar()

= 4.2.2.26 =
* Added: add_filter( 'acf/load_field/type=radio', 'lct_acf_options_check_show_params' );
* Updated acf.css
* Modified Fixes and cleanups
* Completed: //TO-DO: cs - Make this dynamic - 7/23/2015 12:08 AM By adding lct_acf_get_fields_by_parent()\
* Added lct_acf_recap_field_settings()
* Added lct_acf_create_table()
* Added lct_acf_field_groups_columns()
* Added lct_acf_field_groups_columns_values()
* Added lct_acf_acf_export_title_mod()
* Fixed tel_link version bug.
* Added lct_create_find_and_replace_arrays()
* Code refactoring
* acf.css update
* Added local groups

= 4.2.2.25 =
* Added extend_plugin dir, now we can properly include functions. But only is the plugin is loaded up first. YAY!
* Added support for plugin acf
* Added lct_acf_print_scripts()
* Added wp-admin css
* Added Function to create fixed and clean ups
* Added a New ACF Fix/Cleanup (db_fix_add_taxonomy_field_data)

= 4.2.2.24 =
* Added lct_get_dev_emails() function
* Added lct_is_user_a_dev() function
* Change C:/s to W:/wamp

= 4.2.2.23 =
* Added disable_auto_set_user_timezone feature
* Reformat code

= 4.2.2.22 =
* Added target as an att to the shortcode lct_shortcode_link()

= 4.2.2.21 =
* Added query as an att to the shortcode lct_shortcode_link()

= 4.2.2.20 =
* Added Shortcode lct_post_content_shortcode()
* Added lct_is_in_url()

= 4.2.2.19 =
* Updated front.css

= 4.2.2.18 =
* Reworked all the code for lct_shortcode_link()

= 4.2.2.17 =
* added lct_tel_link shortcode

= 4.2.2.16 =
* Added lct_close_all_pings_and_comments()

= 4.2.2.14 - 4.2.2.15 =
* Fixed up functions to be better:
	* lct_select_options()
	* lct_select_options_default()
	* lct_get_select_blank()

= 4.2.2.13 =
* Moved lct_select_options_meta_key() to deprecated
* added lct_get_select_blank() in display/options.php
* reformatted code in display/options.php

= 4.2.2.11 - 4.2.2.12 =
* Tweaks to gforms CSS
* Tweaks to css

= 4.2.2.10 =
* Tweaks to lct_remove_site_root

= 4.2.2.9 =
* Minor Tweaks

= 4.2.2.5 - 4.2.2.8 =
* ADDED Cleanup Guid

= 4.2.2.4 =
* Debug function tweaks

= 4.2.2.2 - 4.2.2.3 =
* Additions to Avada.css

= 4.2.2.1 =
* Additions to Avada.css
* ADDED to gforms.css

= 4.2.2 =
* WP 4.2.2 Ready
* Additions to front.css

= 4.2.1.3 =
* Minor Tweaks

= 4.2.1.2 =
* Updated to iFrame Resizer v2.8.6
* Code cleanup

= 4.2.1.1 =
* Avada.css Tweaks

= 4.2.1 =
* WP 4.2.1 Ready

= 4.1.26 =
* Removed labob
* WP v4.2 Ready

= 4.1.25 =
* Removed CRLF

= 4.1.15 - 4.1.21 =
* Added Avada Theme Support
* gform css tweaks
* Code Cleanup

= 4.1.14 =
* CJ Spam Filter

= 4.1.13 =
* added includes: iframe_resizer

= 4.1.12 =
* ADDED lct_preload

= 4.1.11 =
* WP 4.1.1 Ready
* Added: lct_get_user_agent_info
* Fixed Browscap.php

= 4.1.9 - 4.1.10 =
* changes to wpauto selection
* lct_useful_settings default settings and checker

= 4.1.2 - 4.1.8 =
* Minor tweaks

= 4.1 =
* WP 4.1 Ready
* jumped version to match WP

= 1.4.28 =
* ADDED Shortcode: P_R_O

= 1.4.27 =
* Minor tweaks

= 1.4.26 =
* Added: Shortcode [lct_admin_onetime_script_run]

= 1.4.17 thru 1.4.25 =
* Minor tweaks

= 1.4.16 =
* ADDED lct_send_to_console()

= 1.4.8 thru 1.4.15 =
* Minor tweaks

= 1.4.7 =
* WP 4.0 Ready

= 1.4.6 =
* ADDED lct_opengraph_site_name

= 1.4.5 =
* Minor tweaks

= 1.4.4 =
* Fixed login shortcode

= 1.4.3 =
* Fixed ")[" issues
* Added ga.js

= 1.4.2 =
* Fixed global class issue

= 1.4.1 =
* Fixed global class issue

= 1.4 =
* Changed the is_plugin_active() code

= 1.2.95 =
* minor tweaks
* Added lct_opengraph_single_image_filter

= 1.2.94 =
* Tested for WP 3.9.2 Compatibility

= 1.2.93 =
* minor tweaks
* added sitemap-generator

= 1.2.92 =
* minor tweaks

= 1.2.91 =
* minor tweaks

= 1.2.9 =
* ADDED lct_textimage_linking_shortcode
* ADDED lct_admin_bar_on_bottom

= 1.2.8 =
* Fixed Bugs in Gravity Form Placeholder Functionality
* Added Login Form

= 1.2.7 =
* Added Gravity Form Placeholder Functionality

= 1.2.6 =
* Add Setting Menu

= 1.2.5 =
* Added function echo_br()

= 1.2.4 =
* Added Fix Multisite plugins_url issue

= 1.2.3 =
* Fixed conflict with function 'wpautop_Disable'

= 1.2.2 =
* Updated Globals

= 1.2.1 =
* Updated Globals

= 1.2 =
* Tested for WP 3.9.1 Compatibility
* Cleaned up code.
* Updated Globals

= 1.1.1 =
* [get_test] bug fix.

= 1.1 =
* Added debug/functions.php
* Added new shortcode items

= 1.0 =
* First Release

== Upgrade Notice ==
none
