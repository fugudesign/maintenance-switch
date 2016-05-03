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
	 * The Instance of the main plugin class.
	 *
	 * @since    1.3.3
	 * @access   protected
	 * @var      object    $plugin    The instance of the main class
	 */
	protected $plugin;
	
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of this plugin.
	 */
	protected $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( &$plugin ) {
		$this->plugin = $plugin;
		$this->plugin_name = $plugin->get_plugin_name();
		$this->version = $plugin->get_version();
	}
	
	/**
	 * Get the protected property plugin_name
	 *
	 * @since    1.3.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}
	
	/**
	 * Get the protected property version
	 *
	 * @since    1.3.0
	 */
	public function get_version() {
		return $this->version;
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
		
		// Get the view
		include_once( 'views/maintenance-switch-admin-display.php' );
		$view = new Maintenance_Switch_Admin_Display( $this->plugin );
		
		// Adds option page in admin settings
		add_options_page(
			__( 'Maintenance Switch', $this->plugin_name ),
			__( 'Maintenance Switch', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $view, 'maintenance_switch_create_admin_page' )
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

}
