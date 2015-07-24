<?php
/**
 * Maintenance Switch.
 *
 * @package   Maintenance_Switch
 * @author    Fugu <info@fugu.fr>
 * @license   GPL-2.0+
 * @link      http://www.fugu.fr
 * @copyright 2015 Fugu
 *
 * @wordpress-plugin
 * Plugin Name:       Maintenance Switch
 * Description:       Adds a button to toggle the native maintenance mode 
 * Version:           1.0.0
 * Author:            Fugu
 * Author URI:        http://www.fugu.fr
 * Text Domain:       maintenance-switch-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Path of the maintenance.php file.
 * @since    1.0.0
 */
define( 'MS_PHP_FILE_USED', WP_CONTENT_DIR . '/maintenance.php' );

/**
 * Path of the maintenance.php template file.
 * @since    1.0.0
 */
define( 'MS_PHP_FILE_TEMPLATE', WP_PLUGIN_DIR . '/maintenance-switch/templates/maintenance.php' );

/**
 * Path of the .maintenance file.
 * @since    1.0.0
 */
define( 'MS_DOT_FILE_USED', ABSPATH . '/.maintenance' );

/**
 * Path of the .maintenance template file.
 * @since    1.0.0
 */
define( 'MS_DOT_FILE_TEMPLATE', WP_PLUGIN_DIR . '/maintenance-switch/templates/.maintenance' );


/*
 * @TODO:
 *
 * - replace `class-maintenance-switch.php` with the name of the plugin's class file
 *
 */
require_once( plugin_dir_path( __FILE__ ) . 'public/class-maintenance-switch.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 * @TODO:
 *
 * - replace Maintenance_Switch with the name of the class defined in
 *   `class-maintenance-switch.php`
 */
register_activation_hook( __FILE__, array( 'Maintenance_Switch', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Maintenance_Switch', 'deactivate' ) );

/*
 * @TODO:
 *
 * - replace Maintenance_Switch with the name of the class defined in
 *   `class-maintenance-switch.php`
 */
add_action( 'plugins_loaded', array( 'Maintenance_Switch', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * - replace `class-maintenance-switch-admin.php` with the name of the plugin's admin file
 * - replace Maintenance_Switch_Admin with the name of the class defined in
 *   `class-maintenance-switch-admin.php`
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
// if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-maintenance-switch-admin.php' );
	add_action( 'plugins_loaded', array( 'Maintenance_Switch_Admin', 'get_instance' ) );

}

