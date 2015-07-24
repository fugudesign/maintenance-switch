<?php
/**
 * Maintenance Switch.
 *
 * @package   Maintenance_Switch_Admin
 * @author    Fugu <info@fugu.fr>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2015 Fugu
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-maintenance-switch.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package Maintenance_Switch_Admin
 * @author  Fugu <info@fugu.fr>
 */
class Maintenance_Switch_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */
		
		/*
		 * Call $plugin_slug from public plugin class.
		 *
		 * @TODO:
		 *
		 * - Rename "Maintenance_Switch" to the name of your initial plugin class
		 *
		 */
		$plugin = Maintenance_Switch::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();


		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
		
		// Add an action for the switch button
		add_action('admin_bar_menu', array( $this, 'add_switch_button' ), 45);
		
		// Add callback action for ajax request
		add_action( 'wp_ajax_toggle_status', array( $this, 'toggle_status_callback' ) );


	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}	

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @TODO:
	 *
	 * - Rename "Maintenance_Switch" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), Maintenance_Switch::VERSION );
		}
		
		wp_enqueue_style( $this->plugin_slug .'-switch-styles', plugins_url( 'assets/css/switch.css', __FILE__ ), array(), Maintenance_Switch::VERSION );

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @TODO:
	 *
	 * - Rename "Maintenance_Switch" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), Maintenance_Switch::VERSION );
		}
		
		wp_enqueue_script( $this->plugin_slug . '-admin-ajax-script', plugins_url( 'assets/js/ajax.js', __FILE__ ), array( 'jquery' ), Maintenance_Switch::VERSION );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 * @TODO:
		 *
		 * - Change 'Page Title' to the title of your plugin admin page
		 * - Change 'Menu Text' to the text for menu item for the plugin settings page
		 * - Change 'manage_options' to the capability you see fit
		 *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
		 */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Maintenance Switch', $this->plugin_slug ),
			__( 'Maintenance Switch', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
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
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
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
		
		if ( !empty( $_REQUEST[ 'settings-updated' ] ) ) {
			$this->create_php_file();
			$this->recreate_dot_file();
		}
		
		include_once( 'views/admin.php' );
	}
	
	/**
	 * Generate the maintenance.php file and copy it to the wp-content dir
	 *
	 * @since    1.0.0
	 */
	public function create_php_file() {
		
		$content = file_get_contents( MS_PHP_FILE_TEMPLATE );
		$page_html = get_option( 'ms_maintenance_page_html' );
		$content = str_replace( '{{MS_MAINTENANCE_PAGE_HTML}}' , $page_html, $content );
		
		if ( file_exists( MS_PHP_FILE_USED ) ) {
			unlink( MS_PHP_FILE_USED );
		}
		
		if ( !file_put_contents( MS_PHP_FILE_USED, $content ) ) {
			return false;
		}
		return true;
	}
	
	/**
	 * Generate the .maintenance file and copy it to the wp-content dir
	 *
	 * @since    1.0.0
	 */
	public function create_dot_file() {
		
		$content = file_get_contents( MS_DOT_FILE_TEMPLATE );
		$allowed_users = "'" . implode( "', '", $this->get_allowed_users() ) . "'";
		$content = str_replace( '{{MS_ALLOWED_USERS}}' , $allowed_users, $content );
		$content = str_replace( '{{MS_PLUGIN_SLUG}}' , $this->plugin_slug, $content );
		
		if ( file_exists( MS_DOT_FILE_USED ) ) {
			wp_send_json_error( array( 'error' => __( 'Wordpress is under core maintenance.', 'maintenance-switch' ) ) );
			return false;
		}
		
		if ( !file_put_contents( MS_DOT_FILE_USED, $content ) ) {
			wp_send_json_error( array( 'error' => __( '.maintenance file was not created.', 'maintenance-switch' ) ) );
			return false;
		}
		return true;
	}
	
	/**
	 * Re-generate the .maintenance file and copy it to the wp-content dir
	 *
	 * @since    1.0.0
	 */
	public function recreate_dot_file() {
		
		if ( $this->dot_file_is_plugin() ) {
			unlink( MS_DOT_FILE_USED );
			$this->create_dot_file();
		}
	}
	
	/**
	 * Re-generate the .maintenance file and copy it to the wp-content dir
	 *
	 * @since    1.0.0
	 */
	public function dot_file_is_plugin() {
		
		if ( file_exists( MS_DOT_FILE_USED ) ) {
			$content = file_get_contents( MS_DOT_FILE_USED );
			if ( preg_match( '/'.$this->plugin_slug.'/i', $content) )
				return true;
		}
		return false;
	}
	
	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function get_allowed_users() {
		
		$allowed_roles = (array) get_option( 'ms_allowed_roles' );
		$users = $this->get_users_by_role( $allowed_roles );
		$allowed_users = array();
		foreach ( $users as $user ) {
			$allowed_users[] = $user->user_login;
		}
		return $allowed_users;
	}
	
	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */	
	public function get_users_by_role( $roles = array() ) { 
	
	    $users = array();
	    foreach ($roles as $role) {
	    	if ( !empty( $role ) ) {
		        $users_query = new WP_User_Query( array( 
		            'fields' => 'all_with_meta', 
		            'role' => $role, 
		            'orderby' => 'display_name'
		            ) );
		        $results = $users_query->get_results();
		        if ($results) $users = array_merge($users, $results);
	        }
	    }
	    return $users;
	}
	
	/**
	 * Check if the maintenance mode is active.
	 *
	 * @since    1.0.0
	 */
	public function is_maintenance_active() {
		
		if ( file_exists( MS_DOT_FILE_USED ) ) {
			return true;
		} 
		
		return false;
	}
	
	/**
	 * Add button to the admin bar for toggling the maintenance mode
	 *
	 * @since    1.0.0
	 */
	public function add_switch_button( $wp_admin_bar ){
		
		$args = array(
			'id' => 'ms-switch-button',
			'title' => '<span class="ab-icon dashicons-admin-tools"></span><span class="ab-label">' . __( 'Maintenance', 'maintenance-switch' ) . '</span>',
			'href' => '#',
			'meta' => array(
				'class' => 'toggle-button ' . ( $this->is_maintenance_active() ? 'active' : '' ),
				'onclick' => 'return MaintenanceSwitchToggleStatus();'
			)
		);
		
		$wp_admin_bar->add_node( $args );
	}
	
	/**
	 * Callback action for changing status ajax request
	 *
	 * @since    1.0.0
	 */
	public function toggle_status_callback() {
		
		global $wpdb; // this is how you get access to the databas
		$status = $_REQUEST['status'];
		$response = array();
		switch ($status) {
			
			case 'on':
				if ( file_exists( MS_DOT_FILE_USED ) ) {
					wp_send_json_error( array( 'error' => __( 'Wordpress is under core maintenance.', 'maintenance-switch' ) ) );
				}
				if ( !$this->create_dot_file() ) {
					wp_send_json_error( array( 'error' => __( 'Maintenance could not be turned on.', 'maintenance-switch' ) ) );
				} else {
					wp_send_json_success( __( 'Maintenance turned on.', 'maintenance-switch' ) );
				}
				break;
				
			case 'off':
				if ( !unlink( MS_DOT_FILE_USED ) ) {
					wp_send_json_error( array( 'error' => __( 'Maintenance could not be turned off.', 'maintenance-switch' ) ) );
				} else {
					wp_send_json_success( __( 'Maintenance turned off.', 'maintenance-switch' ) );
				}
				break;
		}
		
		wp_die(); // this is required to terminate immediately and return a proper response
		
	}
	
}
