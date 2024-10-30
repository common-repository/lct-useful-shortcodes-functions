<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


//Just stop the madness if WP is busy installing
if ( ! defined( 'WP_INSTALLING' ) ) {
	add_action( 'lct_mu/load_mu', 'load_lca_mu' );
	/**
	 * Load other mu
	 *
	 * @verified 2017.01.23
	 */
	function load_lca_mu()
	{
		new lca_mu();
	}


	/**
	 * @verified 2017.03.23
	 */
	class lca_mu
	{
		public $lct;


		/**
		 * Setup action and filter hooks
		 *
		 * @verified 2017.01.23
		 */
		function __construct()
		{
			$this->init();
		}


		/**
		 * Get the class running
		 *
		 * @verified 2017.01.30
		 */
		function init()
		{
			global $lct_mu;

			$this->lct = $lct_mu;


			/**
			 * everytime
			 */


			/**
			 * only if we are DOING_AJAX
			 */
			if ( $this->lct->action ) {
				add_action( 'muplugins_loaded', [ $this, 'ajax_checker' ], 7 );
			}
		}


		/**
		 * Let's check the action and get something done
		 *
		 * @verified 2017.01.23
		 */
		function ajax_checker()
		{
			switch ( $this->lct->action ) {
				case (
					strpos( $this->lct->action, 'acf/' ) !== false
					&& strpos( $this->lct->action, 'taxonomy' ) !== false
				):
					$this->lct->theme_swap = false;
					break;


				default:
			}
		}
	}


}
