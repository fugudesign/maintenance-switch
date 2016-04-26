<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.fugu.fr
 * @since      1.0.0
 *
 * @package    Maintenance_Switch
 * @subpackage Maintenance_Switch/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Maintenance_Switch
 * @subpackage Maintenance_Switch/public
 * @author     Fugu <info@fugu.fr>
 */
class Maintenance_Switch_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if ( is_admin_bar_showing() ) :
			wp_enqueue_style( $this->plugin_name . '-button', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/maintenance-switch-button.css', array(), $this->version, 'all' );
		endif;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if ( is_admin_bar_showing() ) :
			wp_enqueue_script( $this->plugin_name . '-button', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/maintenance-switch-button.js', array( 'jquery' ), $this->version, false );
		endif;
	}
	
	/**
	 * Init the ajaxurl javascript var from the frontend if adminbar is active
	 *
	 * @since    1.0.0
	 */
	public function set_ajaxurl() {
		if ( is_admin_bar_showing() ) :
			echo "<script type='text/javascript'>var ajaxurl = '" . admin_url('admin-ajax.php') . "';</script>";
		endif;
	}

}
