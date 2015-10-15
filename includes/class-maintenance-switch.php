<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.fugu.fr
 * @since      1.0.0
 *
 * @package    Maintenance_Switch
 * @subpackage Maintenance_Switch/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Maintenance_Switch
 * @subpackage Maintenance_Switch/includes
 * @author     Fugu <info@fugu.fr>
 */
class Maintenance_Switch {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Maintenance_Switch_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = MS_SLUG;
		$this->version = '1.0.5';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Maintenance_Switch_Loader. Orchestrates the hooks of the plugin.
	 * - Maintenance_Switch_i18n. Defines internationalization functionality.
	 * - Maintenance_Switch_Admin. Defines all hooks for the admin area.
	 * - Maintenance_Switch_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-maintenance-switch-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-maintenance-switch-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-maintenance-switch-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-maintenance-switch-public.php';

		$this->loader = new Maintenance_Switch_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Maintenance_Switch_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Maintenance_Switch_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Maintenance_Switch_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		// Add the options page and menu item.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->get_plugin_name() . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );
		
		// Add an action for the switch button
		$this->loader->add_action('admin_bar_menu', $this, 'add_switch_button', 45);
		
		// Add callback action for ajax request
		$this->loader->add_action( 'wp_ajax_toggle_status', $this, 'toggle_status_callback' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Maintenance_Switch_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		$this->loader->add_action( 'wp_head', $plugin_public,'set_ajaxurl' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Maintenance_Switch_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
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
	 * Check if the dot file comes from the plugin
	 *
	 * @since    1.0.0
	 */
	public function dot_file_is_plugin() {
		
		if ( file_exists( MS_DOT_FILE_USED ) ) {
			$content = file_get_contents( MS_DOT_FILE_USED );
			if ( preg_match( '/'.$this->plugin_name.'/i', $content) )
				return true;
		}
		return false;
	}
	
	/**
	 * Check if the php file comes from the plugin
	 *
	 * @since    1.0.0
	 */
	public function php_file_is_plugin() {
		
		if ( file_exists( MS_PHP_FILE_USED ) ) {
			$content = file_get_contents( MS_PHP_FILE_USED );
			if ( preg_match( '/'.$this->plugin_name.'/i', $content) )
				return true;
		}
		return false;
	}	
	
	/**
	 * Delete the dot file
	 *
	 * @since    1.0.0
	 */
	public function delete_dot_file() {
		
		if ( $this->dot_file_is_plugin() ) {
			if ( unlink( MS_DOT_FILE_USED ) ) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Delete the php file
	 *
	 * @since    1.0.0
	 */
	public function delete_php_file() {
		
		if ( $this->php_file_is_plugin() ) {
			if ( unlink( MS_PHP_FILE_USED ) ) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Generate the maintenance.php file and copy it to the wp-content dir
	 *
	 * @since    1.0.0
	 */
	public function create_php_file() {
		
		$content = file_get_contents( MS_PHP_FILE_TEMPLATE );
		$page_html = get_option( 'ms_page_html' );
		$content = str_replace( '{{ms_page_html}}' , $page_html, $content );
		$content = str_replace( '{{MS_PLUGIN_SLUG}}' , $this->plugin_name, $content );
		
		if ( file_exists( MS_PHP_FILE_USED ) ) {
			$this->delete_php_file();
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
		$allowed_ips = "'" . implode( "', '", $this->get_allowed_ips() ) . "'";
		$login_url = str_replace( get_site_url(), '', wp_login_url() );
		
		$content = str_replace( '{{MS_ALLOWED_USERS}}' , $allowed_users, $content );
		$content = str_replace( '{{MS_ALLOWED_IPS}}' , $allowed_ips, $content );
		$content = str_replace( '{{MS_PLUGIN_SLUG}}' , $this->plugin_name, $content );
		$content = str_replace( '{{MS_LOGIN_URL}}' , $login_url, $content );
		
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
			$this->delete_dot_file();
			$this->create_dot_file();
		}
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
	 * @since    1.0.4
	 */
	public function get_allowed_ips() {
		
		$allowed_ips = get_option( 'ms_allowed_ips' );
		$allowed_ips = explode( ',', trim( $allowed_ips ) );
		return $allowed_ips;
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
	 * Get current user IP
	 *
	 * @since    1.0.4
	 */
	public function get_user_ip() {

		$client  = @$_SERVER[ 'HTTP_CLIENT_IP' ];
	    $forward = @$_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
	    $remote  = $_SERVER[ 'REMOTE_ADDR' ];
	
	    if( filter_var( $client, FILTER_VALIDATE_IP ) )
	    {
	        $ip = $client;
	    }
	    elseif( filter_var( $forward, FILTER_VALIDATE_IP ) )
	    {
	        $ip = $forward;
	    }
	    else
	    {
	        $ip = $remote;
	    }
	
	    return $ip;
	}
	
	/**
	 * Add button to the admin bar for toggling the maintenance mode
	 *
	 * @since    1.0.0
	 */
	public function add_switch_button( $wp_admin_bar ){
		
		$args = array(
			'id' => 'ms-switch-button',
			'title' => '<span class="ab-icon dashicons-admin-tools"></span><span class="ab-label">' . __( 'Maintenance', $this->plugin_name ) . '</span>',
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
				
		switch ($status) {
			
			case 'on':
				if ( file_exists( MS_DOT_FILE_USED ) ) {
					wp_send_json_error( array( 'error' => __( 'Wordpress is under core maintenance.', $this->plugin_name ) ) );
				}
				if ( ! $this->create_dot_file() ) {
					wp_send_json_error( array( 'error' => __( 'Maintenance could not be turned on.', $this->plugin_name ) ) );
				} else {
					wp_send_json_success( __( 'Maintenance turned on.', 'maintenance-switch' ) );
				}
				break;
				
			case 'off':
				if ( ! $this->delete_dot_file() ) {
					wp_send_json_error( array( 'error' => __( 'Maintenance could not be turned off.', $this->plugin_name ) ) );
				} else {
					wp_send_json_success( __( 'Maintenance turned off.', $this->plugin_name ) );
				}
				break;
		}
		
		wp_die(); // this is required to terminate immediately and return a proper response
		
	}

}
