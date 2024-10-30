<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2016.12.09
 */
class lct_acf_public_choices
{
	public $pretty_preset_choices;


	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2016.12.09
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
	 * @since    7.51
	 * @verified 2019.11.26
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


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		if ( lct_wp_admin_non_ajax() ) {
			//Add to pretty_preset_choices
			$this->pretty_preset_choices = [
				'pretty_acf_field_groups_list'     => 'List of ACF Field Groups',
				'exhaustive_acf_field_groups_list' => 'List of ACF Field Groups (Exhaustive Title)',
				'pretty_acf_fields_list'           => 'List of ACF Fields',
				'pretty_state_list'                => 'List of the US states',
				'pretty_state_abbr_value_list'     => 'List of the US states (with abbr. as value)',
				'pretty_wp_roles'                  => 'WordPress Roles',
				'pretty_wp_caps'                   => 'WordPress Capabilities',
				'pretty_wp_post_types'             => 'WordPress Post Types',
				'pretty_wp_taxonomies'             => 'WordPress Taxonomies',
				'pretty_us_timezone'               => 'List of the US Time Zones',
				'pretty_gforms_forms'              => 'List of all Gravity Forms',
				'pretty_months'                    => 'List of Months of the Year',
				'pretty_months_leading_zero'       => 'List of Months of the Year (value w/ leading zero)',
			];


			add_filter( 'lct/acf/pretty_preset_choices', [ $this, 'add_to_pretty_preset_choices' ] );

			add_filter( 'lct/acf/pretty_roles_n_caps', [ $this, 'add_to_pretty_roles_n_caps' ] );
		}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Added our functions to the preset dropdown
	 *
	 * @param $choices
	 *
	 * @return array
	 * @since    7.51
	 * @verified 2016.12.20
	 */
	function add_to_pretty_preset_choices( $choices )
	{
		$choices = array_merge( $choices, $this->pretty_preset_choices );


		return $choices;
	}


	/**
	 * Array of US states
	 *
	 * @return array
	 * @since    7.51
	 * @verified 2016.12.09
	 */
	function pretty_state_list_data()
	{
		$states = [
			'Alabama'              => 'Alabama',
			'Alaska'               => 'Alaska',
			'Arizona'              => 'Arizona',
			'Arkansas'             => 'Arkansas',
			'California'           => 'California',
			'Colorado'             => 'Colorado',
			'Connecticut'          => 'Connecticut',
			'Delaware'             => 'Delaware',
			'District of Columbia' => 'District of Columbia',
			'Florida'              => 'Florida',
			'Georgia'              => 'Georgia',
			'Hawaii'               => 'Hawaii',
			'Idaho'                => 'Idaho',
			'Illinois'             => 'Illinois',
			'Indiana'              => 'Indiana',
			'Iowa'                 => 'Iowa',
			'Kansas'               => 'Kansas',
			'Kentucky'             => 'Kentucky',
			'Louisiana'            => 'Louisiana',
			'Maine'                => 'Maine',
			'Maryland'             => 'Maryland',
			'Massachusetts'        => 'Massachusetts',
			'Michigan'             => 'Michigan',
			'Minnesota'            => 'Minnesota',
			'Mississippi'          => 'Mississippi',
			'Missouri'             => 'Missouri',
			'Montana'              => 'Montana',
			'Nebraska'             => 'Nebraska',
			'Nevada'               => 'Nevada',
			'New Hampshire'        => 'New Hampshire',
			'New Jersey'           => 'New Jersey',
			'New Mexico'           => 'New Mexico',
			'New York'             => 'New York',
			'North Carolina'       => 'North Carolina',
			'North Dakota'         => 'North Dakota',
			'Ohio'                 => 'Ohio',
			'Oklahoma'             => 'Oklahoma',
			'Oregon'               => 'Oregon',
			'Pennsylvania'         => 'Pennsylvania',
			'Rhode Island'         => 'Rhode Island',
			'South Carolina'       => 'South Carolina',
			'South Dakota'         => 'South Dakota',
			'Tennessee'            => 'Tennessee',
			'Texas'                => 'Texas',
			'Utah'                 => 'Utah',
			'Vermont'              => 'Vermont',
			'Virginia'             => 'Virginia',
			'Washington'           => 'Washington',
			'West Virginia'        => 'West Virginia',
			'Wisconsin'            => 'Wisconsin',
			'Wyoming'              => 'Wyoming',
		];


		return $states;
	}


