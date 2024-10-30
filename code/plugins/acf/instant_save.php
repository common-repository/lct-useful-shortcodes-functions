<?php
/** @noinspection PhpMissingFieldTypeInspection */
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.11.07
 */
class lct_acf_instant_save
{
	public $meta;
	public $vars;


	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2019.10.02
	 */
	function __construct( $args = [] )
	{
		//Store $args
		$this->args = $args;


		//Store parent class, maybe
		if ( $this->args['load_parent'] ) {
			$this->zxzp = lct();
		}


		//vars
		$this->meta             = new stdClass();
		$this->meta->acf_form   = zxzacf( 'acf_form' );
		$this->meta->audit_type = zxzacf( 'audit_type' );
		$this->meta->field_key  = zxzacf( 'field_key' );
		$this->meta->value      = zxzacf( 'value' );
		$this->meta->value_old  = zxzacf( 'value_old' );


		$this->vars = [
			'post_id'              => null,
			'info_id'              => null,
			'info_type'            => null,
			'task'                 => null,
			$this->meta->field_key => null,
			$this->meta->value     => null,
			$this->meta->value_old => null,
		];


		//Setup WordPress action and filter hooks
		$this->load_hooks();
	}


	/**
	 * Setup WordPress action and filter hooks
	 *
	 * @since    2017.21
	 * @verified 2020.09.07
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
		 * Actions
		 */
		add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

		add_action( 'wp_ajax_' . zxzu( 'acf_instant_save' ), [ $this, 'ajax_handler' ] );
		add_action( 'wp_ajax_nopriv_' . zxzu( 'acf_instant_save' ), [ $this, 'ajax_handler' ] );


		/**
		 * Filters
		 */
		add_filter( 'lct/lct_acf_instant_save/add_comment/user', [ $this, 'add_comment_user_is_cron' ], 999 );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		if ( lct_ajax_only() ) {
			add_action( 'lct/acf/instant_save/do_function_later', 'lct_do_function_later', 11 );

			add_action( 'acf/delete_value', [ $this, 'tag_as_deleted' ], 10, 3 );
		}


