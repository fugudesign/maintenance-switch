<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.fugu.fr
 * @since      1.0.0
 *
 * @package    Maintenance_Switch
 * @subpackage Maintenance_Switch/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Maintenance_Switch
 * @subpackage Maintenance_Switch/admin
 * @author     Fugu <info@fugu.fr>
 */
class Maintenance_Switch_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/maintenance-switch-admin.css', array(), $this->version, 'all' );
		
		wp_enqueue_style( $this->plugin_name . '-button', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/maintenance-switch-button.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/maintenance-switch-admin.js', array( 'jquery' ), $this->version, false );
		
		wp_enqueue_script( $this->plugin_name . '-button', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/maintenance-switch-button.js', array( 'jquery' ), $this->version, false );
	}
	
	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		add_options_page(
			__( 'Maintenance Switch', $this->plugin_name ),
			__( 'Maintenance Switch', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_plugin_admin_page' )
		);

	}
	
	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>'
			),
			$links
		);
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		
		$plugin = new Maintenance_Switch();
		
		if ( !empty( $_REQUEST[ 'settings-updated' ] ) ) {
			$plugin->create_php_file();
			$plugin->recreate_dot_file();
		}
		
		include_once( 'partials/maintenance-switch-admin-display.php' );
	}

}
