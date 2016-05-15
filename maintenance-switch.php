<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.fugu.fr
 * @since             1.0.0
 * @package           Maintenance_Switch
 *
 * @wordpress-plugin
 * Plugin Name:       Maintenance Switch
 * Plugin URI:        https://wordpress.org/plugins/maintenance-switch
 * Description:       Customize easily and switch in one-click to (native) maintenance mode from your backend or frontend.
 * Version:           1.3.5
 * Author:            Fugu
 * Author URI:        http://www.fugu.fr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       maintenance-switch
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The config file
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/config.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-maintenance-switch-activator.php
 */
function activate_maintenance_switch() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-maintenance-switch-activator.php';
	Maintenance_Switch_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-maintenance-switch-deactivator.php
 */
function deactivate_maintenance_switch() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-maintenance-switch-deactivator.php';
	Maintenance_Switch_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_maintenance_switch' );
register_deactivation_hook( __FILE__, 'deactivate_maintenance_switch' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-maintenance-switch.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_maintenance_switch() {

	$plugin = new Maintenance_Switch();
	$plugin->run();

}
run_maintenance_switch();
