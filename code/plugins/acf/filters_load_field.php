<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @property array args
 * @property lct   zxzp
 * @verified 2017.02.24
 */
class lct_acf_filters_load_field
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.02.24
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
	 * @since    2017.21
	 * @verified 2017.02.24
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
		add_filter( 'acf/load_field/name=' . zxzacf( 'css_files' ), [ $this, 'css_files' ] );

		add_filter( 'acf/load_field/name=' . zxzacf( 'js_files' ), [ $this, 'js_files' ] );

		add_filter( 'acf/load_field', [ $this, 'process_shortcodes' ] );
	}


	/**
	 * Get a list of all our custom CSS files
	 *
	 * @param $field
	 *
	 * @return mixed
	 * @since    7.69
	 * @verified 2019.12.05
	 */
	function css_files( $field )
	{
		if ( lct_acf_is_field_group_editing_page() ) { //Don't load on ACf edit pages
			return $field;
		}


		$choices = [];
		$files   = lct_list_files( lct_path_theme( '/custom/css/' ) );


		if ( ! empty( $files ) ) {
			foreach ( $files as $file ) {
				if ( in_array( $file, [ 'main.min.css', 'main.css', 'a.min.css', 'a.css', 'a-media.min.css', 'a-media.css', 'gforms.min.css', 'gforms.css' ] ) ) {
					continue;
				}


				if ( strpos( $file, '.css' ) !== false ) {
					$dup = '';


					if ( strpos( $file, '.min.css' ) === false ) {
						$tmp = str_replace( '.css', '.min.css', $file );


						if ( in_array( $tmp, $files ) ) {
							$dup = ' (duplicate, do not use, not minified)';
						}
					}


					$choices[ $file ] = str_replace( [ '.min.css', '.css' ], '', $file ) . $dup;
				}
			}
		}


		$field['choices'] = $choices;


		return $field;
	}


	/**
	 * Get a list of all our custom JS files
	 *
	 * @param $field
	 *
	 * @return mixed
	 * @since    2017.2
	 * @verified 2019.12.05
	 */
	function js_files( $field )
	{
		if ( lct_acf_is_field_group_editing_page() ) { //Don't load on ACf edit pages
			return $field;
		}


		$choices = [];
		$files   = lct_list_files( lct_path_theme( '/custom/js/' ) );


		if ( ! empty( $files ) ) {
			foreach ( $files as $file ) {
				if ( in_array( $file, [ 'custom.min.js', 'custom.js' ] ) ) {
					continue;
				}


				if ( strpos( $file, '.js' ) !== false ) {
					$dup = '';


					if ( strpos( $file, '.min.js' ) === false ) {
						$tmp = str_replace( '.js', '.min.js', $file );


						if ( in_array( $tmp, $files ) ) {
							$dup = ' (duplicate, do not use, not minified)';
						}
					}


					$choices[ $file ] = str_replace( [ '.min.js', '.js' ], '', $file ) . $dup;
				}
			}
		}


		$field['choices'] = $choices;


		return $field;
	}


	/**
	 * Process shortcodes inside ACF field elements
	 * Currently we only need to check the message, instructions & wrapper class
	 * we can add more if needed
	 *
	 * @param $field
	 *
	 * @return mixed
	 * @since    2017.68
	 * @verified 2023.12.15
	 */
	function process_shortcodes( $field )
	{
		if (
			lct_acf_is_field_group_editing_page() //Don't load on ACf edit pages
			|| ! lct_acf_is_process_shortcodes_needed()
		) {
			return $field;
		}


		if ( isset( $field['message'] ) ) {
			$field['message'] = $this->check_sc_element( $field['message'] );
		}


		if ( isset( $field['instructions'] ) ) {
			$field['instructions'] = $this->check_sc_element( $field['instructions'] );
		}


		if ( isset( $field['wrapper']['class'] ) ) {
			$field['wrapper']['class'] = $this->check_sc_element( $field['wrapper']['class'] );
		}


		return $field;
	}


	/**
	 * Process shortcodes inside ACF field elements
	 *
	 * @param string $field_el
	 *
	 * @return string
	 * @date         2020.11.27
	 * @since        2020.14
	 * @verified     2020.11.27
	 * @noinspection PhpMissingParamTypeInspection
	 */
	function check_sc_element( $field_el )
	{
		if (
			! empty( $field_el )
			&& (
				strpos( $field_el, '[' ) !== false
				|| strpos( $field_el, '{' ) !== false
			)
		) {
			if ( strpos( $field_el, '{' ) !== false ) {
				$field_el = lct_check_for_nested_shortcodes( $field_el );
			}


			$field_el = do_shortcode( $field_el );
		}


		return $field_el;
	}
}
