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
 * Version:           1.7.1
 * Author:            Fugu
 * Author URI:        http://www.fugu.fr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       maintenance-switch
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Path of the maintenance.php file.
 * @since    1.0.0
 */
define('MS_SLUG', 'maintenance-switch');

/**
 * Path of the maintenance.php file.
 * @since    1.3.6
 */
define('PLUGIN_VERSION', '1.7.1');

/**
 * The config file
 */
require_once plugin_dir_path(__FILE__) . 'includes/config.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-maintenance-switch-activator.php
 * @since 1.0.0
 */
function maintenance_switch_activate()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-maintenance-switch-activator.php';
	Maintenance_Switch_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-maintenance-switch-deactivator.php
 * @since 1.0.0
 */
function maintenance_switch_deactivate()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-maintenance-switch-deactivator.php';
	Maintenance_Switch_Deactivator::deactivate();
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function maintenance_switch_run()
{
	$plugin = new Maintenance_Switch();
	$plugin->run();
}

/**
 * Backward compatibility wrappers for legacy function names.
 * These ensure smooth updates for existing installations.
 * @since 1.0.0
 * @deprecated Will be removed in a future version.
 */
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Backward compatibility wrapper
function activate_maintenance_switch() {
	maintenance_switch_activate();
}

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Backward compatibility wrapper
function deactivate_maintenance_switch() {
	maintenance_switch_deactivate();
}

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Backward compatibility wrapper
function run_maintenance_switch() {
	maintenance_switch_run();
}

// Register hooks with new function names
register_activation_hook(__FILE__, 'maintenance_switch_activate');
register_deactivation_hook(__FILE__, 'maintenance_switch_deactivate');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-maintenance-switch.php';

// Start the plugin
maintenance_switch_run();
