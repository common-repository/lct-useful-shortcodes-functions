<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @verified 2017.01.11
 */
class lct_siw_widget
{
	/**
	 * Start up the class
	 *
	 * @param $args
	 *
	 * @verified 2017.01.11
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
	 * @since    7.70
	 * @verified 2017.01.11
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
		add_action( 'simple_image_widget_field-image_align', [ $this, 'field_image_align' ], 10, 2 );

		add_action( 'simple_image_widget_field-image_alt', [ $this, 'field_image_alt' ], 10, 2 );

		add_action( 'simple_image_widget_field-image_title', [ $this, 'field_image_title' ], 10, 2 );


		/**
		 * filters
		 */
		add_filter( 'simple_image_widget_fields', [ $this, 'simple_image_widget_fields' ], 10, 2 );

		add_filter( 'simple_image_widget_template_data', [ $this, 'simple_image_widget_template_data' ] );

		add_filter( 'simple_image_widget_template_paths', [ $this, 'simple_image_widget_template_paths' ] );

		add_filter( 'simple_image_widget_hidden_fields', [ $this, 'simple_image_widget_hidden_fields' ] );


		//if ( lct_frontend() ) {}


		//if ( lct_wp_admin_all() ) {}


		//if ( lct_wp_admin_non_ajax() ) {}


		//if ( lct_ajax_only() ) {}
	}


	/**
	 * Add some of our own fields
	 *
	 * @param $fields
	 * @param $id_base
	 *
	 * @return array
	 * @since    7.70
	 * @verified 2017.01.11
	 */
	function simple_image_widget_fields(
		$fields,
		/** @noinspection PhpUnusedParameterInspection */
		$id_base
	) {
		$fields[] = 'image_align';
		$fields[] = 'image_alt';
		$fields[] = 'image_title';


		return $fields;
	}


	/**
	 * One of our custom fields
	 *
	 * @param $instance
	 * @param $class
	 *
	 * @since    7.70
	 * @verified 2017.01.11
	 */
	function field_image_align( $instance, $class )
	{ ?>
		<p class="">
			<label for="<?php /** @noinspection PhpUndefinedMethodInspection */
			echo esc_attr( $class->get_field_id( 'image_align' ) ); ?>"><?php _e( 'Image Alignment:', 'TD_LCT' ); ?></label>
			<select name="<?php /** @noinspection PhpUndefinedMethodInspection */
			echo esc_attr( $class->get_field_name( 'image_align' ) ); ?>" id="<?php /** @noinspection PhpUndefinedMethodInspection */
			echo esc_attr( $class->get_field_id( 'image_align' ) ); ?>" class="widefat">
				<option value="">Default</option>
				<option value="center" <?php selected( esc_attr( $instance['image_align'] ), 'center' ); ?>>Center</option>
				<option value="left" <?php selected( esc_attr( $instance['image_align'] ), 'left' ); ?>>Left</option>
				<option value="right" <?php selected( esc_attr( $instance['image_align'] ), 'right' ); ?>>Right</option>
			</select>
		</p>
	<?php }


	/**
	 * One of our custom fields
	 *
	 * @param $instance
	 * @param $class
	 *
	 * @since    7.70
	 * @verified 2017.01.11
	 */
	function field_image_alt( $instance, $class )
	{ ?>
		<p class="">
			<label for="<?php /** @noinspection PhpUndefinedMethodInspection */
			echo esc_attr( $class->get_field_id( 'image_alt' ) ); ?>"><?php _e( 'Alt Tag:', 'TD_LCT' ); ?></label>
			<input type="text" name="<?php /** @noinspection PhpUndefinedMethodInspection */
			echo esc_attr( $class->get_field_name( 'image_alt' ) ); ?>" id="<?php /** @noinspection PhpUndefinedMethodInspection */
			echo esc_attr( $class->get_field_id( 'image_alt' ) ); ?>" value="<?php echo esc_attr( $instance['image_alt'] ); ?>" class="widefat">
		</p>
	<?php }


	/**
	 * One of our custom fields
	 *
	 * @param $instance
	 * @param $class
	 *
	 * @since    7.70
	 * @verified 2017.01.11
	 */
	function field_image_title( $instance, $class )
	{ ?>
		<p class="">
			<label for="<?php /** @noinspection PhpUndefinedMethodInspection */
			echo esc_attr( $class->get_field_id( 'image_title' ) ); ?>"><?php _e( 'Image Title Tag:', 'TD_LCT' ); ?></label>
			<input type="text" name="<?php /** @noinspection PhpUndefinedMethodInspection */
			echo esc_attr( $class->get_field_name( 'image_title' ) ); ?>" id="<?php /** @noinspection PhpUndefinedMethodInspection */
			echo esc_attr( $class->get_field_id( 'image_title' ) ); ?>" value="<?php echo esc_attr( $instance['image_title'] ); ?>" class="widefat">
		</p>
	<?php }


	/**
	 * Alter the data right before it is printed
	 *
	 * @param $data
	 *
	 * @return mixed
	 * @since    7.70
	 * @verified 2017.01.11
	 */
	function simple_image_widget_template_data( $data )
	{
		$data['image_atts'] = [];


		if ( isset( $data['image_align'] ) ) {
			$data['link_open']  = '<span class="align' . $data['image_align'] . '">' . $data['link_open'];
			$data['link_close'] .= '</span>';
		}


		if ( $data['image_alt'] ) {
			$data['image_atts']['alt'] = $data['image_alt'];
		}


		if ( $data['image_title'] ) {
			$data['image_atts']['title'] = $data['image_title'];
		}


		return $data;
	}


	/**
	 * Add our template path
	 *
	 * @param $file_paths
	 *
	 * @return mixed
	 * @since    7.70
	 * @verified 2017.01.11
	 */
	function simple_image_widget_template_paths( $file_paths )
	{
		$file_paths[11] = lct_get_path( 'templates/siw/' );


		return $file_paths;
	}


	/**
	 * Unhide some fields
	 * //TODO: cs - Make this an ACF setting option - 01/11/2017 08:50 AM
	 *
	 * @param $hidden_fields
	 *
	 * @return mixed
	 * @since    7.70
	 * @verified 2017.01.11
	 */
	function simple_image_widget_hidden_fields( $hidden_fields )
	{
		if ( ! empty( $hidden_fields ) ) {
			foreach ( $hidden_fields as $key => $hidden_field ) {
				if ( $hidden_field == 'link_classes' ) {
					unset( $hidden_fields[ $key ] );
				}
			}
		}


		return $hidden_fields;
	}
}
