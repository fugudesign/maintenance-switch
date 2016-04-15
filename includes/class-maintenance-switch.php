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
	 * The current status of the maintenance mode.
	 *
	 * @since    1.1.1
	 * @access   protected
	 * @var      string    $version    current status of the maintenance mode.
	 */
	protected $status;

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
		$this->version = '1.2.1';

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
		
		// Add an action to init in admin
		$this->loader->add_action( 'wp_loaded', $this, 'admin_init' );
		
		// Add an action to reactivate maintenance after updates
		$this->loader->add_filter( 'upgrader_post_install', $this, 'after_upgrades', 10, 3 );
		
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
	 * Callback after core or plugins install/updates
	 *
	 * @since    1.1.1
	 */
	public function after_upgrades( $bool, $args_hook_extra, $result ) {
		
		$this->sync_status();
		
		return $bool; 
	}
	
	/**
	 * Initialize the plugin in admin (after wp loaded)
	 *
	 * @since    1.1.1
	 */
	public function admin_init() {
		
		$this->init_options();
		$this->init_files();
		
		$this->sync_status();
	}
	
	/**
	 * Initialize the plugin options
	 *
	 * @since    1.1.1
	 */
	public function init_options() {
		
		if ( ! get_option( 'ms_page_html' ) )
			add_option( 'ms_page_html', MS_DEFAULT_PAGE_HTML );
		
		if ( ! get_option( 'ms_switch_roles' ) )	
			add_option( 'ms_switch_roles', explode( ',', MS_DEFAULT_SWITCH_ROLES ) );
		
		if ( ! get_option( 'ms_allowed_roles' ) )	
			add_option( 'ms_allowed_roles', explode( ',', MS_DEFAULT_ALLOWED_ROLES ) );
		
		if ( ! get_option( 'ms_allowed_ips' ) )	
			add_option( 'ms_allowed_ips', '' );
		
		if ( ! get_option( 'ms_status' ) )	
			add_option( 'ms_status', MS_DEFAULT_STATUS );
		
		$this->status = get_option( 'ms_status' );
	}
	
	/**
	 * Initialize the plugin files
	 *
	 * @since    1.1.1
	 */
	public function init_files() {
		
		// create the php file from template
		if ( ! file_exists( MS_PHP_FILE_ACTIVE ) ) {
			$this->create_php_file();
		}
		
		if ( $this->status == 1 )
			$this->create_dot_file();
				
		return true;
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
	 * Retrieve the status of the maintenance mode.
	 *
	 * @since     1.1.1
	 * @return    string    The status of the maintenance mode.
	 */
	public function get_status() {
		return $this->status;
	}
	
	/**
	 * Check if the current user has a role that matches with ms_switch_roles
	 *
	 * @since     1.1.7
	 * @return    boolean    True if the user can switch, false if not
	 */
	public function current_user_can_switch() {
		
		global $current_user;
		$user_can = false;
		
		$switch_roles = (array) get_option( 'ms_switch_roles' );
		
		foreach( $current_user->roles as $role ) {
			if ( in_array( $role, $switch_roles ) )
				$user_can = true;
		}
		return $user_can;
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
		$allowed_ips = explode( ',', str_replace( ' ', '', $allowed_ips ) );
		return $allowed_ips;
	}
	
	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 * @var      array    $roles    the roles for users query
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

		//Just get the headers if we can or else use the SERVER global
		if ( function_exists( 'apache_request_headers' ) ) {
			$headers = apache_request_headers();
		} else {
			$headers = $_SERVER;
		}
		// Get the forwarded IP if it exists
		if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
			$the_ip = $headers['X-Forwarded-For'];
		} elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
			$the_ip = $headers['HTTP_X_FORWARDED_FOR'];
		} else {
			$the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
		}
		return $the_ip;
	}
	
	/**
	 * Check if the file is core file
	 *
	 * @since    1.1.1
	 * @var      string    $file    the filename of the file to check
	 */
	public function _check_core_file( $file ) {
		
		if ( file_exists( $file ) ) {
			$content = file_get_contents( $file );
			if ( preg_match( '/'.$this->get_plugin_name().'/i', $content) )
				return false;
			else
				return true;
		}
		return false;
	}
	
	/**
	 * Delete the dot file
	 *
	 * @since    1.1.1
	 * @var      string    $file    	the filename of the file to check
	 * @var      boolean   $check_core  check or not if the core version of the file is present
	 */
	public function _delete_file( $file, $check_core=false ) {
		
		if ( file_exists( $file ) ) {
			
			if ( $check_core && $this->_check_core_file( $file ) )
				return false;
				
			if ( unlink( $file ) )
				return true;
		}
		return false;
	}
	
	/**
	 * Create the file
	 *
	 * @since    1.1.1
	 * @var      string    $file    	the filename of the file to check
	 * @var      string    $content  	the content to put in the file
	 */
	public function _create_file( $file, $content ) {
		
		if ( file_exists( $file ) )
			return false;
			
		if ( ! file_put_contents( $file, $content ) )
			return false;
			
		return true;
	}
	
	/**
	 * Generate the maintenance.php file and copy it to the wp-content dir
	 *
	 * @since    1.0.0
	 */
	public function create_php_file() {
		
		// get the template file content
		$content = file_get_contents( MS_PHP_FILE_TEMPLATE );
		
		// get flags values
		$page_html = get_option( 'ms_page_html' );
		$use_theme_file = get_option( 'ms_use_theme' ) ? 'true' : 'false';
		$theme = wp_get_theme();
		$theme_file = $theme->get_stylesheet_directory() . '/' . MS_THEME_FILENAME;
		
		// apply flags replacements
		$content = str_replace( '{{MS_PLUGIN_SLUG}}' , $this->get_plugin_name(), $content );
		$content = str_replace( '{{MS_USE_THEME_FILE}}' , $use_theme_file, $content );
		$content = str_replace( '{{MS_THEME_FILE}}' , $theme_file, $content );
		$content = str_replace( '{{MS_PAGE_HTML}}' , $page_html, $content );
		
		// delete the current file
		$this->_delete_file( MS_PHP_FILE_ACTIVE );
		
		// try to create the file
		if ( ! $this->_create_file( MS_PHP_FILE_ACTIVE, $content ) ) {
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
		
		// get the template file content
		$content = file_get_contents( MS_DOT_FILE_TEMPLATE );
		
		// get flags values
		$allowed_users = "'" . implode( "', '", $this->get_allowed_users() ) . "'";
		$allowed_ips = "'" . implode( "', '", $this->get_allowed_ips() ) . "'";
		$login_url = str_replace( get_site_url(), '', wp_login_url() );
		
		// apply flags replacements
		$content = str_replace( '{{MS_ALLOWED_USERS}}' , $allowed_users, $content );
		$content = str_replace( '{{MS_ALLOWED_IPS}}' , $allowed_ips, $content );
		$content = str_replace( '{{MS_PLUGIN_SLUG}}' , $this->get_plugin_name(), $content );
		$content = str_replace( '{{MS_LOGIN_URL}}' , $login_url, $content );

		// check if the core dot file exists or delete current file
		if ( $this->_check_core_file( MS_DOT_FILE_ACTIVE ) ) {
			return false;
		} else {
			$this->_delete_file( MS_DOT_FILE_ACTIVE, true );
		}
		
		// try to create the file
		if ( ! $this->_create_file( MS_DOT_FILE_ACTIVE, $content ) ) {
			return false;
		}
		return true;
	}
	
	/**
	 * Synchronize the maintenance status option with the maintenance file.
	 *
	 * @since    1.1.1
	 * @var      integer    $status    	the status to set, or just sync with file if null
	 */
	public function sync_status( $status=null ) {

		$sync = false;
		// get the status in the database if no status in param
		if ( $status === null ) {
			$status = get_option( 'ms_status', MS_DEFAULT_STATUS );
			$sync = true;
		}
		
		// try to create the file according to the status value
		switch ( $status ) {
			
			case 1:
			
				if ( $this->create_dot_file() ) {
					$msg = array( 'success' => true, 'data' => __( 'Maintenance turned on.', $this->get_plugin_name() ) );
					// if status called, update in db
					if ( ! $sync ) update_option( 'ms_status', $status );
				} else {
					$msg = array( 'success' => false, 'data' => __( 'Maintenance could not be turned on.', $this->get_plugin_name() ) );
				}
				
				break;
			
			case 0:
				
				if ( $this->_delete_file( MS_DOT_FILE_ACTIVE, true ) ) {
					$msg = array( 'success' => true, 'data' => __( 'Maintenance turned off.', $this->get_plugin_name() ) );
					// if status called, update in db
					if ( ! $sync ) update_option( 'ms_status', $status );
				} else {
					$msg = array( 'success' => false, 'data' => __( 'Maintenance could not be turned off.', $this->get_plugin_name() ) );
				}
				
				break;
			
		}

		$msg['status'] = $status;
		
		return !empty($msg) ? $msg : false;
	}
	
	/**
	 * Callback action for changing status ajax request
	 *
	 * @since    1.0.0
	 */
	public function toggle_status_callback() {
		
		// get status in db
		$status = get_option( 'ms_status' );
		// toggle status
		$new_status = (bool) $status == 1 ? 0 : 1;
		// sync status
		$response = $this->sync_status( $new_status );
		// return json response
		wp_send_json( $response );
		// this is required to terminate immediately and return a proper response
		wp_die(); 
		
	}
		
	/**
	 * Add button to the admin bar for toggling the maintenance mode
	 *
	 * @since    1.0.0
	 */
	public function add_switch_button( $wp_admin_bar ){
		
		if ( $this->current_user_can_switch() ) {
		
			$args = array(
				'id' => 'ms-switch-button',
				'title' => '<span class="ab-icon dashicons-admin-tools"></span><span class="ab-label">' . __( 'Maintenance', $this->get_plugin_name() ) . '</span>',
				'href' => '#',
				'meta' => array(
					'class' => 'toggle-button ' . ( $this->status ? 'active' : '' ),
				)
			);
			
			$wp_admin_bar->add_node( $args );
		}
	}

}