	/**
	 * Add US states to choices
	 * Call:
	 * add_filter( 'acf/prepare_field/name=' . zxzacf( 'field' ), [ lct()->acf_public_choices, 'pretty_state_list' ] );
	 *
	 * @param $field
	 *
	 * @return array
	 * @since    7.51
	 * @verified 2020.01.21
	 */
	function pretty_state_list( $field )
	{
		$field['placeholder'] = 'Choose a State...';

		$field['choices'] = $this->pretty_state_list_data();


		return $field;
	}


	/**
	 * Array of US states with the lowercase abbr. as the value
	 *
	 * @return array
	 * @date     2022.10.19
	 * @since    2022.10
	 * @verified 2022.10.19
	 */
	function pretty_state_abbr_value_list_data()
	{
		$states = [
			'al' => 'Alabama',
			'ak' => 'Alaska',
			'az' => 'Arizona',
			'ar' => 'Arkansas',
			'ca' => 'California',
			'co' => 'Colorado',
			'ct' => 'Connecticut',
			'de' => 'Delaware',
			'dc' => 'District of Columbia',
			'fl' => 'Florida',
			'ga' => 'Georgia',
			'hi' => 'Hawaii',
			'id' => 'Idaho',
			'il' => 'Illinois',
			'in' => 'Indiana',
			'ia' => 'Iowa',
			'ks' => 'Kansas',
			'ky' => 'Kentucky',
			'la' => 'Louisiana',
			'me' => 'Maine',
			'md' => 'Maryland',
			'ma' => 'Massachusetts',
			'mi' => 'Michigan',
			'mn' => 'Minnesota',
			'ms' => 'Mississippi',
			'mo' => 'Missouri',
			'mt' => 'Montana',
			'ne' => 'Nebraska',
			'nv' => 'Nevada',
			'nh' => 'New Hampshire',
			'nj' => 'New Jersey',
			'nm' => 'New Mexico',
			'ny' => 'New York',
			'nc' => 'North Carolina',
			'nd' => 'North Dakota',
			'oh' => 'Ohio',
			'ok' => 'Oklahoma',
			'or' => 'Oregon',
			'pa' => 'Pennsylvania',
			'ri' => 'Rhode Island',
			'sc' => 'South Carolina',
			'sd' => 'South Dakota',
			'tn' => 'Tennessee',
			'tx' => 'Texas',
			'ut' => 'Utah',
			'vt' => 'Vermont',
			'va' => 'Virginia',
			'wa' => 'Washington',
			'wv' => 'West Virginia',
			'wi' => 'Wisconsin',
			'wy' => 'Wyoming',
		];


		return $states;
	}


	/**
	 * Add US states to choices with the lowercase abbr. as the value
	 * Call:
	 * add_filter( 'acf/prepare_field/name=' . zxzacf( 'field' ), [ lct()->acf_public_choices, 'pretty_state_abbr_value_list' ] );
	 *
	 * @param $field
	 *
	 * @return array
	 * @date     2022.10.19
	 * @since    2022.10
	 * @verified 2022.10.19
	 */
	function pretty_state_abbr_value_list( $field )
	{
		$field['placeholder'] = 'Choose a State...';

		$field['choices'] = $this->pretty_state_abbr_value_list_data();


		return $field;
	}