		/**
		 * special
		 */
		if ( lct_acf_get_option_raw( 'audit_save_postmeta' ) ) {
			add_filter( 'acf/update_value', [ $this, 'non_ajax_add_comment' ], 100, 3 );

			add_filter( 'acf/location/rule_values/comment', [ $this, 'register_rule_values_comment' ] );
		}
	}


	/**
	 * Register Scripts
	 *
	 * @since    0.0
	 * @verified 2019.02.06
	 */
	function wp_enqueue_scripts()
	{
		lct_enqueue_script( zxzu( 'acf_instant_save' ), lct_get_root_url( 'assets/js/instant_save.min.js' ), true, [ 'jquery' ], lct_get_setting( 'version' ), true );
	}


	/**
	 * Register Scripts
	 *
	 * @since    2019.1
	 * @verified 2019.02.11
	 */
	function admin_enqueue_scripts()
	{
		lct_admin_enqueue_script( zxzu( 'acf_instant_save' ), lct_get_root_url( 'assets/js/instant_save.min.js' ), true, [ 'jquery' ], lct_get_setting( 'version' ), true );
	}


	/**
	 * Do some stuff ajax style
	 *
	 * @since    0.0
	 * @verified 2022.01.21
	 */
	function ajax_handler()
	{
		$r                   = [];
		$r['status']         = 'nothing_happened';
		$r['status_message'] = 'Nothing Happened';


		//Check for fishy actions going on
		if ( ! wp_verify_nonce( $_POST['nonce'], $_POST['screen'] ) ) {
			$r['status']         = 'nonce_failed';
			$r['status_message'] = 'Nonce Failed';
			echo wp_json_encode( $r );
			exit;
		}


		//We do not want to continue if there is not a post_id set
		if ( empty( $_POST['post_id'] ) ) {
			$r['status']         = 'post_id_not_set';
			$r['status_message'] = 'post_id Not Set';
			echo wp_json_encode( $r );
			exit;
		}


		$this->vars = wp_parse_args( $_POST, $this->vars );


		if ( $this->vars['post_id'] !== null ) {
			$decoded = acf_decode_post_id( $this->vars['post_id'] );


			$this->vars['info_id']   = (int) $decoded['id'];
			$this->vars['info_type'] = $decoded['type'];


			if ( $decoded['type'] === 'post' ) {
				$this->vars['post_id'] = (int) $this->vars['post_id'];
			}
		}


		if ( ! empty( $_POST[ $this->meta->acf_form ] ) ) {
			parse_str( $_POST[ $this->meta->acf_form ], $acf_form );


			if ( ! empty( $acf_form['_acf_form'] ) ) {
				$acf_form['_acf_form'] = afwp_acf_maybe_json_decode( acf_decrypt( $acf_form['_acf_form'] ) );
			}


			$_POST[ $this->meta->acf_form ] = $acf_form;
		}


		if ( $r = apply_filters( 'lct/acf/instant_save/pre_process_task', null, $this ) ) {
			echo wp_json_encode( $r );
			exit;
		}


		switch ( $this->vars['task'] ) {
			case 'update':
				$error                                = 'Unknown';
				$updated                              = 'no';
				$this->vars[ $this->meta->field_key ] = str_replace( [ 'acf-', '-input' ], '', $this->vars[ $this->meta->field_key ] );
				$field                                = get_field_object( $this->vars[ $this->meta->field_key ], $this->vars['post_id'], false, false );
				if ( $field['_name'] === lct_status() ) {
					$current_db_value = [ lct_acf_get_old_field_value( $this->vars[ $this->meta->field_key ], $this->vars['post_id'], false ) ];
				} else {
					$current_db_value = get_metadata( $this->vars['info_type'], $this->vars['post_id'], $field['name'] );
				}


				if (
					$this->vars[ $this->meta->value_old ] === null
					&& isset( $current_db_value[0] )
					&& ( $value_old = lct_acf_get_old_field_value( $this->vars[ $this->meta->field_key ], $this->vars['post_id'], false ) ) !== false
				) {
					$this->vars[ $this->meta->value_old ] = $value_old;
				} elseif (
					lct_is_empty( $this->vars[ $this->meta->value_old ] )
					&& ! isset( $current_db_value[0] )
				) {
					$this->vars[ $this->meta->value_old ] = LCT_VALUE_UNSET;
				}


				if ( empty( $field ) ) {
					$updated = 'dont_update';
				}


				if (
					$updated !== 'dont_update'
					&& $this->vars[ $this->meta->value ] == $this->vars[ $this->meta->value_old ]
					&& (
						$this->vars[ $this->meta->value_old ] !== null
						|| (
							$this->vars[ $this->meta->value_old ] === null
							&& $this->vars[ $this->meta->value ] === null
						)
					)
				) {
					$updated = 'dont_update';
				}


				if (
					lct_acf_is_repeater_subfield( $field, $this->vars['post_id'] )
					&& $updated !== 'dont_update'
					&& $this->vars[ $this->meta->value ] === ''
					&& in_array( $this->vars[ $this->meta->value_old ], [ null, LCT_VALUE_UNSET, LCT_VALUE_EMPTY ] )
				) {
					$updated = 'dont_update';
				}


				if (
					isset( $field['field_type'] )
					&& $field['field_type'] === 'multi_select'
				) {
					if (
						! empty( $this->vars[ $this->meta->value ] )
						&& ! is_array( $this->vars[ $this->meta->value ] )
					) {
						$this->vars[ $this->meta->value ] = explode( '||', $this->vars[ $this->meta->value ] );
					}


					if (
						! empty( $this->vars[ $this->meta->value_old ] )
						&& ! is_array( $this->vars[ $this->meta->value_old ] )
					) {
						$this->vars[ $this->meta->value_old ] = explode( '||', $this->vars[ $this->meta->value_old ] );
					}
				}


				if ( $updated !== 'dont_update' ) {
					switch ( $field['type'] ) {
						case 'date_picker':
							if ( $date = DateTime::createFromFormat( $field['display_format'], $this->vars[ $this->meta->value ] ) ) {
								$this->vars[ $this->meta->value ] = get_gmt_from_date( $date->format( $field['return_format'] ) );
							}


							if (
								! empty( $this->vars[ $this->meta->value_old ] )
								&& ( $date = DateTime::createFromFormat( $field['display_format'], $this->vars[ $this->meta->value_old ] ) )
							) {
								$this->vars[ $this->meta->value_old ] = get_gmt_from_date( $date->format( $field['return_format'] ) );
							}
							break;


						default:
					}


					if (
						lct_acf_is_repeater_subfield( $field, $this->vars['post_id'] )
						&& isset( $this->vars['info_id'] )
					) {
						if (
							! empty( $_POST[ $this->meta->acf_form ]['acf'] )
							&& ( $repeater_value = $_POST[ $this->meta->acf_form ]['acf'] )
							&& ( $repeater_value = lct_clean_acf_repeater( $repeater_value ) )
							&& ( $parent_field = lct_find_repeater_field( $repeater_value, $this->vars[ $this->meta->field_key ] ) )
							&& isset( $repeater_value[ $parent_field ] )
						) {
							$first_key = array_key_first( $repeater_value[ $parent_field ] );

							if ( ! isset( $repeater_value[ $parent_field ][ $first_key ][ $this->vars[ $this->meta->field_key ] ] ) ) {
								$repeater_value[ $parent_field ][ $first_key ][ $this->vars[ $this->meta->field_key ] ] = $this->vars[ $this->meta->value ];
							}


							update_field( $parent_field, $repeater_value[ $parent_field ], $this->vars['post_id'] );


							/**
							 * @date     0.0
							 * @since    0.0
							 * @verified 2021.08.30
							 */
							do_action( 'lct/acf/instant_save/repeater_updated', $this->vars['info_id'], $this->vars );


							$updated = 'yes';
						} elseif (
							$this->vars[ $this->meta->value ] === null
							&& lct_get_setting( 'instant_save_deleted' )
						) {
							$updated = 'already_updated';
						} elseif (
							lct_get_setting( 'instant_save_deleted' )
							|| $this->vars['info_id'] === 'new_post'
						) {
							$updated = 'already_updated';
						}
					} elseif (
						( $new_value_successful = update_field( $this->vars[ $this->meta->field_key ], $this->vars[ $this->meta->value ], $this->vars['post_id'] ) )
						|| (
							$new_value_successful === false
							&& lct_get_setting( 'instant_save_deleted' )
						)
					) {
						$updated = 'yes';
					} else {
						if ( $new_error = lct_get_later( zxzu( 'instant' ), $this->vars[ $this->meta->field_key ] ) ) {
							$updated = 'error';
							$error   = $new_error;
						} else {
							/**
							 * Check if update_field() failed, because it was already updated
							 */
							if ( get_field( $this->vars[ $this->meta->field_key ], $this->vars['post_id'], false ) == $this->vars[ $this->meta->value ] ) {
								$updated = 'already_updated';
							}
						}
					}
				}


				switch ( $updated ) {
					case 'yes':
						$this->vars['status']                  = 'updated';
						$this->vars[ $this->meta->audit_type ] = 'acf_update_field';


						$r['status']         = $this->vars['status'];
						$r['status_message'] = 'Updated';


						$this->add_comment( $this->vars );
						break;


					case 'already_updated':
						$r['status']         = 'updated';
						$r['status_message'] = 'Updated';
						break;


					case 'dont_update':
						$r['status']         = 'nothing_changed';
						$r['status_message'] = 'Nothing Changed';
						break;


					case 'error':
						$r['status']         = 'error';
						$r['status_message'] = 'Error: ' . $error;
						break;


					default:
						$r['status']         = 'error';
						$r['status_message'] = 'Error: ' . $error;
				}
				break;


			default:
		}


		/**
		 * @date     0.0
		 * @since    0.0
		 * @verified 2021.08.30
		 */
		do_action( 'lct/acf/instant_save/do_function_later' );


		$r = apply_filters( 'lct/acf/instant_save/final_response', $r );


		echo wp_json_encode( $r );
		exit;
	}


	/**
	 * Add an audit comment
	 *
	 * @param $vars
	 *
	 * @return bool|false|int
	 * @since    0.0
	 * @verified 2024.04.11
	 */
	function add_comment( $vars )
	{
		if (
			! lct_acf_get_option_raw( 'audit_save_postmeta' )
			|| //don't complete if we don't want to save postmeta
			empty( $vars['info_id'] )
			|| //don't complete if there is not a post_id field
			$vars['info_type'] !== 'post'
			|| //don't complete if it is NOT a post field
			lct_is_new_save_post( $vars['post_id'] )
			|| //don't complete if it is a new post
			! $vars[ $this->meta->field_key ]
			|| //don't complete if we don't have a field key
			(
				! empty( $vars['post_id'] )
				&& //don't complete if we want to exclude pages and this is a page
				lct_acf_get_option_raw( 'audit_save_postmeta_exclude_pages' )
				&& get_post_type( $vars['post_id'] ) === 'page'
			)
			|| (
				! empty( $vars['post_id'] )
				&& //don't complete if we want to exclude pages and this is a page
				( $post_type = get_post_type( $vars['post_id'] ) )
				&& in_array( $post_type, [ 'nav_menu_item', 'attachment' ] )
			)
		) {
			return false;
		}


		$user            = apply_filters( 'lct/lct_acf_instant_save/add_comment/user', wp_get_current_user() );
		$current_field   = get_field_object( $vars[ $this->meta->field_key ], $vars['post_id'], false, false );
		$comment_content = '';


		if ( ! $vars[ $this->meta->value ] ) {
			if (
				! in_array( $current_field['type'], [ 'radio', 'true_false' ] )
				|| (
					$vars[ $this->meta->value ] !== 0
					&& $vars[ $this->meta->value ] !== '0'
				)
			) {
				$vars[ $this->meta->value ] = LCT_VALUE_EMPTY;
			}
		}

		if ( ! $vars[ $this->meta->value_old ] ) {
			if (
				! in_array( $current_field['type'], [ 'radio', 'true_false' ] )
				|| (
					$vars[ $this->meta->value_old ] !== 0
					&& $vars[ $this->meta->value_old ] !== '0'
				)
			) {
				$vars[ $this->meta->value_old ] = LCT_VALUE_EMPTY;
			}
		}


		if ( $vars[ $this->meta->audit_type ] === 'acf_update_field' ) {
			$audit_settings  = lct_get_comment_type_lct_audit_settings();
			$comment_content = $audit_settings['audit_types']['acf_update_field']['text'];
		}


		$args       = [
			'comment_post_ID'      => $vars['post_id'],
			'user_id'              => $user->ID,
			'comment_author'       => $user->display_name,
			'comment_author_email' => $user->user_email,
			'comment_content'      => $comment_content,
			'comment_approved'     => 1,
			'comment_type'         => 'lct_audit',
		];
		$comment_id = wp_insert_comment( $args );


		if (
			( $audit_group = acf_get_fields( lct_get_setting( 'acf_group_audit' ) ) )
			&& ! empty( $audit_group )
		) {
			foreach ( $this->meta as $meta_item ) {
				foreach ( $audit_group as $audit_key => $audit_field ) {
					if ( $audit_field['_name'] === $meta_item ) {
						update_field( $audit_field['key'], $vars[ $meta_item ], lct_c( $comment_id ) );


						unset( $audit_group[ $audit_key ] );


						break;
					}
				}
			}
		}


		return $comment_id;
	}


	/**
	 * Add an audit comment when the value is updated outside of instant_save (non-ajax)
	 *
	 * @param $value
	 * @param $post_id
	 * @param $field
	 *
	 * @return mixed
	 * @since    0.0
	 * @verified 2022.08.24
	 */
	function non_ajax_add_comment( $value, $post_id, $field )
	{
		//bail early if already ran
		if ( lct_did() ) {
			return $value;
		}


		$should_add_comment              = false;
		$vars                            = [];
		$vars['post_id']                 = $post_id;
		$vars[ $this->meta->field_key ]  = $field['key'];
		$vars[ $this->meta->value ]      = $value;
		$vars[ $this->meta->audit_type ] = 'acf_update_field';


		$vars = wp_parse_args( $vars, $this->vars );


		if ( $vars['post_id'] !== null ) {
			$decoded = acf_decode_post_id( $vars['post_id'] );


			$vars['info_id']   = (int) $decoded['id'];
			$vars['info_type'] = $decoded['type'];


			if ( $decoded['type'] === 'post' ) {
				$vars['post_id'] = (int) $vars['post_id'];
			}
		}


		$current_db_value = get_metadata( $vars['info_type'], $vars['post_id'], $field['_name'] );


		if (
			(
				lct_doing()
				&& //don't complete through ajax
				isset( $vars['action'] )
				&& $vars['action'] === zxzu( 'acf_instant_save' )
			)
			|| empty( $vars['info_id'] )
			|| //don't complete if there is not a post_id field
			$vars['info_type'] !== 'post'
			|| //don't complete if it is NOT a post field
			lct_is_new_save_post( $vars['post_id'] )
			|| //don't complete if it is a new post
			! $vars[ $this->meta->field_key ]
			|| //don't complete if we don't have a field key
			$field['type'] === 'repeater' //don't complete if it is repeater field
		) {
			lct_undid();


			return $value;
		}


		if (
			$vars[ $this->meta->value_old ] === null
			&& isset( $current_db_value[0] )
			&& ( $value_old = lct_acf_get_old_field_value( $vars[ $this->meta->field_key ], $vars['post_id'], false ) ) !== false
		) {
			$vars[ $this->meta->value_old ] = $value_old;


			/**
			 * Repeater Fields
			 */
			if (
				! empty( $field['name'] )
				&& ! empty( $field['parent'] )
				&& ( $field_parent = acf_get_field( $field['parent'] ) )
				&& ! empty( $field_parent )
				&& $field_parent['type'] === 'repeater'
			) {
				$vars[ $this->meta->value_old ] = get_field( $field['name'], $vars['post_id'], false );
			}
		} elseif ( ! isset( $current_db_value[0] ) ) {
			$vars[ $this->meta->value_old ] = LCT_VALUE_UNSET;
		}


		if ( is_array( $vars[ $this->meta->value_old ] ) ) {
			if ( ! is_array( $vars[ $this->meta->value ] ) ) {
				$vars[ $this->meta->value ] = [ $vars[ $this->meta->value ] ];
			}


			$check     = array_values( $vars[ $this->meta->value_old ] );
			$check_old = $vars[ $this->meta->value_old ];
			$check_new = $vars[ $this->meta->value ];


			if (
				isset( $check[0] )
				&& is_array( $check[0] )
			) {
				foreach ( $check_old as $k => $v ) {
					$check_old[ $k ] = lct_cache_key( $v );
				}


				foreach ( $check_new as $k => $v ) {
					$check_new[ $k ] = lct_cache_key( $v );
				}
			}


			if ( afwp_acf_json_encode( $check_old ) !== afwp_acf_json_encode( $check_new ) ) {
				$should_add_comment = true;
			}
		} elseif ( $vars[ $this->meta->value_old ] != $vars[ $this->meta->value ] ) {
			$should_add_comment = true;
		}


		if ( $should_add_comment ) {
			$this->add_comment( $vars );
		}


		lct_undid();


		return $value;
	}


	/**
	 * Register all our awesome comment_types with ACF
	 *
	 * @param $choices
	 *
	 * @return mixed
	 * @since    5.37
	 * @verified 2016.12.09
	 */
	function register_rule_values_comment( $choices )
	{
		$comment_types = lct_get_setting( 'comment_types_monitored' );


		if ( ! empty( $comment_types ) ) {
			foreach ( $comment_types as $comment_type ) {
				$choices[ $comment_type ] = $comment_type;
			}
		}


		return $choices;
	}


	/**
	 * Mark the saved field as deleted
	 *
	 * @unused   param $post_id
	 * @unused   param $field_name
	 * @unused   param $field
	 * @since    2019.1
	 * @verified 2019.02.06
	 */
	function tag_as_deleted()
	{
		lct_update_setting( 'instant_save_deleted', true );
	}


	/**
	 * Update the user profile if the comment is being submitted by a cron job
	 *
	 * @param WP_User|int $user
	 *
	 * @return WP_User
	 */
	function add_comment_user_is_cron( $user )
	{
		if (
			empty( $user->ID )
			&& lct_doing_cron()
		) {
			$user->display_name = 'Cron Job';
		}


		return $user;
	}
}
