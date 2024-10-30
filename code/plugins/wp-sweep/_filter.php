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
class lct_wp_sweep_filter
{
	public $style;
	public $limit_details;


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
	 * @since    2017.33
	 * @verified 2017.04.20
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
		add_filter( 'wp_sweep_details', [ $this, 'wp_sweep_details' ], 10, 2 );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * We need more info about the sweeps
	 *
	 * @param $details
	 * @param $name
	 *
	 * @return array|null|object
	 * @since    7.19
	 * @verified 2018.12.12
	 */
	function wp_sweep_details( $details, $name )
	{
		if ( empty( $details ) ) {
			return $details;
		}


		global $wpdb;

		$this->style         = 'display: inline-block';
		$this->limit_details = 500;


		switch ( $name ) {
			case 'revisions':
				$new_details = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT * FROM `$wpdb->posts` WHERE `post_type` = %s LIMIT %d",
						'revision',
						$this->limit_details
					)
				);


				if ( ! empty( $new_details ) ) {
					$details = [];


					foreach ( $new_details as $t ) {
						$vars = [
							'Post ID'    => $t->ID,
							'Post Type'  => get_post_type( $t->post_parent ),
							'Post Title' => $t->post_title,
						];


						$details[] = $this->detail_compiler( $vars );
					}
				}
				break;


			case 'orphan_term_relationships':
				$new_details = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT `tt`.`taxonomy`, `tt`.`term_taxonomy_id`, `tt`.`term_id`, `t`.`name`, `tr`.`object_id` 
						FROM `$wpdb->term_relationships` AS tr 
						INNER JOIN `$wpdb->term_taxonomy` AS tt ON `tr`.`term_taxonomy_id` = `tt`.`term_taxonomy_id` 
						LEFT JOIN `$wpdb->terms` AS t ON `t`.`term_id` = `tt`.`term_id` 
						WHERE `tt`.`taxonomy` NOT IN ('" . implode( '\',\'', [ 'link_category' ] ) . "') 
							AND `tr`.`object_id` NOT IN (SELECT ID FROM `$wpdb->posts`) LIMIT %d",
						$this->limit_details
					)
				);


				if ( ! empty( $new_details ) ) {
					$details = [];


					foreach ( $new_details as $t ) {
						$vars = [
							'Taxonomy'    => $t->taxonomy,
							'Taxonomy ID' => $t->term_taxonomy_id,
							'Term ID'     => $t->term_id,
							'Term Name'   => $t->name,
							'Object ID'   => $t->object_id
						];


						$details[] = $this->detail_compiler( $vars );
					}
				}
				break;


			case 'duplicated_postmeta':
				$new_details = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT COUNT(`meta_id`) AS count, `meta_key`, `post_id` FROM `$wpdb->postmeta` GROUP BY `post_id`, `meta_key`, `meta_value` HAVING count > %d LIMIT %d",
						1,
						$this->limit_details
					)
				);


				if ( ! empty( $new_details ) ) {
					$details = [];


					foreach ( $new_details as $t ) {
						$vars = [
							'Post ID'   => $t->post_id,
							'Meta Key'  => $t->meta_key,
							'Post Type' => get_post_type( $t->post_id ),
						];


						$details[] = $this->detail_compiler( $vars );
					}
				}
				break;


			default:
				/*
				$new_details = ''; //Query


				if ( ! empty( $new_details ) ) {
					$details = [];


					foreach ( $new_details as $t ) {
						$vars   = [];


						$details[] = $this->detail_compiler( $vars );
					}
				}
				*/
		}


		return $details;
	}


	/**
	 * Make the HTML of a detail
	 *
	 * @param $vars
	 *
	 * @return string
	 * @since    2018.22
	 * @verified 2018.12.12
	 */
	function detail_compiler( $vars )
	{
		$detail = [];


		foreach ( $vars as $key => $var ) {
			$detail[] = "<strong style='{$this->style};width: 15%;'>{$key}:</strong> <span style='{$this->style};width: 80%;'>{$var}</span>";
		}


		return lct_return( $detail, '<br />' );
	}
}