	/**
	 * Full list of ACF field groups
	 *
	 * @return array
	 * @since    7.51
	 * @verified 2019.03.25
	 */
	function pretty_acf_field_groups_list_data()
	{
		$groups       = acf_get_field_groups();
		$field_groups = [];
		$choices      = [];


		if ( ! empty( $groups ) ) {
			foreach ( $groups as $group ) {
				$key = $group['menu_order'];

				if ( isset( $choices[ $group['menu_order'] ] ) ) {
					$key = lct_rand( 'z' );
				}


				$choices[ $key ] = [ 'key' => $group['key'], 'title' => $group['title'] ];
			}


			ksort( $choices );
			$choices = array_values( $choices );


			foreach ( $choices as $key => $choice ) {
				$field_groups[ $choice['key'] ] = $choice['title'];
			}
		}


		return $field_groups;
	}


	/**
	 * Add ACF field groups to choices
	 * Call:
	 * add_filter( 'acf/prepare_field/name=' . zxzacf( 'field' ), [ lct()->acf_public_choices, 'pretty_acf_field_groups_list' ] );
	 *
	 * @param $field
	 *
	 * @return array
	 * @since    7.51
	 * @verified 2016.12.10
	 */
	function pretty_acf_field_groups_list( $field )
	{
		$field['choices'] = $this->pretty_acf_field_groups_list_data();


		return $field;
	}


	/**
	 * Full list of ACF fields
	 *
	 * @param array $field
	 *
	 * @return array
	 * @since    2019.27
	 * @verified 2019.11.26
	 */
	function pretty_acf_fields_list_data( $field )
	{
		$return_fields = [];


		if (
			( $group = apply_filters( 'lct/pretty_acf_fields_list_data/group', null, $field ) )
			&& ( $fields = acf_get_fields( $group ) )
			&& ! empty( $fields )
		) {
			$choices = [];


			foreach ( $fields as $f ) {
				$key = $f['menu_order'];

				if ( isset( $choices[ $key ] ) ) {
					$key = lct_rand( 'z' );
				}


				$choices[ $key ] = [ 'key' => $f['key'], 'label' => $f['label'] ];
			}


			ksort( $choices );
			$choices = array_values( $choices );


			foreach ( $choices as $choice ) {
				$return_fields[ $choice['key'] ] = $choice['label'];
			}
		}


		return $return_fields;
	}


	/**
	 * Add ACF fields to choices
	 * Call:
	 * add_filter( 'acf/prepare_field/name=' . zxzacf( 'field' ), [ lct()->acf_public_choices, 'pretty_acf_fields_list' ] );
	 *
	 * @param array $field
	 *
	 * @return array
	 * @since    2019.27
	 * @verified 2019.11.26
	 */
	function pretty_acf_fields_list( $field )
	{
		$field['choices'] = $this->pretty_acf_fields_list_data( $field );


		return $field;
	}


	/**
	 * Full list of ACF field groups with exhaustive title
	 *
	 * @return array
	 * @since    2017.34
	 * @verified 2019.03.25
	 */
	function exhaustive_acf_field_groups_list_data()
	{
		$groups       = acf_get_field_groups();
		$field_groups = [];
		$choices      = [];


		if ( ! empty( $groups ) ) {
			foreach ( $groups as $group ) {
				$key = $group['menu_order'];

				if ( isset( $choices[ $group['menu_order'] ] ) ) {
					$key = lct_rand( 'z' );
				}


				$title = $group['title'] . ' [' . $group['key'] . '] (' . $group['location'][0][0]['param'] . ' ' . $group['location'][0][0]['operator'] . ' ' . $group['location'][0][0]['value'] . ')';


				$choices[ $key ] = [ 'key' => $group['key'], 'title' => $title ];
			}


			ksort( $choices );
			$choices = array_values( $choices );


			foreach ( $choices as $key => $choice ) {
				$field_groups[ $choice['key'] ] = $choice['title'];
			}
		}


		return $field_groups;
	}


