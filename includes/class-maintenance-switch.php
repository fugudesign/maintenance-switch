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
	 * @var      string    $plugin_name    The version of the plugin.
	 */
	protected $version;
	
	/**
	 * The current status of the maintenance mode.
	 *
	 * @since    1.1.1
	 * @access   protected
	 * @var      string    $version    The current status of the maintenance mode.
	 */
	protected $status;
	
	/**
	 * The default settings values
	 *
	 * @since    1.3.0
	 * @access   protected
	 * @var      array    $default_settings    The default settings of the plugin.
	 */
	protected $default_settings;
	
	/**
	 * The current theme used in wp
	 *
	 * @since    1.3.0
	 * @access   protected
	 * @var      object    $current_theme    Get the theme object of the current wp theme used.
	 */
	protected $current_theme;
	
	/**
	 * The notices to display in admin panel
	 *
	 * @since    1.3.3
	 * @access   protected
	 * @var      array    $notices    The notice messages to display
	 */
	protected $notices;

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
		$this->version = '1.3.5';
		$this->default_settings = json_decode( MS_DEFAULT_SETTINGS, true );
		$this->current_theme = wp_get_theme();

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
		$plugin_i18n->set_domain( $this->plugin_name );

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

		$plugin_admin = new Maintenance_Switch_Admin( $this );
		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		// Add the options page and menu item.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		
		// Execute actions on settings option updated
		$this->loader->add_action( 'update_option_maintenance_switch_settings', $this, 'admin_action_update' );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );
		
		// Add an action for the switch button
		$this->loader->add_action('admin_bar_menu', $this, 'add_switch_button', 45);
		
		// Add an action to init in admin
		$this->loader->add_action( 'wp_loaded', $this, 'admin_init' );
		
		// Add callback action for ajax request
		$this->loader->add_action( 'wp_ajax_toggle_status', $this, 'toggle_status_callback' );
		
		// Admin notices
		$this->loader->add_action( 'admin_notices', $this, 'display_admin_notices' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Maintenance_Switch_Public( $this->plugin_name, $this->version );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		$this->loader->add_action( 'wp_head', $plugin_public,'set_ajaxurl' );
	}
	
	/**
	 * Actions to execute according to the action field
	 *
	 * @since    1.3.3
	 */
	public function admin_action_update() {
		
		$this->init_files( true ); 
	}
	
	/**
	 * Actions to execute according to the action field
	 *
	 * @since    1.3.3
	 */
	public function admin_action_request() {
		
		if ( !empty( $_REQUEST['action'] ) ) {
			
			switch( $_REQUEST['action'] ) {
				
				case 'restore_settings':
				
					if ( $this->restore_default_settings() )
						$this->notice( 'success', __( 'Default settings successfuly restored.', MS_SLUG ) );
					else
						$this->notice( 'error', __( 'Default settings was not restored.', MS_SLUG ) );
					break;
				
				case 'restore_html':
				
					if ( $this->restore_html_setting() ) {
						$this->notice( 'success', __( 'HTML code successfuly restored.', MS_SLUG ) );
					} else {
						$this->notice( 'error', __( 'HTML code could was not restored.', MS_SLUG ) );
					}
					break;
				
				case 'create_theme_file':
				
					if ( $this->create_theme_file() ) {
						$this->notice( 'success', __( 'The theme file was created successfuly.', MS_SLUG ) );
					} else {
						$this->notice( 'error', __( 'The theme file was not created.', MS_SLUG ) );
					}
					break;
				
				case 'delete_theme_file':
				
					if ( $this->delete_theme_file() ) {
						$this->notice( 'success', __( 'The theme file was deleted successfuly', MS_SLUG ) );
					} else {
						$this->notice( 'error', __( 'The theme file was not deleted.', MS_SLUG ) );
					}
					break;
				
			}
			
		}
	}
	
	/**
	 * Callback after core or plugins install/updates
	 *
	 * @since    1.3.3
	 */
	 public function notice( $type, $notice ) {
		 
		 if ( !empty( $type ) && !empty( $notice ) )
		 	$this->notices[] = sprintf( '<div class="notice notice-%s is-dismissible"><p>%s</p></div>', $type, $notice );
	 }
	 
	 /**
	 * Display admin notices stored in object
	 *
	 * @since    1.3.3
	 */
	public function display_admin_notices() {
		
		if ( !empty( $this->notices ) ) {
			foreach( $this->notices as $key => $notice ) {
				echo $notice;
			}
		}
	}
	
	/**
	 * Initialize the plugin in admin (after wp loaded)
	 *
	 * @since    1.1.1
	 */
	public function admin_init() {
		
		$this->init_settings();
		$this->sync_status();
	}
	
	
	/**
	 * Migrate the plugin settings from older versions
	 *
	 * @since    1.3.1
	 */
	public function init_settings() {
		
		// Define if settings mode needs to be migrated from old to new system
		$migrate = false;

		// Get and delete previous settings values
		if ( $this->version_before( '1.3.3' ) ) {
			
			// Get previous settins in an array
			$previous_version_settings = array(
				'ms_page_html' 		=> get_option( 'ms_page_html' ),
				'ms_switch_roles' 	=> get_option( 'ms_switch_roles' ),
				'ms_allowed_roles' 	=> get_option( 'ms_allowed_roles' ),
				'ms_allowed_ips' 	=> get_option( 'ms_allowed_ips' ),
				'ms_use_theme'		=> get_option( 'ms_use_theme' )
			);
			$ms_status = (int) get_option( 'ms_status' );
			
			// Remove old invalid settings
			delete_option( 'ms_maintenance_page_html' );
			delete_option( 'ms_allowed_ip' );
			
			// Get and remove previous settings version
			if ( $previous_version_settings['ms_page_html'] !== false ) { $migrate = true; delete_option( 'ms_page_html' ); }
			if ( $previous_version_settings['ms_switch_roles'] !== false ) { $migrate = true; delete_option( 'ms_switch_roles' ); }
			if ( $previous_version_settings['ms_allowed_roles'] !== false ) { $migrate = true; delete_option( 'ms_allowed_roles' ); }
			if ( $previous_version_settings['ms_allowed_ips'] !== false ) { $migrate = true; delete_option( 'ms_allowed_ips' ); }
			if ( $previous_version_settings['ms_use_theme'] !== false ) { $migrate = true; delete_option( 'ms_use_theme' ); }
			if ( $ms_status !== false ) { $migrate = true; delete_option( 'ms_status' ); }
			
			if ( !$migrate ) return false;
		}
		
		// Initialize options
		$this->init_options( $migrate ? $previous_version_settings : array(), $migrate ? $ms_status : null );
		
		// Create the plugin core maintenance files
		$this->init_files();
		
		return true;
	}
	
	/**
	 * Initialize the plugin options
	 *
	 * @since    1.1.1
	 * @var 	 array		$options	the options array wanted
	 * @var 	 int		$status		the status wanted
	 */
	public function init_options( $options = array(), $status = null ) {
		
		// Get defaults settings
		$defaults = $this->default_settings;
		
		// Get settings
		$settings = $this->get_the_settings();
		
		// Merging database options with defaults options
		if ( empty($settings) )
			$settings = wp_parse_args( $defaults, $settings );
		
		// Merging options param with defaults options
		if ( !empty( $options ) )
			$settings = wp_parse_args( $options, $settings );
		
		// Save settings
		update_option( 'maintenance_switch_settings', $settings );
		
		// Set the status param
		if ( $status !== null )
			$status = update_option( 'maintenance_switch_status', $status );
		
		// Get the status of maintenance
		$this->status = $this->get_the_status();
		
		// Save the plugin version in the database
		update_option( 'maintenance_switch_version', $this->version );
	}
	
	/**
	 * Synchronize the maintenance status option with the maintenance file.
	 *
	 * @since    1.1.1
	 * @var      integer    $status    	the status to set, or just sync with file if null
	 */
	public function sync_status( $status_wanted=null ) {

		// get the status in the database if no status in param
		if ( $status_wanted === null )
			$status = $this->get_the_status();
		else
			$status = $status_wanted;
		
		// try to create the file according to the status value
		switch ( $status ) {
			
			case 1:
			
				if ( $this->create_dot_file() ) {
					$response = array( 'success' => true );
					// if status called, update in db
					if ( $status_wanted !== null ) $this->set_the_status( $status );
				} else {
					$response = array( 'success' => false );
				}
				
				break;
			
			case 0:
				
				if ( $this->_delete_file( MS_DOT_FILE_ACTIVE, true ) ) {
					$response = array( 'success' => true );
					// if status called, update in db
					if ( $status_wanted !== null ) $this->set_the_status( $status );
				} else {
					$response = array( 'success' => false );
				}
				
				break;
			
		}

		$response['status'] = $status;
		
		return !empty($response) ? $response : false;
	}
	
	/**
	 * Initialize the plugin files
	 *
	 * @since    1.1.1
	 * @return   boolean    true
	 */
	public function init_files( $override=false ) {
		
		// create the php file from template
		if ( $override || ! file_exists( MS_PHP_FILE_ACTIVE ) ) {
			$this->create_php_file();
		}
		
		if ( $this->get_the_status() == 1 )
			$this->create_dot_file();
				
		return true;
	}

	/**
	 * Check the version of the previously installed plugin
	 *
	 * @since    1.3.1
	 */
	public function version_before( $version ) {
		// get the version in db
		$previous_version = $this->get_the_version();
		
		// test if the db version is anterior to called version
		if ( $this->numeric_version( $previous_version ) < $this->numeric_version( $version ) ) 
			return true;
		
		return false;
	}
	
	/**
	 * Get Integer version
	 *
	 * @since    1.3.1
	 */
	public function numeric_version( $version ) {
		
		$version = str_replace( '.', '', $version );
		return (int) $version;
	}
	
	/**
	 * Get the current wp theme used
	 *
	 * @since    1.3.0
	 */
	public function get_current_theme() {
		
		return $this->current_theme;
	}
	
	/**
	 * Get the maintenance status
	 *
	 * @since    1.3.0
	 */
	public function get_the_status() {
		
		$status = get_option( 'maintenance_switch_status' );
		if ( !$status ) {
			$status = update_option( 'maintenance_switch_status', MS_DEFAULT_STATUS );
			return MS_DEFAULT_STATUS;
		}
		return $status;
	}
	
	/**
	 * Set the maintenance status
	 *
	 * @since    1.3.0
	 * @var		 $status		the maintenance status wanted
	 * @return	 boolean		true if the status was changed, false if not
	 */
	public function set_the_status( $status ) {
		
		if ( isset( $status ) ) {
			return update_option( 'maintenance_switch_status', $status );
		}
		return false;
	}
	
	/**
	 * Get the version saved
	 *
	 * @since    1.3.1
	 * @return 	 string 		the version of the plugin saved in db 
	 */
	public function get_the_version() {
		
		return get_option( 'maintenance_switch_version', '1.0.0' );
	}
	
	/**
	 * Get all the settings
	 *
	 * @since    1.3.0
	 * @return 	 misc 		the option value or false if option not exists
	 */
	public function get_the_settings() {
		
		return get_option( 'maintenance_switch_settings' );
	}
	
	/**
	 * Restore all default settings
	 *
	 * @since    1.3.0
	 */
	public function restore_default_settings() {
		
		$settings = $this->default_settings;
		return update_option( 'maintenance_switch_settings', $settings );
	}
	
	/**
	 * Restore the default html code setting
	 *
	 * @since    1.3.0
	 */
	public function restore_html_setting() {
		
		$settings = $this->get_the_settings();
		$settings['ms_page_html'] = $this->default_settings['ms_page_html'];
		return update_option( 'maintenance_switch_settings', $settings );
	}
	
	/**
	 * Get the maintenance.php theme file url
	 *
	 * @since    1.3.0
	 * @return   string    The theme file with absolute url
	 */
	public function get_theme_file_url() {
		
		return $this->current_theme->get_stylesheet_directory_uri() . '/' . MS_THEME_FILENAME;
	}
	
	/**
	 * Get the maintenance.php theme file path
	 *
	 * @since    1.3.0
	 * @return   string    The theme file with absolute path
	 */
	public function get_theme_file_path() {
		
		return $this->current_theme->get_stylesheet_directory() . '/' . MS_THEME_FILENAME;
	}
	
	/**
	 * Check if the maintenance.php fil is present in theme directory
	 *
	 * @since    1.3.0
	 * @return   boolean    true if the file exists in theme, false if not
	 */
	public function theme_file_exists() {
		
		$theme_file = $this->get_theme_file_path();
		return file_exists( $theme_file );
	}
	
	/**
	 * Create the maintenance.php file in theme with default html code
	 *
	 * @since    1.3.0
	 * @return   boolean    True if the file was created in theme, false if not of if it already exists
	 */
	public function create_theme_file() {
		
		$theme_file = $this->get_theme_file_path();
		if ( ! $this->theme_file_exists() ) {
			return $this->_create_file( $theme_file, $this->default_settings['ms_page_html'] );
		}
		return false;
	}
	
	/**
	 * Delete the maintenance.php file in theme
	 *
	 * @since    1.3.0
	 * @return   boolean    True if the file was deleted in theme, false if not of if not exists
	 */
	public function delete_theme_file() {
		
		$theme_file = $this->get_theme_file_path();
		if ( $this->theme_file_exists() ) {
			return $this->_delete_file( $theme_file );
		}
		return false;
	}
	
	/**
	 * Get a setting
	 *
	 * @since    1.3.0
	 * @var      string    $setting_name    	the key name of the setting
	 * @var      string    $default_value    	the default value to return if no setting value
	 * @return   misc    The setting value
	 */
	public function get_setting( $setting_name, $default_value = false ) {
		
		$settings = $this->get_the_settings();
		
		if ( isset( $settings[ $setting_name ] ) ) {
			return $settings[ $setting_name ];
		}
		
		return $default_value;
	}
	
	/**
	 * Update a setting
	 *
	 * @since    1.3.0
	 * @var      string    	$setting_name    	the key name of the setting
	 * @var      misc    	$setting_value    	the value to save for the setting
	 * @return   boolean    True if the setting was updated, false if not of if is not set
	 */
	public function update_setting( $setting_name, $setting_value ) {
		
		$settings = $this->get_the_settings();
		
		if ( isset( $settings[$setting_name] ) )
			$settings[$setting_name] = $setting_value;
		else
			return false;
		
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
	 * Retrieve the default settings of the plugin.
	 *
	 * @since     1.3.0
	 * @return    string    The default settings.
	 */
	public function get_default_settings() {
		
		return $this->default_settings;
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
		
		$switch_roles = (array) $this->get_setting( 'ms_switch_roles' );
		
		foreach( $current_user->roles as $role ) {
			if ( in_array( $role, $switch_roles ) )
				$user_can = true;
		}
		return $user_can;
	}
	
	/**
	 * Get all users that match with the ms_allowed_roles setting 
	 *
	 * @since    1.0.0
	 * @return   array    List of all users logins
	 */
	public function get_allowed_users() {
		
		$allowed_roles = (array) $this->get_setting( 'ms_allowed_roles' );
		$users = $this->get_users_by_role( $allowed_roles );
		$allowed_users = array();
		foreach ( $users as $user ) {
			$allowed_users[] = $user->user_login;
		}
		return $allowed_users;
	}
	
	/**
	 * Get the ips from ms_allowed_ips setting 
	 *
	 * @since    1.0.4
	 * @return   string    List of all ips comma separated
	 */
	public function get_allowed_ips() {
		
		$allowed_ips = $this->get_setting( 'ms_allowed_ips' );
		$allowed_ips = explode( ',', $allowed_ips );
		return $allowed_ips;
	}
	
	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 * @var      array    $roles    the roles for users query
	 * @return	 array	  the user list
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
	 * @return   string    The current ip of the user
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
	 * @return   boolean    True if the .maintenance file is core, false if was created by the plugin
	 */
	public function _check_core_file( $file ) {
		
		if ( file_exists( $file ) ) {
			$content = file_get_contents( $file );
			if ( preg_match( '/'.$this->plugin_name.'/i', $content) )
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
	 * @return   boolean    True if the file was deleted, false if not
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
		$page_html = wp_specialchars_decode( $this->get_setting( 'ms_page_html' ), ENT_QUOTES );
		$use_theme_file = $this->get_setting( 'ms_use_theme' );
		$theme = wp_get_theme();
		$theme_file = $theme->get_stylesheet_directory() . '/' . MS_THEME_FILENAME;
		
		// apply flags replacements
		$content = str_replace( '{{MS_PLUGIN_SLUG}}' , $this->plugin_name, $content );
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
		$allowed_ips = "'" . implode( "','", $this->get_allowed_ips() ) . "'";
		$login_url = str_replace( get_site_url(), '', wp_login_url() );
		
		// apply flags replacements
		$content = str_replace( '{{MS_ALLOWED_USERS}}' , $allowed_users, $content );
		$content = str_replace( '{{MS_ALLOWED_IPS}}' , $allowed_ips, $content );
		$content = str_replace( '{{MS_PLUGIN_SLUG}}' , $this->plugin_name, $content );
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
	 * Callback action for changing status ajax request
	 *
	 * @since    1.0.0
	 */
	public function toggle_status_callback() {
		
		// get status in db
		$status = $this->get_the_status();
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
				'title' => '<span class="ab-icon dashicons-admin-tools"></span><span class="ab-label">' . __( 'Maintenance', $this->plugin_name ) . '</span>',
				'href' => '#',
				'meta' => array(
					'class' => 'toggle-button ' . ( $this->status ? 'active' : '' ),
				)
			);
			
			$wp_admin_bar->add_node( $args );
		}
	}

}
