<?php

/**
* Class to change username
*/

if ( ! class_exists( 'WP_Edit_Username' ) )
{
	class WP_Edit_Username
	{
		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;

		public function __construct()
		{
			$this->plugin_name = 'wp-edit-username';

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	    }

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles()
		{
			wp_enqueue_style( $this->plugin_name, WPEU_PLUGIN_URL . 'admin/css/style.css', array(), false );
			
			wp_enqueue_style( $this->plugin_name . "bootstrap_css", WPEU_PLUGIN_URL . 'admin/css/bootstrap.css', array(), '', false );
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts()
		{
			wp_enqueue_script( $this->plugin_name . "bootstrap_popper", WPEU_PLUGIN_URL . 'admin/js/popper.min.js', array( 'jquery' ), '', true );
			
			wp_enqueue_script( $this->plugin_name . "bootstrap_js", WPEU_PLUGIN_URL . 'admin/js/bootstrap.min.js', array( 'jquery', $this->plugin_name . "bootstrap_popper"), '', true );
			
			wp_enqueue_script( $this->plugin_name, WPEU_PLUGIN_URL . 'admin/js/script.js', array( 'jquery', $this->plugin_name . "bootstrap_js" ), '', true );
		}
	}
}