	/**
	 * Add ACF field groups with exhaustive title to choices
	 * Call:
	 * add_filter( 'acf/prepare_field/name=' . zxzacf( 'field' ), [ lct()->acf_public_choices, 'exhaustive_acf_field_groups_list' ] );
	 *
	 * @param $field
	 *
	 * @return array
	 * @since    2017.34
	 * @verified 2017.05.09
	 */
	function exhaustive_acf_field_groups_list( $field )
	{
		$field['choices'] = $this->exhaustive_acf_field_groups_list_data();


		return $field;
	}


	/**
	 * Full list of WordPress Roles
	 *
	 * @return array
	 * @since    2017.34
	 * @verified 2024.02.29
	 */
	function pretty_wp_roles_data()
	{
		global $wp_roles;

		$roles = [];


		if (
			! empty( $wp_roles )
			&& ! empty( $wp_roles->roles )
		) {
			foreach ( $wp_roles->roles as $role_key => $role ) {
				$roles[ $role_key ] = $role['name'];
			}


			/**
			 * Sort
			 */
			$keys = array_keys( $roles );
			array_multisort( $roles, SORT_ASC, SORT_REGULAR, $keys );
			$roles = array_combine( $keys, $roles );
		}


		return $roles;
	}


	/**
	 * Add WordPress Roles to choices
	 * Call:
	 * add_filter( 'acf/prepare_field/name=' . zxzacf( 'field' ), [ lct()->acf_public_choices, 'pretty_wp_roles' ] );
	 *
	 * @param $field
	 *
	 * @return array
	 * @since    2017.34
	 * @verified 2022.11.16
	 */
	function pretty_wp_roles( $field )
	{
		$field['placeholder'] = 'Choose a Role...';


		$field['choices'] = apply_filters( 'lct/pretty_wp_roles', $this->pretty_wp_roles_data() );


		return $field;
	}


	/**
	 * Full list of WordPress Capabilities
	 *
	 * @return array
	 * @since    2017.34
	 * @verified 2017.04.27
	 */
	function pretty_wp_caps_data()
	{
		global $wp_roles;

		$caps = [];


		if (
			! empty( $wp_roles )
			&& ! empty( $wp_roles->roles['administrator'] )
		) {
			foreach ( $wp_roles->get_role( 'administrator' )->capabilities as $cap => $cap_status ) {
				if ( strpos( $cap, 'level_' ) === 0 ) {
					continue;
				}

				$caps[ $cap ] = $cap;
			}


			ksort( $caps );
		}


		return $caps;
	}


	/**
	 * Add WordPress Capabilities to choices
	 * Call:
	 * add_filter( 'acf/prepare_field/name=' . zxzacf( 'field' ), [ lct()->acf_public_choices, 'pretty_wp_caps' ] );
	 *
	 * @param $field
	 *
	 * @return array
	 * @since    2017.34
	 * @verified 2017.04.27
	 */
	function pretty_wp_caps( $field )
	{
		$field['choices'] = apply_filters( 'lct/pretty_wp_caps', $this->pretty_wp_caps_data() );


		return $field;
	}


	/**
	 * Added our functions to the preset dropdown
	 *
	 * @param $choices
	 *
	 * @return array
	 * @since    2017.34
	 * @verified 2017.04.29
	 */
	function add_to_pretty_roles_n_caps( $choices )
	{
		$choices = array_merge(
			$choices,
			[ 'Roles' => $this->pretty_wp_roles_data() ],
			[ 'Caps' => $this->pretty_wp_caps_data() ]
		);


		return $choices;
	}


