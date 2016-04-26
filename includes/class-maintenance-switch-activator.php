<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.fugu.fr
 * @since      1.0.0
 *
 * @package    Maintenance_Switch
 * @subpackage Maintenance_Switch/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Maintenance_Switch
 * @subpackage Maintenance_Switch/includes
 * @author     Fugu <info@fugu.fr>
 */
class Maintenance_Switch_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		
		// Get the main controller
		$plugin = new Maintenance_Switch();
		
		// Get settings option
		$settings = $plugin->get_the_settings();
		
		// --- Migrate settings before version 1.3.0 ---
		
		// Define if settings mode needs to be migrated from old to new system
		$migrate = false;

		// Get and delete previous settings values
		if ( $settings === false ) {
			
			// Get previous settins in an array
			$previous_version_settings = array(
				'ms_page_html' 		=> get_option( 'ms_page_html' ),
				'ms_switch_roles' 	=> get_option( 'ms_switch_roles' ),
				'ms_allowed_roles' 	=> get_option( 'ms_allowed_roles' ),
				'ms_allowed_ips' 	=> get_option( 'ms_allowed_ips' ),
				'ms_use_theme'		=> get_option( 'ms_use_theme' )
			);
			$ms_status = get_option( 'ms_status' );
			
			// Remove previous settings
			if ( $previous_version_settings['ms_page_html'] !== false ) { $migrate = true; delete_option( 'ms_page_html' ); }
			if ( $previous_version_settings['ms_switch_roles'] !== false ) { $migrate = true; delete_option( 'ms_switch_roles' ); }
			if ( $previous_version_settings['ms_allowed_roles'] !== false ) { $migrate = true; delete_option( 'ms_allowed_roles' ); }
			if ( $previous_version_settings['ms_allowed_ips'] !== false ) { $migrate = true; delete_option( 'ms_allowed_ips' ); }
			if ( $previous_version_settings['ms_use_theme'] !== false ) { $migrate = true; delete_option( 'ms_use_theme' ); }
			if ( $ms_status !== false ) { $migrate = true; delete_option( 'ms_status' ); }
			
		}
		
		// --- End migrate before 1.3.0 ---
		
		// Initialize options
		$plugin->init_options( $migrate ? $previous_version_settings : array(), $migrate ? $ms_status : null );
		
		// Create the php file
		$plugin->create_php_file();
		
	}

}