	/**
	 * Full list of WordPress Post Types
	 *
	 * @param null $field
	 *
	 * @return array
	 * @since    2017.34
	 * @verified 2018.03.20
	 */
	function pretty_wp_post_types_data( $field = null )
	{
		$args                    = [
			'_builtin' => false,
		];
		$post_types_list         = get_post_types( $args, 'object' );
		$post_types_list['page'] = get_post_type_object( 'page' );
		$post_types_list['post'] = get_post_type_object( 'post' );


		$hidden_post_types = [
			'acf-field',
			'acf-field-group',
		];
		$hidden_post_types = apply_filters( 'lct/pretty_wp_post_types_data/hidden_post_types', $hidden_post_types, $field );
		$hidden_post_types = array_unique( $hidden_post_types );

		$post_types = [];
		$ref        = [];


		if ( ! empty( $post_types_list ) ) {
			foreach ( $post_types_list as $post_type => $post_type_obj ) {
				if ( in_array( $post_type, $hidden_post_types ) ) {
					continue;
				}


				$label = acf_get_post_type_label( $post_type );


				$post_types[ $post_type ] = $label;


				//Increase counter
				if ( ! isset( $ref[ $label ] ) ) {
					$ref[ $label ] = 0;
				}

				$ref[ $label ] ++;
			}


			//Get slugs
			foreach ( array_keys( $post_types ) as $i ) {
				if ( $ref[ $post_types[ $i ] ] > 1 ) {
					$post_types[ $i ] .= ' (' . $i . ')';
				}
			}


			ksort( $post_types );
		}


		return $post_types;
	}


	/**
	 * Add WordPress Post Types to choices
	 * Call:
	 * add_filter( 'acf/prepare_field/name=' . zxzacf( 'field' ), [ lct()->acf_public_choices, 'pretty_wp_post_types' ] );
	 *
	 * @param $field
	 *
	 * @return array
	 * @since    2017.34
	 * @verified 2018.03.20
	 */
	function pretty_wp_post_types( $field )
	{
		$field['choices'] = apply_filters( 'lct/pretty_wp_post_types', $this->pretty_wp_post_types_data( $field ) );


		return $field;
	}


	/**
	 * Full list of WordPress Taxonomies
	 *
	 * @param null $field
	 *
	 * @return array
	 * @since    2017.34
	 * @verified 2018.03.20
	 */
	function pretty_wp_taxonomies_data( $field = null )
	{
		$args                        = [
			'_builtin' => false,
		];
		$taxonomies_list             = get_taxonomies( $args, 'object' );
		$taxonomies_list['category'] = get_taxonomy( 'category' );


		$hidden_taxonomies = [];
		$hidden_taxonomies = apply_filters( 'lct/pretty_wp_taxonomies_data/hidden_taxonomies', $hidden_taxonomies, $field );
		if ( ! empty( $hidden_taxonomies ) ) {
			$hidden_taxonomies = array_unique( $hidden_taxonomies );
		}

		$taxonomies = [];
		$ref        = [];


		if ( ! empty( $taxonomies_list ) ) {
			foreach ( $taxonomies_list as $taxonomy => $taxonomy_obj ) {
				if ( in_array( $taxonomy, $hidden_taxonomies ) ) {
					continue;
				}

				$name = $taxonomy_obj->labels->singular_name;

				$taxonomies[ $taxonomy ] = $name;


				//Increase counter
				if ( ! isset( $ref[ $name ] ) ) {
					$ref[ $name ] = 0;
				}

				$ref[ $name ] ++;
			}


			//Get slugs
			foreach ( array_keys( $taxonomies ) as $i ) {
				if ( $ref[ $taxonomies[ $i ] ] > 1 ) {
					$taxonomies[ $i ] .= ' (' . $i . ')';
				}
			}


			ksort( $taxonomies );
		}


		return $taxonomies;
	}


	/**
	 * Add WordPress Taxonomies to choices
	 * Call:
	 * add_filter( 'acf/prepare_field/name=' . zxzacf( 'field' ), [ lct()->acf_public_choices, 'pretty_wp_taxonomies' ] );
	 *
	 * @param $field
	 *
	 * @return array
	 * @since    2017.34
	 * @verified 2018.03.20
	 */
	function pretty_wp_taxonomies( $field )
	{
		$field['choices'] = apply_filters( 'lct/pretty_wp_taxonomies', $this->pretty_wp_taxonomies_data( $field ) );


		return $field;
	}


	/**
	 * Array of US time zones
	 *
	 * @return array
	 * @since    2017.34
	 * @verified 2017.05.09
	 */
	function pretty_us_timezone_data()
	{
		$tzs = [
			'America/Los_Angeles' => 'Pacific',
			'America/Denver'      => 'Mountain',
			'America/Chicago'     => 'Central',
			'America/New_York'    => 'Eastern',
			'America/Phoenix'     => 'Mountain no DST',
			'America/Adak'        => 'Hawaii',
			'Pacific/Honolulu'    => 'Hawaii no DST',
			'America/Anchorage'   => 'Alaska',
		];


		return $tzs;
	}


	/**
	 * Add US time zones to choices
	 * Call:
	 * add_filter( 'acf/prepare_field/name=' . zxzacf( 'field' ), [ lct()->acf_public_choices, 'pretty_us_timezone' ] );
	 *
	 * @param $field
	 *
	 * @return array
	 * @since    2017.34
	 * @verified 2017.05.09
	 */
	function pretty_us_timezone( $field )
	{
		$field['choices'] = $this->pretty_us_timezone_data();


		return $field;
	}


	/**
	 * Full list of gforms forms
	 *
	 * @return array
	 * @since    2018.32
	 * @verified 2018.03.20
	 */
	function pretty_gforms_forms_data()
	{
		if ( ! class_exists( 'GFForms' ) ) {
			return [];
		}


		$gf_forms = RGFormsModel::get_forms( null, 'title' );
		$forms    = [];


		if ( ! empty( $gf_forms ) ) {
			foreach ( $gf_forms as $gf_form ) {
				$forms[ $gf_form->id ] = trim( $gf_form->title );
			}


			asort( $forms );
		}


		return $forms;
	}


	/**
	 * Add gforms forms to choices
	 * Call:
	 * add_filter( 'acf/prepare_field/name=' . zxzacf( 'field' ), [ lct()->acf_public_choices, 'pretty_gforms_forms' ] );
	 *
	 * @param $field
	 *
	 * @return array
	 * @since    2018.32
	 * @verified 2018.03.20
	 */
	function pretty_gforms_forms( $field )
	{
		if ( ! class_exists( 'GFForms' ) ) {
			return $field;
		}


		$field['choices'] = apply_filters( 'lct/pretty_gforms_forms', $this->pretty_gforms_forms_data() );


		return $field;
	}


	/**
	 * Full list of Months of the Year
	 *
	 * @return array
	 * @since    2018.68
	 * @verified 2018.11.07
	 */
	function pretty_months_data()
	{
		$r = [
			1  => 'January',
			2  => 'February',
			3  => 'March',
			4  => 'April',
			5  => 'May',
			6  => 'June',
			7  => 'July',
			8  => 'August',
			9  => 'September',
			10 => 'October',
			11 => 'November',
			12 => 'December',
		];


		return $r;
	}


	/**
	 * Add Months of the Year to choices
	 * Call:
	 * add_filter( 'acf/prepare_field/name=' . zxzacf( 'field' ), [ lct()->acf_public_choices, 'pretty_months' ] );
	 *
	 * @param $field
	 *
	 * @return array
	 * @since    2018.68
	 * @verified 2018.11.07
	 */
	function pretty_months( $field )
	{
		$field['choices'] = $this->pretty_months_data();


		return $field;
	}


	/**
	 * Add Months of the Year to choices, value w/ leading zero
	 * Call:
	 * add_filter( 'acf/prepare_field/name=' . zxzacf( 'field' ), [ lct()->acf_public_choices, 'pretty_months_leading_zero' ] );
	 *
	 * @param $field
	 *
	 * @return array
	 * @since    2019.1
	 * @verified 2019.01.14
	 */
	function pretty_months_leading_zero( $field )
	{
		$field['choices'] = $this->pretty_months_data();


		foreach ( $field['choices'] as $k => $v ) {
			unset( $field['choices'][ $k ] );


			$k = sprintf( '%02d', $k );


			$field['choices'][ $k ] = $v;
		}


		return $field;
	}
}
